<?php

namespace App\Controllers;

use App\Models\ClassModel;
use App\Models\ExamModel;
use App\Models\ExamResultModel;
use App\Models\ExamSubjectMarkModel;
use App\Models\ExamSubjectModel;
use App\Models\SessionModel;
use App\Models\StudentModel;
use App\Models\StudentSessionModel;
use CodeIgniter\Cache\CacheInterface;
use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Model;

class DataAnalyticsController extends BaseController
{
    protected CacheInterface $cache;
    protected int $analyticsCacheTTL = 300;
    protected StudentModel $students;
    protected ClassModel $classes;
    protected SessionModel $sessions;
    protected ExamModel $exams;
    protected ExamResultModel $examResults;
    protected ExamSubjectModel $examSubjects;
    protected ExamSubjectMarkModel $examSubjectMarks;
    protected StudentSessionModel $studentSessions;

    public function __construct()
    {
        $this->students = new StudentModel();
        $this->classes = new ClassModel();
        $this->sessions = new SessionModel();
        $this->exams = new ExamModel();
        $this->examResults = new ExamResultModel();
        $this->examSubjects = new ExamSubjectModel();
        $this->examSubjectMarks = new ExamSubjectMarkModel();
        $this->studentSessions = new StudentSessionModel();
        $this->cache = service('cache');
    }

    public function index()
    {
        return view('analytics/index');
    }

    public function overview()
    {
        return $this->response->setJSON($this->getAnalyticsSnapshot());
    }

    protected function studentAnalytics(): array
    {
        $total = $this->countRows($this->students);
        $active = $this->countRows($this->students, static function (BaseBuilder $builder): void {
            $builder->where('is_active', 'yes');
        });
        $inactive = max($total - $active, 0);

        $genderBreakdownRows = $this->students->builder()
            ->select('gender, COUNT(*) AS total')
            ->groupBy('gender')
            ->get()
            ->getResultArray();

        $genderBreakdown = $this->normalizeBreakdown($genderBreakdownRows, 'gender', 'unspecified');

        $recentStudents = $this->students->builder()
            ->select('id, firstname, middlename, lastname, admission_no, created_at')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        $recent = array_map(static function (array $row): array {
            $first = trim((string) ($row['firstname'] ?? ''));
            $middle = trim((string) ($row['middlename'] ?? ''));
            $last = trim((string) ($row['lastname'] ?? ''));

            $nameParts = array_filter([$first, $middle, $last]);

            return [
                'student_id' => $row['id'] ?? null,
                'full_name' => $nameParts ? implode(' ', $nameParts) : null,
                'admission_no' => $row['admission_no'] ?? null,
                'created_at' => $row['created_at'] ?? null,
            ];
        }, $recentStudents);

        $totalEnrollments = $this->countRows($this->studentSessions);
        $activeEnrollments = $this->countRows($this->studentSessions, static function (BaseBuilder $builder): void {
            $builder->where('is_active', 'yes');
        });

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'gender_breakdown' => $genderBreakdown,
            'enrollments' => [
                'total' => $totalEnrollments,
                'active' => $activeEnrollments,
            ],
            'recent_registrations' => $recent,
        ];
    }

    protected function classAnalytics(): array
    {
        $total = $this->countRows($this->classes);
        $active = $this->countRows($this->classes, static function (BaseBuilder $builder): void {
            $builder->where('is_active', 'yes');
        });

        $distributionRows = $this->studentSessions->builder()
            ->select('classes.id AS class_id, classes.class AS class_name, COUNT(student_session.id) AS total_students')
            ->join('classes', 'classes.id = student_session.class_id', 'left')
            ->groupBy('classes.id, classes.class')
            ->orderBy('total_students', 'DESC')
            ->get()
            ->getResultArray();

        $distribution = array_map(static function (array $row): array {
            $className = trim((string) ($row['class_name'] ?? ''));
            if ($className === '') {
                $className = 'Unassigned';
            }

            return [
                'class_id' => $row['class_id'] ?? null,
                'class_name' => $className,
                'students' => (int) ($row['total_students'] ?? 0),
            ];
        }, $distributionRows);

        return [
            'total' => $total,
            'active' => $active,
            'student_distribution' => $distribution,
        ];
    }

    protected function sessionAnalytics(): array
    {
        $total = $this->countRows($this->sessions);

        $activeSessions = $this->sessions->builder()
            ->select('id, session, is_active, created_at, updated_at')
            ->where('is_active', 'yes')
            ->orderBy('created_at', 'DESC')
            ->get()
            ->getResultArray();

        $current = $activeSessions[0] ?? null;

        $recentSessions = $this->sessions->builder()
            ->select('id, session, is_active, created_at')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        $recent = array_map(static function (array $row): array {
            return [
                'session_id' => $row['id'] ?? null,
                'name' => $row['session'] ?? null,
                'is_active' => ($row['is_active'] ?? null) === 'yes',
                'created_at' => $row['created_at'] ?? null,
            ];
        }, $recentSessions);

        return [
            'total' => $total,
            'active_count' => count($activeSessions),
            'current' => $current ? [
                'session_id' => $current['id'] ?? null,
                'name' => $current['session'] ?? null,
                'since' => $current['created_at'] ?? null,
            ] : null,
            'recent' => $recent,
        ];
    }

    protected function examAnalytics(): array
    {
        $total = $this->countRows($this->exams);
        $active = $this->countRows($this->exams, static function (BaseBuilder $builder): void {
            $builder->where('is_active', 'yes');
        });

        $recentExamRows = $this->exams->builder()
            ->select('tz_exams.id, tz_exams.exam_name, tz_exams.exam_date, tz_exams.is_active, sessions.session AS session_name')
            ->join('sessions', 'sessions.id = tz_exams.session_id', 'left')
            ->orderBy('tz_exams.exam_date', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        $recentExams = array_map(static function (array $row): array {
            return [
                'exam_id' => $row['id'] ?? null,
                'name' => $row['exam_name'] ?? null,
                'date' => $row['exam_date'] ?? null,
                'session' => $row['session_name'] ?? null,
                'is_active' => ($row['is_active'] ?? null) === 'yes',
            ];
        }, $recentExamRows);

        $resultsSummary = $this->examResults->builder()
            ->select('COUNT(*) AS total_results, AVG(total_points) AS avg_points')
            ->get()
            ->getRowArray() ?? ['total_results' => 0, 'avg_points' => null];

        $totalResults = (int) ($resultsSummary['total_results'] ?? 0);
        $avgPoints = $totalResults > 0 && isset($resultsSummary['avg_points'])
            ? (float) $resultsSummary['avg_points']
            : null;

        $divisionRows = $this->examResults->builder()
            ->select('division, COUNT(*) AS total')
            ->groupBy('division')
            ->get()
            ->getResultArray();

        $divisionBreakdown = $this->normalizeBreakdown($divisionRows, 'division', 'unspecified');

        $recentResultRows = $this->examResults->builder()
            ->select('tz_exam_results.id, tz_exam_results.exam_id, tz_exam_results.total_points, tz_exam_results.division, tz_exam_results.created_at, students.firstname, students.lastname, tz_exams.exam_name')
            ->join('students', 'students.id = tz_exam_results.student_id', 'left')
            ->join('tz_exams', 'tz_exams.id = tz_exam_results.exam_id', 'left')
            ->orderBy('tz_exam_results.created_at', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        $recentResults = array_map(static function (array $row): array {
            $first = trim((string) ($row['firstname'] ?? ''));
            $last = trim((string) ($row['lastname'] ?? ''));
            $fullName = trim($first . ' ' . $last);

            return [
                'result_id' => $row['id'] ?? null,
                'exam_id' => $row['exam_id'] ?? null,
                'exam_name' => $row['exam_name'] ?? null,
                'student_name' => $fullName !== '' ? $fullName : null,
                'total_points' => isset($row['total_points']) ? (float) $row['total_points'] : null,
                'division' => $row['division'] ?? null,
                'created_at' => $row['created_at'] ?? null,
            ];
        }, $recentResultRows);

        return [
            'total' => $total,
            'active' => $active,
            'results' => [
                'count' => $totalResults,
                'average_points' => $avgPoints,
                'division_breakdown' => $divisionBreakdown,
            ],
            'recent_exams' => $recentExams,
            'recent_results' => $recentResults,
        ];
    }

    protected function subjectAnalytics(): array
    {
        $subjectCount = $this->countRows($this->examSubjects);

        $subjectRows = $this->examSubjectMarks->builder()
            ->select('tz_exam_subjects.subject_name, AVG(tz_exam_subject_marks.marks_obtained) AS avg_marks, MAX(tz_exam_subject_marks.marks_obtained) AS top_mark, COUNT(tz_exam_subject_marks.id) AS attempts')
            ->join('tz_exam_subjects', 'tz_exam_subjects.id = tz_exam_subject_marks.exam_subject_id', 'left')
            ->groupBy('tz_exam_subject_marks.exam_subject_id, tz_exam_subjects.subject_name')
            ->orderBy('avg_marks', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        $performance = array_map(static function (array $row): array {
            return [
                'subject' => $row['subject_name'] ?? 'Unknown',
                'average_mark' => isset($row['avg_marks']) ? (float) $row['avg_marks'] : null,
                'best_mark' => isset($row['top_mark']) ? (float) $row['top_mark'] : null,
                'attempts' => (int) ($row['attempts'] ?? 0),
            ];
        }, $subjectRows);

        return [
            'total_subjects' => $subjectCount,
            'top_subjects' => $performance,
        ];
    }

    protected function topStudents(int $limit = 5): array
    {
        $topRows = $this->examResults->builder()
            ->select('students.id AS student_id, students.firstname, students.lastname, tz_exam_results.total_points, tz_exam_results.division, tz_exams.exam_name')
            ->join('students', 'students.id = tz_exam_results.student_id', 'left')
            ->join('tz_exams', 'tz_exams.id = tz_exam_results.exam_id', 'left')
            ->orderBy('tz_exam_results.total_points', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();

        return array_map(static function (array $row): array {
            $first = trim((string) ($row['firstname'] ?? ''));
            $last = trim((string) ($row['lastname'] ?? ''));
            $fullName = trim($first . ' ' . $last);

            return [
                'student_id' => $row['student_id'] ?? null,
                'student_name' => $fullName !== '' ? $fullName : null,
                'total_points' => isset($row['total_points']) ? (float) $row['total_points'] : null,
                'division' => $row['division'] ?? null,
                'exam_name' => $row['exam_name'] ?? null,
            ];
        }, $topRows);
    }

    /**
     * @param callable(BaseBuilder):void|null $configure
     */
    protected function countRows(Model $model, ?callable $configure = null): int
    {
        $builder = $model->builder();

        if ($configure !== null) {
            $configure($builder);
        }

        return (int) $builder->countAllResults();
    }

    protected function normalizeBreakdown(array $rows, string $keyField, string $fallbackLabel): array
    {
        $result = [];

        foreach ($rows as $row) {
            $key = trim((string) ($row[$keyField] ?? ''));
            if ($key === '') {
                $key = $fallbackLabel;
            }

            $result[$key] = (int) ($row['total'] ?? 0);
        }

        return $result;
    }

    protected function getAnalyticsSnapshot(): array
    {
        $cacheKey = $this->analyticsCacheKey();
        $cached = $this->cache->get($cacheKey);

        if (is_array($cached)) {
            return $cached;
        }

        $snapshot = [
            'students' => $this->studentAnalytics(),
            'classes' => $this->classAnalytics(),
            'sessions' => $this->sessionAnalytics(),
            'exams' => $this->examAnalytics(),
            'subjects' => $this->subjectAnalytics(),
            'top_students' => $this->topStudents(),
        ];

        $this->cache->save($cacheKey, $snapshot, $this->analyticsCacheTTL);

        return $snapshot;
    }

    protected function analyticsCacheKey(): string
    {
        $schoolId = $this->getCurrentSchoolId() ?? 'global';

        return 'analytics.overview.' . $schoolId;
    }
}


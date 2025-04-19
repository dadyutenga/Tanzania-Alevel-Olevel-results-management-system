<?php

namespace App\Controllers\Alevel;

use App\Controllers\BaseController;
use App\Models\ExamModel;
use App\Models\AlevelSubjectMarksModel;
use App\Models\AlevelExamResultModel;
use App\Models\StudentModel;
use App\Models\StudentSessionModel;
use App\Models\ClassModel;
use App\Models\SessionModel;
use App\Models\AlevelCombinationModel;
use CodeIgniter\API\ResponseTrait;

class ViewAlevelResultsController extends BaseController
{
    use ResponseTrait;

    protected $examModel;
    protected $alevelSubjectMarksModel;
    protected $alevelExamResultModel;
    protected $studentModel;
    protected $studentSessionModel;
    protected $classModel;
    protected $sessionModel;
    protected $alevelCombinationModel;

    public function __construct()
    {
        $this->examModel = new ExamModel();
        $this->alevelSubjectMarksModel = new AlevelSubjectMarksModel();
        $this->alevelExamResultModel = new AlevelExamResultModel();
        $this->studentModel = new StudentModel();
        $this->studentSessionModel = new StudentSessionModel();
        $this->classModel = new ClassModel();
        $this->sessionModel = new SessionModel();
        $this->alevelCombinationModel = new AlevelCombinationModel();
    }

    public function fetchResults($classId = null, $sessionId = null, $examId = null, $combinationId = null)
    {
        try {
            $query = $this->studentModel
                ->select('
                    students.id AS student_id,
                    CONCAT(students.firstname, " ", COALESCE(students.middlename, ""), " ", students.lastname) AS full_name,
                    classes.class AS class_name,
                    tz_alevel_combinations.combination_code,
                    tz_alevel_combinations.combination_name,
                    tz_exams.exam_name,
                    tz_alevel_exam_results.total_points,
                    tz_alevel_exam_results.division
                ')
                ->join('student_session', 'students.id = student_session.student_id')
                ->join('classes', 'student_session.class_id = classes.id')
                ->join('tz_student_alevel_combinations', 'tz_student_alevel_combinations.class_id = student_session.class_id AND tz_student_alevel_combinations.session_id = student_session.session_id')
                ->join('tz_alevel_combinations', 'tz_alevel_combinations.id = tz_student_alevel_combinations.combination_id')
                ->join('tz_alevel_exam_combinations', 'tz_alevel_exam_combinations.class_id = student_session.class_id AND tz_alevel_exam_combinations.session_id = student_session.session_id AND tz_alevel_exam_combinations.combination_id = tz_alevel_combinations.id')
                ->join('tz_exams', 'tz_exams.id = tz_alevel_exam_combinations.exam_id')
                ->join('tz_alevel_exam_results', 'tz_alevel_exam_results.student_id = students.id 
                    AND tz_alevel_exam_results.exam_id = tz_exams.id
                    AND tz_alevel_exam_results.class_id = student_session.class_id
                    AND tz_alevel_exam_results.session_id = student_session.session_id
                    AND tz_alevel_exam_results.combination_id = tz_alevel_combinations.id')
                ->where('students.is_active', 'yes')
                ->where('tz_exams.is_active', 'yes')
                ->where('tz_alevel_combinations.is_active', 'yes')
                ->where('tz_alevel_exam_combinations.is_active', 'yes')
                ->where('tz_alevel_exam_results.is_active', 'yes');

            // Add conditional filters
            if ($classId) {
                $query->where('student_session.class_id', $classId);
            }
            if ($sessionId) {
                $query->where('student_session.session_id', $sessionId);
            }
            if ($examId) {
                $query->where('tz_exams.id', $examId);
            }
            if ($combinationId) {
                $query->where('tz_alevel_combinations.id', $combinationId);
            }

            $results = $query->orderBy('full_name')
                            ->orderBy('tz_exams.exam_name')
                            ->findAll();

            return [
                'status' => 'success',
                'data' => $results
            ];

        } catch (\Exception $e) {
            log_message('error', '[ViewAlevelResultsController.fetchResults] Error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Failed to fetch A-level results: ' . $e->getMessage()
            ];
        }
    }

    public function showResultsPage()
    {
        try {
            $data = [
                'sessions' => $this->sessionModel->findAll(),
                'classes' => $this->classModel->findAll(),
                'combinations' => $this->alevelCombinationModel->where('is_active', 'yes')->findAll()
            ];
            
            return view('alevel/ViewAlevelResults', $data);
        } catch (\Exception $e) {
            log_message('error', '[ViewAlevelResultsController.showResultsPage] Error: ' . $e->getMessage());
            return redirect()->to(base_url('dashboard'))
                ->with('error', 'Failed to load A-level results page');
        }
    }

    public function getExams()
    {
        try {
            $sessionId = $this->request->getGet('session_id');
            $classId = $this->request->getGet('class_id');
            $combinationId = $this->request->getGet('combination_id');
            
            if (!$sessionId || !$classId || !$combinationId) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Session ID, Class ID, and Combination ID are required'
                ]);
            }
    
            $exams = $this->examModel
                ->select('
                    tz_exams.id AS exam_id,
                    tz_exams.exam_name,
                    tz_exams.exam_date
                ')
                ->join('tz_alevel_exam_combinations', 'tz_alevel_exam_combinations.exam_id = tz_exams.id')
                ->where('tz_exams.session_id', $sessionId)
                ->where('tz_alevel_exam_combinations.class_id', $classId)
                ->where('tz_alevel_exam_combinations.combination_id', $combinationId)
                ->where('tz_exams.is_active', 'yes')
                ->where('tz_alevel_exam_combinations.is_active', 'yes')
                ->orderBy('tz_exams.exam_date', 'DESC')
                ->findAll();
    
            return $this->respond([
                'status' => 'success',
                'data' => $exams
            ]);
    
        } catch (\Exception $e) {
            log_message('error', '[ViewAlevelResultsController.getExams] Error: ' . $e->getMessage());
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch exams'
            ]);
        }
    }

    public function getFilteredResults()
    {
        try {
            $classId = $this->request->getPost('class_id');
            $sessionId = $this->request->getPost('session_id');
            $examId = $this->request->getPost('exam_id');
            $combinationId = $this->request->getPost('combination_id');
            
            if (!$classId || !$sessionId || !$examId || !$combinationId) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Class, Session, Exam, and Combination are required'
                ]);
            }

            $results = $this->fetchResults($classId, $sessionId, $examId, $combinationId);
            return $this->respond($results);

        } catch (\Exception $e) {
            log_message('error', '[ViewAlevelResultsController.getFilteredResults] Error: ' . $e->getMessage());
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch filtered A-level results'
            ]);
        }
    }

    public function getStudentSubjectMarks()
    {
        try {
            $studentId = $this->request->getPost('student_id');
            $examId = $this->request->getPost('exam_id');
            
            if (!$studentId || !$examId) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Student ID and Exam ID are required'
                ]);
            }

            $subjectMarks = $this->alevelSubjectMarksModel
                ->select('
                    tz_alevel_combination_subjects.subject_name,
                    tz_alevel_subject_marks.marks_obtained
                ')
                ->join('tz_alevel_combination_subjects', 'tz_alevel_combination_subjects.id = tz_alevel_subject_marks.subject_id')
                ->where('tz_alevel_subject_marks.student_id', $studentId)
                ->where('tz_alevel_subject_marks.exam_id', $examId)
                ->where('tz_alevel_combination_subjects.subject_type', 'major')
                ->where('tz_alevel_combination_subjects.is_active', 'yes')
                ->findAll();

            // Calculate grades using ACSEE scale
            foreach ($subjectMarks as &$mark) {
                $mark['grade'] = $this->calculateGrade($mark['marks_obtained']);
            }

            return $this->respond([
                'status' => 'success',
                'data' => $subjectMarks
            ]);

        } catch (\Exception $e) {
            log_message('error', '[ViewAlevelResultsController.getStudentSubjectMarks] Error: ' . $e->getMessage());
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch A-level subject marks'
            ]);
        }
    }

    private function calculateGrade($marks)
    {
        // ACSEE grading scale for A-level
        if ($marks >= 80) {
            return 'A';
        } elseif ($marks >= 70) {
            return 'B';
        } elseif ($marks >= 60) {
            return 'C';
        } elseif ($marks >= 50) {
            return 'D';
        } elseif ($marks >= 40) {
            return 'E';
        } elseif ($marks >= 30) {
            return 'S';
        } else {
            return 'F';
        }
    }

    public function generateResultsPDF($examId, $classId, $sessionId, $combinationId)
    {
        try {
            // Get all results for the selected exam, class, session, and combination
            $results = $this->fetchResults($classId, $sessionId, $examId, $combinationId)['data'];
            
            $pdfData = [];
            foreach ($results as $result) {
                // Get subject marks for each student
                $subjectMarks = $this->alevelSubjectMarksModel
                    ->select('
                        tz_alevel_combination_subjects.subject_name,
                        tz_alevel_subject_marks.marks_obtained
                    ')
                    ->join('tz_alevel_combination_subjects', 'tz_alevel_combination_subjects.id = tz_alevel_subject_marks.subject_id')
                    ->where('tz_alevel_subject_marks.student_id', $result['student_id'])
                    ->where('tz_alevel_subject_marks.exam_id', $examId)
                    ->where('tz_alevel_combination_subjects.subject_type', 'major')
                    ->where('tz_alevel_combination_subjects.is_active', 'yes')
                    ->findAll();

                // Calculate grades for each subject
                foreach ($subjectMarks as &$mark) {
                    $mark['grade'] = $this->calculateGrade($mark['marks_obtained']);
                }

                $pdfData[] = [
                    'student_name' => $result['full_name'],
                    'class' => $result['class_name'],
                    'combination' => $result['combination_code'],
                    'total_points' => $result['total_points'],
                    'division' => $result['division'],
                    'subjects' => $subjectMarks
                ];
            }

            return [
                'status' => 'success',
                'data' => $pdfData
            ];

        } catch (\Exception $e) {
            log_message('error', '[ViewAlevelResultsController.generateResultsPDF] Error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Failed to generate A-level PDF data'
            ];
        }
    }

    public function downloadResultPDF($studentId, $examId)
    {
        try {
            // Get student details and marks
            $studentMarks = $this->alevelSubjectMarksModel
                ->select('
                    students.id AS student_id,
                    CONCAT(students.firstname, " ", COALESCE(students.middlename, ""), " ", students.lastname) AS full_name,
                    classes.class AS class_name,
                    tz_alevel_combinations.combination_code,
                    tz_alevel_combinations.combination_name,
                    tz_exams.exam_name,
                    tz_alevel_combination_subjects.subject_name,
                    tz_alevel_subject_marks.marks_obtained,
                    tz_alevel_exam_results.total_points,
                    tz_alevel_exam_results.division
                ')
                ->join('students', 'students.id = tz_alevel_subject_marks.student_id')
                ->join('student_session', 'students.id = student_session.student_id')
                ->join('classes', 'student_session.class_id = classes.id')
                ->join('tz_student_alevel_combinations', 'tz_student_alevel_combinations.class_id = student_session.class_id AND tz_student_alevel_combinations.session_id = student_session.session_id AND tz_student_alevel_combinations.student_id = students.id')
                ->join('tz_alevel_combinations', 'tz_alevel_combinations.id = tz_student_alevel_combinations.combination_id')
                ->join('tz_exams', 'tz_exams.id = tz_alevel_subject_marks.exam_id')
                ->join('tz_alevel_combination_subjects', 'tz_alevel_combination_subjects.id = tz_alevel_subject_marks.subject_id')
                ->join('tz_alevel_exam_results', 'tz_alevel_exam_results.student_id = students.id AND tz_alevel_exam_results.exam_id = tz_exams.id')
                ->where('students.id', $studentId)
                ->where('tz_alevel_subject_marks.exam_id', $examId)
                ->where('tz_alevel_combination_subjects.subject_type', 'major')
                ->where('tz_alevel_combination_subjects.is_active', 'yes')
                ->findAll();

            if (empty($studentMarks)) {
                return [
                    'status' => 'error',
                    'message' => 'No A-level results found for this student'
                ];
            }

            // Process grades for each subject
            foreach ($studentMarks as &$mark) {
                $mark['grade'] = $this->calculateGrade($mark['marks_obtained']);
            }

            return [
                'status' => 'success',
                'data' => $studentMarks
            ];

        } catch (\Exception $e) {
            log_message('error', '[ViewAlevelResultsController.downloadResultPDF] Error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Failed to generate A-level PDF'
            ];
        }
    }
}
?>
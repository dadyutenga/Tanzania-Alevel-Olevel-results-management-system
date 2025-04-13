<?php

namespace App\Controllers;

use App\Models\ExamModel;
use App\Models\ExamSubjectModel;
use App\Models\ExamSubjectMarkModel;
use App\Models\ExamResultModel;
use App\Models\StudentModel;
use App\Models\StudentSessionModel;
use App\Models\ClassModel;
use App\Models\ClassSectionModel;
use App\Models\SessionModel;
use CodeIgniter\RESTful\ResourceController;

class StudenResultController extends ResourceController
{
    protected $examModel;
    protected $examSubjectModel;
    protected $examSubjectMarkModel;
    protected $examResultModel;
    protected $studentModel;
    protected $studentSessionModel;
    protected $classModel;
    protected $classSectionModel;
    protected $sessionModel;

    public function __construct()
    {
        $this->examModel = new ExamModel();
        $this->examSubjectModel = new ExamSubjectModel();
        $this->examSubjectMarkModel = new ExamSubjectMarkModel();
        $this->examResultModel = new ExamResultModel();
        $this->studentModel = new StudentModel();
        $this->studentSessionModel = new StudentSessionModel();
        $this->classModel = new ClassModel();
        $this->classSectionModel = new ClassSectionModel();
        $this->sessionModel = new SessionModel();
    }

    public function fetchStudentResults($classId = null, $sessionId = null, $examId = null, $studentName = null)
    {
        try {
            $query = $this->studentModel
                ->select('
                    students.id AS student_id,
                    CONCAT(students.firstname, " ", COALESCE(students.middlename, ""), " ", students.lastname) AS full_name,
                    classes.class AS class_name,
                    class_sections.section_id AS section,
                    tz_exams.exam_name,
                    tz_exam_results.total_points,
                    tz_exam_results.division
                ')
                ->join('student_session', 'students.id = student_session.student_id')
                ->join('classes', 'student_session.class_id = classes.id')
                ->join('class_sections', 'student_session.section_id = class_sections.section_id AND student_session.class_id = class_sections.class_id')
                ->join('tz_exam_classes', 'tz_exam_classes.class_id = student_session.class_id AND tz_exam_classes.session_id = student_session.session_id')
                ->join('tz_exams', 'tz_exams.id = tz_exam_classes.exam_id')
                ->join('tz_exam_results', 'tz_exam_results.student_id = students.id 
                    AND tz_exam_results.exam_id = tz_exams.id
                    AND tz_exam_results.class_id = student_session.class_id
                    AND tz_exam_results.session_id = student_session.session_id')
                ->where('students.is_active', 'yes')
                ->where('tz_exams.is_active', 'yes');

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
            if ($studentName) {
                $query->groupStart()
                    ->like('students.firstname', $studentName)
                    ->orLike('students.middlename', $studentName)
                    ->orLike('students.lastname', $studentName)
                    ->orLike('CONCAT(students.firstname, " ", students.lastname)', $studentName)
                    ->orLike('CONCAT(students.firstname, " ", COALESCE(students.middlename, ""), " ", students.lastname)', $studentName)
                    ->groupEnd();
            }

            $results = $query->orderBy('full_name')
                            ->orderBy('tz_exams.exam_name')
                            ->findAll();

            return [
                'status' => 'success',
                'data' => $results
            ];

        } catch (\Exception $e) {
            log_message('error', '[StudenResultController.fetchStudentResults] Error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Failed to fetch student results: ' . $e->getMessage()
            ];
        }
    }

    public function showStudentResultsPage()
    {
        try {
            $data = [
                'sessions' => $this->sessionModel->findAll(),
                'classes' => $this->classModel->findAll()
            ];
            
            return view('public/SearchResultsPublic', $data);
        } catch (\Exception $e) {
            log_message('error', '[StudenResultController.showStudentResultsPage] Error: ' . $e->getMessage());
            return redirect()->to(base_url('public/results'))
                ->with('error', 'Failed to load student results page');
        }
    }

    public function getExams()
    {
        try {
            $sessionId = $this->request->getGet('session_id');
            $classId = $this->request->getGet('class_id');
            
            if (!$sessionId || !$classId) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Both session_id and class_id are required'
                ]);
            }
    
            $exams = $this->examModel
                ->select('
                    tz_exams.id AS exam_id,
                    tz_exams.exam_name,
                    tz_exams.exam_date
                ')
                ->join('tz_exam_classes', 'tz_exam_classes.exam_id = tz_exams.id')
                ->where('tz_exams.session_id', $sessionId)
                ->where('tz_exam_classes.class_id', $classId)
                ->where('tz_exams.is_active', 'yes')
                ->orderBy('tz_exams.exam_date', 'DESC')
                ->findAll();
    
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $exams
            ]);
    
        } catch (\Exception $e) {
            log_message('error', '[StudenResultController.getExams] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to fetch exams'
            ]);
        }
    }

    public function getFilteredStudentResults()
    {
        try {
            $classId = $this->request->getPost('class_id');
            $sessionId = $this->request->getPost('session_id');
            $examId = $this->request->getPost('exam_id');
            $studentName = $this->request->getPost('student_name');
            
            if (!$classId || !$sessionId || !$examId) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Class, Session, and Exam are required'
                ]);
            }

            $results = $this->fetchStudentResults($classId, $sessionId, $examId, $studentName);
            return $this->response->setJSON($results);

        } catch (\Exception $e) {
            log_message('error', '[StudenResultController.getFilteredStudentResults] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to fetch filtered student results'
            ]);
        }
    }

    public function getStudentSubjectMarks()
    {
        try {
            $studentId = $this->request->getPost('student_id');
            $examId = $this->request->getPost('exam_id');
            
            if (!$studentId || !$examId) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Student ID and Exam ID are required'
                ]);
            }

            $subjectMarks = $this->examSubjectMarkModel
                ->select('
                    tz_exam_subjects.subject_name,
                    tz_exam_subjects.max_marks,
                    tz_exam_subjects.passing_marks,
                    tz_exam_subject_marks.marks_obtained
                ')
                ->join('tz_exam_subjects', 'tz_exam_subjects.id = tz_exam_subject_marks.exam_subject_id')
                ->where('tz_exam_subject_marks.student_id', $studentId)
                ->where('tz_exam_subject_marks.exam_id', $examId)
                ->findAll();

            // Convert marks to grades
            foreach ($subjectMarks as &$mark) {
                $percentage = ($mark['marks_obtained'] / $mark['max_marks']) * 100;
                $mark['grade'] = $this->calculateGrade($percentage);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'data' => $subjectMarks
            ]);

        } catch (\Exception $e) {
            log_message('error', '[StudenResultController.getStudentSubjectMarks] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to fetch subject marks'
            ]);
        }
    }

    public function getReportCard()
    {
        try {
            $studentId = $this->request->getPost('student_id');
            $examId = $this->request->getPost('exam_id');
            
            if (!$studentId || !$examId) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Student ID and Exam ID are required'
                ]);
            }

            // Fetch student details including photo
            $studentData = $this->studentModel
                ->select('
                    students.id AS student_id,
                    CONCAT(students.firstname, " ", COALESCE(students.middlename, ""), " ", students.lastname) AS full_name,
                    students.image AS student_photo,
                    classes.class AS class_name,
                    class_sections.section_id AS section
                ')
                ->join('student_session', 'students.id = student_session.student_id')
                ->join('classes', 'student_session.class_id = classes.id')
                ->join('class_sections', 'student_session.section_id = class_sections.section_id AND student_session.class_id = class_sections.class_id')
                ->where('students.id', $studentId)
                ->first();

            if (!$studentData) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Student not found'
                ]);
            }

            // Fetch subject marks
            $subjectMarks = $this->examSubjectMarkModel
                ->select('
                    tz_exam_subjects.subject_name,
                    tz_exam_subjects.max_marks,
                    tz_exam_subjects.passing_marks,
                    tz_exam_subject_marks.marks_obtained
                ')
                ->join('tz_exam_subjects', 'tz_exam_subjects.id = tz_exam_subject_marks.exam_subject_id')
                ->where('tz_exam_subject_marks.student_id', $studentId)
                ->where('tz_exam_subject_marks.exam_id', $examId)
                ->findAll();

            // Convert marks to grades
            foreach ($subjectMarks as &$mark) {
                $percentage = ($mark['marks_obtained'] / $mark['max_marks']) * 100;
                $mark['grade'] = $this->calculateGrade($percentage);
            }

            // Fetch total points and division
            $examResult = $this->examResultModel
                ->where('student_id', $studentId)
                ->where('exam_id', $examId)
                ->first();

            // Generate PDF directly
            $pdfController = new PDFController();
            return $pdfController->generateStudentReportCard($studentData, $subjectMarks, $examResult);

        } catch (\Exception $e) {
            log_message('error', '[StudenResultController.getReportCard] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to generate report card'
            ]);
        }
    }

    private function calculateGrade($percentage)
    {
        $gradeScale = [
            ['min' => 75, 'grade' => 'A', 'points' => 1],
            ['min' => 65, 'grade' => 'B', 'points' => 2],
            ['min' => 45, 'grade' => 'C', 'points' => 3],
            ['min' => 30, 'grade' => 'D', 'points' => 4],
            ['min' => 0, 'grade' => 'F', 'points' => 5]
        ];

        foreach ($gradeScale as $grade) {
            if ($percentage >= $grade['min']) {
                return $grade['grade'];
            }
        }
        return 'F';
    }
}

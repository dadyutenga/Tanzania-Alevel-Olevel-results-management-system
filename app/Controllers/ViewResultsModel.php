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

class ViewResultsModel extends ResourceController
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

    public function fetchResults($classId = null, $sessionId = null, $examId = null)
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

            $results = $query->orderBy('full_name')
                            ->orderBy('tz_exams.exam_name')
                            ->findAll();

            return [
                'status' => 'success',
                'data' => $results
            ];

        } catch (\Exception $e) {
            log_message('error', '[ViewResults.fetchResults] Error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Failed to fetch results: ' . $e->getMessage()
            ];
        }
    }

    public function showResultsPage()
    {
        try {
            $data = [
                'sessions' => $this->sessionModel->findAll(),
                'classes' => $this->classModel->findAll()
            ];
            
            return view('results/ViewResults', $data);
        } catch (\Exception $e) {
            log_message('error', '[ViewResults.showResultsPage] Error: ' . $e->getMessage());
            return redirect()->to(base_url('dashboard'))
                ->with('error', 'Failed to load results page');
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
            log_message('error', '[ViewResults.getExams] Error: ' . $e->getMessage());
            return $this->response->setJSON([
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
            // Remove level_id check since it's not used
            
            if (!$classId || !$sessionId || !$examId) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Class, Session and Exam are required'
                ]);
            }

            $results = $this->fetchResults($classId, $sessionId, $examId);
            return $this->response->setJSON($results);

        } catch (\Exception $e) {
            log_message('error', '[ViewResults.getFilteredResults] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to fetch filtered results'
            ]);
        }
    }
}
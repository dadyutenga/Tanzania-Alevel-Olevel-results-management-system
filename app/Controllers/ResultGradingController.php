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

class ResultGradingController extends ResourceController
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

    public function showPublishPage()
    {
        try {
            $data = [
                'sessions' => $this->sessionModel->where('is_active', 'no')->findAll(),
                'classes' => $this->classModel->where('is_active', 'no')->findAll(),
                'levels' => [
                    ['id' => 4, 'name' => 'O-Level'],
                    ['id' => 6, 'name' => 'A-Level']
                ]
            ];
            
            return view('results/PublishExamResult', $data);
        } catch (\Exception $e) {
            log_message('error', '[ResultGrading.showPublishPage] Error: ' . $e->getMessage());
            return redirect()->to(base_url('dashboard'))
                ->with('error', 'Failed to load result publishing page');
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
                ->select('tz_exams.id, tz_exams.exam_name, tz_exams.exam_date, 
                         classes.id as class_id, classes.class as class_name, 
                         sessions.id as session_id, sessions.session as session_name')
                ->join('tz_exam_classes', 'tz_exam_classes.exam_id = tz_exams.id')
                ->join('classes', 'tz_exam_classes.class_id = classes.id')
                ->join('sessions', 'tz_exams.session_id = sessions.id')
                ->where('tz_exams.session_id', $sessionId)
                ->where('tz_exam_classes.class_id', $classId)
                ->where('tz_exams.is_active', 'yes')
                ->orderBy('tz_exams.exam_date', 'DESC')
                ->orderBy('classes.class', 'ASC')
                ->findAll();
    
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $exams
            ]);
    
        } catch (\Exception $e) {
            log_message('error', '[ResultGrading.getExams] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to fetch exams'
            ]);
        }
    }

    public function getSections($classId)
    {
        try {
            if (!$classId) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Class ID is required'
                ]);
            }

            $sections = $this->classSectionModel
                ->select('class_sections.id, class_sections.section_name')
                ->where('class_id', $classId)
                ->findAll();
    
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $sections
            ]);
    
        } catch (\Exception $e) {
            log_message('error', '[ResultGrading.getSections] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to fetch sections'
            ]);
        }
    }

    public function getExamsBySession($sessionId)
    {
        try {
            if (!$sessionId) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Session ID is required'
                ]);
            }

            $exams = $this->examModel
                ->select('tz_exams.id, tz_exams.exam_name, tz_exams.exam_date')
                ->where('session_id', $sessionId)
                ->where('is_active', 'yes')
                ->orderBy('exam_date', 'DESC')
                ->findAll();
                
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $exams
            ]);
        } catch (\Exception $e) {
            log_message('error', '[ResultGrading.getExamsBySession] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to fetch exams by session'
            ]);
        }
    }
}
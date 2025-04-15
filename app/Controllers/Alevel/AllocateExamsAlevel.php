<?php

namespace App\Controllers\Alevel;

use App\Controllers\BaseController;
use App\Models\ExamModel;
use App\Models\AlevelExamCombinationModel;
use App\Models\ClassModel;
use App\Models\SessionModel;
use App\Models\AlevelCombinationModel;
use CodeIgniter\RESTful\ResourceController;

class AllocateExamsAlevel extends ResourceController
{
    protected $examCombinationModel;
    protected $examModel;
    protected $classModel;
    protected $sessionModel;
    protected $combinationModel;
    protected $format = 'json';

    public function __construct()
    {
        $this->examCombinationModel = new AlevelExamCombinationModel();
        $this->examModel = new ExamModel();
        $this->classModel = new ClassModel();
        $this->sessionModel = new SessionModel();
        $this->combinationModel = new AlevelCombinationModel();
    }

    public function index()
    {
        try {
            $data = [
                'sessions' => $this->sessionModel->where('is_active', 'no')->findAll(),
                'exams' => [],
                'classes' => [],
                'combinations' => []
            ];

            // Get current session if exists
            $currentSession = $this->sessionModel->getCurrentSession();
            if ($currentSession) {
                $data['current_session'] = $currentSession;
                $data['exams'] = $this->examModel
                    ->where('session_id', $currentSession['id'])
                    ->where('is_active', 'yes')
                    ->findAll();
                $data['classes'] = $this->classModel
                    ->where('is_active', 'no')
                    ->findAll();
                $data['combinations'] = $this->combinationModel
                    ->where('is_active', 'yes')
                    ->findAll();
            }

            return view('alevel/AllocateExamsAlevel', $data);
        } catch (\Exception $e) {
            log_message('error', '[AllocateExamsAlevel.index] Exception: ' . $e->getMessage());
            return redirect()->to(base_url('dashboard'))
                ->with('error', 'Failed to load A-Level exam allocation form.');
        }
    }

    public function getExamsBySession($sessionId)
    {
        try {
            $exams = $this->examModel
                ->where('session_id', $sessionId)
                ->where('is_active', 'yes')
                ->findAll();

            return $this->respond([
                'status' => 'success',
                'data' => $exams
            ]);
        } catch (\Exception $e) {
            log_message('error', '[AllocateExamsAlevel.getExamsBySession] Error: ' . $e->getMessage());
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch exams'
            ], 500);
        }
    }

    public function getClassesBySession($sessionId)
    {
        try {
            $db = \Config\Database::connect('second_db');
            $query = $db->query("
                SELECT DISTINCT
                    c.id AS class_id,
                    c.class AS class_name
                FROM 
                    classes c
                JOIN 
                    student_session ss ON ss.class_id = c.id
                WHERE 
                    ss.session_id = ?
                    AND c.is_active = 'no'
                ORDER BY 
                    c.class
            ", [$sessionId]);

            $classes = $query->getResultArray();

            if (empty($classes)) {
                $classes = $this->classModel
                    ->where('is_active', 'no')
                    ->findAll();
            }

            return $this->respond([
                'status' => 'success',
                'data' => $classes
            ]);
        } catch (\Exception $e) {
            log_message('error', '[AllocateExamsAlevel.getClassesBySession] Error: ' . $e->getMessage());
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch classes'
            ], 500);
        }
    }

    public function store()
    {
        try {
            $rules = [
                'exam_id' => 'required|numeric',
                'session_id' => 'required|numeric',
                'class_id' => 'required|numeric',
                'combination_id' => 'required|numeric'
            ];

            if (!$this->validate($rules)) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ], 400);
            }

            $examId = $this->request->getPost('exam_id');
            $classId = $this->request->getPost('class_id');
            $sessionId = $this->request->getPost('session_id');
            $combinationId = $this->request->getPost('combination_id');

            // Check for duplicate allocation
            $existingAllocation = $this->examCombinationModel
                ->where('exam_id', $examId)
                ->where('class_id', $classId)
                ->where('session_id', $sessionId)
                ->where('combination_id', $combinationId)
                ->first();

            if ($existingAllocation) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'This exam is already allocated to this class and combination for the selected session.'
                ], 400);
            }

            // Create new allocation
            $data = [
                'exam_id' => $examId,
                'class_id' => $classId,
                'session_id' => $sessionId,
                'combination_id' => $combinationId,
                'is_active' => 'yes'
            ];

            $result = $this->examCombinationModel->insert($data);

            if ($result) {
                return $this->respond([
                    'status' => 'success',
                    'message' => 'Exam allocated successfully'
                ]);
            } else {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Failed to allocate exam'
                ], 500);
            }
        } catch (\Exception $e) {
            log_message('error', '[AllocateExamsAlevel.store] Error: ' . $e->getMessage());
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to allocate exam'
            ], 500);
        }
    }
}

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
                    ->where('is_active', 'yes')
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
            $db = \Config\Database::connect('default');
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
                    AND c.is_active = 'yes'
                ORDER BY 
                    c.class
            ", [$sessionId]);

            $classes = $query->getResultArray();

            if (empty($classes)) {
                $classes = $this->classModel
                    ->where('is_active', 'yes')
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
            // Log incoming data for debugging
            log_message('info', '[AllocateExamsAlevel.store] Received POST data: ' . json_encode($this->request->getPost()));
            
            // Manual validation with fresh validator instance
            $validation = \Config\Services::validation();
            $validation->setRules([
                'exam_id' => [
                    'label' => 'Exam ID',
                    'rules' => 'required|string|min_length[36]|max_length[36]'
                ],
                'session_id' => [
                    'label' => 'Session ID',
                    'rules' => 'required|string|min_length[36]|max_length[36]'
                ],
                'class_id' => [
                    'label' => 'Class ID',
                    'rules' => 'required|string|min_length[36]|max_length[36]'
                ],
                'combination_id' => [
                    'label' => 'Combination ID',
                    'rules' => 'required|string|min_length[36]|max_length[36]'
                ]
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                log_message('error', '[AllocateExamsAlevel.store] Validation errors: ' . json_encode($validation->getErrors()));
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validation->getErrors()
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

            log_message('info', '[AllocateExamsAlevel.store] Attempting to insert: ' . json_encode($data));
            $result = $this->examCombinationModel->insert($data, false); // false = skip model validation

            if ($result) {
                log_message('info', '[AllocateExamsAlevel.store] Insert successful. ID: ' . $result);
                return $this->respond([
                    'status' => 'success',
                    'message' => 'Exam allocated successfully'
                ]);
            } else {
                $errors = $this->examCombinationModel->errors();
                log_message('error', '[AllocateExamsAlevel.store] Insert failed. Model errors: ' . json_encode($errors));
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Failed to allocate exam',
                    'errors' => $errors
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


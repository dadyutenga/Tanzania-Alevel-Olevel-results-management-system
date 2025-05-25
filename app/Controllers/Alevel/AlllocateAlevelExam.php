<?php

namespace App\Controllers\Alevel;

use App\Controllers\BaseController;
use App\Models\ExamModel;
use App\Models\AlevelExamCombinationModel;
use App\Models\ClassModel;
use App\Models\SessionModel;
use App\Models\AlevelCombinationModel;
use CodeIgniter\RESTful\ResourceController;

class AlllocateAlevelExam extends ResourceController
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
                'sessions' => $this->sessionModel->where('is_active', 'yes')->findAll(),
                'exams' => [],
                'classes' => [],
                'combinations' => [],
                'allocations' => []
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
                $data['allocations'] = $this->getAllocationsWithDetails(['tz_alevel_exam_combinations.session_id' => $currentSession['id']]);
            }

            // Ensure the correct view path is used
            return view('alevel/AllocateAlevelExam', $data);
        } catch (\Exception $e) {
            log_message('error', '[AlllocateAlevelExam.index] Exception: ' . $e->getMessage());
            return redirect()->to(base_url('dashboard'))
                ->with('error', 'Failed to load A-Level allocation data: ' . $e->getMessage());
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
            log_message('error', '[AlllocateAlevelExam.getExamsBySession] Error: ' . $e->getMessage());
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
            log_message('error', '[AlllocateAlevelExam.getClassesBySession] Error: ' . $e->getMessage());
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch classes'
            ], 500);
        }
    }

    public function getAllocations($sessionId)
    {
        try {
            $allocations = $this->getAllocationsWithDetails(['tz_alevel_exam_combinations.session_id' => $sessionId]);
            return $this->respond([
                'status' => 'success',
                'data' => $allocations
            ]);
        } catch (\Exception $e) {
            log_message('error', '[AlllocateAlevelExam.getAllocations] Error: ' . $e->getMessage());
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch A-Level allocations'
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
            log_message('error', '[AlllocateAlevelExam.store] Error: ' . $e->getMessage());
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to allocate exam'
            ], 500);
        }
    }

    public function deallocate($examId, $combinationId)
    {
        try {
            $result = $this->examCombinationModel->where([
                'exam_id' => $examId,
                'combination_id' => $combinationId
            ])->delete();

            if ($result) {
                return $this->respond([
                    'status' => 'success',
                    'message' => 'Exam deallocation successful'
                ]);
            } else {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Failed to deallocate exam'
                ], 500);
            }
        } catch (\Exception $e) {
            log_message('error', '[AlllocateAlevelExam.deallocate] Error: ' . $e->getMessage());
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to deallocate exam'
            ], 500);
        }
    }

    public function getExamAllocationDetails($examId)
    {
        try {
            $allocations = $this->getAllocationsWithDetails([
                'tz_alevel_exam_combinations.exam_id' => $examId
            ]);

            return $this->respond([
                'status' => 'success',
                'data' => $allocations
            ]);
        } catch (\Exception $e) {
            log_message('error', '[AlllocateAlevelExam.getExamAllocationDetails] Error: ' . $e->getMessage());
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch exam allocation details'
            ], 500);
        }
    }

    /**
     * Fetch allocations with related details from exams, classes, combinations, and sessions.
     * 
     * @param array $conditions Conditions to filter allocations
     * @return array
     */
    public function getAllocationsWithDetails($conditions = [])
    {
        try {
            $db = \Config\Database::connect('second_db');
            $builder = $db->table('tz_alevel_exam_combinations');
            
            $builder->select('
                tz_alevel_exam_combinations.id,
                tz_alevel_exam_combinations.exam_id,
                tz_alevel_exam_combinations.combination_id,
                tz_alevel_exam_combinations.class_id,
                tz_alevel_exam_combinations.session_id,
                tz_exams.exam_name,
                tz_exams.exam_date,
                classes.class,
                tz_alevel_combinations.combination_name,
                tz_alevel_combinations.combination_code,
                sessions.session
            ')
            ->join('tz_exams', 'tz_exams.id = tz_alevel_exam_combinations.exam_id')
            ->join('classes', 'classes.id = tz_alevel_exam_combinations.class_id')
            ->join('tz_alevel_combinations', 'tz_alevel_combinations.id = tz_alevel_exam_combinations.combination_id')
            ->join('sessions', 'sessions.id = tz_alevel_exam_combinations.session_id');

            if (!empty($conditions)) {
                $builder->where($conditions);
            }

            return $builder->get()->getResultArray();
        } catch (\Exception $e) {
            log_message('error', '[AlllocateAlevelExam.getAllocationsWithDetails] Error: ' . $e->getMessage());
            return [];
        }
    }
}


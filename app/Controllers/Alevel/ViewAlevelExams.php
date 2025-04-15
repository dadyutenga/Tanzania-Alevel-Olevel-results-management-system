<?php

namespace App\Controllers\Alevel;

use App\Controllers\BaseController;
use App\Models\ExamModel;
use App\Models\AlevelExamCombinationModel;
use App\Models\ClassModel;
use App\Models\SessionModel;
use App\Models\AlevelCombinationModel;
use CodeIgniter\RESTful\ResourceController;

class ViewAlevelExams extends ResourceController
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
                'allocations' => []
            ];

            // Get current session if exists
            $currentSession = $this->sessionModel->getCurrentSession();
            if ($currentSession) {
                $data['current_session'] = $currentSession;
                $data['allocations'] = $this->getAllocationsWithDetails(['tz_alevel_exam_combinations.session_id' => $currentSession['id']]);
            }

            return view('alevel/ViewAlevelExams', $data);
        } catch (\Exception $e) {
            log_message('error', '[ViewAlevelExams.index] Exception: ' . $e->getMessage());
            return redirect()->to(base_url('dashboard'))
                ->with('error', 'Failed to load A-Level exam allocations.');
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
            log_message('error', '[ViewAlevelExams.getAllocations] Error: ' . $e->getMessage());
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch A-Level allocations'
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
            log_message('error', '[ViewAlevelExams.deallocate] Error: ' . $e->getMessage());
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
            log_message('error', '[ViewAlevelExams.getExamAllocationDetails] Error: ' . $e->getMessage());
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
            log_message('error', '[ViewAlevelExams.getAllocationsWithDetails] Error: ' . $e->getMessage());
            return [];
        }
    }
} 
<?php

namespace App\Controllers;

use App\Models\ExamClassModel;
use App\Models\ExamModel;
use App\Models\ClassModel;
use App\Models\SessionModel;
use App\Models\SettingsModel;
use CodeIgniter\RESTful\ResourceController;

class AllocationController extends ResourceController
{
    protected $examClassModel;
    protected $examModel;
    protected $classModel;
    protected $sessionModel;
    protected $settingsModel;
    protected $format = 'json';

    public function __construct()
    {
        $this->examClassModel = new ExamClassModel();
        $this->examModel = new ExamModel();
        $this->classModel = new ClassModel();
        $this->sessionModel = new SessionModel();
        $this->settingsModel = new SettingsModel();
    }

    public function index()
    {
        try {
            $data = [
                'sessions' => $this->sessionModel->where('is_active', 'yes')->findAll(),
                'exams' => [],
                'classes' => [],
                'existingSubjects' => [],
                'allocations' => []
            ];

            // Get current session if exists
            $currentSession = $this->sessionModel->getCurrentSession();
            if ($currentSession) {
                $data['current_session'] = $currentSession;
                
                // Get exams for current session - fixed is_active to 'yes'
                $data['exams'] = $this->examModel
                    ->where('session_id', $currentSession['id'])
                    ->where('is_active', 'yes')
                    ->findAll();

                // Get active classes
                $data['classes'] = $this->classModel
                    ->where('is_active', 'yes')
                    ->findAll();

                // Get existing allocations
                $data['allocations'] = $this->examClassModel
                    ->getExamClassesWithDetails(['sessions.id' => $currentSession['id']]);
            }

            return view('exam/AllocateExamClasses', $data);
        } catch (\Exception $e) {
            log_message('error', '[AllocationController.index] Exception: ' . $e->getMessage());
            return redirect()->to(base_url('dashboard'))
                ->with('error', 'Failed to load allocation data: ' . $e->getMessage());
        }
    }

    public function getExamsBySession($sessionId)
    {
        try {
            // Fixed is_active to 'yes' to match database values
            $exams = $this->examModel
                ->where('session_id', $sessionId)
                ->where('is_active', 'yes')
                ->findAll();

            return $this->respond([
                'status' => 'success',
                'data' => $exams
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch exams'
            ], 500);
        }
    }

    public function getClassesBySession($sessionId)
    {
        try {
            $classes = $this->classModel
                ->where('is_active', 'yes')
                ->findAll();

            return $this->respond([
                'status' => 'success',
                'data' => $classes
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch classes'
            ], 500);
        }
    }

    public function getAllocations($sessionId)
    {
        try {
            $db = \Config\Database::connect('default');
            $builder = $db->table('tz_exam_classes');
            // Note: BaseModel automatically filters by school_id via beforeFind hook

            $allocations = $builder
                ->select('
                    tz_exam_classes.id,
                    tz_exam_classes.exam_id,
                    tz_exam_classes.class_id,
                    tz_exam_classes.session_id,
                    tz_exams.exam_name,
                    tz_exams.exam_date,
                    classes.class
                ')
                ->join('tz_exams', 'tz_exams.id = tz_exam_classes.exam_id')
                ->join('classes', 'classes.id = tz_exam_classes.class_id')
                ->join('sessions', 'sessions.id = tz_exam_classes.session_id')
                ->where('tz_exam_classes.session_id', $sessionId)
                ->get()
                ->getResultArray();

            log_message('debug', 'Allocations Query: ' . $db->getLastQuery());

            return $this->respond([
                'status' => 'success',
                'data' => $allocations
            ]);
        } catch (\Exception $e) {
            log_message('error', '[AllocationController.getAllocations] Error: ' . $e->getMessage());
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch allocations: ' . $e->getMessage()
            ], 500);
        }
    }

    // Rename 'allocate' method to 'store'
    public function store()
    {
        try {
            // Debug and fix: Check session data
            $session = service('session');
            $userId = $session->get('user_uuid') ?? $session->get('user_id');
            $schoolId = $session->get('school_id');
            
            // If school_id is missing from session, try to get it from settings
            if (!$schoolId && $userId) {
                $school = $this->settingsModel->getSchoolByUserId($userId);
                if ($school) {
                    $schoolId = $school['id'];
                    // Update session with school_id
                    $session->set('school_id', $schoolId);
                    log_message('info', '[AllocationController.store] Fixed missing school_id in session: ' . $schoolId);
                }
            }
            
            $rules = [
                'exam_id' => 'required|string|min_length[36]|max_length[36]',
                'session_id' => 'required|string|min_length[36]|max_length[36]',
                'class_id' => 'required|string|min_length[36]|max_length[36]'
            ];

            if (!$this->validate($rules)) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ], 400);
            }

            $examId = $this->request->getPost('exam_id');
            $classId = $this->request->getPost('class_id');  // Changed from class_ids
            $sessionId = $this->request->getPost('session_id');

            // Validate exam exists
            $exam = $this->examModel->find($examId);
            if (!$exam) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Invalid exam selected'
                ], 400);
            }

            // Validate session exists
            $session = $this->sessionModel->find($sessionId);
            if (!$session) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Invalid session selected'
                ], 400);
            }

            // Create new allocation
            $data = [
                'exam_id' => $examId,
                'class_id' => $classId,
                'session_id' => $sessionId
            ];

            $result = $this->examClassModel->insert($data);

            if ($result) {
                return $this->respond([
                    'status' => 'success',
                    'message' => 'Exam allocated to class successfully'
                ]);
            } else {
                throw new \RuntimeException('Failed to allocate exam to class');
            }
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to allocate exam: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deallocate($examId, $classId)
    {
        try {
            $result = $this->examClassModel->where([
                'exam_id' => $examId,
                'class_id' => $classId
            ])->delete();

            if ($result) {
                return $this->respond([
                    'status' => 'success',
                    'message' => 'Exam deallocation successful'
                ]);
            } else {
                throw new \RuntimeException('Failed to deallocate exam');
            }
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to deallocate exam: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getExamAllocationDetails($examId)
    {
        try {
            $allocations = $this->examClassModel->getExamClassesWithDetails([
                'tz_exam_classes.exam_id' => $examId
            ]);

            return $this->respond([
                'status' => 'success',
                'data' => $allocations
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch exam allocation details'
            ], 500);
        }
    }
}
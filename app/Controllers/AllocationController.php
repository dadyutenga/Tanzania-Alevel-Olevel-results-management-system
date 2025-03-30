<?php

namespace App\Controllers;

use App\Models\ExamClassModel;
use App\Models\ExamModel;
use App\Models\ClassModel;
use App\Models\SessionModel;
use CodeIgniter\RESTful\ResourceController;

class AllocationController extends ResourceController
{
    protected $examClassModel;
    protected $examModel;
    protected $classModel;
    protected $sessionModel;
    protected $format = 'json';

    public function __construct()
    {
        $this->examClassModel = new ExamClassModel();
        $this->examModel = new ExamModel();
        $this->classModel = new ClassModel();
        $this->sessionModel = new SessionModel();
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
                
                // Get exams for current session
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

    // Update the allocate method to match the view's form submission
    public function store()
    {
        try {
            $rules = [
                'exam_id' => 'required|numeric',
                'class_ids' => 'required',
                'session_id' => 'required|numeric'
            ];

            if (!$this->validate($rules)) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ], 400);
            }

            $examId = $this->request->getPost('exam_id');
            $classIds = json_decode($this->request->getPost('class_ids'), true);
            $sessionId = $this->request->getPost('session_id');

            // Validate exam exists
            $exam = $this->examModel->find($examId);
            if (!$exam) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Invalid exam selected'
                ], 400);
            }

            // Remove existing allocations for this exam
            $this->examClassModel->where('exam_id', $examId)->delete();

            // Create new allocations
            $insertData = [];
            foreach ($classIds as $classId) {
                $insertData[] = [
                    'exam_id' => $examId,
                    'class_id' => $classId,
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }

            $result = $this->examClassModel->insertBatch($insertData);

            return $this->respond([
                'status' => 'success',
                'message' => 'Exam allocated to classes successfully'
            ]);

        } catch (\Exception $e) {
            log_message('error', '[AllocationController.store] Exception: ' . $e->getMessage());
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to allocate exam: ' . $e->getMessage()
            ], 500);
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
            $allocations = $this->examClassModel->getExamClassesWithDetails([
                'sessions.id' => $sessionId
            ]);

            return $this->respond([
                'status' => 'success',
                'data' => $allocations
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch allocations'
            ], 500);
        }
    }

    public function allocate()
    {
        try {
            $rules = [
                'exam_id' => 'required|numeric',
                'class_ids' => 'required',
                'session_id' => 'required|numeric'
            ];

            if (!$this->validate($rules)) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ], 400);
            }

            $examId = $this->request->getPost('exam_id');
            $classIds = json_decode($this->request->getPost('class_ids'), true);
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

            // Remove existing allocations for this exam
            $this->examClassModel->where('exam_id', $examId)->delete();

            // Create new allocations
            $result = $this->examClassModel->assignClassesToExam($examId, $classIds, $sessionId);

            if ($result) {
                return $this->respond([
                    'status' => 'success',
                    'message' => 'Exam allocated to classes successfully'
                ]);
            } else {
                throw new \RuntimeException('Failed to allocate exam to classes');
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
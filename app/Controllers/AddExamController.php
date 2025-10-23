<?php

namespace App\Controllers;

use App\Models\ExamModel;
use App\Models\SessionModel;
use CodeIgniter\RESTful\ResourceController;

class AddExamController extends ResourceController
{
    protected $format = 'json';
    protected $sessionModel;
    protected $examModel;

    public function __construct()
    {
        $this->sessionModel = new SessionModel();
        $this->examModel = new ExamModel();
    }

    public function index()
    {
        try {
            // Get current session for pre-selection
            $activeSession = $this->sessionModel->getCurrentSession();
            
            $data = [
                'activeSession' => $activeSession
            ];
            
            return view('exam/AddExam', $data);
        } catch (\Exception $e) {
            log_message('error', '[AddExam.index] Exception: {message}', ['message' => $e->getMessage()]);
            // Redirect to exam list with error message
            return redirect()->to('exam')->with('error', 'Failed to load exam form');
        }
    }

    public function getSessions()
    {
        try {
            log_message('info', 'Fetching available sessions for exam creation');
            
            // Get only active sessions (where is_active = 'no')
            $sessions = $this->sessionModel->where('is_active', 'yes')
                                         ->orderBy('session', 'DESC')
                                         ->findAll();
            
            log_message('debug', 'Sessions result: ' . json_encode($sessions));
            
            return $this->respond([
                'status' => 'success',
                'data' => $sessions
            ]);
        } catch (\Exception $e) {
            log_message('error', '[getSessions] Exception: {message}', ['message' => $e->getMessage()]);
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch sessions'
            ], 500);
        }
    }

    public function store()
    {
        try {
            $rules = [
                'exam_name' => 'required|max_length[100]',
                'exam_date' => 'required|valid_date',
                'session_id' => 'required|string|min_length[36]|max_length[36]',
                'is_active' => 'permit_empty|in_list[yes,no]'
            ];

            if (!$this->validate($rules)) {
                return $this->respond([
                    'status' => 'error',
                    'message' => $this->validator->getErrors()
                ], 400);
            }

            // Verify session exists and is active using the correct model/database
            $sessionId = $this->request->getPost('session_id');
            if (!$this->validateSession($sessionId)) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Invalid or inactive session selected'
                ], 400);
            }

            $data = [
                'exam_name' => $this->request->getPost('exam_name'),
                'exam_date' => $this->request->getPost('exam_date'),
                'session_id' => $sessionId,
                'is_active' => $this->request->getPost('is_active') ?? 'yes'
            ];

            // Using examModel which is correctly configured for default
            $examId = $this->examModel->insert($data);

            if (!$examId) {
                throw new \RuntimeException('Failed to create exam record');
            }

            // Get the created exam
            $createdExam = $this->examModel->find($examId);

            return $this->respond([
                'status' => 'success',
                'message' => 'Exam created successfully',
                'data' => $createdExam
            ]);

        } catch (\Exception $e) {
            log_message('error', '[AddExam.store] Exception: {message}', ['message' => $e->getMessage()]);
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to create exam'
            ], 500);
        }
    }

    // Helper method to validate session
    private function validateSession($sessionId): bool
    {
        try {
            $session = $this->sessionModel->find($sessionId);
            return ($session && $session['is_active'] === 'yes'); // 'no' means active in your system
        } catch (\Exception $e) {
            log_message('error', '[validateSession] Exception: {message}', ['message' => $e->getMessage()]);
            return false;
        }
    }
}

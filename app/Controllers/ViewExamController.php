<?php

namespace App\Controllers;

use App\Models\ExamModel;
use App\Models\SessionModel;
use CodeIgniter\RESTful\ResourceController;

class ViewExamController extends ResourceController
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
                'activeSession' => $activeSession,
                'exams' => [] // Will be populated via AJAX
            ];
            
            return view('exam/ViewExams', $data);
        } catch (\Exception $e) {
            log_message('error', '[ViewExam.index] Exception: {message}', ['message' => $e->getMessage()]);
            return redirect()->to('dashboard')->with('error', 'Failed to load exam list');
        }
    }

    public function getExams()
    {
        try {
            $sessionId = $this->request->getGet('session_id');
            
            $exams = $this->examModel
                ->where('session_id', $sessionId)
                ->orderBy('exam_date', 'DESC')
                ->findAll();

            return $this->respond([
                'status' => 'success',
                'data' => $exams
            ]);
        } catch (\Exception $e) {
            log_message('error', '[ViewExam.getExams] Exception: {message}', ['message' => $e->getMessage()]);
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch exams'
            ], 500);
        }
    }

    public function getSessions()
    {
        try {
            $sessions = $this->sessionModel->where('is_active', 'yes')
                                         ->orderBy('session', 'DESC')
                                         ->findAll();
            
            return $this->respond([
                'status' => 'success',
                'data' => $sessions
            ]);
        } catch (\Exception $e) {
            log_message('error', '[ViewExam.getSessions] Exception: {message}', ['message' => $e->getMessage()]);
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch sessions'
            ], 500);
        }
    }

    public function update($id = null)
    {
        try {
            if (!$id) {
                throw new \Exception('Exam ID is required');
            }

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

            // Verify session exists and is active
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

            if (!$this->examModel->update($id, $data)) {
                throw new \RuntimeException('Failed to update exam record');
            }

            return $this->respond([
                'status' => 'success',
                'message' => 'Exam updated successfully',
                'data' => $this->examModel->find($id)
            ]);

        } catch (\Exception $e) {
            log_message('error', '[ViewExam.update] Exception: {message}', ['message' => $e->getMessage()]);
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to update exam: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete($id = null)
    {
        try {
            if (!$id) {
                throw new \Exception('Exam ID is required');
            }

            // Check if exam exists
            $exam = $this->examModel->find($id);
            if (!$exam) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Exam not found'
                ], 404);
            }

            if (!$this->examModel->delete($id)) {
                throw new \RuntimeException('Failed to delete exam record');
            }

            return $this->respond([
                'status' => 'success',
                'message' => 'Exam deleted successfully'
            ]);

        } catch (\Exception $e) {
            log_message('error', '[ViewExam.delete] Exception: {message}', ['message' => $e->getMessage()]);
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to delete exam: ' . $e->getMessage()
            ], 500);
        }
    }

    private function validateSession($sessionId): bool
    {
        try {
            $session = $this->sessionModel->find($sessionId);
            return ($session && $session['is_active'] === 'yes');
        } catch (\Exception $e) {
            log_message('error', '[validateSession] Exception: {message}', ['message' => $e->getMessage()]);
            return false;
        }
    }
}
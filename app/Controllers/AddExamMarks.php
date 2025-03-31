<?php

namespace App\Controllers;

use App\Models\ExamModel;
use App\Models\ExamClassModel;
use App\Models\StudentSessionModel;
use App\Models\StudentModel;
use App\Models\SessionModel;
use CodeIgniter\RESTful\ResourceController;

class AddExamMarks extends ResourceController
{
    protected $examModel;
    protected $examClassModel;
    protected $studentSessionModel;
    protected $studentModel;
    protected $sessionModel;
    protected $format = 'json';

    public function __construct()
    {
        $this->examModel = new ExamModel();
        $this->examClassModel = new ExamClassModel();
        $this->studentSessionModel = new StudentSessionModel();
        $this->studentModel = new StudentModel();
        $this->sessionModel = new SessionModel();
    }

    public function index()
    {
        try {
            $data = [
                'sessions' => $this->sessionModel->where('is_active', 'no')->findAll(),
                'exams' => [],
                'classes' => [],
                'students' => [],
                'subjects' => []
            ];

            $currentSession = $this->sessionModel->getCurrentSession();
            if ($currentSession) {
                $data['current_session'] = $currentSession;
                $data['exams'] = $this->examModel
                    ->where('session_id', $currentSession['id'])
                    ->where('is_active', 'yes')
                    ->findAll();
            }

            return view('exam/AddExamMarks', $data);
        } catch (\Exception $e) {
            log_message('error', '[AddExamMarks.index] Exception: ' . $e->getMessage());
            return redirect()->to(base_url('dashboard'))
                ->with('error', 'Failed to load exam marks page: ' . $e->getMessage());
        }
    }

    public function getStudents()
    {
        try {
            $examId = $this->request->getGet('exam_id');
            $classId = $this->request->getGet('class_id');
            $sessionId = $this->request->getGet('session_id');

            if (!$examId || !$classId || !$sessionId) {
                throw new \Exception('Missing required parameters');
            }

            $students = $this->studentSessionModel
                ->select('students.*, student_session.*, classes.class')
                ->join('students', 'students.id = student_session.student_id')
                ->join('classes', 'classes.id = student_session.class_id')
                ->where([
                    'student_session.session_id' => $sessionId,
                    'student_session.class_id' => $classId,
                    'students.is_active' => 'no'
                ])
                ->findAll();

            return $this->respond([
                'status' => 'success',
                'data' => $students
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getSubjects()
    {
        try {
            $examId = $this->request->getGet('exam_id');

            if (!$examId) {
                throw new \Exception('Exam ID is required');
            }

            $db = \Config\Database::connect('second_db');
            $subjects = $db->table('tz_exam_subjects')
                ->where('exam_id', $examId)
                ->get()
                ->getResultArray();

            return $this->respond([
                'status' => 'success',
                'data' => $subjects
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function saveMarks()
    {
        try {
            $rules = [
                'exam_id' => 'required|numeric',
                'student_id' => 'required|numeric',
                'class_id' => 'required|numeric',
                'session_id' => 'required|numeric',
                'marks' => 'required|array'
            ];

            if (!$this->validate($rules)) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ], 400);
            }

            $db = \Config\Database::connect('second_db');
            $db->transStart();

            $examId = $this->request->getPost('exam_id');
            $studentId = $this->request->getPost('student_id');
            $classId = $this->request->getPost('class_id');
            $sessionId = $this->request->getPost('session_id');
            $marks = $this->request->getPost('marks');

            // Delete existing marks if any
            $db->table('tz_exam_subject_marks')
                ->where([
                    'exam_id' => $examId,
                    'student_id' => $studentId
                ])
                ->delete();

            // Insert new marks
            foreach ($marks as $subjectId => $mark) {
                $db->table('tz_exam_subject_marks')->insert([
                    'exam_id' => $examId,
                    'student_id' => $studentId,
                    'class_id' => $classId,
                    'session_id' => $sessionId,
                    'exam_subject_id' => $subjectId,
                    'marks_obtained' => $mark,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Failed to save marks');
            }

            return $this->respond([
                'status' => 'success',
                'message' => 'Marks saved successfully'
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to save marks: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getExistingMarks($examId, $studentId)
    {
        try {
            $db = \Config\Database::connect('second_db');
            $marks = $db->table('tz_exam_subject_marks')
                ->select('tz_exam_subject_marks.*, tz_exam_subjects.subject_name, tz_exam_subjects.max_marks')
                ->join('tz_exam_subjects', 'tz_exam_subjects.id = tz_exam_subject_marks.exam_subject_id')
                ->where([
                    'tz_exam_subject_marks.exam_id' => $examId,
                    'tz_exam_subject_marks.student_id' => $studentId
                ])
                ->get()
                ->getResultArray();

            return $this->respond([
                'status' => 'success',
                'data' => $marks
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch existing marks: ' . $e->getMessage()
            ], 500);
        }
    }
}
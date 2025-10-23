<?php

namespace App\Controllers;

use App\Models\ExamModel;
use App\Models\ExamClassModel;
use App\Models\StudentSessionModel;
use App\Models\StudentModel;
use App\Models\SessionModel;
use App\Models\SettingsModel;
use CodeIgniter\RESTful\ResourceController;

class AddExamMarks extends ResourceController
{
    protected $examModel;
    protected $examClassModel;
    protected $studentSessionModel;
    protected $studentModel;
    protected $sessionModel;
    protected $settingsModel;
    protected $format = 'json';

    public function __construct()
    {
        $this->examModel = new ExamModel();
        $this->examClassModel = new ExamClassModel();
        $this->studentSessionModel = new StudentSessionModel();
        $this->studentModel = new StudentModel();
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
                ->select('students.id as student_id, students.firstname, students.middlename, students.lastname, student_session.id as session_record_id, student_session.class_id, student_session.session_id, classes.class')
                ->join('students', 'students.id = student_session.student_id')
                ->join('classes', 'classes.id = student_session.class_id')
                ->where([
                    'student_session.session_id' => $sessionId,
                    'student_session.class_id' => $classId,
                    'student_session.is_active' => 'yes',
                    'students.is_active' => 'yes'
                ])
                ->findAll();

            log_message('debug', '[AddExamMarks.getStudents] Found ' . count($students) . ' students');
            if (count($students) > 0) {
                log_message('debug', '[AddExamMarks.getStudents] First student: ' . json_encode($students[0]));
            }

            return $this->respond([
                'status' => 'success',
                'data' => $students
            ]);
        } catch (\Exception $e) {
            log_message('error', '[AddExamMarks.getStudents] Error: ' . $e->getMessage());
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

            $db = \Config\Database::connect('default');
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
            // Debug and fix: Check session data
            $session = service('session');
            $userId = $session->get('user_uuid') ?? $session->get('user_id');
            $schoolId = $session->get('school_id');
            
            // If school_id is missing from session, try to get it from settings
            if (!$schoolId && $userId) {
                $school = $this->settingsModel->getSchoolByUserId($userId);
                if ($school) {
                    $schoolId = $school['id'];
                    $session->set('school_id', $schoolId);
                    log_message('info', '[AddExamMarks.saveMarks] Fixed missing school_id in session: ' . $schoolId);
                }
            }
            
            $rules = [
                'exam_id' => 'required|string|min_length[36]|max_length[36]',
                'student_id' => 'required|string|min_length[36]|max_length[36]',
                'class_id' => 'required|string|min_length[36]|max_length[36]',
                'session_id' => 'required|string|min_length[36]|max_length[36]'
            ];

            if (!$this->validate($rules)) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ], 400);
            }

            $examId = $this->request->getPost('exam_id');
            $studentId = $this->request->getPost('student_id');
            $classId = $this->request->getPost('class_id');
            $sessionId = $this->request->getPost('session_id');
            $marks = json_decode($this->request->getPost('marks'), true);

            log_message('debug', '[AddExamMarks.saveMarks] Received data: ' . json_encode([
                'exam_id' => $examId,
                'student_id' => $studentId,
                'class_id' => $classId,
                'session_id' => $sessionId,
                'marks_count' => is_array($marks) ? count($marks) : 0
            ]));

            // Verify student exists
            $studentExists = $this->studentModel->find($studentId);
            if (!$studentExists) {
                log_message('error', '[AddExamMarks.saveMarks] Student not found: ' . $studentId);
                throw new \Exception('Student not found in database. ID: ' . $studentId);
            }
            log_message('debug', '[AddExamMarks.saveMarks] Student found: ' . json_encode($studentExists));

            if (!is_array($marks) || empty($marks)) {
                throw new \Exception('Invalid or empty marks data');
            }

            $examSubjectMarkModel = new \App\Models\ExamSubjectMarkModel();
            
            // Start transaction
            $db = \Config\Database::connect();
            $db->transStart();

            try {
                // Delete existing marks for this student and exam
                $deleted = $examSubjectMarkModel->where([
                    'exam_id' => $examId,
                    'student_id' => $studentId
                ])->delete();
                
                log_message('debug', '[AddExamMarks.saveMarks] Deleted existing marks: ' . ($deleted ? 'yes' : 'no'));

                // Insert new marks - use individual inserts so BaseModel hooks work
                $insertedCount = 0;
                foreach ($marks as $subjectId => $mark) {
                    // Skip if mark is empty or not numeric
                    if ($mark === '' || $mark === null) {
                        continue;
                    }
                    
                    $markData = [
                        'exam_id' => $examId,
                        'student_id' => $studentId,
                        'class_id' => $classId,
                        'session_id' => $sessionId,
                        'exam_subject_id' => $subjectId,
                        'marks_obtained' => (float)$mark
                    ];

                    // Use insert - BaseModel will handle UUID and audit trail
                    $markId = $examSubjectMarkModel->insert($markData);
                    
                    if (!$markId) {
                        $errors = $examSubjectMarkModel->errors();
                        log_message('error', '[AddExamMarks.saveMarks] Insert failed for subject ' . $subjectId . ': ' . json_encode($errors));
                        throw new \Exception('Failed to save mark for subject: ' . json_encode($errors));
                    }
                    
                    $insertedCount++;
                    log_message('debug', '[AddExamMarks.saveMarks] Inserted mark ' . $insertedCount . ': subject=' . $subjectId . ', marks=' . $mark);
                }

                $db->transComplete();

                if ($db->transStatus() === false) {
                    throw new \RuntimeException('Transaction failed');
                }

                log_message('info', '[AddExamMarks.saveMarks] Successfully saved ' . $insertedCount . ' marks');

                return $this->respond([
                    'status' => 'success',
                    'message' => 'Marks saved successfully (' . $insertedCount . ' subjects)'
                ]);
            } catch (\Exception $e) {
                $db->transRollback();
                throw $e;
            }
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
            $db = \Config\Database::connect('default');
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

    public function getExams($sessionId)
    {
        try {
            if (!$sessionId) {
                throw new \Exception('Session ID is required');
            }

            $exams = $this->examModel
                ->where([
                    'session_id' => $sessionId,
                    'is_active' => 'yes'
                ])
                ->findAll();

            return $this->respond([
                'status' => 'success',
                'data' => $exams
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getClasses($sessionId)
    {
        try {
            if (!$sessionId) {
                throw new \Exception('Session ID is required');
            }

            $db = \Config\Database::connect('default');
            $classes = $db->table('classes c')
                ->select('c.id, c.class')
                ->join('tz_exam_classes ec', 'c.id = ec.class_id')
                ->join('tz_exams e', 'e.id = ec.exam_id')
                ->where([
                    'e.session_id' => $sessionId,
                    'e.is_active' => 'yes',
                    'c.is_active' => 'yes'
                ])
                ->groupBy('c.id')
                ->get()
                ->getResultArray();

            return $this->respond([
                'status' => 'success',
                'data' => $classes
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
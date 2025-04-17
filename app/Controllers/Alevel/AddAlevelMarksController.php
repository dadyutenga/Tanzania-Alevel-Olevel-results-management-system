<?php

namespace App\Controllers\Alevel;

use App\Controllers\BaseController;
use App\Models\AlevelMarksModel;
use App\Models\AlevelCombinationModel;
use App\Models\AlevelSubjectModel;
use App\Models\AlevelStudentModel; 
use App\Models\AlevelStudentMarksModel;
use App\Models\AlevelStudentCombinationModel;
use App\Models\AlevelStudentSubjectModel;
use App\Models\AlevelStudentSubjectMarksModel;
use App\Models\ExamModel;
use App\Models\SessionModel;
use CodeIgniter\RESTful\ResourceController;

class AddAlevelMarksController extends BaseController
{
    protected $alevelMarksModel;
    protected $alevelCombinationModel;
    protected $alevelSubjectModel;
    protected $alevelStudentModel; 
    protected $alevelStudentMarksModel;
    protected $alevelStudentCombinationModel;
    protected $alevelStudentSubjectModel;
    protected $alevelStudentSubjectMarksModel;
    protected $examModel;
    protected $sessionModel;
    protected $format = 'json';

    public function __construct()
    {
        $this->alevelMarksModel = new AlevelMarksModel();
        $this->alevelCombinationModel = new AlevelCombinationModel();
        $this->alevelSubjectModel = new AlevelSubjectModel();
        $this->alevelStudentModel = new AlevelStudentModel(); 
        $this->alevelStudentMarksModel = new AlevelStudentMarksModel();
        $this->alevelStudentCombinationModel = new AlevelStudentCombinationModel();
        $this->alevelStudentSubjectModel = new AlevelStudentSubjectModel();
        $this->alevelStudentSubjectMarksModel = new AlevelStudentSubjectMarksModel();
        $this->examModel = new ExamModel();
        $this->sessionModel = new SessionModel();
    }

    public function index()
    {
        try {
            $data = [
                'combinations' => $this->alevelCombinationModel->findAll(),
                'subjects' => $this->alevelSubjectModel->findAll(),
                'students' => $this->alevelStudentModel->findAll(),
                'sessions' => $this->sessionModel->where('is_active', 'no')->findAll(),
                'exams' => [],
                'classes' => [],
            ];

            $currentSession = $this->sessionModel->getCurrentSession();
            if ($currentSession) {
                $data['current_session'] = $currentSession;
                $data['exams'] = $this->examModel
                    ->where('session_id', $currentSession['id'])
                    ->where('is_active', 'yes')
                    ->findAll();
                
                // Fetch classes linked to A-level combinations for the current session
                $db = \Config\Database::connect('second_db');
                $data['classes'] = $db->table('classes c')
                    ->select('c.id, c.class')
                    ->join('tz_student_alevel_combinations sac', 'c.id = sac.class_id')
                    ->where([
                        'sac.session_id' => $currentSession['id'],
                        'sac.is_active' => 'yes',
                        'c.is_active' => 'no'
                    ])
                    ->groupBy('c.id')
                    ->get()
                    ->getResultArray();
            }

            return view('alevel/AddMarks', $data);
        } catch (\Exception $e) {
            log_message('error', '[AddAlevelMarksController.index] Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load marks page');
        }
    }

    public function getStudents()
    {
        try {
            $examId = $this->request->getGet('exam_id');
            $classId = $this->request->getGet('class_id');
            $sessionId = $this->request->getGet('session_id');
            $combinationId = $this->request->getGet('combination_id');

            if (!$examId || !$classId || !$sessionId || !$combinationId) {
                throw new \Exception('Missing required parameters');
            }

            $students = $this->alevelStudentModel
                ->select('students.id, students.firstname, students.lastname, students.roll_no, student_session.*, classes.class')
                ->join('student_session', 'student_session.student_id = students.id')
                ->join('classes', 'classes.id = student_session.class_id')
                ->join('tz_student_alevel_combinations', 'tz_student_alevel_combinations.class_id = student_session.class_id AND tz_student_alevel_combinations.session_id = student_session.session_id')
                ->where([
                    'student_session.session_id' => $sessionId,
                    'student_session.class_id' => $classId,
                    'student_session.is_active' => 'no',
                    'students.is_active' => 'yes',
                    'tz_student_alevel_combinations.combination_id' => $combinationId,
                    'tz_student_alevel_combinations.is_active' => 'yes'
                ])
                ->findAll();

            return $this->respond([
                'status' => 'success',
                'data' => $students
            ]);
        } catch (\Exception $e) {
            log_message('error', '[AddAlevelMarksController.getStudents] Error: ' . $e->getMessage());
            return $this->respond([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getSubjects()
    {
        try {
            $combinationId = $this->request->getGet('combination_id');

            if (!$combinationId) {
                throw new \Exception('Combination ID is required');
            }

            $db = \Config\Database::connect('second_db');
            $subjects = $db->table('tz_alevel_combination_subjects')
                ->select('tz_alevel_combination_subjects.*')
                ->where([
                    'tz_alevel_combination_subjects.combination_id' => $combinationId,
                    'tz_alevel_combination_subjects.is_active' => 'yes'
                ])
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
            $studentId = $this->request->getPost('student_id');
            $classId = $this->request->getPost('class_id');
            $sessionId = $this->request->getPost('session_id');
            $combinationId = $this->request->getPost('combination_id');
            $marks = json_decode($this->request->getPost('marks'), true);

            if (!is_array($marks)) {
                throw new \Exception('Invalid marks data format');
            }

            // Start transaction
            $this->alevelStudentSubjectMarksModel->db->transStart();

            // Delete existing marks
            $this->alevelStudentSubjectMarksModel->where([
                'exam_id' => $examId,
                'student_id' => $studentId
            ])->delete();

            // Insert new marks
            foreach ($marks as $subjectId => $mark) {
                $markData = [
                    'exam_id' => $examId,
                    'student_id' => $studentId,
                    'class_id' => $classId,
                    'session_id' => $sessionId,
                    'combination_id' => $combinationId,
                    'subject_id' => $subjectId,
                    'marks_obtained' => $mark
                ];

                if (!$this->alevelStudentSubjectMarksModel->insert($markData)) {
                    throw new \Exception('Failed to save marks: ' . implode(', ', $this->alevelStudentSubjectMarksModel->errors()));
                }
            }

            $this->alevelStudentSubjectMarksModel->db->transComplete();

            if ($this->alevelStudentSubjectMarksModel->db->transStatus() === false) {
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
            $marks = $db->table('tz_alevel_subject_marks')
                ->select('tz_alevel_subject_marks.*, tz_alevel_combination_subjects.subject_name')
                ->join('tz_alevel_combination_subjects', 'tz_alevel_combination_subjects.id = tz_alevel_subject_marks.subject_id')
                ->where([
                    'tz_alevel_subject_marks.exam_id' => $examId,
                    'tz_alevel_subject_marks.student_id' => $studentId
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

            $db = \Config\Database::connect('second_db');
            $classes = $db->table('classes c')
                ->select('c.id, c.class')
                ->join('tz_student_alevel_combinations sac', 'c.id = sac.class_id')
                ->where([
                    'sac.session_id' => $sessionId,
                    'sac.is_active' => 'yes',
                    'c.is_active' => 'no'
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

    public function getCombinations($sessionId, $classId)
    {
        try {
            if (!$sessionId || !$classId) {
                throw new \Exception('Session ID and Class ID are required');
            }

            $db = \Config\Database::connect('second_db');
            $combinations = $db->table('tz_alevel_combinations ac')
                ->select('ac.id, ac.combination_code, ac.combination_name')
                ->join('tz_student_alevel_combinations sac', 'ac.id = sac.combination_id')
                ->where([
                    'sac.session_id' => $sessionId,
                    'sac.class_id' => $classId,
                    'sac.is_active' => 'yes',
                    'ac.is_active' => 'yes'
                ])
                ->groupBy('ac.id')
                ->get()
                ->getResultArray();

            return $this->respond([
                'status' => 'success',
                'data' => $combinations
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
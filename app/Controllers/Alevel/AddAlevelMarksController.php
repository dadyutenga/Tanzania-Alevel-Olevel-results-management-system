<?php

namespace App\Controllers\Alevel;

use App\Controllers\BaseController;
use App\Models\AlevelCombinationModel;
use App\Models\AlevelCombinationSubjectModel;
use App\Models\StudentModel;
use App\Models\AlevelSubjectMarksModel;
use App\Models\StudentAlevelCombinationModel;
use App\Models\ExamModel;
use App\Models\SessionModel;
use CodeIgniter\API\ResponseTrait;

class AddAlevelMarksController extends BaseController
{
    use ResponseTrait;

    protected $alevelMarksModel;
    protected $alevelCombinationModel;
    protected $alevelCombinationSubjectModel;
    protected $studentModel;
    protected $studentAlevelCombinationModel;
    protected $examModel;
    protected $sessionModel;
    protected $format = 'json';

    public function __construct()
    {
        $this->alevelMarksModel = new AlevelSubjectMarksModel();
        $this->alevelCombinationModel = new AlevelCombinationModel();
        $this->alevelCombinationSubjectModel = new AlevelCombinationSubjectModel();
        $this->studentModel = new StudentModel();
        $this->studentAlevelCombinationModel = new StudentAlevelCombinationModel();
        $this->examModel = new ExamModel();
        $this->sessionModel = new SessionModel();
    }

    public function index()
    {
        try {
            $data = [
                'combinations' => $this->alevelCombinationModel->where('is_active', 'yes')->findAll(),
                'subjects' => $this->alevelCombinationSubjectModel->where('is_active', 'yes')->findAll(),
                'students' => $this->studentModel->where('is_active', 'yes')->findAll(),
                'sessions' => $this->sessionModel->where('is_active', 'yes')->findAll(),
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
                
                // Updated is_active to 'no' for classes
                $db = \Config\Database::connect('default');
                $data['classes'] = $db->table('classes c')
                    ->select('c.id, c.class')
                    ->join('tz_student_alevel_combinations sac', 'c.id = sac.class_id')
                    ->where([
                        'sac.session_id' => $currentSession['id'],
                        'sac.is_active' => 'yes',
                        'c.is_active' => 'yes'  // Changed from 'yes' to 'no'
                    ])
                    ->groupBy('c.id')
                    ->get()
                    ->getResultArray();
            }

            return view('alevel/AlevelAddMarks', $data);
        } catch (\Exception $e) {
            log_message('error', '[AddAlevelMarksController.index] Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load marks page: ' . $e->getMessage());
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

        $students = $this->studentModel
            ->select('students.id as student_id, students.firstname, students.middlename, students.lastname, classes.class')
            ->join('student_session', 'student_session.student_id = students.id')
            ->join('classes', 'classes.id = student_session.class_id')
            ->join('tz_student_alevel_combinations sac', 'sac.class_id = student_session.class_id AND sac.session_id = student_session.session_id AND (sac.section_id = student_session.section_id OR sac.section_id IS NULL)')
            ->join('tz_alevel_exam_combinations aec', "aec.combination_id = sac.combination_id AND aec.class_id = student_session.class_id AND aec.session_id = student_session.session_id AND aec.exam_id = '" . $examId . "'")
            ->where([
                'student_session.session_id' => $sessionId,
                'student_session.class_id' => $classId,
                'student_session.is_active' => 'yes',
                'students.is_active' => 'yes',
                'sac.combination_id' => $combinationId,
                'sac.is_active' => 'yes',
                'aec.is_active' => 'yes'
            ])
            ->findAll();

        // Debug log the students
        log_message('error', '[AddAlevelMarksController.getStudents] Students returned: ' . json_encode($students));

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

        $db = \Config\Database::connect('default');
        $subjects = $db->table('tz_alevel_combination_subjects')
            ->select('id, subject_name, subject_type') // Removed max_marks
            ->where([
                'combination_id' => $combinationId,
                'is_active' => 'yes'
            ])
            ->get()
            ->getResultArray();

        return $this->respond([
            'status' => 'success',
            'data' => $subjects
        ]);
    } catch (\Exception $e) {
        log_message('error', '[AddAlevelMarksController.getSubjects] Error: ' . $e->getMessage());
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
            'exam_id' => 'required|string|min_length[36]|max_length[36]',
            'student_id' => 'required|string|min_length[36]|max_length[36]',
            'class_id' => 'required|string|min_length[36]|max_length[36]',
            'session_id' => 'required|string|min_length[36]|max_length[36]',
            'combination_id' => 'required|string|min_length[36]|max_length[36]'
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

        // Debug log
        log_message('error', '[AddAlevelMarksController.saveMarks] Student ID received: ' . $studentId);
        
        // Verify student exists
        $studentExists = $this->studentModel->find($studentId);
        if (!$studentExists) {
            log_message('error', '[AddAlevelMarksController.saveMarks] Student not found in database: ' . $studentId);
            throw new \Exception('Student ID not found in database: ' . $studentId);
        }
        log_message('error', '[AddAlevelMarksController.saveMarks] Student found: ' . json_encode($studentExists));

        if (!is_array($marks)) {
            throw new \Exception('Invalid marks data format');
        }

        // Validate exam allocation
        $db = \Config\Database::connect('default');
        $examAllocation = $db->table('tz_alevel_exam_combinations')
            ->where([
                'exam_id' => $examId,
                'class_id' => $classId,
                'combination_id' => $combinationId,
                'session_id' => $sessionId,
                'is_active' => 'yes'
            ])
            ->countAllResults();

        if ($examAllocation === 0) {
            throw new \Exception('Exam is not allocated to this class and combination');
        }

        // Validate subjects
        $validSubjects = $db->table('tz_alevel_combination_subjects')
            ->select('id')
            ->where([
                'combination_id' => $combinationId,
                'is_active' => 'yes'
            ])
            ->get()
            ->getResultArray();

        $validSubjectIds = array_column($validSubjects, 'id');

        foreach ($marks as $subjectId => $mark) {
            if (!in_array($subjectId, $validSubjectIds)) {
                throw new \Exception("Invalid subject ID: $subjectId");
            }
            if ($mark !== null && ($mark < 0 || $mark > 100)) { // Default max_marks = 100
                throw new \Exception("Marks for subject ID $subjectId must be between 0 and 100");
            }
        }

        // Start transaction
        $this->alevelMarksModel->db->transStart();

        // Fix: Use array for where conditions instead of direct string with backticks
        $this->alevelMarksModel->where([
            'exam_id' => $examId,
            'student_id' => $studentId,
            'class_id' => $classId,
            'session_id' => $sessionId,
            'combination_id' => $combinationId
        ])->delete();

        // Insert new marks
        foreach ($marks as $subjectId => $mark) {
            if ($mark === null) {
                continue; // Skip null marks
            }
            $markData = [
                'exam_id' => $examId,
                'student_id' => $studentId,
                'class_id' => $classId,
                'session_id' => $sessionId,
                'combination_id' => $combinationId,
                'subject_id' => $subjectId,
                'marks_obtained' => $mark
            ];

            if (!$this->alevelMarksModel->insert($markData)) {
                throw new \Exception('Failed to save marks: ' . implode(', ', $this->alevelMarksModel->errors()));
            }
        }

        $this->alevelMarksModel->db->transComplete();

        if ($this->alevelMarksModel->db->transStatus() === false) {
            throw new \RuntimeException('Failed to save marks');
        }

        return $this->respond([
            'status' => 'success',
            'message' => 'Marks saved successfully'
        ]);
    } catch (\Exception $e) {
        log_message('error', '[AddAlevelMarksController.saveMarks] Error: ' . $e->getMessage());
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
            $marks = $db->table('tz_alevel_subject_marks')
                ->select('tz_alevel_subject_marks.*, tz_alevel_combination_subjects.subject_name')  // Removed max_marks
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
            log_message('error', '[AddAlevelMarksController.getExistingMarks] Error: ' . $e->getMessage());
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
            log_message('error', '[AddAlevelMarksController.getExams] Error: ' . $e->getMessage());
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
                ->join('tz_student_alevel_combinations sac', 'c.id = sac.class_id')
                ->where([
                    'sac.session_id' => $sessionId,
                    'sac.is_active' => 'yes',
                    'c.is_active' => 'yes'  // Changed from 'yes' to 'no'
                ])
                ->groupBy('c.id')
                ->get()
                ->getResultArray();

            return $this->respond([
                'status' => 'success',
                'data' => $classes
            ]);
        } catch (\Exception $e) {
            log_message('error', '[AddAlevelMarksController.getClasses] Error: ' . $e->getMessage());
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

            $db = \Config\Database::connect('default');
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
            log_message('error', '[AddAlevelMarksController.getCombinations] Error: ' . $e->getMessage());
            return $this->respond([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
?>
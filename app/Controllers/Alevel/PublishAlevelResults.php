<?php

namespace App\Controllers\Alevel;

use App\Controllers\BaseController;
use App\Models\AlevelCombinationModel;
use App\Models\StudentModel;
use App\Models\AlevelSubjectMarksModel;
use App\Models\ExamModel;
use App\Models\SessionModel;
use App\Models\AlevelExamResultModel;
use CodeIgniter\API\ResponseTrait;

class PublishAlevelResultsController extends BaseController
{
    use ResponseTrait;

    protected $alevelCombinationModel;
    protected $studentModel;
    protected $alevelMarksModel;
    protected $examModel;
    protected $sessionModel;
    protected $alevelExamResultModel;
    protected $format = 'json';

    public function __construct()
    {
        $this->alevelCombinationModel = new AlevelCombinationModel();
        $this->studentModel = new StudentModel();
        $this->alevelMarksModel = new AlevelSubjectMarksModel();
        $this->examModel = new ExamModel();
        $this->sessionModel = new SessionModel();
        $this->alevelExamResultModel = new AlevelExamResultModel();
    }

    public function index()
    {
        try {
            $data = [
                'sessions' => $this->sessionModel->where('is_active', 'no')->findAll(),
                'exams' => [],
                'classes' => [],
                'combinations' => []
            ];

            $currentSession = $this->sessionModel->getCurrentSession();
            if ($currentSession) {
                $data['current_session'] = $currentSession;
                $data['exams'] = $this->examModel
                    ->where('session_id', $currentSession['id'])
                    ->where('is_active', 'yes')
                    ->findAll();

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

            return view('alevel/AlevelCalculateResults', $data);
        } catch (\Exception $e) {
            log_message('error', '[AlevelResultsController.index] Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load results page: ' . $e->getMessage());
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
            log_message('error', '[AlevelResultsController.getExams] Error: ' . $e->getMessage());
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
            log_message('error', '[AlevelResultsController.getClasses] Error: ' . $e->getMessage());
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
            log_message('error', '[AlevelResultsController.getCombinations] Error: ' . $e->getMessage());
            return $this->respond([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function calculateResults()
    {
        try {
            $examId = $this->request->getGet('exam_id');
            $classId = $this->request->getGet('class_id');
            $sessionId = $this->request->getGet('session_id');
            $combinationId = $this->request->getGet('combination_id');

            if (!$examId || !$classId || !$sessionId || !$combinationId) {
                throw new \Exception('Missing required parameters');
            }

            // Fetch marks for major subjects only
            $db = \Config\Database::connect('second_db');
            $marks = $db->table('tz_alevel_subject_marks asm')
                ->select('
                    asm.student_id,
                    s.firstname,
                    s.lastname,
                    asm.subject_id,
                    acs.subject_name,
                    asm.marks_obtained
                ')
                ->join('tz_alevel_combination_subjects acs', 'acs.id = asm.subject_id')
                ->join('students s', 's.id = asm.student_id')
                ->join('tz_alevel_exam_combinations aec', 'aec.exam_id = asm.exam_id AND aec.class_id = asm.class_id AND aec.session_id = asm.session_id AND aec.combination_id = asm.combination_id')
                ->where([
                    'asm.exam_id' => $examId,
                    'asm.class_id' => $classId,
                    'asm.session_id' => $sessionId,
                    'asm.combination_id' => $combinationId,
                    'acs.subject_type' => 'major',
                    'acs.is_active' => 'yes',
                    'aec.is_active' => 'yes',
                    's.is_active' => 'yes'
                ])
                ->get()
                ->getResultArray();

            if (empty($marks)) {
                throw new \Exception('No marks found for the specified exam, class, session, and combination');
            }

            // Group marks by student
            $studentMarks = [];
            foreach ($marks as $mark) {
                $studentId = $mark['student_id'];
                if (!isset($studentMarks[$studentId])) {
                    $studentMarks[$studentId] = [
                        'name' => $mark['firstname'] . ' ' . $mark['lastname'],
                        'subjects' => []
                    ];
                }
                $studentMarks[$studentId]['subjects'][] = [
                    'subject_id' => $mark['subject_id'],
                    'subject_name' => $mark['subject_name'],
                    'marks_obtained' => $mark['marks_obtained']
                ];
            }

            // Calculate grades, points, and divisions
            $results = [];
            foreach ($studentMarks as $studentId => $data) {
                $subjects = $data['subjects'];
                $gradeDetails = [];
                $totalPoints = 0;

                // Ensure exactly 3 major subjects
                if (count($subjects) !== 3) {
                    log_message('warning', "[AlevelResultsController.calculateResults] Student ID $studentId has " . count($subjects) . " major subjects instead of 3");
                    continue;
                }

                foreach ($subjects as $subject) {
                    $marks = $subject['marks_obtained'] ?? null;
                    if ($marks === null) {
                        continue;
                    }

                    // Calculate grade and points
                    if ($marks >= 80) {
                        $grade = 'A';
                        $points = 1;
                    } elseif ($marks >= 70) {
                        $grade = 'B';
                        $points = 2;
                    } elseif ($marks >= 60) {
                        $grade = 'C';
                        $points = 3;
                    } elseif ($marks >= 50) {
                        $grade = 'D';
                        $points = 4;
                    } elseif ($marks >= 40) {
                        $grade = 'E';
                        $points = 5;
                    } elseif ($marks >= 30) {
                        $grade = 'S';
                        $points = 6;
                    } else {
                        $grade = 'F';
                        $points = 7;
                    }

                    $totalPoints += $points;
                    $gradeDetails[] = [
                        'subject_id' => $subject['subject_id'],
                        'subject_name' => $subject['subject_name'],
                        'marks_obtained' => $marks,
                        'grade' => $grade,
                        'points' => $points
                    ];
                }

                // Determine division
                if ($totalPoints >= 3 && $totalPoints <= 9) {
                    $division = 'I';
                } elseif ($totalPoints >= 10 && $totalPoints <= 12) {
                    $division = 'II';
                } elseif ($totalPoints >= 13 && $totalPoints <= 15) {
                    $division = 'III';
                } elseif ($totalPoints >= 16 && $totalPoints <= 18) {
                    $division = 'IV';
                } else {
                    $division = '0';
                }

                $results[] = [
                    'student_id' => $studentId,
                    'name' => $data['name'],
                    'total_points' => $totalPoints,
                    'division' => $division,
                    'grade_details' => $gradeDetails
                ];
            }

            // Save results to tz_alevel_exam_results
            $this->alevelExamResultModel->db->transStart();

            foreach ($results as $result) {
                // Delete existing result
                $this->alevelExamResultModel->where([
                    'exam_id' => $examId,
                    'student_id' => $result['student_id'],
                    'class_id' => $classId,
                    'session_id' => $sessionId,
                    'combination_id' => $combinationId
                ])->delete();

                // Insert new result
                $resultData = [
                    'exam_id' => $examId,
                    'student_id' => $result['student_id'],
                    'class_id' => $classId,
                    'session_id' => $sessionId,
                    'combination_id' => $combinationId,
                    'total_points' => $result['total_points'],
                    'division' => $result['division'],
                    'grade_details' => json_encode($result['grade_details']),
                    'is_active' => 'yes'
                ];

                if (!$this->alevelExamResultModel->insert($resultData)) {
                    throw new \Exception('Failed to save result for student ID ' . $result['student_id']);
                }
            }

            $this->alevelExamResultModel->db->transComplete();

            if ($this->alevelExamResultModel->db->transStatus() === false) {
                throw new \RuntimeException('Failed to save results');
            }

            return $this->respond([
                'status' => 'success',
                'message' => 'Results calculated and saved successfully',
                'data' => $results
            ]);
        } catch (\Exception $e) {
            log_message('error', '[AlevelResultsController.calculateResults] Error: ' . $e->getMessage());
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to calculate results: ' . $e->getMessage()
            ], 500);
        }
    }
}
?>
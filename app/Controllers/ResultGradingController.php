<?php

namespace App\Controllers;

use App\Models\ExamModel;
use App\Models\ExamSubjectModel;
use App\Models\ExamSubjectMarkModel;
use App\Models\ExamResultModel;
use App\Models\StudentModel;
use App\Models\StudentSessionModel;
use App\Models\ClassModel;
use App\Models\ClassSectionModel;
use App\Models\SessionModel;
use CodeIgniter\RESTful\ResourceController;

class ResultGradingController extends ResourceController
{
    protected $examModel;
    protected $examSubjectModel;
    protected $examSubjectMarkModel;
    protected $examResultModel;
    protected $studentModel;
    protected $studentSessionModel;
    protected $classModel;
    protected $classSectionModel;
    protected $sessionModel;

    public function __construct()
    {
        $this->examModel = new ExamModel();
        $this->examSubjectModel = new ExamSubjectModel();
        $this->examSubjectMarkModel = new ExamSubjectMarkModel();
        $this->examResultModel = new ExamResultModel();
        $this->studentModel = new StudentModel();
        $this->studentSessionModel = new StudentSessionModel();
        $this->classModel = new ClassModel();
        $this->classSectionModel = new ClassSectionModel();
        $this->sessionModel = new SessionModel();
    }

    public function index()
    {
        try {
            $data = [
                'sessions' => $this->sessionModel->where('is_active', 'no')->findAll(),
                'classes' => $this->classModel->where('is_active', 'no')->findAll(),
                'levels' => [
                    ['id' => 4, 'name' => 'O-Level'],
                    ['id' => 6, 'name' => 'A-Level']
                ]
            ];
            
            return view('results/PublishExamResult', $data);
        } catch (\Exception $e) {
            log_message('error', '[ResultGrading.index] Error: ' . $e->getMessage());
            return redirect()->to(base_url('dashboard'))
                ->with('error', 'Failed to load result publishing page');
        }
    }

    public function getBySession($sessionId)
    {
        try {
            if (!$sessionId) {
                return $this->fail('Session ID is required', 400);
            }

            $exams = $this->examModel
                ->where('session_id', $sessionId)
                ->where('is_active', 'no')
                ->findAll();

            return $this->respond([
                'status' => 'success',
                'data' => $exams
            ]);
        } catch (\Exception $e) {
            log_message('error', '[ResultGrading.getBySession] Error: ' . $e->getMessage());
            return $this->fail($e->getMessage());
        }
    }

    // In calculateResults method, update the student query
    public function calculateResults()
    {
        try {
            $examId = $this->request->getVar('exam_id');
            $classId = $this->request->getVar('class_id');
            $sectionId = $this->request->getVar('section_id');
            $sessionId = $this->request->getVar('session_id');
            $level = $this->request->getVar('level'); // Add level parameter

            if (!$examId || !$classId || !$sessionId || !$level) {
                return $this->fail('Exam, Class, Session and Level are required', 400);
            }

            $isALevel = $level == 6;

            // Get students in the class
            $students = $this->studentSessionModel
                ->where('class_id', $classId)
                ->where('session_id', $sessionId)
                ->where('is_active', 'no')
                ->findAll();

            $results = [];
            foreach ($students as $student) {
                // Get all subject marks for the student
                $marks = $this->examSubjectMarkModel
                    ->select('
                        tz_exam_subject_marks.*,
                        tz_exam_subjects.subject_name,
                        tz_exam_subjects.max_marks,
                        tz_exam_subjects.passing_marks
                    ')
                    ->join('tz_exam_subjects', 'tz_exam_subjects.id = tz_exam_subject_marks.exam_subject_id')
                    ->where([
                        'tz_exam_subject_marks.student_id' => $student['student_id'],
                        'tz_exam_subject_marks.exam_id' => $examId,
                        'tz_exam_subject_marks.class_id' => $classId,
                        'tz_exam_subject_marks.session_id' => $sessionId
                    ])
                    ->findAll();

                if (empty($marks)) {
                    continue;
                }

                // Calculate grades and points for each subject
                $subjectPoints = [];
                $gradedSubjects = [];
                foreach ($marks as $mark) {
                    $gradeInfo = $isALevel ? 
                        $this->getALevelGrade($mark['marks_obtained']) :
                        $this->getOLevelGrade($mark['marks_obtained']);
                    
                    $subjectPoints[] = $gradeInfo['points'];
                    $gradedSubjects[] = array_merge($mark, $gradeInfo);
                }

                // Calculate division
                $divisionInfo = $isALevel ? 
                    $this->calculateALevelDivision($subjectPoints) :
                    $this->calculateOLevelDivision($subjectPoints);

                // Save or update result
                $resultData = [
                    'student_id' => $student['student_id'],
                    'exam_id' => $examId,
                    'class_id' => $classId,
                    'session_id' => $sessionId,
                    'total_points' => $divisionInfo['total_points'],
                    'division' => $divisionInfo['division'],
                    'division_description' => $divisionInfo['description']
                ];

                $existingResult = $this->examResultModel
                    ->where('student_id', $student['student_id'])
                    ->where('exam_id', $examId)
                    ->first();

                if ($existingResult) {
                    $this->examResultModel->update($existingResult['id'], $resultData);
                } else {
                    $this->examResultModel->insert($resultData);
                }

                $results[] = [
                    'student_id' => $student['student_id'],
                    'subjects' => $gradedSubjects,
                    'division' => $divisionInfo
                ];
            }

            // Get exam details to determine if it's O-Level or A-Level
            $exam = $this->examModel->find($examId);
            $isALevel = $exam['level'] == 6;

            // Remove Redis cache check and just return the results
            return $this->respond([
                'status' => 'success',
                'data' => $results
            ]);

        } catch (\Exception $e) {
            log_message('error', '[ResultGrading.calculateResults] Error: ' . $e->getMessage());
            return $this->fail($e->getMessage());
        }
    }

    private function getOLevelGrade($marks)
    {
        if ($marks >= 75) {
            return ['grade' => 'A', 'points' => 1, 'remarks' => 'Excellent'];
        } elseif ($marks >= 65) {
            return ['grade' => 'B', 'points' => 2, 'remarks' => 'Very Good'];
        } elseif ($marks >= 45) {
            return ['grade' => 'C', 'points' => 3, 'remarks' => 'Good'];
        } elseif ($marks >= 30) {
            return ['grade' => 'D', 'points' => 4, 'remarks' => 'Satisfactory'];
        } else {
            return ['grade' => 'F', 'points' => 5, 'remarks' => 'Fail'];
        }
    }

    private function getALevelGrade($marks)
    {
        if ($marks >= 80) {
            return ['grade' => 'A', 'points' => 1, 'remarks' => 'Excellent'];
        } elseif ($marks >= 70) {
            return ['grade' => 'B', 'points' => 2, 'remarks' => 'Very Good'];
        } elseif ($marks >= 60) {
            return ['grade' => 'C', 'points' => 3, 'remarks' => 'Good'];
        } elseif ($marks >= 50) {
            return ['grade' => 'D', 'points' => 4, 'remarks' => 'Average'];
        } elseif ($marks >= 40) {
            return ['grade' => 'E', 'points' => 5, 'remarks' => 'Satisfactory'];
        } elseif ($marks >= 35) {
            return ['grade' => 'S', 'points' => 6, 'remarks' => 'Subsidiary'];
        } else {
            return ['grade' => 'F', 'points' => 7, 'remarks' => 'Fail'];
        }
    }

    private function calculateOLevelDivision($points)
    {
        sort($points); // Fix: sort() returns void, should be called separately
        $best7 = array_slice($points, 0, 7);
        $totalPoints = array_sum($best7);

        if ($totalPoints <= 17) {
            return ['division' => 'I', 'description' => 'Excellent', 'total_points' => $totalPoints];
        } elseif ($totalPoints <= 21) {
            return ['division' => 'II', 'description' => 'Very Good', 'total_points' => $totalPoints];
        } elseif ($totalPoints <= 25) {
            return ['division' => 'III', 'description' => 'Good', 'total_points' => $totalPoints];
        } elseif ($totalPoints <= 33) {
            return ['division' => 'IV', 'description' => 'Satisfactory', 'total_points' => $totalPoints];
        } else {
            return ['division' => 'O', 'description' => 'Fail', 'total_points' => $totalPoints];
        }
    }

    private function calculateALevelDivision($points)
    {
        sort($points); // Fix: sort() returns void, should be called separately
        $best3 = array_slice($points, 0, 3);
        $totalPoints = array_sum($best3);

        if ($totalPoints <= 9) {
            return ['division' => 'I', 'description' => 'Excellent', 'total_points' => $totalPoints];
        } elseif ($totalPoints <= 12) {
            return ['division' => 'II', 'description' => 'Very Good', 'total_points' => $totalPoints];
        } elseif ($totalPoints <= 17) {
            return ['division' => 'III', 'description' => 'Good', 'total_points' => $totalPoints];
        } elseif ($totalPoints <= 19) {
            return ['division' => 'IV', 'description' => 'Satisfactory', 'total_points' => $totalPoints];
        } else {
            return ['division' => 'O', 'description' => 'Fail', 'total_points' => $totalPoints];
        }
    }

    public function getResultsByClass()
    {
        try {
            $examId = $this->request->getVar('exam_id');
            $classId = $this->request->getVar('class_id');
            $sessionId = $this->request->getVar('session_id');

            if (!$examId || !$classId || !$sessionId) {
                return $this->fail('Exam, Class and Session are required', 400);
            }

            $results = $this->examResultModel
                ->select('
                    tz_exam_results.*,
                    students.firstname,
                    students.lastname,
                    students.roll_no
                ')
                ->join('students', 'students.id = tz_exam_results.student_id')
                ->join('student_session', 'student_session.student_id = students.id')
                ->where([
                    'tz_exam_results.exam_id' => $examId,
                    'tz_exam_results.class_id' => $classId,
                    'tz_exam_results.session_id' => $sessionId,
                    'student_session.is_active' => 'no'
                ])
                ->findAll();

            return $this->respond([
                'status' => 'success',
                'data' => $results
            ]);
        } catch (\Exception $e) {
            log_message('error', '[ResultGrading.getResultsByClass] Error: ' . $e->getMessage());
            return $this->fail($e->getMessage());
        }
    }

    public function getExamsByClass()
    {
        try {
            $classId = $this->request->getVar('class_id');
            $sessionId = $this->request->getVar('session_id');

            if (!$classId || !$sessionId) {
                return $this->fail('Class and Session are required', 400);
            }

            $exams = $this->examModel
                ->where('session_id', $sessionId)
                ->where('is_active', 'yes')  // Changed from 'no' to 'yes'
                ->findAll();

            return $this->respond([
                'status' => 'success',
                'data' => $exams
            ]);
        } catch (\Exception $e) {
            log_message('error', '[ResultGrading.getBySession] Error: ' . $e->getMessage());
            return $this->fail($e->getMessage());
        }
    }
}
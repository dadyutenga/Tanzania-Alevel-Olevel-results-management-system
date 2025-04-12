<?php

namespace App\Controllers;

class OLevelController extends ResultGradingController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function processOLevelGrades($examId, $classId, $sectionId = null, $sessionId = null)
    {
        try {
            // Fix the query by starting with students and using proper join order
            $query = $this->examSubjectMarkModel
                ->select('
                    s.id AS student_id,
                    CONCAT(s.firstname, " ", COALESCE(s.middlename, ""), " ", s.lastname) AS full_name,
                    c.class AS class_name,
                    cs.section_id AS section,
                    te.exam_name,
                    tes.subject_name,
                    tesm.marks_obtained
                ')
                ->from('students s')  // Start with students table
                ->join('student_session ss', 's.id = ss.student_id')
                ->join('classes c', 'ss.class_id = c.id')
                ->join('class_sections cs', 'ss.section_id = cs.section_id AND ss.class_id = cs.class_id')
                ->join('tz_exam_classes tec', 'tec.class_id = ss.class_id AND tec.session_id = ss.session_id')
                ->join('tz_exams te', 'te.id = tec.exam_id')
                ->join('tz_exam_subjects tes', 'tes.exam_id = te.id')
                ->join('tz_exam_subject_marks tesm', 'tesm.student_id = s.id 
                    AND tesm.exam_subject_id = tes.id 
                    AND tesm.exam_id = te.id 
                    AND tesm.class_id = ss.class_id', 'left')
                ->where('te.id', $examId)
                ->where('ss.class_id', $classId)
                ->where('s.is_active', 'yes')
                ->where('te.is_active', 'yes');

            if ($sectionId) {
                $query->where('ss.section_id', $sectionId);
            }
            if ($sessionId) {
                $query->where('ss.session_id', $sessionId);
            }

            $marks = $query->orderBy('full_name')
                          ->orderBy('te.exam_name')
                          ->orderBy('tes.subject_name')
                          ->findAll();

            // O-Level specific grading logic
            $gradeScale = [
                ['min' => 75, 'grade' => 'A', 'points' => 1],
                ['min' => 65, 'grade' => 'B', 'points' => 2],
                ['min' => 45, 'grade' => 'C', 'points' => 3],
                ['min' => 30, 'grade' => 'D', 'points' => 4],
                ['min' => 0, 'grade' => 'F', 'points' => 5]
            ];

            // Group marks by student
            $studentGroups = [];
            foreach ($marks as $mark) {
                if (!isset($studentGroups[$mark['student_id']])) {
                    $studentGroups[$mark['student_id']] = [];
                }
                $studentGroups[$mark['student_id']][] = $mark;
            }

            $processedResults = [];
            $successCount = 0;
            
            foreach ($studentGroups as $studentId => $studentMarks) {
                try {
                    if (empty($studentMarks)) {
                        continue;
                    }

                    $result = $this->processStudentGrades($studentMarks, $gradeScale);
                    
                    $resultData = [
                        'student_id' => $studentId,
                        'exam_id' => $examId,
                        'class_id' => $classId,
                        'session_id' => $sessionId,
                        'total_points' => $result['total_points'],
                        'division' => $result['division'],
                        'division_description' => $this->getDivisionDescription($result['division'])
                    ];

                    // Check if result exists
                    $existingResult = $this->examResultModel
                        ->where([
                            'student_id' => $studentId,
                            'exam_id' => $examId,
                            'class_id' => $classId,
                            'session_id' => $sessionId
                        ])
                        ->first();

                    if ($existingResult) {
                        $this->examResultModel->update($existingResult['id'], $resultData);
                    } else {
                        $this->examResultModel->insert($resultData);
                    }
                    
                    $successCount++;
                    $processedResults[] = array_merge($resultData, [
                        'student_name' => $studentMarks[0]['full_name'] ?? 'Unknown',
                        'debug_info' => $result['debug_info'] ?? [] // Add debug information to response
                    ]);

                } catch (\Exception $e) {
                    log_message('error', '[OLevel.processGrades] Student ID ' . $studentId . ' Error: ' . $e->getMessage());
                    continue;
                }
            }

            if ($successCount === 0) {
                return [
                    'status' => 'error',
                    'message' => 'No results were processed successfully'
                ];
            }

            return [
                'status' => 'success',
                'message' => $successCount . ' student grades processed successfully',
                'data' => $processedResults
            ];

        } catch (\Exception $e) {
            log_message('error', '[OLevel.processGrades] Error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Failed to process grades: ' . $e->getMessage()
            ];
        }
    }

    private function calculateDivision($totalPoints)
    {
        // Division ranges based on total points from best 7 subjects
        if ($totalPoints >= 7 && $totalPoints <= 17) return 'I';     // Division I: 7-17 points
        if ($totalPoints >= 18 && $totalPoints <= 21) return 'II';   // Division II: 18-21 points
        if ($totalPoints >= 22 && $totalPoints <= 25) return 'III';  // Division III: 22-25 points
        if ($totalPoints >= 26 && $totalPoints <= 33) return 'IV';   // Division IV: 26-33 points
        else return 'O';    
                                                          // Division F: > 35 points
    }

    private function processStudentGrades($studentMarks, $gradeScale)
    {
        $subjects = [];
        $requiredSubjects = 7;
        $debugInfo = [
            'student_name' => $studentMarks[0]['full_name'] ?? 'Unknown',
            'all_subjects' => [],
            'best_subjects' => [],
            'total_points' => 0
        ];

        // First, collect all valid subjects
        foreach ($studentMarks as $mark) {
            // Skip empty or invalid marks
            if (!isset($mark['marks_obtained']) || 
                $mark['marks_obtained'] === '' || 
                $mark['marks_obtained'] === null) {
                continue;
            }
            
            $grade = $this->getGrade($mark['marks_obtained'], $gradeScale);
            $subjectInfo = [
                'subject' => $mark['subject_name'],
                'marks' => $mark['marks_obtained'],
                'grade' => $grade['grade'],
                'points' => $grade['points']
            ];
            
            $subjects[] = $subjectInfo;
            $debugInfo['all_subjects'][] = $subjectInfo;

            // Log for debugging
            log_message('debug', sprintf(
                "Initial Subject Data - Student: %s, Subject: %s, Marks: %d, Grade: %s, Points: %d",
                $debugInfo['student_name'],
                $mark['subject_name'],
                $mark['marks_obtained'],
                $grade['grade'],
                $grade['points']
            ));
        }

        // Validate minimum subjects requirement
        if (count($subjects) < $requiredSubjects) {
            throw new \Exception(sprintf(
                'Student %s has insufficient subjects: %d (required: %d)',
                $debugInfo['student_name'],
                count($subjects),
                $requiredSubjects
            ));
        }

        // Sort subjects by points (ascending: best grades first)
        usort($subjects, function ($a, $b) {
            return $a['points'] <=> $b['points'];  // Using spaceship operator for cleaner comparison
        });

        // Log sorted subjects
        log_message('debug', "Sorted subjects for {$debugInfo['student_name']}:");
        foreach ($subjects as $subject) {
            log_message('debug', sprintf(
                "Subject: %s, Points: %d",
                $subject['subject'],
                $subject['points']
            ));
        }

        // Pick the best 7 subjects
        $bestSubjects = array_slice($subjects, 0, 7);
        $debugInfo['best_subjects'] = $bestSubjects;

        // Log best 7 subjects
        log_message('debug', "Best 7 subjects selected for {$debugInfo['student_name']}:");
        foreach ($bestSubjects as $subject) {
            log_message('debug', sprintf(
                "Selected Subject: %s, Points: %d",
                $subject['subject'],
                $subject['points']
            ));
        }

        // Sum their points using array_column for cleaner code
        $totalPoints = array_sum(array_column($bestSubjects, 'points'));
        $debugInfo['total_points'] = $totalPoints;

        // Log total points
        log_message('debug', sprintf(
            "Total Points for %s: %d",
            $debugInfo['student_name'],
            $totalPoints
        ));

        // Calculate division
        $division = $this->calculateDivision($totalPoints);

        // Log final division
        log_message('debug', sprintf(
            "Final Division for %s: %s (Points: %d)",
            $debugInfo['student_name'],
            $division,
            $totalPoints
        ));

        return [
            'total_points' => $totalPoints,
            'division' => $division,
            'debug_info' => $debugInfo
        ];
    }

    private function getDivisionDescription($division)
    {
        $descriptions = [
            'I' => 'Excellent',
            'II' => 'Very Good',
            'III' => 'Good',
            'IV' => 'Satisfactory',
            'O' => 'Fail'
         
        ];
        return $descriptions[$division] ?? 'Unknown';
    }

    private function getGrade($marks, $gradeScale)
    {
        foreach ($gradeScale as $grade) {
            if ($marks >= $grade['min']) {
                return $grade;
            }
        }
        return end($gradeScale);
    }
}
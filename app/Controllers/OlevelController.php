<?php

namespace App\Controllers;

class OLevelController extends ResultGradingController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function processOLevelGrades($examId, $classId, $sectionId = null, $sessionId = null, $studentId = null)
    {
        try {
            // Validate input parameters
            if (!is_numeric($examId) || $examId <= 0) {
                throw new \Exception('Invalid exam ID');
            }
            if (!is_numeric($classId) || $classId <= 0) {
                throw new \Exception('Invalid class ID');
            }
            if ($sectionId !== null && (!is_numeric($sectionId) || $sectionId <= 0)) {
                throw new \Exception('Invalid section ID');
            }
            if ($sessionId !== null && (!is_numeric($sessionId) || $sessionId <= 0)) {
                throw new \Exception('Invalid session ID');
            }
            if ($studentId !== null && (!is_numeric($studentId) || $studentId <= 0)) {
                throw new \Exception('Invalid student ID');
            }

            // Build query
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
                ->from('students s')
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
            if ($studentId) {
                $query->where('s.id', $studentId);
            }

            $marks = $query->orderBy('full_name')
                          ->orderBy('te.exam_name')
                          ->orderBy('tes.subject_name')
                          ->findAll();

            // Log raw marks for debugging
            log_message('debug', 'Raw marks fetched: ' . json_encode($marks));

            // O-Level grading scale
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

            // Log grouped marks
            log_message('debug', 'Student groups: ' . json_encode(array_keys($studentGroups)));

            $processedResults = [];
            $successCount = 0;

            foreach ($studentGroups as $studentId => $studentMarks) {
                try {
                    if (empty($studentMarks)) {
                        log_message('error', 'No marks for student ID: ' . $studentId);
                        continue;
                    }

                    $result = $this->processStudentGrades($studentMarks, $gradeScale);

                    $resultData = [
                        'student_id' => $studentId,
                        'exam_id' => $examId,
                        'class_id' => $classId,
                        'session_id' => $sessionId ?? null,
                        'total_points' => $result['total_points'],
                        'division' => $result['division'],
                        'division_description' => $this->getDivisionDescription($result['division'])
                    ];

                    // Log result data before storage
                    log_message('debug', 'Result data for student ' . $studentId . ': ' . json_encode($resultData));

                    // Store result
                    $existingResult = $this->examResultModel
                        ->where([
                            'student_id' => $studentId,
                            'exam_id' => $examId,
                            'class_id' => $classId,
                            'session_id' => $sessionId ?? null
                        ])
                        ->first();

                    if ($existingResult) {
                        if (!$this->examResultModel->update($existingResult['id'], $resultData)) {
                            log_message('error', 'Failed to update result for student ' . $studentId);
                            continue;
                        }
                    } else {
                        if (!$this->examResultModel->insert($resultData)) {
                            log_message('error', 'Failed to insert result for student ' . $studentId);
                            continue;
                        }
                    }

                    $successCount++;
                    $processedResults[] = array_merge($resultData, [
                        'student_name' => $studentMarks[0]['full_name'] ?? 'Unknown',
                        'debug_info' => $result['debug_info'] ?? []
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
        if ($totalPoints >= 7 && $totalPoints <= 17) return 'I';
        if ($totalPoints >= 18 && $totalPoints <= 21) return 'II';
        if ($totalPoints >= 22 && $totalPoints <= 25) return 'III';
        if ($totalPoints >= 26 && $totalPoints <= 33) return 'IV';
        return 'O';
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
        $subjectNames = []; // Track subjects to prevent duplicates

        foreach ($studentMarks as $mark) {
            // Validate marks_obtained
            if (!isset($mark['marks_obtained']) || 
                !is_numeric($mark['marks_obtained']) || 
                $mark['marks_obtained'] === '' || 
                $mark['marks_obtained'] === null) {
                log_message('error', sprintf(
                    'Invalid mark for student %s, subject %s: %s',
                    $debugInfo['student_name'],
                    $mark['subject_name'] ?? 'Unknown',
                    json_encode($mark)
                ));
                continue;
            }

            $markValue = floatval($mark['marks_obtained']);

            // Validate mark range
            if ($markValue < 0 || $markValue > 100) {
                log_message('error', sprintf(
                    'Out-of-range mark for student %s, subject %s: %f',
                    $debugInfo['student_name'],
                    $mark['subject_name'] ?? 'Unknown',
                    $markValue
                ));
                continue;
            }

            // Check for duplicate subjects
            $subjectName = strtolower(trim($mark['subject_name']));
            if (in_array($subjectName, $subjectNames)) {
                log_message('error', sprintf(
                    'Duplicate subject for student %s: %s',
                    $debugInfo['student_name'],
                    $subjectName
                ));
                continue;
            }
            $subjectNames[] = $subjectName;

            $grade = $this->getGrade($markValue, $gradeScale);
            $subjectInfo = [
                'subject' => $mark['subject_name'],
                'marks' => $markValue,
                'grade' => $grade['grade'],
                'points' => $grade['points']
            ];

            $subjects[] = $subjectInfo;
            $debugInfo['all_subjects'][] = $subjectInfo;

            // Log subject details
            log_message('debug', sprintf(
                'Subject: %s, Marks: %f, Grade: %s, Points: %d for student %s',
                $subjectInfo['subject'],
                $subjectInfo['marks'],
                $subjectInfo['grade'],
                $subjectInfo['points'],
                $debugInfo['student_name']
            ));
        }

        // Validate minimum subjects
        if (count($subjects) < $requiredSubjects) {
            throw new \Exception(sprintf(
                'Student %s has insufficient subjects: %d (required: %d)',
                $debugInfo['student_name'],
                count($subjects),
                $requiredSubjects
            ));
        }

        // Sort subjects by points
        usort($subjects, function ($a, $b) {
            return $a['points'] <=> $b['points'];
        });

        // Select best 7 subjects
        $bestSubjects = array_slice($subjects, 0, 7);
        $debugInfo['best_subjects'] = $bestSubjects;

        // Sum points
        $totalPoints = array_sum(array_column($bestSubjects, 'points'));
        $debugInfo['total_points'] = $totalPoints;

        // Calculate division
        $division = $this->calculateDivision($totalPoints);

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
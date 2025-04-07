<?php

namespace App\Controllers;

class ALevelController extends ResultGradingController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function processALevelGrades($examId, $classId)
    {
        try {
            // A-Level specific grading logic
            $marks = $this->examSubjectMarkModel
                ->select('
                    students.id as student_id,
                    students.firstname,
                    students.lastname,
                    tz_exam_subject_marks.obtained_marks,
                    tz_exam_subjects.full_marks,
                    tz_exam_subjects.subject_name,
                    tz_exam_subjects.subject_type
                ')
                ->join('students', 'students.id = tz_exam_subject_marks.student_id')
                ->join('tz_exam_subjects', 'tz_exam_subjects.id = tz_exam_subject_marks.exam_subject_id')
                ->where('tz_exam_subject_marks.exam_id', $examId)
                ->where('tz_exam_subject_marks.class_id', $classId)
                ->findAll();

            // A-Level grading scale
            $gradeScale = [
                ['min' => 80, 'grade' => 'A', 'points' => 5],
                ['min' => 70, 'grade' => 'B', 'points' => 4],
                ['min' => 60, 'grade' => 'C', 'points' => 3],
                ['min' => 50, 'grade' => 'D', 'points' => 2],
                ['min' => 40, 'grade' => 'E', 'points' => 1],
                ['min' => 0, 'grade' => 'F', 'points' => 0]
            ];

            return $this->calculateGrades($marks, $gradeScale);

        } catch (\Exception $e) {
            log_message('error', '[ALevel.processGrades] Error: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Failed to process A-Level grades'];
        }
    }

    private function calculateGrades($marks, $gradeScale)
    {
        // Process student grades according to A-Level standards
        $results = [];
        $studentMarks = [];

        foreach ($marks as $mark) {
            if (!isset($studentMarks[$mark->student_id])) {
                $studentMarks[$mark->student_id] = [
                    'student_id' => $mark->student_id,
                    'name' => $mark->firstname . ' ' . $mark->lastname,
                    'subjects' => [],
                    'total_points' => 0,
                    'principal_points' => 0,
                    'subsidiary_points' => 0
                ];
            }

            $grade = $this->getGrade($mark->obtained_marks, $gradeScale);
            $subjectData = [
                'subject' => $mark->subject_name,
                'marks' => $mark->obtained_marks,
                'grade' => $grade['grade'],
                'points' => $grade['points'],
                'type' => $mark->subject_type
            ];

            $studentMarks[$mark->student_id]['subjects'][] = $subjectData;
            
            // Calculate points based on subject type
            if ($mark->subject_type === 'principal') {
                $studentMarks[$mark->student_id]['principal_points'] += $grade['points'];
            } else {
                $studentMarks[$mark->student_id]['subsidiary_points'] += $grade['points'];
            }
            
            $studentMarks[$mark->student_id]['total_points'] += $grade['points'];
        }

        // Calculate final grades and classifications
        foreach ($studentMarks as $studentId => $data) {
            $data['classification'] = $this->calculateClassification(
                $data['principal_points'],
                $data['subsidiary_points']
            );
            $results[] = $data;
        }

        return ['status' => 'success', 'data' => $results];
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

    private function calculateClassification($principalPoints, $subsidiaryPoints)
    {
        $totalPoints = $principalPoints + ($subsidiaryPoints * 0.5);
        
        if ($totalPoints >= 15) return 'FIRST CLASS';
        if ($totalPoints >= 12) return 'UPPER SECOND';
        if ($totalPoints >= 9) return 'LOWER SECOND';
        if ($totalPoints >= 6) return 'PASS';
        return 'FAIL';
    }
}
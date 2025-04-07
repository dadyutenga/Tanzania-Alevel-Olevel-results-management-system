<?php

namespace App\Controllers;

class OLevelController extends ResultGradingController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function processOLevelGrades($examId, $classId)
    {
        try {
            // O-Level specific grading logic
            $marks = $this->examSubjectMarkModel
                ->select('
                    students.id as student_id,
                    students.firstname,
                    students.lastname,
                    tz_exam_subject_marks.obtained_marks,
                    tz_exam_subjects.full_marks,
                    tz_exam_subjects.subject_name
                ')
                ->join('students', 'students.id = tz_exam_subject_marks.student_id')
                ->join('tz_exam_subjects', 'tz_exam_subjects.id = tz_exam_subject_marks.exam_subject_id')
                ->where('tz_exam_subject_marks.exam_id', $examId)
                ->where('tz_exam_subject_marks.class_id', $classId)
                ->findAll();

            // O-Level grading scale
            $gradeScale = [
                ['min' => 75, 'grade' => 'A', 'points' => 1],
                ['min' => 65, 'grade' => 'B+', 'points' => 2],
                ['min' => 55, 'grade' => 'B', 'points' => 3],
                ['min' => 45, 'grade' => 'C', 'points' => 4],
                ['min' => 35, 'grade' => 'D', 'points' => 5],
                ['min' => 0, 'grade' => 'F', 'points' => 6]
            ];

            return $this->calculateGrades($marks, $gradeScale);

        } catch (\Exception $e) {
            log_message('error', '[OLevel.processGrades] Error: ' . $e->getMessage());
            return ['status' => 'error', 'message' => 'Failed to process O-Level grades'];
        }
    }

    private function calculateGrades($marks, $gradeScale)
    {
        // Process student grades according to O-Level standards
        $results = [];
        $studentMarks = [];

        foreach ($marks as $mark) {
            if (!isset($studentMarks[$mark->student_id])) {
                $studentMarks[$mark->student_id] = [
                    'student_id' => $mark->student_id,
                    'name' => $mark->firstname . ' ' . $mark->lastname,
                    'subjects' => [],
                    'total_points' => 0,
                    'average' => 0
                ];
            }

            $grade = $this->getGrade($mark->obtained_marks, $gradeScale);
            $studentMarks[$mark->student_id]['subjects'][] = [
                'subject' => $mark->subject_name,
                'marks' => $mark->obtained_marks,
                'grade' => $grade['grade'],
                'points' => $grade['points']
            ];
            $studentMarks[$mark->student_id]['total_points'] += $grade['points'];
        }

        // Calculate averages and final grades
        foreach ($studentMarks as $studentId => $data) {
            $subjectCount = count($data['subjects']);
            if ($subjectCount > 0) {
                $data['average'] = $data['total_points'] / $subjectCount;
                $data['division'] = $this->calculateDivision($data['average']);
                $results[] = $data;
            }
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

    private function calculateDivision($average)
    {
        if ($average <= 2) return 'DIVISION I';
        if ($average <= 3) return 'DIVISION II';
        if ($average <= 4) return 'DIVISION III';
        if ($average <= 5) return 'DIVISION IV';
        return 'FAIL';
    }
}
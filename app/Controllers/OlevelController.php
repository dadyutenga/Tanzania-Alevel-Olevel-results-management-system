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
                ->join('students s', 's.id = tesm.student_id')
                ->join('student_session ss', 's.id = ss.student_id')
                ->join('classes c', 'ss.class_id = c.id')
                ->join('class_sections cs', 'ss.section_id = cs.section_id AND ss.class_id = cs.class_id')
                ->join('tz_exam_classes tec', 'tec.class_id = ss.class_id AND tec.session_id = ss.session_id')
                ->join('tz_exams te', 'te.id = tec.exam_id')
                ->join('tz_exam_subjects tes', 'tes.exam_id = te.id')
                ->join('tz_exam_subject_marks tesm', 'tesm.student_id = s.id AND tesm.exam_subject_id = tes.id AND tesm.exam_id = te.id AND tesm.class_id = ss.class_id')
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
        $results = [];
        $studentMarks = [];
        $requiredSubjects = 7; // O-Level requires best 7 subjects

        // First pass: Group all subjects by student
        foreach ($marks as $mark) {
            if (!isset($studentMarks[$mark->student_id])) {
                $studentMarks[$mark->student_id] = [
                    'student_id' => $mark->student_id,
                    'name' => $mark->full_name,
                    'class' => $mark->class_name,
                    'section' => $mark->section,
                    'subjects' => [],
                    'total_points' => 0
                ];
            }

            $grade = $this->getGrade($mark->marks_obtained, $gradeScale);
            $studentMarks[$mark->student_id]['subjects'][] = [
                'subject' => $mark->subject_name,
                'marks' => $mark->marks_obtained,
                'grade' => $grade['grade'],
                'points' => $grade['points']
            ];
        }

        // Second pass: Calculate grades based on best 7 subjects
        foreach ($studentMarks as $studentId => $data) {
            // Sort subjects by points (ascending since A=1, F=6)
            usort($data['subjects'], function($a, $b) {
                return $a['points'] - $b['points'];
            });

            // Take best 7 subjects (or all if less than 7)
            $bestSubjects = array_slice($data['subjects'], 0, $requiredSubjects);
            
            // Calculate total points from best 7 subjects
            $totalPoints = 0;
            foreach ($bestSubjects as $subject) {
                $totalPoints += $subject['points'];
            }

            $data['subjects'] = $bestSubjects;
            $data['total_points'] = $totalPoints;
            $data['subject_count'] = count($bestSubjects);
            $data['division'] = $this->calculateDivision($totalPoints / $data['subject_count']);

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

    private function calculateDivision($average)
    {
        if ($average <= 2) return 'DIVISION I';
        if ($average <= 3) return 'DIVISION II';
        if ($average <= 4) return 'DIVISION III';
        if ($average <= 5) return 'DIVISION IV';
        return 'FAIL';
    }
}
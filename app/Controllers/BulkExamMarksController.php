<?php

namespace App\Controllers;

use App\Models\ExamModel;
use App\Models\ExamClassModel;
use App\Models\StudentSessionModel;
use App\Models\ExamSubjectMarkModel;
use CodeIgniter\RESTful\ResourceController;

class BulkExamMarksController extends ResourceController
{
    protected $examModel;
    protected $examClassModel;
    protected $studentSessionModel;
    protected $examSubjectMarkModel;
    protected $format = 'json';

    public function __construct()
    {
        $this->examModel = new ExamModel();
        $this->examClassModel = new ExamClassModel();
        $this->studentSessionModel = new StudentSessionModel();
        $this->examSubjectMarkModel = new ExamSubjectMarkModel();
    }

    public function downloadTemplate()
    {
        try {
            $examId = $this->request->getGet('exam_id');
            $classId = $this->request->getGet('class_id');
            $sessionId = $this->request->getGet('session_id');

            if (!$examId || !$classId || !$sessionId) {
                throw new \Exception('Missing required parameters');
            }

            // Get students
            $students = $this->studentSessionModel
                ->select('students.id, students.firstname, students.lastname, students.roll_no')
                ->join('students', 'students.id = student_session.student_id')
                ->where([
                    'student_session.session_id' => $sessionId,
                    'student_session.class_id' => $classId,
                    'student_session.is_active' => 'no',
                    'students.is_active' => 'yes'
                ])
                ->findAll();

            // Get subjects
            $db = \Config\Database::connect('second_db');
            $subjects = $db->table('tz_exam_subjects')
                ->where('exam_id', $examId)
                ->get()
                ->getResultArray();

            // Create CSV headers
            $headers = ['Student ID', 'Student Name', 'Roll Number'];
            foreach ($subjects as $subject) {
                $headers[] = $subject['subject_name'] . ' (Max: ' . $subject['max_marks'] . ')';
                $headers[] = $subject['id']; // Hidden subject ID
            }

            // Create CSV content
            $output = fopen('php://temp', 'w+');
            fputcsv($output, $headers);

            foreach ($students as $student) {
                $row = [
                    $student['id'],
                    $student['firstname'] . ' ' . $student['lastname'],
                    $student['roll_no']
                ];
                // Add empty columns for marks
                foreach ($subjects as $subject) {
                    $row[] = ''; // Empty mark field
                    $row[] = $subject['id']; // Hidden subject ID
                }
                fputcsv($output, $row);
            }

            // Get CSV content
            rewind($output);
            $csv = stream_get_contents($output);
            fclose($output);

            // Set headers for download
            $filename = "exam_marks_template_" . date('Y-m-d_His') . ".csv";
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');

            return $this->response->setBody($csv);

        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function uploadMarks()
    {
        try {
            $examId = $this->request->getPost('exam_id');
            $classId = $this->request->getPost('class_id');
            $sessionId = $this->request->getPost('session_id');

            if (!$examId || !$classId || !$sessionId) {
                throw new \Exception('Missing required parameters');
            }

            $file = $this->request->getFile('csv_file');
            if (!$file->isValid()) {
                throw new \Exception('Invalid file uploaded');
            }

            if ($file->getExtension() !== 'csv') {
                throw new \Exception('Only CSV files are allowed');
            }

            // Start transaction
            $db = \Config\Database::connect('second_db');
            $db->transStart();

            $handle = fopen($file->getTempName(), 'r');
            $headers = fgetcsv($handle);
            $subjectIds = [];
            
            // Extract subject IDs from headers
            for ($i = 3; $i < count($headers); $i += 2) {
                $subjectIds[] = $headers[$i + 1];
            }

            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            // Process each row
            while (($row = fgetcsv($handle)) !== false) {
                try {
                    $studentId = $row[0];
                    
                    // Delete existing marks for this student
                    $this->examSubjectMarkModel->where([
                        'exam_id' => $examId,
                        'student_id' => $studentId
                    ])->delete();

                    // Insert marks for each subject
                    for ($i = 0; $i < count($subjectIds); $i++) {
                        $markIndex = ($i * 2) + 3;
                        $mark = trim($row[$markIndex]);
                        
                        if ($mark !== '') {
                            $markData = [
                                'exam_id' => $examId,
                                'student_id' => $studentId,
                                'class_id' => $classId,
                                'session_id' => $sessionId,
                                'exam_subject_id' => $subjectIds[$i],
                                'marks_obtained' => $mark
                            ];

                            $this->examSubjectMarkModel->insert($markData);
                        }
                    }
                    $successCount++;
                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = "Row for student ID {$row[0]}: " . $e->getMessage();
                    continue; // Skip to next student on error
                }
            }

            fclose($handle);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Failed to save marks');
            }

            return $this->respond([
                'status' => 'success',
                'message' => "Processed successfully. Success: $successCount, Errors: $errorCount",
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to process CSV: ' . $e->getMessage()
            ], 500);
        }
    }
}
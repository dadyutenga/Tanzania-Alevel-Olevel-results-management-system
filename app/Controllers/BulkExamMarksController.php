<?php

namespace App\Controllers;

use App\Models\ExamModel;
use App\Models\ExamClassModel;
use App\Models\StudentSessionModel;
use App\Models\ExamSubjectMarkModel;
use App\Models\SessionModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class BulkExamMarksController extends ResourceController
{
    use ResponseTrait;

    protected $examModel;
    protected $examClassModel;
    protected $studentSessionModel;
    protected $examSubjectMarkModel;
    protected $sessionModel;
    protected $format = 'json';
    protected $db;

    public function __construct()
    {
        $this->examModel = new ExamModel();
        $this->examClassModel = new ExamClassModel();
        $this->studentSessionModel = new StudentSessionModel();
        $this->examSubjectMarkModel = new ExamSubjectMarkModel();
        $this->sessionModel = new SessionModel();
        $this->db = \Config\Database::connect('second_db');
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

            // Get subjects (simplified query as subject_name is in tz_exam_subjects)
            $db = \Config\Database::connect('second_db');
            // Get subjects
            $subjects = $db->table('tz_exam_subjects')
                ->where('exam_id', $examId)
                ->get()
                ->getResultArray();

            if (empty($subjects)) {
                throw new \Exception('No subjects found for this exam');
            }

            // Create CSV headers - simplified and structured
            $headers = ['Student ID', 'Student Name', 'Roll Number'];
            foreach ($subjects as $subject) {
                $headers[] = $subject['subject_name'];
                $headers[] = $subject['id']; // Hidden subject ID
            }

            // Create CSV content with structure
            $output = fopen('php://temp', 'w+');
            
            // Add a title row
            fputcsv($output, ['EXAM MARKS TEMPLATE']);
            fputcsv($output, []); // Empty line for spacing
            
            // Add max marks row
            $maxMarksRow = ['', '', ''];
            foreach ($subjects as $subject) {
                $maxMarksRow[] = "Maximum Marks: {$subject['max_marks']}";
                $maxMarksRow[] = ''; // For hidden ID column
            }
            fputcsv($output, $maxMarksRow);
            fputcsv($output, []); // Empty line for spacing
            
            // Add the main headers
            fputcsv($output, $headers);
            
            // Add a separator line
            $separator = array_fill(0, count($headers), str_repeat('-', 15));
            fputcsv($output, $separator);

            // Add student rows
            foreach ($students as $student) {
                $row = [
                    $student['id'],
                    $student['firstname'] . ' ' . $student['lastname'],
                    $student['roll_no']
                ];
                foreach ($subjects as $subject) {
                    $row[] = ''; // Empty mark field
                    $row[] = $subject['id']; // Hidden subject ID
                }
                fputcsv($output, $row);
            }

            // Add footer separator
            fputcsv($output, $separator);

            // Get CSV content and return
            rewind($output);
            $csv = stream_get_contents($output);
            fclose($output);

            $filename = "exam_marks_template_" . date('Y-m-d_His') . ".csv";
            
            return $this->response
                ->setHeader('Content-Type', 'text/csv')
                ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->setBody($csv);

        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 500);
        }
    }

    public function uploadMarks()
    {
        try {
            $examId = $this->request->getPost('exam_id');
            $classId = $this->request->getPost('class_id');
            $sessionId = $this->request->getPost('session_id'); // Changed from getVar to getPost

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
            $this->db->transStart();

            $handle = fopen($file->getTempName(), 'r');
            if ($handle === false) {
                throw new \Exception('Failed to open uploaded file');
            }

            $headers = fgetcsv($handle);
            if ($headers === false) {
                throw new \Exception('Failed to read CSV headers');
            }

            $subjectIds = [];
            // Extract subject IDs from headers (every second column starting from index 3)
            for ($i = 3; $i < count($headers); $i += 2) {
                if (isset($headers[$i + 1])) {
                    $subjectIds[] = $headers[$i + 1];
                }
            }

            if (empty($subjectIds)) {
                throw new \Exception('No subject IDs found in CSV');
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

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                throw new \Exception('Failed to save marks');
            }
            
            $this->db->transCommit();

            return $this->respond([
                'status' => 'success',
                'message' => "Processed successfully. Success: $successCount, Errors: $errorCount",
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            if (isset($this->db) && $this->db->transStatus() !== false) {
                $this->db->transRollback();
            }
            if (isset($handle)) {
                fclose($handle);
            }
            return $this->fail('Failed to process CSV: ' . $e->getMessage(), 500);
        }
    }

    public function index()
    {
        try {
            $data = [
                'title' => 'Bulk Upload Exam Marks',
                'sessions' => $this->sessionModel->where('is_active', 'no')->findAll(), // Changed 'yes' to 'no'
                'exams' => [],
                'classes' => [],
            ];

            $currentSession = $this->sessionModel->getCurrentSession();
            if ($currentSession) {
                $data['current_session'] = $currentSession;
                $data['exams'] = $this->examModel
                    ->where('session_id', $currentSession['id'])
                    ->where('is_active', 'yes')  // Changed 'no' to 'yes'
                    ->findAll();
            }

            return view('exam/BulkUploadExamMarks', $data);
        } catch (\Exception $e) {
            return $this->fail('Failed to load bulk upload page: ' . $e->getMessage(), 500);
        }
    }

    public function getExams($sessionId)
    {
        try {
            $exams = $this->examModel
                ->where([
                    'session_id' => $sessionId,
                    'is_active' => 'yes'  // Changed 'no' to 'yes'
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
            $db = \Config\Database::connect('second_db');
            $classes = $db->table('classes c')
                ->select('c.id, c.class')
                ->join('tz_exam_classes ec', 'c.id = ec.class_id')
                ->join('tz_exams e', 'e.id = ec.exam_id')
                ->where([
                    'e.session_id' => $sessionId,
                    'e.is_active' => 'yes',
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
}
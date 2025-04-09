<?php

namespace App\Controllers;

use App\Models\ExamModel;
use App\Models\ExamClassModel;
use App\Models\StudentSessionModel;
use App\Models\ExamSubjectMarkModel;
use App\Models\SessionModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

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

            // Get exam details
            $exam = $this->examModel->find($examId);
            $class = $this->db->table('classes')->where('id', $classId)->get()->getRowArray();

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
            $subjects = $this->db->table('tz_exam_subjects')
                ->where('exam_id', $examId)
                ->get()
                ->getResultArray();

            if (empty($subjects)) {
                throw new \Exception('No subjects found for this exam');
            }

            // Create new Spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set column widths
            $sheet->getColumnDimension('A')->setWidth(12);  // Student ID
            $sheet->getColumnDimension('B')->setWidth(30);  // Student Name
            $sheet->getColumnDimension('C')->setWidth(12);  // Roll No

            // Set title and headers
            $sheet->mergeCells('A1:C1');
            $sheet->setCellValue('A1', 'EXAM MARKS TEMPLATE');
            $sheet->mergeCells('A2:C2');
            $sheet->setCellValue('A2', $exam['exam_name'] . ' - ' . $class['class']);
            $sheet->mergeCells('A3:C3');
            $sheet->setCellValue('A3', 'Date: ' . date('Y-m-d'));

            // Style the header
            $headerStyle = [
                'font' => [
                    'bold' => true,
                    'size' => 14
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2EFDA']
                ]
            ];
            $sheet->getStyle('A1:C3')->applyFromArray($headerStyle);

            // Set column headers
            $sheet->setCellValue('A5', 'Student ID');
            $sheet->setCellValue('B5', 'Student Name');
            $sheet->setCellValue('C5', 'Roll No.');

            // Add subjects and their max marks
            $col = 'D';
            foreach ($subjects as $subject) {
                $sheet->getColumnDimension($col)->setWidth(15);
                $sheet->setCellValue($col . '5', $subject['subject_name']);
                $sheet->setCellValue($col . '6', 'Max: ' . $subject['max_marks']);
                $col++;
            }

            // Style the column headers
            $columnHeaderStyle = [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F2F2F2']
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN
                    ]
                ]
            ];
            $sheet->getStyle('A5:' . --$col . '6')->applyFromArray($columnHeaderStyle);

            // Add students
            $row = 7;
            foreach ($students as $student) {
                $sheet->setCellValue('A' . $row, $student['id']);
                $sheet->setCellValue('B' . $row, $student['firstname'] . ' ' . $student['lastname']);
                $sheet->setCellValue('C' . $row, $student['roll_no']);
                
                // Add empty cells for marks with validation
                $markCol = 'D';
                foreach ($subjects as $subject) {
                    // Add data validation for marks
                    $validation = $sheet->getCell($markCol . $row)->getDataValidation();
                    $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_WHOLE);
                    $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(true);
                    $validation->setFormula1(0);
                    $validation->setFormula2($subject['max_marks']);
                    $validation->setErrorTitle('Invalid Mark');
                    $validation->setError('Please enter a number between 0 and ' . $subject['max_marks']);
                    $validation->setPromptTitle('Allowed Mark');
                    $validation->setPrompt("Enter mark between 0 and {$subject['max_marks']}");
                    $validation->setShowErrorMessage(true);
                    $validation->setShowDropDown(true);
                    $markCol++;
                }
                $row++;
            }

            // Style the data rows
            $dataStyle = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN
                    ]
                ]
            ];
            $sheet->getStyle('A7:' . $col . ($row-1))->applyFromArray($dataStyle);

            // Create Excel file
            $writer = new Xlsx($spreadsheet);
            $filename = "exam_marks_template_" . date('Y-m-d_His') . ".xlsx";

            // Set headers for download
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');

            // Save to PHP output
            $writer->save('php://output');
            exit;

        } catch (\Exception $e) {
            return $this->fail($e->getMessage(), 500);
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

            $file = $this->request->getFile('excel_file');
            if (!$file->isValid()) {
                throw new \Exception('Invalid file uploaded');
            }

            if ($file->getExtension() !== 'xlsx') {
                throw new \Exception('Only Excel (.xlsx) files are allowed');
            }

            // Load Excel file
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
            $sheet = $spreadsheet->getActiveSheet();

            // Get subject mappings
            $subjects = $this->db->table('tz_exam_subjects')
                ->where('exam_id', $examId)
                ->get()
                ->getResultArray();

            $subjectMap = [];
            $colMap = [];
            $col = 'D';
            foreach ($subjects as $subject) {
                if ($sheet->getCell($col . '5')->getValue() === $subject['subject_name']) {
                    $subjectMap[$col] = $subject['id'];
                }
                $col++;
            }

            // Start transaction
            $this->db->transStart();

            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            // Process each row starting from row 7
            $row = 7;
            while ($sheet->getCell('A' . $row)->getValue() !== null) {
                try {
                    $studentId = $sheet->getCell('A' . $row)->getValue();

                    // Delete existing marks
                    $this->examSubjectMarkModel->where([
                        'exam_id' => $examId,
                        'student_id' => $studentId
                    ])->delete();

                    // Insert new marks
                    foreach ($subjectMap as $col => $subjectId) {
                        $mark = trim($sheet->getCell($col . $row)->getValue());
                        if ($mark !== '') {
                            $this->examSubjectMarkModel->insert([
                                'exam_id' => $examId,
                                'student_id' => $studentId,
                                'class_id' => $classId,
                                'session_id' => $sessionId,
                                'exam_subject_id' => $subjectId,
                                'marks_obtained' => $mark
                            ]);
                        }
                    }
                    $successCount++;
                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = "Row $row (Student ID: {$studentId}): " . $e->getMessage();
                }
                $row++;
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Failed to save marks');
            }

            return $this->respond([
                'status' => 'success',
                'message' => "Processed successfully. Success: $successCount, Errors: $errorCount",
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            if (isset($this->db) && $this->db->transStatus() !== false) {
                $this->db->transRollback();
            }
            return $this->fail('Failed to process Excel file: ' . $e->getMessage(), 500);
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
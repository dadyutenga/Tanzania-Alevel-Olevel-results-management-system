<?php

namespace App\Controllers;

use TCPDF;

class PDFController extends BaseController
{
    protected $viewResultsModel;

    public function __construct()
    {
        $this->viewResultsModel = new \App\Controllers\ViewResultsModel();
    }

    public function generateResultPDF()
    {
        $examId = $this->request->getPost('exam_id');
        $classId = $this->request->getPost('class_id');
        $sessionId = $this->request->getPost('session_id');
        $studentId = $this->request->getPost('student_id');

        if ($studentId) {
            // Generate individual student result
            $result = $this->viewResultsModel->downloadResultPDF($studentId, $examId);
            if ($result['status'] !== 'success') {
                return $this->response->setJSON($result);
            }
            return $this->generateSingleStudentPDF($result['data']);
        } else {
            // Generate class results
            $result = $this->viewResultsModel->generateResultsPDF($examId, $classId, $sessionId);
            if ($result['status'] !== 'success') {
                return $this->response->setJSON($result);
            }
            return $this->generateClassPDF($result['data']);
        }
    }

    private function generateSingleStudentPDF($studentData)
    {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('School Management System');
        $pdf->SetTitle('Exam Result');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();

        // Header
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'SCHOOL NAME', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 10, 'EXAM RESULTS', 0, 1, 'C');
        $pdf->Ln(10);

        // Student information
        $student = $studentData[0];
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(50, 10, 'Student Name:', 0);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 10, $student['full_name'], 0, 1);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(50, 10, 'Class:', 0);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 10, $student['class_name'] . ' ' . $student['section'], 0, 1);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(50, 10, 'Exam:', 0);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 10, $student['exam_name'], 0, 1);
        $pdf->Ln(10);

        // Results table
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(90, 10, 'Subject', 1);
        $pdf->Cell(90, 10, 'Grade', 1);
        $pdf->Ln();

        $pdf->SetFont('helvetica', '', 12);
        foreach ($studentData as $mark) {
            $pdf->Cell(90, 10, $mark['subject_name'], 1);
            $pdf->Cell(90, 10, $mark['grade'], 1);
            $pdf->Ln();
        }

        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(90, 10, 'Total Points:', 0);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(90, 10, $student['total_points'], 0, 1);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(90, 10, 'Division:', 0);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(90, 10, $student['division'], 0, 1);

        $pdf->Output('student_result.pdf', 'D');
    }

    private function generateClassPDF($classData)
    {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('School Management System');
        $pdf->SetTitle('Class Results');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage('L'); // Landscape for class results

        // Set margins
        $pdf->SetMargins(10, 10, 10);

        // Header
        $pdf->SetFont('helvetica', 'B', 18);
        $pdf->Cell(0, 10, 'SCHOOL NAME', 0, 1, 'C');
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'EXAM RESULTS', 0, 1, 'C');
        
        if (!empty($classData)) {
            $firstStudent = $classData[0];
            $pdf->SetFont('helvetica', 'B', 12);
            // Add date
            $pdf->Cell(0, 8, 'Date: ' . date('Y-m-d'), 0, 1, 'C');
            // Combine class and section in a better format
            $pdf->Cell(0, 8, 'Class: FORM ' . $firstStudent['class'] . ' Section ' . $firstStudent['section'], 0, 1, 'C');
        }
        $pdf->Ln(5);

        // Get all unique subjects
        $subjects = [];
        foreach ($classData as $student) {
            foreach ($student['subjects'] as $subject) {
                $subjects[$subject['subject_name']] = true;
            }
        }
        $subjects = array_keys($subjects);

        // Calculate column widths based on number of subjects
        $pageWidth = 277; // A4 landscape width in mm
        $nameWidth = 50; // Width for student name
        $gradeWidth = 15; // Width for each grade
        $finalColumnWidth = 20; // Width for points and division

        // Table header with borders and background
        $pdf->SetFillColor(220, 220, 220); // Light gray background
        $pdf->SetFont('helvetica', 'B', 9);
        
        // Student name column
        $pdf->Cell($nameWidth, 8, 'Student Name', 1, 0, 'C', true);
        
        // Subject columns
        foreach ($subjects as $subject) {
            $pdf->Cell($gradeWidth, 8, $subject, 1, 0, 'C', true);
        }
        
        // Points and Division columns
        $pdf->Cell($finalColumnWidth, 8, 'Points', 1, 0, 'C', true);
        $pdf->Cell($finalColumnWidth, 8, 'Division', 1, 1, 'C', true);

        // Table content
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetFillColor(255, 255, 255); // White background for alternating rows
        $alternate = false;

        foreach ($classData as $student) {
            // Set alternating row background
            $alternate = !$alternate;
            $fillColor = $alternate ? true : false;

            // Student name
            $pdf->Cell($nameWidth, 7, $student['student_name'], 1, 0, 'L', $fillColor);
            
            // Grades for each subject
            foreach ($subjects as $subject) {
                $grade = '-';
                foreach ($student['subjects'] as $studentSubject) {
                    if ($studentSubject['subject_name'] === $subject) {
                        $grade = $studentSubject['grade'];
                        break;
                    }
                }
                $pdf->Cell($gradeWidth, 7, $grade, 1, 0, 'C', $fillColor);
            }
            
            // Points and Division
            $pdf->Cell($finalColumnWidth, 7, $student['total_points'], 1, 0, 'C', $fillColor);
            $pdf->Cell($finalColumnWidth, 7, $student['division'], 1, 1, 'C', $fillColor);
        }

        // Add summary footer
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(0, 8, 'Grade Key:', 0, 1);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(0, 6, 'A (75-100) | B (65-74) | C (45-64) | D (30-44) | F (0-29)', 0, 1);
        
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(0, 8, 'Division Key:', 0, 1);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(0, 6, 'I (Excellent) | II (Very Good) | III (Good) | IV (Satisfactory) | F (Fail)', 0, 1);

        // Add signature lines at the bottom
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(90, 6, '_____________________', 0, 0, 'C');
        $pdf->Cell(90, 6, '_____________________', 0, 0, 'C');
        $pdf->Cell(90, 6, '_____________________', 0, 1, 'C');
        
        $pdf->Cell(90, 6, 'Class Teacher', 0, 0, 'C');
        $pdf->Cell(90, 6, 'Head Teacher', 0, 0, 'C');
        $pdf->Cell(90, 6, 'Date', 0, 1, 'C');

        $pdf->Output('class_results.pdf', 'D');
    }
} 
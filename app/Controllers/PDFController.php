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
        $studentId = $this->request->getPost('student_id');

        $result = $this->viewResultsModel->downloadResultPDF($studentId, $examId);

        if ($result['status'] !== 'success') {
            return $this->response->setJSON($result);
        }

        $studentData = $result['data'][0];

        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('School Management System');
        $pdf->SetTitle('Exam Result');

        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Add a page
        $pdf->AddPage();

        // Set font
        $pdf->SetFont('helvetica', '', 12);

        // School header
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, 'SCHOOL NAME', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 10, 'EXAM RESULTS', 0, 1, 'C');
        $pdf->Ln(10);

        // Student information
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(50, 10, 'Student Name:', 0);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 10, $studentData['full_name'], 0, 1);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(50, 10, 'Class:', 0);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 10, $studentData['class_name'] . ' ' . $studentData['section'], 0, 1);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(50, 10, 'Exam:', 0);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 10, $studentData['exam_name'], 0, 1);
        $pdf->Ln(10);

        // Results table header
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(90, 10, 'Subject', 1);
        $pdf->Cell(90, 10, 'Grade', 1);
        $pdf->Ln();

        // Results table content
        $pdf->SetFont('helvetica', '', 12);
        foreach ($result['data'] as $mark) {
            $pdf->Cell(90, 10, $mark['subject_name'], 1);
            $pdf->Cell(90, 10, $mark['grade'], 1);
            $pdf->Ln();
        }

        // Final results
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(90, 10, 'Total Points:', 0);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(90, 10, $studentData['total_points'], 0, 1);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(90, 10, 'Division:', 0);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(90, 10, $studentData['division'], 0, 1);

        // Output the PDF
        $pdf->Output('exam_result.pdf', 'D');
    }
} 
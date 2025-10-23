<?php

namespace App\Controllers;

use TCPDF;

class PDFController extends BaseController
{
    protected $viewResultsModel;
    protected $settingsModel;

    public function __construct()
    {
        $this->viewResultsModel = new \App\Controllers\ViewResultsModel();
        $this->settingsModel = new \App\Models\SettingsModel();
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
        // Get school settings
        $schoolSettings = $this->settingsModel->getCurrentSettings();
        $schoolName = $schoolSettings['school_name'] ?? 'SCHOOL NAME';
        $schoolAddress = $schoolSettings['school_address'] ?? '';
        $schoolPhone = $schoolSettings['contact_phone'] ?? '';
        $schoolEmail = $schoolSettings['contact_email'] ?? '';

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($schoolName);
        $pdf->SetTitle('Exam Result');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();

        // Header
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, strtoupper($schoolName), 0, 1, 'C');
        if ($schoolAddress) {
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(0, 6, $schoolAddress, 0, 1, 'C');
        }
        if ($schoolPhone || $schoolEmail) {
            $pdf->SetFont('helvetica', '', 9);
            $contactInfo = [];
            if ($schoolPhone) $contactInfo[] = 'Tel: ' . $schoolPhone;
            if ($schoolEmail) $contactInfo[] = 'Email: ' . $schoolEmail;
            $pdf->Cell(0, 6, implode(' | ', $contactInfo), 0, 1, 'C');
        }
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
        // Get school settings
        $schoolSettings = $this->settingsModel->getCurrentSettings();
        $schoolName = $schoolSettings['school_name'] ?? 'SCHOOL NAME';
        $schoolAddress = $schoolSettings['school_address'] ?? '';
        $schoolPhone = $schoolSettings['contact_phone'] ?? '';
        $schoolEmail = $schoolSettings['contact_email'] ?? '';

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($schoolName);
        $pdf->SetTitle('Class Results');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage('L'); // Landscape for class results

        // Set margins
        $pdf->SetMargins(10, 10, 10);

        // Header
        $pdf->SetFont('helvetica', 'B', 18);
        $pdf->Cell(0, 10, strtoupper($schoolName), 0, 1, 'C');
        if ($schoolAddress) {
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(0, 6, $schoolAddress, 0, 1, 'C');
        }
        if ($schoolPhone || $schoolEmail) {
            $pdf->SetFont('helvetica', '', 9);
            $contactInfo = [];
            if ($schoolPhone) $contactInfo[] = 'Tel: ' . $schoolPhone;
            if ($schoolEmail) $contactInfo[] = 'Email: ' . $schoolEmail;
            $pdf->Cell(0, 6, implode(' | ', $contactInfo), 0, 1, 'C');
        }
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

    public function generateStudentReportCard($studentData, $subjectMarks, $examResult)
    {
        // Get school settings
        $schoolSettings = $this->settingsModel->getCurrentSettings();
        $schoolName = $schoolSettings['school_name'] ?? 'SCHOOL NAME';
        $schoolAddress = $schoolSettings['school_address'] ?? '';
        $schoolPhone = $schoolSettings['contact_phone'] ?? '';
        $schoolEmail = $schoolSettings['contact_email'] ?? '';
        $schoolYear = $schoolSettings['school_year'] ?? date('Y');
        $schoolLogo = $schoolSettings['school_logo'] ?? '';

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($schoolName);
        $pdf->SetTitle('Student Report Card');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(15, 15, 15);
        $pdf->AddPage();

        // Header with School Logo and Title
        if ($schoolLogo && file_exists(WRITEPATH . 'uploads/' . $schoolLogo)) {
            $pdf->Image(WRITEPATH . 'uploads/' . $schoolLogo, 15, 15, 30, 30, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
        }
        $pdf->SetFont('helvetica', 'B', 18);
        $pdf->Cell(0, 10, strtoupper($schoolName), 0, 1, 'C');
        if ($schoolAddress) {
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(0, 6, $schoolAddress, 0, 1, 'C');
        }
        if ($schoolPhone || $schoolEmail) {
            $pdf->SetFont('helvetica', '', 9);
            $contactInfo = [];
            if ($schoolPhone) $contactInfo[] = 'Tel: ' . $schoolPhone;
            if ($schoolEmail) $contactInfo[] = 'Email: ' . $schoolEmail;
            $pdf->Cell(0, 6, implode(' | ', $contactInfo), 0, 1, 'C');
        }
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 8, 'STUDENT REPORT CARD', 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(0, 6, 'Academic Year: ' . $schoolYear, 0, 1, 'C');
        $pdf->Ln(5);

        // Decorative Line
        $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(74, 229, 74)));
        $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
        $pdf->Ln(10);

        // Student Photo and Information Section
        if (!empty($studentData['student_photo']) && file_exists(WRITEPATH . 'uploads/' . $studentData['student_photo'])) {
            $pdf->Image(WRITEPATH . 'uploads/' . $studentData['student_photo'], 160, 40, 30, 30, '', '', 'T', false, 300, '', false, false, 1, false, false, false);
        } else {
            // Placeholder for photo with border
            $pdf->SetXY(160, 40);
            $pdf->SetFillColor(240, 240, 240);
            $pdf->Cell(30, 30, 'Photo', 1, 1, 'C', true);
            $pdf->SetFont('helvetica', 'I', 8);
            $pdf->SetXY(160, 65);
            $pdf->Cell(30, 5, 'Not Available', 0, 1, 'C');
        }

        // Student Information
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(50, 8, 'Student Name:', 0);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 8, $studentData['full_name'], 0, 1);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(50, 8, 'Class & Section:', 0);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 8, $studentData['class_name'] . ' - Section ' . $studentData['section'], 0, 1);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(50, 8, 'Student ID:', 0);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 8, 'STU' . str_pad($studentData['student_id'], 5, '0', STR_PAD_LEFT), 0, 1);
        $pdf->Ln(10);

        // Decorative Line
        $pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
        $pdf->Ln(10);

        // Results Section Title
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'ACADEMIC PERFORMANCE', 0, 1, 'C');
        $pdf->Ln(5);

        // Results Table with Styling
        $pdf->SetFillColor(220, 220, 220); // Light gray for header
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->Cell(45, 8, 'Subject', 1, 0, 'C', true);
        $pdf->Cell(30, 8, 'Max Marks', 1, 0, 'C', true);
        $pdf->Cell(30, 8, 'Marks Obtained', 1, 0, 'C', true);
        $pdf->Cell(25, 8, 'Percentage', 1, 0, 'C', true);
        $pdf->Cell(25, 8, 'Grade', 1, 1, 'C', true);

        $pdf->SetFont('helvetica', '', 10);
        $totalMaxMarks = 0;
        $totalObtainedMarks = 0;
        foreach ($subjectMarks as $mark) {
            $percentage = ($mark['max_marks'] > 0) ? ($mark['marks_obtained'] / $mark['max_marks']) * 100 : 0;
            $totalMaxMarks += $mark['max_marks'];
            $totalObtainedMarks += $mark['marks_obtained'];
            
            // Alternate row coloring
            $pdf->SetFillColor(245, 245, 245);
            $pdf->Cell(45, 7, $mark['subject_name'], 1, 0, 'L', ($pdf->GetY() % 2 == 0));
            $pdf->Cell(30, 7, $mark['max_marks'], 1, 0, 'C', ($pdf->GetY() % 2 == 0));
            $pdf->Cell(30, 7, $mark['marks_obtained'], 1, 0, 'C', ($pdf->GetY() % 2 == 0));
            $pdf->Cell(25, 7, number_format($percentage, 1) . '%', 1, 0, 'C', ($pdf->GetY() % 2 == 0));
            
            // Colorful grade text
            $gradeColor = $this->getGradeColor($mark['grade']);
            $pdf->SetTextColor($gradeColor[0], $gradeColor[1], $gradeColor[2]);
            $pdf->Cell(25, 7, $mark['grade'], 1, 1, 'C', ($pdf->GetY() % 2 == 0));
            $pdf->SetTextColor(0, 0, 0); // Reset to black
        }

        // Total Row
        $pdf->SetFillColor(230, 230, 230);
        $pdf->SetFont('helvetica', 'B', 11);
        $totalPercentage = ($totalMaxMarks > 0) ? ($totalObtainedMarks / $totalMaxMarks) * 100 : 0;
        $pdf->Cell(45, 8, 'TOTAL', 1, 0, 'C', true);
        $pdf->Cell(30, 8, $totalMaxMarks, 1, 0, 'C', true);
        $pdf->Cell(30, 8, $totalObtainedMarks, 1, 0, 'C', true);
        $pdf->Cell(25, 8, number_format($totalPercentage, 1) . '%', 1, 0, 'C', true);
        $pdf->Cell(25, 8, '', 1, 1, 'C', true);

        $pdf->Ln(10);

        // Summary Section
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'OVERALL RESULTS', 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(60, 8, 'Total Points:', 0);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 8, $examResult['total_points'] ?? 'N/A', 0, 1);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(60, 8, 'Division:', 0);
        $pdf->SetFont('helvetica', '', 12);
        $division = $examResult['division'] ?? 'N/A';
        $divisionColor = $this->getDivisionColor($division);
        $pdf->SetTextColor($divisionColor[0], $divisionColor[1], $divisionColor[2]);
        $pdf->Cell(0, 8, $division, 0, 1);
        $pdf->SetTextColor(0, 0, 0); // Reset to black

        // Grading Key
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(0, 8, 'Grading Key:', 0, 1);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(0, 6, 'A (75-100%) - Excellent | B (65-74%) - Very Good | C (45-64%) - Good | D (30-44%) - Satisfactory | F (0-29%) - Needs Improvement', 0, 1);

        // Division Key
        $pdf->Ln(5);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(0, 8, 'Division Key:', 0, 1);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(0, 6, 'I - Excellent | II - Very Good | III - Good | IV - Satisfactory | F - Fail', 0, 1);

        // Signature Section
        $pdf->Ln(15);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(60, 6, '_____________________', 0, 0, 'C');
        $pdf->Cell(60, 6, '_____________________', 0, 0, 'C');
        $pdf->Cell(60, 6, '_____________________', 0, 1, 'C');
        
        $pdf->Cell(60, 6, 'Class Teacher', 0, 0, 'C');
        $pdf->Cell(60, 6, 'Head Teacher', 0, 0, 'C');
        $pdf->Cell(60, 6, 'Date: ' . date('Y-m-d'), 0, 1, 'C');

        // Output PDF directly to browser
        $pdf->Output('report_card_' . $studentData['student_id'] . '.pdf', 'D');
    }

    private function getGradeColor($grade)
    {
        switch ($grade) {
            case 'A':
                return [40, 167, 69]; // Green
            case 'B':
                return [108, 117, 125]; // Gray
            case 'C':
                return [0, 123, 255]; // Blue
            case 'D':
                return [255, 193, 7]; // Yellow
            case 'F':
                return [220, 53, 69]; // Red
            default:
                return [0, 0, 0]; // Black
        }
    }

    private function getDivisionColor($division)
    {
        switch ($division) {
            case 'I':
                return [40, 167, 69]; // Green
            case 'II':
                return [108, 117, 125]; // Gray
            case 'III':
                return [0, 123, 255]; // Blue
            case 'IV':
                return [255, 193, 7]; // Yellow
            case 'F':
                return [220, 53, 69]; // Red
            default:
                return [0, 0, 0]; // Black
        }
    }

    public function generateAlevelClassPDF($classData)
    {
        // Get school settings
        $schoolSettings = $this->settingsModel->getCurrentSettings();
        $schoolName = $schoolSettings['school_name'] ?? 'SCHOOL NAME';
        $schoolAddress = $schoolSettings['school_address'] ?? '';
        $schoolPhone = $schoolSettings['contact_phone'] ?? '';
        $schoolEmail = $schoolSettings['contact_email'] ?? '';

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($schoolName);
        $pdf->SetTitle('A-Level Class Results');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage('L'); // Landscape for class results

        // Set margins
        $pdf->SetMargins(10, 10, 10);

        // Header
        $pdf->SetFont('helvetica', 'B', 18);
        $pdf->Cell(0, 10, strtoupper($schoolName), 0, 1, 'C');
        if ($schoolAddress) {
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(0, 6, $schoolAddress, 0, 1, 'C');
        }
        if ($schoolPhone || $schoolEmail) {
            $pdf->SetFont('helvetica', '', 9);
            $contactInfo = [];
            if ($schoolPhone) $contactInfo[] = 'Tel: ' . $schoolPhone;
            if ($schoolEmail) $contactInfo[] = 'Email: ' . $schoolEmail;
            $pdf->Cell(0, 6, implode(' | ', $contactInfo), 0, 1, 'C');
        }
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'A-LEVEL EXAM RESULTS', 0, 1, 'C');
        
        if (!empty($classData)) {
            $firstStudent = $classData[0];
            $pdf->SetFont('helvetica', 'B', 12);
            // Add date
            $pdf->Cell(0, 8, 'Date: ' . date('Y-m-d'), 0, 1, 'C');
            // Class information
            $pdf->Cell(0, 8, 'Class: ' . $firstStudent['class'], 0, 1, 'C');
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
        $pdf->Cell(0, 8, 'Grade Key (ACSEE Scale):', 0, 1);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(0, 6, 'A (80-100) | B (70-79) | C (60-69) | D (50-59) | E (40-49) | S (30-39) | F (0-29)', 0, 1);
        
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

        $pdf->Output('alevel_class_results.pdf', 'D');
    }

    public function generateAlevelSingleStudentPDF($studentData)
    {
        // Get school settings
        $schoolSettings = $this->settingsModel->getCurrentSettings();
        $schoolName = $schoolSettings['school_name'] ?? 'SCHOOL NAME';
        $schoolAddress = $schoolSettings['school_address'] ?? '';
        $schoolPhone = $schoolSettings['contact_phone'] ?? '';
        $schoolEmail = $schoolSettings['contact_email'] ?? '';

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($schoolName);
        $pdf->SetTitle('A-Level Exam Result');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->AddPage();

        // Header
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->Cell(0, 10, strtoupper($schoolName), 0, 1, 'C');
        if ($schoolAddress) {
            $pdf->SetFont('helvetica', '', 10);
            $pdf->Cell(0, 6, $schoolAddress, 0, 1, 'C');
        }
        if ($schoolPhone || $schoolEmail) {
            $pdf->SetFont('helvetica', '', 9);
            $contactInfo = [];
            if ($schoolPhone) $contactInfo[] = 'Tel: ' . $schoolPhone;
            if ($schoolEmail) $contactInfo[] = 'Email: ' . $schoolEmail;
            $pdf->Cell(0, 6, implode(' | ', $contactInfo), 0, 1, 'C');
        }
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 10, 'A-LEVEL EXAM RESULTS', 0, 1, 'C');
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
        $pdf->Cell(0, 10, $student['class_name'], 0, 1);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(50, 10, 'Combination:', 0);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 10, $student['combination_code'], 0, 1);

        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(50, 10, 'Exam:', 0);
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 10, $student['exam_name'], 0, 1);
        $pdf->Ln(10);

        // Results table
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->Cell(60, 10, 'Subject', 1);
        $pdf->Cell(40, 10, 'Type', 1);
        $pdf->Cell(40, 10, 'Marks Obtained', 1);
        $pdf->Cell(40, 10, 'Grade', 1);
        $pdf->Ln();

        $pdf->SetFont('helvetica', '', 12);
        foreach ($studentData as $mark) {
            $pdf->Cell(60, 10, $mark['subject_name'], 1);
            $pdf->Cell(40, 10, $mark['subject_type'], 1);
            $pdf->Cell(40, 10, $mark['marks_obtained'], 1);
            $pdf->Cell(40, 10, $mark['grade'], 1);
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

        // Add grading key
        $pdf->Ln(10);
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(0, 8, 'Grade Key (ACSEE Scale):', 0, 1);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell(0, 6, 'A (80-100) | B (70-79) | C (60-69) | D (50-59) | E (40-49) | S (30-39) | F (0-29)', 0, 1);

        $pdf->Output('alevel_student_result.pdf', 'D');
    }
} 
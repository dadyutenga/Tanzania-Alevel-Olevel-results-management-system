<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ClassModel;
use App\Models\ExamModel;
use App\Models\StudentModel;

class DashboardController extends Controller
{
    protected $classModel;
    protected $examModel;
    protected $studentModel;

    public function __construct()
    {
        $this->classModel = new ClassModel();
        $this->examModel = new ExamModel();
        $this->studentModel = new StudentModel();
    }

    public function index()
    {
        try {
            // Get total students
            $totalStudents = $this->studentModel->countAll();
            
            // Get active exams count
            $activeExams = $this->examModel->where('is_active', 'yes')->countAllResults();
            
            // Get completed exams count (this month)
            $startOfMonth = date('Y-m-01');
            $endOfMonth = date('Y-m-t');
            $completedExams = $this->examModel
                ->where('is_active', 'no')
                ->where('exam_date >=', $startOfMonth)
                ->where('exam_date <=', $endOfMonth)
                ->countAllResults();

            // Calculate percentage increase for students
            $lastMonthStart = date('Y-m-01', strtotime('-1 month'));
            $lastMonthStudents = $this->studentModel
                ->where('created_at <', $startOfMonth)
                ->countAllResults();
            
            $newStudents = $this->studentModel
                ->where('created_at >=', $startOfMonth)
                ->where('created_at <=', $endOfMonth)
                ->countAllResults();
            
            $studentGrowth = $lastMonthStudents > 0 
                ? round(($newStudents / $lastMonthStudents) * 100, 1)
                : 0;

            return view('dashboard', [
                'totalStudents' => $totalStudents,
                'studentGrowth' => $studentGrowth,
                'activeExams' => $activeExams,
                'completedExams' => $completedExams,
                'newExamsThisWeek' => $this->getNewExamsThisWeek()
            ]);
        } catch (\Exception $e) {
            log_message('error', '[Dashboard] Error: ' . $e->getMessage());
            return view('dashboard', [
                'totalStudents' => 0,
                'studentGrowth' => 0,
                'activeExams' => 0,
                'completedExams' => 0,
                'newExamsThisWeek' => 0
            ]);
        }
    }

    private function getNewExamsThisWeek()
    {
        $startOfWeek = date('Y-m-d', strtotime('monday this week'));
        return $this->examModel
            ->where('created_at >=', $startOfWeek)
            ->countAllResults();
    }
}
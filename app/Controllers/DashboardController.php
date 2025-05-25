<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\ClassModel;
use App\Models\ExamModel;
use App\Models\StudentModel;
use App\Models\ExamResultModel;
use App\Models\ExamClassModel;
use App\Models\SessionModel;

class DashboardController extends Controller
{
    protected $classModel;
    protected $examModel;
    protected $studentModel;
    protected $examResultModel;
    protected $examClassModel;
    protected $sessionModel;

    public function __construct()
    {
        $this->classModel = new ClassModel();
        $this->examModel = new ExamModel();
        $this->studentModel = new StudentModel();
        $this->examResultModel = new ExamResultModel();
        $this->examClassModel = new ExamClassModel();
        $this->sessionModel = new SessionModel();
    }

    public function index()
    {
        try {
            // Basic Stats
            $totalStudents = $this->studentModel->countAll();
            $activeExams = $this->examModel->where('is_active', 'yes')->countAllResults();
            
            // Get current session
            $currentSession = $this->sessionModel->getCurrentSession();
            
            // Exam Completion Stats
            $examStats = $this->getExamStats();
            
            // Performance Distribution
            $performanceData = $this->getPerformanceDistribution();
            
            // Class-wise Student Distribution
            $classDistribution = $this->getClassDistribution();
            
            // Recent Results
            $recentResults = $this->getRecentResults();
            
            // Monthly Progress
            $monthlyProgress = $this->getMonthlyProgress();
            
            // Division Distribution
            $divisionStats = $this->getDivisionStats();

            return view('dashboard', [
                'totalStudents' => $totalStudents,
                'activeExams' => $activeExams,
                'examStats' => $examStats,
                'performanceData' => $performanceData,
                'classDistribution' => $classDistribution,
                'recentResults' => $recentResults,
                'monthlyProgress' => $monthlyProgress,
                'divisionStats' => $divisionStats,
                'currentSession' => $currentSession
            ]);
        } catch (\Exception $e) {
            log_message('error', '[Dashboard] Error: ' . $e->getMessage());
            return view('dashboard', [
                'error' => 'Unable to load dashboard data',
                'totalStudents' => 0,
                'activeExams' => 0,
                'examStats' => ['total' => 0, 'completed' => 0, 'ongoing' => 0, 'completion_rate' => 0],
                'performanceData' => [],
                'classDistribution' => [],
                'recentResults' => [],
                'monthlyProgress' => [],
                'divisionStats' => [],
                'currentSession' => null
            ]);
        }
    }

    private function getExamStats()
    {
        $total = $this->examModel->countAll();
        $completed = $this->examModel->where('is_active', 'yes')->countAllResults();
        $ongoing = $this->examModel->where('is_active', 'yes')->countAllResults();
        
        return [
            'total' => $total,
            'completed' => $completed,
            'ongoing' => $ongoing,
            'completion_rate' => $total > 0 ? round(($completed / $total) * 100, 1) : 0
        ];
    }

    private function getPerformanceDistribution()
    {
        return $this->examResultModel
            ->select('division, COUNT(*) as count')
            ->where('division IS NOT NULL')
            ->groupBy('division')
            ->findAll();
    }

    private function getClassDistribution()
    {
        return $this->examClassModel
            ->select('classes.class, COUNT(DISTINCT tz_exam_classes.exam_id) as exam_count')
            ->join('classes', 'classes.id = tz_exam_classes.class_id')
            ->groupBy('tz_exam_classes.class_id')
            ->findAll();
    }

    private function getRecentResults()
    {
        return $this->examResultModel
            ->select('
                tz_exam_results.*, 
                tz_exams.exam_name,
                classes.class as class_name
            ')
            ->join('tz_exams', 'tz_exams.id = tz_exam_results.exam_id')
            ->join('classes', 'classes.id = tz_exam_results.class_id')
            ->orderBy('tz_exam_results.created_at', 'DESC')
            ->limit(5)
            ->findAll();
    }

    private function getMonthlyProgress()
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-$i months"));
            $months[$month] = [
                'exams' => 0,
                'avg_points' => 0
            ];
        }

        $results = $this->examResultModel
            ->select('
                DATE_FORMAT(created_at, "%Y-%m") as month,
                COUNT(*) as exam_count,
                AVG(total_points) as average_points
            ')
            ->where('created_at >=', date('Y-m-d', strtotime('-6 months')))
            ->groupBy('month')
            ->findAll();

        foreach ($results as $result) {
            if (isset($months[$result['month']])) {
                $months[$result['month']] = [
                    'exams' => (int)$result['exam_count'],
                    'avg_points' => round($result['average_points'], 1)
                ];
            }
        }

        return $months;
    }

    private function getDivisionStats()
    {
        return $this->examResultModel
            ->select('
                division,
                division_description,
                COUNT(*) as count,
                AVG(total_points) as avg_points
            ')
            ->where('division IS NOT NULL')
            ->groupBy('division')
            ->orderBy('avg_points', 'DESC')
            ->findAll();
    }
}
<?php

namespace App\Controllers;

use App\Models\StudentModel;
use App\Models\ClassModel;
use App\Models\SectionModel;
use App\Models\SessionModel;
use App\Models\ClassSectionModel;
use CodeIgniter\RESTful\ResourceController;

class StudentController extends ResourceController
{
    protected $modelName = 'App\Models\StudentModel';
    protected $format    = 'json';

    public function index()
    {
        return view('Student/index');
    }

    public function getClasses()
    {
        try {
            $classModel = new ClassModel();
            $classSectionModel = new ClassSectionModel();
            
            // Get active classes that have sections
            $classes = $classModel->select('classes.*')
                                ->join('class_sections', 'classes.id = class_sections.class_id')
                                ->where('classes.is_active', 'no')
                                ->where('class_sections.is_active', 'no')
                                ->groupBy('classes.id')
                                ->findAll();
            
            return $this->respond([
                'status' => 'success',
                'data' => $classes
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error in getClasses: ' . $e->getMessage());
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch classes'
            ], 500);
        }
    }

    public function getSections($classId = null)
    {
        try {
            if (!$classId) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Class ID is required'
                ], 400);
            }

            $classSectionModel = new ClassSectionModel();
            $sectionModel = new SectionModel();
            
            // Get sections linked to the class through class_sections
            $sections = $sectionModel->select('sections.*')
                                   ->join('class_sections', 'sections.id = class_sections.section_id')
                                   ->where('class_sections.class_id', $classId)
                                   ->where('sections.is_active', 'no')
                                   ->where('class_sections.is_active', 'no')
                                   ->findAll();
            
            return $this->respond([
                'status' => 'success',
                'data' => $sections
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch sections'
            ], 500);
        }
    }

    public function fetchStudents()
    {
        try {
            $page = $this->request->getGet('page') ?? 1;
            $limit = $this->request->getGet('limit') ?? 10;
            $search = $this->request->getGet('search') ?? '';
            $class = $this->request->getGet('class') ?? '';
            $section = $this->request->getGet('section') ?? '';

            // Get current session
            $sessionModel = new SessionModel();
            $currentSession = $sessionModel->getCurrentSession();
            
            if (!$currentSession) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'No active session found'
                ], 400);
            }

            $studentModel = new StudentModel();
            $builder = $studentModel->builder();
            
            // Base query with all necessary joins
            $builder->select('students.*, classes.class as class_name, sections.section as section_name')
                    ->join('student_session', 'students.id = student_session.student_id')
                    ->join('class_sections', 'student_session.class_section_id = class_sections.id')
                    ->join('classes', 'class_sections.class_id = classes.id')
                    ->join('sections', 'class_sections.section_id = sections.id')
                    ->where('students.is_active', 'no')
                    ->where('student_session.session_id', $currentSession['id']);

            // Apply filters
            if ($class) {
                $builder->where('class_sections.class_id', $class);
            }

            if ($section) {
                $builder->where('class_sections.section_id', $section);
            }

            if ($search) {
                $builder->groupStart()
                        ->like('students.firstname', $search)
                        ->orLike('students.lastname', $search)
                        ->orLike('students.admission_no', $search)
                        ->groupEnd();
            }

            // Get total records before limit
            $totalRecords = $builder->countAllResults(false);

            // Get paginated results
            $students = $builder->limit($limit, ($page - 1) * $limit)
                              ->get()
                              ->getResultArray();

            return $this->respond([
                'status' => 'success',
                'data' => [
                    'students' => $students,
                    'current_session' => $currentSession,
                    'pagination' => [
                        'current_page' => (int)$page,
                        'total_pages' => ceil($totalRecords / $limit),
                        'total_records' => $totalRecords,
                        'per_page' => $limit
                    ],
                ]
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error fetching students: ' . $e->getMessage());
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch students'
            ], 500);
        }
    }

    public function getStudent($id)
    {
        try {
            $studentModel = new StudentModel();
            $sessionModel = new SessionModel();
            $currentSession = $sessionModel->getCurrentSession();
            
            $builder = $studentModel->builder();
            $builder->select('students.*, classes.class as class_name, sections.section as section_name')
                    ->join('student_session', 'students.id = student_session.student_id')
                    ->join('class_sections', 'student_session.class_section_id = class_sections.id')
                    ->join('classes', 'class_sections.class_id = classes.id')
                    ->join('sections', 'class_sections.section_id = sections.id')
                    ->where('students.id', $id)
                    ->where('student_session.session_id', $currentSession['id']);

            $student = $builder->get()->getRowArray();

            if (!$student) {
                return $this->failNotFound('Student not found');
            }

            return $this->respond([
                'status' => 'success',
                'data' => $student,
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error getting student: ' . $e->getMessage());
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to get student details'
            ], 500);
        }
    }
}
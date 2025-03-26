<?php

namespace App\Controllers;

use App\Models\StudentModel;
use App\Models\ClassModel;
use App\Models\SectionModel;
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
            $classes = $classModel->where('is_active', 'no')->findAll();
            
            log_message('debug', 'Classes fetched: ' . json_encode($classes)); // Debug log
            
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
            $sectionModel = new SectionModel();
            $sections = $sectionModel->where('is_active', 'no')
                                   ->where('class_id', $classId)
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

            $studentModel = new StudentModel();
            $builder = $studentModel->builder();
            
            // Base query with joins
            $builder->select('students.*, classes.class as class_name, sections.section as section_name')
                    ->join('student_session', 'students.id = student_session.student_id', 'left')
                    ->join('classes', 'student_session.class_id = classes.id', 'left')
                    ->join('sections', 'student_session.section_id = sections.id', 'left')
                    ->where('students.is_active', 'yes');

            // Apply filters
            if ($class) {
                $builder->where('classes.id', $class);
            }

            if ($section) {
                $builder->where('sections.id', $section);
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
                    'pagination' => [
                        'current_page' => (int)$page,
                        'total_pages' => ceil($totalRecords / $limit),
                        'total_records' => $totalRecords,
                        'per_page' => $limit
                    ],
                ]
            ]);
        } catch (\Exception $e) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch students'
            ], 500);
        }
    }

    public function getStudent($id)
    {
        $studentModel = new StudentModel();
        
        $builder = $studentModel->builder();
        $builder->select('students.*, classes.class as class_name, sections.section as section_name')
                ->join('student_session', 'students.id = student_session.student_id', 'left')
                ->join('classes', 'student_session.class_id = classes.id', 'left')
                ->join('sections', 'student_session.section_id = sections.id', 'left')
                ->where('students.id', $id);

        $student = $builder->get()->getRowArray();

        if (!$student) {
            return $this->failNotFound('Student not found');
        }

        return $this->respond([
            'status' => 'success',
            'data' => $student,
        ]);
    }
}
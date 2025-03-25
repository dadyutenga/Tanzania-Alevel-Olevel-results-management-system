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
        $classModel = new ClassModel();
        $sectionModel = new SectionModel();

        $data = [
            'classes' => $classModel->where('is_active', 'yes')->findAll(),
            'sections' => $sectionModel->where('is_active', 'yes')->findAll()
        ];

        return view('Student/index', $data);
    }

    public function fetchStudents()
    {
        $page = $this->request->getGet('page') ?? 1;
        $limit = $this->request->getGet('limit') ?? 10;
        $search = $this->request->getGet('search') ?? '';
        $class = $this->request->getGet('class') ?? '';
        $section = $this->request->getGet('section') ?? '';

        $studentModel = new StudentModel();

        $builder = $studentModel->builder();
        $builder->select('students.*, classes.class as class_name, sections.section as section_name')
                ->join('student_session', 'students.id = student_session.student_id', 'left')
                ->join('classes', 'student_session.class_id = classes.id', 'left')
                ->join('sections', 'student_session.section_id = sections.id', 'left')
                ->where('students.is_active', 'yes');

        if ($search) {
            $builder->groupStart()
                    ->like('students.firstname', $search)
                    ->orLike('students.lastname', $search)
                    ->orLike('students.admission_no', $search)
                    ->groupEnd();
        }

        if ($class) {
            $builder->where('classes.id', $class);
        }

        if ($section) {
            $builder->where('sections.id', $section);
        }

        $totalRecords = $builder->countAllResults(false);
        $students = $builder->limit($limit, ($page - 1) * $limit)->get()->getResultArray();

        $totalPages = ceil($totalRecords / $limit);

        return $this->respond([
            'status' => 'success',
            'data' => [
                'students' => $students,
                'pagination' => [
                    'current_page' => (int)$page,
                    'total_pages' => $totalPages,
                    'total_records' => $totalRecords,
                ],
            ],
        ]);
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

<?php

namespace App\Controllers;

use App\Models\StudentModel;
use App\Models\ClassModel;
use App\Models\SectionModel;
use App\Models\SessionModel;
use App\Models\ClassSectionModel;
use App\Models\StudentSessionModel;
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
            log_message('info', 'Fetching active classes with sections');
            
            $classModel = new ClassModel();
            
            // Get active classes that have sections (is_active = 'no' means active)
            $classes = $classModel->select('classes.*')
                                ->join('class_sections', 'classes.id = class_sections.class_id')
                                ->where('classes.is_active', 'no')
                                ->where('class_sections.is_active', 'no')
                                ->groupBy('classes.id')
                                ->findAll();
            
            log_message('debug', 'Classes query: ' . $classModel->getLastQuery());
            log_message('info', 'Successfully fetched {count} classes', ['count' => count($classes)]);
            
            return $this->respond([
                'status' => 'success',
                'data' => $classes
            ]);
        } catch (\Exception $e) {
            log_message('error', '[getClasses] Exception: {message}', ['message' => $e->getMessage()]);
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch classes'
            ], 500);
        }
    }

    public function getSections($classId = null)
    {
        try {
            log_message('info', 'Fetching sections for class ID: {classId}', ['classId' => $classId]);
            
            if (!$classId) {
                log_message('warning', 'getSections called without class ID');
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Class ID is required'
                ], 400);
            }

            $sectionModel = new SectionModel();
            
            // Get sections through class_sections (is_active = 'no' means active)
            $sections = $sectionModel->select('sections.*, class_sections.id as class_section_id')
                                   ->join('class_sections', 'sections.id = class_sections.section_id')
                                   ->where('class_sections.class_id', $classId)
                                   ->where('sections.is_active', 'no')
                                   ->where('class_sections.is_active', 'no')
                                   ->findAll();
            
            log_message('debug', 'Sections query: ' . $sectionModel->getLastQuery());
            log_message('info', 'Successfully fetched {count} sections for class {classId}', [
                'count' => count($sections),
                'classId' => $classId
            ]);
            
            return $this->respond([
                'status' => 'success',
                'data' => $sections
            ]);
        } catch (\Exception $e) {
            log_message('error', '[getSections] Exception for class {classId}: {message}', [
                'classId' => $classId,
                'message' => $e->getMessage()
            ]);
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch sections'
            ], 500);
        }
    }

    public function getSessions()
    {
        try {
            log_message('info', 'Fetching available sessions');
            
            $sessionModel = new SessionModel();
            
            // Get all sessions with debug logging
            $sessions = $sessionModel->getAllSessions();
            
            log_message('debug', 'Sessions query: ' . $sessionModel->getLastQuery());
            log_message('debug', 'Sessions result: ' . json_encode($sessions));
            
            if (empty($sessions)) {
                log_message('warning', 'No sessions found in database');
            }
            
            return $this->respond([
                'status' => 'success',
                'data' => $sessions
            ]);
        } catch (\Exception $e) {
            log_message('error', '[getSessions] Exception: {message} | Stack: {stack}', [
                'message' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch sessions: ' . $e->getMessage()
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
            $session = $this->request->getGet('session') ?? '';

            log_message('debug', 'Request params: ' . json_encode([
                'session' => $session,
                'class' => $class,
                'section' => $section
            ]));

            // First verify if we have any students in this session-class-section combination
            $studentSessionModel = new StudentSessionModel();
            $validStudentCount = $studentSessionModel->where([
                'session_id' => $session,
                'class_id' => $class,
                'section_id' => $section,
                'is_active' => 'no'
            ])->countAllResults();

            if ($validStudentCount === 0) {
                log_message('info', 'No students found for session={session}, class={class}, section={section}', [
                    'session' => $session,
                    'class' => $class,
                    'section' => $section
                ]);
                return $this->respond([
                    'status' => 'success',
                    'data' => [
                        'students' => [],
                        'pagination' => [
                            'current_page' => (int)$page,
                            'total_pages' => 0,
                            'total_records' => 0,
                            'per_page' => (int)$limit
                        ]
                    ]
                ]);
            }

            $studentModel = new StudentModel();
            $builder = $studentModel->builder();

            // Build the query with strict session validation
            $builder->select('
                students.*, 
                classes.class as class_name, 
                sections.section as section_name,
                student_session.session_id,
                student_session.class_id,
                student_session.section_id
            ')
            ->join('student_session', 'students.id = student_session.student_id')
            ->join('classes', 'student_session.class_id = classes.id')
            ->join('sections', 'student_session.section_id = sections.id')
            ->where([
                'students.is_active' => 'yes',
                'student_session.is_active' => 'no',
                'student_session.session_id' => $session,
                'student_session.class_id' => $class,
                'student_session.section_id' => $section
            ]);

            // Log the query for debugging
            log_message('debug', 'SQL Query: ' . $builder->getCompiledSelect());

            if ($search) {
                $builder->groupStart()
                        ->like('students.firstname', $search)
                        ->orLike('students.lastname', $search)
                        ->orLike('students.admission_no', $search)
                        ->groupEnd();
            }

            // Get total count
            $countBuilder = clone $builder;
            $totalRecords = $countBuilder->countAllResults(false);

            // Get paginated results
            $students = $builder->limit($limit, ($page - 1) * $limit)
                              ->get()
                              ->getResultArray();

            // Double check the results match our criteria exactly
            $students = array_filter($students, function($student) use ($session, $class, $section) {
                return (
                    $student['session_id'] === $session &&
                    $student['class_id'] === $class &&
                    $student['section_id'] === $section
                );
            });

            // Reset array keys after filtering
            $students = array_values($students);

            log_message('debug', 'Final results count: ' . count($students));

            return $this->respond([
                'status' => 'success',
                'data' => [
                    'students' => $students,
                    'pagination' => [
                        'current_page' => (int)$page,
                        'total_pages' => $totalRecords > 0 ? ceil($totalRecords / $limit) : 0,
                        'total_records' => count($students),
                        'per_page' => (int)$limit
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', '[fetchStudents] Exception: {message} | Stack: {stack}', [
                'message' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch students'
            ], 500);
        }
    }

    public function getStudent($id)
    {
        try {
            log_message('info', 'Fetching details for student ID: {id}', ['id' => $id]);
            
            $studentModel = new StudentModel();
            $sessionModel = new SessionModel();
            $currentSession = $sessionModel->getCurrentSession();
            
            $builder = $studentModel->builder();
            // Modified join logic to use separate class_id and section_id
            $builder->select('students.*, classes.class as class_name, sections.section as section_name')
                    ->join('student_session', 'students.id = student_session.student_id')
                    ->join('classes', 'student_session.class_id = classes.id')
                    ->join('sections', 'student_session.section_id = sections.id')
                    ->where('students.id', $id)
                    ->where('student_session.session_id', $currentSession['id']);

            $student = $builder->get()->getRowArray();

            if (!$student) {
                log_message('warning', 'Student not found with ID: {id}', ['id' => $id]);
                return $this->failNotFound('Student not found');
            }

            log_message('info', 'Successfully fetched details for student ID: {id}', ['id' => $id]);
            
            return $this->respond([
                'status' => 'success',
                'data' => $student,
            ]);
        } catch (\Exception $e) {
            log_message('error', '[getStudent] Exception for ID {id}: {message} | Stack: {stack}', [
                'id' => $id,
                'message' => $e->getMessage(),
                'stack' => $e->getTraceAsString()
            ]);
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to get student details'
            ], 500);
        }
    }
}
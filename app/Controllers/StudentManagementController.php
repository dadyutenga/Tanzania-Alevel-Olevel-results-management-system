<?php

namespace App\Controllers;

use App\Models\StudentModel;
use App\Models\StudentSessionModel;
use App\Models\ClassModel;
use App\Models\SectionModel;
use App\Models\SessionModel;
use App\Models\SettingsModel;
use CodeIgniter\RESTful\ResourceController;

class StudentManagementController extends ResourceController
{
    protected $format = 'json';
    protected $studentModel;
    protected $studentSessionModel;
    protected $classModel;
    protected $sectionModel;
    protected $sessionModel;
    protected $settingsModel;

    public function __construct()
    {
        $this->studentModel = new StudentModel();
        $this->studentSessionModel = new StudentSessionModel();
        $this->classModel = new ClassModel();
        $this->sectionModel = new SectionModel();
        $this->sessionModel = new SessionModel();
        $this->settingsModel = new SettingsModel();
    }

    public function index()
    {
        try {
            $data = [
                'title' => 'Manage Students'
            ];
            
            return view('students/index', $data);
        } catch (\Exception $e) {
            log_message('error', '[StudentManagement.index] Exception: {message}', ['message' => $e->getMessage()]);
            return redirect()->to('dashboard')->with('error', 'Failed to load students page');
        }
    }

    public function create()
    {
        $session = service('session');
        $userId = $session->get('user_uuid') ?? $session->get('user_id');
        
        if (!$userId) {
            return redirect()->to('/login');
        }

        $settings = $this->settingsModel->getSchoolByUserId($userId);
        $currentSession = null;
        
        if ($settings && !empty($settings['school_year'])) {
            $currentSession = $this->sessionModel->where('session', $settings['school_year'])->first();
        }

        $classes = $this->classModel->where('is_active', 'yes')->orderBy('class', 'ASC')->findAll();
        $sections = $this->sectionModel->where('is_active', 'yes')->orderBy('section', 'ASC')->findAll();
        $sessions = $this->sessionModel->orderBy('session', 'DESC')->findAll();

        $data = [
            'title' => 'Add Student',
            'student' => null,
            'classes' => $classes,
            'sections' => $sections,
            'sessions' => $sessions,
            'currentSession' => $currentSession
        ];

        return view('students/manage', $data);
    }

    public function edit($id = null)
    {
        $session = service('session');
        $userId = $session->get('user_uuid') ?? $session->get('user_id');
        
        if (!$userId) {
            return redirect()->to('/login');
        }

        $student = $this->studentModel->find($id);
        
        if (!$student) {
            return redirect()->to('students')->with('error', 'Student not found');
        }

        $studentSession = $this->studentSessionModel->where('student_id', $id)->first();

        $settings = $this->settingsModel->getSchoolByUserId($userId);
        $currentSession = null;
        
        if ($settings && !empty($settings['school_year'])) {
            $currentSession = $this->sessionModel->where('session', $settings['school_year'])->first();
        }

        $classes = $this->classModel->where('is_active', 'yes')->orderBy('class', 'ASC')->findAll();
        $sections = $this->sectionModel->where('is_active', 'yes')->orderBy('section', 'ASC')->findAll();
        $sessions = $this->sessionModel->orderBy('session', 'DESC')->findAll();

        $data = [
            'title' => 'Edit Student',
            'student' => $student,
            'studentSession' => $studentSession,
            'classes' => $classes,
            'sections' => $sections,
            'sessions' => $sessions,
            'currentSession' => $currentSession
        ];

        return view('students/manage', $data);
    }

    public function bulkRegister()
    {
        $session = service('session');
        $userId = $session->get('user_uuid') ?? $session->get('user_id');
        
        if (!$userId) {
            return redirect()->to('/login');
        }

        $settings = $this->settingsModel->getSchoolByUserId($userId);
        $currentSession = null;
        
        if ($settings && !empty($settings['school_year'])) {
            $currentSession = $this->sessionModel->where('session', $settings['school_year'])->first();
        }

        $classes = $this->classModel->where('is_active', 'yes')->orderBy('class', 'ASC')->findAll();
        $sections = $this->sectionModel->where('is_active', 'yes')->orderBy('section', 'ASC')->findAll();
        $sessions = $this->sessionModel->orderBy('session', 'DESC')->findAll();

        $data = [
            'title' => 'Bulk Student Registration',
            'classes' => $classes,
            'sections' => $sections,
            'sessions' => $sessions,
            'currentSession' => $currentSession
        ];

        return view('students/bulk_register', $data);
    }

    public function getStudents()
    {
        try {
            $builder = $this->studentModel->builder();
            $builder->select('students.*, student_session.class_id, student_session.section_id, student_session.session_id, classes.class, sections.section, sessions.session')
                    ->join('student_session', 'student_session.student_id = students.id', 'left')
                    ->join('classes', 'classes.id = student_session.class_id', 'left')
                    ->join('sections', 'sections.id = student_session.section_id', 'left')
                    ->join('sessions', 'sessions.id = student_session.session_id', 'left')
                    ->orderBy('students.firstname', 'ASC');
            
            $students = $builder->get()->getResultArray();
            
            return $this->respond([
                'status' => 'success',
                'data' => $students
            ]);
        } catch (\Exception $e) {
            log_message('error', '[StudentManagement.getStudents] Exception: {message}', ['message' => $e->getMessage()]);
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch students'
            ], 500);
        }
    }

    public function store()
    {
        try {
            $data = $this->request->getPost();
            
            $rules = [
                'firstname' => 'required|min_length[2]|max_length[100]',
                'lastname' => 'required|min_length[2]|max_length[100]',
                'middlename' => 'permit_empty|max_length[100]',
                'dob' => 'required|valid_date',
                'gender' => 'required|in_list[male,female,other]',
                'guardian_phone' => 'required|min_length[10]|max_length[15]',
                'class_id' => 'required',
                'section_id' => 'required',
                'session_id' => 'required',
                'is_active' => 'permit_empty|in_list[yes,no]'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $studentData = [
                'firstname' => $data['firstname'],
                'middlename' => $data['middlename'] ?? '',
                'lastname' => $data['lastname'],
                'dob' => $data['dob'],
                'gender' => $data['gender'],
                'guardian_phone' => $data['guardian_phone'],
                'is_active' => $data['is_active'] ?? 'yes',
                'image' => ''
            ];

            $studentId = $this->studentModel->insert($studentData);

            if (!$studentId) {
                return redirect()->back()->withInput()->with('error', 'Failed to create student');
            }

            $sessionData = [
                'student_id' => $studentId,
                'class_id' => $data['class_id'],
                'section_id' => $data['section_id'],
                'session_id' => $data['session_id'],
                'is_active' => 'yes'
            ];

            if (!$this->studentSessionModel->insert($sessionData)) {
                $this->studentModel->delete($studentId);
                return redirect()->back()->withInput()->with('error', 'Failed to create student session');
            }

            return redirect()->to('students')->with('success', 'Student added successfully!');
        } catch (\Exception $e) {
            log_message('error', '[StudentManagement.store] Exception: {message}', ['message' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Failed to create student: ' . $e->getMessage());
        }
    }

    public function storeBulk()
    {
        try {
            $data = $this->request->getPost();
            
            $rules = [
                'class_id' => 'required',
                'section_id' => 'required',
                'session_id' => 'required',
                'students' => 'required'
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ]);
            }

            $students = json_decode($data['students'], true);
            
            if (empty($students)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'No students data provided'
                ]);
            }

            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            foreach ($students as $index => $student) {
                try {
                    $studentData = [
                        'firstname' => $student['firstname'],
                        'middlename' => $student['middlename'] ?? '',
                        'lastname' => $student['lastname'],
                        'dob' => $student['dob'],
                        'gender' => $student['gender'],
                        'guardian_phone' => $student['guardian_phone'],
                        'is_active' => 'yes',
                        'image' => ''
                    ];

                    $studentId = $this->studentModel->insert($studentData);

                    if (!$studentId) {
                        $errorCount++;
                        $errors[] = "Row " . ($index + 1) . ": Failed to create student";
                        continue;
                    }

                    $sessionData = [
                        'student_id' => $studentId,
                        'class_id' => $data['class_id'],
                        'section_id' => $data['section_id'],
                        'session_id' => $data['session_id'],
                        'is_active' => 'yes'
                    ];

                    if (!$this->studentSessionModel->insert($sessionData)) {
                        $this->studentModel->delete($studentId);
                        $errorCount++;
                        $errors[] = "Row " . ($index + 1) . ": Failed to create student session";
                        continue;
                    }

                    $successCount++;
                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = "Row " . ($index + 1) . ": " . $e->getMessage();
                }
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => "Registered $successCount students successfully" . ($errorCount > 0 ? ", $errorCount failed" : ""),
                'successCount' => $successCount,
                'errorCount' => $errorCount,
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            log_message('error', '[StudentManagement.storeBulk] Exception: {message}', ['message' => $e->getMessage()]);
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to register students: ' . $e->getMessage()
            ]);
        }
    }

    public function update($id = null)
    {
        try {
            $data = $this->request->getPost();
            
            $rules = [
                'firstname' => 'required|min_length[2]|max_length[100]',
                'lastname' => 'required|min_length[2]|max_length[100]',
                'middlename' => 'permit_empty|max_length[100]',
                'dob' => 'required|valid_date',
                'gender' => 'required|in_list[male,female,other]',
                'guardian_phone' => 'required|min_length[10]|max_length[15]',
                'class_id' => 'required',
                'section_id' => 'required',
                'session_id' => 'required',
                'is_active' => 'permit_empty|in_list[yes,no]'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $student = $this->studentModel->find($id);
            
            if (!$student) {
                return redirect()->to('students')->with('error', 'Student not found');
            }

            $studentData = [
                'firstname' => $data['firstname'],
                'middlename' => $data['middlename'] ?? '',
                'lastname' => $data['lastname'],
                'dob' => $data['dob'],
                'gender' => $data['gender'],
                'guardian_phone' => $data['guardian_phone'],
                'is_active' => $data['is_active'] ?? 'yes'
            ];

            if (!$this->studentModel->update($id, $studentData)) {
                return redirect()->back()->withInput()->with('error', 'Failed to update student');
            }

            $studentSession = $this->studentSessionModel->where('student_id', $id)->first();
            
            $sessionData = [
                'class_id' => $data['class_id'],
                'section_id' => $data['section_id'],
                'session_id' => $data['session_id']
            ];

            if ($studentSession) {
                $this->studentSessionModel->update($studentSession['id'], $sessionData);
            } else {
                $sessionData['student_id'] = $id;
                $sessionData['is_active'] = 'yes';
                $this->studentSessionModel->insert($sessionData);
            }

            return redirect()->to('students')->with('success', 'Student updated successfully!');
        } catch (\Exception $e) {
            log_message('error', '[StudentManagement.update] Exception: {message}', ['message' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Failed to update student: ' . $e->getMessage());
        }
    }

    public function delete($id = null)
    {
        $this->response->setContentType('application/json');
        
        try {
            $student = $this->studentModel->find($id);
            
            if (!$student) {
                $this->response->setStatusCode(404);
                $this->response->setBody(json_encode([
                    'status' => 'error',
                    'message' => 'Student not found'
                ]));
                $this->response->send();
                exit;
            }

            $this->studentSessionModel->where('student_id', $id)->delete();

            if (!$this->studentModel->delete($id)) {
                $this->response->setStatusCode(500);
                $this->response->setBody(json_encode([
                    'status' => 'error',
                    'message' => 'Failed to delete student'
                ]));
                $this->response->send();
                exit;
            }

            $this->response->setStatusCode(200);
            $this->response->setBody(json_encode([
                'status' => 'success',
                'message' => 'Student deleted successfully'
            ]));
            $this->response->send();
            exit;
        } catch (\Exception $e) {
            log_message('error', '[StudentManagement.delete] Exception: {message}', ['message' => $e->getMessage()]);
            $this->response->setStatusCode(500);
            $this->response->setBody(json_encode([
                'status' => 'error',
                'message' => 'Failed to delete student: ' . $e->getMessage()
            ]));
            $this->response->send();
            exit;
        }
    }
}

<?php

namespace App\Controllers;

use App\Models\StudentModel;
use App\Models\ClassModel;
use App\Models\SectionModel;
use App\Models\SessionModel;
use CodeIgniter\Controller;

class StudentRegistrationController extends Controller
{
    protected $studentModel;
    protected $classModel;
    protected $sectionModel;
    protected $sessionModel;

    public function __construct()
    {
        $this->studentModel = new StudentModel();
        $this->classModel = new ClassModel();
        $this->sectionModel = new SectionModel();
        $this->sessionModel = new SessionModel();
    }

    public function index()
    {
        $data['students'] = $this->studentModel->findAll();
        return view('student/index', $data);
    }

    public function create()
    {
        $data['classes'] = $this->classModel->findAll();
        $data['sections'] = $this->sectionModel->findAll();
        $data['sessions'] = $this->sessionModel->findAll();
        return view('student/create', $data);
    }

    public function store()
    {
        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'registration_number' => $this->request->getPost('registration_number'),
            'gender' => $this->request->getPost('gender'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
        ];

        if ($this->studentModel->insert($data)) {
            return redirect()->to('/students')->with('success', 'Student registered successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to register student');
        }
    }

    public function edit($id)
    {
        $data['student'] = $this->studentModel->find($id);
        $data['classes'] = $this->classModel->findAll();
        $data['sections'] = $this->sectionModel->findAll();
        $data['sessions'] = $this->sessionModel->findAll();
        return view('student/edit', $data);
    }

    public function update($id)
    {
        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'registration_number' => $this->request->getPost('registration_number'),
            'gender' => $this->request->getPost('gender'),
            'date_of_birth' => $this->request->getPost('date_of_birth'),
        ];

        if ($this->studentModel->update($id, $data)) {
            return redirect()->to('/students')->with('success', 'Student updated successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to update student');
        }
    }

    public function delete($id)
    {
        if ($this->studentModel->delete($id)) {
            return redirect()->to('/students')->with('success', 'Student deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to delete student');
        }
    }
}

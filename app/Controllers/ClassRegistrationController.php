<?php

namespace App\Controllers;

use App\Models\ClassModel;
use App\Models\SectionModel;
use CodeIgniter\Controller;

class ClassRegistrationController extends Controller
{
    protected $classModel;
    protected $sectionModel;

    public function __construct()
    {
        $this->classModel = new ClassModel();
        $this->sectionModel = new SectionModel();
    }

    public function index()
    {
        $data['classes'] = $this->classModel->findAll();
        return view('class/index', $data);
    }

    public function create()
    {
        $data['sections'] = $this->sectionModel->findAll();
        return view('class/create', $data);
    }

    public function store()
    {
        $data = [
            'name' => $this->request->getPost('name'),
            'level' => $this->request->getPost('level'),
        ];

        if ($this->classModel->insert($data)) {
            return redirect()->to('/classes')->with('success', 'Class registered successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to register class');
        }
    }

    public function edit($id)
    {
        $data['class'] = $this->classModel->find($id);
        $data['sections'] = $this->sectionModel->findAll();
        return view('class/edit', $data);
    }

    public function update($id)
    {
        $data = [
            'name' => $this->request->getPost('name'),
            'level' => $this->request->getPost('level'),
        ];

        if ($this->classModel->update($id, $data)) {
            return redirect()->to('/classes')->with('success', 'Class updated successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to update class');
        }
    }

    public function delete($id)
    {
        if ($this->classModel->delete($id)) {
            return redirect()->to('/classes')->with('success', 'Class deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to delete class');
        }
    }
}

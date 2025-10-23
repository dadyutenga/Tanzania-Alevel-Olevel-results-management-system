<?php

namespace App\Controllers;

use App\Models\ClassModel;
use CodeIgniter\RESTful\ResourceController;

class ClassManagementController extends ResourceController
{
    protected $format = 'json';
    protected $classModel;

    public function __construct()
    {
        $this->classModel = new ClassModel();
    }

    public function index()
    {
        try {
            $data = [
                'title' => 'Manage Classes'
            ];
            
            return view('classsManage/index', $data);
        } catch (\Exception $e) {
            log_message('error', '[ClassManagement.index] Exception: {message}', ['message' => $e->getMessage()]);
            return redirect()->to('dashboard')->with('error', 'Failed to load classes page');
        }
    }

    public function create()
    {
        $session = service('session');
        $userId = $session->get('user_uuid') ?? $session->get('user_id');
        
        if (!$userId) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Create Class',
            'class' => null,
        ];

        return view('classsManage/manage', $data);
    }

    public function edit($id = null)
    {
        $session = service('session');
        $userId = $session->get('user_uuid') ?? $session->get('user_id');
        
        if (!$userId) {
            return redirect()->to('/login');
        }

        $class = $this->classModel->find($id);
        
        if (!$class) {
            return redirect()->to('classes')->with('error', 'Class not found');
        }

        $data = [
            'title' => 'Edit Class',
            'class' => $class,
        ];

        return view('classsManage/manage', $data);
    }

    public function getClasses()
    {
        try {
            $classes = $this->classModel->orderBy('class', 'ASC')->findAll();
            
            return $this->respond([
                'status' => 'success',
                'data' => $classes
            ]);
        } catch (\Exception $e) {
            log_message('error', '[ClassManagement.getClasses] Exception: {message}', ['message' => $e->getMessage()]);
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch classes'
            ], 500);
        }
    }

    public function store()
    {
        try {
            $data = $this->request->getPost();
            
            $rules = [
                'class' => 'required|max_length[60]',
                'is_active' => 'permit_empty|in_list[yes,no]'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $classData = [
                'class' => $data['class'],
                'is_active' => $data['is_active'] ?? 'yes'
            ];

            if (!$this->classModel->insert($classData)) {
                return redirect()->back()->withInput()->with('error', 'Failed to create class');
            }

            return redirect()->to('classes')->with('success', 'Class created successfully!');
        } catch (\Exception $e) {
            log_message('error', '[ClassManagement.store] Exception: {message}', ['message' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Failed to create class: ' . $e->getMessage());
        }
    }

    public function update($id = null)
    {
        try {
            $data = $this->request->getPost();
            
            $rules = [
                'class' => 'required|max_length[60]',
                'is_active' => 'permit_empty|in_list[yes,no]'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $class = $this->classModel->find($id);
            
            if (!$class) {
                return redirect()->to('classes')->with('error', 'Class not found');
            }

            $classData = [
                'class' => $data['class'],
                'is_active' => $data['is_active'] ?? 'yes'
            ];

            if (!$this->classModel->update($id, $classData)) {
                return redirect()->back()->withInput()->with('error', 'Failed to update class');
            }

            return redirect()->to('classes')->with('success', 'Class updated successfully!');
        } catch (\Exception $e) {
            log_message('error', '[ClassManagement.update] Exception: {message}', ['message' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Failed to update class: ' . $e->getMessage());
        }
    }

    public function delete($id = null)
    {
        $this->response->setContentType('application/json');
        
        try {
            $class = $this->classModel->find($id);
            
            if (!$class) {
                $this->response->setStatusCode(404);
                $this->response->setBody(json_encode([
                    'status' => 'error',
                    'message' => 'Class not found'
                ]));
                $this->response->send();
                exit;
            }

            if (!$this->classModel->delete($id)) {
                $this->response->setStatusCode(500);
                $this->response->setBody(json_encode([
                    'status' => 'error',
                    'message' => 'Failed to delete class'
                ]));
                $this->response->send();
                exit;
            }

            $this->response->setStatusCode(200);
            $this->response->setBody(json_encode([
                'status' => 'success',
                'message' => 'Class deleted successfully'
            ]));
            $this->response->send();
            exit;
        } catch (\Exception $e) {
            log_message('error', '[ClassManagement.delete] Exception: {message}', ['message' => $e->getMessage()]);
            $this->response->setStatusCode(500);
            $this->response->setBody(json_encode([
                'status' => 'error',
                'message' => 'Failed to delete class: ' . $e->getMessage()
            ]));
            $this->response->send();
            exit;
        }
    }
}

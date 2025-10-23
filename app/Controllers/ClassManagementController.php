<?php

namespace App\Controllers;

use App\Models\ClassModel;
use App\Models\SectionModel;
use App\Models\ClassSectionModel;
use CodeIgniter\RESTful\ResourceController;

class ClassManagementController extends ResourceController
{
    protected $format = 'json';
    protected $classModel;
    protected $sectionModel;
    protected $classSectionModel;

    public function __construct()
    {
        $this->classModel = new ClassModel();
        $this->sectionModel = new SectionModel();
        $this->classSectionModel = new ClassSectionModel();
    }

    public function index()
    {
        try {
            $data = [
                'title' => 'Manage Classes'
            ];
            
            return view('class/index', $data);
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

        return view('class/manage', $data);
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

        return view('class/manage', $data);
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

    public function sections()
    {
        try {
            $data = [
                'title' => 'Manage Sections'
            ];
            
            return view('class/sections_index', $data);
        } catch (\Exception $e) {
            log_message('error', '[ClassManagement.sections] Exception: {message}', ['message' => $e->getMessage()]);
            return redirect()->to('dashboard')->with('error', 'Failed to load sections page');
        }
    }

    public function createSection()
    {
        $session = service('session');
        $userId = $session->get('user_uuid') ?? $session->get('user_id');
        
        if (!$userId) {
            return redirect()->to('/login');
        }

        $data = [
            'title' => 'Create Section',
            'section' => null,
        ];

        return view('class/sections_manage', $data);
    }

    public function editSection($id = null)
    {
        $session = service('session');
        $userId = $session->get('user_uuid') ?? $session->get('user_id');
        
        if (!$userId) {
            return redirect()->to('/login');
        }

        $section = $this->sectionModel->find($id);
        
        if (!$section) {
            return redirect()->to('classes/sections')->with('error', 'Section not found');
        }

        $data = [
            'title' => 'Edit Section',
            'section' => $section,
        ];

        return view('class/sections_manage', $data);
    }

    public function getSections()
    {
        try {
            $sections = $this->sectionModel->orderBy('section', 'ASC')->findAll();
            
            return $this->respond([
                'status' => 'success',
                'data' => $sections
            ]);
        } catch (\Exception $e) {
            log_message('error', '[ClassManagement.getSections] Exception: {message}', ['message' => $e->getMessage()]);
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch sections'
            ], 500);
        }
    }

    public function storeSection()
    {
        try {
            $data = $this->request->getPost();
            
            $rules = [
                'section' => 'required|max_length[60]',
                'is_active' => 'permit_empty|in_list[yes,no]'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $sectionData = [
                'section' => $data['section'],
                'is_active' => $data['is_active'] ?? 'yes'
            ];

            if (!$this->sectionModel->insert($sectionData)) {
                return redirect()->back()->withInput()->with('error', 'Failed to create section');
            }

            return redirect()->to('classes/sections')->with('success', 'Section created successfully!');
        } catch (\Exception $e) {
            log_message('error', '[ClassManagement.storeSection] Exception: {message}', ['message' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Failed to create section: ' . $e->getMessage());
        }
    }

    public function updateSection($id = null)
    {
        try {
            $data = $this->request->getPost();
            
            $rules = [
                'section' => 'required|max_length[60]',
                'is_active' => 'permit_empty|in_list[yes,no]'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $section = $this->sectionModel->find($id);
            
            if (!$section) {
                return redirect()->to('classes/sections')->with('error', 'Section not found');
            }

            $sectionData = [
                'section' => $data['section'],
                'is_active' => $data['is_active'] ?? 'yes'
            ];

            if (!$this->sectionModel->update($id, $sectionData)) {
                return redirect()->back()->withInput()->with('error', 'Failed to update section');
            }

            return redirect()->to('classes/sections')->with('success', 'Section updated successfully!');
        } catch (\Exception $e) {
            log_message('error', '[ClassManagement.updateSection] Exception: {message}', ['message' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Failed to update section: ' . $e->getMessage());
        }
    }

    public function deleteSection($id = null)
    {
        $this->response->setContentType('application/json');
        
        try {
            $section = $this->sectionModel->find($id);
            
            if (!$section) {
                $this->response->setStatusCode(404);
                $this->response->setBody(json_encode([
                    'status' => 'error',
                    'message' => 'Section not found'
                ]));
                $this->response->send();
                exit;
            }

            if (!$this->sectionModel->delete($id)) {
                $this->response->setStatusCode(500);
                $this->response->setBody(json_encode([
                    'status' => 'error',
                    'message' => 'Failed to delete section'
                ]));
                $this->response->send();
                exit;
            }

            $this->response->setStatusCode(200);
            $this->response->setBody(json_encode([
                'status' => 'success',
                'message' => 'Section deleted successfully'
            ]));
            $this->response->send();
            exit;
        } catch (\Exception $e) {
            log_message('error', '[ClassManagement.deleteSection] Exception: {message}', ['message' => $e->getMessage()]);
            $this->response->setStatusCode(500);
            $this->response->setBody(json_encode([
                'status' => 'error',
                'message' => 'Failed to delete section: ' . $e->getMessage()
            ]));
            $this->response->send();
            exit;
        }
    }

    public function allocations()
    {
        try {
            $data = [
                'title' => 'Class-Section Allocations'
            ];
            
            return view('class/allocations_index', $data);
        } catch (\Exception $e) {
            log_message('error', '[ClassManagement.allocations] Exception: {message}', ['message' => $e->getMessage()]);
            return redirect()->to('dashboard')->with('error', 'Failed to load allocations page');
        }
    }

    public function createAllocation()
    {
        $session = service('session');
        $userId = $session->get('user_uuid') ?? $session->get('user_id');
        
        if (!$userId) {
            return redirect()->to('/login');
        }

        $classes = $this->classModel->where('is_active', 'yes')->orderBy('class', 'ASC')->findAll();
        $sections = $this->sectionModel->where('is_active', 'yes')->orderBy('section', 'ASC')->findAll();

        $data = [
            'title' => 'Create Allocation',
            'allocation' => null,
            'classes' => $classes,
            'sections' => $sections
        ];

        return view('class/allocations_manage', $data);
    }

    public function editAllocation($id = null)
    {
        $session = service('session');
        $userId = $session->get('user_uuid') ?? $session->get('user_id');
        
        if (!$userId) {
            return redirect()->to('/login');
        }

        $allocation = $this->classSectionModel->find($id);
        
        if (!$allocation) {
            return redirect()->to('classes/allocations')->with('error', 'Allocation not found');
        }

        $classes = $this->classModel->where('is_active', 'yes')->orderBy('class', 'ASC')->findAll();
        $sections = $this->sectionModel->where('is_active', 'yes')->orderBy('section', 'ASC')->findAll();

        $data = [
            'title' => 'Edit Allocation',
            'allocation' => $allocation,
            'classes' => $classes,
            'sections' => $sections
        ];

        return view('class/allocations_manage', $data);
    }

    public function getAllocations()
    {
        try {
            $builder = $this->classSectionModel->builder();
            $builder->select('class_sections.*, classes.class, sections.section')
                    ->join('classes', 'classes.id = class_sections.class_id')
                    ->join('sections', 'sections.id = class_sections.section_id')
                    ->orderBy('classes.class', 'ASC');
            
            $allocations = $builder->get()->getResultArray();
            
            return $this->respond([
                'status' => 'success',
                'data' => $allocations
            ]);
        } catch (\Exception $e) {
            log_message('error', '[ClassManagement.getAllocations] Exception: {message}', ['message' => $e->getMessage()]);
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch allocations'
            ], 500);
        }
    }

    public function storeAllocation()
    {
        try {
            $data = $this->request->getPost();
            
            $rules = [
                'class_id' => 'required',
                'section_id' => 'required',
                'is_active' => 'permit_empty|in_list[yes,no]'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $existing = $this->classSectionModel
                ->where('class_id', $data['class_id'])
                ->where('section_id', $data['section_id'])
                ->first();

            if ($existing) {
                return redirect()->back()->withInput()->with('error', 'This class-section allocation already exists');
            }

            $allocationData = [
                'class_id' => $data['class_id'],
                'section_id' => $data['section_id'],
                'is_active' => $data['is_active'] ?? 'yes'
            ];

            if (!$this->classSectionModel->insert($allocationData)) {
                return redirect()->back()->withInput()->with('error', 'Failed to create allocation');
            }

            return redirect()->to('classes/allocations')->with('success', 'Allocation created successfully!');
        } catch (\Exception $e) {
            log_message('error', '[ClassManagement.storeAllocation] Exception: {message}', ['message' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Failed to create allocation: ' . $e->getMessage());
        }
    }

    public function updateAllocation($id = null)
    {
        try {
            $data = $this->request->getPost();
            
            $rules = [
                'class_id' => 'required',
                'section_id' => 'required',
                'is_active' => 'permit_empty|in_list[yes,no]'
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $allocation = $this->classSectionModel->find($id);
            
            if (!$allocation) {
                return redirect()->to('classes/allocations')->with('error', 'Allocation not found');
            }

            $existing = $this->classSectionModel
                ->where('class_id', $data['class_id'])
                ->where('section_id', $data['section_id'])
                ->where('id !=', $id)
                ->first();

            if ($existing) {
                return redirect()->back()->withInput()->with('error', 'This class-section allocation already exists');
            }

            $allocationData = [
                'class_id' => $data['class_id'],
                'section_id' => $data['section_id'],
                'is_active' => $data['is_active'] ?? 'yes'
            ];

            if (!$this->classSectionModel->update($id, $allocationData)) {
                return redirect()->back()->withInput()->with('error', 'Failed to update allocation');
            }

            return redirect()->to('classes/allocations')->with('success', 'Allocation updated successfully!');
        } catch (\Exception $e) {
            log_message('error', '[ClassManagement.updateAllocation] Exception: {message}', ['message' => $e->getMessage()]);
            return redirect()->back()->withInput()->with('error', 'Failed to update allocation: ' . $e->getMessage());
        }
    }

    public function deleteAllocation($id = null)
    {
        $this->response->setContentType('application/json');
        
        try {
            $allocation = $this->classSectionModel->find($id);
            
            if (!$allocation) {
                $this->response->setStatusCode(404);
                $this->response->setBody(json_encode([
                    'status' => 'error',
                    'message' => 'Allocation not found'
                ]));
                $this->response->send();
                exit;
            }

            if (!$this->classSectionModel->delete($id)) {
                $this->response->setStatusCode(500);
                $this->response->setBody(json_encode([
                    'status' => 'error',
                    'message' => 'Failed to delete allocation'
                ]));
                $this->response->send();
                exit;
            }

            $this->response->setStatusCode(200);
            $this->response->setBody(json_encode([
                'status' => 'success',
                'message' => 'Allocation deleted successfully'
            ]));
            $this->response->send();
            exit;
        } catch (\Exception $e) {
            log_message('error', '[ClassManagement.deleteAllocation] Exception: {message}', ['message' => $e->getMessage()]);
            $this->response->setStatusCode(500);
            $this->response->setBody(json_encode([
                'status' => 'error',
                'message' => 'Failed to delete allocation: ' . $e->getMessage()
            ]));
            $this->response->send();
            exit;
        }
    }
}

<?php

namespace App\Controllers\Alevel;

use App\Controllers\BaseController;
use App\Models\StudentAlevelCombinationModel;
use App\Models\ClassModel;
use App\Models\SessionModel;
use App\Models\ClassSectionModel;
use App\Models\AlevelCombinationModel;

class AllocationCombinationClasssController extends BaseController
{
    protected $studentAlevelCombinationModel;
    protected $classModel;
    protected $sessionModel;
    protected $classSectionModel;
    protected $alevelCombinationModel;

    public function __construct()
    {
        $this->studentAlevelCombinationModel = new StudentAlevelCombinationModel();
        $this->classModel = new ClassModel();
        $this->sessionModel = new SessionModel();
        $this->classSectionModel = new ClassSectionModel();
        $this->alevelCombinationModel = new AlevelCombinationModel();
    }

    public function index()
    {
        $data = [
            'classes' => $this->classModel->where('is_active', 'yes')->findAll(),
            'sessions' => $this->sessionModel->getAllSessions(),
            'combinations' => $this->alevelCombinationModel->where('is_active', 'yes')->findAll(),
            'allocations' => $this->getAllocationsWithDetails()
        ];

        return view('alevel/ViewAllocations', $data);
    }

    public function create()
    {
        $data = [
            'classes' => $this->classModel->where('is_active', 'yes')->findAll(),
            'sessions' => $this->sessionModel->getAllSessions(),
            'combinations' => $this->alevelCombinationModel->where('is_active', 'yes')->findAll()
        ];

        return view('alevel/AllocationAlevel', $data);
    }

    public function store()
    {
        // Validate request
        $rules = [
            'combination_id' => 'required|numeric',
            'class_id' => 'required|numeric',
            'session_id' => 'required|numeric',
            'section_id' => 'permit_empty|numeric',
            'is_active' => 'required|in_list[yes,no]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        // Check for duplicate allocation
        $existingAllocation = $this->studentAlevelCombinationModel
            ->where('combination_id', $this->request->getPost('combination_id'))
            ->where('class_id', $this->request->getPost('class_id'))
            ->where('session_id', $this->request->getPost('session_id'))
            ->first();

        if ($existingAllocation) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'This combination is already allocated to this class for the selected session.');
        }

        try {
            $data = [
                'combination_id' => $this->request->getPost('combination_id'),
                'class_id' => $this->request->getPost('class_id'),
                'session_id' => $this->request->getPost('session_id'),
                'section_id' => $this->request->getPost('section_id') ?: null,
                'is_active' => $this->request->getPost('is_active')
            ];

            $this->studentAlevelCombinationModel->insert($data);
            return redirect()->to(base_url('alevel/allocations/view'))
                           ->with('message', 'Combination allocated successfully');
        } catch (\Exception $e) {
            log_message('error', '[AllocationCombinationClasssController.store] Error: ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to allocate combination. Please try again.');
        }
    }

    public function edit($id)
    {
        $allocation = $this->studentAlevelCombinationModel->find($id);
        if (!$allocation) {
            return redirect()->to(base_url('alevel/allocations/view'))
                           ->with('error', 'Allocation not found');
        }

        $data = [
            'allocation' => $allocation,
            'classes' => $this->classModel->where('is_active', 'yes')->findAll(),
            'sessions' => $this->sessionModel->getAllSessions(),
            'combinations' => $this->alevelCombinationModel->where('is_active', 'yes')->findAll(),
            'sections' => $this->classSectionModel->getActiveClassSections($allocation['class_id'])
        ];

        return view('alevel/allocation/edit_allocation', $data);
    }

    public function update($id)
    {
        // Validate request
        $rules = [
            'combination_id' => 'required|numeric',
            'class_id' => 'required|numeric',
            'session_id' => 'required|numeric',
            'section_id' => 'permit_empty|numeric',
            'is_active' => 'required|in_list[yes,no]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        try {
            $data = [
                'combination_id' => $this->request->getPost('combination_id'),
                'class_id' => $this->request->getPost('class_id'),
                'session_id' => $this->request->getPost('session_id'),
                'section_id' => $this->request->getPost('section_id') ?: null,
                'is_active' => $this->request->getPost('is_active')
            ];

            $this->studentAlevelCombinationModel->update($id, $data);
            return redirect()->to(base_url('alevel/allocations/view'))
                           ->with('message', 'Allocation updated successfully');
        } catch (\Exception $e) {
            log_message('error', '[AllocationCombinationClasssController.update] Error: ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Failed to update allocation. Please try again.');
        }
    }

    public function delete($id)
    {
        try {
            $allocation = $this->studentAlevelCombinationModel->find($id);
            if (!$allocation) {
                return redirect()->to(base_url('alevel/allocations/view'))
                               ->with('error', 'Allocation not found');
            }

            $this->studentAlevelCombinationModel->delete($id);
            return redirect()->to(base_url('alevel/allocations/view'))
                           ->with('message', 'Allocation deleted successfully');
        } catch (\Exception $e) {
            log_message('error', '[AllocationCombinationClasssController.delete] Error: ' . $e->getMessage());
            return redirect()->to(base_url('alevel/allocations/view'))
                           ->with('error', 'Failed to delete allocation. Please try again.');
        }
    }

    public function getSections()
    {
        $classId = $this->request->getGet('class_id');
        if (!$classId) {
            return $this->response->setJSON(['error' => 'Class ID is required']);
        }

        $sections = $this->classSectionModel->getActiveClassSections($classId);
        return $this->response->setJSON(['sections' => $sections]);
    }

    public function getClassesBySession($sessionId)
    {
        try {
            // Log the request for debugging
            log_message('debug', '[AllocationCombinationClasssController.getClassesBySession] Session ID: ' . $sessionId);

            // Fetch active classes
            $classes = $this->classModel
                ->where('is_active', 'yes')
                ->findAll();

            // Log the number of classes found
            log_message('debug', '[AllocationCombinationClasssController.getClassesBySession] Classes found: ' . count($classes));

            return $this->response->setJSON([
                'status' => 'success',
                'data' => $classes
            ]);
        } catch (\Exception $e) {
            log_message('error', '[AllocationCombinationClasssController.getClassesBySession] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to fetch classes: ' . $e->getMessage()
            ]);
        }
    }

    protected function getAllocationsWithDetails()
    {
        $allocations = $this->studentAlevelCombinationModel->findAll();
        $detailedAllocations = [];

        foreach ($allocations as $allocation) {
            $combination = $this->alevelCombinationModel->find($allocation['combination_id']);
            $class = $this->classModel->find($allocation['class_id']);
            $session = $this->sessionModel->find($allocation['session_id']);
            
            if ($allocation['section_id']) {
                $section = $this->classSectionModel->getClassSectionDetails(
                    $allocation['class_id'], 
                    $allocation['section_id']
                );
            }

            $detailedAllocations[] = array_merge($allocation, [
                'combination_name' => $combination['combination_name'] ?? 'Unknown',
                'combination_code' => $combination['combination_code'] ?? 'Unknown',
                'class_name' => $class['class'] ?? 'Unknown',
                'session_name' => $session['session'] ?? 'Unknown',
                'section_name' => $section['section_name'] ?? null
            ]);
        }

        return $detailedAllocations;
    }
}

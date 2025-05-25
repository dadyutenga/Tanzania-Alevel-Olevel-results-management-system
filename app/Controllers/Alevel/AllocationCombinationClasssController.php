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
        // Log the incoming request for debugging
        log_message('debug', '[AllocationCombinationClasssController.store] Request received with data: ' . json_encode($this->request->getPost()));

        // Validate request
        $rules = [
            'combination_id' => 'required|numeric',
            'class_id' => 'required|numeric',
            'session_id' => 'required|numeric',
            'section_id' => 'permit_empty|numeric',
            'is_active' => 'required|in_list[yes,no]'
        ];

        if (!$this->validate($rules)) {
            log_message('debug', '[AllocationCombinationClasssController.store] Validation failed: ' . json_encode($this->validator->getErrors()));
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Check for duplicate allocation
        $existingAllocation = $this->studentAlevelCombinationModel
            ->where('combination_id', $this->request->getPost('combination_id'))
            ->where('class_id', $this->request->getPost('class_id'))
            ->where('session_id', $this->request->getPost('session_id'))
            ->first();

        if ($existingAllocation) {
            log_message('debug', '[AllocationCombinationClasssController.store] Duplicate allocation found.');
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'This combination is already allocated to this class for the selected session.'
            ]);
        }

        try {
            $data = [
                'combination_id' => $this->request->getPost('combination_id'),
                'class_id' => $this->request->getPost('class_id'),
                'session_id' => $this->request->getPost('session_id'),
                'section_id' => $this->request->getPost('section_id') ?: null,
                'is_active' => $this->request->getPost('is_active')
            ];

            log_message('debug', '[AllocationCombinationClasssController.store] Attempting to insert data: ' . json_encode($data));
            $this->studentAlevelCombinationModel->insert($data);
            log_message('debug', '[AllocationCombinationClasssController.store] Data inserted successfully.');
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Combination allocated successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', '[AllocationCombinationClasssController.store] Error: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Failed to allocate combination. Please try again. Error: ' . $e->getMessage()
            ]);
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

            // Fetch classes and sections based on session ID using the provided custom query with the default connection
            $db = \Config\Database::connect('default');
            $query = $db->query("
                SELECT DISTINCT
                    c.id AS class_id,
                    c.class AS class_name,
                    s.id AS section_id,
                    s.section AS section_name,
                    cs.is_active AS class_section_status
                FROM 
                    classes c
                JOIN 
                    class_sections cs ON cs.class_id = c.id
                JOIN 
                    sections s ON cs.section_id = s.id
                JOIN 
                    student_session ss ON ss.class_id = c.id AND ss.section_id = s.id
                WHERE 
                    ss.session_id = ?
                    AND c.is_active = 'yes'
                    AND s.is_active = 'yes'
                    AND cs.is_active = 'yes'
                    AND ss.is_active = 'yes'
                ORDER BY 
                    c.class, s.section
            ", [$sessionId]);

            $results = $query->getResultArray();

            // Log the number of results found
            log_message('debug', '[AllocationCombinationClasssController.getClassesBySession] Results found with session filter: ' . count($results));

            // Format the data to group sections under classes for easier frontend handling
            $classes = [];
            foreach ($results as $row) {
                $classId = $row['class_id'];
                if (!isset($classes[$classId])) {
                    $classes[$classId] = [
                        'id' => $row['class_id'],
                        'class' => $row['class_name'],
                        'sections' => []
                    ];
                }
                $classes[$classId]['sections'][] = [
                    'id' => $row['section_id'],
                    'section_name' => $row['section_name']
                ];
            }
            $classes = array_values($classes);

            // Log the number of unique classes found
            log_message('debug', '[AllocationCombinationClasssController.getClassesBySession] Unique classes found: ' . count($classes));

            // If no results are found, try fetching all active classes as a fallback for debugging
            if (empty($classes)) {
                log_message('debug', '[AllocationCombinationClasssController.getClassesBySession] No results found with session filter, fetching all active classes');
                $fallbackQuery = $db->query("
                    SELECT 
                        id,
                        class
                    FROM 
                        classes
                    WHERE 
                        is_active = 'yes'
                    ORDER BY 
                        class
                ");
                $fallbackClasses = $fallbackQuery->getResultArray();
                log_message('debug', '[AllocationCombinationClasssController.getClassesBySession] Fallback active classes found: ' . count($fallbackClasses));

                // Return fallback classes if available, otherwise return empty result
                if (!empty($fallbackClasses)) {
                    $classes = array_map(function($class) {
                        return [
                            'id' => $class['id'],
                            'class' => $class['class'],
                            'sections' => []
                        ];
                    }, $fallbackClasses);
                }
            }

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

    /**
     * Fetch all allocations with related details from combinations, classes, sections, and sessions.
     * 
     * @return array
     */
    public function getAllocationsWithDetails()
    {
        try {
            // Fetch all allocations
        $allocations = $this->studentAlevelCombinationModel->findAll();
        $detailedAllocations = [];

            // Loop through each allocation to fetch related details
        foreach ($allocations as $allocation) {
                // Fetch combination details
            $combination = $this->alevelCombinationModel->find($allocation['combination_id']);
                // Fetch class details
            $class = $this->classModel->find($allocation['class_id']);
                // Fetch session details
            $session = $this->sessionModel->find($allocation['session_id']);
                // Fetch section details if section_id is set
                $section = null;
            if ($allocation['section_id']) {
                    $section = $this->classSectionModel->find($allocation['section_id']);
                }

                // Combine the data
                $detailedAllocations[] = [
                    'id' => $allocation['id'],
                    'combination_id' => $allocation['combination_id'],
                    'combination_name' => $combination ? $combination['combination_name'] : 'N/A',
                    'combination_code' => $combination ? $combination['combination_code'] : 'N/A',
                    'class_id' => $allocation['class_id'],
                    'class_name' => $class ? $class['class'] : 'N/A',
                    'section_id' => $allocation['section_id'],
                    'section_name' => $section && isset($section['section']) ? $section['section'] : ($section && isset($section['section_name']) ? $section['section_name'] : null),
                    'session_id' => $allocation['session_id'],
                    'session_name' => $session ? $session['session'] : 'N/A',
                    'is_active' => $allocation['is_active']
                ];
        }

        return $detailedAllocations;
        } catch (\Exception $e) {
            log_message('error', '[AllocationCombinationClasssController.getAllocationsWithDetails] Error: ' . $e->getMessage());
            return [];
        }
    }
}
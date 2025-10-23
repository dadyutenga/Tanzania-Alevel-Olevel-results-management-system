<?php

namespace App\Controllers;

use App\Models\SettingsModel;
use App\Models\SessionModel;
use App\Libraries\MinioService;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class SettingsController extends ResourceController
{
    use ResponseTrait;

    protected $settingsModel;
    protected $sessionModel;
    protected $minioService = null;
    protected $format = "json";

    public function __construct()
    {
        $this->settingsModel = new SettingsModel();
        $this->sessionModel = new SessionModel();
    }

    protected function getMinioService()
    {
        if ($this->minioService === null) {
            $this->minioService = new MinioService();
        }
        return $this->minioService;
    }

    public function index()
    {
        $session = service('session');
        $userId = $session->get('user_uuid') ?? $session->get('user_id');
        
        if (!$userId) {
            return redirect()->to('/login');
        }

        $existingSchool = $this->settingsModel->getSchoolByUserId($userId);
        
        if ($existingSchool) {
            return redirect()->to('/settings/view');
        } else {
            return redirect()->to('/settings/create');
        }
    }

    public function create($id = null)
    {
        $session = service('session');
        $userId = $session->get('user_uuid') ?? $session->get('user_id');
        
        if (!$userId) {
            return redirect()->to('/login');
        }

        $existingSchool = $this->settingsModel->getSchoolByUserId($userId);
        
        if ($existingSchool) {
            return redirect()->to('/settings/edit');
        }

        $data = [
            'title' => 'Create School Settings',
            'settings' => null,
        ];

        return view('settings/manage', $data);
    }

    public function edit($id = null)
    {
        $session = service('session');
        $userId = $session->get('user_uuid') ?? $session->get('user_id');
        
        if (!$userId) {
            return redirect()->to('/login');
        }

        $existingSchool = $this->settingsModel->getSchoolByUserId($userId);
        
        if (!$existingSchool) {
            return redirect()->to('/settings/create');
        }

        $data = [
            'title' => 'Edit School Settings',
            'settings' => $existingSchool,
        ];

        return view('settings/manage', $data);
    }

    public function view($id = null)
    {
        $session = service('session');
        $userId = $session->get('user_uuid') ?? $session->get('user_id');
        
        if (!$userId) {
            return redirect()->to('/login');
        }

        $existingSchool = $this->settingsModel->getSchoolByUserId($userId);
        
        if (!$existingSchool) {
            return redirect()->to('/settings/create');
        }

        $data = [
            'title' => 'School Settings',
            'settings' => $existingSchool,
        ];

        return view('settings/view', $data);
    }

    public function store($id = null)
    {
        try {
            $data = $this->request->getPost();
            $session = service('session');
            $userId = $session->get('user_uuid') ?? $session->get('user_id');
            
            if (!$userId) {
                return $this->fail('User not authenticated', 401);
            }

            $existingSchool = $this->settingsModel->getSchoolByUserId($userId);
            
            if ($existingSchool) {
                return $this->fail('School already exists. Use update instead.', 400);
            }

            $file = $this->request->getFile('school_logo');
            
            if ($file && $file->isValid()) {
                $maxSize = 5 * 1024 * 1024;
                if ($file->getSize() > $maxSize) {
                    return $this->fail('Image file is too large. Maximum size allowed is 5MB.', 400);
                }

                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                if (!in_array($file->getMimeType(), $allowedTypes)) {
                    return $this->fail('Invalid image type. Only JPEG, PNG, and GIF are allowed.', 400);
                }

                // Store as binary data in database
                $imageData = file_get_contents($file->getTempName());
                if ($imageData === false) {
                    return $this->fail('Failed to read image data.', 500);
                }
                
                $data['school_logo'] = base64_encode($imageData);
            }

            if (!$this->settingsModel->validate($data)) {
                return $this->failValidationErrors($this->settingsModel->errors());
            }

            $db = \Config\Database::connect();
            $db->transStart();

            $schoolId = $this->generateUuid();
            $data['id'] = $schoolId;
            
            if (!$this->settingsModel->createSchool($data)) {
                $db->transRollback();
                return $this->fail('Failed to create school settings', 500);
            }

            if (isset($data['school_year']) && !empty($data['school_year'])) {
                $sessionData = [
                    'session' => $data['school_year'],
                    'is_active' => 'yes',
                    'school_id' => $schoolId
                ];

                if (!$this->sessionModel->createSession($sessionData)) {
                    $db->transRollback();
                    return $this->fail('Failed to create session', 500);
                }

                $session->set('school_id', $schoolId);
                $session->set('school_year', $data['school_year']);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->fail('Failed to save settings', 500);
            }

            return $this->respond([
                'status' => 'success',
                'message' => 'School created successfully',
                'redirect' => base_url('settings/view')
            ]);
        } catch (\Exception $e) {
            log_message('error', '[SettingsController.store] Exception: ' . $e->getMessage());
            return $this->fail('Failed to create school: ' . $e->getMessage(), 500);
        }
    }

    public function update($id = null)
    {
        try {
            $data = $this->request->getPost();
            $session = service('session');
            $userId = $session->get('user_uuid') ?? $session->get('user_id');
            
            if (!$userId) {
                return $this->fail('User not authenticated', 401);
            }

            $existingSchool = $this->settingsModel->getSchoolByUserId($userId);
            
            if (!$existingSchool) {
                return $this->fail('No school found. Please create one first.', 404);
            }

            $schoolId = $existingSchool['id'];
            $file = $this->request->getFile('school_logo');
            
            if ($file && $file->isValid()) {
                $maxSize = 5 * 1024 * 1024;
                if ($file->getSize() > $maxSize) {
                    return $this->fail('Image file is too large. Maximum size allowed is 5MB.', 400);
                }

                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                if (!in_array($file->getMimeType(), $allowedTypes)) {
                    return $this->fail('Invalid image type. Only JPEG, PNG, and GIF are allowed.', 400);
                }

                // Store as binary data in database
                $imageData = file_get_contents($file->getTempName());
                if ($imageData === false) {
                    return $this->fail('Failed to read image data.', 500);
                }
                
                $data['school_logo'] = base64_encode($imageData);
            } else {
                $data['school_logo'] = $existingSchool['school_logo'];
            }

            if (!$this->settingsModel->validate($data)) {
                return $this->failValidationErrors($this->settingsModel->errors());
            }

            $db = \Config\Database::connect();
            $db->transStart();

            if (!$this->settingsModel->updateSchool($schoolId, $data)) {
                $db->transRollback();
                return $this->fail('Failed to update school settings', 500);
            }

            if (isset($data['school_year']) && !empty($data['school_year'])) {
                $schoolYear = $data['school_year'];
                $existingSession = $this->sessionModel->getSessionBySchoolAndYear($schoolId, $schoolYear);

                if ($existingSession) {
                    $sessionUpdate = $this->sessionModel->updateSession($existingSession['id'], [
                        'session' => $schoolYear,
                        'is_active' => 'yes',
                        'school_id' => $schoolId
                    ]);

                    if (!$sessionUpdate) {
                        $db->transRollback();
                        return $this->fail('Failed to update session', 500);
                    }
                } else {
                    $sessionData = [
                        'session' => $schoolYear,
                        'is_active' => 'yes',
                        'school_id' => $schoolId
                    ];

                    if (!$this->sessionModel->createSession($sessionData)) {
                        $db->transRollback();
                        return $this->fail('Failed to create session', 500);
                    }
                }

                $session->set('school_id', $schoolId);
                $session->set('school_year', $schoolYear);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->fail('Failed to update settings', 500);
            }

            return $this->respond([
                'status' => 'success',
                'message' => 'Settings updated successfully',
                'redirect' => base_url('settings/view')
            ]);
        } catch (\Exception $e) {
            log_message('error', '[SettingsController.update] Exception: ' . $e->getMessage());
            return $this->fail('Failed to update settings: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Extract MinIO object key from full URL
     */
    private function extractMinioKey($url)
    {
        if (empty($url)) {
            return null;
        }
        
        // Extract the path after the bucket name
        $parts = parse_url($url);
        if (isset($parts['path'])) {
            // Remove leading slash and bucket name
            $path = ltrim($parts['path'], '/');
            $segments = explode('/', $path, 2);
            return $segments[1] ?? null;
        }
        
        return null;
    }

    /**
     * Generate UUID v4
     */
    private function generateUuid(): string
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    /**
     * Test endpoint to verify controller is working
     */
    public function test()
    {
        log_message("debug", "[SettingsController.test] Test endpoint called");
        return $this->respond([
            "status" => "success",
            "message" => "SettingsController is working!",
            "timestamp" => date("Y-m-d H:i:s"),
        ]);
    }


}

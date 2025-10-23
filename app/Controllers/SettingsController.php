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
    protected $minioService;
    protected $format = "json";

    public function __construct()
    {
        $this->settingsModel = new SettingsModel();
        $this->sessionModel = new SessionModel();
        $this->minioService = new MinioService();
    }

    /**
     * Get the current settings
     */
    public function index()
    {
        try {
            $settings = $this->settingsModel->getCurrentSettings();

            if (!$settings) {
                return $this->failNotFound("Settings not found");
            }

            return $this->respond([
                "status" => "success",
                "data" => $settings,
            ]);
        } catch (\Exception $e) {
            log_message(
                "error",
                "[SettingsController.index] Error: " . $e->getMessage(),
            );
            return $this->fail(
                "Failed to retrieve settings: " . $e->getMessage(),
                500,
            );
        }
    }

    /**
     * Update the settings - creates new school if user doesn't have one, updates existing otherwise
     */
    public function update($id = null)
    {
        try {
            log_message('debug', '[SettingsController.update] Update method called');

            $data = $this->request->getPost();
            log_message('debug', '[SettingsController.update] POST data: ' . json_encode($data));

            // Get current user ID from session
            $session = service('session');
            $userId = $session->get('user_uuid') ?? $session->get('user_id');
            
            if (!$userId) {
                return $this->fail('User not authenticated', 401);
            }

            log_message('debug', '[SettingsController.update] User ID: ' . $userId);

            // Check if user already has a school
            $existingSchool = $this->settingsModel->getSchoolByUserId($userId);
            $schoolId = $existingSchool['id'] ?? null;
            $isUpdate = $existingSchool !== null;

            log_message('debug', '[SettingsController.update] Existing school: ' . ($isUpdate ? 'Yes' : 'No'));

            // Handle file upload to MinIO if a new file is provided
            $logoUrl = null;
            $file = $this->request->getFile('school_logo');
            
            if ($file && $file->isValid()) {
                log_message('debug', '[SettingsController.update] Processing file upload');

                // Validate file
                $maxSize = 5 * 1024 * 1024; // 5MB
                if ($file->getSize() > $maxSize) {
                    return $this->fail('Image file is too large. Maximum size allowed is 5MB.', 400);
                }

                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                if (!in_array($file->getMimeType(), $allowedTypes)) {
                    return $this->fail('Invalid image type. Only JPEG, PNG, and GIF are allowed.', 400);
                }

                // Generate unique filename
                $extension = $file->getExtension();
                $schoolUuid = $schoolId ?? $this->generateUuid();
                $fileName = "schools/logos/{$schoolUuid}.{$extension}";

                // Delete old logo if updating
                if ($isUpdate && !empty($existingSchool['school_logo'])) {
                    $oldLogoKey = $this->extractMinioKey($existingSchool['school_logo']);
                    if ($oldLogoKey) {
                        log_message('debug', '[SettingsController.update] Deleting old logo: ' . $oldLogoKey);
                        $this->minioService->deleteFile($oldLogoKey);
                    }
                }

                // Upload to MinIO
                $uploadResult = $this->minioService->uploadFile(
                    $file->getTempName(),
                    $fileName,
                    $file->getMimeType()
                );

                if (!$uploadResult['success']) {
                    log_message('error', '[SettingsController.update] MinIO upload failed: ' . $uploadResult['error']);
                    return $this->fail('Failed to upload logo: ' . $uploadResult['error'], 500);
                }

                $logoUrl = $uploadResult['url'];
                $data['school_logo'] = $logoUrl;
                
                log_message('debug', '[SettingsController.update] Logo uploaded successfully: ' . $logoUrl);
            } elseif ($isUpdate && isset($existingSchool['school_logo'])) {
                // Keep existing logo if not uploading new one
                $data['school_logo'] = $existingSchool['school_logo'];
            }

            // Validate the input data
            if (!$this->settingsModel->validate($data)) {
                $errors = $this->settingsModel->errors();
                log_message('error', '[SettingsController.update] Validation failed: ' . json_encode($errors));
                return $this->failValidationErrors($errors);
            }

            log_message('debug', '[SettingsController.update] Validation passed');

            // Start database transaction
            $db = \Config\Database::connect();
            $db->transStart();

            // Create or update school settings
            if ($isUpdate) {
                // Update existing school
                log_message('debug', '[SettingsController.update] Updating school ID: ' . $schoolId);
                
                if (!$this->settingsModel->updateSchool($schoolId, $data)) {
                    $db->transRollback();
                    log_message('error', '[SettingsController.update] Failed to update school settings');
                    return $this->fail('Failed to update school settings', 500);
                }
            } else {
                // Create new school
                $schoolId = $this->generateUuid();
                $data['id'] = $schoolId;
                
                log_message('debug', '[SettingsController.update] Creating new school with ID: ' . $schoolId);
                
                if (!$this->settingsModel->createSchool($data)) {
                    $db->transRollback();
                    log_message('error', '[SettingsController.update] Failed to create school settings');
                    return $this->fail('Failed to create school settings', 500);
                }
            }

            // Handle session creation/update
            if (isset($data['school_year']) && !empty($data['school_year'])) {
                $schoolYear = $data['school_year'];
                
                // Check if session already exists for this school and year
                $existingSession = $this->sessionModel->getSessionBySchoolAndYear($schoolId, $schoolYear);

                if ($existingSession) {
                    // Update existing session
                    log_message('debug', '[SettingsController.update] Updating session: ' . $schoolYear);
                    
                    $sessionUpdate = $this->sessionModel->updateSession($existingSession['id'], [
                        'session' => $schoolYear,
                        'is_active' => 'yes',
                        'school_id' => $schoolId
                    ]);

                    if (!$sessionUpdate) {
                        $db->transRollback();
                        log_message('error', '[SettingsController.update] Failed to update session');
                        return $this->fail('Failed to update session', 500);
                    }
                } else {
                    // Create new session
                    log_message('debug', '[SettingsController.update] Creating new session: ' . $schoolYear);
                    
                    $sessionData = [
                        'session' => $schoolYear,
                        'is_active' => 'yes',
                        'school_id' => $schoolId
                    ];

                    $sessionInsert = $this->sessionModel->createSession($sessionData);

                    if (!$sessionInsert) {
                        $db->transRollback();
                        log_message('error', '[SettingsController.update] Failed to create session');
                        return $this->fail('Failed to create session', 500);
                    }
                }

                // Set session in user's session data
                $session->set('school_id', $schoolId);
                $session->set('school_year', $schoolYear);
            }

            // Complete transaction
            $db->transComplete();

            if ($db->transStatus() === false) {
                log_message('error', '[SettingsController.update] Transaction failed');
                return $this->fail('Failed to save settings and session', 500);
            }

            // Retrieve the updated/created settings
            $updatedSettings = $this->settingsModel->getSchoolById($schoolId);

            log_message('debug', '[SettingsController.update] Operation successful');

            return $this->respond([
                'status' => 'success',
                'message' => $isUpdate ? 'Settings updated successfully' : 'School created successfully',
                'data' => $updatedSettings,
            ]);
        } catch (\Exception $e) {
            log_message('error', '[SettingsController.update] Exception: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            log_message('error', '[SettingsController.update] Stack trace: ' . $e->getTraceAsString());

            return $this->fail('Failed to save settings: ' . $e->getMessage(), 500);
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

    /**
     * View for settings page
     */
    public function settingsPage()
    {
        try {
            $settings = $this->settingsModel->getCurrentSettings();

            $data = [
                "title" => "Web Settings",
                "settings" => $settings,
            ];

            return view("Settings", $data);
        } catch (\Exception $e) {
            log_message(
                "error",
                "[SettingsController.settingsPage] Error: " . $e->getMessage(),
            );
            return view("errors/html/error_404", [
                "title" => "Error",
                "message" =>
                    "Failed to load settings page: " . $e->getMessage(),
            ]);
        }
    }
}

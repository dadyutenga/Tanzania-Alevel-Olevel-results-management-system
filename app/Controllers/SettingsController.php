<?php

namespace App\Controllers;

use App\Models\SettingsModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class SettingsController extends ResourceController
{
    use ResponseTrait;

    protected $settingsModel;
    protected $format = 'json';

    public function __construct()
    {
        $this->settingsModel = new SettingsModel();
    }

    /**
     * Get the current settings
     */
    public function index()
    {
        try {
            $settings = $this->settingsModel->getCurrentSettings();
            
            if (!$settings) {
                return $this->failNotFound('Settings not found');
            }

            return $this->respond([
                'status' => 'success',
                'data' => $settings
            ]);
        } catch (\Exception $e) {
            log_message('error', '[SettingsController.index] Error: ' . $e->getMessage());
            return $this->fail('Failed to retrieve settings: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update the settings by deleting existing record and inserting a new one
     */
    public function update($id = 1)
    {
        try {
            $data = $this->request->getPost();
            $file = $this->request->getFile('school_logo');

            // Handle file upload if a new file is provided
            if ($file) {
                if ($file->isValid()) {
                    // Check file size (limit to 5MB for example)
                    $maxSize = 5 * 1024 * 1024; // 5MB in bytes
                    if ($file->getSize() > $maxSize) {
                        return $this->fail('Image file is too large. Maximum size allowed is 5MB.', 400);
                    }

                    // Check file type
                    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                    if (!in_array($file->getMimeType(), $allowedTypes)) {
                        return $this->fail('Invalid image type. Only JPEG, PNG, and GIF are allowed.', 400);
                    }

                    // Read the file content as binary data
                    $imageData = file_get_contents($file->getTempName());
                    if ($imageData === false) {
                        return $this->fail('Failed to read image data.', 500);
                    }

                    // Store the binary data in the data array
                    $data['school_logo'] = $imageData;
                    log_message('debug', '[SettingsController.update] Image data read successfully, size: ' . strlen($imageData) . ' bytes');
                } else {
                    log_message('error', '[SettingsController.update] Invalid file upload: ' . $file->getErrorString());
                    return $this->fail('File upload error: ' . $file->getErrorString(), 400);
                }
            }

            // Validate the input data
            if (!$this->settingsModel->validate($data)) {
                return $this->failValidationErrors($this->settingsModel->errors());
            }

            // Delete any existing record with the given ID to avoid primary key conflict
            $existingRecord = $this->settingsModel->find($id);
            if ($existingRecord) {
                $this->settingsModel->delete($id);
                log_message('debug', '[SettingsController.update] Existing record deleted for ID: ' . $id);
            }

            // Insert a new record with the specified ID
            $data['id'] = $id; // Ensure the ID is set for the new record
            if (!$this->settingsModel->insert($data)) {
                log_message('error', '[SettingsController.update] Failed to insert settings data.');
                return $this->fail('Failed to save settings', 500);
            }

            log_message('debug', '[SettingsController.update] Settings data inserted successfully for ID: ' . $id);

            // Retrieve the updated settings
            $updatedSettings = $this->settingsModel->getCurrentSettings();

            return $this->respond([
                'status' => 'success',
                'message' => 'Settings saved successfully',
                'data' => $updatedSettings
            ]);
        } catch (\Exception $e) {
            log_message('error', '[SettingsController.update] Error: ' . $e->getMessage());
            return $this->fail('Failed to save settings: ' . $e->getMessage(), 500);
        }
    }

    /**
     * View for settings page
     */
    public function settingsPage()
    {
        try {
            $settings = $this->settingsModel->getCurrentSettings();
            
            $data = [
                'title' => 'Web Settings',
                'settings' => $settings
            ];

            return view('Settings', $data);
        } catch (\Exception $e) {
            log_message('error', '[SettingsController.settingsPage] Error: ' . $e->getMessage());
            return view('errors/html/error_404', [
                'title' => 'Error',
                'message' => 'Failed to load settings page: ' . $e->getMessage()
            ]);
        }
    }
}

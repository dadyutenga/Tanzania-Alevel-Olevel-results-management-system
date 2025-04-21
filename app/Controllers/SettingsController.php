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
     * Update the settings
     */
    public function update($id = 1)
    {
        try {
            $data = $this->request->getPost();
            
            // Validate the input data
            if (!$this->settingsModel->validate($data)) {
                return $this->failValidationErrors($this->settingsModel->errors());
            }

            // Update the settings (ID is always 1 as per table structure)
            if (!$this->settingsModel->update($id, $data)) {
                return $this->fail('Failed to update settings', 500);
            }

            // Retrieve the updated settings
            $updatedSettings = $this->settingsModel->getCurrentSettings();

            return $this->respond([
                'status' => 'success',
                'message' => 'Settings updated successfully',
                'data' => $updatedSettings
            ]);
        } catch (\Exception $e) {
            log_message('error', '[SettingsController.update] Error: ' . $e->getMessage());
            return $this->fail('Failed to update settings: ' . $e->getMessage(), 500);
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

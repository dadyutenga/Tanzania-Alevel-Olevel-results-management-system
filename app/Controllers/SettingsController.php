<?php

namespace App\Controllers;

use App\Models\SettingsModel;
use App\Models\SessionModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class SettingsController extends ResourceController
{
    use ResponseTrait;

    protected $settingsModel;
    protected $sessionModel;
    protected $format = "json";

    public function __construct()
    {
        $this->settingsModel = new SettingsModel();
        $this->sessionModel = new SessionModel();
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
     * Update the settings by deleting existing record and inserting a new one
     */
    public function update($id = 1)
    {
        try {
            log_message(
                "debug",
                "[SettingsController.update] Update method called with ID: " .
                    $id,
            );

            $data = $this->request->getPost();
            log_message(
                "debug",
                "[SettingsController.update] POST data: " . json_encode($data),
            );

            $file = $this->request->getFile("school_logo");
            log_message(
                "debug",
                "[SettingsController.update] File received: " .
                    ($file ? "Yes" : "No"),
            );

            // Handle file upload if a new file is provided
            if ($file && !empty($file)) {
                if ($file->isValid()) {
                    // Check file size (limit to 5MB for example)
                    $maxSize = 5 * 1024 * 1024; // 5MB in bytes
                    if ($file->getSize() > $maxSize) {
                        return $this->fail(
                            "Image file is too large. Maximum size allowed is 5MB.",
                            400,
                        );
                    }

                    // Check file type
                    $allowedTypes = ["image/jpeg", "image/png", "image/gif"];
                    if (!in_array($file->getMimeType(), $allowedTypes)) {
                        return $this->fail(
                            "Invalid image type. Only JPEG, PNG, and GIF are allowed.",
                            400,
                        );
                    }

                    // Read the file content as binary data
                    $imageData = file_get_contents($file->getTempName());
                    if ($imageData === false) {
                        return $this->fail("Failed to read image data.", 500);
                    }

                    // Store the binary data in the data array
                    $data["school_logo"] = $imageData;
                    log_message(
                        "debug",
                        "[SettingsController.update] Image data read successfully, size: " .
                            strlen($imageData) .
                            " bytes",
                    );
                } else {
                    log_message(
                        "error",
                        "[SettingsController.update] Invalid file upload: " .
                            $file->getErrorString(),
                    );
                    return $this->fail(
                        "File upload error: " . $file->getErrorString(),
                        400,
                    );
                }
            }

            // Validate the input data
            if (!$this->settingsModel->validate($data)) {
                $errors = $this->settingsModel->errors();
                log_message(
                    "error",
                    "[SettingsController.update] Validation failed: " .
                        json_encode($errors),
                );
                return $this->failValidationErrors($errors);
            }

            log_message(
                "debug",
                "[SettingsController.update] Validation passed",
            );

            // Start database transaction
            $db = \Config\Database::connect();
            $db->transStart();

            // Delete any existing record with the given ID to avoid primary key conflict
            $existingRecord = $this->settingsModel->find($id);
            if ($existingRecord) {
                $this->settingsModel->delete($id);
                log_message(
                    "debug",
                    "[SettingsController.update] Existing record deleted for ID: " .
                        $id,
                );
            }

            // Insert a new record with the specified ID
            $data["id"] = $id; // Ensure the ID is set for the new record
            if (!$this->settingsModel->insert($data)) {
                $db->transRollback();
                log_message(
                    "error",
                    "[SettingsController.update] Failed to insert settings data.",
                );
                return $this->fail("Failed to save settings", 500);
            }

            log_message(
                "debug",
                "[SettingsController.update] Settings data inserted successfully for ID: " .
                    $id,
            );

            // Create or update session if school_year is provided
            if (isset($data["school_year"]) && !empty($data["school_year"])) {
                $schoolYear = $data["school_year"];

                // Check if session already exists
                $existingSession = $this->sessionModel
                    ->where("session", $schoolYear)
                    ->first();

                if ($existingSession) {
                    // Update existing session
                    $sessionUpdate = $this->sessionModel->update(
                        $existingSession["id"],
                        [
                            "session" => $schoolYear,
                            "is_active" => "yes",
                        ],
                    );

                    if (!$sessionUpdate) {
                        $db->transRollback();
                        log_message(
                            "error",
                            "[SettingsController.update] Failed to update session.",
                        );
                        return $this->fail("Failed to update session", 500);
                    }

                    log_message(
                        "debug",
                        "[SettingsController.update] Session updated: " .
                            $schoolYear,
                    );
                } else {
                    // Create new session
                    $sessionData = [
                        "session" => $schoolYear,
                        "is_active" => "yes",
                    ];

                    $sessionInsert = $this->sessionModel->insert($sessionData);

                    if (!$sessionInsert) {
                        $db->transRollback();
                        log_message(
                            "error",
                            "[SettingsController.update] Failed to create session.",
                        );
                        return $this->fail("Failed to create session", 500);
                    }

                    log_message(
                        "debug",
                        "[SettingsController.update] Session created: " .
                            $schoolYear,
                    );
                }
            }

            // Complete transaction
            $db->transComplete();

            if ($db->transStatus() === false) {
                log_message(
                    "error",
                    "[SettingsController.update] Transaction failed.",
                );
                return $this->fail("Failed to save settings and session", 500);
            }

            // Retrieve the updated settings
            $updatedSettings = $this->settingsModel->getCurrentSettings();

            // Prepare response data without binary image data to avoid JSON encoding issues
            $responseData = $updatedSettings;
            if (
                isset($responseData["school_logo"]) &&
                !empty($responseData["school_logo"])
            ) {
                // Replace binary data with a flag or placeholder to indicate an image exists
                $responseData["school_logo"] = "uploaded"; // This avoids including binary data in JSON
            }

            log_message(
                "debug",
                "[SettingsController.update] Sending success response",
            );

            return $this->respond([
                "status" => "success",
                "message" => "Settings and session saved successfully",
                "data" => $responseData,
            ]);
        } catch (\Exception $e) {
            log_message(
                "error",
                "[SettingsController.update] Exception: " .
                    $e->getMessage() .
                    " at " .
                    $e->getFile() .
                    ":" .
                    $e->getLine(),
            );
            log_message(
                "error",
                "[SettingsController.update] Stack trace: " .
                    $e->getTraceAsString(),
            );

            return $this->fail(
                "Failed to save settings: " . $e->getMessage(),
                500,
            );
        }
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

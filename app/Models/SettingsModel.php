<?php

namespace App\Models;

class SettingsModel extends BaseModel
{
    protected $table = 'tz_web_setting';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $protectFields = true;
    
    // Override to prevent school_id auto-assignment since this table doesn't have that column
    protected $skipSchoolId = true;
    
    protected $allowedFields = [
        'id',
        'school_name',
        'total_classes',
        'school_year',
        'school_address',
        'school_logo',
        'contact_email',
        'contact_phone',
        'is_active',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'school_name' => 'required|max_length[255]',
        'total_classes' => 'required|integer',
        'school_year' => 'required|max_length[9]',
        'contact_email' => 'permit_empty|valid_email|max_length[255]',
        'contact_phone' => 'permit_empty|max_length[20]',
        'is_active' => 'required|in_list[yes,no]'
    ];

    protected $validationMessages = [
        'school_name' => [
            'required' => 'School name is required',
            'max_length' => 'School name cannot exceed 255 characters'
        ],
        'total_classes' => [
            'required' => 'Total classes is required',
            'integer' => 'Total classes must be a number'
        ],
        'school_year' => [
            'required' => 'School year is required',
            'max_length' => 'School year must be in format YYYY-YYYY (9 characters)'
        ],
        'contact_email' => [
            'valid_email' => 'Please provide a valid email address',
            'max_length' => 'Email cannot exceed 255 characters'
        ],
        'contact_phone' => [
            'max_length' => 'Phone number cannot exceed 20 characters'
        ],
        'is_active' => [
            'required' => 'Active status is required',
            'in_list' => 'Active status must be either yes or no'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    /**
     * Get the current settings for the active context.
     */
    public function getCurrentSettings()
    {
        return $this->first();
    }

    /**
     * Get school settings by user ID (created_by)
     */
    public function getSchoolByUserId($userId)
    {
        return $this->where('created_by', $userId)->first();
    }

    /**
     * Check if user has a school entry
     */
    public function userHasSchool($userId)
    {
        $school = $this->where('created_by', $userId)->first();
        return $school !== null;
    }

    /**
     * Get school by ID
     */
    public function getSchoolById($schoolId)
    {
        return $this->find($schoolId);
    }

    /**
     * Create new school settings
     */
    public function createSchool($schoolData)
    {
        try {
            return $this->insert($schoolData);
        } catch (\Exception $e) {
            log_message('error', 'Error creating school: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update existing school settings
     */
    public function updateSchool($schoolId, $schoolData)
    {
        try {
            return $this->update($schoolId, $schoolData);
        } catch (\Exception $e) {
            log_message('error', 'Error updating school: ' . $e->getMessage());
            return false;
        }
    }
}

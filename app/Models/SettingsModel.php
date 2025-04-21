<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingsModel extends Model
{
    protected $DBGroup = 'second_db';
    protected $table = 'tz_web_setting';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'school_name',
        'total_classes',
        'school_year',
        'school_address',
        'school_logo_url',
        'contact_email',
        'contact_phone',
        'is_active'
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

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    /**
     * Get the current settings
     * Since ID is always 1 as per the table structure
     */
    public function getCurrentSettings()
    {
        return $this->find(1);
    }
}

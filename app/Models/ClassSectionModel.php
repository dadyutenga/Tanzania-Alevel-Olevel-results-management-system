<?php

namespace App\Models;

class ClassSectionModel extends BaseModel
{
    protected $table = 'class_sections';
    protected $protectFields = true;


    protected $allowedFields = [
        'id',
        'class_id',
        'section_id',
        'is_active',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'school_id',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'class_id' => 'required|max_length[36]',
        'section_id' => 'required|max_length[36]',
        'is_active' => 'required|in_list[yes,no]'
    ];

    protected $validationMessages = [
        'class_id' => [
            'required' => 'Class ID is required',
            'max_length' => 'Class ID must be a valid identifier'
        ],
        'section_id' => [
            'required' => 'Section ID is required',
            'max_length' => 'Section ID must be a valid identifier'
        ],
        'is_active' => [
            'required' => 'Active status is required',
            'in_list' => 'Active status must be either yes or no'
        ]
    ];

    // Custom Methods
    public function getActiveClassSections($classId = null)
    {
        $builder = $this->builder();
        $builder->where('is_active', 'yes'); // Since you're using 'no' as active

        if ($classId !== null) {
            $builder->where('class_id', $classId);
        }

        return $builder->get()->getResultArray();
    }

    public function getClassSectionDetails($classId = null, $sectionId = null)
    {
        $builder = $this->builder();
        
        if ($classId !== null) {
            $builder->where('class_id', $classId);
        }
        
        if ($sectionId !== null) {
            $builder->where('section_id', $sectionId);
        }

        return $builder->get()->getRowArray();
    }
}


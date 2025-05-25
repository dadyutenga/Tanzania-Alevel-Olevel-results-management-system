<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassSectionModel extends Model
{
    protected $table = 'class_sections';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
        

    protected $allowedFields = [
        'class_id',
        'section_id',
        'is_active',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'class_id' => 'required|numeric',
        'section_id' => 'required|numeric',
        'is_active' => 'required|in_list[yes,no]'
    ];

    protected $validationMessages = [
        'class_id' => [
            'required' => 'Class ID is required',
            'numeric' => 'Class ID must be numeric'
        ],
        'section_id' => [
            'required' => 'Section ID is required',
            'numeric' => 'Section ID must be numeric'
        ],
        'is_active' => [
            'required' => 'Active status is required',
            'in_list' => 'Active status must be either yes or no'
        ]
    ];

    // Callbacks
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    protected function beforeInsert(array $data)
    {
        $data['data']['created_at'] = date('Y-m-d H:i:s');
        $data['data']['updated_at'] = date('Y-m-d H:i:s');
        return $data;
    }

    protected function beforeUpdate(array $data)
    {
        $data['data']['updated_at'] = date('Y-m-d H:i:s');
        return $data;
    }

    // Custom Methods
    public function getActiveClassSections($classId = null)
    {
        $builder = $this->builder();
        $builder->where('is_active', 'no'); // Since you're using 'no' as active

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


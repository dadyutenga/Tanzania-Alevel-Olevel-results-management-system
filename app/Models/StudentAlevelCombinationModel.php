<?php

namespace App\Models;

class StudentAlevelCombinationModel extends BaseModel
{
    protected $table            = 'tz_student_alevel_combinations';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'combination_id',
        'class_id',
        'section_id',
        'session_id',
        'is_active',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'school_id'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'combination_id' => 'required|max_length[36]',
        'class_id'       => 'required|max_length[36]',
        'session_id'     => 'required|max_length[36]',
        'is_active'      => 'in_list[yes,no]'
    ];
}
<?php

namespace App\Models;

class AlevelExamCombinationModel extends BaseModel
{
    protected $table            = 'tz_alevel_exam_combinations';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'exam_id',
        'combination_id',
        'class_id',
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
        'exam_id'         => 'required|string|min_length[36]|max_length[36]',
        'combination_id'  => 'required|string|min_length[36]|max_length[36]',
        'class_id'        => 'required|string|min_length[36]|max_length[36]',
        'session_id'      => 'required|string|min_length[36]|max_length[36]',
        'is_active'       => 'in_list[yes,no]'
    ];
}
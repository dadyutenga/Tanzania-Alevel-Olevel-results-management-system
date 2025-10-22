<?php

namespace App\Models;

class AlevelCombinationSubjectModel extends BaseModel
{
    protected $table            = 'tz_alevel_combination_subjects';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'combination_id',
        'subject_name',
        'subject_type',
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
        'subject_name'   => 'required|max_length[100]',
        'subject_type'   => 'required|in_list[major,additional]',
        'is_active'      => 'in_list[yes,no]'
    ];
}
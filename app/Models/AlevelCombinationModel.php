<?php

namespace App\Models;

class AlevelCombinationModel extends BaseModel
{
    protected $table            = 'tz_alevel_combinations';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'combination_code',
        'combination_name',
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
        'combination_code' => 'required|max_length[10]|is_unique[tz_alevel_combinations.combination_code]',
        'combination_name' => 'required|max_length[100]',
        'is_active'        => 'in_list[yes,no]'
    ];
    
}
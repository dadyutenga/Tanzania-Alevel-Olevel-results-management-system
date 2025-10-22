<?php

namespace App\Models;

class ClassModel extends BaseModel
{
    protected $table            = 'classes';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'class',
        'is_active',
        'created_by',
        'updated_by',
        'school_id',
        'created_at',
        'updated_at',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'class'     => 'permit_empty|max_length[60]',
        'is_active' => 'permit_empty|in_list[yes,no]',
    ];
}

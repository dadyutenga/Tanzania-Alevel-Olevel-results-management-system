<?php

namespace App\Models;

class SectionModel extends BaseModel
{
    protected $table            = 'sections';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'section',
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
        'section'   => 'permit_empty|max_length[60]',
        'is_active' => 'permit_empty|in_list[yes,no]',
    ];
}

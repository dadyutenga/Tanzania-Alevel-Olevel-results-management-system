<?php

namespace App\Models;

class StudentSessionModel extends BaseModel
{
    protected $table            = 'student_session';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'session_id',
        'student_id',
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
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'session_id' => 'required|max_length[36]',
        'student_id' => 'required|max_length[36]',
        'class_id' => 'required|max_length[36]',
        'section_id' => 'required|max_length[36]'
    ];
}

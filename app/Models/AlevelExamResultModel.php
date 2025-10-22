<?php

namespace App\Models;

class AlevelExamResultModel extends BaseModel
{
    protected $table            = 'tz_alevel_exam_results';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id',
        'student_id',
        'exam_id',
        'class_id',
        'session_id',
        'combination_id',
        'total_points',
        'division',
        'division_description',
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
        'student_id'      => 'required|max_length[36]',
        'exam_id'         => 'required|max_length[36]',
        'class_id'        => 'required|max_length[36]',
        'session_id'      => 'required|max_length[36]',
        'combination_id'  => 'required|max_length[36]',
        'division'        => 'permit_empty|max_length[5]',
        'division_description' => 'permit_empty|max_length[50]'
    ];
}
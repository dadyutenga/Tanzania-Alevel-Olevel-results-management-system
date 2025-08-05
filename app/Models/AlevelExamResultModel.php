<?php

namespace App\Models;

use CodeIgniter\Model;

class AlevelExamResultModel extends Model
{
    protected $table            = 'tz_alevel_exam_results';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'student_id', 'exam_id', 'class_id', 'session_id', 'combination_id', 
        'total_points', 'division', 'division_description'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'student_id'      => 'required|numeric',
        'exam_id'         => 'required|numeric',
        'class_id'        => 'required|numeric',
        'session_id'      => 'required|numeric',
        'combination_id'  => 'required|numeric',
        'division'        => 'permit_empty|max_length[5]',
        'division_description' => 'permit_empty|max_length[50]'
    ];
}
<?php

namespace App\Models;

use CodeIgniter\Model;

class AlevelExamCombinationModel extends Model
{
    protected $DBGroup          = 'second_db';
    protected $table            = 'tz_alevel_exam_combinations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'exam_id', 'combination_id', 'class_id', 'session_id', 'is_active'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'exam_id'         => 'required|numeric',
        'combination_id'  => 'required|numeric',
        'class_id'        => 'required|numeric',
        'session_id'      => 'required|numeric',
        'is_active'       => 'in_list[yes,no]'
    ];
} 
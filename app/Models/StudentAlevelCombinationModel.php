<?php

namespace App\Models;

use CodeIgniter\Model;

class StudentAlevelCombinationModel extends Model
{
    protected $DBGroup          = 'second_db';
    protected $table            = 'tz_student_alevel_combinations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'combination_id', 'class_id', 'section_id', 'session_id', 'is_active'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'combination_id' => 'required|numeric',
        'class_id'       => 'required|numeric',
        'session_id'     => 'required|numeric',
        'is_active'      => 'in_list[yes,no]'
    ];
}
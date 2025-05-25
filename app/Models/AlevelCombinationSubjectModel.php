<?php

namespace App\Models;

use CodeIgniter\Model;

class AlevelCombinationSubjectModel extends Model
{
    protected $table            = 'tz_alevel_combination_subjects';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'combination_id', 'subject_name', 'subject_type', 'is_active'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'combination_id' => 'required|numeric',
        'subject_name'   => 'required|max_length[100]',
        'subject_type'   => 'required|in_list[major,additional]',
        'is_active'      => 'in_list[yes,no]'
    ];
}
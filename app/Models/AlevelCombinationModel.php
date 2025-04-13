<?php

namespace App\Models;

use CodeIgniter\Model;

class AlevelCombinationModel extends Model
{
    protected $DBGroup          = 'second_db';
    protected $table            = 'tz_alevel_combinations';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'combination_code', 'combination_name', 'is_active'
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
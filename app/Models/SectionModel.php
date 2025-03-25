<?php

namespace App\Models;

use CodeIgniter\Model;

class SectionModel extends Model
{
    protected $DBGroup          = 'second_db';
    protected $table            = 'sections';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['section', 'is_active'];

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

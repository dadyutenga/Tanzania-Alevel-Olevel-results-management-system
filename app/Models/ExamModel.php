<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamModel extends Model
{
    protected $DBGroup = 'second_db';
    protected $table = 'tz_exams';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';
    protected $allowedFields = [
        'exam_name',
        'exam_date',
        'academic_year',
        'is_active'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Custom validation rules
    protected $validationRules = [
        'exam_name' => 'required|max_length[100]',
        'academic_year' => 'permit_empty|max_length[20]',
        'is_active' => 'permit_empty|in_list[yes,no]'
    ];
} 
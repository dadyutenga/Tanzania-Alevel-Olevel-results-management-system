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
        'session_id',
        'is_active'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Custom validation rules
    protected $validationRules = [
        'exam_name' => 'required|max_length[100]',
        'exam_date' => 'permit_empty|valid_date',
        'session_id' => 'required|integer|is_not_unique[sessions.id]',
        'is_active' => 'permit_empty|in_list[yes,no]'
    ];

 
    
    // Optional: Add relationship methods
    public function session()
    {
        return $this->belongsTo('App\Models\SessionModel', 'session_id', 'id');
    }
} 
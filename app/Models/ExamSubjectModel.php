<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamSubjectModel extends Model
{
    protected $DBGroup = 'second_db';
    protected $table = 'tz_exam_subjects';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';
    protected $allowedFields = [
        'exam_id',
        'subject_name',
        'max_marks',
        'passing_marks'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Custom validation rules
    protected $validationRules = [
        'exam_id' => 'required|numeric',
        'subject_name' => 'required|max_length[100]',
        'max_marks' => 'permit_empty|numeric',
        'passing_marks' => 'permit_empty|numeric'
    ];

    // Define relationships
    public function exam()
    {
        return $this->belongsTo(ExamModel::class, 'exam_id', 'id');
    }
} 
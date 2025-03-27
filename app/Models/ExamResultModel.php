<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamResultModel extends Model
{
    protected $DBGroup = 'second_db';
    protected $table = 'tz_exam_results';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';
    protected $allowedFields = [
        'student_id',
        'exam_id',
        'class_id',
        'academic_year',
        'total_points',
        'division',
        'division_description'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Custom validation rules
    protected $validationRules = [
        'student_id' => 'required|numeric',
        'exam_id' => 'required|numeric',
        'class_id' => 'required|numeric',
        'academic_year' => 'permit_empty|max_length[20]',
        'total_points' => 'permit_empty|numeric',
        'division' => 'permit_empty|max_length[5]',
        'division_description' => 'permit_empty|max_length[50]'
    ];

    // Define relationships
    public function student()
    {
        return $this->belongsTo(StudentModel::class, 'student_id', 'id');
    }

    public function exam()
    {
        return $this->belongsTo(ExamModel::class, 'exam_id', 'id');
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id', 'id');
    }
} 
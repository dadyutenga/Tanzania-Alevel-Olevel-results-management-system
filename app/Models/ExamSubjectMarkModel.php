<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamSubjectMarkModel extends Model
{
    protected $DBGroup = 'second_db';
    protected $table = 'tz_exam_subject_marks';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';
    protected $allowedFields = [
        'exam_id',
        'student_id',
        'exam_subject_id',
        'marks_obtained'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Custom validation rules
    protected $validationRules = [
        'exam_id' => 'required|numeric',
        'student_id' => 'required|numeric',
        'exam_subject_id' => 'required|numeric',
        'marks_obtained' => 'permit_empty|numeric'
    ];

    // Define relationships
    public function exam()
    {
        return $this->belongsTo(ExamModel::class, 'exam_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo(StudentModel::class, 'student_id', 'id');
    }

    public function examSubject()
    {
        return $this->belongsTo(ExamSubjectModel::class, 'exam_subject_id', 'id');
    }
} 
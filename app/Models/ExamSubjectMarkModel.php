<?php

namespace App\Models;

class ExamSubjectMarkModel extends BaseModel
{
    protected $table = 'tz_exam_subject_marks';
    protected $allowedFields = [
        'id',
        'exam_id',
        'student_id',
        'class_id',
        'session_id',
        'exam_subject_id',
        'marks_obtained',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'school_id',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation rules matching SQL constraints
    // Note: Removed is_not_unique checks as they conflict with BaseModel's multi-tenancy scoping
    // Foreign key constraints in the database will handle referential integrity
    protected $validationRules = [
        'exam_id' => 'required|string|min_length[36]|max_length[36]',
        'student_id' => 'required|string|min_length[36]|max_length[36]',
        'class_id' => 'required|string|min_length[36]|max_length[36]',
        'session_id' => 'required|string|min_length[36]|max_length[36]',
        'exam_subject_id' => 'required|string|min_length[36]|max_length[36]',
        'marks_obtained' => 'required|numeric'
    ];

    // Relationships based on SQL foreign keys
    public function exam()
    {
        return $this->belongsTo('App\Models\ExamModel', 'exam_id', 'id');
    }

    public function student()
    {
        return $this->belongsTo('App\Models\StudentModel', 'student_id', 'id');
    }

    public function class()
    {
        return $this->belongsTo('App\Models\ClassModel', 'class_id', 'id');
    }

    public function session()
    {
        return $this->belongsTo('App\Models\SessionModel', 'session_id', 'id');
    }

    public function examSubject()
    {
        return $this->belongsTo('App\Models\ExamSubjectModel', 'exam_subject_id', 'id');
    }
}
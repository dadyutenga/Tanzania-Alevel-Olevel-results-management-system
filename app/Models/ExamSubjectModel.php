<?php

namespace App\Models;

class ExamSubjectModel extends BaseModel
{
    protected $table = 'tz_exam_subjects';
    protected $allowedFields = [
        'id',
        'exam_id',
        'subject_name',
        'max_marks',
        'passing_marks',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'school_id',
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'exam_id' => 'required|max_length[36]',
        'subject_name' => 'required|max_length[100]',
        'max_marks' => 'required|numeric',
        'passing_marks' => 'required|numeric',
    ];

    // Validation rules matching SQL constraints
   

    // Relationships based on SQL foreign keys
    public function exam()
    {
        return $this->belongsTo('App\Models\ExamModel', 'exam_id', 'id');
    }

    public function subjectMarks()
    {
        return $this->hasMany('App\Models\ExamSubjectMarkModel', 'exam_subject_id', 'id');
    }
}
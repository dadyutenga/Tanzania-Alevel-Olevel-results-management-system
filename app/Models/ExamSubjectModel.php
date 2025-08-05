<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamSubjectModel extends Model
{
    protected $table = 'tz_exam_subjects';
    protected $allowedFields = [
        'exam_id',
        'subject_name',
        'max_marks',
        'passing_marks'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

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
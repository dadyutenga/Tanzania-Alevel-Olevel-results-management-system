<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamSubjectModel extends Model
{
    protected $DBGroup = 'second_db';
    protected $table = 'tz_exam_subjects';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'exam_id',
        'subject_name',
        'max_marks',
        'passing_marks'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation rules matching SQL constraints
    protected $validationRules = [
        'exam_id' => 'required|numeric|is_not_unique[tz_exams.id]',
        'subject_name' => 'required|max_length[100]',
        'max_marks' => 'permit_empty|numeric',
        'passing_marks' => 'permit_empty|numeric'
    ];

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
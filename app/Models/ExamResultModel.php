<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamResultModel extends Model
{
    protected $table = 'tz_exam_results';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'student_id',
        'exam_id',
        'class_id',
        'session_id',
        'total_points',
        'division',
        'division_description'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation rules matching SQL constraints
    protected $validationRules = [
        'student_id' => 'required|numeric|is_not_unique[students.id]',
        'exam_id' => 'required|numeric|is_not_unique[tz_exams.id]',
        'class_id' => 'required|numeric|is_not_unique[classes.id]',
        'session_id' => 'required|numeric|is_not_unique[sessions.id]',
        'total_points' => 'permit_empty|numeric',
        'division' => 'permit_empty|max_length[5]',
        'division_description' => 'permit_empty|max_length[50]'
    ];

    // Relationships based on SQL foreign keys
    public function student()
    {
        return $this->belongsTo('App\Models\StudentModel', 'student_id', 'id');
    }

    public function exam()
    {
        return $this->belongsTo('App\Models\ExamModel', 'exam_id', 'id');
    }

    public function class()
    {
        return $this->belongsTo('App\Models\ClassModel', 'class_id', 'id');
    }

    public function session()
    {
        return $this->belongsTo('App\Models\SessionModel', 'session_id', 'id');
    }
} 
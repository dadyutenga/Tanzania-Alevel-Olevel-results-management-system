<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamSubjectMarkModel extends Model
{
    protected $DBGroup = 'second_db';
    protected $table = 'tz_exam_subject_marks';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'exam_id',
        'student_id',
        'class_id',
        'session_id',
        'exam_subject_id',
        'marks_obtained'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation rules matching SQL constraints
    protected $validationRules = [
        'exam_id' => 'required|numeric|is_not_unique[tz_exams.id]',
        'student_id' => 'required|numeric|is_not_unique[students.id]',
        'class_id' => 'required|numeric|is_not_unique[classes.id]',
        'session_id' => 'required|numeric|is_not_unique[sessions.id]',
        'exam_subject_id' => 'required|numeric|is_not_unique[tz_exam_subjects.id]',
        'marks_obtained' => 'permit_empty|numeric'
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
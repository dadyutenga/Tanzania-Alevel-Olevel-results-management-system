<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamModel extends Model
{
    protected $table = 'tz_exams';
    protected $allowedFields = [
        'exam_name',
        'exam_date',
        'session_id',
        'is_active'
    ];
    protected $returnType = 'array';
    protected $validationRules = [
        'exam_name' => 'required|max_length[100]',
        'exam_date' => 'required|valid_date',
        'session_id' => 'required|numeric|is_not_unique[sessions.id]',
        'is_active' => 'required|in_list[yes,no]'
    ];

    // Relationships based on SQL foreign keys
    public function session()
    {
        return $this->belongsTo('App\Models\SessionModel', 'session_id', 'id');
    }

    public function examClasses()
    {
        return $this->hasMany('App\Models\ExamClassModel', 'exam_id', 'id');
    }

    public function examSubjects()
    {
        return $this->hasMany('App\Models\ExamSubjectModel', 'exam_id', 'id');
    }

    public function examResults()
    {
        return $this->hasMany('App\Models\ExamResultModel', 'exam_id', 'id');
    }
}
<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamModel extends Model
{
    protected $DBGroup = 'second_db';
    protected $table = 'tz_exams';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'exam_name',
        'exam_date',
        'session_id',
        'is_active'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation rules matching SQL constraints
    protected $validationRules = [
        'exam_name' => 'required|max_length[100]',
        'exam_date' => 'permit_empty|valid_date',
        'session_id' => 'required|integer|is_not_unique[sessions.id]',
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
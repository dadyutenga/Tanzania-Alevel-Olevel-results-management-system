<?php

namespace App\Models;

use CodeIgniter\Model;

class AlevelSubjectMarksModel extends Model
{
    protected $DBGroup          = 'second_db';
    protected $table = 'tz_alevel_subject_marks';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;

    protected $returnType = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [
        'exam_id',
        'student_id',
        'class_id',
        'session_id',
        'combination_id',
        'subject_id',
        'marks_obtained'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = '';

    protected $validationRules = [
        'exam_id' => 'required|integer',
        'student_id' => 'required|integer',
        'class_id' => 'required|integer',
        'session_id' => 'required|integer',
        'combination_id' => 'required|integer',
        'subject_id' => 'required|integer',
        'marks_obtained' => 'permit_empty|integer'
    ];

    protected $validationMessages = [
        'exam_id' => [
            'required' => 'Exam ID is required.',
            'integer' => 'Exam ID must be an integer.'
        ],
        'student_id' => [
            'required' => 'Student ID is required.',
            'integer' => 'Student ID must be an integer.'
        ],
        'class_id' => [
            'required' => 'Class ID is required.',
            'integer' => 'Class ID must be an integer.'
        ],
        'session_id' => [
            'required' => 'Session ID is required.',
            'integer' => 'Session ID must be an integer.'
        ],
        'combination_id' => [
            'required' => 'Combination ID is required.',
            'integer' => 'Combination ID must be an integer.'
        ],
        'subject_id' => [
            'required' => 'Subject ID is required.',
            'integer' => 'Subject ID must be an integer.'
        ],
        'marks_obtained' => [
            'integer' => 'Marks obtained must be an integer.'
        ]
    ];

    protected $skipValidation = false;

    /**
     * Relationships with other tables
     */
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

    public function combination()
    {
        return $this->belongsTo('App\Models\AlevelCombinationModel', 'combination_id', 'id');
    }

    public function subject()
    {
        return $this->belongsTo('App\Models\AlevelCombinationSubjectModel', 'subject_id', 'id');
    }

    /**
     * Custom method to fetch marks with related data
     * 
     * @param array $conditions Conditions to filter the results
     * @return array
     */
    public function getMarksWithDetails(array $conditions = []): array
    {
        $this->select('tz_alevel_subject_marks.*, 
                       tz_exams.exam_name, 
                       tz_exams.exam_date, 
                       students.first_name, 
                       students.last_name, 
                       students.registration_number, 
                       classes.class, 
                       sessions.session, 
                       tz_alevel_combinations.combination_code, 
                       tz_alevel_combinations.combination_name, 
                       tz_alevel_combination_subjects.subject_name')
             ->join('tz_exams', 'tz_exams.id = tz_alevel_subject_marks.exam_id', 'left')
             ->join('students', 'students.id = tz_alevel_subject_marks.student_id', 'left')
             ->join('classes', 'classes.id = tz_alevel_subject_marks.class_id', 'left')
             ->join('sessions', 'sessions.id = tz_alevel_subject_marks.session_id', 'left')
             ->join('tz_alevel_combinations', 'tz_alevel_combinations.id = tz_alevel_subject_marks.combination_id', 'left')
             ->join('tz_alevel_combination_subjects', 'tz_alevel_combination_subjects.id = tz_alevel_subject_marks.subject_id', 'left');

        if (!empty($conditions)) {
            $this->where($conditions);
        }

        return $this->findAll();
    }

    /**
     * Custom method to check if a mark entry already exists
     * 
     * @param int $examId
     * @param int $studentId
     * @param int $subjectId
     * @return bool
     */
    public function markExists(int $examId, int $studentId, int $subjectId): bool
    {
        return $this->where([
            'exam_id' => $examId,
            'student_id' => $studentId,
            'subject_id' => $subjectId
        ])->countAllResults() > 0;
    }
}
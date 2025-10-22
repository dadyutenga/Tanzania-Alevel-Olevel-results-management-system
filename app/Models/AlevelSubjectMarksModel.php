<?php

namespace App\Models;

class AlevelSubjectMarksModel extends BaseModel
{
    protected $table = 'tz_alevel_subject_marks';

    protected $allowedFields = [
        'id',
        'exam_id',
        'student_id',
        'class_id',
        'session_id',
        'combination_id',
        'subject_id',
        'marks_obtained',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'school_id'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = '';

    protected $validationRules = [
        'exam_id' => 'required|max_length[36]',
        'student_id' => 'required|max_length[36]',
        'class_id' => 'required|max_length[36]',
        'session_id' => 'required|max_length[36]',
        'combination_id' => 'required|max_length[36]',
        'subject_id' => 'required|max_length[36]',
        'marks_obtained' => 'permit_empty|numeric'
    ];

    protected $validationMessages = [
        'exam_id' => [
            'required' => 'Exam ID is required.',
            'max_length' => 'Exam ID must be a valid identifier.'
        ],
        'student_id' => [
            'required' => 'Student ID is required.',
            'max_length' => 'Student ID must be a valid identifier.'
        ],
        'class_id' => [
            'required' => 'Class ID is required.',
            'max_length' => 'Class ID must be a valid identifier.'
        ],
        'session_id' => [
            'required' => 'Session ID is required.',
            'max_length' => 'Session ID must be a valid identifier.'
        ],
        'combination_id' => [
            'required' => 'Combination ID is required.',
            'max_length' => 'Combination ID must be a valid identifier.'
        ],
        'subject_id' => [
            'required' => 'Subject ID is required.',
            'max_length' => 'Subject ID must be a valid identifier.'
        ],
        'marks_obtained' => [
            'numeric' => 'Marks obtained must be numeric.'
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
     * @param string $examId
     * @param string $studentId
     * @param string $subjectId
     * @return bool
     */
    public function markExists(string $examId, string $studentId, string $subjectId): bool
    {
        return $this->where([
            'exam_id' => $examId,
            'student_id' => $studentId,
            'subject_id' => $subjectId
        ])->countAllResults() > 0;
    }
}
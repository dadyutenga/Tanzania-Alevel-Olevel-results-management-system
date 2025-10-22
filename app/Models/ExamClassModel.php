<?php

namespace App\Models;

class ExamClassModel extends BaseModel
{
    protected $table = 'tz_exam_classes';
    protected $allowedFields = [
        'id',
        'exam_id',
        'class_id',
        'session_id',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'school_id',
    ];

    // Specify the return type for all queries
    protected $returnType = 'array';

    // Enable automatic timestamps
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Validation rules
    protected $validationRules = [
        'exam_id' => 'required|max_length[36]',
        'class_id' => 'required|max_length[36]',
        'session_id' => 'required|max_length[36]'
    ];
    
    // Validation messages
    protected $validationMessages = [
        'exam_id' => [
            'required' => 'Exam ID is required',
            'max_length' => 'Exam ID must be a valid identifier'
        ],
        'class_id' => [
            'required' => 'Class ID is required',
            'max_length' => 'Class ID must be a valid identifier'
        ],
        'session_id' => [
            'required' => 'Session ID is required',
            'max_length' => 'Session ID must be a valid identifier'
        ]
    ];

    /**
     * Get exam classes with related exam, class, and session information
     *
     * @param array|null $where Optional where conditions
     * @return array
     */
    public function getExamClassesWithDetails(?array $where = null)
    {
        $builder = $this->db->table($this->table)
            ->select('tz_exam_classes.*, tz_exams.exam_name, classes.class as class_name, sessions.session as session_name')
            ->join('tz_exams', 'tz_exams.id = tz_exam_classes.exam_id')
            ->join('classes', 'classes.id = tz_exam_classes.class_id')
            ->join('sessions', 'sessions.id = tz_exam_classes.session_id');
            
        if ($where) {
            $builder->where($where);
        }
        
        return $builder->get()->getResultArray();
    }

    /**
     * Get classes assigned to a specific exam
     *
     * @param string $examId
     * @return array
     */
    public function getClassesByExamId(string $examId)
    {
        return $this->where('exam_id', $examId)
                    ->findAll();
    }

    /**
     * Get exams assigned to a specific class
     *
     * @param string $classId
     * @return array
     */
    public function getExamsByClassId(string $classId)
    {
        return $this->where('class_id', $classId)
                    ->findAll();
    }

    /**
     * Check if an exam is assigned to a class
     *
     * @param string $examId
     * @param string $classId
     * @return bool
     */
    public function isExamAssignedToClass(string $examId, string $classId): bool
    {
        return $this->where([
            'exam_id' => $examId,
            'class_id' => $classId
        ])->countAllResults() > 0;
    }

    /**
     * Assign multiple classes to an exam
     *
     * @param string $examId
     * @param array $classIds
     * @param string $sessionId
     * @return bool
     */
    public function assignClassesToExam(string $examId, array $classIds, string $sessionId): bool
    {
        $data = [];
        foreach ($classIds as $classId) {
            $data[] = [
                'exam_id' => $examId,
                'class_id' => $classId,
                'session_id' => $sessionId
            ];
        }
        
        return $this->insertBatch($data);
    }
}
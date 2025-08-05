<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamClassModel extends Model
{
    protected $table = 'tz_exam_classes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['exam_id', 'class_id', 'session_id'];   
    
    // Specify the return type for all queries
    protected $returnType = 'array';
    
    // Enable automatic timestamps
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Validation rules
    protected $validationRules = [
        'exam_id' => 'required|numeric|is_natural_no_zero',
        'class_id' => 'required|numeric|is_natural_no_zero',
        'session_id' => 'required|numeric|is_natural_no_zero'
    ];
    
    // Validation messages
    protected $validationMessages = [
        'exam_id' => [
            'required' => 'Exam ID is required',
            'numeric' => 'Exam ID must be numeric',
            'is_natural_no_zero' => 'Invalid Exam ID'
        ],
        'class_id' => [
            'required' => 'Class ID is required',
            'numeric' => 'Class ID must be numeric',
            'is_natural_no_zero' => 'Invalid Class ID'
        ],
        'session_id' => [
            'required' => 'Session ID is required',
            'numeric' => 'Session ID must be numeric',
            'is_natural_no_zero' => 'Invalid Session ID'
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
     * @param int $examId
     * @return array
     */
    public function getClassesByExamId(int $examId)
    {
        return $this->where('exam_id', $examId)
                    ->findAll();
    }

    /**
     * Get exams assigned to a specific class
     *
     * @param int $classId
     * @return array
     */
    public function getExamsByClassId(int $classId)
    {
        return $this->where('class_id', $classId)
                    ->findAll();
    }

    /**
     * Check if an exam is assigned to a class
     *
     * @param int $examId
     * @param int $classId
     * @return bool
     */
    public function isExamAssignedToClass(int $examId, int $classId): bool
    {
        return $this->where([
            'exam_id' => $examId,
            'class_id' => $classId
        ])->countAllResults() > 0;
    }

    /**
     * Assign multiple classes to an exam
     *
     * @param int $examId
     * @param array $classIds
     * @param int $sessionId
     * @return bool
     */
    public function assignClassesToExam(int $examId, array $classIds, int $sessionId): bool
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
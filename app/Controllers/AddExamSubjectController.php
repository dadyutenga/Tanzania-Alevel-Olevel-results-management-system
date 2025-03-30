<?php

namespace App\Controllers;

use App\Models\ExamModel;
use App\Models\ExamSubjectModel;
use CodeIgniter\RESTful\ResourceController;

class AddExamSubjectController extends ResourceController
{
    protected $format = 'json';
    protected $examModel;
    protected $examSubjectModel;

    public function __construct()
    {
        $this->examModel = new ExamModel();
        $this->examSubjectModel = new ExamSubjectModel();
    }

    public function index($examId = null)
    {
        try {
            // If no exam ID, show exam selection page
            if (!$examId) {
                $exams = $this->examModel
                    ->where('is_active', 'yes')
                    ->orderBy('exam_date', 'DESC')
                    ->findAll();
                    
                $data = [
                    'exams' => $exams,
                    'exam' => null,  // Add this to prevent undefined variable
                    'existingSubjects' => []  // Add empty array for consistency
                ];
                return view('exam/AddExamSubject', $data);
            }

            // Get exam details
            $exam = $this->examModel->find($examId);
            if (!$exam) {
                return redirect()->to('exam')->with('error', 'Exam not found');
            }

            // Get existing subjects for this exam
            $existingSubjects = $this->examSubjectModel->where('exam_id', $examId)->findAll();

            $data = [
                'exam' => $exam,
                'existingSubjects' => $existingSubjects,
                'exams' => []  // Add empty array for consistency
            ];

            return view('exam/AddExamSubject', $data);
        } catch (\Exception $e) {
            log_message('error', '[AddExamSubject.index] Exception: {message}', ['message' => $e->getMessage()]);
            return redirect()->to('exam')->with('error', 'Failed to load exam subject form');
        }
    }

    // Get active exams for selection
    public function getActiveExams()
    {
        try {
            $exams = $this->examModel
                ->where('is_active', 'yes')
                ->orderBy('exam_date', 'DESC')
                ->findAll();

            return $this->respond([
                'status' => 'success',
                'data' => $exams
            ]);
        } catch (\Exception $e) {
            log_message('error', '[getActiveExams] Exception: {message}', ['message' => $e->getMessage()]);
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch active exams'
            ], 500);
        }
    }

    // Get exam details with its subjects
    public function getExamDetails($examId)
    {
        try {
            $exam = $this->examModel->find($examId);
            if (!$exam) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Exam not found'
                ], 404);
            }

            $subjects = $this->examSubjectModel
                ->where('exam_id', $examId)
                ->findAll();

            return $this->respond([
                'status' => 'success',
                'data' => [
                    'exam' => $exam,
                    'subjects' => $subjects
                ]
            ]);
        } catch (\Exception $e) {
            log_message('error', '[getExamDetails] Exception: {message}', ['message' => $e->getMessage()]);
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch exam details'
            ], 500);
        }
    }

    public function getExamSubjects($examId)
    {
        try {
            log_message('info', 'Fetching subjects for exam ID: {examId}', ['examId' => $examId]);

            $subjects = $this->examSubjectModel
                ->where('exam_id', $examId)
                ->findAll();

            return $this->respond([
                'status' => 'success',
                'data' => $subjects
            ]);
        } catch (\Exception $e) {
            log_message('error', '[getExamSubjects] Exception: {message}', ['message' => $e->getMessage()]);
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to fetch exam subjects'
            ], 500);
        }
    }

    // Store multiple subjects at once
    public function storeBatch()
    {
        try {
            $examId = $this->request->getPost('exam_id');
            $subjects = $this->request->getPost('subjects'); // Array of subjects

            // Add debug logging
            log_message('debug', 'Received exam_id: {exam_id}', ['exam_id' => $examId]);
            log_message('debug', 'Received subjects: {subjects}', ['subjects' => json_encode($subjects)]);

            if (!$examId || !is_array($subjects)) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Invalid input data'
                ], 400);
            }

            // Validate exam exists
            $exam = $this->examModel->find($examId);
            if (!$exam) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Invalid exam selected'
                ], 400);
            }

            $insertData = [];
            $errors = [];

            foreach ($subjects as $subject) {
                // Add debug logging for each subject
                log_message('debug', 'Processing subject: {subject}', ['subject' => json_encode($subject)]);

                // Validate each subject
                if (!$this->validateSubject($subject)) {
                    $errors[] = "Invalid data for subject: {$subject['subject_name']}";
                    continue;
                }

                $insertData[] = [
                    'exam_id' => $examId,
                    'subject_name' => $subject['subject_name'],
                    'max_marks' => $subject['max_marks'],
                    'passing_marks' => $subject['passing_marks']
                ];
            }

            if (!empty($insertData)) {
                // Add debug logging before insert
                log_message('debug', 'Attempting to insert data: {data}', ['data' => json_encode($insertData)]);
                
                $inserted = $this->examSubjectModel->insertBatch($insertData);
                
                // Add debug logging after insert
                log_message('debug', 'Insert result: {result}', ['result' => $inserted]);
            }

            return $this->respond([
                'status' => 'success',
                'message' => 'Subjects added successfully',
                'errors' => $errors,
                'added_count' => count($insertData),
                'debug_data' => [
                    'insert_data' => $insertData,
                    'exam_id' => $examId
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', '[AddExamSubject.storeBatch] Exception: {message}', ['message' => $e->getMessage()]);
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to add subjects: ' . $e->getMessage()
            ], 500);
        }
    }

    // Single subject validation
    private function validateSubject($subject): bool
    {
        $rules = [
            'subject_name' => 'required|max_length[100]',
            'max_marks' => 'required|numeric|greater_than[0]',
            'passing_marks' => 'required|numeric|greater_than[0]|less_than_equal_to[' . ($subject['max_marks'] ?? 0) . ']'
        ];

        // Use the second_db group for validation
        $db = \Config\Database::connect('second_db');
        $validation = \Config\Services::validation();
        $validation->setRules($rules);
        
        return $validation->run((array)$subject);
    }

    public function store()
    {
        try {
            $rules = [
                'exam_id' => 'required|numeric|is_not_unique[second_db.tz_exams.id]',
                'subject_name' => 'required|max_length[100]',
                'max_marks' => 'required|numeric|greater_than[0]',
                'passing_marks' => 'required|numeric|greater_than[0]'
            ];

            if (!$this->validate($rules)) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $this->validator->getErrors()
                ], 400);
            }

            // Check if exam exists
            $examId = $this->request->getPost('exam_id');
            $exam = $this->examModel->find($examId);
            if (!$exam) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Invalid exam selected'
                ], 400);
            }

            // Check if subject already exists for this exam
            $subjectExists = $this->examSubjectModel->where([
                'exam_id' => $examId,
                'subject_name' => $this->request->getPost('subject_name')
            ])->first();

            if ($subjectExists) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Subject already exists for this exam'
                ], 400);
            }

            $data = [
                'exam_id' => $examId,
                'subject_name' => $this->request->getPost('subject_name'),
                'max_marks' => $this->request->getPost('max_marks'),
                'passing_marks' => $this->request->getPost('passing_marks')
            ];

            $subjectId = $this->examSubjectModel->insert($data);

            if (!$subjectId) {
                throw new \RuntimeException('Failed to create exam subject record');
            }

            // Get the created subject
            $createdSubject = $this->examSubjectModel->find($subjectId);

            return $this->respond([
                'status' => 'success',
                'message' => 'Exam subject created successfully',
                'data' => $createdSubject
            ]);

        } catch (\Exception $e) {
            log_message('error', '[AddExamSubject.store] Exception: {message}', ['message' => $e->getMessage()]);
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to create exam subject'
            ], 500);
        }
    }

    public function update($id = null)
    {
        try {
            $rules = [
                'subject_name' => 'required|max_length[100]',
            'max_marks' => 'required|numeric|greater_than[0]',
            'passing_marks' => 'required|numeric|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return $this->respond([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $this->validator->getErrors()
            ], 400);
        }

        $data = [
            'subject_name' => $this->request->getPost('subject_name'),
            'max_marks' => $this->request->getPost('max_marks'),
            'passing_marks' => $this->request->getPost('passing_marks')
        ];

        $updated = $this->examSubjectModel->update($id, $data);

        return $this->respond([
            'status' => 'success',
            'message' => 'Subject updated successfully'
        ]);

    } catch (\Exception $e) {
        log_message('error', '[AddExamSubject.update] Exception: {message}', ['message' => $e->getMessage()]);
        return $this->respond([
            'status' => 'error',
            'message' => 'Failed to update subject'
        ], 500);
    }
    }

    public function delete($id = null)
    {
        try {
            if (!$id) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Subject ID is required'
                ], 400);
            }

            if (!$this->examSubjectModel->delete($id)) {
                throw new \RuntimeException('Failed to delete exam subject');
            }

            return $this->respond([
                'status' => 'success',
                'message' => 'Exam subject deleted successfully'
            ]);

        } catch (\Exception $e) {
            log_message('error', '[AddExamSubject.delete] Exception: {message}', ['message' => $e->getMessage()]);
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to delete exam subject'
            ], 500);
        }
    }
}

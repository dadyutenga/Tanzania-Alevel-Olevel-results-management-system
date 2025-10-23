<?php

namespace App\Controllers;

use App\Models\ExamModel;
use App\Models\ExamSubjectModel;
use App\Models\SettingsModel;
use CodeIgniter\RESTful\ResourceController;

class AddExamSubjectController extends ResourceController
{
    protected $format = 'json';
    protected $examModel;
    protected $examSubjectModel;
    protected $settingsModel;

    public function __construct()
    {
        $this->examModel = new ExamModel();
        $this->examSubjectModel = new ExamSubjectModel();
        $this->settingsModel = new SettingsModel();
    }

    public function index($examId = null)
    {
        try {
            // Initialize with empty arrays
            $data = [
                'exams' => [],
                'exam' => null,
                'existingSubjects' => []
            ];

            // Get active exams with proper error handling
            $exams = $this->examModel->select('id, exam_name, exam_date')
                ->where('is_active', 'yes')
                ->orderBy('exam_date', 'DESC')
                ->findAll();
            
            if ($exams === null) {
                throw new \RuntimeException('Failed to fetch exams');
            }
            
            $data['exams'] = $exams;

            // Handle exam ID if provided
            if (!empty($examId)) {
                $exam = $this->examModel->find($examId);
                if ($exam === null) {
                    return redirect()->to(base_url('exam/subjects'))
                        ->with('error', 'Invalid exam selected');
                }
                
                $data['exam'] = $exam;
                
                // Fetch subjects with proper error handling
                $subjects = $this->examSubjectModel
                    ->where('exam_id', $examId)
                    ->findAll();
                
                $data['existingSubjects'] = $subjects ?? [];
            }

            // Add debug information
            log_message('debug', 'Data being passed to view: ' . json_encode($data));

            return view('exam/AddExamSubject', $data);

        } catch (\Exception $e) {
            log_message('error', '[AddExamSubject.index] Exception: {message}', ['message' => $e->getMessage()]);
            return redirect()->to(base_url('dashboard'))
                ->with('error', 'Failed to load exam subject form: ' . $e->getMessage());
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
            $subjectsJson = $this->request->getPost('subjects');
            
            // Debug logging
            log_message('debug', 'Raw subjects data: ' . $subjectsJson);
            
            // Decode JSON string to array
            $subjects = json_decode($subjectsJson, true);
            
            // Validate input
            if (!$examId || !is_array($subjects)) {
                log_message('error', 'Invalid input - examId: ' . $examId . ', subjects: ' . gettype($subjects));
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Invalid input data',
                    'debug' => [
                        'exam_id' => $examId,
                        'subjects_type' => gettype($subjects),
                        'raw_subjects' => $subjectsJson
                    ]
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

            // Debug and fix: Check session data
            $session = service('session');
            $userId = $session->get('user_uuid') ?? $session->get('user_id');
            $schoolId = $session->get('school_id');
            
            // If school_id is missing from session, try to get it from settings
            if (!$schoolId && $userId) {
                $school = $this->settingsModel->getSchoolByUserId($userId);
                if ($school) {
                    $schoolId = $school['id'];
                    // Update session with school_id
                    $session->set('school_id', $schoolId);
                    log_message('info', '[storeBatch] Fixed missing school_id in session: ' . $schoolId);
                }
            }
            
            $sessionData = [
                'user_id' => $session->get('user_id'),
                'user_uuid' => $session->get('user_uuid'),
                'school_id' => $schoolId,
                'role' => $session->get('role')
            ];
            log_message('debug', '[storeBatch] Session data: ' . json_encode($sessionData));

            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            foreach ($subjects as $index => $subject) {
                try {
                    // Add debug logging for each subject
                    log_message('debug', 'Processing subject: ' . json_encode($subject));

                    // Validate each subject
                    if (!$this->validateSubject($subject)) {
                        $errorCount++;
                        $errors[] = "Row " . ($index + 1) . ": Invalid data for subject: {$subject['subject_name']}";
                        continue;
                    }

                    // Prepare data - let BaseModel handle school_id, created_by, updated_by
                    $subjectData = [
                        'exam_id' => $examId,
                        'subject_name' => trim($subject['subject_name']),
                        'max_marks' => (int)$subject['max_marks'],
                        'passing_marks' => (int)$subject['passing_marks']
                    ];

                    // Use individual insert so BaseModel beforeInsert hook works
                    $subjectId = $this->examSubjectModel->insert($subjectData);

                    if (!$subjectId) {
                        $errorCount++;
                        $errors[] = "Row " . ($index + 1) . ": Failed to insert subject";
                        continue;
                    }

                    $successCount++;
                } catch (\Exception $e) {
                    $errorCount++;
                    $errors[] = "Row " . ($index + 1) . ": " . $e->getMessage();
                    log_message('error', 'Subject insert error: ' . $e->getMessage());
                }
            }

            return $this->respond([
                'status' => 'success',
                'message' => "Added $successCount subjects successfully" . ($errorCount > 0 ? ", $errorCount failed" : ""),
                'successCount' => $successCount,
                'errorCount' => $errorCount,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            log_message('error', '[AddExamSubject.storeBatch] Exception: ' . $e->getMessage());
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

        // Use the default group for validation
        $db = \Config\Database::connect('default');
        $validation = \Config\Services::validation();
        $validation->setRules($rules);
        
        return $validation->run((array)$subject);
    }

    public function store()
    {
        try {
            $rules = [
                'exam_id' => 'required|string|min_length[36]|max_length[36]',
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

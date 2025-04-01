<?php

namespace App\Controllers;

use App\Models\ExamModel;
use App\Models\ExamClassModel;
use App\Models\StudentSessionModel;
use App\Models\ExamSubjectMarkModel;
use App\Models\ExamSubjectModel;
use App\Models\SessionModel;
use CodeIgniter\RESTful\ResourceController;

class ViewExamMarksController extends ResourceController
{
    protected $examModel;
    protected $examClassModel;
    protected $studentSessionModel;
    protected $examSubjectMarkModel;
    protected $examSubjectModel;
    protected $sessionModel;
    protected $format = 'json';

    public function __construct()
    {
        $this->examModel = new ExamModel();
        $this->examClassModel = new ExamClassModel();
        $this->studentSessionModel = new StudentSessionModel();
        $this->examSubjectMarkModel = new ExamSubjectMarkModel();
        $this->examSubjectModel = new ExamSubjectModel();
        $this->sessionModel = new SessionModel();
    }

    public function index()
    {
        try {
            $data = [
                'sessions' => $this->sessionModel->where('is_active', 'no')->findAll(),
                'exams' => [],
                'classes' => [],
                'students' => [],
                'subjects' => []
            ];

            $currentSession = $this->sessionModel->getCurrentSession();
            if ($currentSession) {
                $data['current_session'] = $currentSession;
                $data['exams'] = $this->examModel
                    ->where('session_id', $currentSession['id'])
                    ->findAll();
            }

            return view('exam/ViewExamMarks', $data);
        } catch (\Exception $e) {
            log_message('error', '[ViewExamMarks.index] Exception: ' . $e->getMessage());
            return redirect()->to(base_url('dashboard'))
                ->with('error', 'Failed to load exam marks page: ' . $e->getMessage());
        }
    }

    public function getStudentMarks()
    {
        try {
            $examId = $this->request->getGet('exam_id');
            $classId = $this->request->getGet('class_id');
            $sessionId = $this->request->getGet('session_id');
            $studentId = $this->request->getGet('student_id');

            if (!$examId || !$classId || !$sessionId) {
                throw new \Exception('Missing required parameters');
            }

            $query = $this->examSubjectMarkModel
                ->select('
                    tz_exam_subject_marks.*,
                    tz_exam_subjects.subject_name,
                    tz_exam_subjects.max_marks,
                    tz_exam_subjects.passing_marks,
                    students.firstname,
                    students.lastname,
                    students.roll_no
                ')
                ->join('tz_exam_subjects', 'tz_exam_subjects.id = tz_exam_subject_marks.exam_subject_id')
                ->join('students', 'students.id = tz_exam_subject_marks.student_id')
                ->where([
                    'tz_exam_subject_marks.exam_id' => $examId,
                    'tz_exam_subject_marks.class_id' => $classId,
                    'tz_exam_subject_marks.session_id' => $sessionId
                ]);

            if ($studentId) {
                $query->where('tz_exam_subject_marks.student_id', $studentId);
            }

            $marks = $query->findAll();

            return $this->respond([
                'status' => 'success',
                'data' => $marks
            ]);
        } catch (\Exception $e) {
            log_message('error', '[ViewExamMarks.getStudentMarks] Error: ' . $e->getMessage());
            return $this->respond([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function update($id = null)
    {
        try {
            if (!$id) {
                throw new \Exception('Mark ID is required');
            }

            $rules = [
                'marks_obtained' => 'required|numeric|greater_than_equal_to[0]'
            ];

            if (!$this->validate($rules)) {
                return $this->respond([
                    'status' => 'error',
                    'message' => $this->validator->getErrors()
                ], 400);
            }

            $marksObtained = $this->request->getPost('marks_obtained');
            
            // Get the exam subject to validate against max marks
            $mark = $this->examSubjectMarkModel->find($id);
            $subject = $this->examSubjectModel->find($mark['exam_subject_id']);

            if ($marksObtained > $subject['max_marks']) {
                return $this->respond([
                    'status' => 'error',
                    'message' => 'Marks obtained cannot be greater than maximum marks'
                ], 400);
            }

            $this->examSubjectMarkModel->update($id, [
                'marks_obtained' => $marksObtained
            ]);

            return $this->respond([
                'status' => 'success',
                'message' => 'Marks updated successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', '[ViewExamMarks.update] Error: ' . $e->getMessage());
            return $this->respond([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id = null)
    {
        try {
            if (!$id) {
                throw new \Exception('Mark ID is required');
            }

            if (!$this->examSubjectMarkModel->delete($id)) {
                throw new \Exception('Failed to delete marks');
            }

            return $this->respond([
                'status' => 'success',
                'message' => 'Marks deleted successfully'
            ]);
        } catch (\Exception $e) {
            log_message('error', '[ViewExamMarks.delete] Error: ' . $e->getMessage());
            return $this->respond([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getExamClasses()
    {
        try {
            $examId = $this->request->getGet('exam_id');
            $sessionId = $this->request->getGet('session_id');

            if (!$examId || !$sessionId) {
                throw new \Exception('Exam ID and Session ID are required');
            }

            $classes = $this->examClassModel->getExamClassesWithDetails([
                'tz_exam_classes.exam_id' => $examId,
                'tz_exam_classes.session_id' => $sessionId
            ]);

            return $this->respond([
                'status' => 'success',
                'data' => $classes
            ]);
        } catch (\Exception $e) {
            log_message('error', '[ViewExamMarks.getExamClasses] Error: ' . $e->getMessage());
            return $this->respond([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getExams()
    {
        try {
            $sessionId = $this->request->getGet('session_id');

            if (!$sessionId) {
                throw new \Exception('Session ID is required');
            }

            $exams = $this->examModel
                ->where('session_id', $sessionId)
                ->findAll();

            return $this->respond([
                'status' => 'success',
                'data' => $exams
            ]);
        } catch (\Exception $e) {
            log_message('error', '[ViewExamMarks.getExams] Error: ' . $e->getMessage());
            return $this->respond([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
<?php

namespace App\Controllers\Alevel;

use App\Controllers\BaseController;
use App\Models\AlevelCombinationModel;
use App\Models\AlevelCombinationSubjectModel;
use App\Models\StudentModel;
use App\Models\AlevelSubjectMarksModel;
use App\Models\ExamModel;
use App\Models\SessionModel;
use CodeIgniter\API\ResponseTrait;

class ViewAlevelMarksController extends BaseController
{
    use ResponseTrait;

    protected $alevelMarksModel;
    protected $alevelCombinationModel;
    protected $alevelCombinationSubjectModel;
    protected $studentModel;
    protected $examModel;
    protected $sessionModel;

    public function __construct()
    {
        log_message('debug', '[ViewAlevelMarksController.__construct] Controller initialized');
        $this->alevelMarksModel = new AlevelSubjectMarksModel();
        $this->alevelCombinationModel = new AlevelCombinationModel();
        $this->alevelCombinationSubjectModel = new AlevelCombinationSubjectModel();
        $this->studentModel = new StudentModel();
        $this->examModel = new ExamModel();
        $this->sessionModel = new SessionModel();
        log_message('debug', '[ViewAlevelMarksController.__construct] Models loaded successfully');
    }

    public function index()
    {
        log_message('debug', '[ViewAlevelMarksController.index] Method called');
        try {
            log_message('debug', '[ViewAlevelMarksController.index] Fetching initial data');
            $data = [
                'combinations' => $this->alevelCombinationModel->where('is_active', 'yes')->findAll(),
                'sessions' => $this->sessionModel->where('is_active', 'no')->findAll(),
                'exams' => [],
                'classes' => [],
                'marks' => [],
                'students' => [],
                'subjects' => [],
            ];
            log_message('debug', '[ViewAlevelMarksController.index] Initial data fetched: ' . json_encode(array_keys($data)));

            $currentSession = $this->sessionModel->getCurrentSession();
            if ($currentSession) {
                log_message('debug', '[ViewAlevelMarksController.index] Current session found: ' . json_encode($currentSession));
                $data['current_session'] = $currentSession;
                $data['exams'] = $this->examModel
                    ->where('session_id', $currentSession['id'])
                    ->where('is_active', 'yes')
                    ->findAll();
                log_message('debug', '[ViewAlevelMarksController.index] Exams fetched for session ' . $currentSession['id'] . ': ' . count($data['exams']) . ' exams');
                
                $db = \Config\Database::connect('second_db');
                $data['classes'] = $db->table('classes c')
                    ->select('c.id, c.class')
                    ->join('tz_student_alevel_combinations sac', 'c.id = sac.class_id')
                    ->where([
                        'sac.session_id' => $currentSession['id'],
                        'sac.is_active' => 'yes',
                        'c.is_active' => 'no'
                    ])
                    ->groupBy('c.id')
                    ->get()
                    ->getResultArray();
                log_message('debug', '[ViewAlevelMarksController.index] Classes fetched for session ' . $currentSession['id'] . ': ' . count($data['classes']) . ' classes');
            } else {
                log_message('debug', '[ViewAlevelMarksController.index] No current session found');
            }

            // Handle filter form submission
            if ($this->request->getMethod() === 'post') {
                log_message('debug', '[ViewAlevelMarksController.index] Form submission detected');
                $sessionId = $this->request->getPost('session_id');
                $examId = $this->request->getPost('exam_id');
                $classId = $this->request->getPost('class_id');
                $combinationId = $this->request->getPost('combination_id');
                log_message('debug', '[ViewAlevelMarksController.index] Form data - Session: ' . $sessionId . ', Exam: ' . $examId . ', Class: ' . $classId . ', Combination: ' . $combinationId);

                if ($sessionId && $examId && $classId && $combinationId) {
                    // Fetch marks based on filters
                    log_message('debug', '[ViewAlevelMarksController.index] Fetching marks with filters');
                    $data['marks'] = $this->alevelMarksModel
                        ->select('alevel_subject_marks.*, students.firstname, students.lastname, students.roll_no, tz_alevel_combination_subjects.subject_name')
                        ->join('students', 'students.id = alevel_subject_marks.student_id')
                        ->join('second_db.tz_alevel_combination_subjects', 'tz_alevel_combination_subjects.id = alevel_subject_marks.subject_id')
                        ->where([
                            'alevel_subject_marks.session_id' => $sessionId,
                            'alevel_subject_marks.exam_id' => $examId,
                            'alevel_subject_marks.class_id' => $classId,
                            'alevel_subject_marks.combination_id' => $combinationId,
                            'alevel_subject_marks.is_active' => 'yes'
                        ])
                        ->orderBy('students.firstname', 'ASC')
                        ->findAll();
                    log_message('debug', '[ViewAlevelMarksController.index] Marks fetched: ' . count($data['marks']) . ' records');

                    // Fetch subjects for the selected combination
                    $db = \Config\Database::connect('second_db');
                    $data['subjects'] = $db->table('tz_alevel_combination_subjects')
                        ->select('id, subject_name')
                        ->where([
                            'combination_id' => $combinationId,
                            'is_active' => 'yes'
                        ])
                        ->get()
                        ->getResultArray();
                    log_message('debug', '[ViewAlevelMarksController.index] Subjects fetched for combination ' . $combinationId . ': ' . count($data['subjects']) . ' subjects');

                    // Store filter values for display
                    $data['selected_filters'] = [
                        'session_id' => $sessionId,
                        'exam_id' => $examId,
                        'class_id' => $classId,
                        'combination_id' => $combinationId
                    ];
                    log_message('debug', '[ViewAlevelMarksController.index] Filter values stored for display');
                } else {
                    log_message('warning', '[ViewAlevelMarksController.index] Missing required filters for marks retrieval');
                    session()->setFlashdata('error', 'Please select all filters to view marks.');
                }
            } else {
                log_message('debug', '[ViewAlevelMarksController.index] No form submission, loading page without filtered marks');
            }

            log_message('debug', '[ViewAlevelMarksController.index] Rendering view alevel/ViewAlevelExamMarks');
            return view('alevel/ViewAlevelExamMarks', $data);
        } catch (\Exception $e) {
            log_message('error', '[ViewAlevelMarksController.index] Error: ' . $e->getMessage() . ' at line ' . $e->getLine());
            return redirect()->back()->with('error', 'Failed to load marks view page: ' . $e->getMessage());
        }
    }

    public function update()
    {
        log_message('debug', '[ViewAlevelMarksController.update] Method called');
        try {
            if ($this->request->getMethod() !== 'post') {
                log_message('error', '[ViewAlevelMarksController.update] Invalid request method: ' . $this->request->getMethod());
                throw new \Exception('Invalid request method for updating marks.');
            }

            $markId = $this->request->getPost('mark_id');
            $marksObtained = $this->request->getPost('marks_obtained');
            log_message('debug', '[ViewAlevelMarksController.update] Received data - Mark ID: ' . $markId . ', Marks Obtained: ' . $marksObtained);

            if (!$markId || !is_numeric($marksObtained) || $marksObtained < 0 || $marksObtained > 100) {
                log_message('error', '[ViewAlevelMarksController.update] Invalid data - Mark ID: ' . $markId . ', Marks Obtained: ' . $marksObtained);
                throw new \Exception('Invalid mark data provided for update.');
            }

            $updateData = [
                'marks_obtained' => (int)$marksObtained,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            log_message('debug', '[ViewAlevelMarksController.update] Update data prepared: ' . json_encode($updateData));

            if (!$this->alevelMarksModel->update($markId, $updateData)) {
                $errors = implode(', ', $this->alevelMarksModel->errors());
                log_message('error', '[ViewAlevelMarksController.update] Update failed for Mark ID ' . $markId . ': ' . $errors);
                throw new \Exception('Failed to update marks: ' . $errors);
            }

            log_message('debug', '[ViewAlevelMarksController.update] Marks updated successfully for Mark ID ' . $markId);
            session()->setFlashdata('message', 'Marks updated successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            log_message('error', '[ViewAlevelMarksController.update] Error: ' . $e->getMessage() . ' at line ' . $e->getLine());
            session()->setFlashdata('error', 'Failed to update marks: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function delete()
    {
        log_message('debug', '[ViewAlevelMarksController.delete] Method called');
        try {
            if ($this->request->getMethod() !== 'post') {
                log_message('error', '[ViewAlevelMarksController.delete] Invalid request method: ' . $this->request->getMethod());
                throw new \Exception('Invalid request method for deleting marks.');
            }

            $markIds = $this->request->getPost('mark_ids');
            $deleteAll = $this->request->getPost('delete_all');
            $sessionId = $this->request->getPost('session_id');
            $examId = $this->request->getPost('exam_id');
            $classId = $this->request->getPost('class_id');
            $combinationId = $this->request->getPost('combination_id');
            log_message('debug', '[ViewAlevelMarksController.delete] Received data - Delete All: ' . $deleteAll . ', Mark IDs: ' . json_encode($markIds) . ', Filters: Session=' . $sessionId . ', Exam=' . $examId . ', Class=' . $classId . ', Combination=' . $combinationId);

            if ($deleteAll === 'yes' && $sessionId && $examId && $classId && $combinationId) {
                // Delete all marks based on filters
                log_message('debug', '[ViewAlevelMarksController.delete] Deleting all marks based on filters');
                $this->alevelMarksModel->where([
                    'session_id' => $sessionId,
                    'exam_id' => $examId,
                    'class_id' => $classId,
                    'combination_id' => $combinationId
                ])->delete();
                log_message('debug', '[ViewAlevelMarksController.delete] All marks deleted for filters');
                session()->setFlashdata('message', 'All marks for the selected filters have been deleted successfully.');
            } elseif (is_array($markIds) && !empty($markIds)) {
                // Delete specific marks
                log_message('debug', '[ViewAlevelMarksController.delete] Deleting specific marks: ' . json_encode($markIds));
                $this->alevelMarksModel->whereIn('id', $markIds)->delete();
                log_message('debug', '[ViewAlevelMarksController.delete] Specific marks deleted');
                session()->setFlashdata('message', 'Selected marks have been deleted successfully.');
            } else {
                log_message('error', '[ViewAlevelMarksController.delete] No marks selected or invalid filters');
                throw new \Exception('No marks selected for deletion or invalid filters.');
            }

            log_message('debug', '[ViewAlevelMarksController.delete] Redirecting back after deletion');
            return redirect()->back();
        } catch (\Exception $e) {
            log_message('error', '[ViewAlevelMarksController.delete] Error: ' . $e->getMessage() . ' at line ' . $e->getLine());
            session()->setFlashdata('error', 'Failed to delete marks: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function getExams($sessionId)
    {
        log_message('debug', '[ViewAlevelMarksController.getExams] Method called with Session ID: ' . $sessionId);
        try {
            if (!$sessionId) {
                log_message('error', '[ViewAlevelMarksController.getExams] Session ID is required but not provided');
                return $this->respond(['status' => 'error', 'message' => 'Session ID is required'], 400);
            }

            $exams = $this->examModel
                ->where('session_id', $sessionId)
                ->where('is_active', 'yes')
                ->findAll();
            log_message('debug', '[ViewAlevelMarksController.getExams] Exams fetched for Session ID ' . $sessionId . ': ' . count($exams) . ' exams');

            return $this->respond(['status' => 'success', 'data' => $exams], 200);
        } catch (\Exception $e) {
            log_message('error', '[ViewAlevelMarksController.getExams] Error: ' . $e->getMessage() . ' at line ' . $e->getLine());
            return $this->respond(['status' => 'error', 'message' => 'Failed to fetch exams'], 500);
        }
    }

    public function getClasses($sessionId)
    {
        log_message('debug', '[ViewAlevelMarksController.getClasses] Method called with Session ID: ' . $sessionId);
        try {
            if (!$sessionId) {
                log_message('error', '[ViewAlevelMarksController.getClasses] Session ID is required but not provided');
                return $this->respond(['status' => 'error', 'message' => 'Session ID is required'], 400);
            }

            $db = \Config\Database::connect('second_db');
            $classes = $db->table('classes c')
                ->select('c.id, c.class')
                ->join('tz_student_alevel_combinations sac', 'c.id = sac.class_id')
                ->where([
                    'sac.session_id' => $sessionId,
                    'sac.is_active' => 'yes',
                    'c.is_active' => 'no'
                ])
                ->groupBy('c.id')
                ->get()
                ->getResultArray();
            log_message('debug', '[ViewAlevelMarksController.getClasses] Classes fetched for Session ID ' . $sessionId . ': ' . count($classes) . ' classes');

            return $this->respond(['status' => 'success', 'data' => $classes], 200);
        } catch (\Exception $e) {
            log_message('error', '[ViewAlevelMarksController.getClasses] Error: ' . $e->getMessage() . ' at line ' . $e->getLine());
            return $this->respond(['status' => 'error', 'message' => 'Failed to fetch classes'], 500);
        }
    }

    public function getCombinations($sessionId, $classId)
    {
        log_message('debug', '[ViewAlevelMarksController.getCombinations] Method called with Session ID: ' . $sessionId . ', Class ID: ' . $classId);
        try {
            if (!$sessionId || !$classId) {
                log_message('error', '[ViewAlevelMarksController.getCombinations] Session ID and Class ID are required but missing - Session: ' . $sessionId . ', Class: ' . $classId);
                return $this->respond(['status' => 'error', 'message' => 'Session ID and Class ID are required'], 400);
            }

            $db = \Config\Database::connect('second_db');
            $combinations = $db->table('tz_student_alevel_combinations sac')
                ->select('ac.id, ac.combination_code, ac.combination_name')
                ->join('tz_alevel_combinations ac', 'ac.id = sac.combination_id')
                ->where([
                    'sac.session_id' => $sessionId,
                    'sac.class_id' => $classId,
                    'sac.is_active' => 'yes',
                    'ac.is_active' => 'yes'
                ])
                ->groupBy('ac.id')
                ->get()
                ->getResultArray();
            log_message('debug', '[ViewAlevelMarksController.getCombinations] Combinations fetched for Session ID ' . $sessionId . ' and Class ID ' . $classId . ': ' . count($combinations) . ' combinations');

            return $this->respond(['status' => 'success', 'data' => $combinations], 200);
        } catch (\Exception $e) {
            log_message('error', '[ViewAlevelMarksController.getCombinations] Error: ' . $e->getMessage() . ' at line ' . $e->getLine());
            return $this->respond(['status' => 'error', 'message' => 'Failed to fetch combinations'], 500);
        }
    }
}

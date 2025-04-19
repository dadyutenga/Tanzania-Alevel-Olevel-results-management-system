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
            $data = [
                'combinations' => $this->alevelCombinationModel->where('is_active', 'yes')->findAll(),
                'sessions' => $this->sessionModel->where('is_active', 'no')->findAll(),
                'exams' => [],
                'classes' => [],
                'marks' => [],
                'selected_filters' => null
            ];

            // Handle filter form submission
            if ($this->request->getMethod() === 'post') {
                log_message('debug', '[ViewAlevelMarksController.index] Processing POST request');
                
                $sessionId = $this->request->getPost('session_id');
                $examId = $this->request->getPost('exam_id');
                $classId = $this->request->getPost('class_id');
                $combinationId = $this->request->getPost('combination_id');

                log_message('debug', '[ViewAlevelMarksController.index] Filter values: ' . 
                           "Session: $sessionId, Exam: $examId, Class: $classId, Combination: $combinationId");

                if ($sessionId && $examId && $classId && $combinationId) {
                    // Store filter values
                    $data['selected_filters'] = [
                        'session_id' => $sessionId,
                        'exam_id' => $examId,
                        'class_id' => $classId,
                        'combination_id' => $combinationId
                    ];

                    // Fetch marks with comprehensive join query
                    $db = \Config\Database::connect();
                    $marks = $db->table('students s')
                        ->select('
                            s.id AS student_id,
                            CONCAT(s.firstname, " ", COALESCE(s.middlename, ""), " ", s.lastname) AS student_name,
                            c.class AS class_name,
                            sec.section AS section_name,
                            sess.session AS session_name,
                            ac.combination_code,
                            ac.combination_name,
                            acs.subject_name AS subject_name,
                            acs.subject_type AS subject_type,
                            e.exam_name,
                            e.exam_date,
                            asm.id as mark_id,
                            asm.marks_obtained,
                            s.roll_no
                        ')
                        ->join('student_session ss', 's.id = ss.student_id')
                        ->join('classes c', 'ss.class_id = c.id')
                        ->join('sections sec', 'ss.section_id = sec.id', 'left')
                        ->join('sessions sess', 'ss.session_id = sess.id')
                        ->join('tz_student_alevel_combinations sac', '
                            sac.class_id = ss.class_id 
                            AND sac.session_id = ss.session_id
                            AND (sac.section_id = ss.section_id OR sac.section_id IS NULL)
                        ')
                        ->join('tz_alevel_combinations ac', 'sac.combination_id = ac.id')
                        ->join('tz_alevel_combination_subjects acs', 'ac.id = acs.combination_id')
                        ->join('tz_alevel_exam_combinations aec', '
                            aec.combination_id = ac.id 
                            AND aec.class_id = c.id 
                            AND aec.session_id = sess.id
                            AND aec.exam_id = ' . $examId
                        )
                        ->join('tz_exams e', 'aec.exam_id = e.id', 'left')
                        ->join('tz_alevel_subject_marks asm', '
                            asm.exam_id = e.id 
                            AND asm.student_id = s.id 
                            AND asm.class_id = c.id 
                            AND asm.session_id = sess.id 
                            AND asm.combination_id = ac.id
                            AND asm.subject_id = acs.id
                        ', 'left')
                        ->where([
                            's.is_active' => 'yes',
                            'ss.is_active' => 'no',
                            'c.is_active' => 'no',
                            'sess.is_active' => 'no',
                            'ac.is_active' => 'yes',
                            'acs.is_active' => 'yes',
                            'sac.is_active' => 'yes',
                            'aec.is_active' => 'yes',
                            'e.is_active' => 'yes',
                            'ss.session_id' => $sessionId,
                            'ss.class_id' => $classId,
                            'sac.combination_id' => $combinationId
                        ])
                        ->orderBy('s.id')
                        ->orderBy('ac.combination_code')
                        ->orderBy('acs.subject_name')
                        ->get()
                        ->getResultArray();

                    log_message('debug', '[ViewAlevelMarksController.index] Marks query executed. Found ' . count($marks) . ' records');
                    
                    if (empty($marks)) {
                        log_message('debug', '[ViewAlevelMarksController.index] No marks found for the selected filters');
                        session()->setFlashdata('message', 'No marks found for the selected criteria.');
                    }

                    $data['marks'] = $marks;

                    // Fetch dropdowns data for the selected session
                    $data['exams'] = $this->examModel
                        ->where('session_id', $sessionId)
                        ->where('is_active', 'yes')
                        ->findAll();

                    $db = \Config\Database::connect('second_db');
                    $data['classes'] = $db->table('classes c')
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

                    // Log the final data being sent to view
                    log_message('debug', '[ViewAlevelMarksController.index] Data prepared for view: ' . 
                               json_encode(array_keys($data)));
                } else {
                    log_message('warning', '[ViewAlevelMarksController.index] Missing required filter values');
                    session()->setFlashdata('error', 'Please select all required filters.');
                }
            }

            return view('alevel/ViewAlevelExamMarks', $data);
        } catch (\Exception $e) {
            log_message('error', '[ViewAlevelMarksController.index] Error: ' . $e->getMessage());
            session()->setFlashdata('error', 'An error occurred while fetching marks: ' . $e->getMessage());
            return view('alevel/ViewAlevelExamMarks', $data);
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

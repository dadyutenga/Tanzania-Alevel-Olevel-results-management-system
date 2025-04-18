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
        $this->alevelMarksModel = new AlevelSubjectMarksModel();
        $this->alevelCombinationModel = new AlevelCombinationModel();
        $this->alevelCombinationSubjectModel = new AlevelCombinationSubjectModel();
        $this->studentModel = new StudentModel();
        $this->examModel = new ExamModel();
        $this->sessionModel = new SessionModel();
    }

    public function index()
    {
        try {
            $data = [
                'combinations' => $this->alevelCombinationModel->where('is_active', 'yes')->findAll(),
                'sessions' => $this->sessionModel->where('is_active', 'no')->findAll(),
                'exams' => [],
                'classes' => [],
                'marks' => [],
                'students' => [],
                'subjects' => [],
            ];

            $currentSession = $this->sessionModel->getCurrentSession();
            if ($currentSession) {
                $data['current_session'] = $currentSession;
                $data['exams'] = $this->examModel
                    ->where('session_id', $currentSession['id'])
                    ->where('is_active', 'yes')
                    ->findAll();
                
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
            }

            // Handle filter form submission
            if ($this->request->getMethod() === 'post') {
                $sessionId = $this->request->getPost('session_id');
                $examId = $this->request->getPost('exam_id');
                $classId = $this->request->getPost('class_id');
                $combinationId = $this->request->getPost('combination_id');

                if ($sessionId && $examId && $classId && $combinationId) {
                    // Fetch marks based on filters
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

                    // Store filter values for display
                    $data['selected_filters'] = [
                        'session_id' => $sessionId,
                        'exam_id' => $examId,
                        'class_id' => $classId,
                        'combination_id' => $combinationId
                    ];
                } else {
                    session()->setFlashdata('error', 'Please select all filters to view marks.');
                }
            }

            return view('alevel/ViewAlevelMarks', $data);
        } catch (\Exception $e) {
            log_message('error', '[ViewAlevelMarksController.index] Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load marks view page: ' . $e->getMessage());
        }
    }

    public function update()
    {
        try {
            if ($this->request->getMethod() !== 'post') {
                throw new \Exception('Invalid request method for updating marks.');
            }

            $markId = $this->request->getPost('mark_id');
            $marksObtained = $this->request->getPost('marks_obtained');

            if (!$markId || !is_numeric($marksObtained) || $marksObtained < 0 || $marksObtained > 100) {
                throw new \Exception('Invalid mark data provided for update.');
            }

            $updateData = [
                'marks_obtained' => (int)$marksObtained,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            if (!$this->alevelMarksModel->update($markId, $updateData)) {
                throw new \Exception('Failed to update marks: ' . implode(', ', $this->alevelMarksModel->errors()));
            }

            session()->setFlashdata('message', 'Marks updated successfully.');
            return redirect()->back();
        } catch (\Exception $e) {
            log_message('error', '[ViewAlevelMarksController.update] Error: ' . $e->getMessage());
            session()->setFlashdata('error', 'Failed to update marks: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function delete()
    {
        try {
            if ($this->request->getMethod() !== 'post') {
                throw new \Exception('Invalid request method for deleting marks.');
            }

            $markIds = $this->request->getPost('mark_ids');
            $deleteAll = $this->request->getPost('delete_all');
            $sessionId = $this->request->getPost('session_id');
            $examId = $this->request->getPost('exam_id');
            $classId = $this->request->getPost('class_id');
            $combinationId = $this->request->getPost('combination_id');

            if ($deleteAll === 'yes' && $sessionId && $examId && $classId && $combinationId) {
                // Delete all marks based on filters
                $this->alevelMarksModel->where([
                    'session_id' => $sessionId,
                    'exam_id' => $examId,
                    'class_id' => $classId,
                    'combination_id' => $combinationId
                ])->delete();
                session()->setFlashdata('message', 'All marks for the selected filters have been deleted successfully.');
            } elseif (is_array($markIds) && !empty($markIds)) {
                // Delete specific marks
                $this->alevelMarksModel->whereIn('id', $markIds)->delete();
                session()->setFlashdata('message', 'Selected marks have been deleted successfully.');
            } else {
                throw new \Exception('No marks selected for deletion or invalid filters.');
            }

            return redirect()->back();
        } catch (\Exception $e) {
            log_message('error', '[ViewAlevelMarksController.delete] Error: ' . $e->getMessage());
            session()->setFlashdata('error', 'Failed to delete marks: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}

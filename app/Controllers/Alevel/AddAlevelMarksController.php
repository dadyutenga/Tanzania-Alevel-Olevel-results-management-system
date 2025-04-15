<?php

namespace  App\Controllers\Alevel
use  App\Controllers\BaseController
use  App\Models\AlevelMarksModel;
use  App\Models\AlevelCombinationModel;
use  App\Models\AlevelSubjectModel;
use  App\Models\AlevelStudentModel; 
use  App\Models\AlevelStudentMarksModel;
use  App\Models\AlevelStudentCombinationModel;
use  App\Models\AlevelStudentSubjectModel;
use  App\Models\AlevelStudentSubjectMarksModel;
use  App\Models\AlevelStudentSubjectMarksModel;
use  App\Models\AlevelStudentSubjectMarksModel;

class AddAlevelMarksController extends BaseController
{
    protected $alevelMarksModel;
    protected $alevelCombinationModel;
    protected $alevelSubjectModel;
    protected $alevelStudentModel; 
    protected $alevelStudentMarksModel;
    protected $alevelStudentCombinationModel;
    protected $alevelStudentSubjectModel;
    protected $alevelStudentSubjectMarksModel;

    public function __construct()
    {
        $this->alevelMarksModel = new AlevelMarksModel();
        $this->alevelCombinationModel = new AlevelCombinationModel();
        $this->alevelSubjectModel = new AlevelSubjectModel();
        $this->alevelStudentModel = new AlevelStudentModel(); 
        $this->alevelStudentMarksModel = new AlevelStudentMarksModel();
        $this->alevelStudentCombinationModel = new AlevelStudentCombinationModel();
        $this->alevelStudentSubjectModel = new AlevelStudentSubjectModel();
        $this->alevelStudentSubjectMarksModel = new AlevelStudentSubjectMarksModel();
    }


     public function index()
    {
        try {
            $data = [
                'combinations' => $this->alevelCombinationModel->findAll(),
                'subjects' => $this->alevelSubjectModel->findAll(),
                'students' => $this->alevelStudentModel->findAll()
                'session' => $this->alevelStudentMarksModel->findAll(),
                'marks' => $this->alevelStudentSubjectMarksModel->findAll(),
                'student_subjects' => $this->alevelStudentSubjectModel->findAll(),
                'student_combinations' => $this->alevelStudentCombinationModel->findAll(),
            ];
            return view('alevel/AddMarks', $data);
        } catch (\Exception $e) {
            log_message('error', '[AddAlevelMarksController.index] Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load marks page');
        }
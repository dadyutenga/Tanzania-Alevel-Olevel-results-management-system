<?php

namespace App\Controllers;

use App\Models\ExamModel;
use CodeIgniter\RESTful\ResourceController;

class AddExamController extends ResourceController
{
    protected $format = 'json';

    public function index()
    {
        return view('exam/AddExam');
    }

    public function store()
    {
        try {
            $rules = [
                'exam_name' => 'required|max_length[100]',
                'exam_date' => 'required|valid_date',
                'academic_year' => 'required|max_length[20]',
                'is_active' => 'permit_empty|in_list[yes,no]'
            ];

            if (!$this->validate($rules)) {
                return $this->respond([
                    'status' => 'error',
                    'message' => $this->validator->getErrors()
                ], 400);
            }

            $data = [
                'exam_name' => $this->request->getPost('exam_name'),
                'exam_date' => $this->request->getPost('exam_date'),
                'academic_year' => $this->request->getPost('academic_year'),
                'is_active' => $this->request->getPost('is_active', 'yes')
            ];

            $examModel = new ExamModel();
            $examModel->insert($data);

            return $this->respond([
                'status' => 'success',
                'message' => 'Exam created successfully'
            ]);

        } catch (\Exception $e) {
            log_message('error', '[AddExam] Exception: {message}', ['message' => $e->getMessage()]);
            return $this->respond([
                'status' => 'error',
                'message' => 'Failed to create exam'
            ], 500);
        }
    }
}

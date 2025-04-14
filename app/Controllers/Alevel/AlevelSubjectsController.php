<?php

namespace App\Controllers\Alevel;  

use App\Controllers\BaseController;
use App\Models\AlevelCombinationModel;
use App\Models\AlevelCombinationSubjectModel;

class AlevelSubjectsController extends BaseController
{
    protected $alevelCombinationModel;
    protected $alevelCombinationSubjectModel;

    public function __construct()
    {
        $this->alevelCombinationModel = new AlevelCombinationModel();
        $this->alevelCombinationSubjectModel = new AlevelCombinationSubjectModel();
    }

    public function index()
    {
        try {
            $data = [
                'subjects' => $this->alevelCombinationSubjectModel->findAll(),
                'combinations' => $this->alevelCombinationModel->findAll()
            ];
            return view('alevel/AddCombinationSubjects', $data);
        } catch (\Exception $e) {
            log_message('error', '[AlevelSubjectsController.index] Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load subjects page');
        }
    }

    public function store()
    {
        try {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'combination_id' => 'required|numeric',
                'subject_name' => 'required|max_length[100]',
                'subject_type' => 'required|in_list[major,additional]',
                'is_active' => 'in_list[yes,no]'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }

            $data = [
                'combination_id' => $this->request->getPost('combination_id'),
                'subject_name' => $this->request->getPost('subject_name'),
                'subject_type' => $this->request->getPost('subject_type'),
                'is_active' => $this->request->getPost('is_active') ?? 'yes'
            ];

            if ($this->alevelCombinationSubjectModel->insert($data)) {
                return redirect()->to(base_url('alevel/subjects'))->with('message', 'Subject added successfully');
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to add subject');
            }
        } catch (\Exception $e) {
            log_message('error', '[AlevelSubjectsController.store] Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred while adding the subject: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $subject = $this->alevelCombinationSubjectModel->find($id);
            if (!$subject) {
                return redirect()->to(base_url('alevel/subjects'))->with('error', 'Subject not found');
            }

            $data = [
                'subjects' => $this->alevelCombinationSubjectModel->findAll(),
                'combinations' => $this->alevelCombinationModel->findAll(),
                'edit_subject' => $subject
            ];
            return view('alevel/AddCombinationSubjects', $data);
        } catch (\Exception $e) {
            log_message('error', '[AlevelSubjectsController.edit] Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load subject for editing');
        }
    }

    public function update($id)
    {
        try {
            $subject = $this->alevelCombinationSubjectModel->find($id);
            if (!$subject) {
                return redirect()->to(base_url('alevel/subjects'))->with('error', 'Subject not found');
            }

            $validation = \Config\Services::validation();
            $validation->setRules([
                'combination_id' => 'required|numeric',
                'subject_name' => 'required|max_length[100]',
                'subject_type' => 'required|in_list[major,additional]',
                'is_active' => 'in_list[yes,no]'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }

            $data = [
                'combination_id' => $this->request->getPost('combination_id'),
                'subject_name' => $this->request->getPost('subject_name'),
                'subject_type' => $this->request->getPost('subject_type'),
                'is_active' => $this->request->getPost('is_active')
            ];

            if ($this->alevelCombinationSubjectModel->update($id, $data)) {
                return redirect()->to(base_url('alevel/subjects'))->with('message', 'Subject updated successfully');
            } else {
                $errors = $this->alevelCombinationSubjectModel->errors();
                if (!empty($errors)) {
                    return redirect()->back()->withInput()->with('error', 'Failed to update subject: ' . implode(', ', $errors));
                }
                return redirect()->back()->withInput()->with('error', 'Failed to update subject due to an unknown error');
            }
        } catch (\Exception $e) {
            log_message('error', '[AlevelSubjectsController.update] Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred while updating the subject: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $subject = $this->alevelCombinationSubjectModel->find($id);
            if (!$subject) {
                return redirect()->to(base_url('alevel/subjects'))->with('error', 'Subject not found');
            }

            if ($this->alevelCombinationSubjectModel->delete($id)) {
                return redirect()->to(base_url('alevel/subjects'))->with('message', 'Subject deleted successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to delete subject');
            }
        } catch (\Exception $e) {
            log_message('error', '[AlevelSubjectsController.delete] Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting the subject');
        }
    }

    public function view()
    {
        try {
            $data = [
                'subjects' => $this->alevelCombinationSubjectModel->findAll(),
                'combinations' => $this->alevelCombinationModel->findAll()
            ];
            return view('alevel/viewCombinationSubjects', $data);
        } catch (\Exception $e) {
            log_message('error', '[AlevelSubjectsController.view] Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load subjects view page');
        }
    }
}


<?php

namespace App\Controllers\Alevel;

use App\Controllers\BaseController;
use App\Models\AlevelCombinationModel;

class AddAlevelController extends BaseController
{
    protected $alevelCombinationModel;

    public function __construct()
    {
        $this->alevelCombinationModel = new AlevelCombinationModel();
    }

    public function index()
    {
        try {
            $data = [
                'combinations' => $this->alevelCombinationModel->findAll()
            ];
            return view('alevel/AddCombinations', $data);
        } catch (\Exception $e) {
            log_message('error', '[AddAlevelController.index] Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load combinations page');
        }
    }

    public function store()
    {
        try {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'combination_name' => 'required|max_length[100]',
                'is_active'        => 'in_list[yes,no]'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }

            $combinationCode = $this->request->getPost('combination_code');
            // Manual uniqueness check using the model's database connection
            $existingCombination = $this->alevelCombinationModel->where('combination_code', $combinationCode)->first();
            if ($existingCombination) {
                return redirect()->back()->withInput()->with('error', 'The combination code must be unique.');
            }

            $data = [
                'combination_code' => $combinationCode,
                'combination_name' => $this->request->getPost('combination_name'),
                'is_active'        => $this->request->getPost('is_active') ?? 'yes'
            ];

            if ($this->alevelCombinationModel->insert($data)) {
                return redirect()->to(base_url('alevel/combinations'))->with('message', 'Combination added successfully');
            } else {
                return redirect()->back()->withInput()->with('error', 'Failed to add combination');
            }
        } catch (\Exception $e) {
            log_message('error', '[AddAlevelController.store] Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred while adding the combination: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $combination = $this->alevelCombinationModel->find($id);
            if (!$combination) {
                return redirect()->to(base_url('alevel/combinations'))->with('error', 'Combination not found');
            }

            $data = [
                'combinations' => $this->alevelCombinationModel->findAll(),
                'edit_combination' => $combination
            ];
            return view('alevel/AddCombinations', $data);
        } catch (\Exception $e) {
            log_message('error', '[AddAlevelController.edit] Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to load combination for editing');
        }
    }

    public function update($id)
    {
        try {
            $combination = $this->alevelCombinationModel->find($id);
            if (!$combination) {
                return redirect()->to(base_url('alevel/combinations'))->with('error', 'Combination not found');
            }

            $validation = \Config\Services::validation();
            $validation->setRules([
                'combination_name' => 'required|max_length[100]'
            ]);

            if (!$validation->withRequest($this->request)->run()) {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }

            $data = [
                'combination_name' => $this->request->getPost('combination_name')
            ];

            if ($this->alevelCombinationModel->update($id, $data)) {
                return redirect()->to(base_url('alevel/combinations'))->with('message', 'Combination name updated successfully');
            } else {
                $errors = $this->alevelCombinationModel->errors();
                if (!empty($errors)) {
                    return redirect()->back()->withInput()->with('error', 'Failed to update combination name: ' . implode(', ', $errors));
                }
                return redirect()->back()->withInput()->with('error', 'Failed to update combination name due to an unknown error');
            }
        } catch (\Exception $e) {
            log_message('error', '[AddAlevelController.update] Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred while updating the combination name: ' . $e->getMessage());
        }
    }
    public function delete($id)
    {
        try {
            $combination = $this->alevelCombinationModel->find($id);
            if (!$combination) {
                return redirect()->to(base_url('alevel/combinations'))->with('error', 'Combination not found');
            }

            if ($this->alevelCombinationModel->delete($id)) {
                return redirect()->to(base_url('alevel/combinations'))->with('message', 'Combination deleted successfully');
            } else {
                return redirect()->back()->with('error', 'Failed to delete combination');
            }
        } catch (\Exception $e) {
            log_message('error', '[AddAlevelController.delete] Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting the combination');
        }
    }
}

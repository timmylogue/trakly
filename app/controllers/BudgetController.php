<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Budget.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Helper.php';
require_once __DIR__ . '/../helpers/Validator.php';

class BudgetController
{
    private $budgetModel;
    private $validator;

    public function __construct()
    {
        $this->budgetModel = new Budget();
        $this->validator = new Validator();
    }

    public function index()
    {
        Auth::requireLogin();

        $userId = Auth::userId();
        $budgets = $this->budgetModel->findByUserId($userId);

        $data = [
            'budgets' => $budgets
        ];

        require_once __DIR__ . '/../views/budgets/index.php';
    }

    public function edit($id)
    {
        Auth::requireLogin();

        $userId = Auth::userId();
        $budget = $this->budgetModel->findById($id);

        if (!$budget || $budget['user_id'] != $userId) {
            Helper::flashMessage('error', 'Budget not found');
            Helper::redirect('budgets');
        }

        $data = [
            'budget' => $budget
        ];

        require_once __DIR__ . '/../views/budgets/edit.php';
    }

    public function update($id)
    {
        Auth::requireLogin();

        $userId = Auth::userId();
        $budget = $this->budgetModel->findById($id);

        if (!$budget || $budget['user_id'] != $userId) {
            Helper::flashMessage('error', 'Budget not found');
            Helper::redirect('budgets');
        }

        $data = [
            'name' => Helper::sanitize($_POST['name'] ?? ''),
            'total_amount' => Helper::sanitize($_POST['total_amount'] ?? 0),
            'period' => Helper::sanitize($_POST['period'] ?? 'monthly')
        ];

        $rules = [
            'name' => 'required|min:2|max:255',
            'total_amount' => 'required|numeric',
            'period' => 'required'
        ];

        if (!$this->validator->validate($data, $rules)) {
            Helper::flashMessage('error', $this->validator->getFirstError());
            Helper::redirect('budgets/edit/' . $id);
        }

        if ($this->budgetModel->update($id, $data['name'], $data['period'], $data['total_amount'])) {
            Helper::flashMessage('success', 'Budget updated successfully');
            Helper::redirect('budgets');
        } else {
            Helper::flashMessage('error', 'Failed to update budget');
            Helper::redirect('budgets/edit/' . $id);
        }
    }

    public function setActive($id)
    {
        Auth::requireLogin();

        $userId = Auth::userId();
        $budget = $this->budgetModel->findById($id);

        if (!$budget || $budget['user_id'] != $userId) {
            Helper::flashMessage('error', 'Budget not found');
            Helper::redirect('budgets');
        }

        // Deactivate all budgets for this user
        $allBudgets = $this->budgetModel->findByUserId($userId, false);
        foreach ($allBudgets as $b) {
            $this->budgetModel->setActive($b['id'], 0);
        }

        // Activate the selected budget
        if ($this->budgetModel->setActive($id, 1)) {
            Helper::flashMessage('success', 'Budget activated successfully');
        } else {
            Helper::flashMessage('error', 'Failed to activate budget');
        }

        Helper::redirect('budgets');
    }
}

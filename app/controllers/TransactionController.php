<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Transaction.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Budget.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Helper.php';
require_once __DIR__ . '/../helpers/Validator.php';

class TransactionController
{
    private $transactionModel;
    private $categoryModel;
    private $budgetModel;
    private $validator;

    public function __construct()
    {
        $this->transactionModel = new Transaction();
        $this->categoryModel = new Category();
        $this->budgetModel = new Budget();
        $this->validator = new Validator();
    }

    public function index()
    {
        Auth::requireLogin();

        $userId = Auth::userId();
        $filters = [
            'category_id' => $_GET['category'] ?? null,
            'type' => $_GET['type'] ?? null,
            'start_date' => $_GET['start_date'] ?? null,
            'end_date' => $_GET['end_date'] ?? null,
            'search' => $_GET['search'] ?? null
        ];

        $transactions = $this->transactionModel->search($userId, $filters);
        $categories = $this->categoryModel->findByUserId($userId);

        $data = [
            'transactions' => $transactions,
            'categories' => $categories,
            'filters' => $filters
        ];

        require_once __DIR__ . '/../views/transactions/index.php';
    }

    public function create()
    {
        Auth::requireLogin();

        $userId = Auth::userId();
        $categories = $this->categoryModel->findByUserId($userId);
        $budgets = $this->budgetModel->findByUserId($userId);

        $data = [
            'categories' => $categories,
            'budgets' => $budgets
        ];

        require_once __DIR__ . '/../views/transactions/create.php';
    }

    public function store()
    {
        Auth::requireLogin();

        $userId = Auth::userId();

        $data = [
            'amount' => Helper::sanitize($_POST['amount'] ?? ''),
            'date' => Helper::sanitize($_POST['date'] ?? ''),
            'type' => Helper::sanitize($_POST['type'] ?? 'expense'),
            'category_id' => Helper::sanitize($_POST['category_id'] ?? null),
            'budget_id' => Helper::sanitize($_POST['budget_id'] ?? null),
            'note' => Helper::sanitize($_POST['note'] ?? ''),
            'tags' => Helper::sanitize($_POST['tags'] ?? '')
        ];

        // Convert empty strings to null for foreign keys
        if (empty($data['category_id'])) {
            $data['category_id'] = null;
        }
        if (empty($data['budget_id'])) {
            $data['budget_id'] = null;
        }

        $rules = [
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'type' => 'required'
        ];

        if (!$this->validator->validate($data, $rules)) {
            Helper::flashMessage('error', $this->validator->getFirstError());
            Helper::redirect('transactions/create');
        }

        // Handle receipt upload (Premium feature)
        $receiptPath = null;
        if (Auth::isPremium() && isset($_FILES['receipt']) && $_FILES['receipt']['error'] === UPLOAD_ERR_OK) {
            $upload = Helper::uploadFile($_FILES['receipt']);
            if ($upload['success']) {
                $receiptPath = $upload['path'];
            }
        }

        $transactionId = $this->transactionModel->create(
            $userId,
            $data['amount'],
            $data['date'],
            $data['type'],
            $data['category_id'],
            $data['budget_id'],
            $data['note'],
            $data['tags'],
            $receiptPath
        );

        if ($transactionId) {
            Helper::flashMessage('success', 'Transaction added successfully');
            Helper::redirect('transactions');
        } else {
            Helper::flashMessage('error', 'Failed to add transaction');
            Helper::redirect('transactions/create');
        }
    }

    public function edit($id)
    {
        Auth::requireLogin();

        $userId = Auth::userId();
        $transaction = $this->transactionModel->findById($id);

        if (!$transaction || $transaction['user_id'] != $userId) {
            Helper::flashMessage('error', 'Transaction not found');
            Helper::redirect('transactions');
        }

        $categories = $this->categoryModel->findByUserId($userId);

        $data = [
            'transaction' => $transaction,
            'categories' => $categories
        ];

        require_once __DIR__ . '/../views/transactions/edit.php';
    }

    public function update($id)
    {
        Auth::requireLogin();

        $userId = Auth::userId();
        $transaction = $this->transactionModel->findById($id);

        if (!$transaction || $transaction['user_id'] != $userId) {
            Helper::flashMessage('error', 'Transaction not found');
            Helper::redirect('transactions');
        }

        $data = [
            'amount' => Helper::sanitize($_POST['amount'] ?? ''),
            'date' => Helper::sanitize($_POST['date'] ?? ''),
            'type' => Helper::sanitize($_POST['type'] ?? 'expense'),
            'category_id' => Helper::sanitize($_POST['category_id'] ?? null),
            'note' => Helper::sanitize($_POST['note'] ?? ''),
            'tags' => Helper::sanitize($_POST['tags'] ?? '')
        ];

        $rules = [
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'type' => 'required'
        ];

        if (!$this->validator->validate($data, $rules)) {
            Helper::flashMessage('error', $this->validator->getFirstError());
            Helper::redirect('transactions/edit/' . $id);
        }

        if ($this->transactionModel->update($id, $data['amount'], $data['date'], $data['type'], $data['category_id'], $data['note'], $data['tags'])) {
            Helper::flashMessage('success', 'Transaction updated successfully');
            Helper::redirect('transactions');
        } else {
            Helper::flashMessage('error', 'Failed to update transaction');
            Helper::redirect('transactions/edit/' . $id);
        }
    }

    public function delete($id)
    {
        Auth::requireLogin();

        $userId = Auth::userId();
        $transaction = $this->transactionModel->findById($id);

        if (!$transaction || $transaction['user_id'] != $userId) {
            Helper::flashMessage('error', 'Transaction not found');
            Helper::redirect('transactions');
        }

        // Delete receipt if exists
        if (!empty($transaction['receipt_path'])) {
            Helper::deleteFile($transaction['receipt_path']);
        }

        if ($this->transactionModel->delete($id)) {
            Helper::flashMessage('success', 'Transaction deleted successfully');
        } else {
            Helper::flashMessage('error', 'Failed to delete transaction');
        }

        Helper::redirect('transactions');
    }
}

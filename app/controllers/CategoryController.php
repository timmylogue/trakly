<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Helper.php';
require_once __DIR__ . '/../helpers/Validator.php';

class CategoryController
{
    private $categoryModel;
    private $validator;

    public function __construct()
    {
        $this->categoryModel = new Category();
        $this->validator = new Validator();
    }

    public function index()
    {
        Auth::requireLogin();

        $userId = Auth::userId();
        $monthStart = Helper::getMonthStart();
        $monthEnd = Helper::getMonthEnd();

        $categories = $this->categoryModel->getCategoryWithSpending($userId, null, $monthStart, $monthEnd);

        $data = [
            'categories' => $categories,
            'currentMonth' => date('F Y'),
            'monthStart' => $monthStart,
            'monthEnd' => $monthEnd
        ];

        require_once __DIR__ . '/../views/categories/index.php';
    }

    public function create()
    {
        Auth::requireLogin();
        require_once __DIR__ . '/../views/categories/create.php';
    }

    public function store()
    {
        Auth::requireLogin();

        $userId = Auth::userId();

        $data = [
            'name' => Helper::sanitize($_POST['name'] ?? ''),
            'limit_amount' => Helper::sanitize($_POST['limit_amount'] ?? 0),
            'color' => Helper::sanitize($_POST['color'] ?? '#3B82F6'),
            'icon' => Helper::sanitize($_POST['icon'] ?? 'tag')
        ];

        $rules = [
            'name' => 'required|min:2|max:255',
            'limit_amount' => 'required|numeric'
        ];

        if (!$this->validator->validate($data, $rules)) {
            Helper::flashMessage('error', $this->validator->getFirstError());
            Helper::redirect('categories/create');
        }

        if ($this->categoryModel->create($userId, $data['name'], $data['limit_amount'], null, $data['color'], $data['icon'])) {
            Helper::flashMessage('success', 'Category created successfully');
            Helper::redirect('categories');
        } else {
            Helper::flashMessage('error', 'Failed to create category');
            Helper::redirect('categories/create');
        }
    }

    public function edit($id)
    {
        Auth::requireLogin();

        $userId = Auth::userId();
        $category = $this->categoryModel->findById($id);

        if (!$category || $category['user_id'] != $userId) {
            Helper::flashMessage('error', 'Category not found');
            Helper::redirect('categories');
        }

        $data = [
            'category' => $category
        ];

        require_once __DIR__ . '/../views/categories/edit.php';
    }

    public function update($id)
    {
        Auth::requireLogin();

        $userId = Auth::userId();
        $category = $this->categoryModel->findById($id);

        if (!$category || $category['user_id'] != $userId) {
            Helper::flashMessage('error', 'Category not found');
            Helper::redirect('categories');
        }

        $data = [
            'name' => Helper::sanitize($_POST['name'] ?? ''),
            'limit_amount' => Helper::sanitize($_POST['limit_amount'] ?? 0),
            'color' => Helper::sanitize($_POST['color'] ?? '#3B82F6'),
            'icon' => Helper::sanitize($_POST['icon'] ?? 'tag')
        ];

        $rules = [
            'name' => 'required|min:2|max:255',
            'limit_amount' => 'required|numeric'
        ];

        if (!$this->validator->validate($data, $rules)) {
            Helper::flashMessage('error', $this->validator->getFirstError());
            Helper::redirect('categories/edit/' . $id);
        }

        if ($this->categoryModel->update($id, $data['name'], $data['limit_amount'], $data['color'], $data['icon'])) {
            Helper::flashMessage('success', 'Category updated successfully');
            Helper::redirect('categories');
        } else {
            Helper::flashMessage('error', 'Failed to update category');
            Helper::redirect('categories/edit/' . $id);
        }
    }

    public function delete($id)
    {
        Auth::requireLogin();

        $userId = Auth::userId();
        $category = $this->categoryModel->findById($id);

        if (!$category || $category['user_id'] != $userId) {
            Helper::flashMessage('error', 'Category not found');
            Helper::redirect('categories');
        }

        if ($this->categoryModel->delete($id)) {
            Helper::flashMessage('success', 'Category deleted successfully');
        } else {
            Helper::flashMessage('error', 'Failed to delete category');
        }

        Helper::redirect('categories');
    }
}

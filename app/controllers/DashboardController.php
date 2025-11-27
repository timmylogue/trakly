<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../models/Transaction.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Budget.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Helper.php';

class DashboardController
{
    private $transactionModel;
    private $categoryModel;
    private $budgetModel;

    public function __construct()
    {
        $this->transactionModel = new Transaction();
        $this->categoryModel = new Category();
        $this->budgetModel = new Budget();
    }

    public function index()
    {
        Auth::requireLogin();

        $userId = Auth::userId();

        // Get selected month from URL parameter or use current month
        $selectedMonth = $_GET['month'] ?? date('Y-m');
        $selectedDate = $selectedMonth . '-01';

        $currentMonth = Helper::getCurrentMonth();
        $monthStart = Helper::getMonthStart($selectedDate);
        $monthEnd = Helper::getMonthEnd($selectedDate);

        // Previous month dates
        $prevMonthDate = date('Y-m-d', strtotime('-1 month', strtotime($selectedDate)));
        $prevMonthStart = Helper::getMonthStart($prevMonthDate);
        $prevMonthEnd = Helper::getMonthEnd($prevMonthDate);

        // Next month date for navigation
        $nextMonthDate = date('Y-m', strtotime('+1 month', strtotime($selectedDate)));
        $prevMonthDateNav = date('Y-m', strtotime('-1 month', strtotime($selectedDate)));

        // Check if selected month is current month
        $isCurrentMonth = ($selectedMonth === $currentMonth);

        // Get active budget
        $activeBudget = $this->budgetModel->getActiveBudget($userId);

        // Get monthly totals (selected month)
        $monthlyExpenses = $this->transactionModel->getMonthlyTotal($userId, date('Y', strtotime($selectedDate)), date('m', strtotime($selectedDate)), 'expense');
        $monthlyIncome = $this->transactionModel->getMonthlyTotal($userId, date('Y', strtotime($selectedDate)), date('m', strtotime($selectedDate)), 'income');

        // Get previous month totals
        $prevMonthExpenses = $this->transactionModel->getMonthlyTotal($userId, date('Y', strtotime($prevMonthDate)), date('m', strtotime($prevMonthDate)), 'expense');
        $prevMonthIncome = $this->transactionModel->getMonthlyTotal($userId, date('Y', strtotime($prevMonthDate)), date('m', strtotime($prevMonthDate)), 'income');

        // Get categories with spending (current month only)
        $categories = $this->categoryModel->getCategoryWithSpending($userId, $activeBudget['id'] ?? null, $monthStart, $monthEnd);

        // Get recent transactions (current month)
        $recentTransactions = $this->transactionModel->search($userId, [
            'start_date' => $monthStart,
            'end_date' => $monthEnd,
            'limit' => 10
        ]);

        // Get category breakdown for chart
        $categoryBreakdown = $this->transactionModel->getCategoryBreakdown($userId, $monthStart, $monthEnd);

        // Calculate totals
        $budgetTotal = $activeBudget['total_amount'] ?? 0;
        $remaining = $budgetTotal - $monthlyExpenses;

        $data = [
            'activeBudget' => $activeBudget,
            'monthlyExpenses' => $monthlyExpenses,
            'monthlyIncome' => $monthlyIncome,
            'remaining' => $remaining,
            'budgetTotal' => $budgetTotal,
            'categories' => $categories,
            'recentTransactions' => $recentTransactions,
            'categoryBreakdown' => $categoryBreakdown,
            'currentMonth' => date('F Y', strtotime($selectedDate)),
            'prevMonth' => date('F Y', strtotime($prevMonthDate)),
            'monthStart' => $monthStart,
            'monthEnd' => $monthEnd,
            'prevMonthExpenses' => $prevMonthExpenses,
            'prevMonthIncome' => $prevMonthIncome,
            'prevMonthRemaining' => $budgetTotal - $prevMonthExpenses,
            'selectedMonth' => $selectedMonth,
            'prevMonthNav' => $prevMonthDateNav,
            'nextMonthNav' => $nextMonthDate,
            'isCurrentMonth' => $isCurrentMonth,
            'canGoNext' => !$isCurrentMonth
        ];

        require_once __DIR__ . '/../views/dashboard/index.php';
    }
}

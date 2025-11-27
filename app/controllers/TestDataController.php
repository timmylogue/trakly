<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../helpers/Auth.php';
require_once __DIR__ . '/../helpers/Helper.php';

class TestDataController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function index()
    {
        Auth::requireLogin();

        // Check if test data exists
        $hasTestData = $this->hasTestData();

        $data = [
            'hasTestData' => $hasTestData,
            'testDataInfo' => $this->getTestDataInfo()
        ];

        require_once __DIR__ . '/../views/testdata/index.php';
    }

    public function seed()
    {
        Auth::requireLogin();

        try {
            // Read and execute seed file
            $sqlFile = __DIR__ . '/../../sql/seed_test_data.sql';

            if (!file_exists($sqlFile)) {
                $_SESSION['error'] = 'Seed file not found!';
                header('Location: ' . BASE_URL . 'testdata');
                exit;
            }

            $sql = file_get_contents($sqlFile);

            // Remove comments and split by semicolon
            $sql = preg_replace('/--.*$/m', '', $sql);
            $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);

            // Execute SQL
            $this->db->exec($sql);

            $_SESSION['success'] = 'Test data seeded successfully! You can now login with: john@example.com / password123';
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error seeding test data: ' . $e->getMessage();
        }

        header('Location: ' . BASE_URL . 'testdata');
        exit;
    }

    public function clean()
    {
        Auth::requireLogin();

        try {
            // Delete test users (cascade will handle related data)
            $stmt = $this->db->prepare("
                DELETE FROM users WHERE email IN (
                    'john@example.com',
                    'jane@example.com',
                    'bob@example.com'
                )
            ");
            $stmt->execute();

            $deletedCount = $stmt->rowCount();

            $_SESSION['success'] = "Test data cleaned successfully! Removed {$deletedCount} test users and their associated data.";
        } catch (Exception $e) {
            $_SESSION['error'] = 'Error cleaning test data: ' . $e->getMessage();
        }

        header('Location: ' . BASE_URL . 'testdata');
        exit;
    }

    private function hasTestData()
    {
        $stmt = $this->db->query("
            SELECT COUNT(*) as count 
            FROM users 
            WHERE email LIKE '%@example.com'
        ");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    private function getTestDataInfo()
    {
        $stmt = $this->db->query("
            SELECT 
                (SELECT COUNT(*) FROM users WHERE email LIKE '%@example.com') as users,
                (SELECT COUNT(*) FROM transactions WHERE user_id IN (SELECT id FROM users WHERE email LIKE '%@example.com')) as transactions,
                (SELECT COUNT(*) FROM categories WHERE user_id IN (SELECT id FROM users WHERE email LIKE '%@example.com')) as categories,
                (SELECT COUNT(*) FROM budgets WHERE user_id IN (SELECT id FROM users WHERE email LIKE '%@example.com')) as budgets
        ");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

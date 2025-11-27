<?php
require_once __DIR__ . '/../../config/database.php';

class Budget
{
    private $db;
    private $table = 'budgets';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($userId, $name, $period = 'monthly', $totalAmount = 0.00, $startDate = null, $endDate = null)
    {
        $query = "INSERT INTO {$this->table} (user_id, name, period, total_amount, start_date, end_date) 
                  VALUES (:user_id, :name, :period, :total_amount, :start_date, :end_date)";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':period', $period);
        $stmt->bindParam(':total_amount', $totalAmount);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);

        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function findById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function findByUserId($userId, $activeOnly = true)
    {
        $query = "SELECT * FROM {$this->table} WHERE user_id = :user_id";

        if ($activeOnly) {
            $query .= " AND is_active = 1";
        }

        $query .= " ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function update($id, $name, $period, $totalAmount, $startDate = null, $endDate = null)
    {
        $query = "UPDATE {$this->table} SET name = :name, period = :period, total_amount = :total_amount, 
                  start_date = :start_date, end_date = :end_date WHERE id = :id";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':period', $period);
        $stmt->bindParam(':total_amount', $totalAmount);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function setActive($id, $isActive = 1)
    {
        $query = "UPDATE {$this->table} SET is_active = :is_active WHERE id = :id";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':is_active', $isActive, PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function getActiveBudget($userId)
    {
        $query = "SELECT * FROM {$this->table} WHERE user_id = :user_id AND is_active = 1 
                  ORDER BY created_at DESC LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function getTotalSpent($budgetId)
    {
        $query = "SELECT SUM(amount) as total FROM transactions 
                  WHERE budget_id = :budget_id AND type = 'expense'";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':budget_id', $budgetId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
}

<?php
require_once __DIR__ . '/../../config/database.php';

class Category
{
    private $db;
    private $table = 'categories';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($userId, $name, $limitAmount = 0.00, $budgetId = null, $color = '#3B82F6', $icon = 'tag')
    {
        $query = "INSERT INTO {$this->table} (user_id, budget_id, name, limit_amount, color, icon) 
                  VALUES (:user_id, :budget_id, :name, :limit_amount, :color, :icon)";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':budget_id', $budgetId, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':limit_amount', $limitAmount);
        $stmt->bindParam(':color', $color);
        $stmt->bindParam(':icon', $icon);

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

    public function findByUserId($userId, $budgetId = null)
    {
        if ($budgetId !== null) {
            $query = "SELECT * FROM {$this->table} WHERE user_id = :user_id AND budget_id = :budget_id 
                      ORDER BY name ASC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindParam(':budget_id', $budgetId, PDO::PARAM_INT);
        } else {
            $query = "SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY name ASC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function update($id, $name, $limitAmount, $color = null, $icon = null)
    {
        $query = "UPDATE {$this->table} SET name = :name, limit_amount = :limit_amount";

        if ($color !== null) {
            $query .= ", color = :color";
        }
        if ($icon !== null) {
            $query .= ", icon = :icon";
        }

        $query .= " WHERE id = :id";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':limit_amount', $limitAmount);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($color !== null) {
            $stmt->bindParam(':color', $color);
        }
        if ($icon !== null) {
            $stmt->bindParam(':icon', $icon);
        }

        return $stmt->execute();
    }

    public function delete($id)
    {
        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function getSpentAmount($categoryId)
    {
        $query = "SELECT SUM(amount) as total FROM transactions 
                  WHERE category_id = :category_id AND type = 'expense'";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function getCategoryWithSpending($userId, $budgetId = null, $startDate = null, $endDate = null)
    {
        $query = "SELECT c.*, 
                  COALESCE(SUM(CASE WHEN t.type = 'expense' AND t.date BETWEEN :start_date1 AND :end_date1 THEN t.amount ELSE 0 END), 0) as spent,
                  COALESCE(c.limit_amount - SUM(CASE WHEN t.type = 'expense' AND t.date BETWEEN :start_date2 AND :end_date2 THEN t.amount ELSE 0 END), c.limit_amount) as remaining
                  FROM {$this->table} c
                  LEFT JOIN transactions t ON c.id = t.category_id
                  WHERE c.user_id = :user_id";

        if ($budgetId !== null) {
            $query .= " AND c.budget_id = :budget_id";
        }

        $query .= " GROUP BY c.id ORDER BY c.name ASC";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':start_date1', $startDate);
        $stmt->bindParam(':end_date1', $endDate);
        $stmt->bindParam(':start_date2', $startDate);
        $stmt->bindParam(':end_date2', $endDate);

        if ($budgetId !== null) {
            $stmt->bindParam(':budget_id', $budgetId, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }
}

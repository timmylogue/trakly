<?php
require_once __DIR__ . '/../../config/database.php';

class Transaction
{
    private $db;
    private $table = 'transactions';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($userId, $amount, $date, $type = 'expense', $categoryId = null, $budgetId = null, $note = '', $tags = '', $receiptPath = null)
    {
        $query = "INSERT INTO {$this->table} (user_id, budget_id, category_id, amount, type, note, date, tags, receipt_path) 
                  VALUES (:user_id, :budget_id, :category_id, :amount, :type, :note, :date, :tags, :receipt_path)";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':budget_id', $budgetId, PDO::PARAM_INT);
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':note', $note);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':tags', $tags);
        $stmt->bindParam(':receipt_path', $receiptPath);

        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    public function findById($id)
    {
        $query = "SELECT t.*, c.name as category_name, c.color as category_color 
                  FROM {$this->table} t 
                  LEFT JOIN categories c ON t.category_id = c.id 
                  WHERE t.id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function findByUserId($userId, $limit = 100, $offset = 0)
    {
        $query = "SELECT t.*, c.name as category_name, c.color as category_color 
                  FROM {$this->table} t 
                  LEFT JOIN categories c ON t.category_id = c.id 
                  WHERE t.user_id = :user_id 
                  ORDER BY t.date DESC, t.created_at DESC 
                  LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function update($id, $amount, $date, $type, $categoryId = null, $note = '', $tags = '')
    {
        $query = "UPDATE {$this->table} SET amount = :amount, date = :date, type = :type, 
                  category_id = :category_id, note = :note, tags = :tags WHERE id = :id";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindParam(':note', $note);
        $stmt->bindParam(':tags', $tags);
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

    public function search($userId, $filters = [])
    {
        $query = "SELECT t.*, c.name as category_name, c.color as category_color 
                  FROM {$this->table} t 
                  LEFT JOIN categories c ON t.category_id = c.id 
                  WHERE t.user_id = :user_id";

        $params = [':user_id' => $userId];

        if (!empty($filters['category_id'])) {
            $query .= " AND t.category_id = :category_id";
            $params[':category_id'] = $filters['category_id'];
        }

        if (!empty($filters['type'])) {
            $query .= " AND t.type = :type";
            $params[':type'] = $filters['type'];
        }

        if (!empty($filters['start_date'])) {
            $query .= " AND t.date >= :start_date";
            $params[':start_date'] = $filters['start_date'];
        }

        if (!empty($filters['end_date'])) {
            $query .= " AND t.date <= :end_date";
            $params[':end_date'] = $filters['end_date'];
        }

        if (!empty($filters['search'])) {
            $query .= " AND (t.note LIKE :search OR t.tags LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $query .= " ORDER BY t.date DESC, t.created_at DESC";

        if (!empty($filters['limit'])) {
            $query .= " LIMIT :limit";
        }

        $stmt = $this->db->prepare($query);

        foreach ($params as $key => $value) {
            if ($key === ':category_id' || $key === ':user_id') {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value);
            }
        }

        if (!empty($filters['limit'])) {
            $stmt->bindValue(':limit', (int)$filters['limit'], PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getMonthlyTotal($userId, $year, $month, $type = 'expense')
    {
        $query = "SELECT SUM(amount) as total FROM {$this->table} 
                  WHERE user_id = :user_id AND type = :type 
                  AND YEAR(date) = :year AND MONTH(date) = :month";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->bindParam(':month', $month, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }

    public function getCategoryBreakdown($userId, $startDate, $endDate)
    {
        $query = "SELECT c.name, c.color, SUM(t.amount) as total, COUNT(t.id) as count
                  FROM {$this->table} t
                  LEFT JOIN categories c ON t.category_id = c.id
                  WHERE t.user_id = :user_id AND t.type = 'expense'
                  AND t.date BETWEEN :start_date AND :end_date
                  GROUP BY t.category_id
                  ORDER BY total DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}

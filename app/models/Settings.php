<?php
require_once __DIR__ . '/../../config/database.php';

class Settings
{
    private $db;
    private $table = 'settings';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByUserId($userId)
    {
        $query = "SELECT * FROM {$this->table} WHERE user_id = :user_id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function updateTheme($userId, $theme)
    {
        // Use INSERT ON DUPLICATE KEY UPDATE to handle cases where settings row doesn't exist
        $query = "INSERT INTO {$this->table} (user_id, theme) 
                  VALUES (:user_id, :theme) 
                  ON DUPLICATE KEY UPDATE theme = :theme2";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':theme', $theme);
        $stmt->bindParam(':theme2', $theme);

        return $stmt->execute();
    }

    public function update($userId, $data)
    {
        $fields = [];
        $params = [':user_id' => $userId];

        if (isset($data['currency'])) {
            $fields[] = 'currency = :currency';
            $params[':currency'] = $data['currency'];
        }

        if (isset($data['currency_symbol'])) {
            $fields[] = 'currency_symbol = :currency_symbol';
            $params[':currency_symbol'] = $data['currency_symbol'];
        }

        if (isset($data['theme'])) {
            $fields[] = 'theme = :theme';
            $params[':theme'] = $data['theme'];
        }

        if (isset($data['notifications_enabled'])) {
            $fields[] = 'notifications_enabled = :notifications_enabled';
            $params[':notifications_enabled'] = $data['notifications_enabled'];
        }

        if (isset($data['date_format'])) {
            $fields[] = 'date_format = :date_format';
            $params[':date_format'] = $data['date_format'];
        }

        if (empty($fields)) {
            return false;
        }

        $query = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        return $stmt->execute();
    }
}

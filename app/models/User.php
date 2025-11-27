<?php
require_once __DIR__ . '/../../config/database.php';

class User
{
    private $db;
    private $table = 'users';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($name, $email, $password)
    {
        $query = "INSERT INTO {$this->table} (name, email, password_hash) VALUES (:name, :email, :password_hash)";
        $stmt = $this->db->prepare($query);

        $passwordHash = password_hash($password, HASH_ALGO, ['cost' => HASH_COST]);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password_hash', $passwordHash);

        return $stmt->execute();
    }

    public function findByEmail($email)
    {
        $query = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function findById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function verifyPassword($email, $password)
    {
        $user = $this->findByEmail($email);

        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }

        return false;
    }

    public function updateProfile($userId, $name, $email)
    {
        $query = "UPDATE {$this->table} SET name = :name, email = :email WHERE id = :id";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updatePassword($userId, $newPassword)
    {
        $query = "UPDATE {$this->table} SET password_hash = :password_hash WHERE id = :id";
        $stmt = $this->db->prepare($query);

        $passwordHash = password_hash($newPassword, HASH_ALGO, ['cost' => HASH_COST]);
        $stmt->bindParam(':password_hash', $passwordHash);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function isPremium($userId)
    {
        $user = $this->findById($userId);

        if (!$user) {
            return false;
        }

        if ($user['is_premium'] && $user['premium_expires_at']) {
            return strtotime($user['premium_expires_at']) > time();
        }

        return false;
    }

    public function updatePremium($userId, $expiresAt)
    {
        $query = "UPDATE {$this->table} SET is_premium = 1, premium_expires_at = :expires_at WHERE id = :id";
        $stmt = $this->db->prepare($query);

        $stmt->bindParam(':expires_at', $expiresAt);
        $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function emailExists($email)
    {
        return $this->findByEmail($email) !== false;
    }
}

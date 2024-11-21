<?php
require_once 'Database.php';
require_once 'User.php';

class UserManager {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    
    public function createUser($username, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        return $stmt->execute([$username, $email, $hashedPassword]);
    }

    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? new User($user) : null;
    }

    public static function register($db, $username, $password, $email) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $db->prepare("INSERT INTO users (username, password, email, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$username, $hashedPassword, $email]);
        return new User([
            'id' => $db->lastInsertId(),
            'username' => $username,
            'password' => $hashedPassword,
            'email' => $email,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    public  function login($email, $password) {
        $stmt =$this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        var_dump($user);
        if ($user && password_verify($password, $user['password'])) {
            return new User($user);
        }
        return null;
    }
}

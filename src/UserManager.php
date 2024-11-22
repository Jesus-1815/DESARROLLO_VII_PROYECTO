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

    public function generateRecoveryToken($email) {
        $db = Database::getInstance()->getConnection();
        
        // Verificar si el email existe
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$user) {
            throw new Exception("No existe una cuenta con este email.");
        }
        
        // Generar token único
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Guardar token en la base de datos
        $stmt = $db->prepare("INSERT INTO recovery_tokens (user_id, token, expiry) VALUES (?, ?, ?)");
        $stmt->execute([$user['id'], $token, $expiry]);
        
        return $token;
    }

    public function verifyRecoveryToken($token) {
        $db = Database::getInstance()->getConnection();
        
        $stmt = $db->prepare("
            SELECT user_id 
            FROM recovery_tokens 
            WHERE token = ? 
            AND expiry > NOW() 
            AND used = FALSE
        ");
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePassword($userId, $newPassword) {
        $db = Database::getInstance()->getConnection();
        
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashedPassword, $userId]);
        
        // Marcar el token como usado
        $stmt = $db->prepare("UPDATE recovery_tokens SET used = TRUE WHERE user_id = ?");
        $stmt->execute([$userId]);
    }
}


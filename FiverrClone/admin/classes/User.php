<?php
require_once __DIR__ . "/Database.php";

class User extends Database {

    public function registerAdmin($username, $email, $password) {
        $hash = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO fiverr_clone_users (username, email, password, role) 
                VALUES (:username, :email, :password, 'fiverr_administrator')";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':username' => $username,
            ':email'    => $email,
            ':password' => $hash
        ]);
    }

    public function loginAdmin($email, $password) {
        $sql = "SELECT * FROM fiverr_clone_users WHERE email = :email AND role = 'fiverr_administrator' LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_id'] = $user['user_id'];
            $_SESSION['admin_username'] = $user['username'];
            $_SESSION['role'] = $user['role']; 
            return true;
        }
        return false;
    }

    public function isLoggedIn() {
        return isset($_SESSION['admin_id']);
    }

    public function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] === 'fiverr_administrator'; 
    }

public function logout() {
    session_destroy();
    header("Location: ../login.php"); 
    exit;
}

    public function getPDO() {
    return $this->pdo;
}
}

<?php
declare(strict_types=1);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';

class AdminAuth {
    private $conn;

    public function __construct() {
        $this->conn = Database::connect();
    }

    public function login(string $username, string $password): bool {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM felhasznalo WHERE nev = ? AND jogosultsag IN ('admin', 'editor')");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['jelszo'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['nev'];
                $_SESSION['role'] = $user['jogosultsag'];
                return true;
            }
            return false;
        } catch (PDOException $e) {
            error_log('Login Error: ' . $e->getMessage());
            return false;
        }
    }

    public static function isLoggedIn(): bool {
        return isset($_SESSION['user_id']) && 
               isset($_SESSION['role']) && 
               in_array($_SESSION['role'], ['admin', 'editor']);
    }

    public static function requireAdminOrEditor(): void {
        if (!self::isLoggedIn()) {
            header('Location: login.php');
            exit();
        }
    }

    public static function requireAdminOnly(): void {
        if (!self::isLoggedIn() || $_SESSION['role'] !== 'admin') {
            header('Location: unauthorized.php');
            exit();
        }
    }

    public function logout(): void {
        $_SESSION = [];
        session_destroy();
        header('Location: login.php');
        exit();
    }

    public static function checkUserRoleConsistency(): void {
        if (!isset($_SESSION['user_id'])) {
            return;
        }

        try {
            $conn = Database::connect();
            $stmt = $conn->prepare("SELECT jogosultsag FROM felhasznalo WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch();

            if (!$user || !in_array($user['jogosultsag'], ['admin', 'editor'])) {
                $_SESSION = [];
                session_destroy();
                header('Location: unauthorized.php');
                exit();
            }
        } catch (PDOException $e) {
            error_log('Role Check Error: ' . $e->getMessage());
            $_SESSION = [];
            session_destroy();
            header('Location: login.php');
            exit();
        }
    }
}

AdminAuth::checkUserRoleConsistency();
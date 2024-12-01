<?php
declare(strict_types=1);
session_start();

require_once '../config/database.php';
require_once 'auth.php';

class AuthCheck {
    private $conn;

    public function __construct() {
        $this->conn = Database::connect();
    }

    public function checkUserStatus(): void {
        if (!isset($_SESSION['user_id'])) {
            return;
        }

        try {
            $stmt = $this->conn->prepare("SELECT jogosultsag, aktiv FROM felhasznalo WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                $this->forceLogout("Felhasználó nem található");
            }

            if ($user['aktiv'] == 0) {
                $this->forceLogout("Felhasználói fiók inaktív");
            }

            if (!in_array($user['jogosultsag'], ['admin', 'editor'])) {
                $this->forceLogout("Jogosultság megváltozott");
            }
        } catch (PDOException $e) {
            error_log('Auth Check Error: ' . $e->getMessage());
            $this->forceLogout("Adatbázis hiba");
        }
    }

    private function forceLogout(string $reason): void {
        error_log("Kényszerű kijelentkezés: " . $reason . " - Felhasználó ID: " . $_SESSION['user_id']);

        $_SESSION = [];
        session_destroy();

        header('Location: unauthorized.php');
        exit();
    }
}

if (AdminAuth::isLoggedIn()) {
    $authCheck = new AuthCheck();
    $authCheck->checkUserStatus();
}
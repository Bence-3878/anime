<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function checkLogin()
{

}

function checkAdmin()
{
    if (!isset($_SESSION["user_id"]) || ($_SESSION["jog"] !== "admin" && $_SESSION["jog"] !== "editor")) {
        header("Location: index.php");
        exit();
    }
}

function checkSessionRefresh()
{
    if (isset($_SESSION["user_id"])) {
        try {
            require_once 'config/database.php';
            $pdo = Database::connect();
            
            $sql = "SELECT nev, jogosultsag FROM felhasznalo WHERE id = :user_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $_SESSION["user_id"]);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                if ($_SESSION["user_name"] !== $user["nev"] || $_SESSION["jog"] !== $user["jogosultsag"]) {
                    $_SESSION["user_name"] = $user["nev"];
                    $_SESSION["jog"] = $user["jogosultsag"];
                }
            }
            $pdo = null;
        } catch (Exception $e) {
            error_log("Session refresh error: " . $e->getMessage());
        }
    }
}

checkSessionRefresh();
?>

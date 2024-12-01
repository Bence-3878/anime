<?php
session_start();

if (isset($_SESSION["user_id"])) {
    header("Location: profil.php");
    exit();
}

$errors = [];

if (!empty($_POST)) {
    require_once 'config/Database.php';

    try {
        $pdo = Database::connect();

        if (empty($_POST["username"])) {
            $errors["username"] = "Név megadása kötelező";
        }
        if (empty($_POST["password"])) {
            $errors["password"] = "Jelszó megadása kötelező";
        }

        if (empty($errors["username"]) && empty($errors["password"])) {
            $username = $_POST["username"];
            $password = $_POST["password"];

            $sql = "SELECT * FROM felhasznalo WHERE nev = :nev";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":nev", $username);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                if (password_verify($password, $user["jelszo"])) {
                    $_SESSION["user_id"] = $user["id"];
                    $_SESSION["user_name"] = $user["nev"];
                    $_SESSION["jog"] = $user["jogosultsag"];
                    header("Location: profil.php");
                    exit();
                } else {
                    $errors["password"] = "Helytelen felhasználónév vagy jelszó.";
                }
            } else {
                $errors["username"] = "Helytelen felhasználónév vagy jelszó.";
            }
        }
    } catch (Exception $e) {
        $errors["error"] = "Hiba történt: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <title>Bejelentkezés</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container">
        <?php require_once 'includes/header.php'; ?>
        <main class="main-sectio">
            <form action="bejelentkezes.php" method="post">
                <label for="username">Felhasználónév:</label>
                <?php if (!empty($errors["username"])): ?>
                    <span class="error"> <?= $errors["username"] ?></span>
                <?php endif; ?>
                <input type="text" id="username" name="username" value="<?= $_POST['username'] ?? '' ?>" required>

                <label for="password">Jelszó:</label>
                <?php if (!empty($errors["password"])): ?>
                    <span class="error"> <?= $errors["password"] ?></span>
                <?php endif; ?>
                <input type="password" id="password" name="password" required>

                <input type="submit" value="Bejelentkezés">
            </form>
        </main>
    </div>
</body>

</html>
<?php
session_start();

if (isset($_SESSION["user_id"])) {
    header("Location: profil.php");
    exit();
}

$errors = [];

require_once 'config/Database.php'; 

$pdo = Database::connect();

if (!empty($_POST)) {

    if (empty($_POST["nev"])) {
        $errors["nev"] = "név megadása kötelező";
    }

    if (empty($_POST["jelszo"])) {
        $errors["jelszo"] = "jelszó megadása kötelező";
    }

    if (empty($_POST["jelszo_2"])) {
        $errors["jelszo_2"] = "jelszó megadása kötelező";
    }

    if ($_POST["jelszo"] != $_POST["jelszo_2"]) {
        $errors["jelszo"] = "a jelszavak nem egyeznek";
    }

    if (empty($errors)) {
        $username = $_POST["nev"];
        $password = $_POST["jelszo"];
        $hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $sql = "SELECT * FROM felhasznalo WHERE nev = :nev";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(":nev", $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $errors["nev"] = "A felhasználó név már foglalt!";
            } else {
                $sql = "INSERT INTO felhasznalo (nev, jelszo) VALUES (:nev, :jelszo)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(":nev", $username);
                $stmt->bindParam(":jelszo", $hash);
                $stmt->execute();

                $_SESSION["user_id"] = $pdo->lastInsertId();
                $_SESSION["user_name"] = $username;
                $_SESSION["jog"] = "user";
                header("Location: profil.php");
                exit();
            }
        } catch (PDOException $e) {
            $errors["error"] = "Hiba történt: " . $e->getMessage();
            error_log("Database Error: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <title>Regisztrárció</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container">
        <?php require_once 'includes/header.php'; ?>
        <main class="main-sectio">
            <?php if (isset($errors["nev"])): ?>
                <div class="error"><?= $errors["nev"]; ?></div>
            <?php endif; ?>
            <form method="post">
                <label for="nev">Felhasználó név</label>
                <input type="text" name="nev" id="nev" required>
                <?php if (isset($errors["nev"])): ?>
                    <div class="error"><?= $errors["nev"]; ?></div>
                <?php endif; ?>

                <label for="jelszo">Jelszó</label>
                <input type="password" name="jelszo" id="jelszo" required>
                <?php if (isset($errors["jelszo"])): ?>
                    <div class="error"><?= $errors["jelszo"]; ?></div>
                <?php endif; ?>

                <label for="jelszo_2">Jelszó még egyszer</label>
                <input type="password" name="jelszo_2" id="jelszo_2" required>
                <?php if (isset($errors["jelszo_2"])): ?>
                    <div class="error"><?= $errors["jelszo_2"]; ?></div>
                <?php endif; ?>

                <input type="submit" value="Regisztrálok">
            </form>
        </main>
    </div>
</body>

</html>

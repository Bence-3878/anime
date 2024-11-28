<?php
session_start();

// Ha a felhasználó már bejelentkezett, átirányítjuk a profil oldalra
if (isset($_SESSION['user_id'])) {
    header("Location: profil.php");
    exit();
}

$errors = array();

// A bejelentkezési űrlap kezelése
if (!empty($_POST)) {
    // Kapcsolódás az adatbázishoz
    $dsn = 'mysql:host=localhost;dbname=hazi;charset=utf8';
    $sqlusername = 'root';
    $sqlpassword = '';

    try {
        $pdo = new PDO($dsn, $sqlusername, $sqlpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        http_response_code(500);
        die('Sikertelen kapcsolódás: ' . $e->getMessage());
    }

    if (empty($_POST["username"])) {
        $errors["username"] = "név megadása kötelező";
    }
    if (empty($_POST["password"])) {
        $errors["password"] = "jelszó megadása kötelező";
    }

    if (empty($errors['username']) && empty($errors['password'])) {
        try {
            $username = $_POST['username'];
            $password = $_POST['password'];


            // Felhasználó keresése az adatbázisban
            $sql = "SELECT * FROM felhasznalo WHERE nev = :nev";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nev', $username);
            if (!$stmt->execute()) {
                throw new Exception('Hiba a SQL végrehajtása közben.');
            }

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Jelszó ellenőrzése
                if (password_verify($password, $user['jelszo'])) {
                    // Sikeres bejelentkezés
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['nev'];
                    $_SESSION['jog'] = $user['jogosultsag'];
                    header("Location: profil.php");
                    exit();
                } else {
                    // Helytelen jelszó
                    $errors['password'] = "Helytelen felhasználónév vagy jelszó.";
                }
            } else {
                // Felhasználó nem található
                $errors['username'] = "Helytelen felhasználónév vagy jelszó.";
            }

        } catch (Exception $e) {
            $errors['error'] = 'Hiba történt: ' . $e->getMessage();
        } finally {
            $pdo = null; // PDO kapcsolat törlése
        }
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Bejelentkezés</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <header class="row">
        <nav>
            <a class="menu" href="index.php">Kezdőlap</a>
            <a class="menu" href="szezon.php">Szezon</a>
            <a class="menu" href="kereso.php">Kereső</a>
            <a class="menu" href="regisztracio.php">Regisztráció</a>
            <a class="menu" href="bejelentkezes.php">Bejelenkezés</a>
        </nav>
    </header>
    <main class="login">
        <form action="bejelentkezes.php" method="post">
            <label for="username">Felhasználónév:</label>
            <?php if (!empty($errors['username'])): ?>
                <span class="error"> <?= $errors['username'] ?></span>
            <?php endif; ?>
            <?php if (!empty($_POST['username'])): ?>
                <input type="text" id="username" name="username" value="<?=$_POST['username']?>" required>
            <?php else: ?>
                <input type="text" id="username" name="username" required>
            <?php endif; ?>
            <label for="password">Jelszó:</label>
            <?php if (!empty($errors['password'])): ?>
                <span class="error"> <?= $errors['password'] ?></span>
            <?php endif; ?>
            <?php if (!empty($_POST['password'])): ?>
                <input type="password" id="password" name="password" value="<?=$_POST['password']?>" required>
            <?php else: ?>
                <input type="password" id="password" name="password" required>
            <?php endif; ?>
            <input type="submit" value="Bejelentkezés">
        </form>
    </main>
</div>
</body>
</html>
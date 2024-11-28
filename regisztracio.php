<?php

session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: profil.php");
    exit();
}

$errors = array();

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

    if (empty($_POST["nev"])) {
        $errors["nev"] = "név megadása kötelező";
    }
    if (empty($_POST["jelszo"])) {
        $errors["jelszo"] = "jelszó megadása kötelező";
    }

    if (empty($errors)) {
        $username = $_POST["nev"];
        $password = $_POST["jelszo"];
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "SELECT * FROM felhasznalo WHERE nev = :nev";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nev', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user){
            $sql = "INSERT INTO felhasznalo (nev, jelszo) VALUES (:nev, :jelszo)";
            $stmt = $pdo->prepare($sql);

            // Paraméterek kötése és végrehajtása
            $stmt->bindParam(':nev', $username);
            $stmt->bindParam(':jelszo', $hash);

            try {
                $stmt->execute();
                $_SESSION["user_id"] = $pdo->lastInsertId();
                $_SESSION["user_name"] = $username;
                header("Location: profil.php");
                exit();
            } catch (PDOException $e) {
                echo "Hiba történt: " . $e->getMessage();
            }
        }
    } else {
        // Hibák megjelenítése
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Regisztárció</title>
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
    <main class="regist">
        <?php if (isset($user)):
            if($user):?>
        <div class="error">A felhasználó név már foglalt!</div>
        <?php endif;endif ?>
        <form method="post">
            <label for="nev">Felhasználó név</label>
            <input type="text" name="nev" id="nev" required>

            <label for="jelszo">Jelszó</label>
            <input type="password" name="jelszo" id="jelszo" required>

            <input type="submit" value="Regisztrálok" >
        </form>
    </main>
</div>
</body>
</html>
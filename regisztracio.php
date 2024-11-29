<?php

session_start();

if (isset($_SESSION["user_id"])) {
    header("Location: profil.php");
    exit();
}

$errors = [];

if (!empty($_POST)) {
    // Kapcsolódás az adatbázishoz
    $dsn = "mysql:host=localhost;dbname=hazi;charset=utf8";
    $sqlusername = "root";
    $sqlpassword = "";

    try {
        $pdo = new PDO($dsn, $sqlusername, $sqlpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        http_response_code(500);
        die("Sikertelen kapcsolódás: " . $e->getMessage());
    }

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

        $sql = "SELECT * FROM felhasznalo WHERE nev = :nev";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":nev", $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            $sql =
                "INSERT INTO felhasznalo (nev, jelszo) VALUES (:nev, :jelszo)";
            $stmt = $pdo->prepare($sql);

            // Paraméterek kötése és végrehajtása
            $stmt->bindParam(":nev", $username);
            $stmt->bindParam(":jelszo", $hash);

            try {
                $stmt->execute();
                $_SESSION["user_id"] = $pdo->lastInsertId();
                $_SESSION["user_name"] = $username;
                $_SESSION["jog"] = "user";
                header("Location: profil.php");
                exit();
            } catch (PDOException $e) {
                echo "Hiba történt: " . $e->getMessage();
            }
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
    <main class="main-sectio">
        <?php if (isset($user)):
            if ($user): ?>
                <div class="error">A felhasználó név már foglalt!</div>
            <?php endif;
        endif; ?>
        <form method="post">
            <label for="nev">Felhasználó név</label>
            <input type="text" name="nev" id="nev" required>
            <?php if (isset($errors["nev"])): ?>
                <div class="error"><?php echo $errors["nev"]; ?></div>
            <?php endif; ?>

            <label for="jelszo">Jelszó</label>
            <input type="password" name="jelszo" id="jelszo" required>
            <?php if (isset($errors["jelszo"])): ?>
                <div class="error"><?php echo $errors["jelszo"]; ?></div>
            <?php endif; ?>

            <label for="jelszo">Jelszó még egyszer</label>
            <input type="password" name="jelszo_2" id="jelszo_2" required>
            <?php if (isset($errors["jelszo_2"])): ?>
                <div class="error"><?php echo $errors["jelszo_2"]; ?></div>
            <?php endif; ?>

            <input type="submit" value="Regisztrálok" >
        </form>
    </main>
</div>
</body>
</html>
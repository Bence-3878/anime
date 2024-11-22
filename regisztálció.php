<?php
$errors = array();

if (!empty($_POST)) {
    // Kapcsolódás az adatbázishoz
    $dsn = 'mysql:host=localhost;dbname=hazi';
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO($dsn, $username, $password);
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
        $nev = $_POST["nev"];
        $jelszo = $_POST["jelszo"];
        $hash = password_hash($jelszo, PASSWORD_DEFAULT);

        $sql = "INSERT INTO felhasznalo (felhasznalo_nev, jelszo) VALUES (:felhasznalonev, :jelszo)";
        $stmt = $pdo->prepare($sql);

        // Paraméterek kötése és végrehajtása
        $stmt->bindParam(':felhasznalonev', $nev);
        $stmt->bindParam(':jelszo', $hash);

        try {
            $stmt->execute();
            echo "Sikeres regisztráció!";
        } catch (PDOException $e) {
            echo "Hiba történt: " . $e->getMessage();
        }
    } else {
        // Hibák megjelenítése
        foreach ($errors as $error) {
            echo "<p>$error</p>";
        }
    }

    // PDO kapcsolat bezárása
    unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Regisztárció</title>
</head>
<body>
<div class="container">
    <header class="row">
        <a class="menu" href="index.php">Kezdőlap</a>
        <a class="menu" href="sezon.html">Szezon</a>
        <a class="menu" href="kereso.html">Kereső</a>
        <a class="menu" href="bejelentkezés.html">Bejelentkezés</a>
        <a class="menu" href="regisztálció.html">Regisztráció</a>
    </header>
    <main class="regist">
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
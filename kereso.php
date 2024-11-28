<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>kereső</title>
    <link rel="icon" href="képek/ikon.jpg" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <header class="row">
        <nav>
            <a class="menu" href="index.php">Kezdőlap</a>
            <a class="menu" href="szezon.php">Szezon</a>
            <a class="menu" href="kereso.php">Kereső</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a class="menu" href="profil.php">Profil</a>
                <?php if ($_SESSION['jog'] = 'admin' || $_SESSION['jog'] = 'editor'): ?>
                    <a class="menu" href="admin.php">Admin</a>
                <?php endif; ?>
            <?php else: ?>
                <a class="menu" href="regisztracio.php">Regisztráció</a>
                <a class="menu" href="bejelentkezes.php">Bejelenkezés</a>
            <?php endif; ?>
        </nav>
    </header>
</div>
<div class="container" id="kereso">
    <?php
        if (isset($_POST['kereso'])) :
            $kereso = $_POST['kereso'];
    ?>
    <form action="kereso.php" method="post">
        <input type="text" name="kereso" required value="<?=$kereso?>">
        <input type="submit" value="keresés">
    </form>
    <?php
        $dsn = 'mysql:host=localhost;dbname=hazi;charset=utf8';
        $sqlusername = 'root';
        $sqlpassword = '';

        try {
            $pdo = new PDO($dsn, $sqlusername, $sqlpassword);
            $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Hibakereskődve felhasználói rétegben közlünk egyszerű üzenetet
            echo 'Hiba történt az adatbázis kapcsolódás során. Kérjük, próbálja meg később.';

            // Hibanaplózás a részletekkel
            error_log('Adatbázis hiba: ' . $e->getMessage());
        }
        $sql = "SELECT * FROM anime WHERE romanji_cim LIKE :kereso1 OR angol_cim LIKE :kereso2";
        $stmt = $pdo->prepare($sql);
        $likeKereso = '%' . $kereso . '%';
        $stmt->bindParam(':kereso1', $likeKereso);
        $stmt->bindParam(':kereso2', $likeKereso);
        $stmt->execute();
        $anime = $stmt->fetchAll();

    ?>
        <main class="row">
            <ul class="anime-list">
                <?php foreach ($anime as $anime_elem): ?>
                    <li class="anime-item"><img src="<?=$anime_elem['poszter']?>" class="anime-poszter">
                        <a href="anime.php?id=<?=$anime_elem['id']?>"><?=$anime_elem['romanji_cim']?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </main>
    <?php else: ?>
            <form action="kereso.php" method="post">
                <input type="text" name="kereso" required>
                <input type="submit" value="keresés">
            </form>
    <?php endif; ?>
</div>
</body>
</html>
<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: bejelentkezes.php');
}

if (isset($_POST['kijelentkezes']) && isset($_SESSION['user_id'])) {
    $_SESSION = array();
    session_destroy();
    header('Location: index.php');
}
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];


$errors = array();
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title><?=$user_name?></title>
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
            <?php else: ?>
                <a class="menu" href="regisztracio.php">Regisztráció</a>
                <a class="menu" href="bejelentkezes.php">Bejelenkezés</a>
            <?php endif; ?>
        </nav>
    </header>
    <form action="profil.php" method="post">
        <input type="submit" class="kijelenkezes" name="kijelentkezes" value="kijelentkezes">
    </form>
</div>
</body>
</html>

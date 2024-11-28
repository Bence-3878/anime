<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: bejelentkezes.php');
}
if ($_SESSION['jog'] != 'admin' && $_SESSION['jog'] != 'editor' ) {
    header('Location: index.php');
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
            <?php if (isset($_SESSION['user_id'])): ?>
                <a class="menu" href="profil.php">Profil</a>
                <?php if ($_SESSION['jog'] == 'admin' || $_SESSION['jog'] == 'editor'): ?>
                    <a class="menu" href="admin.php">Admin</a>
                <?php endif; ?>
            <?php else: ?>
                <a class="menu" href="regisztracio.php">Regisztráció</a>
                <a class="menu" href="bejelentkezes.php">Bejelenkezés</a>
            <?php endif; ?>
        </nav>
    </header>
</div>
</body>
</html>

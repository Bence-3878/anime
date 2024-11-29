<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: bejelentkezes.php');
    exit();
}
if ($_SESSION['jog'] != 'admin' && $_SESSION['jog'] != 'editor' ) {
    header('Location: index.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Admin felület</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container">
    <header class="row">
        <nav>
            <a class="menu" href="index.php">Kezdőlap</a>
            <a class="menu" href="szezon.php">Szezon</a>
            <a class="menu" href="kereso.php">Kereső</a>
            <?php if (isset($_SESSION["user_id"])): ?>
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

    <div class="row">
        <div class="row">
            <nav class="second-menu">
                <a href="#" class="menu" data-database="anime">anime</a>
                <a href="#" class="menu" data-database="felhasznalo">felhasználó</a>
                <a href="#" class="menu" data-database="studio">studió</a>
                <a href="#" class="menu" data-database="episodes">epizod</a>
            </nav>
        </div>

        <div class="row" id="database-list">
            <!-- Content will be dynamically loaded here -->
        </div>





    </div>

</div>
</body>
<script src="js/adatbazis.js"></script>
</html>

<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Nagy Házi</title>
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
            <?php else: ?>
                <a class="menu" href="regisztracio.php">Regisztráció</a>
                <a class="menu" href="bejelentkezes.php">Bejelenkezés</a>
            <?php endif; ?>
        </nav>
    </header>
    <main class="main">
        <section id="introduction">
            <h2>Projekt Bemutató</h2>
            <p>Ez a projekt az egyetem Info 2 tárgyának nagy házi feladata, melynek célja egy dinamikus weboldal
                készítése PHP segítségével.
                A házi feladat során különböző webfejlesztési technikákat kell alkalmaznunk, például adatbázis-kezelést,
                session kezelést és
                responsív dizájn kialakítását.</p>
        </section>
    </main>
</div>
</body>
</html>
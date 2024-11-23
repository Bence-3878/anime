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
            <?php else: ?>
                <a class="menu" href="regisztracio.php">Regisztráció</a>
                <a class="menu" href="bejelentkezes.php">Bejelenkezés</a>
            <?php endif; ?>
        </nav>
    </header>
    <main class="login">
        <form action="login.php" method="post">

            <label for="username">Felhasználónév:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Jelszó:</label>
            <input type="password" id="password" name="password" required>

            <input type="submit" value="Bejelentkezés">
        </form>
    </main>
</div>
</body>
</html>
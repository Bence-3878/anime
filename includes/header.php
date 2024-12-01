<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once './session_check.php';  
checkLogin(); 
?>

<div id="loader">
    <div class="spinner"></div>
</div>
<header class="row">
    <nav>
        <a class="menu" href="index.php">Kezdőlap</a>
        <a class="menu" href="szezon.php">Szezon</a>
        <a class="menu" href="kereso.php">Kereső</a>
        <?php if (isset($_SESSION["user_id"])): ?>
            <a class="menu" href="profil.php">Profil</a>
            <?php if ($_SESSION["jog"] == "admin" || $_SESSION["jog"] == "editor"): ?>
                <a class="menu" href="admin">Admin Panel</a>
            <?php endif; ?>
        <?php else: ?>
            <a class="menu" href="regisztracio.php">Regisztráció</a>
            <a class="menu" href="bejelentkezes.php">Bejelentkezés</a>
        <?php endif; ?>
    </nav>
</header>

<script>
    window.addEventListener('load', function () {
        const loader = document.getElementById('loader');
        const content = document.querySelector('.content');

        loader.classList.add('hidden');

        setTimeout(() => {
            content.style.visibility = 'visible';
        }, 1000);
    });
</script>

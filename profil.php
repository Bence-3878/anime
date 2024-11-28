<?php

session_start();

if (!isset($_SESSION['user_id']) && !isset($_GET['id']) && !isset($_GET['name']))
    header('Location: bejelentkezes.php');

elseif (isset($_POST['kijelentkezes']) && isset($_SESSION['user_id'])) {
    $_SESSION = array();
    session_destroy();
    header('Location: index.php');
    exit();
}
elseif (isset($_GET['id']) && isset($_SESSION['user_id']) && $_SESSION['user_id'] == $_GET['id'])
    $user_id = $_SESSION['user_id'];

elseif (isset($_GET['id']))
    $user_id = $_GET['id'];

elseif (isset($_GET['name']))
    $user_name = $_GET['name'];

elseif(isset($_SESSION['user_id']) && !isset($_GET['id']) && !isset($_GET['name']))
    $user_id = $_SESSION['user_id'];
else {
    http_response_code(400);
    die('Az id és a felhasználó név nem egy felhasználóhoz tartozik');
}


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

if (isset($user_id)) {
    $sqlfelhasznalo = 'SELECT * FROM felhasznalo WHERE id = :id';
    $stmt = $pdo->prepare($sqlfelhasznalo);
    $stmt->bindParam(':id', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$user){
        http_response_code(404);
        die('felhasználó nem található ilyen id-val');
    }
    $user_name = $user['nev'];
} elseif (isset($user_name)) {
    $sqlfelhasznalo = 'SELECT * FROM felhasznalo WHERE nev = :nev';
    $stmt = $pdo->prepare($sqlfelhasznalo);
    $stmt->bindParam(':nev', $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!$user){
        http_response_code(404);
        die('felhasználó nem található ilyen névvel');
    }
    $user_name = $user['id'];
}

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
                <?php if ($_SESSION['jog'] = 'admin' || $_SESSION['jog'] = 'editor'): ?>
                    <a class="menu" href="admin.php">Admin</a>
                <?php endif; ?>
            <?php else: ?>
                <a class="menu" href="regisztracio.php">Regisztráció</a>
                <a class="menu" href="bejelentkezes.php">Bejelenkezés</a>
            <?php endif; ?>
        </nav>
    </header>

    <div class="row">
        <aside class="sidebar">
            <h2>Felhasználói adatok</h2>
            <p><strong>Név:</strong> <?= $user_name ?></p>
            <p><strong>Felhasználó ID:</strong> <?= $user_id ?></p>
            <form action="profil.php" method="post">
                <input type="submit" class="kijelenkezes" name="kijelentkezes" value="kijelentkezes">
            </form>
        </aside>
        <main class="content">
            <h2>Felhasználói lista</h2>
            <!-- Itt adjuk hozzá a felhasználó listáját -->
        </main>
    </div>
</div>
</body>
</html>

<?php
session_start();

$dsn = 'mysql:host=localhost;dbname=hazi;charset=utf8';
$sqlusername = 'root';
$sqlpassword = '';

try {
    $pdo = new PDO($dsn, $sqlusername, $sqlpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Hiba: ' . $e->getMessage();
}
date_default_timezone_set('Europe/Budapest');
$ev=date("Y");
$honap=date("m");
switch ($honap) {
    case '01':
    case '02':
    case '03':
        $szezon='tel';
        break;
    case '04':
    case '05':
    case '06':
        $szezon='tavasz';
        break;
    case '07':
    case '08':
    case '09':
        $szezon='nyar';
        break;
    case '10':
    case '11':
    case '12':
        $szezon='osz';
}


$sql = 'SELECT * FROM anime WHERE szezon_id=(SELECT id FROM szezon WHERE ev=:ev AND szezon=:szezon)';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':ev', $ev);
$stmt->bindParam(':szezon', $szezon);
$stmt->execute();
$anime=$stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=$ev.' '. $szezon?> szezon</title>
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
    </div>
    <div class="container" id="szezon">
        <main class="row">
            <?php foreach ($anime as $anime_elem): ?>
            <?= $anime_elem['romanji_cim'] ?>
            <?php endforeach; ?>
        </main>
    </div>
</body>
</html>
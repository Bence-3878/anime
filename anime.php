<?php
if(empty($_GET['id'])){
    http_response_code(400);
    exit("400 nincs ilyen id");
}
else {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if ($id === false) {
        http_response_code(400);
        exit("400 nincs ilyen id");
    }
}
try{
    $db = new PDO('mysql:host=localhost;dbname=hazi;charset=utf8','root','');
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    die($e->getMessage());
}
$sql = "SELECT * FROM Anime WHERE id = :id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if($result->rowCount()!=1){
http_response_code(404);
    if (!$result) {
        http_response_code(404);
        exit("$id nincs ilyen id");
    }

}
?>
<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($result['romanji_cim']); ?></title>
</head>

<body>
<header>
    <nav>
        <a class="menu" href="index.html">Kezdőlap</a>
        <a class="menu" href="sezon.html">Szezon</a>
        <a class="menu" href="kereso.html">Kereső</a>
        <a class="menu" href="bejelentkezes.html">Bejelentkezés</a>
        <a class="menu" href="regisztracio.php">Regisztráció</a>
    </nav>
</header>

<main>
    <section class="anime-profile">
        <h1><?= htmlspecialchars($result['romanji_cim']); ?></h1>
        <h2><?= htmlspecialchars($result['angol_cim']); ?></h2>
        <p><?= htmlspecialchars($result['leiras']); ?></p>
        <h2>Epizódok</h2>
        <ul>
            <?php
            $sqlEpisodes = "SELECT * FROM episodes WHERE anime_id = :id ORDER BY episode_number";
            $stmtEpisodes = $db->prepare($sqlEpisodes);
            $stmtEpisodes->bindParam(':id', $id, PDO::PARAM_INT);
            $stmtEpisodes->execute();
            $episodes = $stmtEpisodes->fetchAll(PDO::FETCH_ASSOC);
            foreach($episodes as $episode) {
                echo "<li>Epizód " . htmlspecialchars($episode['episode_number']) . ": " . htmlspecialchars($episode['title']) .
                    " (" . htmlspecialchars($episode['duration']) . ") - " . htmlspecialchars($episode['air_date']) . "</li>";
            }
            ?>
        </ul>
    </section>
</main>

<footer>
    <p>&copy; 2024 Anime Oldal. Minden jog fenntartva.</p>
</footer>
</body>

</html>
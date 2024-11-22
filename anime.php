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
$sql = "SELECT * FROM anime WHERE id = :id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$result){
    http_response_code(404);
    exit("$id nincs ilyen id");

}
?>
<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($result['romanji_cim']); ?></title>
    <link rel="icon" href="képek/ikon.jpg" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
</head>

<body>
<header>
    <nav>
        <a class="menu" href="index.php">Kezdőlap</a>
        <a class="menu" href="szezon.php">Szezon</a>
        <a class="menu" href="kereso.php">Kereső</a>
        <a class="menu" href="regisztracio.php">Regisztráció</a>
    </nav>
</header>

<main>
    <section class="anime-profile">
        <h1><?= htmlspecialchars($result['romanji_cim']); ?></h1>
        <h2><?= htmlspecialchars($result['angol_cim']); ?></h2>
        <?php if (isset($result['poszter'], $result['romanji_cim'])): ?>
            <img src="<?= htmlspecialchars($result['poszter'], ENT_QUOTES, 'UTF-8'); ?>"
                 alt="<?= htmlspecialchars($result['romanji_cim'], ENT_QUOTES, 'UTF-8'); ?>">
        <?php endif; ?>
        <p><?= htmlspecialchars($result['leiras']); ?></p>
        <a href="anime.php?id=<?= htmlspecialchars($result['folytatas_id']); ?>" >
            <?php
            $sqlNext = "SELECT romanji_cim FROM anime WHERE id = :id";
            $stmtNext = $db->prepare($sqlNext);
            $stmtNext->bindParam(':id', $result['folytatas_id'], PDO::PARAM_INT);
            $stmtNext->execute();
            echo htmlspecialchars($stmtNext->fetchColumn());
            ?></a>
        <a href="anime.php?id=<?= htmlspecialchars($result['elozmeny_id']); ?>" >
            <?php
            $sqlPrev = "SELECT romanji_cim FROM anime WHERE id = :id";
            $stmtPrev = $db->prepare($sqlPrev);
            $stmtPrev->bindParam(':id', $result['elozmeny_id'], PDO::PARAM_INT);
            $stmtPrev->execute();
            echo htmlspecialchars($stmtPrev->fetchColumn());
            ?>
        </a>
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
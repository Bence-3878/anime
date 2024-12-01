<?php
declare(strict_types=1);

require_once 'config/Database.php'; 

$pdo = Database::connect();

$animeResults = [];
$errorMessage = '';

if (isset($_GET['kereso'])) {
    $kereso = trim($_GET['kereso']);

    try {
        if (empty($kereso)) {
            throw new Exception("A keresési mező nem lehet üres.");
        }

        $sql = "SELECT * FROM anime WHERE 
                romanji_cim LIKE :kereso1 OR 
                angol_cim LIKE :kereso2";
        $stmt = $pdo->prepare($sql);
        $likeKereso = "%" . $kereso . "%";
        $stmt->bindParam(":kereso1", $likeKereso);
        $stmt->bindParam(":kereso2", $likeKereso);
        $stmt->execute();

        $animeResults = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($animeResults)) {
            throw new Exception("Nincsenek találatok a keresési feltételnek.");
        }
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Kereső</title>
    <link rel="icon" href="kép/ikon.jpg" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container" id="kereso">
        <?php require_once 'includes/header.php'; ?>
        <br>
        <form action="kereso.php" method="get">
            <input type="text" name="kereso" required
                value="<?= isset($_GET['kereso']) ? htmlspecialchars($_GET['kereso']) : '' ?>">
            <input type="submit" value="Keresés">
        </form>

        <?php if (isset($_GET['kereso'])): ?>
            <?php if ($errorMessage): ?>
                <p><?= htmlspecialchars($errorMessage) ?></p>
            <?php else: ?>
                <p>Keresett anime: <?= htmlspecialchars($kereso) ?></p>
                <main class="anime-grid">
                    <?php if (!empty($animeResults)): ?>
                        <?php foreach ($animeResults as $anime_elem): ?>
                            <div class="anime-card">
                                <img src="<?= htmlspecialchars($anime_elem["poszter"]) ?>" alt="Anime Poster" class="anime-card-image">
                                <div class="anime-card-content">
                                    <h3><?= htmlspecialchars($anime_elem["romanji_cim"]) ?></h3>
                                    <a href="anime.php?id=<?= $anime_elem["id"] ?>" class="anime-card-link">Részletek</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Nincsenek találatok a kereséshez: <?= htmlspecialchars($kereso) ?></p>
                    <?php endif; ?>
                </main>
            <?php endif; ?>
        <?php else: ?>
            <p>Írja be, amit keres:</p>
        <?php endif; ?>
    </div>
</body>

</html>

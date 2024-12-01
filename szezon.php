<?php
declare(strict_types=1);

require_once 'config/database.php';

class SeasonManager {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::connect();
    }

    private function determineCurrentSeason() {
        $month = date('m');
        return match(true) {
            in_array($month, ['01', '02', '03']) => 'tel',
            in_array($month, ['04', '05', '06']) => 'tavasz',
            in_array($month, ['07', '08', '09']) => 'nyar',
            in_array($month, ['10', '11', '12']) => 'osz',
            default => null
        };
    }

    public function getAnimeList($seasonId = null) {
        try {
            if ($seasonId === null) {
                $currentYear = date('Y');
                $currentSeason = $this->determineCurrentSeason();

                $query = "SELECT * FROM anime 
                          WHERE szezon_id = (
                              SELECT id FROM szezon 
                              WHERE ev = :year AND szezon = :season
                          )";
                
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([
                    ':year' => $currentYear,
                    ':season' => $currentSeason
                ]);
            } else {
                $query = "SELECT a.*, sz.ev, sz.szezon 
                          FROM anime a
                          JOIN szezon sz ON a.szezon_id = sz.id
                          WHERE a.szezon_id = :seasonId";
                
                $stmt = $this->pdo->prepare($query);
                $stmt->execute([':seasonId' => $seasonId]);
            }

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Anime Listing Error: " . $e->getMessage());
            return [];
        }
    }

    public function getSeasonDetails($seasonId) {
        try {
            $query = "SELECT * FROM szezon WHERE id = :seasonId";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute([':seasonId' => $seasonId]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Season Details Error: " . $e->getMessage());
            return null;
        }
    }
}

try {
    $seasonManager = new SeasonManager();
    $seasonId = $_GET['id'] ?? null;
    
    if ($seasonId) {
        $seasonDetails = $seasonManager->getSeasonDetails($seasonId);
        $animeList = $seasonManager->getAnimeList($seasonId);
        $year = $seasonDetails['ev'];
        $season = $seasonDetails['szezon'];
    } else {
        $animeList = $seasonManager->getAnimeList();
        $year = date('Y');
        $season = match(date('m')) {
            '01', '02', '03' => 'téli',
            '04', '05', '06' => 'tavaszi',
            '07', '08', '09' => 'nyári',
            '10', '11', '12' => 'őszi'
        };
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($year . ' ' . $season . ' szezon') ?></title>
    <link rel="icon" href="kép/ikon.jpg" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php require_once 'includes/header.php'; ?>
    </div>

    <div class="container">
        <div class="season-header">
            <h1><?= htmlspecialchars($year . ' ' . $season . ' szezon') ?></h1>
        </div>

        <main class="anime-grid">
            <?php if (!empty($animeList)): ?>
                <?php foreach ($animeList as $anime): ?>
                    <div class="anime-card">
                        <img src="<?= htmlspecialchars($anime['poszter']) ?>" alt="<?= htmlspecialchars($anime['romanji_cim']) ?>" class="anime-card-image">
                        <div class="anime-card-content">
                            <h3><?= htmlspecialchars($anime['romanji_cim']) ?></h3>
                            <a href="anime.php?id=<?= $anime['id'] ?>" class="anime-card-link">Részletek</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nincsenek animék ebben a szezonban.</p>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>

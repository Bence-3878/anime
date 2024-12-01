<?php
require_once 'includes/header.php';
require_once '../config/database.php';

if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'editor') {
    header('Location: index.php');
    exit();
}

if (isset($_GET['delete']) && $_SESSION['role'] === 'admin') {
    try {
        $conn = Database::connect();
        
        $stmt = $conn->prepare("DELETE FROM anime_has_studio WHERE anime_id = :id");
        $stmt->execute([':id' => $_GET['delete']]);
        
        $stmt = $conn->prepare("DELETE FROM anime WHERE id = :id");
        $stmt->execute([':id' => $_GET['delete']]);
        
        header('Location: anime_list.php?success=1');
        exit();
    } catch (PDOException $e) {
        $error = 'Hiba történt a törlés során: ' . $e->getMessage();
    }
}

try {
    $conn = Database::connect();
    $query = $conn->query("
        SELECT a.id, a.romanji_cim, a.angol_cim, a.statusz, a.epizod_szam, 
               s.ev, s.szezon, st.nev as studio_nev 
        FROM anime a
        LEFT JOIN szezon s ON a.szezon_id = s.id
        LEFT JOIN anime_has_studio ahs ON a.id = ahs.anime_id
        LEFT JOIN studio st ON ahs.studio_id = st.id
        ORDER BY a.romanji_cim
    ");
    $animek = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Anime lista lekérdezés hiba: ' . $e->getMessage());
    $animek = [];
    $error = 'Nem sikerült lekérdezni az animéket.';
}
?>

<div class="admin-container">
    <div class="card">
        <div class="card-header">
            <h1>Anime Lista</h1>
            <a href="add_anime.php" class="btn">Új Anime Hozzáadása</a>
        </div>

        <?php if (isset($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="success-message">Művelet sikeresen végrehajtva!</div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="anime-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Romanji Cím</th>
                        <th>Angol Cím</th>
                        <th>Státusz</th>
                        <th>Epizódok</th>
                        <th>Szezon</th>
                        <th>Stúdió</th>
                        <th>Műveletek</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($animek as $anime): ?>
                        <tr>
                            <td><?php echo $anime['id']; ?></td>
                            <td><?php echo htmlspecialchars($anime['romanji_cim']); ?></td>
                            <td><?php echo htmlspecialchars($anime['angol_cim'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($anime['statusz'])); ?></td>
                            <td><?php echo $anime['epizod_szam'] ?? 'N/A'; ?></td>
                            <td><?php echo $anime['ev'] ? $anime['ev'] . ' ' . ucfirst($anime['szezon']) : 'N/A'; ?></td>
                            <td><?php echo htmlspecialchars($anime['studio_nev'] ?? 'N/A'); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="anime_edit.php?id=<?php echo $anime['id']; ?>" class="btn btn-edit">Szerkesztés</a>
                                    <?php if ($_SESSION['role'] === 'admin'): ?>
                                        <a href="?delete=<?php echo $anime['id']; ?>" 
                                           class="btn btn-delete" 
                                           onclick="return confirm('Biztosan törölni szeretné ezt az animét?');">Törlés</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
<?php
ob_start();
session_start();
require_once 'includes/header.php';
require_once '../config/database.php';

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'editor')) {
    ob_end_clean();
    header('Location: index.php');
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    ob_end_clean();
    header('Location: anime_list.php');
    exit();
}

$anime_id = $_GET['id'];
$error = null;

try {
    $conn = Database::connect();

    $stmt = $conn->prepare("
        SELECT a.*, s.id as studio_id 
        FROM anime a
        LEFT JOIN anime_has_studio ahs ON a.id = ahs.anime_id
        LEFT JOIN studio s ON ahs.studio_id = s.id
        WHERE a.id = :id
    ");
    $stmt->execute([':id' => $anime_id]);
    $anime = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$anime) {
        throw new Exception('Anime nem található');
    }

    // Fetch episodes for this anime
    $episodesStmt = $conn->prepare("
        SELECT id, episode_number, title, duration, air_date 
        FROM episodes 
        WHERE anime_id = :anime_id 
        ORDER BY episode_number
    ");
    $episodesStmt->execute([':anime_id' => $anime_id]);
    $episodes = $episodesStmt->fetchAll(PDO::FETCH_ASSOC);

    $studiosQuery = $conn->query("SELECT id, nev FROM studio ORDER BY nev");
    $studios = $studiosQuery->fetchAll(PDO::FETCH_ASSOC);

    $seasonQuery = $conn->query("SELECT id, ev, szezon FROM szezon ORDER BY ev DESC, szezon");
    $seasons = $seasonQuery->fetchAll(PDO::FETCH_ASSOC);

} catch (Exception $e) {
    error_log('Anime szerkesztés oldal hiba: ' . $e->getMessage());
    ob_end_clean();
    header('Location: anime_list.php?error=1');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conn = Database::connect();

        $stmt = $conn->prepare("
            UPDATE anime 
            SET romanji_cim = :romanji_cim, 
                angol_cim = :angol_cim, 
                leiras = :leiras, 
                hossza = :hossza, 
                poszter = :poszter, 
                epizod_szam = :epizod_szam, 
                kezdo_datum = :kezdo_datum, 
                vege_datum = :vege_datum, 
                statusz = :statusz, 
                ertekeles = :ertekeles, 
                szezon_id = :szezon_id
            WHERE id = :id
        ");

        $stmt->execute([
            ':id' => $anime_id,
            ':romanji_cim' => $_POST['romanji_cim'],
            ':angol_cim' => $_POST['angol_cim'] ?? null,
            ':leiras' => $_POST['leiras'],
            ':hossza' => $_POST['hossza'] ?? null,
            ':poszter' => $_POST['poszter'] ?? null,
            ':epizod_szam' => $_POST['epizod_szam'] ?? null,
            ':kezdo_datum' => $_POST['kezdo_datum'] ?? null,
            ':vege_datum' => $_POST['vege_datum'] ?? null,
            ':statusz' => $_POST['statusz'],
            ':ertekeles' => $_POST['ertekeles'] ?? null,
            ':szezon_id' => $_POST['szezon_id']
        ]);

        $conn->prepare("DELETE FROM anime_has_studio WHERE anime_id = :anime_id")
            ->execute([':anime_id' => $anime_id]);

        if (!empty($_POST['studio_id'])) {
            $studioStmt = $conn->prepare("INSERT INTO anime_has_studio (anime_id, studio_id) VALUES (:anime_id, :studio_id)");
            $studioStmt->execute([
                ':anime_id' => $anime_id,
                ':studio_id' => $_POST['studio_id']
            ]);
        }

        ob_end_clean();
        header('Location: anime_list.php?success=1');
        exit();
    } catch (PDOException $e) {
        $error = 'Hiba történt a mentés közben: ' . $e->getMessage();
    }
}
?>

<div class="admin-container">
    <div class="card">
        <h1>Anime Szerkesztése</h1>

        <?php if ($error): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="romanji_cim">Romanji Cím *</label>
                <input type="text" id="romanji_cim" name="romanji_cim"
                    value="<?php echo htmlspecialchars($anime['romanji_cim']); ?>" required>
            </div>

            <div class="form-group">
                <label for="angol_cim">Angol Cím</label>
                <input type="text" id="angol_cim" name="angol_cim"
                    value="<?php echo htmlspecialchars($anime['angol_cim'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="leiras">Leírás *</label>
                <textarea id="leiras" name="leiras" required><?php
                echo htmlspecialchars($anime['leiras']);
                ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="hossza">Epizód Hossza</label>
                    <input type="time" id="hossza" name="hossza" value="<?php echo $anime['hossza'] ?? ''; ?>">
                </div>

                <div class="form-group">
                    <label for="poszter">Poszter URL</label>
                    <input type="text" id="poszter" name="poszter"
                        value="<?php echo htmlspecialchars($anime['poszter'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="epizod_szam">Epizódok Száma</label>
                    <input type="number" id="epizod_szam" name="epizod_szam"
                        value="<?php echo $anime['epizod_szam'] ?? ''; ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="kezdo_datum">Kezdő Dátum</label>
                    <input type="date" id="kezdo_datum" name="kezdo_datum"
                        value="<?php echo $anime['kezdo_datum'] ?? ''; ?>">
                </div>

                <div class="form-group">
                    <label for="vege_datum">Befejezési Dátum</label>
                    <input type="date" id="vege_datum" name="vege_datum"
                        value="<?php echo $anime['vege_datum'] ?? ''; ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="statusz">Státusz</label>
                    <select id="statusz" name="statusz">
                        <option value="tervezet" <?php echo $anime['statusz'] === 'tervezet' ? 'selected' : ''; ?>>
                            Tervezett</option>
                        <option value="fut" <?php echo $anime['statusz'] === 'fut' ? 'selected' : ''; ?>>Folyamatban
                        </option>
                        <option value="befejezett" <?php echo $anime['statusz'] === 'befejezett' ? 'selected' : ''; ?>>
                            Befejezett</option>
                        <option value="szunet" <?php echo $anime['statusz'] === 'szunet' ? 'selected' : ''; ?>>Szünet
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="ertekeles">Értékelés</label>
                    <input type="number" id="ertekeles" name="ertekeles" step="0.1" min="0" max="10"
                        value="<?php echo $anime['ertekeles'] ?? ''; ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="szezon_id">Szezon</label>
                    <select id="szezon_id" name="szezon_id">
                        <?php foreach ($seasons as $season): ?>
                            <option value="<?php echo $season['id']; ?>" <?php echo $season['id'] == $anime['szezon_id'] ? 'selected' : ''; ?>>
                                <?php echo $season['ev'] . ' ' . ucfirst($season['szezon']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="studio_id">Stúdió</label>
                    <select id="studio_id" name="studio_id">
                        <option value="">Válasszon stúdiót</option>
                        <?php foreach ($studios as $studio): ?>
                            <option value="<?php echo $studio['id']; ?>" <?php echo $studio['id'] == $anime['studio_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($studio['nev']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn">Mentés</button>
                <a href="anime_list.php" class="btn btn-cancel">Mégsem</a>
            </div>
        </form>

        <div class="episode-management">
            <h2>Epizódok</h2>
            <table class="episode-table">
                <thead>
                    <tr>
                        <th>Sorszám</th>
                        <th>Cím</th>
                        <th>Hossz</th>
                        <th>Adás Dátuma</th>
                        <th>Műveletek</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($episodes)): ?>
                        <tr>
                            <td colspan="5">Nincsenek epizódok hozzáadva</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($episodes as $episode): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($episode['episode_number']); ?></td>
                                <td><?php echo htmlspecialchars($episode['title']); ?></td>
                                <td><?php echo htmlspecialchars($episode['duration']); ?></td>
                                <td><?php echo htmlspecialchars($episode['air_date']); ?></td>
                                <td>
                                    <button onclick="openEditEpisodeModal(
                                        <?php echo htmlspecialchars(json_encode($episode)); ?>
                                    )" class="btn btn-edit">Szerkesztés</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="episode-actions">
                <button onclick="openAddEpisodeModal()" class="btn btn-add">Epizód Hozzáadása</button>
            </div>
        </div>

        <div id="addEpisodeModal" class="modal">
            <div class="modal-content">
                <span class="modal-close" onclick="closeModal('addEpisodeModal')">&times;</span>
                <h2>Új Epizód Hozzáadása</h2>
                <form id="addEpisodeForm" method="POST" action="add_episode.php">
                    <input type="hidden" name="anime_id" value="<?php echo $anime_id; ?>">
                    <div class="form-group">
                        <label for="new_episode_number">Epizód Sorszáma *</label>
                        <input type="number" id="new_episode_number" name="episode_number" required>
                    </div>
                    <div class="form-group">
                        <label for="new_episode_title">Epizód Címe *</label>
                        <input type="text" id="new_episode_title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="new_episode_duration">Epizód Hossza</label>
                        <input type="time" id="new_episode_duration" name="duration">
                    </div>
                    <div class="form-group">
                        <label for="new_episode_air_date">Adás Dátuma</label>
                        <input type="date" id="new_episode_air_date" name="air_date">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn">Mentés</button>
                        <button type="button" class="btn btn-cancel"
                            onclick="closeModal('addEpisodeModal')">Mégsem</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="editEpisodeModal" class="modal">
            <div class="modal-content">
                <span class="modal-close" onclick="closeModal('editEpisodeModal')">&times;</span>
                <h2>Epizód Szerkesztése</h2>
                <form id="editEpisodeForm" method="POST" action="edit_episode.php">
                    <input type="hidden" name="id" id="edit_episode_id">
                    <div class="form-group">
                        <label for="edit_episode_number">Epizód Sorszáma *</label>
                        <input type="number" id="edit_episode_number" name="episode_number" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_episode_title">Epizód Címe *</label>
                        <input type="text" id="edit_episode_title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_episode_duration">Epizód Hossza</label>
                        <input type="time" id="edit_episode_duration" name="duration">
                    </div>
                    <div class="form-group">
                        <label for="edit_episode_air_date">Adás Dátuma</label>
                        <input type="date" id="edit_episode_air_date" name="air_date">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn">Mentés</button>
                        <button type="button" class="btn btn-cancel"
                            onclick="closeModal('editEpisodeModal')">Mégsem</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php'; ?>

<script>
    function openAddEpisodeModal() {
        const modal = document.getElementById('addEpisodeModal');
        modal.style.display = 'flex';
        disableScroll();
    }

    function openEditEpisodeModal(episode) {
        document.getElementById('edit_episode_id').value = episode.id;
        document.getElementById('edit_episode_number').value = episode.episode_number;
        document.getElementById('edit_episode_title').value = episode.title;
        document.getElementById('edit_episode_duration').value = episode.duration;
        document.getElementById('edit_episode_air_date').value = episode.air_date;

        const modal = document.getElementById('editEpisodeModal');
        modal.style.display = 'flex';
        disableScroll();
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.style.display = 'none';
        enableScroll();
    }

    function disableScroll() {
        document.body.style.overflow = 'hidden';
    }

    function enableScroll() {
        document.body.style.overflow = 'auto';
    }

    window.onclick = function (event) {
        const modals = document.getElementsByClassName('modal');
        for (let modal of modals) {
            if (event.target == modal) {
                modal.style.display = 'none';
                enableScroll();
            }
        }
    }
</script>

<?php
ob_end_flush();
?>
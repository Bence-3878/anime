<?php
require_once 'includes/header.php';
require_once '../config/database.php';

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'editor')) {
    header('Location: index.php');
    exit();
}

$studios = [];
$seasons = [];
$error = '';

try {
    $conn = Database::connect();

    $studiosQuery = $conn->query("SELECT id, nev FROM studio ORDER BY nev");
    $studios = $studiosQuery->fetchAll(PDO::FETCH_ASSOC);

    $seasonQuery = $conn->query("SELECT id, ev, szezon FROM szezon ORDER BY ev DESC, szezon");
    $seasons = $seasonQuery->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('Anime addition page error: ' . $e->getMessage());
    
    $error = 'Adatbázis hiba: Nem sikerült betölteni a legördülő menüket.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requiredFields = ['romanji_cim', 'leiras', 'szezon_id'];
    $missingFields = [];

    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $missingFields[] = $field;
        }
    }

    if (empty($missingFields)) {
        try {
            $conn->beginTransaction();

            $stmt = $conn->prepare("
                INSERT INTO anime 
                (romanji_cim, angol_cim, leiras, poszter, hossza, 
                epizod_szam, kezdo_datum, vege_datum, statusz, 
                ertekeles, szezon_id) 
                VALUES 
                (:romanji_cim, :angol_cim, :leiras, :poszter, :hossza, 
                :epizod_szam, :kezdo_datum, :vege_datum, :statusz, 
                :ertekeles, :szezon_id)
            ");

            $animeData = [
                ':romanji_cim' => trim($_POST['romanji_cim']),
                ':angol_cim' => trim($_POST['angol_cim'] ?? ''),
                ':leiras' => trim($_POST['leiras']),
                ':poszter' => trim($_POST['poszter'] ?? ''),
                ':hossza' => !empty($_POST['hossza']) ? $_POST['hossza'] : null,
                ':epizod_szam' => !empty($_POST['epizod_szam']) ? intval($_POST['epizod_szam']) : null,
                ':kezdo_datum' => !empty($_POST['kezdo_datum']) ? $_POST['kezdo_datum'] : null,
                ':vege_datum' => !empty($_POST['vege_datum']) ? $_POST['vege_datum'] : null,
                ':statusz' => $_POST['statusz'] ?? 'tervezet',
                ':ertekeles' => !empty($_POST['ertekeles']) ? floatval($_POST['ertekeles']) : null,
                ':szezon_id' => intval($_POST['szezon_id'])
            ];

            $stmt->execute($animeData);
            $anime_id = $conn->lastInsertId();

            if (!empty($_POST['studio_id'])) {
                $studioStmt = $conn->prepare("
                    INSERT INTO anime_has_studio (anime_id, studio_id) 
                    VALUES (:anime_id, :studio_id)
                ");
                $studioStmt->execute([
                    ':anime_id' => $anime_id,
                    ':studio_id' => intval($_POST['studio_id'])
                ]);
            }

            $conn->commit();

            header('Location: anime_list.php?success=1');
            exit();

        } catch (PDOException $e) {
            $conn->rollBack();

            error_log('Anime insertion error: ' . $e->getMessage());

            $error = 'Hiba történt az anime mentése közben. Ellenőrizze az adatokat.';
        }
    } else {
        $error = 'Kötelező mezők hiányoznak: ' . implode(', ', $missingFields);
    }
}
?>

<div class="admin-container">
    <div class="card">
        <h1>Új Anime Hozzáadása</h1>

        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label for="romanji_cim">Romanji Cím *</label>
                <input type="text" id="romanji_cim" name="romanji_cim" 
                       value="<?php echo htmlspecialchars($_POST['romanji_cim'] ?? ''); ?>" 
                       required>
            </div>

            <div class="form-group">
                <label for="angol_cim">Angol Cím</label>
                <input type="text" id="angol_cim" name="angol_cim" 
                       value="<?php echo htmlspecialchars($_POST['angol_cim'] ?? ''); ?>">
            </div>

            <div class="form-group">
                <label for="leiras">Leírás *</label>
                <textarea id="leiras" name="leiras" required><?php 
                    echo htmlspecialchars($_POST['leiras'] ?? ''); 
                ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="hossza">Epizód Hossza</label>
                    <input type="time" id="hossza" name="hossza" 
                           value="<?php echo htmlspecialchars($_POST['hossza'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="poszter">Poszter URL</label>
                    <input type="text" id="poszter" name="poszter" 
                           value="<?php echo htmlspecialchars($_POST['poszter'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="epizod_szam">Epizódok Száma</label>
                    <input type="number" id="epizod_szam" name="epizod_szam" 
                           value="<?php echo htmlspecialchars($_POST['epizod_szam'] ?? ''); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="kezdo_datum">Kezdő Dátum</label>
                    <input type="date" id="kezdo_datum" name="kezdo_datum" 
                           value="<?php echo htmlspecialchars($_POST['kezdo_datum'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="vege_datum">Befejezési Dátum</label>
                    <input type="date" id="vege_datum" name="vege_datum" 
                           value="<?php echo htmlspecialchars($_POST['vege_datum'] ?? ''); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="statusz">Státusz</label>
                    <select id="statusz" name="statusz">
                        <option value="tervezet" <?php echo isset($_POST['statusz']) && $_POST['statusz'] == 'tervezet' ? 'selected' : ''; ?>>Tervezett</option>
                        <option value="fut" <?php echo isset($_POST['statusz']) && $_POST['statusz'] == 'fut' ? 'selected' : ''; ?>>Folyamatban</option>
                        <option value="befejezett" <?php echo isset($_POST['statusz']) && $_POST['statusz'] == 'befejezett' ? 'selected' : ''; ?>>Befejezett</option>
                        <option value="szunet" <?php echo isset($_POST['statusz']) && $_POST['statusz'] == 'szunet' ? 'selected' : ''; ?>>Szünet</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="ertekeles">Értékelés</label>
                    <input type="number" id="ertekeles" name="ertekeles" 
                           step="0.1" min="0" max="10" 
                           value="<?php echo htmlspecialchars($_POST['ertekeles'] ?? ''); ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="szezon_id">Szezon *</label>
                    <select id="szezon_id" name="szezon_id" required>
                        <option value="">Válasszon szezont</option>
                        <?php foreach ($seasons as $season): ?>
                            <option value="<?php echo $season['id']; ?>" 
                                <?php echo (isset($_POST['szezon_id']) && $_POST['szezon_id'] == $season['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($season['ev'] . ' ' . ucfirst($season['szezon'])); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="studio_id">Stúdió</label>
                    <select id="studio_id" name="studio_id">
                        <option value="">Válasszon stúdiót</option>
                        <?php foreach ($studios as $studio): ?>
                            <option value="<?php echo $studio['id']; ?>" 
                                <?php echo (isset($_POST['studio_id']) && $_POST['studio_id'] == $studio['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($studio['nev']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn">Anime Mentése</button>
            </div>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
<?php
declare(strict_types=1);

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: bejelentkezes.php");
    exit();
}

require_once 'config/Database.php';

$user_id = (int)$_SESSION["user_id"];  
$profile = new Profile($user_id);
$user = $profile->getUserData();
$sikeres = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST["user_name"])) {
        $sikeres = $profile->updateUsername($_POST["user_name"]);
    }
    if (isset($_POST["regi_jelszo"], $_POST["uj_jelszo"])) {
        $sikeres = $profile->changePassword($_POST["regi_jelszo"], $_POST["uj_jelszo"]);
    }
    if (isset($_POST["option_selected"]) && $_SESSION["jog"] == "admin") {
        $sikeres = $profile->updateRole($_POST["option_selected"]);
    }
}

class Profile
{
    private PDO $pdo;
    private int $user_id;

    public function __construct(int $user_id)
    {
        $this->pdo = Database::connect();
        $this->user_id = $user_id;
    }

    public function updateUsername(string $username): bool
    {
        $sql = "SELECT COUNT(*) FROM felhasznalo WHERE nev = :nev AND id != :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":nev", $username);
        $stmt->bindParam(":id", $this->user_id);
        $stmt->execute();
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            return false;
        }

        $sql = "UPDATE felhasznalo SET nev = :nev WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":nev", $username);
        $stmt->bindParam(":id", $this->user_id);
        return $stmt->execute();
    }

    public function changePassword(string $old_password, string $new_password): bool
    {
        $sql = "SELECT jelszo FROM felhasznalo WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":id", $this->user_id);
        $stmt->execute();
        $storedPassword = $stmt->fetchColumn(PDO::FETCH_ASSOC);

        if ($storedPassword && password_verify($old_password, $storedPassword)) {
            $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
            $sql = "UPDATE felhasznalo SET jelszo = :jelszo WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(":jelszo", $hashedPassword);
            $stmt->bindParam(":id", $this->user_id);
            return $stmt->execute();
        }
        return false;
    }

    public function updateRole(string $role): bool
    {
        if ($_SESSION["jog"] !== "admin") {
            throw new Exception("Access denied.");
        }
        $sql = "UPDATE felhasznalo SET jogosultsag = :jogosultsag WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":jogosultsag", $role);
        $stmt->bindParam(":id", $this->user_id);
        return $stmt->execute();
    }

    public function getUserData(): array
    {
        $sql = "SELECT * FROM felhasznalo WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":id", $this->user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserAnimeList(): array
    {
        $sql = "SELECT a.romanji_cim, al.hol_tart, al.ertekeles, al.statusz 
                FROM anime_lista al
                JOIN anime a ON al.anime_id = a.id
                WHERE al.felhasznalo_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

$anime_list = $profile->getUserAnimeList();

?>

<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($user["nev"]) ?> Profil</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="container">
        <?php require_once 'includes/header.php'; ?>
        <br>
        <div class="row">
            <aside class="sidebar">
                <h2>Felhasználói adatok</h2>
                <p><strong>Név:</strong> <?= htmlspecialchars($user["nev"]) ?></p>
                <p><strong>Felhasználó ID:</strong> <?= $user_id ?></p>
                <?php if ($sikeres): ?>
                    <p class="sikeres">Sikeresen módosítva</p>
                <?php endif; ?>
                <button id="open_felhasznalonev">Felhasználó módosítása</button>
                <button id="open_jelszo">Jelszó módosítása</button>
                <a href="/logout.php"><button id="logout">Kijelentkezés</button></a>
            </aside>

            <main class="content">
                <h2>Anime lista</h2>
                <?php if (empty($anime_list)): ?>
                    <p>Nincs anime a listádban.</p>
                <?php else: ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Anime Cím</th>
                                <th>Hol tartasz?</th>
                                <th>Értékelés</th>
                                <th>Státusz</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($anime_list as $anime): ?>
                                <tr>
                                    <td class="anime-title"><?= htmlspecialchars($anime["romanji_cim"] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars((string) ($anime["hol_tart"] ?? 'N/A')) ?></td>
                                    <td><?= htmlspecialchars((string) ($anime["ertekeles"] ?? 'N/A')) ?></td>
                                    <td><?= htmlspecialchars($anime["statusz"] ?? 'N/A') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <div id="felhasznalo_modositas_modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form action="profil.php" method="post">
                <h3>Felhasználó név módosítás</h3>
                <label for="user_name">Felhasználó Név</label>
                <input type="text" name="user_name" id="user_name" value="<?= htmlspecialchars($user["nev"]) ?>"
                    required>
                <input type="submit" class="modosit" value="Módosít">
            </form>
        </div>
    </div>

    <div id="jelszo_modositas_modal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <form action="profil.php" method="post">
                <h3>Jelszó módosítás</h3>
                <label for="regi_jelszo">Régi jelszó</label>
                <input type="password" name="regi_jelszo" id="regi_jelszo" required>
                <label for="uj_jelszo">Új jelszó</label>
                <input type="password" name="uj_jelszo" id="uj_jelszo" required>
                <input type="submit" class="modosit" value="Módosít">
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $("#open_felhasznalonev").on("click", function () {
                openModal("#felhasznalo_modositas_modal");
            });

            $("#open_jelszo").on("click", function () {
                openModal("#jelszo_modositas_modal");
            });

            $(".close").on("click", function () {
                closeModal($(this).closest(".modal"));
            });

            $(".modal").on("click", function (e) {
                if ($(e.target).is(this)) {
                    closeModal($(this));
                }
            });

            function openModal(modalId) {
                $(modalId).fadeIn();
                $("body").css("overflow", "hidden");
                centerModal(modalId);
            }

            function closeModal(modal) {
                modal.fadeOut();
                $("body").css("overflow", "auto");
            }

            function centerModal(modalId) {
                var modal = $(modalId);
                var windowHeight = $(window).height();
                var modalHeight = modal.outerHeight();
                var modalWidth = modal.outerWidth();
                modal.css({
                    "top": (windowHeight - modalHeight) / 2,
                    "left": ($(window).width() - modalWidth) / 2
                });
            }
        });
    </script>
</body>

</html>
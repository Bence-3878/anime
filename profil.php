<?php
session_start();

if (!isset($_SESSION["user_id"]) && !isset($_GET["id"])) {
    header("Location: bejelentkezes.php");
    exit();
} elseif (isset($_POST["kijelentkezes"]) && isset($_SESSION["user_id"])) {
    $_SESSION = [];
    session_destroy();
    header("Location: index.php");
    exit();
} elseif (
    isset($_GET["id"]) &&
    isset($_SESSION["user_id"]) &&
    $_SESSION["user_id"] == $_GET["id"]
) {
    $user_id = $_SESSION["user_id"];
} elseif (isset($_GET["id"])) {
    $user_id = $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
    if (!$user_id) {
        http_response_code(400);
        die("Ez nem id");
    }
} elseif (isset($_SESSION["user_id"]) && !isset($_GET["id"])) {
    $user_id = $_SESSION["user_id"];
} else {
    http_response_code(400);
    die("Az id és a felhasználó név nem egy felhasználóhoz tartozik");
}

$dsn = "mysql:host=localhost;dbname=hazi;charset=utf8";
$sqlusername = "root";
$sqlpassword = "";

try {
    $pdo = new PDO($dsn, $sqlusername, $sqlpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    die("Sikertelen kapcsolódás: " . $e->getMessage());
}

$sikeres = false;
if (
    isset($_POST["user_name"]) &&
    isset($_POST["jelszo"]) &&
    isset($_SESSION["user_id"]) &&
    $_SESSION["user_id"] == $user_id
) {
    $sqlfelhasznalo = "UPDATE felhasznalo SET nev = :nev WHERE id = :id";
    $stmt = $pdo->prepare($sqlfelhasznalo);
    $stmt->bindParam(":nev", $_POST["user_name"]);
    $stmt->bindParam(":id", $user_id);
    $stmt->execute();
    $sikeres = true;
}

if (
    isset($_POST["regi_jelszo"]) &&
    isset($_POST["uj_jelszo"]) &&
    isset($_POST["uj_jelszo2"]) &&
    isset($_SESSION["user_id"]) &&
    $_SESSION["user_id"] == $user_id
) {
    $sqlfelhasznalo = "SELECT jelszo FROM felhasznalo WHERE id = :id";
    $stmt = $pdo->prepare($sqlfelhasznalo);
    $stmt->bindParam(":id", $user_id);
    $stmt->execute();
    $jelszo = $stmt->fetchColumn(PDO::FETCH_ASSOC);
    if (password_verify($_POST["regi_jelszo"], $jelszo)) {
        if ($_POST["uj_jelszo"] == $_POST["uj_jelszo2"]) {
            $sqlfelhasznalo =
                "UPDATE felhasznalo SET jelszo = :jelszo WHERE id = :id";
            $stmt = $pdo->prepare($sqlfelhasznalo);
            $jelszo = password_hash($_POST["uj_jelszo"], PASSWORD_DEFAULT);
            $stmt->bindParam(":jelszo", $jelszo);
            $stmt->bindParam(":id", $user_id);
            $stmt->execute();
            $sikeres = true;
        }
    }
}

if (
    isset($_POST["option_selected"]) &&
    isset($_SESSION["user_id"]) &&
    $_SESSION["jog"] == "admin"
) {
    $sqlfelhasznalo =
        "UPDATE felhasznalo SET jogosultsag = :jogosultsag WHERE id = :id";
    $stmt = $pdo->prepare($sqlfelhasznalo);
    $stmt->bindParam(":jogosultsag", $_POST["option_selected"]);
    $stmt->bindParam(":id", $user_id);
    $stmt->execute();
    $sikeres = true;
}

if (isset($user_id)) {
    $sqlfelhasznalo = "SELECT * FROM felhasznalo WHERE id = :id";
    $stmt = $pdo->prepare($sqlfelhasznalo);
    $stmt->bindParam(":id", $user_id);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        http_response_code(404);
        die("felhasználó nem található ilyen id-val");
    }
    $user_name = $user["nev"];
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($user_name) ?></title>
    <link rel="stylesheet" href="style.css">
    <!-- JQuery könyvtár betöltése -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container">
    <header class="row">
        <nav>
            <a class="menu" href="index.php">Kezdőlap</a>
            <a class="menu" href="szezon.php">Szezon</a>
            <a class="menu" href="kereso.php">Kereső</a>
            <?php if (isset($_SESSION["user_id"])): ?>
                <a class="menu" href="profil.php">Profil</a>
                <?php if (
                    $_SESSION["jog"] == "admin" ||
                    $_SESSION["jog"] == "editor"
                ): ?>
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
            <p><strong>Név:</strong> <?= htmlspecialchars($user_name) ?></p>
            <p><strong>Felhasználó ID:</strong> <?= $user_id ?></p>
            <?php if ($sikeres): ?>
                <p class="sikeres" >Szikeren modósult</p>
            <?php endif; ?>
            <?php if (
                isset($_SESSION["user_id"]) &&
                $_SESSION["user_id"] == $user_id
            ): ?>
                <button id="open_felhasznalonev">Felhasználó módosítása</button>

                <button id="open_jelszo">Jelszó módosítása</button>

            <?php endif; ?>
            <?php if (
                isset($_SESSION["jog"]) &&
                $_SESSION["jog"] == "admin"
            ): ?>
                <form action="profil.php?id=<?= $user_id ?>" method="post">
                    <select name="option_selected" id="option_selected" required>
                        <option value="user" <?php if (
                            $user["jogosultsag"] == "user"
                        ) {
                            echo "disabled selected";
                        } ?>>User</option>
                        <option value="editor" <?php if (
                            $user["jogosultsag"] == "editor"
                        ) {
                            echo "disabled selected";
                        } ?>>Editor</option>
                        <option value="admin" <?php if (
                            $user["jogosultsag"] == "admin"
                        ) {
                            echo "disabled selected";
                        } ?>>Admin</option>
                    </select>

                    <input type="submit" class="modosit" value="modosit">
                </form>
            <?php endif; ?>
            <?php if (
                isset($_SESSION["user_id"]) &&
                $_SESSION["user_id"] == $user_id
            ): ?>
                <form action="profil.php" method="post">
                    <input type="submit" class="kijelenkezes" name="kijelentkezes" value="kijelentkezes">
                </form>
            <?php endif; ?>
        </aside>
        <main class="content">
            <h2>Felhasználói lista</h2>

        </main>
    </div>
</div>

<!-- A modális ablak -->
<div id="felhasznalo_modositas_modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <form action="profil.php" method="post">
            <h3>Felhasználó név módosítás</h3>
            <label for="user_name">Felhasználó Név</label>
            <input type="text" name="user_name" id="user_name" value="<?= htmlspecialchars(
                $user_name
            ) ?>" required>
            <label for="jelszo">Jelszó</label>
            <input type="password" name="jelszo" id="jelszo" required>
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

            <label for="uj_jelszo">új jelszó</label>
            <input type="password" name="uj_jelszo" id="uj_jelszo" required>
            <?php if (
                isset($_POST["regi_jelszo"]) &&
                isset($_POST["uj_jelszo"]) &&
                isset($_POST["uj_jelszo2"]) &&
                isset($_SESSION["user_id"]) &&
                $_SESSION["user_id"] == $user_id &&
                $_POST["uj_jelszo"] != $_POST["uj_jelszo2"]
            ): ?>
                <p class="error">A jelszavak nem egyeznek meg!</p>
            <?php endif; ?>

            <label for="uj_jelszo2">Új jelszó még egyszer</label>
            <input type="password" name="uj_jelszo2" id="uj_jelszo2" required>

            <input type="submit" class="modosit" value="Módosít">
        </form>
    </div>
</div>
<script>
    $(document).ready(function() {
        // A modális ablak megnyitása
        $("#open_felhasznalonev").on('click', function() {
            $("#felhasznalo_modositas_modal").fadeIn();
        });

        $("#open_jelszo").on('click', function() {
            $("#jelszo_modositas_modal").fadeIn();
        });

        // A modális ablak bezárása
        $(".close").on('click', function() {
            $(".modal").fadeOut();
        });

        // A modális ablak bezárása, ha a tartalmon kívülre kattintunk
        $(window).on('click', function(event) {
            if ($(event.target).is("#felhasznalo_modositas_modal")) {
                $("#felhasznalo_modositas_modal").fadeOut();
            }
            if ($(event.target).is("#jelszo_modositas_modal")) {
                $("#jelszo_modositas_modal").fadeOut();
            }
        });
    });
</script>
</body>
</html>

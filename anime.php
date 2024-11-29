<?php
session_start();
if (empty($_GET["id"])) {
    http_response_code(400);
    exit("400 nincs id");
} else {
    $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
    if ($id === false) {
        http_response_code(400);
        exit("400 nincs id");
    }
}
$dsn = "mysql:host=localhost;dbname=hazi;charset=utf8";
$sqlusername = "root";
$sqlpassword = "";
try {
    $pdo = new PDO($dsn, $sqlusername, $sqlpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die($e->getMessage());
}

if (isset($_POST["felvesz"])) {
    $sql = "INSERT INTO anime_lista (felhasznalo_id, anime_id";
    if (isset($_POST["ertesites"])) {
        $sql .= ", ertekeles";
    }
    if (isset($_POST["hol_tartasz"])) {
        $sql .= ", hol_tart";
    }
    $sql .= ", statusz) VALUES (:user_id, :anime_id";
    if (isset($_POST["ertesites"])) {
        $sql .= ", :ertesites";
    }
    if (isset($_POST["hol_tartasz"])) {
        $sql .= ", :hol_tartasz";
    }
    $sql .= ", NULL)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":user_id", $_SESSION["user_id"], PDO::PARAM_INT);
    $stmt->bindParam(":anime_id", $id, PDO::PARAM_INT);
    $stmt->bindParam(":ertesites", $_POST["ertesites"], PDO::PARAM_INT);
    $stmt->bindParam(":hol_tartasz", $_POST["hol_tartasz"], PDO::PARAM_STR);
    $stmt->execute();
}

$sql = "SELECT * FROM anime WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":id", $id, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$result) {
    http_response_code(404);
    exit("$id nincs ilyen id");
}
?>
<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($result['romanji_cim']); ?></title>
    <link rel="icon" href="kép/ikon.jpg" type="image/x-icon">

    <link rel="stylesheet" href="style.css">
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
            <h1><?= htmlspecialchars($result["romanji_cim"]) ?></h1>
            <?php if (isset($result["angol_cim"])): ?>
                <h2><?= htmlspecialchars($result["angol_cim"]) ?></h2>
            <?php endif; ?>
            <?php if (isset($result["poszter"], $result["romanji_cim"])): ?>
                <img src="<?= htmlspecialchars(
                    $result["poszter"],
                    ENT_QUOTES,
                    "UTF-8"
                ) ?>"
                     alt="<?= htmlspecialchars(
                         $result["romanji_cim"],
                         ENT_QUOTES,
                         "UTF-8"
                     ) ?>">
            <?php endif; ?>
            <?php if (isset($result["szezon_id"])): ?>
                Szezon:
                <a href="szezon.php?id=<?= htmlspecialchars($result["szezon_id"]) ?>" >
                    <?php
                    $sqlSzezon = "SELECT ev ,szezon FROM szezon WHERE id = :id";
                    $stmtSzezon = $pdo->prepare($sqlSzezon);
                    $stmtSzezon->bindParam(":id", $result["szezon_id"], PDO::PARAM_INT);
                    $stmtSzezon->execute();
                    $szezon = $stmtSzezon->fetch(PDO::FETCH_ASSOC);
                    echo $szezon["ev"] . " " . $szezon["szezon"];
                    ?></a>
            <?php endif; ?>
            <?php if (isset($_SESSION["user_id"])): ?>
                <form action="anime.php?id=<?= $_GET["id"] ?>" method="post">
                    <label for="hol_tartasz">Hol tartasz</label>
                    <input type="text" name="hol_tartasz" id="hol_tartasz" value="0" required>
                    <!--value="< htmlspecialchars($_POST['hol_tartasz']); ?>"-->

                    <label for="ertesites">Értékelés</label>
                    <input type="number" name="ertesites" id="ertesites">
                    <!-- value="< htmlspecialchars($_POST['ertesites']); ?>" -->
                    <input type="submit" value="Felvesz" name="felvesz">
                </form>
            <?php endif; ?>
        </aside>
        <main>
            <section class="anime-profile">

                <p><?= htmlspecialchars($result["leiras"]) ?></p>
                <?php if (isset($result["folytatas_id"])): ?>
                    <p> Folytatás:
                        <a href="anime.php?id=<?= htmlspecialchars(
                            $result["folytatas_id"]
                        ) ?>" >
                            <?php
                            $sqlNext = "SELECT romanji_cim FROM anime WHERE id = :id";
                            $stmtNext = $pdo->prepare($sqlNext);
                            $stmtNext->bindParam(
                                ":id",
                                $result["folytatas_id"],
                                PDO::PARAM_INT
                            );
                            $stmtNext->execute();
                            echo htmlspecialchars($stmtNext->fetchColumn());
                            ?></a></p>
                <?php endif; ?>
                <?php if (isset($result["elozmeny_id"])): ?>
                    <p> Előzmény:
                        <a href="anime.php?id=<?= htmlspecialchars($result["elozmeny_id"]) ?>" >
                            <?php
                            $sqlPrev = "SELECT romanji_cim FROM anime WHERE id = :id";
                            $stmtPrev = $pdo->prepare($sqlPrev);
                            $stmtPrev->bindParam(":id", $result["elozmeny_id"], PDO::PARAM_INT);
                            $stmtPrev->execute();
                            echo htmlspecialchars($stmtPrev->fetchColumn());
                            ?>
                        </a></p>
                <?php endif; ?>
                <?php
                $sqlEpisodes =
                    "SELECT * FROM episodes WHERE anime_id = :id ORDER BY episode_number";
                $stmtEpisodes = $pdo->prepare($sqlEpisodes);
                $stmtEpisodes->bindParam(":id", $id, PDO::PARAM_INT);
                $stmtEpisodes->execute();
                $episodes = $stmtEpisodes->fetchAll(PDO::FETCH_ASSOC);
                if ($episodes): ?>
                    <h2>Epizódok</h2>
                    <ul>
                        <?php foreach ($episodes as $episode) {
                            echo "<li>Epizód " .
                                htmlspecialchars($episode["episode_number"]) .
                                ": " .
                                htmlspecialchars($episode["title"]) .
                                " (" .
                                htmlspecialchars($episode["duration"]) .
                                ") - " .
                                htmlspecialchars($episode["air_date"]) .
                                "</li>";
                        } ?>
                    </ul>
                <?php endif;
                ?>
            </section>
        </main>
    </div>
</div>
</body>
</html>
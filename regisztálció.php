<?php
$errors = array();
if(!empty($_POST)) {
    try{
        $db = new PDO('mysql:host=localhost;dbname=hazi;charset=utf8','root','');
        $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $e){
        die('Sikertelen kapcsolódás :' . $e->getMessage());
        http_response_code(500);
    }
    if(empty($_POST["nev"]))
        $errors["nev"] = "név megadása kötelező";
    if(empty($_POST["jelszo"]))
        $errors["jelszo"] = "jelszó megadása kötelező";
    if(empty($errors)) {
        $nev = $_POST["nev"];
        $jelszo = $_POST["jelszo"];
        $hash=password_hash($jelszo, PASSWORD_DEFAULT);
    }
    $insert = "INSERT INTO `felhasználó`(`felhasználó_név`, `jelszó`) VALUES ('%s','%s');";
    $query = sprintf($insert, $nev, $hash);
    $result = $db->prepare($query);

    unset($db);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Regisztárció</title>
</head>
<body>
<div class="container">
    <header class="row">
        <a class="menu" href="index.php">Kezdőlap</a>
        <a class="menu" href="sezon.html">Szezon</a>
        <a class="menu" href="kereso.html">Kereső</a>
        <a class="menu" href="bejelentkezés.html">bejelentkezés</a>
        <a class="menu" href="regisztálció.html">regisztráció</a>
    </header>
    <main class="regist">
        <form method="post">
            felhasználó név
            <input type="text" name="nev" id="nev" required>
            jelszó
            <input type="password" name="jelszo" id="jelszo" required>
            <input type="submit" value="regisztrálok" >
        </form>
    </main>

</div>
</body>
</html>
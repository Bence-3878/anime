<?php
if(empty($_GET['id'])){
    http_response_code(400);
    exit("400 nincs ilyen id");
}
else {
    $id =filter_var( $_GET['id'],FILTER_VALIDATE_INT);
    if (!$id){
        http_response_code(400);
        exit("$id nincs ilyen id");
    }
}
try{
    $db = new PDO('mysql:host=localhost;dbname=hazi;charset=utf8','root','');
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    die($e->getMessage());
}
$sql = "SELECT * FROM Anime WHERE id=1";
$result = $db->query($sql);
if($result->rowCount()!=1){
http_response_code(404);
exit("$id nincs ilyen id");
unset($db);
}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Nagy Házi</title>
</head>
<body>
<header>
    <a class="menu" href="index.html">Kezdőlap</a>
    <a class="menu" href="sezon.html">Szezon</a>
    <a class="menu" href="kereso.html">Kereső</a>
    <a class="menu" href="bejelentkezés.html">bejelentkezés</a>
    <a class="menu" href="regisztálció.php">regisztráció</a>
</header>

</body>
</html>
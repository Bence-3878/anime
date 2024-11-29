<?php
session_start();

if (!empty($_POST)) {
    $dsn = 'mysql:host=localhost;dbname=hazi;charset=utf8';
    $sqlusername = 'root';
    $sqlpassword = '';

    try {
        $pdo = new PDO($dsn, $sqlusername, $sqlpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        http_response_code(500);

    }

    if (isset($_POST['table']))
        $database = $_POST['table'];
    else
        die("nincs tabla");

    if (isset($_POST['action'])){
        $action = $_POST['action'];
        if ($action == 'insert') ;
        elseif ($action == 'update') ;
        elseif ($action == 'delete') ;
        elseif ($action == 'select') ;
        elseif ($action == 'selectall') {
            $sql = "SELECT * FROM ".$database;
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll();
            header('Content-Type: application/json');
            echo json_encode($result);
            exit();
        }
        elseif ($action == 'selectwhere') ;
        elseif ($action == 'selectwhereall') ;
        else
            http_response_code(400);
    }

}
else{
    http_response_code(400);
    header('Location: ../index.php');
    exit();
}

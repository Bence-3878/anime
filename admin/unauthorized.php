<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>Hozzáférés megtagadva</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1a1a2e;
            color: #e7e7e7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            text-align: center;
        }
        .error-container {
            background-color: #16213e;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>Hozzáférés megtagadva</h1>
        <p>Önnek nincs jogosultsága ehhez a felülethez.</p>
        <p>Kérjük, jelentkezzen be megfelelő jogosultsággal.</p>
        <a href="login.php" style="color: #0f3460; text-decoration: none;">Vissza a bejelentkezéshez</a>
    </div>
</body>
</html>
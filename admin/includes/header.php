<?php
ob_start();
require_once dirname(__DIR__) . '/auth.php';
AdminAuth::requireAdminOrEditor();
?>
<!DOCTYPE html>
<html lang="hu">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --bg-primary: #121229;
            --bg-secondary: #1e1e3f;
            --text-primary: #e7e7ff;
            --text-secondary: #a0a0c0;
            --accent-color: #6a5acd;
            --accent-hover: #7b68ee;
            --border-color: #2c2c4f;
            --shadow-color: rgba(0, 0, 0, 0.3);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .admin-container {
            max-width: 1400px;
            width: 95%;
            margin: 0 auto;
            padding: 20px;
            flex-grow: 1;
        }

        .header {
            background-color: var(--bg-secondary);
            padding: 15px 0;
            box-shadow: 0 4px 15px var(--shadow-color);
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 30px;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            width: 95%;
            margin: 0 auto;
            padding: 0 20px;
        }

        .header-logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-primary);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .header-logo:hover {
            color: var(--accent-color);
        }

        .header-nav {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .header-nav a {
            color: var(--text-secondary);
            text-decoration: none;
            padding: 8px 15px;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .header-nav a:hover {
            background-color: var(--accent-color);
            color: var(--text-primary);
            transform: translateY(-2px);
        }

        .footer {
            background-color: var(--bg-secondary);
            color: var(--text-secondary);
            text-align: center;
            padding: 15px 0;
            border-top: 1px solid var(--border-color);
            margin-top: 30px;
        }

        .card {
            background-color: var(--bg-secondary);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 10px 25px var(--shadow-color);
            border: 1px solid var(--border-color);
            transition: transform 0.3s ease;
        }

        .card h1,
        .card h2 {
            margin-bottom: 20px;
            color: var(--accent-color);
            border-bottom: 2px solid var(--accent-color);
            padding-bottom: 10px;
        }

        .btn {
            display: inline-block;
            background-color: var(--accent-color);
            color: var(--text-primary);
            padding: 12px 25px;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn:hover {
            background-color: var(--accent-hover);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px var(--shadow-color);
        }

        .welcome-card {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .welcome-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .welcome-header h1 {
            color: var(--accent-color);
            margin-bottom: 0;
            border-bottom: none;
        }

        .user-badge {
            background-color: var(--accent-color);
            color: var(--text-primary);
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        .welcome-message {
            color: var(--text-secondary);
            line-height: 1.6;
        }

        .stats-container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .stat-card {
            flex: 1;
            text-align: center;
            background-color: var(--bg-primary);
            padding: 25px;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px var(--shadow-color);
        }

        .stat-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.7;
        }

        .stat-card small {
            display: block;
            color: var(--text-secondary);
            margin-top: 10px;
            font-size: 0.8rem;
        }

        .recent-anime-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .recent-anime-item {
            background-color: var(--bg-primary);
            padding: 15px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .recent-anime-item h3 {
            color: var(--accent-color);
            margin-bottom: 10px;
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }

            .header-nav {
                margin-top: 15px;
                flex-wrap: wrap;
                justify-content: center;
            }

            .header-nav a {
                margin: 5px;
            }

            .recent-anime-grid {
                grid-template-columns: 1fr;
            }

            .stats-container {
                flex-direction: column;
            }
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 10px;
            background-color: var(--bg-primary);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--accent-color);
        }

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .error-message {
            background-color: #ff000020;
            color: #ff6b6b;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        @media screen and (max-width: 768px) {
            .form-row {
                flex-direction: column;
            }
        }

        .simma-button {
            background-color: #3498db;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .simma-button:hover {
            background-color: #2980b9;
            transform: scale(1.02);
        }

        .simma-button:focus {
            outline: none;
            box-shadow: 0 0 10px rgba(52, 152, 219, 0.6);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fefefe;
            padding: 20px;
            border-radius: 5px;
            width: 90%;
            max-width: 500px;
            max-height: 90%;
            overflow-y: auto;
        }

        .modal-close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .modal-close:hover {
            color: black;
        }

        .episode-table {
            width: 100%;
            border-collapse: collapse;
        }

        .episode-table th,
        .episode-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="header-content">
            <a href="/admin/" class="header-logo">Admin Panel</a>
            <nav class="header-nav">
                <a href="/admin/">Index</a>
                <a href="add_anime.php">Anime Hozzáadás</a>
                <a href="anime_list.php">Anime lista</a>
                <a href="logout.php">Kijelentkezés</a>
            </nav>
            <a href="/" class="simma-button">Simma oldal</a>
        </div>
    </header>
    <div class="admin-container"></div>
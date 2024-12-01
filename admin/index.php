<?php
$pageTitle = 'Vezérlőpult';
require_once 'includes/header.php';

try {
    $conn = Database::connect();
    
    $stats = [
        'total_anime' => [
            'count' => $conn->query("SELECT COUNT(*) FROM anime")->fetchColumn(),
        ],
        'total_users' => [
            'count' => $conn->query("SELECT COUNT(*) FROM felhasznalo")->fetchColumn(),
        ],
        'total_studios' => [
            'count' => $conn->query("SELECT COUNT(*) FROM studio")->fetchColumn(),
        ]
    ];

    error_log("Total Anime Count: " . $stats['total_anime']['count']);
    error_log("Total Users Count: " . $stats['total_users']['count']);
    error_log("Total Studios Count: " . $stats['total_studios']['count']);

} catch (PDOException $e) {
    error_log('Dashboard Error: ' . $e->getMessage());
    $stats = [];
}

function getTimeBasedGreeting() {
    $hour = date('H');
    if ($hour < 6) return 'Jó éjszakát';
    if ($hour < 10) return 'Jó reggelt';
    if ($hour < 12) return 'Jó délelőttöt';
    if ($hour < 18) return 'Jó napot';
    return 'Jó estét';
}
?>

<div class="card welcome-card">
    <div class="welcome-header">
        <h1><?php echo getTimeBasedGreeting(); ?>, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <div class="user-badge"><?php echo htmlspecialchars(ucfirst($_SESSION['role'])); ?></div>
    </div>
    <p class="welcome-message">Üdvözöljük az admin felületen. Ön jelenleg <?php echo htmlspecialchars(ucfirst($_SESSION['role'])); ?> jogosultsággal van bejelentkezve.</p>
</div>

<div class="stats-container">
    <div class="stat-card">
        <div class="stat-icon">🎬</div>
        <h3>Animék</h3>
        <p><?php echo number_format($stats['total_anime']['count'] ?? 0); ?></p>
        <small>Összes anime a táblában</small>
    </div>

    <div class="stat-card">
        <div class="stat-icon">👥</div>
        <h3>Felhasználók</h3>
        <p><?php echo number_format($stats['total_users']['count'] ?? 0); ?></p>
        <small>Összes felhasználó a táblában</small>
    </div>

    <div class="stat-card">
        <div class="stat-icon">🏢</div>
        <h3>Stúdiók</h3>
        <p><?php echo number_format($stats['total_studios']['count'] ?? 0); ?></p>
        <small>Összes stúdió a táblában</small>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

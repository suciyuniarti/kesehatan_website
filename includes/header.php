<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base_url = "http://localhost/kesehatan_website/";
$current_page = basename($_SERVER['SCRIPT_NAME']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Kesehatan</title>
    
    <!-- Pemanggilan CSS -->
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<header>
    <nav>
        <ul>
            <li><a href="<?= $base_url; ?>index.php" class="<?= ($current_page == 'index.php') ? 'active' : ''; ?>">Beranda</a></li>
            <li><a href="<?= $base_url; ?>articles/index.php" class="<?= ($current_page == 'articles/index.php') ? 'active' : ''; ?>">Artikel</a></li>
            <li><a href="<?= $base_url; ?>calculate/cek_kalori.php" class="<?= ($current_page == 'cek_kalori.php') ? 'active' : ''; ?>">Cek Kalori</a></li>

            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="<?= $base_url; ?>users/logout.php">Logout (<?= htmlspecialchars($_SESSION['username']); ?>)</a></li>
            <?php else: ?>
                <li><a href="<?= $base_url; ?>users/login.php" class="<?= ($current_page == 'login.php') ? 'active' : ''; ?>">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
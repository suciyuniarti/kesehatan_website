<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek apakah pengguna sudah login dan memiliki peran sebagai admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/login.php");
    exit();
}

$base_url = "http://localhost/kesehatan_website/";
$current_page = basename($_SERVER['SCRIPT_NAME']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Website Kesehatan</title>
    
    <!-- Pemanggilan CSS -->
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<header>
    <div class="logo-container">
        <img src="<?= $base_url; ?>assets/images/logo.png" alt="Logo" class="logo">
        <span class="website-name">Admin Panel</span>
    </div>

    <button class="menu-toggle" onclick="toggleMenu()">☰</button>

    <nav>
        <ul id="nav-menu">
            <li>
                <a href="<?= $base_url; ?>admin/dashboard.php" 
                   class="<?= ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
                   Home
                </a>
            </li>
            <li>
                <a href="<?= $base_url; ?>admin/manage_articles.php" 
                   class="<?= ($current_page == 'manage_articles.php') ? 'active' : ''; ?>">
                   Kelola Artikel
                </a>
            </li>
            <li>
                <a href="<?= $base_url; ?>admin/manage_users.php" 
                   class="<?= ($current_page == 'manage_users.php') ? 'active' : ''; ?>">
                   Kelola Pengguna
                </a>
            </li>
            <li>
                <a href="<?= $base_url; ?>users/logout.php">
                    Logout (<?= htmlspecialchars($_SESSION['username']); ?>)
                </a>
            </li>
        </ul>
    </nav>
</header>

<script>
    function toggleMenu() {
        document.getElementById('nav-menu').classList.toggle('show');
    }
</script>

</body>
</html>

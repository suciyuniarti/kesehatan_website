<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$base_url = "http://localhost/kesehatan_website/";
$current_page = basename($_SERVER['SCRIPT_NAME']);

// Fungsi untuk menentukan link yang aktif
function isActive($page_name) {
    global $current_page;
    return ($current_page == $page_name) ? 'active' : '';
}

$is_articles_page = (strpos($_SERVER['SCRIPT_NAME'], 'articles') !== false);
?>

<header>
    <div class="logo-container">
        <img src="<?= $base_url; ?>assets/images/Logo.png" alt="Logo Website" class="logo">
        <span class="website-name">Sehatri</span>
    </div>

    <!-- Tombol Hamburger (Mobile) -->
    <button class="menu-toggle" onclick="toggleMenu()">☰</button>

    <nav>
        <ul id="nav-menu">
            <li><a href="<?= $base_url; ?>index.php" class="<?= $is_articles_page ? '' : isActive('index.php'); ?>">Beranda</a></li>
            <li><a href="<?= $base_url; ?>articles/index.php" class="<?= $is_articles_page ? 'active' : ''; ?>">Artikel</a></li>
            <li><a href="<?= $base_url; ?>calculate/cek_kalori.php" class="<?= isActive('cek_kalori.php'); ?>">Cek Kalori</a></li>
            
            <li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="<?= $base_url; ?>articles/create.php" class="<?= isActive('create.php'); ?>">Tulis Artikel</a>
                    <a href="<?= $base_url; ?>articles/dashboard.php" class="<?= isActive('dashboard.php'); ?>">Dashboard Artikel</a>
                <?php else: ?>
                    <a href="#" onclick="checkLogin(event)">Tulis Artikel</a>
                <?php endif; ?>
            </li>


            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="<?= $base_url; ?>users/logout.php">Logout (<?= htmlspecialchars($_SESSION['username']); ?>)</a></li>
            <?php else: ?>
                <li><a href="<?= $base_url; ?>users/login.php" class="<?= isActive('login.php'); ?>">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<script>
    function checkLogin(event) {
        <?php if (!isset($_SESSION['user_id'])): ?>
            event.preventDefault();
            alert("Silakan login terlebih dahulu untuk menulis artikel.");
        <?php endif; ?>
    }

    function toggleMenu() {
        var menu = document.getElementById("nav-menu");
        menu.classList.toggle("show");
    }

    document.addEventListener("click", function(event) {
        var menu = document.getElementById("nav-menu");
        var toggleButton = document.querySelector(".menu-toggle");

        if (!menu.contains(event.target) && !toggleButton.contains(event.target)) {
            menu.classList.remove("show");
        }
    });
</script>
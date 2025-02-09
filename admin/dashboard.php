<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../assets/css/styles.css"> 
</head>
<body>
    <header>
        <h1>Dashboard Admin</h1>
        <nav>
            <a href="dashboard.php">Home</a>
            <a href="manage_articles.php">Kelola Artikel</a>
            <a href="manage_users.php">Kelola Pengguna</a>
            <a href="../users/logout.php">Logout</a>
        </nav>
    </header>

    <section>
        <h2>Selamat Datang, <?php echo $_SESSION['username']; ?>!</h2>
        <p>Anda masuk sebagai Admin.</p>
        <p>Gunakan menu di atas untuk mengelola konten dan pengguna.</p>
    </section>

</body>
</html>
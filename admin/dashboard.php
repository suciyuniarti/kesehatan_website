<?php
session_start();
include "../includes/db.php"; // Koneksi database

// Cek apakah user adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/login.php");
    exit();
}

// Ambil jumlah pengguna dari database
$user_query = "SELECT COUNT(*) AS total_users FROM users";
$user_result = $conn->query($user_query);
$user_count = $user_result->fetch_assoc()['total_users'] ?? 0;

// Ambil jumlah artikel dari database
$article_query = "SELECT COUNT(*) AS total_articles FROM articles";
$article_result = $conn->query($article_query);
$article_count = $article_result->fetch_assoc()['total_articles'] ?? 0;

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    
    <!-- Link CSS -->
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- Tambahkan Chart.js -->

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #E3F2FD;
            display: flex;
            flex-direction: column;
        }

        .main-content {
            padding: 20px;
            width: 90%;
            max-width: 900px;
            margin: 20px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            color: #2c3e50;
        }

        p {
            color: #555;
            margin-bottom: 20px;
        }

        /* Cards */
        .card-container {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            flex: 1;
            min-width: 250px;
            max-width: 300px;
            text-align: center;
        }

        .card h3 {
            color: #1E88E5;
            margin-bottom: 10px;
        }

        .card i {
            font-size: 40px;
            color: #1E88E5;
            margin-bottom: 10px;
        }

        /* Grafik */
        .chart-container {
            margin-top: 40px;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                width: 95%;
            }
        }
    </style>
</head>
<body>

<!-- Menggunakan header dari file terpisah -->
<?php include "../includes/header_admin.php"; ?>

<div class="main-content">
    <h2>Selamat Datang, <?= htmlspecialchars($_SESSION['username']); ?>!</h2>
    <p>Anda masuk sebagai <strong>Admin</strong>. Gunakan menu di atas untuk navigasi.</p>

    <div class="card-container">
        <div class="card">
            <i class="fa fa-users"></i>
            <h3><?= $user_count; ?> Pengguna</h3>
            <p>Total pengguna yang terdaftar.</p>
        </div>

        <div class="card">
            <i class="fa fa-file-alt"></i>
            <h3><?= $article_count; ?> Artikel</h3>
            <p>Artikel yang telah dipublikasikan.</p>
        </div>
    </div>

    <!-- Grafik Statistik -->
    <div class="chart-container">
        <h3>Statistik Pengguna & Artikel</h3>
        <canvas id="dashboardChart"></canvas>
    </div>
</div>

<script>
    // Data untuk grafik
    var ctx = document.getElementById('dashboardChart').getContext('2d');
    var dashboardChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Pengguna', 'Artikel'],
            datasets: [{
                label: 'Jumlah',
                data: [<?= $user_count; ?>, <?= $article_count; ?>],
                backgroundColor: ['#1E88E5', '#43A047'],
                borderColor: ['#1565C0', '#2E7D32'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

</body>
</html>

<?php 
include "includes/db.php"; 
session_start(); // Pastikan hanya dipanggil sekali di aplikasi utama
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website Kesehatan</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        /* Reset dasar */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #E3F2FD; /* Biru muda */
        }

        .container {
            flex: 1;
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }

        /* Hero Section */
        .hero {
            text-align: center;
            padding: 50px 20px;
            background: linear-gradient(to right, #1E88E5, #42A5F5);
            color: white;
            border-radius: 10px;
            animation: fadeIn 1.5s ease-in-out;
        }

        .hero h2 {
            font-size: 2.5em;
        }

        .hero p {
            font-size: 1.2em;
            margin-top: 10px;
        }

        /* Animasi fade-in */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Card Artikel */
        .artikel-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .card {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
        }

        .card h3 {
            font-size: 1.2em;
            margin-top: 10px;
        }

        .card p {
            font-size: 0.9em;
            color: #555;
        }

        .card a {
            display: block;
            margin-top: 10px;
            padding: 8px;
            background: #1E88E5;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
        }

        .card a:hover {
            background: #1565C0;
        }

        /* Footer tetap di bawah */
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #555;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php include "includes/header.php"; ?>

    <div class="container">
        <section class="hero">
            <h2>Selamat Datang di Website Kesehatan</h2>
            <p>Temukan informasi kesehatan dan artikel terbaru untuk menjaga tubuh tetap sehat.</p>
        </section>

        <h2 style="text-align: center; margin-top: 40px;">Artikel Terbaru</h2>
        <div class="artikel-container">
            <?php
            $query = "SELECT * FROM articles ORDER BY created_at DESC LIMIT 4";
            $result = mysqli_query($conn, $query);
            
            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // Perbaikan path gambar
                    $image_path = !empty($row['image']) ? $row['image'] : 'assets/images/default.jpg';
            
                    echo "<div class='card'>";
                    echo "<img src='".$image_path."' alt='".$row['title']."'>";
                    echo "<h3>".$row['title']."</h3>";
                    echo "<p>".substr(strip_tags($row['content']), 0, 100)."...</p>";
                    echo "<a href='articles/detail.php?id=".$row['id']."'>Baca Selengkapnya</a>";
                    echo "</div>";
                }
            } else {
                echo "<p style='text-align: center;'>Belum ada artikel tersedia.</p>";
            }            
            ?>
        </div>
    </div>

    <?php include "includes/footer.php"; ?>
</body>

</html>
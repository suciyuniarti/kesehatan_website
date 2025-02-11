<?php 
include "../includes/db.php"; 
include "../includes/header.php"; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Artikel</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #E3F2FD;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            flex: 1;
            max-width: 1100px;
            margin: auto;
            padding: 20px;
        }

        h2 {
            text-align: center;
            color: #1565C0;
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .artikel-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background: white;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card:hover {
            transform: translateY(-7px);
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.2);
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
        }

        .card h3 {
            font-size: 1.4em;
            margin-top: 15px;
            color: #1E88E5;
            font-weight: bold;
        }

        .card p {
            font-size: 1em;
            color: #555;
            line-height: 1.5;
        }

        .card a {
            display: block;
            margin-top: 15px;
            padding: 10px;
            background: #1E88E5;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: background 0.3s ease-in-out;
        }

        .card a:hover {
            background: #1565C0;
        }

        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #555;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Daftar Artikel</h2>
        <div class="artikel-container">
    <?php
    $query = "SELECT * FROM articles ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Pastikan path gambar benar
            $image_path = !empty($row['image']) ? '../' . ltrim($row['image'], '/') : '../assets/images/default.jpg';
            ?>

            <div class="card">
            <img src="<?= htmlspecialchars($image_path, ENT_QUOTES, 'UTF-8'); ?>" 
            alt="<?= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'); ?>" 
            onerror="this.onerror=null;this.src='/assets/images/default.jpg';">
                <h3><?= htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8'); ?></h3>
                <p><?= htmlspecialchars(substr(strip_tags($row['content']), 0, 120)); ?>...</p>
                <a href="../articles/detail.php?id=<?= urlencode($row['id']); ?>">Baca Selengkapnya</a>
            </div>
            <?php
        }
    } else {
        echo "<p style='text-align: center; color: #555;'>Belum ada artikel tersedia.</p>";
    }
    ?>
</div>

    </div>    

    <?php include "../includes/footer.php"; ?>
</body>

</html>

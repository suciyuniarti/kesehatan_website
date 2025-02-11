<?php 
include '../includes/db.php';
include '../includes/header.php'; 

// Pastikan parameter ID tersedia
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $article_id = intval($_GET['id']); // Mencegah SQL Injection

    // Query untuk mendapatkan artikel dengan nama user yang login
    $query = "SELECT articles.title, articles.content, articles.image, articles.category, articles.created_at, 
                 COALESCE(users.username, articles.author) AS author 
          FROM articles 
          LEFT JOIN users ON articles.user_id = users.id 
          WHERE articles.id = ?";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $article_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Cek apakah artikel ditemukan
    if ($row = mysqli_fetch_assoc($result)) {
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($row['title']); ?> - Website Kesehatan</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #F8F9FA;
            margin: 0;
            padding: 0;
        }

        .main-content {
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        .container {
            width: 95%;
            max-width: 900px;
            background: white;
            border-radius: 12px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
            padding: 20px;
        }

        .article-title {
            font-size: 2em;
            color: #1E88E5;
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .article-info {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9em;
            color: #555;
            background: #e3f2fd;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .category {
            font-size: 0.9em;
            font-weight: bold;
            background: #FFC107;
            color: #333;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .article-img {
            width: 100%;
            max-height: 450px;
            object-fit: cover;
            border-radius: 10px;
            display: block;
            margin: 0 auto 20px;
        }

        .article-content {
            font-size: 1.1em;
            line-height: 1.8;
            text-align: justify;
            color: #333;
            margin-bottom: 20px;
        }

        .article-description {
            font-size: 1em;
            color: #666;
            background: #FFF3CD;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 5px solid #FFC107;
        }

        .back-btn {
            display: block;
            width: fit-content;
            padding: 12px 18px;
            margin: 20px auto 0;
            background: #1E88E5;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            transition: background 0.3s ease-in-out;
        }

        .back-btn:hover {
            background: #1565C0;
        }

        /* Responsiveness */
        @media screen and (max-width: 768px) {
            .container {
                width: 90%;
                padding: 15px;
            }
            .article-title {
                font-size: 1.8em;
            }
            .article-content {
                font-size: 1em;
            }
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

<div class="main-content">
    <div class="container">
        <h1 class="article-title"><?php echo htmlspecialchars($row['title']); ?></h1>

        <div class="article-info">
            <span>🖊️ <strong><?php echo htmlspecialchars($row['author']); ?></strong></span>
            <span>📅 <?php echo date("d M Y", strtotime($row['created_at'])); ?></span>
            <?php if (!empty($row['category'])): ?>
                <span class="category"><?php echo htmlspecialchars($row['category']); ?></span>
            <?php endif; ?>
        </div>

        <?php 
        $image_path = !empty($row['image']) ? '../' . ltrim($row['image'], '/') : '../assets/images/default.jpg';
        ?>
        <img src="<?php echo htmlspecialchars($image_path, ENT_QUOTES, 'UTF-8'); ?>" 
             alt="Gambar Artikel" class="article-img" 
             onerror="this.onerror=null;this.src='../assets/images/default.jpg';">

             <div class="article-content">
                <?php echo nl2br(htmlspecialchars_decode($row['content'])); ?>
            </div>


        <a href="index.php" class="back-btn">← Kembali ke Daftar Artikel</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

</body>
</html>
<?php
    } else {
        echo "<p style='text-align: center; color: red;'>Artikel tidak ditemukan!</p>";
    }
} else {
    echo "<p style='text-align: center; color: red;'>ID Artikel tidak valid!</p>";
}
?>

<?php
session_start();
include "../includes/db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/login.php");
    exit();
}

// Cek apakah ID artikel ada
if (!isset($_GET['id'])) {
    header("Location: manage_articles.php");
    exit();
}

$id = intval($_GET['id']);
$query = "SELECT * FROM articles WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$article = mysqli_fetch_assoc($result);

if (!$article) {
    echo "<script>alert('Artikel tidak ditemukan!'); window.location.href='manage_articles.php';</script>";
    exit();
}

// Proses Update Artikel
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_article'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $image_path = $article['image']; // Gunakan gambar lama jika tidak ada yang baru

    // Jika ada gambar baru diunggah
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../uploads/";
        $file_name = time() . "_" . basename($_FILES["image"]["name"]);
        $file_path = $target_dir . $file_name;
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        // Validasi ekstensi file
        if (!in_array($file_extension, $allowed_types)) {
            echo "<script>alert('Format file tidak diizinkan. Hanya JPG, JPEG, PNG, dan GIF!');</script>";
        } elseif ($_FILES["image"]["size"] > 2 * 1024 * 1024) { // Maksimum 2MB
            echo "<script>alert('Ukuran file terlalu besar (maksimal 2MB)!');</script>";
        } else {
            // Pindahkan file ke folder uploads
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $file_path)) {
                $image_path = "uploads/" . $file_name;
            } else {
                echo "<script>alert('Upload gambar gagal!');</script>";
            }
        }
    }

    // Update data di database
    $update_query = "UPDATE articles SET title=?, content=?, category=?, author=?, image=? WHERE id=?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "sssssi", $title, $content, $category, $author, $image_path, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Artikel berhasil diperbarui!'); window.location.href='manage_articles.php';</script>";
    } else {
        die("Error SQL: " . mysqli_error($conn));
    }

    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Artikel</title>
    <link rel="stylesheet" href="../assets/css/styles.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .header {
            background: #007BFF;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            padding: 10px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            box-sizing: border-box;
        }

        .button-container {
            display: flex;
            justify-content: space-between;
        }

        button, .back-btn {
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            border: none;
            transition: background 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            display: inline-block;
        }

        .preview-btn {
            background: #17a2b8;
            color: white;
        }

        .save-btn {
            background: #28a745;
            color: white;
        }

        .back-btn {
            background: #dc3545;
            color: white;
        }

        #preview-container {
            display: none;
            border: 1px solid #ddd;
            padding: 20px;
            margin-top: 20px;
            background: #f9f9f9;
            border-radius: 8px;
        }

        #preview-image {
            max-width: 100%;
            display: none;
            margin-top: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="header">
    <a href="manage_articles.php">⬅ Kembali</a>
    <span>Edit Artikel</span>
    <div></div>
</div>

<div class="container">
    <h2>Edit Artikel</h2>
    <form id="edit-form" action="" method="POST" enctype="multipart/form-data">
        <label for="title">Judul Artikel</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($article['title']); ?>" required>

        <label for="category">Kategori</label>
        <select name="category" id="category" required>
            <option value="">Pilih Kategori</option>
            <option value="Kesehatan Mental">Kesehatan Mental</option>
            <option value="Gizi">Gizi</option>
            <option value="Olahraga">Olahraga</option>
            <option value="Pola Hidup Sehat">Pola Hidup Sehat</option>
        </select>

        <label for="author">Penulis</label>
        <input type="text" id="author" name="author" value="<?= htmlspecialchars($article['author']); ?>" required>

        <label for="content">Isi Artikel</label>
        <textarea id="content" name="content" required><?= htmlspecialchars($article['content']); ?></textarea>

        <label for="image">Upload Gambar (Opsional)</label>
        <input type="file" id="image" name="image" accept="image/*">
        
        <button type="submit" name="update_article" class="save-btn">Update</button>
    </form>
</div>

</body>
</html>
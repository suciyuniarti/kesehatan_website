<?php
session_start();
include "../includes/db.php"; 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/login.php");
    exit();
}

// Cek koneksi database
if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_article'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $image_path = "";

    // Upload gambar
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        $file_name = time() . "_" . basename($_FILES["image"]["name"]);
        $image_path = $target_dir . $file_name;
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));

        if (!in_array($file_extension, $allowed_types)) {
            die("Format file tidak diizinkan.");
        }

        if ($_FILES["image"]["size"] > 2 * 1024 * 1024) { 
            die("Ukuran file terlalu besar (maksimal 2MB).");
        }

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], "../" . $image_path)) {
            die("Upload gambar gagal!");
        }
    }

    // Gunakan Prepared Statement
    $query = "INSERT INTO articles (title, content, image, category, author) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssss", $title, $content, $image_path, $category, $author);

    if (mysqli_stmt_execute($stmt)) {
        echo "<script>alert('Artikel berhasil ditambahkan!'); window.location.href='manage_articles.php';</script>";
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
    <title>Tambah Artikel</title>
    <link rel="stylesheet" href="../assets/css/styles.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Header */
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
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 10px;
        }

        button, .button-container a {
            flex: 1;
            text-align: center;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            border: none;
            transition: background 0.3s ease;
            cursor: pointer;
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
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        #preview-container {
            display: none;
            border: 1px solid #ddd;
            padding: 20px;
            margin-top: 20px;
            text-align: left;
            background: #f9f9f9;
            border-radius: 8px;
        }

        #preview-image {
            max-width: 100%;
            display: none;
            margin-top: 10px;
            border-radius: 5px;
        }

        @media (max-width: 480px) {
            .container {
                width: 90%;
                padding: 15px;
            }
        }
    </style>
</head>
<body>

<div class="header">
    <a href="manage_articles.php">⬅ Kembali</a>
    <span>Tambah Artikel</span>
    <div></div>
</div>

<div class="container">
    <h2>Tambah Artikel Baru</h2>
    <form id="article-form" action="add_article.php" method="POST" enctype="multipart/form-data">
        <label for="title">Judul Artikel</label>
        <input type="text" id="title" name="title" required>

        <label for="category">Kategori</label>
        <select name="category" id="category" required>
            <option value="">Pilih Kategori</option>
            <option value="Kesehatan Mental">Kesehatan Mental</option>
            <option value="Gizi">Gizi</option>
            <option value="Olahraga">Olahraga</option>
            <option value="Pola Hidup Sehat">Pola Hidup Sehat</option>
        </select>

        <label for="author">Penulis</label>
        <input type="text" id="author" name="author" required>

        <label for="content">Isi Artikel</label>
        <textarea name="content" id="content" rows="6" required></textarea>

        <label for="image">Upload Gambar</label>
        <input type="file" id="image" name="image" accept="image/*">

        <div class="button-container">
            <button type="button" onclick="previewArticle()" class="preview-btn">Preview</button>
            <button type="submit" name="submit_article" class="save-btn">Simpan</button>
        </div>
    </form>

    <!-- Preview Artikel -->
    <div id="preview-container">
        <h3>Preview Artikel</h3>
        <p><strong>Judul:</strong> <span id="preview-title"></span></p>
        <p><strong>Kategori:</strong> <span id="preview-category"></span></p>
        <p><strong>Penulis:</strong> <span id="preview-author"></span></p>
        <p><strong>Isi:</strong> <span id="preview-content"></span></p>
        <img id="preview-image" src="" alt="Preview Gambar">
    </div>
</div>

<script>
function previewArticle() {
    document.getElementById("preview-title").textContent = document.getElementById("title").value;
    document.getElementById("preview-category").textContent = document.getElementById("category").value;
    document.getElementById("preview-author").textContent = document.getElementById("author").value;
    document.getElementById("preview-content").innerHTML = document.getElementById("content").value.replace(/\n/g, "<br>");


    const file = document.getElementById("image").files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById("preview-image").src = e.target.result;
            document.getElementById("preview-image").style.display = "block";
        };
        reader.readAsDataURL(file);
    }

    document.getElementById("preview-container").style.display = "block";
}
</script>

</body>
</html>

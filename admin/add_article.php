<?php
include "../includes/db.php"; 
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_article'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $image_path = "";

    // Upload gambar
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/"; // Pastikan tanpa "../"
        $file_name = time() . "_" . basename($_FILES["image"]["name"]);
        $image_path = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], "../" . $image_path)) {
            echo "Gambar berhasil diupload!";
        } else {
            echo "Upload gambar gagal!";
        }
    }

    // Simpan ke database
    $query = "INSERT INTO articles (title, content, image, category, author) 
              VALUES ('$title', '$content', '$image_path', '$category', '$author')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Artikel berhasil ditambahkan!'); window.location.href='manage_articles.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Artikel</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    
    <script>
        function previewArticle() {
            let title = document.getElementById("title").value;
            let category = document.getElementById("category").value;
            let author = document.getElementById("author").value;
            let content = document.getElementById("content").value;
            let imageInput = document.getElementById("image");
            let previewImage = document.getElementById("preview-image");

            document.getElementById("preview-title").innerText = title;
            document.getElementById("preview-category").innerText = "Kategori: " + category;
            document.getElementById("preview-author").innerText = "Penulis: " + author;
            document.getElementById("preview-content").innerHTML = content.replace(/\n/g, "<br>"); 

            if (imageInput.files.length > 0) {
                let file = imageInput.files[0];
                let reader = new FileReader();
                reader.onload = function (e) {
                    previewImage.src = e.target.result;
                    previewImage.style.display = "block";
                };
                reader.readAsDataURL(file);
            } else {
                previewImage.style.display = "none";
            }

            document.getElementById("preview-container").style.display = "block";
        }
    </script>

    <style>
        .container {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
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
    </style>
</head>
<body>

<div class="container">
    <h2>Tambah Artikel Baru</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="title">Judul Artikel</label>
        <input type="text" id="title" name="title" required>

        <label for="category">Kategori</label>
        <select id="category" name="category" required>
            <option value="Kesehatan">Kesehatan</option>
            <option value="Gizi">Gizi</option>
        </select>

        <label for="author">Penulis</label>
        <input type="text" id="author" name="author" required>

        <label for="content">Isi Artikel</label>
        <textarea id="content" name="content" required></textarea>

        <label for="image">Upload Gambar</label>
        <input type="file" id="image" name="image" accept="image/*">

        <div class="button-container">
            <button type="button" class="preview-btn" onclick="previewArticle()">Preview</button>
            <button type="submit" name="submit_article" class="save-btn">Simpan</button>
            <a href="manage_articles.php" class="back-btn">Kembali</a>
        </div>
    </form>

    <div id="preview-container">
        <h2 id="preview-title"></h2>
        <p id="preview-category"></p>
        <p id="preview-author"></p>
        <img id="preview-image">
        <p id="preview-content"></p>
    </div>
</div>

</body>
</html>

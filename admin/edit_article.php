<?php
include "../includes/db.php"; 
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data artikel berdasarkan ID
    $query = "SELECT * FROM articles WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $article = mysqli_fetch_assoc($result);
}

// Proses update artikel
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_article'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $image_path = $article['image'];

    // Cek jika ada gambar baru yang diupload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../uploads/";
        $image_path = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
    }

    // Update database
    $update_query = "UPDATE articles 
                     SET title='$title', content='$content', category='$category', author='$author', image='$image_path' 
                     WHERE id=$id";
    
    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Artikel berhasil diperbarui!'); window.location.href='manage_articles.php';</script>";
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
    <title>Edit Artikel</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<div class="container">
    <h2>Edit Artikel</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        <label for="title">Judul Artikel</label>
        <input type="text" id="title" name="title" value="<?= $article['title']; ?>" required>

        <label for="category">Kategori</label>
        <select id="category" name="category" required>
            <option value="Kesehatan" <?= $article['category'] == 'Kesehatan' ? 'selected' : ''; ?>>Kesehatan</option>
            <option value="Gizi" <?= $article['category'] == 'Gizi' ? 'selected' : ''; ?>>Gizi</option>
            <option value="Olahraga" <?= $article['category'] == 'Olahraga' ? 'selected' : ''; ?>>Olahraga</option>
            <option value="Mental Health" <?= $article['category'] == 'Mental Health' ? 'selected' : ''; ?>>Mental Health</option>
        </select>

        <label for="author">Penulis</label>
        <input type="text" id="author" name="author" value="<?= $article['author']; ?>" required>

        <label for="content">Isi Artikel</label>
        <textarea id="content" name="content" required><?= $article['content']; ?></textarea>

        <label for="image">Upload Gambar (Opsional)</label>
        <?php if (!empty($article['image'])): ?>
            <p>Gambar saat ini:</p>
            <img src="<?= $article['image']; ?>" width="200" style="margin-bottom: 10px;">
        <?php endif; ?>
        <input type="file" id="image" name="image" accept="image/*">

        <div class="button-container">
            <button type="submit" name="update_article" class="save-btn">Update Artikel</button>
            <a href="manage_articles.php" class="back-btn" style="text-decoration:none; text-align:center; padding:10px; display:inline-block;">Kembali</a>
        </div>
    </form>
</div>

</body>
</html>
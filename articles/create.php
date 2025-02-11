<?php
session_start();
include "../includes/db.php"; 

// Redirect jika user belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../users/login.php");
    exit();
}

// Ambil nama user login
$user_id = $_SESSION['user_id'];
$query_user = $conn->prepare("SELECT username FROM users WHERE id = ?");
$query_user->bind_param("i", $user_id);
$query_user->execute();
$result = $query_user->get_result();
$user = $result->fetch_assoc();
$username = $user['username'];

$error = ""; // Untuk menyimpan pesan error

// Proses penyimpanan artikel
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $category = trim($_POST['category']);
    
    // Validasi: Pastikan semua input tidak kosong
    if (empty($title) || empty($content) || empty($category) || empty($_FILES["image"]["name"])) {
        $error = "Semua bidang harus diisi, termasuk gambar!";
    } else {
        // Pastikan folder "uploads/" ada
        if (!is_dir("../uploads/")) {
            mkdir("../uploads/", 0777, true);
        }

        // Proses upload gambar
        $file_name = time() . "_" . basename($_FILES["image"]["name"]);
        $target_dir = "uploads/";
        $image_path = $target_dir . $file_name;
        $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));

        $allowed_types = ['jpg', 'jpeg', 'png'];
        if (!in_array($file_extension, $allowed_types)) {
            $error = "Format gambar hanya boleh JPG, JPEG, atau PNG!";
        } elseif ($_FILES["image"]["size"] > 2 * 1024 * 1024) { // Maksimal 2MB
            $error = "Ukuran file terlalu besar (maksimal 2MB).";
        } else {
            // Pindahkan file ke folder uploads/
            if (move_uploaded_file($_FILES["image"]["tmp_name"], "../" . $image_path)) {
                // Simpan path gambar ke database (tanpa "../")
                $sql = "INSERT INTO articles (user_id, title, content, category, image, created_at) 
                        VALUES (?, ?, ?, ?, ?, NOW())";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("issss", $user_id, $title, $content, $category, $image_path);
                
                if ($stmt->execute()) {
                    header("Location: index.php");
                    exit();
                } else {
                    $error = "Gagal menyimpan artikel.";
                }
            } else {
                $error = "Gagal mengunggah gambar.";
            }
        }
    }
}

include "../includes/header.php"; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tulis Artikel</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        .container {
            max-width: 800px;
            margin: 80px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        h2 {
            text-align: center;
            color: #1565C0;
            margin-bottom: 20px;
        }

        input, textarea, select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn-submit {
            background: #1565C0;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
            font-size: 16px;
        }

        .btn-submit:hover {
            background: #0d47a1;
        }

        .error-message {
            color: red;
            text-align: center;
            font-weight: bold;
            background: #ffe6e6;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
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
    <h2>Tulis Artikel</h2>

    <?php if (!empty($error)): ?>
        <p class="error-message"><?= htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form action="create.php" method="POST" enctype="multipart/form-data">
        <label for="title">Judul Artikel</label>
        <input type="text" name="title" id="title" required>

        <label for="category">Kategori</label>
        <select name="category" id="category" required>
            <option value="">Pilih Kategori</option>
            <option value="Kesehatan Mental">Kesehatan Mental</option>
            <option value="Gizi">Gizi</option>
            <option value="Olahraga">Olahraga</option>
            <option value="Pola Hidup Sehat">Pola Hidup Sehat</option>
        </select>

        <label for="image">Upload Gambar <span style="color: red;">(Wajib JPG, JPEG, PNG)</span></label>
        <input type="file" name="image" id="image" accept=".jpg, .jpeg, .png" required>

        <label for="content">Konten Artikel</label>
        <textarea name="content" id="content" rows="6" required></textarea>

        <button type="submit" class="btn-submit">Publikasikan</button>
    </form>
</div>

<?php include "../includes/footer.php"; ?>

</body>
</html>

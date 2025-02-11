<?php
session_start();
include "../includes/db.php";

// Redirect jika user belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../users/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Pastikan ada ID artikel yang dikirim
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$article_id = intval($_GET['id']);

// Ambil data artikel yang sesuai dengan user
$sql = "SELECT id, title, content FROM articles WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $article_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='error-msg'>Artikel tidak ditemukan atau bukan milik Anda!</div>";
    exit();
}

$article = $result->fetch_assoc();

// Proses update artikel
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (!empty($title) && !empty($content)) {
        $update_sql = "UPDATE articles SET title = ?, content = ? WHERE id = ? AND user_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssii", $title, $content, $article_id, $user_id);
        if ($update_stmt->execute()) {
            echo "<script>alert('Artikel berhasil diperbarui!'); window.location.href='dashboard.php';</script>";
            exit();
        } else {
            echo "<div class='error-msg'>Terjadi kesalahan saat memperbarui artikel.</div>";
        }
    } else {
        echo "<div class='error-msg'>Judul dan konten tidak boleh kosong!</div>";
    }
}

include "../includes/header.php";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Artikel</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
        }

        body {
            background: #f4f7f6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .container {
            width: 90%;
            max-width: 600px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin: auto;
            margin-top: 50px;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
            color: #34495e;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        textarea {
            resize: vertical;
            min-height: 150px;
        }

        .btn-submit {
            background: #27ae60;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
            width: 100%;
            transition: 0.3s;
        }

        .btn-submit:hover {
            background: #219150;
        }

        .btn-back {
            background: #c0392b;
            text-align: center;
            color: white;
            padding: 12px;
            display: block;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 15px;
            font-size: 16px;
            transition: 0.3s;
        }

        .btn-back:hover {
            background: #a83226;
        }

        .error-msg {
            background: #e74c3c;
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
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
    <h2>📝 Edit Artikel</h2>
    
    <form method="POST">
        <label for="title">Judul:</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($article['title']); ?>" required>

        <label for="content">Konten:</label>
        <textarea id="content" name="content" rows="6" required><?= htmlspecialchars($article['content']); ?></textarea>

        <button type="submit" class="btn-submit">✅ Simpan Perubahan</button>
    </form>

    <a href="dashboard.php" class="btn-back">🔙 Kembali</a>
</div>


<?php include "../includes/footer.php"; ?>

</body>
</html>

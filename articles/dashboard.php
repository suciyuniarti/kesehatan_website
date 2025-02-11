<?php
session_start();
include "../includes/db.php"; 

// Redirect jika user belum login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../users/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil daftar artikel user dari database
$sql = "SELECT id, title, created_at FROM articles WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

include "../includes/header.php"; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Artikel</title>
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

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }

        th {
            background: #1565C0;
            color: white;
        }

        .btn-edit {
            background: #ff9800;
            padding: 5px 8px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
        }

        .btn-delete {
            background: #e53935;
            padding: 5px 8px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
        }

        .btn-edit:hover, .btn-delete:hover {
            opacity: 0.8;
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
    <h2>Dashboard Artikel</h2>

    <table>
        <tr>
            <th>Judul</th>
            <th>Tanggal Dibuat</th>
            <th>Aksi</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['title']); ?></td>
            <td><?= $row['created_at']; ?></td>
            <td>
                <a href="edit.php?id=<?= $row['id']; ?>" class="btn-edit">Edit</a>
                <a href="delete.php?id=<?= $row['id']; ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <a href="create.php" class="btn-submit" style="display: block; text-align: center; margin-top: 20px;">Tulis Artikel Baru</a>
</div>

<?php include "../includes/footer.php"; ?>

</body>
</html>

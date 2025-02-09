<?php
include "../includes/db.php"; // Load koneksi database
session_start();

// Cek apakah user sudah login dan memiliki role admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Artikel</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f8f9fa;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background: #28a745;
            color: white;
        }
        tr:nth-child(even) {
            background: #f2f2f2;
        }
        .action-buttons a {
            padding: 5px 10px;
            text-decoration: none;
            margin: 2px;
            border-radius: 5px;
        }
        .edit-btn {
            background: #ffc107;
            color: black;
        }
        .delete-btn {
            background: #dc3545;
            color: white;
        }
        .add-btn {
            display: inline-block;
            margin-bottom: 10px;
            padding: 10px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Manajemen Artikel</h2>
    <a href="add_article.php" class="add-btn">+ Tambah Artikel</a>

    <table border="1">
    <tr>
        <th>ID</th>
        <th>Gambar</th>
        <th>Judul</th>
        <th>Kategori</th>
        <th>Penulis</th>
        <th>Tanggal Publikasi</th>
        <th>Aksi</th>
    </tr>

    <?php
    $query = "SELECT * FROM articles ORDER BY created_at DESC";
    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>".$row['id']."</td>";
            echo "<td><img src='".$row['image']."' width='100'></td>";
            echo "<td>".$row['title']."</td>";
            echo "<td>".$row['category']."</td>";
            echo "<td>".$row['author']."</td>";
            echo "<td>".date('d-m-Y H:i', strtotime($row['created_at']))."</td>";
            echo "<td>
                <a href='edit_article.php?id=".$row['id']."'>Edit</a> |
                <a href='delete_article.php?id=".$row['id']."' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
            </td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>Gagal mengambil data artikel.</td></tr>";
    }
    ?>
</table>
</div>

</body>
</html>
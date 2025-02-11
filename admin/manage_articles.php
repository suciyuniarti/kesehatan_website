<?php 
include "../includes/header_admin.php"; 
include "../includes/db.php"; 

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
            background-color: #E3F2FD;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1100px;
            width: 95%;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            overflow-x: auto; /* Agar tabel bisa di-scroll di layar kecil */
        }

        h2 {
            text-align: center;
        }

        .add-btn {
            display: inline-block;
            margin-bottom: 15px;
            padding: 10px 15px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
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

        td img {
            width: 80px; /* Gambar lebih proporsional */
            height: auto;
            border-radius: 5px;
        }

        /* Style Tombol */
        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }

        .action-buttons a {
            padding: 6px 10px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
            display: inline-block;
            width: 70px;
        }

        .edit-btn {
            background: #ffc107;
            color: black;
        }

        .delete-btn {
            background: #dc3545;
            color: white;
        }

        /* Responsif: Buat tabel bisa di-scroll di layar kecil */
        @media screen and (max-width: 768px) {
            .container {
                padding: 15px;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }

            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
            

            .action-buttons a {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Manajemen Artikel</h2>
    <a href="add_article.php" class="add-btn">+ Tambah Artikel</a>

    <table>
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
                echo "<td><img src='../".$row['image']."' alt='Gambar Artikel'></td>";
                echo "<td>".$row['title']."</td>";
                echo "<td>".$row['category']."</td>";
                echo "<td>".$row['author']."</td>";
                echo "<td>".date('d-m-Y H:i', strtotime($row['created_at']))."</td>";
                echo "<td class='action-buttons'>
                    <a href='edit_article.php?id=".$row['id']."' class='edit-btn'>Edit</a> 
                    <a href='delete_article.php?id=".$row['id']."' class='delete-btn' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
                </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7'>Tidak ada artikel yang tersedia.</td></tr>";
        }
        ?>
    </table>
</div>

</body>
</html>

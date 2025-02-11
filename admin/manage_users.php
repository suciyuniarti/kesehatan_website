<?php
session_start();
include "../includes/db.php"; // Koneksi ke database

// Pastikan hanya admin yang bisa mengakses halaman ini
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/login.php");
    exit();
}

// Ambil data pengguna dari database
$user_query = "SELECT id, username, email, role FROM users";
$user_result = $conn->query($user_query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #E3F2FD;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .main-content {
            padding: 20px;
            width: 95%;
            max-width: 1000px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .table-container {
            overflow-x: auto;
            width: 100%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 600px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
            white-space: nowrap;
        }

        th {
            background: #1E88E5;
            color: white;
        }

        tr:nth-child(even) {
            background: #f9f9f9;
        }

        .action-btn {
            display: inline-block;
            padding: 7px 12px;
            font-size: 14px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
            transition: 0.3s;
        }

        .edit-btn {
            background: #FFC107;
        }

        .edit-btn:hover {
            background: #E0A800;
        }

        .delete-btn {
            background: #D32F2F;
        }

        .delete-btn:hover {
            background: #B71C1C;
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            .main-content {
                width: 90%;
                padding: 15px;
            }

            table {
                font-size: 14px;
            }

            th, td {
                padding: 8px;
            }

            .action-btn {
                font-size: 12px;
                padding: 5px 8px;
            }
        }
    </style>
</head>
<body>

<?php include "../includes/header_admin.php"; ?>

<div class="main-content">
    <h2>Kelola Pengguna</h2>

    <div class="table-container">
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
            <?php while ($user = $user_result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($user['id']); ?></td>
                <td><?= htmlspecialchars($user['username']); ?></td>
                <td><?= htmlspecialchars($user['email']); ?></td>
                <td><?= htmlspecialchars($user['role']); ?></td>
                <td>
                    <a href="edit_users.php?id=<?= $user['id']; ?>" class="action-btn edit-btn">Edit</a>
                    <a href="delete_users.php?id=<?= $user['id']; ?>" class="action-btn delete-btn" onclick="return confirm('Hapus pengguna ini?')">Hapus</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>

</body>
</html>

<?php
session_start();
include "../includes/db.php";

// Cek apakah pengguna adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../users/login.php");
    exit();
}

// Pastikan ada parameter ID yang valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_users.php");
    exit();
}

$user_id = intval($_GET['id']);
$user_query = "SELECT id, username, email, role FROM users WHERE id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    header("Location: manage_users.php");
    exit();
}

// Proses update data pengguna
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $role = $_POST['role'];

    if (!empty($username) && !empty($role)) {
        $update_sql = "UPDATE users SET username = ?, role = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssi", $username, $role, $user_id);

        if ($update_stmt->execute()) {
            header("Location: manage_users.php?success=1");
            exit();
        } else {
            $error_message = "Terjadi kesalahan saat memperbarui data.";
        }
    } else {
        $error_message = "Username dan role tidak boleh kosong!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #E3F2FD;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .main-content {
            padding: 20px;
            width: 100%;
            max-width: 400px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        input, select {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            width: 100%;
        }

        button {
            background: #1E88E5;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #1565C0;
        }

        .error-message {
            color: red;
            margin-bottom: 10px;
        }

        .back-link {
            display: inline-block;
            margin-top: 10px;
            color: #1E88E5;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<?php include "../includes/header_admin.php"; ?>

<div class="main-content">
    <h2>Edit Pengguna</h2>

    <?php if (isset($error_message)): ?>
        <p class="error-message"><?= htmlspecialchars($error_message); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']); ?>" required>

        <label for="role">Role:</label>
        <select id="role" name="role" required>
            <option value="admin" <?= ($user['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
            <option value="user" <?= ($user['role'] == 'user') ? 'selected' : ''; ?>>User</option>
        </select>

        <button type="submit">Simpan Perubahan</button>
    </form>

    <a href="manage_users.php" class="back-link">Kembali ke Kelola Pengguna</a>
</div>

</body>
</html>

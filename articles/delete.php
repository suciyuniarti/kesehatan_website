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

// Periksa apakah artikel milik user yang login
$sql = "DELETE FROM articles WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $article_id, $user_id);

if ($stmt->execute()) {
    header("Location: dashboard.php");
    exit();
} else {
    echo "Gagal menghapus artikel!";
}
?>

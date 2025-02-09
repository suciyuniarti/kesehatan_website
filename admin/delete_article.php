<?php
include "../includes/db.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM articles WHERE id=$id";

    if (mysqli_query($conn, $query)) {
        header("Location: manage_articles.php");
    } else {
        echo "Gagal menghapus artikel.";
    }
}
?>
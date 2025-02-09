<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kesehatan_db";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi Gagal: " . $conn->connect_error);
}

// Set base URL
$base_url = "http://localhost/kesehatan_website/";
?>
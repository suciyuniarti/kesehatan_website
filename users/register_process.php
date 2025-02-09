<?php
require_once "../includes/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash password

    // Cek apakah email sudah digunakan
    $check_email = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $check_email->store_result();

    if ($check_email->num_rows > 0) {
        echo "Email sudah terdaftar!";
    } else {
        // Simpan ke database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            echo "Registrasi berhasil! <a href='login.php'>Login sekarang</a>";
        } else {
            echo "Terjadi kesalahan!";
        }
    }

    $check_email->close();
    $stmt->close();
    $conn->close();
}
?>
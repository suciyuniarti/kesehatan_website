<?php
include "../includes/header.php";
include "../includes/db.php";
include "../config.php";  // Pastikan $base_url bisa digunakan di sini

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Redirect berdasarkan role
        $redirect_url = ($user['role'] == 'admin') ? "admin/dashboard.php" : "index.php";
        header("Location: " . $base_url . $redirect_url);
        exit();
    } else {
        $error = "Email atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="<?php echo $base_url; ?>css/style.css">
</head>
<body>

    <main>
        <section class="login-container">
            <h2>Login</h2>

            <?php if (isset($error)): ?>
                <div class="error-message"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>

                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>

                <button type="submit" class="btn-login">Login</button>
            </form>
        </section>
    </main>

    <?php include "../includes/footer.php"; ?>

</body>
</html>

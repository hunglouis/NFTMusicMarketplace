<?php
session_start(); // Bắt đầu phiên làm việc
require 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    // Tìm xem có tên đăng nhập này trong DB không
    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$user'");
    $row = mysqli_fetch_assoc($result);
    // Kiểm tra mật khẩu (giải mã so với mật khẩu đã mã hóa trong DB)
    if ($row && password_verify($pass, $row['password'])) {
        $_SESSION['user'] = $row['username']; // Lưu tên vào Session
        header("Location: index.php"); // Chuyển hướng sang trang chủ
        exit();
    } else {
        $error = "Sai tên đăng nhập hoặc mật khẩu!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Đăng Nhập</title>
    <style>
        body { font-family: Arial; display: flex; justify-content: center; padding-top: 50px; background: #e9ecef; }
        .login-box { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); width: 300px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; cursor: pointer; }
        .error { color: red; font-size: 14px; }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?> <!-- Chèn thanh điều hướng -->
    <div class="login-box">
        <h2>Đăng Nhập</h2>
        <?php if($error) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Tên đăng nhập" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit">Vào hệ thống</button>
        </form>
        <p>Chưa có tài khoản? <a href="dangky.php">Đăng ký</a></p>
    </div>
</body>
</html>

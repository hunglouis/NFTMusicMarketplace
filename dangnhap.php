<?php
session_start(); // Bắt đầu phiên làm việc
require_once 'db.php';

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
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quỳnh Hương - Genesis Edition</title>
    <!-- Link làm đẹp giao diện -->
    <script src="https://tailwindcss.com"></script>
    <link href="https://cloudflare.com" rel="stylesheet">
    <style>
        body { background: radial-gradient(circle at top right, #0891b2, #064e3b, #020617); min-height: 100vh; color: white; }
        .glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(6, 182, 212, 0.2); }
    </style>
</head>
<body class="p-10">
    <!-- Toàn bộ phần vòng lặp foreach của bạn nằm ở đây -->

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

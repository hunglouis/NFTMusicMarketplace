<?php
require_once 'db.php'; // Gọi file kết nối database

$thongbao = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    // Mã hóa mật khẩu để bảo mật (quan trọng!)
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Câu lệnh SQL để lưu vào bảng users
    $sql = "INSERT INTO users (username, password) VALUES ('$user', '$pass')";
    
    if (mysqli_query($conn, $sql)) {
        $thongbao = "<p style='color:green;'>Đăng ký thành công! <a href='dangnhap.php'>Đăng nhập ngay</a></p>";
    } else {
        $thongbao = "<p style='color:red;'>Lỗi: " . mysqli_error($conn) . "</p>";
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
    <div class="form-box">
        <h2>Đăng Ký</h2>
        <?php echo $thongbao; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Tên đăng nhập" required>
            <input type="password" name="password" placeholder="Mật khẩu" required>
            <button type="submit">Xác nhận Đăng ký</button>
        </form>
    </div>
</body>
</html>

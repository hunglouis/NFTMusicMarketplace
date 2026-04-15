<?php
require 'db.php'; // Gọi file kết nối database

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
<html>
<head>
    <title>Trang Đăng Ký</title>
    <style>
        body { font-family: Arial; display: flex; justify-content: center; padding-top: 50px; background: #f4f4f4; }
        .form-box { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); width: 300px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ddd; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #28a745; color: white; border: none; cursor: pointer; }
        button:hover { background: #218838; }
    </style>
</head>
<body>
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

<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user'])) { header("Location: dangnhap.php"); exit(); }

$thongbao = "";
$user = $_SESSION['user'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_pass = $_POST['old_password'];
    $new_pass = $_POST['new_password'];

    // Lấy mật khẩu hiện tại trong DB
    $res = mysqli_query($conn, "SELECT password FROM users WHERE username='$user'");
    $row = mysqli_fetch_assoc($res);

    if (password_verify($old_pass, $row['password'])) {
        $new_pass_hash = password_hash($new_pass, PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE users SET password='$new_pass_hash' WHERE username='$user'");
        $thongbao = "<p style='color:green;'>Đổi mật khẩu thành công!</p>";
    } else {
        $thongbao = "<p style='color:red;'>Mật khẩu cũ không chính xác!</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Đổi mật khẩu</title></head>
<body style="font-family: Arial; text-align: center; padding-top: 50px;">
    <?php include 'navbar.php'; ?> <!-- Chèn thanh điều hướng -->
    <div style="width: 300px; margin: auto; padding: 20px; border: 1px solid #ddd;">
        <h2>Đổi mật khẩu</h2>
        <?php echo $thongbao; ?>
        <form method="POST">
            <input type="password" name="old_password" placeholder="Mật khẩu cũ" required style="width:100%; margin-bottom:10px; padding:8px;">
            <input type="password" name="new_password" placeholder="Mật khẩu mới" required style="width:100%; margin-bottom:10px; padding:8px;">
            <button type="submit" style="width:100%; padding:10px; background: #ffc107; border:none; cursor:pointer;">Cập nhật</button>
        </form>
        <br><a href="index.php">Quay lại trang chủ</a>
    </div>
</body>
</html>

<?php
session_start();
require_once 'db.php';
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

</html>

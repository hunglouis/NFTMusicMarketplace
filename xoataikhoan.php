<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user'])) { header("Location: dangnhap.php"); exit(); }

$user = $_SESSION['user'];

// Xóa User khỏi bảng users
$sql = "DELETE FROM users WHERE username='$user'";

if (mysqli_query($conn, $sql)) {
    session_destroy(); // Xóa session để đăng xuất luôn
    echo "<script>alert('Tài khoản đã bị xóa vĩnh viễn.'); window.location.href='dangky.php';</script>";
} else {
    echo "Lỗi khi xóa: " . mysqli_error($conn);
}
?>

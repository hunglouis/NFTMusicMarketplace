<?php
session_start();
session_destroy(); // Xóa sạch session
header("Location: dangnhap.php");
exit();
?>

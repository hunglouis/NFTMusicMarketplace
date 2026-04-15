<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "hethong_user";

// Kết nối tới MySQL
$conn = mysqli_connect($host, $user, $pass, $dbname);

// Kiểm tra kết nối
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
} else {
    
}
?>

<?php
session_start();
require_once 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $user = $_SESSION['user'];

    // Chỉ xóa nếu đúng chủ nhân của bình luận đó
    $sql = "DELETE FROM comments WHERE id='$id' AND username='$user'";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php"); // Xóa xong quay về trang chủ
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }
}
?>

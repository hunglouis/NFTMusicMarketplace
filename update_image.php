<?php
<<<<<<< HEAD
require_once 'db.php'; 
=======
require_once 'db.php';
>>>>>>> c80f28e699f8a654a555ab53c88a7f61a5001b85

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $song_id = $_POST['song_id'];
    $new_url = $_POST['new_image_url'];

    // Cập nhật link ảnh vào database
    $query = "UPDATE music_collection SET image_url = '$new_url' WHERE id = '$song_id'";
    
    if (mysqli_query($conn, $query)) {
        // Lưu xong thì quay về dashboard
        header("Location: dashboard.php?msg=success");
        exit();
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }
} // <--- Đây chính là dấu đóng ngoặc anh bị thiếu này!
?>

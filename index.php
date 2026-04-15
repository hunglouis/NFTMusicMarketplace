<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user'])) { header("Location: dangnhap.php"); exit(); }

$user = $_SESSION['user'];

// Xử lý khi nhấn nút Tải ảnh
if (isset($_POST['upload'])) {
    $file_name = $_FILES['name'];
    $target = "uploads/" . basename($file_name);

    if (move_uploaded_file($_FILES['tmp_name'], $target)) {
        mysqli_query($conn, "UPDATE users SET avatar='$file_name' WHERE username='$user'");
        echo "<script>alert('Tải ảnh thành công!');</script>";
    }
}

// Lấy thông tin ảnh từ DB để hiển thị
$res = mysqli_query($conn, "SELECT avatar FROM users WHERE username='$user'");
$row = mysqli_fetch_assoc($res);
$avatar = $row['avatar'] ? $row['avatar'] : 'default.png';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Trang cá nhân</title>
</head>
<body style="text-align: center; font-family: Arial; padding-top: 50px;">
    <?php include 'navbar.php'; ?> <!-- Chèn thanh điều hướng -->
    <h1>Chào mừng, <?php echo $user; ?>! 🎉</h1>
    
    <!-- Hiển thị ảnh đại diện -->
    <img src="uploads/<?php echo $avatar; ?>" width="150" height="150" style="border-radius: 50%; object-fit: cover; border: 3px solid #007bff;">
    
    <form method="POST" enctype="multipart/form-data" style="margin-top: 20px;">
        <input type="file" name="image" required>
        <button type="submit" name="upload">Đổi ảnh đại diện</button>
    </form>

    <br><br>
    <a href="logout.php" style="color: red;">Đăng xuất</a>
    <br><br>
<a href="xoataikhoan.php" onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản vĩnh viễn không?')" style="color: grey; font-size: 12px;">Xóa tài khoản này</a>
<?php
// ... giữ nguyên phần code cũ của index.php ...

// 1. Xử lý khi người dùng nhấn nút Gửi bình luận
if (isset($_POST['send_comment'])) {
    $noidung = mysqli_real_escape_string($conn, $_POST['noidung']);
    $user = $_SESSION['user'];

    if (!empty($noidung)) {
        mysqli_query($conn, "INSERT INTO comments (username, content) VALUES ('$user', '$noidung')");
    }
}

// 2. Lấy danh sách bình luận từ Database ra
$ds_binhluan = mysqli_query($conn, "SELECT * FROM comments ORDER BY created_at DESC");
?>

<!-- Giao diện Khung bình luận -->
<div style="width: 500px; margin: 30px auto; text-align: left; border-top: 1px solid #ccc; padding-top: 20px;">
    <h3>Bình luận</h3>
    
    <form method="POST">
        <textarea name="noidung" placeholder="Viết gì đó..." style="width: 100%; height: 60px; padding: 10px;"></textarea>
        <button type="submit" name="send_comment" style="margin-top: 10px; background: #28a745; color: white; border: none; padding: 10px 20px; cursor: pointer;">Gửi</button>
    </form>

    <div style="margin-top: 20px;">
        <?php while($bl = mysqli_fetch_assoc($ds_binhluan)): ?>
            <div style="background: #f9f9f9; padding: 10px; border-bottom: 1px solid #eee; margin-bottom: 10px;">
                <strong><?php echo $bl['username']; ?>:</strong> 
                <span><?php echo $bl['content']; ?></span>
                <div style="font-size: 10px; color: gray;"><?php echo $bl['created_at']; ?></div>
             <!-- Chỉ hiện nút Xóa nếu đúng là chủ nhân bình luận -->
            <?php if ($bl['username'] == $_SESSION['user']): ?>
                <a href="xoabinhluan.php?id=<?php echo $bl['id']; ?>" 
                   onclick="return confirm('Bạn muốn xóa bình luận này?')"
                   style="color: red; font-size: 11px; text-decoration: none; position: absolute; right: 10px; top: 10px;">
                   [Xóa]
                </a>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</div>
</div>

</body>
</html>

<?php
session_start();
require 'supabase_config.php';
require 'finance_logic.php'; 

if (!isset($_SESSION['user'])) { header("Location: dangnhap.php"); exit(); }

$user = $_SESSION['user'];
$song_id = isset($_GET['id']) ? $_GET['id'] : 1; 
// 1. Lấy thông tin bài hát từ kho
$res_song = mysqli_query($conn, "SELECT * FROM music_collection WHERE id='$song_id'");
$song = mysqli_fetch_assoc($res_song);
if (!$song) {
    die("Không tìm thấy bài hát này trong hệ thống!");
}
// Tăng số lượt nghe lên 1 mỗi khi trang này được tải
mysqli_query($conn, "UPDATE music_collection SET views = views + 1 WHERE id = '$song_id'");

// 2. KIỂM TRA QUYỀN SỞ HỮU (Dòng 18 quan trọng ở đây)
// Chúng ta tìm xem có khối nào mà bạn là người gửi tiền với nội dung là tên bài hát này không
$title = mysqli_real_escape_string($conn, $song['title']);
$check_owner = mysqli_query($conn, "SELECT * FROM blockchain WHERE sender='$user' AND content='$title'");

// Sửa lỗi: Kiểm tra xem truy vấn có thành công không trước khi đếm dòng
$is_owner = ($check_owner && mysqli_num_rows($check_owner) > 0);

// 3. Quyết định file nhạc
$file_to_play = $is_owner ? $song['full_file'] : $song['demo_file'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Studio Nhạc Mạnh Hùng</title>
    <style>
        body { background: #121212; color: white; font-family: sans-serif; text-align: center; padding-top: 50px; }
        .player-card { background: #1e1e1e; width: 450px; margin: auto; padding: 30px; border-radius: 15px; border: 1px solid #333; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .status-badge { padding: 8px 15px; border-radius: 20px; font-size: 13px; font-weight: bold; display: inline-block; margin-bottom: 20px; }
        .owned { background: #238636; color: white; } 
        .guest { background: #f2e711; color: black; }
        audio { width: 100%; margin-top: 20px; filter: invert(100%); }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="player-card">
        <h1 style="font-size: 60px; margin: 0;">💿</h1>
        <h2 style="color: #58a6ff;"><?php echo $song['title']; ?></h2>
        <p style="color: #8b949e;">Tác giả: Nhạc sĩ Mạnh Hùng</p>

        <?php if ($is_owner): ?>
            <div class="status-badge owned">✅ CHỨNG CHỈ SỞ HỮU HỢP LỆ</div>
            <p style="color: #238636; font-size: 14px;">Bạn đang nghe bản Full chất lượng cao.</p>
        <?php else: ?>
            <div class="status-badge guest">⚠️ CHẾ ĐỘ NGHE THỬ</div>
            <p style="color: #8b949e; font-size: 14px;">Vui lòng mua tác phẩm để mở khóa bản Full.</p>
        <?php endif; ?>

        <audio controls controlsList="nodownload" autoplay>
            <source src="uploads/<?php echo $file_to_play; ?>" type="audio/mpeg">
            Trình duyệt không hỗ trợ audio.
        </audio>
        
        <br><br>
        <a href="marketplace.php" style="color: #8b949e; text-decoration: none; font-size: 13px;">← Quay lại Chợ Nhạc</a>
    </div>
</body>
</html>
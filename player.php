<?php
session_start();
require_once 'db.php';
require 'finance_logic.php';

if (!isset($_SESSION['user'])) { 
    header("Location: dangnhap.php"); 
    exit(); 
}

$user = $_SESSION['user'];
$song_id = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// 1) Lấy thông tin bài hát
$res_song = callSupabase(
    "hunglouis?select=*&id=eq." . $song_id . "&limit=1"
);

// Debug nhanh (tạm thời mở dòng này để xem API trả gì)
var_dump($res_song);

if (!is_array($res_song) || count($res_song) === 0) {
    die("Không tìm thấy bài hát này trong hệ thống! (id=" . $song_id . ")");
}

$song = $res_song[0];

// 1.1) Tăng views lên 1
$current_views = (int)($song['views'] ?? 0);

callSupabase(
    "hunglouis?id=eq." . $song_id,
    "PATCH",
    ["views" => $current_views + 1]
);

// 2) KIỂM TRA QUYỀN SỞ HỮU
$title = (string)($song['title'] ?? "");

// Kiểm tra blockchain: sender = user và content = title
$check_owner = callSupabase(
    "blockchain?select=id&sender=eq." . urlencode($user) . "&content=eq." . urlencode($title) . "&limit=1"
);

$is_owner = (is_array($check_owner) && count($check_owner) > 0);

// 3) Quyết định file nhạc
$file_to_play = $is_owner ? ($song['full_file'] ?? '') : ($song['demo_file'] ?? '');
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
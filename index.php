<?php
session_start();
require 'db.php';

// 1. Lấy dữ liệu bài hát và đặt tên là $songs
$songs = callSupabase("hunglouis?select=*");

// 2. Kiểm tra nếu có lỗi kết nối Supabase
if (isset($songs['error']) || isset($songs['code'])) {
    echo "<h3 style='color:red;'>❌ LỖI KẾT NỐI SUPABASE. Vui lòng kiểm tra lại Key!</h3>";
    exit; // Dừng lại nếu lỗi thật sự
}

// KHÔNG DÙNG ECHO HAY PRINT_R Ở ĐÂY NỮA ĐỂ TRANG WEB TIẾP TỤC CHẠY XUỐNG DƯỚI
?>

<div class="grid">
    <?php if (is_array($songs)): ?>
        <?php foreach($songs as $s): ?>
            <div class="card">
                <h3><?php echo $s['name']; ?></h3> <!-- Dựa vào ảnh, cột của bạn tên là 'name' -->
                <img src="<?php echo $storageUrl . $s['image_path']; ?>" class="w-full h-48 object-cover rounded-2xl" alt="Hoa Quỳnh">

            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

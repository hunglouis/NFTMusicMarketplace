<?php
session_start();
// Bật chế độ "Soi lỗi" mức cao nhất
ini_set('display_errors', 1);
error_reporting(E_ALL);
// Gọi file chứa hàm kết nối (Chỉ chọn 1 trong 2 file)
require 'db.php'; 
?>
<div class="grid">
    <?php if (is_array($songs)): ?>
        <?php foreach($songs as $s): ?>
            <div class="card">
                <h3><?php echo $s['name']; ?></h3> <!-- Dựa vào ảnh, cột của bạn tên là 'name' -->
                <img src="https://supabase.co<?php echo $s['image_path']; ?>">
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<style>
    .grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }
    .card {
        border: 1px solid #ccc;
        padding: 10px;
        text-align: center;
    }
    .card img {
        max-width: 100%;
        height: auto;
    }
</style>
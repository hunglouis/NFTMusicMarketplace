<?php
session_start();
require 'db.php';

// 1. Lấy dữ liệu bài hát
$songs = callSupabase("hunglouis?select=*");

// 2. KHAI BÁO LINK BUCKET (Quan trọng nhất - bạn đang thiếu dòng này)
$storageUrl = "https://supabase.co";

// KHÔNG DÙNG ECHO HAY PRINT_R Ở ĐÂY NỮA
?>

<!-- Phần hiển thị giao diện -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-10">
    <?php if (is_array($songs) && !isset($songs['error'])): ?>
        <?php foreach($songs as $s): ?>
            <div class="card glass p-5 rounded-[2rem] hover:scale-105 transition duration-300 shadow-xl">
                
                <!-- Tên hoa/bài hát -->
                <h3 class="font-bold text-xl mb-3 text-cyan-400">
                    <?php echo $s['name'] ?? $s['title'] ?? 'Tuyệt phẩm Hoa Quỳnh'; ?>
                </h3>

                <!-- Hình ảnh hoa Quỳnh lung linh -->
                <img src="<?php echo $storageUrl . ($s['image_path'] ?? ''); ?>" 
                     class="w-full h-64 object-cover rounded-2xl shadow-lg border-2 border-cyan-500/30" 
                     alt="Hoa Quỳnh">

                <!-- Nếu bạn có file nhạc, hãy thêm dòng này bên dưới ảnh -->
                <audio controls class="w-full mt-4 h-8 brightness-110">
                    <source src="<?php echo $storageUrl . ($s['audio_path'] ?? ''); ?>" type="audio/mpeg">
                </audio>

            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-white text-center col-span-full">Đang kết nối dữ liệu hoặc bảng đang trống...</p>
    <?php endif; ?>
</div>


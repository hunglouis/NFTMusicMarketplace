<?php
include 'db.php';
$storageUrl = "https://hmvvjjiiaelcsfqgxbxv.supabase.co";
$songs = callSupabase('hunglouis?select=*', 'GET');
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Louis Music NFT Marketplace</title>
    <!-- Nhúng thư viện làm đẹp -->
    <script src="https://tailwindcss.com"></script>
    <link href="https://cloudflare.com" rel="stylesheet">
    <style>
        body { background: radial-gradient(circle at top right, #0891b2, #020617); min-height: 100vh; color: white; }
        .glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(6, 182, 212, 0.2); }
        .nav-link:hover { color: #22d3ee; transition: 0.3s; }
    </style>
</head>
<body class="p-0">

    <!-- 1. THANH MENU (NAVBAR) -->
    <!-- THANH MENU (NAVBAR) CẢI TIẾN -->
<nav class="glass sticky top-0 z-50 px-6 md:px-10 py-4 flex justify-between items-center mb-10 shadow-2xl border-b border-green-500/30">
    <div class="flex items-center gap-3">
        <div class="bg-green-500 p-2 rounded-lg shadow-lg shadow-green-500/50">
            <i class="fas fa-compact-disc text-2xl text-white fa-spin"></i>
        </div>
        <span class="text-2xl font-black tracking-tighter text-white">LOUIS<span class="text-green-400">MUSIC</span></span>
    </div>
    
    <!-- Các nút bấm đã có link để sử dụng được -->
    <div class="hidden md:flex gap-8 font-bold">
        <a href="index.php" class="text-green-400 border-b-2 border-green-400 pb-1">TRANG CHỦ</a>
        <a href="marketplace.php" class="text-white hover:text-green-400 transition">CHỢ NFT</a>
        <a href="dashboard.php" class="text-white hover:text-green-400 transition">BẢNG ĐIỀU KHIỂN</a>
        <a href="collection.php" class="text-white hover:text-green-400 transition">BỘ SƯU TẬP</a>
    </div>

    <button class="bg-gradient-to-r from-green-500 to-blue-600 hover:from-green-400 hover:to-blue-500 text-white px-6 py-2 rounded-xl font-bold transition transform hover:scale-105 shadow-lg shadow-green-500/40">
        <i class="fas fa-wallet mr-2"></i>KẾT NỐI VÍ
    </button>
</nav>


    <!-- 2. NỘI DUNG CHÍNH (HOA QUỲNH) -->
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold mb-8 flex items-center gap-3">
            <i class="fas fa-star text-yellow-400"></i> Bộ sưu tập Hoa Quỳnh Genesis
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php if (is_array($songs)): foreach($songs as $s): ?>
                <div class="glass p-5 rounded-[2rem] hover:scale-105 transition duration-500 group">
                    <!-- Ảnh hoa -->
                    <div class="relative overflow-hidden rounded-2xl mb-4">
                        <img src="<?= $storageUrl . ($s['image_path'] ?? 'default.jpg') ?>" 
                             class="w-full h-56 object-cover group-hover:scale-110 transition duration-700">
                    </div>
                    
                    <!-- Thông tin -->
                    <h3 class="font-bold text-lg mb-1 truncate"><?= $s['name'] ?? 'Tuyệt phẩm' ?></h3>
                    <p class="text-green-400/70 text-xs mb-4 uppercase tracking-widest">Digital Art NFT</p>
                    
                    <!-- Trình phát nhạc -->
                    <audio controls class="w-full h-8 brightness-125 hue-rotate-180">
                        <source src="<?= $storageUrl . ($s['audio_path'] ?? '') ?>" type="audio/mpeg">
                    </audio>
                </div>
            <?php endforeach; else: ?>
                <p class="col-span-full text-center">Đang tải dữ liệu...</p>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>



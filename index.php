<?php
include 'db.php';
$storageUrl = "https://supabase.co";
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
    <nav class="glass sticky top-0 z-50 px-10 py-4 flex justify-between items-center mb-10">
        <div class="flex items-center gap-3">
            <i class="fas fa-compact-disc text-3xl text-cyan-400 fa-spin"></i>
            <span class="text-2xl font-black tracking-tighter">LOUIS<span class="text-cyan-400">MUSIC</span></span>
        </div>
        <div class="hidden md:flex gap-8 font-medium">
            <a href="index.php" class="nav-link text-cyan-400">Trang chủ</a>
            <a href="marketplace.php" class="nav-link">Chợ NFT</a>
            <a href="dashboard.php" class="nav-link">Bảng điều khiển</a>
            <a href="#" class="nav-link">Bộ sưu tập</a>
        </div>
        <button class="bg-cyan-500 hover:bg-cyan-400 px-6 py-2 rounded-xl font-bold transition shadow-lg shadow-cyan-500/30">
            Kết nối ví
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
                    <p class="text-cyan-400/70 text-xs mb-4 uppercase tracking-widest">Digital Art NFT</p>
                    
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



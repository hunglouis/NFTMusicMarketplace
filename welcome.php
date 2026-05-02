<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>CHÀO MỪNG ĐẾN VỚI MUSIC NFT STUDIO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cloudflare.com">
    <style>
        body { background: #020617; color: #d4d4d8; padding-left: 3rem; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .welcome-card { background: rgba(255, 255, 255, 0.01); border: 1px solid rgba(234, 179, 8, 0.1); border-radius: 40px; backdrop-filter: blur(15px); max-width: 800px; width: 90%; }
        .gold-gradient { background: linear-gradient(to right, #eab308, #ca8a04); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .step-num { width: 30px; height: 30px; border: 1px solid #eab308; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; color: #eab308; font-weight: bold; }
    </style>
</head>
<body>
    <?php if(file_exists('navbar.php')) include 'navbar.php'; ?>

    <div class="welcome-card p-12 text-center shadow-2xl">
        <!-- BIỂU TƯỢNG -->
        <div class="mb-8">
            <i class="fa-solid fa-dharmachakra text-6xl text-yellow-500/20 pulse absolute left-1/2 -translate-x-1/2 -top-10"></i>
            <i class="fa-solid fa-bolt-lightning text-4xl text-yellow-500 relative z-10"></i>
        </div>

        <h1 class="text-4xl font-black gold-gradient uppercase tracking-tighter mb-4">Chào mừng Nghệ sĩ gia nhập</h1>
        <p class="text-gray-500 text-sm italic mb-12">"Nơi mỗi sản vật trở thành một chương của lịch sử bất tử"</p>

        <!-- LỘ TRÌNH 3 BƯỚC -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-left mb-12">
            <div class="space-y-3">
                <div class="step-num text-xs">01</div>
                <h3 class="text-xs font-black uppercase tracking-widest text-white">Kết nối Ví</h3>
                <p class="text-[10px] text-gray-500 leading-relaxed">Sử dụng ví Web3 (Metamask) để làm chìa khóa vạn năng cho mọi di sản của bạn.</p>
            </div>
            <div class="space-y-3">
                <div class="step-num text-xs">02</div>
                <h3 class="text-xs font-black uppercase tracking-widest text-white">Định danh</h3>
                <p class="text-[10px] text-gray-500 leading-relaxed">Vào mục <b>Users</b> để cập nhật Bio và Email. Hãy để thế giới biết bạn là ai.</p>
            </div>
            <div class="space-y-3">
                <div class="step-num text-xs">03</div>
                <h3 class="text-xs font-black uppercase tracking-widest text-white">Khám phá</h3>
                <p class="text-[10px] text-gray-500 leading-relaxed">Bắt đầu xem kệ hàng và sở hữu những sản vật quý giá.</p>
            </div>
        </div>

        <!-- NÚT HÀNH ĐỘNG -->
        <div class="flex flex-col md:flex-row gap-4 justify-center">
            <div class="step-num text-xs">04</div>
            <a href="marketplace_supabase.php" class="px-10 py-4 bg-yellow-500 text-black font-black text-[10px] uppercase tracking-[0.3em] rounded-2xl hover:scale-105 transition-all shadow-xl shadow-yellow-500/10">Vào Marketplace</a>
            <p class="text-[10px] text-gray-500 leading-relaxed">Bắt đầu xem kệ hàng và sở hữu những sản vật quý giá.</p>
            <div class="step-num text-xs">05</div>
            <a href="users_pro.php" class="px-10 py-4 bg-white/5 border border-white/10 text-white font-black text-[10px] uppercase tracking-[0.3em] rounded-2xl hover:bg-white/10 transition-all">Cài đặt hồ sơ</a>
            <p class="text-[10px] text-gray-500 leading-relaxed">Bắt đầu xem kệ hàng và sở hữu những sản vật quý giá.</p>
        </div>
    </div>
</body>
</html>

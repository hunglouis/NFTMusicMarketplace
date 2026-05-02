<?php
session_start();
function callSupabase($endpoint) {
    $url = "https://hmvvjjiiaelcsfqgxbxv.supabase.co/rest/v1/" . $endpoint;

    $headers = [
        "apikey: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw",
        "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw"
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $res = curl_exec($ch);
    curl_close($ch);

    return json_decode($res, true);
}

// ====== LẤY DATA ======
$songs = callSupabase("hunglouis?price=gt.0&order=id.desc&limit=200");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>MARKETPLACE - DI SẢN MẠNH HÙNG</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cloudflare.com">
    <style>
        body { background: #020617; color: #d4d4d8; padding-left: 5rem; padding-right: 2rem; min-height: 100vh; }
        .gold-card { background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(234, 179, 8, 0.1); transition: all 0.4s; }
        .gold-card:hover { border-color: #eab308; transform: translateY(-5px); box-shadow: 0 0 20px rgba(234, 179, 8, 0.1); }
        .gold-text { color: #eab308; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
</head>
<body class="p-8">
    <?php include 'navbar.php'; ?>

    <div class="max-w-7xl mx-auto">
        <header class="mb-16 flex justify-between items-end">
            <div>
                <h1 class="text-5xl font-black gold-text tracking-tighter uppercase">Kệ Hàng Di Sản</h1>
                <p class="text-gray-500 text-[10px] tracking-[0.5em] uppercase mt-2">Nơi hội tụ tinh hoa nghệ thuật & trí tuệ</p>
            </div>
            <div class="text-right">
                <span class="text-[10px] text-gray-600 uppercase tracking-widest">Tiêu chuẩn bảo tồn</span><br>
                <span class="gold-text font-bold text-sm">ERC-721 & ERC-2981</span>
            </div>
        </header>

        <!-- NƠI HIỂN THỊ DANH SÁCH TÁC PHẨM -->
        <div id="marketplace-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Dữ liệu từ Supabase sẽ tự động đổ vào đây -->
            <div class="col-span-full text-center py-20 opacity-20">
                <i class="fas fa-spinner fa-spin text-4xl mb-4"></i>
                <p class="uppercase tracking-widest text-xs">Đang quét kho di sản...</p>
            </div>
        </div>
    </div>

    <script>
        const SUPABASE_URL = "https://hmvvjjiiaelcsfqgxbxv.supabase.co";
        const ANON_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw";

        async function loadMarketplace() {
            try {
                const res = await fetch(`${SUPABASE_URL}/rest/v1/hunglouis?select=*&order=id.desc`, {
                    headers: { "apikey": ANON_KEY, "Authorization": `Bearer ${ANON_KEY}` }
                });
                const items = await res.json();
                const grid = document.getElementById('marketplace-grid');
                grid.innerHTML = ''; // Xóa trạng thái chờ

                items.forEach(item => {
                    // Xử lý link IPFS để hiển thị (Dùng gateway công cộng)
                    let displayUrl = item.image_url.replace('ipfs://', 'https://pinata.cloud');
                    
                    // Kiểm tra loại file để hiện icon tương ứng
                    let mediaContent = `<img src="${displayUrl}" class="w-full h-48 object-cover rounded-2xl mb-4" onerror="this.src='https://placehold.co'">`;
                    
                    if(displayUrl.toLowerCase().endsWith('.pdf')) {
                        mediaContent = `<div class="w-full h-48 flex flex-col items-center justify-center bg-white/5 rounded-2xl mb-4 border border-white/5">
                                            <i class="fas fa-file-pdf text-4xl text-red-500 mb-2"></i>
                                            <span class="text-[8px] uppercase tracking-widest text-gray-500">Hồ sơ tài liệu</span>
                                        </div>`;
                    } else if(displayUrl.toLowerCase().endsWith('.mp3')) {
                        mediaContent = `<div class="w-full h-48 flex flex-col items-center justify-center bg-white/5 rounded-2xl mb-4 border border-white/5">
                                            <i class="fas fa-music text-4xl gold-text mb-4"></i>
                                            <audio controls class="h-8 w-4/5 scale-75 opacity-50 hover:opacity-100 transition-all">
                                                <source src="${displayUrl}" type="audio/mpeg">
                                            </audio>
                                        </div>`;
                    }

                    grid.innerHTML += `
                        <div class="gold-card p-5 rounded-[35px] flex flex-col">
                            ${mediaContent}
                            <div class="flex-1">
                                <h3 class="text-white font-bold text-sm mb-1 uppercase tracking-tight truncate">${item.name || 'Tác phẩm vô danh'}</h3>
                                <p class="text-gray-500 text-[10px] line-clamp-2 mb-4 italic h-8">${item.description || 'Không có mô tả di sản.'}</p>
                            </div>
                            <div class="flex justify-between items-center pt-4 border-t border-white/5">
                                <span class="gold-text font-black text-sm">${item.price || '0.01'} <span class="text-[8px]">MATIC</span></span>
                                <a href="nft_detail.php?id=${item.id}" class="text-[9px] uppercase font-black tracking-widest bg-white/5 px-4 py-2 rounded-full hover:bg-yellow-500 hover:text-black transition-all">Chi tiết</a>
                            </div>
                        </div>
                    `;
                });
            } catch (e) {
                console.error(e);
            }
        }

        window.onload = loadMarketplace;
    </script>
</body>
</html>


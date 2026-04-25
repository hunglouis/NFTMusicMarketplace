<?php
session_start();
require_once 'db.php'; 

// 1. Lấy ID
$nft_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// 2. Cấu hình Supabase (NHỚ ĐIỀN KEY THẬT CỦA BẠN VÀO ĐÂY)
$supabaseUrl = "https://hmvvjjiiaelcsfqgxbxv.supabase.co";
$apiKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw"; 

$fullUrl = $supabaseUrl . "/rest/v1/hunglouis?id=eq." . $nft_id . "&select=*";
$ch = curl_init($fullUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["apikey: ".$apiKey, "Authorization: Bearer ".$apiKey]);
$response = curl_exec($ch);
curl_close($ch);
$data = json_decode($response, true);
$nft = (isset($data[0])) ? $data[0] : null;

if (!$nft) die("Không tìm thấy tác phẩm.");

// Xử lý link Pinata
if (isset($nft['image_url']) && strpos($nft['image_url'], 'ipfs://') !== false) {
    $nft['image_url'] = str_replace('ipfs://', 'https://pinata.cloud', $nft['image_url']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Chi tiết NFT</title>
    <script src="https://tailwindcss.com"></script>
    <style>
        body { background-color: #020617; color: white; font-family: sans-serif; margin: 0; padding-left: 3.5rem; }
        /* Khống chế ảnh tuyệt đối */
        .poster-frame { width: 450px; height: 550px; overflow: hidden; border-radius: 30px; border: 1px solid rgba(255,255,255,0.1); background: #000; }
        .poster-frame img { width: 100%; height: 100%; object-fit: cover; }
    </style>
</head>
<!-- Thay thế đoạn từ <body> đến hết file nft_detail.php bằng đoạn này -->
<body class="p-10 min-h-screen bg-[#020617]">
    <?php include 'navbar.php'; ?>

    <div class="max-w-6xl mx-auto mt-10">
        <!-- Nút quay lại -->
        <a href="marketplace_supabase.php" class="text-gray-500 hover:text-cyan-400 font-bold text-xs uppercase tracking-widest mb-10 inline-block">
            ← Quay lại cửa hàng
        </a>

        <!-- BỐ CỤC CHIA 2 CỘT RÕ RÀNG -->
        <div class="flex flex-col lg:flex-row gap-12 items-start">
            
            <!-- CỘT TRÁI: KHUNG ẢNH ĐÃ FIX KÍCH THƯỚC -->
            <div class="w-[400px] flex-shrink-0 shadow-[0_0_50px_rgba(6,182,212,0.15)] rounded-[40px] overflow-hidden border border-white/10">
                <img src="<?php echo $nft['image_url']; ?>" class="w-full h-auto object-cover block">
            </div>

            <!-- CỘT PHẢI: THÔNG TIN CHI TIẾT -->
            <div class="flex-grow space-y-8">
                <div>
                    <h1 class="text-5xl font-black text-white tracking-tighter leading-tight">
                        <?php echo $nft['name']; ?>
                    </h1>
                    <p class="text-2xl font-bold text-emerald-400 mt-4">💰 <?php echo $nft['price']; ?> MATIC</p>
                </div>

                <div class="bg-white/5 p-8 rounded-[32px] border border-white/5 backdrop-blur-xl">
                    <h3 class="text-cyan-400 text-[10px] font-black uppercase tracking-[0.3em] mb-4">📜 Mô tả tác phẩm</h3>
                    <p class="text-gray-300 text-sm leading-relaxed italic whitespace-pre-line">
                        <?php echo $nft['description'] ?: "Tác phẩm NFT âm nhạc độc bản nằm trong bộ sưu tập di sản của nghệ sĩ Mạnh Hùng."; ?>
                    </p>
                </div>

                <!-- Nút Mua -->
                <button class="w-full bg-white text-black hover:bg-cyan-500 hover:text-white font-black py-5 rounded-2xl transition-all duration-500 shadow-xl text-xs uppercase tracking-widest">
                    Sở hữu tác phẩm này
                </button>
            </div>
        </div>
    </div>
</body>
</html>

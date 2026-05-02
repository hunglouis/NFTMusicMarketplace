<?php
// --- 1. PHẦN XỬ LÝ DỮ LIỆU (Giữ nguyên logic cũ của bạn) ---
$supabaseUrl = "https://hmvvjjiiaelcsfqgxbxv.supabase.co"; 
$apiKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw";
$items_per_page = 12;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

$ch = curl_init("$supabaseUrl/rest/v1/items?select=*&order=item_id.asc&limit=50");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["apikey: $apiKey", "Authorization: Bearer $apiKey", "Range: $offset-" . ($offset + $items_per_page - 1)]);
$items = json_decode(curl_exec($ch), true);
curl_close($ch);
if (!is_array($items)) { $items = []; }

// Hàm nút bấm thiết kế lại: Sáng sủa và Sang trọng
function renderPagination($current_page) {
    echo '<div style="display: flex; justify-content: center; align-items: center; gap: 25px; margin: 50px 0;">';
    if ($current_page > 1) {
        echo '<a href="?page='.($current_page-1).'" style="padding: 12px 25px; background: rgba(0,255,255,0.05); border: 1px solid #00ffff; color: #00ffff; text-decoration: none; border-radius: 50px; font-weight: bold; font-size: 12px; transition: 0.3s;">← TRƯỚC</a>';
    }
    echo '<span style="color: #00ffff; font-size: 11px; font-weight: 900; letter-spacing: 3px; text-transform: uppercase;">Trang '.$current_page.'</span>';
    echo '<a href="?page='.($current_page+1).'" style="padding: 12px 25px; background: #00ffff; color: #000; text-decoration: none; border-radius: 50px; font-weight: bold; font-size: 12px; box-shadow: 0 0 20px rgba(0,255,255,0.4); transition: 0.3s;">SAU →</a>';
    echo '</div>';
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>OpenSea Heritage | Studio NFT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: radial-gradient(circle at top right, #0891b2, #064e3b, #020617);
            color: white;
            min-height: 100vh;
        }
        
        body { 
            padding-left: 16rem;
        }

        .card-nft {
            background: rgba(255, 255, 255, 0.02); /* Trong suốt hơn */
            backdrop-filter: blur(12px); /* Hiệu ứng kính mờ */
            border: 1px solid rgba(255, 255, 255, 0.05); /* Viền cực mảnh */
            border-radius: 24px; /* Bo tròn sâu hơn cho sang trọng */
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .card-nft:hover {
            transform: translateY(-12px) scale(1.02); /* Bay bổng hơn khi rà chuột */
            border-color: rgba(6, 182, 212, 0.4);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.7);
            background: rgba(255, 255, 255, 0.05);
        }

        .btn-action {
             border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .page-btn {
            background: #111;
            border: 1px solid #333;
            color: #666;
            padding: 8px 20px;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.3s;
            font-size: 13px;
            font-weight: bold;
        }
        .page-btn:hover {
            border-color: #00ffff;
            color: #00ffff;
            box-shadow: 0 0 10px rgba(0, 255, 255, 0.2);
        }
        
        /* Làm cho các khung ảnh NFT đều tăm tắp */
        .nft-card img {
            width: 100%;
            height: 250px; /* Độ cao cố định cho đẹp */
            object-fit: cover; /* Cắt ảnh thông minh không bị méo */
            border-radius: 12px 12px 0 0;
            border-bottom: 1px solid rgba(0, 255, 255, 0.1);
        }
        
        /* Hiệu ứng hào quang khi di chuột vào món hàng */
        .nft-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 0 20px rgba(0, 255, 255, 0.3);
            transition: 0.4s;
        }


    </style>
</head>
<body class="p-5 md:p-10">
     <?php if(file_exists('navbar.php')) include 'navbar.php'; ?>
 
    <!-- 2. NỘI DUNG CHÍNH (Rực sáng và Hoành tráng) -->
          
        <div style="margin-bottom: 60px;">
            <h1 class="cyan-glow" style="font-size: 48px; font-weight: 900; text-transform: uppercase; letter-spacing: -2px; margin: 0;">
                Di Sản Từ OpenSea
            </h1>
            <p style="color: #555; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 4px; margin-top: 15px;">
                <i class="fas fa-database" style="color: #00ffff; margin-right: 10px;"></i>
                Kho di sản • 26 vật phẩm • Blockchain Polygon
            </p>
        </div>

        <!-- 🧭 NÚT CHUYỂN TRANG ĐẦU -->
        <?php renderPagination($current_page); ?>

        <!-- 🖼️ LƯỚI NFT -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <?php if (!empty($items) && is_array($items)): ?>
                <?php foreach ($items as $item): ?>
                    <div class="card-nft rounded-3xl p-5 flex flex-col h-[520px]">
                                                <!-- Ảnh NFT -->
                        <div class="relative h-56 w-full mb-4 overflow-hidden rounded-2xl group">
                            <a href="nft_detail.php?id=<?php echo $item['id']; ?>" class="block h-full w-full">
                                <img src="<?php  echo $item['image_url'] ?: 'https://placeholder.com'; ?>" 
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                
                                <div class="absolute top-2 right-2 bg-black/60 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold text-cyan-400">
                                    #<?php  echo $item['id']; ?>
                                </div>

                                <div class="absolute inset-0 bg-cyan-500/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="bg-black/80 text-white text-[10px] px-3 py-1 rounded-full border border-cyan-500">XEM CHI TIẾT</span>
                                </div>
                            </a>
                        </div>

                        <!-- Thông tin -->
                        <div class="flex-grow">
                           
                        <h3 class="text-lg font-bold text-white line-clamp-2 mb-2 leading-tight">
                                <?php  $item['name'] ?: 'Tác phẩm chưa đặt tên'; ?>
                            </h3>
                            <!-- KHUNG NỘI DUNG DƯỚI ẢNH -->
<div style="padding: 20px; background: rgba(0,0,0,0.2); border-radius: 0 0 20px 20px;">
    
    <!-- Dòng Giá niêm yết -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <span style="font-size: 11px; color: #555; text-transform: uppercase; font-weight: bold; letter-spacing: 1px;">Giá niêm yết</span>
        <div style="display: flex; align-items: center; gap: 5px;">
            <span style="color: #ffcc00; font-weight: 900; font-size: 20px; text-shadow: 0 0 10px rgba(255,204,0,0.3);">
                <?php echo number_format($item['price'] ?? 0, 1); ?>
            </span>
            <span style="color: #00ffff; font-size: 12px; font-weight: bold;">MATIC</span>
        </div>
    </div>

    <!-- Hàng nút bấm (Ép nằm ngang tăm tắp) -->
    <div style="display: flex; gap: 10px; width: 100%;">
        <!-- Nút Nghe/Xem thử -->
        <button onclick="playMusic('<?php echo $item['image_url']; ?>', '<?php echo addslashes($item['name']); ?>', '<?php echo $item['image_url']; ?>')" 
                style="flex: 1; background: #008888; color: white; border: none; padding: 12px 5px; border-radius: 10px; font-size: 11px; font-weight: bold; cursor: pointer; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 5px;">
            <i class="fas fa-play-circle"></i> Nghe/Xem thử
        </button>

        <!-- Nút Sở hữu ngay -->
        <a href="nft-details.php?id=<?php echo $item['id']; ?>" 
           style="flex: 1; background: #ffffff; color: #000; text-decoration: none; padding: 12px 5px; border-radius: 10px; font-size: 11px; font-weight: bold; transition: 0.3s; display: flex; align-items: center; justify-content: center; text-align: center;">
            Sở hữu ngay
        </a>
    </div>
</div>

                            <form method="POST">
                                <input type="hidden" name="song_id" value="<?php $item['id']; ?>">
                                <button name="buy_nft" class="w-full bg-white text-black hover:bg-emerald-400 hover:text-white py-2.5 rounded-xl text-sm font-bold transition-all">
                                    Sở hữu ngay
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="grid-column: span 4; text-align: center; padding: 100px; color: #333;">
                    <i class="fas fa-box-open" style="font-size: 50px; margin-bottom: 20px;"></i>
                    <p>Kho hàng đang trống, hãy kiểm tra lại kết nối Supabase!</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- 🧭 NÚT CHUYỂN TRANG CUỐI -->
        <?php renderPagination($current_page); ?>
   </div>

<!-- Thông báo mua hàng -->
    <?php if (isset($thongbao))  "<div class='fixed bottom-5 right-5 p-4 rounded-2xl bg-black/80 border border-cyan-500 shadow-2xl'>$thongbao</div>"; ?>

    <!-- Music Player Bar -->
    <div id="music-player-bar" class="fixed bottom-0 left-0 w-full bg-black/95 backdrop-blur-md border-t border-cyan-500/50 p-4 transform translate-y-full transition-all duration-500 z-[100]">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
        
        <!-- 1. Khu vực hiển thị Video/Ảnh -->
        <div class="flex items-center gap-4 w-1/3">
            <div class="relative w-24 h-14 bg-black rounded-lg overflow-hidden border border-cyan-400 group">
                <video id="main-hybrid-player" class="w-full h-full object-cover cursor-pointer" onclick="toggleFullscreen()"></video>
                <img id="player-poster" src="" class="absolute inset-0 w-full h-full object-cover hidden pointer-events-none">
                <!-- Nút phóng to nhanh hiện khi di chuột vào -->
                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                    <span class="text-[10px] text-white">CLICK PHÓNG TO</span>
                </div>
            </div>
            <div class="overflow-hidden">
                <h4 id="player-title" class="text-white font-bold truncate text-sm">Tên bài hát</h4>
                <p id="player-status" class="text-cyan-400 text-[10px] uppercase">Đang phát...</p>
            </div>
        </div>

        <!-- 2. Bộ điều khiển trung tâm -->
        <div class="flex flex-col items-center gap-2 w-1/3">
            <div class="flex items-center gap-8">
                <!-- Nút Tạm dừng/Phát -->
                <button onclick="togglePlay()" class="bg-white text-black p-3 rounded-full hover:scale-110 transition-transform">
                    <div id="play-pause-icon">
                        <!-- Mặc định hiện icon Pause khi đang hát -->
                        <svg xmlns="http://w3.org" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </button>
                
                <!-- Nút Phóng to chuyên dụng -->
                <button onclick="toggleFullscreen()" class="text-gray-400 hover:text-cyan-400 transition-colors">
                    <svg xmlns="http://w3.org" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                    </svg>
                </button>
            </div>
        </div>
        <!-- 3. Nút Tắt hẳn -->
        <div class="w-1/3 text-right">
            <button onclick="closePlayer()" class="text-gray-500 hover:text-red-500 font-black text-xl px-4 transition-colors">✕</button>
        </div>
    </div>
    </div>
</div>
<script>
    // Toàn bộ logic điều khiển nhạc nằm ở đây
    const playerBar = document.getElementById('music-player-bar');
    const audioTag = document.getElementById('main-audio');
    const playIcon = document.getElementById('play-icon');

    function playMusic(url, name, img) {
    const player = document.getElementById('main-hybrid-player');
    const poster = document.getElementById('player-poster');
    const playerBar = document.getElementById('music-player-bar');

    if (!url) return alert("Không tìm thấy link tệp tin!");

    // 1. Cập nhật thông tin cơ bản
    document.getElementById('player-title').innerText = name;
    player.src = url;

    // 2. Kiểm tra định dạng để hiển thị "màn hình"
    const isVideo = url.toLowerCase().includes('.mp4');
    
    if (isVideo) {
        poster.classList.add('hidden'); // Ẩn ảnh bìa đi để hiện video
    } else {
        poster.src = img;
        poster.classList.remove('hidden'); // Hiện ảnh bìa đè lên video nếu chỉ là nhạc
    }

    // 3. Hiện thanh điều khiển và phát
    playerBar.classList.remove('translate-y-full');
    player.play();
    updatePlayIcon(true);
}

function togglePlay() {
    const player = document.getElementById('main-hybrid-player');
    const iconContainer = document.getElementById('play-pause-icon');

    if (player.paused) {
        player.play();
        iconContainer.innerHTML = '<svg xmlns="http://w3.org" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
    } else {
        player.pause();
        iconContainer.innerHTML = '<svg xmlns="http://w3.org" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /></svg>';
    }
}

function toggleFullscreen() {
    const player = document.getElementById('main-hybrid-player');
    if (player.requestFullscreen) {
        player.requestFullscreen();
    } else if (player.webkitRequestFullscreen) { /* Safari */
        player.webkitRequestFullscreen();
    } else if (player.msRequestFullscreen) { /* IE11 */
        player.msRequestFullscreen();
    }
}

function closePlayer() {
    const player = document.getElementById('main-hybrid-player');
    const playerBar = document.getElementById('music-player-bar');
    
    player.pause(); // Dừng nhạc ngay lập tức
    player.src = ""; // Xóa link nhạc
    playerBar.classList.add('translate-y-full'); // Ẩn thanh công cụ
}

</script>

</body>
</html>                                                                 



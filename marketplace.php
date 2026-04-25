<?php
session_start();
// Khai báo các thông số kết nối trực tiếp
$supabaseUrl = "https://hmvvjjiiaelcsfqgxbxv.supabase.co";
$apiKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw"; 

// 1. GỌI DỮ LIỆU THẬT TỪ SUPABASE
$fullUrl = $supabaseUrl . "/rest/v1/hunglouis?select=*&order=id.asc&limit=50";
$ch = curl_init($fullUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "apikey: " . $apiKey,
    "Authorization: Bearer " . $apiKey,
    "Content-Type: application/json"
]);
$response = curl_exec($ch);
$all_data = json_decode($response, true);
curl_close($ch);

// 2. NẾU SUPABASE TRỐNG, TỰ ĐỘNG HIỆN HÀNG MẪU (Để bạn yên lòng dàn trang)
if (empty($all_data)) {
    $all_data = [
        [
            "id" => "0",
            "name" => "Hàng mẫu: Quỳnh Hương 01",
            "price" => "0.05",
            "image_url" => "https://vnecdn.net"
        ],
        [
            "id" => "0",
            "name" => "Hàng mẫu: Ngày Tình Xa",
            "price" => "0.1",
            "image_url" => "https://scdn.co"
        ]
    ];
}

$user = $_SESSION['user'] ?? 'Nhạc sĩ Mạnh Hùng';
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>CỬA HÀNG NHẠC NFT - MẠNH HÙNG</title>
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

    </style>
</head>
<body class="p-5 md:p-10">
    
    <?php if(file_exists('navbar.php')) include 'navbar.php'; ?>

    <div class="max-w-7xl mx-auto">
        <header class="text-center mb-12">
            <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-emerald-400 mb-2">
                NFT MARKETPLACE
            </h1>
            <p class="text-gray-400">Chào mừng nghệ sĩ: <span class="text-cyan-400 font-bold"><?php echo $user; ?></span></p>
        </header>

        <!-- GRID HIỂN THỊ HÀNG HÓA -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            
            <?php if (!empty($all_data) && is_array($all_data)): ?>
                <?php foreach ($all_data as $item): ?>
                    <div class="card-nft rounded-3xl p-5 flex flex-col h-[520px]">
                                                <!-- Ảnh NFT -->
                        <div class="relative h-56 w-full mb-4 overflow-hidden rounded-2xl group">
                            <a href="nft_detail.php?id=<?php echo $item['id']; ?>" class="block h-full w-full">
                                <img src="<?php echo $item['image_url'] ?: 'https://placeholder.com'; ?>" 
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                
                                <div class="absolute top-2 right-2 bg-black/60 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold text-cyan-400">
                                    #<?php echo $item['id']; ?>
                                </div>

                                <div class="absolute inset-0 bg-cyan-500/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span class="bg-black/80 text-white text-[10px] px-3 py-1 rounded-full border border-cyan-500">XEM CHI TIẾT</span>
                                </div>
                            </a>
                        </div>

                        <!-- Thông tin -->
                        <div class="flex-grow">
                           
                        <h3 class="text-lg font-bold text-white line-clamp-2 mb-2 leading-tight">
                                <?php echo $item['name'] ?: 'Tác phẩm chưa đặt tên'; ?>
                            </h3>
                            <div class="flex justify-between items-center bg-white/5 p-3 rounded-xl">
                                <span class="text-gray-400 text-sm">Giá niêm yết</span>
                                <span class="text-emerald-400 font-black">💰 <?php echo $item['price']; ?> MATIC</span>
                            </div>
                        </div>

                        <!-- Nút hành động -->
                        <div class="grid grid-cols-2 gap-3 mt-5">
                            <button onclick="playMusic('<?php echo $item['audio_url'] ?: $item['image_url']; ?>', '<?php echo addslashes($item['name']); ?>', '<?php echo $item['image_url']; ?>')" 
                                class="bg-cyan-600 hover:bg-cyan-500 text-white py-2 px-4 rounded-lg text-xs font-bold transition-all flex items-center gap-2">
                                    ▶ Nghe/Xem thử
                            </button>




                            <form method="POST">
                                <input type="hidden" name="song_id" value="<?php echo $item['id']; ?>">
                                <button name="buy_nft" class="w-full bg-white text-black hover:bg-emerald-400 hover:text-white py-2.5 rounded-xl text-sm font-bold transition-all">
                                    Sở hữu ngay
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Thông báo khi kho hàng trống -->
                <div class="col-span-full text-center py-20 bg-white/5 rounded-3xl border border-dashed border-gray-700">
                    <p class="text-gray-500 text-xl">Kho hàng hiện đang trống...</p>
                    <a href="?auto_quynh=1" class="mt-4 inline-block text-cyan-400 underline italic">Tự động nhập 12 đóa Quỳnh Hương?</a>
                    <a href="nft_detail.php?id=<?php echo $item['id']; ?>"> ... </a>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- Thông báo mua hàng -->
    <?php if (isset($thongbao)) echo "<div class='fixed bottom-5 right-5 p-4 rounded-2xl bg-black/80 border border-cyan-500 shadow-2xl'>$thongbao</div>"; ?>

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

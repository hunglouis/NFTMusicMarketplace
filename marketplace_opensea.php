<?php
include 'config.php';
session_start();
// --- 1. PHẦN XỬ LÝ DỮ LIỆU (Giữ nguyên logic cũ của bạn) ---
$supabaseUrl = "https://hmvvjjiiaelcsfqgxbxv.supabase.co";
$apiKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw";


// 🔗 CHÈN FILE HELPER DÙNG CHUNG VÀO ĐÂY
require_once "filter_helper.php";

// --- BỘ ĐẾM DI SẢN TỰ ĐỘNG ---
function getTotalCount($tableName)
{
    // Bắc cầu trực tiếp để lấy chìa khóa từ bên ngoài vào
    global $url, $apiKey;

    $ch = curl_init($url . $tableName . "?select=id");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "apikey: " . $apiKey,
        "Authorization: Bearer " . $apiKey
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($response, true);
    return is_array($data) ? count($data) : 0;
}

// BÂY GIỜ GỌI LỆNH NÀY SẼ CỰC KỲ AN TOÀN
$countHungLouis = getTotalCount('hunglouis');
$countItems = getTotalCount('items');
$total = $countHungLouis + $countItems;

// -----------------------------


$items_per_page = 12;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $items_per_page;

$ch = curl_init("$supabaseUrl/rest/v1/items?select=*&order=id.asc&limit=50&is_hidden=eq.false");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ["apikey: $apiKey", "Authorization: Bearer $apiKey", "Range: $offset-" . ($offset + $items_per_page - 1)]);
$items = json_decode(curl_exec($ch), true);
curl_close($ch);
if (!is_array($items)) {
    $items = [];
}

// Hàm nút bấm thiết kế lại: Sáng sủa và Sang trọng
function renderPagination($current_page)
{
    echo '<div style="display: flex; justify-content: center; align-items: center; gap: 25px; margin: 50px 0;">';
    if ($current_page > 1) {
        echo '<a href="?page=' . ($current_page - 1) . '" style="padding: 12px 25px; background: rgba(0,255,255,0.05); border: 1px solid #00ffff; color: #00ffff; text-decoration: none; border-radius: 50px; font-weight: bold; font-size: 12px; transition: 0.3s;">← TRƯỚC</a>';
    }
    echo '<span style="color: #00ffff; font-size: 11px; font-weight: 900; letter-spacing: 3px; text-transform: uppercase;">Trang ' . $current_page . '</span>';
    echo '<a href="?page=' . ($current_page + 1) . '" style="padding: 12px 25px; background: #00ffff; color: #000; text-decoration: none; border-radius: 50px; font-weight: bold; font-size: 12px; box-shadow: 0 0 20px rgba(0,255,255,0.4); transition: 0.3s;">SAU →</a>';
    echo '</div>';
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <base href="/">
    <meta charset="UTF-8">
    <title>OpenSea | Studio NFT</title>
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
            background: rgba(255, 255, 255, 0.02);
            /* Trong suốt hơn */
            backdrop-filter: blur(12px);
            /* Hiệu ứng kính mờ */
            border: 1px solid rgba(255, 255, 255, 0.05);
            /* Viền cực mảnh */
            border-radius: 24px;
            /* Bo tròn sâu hơn cho sang trọng */
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .card-nft:hover {
            transform: translateY(-12px) scale(1.02);
            /* Bay bổng hơn khi rà chuột */
            border-color: rgba(6, 182, 212, 0.4);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.7);
            background: rgba(255, 255, 255, 0.05);
        }
    </style>
</head>

<body class="p-5 md:p-10">
    <?php if (file_exists('navbar.php')) include 'navbar.php'; ?>

    <!-- 2. NỘI DUNG CHÍNH (Rực sáng và Hoành tráng) -->
    <div style="margin-bottom: 60px;">
        <h1 class="text-white font-black text-5xl uppercase mb-2">DI SẢN TỪ OPENSEA</h1>
        <p class="text-cyan-400 text-xs tracking-[0.3em] uppercase opacity-70">
            KHO DI SẢN: <?php echo $total; ?> TÁC PHẨM | BLOCKCHAIN POLYGON
        </p>
    </div>

    <!-- 🧭 NÚT CHUYỂN TRANG ĐẦU -->
    <?php renderPagination($current_page); ?>

    <!-- 🖼️ LƯỚI NFT -->
    <!-- Khung lưới chứa sản phẩm: Ép giao diện tự động chia cột theo kích thước màn hình -->
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 30px; max-width: 1200px; margin: 0 auto;">

        <?php if (!empty($items) && is_array($items)): ?>
            <?php foreach ($items as $item): ?>
                <?php
                // Lấy địa chỉ ví của người đang xem từ Session PHP
                $currentViewer = isset($_SESSION['user_wallet']) ? strtolower(trim($_SESSION['user_wallet'])) : '';

                // Lấy địa chỉ ví của chủ sở hữu bài hát này từ database Supabase
                $itemOwner = isset($item['owner_address']) ? strtolower(trim($item['owner_address'])) : '';

                // ĐỊNH NGHĨA BIẾN: Kiểm tra xem người xem hiện tại có phải là chủ sở hữu (Chính chủ) hay không
                $isOwner = ($currentViewer !== '' && $currentViewer === $itemOwner);

                $mediaUrl = htmlspecialchars($item['image_url'] ?? 'assets/default-music.png');
                // PHP tự động kiểm tra xem tệp trong image_url có phải là Music Video (MV) hay không
                $fileExtension = strtolower(pathinfo($mediaUrl, PATHINFO_EXTENSION));
                $isVideo = in_array($fileExtension, ['mp4', 'webm', 'mov', 'ogg']);

                ?>

                <!-- CHÈN ĐẦY ĐỦ THUỘC TÍNH KIỂM TRA CHÍNH CHỦ VÀ DI CHUỘT VÀO THÈ CHA NÀY -->
                <div class="music-card-php"
                    data-audio="<?= $mediaUrl ?>"
                    data-owner="<?= $itemOwner ?>"
                    onmouseenter="playPreview(this)"
                    onmouseleave="stopPreview(this)"
                    style="position: relative; width: 100%; box-sizing: border-box;">

                    <div class="card-media" style="position: relative; width: 100%; padding-top: 100%; overflow: hidden; background: #000;">
                        <?php if ($isVideo): ?>
                            <!-- TRƯỜNG HỢP 1: NẾU LÀ MUSIC VIDEO (MV) -> Chuẩn bị sẵn thẻ video tắt tiếng ngầm để lách luật trình duyệt -->
                            <img src="assets/default-video-cover.png" class="nft-thumb-img video-poster" alt="MV Poster" style="position: absolute; top:0; left:0; width:100%; height:100%; object-fit:cover;">
                            <video src="<?= $mediaUrl ?>" class="nft-video-preview" loop muted playsinline style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; display: none;"></video>
                        <?php else: ?>
                            <!-- TRƯỜNG HỢP 2: NẾU CHỈ LÀ FILE ẢNH/AUDIO THƯỜNG -->
                            <img src="<?= $mediaUrl ?>" class="nft-thumb-img" alt="Cover" style="position: absolute; top:0; left:0; width:100%; height:100%; object-fit:cover;">
                        <?php endif; ?>
                        <div class="card-nft rounded-3xl p-5 flex flex-col h-[520px]">
                            <!-- Ảnh NFT -->
                            <div class="relative h-56 w-full mb-4 overflow-hidden rounded-2xl group">
                                <a href="<?php echo createSlug($item['name'], $item['id']); ?>" class="block h-full w-full">
                                    <img src="<?php
                                                // Nếu có link ảnh thì hiện, nếu không thì dùng ảnh logo hoặc biểu tượng âm nhạc của bạn
                                                echo !empty($item['image_url']) ? $item['image_url'] : 'assets/images/louis-music-default.jpg';
                                                ?>" class="w-full h-full object-cover">

                                    <div class="absolute top-2 right-2 bg-black/60 backdrop-blur-md px-3 py-1 rounded-full text-[10px] font-bold text-cyan-400">
                                        #<?php echo $item['id']; ?>
                                    </div>

                                    <div class="absolute inset-0 bg-cyan-500/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <span class="bg-black/80 text-white text-[10px] px-3 py-1 rounded-full border border-cyan-500">XEM CHI TIẾT</span>
                                    </div>
                                </a>
                            </div>


                            <h3 class="text-lg font-bold text-white line-clamp-2 mb-2 leading-tight">
                                <?php $item['name'] ?: 'Tác phẩm chưa đặt tên'; ?>
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
                                        XEM NGAY
                                    </a>
                                </div>

                            </div>


                        </div>
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
        // --- BẮT ĐẦU CHẾ ĐỘ GIÁM SÁT 45 GIÂY ---
        const mainPlayer = document.getElementById('main-hybrid-player');
        const previewLimit = 45;

        mainPlayer.addEventListener('timeupdate', function() {
            // Tạm thời để false để test. Sau này code xác thực ví sẽ điều khiển biến này.
            let isVerifiedMember = false;

            if (!isVerifiedMember && mainPlayer.currentTime >= previewLimit) {
                mainPlayer.pause();
                mainPlayer.currentTime = 0;

                // Hiện thông báo bằng 4 thứ tiếng (Dùng alert để test nhanh)
                showPopup(); // Gọi Popup đẹp thay vì hiện hộp thoại xấu

                // Thu thanh nhạc lại để yêu cầu xác thực
                document.getElementById('music-player-bar').classList.add('translate-y-full');
            }
        });
        // --- KẾT THÚC CHẾ ĐỘ GIÁM SÁT ---
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
            } else if (player.webkitRequestFullscreen) {
                /* Safari */
                player.webkitRequestFullscreen();
            } else if (player.msRequestFullscreen) {
                /* IE11 */
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

        function showPopup() {
            // Nếu đã có popup rồi thì không tạo thêm
            if (document.getElementById('-popup')) return;
            // Thay đoạn nút bấm cũ bằng thiết kế "Phân cấp" này
            const btnHtml = `
    <div class="flex flex-col gap-4 mt-6">
        <!-- NÚT CHÍNH: SANG TRỌNG, NỔI BẬT -->
        <a href="/NFTMusicmarketplace/mint_page.php" class="bg-gradient-to-r from-cyan-400 to-blue-500 text-black font-black py-5 rounded-full hover:scale-105 transition shadow-[0_0_30px_rgba(0,255,255,0.5)] uppercase tracking-tighter"> 💎 Mua trực tiếp tại HungLouis (Full Privileges) </a>
        <!-- NÚT PHỤ: KHIÊM TỐN HƠN -->
        <a href="https://opensea.io" target="_blank" class="text-gray-400 text-xs hover:text-white transition underline decoration-gray-600"> Hoặc mua qua sàn thứ cấp OpenSea </a>
    </div>
`;
            document.body.insertAdjacentHTML('beforeend', popupHtml);
        }
        async function buyNFTWithToken(contractAddress, tokenId, price, tokenType) {
            const provider = new ethers.BrowserProvider(window.ethereum);
            const signer = await provider.getSigner();

            // Nếu dùng MATIC trực tiếp
            if (tokenType === "MATIC") {
                const tx = await marketplaceContract.buyWithMatic(contractAddress, tokenId, {
                    value: ethers.parseEther(price.toString())
                });
                await tx.wait();
            }
            // Nếu dùng USDT/USDC
            else {
                // Bước 1: Người mua phải Approve cho Sàn sử dụng USDT của họ
                const usdtContract = new ethers.Contract(USDT_ADDRESS, ERC20_ABI, signer);
                await usdtContract.approve(MARKETPLACE_ADDRESS, ethers.parseUnits(price, 6));

                // Bước 2: Thực hiện mua trên Sàn
                await marketplaceContract.buyWithToken(contractAddress, tokenId, price, USDT_ADDRESS);
            }
        }
        // Biến tạm quản lý luồng phát thử nhạc âm thanh công cộng
        let currentAudio = null;
        let currentCard = null;
        let previewTimeout = null;

        function playPreview(cardElement) {
            // 1. Nếu đang có một bài khác phát, dọn dẹp và tắt bài cũ trước
            if (currentCard && currentCard !== cardElement) {
                stopPreview(currentCard);
            }

            currentCard = cardElement;

            // Đổi biểu tượng nút bấm ở giữa ảnh sang hình Pause ⏸
            const playButton = cardElement.querySelector('.play-btn-overlay');
            if (playButton) playButton.innerHTML = '⏸';

            // 2. TÌM XEM CARD NÀY LÀ MV (VIDEO) HAY LÀ ẢNH THƯỜNG
            const videoPreview = cardElement.querySelector('.nft-video-preview');

            if (videoPreview) {
                // --- TRƯỜNG HỢP A: ĐỐI VỚI MUSIC VIDEO (MV) ---
                videoPreview.style.display = 'block'; // Hiện khung hình lên
                videoPreview.currentTime = 0; // Tua video về giây đầu tiên

                // MẤU CHỐT: Mở âm thanh trực tiếp của video bằng lệnh JavaScript
                videoPreview.muted = false;
                videoPreview.volume = 1.0; // Đặt âm lượng lớn nhất

                // Kích hoạt phát cả Hình lẫn Tiếng đồng bộ
                videoPreview.play().catch(error => {
                    console.warn("Chrome chặn tiếng do chưa click tương tác: ", error.message);
                    // Nếu bị chặn, video vẫn chạy hình, người dùng chỉ cần click 1 cái vào màn hình là lần sau sẽ có tiếng
                });
            } else {
                // --- TRƯỜNG HỢP B: ĐỐI VỚI BÀI HÁT ẢNH TĨNH THƯỜNG ---
                const audioUrl = cardElement.getAttribute('data-audio');
                if (audioUrl && (!window.currentAudio)) {
                    window.currentAudio = new Audio(audioUrl);
                    window.currentAudio.play().catch(err => console.log("Chờ tương tác"));
                }
            }

            // BỘ ĐẾM THỜI GIAN: Đúng 45 giây tự động gọi hàm ngắt cả hình lẫn tiếng
            clearTimeout(previewTimeout);
            previewTimeout = setTimeout(() => {
                if (currentCard === cardElement) {
                    stopPreview(cardElement);
                }
            }, 45000); // 45 giây để người dùng có đủ thời gian thưởng thức preview
        }

        function stopPreview(cardElement) {
            if (currentCard === cardElement) {
                // 1. Tắt và ẩn Video nếu là MV
                const videoPreview = cardElement.querySelector('.nft-video-preview');
                if (videoPreview) {
                    videoPreview.pause();
                    videoPreview.muted = true; // Khóa tiếng lại trước khi ẩn
                    videoPreview.style.display = 'none'; // Ẩn khung hình video đi
                }

                // 2. Tắt Audio nếu là bài hát thường
                if (window.currentAudio) {
                    window.currentAudio.pause();
                    window.currentAudio = null;
                }

                // Trả icon nút bấm ở giữa ảnh về lại hình tam giác ▶ ban đầu
                const playButton = cardElement.querySelector('.play-btn-overlay');
                if (playButton) playButton.innerHTML = '▶';

                currentCard = null;
                clearTimeout(previewTimeout);
            }
        }
        //let currentCard = null;
        //let previewTimeout = null;

        function playPreview(cardElement) {
            if (currentCard && currentCard !== cardElement) {
                stopPreview(currentCard);
            }

            currentCard = cardElement;
            const playButton = cardElement.querySelector('.play-btn-overlay');
            if (playButton) playButton.innerHTML = '⏸';

            // 1. NHẬN DIỆN VÍ VÀ ĐỊA CHỈ VÍ CHỦ SỞ HỮU VẬT PHẨM
            // Lấy ví của người đang xem trang web từ PHP Session (Đổi về chữ thường để so sánh)
            const currentViewerWallet = "<?= isset($_SESSION['user_wallet']) ? strtolower(trim($_SESSION['user_wallet'])) : '' ?>".trim();
            // Lấy ví của chủ nhân thực sự sở hữu bài hát này từ thuộc tính data-owner của thẻ
            const itemOwnerWallet = cardElement.getAttribute('data-owner').toLowerCase().trim();

            // 2. KHỞI ĐỘNG PHÁT FILE HÌNH HOẶC FILE TIẾNG
            const videoPreview = cardElement.querySelector('.nft-video-preview');

            if (videoPreview) {
                // NẾU LÀ MV: Bật khung hình video và mở âm thanh trực tiếp của video ca nhạc
                videoPreview.style.display = 'block';
                videoPreview.currentTime = 0;
                videoPreview.muted = false;
                videoPreview.volume = 1.0;
                videoPreview.play().catch(err => console.log("Chờ người dùng tương tác màn hình"));
            } else {
                // NẾU LÀ AUDIO THƯỜNG: Phát qua đối tượng Audio của trình duyệt
                const audioUrl = cardElement.getAttribute('data-audio');
                if (audioUrl && (!window.currentAudio)) {
                    window.currentAudio = new Audio(audioUrl);
                    window.currentAudio.play().catch(err => console.log("Chờ người dùng tương tác màn hình"));
                }
            }

            // 3. THUẬT TOÁN KIỂM TRA CHÍNH CHỦ - BẢO VỆ BẢN QUYỀN TUYỆT ĐỐI (45 GIÂY)
            clearTimeout(previewTimeout);

            // ĐIỀU KIỆN KÉP: Nếu chưa kết nối ví HOẶC ví đang kết nối KHÔNG trùng khớp với ví chủ sở hữu bản nhạc
            if (!currentViewerWallet || currentViewerWallet !== itemOwnerWallet) {
                console.log("⚠️ Cảnh báo tác quyền: Người xem không phải là chủ sở hữu bản nhạc. Giới hạn 45 giây kích hoạt.");

                previewTimeout = setTimeout(() => {
                    if (currentCard === cardElement) {
                        // Tắt ngay lập tức luồng hình ảnh chuyển động và âm thanh
                        if (videoPreview) {
                            videoPreview.pause();
                            videoPreview.muted = true;
                            videoPreview.style.display = 'none';
                        } else if (window.currentAudio) {
                            window.currentAudio.pause();
                            window.currentAudio = null;
                        }

                        if (playButton) playButton.innerHTML = '▶';

                        // Bắn thông báo văn minh, khẳng định giá trị tác phẩm
                        alert("🎵 Bạn đã nghe hết 45 giây thử nghiệm của tác phẩm. Đây là nội dung độc quyền, vui lòng mua NFT bài hát này để được cấp quyền thưởng thức trọn vẹn ca khúc!");
                        currentCard = null;
                    }
                }, 45000); // Ngắt đúng giây thứ 45
            } else {
                console.log("✅ Xác thực chính chủ thành công! Bạn đang sở hữu bản quyền NFT này, hệ thống mở khóa nghe trọn vẹn toàn bộ bài hát.");
            }
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

        function stopPreview(cardElement) {
            if (currentCard === cardElement) {
                const videoPreview = cardElement.querySelector('.nft-video-preview');
                if (videoPreview) {
                    videoPreview.pause();
                    videoPreview.muted = true;
                    videoPreview.style.display = 'none';
                }
                if (window.currentAudio) {
                    window.currentAudio.pause();
                    window.currentAudio = null;
                }
                const playButton = cardElement.querySelector('.play-btn-overlay');
                if (playButton) playButton.innerHTML = '▶';

                currentCard = null;
                clearTimeout(previewTimeout);
            }
        }
    </script>
</body>

</html>
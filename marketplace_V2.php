<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lấy ví người dùng nếu có (để bẫy nút Mua nếu họ tự mua bài của chính mình)
$userWallet = isset($_SESSION['user_wallet']) ? strtolower(trim($_SESSION['user_wallet'])) : '';

// 1. CẤU HÌNH SUPABASE GỌI TOÀN BỘ SẢN PHẨM ĐANG Rao BÁN
$supabaseUrl = "https://hmvvjjiiaelcsfqgxbxv.supabase.co"; // Thay URL thật
$supabaseAnonKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw"; // Thay Key thật
$tableName = "items";

// Bộ lọc: Chỉ tải về các vật phẩm có trạng thái rao bán (is_listed = true)
$apiUrl = $supabaseUrl . "/rest/v1/" . $tableName .  "?price=gt.0";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "apikey: " . $supabaseAnonKey,
    "Authorization: Bearer " . $supabaseAnonKey
]);
$response = curl_exec($ch);
curl_close($ch);

$market_items = json_decode($response, true);
if (!is_array($market_items)) { $market_items = []; }
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>TRUNG TÂM GIAO DỊCH NFT CÔNG KHAI | NFT TRANSACTION CENTER</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="assets/style.css"> <!-- Sử dụng lại file CSS của bạn -->
    <!-- Nhúng thư viện Ethers.js ở đầu trang -->
    <script src="https://cdn.jsdelivr.net/npm/ethers/dist/ethers.min.js"></script>
</head>
<body class="p-5 md:p-10">
     <?php if(file_exists('navbar.php')) include 'navbar.php'; ?>

    <div class="marketplace-wrapper">
        <header style="padding: 40px 0 20px 0; border-bottom: 1px solid #262629; margin-bottom: 30px;">
            <h1 style="font-size: 32px; margin: 0; color: #fff;">🎵 TRUNG TÂM GIAO DỊCH NFT CÔNG KHAI | NFT TRANSACTION CENTER</h1>
            <p style="color: #a1a1aa; margin: 8px 0 0 0;">Khám phá và sở hữu bản quyền các bài hát trực tiếp từ nghệ sĩ</p>
        </header>
<!-- BANNER BẢNG THỐNG KÊ THÔNG SỐ SÀN GIAO DỊCH -->
<div class="market-stats-band">
    <div class="stat-item">
        <span class="stat-icon">🔹</span>
        <span class="stat-label">Ví kết nối của bạn:</span>
        <strong class="stat-value wallet-text">
            <?= !empty($userWallet) ? htmlspecialchars($userWallet) : 'Chưa kết nối ví' ?>
        </strong>
    </div>
    
    <div class="stat-item">
        <span class="stat-icon">🔹</span>
        <span class="stat-label">Tổng số bài hát trên sàn chợ:</span>
        <strong class="stat-value"><?= count($market_items) ?> vật phẩm.</strong>
    </div>

    <?php 
    // Thuật toán đếm xem trong danh sách chợ có bao nhiêu bài thuộc sở hữu của chính người đang xem
    $my_listed_count = 0;
    foreach ($market_items as $m_item) {
        if (isset($m_item['owner_address']) && strtolower(trim($m_item['owner_address'])) === $userWallet) {
            $my_listed_count++;
        }
    }
    ?>
    <div class="stat-item">
        <span class="stat-icon">🔹</span>
        <span class="stat-label">Số sản phẩm bạn đang rao bán:</span>
        <strong class="stat-value"><?= $my_listed_count ?> vật phẩm.</strong>
    </div>
</div>
        <!-- LƯỚI SẢN PHẨM RAO BÁN -->
        <div class="nft-grid">
            <?php if (!empty($market_items)): ?>
                <?php foreach ($market_items as $item): ?>
                    <?php 
                    $isSeller = (strtolower(trim($item['owner_address'] ?? '')) === $userWallet); 
                    ?>
                    <!-- Tìm thẻ div bọc ngoài của từng ô card nhạc và sửa lại thành cấu trúc này -->
<div class="music-card" 
     data-audio="<?= htmlspecialchars($item['image_url'] ?? 'assets/default-preview.mp3') ?>" 
     onmouseenter="playPreview(this)" 
     onmouseleave="stopPreview(this)">
     
    <div class="card-media">
    <?php 
    $mediaUrl = htmlspecialchars($item['image_url'] ?? 'assets/default-music.png');
    
    // Thuật toán PHP tự cắt đuôi file để kiểm tra định dạng
    $fileExtension = strtolower(pathinfo($mediaUrl, PATHINFO_EXTENSION));
    $videoExtensions = ['mp4', 'webm', 'ogg', 'mov'];
    $isVideo = in_array($fileExtension, $videoExtensions);
    ?>

    <?php if ($isVideo): ?>
        <!-- TRƯỜNG HỢP 1: NẾU LÀ MUSIC VIDEO (MV) -->
        <!-- Hiện ảnh đại diện tĩnh mặc định trước, khi hover sẽ hiện video -->
        <img src="assets/default-video-cover.png" class="nft-thumb-img video-poster" alt="MV Poster">
        
        <!-- Thẻ video ẩn chạy ngầm, tắt tiếng mặc định (muted) để lách luật trình duyệt -->
        <video src="<?= $mediaUrl ?>" class="nft-video-preview" loop muted playsinline style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; display: none;"></video>
    <?php else: ?>
        <!-- TRƯỜNG HỢP 2: NẾU CHỈ LÀ FILE ẢNH/AUDIO THƯỜNG -->
        <img src="<?= $mediaUrl ?>" class="nft-thumb-img" alt="Cover" onerror="this.onerror=null; this.src='assets/default-music.png';">
    <?php endif; ?>

    <!-- Nút Play ở giữa ảnh giữ nguyên -->
    <button class="play-btn-overlay">▶</button>
</div>

    
    <div class="card-body">
        <h4><?= htmlspecialchars($item['name'] ?? 'Chưa đặt tên') ?></h4>
        <!-- ... các đoạn thông tin giá và nút bấm hành động của bạn giữ nguyên ... -->
                            
                            <!-- Giá niêm yết hiển thị tinh tế -->
                            <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 12px;">
                                <span style="color: #a1a1aa; font-size: 13px;">Giá niêm yết:</span>
                                <strong style="color: #f1c40f; font-size: 16px; font-family: monospace;"><?= htmlspecialchars($item['price'] ?? '0') ?> ETH</strong>
                            </div>

                            <!-- NÚT MUA NGAY -->
                            <div class="market-actions">
                                <?php if ($isSeller): ?>
                                    <!-- Nếu người xem chính là người bán -->
                                    <button disabled style="width: 100%; background-color: #3f3f46; color: #a1a1aa; border: none; padding: 10px; font-weight: 600; border-radius: 8px; cursor: not-allowed;">Vật phẩm của bạn</button>
                                <?php else: ?>
                                    <!-- Nút bấm mua thực tế gọi ví Web3 -->
                                    <!-- Sửa lại nút bấm Mua Ngay để truyền thêm địa chỉ hợp đồng của từng bài hát -->
                                    <button class="btn-buy-now" 
                                            onclick="executeBuyMusic('<?= htmlspecialchars($item['token_id'] ?? '0') ?>', '<?= htmlspecialchars($item['price'] ?? '0') ?>', '<?= htmlspecialchars($item['contract_address'] ?? '') ?>')" 
                                            style="width: 100%; background-color: #2563eb; color: white; border: none; padding: 10px; font-weight: 600; border-radius: 8px; cursor: pointer; font-size: 14px;">
                                        🛒 Mua Ngay
                                    </button>

                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state" style="grid-column: 1/-1; text-align: center; padding: 60px 0; color: #a1a1aa;">
                    <p>🎷 Hiện chưa có bản nhạc nào được treo bán trên thị trường.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- JAVASCRIPT XỬ LÝ HÀM MUA TRỰC TIẾP ON-CHAIN -->
    <script>
    // 1. Thêm tham số nftContractAddress vào hàm nhận diện
async function executeBuyMusic(tokenId, priceInEth, nftContractAddress) {
    if (!window.ethereum) {
        alert("Vui lòng cài đặt và kết nối ví MetaMask!");
        return;
    }

    // Bẫy lỗi nếu dữ liệu địa chỉ hợp đồng của bài hát này trong database bị trống
    if (!nftContractAddress || nftContractAddress.length < 40) {
        alert("Lỗi: Bài hát này chưa được liên kết với địa chỉ Smart Contract trên Blockchain!");
        return;
    }

    try {
        const provider = new ethers.providers.Web3Provider(window.ethereum);
        const signer = provider.getSigner();

        // 2. BIẾN ĐỘNG: Tự động lấy địa chỉ bộ sưu tập của chính bài hát đó từ Supabase truyền sang
        const NFT_CONTRACT_ADDRESS = nftContractAddress;

        // 3. BIẾN CỐ ĐỊNH: Đây mới là địa chỉ hợp đồng NFTMarketplace bạn đã deploy trên Remix
        const MARKETPLACE_ADDRESS = "0x624dce1e5da13ad6f45e897280d1a3f8b36b4af3";

        const valueInWei = ethers.utils.parseEther(priceInEth.toString());
        alert(`Khởi tạo mua bản nhạc thuộc hợp đồng: ${NFT_CONTRACT_ADDRESS} - Token ID: #${tokenId}`);

        const marketplaceABI = [
            "function buyItem(address nftContract, uint256 tokenId) external payable"
        ];
        const marketplaceContract = new ethers.Contract(MARKETPLACE_ADDRESS, marketplaceABI, signer);

        // Gọi hàm mua thực tế on-chain (Truyền đúng địa chỉ bộ sưu tập và Token ID vào)
        const tx = await marketplaceContract.buyItem(NFT_CONTRACT_ADDRESS, tokenId, {
            value: valueInWei
        });

        alert("Giao dịch đang được xử lý on-chain... Vui lòng không đóng trình duyệt.");
        await tx.wait();

        // ... các đoạn fetch cập nhật database nội bộ phía dưới giữ nguyên ...


            // --- CẬP NHẬT DATABASE SUPABASE ĐỔI CHỦ SỞ HỮU MỚI ---
            // Sau khi mua thành công, owner_address biến thành ví của người mua, is_listed về false
            // Thay thế đoạn fetch cũ sau khi giao dịch Blockchain thành công
            const response = await fetch('api/process_buy.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    token_id: tokenId,
                    buyer_address: buyerAddress,
                    price: priceInEth
                })
            });
            const result = await response.json();
            if (result.status === 'success') {
                alert("🎉 Chúc mừng! Giao dịch thành công, bạn đã là chủ sở hữu mới của bản nhạc!");
                window.location.reload();
            }


        } catch (error) {
            console.error("Lỗi giao dịch mua nhạc:", error);
            alert("Giao dịch thất bại! Chi tiết lỗi: " + (error.reason || error.message));
        }
    }
    // Tạo một biến toàn cục để lưu vết đối tượng âm thanh đang phát, tránh việc nhiều bài phát đè lên nhau
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
        videoPreview.currentTime = 0;         // Tua video về giây đầu tiên
        
        // MẤU CHỐT: Mở âm thanh trực tiếp của video bằng lệnh JavaScript
        videoPreview.muted = false;           
        videoPreview.volume = 1.0;            // Đặt âm lượng lớn nhất
        
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



    </script>
</body>
</html>

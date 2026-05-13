<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Giữ cơ chế nhận ví động từ MetaMask chuyển về dạng chữ thường
if (!isset($_SESSION['user_wallet']) || empty($_SESSION['user_wallet'])) {
    $_SESSION['user_wallet'] = "0x8429BC345266D03a433b25B8Fb6301274294D81E"; // Ví mặc định để tránh trống trang
}
$currentWallet = strtolower(trim($_SESSION['user_wallet']));

// 2. Nhận trạng thái tab từ URL công khai công nghệ công cộng
$currentTab = isset($_GET['tab']) ? $_GET['tab'] : 'collected';

// 3. ĐƯỜNG DẪN GỌI SẠCH TỔNG DỮ LIỆU TỪ SUPABASE (Xóa hoàn toàn bộ lọc cũ)
$supabaseUrl = "https://hmvvjjiiaelcsfqgxbxv.supabase.co"; // Thay url thật của bạn
$supabaseAnonKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw"; // Thay key thật của bạn
$tableName = "items";

// Tải hết 100% dữ liệu đang có trong bảng items về máy
$apiUrl = $supabaseUrl . "/rest/v1/" . $tableName; 

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "apikey: " . $supabaseAnonKey,
    "Authorization: Bearer " . $supabaseAnonKey
]);

$all_response = curl_exec($ch);
curl_close($ch);

$all_items = json_decode($all_response, true);
if (!is_array($all_items)) { $all_items = []; }

// 4. THUẬT TOÁN TỰ LỌC TẠI SÂN NHÀ PHP
$filtered_items = []; // Mảng chứa kết quả sau khi tự lọc

foreach ($all_items as $single_item) {
    // Chuyển địa chỉ ví lưu trong database về chữ thường để so sánh cho chính xác
    $db_owner = isset($single_item['owner_address']) ? strtolower(trim($single_item['owner_address'])) : '';
    $db_creator = isset($single_item['creator_address']) ? strtolower(trim($single_item['creator_address'])) : '';

    if ($currentTab === 'created') {
        // Nếu đang ở tab Sáng tác, tự nhặt các bài có creator trùng với ví hiện tại
        if ($db_creator === $currentWallet) {
            $filtered_items[] = $single_item;
        }
    } else {
        // Mặc định hoặc tab Đang sở hữu, tự nhặt các bài có owner trùng với ví hiện tại
        if ($db_owner === $currentWallet) {
            $filtered_items[] = $single_item;
        }
    }
}
?>



<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Profile | NFT Music Marketplace</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Nhúng thư viện Ethers.js bản chuẩn mã nguồn mở để xử lý các lệnh Web3/Blockchain -->
<script src="cloudflare.com" type="text/javascript"></script>

    <style>
    /* Toàn bộ giao diện nền tối */
body.nft-dark-mode {
    background-color: #0a0a0b;
    color: #ffffff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 20px;
}


    </style>
</head>
<body class="p-5 md:p-10">
     <?php if(file_exists('navbar.php')) include 'navbar.php'; ?>

    <!-- Header: Banner & Avatar -->
    <section <!-- Cấu trúc phần đầu Hồ sơ chuẩn OpenSea -->
<div class="profile-header-wrapper">
    <!-- 1. Vùng ảnh Banner -->
    <div class="profile-banner">
        <!-- Thay link ảnh bằng banner thật của bạn nếu có -->
        <img src="https://unsplash.com" alt="Banner">
    </div>

    <!-- 2. Khối thông tin cá nhân -->
    <div class="profile-info-container">
        <!-- Khung ảnh đại diện Avatar -->
        <div class="avatar-holder">
            <!-- Sử dụng API Dicebear tạo hình đại diện tự động theo mã ví -->
            <img src="https://api.dicebear.com/5.5.1/initials/svg?seed=<?= urlencode($currentWallet) ?>" alt="Avatar">
        </div>

        <!-- Tên người dùng & Địa chỉ ví -->
        <div class="user-details">
            <h1 class="user-name">hunglouis_manhhung</h1>
            <div class="wallet-badge" onclick="navigator.clipboard.writeText('<?= $currentWallet ?>')">
                <span class="wallet-text">
                    <?= substr($currentWallet, 0, 6) ?>...<?= substr($currentWallet, -4) ?>
                </span>
                <span class="copy-icon" title="Sao chép địa chỉ ví">📋</span>
                <!-- Dưới phần hiển thị mã ví rút gọn, bạn thêm nút này -->
                <button id="change-wallet-btn" style="background: #1e1e24; border: 1px solid #3f3f46; color: #fff; padding: 6px 12px; border-radius: 20px; cursor: pointer; margin-left: 10px; font-size: 13px;">
                    🔄 Đổi ví khác
                </button>
            </div>
        </div>
    </div>
</div>
    </section>
<?php
    // 1. Nhận trạng thái tab từ URL, mặc định nếu không có là 'collected'
    $currentTab = isset($_GET['tab']) ? $_GET['tab'] : 'collected';
?>
    <!-- 2. Cập nhật class 'active' cho các nút dựa vào PHP -->
    <div class="profile-tabs">
        <a href="?tab=collected" class="<?= $currentTab == 'collected' ? 'active' : '' ?>">Collected</a>
        <a href="?tab=created" class="<?= $currentTab == 'created' ? 'active' : '' ?>">Created</a>
        <a href="?tab=activity" class="<?= $currentTab == 'activity' ? 'active' : '' ?>">Activity</a>
    </div>

        <!-- NFT Grid Area -->

<!-- CHÉP ĐOẠN CODE KIỂM TRA VÀO ĐÂY -->
<div style="background: #1c1c1f; padding: 15px; margin-bottom: 20px; border-radius: 8px; font-size: 13px; color: #a1a1aa;">
    <p>🔹 Ví hiện tại của bạn: <strong><?= $currentWallet ?></strong></p>
    <p>🔹 Tổng số item tải về từ Supabase: <strong><?= count($all_items) ?></strong> bài hát.</p>
    <p>🔹 Số item sau khi tự lọc theo ví này: <strong><?= count($filtered_items) ?></strong> bài hát.</p>
</div>


<div class="nft-grid">
    <?php if (!empty($filtered_items) && is_array($filtered_items)): ?>
        <?php foreach ($filtered_items as $item): ?>
            <!-- ĐẢM BẢO THẺ KHỞI ĐẦU music-card NẰM BÊN TRONG VÒNG LẶP -->
            <div class="music-card">
                <div class="card-media">
                    <?php 
                    $defaultImage = "http://www.unsplash.com/photos/1614680376593-902f74fa0d41?q=80&w=500"; // Link ảnh mặc định nếu item không có ảnh
                    $itemImage = (!empty($item['image_url'])) ? htmlspecialchars($item['image_url']) : $defaultImage;
                    ?>
                    <img src="<?= $itemImage ?>" class="nft-thumb-img" alt="Thumbnail" onerror="this.onerror=null; this.src='<?= $defaultImage ?>';">
                    <button class="play-btn-overlay">▶</button>
                </div>
                
                <div class="card-body">
                    <h4><?= htmlspecialchars($item['name'] ?? 'Chưa đặt tên') ?></h4>
                    <div class="owner-actions">
                <?php
// Kiểm tra trạng thái niêm yết từ database Supabase
$isListed = isset($item['is_listed']) ? $item['is_listed'] : false;
$tokenId = htmlspecialchars($item['token_id'] ?? '0');
?>        
                    <?php $nft = $item;?>
                    </div>
                    <div class="button-container" style="display: flex; gap: 10px; margin-top: 10px; width: 100%;">
    <?php if ($isListed): ?>
        <!-- Nếu bản nhạc ĐANG BÁN -> Hiện nút Hủy Bán màu đỏ -->
        <button class="btn btn-cancel" 
                onclick="executeCancelSale('<?= $tokenId ?>')" 
                style="flex: 1; background-color: #e74c3c; color: white; border: none; padding: 8px 12px; font-weight: 600; border-radius: 6px; cursor: pointer; font-size: 13px;">
            Hủy bán
        </button>
    <?php else: ?>
        <!-- Nếu bản nhạc CHƯA BÁN -> Hiện nút Đăng Bán màu xanh lá -->
        <button class="btn btn-sell" 
                onclick="openSellModal('<?= $tokenId ?>')" 
                style="flex: 1; background-color: #27ae60; color: white; border: none; padding: 8px 12px; font-weight: 600; border-radius: 6px; cursor: pointer; font-size: 13px;">
            Đăng bán
        </button>
    <?php endif; ?>

    <!-- Nút Tặng / Cho luôn xuất hiện để chủ sở hữu chuyển nhượng tự do -->
    <button class="btn btn-gift" 
            onclick="transferGift('<?= $tokenId ?>')" 
            style="flex: 1; background-color: #2980b9; color: white; border: none; padding: 8px 12px; font-weight: 600; border-radius: 6px; cursor: pointer; font-size: 13px;">
        Tặng / Cho
    </button>
</div>
                    
                </div>    
            </div> 
            <!-- ĐẢM BẢO THẺ ĐÓNG music-card NẰM TRƯỚC LỆNH ENDFOREACH -->
        <?php endforeach; ?>
    <?php else: ?>
        <div class="empty-state" style="grid-column: 1/-1; text-align: center; padding: 40px 0; color: #a1a1aa;">
            <p>🎵 Không tìm thấy bản nhạc nào thuộc sở hữu của ví này.</p>
        </div>
    <?php endif; ?>
</div> <!-- Đây mới là thẻ đóng duy nhất và chuẩn xác của .nft-grid -->


<!-- ĐẢM BẢO ĐOẠN NÀY NẰM NGOÀI TẤT CẢ CÁC VÒNG LẶP, NẰM ĐỘC LẬP Ở CUỐI FILE -->
<div id="giftModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>🎁 Tặng / Chuyển Nhượng Bản Nhạc</h3>
            <span class="close-modal-btn" onclick="closeGiftModal()">&times;</span>
        </div>
        <div class="modal-body">
            <p style="color: #a1a1aa; font-size: 13px; margin-bottom: 15px;">Vui lòng nhập chính xác địa chỉ ví MetaMask của người nhận:</p>
            
            <!-- Ô chứa ID ẩn để lưu vết vật phẩm đang chọn -->
            <input type="hidden" id="gift-token-id" value="">
            
            <div class="input-group">
                <input type="text" id="receiver-wallet" placeholder="Ví dụ: 0x71C..." autocomplete="off">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-modal-cancel" onclick="closeGiftModal()">Hủy bỏ</button>
            <button class="btn-modal-confirm" onclick="executeGiftNFT()">Xác Nhận Tặng</button>
        </div>
    </div>
</div>
<!-- Giao diện Popup Modal Đăng Bán (Ẩn mặc định) -->
<div id="sellModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>🏷️ Niêm Yết Bán Bản Nhạc</h3>
            <span class="close-modal-btn" onclick="closeSellModal()">&times;</span>
        </div>
        <div class="modal-body">
            <p style="color: #a1a1aa; font-size: 13px; margin-bottom: 15px;">Nhập mức giá bạn muốn bán (Đơn vị tính bằng ETH):</p>
            
            <!-- Ô chứa ID ẩn của vật phẩm -->
            <input type="hidden" id="sell-token-id" value="">
            
            <div class="input-group" style="position: relative; display: flex; align-items: center;">
                <input type="number" id="sell-price" step="0.01" min="0" placeholder="Ví dụ: 0.05" autocomplete="off" style="width: 100%; background-color: #0a0a0b; border: 1px solid #262629; color: #ffffff; padding: 12px 60px 12px 16px; border-radius: 10px; font-size: 16px; outline: none;">
                <span style="position: absolute; right: 16px; color: #a1a1aa; font-weight: bold; font-size: 14px;">ETH</span>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-modal-cancel" onclick="closeSellModal()">Hủy bỏ</button>
            <button class="btn-modal-confirm" onclick="executeSellNFT()" style="background-color: #27ae60;">Xác Nhận Bán</button>
        </div>
    </div>
</div>

<!-- Dán đoạn mã Script này vào cuối file profile.php (ngay trước thẻ </body>) -->
<script>
// 1. Hàm bật mở khung Popup Tặng / Cho
function transferGift(tokenId) {
    console.log("Đang gọi popup cho Token ID:", tokenId);
    const modal = document.getElementById('giftModal');
    if (modal) {
        document.getElementById('gift-token-id').value = tokenId;
        document.getElementById('receiver-wallet').value = '';
        modal.classList.add('show'); // Thêm class show để hiện CSS
    } else {
        alert("Lỗi: Không tìm thấy thẻ HTML id='giftModal' trong file!");
    }
}

function closeGiftModal() {
    const modal = document.getElementById('giftModal');
    if (modal) modal.classList.remove('show');
}

// 2. Logic đồng bộ ví khi bấm nút "Đổi ví khác" hoặc thao tác MetaMask
async function updatePHPSession(accounts) {
    if (!accounts || accounts.length === 0) return;
    const walletAddress = accounts[0].toLowerCase();
    
    await fetch('update_wallet.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ wallet: walletAddress })
    });
    window.location.reload();
}

if (window.ethereum) {
    // Tự động nhận diện đổi ví trực tiếp trên MetaMask
    window.ethereum.on('accountsChanged', function (accounts) {
        updatePHPSession(accounts);
    });

    // Xử lý khi nhấn nút bấm "Đổi ví khác" trên UI của bạn
    const changeBtn = document.getElementById('change-wallet-btn');
    if (changeBtn) {
        changeBtn.addEventListener('click', async () => {
            try {
                const accounts = await window.ethereum.request({ 
                    method: 'wallet_requestPermissions', 
                    permissions: [{ eth_accounts: {} }] 
                });
                const accountList = await window.ethereum.request({ method: 'eth_accounts' });
                updatePHPSession(accountList);
            } catch (err) {
                console.log("Hủy đổi ví");
            }
        });
    }
}

async function executeGiftNFT() {
    const receiver = document.getElementById('receiver-wallet').value.trim();
    const tokenId = document.getElementById('gift-token-id').value;

    if (!receiver.startsWith('0x') || receiver.length !== 42) {
        alert('Địa chỉ ví người nhận không hợp lệ!');
        return;
    }

    try {
        alert('Vui lòng xác nhận và ký giao dịch chuyển nhượng NFT trên ví MetaMask của bạn!');

        // 1. KẾT NỐI VỚI METAMASK VÀ BLOCKCHAIN
        const provider = new ethers.providers.Web3Provider(window.ethereum);
        const signer = provider.getSigner();
        const myWalletAddress = await signer.getAddress();

        // 2. CẤU HÌNH SMART CONTRACT CỦA BẠN
        const contractAddress = "0xDia_Chi_Smart_Contract_That_Cua_Ban"; 
        const contractABI = [
            "function safeTransferFrom(address from, address to, uint256 tokenId)"
        ];
        const musicContract = new ethers.Contract(contractAddress, contractABI, signer);

        // 3. GỌI HÀM BLOCKCHAIN THỰC HIỆN CHUYỂN NFT
        // safeTransferFrom(Từ ví của tôi, Đến ví người nhận, Mã token id)
        const tx = await musicContract.safeTransferFrom(myWalletAddress, receiver, tokenId);
        
        console.log("Giao dịch đang được đào trên mạng lưới... Mã Hash:", tx.hash);
        await tx.wait(); // Chờ Blockchain xác nhận giao dịch thành công (On-chain xong)

        // 4. SAU KHI BLOCKCHAIN THÀNH CÔNG -> MỚI CẬP NHẬT DATABASE SÂN NHÀ (Off-chain)
        const response = await fetch('api/process_gift.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                token_id: tokenId,
                new_owner: receiver.toLowerCase()
            })
        });

        const result = await response.json();
        if (result.status === 'success') {
            alert('🎁 Đã chuyển nhượng thành công trên Blockchain và hệ thống dữ liệu!');
            closeGiftModal();
            window.location.reload();
        }

    } catch (error) {
        console.error("Lỗi giao dịch Blockchain:", error);
        alert("Giao dịch trên Blockchain thất bại hoặc bạn đã từ chối ký ví!");
    }
}

// 1. Hàm mở Popup Đăng bán
function openSellModal(tokenId) {
    const modal = document.getElementById('sellModal');
    if (modal) {
        document.getElementById('sell-token-id').value = tokenId;
        document.getElementById('sell-price').value = ''; // Xóa trống giá cũ
        modal.classList.add('show');
    }
}

// 2. Hàm đóng Popup Đăng bán
function closeSellModal() {
    const modal = document.getElementById('sellModal');
    if (modal) modal.classList.remove('show');
}

async function executeSellNFT() {
    const priceInput = document.getElementById('sell-price').value.trim();
    const tokenId = document.getElementById('sell-token-id').value;

    if (!priceInput || isNaN(priceInput) || parseFloat(priceInput) <= 0) {
        alert('Vui lòng nhập mức giá bán hợp lệ!');
        return;
    }

    if (!window.ethereum) {
        alert("Vui lòng cài đặt MetaMask!");
        return;
    }

    try {
        alert('Bước 1: Vui lòng xác nhận trên MetaMask để cấp quyền và đăng bán lên Blockchain...');

        // 1. Kết nối ví thông qua thư viện Ethers
        const provider = new ethers.providers.Web3Provider(window.ethereum);
        const signer = provider.getSigner();

        // 2. Điền chính xác địa chỉ các hợp đồng đã deploy của bạn vào đây
        const NFT_CONTRACT_ADDRESS = "0xĐịa_Chỉ_Hợp_Đồng_StudioNFT_Hoặc_MusicNFT_Của_Bài_Hát";
        const MARKETPLACE_ADDRESS = "0xĐịa_Chỉ_Hợp_Đồng_NFTMarketplace_Đã_Deploy_Của_Bạn";

        // Chuyển đổi giá tiền từ ETH sang định dạng Wei (Ví dụ: 0.1 ETH = 10^17 Wei)
        const priceInWei = ethers.utils.parseEther(priceInput);

        // 3. GỌI CONTRACT NFT GỐC ĐỂ PHÊ DUYỆT (APPROVE) CHO CHỢ ĐƯỢC QUYỀN CHUYỂN BÀI HÁT
        const nftABI = ["function approve(address to, uint256 tokenId) external"];
        const nftContract = new ethers.Contract(NFT_CONTRACT_ADDRESS, nftABI, signer);
        
        console.log("Đang gọi lệnh Approve...");
        const approveTx = await nftContract.approve(MARKETPLACE_ADDRESS, tokenId);
        await approveTx.wait(); // Chờ Blockchain xác nhận quyền approve

        // 4. GỌI HÀM listItem TRÊN SMART CONTRACT MARKETPLACE CỦA BẠN
        const marketplaceABI = [
            "function listItem(address nftContract, uint256 tokenId, uint256 price) external"
        ];
        const marketplaceContract = new ethers.Contract(MARKETPLACE_ADDRESS, marketplaceABI, signer);

        console.log("Đang gọi hàm listItem trên Marketplace...");
        const listTx = await marketplaceContract.listItem(NFT_CONTRACT_ADDRESS, tokenId, priceInWei);
        
        alert("Giao dịch đang được xử lý trên mạng lưới... Vui lòng đợi trong giây lát.");
        await listTx.wait(); // Chờ Blockchain đào xong block chứa lệnh niêm yết

        // 5. SAU KHI BLOCKCHAIN GHI NHẬN THÀNH CÔNG -> CẬP NHẬT DATABASE SÂN NHÀ (SUPABASE)
        const response = await fetch('api/process_sell.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                token_id: tokenId,
                price: parseFloat(priceInput),
                is_listed: true
            })
        });

        const result = await response.json();
        if (result.status === 'success') {
            alert('🏷️ Chúc mừng! Bản nhạc đã được niêm yết lên Blockchain và đồng bộ lên sàn thành công!');
            closeSellModal();
            window.location.reload();
        } else {
            alert('Cảnh báo: Đã lưu on-chain nhưng database không đồng bộ được: ' + result.message);
        }

    } catch (error) {
        console.error("Lỗi giao dịch Web3:", error);
        alert("Thao tác thất bại! Chi tiết lỗi: " + (error.reason || error.message));
    }
}
async function executeCancelSale(tokenId) {
    if (!confirm('Bạn có chắc chắn muốn hủy niêm yết và gỡ bản nhạc này xuống khỏi Marketplace không?')) {
        return;
    }

    if (!window.ethereum) {
        alert("Vui lòng cài đặt MetaMask!");
        return;
    }

    try {
        alert('Bước 1: Vui lòng ký xác nhận hủy niêm yết trên ví MetaMask để gỡ bỏ khỏi Blockchain...');

        const provider = new ethers.providers.Web3Provider(window.ethereum);
        const signer = provider.getSigner();

        // Điền chính xác địa chỉ hợp đồng đã deploy của bạn vào đây
        const NFT_CONTRACT_ADDRESS = "0xĐịa_Chỉ_Hợp_Đồng_StudioNFT_Hoặc_MusicNFT_Của_Bài_Hát";
        const MARKETPLACE_ADDRESS = "0xĐịa_Chỉ_Hợp_Đồng_NFTMarketplace_Đã_Deploy_Của_Bạn";

        // GỌI HÀM cancelListing TRÊN SMART CONTRACT MARKETPLACE CỦA BẠN
        const marketplaceABI = [
            "function cancelListing(address nftContract, uint256 tokenId) external"
        ];
        const marketplaceContract = new ethers.Contract(MARKETPLACE_ADDRESS, marketplaceABI, signer);

        console.log("Đang gọi hàm cancelListing trên Blockchain...");
        const cancelTx = await marketplaceContract.cancelListing(NFT_CONTRACT_ADDRESS, tokenId);
        
        alert("Yêu cầu đang được xử lý on-chain... Vui lòng đợi trong giây lát.");
        await cancelTx.wait(); // Chờ Blockchain xác nhận hủy niêm yết thành công

        // 2. SAU KHI BLOCKCHAIN THÀNH CÔNG -> CẬP NHẬT ĐỔI TRẠNG THÁI TRÊN SUPABASE (OFF-CHAIN)
        // Tận dụng lại file process_sell.php bằng cách đưa giá về 0 và is_listed về false
        const response = await fetch('api/process_sell.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                token_id: tokenId,
                price: 0,
                is_listed: false 
            })
        });

        const result = await response.json();
        if (result.status === 'success') {
            alert('🛑 Đã gỡ bản nhạc khỏi Marketplace thành công!');
            window.location.reload(); // Tải lại trang để cập nhật lại nút bấm giao diện
        } else {
            alert('Lỗi: Không thể đồng bộ trạng thái hủy bán về database: ' + result.message);
        }

    } catch (error) {
        console.error("Lỗi hủy niêm yết:", error);
        alert("Thao tác thất bại! Chi tiết: " + (error.reason || error.message));
    }
}


</script>


</body>
</html>

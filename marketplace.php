<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/finance_logic.php';

$user = $_SESSION['user'] ?? 'nhạc sĩ Mạnh Hùng';
$thongbao = "";
// 1. Lấy dữ liệu Supabase (Code hiện tại của bạn)
$res_supabase = callSupabase("SELECT * FROM songs");
$all_data = [];
if (!empty($res_supabase)) {
    $nfts_supabase = is_string($res_supabase) ? json_decode($res_supabase, true) : $res_supabase;
    foreach ($nfts_supabase as $s) {
        $all_data[] = [
            'name'      => $s['name'] ?? 'No name',
            'price'     => $s['price'] ?? '0',
            'audio_url' => $s['audio_url'] ?? '',
            'image_url' => $s['image_url'] ?? '',
            'source'    => 'Supabase' // Đánh dấu để dùng nút bấm riêng
        ];
    }
}

// 2. ĐƯA DỮ LIỆU OPENSEA VÀO MẢNG CHUNG
if (!empty($nfts_opensea) && is_array($nfts_opensea)) {
    foreach ($nfts_opensea as $n) {
        if (is_array($n)) {
            $all_data[] = [
                'name'      => $n['name'] ?? 'No name',
                'image_url' => $n['image_url'] ?? '',
                'price'     => '0.01', 
                'audio_url' => $n['audio_url'] ?? '', 
                'source'    => 'OpenSea'
            ];
        }
    }
}

// Trộn lẫn 2 nguồn nếu có dữ liệu
if (!empty($all_data)) {
    shuffle($all_data); 
}

// MUA NFT (GIỮ NGUYÊN HOÀN TOÀN LOGIC CỦA BẠN)
if (isset($_POST['buy_nft'])) {
    $song_id = $_POST['song_id'];
    $song_title = htmlspecialchars($_POST['song_title']);
    $price = (float)$_POST['price'];
    $balance = getBalance($conn, $user);
    if ($balance >= $price) {
        $prevHash = getLatestHash($conn);
        $res = callSupabase("SELECT id FROM blockchain ORDER BY id DESC LIMIT 1");
        $next_id = (isset($res[0]['id']) ? $res[0]['id'] : 0) + 1;
        $hash = calculateFinanceHash($next_id, $prevHash, $user, 'Hệ thống Music NFT', $price);
        $sql = "INSERT INTO blockchain (sender, receiver, amount, content, prev_hash, hash) 
                VALUES ('$user', 'Nhạc sĩ Mạnh Hùng', $price, '$song_title', '$prevHash', '$hash')";
        if (callSupabase($sql)) {
            $thongbao = "<div style='color:lime'>✔️ Đã mua: $song_title</div>";
        }
    } else {
        $thongbao = "<div style='color:red'>❌ Không đủ tiền</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title> NHẠC NFT</title>

<!-- ✅ Tailwind đúng -->
<script src="https://cdn.tailwindcss.com"></script>

<style>
body {
    background: radial-gradient(circle at top right, #0891b2, #064e3b, #020617);
    color: white;
}
.card {
    background: rgba(255,255,255,0.05);
    padding:15px;
    border-radius:15px;
}
</style>
</head>

<body class="p-10">
<?php include 'navbar.php'; ?>
<!-- Container chung dùng Grid để đảm bảo cân đối -->
<div id="nft-display-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-5">
    
    <!-- A. HIỂN THỊ DỮ LIỆU TỪ SUPABASE (PHP) -->
    <?php if (!empty($all_data)): ?>
        <?php foreach ($all_data as $item): ?>
            <div class="card bg-gray-900 border border-gray-700 p-4 rounded-2xl shadow-xl flex flex-col justify-between h-[500px]">
                <div>
                    <?php if (!empty($item['image_url'])): ?>
                        <img src="<?= $item['image_url'] ?>" class="w-full h-48 object-cover rounded-xl mb-4">
                    <?php else: ?>
                        <div class="w-full h-48 bg-gray-800 flex items-center justify-center rounded-xl mb-4 text-gray-500">No Image</div>
                    <?php endif; ?>
                    
                    <h3 class="text-lg font-bold text-white truncate text-center"><?= $item['name'] ?></h3>
                    <p class="text-yellow-500 font-bold text-center mt-1">💰 <?= $item['price'] ?> MATIC</p>
                    <p class="text-xs text-gray-500 text-center mt-1">Nguồn: Supabase</p>
                </div>

                <div class="flex flex-col gap-2 mt-4">
                    <button onclick="playLocal('<?= $item['audio_url'] ?>', '<?= $item['name'] ?>')" 
                            class="bg-emerald-600 hover:bg-emerald-700 text-white py-2 rounded-lg font-medium transition-all">▶ Nghe Nhạc</button>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-medium transition-all">🦊 Kết nối ví</button>
                    <button class="bg-orange-600 hover:bg-orange-700 text-white py-2 rounded-lg font-medium transition-all">💰 Mua NFT</button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- B. KHU VỰC CHỜ CHO OPENSEA (JS) -->
    <!-- Dữ liệu JS sẽ được "nối đuôi" vào cùng hàng với Supabase -->
</div>

<!-- Thay đổi class thành grid để tự động chia cột và xuống hàng -->
<div id="nft-list" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 p-5">
    
    <!-- Phần PHP (Supabase) của bạn nằm ở đây -->
    <?php foreach ($all_data as $item): ?>
        <div class="card bg-gray-800 p-4 rounded-xl border border-gray-700 grid grid-col h-full">
             <!-- Giữ nguyên nội dung bên trong thẻ card của bạn -->
        </div>
    <?php endforeach; ?>

    <!-- Phần OpenSea (JavaScript) sẽ được vẽ tiếp vào đây -->
</div>

<h1 class="text-3xl mb-6 text-center">🎼  NHẠC NFT</h1>

<!-- HIỂN THỊ THÔNG BÁO MUA BÁN CỦA BẠN -->
<div class="mb-4 text-center"><?= $thongbao ?></div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
<!-- Khu vực bao chung cho cả 2 nguồn -->
<div id="combined-marketplace" class="grid grid-cols-1 md:grid-cols-3 gap-6 p-5">    
 <!-- BƯỚC A: ĐỔ NFT TỪ SUPABASE (Bằng PHP) -->
    <?php if (!empty($all_data)): ?>
        <?php foreach ($all_data as $item): ?>
            <div class="card bg-gray-800 p-4 rounded-xl border border-gray-700 shadow-lg">
                <img src="<?= $item['image_url'] ?>" class="w-full h-40 object-cover rounded mb-4">
                <h3 class="text-center font-bold text-lg text-white"><?= $item['name'] ?></h3>
                <p class="text-center text-yellow-500 mb-4">💰 <?= $item['price'] ?> MATIC</p>
                
                <div class="grid grid-col gap-2">
                    <button onclick="playLocal('<?= $item['audio_url'] ?>', '<?= $item['name'] ?>')" class="bg-green-600 hover:bg-green-700 py-2 rounded text-white text-sm">▶ Nghe</button>
                    <button class="bg-blue-600 hover:bg-blue-700 py-2 rounded text-white text-sm grid items-center justify-center gap-2">🦊 Kết nối ví</button>
                    <button class="bg-orange-600 hover:bg-orange-700 py-2 rounded text-white text-sm">💰 Mua NFT</button>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- NƠI ĐỂ JAVASCRIPT ĐỔ NFT OPENSEA VÀO TIẾP THEO -->
    <div id="nft-list" class="contents"></div> 
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <?php if (!empty($all_data)): ?>
        <?php foreach ($all_data as $item): ?>
            <div class="card border p-4 rounded-lg bg-gray-800 text-white">
                
                <!-- Hiển thị Ảnh -->
                <?php if (!empty($item['image_url'])): ?>
                    <img src="<?= !empty($item['image_url']) ? $item['image_url'] : 'https://placeholder.com' ?>" class="w-full h-48 object-cover rounded">

                <?php else: ?>
                    <div class="h-48 bg-gray-700 grid items-center justify-center">No Image</div>
                <?php endif; ?>

                <!-- Hiển thị Tên và Giá -->
                <h3 class="mt-3 font-bold"><?= $item['name'] ?? 'No name' ?></h3>
                <p><?= $item['price'] ?? '0' ?> ETH</p>
                
                <!-- Nhãn nguồn để phân biệt -->
                <span class="text-xs bg-gray-600 px-2 py-1 rounded">Nguồn: <?= $item['source'] ?></span>

                <!-- Nút Nghe (Chỉ hiện nếu có link Audio) -->
                <?php if (!empty($item['audio_url'])): ?>
                    <button onclick="playLocal('<?= $item['audio_url'] ?>','<?= $item['name'] ?>')" 
                            class="bg-cyan-500 px-3 py-1 rounded mt-2 block w-full">
                        ▶ Nghe
                    </button>
                <?php endif; ?>

            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="col-span-3 text-center">Không có dữ liệu NFT nào được tìm thấy.</p>
    <?php endif; ?>
</div>

<h2 class="mt-10 text-2xl font-bold">NFT của bạn</h2>

<!-- PHẦN JS GỌI API ĐỂ IN RA NFT CỦA BẠN Ở ĐÂY -->
<div id="nft-list" class="grid gap-5 grid-wrap"></div>

<!-- PLAYER (GIỮ NGUYÊN HOÀN TOÀN CỦA BẠN) -->
<div class="fixed bottom-0 left-0 w-full bg-black p-3">
    <div id="now-playing">Chưa phát</div>
    <audio id="main-audio" controls class="w-full"></audio>
</div>

<script src="https://cdn.jsdelivr.net/npm/ethers@5.7.2/dist/ethers.min.js"></script>

<script>

let playlist = [];
let currentIndex = 0;

const mainAudio = document.getElementById("main-audio");
const nowPlaying = document.getElementById("now-playing");

// PLAY LOCAL
function playLocal(url, name) {
    mainAudio.src = url;
    mainAudio.play();
    nowPlaying.innerText = "🎵 " + name;
}

// NEXT
function nextTrack() {
    if (!playlist.length) return;
    currentIndex = (currentIndex + 1) % playlist.length;
    playMusic(currentIndex);
}

// PLAY NFT
function playMusic(index) {
    const nft = playlist[index];
    const audio = nft.animation_url || nft.metadata?.animation_url;
    mainAudio.src = audio;
    mainAudio.play();
    nowPlaying.innerText = nft.title || "NFT";
}

mainAudio.onended = nextTrack;

// 🔥 LOAD TOÀN BỘ NFT (FULL)
async function loadAllNFTs() {
    let pageKey = null;
    let allNFTs = [];

    do {
        let url = "api_nft.php";
        if (pageKey) url += "?pageKey=" + pageKey;

        const res = await fetch(url);
        const data = await res.json();

        if (data.ownedNfts) {
            allNFTs = allNFTs.concat(data.ownedNfts);
        }

        pageKey = data.pageKey || null;

    } while (pageKey);

    renderNFTs(allNFTs);
}

// RENDER NFT
function renderNFTs(nfts) {
    // Trỏ đúng vào Container chung để OpenSea hiện tiếp sau Supabase
    const container = document.getElementById("nft-display-container"); 
    // container.innerHTML = ""; // Giữ dòng này nếu muốn xóa các thông báo chờ

    nfts.forEach((nft, index) => {
        playlist.push(nft);

        // 1. XỬ LÝ ẢNH & FIX LỖI IPFS (Hết lỗi dấu X)
        let img = nft.media?.[0]?.gateway || nft.rawMetadata?.image || nft.metadata?.image || "";
        if (img.startsWith("ipfs://")) {
            img = img.replace("ipfs://", "https://ipfs.io");
        } else if (!img) {
            img = "https://placeholder.com";
        }

        let name = nft.title || nft.metadata?.name || "Unnamed NFT";

        // 2. TẠO THẺ CARD ĐỒNG BỘ VỚI PHẦN TRÊN
        const div = document.createElement("div");
        // Class này giúp chia cột đều và cao bằng nhau
        div.className = "card bg-gray-900 border border-gray-700 p-4 rounded-2xl shadow-xl flex flex-col justify-between h-[500px]";

        div.innerHTML = `
            <div>
                <img src="${img}" class="w-full h-48 object-cover rounded-xl mb-4" onerror="this.src='https://placeholder.com'">
                <h3 class="text-lg font-bold text-white truncate text-center">${name}</h3>
                <p class="text-yellow-500 font-bold text-center mt-1">💰 0.01 ETH</p>
                <p class="text-xs text-gray-500 text-center mt-1">Nguồn: OpenSea</p>
            </div>

            <div class="flex flex-col gap-2 mt-4">
                <button onclick="playMusic(${index})" 
                        class="bg-emerald-600 hover:bg-emerald-700 text-white py-2 rounded-lg font-medium transition-all">▶ Nghe NFT</button>
                <button class="bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-medium transition-all">🦊 Kết nối ví</button>
                <button class="bg-orange-600 hover:bg-orange-700 text-white py-2 rounded-lg font-medium transition-all">💰 Mua NFT</button>
            </div>
        `;

        container.appendChild(div);
    });
}


// GỌI HÀM
loadAllNFTs();
</script>

</body>
</html>

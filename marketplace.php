<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/finance_logic.php';

$user = $_SESSION['user'] ?? 'nhạc sĩ Mạnh Hùng';
$thongbao = "";
$all_data = [];

// --- A. GỌI DỮ LIỆU ĐỀ ĐỔ VÀO MẢNG CHUNG ---
$res_supabase = callSupabase("SELECT * FROM songs");

// Chuyển đổi dữ liệu nếu nó là chuỗi JSON
if (is_string($res_supabase)) {
    $nfts_supabase = json_decode($res_supabase, true);
} else {
    $nfts_supabase = $res_supabase;
}

$all_data = [];

// 1. ĐƯA DỮ LIỆU SUPABASE VÀO MẢNG CHUNG
if (!empty($nfts_supabase) && is_array($nfts_supabase)) {
    foreach ($nfts_supabase as $s) {
        if (is_array($s)) {
            $all_data[] = [
                'name'      => $s['name'] ?? 'Unnamed',
                'image_url' => $s['image_url'] ?? '',
                'price'     => $s['price'] ?? '0',
                'audio_url' => $s['audio_url'] ?? '',
                'source'    => 'Supabase'
            ];
        }
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

// MUA NFT (Bắt đầu đoạn code giữ nguyên xử lý mua bán của bạn...)


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

<h1 class="text-3xl mb-6 text-center">🎼  NHẠC NFT</h1>

<!-- HIỂN THỊ THÔNG BÁO MUA BÁN CỦA BẠN -->
<div class="mb-4 text-center"><?= $thongbao ?></div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <?php if (!empty($all_data)): ?>
        <?php foreach ($all_data as $item): ?>
            <div class="card border p-4 rounded-lg bg-gray-800 text-white">
                
                <!-- Hiển thị Ảnh -->
                <?php if (!empty($item['image_url'])): ?>
                    <img src="<?= $item['image_url'] ?>" class="w-full h-48 object-cover rounded">
                <?php else: ?>
                    <div class="h-48 bg-gray-700 flex items-center justify-center">No Image</div>
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
<div id="nft-list" class="flex gap-5 flex-wrap"></div>

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
    const container = document.getElementById("nft-list");
    container.innerHTML = "";
    playlist = []; // reset playlist

    nfts.forEach((nft, index) => {
        playlist.push(nft);
        let img = nft.media?.[0]?.gateway || nft.metadata?.image;
        let name = nft.title || nft.metadata?.name || "NFT";

        const div = document.createElement("div");
        div.style.width = "200px";

        div.innerHTML = `
            <img src="${img}" style="width:100%;height:200px;object-fit:cover">
            <p>${name}</p>
        `;

        const playBtn = document.createElement("button");
        playBtn.innerText = "▶ Nghe NFT";
        playBtn.onclick = () => playMusic(index);

        div.appendChild(playBtn);
        container.appendChild(div);
    });

    console.log("✅ Tổng NFT:", nfts.length);
}

// GỌI HÀM
loadAllNFTs();
</script>

</body>
</html>

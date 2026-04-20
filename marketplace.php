<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/finance_logic.php';

$user = $_SESSION['user'] ?? 'nhạc sĩ Mạnh Hùng';
$thongbao = "";

// MUA NFT
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

// LẤY NHẠC
$result = callSupabase("SELECT * FROM songs");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>CHỢ NHẠC NFT</title>

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

<h1 class="text-3xl mb-6 text-center">🎼 CHỢ NHẠC NFT</h1>

<?= $thongbao ?>

<!-- DANH SÁCH NHẠC -->
<div class="grid grid-cols-4 gap-6">

<?php if (!empty($result)): ?>
<?php foreach ($result as $s): ?>

<div class="card">

<?php if (!empty($s['image_url'])): ?>
<img src="<?= $s['image_url'] ?>" class="w-full h-48 object-cover rounded">
<?php else: ?>
<div class="h-48 bg-gray-700 flex items-center justify-center">No Image</div>
<?php endif; ?>

<h3 class="mt-3 font-bold"><?= $s['name'] ?? 'No name' ?></h3>
<p><?= $s['price'] ?? '0' ?> ETH</p>

<button onclick="playLocal('<?= $s['audio_url'] ?>','<?= $s['name'] ?>')" 
class="bg-cyan-500 px-3 py-1 rounded mt-2">
▶ Nghe
</button>

</div>

<?php endforeach; ?>
<?php else: ?>
<p>Không có dữ liệu</p>
<?php endif; ?>

</div>

<!-- NFT -->
<h2 class="mt-10 text-2xl">NFT của bạn</h2>
<div id="nft-list" class="flex gap-5 flex-wrap"></div>

<!-- PLAYER -->
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
    if (!nft) return;

    const audio = nft.animation_url || nft.metadata?.animation_url;

    if (!audio) {
        alert("NFT không có nhạc");
        return;
    }

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

        let img =
            nft.media?.[0]?.gateway ||
            nft.metadata?.image;

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

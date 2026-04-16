<?php
session_start();
require 'db.php';
require 'finance_logic.php';

if (!isset($_SESSION['user'])) { header("Location: dangnhap.php"); exit(); }

$user = $_SESSION['user'];
$thongbao = "";

// XỬ LÝ KHI NHẤN NÚT MUA
if (isset($_POST['buy_nft'])) {
    $song_id = $_POST['song_id'];
    $song_title = $_POST['song_title'];
    $price = $_POST['price'];
    
    // 1. Kiểm tra số dư người mua
    $balance = getBalance($conn, $user);
    
    if ($balance >= $price) {
        // 2. Tạo khối giao dịch (Blockchain)
        $prevHash = getLatestHash($conn);
        $res_id = mysqli_query($conn, "SELECT id FROM blockchain ORDER BY id DESC LIMIT 1");
        $next_id = (mysqli_fetch_assoc($res_id)['id'] ?? 0) + 1;
        
        // Nội dung khối lưu tên bài hát để xác nhận quyền sở hữu
        $hash = calculateFinanceHash($next_id, $prevHash, $user, 'Hệ thống Music NFT', $price);
        
        $sql = "INSERT INTO blockchain (sender, receiver, amount, content, prev_hash, hash) 
                VALUES ('$user', 'Nhạc sĩ Mạnh Hùng', '$price', '$song_title', '$prevHash', '$hash')";
        
        if (mysqli_query($conn, $sql)) {
            $thongbao = "<div style='color:lime; background:#1b331b; padding:10px;'>✔️ Chúc mừng! Bạn đã sở hữu tác phẩm: $song_title</div>";
        }
    } else {
        $thongbao = "<div style='color:red; background:#331b1b; padding:10px;'>❌ Số dư PHP Coin không đủ để mua tác phẩm này!</div>";
    }
}

// Lấy danh sách nhạc từ kho
$songs = mysqli_query($conn, "SELECT * FROM music_collection");
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="assets/style.css">
    <title>Chợ Nhạc NFT Mạnh Hùng</title>

    <style>
        body { background: #0d1117; color: #c9d1d9; font-family: 'Segoe UI', sans-serif; margin: 0; }
        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; padding: 20px; }
        .card { background: #161b22; border: 1px solid #30363d; border-radius: 10px; padding: 15px; text-align: center; }
        .price { color: #f2e711; font-size: 18px; }
        button { padding:8px; border:none; border-radius:5px; cursor:pointer; margin-top:5px; }
    </style>
</head>

<body>

<?php include 'navbar.php'; ?>

<div style="text-align:center;padding:20px;">
    <h1>🎼 CHỢ NHẠC NFT</h1>

    <button onclick="connectWallet()" style="background:#f6851b;color:#fff;">
        🦊 Kết nối MetaMask
    </button>

    <p id="wallet"></p>
</div>

<!-- NHẠC PHP -->
<div class="grid">
<?php while($s = mysqli_fetch_assoc($songs)): ?>
<div class="card">

<?php if (!empty($s['image_url'])): ?>
<img src="<?php echo $s['image_url']; ?>" style="width:100%;height:200px;object-fit:cover;border-radius:10px;">
<?php else: ?>
<div style="height:200px;background:#333;display:flex;align-items:center;justify-content:center;">No Image</div>
<?php endif; ?>

<h3><?php echo $s['name']; ?></h3> <!-- Dùng 'name' thay vì 'title' -->
<p class="price"><?php echo number_format($s['price']); ?> PHP</p>
<img src="<?php echo $s['image_url']; ?>">
<button onclick="playLocal('<?php echo $s['audio_url']; ?>','<?php echo $s['title']; ?>')">
▶ Nghe thử
</button>

</div>
<?php endwhile; ?>
</div>

<!-- NFT -->
<div id="nft-list" style="display:flex;flex-wrap:wrap;gap:20px;padding:20px;"></div>

<!-- PLAYER -->
<div style="position:fixed;bottom:0;width:100%;background:#111;padding:10px;color:#fff;">
    <div id="now-playing">Chưa phát</div>
    <audio id="main-audio" controls style="width:100%"></audio>

    <div>
        <button onclick="prevTrack()">⏮</button>
        <button onclick="nextTrack()">⏭</button>
        <button onclick="toggleShuffle()">🔀</button>
    </div>
</div>

<!-- LIB -->
<script src="https://cdn.jsdelivr.net/npm/ethers@5.7.2/dist/ethers.min.js"></script>

<script>

let playlist = [];
let currentIndex = 0;
let isShuffle = false;
let userAddress = "";

const mainAudio = document.getElementById("main-audio");
const nowPlaying = document.getElementById("now-playing");

const contractAddress = "0x254B57b096a308c97A90da781D0E4cd74a733f4D";

const abi = [
    {
        "name":"buyMusic",
        "type":"function",
        "inputs":[{"name":"tokenId","type":"uint256"}],
        "stateMutability":"payable"
    },
    {
        "name":"prices",
        "type":"function",
        "inputs":[{"name":"","type":"uint256"}],
        "outputs":[{"type":"uint256"}],
        "stateMutability":"view"
    }
];

// 🦊 CONNECT WALLET
async function connectWallet() {
    if (!window.ethereum) return alert("Cài MetaMask!");
    const accounts = await window.ethereum.request({ method: "eth_requestAccounts" });
    userAddress = accounts[0];
    document.getElementById("wallet").innerText = "Đã kết nối: " + userAddress;
}

// 🎵 FIX IPFS
function fixIPFS(url) {
    if (!url) return null;
    if (url.startsWith("ipfs://")) {
        return url.replace("ipfs://", "https://ipfs.io/ipfs/");
    }
    return url;
}

// 🎧 PLAY LOCAL
function playLocal(url, name) {
    mainAudio.src = url;
    mainAudio.play();
    nowPlaying.innerText = "🎵 " + name;
}

// 🎧 PLAY NFT
function playMusic(index) {

    if (!playlist[index]) return;

    currentIndex = index;
    const nft = playlist[index];

    const audioUrl = fixIPFS(
        nft.animation_url ||
        nft.media?.[0]?.gateway ||
        nft.metadata?.animation_url
    );

    if (!audioUrl) return alert("NFT không có audio");

    mainAudio.src = audioUrl;
    mainAudio.play();

    nowPlaying.innerText = "🎵 " + (nft.title || nft.metadata?.name);
}

// ⏭ NEXT / PREV
function nextTrack() {
    currentIndex = isShuffle
        ? Math.floor(Math.random() * playlist.length)
        : (currentIndex + 1) % playlist.length;
    playMusic(currentIndex);
}

function prevTrack() {
    currentIndex = (currentIndex - 1 + playlist.length) % playlist.length;
    playMusic(currentIndex);
}

function toggleShuffle() {
    isShuffle = !isShuffle;
    alert("Shuffle: " + (isShuffle ? "ON" : "OFF"));
}

mainAudio.onended = () => nextTrack();

// 💰 BUY NFT
async function buyNFT(nft) {

    if (!window.ethereum) return alert("Cần MetaMask");

    const provider = new ethers.providers.Web3Provider(window.ethereum);
    const signer = provider.getSigner();

    const contract = new ethers.Contract(contractAddress, abi, signer);

    const tokenId = parseInt(nft.id.tokenId, 16);
    const price = await contract.prices(tokenId);

    await contract.buyMusic(tokenId, { value: price });

    alert("Mua thành công!");
}

// 🔥 LOAD NFT
fetch("/api_nft.php", {
    headers: { "ngrok-skip-browser-warning": "true" }
})
.then(res => res.json())
.then(data => {

    const container = document.getElementById("nft-list");

    data.ownedNfts.forEach((nft, index) => {

        playlist.push(nft);

        let img =
            nft.media?.[0]?.gateway ||
            nft.media?.[0]?.thumbnail ||
            fixIPFS(nft.metadata?.image);

        if (!img) return;

        const name = nft.title || nft.metadata?.name || "NFT";

        const div = document.createElement("div");
        div.style.width = "200px";

        div.innerHTML = `
            <img src="${img}" style="width:100%;height:200px;object-fit:cover">
            <p>${name}</p>
        `;

        // ▶ PLAY
        const playBtn = document.createElement("button");
        playBtn.innerText = "▶ Nghe NFT";
        playBtn.onclick = () => playMusic(index);

        // 💰 BUY
        const buyBtn = document.createElement("button");
        buyBtn.innerText = "🛒 Mua NFT";
        buyBtn.style.background = "#2081e2";
        buyBtn.style.color = "#fff";
        buyBtn.onclick = () => buyNFT(nft);

        div.appendChild(playBtn);
        div.appendChild(buyBtn);

        container.appendChild(div);
    });

});

</script>

</body>
</html>


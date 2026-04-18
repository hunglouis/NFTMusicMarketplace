<?php
// ====== CONFIG ======
function callSupabase($endpoint) {
    $url = "https://hmvvjjiiaelcsfqgxbxv.supabase.co/rest/v1/" . $endpoint;

    $headers = [
        "apikey: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw",
        "Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw"
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $res = curl_exec($ch);
    curl_close($ch);

    return json_decode($res, true);
}

// ====== LẤY DATA ======
$songs = callSupabase("hunglouis?price=gt.0&order=id.desc&limit=200");
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Marketplace NFT</title>

<style>
body {
    background:#0d1117;
    color:#fff;
    font-family:sans-serif;
}

.grid {
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
    gap:20px;
    padding:20px;
}

.card {
    background:#161b22;
    padding:15px;
    border-radius:10px;
    text-align:center;
}

.card img {
    width:100%;
    height:180px;
    object-fit:cover;
    border-radius:10px;
}

button {
    width:100%;
    margin-top:5px;
    padding:8px;
    border:none;
    border-radius:5px;
    cursor:pointer;
}

.play { background:#238636; color:#fff; }
.buy { background:#f6851b; color:#000; }
.wallet { background:#2081e2; color:#fff; }

#player {
    position:fixed;
    bottom:0;
    width:100%;
    background:#111;
    padding:10px;
}
</style>
</head>

<body>
<div class="menu">

    <div class="menu-left">
        <a href="index.php">🏠 Trang chủ</a>
        <a href="marketplace.php">🎼 Marketplace</a>
        <a href="marketplace_supabase.php">🚀 Supabase</a>
        <a href="player.php">🎧 Player</a>
    </div>

    <div class="menu-right">

        <button class="btn-wallet" onclick="connectWallet()">🦊 Kết nối ví</button>

        <div class="user-box" onclick="toggleDropdown()">
            <div class="avatar">👤</div>
            <div id="user-address">Chưa kết nối</div>

            <div id="dropdown" class="dropdown">
                <p onclick="copyAddress()">📋 Copy ví</p>
                <p onclick="logout()">🚪 Đăng xuất</p>
            </div>
        </div>

    </div>

</div>
 
<div class="menu">
    <a href="index.php">🏠 Trang chủ</a>
    <a href="marketplace.php">🎼 Marketplace</a>
    <a href="marketplace_supabase.php">🚀 Supabase</a>
    <a href="player.php">🎧 Player</a>
</div>

<h2 style="text-align:center">🎼 NFT MUSIC MARKET</h2>

<div class="grid">
<h2 style="text-align:center">🌐 NFT từ OpenSea</h2>
<div id="opensea-list" class="grid"></div>

<?php foreach($songs as $s): ?>
<div class="card">

    <img src="<?php echo $s['image'] ?? 'https://via.placeholder.com/200'; ?>">

    <h3><?php echo $s['title'] ?? 'No name'; ?></h3>

    <p>💰 <?php echo $s['price'] ?? 0; ?> MATIC</p>

    <button class="play"
        onclick="playMusic('<?php echo $s['audio'] ?? ''; ?>','<?php echo $s['title'] ?? ''; ?>')">
        ▶ Nghe
    </button>

    <button class="wallet" onclick="connectWallet()">🦊 Kết nối ví</button>

    <button class="buy"
        onclick="buyNFT('<?php echo $s['id'] ?? 0; ?>')">
        💰 Mua NFT
    </button>

</div>
<?php endforeach; ?>
</div>

<!-- PLAYER -->
<div id="player">
    <div id="now">Chưa phát</div>
    <audio id="audio" controls style="width:100%"></audio>
</div>

<script src="https://cdn.jsdelivr.net/npm/ethers@5.7.2/dist/ethers.min.js"></script>

<script>
// ===== PLAYER =====
function playMusic(url, title) {
    const audio = document.getElementById("audio");
    audio.src = url;
    audio.play();
    document.getElementById("now").innerText = "🎵 " + title;
}

// ===== WALLET =====
async function connectWallet() {
    if (window.ethereum) {
        const acc = await window.ethereum.request({method:'eth_requestAccounts'});
        alert("Đã kết nối: " + acc[0]);
    } else {
        alert("Cài MetaMask!");
    }
}

// ===== BUY (DEMO) =====
function buyNFT(id) {
    alert("Mua NFT ID: " + id);
}
</script>
<script>
async function loadAllNFT() {

    let allNFTs = [];
    let pageKey = "";

    while (true) {

       // let url = "https://unaddressed-yaretzi-nonneural.ngrok-free.dev/web_cua_toi/api_nft.php";

        if (pageKey) {
            url += "?pageKey=" + pageKey;
        }

        const res = await fetch(url, {
            headers: { "ngrok-skip-browser-warning": "true" }
        });

        const data = await res.json();

        if (!data.ownedNfts) break;

        // 🔥 gom NFT
        allNFTs = allNFTs.concat(data.ownedNfts);

        // 🔁 nếu còn trang
        if (data.pageKey) {
            pageKey = data.pageKey;
        } else {
            break;
        }
    }

    console.log("Tổng NFT:", allNFTs.length);

    renderNFT(allNFTs);
}

// 🎨 HIỂN THỊ
function renderNFT(nfts) {

    const container = document.getElementById("opensea-list");
    container.innerHTML = "";

    nfts.forEach(nft => {

        let img = "";
        let audio = "";
        let name = nft.title || nft.metadata?.name || "No name";

        // 🖼 ảnh
        if (nft.media?.[0]?.gateway) {
            img = nft.media[0].gateway;
        } else if (nft.metadata?.image) {
            img = nft.metadata.image.replace("ipfs://","https://ipfs.io/ipfs/");
        }

        // 🎧 audio
        if (nft.metadata?.animation_url) {
            audio = nft.metadata.animation_url.replace("ipfs://","https://ipfs.io/ipfs/");
        }

        // ❗ KHÔNG BỎ NFT NỮA
        if (!img) img = "https://via.placeholder.com/200";

        const div = document.createElement("div");
        div.className = "card";

        div.innerHTML = `
            <img src="${img}">
            <h3>${name}</h3>
            <button>▶ Nghe</button>
        `;

        div.onclick = () => {
            if (!audio) {
                alert("Không có audio");
                return;
            }
            playMusic(audio, name);
        };

        container.appendChild(div);
    });
}

// 🚀 CHẠY
loadAllNFT();
</script>

<script>
let userAddress = "";

// 🔗 KẾT NỐI VÍ
async function connectWallet() {
    if (!window.ethereum) {
        alert("Cài MetaMask!");
        return;
    }

    const acc = await window.ethereum.request({
        method: 'eth_requestAccounts'
    });

    userAddress = acc[0];

    document.getElementById("user-address").innerText =
        userAddress.slice(0,6) + "..." + userAddress.slice(-4);
}

// 📂 DROPDOWN
function toggleDropdown() {
    const d = document.getElementById("dropdown");
    d.style.display = (d.style.display === "block") ? "none" : "block";
}

// 📋 COPY VÍ
function copyAddress() {
    navigator.clipboard.writeText(userAddress);
    alert("Đã copy ví");
}

// 🚪 LOGOUT
function logout() {
    userAddress = "";
    document.getElementById("user-address").innerText = "Chưa kết nối";
}
</script>

</body>
</html>

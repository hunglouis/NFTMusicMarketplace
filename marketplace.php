<?php
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/finance_logic.php';
$user = $_SESSION['user'] ?? 'nhạc sĩ Mạnh Hùng';
//set_time_limit(); // Script chỉ được chạy tối đa 5 giây, sau đó tự ngắt.


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
        $res_id = callsupabase($conn, "SELECT id FROM blockchain ORDER BY id DESC LIMIT 1");
        $next_id = (($res_id)['id'] ?? 0) + 1;
        
        // Nội dung khối lưu tên bài hát để xác nhận quyền sở hữu
        $hash = calculateFinanceHash($next_id, $prevHash, $user, 'Hệ thống Music NFT', $price);
        
        $sql = "INSERT INTO blockchain (sender, receiver, amount, content, prev_hash, hash) 
                VALUES ('$user', 'Nhạc sĩ Mạnh Hùng', '$price', '$song_title', '$prevHash', '$hash')";
        
        if (callsupabase($conn, $sql)) {
            $thongbao = "<div style='color:lime; background:#1b331b; padding:10px;'>✔️ Chúc mừng! Bạn đã sở hữu tác phẩm: $song_title</div>";
        }
    } else {
        $thongbao = "<div style='color:red; background:#331b1b; padding:10px;'>❌ Số dư PHP Coin không đủ để mua tác phẩm này!</div>";
    }
}

// Lấy danh sách nhạc từ kho
$songs = callSupabase("hunglouis");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quỳnh Hương - Genesis Edition</title>
    <!-- Link làm đẹp giao diện -->
    <script src="https://tailwindcss.com"></script>
    <link href="https://cloudflare.com" rel="stylesheet">
    <style>
        body { background: radial-gradient(circle at top right, #0891b2, #064e3b, #020617); min-height: 100vh; color: white; }
        .glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(6, 182, 212, 0.2); }
    </style>
</head>
<body class="p-10">
    <!-- Toàn bộ phần vòng lặp foreach của bạn nằm ở đây -->


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
<?php foreach ($songs as $s): ?>
<div class="card">

<?php if (!empty($s['image_url'])): ?>
<img src="<?php echo $s['image_url']; ?>" style="width:100%;height:200px;object-fit:cover;border-radius:10px;">
<?php else: ?>
<div style="height:200px;background:#333;display:flex;align-items:center;justify-content:center;">No Image</div>
<?php endif; ?>

<h3><?php echo $s['title'] ?? 'Chưa đặt tên'; ?></h3>
<p><?php echo $s['price'] ?? '0.00'; ?> ETH</p>
<img src="<?php echo $s['image_url']; ?>">
<button onclick="playLocal('<?php echo $s['audio_url']; ?>','<?php echo $s['name']; ?>')">
▶ Nghe thử
</button>

</div>
<?php endforeach; ?>
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
    
console.log('audio_url=', url);  
console.log('name=', name);
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
<script>
// Hàm này sẽ tự động chạy khi trang web tải xong
document.addEventListener('DOMContentLoaded', function() {
    const songContainer = document.querySelector('#song-grid');
		if (!songContainer) return	// Nơi chứa hoa Quỳnh
    const storageUrl = "https://hmvvjjiiaelcsfqgxbxv.supabase.co/storage/v1/object/public/hunglouis/"; // URL gốc của Supabase Storage

    // Gọi đến file API Backend
    fetch('api/marketplace.php')
        .then(response => response.json())
        .then(data => {
            if (Array.isArray(data)) {
                songContainer.innerHTML = ''; // Xóa thông báo "Đang tải"
                
                data.forEach(s => {
                    // Tạo giao diện cho từng bông hoa
                    const card = `
                        <div class="glass p-5 rounded-[2rem] hover:scale-105 transition duration-500">
                            <img src="${storageUrl + s.image_path}" class="w-full h-56 object-cover rounded-2xl mb-4">
                            <h3 class="font-bold text-lg">${s.name || 'Hoa Quỳnh'}</h3>
                            <audio controls class="w-full h-8 mt-4 brightness-110">
                                <source src="${storageUrl + s.audio_path}" type="audio/mpeg">
                            </audio>
                        </div>
                    `;
					 if (!data || data.length === 0) {
                    songContainer.innerHTML = 'HẾT NHẠC RỒI!';
					 return;					 			 
                });
            }
        })
		let htmlContent = ''; // Dùng biến tạm để cộng dồn chuỗi, tránh render liên tục
            data.forEach(s => {
                htmlContent += `
                    <div class="glass p-5 rounded-[2rem]">
                        <img src="${storageUrl + s.image_path}" class="w-full h-56 object-cover">
                        <h3>${s.name}</h3>
                        <audio src="${storageUrl + s.audio_path}" controls></audio>
                    </div>`;
            });
            songContainer.innerHTML = htmlContent; // Chỉ cập nhật DOM đúng 1 lần duy nhất
        })
        .catch(error => {
            console.error('Lỗi lấy dữ liệu:', error);
            songContainer.innerHTML = '<p class="text-red-400">Không thể kết nối với API Backend.</p>';
        });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const grid = document.querySelector('#song-grid');

    // Gọi đến file xử lý dữ liệu ở Backend
    fetch('api/marketplace.php')
        .then(res => res.json())
        .then(data => {
            // Alchemy trả về mảng nằm trong 'ownedNfts'
            const nfts = data.ownedNfts || data; 
            
            if (Array.isArray(nfts)) {
                grid.innerHTML = ''; // Xóa chữ "Đang tải"

                nfts.forEach(item => {
                    // Lấy link ảnh từ đúng vị trí trong file JSON của bạn
                    const imageUrl = item.media[0]?.gateway || item.metadata?.image || 'default.jpg';
                    const title = item.title || "Quỳnh Hương NFT";
                    
                    grid.innerHTML += `
                        <div class="glass p-5 rounded-[2.5rem] hover:scale-105 transition duration-500 shadow-2xl">
                            <div class="relative overflow-hidden rounded-3xl mb-4">
                                <img src="${imageUrl}" class="w-full h-64 object-cover" alt="NFT">
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">${title}</h3>
                            <p class="text-cyan-400 text-xs mb-4">Số lượng: ${item.balance} bản</p>
                            <a href="https://opensea.io{item.contract.address}/${item.id.tokenId}" 
                               target="_blank" 
                               class="block text-center bg-cyan-500 hover:bg-cyan-400 py-3 rounded-2xl font-bold transition">
                                XEM TRÊN OPENSEA
                            </a>
                        </div>
                    `;
                });
            }
        })
        .catch(err => {
            console.error("Lỗi:", err);
            grid.innerHTML = '<p class="text-center col-span-full">Không thể kết nối dữ liệu NFT.</p>';
        });
});
</script>

</body>
</html>


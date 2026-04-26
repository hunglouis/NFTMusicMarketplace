<?php
session_start();
// Chỉ cho phép truy cập nếu đã kết nối ví hoặc đăng nhập (sau này sẽ nâng cấp)
$user = $_SESSION['user'] ?? 'Nghệ sĩ Mạnh Hùng';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>STUDIO CHẾ TÁC NFT - DI SẢN MẠNH HÙNG</title>
    <script src="https://tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cloudflare.com">
    <style>
        body { 
    background: radial-gradient(circle at top left, #1e1b4b, #020617); 
    color: #d4d4d8; 
    /* Chỉnh lại để cân bằng 2 bên */
    padding-left: 5rem; 
    padding-right: 2rem; 
    min-height: 100vh;
}

        /* Màu vàng đồng đặc trưng */
        .gold-text { color: #eab308; }
        .gold-border { border-color: rgba(234, 179, 8, 0.3); }
        .gold-gradient { background: linear-gradient(to right, #eab308, #ca8a04); }
        .glass-gold { 
            background: rgba(255, 255, 255, 0.02); 
            backdrop-filter: blur(10px); 
            border: 1px solid rgba(234, 179, 8, 0.1); 
        }
    </style>
</head>
<body class="p-8 md:p-16">
    <?php include 'navbar.php'; ?>

    <div class="max-w-4xl mx-auto">
        <!-- TIÊU ĐỀ STUDIO -->
        <header class="text-center mb-16">
            <h1 class="text-5xl font-black gold-text tracking-tighter uppercase mb-4">
                Xưởng Chế Tác NFT
            </h1>
            <p class="text-gray-500 text-xs uppercase tracking-[0.5em]">Nơi số hóa di sản và tinh hoa nghệ thuật</p>
        </header>

        <div class="grid grid-cols-1 gap-12">
            
            <!-- PHẦN 1: TUYÊN NGÔN DI SẢN (BẢN VUN BỒI) -->
<section class="glass-gold p-8 rounded-[40px] shadow-2xl relative overflow-hidden mb-12">
    <div class="absolute top-0 right-0 p-6 opacity-5">
        <i class="fa-solid fa-scroll text-8xl gold-text"></i>
    </div>

    <h3 class="gold-text text-xs font-black uppercase tracking-[0.3em] mb-8 flex items-center gap-2">
        <i class="fa-solid fa-scale-balanced"></i> I. Tuyên Ngôn & Giao Ước Di Sản
    </h3>

    <div class="space-y-6 italic font-serif text-gray-200 text-sm leading-relaxed border-l-2 border-yellow-500/30 pl-6">
        <p>"Chúng tôi tin rằng mọi tạo tác và sản vật, khi được gửi gắm bằng cả tâm tình, đều mang trong mình giá trị di sản. Không chỉ có âm nhạc hay hội họa, mà từng kỷ vật, từng ký ức cá nhân đều xứng đáng được bảo tồn vĩnh cửu."</p>
        
        <p>"Bằng việc khởi sinh NFT này, tôi - chủ sở hữu hợp pháp <span id="artist-wallet-detail" class="text-yellow-500 font-mono">[Đang chờ ví...]</span> - chính thức xác lập vị thế di sản cho sản vật của mình. Tôi cam kết thực thi quyền sở hữu với đạo đức sáng tạo cao nhất, bảo đảm tính minh bạch để kết nối quá khứ, hiện tại và những thế hệ mai sau qua tiêu chuẩn cộng đồng ERC-721 và ERC-2981."</p>
    </div>

    <div class="mt-10 flex items-center gap-4 bg-white/5 p-4 rounded-2xl border border-white/5">
        <input type="checkbox" id="agreement" class="w-5 h-5 accent-yellow-600 cursor-pointer">
        <label for="agreement" class="text-[11px] text-gray-400 font-bold uppercase tracking-wider cursor-pointer">
            Tôi xác nhận đã thấu hiểu và cam kết thực hiện đúng giao ước.
        </label>
    </div>
</section>


                        <!-- II. THÔNG TIN TÁC PHẨM - BẢN HIỆN RÕ CỬA NẠP -->
<section class="glass-gold p-8 rounded-[40px] mt-10 space-y-10 border border-yellow-500/10">
    <h3 class="gold-text text-sm font-black uppercase tracking-widest flex items-center gap-2">
        <i class="fa-solid fa-wand-magic-sparkles"></i> II. Thông Tin Tác Phẩm
    </h3>
    
    <!-- 1. CỬA NẠP SẢN VẬT (TO VÀ RÕ) -->
    <div onclick="document.getElementById('file-input').click()" 
         style="cursor: pointer; border: 2px dashed rgba(234, 179, 8, 0.2); background: rgba(255,255,255,0.02);"
         class="w-full h-40 rounded-[30px] flex flex-col items-center justify-center group hover:bg-yellow-500/5 transition-all">
        
        <i class="fa-solid fa-cloud-arrow-up text-3xl text-gray-600 group-hover:text-yellow-500 mb-3"></i>
        <p id="file-status" class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] group-hover:text-white">
            Nhấn vào đây để chọn Sản vật (MV/Ảnh/Hồ sơ)
        </p>
        <!-- Thẻ chọn file ẩn -->
        <input type="file" id="file-input" class="hidden" onchange="handleFileSelect(this)">
    </div>

    <!-- 2. KHUNG NHẬP LIỆU (3 CỘT NGANG) -->
    <div style="display: flex; gap: 20px; align-items: flex-start;">
        <div style="flex: 2;">
            <label style="display: block; font-size: 9px; color: #71717a; font-weight: 900; text-transform: uppercase; margin-bottom: 8px;">Tên Tác Phẩm</label>
            <input type="text" id="nft-name" placeholder="Cánh Hoa Phiêu Bồng (1994)" 
                   style="width: 100%; background: rgba(255,255,255,0.03); border: 1px solid rgba(234, 179, 8, 0.2); padding: 14px; border-radius: 12px; color: white; outline: none; box-sizing: border-box;">
        </div>
        <div style="flex: 1;">
            <label style="display: block; font-size: 9px; color: #71717a; font-weight: 900; text-transform: uppercase; margin-bottom: 8px;">Giá (MATIC)</label>
            <input type="number" id="nft-price" placeholder="0.1" 
                   style="width: 100%; background: rgba(255,255,255,0.03); border: 1px solid rgba(234, 179, 8, 0.2); padding: 14px; border-radius: 12px; color: white; outline: none; box-sizing: border-box;">
        </div>
        <div style="flex: 1;">
            <label style="display: block; font-size: 9px; color: #71717a; font-weight: 900; text-transform: uppercase; margin-bottom: 8px;">Tác quyền %</label>
            <input type="number" id="nft-royalty" value="10" 
                   style="width: 100%; background: rgba(255,255,255,0.03); border: 1px solid rgba(234, 179, 8, 0.2); padding: 14px; border-radius: 12px; color: #eab308; font-weight: bold; outline: none; box-sizing: border-box;">
        </div>
    </div>

    <!-- 3. THƯ NGỎ -->
    <div style="width: 100%;">
        <label style="display: block; font-size: 9px; color: #71717a; font-weight: 900; text-transform: uppercase; margin-bottom: 8px;">Thư Ngỏ / Mô Tả Di Sản</label>
        <textarea id="nft-desc" rows="4" placeholder="Kể câu chuyện về hoàn cảnh ra đời..." 
                  style="width: 100%; background: rgba(255,255,255,0.03); border: 1px solid rgba(234, 179, 8, 0.2); padding: 15px; border-radius: 12px; color: white; outline: none; resize: none; box-sizing: border-box;"></textarea>
    </div>

    <!-- NÚT MINT SẼ ĐƯỢC ĐIỀU KHIỂN BỞI JAVASCRIPT -->
    <button id="mint-button" disabled 
            style="width: 100%; padding: 20px; border-radius: 15px; background: #18181b; color: #3f3f46; font-weight: 900; text-transform: uppercase; letter-spacing: 4px; border: 1px solid rgba(255,255,255,0.05); cursor: not-allowed; transition: all 0.5s;">
        Khởi sinh Di Sản (Mint NFT)
    </button>
</section>

        </div>
        <footer class="mt-20 text-center text-[10px] text-gray-600 uppercase tracking-widest">
            © Music NFT Studio - Bảo tồn quá khứ, kết nối tương lai
        </footer>
    </div>

<script>
    // 1. Chìa khóa kho di sản
    const PINATA_JWT = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VySW5mb3JtYXRpb24iOnsiaWQiOiJkYmU0YWZlMi04ZTNkLTQzODItYmI4MC03NmEyNjYxZjUwNDciLCJlbWFpbCI6Imh1bmdsb3Vpcy5tYW5oaHVuZ0BnbWFpbC5jb20iLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwicGluX3BvbGljeSI6eyJyZWdpb25zIjpbeyJkZXNpcmVkUmVwbGljYXRpb25Db3VudCI6MSwiaWQiOiJGUkExIn0seyJkZXNpcmVkUmVwbGljYXRpb25Db3VudCI6MSwiaWQiOiJOWUMxIn1dLCJ2ZXJzaW9uIjoxfSwibWZhX2VuYWJsZWQiOmZhbHNlLCJzdGF0dXMiOiJBQ1RJVkUifSwiYXV0aGVudGljYXRpb25UeXBlIjoic2NvcGVkS2V5Iiwic2NvcGVkS2V5S2V5IjoiYmM2MGZmMjQzNzYyMWYxODY3YzgiLCJzY29wZWRLZXlTZWNyZXQiOiJmN2Y0NDc2MTk0ZmI3ZmVhZTRkOGFmYzlkNTIzMGI5NDU3MjZkNWMwNDQ3ODFmOGYzZThiYzA3NTZiMGNmN2YzIiwiZXhwIjoxODA1OTUxMjIyfQ.KoZ-lqftq5bv-GDyjvoyHVvcf5h52K9RKYCIv6pBUGI";

    // 2. Tự động hiện địa chỉ ví vào Bản Giao Ước
    async function showWallet() {
        if (window.ethereum) {
            const accounts = await window.ethereum.request({ method: 'eth_accounts' });
            if (accounts.length > 0) {
                document.getElementById('artist-wallet-detail').innerText = accounts[0];
            }
        }
    }
    window.addEventListener('load', showWallet);

    // 3. Logic làm rực sáng nút Vàng
    const agree = document.getElementById('agreement');
    const btn = document.getElementById('mint-button');

    agree.addEventListener('change', function() {
        if (this.checked) {
            btn.disabled = false;
            btn.style.background = 'linear-gradient(to right, #eab308, #ca8a04)';
            btn.style.color = '#000';
            btn.style.boxShadow = '0 0 30px rgba(234, 179, 8, 0.4)';
            btn.innerText = "KHỞI SINH DI SẢN NGAY";
        } else {
            btn.disabled = true;
            btn.style.background = '#18181b';
            btn.style.color = '#3f3f46';
            btn.style.boxShadow = 'none';
            btn.innerText = "KHỞI SINH DI SẢN (MINT NFT)";
        }
    });

    // 4. Lệnh thực thi: Đẩy Pinata -> Lưu Supabase
    btn.onclick = async () => {
        const fileInput = document.getElementById('file-input');
        if (!fileInput.files[0]) return alert("Nghệ sĩ vui lòng chọn Sản vật!");

        btn.innerText = "⏳ ĐANG ĐƯA DI SẢN LÊN KHO...";
        btn.disabled = true;

        const formData = new FormData();
        formData.append('file', fileInput.files[0]);

        try {
            // A. Đẩy lên Pinata
            const res = await fetch("https://api.pinata.cloud/pinning/pinFileToIPFS", {
                method: 'POST',
                headers: { 'Authorization': 'Bearer ' + PINATA_JWT },
                body: formData
            });
            const data = await res.json();
            
            if (data.IpfsHash) {
                const cid = data.IpfsHash;
                btn.innerText = "✅ ĐÃ LƯU KHO - ĐANG NIÊM YẾT...";

                // B. Lưu sang Supabase
                const nftData = {
                    name: document.getElementById('nft-name').value,
                    price: document.getElementById('nft-price').value,
                    image_url: "ipfs://" + cid,
                    description: document.getElementById('nft-desc').value
                };

                const resSupa = await fetch('save_to_supabase.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(nftData)
                });
                const resSupaData = await resSupa.json();

                if (resSupaData.success) {
                    alert("✨ TUYỆT VỜI! Di sản đã lên kệ hàng Marketplace.");
                    window.location.href = "marketplace_supabase.php";
                }
            }
        } catch (e) {
            alert("Lỗi: " + e.message);
            btn.innerText = "THỬ LẠI NGAY";
            btn.disabled = false;
        }
    };
</script>

</body>
</html>

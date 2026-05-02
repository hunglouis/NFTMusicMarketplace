<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>HỒ SƠ CHUYÊN GIA - MANH HUNG STUDIO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cloudflare.com">
    <style>
        body { background: #020617; color: #d4d4d8; padding-left: 5rem; min-height: 100vh; }
        .glass-gold { background: rgba(255, 255, 255, 0.01); border: 1px solid rgba(234, 179, 8, 0.1); border-radius: 35px; backdrop-filter: blur(10px); }
        .gold-input { background: rgba(255,255,255,0.03); border: 1px solid rgba(234, 179, 8, 0.2); border-radius: 15px; color: white; padding: 12px; outline: none; transition: 0.3s; }
        .gold-input:focus { border-color: #eab308; background: rgba(255,255,255,0.05); }
        .gold-text { color: #eab308; }
        .tx-card { background: rgba(234, 179, 8, 0.05); border-radius: 20px; transition: 0.3s; }
        .tx-card:hover { background: rgba(234, 179, 8, 0.1); border-color: #eab308; }
    </style>
</head>
<body class="p-10">
    <?php include 'navbar.php'; ?>

    <div class="max-w-6xl mx-auto">
        <header class="mb-10">
            <h1 class="text-3xl font-black text-yellow-500 uppercase tracking-tighter">Trung tâm điều hành di sản</h1>
            <p class="text-[9px] text-gray-500 uppercase tracking-[0.4em] mt-2">Đăng ký & Định danh thành viên Web3</p>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- CẶP ĐÔI CHỨNG THỰC VĨNH CỬU - POLYGON MAINNET -->
<div class="mt-10 p-8 glass-gold border-t-2 border-yellow-500/30">
    <p class="text-[10px] font-black gold-text uppercase tracking-[0.4em] mb-8 text-center">
        <i class="fas fa-guarantor mr-2 text-blue-400"></i> Hồ sơ chứng thực Blockchain (Full Data)
    </p>
    
    <div class="space-y-6">
        <!-- GIAO DỊCH 01: KHỞI SINH DI SẢN -->
        <div class="p-5 bg-white/5 rounded-2xl border border-yellow-500/10 hover:border-yellow-500/40 transition-all">
            <p class="text-[8px] text-yellow-600 font-black uppercase mb-2 tracking-widest italic">● Xác lập Khởi sinh (Minting TxHash):</p>
            <a href="https://polygonscan.com" 
               target="_blank" 
               class="text-[10px] font-mono text-gray-300 break-all leading-relaxed hover:text-yellow-500 block">
               0xdd885bab92a712c4851897f84e350e92793bcf49c91224ab1b8dd26f64b3aced
            </a>
        </div>

        <!-- GIAO DỊCH 02: XÁC LẬP TRẠNG THÁI SỞ HỮU -->
        <div class="p-5 bg-white/5 rounded-2xl border border-blue-500/10 hover:border-blue-500/40 transition-all">
            <p class="text-[8px] text-blue-400 font-black uppercase mb-2 tracking-widest italic">● Xác lập Sở hữu (Transfer TxHash):</p>
            <a href="https://polygonscan.com" 
               target="_blank" 
               class="text-[10px] font-mono text-gray-300 break-all leading-relaxed hover:text-blue-400 block">
               0x27681f116ec8e30ca49ec3dca67a46af795381c7eb27c3d37c5f4c8dbe134b28
            </a>
        </div>
    </div>
    
    <p class="text-[9px] text-gray-600 text-center mt-6 uppercase tracking-widest font-bold">
        Mọi dữ liệu trên đã được nén và lưu trữ vĩnh viễn tại Block #86000754
    </p>
</div>


            <!-- CỘT PHẢI: FORM ĐĂNG KÝ THÀNH VIÊN -->
            <div class="lg:col-span-2">
                <div class="glass-gold p-8">
                    <div class="flex justify-between items-center mb-8 border-b border-white/5 pb-6">
                        <h3 class="gold-text text-xs font-black uppercase tracking-widest">Khai báo danh tính thành viên</h3>
                        <div class="text-right">
                            <p class="text-[8px] text-gray-600 uppercase font-black tracking-widest">Tài chính ví</p>
                            <p class="text-lg font-black text-white"><span id="display-balance">0.00</span> <span class="text-[10px] gold-text font-bold">MATIC</span></p>
                        </div>
                    </div>
                    
                    <form class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="flex flex-col">
                                <label class="text-[9px] font-black text-gray-600 mb-2 ml-2 uppercase tracking-widest">Tên nghệ danh</label>
                                <input type="text" id="user-name" class="gold-input" placeholder="Nhập tên của bạn">
                            </div>
                            <div class="flex flex-col">
                                <label class="text-[9px] font-black text-gray-600 mb-2 ml-2 uppercase tracking-widest">Email liên hệ</label>
                                <input type="email" id="user-email" class="gold-input" placeholder="email@example.com">
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <label class="text-[9px] font-black text-gray-600 mb-2 ml-2 uppercase tracking-widest">Thư ngỏ gửi cộng đồng</label>
                            <textarea id="user-bio" rows="4" class="gold-input resize-none" placeholder="Viết vài dòng về đam mê di sản của bạn..."></textarea>
                        </div>
                        <button type="button" onclick="saveProfile()" class="w-full py-4 rounded-2xl bg-yellow-500 text-black font-black uppercase text-[10px] tracking-[0.3em] shadow-xl hover:scale-[1.01] active:scale-95 transition-all">Lưu hồ sơ di sản</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
        const SUPABASE_URL = "https://hmvvjjiiaelcsfqgxbxv.supabase.co";
        const ANON_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw";

        async function loadExpertData() {
            if (window.ethereum) {
                const accounts = await window.ethereum.request({ method: 'eth_accounts' });
                if (accounts.length > 0) {
                    const address = accounts;
                    document.getElementById('display-address').innerText = address;
                    const balanceWei = await window.ethereum.request({ method: 'eth_getBalance', params: [address, 'latest'] });
                    document.getElementById('display-balance').innerText = (parseInt(balanceWei, 16) / 1e18).toFixed(4);
                }
            }
        }
        window.addEventListener('load', loadExpertData);

        async function saveProfile() {
    const nameInput = document.getElementById('user-name').value;
    const emailInput = document.getElementById('user-email').value;

    if (!nameInput || !emailInput) return alert("Nghệ sĩ vui lòng nhập Tên và Email!");

    const accounts = await window.ethereum.request({ method: 'eth_accounts' });
    if (accounts.length === 0) return alert("Vui lòng kết nối ví!");
    const wallet = accounts[0].toLowerCase();

    // CHUẨN BỊ DỮ LIỆU KHỚP VỚI CÁC CỘT TRONG SQL CỦA BẠN
    const profileData = {
        username: nameInput,        // Khớp cột username
        email: emailInput,          // Khớp cột email
        wallet_address: wallet,     // Khớp cột wallet_address
        password: 'web3-user-' + wallet.substring(0, 5), // Điền mật khẩu giả để vượt qua NOT NULL
        updated_at: new Date()
    };

    const res = await fetch(`${SUPABASE_URL}/rest/v1/users`, {
        method: 'POST',
        headers: {
            "apikey": ANON_KEY,
            "Authorization": `Bearer ${ANON_KEY}`,
            "Content-Type": "application/json",
            "Prefer": "resolution=merge-duplicates" // Nếu trùng Email/Username thì cập nhật
        },
        body: JSON.stringify(profileData)
    });

    if (res.ok) {
        alert("✅ TUYỆT VỜI! Thành viên '" + nameInput + "' đã được xác lập danh tính.");
    } else {
        const errorData = await res.json();
        alert("❌ Lỗi hệ thống: " + errorData.message);
    }
}



    </script>
</body>
</html>

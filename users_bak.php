<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>BẢNG ĐIỀU KHIỂN DI SẢN - MANH HUNG STUDIO</title>
    <script src="https://tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cloudflare.com">
    <style>
        body { background: #020617; color: #d4d4d8; padding-left: 5rem; min-height: 100vh; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .glass-gold { background: rgba(255, 255, 255, 0.01); border: 1px solid rgba(234, 179, 8, 0.1); border-radius: 35px; backdrop-filter: blur(10px); }
        .gold-gradient { background: linear-gradient(to right, #eab308, #ca8a04); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .stat-card { border-left: 2px solid rgba(234, 179, 8, 0.3); background: rgba(255,255,255,0.02); }
        .pulse { animation: pulse 2s infinite; }
        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }
    </style>
</head>
<body class="p-10">
    <?php include 'navbar.php'; ?>

    <div class="max-w-6xl mx-auto">
        <!-- HEADER: TRẠNG THÁI HỆ THỐNG -->
        <header class="mb-12 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-black gold-gradient uppercase tracking-tighter">Bảng điều hành chuyên gia</h1>
                <p class="text-[10px] text-gray-500 uppercase tracking-[0.5em] mt-2 flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full pulse"></span> Hệ thống đang kết nối Polygon Mainnet
                </p>
            </div>
            <div class="text-right">
                <p class="text-[9px] text-gray-600 uppercase font-black">ID Thành viên</p>
                <p class="gold-text font-mono text-sm">#<?php echo rand(1000, 9999); ?>-MH</p>
            </div>
        </header>

        <!-- GRID 1: THÔNG SỐ VÍ & TÀI SẢN -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="glass-gold p-8 stat-card">
                <p class="text-[9px] uppercase text-gray-500 font-black mb-2 tracking-widest">Địa chỉ định danh</p>
                <p id="display-address" class="text-white font-mono text-sm truncate">Đang quét ví...</p>
            </div>
            <div class="glass-gold p-8 stat-card">
                <p class="text-[9px] uppercase text-gray-500 font-black mb-2 tracking-widest">Số dư khả dụng</p>
                <p class="text-2xl font-black text-white"><span id="display-balance">0.00</span> <span class="text-xs gold-text">MATIC</span></p>
            </div>
            <div class="glass-gold p-8 stat-card">
                <p class="text-[9px] uppercase text-gray-500 font-black mb-2 tracking-widest">Xếp hạng Node</p>
                <p class="text-xl font-black text-white uppercase italic">Nghệ sĩ Tiên phong</p>
            </div>
        </div>

        <!-- GRID 2: NHẬT KÝ GIAO DỊCH BLOCKCHAIN (TÍCH HỢP MÃ HASH CỦA BẠN) -->
        <div class="glass-gold p-10">
            <h3 class="gold-text text-xs font-black uppercase tracking-[0.4em] mb-8 flex items-center gap-3">
                <i class="fas fa-list-check"></i> Nhật ký giao dịch di sản (Bằng chứng thép)
            </h3>
            
            <div class="space-y-6">
                <!-- Giao dịch 1 -->
                <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl border border-white/5 hover:border-yellow-500/30 transition-all">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-yellow-500/10 flex items-center justify-center">
                            <i class="fas fa-cube gold-text text-xs"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-white uppercase">Xác lập Smart Contract (Mint)</p>
                            <p class="text-[9px] text-gray-500 font-mono">0xdd885bab92a712c4851897f84e350e92793bcf49c91224ab1b8dd26f64b3aced</p>
                        </div>
                    </div>
                    <a href="https://polygonscan.com" target="_blank" class="text-[9px] gold-text font-black uppercase border border-yellow-500/30 px-4 py-2 rounded-full hover:bg-yellow-500 hover:text-black transition-all">Kiểm chứng</a>
                </div>

                <!-- Giao dịch 2 -->
                <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl border border-white/5 hover:border-yellow-500/30 transition-all">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-blue-500/10 flex items-center justify-center">
                            <i class="fas fa-exchange-alt text-blue-400 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-white uppercase">Xác lập Trạng thái Di sản</p>
                            <p class="text-[9px] text-gray-500 font-mono">0x27681f116ec8e30ca49ec3dca67a46af795381c7eb27c3d37c5f4c8dbe134b28</p>
                        </div>
                    </div>
                    <a href="https://polygonscan.com" target="_blank" class="text-[9px] gold-text font-black uppercase border border-yellow-500/30 px-4 py-2 rounded-full hover:bg-yellow-500 hover:text-black transition-all">Kiểm chứng</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Logic đồng bộ hóa thông tin từ ví Web3
        async function syncExpertData() {
            if (window.ethereum) {
                const accounts = await window.ethereum.request({ method: 'eth_accounts' });
                if (accounts.length > 0) {
                    const address = accounts;
                    document.getElementById('display-address').innerText = address;
                    
                    const balanceWei = await window.ethereum.request({
                        method: 'eth_getBalance', params: [address, 'latest']
                    });
                    const balance = (parseInt(balanceWei, 16) / 1e18).toFixed(4);
                    document.getElementById('display-balance').innerText = balance;
                }
            }
        }
        window.addEventListener('load', syncExpertData);
    </script>
</body>
</html>

<style>
 <link rel="stylesheet" href="https://cloudflare.com">
   
/* Làm cho các nút mạng xã hội sáng lên */
.social-icons a {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1); /* Nền hơi sáng */
    color: #eab308 !important; /* Màu vàng đồng cho đồng bộ */
    margin-right: 8px;
    transition: all 0.3s;
    opacity: 1 !important; /* Hiện rõ 100% */
    pointer-events: auto !important; /* Mở khóa để bấm được */
    text-decoration: none;
}

/* Hiệu ứng khi di chuột vào: Sáng rực lên */
.social-icons a:hover {
    background: #eab308;
    color: #000 !important;
    transform: scale(1.1);
}
.social-icon {
    width: 32px; height: 32px;
    background: rgba(0, 255, 255, 0.1); /* Màu xanh ngọc nhạt */
    border: 1px solid #00ffff;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: #00ffff !important;
    text-decoration: none !important;
    transition: 0.3s;
}
.social-icon:hover {
    background: #00ffff;
    color: #000 !important;
    box-shadow: 0 0 10px #00ffff;
}

     /* Nhắm vào các mục menu trong Sidebar */
    .sidebar a, .nav-link, nav a {
        padding-top: 10px !important;    /* Giảm khoảng cách trên */
        padding-bottom:10px !important; /* Giảm khoảng cách dưới */
        margin-top: 0px !important;     /* Xóa khoảng trống thừa */
        margin-bottom: 0px !important;  /* Xóa khoảng trống thừa */
        line-height: 1.2 !important;    /* Làm cho hàng chữ thanh mảnh hơn */
        display: block;                  /* Để padding có tác dụng */
    }
    
    /* SIDEBAR SIÊU THANH MẢNH: Chỉ 3rem */
    aside { 
        width: 3rem; 
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        overflow: hidden; 
    }
    
    /* Khi rà chuột: Mở rộng vừa đủ 13rem để hiện chữ */
    aside:hover { 
        width: 13rem; 
    }
    
    /* Chữ hiển thị tinh tế */
    .menu-text { 
        opacity: 0; 
        transition: opacity 0.2s; 
        white-space: nowrap; 
        font-size: 0.75rem; /* Nhỏ gọn, tinh tế */
        letter-spacing: 0.05em;
    }
    aside:hover .menu-text { 
        opacity: 1; 
    }

    /* Nội dung chính chiếm gần như trọn màn hình */
    body { 
        padding-left: 3rem; 
        transition: padding-left 0.3s; 
    }

    /* Tùy chỉnh thanh cuộn sidebar */
    .custom-scrollbar::-webkit-scrollbar { width: 2px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #22d3ee; }
</style>

<aside class="fixed top-0 left-0 h-screen bg-black/95 border-r border-white/5 flex flex-col z-50 shadow-2xl custom-scrollbar">
    
    <!-- Logo Mini -->
    <div class="p-2 flex items-center gap-4 border-b border-white/5 h-10">
        <div class="min-w-[2rem] h-5 flex items-center justify-center bg-cyan-500 rounded-md shadow-[0_0_15px_rgba(6,182,212,0.4)]">
            <i class="fa-solid fa-bolt text-white text-xs"></i>
        </div>
        <span class="menu-text font-black text-white tracking-widest uppercase">Studio NFT</span>
    </div>

    <!-- Menu tối giản tuyệt đối -->
    <nav class="flex-grow py-4 px-2 space-y-2">
        <a href="welcome.php" class="flex items-center gap-4 px-2 py-3 text-gray-500 hover:text-cyan-400 transition-all group">
            <i class="fa-solid fa-house min-w-[1.2rem] text-center text-sm"></i>
            <span class="menu-text font-bold uppercase">Trang chủ</span>
        </a>

        <a href="marketplace_supabase.php" class="flex items-center gap-4 px-2 py-3 text-white bg-white/5 rounded-lg border border-white/10">
            <i class="fa-solid fa-compass min-w-[1.2rem] text-center text-sm text-cyan-400"></i>
            <span class="menu-text font-bold uppercase">Khám phá</span>
        </a>

        <a href="mint_nft.php" class="flex items-center gap-3 px-2 py-3 text-gray-500 hover:text-emerald-400 transition-all">
            <i class="fa-solid fa-plus-square min-w-[1.2rem] text-center text-sm"></i>
            <span class="menu-text font-bold uppercase">Tạo NFT</span>
        </a>

        <div class="h-px bg-white/5 my-4 mx-2"></div>

        <a href="wallet.php" class="flex items-center gap-3 px-2 py-3 text-gray-500 hover:text-yellow-500 transition-all">
            <i class="fa-solid fa-circle-nodes min-w-[1.2rem] text-center text-sm"></i>
            <span class="menu-text font-bold uppercase">Tài sản</span>
        </a>

        <a href="users_pro.php" class="flex items-center gap-3 px-2 py-3 text-gray-500 hover:text-yellow-500 transition-all">
            <i class="fa-solid fa-circle-nodes min-w-[1.2rem] text-center text-sm"></i>
            <span class="menu-text font-bold uppercase">Cài đặt hồ sơ</span>
        </a>

        <a href="users.php" class="flex items-center gap-3 px-2 py-3 text-gray-500 hover:text-yellow-500 transition-all">
            <i class="fa-solid fa-circle-nodes min-w-[1.2rem] text-center text-sm"></i>
            <span class="menu-text font-bold uppercase">Trung tâm Điều hành Di sản</span>
        </a>


        <a href="https://musicnftstudiomanhhung.online/" class="flex items-center gap-3 px-2 py-3 text-gray-500 hover:text-yellow-500 transition-all">
            <i class="fa-solid fa-circle-nodes min-w-[1.2rem] text-center text-sm"></i>
            <span class="menu-text font-bold uppercase">MusicNftStudio</span>
        </a>

        <a href="https://manhhungmarketplace.online/" class="flex items-center gap-3 px-2 py-3 text-gray-500 hover:text-yellow-500 transition-all">
            <i class="fa-solid fa-circle-nodes min-w-[1.2rem] text-center text-sm"></i>
            <span class="menu-text font-bold uppercase">MarketPlace</span>
        </a>   

        <a href="https://opensea.io/" class="flex items-center gap-3 px-2 py-3 text-gray-500 hover:text-yellow-500 transition-all">
            <i class="fa-solid fa-circle-nodes min-w-[1.2rem] text-center text-sm"></i>
            <span class="menu-text font-bold uppercase">OpenSea</span>
        </a>

    </nav>

    <!-- Khu vực Ví ở đáy -->
    <div class="p-2 border-t border-white/5 bg-black/80">
        <button id="connect-wallet-btn" onclick="connectWallet()" 
                class="w-full flex items-center gap-5 bg-zinc-900 p-2 rounded-md hover:bg-cyan-600 transition-all border border-zinc-800">          
            <span id="wallet-address-text" class="menu-text text-[9px] font-black text-white uppercase">Ví Web3</span>
        </button>
        <a href="logout.php" class="flex items-center gap-5 px-2 py-4 text-red-500/40 hover:text-red-500 transition-all">
            <i class="fa-solid fa-arrow-right-from-bracket min-w-[1.2rem] text-center text-xs"></i>
            <span class="menu-text text-[9px] font-bold uppercase">Đăng xuất</span>
        </a>
    </div>    
    <!-- BỘ NÚT MẠNG XÃ HỘI MỚI -->
<?php $current_url = urlencode("http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>
<div style="display: flex; gap: 10px; align-items: center; padding-left: 10px;">
    <span style="font-size: 9px; color: #666; text-transform: uppercase;">Lan tỏa:</span>

    <!-- Nút Facebook -->
    <div onclick="openLink('fb')" class="s-icon"><span style="font-size: 8px; font-weight: bold;">F</span></div>

    <!-- Nút Twitter (X) -->
    <div onclick="openLink('tw')" class="s-icon"><span style="font-size: 8px; font-weight: bold;">X</span></div>

    <!-- Nút Zalo -->
    <div onclick="openLink('zl')" class="s-icon"><span style="font-size: 8px; font-weight: bold;">Zalo</span></div>

    <!-- Nút YouTube -->
    <div onclick="openLink('yt')" class="s-icon"><span style="font-size: 8px; font-weight: bold;">Y</span></div>
</div>

<script>
function openLink(type) {
    let url = "";
    if(type === 'fb') url = "https://facebook.com"; // Thay bằng link của bạn
    if(type === 'tw') url = "https://twitter.com"; 
    if(type === 'zl') url = "https://zalo.me"; // Thay bằng số điện thoại của bạn
    if(type === 'yt') url = "https://youtube.com";

    window.open(url, '_blank');
}
</script>

<style>
.s-icon {
    width: 30px; height: 30px;
    background: rgba(0, 255, 255, 0.1);
    border: 1px solid #00ffff;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    color: #00ffff;
    cursor: pointer; /* Biến chuột thành hình bàn tay */
    transition: 0.3s;
}
.s-icon:hover { background: #00ffff; color: #000; }
</style>
   
       
</aside>

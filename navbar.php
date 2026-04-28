<style>
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
    <div class="p-2 flex items-center gap-4 border-b border-white/5 h-14">
        <div class="min-w-[2rem] h-8 flex items-center justify-center bg-cyan-500 rounded-md shadow-[0_0_15px_rgba(6,182,212,0.4)]">
            <i class="fa-solid fa-bolt text-white text-xs"></i>
        </div>
        <span class="menu-text font-black text-white tracking-widest uppercase">Studio NFT</span>
    </div>

    <!-- Menu tối giản tuyệt đối -->
    <nav class="flex-grow py-6 px-2 space-y-2">
        <a href="index.php" class="flex items-center gap-5 px-2 py-3 text-gray-500 hover:text-cyan-400 transition-all group">
            <i class="fa-solid fa-house min-w-[1.2rem] text-center text-sm"></i>
            <span class="menu-text font-bold uppercase">Trang chủ</span>
        </a>

        <a href="marketplace_supabase.php" class="flex items-center gap-5 px-2 py-3 text-white bg-white/5 rounded-lg border border-white/10">
            <i class="fa-solid fa-compass min-w-[1.2rem] text-center text-sm text-cyan-400"></i>
            <span class="menu-text font-bold uppercase">Khám phá</span>
        </a>

        <a href="mint_nft.php" class="flex items-center gap-5 px-2 py-3 text-gray-500 hover:text-emerald-400 transition-all">
            <i class="fa-solid fa-plus-square min-w-[1.2rem] text-center text-sm"></i>
            <span class="menu-text font-bold uppercase">Tạo NFT</span>
        </a>

        <div class="h-px bg-white/5 my-4 mx-2"></div>

        <a href="wallet.php" class="flex items-center gap-5 px-2 py-3 text-gray-500 hover:text-yellow-500 transition-all">
            <i class="fa-solid fa-circle-nodes min-w-[1.2rem] text-center text-sm"></i>
            <span class="menu-text font-bold uppercase">Tài sản</span>
        </a>

        <a href="https://musicnftstudiomanhhung.online/" class="flex items-center gap-5 px-2 py-3 text-gray-500 hover:text-yellow-500 transition-all">
            <i class="fa-solid fa-circle-nodes min-w-[1.2rem] text-center text-sm"></i>
            <span class="menu-text font-bold uppercase">MusicNftStudio</span>
        </a>

        <a href="https://manhhungmarketplace.online/" class="flex items-center gap-5 px-2 py-3 text-gray-500 hover:text-yellow-500 transition-all">
            <i class="fa-solid fa-circle-nodes min-w-[1.2rem] text-center text-sm"></i>
            <span class="menu-text font-bold uppercase">MarketPlace</span>
        </a>   

        <a href="https://opensea.io/" class="flex items-center gap-5 px-2 py-3 text-gray-500 hover:text-yellow-500 transition-all">
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
</aside>

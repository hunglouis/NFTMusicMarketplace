// Louis Music Heritage Protection System
import function applyHeritageLock(playerId, limit = 45) {
    const audio = document.getElementById(playerId);
    if (!audio) return;

    audio.addEventListener('timeupdate', function() {
        // Tạm thời để false. Sau này logic xác thực ví sẽ điều khiển biến này.
        let isVerifiedMember = false; 

        if (!isVerifiedMember && audio.currentTime >= limit) {
            audio.pause();
            audio.currentTime = 0;
            
            // Gọi hàm hiện Popup (sẽ định nghĩa ở dưới)
            showHeritagePopup();
            
            // Ẩn thanh player bar nếu có
            const playerBar = document.getElementById('music-player-bar');
            if(playerBar) playerBar.classList.add('translate-y-full');
        }
    });
}

// Chặn chuột phải toàn trang để hạn chế lưu nhạc trái phép
document.addEventListener('contextmenu', event => event.preventDefault());

// Thay đoạn nút bấm cũ bằng thiết kế "Phân cấp" này
const btnHtml = `
    <div class="flex flex-col gap-4 mt-6">
        <!-- NÚT CHÍNH: SANG TRỌNG, NỔI BẬT -->
        <a href="/NFTMusicmarketplace/mint_page.php" 
           class="bg-gradient-to-r from-cyan-400 to-blue-500 text-black font-black py-5 rounded-full hover:scale-105 transition shadow-[0_0_30px_rgba(0,255,255,0.5)] uppercase tracking-tighter">
            💎 Mua trực tiếp tại HungLouis (Full Privileges)
        </a>
        
        <!-- NÚT PHỤ: KHIÊM TỐN HƠN -->
        <a href="https://opensea.io" target="_blank" 
           class="text-gray-400 text-xs hover:text-white transition underline decoration-gray-600">
            Hoặc mua qua sàn thứ cấp OpenSea
        </a>
    </div>
`;



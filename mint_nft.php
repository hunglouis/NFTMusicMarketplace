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
            
            <!-- PHẦN 1: BẢN GIAO ƯỚC (KHUNG CHỜ) -->
            <section class="glass-gold p-8 rounded-[40px] shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <i class="fa-solid fa-scroll text-6xl gold-text"></i>
                </div>
                <h3 class="gold-text text-sm font-black uppercase tracking-widest mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-scale-balanced"></i> I. Bản Giao Ước Đạo Đức & Pháp Lý
                </h3>
                <div class="min-h-[200px] flex items-center justify-center border-2 border-dashed gold-border rounded-3xl">
                    <p class="text-gray-600 italic text-sm text-center px-10">
                        <div class="glass-gold p-6 rounded-2xl border-l-4 border-yellow-500/50 my-6 bg-white/5">
                    <p class="italic text-gray-300 text-sm leading-relaxed">
                        "Bằng việc khởi sinh tác phẩm này lên mạng lưới Blockchain, tôi - Nghệ sĩ Mạnh Hùng - khẳng định đây là kết tinh của lao động sáng tạo độc bản, được bảo tồn nhằm kết nối giá trị âm nhạc từ quá khứ đến tương lai. Tôi cam kết thượng tôn các quy tắc đạo đức và pháp lý, bảo vệ tính minh bạch của di sản vĩnh viễn theo tiêu chuẩn ERC-721 và ERC-2981."
                    </p>
                </div>

                    </p>
                </div>
                <div class="mt-6 flex items-center gap-3">
                    <input type="checkbox" id="agreement" class="w-4 h-4 accent-yellow-600" disabled>
                    <label for="agreement" class="text-xs text-gray-500">Tôi xác nhận đã thấu hiểu và cam kết thực hiện đúng giao ước.</label>
                </div>
            </section>

                        <!-- II. THÔNG TIN TÁC PHẨM - BẢN TINH CHỈNH TỶ LỆ VÀNG -->
<section class="glass-gold p-8 rounded-[40px] mt-10 border border-yellow-500/10 bg-black/20">
    <h3 class="gold-text text-sm font-black uppercase tracking-widest mb-10 flex items-center gap-2">
        <i class="fa-solid fa-wand-magic-sparkles"></i> II. Thông Tin Tác Phẩm
    </h3>
    
    <!-- III. KHU VỰC NHẬP LIỆU - DỨT ĐIỂM CHỒNG LẤN -->
<div style="display: flex; gap: 20px; align-items: flex-start; margin-bottom: 30px;">
    
    <!-- 1. Tên tác phẩm (Chiếm 50% chiều ngang) -->
    <div style="flex: 2;">
        <label style="display: block; font-size: 9px; color: #71717a; font-weight: 900; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Tên Tác Phẩm</label>
        <input type="text" placeholder="Cánh Hoa Phiêu Bồng (1994)" 
               style="width: 100%; background: rgba(255,255,255,0.03); border: 1px solid rgba(234, 179, 8, 0.2); padding: 14px; border-radius: 12px; color: white; outline: none; box-sizing: border-box;">
    </div>

    <!-- 2. Giá (Chiếm 25%) -->
    <div style="flex: 1;">
        <label style="display: block; font-size: 9px; color: #71717a; font-weight: 900; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Giá (MATIC)</label>
        <input type="number" placeholder="0.1" 
               style="width: 100%; background: rgba(255,255,255,0.03); border: 1px solid rgba(234, 179, 8, 0.2); padding: 14px; border-radius: 12px; color: white; outline: none; box-sizing: border-box;">
    </div>

    <!-- 3. Tác quyền (Chiếm 25%) -->
    <div style="flex: 1;">
        <label style="display: block; font-size: 9px; color: #71717a; font-weight: 900; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Tác quyền %</label>
        <input type="number" value="10" 
               style="width: 100%; background: rgba(255,255,255,0.03); border: 1px solid rgba(234, 179, 8, 0.2); padding: 14px; border-radius: 12px; color: #eab308; font-weight: bold; outline: none; box-sizing: border-box;">
    </div>
</div>


    <div style="margin-bottom: 30px;">
        <label style="display: block; font-size: 9px; color: #71717a; font-weight: 900; text-transform: uppercase; margin-bottom: 10px; letter-spacing: 1px;">Thư Ngỏ / Mô Tả Di Sản</label>
        <textarea rows="5" placeholder="Kể câu chuyện về hoàn cảnh ra đời..." 
                  style="width: 100%; background: rgba(255,255,255,0.03); border: 1px solid rgba(234, 179, 8, 0.2); padding: 15px; border-radius: 15px; color: white; outline: none; resize: none; box-sizing: border-box;"></textarea>
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
    const checkbox = document.querySelector('input[type="checkbox"]');
    const mintBtn = document.querySelector('button[disabled]');

    // Xóa bỏ thuộc tính disabled của checkbox để bạn có thể tích vào
    checkbox.removeAttribute('disabled');

    checkbox.addEventListener('change', function() {
        if (this.checked) {
            mintBtn.disabled = false;
            mintBtn.style.background = 'linear-gradient(to right, #eab308, #ca8a04)';
            mintBtn.style.color = '#000';
            mintBtn.style.cursor = 'pointer';
            mintBtn.style.boxShadow = '0 0 30px rgba(234, 179, 8, 0.4)';
            mintBtn.innerText = "KHỞI SINH DI SẢN NGAY";
        } else {
            mintBtn.disabled = true;
            mintBtn.style.background = '#18181b';
            mintBtn.style.color = '#3f3f46';
            mintBtn.style.cursor = 'not-allowed';
            mintBtn.style.boxShadow = 'none';
            mintBtn.innerText = "KHỞI SINH DI SẢN (MINT NFT)";
        }
    });
</script>

</body>
</html>

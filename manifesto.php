<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .text-neon { color: #00ffff; text-shadow: 0 0 10px rgba(0,255,255,0.5); }
    </style>
</head>
<body class="bg-black text-gray-200 font-sans leading-relaxed">
    <?php if(file_exists('navbar.php')) include 'navbar.php'; ?>
<!-- TỔ HỢP XÁC THỰC SMART CONTRACT & HƯỚNG DẪN MINH BẠCH -->
<div class="max-w-6xl mx-auto mb-16 space-y-6">
    
    <!-- 1. Thanh địa chỉ hợp đồng -->
    <div class="glass-card border-cyan-500/50 p-1 rounded-2xl bg-gradient-to-r from-cyan-950/20 to-transparent">
        <div class="flex flex-col md:flex-row items-center justify-between px-6 py-4 gap-4">
            <div class="flex items-center gap-3">
                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse shadow-[0_0_10px_#22c55e]"></div>
                <div>
                    <p class="text-[10px] text-gray-500 uppercase font-bold tracking-widest">Trạng thái hệ thống</p>
                    <p class="text-white font-mono text-sm">Smart Contract Đã Xác Minh (Verified)</p>
                </div>
            </div>
            
            <div class="flex flex-col md:items-end">
                <p class="text-[10px] text-gray-500 mb-1 uppercase font-bold tracking-widest">Địa chỉ hợp đồng (Polygon):</p>
                <a href="https://polygonscan.com" 
                   target="_blank" 
                   class="text-neon font-mono text-sm hover:underline flex items-center gap-2 group">
                    <span>0xddcd6cf61e81c6e22a082841a388a19984ff4745</span>
                    <svg xmlns="http://w3.org" class="h-4 w-4 group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Khối Hướng dẫn soi Contract cho người mới -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="glass-card p-6 rounded-3xl border-white/5 bg-white/5">
            <h4 class="text-white font-bold mb-4 flex items-center gap-2">
                <span class="text-neon">🔍</span> Hướng dẫn kiểm tra minh bạch
            </h4>
            <div class="space-y-4 text-xs text-gray-400">
                <div class="flex gap-3">
                    <span class="text-neon font-mono">01.</span>
                    <p>Click vào link <b>Polygonscan</b> ở trên để mở sổ cái công khai của hệ thống.</p>
                </div>
                <div class="flex gap-3">
                    <span class="text-neon font-mono">02.</span>
                    <p>Chọn tab <b>"Contract"</b> -> <b>"Read Contract"</b> để xem các quy tắc đã lập trình.</p>
                </div>
                <div class="flex gap-3">
                    <span class="text-neon font-mono">03.</span>
                    <p>Tại mục <b>"royaltyInfo"</b>: Bạn sẽ thấy tỷ lệ 10% (1000) - Cam kết bảo tồn vĩnh viễn.</p>
                </div>
            </div>
        </div>

        <div class="glass-card p-6 rounded-3xl border-white/5 bg-white/5">
            <h4 class="text-white font-bold mb-4 flex items-center gap-2">
                <span class="text-neon">🛡️</span> Quyền lợi đối chiếu thực tế
            </h4>
            <div class="space-y-4 text-xs text-gray-400">
                <div class="flex gap-3">
                    <span class="text-neon font-mono">04.</span>
                    <p>Tại mục <b>"ownerOf"</b>: Nhập TokenID bạn giữ để thấy ví của mình đã được ghi danh.</p>
                </div>
                <div class="flex gap-3">
                    <span class="text-neon font-mono">05.</span>
                    <p>Mọi thông tin tại đây là <b>vĩnh viễn</b>, ngân hàng hay tác giả đều không thể tự ý sửa đổi.</p>
                </div>
                <div class="flex gap-3">
                    <span class="text-neon font-mono">06.</span>
                    <p>Dữ liệu này là bằng chứng pháp lý cao nhất cho quyền sở hữu của bạn.</p>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="max-w-6xl mx-auto px-6 py-12">
    <header class="text-center mb-16">
        <h1 class="text-5xl font-black text-neon mb-4 tracking-tighter uppercase">路易斯音乐宣言 / LOUIS MUSIC MANIFESTO</h1>
        <p class="text-xl text-gray-400 mb-8">Hệ thống vận hành & Bảo tồn di sản âm nhạc số</p>
        <div class="max-w-4xl mx-auto glass-card p-8 rounded-[2rem] border-cyan-500/30">
            <p class="text-lg italic text-white leading-relaxed">
                "Âm nhạc không chỉ là những nốt nhạc, đó là di sản của tâm hồn. Louis Music ra đời để bảo tồn những độc bản, tôn vinh người sáng tạo và xây dựng cộng đồng sở hữu trí tuệ minh bạch."
            </p>
        </div>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Sidebar Navigation -->
         <!-- CỘT TRÁI: MỤC LỤC -->
        <section class="space-y-4">
            <div class="glass-card p-8 rounded-[2rem] sticky top-8">
                <h3 class="text-white font-bold mb-6 border-b border-white/10 pb-2 uppercase tracking-widest text-sm">Mục lục</h3>
                <nav class="flex flex-col gap-4 text-sm text-gray-400">
                    <a href="#chuong-1" class="hover:text-neon">Chương 1: Ý niệm nghệ thuật</a>
                    <a href="#chuong-2" class="hover:text-neon">Chương 2: Cẩm nang Độc bản (721)</a>
                    <a href="#chuong-3" class="hover:text-neon">Chương 3: Đặc quyền Cộng đồng (1155)</a>
                    <a href="#chuong-4" class="hover:text-neon">Chương 4: Chống bào mòn (2981)</a>
                </nav>
            </div>
        </section>

        <!-- Main Content Area -->
        <main class="md:col-span-2 space-y-16">
            <!-- Chương 1 -->
            <section id="chuong-1" class="glass-card p-8 rounded-[2rem]">
                <h2 class="text-3xl font-black text-white mb-6 flex items-center gap-3">
                    <span class="text-neon text-2xl">01</span> Chương 1: Ý niệm nghệ thuật
                </h2>
                <p class="text-gray-400 leading-relaxed">
                    Chúng tôi coi âm nhạc là <b>Di sản</b> thay vì hàng hóa tiêu dùng. Louis Music tập hợp những tâm hồn muốn bảo vệ tính nguyên bản của nghệ thuật trước sự nhân bản vô tính của kỷ nguyên số.
                </p>
            </section>
            
            <!-- Chương 2 (Như trong hình bạn gửi) -->
            <section id="chuong-2" class="glass-card p-8 rounded-[2rem]">
                <h2 class="text-3xl font-black text-white mb-6 flex items-center gap-3">
                    <span class="text-neon text-2xl">02</span> Chương 2: Vận hành Độc bản
                </h2>
                <div class="space-y-6">
                    <div>
                        <h4 class="text-neon font-bold">2.1. Xác thực nguồn gốc</h4>
                        <p class="text-sm text-gray-400">Mỗi NFT ERC-721 được đúc duy nhất một bản. Bạn có thể kiểm tra "Provenance" trên Polygonscan để đảm bảo chữ ký số từ Louis Music.</p>
                    </div>
                    <div>
                        <h4 class="text-neon font-bold">2.2. Quyền Giám hộ</h4>
                        <p class="text-sm text-gray-400">Chủ sở hữu có quyền thương lượng bản quyền phái sinh và sử dụng tác phẩm cho mục đích thương mại theo thỏa thuận đặc biệt.</p>
                    </div>
                </div>
            </section>

            <!-- Chương 3 (BỔ SUNG) -->
            <section id="chuong-3" class="glass-card p-8 rounded-[2rem]">
                <h2 class="text-3xl font-black text-white mb-6 flex items-center gap-3">
                    <span class="text-neon text-2xl">03</span> Chương 3: Đặc quyền Cộng đồng
                </h2>
                <ul class="space-y-3 text-sm text-gray-400">
                    <li class="flex items-start gap-2"><span>✔️</span> Quyền truy cập thư viện âm thanh Lossless và video hậu trường.</li>
                    <li class="flex items-start gap-2"><span>✔️</span> Nhận Airdrop các bản demo đặc biệt trực tiếp vào ví cá nhân.</li>
                    <li class="flex items-start gap-2"><span>✔️</span> Quyền biểu quyết (DAO) cho các dự án bảo tồn tiếp theo.</li>
                </ul>
            </section>

                        <!-- PHẦN NÀY ĐÃ CÓ ĐỦ VĂN BẢN - BẠN CHỈ CẦN COPY & PASTE -->
            <section id="chương-4" class="glass-card p-8 rounded-[40px] border-emerald-500/30">
                <h2 class="text-3xl font-bold text-emerald-400 mb-6">🛡️ Chương 4: Chống bào mòn tài sản</h2>
                <p class="mb-4 text-gray-300">
                    Tại Louis Music, chúng tôi chấm dứt kỷ nguyên của các loại phí ẩn. 
                    Khác với hệ thống ngân hàng truyền thống - nơi biểu phí có thể thay đổi đơn phương 
                    và âm thầm bào mòn tài sản của khách hàng - mọi quy tắc tại đây được bảo vệ bởi toán học.
                </p>
                <div class="bg-black/40 p-6 rounded-2xl border border-emerald-500/20 mb-6">
                    <p class="text-emerald-300 font-bold mb-2">QUY TẮC BẤT BIẾN:</p>
                    <ul class="list-disc pl-5 text-sm text-gray-400 space-y-2">
                        <li>Phí tác quyền 10% được lập trình cứng trong Smart Contract.</li>
                        <li>Không một ai (kể cả tác giả) có quyền tự ý nâng mức phí sau khi phát hành.</li>
                        <li>Phí này dùng để bảo tồn di sản, đảm bảo giá trị NFT của bạn luôn tăng trưởng.</li>
                    </ul>
                </div>
            </section>

            <!-- BOX HIỆU TRIỆU XÁC THỰC -->
<div class="max-w-4xl mx-auto my-12 p-8 rounded-[3rem] border-2 border-dashed border-cyan-500/30 bg-cyan-950/10 text-center">
    <h3 class="text-2xl font-black text-white mb-4">BẠN KHÔNG CẦN PHẢI TIN CHÚNG TÔI</h3>
    <p class="text-gray-400 mb-6 px-4">
        Khác với các tổ chức tài chính truyền thống luôn giữ kín các thuật toán thu phí, 
        <span class="text-neon">Louis Music</span> mở toang cánh cửa để bạn giám sát. 
        Hãy để toán học và Blockchain chứng minh quyền lợi của bạn.
    </p>
    
    <div class="flex flex-wrap justify-center gap-4">
        <a href="#huong-dan-soi" class="px-8 py-3 bg-cyan-500 text-black font-bold rounded-full hover:scale-105 transition">
            Cách "soi" Contract cho người mới
        </a>
        <a href="https://polygonscan.com" target="_blank" class="px-8 py-3 border border-cyan-500 text-cyan-500 font-bold rounded-full hover:bg-cyan-500/10 transition">
            Xem trực tiếp trên Polygonscan
        </a>
    </div>
</div>

        </main>
        
    </div>
</div>

</body>
</html>

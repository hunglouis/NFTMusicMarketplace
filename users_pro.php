<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>THIẾT LẬP HỒ SƠ CHUYÊN NGHIỆP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cloudflare.com">
    <style>
        body { background: #080808; color: #fff; padding-left: 5rem; min-height: 100vh; }
        .opensea-card { background: #121212; border: 1px solid #303339; border-radius: 20px; padding: 30px; }
        .opensea-input { background: transparent; border: 1px solid #303339; border-radius: 12px; padding: 12px; color: white; width: 100%; outline: none; transition: 0.3s; }
        .opensea-input:focus { border-color: #eab308; }
        .gold-text { color: #eab308; }
        .save-btn { background: #2081e2; color: white; font-weight: 800; padding: 12px 30px; border-radius: 12px; transition: 0.3s; }
        .save-btn:hover { background: #1868b7; }
    </style>
</head>
<body class="p-10">
    <?php include 'navbar.php'; ?>

    <div class="max-w-3xl mx-auto">
        <header class="mb-10">
            <h1 class="text-3xl font-black mb-2 uppercase tracking-tighter">Cài đặt hồ sơ</h1>
            <p class="text-gray-500 text-xs tracking-widest uppercase">Tầng định danh di sản (Level 2)</p>
        </header>

        <div class="opensea-card space-y-8">
            <!-- 1. USERNAME (Dựa trên SQL của bạn) -->
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase text-gray-500 tracking-widest">Tên người dùng (Username)</label>
                <input type="text" id="new-username" placeholder="Nhập nghệ danh..." class="opensea-input">
            </div>

            <!-- 2. EMAIL (Theo chuẩn OpenSea) -->
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <label class="text-[10px] font-black uppercase text-gray-500 tracking-widest">Địa chỉ Email</label>
                    <span class="text-[#2081e2] text-[9px] font-black uppercase"><i class="fas fa-check-circle"></i> Verified</span>
                </div>
                <input type="email" id="new-email" placeholder="manhhung@example.com" class="opensea-input">
            </div>

            <!-- 3. BIO -->
            <div class="space-y-2">
                <label class="text-[10px] font-black uppercase text-gray-500 tracking-widest">Tiểu sử di sản (Bio)</label>
                <textarea id="new-bio" rows="4" placeholder="Kể câu chuyện về sản vật của bạn..." class="opensea-input resize-none"></textarea>
            </div>

            <!-- 4. SOCIAL CONNECTIONS -->
            <div class="pt-6 border-t border-[#303339] space-y-4">
                <label class="text-[10px] font-black uppercase text-gray-500 tracking-widest">Kết nối mạng xã hội</label>
                <div class="flex items-center justify-between bg-white/5 p-4 rounded-xl border border-white/5">
                    <div class="flex items-center gap-3">
                        <i class="fa-brands fa-x-twitter text-xl"></i>
                        <span class="text-sm font-bold">Connect X</span>
                    </div>
                    <button class="text-[10px] font-black uppercase bg-white/10 px-4 py-2 rounded-lg hover:bg-white/20">+ Connect</button>
                </div>
            </div>

            <div class="flex justify-end pt-6">
                <button onclick="saveNewProfile()" class="save-btn uppercase text-xs tracking-widest">Lưu hồ sơ (Save)</button>
            </div>
        </div>
    </div>
    <script>
    // 1. Chìa khóa kết nối tầng dữ liệu
    const SUPABASE_URL = "https://hmvvjjiiaelcsfqgxbxv.supabase.co";
    const ANON_KEY = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw";

    // 2. Hàm "Ghi danh di sản"
    async function saveNewProfile() {
        // Kiểm tra xem nghệ sĩ đã kết nối ví chưa
        if (!window.ethereum) return alert("Vui lòng cài đặt Metamask!");
        
        const accounts = await window.ethereum.request({ method: 'eth_accounts' });
        if (accounts.length === 0) return alert("Nghệ sĩ vui lòng kết nối ví để xác lập danh tính!");
        
        const wallet = accounts[0].toLowerCase();
        const username = document.getElementById('new-username').value;
        const email = document.getElementById('new-email').value;
        const bio = document.getElementById('new-bio').value;

        if (!username || !email) return alert("Tên và Email là hai mạch máu chính, không thể để trống!");

        // 3. Thực hiện lệnh UPSERT (Có thì cập nhật, chưa có thì khởi tạo mới)
        const res = await fetch(`${SUPABASE_URL}/rest/v1/users`, {
            method: 'POST',
            headers: {
                "apikey": ANON_KEY,
                "Authorization": `Bearer ${ANON_KEY}`,
                "Content-Type": "application/json",
                "Prefer": "resolution=merge-duplicates" // Tuyệt kỹ: Tự động hợp nhất dữ liệu trùng
            },
            body: JSON.stringify({
                wallet_address: wallet,
                username: username,
                email: email,
                avatar: bio, // Tạm thời lưu Bio vào đây hoặc cột bio nếu bạn đã tạo
                updated_at: new Date()
            })
        });

        if (res.ok) {
            alert("✅ XÁC LẬP THÀNH CÔNG!\nTính cách và di sản của bạn đã được ghi lại vào lịch sử hệ thống.");
        } else {
            const err = await res.json();
            alert("❌ MẠCH DỮ LIỆU BỊ NGẮT: " + err.message);
        }
    }

    // Tự động nạp dữ liệu cũ (nếu có) khi vừa vào trang
    window.addEventListener('load', async () => {
        // Ở đây chúng ta sẽ viết code để tự động điền Tên/Email cũ nếu họ đã từng đăng ký
    });
    </script>

    <script>
        async function saveNewProfile() {
            // Logic Insert/Update vào bảng users của Supabase sẽ nằm ở đây
            alert("Đang đồng bộ thông tin mới lên tầng di sản...");
        }
    </script>
</body>
</html>

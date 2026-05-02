<?php
session_start();
require_once 'db.php'; 

// 1. Lấy ID
$item_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
// 2. Cấu hình Supabase (NHỚ ĐIỀN KEY THẬT CỦA BẠN VÀO ĐÂY)
$supabaseUrl = "https://hmvvjjiiaelcsfqgxbxv.supabase.co";
$apiKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw"; 

$id = $_GET['id'] ?? 0;
$item = null;

// Hàm hỗ trợ lấy dữ liệu từ Supabase để code sạch hơn
function getFromSupabase($url, $apiKey, $table, $id) {
    $ch = curl_init("$url/rest/v1/$table?id=eq.$id&select=*");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["apikey: $apiKey", "Authorization: Bearer $apiKey"]);
    $response = json_decode(curl_exec($ch), true);
    curl_close($ch);
    // Trả về phần tử đầu tiên nếu mảng có dữ liệu, nếu không trả về null
    return (is_array($response) && !empty($response)) ? $response[0] : null;
}

// 1. Thử tìm trong bảng 'items'
$item = getFromSupabase($supabaseUrl, $apiKey, 'items', $id);

// 2. Nếu không thấy, thử tìm trong bảng 'hunglouis'
if (!$item) {
    $item = getFromSupabase($supabaseUrl, $apiKey, 'hunglouis', $id);
}

// --- KIỂM TRA CUỐI CÙNG ---
if (!$item) {
    // Dùng exit hoặc die để dừng hoàn toàn, không chạy xuống phần HTML bên dưới nữa
    exit("⚠️ Không tìm thấy tác phẩm có ID là $id trong bất kỳ kho hàng nào!");
}

// Xử lý link Pinata
if (isset($item['image_url']) && strpos($item['image_url'], 'ipfs://') !== false) {
    $item['image_url'] = str_replace('ipfs://', 'https://pinata.cloud', $item['image_url']);
}

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <meta charset="UTF-8">
    <title>Chi tiết NFT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #020617; color: white; font-family: sans-serif; margin: 0; padding-left: 3.5rem; }
        /* Khống chế ảnh tuyệt đối */
        .poster-frame { width: 450px; height: 550px; overflow: hidden; border-radius: 30px; border: 1px solid rgba(255,255,255,0.1); background: #000; }
        .poster-frame img { width: 100%; height: 100%; object-fit: cover; }
    </style>
</head>
    

<body class="p-5 md:p-10">
    <?php if(file_exists('navbar.php')) include 'navbar.php'; ?>
        <div class="max-w-6xl mx-auto mt-10">
        <!-- Nút quay lại -->
        <a href="marketplace_supabase.php" class="text-gray-500 hover:text-cyan-400 font-bold text-xs uppercase tracking-widest mb-10 inline-block">
            ← Quay lại cửa hàng
        </a>

        <!-- BỐ CỤC CHIA 2 CỘT RÕ RÀNG -->
        <div class="flex flex-col lg:flex-row gap-12 items-start">
            
            <!-- CỘT TRÁI: KHUNG ẢNH ĐÃ FIX KÍCH THƯỚC -->
            <div class="w-[400px] flex-shrink-0 shadow-[0_0_50px_rgba(6,182,212,0.15)] rounded-[40px] overflow-hidden border border-white/10">
                <img src="<?php echo $item['image_url']; ?>" class="w-full h-auto object-cover block">
            </div>

            <!-- CỘT PHẢI: THÔNG TIN CHI TIẾT -->
            <div class="flex-grow space-y-8">
                <div>
                    <h1 style="color: #00ffff; font-weight: 900;"><?php echo $item['name']; ?></h1>
                    <img src="<?php echo $item['image_url']; ?>" style="width: 100%; border-radius: 20px;">
                    </h1>
                    <p class="text-2xl font-bold text-emerald-400 mt-4">💰 <?php echo $item['price']; ?> MATIC</p>
                </div>
<!-- BỘ NÚT CHIA SẺ MẠNG XÃ HỘI - DỨT ĐIỂM LỖI -->
<div style="display: flex; align-items: center; gap: 15px; margin-top: 20px; margin-bottom: 20px;">
    <span style="font-size: 10px; color: #6b7280; font-weight: 900; text-transform: uppercase; letter-spacing: 2px;">Lan tỏa:</span>
    
    <!-- Nút Facebook -->
    <a href="https://facebook.com<?php echo urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?>" 
       target="_blank" style="width: 32px; height: 32px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: 0.3s;">
        <i class="fab fa-facebook-f" style="color: #9ca3af; font-size: 12px;"></i>
    </a>

    <!-- Nút Twitter -->
    <a href="https://twitter.com<?php echo urlencode("http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"); ?>" 
       target="_blank" style="width: 32px; height: 32px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: 0.3s;">
        <i class="fab fa-x-twitter" style="color: #9ca3af; font-size: 12px;"></i>
    </a>

    <!-- Nút Sao chép link -->
    <button onclick="copyPageLink()" style="width: 32px; height: 32px; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.3s;">
        <i class="fas fa-link" style="color: #9ca3af; font-size: 12px;"></i>
    </button>
</div>

<script>
function copyPageLink() {
    navigator.clipboard.writeText(window.location.href);
    alert("Đã sao chép đường dẫn tác phẩm!");
}
</script>


                <div class="bg-white/5 p-8 rounded-[32px] border border-white/5 backdrop-blur-xl">
                    <h3 class="text-cyan-400 text-[10px] font-black uppercase tracking-[0.3em] mb-4">📜 Mô tả tác phẩm</h3>
                    <p class="text-gray-300 text-sm leading-relaxed italic whitespace-pre-line">
                        <?php echo $item['description'] ?: "Tác phẩm NFT âm nhạc độc bản nằm trong bộ sưu tập di sản của nghệ sĩ Mạnh Hùng."; ?>
                    </p>
                </div>
                <!-- BẮT ĐẦU ĐOẠN HÀNH TRÌNH DI SẢN (BẢN ĐẸP) -->
                <!-- KHUNG HÀNH TRÌNH DI SẢN - BẢN HOÀN THIỆN -->
<!-- KHUNG HÀNH TRÌNH DI SẢN - DỨT ĐIỂM LỖI HIỂN THỊ -->
<div style="margin-top: 40px; padding: 25px; background: rgba(255,255,255,0.05); border-radius: 30px; border: 1px solid rgba(255,255,255,0.1);">
    
    <h3 style="color: #22d3ee; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 3px; margin-bottom: 30px; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-history"></i> Hành trình di sản
    </h3>

    <div style="position: relative; padding-left: 45px;">
        <!-- Đường kẻ dọc nối liền -->
        <div style="position: absolute; left: 18px; top: 5px; bottom: 5px; width: 1px; background: linear-gradient(to bottom, #10b981, #1f2937, #06b6d4);"></div>

        <!-- Mốc 1: Chủ sở hữu -->
        <div style="position: relative; margin-bottom: 40px;">
            <div style="position: absolute; left: -45px; top: 0; width: 36px; height: 36px; background: #000; border: 1px solid #10b981; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px rgba(16,185,129,0.3);">
                <i class="fas fa-user-check" style="color: #10b981; font-size: 12px;"></i>
            </div>
            <p style="font-size: 9px; color: #6b7280; font-weight: 900; text-transform: uppercase; margin: 0;">Chủ sở hữu hiện tại</p>
            <p style="font-size: 13px; color: #fff; font-family: monospace; margin: 2px 0;">0x8429...d81e (Ví của bạn)</p>
        </div>

        <!-- Mốc 2: Nghệ sĩ -->
        <div style="position: relative;">
            <div style="position: absolute; left: -45px; top: 0; width: 36px; height: 36px; background: #000; border: 1px solid #06b6d4; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 15px rgba(6,182,212,0.3);">
                <i class="fas fa-feather-alt" style="color: #06b6d4; font-size: 12px;"></i>
            </div>
            <p style="font-size: 9px; color: #6b7280; font-weight: 900; text-transform: uppercase; margin: 0;">Khởi sinh tác phẩm</p>
            <p style="font-size: 13px; color: #06b6d4; font-weight: 900; margin: 2px 0;">NGHỆ SĨ MẠNH HÙNG</p>
            <p style="font-size: 10px; color: #9ca3af; font-style: italic; margin: 0;">Xác thực bản quyền gốc vĩnh viễn</p>
        </div>
    </div>
</div>
<!-- KẾT THÚC ĐOẠN HÀNH TRÌNH DI SẢN -->


                <!-- Nút Mua -->
                <button class="w-full bg-white text-black hover:bg-cyan-500 hover:text-white font-black py-5 rounded-2xl transition-all duration-500 shadow-xl text-xs uppercase tracking-widest">
                    Sở hữu tác phẩm này
                </button>
            </div>
        </div>
    </div>

<script>
function copyLink() {
    navigator.clipboard.writeText(window.location.href);
    alert("Đã sao chép đường dẫn tác phẩm!");
}
</script>    
</body>
</html>

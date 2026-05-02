<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}
$total_songs = $total_songs ?? 0;
$balance = $balance ?? 0;
$songs =  $songs ?? [];
require_once 'db.php';
require_once 'finance_logic.php';
set_time_limit(5);

// TẠM DEBUG: bỏ exit để trang tiếp tục chạy
// echo isset($_SESSION['user']) ? 'user có' : 'user KHÔNG có';
// echo '<pre>'; print_r($_SESSION); echo '</pre>';
// exit;

// GỠ KHÓA TẠM: bỏ kiểm tra admin
// if (empty($_SESSION['user']) || $_SESSION['user'] !== 'hunglouis') {
//   die("Bạn chưa được phép vào trang này.");
// }

// Cho chạy tiếp dù không có session
$user = $_SESSION['user'] ?? '';
$user_display = $user;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quỳnh Hương - Genesis Edition</title>
    <!-- Link làm đẹp giao diện -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cloudflare.com" rel="stylesheet">
    <style>
        body { background: radial-gradient(circle at top right, #0891b2, #064e3b, #020617); min-height: 100vh; color: white; }
        .glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(6, 182, 212, 0.2); }
    </style>
</head>
<body class="p-5 md:p-10">
     <?php if(file_exists('navbar.php')) include 'navbar.php'; ?> <!-- Chèn thanh điều hướng -->
     <!-- Toàn bộ phần vòng lặp foreach của bạn nằm ở đây -->
   

     
<!-- ... Phía trên là Thanh Menu (Navbar) ... -->

<div class="container mx-auto px-6 py-10">
    <div class="flex items-center gap-4 mb-8">
        <div class="h-10 w-2 bg-cyan-500 rounded-full"></div>
        <h1 class="text-3xl font-black text-white uppercase tracking-tighter">
            Quản lý <span class="text-cyan-400">Hoa Quỳnh</span>
        </h1>
    </div>

    <!-- ĐÂY LÀ ĐOẠN MÃ CÁC "Ô CỬA SỔ" NHẬP LIỆU -->
    <section class="glass p-8 rounded-[2.5rem] shadow-2xl mb-12 border border-cyan-500/20">
        <h2 class="text-xl font-bold mb-6 text-white flex items-center gap-3">
            <i class="fas fa-plus-circle text-cyan-400"></i>
            Thêm tác phẩm mới vào hệ thống
        </h2>

        <form action="api/save_song.php" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Ô 1: Tên tác phẩm -->
            <div>
                <label class="block text-[10px] uppercase tracking-widest text-cyan-500 mb-2 ml-2">Tên tác phẩm</label>
                <input type="text" name="title" placeholder="Ví dụ: Quỳnh Hương" required
                       class="w-full bg-black/40 border border-white/10 p-4 rounded-2xl text-white focus:border-cyan-500 outline-none transition">
            </div>

            <!-- Ô 2: DÁN LINK URL ẢNH (Quan trọng nhất) -->
            <div>
                <label class="block text-[10px] uppercase tracking-widest text-cyan-500 mb-2 ml-2">Link URL Ảnh (Từ Supabase)</label>
                <input type="text" name="image_path" placeholder="https://..." required
                       class="w-full bg-black/40 border border-white/10 p-4 rounded-2xl text-white focus:border-cyan-500 outline-none transition">
            </div>

            <!-- Ô 3: Giá hoặc ID -->
            <div>
                <label class="block text-[10px] uppercase tracking-widest text-cyan-500 mb-2 ml-2">Giá NFT (ETH)</label>
                <input type="text" name="price" placeholder="0.05"
                       class="w-full bg-black/40 border border-white/10 p-4 rounded-2xl text-white focus:border-cyan-500 outline-none transition">
            </div>

            <!-- Nút bấm lưu -->
            <div class="md:col-span-3">
                <button type="submit" class="w-full bg-cyan-500 hover:bg-cyan-400 text-white font-black py-4 rounded-2xl shadow-lg shadow-cyan-500/40 transition transform active:scale-95">
                    LƯU VÀO CƠ SỞ DỮ LIỆU
                </button>
            </div>
        </form>
    </section>

    <!-- ... Phía dưới là danh sách các bài hát hiện có ... -->
</div>

    <div class="sidebar">
        <h3>MENU ADMIN</h3>
        <hr>
        <p><a href="marketplace.php" style="color:white; text-decoration:none;">🛒 Xem Chợ Nhạc</a></p>
        <p><a href="thongke.php" style="color:white; text-decoration:none;">📊 Thống kê</a></p>
        <p><a href="logout.php" style="color:red; text-decoration:none;">🚪 Thoát</a></p>
    </div>

    <div class="main-content">
        <h1>🎙️ TRUNG TÂM ĐIỀU HÀNH STUDIO</h1>
        
        <div class="stat-card">
  <div style="color: #8b949e;">Tổng tác phẩm</div>
  <div style="font-size: 24px; font-weight: bold;">
    <?php echo $total_songs ?? 0; ?>
  </div>
</div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên tác phẩm</th>
                    <th>Giá niêm yết</th>
                    <th>File Demo (Supabase)</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                    <th>Cập nhật Ảnh (Link Supabase)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($songs as $s) : ?>
  <tr>
    <td>#<?php echo $s['id']; ?></td>
    <td style="font-weight: bold;"><?php echo $s['name']; ?></td>
    <td><?php echo number_format($s['price']); ?> PHP</td>
    <td style="font-size: 11px; color: #8b949e;"><?php echo $s['demo_file']; ?></td>
    <td><span class="badge-active">On Cloud</span></td>
    <td>
      <a href="player.php?id=<?php echo $s['id']; ?>" class="btn-edit">Nghe thử</a> | 
      <a href="#" class="btn-edit" style="color: #d73a49;">Gỡ bỏ</a>
    </td>

    <td>
      <form method="POST" action="update_image.php" style="display: flex; gap: 5px;">
        <input type="hidden" name="song_id" value="<?php echo $s['id']; ?>">
        <input
          type="text"
          name="new_image_url"
          placeholder="Dán link ảnh..."
          style="background: #0d1117; color: white; border: 1px solid #30363d; padding: 5px; border-radius: 4px; font-size: 11px;"
        >
        <button type="submit" style="background: #238636; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 11px;">
          Lưu
        </button>
      </form>
    </td>

    <td>
      <?php if(!empty($s['image_url'])): ?>
        <img src="<?php echo $s['image_url']; ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px; margin-right: 10px; border: 1px solid #30363d;">
      <?php else: ?>
        <div style="width: 50px; height: 50px; background: #161b22; display: inline-block; vertical-align: middle; border-radius: 5px; margin-right: 10px;"></div>
      <?php endif; ?>

      <span style="font-weight: bold;"><?php echo $s['title']; ?></span>
    </td>
  </tr>
<?php endforeach; ?>
            </tbody>
        </table>
    </div>


    
    echo "<script>alert('Đã đồng bộ thành công: OpenSea <=> Polygon <=> Supabase <=> Localhost');
    </script>";
}

</body>
</html>

<?php
session_start();
require_once 'db.php';
require 'finance_logic.php';
if (!isset($_SESSION['username'])) {
    header("Location: dangnhap.php");
    exit;
}
$display_name = $_SESSION['full_name']; // Sẽ hiện "Nhạc sĩ Mạnh Hùng" nếu user là hunglouis
?>


// Bảo mật: Chỉ anh (Admin) mới vào được trang này
//if (!isset($_SESSION['user']) || $_SESSION['user'] !== 'hunglouis_manhhung') {
    //die("Truy cập bị từ chối! Trang này chỉ dành cho Nhạc sĩ Mạnh Hùng.")}

// 1. Lấy thống kê tổng quan
$res_total = mysqli_query($conn, "SELECT COUNT(*) as total FROM music_collection");
$total_songs = mysqli_fetch_assoc($res_total)['total'];

$balance = getBalance($conn, $_SESSION['user']);

// 2. Lấy danh sách nhạc để quản lý
$songs = mysqli_query($conn, "SELECT * FROM music_collection ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quỳnh Hương - Genesis Edition</title>
    <!-- Link làm đẹp giao diện -->
    <script src="https://tailwindcss.com"></script>
    <link href="https://cloudflare.com" rel="stylesheet">
    <style>
        body { background: radial-gradient(circle at top right, #0891b2, #064e3b, #020617); min-height: 100vh; color: white; }
        .glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(6, 182, 212, 0.2); }
    </style>
</head>
<body class="p-10">
    <!-- Toàn bộ phần vòng lặp foreach của bạn nằm ở đây -->
>
    <?php include 'navbar.php'; ?> <!-- Chèn thanh điều hướng -->
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
            <div style="font-size: 24px; font-weight: bold;"><?php echo $total_songs; ?></div>
        </div>
        
        <div class="stat-card">
            <div style="color: #8b949e;">Số dư ví Polygon</div>
            <div style="font-size: 24px; font-weight: bold; color: #f2e711;"><?php echo number_format($balance); ?> PHP</div>
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
                <?php while($s = mysqli_fetch_assoc($songs)): ?>
                <tr>
                    <td>#<?php echo $s['id']; ?></td>
                    <td style="font-weight: bold;"><?php echo $s['title']; ?></td>
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
                    <input type="text" name="new_image_url" placeholder="Dán link ảnh..." 
                    style="background: #0d1117; color: white; border: 1px solid #30363d; padding: 5px; border-radius: 4px; font-size: 11px;">
                    <button type="submit" style="background: #238636; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 11px;">
                    Lưu
                    </button>
                    </form>
                    </td>
                    <td>
        <!-- Nếu có link ảnh thì hiện ảnh, không thì hiện ô trống -->
                    <?php if(!empty($s['image_url'])): ?>
                    <img src="<?php echo $s['image_url']; ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px; margin-right: 10px; border: 1px solid #30363d;">
                    <?php else: ?>
                    <div style="width: 50px; height: 50px; background: #161b22; display: inline-block; vertical-align: middle; border-radius: 5px; margin-right: 10px;"></div>
                    <?php endif; ?>
    
                    <span style="font-weight: bold;"><?php echo $s['title']; ?></span>
                    </td>

                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php
// Thông tin này lấy từ mục Database Settings trên Supabase của anh
$host = "://supabase.com"; // Địa chỉ host Supabase
$port = "5432";
$dbname = "postgres";
$user = "postgres.hmvvjjiiaelcsfqgxbxv"; // User cụ thể của anh
$password = "MẬT_KHẨU_CỦA_ANH";

// Kết nối PostgreSQL (Supabase dùng Postgres thay vì MySQL)
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

if (!$conn) {
    die("Không thể kết nối đến trung tâm Supabase!");
}
?>
// Đoạn code giả lập logic đồng bộ đa nền tảng
if (isset($_POST['sync_all'])) {
    // 1. Kết nối Polygon để lấy danh sách NFT hiện có (thông qua Contract của anh)
    // 2. Kết nối Pinata để lấy link nhạc chất lượng cao
    // 3. Đổ tất cả vào Supabase
    
    // Giả lập lệnh cập nhật hàng loạt
    $sql_sync = "UPDATE music_collection SET status = 'Synced', last_update = NOW()";
    mysqli_query($conn, $sql_sync);
    
    echo "<script>alert('Đã đồng bộ thành công: OpenSea <=> Polygon <=> Supabase <=> Localhost');</script>";
}

</body>
</html>

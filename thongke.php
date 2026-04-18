<?php
require_once 'db.php';
require 'finance_logic.php';

// 1. Lấy danh sách tất cả người dùng hiện có
$users_res = ("username FROM users");

$leaderboard = [];

($u = $users_res) {
    $name = $u['username'];
    // Dùng hàm getBalance chúng ta đã viết ở file trước để tính tiền
    $balance = getBalance($name);
    $leaderboard[] = ['name' => $name, 'balance' => $balance];
}

// 2. Sắp xếp danh sách: ai nhiều tiền hơn đứng trên (Sắp xếp giảm dần)
usort($leaderboard, function($a, $b) {
    return $b['balance'] <=> $a['balance'];
});

// 3. Thống kê chung
$total_blocks = callSupabase("SELECT COUNT(*) as total FROM blockchain");
$block_count = $total_blocks[0]['total'];
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
<body> 
    <?php include 'navbar.php'; ?> <!-- Chèn thanh điều hướng -->
class="p-10">
    <!-- Toàn bộ phần vòng lặp foreach của bạn nằm ở đây -->
       
<div class="stats-container">
    <h1>📊 BẢNG VÀNG ĐẠI GIA PHP</h1>
    
    <div style="display: flex; justify-content: space-around; margin-bottom: 20px;">
        <div>Tổng số khối: <span class="badge"><?php echo $block_count; ?> Blocks</span></div>
        <div>Hệ thống: <span class="badge" style="background: #8957e5;">Active</span></div>
    </div>

    <table>
        <tr>
            <th>Hạng</th>
            <th>Người dùng</th>
            <th>Số dư (PHP Coin)</th>
        </tr>
        <?php foreach ($leaderboard as $index => $player): ?>
        <tr class="<?php echo $index == 0 ? 'rank-1' : ''; ?>">
            <td>#<?php echo $index + 1; ?></td>
            <td><?php echo $player['name']; ?></td>
            <td><?php echo number_format($player['balance']); ?> 🪙</td>
        </tr>
        <?php endforeach; ?>
    </table>
    <p style="text-align: center; margin-top: 20px;">
        <a href="wallet.php" style="color: #58a6ff; text-decoration: none;">← Quay lại Ví của bạn</a>
    </p>
</div>

</body>
</html>

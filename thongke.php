<?php
require 'db.php';
require 'finance_logic.php';

// 1. Lấy danh sách tất cả người dùng hiện có
$users_res = mysqli_query($conn, "SELECT username FROM users");

$leaderboard = [];

while ($u = mysqli_fetch_assoc($users_res)) {
    $name = $u['username'];
    // Dùng hàm getBalance chúng ta đã viết ở file trước để tính tiền
    $balance = getBalance($conn, $name);
    $leaderboard[] = ['name' => $name, 'balance' => $balance];
}

// 2. Sắp xếp danh sách: ai nhiều tiền hơn đứng trên (Sắp xếp giảm dần)
usort($leaderboard, function($a, $b) {
    return $b['balance'] <=> $a['balance'];
});

// 3. Thống kê chung
$total_blocks = mysqli_query($conn, "SELECT COUNT(*) as total FROM blockchain");
$block_count = mysqli_fetch_assoc($total_blocks)['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Blockchain Stats</title>
    <style>
        body { background: #0b0f19; color: #fff; font-family: 'Segoe UI', sans-serif; padding: 40px; }
        .stats-container { max-width: 600px; margin: auto; background: #161b22; padding: 20px; border-radius: 10px; border: 1px solid #30363d; }
        h1 { color: #58a6ff; text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #30363d; text-align: left; }
        th { color: #8b949e; }
        .rank-1 { color: #f2e711; font-weight: bold; } /* Màu vàng cho đại gia số 1 */
        .badge { background: #238636; padding: 5px 10px; border-radius: 20px; font-size: 12px; }
    </style>
</head>
<body>
        <?php include 'navbar.php'; ?> <!-- Chèn thanh điều hướng -->

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

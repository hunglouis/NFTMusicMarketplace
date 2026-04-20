<?php
require_once 'db.php';
require 'finance_logic.php';

// --- Helpers: để trang KHÔNG chết khi query/hàm balance lỗi ---
function safe_get_balance($name) {
    try {
        if (function_exists('getBalance')) {
            return getBalance($name);
        }
    } catch (Throwable $e) {
        // bỏ qua để render tiếp
    }
    return 0;
}

function safe_call_supabase($sql) {
    try {
        if (function_exists('callSupabase')) {
            $res = callSupabase($sql);
            return is_array($res) ? $res : [];
        }
    } catch (Throwable $e) {
        // bỏ qua để render tiếp
    }
    return [];
}

// 1) Lấy danh sách tất cả người dùng hiện có
$leaderboard = [];

$users = safe_call_supabase("SELECT username FROM users");

foreach ($users as $u) {
    $name = $u['username'] ?? null;
    if (!$name) continue;

    $balance = safe_get_balance($name);
    $leaderboard[] = ['name' => $name, 'balance' => $balance];
}

// 2) Sắp xếp giảm dần theo balance
usort($leaderboard, function($a, $b) {
    return ($b['balance'] ?? 0) <=> ($a['balance'] ?? 0);
});

// 3) Thống kê chung
$total_blocks = safe_call_supabase("SELECT COUNT(*) as total FROM blockchain");
$block_count = 0;
if (!empty($total_blocks) && isset($total_blocks[0]['total'])) {
    $block_count = (int)$total_blocks[0]['total'];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quỳnh Hương - Genesis Edition</title>
    <script src="https://tailwindcss.com"></script>
    <link href="https://cloudflare.com" rel="stylesheet">
    <style>
        body { background: radial-gradient(circle at top right, #0891b2, #064e3b, #020617); min-height: 100vh; color: white; }
        .glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(6, 182, 212, 0.2); }
        table { width: 100%; border-collapse: collapse; margin-top: 14px; }
        th, td { padding: 10px; border-bottom: 1px solid rgba(255,255,255,0.12); text-align: left; }
        .rank-1 { background: rgba(137, 87, 229, 0.18); }
        .badge { padding: 6px 10px; border-radius: 999px; background: rgba(255,255,255,0.08); }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="p-10">
        <div class="stats-container">
            <h1>📊 BẢNG VÀNG ĐẠI GIA PHP</h1>

            <div style="display: flex; justify-content: space-around; margin-bottom: 20px;">
                <div>Tổng số khối: <span class="badge"><?php echo htmlspecialchars((string)$block_count); ?> Blocks</span></div>
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
                    <td><?php echo htmlspecialchars((string)($player['name'] ?? '')); ?></td>
                    <td>
                        <?php echo number_format((float)($player['balance'] ?? 0)); ?> 🪙
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>

            <p style="text-align: center; margin-top: 20px;">
                <a href="wallet.php" style="color: #58a6ff; text-decoration: none;">← Quay lại Ví của bạn</a>
            </p>
        </div>
    </div>
</body>
</html>
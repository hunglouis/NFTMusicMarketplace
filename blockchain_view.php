<?php
session_start();
require_once 'db.php';

// --- 1. PHẦN LOGIC (TÍNH TOÁN) ---
function calculateHash($index, $prevHash, $username, $content) {
    return hash('sha256', $index . $prevHash . $username . $content);
}
function getLatestHash() {
    $rows = callSupabase("SELECT hash FROM blockchain ORDER BY id DESC LIMIT 1");
    if (!empty($rows) && isset($rows[0]['hash'])) {
        return $rows[0]['hash'];
    }
    return "00000000000000000000000000000000";
}

// Xử lý khi nhấn nút "Khai thác khối"
if (isset($_POST['send_block'])) {
    $user = $_SESSION['user'] ?? 'Ẩn danh';
    $noidung = (string)($_POST['noidung'] ?? '');

    // Lấy prevHash của khối mới nhất (id lớn nhất)
    $latest = callSupabase("blockchain?select=prev_hash,id&order=id.desc&limit=1");

    $prevHash = $latest[0]['prev_hash'] ?? "00000000000000000000000000000000";
    $latestId = (int)($latest[0]['id'] ?? 0);
    $next_id = $latestId + 1;

    $hash = calculateHash($next_id, $prevHash, $user, $noidung);

    // Insert block (REST: POST /rest/v1/blockchain)
    callSupabase("blockchain", "POST", [
        "username" => $user,
        "content"  => $noidung,
        "prev_hash"=> $prevHash,
        "hash"     => $hash
    ]);
}

// Xử lý khi nhấn nút "Xác minh"
$error_blocks = [];
if (isset($_POST['verify_all'])) {
    $temp_prev = "00000000000000000000000000000000";

    // Lấy toàn bộ blocks theo thứ tự tăng dần id
    $blocks_res = callSupabase("blockchain?select=id,prev_hash,username,content,hash&order=id.asc");

    if (is_array($blocks_res)) {
        foreach ($blocks_res as $b) {
            $id = $b['id'] ?? null;

            $re_hash = calculateHash(
                $id,
                $b['prev_hash'] ?? '',
                $b['username'] ?? '',
                $b['content'] ?? ''
            );

            if (($b['hash'] ?? '') !== $re_hash || ($b['prev_hash'] ?? '') !== $temp_prev) {
                $error_blocks[] = $id;
            }

            $temp_prev = $b['hash'] ?? $temp_prev;
        }
    }
}
?>

<!-- --- 2. PHẦN GIAO DIỆN --- -->
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

        <?php include 'navbar.php'; ?> <!-- Chèn thanh điều hướng -->

    <h1>🔗 HỆ THỐNG BLOCKCHAIN CỦA BẠN</h1>

    <!-- Form nhập liệu -->
    <div style="background: #222; padding: 20px; border-radius: 5px;">
        <form method="POST">
            <input type="text" name="noidung" placeholder="Nhập lời nhắn vào khối..." required style="width: 300px;">
            <button type="submit" name="send_block" style="background: #0f0; color: #000; font-weight: bold; cursor:pointer;">MINE BLOCK (Tạo khối)</button>
            <button type="submit" name="verify_all" style="background: yellow; cursor:pointer;">VERIFY (Xác minh)</button>
        </form>
        <?php 
        if (isset($_POST['verify_all'])) {
            echo empty($error_blocks) ? "<p style='color:lime;'>✅ Chuỗi an toàn!</p>" : "<p style='color:red;'>❌ CẢNH BÁO: Khối số ".implode(", ", $error_blocks)." bị lỗi!</p>";
        }
        ?>
    </div>

    <!-- Hiển thị danh sách khối -->
<div style="margin-top: 30px;">
    <?php
    $blocks = [];
    try {
        $blocks = callSupabase("SELECT * FROM blockchain ORDER BY id DESC");
        if (!is_array($blocks)) $blocks = [];
    } catch (Throwable $e) {
        $blocks = [];
    }
    ?>

    <?php if (empty($blocks)): ?>
        <div class="block" style="opacity:0.85;">
            <strong>Không có dữ liệu blockchain.</strong>
        </div>
    <?php else: ?>
        <?php foreach ($blocks as $b): ?>
            <?php
                $id = $b['id'] ?? null;
                $is_corrupt = is_array($error_blocks) && $id !== null && in_array($id, $error_blocks, true);
            ?>
            <div class="block <?php echo $is_corrupt ? 'error' : ''; ?>">
                <strong>KHỐI #<?php echo htmlspecialchars((string)$id); ?></strong>
                <?php echo $is_corrupt ? "[DỮ LIỆU SAI LỆCH!]" : "[OK]"; ?><br>

                <span>Người gửi: <?php echo htmlspecialchars((string)($b['username'] ?? '')); ?></span><br>

                <span>
                    Nội dung:
                    <span style="color: white;"><?php echo htmlspecialchars((string)($b['content'] ?? '')); ?></span>
                </span><br>

                <div class="hash-text">
                    Prev: <?php echo htmlspecialchars((string)($b['prev_hash'] ?? '')); ?>
                </div>

                <div class="hash-text" style="color: yellow;">
                    Hash: <?php echo htmlspecialchars((string)($b['hash'] ?? '')); ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
</body>
</html>

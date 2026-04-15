<?php
session_start();
require 'db.php';

// --- 1. PHẦN LOGIC (TÍNH TOÁN) ---
function calculateHash($index, $prevHash, $username, $content) {
    return hash('sha256', $index . $prevHash . $username . $content);
}

function getLatestHash($conn) {
    $res = mysqli_query($conn, "SELECT hash FROM blockchain ORDER BY id DESC LIMIT 1");
    $row = mysqli_fetch_assoc($res);
    return $row ? $row['hash'] : "00000000000000000000000000000000";
}

// Xử lý khi nhấn nút "Khai thác khối"
if (isset($_POST['send_block'])) {
    $user = $_SESSION['user'] ?? 'Ẩn danh';
    $noidung = mysqli_real_escape_string($conn, $_POST['noidung']);
    $prevHash = getLatestHash($conn);
    
    $res_id = mysqli_query($conn, "SELECT id FROM blockchain ORDER BY id DESC LIMIT 1");
    $next_id = (mysqli_fetch_assoc($res_id)['id'] ?? 0) + 1;
    
    $hash = calculateHash($next_id, $prevHash, $user, $noidung);
    mysqli_query($conn, "INSERT INTO blockchain (username, content, prev_hash, hash) VALUES ('$user', '$noidung', '$prevHash', '$hash')");
}

// Xử lý khi nhấn nút "Xác minh"
$error_blocks = [];
if (isset($_POST['verify_all'])) {
    $blocks_res = mysqli_query($conn, "SELECT * FROM blockchain ORDER BY id ASC");
    $temp_prev = "00000000000000000000000000000000";
    while ($b = mysqli_fetch_assoc($blocks_res)) {
        $re_hash = calculateHash($b['id'], $b['prev_hash'], $b['username'], $b['content']);
        if ($b['hash'] !== $re_hash || $b['prev_hash'] !== $temp_prev) {
            $error_blocks[] = $b['id'];
        }
        $temp_prev = $b['hash'];
    }
}
?>

<!-- --- 2. PHẦN GIAO DIỆN --- -->
<!DOCTYPE html>
<html>
<head>
    <title>Blockchain Pro</title>
    <style>
        body { background: #121212; color: #0f0; font-family: 'Courier New', Courier, monospace; padding: 20px; }
        .block { border: 1px solid #333; padding: 15px; margin: 15px 0; border-radius: 5px; background: #1e1e1e; }
        .error { border-color: red !important; color: red; }
        input, button { padding: 10px; font-family: monospace; }
        .hash-text { font-size: 0.8em; color: #888; word-break: break-all; }
    </style>
</head>
<body>
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
        $all_blocks = mysqli_query($conn, "SELECT * FROM blockchain ORDER BY id DESC");
        while($b = mysqli_fetch_assoc($all_blocks)): 
            $is_corrupt = in_array($b['id'], $error_blocks);
        ?>
            <div class="block <?php echo $is_corrupt ? 'error' : ''; ?>">
                <strong>KHỐI #<?php echo $b['id']; ?></strong> <?php echo $is_corrupt ? "[DỮ LIỆU SAI LỆCH!]" : "[OK]"; ?><br>
                <span>Người gửi: <?php echo $b['username']; ?></span><br>
                <span>Nội dung: <span style="color: white;"><?php echo $b['content']; ?></span></span><br>
                <div class="hash-text">Prev: <?php echo $b['prev_hash']; ?></div>
                <div class="hash-text" style="color: yellow;">Hash: <?php echo $b['hash']; ?></div>
            </div>
        <?php endwhile; ?>
    </div>
</body>
</html>

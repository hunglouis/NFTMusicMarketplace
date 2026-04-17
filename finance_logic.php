<?php
require_once 'db.php';

// 1. Hàm tính toán mã Hash cho giao dịch
function calculateFinanceHash($index, $prevHash, $sender, $receiver, $amount) {
    return hash('sha256', $index . $prevHash . $sender . $receiver . $amount);
}

// 2. Hàm lấy mã Hash mới nhất từ chuỗi khối
function getLatestHash($conn) {
    $res = mysqli_query($conn, "SELECT hash FROM blockchain ORDER BY id DESC LIMIT 1");
    $row = mysqli_fetch_assoc($res);
    return $row ? $row['hash'] : "00000000000000000000000000000000";
}

// 3. Hàm tính số dư (đã bao gồm tặng 1000 coin và tiền đào coin)
function getBalance($conn, $username) {
    // Tiền nhận
    $res_in = mysqli_query($conn, "SELECT SUM(amount) AS total FROM blockchain WHERE receiver='$username'");
    $in = mysqli_fetch_assoc($res_in)['total'] ?? 0;

    // Tiền gửi
    $res_out = mysqli_query($conn, "SELECT SUM(amount) AS total FROM blockchain WHERE sender='$username'");
    $out = mysqli_fetch_assoc($res_out)['total'] ?? 0;

    // Tiền thưởng đào coin (Mỗi khối do mình tạo ra được 50)
    $res_mine = mysqli_query($conn, "SELECT COUNT(*) AS total FROM blockchain WHERE sender='$username'");
    $mining_reward = (mysqli_fetch_assoc($res_mine)['total'] ?? 0) * 50;

    return (1000 + $in + $mining_reward) - $out; 
}
?>

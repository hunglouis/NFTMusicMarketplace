<?php
require_once 'db.php';

// 1. Hàm tính toán mã Hash cho giao dịch
function calculateFinanceHash($index, $prevHash, $sender, $receiver, $amount) {
    return hash('sha256', $index . $prevHash . $sender . $receiver . $amount);
}

// 2. Hàm lấy mã Hash mới nhất từ chuỗi khối
function getLatestHash($conn) {
    $res = callsupabase("blockchain?order_by=id.desc&limit=1");
    
    return $row ? $row['hash'] : "00000000000000000000000000000000";
}

// 3. Hàm tính số dư (đã bao gồm tặng 1000 coin và tiền đào coin)
function getBalance($username) {
    // Tiền nhận
    $res_in = callsupabase("blockchain?order_by=id.desc&limit=1");
    $in = callSupabase($res_in)['total'] ?? 0;

    // Tiền gửi
    $res_out = callsupabase("blockchain?order_by=id.desc&limit=1");
    $out = callSupabase($res_out)['total'] ?? 0;

    // Tiền thưởng đào coin (Mỗi khối do mình tạo ra được 50)
    $res_mine = callsupabase("blockchain?order_by=id.desc&limit=1");
    $mining_reward = (callSupabase($res_mine)['total'] ?? 0) * 50;

    return (1000 + $in + $mining_reward) - $out; 
}
?>

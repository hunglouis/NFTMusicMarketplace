<?php
require_once 'db.php';
require 'blockchain_logic.php';

function verifyBlockchain($conn) {
    $blocks = mysqli_query($conn, "SELECT * FROM blockchain ORDER BY id ASC");
    $prev_hash_to_check = "00000000000000000000000000000000";
    $invalid_blocks = [];

    while ($b = mysqli_fetch_assoc($blocks)) {
        // 1. Kiểm tra xem prev_hash của khối này có khớp với hash của khối trước không
        if ($b['prev_hash'] !== $prev_hash_to_check) {
            $invalid_blocks[] = $b['id'];
        }

        // 2. Tính toán lại hash dựa trên dữ liệu hiện tại trong DB
        $recalculated_hash = calculateHash($b['id'], $b['prev_hash'], $b['username'], $b['content']);
        
        // Nếu hash lưu trong DB khác với hash vừa tính lại => Dữ liệu đã bị sửa!
        if ($b['hash'] !== $recalculated_hash) {
            if (!in_array($b['id'], $invalid_blocks)) {
                $invalid_blocks[] = $b['id'];
            }
        }

        $prev_hash_to_check = $b['hash'];
    }
    return $invalid_blocks;
}
?>

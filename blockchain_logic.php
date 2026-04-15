<?php
function calculateHash($index, $prevHash, $username, $content) {
    // SHA256 là thuật toán mã hóa tạo ra chuỗi 64 ký tự không thể giải mã ngược
    return hash('sha256', $index . $prevHash . $username . $content);
}

function getLatestHash($conn) {
    $res = mysqli_query($conn, "SELECT hash FROM blockchain ORDER BY id DESC LIMIT 1");
    $row = mysqli_fetch_assoc($res);
    return $row ? $row['hash'] : "00000000000000000000000000000000"; // Khối đầu tiên (Genesis)
}
?>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$data = json_decode(file_get_contents('php://input'), true);

// Chỉ cập nhật nếu chuỗi ví gửi lên có dữ liệu thực sự và độ dài chuẩn ví Web3
if (isset($data['wallet']) && !empty($data['wallet']) && strlen($data['wallet']) > 10) {
    $_SESSION['user_wallet'] = trim($data['wallet']);
    echo json_encode(['status' => 'success', 'wallet' => $_SESSION['user_wallet']]);
} else {
    // Nếu dữ liệu trống, giữ nguyên ví cũ trong session chứ không xóa bỏ
    echo json_encode(['status' => 'ignored', 'message' => 'Giữ nguyên ví cũ do dữ liệu gửi lên trống']);
}
?>

<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        // 1. Gọi Supabase lấy thông tin user theo username
        $path = "users?username=eq." . urlencode($username) . "&select=*";
        $response = callSupabase($path, 'GET');

        if (is_array($response) && !empty($response)) {
            $user = $response[0]; // Lấy thằng đầu tiên tìm thấy

            // 2. Kiểm tra mật khẩu (Sử dụng password_verify để bảo mật)
            // Nếu bạn đang lưu mật khẩu dạng chữ thường (không mã hóa), dùng: if ($password == $user['password'])
            if (password_verify($password, $user['password']) || $password == $user['password']) {
                
                // 3. Đăng nhập thành công - Lưu Session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_avatar'] = $user['avatar'] ?? 'default-avatar.png';
                $_SESSION['user_wallet'] = $user['wallet_address'] ?? 'Chưa kết nối';
                
                // Gán tên hiển thị đặc biệt cho bạn
                $_SESSION['full_name'] = ($user['username'] == 'hunglouis') ? 'Nhạc sĩ Mạnh Hùng' : $user['username'];

                header("Location: dashboard.php");
                exit;
            } else {
                $error = "Mật khẩu không chính xác!";
            }
        } else {
            $error = "Tài khoản không tồn tại!";
        }
    } else {
        $error = "Vui lòng nhập đầy đủ thông tin!";
    }
}
?>
<!-- Giữ nguyên phần HTML giao diện màu xanh bên dưới của bạn -->


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập - Louis Music</title>
    <script src="https://tailwindcss.com"></script>
    <link href="https://cloudflare.com" rel="stylesheet">
    <style>
        body { background: radial-gradient(circle at top left, #0891b2, #020617); min-height: 100vh; display: flex; align-items: center; justify-content: center; color: white; }
        .glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(15px); border: 1px solid rgba(6, 182, 212, 0.2); }
    </style>
</head>
<body>
    <div class="glass p-10 rounded-[2.5rem] w-full max-w-md shadow-2xl">
        <div class="text-center mb-8">
            <i class="fas fa-unlock-alt text-cyan-400 text-4xl mb-4"></i>
            <h1 class="text-2xl font-bold">LOUIS MUSIC LOGIN</h1>
            <p class="text-cyan-200/60 text-sm">Chào mừng Nhạc sĩ quay trở lại</p>
        </div>

        <?php if($error): ?>
            <div class="bg-red-500/20 border border-red-500 text-red-200 p-3 rounded-xl mb-6 text-sm">
                <i class="fas fa-exclamation-circle mr-2"></i> <?= $error ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div>
                <label class="block text-xs uppercase tracking-widest text-cyan-400 mb-2 ml-1">Username</label>
                <input type="text" name="username" required class="w-full bg-white/5 border border-white/10 p-4 rounded-2xl focus:outline-none focus:border-cyan-500 transition text-white">
            </div>
            <div>
                <label class="block text-xs uppercase tracking-widest text-cyan-400 mb-2 ml-1">Password</label>
                <input type="password" name="password" required class="w-full bg-white/5 border border-white/10 p-4 rounded-2xl focus:outline-none focus:border-cyan-500 transition text-white">
            </div>
            <button type="submit" class="w-full bg-cyan-500 hover:bg-cyan-400 text-white font-bold py-4 rounded-2xl shadow-lg shadow-cyan-500/30 transition transform hover:scale-[1.02]">
                ĐĂNG NHẬP NGAY
            </button>
        </form>
    </div>
</body>
</html>
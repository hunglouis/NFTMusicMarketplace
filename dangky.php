<?php
require_once 'db.php';
error_reporting(0); // Tắt thông báo lỗi hệ thống để giao diện sạch sẽ

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        // 1. Chuẩn bị dữ liệu để gửi lên Supabase
        $newUser = [
            "username" => $username,
            "email"    => $email,
            "password" => $password, // Sau này nên dùng password_hash để bảo mật hơn
            "avatar"   => "default-avatar.png"
        ];

        // 2. Gọi hàm callSupabase để chèn (POST) người dùng mới vào bảng users
        $response = callSupabase('users', 'POST', $newUser);

        if (isset($response['error'])) {
            $error_msg = "Lỗi đăng ký: " . $response['error']['message'];
        } else {
            // Đăng ký thành công, chuyển về trang đăng nhập
            header("Location: dangnhap.php?success=1");
            exit;
        }
    } else {
        $error_msg = "Vui lòng nhập đầy đủ Username và Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký - Louis Music NFT</title>
    <script src="https://tailwindcss.com"></script>
    <style>
        body { background: radial-gradient(circle at bottom right, #0891b2, #020617); min-height: 100vh; display: flex; align-items: center; justify-content: center; color: white; }
        .glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(20px); border: 1px solid rgba(6, 182, 212, 0.2); border-radius: 2.5rem; }
    </style>
</head>
<body>
    <div class="glass p-10 w-full max-w-md shadow-2xl">
        <h1 class="text-3xl font-black text-center mb-6 uppercase tracking-widest text-cyan-400">Đăng ký</h1>
        
        <?php if(isset($error_msg)) echo "<p class='text-red-400 text-sm mb-4 text-center'>$error_msg</p>"; ?>

        <form method="POST" class="space-y-6">
            <input type="text" name="username" placeholder="Tên đăng nhập" required class="w-full bg-white/5 border border-white/10 p-4 rounded-2xl outline-none focus:border-cyan-500 transition">
            <input type="email" name="email" placeholder="Email của bạn" required class="w-full bg-white/5 border border-white/10 p-4 rounded-2xl outline-none focus:border-cyan-500 transition">
            <input type="password" name="password" placeholder="Mật khẩu" required class="w-full bg-white/5 border border-white/10 p-4 rounded-2xl outline-none focus:border-cyan-500 transition">
            
            <button type="submit" class="w-full bg-cyan-500 hover:bg-cyan-400 text-white font-bold py-4 rounded-2xl shadow-lg shadow-cyan-500/30 transition transform hover:scale-[1.02]">
                TẠO TÀI KHOẢN NGAY
            </button>
        </form>
        <p class="text-center mt-6 text-sm text-gray-400">
            Đã có tài khoản? <a href="dangnhap.php" class="text-cyan-400 hover:underline">Đăng nhập ngay</a>
        </p>
    </div>
</body>
</html>

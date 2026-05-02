<?php
session_start();
error_reporting(0); // Dòng này sẽ xóa sạch đống chữ trắng chạy ngang màn hình
ini_set('display_errors', 0);
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

       // Logic kiểm tra Supabase dự phòng
    
    $res = callSupabase($path, 'GET');
  
    }
    $error = "Thông tin không chính xác!";

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Login - Louis Music</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: radial-gradient(circle at top left, #0891b2, #020617); min-height: 100vh; display: flex; align-items: center; justify-content: center; color: white; overflow: hidden; }
        .glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(20px); border: 1px solid rgba(6, 182, 212, 0.2); border-radius: 2rem; }
    </style>
</head>
<body class="p-5 md:p-10">
     <?php if(file_exists('navbar.php')) include 'navbar.php'; ?>
    <div class="glass p-10 w-full max-w-md">
        <h1 class="text-3xl font-black text-center mb-8 uppercase tracking-tighter">Louis Music <span class="text-cyan-400">Login</span></h1>
        <form method="POST" class="space-y-6">
            <input type="text" name="username" placeholder="Username" class="w-full bg-white/5 border border-white/10 p-4 rounded-xl outline-none focus:border-cyan-500">
            <input type="password" name="password" placeholder="Password" class="w-full bg-white/5 border border-white/10 p-4 rounded-xl outline-none focus:border-cyan-500">
            <button type="submit" class="w-full bg-cyan-500 hover:bg-cyan-400 py-4 rounded-xl font-bold shadow-lg shadow-cyan-500/30 transition">ĐĂNG NHẬP NGAY</button>
        </form>
        <?php if($error) echo "<p class='text-red-400 text-center mt-4'>$error</p>"; ?>
    </div>
</body>
</html>

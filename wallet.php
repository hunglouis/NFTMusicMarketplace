<?php
session_start();
require_once 'db.php';
require 'finance_logic.php';
if (!isset($_SESSION['user'])) { header("Location: dangnhap.php"); exit(); }
?>

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

    <div class="container">
        <div class="wallet-card">
            <h2>VÍ BLOCKCHAIN: <?php echo $_SESSION['user']; ?></h2>
            <h3 style="color: yellow;">Số dư: <?php echo getBalance($conn, $_SESSION['user']); ?> PHP Coin</h3>
            <p class="reward-info">*(Bạn nhận được +50 coin cho mỗi giao dịch bạn thực hiện)*</p>
            <hr>
            <form method="POST" action="wallet.php">
                <input type="text" name="receiver" placeholder="Người nhận" required style="padding: 10px;">
                <input type="number" name="amount" placeholder="Số lượng" required style="padding: 10px;">
                <button type="submit" name="transfer" style="padding: 10px; background: #0f0; font-weight: bold; cursor:pointer;">GỬI TIỀN & ĐÀO KHỐI</button>
            </form>
        </div>
        <h4>LỊCH SỬ CHUỖI KHỐI</h4>
        <!-- (Giữ nguyên phần hiển thị Blocks như bài trước) -->
    </div>
</body>
</html>

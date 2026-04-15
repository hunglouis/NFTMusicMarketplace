<?php
session_start();
require 'finance_logic.php';
if (!isset($_SESSION['user'])) { header("Location: dangnhap.php"); exit(); }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Blockchain Wallet & Mining</title>
    <style>
        body { background: #000; color: #0f0; font-family: monospace; margin: 0; }
        .container { padding: 20px; }
        .wallet-card { border: 2px solid #0f0; padding: 20px; max-width: 500px; background: #0a0a0a; }
        .reward-info { color: #8957e5; font-size: 12px; }
    </style>
</head>
<body>
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

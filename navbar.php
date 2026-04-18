<style>
    .navbar { background: #161b22; padding: 15px; display: flex; justify-content: center; gap: 20px; border-bottom: 2px solid #0f0; margin-bottom: 20px; }
    .navbar a { color: #0f0; text-decoration: none; font-weight: bold; font-family: monospace; padding: 8px 15px; border: 1px solid transparent; }
    .navbar a:hover { border: 1px solid #0f0; background: #002200; }
    .user-info { color: yellow; margin-left: auto; font-family: monospace; }
</style>

<div class="navbar">
    <a href="dashboard.php">DashBoard</a>
    <a href="index.php">🏠 TRANG CHỦ</a>
    <a href="wallet.php">💰 VÍ TIỀN</a>
    <a href="marketplace_supabase.php">🎼 CHỢ NHẠC</a>
    <a href="api_nft.php">Bộ Sưu tập của tôi trên Alchemy</a>
    <a href="api_opensea.php">Bộ Sưu tập cua tôi trên OpenSea</a>
    <a href="player.php">🎧 NGHE NHẠC</a>
    <a href="thongke.php">📊 BẢNG VÀNG</a>
    <a href="blockchain_view.php">💬 BÌNH LUẬN</a>
    <a href="logout.php" style="color: red;">🚪 ĐĂNG XUẤT</a>
    <?php if(isset($_SESSION['user'])): ?>
        <span class="user-info">User: <?php echo $_SESSION['user']; ?></span>
    <?php endif; ?>
</div>

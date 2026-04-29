<div class="sidebar">

    <h2 style="text-align:center;">🎵 NFT</h2>

    <a href="index.php">🏠</a>

    <button class="dropdown-btn">🎼▼</button>
    <div class="submenu">
        <a href="marketplace.php">Local</a>
        <a href="marketplace_supabase.php">Supabase</a>
    </div>

    <button class="dropdown-btn">🎧▼</button>
    <div class="submenu">
        <a href="player.php">Nghe nhạc</a>
    </div>

    <a href="dangnhap.php">🔐</a>
    <a href="logout.php">🚪</a>

</div>

<style>
.sidebar {
    width: 3rem;
    height: 100vh;
    position: fixed;
    left: 0;
    top: 0;
    background: #0d1117;
    padding-top: 20px;
    border-right: 1px solid #30363d;
}

.sidebar a, .dropdown-btn {
    display: block;
    width: 100%;
    padding: 12px;
    color: #58a6ff;
    text-decoration: none;
    border: none;
    background: none;
    text-align: left;
    cursor: pointer;
}

.sidebar a:hover, .dropdown-btn:hover {
    background: #161b22;
    color: #fff;
}

.submenu {
    display: none;
    padding-left: 10px;
}

.submenu a {
    font-size: 14px;
    color: #c9d1d9;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const dropdowns = document.querySelectorAll(".dropdown-btn");

    dropdowns.forEach(btn => {
        btn.addEventListener("click", function () {
            const submenu = this.nextElementSibling;

            submenu.style.display =
                submenu.style.display === "block" ? "none" : "block";
        });
    });
});
</script>

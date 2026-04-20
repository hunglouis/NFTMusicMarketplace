<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-black text-white p-10">

<h1 class="text-3xl mb-5">🎼 NFT Music (Supabase)</h1>
<h2 class="text-xl mt-6">Upload nhạc NFT</h2>

<input type="file" id="audio" class="text-black block my-2">
<input type="file" id="image" class="text-black block my-2">
<input id="title" placeholder="Tên bài" class="text-black p-2 block my-2">

<button onclick="upload()" class="bg-green-500 px-4 py-2 rounded">
    🚀 Upload + Mint NFT
</button>

<div id="grid" class="grid grid-cols-4 gap-5"></div>

<div class="fixed bottom-0 left-0 w-full bg-gray-900 p-3">
    <div id="now">Chưa phát</div>
    <audio id="audio" controls class="w-full"></audio>
</div>

<script src="player.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
<input id="email" placeholder="Email" class="text-black p-2">
<input id="password" type="password" placeholder="Password" class="text-black p-2">

<button onclick="login()" class="bg-green-500 px-3 py-2">
    Login
</button>

<div id="auth" class="mb-6">
    <input id="email" placeholder="Email" class="text-black p-2">
    <input id="password" type="password" placeholder="Password" class="text-black p-2">
    <button onclick="login()" class="bg-green-500 px-3 py-2">Login</button>
</div>

<script>
const supabase = window.supabase.createClient(
    "https://hmvvjjiiaelcsfqgxbxv.supabase.co",
    "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw"
);

async function login() {
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    const { data, error } = await supabase.auth.signInWithPassword({
        email, password
    });

    if (error) return alert(error.message);

    alert("Đăng nhập OK");
}
</script>
<script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>
<script src="assets/js/app.js"></script>
<script src="https://cdn.jsdelivr.net/npm/ethers@5.7.2/dist/ethers.min.js"></script>

<script src="assets/js/web3.js"></script>
<script src="assets/js/app.js"></script>
<script src="player.js"></script>


</body>
</html>

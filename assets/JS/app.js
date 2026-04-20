const supabase = window.supabase.createClient(
    "https://hmvvjjiiaelcsfqgxbxv.supabase.co",
    "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImhtdnZqamlpYWVsY3NmcWd4Ynh2Iiwicm9sZSI6ImFub24iLCJpYXQiOjE3NzQzNDg4MzcsImV4cCI6MjA4OTkyNDgzN30.zCpflfgSmBwpwe62P7cr1Ppf5dMUMjh782EhZeZ-kuw"
);

// UPLOAD
async function upload() {

    const audioFile = document.getElementById("audio").files[0];
    const imageFile = document.getElementById("image").files[0];
    const title = document.getElementById("title").value;

    if (!audioFile || !imageFile) return alert("Thiếu file");

    // upload audio
    const audioPath = "audio/" + Date.now() + "_" + audioFile.name;
    await supabase.storage.from("music").upload(audioPath, audioFile);

    // upload image
    const imagePath = "image/" + Date.now() + "_" + imageFile.name;
    await supabase.storage.from("music").upload(imagePath, imageFile);

    const audioUrl = supabase.storage.from("music").getPublicUrl(audioPath).data.publicUrl;
    const imageUrl = supabase.storage.from("music").getPublicUrl(imagePath).data.publicUrl;

    await mintNFT(title, imageUrl, audioUrl);
}

async function createMetadata(title, image, audio) {

    const metadata = {
        name: title,
        description: "Music NFT by bạn",
        image: image,
        animation_url: audio
    };

    // upload JSON lên Supabase
    const path = "metadata/" + Date.now() + ".json";

    await supabase.storage
        .from("music")
        .upload(path, new Blob([JSON.stringify(metadata)], { type: "application/json" }));

    return supabase.storage.from("music").getPublicUrl(path).data.publicUrl;
}


const CONTRACT = "0x254B57b096a308c97A90da781D0E4cd74a733f4D";

const ABI = [
    {
        "inputs":[
            {"name":"to","type":"address"},
            {"name":"tokenURI","type":"string"}
        ],
        "name":"mint",
        "type":"function"
    }
];

async function mintNFT(title, image, audio) {

    const metadataUrl = await createMetadata(title, image, audio);

    const provider = new ethers.providers.Web3Provider(window.ethereum);
    const signer = provider.getSigner();

    const contract = new ethers.Contract(CONTRACT, ABI, signer);

    const address = await signer.getAddress();

    await contract.mint(address, metadataUrl);

    alert("🎉 Mint NFT thành công!");

    // lưu DB
    await saveToDB(title, image, audio);
}
async function saveToDB(title, image, audio) {

    const user = (await supabase.auth.getUser()).data.user;

    await supabase.from("songs").insert([
        {
            name: title,
            image_url: image,
            audio_url: audio,
            owner: user.email
        }
    ]);
}
location.reload();
async function loadMarketplace() {

    const provider = new ethers.providers.Web3Provider(window.ethereum);
    const contract = new ethers.Contract(CONTRACT, ABI, provider);

    const total = await contract.tokenCount();

    for (let i = 1; i <= total; i++) {

        const m = await contract.musics(i);
        const owner = await contract.ownerOf(i);
        const uri = await contract.tokenURI(i);

        const meta = await fetch(uri).then(r => r.json());

        renderNFT({
            id: i,
            price: m.price,
            owner,
            ...meta
        });
    }
}
async function buyNFT(id, price) {

    const provider = new ethers.providers.Web3Provider(window.ethereum);
    const signer = provider.getSigner();

    const contract = new ethers.Contract(CONTRACT, ABI, signer);

    await contract.buy(id, { value: price });

    alert("Mua thành công!");
}
function renderNFT(nft) {

    const grid = document.getElementById("grid");

    grid.innerHTML += `
    <div class="bg-white/10 p-4 rounded-xl">
        <img src="${nft.image}" class="w-full h-48 object-cover">
        <h3>${nft.name}</h3>
        <p>${ethers.utils.formatEther(nft.price)} MATIC</p>

        <button onclick="playAudio('${nft.animation_url}')">
            ▶ Nghe
        </button>

        <button onclick="buyNFT(${nft.id}, '${nft.price}')">
            🛒 Mua
        </button>
    </div>
    `;
}
async function loadMarketplace() {

    const provider = new ethers.providers.Web3Provider(window.ethereum);
    const contract = new ethers.Contract(CONTRACT, ABI, provider);

    const total = await contract.tokenCount();
    const user = await connectWallet();

    const grid = document.getElementById("grid");
    grid.innerHTML = "";

    for (let i = 1; i <= total; i++) {

        const m = await contract.musics(i);
        const owner = await contract.ownerOf(i);
        const uri = await contract.tokenURI(i);

        const meta = await fetch(uri).then(r => r.json());

        const isOwner = owner.toLowerCase() === user.toLowerCase();

        grid.innerHTML += `
        <div class="bg-white/10 p-4 rounded-2xl hover:scale-105 transition">
            <img src="${meta.image}" class="w-full h-60 object-cover rounded-xl">
            
            <h3 class="text-lg font-bold mt-3">${meta.name}</h3>
            <p class="text-cyan-400 text-sm mb-2">
                ${ethers.utils.formatEther(m.price)} MATIC
            </p>

            <button onclick="playProtected(${i}, '${meta.animation_url}', ${isOwner})"
            class="bg-cyan-500 w-full py-2 rounded-xl mb-2">
                ▶ Nghe
            </button>

            ${
                isOwner
                ? `<div class="text-green-400 text-center">✔️ Bạn sở hữu</div>`
                : `<button onclick="buyNFT(${i}, '${m.price}')"
                   class="bg-blue-600 w-full py-2 rounded-xl">
                   🛒 Mua
                   </button>`
            }
        </div>
        `;
    }
}
async function buyNFT(id, price) {

    const provider = new ethers.providers.Web3Provider(window.ethereum);
    const signer = provider.getSigner();

    const contract = new ethers.Contract(CONTRACT, ABI, signer);

    await contract.buy(id, { value: price });

    alert("🎉 Mua thành công!");
    loadMarketplace();
}
window.onload = () => {
    loadMarketplace();
};
// 🔥 KẾT NỐI SUPABASE (chỉ khai báo 1 lần duy nhất)
const supabase = window.supabase.createClient(
    "https://YOUR_PROJECT.supabase.co",
    "YOUR_ANON_KEY"
);

// 🔐 LOGIN
async function login() {

    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    if (!email || !password) {
        alert("Nhập email + password");
        return;
    }

    const { data, error } = await supabase.auth.signInWithPassword({
        email: email,
        password: password
    });

    if (error) {
        alert("❌ " + error.message);
        return;
    }

    alert("✅ Đăng nhập thành công!");
    console.log("User:", data.user);
}

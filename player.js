let playlist = [];
let currentIndex = 0;

const audio = document.getElementById("audio");
const now = document.getElementById("now");

// LOAD
fetch("api/get_songs.php")
.then(res => res.json())
.then(data => {
    playlist = data;
    render(data);
});

// RENDER
function render(list) {
    const grid = document.getElementById("grid");
    grid.innerHTML = "";

    list.forEach((item, i) => {
        grid.innerHTML += `
        <div class="p-4 bg-white/10 rounded-xl">
            <img src="${item.image_url}" class="w-full h-48 object-cover">
            <h3>${item.name}</h3>
            <button onclick="play(${i})">▶</button>
        </div>`;
    });
}

// PLAY
function play(i) {
    currentIndex = i;
    audio.src = playlist[i].audio_url;
    audio.play();
    now.innerText = playlist[i].name;
}
function playProtected(id, url, isOwner) {

    const audio = document.getElementById("audio");

    audio.src = url;
    audio.play();

    if (!isOwner) {
        // chỉ nghe 30s
        setTimeout(() => {
            audio.pause();
            alert("🔒 Bạn cần mua NFT để nghe full");
        }, 30000);
    }
}

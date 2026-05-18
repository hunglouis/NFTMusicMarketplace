const audio = new Audio('/assets/audio/QUYNH-HUONG_Folk-1.mp3');



function enterGallery() {

  // phát nhạc
  audio.volume = 1;
  
  audio.play().catch(error => {
    console.log("Trình duyệt yêu cầu click trước:", error);
  });

  // fade out sau 59 giây
  setTimeout(() => {

    const fadeEffect = setInterval(() => {

      if (audio.volume > 0.1) {
        audio.volume -= 0.1;
      } else {
        clearInterval(fadeEffect);
        audio.pause();
      }

    }, 100);

  }, 59000);

  // chuyển trang gallery
  window.location.href = "marketplace.php";
}
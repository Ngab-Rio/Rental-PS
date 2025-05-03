// Fungsi untuk menghitung waktu mundur secara dinamis
function updateCountdown() {
    const countdownElements = document.querySelectorAll('.countdown');
    
    countdownElements.forEach(function(element) {
        const timestamp = parseInt(element.getAttribute('data-timestamp'));
        const durasi = parseInt(element.closest('tr').querySelector('td:nth-child(4)').innerText);  // Durasi diambil dari kolom Durasi
        const totalTime = timestamp + durasi * 3600;  // Waktu selesai dihitung dari waktu panggil + durasi
        
        const currentTime = Math.floor(Date.now() / 1000);  // Waktu sekarang dalam detik
        const timeLeft = totalTime - currentTime;

        if (timeLeft > 0) {
            const hours = Math.floor(timeLeft / 3600);
            const minutes = Math.floor((timeLeft % 3600) / 60);
            const seconds = timeLeft % 60;

            element.innerHTML = String(hours).padStart(2, '0') + ":" + String(minutes).padStart(2, '0') + ":" + String(seconds).padStart(2, '0');
        } else {
            element.innerHTML = 'Selesai';
        }
    });
}

// Update countdown every second
setInterval(updateCountdown, 1000);

// Call function once when page loads
updateCountdown();

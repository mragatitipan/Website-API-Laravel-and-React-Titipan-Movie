
    // Fetch IP address using a third-party API
    fetch('https://api.ipify.org?format=json')
    .then(response => response.json())
    .then(data => {
        document.getElementById("user-ip").textContent = data.ip;
    });

    // Bandwidth speed test
    function calculateBandwidth() {
    const startTime = Date.now();
    const image = new Image();
    image.src = "https://www.google.com/images/phd/px.gif?cachebust=" + startTime;
    image.onload = () => {
        const endTime = Date.now();
        const duration = (endTime - startTime) / 1000;
        const bitsLoaded = 50000 * 8;
        const speedMbps = (bitsLoaded / duration / 1024 / 1024).toFixed(2);
        document.getElementById("bandwidth-speed").textContent = speedMbps + " Mbps";
    };
    }
    calculateBandwidth();

    const karek = document.getElementById('karek');
    const speechBubble = document.getElementById('speechBubble');
    const notification = document.createElement('div');
    let karekX = 100;
    let karekY = 100;
    let speed = 3;
    let isCaught = false;
    let directionX = 1;
    let directionY = 1;

    const speechMessages = [
        "Ayo, coba tangkap aku kalau bisa!",
        "Hei! Aku di sini!",
        "Cepatlah, aku tak akan diam!",
        "Pasti kamu tidak bisa menangkapku!",
        "Lambat sekali, ayo semangat!",
        "Hei, masih di sana saja? 😆",
        "Aku lebih cepat dari yang kamu kira!",
        "Terus kejar! Aku takkan lari... jauh! 😉"
    ];

    // Notifikasi ketika Karek berhasil ditangkap
    notification.style.position = 'fixed';
    notification.style.top = '50%';
    notification.style.left = '50%';
    notification.style.transform = 'translate(-50%, -50%)';
    notification.style.padding = '20px 40px';
    notification.style.backgroundColor = '#28a745';
    notification.style.color = '#fff';
    notification.style.borderRadius = '20px';
    notification.style.fontSize = '1.5rem';
    notification.style.fontWeight = 'bold';
    notification.style.textAlign = 'center';
    notification.style.display = 'none';
    notification.style.zIndex = '1000';
    notification.textContent = 'Selamat! Anda berhasil menangkap Karek!';
    document.body.appendChild(notification);

    // Fungsi untuk menggerakkan Karek di dalam batas layar
    function moveKarek() {
        if (isCaught) return;

        karekX += speed * directionX;
        karekY += speed * directionY;

        // Batas layar dengan margin lebih besar agar tidak menabrak scrollbar
        const margin = 30;
        const windowWidth = window.innerWidth - karek.clientWidth - margin;
        const windowHeight = window.innerHeight - karek.clientHeight - margin;

        // Pantulan dengan pergantian arah di batas layar
        if (karekX <= margin || karekX >= windowWidth) {
            directionX *= -1;
            karekX = Math.max(margin, Math.min(karekX, windowWidth));

            // Putar suara mantul saat Karek memantul
            playBounceSound();
        }
        if (karekY <= margin || karekY >= windowHeight) {
            directionY *= -1;
            karekY = Math.max(margin, Math.min(karekY, windowHeight));

            // Putar suara mantul saat Karek memantul
            playBounceSound();
        }

        karek.style.left = `${karekX}px`;
        karek.style.top = `${karekY}px`;

        // Posisi balon bicara mengikuti posisi Karek
        speechBubble.style.left = `${karekX + 50}px`;
        speechBubble.style.top = `${karekY - 40}px`;

        requestAnimationFrame(moveKarek);
    }

    // Fungsi untuk memainkan suara mantul
    function playBounceSound() {
        const bounceSound = new Audio('public/ui/svg/karek_mantul.mp3'); // Ganti path jika perlu
        bounceSound.play();
    }

    // Deteksi posisi mouse untuk menghindari kursor
    document.addEventListener('mousemove', (event) => {
        if (isCaught) return;

        const mouseX = event.clientX;
        const mouseY = event.clientY;
        const dx = mouseX - (karekX + karek.clientWidth / 2);
        const dy = mouseY - (karekY + karek.clientHeight / 2);
        const distance = Math.sqrt(dx * dx + dy * dy);

        // Jika kursor mendekat, Karek bergerak lebih cepat menjauhi kursor
        if (distance < 200) {
            speed = Math.min(15, 5 + (200 - distance) / 10);
            karekX -= (dx / distance) * speed;
            karekY -= (dy / distance) * speed;
            speechBubble.style.display = 'block';
            speechBubble.textContent = 'Ayo tangkap aku jika bisa!';
        } else {
            speed = 3;
            speechBubble.style.display = 'none';
        }
    });

    // Interaksi Klik pada Karek
    karek.addEventListener('mousedown', () => {
        isCaught = true;
        speed = 0;
        speechBubble.style.display = 'block';
        speechBubble.textContent = 'Selamat, Anda berhasil menangkap saya!';

        // Tampilkan notifikasi
        notification.style.display = 'block';

        // Tutup notifikasi setelah 4 detik dan lanjutkan gerakan Karek
        setTimeout(() => {
            notification.style.display = 'none';
            isCaught = false;
            speed = 3;
            moveKarek(); // Lanjutkan gerakan setelah notifikasi hilang
        }, 4000);
    });

    // Fungsi untuk menampilkan pesan acak dari Karek secara otomatis
    function showRandomSpeech() {
        if (isCaught) return;

        const randomMessage = speechMessages[Math.floor(Math.random() * speechMessages.length)];
        speechBubble.textContent = randomMessage;
        speechBubble.style.left = `${karekX + 50}px`;
        speechBubble.style.top = `${karekY - 40}px`;
        speechBubble.style.display = 'block';

        setTimeout(() => {
            if (!isCaught) {
                speechBubble.style.display = 'none';
            }
        }, 3000); // Tampilkan pesan selama 3 detik
    }

    // Timer untuk membuat Karek berbicara setiap 10 detik
    setInterval(showRandomSpeech, 10000); // Jeda 10 detik sebelum pesan berikutnya

    // Memulai gerakan Karek
    moveKarek();


    // Fungsi untuk mendapatkan IP address menggunakan API pihak ketiga
    function getIP() {
        return fetch('https://api.ipify.org?format=json')
            .then(response => response.json())
            .then(data => data.ip)
            .catch(error => console.error('Error fetching IP:', error));
    }

    // Fungsi untuk memperbarui informasi bandwidth
    function calculateBandwidth() {
        const startTime = Date.now();
        const image = new Image();
        image.src = "https://www.google.com/images/phd/px.gif?cachebust=" + startTime;
        image.onload = () => {
            const endTime = Date.now();
            const duration = (endTime - startTime) / 1000;
            const bitsLoaded = 50000 * 8;
            const speedMbps = (bitsLoaded / duration / 1024 / 1024).toFixed(2);
            document.getElementById("bandwidth-speed").textContent = speedMbps + " Mbps";
        };
    }
    calculateBandwidth(); // Jalankan fungsi saat halaman dimuat

    // Fungsi untuk memperbarui total pengunjung di Local Storage
    async function updateVisitorCount() {
        const ip = await getIP();
        document.getElementById("user-ip").textContent = ip; // Tampilkan IP di halaman

        // Ambil data pengunjung dari Local Storage atau inisialisasi sebagai array kosong
        let visitors = JSON.parse(localStorage.getItem('visitors')) || [];

        // Jika IP belum ada dalam daftar, tambahkan dan simpan kembali ke Local Storage
        if (!visitors.includes(ip)) {
            visitors.push(ip);
            localStorage.setItem('visitors', JSON.stringify(visitors));
        }

        // Hitung total pengunjung unik berdasarkan panjang array
        const totalVisitors = visitors.length;
        document.getElementById('visitor-count').textContent = totalVisitors; // Tampilkan total pengunjung
    }

    // Panggil fungsi saat halaman dimuat
    updateVisitorCount();

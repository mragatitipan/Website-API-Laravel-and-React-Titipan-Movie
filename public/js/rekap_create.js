// Event yang akan dieksekusi setelah halaman selesai dimuat
document.addEventListener('DOMContentLoaded', function () {
    var currentUrl = window.location.href;
    var isCreatePage = currentUrl.includes('/create');

    // Menonaktifkan tautan sidebar jika berada di halaman pembuatan data
    if (isCreatePage) {
        var sidebarLinks = document.querySelectorAll('.nav-link');

        sidebarLinks.forEach(function (link) {
            link.classList.add('disabled');
            link.setAttribute('aria-disabled', 'true');
            link.addEventListener('click', function (event) {
                event.preventDefault();
            });
        });
    }
});

// Fungsi untuk menghitung biaya perkiraan harian berdasarkan jumlah pekerja dan upah per jam
function calculateEstimatedCost() {
    const jumlahTukang = parseInt(document.getElementById('jumlah_tukang').value) || 0;
    const jumlahKenek = parseInt(document.getElementById('jumlah_kenek').value) || 0;
    const jamKerjaTukang = parseInt(document.getElementById('jam_kerja_tukang').value) || 0;
    const jamKerjaKenek = parseInt(document.getElementById('jam_kerja_kenek').value) || 0;
    const upahPerJamTukang = parseFloat(document.getElementById('upah_perjam_tukang').value) || 0;
    const upahPerJamKenek = parseFloat(document.getElementById('upah_perjam_kenek').value) || 0;

    // Menghitung total upah harian tukang dan kenek
    const totalUpahTukang = jumlahTukang * jamKerjaTukang * upahPerJamTukang;
    const totalUpahKenek = jumlahKenek * jamKerjaKenek * upahPerJamKenek;
    const totalEstimatedCost = totalUpahTukang + totalUpahKenek;

    // Memperbarui field display dengan format mata uang
    document.getElementById('perkiraan_biaya_display').value = formatCurrency(totalEstimatedCost);

    // Menyimpan nilai total ke dalam field tersembunyi
    document.getElementById('perkiraan_biaya').value = totalEstimatedCost;
}

// Menambahkan event listener pada input terkait untuk memperbarui perhitungan secara otomatis
['jumlah_tukang', 'jumlah_kenek', 'jam_kerja_tukang', 'jam_kerja_kenek', 'upah_perjam_tukang', 'upah_perjam_kenek'].forEach(fieldId => {
    document.getElementById(fieldId).addEventListener('change', () => {
        calculateTotalManpower();
        calculateEstimatedCost();
    });
});

// Memicu perhitungan saat halaman dimuat pertama kali
document.addEventListener('DOMContentLoaded', () => {
    calculateTotalManpower();
    calculateEstimatedCost();
});

// Fitur Analisa otomatis untuk menghitung estimasi lama pekerjaan, total pekerja, biaya, dan status pekerjaan
document.addEventListener('DOMContentLoaded', function () {
    // Mengambil elemen form yang diperlukan untuk perhitungan
    const estimasiWaktuRencana = document.getElementById('estimasi_waktu_rencana');
    const jumlahTukang = document.getElementById('jumlah_tukang');
    const jumlahKenek = document.getElementById('jumlah_kenek');
    const upahPerJamTukang = document.getElementById('upah_perjam_tukang');
    const upahPerJamKenek = document.getElementById('upah_perjam_kenek');
    const progress = document.getElementById('progress');

    // Mengambil elemen hasil analisa untuk ditampilkan
    const estimasiLamaPekerjaan = document.getElementById('estimasi_lama_pekerjaan');
    const totalManpower = document.getElementById('total_manpower');
    const perkiraanBiaya = document.getElementById('perkiraan_biaya');
    const statusAnalisa = document.getElementById('status_analisa');

    // Elemen kesimpulan analisa
    const kesimpulanLamaPekerjaan = document.getElementById('kesimpulan_lama_pekerjaan');
    const kesimpulanTotalManpower = document.getElementById('kesimpulan_total_manpower');
    const kesimpulanPerkiraanBiaya = document.getElementById('kesimpulan_perkiraan_biaya');
    const kesimpulanStatusPekerjaan = document.getElementById('kesimpulan_status_pekerjaan');

    // Fungsi untuk memperbarui data analisa ketika data diubah
    function updateAnalysis() {
        // Menghitung estimasi lama pekerjaan
        estimasiLamaPekerjaan.value = estimasiWaktuRencana.value || 0;
        kesimpulanLamaPekerjaan.textContent = estimasiLamaPekerjaan.value;

        // Menghitung total pekerja
        totalManpower.value = (parseInt(jumlahTukang.value) || 0) + (parseInt(jumlahKenek.value) || 0);
        kesimpulanTotalManpower.textContent = totalManpower.value;

        // Menghitung biaya pekerjaan
        const totalJamKerjaTukang = (parseInt(jumlahTukang.value) || 0) * (parseInt(document.getElementById('jam_kerja_tukang').value) || 0);
        const totalJamKerjaKenek = (parseInt(jumlahKenek.value) || 0) * (parseInt(document.getElementById('jam_kerja_kenek').value) || 0);
        const biayaTukang = totalJamKerjaTukang * (parseInt(upahPerJamTukang.value) || 0);
        const biayaKenek = totalJamKerjaKenek * (parseInt(upahPerJamKenek.value) || 0);

        // Total biaya
        perkiraanBiaya.value = biayaTukang + biayaKenek;
        kesimpulanPerkiraanBiaya.textContent = perkiraanBiaya.value.toLocaleString();

        // Menentukan status berdasarkan progress
        const progressValue = parseInt(progress.value) || 0;
        if (progressValue === 100) {
            statusAnalisa.value = 'Selesai';
            kesimpulanStatusPekerjaan.textContent = 'Selesai';
        } else if (progressValue > 0) {
            statusAnalisa.value = 'Sedang Berjalan';
            kesimpulanStatusPekerjaan.textContent = 'Sedang Berjalan';
        } else {
            statusAnalisa.value = 'Belum Dimulai';
            kesimpulanStatusPekerjaan.textContent = 'Belum Dimulai';
        }
    }

    // Menambahkan event listener pada elemen form untuk memperbarui analisa
    estimasiWaktuRencana.addEventListener('input', updateAnalysis);
    jumlahTukang.addEventListener('input', updateAnalysis);
    jumlahKenek.addEventListener('input', updateAnalysis);
    document.getElementById('jam_kerja_tukang').addEventListener('input', updateAnalysis);
    document.getElementById('jam_kerja_kenek').addEventListener('input', updateAnalysis);
    upahPerJamTukang.addEventListener('input', updateAnalysis);
    upahPerJamKenek.addEventListener('input', updateAnalysis);
    progress.addEventListener('input', updateAnalysis);
});

// Fungsi untuk menghitung lama pekerjaan dan bulan berdasarkan tanggal mulai dan selesai
function calculateDates() {
    const tanggalPelaksanaan = new Date(document.getElementById('tanggal_pelaksanaan').value);
    const tanggalSelesai = new Date(document.getElementById('tanggal_selesai_pekerjaan').value);

    // Menghitung perbedaan hari jika tanggal valid
    if (tanggalPelaksanaan && tanggalSelesai && tanggalSelesai >= tanggalPelaksanaan) {
        const timeDifference = tanggalSelesai.getTime() - tanggalPelaksanaan.getTime();
        const dayDifference = Math.ceil(timeDifference / (1000 * 3600 * 24));
        document.getElementById('estimasi_waktu_rencana').value = dayDifference;

        // Mengisi bulan pekerjaan otomatis berdasarkan tanggal pelaksanaan
        const bulanPekerjaan = tanggalPelaksanaan.toISOString().slice(0, 7);
        document.getElementById('bulan_pekerjaan').value = bulanPekerjaan;
    }
}

// Fungsi untuk menghitung total pekerja (Tukang + Kenek)
function calculateTotalManpower() {
    const jumlahTukang = parseInt(document.getElementById('jumlah_tukang').value) || 0;
    const jumlahKenek = parseInt(document.getElementById('jumlah_kenek').value) || 0;
    const totalManpower = jumlahTukang + jumlahKenek;
    document.getElementById('rekapitulasi_tenaga_kerja_bulan_ini').value = totalManpower;
}

// Fungsi untuk memformat angka menjadi format Rupiah
function formatCurrency(value) {
    if (!value) return 'Rp 0,00';
    const formattedValue = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(value);
    return formattedValue.replace('IDR', 'Rp');
}

// Fungsi untuk menghitung total upah harian dan bulanan berdasarkan jumlah pekerja
function calculateWages() {
    const jumlahTukang = parseInt(document.getElementById('jumlah_tukang').value) || 0;
    const jumlahKenek = parseInt(document.getElementById('jumlah_kenek').value) || 0;
    const jamKerjaTukang = parseInt(document.getElementById('jam_kerja_tukang').value) || 0;
    const jamKerjaKenek = parseInt(document.getElementById('jam_kerja_kenek').value) || 0;
    const upahPerJamTukang = parseFloat(document.getElementById('upah_perjam_tukang').value) || 0;
    const upahPerJamKenek = parseFloat(document.getElementById('upah_perjam_kenek').value) || 0;
    const tukangHari = parseInt(document.getElementById('tukang_hari').value) || 0;
    const kenekHari = parseInt(document.getElementById('kenek_hari').value) || 0;

    // Total upah harian
    const totalUpahTukangHariIni = jumlahTukang * jamKerjaTukang * upahPerJamTukang;
    const totalUpahKenekHariIni = jumlahKenek * jamKerjaKenek * upahPerJamKenek;
    const totalUpahHariIni = totalUpahTukangHariIni + totalUpahKenekHariIni;

    // Total upah bulanan
    const totalUpahTukangBulanIni = jumlahTukang * jamKerjaTukang * upahPerJamTukang * tukangHari;
    const totalUpahKenekBulanIni = jumlahKenek * jamKerjaKenek * upahPerJamKenek * kenekHari;
    const totalUpahBulanIni = totalUpahTukangBulanIni + totalUpahKenekBulanIni;

    // Memperbarui field tampilan upah harian dan bulanan
    document.getElementById('total_upah_display').value = formatCurrency(totalUpahHariIni);
    document.getElementById('total_upah_bulan_ini_display').value = formatCurrency(totalUpahBulanIni);

    // Menyimpan nilai upah sebenarnya ke field tersembunyi
    document.getElementById('total_upah').value = totalUpahHariIni;
    document.getElementById('total_upah_bulan_ini').value = totalUpahBulanIni;
}

// Menambahkan event listener pada elemen input untuk memicu perhitungan upah
['jumlah_tukang', 'jumlah_kenek', 'jam_kerja_tukang', 'jam_kerja_kenek', 'upah_perjam_tukang', 'upah_perjam_kenek', 'tukang_hari', 'kenek_hari'].forEach(fieldId => {
    document.getElementById(fieldId).addEventListener('change', calculateWages);
});

// Memicu perhitungan upah saat halaman pertama kali dimuat
document.addEventListener('DOMContentLoaded', calculateWages);

// Fungsi untuk menangani input kosong pada bagian Manpower dengan mengatur nilai default 0
function handleEmptyInputs() {
    const fields = ['jumlah_tukang', 'jumlah_kenek', 'jam_kerja_tukang', 'jam_kerja_kenek', 'tukang_hari', 'kenek_hari'];
    fields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field && (field.value === '' || field.value === null)) {
            field.value = 0; // Setel menjadi 0 jika input kosong atau null
        }
    });
}

// Memicu fungsi handleEmptyInputs saat halaman dimuat dan ketika input diubah
document.addEventListener('DOMContentLoaded', handleEmptyInputs);
['jumlah_tukang', 'jumlah_kenek', 'jam_kerja_tukang', 'jam_kerja_kenek', 'tukang_hari', 'kenek_hari'].forEach(fieldId => {
    document.getElementById(fieldId).addEventListener('change', handleEmptyInputs);
});


document.addEventListener('DOMContentLoaded', () => {
    startCamera();
    calculateWages();
    calculateDates();
    calculateTotalManpower();
});

// Script khusus kamera
function startCamera() {
    const video = document.getElementById('camera_stream');
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
            })
            .catch(error => {
                console.error("Error accessing the camera: ", error);
            });
    } else {
        alert("Camera not supported by your browser.");
    }
}


// Utility function to format currency in Indonesian Rupiah
function formatCurrency(value) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 2,
    }).format(value || 0).replace('IDR', 'Rp');
}

// Function to calculate daily and monthly wages
function calculateWages() {
    const tukang = {
        jumlah: parseInt(document.getElementById('jumlah_tukang').value) || 0,
        jamKerja: parseInt(document.getElementById('jam_kerja_tukang').value) || 0,
        upahPerJam: parseFloat(document.getElementById('upah_perjam_tukang').value) || 0,
        hari: parseInt(document.getElementById('tukang_hari').value) || 0
    };
    const kenek = {
        jumlah: parseInt(document.getElementById('jumlah_kenek').value) || 0,
        jamKerja: parseInt(document.getElementById('jam_kerja_kenek').value) || 0,
        upahPerJam: parseFloat(document.getElementById('upah_perjam_kenek').value) || 0,
        hari: parseInt(document.getElementById('kenek_hari').value) || 0
    };

    const totalDaily = (tukang.jumlah * tukang.jamKerja * tukang.upahPerJam) +
                       (kenek.jumlah * kenek.jamKerja * kenek.upahPerJam);
    const totalMonthly = (tukang.jumlah * tukang.jamKerja * tukang.upahPerJam * tukang.hari) +
                         (kenek.jumlah * kenek.jamKerja * kenek.upahPerJam * kenek.hari);

    document.getElementById('total_upah_display').value = formatCurrency(totalDaily);
    document.getElementById('total_upah_bulan_ini_display').value = formatCurrency(totalMonthly);
    document.getElementById('total_upah').value = totalDaily;
    document.getElementById('total_upah_bulan_ini').value = totalMonthly;
}

// Fungsi untuk menghitung lama pekerjaan dan bulan berdasarkan tanggal mulai dan selesai
function calculateDates() {
    const tanggalPelaksanaan = new Date(document.getElementById('tanggal_pelaksanaan').value);
    const tanggalSelesai = new Date(document.getElementById('tanggal_selesai_pekerjaan').value);

    // Menghitung perbedaan hari jika tanggal valid
    if (tanggalPelaksanaan && tanggalSelesai && tanggalSelesai >= tanggalPelaksanaan) {
        const timeDifference = tanggalSelesai.getTime() - tanggalPelaksanaan.getTime();

        // Menggunakan Math.ceil untuk memastikan nilai genap dan menambahkan 1 hari untuk memasukkan hari awal
        const dayDifference = Math.ceil(timeDifference / (1000 * 3600 * 24)) + 1;
        document.getElementById('estimasi_waktu_rencana').value = dayDifference;

        // Mengisi bulan pekerjaan otomatis berdasarkan tanggal pelaksanaan
        const bulanPekerjaan = tanggalPelaksanaan.toISOString().slice(0, 7);
        document.getElementById('bulan_pekerjaan').value = bulanPekerjaan;
    }
}


// Function to calculate total manpower
function calculateTotalManpower() {
    const totalManpower = (parseInt(document.getElementById('jumlah_tukang').value) || 0) +
                          (parseInt(document.getElementById('jumlah_kenek').value) || 0);
    document.getElementById('rekapitulasi_tenaga_kerja_bulan_ini').value = totalManpower;
}

// Initialize event listeners and default values
document.addEventListener('DOMContentLoaded', () => {
    calculateWages();
    calculateDates();
    calculateTotalManpower();
});

['jumlah_tukang', 'jumlah_kenek', 'jam_kerja_tukang', 'jam_kerja_kenek',
 'upah_perjam_tukang', 'upah_perjam_kenek', 'tukang_hari', 'kenek_hari'].forEach(id => {
    document.getElementById(id).addEventListener('change', () => {
        calculateWages();
        calculateTotalManpower();
    });
});

['tanggal_pelaksanaan', 'tanggal_selesai_pekerjaan'].forEach(id => {
    document.getElementById(id).addEventListener('change', calculateDates);
});

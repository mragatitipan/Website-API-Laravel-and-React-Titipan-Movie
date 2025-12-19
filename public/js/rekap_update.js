// Fungsi untuk update total keseluruhan
function updateTotals() {
    calculateTotalWages();
    calculateTotalManpower();
}

// Fungsi untuk menghitung total upah (wages)
function calculateTotalWages() {
    // Ambil elemen input untuk jumlah Tukang, Kenek, jam kerja, dan hari kerja
    const jumlahTukangInputs = document.querySelectorAll('input[name^="progresses["][name$="[jumlah_tukang]"]');
    const jumlahKenekInputs = document.querySelectorAll('input[name^="progresses["][name$="[jumlah_kenek]"]');
    const jamKerjaTukangInputs = document.querySelectorAll('input[name^="progresses["][name$="[jam_kerja_tukang]"]');
    const jamKerjaKenekInputs = document.querySelectorAll('input[name^="progresses["][name$="[jam_kerja_kenek]"]');
    const tukangHariInputs = document.querySelectorAll('input[name^="progresses["][name$="[tukang_hari]"]');
    const kenekHariInputs = document.querySelectorAll('input[name^="progresses["][name$="[kenek_hari]"]');

    // Ambil nilai upah per jam untuk Tukang dan Kenek
    const upahPerJamTukang = parseFloat(document.getElementById('upah_perjam_tukang').value) || 0;
    const upahPerJamKenek = parseFloat(document.getElementById('upah_perjam_kenek').value) || 0;

    // Nilai awal total upah harian dan bulanan dari database atau nilai awal sistem
    const initialUpahHariIni = parseFloat(document.getElementById('total_upah').getAttribute('data-initial-value')) || 0;
    const initialUpahBulanIni = parseFloat(document.getElementById('total_upah_bulan_ini').getAttribute('data-initial-value')) || 0;

    let totalUpahHariIni = initialUpahHariIni;
    let totalUpahBulanIni = initialUpahBulanIni;

    // Loop untuk menghitung total upah Tukang dan Kenek per item progress
    for (let i = 0; i < jumlahTukangInputs.length; i++) {
        // Ambil nilai jumlah Tukang, Kenek, jam kerja, dan hari kerja
        const jumlahTukang = parseInt(jumlahTukangInputs[i].value) || 0;
        const jumlahKenek = parseInt(jumlahKenekInputs[i].value) || 0;
        const jamKerjaTukang = parseInt(jamKerjaTukangInputs[i].value) || 0;
        const jamKerjaKenek = parseInt(jamKerjaKenekInputs[i].value) || 0;
        const tukangHari = parseInt(tukangHariInputs[i].value) || 0;
        const kenekHari = parseInt(kenekHariInputs[i].value) || 0;

        // Kalkulasi upah harian untuk Tukang dan Kenek
        const upahHariIniTukang = jumlahTukang * jamKerjaTukang * upahPerJamTukang;
        const upahHariIniKenek = jumlahKenek * jamKerjaKenek * upahPerJamKenek;
        
        // Tambahkan ke total upah hari ini (akumulatif)
        totalUpahHariIni += upahHariIniTukang + upahHariIniKenek;

        // Kalkulasi upah bulanan untuk Tukang dan Kenek berdasarkan hari kerja
        const upahBulanIniTukang = upahHariIniTukang * tukangHari;
        const upahBulanIniKenek = upahHariIniKenek * kenekHari;

        // Tambahkan ke total upah bulan ini (akumulatif)
        totalUpahBulanIni += upahBulanIniTukang + upahBulanIniKenek;
    }

    // Update tampilan untuk total upah hari ini dan bulan ini
    document.getElementById('total_upah_display').value = formatCurrency(totalUpahHariIni);
    document.getElementById('total_upah').value = totalUpahHariIni;
    document.getElementById('total_upah_bulan_ini_display').value = formatCurrency(totalUpahBulanIni);
    document.getElementById('total_upah_bulan_ini').value = totalUpahBulanIni;
}

// Helper: Format angka ke dalam format mata uang Rupiah
function formatCurrency(value) {
    if (!value) return 'Rp 0,00';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(value).replace('IDR', 'Rp');
}

// Event listener untuk kalkulasi ulang saat input berubah
document.querySelectorAll('input[name^="progresses["]').forEach(input => {
    input.addEventListener('input', () => {
        calculateTotalWages();
        calculateTotalManpower();
    });
});



// Fungsi untuk menghitung total tenaga kerja (tukang + kenek)
function calculateTotalManpower() {
    // Ambil nilai tenaga kerja awal dari database (nilai ini adalah total tenaga kerja yang sudah ada di sistem)
    const initialManpower = parseInt(document.getElementById('rekapitulasi_tenaga_kerja_bulan_ini').getAttribute('data-initial-value')) || 0;

    // Ambil semua input untuk jumlah_tukang dan jumlah_kenek
    const jumlahTukangInputs = document.querySelectorAll('input[name^="progresses["][name$="[jumlah_tukang]"]');
    const jumlahKenekInputs = document.querySelectorAll('input[name^="progresses["][name$="[jumlah_kenek]"]');

    let totalManpowerUpdate = 0;

    // Loop setiap item progress dan hitung total tenaga kerja dari input baru
    for (let i = 0; i < jumlahTukangInputs.length; i++) {
        const jumlahTukang = parseInt(jumlahTukangInputs[i].value) || 0;
        const jumlahKenek = parseInt(jumlahKenekInputs[i].value) || 0;

        // Tambahkan tukang dan kenek saat ini ke totalManpowerUpdate (ini adalah nilai pembaruan)
        totalManpowerUpdate += jumlahTukang + jumlahKenek;
    }

    // Total tenaga kerja = initialManpower (dari database) + totalManpowerUpdate (dari input form)
    const updatedTotalManpower = initialManpower + totalManpowerUpdate;

    // Perbarui tampilan dengan total tenaga kerja yang baru
    document.getElementById('rekapitulasi_tenaga_kerja_bulan_ini').value = updatedTotalManpower;
}

// Inisialisasi total tenaga kerja saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
    // Simpan nilai tenaga kerja awal dalam atribut data untuk menghindari perhitungan ulang nilai awal
    const initialManpowerElement = document.getElementById('rekapitulasi_tenaga_kerja_bulan_ini');
    initialManpowerElement.setAttribute('data-initial-value', initialManpowerElement.value);
    
    calculateTotalManpower(); // Kalkulasi awal berdasarkan data yang sudah ada
});

// Trigger kalkulasi ulang ketika nilai input berubah
document.querySelectorAll('input[name^="progresses["]').forEach(input => {
    input.addEventListener('input', () => {
        calculateTotalManpower(); // Kalkulasi ulang saat input dimodifikasi
    });
});

// Helper: Format angka ke dalam format mata uang Rupiah
function formatCurrency(value) {
    if (!value) return 'Rp 0,00';
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(value).replace('IDR', 'Rp');
}

// Inisialisasi kalkulasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
    // Set nilai awal berdasarkan data yang sudah ada untuk total upah dan tenaga kerja
    document.getElementById('total_upah_display').value = formatCurrency(parseFloat(document.getElementById('total_upah').value) || 0);
    document.getElementById('total_upah_bulan_ini_display').value = formatCurrency(parseFloat(document.getElementById('total_upah_bulan_ini').value) || 0);
    calculateTotalManpower();
});

// Event listener untuk kalkulasi ulang saat input berubah
document.querySelectorAll('input[name^="progresses["]').forEach(input => {
    input.addEventListener('input', () => {
        calculateTotalWages();
        calculateTotalManpower();
    });
});

// BAGIAN FORM OTOMATIS UNTUK PROGRESS PEKERJAAN :
document.querySelector('form').addEventListener('submit', function(e) {
    let valid = true;
    document.querySelectorAll('.progress-item input').forEach(input => {
        if (!input.value || input.value === '0') {
            valid = false;
            input.classList.add('is-invalid'); // Tambahkan class untuk menunjukkan error
        } else {
            input.classList.remove('is-invalid'); // Hapus class error jika sudah valid
        }
    });

    if (!valid) {
        e.preventDefault();
        alert('Harap isi semua data progress yang diperlukan.');
    }
});

// Fungsi untuk memvalidasi input dan set nilai default jika kosong
function validateAndSetDefault() {
    document.querySelectorAll('.progress-item input').forEach(input => {
        if (input.value === '') {
            input.value = 0; // Set default nilai ke 0 jika kosong
        }
    });
    return true;
}

const progressList = document.getElementById('progressList');
const addProgressBtn = document.getElementById('addProgressBtn');
const calendarModal = document.getElementById('calendarModal');
const datePicker = document.getElementById('datePicker');
let firstProgressDate = null;

// Initialize Flatpickr untuk memilih tanggal
flatpickr(datePicker, {
    mode: 'single',
    dateFormat: "Y-m-d",
    onChange: function (selectedDates) {
        if (selectedDates.length > 0) {
            const date = selectedDates[0];
            createProgressFields(date);
            if (!firstProgressDate) {
                firstProgressDate = date; // Simpan tanggal pertama yang dipilih
            }
            $(calendarModal).modal('hide'); // Sembunyikan modal setelah tanggal dipilih
        }
    },
    minDate: new Date(document.getElementById('tanggal_pelaksanaan').value),
    maxDate: new Date(document.getElementById('tanggal_selesai_pekerjaan').value),
});

// Tambahkan event listener untuk submit form agar nilai default diatur sebelum kirim
document.querySelector('form').addEventListener('submit', function (e) {
    validateAndSetDefault();
});

let progressIndex = 0;

function createProgressFields(date) {
    const progressItem = document.createElement('div');
    progressItem.className = 'progress-item card mb-4 shadow-sm';
    progressItem.innerHTML = `
        <div class="card-header bg-dark text-white">
            <h6 class="mb-0">${date.toLocaleDateString()}</h6>
        </div>
        <div class="card-body">
            <p class="text-danger mb-3"><strong>Catatan:</strong> Hapus dulu 0 sebelum pengisian</p>
            <div class="row">
                <div class="form-group col-md-2 mb-3">
                    <label>Tanggal Pelaksanaan</label>
                    <input type="date" class="form-control progress-date" name="progresses[${progressIndex}][tanggal_pelaksanaan]" value="${formatDate(date)}" required>
                </div>
                <div class="form-group col-md-2 mb-3">
                    <label>Jumlah Tukang</label>
                    <input type="number" class="form-control" name="progresses[${progressIndex}][jumlah_tukang]" min="0" value="0" required onchange="updateTotals()">
                </div>
                <div class="form-group col-md-2 mb-3">
                    <label>Jumlah Kenek</label>
                    <input type="number" class="form-control" name="progresses[${progressIndex}][jumlah_kenek]" min="0" value="0" required onchange="updateTotals()">
                </div>
                <div class="form-group col-md-2 mb-3">
                    <label>Jam Kerja Tukang</label>
                    <input type="number" class="form-control" name="progresses[${progressIndex}][jam_kerja_tukang]" min="0" value="0" required onchange="updateTotals()">
                </div>
                <div class="form-group col-md-2 mb-3">
                    <label>Jam Kerja Kenek</label>
                    <input type="number" class="form-control" name="progresses[${progressIndex}][jam_kerja_kenek]" min="0" value="0" required onchange="updateTotals()">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6 mb-3">
                    <label>Tukang Hari</label>
                    <input type="number" class="form-control" name="progresses[${progressIndex}][tukang_hari]" value="0" required onchange="updateTotals()">
                </div>
                <div class="form-group col-md-6 mb-3">
                    <label>Kenek Hari</label>
                    <input type="number" class="form-control" name="progresses[${progressIndex}][kenek_hari]" value="0" required onchange="updateTotals()">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-10 mb-3">
                    <label>Progress (%)</label>
                    <input type="number" class="form-control" name="progresses[${progressIndex}][progress]" min="0" max="100" value="0" required onchange="updateTotals()">
                </div>
                <div class="form-group col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm removeProgressBtn">
                        <i class="fas fa-trash"></i> Hapus
                    </button> 
                </div>
            </div>
        </div>
    `;
    
    progressList.appendChild(progressItem);
    progressIndex++;
    progressItem.querySelector('.removeProgressBtn').addEventListener('click', function () {
        progressItem.remove();
        updateTotals();
    });

    updateTotals();
}

// Validasi input progress sebelum submit form
function validateProgressInputs() {
    let isValid = true;
    document.querySelectorAll('.progress-item input').forEach(input => {
        if (input.value === '') {
            input.value = 0; // Set nilai default jika kosong
        }
    });
    return isValid;
}

document.querySelector('form').addEventListener('submit', function(e) {
    if (!validateProgressInputs()) {
        e.preventDefault();
        alert('Mohon isi semua data progress!');
    }
});

// Format date ke dalam bentuk YYYY-MM-DD
function formatDate(date) {
    const d = new Date(date);
    const month = (d.getMonth() + 1).toString().padStart(2, '0');
    const day = d.getDate().toString().padStart(2, '0');
    const year = d.getFullYear();
    return `${year}-${month}-${day}`;
}

// Event listener untuk tombol tambah progress
addProgressBtn.addEventListener('click', function () {
    $(calendarModal).modal('show');
});
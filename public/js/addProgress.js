document.addEventListener('DOMContentLoaded', function () {
    const addProgressBtn = document.getElementById('addProgressBtn');
    const progressList = document.getElementById('progressList');
    const calendarModal = document.getElementById('calendarModal');
    const datePicker = document.getElementById('datePicker');
    let progressIndex = document.querySelectorAll('.progress-item').length;

    // Initialize Flatpickr for date selection
    flatpickr(datePicker, {
        mode: 'single',
        dateFormat: "Y-m-d",
        onChange: function (selectedDates) {
            if (selectedDates.length > 0) {
                const date = selectedDates[0];
                createProgressFields(date);
                $(calendarModal).modal('hide'); // Hide modal after date selection
            }
        },
        minDate: new Date(document.getElementById('tanggal_pelaksanaan').value),
        maxDate: new Date(document.getElementById('tanggal_selesai_pekerjaan').value),
    });

    // Function to create new progress fields
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
        progressIndex++; // Increment progress index for the next entry
    }

    // Function to format date to YYYY-MM-DD
    function formatDate(date) {
        const d = new Date(date);
        const month = (d.getMonth() + 1).toString().padStart(2, '0');
        const day = d.getDate().toString().padStart(2, '0');
        const year = d.getFullYear();
        return `${year}-${month}-${day}`;
    }

    // Show calendar modal on Add Progress button click
    addProgressBtn.addEventListener('click', function () {
        $(calendarModal).modal('show');
    });
});

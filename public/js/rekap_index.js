
    // Enable dragging functionality for the table
    const tableContainer = document.querySelector('.table-responsive');
    let isDragging = false;
    let startX;
    let scrollLeft;

    tableContainer.addEventListener('mousedown', (e) => {
        isDragging = true;
        tableContainer.classList.add('active'); // Add class to change cursor style
        startX = e.pageX - tableContainer.offsetLeft;
        scrollLeft = tableContainer.scrollLeft;
    });

    tableContainer.addEventListener('mouseleave', () => {
        isDragging = false;
        tableContainer.classList.remove('active');
    });

    tableContainer.addEventListener('mouseup', () => {
        isDragging = false;
        tableContainer.classList.remove('active');
    });

    tableContainer.addEventListener('mousemove', (e) => {
        if(!isDragging) return; // Stop the function if not dragging
        e.preventDefault();
        const x = e.pageX - tableContainer.offsetLeft;
        const walk = (x - startX) * 2; // Scroll-fast
        tableContainer.scrollLeft = scrollLeft - walk;
    });

    // CSS style to change cursor when dragging
    document.addEventListener("DOMContentLoaded", function() {
        const style = document.createElement("style");
        style.innerHTML = `
            .table-responsive.active {
                cursor: grabbing;
                cursor: -webkit-grabbing;
            }
            .table-responsive {
                cursor: grab;
                cursor: -webkit-grab;
            }
        `;
        document.head.appendChild(style);
    });




// Variabel yang menyimpan form saat ini untuk konfirmasi penghapusan
let currentForm = null;

// Fungsi untuk menampilkan modal konfirmasi penghapusan
function showDeleteModal(button) {
    currentForm = button.closest('form');
    $('#deleteModal').modal('show');
}

// Event Listener untuk tombol konfirmasi penghapusan
document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
    if (currentForm) {
        currentForm.submit();
    }
});

// Event Listener untuk menjalankan fungsi scroll horizontal pada tabel
document.addEventListener('DOMContentLoaded', function() {
    const tableContainer = document.getElementById('tableContainer');

    let isDown = false;
    let startX;
    let scrollLeft;

    tableContainer.addEventListener('mousedown', (e) => {
        isDown = true;
        tableContainer.classList.add('active');
        startX = e.pageX - tableContainer.offsetLeft;
        scrollLeft = tableContainer.scrollLeft;
    });

    tableContainer.addEventListener('mouseleave', () => {
        isDown = false;
        tableContainer.classList.remove('active');
    });

    tableContainer.addEventListener('mouseup', () => {
        isDown = false;
        tableContainer.classList.remove('active');
    });

    tableContainer.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - tableContainer.offsetLeft;
        const walk = (x - startX) * 1; 
        tableContainer.scrollLeft = scrollLeft - walk;
    });
});

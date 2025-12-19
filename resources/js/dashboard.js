import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', function() {
    initStatusChart();
    initMonthlyChart();
    initWorkHoursCharts();
    fetchSystemInfo();
    // Update server time
    setInterval(updateServerTime, 1000);
    
    // Toggle between chart and table view
    document.getElementById('chartView').addEventListener('click', function() {
        document.getElementById('monthlyTableView').style.display = 'none';
        document.getElementById('monthlyChartView').style.display = 'block';
        this.classList.add('active');
        document.getElementById('tableView').classList.remove('active');
    });
    
    document.getElementById('tableView').addEventListener('click', function() {
        document.getElementById('monthlyTableView').style.display = 'block';
        document.getElementById('monthlyChartView').style.display = 'none';
        this.classList.add('active');
        document.getElementById('chartView').classList.remove('active');
    });
    
    // Date range filter validation
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    
    startDateInput.addEventListener('change', function() {
        if (endDateInput.value && new Date(this.value) > new Date(endDateInput.value)) {
            alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir');
            this.value = '';
        }
    });
    
    endDateInput.addEventListener('change', function() {
        if (startDateInput.value && new Date(this.value) < new Date(startDateInput.value)) {
            alert('Tanggal akhir tidak boleh lebih kecil dari tanggal mulai');
            this.value = '';
        }
    });
    
    // Refresh table button
    document.getElementById('refreshTable').addEventListener('click', function() {
        window.location.reload();
    });
});

// Fetch System Information
function fetchSystemInfo() {
    // Simulate fetching IP address
    setTimeout(() => {
        document.getElementById('ip-address').textContent = '192.168.1.' + Math.floor(Math.random() * 255);
        
        // Simulate location data
        document.getElementById('location').textContent = 'Cikampek, Jawa Barat, Indonesia';
    }, 1000);
    
    // Simulate bandwidth and resource usage updates
    setInterval(() => {
        // Bandwidth simulation
        const bandwidthSpeed = Math.floor(Math.random() * 500) + 50;
        document.getElementById('bandwidth-speed').textContent = `${bandwidthSpeed} Mbps`;
        
        if (bandwidthSpeed < 100) {
            document.getElementById('bandwidth-speed').style.color = '#ff0000';
            document.getElementById('bandwidth-warning').style.display = 'block';
            document.getElementById('bandwidth-warning').textContent = "Peringatan: Kecepatan internet rendah!";
        } else {
            document.getElementById('bandwidth-speed').style.color = '';
            document.getElementById('bandwidth-warning').style.display = 'none';
        }
        
        // CPU usage simulation
        const cpuUsage = Math.floor(Math.random() * 100);
        document.getElementById('cpu-usage').textContent = `${cpuUsage}%`;
        document.getElementById('cpu-progress').style.width = `${cpuUsage}%`;
        
        if (cpuUsage > 80) {
            document.getElementById('cpu-progress').style.backgroundColor = '#e74a3b';
        } else if (cpuUsage > 50) {
            document.getElementById('cpu-progress').style.backgroundColor = '#f6c23e';
        } else {
            document.getElementById('cpu-progress').style.backgroundColor = '#1cc88a';
        }
        
        // Memory usage simulation
        const memoryUsage = Math.floor(Math.random() * 100);
        document.getElementById('memory-usage').textContent = `${memoryUsage}%`;
        document.getElementById('memory-progress').style.width = `${memoryUsage}%`;
        
        if (memoryUsage > 80) {
            document.getElementById('memory-progress').style.backgroundColor = '#e74a3b';
        } else if (memoryUsage > 50) {
            document.getElementById('memory-progress').style.backgroundColor = '#f6c23e';
        } else {
            document.getElementById('memory-progress').style.backgroundColor = '#1cc88a';
        }
    }, 3000);
}

// Update server time
function updateServerTime() {
    const now = new Date();
    const options = { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    };
    document.getElementById('server-time').textContent = now.toLocaleDateString('id-ID', options);
}

    // Update welcome card date and time
    function updateWelcomeDateTime() {
        const now = new Date();
        const day = now.getDate();
        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const month = monthNames[now.getMonth()];
        
        let hours = now.getHours();
        let minutes = now.getMinutes();
        hours = hours < 10 ? '0' + hours : hours;
        minutes = minutes < 10 ? '0' + minutes : minutes;
        
        document.getElementById('welcome-day').textContent = day;
        document.getElementById('welcome-month').textContent = month;
        document.getElementById('welcome-time').textContent = hours + ':' + minutes;
    }
    
    // Update time initially and then every minute
    updateWelcomeDateTime();
    setInterval(updateWelcomeDateTime, 60000);



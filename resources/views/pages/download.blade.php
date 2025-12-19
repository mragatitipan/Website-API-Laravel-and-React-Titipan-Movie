<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report - {{ $dataProcessed->no_systic }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        h2, h4 {
            text-align: center;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-section p {
            margin: 5px 0;
        }
        .progress {
            text-align: center;
        }
        .chart-container {
            width: 100%;
            height: 400px;
            margin-top: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>Karya Asli Rohtek</h2>
    <h4>Tracking Report - {{ $dataProcessed->no_systic }}</h4>

    <div class="info-section">
        <p><strong>No Systic:</strong> {{ $dataProcessed->no_systic }}</p>
        <p><strong>Uraian Pekerjaan:</strong> {{ $dataProcessed->uraian_pekerjaan }}</p>
        <p><strong>Lokasi:</strong> {{ $dataProcessed->lokasi }}</p>
        <p><strong>Status Pekerjaan:</strong> {{ $dataProcessed->status_pekerjaan }}</p>
        <p><strong>Estimasi Waktu Pengerjaan:</strong> {{ $dataProcessed->estimasi_waktu_pengerjaan }} Hari</p>
        <p><strong>Progress:</strong> {{ $dataProcessed->progress_percentage }}%</p>
    </div>

    <!-- Add your chart rendering here, could be using a static image or library -->
    <div class="progress">
        <h3>Progress Chart</h3>
        <img src="chart_placeholder.png" alt="Chart" style="width: 100%; height: auto;">
    </div>

    <!-- Download Button -->
    <div class="download-section" style="text-align: center;">
        <a href="{{ route('downloadReport', ['id' => $dataProcessed->id]) }}" class="btn">Download Report</a>
    </div>
</body>
</html>

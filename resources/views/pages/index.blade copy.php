<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking Systic | PT Rohtek Amanah Global</title>

    <!-- Meta Tags for SEO -->
    <meta name="description" content="Tracking SPK secara real-time">
    <meta name="keywords" content="tracking systic, PT Rohtek Amanah Global">
    <meta name="robots" content="index, follow">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('ui/svg/rohtek1.png') }}" type="image/png">

    <!-- Bootstrap & FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

    <!-- Custom Styles -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: url('{{ asset('ui/svg/bgdepan.png') }}') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar {
            padding: 15px 30px;
            background-color: #003366;
            border-bottom: 2px solid #007bff;
        }

        /* Navbar */
        .navbar {
        padding: 15px 30px;
        background-color: #0a1e40;
        justify-content: space-between;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        }


        .navbar-brand {
        display: flex;
        align-items: center;
        }

        .navbar-brand img {
        max-height: 40px;
        margin-right: 10px;
        }

        .navbar-brand span {
        font-weight: bold;
        font-size: 1.1rem;
        color: #ffffff;
        }


        .navbar-nav .nav-item {
        margin: 0 15px;
        }

        .navbar-nav .nav-link {
        color: #ffffff;
        font-weight: 500;
        font-size: 0.9rem;
        }

        .tracking-section {
            background-image: url('{{ asset('ui/svg/background.jpg') }}'); /* Ganti dengan URL gambar latar belakang */
            background-size: cover;
            background-position: center;
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
            color: #fff;
            margin-top: 30px;
        }

        .tracking-section h2 {
            color: #ffffff;
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 30px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
        }

        .input-group {
            width: 100%;
        }

        .input-group input {
            font-size: 1.1rem;
            border-radius: 10px;
            padding-left: 15px;
        }

        .input-group button {
            font-size: 1.1rem;
            padding: 0.75rem 1.5rem;
            background-color: #007bff;
            border: none;
            font-weight: bold;
            border-radius: 10px;
        }

        .input-group button:hover {
            background-color: #0056b3;
        }

        /* Box padding and alignment */
        .search-box {
            padding: 15px;
            margin-left: auto;
            margin-right: auto;
            max-width: 700px;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.15);
            background-color: #fff;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        /* Feedback Error Message Styling */
        .alert-warning {
            border: 1px solid #f8d7da;
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            font-size: 1.1rem;
            font-weight: bold;
            border-radius: 5px;
            margin-bottom: 30px;
        }
        /* Card Table Styling */
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-header {
            background-color: #003366;
            color: #fff;
            font-size: 1.5rem;
            font-weight: bold;
            padding: 20px;
        }
        .card-body {
            padding: 30px;
        }

        /* Informasi Proyek Layout */
        .info-layout {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .info-column {
            width: 48%;
            display: flex;
            flex-direction: column;
        }

        .chart-container {
            width: 48%;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

       /* Footer */
        .footer {
        background-color: #0a1e40;
        padding: 20px;
        color: #ffffff;
        font-size: 0.9rem;
        text-align: center;
        width: 100%;
        position: relative;
        bottom: 0;
        margin-top: auto;
        }

        .footer .footer-links a {
        color: #ffffff;
        margin: 0 10px;
        text-decoration: none;
        }

        .footer .footer-links a:hover {
        color: #007bff;
        }

        .footer p {
        margin: 0;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .tracking-section {
                padding: 20px;
            }

            .info-layout {
                flex-direction: column;
                align-items: center;
            }

            .info-column {
                width: 100%;
                margin-bottom: 20px;
            }

            .footer {
                font-size: 0.8rem;
            }

            .footer a {
                font-size: 0.9rem;
            }
        }
        /* Hover Card Effects */
        .card-body p {
            font-size: 1.1rem;
            color: #333;
            margin: 10px 0;
        }

        .card-body p strong {
            font-weight: bold;
            color: #003366;
        }

        /* Chart Styling */
        .chart-container canvas {
            max-width: 100% !important;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="#">
        <img src="{{ asset('ui/svg/rohtek1.png') }}" alt="PT Rohtek Logo">
        <span>PT Rohtek Amanah Global</span>
        </a>
        <div class="info-header">
        <div class="navbar-nav ml-auto">
        <a class="btn btn-primary" href="login">Login Administrator</a>
        </div>
    </nav>

    <!-- Tracking Section -->
    <section class="tracking-section">
        <h2 class="text-center">Tracking Systic</h2>

        <!-- Search Form -->
        <form action="{{ route('index') }}" method="get" class="d-flex justify-content-center mb-4">
            <div class="input-group w-75">
                <input type="text" name="no_systic" id="no_systic" class="form-control form-control-lg" placeholder="Masukkan No Systic" value="{{ old('no_systic', $no_systic) }}">
                <button type="submit" class="btn btn-primary btn-lg ms-2">Cari</button>
            </div>
        </form>

        @if ($no_systic && isset($dataProcessed))
        <!-- Data Table Card -->
        <div class="card">
            <div class="card-header">Detail Pekerjaan</div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No Systic</th>
                            <th>Uraian Pekerjaan</th>
                            <th>Lokasi</th>
                            <th>Kode Pengawas</th>
                            <th>No Sementara</th>
                            <th>No SIP</th>
                            <th>Estimasi Waktu (Hari)</th>
                            <th>Bulan Pekerjaan</th>
                            <th>Tanggal Selesai</th>
                            <th>Total Tenaga Kerja</th>
                            <th>Status Pekerjaan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $dataProcessed->no_systic }}</td>
                            <td>{{ $dataProcessed->uraian_pekerjaan }}</td>
                            <td>{{ $dataProcessed->lokasi }}</td>
                            <td>{{ $dataProcessed->kode_pengawas }}</td>
                            <td>{{ $dataProcessed->no_sementara }}</td>
                            <td>{{ $dataProcessed->no_sip }}</td>
                            <td>{{ $dataProcessed->estimasi_waktu_pengerjaan }}</td>
                            <td>{{ $dataProcessed->bulan_pekerjaan }}</td>
                            <td>{{ \Carbon\Carbon::parse($dataProcessed->tanggal_selesai_pekerjaan)->format('d-m-Y') }}</td>
                            <td>{{ $dataProcessed->total_tenaga_kerja }}</td>
                            <td>{{ $dataProcessed->status_pekerjaan }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Progress Bars -->
        <div class="card">
            <div class="card-header">Progress Pekerjaan</div>
            <div class="card-body">
                <div class="progress">
                    <div class="progress-bar" role="progressbar" style="width: {{ $dataProcessed->progress_percentage ?? 0 }}%;" aria-valuenow="{{ $dataProcessed->progress_percentage ?? 0 }}" aria-valuemin="0" aria-valuemax="100">{{ $dataProcessed->progress_percentage ?? 0 }}%</div>
                </div>
                <div class="progress mt-4">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $dataProcessed->total_tenaga_kerja ?? 0 }}%;" aria-valuenow="{{ $dataProcessed->total_tenaga_kerja ?? 0 }}" aria-valuemin="0" aria-valuemax="100">{{ $dataProcessed->total_tenaga_kerja ?? 0 }} Tenaga Kerja</div>
                </div>
            </div>
        </div>

        <!-- Informasi Proyek with Chart -->
        <div class="info-layout">
            <div class="info-column">
                <div class="card">
                    <div class="card-header">Informasi Proyek</div>
                    <div class="card-body">
                        <p><strong>No SIP:</strong> {{ $dataProcessed->no_sip }}</p>
                        <p><strong>Kode Pengawas:</strong> {{ $dataProcessed->kode_pengawas }}</p>
                        <p><strong>Tanggal Pelaksanaan:</strong> {{ \Carbon\Carbon::parse($dataProcessed->tanggal_pelaksanaan)->format('d-m-Y') }}</p>
                        <p><strong>Tanggal Selesai:</strong> {{ \Carbon\Carbon::parse($dataProcessed->tanggal_selesai_pekerjaan)->format('d-m-Y') }}</p>
                        <p><strong>Total Tenaga Kerja Bulan Ini:</strong> {{ $dataProcessed->rekapitulasi_tenaga_kerja_bulan_ini }}</p>
                    </div>
                </div>
            </div>

        </div>

        @else
        <div class="alert alert-warning text-center">
            <strong>Data tidak ditemukan!</strong> Pastikan No Systic yang dimasukkan sudah benar.
        </div>
        @endif
    </section>

   <!-- Footer -->
   <footer class="footer">
    <div class="footer-links">
      <a href="https://rohtekamanah.com" target="_blank">Website Utama</a> |
      <a href="https://support.rohtekamanah.com" target="_blank">Database Link</a> |
      <a href="https://si.rohtekamanah.com" target="_blank">Konstruksi</a> |
      <a href="https://rohteku.com" target="_blank">Anak Perusahaan</a>
    </div>
    <p>&copy; {{ now()->year }} PT Rohtek Amanah Global. Architecture, Construction & Maintenance.</p>
  </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

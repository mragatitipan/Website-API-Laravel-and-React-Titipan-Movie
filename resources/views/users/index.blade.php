@extends('layout.admin')

@section('content')
<div class="modern-container">
    <!-- Flash Messages -->
    @if(session('status'))
        <div class="alert-modern alert-success-modern">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('status') }}</span>
            <button class="alert-close" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    @if(session('danger'))
        <div class="alert-modern alert-danger-modern">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ session('danger') }}</span>
            <button class="alert-close" onclick="this.parentElement.remove()">×</button>
        </div>
    @endif

    <!-- Welcome Header with Animated Gradient -->
    <div class="welcome-header animated-gradient-header">
        <div class="welcome-content">
            <div class="welcome-icon">
                <i class="fas fa-users-cog"></i>
            </div>
            <div class="welcome-text">
                <h1 class="welcome-title">Control Panel Users</h1>
                <p class="welcome-subtitle">Kelola Akun Pengguna Sistem Secara Realtime</p>
            </div>
        </div>
        
        <!-- Stats Mini Cards -->
        <div class="stats-mini-grid">
            <div class="stat-mini-card">
                <div class="stat-mini-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-mini-info">
                    <div class="stat-mini-value">{{ $users->count() }}</div>
                    <div class="stat-mini-label">Total Users</div>
                </div>
            </div>
            <div class="stat-mini-card">
                <div class="stat-mini-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="stat-mini-info">
                    <div class="stat-mini-value" id="adminCount">0</div>
                    <div class="stat-mini-label">Admin</div>
                </div>
            </div>
            <div class="stat-mini-card">
                <div class="stat-mini-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <div class="stat-mini-info">
                    <div class="stat-mini-value" id="staffCount">0</div>
                    <div class="stat-mini-label">Staff</div>
                </div>
            </div>
            <div class="stat-mini-card">
                <div class="stat-mini-icon">
                    <i class="fas fa-signal"></i>
                </div>
                <div class="stat-mini-info">
                    <div class="stat-mini-value" id="onlineCount">0</div>
                    <div class="stat-mini-label">Online</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="action-row">
        <!-- Table Section -->
        <div class="users-table-card">
            <div class="action-card">
                <div class="action-card-header">
                    <i class="fas fa-table"></i>
                    <h3>Daftar Pengguna</h3>
                    <a href="{{ url('/home/create') }}" class="btn-add-user">
                        <i class="fas fa-user-plus"></i>
                        <span>Tambah User</span>
                    </a>
                </div>
                <div class="action-card-body">
                    <!-- Search and Show Entries -->
                    <div class="table-controls">
                        <div class="entries-control">
                            <label>Tampilkan:</label>
                            <select id="entriesSelect" class="pagination-select">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                            <span>data</span>
                        </div>
                        <div class="search-wrapper-modern">
                            <i class="fas fa-search search-icon-modern"></i>
                            <input type="text" id="searchInput" class="search-input-modern" placeholder="Cari nama, email, atau role...">
                            <button class="search-clear-modern" id="searchClear" onclick="clearSearch()" style="display:none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive-modern">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th class="text-center">NO</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="userTable">
                                @foreach($users as $user)
                                    <tr id="user-{{ $user->id }}" data-role="{{ $user->role }}">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="user-info">
                                                <div class="user-avatar">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <span class="user-name">{{ ucwords($user->name) }}</span>
                                            </div>
                                        </td>
                                        <td>{{ strtolower($user->email) }}</td>
                                        <td>
                                            <span class="role-badge role-{{ strtolower($user->role) }}">
                                                @switch($user->role)
                                                    @case('Admin')
                                                        <i class="fas fa-crown"></i> Administrator
                                                        @break
                                                    @case('Staff')
                                                        <i class="fas fa-eye"></i> Staff (View Only)
                                                        @break
                                                    @case('Staff2')
                                                        <i class="fas fa-cogs"></i> Staff Proses
                                                        @break
                                                    @case('Staff3')
                                                        <i class="fas fa-warehouse"></i> Staff Gudang
                                                        @break
                                                    @case('Staff4')
                                                        <i class="fas fa-user-friends"></i> Staff HRD
                                                        @break
                                                    @case('Staff5')
                                                        <i class="fas fa-money-bill-wave"></i> Staff Keuangan
                                                        @break
                                                    @case('Staff6')
                                                        <i class="fas fa-laptop-code"></i> Staff IT
                                                        @break
                                                    @case('Staff7')
                                                        <i class="fas fa-shopping-cart"></i> Staff Purchase
                                                        @break
                                                    @default
                                                        <i class="fas fa-question"></i> Unknown
                                                @endswitch
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span id="status-{{ $user->id }}" class="status-badge status-offline">
                                                <i class="fas fa-circle"></i> Offline
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="action-buttons">
                                                <a href="/home/{{ $user->id }}/edit" class="btn-action btn-edit">
                                                    <i class="fas fa-user-edit"></i>
                                                    <span>Edit</span>
                                                </a>
                                                <form action="/home/{{ $user->id }}" method="POST" style="display: inline;" onsubmit="return confirm('Anda yakin akan menghapus data {{ $user->name }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-action btn-delete">
                                                        <i class="fas fa-trash"></i>
                                                        <span>Hapus</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Info Section -->
        <div class="user-info-card">
            <div class="action-card">
                <div class="action-card-header">
                    <i class="fas fa-user-circle"></i>
                    <h3>Informasi Pengguna</h3>
                </div>
                <div class="action-card-body">
                    <div class="current-user-info">
                        <div class="user-avatar-large">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <h4 class="user-name-large">{{ Auth::user()->name }}</h4>
                        <p class="user-role-large">{{ Auth::user()->role }}</p>
                    </div>

                    <div class="info-list">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Email</span>
                                <span class="info-value">{{ Auth::user()->email }}</span>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-wifi"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">IP Address</span>
                                <span class="info-value" id="userIP">192.168.1.1</span>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Bandwidth</span>
                                <span class="info-value" id="userBandwidth">0 Mbps</span>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-memory"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Memory</span>
                                <span class="info-value" id="userMemory">0 GB</span>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fas fa-hdd"></i>
                            </div>
                            <div class="info-content">
                                <span class="info-label">Storage</span>
                                <span class="info-value" id="userStorage">0 GB</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* ============================================================================
   ANIMASI GRADIENT BERGERAK
============================================================================ */
@keyframes gradient-animation {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* ============================================================================
   MODERN CONTAINER
============================================================================ */
.modern-container {
    padding: 10px 20px;
    background: #f5f7fa;
    min-height: 100vh;
}

/* ============================================================================
   ALERT MODERN
============================================================================ */
.alert-modern {
    padding: 14px 20px;
    border-radius: 12px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    animation: slideInDown 0.4s ease-out;
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.alert-success-modern {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
    border-left: 4px solid #28a745;
}

.alert-danger-modern {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
    border-left: 4px solid #dc3545;
}

.alert-close {
    margin-left: auto;
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    opacity: 0.6;
    transition: opacity 0.3s;
}

.alert-close:hover {
    opacity: 1;
}

/* ============================================================================
   WELCOME HEADER - SAMA SEPERTI HOME
============================================================================ */
.animated-gradient-header {
    position: relative;
    background: linear-gradient(-45deg, #213823, #375534, #6B9071, #0F2A1D);
    background-size: 400% 400%;
    animation: gradient-animation 15s ease infinite;
    box-shadow: 0 10px 25px rgba(30, 60, 114, 0.5);
    border-radius: 20px;
    padding: 20px 30px;
    margin-bottom: 15px;
    overflow: hidden;
}

.welcome-content {
    position: relative;
    z-index: 1;
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 18px;
}

.welcome-icon {
    width: 70px;
    height: 70px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    color: white;
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
}

.welcome-text {
    flex: 1;
}

.welcome-title {
    font-size: 32px;
    font-weight: 800;
    color: white;
    margin: 0;
    letter-spacing: -0.5px;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
}

.welcome-subtitle {
    font-size: 16px;
    color: rgba(255,255,255,0.9);
    margin: 5px 0 0 0;
}

/* Stats Mini Cards */
.stats-mini-grid {
    position: relative;
    z-index: 1;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 15px;
}

.stat-mini-card {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border-radius: 14px;
    padding: 15px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: all 0.3s ease;
    border: 1px solid rgba(255,255,255,0.2);
}

.stat-mini-card:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-3px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
}

.stat-mini-icon {
    width: 45px;
    height: 45px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
}

.stat-mini-info {
    flex: 1;
}

.stat-mini-value {
    font-size: 24px;
    font-weight: 700;
    color: white;
    line-height: 1;
}

.stat-mini-label {
    font-size: 12px;
    color: rgba(255,255,255,0.8);
    margin-top: 3px;
}

/* ============================================================================
   ACTION ROW
============================================================================ */
.action-row {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 20px;
    margin-bottom: 25px;
}

/* ============================================================================
   ACTION CARDS
============================================================================ */
.action-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    overflow: hidden;
    transition: all 0.3s ease;
}

.action-card:hover {
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    transform: translateY(-2px);
}

.action-card-header {
    background: linear-gradient(-45deg, #213823, #375534, #6B9071, #0F2A1D);
    background-size: 400% 400%;
    animation: gradient-animation 15s ease infinite;
    padding: 18px 24px;
    display: flex;
    align-items: center;
    gap: 12px;
    color: white;
}

.action-card-header i {
    font-size: 22px;
}

.action-card-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    flex: 1;
}

.btn-add-user {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.3);
    border-radius: 10px;
    color: white;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-add-user:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: scale(1.05);
    color: white;
}

.action-card-body {
    padding: 24px;
}

/* ============================================================================
   TABLE CONTROLS
============================================================================ */
.table-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    gap: 15px;
}

.entries-control {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #495057;
}

.pagination-select {
    padding: 6px 12px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.pagination-select:focus {
    outline: none;
    border-color: #6B9071;
}

.search-wrapper-modern {
    position: relative;
    flex: 1;
    max-width: 400px;
}

.search-icon-modern {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #6B9071;
    font-size: 16px;
    pointer-events: none;
}

.search-input-modern {
    width: 100%;
    padding: 10px 40px 10px 45px;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.search-input-modern:focus {
    outline: none;
    border-color: #6B9071;
    box-shadow: 0 0 0 4px rgba(107, 144, 113, 0.1);
}

.search-clear-modern {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: #dc3545;
    color: white;
    border: none;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 12px;
}

.search-clear-modern:hover {
    background: #c82333;
    transform: translateY(-50%) scale(1.1);
}

/* ============================================================================
   TABLE MODERN
============================================================================ */
.table-responsive-modern {
    max-height: 600px;
    overflow-y: auto;
    border-radius: 12px;
    border: 1px solid #e9ecef;
}

.table-modern {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 14px;
}

.table-modern thead {
    position: sticky;
    top: 0;
    z-index: 10;
}

.table-modern thead tr {
    background: linear-gradient(135deg, #213823 0%, #375534 100%);
}

.table-modern th {
    padding: 14px 16px;
    color: white;
    font-weight: 600;
    text-align: left;
    border-bottom: 2px solid #0F2A1D;
}

.table-modern th.text-center {
    text-align: center;
}

.table-modern tbody tr {
    transition: all 0.3s ease;
    border-bottom: 1px solid #e9ecef;
}

.table-modern tbody tr:hover {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    transform: scale(1.01);
}

.table-modern td {
    padding: 14px 16px;
    vertical-align: middle;
}

.table-modern td.text-center {
    text-align: center;
}

/* User Info in Table */
.user-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.user-avatar {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #213823, #6B9071);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 14px;
}

.user-name {
    font-weight: 600;
    color: #213823;
}

/* Role Badge */
.role-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.role-admin {
    background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
    color: #856404;
}

.role-staff,
.role-staff2,
.role-staff3,
.role-staff4,
.role-staff5,
.role-staff6,
.role-staff7 {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    color: #0d47a1;
}

/* Status Badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.status-online {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
}

.status-online i {
    color: #28a745;
    animation: pulse 2s infinite;
}

.status-offline {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    color: #6c757d;
}

.status-offline i {
    color: #adb5bd;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
}

.btn-action {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    border: none;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
}

.btn-edit {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    color: white;
}

.btn-edit:hover {
    background: linear-gradient(135deg, #138496 0%, #117a8b 100%);
    transform: scale(1.05);
    color: white;
}

.btn-delete {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
}

.btn-delete:hover {
    background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
    transform: scale(1.05);
}

/* ============================================================================
   USER INFO CARD
============================================================================ */
.current-user-info {
    text-align: center;
    padding: 20px;
    border-bottom: 1px solid #e9ecef;
    margin-bottom: 20px;
}

.user-avatar-large {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #213823, #6B9071);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 32px;
    margin: 0 auto 15px;
    box-shadow: 0 4px 12px rgba(33, 56, 35, 0.3);
}

.user-name-large {
    font-size: 20px;
    font-weight: 700;
    color: #213823;
    margin: 0 0 5px 0;
}

.user-role-large {
    font-size: 14px;
    color: #6c757d;
    margin: 0;
}

/* Info List */
.info-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.info-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.info-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #213823, #6B9071);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

.info-content {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.info-label {
    font-size: 11px;
    color: #6c757d;
    text-transform: uppercase;
    font-weight: 600;
}

.info-value {
    font-size: 14px;
    color: #213823;
    font-weight: 600;
}

/* ============================================================================
   RESPONSIVE DESIGN
============================================================================ */
@media (max-width: 1200px) {
    .action-row {
        grid-template-columns: 1fr;
    }
    
    .user-info-card {
        order: -1;
    }
}

@media (max-width: 768px) {
    .modern-container {
        padding: 10px 15px;
    }
    
    .welcome-title {
        font-size: 24px;
    }
    
    .welcome-subtitle {
        font-size: 14px;
    }
    
    .welcome-icon {
        width: 50px;
        height: 50px;
        font-size: 24px;
    }
    
    .stats-mini-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .table-controls {
        flex-direction: column;
                align-items: stretch;
    }
    
    .search-wrapper-modern {
        max-width: 100%;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .table-modern {
        font-size: 12px;
    }
    
    .table-modern th,
    .table-modern td {
        padding: 10px 8px;
    }
}

@media (max-width: 480px) {
    .welcome-content {
        flex-direction: column;
        text-align: center;
    }
    
    .stats-mini-grid {
        grid-template-columns: 1fr;
    }
    
    .btn-add-user span {
        display: none;
    }
}

/* ============================================================================
   SCROLLBAR STYLING
============================================================================ */
.table-responsive-modern::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.table-responsive-modern::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.table-responsive-modern::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #213823, #6B9071);
    border-radius: 10px;
}

.table-responsive-modern::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(180deg, #375534, #6B9071);
}
</style>

<script>
// ============================================================================
// GLOBAL VARIABLES
// ============================================================================
let allRows = [];
let currentEntriesCount = 10;

// ============================================================================
// MAIN INITIALIZATION
// ============================================================================
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Users Control Panel Initialized');
    
    initializeUserManagement();
    initializeSearch();
    initializeEntriesControl();
    initializeUserStats();
    initializeDynamicData();
    detectFlashMessages();
});

// ============================================================================
// USER MANAGEMENT
// ============================================================================
function initializeUserManagement() {
    const userTable = document.getElementById('userTable');
    if (!userTable) return;
    
    allRows = Array.from(userTable.querySelectorAll('tr'));
    
    // Count roles
    countRoles();
    
    // Simulate online status (random)
    simulateOnlineStatus();
}

function countRoles() {
    let adminCount = 0;
    let staffCount = 0;
    
    allRows.forEach(row => {
        const role = row.getAttribute('data-role');
        if (role === 'Admin') {
            adminCount++;
        } else if (role && role.startsWith('Staff')) {
            staffCount++;
        }
    });
    
    animateCounter('adminCount', adminCount);
    animateCounter('staffCount', staffCount);
}

function simulateOnlineStatus() {
    allRows.forEach((row, index) => {
        const userId = row.id.replace('user-', '');
        const statusBadge = document.getElementById('status-' + userId);
        
        if (statusBadge) {
            // Random online status (30% chance)
            const isOnline = Math.random() < 0.3;
            
            setTimeout(() => {
                if (isOnline) {
                    statusBadge.className = 'status-badge status-online';
                    statusBadge.innerHTML = '<i class="fas fa-circle"></i> Online';
                } else {
                    statusBadge.className = 'status-badge status-offline';
                    statusBadge.innerHTML = '<i class="fas fa-circle"></i> Offline';
                }
            }, index * 100);
        }
    });
    
    // Update online count
    setTimeout(() => {
        const onlineCount = document.querySelectorAll('.status-online').length;
        animateCounter('onlineCount', onlineCount);
    }, allRows.length * 100 + 100);
}

function animateCounter(elementId, finalValue) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    let startValue = 0;
    const duration = 1000;
    const increment = finalValue / (duration / 16);
    
    const timer = setInterval(() => {
        startValue += increment;
        element.textContent = Math.floor(startValue);
        
        if (startValue >= finalValue) {
            element.textContent = finalValue;
            clearInterval(timer);
        }
    }, 16);
}

// ============================================================================
// SEARCH FUNCTIONALITY
// ============================================================================
function initializeSearch() {
    const searchInput = document.getElementById('searchInput');
    const searchClear = document.getElementById('searchClear');
    
    if (!searchInput) return;
    
    let searchTimeout;
    
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        
        const query = this.value.toLowerCase().trim();
        
        if (query === '') {
            searchClear.style.display = 'none';
            showAllRows();
            return;
        }
        
        searchClear.style.display = 'flex';
        
        searchTimeout = setTimeout(() => {
            filterRows(query);
        }, 300);
    });
}

function clearSearch() {
    const searchInput = document.getElementById('searchInput');
    const searchClear = document.getElementById('searchClear');
    
    if (searchInput) {
        searchInput.value = '';
    }
    
    if (searchClear) {
        searchClear.style.display = 'none';
    }
    
    showAllRows();
}

function filterRows(query) {
    let visibleCount = 0;
    
    allRows.forEach(row => {
        const cells = Array.from(row.querySelectorAll('td'));
        const matches = cells.some(cell => cell.textContent.toLowerCase().includes(query));
        
        if (matches && visibleCount < currentEntriesCount) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
}

function showAllRows() {
    allRows.forEach((row, index) => {
        if (index < currentEntriesCount) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// ============================================================================
// ENTRIES CONTROL
// ============================================================================
function initializeEntriesControl() {
    const entriesSelect = document.getElementById('entriesSelect');
    
    if (!entriesSelect) return;
    
    entriesSelect.addEventListener('change', function() {
        currentEntriesCount = parseInt(this.value, 10);
        showAllRows();
    });
    
    // Initial display
    showAllRows();
}

// ============================================================================
// USER STATS (Dynamic Data)
// ============================================================================
function initializeUserStats() {
    // Set static IP
    const ipElement = document.getElementById('userIP');
    if (ipElement) {
        ipElement.textContent = '192.168.1.1';
    }
}

function initializeDynamicData() {
    updateDynamicData();
    setInterval(updateDynamicData, 5000);
}

function updateDynamicData() {
    const randomBandwidth = getRandomValue(50, 500);
    const randomMemory = getRandomValue(4, 32);
    const randomStorage = getRandomValue(100, 512);
    
    animateValue(
        'userBandwidth',
        parseFloat(document.getElementById('userBandwidth')?.textContent) || 0,
        parseFloat(randomBandwidth),
        1000,
        'Mbps'
    );
    
    animateValue(
        'userMemory',
        parseFloat(document.getElementById('userMemory')?.textContent) || 0,
        parseFloat(randomMemory),
        1000,
        'GB'
    );
    
    animateValue(
        'userStorage',
        parseFloat(document.getElementById('userStorage')?.textContent) || 0,
        parseFloat(randomStorage),
        1000,
        'GB'
    );
}

function getRandomValue(min, max) {
    return (Math.random() * (max - min) + min).toFixed(2);
}

function animateValue(elementId, start, end, duration, unit) {
    const element = document.getElementById(elementId);
    if (!element) return;
    
    const range = end - start;
    const stepTime = Math.abs(Math.floor(duration / range));
    let current = start;
    const increment = end > start ? 1 : -1;
    
    const timer = setInterval(() => {
        current += increment;
        element.textContent = `${current.toFixed(2)} ${unit}`;
        
        if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
            clearInterval(timer);
            element.textContent = `${end.toFixed(2)} ${unit}`;
        }
    }, stepTime);
}

// ============================================================================
// FLASH MESSAGES
// ============================================================================
function detectFlashMessages() {
    const successAlert = document.querySelector('.alert-success-modern');
    const dangerAlert = document.querySelector('.alert-danger-modern');
    
    if (successAlert) {
        setTimeout(() => {
            successAlert.style.opacity = '0';
            setTimeout(() => successAlert.remove(), 300);
        }, 5000);
    }
    
    if (dangerAlert) {
        setTimeout(() => {
            dangerAlert.style.opacity = '0';
            setTimeout(() => dangerAlert.remove(), 300);
        }, 5000);
    }
}

// ============================================================================
// UTILITY FUNCTIONS
// ============================================================================
function showNotification(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert-modern alert-${type}-modern`;
    alertDiv.style.position = 'fixed';
    alertDiv.style.top = '20px';
    alertDiv.style.right = '20px';
    alertDiv.style.zIndex = '9999';
    alertDiv.style.minWidth = '300px';
    alertDiv.style.maxWidth = '500px';
    alertDiv.style.animation = 'slideInDown 0.4s ease-out';
    
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check' : 'times'}-circle"></i>
        <span>${message}</span>
        <button class="alert-close" onclick="this.parentElement.remove()">×</button>
    `;
    
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        alertDiv.style.opacity = '0';
        setTimeout(() => alertDiv.remove(), 300);
    }, 5000);
}

// ============================================================================
// REFRESH ONLINE STATUS PERIODICALLY
// ============================================================================
setInterval(() => {
    simulateOnlineStatus();
}, 30000); // Refresh every 30 seconds
</script>
@endsection
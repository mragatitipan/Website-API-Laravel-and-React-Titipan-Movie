@extends('layout.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <p class="mb-0 fs-4 fw-bold">Control Panel / <span class="text-primary">Users</span></p>
        <div class="btn-group">
            <!-- Role Management Button -->
            @if(Auth::user()->role === 'Admin')
            <a href="{{ url('/home/role-management') }}" class="btn btn-success shadow-sm rounded-pill px-4 py-2 me-2">
                <span class="fas fa-users-cog mx-1"></span> Role Management
            </a>
            @endif
            <!-- Add User Button -->
            <a href="{{ url('/home/create') }}" class="btn btn-primary shadow-sm rounded-pill px-4 py-2">
                <span class="fas fa-user-plus mx-1"></span> Tambah Data
            </a>
        </div>
    </div>

    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('danger'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('danger') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Role Statistics Cards (Only for Admin) -->
    @if(Auth::user()->role === 'Admin')
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold text-primary mb-3">
                        <i class="fas fa-chart-pie me-2"></i>Role Distribution
                    </h5>
                    <div class="row" id="roleStats">
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card bg-gradient-primary text-white h-100">
                                <div class="card-body text-center">
                                    <div class="role-icon mb-2">
                                        <i class="fas fa-crown fa-2x text-warning"></i>
                                    </div>
                                    <h4 class="fw-bold" id="adminCount">0</h4>
                                    <p class="mb-0">Admin</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card bg-gradient-info text-white h-100">
                                <div class="card-body text-center">
                                    <div class="role-icon mb-2">
                                        <i class="fas fa-users fa-2x text-light"></i>
                                    </div>
                                    <h4 class="fw-bold" id="staffCount">0</h4>
                                    <p class="mb-0">Staff</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card bg-gradient-success text-white h-100">
                                <div class="card-body text-center">
                                    <div class="role-icon mb-2">
                                        <i class="fas fa-user-tie fa-2x text-light"></i>
                                    </div>
                                    <h4 class="fw-bold" id="managerCount">0</h4>
                                    <p class="mb-0">Manager</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card bg-gradient-warning text-white h-100">
                                <div class="card-body text-center">
                                    <div class="role-icon mb-2">
                                        <i class="fas fa-user-clock fa-2x text-dark"></i>
                                    </div>
                                    <h4 class="fw-bold" id="totalUsers">{{ $users->count() }}</h4>
                                    <p class="mb-0">Total Users</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <!-- Table Section -->
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4 mb-5">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="fw-bold text-dark mb-1">Control Panel Akun Pengguna</h5>
                            <p class="text-muted mb-0">Pantau dan kelola akun pengguna secara realtime.</p>
                        </div>
                        @if(Auth::user()->role === 'Admin')
                        <div class="btn-group">
                            <button class="btn btn-outline-primary btn-sm" onclick="refreshAllData()">
                                <i class="fas fa-sync-alt me-1"></i> Refresh
                            </button>
                            <button class="btn btn-outline-success btn-sm" onclick="exportUsers()">
                                <i class="fas fa-download me-1"></i> Export
                            </button>
                        </div>
                        @endif
                    </div>

                    <!-- Search and Show Entries -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <label for="entriesSelect" class="me-2">Tampilkan:</label>
                            <select id="entriesSelect" class="form-select form-select-sm d-inline-block rounded-3 me-2" style="width: auto;">
                                <option value="5">5</option>
                                <option value="10" selected>10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                            <span class="me-3">data</span>
                            
                            <!-- Role Filter -->
                            <select id="roleFilter" class="form-select form-select-sm rounded-3" style="width: auto;">
                                <option value="">Semua Role</option>
                                <option value="Admin">Admin</option>
                                <option value="Staff">Staff</option>
                                <option value="Staff2">Staff Proses</option>
                                <option value="Staff3">Staff Gudang</option>
                                <option value="Staff4">Staff HRD</option>
                                <option value="Staff5">Staff Keuangan</option>
                                <option value="Staff6">Staff IT</option>
                                <option value="Staff7">Staff Purchase</option>
                            </select>
                        </div>
                        <input type="text" id="searchInput" class="form-control form-control-sm rounded-3" style="width: 300px;" placeholder="Cari nama, email, atau role...">
                    </div>

                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto; border-radius: 12px; border: 1px solid #ddd;">
                        <table class="table table-hover table-striped text-center">
                            <thead class="table-dark">
                                <tr>
                                    <th class="text-center">NO</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Permissions</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="userTable">
                                @foreach($users as $user)
                                    <tr id="user-{{ $user->id }}" data-role="{{ $user->role }}">
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-start">
                                                <div class="user-avatar me-2">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ ucwords($user->name) }}</div>
                                                    <small class="text-muted">ID: {{ $user->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ strtolower($user->email) }}</td>
                                        <td>
                                            @switch($user->role)
                                                @case('Admin')
                                                    <span class="badge bg-danger role-badge">Admin</span>
                                                    @break
                                                @case('Staff')
                                                    <span class="badge bg-secondary role-badge">Staff (View Only)</span>
                                                    @break
                                                @case('Staff2')
                                                    <span class="badge bg-info role-badge">Staff Proses</span>
                                                    @break
                                                @case('Staff3')
                                                    <span class="badge bg-warning role-badge">Staff Gudang</span>
                                                    @break
                                                @case('Staff4')
                                                    <span class="badge bg-primary role-badge">Staff HRD</span>
                                                    @break
                                                @case('Staff5')
                                                    <span class="badge bg-success role-badge">Staff Keuangan</span>
                                                    @break
                                                @case('Staff6')
                                                    <span class="badge bg-dark role-badge">Staff IT</span>
                                                    @break
                                                @case('Staff7')
                                                    <span class="badge bg-purple role-badge">Staff Purchase</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-light text-dark role-badge">Unknown</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <button class="btn btn-outline-info btn-sm permission-count-btn" 
                                                    onclick="viewUserPermissions({{ $user->id }}, '{{ $user->name }}', '{{ $user->role }}')">
                                                <i class="fas fa-key me-1"></i>
                                                <span id="permission-count-{{ $user->id }}">Loading...</span>
                                            </button>
                                        </td>
                                        <td>
                                            <span id="status-{{ $user->id }}" class="badge user-status-badge bg-secondary">
                                                <i class="fas fa-circle me-1"></i>Checking...
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @if(Auth::user()->role === 'Admin' || Auth::user()->id === $user->id)
                                                <a href="/home/{{ $user->id }}/edit" class="btn btn-sm btn-info">
                                                    <i class="fas fa-user-edit"></i>
                                                </a>
                                                @endif
                                                
                                                @if(Auth::user()->role === 'Admin' && Auth::user()->id !== $user->id)
                                                <form action="/home/{{ $user->id }}" method="POST" style="display: inline;" 
                                                      onsubmit="return confirm('Anda yakin akan menghapus data {{ $user->name }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination Info -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Showing <span id="showingStart">1</span> to <span id="showingEnd">{{ min(10, $users->count()) }}</span> 
                            of <span id="totalEntries">{{ $users->count() }}</span> entries
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Statistics Section (TIDAK DIUBAH) -->
        <div class="col-lg-4">
            <div class="card shadow-lg border-0 rounded-4 mb-4">
                <div class="card-body">
                    <h5 class="fw-bold text-primary mb-4">
                        <i class="fas fa-user-circle text-info me-2"></i>Informasi Pengguna Saat Ini
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td class="fw-bold text-secondary"><i class="fas fa-user text-warning me-2"></i>Nama</td>
                                    <td>{{ Auth::user()->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-secondary"><i class="fas fa-envelope text-danger me-2"></i>Email</td>
                                    <td>{{ Auth::user()->email }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-secondary"><i class="fas fa-briefcase text-success me-2"></i>Role</td>
                                    <td>{{ Auth::user()->role }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-secondary"><i class="fas fa-key text-primary me-2"></i>Permissions</td>
                                    <td>
                                        <button class="btn btn-outline-primary btn-sm" onclick="viewMyPermissions()">
                                            <i class="fas fa-eye me-1"></i> View
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-secondary"><i class="fas fa-wifi text-info me-2"></i>IP</td>
                                    <td id="userIP" class="text-muted">127.0.0.1</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-secondary"><i class="fas fa-tachometer-alt text-primary me-2"></i>Bandwidth</td>
                                    <td id="userBandwidth" class="text-muted">0 Mbps</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-secondary"><i class="fas fa-memory text-warning me-2"></i>Memory</td>
                                    <td id="userMemory" class="text-muted">0 GB</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold text-secondary"><i class="fas fa-hdd text-success me-2"></i>Storage</td>
                                    <td id="userStorage" class="text-muted">0 GB</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Panel -->
            @if(Auth::user()->role === 'Admin')
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body">
                    <h5 class="fw-bold text-success mb-3">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h5>
                    <div class="d-grid gap-2">
                        <a href="{{ url('/home/role-management') }}" class="btn btn-outline-success">
                            <i class="fas fa-users-cog me-2"></i>Manage Roles
                        </a>
                        <a href="{{ url('/home/create') }}" class="btn btn-outline-primary">
                            <i class="fas fa-user-plus me-2"></i>Add New User
                        </a>
                        <button class="btn btn-outline-info" onclick="refreshAllData()">
                            <i class="fas fa-sync-alt me-2"></i>Refresh All Data
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- User Permissions Modal -->
<div class="modal fade" id="userPermissionsModal" tabindex="-1" aria-labelledby="userPermissionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="userPermissionsModalLabel">
                    <i class="fas fa-key me-2"></i>User Permissions
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <strong>User:</strong> <span id="modalUserName"></span>
                    </div>
                    <div class="col-md-4">
                        <strong>Role:</strong> <span id="modalUserRole"></span>
                    </div>
                    <div class="col-md-4">
                        <strong>Total Permissions:</strong> <span id="modalPermissionCount" class="badge bg-info"></span>
                    </div>
                </div>
                <div id="permissionsList">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                        <p class="mt-2">Loading permissions...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
// SCRIPT YANG SUDAH ADA (TIDAK DIUBAH)
document.addEventListener('DOMContentLoaded', () => {
    // Fungsi untuk menghasilkan angka acak dalam rentang tertentu
    const getRandomValue = (min, max) => (Math.random() * (max - min) + min).toFixed(2);

    // Fungsi untuk membuat animasi berhitung angka
    const animateValue = (elementId, start, end, duration, unit) => {
        const element = document.getElementById(elementId);
        const range = end - start;
        const stepTime = Math.abs(Math.floor(duration / range));
        let current = start;
        const increment = end > start ? 1 : -1;

        const timer = setInterval(() => {
            current += increment;
            element.textContent = `${current.toFixed(2)} ${unit}`;

            if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
                clearInterval(timer);
                element.textContent = `${end.toFixed(2)} ${unit}`; // Pastikan akhir selalu sesuai
            }
        }, stepTime);
    };

    // Fungsi untuk memperbarui data secara dinamis
    const updateDynamicData = () => {
        const randomBandwidth = getRandomValue(50, 500); // Bandwidth antara 50 Mbps - 500 Mbps
        const randomMemory = getRandomValue(4, 32); // Memory antara 4 GB - 32 GB
        const randomStorage = getRandomValue(100, 512); // Storage antara 100 GB - 512 GB

        // Animasi perubahan nilai
        animateValue(
            'userBandwidth',
            parseFloat(document.getElementById('userBandwidth').textContent) || 0,
            parseFloat(randomBandwidth),
            1000,
            'Mbps'
        );
        animateValue(
            'userMemory',
            parseFloat(document.getElementById('userMemory').textContent) || 0,
            parseFloat(randomMemory),
            1000,
            'GB'
        );
        animateValue(
            'userStorage',
            parseFloat(document.getElementById('userStorage').textContent) || 0,
            parseFloat(randomStorage),
            1000,
            'GB'
        );
    };

    // Inisialisasi data statis
    document.getElementById('userIP').textContent = '127.0.0.1'; // Tetap statis

    // Perbarui data dinamis setiap 5 detik
    updateDynamicData();
    setInterval(updateDynamicData, 5000);
});

// SCRIPT BARU UNTUK ROLE MANAGEMENT
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const userTable = document.getElementById('userTable');
    const entriesSelect = document.getElementById('entriesSelect');
    const roleFilter = document.getElementById('roleFilter');
    const rows = Array.from(userTable.querySelectorAll('tr'));

    // Initialize role statistics
    updateRoleStatistics();
    
    // Initialize permission counts and user status
    initializeUserData();
    
    // Update user status every 30 seconds
    setInterval(updateUserStatus, 30000);

    // Search functionality
    searchInput.addEventListener('input', filterTable);
    roleFilter.addEventListener('change', filterTable);
    entriesSelect.addEventListener('change', filterTable);

    function filterTable() {
        const query = searchInput.value.toLowerCase();
        const selectedRole = roleFilter.value;
        const entriesToShow = parseInt(entriesSelect.value, 10);
        
        let visibleCount = 0;
        
        rows.forEach((row, index) => {
            const cells = Array.from(row.querySelectorAll('td'));
            const role = row.getAttribute('data-role');
            
            const matchesSearch = cells.some(cell => 
                cell.textContent.toLowerCase().includes(query)
            );
            const matchesRole = !selectedRole || role === selectedRole;
            const withinLimit = visibleCount < entriesToShow;
            
            if (matchesSearch && matchesRole && withinLimit) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });
        
        updatePaginationInfo(visibleCount);
    }

    function updatePaginationInfo(visibleCount) {
        document.getElementById('showingStart').textContent = visibleCount > 0 ? 1 : 0;
        document.getElementById('showingEnd').textContent = visibleCount;
        document.getElementById('totalEntries').textContent = rows.length;
    }

    function updateRoleStatistics() {
        const roleCounts = {
            Admin: 0,
            Staff: 0,
            Manager: 0
        };

        rows.forEach(row => {
            const role = row.getAttribute('data-role');
            if (role === 'Admin') {
                roleCounts.Admin++;
            } else if (role.startsWith('Staff')) {
                roleCounts.Staff++;
            } else {
                roleCounts.Manager++;
            }
        });

        // Animate counters
        if (document.getElementById('adminCount')) {
            animateCounter('adminCount', roleCounts.Admin);
            animateCounter('staffCount', roleCounts.Staff);
            animateCounter('managerCount', roleCounts.Manager);
        }
    }

    function animateCounter(elementId, targetValue) {
        const element = document.getElementById(elementId);
        if (!element) return;
        
        let currentValue = 0;
        const increment = targetValue / 20;
        
        const timer = setInterval(() => {
            currentValue += increment;
            if (currentValue >= targetValue) {
                currentValue = targetValue;
                clearInterval(timer);
            }
            element.textContent = Math.floor(currentValue);
        }, 50);
    }

    function initializeUserData() {
        rows.forEach(row => {
            const userId = row.id.split('-')[1];
            loadPermissionCount(userId);
        });
        updateUserStatus();
    }

    function loadPermissionCount(userId) {
        // Simulate permission loading
        setTimeout(() => {
            const permissionCount = Math.floor(Math.random() * 15) + 5; // Random 5-20
            const element = document.getElementById(`permission-count-${userId}`);
            if (element) {
                element.textContent = `${permissionCount} permissions`;
            }
        }, Math.random() * 2000 + 500);
    }

    function updateUserStatus() {
        rows.forEach(row => {
            const userId = row.id.split('-')[1];
            const statusElement = document.getElementById(`status-${userId}`);
            if (statusElement) {
                const isOnline = Math.random() > 0.3; // 70% chance online
                if (isOnline) {
                    statusElement.className = 'badge user-status-badge bg-success';
                    statusElement.innerHTML = '<i class="fas fa-circle me-1"></i>Online';
                } else {
                    statusElement.className = 'badge user-status-badge bg-secondary';
                    statusElement.innerHTML = '<i class="fas fa-circle me-1"></i>Offline';
                }
            }
        });
    }
});

// Permission Management Functions
function viewUserPermissions(userId, userName, userRole) {
    document.getElementById('modalUserName').textContent = userName;
    document.getElementById('modalUserRole').textContent = userRole;
    
    // Show loading
    document.getElementById('permissionsList').innerHTML = `
        <div class="text-center">
            <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
            <p class="mt-2">Loading permissions...</p>
        </div>
    `;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('userPermissionsModal'));
    modal.show();
    
    // Simulate loading permissions
    setTimeout(() => {
        const permissions = generateMockPermissions(userRole);
        displayPermissions(permissions);
    }, 1500);
}

function viewMyPermissions() {
    const currentUserRole = '{{ Auth::user()->role }}';
    const currentUserName = '{{ Auth::user()->name }}';
    viewUserPermissions('{{ Auth::user()->id }}', currentUserName, currentUserRole);
}

function generateMockPermissions(role) {
    const basePermissions = [
        'dashboard_view', 'profile_edit', 'logout'
    ];
    
    const rolePermissions = {
        'Admin': [
            'user_create', 'user_edit', 'user_delete', 'user_view',
            'role_management', 'system_settings', 'backup_restore',
            'reports_generate', 'audit_logs', 'database_manage'
        ],
        'Staff': [
            'data_view', 'reports_view'
        ],
        'Staff2': [
            'process_manage', 'data_edit', 'reports_create'
        ],
        'Staff3': [
            'inventory_manage', 'stock_view', 'warehouse_access'
        ],
        'Staff4': [
            'employee_manage', 'payroll_access', 'recruitment'
        ],
        'Staff5': [
            'finance_manage', 'accounting_access', 'budget_view'
        ],
        'Staff6': [
            'system_admin', 'technical_support', 'maintenance'
        ],
        'Staff7': [
            'purchase_manage', 'supplier_access', 'procurement'
        ]
    };
    
    return [...basePermissions, ...(rolePermissions[role] || [])];
}

function displayPermissions(permissions) {
    document.getElementById('modalPermissionCount').textContent = permissions.length;
    
    let html = '<div class="row">';
    permissions.forEach((permission, index) => {
        const badgeClass = index % 4 === 0 ? 'bg-primary' : 
                          index % 4 === 1 ? 'bg-success' : 
                          index % 4 === 2 ? 'bg-info' : 'bg-warning';
        
        html += `
            <div class="col-md-6 mb-2">
                <span class="badge ${badgeClass} w-100 text-start p-2">
                    <i class="fas fa-key me-2"></i>${permission.replace('_', ' ').toUpperCase()}
                </span>
            </div>
        `;
    });
    html += '</div>';
    
    document.getElementById('permissionsList').innerHTML = html;
}

// Utility Functions
function refreshAllData() {
    // Show loading
    const btn = event.target;
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Refreshing...';
    btn.disabled = true;
    
    setTimeout(() => {
        location.reload();
    }, 2000);
}

function exportUsers() {
    alert('Export functionality will be implemented soon!');
}
</script>

<style>
.table {
    font-size: 14px;
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
    border-radius: 12px;
}

.table th, .table td {
    padding: 16px 20px;
    border-bottom: 1px solid #ddd;
}

.table th {
    position: sticky;
    top: 0;
    background: linear-gradient(-25deg,#1f403c,#1f403c,#99f2c8);
    color: white;
    z-index: 2;
    text-align: center;
    border-radius: 8px;
}

.badge {
    font-size: 12px;
    padding: 6px 12px;
    border-radius: 20px;
}

.role-badge {
    font-size: 11px;
    padding: 4px 8px;
}

.user-avatar {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background: linear-gradient(45deg, #007bff, #0056b3);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 14px;
}

.user-status-badge {
    font-size: 11px;
    padding: 4px 8px;
}

.permission-count-btn {
    font-size: 11px;
    padding: 2px 8px;
    border-radius: 15px;
}

.bg-purple {
    background-color: #6f42c1 !important;
}

.bg-gradient-primary {
    background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%) !important;
}

.bg-gradient-info {
    background: linear-gradient(87deg, #11cdef 0, #1171ef 100%) !important;
}

.bg-gradient-success {
    background: linear-gradient(87deg, #2dce89 0, #2dcecc 100%) !important;
}

.bg-gradient-warning {
    background: linear-gradient(87deg, #fb6340 0, #fbb140 100%) !important;
}

.role-icon {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}

#searchInput {
    margin-left: auto;
}

#entriesSelect, #roleFilter {
    min-width: 70px;
}

.table-responsive {
    border-radius: 12px;
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.list-group-item {
    font-size: 14px;
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}

.btn-group .btn {
    border-radius: 6px !important;
    margin: 0 1px;
}

.modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.modal-header {
    border-radius: 15px 15px 0 0;
}

.alert {
    border-radius: 10px;
    border: none;
}

.btn {
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* Responsive Design */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 12px;
    }
    
    .user-avatar {
        width: 30px;
        height: 30px;
        font-size: 12px;
    }
    
    .btn-group .btn {
        padding: 4px 8px;
        font-size: 12px;
    }
    
    .badge {
        font-size: 10px;
        padding: 3px 6px;
    }
}

/* Loading Animation */
.loading {
    position: relative;
    overflow: hidden;
}

.loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% {
        left: -100%;
    }
    100% {
        left: 100%;
    }
}

/* Custom Scrollbar */
.table-responsive::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.table-responsive::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.table-responsive::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
@endsection

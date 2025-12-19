@extends('layout.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="m-0 font-weight-bold text-gradient">
                <i class="fas fa-user-edit me-2"></i>Edit User Account
            </h5>
            <p class="text-muted mb-0">Update user information and manage permissions carefully</p>
        </div>
        <div class="btn-group">
            @if(Auth::user()->role === 'Admin')
            <a href="{{ url('/home/role-management') }}" class="btn btn-success rounded-pill shadow-sm px-4 py-2 me-2 animate-hover">
                <i class="fas fa-users-cog me-2"></i>Role Management
            </a>
            @endif
            <a href="/home" class="btn btn-outline-primary rounded-pill shadow-sm px-4 py-2 animate-hover">
                <i class="fas fa-arrow-left me-2"></i>Back to Users
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show animate-slide-down" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Main Edit Form -->
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4 animate-card">
                <div class="card-header py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 text-white font-weight-bold">
                            <i class="fas fa-user-edit me-2"></i>Edit User: {{ $user->name }}
                        </h6>
                        <span class="badge bg-light text-dark">ID: {{ $user->id }}</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form method="post" action="{{ url('/home/'. $user->id) }}" id="editUserForm">
                        @csrf
                        @method('PUT')

                        <!-- User Avatar Section -->
                        <div class="text-center mb-4">
                            <div class="user-avatar-large mx-auto mb-3">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <h5 class="fw-bold text-primary">{{ $user->name }}</h5>
                            <p class="text-muted">{{ $user->email }}</p>
                        </div>

                        <!-- Form Fields -->
                        <div class="row">
                            <!-- Name Field -->
                            <div class="col-md-6 mb-4">
                                <label for="nama" class="form-label fw-bold text-secondary">
                                    <i class="fas fa-user text-primary me-2"></i>Full Name
                                </label>
                                <input type="text" 
                                       placeholder="Enter full name" 
                                       class="form-control rounded-3 shadow-sm input-animate @error('nama') is-invalid @enderror" 
                                       id="nama" 
                                       name="nama" 
                                       value="{{ old('nama', $user->name) }}"
                                       required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div class="col-md-6 mb-4">
                                <label for="email" class="form-label fw-bold text-secondary">
                                    <i class="fas fa-envelope text-danger me-2"></i>Email Address
                                </label>
                                <input type="email" 
                                       placeholder="Enter email address" 
                                       class="form-control rounded-3 shadow-sm input-animate @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}"
                                       @if(Auth::user()->role !== 'Admin' && Auth::user()->id !== $user->id) readonly @endif>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Role Selection -->
                        @if(Auth::user()->role === 'Admin')
                        <div class="mb-4">
                            <label for="role" class="form-label fw-bold text-secondary">
                                <i class="fas fa-user-tag text-success me-2"></i>User Role
                            </label>
                            <select id="role" name="role" class="form-select rounded-3 shadow-sm select-animate @error('role') is-invalid @enderror" onchange="updateRoleInfo()">
                                <option disabled>Select Role</option>
                                <option value="Admin" {{ ($user->role == 'Admin') ? 'selected' : '' }}>
                                    👑 Admin - Full System Access
                                </option>
                                <option value="Staff" {{ ($user->role == 'Staff') ? 'selected' : '' }}>
                                    👀 Staff - View Only Access
                                </option>
                                <option value="Staff2" {{ ($user->role == 'Staff2') ? 'selected' : '' }}>
                                    ⚙️ Staff Process - Process Management
                                </option>
                                <option value="Staff3" {{ ($user->role == 'Staff3') ? 'selected' : '' }}>
                                    📦 Staff Warehouse - Inventory Management
                                </option>
                                <option value="Staff4" {{ ($user->role == 'Staff4') ? 'selected' : '' }}>
                                    👥 Staff HRD - Human Resources
                                </option>
                                <option value="Staff5" {{ ($user->role == 'Staff5') ? 'selected' : '' }}>
                                    💰 Staff Finance - Financial Management
                                </option>
                                <option value="Staff6" {{ ($user->role == 'Staff6') ? 'selected' : '' }}>
                                    💻 Staff IT - Technical Support
                                </option>
                                <option value="Staff7" {{ ($user->role == 'Staff7') ? 'selected' : '' }}>
                                    🛒 Staff Purchase - Procurement
                                </option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <!-- Role Information Card -->
                            <div id="roleInfoCard" class="mt-3" style="display: none;">
                                <div class="card border-info">
                                    <div class="card-body p-3">
                                        <h6 class="card-title text-info mb-2">
                                            <i class="fas fa-info-circle me-2"></i>Role Information
                                        </h6>
                                        <p id="roleDescription" class="card-text text-muted mb-2"></p>
                                        <div id="rolePermissions" class="d-flex flex-wrap gap-1"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="mb-4">
                            <label class="form-label fw-bold text-secondary">
                                <i class="fas fa-user-tag text-success me-2"></i>Current Role
                            </label>
                            <input type="text" class="form-control rounded-3 shadow-sm" value="{{ $user->role }}" readonly>
                            <small class="text-muted">Only administrators can change user roles</small>
                        </div>
                        @endif

                        <!-- Password Section -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-bold text-secondary">
                                    <i class="fas fa-lock text-warning me-2"></i>New Password
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           placeholder="Leave blank to keep current" 
                                           class="form-control rounded-start-3 shadow-sm input-animate @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password">
                                    <button class="btn btn-outline-secondary rounded-end-3" type="button" onclick="togglePassword('password')">
                                        <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label fw-bold text-secondary">
                                    <i class="fas fa-lock text-warning me-2"></i>Confirm Password
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           placeholder="Confirm new password" 
                                           class="form-control rounded-start-3 shadow-sm input-animate" 
                                           id="password_confirmation" 
                                           name="password_confirmation">
                                    <button class="btn btn-outline-secondary rounded-end-3" type="button" onclick="togglePassword('password_confirmation')">
                                        <i class="fas fa-eye" id="togglePasswordConfirmIcon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Last updated: {{ $user->updated_at->format('M d, Y H:i') }}
                                </small>
                            </div>
                            <div class="btn-group">
                                <a href="/home" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-primary rounded-pill shadow-lg px-5 py-2 animate-hover">
                                    <i class="fas fa-save me-2"></i>Update User
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- User Information Sidebar -->
        <div class="col-lg-4">
            <!-- Current User Stats -->
            <div class="card shadow-lg border-0 rounded-4 mb-4 animate-card">
                <div class="card-header py-3 bg-gradient-info text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-chart-line me-2"></i>User Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border-end">
                                <h4 class="text-primary fw-bold">{{ \App\Models\User::count() }}</h4>
                                <small class="text-muted">Total Users</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <h4 class="text-success fw-bold">{{ \App\Models\User::where('role', 'Admin')->count() }}</h4>
                            <small class="text-muted">Administrators</small>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Account Status:</span>
                        <span class="badge bg-success">Active</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span class="text-muted">Last Login:</span>
                        <span class="text-primary">{{ $user->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            @if(Auth::user()->role === 'Admin')
            <div class="card shadow-lg border-0 rounded-4 animate-card">
                <div class="card-header py-3 bg-gradient-success text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ url('/home/role-management') }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-users-cog me-2"></i>Manage All Roles
                        </a>
                        <a href="{{ url('/home/create') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-user-plus me-2"></i>Add New User
                        </a>
                        <button class="btn btn-outline-warning btn-sm" onclick="resetUserPassword()">
                            <i class="fas fa-key me-2"></i>Reset Password
                        </button>
                        @if(Auth::user()->id !== $user->id)
                        <button class="btn btn-outline-danger btn-sm" onclick="confirmDeleteUser()">
                            <i class="fas fa-user-times me-2"></i>Delete User
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Delete
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete user <strong>{{ $user->name }}</strong>?</p>
                <p class="text-danger"><small>This action cannot be undone!</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="/home/{{ $user->id }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Role Information Data
const roleInfo = {
    'Admin': {
        description: 'Full system access with all administrative privileges',
        permissions: ['user_management', 'system_settings', 'reports', 'database_access', 'security_config']
    },
    'Staff': {
        description: 'Basic access with view-only permissions',
        permissions: ['view_dashboard', 'view_reports']
    },
    'Staff2': {
        description: 'Process management and workflow control',
        permissions: ['process_management', 'workflow_control', 'task_assignment']
    },
    'Staff3': {
        description: 'Warehouse and inventory management',
        permissions: ['inventory_management', 'stock_control', 'warehouse_access']
    },
    'Staff4': {
        description: 'Human resources and employee management',
        permissions: ['employee_management', 'payroll_access', 'recruitment']
    },
    'Staff5': {
        description: 'Financial management and accounting',
        permissions: ['financial_reports', 'accounting_access', 'budget_management']
    },
    'Staff6': {
        description: 'IT support and technical management',
        permissions: ['system_maintenance', 'technical_support', 'server_access']
    },
    'Staff7': {
        description: 'Procurement and purchase management',
        permissions: ['purchase_orders', 'supplier_management', 'procurement']
    }
};

// Update role information when role is changed
function updateRoleInfo() {
    const roleSelect = document.getElementById('role');
    const roleInfoCard = document.getElementById('roleInfoCard');
    const roleDescription = document.getElementById('roleDescription');
    const rolePermissions = document.getElementById('rolePermissions');
    
    const selectedRole = roleSelect.value;
    
    if (selectedRole && roleInfo[selectedRole]) {
        const info = roleInfo[selectedRole];
        
        roleDescription.textContent = info.description;
        
        // Clear previous permissions
        rolePermissions.innerHTML = '';
        
        // Add permission badges
        info.permissions.forEach(permission => {
            const badge = document.createElement('span');
            badge.className = 'badge bg-primary me-1 mb-1';
            badge.innerHTML = `<i class="fas fa-key me-1"></i>${permission.replace('_', ' ')}`;
            rolePermissions.appendChild(badge);
        });
        
        roleInfoCard.style.display = 'block';
    } else {
        roleInfoCard.style.display = 'none';
    }
}

// Toggle password visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById('toggle' + fieldId.charAt(0).toUpperCase() + fieldId.slice(1) + 'Icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        field.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

// Reset user password
function resetUserPassword() {
    if (confirm('Generate a new random password for this user?')) {
        const newPassword = Math.random().toString(36).slice(-8);
        document.getElementById('password').value = newPassword;
        document.getElementById('password_confirmation').value = newPassword;
        alert('New password generated: ' + newPassword + '\nPlease save this information!');
    }
}

// Confirm delete user
function confirmDeleteUser() {
    const modal = new bootstrap.Modal(document.getElementById('deleteUserModal'));
    modal.show();
}

// Initialize role info on page load
document.addEventListener('DOMContentLoaded', function() {
    updateRoleInfo();
    
    // Form validation
    const form = document.getElementById('editUserForm');
    form.addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const passwordConfirm = document.getElementById('password_confirmation').value;
        
        if (password && password !== passwordConfirm) {
            e.preventDefault();
            alert('Password confirmation does not match!');
            return false;
        }
    });
});
</script>

<style>
.text-gradient {
    background: linear-gradient(90deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.user-avatar-large {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(45deg, #667eea, #764ba2);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 24px;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.animate-hover:hover {
    transform: translateY(-3px);
    transition: all 0.3s ease;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.animate-card {
    transition: all 0.3s ease;
}

.animate-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.input-animate {
    transition: all 0.3s ease;
}

.input-animate:focus {
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
}

.select-animate {
    transition: all 0.3s ease;
}

.select-animate:hover {
    transform: translateY(-1px);
}

.animate-slide-down {
    animation: slideDown 0.5s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.bg-gradient-info {
    background: linear-gradient(87deg, #11cdef 0, #1171ef 100%) !important;
}

.bg-gradient-success {
    background: linear-gradient(87deg, #2dce89 0, #2dcecc 100%) !important;
}

.card {
    border: none;
    transition: all 0.3s ease;
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

@media (max-width: 768px) {
    .user-avatar-large {
        width: 60px;
        height: 60px;
        font-size: 18px;
    }
}
</style>
@endsection

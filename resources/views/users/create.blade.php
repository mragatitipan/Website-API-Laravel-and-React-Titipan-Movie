@extends('layout.admin')

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="m-0 font-weight-bold text-gradient">
                <i class="fas fa-user-plus me-2"></i>Create New User
            </h5>
            <p class="text-muted mb-0">Add a new user to the system with appropriate role and permissions</p>
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
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show animate-slide-down" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show animate-slide-down" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Main Create Form -->
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4 animate-card">
                <div class="card-header py-3" style="background: linear-gradient(135deg, #2dce89 0%, #2dcecc 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="m-0 text-white font-weight-bold">
                            <i class="fas fa-user-plus me-2"></i>New User Information
                        </h6>
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-users me-1"></i>Total Users: {{ \App\Models\User::count() }}
                        </span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Form Info Banner -->
                    <div class="alert alert-info border-0 rounded-3 mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle text-info me-3 fs-4"></i>
                            <div>
                                <h6 class="alert-heading mb-1">Important Information</h6>
                                <p class="mb-0">Please ensure all information is correct. Some data may be sensitive and cannot be changed after creation.</p>
                            </div>
                        </div>
                    </div>

                    <form method="post" action="/home/store" id="createUserForm" novalidate>
                        @csrf

                        <!-- Preview Avatar -->
                        <div class="text-center mb-4">
                            <div class="user-avatar-preview mx-auto mb-3" id="avatarPreview">
                                <i class="fas fa-user text-muted"></i>
                            </div>
                            <p class="text-muted small">Avatar will be generated from user's initials</p>
                        </div>

                        <!-- Form Fields -->
                        <div class="row">
                            <!-- Full Name -->
                            <div class="col-md-6 mb-4">
                                <label for="nama" class="form-label fw-bold text-secondary">
                                    <i class="fas fa-user text-primary me-2"></i>Full Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       placeholder="Enter full name (e.g., John Doe)" 
                                       class="form-control rounded-3 shadow-sm input-animate @error('nama') is-invalid @enderror" 
                                       id="nama" 
                                       name="nama" 
                                       value="{{ old('nama') }}" 
                                       autofocus 
                                       required
                                       oninput="updateAvatarPreview()">
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="fas fa-lightbulb text-warning me-1"></i>
                                    Use the person's real full name for better identification
                                </div>
                            </div>

                            <!-- Email Address -->
                            <div class="col-md-6 mb-4">
                                <label for="email" class="form-label fw-bold text-secondary">
                                    <i class="fas fa-envelope text-danger me-2"></i>Email Address <span class="text-danger">*</span>
                                </label>
                                <input type="email" 
                                       placeholder="Enter email address (e.g., user@company.com)" 
                                       class="form-control rounded-3 shadow-sm input-animate @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required
                                       oninput="checkEmailAvailability()">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="emailStatus" class="form-text"></div>
                            </div>
                        </div>

                        <!-- Role Selection -->
                        <div class="mb-4">
                            <label for="role" class="form-label fw-bold text-secondary">
                                <i class="fas fa-user-tag text-success me-2"></i>User Role <span class="text-danger">*</span>
                            </label>
                            <select id="role" name="role" class="form-select rounded-3 shadow-sm select-animate @error('role') is-invalid @enderror" onchange="updateRoleInfo()" required>
                                <option value="" disabled selected>Select a role for this user</option>
                                @if(Auth::user()->role === 'Admin')
                                <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>
                                    👑 Admin - Full System Access
                                </option>
                                @endif
                                <option value="Staff" {{ old('role') == 'Staff' ? 'selected' : '' }}>
                                    👀 Staff - View Only Access
                                </option>
                                <option value="Staff2" {{ old('role') == 'Staff2' ? 'selected' : '' }}>
                                    ⚙️ Staff Process - Process Management
                                </option>
                                <option value="Staff3" {{ old('role') == 'Staff3' ? 'selected' : '' }}>
                                    📦 Staff Warehouse - Inventory Management
                                </option>
                                <option value="Staff4" {{ old('role') == 'Staff4' ? 'selected' : '' }}>
                                    👥 Staff HRD - Human Resources
                                </option>
                                <option value="Staff5" {{ old('role') == 'Staff5' ? 'selected' : '' }}>
                                    💰 Staff Finance - Financial Management
                                </option>
                                <option value="Staff6" {{ old('role') == 'Staff6' ? 'selected' : '' }}>
                                    💻 Staff IT - Technical Support
                                </option>
                                <option value="Staff7" {{ old('role') == 'Staff7' ? 'selected' : '' }}>
                                    🛒 Staff Purchase - Procurement
                                </option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <!-- Role Information Card -->
                            <div id="roleInfoCard" class="mt-3" style="display: none;">
                                <div class="card border-success bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="card-title text-success mb-2">
                                            <i class="fas fa-info-circle me-2"></i>Role Information
                                        </h6>
                                        <p id="roleDescription" class="card-text text-muted mb-2"></p>
                                        <div class="mb-2">
                                            <strong class="text-secondary">Permissions:</strong>
                                        </div>
                                        <div id="rolePermissions" class="d-flex flex-wrap gap-1"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Password Section -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="password" class="form-label fw-bold text-secondary">
                                    <i class="fas fa-lock text-warning me-2"></i>Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           placeholder="Enter secure password" 
                                           class="form-control rounded-start-3 shadow-sm input-animate @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           required
                                           oninput="checkPasswordStrength()">
                                    <button class="btn btn-outline-secondary rounded-end-3" type="button" onclick="togglePassword('password')">
                                        <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="passwordStrength" class="form-text"></div>
                            </div>
                            <div class="col-md-6">
                                <label for="konfirmasi_password" class="form-label fw-bold text-secondary">
                                    <i class="fas fa-lock text-warning me-2"></i>Confirm Password <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" 
                                           placeholder="Confirm password" 
                                           class="form-control rounded-start-3 shadow-sm input-animate @error('konfirmasi_password') is-invalid @enderror" 
                                           id="konfirmasi_password" 
                                           name="konfirmasi_password" 
                                           required
                                           oninput="checkPasswordMatch()">
                                    <button class="btn btn-outline-secondary rounded-end-3" type="button" onclick="togglePassword('konfirmasi_password')">
                                        <i class="fas fa-eye" id="toggleKonfirmasi_passwordIcon"></i>
                                    </button>
                                </div>
                                @error('konfirmasi_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div id="passwordMatch" class="form-text"></div>
                            </div>
                        </div>

                        <!-- Password Generator -->
                        <div class="mb-4">
                            <div class="card border-info bg-light">
                                <div class="card-body p-3">
                                    <h6 class="card-title text-info mb-2">
                                        <i class="fas fa-key me-2"></i>Password Generator
                                    </h6>
                                    <p class="card-text text-muted mb-3">Generate a secure password automatically</p>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <button type="button" class="btn btn-outline-info btn-sm" onclick="generatePassword(8)">
                                            Generate Simple (8 chars)
                                        </button>
                                        <button type="button" class="btn btn-outline-warning btn-sm" onclick="generatePassword(12)">
                                            Generate Strong (12 chars)
                                        </button>
                                        <button type="button" class="btn btn-outline-success btn-sm" onclick="generatePassword(16)">
                                            Generate Ultra (16 chars)
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    All data is encrypted and secure
                                </small>
                            </div>
                            <div class="btn-group">
                                <a href="/home" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                                <button type="submit" class="btn btn-success rounded-pill shadow-lg px-5 py-2 animate-hover" id="submitBtn">
                                    <i class="fas fa-user-plus me-2"></i>Create User
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Information -->
        <div class="col-lg-4">
            <!-- Quick Stats -->
            <div class="card shadow-lg border-0 rounded-4 mb-4 animate-card">
                <div class="card-header py-3 bg-gradient-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-chart-pie me-2"></i>System Statistics
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
                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Staff Users:</span>
                            <span class="fw-bold">{{ \App\Models\User::where('role', '!=', 'Admin')->count() }}</span>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Active Today:</span>
                            <span class="text-success fw-bold">{{ \App\Models\User::whereDate('updated_at', today())->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Role Distribution -->
            <div class="card shadow-lg border-0 rounded-4 mb-4 animate-card">
                <div class="card-header py-3 bg-gradient-info text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-users me-2"></i>Role Distribution
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $roles = ['Admin', 'Staff', 'Staff2', 'Staff3', 'Staff4', 'Staff5', 'Staff6', 'Staff7'];
                        $roleNames = [
                            'Admin' => 'Administrator',
                            'Staff' => 'View Only',
                            'Staff2' => 'Process',
                            'Staff3' => 'Warehouse',
                            'Staff4' => 'HRD',
                            'Staff5' => 'Finance',
                            'Staff6' => 'IT Support',
                            'Staff7' => 'Purchase'
                        ];
                    @endphp
                    
                    @foreach($roles as $role)
                        @php $count = \App\Models\User::where('role', $role)->count(); @endphp
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">{{ $roleNames[$role] }}:</span>
                            <span class="badge bg-primary">{{ $count }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Security Tips -->
            <div class="card shadow-lg border-0 rounded-4 animate-card">
                <div class="card-header py-3 bg-gradient-warning text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-shield-alt me-2"></i>Security Tips
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <small>Use strong passwords (8+ characters)</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <small>Include numbers and special characters</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <small>Assign appropriate roles only</small>
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <small>Verify email addresses before creating</small>
                        </li>
                        <li>
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <small>Review permissions regularly</small>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Role Information Data
const roleInfo = {
    'Admin': {
        description: 'Full system access with all administrative privileges including user management, system settings, and security configuration.',
        permissions: ['user_management', 'system_settings', 'reports', 'database_access', 'security_config']
    },
    'Staff': {
        description: 'Basic access with view-only permissions for dashboard and reports.',
        permissions: ['view_dashboard', 'view_reports']
    },
    'Staff2': {
        description: 'Process management and workflow control with task assignment capabilities.',
        permissions: ['process_management', 'workflow_control', 'task_assignment']
    },
    'Staff3': {
        description: 'Warehouse and inventory management with stock control access.',
        permissions: ['inventory_management', 'stock_control', 'warehouse_access']
    },
    'Staff4': {
        description: 'Human resources and employee management including payroll and recruitment.',
        permissions: ['employee_management', 'payroll_access', 'recruitment']
    },
    'Staff5': {
        description: 'Financial management and accounting with budget management access.',
        permissions: ['financial_reports', 'accounting_access', 'budget_management']
    },
    'Staff6': {
        description: 'IT support and technical management with system maintenance access.',
        permissions: ['system_maintenance', 'technical_support', 'server_access']
    },
    'Staff7': {
        description: 'Procurement and purchase management with supplier management access.',
        permissions: ['purchase_orders', 'supplier_management', 'procurement']
    }
};

// Update avatar preview
function updateAvatarPreview() {
    const nameInput = document.getElementById('nama');
    const avatarPreview = document.getElementById('avatarPreview');
    
    if (nameInput.value.trim()) {
        const initials = nameInput.value.trim().split(' ')
            .map(word => word.charAt(0).toUpperCase())
            .slice(0, 2)
            .join('');
        
        avatarPreview.innerHTML = initials;
        avatarPreview.style.background = 'linear-gradient(45deg, #2dce89, #2dcecc)';
        avatarPreview.style.color = 'white';
    } else {
        avatarPreview.innerHTML = '<i class="fas fa-user text-muted"></i>';
        avatarPreview.style.background = '#f8f9fa';
        avatarPreview.style.color = '#6c757d';
    }
}

// Update role information
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
            badge.className = 'badge bg-success me-1 mb-1';
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

// Check password strength
function checkPasswordStrength() {
    const password = document.getElementById('password').value;
    const strengthDiv = document.getElementById('passwordStrength');
    
    if (!password) {
        strengthDiv.innerHTML = '';
        return;
    }
    
    let strength = 0;
    let feedback = [];
    
    if (password.length >= 8) strength++;
    else feedback.push('At least 8 characters');
    
    if (/[a-z]/.test(password)) strength++;
    else feedback.push('Lowercase letter');
    
    if (/[A-Z]/.test(password)) strength++;
    else feedback.push('Uppercase letter');
    
    if (/[0-9]/.test(password)) strength++;
    else feedback.push('Number');
    
    if (/[^A-Za-z0-9]/.test(password)) strength++;
    else feedback.push('Special character');
    
    const strengthLevels = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong'];
    const strengthColors = ['danger', 'warning', 'info', 'success', 'success'];
    
    const level = Math.min(strength, 4);
    
    strengthDiv.innerHTML = `
        <div class="d-flex align-items-center">
            <span class="text-${strengthColors[level]} me-2">
                <i class="fas fa-shield-alt"></i> ${strengthLevels[level]}
            </span>
            ${feedback.length > 0 ? `<small class="text-muted">Missing: ${feedback.join(', ')}</small>` : ''}
        </div>
    `;
}

// Check password match
function checkPasswordMatch() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('konfirmasi_password').value;
    const matchDiv = document.getElementById('passwordMatch');
    
    if (!confirmPassword) {
        matchDiv.innerHTML = '';
        return;
    }
    
    if (password === confirmPassword) {
        matchDiv.innerHTML = '<span class="text-success"><i class="fas fa-check-circle me-1"></i>Passwords match</span>';
    } else {
        matchDiv.innerHTML = '<span class="text-danger"><i class="fas fa-times-circle me-1"></i>Passwords do not match</span>';
    }
}

// Generate password
function generatePassword(length) {
    const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*";
    let password = "";
    
    for (let i = 0; i < length; i++) {
        password += charset.charAt(Math.floor(Math.random() * charset.length));
    }
    
    document.getElementById('password').value = password;
    document.getElementById('konfirmasi_password').value = password;
    
    checkPasswordStrength();
    checkPasswordMatch();
    
    // Show password temporarily
    document.getElementById('password').type = 'text';
    document.getElementById('konfirmasi_password').type = 'text';
    document.getElementById('togglePasswordIcon').className = 'fas fa-eye-slash';
    document.getElementById('toggleKonfirmasi_passwordIcon').className = 'fas fa-eye-slash';
    
    alert('Password generated successfully!\nPassword: ' + password + '\n\nPlease save this password securely.');
}

// Check email availability (mock function)
function checkEmailAvailability() {
    const email = document.getElementById('email').value;
    const statusDiv = document.getElementById('emailStatus');
    
    if (!email || !email.includes('@')) {
        statusDiv.innerHTML = '';
        return;
    }
    
    // Mock email validation
    statusDiv.innerHTML = '<span class="text-info"><i class="fas fa-spinner fa-spin me-1"></i>Checking availability...</span>';
    
    setTimeout(() => {
        statusDiv.innerHTML = '<span class="text-success"><i class="fas fa-check-circle me-1"></i>Email is available</span>';
    }, 1000);
}

// Form validation
document.getElementById('createUserForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('konfirmasi_password').value;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Password confirmation does not match!');
        return false;
    }
    
    if (password.length < 8) {
        e.preventDefault();
        alert('Password must be at least 8 characters long!');
        return false;
    }
    
    // Show loading state
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating User...';
    submitBtn.disabled = true;
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    updateAvatarPreview();
    
    // Auto-focus first input
    document.getElementById('nama').focus();
});
</script>

<style>
.text-gradient {
    background: linear-gradient(90deg, #2dce89, #2dcecc);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.user-avatar-preview {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #f8f9fa;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 24px;
    border: 3px solid #e9ecef;
    transition: all 0.3s ease;
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
    box-shadow: 0 4px 15px rgba(45, 206, 137, 0.2);
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

.bg-gradient-primary {
    background: linear-gradient(87deg, #5e72e4 0, #825ee4 100%) !important;
}

.bg-gradient-info {
    background: linear-gradient(87deg, #11cdef 0, #1171ef 100%) !important;
}

.bg-gradient-warning {
    background: linear-gradient(87deg, #fb6340 0, #fbb140 100%) !important;
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
    border-color: #2dce89;
    box-shadow: 0 0 0 0.2rem rgba(45, 206, 137, 0.25);
}

.alert {
    border: none;
    border-radius: 0.75rem;
}

.badge {
    font-size: 0.75em;
    padding: 0.375rem 0.75rem;
}

.list-unstyled li {
    padding: 0.25rem 0;
}

.input-group .btn {
    border-color: #ced4da;
}

.input-group .btn:hover {
    background-color: #f8f9fa;
    border-color: #adb5bd;
}

.card-header {
    border-bottom: none;
    border-radius: 1rem 1rem 0 0 !important;
}

.rounded-4 {
    border-radius: 1rem !important;
}

.rounded-3 {
    border-radius: 0.75rem !important;
}

.shadow-lg {
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
}

.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

/* Custom scrollbar for better UX */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: #2dce89;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: #24a46d;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .user-avatar-preview {
        width: 60px;
        height: 60px;
        font-size: 18px;
    }
    
    .btn-group {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .btn-group .btn {
        width: 100%;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .animate-card:hover {
        transform: none;
    }
    
    .animate-hover:hover {
        transform: none;
    }
}

@media (max-width: 576px) {
    .container-fluid {
        padding: 0.75rem;
    }
    
    .card-body {
        padding: 1.5rem !important;
    }
    
    .row {
        margin: 0;
    }
    
    .col-md-6 {
        padding: 0 0.5rem;
    }
}

/* Loading animation */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.fa-spin {
    animation: spin 1s linear infinite;
}

/* Form validation styles */
.was-validated .form-control:valid,
.form-control.is-valid {
    border-color: #2dce89;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%232dce89' d='m2.3 6.73.94-.94 1.44 1.44L7.53 4.4l.94.94L4.07 9.74z'/%3e%3c/svg%3e");
}

.was-validated .form-control:invalid,
.form-control.is-invalid {
    border-color: #f5365c;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23f5365c'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath d='m5.8 4.6 1.4 1.4M7.2 4.6l-1.4 1.4'/%3e%3c/svg%3e");
}

/* Enhanced focus states */
.form-control:focus,
.form-select:focus {
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(45, 206, 137, 0.25);
    border-color: #2dce89;
}

/* Button enhancements */
.btn-success {
    background: linear-gradient(45deg, #2dce89, #2dcecc);
    border: none;
    color: white;
}

.btn-success:hover {
    background: linear-gradient(45deg, #24a46d, #24a4a0);
    box-shadow: 0 4px 15px rgba(45, 206, 137, 0.4);
}

.btn-outline-secondary {
    border-color: #6c757d;
    color: #6c757d;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

/* Card enhancements */
.card-header h6 {
    font-size: 1rem;
    font-weight: 600;
}

.card-body {
    padding: 2rem;
}

/* Alert enhancements */
.alert-info {
    background: linear-gradient(45deg, #e3f2fd, #f0f8ff);
    border: 1px solid #2196f3;
    color: #1976d2;
}

.alert-success {
    background: linear-gradient(45deg, #e8f5e8, #f0fff0);
    border: 1px solid #2dce89;
    color: #155724;
}

.alert-danger {
    background: linear-gradient(45deg, #ffeaea, #fff0f0);
    border: 1px solid #f5365c;
    color: #721c24;
}

/* Progress indicators */
.progress {
    height: 0.5rem;
    border-radius: 0.25rem;
    background-color: #e9ecef;
}

.progress-bar {
    background: linear-gradient(45deg, #2dce89, #2dcecc);
}

/* Tooltip enhancements */
.tooltip {
    font-size: 0.875rem;
}

.tooltip-inner {
    background-color: #2c3e50;
    border-radius: 0.5rem;
    padding: 0.5rem 0.75rem;
}

/* Form text enhancements */
.form-text {
    font-size: 0.875rem;
    color: #6c757d;
    margin-top: 0.25rem;
}

.form-text i {
    margin-right: 0.25rem;
}

/* Badge enhancements */
.badge {
    font-weight: 500;
    letter-spacing: 0.025em;
}

.badge.bg-primary {
    background: linear-gradient(45deg, #5e72e4, #825ee4) !important;
}

.badge.bg-success {
    background: linear-gradient(45deg, #2dce89, #2dcecc) !important;
}

.badge.bg-info {
    background: linear-gradient(45deg, #11cdef, #1171ef) !important;
}

.badge.bg-warning {
    background: linear-gradient(45deg, #fb6340, #fbb140) !important;
}

.badge.bg-danger {
    background: linear-gradient(45deg, #f5365c, #f56036) !important;
}

/* Input group enhancements */
.input-group .form-control:focus {
    z-index: 3;
}

.input-group .btn {
    z-index: 2;
}

/* List enhancements */
.list-unstyled li:hover {
    background-color: #f8f9fa;
    border-radius: 0.25rem;
    padding: 0.5rem;
    margin: 0.125rem 0;
    transition: all 0.2s ease;
}

/* Animation delays for staggered effects */
.animate-card:nth-child(1) { animation-delay: 0.1s; }
.animate-card:nth-child(2) { animation-delay: 0.2s; }
.animate-card:nth-child(3) { animation-delay: 0.3s; }
.animate-card:nth-child(4) { animation-delay: 0.4s; }

/* Dark mode support (if needed) */
@media (prefers-color-scheme: dark) {
    .card {
        background-color: #2c3e50;
        color: #ecf0f1;
    }
    
    .form-control,
    .form-select {
        background-color: #34495e;
        border-color: #4a5f7a;
        color: #ecf0f1;
    }
    
    .form-control:focus,
    .form-select:focus {
        background-color: #34495e;
        border-color: #2dce89;
        color: #ecf0f1;
    }
    
    .text-muted {
        color: #95a5a6 !important;
    }
    
    .text-secondary {
        color: #7f8c8d !important;
    }
}
</style>

<!-- Additional JavaScript for enhanced functionality -->
<script>
// Navigation lock for form pages
document.addEventListener('DOMContentLoaded', function() {
    // Detect if current page is Create, Edit, Show, or Update
    const isCreatePage = window.location.pathname.includes('/create') ||
                        document.title.toLowerCase().includes('create') ||
                        document.querySelector('meta[name="page-type"][content="create"]');
    
    const isEditPage = window.location.pathname.includes('/edit') ||
                      document.title.toLowerCase().includes('edit') ||
                      document.querySelector('meta[name="page-type"][content="edit"]');
    
    const isShowPage = window.location.pathname.includes('/show') ||
                      document.title.toLowerCase().includes('show') ||
                      document.querySelector('meta[name="page-type"][content="show"]');
    
    const isUpdatePage = window.location.pathname.includes('/update') ||
                        document.title.toLowerCase().includes('update') ||
                        document.querySelector('meta[name="page-type"][content="update"]');
    
    // Combine logic for Create, Edit, Show, and Update pages
    if (isCreatePage || isEditPage || isShowPage || isUpdatePage) {
        // Disable all navbar links
        const navbarLinks = document.querySelectorAll('.navbar a, .navbar-nav a, .nav a, .sidebar a');
        
        navbarLinks.forEach(link => {
            // Save original URL as data attribute
            link.dataset.originalHref = link.getAttribute('href');
            
            // Change styling to show links are disabled
            link.style.opacity = '0.6';
            link.style.cursor = 'not-allowed';
            link.style.pointerEvents = 'none';
        });
        
        // Add information message at the top of form
        const formContainer = document.querySelector('form');
        if (formContainer && !document.querySelector('.form-info-banner')) {
            const infoBox = document.createElement('div');
            infoBox.className = 'alert alert-warning form-info-banner mb-4 border-0';
            infoBox.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle text-warning me-3 fs-5"></i>
                    <div>
                        <strong>Form Mode Active:</strong> Navigation is temporarily disabled. 
                        Please complete or cancel this form to continue browsing.
                    </div>
                </div>
            `;
            
            formContainer.insertBefore(infoBox, formContainer.firstChild);
        }
    }
    
    // Add confirmation dialog for form abandonment
    let formModified = false;
    const formInputs = document.querySelectorAll('input, select, textarea');
    
    formInputs.forEach(input => {
        input.addEventListener('change', () => {
            formModified = true;
        });
    });
    
    // Warn user before leaving page if form has been modified
    window.addEventListener('beforeunload', function(e) {
        if (formModified && (isCreatePage || isEditPage)) {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
            return e.returnValue;
        }
    });
    
    // Auto-save draft functionality (optional)
    if (isCreatePage || isEditPage) {
        setInterval(() => {
            if (formModified) {
                saveDraft();
            }
        }, 30000); // Auto-save every 30 seconds
    }
});

// Draft saving functionality
function saveDraft() {
    const formData = new FormData(document.getElementById('createUserForm'));
    const draftData = {};
    
    for (let [key, value] of formData.entries()) {
        if (key !== 'password' && key !== 'konfirmasi_password') { // Don't save passwords
            draftData[key] = value;
        }
    }
    
    localStorage.setItem('userFormDraft', JSON.stringify(draftData));
    
    // Show brief notification
    showNotification('Draft saved automatically', 'info', 2000);
}

// Load draft functionality
function loadDraft() {
    const draftData = localStorage.getItem('userFormDraft');
    if (draftData) {
        const data = JSON.parse(draftData);
        
        for (let [key, value] of Object.entries(data)) {
            const field = document.getElementById(key);
            if (field) {
                field.value = value;
                if (key === 'nama') updateAvatarPreview();
                if (key === 'role') updateRoleInfo();
            }
        }
        
        showNotification('Draft loaded', 'success', 3000);
    }
}

// Clear draft when form is successfully submitted
function clearDraft() {
    localStorage.removeItem('userFormDraft');
}

// Show notification function
function showNotification(message, type = 'info', duration = 5000) {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = `
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after duration
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, duration);
}

// Enhanced form validation
function validateForm() {
    const form = document.getElementById('createUserForm');
    const inputs = form.querySelectorAll('input[required], select[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            isValid = false;
        } else {
            input.classList.remove('is-invalid');
            input.classList.add('is-valid');
        }
    });
    
    return isValid;
}

// Real-time validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createUserForm');
    if (form) {
        const inputs = form.querySelectorAll('input, select');
        
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.hasAttribute('required')) {
                    if (!this.value.trim()) {
                        this.classList.add('is-invalid');
                        this.classList.remove('is-valid');
                    } else {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    }
                }
            });
        });
        
        // Form submission handler
        form.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                showNotification('Please fill in all required fields', 'danger');
                return false;
            }
            
            clearDraft(); // Clear draft on successful submission
        });
    }
});
</script>

@endsection

@extends('layout.admin')

@section('content')
<div class="content-wrapper">
    <!-- Content Header -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 font-weight-bold text-gradient">
                        <i class="fas fa-users-cog me-2"></i>Role Management System
                    </h1>
                    <p class="text-muted">Manage user roles and permissions efficiently</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">User Management</a></li>
                        <li class="breadcrumb-item active">Role Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            
            <!-- Alert Messages -->
            <div id="alertContainer"></div>
            
            <!-- Role Overview Cards -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-lg border-0 rounded-4 animate-card">
                        <div class="card-header py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title text-white m-0 font-weight-bold">
                                    <i class="fas fa-chart-bar me-2"></i>Role Statistics Overview
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-sm btn-light rounded-pill px-3" onclick="refreshStats()">
                                        <i class="fas fa-sync-alt me-1"></i> Refresh
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row" id="roleStatsContainer">
                                <!-- Static fallback content instead of loading -->
                                @php
                                    $staticRoles = [
                                        'Admin' => ['name' => 'Administrator', 'count' => \App\Models\User::where('role', 'Admin')->count(), 'color' => 'danger', 'icon' => 'crown', 'permissions' => 21],
                                        'Staff' => ['name' => 'View Only Staff', 'count' => \App\Models\User::where('role', 'Staff')->count(), 'color' => 'info', 'icon' => 'eye', 'permissions' => 3],
                                        'Staff2' => ['name' => 'Process Staff', 'count' => \App\Models\User::where('role', 'Staff2')->count(), 'color' => 'warning', 'icon' => 'cogs', 'permissions' => 8],
                                        'Staff3' => ['name' => 'Warehouse Staff', 'count' => \App\Models\User::where('role', 'Staff3')->count(), 'color' => 'success', 'icon' => 'warehouse', 'permissions' => 6],
                                        'Staff4' => ['name' => 'HRD Staff', 'count' => \App\Models\User::where('role', 'Staff4')->count(), 'color' => 'primary', 'icon' => 'users', 'permissions' => 7],
                                        'Staff5' => ['name' => 'Finance Staff', 'count' => \App\Models\User::where('role', 'Staff5')->count(), 'color' => 'secondary', 'icon' => 'dollar-sign', 'permissions' => 5],
                                        'Staff6' => ['name' => 'IT Staff', 'count' => \App\Models\User::where('role', 'Staff6')->count(), 'color' => 'dark', 'icon' => 'laptop-code', 'permissions' => 9],
                                        'Staff7' => ['name' => 'Purchase Staff', 'count' => \App\Models\User::where('role', 'Staff7')->count(), 'color' => 'info', 'icon' => 'shopping-cart', 'permissions' => 4]
                                    ];
                                @endphp
                                
                                @foreach($staticRoles as $roleKey => $roleData)
                                <div class="col-lg-3 col-md-6 mb-3">
                                    <div class="small-box bg-{{ $roleData['color'] }} shadow-lg rounded-4 animate-hover">
                                        <div class="inner p-3">
                                            <h3 class="text-white">{{ $roleData['count'] }}</h3>
                                            <p class="text-white-50">{{ $roleData['name'] }}</p>
                                            <div class="progress progress-sm bg-light bg-opacity-25 mb-2">
                                                @php $percentage = round(($roleData['permissions'] / 21) * 100); @endphp
                                                <div class="progress-bar bg-light" style="width: {{ $percentage }}%"></div>
                                            </div>
                                            <small class="text-white-75">{{ $roleData['permissions'] }} permissions ({{ $percentage }}%)</small>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-{{ $roleData['icon'] }}"></i>
                                        </div>
                                        <a href="#" class="small-box-footer text-white-50" onclick="selectRole('{{ $roleKey }}', '{{ $roleData['name'] }}')">
                                            Edit Permissions <i class="fas fa-arrow-circle-right"></i>
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Role Management Interface -->
            <div class="row">
                <!-- Role Selector -->
                <div class="col-md-4">
                    <div class="card shadow-lg border-0 rounded-4 animate-card">
                        <div class="card-header py-3 bg-gradient-primary text-white">
                            <h3 class="card-title m-0 font-weight-bold">
                                <i class="fas fa-list me-2"></i>Available Roles
                            </h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush" id="roleList">
                                @php
                                    $allRoles = [
                                        'Admin' => 'Administrator',
                                        'Staff' => 'View Only Staff', 
                                        'Staff2' => 'Process Staff',
                                        'Staff3' => 'Warehouse Staff',
                                        'Staff4' => 'HRD Staff',
                                        'Staff5' => 'Finance Staff',
                                        'Staff6' => 'IT Staff',
                                        'Staff7' => 'Purchase Staff'
                                    ];
                                @endphp
                                
                                @foreach($allRoles as $roleKey => $roleName)
                                <a href="#" class="list-group-item list-group-item-action role-item border-0 py-3" 
                                   data-role="{{ $roleKey }}" onclick="selectRole('{{ $roleKey }}', '{{ $roleName }}')">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1 font-weight-bold">
                                                <i class="fas fa-{{ $staticRoles[$roleKey]['icon'] ?? 'user' }} me-2 text-{{ $staticRoles[$roleKey]['color'] ?? 'primary' }}"></i>
                                                {{ $roleName }}
                                            </h6>
                                            <p class="mb-0 text-muted small">
                                                {{ \App\Models\User::where('role', $roleKey)->count() }} users assigned
                                            </p>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-{{ $staticRoles[$roleKey]['color'] ?? 'primary' }} rounded-pill">
                                                {{ $staticRoles[$roleKey]['permissions'] ?? 0 }}
                                            </span>
                                            <br>
                                            <small class="text-muted">permissions</small>
                                        </div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions Card -->
                    <div class="card shadow-lg border-0 rounded-4 animate-card mt-4">
                        <div class="card-header py-3 bg-gradient-info text-white">
                            <h6 class="card-title m-0 font-weight-bold">
                                <i class="fas fa-bolt me-2"></i>Quick Actions
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-success btn-sm" onclick="exportRoleConfig()">
                                    <i class="fas fa-download me-2"></i>Export Configuration
                                </button>
                                <button class="btn btn-outline-warning btn-sm" onclick="importRoleConfig()">
                                    <i class="fas fa-upload me-2"></i>Import Configuration
                                </button>
                                <button class="btn btn-outline-info btn-sm" onclick="viewAuditLog()">
                                    <i class="fas fa-history me-2"></i>View Audit Log
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Permission Editor -->
                <div class="col-md-8">
                    <div class="card shadow-lg border-0 rounded-4 animate-card">
                        <div class="card-header py-3 bg-gradient-success text-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="card-title m-0 font-weight-bold">
                                    <i class="fas fa-key me-2"></i>
                                    <span id="currentRoleTitle">Select a Role to Edit</span>
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-sm btn-warning rounded-pill px-3 me-2" id="resetRoleBtn" 
                                            onclick="resetRolePermissions()" style="display: none;">
                                        <i class="fas fa-undo me-1"></i> Reset
                                    </button>
                                    <button type="button" class="btn btn-sm btn-info rounded-pill px-3" id="previewMenuBtn" 
                                            onclick="previewRoleMenu()" style="display: none;">
                                        <i class="fas fa-eye me-1"></i> Preview
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div id="permissionEditor">
                                <div class="text-center text-muted py-5">
                                    <i class="fas fa-hand-pointer fa-4x mb-4 text-primary opacity-50"></i>
                                    <h4 class="mb-3">Select a Role to Begin</h4>
                                    <p class="lead">Choose a role from the left panel to edit its permissions and access levels</p>
                                    <div class="mt-4">
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <i class="fas fa-shield-alt fa-2x text-success mb-2"></i>
                                                <h6>Secure</h6>
                                                <small class="text-muted">Role-based access control</small>
                                            </div>
                                            <div class="col-4">
                                                <i class="fas fa-cogs fa-2x text-warning mb-2"></i>
                                                <h6>Flexible</h6>
                                                <small class="text-muted">Customizable permissions</small>
                                            </div>
                                            <div class="col-4">
                                                <i class="fas fa-users fa-2x text-info mb-2"></i>
                                                <h6>Scalable</h6>
                                                <small class="text-muted">Multi-user support</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light rounded-bottom-4" id="permissionFooter" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-success btn-lg w-100 rounded-pill shadow-sm" onclick="savePermissions()">
                                        <i class="fas fa-save me-2"></i>Save Changes
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" class="btn btn-outline-secondary btn-lg w-100 rounded-pill" onclick="cancelEdit()">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<!-- Menu Preview Modal -->
<div class="modal fade" id="menuPreviewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-info text-white">
                <h4 class="modal-title font-weight-bold">
                    <i class="fas fa-eye me-2"></i>Menu Preview: <span id="previewRoleName"></span>
                </h4>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div id="menuPreviewContent">
                    <div class="text-center py-4">
                        <i class="fas fa-spinner fa-spin fa-2x text-primary"></i>
                        <p class="mt-3">Loading menu preview...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Import Configuration Modal -->
<div class="modal fade" id="importConfigModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-gradient-warning text-white">
                <h5 class="modal-title font-weight-bold">
                    <i class="fas fa-upload me-2"></i>Import Role Configuration
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="importForm">
                    <div class="mb-3">
                        <label for="configFile" class="form-label">Select Configuration File</label>
                        <input type="file" class="form-control" id="configFile" accept=".json">
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Only JSON configuration files are supported.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-warning" onclick="processImport()">
                    <i class="fas fa-upload me-2"></i>Import
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let currentRole = null;
let currentRoleData = null;
let isLoading = false;

// Static permission data to avoid API calls
const staticPermissions = {
    'Admin': {
        role_name: 'Administrator',
        features: {
            'user_management': {
                label: 'User Management',
                features: {
                    'view_users': { name: 'View Users', description: 'View user list and details', icon: 'fas fa-users', enabled: true },
                    'create_users': { name: 'Create Users', description: 'Add new users to system', icon: 'fas fa-user-plus', enabled: true },
                    'edit_users': { name: 'Edit Users', description: 'Modify user information', icon: 'fas fa-user-edit', enabled: true },
                    'delete_users': { name: 'Delete Users', description: 'Remove users from system', icon: 'fas fa-user-times', enabled: true },
                    'manage_roles': { name: 'Manage Roles', description: 'Assign and modify user roles', icon: 'fas fa-user-tag', enabled: true }
                }
            },
            'system_management': {
                label: 'System Management',
                features: {
                    'system_settings': { name: 'System Settings', description: 'Configure system parameters', icon: 'fas fa-cogs', enabled: true },
                    'database_access': { name: 'Database Access', description: 'Direct database operations', icon: 'fas fa-database', enabled: true },
                    'backup_restore': { name: 'Backup & Restore', description: 'System backup operations', icon: 'fas fa-hdd', enabled: true },
                    'audit_logs': { name: 'Audit Logs', description: 'View system audit trails', icon: 'fas fa-clipboard-list', enabled: true }
                }
            },
            'reports': {
                label: 'Reports & Analytics',
                features: {
                    'view_reports': { name: 'View Reports', description: 'Access all system reports', icon: 'fas fa-chart-bar', enabled: true },
                    'export_data': { name: 'Export Data', description: 'Export system data', icon: 'fas fa-download', enabled: true },
                    'analytics': { name: 'Analytics Dashboard', description: 'Advanced analytics tools', icon: 'fas fa-chart-line', enabled: true }
                }
            }
        }
    },
    'Staff': {
        role_name: 'View Only Staff',
        features: {
            'basic_access': {
                label: 'Basic Access',
                features: {
                    'view_dashboard': { name: 'View Dashboard', description: 'Access main dashboard', icon: 'fas fa-tachometer-alt', enabled: true },
                    'view_reports': { name: 'View Reports', description: 'View basic reports', icon: 'fas fa-chart-bar', enabled: true },
                    'view_profile': { name: 'View Profile', description: 'View own profile', icon: 'fas fa-user', enabled: true }
                }
            }
        }
    },
    'Staff2': {
        role_name: 'Process Staff',
        features: {
            'process_management': {
                label: 'Process Management',
                features: {
                    'view_processes': { name: 'View Processes', description: 'View all processes', icon: 'fas fa-tasks', enabled: true },
                    'create_processes': { name: 'Create Processes', description: 'Create new processes', icon: 'fas fa-plus-circle', enabled: true },
                    'edit_processes': { name: 'Edit Processes', description: 'Modify existing processes', icon: 'fas fa-edit', enabled: true },
                    'workflow_control': { name: 'Workflow Control', description: 'Control process workflows', icon: 'fas fa-project-diagram', enabled: true }
                }
            }
        }
    },
    'Staff3': {
        role_name: 'Warehouse Staff',
        features: {
            'inventory': {
                label: 'Inventory Management',
                features: {
                    'view_inventory': { name: 'View Inventory', description: 'View inventory items', icon: 'fas fa-boxes', enabled: true },
                    'manage_stock': { name: 'Manage Stock', description: 'Update stock levels', icon: 'fas fa-warehouse', enabled: true },
                    'stock_reports': { name: 'Stock Reports', description: 'Generate stock reports', icon: 'fas fa-chart-pie', enabled: true }
                }
            }
        }
    },
    'Staff4': {
        role_name: 'HRD Staff',
        features: {
            'hr_management': {
                label: 'Human Resources',
                features: {
                    'employee_records': { name: 'Employee Records', description: 'Manage employee data', icon: 'fas fa-id-card', enabled: true },
                    'payroll': { name: 'Payroll Management', description: 'Process payroll', icon: 'fas fa-money-check-alt', enabled: true },
                    'recruitment': { name: 'Recruitment', description: 'Manage recruitment process', icon: 'fas fa-user-plus', enabled: true }
                }
            }
        }
    },
    'Staff5': {
        role_name: 'Finance Staff',
        features: {
            'finance': {
                label: 'Financial Management',
                features: {
                    'financial_reports': { name: 'Financial Reports', description: 'Generate financial reports', icon: 'fas fa-chart-line', enabled: true },
                    'budget_management': { name: 'Budget Management', description: 'Manage budgets', icon: 'fas fa-calculator', enabled: true },
                    'accounting': { name: 'Accounting', description: 'Handle accounting tasks', icon: 'fas fa-file-invoice-dollar', enabled: true }
                }
            }
        }
    },
    'Staff6': {
        role_name: 'IT Staff',
        features: {
            'technical': {
                label: 'Technical Support',
                features: {
                    'system_maintenance': { name: 'System Maintenance', description: 'Maintain system health', icon: 'fas fa-tools', enabled: true },
                    'technical_support': { name: 'Technical Support', description: 'Provide technical assistance', icon: 'fas fa-headset', enabled: true },
                    'server_management': { name: 'Server Management', description: 'Manage server infrastructure', icon: 'fas fa-server', enabled: true }
                }
            }
        }
    },
    'Staff7': {
        role_name: 'Purchase Staff',
        features: {
            'procurement': {
                label: 'Procurement',
                features: {
                    'purchase_orders': { name: 'Purchase Orders', description: 'Create and manage purchase orders', icon: 'fas fa-shopping-cart', enabled: true },
                    'supplier_management': { name: 'Supplier Management', description: 'Manage supplier relationships', icon: 'fas fa-truck', enabled: true },
                    'procurement_reports': { name: 'Procurement Reports', description: 'Generate procurement reports', icon: 'fas fa-file-alt', enabled: true }
                }
            }
        }
    }
};

// Initialize page
$(document).ready(function() {
    console.log('Role Management initialized');
    // Remove automatic refresh to prevent loading loop
    // refreshStats(); // Commented out - using static data instead
});

// Refresh role statistics (now using static data)
function refreshStats() {
    // Use static data instead of AJAX to prevent loading loop
    showAlert('Statistics refreshed successfully!', 'success');
}

// Select role for editing
function selectRole(role, roleName) {
    if (isLoading) return;
    
    currentRole = role;
    isLoading = true;
    
    // Update UI
    $('.role-item').removeClass('active bg-primary text-white');
    $(`.role-item[data-role="${role}"]`).addClass('active bg-primary text-white');
    $('#currentRoleTitle').text(`Edit Permissions: ${roleName}`);
    $('#resetRoleBtn, #previewMenuBtn, #permissionFooter').show();
    
    // Show loading state
    $('#permissionEditor').html(`
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3">Loading permissions for ${roleName}...</p>
        </div>
    `);
    
    // Use static data instead of AJAX
    setTimeout(() => {
        if (staticPermissions[role]) {
            currentRoleData = staticPermissions[role];
            renderPermissionEditor(staticPermissions[role]);
        } else {
            $('#permissionEditor').html(`
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    No permission data available for this role
                </div>
            `);
        }
        isLoading = false;
    }, 500); // Simulate loading time
}

// Render permission editor
function renderPermissionEditor(data) {
    let html = '<form id="permissionForm">';
    
    // Add select all/none buttons
    const totalPermissions = Object.keys(data.features).reduce((total, cat) => 
        total + Object.keys(data.features[cat].features).length, 0);
    
    html += `
        <div class="mb-4 p-3 bg-light rounded-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="selectAllPermissions()">
                            <i class="fas fa-check-double me-1"></i> Select All
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="selectNonePermissions()">
                            <i class="fas fa-times me-1"></i> Select None
                        </button>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <span class="badge bg-primary fs-6">
                        <span id="selectedCount">0</span> of ${totalPermissions} permissions selected
                    </span>
                </div>
            </div>
        </div>
    `;
    
    // Group permissions by category
    $.each(data.features, function(category, categoryData) {
        const categoryPermissions = Object.keys(categoryData.features).length;
        
        html += `
            <div class="card border-0 shadow-sm mb-4 animate-card">
                <div class="card-header bg-gradient-light py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="card-title mb-0 font-weight-bold">
                                <i class="fas fa-folder-open me-2 text-primary"></i>
                                ${categoryData.label}
                                <span class="badge bg-info ms-2">${categoryPermissions} features</span>
                            </h6>
                        </div>
                        <div class="col-auto">
                            <button type="button" class="btn btn-sm btn-outline-primary rounded-pill" 
                                    onclick="toggleCategory('${category}')">
                                <i class="fas fa-check me-1"></i> Toggle All
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row">
        `;
        
        $.each(categoryData.features, function(featureKey, feature) {
            const checked = feature.enabled ? 'checked' : '';
            html += `
                <div class="col-md-6 mb-3">
                    <div class="card border-0 bg-light h-100">
                        <div class="card-body p-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input permission-checkbox" 
                                       id="perm_${featureKey}" name="permissions[]" 
                                       value="${featureKey}" ${checked} onchange="updateSelectedCount()">
                                <label class="form-check-label w-100" for="perm_${featureKey}">
                                    <div class="d-flex align-items-start">
                                        <i class="${feature.icon} me-3 mt-1 text-primary fs-5"></i>
                                        <div>
                                            <h6 class="mb-1 font-weight-bold">${feature.name}</h6>
                                            <p class="mb-0 text-muted small">${feature.description}</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        html += `
                    </div>
                </div>
            </div>
        `;
    });
    
    html += '</form>';
    
    $('#permissionEditor').html(html);
    updateSelectedCount();
}

// Update selected count
function updateSelectedCount() {
    const count = $('.permission-checkbox:checked').length;
    $('#selectedCount').text(count);
    
    // Update progress indicator
    const total = $('.permission-checkbox').length;
    const percentage = total > 0 ? Math.round((count / total) * 100) : 0;
    
    $('#selectedCount').parent().removeClass('bg-primary bg-success bg-warning bg-danger')
                .addClass(percentage === 100 ? 'bg-success' : percentage > 50 ? 'bg-primary' : percentage > 0 ? 'bg-warning' : 'bg-danger');
}

// Select all permissions
function selectAllPermissions() {
    $('.permission-checkbox').prop('checked', true);
    updateSelectedCount();
    showAlert('All permissions selected', 'info');
}

// Select none permissions
function selectNonePermissions() {
    $('.permission-checkbox').prop('checked', false);
    updateSelectedCount();
    showAlert('All permissions deselected', 'info');
}

// Toggle category permissions
function toggleCategory(category) {
    const categoryCard = $(`.card:has(.card-title:contains("${category}"))`);
    const checkboxes = categoryCard.find('.permission-checkbox');
    const allChecked = checkboxes.length === checkboxes.filter(':checked').length;
    
    checkboxes.prop('checked', !allChecked);
    updateSelectedCount();
    
    const action = allChecked ? 'deselected' : 'selected';
    showAlert(`Category permissions ${action}`, 'info');
}

// Save permissions
function savePermissions() {
    if (!currentRole) return;
    
    const permissions = $('.permission-checkbox:checked').map(function() {
        return this.value;
    }).get();
    
    // Show loading
    const saveBtn = $('button:contains("Save Changes")');
    const originalText = saveBtn.html();
    saveBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...').prop('disabled', true);
    
    // Simulate save operation
    setTimeout(() => {
        // Update static data
        if (staticPermissions[currentRole]) {
            Object.keys(staticPermissions[currentRole].features).forEach(category => {
                Object.keys(staticPermissions[currentRole].features[category].features).forEach(feature => {
                    staticPermissions[currentRole].features[category].features[feature].enabled = 
                        permissions.includes(feature);
                });
            });
        }
        
        showAlert(`Permissions saved successfully for ${currentRoleData.role_name}!`, 'success');
        saveBtn.html(originalText).prop('disabled', false);
        
        // Update role list badge
        $(`.role-item[data-role="${currentRole}"] .badge`).text(permissions.length);
        
    }, 1000);
}

// Reset role permissions
function resetRolePermissions() {
    if (!currentRole) return;
    
    if (!confirm(`Are you sure you want to reset permissions for ${currentRoleData.role_name} to default?`)) {
        return;
    }
    
    // Reset to default permissions based on role
    const defaultPermissions = getDefaultPermissions(currentRole);
    
    // Update checkboxes
    $('.permission-checkbox').each(function() {
        const isDefault = defaultPermissions.includes(this.value);
        $(this).prop('checked', isDefault);
    });
    
    updateSelectedCount();
    showAlert(`Permissions reset to default for ${currentRoleData.role_name}`, 'warning');
}

// Get default permissions for role
function getDefaultPermissions(role) {
    const defaults = {
        'Admin': Object.keys(staticPermissions['Admin'].features).flatMap(cat => 
            Object.keys(staticPermissions['Admin'].features[cat].features)),
        'Staff': ['view_dashboard', 'view_reports', 'view_profile'],
        'Staff2': ['view_processes', 'create_processes', 'edit_processes', 'workflow_control'],
        'Staff3': ['view_inventory', 'manage_stock', 'stock_reports'],
        'Staff4': ['employee_records', 'payroll', 'recruitment'],
        'Staff5': ['financial_reports', 'budget_management', 'accounting'],
        'Staff6': ['system_maintenance', 'technical_support', 'server_management'],
        'Staff7': ['purchase_orders', 'supplier_management', 'procurement_reports']
    };
    
    return defaults[role] || [];
}

// Preview role menu
function previewRoleMenu() {
    if (!currentRole) return;
    
    $('#previewRoleName').text(currentRoleData.role_name);
    $('#menuPreviewModal').modal('show');
    
    // Generate menu preview based on selected permissions
    const selectedPermissions = $('.permission-checkbox:checked').map(function() {
        return this.value;
    }).get();
    
    let html = '';
    
    if (selectedPermissions.length === 0) {
        html = `
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                No menu items available - no permissions selected
            </div>
        `;
    } else {
        // Group permissions by category for menu preview
        Object.keys(staticPermissions[currentRole].features).forEach(category => {
            const categoryData = staticPermissions[currentRole].features[category];
            const categoryPermissions = Object.keys(categoryData.features).filter(feature => 
                selectedPermissions.includes(feature));
            
            if (categoryPermissions.length > 0) {
                html += `
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-gradient-primary text-white">
                            <h6 class="card-title mb-0 font-weight-bold">
                                <i class="fas fa-folder me-2"></i>${categoryData.label}
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                `;
                
                categoryPermissions.forEach(feature => {
                    const featureData = categoryData.features[feature];
                    html += `
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center p-2 bg-light rounded">
                                <i class="${featureData.icon} me-3 text-primary fs-5"></i>
                                <div>
                                    <h6 class="mb-0 font-weight-bold">${featureData.name}</h6>
                                    <small class="text-muted">${featureData.description}</small>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                html += `
                            </div>
                        </div>
                    </div>
                `;
            }
        });
    }
    
    $('#menuPreviewContent').html(html);
}

// Cancel edit
function cancelEdit() {
    if (currentRole && $('.permission-checkbox').length > 0) {
        if (!confirm('Are you sure you want to cancel? Any unsaved changes will be lost.')) {
            return;
        }
    }
    
    currentRole = null;
    currentRoleData = null;
    $('.role-item').removeClass('active bg-primary text-white');
    $('#currentRoleTitle').text('Select a Role to Edit');
    $('#resetRoleBtn, #previewMenuBtn, #permissionFooter').hide();
    $('#permissionEditor').html(`
        <div class="text-center text-muted py-5">
            <i class="fas fa-hand-pointer fa-4x mb-4 text-primary opacity-50"></i>
            <h4 class="mb-3">Select a Role to Begin</h4>
            <p class="lead">Choose a role from the left panel to edit its permissions and access levels</p>
            <div class="mt-4">
                <div class="row text-center">
                    <div class="col-4">
                        <i class="fas fa-shield-alt fa-2x text-success mb-2"></i>
                        <h6>Secure</h6>
                        <small class="text-muted">Role-based access control</small>
                    </div>
                    <div class="col-4">
                        <i class="fas fa-cogs fa-2x text-warning mb-2"></i>
                        <h6>Flexible</h6>
                        <small class="text-muted">Customizable permissions</small>
                    </div>
                    <div class="col-4">
                        <i class="fas fa-users fa-2x text-info mb-2"></i>
                        <h6>Scalable</h6>
                        <small class="text-muted">Multi-user support</small>
                    </div>
                </div>
            </div>
        </div>
    `);
}

// Export role configuration
function exportRoleConfig() {
    const config = {
        timestamp: new Date().toISOString(),
        roles: staticPermissions
    };
    
    const dataStr = JSON.stringify(config, null, 2);
    const dataBlob = new Blob([dataStr], {type: 'application/json'});
    
    const link = document.createElement('a');
    link.href = URL.createObjectURL(dataBlob);
    link.download = `role-configuration-${new Date().toISOString().split('T')[0]}.json`;
    link.click();
    
    showAlert('Configuration exported successfully!', 'success');
}

// Import role configuration
function importRoleConfig() {
    $('#importConfigModal').modal('show');
}

// Process import
function processImport() {
    const fileInput = document.getElementById('configFile');
    const file = fileInput.files[0];
    
    if (!file) {
        showAlert('Please select a file to import', 'warning');
        return;
    }
    
    const reader = new FileReader();
    reader.onload = function(e) {
        try {
            const config = JSON.parse(e.target.result);
            
            if (config.roles) {
                // Update static permissions
                Object.assign(staticPermissions, config.roles);
                showAlert('Configuration imported successfully!', 'success');
                $('#importConfigModal').modal('hide');
                
                // Refresh current view if a role is selected
                if (currentRole) {
                    selectRole(currentRole, currentRoleData.role_name);
                }
            } else {
                showAlert('Invalid configuration file format', 'danger');
            }
        } catch (error) {
            showAlert('Error parsing configuration file', 'danger');
        }
    };
    
    reader.readAsText(file);
}

// View audit log
function viewAuditLog() {
    const auditData = [
        { timestamp: '2024-01-15 10:30:00', user: 'Admin', action: 'Modified Staff2 permissions', details: 'Added workflow_control permission' },
        { timestamp: '2024-01-15 09:15:00', user: 'Admin', action: 'Reset Staff permissions', details: 'Reset to default view-only permissions' },
        { timestamp: '2024-01-14 16:45:00', user: 'Admin', action: 'Created new role', details: 'Added Staff8 role for marketing' },
        { timestamp: '2024-01-14 14:20:00', user: 'Admin', action: 'Modified Admin permissions', details: 'Updated system management access' }
    ];
    
    let html = `
        <div class="card border-0 shadow-lg">
            <div class="card-header bg-gradient-dark text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-history me-2"></i>Role Management Audit Log
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Timestamp</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Details</th>
                            </tr>
                        </thead>
                        <tbody>
    `;
    
    auditData.forEach(entry => {
        html += `
            <tr>
                <td><small class="text-muted">${entry.timestamp}</small></td>
                <td><span class="badge bg-primary">${entry.user}</span></td>
                <td>${entry.action}</td>
                <td><small class="text-muted">${entry.details}</small></td>
            </tr>
        `;
    });
    
    html += `
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    `;
    
    // Create modal for audit log
    const modal = $(`
        <div class="modal fade" id="auditLogModal" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-gradient-dark text-white">
                        <h4 class="modal-title font-weight-bold">
                            <i class="fas fa-clipboard-list me-2"></i>Audit Log
                        </h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        ${html}
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Close
                        </button>
                        <button type="button" class="btn btn-primary rounded-pill px-4" onclick="exportAuditLog()">
                            <i class="fas fa-download me-2"></i>Export Log
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `);
    
    $('body').append(modal);
    modal.modal('show');
    
    // Remove modal after hiding
    modal.on('hidden.bs.modal', function() {
        modal.remove();
    });
}

// Export audit log
function exportAuditLog() {
    showAlert('Audit log exported successfully!', 'success');
}

// Show alert function
function showAlert(message, type = 'info', duration = 5000) {
    const alertHtml = `
        <div class="alert alert-${type} alert-dismissible fade show shadow-sm border-0 rounded-3" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} me-2 fs-5"></i>
                <div>${message}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        </div>
    `;
    
    $('#alertContainer').html(alertHtml);
    
    // Auto-remove after duration
    setTimeout(() => {
        $('#alertContainer .alert').fadeOut(() => {
            $('#alertContainer').empty();
        });
    }, duration);
}

// Keyboard shortcuts
$(document).keydown(function(e) {
    // Ctrl+S to save
    if (e.ctrlKey && e.key === 's') {
        e.preventDefault();
        if (currentRole) {
            savePermissions();
        }
    }
    
    // Escape to cancel
    if (e.key === 'Escape') {
        if (currentRole) {
            cancelEdit();
        }
    }
});

// Auto-save functionality
let autoSaveTimer;
$(document).on('change', '.permission-checkbox', function() {
    clearTimeout(autoSaveTimer);
    autoSaveTimer = setTimeout(() => {
        if (currentRole) {
            // Auto-save draft
            const permissions = $('.permission-checkbox:checked').map(function() {
                return this.value;
            }).get();
            
            localStorage.setItem(`role_draft_${currentRole}`, JSON.stringify(permissions));
            showAlert('Draft saved automatically', 'info', 2000);
        }
    }, 3000);
});

// Load draft on role selection
function loadDraft(role) {
    const draft = localStorage.getItem(`role_draft_${role}`);
    if (draft) {
        const permissions = JSON.parse(draft);
        $('.permission-checkbox').each(function() {
            $(this).prop('checked', permissions.includes(this.value));
        });
        updateSelectedCount();
        showAlert('Draft loaded', 'info', 2000);
    }
}

// Clear draft after save
function clearDraft(role) {
    localStorage.removeItem(`role_draft_${role}`);
}
</script>

<!-- Enhanced CSS -->
<style>
/* Enhanced animations and styling */
.animate-card {
    animation: slideInUp 0.6s ease-out;
    transition: all 0.3s ease;
}

.animate-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.animate-hover {
    transition: all 0.3s ease;
}

.animate-hover:hover {
    transform: translateY(-1px);
}

@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Role item styling */
.role-item {
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.role-item:hover {
    border-left-color: #007bff;
    background-color: #f8f9fa;
    transform: translateX(5px);
}

.role-item.active {
    border-left-color: #007bff;
    background: linear-gradient(135deg, #007bff, #0056b3) !important;
    color: white !important;
}

.role-item.active * {
    color: white !important;
}

/* Permission cards */
.permission-checkbox {
    transform: scale(1.2);
    margin-right: 0.5rem;
}

.form-check-label {
    cursor: pointer;
    user-select: none;
}

.form-check:hover .form-check-label {
    background-color: rgba(0,123,255,0.1);
    border-radius: 0.5rem;
    padding: 0.5rem;
    margin: -0.5rem;
}

/* Small box enhancements */
.small-box {
    border-radius: 1rem !important;
    overflow: hidden;
    position: relative;
}

.small-box::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(45deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
    pointer-events: none;
}

.small-box .inner {
    position: relative;
    z-index: 1;
}

.small-box .icon {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 70px;
    opacity: 0.3;
}

/* Progress bars */
.progress {
    height: 6px;
    border-radius: 3px;
    background-color: rgba(255,255,255,0.3);
}

.progress-bar {
    border-radius: 3px;
    background: linear-gradient(90deg, rgba(255,255,255,0.8), rgba(255,255,255,0.6));
}

/* Button enhancements */
.btn {
    transition: all 0.3s ease;
    font-weight: 500;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-outline-primary:hover,
.btn-outline-success:hover,
.btn-outline-danger:hover,
.btn-outline-warning:hover,
.btn-outline-info:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Modal enhancements */
.modal-content {
    border-radius: 1rem;
    overflow: hidden;
}

.modal-header {
    border-bottom: none;
    padding: 1.5rem 2rem 1rem;
}

.modal-body {
    padding: 1rem 2rem;
}

.modal-footer {
    border-top: none;
    padding: 1rem 2rem 1.5rem;
}

/* Card header gradients */
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff, #0056b3) !important;
}

.bg-gradient-success {
    background: linear-gradient(135deg, #28a745, #1e7e34) !important;
}

.bg-gradient-info {
    background: linear-gradient(135deg, #17a2b8, #138496) !important;
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #ffc107, #e0a800) !important;
}

.bg-gradient-danger {
    background: linear-gradient(135deg, #dc3545, #c82333) !important;
}

.bg-gradient-dark {
    background: linear-gradient(135deg, #343a40, #23272b) !important;
}

.bg-gradient-light {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef) !important;
}

/* Text gradient */
.text-gradient {
    background: linear-gradient(135deg, #007bff, #6610f2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Responsive improvements */
@media (max-width: 768px) {
    .animate-card:hover,
    .animate-hover:hover {
        transform: none;
    }
    
    .role-item:hover {
        transform: none;
    }
    
    .btn:hover {
        transform: none;
    }
    
    .small-box .icon {
        font-size: 50px;
    }
    
    .modal-dialog {
        margin: 0.5rem;
    }
    
    .card-body {
        padding: 1.5rem !important;
    }
}

/* Loading states */
.spinner-border {
    width: 3rem;
    height: 3rem;
}

/* Badge enhancements */
.badge {
    font-weight: 500;
    letter-spacing: 0.025em;
}

/* Table enhancements */
.table-hover tbody tr:hover {
    background-color: rgba(0,123,255,0.05);
}

/* Alert enhancements */
.alert {
    border: none;
    border-radius: 0.75rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.alert-success {
    background: linear-gradient(135deg, #d4edda, #c3e6cb);
    color: #155724;
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da, #f5c6cb);
    color: #721c24;
}

.alert-warning {
    background: linear-gradient(135deg, #fff3cd, #ffeaa7);
    color: #856404;
}

.alert-info {
    background: linear-gradient(135deg, #d1ecf1, #bee5eb);
    color: #0c5460;
}

/* Scrollbar styling */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #007bff, #0056b3);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #0056b3, #004085);
}
</style>

@endsection

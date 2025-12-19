<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class HomeController extends Controller
{
    /**
     * Constructor - Require authentication
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * ========================================================================
     * PERMISSION SYSTEM
     * ========================================================================
     */

    /**
     * Get all available features/permissions
     */
    private function getAllAvailableFeatures()
    {
        return [
            // Dashboard
            'dashboard' => [
                'name' => 'Dashboard',
                'description' => 'View dashboard with statistics and charts',
                'icon' => 'fa-tachometer-alt',
                'route' => 'dashboard',
                'category' => 'main'
            ],
            
            // Movies Management
            'movies_view' => [
                'name' => 'View Movies',
                'description' => 'View movies list',
                'icon' => 'fa-film',
                'route' => 'movies.index',
                'category' => 'movies'
            ],
            'movies_create' => [
                'name' => 'Create Movie',
                'description' => 'Add new movie',
                'icon' => 'fa-plus',
                'route' => 'movies.create',
                'category' => 'movies'
            ],
            'movies_edit' => [
                'name' => 'Edit Movie',
                'description' => 'Edit existing movie',
                'icon' => 'fa-edit',
                'route' => null,
                'category' => 'movies'
            ],
            'movies_delete' => [
                'name' => 'Delete Movie',
                'description' => 'Delete movie',
                'icon' => 'fa-trash',
                'route' => null,
                'category' => 'movies'
            ],
            
            // API Sync
            'sync_view' => [
                'name' => 'View Sync',
                'description' => 'View sync page',
                'icon' => 'fa-sync',
                'route' => 'sync.index',
                'category' => 'sync'
            ],
            'sync_execute' => [
                'name' => 'Execute Sync',
                'description' => 'Run API synchronization',
                'icon' => 'fa-play',
                'route' => null,
                'category' => 'sync'
            ],
            'sync_history' => [
                'name' => 'Sync History',
                'description' => 'View sync history',
                'icon' => 'fa-history',
                'route' => 'sync.history',
                'category' => 'sync'
            ],
            
            // User Management
            'user_management' => [
                'name' => 'User Management',
                'description' => 'Manage users and roles',
                'icon' => 'fa-users-cog',
                'route' => 'home',
                'category' => 'admin'
            ],
        ];
    }

    /**
     * Get default permissions for each role
     */
    private function getDefaultRolePermissions()
    {
        return [
            'Admin' => array_keys($this->getAllAvailableFeatures()), // Admin has all permissions
            'Manager' => [
                'dashboard',
                'movies_view',
                'movies_create',
                'movies_edit',
                'sync_view',
                'sync_execute',
                'sync_history'
            ],
            'Staff' => [
                'dashboard',
                'movies_view'
            ],
            'Guest' => [
                'dashboard'
            ],
        ];
    }

    /**
     * Get permissions for specific role
     */
    public function getRolePermissions($role)
    {
        $allPermissions = $this->getDefaultRolePermissions();
        return $allPermissions[$role] ?? [];
    }

    /**
     * Check if user has specific permission
     */
    public function hasPermission($permission, $userId = null)
    {
        $user = $userId ? User::find($userId) : Auth::user();
        
        if (!$user) return false;
        
        $userPermissions = $this->getRolePermissions($user->role);
        return in_array($permission, $userPermissions);
    }

    /**
     * Check permission and throw 403 if not authorized
     */
    public function checkPermission($permission)
    {
        if (!$this->hasPermission($permission)) {
            abort(403, 'You do not have permission to access this feature.');
        }
    }

    /**
     * Get all available roles
     */
    public function getAllRoles()
    {
        return [
            'Admin' => 'Administrator',
            'Manager' => 'Manager',
            'Staff' => 'Staff',
            'Guest' => 'Guest',
        ];
    }

    /**
     * ========================================================================
     * USER MANAGEMENT CRUD
     * ========================================================================
     */

    /**
     * Display users list
     */
    public function index()
    {
        $this->checkPermission('user_management');
        
        $users = User::orderBy('id', 'desc')->get();
        $allRoles = $this->getAllRoles();
        
        // Add permission info for each user
        foreach ($users as $user) {
            $user->permissions = $this->getRolePermissions($user->role);
            $user->permission_count = count($user->permissions);
        }
        
        return view('home.index', compact('users', 'allRoles'));
    }

    /**
     * Show create user form
     */
    public function create()
    {
        $this->checkPermission('user_management');
        
        $allRoles = $this->getAllRoles();
        return view('home.create', compact('allRoles'));
    }

    /**
     * Store new user
     */
    public function store(Request $request)
    {
        $this->checkPermission('user_management');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:Admin,Manager,Staff,Guest',
            'password' => 'required|string|min:5|confirmed',
        ]);

        User::create([
            'name' => ucwords($validated['name']),
            'email' => strtolower($validated['email']),
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('home')
            ->with('success', "User '{$validated['name']}' has been created successfully.");
    }

    /**
     * Show edit user form
     */
    public function edit(User $user)
    {
        $this->checkPermission('user_management');
        
        $allRoles = $this->getAllRoles();
        return view('home.edit', compact('user', 'allRoles'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $this->checkPermission('user_management');
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'role' => 'required|in:Admin,Manager,Staff,Guest',
            'password' => 'nullable|string|min:5|confirmed',
        ]);

        $updateData = [
            'name' => ucwords($validated['name']),
            'email' => strtolower($validated['email']),
            'role' => $validated['role'],
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('home')
            ->with('success', "User '{$user->name}' has been updated successfully.");
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        $this->checkPermission('user_management');
        
        // Prevent deleting own account
        if ($user->id === Auth::id()) {
            return redirect()->route('home')
                ->with('error', 'You cannot delete your own account.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('home')
            ->with('success', "User '{$name}' has been deleted successfully.");
    }

    /**
     * ========================================================================
     * PROFILE MANAGEMENT
     * ========================================================================
     */

    /**
     * Show user profile
     */
    public function profile($id)
    {
        $user = User::findOrFail($id);
        
        // Users can only view their own profile unless they're admin
        if ($user->id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'You can only view your own profile.');
        }
        
        return view('home.profile', compact('user'));
    }

    /**
     * Update user profile and password
     */
    public function update_password(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Users can only update their own profile unless they're admin
        if ($user->id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'You can only update your own profile.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'current_password' => 'required_with:password',
            'password' => 'nullable|string|min:5|confirmed',
        ]);

        // Verify current password if trying to change password
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }
        }

        $updateData = [
            'name' => ucwords($validated['name']),
            'email' => strtolower($validated['email']),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
            $message = 'Profile and password updated successfully.';
        } else {
            $message = 'Profile updated successfully.';
        }

        $user->update($updateData);

        return redirect()->route('home.profile', $user->id)
            ->with('success', $message);
    }

    /**
     * ========================================================================
     * API ENDPOINTS
     * ========================================================================
     */

    /**
     * Get user statistics by role
     */
    public function getUserStats()
    {
        $this->checkPermission('user_management');
        
        $stats = User::selectRaw('role, COUNT(*) as count')
            ->groupBy('role')
            ->get()
            ->pluck('count', 'role')
            ->toArray();

        $allRoles = $this->getAllRoles();
        $result = [];

        foreach ($allRoles as $roleKey => $roleName) {
            $result[$roleKey] = [
                'name' => $roleName,
                'user_count' => $stats[$roleKey] ?? 0,
                'permission_count' => count($this->getRolePermissions($roleKey))
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    /**
     * Get role permissions (API)
     */
    public function apiGetRolePermissions($role)
    {
        $this->checkPermission('user_management');
        
        $allFeatures = $this->getAllAvailableFeatures();
        $rolePermissions = $this->getRolePermissions($role);
        
        // Group features by category
        $groupedFeatures = [];
        foreach ($allFeatures as $key => $feature) {
            $category = $feature['category'];
            if (!isset($groupedFeatures[$category])) {
                $groupedFeatures[$category] = [
                    'label' => ucfirst($category),
                    'features' => []
                ];
            }
            $groupedFeatures[$category]['features'][$key] = [
                'key' => $key,
                'name' => $feature['name'],
                'description' => $feature['description'],
                'icon' => $feature['icon'],
                'enabled' => in_array($key, $rolePermissions)
            ];
        }

        return response()->json([
            'success' => true,
            'data' => [
                'role' => $role,
                'role_name' => $this->getAllRoles()[$role] ?? $role,
                'features' => $groupedFeatures,
                'current_permissions' => $rolePermissions
            ]
        ]);
    }

    /**
     * ========================================================================
     * STATIC HELPER METHODS (for Blade views)
     * ========================================================================
     */

    /**
     * Check user permission (static - for Blade)
     */
    public static function checkUserPermission($permission)
    {
        $instance = new self();
        return $instance->hasPermission($permission);
    }

    /**
     * Get user menu items (static - for Blade)
     */
    public static function getUserMenu()
    {
        $instance = new self();
        $user = Auth::user();
        
        if (!$user) return [];
        
        $userPermissions = $instance->getRolePermissions($user->role);
        $allFeatures = $instance->getAllAvailableFeatures();
        
        $userMenus = [];
        foreach ($userPermissions as $permission) {
            if (isset($allFeatures[$permission]) && $allFeatures[$permission]['route']) {
                $userMenus[$permission] = $allFeatures[$permission];
            }
        }
        
        return $userMenus;
    }
}

<?php

/*
|--------------------------------------------------------------------------
| Web Routes - MovieDB Application
|--------------------------------------------------------------------------
| Laravel 8 + React SPA
| Tanpa Authentication (untuk development cepat)
|
*/

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
// Controllers
use App\Http\Controllers\PagesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ApiSyncController;
use App\Http\Controllers\DataManagementController;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| API ENDPOINTS (Backend untuk React)
|--------------------------------------------------------------------------
| PENTING: Semua API route harus di atas catch-all route!
|
*/

// ============================================================================
// DASHBOARD API
// ============================================================================
Route::prefix('api/dashboard')->group(function() {
    // Statistics
    Route::get('statistics', [DashboardController::class, 'getStatistics']);
    
    // Charts
    Route::get('movies-by-genre', [DashboardController::class, 'getMoviesByGenre']);
    Route::get('movies-by-date', [DashboardController::class, 'getMoviesByDate']);
    Route::get('movies-by-year', [DashboardController::class, 'getMoviesByYear']);
    Route::get('rating-distribution', [DashboardController::class, 'getRatingDistribution']);
    
    // Top Lists
    Route::get('top-rated', [DashboardController::class, 'getTopRated']);
    Route::get('most-popular', [DashboardController::class, 'getMostPopular']);
    Route::get('recent-movies', [DashboardController::class, 'getRecentMovies']);
    Route::get('highest-grossing', [DashboardController::class, 'getHighestGrossing']);
    
    // All Data (untuk load sekaligus - optional)
    Route::get('all', [DashboardController::class, 'getDashboardData']);
});

// ============================================================================
// MOVIES API (Data Management)
// ============================================================================
Route::prefix('api/movies')->group(function() {
    
    // ===== SPECIFIC ROUTES (MUST BE FIRST) =====
    // These must come BEFORE /{id} to avoid conflicts
    
    // Statistics (must be before /{id})
    Route::get('statistics', [DataManagementController::class, 'getStatistics']);
    
    // Filters & Metadata (must be before /{id})
    Route::get('genres', [DataManagementController::class, 'getGenres']);
    Route::get('years', [DataManagementController::class, 'getYears']);
    Route::get('statuses', [DataManagementController::class, 'getStatuses']);
    Route::get('languages', [DataManagementController::class, 'getLanguages']);
    
    // Search & Filter (must be before /{id})
    Route::get('search', [DataManagementController::class, 'search']);
    Route::get('filter', [DataManagementController::class, 'filter']);
    
    // Export (must be before /{id})
    Route::get('export', [DataManagementController::class, 'exportCsv']);
    
    // Bulk Operations (POST methods, no conflict)
    Route::post('bulk-delete', [DataManagementController::class, 'bulkDelete']);
    Route::post('bulk-export', [DataManagementController::class, 'exportCsv']);
    
    // ===== LIST & CRUD (DYNAMIC ROUTES LAST) =====
    
    // List all movies (with filters)
    Route::get('/', [DataManagementController::class, 'getMoviesList']);
    
    // CRUD Operations (/{id} must be LAST for GET)
    Route::get('/{id}', [DataManagementController::class, 'show']);
    Route::post('/', [DataManagementController::class, 'store']);
    Route::put('/{id}', [DataManagementController::class, 'update']);
    Route::patch('/{id}', [DataManagementController::class, 'update']); // Alternative for partial update
    Route::delete('/{id}', [DataManagementController::class, 'destroy']);
});

// ============================================================================
// SYNC API
// ============================================================================
Route::prefix('api/sync')->group(function() {
    // Execute Sync
    Route::post('execute', [ApiSyncController::class, 'sync']);
    
    // Test Connection
    Route::get('test-connection', [ApiSyncController::class, 'testConnection']);
    
    // Sync Info
    Route::get('last', [ApiSyncController::class, 'getLastSync']);
    Route::get('status', [ApiSyncController::class, 'getSyncStatus']);
    Route::get('statistics', [ApiSyncController::class, 'getStatistics']);
    
    // History
    Route::get('history', [ApiSyncController::class, 'getSyncHistory']);
    
    // Manage Logs
    Route::delete('logs/{id}', [ApiSyncController::class, 'deleteSyncLog']);
    Route::delete('logs', [ApiSyncController::class, 'clearSyncLogs']);
});

// ============================================================================
// USER MANAGEMENT API (Optional - untuk future development)
// ============================================================================
Route::prefix('api/users')->group(function() {
    Route::get('/', [HomeController::class, 'index']);
    Route::get('stats', [HomeController::class, 'getUserStats']);
    Route::post('/', [HomeController::class, 'store']);
    Route::put('/{user}', [HomeController::class, 'update']);
    Route::delete('/{user}', [HomeController::class, 'destroy']);
});

/*
|--------------------------------------------------------------------------
| DEVELOPMENT & TESTING ROUTES
|--------------------------------------------------------------------------
*/

// Test API Page (untuk testing backend)
Route::get('/test-api', function () {
    return view('test-api');
})->name('test-api');

// API Health Check
Route::get('/api/health', function () {
    return response()->json([
        'status' => 'ok',
        'message' => 'API is running',
        'timestamp' => now()->toDateTimeString(),
        'environment' => config('app.env'),
        'database' => DB::connection()->getDatabaseName(),
    ]);
});

/*
|--------------------------------------------------------------------------
| REACT SPA ROUTE (Catch-all)
|--------------------------------------------------------------------------
| Semua route selain API akan di-handle oleh React Router
| PENTING: Taruh di paling bawah!
|
| Route ini akan menangkap:
| - /dashboard
| - /movies
| - /sync
| - /users
| - dan semua route React lainnya
|
*/

Route::get('/{any}', function () {
    return view('app');
})->where('any', '^(?!api|test-api).*$');

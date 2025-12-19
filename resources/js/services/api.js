import axios from 'axios';

// Base URL dari Laravel
const API_BASE_URL = window.location.origin;

// Create axios instance
const api = axios.create({
    baseURL: API_BASE_URL,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
    },
});

// Add CSRF token to requests
const csrfToken = document.querySelector('meta[name="csrf-token"]');
if (csrfToken) {
    api.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.content;
}

// Request interceptor
api.interceptors.request.use(
    (config) => {
        // You can add auth token here if needed
        // const token = localStorage.getItem('token');
        // if (token) {
        //     config.headers.Authorization = `Bearer ${token}`;
        // }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Response interceptor
api.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        // Handle errors globally
        if (error.response) {
            // Server responded with error
            console.error('API Error:', error.response.data);
            
            if (error.response.status === 401) {
                // Unauthorized - redirect to login if needed
                // window.location.href = '/login';
            }
            
            if (error.response.status === 419) {
                // CSRF token mismatch - reload page
                alert('Session expired. Please refresh the page.');
                window.location.reload();
            }
        } else if (error.request) {
            // Request made but no response
            console.error('Network Error:', error.request);
        } else {
            // Something else happened
            console.error('Error:', error.message);
        }
        
        return Promise.reject(error);
    }
);

// ============================================================================
// DASHBOARD API
// ============================================================================
export const dashboardAPI = {
    // Statistics
    getStatistics: () => api.get('/api/dashboard/statistics'),
    
    // Charts
    getMoviesByGenre: () => api.get('/api/dashboard/movies-by-genre'),
    getMoviesByDate: (months = 12) => api.get(`/api/dashboard/movies-by-date?months=${months}`),
    getMoviesByYear: (years = 5) => api.get(`/api/dashboard/movies-by-year?years=${years}`),
    getRatingDistribution: () => api.get('/api/dashboard/rating-distribution'),
    
    // Top Lists
    getTopRated: (limit = 10) => api.get(`/api/dashboard/top-rated?limit=${limit}`),
    getMostPopular: (limit = 10) => api.get(`/api/dashboard/most-popular?limit=${limit}`),
    getRecentMovies: (limit = 10) => api.get(`/api/dashboard/recent-movies?limit=${limit}`),
    getHighestGrossing: (limit = 10) => api.get(`/api/dashboard/highest-grossing?limit=${limit}`),
};

// ============================================================================
// MOVIES API (Data Management)
// ============================================================================
export const moviesAPI = {
    // CRUD Operations
    getList: (params) => api.get('/api/movies', { params }),
    getById: (id) => api.get(`/api/movies/${id}`),
    create: (data) => api.post('/api/movies', data),
    update: (id, data) => api.put(`/api/movies/${id}`, data),
    delete: (id) => api.delete(`/api/movies/${id}`),
    
    // Bulk Operations
    bulkDelete: (data) => api.post('/api/movies/bulk-delete', data),
    bulkExport: (data) => api.post('/api/movies/bulk-export', data, { responseType: 'blob' }),
    
    // Filters & Search
    getGenres: () => api.get('/api/movies/genres'),
    getYears: () => api.get('/api/movies/years'),
    getStatuses: () => api.get('/api/movies/statuses'),
    search: (params) => api.get('/api/movies/search', { params }),
    filter: (params) => api.get('/api/movies/filter', { params }),
    
    // Export
    exportCsv: (params) => api.get('/api/movies/export', { 
        params,
        responseType: 'blob' 
    }),
    
    // Statistics
    getStatistics: () => api.get('/api/movies/statistics'),
};

// ============================================================================
// SYNC API
// ============================================================================
export const syncAPI = {
    // Execute Sync
    execute: (data) => api.post('/api/sync/execute', data),
    
    // Test Connection
    testConnection: () => api.get('/api/sync/test-connection'),
    
    // Sync Info
    getLastSync: () => api.get('/api/sync/last'),
    getSyncStatus: () => api.get('/api/sync/status'),
    getStatistics: () => api.get('/api/sync/statistics'),
    
    // History
    getHistory: (params) => api.get('/api/sync/history', { params }),
    
    // Manage Logs
    deleteSyncLog: (id) => api.delete(`/api/sync/logs/${id}`),
    clearSyncLogs: () => api.delete('/api/sync/logs'),
};

// ============================================================================
// HELPER FUNCTIONS
// ============================================================================

/**
 * Format error message from API response
 */
export const getErrorMessage = (error) => {
    if (error.response) {
        // Server responded with error
        if (error.response.data) {
            if (error.response.data.message) {
                return error.response.data.message;
            }
            if (error.response.data.errors) {
                // Validation errors
                const errors = error.response.data.errors;
                const firstError = Object.values(errors)[0];
                return Array.isArray(firstError) ? firstError[0] : firstError;
            }
        }
        return `Error ${error.response.status}: ${error.response.statusText}`;
    } else if (error.request) {
        return 'Network error. Please check your connection.';
    } else {
        return error.message || 'An unexpected error occurred.';
    }
};

/**
 * Download file from blob response
 */
export const downloadFile = (blob, filename) => {
    const url = window.URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', filename);
    document.body.appendChild(link);
    link.click();
    link.remove();
    window.URL.revokeObjectURL(url);
};

/**
 * Format date for API
 */
export const formatDateForAPI = (date) => {
    if (!date) return null;
    if (typeof date === 'string') return date;
    
    const d = new Date(date);
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    
    return `${year}-${month}-${day}`;
};

/**
 * Parse date from API
 */
export const parseDateFromAPI = (dateString) => {
    if (!dateString) return null;
    return new Date(dateString);
};

/**
 * Build query string from object
 */
export const buildQueryString = (params) => {
    const filtered = Object.entries(params)
        .filter(([_, value]) => value !== null && value !== undefined && value !== '')
        .map(([key, value]) => `${encodeURIComponent(key)}=${encodeURIComponent(value)}`)
        .join('&');
    
    return filtered ? `?${filtered}` : '';
};

// Export default axios instance
export default api;

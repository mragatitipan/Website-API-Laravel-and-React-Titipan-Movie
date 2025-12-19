import React, { useState, useEffect } from 'react';
import { syncAPI } from '../services/api';

function Sync() {
    // ========================================================================
    // STATES
    // ========================================================================
    const [syncing, setSyncing] = useState(false);
    const [progress, setProgress] = useState(0);
    const [pages, setPages] = useState(1);
    const [lastSync, setLastSync] = useState(null);
    const [history, setHistory] = useState([]);
    const [statistics, setStatistics] = useState(null);
    const [testResult, setTestResult] = useState(null);
    const [testing, setTesting] = useState(false);
    const [loading, setLoading] = useState(true);
    const [filterStatus, setFilterStatus] = useState('');
    const [filterSyncType, setFilterSyncType] = useState('');

    // ========================================================================
    // LOAD DATA ON MOUNT
    // ========================================================================
    useEffect(() => {
        loadAllData();
    }, []);

    // ========================================================================
    // LOAD ALL DATA
    // ========================================================================
    const loadAllData = async () => {
        setLoading(true);
        try {
            await Promise.all([
                loadLastSync(),
                loadHistory(),
                loadStatistics()
            ]);
        } catch (error) {
            console.error('❌ Error loading data:', error);
        } finally {
            setLoading(false);
        }
    };

    // ========================================================================
    // LOAD LAST SYNC
    // ========================================================================
    const loadLastSync = async () => {
        try {
            const response = await syncAPI.getLastSync();
            setLastSync(response.data.data);
        } catch (error) {
            console.error('❌ Error loading last sync:', error);
        }
    };

    // ========================================================================
    // LOAD HISTORY
    // ========================================================================
    const loadHistory = async () => {
        try {
            const params = { per_page: 10 };
            if (filterStatus) params.status = filterStatus;
            if (filterSyncType) params.sync_type = filterSyncType;

            const response = await syncAPI.getHistory(params);
            setHistory(response.data.data || []);
        } catch (error) {
            console.error('❌ Error loading history:', error);
        }
    };

    // ========================================================================
    // LOAD STATISTICS
    // ========================================================================
    const loadStatistics = async () => {
        try {
            const response = await syncAPI.getStatistics();
            setStatistics(response.data.data);
        } catch (error) {
            console.error('❌ Error loading statistics:', error);
        }
    };

    // ========================================================================
    // TEST CONNECTION
    // ========================================================================
    const handleTestConnection = async () => {
        setTesting(true);
        setTestResult(null);
        
        try {
            const response = await syncAPI.testConnection();
            setTestResult({
                success: response.data.success,
                message: response.data.message
            });
        } catch (error) {
            setTestResult({
                success: false,
                message: error.response?.data?.message || 'Connection failed',
                error: error.message
            });
        } finally {
            setTesting(false);
        }
    };

    // ========================================================================
    // EXECUTE SYNC
    // ========================================================================
    const handleSync = async () => {
        if (pages < 1 || pages > 10) {
            alert('⚠️ Please enter pages between 1 and 10');
            return;
        }

        if (!confirm(`🔄 Sync ${pages} page(s) from TMDB API?\n\nThis will fetch approximately ${pages * 20} movies.`)) {
            return;
        }

        setSyncing(true);
        setProgress(0);

        try {
            const interval = setInterval(() => {
                setProgress(prev => {
                    if (prev >= 90) {
                        clearInterval(interval);
                        return 90;
                    }
                    return prev + 10;
                });
            }, 500);

            const response = await syncAPI.execute({ pages });
            
            clearInterval(interval);
            setProgress(100);

            const result = response.data.data;
            
            setTimeout(() => {
                alert(
                    `✅ Sync Completed Successfully!\n\n` +
                    `📥 Fetched: ${result.fetched} movies\n` +
                    `➕ Created: ${result.created}\n` +
                    `✏️ Updated: ${result.updated}\n` +
                    `❌ Failed: ${result.failed}\n` +
                    `⏱️ Duration: ${result.duration}s`
                );
                loadAllData();
                setProgress(0);
            }, 500);

        } catch (error) {
            console.error('❌ Sync error:', error);
            alert('❌ Sync failed: ' + (error.response?.data?.message || error.message));
        } finally {
            setSyncing(false);
        }
    };

    // ========================================================================
    // DELETE LOG
    // ========================================================================
    const handleDeleteLog = async (id) => {
        if (!confirm('⚠️ Are you sure you want to delete this sync log?')) {
            return;
        }

        try {
            await syncAPI.deleteSyncLog(id);
            alert('✅ Sync log deleted successfully');
            loadHistory();
        } catch (error) {
            alert('❌ Failed to delete sync log: ' + error.message);
        }
    };

    // ========================================================================
    // CLEAR ALL LOGS
    // ========================================================================
    const handleClearLogs = async () => {
        if (!confirm('⚠️ Are you sure you want to clear ALL sync logs?\n\nThis action cannot be undone!')) {
            return;
        }

        try {
            const response = await syncAPI.clearSyncLogs();
            alert('✅ ' + response.data.message);
            loadHistory();
            loadStatistics();
        } catch (error) {
            alert('❌ Failed to clear logs: ' + error.message);
        }
    };

    // ========================================================================
    // LOADING STATE
    // ========================================================================
    if (loading) {
        return (
            <div className="sync-loading">
                <div className="loading-content">
                    <div className="spinner-border" style={{ width: '4rem', height: '4rem' }}>
                        <span className="visually-hidden">Loading...</span>
                    </div>
                    <p className="mt-4">Loading sync data...</p>
                </div>
            </div>
        );
    }

    // ========================================================================
    // RENDER
    // ========================================================================
    return (
        <div className="sync-page">
            <div className="container-fluid px-4 py-4">
                {/* ============================================================ */}
                {/* HEADER */}
                {/* ============================================================ */}
                <div className="page-header-sync">
                    <div className="row align-items-center">
                        <div className="col-md-6">
                            <h1 className="page-title-sync">
                                <div className="title-icon">
                                    <i className="fas fa-sync-alt"></i>
                                </div>
                                <div>
                                    <div className="title-main">API Synchronization</div>
                                    <div className="title-sub">TMDB Data Integration</div>
                                </div>
                            </h1>
                        </div>
                        <div className="col-md-6 text-md-end">
                            <button 
                                className="btn btn-gradient btn-lg"
                                onClick={loadAllData}
                            >
                                <i className="fas fa-refresh me-2"></i>
                                Refresh Data
                            </button>
                        </div>
                    </div>
                </div>

                {/* ============================================================ */}
                {/* STATISTICS CARDS */}
                {/* ============================================================ */}
                {statistics && (
                    <div className="statistics-grid">
                        <div className="stat-card stat-primary">
                            <div className="stat-icon">
                                <i className="fas fa-sync-alt"></i>
                            </div>
                            <div className="stat-content">
                                <div className="stat-label">Total Syncs</div>
                                <div className="stat-value">{statistics.total_syncs}</div>
                                <div className="stat-footer">All time operations</div>
                            </div>
                        </div>

                        <div className="stat-card stat-success">
                            <div className="stat-icon">
                                <i className="fas fa-check-circle"></i>
                            </div>
                            <div className="stat-content">
                                <div className="stat-label">Success Rate</div>
                                <div className="stat-value">{statistics.average_success_rate}%</div>
                                <div className="stat-footer">Average performance</div>
                            </div>
                        </div>

                        <div className="stat-card stat-info">
                            <div className="stat-icon">
                                <i className="fas fa-database"></i>
                            </div>
                            <div className="stat-content">
                                <div className="stat-label">Total Records</div>
                                <div className="stat-value">{statistics.total_records_fetched?.toLocaleString()}</div>
                                <div className="stat-footer">Movies synchronized</div>
                            </div>
                        </div>

                        <div className="stat-card stat-warning">
                            <div className="stat-icon">
                                <i className="fas fa-clock"></i>
                            </div>
                            <div className="stat-content">
                                <div className="stat-label">Avg Duration</div>
                                <div className="stat-value">{statistics.average_duration}s</div>
                                <div className="stat-footer">Per sync operation</div>
                            </div>
                        </div>
                    </div>
                )}

                {/* ============================================================ */}
                {/* MAIN CONTENT */}
                {/* ============================================================ */}
                <div className="row g-4">
                    {/* LEFT COLUMN - CONTROLS */}
                    <div className="col-xl-5">
                        {/* Test Connection Card */}
                        <div className="sync-card test-card">
                            <div className="sync-card-header">
                                <i className="fas fa-plug me-2"></i>
                                Test API Connection
                            </div>
                            <div className="sync-card-body">
                                <p className="card-description">
                                    Verify connection to TMDB API before executing synchronization.
                                </p>
                                
                                <button
                                    className="btn btn-info btn-lg w-100"
                                    onClick={handleTestConnection}
                                    disabled={testing}
                                >
                                    {testing ? (
                                        <>
                                            <span className="spinner-border spinner-border-sm me-2"></span>
                                            Testing Connection...
                                        </>
                                    ) : (
                                        <>
                                            <i className="fas fa-vial me-2"></i>
                                            Test Connection
                                        </>
                                    )}
                                </button>

                                {testResult && (
                                    <div className={`test-result ${testResult.success ? 'success' : 'error'}`}>
                                        <div className="result-icon">
                                            <i className={`fas fa-${testResult.success ? 'check-circle' : 'times-circle'}`}></i>
                                        </div>
                                        <div className="result-content">
                                            <div className="result-title">{testResult.message}</div>
                                            {testResult.error && (
                                                <div className="result-error">{testResult.error}</div>
                                            )}
                                        </div>
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Sync Execution Card */}
                        <div className="sync-card execute-card">
                            <div className="sync-card-header">
                                <i className="fas fa-download me-2"></i>
                                Execute Synchronization
                            </div>
                            <div className="sync-card-body">
                                <div className="form-group-custom">
                                    <label className="form-label-custom">
                                        <i className="fas fa-layer-group me-2"></i>
                                        Number of Pages
                                    </label>
                                    <div className="input-with-info">
                                        <input
                                            type="number"
                                            className="form-control-custom"
                                            min="1"
                                            max="10"
                                            value={pages}
                                            onChange={(e) => setPages(parseInt(e.target.value) || 1)}
                                            disabled={syncing}
                                        />
                                        <div className="input-info">
                                            <i className="fas fa-info-circle me-1"></i>
                                            Each page ≈ 20 movies
                                        </div>
                                    </div>
                                    <div className="estimated-total">
                                        <i className="fas fa-calculator me-2"></i>
                                        Estimated Total: <strong>~{pages * 20} movies</strong>
                                    </div>
                                </div>

                                {syncing && (
                                    <div className="sync-progress">
                                        <div className="progress-bar-custom">
                                            <div 
                                                className="progress-fill"
                                                style={{ width: `${progress}%` }}
                                            >
                                                <span className="progress-text">{progress}%</span>
                                            </div>
                                        </div>
                                        <div className="progress-status">
                                            <i className="fas fa-spinner fa-spin me-2"></i>
                                            Synchronizing data from TMDB API...
                                        </div>
                                    </div>
                                )}

                                <button
                                    className="btn btn-gradient btn-lg w-100 sync-button"
                                    onClick={handleSync}
                                    disabled={syncing}
                                >
                                    {syncing ? (
                                        <>
                                            <span className="spinner-border spinner-border-sm me-2"></span>
                                            Syncing in Progress...
                                        </>
                                    ) : (
                                        <>
                                            <i className="fas fa-sync-alt me-2"></i>
                                            Start Synchronization
                                        </>
                                    )}
                                </button>

                                <div className="warning-box">
                                    <i className="fas fa-exclamation-triangle me-2"></i>
                                    <div>
                                        <strong>Important:</strong> This will fetch data from TMDB API and update your database.
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Last Sync Info */}
                        {lastSync && (
                            <div className="sync-card last-sync-card">
                                <div className="sync-card-header">
                                    <i className="fas fa-history me-2"></i>
                                    Last Synchronization
                                </div>
                                <div className="sync-card-body">
                                    <div className="last-sync-info">
                                        <div className="info-row">
                                            <span className="info-label">Status</span>
                                            <span className={`status-badge status-${lastSync.status}`}>
                                                <i className={`fas ${lastSync.status_icon} me-1`}></i>
                                                {lastSync.status.toUpperCase()}
                                            </span>
                                        </div>
                                        <div className="info-row">
                                            <span className="info-label">Type</span>
                                            <span className={`type-badge type-${lastSync.sync_type}`}>
                                                {lastSync.sync_type}
                                            </span>
                                        </div>
                                        <div className="info-row">
                                            <span className="info-label">Time</span>
                                            <span className="info-value">{lastSync.time_ago}</span>
                                        </div>
                                        <div className="info-row">
                                            <span className="info-label">Duration</span>
                                            <span className="info-value">{lastSync.formatted_duration}</span>
                                        </div>
                                    </div>

                                    <div className="stats-grid">
                                        <div className="stat-item stat-fetched">
                                            <div className="stat-number">{lastSync.records_fetched}</div>
                                            <div className="stat-text">Fetched</div>
                                        </div>
                                        <div className="stat-item stat-created">
                                            <div className="stat-number">{lastSync.records_created}</div>
                                            <div className="stat-text">Created</div>
                                        </div>
                                        <div className="stat-item stat-updated">
                                            <div className="stat-number">{lastSync.records_updated}</div>
                                            <div className="stat-text">Updated</div>
                                        </div>
                                        <div className="stat-item stat-failed">
                                            <div className="stat-number">{lastSync.records_failed}</div>
                                            <div className="stat-text">Failed</div>
                                        </div>
                                    </div>

                                    <div className="success-rate-section">
                                        <div className="success-rate-label">
                                            <span>Success Rate</span>
                                            <strong>{lastSync.success_rate}%</strong>
                                        </div>
                                        <div className="success-rate-bar">
                                            <div 
                                                className={`success-rate-fill rate-${lastSync.success_rate >= 90 ? 'high' : lastSync.success_rate >= 70 ? 'medium' : 'low'}`}
                                                style={{ width: `${lastSync.success_rate}%` }}
                                            ></div>
                                        </div>
                                    </div>

                                    {lastSync.message && (
                                        <div className="sync-message">
                                            <i className="fas fa-comment-dots me-2"></i>
                                            {lastSync.message}
                                        </div>
                                    )}
                                </div>
                            </div>
                        )}
                    </div>

                    {/* RIGHT COLUMN - HISTORY */}
                    <div className="col-xl-7">
                        <div className="sync-card history-card">
                            <div className="sync-card-header">
                                <div className="header-content">
                                    <i className="fas fa-history me-2"></i>
                                    Synchronization History
                                </div>
                                <button 
                                    className="btn btn-danger btn-sm"
                                    onClick={handleClearLogs}
                                    disabled={history.length === 0}
                                >
                                    <i className="fas fa-trash-alt me-1"></i>
                                    Clear All
                                </button>
                            </div>
                            
                            {/* Filters */}
                            <div className="filters-section">
                                <div className="row g-3">
                                    <div className="col-md-6">
                                        <div className="filter-group">
                                            <label className="filter-label">
                                                <i className="fas fa-filter me-1"></i>
                                                Filter by Status
                                            </label>
                                            <select 
                                                className="filter-select"
                                                value={filterStatus}
                                                onChange={(e) => {
                                                    setFilterStatus(e.target.value);
                                                    setTimeout(loadHistory, 100);
                                                }}
                                            >
                                                <option value="">All Status</option>
                                                <option value="success">✅ Success</option>
                                                <option value="partial">⚠️ Partial</option>
                                                <option value="failed">❌ Failed</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div className="col-md-6">
                                        <div className="filter-group">
                                            <label className="filter-label">
                                                <i className="fas fa-tag me-1"></i>
                                                Filter by Type
                                            </label>
                                            <select 
                                                className="filter-select"
                                                value={filterSyncType}
                                                onChange={(e) => {
                                                    setFilterSyncType(e.target.value);
                                                    setTimeout(loadHistory, 100);
                                                }}
                                            >
                                                <option value="">All Types</option>
                                                <option value="manual">👤 Manual</option>
                                                <option value="scheduled">⏰ Scheduled</option>
                                                <option value="auto">🤖 Auto</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* History List */}
                            <div className="history-list">
                                {history.length > 0 ? (
                                    history.map((item) => (
                                        <div key={item.id} className="history-item">
                                            <div className="history-header">
                                                <div className="history-badges">
                                                    <span className={`status-badge status-${item.status}`}>
                                                        <i className={`fas ${item.status_icon} me-1`}></i>
                                                        {item.status}
                                                    </span>
                                                    <span className={`type-badge type-${item.sync_type}`}>
                                                        {item.sync_type}
                                                    </span>
                                                    <span className="time-badge">
                                                        <i className="fas fa-clock me-1"></i>
                                                        {item.time_ago}
                                                    </span>
                                                </div>
                                                <button
                                                    className="btn-delete"
                                                    onClick={() => handleDeleteLog(item.id)}
                                                    title="Delete log"
                                                >
                                                    <i className="fas fa-trash"></i>
                                                </button>
                                            </div>

                                            <div className="history-message">
                                                {item.message}
                                            </div>

                                            <div className="history-stats">
                                                <div className="stat-mini">
                                                    <i className="fas fa-download"></i>
                                                    <span>Fetched: <strong>{item.records_fetched}</strong></span>
                                                </div>
                                                <div className="stat-mini">
                                                    <i className="fas fa-plus"></i>
                                                    <span>Created: <strong>{item.records_created}</strong></span>
                                                </div>
                                                <div className="stat-mini">
                                                    <i className="fas fa-edit"></i>
                                                    <span>Updated: <strong>{item.records_updated}</strong></span>
                                                </div>
                                                {item.records_failed > 0 && (
                                                    <div className="stat-mini stat-failed">
                                                        <i className="fas fa-times"></i>
                                                        <span>Failed: <strong>{item.records_failed}</strong></span>
                                                    </div>
                                                )}
                                            </div>

                                            <div className="history-progress">
                                                <div className="progress-info">
                                                    <span>Success Rate: {item.success_rate}%</span>
                                                    <span>Duration: {item.formatted_duration}</span>
                                                </div>
                                                <div className="progress-bar-mini">
                                                    <div 
                                                        className={`progress-fill-mini rate-${item.success_rate >= 90 ? 'high' : item.success_rate >= 70 ? 'medium' : 'low'}`}
                                                        style={{ width: `${item.success_rate}%` }}
                                                    ></div>
                                                </div>
                                            </div>
                                        </div>
                                    ))
                                ) : (
                                    <div className="empty-history">
                                        <i className="fas fa-inbox"></i>
                                        <h4>No Sync History</h4>
                                        <p>Start your first synchronization to see history here</p>
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* ============================================================ */}
            {/* CUSTOM STYLES */}
            {/* ============================================================ */}
            <style>{`
                /* Gradient Colors: #977DFF, #0033FF, #0600AB, #00033D */
                
                .sync-page {
                    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                    min-height: calc(100vh - 160px);
                    padding: 0;
                }

                /* Loading State */
                .sync-loading {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    min-height: 80vh;
                    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                }

                .loading-content {
                    text-align: center;
                }

                .loading-content .spinner-border {
                    color: #977DFF;
                    border-width: 4px;
                }

                .loading-content p {
                    color: #0600AB;
                    font-weight: 600;
                    font-size: 1.1rem;
                }

                /* Page Header */
                .page-header-sync {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 50%, #0600AB 100%);
                    padding: 2.5rem;
                    border-radius: 20px;
                    margin-bottom: 2rem;
                    box-shadow: 0 8px 30px rgba(151, 125, 255, 0.3);
                }

                .page-title-sync {
                    display: flex;
                    align-items: center;
                    gap: 1.5rem;
                    margin: 0;
                    color: white;
                }

                .title-icon {
                    width: 70px;
                    height: 70px;
                    background: rgba(255, 255, 255, 0.2);
                    border-radius: 15px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 2rem;
                    backdrop-filter: blur(10px);
                }

                .title-main {
                    font-size: 2rem;
                    font-weight: 700;
                    line-height: 1.2;
                }

                .title-sub {
                    font-size: 1rem;
                    opacity: 0.9;
                    font-weight: 400;
                }

                /* Statistics Grid */
                .statistics-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                    gap: 1.5rem;
                    margin-bottom: 2rem;
                }

                .stat-card {
                    background: white;
                    border-radius: 15px;
                    padding: 2rem;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                    display: flex;
                    align-items: center;
                    gap: 1.5rem;
                    transition: all 0.3s ease;
                    position: relative;
                    overflow: hidden;
                }

                .stat-card::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    height: 4px;
                }

                .stat-card.stat-primary::before {
                    background: linear-gradient(90deg, #977DFF 0%, #0033FF 100%);
                }

                .stat-card.stat-success::before {
                    background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
                }

                .stat-card.stat-info::before {
                    background: linear-gradient(90deg, #17a2b8 0%, #138496 100%);
                }

                .stat-card.stat-warning::before {
                    background: linear-gradient(90deg, #ffc107 0%, #ff9800 100%);
                }

                .stat-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 8px 30px rgba(151, 125, 255, 0.2);
                }

                .stat-icon {
                    width: 70px;
                    height: 70px;
                    border-radius: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 2rem;
                    flex-shrink: 0;
                }

                .stat-primary .stat-icon {
                    background: linear-gradient(135deg, #f0ebff 0%, #e6f2ff 100%);
                    color: #977DFF;
                }

                .stat-success .stat-icon {
                    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
                    color: #28a745;
                }

                .stat-info .stat-icon {
                    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
                    color: #17a2b8;
                }

                .stat-warning .stat-icon {
                    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
                    color: #ff9800;
                }

                .stat-content {
                    flex: 1;
                }

                .stat-label {
                    font-size: 0.85rem;
                    color: #6c757d;
                    font-weight: 600;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    margin-bottom: 0.5rem;
                }

                .stat-value {
                    font-size: 2.5rem;
                    font-weight: 700;
                    color: #0600AB;
                    line-height: 1;
                    margin-bottom: 0.5rem;
                }

                .stat-footer {
                    font-size: 0.8rem;
                    color: #6c757d;
                }

                /* Sync Cards */
                .sync-card {
                    background: white;
                    border-radius: 15px;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                    margin-bottom: 1.5rem;
                    overflow: hidden;
                }

                .sync-card-header {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 100%);
                    color: white;
                    padding: 1.5rem 2rem;
                    font-weight: 700;
                    font-size: 1.1rem;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                }

                .sync-card-body {
                    padding: 2rem;
                }

                .card-description {
                    color: #6c757d;
                    margin-bottom: 1.5rem;
                    line-height: 1.6;
                }

                /* Test Result */
                .test-result {
                    margin-top: 1.5rem;
                    padding: 1.5rem;
                    border-radius: 12px;
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                }

                .test-result.success {
                    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
                    border-left: 4px solid #28a745;
                }

                .test-result.error {
                    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
                    border-left: 4px solid #dc3545;
                }

                .result-icon {
                    font-size: 2.5rem;
                }

                .test-result.success .result-icon {
                    color: #28a745;
                }

                .test-result.error .result-icon {
                    color: #dc3545;
                }

                .result-content {
                    flex: 1;
                }

                .result-title {
                    font-weight: 700;
                    font-size: 1.1rem;
                    margin-bottom: 0.25rem;
                }

                .test-result.success .result-title {
                    color: #155724;
                }

                .test-result.error .result-title {
                    color: #721c24;
                }

                .result-error {
                    font-size: 0.9rem;
                    opacity: 0.8;
                }

                /* Form Custom */
                .form-group-custom {
                    margin-bottom: 2rem;
                }

                .form-label-custom {
                    font-weight: 700;
                    color: #0600AB;
                    margin-bottom: 1rem;
                    font-size: 1rem;
                    display: flex;
                    align-items: center;
                }

                .form-control-custom {
                    width: 100%;
                    padding: 1rem 1.5rem;
                    border: 3px solid #e2e8f0;
                    border-radius: 12px;
                    font-size: 1.5rem;
                    font-weight: 700;
                    text-align: center;
                    color: #0600AB;
                    transition: all 0.3s ease;
                }

                .form-control-custom:focus {
                    outline: none;
                    border-color: #977DFF;
                    box-shadow: 0 0 0 0.25rem rgba(151, 125, 255, 0.15);
                }

                .input-info {
                    text-align: center;
                    color: #6c757d;
                    font-size: 0.9rem;
                    margin-top: 0.5rem;
                }

                .estimated-total {
                    background: linear-gradient(135deg, #f0ebff 0%, #e6f2ff 100%);
                    padding: 1rem 1.5rem;
                    border-radius: 10px;
                    text-align: center;
                    color: #0600AB;
                    font-size: 1.1rem;
                    margin-top: 1rem;
                }

                /* Sync Progress */
                .sync-progress {
                    margin-bottom: 1.5rem;
                }

                .progress-bar-custom {
                    height: 50px;
                    background: #e9ecef;
                    border-radius: 12px;
                    overflow: hidden;
                    position: relative;
                }

                .progress-fill {
                    height: 100%;
                    background: linear-gradient(90deg, #977DFF 0%, #0033FF 100%);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: width 0.3s ease;
                    position: relative;
                    overflow: hidden;
                }

                .progress-fill::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
                    animation: shimmer 2s infinite;
                }

                @keyframes shimmer {
                    0% { transform: translateX(-100%); }
                    100% { transform: translateX(100%); }
                }

                .progress-text {
                    color: white;
                    font-weight: 700;
                    font-size: 1.2rem;
                    position: relative;
                    z-index: 1;
                }

                .progress-status {
                    text-align: center;
                    color: #0600AB;
                    font-weight: 600;
                    margin-top: 1rem;
                }

                /* Sync Button */
                .sync-button {
                    font-size: 1.1rem;
                    padding: 1rem 2rem;
                    margin-bottom: 1.5rem;
                }

                /* Warning Box */
                .warning-box {
                    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
                    border-left: 4px solid #ffc107;
                    padding: 1rem 1.5rem;
                    border-radius: 10px;
                    color: #856404;
                    display: flex;
                    align-items: flex-start;
                    gap: 0.75rem;
                }

                .warning-box i {
                    font-size: 1.5rem;
                    margin-top: 0.25rem;
                }

                /* Last Sync Info */
                .last-sync-info {
                    margin-bottom: 1.5rem;
                }

                .info-row {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 0.75rem 0;
                    border-bottom: 1px solid #e9ecef;
                }

                .info-row:last-child {
                    border-bottom: none;
                }

                .info-label {
                    color: #6c757d;
                    font-weight: 600;
                }

                .info-value {
                    color: #0600AB;
                    font-weight: 700;
                }

                /* Status & Type Badges */
                .status-badge,
                .type-badge {
                    padding: 0.4rem 0.8rem;
                    border-radius: 8px;
                    font-weight: 600;
                    font-size: 0.85rem;
                    display: inline-flex;
                    align-items: center;
                }

                .status-badge.status-success {
                    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
                    color: #155724;
                }

                .status-badge.status-partial {
                    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
                    color: #856404;
                }

                .status-badge.status-failed {
                    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
                    color: #721c24;
                }

                .type-badge.type-manual {
                    background: linear-gradient(135deg, #f0ebff 0%, #e6f2ff 100%);
                    color: #0600AB;
                }

                .type-badge.type-scheduled {
                    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
                    color: #0c5460;
                }

                .type-badge.type-auto {
                    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
                    color: #155724;
                }

                /* Stats Grid */
                .stats-grid {
                    display: grid;
                    grid-template-columns: repeat(4, 1fr);
                    gap: 1rem;
                    margin-bottom: 1.5rem;
                    padding: 1.5rem;
                    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                    border-radius: 12px;
                }

                .stat-item {
                    text-align: center;
                }

                .stat-number {
                    font-size: 2rem;
                    font-weight: 700;
                    line-height: 1;
                    margin-bottom: 0.5rem;
                }

                .stat-fetched .stat-number { color: #977DFF; }
                .stat-created .stat-number { color: #28a745; }
                .stat-updated .stat-number { color: #17a2b8; }
                .stat-failed .stat-number { color: #dc3545; }

                .stat-text {
                    font-size: 0.8rem;
                    color: #6c757d;
                    font-weight: 600;
                    text-transform: uppercase;
                }

                /* Success Rate */
                .success-rate-section {
                    margin-bottom: 1.5rem;
                }

                .success-rate-label {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 0.75rem;
                    color: #0600AB;
                    font-weight: 600;
                }

                .success-rate-bar {
                    height: 30px;
                    background: #e9ecef;
                    border-radius: 10px;
                    overflow: hidden;
                }

                .success-rate-fill {
                    height: 100%;
                    transition: width 0.5s ease;
                }

                .success-rate-fill.rate-high {
                    background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
                }

                .success-rate-fill.rate-medium {
                    background: linear-gradient(90deg, #ffc107 0%, #ff9800 100%);
                }

                .success-rate-fill.rate-low {
                    background: linear-gradient(90deg, #dc3545 0%, #c82333 100%);
                }

                /* Sync Message */
                .sync-message {
                    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
                    padding: 1rem 1.5rem;
                    border-radius: 10px;
                    color: #0c5460;
                    border-left: 4px solid #17a2b8;
                }

                /* Filters Section */
                .filters-section {
                    padding: 1.5rem 2rem;
                    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                    border-bottom: 2px solid #e2e8f0;
                }

                .filter-group {
                    display: flex;
                    flex-direction: column;
                    gap: 0.5rem;
                }

                .filter-label {
                    font-weight: 600;
                    color: #0600AB;
                    font-size: 0.9rem;
                }

                .filter-select {
                    padding: 0.75rem 1rem;
                    border: 2px solid #e2e8f0;
                    border-radius: 10px;
                    font-weight: 600;
                    color: #0600AB;
                    background: white;
                    transition: all 0.3s ease;
                }

                .filter-select:focus {
                    outline: none;
                    border-color: #977DFF;
                    box-shadow: 0 0 0 0.25rem rgba(151, 125, 255, 0.15);
                }

                /* History List */
                .history-list {
                    max-height: 700px;
                    overflow-y: auto;
                    padding: 1rem;
                }

                .history-list::-webkit-scrollbar {
                    width: 8px;
                }

                .history-list::-webkit-scrollbar-track {
                    background: #f1f1f1;
                    border-radius: 10px;
                }

                .history-list::-webkit-scrollbar-thumb {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 100%);
                    border-radius: 10px;
                }

                /* History Item */
                .history-item {
                    background: white;
                    border: 2px solid #e9ecef;
                    border-radius: 12px;
                    padding: 1.5rem;
                    margin-bottom: 1rem;
                    transition: all 0.3s ease;
                }

                .history-item:hover {
                    border-color: #977DFF;
                    box-shadow: 0 4px 12px rgba(151, 125, 255, 0.15);
                    transform: translateX(5px);
                }

                .history-header {
                    display: flex;
                    justify-content: space-between;
                    align-items: flex-start;
                    margin-bottom: 1rem;
                }

                .history-badges {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 0.5rem;
                }

                .time-badge {
                    background: #f8f9fa;
                    color: #6c757d;
                    padding: 0.4rem 0.8rem;
                    border-radius: 8px;
                    font-size: 0.85rem;
                    font-weight: 600;
                }

                .btn-delete {
                    background: transparent;
                    border: 2px solid #dc3545;
                    color: #dc3545;
                    width: 36px;
                    height: 36px;
                    border-radius: 8px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    cursor: pointer;
                    transition: all 0.3s ease;
                }

                .btn-delete:hover {
                    background: #dc3545;
                    color: white;
                }

                .history-message {
                    color: #6c757d;
                    margin-bottom: 1rem;
                    line-height: 1.5;
                }

                .history-stats {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 1rem;
                    margin-bottom: 1rem;
                    padding-bottom: 1rem;
                    border-bottom: 1px solid #e9ecef;
                }

                .stat-mini {
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    font-size: 0.9rem;
                }

                .stat-mini i {
                    width: 20px;
                    text-align: center;
                }

                .stat-mini:nth-child(1) i { color: #977DFF; }
                .stat-mini:nth-child(2) i { color: #28a745; }
                .stat-mini:nth-child(3) i { color: #17a2b8; }
                .stat-mini.stat-failed i { color: #dc3545; }

                .history-progress {
                    margin-top: 1rem;
                }

                .progress-info {
                    display: flex;
                    justify-content: space-between;
                    font-size: 0.85rem;
                    color: #6c757d;
                    margin-bottom: 0.5rem;
                    font-weight: 600;
                }

                .progress-bar-mini {
                    height: 10px;
                    background: #e9ecef;
                    border-radius: 10px;
                    overflow: hidden;
                }

                .progress-fill-mini {
                    height: 100%;
                    transition: width 0.5s ease;
                }

                .progress-fill-mini.rate-high {
                    background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
                }

                .progress-fill-mini.rate-medium {
                    background: linear-gradient(90deg, #ffc107 0%, #ff9800 100%);
                }

                .progress-fill-mini.rate-low {
                    background: linear-gradient(90deg, #dc3545 0%, #c82333 100%);
                }

                /* Empty History */
                .empty-history {
                    text-align: center;
                    padding: 4rem 2rem;
                    color: #6c757d;
                }

                .empty-history i {
                    font-size: 5rem;
                    color: #977DFF;
                    margin-bottom: 1.5rem;
                    opacity: 0.5;
                }

                .empty-history h4 {
                    color: #0600AB;
                    margin-bottom: 0.5rem;
                }

                .empty-history p {
                    color: #6c757d;
                }

                /* Responsive */
                @media (max-width: 991.98px) {
                    .page-header-sync {
                        padding: 2rem;
                    }

                    .title-main {
                        font-size: 1.5rem;
                    }

                    .title-icon {
                        width: 60px;
                        height: 60px;
                        font-size: 1.5rem;
                    }

                    .statistics-grid {
                        grid-template-columns: repeat(2, 1fr);
                    }

                    .stats-grid {
                        grid-template-columns: repeat(2, 1fr);
                    }

                    .sync-card-body {
                        padding: 1.5rem;
                    }

                    .history-list {
                        max-height: 500px;
                    }
                }

                @media (max-width: 575.98px) {
                    .statistics-grid {
                        grid-template-columns: 1fr;
                    }

                    .stats-grid {
                        grid-template-columns: repeat(2, 1fr);
                        gap: 0.75rem;
                    }

                    .stat-number {
                        font-size: 1.5rem;
                    }

                    .page-title-sync {
                        flex-direction: column;
                        text-align: center;
                    }

                    .history-stats {
                        flex-direction: column;
                        gap: 0.5rem;
                    }

                    .filters-section {
                        padding: 1rem;
                    }
                }

                /* Animations */
                @keyframes fadeIn {
                    from {
                        opacity: 0;
                        transform: translateY(20px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .sync-card,
                .stat-card,
                .history-item {
                    animation: fadeIn 0.3s ease;
                }

                /* Print Styles */
                @media print {
                    .btn,
                    .filters-section,
                    .btn-delete {
                        display: none !important;
                    }

                    .sync-page {
                        background: white !important;
                    }

                    .sync-card {
                        box-shadow: none !important;
                        border: 1px solid #ddd !important;
                    }
                }
            `}</style>
        </div>
    );
}

export default Sync;

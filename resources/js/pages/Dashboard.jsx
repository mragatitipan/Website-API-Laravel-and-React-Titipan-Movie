import React, { useEffect, useState } from 'react';
import { dashboardAPI } from '../services/api';

function Dashboard() {
    // ========================================================================
    // STATES
    // ========================================================================
    const [stats, setStats] = useState(null);
    const [moviesByGenre, setMoviesByGenre] = useState([]);
    const [moviesByDate, setMoviesByDate] = useState([]);
    const [moviesByYear, setMoviesByYear] = useState([]);
    const [topRated, setTopRated] = useState([]);
    const [mostPopular, setMostPopular] = useState([]);
    const [recentMovies, setRecentMovies] = useState([]);
    const [highestGrossing, setHighestGrossing] = useState([]);
    const [ratingDistribution, setRatingDistribution] = useState([]);
    const [loading, setLoading] = useState(true);
    const [filterMonths, setFilterMonths] = useState(12);

    // ========================================================================
    // GRADIENT COLORS
    // ========================================================================
    const gradientColors = ['#977DFF', '#0033FF', '#0600AB', '#00033D'];
    const extendedColors = [
        '#977DFF', '#0033FF', '#0600AB', '#00033D',
        '#FF6B9D', '#C44569', '#FFA07A', '#FF6347',
        '#20E3B2', '#29BB89', '#4ECDC4', '#44A08D',
    ];

    // ========================================================================
    // LOAD DATA
    // ========================================================================
    useEffect(() => {
        loadDashboardData();
    }, [filterMonths]);

    const loadDashboardData = async () => {
        setLoading(true);
        try {
            const [
                statsRes,
                genreRes,
                dateRes,
                yearRes,
                topRatedRes,
                popularRes,
                recentRes,
                grossingRes,
                ratingDistRes
            ] = await Promise.all([
                dashboardAPI.getStatistics(),
                dashboardAPI.getMoviesByGenre(),
                dashboardAPI.getMoviesByDate(filterMonths),
                dashboardAPI.getMoviesByYear(5),
                dashboardAPI.getTopRated(10),
                dashboardAPI.getMostPopular(10),
                dashboardAPI.getRecentMovies(10),
                dashboardAPI.getHighestGrossing(10),
                dashboardAPI.getRatingDistribution()
            ]);

            setStats(statsRes.data.data);
            setMoviesByGenre(genreRes.data.data);
            setMoviesByDate(dateRes.data.data);
            setMoviesByYear(yearRes.data.data);
            setTopRated(topRatedRes.data.data);
            setMostPopular(popularRes.data.data);
            setRecentMovies(recentRes.data.data);
            setHighestGrossing(grossingRes.data.data);
            setRatingDistribution(ratingDistRes.data.data);
        } catch (error) {
            console.error('❌ Error loading dashboard data:', error);
        } finally {
            setLoading(false);
        }
    };

    // ========================================================================
    // GET TOP CATEGORIES (LOGIC DI FRONTEND)
    // ========================================================================
    const getTopCategories = () => {
        if (!moviesByGenre || moviesByGenre.length === 0) return [];
        
        // Sort by count and take top 5
        return [...moviesByGenre]
            .sort((a, b) => b.count - a.count)
            .slice(0, 5);
    };

    // ========================================================================
    // GET LATEST DATA (LOGIC DI FRONTEND)
    // ========================================================================
    const getLatestData = () => {
        if (!moviesByDate || moviesByDate.length === 0) return [];
        
        // Take last 7 entries (most recent)
        return [...moviesByDate].slice(-7);
    };

    // ========================================================================
    // PIE CHART COMPONENT (MODERN)
    // ========================================================================
    const ModernPieChart = ({ data, title }) => {
        if (!data || data.length === 0) {
            return (
                <div className="no-data-state">
                    <i className="fas fa-chart-pie"></i>
                    <p>No data available</p>
                </div>
            );
        }

        const total = data.reduce((sum, item) => sum + item.count, 0);
        const displayData = data.slice(0, 10);

        return (
            <div className="modern-chart-container">
                <h6 className="chart-title-modern">{title}</h6>
                
                {/* Pie Chart */}
                <div className="pie-chart-wrapper-modern">
                    <div 
                        className="pie-chart-modern"
                        style={{ 
                            background: `conic-gradient(${
                                displayData.map((item, index) => {
                                    const percentage = (item.count / total) * 100;
                                    const prevPercentage = displayData.slice(0, index).reduce((sum, i) => 
                                        sum + (i.count / total) * 100, 0);
                                    return `${extendedColors[index % extendedColors.length]} ${prevPercentage}% ${prevPercentage + percentage}%`;
                                }).join(', ')
                            })`
                        }}
                    >
                        <div className="pie-center-modern">
                            <div className="pie-total">{total}</div>
                            <div className="pie-label">Total</div>
                        </div>
                    </div>
                </div>

                {/* Legend */}
                <div className="pie-legend-modern">
                    {displayData.map((item, index) => (
                        <div key={index} className="legend-item-modern">
                            <div 
                                className="legend-color-modern"
                                style={{ backgroundColor: extendedColors[index % extendedColors.length] }}
                            ></div>
                            <div className="legend-text-modern">
                                <span className="legend-name">{item.genre}</span>
                                <span className="legend-value">{item.count} ({item.percentage}%)</span>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        );
    };

    // ========================================================================
    // COLUMN CHART COMPONENT (MODERN)
    // ========================================================================
    const ModernColumnChart = ({ data, title, labelKey, valueKey }) => {
        if (!data || data.length === 0) {
            return (
                <div className="no-data-state">
                    <i className="fas fa-chart-bar"></i>
                    <p>No data available</p>
                </div>
            );
        }

        const maxValue = Math.max(...data.map(item => item[valueKey] || 0));

        return (
            <div className="modern-chart-container">
                <h6 className="chart-title-modern">{title}</h6>
                
                <div className="column-chart-modern">
                    {data.map((item, index) => {
                        const height = maxValue > 0 ? (item[valueKey] / maxValue) * 100 : 0;
                        const colorIndex = index % gradientColors.length;
                        
                        return (
                            <div key={index} className="column-item-modern">
                                <div className="column-value">{item[valueKey]}</div>
                                <div 
                                    className="column-bar-modern"
                                    style={{ 
                                        height: `${height}%`,
                                        background: `linear-gradient(180deg, ${gradientColors[colorIndex]} 0%, ${gradientColors[(colorIndex + 1) % gradientColors.length]} 100%)`
                                    }}
                                    title={`${item[labelKey]}: ${item[valueKey]}`}
                                >
                                    <div className="column-bar-shine"></div>
                                </div>
                                <div className="column-label">{item[labelKey]}</div>
                            </div>
                        );
                    })}
                </div>
            </div>
        );
    };

    // ========================================================================
    // LINE CHART COMPONENT (MODERN)
    // ========================================================================
    const ModernLineChart = ({ data, title }) => {
        if (!data || data.length === 0) {
            return (
                <div className="no-data-state">
                    <i className="fas fa-chart-line"></i>
                    <p>No data available</p>
                </div>
            );
        }

        const maxValue = Math.max(...data.map(item => item.count || 0));

        return (
            <div className="modern-chart-container">
                <h6 className="chart-title-modern">{title}</h6>
                
                <div className="line-chart-modern">
                    <svg width="100%" height="100%" viewBox="0 0 100 100" preserveAspectRatio="none">
                        {/* Grid lines */}
                        {[0, 25, 50, 75, 100].map((percent, i) => (
                            <line 
                                key={i}
                                x1="0" 
                                y1={percent} 
                                x2="100" 
                                y2={percent}
                                stroke="rgba(151, 125, 255, 0.1)"
                                strokeWidth="0.5"
                                vectorEffect="non-scaling-stroke"
                            />
                        ))}
                        
                        {/* Area gradient */}
                        <defs>
                            <linearGradient id="lineGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" stopColor="#977DFF" stopOpacity="0.4"/>
                                <stop offset="100%" stopColor="#977DFF" stopOpacity="0"/>
                            </linearGradient>
                        </defs>
                        
                        {/* Area fill */}
                        <polygon
                            fill="url(#lineGradient)"
                            points={`
                                0,100 
                                ${data.map((item, index) => {
                                    const x = (index / (data.length - 1)) * 100;
                                    const y = 100 - ((item.count / maxValue) * 100);
                                    return `${x},${y}`;
                                }).join(' ')} 
                                100,100
                            `}
                        />
                        
                        {/* Line path */}
                        <polyline
                            fill="none"
                            stroke="#977DFF"
                            strokeWidth="3"
                            vectorEffect="non-scaling-stroke"
                            points={data.map((item, index) => {
                                const x = (index / (data.length - 1)) * 100;
                                const y = 100 - ((item.count / maxValue) * 100);
                                return `${x},${y}`;
                            }).join(' ')}
                        />
                        
                        {/* Data points */}
                        {data.map((item, index) => {
                            const x = (index / (data.length - 1)) * 100;
                            const y = 100 - ((item.count / maxValue) * 100);
                            return (
                                <circle
                                    key={index}
                                    cx={x}
                                    cy={y}
                                    r="2.5"
                                    fill="#977DFF"
                                    stroke="#fff"
                                    strokeWidth="2"
                                    vectorEffect="non-scaling-stroke"
                                >
                                    <title>{item.range}: {item.count}</title>
                                </circle>
                            );
                        })}
                    </svg>
                    
                    {/* X-axis labels */}
                    <div className="line-chart-labels">
                        {data.map((item, index) => (
                            <div key={index} className="line-label">
                                {item.range}
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        );
    };

    // ========================================================================
    // LOADING STATE
    // ========================================================================
    if (loading) {
        return (
            <div className="loading-container-modern">
                <div className="loading-content">
                    <div className="spinner-modern">
                        <div className="spinner-ring"></div>
                        <div className="spinner-ring"></div>
                        <div className="spinner-ring"></div>
                    </div>
                    <p className="loading-text">Loading dashboard...</p>
                </div>
            </div>
        );
    }

    // ========================================================================
    // RENDER
    // ========================================================================
    return (
        <div className="dashboard-modern">
            {/* ============================================================ */}
            {/* HEADER */}
            {/* ============================================================ */}
            <div className="dashboard-header-modern">
                <div className="header-content">
                    <div className="header-title-section">
                        <div className="title-icon-modern">
                            <i className="fas fa-chart-line"></i>
                        </div>
                        <div>
                            <h1 className="page-title">Dashboard</h1>
                            <p className="page-subtitle">Analytics & Statistics Overview</p>
                        </div>
                    </div>
                    
                    <div className="header-actions">
                        <select 
                            className="filter-select-modern" 
                            value={filterMonths}
                            onChange={(e) => setFilterMonths(parseInt(e.target.value))}
                        >
                            <option value={1}>Last Month</option>
                            <option value={3}>Last 3 Months</option>
                            <option value={6}>Last 6 Months</option>
                            <option value={12}>Last 12 Months</option>
                            <option value={24}>Last 2 Years</option>
                        </select>
                        <button className="btn-refresh-modern" onClick={loadDashboardData}>
                            <i className="fas fa-sync-alt"></i>
                            Refresh
                        </button>
                    </div>
                </div>
            </div>

            <div className="dashboard-content">
                {/* ============================================================ */}
                {/* STATISTICS CARDS */}
                {/* ============================================================ */}
                <div className="stats-grid-modern">
                    {/* Total Movies */}
                    <div className="stat-card-modern stat-gradient-1">
                        <div className="stat-icon-wrapper">
                            <i className="fas fa-film"></i>
                        </div>
                        <div className="stat-content">
                            <div className="stat-label">Total Movies</div>
                            <div className="stat-value">{stats?.total_movies?.toLocaleString() || 0}</div>
                            <div className="stat-footer">
                                <i className="fas fa-calendar"></i>
                                This Year: {stats?.movies_this_year || 0}
                            </div>
                        </div>
                    </div>

                    {/* Total Genres */}
                    <div className="stat-card-modern stat-gradient-2">
                        <div className="stat-icon-wrapper">
                            <i className="fas fa-layer-group"></i>
                        </div>
                        <div className="stat-content">
                            <div className="stat-label">Total Genres</div>
                            <div className="stat-value">{stats?.total_genres || 0}</div>
                            <div className="stat-footer">
                                <i className="fas fa-star"></i>
                                Avg Rating: {stats?.average_rating || 0}
                            </div>
                        </div>
                    </div>

                    {/* Average Rating */}
                    <div className="stat-card-modern stat-gradient-3">
                        <div className="stat-icon-wrapper">
                            <i className="fas fa-star"></i>
                        </div>
                        <div className="stat-content">
                            <div className="stat-label">Average Rating</div>
                            <div className="stat-value">{stats?.average_rating || 0}</div>
                            <div className="stat-footer">
                                <i className="fas fa-vote-yea"></i>
                                Total Votes: {stats?.total_votes?.toLocaleString() || 0}
                            </div>
                        </div>
                    </div>

                    {/* This Month */}
                    <div className="stat-card-modern stat-gradient-4">
                        <div className="stat-icon-wrapper">
                            <i className="fas fa-calendar-day"></i>
                        </div>
                        <div className="stat-content">
                            <div className="stat-label">This Month</div>
                            <div className="stat-value">{stats?.movies_this_month || 0}</div>
                            <div className="stat-footer">
                                <i className="fas fa-clock"></i>
                                Recent additions
                            </div>
                        </div>
                    </div>
                </div>

                {/* ============================================================ */}
                {/* TOP CATEGORIES & LATEST DATA */}
                {/* ============================================================ */}
                <div className="row g-4 mb-4">
                    {/* Top 5 Categories */}
                    <div className="col-xl-6">
                        <div className="card-modern">
                            <div className="card-header-modern gradient-header-1">
                                <i className="fas fa-trophy me-2"></i>
                                Top 5 Categories
                            </div>
                            <div className="card-body-modern">
                                <div className="top-categories-list">
                                    {getTopCategories().map((category, index) => (
                                        <div key={index} className="category-item-modern">
                                            <div className="category-rank" style={{
                                                background: `linear-gradient(135deg, ${gradientColors[index % gradientColors.length]} 0%, ${gradientColors[(index + 1) % gradientColors.length]} 100%)`
                                            }}>
                                                #{index + 1}
                                            </div>
                                            <div className="category-info">
                                                <div className="category-name">{category.genre}</div>
                                                <div className="category-stats">
                                                    {category.count} movies • {category.percentage}%
                                                </div>
                                            </div>
                                            <div className="category-bar-wrapper">
                                                <div 
                                                    className="category-bar"
                                                    style={{
                                                        width: `${category.percentage}%`,
                                                        background: `linear-gradient(90deg, ${gradientColors[index % gradientColors.length]} 0%, ${gradientColors[(index + 1) % gradientColors.length]} 100%)`
                                                    }}
                                                ></div>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Latest 7 Days Data */}
                    <div className="col-xl-6">
                        <div className="card-modern">
                            <div className="card-header-modern gradient-header-2">
                                <i className="fas fa-clock me-2"></i>
                                Latest Data (Last 7 Periods)
                            </div>
                            <div className="card-body-modern">
                                <div className="latest-data-list">
                                    {getLatestData().map((item, index) => (
                                        <div key={index} className="latest-item-modern">
                                            <div className="latest-date">
                                                <i className="fas fa-calendar-alt"></i>
                                                {item.month || item.year_month}
                                            </div>
                                            <div className="latest-count">
                                                <span className="count-badge">{item.count}</span>
                                                <span className="count-label">movies</span>
                                            </div>
                                            <div className="latest-bar-wrapper">
                                                <div 
                                                    className="latest-bar"
                                                    style={{
                                                        width: `${(item.count / Math.max(...getLatestData().map(d => d.count))) * 100}%`,
                                                        background: `linear-gradient(90deg, ${gradientColors[index % gradientColors.length]} 0%, ${gradientColors[(index + 1) % gradientColors.length]} 100%)`
                                                    }}
                                                ></div>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* ============================================================ */}
                {/* CHARTS ROW 1 */}
                {/* ============================================================ */}
                <div className="row g-4 mb-4">
                    {/* Pie Chart - Movies by Genre */}
                    <div className="col-xl-6">
                        <div className="card-modern">
                            <div className="card-header-modern gradient-header-3">
                                <i className="fas fa-chart-pie me-2"></i>
                                Movies by Genre Distribution
                            </div>
                            <div className="card-body-modern">
                                <ModernPieChart 
                                    data={moviesByGenre} 
                                    title="Genre Distribution"
                                />
                            </div>
                        </div>
                    </div>

                    {/* Column Chart - Movies by Date */}
                    <div className="col-xl-6">
                        <div className="card-modern">
                            <div className="card-header-modern gradient-header-4">
                                <i className="fas fa-chart-bar me-2"></i>
                                Movies Released by Date
                            </div>
                            <div className="card-body-modern">
                                <ModernColumnChart 
                                    data={moviesByDate} 
                                    title={`Last ${filterMonths} Months`}
                                    labelKey="month"
                                    valueKey="count"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                {/* ============================================================ */}
                {/* CHARTS ROW 2 */}
                {/* ============================================================ */}
                <div className="row g-4 mb-4">
                    {/* Column Chart - Movies by Year */}
                    <div className="col-xl-6">
                        <div className="card-modern">
                            <div className="card-header-modern gradient-header-1">
                                <i className="fas fa-calendar me-2"></i>
                                Movies by Year
                            </div>
                            <div className="card-body-modern">
                                <ModernColumnChart 
                                    data={moviesByYear} 
                                    title="Last 5 Years"
                                    labelKey="year"
                                    valueKey="count"
                                />
                            </div>
                        </div>
                    </div>

                    {/* Line Chart - Rating Distribution */}
                    <div className="col-xl-6">
                        <div className="card-modern">
                            <div className="card-header-modern gradient-header-2">
                                <i className="fas fa-chart-line me-2"></i>
                                Rating Distribution
                            </div>
                            <div className="card-body-modern">
                                <ModernLineChart 
                                    data={ratingDistribution} 
                                    title="Movies by Rating Range"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                {/* ============================================================ */}
                {/* FEATURED MOVIES */}
                {/* ============================================================ */}
                <div className="featured-section-modern">
                    <h5 className="section-title-modern">
                        <i className="fas fa-star me-2"></i>
                        Featured Movies
                    </h5>
                    <div className="featured-grid-modern">
                        {/* Latest Movie */}
                        {stats?.latest_movie && (
                            <div className="featured-card-modern">
                                <div className="featured-badge badge-latest">Latest</div>
                                <div className="featured-poster-wrapper">
                                    <img 
                                        src={stats.latest_movie.poster_url || 'https://via.placeholder.com/300x450?text=No+Image'} 
                                        alt={stats.latest_movie.title}
                                        className="featured-poster"
                                        onError={(e) => e.target.src = 'https://via.placeholder.com/300x450?text=No+Image'}
                                    />
                                    <div className="featured-overlay"></div>
                                </div>
                                <div className="featured-info">
                                    <h6 className="featured-title">{stats.latest_movie.title}</h6>
                                    <div className="featured-meta">
                                        <span className="meta-badge badge-year">{stats.latest_movie.release_year}</span>
                                        <span className="meta-badge badge-rating">
                                            <i className="fas fa-star"></i>
                                            {stats.latest_movie.vote_average}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        )}

                        {/* Highest Rated */}
                        {stats?.highest_rated && (
                            <div className="featured-card-modern">
                                <div className="featured-badge badge-top">Top Rated</div>
                                <div className="featured-poster-wrapper">
                                    <img 
                                        src={stats.highest_rated.poster_url || 'https://via.placeholder.com/300x450?text=No+Image'} 
                                        alt={stats.highest_rated.title}
                                        className="featured-poster"
                                        onError={(e) => e.target.src = 'https://via.placeholder.com/300x450?text=No+Image'}
                                    />
                                    <div className="featured-overlay"></div>
                                </div>
                                <div className="featured-info">
                                    <h6 className="featured-title">{stats.highest_rated.title}</h6>
                                    <div className="featured-meta">
                                        <span className="meta-badge badge-rating-high">
                                            <i className="fas fa-star"></i>
                                            {stats.highest_rated.vote_average}
                                        </span>
                                        <span className="meta-badge badge-votes">
                                            {stats.highest_rated.vote_count.toLocaleString()} votes
                                        </span>
                                    </div>
                                </div>
                            </div>
                        )}

                        {/* Most Popular */}
                        {stats?.most_popular && (
                            <div className="featured-card-modern">
                                <div className="featured-badge badge-popular">Popular</div>
                                <div className="featured-poster-wrapper">
                                    <img 
                                        src={stats.most_popular.poster_url || 'https://via.placeholder.com/300x450?text=No+Image'} 
                                        alt={stats.most_popular.title}
                                        className="featured-poster"
                                        onError={(e) => e.target.src = 'https://via.placeholder.com/300x450?text=No+Image'}
                                    />
                                    <div className="featured-overlay"></div>
                                </div>
                                <div className="featured-info">
                                    <h6 className="featured-title">{stats.most_popular.title}</h6>
                                    <div className="featured-meta">
                                        <span className="meta-badge badge-popularity">
                                            <i className="fas fa-fire"></i>
                                            {stats.most_popular.popularity.toFixed(1)}
                                        </span>
                                        <span className="meta-badge badge-rating">
                                            <i className="fas fa-star"></i>
                                            {stats.most_popular.vote_average}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        )}

                        {/* Highest Grossing */}
                        {stats?.highest_grossing && (
                            <div className="featured-card-modern">
                                <div className="featured-badge badge-grossing">Top Grossing</div>
                                <div className="featured-poster-wrapper">
                                    <img 
                                        src={stats.highest_grossing.poster_url || 'https://via.placeholder.com/300x450?text=No+Image'} 
                                        alt={stats.highest_grossing.title}
                                        className="featured-poster"
                                        onError={(e) => e.target.src = 'https://via.placeholder.com/300x450?text=No+Image'}
                                    />
                                    <div className="featured-overlay"></div>
                                </div>
                                <div className="featured-info">
                                    <h6 className="featured-title">{stats.highest_grossing.title}</h6>
                                    <div className="featured-meta-column">
                                        <div className="meta-row">
                                            <span className="meta-label">Revenue:</span>
                                            <span className="meta-value meta-success">
                                                {stats.highest_grossing.formatted_revenue}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        )}
                    </div>
                </div>

                {/* ============================================================ */}
                {/* TOP LISTS */}
                {/* ============================================================ */}
                <div className="row g-4 mb-4">
                    {/* Top Rated Movies */}
                    <div className="col-xl-6">
                        <div className="card-modern">
                            <div className="card-header-modern gradient-header-3">
                                <i className="fas fa-trophy me-2"></i>
                                Top Rated Movies
                            </div>
                            <div className="card-body-modern p-0">
                                <div className="movie-list-modern">
                                    {topRated.map((movie, index) => (
                                        <div key={movie.id} className="movie-item-modern">
                                            <div className="movie-rank-modern" style={{
                                                background: `linear-gradient(135deg, ${gradientColors[index % gradientColors.length]} 0%, ${gradientColors[(index + 1) % gradientColors.length]} 100%)`
                                            }}>
                                                #{index + 1}
                                            </div>
                                            <img 
                                                src={movie.poster_url || 'https://via.placeholder.com/50x75?text=No'}
                                                alt={movie.title}
                                                className="movie-thumb-modern"
                                                onError={(e) => e.target.src = 'https://via.placeholder.com/50x75?text=No'}
                                            />
                                            <div className="movie-details-modern">
                                                <div className="movie-title-modern">{movie.title}</div>
                                                <div className="movie-year-modern">{movie.release_year}</div>
                                            </div>
                                            <div className="movie-stats-modern">
                                                <div className="movie-rating-modern">
                                                    <i className="fas fa-star"></i>
                                                    {movie.vote_average}
                                                </div>
                                                <div className="movie-votes-modern">
                                                    {movie.vote_count.toLocaleString()} votes
                                                </div>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Most Popular Movies */}
                    <div className="col-xl-6">
                        <div className="card-modern">
                            <div className="card-header-modern gradient-header-4">
                                <i className="fas fa-fire me-2"></i>
                                Most Popular Movies
                            </div>
                            <div className="card-body-modern p-0">
                                <div className="movie-list-modern">
                                    {mostPopular.map((movie, index) => (
                                        <div key={movie.id} className="movie-item-modern">
                                            <div className="movie-rank-modern" style={{
                                                background: `linear-gradient(135deg, ${gradientColors[index % gradientColors.length]} 0%, ${gradientColors[(index + 1) % gradientColors.length]} 100%)`
                                            }}>
                                                #{index + 1}
                                            </div>
                                            <img 
                                                src={movie.poster_url || 'https://via.placeholder.com/50x75?text=No'}
                                                alt={movie.title}
                                                className="movie-thumb-modern"
                                                onError={(e) => e.target.src = 'https://via.placeholder.com/50x75?text=No'}
                                            />
                                            <div className="movie-details-modern">
                                                <div className="movie-title-modern">{movie.title}</div>
                                                <div className="movie-year-modern">{movie.release_year}</div>
                                            </div>
                                            <div className="movie-stats-modern">
                                                <div className="movie-popularity-modern">
                                                    <i className="fas fa-fire"></i>
                                                    {movie.popularity.toFixed(1)}
                                                </div>
                                                <div className="movie-rating-small-modern">
                                                    <i className="fas fa-star"></i>
                                                    {movie.vote_average}
                                                </div>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* ============================================================ */}
                {/* SYNC INFO */}
                {/* ============================================================ */}
                {stats?.last_sync && (
                    <div className="sync-card-modern">
                        <div className="sync-icon-modern">
                            <i className={`fas ${stats.last_sync.status_icon}`}></i>
                        </div>
                        <div className="sync-content-modern">
                            <h6 className="sync-title">Last Sync Status</h6>
                            <p className="sync-text">
                                {stats.last_sync.time_ago} • 
                                Created: {stats.last_sync.records_created} • 
                                Updated: {stats.last_sync.records_updated} • 
                                Success Rate: {stats.last_sync.success_rate}%
                            </p>
                        </div>
                        <div className="sync-badge-modern">
                            <span className={`status-badge status-${stats.last_sync.status}`}>
                                {stats.last_sync.status.toUpperCase()}
                            </span>
                            <div className="sync-duration">
                                Duration: {stats.last_sync.formatted_duration}
                            </div>
                        </div>
                    </div>
                )}
            </div>

            {/* ============================================================ */}
            {/* STYLES */}
            {/* ============================================================ */}
            <style>{`
                /* Gradient Colors: #977DFF, #0033FF, #0600AB, #00033D */
                
                /* ===== DASHBOARD LAYOUT ===== */
                .dashboard-modern {
                    background: linear-gradient(135deg, #f5f7fa 0%, #e8eef5 100%);
                    min-height: 100vh;
                    padding: 0;
                }

                .dashboard-content {
                    padding: 2rem;
                }

                /* ===== LOADING STATE ===== */
                .loading-container-modern {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    min-height: 100vh;
                    background: linear-gradient(135deg, #f5f7fa 0%, #e8eef5 100%);
                }

                .loading-content {
                    text-align: center;
                }

                .spinner-modern {
                    position: relative;
                    width: 80px;
                    height: 80px;
                    margin: 0 auto 2rem;
                }

                .spinner-ring {
                    position: absolute;
                    width: 100%;
                    height: 100%;
                    border: 4px solid transparent;
                    border-top-color: #977DFF;
                    border-radius: 50%;
                    animation: spin 1.5s cubic-bezier(0.5, 0, 0.5, 1) infinite;
                }

                .spinner-ring:nth-child(1) {
                    animation-delay: -0.45s;
                    border-top-color: #977DFF;
                }

                .spinner-ring:nth-child(2) {
                    animation-delay: -0.3s;
                    border-top-color: #0033FF;
                }

                .spinner-ring:nth-child(3) {
                    animation-delay: -0.15s;
                    border-top-color: #0600AB;
                }

                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }

                .loading-text {
                    color: #0600AB;
                    font-weight: 600;
                    font-size: 1.2rem;
                    margin: 0;
                }

                /* ===== HEADER ===== */
                .dashboard-header-modern {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 50%, #0600AB 100%);
                    padding: 2.5rem 2rem;
                    box-shadow: 0 10px 40px rgba(151, 125, 255, 0.3);
                    position: relative;
                    overflow: hidden;
                }

                .dashboard-header-modern::before {
                    content: '';
                    position: absolute;
                    top: -50%;
                    right: -10%;
                    width: 500px;
                    height: 500px;
                    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
                    border-radius: 50%;
                }

                .header-content {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    position: relative;
                    z-index: 1;
                }

                .header-title-section {
                    display: flex;
                    align-items: center;
                    gap: 1.5rem;
                }

                .title-icon-modern {
                    width: 70px;
                    height: 70px;
                    background: rgba(255, 255, 255, 0.2);
                    border-radius: 20px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 2rem;
                    color: white;
                    backdrop-filter: blur(10px);
                    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
                }

                .page-title {
                    font-size: 2.5rem;
                    font-weight: 800;
                    color: white;
                    margin: 0;
                    line-height: 1.2;
                    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
                }

                .page-subtitle {
                    font-size: 1rem;
                    color: rgba(255, 255, 255, 0.9);
                    margin: 0.25rem 0 0 0;
                }

                .header-actions {
                    display: flex;
                    gap: 1rem;
                }

                .filter-select-modern {
                    background: rgba(255, 255, 255, 0.15);
                    border: 2px solid rgba(255, 255, 255, 0.3);
                    color: white;
                    padding: 0.75rem 1.5rem;
                    border-radius: 12px;
                    font-weight: 600;
                    cursor: pointer;
                    backdrop-filter: blur(10px);
                    transition: all 0.3s ease;
                }

                .filter-select-modern:focus {
                    outline: none;
                    background: rgba(255, 255, 255, 0.25);
                    border-color: white;
                }

                .filter-select-modern option {
                    background: #0600AB;
                    color: white;
                }

                .btn-refresh-modern {
                    background: white;
                    color: #0600AB;
                    border: none;
                    padding: 0.75rem 1.5rem;
                    border-radius: 12px;
                    font-weight: 700;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                }

                .btn-refresh-modern:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
                }

                /* ===== STATISTICS CARDS ===== */
                .stats-grid-modern {
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                    gap: 1.5rem;
                    margin-bottom: 2rem;
                }

                .stat-card-modern {
                    background: white;
                    border-radius: 20px;
                    padding: 2rem;
                    display: flex;
                    align-items: center;
                    gap: 1.5rem;
                    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
                    transition: all 0.3s ease;
                    position: relative;
                    overflow: hidden;
                }

                .stat-card-modern::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    height: 5px;
                }

                .stat-card-modern.stat-gradient-1::before {
                    background: linear-gradient(90deg, #977DFF 0%, #0033FF 100%);
                }

                .stat-card-modern.stat-gradient-2::before {
                    background: linear-gradient(90deg, #0033FF 0%, #0600AB 100%);
                }

                .stat-card-modern.stat-gradient-3::before {
                    background: linear-gradient(90deg, #0600AB 0%, #00033D 100%);
                }

                .stat-card-modern.stat-gradient-4::before {
                    background: linear-gradient(90deg, #977DFF 0%, #00033D 100%);
                }

                .stat-card-modern:hover {
                    transform: translateY(-8px);
                    box-shadow: 0 15px 40px rgba(151, 125, 255, 0.25);
                }

                .stat-icon-wrapper {
                    width: 70px;
                    height: 70px;
                    border-radius: 15px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 2rem;
                    flex-shrink: 0;
                }

                .stat-gradient-1 .stat-icon-wrapper {
                    background: linear-gradient(135deg, #f0ebff 0%, #e6f2ff 100%);
                    color: #977DFF;
                }

                .stat-gradient-2 .stat-icon-wrapper {
                    background: linear-gradient(135deg, #e6f2ff 0%, #d9e9ff 100%);
                    color: #0033FF;
                }

                .stat-gradient-3 .stat-icon-wrapper {
                    background: linear-gradient(135deg, #d9e9ff 0%, #cce0ff 100%);
                    color: #0600AB;
                }

                .stat-gradient-4 .stat-icon-wrapper {
                    background: linear-gradient(135deg, #cce0ff 0%, #bfd7ff 100%);
                    color: #00033D;
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
                    font-weight: 800;
                    color: #0600AB;
                    line-height: 1;
                    margin-bottom: 0.5rem;
                }

                .stat-footer {
                    font-size: 0.85rem;
                    color: #6c757d;
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                }

                /* ===== CARD MODERN ===== */
                .card-modern {
                    background: white;
                    border-radius: 20px;
                    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
                    overflow: hidden;
                    transition: all 0.3s ease;
                }

                .card-modern:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 15px 40px rgba(151, 125, 255, 0.2);
                }

                .card-header-modern {
                    color: white;
                    padding: 1.5rem 2rem;
                    font-weight: 700;
                    font-size: 1.1rem;
                    display: flex;
                    align-items: center;
                }

                .gradient-header-1 {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 100%);
                }

                .gradient-header-2 {
                    background: linear-gradient(135deg, #0033FF 0%, #0600AB 100%);
                }

                .gradient-header-3 {
                    background: linear-gradient(135deg, #0600AB 0%, #00033D 100%);
                }

                .gradient-header-4 {
                    background: linear-gradient(135deg, #977DFF 0%, #00033D 100%);
                }

                .card-body-modern {
                    padding: 2rem;
                }

                /* ===== TOP CATEGORIES ===== */
                .top-categories-list {
                    display: flex;
                    flex-direction: column;
                    gap: 1.5rem;
                }

                .category-item-modern {
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                }

                .category-rank {
                    width: 45px;
                    height: 45px;
                    border-radius: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    font-weight: 800;
                    font-size: 1rem;
                    flex-shrink: 0;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                }

                .category-info {
                    flex: 1;
                }

                .category-name {
                    font-weight: 700;
                    color: #0600AB;
                    margin-bottom: 0.25rem;
                }

                .category-stats {
                    font-size: 0.85rem;
                    color: #6c757d;
                }

                .category-bar-wrapper {
                    width: 150px;
                    height: 8px;
                    background: #f0f0f0;
                    border-radius: 10px;
                    overflow: hidden;
                }

                .category-bar {
                    height: 100%;
                    border-radius: 10px;
                    transition: width 0.5s ease;
                }

                /* ===== LATEST DATA ===== */
                .latest-data-list {
                    display: flex;
                    flex-direction: column;
                    gap: 1rem;
                }

                .latest-item-modern {
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                    padding: 1rem;
                    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
                    border-radius: 12px;
                    transition: all 0.3s ease;
                }

                .latest-item-modern:hover {
                    background: linear-gradient(135deg, #f0ebff 0%, #e6f2ff 100%);
                    transform: translateX(5px);
                }

                .latest-date {
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    font-weight: 600;
                    color: #0600AB;
                    min-width: 120px;
                }

                .latest-count {
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    min-width: 100px;
                }

                .count-badge {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 100%);
                    color: white;
                    padding: 0.4rem 0.8rem;
                    border-radius: 8px;
                    font-weight: 700;
                }

                .count-label {
                    font-size: 0.85rem;
                    color: #6c757d;
                }

                .latest-bar-wrapper {
                    flex: 1;
                    height: 8px;
                    background: #f0f0f0;
                    border-radius: 10px;
                    overflow: hidden;
                }

                .latest-bar {
                    height: 100%;
                    border-radius: 10px;
                    transition: width 0.5s ease;
                }

                /* ===== CHARTS ===== */
                .modern-chart-container {
                    width: 100%;
                }

                .chart-title-modern {
                    text-align: center;
                    color: #0600AB;
                    font-weight: 700;
                    margin-bottom: 2rem;
                    font-size: 1.1rem;
                }

                /* Pie Chart */
                .pie-chart-wrapper-modern {
                    display: flex;
                    justify-content: center;
                    margin-bottom: 2rem;
                }

                .pie-chart-modern {
                    width: 280px;
                    height: 280px;
                    border-radius: 50%;
                    position: relative;
                    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                .pie-center-modern {
                    width: 140px;
                    height: 140px;
                    background: white;
                    border-radius: 50%;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
                }

                .pie-total {
                    font-size: 3rem;
                    font-weight: 800;
                    color: #0600AB;
                    line-height: 1;
                }

                .pie-label {
                    font-size: 0.9rem;
                    color: #6c757d;
                    font-weight: 600;
                    margin-top: 0.25rem;
                }

                .pie-legend-modern {
                    display: grid;
                    grid-template-columns: repeat(2, 1fr);
                    gap: 1rem;
                }

                .legend-item-modern {
                    display: flex;
                    align-items: center;
                    gap: 0.75rem;
                    padding: 0.75rem;
                    background: #f8f9fa;
                    border-radius: 10px;
                    transition: all 0.3s ease;
                }

                .legend-item-modern:hover {
                    background: linear-gradient(135deg, #f0ebff 0%, #e6f2ff 100%);
                    transform: translateX(5px);
                }

                .legend-color-modern {
                    width: 20px;
                    height: 20px;
                    border-radius: 6px;
                    flex-shrink: 0;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
                }

                .legend-text-modern {
                    display: flex;
                    flex-direction: column;
                    font-size: 0.85rem;
                }

                .legend-name {
                    font-weight: 700;
                    color: #0600AB;
                }

                .legend-value {
                    color: #6c757d;
                }

                /* Column Chart */
                .column-chart-modern {
                    height: 350px;
                    display: flex;
                    align-items: flex-end;
                    gap: 1rem;
                    padding: 2rem 1rem 3rem;
                }

                .column-item-modern {
                    flex: 1;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    gap: 0.5rem;
                }

                .column-value {
                    font-size: 0.9rem;
                    font-weight: 700;
                    color: #0600AB;
                }

                .column-bar-modern {
                    width: 100%;
                    border-radius: 10px 10px 0 0;
                    transition: all 0.3s ease;
                    cursor: pointer;
                    box-shadow: 0 5px 15px rgba(151, 125, 255, 0.3);
                    position: relative;
                    overflow: hidden;
                }

                .column-bar-shine {
                    position: absolute;
                    top: 0;
                    left: -100%;
                    width: 100%;
                    height: 100%;
                    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
                    transition: left 0.5s ease;
                }

                .column-bar-modern:hover .column-bar-shine {
                    left: 100%;
                }

                .column-bar-modern:hover {
                    transform: scale(1.05);
                    box-shadow: 0 8px 25px rgba(151, 125, 255, 0.5);
                }

                .column-label {
                    font-size: 0.75rem;
                    color: #6c757d;
                    font-weight: 600;
                    text-align: center;
                    transform: rotate(-45deg);
                    white-space: nowrap;
                    margin-top: 1rem;
                }

                /* Line Chart */
                .line-chart-modern {
                    height: 300px;
                    position: relative;
                    padding: 2rem 1rem;
                }

                .line-chart-labels {
                    display: flex;
                    justify-content: space-between;
                    margin-top: 1rem;
                }

                .line-label {
                    font-size: 0.75rem;
                    color: #6c757d;
                    font-weight: 600;
                }

                /* No Data State */
                .no-data-state {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    padding: 4rem 2rem;
                    color: #6c757d;
                }

                .no-data-state i {
                    font-size: 4rem;
                    color: #977DFF;
                    opacity: 0.3;
                    margin-bottom: 1rem;
                }

                .no-data-state p {
                    margin: 0;
                    font-weight: 600;
                }

                /* ===== FEATURED SECTION ===== */
                .featured-section-modern {
                    margin-bottom: 2rem;
                }

                .section-title-modern {
                    color: #0600AB;
                    font-weight: 800;
                    margin-bottom: 1.5rem;
                    padding-bottom: 1rem;
                    border-bottom: 4px solid;
                    border-image: linear-gradient(90deg, #977DFF 0%, #0033FF 50%, #0600AB 100%) 1;
                    display: flex;
                    align-items: center;
                }

                .featured-grid-modern {
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                    gap: 1.5rem;
                }

                .featured-card-modern {
                    background: white;
                    border-radius: 20px;
                    overflow: hidden;
                    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
                    transition: all 0.3s ease;
                    position: relative;
                }

                .featured-card-modern:hover {
                    transform: translateY(-10px);
                    box-shadow: 0 20px 50px rgba(151, 125, 255, 0.3);
                }

                .featured-badge {
                    position: absolute;
                    top: 1rem;
                    right: 1rem;
                    padding: 0.5rem 1rem;
                    border-radius: 10px;
                    font-weight: 700;
                    font-size: 0.85rem;
                    z-index: 2;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
                }

                .badge-latest {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 100%);
                    color: white;
                }

                .badge-top {
                    background: linear-gradient(135deg, #20E3B2 0%, #29BB89 100%);
                    color: white;
                }

                .badge-popular {
                    background: linear-gradient(135deg, #FF6B9D 0%, #C44569 100%);
                    color: white;
                }

                .badge-grossing {
                    background: linear-gradient(135deg, #FFA07A 0%, #FF6347 100%);
                    color: white;
                }

                .featured-poster-wrapper {
                    position: relative;
                    overflow: hidden;
                }

                .featured-poster {
                    width: 100%;
                    height: 350px;
                    object-fit: cover;
                    transition: transform 0.5s ease;
                }

                .featured-card-modern:hover .featured-poster {
                    transform: scale(1.1);
                }

                .featured-overlay {
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    height: 100px;
                    background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
                }

                .featured-info {
                    padding: 1.5rem;
                }

                .featured-title {
                    font-weight: 700;
                    color: #0600AB;
                    margin-bottom: 1rem;
                    font-size: 1.1rem;
                }

                .featured-meta {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    gap: 0.5rem;
                }

                .meta-badge {
                    padding: 0.5rem 1rem;
                                        border-radius: 10px;
                    font-weight: 700;
                    font-size: 0.85rem;
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                }

                .badge-year {
                    background: linear-gradient(135deg, #f0ebff 0%, #e6f2ff 100%);
                    color: #0600AB;
                }

                .badge-rating {
                    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
                    color: #856404;
                }

                .badge-rating-high {
                    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
                    color: #155724;
                }

                .badge-popularity {
                    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
                    color: #0c5460;
                }

                .badge-votes {
                    background: #f8f9fa;
                    color: #6c757d;
                    font-size: 0.75rem;
                }

                .featured-meta-column {
                    display: flex;
                    flex-direction: column;
                    gap: 0.5rem;
                }

                .meta-row {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }

                .meta-label {
                    color: #6c757d;
                    font-size: 0.85rem;
                }

                .meta-value {
                    font-weight: 700;
                    font-size: 0.9rem;
                }

                .meta-success {
                    color: #28a745;
                }

                /* ===== MOVIE LIST ===== */
                .movie-list-modern {
                    max-height: 600px;
                    overflow-y: auto;
                }

                .movie-list-modern::-webkit-scrollbar {
                    width: 8px;
                }

                .movie-list-modern::-webkit-scrollbar-track {
                    background: #f1f1f1;
                }

                .movie-list-modern::-webkit-scrollbar-thumb {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 100%);
                    border-radius: 10px;
                }

                .movie-item-modern {
                    display: flex;
                    align-items: center;
                    gap: 1rem;
                    padding: 1.25rem 1.5rem;
                    border-bottom: 1px solid #e9ecef;
                    transition: all 0.3s ease;
                }

                .movie-item-modern:hover {
                    background: linear-gradient(135deg, #f0ebff 0%, #e6f2ff 100%);
                    transform: translateX(5px);
                }

                .movie-item-modern:last-child {
                    border-bottom: none;
                }

                .movie-rank-modern {
                    width: 45px;
                    height: 45px;
                    border-radius: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                    font-weight: 800;
                    font-size: 1.1rem;
                    flex-shrink: 0;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                }

                .movie-thumb-modern {
                    width: 50px;
                    height: 75px;
                    object-fit: cover;
                    border-radius: 10px;
                    flex-shrink: 0;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                }

                .movie-details-modern {
                    flex: 1;
                    min-width: 0;
                }

                .movie-title-modern {
                    font-weight: 700;
                    color: #0600AB;
                    margin-bottom: 0.25rem;
                    white-space: nowrap;
                    overflow: hidden;
                    text-overflow: ellipsis;
                }

                .movie-year-modern {
                    color: #6c757d;
                    font-size: 0.85rem;
                }

                .movie-stats-modern {
                    display: flex;
                    flex-direction: column;
                    align-items: flex-end;
                    gap: 0.25rem;
                }

                .movie-rating-modern {
                    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
                    color: #856404;
                    padding: 0.5rem 1rem;
                    border-radius: 10px;
                    font-weight: 700;
                    font-size: 0.9rem;
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                }

                .movie-votes-modern {
                    color: #6c757d;
                    font-size: 0.75rem;
                }

                .movie-popularity-modern {
                    background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
                    color: #0c5460;
                    padding: 0.5rem 1rem;
                    border-radius: 10px;
                    font-weight: 700;
                    font-size: 0.9rem;
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                }

                .movie-rating-small-modern {
                    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
                    color: #856404;
                    padding: 0.4rem 0.8rem;
                    border-radius: 8px;
                    font-weight: 600;
                    font-size: 0.8rem;
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                }

                /* ===== SYNC CARD ===== */
                .sync-card-modern {
                    background: white;
                    border-radius: 20px;
                    padding: 2rem;
                    box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
                    display: flex;
                    align-items: center;
                    gap: 1.5rem;
                    border-left: 5px solid #977DFF;
                    transition: all 0.3s ease;
                }

                .sync-card-modern:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 15px 40px rgba(151, 125, 255, 0.2);
                }

                .sync-icon-modern {
                    width: 70px;
                    height: 70px;
                    background: linear-gradient(135deg, #f0ebff 0%, #e6f2ff 100%);
                    color: #977DFF;
                    border-radius: 15px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 2rem;
                    flex-shrink: 0;
                    box-shadow: 0 4px 12px rgba(151, 125, 255, 0.2);
                }

                .sync-content-modern {
                    flex: 1;
                }

                .sync-title {
                    color: #0600AB;
                    font-weight: 700;
                    margin-bottom: 0.5rem;
                }

                .sync-text {
                    color: #6c757d;
                    margin: 0;
                    font-size: 0.95rem;
                }

                .sync-badge-modern {
                    text-align: right;
                }

                .status-badge {
                    padding: 0.6rem 1.2rem;
                    border-radius: 10px;
                    font-weight: 700;
                    font-size: 0.9rem;
                    display: inline-block;
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

                .sync-duration {
                    margin-top: 0.5rem;
                    color: #6c757d;
                    font-size: 0.85rem;
                }

                /* ===== RESPONSIVE DESIGN ===== */
                @media (max-width: 1199.98px) {
                    .stats-grid-modern {
                        grid-template-columns: repeat(2, 1fr);
                    }

                    .featured-grid-modern {
                        grid-template-columns: repeat(2, 1fr);
                    }
                }

                @media (max-width: 991.98px) {
                    .dashboard-header-modern {
                        padding: 2rem 1.5rem;
                    }

                    .header-content {
                        flex-direction: column;
                        gap: 1.5rem;
                    }

                    .header-title-section {
                        width: 100%;
                        justify-content: center;
                        text-align: center;
                    }

                    .header-actions {
                        width: 100%;
                        justify-content: center;
                    }

                    .page-title {
                        font-size: 2rem;
                    }

                    .column-chart-modern {
                        height: 300px;
                    }

                    .pie-chart-modern {
                        width: 220px;
                        height: 220px;
                    }

                    .pie-center-modern {
                        width: 110px;
                        height: 110px;
                    }

                    .pie-total {
                        font-size: 2.5rem;
                    }
                }

                @media (max-width: 767.98px) {
                    .dashboard-content {
                        padding: 1rem;
                    }

                    .stats-grid-modern {
                        grid-template-columns: 1fr;
                    }

                    .featured-grid-modern {
                        grid-template-columns: 1fr;
                    }

                    .stat-value {
                        font-size: 2rem;
                    }

                    .movie-item-modern {
                        flex-wrap: wrap;
                    }

                    .movie-stats-modern {
                        width: 100%;
                        flex-direction: row;
                        justify-content: space-between;
                        margin-top: 0.5rem;
                    }

                    .sync-card-modern {
                        flex-direction: column;
                        text-align: center;
                    }

                    .sync-badge-modern {
                        text-align: center;
                    }

                    .pie-legend-modern {
                        grid-template-columns: 1fr;
                    }

                    .column-chart-modern {
                        padding: 1rem 0.5rem 2rem;
                        gap: 0.5rem;
                    }

                    .column-label {
                        font-size: 0.65rem;
                    }

                    .header-actions {
                        flex-direction: column;
                        width: 100%;
                    }

                    .filter-select-modern,
                    .btn-refresh-modern {
                        width: 100%;
                        justify-content: center;
                    }
                }

                @media (max-width: 575.98px) {
                    .dashboard-header-modern {
                        padding: 1.5rem 1rem;
                    }

                    .title-icon-modern {
                        width: 60px;
                        height: 60px;
                        font-size: 1.5rem;
                    }

                    .page-title {
                        font-size: 1.75rem;
                    }

                    .page-subtitle {
                        font-size: 0.9rem;
                    }

                    .stat-card-modern {
                        padding: 1.5rem;
                    }

                    .stat-icon-wrapper {
                        width: 60px;
                        height: 60px;
                        font-size: 1.5rem;
                    }

                    .stat-value {
                        font-size: 1.75rem;
                    }

                    .card-body-modern {
                        padding: 1.5rem;
                    }

                    .featured-poster {
                        height: 300px;
                    }

                    .movie-rank-modern {
                        width: 40px;
                        height: 40px;
                        font-size: 1rem;
                    }

                    .movie-thumb-modern {
                        width: 40px;
                        height: 60px;
                    }
                }

                /* ===== ANIMATIONS ===== */
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

                .stat-card-modern,
                .card-modern,
                .featured-card-modern {
                    animation: fadeIn 0.5s ease;
                }

                @keyframes pulse {
                    0%, 100% {
                        opacity: 1;
                    }
                    50% {
                        opacity: 0.5;
                    }
                }

                /* ===== PRINT STYLES ===== */
                @media print {
                    .dashboard-header-modern,
                    .header-actions,
                    .btn-refresh-modern {
                        display: none !important;
                    }

                    .dashboard-modern {
                        background: white !important;
                    }

                    .stat-card-modern,
                    .card-modern {
                        box-shadow: none !important;
                        border: 1px solid #ddd !important;
                        break-inside: avoid;
                    }
                }

                /* ===== ACCESSIBILITY ===== */
                *:focus-visible {
                    outline: 3px solid rgba(151, 125, 255, 0.5);
                    outline-offset: 2px;
                }

                /* ===== SMOOTH TRANSITIONS ===== */
                * {
                    transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
                    transition-duration: 0.3s;
                    transition-timing-function: ease;
                }

                /* ===== BACKDROP BLUR SUPPORT ===== */
                @supports (backdrop-filter: blur(10px)) {
                    .filter-select-modern,
                    .title-icon-modern {
                        backdrop-filter: blur(10px);
                        -webkit-backdrop-filter: blur(10px);
                    }
                }

                /* ===== SELECTION ===== */
                ::selection {
                    background: #977DFF;
                    color: white;
                }

                ::-moz-selection {
                    background: #977DFF;
                    color: white;
                }

                /* ===== UTILITY CLASSES ===== */
                .text-gradient {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 100%);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                }

                .bg-gradient-primary {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 100%);
                }

                .shadow-gradient {
                    box-shadow: 0 10px 40px rgba(151, 125, 255, 0.3);
                }

                /* ===== HOVER EFFECTS ===== */
                .hover-scale {
                    transition: transform 0.3s ease;
                }

                .hover-scale:hover {
                    transform: scale(1.05);
                }

                .hover-lift {
                    transition: transform 0.3s ease, box-shadow 0.3s ease;
                }

                .hover-lift:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 15px 40px rgba(151, 125, 255, 0.3);
                }

                /* ===== SKELETON LOADING ===== */
                @keyframes skeleton-loading {
                    0% {
                        background-position: -200px 0;
                    }
                    100% {
                        background-position: calc(200px + 100%) 0;
                    }
                }

                .skeleton {
                    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
                    background-size: 200px 100%;
                    animation: skeleton-loading 1.5s ease-in-out infinite;
                }

                /* ===== DARK MODE SUPPORT (OPTIONAL) ===== */
                @media (prefers-color-scheme: dark) {
                    .dashboard-modern {
                        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
                    }

                    .stat-card-modern,
                    .card-modern,
                    .featured-card-modern {
                        background: #2d3748;
                        color: #e2e8f0;
                    }

                    .stat-value,
                    .movie-title-modern,
                    .featured-title {
                        color: #e2e8f0;
                    }
                }

                /* ===== HIGH CONTRAST MODE ===== */
                @media (prefers-contrast: high) {
                    .stat-card-modern,
                    .card-modern {
                        border: 2px solid #0600AB;
                    }

                    .filter-select-modern {
                        border-width: 3px;
                    }
                }

                /* ===== REDUCED MOTION ===== */
                @media (prefers-reduced-motion: reduce) {
                    *,
                    *::before,
                    *::after {
                        animation-duration: 0.01ms !important;
                        animation-iteration-count: 1 !important;
                        transition-duration: 0.01ms !important;
                    }
                }

                /* ===== CUSTOM SCROLLBAR ===== */
                * {
                    scrollbar-width: thin;
                    scrollbar-color: #977DFF #f1f1f1;
                }

                *::-webkit-scrollbar {
                    width: 10px;
                    height: 10px;
                }

                *::-webkit-scrollbar-track {
                    background: #f1f1f1;
                    border-radius: 10px;
                }

                *::-webkit-scrollbar-thumb {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 100%);
                    border-radius: 10px;
                }

                *::-webkit-scrollbar-thumb:hover {
                    background: linear-gradient(135deg, #0600AB 0%, #00033D 100%);
                }

                /* ===== GRADIENT ANIMATIONS ===== */
                @keyframes gradient-shift {
                    0% {
                        background-position: 0% 50%;
                    }
                    50% {
                        background-position: 100% 50%;
                    }
                    100% {
                        background-position: 0% 50%;
                    }
                }

                .animated-gradient {
                    background-size: 200% 200%;
                    animation: gradient-shift 3s ease infinite;
                }

                /* ===== GLOW EFFECTS ===== */
                @keyframes glow {
                    0%, 100% {
                        box-shadow: 0 0 20px rgba(151, 125, 255, 0.5);
                    }
                    50% {
                        box-shadow: 0 0 40px rgba(151, 125, 255, 0.8);
                    }
                }

                .glow-effect {
                    animation: glow 2s ease-in-out infinite;
                }

                /* ===== FLOATING ANIMATION ===== */
                @keyframes float {
                    0%, 100% {
                        transform: translateY(0px);
                    }
                    50% {
                        transform: translateY(-10px);
                    }
                }

                .float-animation {
                    animation: float 3s ease-in-out infinite;
                }
            `}</style>
        </div>
    );
}

export default Dashboard;

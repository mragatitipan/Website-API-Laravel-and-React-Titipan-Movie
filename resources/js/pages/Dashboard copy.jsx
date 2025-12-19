import React, { useEffect, useState } from 'react';
import { dashboardAPI } from '../services/api';
import { 
    Chart as ChartJS, 
    ArcElement, 
    Tooltip, 
    Legend,
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    LineElement,
    PointElement
} from 'chart.js';
import { Pie, Bar, Line } from 'react-chartjs-2';
import DatePicker from 'react-datepicker';
import 'react-datepicker/dist/react-datepicker.css';

// Register ChartJS components
ChartJS.register(
    ArcElement, 
    Tooltip, 
    Legend,
    CategoryScale,
    LinearScale,
    BarElement,
    Title,
    LineElement,
    PointElement
);

function Dashboard() {
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
    
    // Date filter state
    const [startDate, setStartDate] = useState(
        new Date(new Date().setMonth(new Date().getMonth() - 1))
    );
    const [endDate, setEndDate] = useState(new Date());
    const [filterMonths, setFilterMonths] = useState(12);

    useEffect(() => {
        loadDashboardData();
    }, [filterMonths]);

    const loadDashboardData = async () => {
        setLoading(true);
        try {
            // Load all dashboard data
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
            console.error('Error loading dashboard data:', error);
        } finally {
            setLoading(false);
        }
    };

    // Pie Chart Data - Movies by Genre
    const pieChartData = {
        labels: moviesByGenre.slice(0, 10).map(item => item.genre),
        datasets: [{
            label: 'Movies by Genre',
            data: moviesByGenre.slice(0, 10).map(item => item.count),
            backgroundColor: [
                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    };

    // Column Chart Data - Movies by Date
    const columnChartData = {
        labels: moviesByDate.map(item => item.month),
        datasets: [{
            label: 'Movies Released',
            data: moviesByDate.map(item => item.count),
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2
        }]
    };

    // Bar Chart Data - Movies by Year
    const yearChartData = {
        labels: moviesByYear.map(item => item.year),
        datasets: [
            {
                label: 'Number of Movies',
                data: moviesByYear.map(item => item.count),
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                yAxisID: 'y'
            },
            {
                label: 'Average Rating',
                data: moviesByYear.map(item => item.avg_rating),
                backgroundColor: 'rgba(255, 159, 64, 0.6)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 2,
                yAxisID: 'y1'
            }
        ]
    };

    // Line Chart Data - Rating Distribution
    const ratingChartData = {
        labels: ratingDistribution.map(item => item.range),
        datasets: [{
            label: 'Number of Movies',
            data: ratingDistribution.map(item => item.count),
            fill: true,
            backgroundColor: 'rgba(153, 102, 255, 0.2)',
            borderColor: 'rgba(153, 102, 255, 1)',
            tension: 0.4,
            borderWidth: 2
        }]
    };

    // Chart Options
    const pieOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'right',
                labels: {
                    boxWidth: 15,
                    padding: 10
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const label = context.label || '';
                        const value = context.parsed || 0;
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((value / total) * 100).toFixed(1);
                        return `${label}: ${value} (${percentage}%)`;
                    }
                }
            }
        }
    };

    const columnOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            },
            title: {
                display: true,
                text: `Movies Released (Last ${filterMonths} Months)`
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    };

    const yearOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            },
            title: {
                display: true,
                text: 'Movies & Ratings by Year'
            }
        },
        scales: {
            y: {
                type: 'linear',
                display: true,
                position: 'left',
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Number of Movies'
                }
            },
            y1: {
                type: 'linear',
                display: true,
                position: 'right',
                beginAtZero: true,
                max: 10,
                title: {
                    display: true,
                    text: 'Average Rating'
                },
                grid: {
                    drawOnChartArea: false
                }
            }
        }
    };

    const ratingOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'top'
            },
            title: {
                display: true,
                text: 'Rating Distribution'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    };

    if (loading) {
        return (
            <div className="d-flex justify-content-center align-items-center" style={{ minHeight: '80vh' }}>
                <div className="text-center">
                    <div className="spinner-border text-primary" role="status" style={{ width: '3rem', height: '3rem' }}>
                        <span className="visually-hidden">Loading...</span>
                    </div>
                    <p className="mt-3 text-muted">Loading dashboard data...</p>
                </div>
            </div>
        );
    }

    return (
        <div className="container-fluid p-4">
            {/* Header */}
            <div className="d-flex justify-content-between align-items-center mb-4">
                <h1 className="mb-0">
                    <i className="fas fa-chart-line text-primary me-2"></i>
                    Dashboard
                </h1>
                
                {/* Date Filter */}
                <div className="d-flex gap-2">
                    <select 
                        className="form-select" 
                        value={filterMonths}
                        onChange={(e) => setFilterMonths(parseInt(e.target.value))}
                        style={{ width: 'auto' }}
                    >
                        <option value={1}>Last Month</option>
                        <option value={3}>Last 3 Months</option>
                        <option value={6}>Last 6 Months</option>
                        <option value={12}>Last 12 Months</option>
                        <option value={24}>Last 2 Years</option>
                    </select>
                    <button className="btn btn-primary" onClick={loadDashboardData}>
                        <i className="fas fa-sync-alt me-2"></i>
                        Refresh
                    </button>
                </div>
            </div>

            {/* Statistics Cards */}
            <div className="row g-3 mb-4">
                {/* Total Movies */}
                <div className="col-xl-3 col-md-6">
                    <div className="card border-start border-primary border-4 shadow-sm h-100">
                        <div className="card-body">
                            <div className="d-flex justify-content-between align-items-center">
                                <div>
                                    <div className="text-xs fw-bold text-primary text-uppercase mb-1">
                                        Total Movies
                                    </div>
                                    <div className="h5 mb-0 fw-bold text-gray-800">
                                        {stats?.total_movies?.toLocaleString() || 0}
                                    </div>
                                </div>
                                <div className="text-primary" style={{ fontSize: '2rem' }}>
                                    <i className="fas fa-film"></i>
                                </div>
                            </div>
                            <div className="mt-2">
                                <small className="text-muted">
                                    <i className="fas fa-calendar me-1"></i>
                                    This Year: {stats?.movies_this_year || 0}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Total Genres */}
                <div className="col-xl-3 col-md-6">
                    <div className="card border-start border-success border-4 shadow-sm h-100">
                        <div className="card-body">
                            <div className="d-flex justify-content-between align-items-center">
                                <div>
                                    <div className="text-xs fw-bold text-success text-uppercase mb-1">
                                        Total Genres
                                    </div>
                                    <div className="h5 mb-0 fw-bold text-gray-800">
                                        {stats?.total_genres || 0}
                                    </div>
                                </div>
                                <div className="text-success" style={{ fontSize: '2rem' }}>
                                    <i className="fas fa-layer-group"></i>
                                </div>
                            </div>
                            <div className="mt-2">
                                <small className="text-muted">
                                    <i className="fas fa-star me-1"></i>
                                    Avg Rating: {stats?.average_rating || 0}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Total Revenue */}
                <div className="col-xl-3 col-md-6">
                    <div className="card border-start border-info border-4 shadow-sm h-100">
                        <div className="card-body">
                            <div className="d-flex justify-content-between align-items-center">
                                <div>
                                    <div className="text-xs fw-bold text-info text-uppercase mb-1">
                                        Total Revenue
                                    </div>
                                    <div className="h5 mb-0 fw-bold text-gray-800">
                                        {stats?.formatted_revenue || '$0'}
                                    </div>
                                </div>
                                <div className="text-info" style={{ fontSize: '2rem' }}>
                                    <i className="fas fa-dollar-sign"></i>
                                </div>
                            </div>
                            <div className="mt-2">
                                <small className="text-muted">
                                    <i className="fas fa-chart-line me-1"></i>
                                    Profit: {stats?.formatted_profit || '$0'}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Profit Margin */}
                <div className="col-xl-3 col-md-6">
                    <div className="card border-start border-warning border-4 shadow-sm h-100">
                        <div className="card-body">
                            <div className="d-flex justify-content-between align-items-center">
                                <div>
                                    <div className="text-xs fw-bold text-warning text-uppercase mb-1">
                                        Profit Margin
                                    </div>
                                    <div className="h5 mb-0 fw-bold text-gray-800">
                                        {stats?.profit_margin || 0}%
                                    </div>
                                </div>
                                <div className="text-warning" style={{ fontSize: '2rem' }}>
                                    <i className="fas fa-percentage"></i>
                                </div>
                            </div>
                            <div className="mt-2">
                                <small className="text-muted">
                                    <i className="fas fa-money-bill-wave me-1"></i>
                                    Budget: {stats?.formatted_budget || '$0'}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Charts Row 1 */}
            <div className="row g-3 mb-4">
                {/* Pie Chart - Movies by Genre */}
                <div className="col-xl-6 col-lg-6">
                    <div className="card shadow-sm h-100">
                        <div className="card-header bg-primary text-white">
                            <h6 className="m-0 fw-bold">
                                <i className="fas fa-chart-pie me-2"></i>
                                Movies by Genre
                            </h6>
                        </div>
                        <div className="card-body">
                            <div style={{ height: '350px' }}>
                                <Pie data={pieChartData} options={pieOptions} />
                            </div>
                        </div>
                    </div>
                </div>

                {/* Column Chart - Movies by Date */}
                <div className="col-xl-6 col-lg-6">
                    <div className="card shadow-sm h-100">
                        <div className="card-header bg-info text-white">
                            <h6 className="m-0 fw-bold">
                                <i className="fas fa-chart-bar me-2"></i>
                                Movies Released by Month
                            </h6>
                        </div>
                        <div className="card-body">
                            <div style={{ height: '350px' }}>
                                <Bar data={columnChartData} options={columnOptions} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Charts Row 2 */}
            <div className="row g-3 mb-4">
                {/* Bar Chart - Movies by Year */}
                <div className="col-xl-6 col-lg-6">
                    <div className="card shadow-sm h-100">
                        <div className="card-header bg-success text-white">
                            <h6 className="m-0 fw-bold">
                                <i className="fas fa-chart-bar me-2"></i>
                                Movies & Ratings by Year
                            </h6>
                        </div>
                        <div className="card-body">
                            <div style={{ height: '350px' }}>
                                <Bar data={yearChartData} options={yearOptions} />
                            </div>
                        </div>
                    </div>
                </div>

                {/* Line Chart - Rating Distribution */}
                <div className="col-xl-6 col-lg-6">
                    <div className="card shadow-sm h-100">
                        <div className="card-header bg-warning text-white">
                            <h6 className="m-0 fw-bold">
                                <i className="fas fa-chart-line me-2"></i>
                                Rating Distribution
                            </h6>
                        </div>
                        <div className="card-body">
                            <div style={{ height: '350px' }}>
                                <Line data={ratingChartData} options={ratingOptions} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Featured Movies */}
            <div className="row g-3 mb-4">
                {/* Latest Movie */}
                {stats?.latest_movie && (
                    <div className="col-xl-3 col-md-6">
                        <div className="card shadow-sm h-100">
                            <div className="card-header bg-primary text-white">
                                <h6 className="m-0 fw-bold">
                                    <i className="fas fa-clock me-2"></i>
                                    Latest Movie
                                </h6>
                            </div>
                            <div className="card-body p-0">
                                <img 
                                    src={stats.latest_movie.poster_url || '/placeholder.jpg'} 
                                    alt={stats.latest_movie.title}
                                    className="card-img-top"
                                    style={{ height: '300px', objectFit: 'cover' }}
                                />
                                <div className="p-3">
                                    <h6 className="fw-bold mb-2">{stats.latest_movie.title}</h6>
                                    <div className="d-flex justify-content-between align-items-center">
                                        <span className="badge bg-primary">
                                            {stats.latest_movie.release_year}
                                        </span>
                                        <span className="badge bg-warning text-dark">
                                            <i className="fas fa-star me-1"></i>
                                            {stats.latest_movie.vote_average}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                )}

                {/* Highest Rated */}
                {stats?.highest_rated && (
                    <div className="col-xl-3 col-md-6">
                        <div className="card shadow-sm h-100">
                            <div className="card-header bg-success text-white">
                                <h6 className="m-0 fw-bold">
                                    <i className="fas fa-star me-2"></i>
                                    Highest Rated
                                </h6>
                            </div>
                            <div className="card-body p-0">
                                <img 
                                    src={stats.highest_rated.poster_url || '/placeholder.jpg'} 
                                    alt={stats.highest_rated.title}
                                    className="card-img-top"
                                    style={{ height: '300px', objectFit: 'cover' }}
                                />
                                <div className="p-3">
                                    <h6 className="fw-bold mb-2">{stats.highest_rated.title}</h6>
                                    <div className="d-flex justify-content-between align-items-center">
                                        <span className="badge bg-success">
                                            <i className="fas fa-star me-1"></i>
                                            {stats.highest_rated.vote_average}
                                        </span>
                                        <span className="text-muted small">
                                            {stats.highest_rated.vote_count.toLocaleString()} votes
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                )}

                {/* Most Popular */}
                {stats?.most_popular && (
                    <div className="col-xl-3 col-md-6">
                        <div className="card shadow-sm h-100">
                            <div className="card-header bg-info text-white">
                                <h6 className="m-0 fw-bold">
                                    <i className="fas fa-fire me-2"></i>
                                    Most Popular
                                </h6>
                            </div>
                            <div className="card-body p-0">
                                <img 
                                    src={stats.most_popular.poster_url || '/placeholder.jpg'} 
                                    alt={stats.most_popular.title}
                                    className="card-img-top"
                                    style={{ height: '300px', objectFit: 'cover' }}
                                />
                                <div className="p-3">
                                    <h6 className="fw-bold mb-2">{stats.most_popular.title}</h6>
                                    <div className="d-flex justify-content-between align-items-center">
                                        <span className="badge bg-info">
                                            <i className="fas fa-fire me-1"></i>
                                            {stats.most_popular.popularity.toFixed(1)}
                                        </span>
                                        <span className="badge bg-warning text-dark">
                                            <i className="fas fa-star me-1"></i>
                                            {stats.most_popular.vote_average}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                )}

                {/* Highest Grossing */}
                {stats?.highest_grossing && (
                    <div className="col-xl-3 col-md-6">
                        <div className="card shadow-sm h-100">
                            <div className="card-header bg-warning text-white">
                                <h6 className="m-0 fw-bold">
                                    <i className="fas fa-dollar-sign me-2"></i>
                                    Highest Grossing
                                </h6>
                            </div>
                            <div className="card-body p-0">
                                <img 
                                    src={stats.highest_grossing.poster_url || '/placeholder.jpg'} 
                                    alt={stats.highest_grossing.title}
                                    className="card-img-top"
                                    style={{ height: '300px', objectFit: 'cover' }}
                                />
                                <div className="p-3">
                                    <h6 className="fw-bold mb-2">{stats.highest_grossing.title}</h6>
                                    <div className="d-flex flex-column gap-1">
                                        <div className="d-flex justify-content-between">
                                            <small className="text-muted">Revenue:</small>
                                            <small className="fw-bold text-success">
                                                {stats.highest_grossing.formatted_revenue}
                                            </small>
                                        </div>
                                        <div className="d-flex justify-content-between">
                                            <small className="text-muted">Profit:</small>
                                            <small className="fw-bold text-primary">
                                                {stats.highest_grossing.formatted_profit}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                )}
            </div>

            {/* Top Lists */}
            <div className="row g-3 mb-4">
                {/* Top Rated Movies */}
                <div className="col-xl-6">
                    <div className="card shadow-sm">
                        <div className="card-header bg-success text-white">
                            <h6 className="m-0 fw-bold">
                                <i className="fas fa-trophy me-2"></i>
                                Top Rated Movies
                            </h6>
                        </div>
                        <div className="card-body p-0">
                            <div className="table-responsive">
                                <table className="table table-hover mb-0">
                                    <thead className="table-light">
                                        <tr>
                                            <th style={{ width: '50px' }}>#</th>
                                            <th>Title</th>
                                            <th style={{ width: '100px' }}>Rating</th>
                                            <th style={{ width: '100px' }}>Votes</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {topRated.map((movie, index) => (
                                            <tr key={movie.id}>
                                                <td className="fw-bold">{index + 1}</td>
                                                <td>
                                                    <div className="d-flex align-items-center">
                                                        <img 
                                                            src={movie.poster_url || '/placeholder.jpg'}
                                                            alt={movie.title}
                                                            style={{ width: '40px', height: '60px', objectFit: 'cover' }}
                                                            className="me-2 rounded"
                                                        />
                                                        <div>
                                                            <div className="fw-bold">{movie.title}</div>
                                                            <small className="text-muted">{movie.release_year}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span className={`badge bg-${movie.rating_color}`}>
                                                        <i className="fas fa-star me-1"></i>
                                                        {movie.vote_average}
                                                    </span>
                                                </td>
                                                <td className="text-muted">
                                                    {movie.vote_count.toLocaleString()}
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Most Popular Movies */}
                <div className="col-xl-6">
                    <div className="card shadow-sm">
                        <div className="card-header bg-info text-white">
                            <h6 className="m-0 fw-bold">
                                <i className="fas fa-fire me-2"></i>
                                Most Popular Movies
                            </h6>
                        </div>
                        <div className="card-body p-0">
                            <div className="table-responsive">
                                <table className="table table-hover mb-0">
                                    <thead className="table-light">
                                        <tr>
                                            <th style={{ width: '50px' }}>#</th>
                                            <th>Title</th>
                                            <th style={{ width: '120px' }}>Popularity</th>
                                            <th style={{ width: '100px' }}>Rating</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {mostPopular.map((movie, index) => (
                                            <tr key={movie.id}>
                                                <td className="fw-bold">{index + 1}</td>
                                                <td>
                                                    <div className="d-flex align-items-center">
                                                        <img 
                                                            src={movie.poster_url || '/placeholder.jpg'}
                                                            alt={movie.title}
                                                            style={{ width: '40px', height: '60px', objectFit: 'cover' }}
                                                            className="me-2 rounded"
                                                        />
                                                        <div>
                                                            <div className="fw-bold">{movie.title}</div>
                                                            <small className="text-muted">{movie.release_year}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span className="badge bg-info">
                                                        <i className="fas fa-fire me-1"></i>
                                                        {movie.popularity.toFixed(1)}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span className="badge bg-warning text-dark">
                                                        <i className="fas fa-star me-1"></i>
                                                        {movie.vote_average}
                                                    </span>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Sync Info */}
            {stats?.last_sync && (
                <div className="row g-3">
                    <div className="col-12">
                        <div className="card shadow-sm border-start border-primary border-4">
                            <div className="card-body">
                                <div className="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 className="mb-2">
                                            <i className={`fas ${stats.last_sync.status_icon} text-${stats.last_sync.status_color} me-2`}></i>
                                            Last Sync Status
                                        </h6>
                                        <p className="mb-0 text-muted">
                                            {stats.last_sync.time_ago} • 
                                            Created: {stats.last_sync.records_created} • 
                                            Updated: {stats.last_sync.records_updated} • 
                                            Success Rate: {stats.last_sync.success_rate}%
                                        </p>
                                    </div>
                                    <div className="text-end">
                                        <span className={`badge bg-${stats.last_sync.status_color} fs-6`}>
                                            {stats.last_sync.status.toUpperCase()}
                                        </span>
                                        <div className="mt-2">
                                            <small className="text-muted">
                                                Duration: {stats.last_sync.formatted_duration}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}

export default Dashboard;

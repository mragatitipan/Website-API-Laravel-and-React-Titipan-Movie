import React, { useState, useEffect } from 'react';
import { moviesAPI, getErrorMessage } from '../services/api';
import MovieFormModal from '../components/MovieFormModal';

function Movies() {
    // ========================================================================
    // STATES
    // ========================================================================
    const [movies, setMovies] = useState([]);
    const [genres, setGenres] = useState([]);
    const [years, setYears] = useState([]);
    const [loading, setLoading] = useState(true);
    const [pagination, setPagination] = useState({});
    
    // Filter states
    const [search, setSearch] = useState('');
    const [selectedGenre, setSelectedGenre] = useState('');
    const [selectedYear, setSelectedYear] = useState('');
    const [selectedMonth, setSelectedMonth] = useState('');
    const [minRating, setMinRating] = useState('');
    const [sortBy, setSortBy] = useState('updated_at');
    
    // Modal states
    const [showModal, setShowModal] = useState(false);
    const [modalMode, setModalMode] = useState('add');
    const [selectedMovie, setSelectedMovie] = useState(null);
    const [viewMode, setViewMode] = useState('grid');
    
    // Preview modal
    const [showPreview, setShowPreview] = useState(false);
    const [previewMovie, setPreviewMovie] = useState(null);
    
    // Selected movies for bulk operations
    const [selectedMovies, setSelectedMovies] = useState([]);

    // ========================================================================
    // LOAD DATA ON MOUNT
    // ========================================================================
    useEffect(() => {
        loadMovies();
        loadGenres();
        loadYears();
    }, []);

    // ========================================================================
    // AUTO-RELOAD WHEN FILTERS CHANGE
    // ========================================================================
    useEffect(() => {
        const timer = setTimeout(() => {
            if (!loading) {
                loadMovies(1);
            }
        }, 500);
        return () => clearTimeout(timer);
    }, [search, selectedGenre, selectedYear, selectedMonth, minRating, sortBy]);

    // ========================================================================
    // LOAD MOVIES
    // ========================================================================
    const loadMovies = async (page = 1) => {
        setLoading(true);
        try {
            const params = {
                page,
                per_page: 12,
                sort_by: sortBy,
                sort_order: 'desc',
            };

            if (search.trim()) params.search = search.trim();
            if (selectedGenre) params.genre = selectedGenre;
            if (selectedYear) params.year = selectedYear;
            if (selectedMonth) params.month = selectedMonth;
            if (minRating) params.min_rating = minRating;
            
            console.log('📥 Loading movies with params:', params);
            
            const response = await moviesAPI.getList(params);
            
            console.log('✅ Movies loaded:', response.data);
            
            if (response.data && response.data.data) {
                setMovies(Array.isArray(response.data.data) ? response.data.data : []);
                setPagination(response.data.meta || {});
            } else {
                setMovies([]);
                setPagination({});
            }
        } catch (error) {
            console.error('❌ Error loading movies:', error);
            alert(getErrorMessage(error));
            setMovies([]);
            setPagination({});
        } finally {
            setLoading(false);
        }
    };

    // ========================================================================
    // LOAD GENRES
    // ========================================================================
    const loadGenres = async () => {
        try {
            const response = await moviesAPI.getGenres();
            console.log('📥 Genres loaded:', response.data);
            
            if (response.data) {
                const genresData = response.data.data || response.data;
                
                if (Array.isArray(genresData)) {
                    setGenres(genresData);
                } else {
                    console.error('❌ Genres data is not an array:', genresData);
                    setGenres([]);
                }
            } else {
                setGenres([]);
            }
        } catch (error) {
            console.error('❌ Error loading genres:', error);
            setGenres([]);
        }
    };

    // ========================================================================
    // LOAD YEARS
    // ========================================================================
    const loadYears = async () => {
        try {
            const response = await moviesAPI.getYears();
            console.log('📥 Years loaded:', response.data);
            
            if (response.data && response.data.data) {
                setYears(Array.isArray(response.data.data) ? response.data.data : []);
            }
        } catch (error) {
            console.error('❌ Error loading years:', error);
            setYears([]);
        }
    };

    // ========================================================================
    // HELPER: PARSE GENRES
    // ========================================================================
    const parseGenres = (movie) => {
        if (movie.genres && Array.isArray(movie.genres) && movie.genres.length > 0) {
            return movie.genres.map(g => {
                if (typeof g === 'string') return g;
                if (g && typeof g === 'object' && g.name) return g.name;
                return String(g);
            }).filter(g => g);
        }
        
        if (movie.genre && typeof movie.genre === 'string') {
            return movie.genre.split(',').map(g => g.trim()).filter(g => g);
        }
        
        return [];
    };

    // ========================================================================
    // HELPER: GET POSTER URL
    // ========================================================================
    const getPosterUrl = (movie) => {
        if (movie.poster_url) return movie.poster_url;
        
        if (movie.poster_path) {
            if (movie.poster_path.startsWith('http')) {
                return movie.poster_path;
            }
            return `https://image.tmdb.org/t/p/w500${movie.poster_path}`;
        }
        
        return null;
    };

    // ========================================================================
    // HELPER: FORMAT DATE
    // ========================================================================
    const formatDate = (dateString) => {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric' 
        });
    };

    // ========================================================================
    // HELPER: GET RATING COLOR
    // ========================================================================
    const getRatingColor = (rating) => {
        if (!rating) return '#6c757d';
        if (rating >= 8) return '#28a745';
        if (rating >= 6) return '#ffc107';
        return '#dc3545';
    };

    // ========================================================================
    // RESET FILTERS
    // ========================================================================
    const resetFilters = () => {
        setSearch('');
        setSelectedGenre('');
        setSelectedYear('');
        setSelectedMonth('');
        setMinRating('');
        setSortBy('updated_at');
        setTimeout(() => loadMovies(1), 100);
    };

    // ========================================================================
    // HANDLE DELETE
    // ========================================================================
    const handleDelete = async (id) => {
        if (!confirm('⚠️ Are you sure you want to delete this movie?\n\nThis action cannot be undone.')) return;
        
        try {
            await moviesAPI.delete(id);
            alert('✅ Movie deleted successfully!');
            loadMovies(pagination.current_page || 1);
            setSelectedMovies(prev => prev.filter(movieId => movieId !== id));
        } catch (error) {
            console.error('❌ Error deleting movie:', error);
            alert('❌ ' + getErrorMessage(error));
        }
    };

    // ========================================================================
    // HANDLE BULK DELETE
    // ========================================================================
    const handleBulkDelete = async () => {
        if (selectedMovies.length === 0) {
            alert('⚠️ Please select movies to delete');
            return;
        }
        
        if (!confirm(`⚠️ Delete ${selectedMovies.length} selected movie(s)?\n\nThis action cannot be undone.`)) return;
        
        try {
            await moviesAPI.bulkDelete({ ids: selectedMovies });
            alert(`✅ ${selectedMovies.length} movie(s) deleted successfully!`);
            setSelectedMovies([]);
            loadMovies(pagination.current_page || 1);
        } catch (error) {
            console.error('❌ Error deleting movies:', error);
            alert('❌ ' + getErrorMessage(error));
        }
    };

    // ========================================================================
    // TOGGLE SELECT MOVIE
    // ========================================================================
    const toggleSelectMovie = (id) => {
        setSelectedMovies(prev => 
            prev.includes(id) 
                ? prev.filter(movieId => movieId !== id)
                : [...prev, id]
        );
    };

    // ========================================================================
    // SELECT ALL MOVIES
    // ========================================================================
    const toggleSelectAll = () => {
        if (Array.isArray(movies) && movies.length > 0) {
            if (selectedMovies.length === movies.length) {
                setSelectedMovies([]);
            } else {
                setSelectedMovies(movies.map(m => m.id));
            }
        }
    };

    // ========================================================================
    // EXPORT CSV
    // ========================================================================
    const handleExport = async () => {
        try {
            const response = await moviesAPI.exportCsv();
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', `movies_export_${Date.now()}.csv`);
            document.body.appendChild(link);
            link.click();
            link.remove();
            window.URL.revokeObjectURL(url);
            alert('✅ Export successful!');
        } catch (error) {
            console.error('❌ Error exporting:', error);
            alert('❌ ' + getErrorMessage(error));
        }
    };

    // ========================================================================
    // OPEN ADD MODAL
    // ========================================================================
    const openAddModal = () => {
        setModalMode('add');
        setSelectedMovie(null);
        setShowModal(true);
    };

    // ========================================================================
    // OPEN EDIT MODAL
    // ========================================================================
    const openEditModal = async (movie) => {
        try {
            console.log('📝 Opening edit modal for movie:', movie.id);
            const response = await moviesAPI.getById(movie.id);
            console.log('✅ Movie details loaded:', response.data);
            setModalMode('edit');
            setSelectedMovie(response.data.data);
            setShowModal(true);
        } catch (error) {
            console.error('❌ Error loading movie details:', error);
            alert('❌ ' + getErrorMessage(error));
        }
    };

    // ========================================================================
    // OPEN PREVIEW MODAL
    // ========================================================================
    const openPreview = async (movie) => {
        try {
            console.log('👁️ Opening preview for movie:', movie.id);
            const response = await moviesAPI.getById(movie.id);
            console.log('✅ Movie preview loaded:', response.data);
            setPreviewMovie(response.data.data);
            setShowPreview(true);
        } catch (error) {
            console.error('❌ Error loading movie preview:', error);
            alert('❌ ' + getErrorMessage(error));
        }
    };

    // ========================================================================
    // RENDER GRID VIEW
    // ========================================================================
    const renderGridView = () => (
        <div className="row g-4">
            {movies.map(movie => {
                const posterUrl = getPosterUrl(movie);
                const genresList = parseGenres(movie);
                const ratingColor = getRatingColor(movie.vote_average);
                
                return (
                    <div key={movie.id} className="col-xl-3 col-lg-4 col-md-6">
                        <div className="movie-card h-100">
                            {/* Checkbox */}
                            <div className="movie-checkbox">
                                <input
                                    type="checkbox"
                                    className="form-check-input"
                                    checked={selectedMovies.includes(movie.id)}
                                    onChange={(e) => {
                                        e.stopPropagation();
                                        toggleSelectMovie(movie.id);
                                    }}
                                />
                            </div>

                            {/* Poster */}
                            <div className="movie-poster" onClick={() => openPreview(movie)}>
                                {posterUrl ? (
                                    <img
                                        src={posterUrl}
                                        alt={movie.title}
                                        onError={(e) => {
                                            e.target.style.display = 'none';
                                            e.target.nextElementSibling.style.display = 'flex';
                                        }}
                                    />
                                ) : null}
                                <div className="movie-poster-placeholder" style={{ display: posterUrl ? 'none' : 'flex' }}>
                                    <i className="fas fa-film fa-4x"></i>
                                    <p className="mt-3 px-3 text-center">{movie.title}</p>
                                </div>

                                {/* Overlay */}
                                <div className="movie-overlay">
                                    <button className="btn btn-light btn-sm">
                                        <i className="fas fa-eye me-2"></i>
                                        View Details
                                    </button>
                                </div>

                                {/* Rating Badge */}
                                <div className="movie-rating" style={{ backgroundColor: ratingColor }}>
                                    <i className="fas fa-star me-1"></i>
                                    {movie.vote_average ? Number(movie.vote_average).toFixed(1) : 'N/A'}
                                </div>

                                {/* Status Badge */}
                                {movie.status && (
                                    <div className="movie-status">
                                        <span className="badge badge-gradient">
                                            {movie.status}
                                        </span>
                                    </div>
                                )}
                            </div>

                            {/* Card Body */}
                            <div className="movie-body">
                                {/* Title */}
                                <h6 className="movie-title" title={movie.title}>
                                    {movie.title || 'Untitled'}
                                </h6>

                                {/* Genres */}
                                <div className="movie-genres">
                                    {genresList.length > 0 ? (
                                        genresList.slice(0, 3).map((genreName, idx) => (
                                            <span key={idx} className="genre-badge">
                                                {genreName}
                                            </span>
                                        ))
                                    ) : (
                                        <span className="genre-badge empty">No Genre</span>
                                    )}
                                    {genresList.length > 3 && (
                                        <span className="genre-badge more">
                                            +{genresList.length - 3}
                                        </span>
                                    )}
                                </div>

                                {/* Meta Info */}
                                <div className="movie-meta">
                                    <div className="meta-item">
                                        <i className="fas fa-calendar"></i>
                                        <span>{movie.release_year || formatDate(movie.release_date)}</span>
                                    </div>
                                    {movie.runtime && (
                                        <div className="meta-item">
                                            <i className="fas fa-clock"></i>
                                            <span>{movie.formatted_runtime}</span>
                                        </div>
                                    )}
                                </div>

                                {/* Stats */}
                                <div className="movie-stats">
                                    <div className="stat-item">
                                        <i className="fas fa-users"></i>
                                        <span>{movie.vote_count ? movie.vote_count.toLocaleString() : 0}</span>
                                    </div>
                                    <div className="stat-item">
                                        <i className="fas fa-fire"></i>
                                        <span>{movie.popularity ? Number(movie.popularity).toFixed(0) : 0}</span>
                                    </div>
                                </div>

                                {/* Actions */}
                                <div className="movie-actions">
                                    <button
                                        className="btn btn-gradient btn-sm flex-fill"
                                        onClick={() => openEditModal(movie)}
                                    >
                                        <i className="fas fa-edit me-1"></i>
                                        Edit
                                    </button>
                                    <button
                                        className="btn btn-outline-danger btn-sm"
                                        onClick={() => handleDelete(movie.id)}
                                    >
                                        <i className="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                );
            })}
        </div>
    );

    // ========================================================================
    // RENDER TABLE VIEW
    // ========================================================================
    const renderTableView = () => (
        <div className="table-responsive">
            <table className="table table-hover align-middle mb-0 movie-table">
                <thead>
                    <tr>
                        <th width="50" className="text-center">
                            <input
                                type="checkbox"
                                className="form-check-input"
                                checked={movies.length > 0 && selectedMovies.length === movies.length}
                                onChange={toggleSelectAll}
                            />
                        </th>
                        <th width="80">POSTER</th>
                        <th>TITLE</th>
                        <th width="200">GENRE</th>
                        <th width="120">RELEASE</th>
                        <th width="100">RATING</th>
                        <th width="100">STATUS</th>
                        <th width="180">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    {movies.map(movie => {
                        const posterUrl = getPosterUrl(movie);
                        const genresList = parseGenres(movie);
                        const ratingColor = getRatingColor(movie.vote_average);
                        
                        return (
                            <tr key={movie.id}>
                                <td className="text-center">
                                    <input
                                        type="checkbox"
                                        className="form-check-input"
                                        checked={selectedMovies.includes(movie.id)}
                                        onChange={() => toggleSelectMovie(movie.id)}
                                    />
                                </td>
                                <td>
                                    <div className="table-poster" onClick={() => openPreview(movie)}>
                                        {posterUrl ? (
                                            <img src={posterUrl} alt={movie.title} />
                                        ) : (
                                            <div className="table-poster-placeholder">
                                                <i className="fas fa-film"></i>
                                            </div>
                                        )}
                                    </div>
                                </td>
                                <td>
                                    <div className="fw-bold mb-1">{movie.title || 'Untitled'}</div>
                                    {movie.original_title && movie.original_title !== movie.title && (
                                        <small className="text-muted d-block mb-1">
                                            {movie.original_title}
                                        </small>
                                    )}
                                    {movie.overview && (
                                        <small className="text-muted d-block table-overview">
                                            {movie.overview}
                                        </small>
                                    )}
                                </td>
                                <td>
                                    <div className="d-flex flex-wrap gap-1">
                                        {genresList.length > 0 ? (
                                            genresList.slice(0, 2).map((genreName, idx) => (
                                                <span key={idx} className="genre-badge small">
                                                    {genreName}
                                                </span>
                                            ))
                                        ) : (
                                            <span className="genre-badge small empty">No Genre</span>
                                        )}
                                        {genresList.length > 2 && (
                                            <span className="genre-badge small more">
                                                +{genresList.length - 2}
                                            </span>
                                        )}
                                    </div>
                                </td>
                                <td>
                                    <div className="fw-semibold">{movie.release_year || '-'}</div>
                                    <small className="text-muted">{formatDate(movie.release_date)}</small>
                                </td>
                                <td>
                                    <div 
                                        className="rating-badge" 
                                        style={{ backgroundColor: ratingColor }}
                                    >
                                        <i className="fas fa-star me-1"></i>
                                        {movie.vote_average ? Number(movie.vote_average).toFixed(1) : 'N/A'}
                                    </div>
                                    <small className="text-muted d-block mt-1">
                                        {movie.vote_count ? movie.vote_count.toLocaleString() : 0} votes
                                    </small>
                                </td>
                                <td>
                                    {movie.status ? (
                                        <span className="badge badge-gradient">
                                            {movie.status}
                                        </span>
                                    ) : (
                                        <span className="text-muted">-</span>
                                    )}
                                </td>
                                <td>
                                    <div className="btn-group" role="group">
                                        <button
                                            className="btn btn-sm btn-outline-primary"
                                            onClick={() => openPreview(movie)}
                                            title="View Details"
                                        >
                                            <i className="fas fa-eye"></i>
                                        </button>
                                        <button
                                            className="btn btn-sm btn-gradient"
                                            onClick={() => openEditModal(movie)}
                                            title="Edit Movie"
                                        >
                                            <i className="fas fa-edit"></i>
                                        </button>
                                        <button
                                            className="btn btn-sm btn-outline-danger"
                                            onClick={() => handleDelete(movie.id)}
                                            title="Delete Movie"
                                        >
                                            <i className="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        );
                    })}
                </tbody>
            </table>
        </div>
    );

    // ========================================================================
    // RENDER PREVIEW MODAL
    // ========================================================================
    const renderPreviewModal = () => {
        if (!showPreview || !previewMovie) return null;

        const posterUrl = getPosterUrl(previewMovie);
        const backdropUrl = previewMovie.backdrop_url || (previewMovie.backdrop_path ? `https://image.tmdb.org/t/p/original${previewMovie.backdrop_path}` : null);
        const genresList = parseGenres(previewMovie);
        const ratingColor = getRatingColor(previewMovie.vote_average);

        return (
            <div className="modal show d-block preview-modal" onClick={() => setShowPreview(false)}>
                <div className="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" onClick={(e) => e.stopPropagation()}>
                    <div className="modal-content border-0 shadow-lg">
                        {/* Backdrop Header */}
                        {backdropUrl && (
                            <div className="preview-backdrop" style={{ backgroundImage: `url(${backdropUrl})` }}>
                                <div className="preview-backdrop-overlay"></div>
                                <button 
                                    className="btn-close btn-close-white position-absolute top-0 end-0 m-3" 
                                    onClick={() => setShowPreview(false)}
                                ></button>
                            </div>
                        )}

                        <div className="modal-body p-0">
                            <div className="row g-0">
                                {/* Poster */}
                                <div className="col-md-4 p-4">
                                    <div className="preview-poster">
                                        {posterUrl ? (
                                            <img src={posterUrl} alt={previewMovie.title} />
                                        ) : (
                                            <div className="preview-poster-placeholder">
                                                <i className="fas fa-film fa-5x"></i>
                                            </div>
                                        )}
                                    </div>
                                </div>

                                {/* Details */}
                                <div className="col-md-8 p-4">
                                    {/* Title */}
                                    <h2 className="fw-bold mb-2" style={{ color: '#0600AB' }}>
                                        {previewMovie.title}
                                    </h2>
                                    {previewMovie.original_title && previewMovie.original_title !== previewMovie.title && (
                                        <h5 className="text-muted mb-3">{previewMovie.original_title}</h5>
                                    )}

                                    {/* Tagline */}
                                    {previewMovie.tagline && (
                                        <p className="fst-italic text-muted mb-3">"{previewMovie.tagline}"</p>
                                    )}

                                    {/* Meta Info */}
                                    <div className="d-flex flex-wrap gap-3 mb-3">
                                        <div className="preview-meta-item">
                                            <i className="fas fa-calendar me-2" style={{ color: '#977DFF' }}></i>
                                            <strong>{previewMovie.release_date_formatted || formatDate(previewMovie.release_date)}</strong>
                                        </div>
                                        {previewMovie.runtime && (
                                            <div className="preview-meta-item">
                                                <i className="fas fa-clock me-2" style={{ color: '#0033FF' }}></i>
                                                <strong>{previewMovie.formatted_runtime}</strong>
                                            </div>
                                        )}
                                        <div className="preview-meta-item">
                                            <i className="fas fa-star me-2" style={{ color: ratingColor }}></i>
                                            <strong>{previewMovie.vote_average ? Number(previewMovie.vote_average).toFixed(1) : 'N/A'}</strong>
                                            <span className="text-muted ms-1">({previewMovie.vote_count?.toLocaleString()} votes)</span>
                                        </div>
                                    </div>

                                    {/* Genres */}
                                    <div className="mb-3">
                                        {genresList.map((genre, idx) => (
                                            <span key={idx} className="badge badge-gradient me-1 mb-1">
                                                {genre}
                                            </span>
                                        ))}
                                    </div>

                                    {/* Overview */}
                                    {previewMovie.overview && (
                                        <div className="mb-4">
                                            <h6 className="fw-bold mb-2" style={{ color: '#0600AB' }}>Overview</h6>
                                            <p className="text-muted">{previewMovie.overview}</p>
                                        </div>
                                    )}

                                    {/* Additional Info */}
                                    <div className="row g-3">
                                        {/* Status */}
                                        {previewMovie.status && (
                                            <div className="col-md-6">
                                                <div className="preview-info-card">
                                                    <i className="fas fa-flag me-2" style={{ color: '#977DFF' }}></i>
                                                    <div>
                                                        <small className="text-muted d-block">Status</small>
                                                        <strong>{previewMovie.status}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        )}

                                        {/* Language */}
                                        {previewMovie.original_language && (
                                            <div className="col-md-6">
                                                <div className="preview-info-card">
                                                    <i className="fas fa-language me-2" style={{ color: '#0033FF' }}></i>
                                                    <div>
                                                        <small className="text-muted d-block">Language</small>
                                                        <strong>{previewMovie.original_language.toUpperCase()}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        )}

                                        {/* Budget */}
                                        {previewMovie.budget > 0 && (
                                            <div className="col-md-6">
                                                <div className="preview-info-card">
                                                    <i className="fas fa-money-bill-wave me-2" style={{ color: '#977DFF' }}></i>
                                                    <div>
                                                        <small className="text-muted d-block">Budget</small>
                                                        <strong>{previewMovie.formatted_budget}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        )}

                                        {/* Revenue */}
                                        {previewMovie.revenue > 0 && (
                                            <div className="col-md-6">
                                                <div className="preview-info-card">
                                                    <i className="fas fa-chart-line me-2" style={{ color: '#0033FF' }}></i>
                                                    <div>
                                                        <small className="text-muted d-block">Revenue</small>
                                                        <strong>{previewMovie.formatted_revenue}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        )}

                                        {/* Popularity */}
                                        {previewMovie.popularity && (
                                            <div className="col-md-6">
                                                <div className="preview-info-card">
                                                    <i className="fas fa-fire me-2" style={{ color: '#977DFF' }}></i>
                                                    <div>
                                                        <small className="text-muted d-block">Popularity</small>
                                                        <strong>{Number(previewMovie.popularity).toFixed(1)}</strong>
                                                    </div>
                                                </div>
                                            </div>
                                        )}
                                    </div>

                                    {/* External Links */}
                                    <div className="mt-4 d-flex gap-2 flex-wrap">
                                        {previewMovie.homepage && (
                                            <a 
                                                href={previewMovie.homepage} 
                                                target="_blank" 
                                                rel="noopener noreferrer"
                                                className="btn btn-outline-primary btn-sm"
                                            >
                                                <i className="fas fa-home me-2"></i>
                                                Official Website
                                            </a>
                                        )}
                                        {previewMovie.imdb_url && (
                                            <a 
                                                href={previewMovie.imdb_url} 
                                                target="_blank" 
                                                rel="noopener noreferrer"
                                                className="btn btn-outline-warning btn-sm"
                                            >
                                                <i className="fab fa-imdb me-2"></i>
                                                IMDB
                                            </a>
                                        )}
                                        {previewMovie.tmdb_url && (
                                            <a 
                                                href={previewMovie.tmdb_url} 
                                                target="_blank" 
                                                rel="noopener noreferrer"
                                                className="btn btn-outline-info btn-sm"
                                            >
                                                <i className="fas fa-film me-2"></i>
                                                TMDB
                                            </a>
                                        )}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Footer Actions */}
                        <div className="modal-footer border-top">
                            <button 
                                className="btn btn-secondary" 
                                onClick={() => setShowPreview(false)}
                            >
                                <i className="fas fa-times me-2"></i>
                                Close
                            </button>
                            <button 
                                className="btn btn-gradient" 
                                onClick={() => {
                                    setShowPreview(false);
                                    openEditModal(previewMovie);
                                }}
                            >
                                <i className="fas fa-edit me-2"></i>
                                Edit Movie
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        );
    };

    // ========================================================================
    // RENDER
    // ========================================================================
    return (
        <div className="movies-page">
            {/* Header */}
            <div className="page-header">
                <div className="container-fluid">
                    <div className="row align-items-center">
                        <div className="col-md-6">
                            <h1 className="page-title">
                                <i className="fas fa-film me-3"></i>
                                Movies Management
                            </h1>
                            <p className="page-subtitle">
                                <i className="fas fa-database me-2"></i>
                                Total: <strong>{pagination.total || 0}</strong> movies
                                {genres.length > 0 && (
                                    <span className="ms-3">
                                        <i className="fas fa-tags me-2"></i>
                                        <strong>{genres.length}</strong> genres
                                    </span>
                                )}
                            </p>
                        </div>
                        <div className="col-md-6 text-md-end">
                            <div className="d-flex gap-2 justify-content-md-end flex-wrap">
                                <button className="btn btn-gradient" onClick={openAddModal}>
                                    <i className="fas fa-plus-circle me-2"></i>
                                    Add Movie
                                </button>
                                <button className="btn btn-outline-light" onClick={handleExport}>
                                    <i className="fas fa-file-export me-2"></i>
                                    Export
                                </button>
                                <div className="btn-group">
                                    <button 
                                        className={`btn ${viewMode === 'grid' ? 'btn-light' : 'btn-outline-light'}`}
                                        onClick={() => setViewMode('grid')}
                                    >
                                        <i className="fas fa-th"></i>
                                    </button>
                                    <button 
                                        className={`btn ${viewMode === 'table' ? 'btn-light' : 'btn-outline-light'}`}
                                        onClick={() => setViewMode('table')}
                                    >
                                        <i className="fas fa-list"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div className="container-fluid p-4">
                {/* Bulk Actions */}
                {selectedMovies.length > 0 && (
                    <div className="bulk-actions-bar">
                        <div className="d-flex justify-content-between align-items-center">
                            <div>
                                <i className="fas fa-check-circle me-2"></i>
                                <strong>{selectedMovies.length}</strong> movie(s) selected
                            </div>
                            <div className="d-flex gap-2">
                                <button 
                                    className="btn btn-sm btn-outline-light" 
                                    onClick={() => setSelectedMovies([])}
                                >
                                    <i className="fas fa-times me-1"></i>
                                    Clear
                                </button>
                                <button className="btn btn-sm btn-danger" onClick={handleBulkDelete}>
                                    <i className="fas fa-trash-alt me-1"></i>
                                    Delete Selected
                                </button>
                            </div>
                        </div>
                    </div>
                )}

                {/* Filters */}
                <div className="filters-card">
                    <div className="filters-header">
                        <h5 className="mb-0">
                            <i className="fas fa-filter me-2"></i>
                            Filters & Search
                        </h5>
                    </div>
                    <div className="filters-body">
                        <div className="row g-3">
                            {/* Search */}
                            <div className="col-md-4">
                                <label className="filter-label">
                                    <i className="fas fa-search me-1"></i>
                                    SEARCH
                                </label>
                                <input
                                    type="text"
                                    className="form-control"
                                    placeholder="Type movie title..."
                                    value={search}
                                    onChange={(e) => setSearch(e.target.value)}
                                />
                            </div>

                            {/* Genre */}
                            <div className="col-md-2">
                                <label className="filter-label">
                                    <i className="fas fa-tags me-1"></i>
                                    GENRE
                                </label>
                                <select
                                    className="form-select"
                                    value={selectedGenre}
                                    onChange={(e) => setSelectedGenre(e.target.value)}
                                >
                                    <option value="">All Genres</option>
                                    {genres.map((genre, index) => (
                                        <option key={genre.id || index} value={genre.name}>
                                            {genre.name}
                                        </option>
                                    ))}
                                </select>
                            </div>

                            {/* Year */}
                            <div className="col-md-2">
                                <label className="filter-label">
                                    <i className="fas fa-calendar-alt me-1"></i>
                                    YEAR
                                </label>
                                <select
                                    className="form-select"
                                    value={selectedYear}
                                    onChange={(e) => setSelectedYear(e.target.value)}
                                >
                                    <option value="">All Years</option>
                                    {years.map(year => (
                                        <option key={year} value={year}>
                                            {year}
                                        </option>
                                    ))}
                                </select>
                            </div>

                            {/* Month */}
                            <div className="col-md-2">
                                <label className="filter-label">
                                    <i className="fas fa-calendar me-1"></i>
                                    MONTH
                                </label>
                                <select
                                    className="form-select"
                                    value={selectedMonth}
                                    onChange={(e) => setSelectedMonth(e.target.value)}
                                    disabled={!selectedYear}
                                >
                                    <option value="">All Months</option>
                                    {['January', 'February', 'March', 'April', 'May', 'June', 
                                      'July', 'August', 'September', 'October', 'November', 'December'].map((month, idx) => (
                                        <option key={idx + 1} value={idx + 1}>{month}</option>
                                    ))}
                                </select>
                            </div>

                            {/* Rating */}
                            <div className="col-md-2">
                                <label className="filter-label">
                                    <i className="fas fa-star me-1"></i>
                                    MIN RATING
                                </label>
                                <select
                                    className="form-select"
                                    value={minRating}
                                    onChange={(e) => setMinRating(e.target.value)}
                                >
                                    <option value="">All Ratings</option>
                                    <option value="9">9+ Masterpiece</option>
                                    <option value="8">8+ Excellent</option>
                                    <option value="7">7+ Good</option>
                                    <option value="6">6+ Above Average</option>
                                    <option value="5">5+ Average</option>
                                </select>
                            </div>

                            {/* Sort */}
                            <div className="col-md-3">
                                <label className="filter-label">
                                    <i className="fas fa-sort me-1"></i>
                                    SORT BY
                                </label>
                                <select
                                    className="form-select"
                                    value={sortBy}
                                    onChange={(e) => setSortBy(e.target.value)}
                                >
                                    <option value="updated_at">Last Updated</option>
                                    <option value="created_at">Recently Added</option>
                                    <option value="release_date">Release Date</option>
                                    <option value="vote_average">Rating</option>
                                    <option value="popularity">Popularity</option>
                                    <option value="vote_count">Most Voted</option>
                                    <option value="title">Title (A-Z)</option>
                                </select>
                            </div>

                            {/* Reset */}
                            <div className="col-md-9">
                                <label className="filter-label d-block">&nbsp;</label>
                                <button 
                                    className="btn btn-outline-secondary"
                                    onClick={resetFilters}
                                >
                                    <i className="fas fa-redo me-2"></i>
                                    Reset Filters
                                </button>
                                {(search || selectedGenre || selectedYear || selectedMonth || minRating) && (
                                    <span className="ms-3 badge badge-gradient">
                                        <i className="fas fa-check-circle me-1"></i>
                                        Filters Active
                                    </span>
                                )}
                            </div>
                        </div>
                    </div>
                </div>

                {/* Movies Content */}
                <div className="movies-content">
                    {loading ? (
                        <div className="loading-state">
                            <div className="spinner-border" style={{ width: '3rem', height: '3rem', color: '#977DFF' }}>
                                <span className="visually-hidden">Loading...</span>
                            </div>
                            <p className="mt-3">Loading movies...</p>
                        </div>
                    ) : !Array.isArray(movies) || movies.length === 0 ? (
                        <div className="empty-state">
                            <i className="fas fa-inbox fa-5x mb-3"></i>
                            <h4>No movies found</h4>
                            <p>
                                {search || selectedGenre || selectedYear || selectedMonth || minRating
                                    ? 'Try adjusting your filters'
                                    : 'Start by adding your first movie'}
                            </p>
                            <button className="btn btn-gradient mt-3" onClick={openAddModal}>
                                <i className="fas fa-plus me-2"></i>
                                Add Your First Movie
                            </button>
                        </div>
                    ) : (
                        <>
                            {viewMode === 'grid' ? renderGridView() : renderTableView()}

                            {/* Pagination */}
                            {pagination && pagination.last_page > 1 && (
                                <div className="pagination-wrapper">
                                    <nav>
                                        <ul className="pagination justify-content-center mb-3">
                                            <li className={`page-item ${pagination.current_page === 1 ? 'disabled' : ''}`}>
                                                <button
                                                    className="page-link"
                                                    onClick={() => loadMovies(pagination.current_page - 1)}
                                                    disabled={pagination.current_page === 1}
                                                >
                                                    <i className="fas fa-chevron-left"></i>
                                                </button>
                                            </li>
                                            
                                            {(() => {
                                                const pages = [];
                                                const maxPages = 10;
                                                let startPage = Math.max(1, pagination.current_page - Math.floor(maxPages / 2));
                                                let endPage = Math.min(pagination.last_page, startPage + maxPages - 1);
                                                
                                                if (endPage - startPage < maxPages - 1) {
                                                    startPage = Math.max(1, endPage - maxPages + 1);
                                                }
                                                
                                                for (let i = startPage; i <= endPage; i++) {
                                                    pages.push(
                                                        <li key={i} className={`page-item ${pagination.current_page === i ? 'active' : ''}`}>
                                                            <button
                                                                className="page-link"
                                                                onClick={() => loadMovies(i)}
                                                            >
                                                                {i}
                                                            </button>
                                                        </li>
                                                    );
                                                }
                                                return pages;
                                            })()}
                                            
                                            <li className={`page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}`}>
                                                <button
                                                    className="page-link"
                                                    onClick={() => loadMovies(pagination.current_page + 1)}
                                                    disabled={pagination.current_page === pagination.last_page}
                                                >
                                                    <i className="fas fa-chevron-right"></i>
                                                </button>
                                            </li>
                                        </ul>
                                    </nav>
                                    <div className="text-center">
                                        <small className="text-muted">
                                            Page <strong>{pagination.current_page}</strong> of <strong>{pagination.last_page}</strong>
                                            {' '}({pagination.total} total movies)
                                        </small>
                                    </div>
                                </div>
                            )}
                        </>
                    )}
                </div>
            </div>

            {/* Movie Form Modal */}
            <MovieFormModal
                show={showModal}
                mode={modalMode}
                movie={selectedMovie}
                genres={genres}
                onClose={() => {
                    setShowModal(false);
                    setSelectedMovie(null);
                }}
                onSuccess={() => {
                    setShowModal(false);
                    setSelectedMovie(null);
                    loadMovies(pagination.current_page || 1);
                    loadGenres();
                }}
            />

            {/* Preview Modal */}
            {renderPreviewModal()}

            {/* ============================================================ */}
            {/* CUSTOM STYLES */}
            {/* ============================================================ */}
            <style>{`
                /* Gradient Colors: #977DFF, #0033FF, #0600AB, #00033D */
                
                .movies-page {
                    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                    min-height: 100vh;
                }

                /* Page Header */
                .page-header {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 50%, #0600AB 100%);
                    color: white;
                    padding: 2rem 0;
                    margin-bottom: 2rem;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
                }

                .page-title {
                    font-size: 2rem;
                    font-weight: 700;
                    margin-bottom: 0.5rem;
                }

                .page-subtitle {
                    font-size: 1rem;
                    opacity: 0.9;
                    margin: 0;
                }

                /* Gradient Button */
                .btn-gradient {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 100%);
                    color: white;
                    border: none;
                    font-weight: 600;
                    transition: all 0.3s ease;
                }

                .btn-gradient:hover {
                    background: linear-gradient(135deg, #0600AB 0%, #00033D 100%);
                    transform: translateY(-2px);
                    box-shadow: 0 4px 12px rgba(151, 125, 255, 0.4);
                    color: white;
                }

                /* Badge Gradient */
                .badge-gradient {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 100%);
                    color: white;
                }

                /* Bulk Actions Bar */
                .bulk-actions-bar {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 100%);
                    color: white;
                    padding: 1rem 1.5rem;
                    border-radius: 10px;
                    margin-bottom: 1.5rem;
                    box-shadow: 0 4px 12px rgba(151, 125, 255, 0.3);
                }

                /* Filters Card */
                .filters-card {
                    background: white;
                    border-radius: 15px;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                    margin-bottom: 2rem;
                    overflow: hidden;
                }

                .filters-header {
                    background: linear-gradient(135deg, #f0ebff 0%, #e6f2ff 100%);
                    padding: 1.25rem 1.5rem;
                    border-bottom: 2px solid #977DFF;
                }

                .filters-header h5 {
                    color: #0600AB;
                    font-weight: 700;
                }

                .filters-body {
                    padding: 1.5rem;
                }

                .filter-label {
                    font-size: 0.75rem;
                    font-weight: 700;
                    color: #0600AB;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                    margin-bottom: 0.5rem;
                }

                .form-control:focus,
                .form-select:focus {
                    border-color: #977DFF;
                    box-shadow: 0 0 0 0.25rem rgba(151, 125, 255, 0.25);
                }

                /* Movies Content */
                .movies-content {
                    background: white;
                    border-radius: 15px;
                    padding: 2rem;
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
                }

                /* Movie Card (Grid View) */
                .movie-card {
                    background: white;
                    border-radius: 12px;
                    overflow: hidden;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                    transition: all 0.3s ease;
                    position: relative;
                }

                .movie-card:hover {
                    transform: translateY(-8px);
                    box-shadow: 0 12px 24px rgba(151, 125, 255, 0.3);
                }

                .movie-checkbox {
                    position: absolute;
                    top: 10px;
                    left: 10px;
                    z-index: 10;
                }

                .movie-checkbox .form-check-input {
                    width: 22px;
                    height: 22px;
                    cursor: pointer;
                    background-color: white;
                    border: 2px solid #977DFF;
                }

                .movie-checkbox .form-check-input:checked {
                    background-color: #977DFF;
                    border-color: #977DFF;
                }

                .movie-poster {
                    height: 400px;
                    position: relative;
                    overflow: hidden;
                    cursor: pointer;
                }

                .movie-poster img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    transition: transform 0.3s ease;
                }

                .movie-card:hover .movie-poster img {
                    transform: scale(1.05);
                }

                .movie-poster-placeholder {
                    width: 100%;
                    height: 100%;
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 100%);
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    color: white;
                }

                .movie-overlay {
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0, 0, 0, 0.8);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    opacity: 0;
                    transition: opacity 0.3s ease;
                }

                .movie-card:hover .movie-overlay {
                    opacity: 1;
                }

                .movie-rating {
                    position: absolute;
                    top: 10px;
                    right: 10px;
                    padding: 0.5rem 0.75rem;
                    border-radius: 8px;
                    color: white;
                    font-weight: 700;
                    font-size: 0.9rem;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
                }

                .movie-status {
                    position: absolute;
                    bottom: 10px;
                    left: 10px;
                }

                .movie-body {
                    padding: 1.25rem;
                }

                .movie-title {
                    font-size: 1rem;
                    font-weight: 700;
                    color: #0600AB;
                    min-height: 48px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    display: -webkit-box;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                    line-height: 1.4;
                    margin-bottom: 0.75rem;
                }

                .movie-genres {
                    min-height: 32px;
                    margin-bottom: 0.75rem;
                    display: flex;
                    flex-wrap: wrap;
                    gap: 0.25rem;
                }

                .genre-badge {
                    background: linear-gradient(135deg, #f0ebff 0%, #e6f2ff 100%);
                    color: #0600AB;
                    padding: 0.25rem 0.6rem;
                    border-radius: 6px;
                    font-size: 0.7rem;
                    font-weight: 600;
                    display: inline-block;
                }

                .genre-badge.empty {
                    background: #f5f5f5;
                    color: #999;
                }

                .genre-badge.more {
                    background: #e9ecef;
                    color: #666;
                }

                .movie-meta {
                    display: flex;
                    gap: 1rem;
                    margin-bottom: 0.75rem;
                    padding-bottom: 0.75rem;
                    border-bottom: 1px solid #e9ecef;
                }

                .meta-item {
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    font-size: 0.85rem;
                    color: #666;
                }

                .meta-item i {
                    color: #977DFF;
                }

                .movie-stats {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 1rem;
                    padding-bottom: 1rem;
                    border-bottom: 1px solid #e9ecef;
                }

                .stat-item {
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                    font-size: 0.85rem;
                    color: #666;
                }

                .stat-item i {
                    color: #0033FF;
                }

                .movie-actions {
                    display: flex;
                    gap: 0.5rem;
                }

                /* Table View */
                .movie-table thead {
                    background: linear-gradient(135deg, #f0ebff 0%, #e6f2ff 100%);
                    color: #0600AB;
                    font-weight: 700;
                    font-size: 0.8rem;
                }

                .movie-table tbody tr {
                    transition: all 0.2s ease;
                }

                .movie-table tbody tr:hover {
                    background: linear-gradient(135deg, #f0ebff 0%, #e6f2ff 100%);
                    transform: scale(1.01);
                }

                .table-poster {
                    width: 50px;
                    height: 75px;
                    border-radius: 6px;
                    overflow: hidden;
                    cursor: pointer;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                    transition: transform 0.2s ease;
                }

                .table-poster:hover {
                    transform: scale(1.1);
                }

                .table-poster img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }

                .table-poster-placeholder {
                    width: 100%;
                    height: 100%;
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 100%);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    color: white;
                }

                .table-overview {
                    max-width: 300px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                }

                .rating-badge {
                    padding: 0.4rem 0.6rem;
                    border-radius: 6px;
                    color: white;
                    font-weight: 700;
                    font-size: 0.85rem;
                    display: inline-block;
                }

                /* Preview Modal */
                .preview-modal {
                    background: rgba(0, 0, 0, 0.85);
                }

                .preview-backdrop {
                    height: 300px;
                    background-size: cover;
                    background-position: center;
                    position: relative;
                }

                .preview-backdrop-overlay {
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(255,255,255,1) 100%);
                }

                .preview-poster {
                    border-radius: 12px;
                    overflow: hidden;
                    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
                }

                .preview-poster img {
                    width: 100%;
                    height: auto;
                }

                .preview-poster-placeholder {
                    width: 100%;
                    aspect-ratio: 2/3;
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 100%);
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    color: white;
                }

                .preview-meta-item {
                    display: flex;
                    align-items: center;
                }

                .preview-info-card {
                    background: linear-gradient(135deg, #f0ebff 0%, #e6f2ff 100%);
                    padding: 1rem;
                    border-radius: 10px;
                    display: flex;
                    align-items: center;
                }

                /* Loading & Empty States */
                .loading-state,
                .empty-state {
                    text-align: center;
                    padding: 4rem 2rem;
                    color: #666;
                }

                .loading-state i,
                .empty-state i {
                    color: #977DFF;
                }

                /* Pagination */
                .pagination-wrapper {
                    margin-top: 2rem;
                    padding-top: 2rem;
                    border-top: 2px solid #e9ecef;
                }

                .pagination .page-link {
                    color: #0600AB;
                    border-color: #e9ecef;
                }
                                .pagination .page-item.active .page-link {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 100%);
                    border-color: #977DFF;
                    color: white;
                }

                .pagination .page-link:hover {
                    background: linear-gradient(135deg, #f0ebff 0%, #e6f2ff 100%);
                    border-color: #977DFF;
                    color: #0600AB;
                }

                /* Responsive */
                @media (max-width: 768px) {
                    .page-title {
                        font-size: 1.5rem;
                    }

                    .movie-card {
                        margin-bottom: 1rem;
                    }

                    .filters-body {
                        padding: 1rem;
                    }

                    .movies-content {
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

                .movie-card {
                    animation: fadeIn 0.3s ease;
                }

                /* Scrollbar */
                ::-webkit-scrollbar {
                    width: 10px;
                    height: 10px;
                }

                ::-webkit-scrollbar-track {
                    background: #f1f1f1;
                }

                ::-webkit-scrollbar-thumb {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 100%);
                    border-radius: 5px;
                }

                ::-webkit-scrollbar-thumb:hover {
                    background: linear-gradient(135deg, #0600AB 0%, #00033D 100%);
                }

                /* Form Controls Custom */
                .form-control,
                .form-select {
                    border: 2px solid #e9ecef;
                    border-radius: 8px;
                    padding: 0.6rem 1rem;
                    transition: all 0.3s ease;
                }

                .form-control:focus,
                .form-select:focus {
                    border-color: #977DFF;
                    box-shadow: 0 0 0 0.25rem rgba(151, 125, 255, 0.15);
                }

                /* Buttons */
                .btn {
                    border-radius: 8px;
                    padding: 0.6rem 1.2rem;
                    font-weight: 600;
                    transition: all 0.3s ease;
                }

                .btn-outline-light {
                    border: 2px solid white;
                    color: white;
                }

                .btn-outline-light:hover {
                    background: white;
                    color: #0600AB;
                }

                .btn-outline-primary {
                    border: 2px solid #977DFF;
                    color: #977DFF;
                }

                .btn-outline-primary:hover {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 100%);
                    border-color: #977DFF;
                    color: white;
                }

                .btn-outline-secondary {
                    border: 2px solid #6c757d;
                }

                .btn-outline-danger {
                    border: 2px solid #dc3545;
                }

                .btn-outline-danger:hover {
                    background: #dc3545;
                    border-color: #dc3545;
                    color: white;
                }

                /* Card Shadows */
                .shadow-sm {
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08) !important;
                }

                /* Badge Styles */
                .badge {
                    padding: 0.4rem 0.8rem;
                    border-radius: 6px;
                    font-weight: 600;
                    font-size: 0.75rem;
                }

                /* Modal Improvements */
                .modal-content {
                    border-radius: 15px;
                    overflow: hidden;
                }

                .modal-header {
                    border-bottom: 2px solid #e9ecef;
                }

                .modal-footer {
                    border-top: 2px solid #e9ecef;
                }

                /* Hover Effects */
                .btn-group .btn:hover {
                    transform: translateY(-2px);
                }

                /* Text Gradient */
                .text-gradient {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 100%);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                }

                /* Info Cards */
                .info-card {
                    background: white;
                    border-radius: 12px;
                    padding: 1.5rem;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
                    transition: all 0.3s ease;
                }

                .info-card:hover {
                    transform: translateY(-4px);
                    box-shadow: 0 8px 24px rgba(151, 125, 255, 0.2);
                }

                /* Loading Spinner */
                .spinner-border {
                    border-width: 3px;
                }

                /* Alert Styles */
                .alert {
                    border-radius: 10px;
                    border: none;
                }

                /* Custom Checkbox */
                .form-check-input {
                    cursor: pointer;
                    transition: all 0.2s ease;
                }

                .form-check-input:checked {
                    background-color: #977DFF;
                    border-color: #977DFF;
                }

                /* Tooltip Styles */
                [title] {
                    position: relative;
                }

                /* Print Styles */
                @media print {
                    .page-header,
                    .filters-card,
                    .bulk-actions-bar,
                    .movie-actions,
                    .pagination-wrapper {
                        display: none !important;
                    }
                }

                /* Dark Mode Support (Optional) */
                @media (prefers-color-scheme: dark) {
                    .movies-page {
                        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
                    }

                    .filters-card,
                    .movies-content,
                    .movie-card {
                        background: #0f3460;
                        color: white;
                    }

                    .movie-title {
                        color: #977DFF;
                    }

                    .filter-label {
                        color: #977DFF;
                    }

                    .form-control,
                    .form-select {
                        background: #16213e;
                        color: white;
                        border-color: #977DFF;
                    }
                }

                /* Accessibility */
                .btn:focus,
                .form-control:focus,
                .form-select:focus {
                    outline: 3px solid rgba(151, 125, 255, 0.5);
                    outline-offset: 2px;
                }

                /* Selection Styles */
                ::selection {
                    background: #977DFF;
                    color: white;
                }

                ::-moz-selection {
                    background: #977DFF;
                    color: white;
                }

                /* Link Styles */
                a {
                    color: #977DFF;
                    text-decoration: none;
                    transition: all 0.2s ease;
                }

                a:hover {
                    color: #0033FF;
                    text-decoration: underline;
                }

                /* Table Responsive */
                .table-responsive {
                    border-radius: 10px;
                    overflow: hidden;
                }

                /* Card Hover Animation */
                @keyframes cardHover {
                    0% {
                        transform: translateY(0);
                    }
                    50% {
                        transform: translateY(-5px);
                    }
                    100% {
                        transform: translateY(-8px);
                    }
                }

                .movie-card:hover {
                    animation: cardHover 0.3s ease forwards;
                }

                /* Gradient Border */
                .gradient-border {
                    border: 2px solid transparent;
                    background: linear-gradient(white, white) padding-box,
                                linear-gradient(135deg, #977DFF 0%, #0033FF 100%) border-box;
                }

                /* Shimmer Effect for Loading */
                @keyframes shimmer {
                    0% {
                        background-position: -1000px 0;
                    }
                    100% {
                        background-position: 1000px 0;
                    }
                }

                .shimmer {
                    background: linear-gradient(to right, #f0f0f0 0%, #e0e0e0 20%, #f0f0f0 40%, #f0f0f0 100%);
                    background-size: 1000px 100%;
                    animation: shimmer 2s infinite;
                }

                /* Status Colors */
                .status-released {
                    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
                }

                .status-upcoming {
                    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
                }

                .status-production {
                    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
                }

                /* Genre Colors */
                .genre-action {
                    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
                }

                .genre-comedy {
                    background: linear-gradient(135deg, #ffd93d 0%, #ffb800 100%);
                }

                .genre-drama {
                    background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
                }

                .genre-horror {
                    background: linear-gradient(135deg, #2d3436 0%, #636e72 100%);
                }

                /* Smooth Transitions */
                * {
                    transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
                }

                /* Focus Visible */
                *:focus-visible {
                    outline: 3px solid rgba(151, 125, 255, 0.5);
                    outline-offset: 2px;
                }

                /* No Select for UI Elements */
                .btn,
                .badge,
                .page-link {
                    user-select: none;
                    -webkit-user-select: none;
                    -moz-user-select: none;
                }

                /* Image Loading */
                img {
                    image-rendering: -webkit-optimize-contrast;
                    image-rendering: crisp-edges;
                }

                /* Backdrop Blur */
                .backdrop-blur {
                    backdrop-filter: blur(10px);
                    -webkit-backdrop-filter: blur(10px);
                }

                /* Glass Morphism Effect */
                .glass {
                    background: rgba(255, 255, 255, 0.1);
                    backdrop-filter: blur(10px);
                    -webkit-backdrop-filter: blur(10px);
                    border: 1px solid rgba(255, 255, 255, 0.2);
                }

                /* Gradient Text Animation */
                @keyframes gradientText {
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

                .animated-gradient-text {
                    background: linear-gradient(270deg, #977DFF, #0033FF, #0600AB, #00033D);
                    background-size: 400% 400%;
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    animation: gradientText 3s ease infinite;
                }

                /* Pulse Animation */
                @keyframes pulse {
                    0%, 100% {
                        opacity: 1;
                    }
                    50% {
                        opacity: 0.5;
                    }
                }

                .pulse {
                    animation: pulse 2s ease-in-out infinite;
                }

                /* Floating Animation */
                @keyframes floating {
                    0%, 100% {
                        transform: translateY(0);
                    }
                    50% {
                        transform: translateY(-10px);
                    }
                }

                .floating {
                    animation: floating 3s ease-in-out infinite;
                }

                /* Slide In Animation */
                @keyframes slideIn {
                    from {
                        opacity: 0;
                        transform: translateX(-30px);
                    }
                    to {
                        opacity: 1;
                        transform: translateX(0);
                    }
                }

                .slide-in {
                    animation: slideIn 0.5s ease;
                }

                /* Zoom In Animation */
                @keyframes zoomIn {
                    from {
                        opacity: 0;
                        transform: scale(0.8);
                    }
                    to {
                        opacity: 1;
                        transform: scale(1);
                    }
                }

                .zoom-in {
                    animation: zoomIn 0.3s ease;
                }

                /* Rotate Animation */
                @keyframes rotate {
                    from {
                        transform: rotate(0deg);
                    }
                    to {
                        transform: rotate(360deg);
                    }
                }

                .rotate {
                    animation: rotate 2s linear infinite;
                }

                /* Bounce Animation */
                @keyframes bounce {
                    0%, 20%, 50%, 80%, 100% {
                        transform: translateY(0);
                    }
                    40% {
                        transform: translateY(-20px);
                    }
                    60% {
                        transform: translateY(-10px);
                    }
                }

                .bounce {
                    animation: bounce 2s infinite;
                }

                /* Shake Animation */
                @keyframes shake {
                    0%, 100% {
                        transform: translateX(0);
                    }
                    10%, 30%, 50%, 70%, 90% {
                        transform: translateX(-5px);
                    }
                    20%, 40%, 60%, 80% {
                        transform: translateX(5px);
                    }
                }

                .shake {
                    animation: shake 0.5s;
                }

                /* Glow Effect */
                .glow {
                    box-shadow: 0 0 20px rgba(151, 125, 255, 0.6);
                }

                .glow:hover {
                    box-shadow: 0 0 30px rgba(151, 125, 255, 0.8);
                }

                /* Neon Text */
                .neon-text {
                    color: #977DFF;
                    text-shadow: 
                        0 0 5px #977DFF,
                        0 0 10px #977DFF,
                        0 0 20px #977DFF,
                        0 0 40px #0033FF,
                        0 0 80px #0033FF;
                }

                /* 3D Button Effect */
                .btn-3d {
                    box-shadow: 0 5px 0 #0600AB;
                    position: relative;
                    top: 0;
                }

                .btn-3d:active {
                    top: 5px;
                    box-shadow: 0 0 0 #0600AB;
                }

                /* Ripple Effect */
                @keyframes ripple {
                    0% {
                        transform: scale(0);
                        opacity: 1;
                    }
                    100% {
                        transform: scale(4);
                        opacity: 0;
                    }
                }

                .ripple {
                    position: relative;
                    overflow: hidden;
                }

                .ripple::after {
                    content: '';
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    width: 0;
                    height: 0;
                    border-radius: 50%;
                    background: rgba(151, 125, 255, 0.5);
                    transform: translate(-50%, -50%);
                    animation: ripple 0.6s ease-out;
                }

                /* Gradient Animation */
                @keyframes gradientShift {
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

                .gradient-animated {
                    background: linear-gradient(270deg, #977DFF, #0033FF, #0600AB, #00033D);
                    background-size: 400% 400%;
                    animation: gradientShift 5s ease infinite;
                }

                /* Smooth Scroll */
                html {
                    scroll-behavior: smooth;
                }

                /* Custom Scrollbar for Modal */
                .modal-body::-webkit-scrollbar {
                    width: 8px;
                }

                .modal-body::-webkit-scrollbar-track {
                    background: #f1f1f1;
                    border-radius: 10px;
                }

                .modal-body::-webkit-scrollbar-thumb {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 100%);
                    border-radius: 10px;
                }

                .modal-body::-webkit-scrollbar-thumb:hover {
                    background: linear-gradient(135deg, #0600AB 0%, #00033D 100%);
                }

                /* Utility Classes */
                .text-primary-gradient {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 100%);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                }

                .bg-primary-gradient {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 100%);
                }

                .border-primary-gradient {
                    border: 2px solid transparent;
                    background: linear-gradient(white, white) padding-box,
                                linear-gradient(135deg, #977DFF 0%, #0033FF 100%) border-box;
                }

                /* Performance Optimization */
                .movie-card,
                .movie-poster img,
                .btn,
                .badge {
                    will-change: transform;
                }

                /* Prevent Layout Shift */
                .movie-poster,
                .table-poster {
                    aspect-ratio: 2/3;
                }

                /* Accessibility - Screen Reader Only */
                .sr-only {
                    position: absolute;
                    width: 1px;
                    height: 1px;
                    padding: 0;
                    margin: -1px;
                    overflow: hidden;
                    clip: rect(0, 0, 0, 0);
                    white-space: nowrap;
                    border-width: 0;
                }

                /* High Contrast Mode Support */
                @media (prefers-contrast: high) {
                    .btn-gradient {
                        border: 2px solid currentColor;
                    }
                }

                /* Reduced Motion Support */
                @media (prefers-reduced-motion: reduce) {
                    *,
                    *::before,
                    *::after {
                        animation-duration: 0.01ms !important;
                        animation-iteration-count: 1 !important;
                        transition-duration: 0.01ms !important;
                    }
                }
            `}</style>
        </div>
    );
}

export default Movies;

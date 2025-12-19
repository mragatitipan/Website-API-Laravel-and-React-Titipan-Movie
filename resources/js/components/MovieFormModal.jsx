import React, { useState, useEffect } from 'react';
import { moviesAPI, getErrorMessage } from '../services/api';

function MovieFormModal({ show, mode, movie, genres, onClose, onSuccess }) {
    const [formData, setFormData] = useState({
        title: '',
        original_title: '',
        original_language: '',
        overview: '',
        tagline: '',
        release_date: '',
        genre: '',
        vote_average: '',
        vote_count: '',
        popularity: '',
        runtime: '',
        status: '',
        budget: '',
        revenue: '',
        poster_path: '',
        backdrop_path: '',
        homepage: '',
        imdb_id: '',
        adult: false,
        video: false,
    });
    
    const [selectedGenres, setSelectedGenres] = useState([]);
    const [loading, setLoading] = useState(false);
    const [errors, setErrors] = useState({});
    const [activeAccordion, setActiveAccordion] = useState('basic');
    const [saveSuccess, setSaveSuccess] = useState(false);

    // ========================================================================
    // LOAD MOVIE DATA (FIXED FOR EDIT MODE)
    // ========================================================================
    useEffect(() => {
        if (mode === 'edit' && movie && show) {
            console.log('📝 [EDIT MODE] Loading movie data:', movie);
            
            // Parse genres from different formats
            let genreNames = [];
            if (movie.genres && Array.isArray(movie.genres)) {
                genreNames = movie.genres;
            } else if (movie.genre && typeof movie.genre === 'string') {
                genreNames = movie.genre.split(',').map(g => g.trim()).filter(g => g);
            }

            console.log('🏷️ Parsed genres:', genreNames);

            // Set form data - CONVERT NULL TO EMPTY STRING
            setFormData({
                title: movie.title || '',
                original_title: movie.original_title || '',
                original_language: movie.original_language || '',
                overview: movie.overview || '',
                tagline: movie.tagline || '',
                release_date: movie.release_date || '',
                genre: movie.genre || '',
                vote_average: movie.vote_average !== null && movie.vote_average !== undefined ? String(movie.vote_average) : '',
                vote_count: movie.vote_count !== null && movie.vote_count !== undefined ? String(movie.vote_count) : '',
                popularity: movie.popularity !== null && movie.popularity !== undefined ? String(movie.popularity) : '',
                runtime: movie.runtime !== null && movie.runtime !== undefined ? String(movie.runtime) : '',
                status: movie.status || '',
                budget: movie.budget !== null && movie.budget !== undefined ? String(movie.budget) : '',
                revenue: movie.revenue !== null && movie.revenue !== undefined ? String(movie.revenue) : '',
                poster_path: movie.poster_path || '',
                backdrop_path: movie.backdrop_path || '',
                homepage: movie.homepage || '',
                imdb_id: movie.imdb_id || '',
                adult: Boolean(movie.adult),
                video: Boolean(movie.video),
            });

            setSelectedGenres(genreNames);
            console.log('✅ Form data loaded successfully');
        } else if (mode === 'add' || !show) {
            resetForm();
        }
    }, [mode, movie, show]);

    // ========================================================================
    // FORM HANDLERS
    // ========================================================================
    const resetForm = () => {
        setFormData({
            title: '',
            original_title: '',
            original_language: '',
            overview: '',
            tagline: '',
            release_date: '',
            genre: '',
            vote_average: '',
            vote_count: '',
            popularity: '',
            runtime: '',
            status: '',
            budget: '',
            revenue: '',
            poster_path: '',
            backdrop_path: '',
            homepage: '',
            imdb_id: '',
            adult: false,
            video: false,
        });
        setSelectedGenres([]);
        setErrors({});
        setActiveAccordion('basic');
        setSaveSuccess(false);
    };

    const handleChange = (e) => {
        const { name, value, type, checked } = e.target;
        setFormData(prev => ({
            ...prev,
            [name]: type === 'checkbox' ? checked : value
        }));
        
        // Clear error for this field
        if (errors[name]) {
            setErrors(prev => {
                const newErrors = { ...prev };
                delete newErrors[name];
                return newErrors;
            });
        }
    };

    const handleGenreToggle = (genreName) => {
        setSelectedGenres(prev => {
            if (prev.includes(genreName)) {
                return prev.filter(g => g !== genreName);
            } else {
                return [...prev, genreName];
            }
        });

        if (errors.genre) {
            setErrors(prev => {
                const newErrors = { ...prev };
                delete newErrors.genre;
                return newErrors;
            });
        }
    };

    // ========================================================================
    // VALIDATION
    // ========================================================================
    const validate = () => {
        const newErrors = {};
        
        // Required fields
        if (!formData.title.trim()) {
            newErrors.title = 'Title is required';
        }
        
        if (!formData.release_date) {
            newErrors.release_date = 'Release date is required';
        }
        
        if (selectedGenres.length === 0) {
            newErrors.genre = 'Please select at least one genre';
        }
        
        // Numeric validations
        if (formData.vote_average && (parseFloat(formData.vote_average) < 0 || parseFloat(formData.vote_average) > 10)) {
            newErrors.vote_average = 'Rating must be between 0 and 10';
        }

        if (formData.vote_count && parseInt(formData.vote_count) < 0) {
            newErrors.vote_count = 'Vote count cannot be negative';
        }

        if (formData.runtime && parseInt(formData.runtime) < 0) {
            newErrors.runtime = 'Runtime cannot be negative';
        }

        if (formData.budget && parseInt(formData.budget) < 0) {
            newErrors.budget = 'Budget cannot be negative';
        }

        if (formData.revenue && parseInt(formData.revenue) < 0) {
            newErrors.revenue = 'Revenue cannot be negative';
        }

        // URL validation
        if (formData.homepage && formData.homepage.trim() && !formData.homepage.startsWith('http')) {
            newErrors.homepage = 'Homepage must be a valid URL (start with http:// or https://)';
        }
        
        setErrors(newErrors);
        
        // Auto-open accordion with first error
        if (Object.keys(newErrors).length > 0) {
            if (newErrors.title || newErrors.overview || newErrors.tagline || newErrors.original_title) {
                setActiveAccordion('basic');
            } else if (newErrors.release_date || newErrors.genre) {
                setActiveAccordion('release');
            } else if (newErrors.vote_average || newErrors.vote_count || newErrors.runtime) {
                setActiveAccordion('ratings');
            } else if (newErrors.budget || newErrors.revenue) {
                setActiveAccordion('financial');
            } else if (newErrors.poster_path || newErrors.backdrop_path || newErrors.homepage || newErrors.imdb_id) {
                setActiveAccordion('media');
            }
        }
        
        return Object.keys(newErrors).length === 0;
    };

    // ========================================================================
    // SUBMIT HANDLER (FIXED FOR UPDATE)
    // ========================================================================
    const handleSubmit = async (e) => {
        e.preventDefault();
        
        console.log('🚀 [SUBMIT] Starting submission...');
        console.log('📋 Mode:', mode);
        console.log('🎬 Movie ID:', movie?.id);
        
        if (!validate()) {
            console.log('❌ Validation failed');
            return;
        }
        
        setLoading(true);
        setSaveSuccess(false);
        
        try {
            // Prepare submit data
            const submitData = {
                title: formData.title.trim(),
                original_title: formData.original_title.trim() || null,
                original_language: formData.original_language || null,
                overview: formData.overview.trim() || null,
                tagline: formData.tagline.trim() || null,
                release_date: formData.release_date,
                genre: selectedGenres.join(', '),
                vote_average: formData.vote_average ? parseFloat(formData.vote_average) : null,
                vote_count: formData.vote_count ? parseInt(formData.vote_count) : null,
                popularity: formData.popularity ? parseFloat(formData.popularity) : null,
                runtime: formData.runtime ? parseInt(formData.runtime) : null,
                status: formData.status || null,
                budget: formData.budget ? parseInt(formData.budget) : null,
                revenue: formData.revenue ? parseInt(formData.revenue) : null,
                poster_path: formData.poster_path.trim() || null,
                backdrop_path: formData.backdrop_path.trim() || null,
                homepage: formData.homepage.trim() || null,
                imdb_id: formData.imdb_id.trim() || null,
                adult: Boolean(formData.adult),
                video: Boolean(formData.video),
            };

            console.log('📤 Submit data prepared:', submitData);

            let response;
            if (mode === 'add') {
                console.log('➕ Creating new movie...');
                response = await moviesAPI.create(submitData);
                console.log('✅ Movie created:', response.data);
            } else if (mode === 'edit') {
                console.log('✏️ Updating movie ID:', movie.id);
                console.log('🔄 Calling API update...');
                
                // FIXED: Use PUT method explicitly
                response = await moviesAPI.update(movie.id, submitData);
                
                console.log('✅ Movie updated successfully:', response.data);
            }
            
            // Show success state
            setSaveSuccess(true);
            
            // Success notification
            const successMessage = mode === 'add' 
                ? `✅ Movie "${formData.title}" added successfully!`
                : `✅ Movie "${formData.title}" updated successfully!`;
            
            alert(successMessage);
            
            console.log('🎉 Success! Closing modal...');
            
            // Reset and close
            setTimeout(() => {
                resetForm();
                onSuccess(); // This will refresh the movie list
            }, 500);
            
        } catch (error) {
            console.error('❌ [ERROR] Submission failed:', error);
            console.error('📋 Error response:', error.response);
            
            // Handle validation errors from backend
            if (error.response?.data?.errors) {
                const backendErrors = error.response.data.errors;
                console.log('🔍 Backend validation errors:', backendErrors);
                
                // Convert Laravel validation errors to flat object
                const flatErrors = {};
                Object.keys(backendErrors).forEach(key => {
                    flatErrors[key] = Array.isArray(backendErrors[key]) 
                        ? backendErrors[key][0] 
                        : backendErrors[key];
                });
                
                setErrors(flatErrors);
                
                // Show first error
                const firstError = Object.values(flatErrors)[0];
                alert('❌ Validation Error: ' + firstError);
            } else {
                // Generic error
                const errorMsg = getErrorMessage(error);
                console.error('💥 Generic error:', errorMsg);
                alert('❌ Error: ' + errorMsg);
            }
        } finally {
            setLoading(false);
            console.log('🏁 Submission process completed');
        }
    };

    // ========================================================================
    // ACCORDION TOGGLE
    // ========================================================================
    const toggleAccordion = (section) => {
        setActiveAccordion(activeAccordion === section ? '' : section);
    };

    // ========================================================================
    // COMPUTED VALUES
    // ========================================================================
    const computedProfit = (parseInt(formData.revenue || 0) - parseInt(formData.budget || 0));
    const computedProfitPercentage = formData.budget > 0 
        ? ((computedProfit / formData.budget) * 100).toFixed(2) 
        : 0;

    // ========================================================================
    // RENDER
    // ========================================================================
    if (!show) return null;

    return (
        <>
            <div className="modal show d-block" tabIndex="-1" style={{ backgroundColor: 'rgba(0,0,0,0.75)' }}>
                <div className="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
                    <div className="modal-content shadow-lg border-0">
                        
                        {/* ============================================================ */}
                        {/* HEADER - NEW GRADIENT */}
                        {/* ============================================================ */}
                        <div className="modal-header gradient-header" style={{
                            background: 'linear-gradient(135deg, #977DFF 0%, #0033FF 50%, #0600AB 100%)',
                            color: 'white',
                            borderBottom: 'none'
                        }}>
                            <div>
                                <h5 className="modal-title mb-1 fw-bold">
                                    <i className={`fas fa-${mode === 'add' ? 'plus-circle' : 'edit'} me-2`}></i>
                                    {mode === 'add' ? 'Add New Movie' : 'Edit Movie'}
                                </h5>
                                {mode === 'edit' && movie && (
                                    <small className="opacity-90">
                                        <i className="fas fa-film me-1"></i>
                                        {movie.title}
                                        {movie.release_year && ` (${movie.release_year})`}
                                    </small>
                                )}
                            </div>
                            <button 
                                type="button" 
                                className="btn-close btn-close-white" 
                                onClick={onClose}
                                disabled={loading}
                            ></button>
                        </div>
                        
                        <form onSubmit={handleSubmit}>
                            
                            {/* ============================================================ */}
                            {/* BODY */}
                            {/* ============================================================ */}
                            <div className="modal-body p-0" style={{ maxHeight: '70vh', overflowY: 'auto' }}>
                                
                                {/* Progress Indicator */}
                                <div className="px-4 pt-3 pb-2 bg-light border-bottom sticky-top" style={{ zIndex: 10 }}>
                                    <div className="d-flex justify-content-between align-items-center mb-2">
                                        <small className="text-muted">
                                            <i className="fas fa-info-circle me-1"></i>
                                            Fill in the required fields (*) to save the movie
                                        </small>
                                        {saveSuccess && (
                                            <span className="badge bg-success">
                                                <i className="fas fa-check-circle me-1"></i>
                                                Saved Successfully
                                            </span>
                                        )}
                                    </div>
                                    <div className="d-flex gap-2 flex-wrap">
                                        <span className={`badge ${formData.title && formData.release_date ? 'badge-gradient' : 'bg-secondary'}`}>
                                            <i className="fas fa-check-circle me-1"></i>
                                            Required Fields
                                        </span>
                                        <span className={`badge ${selectedGenres.length > 0 ? 'badge-gradient' : 'bg-secondary'}`}>
                                            <i className="fas fa-tags me-1"></i>
                                            {selectedGenres.length} Genre(s)
                                        </span>
                                        <span className={`badge ${formData.vote_average ? 'badge-gradient' : 'bg-light text-dark'}`}>
                                            <i className="fas fa-star me-1"></i>
                                            {formData.vote_average ? `Rating: ${formData.vote_average}` : 'No Rating'}
                                        </span>
                                        <span className={`badge ${formData.runtime ? 'badge-gradient' : 'bg-light text-dark'}`}>
                                            <i className="fas fa-clock me-1"></i>
                                            {formData.runtime ? `${formData.runtime} min` : 'No Runtime'}
                                        </span>
                                    </div>
                                </div>

                                {/* Accordion Sections */}
                                <div className="accordion accordion-flush" id="movieFormAccordion">
                                    
                                    {/* ============================================================ */}
                                    {/* 1. BASIC INFORMATION */}
                                    {/* ============================================================ */}
                                    <div className="accordion-item">
                                        <h2 className="accordion-header">
                                            <button 
                                                className={`accordion-button ${activeAccordion !== 'basic' ? 'collapsed' : ''} fw-bold`}
                                                type="button"
                                                onClick={() => toggleAccordion('basic')}
                                                style={{ 
                                                    backgroundColor: activeAccordion === 'basic' ? '#f0ebff' : 'white',
                                                    color: '#0600AB'
                                                }}
                                            >
                                                <i className="fas fa-info-circle me-2" style={{ color: '#977DFF' }}></i>
                                                Basic Information
                                                <span className="ms-2 badge bg-danger">Required *</span>
                                                {(errors.title || errors.overview) && (
                                                    <span className="ms-2 badge bg-warning">
                                                        <i className="fas fa-exclamation-triangle"></i>
                                                    </span>
                                                )}
                                            </button>
                                        </h2>
                                        <div className={`accordion-collapse collapse ${activeAccordion === 'basic' ? 'show' : ''}`}>
                                            <div className="accordion-body bg-light">
                                                <div className="row g-3">
                                                    {/* Title */}
                                                    <div className="col-md-6">
                                                        <label className="form-label fw-semibold" style={{ color: '#0600AB' }}>
                                                            <i className="fas fa-film me-1" style={{ color: '#977DFF' }}></i>
                                                            Title <span className="text-danger">*</span>
                                                        </label>
                                                        <input
                                                            type="text"
                                                            className={`form-control form-control-lg ${errors.title ? 'is-invalid' : formData.title ? 'is-valid' : ''}`}
                                                            name="title"
                                                            value={formData.title}
                                                            onChange={handleChange}
                                                            placeholder="Enter movie title"
                                                            required
                                                        />
                                                        {errors.title && <div className="invalid-feedback">{errors.title}</div>}
                                                    </div>

                                                    {/* Original Title */}
                                                    <div className="col-md-6">
                                                        <label className="form-label fw-semibold" style={{ color: '#0600AB' }}>
                                                            <i className="fas fa-language me-1" style={{ color: '#0033FF' }}></i>
                                                            Original Title
                                                        </label>
                                                        <input
                                                            type="text"
                                                            className="form-control form-control-lg"
                                                            name="original_title"
                                                            value={formData.original_title}
                                                            onChange={handleChange}
                                                            placeholder="Original title (if different)"
                                                        />
                                                    </div>

                                                    {/* Overview */}
                                                    <div className="col-12">
                                                        <label className="form-label fw-semibold" style={{ color: '#0600AB' }}>
                                                            <i className="fas fa-align-left me-1" style={{ color: '#977DFF' }}></i>
                                                            Overview / Description
                                                        </label>
                                                        <textarea
                                                            className="form-control"
                                                            name="overview"
                                                            rows="5"
                                                            value={formData.overview}
                                                            onChange={handleChange}
                                                            placeholder="Movie plot, storyline, description..."
                                                            style={{ resize: 'vertical' }}
                                                        ></textarea>
                                                        <div className="d-flex justify-content-between mt-1">
                                                            <small className="text-muted">
                                                                {formData.overview.length} characters
                                                            </small>
                                                            {formData.overview.length > 500 && (
                                                                <small style={{ color: '#977DFF' }}>
                                                                    <i className="fas fa-check-circle me-1"></i>
                                                                    Good length
                                                                </small>
                                                            )}
                                                        </div>
                                                    </div>

                                                    {/* Tagline */}
                                                    <div className="col-12">
                                                        <label className="form-label fw-semibold" style={{ color: '#0600AB' }}>
                                                            <i className="fas fa-quote-left me-1" style={{ color: '#0033FF' }}></i>
                                                            Tagline
                                                        </label>
                                                        <input
                                                            type="text"
                                                            className="form-control"
                                                            name="tagline"
                                                            value={formData.tagline}
                                                            onChange={handleChange}
                                                            placeholder="Movie tagline or slogan"
                                                        />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {/* ============================================================ */}
                                    {/* 2. RELEASE & GENRE */}
                                    {/* ============================================================ */}
                                    <div className="accordion-item">
                                        <h2 className="accordion-header">
                                            <button 
                                                className={`accordion-button ${activeAccordion !== 'release' ? 'collapsed' : ''} fw-bold`}
                                                type="button"
                                                onClick={() => toggleAccordion('release')}
                                                style={{ 
                                                    backgroundColor: activeAccordion === 'release' ? '#f0ebff' : 'white',
                                                    color: '#0600AB'
                                                }}
                                            >
                                                <i className="fas fa-calendar-alt me-2" style={{ color: '#977DFF' }}></i>
                                                Release & Genre
                                                <span className="ms-2 badge bg-danger">Required *</span>
                                                {(errors.release_date || errors.genre) && (
                                                    <span className="ms-2 badge bg-warning">
                                                        <i className="fas fa-exclamation-triangle"></i>
                                                    </span>
                                                )}
                                            </button>
                                        </h2>
                                        <div className={`accordion-collapse collapse ${activeAccordion === 'release' ? 'show' : ''}`}>
                                            <div className="accordion-body bg-light">
                                                <div className="row g-3">
                                                    {/* Release Date */}
                                                    <div className="col-md-4">
                                                        <label className="form-label fw-semibold" style={{ color: '#0600AB' }}>
                                                            <i className="fas fa-calendar me-1" style={{ color: '#977DFF' }}></i>
                                                            Release Date <span className="text-danger">*</span>
                                                        </label>
                                                        <input
                                                            type="date"
                                                            className={`form-control form-control-lg ${errors.release_date ? 'is-invalid' : formData.release_date ? 'is-valid' : ''}`}
                                                            name="release_date"
                                                            value={formData.release_date}
                                                            onChange={handleChange}
                                                            required
                                                        />
                                                        {errors.release_date && <div className="invalid-feedback">{errors.release_date}</div>}
                                                    </div>

                                                    {/* Original Language */}
                                                    <div className="col-md-4">
                                                        <label className="form-label fw-semibold" style={{ color: '#0600AB' }}>
                                                            <i className="fas fa-globe me-1" style={{ color: '#0033FF' }}></i>
                                                            Language
                                                        </label>
                                                        <select
                                                            className="form-select form-select-lg"
                                                            name="original_language"
                                                            value={formData.original_language}
                                                            onChange={handleChange}
                                                        >
                                                            <option value="">Select Language</option>
                                                            <option value="en">🇺🇸 English (en)</option>
                                                            <option value="id">🇮🇩 Indonesian (id)</option>
                                                            <option value="ja">🇯🇵 Japanese (ja)</option>
                                                            <option value="ko">🇰🇷 Korean (ko)</option>
                                                            <option value="zh">🇨🇳 Chinese (zh)</option>
                                                            <option value="es">🇪🇸 Spanish (es)</option>
                                                            <option value="fr">🇫🇷 French (fr)</option>
                                                            <option value="de">🇩🇪 German (de)</option>
                                                            <option value="it">🇮🇹 Italian (it)</option>
                                                            <option value="pt">🇵🇹 Portuguese (pt)</option>
                                                            <option value="ru">🇷🇺 Russian (ru)</option>
                                                            <option value="ar">🇸🇦 Arabic (ar)</option>
                                                            <option value="hi">🇮🇳 Hindi (hi)</option>
                                                            <option value="th">🇹🇭 Thai (th)</option>
                                                        </select>
                                                    </div>

                                                    {/* Status */}
                                                    <div className="col-md-4">
                                                        <label className="form-label fw-semibold" style={{ color: '#0600AB' }}>
                                                            <i className="fas fa-flag me-1" style={{ color: '#977DFF' }}></i>
                                                            Status
                                                        </label>
                                                        <select
                                                            className="form-select form-select-lg"
                                                            name="status"
                                                            value={formData.status}
                                                            onChange={handleChange}
                                                        >
                                                            <option value="">Select Status</option>
                                                            <option value="Released">✅ Released</option>
                                                            <option value="Post Production">🎬 Post Production</option>
                                                            <option value="In Production">📹 In Production</option>
                                                            <option value="Planned">📅 Planned</option>
                                                            <option value="Rumored">💭 Rumored</option>
                                                            <option value="Canceled">❌ Canceled</option>
                                                        </select>
                                                    </div>

                                                    {/* Genres */}
                                                    <div className="col-12">
                                                        <label className="form-label fw-semibold" style={{ color: '#0600AB' }}>
                                                            <i className="fas fa-tags me-1" style={{ color: '#977DFF' }}></i>
                                                            Genres <span className="text-danger">*</span>
                                                        </label>
                                                        <div className={`border rounded p-3 ${errors.genre ? 'border-danger' : selectedGenres.length > 0 ? 'border-success' : ''}`} 
                                                             style={{ maxHeight: '250px', overflowY: 'auto', backgroundColor: 'white' }}>
                                                            {genres && genres.length > 0 ? (
                                                                <div className="row g-2">
                                                                    {genres.map(genre => (
                                                                        <div key={genre.id} className="col-6 col-md-4 col-lg-3">
                                                                            <div className="form-check">
                                                                                <input
                                                                                    className="form-check-input"
                                                                                    type="checkbox"
                                                                                    id={`genre-${genre.id}`}
                                                                                    checked={selectedGenres.includes(genre.name)}
                                                                                    onChange={() => handleGenreToggle(genre.name)}
                                                                                />
                                                                                <label className="form-check-label" htmlFor={`genre-${genre.id}`}>
                                                                                    {genre.name}
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                    ))}
                                                                </div>
                                                            ) : (
                                                                <div className="text-muted text-center py-3">
                                                                    <i className="fas fa-info-circle me-2"></i>
                                                                    No genres available. Please sync from TMDB first.
                                                                </div>
                                                            )}
                                                        </div>
                                                        {errors.genre && <div className="text-danger small mt-1">{errors.genre}</div>}
                                                        {selectedGenres.length > 0 && (
                                                            <div className="mt-2">
                                                                <small className="text-muted me-2">
                                                                    <i className="fas fa-check-circle me-1" style={{ color: '#977DFF' }}></i>
                                                                    Selected ({selectedGenres.length}):
                                                                </small>
                                                                {selectedGenres.map((g, idx) => (
                                                                    <span key={idx} className="badge badge-gradient me-1 mb-1">
                                                                        {g}
                                                                        <button
                                                                            type="button"
                                                                            className="btn-close btn-close-white ms-1"
                                                                            style={{ fontSize: '0.6rem' }}
                                                                            onClick={() => handleGenreToggle(g)}
                                                                        ></button>
                                                                    </span>
                                                                ))}
                                                            </div>
                                                        )}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {/* ============================================================ */}
                                    {/* 3. RATINGS & STATISTICS */}
                                    {/* ============================================================ */}
                                    <div className="accordion-item">
                                        <h2 className="accordion-header">
                                            <button 
                                                className={`accordion-button ${activeAccordion !== 'ratings' ? 'collapsed' : ''} fw-bold`}
                                                type="button"
                                                onClick={() => toggleAccordion('ratings')}
                                                style={{ 
                                                    backgroundColor: activeAccordion === 'ratings' ? '#f0ebff' : 'white',
                                                    color: '#0600AB'
                                                }}
                                            >
                                                <i className="fas fa-star me-2" style={{ color: '#977DFF' }}></i>
                                                Ratings & Statistics
                                                {(errors.vote_average || errors.vote_count || errors.runtime) && (
                                                    <span className="ms-2 badge bg-warning">
                                                        <i className="fas fa-exclamation-triangle"></i>
                                                    </span>
                                                )}
                                            </button>
                                        </h2>
                                        <div className={`accordion-collapse collapse ${activeAccordion === 'ratings' ? 'show' : ''}`}>
                                            <div className="accordion-body bg-light">
                                                <div className="row g-3">
                                                    {/* Vote Average */}
                                                    <div className="col-md-3">
                                                        <label className="form-label fw-semibold" style={{ color: '#0600AB' }}>
                                                            <i className="fas fa-star me-1" style={{ color: '#977DFF' }}></i>
                                                            Rating (0-10)
                                                        </label>
                                                        <input
                                                            type="number"
                                                            step="0.1"
                                                            min="0"
                                                            max="10"
                                                            className={`form-control form-control-lg ${errors.vote_average ? 'is-invalid' : ''}`}
                                                            name="vote_average"
                                                            value={formData.vote_average}
                                                            onChange={handleChange}
                                                            placeholder="7.5"
                                                        />
                                                        {errors.vote_average && <div className="invalid-feedback">{errors.vote_average}</div>}
                                                        {formData.vote_average && !errors.vote_average && (
                                                            <small style={{ color: '#977DFF' }}>
                                                                <i className="fas fa-star me-1"></i>
                                                                {formData.vote_average}/10
                                                            </small>
                                                        )}
                                                    </div>

                                                    {/* Vote Count */}
                                                    <div className="col-md-3">
                                                        <label className="form-label fw-semibold" style={{ color: '#0600AB' }}>
                                                            <i className="fas fa-users me-1" style={{ color: '#0033FF' }}></i>
                                                            Vote Count
                                                        </label>
                                                        <input
                                                            type="number"
                                                            min="0"
                                                            className={`form-control form-control-lg ${errors.vote_count ? 'is-invalid' : ''}`}
                                                            name="vote_count"
                                                            value={formData.vote_count}
                                                            onChange={handleChange}
                                                            placeholder="1000"
                                                        />
                                                        {errors.vote_count && <div className="invalid-feedback">{errors.vote_count}</div>}
                                                    </div>

                                                    {/* Popularity */}
                                                    <div className="col-md-3">
                                                        <label className="form-label fw-semibold" style={{ color: '#0600AB' }}>
                                                            <i className="fas fa-fire me-1" style={{ color: '#977DFF' }}></i>
                                                            Popularity
                                                        </label>
                                                        <input
                                                            type="number"
                                                            step="0.01"
                                                            min="0"
                                                            className="form-control form-control-lg"
                                                            name="popularity"
                                                            value={formData.popularity}
                                                            onChange={handleChange}
                                                            placeholder="100.5"
                                                        />
                                                    </div>

                                                    {/* Runtime */}
                                                    <div className="col-md-3">
                                                        <label className="form-label fw-semibold" style={{ color: '#0600AB' }}>
                                                            <i className="fas fa-clock me-1" style={{ color: '#0033FF' }}></i>
                                                            Runtime (min)
                                                        </label>
                                                        <input
                                                            type="number"
                                                            min="0"
                                                            className={`form-control form-control-lg ${errors.runtime ? 'is-invalid' : ''}`}
                                                            name="runtime"
                                                            value={formData.runtime}
                                                            onChange={handleChange}
                                                            placeholder="120"
                                                        />
                                                        {errors.runtime && <div className="invalid-feedback">{errors.runtime}</div>}
                                                        {formData.runtime && !errors.runtime && (
                                                            <small style={{ color: '#977DFF' }}>
                                                                <i className="fas fa-clock me-1"></i>
                                                                {Math.floor(formData.runtime / 60)}h {formData.runtime % 60}m
                                                            </small>
                                                        )}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {/* ============================================================ */}
                                    {/* 4. FINANCIAL INFORMATION */}
                                    {/* ============================================================ */}
                                    <div className="accordion-item">
                                        <h2 className="accordion-header">
                                            <button 
                                                className={`accordion-button ${activeAccordion !== 'financial' ? 'collapsed' : ''} fw-bold`}
                                                type="button"
                                                onClick={() => toggleAccordion('financial')}
                                                style={{ 
                                                    backgroundColor: activeAccordion === 'financial' ? '#f0ebff' : 'white',
                                                    color: '#0600AB'
                                                }}
                                            >
                                                <i className="fas fa-dollar-sign me-2" style={{ color: '#977DFF' }}></i>
                                                Financial Information
                                                {(errors.budget || errors.revenue) && (
                                                    <span className="ms-2 badge bg-warning">
                                                        <i className="fas fa-exclamation-triangle"></i>
                                                    </span>
                                                )}
                                            </button>
                                        </h2>
                                        <div className={`accordion-collapse collapse ${activeAccordion === 'financial' ? 'show' : ''}`}>
                                            <div className="accordion-body bg-light">
                                                <div className="row g-3">
                                                    {/* Budget */}
                                                    <div className="col-md-6">
                                                        <label className="form-label fw-semibold" style={{ color: '#0600AB' }}>
                                                            <i className="fas fa-money-bill-wave me-1" style={{ color: '#977DFF' }}></i>
                                                            Budget (USD)
                                                        </label>
                                                        <div className="input-group input-group-lg">
                                                            <span className="input-group-text">$</span>
                                                            <input
                                                                type="number"
                                                                min="0"
                                                                className={`form-control ${errors.budget ? 'is-invalid' : ''}`}
                                                                name="budget"
                                                                value={formData.budget}
                                                                onChange={handleChange}
                                                                placeholder="50000000"
                                                            />
                                                        </div>
                                                        {errors.budget && <div className="text-danger small mt-1">{errors.budget}</div>}
                                                        {formData.budget && !errors.budget && (
                                                            <small className="text-muted">
                                                                ${parseInt(formData.budget).toLocaleString()}
                                                            </small>
                                                        )}
                                                    </div>

                                                    {/* Revenue */}
                                                    <div className="col-md-6">
                                                        <label className="form-label fw-semibold" style={{ color: '#0600AB' }}>
                                                            <i className="fas fa-chart-line me-1" style={{ color: '#0033FF' }}></i>
                                                            Revenue (USD)
                                                        </label>
                                                        <div className="input-group input-group-lg">
                                                            <span className="input-group-text">$</span>
                                                            <input
                                                                type="number"
                                                                min="0"
                                                                className={`form-control ${errors.revenue ? 'is-invalid' : ''}`}
                                                                name="revenue"
                                                                value={formData.revenue}
                                                                onChange={handleChange}
                                                                placeholder="150000000"
                                                            />
                                                        </div>
                                                        {errors.revenue && <div className="text-danger small mt-1">{errors.revenue}</div>}
                                                        {formData.revenue && !errors.revenue && (
                                                            <small className="text-muted">
                                                                ${parseInt(formData.revenue).toLocaleString()}
                                                            </small>
                                                        )}
                                                    </div>

                                                    {/* Profit Calculation Preview */}
                                                    {(formData.budget || formData.revenue) && (
                                                        <div className="col-12">
                                                            <div className={`alert ${computedProfit >= 0 ? 'alert-success' : 'alert-danger'} mb-0`}>
                                                                <div className="d-flex justify-content-between align-items-center">
                                                                    <div>
                                                                        <i className="fas fa-calculator me-2"></i>
                                                                        <strong>Profit Calculation:</strong>
                                                                    </div>
                                                                    <span className={`badge ${computedProfit >= 0 ? 'bg-success' : 'bg-danger'} fs-6`}>
                                                                        {computedProfit >= 0 ? '+' : ''}${computedProfit.toLocaleString()}
                                                                    </span>
                                                                </div>
                                                                <div className="mt-2 d-flex gap-3 flex-wrap">
                                                                    <span className="badge bg-light text-dark">
                                                                        Budget: ${parseInt(formData.budget || 0).toLocaleString()}
                                                                    </span>
                                                                    <span className="badge bg-light text-dark">
                                                                        Revenue: ${parseInt(formData.revenue || 0).toLocaleString()}
                                                                    </span>
                                                                    <span className="badge bg-light text-dark">
                                                                        ROI: {computedProfitPercentage}%
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    )}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {/* ============================================================ */}
                                    {/* 5. MEDIA & LINKS */}
                                    {/* ============================================================ */}
                                    <div className="accordion-item">
                                        <h2 className="accordion-header">
                                            <button 
                                                className={`accordion-button ${activeAccordion !== 'media' ? 'collapsed' : ''} fw-bold`}
                                                type="button"
                                                onClick={() => toggleAccordion('media')}
                                                style={{ 
                                                    backgroundColor: activeAccordion === 'media' ? '#f0ebff' : 'white',
                                                    color: '#0600AB'
                                                }}
                                            >
                                                <i className="fas fa-image me-2" style={{ color: '#977DFF' }}></i>
                                                Media & External Links
                                                {(errors.poster_path || errors.backdrop_path || errors.homepage || errors.imdb_id) && (
                                                    <span className="ms-2 badge bg-warning">
                                                        <i className="fas fa-exclamation-triangle"></i>
                                                    </span>
                                                )}
                                            </button>
                                        </h2>
                                        <div className={`accordion-collapse collapse ${activeAccordion === 'media' ? 'show' : ''}`}>
                                            <div className="accordion-body bg-light">
                                                <div className="row g-3">
                                                    {/* Poster Path */}
                                                    <div className="col-md-6">
                                                        <label className="form-label fw-semibold" style={{ color: '#0600AB' }}>
                                                            <i className="fas fa-image me-1" style={{ color: '#977DFF' }}></i>
                                                            Poster Path
                                                        </label>
                                                        <input
                                                            type="text"
                                                            className="form-control"
                                                            name="poster_path"
                                                            value={formData.poster_path}
                                                            onChange={handleChange}
                                                            placeholder="/path/to/poster.jpg"
                                                        />
                                                        <small className="text-muted">TMDB poster path (e.g. /abc123.jpg)</small>
                                                    </div>

                                                    {/* Backdrop Path */}
                                                    <div className="col-md-6">
                                                        <label className="form-label fw-semibold" style={{ color: '#0600AB' }}>
                                                            <i className="fas fa-panorama me-1" style={{ color: '#0033FF' }}></i>
                                                            Backdrop Path
                                                        </label>
                                                        <input
                                                            type="text"
                                                            className="form-control"
                                                            name="backdrop_path"
                                                            value={formData.backdrop_path}
                                                            onChange={handleChange}
                                                            placeholder="/path/to/backdrop.jpg"
                                                        />
                                                        <small className="text-muted">TMDB backdrop path</small>
                                                    </div>

                                                    {/* Homepage */}
                                                    <div className="col-md-6">
                                                        <label className="form-label fw-semibold" style={{ color: '#0600AB' }}>
                                                            <i className="fas fa-link me-1" style={{ color: '#977DFF' }}></i>
                                                            Homepage URL
                                                        </label>
                                                        <input
                                                            type="url"
                                                            className={`form-control ${errors.homepage ? 'is-invalid' : ''}`}
                                                            name="homepage"
                                                            value={formData.homepage}
                                                            onChange={handleChange}
                                                            placeholder="https://example.com"
                                                        />
                                                        {errors.homepage && <div className="invalid-feedback">{errors.homepage}</div>}
                                                    </div>

                                                    {/* IMDB ID */}
                                                    <div className="col-md-6">
                                                        <label className="form-label fw-semibold" style={{ color: '#0600AB' }}>
                                                            <i className="fab fa-imdb me-1" style={{ color: '#0033FF' }}></i>
                                                            IMDB ID
                                                        </label>
                                                        <input
                                                            type="text"
                                                            className="form-control"
                                                            name="imdb_id"
                                                            value={formData.imdb_id}
                                                            onChange={handleChange}
                                                            placeholder="tt1234567"
                                                            maxLength="20"
                                                        />
                                                        <small className="text-muted">IMDB identifier (e.g. tt1234567)</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {/* ============================================================ */}
                                    {/* 6. ADDITIONAL OPTIONS */}
                                    {/* ============================================================ */}
                                    <div className="accordion-item">
                                        <h2 className="accordion-header">
                                            <button 
                                                className={`accordion-button ${activeAccordion !== 'additional' ? 'collapsed' : ''} fw-bold`}
                                                type="button"
                                                onClick={() => toggleAccordion('additional')}
                                                style={{ 
                                                    backgroundColor: activeAccordion === 'additional' ? '#f0ebff' : 'white',
                                                    color: '#0600AB'
                                                }}
                                            >
                                                <i className="fas fa-cog me-2" style={{ color: '#977DFF' }}></i>
                                                Additional Options
                                            </button>
                                        </h2>
                                        <div className={`accordion-collapse collapse ${activeAccordion === 'additional' ? 'show' : ''}`}>
                                            <div className="accordion-body bg-light">
                                                <div className="row g-3">
                                                    <div className="col-md-6">
                                                        <div className="card border-0 shadow-sm h-100">
                                                            <div className="card-body">
                                                                <div className="form-check form-switch">
                                                                    <input
                                                                        className="form-check-input"
                                                                        type="checkbox"
                                                                        id="adultSwitch"
                                                                        name="adult"
                                                                        checked={formData.adult}
                                                                        onChange={handleChange}
                                                                        style={{ width: '3rem', height: '1.5rem' }}
                                                                    />
                                                                    <label className="form-check-label ms-2" htmlFor="adultSwitch">
                                                                        <strong>
                                                                            <i className="fas fa-exclamation-triangle me-1 text-danger"></i>
                                                                            Adult Content (18+)
                                                                        </strong>
                                                                        <br />
                                                                        <small className="text-muted">Mark as mature/adult content</small>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div className="col-md-6">
                                                        <div className="card border-0 shadow-sm h-100">
                                                            <div className="card-body">
                                                                <div className="form-check form-switch">
                                                                    <input
                                                                        className="form-check-input"
                                                                        type="checkbox"
                                                                        id="videoSwitch"
                                                                        name="video"
                                                                        checked={formData.video}
                                                                        onChange={handleChange}
                                                                        style={{ width: '3rem', height: '1.5rem' }}
                                                                    />
                                                                    <label className="form-check-label ms-2" htmlFor="videoSwitch">
                                                                        <strong style={{ color: '#0600AB' }}>
                                                                            <i className="fas fa-video me-1" style={{ color: '#977DFF' }}></i>
                                                                            Video Available
                                                                        </strong>
                                                                        <br />
                                                                        <small className="text-muted">Has video content available</small>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            {/* ============================================================ */}
                            {/* FOOTER */}
                            {/* ============================================================ */}
                            <div className="modal-footer bg-light border-top">
                                <div className="d-flex justify-content-between align-items-center w-100">
                                    <div>
                                        {mode === 'edit' && movie && (
                                            <small className="text-muted">
                                                <i className="fas fa-info-circle me-1"></i>
                                                ID: {movie.id}
                                            </small>
                                        )}
                                    </div>
                                    <div>
                                        <button 
                                            type="button" 
                                            className="btn btn-secondary me-2" 
                                            onClick={onClose} 
                                            disabled={loading}
                                        >
                                            <i className="fas fa-times me-2"></i>
                                            Cancel
                                        </button>
                                        <button 
                                            type="submit" 
                                            className="btn btn-gradient px-4" 
                                            disabled={loading}
                                        >
                                            {loading ? (
                                                <>
                                                    <span className="spinner-border spinner-border-sm me-2"></span>
                                                    Saving...
                                                </>
                                            ) : (
                                                <>
                                                    <i className={`fas fa-${mode === 'add' ? 'plus' : 'save'} me-2`}></i>
                                                    {mode === 'add' ? 'Add Movie' : 'Update Movie'}
                                                </>
                                            )}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {/* ============================================================ */}
            {/* CUSTOM STYLES - NEW GRADIENT THEME */}
            {/* ============================================================ */}
            <style>{`
                /* Gradient Colors: #977DFF, #0033FF, #0600AB, #00033D */
                
                .modal.show {
                    display: block;
                    overflow-y: auto;
                }

                .gradient-header {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 50%, #0600AB 100%) !important;
                }

                .badge-gradient {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 100%);
                    color: white;
                }

                .btn-gradient {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 50%, #0600AB 100%);
                    color: white;
                    border: none;
                    font-weight: 600;
                    transition: all 0.3s ease;
                }

                .btn-gradient:hover {
                    background: linear-gradient(135deg, #0600AB 0%, #0033FF 50%, #977DFF 100%);
                    transform: translateY(-2px);
                    box-shadow: 0 4px 12px rgba(151, 125, 255, 0.4);
                    color: white;
                }

                .btn-gradient:disabled {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 100%);
                    opacity: 0.6;
                }

                .accordion-button:not(.collapsed) {
                    box-shadow: none;
                }

                .accordion-button:focus {
                    box-shadow: none;
                    border-color: rgba(151, 125, 255, 0.3);
                }

                .accordion-button::after {
                    margin-left: auto;
                }

                .form-control:focus,
                .form-select:focus {
                    border-color: #977DFF;
                    box-shadow: 0 0 0 0.25rem rgba(151, 125, 255, 0.25);
                }

                .form-check-input:checked {
                    background-color: #977DFF;
                    border-color: #977DFF;
                }

                .form-control.is-valid {
                    border-color: #198754;
                }

                .form-control.is-invalid {
                    border-color: #dc3545;
                }

                .invalid-feedback {
                    display: block;
                }

                .accordion-body {
                    padding: 1.5rem;
                }

                .badge {
                    font-weight: 500;
                }

                .accordion-collapse {
                    transition: height 0.3s ease;
                }

                .modal-body::-webkit-scrollbar {
                    width: 10px;
                }

                .modal-body::-webkit-scrollbar-track {
                    background: #f1f1f1;
                }

                .modal-body::-webkit-scrollbar-thumb {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 100%);
                    border-radius: 5px;
                }

                .modal-body::-webkit-scrollbar-thumb:hover {
                    background: linear-gradient(135deg, #0600AB 0%, #00033D 100%);
                }

                .sticky-top {
                    position: sticky;
                    top: 0;
                    z-index: 10;
                }

                .btn-close-white {
                    filter: brightness(0) invert(1);
                }

                .input-group-text {
                    background-color: #e9ecef;
                    border-color: #ced4da;
                }

                .card {
                    transition: transform 0.2s;
                }

                .card:hover {
                    transform: translateY(-2px);
                }

                /* Gradient text */
                .text-gradient {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 100%);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                }
            `}</style>
        </>
    );
}

export default MovieFormModal;

import React, { useState } from 'react';
import { Link, useLocation } from 'react-router-dom';

function Layout({ children }) {
    const location = useLocation();
    const [navbarCollapsed, setNavbarCollapsed] = useState(true);
    
    const isActive = (path) => {
        return location.pathname === path ? 'active' : '';
    };

    const toggleNavbar = () => {
        setNavbarCollapsed(!navbarCollapsed);
    };

    const closeNavbar = () => {
        setNavbarCollapsed(true);
    };

    // Get current page title
    const getCurrentPageTitle = () => {
        switch(location.pathname) {
            case '/dashboard':
                return 'Dashboard';
            case '/movies':
                return 'Movies Management';
            case '/sync':
                return 'API Synchronization';
            default:
                return 'Titipan Movies';
        }
    };
    
    return (
        <>
            {/* Navbar */}
            <nav className="navbar navbar-expand-lg navbar-dark sticky-top shadow-lg">
                <div className="container-fluid px-4">
                    {/* Brand */}
                    <Link className="navbar-brand fw-bold d-flex align-items-center" to="/dashboard" onClick={closeNavbar}>
                        <div className="brand-icon">
                            <i className="fas fa-film"></i>
                        </div>
                        <div className="brand-text ms-3">
                            <div className="brand-title">Titipan Movies</div>
                            <div className="brand-subtitle">Movie Database</div>
                        </div>
                    </Link>
                    
                    {/* Toggler for Mobile */}
                    <button 
                        className="navbar-toggler border-0 shadow-none" 
                        type="button" 
                        onClick={toggleNavbar}
                        aria-controls="navbarContent" 
                        aria-expanded={!navbarCollapsed} 
                        aria-label="Toggle navigation"
                    >
                        <span className="navbar-toggler-icon"></span>
                    </button>
                    
                    {/* Navbar Content */}
                    <div className={`collapse navbar-collapse ${navbarCollapsed ? '' : 'show'}`} id="navbarContent">
                        {/* Center Menu */}
                        <ul className="navbar-nav mx-auto mb-2 mb-lg-0">
                            <li className="nav-item">
                                <Link 
                                    className={`nav-link ${isActive('/dashboard')}`} 
                                    to="/dashboard"
                                    onClick={closeNavbar}
                                >
                                    <i className="fas fa-tachometer-alt me-2"></i>
                                    Dashboard
                                </Link>
                            </li>
                            <li className="nav-item">
                                <Link 
                                    className={`nav-link ${isActive('/movies')}`} 
                                    to="/movies"
                                    onClick={closeNavbar}
                                >
                                    <i className="fas fa-film me-2"></i>
                                    Movies
                                </Link>
                            </li>
                            <li className="nav-item">
                                <Link 
                                    className={`nav-link ${isActive('/sync')}`} 
                                    to="/sync"
                                    onClick={closeNavbar}
                                >
                                    <i className="fas fa-sync-alt me-2"></i>
                                    API Sync
                                </Link>
                            </li>
                        </ul>
                        
                        {/* Right Menu - Current Page */}
                        <div className="d-none d-lg-block">
                            <span className="navbar-text current-page">
                                <i className="fas fa-location-dot me-2"></i>
                                {getCurrentPageTitle()}
                            </span>
                        </div>
                    </div>
                </div>
            </nav>
            
            {/* Main Content */}
            <main className="main-content">
                <div className="content-wrapper">
                    {children}
                </div>
            </main>

            {/* Footer */}
            <footer className="footer">
                <div className="container-fluid px-4">
                    <div className="row align-items-center">
                        <div className="col-md-6 text-center text-md-start mb-3 mb-md-0">
                            <div className="footer-brand mb-2">
                                <i className="fas fa-film me-2"></i>
                                <strong>Titipan Movies</strong>
                            </div>
                            <div className="footer-text">
                                <i className="fas fa-copyright me-1"></i>
                                {new Date().getFullYear()} All Rights Reserved
                            </div>
                            <div className="footer-text mt-1">
                                <i className="fas fa-database me-1"></i>
                                Powered by <strong>TMDB API</strong>
                            </div>
                        </div>
                        <div className="col-md-6 text-center text-md-end">
                            <div className="footer-developer mb-2">
                                <i className="fas fa-code me-2"></i>
                                Developed by <strong>Muhammad Raga Titipan</strong>
                            </div>
                            <div className="footer-links">
                                <a 
                                    href="https://github.com/mragatitipan" 
                                    target="_blank" 
                                    rel="noopener noreferrer"
                                    className="footer-link"
                                >
                                    <i className="fab fa-github me-1"></i>
                                    GitHub
                                </a>
                                <span className="mx-2">•</span>
                                <a 
                                    href="https://www.themoviedb.org/" 
                                    target="_blank" 
                                    rel="noopener noreferrer"
                                    className="footer-link"
                                >
                                    <i className="fas fa-external-link-alt me-1"></i>
                                    TMDB
                                </a>
                            </div>
                            <div className="footer-tech mt-2">
                                <span className="tech-badge">
                                    <i className="fab fa-laravel me-1"></i>
                                    Laravel 8
                                </span>
                                <span className="tech-badge ms-2">
                                    <i className="fab fa-react me-1"></i>
                                    React 18
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>

            {/* Custom Styles */}
            <style>{`
                /* ============================================================ */
                /* GRADIENT COLORS: #977DFF, #0033FF, #0600AB, #00033D */
                /* ============================================================ */

                /* Navbar Gradient */
                .navbar {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 50%, #0600AB 100%) !important;
                    padding: 1rem 0;
                    border-bottom: 3px solid rgba(255, 255, 255, 0.1);
                }

                /* Brand Styling */
                .navbar-brand {
                    transition: all 0.3s ease;
                }

                .navbar-brand:hover {
                    transform: translateY(-2px);
                }

                .brand-icon {
                    width: 50px;
                    height: 50px;
                    background: rgba(255, 255, 255, 0.2);
                    border-radius: 12px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 1.5rem;
                    transition: all 0.3s ease;
                }

                .navbar-brand:hover .brand-icon {
                    background: rgba(255, 255, 255, 0.3);
                    transform: rotate(5deg);
                }

                .brand-title {
                    font-size: 1.25rem;
                    font-weight: 700;
                    line-height: 1.2;
                    color: white;
                }

                .brand-subtitle {
                    font-size: 0.7rem;
                    opacity: 0.8;
                    font-weight: 400;
                    color: white;
                }

                /* Navigation Links */
                .navbar-nav {
                    gap: 0.5rem;
                }

                .navbar-nav .nav-link {
                    color: rgba(255, 255, 255, 0.9) !important;
                    padding: 0.75rem 1.5rem !important;
                    border-radius: 12px;
                    font-weight: 600;
                    transition: all 0.3s ease;
                    position: relative;
                    overflow: hidden;
                }

                .navbar-nav .nav-link::before {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: -100%;
                    width: 100%;
                    height: 100%;
                    background: rgba(255, 255, 255, 0.1);
                    transition: left 0.3s ease;
                }

                .navbar-nav .nav-link:hover::before {
                    left: 0;
                }

                .navbar-nav .nav-link:hover {
                    color: white !important;
                    transform: translateY(-2px);
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
                }

                /* Active Link */
                .navbar-nav .nav-link.active {
                    background: rgba(255, 255, 255, 0.25) !important;
                    color: white !important;
                    font-weight: 700;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
                }

                .navbar-nav .nav-link.active::after {
                    content: '';
                    position: absolute;
                    bottom: 0;
                    left: 50%;
                    transform: translateX(-50%);
                    width: 40%;
                    height: 3px;
                    background: white;
                    border-radius: 10px;
                }

                /* Current Page Indicator */
                .current-page {
                    background: rgba(255, 255, 255, 0.15);
                    padding: 0.75rem 1.5rem;
                    border-radius: 12px;
                    color: white !important;
                    font-weight: 600;
                    font-size: 0.95rem;
                    backdrop-filter: blur(10px);
                    border: 1px solid rgba(255, 255, 255, 0.2);
                }

                /* Navbar Toggler */
                .navbar-toggler {
                    padding: 0.5rem 0.75rem;
                    border-radius: 8px;
                    background: rgba(255, 255, 255, 0.1);
                }

                .navbar-toggler:focus {
                    box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.25);
                }

                /* Main Content */
                .main-content {
                    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
                    min-height: calc(100vh - 160px);
                    padding: 2.5rem 0;
                }

                .content-wrapper {
                    max-width: 100%;
                    padding: 0 1.5rem;
                }

                /* Footer */
                .footer {
                    background: linear-gradient(135deg, #00033D 0%, #0600AB 50%, #0033FF 100%);
                    color: white;
                    padding: 2.5rem 0;
                    margin-top: auto;
                    border-top: 3px solid rgba(255, 255, 255, 0.1);
                }

                .footer-brand {
                    font-size: 1.25rem;
                    font-weight: 700;
                    color: white;
                }

                .footer-text {
                    font-size: 0.9rem;
                    color: rgba(255, 255, 255, 0.8);
                }

                .footer-developer {
                    font-size: 1rem;
                    color: white;
                    font-weight: 600;
                }

                .footer-link {
                    color: rgba(255, 255, 255, 0.8);
                    text-decoration: none;
                    transition: all 0.3s ease;
                    font-size: 0.9rem;
                }

                .footer-link:hover {
                    color: white;
                    transform: translateY(-2px);
                }

                .footer-tech {
                    font-size: 0.85rem;
                }

                .tech-badge {
                    background: rgba(255, 255, 255, 0.15);
                    padding: 0.4rem 0.8rem;
                    border-radius: 8px;
                    color: white;
                    font-weight: 600;
                    display: inline-block;
                    backdrop-filter: blur(10px);
                    border: 1px solid rgba(255, 255, 255, 0.2);
                }

                /* Sticky Navbar */
                .sticky-top {
                    position: sticky;
                    top: 0;
                    z-index: 1030;
                }

                /* Responsive */
                @media (max-width: 991.98px) {
                    .navbar {
                        padding: 0.75rem 0;
                    }

                    .brand-icon {
                        width: 40px;
                        height: 40px;
                        font-size: 1.2rem;
                    }

                    .brand-title {
                        font-size: 1rem;
                    }

                    .brand-subtitle {
                        font-size: 0.65rem;
                    }

                    .navbar-collapse {
                        background: rgba(0, 0, 0, 0.2);
                        padding: 1.5rem;
                        border-radius: 15px;
                        margin-top: 1rem;
                        backdrop-filter: blur(10px);
                    }

                    .navbar-nav {
                        gap: 0.5rem;
                    }

                    .navbar-nav .nav-link {
                        padding: 1rem 1.25rem !important;
                        margin: 0.25rem 0;
                    }

                    .main-content {
                        padding: 1.5rem 0;
                    }

                    .content-wrapper {
                        padding: 0 1rem;
                    }

                    .footer {
                        padding: 2rem 0;
                    }

                    .footer-brand {
                        font-size: 1.1rem;
                    }

                    .footer-developer {
                        font-size: 0.95rem;
                    }

                    .tech-badge {
                        font-size: 0.75rem;
                        padding: 0.3rem 0.6rem;
                    }
                }

                @media (max-width: 575.98px) {
                    .brand-text {
                        display: none;
                    }

                    .brand-icon {
                        margin: 0;
                    }

                    .footer-text,
                    .footer-links,
                    .footer-tech {
                        font-size: 0.8rem;
                    }
                }

                /* Smooth Scrolling */
                html {
                    scroll-behavior: smooth;
                }

                /* Body */
                body {
                    display: flex;
                    flex-direction: column;
                    min-height: 100vh;
                }

                #root {
                    display: flex;
                    flex-direction: column;
                    min-height: 100vh;
                }

                /* Animations */
                @keyframes fadeIn {
                    from {
                        opacity: 0;
                        transform: translateY(-10px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .navbar-collapse.show {
                    animation: fadeIn 0.3s ease;
                }

                /* Scrollbar */
                ::-webkit-scrollbar {
                    width: 12px;
                    height: 12px;
                }

                ::-webkit-scrollbar-track {
                    background: #f1f1f1;
                }

                ::-webkit-scrollbar-thumb {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 100%);
                    border-radius: 6px;
                }

                ::-webkit-scrollbar-thumb:hover {
                    background: linear-gradient(135deg, #0600AB 0%, #00033D 100%);
                }

                /* Selection */
                ::selection {
                    background: #977DFF;
                    color: white;
                }

                ::-moz-selection {
                    background: #977DFF;
                    color: white;
                }

                /* Focus Visible */
                *:focus-visible {
                    outline: 3px solid rgba(151, 125, 255, 0.5);
                    outline-offset: 2px;
                }

                /* Backdrop Blur Support */
                @supports (backdrop-filter: blur(10px)) {
                    .current-page,
                    .tech-badge,
                    .navbar-collapse {
                        backdrop-filter: blur(10px);
                        -webkit-backdrop-filter: blur(10px);
                    }
                }

                /* Print Styles */
                @media print {
                    .navbar,
                    .footer {
                        display: none !important;
                    }

                    .main-content {
                        padding: 0 !important;
                        background: white !important;
                    }
                }

                /* Accessibility - High Contrast */
                @media (prefers-contrast: high) {
                    .navbar,
                    .footer {
                        border: 2px solid white;
                    }

                    .navbar-nav .nav-link {
                        border: 1px solid rgba(255, 255, 255, 0.5);
                    }
                }

                /* Reduced Motion */
                @media (prefers-reduced-motion: reduce) {
                    *,
                    *::before,
                    *::after {
                        animation-duration: 0.01ms !important;
                        animation-iteration-count: 1 !important;
                        transition-duration: 0.01ms !important;
                    }
                }

                /* Dark Mode Support */
                @media (prefers-color-scheme: dark) {
                    .main-content {
                        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
                    }
                }

                /* Loading State */
                .navbar-nav .nav-link.loading {
                    pointer-events: none;
                    opacity: 0.6;
                }

                /* Hover Effects */
                .footer-link,
                .tech-badge {
                    transition: all 0.3s ease;
                }

                .tech-badge:hover {
                    background: rgba(255, 255, 255, 0.25);
                    transform: translateY(-2px);
                }

                /* Shadow Effects */
                .navbar {
                    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
                }

                .footer {
                    box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15);
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

                .navbar,
                .footer {
                    background-size: 200% 200%;
                    animation: gradientShift 10s ease infinite;
                }

                /* Icon Animations */
                @keyframes iconPulse {
                    0%, 100% {
                        transform: scale(1);
                    }
                    50% {
                        transform: scale(1.1);
                    }
                }

                .navbar-nav .nav-link:hover i {
                    animation: iconPulse 0.5s ease;
                }

                /* Glow Effect */
                .navbar-nav .nav-link.active {
                    box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3),
                                0 0 20px rgba(151, 125, 255, 0.4);
                }

                /* Typography */
                .navbar,
                .footer {
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
                }

                /* Utility Classes */
                .text-gradient {
                    background: linear-gradient(135deg, #977DFF 0%, #0033FF 100%);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                }

                /* Border Radius */
                .rounded-custom {
                    border-radius: 15px !important;
                }

                /* Padding Adjustments */
                .px-custom {
                    padding-left: 2rem !important;
                    padding-right: 2rem !important;
                }

                .py-custom {
                    padding-top: 2rem !important;
                    padding-bottom: 2rem !important;
                }
            `}</style>
        </>
    );
}

export default Layout;

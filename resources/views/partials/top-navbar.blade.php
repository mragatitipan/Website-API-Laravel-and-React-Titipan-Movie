<!DOCTYPE html>
<html lang="en">
<head>
  <!-- ============================================================================
       META & TITLE SECTION
  ============================================================================ -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Navigasi Karek - PT. Rohtek Amanah Global</title>
  
  <!-- ============================================================================
       EXTERNAL CSS & FONTS
  ============================================================================ -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('/ui/css/utilities.css') }}" rel="stylesheet">
  
  <!-- ============================================================================
       BOOTSTRAP JS
  ============================================================================ -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- ============================================================================
       INLINE CSS (Your existing CSS - kept as is)
  ============================================================================ -->
  <style>
  /* ============================================================================
    KAREK V5 - PREMIUM NAVIGATION BAR - FIXED CSS
    PT. Rohtek Amanah Global
    Version: 4.0.2 - Fixed Button Blocking & Route Issues
    Last Updated: 2025
  ============================================================================ */

  /* ============================================================================
    1. BASE & RESET STYLES
  ============================================================================ */
  * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
  }

  body {
    margin-top: -40px;
    font-family: 'Poppins', sans-serif;
    background-color: #f5f5f5;
    overflow-x: hidden;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
  }

  /* ============================================================================
    2. NAVBAR CORE STRUCTURE - FIXED Z-INDEX
  ============================================================================ */
  .navbar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    width: 100%;
    height: 90px;
    padding: 0 50px;
    
    /* Layout */
    display: flex;
    align-items: center;
    justify-content: space-between;
    
    /* Visual */
    box-shadow: 0 3px 20px rgba(0, 0, 0, 0.18);
    border-radius: 0 0 40px 40px;
    
    /* FIXED: Stacking - menurunkan z-index agar tidak block button */
    z-index: 1030 !important; /* Turun dari 9999 */
    isolation: isolate;
    
    /* Overflow */
    overflow: visible !important;
    
    /* Animation */
    transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
    animation: navbarEntrance 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
    
    /* Performance */
    will-change: transform;
    transform: translateZ(0);
    backface-visibility: hidden;
  }

  /* Navbar Gradient Background - Staff Role */
  .navbar-staff {
    background: linear-gradient(-45deg, #213823, #375534, #6B9071, #0F2A1D, #375534, #6B9071);
    background-size: 400% 400%;
    animation: gradient-animation 15s ease infinite;
  }

  /* Navbar Gradient Background - Other Roles */
  .navbar-other {
    background: linear-gradient(-45deg, #213823, #375534, #6B9071, #0F2A1D);
    background-size: 400% 400%;
    animation: gradient-animation 15s ease infinite;
  }

  /* ============================================================================
    3. BRAND & LOGO SECTION
  ============================================================================ */
  .navbar-brand {
    display: flex;
    align-items: center;
    height: 100%;
    padding: 0;
    margin-right: 20px;
    flex-shrink: 0;
    z-index: 1; /* FIXED: Relative z-index */
  }

  .navbar-brand img {
    height: 40px;
    margin-right: 10px;
    animation: logo-pulse 3s ease-in-out infinite;
    will-change: transform;
    transform: translateZ(0);
  }

  .karek-brand-link {
    text-decoration: none;
    display: flex;
    align-items: center;
    height: 100%;
    transition: transform 0.4s ease;
    position: relative;
    z-index: 1; /* FIXED: Tidak block link */
  }

  .karek-brand-link:hover {
    transform: translateY(-2px);
  }

  .karek-logo-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
  }

  .karek-logo-text {
    font-family: 'Poppins', sans-serif;
    font-weight: 700;
    font-size: 1.6rem;
    letter-spacing: 1px;
    color: #ffffff;
    position: relative;
    padding: 0 5px;
    margin: 0;
    line-height: 1.2;
    
    background-image: linear-gradient(90deg, 
      transparent 0%,
      rgba(255,255,255,0.8) 15%, 
      rgba(255,255,255,0.9) 50%,
      rgba(255,255,255,0.8) 85%,
      transparent 100%);
    background-size: 100% 2px;
    background-position: 0 100%;
    background-repeat: no-repeat;
    
    animation: karek-pulse 3s ease-in-out infinite;
    will-change: transform;
    transform: translateZ(0);
  }

  .karek-tagline {
    font-size: 0.5rem;
    color: rgba(255,255,255,0.85);
    letter-spacing: 0.8px;
    font-weight: 400;
    font-family: 'Poppins', sans-serif;
    text-align: center;
    margin-top: 2px;
    margin-bottom: 0;
    animation: tagline-fade 3s ease-in-out infinite;
    will-change: opacity;
  }

  .karek-brand-link:hover .karek-logo-text {
    color: #ffffff;
    text-shadow: 0 0 10px rgba(255,255,255,0.6);
    transition: all 0.3s ease;
  }

  .karek-brand-link:hover .karek-tagline {
    color: #ffffff;
    transition: all 0.3s ease;
  }

  /* ============================================================================
    4. NAVIGATION MENU CONTAINER
  ============================================================================ */
  .nav-menu-container {
    flex: 1;
    height: 100%;
    max-width: calc(100% - 40px);
    margin: 0 20px;
    
    overflow-x: auto;
    overflow-y: hidden !important;
    -webkit-overflow-scrolling: touch;
    
    display: flex;
    align-items: center;
    justify-content: center;
    
    cursor: grab;
    user-select: none;
    white-space: nowrap;
    
    scrollbar-width: thin;
    scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
    
    padding-bottom: 5px;
    position: relative;
    z-index: 1; /* FIXED: Relative z-index */
  }

  .nav-menu-container.grabbing {
    cursor: grabbing;
  }

  .nav-menu-container::-webkit-scrollbar {
    height: 4px;
    width: 0;
  }

  .nav-menu-container::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 4px;
  }

  .nav-menu-container::-webkit-scrollbar-thumb {
    background-color: rgba(255, 255, 255, 0.4);
    border-radius: 4px;
    transition: background-color 0.3s ease;
  }

  .nav-menu-container::-webkit-scrollbar-thumb:hover {
    background-color: rgba(255, 255, 255, 0.6);
  }

  .nav-menu {
    display: inline-flex;
    list-style: none;
    margin: 0;
    padding: 0 20px;
    flex-wrap: nowrap;
    justify-content: center;
    min-width: min-content;
    width: auto;
    height: 100%;
    align-items: center;
    overflow-y: hidden !important;
  }

  @media (min-width: 992px) {
    .nav-menu-container {
      justify-content: center;
    }
    
    .nav-menu-container.wide-menu {
      justify-content: flex-start;
    }
    
    .nav-menu-container.narrow-menu {
      justify-content: center;
    }
  }

  /* ============================================================================
    5. NAVIGATION LINKS & ITEMS - FIXED POINTER EVENTS
  ============================================================================ */
  .nav-item {
    position: relative;
    margin: 0 10px;
    white-space: nowrap;
    flex-shrink: 0;
    height: 100%;
    display: flex;
    align-items: center;
    z-index: 1; /* FIXED: Relative z-index */
  }

  .nav-link {
    color: white;
    font-size: 0.95rem;
    font-weight: 500;
    letter-spacing: 0.2px;
    text-decoration: none;
    
    display: block;
    padding: 8px 16px;
    
    border-radius: 15px;
    
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    cursor: pointer;
    
    position: relative;
    overflow: hidden;
    
    /* FIXED: Pastikan clickable */
    pointer-events: auto !important;
    z-index: 1;
  }

  /* FIXED: Ripple tidak block link */
  .nav-link::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 5px;
    height: 5px;
    background: rgba(255, 255, 255, 0.5);
    opacity: 0;
    border-radius: 100%;
    transform: scale(1, 1) translate(-50%);
    transform-origin: 50% 50%;
    pointer-events: none; /* FIXED: Ripple tidak block click */
  }

  .nav-link:focus:not(:active)::after {
    animation: ripple 1s ease-out;
  }

  .navbar-staff .nav-link:hover {
    background: rgba(255, 255, 255, 0.9);
    color: #1b263b;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
  }

  .navbar-other .nav-link:hover {
    background: rgba(255, 255, 255, 0.9);
    color: #213823;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
  }

  .navbar-staff .active-menu-item {
    background: #4dabf7;
    color: white !important;
    padding: 7px 15px;
    border-radius: 14px;
    box-shadow: 0 2px 10px rgba(77, 171, 247, 0.5);
    font-weight: 600;
    animation: active-pulse 2s infinite ease-in-out;
    will-change: box-shadow;
  }

  .navbar-other .active-menu-item {
    background: #6B9071;
    color: white !important;
    padding: 7px 15px;
    border-radius: 14px;
    box-shadow: 0 2px 10px rgba(107, 144, 113, 0.5);
    font-weight: 600;
    animation: active-pulse 2s infinite ease-in-out;
    will-change: box-shadow;
  }

  /* ============================================================================
    6. DROPDOWN MENUS - FIXED Z-INDEX
  ============================================================================ */
  .nav-item.dropdown {
    position: relative !important;
  }

  .dropdown-menu {
    position: absolute !important;
    top: 100% !important;
    left: 0 !important;
    z-index: 1050 !important; /* FIXED: Di bawah navbar tapi di atas content */
    
    display: none;
    min-width: 220px;
    padding: 12px;
    margin-top: 15px !important;
    
    background-color: #fff;
    border: none;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
    
    overflow: visible !important;
    transform: none !important;
    
    animation: dropdownFadeIn 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
    animation-fill-mode: both;
    
    /* FIXED: Pastikan clickable */
    pointer-events: auto !important;
  }

  .dropdown.show .dropdown-menu {
    display: block !important;
  }

  .dropdown-item {
    color: #333;
    font-weight: 500;
    text-decoration: none;
    
    display: block;
    padding: 10px 15px;
    margin-bottom: 5px;
    
    border-radius: 10px;
    
    transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
    cursor: pointer;
    
    position: relative;
    overflow: hidden;
    
    /* FIXED: Pastikan clickable */
    pointer-events: auto !important;
  }

  /* FIXED: Ripple tidak block click */
  .dropdown-item::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 5px;
    height: 5px;
    background: rgba(0, 0, 0, 0.1);
    opacity: 0;
    border-radius: 100%;
    transform: scale(1, 1) translate(-50%);
    transform-origin: 50% 50%;
    pointer-events: none; /* FIXED */
  }

  .dropdown-item:focus:not(:active)::after {
    animation: ripple-dark 1s ease-out;
  }

  .navbar-staff .dropdown-item:hover {
    background: #4dabf7;
    color: white;
    transform: translateX(5px);
    box-shadow: 0 2px 8px rgba(77, 171, 247, 0.3);
  }

  .navbar-other .dropdown-item:hover {
    background: #6B9071;
    color: white;
    transform: translateX(5px);
    box-shadow: 0 2px 8px rgba(107, 144, 113, 0.3);
  }

  .navbar-staff .dropdown-item:active {
    background: #339af0;
  }

  .navbar-other .dropdown-item:active {
    background: #375534;
  }

  .dropdown-divider {
    margin: 8px 0;
    border-top: 1px solid #e9ecef;
  }

  .dropdown-toggle::after {
    display: inline-block;
    margin-left: 5px;
    vertical-align: middle;
    border-top: 0.3em solid;
    border-right: 0.3em solid transparent;
    border-bottom: 0;
    border-left: 0.3em solid transparent;
    transition: transform 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
  }

  .dropdown.show .dropdown-toggle::after {
    transform: rotate(180deg);
  }

  .dropdown-toggle {
    white-space: nowrap;
    cursor: pointer;
    user-select: none;
    pointer-events: auto !important; /* FIXED */
  }

  .dropdown-toggle:focus {
    outline: none;
  }

  .dropdown-menu[data-bs-popper] {
    position: absolute !important;
    z-index: 1050 !important;
    transform: none !important;
    margin-top: 15px !important;
  }

  .dropdown-menu-end,
  .dropdown-menu-right {
    right: 0 !important;
    left: auto !important;
  }

  /* ============================================================================
    7. USER ACTIONS SECTION - FIXED
  ============================================================================ */
  .user-actions {
    display: flex;
    align-items: center;
    min-width: 150px;
    flex-shrink: 0;
    height: 100%;
    justify-content: flex-end;
    z-index: 1; /* FIXED */
  }

  .fullscreen-btn {
    color: white;
    font-size: 1.5rem;
    margin-right: 20px;
    cursor: pointer;
    transition: transform 0.3s cubic-bezier(0.165, 0.84, 0.44, 1), 
                color 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
    animation: float 3s ease-in-out infinite;
    will-change: transform;
    transform: translateZ(0);
    pointer-events: auto !important; /* FIXED */
  }

  .navbar-staff .fullscreen-btn:hover {
    color: #00e3eb;
    transform: scale(1.2);
  }

  .navbar-other .fullscreen-btn:hover {
    color: #8FBC8F;
    transform: scale(1.2);
  }

  .user-avatar {
    display: flex;
    align-items: center;
    height: 100%;
  }

  .user-avatar .nav-link {
    display: flex;
    align-items: center;
    height: 100%;
    padding: 0 15px;
    border-radius: 0;
    background: transparent !important; /* FIXED */
    pointer-events: auto !important; /* FIXED */
  }

  /* FIXED: Hover hijau transparan, tidak putih */
  .user-avatar .nav-link:hover {
    background: rgba(107, 144, 113, 0.15) !important;
    transform: none !important; /* FIXED: Tidak translateY */
    box-shadow: none !important; /* FIXED: Tidak ada shadow */
  }

  .user-avatar img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    transition: transform 0.3s cubic-bezier(0.165, 0.84, 0.44, 1), 
                border-color 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
    border: 2px solid rgb(255, 255, 255);
    margin-left: 10px;
    animation: avatar-pulse 4s ease-in-out infinite;
    will-change: box-shadow;
    transform: translateZ(0);
  }

  .user-avatar .text-white {
    font-size: 0.9rem;
    font-weight: 500;
  }

  .navbar-staff .user-avatar img:hover {
    transform: scale(1.1);
    border-color: #4dabf7;
  }

  .navbar-other .user-avatar img:hover {
    transform: scale(1.1);
    border-color: #6B9071;
  }

  /* ============================================================================
    8. MOBILE MENU & MODAL - FIXED
  ============================================================================ */
  .mobile-menu-btn {
    display: none;
    color: white;
    font-size: 1.5rem;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
    background: none;
    border: none;
    padding: 0;
    animation: float 3s ease-in-out infinite;
    pointer-events: auto !important; /* FIXED */
  }

  .mobile-menu-btn:focus {
    outline: none;
  }

  .mobile-menu-btn:hover {
    transform: scale(1.1);
    color: rgba(255, 255, 255, 0.9);
  }

  /* FIXED: Modal z-index */
  .modal-menu {
    padding: 0 !important;
    z-index: 1055 !important; /* FIXED: Di atas navbar */
  }

  .modal-backdrop {
    z-index: 1054 !important; /* FIXED: Di bawah modal, di atas navbar */
  }

  .modal-menu .modal-dialog {
    margin: 20px auto !important;
    max-width: 450px;
    width: calc(100% - 40px);
    height: auto;
    max-height: calc(100vh - 40px);
    
    border-radius: 25px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
    
    transform: none !important;
    animation: modalSlideIn 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    
    position: relative;
    z-index: 1056; /* FIXED */
  }

  .modal-menu .modal-content {
    height: 100%;
    border: none;
    border-radius: 25px;
    background: linear-gradient(135deg, #213823, #375534, #6B9071);
    animation: gradientShift 15s ease infinite;
    background-size: 400% 400%;
    overflow: hidden;
    display: flex;
    flex-direction: column;
  }

  .modal-menu .modal-header {
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    padding: 25px 30px;
    background: rgba(0, 0, 0, 0.1);
    flex-shrink: 0;
  }

  .modal-menu .modal-title {
    color: white;
    font-weight: 600;
    font-size: 1.6rem;
    animation: titleFadeIn 0.5s ease-out;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  }

  .modal-menu .btn-close {
    color: white;
    background: transparent url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='white'%3e%3cpath d='M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .293 1.707a1 1 0 010-1.414z'/%3e%3c/svg%3e") center/1em auto no-repeat;
    opacity: 1;
    padding: 1rem;
    margin: -1rem -1rem -1rem auto;
    cursor: pointer;
    transition: transform 0.3s ease;
    pointer-events: auto !important; /* FIXED */
  }

  .modal-menu .btn-close:hover {
    opacity: 0.75;
    transform: rotate(90deg);
  }

  .modal-menu .modal-body {
    padding: 0;
    overflow-y: auto;
    overflow-x: hidden;
    flex: 1;
    -webkit-overflow-scrolling: touch;
  }

  .mobile-nav-menu {
    list-style: none;
    padding: 30px;
    margin: 0;
  }

  .mobile-nav-item {
    margin-bottom: 18px;
    animation: menuItemFadeIn 0.5s ease-out;
    animation-fill-mode: both;
  }

  .mobile-nav-item:nth-child(1) { animation-delay: 0.1s; }
  .mobile-nav-item:nth-child(2) { animation-delay: 0.15s; }
  .mobile-nav-item:nth-child(3) { animation-delay: 0.2s; }
  .mobile-nav-item:nth-child(4) { animation-delay: 0.25s; }
  .mobile-nav-item:nth-child(5) { animation-delay: 0.3s; }
  .mobile-nav-item:nth-child(6) { animation-delay: 0.35s; }
  .mobile-nav-item:nth-child(7) { animation-delay: 0.4s; }
  .mobile-nav-item:nth-child(8) { animation-delay: 0.45s; }

  .mobile-nav-link {
    color: white;
    font-size: 1.1rem;
    font-weight: 500;
    text-decoration: none;
    display: block;
    padding: 14px 20px;
    border-radius: 18px;
    transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
    background: rgba(255, 255, 255, 0.1);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
    pointer-events: auto !important; /* FIXED */
  }

  .mobile-nav-link::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 5px;
    height: 5px;
    background: rgba(255, 255, 255, 0.5);
    opacity: 0;
    border-radius: 100%;
    transform: scale(1, 1) translate(-50%);
    transform-origin: 50% 50%;
    pointer-events: none; /* FIXED */
  }

  .mobile-nav-link:focus:not(:active)::after {
    animation: ripple 1s ease-out;
  }

  .mobile-nav-link:hover,
  .mobile-nav-link:focus {
    background: rgba(255, 255, 255, 0.2);
    transform: translateX(5px);
    color: white;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
  }

  .mobile-nav-link.active {
    background: rgba(255, 255, 255, 0.25);
    font-weight: 600;
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
  }

  .mobile-dropdown-toggle {
    display: flex;
    justify-content: space-between;
    align-items: center;
    pointer-events: auto !important; /* FIXED */
  }

  .mobile-dropdown-toggle::after {
    content: '\f107';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    transition: transform 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
  }

  .mobile-dropdown-toggle[aria-expanded="true"]::after {
    transform: rotate(180deg);
  }

  .mobile-dropdown-menu {
    list-style: none;
    padding: 5px 0 5px 25px;
    margin: 15px 0 5px 0;
    display: none;
    border-left: 2px dashed rgba(255, 255, 255, 0.3);
  }

  .mobile-dropdown-menu.show {
    display: block;
    animation: dropdownSlideDown 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
  }

  .mobile-dropdown-item {
    margin-bottom: 12px;
    animation: dropdownItemFadeIn 0.4s ease-out;
    animation-fill-mode: both;
  }

  .mobile-dropdown-item:nth-child(1) { animation-delay: 0.1s; }
  .mobile-dropdown-item:nth-child(2) { animation-delay: 0.15s; }
  .mobile-dropdown-item:nth-child(3) { animation-delay: 0.2s; }
  .mobile-dropdown-item:nth-child(4) { animation-delay: 0.25s; }
  .mobile-dropdown-item:nth-child(5) { animation-delay: 0.3s; }

  .mobile-dropdown-link {
    color: rgba(255, 255, 255, 0.9);
    font-size: 1rem;
    text-decoration: none;
    display: block;
    padding: 12px 18px;
    border-radius: 15px;
    transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
    background: rgba(255, 255, 255, 0.05);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
    pointer-events: auto !important; /* FIXED */
  }

  .mobile-dropdown-link::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 5px;
    height: 5px;
    background: rgba(255, 255, 255, 0.5);
    opacity: 0;
    border-radius: 100%;
    transform: scale(1, 1) translate(-50%);
    transform-origin: 50% 50%;
    pointer-events: none; /* FIXED */
  }

  .mobile-dropdown-link:focus:not(:active)::after {
    animation: ripple 1s ease-out;
  }

  .mobile-dropdown-link:hover,
  .mobile-dropdown-link:focus {
    background: rgba(255, 255, 255, 0.15);
    transform: translateX(5px);
    color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
  }

  .mobile-user-info {
    display: flex;
    align-items: center;
    padding: 25px;
    background: rgba(0, 0, 0, 0.2);
    margin: 30px 30px 25px 30px;
    border-radius: 20px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    animation: userInfoFadeIn 0.5s 0.3s ease-out;
    animation-fill-mode: both;
    border: 1px solid rgba(255, 255, 255, 0.1);
  }

  .mobile-user-avatar {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    border: 3px solid white;
    margin-right: 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    animation: avatarPulse 4s ease-in-out infinite;
    flex-shrink: 0;
  }

  .mobile-user-details {
    flex: 1;
    min-width: 0;
  }

  .mobile-user-name {
    color: white;
    font-size: 1.3rem;
    font-weight: 600;
    margin: 0 0 8px 0;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .mobile-user-role {
    color: rgba(255, 255, 255, 0.85);
    font-size: 0.95rem;
    margin: 0;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
  }

  .mobile-user-actions {
    margin-top: 16px;
    display: flex;
    gap: 12px;
  }

  .mobile-user-btn {
    color: white;
    background: rgba(255, 255, 255, 0.15);
    border: none;
    border-radius: 12px;
    padding: 11px 18px;
    font-size: 0.95rem;
    transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
    text-decoration: none;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    position: relative;
    overflow: hidden;
    flex: 1;
    text-align: center;
    pointer-events: auto !important; /* FIXED */
  }

  .mobile-user-btn::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 5px;
    height: 5px;
    background: rgba(255, 255, 255, 0.5);
    opacity: 0;
    border-radius: 100%;
    transform: scale(1, 1) translate(-50%);
    transform-origin: 50% 50%;
    pointer-events: none; /* FIXED */
  }

  .mobile-user-btn:focus:not(:active)::after {
    animation: ripple 1s ease-out;
  }

  .mobile-user-btn:hover {
    background: rgba(255, 255, 255, 0.25);
    color: white;
    text-decoration: none;
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
  }

  .mobile-user-btn i {
    margin-right: 8px;
  }

  .modal-body::-webkit-scrollbar {
    width: 6px;
  }

  .modal-body::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
  }

  .modal-body::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.3);
    border-radius: 10px;
  }

  .modal-body::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.5);
  }

  /* ============================================================================
    9. ANIMATIONS & KEYFRAMES
  ============================================================================ */

  @keyframes gradient-animation {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }

  @keyframes logo-pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
  }

  @keyframes karek-pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.02); }
    100% { transform: scale(1); }
  }

  @keyframes tagline-fade {
    0% { opacity: 0.85; }
    50% { opacity: 1; }
    100% { opacity: 0.85; }
  }

  @keyframes float {
    0% { transform: translateY(0px); }
    50% { transform: translateY(-5px); }
    100% { transform: translateY(0px); }
  }

  @keyframes avatar-pulse {
    0% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4); }
    70% { box-shadow: 0 0 0 10px rgba(255, 255, 255, 0); }
    100% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0); }
  }

  @keyframes active-pulse {
    0% { box-shadow: 0 2px 6px rgba(107, 144, 113, 0.4); }
    50% { box-shadow: 0 2px 15px rgba(107, 144, 113, 0.7); }
    100% { box-shadow: 0 2px 6px rgba(107, 144, 113, 0.4); }
  }

  @keyframes dropdownFadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
  }

  @keyframes ripple {
    0% {
      transform: scale(0, 0);
      opacity: 0.5;
    }
    20% {
      transform: scale(25, 25);
      opacity: 0.3;
    }
    100% {
      opacity: 0;
      transform: scale(40, 40);
    }
  }

  @keyframes ripple-dark {
    0% {
      transform: scale(0, 0);
      opacity: 0.5;
    }
    20% {
      transform: scale(25, 25);
      opacity: 0.3;
    }
    100% {
      opacity: 0;
      transform: scale(40, 40);
    }
  }

  @keyframes navbarEntrance {
    from { transform: translateY(-100%); }
    to { transform: translateY(0); }
  }

  @keyframes modalSlideIn {
    from { opacity: 0; transform: translateY(-30px); }
    to { opacity: 1; transform: translateY(0); }
  }

  @keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }

  @keyframes titleFadeIn {
    from { opacity: 0; transform: translateX(-10px); }
    to { opacity: 1; transform: translateX(0); }
  }

  @keyframes menuItemFadeIn {
    from { opacity: 0; transform: translateX(-20px); }
    to { opacity: 1; transform: translateX(0); }
  }

  @keyframes userInfoFadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }

  @keyframes avatarPulse {
    0% { box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); }
    50% { box-shadow: 0 4px 15px rgba(255, 255, 255, 0.3); }
    100% { box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); }
  }

  @keyframes dropdownSlideDown {
    from { opacity: 0; transform: translateY(-15px); }
    to { opacity: 1; transform: translateY(0); }
  }

  @keyframes dropdownItemFadeIn {
    from { opacity: 0; transform: translateX(-10px); }
    to { opacity: 1; transform: translateX(0); }
  }

  @keyframes mainContentEntrance {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }

  @keyframes ripple-animation {
    to {
      transform: scale(20);
      opacity: 0;
    }
  }

  /* ============================================================================
    10. MAIN CONTENT
  ============================================================================ */
  main {
    margin-top: 100px;
    padding: 20px;
    animation: mainContentEntrance 0.5s 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
    animation-fill-mode: both;
    position: relative;
    z-index: 1; /* FIXED: Di bawah navbar */
  }

  /* ============================================================================
    11. RESPONSIVE DESIGN
  ============================================================================ */

  /* Tablet (992px and below) */
  @media (max-width: 992px) {
    .navbar {
      padding: 0 30px;
      height: 80px;
    }
    
    .nav-item {
      margin: 0 8px;
    }
    
    .nav-menu-container {
      margin: 0 15px;
    }
    
    .karek-logo-text {
      font-size: 1.5rem;
    }
    
    .karek-tagline {
      font-size: 0.48rem;
    }
  }

  /* Mobile (768px and below) */
  @media (max-width: 768px) {
    .navbar {
      padding: 0 25px;
      border-radius: 0 0 30px 30px;
      height: 90px;
      justify-content: space-between;
    }
    
    main {
      margin-top: 100px;
    }
    
    .navbar-brand {
      margin-right: 0;
    }
    
    .karek-logo-text {
      font-size: 1.4rem;
    }
    
    .karek-tagline {
      font-size: 0.45rem;
    }
    
    .nav-menu-container,
    .fullscreen-btn,
    .user-avatar {
      display: none;
    }
    
    .mobile-menu-btn {
      display: block;
      font-size: 2rem;
      padding: 10px;
    }
    
    .user-actions {
      min-width: unset;
    }
    
    /* FIXED: Modal untuk mobile */
    .modal-menu .modal-dialog {
      max-width: 90%;
      width: 90%;
      margin: 20px auto !important;
      max-height: calc(100vh - 40px);
    }
  }

  /* Small Mobile (576px and below) */
  @media (max-width: 576px) {
    .navbar {
      padding: 0 20px;
      height: 85px;
    }
    
    .karek-logo-text {
      font-size: 1.3rem;
    }
    
    .karek-tagline {
      font-size: 0.42rem;
    }
    
    .mobile-menu-btn {
      font-size: 1.8rem;
    }
    
    .modal-menu .modal-dialog {
      max-width: 92%;
      width: 92%;
      margin: 18px auto !important;
      max-height: calc(100vh - 36px);
      border-radius: 22px;
    }
    
    .modal-menu .modal-content {
      border-radius: 22px;
    }
    
    .modal-menu .modal-header {
      padding: 22px 26px;
    }
    
    .modal-menu .modal-title {
      font-size: 1.5rem;
    }
    
    .mobile-nav-menu {
      padding: 25px 22px;
    }
    
    .mobile-user-info {
      margin: 25px 22px 20px 22px;
      padding: 22px;
    }
    
    .mobile-user-avatar {
      width: 65px;
      height: 65px;
    }
    
    .mobile-user-name {
      font-size: 1.2rem;
    }
    
    .mobile-user-role {
      font-size: 0.9rem;
    }
    
    .mobile-nav-link {
      font-size: 1.05rem;
      padding: 13px 18px;
    }
    
    .mobile-dropdown-link {
      font-size: 0.95rem;
      padding: 11px 16px;
    }
  }

  /* Extra Small (480px and below) */
  @media (max-width: 480px) {
    .navbar {
      height: 80px;
      padding: 0 15px;
    }
    
    .karek-logo-text {
      font-size: 1.2rem;
    }
    
    .karek-tagline {
      font-size: 0.4rem;
    }
    
    .modal-menu .modal-dialog {
      max-width: 94%;
      width: 94%;
      margin: 16px auto !important;
      max-height: calc(100vh - 32px);
      border-radius: 20px;
    }
    
    .modal-menu .modal-content {
      border-radius: 20px;
    }
    
    .modal-menu .modal-header {
      padding: 20px 24px;
    }
    
    .modal-menu .modal-title {
      font-size: 1.4rem;
    }
    
    .mobile-nav-menu {
      padding: 22px 20px;
    }
    
    .mobile-user-info {
      margin: 22px 20px 18px 20px;
      padding: 20px;
    }
    
    .mobile-user-avatar {
      width: 60px;
      height: 60px;
    }
    
    .mobile-user-name {
      font-size: 1.15rem;
    }
    
    .mobile-user-role {
      font-size: 0.88rem;
    }
    
    .mobile-nav-link {
      font-size: 1rem;
      padding: 12px 16px;
    }
    
    .mobile-dropdown-link {
      font-size: 0.92rem;
      padding: 10px 14px;
    }
  }

  /* Very Small (360px and below) */
  @media (max-width: 360px) {
    .navbar {
      height: 75px;
      padding: 0 12px;
    }
    
    .karek-logo-text {
      font-size: 1.1rem;
    }
    
    .karek-tagline {
      font-size: 0.38rem;
    }
    
    .modal-menu .modal-dialog {
      max-width: 95%;
      width: 95%;
      margin: 14px auto !important;
      max-height: calc(100vh - 28px);
    }
    
    .mobile-nav-menu {
      padding: 20px 18px;
    }
    
    .mobile-user-info {
      margin: 20px 18px 16px 18px;
      padding: 18px;
      flex-direction: column;
      text-align: center;
    }
    
    .mobile-user-avatar {
      width: 55px;
      height: 55px;
      margin-right: 0;
      margin-bottom: 12px;
    }
    
    .mobile-user-name {
      font-size: 1.1rem;
    }
    
    .mobile-user-role {
      font-size: 0.82rem;
    }
    
    .mobile-user-actions {
      width: 100%;
      margin-top: 12px;
    }
    
    .mobile-user-btn {
      font-size: 0.88rem;
      padding: 10px 15px;
    }
    
    .mobile-nav-link {
      font-size: 0.95rem;
      padding: 11px 14px;
    }
  }

  /* Landscape Mode */
  @media (max-width: 768px) and (orientation: landscape) {
    .navbar {
      height: 70px;
    }
    
    .modal-menu .modal-dialog {
      max-width: 85%;
      width: 85%;
      margin: 12px auto !important;
      max-height: calc(100vh - 24px);
    }
    
    .mobile-user-info {
      padding: 16px 18px;
      margin: 18px;
    }
    
    .mobile-user-avatar {
      width: 50px;
      height: 50px;
    }
    
    .mobile-user-name {
      font-size: 1.1rem;
    }
    
    .mobile-user-role {
      font-size: 0.85rem;
    }
    
    .mobile-nav-link {
      padding: 10px 15px;
      font-size: 0.95rem;
    }
    
    .mobile-dropdown-link {
      padding: 9px 13px;
      font-size: 0.88rem;
    }
  }

  /* ============================================================================
    12. UTILITY CLASSES
  ============================================================================ */

  .ripple-effect {
    position: absolute;
    background: rgba(255, 255, 255, 0.4);
    border-radius: 50%;
    pointer-events: none;
    width: 10px;
    height: 10px;
    transform: scale(0);
    animation: ripple-animation 0.6s ease-out;
  }

  /* ============================================================================
    13. BROWSER-SPECIFIC FIXES
  ============================================================================ */

  /* Safari iOS Fix */
  @supports (-webkit-touch-callout: none) {
    .navbar {
      -webkit-transform: translateZ(0);
    }
    
    .modal-menu .modal-dialog {
      -webkit-transform: translateZ(0);
    }
    
    .modal-menu .modal-body {
      -webkit-overflow-scrolling: touch;
    }
  }

  /* Firefox Scrollbar */
  @-moz-document url-prefix() {
    .nav-menu-container {
      scrollbar-width: thin;
      scrollbar-color: rgba(255, 255, 255, 0.4) transparent;
    }
  }

  /* ============================================================================
    14. ACCESSIBILITY
  ============================================================================ */

  .nav-link:focus-visible,
  .dropdown-item:focus-visible,
  .mobile-nav-link:focus-visible,
  .mobile-dropdown-link:focus-visible {
    outline: 2px solid rgba(255, 255, 255, 0.6);
    outline-offset: 2px;
  }

  @media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
      animation-duration: 0.01ms !important;
      animation-iteration-count: 1 !important;
      transition-duration: 0.01ms !important;
    }
  }

  @media (prefers-contrast: high) {
    .navbar {
      border: 2px solid white;
    }
    
    .nav-link,
    .dropdown-item {
      border: 1px solid rgba(255, 255, 255, 0.3);
    }
  }

  /* ============================================================================
    15. PRINT STYLES
  ============================================================================ */
  @media print {
    .navbar {
      position: static;
      box-shadow: none;
    }
    
    .mobile-menu-btn,
    .fullscreen-btn {
      display: none;
    }
    
    main {
      margin-top: 0;
    }
  }

  /* ============================================================================
    16. CUSTOM SCROLLBAR
  ============================================================================ */
  ::-webkit-scrollbar {
    width: 8px;
    height: 8px;
  }

  ::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.05);
    border-radius: 10px;
  }

  ::-webkit-scrollbar-thumb {
    background: rgba(107, 144, 113, 0.5);
    border-radius: 10px;
    transition: background 0.3s ease;
  }

  ::-webkit-scrollbar-thumb:hover {
    background: rgba(107, 144, 113, 0.7);
  }

  /* ============================================================================
    17. LOADING STATE
  ============================================================================ */
  .navbar.loading {
    opacity: 0;
    transform: translateY(-100%);
  }

  .navbar.loaded {
    opacity: 1;
    transform: translateY(0);
    transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
  }

  /* ============================================================================
    18. TOUCH DEVICE OPTIMIZATIONS
  ============================================================================ */
  .touch-device .nav-link,
  .touch-device .dropdown-item,
  .touch-device .mobile-nav-link {
    -webkit-tap-highlight-color: rgba(255, 255, 255, 0.1);
    tap-highlight-color: rgba(255, 255, 255, 0.1);
  }

  /* ============================================================================
    END OF KAREK V5 FIXED CSS
  ============================================================================ */
  </style>
</head>

<body>
<!-- ============================================================================
     MAIN NAVIGATION BAR
============================================================================ -->
<nav class="navbar {{ Auth::user()->role === 'Staff' ? 'navbar-staff' : 'navbar-other' }}">
  
  <!-- ============================================================================
       BRAND LOGO SECTION
  ============================================================================ -->
  <div class="navbar-brand">
    <a href="{{ url('dashboard') }}" class="karek-brand-link">
      <div class="karek-logo-container">
        <span class="karek-logo-text">KAREK V5</span>
        <small class="karek-tagline">Key Analysis, Results, Execution, Knowledge</small>
      </div>
    </a>
  </div>

  <!-- ============================================================================
       DESKTOP NAVIGATION MENU CONTAINER
  ============================================================================ -->
  <div class="nav-menu-container" id="navMenuContainer">
    <ul class="nav-menu">
      
      <!-- ============================================================================
           SEARCH DROPDOWN (Visible Only XS - DO NOT DELETE)
      ============================================================================ -->
      <li class="nav-item dropdown no-arrow d-sm-none">
        <a class="nav-link dropdown-toggle text-white" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-search fa-fw"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
          <form class="form-inline mr-auto w-100 navbar-search">
            <div class="input-group">
              <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
              <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li>
 
      <!-- ============================================================================
           MENU FOR ADMIN ROLE
      ============================================================================ -->
      @if(Auth::user()->role === 'Admin')
      <li class="nav-item">
        <a href="{{ url('dashboard') }}" class="nav-link {{ request()->is('dashboard*') ? 'active-menu-item' : '' }}">
          <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
        </a>
      </li>
     
      <li class="nav-item">
        <a href="{{ url('home') }}" class="nav-link {{ request()->is('home*') ? 'active-menu-item' : '' }}">
          <i class="fas fa-user-shield mr-2"></i> Akun Karek
        </a>
      </li>
      @endif

  <!-- ============================================================================
       USER ACTIONS SECTION (Fullscreen & Avatar)
  ============================================================================ -->
  <div class="user-actions">
    <!-- Fullscreen Button -->
    <i class="fas fa-expand fullscreen-btn" id="fullscreenBtn" title="Toggle Fullscreen"></i>
    
    <!-- User Avatar Dropdown -->
    <div class="user-avatar dropdown">
      <a href="#" class="nav-link dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="text-white">{{ Auth::user()->name }}</span>
        <img src="{{ asset('agency/assets/img/rohtek1.png') }}" alt="User Avatar">
      </a>
      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
        <li>
          <a href="{{ url('/home/'. auth()->user()->id .'/profile') }}" class="dropdown-item {{ request()->is('home/*/profile') ? 'active' : '' }}">
            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Profile
          </a>
        </li>
        <li><hr class="dropdown-divider"></li>
        <li>
          <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#logoutModal">
            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout
          </a>
        </li>
      </ul>
    </div>
    
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" data-bs-toggle="modal" data-bs-target="#mobileMenuModal">
      <i class="fas fa-bars"></i>
    </button>
  </div>
</nav>

<!-- ============================================================================
     MOBILE MENU MODAL
============================================================================ -->
<div class="modal fade modal-menu" id="mobileMenuModal" tabindex="-1" aria-labelledby="mobileMenuModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <!-- Modal Header -->
      <div class="modal-header">
        <h5 class="modal-title" id="mobileMenuModalLabel">KAREK V5 Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <!-- Modal Body -->
      <div class="modal-body">
        
        <!-- ============================================================================
             MOBILE USER INFO SECTION
        ============================================================================ -->
        <div class="mobile-user-info">
          <img src="{{ asset('agency/assets/img/rohtek1.png') }}" alt="User Avatar" class="mobile-user-avatar">
          <div class="mobile-user-details">
            <h5 class="mobile-user-name">{{ Auth::user()->name }}</h5>
            <p class="mobile-user-role">{{ Auth::user()->role }}</p>
            <div class="mobile-user-actions">
              <a href="{{ url('/home/'. auth()->user()->id .'/profile') }}" class="mobile-user-btn">
                <i class="fas fa-user"></i> Profile
              </a>
              <a href="#" class="mobile-user-btn" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="fas fa-sign-out-alt"></i> Logout
              </a>
            </div>
          </div>
        </div>
        
        <!-- ============================================================================
             MOBILE NAVIGATION MENU
        ============================================================================ -->
        <ul class="mobile-nav-menu">
          
          <!-- Mobile Menu untuk Admin -->
          @if(Auth::user()->role === 'Admin')
          <li class="mobile-nav-item">
            <a href="{{ url('dashboard') }}" class="mobile-nav-link {{ request()->is('dashboard*') ? 'active' : '' }}">
              <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
            </a>
          </li>
          
          
          <li class="mobile-nav-item">
            <a href="{{ url('home') }}" class="mobile-nav-link {{ request()->is('home*') && !request()->is('home/*/profile') ? 'active' : '' }}">
              <i class="fas fa-user-shield mr-2"></i> Akun Karek
            </a>
          </li>
          @endif

        </ul>
      </div>
    </div>
  </div>
</div>

<!-- ============================================================================
     MAIN CONTENT AREA
============================================================================ -->
<main>
  <!-- Your page content goes here -->
</main>

<!-- ============================================================================
     JAVASCRIPT SECTION
============================================================================ -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  
  // ============================================================================
  // FULLSCREEN FUNCTIONALITY
  // ============================================================================
  const fullscreenBtn = document.getElementById('fullscreenBtn');

  /**
   * Toggle fullscreen mode
   */
  function toggleFullscreen() {
    if (!document.fullscreenElement) {
      // Enter fullscreen
      document.documentElement.requestFullscreen().catch(err => {
        console.warn(`Error attempting to enable fullscreen: ${err.message}`);
      });
      fullscreenBtn.classList.replace('fa-expand', 'fa-compress');
      localStorage.setItem('fullscreen', 'true');
    } else {
      // Exit fullscreen
      if (document.exitFullscreen) {
        document.exitFullscreen();
        fullscreenBtn.classList.replace('fa-compress', 'fa-expand');
        localStorage.setItem('fullscreen', 'false');
      }
    }
  }

  // Check if fullscreen was previously enabled
  if (localStorage.getItem('fullscreen') === 'true') {
    try {
      document.documentElement.requestFullscreen();
      fullscreenBtn.classList.replace('fa-expand', 'fa-compress');
    } catch (err) {
      console.warn(`Error attempting to restore fullscreen: ${err.message}`);
    }
  }

  // Add click event to fullscreen button
  if (fullscreenBtn) {
    fullscreenBtn.addEventListener('click', toggleFullscreen);
  }

  // Update button icon when exiting fullscreen via Escape key
  document.addEventListener('fullscreenchange', () => {
    if (!document.fullscreenElement) {
      fullscreenBtn.classList.replace('fa-compress', 'fa-expand');
      localStorage.setItem('fullscreen', 'false');
    }
  });

  // ============================================================================
  // DROPDOWN POSITIONING ENHANCEMENT
  // ============================================================================
  const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
  
  dropdownToggles.forEach(toggle => {
    toggle.addEventListener('click', function(e) {
      const dropdown = this.closest('.dropdown');
      const menu = dropdown.querySelector('.dropdown-menu');
      
      if (!menu) return;
      
      // Position the dropdown menu
      menu.style.position = 'absolute';
      menu.style.top = '100%';
      menu.style.left = '0';
      menu.style.zIndex = '1050';
      
      // Prevent dropdown from going off-screen
      const menuRect = menu.getBoundingClientRect();
      const viewportWidth = window.innerWidth;
      
      if (menuRect.right > viewportWidth - 10) {
        menu.style.left = 'auto';
        menu.style.right = '0';
      }
    });
  });

  // ============================================================================
  // HORIZONTAL SCROLL FOR NAVBAR
  // ============================================================================
  const navMenuContainer = document.getElementById('navMenuContainer');
  
  if (navMenuContainer) {
    let isDown = false;
    let startX;
    let scrollLeft;
    
    // Mouse events for desktop
    navMenuContainer.addEventListener('mousedown', (e) => {
      if (!e.target.classList.contains('dropdown-toggle') && 
          !e.target.closest('.dropdown-menu')) {
        isDown = true;
        navMenuContainer.classList.add('grabbing');
        startX = e.pageX - navMenuContainer.offsetLeft;
        scrollLeft = navMenuContainer.scrollLeft;
        e.preventDefault();
      }
    });

    navMenuContainer.addEventListener('mouseleave', () => {
      isDown = false;
      navMenuContainer.classList.remove('grabbing');
    });

    navMenuContainer.addEventListener('mouseup', () => {
      isDown = false;
      navMenuContainer.classList.remove('grabbing');
    });

    navMenuContainer.addEventListener('mousemove', (e) => {
      if (!isDown) return;
      e.preventDefault();
      const x = e.pageX - navMenuContainer.offsetLeft;
      const walk = (x - startX) * 2;
      navMenuContainer.scrollLeft = scrollLeft - walk;
    });

    // Touch events for mobile
    navMenuContainer.addEventListener('touchstart', (e) => {
      if (!e.target.classList.contains('dropdown-toggle') && 
          !e.target.closest('.dropdown-menu')) {
        isDown = true;
        startX = e.touches[0].pageX - navMenuContainer.offsetLeft;
        scrollLeft = navMenuContainer.scrollLeft;
      }
    }, { passive: true });

    navMenuContainer.addEventListener('touchend', () => {
      isDown = false;
    });

    navMenuContainer.addEventListener('touchmove', (e) => {
      if (!isDown) return;
      const x = e.touches[0].pageX - navMenuContainer.offsetLeft;
      const walk = (x - startX) * 2;
      navMenuContainer.scrollLeft = scrollLeft - walk;
    }, { passive: true });

    /**
     * Ensure all menu items are visible
     */
    function fixMenuVisibility() {
      const navMenu = navMenuContainer.querySelector('.nav-menu');
      if (!navMenu) return;
      
      const menuItems = navMenu.querySelectorAll('.nav-item');
      let totalWidth = 0;
      
      menuItems.forEach(item => {
        const style = window.getComputedStyle(item);
        const itemWidth = item.offsetWidth + 
                        parseInt(style.marginLeft) + 
                        parseInt(style.marginRight);
        totalWidth += itemWidth;
      });
      
      navMenu.style.minWidth = (totalWidth + 40) + 'px';
      
      if (navMenu.offsetWidth < navMenuContainer.offsetWidth * 0.8) {
        navMenuContainer.classList.add('narrow-menu');
        navMenuContainer.classList.remove('wide-menu');
      } else {
        navMenuContainer.classList.add('wide-menu');
        navMenuContainer.classList.remove('narrow-menu');
      }
    }

    fixMenuVisibility();
    window.addEventListener('resize', fixMenuVisibility);
  }

  // ============================================================================
  // ACTIVE MENU HIGHLIGHTING
  // ============================================================================
  
  /**
   * Set active menu items based on current URL
   */
  function setActiveMenuItem() {
    const currentPath = window.location.pathname;
    console.log("Setting active menu for path:", currentPath);
    
    // Clear all active states
    document.querySelectorAll('.active-menu-item, .active').forEach(el => {
      el.classList.remove('active-menu-item', 'active');
    });
    
    // Desktop menu items
    document.querySelectorAll('.nav-link').forEach(link => {
      const href = link.getAttribute('href');
      if (!href) return;
      
      let hrefPath = href;
      if (href.includes('://')) {
        try {
          const url = new URL(href);
          hrefPath = url.pathname;
        } catch (e) {
          return;
        }
      }
      
      // Special case for dashboard
      if ((hrefPath.endsWith('/dashboard') || hrefPath === '/') && 
          (currentPath.endsWith('/dashboard') || currentPath === '/')) {
        link.classList.add('active-menu-item');
        return;
      }
      
      // Check if current path matches
      if (currentPath === hrefPath || 
          (hrefPath !== '/' && currentPath.startsWith(hrefPath) && 
           (currentPath.length === hrefPath.length || currentPath[hrefPath.length] === '/'))) {
        link.classList.add('active-menu-item');
        
        // Activate parent dropdown if exists
        const dropdownParent = link.closest('.dropdown');
        if (dropdownParent) {
          const dropdownToggle = dropdownParent.querySelector('.dropdown-toggle');
          if (dropdownToggle) {
            dropdownToggle.classList.add('active');
          }
        }
      }
    });
    
    // Mobile menu items
    document.querySelectorAll('.mobile-nav-link, .mobile-dropdown-link').forEach(link => {
      const href = link.getAttribute('href');
      if (!href) return;
      
      let hrefPath = href;
      if (href.includes('://')) {
        try {
          const url = new URL(href);
          hrefPath = url.pathname;
        } catch (e) {
          return;
        }
      }
      
      // Special case for dashboard
      if ((hrefPath.endsWith('/dashboard') || hrefPath === '/') && 
          (currentPath.endsWith('/dashboard') || currentPath === '/')) {
        link.classList.add('active');
        return;
      }
      
      if (currentPath === hrefPath || 
          (hrefPath !== '/' && currentPath.startsWith(hrefPath) && 
           (currentPath.length === hrefPath.length || currentPath[hrefPath.length] === '/'))) {
        link.classList.add('active');
        
        // Expand parent dropdown
        const dropdownMenu = link.closest('.mobile-dropdown-menu');
        if (dropdownMenu) {
          dropdownMenu.classList.add('show');
          
          const dropdownId = dropdownMenu.getAttribute('id');
          if (dropdownId) {
            const toggle = document.querySelector(`[data-bs-target="#${dropdownId}"]`);
            if (toggle) {
              toggle.setAttribute('aria-expanded', 'true');
              toggle.classList.add('active');
            }
          }
        }
      }
    });
  }

  setActiveMenuItem();
  window.addEventListener('popstate', setActiveMenuItem);

  // ============================================================================
  // RIPPLE EFFECT FOR BUTTONS AND LINKS
  // ============================================================================
  
  /**
   * Create ripple effect on click
   */
  function createRippleEffect() {
    const buttons = document.querySelectorAll('.nav-link, .dropdown-item, .mobile-nav-link, .mobile-dropdown-link, .mobile-user-btn');
    
    buttons.forEach(button => {
      button.addEventListener('click', function(e) {
        const rect = this.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        
        const ripple = document.createElement('span');
        ripple.classList.add('ripple-effect');
        ripple.style.left = `${x}px`;
        ripple.style.top = `${y}px`;
        
        this.appendChild(ripple);
        
        setTimeout(() => {
          ripple.remove();
        }, 600);
      });
    });
  }

  createRippleEffect();

  // ============================================================================
  // STAGGERED ANIMATION FOR MENU ITEMS
  // ============================================================================
  
  /**
   * Animate menu items on page load
   */
  function animateMenuItems() {
    if (performance.navigation.type !== 1) {
      const menuItems = document.querySelectorAll('.nav-item');
      
      menuItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateY(-10px)';
        item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        
        setTimeout(() => {
          item.style.opacity = '1';
          item.style.transform = 'translateY(0)';
        }, 100 + (index * 50));
      });
    }
  }
  
  animateMenuItems();

  // ============================================================================
  // RESPONSIVE ADJUSTMENTS
  // ============================================================================
  
  /**
   * Handle responsive layout changes
   */
  function handleResponsiveLayout() {
    const navbar = document.querySelector('.navbar');
    
    if (window.innerWidth <= 768) {
      // Mobile adjustments
      if (navbar) {
        navbar.style.borderRadius = '0 0 30px 30px';
        navbar.style.height = '90px';
      }
      
      const modalMenu = document.getElementById('mobileMenuModal');
      if (modalMenu) {
        const modalDialog = modalMenu.querySelector('.modal-dialog');
        if (modalDialog) {
          modalDialog.style.margin = '15px';
          modalDialog.style.width = 'calc(100% - 30px)';
          modalDialog.style.height = 'calc(100vh - 30px)';
        }
      }
    } else if (window.innerWidth <= 992) {
      // Tablet adjustments
      if (navbar) {
        navbar.style.borderRadius = '0 0 35px 35px';
        navbar.style.height = '80px';
      }
    } else {
      // Desktop adjustments
      if (navbar) {
        navbar.style.borderRadius = '0 0 40px 40px';
        navbar.style.height = '90px';
      }
    }
  }
  
  handleResponsiveLayout();

  // ============================================================================
  // DEBOUNCE FUNCTION FOR PERFORMANCE
  // ============================================================================
  
  /**
   * Debounce function to limit execution rate
   */
  function debounce(func, wait) {
    let timeout;
    return function() {
      const context = this;
      const args = arguments;
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(context, args), wait);
    };
  }
  
  const debouncedResize = debounce(function() {
    if (navMenuContainer) {
      const navMenu = navMenuContainer.querySelector('.nav-menu');
      if (navMenu) {
        const menuItems = navMenu.querySelectorAll('.nav-item');
        let totalWidth = 0;
        
        menuItems.forEach(item => {
          const style = window.getComputedStyle(item);
          const itemWidth = item.offsetWidth + 
                          parseInt(style.marginLeft) + 
                          parseInt(style.marginRight);
          totalWidth += itemWidth;
        });
        
        navMenu.style.minWidth = (totalWidth + 40) + 'px';
      }
    }
    handleResponsiveLayout();
  }, 250);
  
  window.addEventListener('resize', debouncedResize);

  // ============================================================================
  // SMOOTH SCROLL TO TOP
  // ============================================================================
  
  const logoLink = document.querySelector('.karek-brand-link');
  if (logoLink) {
    logoLink.addEventListener('click', function(e) {
      if (this.getAttribute('href') === '/dashboard' || this.getAttribute('href') === '/') {
        e.preventDefault();
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
        
        const href = this.getAttribute('href');
        if (href && history.pushState) {
          history.pushState(null, null, href);
        }
      }
    });
  }

  // ============================================================================
  // PRELOAD DROPDOWNS
  // ============================================================================
  
  /**
   * Preload dropdown menus for smoother interaction
   */
  function preloadDropdowns() {
    const dropdowns = document.querySelectorAll('.dropdown-menu');
    dropdowns.forEach(dropdown => {
      dropdown.getBoundingClientRect();
    });
  }
  
  setTimeout(preloadDropdowns, 1000);

  // ============================================================================
  // HOVER INTENT FOR DESKTOP DROPDOWNS
  // ============================================================================
  
  const desktopDropdowns = document.querySelectorAll('.dropdown');
  
  desktopDropdowns.forEach(dropdown => {
    let timeout;
    
    dropdown.addEventListener('mouseenter', function() {
      clearTimeout(timeout);
      
      if (window.innerWidth >= 768) {
        const dropdownMenu = this.querySelector('.dropdown-menu');
        if (dropdownMenu) {
          dropdownMenu.style.display = 'block';
          this.classList.add('show');
        }
      }
    });
    
    dropdown.addEventListener('mouseleave', function() {
      if (window.innerWidth >= 768) {
        const dropdownMenu = this.querySelector('.dropdown-menu');
        const self = this;
        
        timeout = setTimeout(function() {
          if (dropdownMenu) {
            dropdownMenu.style.display = 'none';
            self.classList.remove('show');
          }
        }, 200);
      }
    });
  });

  // ============================================================================
  // CONSOLE LOG FOR DEBUGGING
  // ============================================================================
  
  console.log('✅ KAREK V5 Navigation loaded successfully');
  console.log('📍 Current path:', window.location.pathname);
  console.log('👤 User role:', '{{ Auth::user()->role }}');
  
}); // End DOMContentLoaded
</script>

</body>
</html>



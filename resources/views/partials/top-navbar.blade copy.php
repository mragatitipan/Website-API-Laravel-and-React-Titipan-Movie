<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Navigasi Karek - PT. Rohtek Amanah Global</title>
  
  <!-- External Libraries -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="{{ asset('/ui/css/utilities.css') }}" rel="stylesheet">
  
  <style>
/* ============================================================================
   KAREK V5 - PREMIUM NAVIGATION BAR - ENHANCED CSS
   PT. Rohtek Amanah Global
   Version: 4.0.1 - Fixed Modal & Hover Issues
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

/* Force animations to run */
.navbar,
.navbar *,
.navbar *::before,
.navbar *::after {
  animation-play-state: running !important;
  animation-fill-mode: both !important;
}

/* ============================================================================
   2. NAVBAR CORE STRUCTURE
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
  
  /* Stacking */
  z-index: 9999 !important;
  isolation: isolate;
  
  /* Overflow */
  overflow: visible !important;
  
  /* Animation */
  transition: all 0.5s cubic-bezier(0.165, 0.84, 0.44, 1);
  animation: navbarEntrance 0.5s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
  
  /* Performance */
  will-change: transform, opacity;
  transform: translateZ(0);
  backface-visibility: hidden;
}

/* Navbar Gradient Background - Staff Role */
.navbar-staff {
  background: linear-gradient(-45deg, #213823, #375534, #6B9071, #0F2A1D, #375534, #6B9071);
  background-size: 400% 400% !important;
  animation: gradient-animation 15s ease infinite !important;
}

/* Navbar Gradient Background - Other Roles */
.navbar-other {
  background: linear-gradient(-45deg, #213823, #375534, #6B9071, #0F2A1D);
  background-size: 400% 400% !important;
  animation: gradient-animation 15s ease infinite !important;
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
}

.navbar-brand img {
  height: 40px;
  margin-right: 10px;
  animation: logo-pulse 3s ease-in-out infinite !important;
  will-change: transform;
  transform: translateZ(0);
}

.karek-brand-link {
  text-decoration: none;
  display: flex;
  align-items: center;
  height: 100%;
  transition: transform 0.4s ease;
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
  
  /* Gradient underline */
  background-image: linear-gradient(90deg, 
    transparent 0%,
    rgba(255,255,255,0.8) 15%, 
    rgba(255,255,255,0.9) 50%,
    rgba(255,255,255,0.8) 85%,
    transparent 100%);
  background-size: 100% 2px;
  background-position: 0 100%;
  background-repeat: no-repeat;
  
  /* Animation */
  animation: karek-pulse 3s ease-in-out infinite !important;
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
  animation: tagline-fade 3s ease-in-out infinite !important;
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
  
  /* Scroll */
  overflow-x: auto;
  overflow-y: hidden !important;
  -webkit-overflow-scrolling: touch;
  
  /* Layout */
  display: flex;
  align-items: center;
  justify-content: center;
  
  /* Interaction */
  cursor: grab;
  user-select: none;
  white-space: nowrap;
  
  /* Scrollbar */
  scrollbar-width: thin;
  scrollbar-color: rgba(255, 255, 255, 0.3) transparent;
  
  /* Spacing */
  padding-bottom: 5px;
  
  /* Position */
  position: relative;
}

.nav-menu-container.grabbing {
  cursor: grabbing;
}

/* Scrollbar Styling */
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

/* Navigation Menu List */
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

/* Menu Alignment */
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
   5. NAVIGATION LINKS & ITEMS
============================================================================ */
.nav-item {
  position: relative;
  margin: 0 10px;
  white-space: nowrap;
  flex-shrink: 0;
  height: 100%;
  display: flex;
  align-items: center;
}

.nav-link {
  /* Typography */
  color: white;
  font-size: 0.95rem;
  font-weight: 500;
  letter-spacing: 0.2px;
  text-decoration: none;
  
  /* Layout */
  display: block;
  padding: 8px 16px;
  
  /* Visual */
  border-radius: 15px;
  
  /* Interaction */
  transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
  cursor: pointer;
  
  /* Position */
  position: relative;
  overflow: hidden;
}

/* Ripple Effect Base */
.nav-link:after {
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
}

.nav-link:focus:not(:active)::after {
  animation: ripple 1s ease-out;
}

/* Hover Effects - Staff */
.navbar-staff .nav-link:hover {
  background: rgba(255, 255, 255, 0.9);
  color: #1b263b;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
  text-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
}

/* Hover Effects - Other Roles */
.navbar-other .nav-link:hover {
  background: rgba(255, 255, 255, 0.9);
  color: #213823;
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
  text-shadow: 0 0 5px rgba(255, 255, 255, 0.5);
}

/* Active Menu Item - Staff */
.navbar-staff .active-menu-item {
  background: #4dabf7;
  color: white !important;
  padding: 7px 15px;
  border-radius: 14px;
  box-shadow: 0 2px 10px rgba(77, 171, 247, 0.5);
  font-weight: 600;
  animation: active-pulse 2s infinite ease-in-out !important;
  will-change: box-shadow;
}

/* Active Menu Item - Other Roles */
.navbar-other .active-menu-item {
  background: #6B9071;
  color: white !important;
  padding: 7px 15px;
  border-radius: 14px;
  box-shadow: 0 2px 10px rgba(107, 144, 113, 0.5);
  font-weight: 600;
  animation: active-pulse 2s infinite ease-in-out !important;
  will-change: box-shadow;
}

/* ============================================================================
   6. DROPDOWN MENUS
============================================================================ */
.nav-item.dropdown {
  position: relative !important;
}

.dropdown-menu {
  /* Position */
  position: absolute !important;
  top: 100% !important;
  left: 0 !important;
  z-index: 9999 !important;
  
  /* Layout */
  display: none;
  min-width: 220px;
  padding: 12px;
  margin-top: 15px !important;
  
  /* Visual */
  background-color: #fff;
  border: none;
  border-radius: 15px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
  
  /* Overflow */
  overflow: visible !important;
  
  /* Transform */
  transform: none !important;
  
  /* Animation */
  animation: dropdownFadeIn 0.3s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
  animation-fill-mode: both !important;
}

.dropdown.show .dropdown-menu {
  display: block !important;
}

.dropdown-item {
  /* Typography */
  color: #333;
  font-weight: 500;
  text-decoration: none;
  
  /* Layout */
  display: block;
  padding: 10px 15px;
  margin-bottom: 5px;
  
  /* Visual */
  border-radius: 10px;
  
  /* Interaction */
  transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
  cursor: pointer;
  
  /* Position */
  position: relative;
  overflow: hidden;
}

/* Dropdown Item Ripple */
.dropdown-item:after {
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
}

.dropdown-item:focus:not(:active)::after {
  animation: ripple-dark 1s ease-out;
}

/* Dropdown Item Hover - Staff */
.navbar-staff .dropdown-item:hover {
  background: #4dabf7;
  color: white;
  transform: translateX(5px);
  box-shadow: 0 2px 8px rgba(77, 171, 247, 0.3);
}

/* Dropdown Item Hover - Other Roles */
.navbar-other .dropdown-item:hover {
  background: #6B9071;
  color: white;
  transform: translateX(5px);
  box-shadow: 0 2px 8px rgba(107, 144, 113, 0.3);
}

/* Dropdown Item Active */
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

/* Dropdown Toggle Indicator */
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
}

.dropdown-toggle:focus {
  outline: none;
}

/* Bootstrap Dropdown Override */
.dropdown-menu[data-bs-popper] {
  position: absolute !important;
  z-index: 1060 !important;
  transform: none !important;
  margin-top: 15px !important;
}

.dropdown-menu-end,
.dropdown-menu-right {
  right: 0 !important;
  left: auto !important;
}

/* ============================================================================
   7. USER ACTIONS SECTION - FIXED HOVER
============================================================================ */
.user-actions {
  display: flex;
  align-items: center;
  min-width: 150px;
  flex-shrink: 0;
  height: 100%;
  justify-content: flex-end;
}

/* Fullscreen Button */
.fullscreen-btn {
  color: white;
  font-size: 1.5rem;
  margin-right: 20px;
  cursor: pointer;
  transition: transform 0.3s cubic-bezier(0.165, 0.84, 0.44, 1), 
              color 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
  animation: float 3s ease-in-out infinite !important;
  will-change: transform;
  transform: translateZ(0);
}

.navbar-staff .fullscreen-btn:hover {
  color: #00e3eb;
  transform: scale(1.2);
}

.navbar-other .fullscreen-btn:hover {
  color: #8FBC8F;
  transform: scale(1.2);
}

/* User Avatar - FIXED HOVER */
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
}

/* FIXED: Hover tidak putih lagi */
.user-avatar .nav-link:hover {
  background: rgba(107, 144, 113, 0.2) !important; /* Hijau transparan */
  transform: translateY(-2px);
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
  animation: avatar-pulse 4s ease-in-out infinite !important;
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
   8. MOBILE MENU & MODAL - CENTERED & SYMMETRICAL
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
  animation: float 3s ease-in-out infinite !important;
}

.mobile-menu-btn:focus {
  outline: none;
}

.mobile-menu-btn:hover {
  transform: scale(1.1);
  color: rgba(255, 255, 255, 0.9);
}

/* FIXED: Modal Centered & Symmetrical */
.modal-menu {
  padding: 0 !important;
  display: flex !important;
  align-items: center;
  justify-content: center;
}

.modal-menu.show {
  padding-top: 0 !important;
}

.modal-menu .modal-dialog {
  /* Centering */
  margin: 20px auto !important;
  
  /* Size - Desktop */
  max-width: 450px !important;
  width: calc(100% - 40px) !important;
  height: auto !important;
  max-height: calc(100vh - 140px) !important;
  
  /* Visual */
  border-radius: 25px;
  overflow: hidden;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
  
  /* Transform */
  transform: none !important;
  
  /* Animation */
  animation: modalSlideIn 0.4s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
}

.modal-menu .modal-content {
  height: 100%;
  border: none;
  border-radius: 25px;
  background: linear-gradient(135deg, #213823, #375534, #6B9071);
  animation: gradientShift 15s ease infinite !important;
  background-size: 400% 400%;
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

.modal-menu .modal-header {
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
  padding: 22px 28px;
  background: rgba(0, 0, 0, 0.15);
  flex-shrink: 0;
}

.modal-menu .modal-title {
  color: white;
  font-weight: 600;
  font-size: 1.5rem;
  animation: titleFadeIn 0.5s ease-out !important;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
}

.modal-menu .btn-close {
  color: white;
  background: transparent url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='white'%3e%3cpath d='M.293.293a1 1 0 011.414 0L8 6.586 14.293.293a1 1 0 111.414 1.414L9.414 8l6.293 6.293a1 1 0 01-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 01-1.414-1.414L6.586 8 .293 1.707a1 1 0 010-1.414z'/%3e%3c/svg%3e") center/1em auto no-repeat;
  opacity: 1;
  padding: 1rem;
  margin: -0.5rem -0.5rem -0.5rem auto;
  cursor: pointer;
  transition: transform 0.3s ease;
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

/* Mobile Navigation Menu */
.mobile-nav-menu {
  list-style: none;
  padding: 25px 22px;
  margin: 0;
}

.mobile-nav-item {
  margin-bottom: 14px;
  animation: menuItemFadeIn 0.5s ease-out !important;
  animation-fill-mode: both !important;
}

/* Staggered Animation Delays */
.mobile-nav-item:nth-child(1) { animation-delay: 0.1s !important; }
.mobile-nav-item:nth-child(2) { animation-delay: 0.15s !important; }
.mobile-nav-item:nth-child(3) { animation-delay: 0.2s !important; }
.mobile-nav-item:nth-child(4) { animation-delay: 0.25s !important; }
.mobile-nav-item:nth-child(5) { animation-delay: 0.3s !important; }
.mobile-nav-item:nth-child(6) { animation-delay: 0.35s !important; }
.mobile-nav-item:nth-child(7) { animation-delay: 0.4s !important; }
.mobile-nav-item:nth-child(8) { animation-delay: 0.45s !important; }

.mobile-nav-link {
  color: white;
  font-size: 1.05rem;
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
}

.mobile-nav-link:after {
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

/* Mobile Dropdown */
.mobile-dropdown-toggle {
  display: flex;
  justify-content: space-between;
  align-items: center;
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
  padding: 8px 0 8px 25px;
  margin: 12px 0 0 0;
  display: none;
  border-left: 2px dashed rgba(255, 255, 255, 0.3);
  position: relative;
  z-index: 1;
}

.mobile-dropdown-menu.show {
  display: block;
  animation: dropdownSlideDown 0.4s cubic-bezier(0.165, 0.84, 0.44, 1) !important;
}

.mobile-dropdown-item {
  margin-bottom: 10px;
  animation: dropdownItemFadeIn 0.4s ease-out !important;
  animation-fill-mode: both !important;
  position: relative;
  z-index: 2;
}

.mobile-dropdown-item:nth-child(1) { animation-delay: 0.1s !important; }
.mobile-dropdown-item:nth-child(2) { animation-delay: 0.15s !important; }
.mobile-dropdown-item:nth-child(3) { animation-delay: 0.2s !important; }
.mobile-dropdown-item:nth-child(4) { animation-delay: 0.25s !important; }
.mobile-dropdown-item:nth-child(5) { animation-delay: 0.3s !important; }

.mobile-dropdown-link {
  color: rgba(255, 255, 255, 0.9);
  font-size: 0.95rem;
  text-decoration: none;
  display: block;
  width: 100%;
  padding: 12px 18px;
  border-radius: 15px;
  transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
  background: rgba(255, 255, 255, 0.05);
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  position: relative;
  overflow: hidden;
}

.mobile-dropdown-link:after {
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

/* Mobile User Info */
.mobile-user-info {
  display: flex;
  align-items: center;
  padding: 22px;
  background: rgba(0, 0, 0, 0.2);
  margin: 22px;
  border-radius: 20px;
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
  animation: userInfoFadeIn 0.5s 0.3s ease-out !important;
  animation-fill-mode: both !important;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.mobile-user-avatar {
  width: 70px;
  height: 70px;
  border-radius: 50%;
  border: 3px solid white;
  margin-right: 18px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
  animation: avatarPulse 4s ease-in-out infinite !important;
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
}

.mobile-user-btn:after {
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
}

.mobile-user-btn:focus:not(:active)::after {
  animation: ripple 1s ease-out;
}

.mobile-user-btn:hover {
  background: rgba(107, 144, 113, 0.3) !important;
  color: white;
  text-decoration: none;
  transform: translateY(-3px);
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.mobile-user-btn i {
  margin-right: 8px;
}

/* Modal Scrollbar */
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
   RESPONSIVE - MOBILE MODAL ADJUSTMENTS
============================================================================ */

/* Tablet (992px and below) */
@media (max-width: 992px) {
  .modal-menu .modal-dialog {
    max-width: 420px !important;
    width: calc(100% - 40px) !important;
    margin: 20px auto !important;
  }
}

/* Mobile Devices (768px and below) */
@media (max-width: 768px) {
  .navbar {
    height: 90px;
  }
  
  /* Show mobile menu button */
  .mobile-menu-btn {
    display: block;
    font-size: 2rem;
    padding: 10px;
  }
  
  /* Hide desktop menu */
  .nav-menu-container,
  .fullscreen-btn,
  .user-avatar {
    display: none;
  }
  
  /* Modal Centered */
  .modal-menu .modal-dialog {
    max-width: 90% !important;
    width: 90% !important;
    margin: 20px auto !important;
    max-height: calc(100vh - 130px) !important;
  }
  
  .mobile-user-info {
    margin: 20px;
    padding: 20px;
  }
  
  .mobile-nav-menu {
    padding: 22px 20px;
  }
}

/* Small Mobile (576px and below) */
@media (max-width: 576px) {
  .navbar {
    height: 85px;
  }
  
  .modal-menu .modal-dialog {
    max-width: 92% !important;
    width: 92% !important;
    margin: 18px auto !important;
    max-height: calc(100vh - 120px) !important;
    border-radius: 22px;
  }
  
  .modal-menu .modal-content {
    border-radius: 22px;
  }
  
  .modal-menu .modal-header {
    padding: 20px 24px;
  }
  
  .modal-menu .modal-title {
    font-size: 1.4rem;
  }
  
  .mobile-nav-menu {
    padding: 20px 18px;
  }
  
  .mobile-user-info {
    margin: 18px;
    padding: 18px;
  }
  
  .mobile-user-avatar {
    width: 65px;
    height: 65px;
  }
  
  .mobile-user-name {
    font-size: 1.2rem;
  }
  
  .mobile-user-role {
    font-size: 0.88rem;
  }
  
  .mobile-nav-link {
    font-size: 1rem;
    padding: 13px 18px;
  }
  
  .mobile-dropdown-link {
    font-size: 0.92rem;
    padding: 11px 16px;
  }
}

/* Extra Small (480px and below) */
@media (max-width: 480px) {
  .navbar {
    height: 80px;
  }
  
  .modal-menu .modal-dialog {
    max-width: 94% !important;
    width: 94% !important;
    margin: 16px auto !important;
    max-height: calc(100vh - 110px) !important;
    border-radius: 20px;
  }
  
  .modal-menu .modal-content {
    border-radius: 20px;
  }
  
  .modal-menu .modal-header {
    padding: 18px 22px;
  }
  
  .modal-menu .modal-title {
    font-size: 1.3rem;
  }
  
  .mobile-nav-menu {
    padding: 18px 16px;
  }
  
  .mobile-user-info {
    margin: 16px;
    padding: 16px;
  }
  
  .mobile-user-avatar {
    width: 60px;
    height: 60px;
  }
  
  .mobile-user-name {
    font-size: 1.15rem;
  }
  
  .mobile-user-role {
    font-size: 0.85rem;
  }
  
  .mobile-nav-link {
    font-size: 0.95rem;
    padding: 12px 16px;
  }
  
  .mobile-dropdown-link {
    font-size: 0.88rem;
    padding: 10px 14px;
  }
}

/* Very Small (360px and below) */
@media (max-width: 360px) {
  .navbar {
    height: 75px;
    padding: 0 12px;
  }
  
  .modal-menu .modal-dialog {
    max-width: 95% !important;
    width: 95% !important;
    margin: 14px auto !important;
    max-height: calc(100vh - 100px) !important;
  }
  
  .mobile-nav-menu {
    padding: 16px 14px;
  }
  
  .mobile-user-info {
    margin: 14px;
    padding: 14px;
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
    font-size: 0.8rem;
  }
  
  .mobile-user-actions {
    width: 100%;
    margin-top: 12px;
  }
  
  .mobile-user-btn {
    font-size: 0.85rem;
    padding: 9px 14px;
  }
  
  .mobile-nav-link {
    font-size: 0.9rem;
    padding: 11px 14px;
  }
}

/* Landscape Mode */
@media (max-width: 768px) and (orientation: landscape) {
  .navbar {
    height: 70px;
  }
  
  .modal-menu .modal-dialog {
    max-width: 85% !important;
    width: 85% !important;
    margin: 12px auto !important;
    max-height: calc(100vh - 90px) !important;
  }
  
  .mobile-user-info {
    padding: 14px 16px;
    margin: 16px;
  }
  
  .mobile-user-avatar {
    width: 50px;
    height: 50px;
  }
  
  .mobile-user-name {
    font-size: 1.05rem;
  }
  
  .mobile-user-role {
    font-size: 0.8rem;
  }
  
  .mobile-nav-link {
    padding: 10px 15px;
    font-size: 0.92rem;
  }
  
  .mobile-dropdown-link {
    padding: 9px 13px;
    font-size: 0.85rem;
  }
}

/* Safari iOS Fix */
@supports (-webkit-touch-callout: none) {
  .modal-menu .modal-dialog {
    -webkit-transform: translateZ(0);
  }
  
  .modal-menu .modal-body {
    -webkit-overflow-scrolling: touch;
  }
}

/* ============================================================================
   9. ANIMATIONS & KEYFRAMES
============================================================================ */

/* Gradient Background Animation */
@keyframes gradient-animation {
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

/* Logo Pulse Animation */
@keyframes logo-pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.05); }
  100% { transform: scale(1); }
}

/* KAREK Text Pulse */
@keyframes karek-pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.02); }
  100% { transform: scale(1); }
}

/* Tagline Fade */
@keyframes tagline-fade {
  0% { opacity: 0.85; }
  50% { opacity: 1; }
  100% { opacity: 0.85; }
}

/* Float Animation */
@keyframes float {
  0% { transform: translateY(0px); }
  50% { transform: translateY(-5px); }
  100% { transform: translateY(0px); }
}

/* Avatar Pulse */
@keyframes avatar-pulse {
  0% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4); }
  70% { box-shadow: 0 0 0 10px rgba(255, 255, 255, 0); }
  100% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0); }
}

/* Active Menu Pulse */
@keyframes active-pulse {
  0% { box-shadow: 0 2px 6px rgba(107, 144, 113, 0.4); }
  50% { box-shadow: 0 2px 15px rgba(107, 144, 113, 0.7); }
  100% { box-shadow: 0 2px 6px rgba(107, 144, 113, 0.4); }
}

/* Dropdown Fade In */
@keyframes dropdownFadeIn {
  from { 
    opacity: 0; 
    transform: translateY(-10px); 
  }
  to { 
    opacity: 1; 
    transform: translateY(0); 
  }
}

/* Ripple Effect */
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

/* Ripple Dark */
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

/* Navbar Entrance */
@keyframes navbarEntrance {
  from { transform: translateY(-100%); }
  to { transform: translateY(0); }
}

/* Modal Slide In */
@keyframes modalSlideIn {
  from { opacity: 0; transform: translateY(-30px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Gradient Shift */
@keyframes gradientShift {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

/* Title Fade In */
@keyframes titleFadeIn {
  from { opacity: 0; transform: translateX(-10px); }
  to { opacity: 1; transform: translateX(0); }
}

/* Menu Item Fade In */
@keyframes menuItemFadeIn {
  from { opacity: 0; transform: translateX(-20px); }
  to { opacity: 1; transform: translateX(0); }
}

/* User Info Fade In */
@keyframes userInfoFadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Avatar Pulse (Mobile) */
@keyframes avatarPulse {
  0% { box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); }
  50% { box-shadow: 0 4px 15px rgba(255, 255, 255, 0.3); }
  100% { box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); }
}

/* Dropdown Slide Down */
@keyframes dropdownSlideDown {
  from { opacity: 0; transform: translateY(-15px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Dropdown Item Fade In */
@keyframes dropdownItemFadeIn {
  from { opacity: 0; transform: translateX(-10px); }
  to { opacity: 1; transform: translateX(0); }
}

/* Main Content Entrance */
@keyframes mainContentEntrance {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}

/* ============================================================================
   10. RESPONSIVE DESIGN
============================================================================ */

/* Main Content Spacing */
main {
  margin-top: 100px;
  padding: 20px;
  animation: mainContentEntrance 0.5s 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
  animation-fill-mode: both;
}

/* Tablet Devices (992px and below) */
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
}

/* Mobile Devices (768px and below) */
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
  
  /* Hide desktop menu */
  .nav-menu-container {
    display: none;
  }
  
  /* Show mobile menu button */
  .mobile-menu-btn {
    display: block;
    font-size: 2rem;
    padding: 10px;
  }
  
  .user-actions {
    min-width: unset;
  }
  
  /* Hide desktop user actions */
  .fullscreen-btn {
    display: none;
  }
  
  .user-avatar {
    display: none;
  }
  
  /* FIXED: Modal positioning untuk mobile */
  .modal-menu .modal-dialog {
    margin: 100px 15px 15px 15px !important;
    width: calc(100% - 30px);
    height: calc(100vh - 115px) !important;
  }
  
  .modal-menu .modal-body {
    max-height: calc(100vh - 250px);
  }
}

/* Small Mobile Devices (576px and below) */
@media (max-width: 576px) {
  .navbar {
    padding: 0 20px;
    height: 80px;
  }
  
  .karek-logo-text {
    font-size: 1.3rem;
  }
  
  .karek-tagline {
    font-size: 0.4rem;
  }
  
  .mobile-menu-btn {
    font-size: 1.8rem;
  }
  
  /* FIXED: Modal positioning untuk small mobile */
  .modal-menu .modal-dialog {
    margin: 90px 10px 10px 10px !important;
    width: calc(100% - 20px);
    height: calc(100vh - 100px) !important;
    border-radius: 20px;
  }
  
  .modal-menu .modal-content {
    border-radius: 20px;
  }
  
  .modal-menu .modal-body {
    max-height: calc(100vh - 220px);
  }
  
  .mobile-nav-menu {
    padding: 20px;
  }
  
  .mobile-user-info {
    margin: 20px 20px 15px 20px;
    padding: 20px;
  }
  
  .mobile-user-avatar {
    width: 60px;
    height: 60px;
  }
  
  .mobile-user-name {
    font-size: 1.2rem;
  }
  
  .mobile-user-role {
    font-size: 0.85rem;
  }
  
  .mobile-nav-link {
    font-size: 1rem;
    padding: 12px 16px;
  }
  
  .mobile-dropdown-link {
    font-size: 0.95rem;
    padding: 10px 15px;
  }
}

/* Extra Small Devices (480px and below) */
@media (max-width: 480px) {
  .navbar {
    height: 75px;
    padding: 0 15px;
  }
  
  .karek-logo-text {
    font-size: 1.2rem;
  }
  
  .karek-tagline {
    font-size: 0.35rem;
  }
  
  /* FIXED: Modal positioning untuk extra small */
  .modal-menu .modal-dialog {
    margin: 85px 8px 8px 8px !important;
    width: calc(100% - 16px);
    height: calc(100vh - 93px) !important;
  }
  
  .modal-menu .modal-header {
    padding: 20px;
  }
  
  .modal-menu .modal-title {
    font-size: 1.4rem;
  }
  
  .mobile-nav-menu {
    padding: 15px;
  }
  
  .mobile-user-info {
    margin: 15px;
    padding: 15px;
    flex-direction: column;
    text-align: center;
  }
  
  .mobile-user-avatar {
    margin-right: 0;
    margin-bottom: 15px;
  }
  
  .mobile-user-actions {
    justify-content: center;
    width: 100%;
  }
  
  .mobile-user-btn {
    flex: 1;
    text-align: center;
  }
}

/* ============================================================================
   11. UTILITY CLASSES
============================================================================ */

/* Ripple Effect Utility */
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

@keyframes ripple-animation {
  to {
    transform: scale(20);
    opacity: 0;
  }
}

/* Dropdown Positioning Fix */
.dropdown-positioning-fix {
  position: fixed !important;
  z-index: 1060 !important;
}

/* Modal Backdrop Override */
.modal-backdrop {
  z-index: 1040 !important;
}

.modal {
  z-index: 1050 !important;
}

.btn-close:focus {
  box-shadow: none;
  opacity: 1;
}

.modal-header .btn-close {
  position: relative;
  z-index: 1060;
}

/* ============================================================================
   12. PERFORMANCE OPTIMIZATIONS
============================================================================ */

/* GPU Acceleration */
.navbar,
.navbar-brand,
.karek-logo-text,
.fullscreen-btn,
.user-avatar img,
.nav-link,
.dropdown-menu,
.modal-menu .modal-dialog {
  transform: translateZ(0);
  backface-visibility: hidden;
  perspective: 1000px;
}

/* Prevent Layout Shifts */
.navbar * {
  animation-duration: inherit !important;
}

/* Smooth Scrolling */
html {
  scroll-behavior: smooth;
}

/* Reduce Motion for Accessibility */
@media (prefers-reduced-motion: reduce) {
  *,
  *::before,
  *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
  }
}

/* ============================================================================
   13. ADDITIONAL ENHANCEMENTS
============================================================================ */

/* Hover Glow Effect */
.mobile-user-btn:hover {
  box-shadow: 0 0 15px rgba(107, 144, 113, 0.3);
}

/* Smooth Transitions */
.nav-link,
.dropdown-item,
.mobile-nav-link {
  transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
}

/* Focus Visible (Accessibility) */
.nav-link:focus-visible,
.dropdown-item:focus-visible,
.mobile-nav-link:focus-visible {
  outline: 2px solid rgba(255, 255, 255, 0.5);
  outline-offset: 2px;
}

/* Print Styles */
@media print {
  .navbar {
    position: static;
    box-shadow: none;
  }
  
  .mobile-menu-btn,
  .fullscreen-btn {
    display: none;
  }
}

/* Dark Mode Support (Optional) */
@media (prefers-color-scheme: dark) {
  /* Add dark mode styles if needed */
}

/* High Contrast Mode (Accessibility) */
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
   14. LANDSCAPE ORIENTATION FIX (Mobile)
============================================================================ */
@media (max-width: 768px) and (orientation: landscape) {
  .navbar {
    height: 70px;
  }
  
  .karek-logo-text {
    font-size: 1.2rem;
  }
  
  .karek-tagline {
    font-size: 0.4rem;
  }
  
  .mobile-menu-btn {
    font-size: 1.5rem;
  }
  
  /* FIXED: Modal dalam landscape mode */
  .modal-menu .modal-dialog {
    margin: 80px 10px 10px 10px !important;
    height: calc(100vh - 90px) !important;
  }
  
  .modal-menu .modal-body {
    max-height: calc(100vh - 180px);
  }
  
  .mobile-user-info {
    padding: 15px;
    margin: 15px;
  }
  
  .mobile-user-avatar {
    width: 50px;
    height: 50px;
  }
  
  .mobile-user-name {
    font-size: 1.1rem;
  }
  
  .mobile-nav-link {
    padding: 10px 15px;
    font-size: 0.95rem;
  }
}

/* ============================================================================
   15. SAFARI-SPECIFIC FIXES
============================================================================ */
@supports (-webkit-touch-callout: none) {
  /* iOS Safari specific fixes */
  .navbar {
    -webkit-transform: translateZ(0);
  }
  
  .modal-menu .modal-dialog {
    -webkit-overflow-scrolling: touch;
  }
  
  .modal-menu .modal-body {
    -webkit-overflow-scrolling: touch;
  }
}

/* ============================================================================
   16. FIREFOX-SPECIFIC FIXES
============================================================================ */
@-moz-document url-prefix() {
  .nav-menu-container {
    scrollbar-width: thin;
    scrollbar-color: rgba(255, 255, 255, 0.4) transparent;
  }
}

/* ============================================================================
   17. EDGE-SPECIFIC FIXES
============================================================================ */
@supports (-ms-ime-align: auto) {
  .navbar {
    -ms-overflow-style: -ms-autohiding-scrollbar;
  }
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
   19. LOADING STATE (Optional)
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
   20. CUSTOM SCROLLBAR FOR WEBKIT BROWSERS
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
   END OF KAREK V ENHANCED CSS
============================================================================ */

  </style>
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<!-- Navbar -->
<nav class="navbar {{ Auth::user()->role === 'Staff' ? 'navbar-staff' : 'navbar-other' }}">
  <div class="navbar-brand">
    <a href="{{ url('dashboard') }}" class="karek-brand-link">
      <div class="karek-logo-container">
        <span class="karek-logo-text">KAREK V5</span>
        <small class="karek-tagline">Key Analysis, Results, Execution, Knowledge</small>
      </div>
    </a>
  </div>

  <div class="nav-menu-container" id="navMenuContainer">
    <ul class="nav-menu">
      <!-- JANGAN DI HAPUS INI PENTING (Visible Only XS) -->
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
 
    <!-- Menu untuk Admin -->
    @if(Auth::user()->role === 'Admin')
    <li class="nav-item">
      <a href="{{ url('dashboard') }}" target="_blank" class="nav-link {{ request()->is('dashboard*') ? 'active-menu-item' : '' }}"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('rekapitulasi_pekerjaan') }}" target="_blank" class="nav-link {{ request()->is('rekapitulasi_pekerjaan*') ? 'active-menu-item' : '' }}"><i class="fas fa-clipboard-list mr-2"></i> Laporan Harian</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('penagihan') }}" target="_blank" class="nav-link {{ request()->is('penagihan*') ? 'active-menu-item' : '' }}"><i class="fas fa-file-invoice-dollar mr-2"></i> Penagihan</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('purchasing') }}" target="_blank" class="nav-link {{ request()->is('purchasing*') ? 'active-menu-item' : '' }}"><i class="fas fa-shopping-cart mr-2"></i> Purchasing</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('dataproduk') }}" target="_blank" class="nav-link {{ request()->is('dataproduk*') ? 'active-menu-item' : '' }}"><i class="fas fa-boxes mr-2"></i> Database Material</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('barangkeluar') }}" target="_blank" class="nav-link {{ request()->is('barangkeluar*') ? 'active-menu-item' : '' }}"><i class="fas fa-truck-loading mr-2"></i> Pengeluaran Barang</a>
    </li>
    <li class="nav-item">
      <a href="{{ route('it.monitoring') }}" target="_blank" class="nav-link {{ request()->routeIs('it.monitoring*') ? 'active-menu-item' : '' }}"><i class="fas fa-desktop mr-2"></i> IT Monitoring</a>
    </li>
    <li class="nav-item">
      <a href="{{ route('it.accounts.index') }}" target="_blank" class="nav-link {{ request()->routeIs('it.accounts.*') ? 'active-menu-item' : '' }}"><i class="fas fa-users-cog mr-2"></i> IT Accounts</a>
    </li>
    <li class="nav-item">
      <a href="{{ route('it.inventory.index') }}" target="_blank" class="nav-link {{ request()->routeIs('it.inventory.*') ? 'active-menu-item' : '' }}"><i class="fas fa-server mr-2"></i> IT Inventory</a>
    </li>
    <li class="nav-item">
      <a href="{{ route('it.pc-details.index') }}" target="_blank" class="nav-link {{ request()->routeIs('it.pc-details.*') ? 'active-menu-item' : '' }}"><i class="fas fa-laptop mr-2"></i> PC Details</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('home') }}" target="_blank" class="nav-link {{ request()->is('home*') ? 'active-menu-item' : '' }}"><i class="fas fa-user-shield mr-2"></i> Akun Karek</a>
    </li>
    @endif

    <!-- Menu untuk Staff Hanya melihat saja untuk client -->
    @if(Auth::user()->role === 'Staff')
    <li class="nav-item">
      <a href="{{ url('dashboard') }}" class="nav-link {{ request()->is('dashboard*') ? 'active-menu-item' : '' }}"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a>
    </li>
    @endif

    <!-- Menu untuk Staff2 Proses lapangan -->
    @if(Auth::user()->role === 'Staff2')
    <li class="nav-item">
      <a href="{{ url('dashboard') }}" class="nav-link {{ request()->is('dashboard*') ? 'active-menu-item' : '' }}"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('rekapitulasi_pekerjaan') }}" class="nav-link {{ request()->is('rekapitulasi_pekerjaan*') ? 'active-menu-item' : '' }}"><i class="fas fa-clipboard-list mr-2"></i> Laporan Harian</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('penagihan') }}" class="nav-link {{ request()->is('penagihan*') ? 'active-menu-item' : '' }}"><i class="fas fa-file-invoice-dollar mr-2"></i> Penagihan</a>
    </li>
    @endif

    <!-- Menu untuk Staff3 Gudang-->
    @if(Auth::user()->role === 'Staff3')
    <li class="nav-item">
      <a href="{{ url('dashboard') }}" class="nav-link {{ request()->is('dashboard*') ? 'active-menu-item' : '' }}"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('dataproduk') }}" class="nav-link {{ request()->is('dataproduk*') ? 'active-menu-item' : '' }}"><i class="fas fa-boxes mr-2"></i> Database Material</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('barangkeluar') }}" class="nav-link {{ request()->is('barangkeluar*') ? 'active-menu-item' : '' }}"><i class="fas fa-truck-loading mr-2"></i> Pengeluaran Barang</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('purchase_request') }}" class="nav-link {{ request()->is('purchase_request*') ? 'active-menu-item' : '' }}"><i class="fas fa-file-alt mr-2"></i> Purchase Request</a>
    </li>
    @endif

    <!-- Menu untuk Staff4 HRD -->
    @if(Auth::user()->role === 'Staff4')
    <li class="nav-item">
      <a href="{{ url('dashboard') }}" class="nav-link {{ request()->is('dashboard*') ? 'active-menu-item' : '' }}"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('rekapitulasi_pekerjaan') }}" class="nav-link {{ request()->is('rekapitulasi_pekerjaan*') ? 'active-menu-item' : '' }}"><i class="fas fa-clipboard-list mr-2"></i> Laporan Harian</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('data_karyawan') }}" class="nav-link {{ request()->is('data_karyawan*') ? 'active-menu-item' : '' }}"><i class="fas fa-id-card mr-2"></i> Data Karyawan</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('kontrak') }}" class="nav-link {{ request()->is('kontrak*') ? 'active-menu-item' : '' }}"><i class="fas fa-file-contract mr-2"></i> Kontrak Kerja</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('gaji') }}" class="nav-link {{ request()->is('gaji*') ? 'active-menu-item' : '' }}"><i class="fas fa-money-bill-wave mr-2"></i> Gaji</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('absensi') }}" class="nav-link {{ request()->is('absensi*') ? 'active-menu-item' : '' }}"><i class="fas fa-calendar-check mr-2"></i> Absensi</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('home') }}" class="nav-link {{ request()->is('home*') ? 'active-menu-item' : '' }}"><i class="fas fa-users mr-2"></i> Data User Karek</a>
    </li>
    @endif

    <!-- Menu untuk Staff5 Keuangan -->
    @if(Auth::user()->role === 'Staff5')
    <li class="nav-item">
      <a href="{{ url('dashboard') }}" class="nav-link {{ request()->is('dashboard*') ? 'active-menu-item' : '' }}"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('cashflow') }}" class="nav-link {{ request()->is('cashflow*') ? 'active-menu-item' : '' }}"><i class="fas fa-chart-line mr-2"></i> Cashflow</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('hutang_piutang') }}" class="nav-link {{ request()->is('hutang_piutang*') ? 'active-menu-item' : '' }}"><i class="fas fa-balance-scale mr-2"></i> Hutang & Piutang</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('pengeluaran') }}" class="nav-link {{ request()->is('pengeluaran*') ? 'active-menu-item' : '' }}"><i class="fas fa-hand-holding-usd mr-2"></i> Pengeluaran</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('dataproduk') }}" class="nav-link {{ request()->is('dataproduk*') ? 'active-menu-item' : '' }}"><i class="fas fa-boxes mr-2"></i> Database Material</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('barangkeluar') }}" class="nav-link {{ request()->is('barangkeluar*') ? 'active-menu-item' : '' }}"><i class="fas fa-truck-loading mr-2"></i> Pengeluaran Barang</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('purchase_request') }}" class="nav-link {{ request()->is('purchase_request*') ? 'active-menu-item' : '' }}"><i class="fas fa-file-alt mr-2"></i> Purchase Request</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('rekap_lapangan') }}" class="nav-link {{ request()->is('rekap_lapangan*') ? 'active-menu-item' : '' }}"><i class="fas fa-clipboard mr-2"></i> Rekap Data Lapangan</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('rekap_keuangan') }}" class="nav-link {{ request()->is('rekap_keuangan*') ? 'active-menu-item' : '' }}"><i class="fas fa-file-invoice mr-2"></i> Rekap Data Keuangan</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('rekap_purchasing') }}" class="nav-link {{ request()->is('rekap_purchasing*') ? 'active-menu-item' : '' }}"><i class="fas fa-shopping-basket mr-2"></i> Rekap Data Purchasing</a>
    </li>
    @endif
      
    <!-- Menu untuk Staff6 IT  -->
    @if(Auth::user()->role === 'Staff6')
    <li class="nav-item">
      <a href="{{ url('dashboard') }}" class="nav-link {{ request()->is('dashboard*') ? 'active-menu-item' : '' }}"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a>
    </li>
        <li class="nav-item">
      <a href="{{ url('rekapitulasi_pekerjaan') }}" class="nav-link {{ request()->is('rekapitulasi_pekerjaan*') ? 'active-menu-item' : '' }}"><i class="fas fa-clipboard-list mr-2"></i> Laporan Harian</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('penagihan') }}" class="nav-link {{ request()->is('penagihan*') ? 'active-menu-item' : '' }}"><i class="fas fa-file-invoice-dollar mr-2"></i> Penagihan</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('purchasing') }}" class="nav-link {{ request()->is('purchasing*') ? 'active-menu-item' : '' }}"><i class="fas fa-shopping-cart mr-2"></i> Purchasing</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('dataproduk') }}" class="nav-link {{ request()->is('dataproduk*') ? 'active-menu-item' : '' }}"><i class="fas fa-boxes mr-2"></i> Database Material</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('barangkeluar') }}" class="nav-link {{ request()->is('barangkeluar*') ? 'active-menu-item' : '' }}"><i class="fas fa-truck-loading mr-2"></i> Pengeluaran Barang</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('purchase_request') }}" class="nav-link {{ request()->is('purchase_request*') ? 'active-menu-item' : '' }}"><i class="fas fa-file-alt mr-2"></i> Purchase Request</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('cashflow') }}" class="nav-link {{ request()->is('cashflow*') ? 'active-menu-item' : '' }}"><i class="fas fa-chart-line mr-2"></i> Cashflow</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('hutang_piutang') }}" class="nav-link {{ request()->is('hutang_piutang*') ? 'active-menu-item' : '' }}"><i class="fas fa-balance-scale mr-2"></i> Hutang & Piutang</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('pengeluaran') }}" class="nav-link {{ request()->is('pengeluaran*') ? 'active-menu-item' : '' }}"><i class="fas fa-hand-holding-usd mr-2"></i> Pengeluaran</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('data_karyawan') }}" class="nav-link {{ request()->is('data_karyawan*') ? 'active-menu-item' : '' }}"><i class="fas fa-id-card mr-2"></i> Data Karyawan</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('kontrak') }}" class="nav-link {{ request()->is('kontrak*') ? 'active-menu-item' : '' }}"><i class="fas fa-file-contract mr-2"></i> Kontrak Kerja</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('gaji') }}" class="nav-link {{ request()->is('gaji*') ? 'active-menu-item' : '' }}"><i class="fas fa-money-bill-wave mr-2"></i> Gaji</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('absensi') }}" class="nav-link {{ request()->is('absensi*') ? 'active-menu-item' : '' }}"><i class="fas fa-calendar-check mr-2"></i> Absensi</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('rekap_lapangan') }}" class="nav-link {{ request()->is('rekap_lapangan*') ? 'active-menu-item' : '' }}"><i class="fas fa-clipboard mr-2"></i> Rekap Data Lapangan</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('rekap_keuangan') }}" class="nav-link {{ request()->is('rekap_keuangan*') ? 'active-menu-item' : '' }}"><i class="fas fa-file-invoice mr-2"></i> Rekap Data Keuangan</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('rekap_purchasing') }}" class="nav-link {{ request()->is('rekap_purchasing*') ? 'active-menu-item' : '' }}"><i class="fas fa-shopping-basket mr-2"></i> Rekap Data Purchasing</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('home') }}" class="nav-link {{ request()->is('home*') ? 'active-menu-item' : '' }}"><i class="fas fa-cogs mr-2"></i> IT Management</a>
    </li>
    @endif

    <!-- Menu untuk Staff7 Purchasing -->
    @if(Auth::user()->role === 'Staff7')
    <li class="nav-item">
      <a href="{{ url('dashboard') }}" class="nav-link {{ request()->is('dashboard*') ? 'active-menu-item' : '' }}"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('dataproduk') }}" class="nav-link {{ request()->is('dataproduk*') ? 'active-menu-item' : '' }}"><i class="fas fa-boxes mr-2"></i> Database Material</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('barangkeluar') }}" class="nav-link {{ request()->is('barangkeluar*') ? 'active-menu-item' : '' }}"><i class="fas fa-truck-loading mr-2"></i> Pengeluaran Barang</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('purchase_request') }}" class="nav-link {{ request()->is('purchase_request*') ? 'active-menu-item' : '' }}"><i class="fas fa-file-alt mr-2"></i> Purchase Request</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('data_karyawan') }}" class="nav-link {{ request()->is('data_karyawan*') ? 'active-menu-item' : '' }}"><i class="fas fa-id-card mr-2"></i> Data Karyawan</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('kontrak') }}" class="nav-link {{ request()->is('kontrak*') ? 'active-menu-item' : '' }}"><i class="fas fa-file-contract mr-2"></i> Kontrak Kerja</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('gaji') }}" class="nav-link {{ request()->is('gaji*') ? 'active-menu-item' : '' }}"><i class="fas fa-money-bill-wave mr-2"></i> Gaji</a>
    </li>
    <li class="nav-item">
      <a href="{{ url('absensi') }}" class="nav-link {{ request()->is('absensi*') ? 'active-menu-item' : '' }}"><i class="fas fa-calendar-check mr-2"></i> Absensi</a>
    </li>
    @endif
    </ul>
  </div>

  <div class="user-actions">
    <i class="fas fa-expand fullscreen-btn" id="fullscreenBtn" title="Toggle Fullscreen"></i>
    <div class="user-avatar dropdown">
      <a href="#" class="nav-link dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <span class="text-white">{{ Auth::user()->name }}</span>
        <img src="{{ asset('agency/assets/img/rohtek1.png') }}" alt="User Avatar">
      </a>
      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
        <li><a href="{{ url('/home/'. auth()->user()->id .'/profile') }}" class="dropdown-item {{ request()->is('home/*/profile') ? 'active' : '' }}">
          <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i> Profile
        </a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#logoutModal">
          <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout
        </a></li>
      </ul>
    </div>
    
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" data-bs-toggle="modal" data-bs-target="#mobileMenuModal">
      <i class="fas fa-bars"></i>
    </button>
  </div>
</nav>

<!-- Mobile Menu Modal -->
<div class="modal fade modal-menu" id="mobileMenuModal" tabindex="-1" aria-labelledby="mobileMenuModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="mobileMenuModalLabel">KAREK V5 Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- User Info Section -->
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
        
        <!-- Mobile Navigation Menu -->
        <ul class="mobile-nav-menu">
          <!-- Menu untuk Admin -->
          @if(Auth::user()->role === 'Admin')
          <li class="mobile-nav-item">
            <a href="{{ url('dashboard') }}" class="mobile-nav-link {{ request()->is('dashboard*') ? 'active' : '' }}"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a>
          </li>
          
          <li class="mobile-nav-item">
            <a href="#" class="mobile-nav-link mobile-dropdown-toggle {{ request()->is('rekapitulasi_pekerjaan*') || request()->is('penagihan*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#mobileProcessDropdown" aria-expanded="{{ request()->is('rekapitulasi_pekerjaan*') || request()->is('penagihan*') ? 'true' : 'false' }}">
              <i class="fas fa-tasks mr-2"></i> Process
            </a>
            <ul class="mobile-dropdown-menu collapse {{ request()->is('rekapitulasi_pekerjaan*') || request()->is('penagihan*') ? 'show' : '' }}" id="mobileProcessDropdown">
              <li class="mobile-dropdown-item"><a href="{{ url('rekapitulasi_pekerjaan') }}" class="mobile-dropdown-link {{ request()->is('rekapitulasi_pekerjaan*') ? 'active' : '' }}"><i class="fas fa-clipboard-list mr-2"></i> Laporan Harian</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('penagihan') }}" class="mobile-dropdown-link {{ request()->is('penagihan*') ? 'active' : '' }}"><i class="fas fa-file-invoice-dollar mr-2"></i> Penagihan</a></li>
            </ul>
          </li>
          
          <li class="mobile-nav-item">
            <a href="{{ url('purchasing') }}" class="mobile-nav-link {{ request()->is('purchasing*') ? 'active' : '' }}"><i class="fas fa-shopping-cart mr-2"></i> Purchasing</a>
          </li>
          
          <li class="mobile-nav-item">
            <a href="#" class="mobile-nav-link mobile-dropdown-toggle {{ request()->is('dataproduk*') || request()->is('barangkeluar*') || request()->is('purchase_request*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#mobileWarehouseDropdown" aria-expanded="{{ request()->is('dataproduk*') || request()->is('barangkeluar*') || request()->is('purchase_request*') ? 'true' : 'false' }}">
              <i class="fas fa-warehouse mr-2"></i> Warehouse
            </a>
            <ul class="mobile-dropdown-menu collapse {{ request()->is('dataproduk*') || request()->is('barangkeluar*') || request()->is('purchase_request*') ? 'show' : '' }}" id="mobileWarehouseDropdown">
              <li class="mobile-dropdown-item"><a href="{{ url('dataproduk') }}" class="mobile-dropdown-link {{ request()->is('dataproduk*') ? 'active' : '' }}"><i class="fas fa-boxes mr-2"></i> Database Material</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('barangkeluar') }}" class="mobile-dropdown-link {{ request()->is('barangkeluar*') ? 'active' : '' }}"><i class="fas fa-truck-loading mr-2"></i> Pengeluaran Barang</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('purchase_request') }}" class="mobile-dropdown-link {{ request()->is('purchase_request*') ? 'active' : '' }}"><i class="fas fa-file-alt mr-2"></i> Purchase Request</a></li>
            </ul>
          </li>
          
          <li class="mobile-nav-item">
            <a href="#" class="mobile-nav-link mobile-dropdown-toggle {{ request()->is('cashflow*') || request()->is('hutang_piutang*') || request()->is('pengeluaran*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#mobileFinanceDropdown" aria-expanded="{{ request()->is('cashflow*') || request()->is('hutang_piutang*') || request()->is('pengeluaran*') ? 'true' : 'false' }}">
              <i class="fas fa-money-bill-alt mr-2"></i> Finance
            </a>
            <ul class="mobile-dropdown-menu collapse {{ request()->is('cashflow*') || request()->is('hutang_piutang*') || request()->is('pengeluaran*') ? 'show' : '' }}" id="mobileFinanceDropdown">
              <li class="mobile-dropdown-item"><a href="{{ url('cashflow') }}" class="mobile-dropdown-link {{ request()->is('cashflow*') ? 'active' : '' }}"><i class="fas fa-chart-line mr-2"></i> Cashflow</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('hutang_piutang') }}" class="mobile-dropdown-link {{ request()->is('hutang_piutang*') ? 'active' : '' }}"><i class="fas fa-balance-scale mr-2"></i> Hutang & Piutang</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('pengeluaran') }}" class="mobile-dropdown-link {{ request()->is('pengeluaran*') ? 'active' : '' }}"><i class="fas fa-hand-holding-usd mr-2"></i> Pengeluaran</a></li>
            </ul>
          </li>
          
          <li class="mobile-nav-item">
            <a href="#" class="mobile-nav-link mobile-dropdown-toggle {{ request()->is('data_karyawan*') || request()->is('kontrak*') || request()->is('gaji*') || request()->is('absensi*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#mobileHRDDropdown" aria-expanded="{{ request()->is('data_karyawan*') || request()->is('kontrak*') || request()->is('gaji*') || request()->is('absensi*') ? 'true' : 'false' }}">
              <i class="fas fa-users mr-2"></i> HRD & GA
            </a>
            <ul class="mobile-dropdown-menu collapse {{ request()->is('data_karyawan*') || request()->is('kontrak*') || request()->is('gaji*') || request()->is('absensi*') ? 'show' : '' }}" id="mobileHRDDropdown">
              <li class="mobile-dropdown-item"><a href="{{ url('data_karyawan') }}" class="mobile-dropdown-link {{ request()->is('data_karyawan*') ? 'active' : '' }}"><i class="fas fa-id-card mr-2"></i> Data Karyawan</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('kontrak') }}" class="mobile-dropdown-link {{ request()->is('kontrak*') ? 'active' : '' }}"><i class="fas fa-file-contract mr-2"></i> Kontrak Kerja</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('gaji') }}" class="mobile-dropdown-link {{ request()->is('gaji*') ? 'active' : '' }}"><i class="fas fa-money-bill-wave mr-2"></i> Gaji</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('absensi') }}" class="mobile-dropdown-link {{ request()->is('absensi*') ? 'active' : '' }}"><i class="fas fa-calendar-check mr-2"></i> Absensi</a></li>
            </ul>
          </li>
          
          <li class="mobile-nav-item">
            <a href="#" class="mobile-nav-link mobile-dropdown-toggle {{ request()->is('rekap_lapangan*') || request()->is('rekap_keuangan*') || request()->is('rekap_purchasing*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#mobileDocumentDropdown" aria-expanded="{{ request()->is('rekap_lapangan*') || request()->is('rekap_keuangan*') || request()->is('rekap_purchasing*') ? 'true' : 'false' }}">
              <i class="fas fa-folder-open mr-2"></i> Dokumen
            </a>
            <ul class="mobile-dropdown-menu collapse {{ request()->is('rekap_lapangan*') || request()->is('rekap_keuangan*') || request()->is('rekap_purchasing*') ? 'show' : '' }}" id="mobileDocumentDropdown">
              <li class="mobile-dropdown-item"><a href="{{ url('rekap_lapangan') }}" class="mobile-dropdown-link {{ request()->is('rekap_lapangan*') ? 'active' : '' }}"><i class="fas fa-clipboard mr-2"></i> Rekap Data Lapangan</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('rekap_keuangan') }}" class="mobile-dropdown-link {{ request()->is('rekap_keuangan*') ? 'active' : '' }}"><i class="fas fa-file-invoice mr-2"></i> Rekap Data Keuangan</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('rekap_purchasing') }}" class="mobile-dropdown-link {{ request()->is('rekap_purchasing*') ? 'active' : '' }}"><i class="fas fa-shopping-basket mr-2"></i> Rekap Data Purchasing</a></li>
            </ul>
          </li>
          
          <li class="mobile-nav-item">
            <a href="#" class="mobile-nav-link mobile-dropdown-toggle {{ request()->routeIs('it.*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#mobileITDropdown" aria-expanded="{{ request()->routeIs('it.*') ? 'true' : 'false' }}">
              <i class="fas fa-laptop-code mr-2"></i> IT Management
            </a>
            <ul class="mobile-dropdown-menu collapse {{ request()->routeIs('it.*') ? 'show' : '' }}" id="mobileITDropdown">
              <li class="mobile-dropdown-item"><a href="{{ route('it.monitoring') }}" class="mobile-dropdown-link {{ request()->routeIs('it.monitoring*') ? 'active' : '' }}"><i class="fas fa-desktop mr-2"></i> Monitoring</a></li>
              <li class="mobile-dropdown-item"><a href="{{ route('it.accounts.index') }}" class="mobile-dropdown-link {{ request()->routeIs('it.accounts.*') ? 'active' : '' }}"><i class="fas fa-users-cog mr-2"></i> IT Accounts</a></li>
              <li class="mobile-dropdown-item"><a href="{{ route('it.inventory.index') }}" class="mobile-dropdown-link {{ request()->routeIs('it.inventory.*') ? 'active' : '' }}"><i class="fas fa-server mr-2"></i> IT Inventory</a></li>
              <li class="mobile-dropdown-item"><a href="{{ route('it.pc-details.index') }}" class="mobile-dropdown-link {{ request()->routeIs('it.pc-details.*') ? 'active' : '' }}"><i class="fas fa-laptop mr-2"></i> PC Details</a></li>
            </ul>
          </li>
          
          <li class="mobile-nav-item">
            <a href="{{ url('home') }}" class="mobile-nav-link {{ request()->is('home*') && !request()->is('home/*/profile') ? 'active' : '' }}"><i class="fas fa-user-shield mr-2"></i> Akun Karek</a>
          </li>
          @endif
          
          <!-- Menu untuk Staff -->
          @if(Auth::user()->role === 'Staff')
          <li class="mobile-nav-item">
            <a href="{{ url('dashboard') }}" class="mobile-nav-link {{ request()->is('dashboard*') ? 'active' : '' }}"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a>
          </li>
          @endif
          
          <!-- Menu untuk Staff2 -->
          @if(Auth::user()->role === 'Staff2')
          <li class="mobile-nav-item">
            <a href="{{ url('dashboard') }}" class="mobile-nav-link {{ request()->is('dashboard*') ? 'active' : '' }}"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a>
          </li>
          <li class="mobile-nav-item">
            <a href="{{ url('rekapitulasi_pekerjaan') }}" class="mobile-nav-link {{ request()->is('rekapitulasi_pekerjaan*') ? 'active' : '' }}"><i class="fas fa-clipboard-list mr-2"></i> Laporan Harian</a>
          </li>
          <li class="mobile-nav-item">
            <a href="{{ url('penagihan') }}" class="mobile-nav-link {{ request()->is('penagihan*') ? 'active' : '' }}"><i class="fas fa-file-invoice-dollar mr-2"></i> Penagihan</a>
          </li>
          @endif
          
          <!-- Menu untuk Staff3 -->
          @if(Auth::user()->role === 'Staff3')
          <li class="mobile-nav-item">
            <a href="{{ url('dashboard') }}" class="mobile-nav-link {{ request()->is('dashboard*') ? 'active' : '' }}"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a>
          </li>
          <li class="mobile-nav-item">
            <a href="{{ url('dataproduk') }}" class="mobile-nav-link {{ request()->is('dataproduk*') ? 'active' : '' }}"><i class="fas fa-boxes mr-2"></i> Database Material</a>
          </li>
          <li class="mobile-nav-item">
            <a href="{{ url('barangkeluar') }}" class="mobile-nav-link {{ request()->is('barangkeluar*') ? 'active' : '' }}"><i class="fas fa-truck-loading mr-2"></i> Pengeluaran Barang</a>
          </li>
          <li class="mobile-nav-item">
            <a href="{{ url('purchase_request') }}" class="mobile-nav-link {{ request()->is('purchase_request*') ? 'active' : '' }}"><i class="fas fa-file-alt mr-2"></i> Purchase Request</a>
          </li>
          @endif
          
          <!-- Menu untuk Staff4 -->
          @if(Auth::user()->role === 'Staff4')
          <li class="mobile-nav-item">
            <a href="{{ url('dashboard') }}" class="mobile-nav-link {{ request()->is('dashboard*') ? 'active' : '' }}"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a>
          </li>
          <li class="mobile-nav-item">
            <a href="{{ url('rekapitulasi_pekerjaan') }}" class="mobile-nav-link {{ request()->is('rekapitulasi_pekerjaan*') ? 'active' : '' }}"><i class="fas fa-clipboard-list mr-2"></i> Laporan Harian</a>
          </li>
          <li class="mobile-nav-item">
            <a href="{{ url('data_karyawan') }}" class="mobile-nav-link {{ request()->is('data_karyawan*') ? 'active' : '' }}"><i class="fas fa-id-card mr-2"></i> Data Karyawan</a>
          </li>
          <li class="mobile-nav-item">
            <a href="{{ url('kontrak') }}" class="mobile-nav-link {{ request()->is('kontrak*') ? 'active' : '' }}"><i class="fas fa-file-contract mr-2"></i> Kontrak Kerja</a>
          </li>
          <li class="mobile-nav-item">
            <a href="{{ url('gaji') }}" class="mobile-nav-link {{ request()->is('gaji*') ? 'active' : '' }}"><i class="fas fa-money-bill-wave mr-2"></i> Gaji</a>
          </li>
          <li class="mobile-nav-item">
            <a href="{{ url('absensi') }}" class="mobile-nav-link {{ request()->is('absensi*') ? 'active' : '' }}"><i class="fas fa-calendar-check mr-2"></i> Absensi</a>
          </li>
          <li class="mobile-nav-item">
            <a href="{{ url('home') }}" class="mobile-nav-link {{ request()->is('home*') && !request()->is('home/*/profile') ? 'active' : '' }}"><i class="fas fa-users mr-2"></i> Data User Karek</a>
          </li>
          @endif
          
          <!-- Menu untuk Staff5 -->
          @if(Auth::user()->role === 'Staff5')
          <li class="mobile-nav-item">
            <a href="{{ url('dashboard') }}" class="mobile-nav-link {{ request()->is('dashboard*') ? 'active' : '' }}"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a>
          </li>
          <li class="mobile-nav-item">
            <a href="#" class="mobile-nav-link mobile-dropdown-toggle {{ request()->is('cashflow*') || request()->is('hutang_piutang*') || request()->is('pengeluaran*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#mobileFinanceDropdown" aria-expanded="{{ request()->is('cashflow*') || request()->is('hutang_piutang*') || request()->is('pengeluaran*') ? 'true' : 'false' }}">
              <i class="fas fa-money-bill-alt mr-2"></i> Finance
            </a>
            <ul class="mobile-dropdown-menu collapse {{ request()->is('cashflow*') || request()->is('hutang_piutang*') || request()->is('pengeluaran*') ? 'show' : '' }}" id="mobileFinanceDropdown">
              <li class="mobile-dropdown-item"><a href="{{ url('cashflow') }}" class="mobile-dropdown-link {{ request()->is('cashflow*') ? 'active' : '' }}"><i class="fas fa-chart-line mr-2"></i> Cashflow</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('hutang_piutang') }}" class="mobile-dropdown-link {{ request()->is('hutang_piutang*') ? 'active' : '' }}"><i class="fas fa-balance-scale mr-2"></i> Hutang & Piutang</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('pengeluaran') }}" class="mobile-dropdown-link {{ request()->is('pengeluaran*') ? 'active' : '' }}"><i class="fas fa-hand-holding-usd mr-2"></i> Pengeluaran</a></li>
            </ul>
          </li>
          <li class="mobile-nav-item">
            <a href="#" class="mobile-nav-link mobile-dropdown-toggle {{ request()->is('dataproduk*') || request()->is('barangkeluar*') || request()->is('purchase_request*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#mobileWarehouseDropdown" aria-expanded="{{ request()->is('dataproduk*') || request()->is('barangkeluar*') || request()->is('purchase_request*') ? 'true' : 'false' }}">
              <i class="fas fa-warehouse mr-2"></i> Warehouse
            </a>
            <ul class="mobile-dropdown-menu collapse {{ request()->is('dataproduk*') || request()->is('barangkeluar*') || request()->is('purchase_request*') ? 'show' : '' }}" id="mobileWarehouseDropdown">
              <li class="mobile-dropdown-item"><a href="{{ url('dataproduk') }}" class="mobile-dropdown-link {{ request()->is('dataproduk*') ? 'active' : '' }}"><i class="fas fa-boxes mr-2"></i> Database Material</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('barangkeluar') }}" class="mobile-dropdown-link {{ request()->is('barangkeluar*') ? 'active' : '' }}"><i class="fas fa-truck-loading mr-2"></i> Pengeluaran Barang</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('purchase_request') }}" class="mobile-dropdown-link {{ request()->is('purchase_request*') ? 'active' : '' }}"><i class="fas fa-file-alt mr-2"></i> Purchase Request</a></li>
            </ul>
          </li>
          <li class="mobile-nav-item">
            <a href="#" class="mobile-nav-link mobile-dropdown-toggle {{ request()->is('rekap_lapangan*') || request()->is('rekap_keuangan*') || request()->is('rekap_purchasing*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#mobileDocumentDropdown" aria-expanded="{{ request()->is('rekap_lapangan*') || request()->is('rekap_keuangan*') || request()->is('rekap_purchasing*') ? 'true' : 'false' }}">
              <i class="fas fa-folder-open mr-2"></i> Dokumen
            </a>
            <ul class="mobile-dropdown-menu collapse {{ request()->is('rekap_lapangan*') || request()->is('rekap_keuangan*') || request()->is('rekap_purchasing*') ? 'show' : '' }}" id="mobileDocumentDropdown">
              <li class="mobile-dropdown-item"><a href="{{ url('rekap_lapangan') }}" class="mobile-dropdown-link {{ request()->is('rekap_lapangan*') ? 'active' : '' }}"><i class="fas fa-clipboard mr-2"></i> Rekap Data Lapangan</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('rekap_keuangan') }}" class="mobile-dropdown-link {{ request()->is('rekap_keuangan*') ? 'active' : '' }}"><i class="fas fa-file-invoice mr-2"></i> Rekap Data Keuangan</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('rekap_purchasing') }}" class="mobile-dropdown-link {{ request()->is('rekap_purchasing*') ? 'active' : '' }}"><i class="fas fa-shopping-basket mr-2"></i> Rekap Data Purchasing</a></li>
            </ul>
          </li>
          @endif
          
          <!-- Menu untuk Staff6 -->
          @if(Auth::user()->role === 'Staff6')
          <li class="mobile-nav-item">
            <a href="{{ url('dashboard') }}" class="mobile-nav-link {{ request()->is('dashboard*') ? 'active' : '' }}"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a>
          </li>
          <li class="mobile-nav-item">
            <a href="#" class="mobile-nav-link mobile-dropdown-toggle {{ request()->is('rekapitulasi_pekerjaan*') || request()->is('penagihan*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#mobileProcessDropdown" aria-expanded="{{ request()->is('rekapitulasi_pekerjaan*') || request()->is('penagihan*') ? 'true' : 'false' }}">
              <i class="fas fa-tasks mr-2"></i> Process
            </a>
            <ul class="mobile-dropdown-menu collapse {{ request()->is('rekapitulasi_pekerjaan*') || request()->is('penagihan*') ? 'show' : '' }}" id="mobileProcessDropdown">
              <li class="mobile-dropdown-item"><a href="{{ url('rekapitulasi_pekerjaan') }}" class="mobile-dropdown-link {{ request()->is('rekapitulasi_pekerjaan*') ? 'active' : '' }}"><i class="fas fa-clipboard-list mr-2"></i> Laporan Harian</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('penagihan') }}" class="mobile-dropdown-link {{ request()->is('penagihan*') ? 'active' : '' }}"><i class="fas fa-file-invoice-dollar mr-2"></i> Penagihan</a></li>
            </ul>
          </li>
          <li class="mobile-nav-item">
            <a href="{{ url('purchasing') }}" class="mobile-nav-link {{ request()->is('purchasing*') ? 'active' : '' }}"><i class="fas fa-shopping-cart mr-2"></i> Purchasing</a>
          </li>
          <li class="mobile-nav-item">
            <a href="#" class="mobile-nav-link mobile-dropdown-toggle {{ request()->is('dataproduk*') || request()->is('barangkeluar*') || request()->is('purchase_request*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#mobileWarehouseDropdown" aria-expanded="{{ request()->is('dataproduk*') || request()->is('barangkeluar*') || request()->is('purchase_request*') ? 'true' : 'false' }}">
              <i class="fas fa-warehouse mr-2"></i> Warehouse
            </a>
            <ul class="mobile-dropdown-menu collapse {{ request()->is('dataproduk*') || request()->is('barangkeluar*') || request()->is('purchase_request*') ? 'show' : '' }}" id="mobileWarehouseDropdown">
              <li class="mobile-dropdown-item"><a href="{{ url('dataproduk') }}" class="mobile-dropdown-link {{ request()->is('dataproduk*') ? 'active' : '' }}"><i class="fas fa-boxes mr-2"></i> Database Material</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('barangkeluar') }}" class="mobile-dropdown-link {{ request()->is('barangkeluar*') ? 'active' : '' }}"><i class="fas fa-truck-loading mr-2"></i> Pengeluaran Barang</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('purchase_request') }}" class="mobile-dropdown-link {{ request()->is('purchase_request*') ? 'active' : '' }}"><i class="fas fa-file-alt mr-2"></i> Purchase Request</a></li>
            </ul>
          </li>
          <li class="mobile-nav-item">
            <a href="#" class="mobile-nav-link mobile-dropdown-toggle {{ request()->is('cashflow*') || request()->is('hutang_piutang*') || request()->is('pengeluaran*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#mobileFinanceDropdown" aria-expanded="{{ request()->is('cashflow*') || request()->is('hutang_piutang*') || request()->is('pengeluaran*') ? 'true' : 'false' }}">
              <i class="fas fa-money-bill-alt mr-2"></i> Finance
            </a>
            <ul class="mobile-dropdown-menu collapse {{ request()->is('cashflow*') || request()->is('hutang_piutang*') || request()->is('pengeluaran*') ? 'show' : '' }}" id="mobileFinanceDropdown">
              <li class="mobile-dropdown-item"><a href="{{ url('cashflow') }}" class="mobile-dropdown-link {{ request()->is('cashflow*') ? 'active' : '' }}"><i class="fas fa-chart-line mr-2"></i> Cashflow</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('hutang_piutang') }}" class="mobile-dropdown-link {{ request()->is('hutang_piutang*') ? 'active' : '' }}"><i class="fas fa-balance-scale mr-2"></i> Hutang & Piutang</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('pengeluaran') }}" class="mobile-dropdown-link {{ request()->is('pengeluaran*') ? 'active' : '' }}"><i class="fas fa-hand-holding-usd mr-2"></i> Pengeluaran</a></li>
            </ul>
          </li>
          <li class="mobile-nav-item">
            <a href="#" class="mobile-nav-link mobile-dropdown-toggle {{ request()->is('data_karyawan*') || request()->is('kontrak*') || request()->is('gaji*') || request()->is('absensi*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#mobileHRDDropdown" aria-expanded="{{ request()->is('data_karyawan*') || request()->is('kontrak*') || request()->is('gaji*') || request()->is('absensi*') ? 'true' : 'false' }}">
              <i class="fas fa-users mr-2"></i> HRD & GA
            </a>
            <ul class="mobile-dropdown-menu collapse {{ request()->is('data_karyawan*') || request()->is('kontrak*') || request()->is('gaji*') || request()->is('absensi*') ? 'show' : '' }}" id="mobileHRDDropdown">
              <li class="mobile-dropdown-item"><a href="{{ url('data_karyawan') }}" class="mobile-dropdown-link {{ request()->is('data_karyawan*') ? 'active' : '' }}"><i class="fas fa-id-card mr-2"></i> Data Karyawan</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('kontrak') }}" class="mobile-dropdown-link {{ request()->is('kontrak*') ? 'active' : '' }}"><i class="fas fa-file-contract mr-2"></i> Kontrak Kerja</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('gaji') }}" class="mobile-dropdown-link {{ request()->is('gaji*') ? 'active' : '' }}"><i class="fas fa-money-bill-wave mr-2"></i> Gaji</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('absensi') }}" class="mobile-dropdown-link {{ request()->is('absensi*') ? 'active' : '' }}"><i class="fas fa-calendar-check mr-2"></i> Absensi</a></li>
            </ul>
          </li>
          <li class="mobile-nav-item">
            <a href="#" class="mobile-nav-link mobile-dropdown-toggle {{ request()->is('rekap_lapangan*') || request()->is('rekap_keuangan*') || request()->is('rekap_purchasing*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#mobileDocumentDropdown" aria-expanded="{{ request()->is('rekap_lapangan*') || request()->is('rekap_keuangan*') || request()->is('rekap_purchasing*') ? 'true' : 'false' }}">
              <i class="fas fa-folder-open mr-2"></i> Dokumen
            </a>
            <ul class="mobile-dropdown-menu collapse {{ request()->is('rekap_lapangan*') || request()->is('rekap_keuangan*') || request()->is('rekap_purchasing*') ? 'show' : '' }}" id="mobileDocumentDropdown">
              <li class="mobile-dropdown-item"><a href="{{ url('rekap_lapangan') }}" class="mobile-dropdown-link {{ request()->is('rekap_lapangan*') ? 'active' : '' }}"><i class="fas fa-clipboard mr-2"></i> Rekap Data Lapangan</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('rekap_keuangan') }}" class="mobile-dropdown-link {{ request()->is('rekap_keuangan*') ? 'active' : '' }}"><i class="fas fa-file-invoice mr-2"></i> Rekap Data Keuangan</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('rekap_purchasing') }}" class="mobile-dropdown-link {{ request()->is('rekap_purchasing*') ? 'active' : '' }}"><i class="fas fa-shopping-basket mr-2"></i> Rekap Data Purchasing</a></li>
            </ul>
          </li>
          <li class="mobile-nav-item">
            <a href="{{ url('home') }}" class="mobile-nav-link {{ request()->is('home*') && !request()->is('home/*/profile') ? 'active' : '' }}"><i class="fas fa-cogs mr-2"></i> IT Management</a>
          </li>
          @endif
          
          <!-- Menu untuk Staff7 -->
          @if(Auth::user()->role === 'Staff7')
          <li class="mobile-nav-item">
            <a href="{{ url('dashboard') }}" class="mobile-nav-link {{ request()->is('dashboard*') ? 'active' : '' }}"><i class="fas fa-tachometer-alt mr-2"></i> Dashboard</a>
          </li>
          <li class="mobile-nav-item">
            <a href="#" class="mobile-nav-link mobile-dropdown-toggle {{ request()->is('dataproduk*') || request()->is('barangkeluar*') || request()->is('purchase_request*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#mobileWarehouseDropdown" aria-expanded="{{ request()->is('dataproduk*') || request()->is('barangkeluar*') || request()->is('purchase_request*') ? 'true' : 'false' }}">
              <i class="fas fa-warehouse mr-2"></i> Warehouse
            </a>
            <ul class="mobile-dropdown-menu collapse {{ request()->is('dataproduk*') || request()->is('barangkeluar*') || request()->is('purchase_request*') ? 'show' : '' }}" id="mobileWarehouseDropdown">
              <li class="mobile-dropdown-item"><a href="{{ url('dataproduk') }}" class="mobile-dropdown-link {{ request()->is('dataproduk*') ? 'active' : '' }}"><i class="fas fa-boxes mr-2"></i> Database Material</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('barangkeluar') }}" class="mobile-dropdown-link {{ request()->is('barangkeluar*') ? 'active' : '' }}"><i class="fas fa-truck-loading mr-2"></i> Pengeluaran Barang</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('purchase_request') }}" class="mobile-dropdown-link {{ request()->is('purchase_request*') ? 'active' : '' }}"><i class="fas fa-file-alt mr-2"></i> Purchase Request</a></li>
            </ul>
          </li>
          <li class="mobile-nav-item">
            <a href="#" class="mobile-nav-link mobile-dropdown-toggle {{ request()->is('data_karyawan*') || request()->is('kontrak*') || request()->is('gaji*') || request()->is('absensi*') ? 'active' : '' }}" data-bs-toggle="collapse" data-bs-target="#mobileHRDDropdown" aria-expanded="{{ request()->is('data_karyawan*') || request()->is('kontrak*') || request()->is('gaji*') || request()->is('absensi*') ? 'true' : 'false' }}">
              <i class="fas fa-users mr-2"></i> HRD & GA
            </a>
            <ul class="mobile-dropdown-menu collapse {{ request()->is('data_karyawan*') || request()->is('kontrak*') || request()->is('gaji*') || request()->is('absensi*') ? 'show' : '' }}" id="mobileHRDDropdown">
              <li class="mobile-dropdown-item"><a href="{{ url('data_karyawan') }}" class="mobile-dropdown-link {{ request()->is('data_karyawan*') ? 'active' : '' }}"><i class="fas fa-id-card mr-2"></i> Data Karyawan</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('kontrak') }}" class="mobile-dropdown-link {{ request()->is('kontrak*') ? 'active' : '' }}"><i class="fas fa-file-contract mr-2"></i> Kontrak Kerja</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('gaji') }}" class="mobile-dropdown-link {{ request()->is('gaji*') ? 'active' : '' }}"><i class="fas fa-money-bill-wave mr-2"></i> Gaji</a></li>
              <li class="mobile-dropdown-item"><a href="{{ url('absensi') }}" class="mobile-dropdown-link {{ request()->is('absensi*') ? 'active' : '' }}"><i class="fas fa-calendar-check mr-2"></i> Absensi</a></li>
            </ul>
          </li>
          @endif
        </ul>
      </div>
    </div>
  </div>
</div>

<!-- Main Content -->
<main>
  <!-- Additional content can be added here -->
</main>
<!-- JavaScript Section -->
<script>
/* ============================================================================
   KAREK V5 - JAVASCRIPT FUNCTIONALITY - FIXED VERSION
   Enhanced with better performance and NO CLICK BLOCKING
   Version: 5.0 - Final Fix
============================================================================ */

document.addEventListener('DOMContentLoaded', function() {
  'use strict';
  
  // =========================================================================
  // 1. FULLSCREEN FUNCTIONALITY
  // =========================================================================
  const fullscreenBtn = document.getElementById('fullscreenBtn');

  function toggleFullscreen() {
    if (!document.fullscreenElement) {
      document.documentElement.requestFullscreen().catch(err => {
        console.warn(`Error attempting to enable fullscreen: ${err.message}`);
      });
      if (fullscreenBtn) {
        fullscreenBtn.classList.replace('fa-expand', 'fa-compress');
      }
      localStorage.setItem('fullscreen', 'true');
    } else {
      if (document.exitFullscreen) {
        document.exitFullscreen();
        if (fullscreenBtn) {
          fullscreenBtn.classList.replace('fa-compress', 'fa-expand');
        }
        localStorage.setItem('fullscreen', 'false');
      }
    }
  }

  // Check if fullscreen was previously enabled
  if (localStorage.getItem('fullscreen') === 'true') {
    try {
      document.documentElement.requestFullscreen();
      if (fullscreenBtn) {
        fullscreenBtn.classList.replace('fa-expand', 'fa-compress');
      }
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
    if (!document.fullscreenElement && fullscreenBtn) {
      fullscreenBtn.classList.replace('fa-compress', 'fa-expand');
      localStorage.setItem('fullscreen', 'false');
    }
  });

  // =========================================================================
  // 2. DROPDOWN POSITIONING ENHANCEMENT
  // =========================================================================
  const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
  
  dropdownToggles.forEach(toggle => {
    toggle.addEventListener('click', function(e) {
      const dropdown = this.closest('.dropdown');
      const menu = dropdown ? dropdown.querySelector('.dropdown-menu') : null;
      
      if (!menu) return;
      
      // Get the position of the toggle button
      const rect = this.getBoundingClientRect();
      
      // Position the dropdown menu below the toggle button
      menu.style.position = 'absolute';
      menu.style.top = '100%';
      menu.style.left = '0';
      menu.style.zIndex = '9999';
      
      // Ensure the dropdown doesn't go off-screen horizontally
      setTimeout(() => {
        const menuRect = menu.getBoundingClientRect();
        const viewportWidth = window.innerWidth;
        
        if (menuRect.right > viewportWidth - 10) {
          menu.style.left = 'auto';
          menu.style.right = '0';
        }
      }, 10);
    });
  });

  // =========================================================================
  // 3. HORIZONTAL SCROLL FOR NAVBAR - FIXED (NO CLICK BLOCKING!)
  // =========================================================================
  const navMenuContainer = document.getElementById('navMenuContainer');
  
  if (navMenuContainer) {
    let isDown = false;
    let startX;
    let scrollLeft;
    let hasMoved = false;
    let isDragging = false;
    
    // ✅ DAFTAR ELEMEN YANG HARUS BISA DIKLIK
    const clickableSelectors = [
      'a', 'button', 'input', 'select', 'textarea',
      '.nav-link', '.dropdown-toggle', '.dropdown-item',
      '.btn', '.form-control', '.dropdown-menu',
      '[onclick]', '[data-bs-toggle]', '[data-toggle]',
      '[href]', '.clickable'
    ];
    
    // ✅ FUNGSI CEK APAKAH ELEMEN BISA DIKLIK
    function isClickableElement(element) {
      if (!element) return false;
      
      // Cek apakah element atau parent-nya adalah clickable
      return clickableSelectors.some(selector => {
        try {
          return element.matches(selector) || element.closest(selector);
        } catch (e) {
          return false;
        }
      });
    }
    
    // ✅ MOUSEDOWN - HANYA UNTUK DRAG, TIDAK BLOCK KLIK
    navMenuContainer.addEventListener('mousedown', (e) => {
      // JANGAN BLOCK KLIK PADA ELEMEN CLICKABLE
      if (isClickableElement(e.target)) {
        return; // Biarkan event berjalan normal
      }
      
      // JANGAN BLOCK KLIK KANAN
      if (e.button !== 0) {
        return;
      }
      
      isDown = true;
      isDragging = false;
      hasMoved = false;
      startX = e.pageX - navMenuContainer.offsetLeft;
      scrollLeft = navMenuContainer.scrollLeft;
      
      // ✅ JANGAN GUNAKAN preventDefault() DI SINI!
    });

    // ✅ MOUSELEAVE
    navMenuContainer.addEventListener('mouseleave', () => {
      isDown = false;
      isDragging = false;
      hasMoved = false;
      navMenuContainer.classList.remove('grabbing');
    });

    // ✅ MOUSEUP
    navMenuContainer.addEventListener('mouseup', (e) => {
      // Jika sedang drag, prevent klik
      if (isDragging && hasMoved) {
        e.preventDefault();
        e.stopPropagation();
      }
      
      isDown = false;
      isDragging = false;
      navMenuContainer.classList.remove('grabbing');
      
      // Reset flag setelah delay singkat
      setTimeout(() => {
        hasMoved = false;
      }, 10);
    });

    // ✅ MOUSEMOVE - HANYA SCROLL SAAT BENAR-BENAR DRAG
    navMenuContainer.addEventListener('mousemove', (e) => {
      if (!isDown) return;
      
      const x = e.pageX - navMenuContainer.offsetLeft;
      const walk = (x - startX) * 2;
      
      // HANYA SCROLL JIKA USER BENAR-BENAR DRAG (lebih dari 5px)
      if (Math.abs(walk) > 5) {
        isDragging = true;
        hasMoved = true;
        navMenuContainer.classList.add('grabbing');
        e.preventDefault(); // ✅ Sekarang aman untuk prevent
        navMenuContainer.scrollLeft = scrollLeft - walk;
      }
    });

    // ✅ CLICK EVENT - PREVENT JIKA BARU SAJA DRAG
    navMenuContainer.addEventListener('click', (e) => {
      // Jika user baru saja drag, cancel klik
      if (hasMoved && isDragging) {
        e.preventDefault();
        e.stopPropagation();
        return false;
      }
    }, true); // Use capture phase

    // ✅ TOUCH EVENTS FOR MOBILE
    let touchStartX = 0;
    let touchStartScrollLeft = 0;
    let touchHasMoved = false;

    navMenuContainer.addEventListener('touchstart', (e) => {
      // JANGAN BLOCK TOUCH PADA ELEMEN CLICKABLE
      if (isClickableElement(e.target)) {
        return;
      }
      
      touchHasMoved = false;
      touchStartX = e.touches[0].pageX;
      touchStartScrollLeft = navMenuContainer.scrollLeft;
    }, { passive: true });

    navMenuContainer.addEventListener('touchmove', (e) => {
      const touchX = e.touches[0].pageX;
      const walk = (touchStartX - touchX) * 1.5;
      
      if (Math.abs(walk) > 5) {
        touchHasMoved = true;
        navMenuContainer.scrollLeft = touchStartScrollLeft + walk;
      }
    }, { passive: true });

    navMenuContainer.addEventListener('touchend', () => {
      setTimeout(() => {
        touchHasMoved = false;
      }, 10);
    }, { passive: true });

    // Function to ensure all menu items are visible
    function fixMenuVisibility() {
      const navMenu = navMenuContainer.querySelector('.nav-menu');
      if (!navMenu) return;
      
      const menuItems = navMenu.querySelectorAll('.nav-item');
      let totalWidth = 0;
      
      menuItems.forEach(item => {
        const style = window.getComputedStyle(item);
        const itemWidth = item.offsetWidth + 
                        parseInt(style.marginLeft || 0) + 
                        parseInt(style.marginRight || 0);
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

  // =========================================================================
  // 4. ACTIVE MENU HIGHLIGHTING
  // =========================================================================
  function setActiveMenuItems() {
    const currentPath = window.location.pathname;
    
    // Clear all active states first
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
      
      // Special case for root/dashboard
      if ((hrefPath.endsWith('/dashboard') || hrefPath === '/') && 
          (currentPath.endsWith('/dashboard') || currentPath === '/')) {
        link.classList.add('active-menu-item');
        return;
      }
      
      // Check if current path matches or starts with the href path
      if (currentPath === hrefPath || 
          (hrefPath !== '/' && currentPath.startsWith(hrefPath) && 
           (currentPath.length === hrefPath.length || currentPath[hrefPath.length] === '/'))) {
        link.classList.add('active-menu-item');
        
        // If this is in a dropdown, also activate parent
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
      
      // Special case for root/dashboard
      if ((hrefPath.endsWith('/dashboard') || hrefPath === '/') && 
          (currentPath.endsWith('/dashboard') || currentPath === '/')) {
        link.classList.add('active');
        return;
      }
      
      if (currentPath === hrefPath || 
          (hrefPath !== '/' && currentPath.startsWith(hrefPath) && 
           (currentPath.length === hrefPath.length || currentPath[hrefPath.length] === '/'))) {
        link.classList.add('active');
        
        // If this is in a dropdown, expand the dropdown
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

  // Call immediately and after page has fully loaded
  setActiveMenuItems();
  window.addEventListener('popstate', setActiveMenuItems);

  // =========================================================================
  // 5. RIPPLE EFFECT FOR BUTTONS AND LINKS
  // =========================================================================
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

  // =========================================================================
  // 6. RESPONSIVE ADJUSTMENTS
  // =========================================================================
  function handleResponsiveLayout() {
    const navbar = document.querySelector('.navbar');
    
    if (window.innerWidth <= 768) {
      if (navbar) {
        navbar.style.borderRadius = '0 0 30px 30px';
        navbar.style.height = '90px';
      }
    } else if (window.innerWidth <= 992) {
      if (navbar) {
        navbar.style.borderRadius = '0 0 35px 35px';
        navbar.style.height = '80px';
      }
    } else {
      if (navbar) {
        navbar.style.borderRadius = '0 0 40px 40px';
        navbar.style.height = '90px';
      }
    }
  }
  
  handleResponsiveLayout();

  // =========================================================================
  // 7. PERFORMANCE OPTIMIZATIONS
  // =========================================================================
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
                          parseInt(style.marginLeft || 0) + 
                          parseInt(style.marginRight || 0);
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
    }
    handleResponsiveLayout();
  }, 250);
  
  window.addEventListener('resize', debouncedResize);

  // =========================================================================
  // 8. SMOOTH SCROLL TO TOP ON LOGO CLICK
  // =========================================================================
  const brandLink = document.querySelector('.karek-brand-link');
  if (brandLink) {
    brandLink.addEventListener('click', function(e) {
      const href = this.getAttribute('href');
      if (href === window.location.pathname || href === '#') {
        e.preventDefault();
        window.scrollTo({
          top: 0,
          behavior: 'smooth'
        });
      }
    });
  }

  // =========================================================================
  // 9. MOBILE MENU MODAL ENHANCEMENTS
  // =========================================================================
  const mobileMenuModal = document.getElementById('mobileMenuModal');
  
  if (mobileMenuModal) {
    // Close modal when clicking on a link (except dropdown toggles)
    mobileMenuModal.addEventListener('click', function(e) {
      if (e.target.classList.contains('mobile-nav-link') && 
          !e.target.classList.contains('mobile-dropdown-toggle')) {
        const bsModal = bootstrap.Modal.getInstance(mobileMenuModal);
        if (bsModal) {
          bsModal.hide();
        }
      }
      
      if (e.target.classList.contains('mobile-dropdown-link')) {
        const bsModal = bootstrap.Modal.getInstance(mobileMenuModal);
        if (bsModal) {
          bsModal.hide();
        }
      }
    });
    
    // Reset scroll position when modal is opened
    mobileMenuModal.addEventListener('shown.bs.modal', function() {
      const modalBody = this.querySelector('.modal-body');
      if (modalBody) {
        modalBody.scrollTop = 0;
      }
    });
    
    // Prevent body scroll when modal is open
    mobileMenuModal.addEventListener('show.bs.modal', function() {
      document.body.style.overflow = 'hidden';
    });
    
    mobileMenuModal.addEventListener('hidden.bs.modal', function() {
      document.body.style.overflow = '';
    });
  }

  // =========================================================================
  // 10. DROPDOWN TOGGLE FUNCTIONALITY FOR MOBILE
  // =========================================================================
  const mobileDropdownToggles = document.querySelectorAll('.mobile-dropdown-toggle');
  
  mobileDropdownToggles.forEach(toggle => {
    toggle.addEventListener('click', function(e) {
      e.preventDefault();
      
      const targetId = this.getAttribute('data-bs-target');
      const targetMenu = document.querySelector(targetId);
      
      if (targetMenu) {
        const isExpanded = this.getAttribute('aria-expanded') === 'true';
        
        // Toggle aria-expanded
        this.setAttribute('aria-expanded', !isExpanded);
        
        // Toggle show class
        targetMenu.classList.toggle('show');
      }
    });
  });

  // =========================================================================
  // 11. PREVENT DROPDOWN CLOSE ON CLICK INSIDE
  // =========================================================================
  document.querySelectorAll('.dropdown-menu').forEach(menu => {
    menu.addEventListener('click', function(e) {
      e.stopPropagation();
    });
  });

  // =========================================================================
  // 12. KEYBOARD NAVIGATION SUPPORT
  // =========================================================================
  document.addEventListener('keydown', function(e) {
    // Escape key to close dropdowns
    if (e.key === 'Escape') {
      document.querySelectorAll('.dropdown.show').forEach(dropdown => {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        if (toggle && typeof bootstrap !== 'undefined') {
          const bsDropdown = bootstrap.Dropdown.getInstance(toggle);
          if (bsDropdown) {
            bsDropdown.hide();
          }
        }
      });
      
      // Close mobile menu modal
      if (mobileMenuModal && typeof bootstrap !== 'undefined') {
        const bsModal = bootstrap.Modal.getInstance(mobileMenuModal);
        if (bsModal) {
          bsModal.hide();
        }
      }
    }
    
    // F11 for fullscreen toggle
    if (e.key === 'F11') {
      e.preventDefault();
      toggleFullscreen();
    }
  });

  // =========================================================================
  // 13. FORCE ANIMATIONS TO RUN
  // =========================================================================
  function forceAnimations() {
    const elements = [
      '.navbar',
      '.navbar-brand img',
      '.karek-logo-text',
      '.karek-tagline',
      '.fullscreen-btn',
      '.user-avatar img'
    ];
    
    elements.forEach(selector => {
      const element = document.querySelector(selector);
      if (element) {
        element.style.animation = 'none';
        element.offsetHeight; // Trigger reflow
        element.style.animation = null;
      }
    });
  }
  
  // Force animations on load
  forceAnimations();
  setTimeout(forceAnimations, 100);

  // =========================================================================
  // 14. NAVBAR SCROLL BEHAVIOR
  // =========================================================================
  let lastScrollTop = 0;
  const navbar = document.querySelector('.navbar');
  
  window.addEventListener('scroll', debounce(function() {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
    if (scrollTop > lastScrollTop && scrollTop > 100) {
      // Scrolling down
      if (navbar) {
        navbar.style.transform = 'translateY(-100%)';
        navbar.style.transition = 'transform 0.3s ease-in-out';
      }
    } else {
      // Scrolling up
      if (navbar) {
        navbar.style.transform = 'translateY(0)';
      }
    }
    
    lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
  }, 100));

  // =========================================================================
  // 15. GRADIENT ANIMATION CONTROL
  // =========================================================================
  function ensureGradientAnimation() {
    const navbar = document.querySelector('.navbar');
    if (!navbar) return;
    
    const computedStyle = window.getComputedStyle(navbar);
    const backgroundSize = computedStyle.backgroundSize;
    
    // Ensure background-size is set correctly
    if (!backgroundSize.includes('400%')) {
      navbar.style.backgroundSize = '400% 400%';
    }
    
    // Ensure animation is running
    const animationName = computedStyle.animationName;
    if (animationName === 'none' || !animationName.includes('gradient')) {
      navbar.style.animation = 'gradient-animation 15s ease infinite';
    }
  }
  
  ensureGradientAnimation();
  setInterval(ensureGradientAnimation, 5000);

  // =========================================================================
  // 16. ACCESSIBILITY IMPROVEMENTS
  // =========================================================================
  document.querySelectorAll('.nav-link, .dropdown-toggle').forEach(link => {
    if (!link.hasAttribute('aria-label')) {
      const text = link.textContent.trim();
      if (text) {
        link.setAttribute('aria-label', text);
      }
    }
  });
  
  const navMenu = document.querySelector('.nav-menu');
  if (navMenu && !navMenu.hasAttribute('role')) {
    navMenu.setAttribute('role', 'navigation');
  }

  // =========================================================================
  // 17. MOBILE TOUCH OPTIMIZATIONS
  // =========================================================================
  if ('ontouchstart' in window) {
    document.body.classList.add('touch-device');
    
    document.querySelectorAll('.nav-link, .dropdown-item, .mobile-nav-link').forEach(element => {
      element.addEventListener('touchstart', function() {
        this.style.transform = 'scale(0.98)';
      }, { passive: true });
      
      element.addEventListener('touchend', function() {
        this.style.transform = '';
      }, { passive: true });
    });
  }

  // =========================================================================
  // 18. CONSOLE WELCOME MESSAGE
  // =========================================================================
  console.log('%c KAREK V5 - Navigation System ', 
    'background: linear-gradient(90deg, #213823, #6B9071); color: white; font-size: 16px; padding: 10px; border-radius: 5px;');
  console.log('%c PT. Rohtek Amanah Global ', 
    'color: #6B9071; font-size: 14px; font-weight: bold;');
  console.log('%c Version: 5.0 Fixed | Loaded Successfully ✓', 
    'color: #4dabf7; font-size: 12px;');

  // =========================================================================
  // 19. FINAL INITIALIZATION CHECK
  // =========================================================================
  setTimeout(function() {
    console.log('✓ Navigation system fully initialized');
    console.log('✓ All animations running');
    console.log('✓ Event listeners attached');
    console.log('✓ Click events NOT blocked');
    console.log('✓ Responsive handlers active');
    
    // Dispatch custom event to signal initialization complete
    window.dispatchEvent(new CustomEvent('karekNavReady', {
      detail: {
        version: '5.0',
        timestamp: new Date().toISOString()
      }
    }));
  }, 500);

}); // End of DOMContentLoaded

/* ============================================================================
   END OF KAREK V5 JAVASCRIPT - FIXED VERSION
============================================================================ */
</script>

</body>
</html>




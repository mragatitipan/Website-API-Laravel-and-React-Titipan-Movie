@extends('auth.layouts.main')

@section('content')

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
  <div class="loading-spinner"></div>
</div>

<!-- Toast Container for Notifications -->
<div class="toast-container" id="toastContainer"></div>

<!-- Main Auth Container -->
<div class="auth-container">
<style>
/* ============================================
   KAREK V5.0 - AUTHENTICATION PAGE CSS (FINAL)
   PT Rohtek Amanah Global
   Developed by: IT Developer Muhammad Raga Titipan
   ============================================ */

/* ============================================
   CSS VARIABLES - COLOR PALETTE
   ============================================ */
:root {
  /* Primary Brand Colors */
  --primary-color: #213823;
  --secondary-color: #375534;
  --accent-color: #6B9071;
  --accent-hover: #8FB996;
  --dark-accent: #0F2A1D;
  
  /* Neutral Colors */
  --white: #FFFFFF;
  --light-gray: #F8F9FA;
  --gray: #E9ECEF;
  --medium-gray: #DEE2E6;
  --dark-gray: #6C757D;
  --text-dark: #212529;
  --text-muted: #6C757D;
  
  /* Status Colors */
  --success: #2ecc71;
  --warning: #f39c12;
  --danger: #e74c3c;
  --info: #3498db;
  
  /* Background Colors */
  --bg-overlay: rgba(0, 0, 0, 0.75);
  --bg-card: #FFFFFF;
  --bg-input: #F8F9FA;
  
  /* Border & Shadow */
  --border-color: #DEE2E6;
  --border-radius: 12px;
  --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
  --box-shadow-hover: 0 8px 30px rgba(0, 0, 0, 0.12);
  
  /* Transitions */
  --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  
  /* Form Dimensions */
  --form-max-width: 500px;
  --form-max-height: 85vh;
  
  /* NEW: Outline & Animation */
  --outline-width: 6px;
  --outline-color: rgba(255, 255, 255, 0.9);
  --gradient-speed: 15s;
}

/* ============================================
   GLOBAL RESET & BASE STYLES
   ============================================ */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  color: var(--text-dark);
  line-height: 1.6;
  overflow: hidden;
  position: relative;
}

/* ============================================
   ANIMATED GRADIENT BACKGROUND WITH PATTERN
   ============================================ */
body::before {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: 
    linear-gradient(135deg, 
      var(--primary-color) 0%, 
      var(--secondary-color) 25%, 
      var(--accent-color) 50%, 
      var(--secondary-color) 75%, 
      var(--primary-color) 100%
    );
  background-size: 400% 400%;
  animation: gradientShift var(--gradient-speed) ease infinite;
  z-index: -2;
}

@keyframes gradientShift {
  0% {
    background-position: 0% 50%;
  }
  25% {
    background-position: 50% 100%;
  }
  50% {
    background-position: 100% 50%;
  }
  75% {
    background-position: 50% 0%;
  }
  100% {
    background-position: 0% 50%;
  }
}

/* ============================================
   ARCHITECTURAL PATTERN OVERLAY (SVG)
   ============================================ */
body::after {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.06'%3E%3Ccircle cx='10' cy='10' r='2'/%3E%3Ccircle cx='30' cy='10' r='2'/%3E%3Ccircle cx='50' cy='10' r='2'/%3E%3Ccircle cx='70' cy='10' r='2'/%3E%3Ccircle cx='90' cy='10' r='2'/%3E%3Ccircle cx='10' cy='30' r='2'/%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3Ccircle cx='50' cy='30' r='2'/%3E%3Ccircle cx='70' cy='30' r='2'/%3E%3Ccircle cx='90' cy='30' r='2'/%3E%3Ccircle cx='10' cy='50' r='2'/%3E%3Ccircle cx='30' cy='50' r='2'/%3E%3Ccircle cx='50' cy='50' r='2'/%3E%3Ccircle cx='70' cy='50' r='2'/%3E%3Ccircle cx='90' cy='50' r='2'/%3E%3Ccircle cx='10' cy='70' r='2'/%3E%3Ccircle cx='30' cy='70' r='2'/%3E%3Ccircle cx='50' cy='70' r='2'/%3E%3Ccircle cx='70' cy='70' r='2'/%3E%3Ccircle cx='90' cy='70' r='2'/%3E%3Ccircle cx='10' cy='90' r='2'/%3E%3Ccircle cx='30' cy='90' r='2'/%3E%3Ccircle cx='50' cy='90' r='2'/%3E%3Ccircle cx='70' cy='90' r='2'/%3E%3Ccircle cx='90' cy='90' r='2'/%3E%3Cpath d='M10 10h20M50 10h20M10 30h20M50 30h20M10 50h20M50 50h20M10 70h20M50 70h20M10 90h20M50 90h20' stroke='%23ffffff' stroke-width='0.5' stroke-opacity='0.04'/%3E%3Cpath d='M10 10v20M30 10v20M50 10v20M70 10v20M90 10v20M10 50v20M30 50v20M50 50v20M70 50v20M90 50v20' stroke='%23ffffff' stroke-width='0.5' stroke-opacity='0.04'/%3E%3C/g%3E%3C/svg%3E");
  background-repeat: repeat;
  opacity: 0.4;
  animation: patternMove 60s linear infinite;
  z-index: -1;
  pointer-events: none;
}

@keyframes patternMove {
  0% {
    transform: translate(0, 0);
  }
  100% {
    transform: translate(60px, 60px);
  }
}

/* ============================================
   LOADING OVERLAY
   ============================================ */
.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.8);
  display: none;
  align-items: center;
  justify-content: center;
  z-index: 9999;
  backdrop-filter: blur(5px);
}

.loading-overlay.active {
  display: flex;
}

.loading-spinner {
  width: 60px;
  height: 60px;
  border: 5px solid rgba(255, 255, 255, 0.2);
  border-top-color: var(--accent-hover);
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

/* ============================================
   MAIN AUTH CONTAINER - WITH WHITE OUTLINE
   ============================================ */
.auth-container {
  display: grid;
  grid-template-columns: 1fr 1fr;
  max-width: 1200px;
  width: 100%;
  height: var(--form-max-height);
  background: var(--white);
  border-radius: 24px;
  overflow: hidden;
  position: relative;
  animation: fadeInScale 0.6s cubic-bezier(0.4, 0, 0.2, 1);
  z-index: 1;
  
  /* WHITE OUTLINE - Multiple Box Shadows for Glow Effect */
  box-shadow: 
    0 0 0 var(--outline-width) var(--outline-color),
    0 0 20px rgba(255, 255, 255, 0.5),
    0 0 40px rgba(255, 255, 255, 0.3),
    0 20px 60px rgba(0, 0, 0, 0.3);
}

/* Animated Glow Effect on Outline */
@keyframes outlineGlow {
  0%, 100% {
    box-shadow: 
      0 0 0 var(--outline-width) var(--outline-color),
      0 0 20px rgba(255, 255, 255, 0.5),
      0 0 40px rgba(255, 255, 255, 0.3),
      0 20px 60px rgba(0, 0, 0, 0.3);
  }
  50% {
    box-shadow: 
      0 0 0 var(--outline-width) rgba(255, 255, 255, 1),
      0 0 30px rgba(255, 255, 255, 0.7),
      0 0 60px rgba(255, 255, 255, 0.5),
      0 20px 60px rgba(0, 0, 0, 0.3);
  }
}

.auth-container {
  animation: fadeInScale 0.6s cubic-bezier(0.4, 0, 0.2, 1), 
             outlineGlow 3s ease-in-out infinite;
}

@keyframes fadeInScale {
  from {
    opacity: 0;
    transform: scale(0.95) translateY(20px);
  }
  to {
    opacity: 1;
    transform: scale(1) translateY(0);
  }
}

/* ============================================
   LEFT SECTION - BRAND WITH REAL LOGO
   ============================================ */
.brand-section {
  position: relative;
  background: url('/img/agency/login.png') center/cover;
  padding: 40px 30px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--white);
  overflow: hidden;
}



.brand-section::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.75);
  z-index: 1;
}

.brand-section::after {
  content: '';
  position: absolute;
  top: -50%;
  right: -50%;
  width: 200%;
  height: 200%;
  background: radial-gradient(circle, rgba(255, 255, 255, 0.03) 0%, transparent 70%);
  animation: rotate 30s linear infinite;
  z-index: 1;
}

@keyframes rotate {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

.brand-content {
  position: relative;
  z-index: 2;
  text-align: center;
  max-width: 450px;
}

/* Real Logo with White Outline (WARNA ASLI) */
.brand-logo {
  width: 120px;
  height: 120px;
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin: 0 auto 25px;
  border: 4px solid rgba(255, 255, 255, 0.8);
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
  animation: float 3s ease-in-out infinite;
  padding: 15px;
}

.brand-logo img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  /* WARNA ASLI - NO FILTER */
}

@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-10px); }
}

.brand-title {
  font-size: 2.2rem;
  font-weight: 800;
  letter-spacing: 3px;
  margin-bottom: 8px;
  text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
}

.brand-version {
  display: inline-block;
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(10px);
  padding: 6px 16px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
  margin-bottom: 15px;
  border: 1px solid rgba(255, 255, 255, 0.3);
}

.brand-acronym {
  font-size: 0.95rem;
  font-weight: 600;
  color: var(--accent-hover);
  margin-bottom: 12px;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.brand-subtitle {
  font-size: 0.95rem;
  line-height: 1.5;
  color: rgba(255, 255, 255, 0.9);
  margin-bottom: 30px;
}

/* Brand Features Grid */
.brand-features {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 12px;
  margin-top: 30px;
}

.feature-card {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  padding: 15px;
  border-radius: 12px;
  border: 1px solid rgba(255, 255, 255, 0.2);
  transition: var(--transition);
  text-align: center;
}

.feature-card:hover {
  background: rgba(255, 255, 255, 0.15);
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
}

.feature-icon {
  font-size: 1.8rem;
  display: block;
  margin-bottom: 8px;
}

.feature-title {
  font-weight: 700;
  font-size: 0.9rem;
  margin-bottom: 4px;
  color: var(--white);
}

.feature-desc {
  font-size: 0.75rem;
  color: rgba(255, 255, 255, 0.8);
  line-height: 1.3;
}

/* ============================================
   RIGHT SECTION - FORM AREA
   ============================================ */
.form-section {
  background: var(--white);
  display: flex;
  flex-direction: column;
  position: relative;
  overflow: hidden;
}

.form-wrapper {
  display: flex;
  flex-direction: column;
  height: 100%;
  overflow: hidden;
}

/* ============================================
   STICKY HEADER
   ============================================ */
.form-sticky-header {
  position: sticky;
  top: 0;
  background: var(--white);
  border-bottom: 2px solid var(--gray);
  padding: 12px 25px;
  z-index: 100;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  flex-shrink: 0;
}

.header-content {
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.header-logo-section {
  display: flex;
  align-items: center;
  gap: 12px;
}

/* Real Logo in Header (WARNA ASLI) */
.header-logo {
  width: 45px;
  height: 45px;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(248, 249, 250, 0.95));
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 12px rgba(33, 56, 35, 0.2);
  padding: 8px;
  border: 2px solid var(--primary-color);
}

.header-logo img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  /* WARNA ASLI - NO FILTER */
}

.header-text {
  display: flex;
  flex-direction: column;
}

.header-title {
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--primary-color);
  line-height: 1.2;
}

.header-subtitle {
  font-size: 0.7rem;
  color: var(--text-muted);
  font-weight: 500;
}

.header-badge {
  background: linear-gradient(135deg, var(--accent-color), var(--accent-hover));
  color: var(--white);
  padding: 6px 14px;
  border-radius: 20px;
  font-size: 0.7rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  gap: 5px;
  box-shadow: 0 4px 12px rgba(107, 144, 113, 0.3);
}

/* ============================================
   SCROLLABLE CONTENT AREA
   ============================================ */
.form-scrollable-content {
  flex: 1;
  overflow-y: auto;
  overflow-x: hidden;
  padding: 25px;
  background: var(--white);
}

.form-scrollable-content::-webkit-scrollbar {
  width: 6px;
}

.form-scrollable-content::-webkit-scrollbar-track {
  background: var(--light-gray);
  border-radius: 10px;
}

.form-scrollable-content::-webkit-scrollbar-thumb {
  background: var(--accent-color);
  border-radius: 10px;
}

.form-scrollable-content::-webkit-scrollbar-thumb:hover {
  background: var(--accent-hover);
}

/* ============================================
   AUTH TABS
   ============================================ */
.auth-tabs {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
}

.tab-btn {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 12px 16px;
  background: var(--light-gray);
  border: 2px solid var(--border-color);
  border-radius: var(--border-radius);
  font-size: 0.95rem;
  font-weight: 600;
  color: var(--text-dark);
  cursor: pointer;
  transition: var(--transition);
}

.tab-btn:hover {
  background: var(--gray);
  border-color: var(--accent-color);
  transform: translateY(-2px);
}

.tab-btn.active {
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  color: var(--white);
  border-color: var(--primary-color);
  box-shadow: 0 4px 15px rgba(33, 56, 35, 0.3);
}

.tab-btn span:first-child {
  font-size: 1.1rem;
}

/* ============================================
   FORM CONTENT CONTAINER
   ============================================ */
.form-content {
  display: none;
  animation: fadeIn 0.4s ease-in-out;
}

.form-content.active {
  display: block;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* ============================================
   FORM HEADER
   ============================================ */
.form-header {
  text-align: center;
  margin-bottom: 20px;
}

.form-title {
  font-size: 1.6rem;
  font-weight: 700;
  color: var(--primary-color);
  margin-bottom: 8px;
}

.form-subtitle {
  font-size: 0.9rem;
  color: var(--text-muted);
}

/* ============================================
   ALERTS
   ============================================ */
.alert {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  padding: 12px 15px;
  border-radius: var(--border-radius);
  margin-bottom: 15px;
  border-left: 4px solid;
  animation: slideDown 0.4s ease-out;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.alert-icon {
  font-size: 1.2rem;
  flex-shrink: 0;
}

.alert-danger {
  background: #fee;
  border-left-color: var(--danger);
  color: #c0392b;
}

.alert-info {
  background: #e3f2fd;
  border-left-color: var(--info);
  color: #1976d2;
}

.alert strong {
  font-weight: 700;
}

/* ============================================
   FORM GROUPS & INPUTS
   ============================================ */
.form-group {
  margin-bottom: 16px;
}

.form-label {
  display: block;
  font-weight: 600;
  color: var(--text-dark);
  margin-bottom: 6px;
  font-size: 0.9rem;
}

.required {
  color: var(--danger);
  margin-left: 3px;
}

.input-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

.input-icon {
  position: absolute;
  left: 12px;
  font-size: 1rem;
  color: var(--text-muted);
  pointer-events: none;
  z-index: 1;
}

.form-input,
.form-textarea,
.form-select {
  width: 100%;
  padding: 12px 15px;
  border: 2px solid var(--border-color);
  border-radius: var(--border-radius);
  font-size: 0.9rem;
  font-family: inherit;
  transition: var(--transition);
  background: var(--bg-input);
  color: var(--text-dark);
}

.form-input.with-icon {
  padding-left: 40px;
}

.form-input:focus,
.form-textarea:focus,
.form-select:focus {
  outline: none;
  border-color: var(--accent-color);
  background: var(--white);
  box-shadow: 0 0 0 4px rgba(107, 144, 113, 0.1);
}

.form-input::placeholder,
.form-textarea::placeholder {
  color: var(--text-muted);
}

.form-textarea {
  min-height: 100px;
  resize: vertical;
  line-height: 1.5;
}

/* Password Toggle */
.password-toggle {
  position: absolute;
  right: 12px;
  font-size: 1.1rem;
  cursor: pointer;
  user-select: none;
  transition: var(--transition);
  z-index: 1;
}

.password-toggle:hover {
  transform: scale(1.1);
}

/* ============================================
   CHECKBOX
   ============================================ */
.form-check {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 16px;
}

.form-check-input {
  width: 16px;
  height: 16px;
  cursor: pointer;
  accent-color: var(--accent-color);
}

.form-check-label {
  font-size: 0.85rem;
  color: var(--text-dark);
  cursor: pointer;
  user-select: none;
}

/* ============================================
   USER TYPE SELECTOR
   ============================================ */
.user-type-selector {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 12px;
}

.user-type-option {
  position: relative;
}

.user-type-radio {
  position: absolute;
  opacity: 0;
  pointer-events: none;
}

.user-type-label {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 16px;
  border: 2px solid var(--border-color);
  border-radius: var(--border-radius);
  background: var(--bg-input);
  cursor: pointer;
  transition: var(--transition);
  text-align: center;
  min-height: 120px;
}

.user-type-label:hover {
  border-color: var(--accent-color);
  background: var(--white);
  transform: translateY(-2px);
  box-shadow: var(--box-shadow);
}

.user-type-radio:checked + .user-type-label {
  border-color: var(--accent-color);
  background: linear-gradient(135deg, rgba(107, 144, 113, 0.1), rgba(143, 185, 150, 0.1));
  box-shadow: 0 0 0 4px rgba(107, 144, 113, 0.1);
}

.user-type-icon {
  font-size: 2.2rem;
  margin-bottom: 8px;
}

.user-type-title {
  font-weight: 700;
  font-size: 0.95rem;
  color: var(--primary-color);
  margin-bottom: 4px;
}

.user-type-desc {
  font-size: 0.8rem;
  color: var(--text-muted);
}

/* ============================================
   BUTTONS
   ============================================ */
.btn {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 12px 20px;
  border: none;
  border-radius: var(--border-radius);
  font-size: 0.95rem;
  font-weight: 600;
  cursor: pointer;
  transition: var(--transition);
  text-decoration: none;
  margin-bottom: 10px;
}

.btn-primary {
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  color: var(--white);
  box-shadow: 0 4px 15px rgba(33, 56, 35, 0.3);
}

.btn-primary:hover {
  background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(33, 56, 35, 0.4);
}

.btn-primary:active {
  transform: translateY(0);
}

.btn-secondary {
  background: var(--light-gray);
  color: var(--text-dark);
  border: 2px solid var(--border-color);
}

.btn-secondary:hover {
  background: var(--gray);
  border-color: var(--accent-color);
  transform: translateY(-2px);
}

.btn-whatsapp {
  background: linear-gradient(135deg, #25D366, #128C7E);
  color: var(--white);
  box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
}

.btn-whatsapp:hover {
  background: linear-gradient(135deg, #128C7E, #075E54);
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(37, 211, 102, 0.4);
}

.btn span:first-child {
  font-size: 1.1rem;
}

/* ============================================
   reCAPTCHA WRAPPER
   ============================================ */
.recaptcha-wrapper {
  margin-bottom: 16px;
  display: flex;
  justify-content: center;
}

.recaptcha-placeholder {
  background: var(--light-gray);
  border: 2px dashed var(--border-color);
  border-radius: var(--border-radius);
  padding: 16px;
  text-align: center;
  color: var(--text-muted);
  font-size: 0.85rem;
}

.recaptcha-placeholder small {
  display: block;
  margin-top: 4px;
  font-size: 0.75rem;
  color: var(--dark-gray);
}

/* ============================================
   WHATSAPP MESSAGE PREVIEW
   ============================================ */
.whatsapp-preview {
  background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
  border: 2px solid #81c784;
  border-radius: var(--border-radius);
  padding: 16px;
  margin-top: 20px;
  animation: slideDown 0.4s ease-out;
}

.whatsapp-preview-header {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 12px;
}

.whatsapp-preview-icon {
  font-size: 1.8rem;
}

.whatsapp-preview-title {
  font-weight: 700;
  font-size: 1rem;
  color: var(--primary-color);
}

.whatsapp-message-bubble {
  background: var(--white);
  border-radius: 12px;
  padding: 15px;
  margin-bottom: 12px;
  white-space: pre-wrap;
  word-wrap: break-word;
  font-size: 0.85rem;
  line-height: 1.5;
  color: var(--text-dark);
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  border-left: 4px solid #25D366;
  max-height: 200px;
  overflow-y: auto;
}

.whatsapp-copy-btn {
  width: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  padding: 10px;
  background: var(--white);
  border: 2px solid #25D366;
  border-radius: var(--border-radius);
  color: #128C7E;
  font-weight: 600;
  cursor: pointer;
  transition: var(--transition);
}

.whatsapp-copy-btn:hover {
  background: #25D366;
  color: var(--white);
  transform: translateY(-2px);
}

/* ============================================
   FORM FOOTER
   ============================================ */
.form-footer {
  margin-top: 20px;
  padding-top: 16px;
  border-top: 2px solid var(--gray);
  text-align: center;
  font-size: 0.8rem;
  color: var(--text-muted);
}

.form-footer p {
  margin-bottom: 4px;
}

.form-footer strong {
  color: var(--primary-color);
  font-weight: 700;
}

/* ============================================
   CHARACTER COUNTER
   ============================================ */
.char-counter {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 6px;
  font-size: 0.8rem;
  color: var(--text-muted);
}

.char-count {
  font-weight: 600;
  color: var(--accent-color);
}

.char-count.warning {
  color: var(--warning);
}

.char-count.danger {
  color: var(--danger);
}

/* ============================================
   DIVIDER
   ============================================ */
.divider {
  display: flex;
  align-items: center;
  text-align: center;
  margin: 20px 0;
  color: var(--text-muted);
  font-size: 0.8rem;
}

.divider::before,
.divider::after {
  content: '';
  flex: 1;
  border-bottom: 2px solid var(--border-color);
}

.divider::before {
  margin-right: 12px;
}

.divider::after {
  margin-left: 12px;
}

/* ============================================
   LOADING BUTTON STATE
   ============================================ */
.btn.loading {
  position: relative;
  color: transparent;
  pointer-events: none;
}

.btn.loading::after {
  content: '';
  position: absolute;
  width: 18px;
  height: 18px;
  top: 50%;
  left: 50%;
  margin-left: -9px;
  margin-top: -9px;
  border: 3px solid rgba(255, 255, 255, 0.3);
  border-top-color: var(--white);
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

/* ============================================
   ERROR STATE FOR INPUTS
   ============================================ */
.form-input.error,
.form-textarea.error,
.form-select.error {
  border-color: var(--danger);
  background: #fff5f5;
}

.form-input.error:focus,
.form-textarea.error:focus,
.form-select.error:focus {
  box-shadow: 0 0 0 4px rgba(231, 76, 60, 0.1);
}

.form-input.success,
.form-textarea.success,
.form-select.success {
  border-color: var(--success);
  background: #f0fff4;
}

.form-input.success:focus,
.form-textarea.success:focus,
.form-select.success:focus {
  box-shadow: 0 0 0 4px rgba(46, 204, 113, 0.1);
}

.error-message {
  display: block;
  color: var(--danger);
  font-size: 0.8rem;
  margin-top: 5px;
  font-weight: 500;
}

.success-message {
  display: block;
  color: var(--success);
  font-size: 0.8rem;
  margin-top: 5px;
  font-weight: 500;
}

/* ============================================
   NOTIFICATION TOAST
   ============================================ */
.toast-container {
  position: fixed;
  top: 20px;
  right: 20px;
  z-index: 10000;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.toast {
  background: var(--white);
  border-left: 4px solid;
  border-radius: var(--border-radius);
  padding: 12px 16px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
  display: flex;
  align-items: center;
  gap: 10px;
  min-width: 280px;
  max-width: 360px;
  animation: toastSlideIn 0.3s ease-out;
}

@keyframes toastSlideIn {
  from {
    opacity: 0;
    transform: translateX(100%);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.toast.toast-success {
  border-left-color: var(--success);
}

.toast.toast-error {
  border-left-color: var(--danger);
}

.toast.toast-warning {
  border-left-color: var(--warning);
}

.toast.toast-info {
  border-left-color: var(--info);
}

.toast-icon {
  font-size: 1.3rem;
  flex-shrink: 0;
}

.toast-content {
  flex: 1;
}

.toast-title {
  font-weight: 700;
  margin-bottom: 2px;
  color: var(--text-dark);
  font-size: 0.9rem;
}

.toast-message {
  font-size: 0.85rem;
  color: var(--text-muted);
}

.toast-close {
  font-size: 1.1rem;
  cursor: pointer;
  color: var(--text-muted);
  transition: var(--transition);
  background: none;
  border: none;
  padding: 0;
  line-height: 1;
}

.toast-close:hover {
  color: var(--text-dark);
}

/* ============================================
   ANIMATION UTILITIES
   ============================================ */
@keyframes rainbow {
  0% { filter: hue-rotate(0deg); }
  100% { filter: hue-rotate(360deg); }
}

@keyframes toastSlideOut {
  from {
    opacity: 1;
    transform: translateX(0);
  }
  to {
    opacity: 0;
    transform: translateX(100%);
  }
}

/* ============================================
   RESPONSIVE DESIGN
   ============================================ */

/* Tablet & Small Laptops */
@media (max-width: 1024px) {
  .auth-container {
    grid-template-columns: 1fr;
    max-width: 550px;
    height: auto;
    max-height: 90vh;
  }
  
  .brand-section {
    display: none;
  }
  
  .form-scrollable-content {
    padding: 20px;
  }
}

/* Mobile Devices */
@media (max-width: 768px) {
  body {
    padding: 10px;
  }
  
  :root {
    --form-max-height: 90vh;
    --outline-width: 4px;
  }
  
  .auth-container {
    border-radius: 16px;
    max-width: 100%;
  }
  
  .form-scrollable-content {
    padding: 16px;
  }
  
  .form-sticky-header {
    padding: 10px 16px;
  }
  
  .header-logo {
    width: 38px;
    height: 38px;
  }
  
  .header-title {
    font-size: 1rem;
  }
  
  .header-subtitle {
    font-size: 0.65rem;
  }
  
  .header-badge {
    font-size: 0.65rem;
    padding: 5px 10px;
  }
  
  .form-title {
    font-size: 1.4rem;
  }
  
  .auth-tabs {
    flex-direction: column;
    gap: 8px;
  }
  
  .user-type-selector {
    grid-template-columns: 1fr;
  }
  
  .btn {
    padding: 11px 18px;
    font-size: 0.9rem;
  }
  
  .toast-container {
    top: 10px;
    right: 10px;
    left: 10px;
  }
  
  .toast {
    min-width: auto;
    max-width: 100%;
  }
}

/* Small Mobile */
@media (max-width: 480px) {
  :root {
    --outline-width: 3px;
  }
  
  .form-scrollable-content {
    padding: 12px;
  }
  
  .form-title {
    font-size: 1.2rem;
  }
  
  .form-input,
  .form-textarea {
    font-size: 0.85rem;
    padding: 10px 12px;
  }
  
  .form-input.with-icon {
    padding-left: 36px;
  }
}

/* ============================================
   PRINT STYLES
   ============================================ */
@media print {
  body {
    background: white;
  }
  
  body::before,
  body::after {
    display: none;
  }
  
  .auth-container {
    box-shadow: none;
    border: 1px solid #000;
  }
  
  .brand-section {
    display: none;
  }
  
  .form-section {
    grid-column: 1 / -1;
  }
  
  .btn,
  .tab-btn,
  .password-toggle {
    display: none;
  }
}

/* ============================================
   ACCESSIBILITY ENHANCEMENTS
   ============================================ */

/* Focus Visible for Keyboard Navigation */
*:focus-visible {
  outline: 3px solid var(--accent-color);
  outline-offset: 2px;
}

/* Screen Reader Only */
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
  .form-input,
  .form-textarea,
  .form-select {
    border-width: 3px;
  }
  
  .btn {
    border: 3px solid currentColor;
  }
  
  .auth-container {
    --outline-width: 8px;
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
  
  body::before {
    animation: none;
  }
  
  body::after {
    animation: none;
  }
  
  .auth-container {
    animation: none;
  }
}

/* ============================================
   UTILITY CLASSES
   ============================================ */
.text-center {
  text-align: center;
}

.d-none {
  display: none !important;
}

.d-block {
  display: block !important;
}

/* ============================================
   ADDITIONAL PATTERN VARIATIONS (Optional)
   ============================================ */

/* Alternative Pattern 1: Hexagon Grid */
body.pattern-hexagon::after {
  background-image: url("data:image/svg+xml,%3Csvg width='28' height='49' viewBox='0 0 28 49' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'%3E%3Cpath d='M13.99 9.25l13 7.5v15l-13 7.5L1 31.75v-15l12.99-7.5zM3 17.9v12.7l10.99 6.34 11-6.35V17.9l-11-6.34L3 17.9zM0 15l12.98-7.5V0h-2v6.35L0 12.69v2.3zm0 18.5L12.98 41v8h-2v-6.85L0 35.81v-2.3zM15 0v7.5L27.99 15H28v-2.31h-.01L17 6.35V0h-2zm0 49v-8l12.99-7.5H28v2.31h-.01L17 42.15V49h-2z'/%3E%3C/g%3E%3C/svg%3E");
}

/* Alternative Pattern 2: Circuit Board */
body.pattern-circuit::after {
  background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
}

/* Alternative Pattern 3: Diagonal Lines */
body.pattern-diagonal::after {
  background-image: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0 40L40 0H20L0 20M40 40V20L20 40' fill='%23ffffff' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
}

/* ============================================
   DARK MODE SUPPORT (Optional)
   ============================================ */
@media (prefers-color-scheme: dark) {
  /* Uncomment if you want to support dark mode */
  /*
  :root {
    --bg-card: #1a1a1a;
    --text-dark: #f8f9fa;
    --text-muted: #adb5bd;
    --bg-input: #2d2d2d;
    --border-color: #495057;
  }
  
  .form-section,
  .form-scrollable-content,
  .form-sticky-header {
    background: var(--bg-card);
    color: var(--text-dark);
  }
  
  .form-input,
  .form-textarea,
  .form-select {
    background: var(--bg-input);
    color: var(--text-dark);
  }
  */
}

/* ============================================
   CUSTOM SCROLLBAR FOR FIREFOX
   ============================================ */
* {
  scrollbar-width: thin;
  scrollbar-color: var(--accent-color) var(--light-gray);
}

/* ============================================
   PERFORMANCE OPTIMIZATIONS
   ============================================ */
.auth-container,
.brand-section,
.form-section {
  will-change: transform;
  transform: translateZ(0);
  backface-visibility: hidden;
}

/* ============================================
   END OF CSS - KAREK V5.0 FINAL
   ============================================ */
</style>

  <!-- ============================================
       LEFT SECTION - BRAND WITH REAL LOGO
       ============================================ -->
  <div class="brand-section">
    <div class="brand-content">
      <div class="feature-card" style="width: 120px; height: 120px; border-radius: 50%; margin: 0 auto 25px; padding: 20px; animation: float 3s ease-in-out infinite;">
        <img src="{{ asset('ui/img/rohtek1.png') }}" alt="PT Rohtek Amanah Global Logo" style="width: 100%; height: 100%; object-fit: contain;">
      </div>
      <h1 class="brand-title">KAREK</h1>
      <span class="brand-version">Version 5.0</span>
      <p class="brand-acronym">Key Analysis, Results, Execution, Knowledge</p>
      <p class="brand-subtitle">
        Professional Construction Management System - Secure Access Portal for PT Rohtek Amanah Global
      </p>
      
      <div class="brand-features">
        <div class="feature-card">
          <span class="feature-icon">🔑</span>
          <div class="feature-title">Key Analysis</div>
          <div class="feature-desc">Intelligent Data Processing</div>
        </div>
        <div class="feature-card">
          <span class="feature-icon">📊</span>
          <div class="feature-title">Results</div>
          <div class="feature-desc">Real-time Metrics</div>
        </div>
        <div class="feature-card">
          <span class="feature-icon">⚡</span>
          <div class="feature-title">Execution</div>
          <div class="feature-desc">Project Management</div>
        </div>
        <div class="feature-card">
          <span class="feature-icon">💡</span>
          <div class="feature-title">Knowledge</div>
          <div class="feature-desc">Learning System</div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- ============================================
       RIGHT SECTION - FORM AREA (CENTERED & SCROLLABLE)
       ============================================ -->
  <div class="form-section">
    <div class="form-wrapper">
      
      <!-- STICKY HEADER - PROFESSIONAL -->
      <div class="form-sticky-header">
        <div class="header-content">
          <div class="header-logo-section">
            <div class="header-logo">
              <img src="{{ asset('ui/img/rohtek1.png') }}" alt="KAREK Logo">
            </div>
            <div class="header-text">
              <div class="header-title">KAREK V5.0</div>
              <div class="header-subtitle">PT Rohtek Amanah Global</div>
            </div>
          </div>
          <div class="header-badge">
            <span>🔒</span>
            <span>Secure</span>
          </div>
        </div>
      </div>
      
      <!-- SCROLLABLE CONTENT AREA -->
      <div class="form-scrollable-content">
        
        <!-- Toggle Tabs -->
        <div class="auth-tabs">
          <button type="button" class="tab-btn active" data-tab="login" aria-label="Switch to Login">
            <span>🔐</span>
            <span>Login</span>
          </button>
          <button type="button" class="tab-btn" data-tab="register" aria-label="Switch to Request Access">
            <span>📱</span>
            <span>Request Access</span>
          </button>
        </div>
        
        <!-- ============================================
             LOGIN FORM CONTENT
             ============================================ -->
        <div class="form-content active" id="loginContent">
          <div class="form-header">
            <h2 class="form-title">Welcome Back</h2>
            <p class="form-subtitle">Enter your credentials to access your account</p>
          </div>
          
          <!-- Error Alert -->
          @if ($errors->has('email') || $errors->has('password'))
          <div class="alert alert-danger" role="alert">
            <span class="alert-icon">⚠️</span>
            <div>
              <strong>Login Failed:</strong> Invalid email or password.
            </div>
          </div>
          @endif
          
          @if (session('error'))
          <div class="alert alert-danger" role="alert">
            <span class="alert-icon">⚠️</span>
            <div>
              <strong>Error:</strong> {{ session('error') }}
            </div>
          </div>
          @endif
          
          @if (session('success'))
          <div class="alert alert-info" role="alert">
            <span class="alert-icon">✅</span>
            <div>
              <strong>Success:</strong> {{ session('success') }}
            </div>
          </div>
          @endif
          
          <!-- Login Form -->
          <form action="{{ route('login') }}" method="POST" id="loginForm" novalidate>
            @csrf
            
            <!-- Email Input -->
            <div class="form-group">
              <label for="email" class="form-label">
                Email Address <span class="required">*</span>
              </label>
              <div class="input-wrapper">
                <span class="input-icon">📧</span>
                <input 
                  type="email" 
                  id="email" 
                  name="email" 
                  class="form-input with-icon @error('email') error @enderror" 
                  placeholder="your.email@rohtekamanah.com"
                  value="{{ old('email') }}"
                  required
                  autocomplete="email"
                  autofocus
                >
              </div>
              @error('email')
              <span class="error-message">{{ $message }}</span>
              @enderror
            </div>
            
            <!-- Password Input -->
            <div class="form-group">
              <label for="password" class="form-label">
                Password <span class="required">*</span>
              </label>
              <div class="input-wrapper">
                <span class="input-icon">🔒</span>
                <input 
                  type="password" 
                  id="password" 
                  name="password" 
                  class="form-input with-icon @error('password') error @enderror" 
                  placeholder="Enter your password"
                  required
                  autocomplete="current-password"
                  style="padding-right: 40px;"
                >
                <span class="password-toggle" id="passwordToggle" role="button" tabindex="0" aria-label="Toggle password visibility">👁️</span>
              </div>
              @error('password')
              <span class="error-message">{{ $message }}</span>
              @enderror
            </div>
            
            <!-- Remember Me -->
            <div class="form-check">
              <input 
                type="checkbox" 
                id="remember" 
                name="remember" 
                class="form-check-input"
                {{ old('remember') ? 'checked' : '' }}
              >
              <label for="remember" class="form-check-label">
                Remember me on this device
              </label>
            </div>
            
            <!-- reCAPTCHA Placeholder -->
            <div class="recaptcha-wrapper">
              <div class="recaptcha-placeholder">
                🔐 reCAPTCHA verification<br>
                <small>(Configure in production)</small>
              </div>
            </div>
            
            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary" id="loginSubmitBtn">
              <span>🚀</span>
              <span>Secure Login</span>
            </button>
            
            <!-- Back Button -->
            <a href="{{ url('/') }}" class="btn btn-secondary">
              <span>←</span>
              <span>Back to Home</span>
            </a>
          </form>
          
          <!-- Divider -->
          <div class="divider">Need Access?</div>
          
          <!-- Quick Switch to Request Access -->
          <button type="button" class="btn btn-secondary" id="quickSwitchToRegister">
            <span>📱</span>
            <span>Request Access via WhatsApp</span>
          </button>
          
          <!-- Footer -->
          <div class="form-footer">
            <p>Developed by <strong>IT Developer Muhammad Raga Titipan</strong></p>
            <p>PT Rohtek Amanah Global | <strong>KAREK V5.0</strong></p>
            <p style="margin-top: 8px;">© {{ date('Y') }} All Rights Reserved</p>
          </div>
        </div>
        
        <!-- ============================================
             REQUEST ACCESS FORM (WhatsApp Registration)
             ============================================ -->
        <div class="form-content" id="registerContent">
          <div class="form-header">
            <h2 class="form-title">Request Access</h2>
            <p class="form-subtitle">Fill in your information to request access</p>
          </div>
          
          <!-- Info Alert -->
          <div class="alert alert-info" role="alert">
            <span class="alert-icon">ℹ️</span>
            <div>
              <strong>How it works:</strong> Fill the form and we'll generate a WhatsApp message to send to <strong>+62 821-2537-6083</strong>
            </div>
          </div>
          
          <!-- Request Access Form -->
          <form id="requestAccessForm" novalidate>
            
            <!-- User Type Selection -->
            <div class="form-group">
              <label class="form-label">
                Account Type <span class="required">*</span>
              </label>
              <div class="user-type-selector">
                <div class="user-type-option">
                  <input 
                    type="radio" 
                    id="typeClient" 
                    name="user_type" 
                    value="client" 
                    class="user-type-radio"
                    required
                    checked
                  >
                  <label for="typeClient" class="user-type-label">
                    <span class="user-type-icon">👤</span>
                    <div class="user-type-title">Client</div>
                    <div class="user-type-desc">External partner</div>
                  </label>
                </div>
                
                <div class="user-type-option">
                  <input 
                    type="radio" 
                    id="typeStaff" 
                    name="user_type" 
                    value="staff" 
                    class="user-type-radio"
                    required
                  >
                  <label for="typeStaff" class="user-type-label">
                    <span class="user-type-icon">👔</span>
                    <div class="user-type-title">Staff Internal</div>
                    <div class="user-type-desc">Company employee</div>
                  </label>
                </div>
              </div>
            </div>
            
            <!-- Full Name -->
            <div class="form-group">
              <label for="req_name" class="form-label">
                Full Name <span class="required">*</span>
              </label>
              <div class="input-wrapper">
                <span class="input-icon">👤</span>
                <input 
                  type="text" 
                  id="req_name" 
                  name="name" 
                  class="form-input with-icon" 
                  placeholder="Enter your full name"
                  required
                  minlength="3"
                  maxlength="100"
                  autocomplete="name"
                >
              </div>
              <span class="error-message" id="req_name_error" style="display: none;"></span>
            </div>
            
            <!-- Email -->
            <div class="form-group">
              <label for="req_email" class="form-label">
                Email Address <span class="required">*</span>
              </label>
              <div class="input-wrapper">
                <span class="input-icon">📧</span>
                <input 
                  type="email" 
                  id="req_email" 
                  name="email" 
                  class="form-input with-icon" 
                  placeholder="your.email@company.com"
                  required
                  autocomplete="email"
                >
              </div>
              <span class="error-message" id="req_email_error" style="display: none;"></span>
            </div>
            
            <!-- Position/Title -->
            <div class="form-group">
              <label for="req_position" class="form-label">
                Position/Title <span class="required">*</span>
              </label>
              <div class="input-wrapper">
                <span class="input-icon">💼</span>
                <input 
                  type="text" 
                  id="req_position" 
                  name="position" 
                  class="form-input with-icon" 
                  placeholder="e.g., Project Manager"
                  required
                  autocomplete="organization-title"
                >
              </div>
              <span class="error-message" id="req_position_error" style="display: none;"></span>
            </div>
            
            <!-- Department/Company -->
            <div class="form-group">
              <label for="req_department" class="form-label">
                <span id="departmentLabel">Company Name</span> <span class="required">*</span>
              </label>
              <div class="input-wrapper">
                <span class="input-icon">🏢</span>
                <input 
                  type="text" 
                  id="req_department" 
                  name="department" 
                  class="form-input with-icon" 
                  placeholder="Enter your company name"
                  required
                  autocomplete="organization"
                >
              </div>
              <span class="error-message" id="req_department_error" style="display: none;"></span>
            </div>
            
            <!-- Phone Number -->
            <div class="form-group">
              <label for="req_phone" class="form-label">
                Phone Number <span class="required">*</span>
              </label>
              <div class="input-wrapper">
                <span class="input-icon">📱</span>
                <input 
                  type="tel" 
                  id="req_phone" 
                  name="phone" 
                  class="form-input with-icon" 
                  placeholder="08xxxxxxxxxx"
                  required
                  pattern="[0-9]{10,15}"
                  autocomplete="tel"
                >
              </div>
              <span class="error-message" id="req_phone_error" style="display: none;"></span>
              <small style="color: var(--text-muted); font-size: 0.75rem; margin-top: 4px; display: block;">
                Format: 08xx-xxxx-xxxx (10-15 digits)
              </small>
            </div>
            
            <!-- Purpose/Reason -->
            <div class="form-group">
              <label for="req_reason" class="form-label">
                Purpose of Access <span class="required">*</span>
              </label>
              <textarea 
                id="req_reason" 
                name="reason" 
                class="form-textarea" 
                placeholder="Briefly explain why you need access..."
                required
                minlength="20"
                maxlength="500"
              ></textarea>
              <div class="char-counter">
                <span style="font-size: 0.75rem; color: var(--text-muted);">
                  Min 20 characters
                </span>
                <span>
                  <span id="charCounter" class="char-count">0</span>/500
                </span>
              </div>
              <span class="error-message" id="req_reason_error" style="display: none;"></span>
            </div>
            
            <!-- Generate WhatsApp Message Button -->
            <button type="button" class="btn btn-primary" id="generateMessageBtn">
              <span>✨</span>
              <span>Generate WhatsApp Message</span>
            </button>
            
            <!-- Back Button -->
            <button type="button" class="btn btn-secondary" id="backToLoginBtn">
              <span>←</span>
              <span>Back to Login</span>
            </button>
          </form>
          
          <!-- WhatsApp Message Preview -->
          <div class="whatsapp-preview" id="whatsappPreview" style="display: none;">
            <div class="whatsapp-preview-header">
              <span class="whatsapp-preview-icon">💬</span>
              <div>
                <div class="whatsapp-preview-title">WhatsApp Message Preview</div>
                <div style="font-size: 0.75rem; color: var(--text-muted);">Ready to send to +62 821-2537-6083</div>
              </div>
            </div>
            
            <div class="whatsapp-message-bubble" id="whatsappMessageContent">
              <!-- Message will be generated here -->
            </div>
            
            <button type="button" class="whatsapp-copy-btn" id="copyMessageBtn">
              <span>📋</span>
              <span>Copy Message</span>
            </button>
            
            <button type="button" class="btn btn-whatsapp" id="sendWhatsAppBtn" style="margin-top: 10px;">
              <span>📱</span>
              <span>Open WhatsApp & Send</span>
            </button>
            
            <div class="divider" style="margin-top: 16px;">Or</div>
            
            <button type="button" class="btn btn-secondary" id="editFormBtn" style="margin-top: 10px;">
              <span>✏️</span>
              <span>Edit Form</span>
            </button>
          </div>
          
          <!-- Footer -->
          <div class="form-footer">
            <p>Your request will be reviewed by our administrator</p>
            <p>Developed by <strong>IT Developer Muhammad Raga Titipan</strong></p>
            <p>PT Rohtek Amanah Global | <strong>KAREK V5.0</strong></p>
            <p style="margin-top: 8px;">© {{ date('Y') }} All Rights Reserved</p>
          </div>
        </div>
        
      </div>
      <!-- END SCROLLABLE CONTENT -->
      
    </div>
  </div>
</div>

<script>
  /**
 * ============================================
 * KAREK V5.0 - AUTHENTICATION SYSTEM (FIXED)
 * PT Rohtek Amanah Global
 * Developed by: IT Developer Muhammad Raga Titipan
 * ============================================
 */

(function() {
  'use strict';
  
  // ============================================
  // CONFIGURATION
  // ============================================
  const CONFIG = {
    WHATSAPP_ADMIN_NUMBER: '6282125376083',
    RECAPTCHA_ENABLED: false,
    AUTO_SAVE_INTERVAL: 2000,
    DRAFT_MAX_AGE: 24 * 60 * 60 * 1000,
    TOAST_DURATION: 5000,
    ANIMATION_DURATION: 300,
    DEBUG_MODE: false // Set true untuk debugging
  };
  
  // ============================================
  // DOM ELEMENTS
  // ============================================
  const elements = {
    tabButtons: document.querySelectorAll('.tab-btn'),
    loginContent: document.getElementById('loginContent'),
    registerContent: document.getElementById('registerContent'),
    loginForm: document.getElementById('loginForm'),
    requestAccessForm: document.getElementById('requestAccessForm'),
    loadingOverlay: document.getElementById('loadingOverlay'),
    emailInput: document.getElementById('email'),
    passwordInput: document.getElementById('password'),
    passwordToggle: document.getElementById('passwordToggle'),
    loginSubmitBtn: document.getElementById('loginSubmitBtn'),
    quickSwitchToRegister: document.getElementById('quickSwitchToRegister'),
    reqNameInput: document.getElementById('req_name'),
    reqEmailInput: document.getElementById('req_email'),
    reqPositionInput: document.getElementById('req_position'),
    reqDepartmentInput: document.getElementById('req_department'),
    reqPhoneInput: document.getElementById('req_phone'),
    reqReasonInput: document.getElementById('req_reason'),
    charCounter: document.getElementById('charCounter'),
    backToLoginBtn: document.getElementById('backToLoginBtn'),
    typeClientRadio: document.getElementById('typeClient'),
    typeStaffRadio: document.getElementById('typeStaff'),
    departmentLabel: document.getElementById('departmentLabel'),
    generateMessageBtn: document.getElementById('generateMessageBtn'),
    whatsappPreview: document.getElementById('whatsappPreview'),
    whatsappMessageContent: document.getElementById('whatsappMessageContent'),
    copyMessageBtn: document.getElementById('copyMessageBtn'),
    sendWhatsAppBtn: document.getElementById('sendWhatsAppBtn'),
    editFormBtn: document.getElementById('editFormBtn'),
    toastContainer: document.getElementById('toastContainer')
  };
  
  // ============================================
  // UTILITY FUNCTIONS
  // ============================================
  const Utils = {
    showLoading() {
      if (elements.loadingOverlay) {
        elements.loadingOverlay.classList.add('active');
      }
    },
    
    hideLoading() {
      if (elements.loadingOverlay) {
        elements.loadingOverlay.classList.remove('active');
      }
    },
    
    showToast(message, type = 'info', duration = CONFIG.TOAST_DURATION) {
      if (!elements.toastContainer) return;
      
      const icons = {
        success: '✅',
        error: '❌',
        warning: '⚠️',
        info: 'ℹ️'
      };
      
      const toast = document.createElement('div');
      toast.className = `toast toast-${type}`;
      toast.innerHTML = `
        <span class="toast-icon">${icons[type] || icons.info}</span>
        <div class="toast-content">
          <div class="toast-message">${message}</div>
        </div>
        <button class="toast-close" aria-label="Close">&times;</button>
      `;
      
      elements.toastContainer.appendChild(toast);
      
      const closeBtn = toast.querySelector('.toast-close');
      if (closeBtn) {
        closeBtn.addEventListener('click', () => {
          toast.remove();
        });
      }
      
      setTimeout(() => {
        if (toast.parentElement) {
          toast.style.animation = 'toastSlideOut 0.3s ease-out forwards';
          setTimeout(() => toast.remove(), 300);
        }
      }, duration);
    },
    
    isValidEmail(email) {
      const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return re.test(String(email).toLowerCase().trim());
    },
    
    isValidPhoneNumber(phone) {
      const cleaned = phone.replace(/\D/g, '');
      return cleaned.length >= 10 && cleaned.length <= 15 && cleaned.startsWith('0');
    },
    
    sanitizeInput(input) {
      return String(input).trim().replace(/[<>]/g, '');
    },
    
    getCurrentTimestamp() {
      return new Date().toLocaleString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    },
    
    async copyToClipboard(text) {
      if (navigator.clipboard && navigator.clipboard.writeText) {
        try {
          await navigator.clipboard.writeText(text);
          return true;
        } catch (err) {
          return this.fallbackCopyToClipboard(text);
        }
      } else {
        return this.fallbackCopyToClipboard(text);
      }
    },
    
    fallbackCopyToClipboard(text) {
      const textArea = document.createElement('textarea');
      textArea.value = text;
      textArea.style.position = 'fixed';
      textArea.style.top = '-9999px';
      textArea.style.left = '-9999px';
      document.body.appendChild(textArea);
      textArea.focus();
      textArea.select();
      
      try {
        const successful = document.execCommand('copy');
        document.body.removeChild(textArea);
        return successful;
      } catch (err) {
        document.body.removeChild(textArea);
        return false;
      }
    },
    
    scrollToElement(element, offset = 0) {
      if (!element) return;
      const elementPosition = element.getBoundingClientRect().top;
      const offsetPosition = elementPosition + window.pageYOffset - offset;
      
      window.scrollTo({
        top: offsetPosition,
        behavior: 'smooth'
      });
    }
  };
  
  // ============================================
  // TAB MANAGEMENT
  // ============================================
  const TabManager = {
    init() {
      if (elements.tabButtons) {
        elements.tabButtons.forEach(button => {
          button.addEventListener('click', (e) => this.switchTab(e.currentTarget));
        });
      }
      
      if (elements.backToLoginBtn) {
        elements.backToLoginBtn.addEventListener('click', () => {
          this.switchToLogin();
        });
      }
      
      if (elements.quickSwitchToRegister) {
        elements.quickSwitchToRegister.addEventListener('click', () => {
          this.switchToRegister();
        });
      }
    },
    
    switchTab(button) {
      const targetTab = button.getAttribute('data-tab');
      
      elements.tabButtons.forEach(btn => btn.classList.remove('active'));
      button.classList.add('active');
      
      if (targetTab === 'login') {
        this.showLogin();
      } else {
        this.showRegister();
      }
    },
    
    showLogin() {
      if (elements.loginContent) elements.loginContent.classList.add('active');
      if (elements.registerContent) elements.registerContent.classList.remove('active');
      setTimeout(() => {
        if (elements.emailInput) elements.emailInput.focus();
      }, CONFIG.ANIMATION_DURATION);
    },
    
    showRegister() {
      if (elements.registerContent) elements.registerContent.classList.add('active');
      if (elements.loginContent) elements.loginContent.classList.remove('active');
      setTimeout(() => {
        if (elements.reqNameInput) elements.reqNameInput.focus();
        DraftManager.loadDraft();
      }, CONFIG.ANIMATION_DURATION);
    },
    
    switchToLogin() {
      const loginTab = document.querySelector('[data-tab="login"]');
      if (loginTab) loginTab.click();
    },
    
    switchToRegister() {
      const registerTab = document.querySelector('[data-tab="register"]');
      if (registerTab) registerTab.click();
    }
  };
  
  // ============================================
  // LOGIN FORM HANDLER (FIXED)
  // ============================================
  const LoginFormHandler = {
    init() {
      if (!elements.loginForm) return;
      
      // Password toggle
      if (elements.passwordToggle) {
        elements.passwordToggle.addEventListener('click', () => this.togglePassword());
        elements.passwordToggle.addEventListener('keydown', (e) => {
          if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            this.togglePassword();
          }
        });
      }
      
      // Form submit handler - SIMPLIFIED
      elements.loginForm.addEventListener('submit', (e) => this.handleSubmit(e));
      
      // Visual validation only (tidak memblokir submit)
      if (elements.emailInput) {
        elements.emailInput.addEventListener('input', () => this.validateEmailVisual());
      }
      if (elements.passwordInput) {
        elements.passwordInput.addEventListener('input', () => this.validatePasswordVisual());
      }
    },
    
    togglePassword() {
      if (!elements.passwordInput || !elements.passwordToggle) return;
      
      if (elements.passwordInput.type === 'password') {
        elements.passwordInput.type = 'text';
        elements.passwordToggle.textContent = '🙈';
        elements.passwordToggle.setAttribute('aria-label', 'Hide password');
      } else {
        elements.passwordInput.type = 'password';
        elements.passwordToggle.textContent = '👁️';
        elements.passwordToggle.setAttribute('aria-label', 'Show password');
      }
    },
    
    validateEmailVisual() {
      if (!elements.emailInput) return;
      
      const email = elements.emailInput.value.trim();
      if (email && Utils.isValidEmail(email)) {
        elements.emailInput.classList.remove('error');
        elements.emailInput.classList.add('success');
      } else if (email) {
        elements.emailInput.classList.remove('success');
        elements.emailInput.classList.add('error');
      } else {
        elements.emailInput.classList.remove('error', 'success');
      }
    },
    
    validatePasswordVisual() {
      if (!elements.passwordInput) return;
      
      const password = elements.passwordInput.value;
      if (password) {
        elements.passwordInput.classList.remove('error');
        elements.passwordInput.classList.add('success');
      } else {
        elements.passwordInput.classList.remove('error', 'success');
      }
    },
    
    handleSubmit(e) {
      if (CONFIG.DEBUG_MODE) {
        console.log('🔍 LOGIN SUBMIT DEBUG:');
        console.log('Email:', elements.emailInput.value);
        console.log('Password length:', elements.passwordInput.value.length);
        console.log('Form action:', elements.loginForm.action);
      }
      
      const email = elements.emailInput.value.trim();
      const password = elements.passwordInput.value;
      
      // HANYA cek jika kosong
      if (!email || !password) {
        e.preventDefault();
        Utils.showToast('Email dan password harus diisi', 'error');
        
        if (!email && elements.emailInput) {
          elements.emailInput.classList.add('error');
          elements.emailInput.focus();
        }
        if (!password && elements.passwordInput) {
          elements.passwordInput.classList.add('error');
        }
        
        return false;
      }
      
      // TIDAK ADA VALIDASI FORMAT - Biarkan server yang handle!
      
      // reCAPTCHA check (jika enabled)
      if (CONFIG.RECAPTCHA_ENABLED && typeof grecaptcha !== 'undefined') {
        const recaptchaResponse = grecaptcha.getResponse();
        if (!recaptchaResponse) {
          e.preventDefault();
          Utils.showToast('Silakan selesaikan verifikasi reCAPTCHA', 'warning');
          return false;
        }
      }
      
      // Show loading
      Utils.showLoading();
      if (elements.loginSubmitBtn) {
        elements.loginSubmitBtn.classList.add('loading');
        elements.loginSubmitBtn.disabled = true;
      }
      
      // Auto-hide loading setelah 5 detik (fallback jika redirect gagal)
      setTimeout(() => {
        Utils.hideLoading();
        if (elements.loginSubmitBtn) {
          elements.loginSubmitBtn.classList.remove('loading');
          elements.loginSubmitBtn.disabled = false;
        }
      }, 5000);
      
      if (CONFIG.DEBUG_MODE) {
        console.log('✅ Form akan di-submit ke server');
      }
      
      // BIARKAN FORM SUBMIT NORMAL!
      return true;
    }
  };
  
  // ============================================
  // REQUEST ACCESS FORM HANDLER
  // ============================================
  const RequestAccessFormHandler = {
    init() {
      if (!elements.requestAccessForm) return;
      
      if (elements.typeClientRadio) {
        elements.typeClientRadio.addEventListener('change', () => this.updateDepartmentLabel());
      }
      if (elements.typeStaffRadio) {
        elements.typeStaffRadio.addEventListener('change', () => this.updateDepartmentLabel());
      }
      if (elements.reqReasonInput) {
        elements.reqReasonInput.addEventListener('input', () => this.updateCharCounter());
      }
      if (elements.reqPhoneInput) {
        elements.reqPhoneInput.addEventListener('input', () => this.formatPhoneInput());
      }
      
      // Validation on blur
      if (elements.reqNameInput) {
        elements.reqNameInput.addEventListener('blur', () => this.validateName());
      }
      if (elements.reqEmailInput) {
        elements.reqEmailInput.addEventListener('blur', () => this.validateEmail());
      }
      if (elements.reqPositionInput) {
        elements.reqPositionInput.addEventListener('blur', () => this.validatePosition());
      }
      if (elements.reqDepartmentInput) {
        elements.reqDepartmentInput.addEventListener('blur', () => this.validateDepartment());
      }
      if (elements.reqPhoneInput) {
        elements.reqPhoneInput.addEventListener('blur', () => this.validatePhone());
      }
      if (elements.reqReasonInput) {
        elements.reqReasonInput.addEventListener('blur', () => this.validateReason());
      }
      
      // Button handlers
      if (elements.generateMessageBtn) {
        elements.generateMessageBtn.addEventListener('click', () => this.generateMessage());
      }
      if (elements.copyMessageBtn) {
        elements.copyMessageBtn.addEventListener('click', () => this.copyMessage());
      }
      if (elements.sendWhatsAppBtn) {
        elements.sendWhatsAppBtn.addEventListener('click', () => this.sendWhatsApp());
      }
      if (elements.editFormBtn) {
        elements.editFormBtn.addEventListener('click', () => this.editForm());
      }
      
      this.initAutoSave();
    },
    
    updateDepartmentLabel() {
      if (!elements.departmentLabel || !elements.reqDepartmentInput) return;
      
      if (elements.typeClientRadio && elements.typeClientRadio.checked) {
        elements.departmentLabel.textContent = 'Company Name';
        elements.reqDepartmentInput.placeholder = 'Enter your company name';
      } else if (elements.typeStaffRadio && elements.typeStaffRadio.checked) {
        elements.departmentLabel.textContent = 'Department';
        elements.reqDepartmentInput.placeholder = 'e.g., IT, Finance, Operations';
      }
    },
    
    updateCharCounter() {
      if (!elements.reqReasonInput || !elements.charCounter) return;
      
      const length = elements.reqReasonInput.value.length;
      elements.charCounter.textContent = length;
      
      if (length > 500) {
        elements.charCounter.classList.add('danger');
        elements.charCounter.classList.remove('warning');
      } else if (length > 450) {
        elements.charCounter.classList.add('warning');
        elements.charCounter.classList.remove('danger');
      } else {
        elements.charCounter.classList.remove('warning', 'danger');
      }
    },
    
    formatPhoneInput() {
      if (!elements.reqPhoneInput) return;
      
      let value = elements.reqPhoneInput.value;
      value = value.replace(/\D/g, '');
      
      if (value.startsWith('8') && value.length > 0) {
        value = '0' + value;
      }
      
      if (value.length > 15) {
        value = value.slice(0, 15);
      }
      
      elements.reqPhoneInput.value = value;
    },
    
    validateName() {
      if (!elements.reqNameInput) return true;
      
      const name = elements.reqNameInput.value.trim();
      const errorEl = document.getElementById('req_name_error');
      
      if (!name) {
        elements.reqNameInput.classList.add('error');
        if (errorEl) {
          errorEl.textContent = 'Name is required';
          errorEl.style.display = 'block';
        }
        return false;
      } else if (name.length < 3) {
        elements.reqNameInput.classList.add('error');
        if (errorEl) {
          errorEl.textContent = 'Name must be at least 3 characters';
          errorEl.style.display = 'block';
        }
        return false;
      } else {
        elements.reqNameInput.classList.remove('error');
        elements.reqNameInput.classList.add('success');
        if (errorEl) errorEl.style.display = 'none';
        return true;
      }
    },
    
    validateEmail() {
      if (!elements.reqEmailInput) return true;
      
      const email = elements.reqEmailInput.value.trim();
      const errorEl = document.getElementById('req_email_error');
      
      if (!email) {
        elements.reqEmailInput.classList.add('error');
        if (errorEl) {
          errorEl.textContent = 'Email is required';
          errorEl.style.display = 'block';
        }
        return false;
      } else if (!Utils.isValidEmail(email)) {
        elements.reqEmailInput.classList.add('error');
        if (errorEl) {
          errorEl.textContent = 'Please enter a valid email address';
          errorEl.style.display = 'block';
        }
        return false;
      } else {
        elements.reqEmailInput.classList.remove('error');
        elements.reqEmailInput.classList.add('success');
        if (errorEl) errorEl.style.display = 'none';
        return true;
      }
    },
    
    validatePosition() {
      if (!elements.reqPositionInput) return true;
      
      const position = elements.reqPositionInput.value.trim();
      const errorEl = document.getElementById('req_position_error');
      
      if (!position) {
        elements.reqPositionInput.classList.add('error');
        if (errorEl) {
          errorEl.textContent = 'Position is required';
          errorEl.style.display = 'block';
        }
        return false;
      } else {
        elements.reqPositionInput.classList.remove('error');
        elements.reqPositionInput.classList.add('success');
        if (errorEl) errorEl.style.display = 'none';
        return true;
      }
    },
    
    validateDepartment() {
      if (!elements.reqDepartmentInput) return true;
      
      const department = elements.reqDepartmentInput.value.trim();
      const errorEl = document.getElementById('req_department_error');
      
      if (!department) {
        elements.reqDepartmentInput.classList.add('error');
        if (errorEl) {
          errorEl.textContent = 'This field is required';
          errorEl.style.display = 'block';
        }
        return false;
      } else {
        elements.reqDepartmentInput.classList.remove('error');
        elements.reqDepartmentInput.classList.add('success');
        if (errorEl) errorEl.style.display = 'none';
        return true;
      }
    },
    
    validatePhone() {
      if (!elements.reqPhoneInput) return true;
      
      const phone = elements.reqPhoneInput.value.trim();
      const errorEl = document.getElementById('req_phone_error');
      
      if (!phone) {
        elements.reqPhoneInput.classList.add('error');
        if (errorEl) {
          errorEl.textContent = 'Phone number is required';
          errorEl.style.display = 'block';
        }
        return false;
      } else if (!Utils.isValidPhoneNumber(phone)) {
        elements.reqPhoneInput.classList.add('error');
        if (errorEl) {
          errorEl.textContent = 'Please enter a valid phone number (10-15 digits)';
          errorEl.style.display = 'block';
        }
        return false;
      } else {
        elements.reqPhoneInput.classList.remove('error');
        elements.reqPhoneInput.classList.add('success');
        if (errorEl) errorEl.style.display = 'none';
        return true;
      }
    },
    
    validateReason() {
      if (!elements.reqReasonInput) return true;
      
      const reason = elements.reqReasonInput.value.trim();
      const errorEl = document.getElementById('req_reason_error');
      
      if (!reason) {
        elements.reqReasonInput.classList.add('error');
        if (errorEl) {
          errorEl.textContent = 'Purpose of access is required';
          errorEl.style.display = 'block';
        }
        return false;
      } else if (reason.length < 20) {
        elements.reqReasonInput.classList.add('error');
        if (errorEl) {
          errorEl.textContent = 'Please provide at least 20 characters';
          errorEl.style.display = 'block';
        }
        return false;
      } else if (reason.length > 500) {
        elements.reqReasonInput.classList.add('error');
        if (errorEl) {
          errorEl.textContent = 'Maximum 500 characters allowed';
          errorEl.style.display = 'block';
        }
        return false;
      } else {
        elements.reqReasonInput.classList.remove('error');
        elements.reqReasonInput.classList.add('success');
        if (errorEl) errorEl.style.display = 'none';
        return true;
      }
    },
    
    validateForm() {
      const validations = [
        this.validateName(),
        this.validateEmail(),
        this.validatePosition(),
        this.validateDepartment(),
        this.validatePhone(),
        this.validateReason()
      ];
      
      return validations.every(v => v === true);
    },
    
    generateMessage() {
      if (!this.validateForm()) {
        Utils.showToast('Please fill all required fields correctly', 'error');
        
        const errorField = elements.requestAccessForm.querySelector('.error');
        if (errorField) {
          errorField.focus();
          errorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        return;
      }
      
      const userType = document.querySelector('input[name="user_type"]:checked')?.value || 'client';
      const name = Utils.sanitizeInput(elements.reqNameInput.value);
      const email = Utils.sanitizeInput(elements.reqEmailInput.value);
      const position = Utils.sanitizeInput(elements.reqPositionInput.value);
      const department = Utils.sanitizeInput(elements.reqDepartmentInput.value);
      const phone = elements.reqPhoneInput.value.trim();
      const reason = Utils.sanitizeInput(elements.reqReasonInput.value);
      
      const message = this.createWhatsAppMessage(userType, name, email, position, department, phone, reason);
      
      if (elements.whatsappMessageContent) {
        elements.whatsappMessageContent.textContent = message;
      }
      if (elements.whatsappPreview) {
        elements.whatsappPreview.style.display = 'block';
        elements.whatsappPreview.dataset.message = message;
        
        setTimeout(() => {
          elements.whatsappPreview.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }, 100);
      }
      
      if (elements.generateMessageBtn) {
        const originalHTML = elements.generateMessageBtn.innerHTML;
        elements.generateMessageBtn.innerHTML = '<span>✅</span><span>Message Generated!</span>';
        elements.generateMessageBtn.style.background = 'linear-gradient(135deg, #2ecc71 0%, #27ae60 100%)';
        
        setTimeout(() => {
          elements.generateMessageBtn.innerHTML = originalHTML;
          elements.generateMessageBtn.style.background = '';
        }, 2000);
      }
      
      Utils.showToast('WhatsApp message generated successfully!', 'success');
      
      setTimeout(() => {
        DraftManager.clearDraft();
      }, 1000);
    },
    
    createWhatsAppMessage(userType, name, email, position, department, phone, reason) {
      const timestamp = Utils.getCurrentTimestamp();
      const userTypeLabel = userType === 'client' ? '👤 Client' : '👔 Staff Internal';
      const departmentLabel = userType === 'client' ? 'Company' : 'Department';
      
      // ✅ Gunakan emoji yang aman untuk WhatsApp
      let message = `🏗 *KAREK V5.0 - REQUEST ACCESS*\n`;
      message += `================================\n\n`;
      message += `Halo Admin PT Rohtek Amanah Global,\n\n`;
      message += `Saya ingin mengajukan permintaan akses ke sistem KAREK V5.0 dengan detail sebagai berikut:\n\n`;
      message += `📋 *INFORMASI PEMOHON*\n`;
      message += `================================\n`;
      message += `👤 Nama Lengkap: *${name}*\n`;
      message += `📧 Email: ${email}\n`;
      message += `💼 Posisi/Jabatan: ${position}\n`;
      message += `🏢 ${departmentLabel}: ${department}\n`;
      message += `📱 No. Telepon: ${phone}\n`;
      message += `🔖 Tipe Akun: ${userTypeLabel}\n\n`;
      message += `📝 *TUJUAN AKSES*\n`;
      message += `================================\n`;
      message += `${reason}\n\n`;
      message += `⏰ *WAKTU PENGAJUAN*\n`;
      message += `================================\n`;
      message += `${timestamp}\n\n`;
      message += `================================\n`;
      message += `Mohon untuk dapat diproses lebih lanjut.\n`;
      message += `Terima kasih atas perhatiannya.\n\n`;
      message += `_Pesan ini digenerate otomatis dari KAREK V5.0 Authentication System_\n`;
      message += `_Developed by IT Developer Muhammad Raga Titipan_`;
      
      return message;
    },

    async copyMessage() {
      if (!elements.whatsappPreview) return;
      
      const message = elements.whatsappPreview.dataset.message;
      
      if (!message) {
        Utils.showToast('No message to copy', 'error');
        return;
      }
      
      const success = await Utils.copyToClipboard(message);
      
      if (elements.copyMessageBtn) {
        const originalHTML = elements.copyMessageBtn.innerHTML;
        
        if (success) {
          elements.copyMessageBtn.innerHTML = '<span>✅</span><span>Copied!</span>';
          elements.copyMessageBtn.style.background = '#2ecc71';
          elements.copyMessageBtn.style.color = '#fff';
          Utils.showToast('Message copied to clipboard!', 'success');
        } else {
          elements.copyMessageBtn.innerHTML = '<span>❌</span><span>Failed to Copy</span>';
          elements.copyMessageBtn.style.background = '#e74c3c';
          elements.copyMessageBtn.style.color = '#fff';
          Utils.showToast('Failed to copy message', 'error');
        }
        
        setTimeout(() => {
          elements.copyMessageBtn.innerHTML = originalHTML;
          elements.copyMessageBtn.style.background = '';
          elements.copyMessageBtn.style.color = '';
        }, 2000);
      }
    },

    sendWhatsApp() {
      if (!elements.whatsappPreview) return;
      
      const message = elements.whatsappPreview.dataset.message;
      
      if (!message) {
        Utils.showToast('No message to send', 'error');
        return;
      }
      
      // ✅ PERBAIKAN 1: Encode message dengan benar
      const encodedMessage = encodeURIComponent(message);
      
      // ✅ PERBAIKAN 2: Deteksi device dengan lebih akurat
      const userAgent = navigator.userAgent.toLowerCase();
      const isAndroid = /android/i.test(userAgent);
      const isIOS = /iphone|ipad|ipod/i.test(userAgent);
      const isMobile = isAndroid || isIOS || /mobile/i.test(userAgent);
      const isDesktop = !isMobile;
      
      let whatsappURL;
      let shouldUseDirectLink = false;
      
      // ✅ PERBAIKAN 3: Gunakan wa.me untuk semua platform (PALING RELIABLE)
      if (isAndroid) {
        // Android: Coba app dulu, fallback ke wa.me
        whatsappURL = `whatsapp://send?phone=${CONFIG.WHATSAPP_ADMIN_NUMBER}&text=${encodedMessage}`;
        shouldUseDirectLink = true;
      } else if (isIOS) {
        // iOS: Gunakan whatsapp:// protocol
        whatsappURL = `whatsapp://send?phone=${CONFIG.WHATSAPP_ADMIN_NUMBER}&text=${encodedMessage}`;
        shouldUseDirectLink = true;
      } else {
        // Desktop & fallback: Gunakan wa.me (BUKAN web.whatsapp.com)
        whatsappURL = `https://wa.me/${CONFIG.WHATSAPP_ADMIN_NUMBER}?text=${encodedMessage}`;
        shouldUseDirectLink = false;
      }
      
      // ✅ PERBAIKAN 4: Buka dengan cara yang berbeda untuk mobile vs desktop
      if (isMobile && shouldUseDirectLink) {
        // Mobile: Coba buka app, jika gagal fallback ke wa.me
        window.location.href = whatsappURL;
        
        // Fallback ke wa.me setelah 2 detik jika app tidak terbuka
        setTimeout(() => {
          const fallbackURL = `https://wa.me/${CONFIG.WHATSAPP_ADMIN_NUMBER}?text=${encodedMessage}`;
          
          // Cek apakah user masih di halaman (app tidak terbuka)
          if (document.hasFocus()) {
            window.location.href = fallbackURL;
          }
        }, 2000);
        
      } else {
        // Desktop: Buka di tab baru dengan wa.me
        const newWindow = window.open(whatsappURL, '_blank', 'noopener,noreferrer');
        
        // ✅ PERBAIKAN 5: Fallback jika popup diblokir
        setTimeout(() => {
          if (!newWindow || newWindow.closed || typeof newWindow.closed === 'undefined') {
            // Popup diblokir, tanya user
            if (confirm('⚠️ Pop-up diblokir!\n\nKlik OK untuk membuka WhatsApp di tab ini.')) {
              window.location.href = whatsappURL;
            } else {
              // Copy link sebagai alternatif
              Utils.copyToClipboard(whatsappURL);
              Utils.showToast('Link WhatsApp telah di-copy! Paste di browser.', 'warning', 8000);
            }
          }
        }, 1000);
      }
      
      // ✅ Update button state
      if (elements.sendWhatsAppBtn) {
        const originalHTML = elements.sendWhatsAppBtn.innerHTML;
        elements.sendWhatsAppBtn.innerHTML = '<span>✅</span><span>Membuka WhatsApp...</span>';
        elements.sendWhatsAppBtn.style.background = 'linear-gradient(135deg, #2ecc71 0%, #27ae60 100%)';
        elements.sendWhatsAppBtn.disabled = true;
        
        setTimeout(() => {
          elements.sendWhatsAppBtn.innerHTML = originalHTML;
          elements.sendWhatsAppBtn.style.background = '';
          elements.sendWhatsAppBtn.disabled = false;
        }, 3000);
      }
      
      Utils.showToast('📱 Membuka WhatsApp...', 'success');
      
      // ✅ Debug logging
      if (CONFIG.DEBUG_MODE) {
        console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        console.log('📱 WHATSAPP DEBUG:');
        console.log('Device:', isAndroid ? 'Android' : isIOS ? 'iOS' : isDesktop ? 'Desktop' : 'Unknown');
        console.log('URL:', whatsappURL);
        console.log('Method:', isMobile ? 'window.location.href' : 'window.open');
        console.log('Message Length:', message.length);
        console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
      }
    },

    editForm() {
      if (elements.whatsappPreview) {
        elements.whatsappPreview.style.display = 'none';
      }
      if (elements.reqNameInput) {
        elements.reqNameInput.focus();
        elements.reqNameInput.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
      Utils.showToast('✏️ Anda dapat mengedit form sekarang', 'info');
    },

    initAutoSave() {
      if (!elements.requestAccessForm) return;
      
      let autoSaveTimeout;
      const inputs = elements.requestAccessForm.querySelectorAll('input, textarea');
      
      inputs.forEach(input => {
        input.addEventListener('input', () => {
          clearTimeout(autoSaveTimeout);
          autoSaveTimeout = setTimeout(() => {
            DraftManager.saveDraft();
          }, CONFIG.AUTO_SAVE_INTERVAL);
        });
      });
    }
  };
  
  // ============================================
  // DRAFT MANAGER
  // ============================================
  const DraftManager = {
    STORAGE_KEY: 'karek_request_draft',
    
    saveDraft() {
      if (!elements.requestAccessForm) return;
      
      const draft = {
        user_type: document.querySelector('input[name="user_type"]:checked')?.value || '',
        name: elements.reqNameInput?.value || '',
        email: elements.reqEmailInput?.value || '',
        position: elements.reqPositionInput?.value || '',
        department: elements.reqDepartmentInput?.value || '',
        phone: elements.reqPhoneInput?.value || '',
        reason: elements.reqReasonInput?.value || '',
        timestamp: Date.now()
      };
      
      try {
        localStorage.setItem(this.STORAGE_KEY, JSON.stringify(draft));
        if (CONFIG.DEBUG_MODE) {
          console.log('💾 Draft auto-saved');
        }
      } catch (e) {
        console.error('Failed to save draft:', e);
      }
    },
    
    loadDraft() {
      try {
        const draft = localStorage.getItem(this.STORAGE_KEY);
        if (!draft) return;
        
        const data = JSON.parse(draft);
        const now = Date.now();
        
        if ((now - data.timestamp) < CONFIG.DRAFT_MAX_AGE) {
          if (confirm('📋 Found unsaved draft from previous session. Would you like to load it?')) {
            if (data.user_type === 'client' && elements.typeClientRadio) {
              elements.typeClientRadio.checked = true;
            } else if (data.user_type === 'staff' && elements.typeStaffRadio) {
              elements.typeStaffRadio.checked = true;
            }
            RequestAccessFormHandler.updateDepartmentLabel();
            
            if (elements.reqNameInput) elements.reqNameInput.value = data.name || '';
            if (elements.reqEmailInput) elements.reqEmailInput.value = data.email || '';
            if (elements.reqPositionInput) elements.reqPositionInput.value = data.position || '';
            if (elements.reqDepartmentInput) elements.reqDepartmentInput.value = data.department || '';
            if (elements.reqPhoneInput) elements.reqPhoneInput.value = data.phone || '';
            if (elements.reqReasonInput) elements.reqReasonInput.value = data.reason || '';
            
            RequestAccessFormHandler.updateCharCounter();
            
            Utils.showToast('Draft loaded successfully!', 'success');
            if (CONFIG.DEBUG_MODE) {
              console.log('✅ Draft loaded');
            }
          } else {
            this.clearDraft();
          }
        } else {
          this.clearDraft();
        }
      } catch (e) {
        console.error('Error loading draft:', e);
        this.clearDraft();
      }
    },
    
    clearDraft() {
      try {
        localStorage.removeItem(this.STORAGE_KEY);
        if (CONFIG.DEBUG_MODE) {
          console.log('🗑️ Draft cleared');
        }
      } catch (e) {
        console.error('Failed to clear draft:', e);
      }
    }
  };
  
  // ============================================
  // KEYBOARD SHORTCUTS
  // ============================================
  const KeyboardShortcuts = {
    init() {
      document.addEventListener('keydown', (e) => this.handleKeydown(e));
    },
    
    handleKeydown(e) {
      // Ctrl/Cmd + Enter: Submit form
      if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
        if (elements.loginContent && elements.loginContent.classList.contains('active')) {
          e.preventDefault();
          if (elements.loginForm) {
            elements.loginForm.dispatchEvent(new Event('submit', { cancelable: true }));
          }
        } else if (elements.registerContent && elements.registerContent.classList.contains('active')) {
          e.preventDefault();
          if (elements.generateMessageBtn) {
            elements.generateMessageBtn.click();
          }
        }
      }
      
      // Alt + L: Switch to Login
      if (e.altKey && e.key === 'l') {
        e.preventDefault();
        TabManager.switchToLogin();
      }
      
      // Alt + R: Switch to Register
      if (e.altKey && e.key === 'r') {
        e.preventDefault();
        TabManager.switchToRegister();
      }
      
      // Escape: Clear/Close
      if (e.key === 'Escape') {
        if (elements.loginContent && elements.loginContent.classList.contains('active')) {
          if (confirm('Clear login form?')) {
            if (elements.emailInput) elements.emailInput.value = '';
            if (elements.passwordInput) elements.passwordInput.value = '';
            if (elements.emailInput) elements.emailInput.focus();
          }
        } else if (elements.registerContent && elements.registerContent.classList.contains('active')) {
          if (elements.whatsappPreview && elements.whatsappPreview.style.display === 'block') {
            elements.whatsappPreview.style.display = 'none';
          } else if (confirm('Clear all form data?')) {
            if (elements.requestAccessForm) {
              elements.requestAccessForm.reset();
            }
            RequestAccessFormHandler.updateCharCounter();
            RequestAccessFormHandler.updateDepartmentLabel();
            DraftManager.clearDraft();
          }
        }
      }
    }
  };
  
  // ============================================
  // CONSOLE BRANDING
  // ============================================
  const ConsoleBranding = {
    init() {
      console.log('%c🏗️ KAREK V5.0', 'color: #213823; font-size: 32px; font-weight: bold;');
      console.log('%cKey Analysis, Results, Execution, Knowledge', 'color: #375534; font-size: 16px; font-style: italic;');
      console.log('%c━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━', 'color: #6B9071;');
      console.log('%c🔑 Key Analysis - Intelligent Data Processing', 'color: #8FB996; font-size: 12px;');
      console.log('%c📊 Results - Real-time Performance Metrics', 'color: #8FB996; font-size: 12px;');
      console.log('%c⚡ Execution - Seamless Project Management', 'color: #8FB996; font-size: 12px;');
      console.log('%c💡 Knowledge - Advanced Learning System', 'color: #8FB996; font-size: 12px;');
      console.log('%c━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━', 'color: #6B9071;');
      console.log('%cDeveloped by IT Developer Muhammad Raga Titipan', 'color: #95a5a6; font-size: 11px;');
      console.log('%cPT Rohtek Amanah Global', 'color: #95a5a6; font-size: 11px;');
      console.log('%c⚠️ Unauthorized access is prohibited!', 'color: #e74c3c; font-size: 13px; font-weight: bold;');
      console.log('%c✅ KAREK V5.0 Authentication System Initialized', 'color: #6B9071; font-size: 14px; font-weight: bold;');
      console.log('%c⏰ ' + new Date().toLocaleString('id-ID'), 'color: #95a5a6; font-size: 11px;');
      console.log('%c📱 WhatsApp Admin: +62 821-2537-6083', 'color: #25D366; font-size: 12px; font-weight: bold;');
    }
  };
  
  // ============================================
  // INITIALIZATION
  // ============================================
  function init() {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', initializeApp);
    } else {
      initializeApp();
    }
  }
  
  function initializeApp() {
    try {
      // Hide loading overlay immediately on page load
      Utils.hideLoading();
      
      // Initialize all modules
      ConsoleBranding.init();
      TabManager.init();
      LoginFormHandler.init();
      RequestAccessFormHandler.init();
      KeyboardShortcuts.init();
      
      // Auto-focus email input after animation
      setTimeout(() => {
        if (elements.loginContent && elements.loginContent.classList.contains('active')) {
          if (elements.emailInput) {
            elements.emailInput.focus();
          }
        }
      }, CONFIG.ANIMATION_DURATION);
      
      // Update department label on init
      RequestAccessFormHandler.updateDepartmentLabel();
      
      if (CONFIG.DEBUG_MODE) {
        console.log('%c✅ All modules initialized successfully', 'color: #2ecc71; font-size: 12px; font-weight: bold;');
        console.log('Elements:', elements);
      } else {
        console.log('%c✅ All modules initialized successfully', 'color: #2ecc71; font-size: 12px; font-weight: bold;');
      }
      
    } catch (error) {
      console.error('%c❌ Initialization Error:', 'color: #e74c3c; font-size: 12px; font-weight: bold;', error);
      Utils.showToast('An error occurred during initialization. Please refresh the page.', 'error');
    }
  }
  
  // ============================================
  // EXPOSE UTILITIES TO GLOBAL SCOPE
  // ============================================
  window.KAREKUtils = Utils;
  window.KAREKConfig = CONFIG;
  
  // ============================================
  // START APPLICATION
  // ============================================
  init();
  
})();
</script>

@endsection

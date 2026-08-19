@extends('layouts.pembeli')

@section('title', 'Dashboard Pembeli - Karyaku')

@push('styles')
<style>
    /* ==========================================================================
       1. ROOT VARIABLES & THEME SETUP
       ========================================================================== */
    :root {
        /* Primary Colors */
        --primary-50: #eff6ff;
        --primary-100: #dbeafe;
        --primary-200: #bfdbfe;
        --primary-300: #93c5fd;
        --primary-400: #60a5fa;
        --primary-500: #3b82f6;
        --primary-600: #2563eb;
        --primary-700: #1d4ed8;
        --primary-800: #1e40af;
        --primary-900: #1e3a8a;
        
        /* Accent Colors (Coral/Orange) */
        --accent-50: #fff1f2;
        --accent-100: #ffe4e6;
        --accent-500: #f43f5e;
        --accent-600: #e11d48;
        --coral: #ff7a59;
        --coral-dark: #f0623f;
        --coral-light: #ffebd2;

        /* Success & Warning */
        --success-light: #d1fae5;
        --success: #10b981;
        --success-dark: #047857;
        --warning-light: #fef3c7;
        --warning: #f59e0b;
        --warning-dark: #b45309;

        /* Grayscale */
        --gray-50: #f8fafc;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-300: #cbd5e1;
        --gray-400: #94a3b8;
        --gray-500: #64748b;
        --gray-600: #475569;
        --gray-700: #334155;
        --gray-800: #1e293b;
        --gray-900: #0f172a;

        /* Layout */
        --border-radius-sm: 8px;
        --border-radius-md: 12px;
        --border-radius-lg: 16px;
        --border-radius-xl: 24px;
        
        /* Shadows */
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        --shadow-hover: 0 12px 30px rgba(15, 23, 42, 0.12);
        --shadow-glow: 0 0 20px rgba(37, 99, 235, 0.3);

        /* Transitions */
        --transition-fast: 0.15s ease;
        --transition-normal: 0.3s ease;
        --transition-slow: 0.5s ease;
    }

    /* ==========================================================================
       2. GLOBAL RESET & UTILITIES
       ========================================================================== */
    html {
        scroll-behavior: smooth;
    }

    body {
        background-color: var(--gray-50);
        color: var(--gray-800);
        font-family: 'Poppins', sans-serif;
        overflow-x: hidden;
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    ::-webkit-scrollbar-track {
        background: var(--gray-100); 
    }
    ::-webkit-scrollbar-thumb {
        background: var(--gray-300); 
        border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: var(--gray-400); 
    }

    /* Text Gradients */
    .text-gradient-primary {
        background: linear-gradient(135deg, var(--primary-600), var(--primary-400));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .text-gradient-coral {
        background: linear-gradient(135deg, var(--coral), #fbbf24);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Animations */
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-5px); }
        100% { transform: translateY(0px); }
    }

    @keyframes pulse-soft {
        0% { transform: scale(1); box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.4); }
        70% { transform: scale(1.02); box-shadow: 0 0 0 10px rgba(37, 99, 235, 0); }
        100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(37, 99, 235, 0); }
    }

    @keyframes slideInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-slide-up {
        animation: slideInUp 0.6s ease-out forwards;
    }

    .delay-100 { animation-delay: 100ms; }
    .delay-200 { animation-delay: 200ms; }
    .delay-300 { animation-delay: 300ms; }

    /* ==========================================================================
       3. DASHBOARD TOP HEADER (GREETING & PROGRESS)
       ========================================================================== */
    .dashboard-top-wrapper {
        margin-top: 20px;
        margin-bottom: 30px;
        position: relative;
    }

    .welcome-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 25px;
        background: white;
        padding: 24px;
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--gray-200);
        position: relative;
        overflow: hidden;
    }

    /* Decorative background for welcome */
    .welcome-header::after {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, var(--primary-100) 0%, transparent 70%);
        opacity: 0.6;
        z-radius: 50%;
        pointer-events: none;
    }

    .welcome-text h2 {
        font-size: 26px;
        font-weight: 800;
        color: var(--gray-900);
        margin: 0 0 8px;
        letter-spacing: -0.5px;
    }

    .welcome-text p {
        margin: 0;
        color: var(--gray-500);
        font-size: 14px;
        line-height: 1.6;
        max-width: 600px;
    }

    .profile-completion {
        margin-top: 15px;
        display: flex;
        align-items: center;
        gap: 15px;
        background: var(--gray-50);
        padding: 10px 15px;
        border-radius: var(--border-radius-sm);
        border: 1px solid var(--gray-200);
    }

    .profile-completion-text {
        font-size: 12px;
        font-weight: 600;
        color: var(--gray-700);
    }

    .progress-bar-container {
        flex: 1;
        height: 8px;
        background: var(--gray-200);
        border-radius: 10px;
        overflow: hidden;
        min-width: 150px;
    }

    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--primary-500), var(--primary-400));
        border-radius: 10px;
        width: 80%; /* Hardcoded 80% */
        position: relative;
    }
    
    .progress-bar-fill::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.4) 50%, rgba(255,255,255,0) 100%);
        animation: progressShine 2s infinite;
    }

    @keyframes progressShine {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }

    .action-btn-group {
        display: flex;
        gap: 12px;
        align-items: center;
        position: relative;
        z-index: 2;
    }

    .btn-market-explore {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        background: linear-gradient(135deg, var(--primary-600), var(--primary-700));
        color: white !important;
        border-radius: var(--border-radius-md);
        font-size: 14px;
        font-weight: 700;
        transition: var(--transition-normal);
        text-decoration: none;
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        border: none;
    }

    .btn-market-explore:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
    }

    .btn-market-explore i {
        font-size: 18px;
        animation: float 3s ease-in-out infinite;
    }

    .btn-create-request {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        background: white;
        color: var(--gray-800) !important;
        border-radius: var(--border-radius-md);
        font-size: 14px;
        font-weight: 700;
        transition: var(--transition-normal);
        text-decoration: none;
        border: 1px solid var(--gray-300);
    }

    .btn-create-request:hover {
        background: var(--gray-50);
        border-color: var(--primary-400);
        color: var(--primary-600) !important;
    }

    /* ==========================================================================
       4. STATISTIC CARDS (Advanced)
       ========================================================================== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card-pro {
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: var(--border-radius-lg);
        padding: 22px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: var(--shadow-sm);
        transition: var(--transition-normal);
        position: relative;
        overflow: hidden;
    }

    .stat-card-pro:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary-200);
    }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
    }

    .stat-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
    }

    .icon-blue { background: linear-gradient(135deg, var(--primary-500), var(--primary-700)); }
    .icon-green { background: linear-gradient(135deg, #10b981, #059669); }
    .icon-orange { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .icon-red { background: linear-gradient(135deg, #f43f5e, #be123c); }

    .stat-trend {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 12px;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 20px;
    }

    .trend-up {
        background: var(--success-light);
        color: var(--success-dark);
    }

    .trend-down {
        background: var(--accent-100);
        color: var(--accent-600);
    }
    
    .trend-neutral {
        background: var(--gray-100);
        color: var(--gray-600);
    }

    .stat-body .stat-value {
        font-size: 28px;
        font-weight: 800;
        color: var(--gray-900);
        line-height: 1.2;
        margin-bottom: 4px;
    }

    .stat-body .stat-label {
        font-size: 13px;
        color: var(--gray-500);
        font-weight: 500;
    }

    /* ==========================================================================
       5. MAIN HERO CAROUSEL PROMO
       ========================================================================== */
    .hero-banner-container {
        position: relative;
        border-radius: var(--border-radius-xl);
        overflow: hidden;
        margin-bottom: 35px;
        box-shadow: var(--shadow-md);
        background: var(--gray-900);
    }

    .hero-slide {
        display: grid;
        grid-template-columns: 1fr 1fr;
        min-height: 320px;
        position: relative;
        background: linear-gradient(105deg, var(--primary-900) 0%, var(--primary-700) 50%, var(--primary-500) 100%);
    }

    /* Abstract shapes in background */
    .hero-slide::before {
        content: '';
        position: absolute;
        width: 400px;
        height: 400px;
        background: var(--primary-400);
        border-radius: 50%;
        filter: blur(80px);
        opacity: 0.4;
        top: -100px;
        right: -100px;
    }

    .hero-content-box {
        padding: 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        z-index: 2;
        position: relative;
    }

    .hero-badge-flash {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        padding: 6px 14px;
        border-radius: 30px;
        color: white;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        width: max-content;
        margin-bottom: 20px;
    }

    .hero-badge-flash i { color: #fbbf24; }

    .hero-title {
        font-size: 36px;
        font-weight: 900;
        color: white;
        line-height: 1.2;
        margin-bottom: 15px;
        letter-spacing: -1px;
    }

    .hero-subtitle {
        font-size: 15px;
        color: var(--primary-100);
        line-height: 1.6;
        margin-bottom: 25px;
        max-width: 90%;
    }

    .hero-cta-group {
        display: flex;
        gap: 15px;
    }

    .btn-hero-primary {
        background: white;
        color: var(--primary-800) !important;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: var(--transition-normal);
    }

    .btn-hero-primary:hover {
        background: var(--primary-50);
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .btn-hero-secondary {
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255,255,255,0.3);
        color: white !important;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        transition: var(--transition-normal);
    }

    .btn-hero-secondary:hover {
        background: rgba(255,255,255,0.25);
    }

    .hero-image-box {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px;
    }

    .hero-image-box img {
        width: 100%;
        max-width: 380px;
        height: auto;
        border-radius: 20px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        transform: rotate(-3deg);
        transition: var(--transition-slow);
    }

    .hero-image-box img:hover {
        transform: rotate(0deg) scale(1.05);
    }

    /* ==========================================================================
       6. CATEGORY EXPLORER (Detailed Grid)
       ========================================================================== */
    .section-block {
        margin-bottom: 45px;
    }

    .section-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 20px;
        border-bottom: 2px solid var(--gray-200);
        padding-bottom: 12px;
    }

    .section-title-wrap h3 {
        font-size: 22px;
        font-weight: 800;
        color: var(--gray-900);
        margin: 0 0 4px;
    }

    .section-title-wrap p {
        font-size: 13px;
        color: var(--gray-500);
        margin: 0;
    }

    .link-view-all {
        font-size: 14px;
        font-weight: 700;
        color: var(--primary-600);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: var(--transition-fast);
    }
    
    .link-view-all:hover {
        color: var(--primary-800);
        gap: 8px;
    }

    .category-grid-large {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 15px;
    }

    .cat-box {
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: var(--border-radius-lg);
        padding: 20px 10px;
        text-align: center;
        text-decoration: none;
        color: var(--gray-700);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .cat-box::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: var(--primary-50);
        transform: translateY(100%);
        transition: transform 0.3s ease;
        z-index: 1;
    }

    .cat-box:hover::before {
        transform: translateY(0);
    }

    .cat-box:hover {
        border-color: var(--primary-300);
        box-shadow: var(--shadow-md);
        transform: translateY(-5px);
    }

    .cat-icon-container {
        width: 56px;
        height: 56px;
        background: var(--gray-50);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        font-size: 24px;
        color: var(--primary-600);
        position: relative;
        z-index: 2;
        transition: var(--transition-normal);
    }

    .cat-box:hover .cat-icon-container {
        background: white;
        box-shadow: var(--shadow-sm);
        transform: scale(1.1);
    }

    .cat-title {
        font-size: 12px;
        font-weight: 700;
        position: relative;
        z-index: 2;
    }

    /* ==========================================================================
       7. REKOMENDASI KHUSUS AI (ADVANCED FEATURE REQUESTED BY USER)
       ========================================================================== */
    .recommendation-container {
        background: white;
        border-radius: var(--border-radius-xl);
        padding: 30px;
        box-shadow: var(--shadow-md);
        border: 1px solid var(--gray-200);
        margin-bottom: 45px;
        position: relative;
        overflow: hidden;
    }

    /* Sparkle decoration for AI recommendation */
    .recommendation-container::before {
        content: '\F5D3'; /* Bootstrap star-fill icon unicode */
        font-family: 'bootstrap-icons';
        position: absolute;
        top: -20px;
        right: -20px;
        font-size: 150px;
        color: var(--warning-light);
        opacity: 0.2;
        transform: rotate(15deg);
        pointer-events: none;
    }

    .ai-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 25px;
    }

    .ai-badge {
        background: linear-gradient(135deg, #8b5cf6, #ec4899);
        color: white;
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        box-shadow: 0 4px 15px rgba(236, 72, 153, 0.3);
        animation: pulse-soft 2s infinite;
    }

    .ai-title-wrap h3 {
        font-size: 22px;
        font-weight: 800;
        margin: 0;
        background: linear-gradient(135deg, #1e293b, #475569);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .ai-title-wrap p {
        font-size: 13px;
        color: var(--gray-500);
        margin: 2px 0 0;
    }

    /* Custom Tabs for Recommendations */
    .custom-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 25px;
        border-bottom: 2px solid var(--gray-100);
        padding-bottom: 10px;
    }

    .custom-tab-btn {
        background: transparent;
        border: none;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 700;
        color: var(--gray-500);
        border-radius: 30px;
        cursor: pointer;
        transition: var(--transition-normal);
        position: relative;
    }

    .custom-tab-btn:hover {
        color: var(--gray-800);
        background: var(--gray-100);
    }

    .custom-tab-btn.active {
        color: white;
        background: var(--gray-900);
        box-shadow: var(--shadow-md);
    }

    .tab-content-panel {
        display: none;
        animation: slideInUp 0.4s ease-out forwards;
    }
    .tab-content-panel.active {
        display: block;
    }

    /* Advanced AI Product Cards */
    .ai-product-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .ai-card {
        display: flex;
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: var(--border-radius-lg);
        padding: 15px;
        gap: 15px;
        transition: var(--transition-normal);
    }

    .ai-card:hover {
        border-color: #8b5cf6;
        box-shadow: 0 10px 25px rgba(139, 92, 246, 0.15);
        transform: translateY(-3px);
    }

    .ai-card-img {
        width: 100px;
        height: 100px;
        border-radius: 10px;
        object-fit: cover;
    }

    .ai-card-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .ai-match-score {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 10px;
        font-weight: 800;
        color: #10b981;
        background: #d1fae5;
        padding: 3px 8px;
        border-radius: 12px;
        width: max-content;
        margin-bottom: 6px;
    }

    .ai-card-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--gray-900);
        margin: 0 0 5px;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .ai-card-price {
        font-size: 15px;
        font-weight: 800;
        color: var(--coral);
        margin-bottom: 8px;
    }

    .ai-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .ai-seller {
        font-size: 11px;
        color: var(--gray-500);
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .btn-quick-view {
        background: var(--gray-100);
        color: var(--gray-700);
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: var(--transition-fast);
    }
    
    .btn-quick-view:hover {
        background: var(--primary-600);
        color: white;
    }

    /* ==========================================================================
       8. COMPLEX LAYOUT: ORDERS & SIDEBAR
       ========================================================================== */
    .content-sidebar-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 25px;
        margin-bottom: 45px;
    }

    /* Advanced Order Tracking Card */
    .order-tracker-card {
        background: white;
        border-radius: var(--border-radius-lg);
        border: 1px solid var(--gray-200);
        box-shadow: var(--shadow-sm);
        padding: 24px;
        height: 100%;
    }
    
    .order-tracker-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--gray-100);
    }

    .order-item-complex {
        border: 1px solid var(--gray-200);
        border-radius: var(--border-radius-md);
        padding: 18px;
        margin-bottom: 15px;
        transition: var(--transition-fast);
    }

    .order-item-complex:hover {
        border-color: var(--primary-300);
        background: var(--gray-50);
    }

    .order-item-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .order-inv {
        font-family: monospace;
        font-size: 13px;
        font-weight: 700;
        color: var(--primary-600);
        background: var(--primary-50);
        padding: 4px 10px;
        border-radius: 6px;
    }

    .order-date {
        font-size: 12px;
        color: var(--gray-500);
    }

    .order-item-mid {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
    }

    .order-item-mid img {
        width: 70px;
        height: 70px;
        border-radius: 8px;
        object-fit: cover;
    }

    .order-details h5 {
        margin: 0 0 5px;
        font-size: 15px;
        font-weight: 700;
        color: var(--gray-900);
    }
    
    .order-details p {
        margin: 0;
        font-size: 13px;
        color: var(--gray-500);
    }

    /* CSS Stepper for order status */
    .stepper-wrapper {
        display: flex;
        justify-content: space-between;
        position: relative;
        padding-top: 20px;
    }

    .stepper-wrapper::before {
        content: '';
        position: absolute;
        top: 24px;
        left: 0;
        width: 100%;
        height: 2px;
        background: var(--gray-200);
        z-index: 1;
    }

    .stepper-item {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .step-counter {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--gray-300);
        border: 3px solid white;
        box-shadow: 0 0 0 1px var(--gray-200);
    }

    .stepper-item.completed .step-counter {
        background: var(--success);
        box-shadow: 0 0 0 2px var(--success-light);
    }

    .stepper-item.active .step-counter {
        background: var(--primary-600);
        box-shadow: 0 0 0 3px var(--primary-200);
        animation: pulse-soft 2s infinite;
    }

    .step-name {
        font-size: 10px;
        font-weight: 700;
        color: var(--gray-500);
        text-transform: uppercase;
    }

    .stepper-item.completed .step-name { color: var(--success); }
    .stepper-item.active .step-name { color: var(--primary-600); }

    /* Sidebar Content */
    .sidebar-widget {
        background: white;
        border-radius: var(--border-radius-lg);
        border: 1px solid var(--gray-200);
        box-shadow: var(--shadow-sm);
        padding: 20px;
        margin-bottom: 20px;
    }

    .widget-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--gray-900);
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .action-menu-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .action-menu-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 15px;
        background: var(--gray-50);
        border-radius: 10px;
        color: var(--gray-800);
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
        transition: var(--transition-fast);
        border: 1px solid transparent;
    }

    .action-menu-item:hover {
        background: white;
        border-color: var(--primary-300);
        color: var(--primary-700);
        transform: translateX(5px);
    }

    .action-menu-item i:first-child {
        font-size: 16px;
        margin-right: 10px;
        color: var(--primary-500);
    }

    .badge-pill-custom {
        background: var(--coral);
        color: white;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 800;
    }

    /* Top Creators List */
    .creator-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .creator-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-bottom: 12px;
        border-bottom: 1px dashed var(--gray-200);
    }
    
    .creator-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .creator-avatar {
        position: relative;
    }

    .creator-avatar img {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
    }

    .verified-badge {
        position: absolute;
        bottom: 0;
        right: 0;
        background: var(--primary-500);
        color: white;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        border: 2px solid white;
    }

    .creator-info h6 {
        margin: 0 0 3px;
        font-size: 13px;
        font-weight: 700;
        color: var(--gray-900);
    }

    .creator-info p {
        margin: 0;
        font-size: 11px;
        color: var(--gray-500);
    }

    .btn-follow-small {
        margin-left: auto;
        background: var(--primary-50);
        color: var(--primary-700);
        border: none;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        transition: var(--transition-fast);
    }

    .btn-follow-small:hover {
        background: var(--primary-600);
        color: white;
    }

    /* ==========================================================================
       9. STANDARD PRODUCT GRID (LATEST)
       ========================================================================== */
    .product-grid-advanced {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }

    .product-card-adv {
        background: white;
        border-radius: var(--border-radius-lg);
        border: 1px solid var(--gray-200);
        overflow: hidden;
        transition: var(--transition-normal);
        position: relative;
        display: flex;
        flex-direction: column;
    }

    .product-card-adv:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
        border-color: var(--primary-200);
    }

    .prod-image-wrap {
        position: relative;
        height: 180px;
        overflow: hidden;
    }

    .prod-image-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .product-card-adv:hover .prod-image-wrap img {
        transform: scale(1.08);
    }

    .prod-badges {
        position: absolute;
        top: 10px;
        left: 10px;
        display: flex;
        flex-direction: column;
        gap: 5px;
        z-index: 2;
    }

    .badge-category-dark {
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(4px);
        color: white;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .badge-bestseller {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 9px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 3px;
    }

    .btn-wishlist-float {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 32px;
        height: 32px;
        background: rgba(255, 255, 255, 0.9);
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gray-400);
        cursor: pointer;
        z-index: 2;
        transition: var(--transition-fast);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .btn-wishlist-float:hover, .btn-wishlist-float.is-active {
        color: var(--coral);
        transform: scale(1.1);
    }

    .prod-content {
        padding: 16px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .prod-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--gray-900);
        margin: 0 0 8px;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-decoration: none;
    }

    .prod-title:hover { color: var(--primary-600); }

    .prod-price-row {
        display: flex;
        align-items: baseline;
        gap: 8px;
        margin-bottom: 10px;
    }

    .prod-price {
        font-size: 18px;
        font-weight: 800;
        color: var(--coral);
    }

    .prod-price-strike {
        font-size: 12px;
        color: var(--gray-400);
        text-decoration: line-through;
    }

    .prod-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: auto;
        padding-top: 12px;
        border-top: 1px dashed var(--gray-200);
    }

    .prod-rating {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 600;
        color: var(--gray-600);
    }
    
    .prod-rating i { color: #f59e0b; }
    
    .prod-sales {
        font-size: 11px;
        color: var(--gray-500);
    }

    .prod-seller-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 12px;
        background: var(--gray-50);
        padding: 8px;
        border-radius: 8px;
    }

    .prod-seller-row img {
        width: 20px;
        height: 20px;
        border-radius: 50%;
    }

    .prod-seller-row span {
        font-size: 11px;
        font-weight: 600;
        color: var(--gray-700);
    }

    /* ==========================================================================
       10. TOAST NOTIFICATIONS & MODALS
       ========================================================================== */
    .toast-container-custom {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .toast-custom {
        background: white;
        border-left: 4px solid var(--success);
        box-shadow: var(--shadow-lg);
        padding: 15px 20px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 12px;
        transform: translateX(120%);
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        width: 300px;
    }

    .toast-custom.show {
        transform: translateX(0);
    }

    .toast-icon {
        width: 24px;
        height: 24px;
        background: var(--success-light);
        color: var(--success-dark);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    .toast-text {
        font-size: 13px;
        font-weight: 600;
        color: var(--gray-800);
    }

    /* RESPONSIVE DESIGN */
    @media (max-width: 1200px) {
        .product-grid-advanced { grid-template-columns: repeat(3, 1fr); }
        .ai-product-grid { grid-template-columns: repeat(2, 1fr); }
    }
    
    @media (max-width: 992px) {
        .content-sidebar-layout { grid-template-columns: 1fr; }
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .hero-slide { grid-template-columns: 1fr; text-align: center; }
        .hero-image-box { display: none; } /* Hide image on tablet/mobile for hero */
        .hero-content-box { padding: 40px 20px; align-items: center; }
        .hero-subtitle { text-align: center; }
    }

    @media (max-width: 768px) {
        .welcome-header { flex-direction: column; gap: 15px; }
        .category-grid-large { grid-template-columns: repeat(3, 1fr); }
        .product-grid-advanced { grid-template-columns: repeat(2, 1fr); }
        .ai-product-grid { grid-template-columns: 1fr; }
        .custom-tabs { overflow-x: auto; white-space: nowrap; padding-bottom: 15px; }
    }

    @media (max-width: 576px) {
        .stats-grid { grid-template-columns: 1fr; }
        .category-grid-large { grid-template-columns: repeat(2, 1fr); }
        .product-grid-advanced { grid-template-columns: 1fr; }
        .action-btn-group { width: 100%; flex-direction: column; }
        .btn-market-explore, .btn-create-request { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')

<div class="dashboard-top-wrapper animate-slide-up">
    <!-- =======================================================================
         1. WELCOME HEADER DENGAN PROFIL PROGRESS BAR
         ======================================================================= -->
    <div class="welcome-header">
        <div class="welcome-text">
            <h2>Halo, {{ $navUser->name ?? Auth::user()->name ?? 'Kreator Hebat' }} 👋</h2>
            <p>Selamat datang kembali di Dashboard Karyaku. Jelajahi ribuan karya digital premium, kelola pesananmu, dan temukan kreator favorit baru hari ini.</p>
            
            <div class="profile-completion">
                <span class="profile-completion-text">Kelengkapan Profil: 80%</span>
                <div class="progress-bar-container">
                    <div class="progress-bar-fill"></div>
                </div>
                <a href="{{ route('pembeli.profile') ?? '#' }}" class="profile-completion-text" style="color:var(--primary-600); text-decoration:none;">Lengkapi <i class="bi bi-arrow-right-short"></i></a>
            </div>
        </div>
    </div>

    <!-- =======================================================================
         2. KARTU STATISTIK LANJUTAN (DENGAN TREN)
         ======================================================================= -->
    <div class="stats-grid">
        <!-- Card 1 -->
        <div class="stat-card-pro delay-100 animate-slide-up">
            <div class="stat-header">
                <div class="stat-icon-wrapper icon-blue">
                    <i class="bi bi-bag-check-fill"></i>
                </div>
                <div class="stat-trend trend-up">
                    <i class="bi bi-arrow-up-right"></i> 12%
                </div>
            </div>
            <div class="stat-body">
                <div class="stat-value">{{ $totalPesanan ?? '0' }}</div>
                <div class="stat-label">Total Pesanan Dibuat</div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="stat-card-pro delay-200 animate-slide-up">
            <div class="stat-header">
                <div class="stat-icon-wrapper icon-green">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="stat-trend trend-up">
                    <i class="bi bi-arrow-up-right"></i> 5%
                </div>
            </div>
            <div class="stat-body">
                <div class="stat-value">{{ $totalSelesai ?? '0' }}</div>
                <div class="stat-label">Pesanan Berhasil Selesai</div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="stat-card-pro delay-300 animate-slide-up">
            <div class="stat-header">
                <div class="stat-icon-wrapper icon-orange">
                    <i class="bi bi-wallet2"></i>
                </div>
                <div class="stat-trend trend-neutral">
                    <i class="bi bi-dash"></i> Stabil
                </div>
            </div>
            <div class="stat-body">
                <div class="stat-value" style="font-size: 22px;">Rp{{ number_format($totalBelanja ?? 0, 0, ',', '.') }}</div>
                <div class="stat-label">Total Pengeluaran Belanja</div>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="stat-card-pro delay-300 animate-slide-up">
            <div class="stat-header">
                <div class="stat-icon-wrapper icon-red">
                    <i class="bi bi-heart-fill"></i>
                </div>
                <div class="stat-trend trend-down">
                    <i class="bi bi-arrow-down-right"></i> 2%
                </div>
            </div>
            <div class="stat-body">
                <div class="stat-value">{{ $totalWishlist ?? '0' }}</div>
                <div class="stat-label">Karya Disimpan (Wishlist)</div>
            </div>
        </div>
    </div>

    <!-- =======================================================================
         3. MAIN HERO CAROUSEL PROMO (BANNER UTAMA)
         ======================================================================= -->
    <div class="hero-banner-container animate-slide-up">
        <div class="hero-slide">
            <div class="hero-content-box">
                <div class="hero-badge-flash">
                    <i class="bi bi-lightning-charge-fill"></i> Penawaran Spesial
                </div>
                <h1 class="hero-title">Kembangkan Bisnismu<br>Dengan Desain Premium</h1>
                <p class="hero-subtitle">
                    Dapatkan potongan harga hingga 50% untuk jasa pembuatan Landing Page, UI/UX Design, dan Logo Branding khusus minggu ini. Kreator top siap membantu.
                </p>
                <div class="hero-cta-group">
                    <a href="{{ route('pembeli.marketplace') }}" class="btn-hero-primary">
                        Klaim Diskon Sekarang <i class="bi bi-arrow-right"></i>
                    </a>
                    <a href="#rekomendasi" class="btn-hero-secondary">
                        Lihat Rekomendasi
                    </a>
                </div>
            </div>
            <div class="hero-image-box">
                <!-- Fallback image from unspash -->
                <img src="https://images.unsplash.com/photo-1559028012-481c04fa702d?auto=format&fit=crop&w=800&q=80" 
                     alt="UI UX Promotion" 
                     loading="lazy">
            </div>
        </div>
    </div>
</div>

<!-- =======================================================================
     4. KATEGORI EKSPLORASI (GRID LENGKAP 12+ ITEM)
     ======================================================================= -->
<div class="section-block">
    <div class="section-head">
        <div class="section-title-wrap">
            <h3>Eksplorasi Kategori</h3>
            <p>Pilih dari puluhan kategori spesifik untuk kebutuhan proyekmu.</p>
        </div>
        <a href="{{ route('pembeli.marketplace') }}" class="link-view-all">
            Jelajahi Semua <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    <div class="category-grid-large">
        <a href="{{ route('pembeli.marketplace', ['category' => 'uiux']) }}" class="cat-box">
            <div class="cat-icon-container"><i class="bi bi-phone"></i></div>
            <span class="cat-title">UI/UX Design</span>
        </a>
        <a href="{{ route('pembeli.marketplace', ['category' => 'logo']) }}" class="cat-box">
            <div class="cat-icon-container"><i class="bi bi-vector-pen"></i></div>
            <span class="cat-title">Logo & Brand</span>
        </a>
        <a href="{{ route('pembeli.marketplace', ['category' => '3d']) }}" class="cat-box">
            <div class="cat-icon-container"><i class="bi bi-box"></i></div>
            <span class="cat-title">3D & Animasi</span>
        </a>
        <a href="{{ route('pembeli.marketplace', ['category' => 'poster']) }}" class="cat-box">
            <div class="cat-icon-container"><i class="bi bi-file-image"></i></div>
            <span class="cat-title">Poster Canva</span>
        </a>
        <a href="{{ route('pembeli.marketplace', ['category' => 'web']) }}" class="cat-box">
            <div class="cat-icon-container"><i class="bi bi-code-slash"></i></div>
            <span class="cat-title">Web Dev</span>
        </a>
        <a href="{{ route('pembeli.marketplace', ['category' => 'video']) }}" class="cat-box">
            <div class="cat-icon-container"><i class="bi bi-camera-video"></i></div>
            <span class="cat-title">Video Editing</span>
        </a>
        <a href="{{ route('pembeli.marketplace', ['category' => 'audio']) }}" class="cat-box">
            <div class="cat-icon-container"><i class="bi bi-music-note-beamed"></i></div>
            <span class="cat-title">Audio & Musik</span>
        </a>
        <a href="{{ route('pembeli.marketplace', ['category' => 'sosmed']) }}" class="cat-box">
            <div class="cat-icon-container"><i class="bi bi-instagram"></i></div>
            <span class="cat-title">Sosial Media</span>
        </a>
    </div>
</div>

<!-- =======================================================================
     5. REKOMENDASI KHUSUS AI (FITUR REQUEST USER)
     ======================================================================= -->
<div class="recommendation-container" id="rekomendasi">
    <div class="ai-header">
        <div class="ai-badge">
            <i class="bi bi-stars"></i>
        </div>
        <div class="ai-title-wrap">
            <h3>Rekomendasi Khusus Untuk Kamu</h3>
            <p>AI kami menganalisis pencarian dan aktivitas terakhirmu untuk menampilkan hasil terbaik.</p>
        </div>
    </div>

    <!-- Interactive Tabs -->
    <div class="custom-tabs">
        <button class="custom-tab-btn active" data-target="tab-foryou">✨ Untuk Kamu</button>
        <button class="custom-tab-btn" data-target="tab-trending">🔥 Sedang Tren</button>
        <button class="custom-tab-btn" data-target="tab-following">👥 Dari Kreator Favorit</button>
    </div>

    <!-- Tab 1: Untuk Kamu (For You) -->
    <div class="tab-content-panel active" id="tab-foryou">
        <div class="ai-product-grid">
            <!-- Simulated AI Recommendation Item 1 -->
            <div class="ai-card">
                <img src="https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=200&q=80" alt="Rec 1" class="ai-card-img">
                <div class="ai-card-info">
                    <span class="ai-match-score"><i class="bi bi-check-circle-fill"></i> 98% Cocok</span>
                    <h4 class="ai-card-title">Paket Branding Kafe Estetik (Logo, Menu, Seragam)</h4>
                    <div class="ai-card-price">Rp850.000</div>
                    <div class="ai-card-footer">
                        <div class="ai-seller">
                            <i class="bi bi-person-circle"></i> Studio Kreatif
                        </div>
                        <button class="btn-quick-view js-add-wishlist" title="Simpan ke Wishlist">
                            <i class="bi bi-heart"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Simulated AI Recommendation Item 2 -->
            <div class="ai-card">
                <img src="https://images.unsplash.com/photo-1618172193622-ae2d025f4032?auto=format&fit=crop&w=200&q=80" alt="Rec 2" class="ai-card-img">
                <div class="ai-card-info">
                    <span class="ai-match-score"><i class="bi bi-check-circle-fill"></i> 95% Cocok</span>
                    <h4 class="ai-card-title">Aset 3D Lingkungan Game Fantasy (Low Poly)</h4>
                    <div class="ai-card-price">Rp420.000</div>
                    <div class="ai-card-footer">
                        <div class="ai-seller">
                            <i class="bi bi-person-circle"></i> Rangga 3D Art
                        </div>
                        <button class="btn-quick-view js-add-wishlist" title="Simpan ke Wishlist">
                            <i class="bi bi-heart"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Simulated AI Recommendation Item 3 -->
            <div class="ai-card">
                <img src="https://images.unsplash.com/photo-1559028012-481c04fa702d?auto=format&fit=crop&w=200&q=80" alt="Rec 3" class="ai-card-img">
                <div class="ai-card-info">
                    <span class="ai-match-score"><i class="bi bi-check-circle-fill"></i> 92% Cocok</span>
                    <h4 class="ai-card-title">Template UI/UX Aplikasi E-Commerce 50+ Screens</h4>
                    <div class="ai-card-price">Rp1.200.000</div>
                    <div class="ai-card-footer">
                        <div class="ai-seller">
                            <i class="bi bi-person-circle"></i> Nadia UI
                        </div>
                        <button class="btn-quick-view js-add-wishlist" title="Simpan ke Wishlist">
                            <i class="bi bi-heart"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab 2: Sedang Tren -->
    <div class="tab-content-panel" id="tab-trending">
        <div class="alert alert-info border-0 bg-primary-50 text-primary-800 rounded-3 p-4 text-center">
            <i class="bi bi-fire fs-1 d-block mb-2 text-primary-500"></i>
            <strong>Trending Topik:</strong> Gaya desain Retro dan Animasi Lofi sedang banyak dicari minggu ini. <br>
            <a href="{{ route('pembeli.marketplace') }}" class="btn btn-sm btn-primary mt-3 rounded-pill px-4">Cari Karya Tren</a>
        </div>
    </div>

    <!-- Tab 3: Dari Kreator -->
    <div class="tab-content-panel" id="tab-following">
        <div class="alert alert-secondary border-0 bg-gray-100 text-gray-700 rounded-3 p-4 text-center">
            <i class="bi bi-people fs-1 d-block mb-2 text-gray-400"></i>
            Kamu belum mengikuti kreator manapun. Mulai ikuti kreator favoritmu untuk melihat karya terbaru mereka di sini.
        </div>
    </div>
</div>

<!-- =======================================================================
     6. MAIN CONTENT SPLIT: ORDERS & SIDEBAR
     ======================================================================= -->
<div class="content-sidebar-layout">
    
    <!-- LEFT COLUMN: ORDER TRACKER -->
    <div class="main-column">
        <div class="order-tracker-card">
            <div class="order-tracker-header">
                <div>
                    <h3 class="widget-title mb-0" style="font-size: 18px;"><i class="bi bi-box-seam text-primary"></i> Lacak Pesanan Aktif</h3>
                    <p class="text-muted small mb-0 mt-1">Pantau progres pengerjaan pesanan terbarumu.</p>
                </div>
                <a href="{{ route('pembeli.pesanan') }}" class="btn btn-sm btn-light border fw-semibold rounded-pill px-3">
                    Semua Riwayat
                </a>
            </div>

            <div class="order-list-container">
                @forelse ($recentOrders ?? [] as $order)
                    <!-- Order Complex Card -->
                    <div class="order-item-complex">
                        <div class="order-item-top">
                            <span class="order-inv">#{{ $order->kode_order }}</span>
                            <span class="order-date"><i class="bi bi-calendar3"></i> {{ $order->created_at ? $order->created_at->format('d M Y, H:i') : 'Baru Saja' }}</span>
                        </div>
                        
                        <div class="order-item-mid">
                            <img src="{{ $order->items->first()->product->image_url ?? 'https://images.unsplash.com/photo-1626785774573-4b799315345d?auto=format&fit=crop&w=150&q=80' }}" alt="Produk">
                            <div class="order-details">
                                <h5>{{ $order->items->first()->product->title ?? 'Judul Produk Digital' }}</h5>
                                <p>Total: <strong>Rp{{ number_format($order->total_price, 0, ',', '.') }}</strong> • {{ $order->items->count() ?? 1 }} Item</p>
                                <p class="mt-1"><i class="bi bi-shop text-primary"></i> Penjual: Kreator ID-{{ $order->seller_id ?? 'Unknown' }}</p>
                            </div>
                        </div>

                        <!-- Stepper UI Based on Status -->
                        @php
                            $status = strtolower($order->status);
                            $isPaid = in_array($status, ['diproses', 'dikirim', 'selesai']);
                            $isProcess = in_array($status, ['dikirim', 'selesai']);
                            $isDone = $status == 'selesai';
                        @endphp
                        
                        <div class="stepper-wrapper">
                            <div class="stepper-item {{ $isPaid ? 'completed' : 'active' }}">
                                <div class="step-counter"></div>
                                <div class="step-name">Dibayar</div>
                            </div>
                            <div class="stepper-item {{ $isProcess ? 'completed' : ($isPaid ? 'active' : '') }}">
                                <div class="step-counter"></div>
                                <div class="step-name">Diproses</div>
                            </div>
                            <div class="stepper-item {{ $isDone ? 'completed' : ($isProcess ? 'active' : '') }}">
                                <div class="step-counter"></div>
                                <div class="step-name">Dikirim</div>
                            </div>
                            <div class="stepper-item {{ $isDone ? 'completed active' : '' }}">
                                <div class="step-counter"></div>
                                <div class="step-name">Selesai</div>
                            </div>
                        </div>
                    </div>
                @empty
                    <!-- Empty State Fallback -->
                    <div class="text-center py-5">
                        <img src="https://cdn3d.iconscout.com/3d/premium/thumb/empty-box-4985160-4158498.png" alt="Empty Box" style="width: 120px; opacity:0.7; margin-bottom:15px;">
                        <h5 class="fw-bold text-gray-800">Belum Ada Pesanan Aktif</h5>
                        <p class="text-muted small mx-auto" style="max-width: 300px;">Keranjang belanjamu masih kosong. Yuk cari produk digital yang kamu butuhkan!</p>
                        <a href="{{ route('pembeli.marketplace') }}" class="btn btn-primary rounded-pill px-4 mt-2">Mulai Belanja</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- RIGHT COLUMN: SIDEBAR WIDGETS -->
    <div class="sidebar-column">
        <!-- Menu Pintas -->
        <div class="sidebar-widget">
            <h4 class="widget-title"><i class="bi bi-grid-fill text-primary"></i> Menu Pintas</h4>
            <ul class="action-menu-list">
                <li>
                    <a href="{{ route('pembeli.keranjang') }}" class="action-menu-item">
                        <div>
                            <i class="bi bi-cart3"></i> Keranjang Belanja
                        </div>
                        <span class="badge-pill-custom">{{ $totalKeranjang ?? 0 }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('pembeli.wishlist') }}" class="action-menu-item">
                        <div>
                            <i class="bi bi-heart"></i> Daftar Keinginan
                        </div>
                        <span class="badge-pill-custom">{{ $totalWishlist ?? 0 }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('pembeli.download') }}" class="action-menu-item">
                        <div>
                            <i class="bi bi-cloud-arrow-down"></i> File Unduhan (Karya Terbeli)
                        </div>
                        <i class="bi bi-chevron-right text-gray-400" style="font-size:12px;"></i>
                    </a>
                </li>
                <li>
                    <a href="{{ route('pembeli.profile') }}" class="action-menu-item">
                        <div>
                            <i class="bi bi-gear"></i> Pengaturan Akun
                        </div>
                        <i class="bi bi-chevron-right text-gray-400" style="font-size:12px;"></i>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Top Creators Widget -->
        <div class="sidebar-widget">
            <h4 class="widget-title"><i class="bi bi-award-fill text-warning"></i> Top Kreator Minggu Ini</h4>
            <div class="creator-list">
                <div class="creator-item">
                    <div class="creator-avatar">
                        <img src="https://ui-avatars.com/api/?name=Studio+Elang&background=dbeafe&color=1e3a8a" alt="Creator 1">
                        <div class="verified-badge"><i class="bi bi-check-lg"></i></div>
                    </div>
                    <div class="creator-info">
                        <h6>Studio Elang Utama</h6>
                        <p>UI/UX & Branding • ⭐ 4.9</p>
                    </div>
                    <button class="btn-follow-small">Ikuti</button>
                </div>

                <div class="creator-item">
                    <div class="creator-avatar">
                        <img src="https://ui-avatars.com/api/?name=Rangga+Art&background=fef3c7&color=b45309" alt="Creator 2">
                        <div class="verified-badge"><i class="bi bi-check-lg"></i></div>
                    </div>
                    <div class="creator-info">
                        <h6>Rangga 3D Art</h6>
                        <p>3D Animation • ⭐ 5.0</p>
                    </div>
                    <button class="btn-follow-small">Ikuti</button>
                </div>

                <div class="creator-item">
                    <div class="creator-avatar">
                        <img src="https://ui-avatars.com/api/?name=Nadia+Design&background=ffebd2&color=f0623f" alt="Creator 3">
                    </div>
                    <div class="creator-info">
                        <h6>Nadia Web Design</h6>
                        <p>Web Developer • ⭐ 4.8</p>
                    </div>
                    <button class="btn-follow-small">Ikuti</button>
                </div>
            </div>
            <a href="#" class="btn btn-light w-100 mt-3 border rounded-3 fw-semibold text-gray-600" style="font-size:12px;">
                Lihat Semua Peringkat Kreator
            </a>
        </div>
    </div>
</div>

<!-- =======================================================================
     7. LATEST MARKETPLACE PRODUCTS GRID (KOMPREHENSIF)
     ======================================================================= -->
<div class="section-block mb-5">
    <div class="section-head">
        <div class="section-title-wrap">
            <h3>Karya Terbaru Ditambahkan</h3>
            <p>Jelajahi karya original terbaru dari komunitas kreator Karyaku.</p>
        </div>
        <a href="{{ route('pembeli.marketplace') }}" class="link-view-all">
            Lihat Katalog Lengkap <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    <div class="product-grid-advanced">
        
        <!-- Karena kita ingin tampilan yang sangat penuh, saya berikan hardcoded list 
             tapi akan override dengan database jika ada -->
             
        @if(isset($rekomendasi) && $rekomendasi->isNotEmpty())
            @foreach ($rekomendasi as $product)
                @include('pembeli.partials.product-card', ['product' => $product, 'wishlistIds' => $wishlistIds ?? []])
            @endforeach
        @else
            
            <!-- FALLBACK PRODUCT 1 -->
            <a href="{{ route('pembeli.marketplace') }}" class="product-card-adv">
                <div class="prod-image-wrap">
                    <div class="prod-badges">
                        <span class="badge-category-dark">UI/UX</span>
                        <span class="badge-bestseller"><i class="bi bi-star-fill"></i> Terlaris</span>
                    </div>
                    <button class="btn-wishlist-float js-add-wishlist" aria-label="Add to Wishlist">
                        <i class="bi bi-heart"></i>
                    </button>
                    <img src="https://images.unsplash.com/photo-1559028012-481c04fa702d?auto=format&fit=crop&w=500&q=80" alt="Prod Image">
                </div>
                <div class="prod-content">
                    <h3 class="prod-title">Mobile Banking UI Kit Premium (Figma, Sketch, Adobe XD) Lengkap</h3>
                    <div class="prod-price-row">
                        <span class="prod-price">Rp850.000</span>
                        <span class="prod-price-strike">Rp1.2M</span>
                    </div>
                    <div class="prod-meta">
                        <div class="prod-rating">
                            <i class="bi bi-star-fill"></i> 4.9
                        </div>
                        <div class="prod-sales">340 Terjual</div>
                    </div>
                    <div class="prod-seller-row">
                        <img src="https://ui-avatars.com/api/?name=Nadia&background=random" alt="Avatar">
                        <span>Nadia UI Designer</span>
                    </div>
                </div>
            </a>

            <!-- FALLBACK PRODUCT 2 -->
            <a href="{{ route('pembeli.marketplace') }}" class="product-card-adv">
                <div class="prod-image-wrap">
                    <div class="prod-badges">
                        <span class="badge-category-dark">ILUSTRASI</span>
                    </div>
                    <button class="btn-wishlist-float js-add-wishlist" aria-label="Add to Wishlist">
                        <i class="bi bi-heart"></i>
                    </button>
                    <img src="https://images.unsplash.com/photo-1618005198919-d3d4b5a92ead?auto=format&fit=crop&w=500&q=80" alt="Prod Image">
                </div>
                <div class="prod-content">
                    <h3 class="prod-title">Set 50+ Vektor Ilustrasi Karakter Flat Design Untuk Startup</h3>
                    <div class="prod-price-row">
                        <span class="prod-price">Rp250.000</span>
                    </div>
                    <div class="prod-meta">
                        <div class="prod-rating">
                            <i class="bi bi-star-fill"></i> 4.7
                        </div>
                        <div class="prod-sales">120 Terjual</div>
                    </div>
                    <div class="prod-seller-row">
                        <img src="https://ui-avatars.com/api/?name=Ilma&background=random" alt="Avatar">
                        <span>Ilma Studio</span>
                    </div>
                </div>
            </a>

            <!-- FALLBACK PRODUCT 3 -->
            <a href="{{ route('pembeli.marketplace') }}" class="product-card-adv">
                <div class="prod-image-wrap">
                    <div class="prod-badges">
                        <span class="badge-category-dark">SOSMED</span>
                    </div>
                    <button class="btn-wishlist-float js-add-wishlist" aria-label="Add to Wishlist">
                        <i class="bi bi-heart"></i>
                    </button>
                    <img src="https://images.unsplash.com/photo-1611926653458-09294b3142bf?auto=format&fit=crop&w=500&q=80" alt="Prod Image">
                </div>
                <div class="prod-content">
                    <h3 class="prod-title">Template 30 Hari Konten Instagram Edukasi Canva (Editable)</h3>
                    <div class="prod-price-row">
                        <span class="prod-price">Rp120.000</span>
                        <span class="prod-price-strike">Rp300.000</span>
                    </div>
                    <div class="prod-meta">
                        <div class="prod-rating">
                            <i class="bi bi-star-fill"></i> 4.8
                        </div>
                        <div class="prod-sales">540 Terjual</div>
                    </div>
                    <div class="prod-seller-row">
                        <img src="https://ui-avatars.com/api/?name=Sasi&background=random" alt="Avatar">
                        <span>Sasi Sosmed</span>
                    </div>
                </div>
            </a>

            <!-- FALLBACK PRODUCT 4 -->
            <a href="{{ route('pembeli.marketplace') }}" class="product-card-adv">
                <div class="prod-image-wrap">
                    <div class="prod-badges">
                        <span class="badge-category-dark">3D MODEL</span>
                        <span class="badge-bestseller"><i class="bi bi-star-fill"></i> Promo</span>
                    </div>
                    <button class="btn-wishlist-float js-add-wishlist" aria-label="Add to Wishlist">
                        <i class="bi bi-heart"></i>
                    </button>
                    <img src="https://images.unsplash.com/photo-1617791160536-598cf32026fb?auto=format&fit=crop&w=500&q=80" alt="Prod Image">
                </div>
                <div class="prod-content">
                    <h3 class="prod-title">Asset Interior Rumah Modern Full Render (Blender File)</h3>
                    <div class="prod-price-row">
                        <span class="prod-price">Rp600.000</span>
                    </div>
                    <div class="prod-meta">
                        <div class="prod-rating">
                            <i class="bi bi-star-fill"></i> 5.0
                        </div>
                        <div class="prod-sales">80 Terjual</div>
                    </div>
                    <div class="prod-seller-row">
                        <img src="https://ui-avatars.com/api/?name=Vio&background=random" alt="Avatar">
                        <span>Vio 3D Studio</span>
                    </div>
                </div>
            </a>
            
            <!-- FALLBACK PRODUCT 5 -->
            <a href="{{ route('pembeli.marketplace') }}" class="product-card-adv">
                <div class="prod-image-wrap">
                    <div class="prod-badges">
                        <span class="badge-category-dark">BRANDING</span>
                    </div>
                    <button class="btn-wishlist-float js-add-wishlist" aria-label="Add to Wishlist">
                        <i class="bi bi-heart"></i>
                    </button>
                    <img src="https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?auto=format&fit=crop&w=500&q=80" alt="Prod Image">
                </div>
                <div class="prod-content">
                    <h3 class="prod-title">Logo Startup & Brand Guideline Lengkap (Format AI, EPS, PDF)</h3>
                    <div class="prod-price-row">
                        <span class="prod-price">Rp450.000</span>
                    </div>
                    <div class="prod-meta">
                        <div class="prod-rating">
                            <i class="bi bi-star-fill"></i> 4.9
                        </div>
                        <div class="prod-sales">156 Terjual</div>
                    </div>
                    <div class="prod-seller-row">
                        <img src="https://ui-avatars.com/api/?name=Branding&background=random" alt="Avatar">
                        <span>Brand Master</span>
                    </div>
                </div>
            </a>
            
            <!-- FALLBACK PRODUCT 6 -->
            <a href="{{ route('pembeli.marketplace') }}" class="product-card-adv">
                <div class="prod-image-wrap">
                    <div class="prod-badges">
                        <span class="badge-category-dark">POSTER</span>
                    </div>
                    <button class="btn-wishlist-float js-add-wishlist" aria-label="Add to Wishlist">
                        <i class="bi bi-heart"></i>
                    </button>
                    <img src="https://images.unsplash.com/photo-1611162618071-b39a2ec055fb?auto=format&fit=crop&w=500&q=80" alt="Prod Image">
                </div>
                <div class="prod-content">
                    <h3 class="prod-title">Set 10 Poster Event Musik & Festival (Photoshop PSD)</h3>
                    <div class="prod-price-row">
                        <span class="prod-price">Rp150.000</span>
                        <span class="prod-price-strike">Rp250.000</span>
                    </div>
                    <div class="prod-meta">
                        <div class="prod-rating">
                            <i class="bi bi-star-fill"></i> 4.5
                        </div>
                        <div class="prod-sales">90 Terjual</div>
                    </div>
                    <div class="prod-seller-row">
                        <img src="https://ui-avatars.com/api/?name=Event&background=random" alt="Avatar">
                        <span>Event Design Id</span>
                    </div>
                </div>
            </a>
            
            <!-- FALLBACK PRODUCT 7 -->
            <a href="{{ route('pembeli.marketplace') }}" class="product-card-adv">
                <div class="prod-image-wrap">
                    <div class="prod-badges">
                        <span class="badge-category-dark">WEB DEV</span>
                    </div>
                    <button class="btn-wishlist-float js-add-wishlist" aria-label="Add to Wishlist">
                        <i class="bi bi-heart"></i>
                    </button>
                    <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=500&q=80" alt="Prod Image">
                </div>
                <div class="prod-content">
                    <h3 class="prod-title">Template HTML/CSS Dashboard Admin Panel Responsif (Bootstrap 5)</h3>
                    <div class="prod-price-row">
                        <span class="prod-price">Rp300.000</span>
                    </div>
                    <div class="prod-meta">
                        <div class="prod-rating">
                            <i class="bi bi-star-fill"></i> 4.8
                        </div>
                        <div class="prod-sales">405 Terjual</div>
                    </div>
                    <div class="prod-seller-row">
                        <img src="https://ui-avatars.com/api/?name=Code&background=random" alt="Avatar">
                        <span>Code Ninja</span>
                    </div>
                </div>
            </a>

            <!-- FALLBACK PRODUCT 8 -->
            <a href="{{ route('pembeli.marketplace') }}" class="product-card-adv">
                <div class="prod-image-wrap">
                    <div class="prod-badges">
                        <span class="badge-category-dark">AUDIO</span>
                    </div>
                    <button class="btn-wishlist-float js-add-wishlist" aria-label="Add to Wishlist">
                        <i class="bi bi-heart"></i>
                    </button>
                    <img src="https://images.unsplash.com/photo-1516280440502-8610eb64811a?auto=format&fit=crop&w=500&q=80" alt="Prod Image">
                </div>
                <div class="prod-content">
                    <h3 class="prod-title">Backsound Musik Lo-Fi untuk YouTube Video (Bebas Royalti)</h3>
                    <div class="prod-price-row">
                        <span class="prod-price">Rp85.000</span>
                    </div>
                    <div class="prod-meta">
                        <div class="prod-rating">
                            <i class="bi bi-star-fill"></i> 4.9
                        </div>
                        <div class="prod-sales">600+ Terjual</div>
                    </div>
                    <div class="prod-seller-row">
                        <img src="https://ui-avatars.com/api/?name=Audio&background=random" alt="Avatar">
                        <span>Audio Beats</span>
                    </div>
                </div>
            </a>

        @endif

    </div>
</div>

<!-- =======================================================================
     8. TOAST NOTIFICATION CONTAINER (Dinamis dari JS)
     ======================================================================= -->
<div class="toast-container-custom" id="toastContainer"></div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // 1. TABS LOGIC UNTUK REKOMENDASI AI
        const tabBtns = document.querySelectorAll('.custom-tab-btn');
        const tabPanels = document.querySelectorAll('.tab-content-panel');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Hapus state aktif dari semua tombol dan panel
                tabBtns.forEach(b => b.classList.remove('active'));
                tabPanels.forEach(p => p.classList.remove('active'));
                
                // Tambahkan state aktif ke elemen yang diklik
                this.classList.add('active');
                const targetId = this.getAttribute('data-target');
                const targetPanel = document.getElementById(targetId);
                
                if(targetPanel) {
                    targetPanel.classList.add('active');
                }
            });
        });

        // 2. WISHLIST TOGGLE & TOAST NOTIFICATION LOGIC
        const wishlistBtns = document.querySelectorAll('.js-add-wishlist');
        const toastContainer = document.getElementById('toastContainer');

        function createToast(message, isAdded) {
            const toast = document.createElement('div');
            toast.className = 'toast-custom';
            
            const iconClass = isAdded ? 'bi-check-lg' : 'bi-info-circle';
            const iconBg = isAdded ? 'var(--success-light)' : 'var(--warning-light)';
            const iconColor = isAdded ? 'var(--success-dark)' : 'var(--warning-dark)';
            const borderLeft = isAdded ? 'var(--success)' : 'var(--warning)';

            toast.style.borderLeftColor = borderLeft;
            toast.innerHTML = `
                <div class="toast-icon" style="background:${iconBg}; color:${iconColor}">
                    <i class="bi ${iconClass}"></i>
                </div>
                <div class="toast-text">${message}</div>
            `;

            toastContainer.appendChild(toast);

            // Animate In
            setTimeout(() => {
                toast.classList.add('show');
            }, 10);

            // Auto Remove
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.remove();
                }, 400); // Wait for transition
            }, 3000);
        }

        wishlistBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault(); // Mencegah pindah halaman jika elemen adalah `a`
                e.stopPropagation(); // Mencegah trigger parent click
                
                this.classList.toggle('is-active');
                const icon = this.querySelector('i');
                
                let isAdded = false;
                if(icon.classList.contains('bi-heart')) {
                    icon.classList.remove('bi-heart');
                    icon.classList.add('bi-heart-fill');
                    isAdded = true;
                } else {
                    icon.classList.remove('bi-heart-fill');
                    icon.classList.add('bi-heart');
                    isAdded = false;
                }

                const msg = isAdded ? 'Berhasil ditambahkan ke Wishlist!' : 'Dihapus dari Wishlist.';
                createToast(msg, isAdded);
            });
        });

        // 3. ANIMATION ON SCROLL (SIMPLE)
        // Membuat efek elemen muncul perlahan saat di-scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = 1;
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.section-block, .content-sidebar-layout, .recommendation-container').forEach(el => {
            el.style.opacity = 0;
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'all 0.6s ease-out';
            observer.observe(el);
        });

    });
</script>
@endpush
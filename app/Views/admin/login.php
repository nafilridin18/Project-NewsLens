<?php
$appUrl = rtrim($appUrl ?? '', '/');
$adminPath = $adminPath ?? 'lens-desk';
$csrfToken = $csrfToken ?? '';
$error = $error ?? '';
?>
<!DOCTYPE html>
<html lang="bn" data-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>লগইন | Newslensbd অ্যাডমিন কমান্ড সেন্টার</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      /* Dynamic theme variables */
      --bg-page: #071630;
      --bg-card: #0c1e3d;
      --bg-input: #071224;
      --border-color: #173567;
      --text-main: #f1f5f9;
      --text-muted: #94a3b8;
      --primary-green: #0E7A3A;
      --primary-red: #E31E24;
      
      /* MAP COLORS (Dark Mode Default: DYNAMIC RED) */
      --map-fill-primary: #E31E24;
      --map-fill-secondary: #991b1b;
      --map-stroke: #f87171;
      --map-glow: rgba(227, 30, 36, 0.35);
      --map-pulse-dot: #fca5a5;
      --map-pin-badge: #E31E24;
      --map-caption-accent: #f87171;
    }

    /* LIGHT / WHITE MODE (DYNAMIC GREEN) */
    [data-theme="light"] {
      --bg-page: #f1f5f9;
      --bg-card: #ffffff;
      --bg-input: #f8fafc;
      --border-color: #cbd5e1;
      --text-main: #0f172a;
      --text-muted: #64748b;

      /* MAP COLORS (Light Mode: DYNAMIC GREEN) */
      --map-fill-primary: #0E7A3A;
      --map-fill-secondary: #064e3b;
      --map-stroke: #22c55e;
      --map-glow: rgba(14, 122, 58, 0.3);
      --map-pulse-dot: #86efac;
      --map-pin-badge: #0E7A3A;
      --map-caption-accent: #0E7A3A;
    }

    body {
      font-family: 'Hind Siliguri', sans-serif;
      background-color: var(--bg-page);
      color: var(--text-main);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 24px 16px;
      transition: background-color 0.3s ease, color 0.3s ease;
      position: relative;
    }

    /* Top Utility Bar (Theme Toggle & Return to Site) */
    .top-controls {
      position: absolute;
      top: 20px;
      right: 24px;
      display: flex;
      align-items: center;
      gap: 14px;
      z-index: 20;
    }

    .btn-return-portal {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 0.85rem;
      font-weight: 600;
      color: var(--text-muted);
      text-decoration: none;
      padding: 6px 12px;
      border-radius: 20px;
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      transition: all 0.2s;
    }
    .btn-return-portal:hover {
      color: var(--text-main);
      border-color: var(--map-fill-primary);
    }

    .theme-toggle-btn, .lang-toggle-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      border-radius: 24px;
      padding: 6px 14px;
      cursor: pointer;
      color: var(--text-main);
      font-size: 0.85rem;
      font-weight: 700;
      transition: all 0.2s;
    }
    .theme-toggle-btn:hover, .lang-toggle-btn:hover {
      border-color: var(--map-fill-primary);
    }

    /* Split-Screen Main Container: 2-Column Balanced Layout */
    .login-container {
      width: 100%;
      max-width: 1100px;
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      border-radius: 18px;
      box-shadow: 0 20px 45px rgba(0, 0, 0, 0.25);
      display: grid;
      grid-template-columns: 1.05fr 1fr;
      overflow: hidden;
      min-height: 600px;
      margin: 20px auto;
      transition: background 0.3s ease, border-color 0.3s ease;
    }

    @media (max-width: 860px) {
      .login-container {
        grid-template-columns: 1fr;
        max-width: 520px;
        min-height: auto;
      }
      .map-side-panel {
        border-right: none !important;
        border-bottom: 1px solid var(--border-color);
        padding: 24px 18px !important;
      }
    }

    /* ================= LEFT SIDE: DYNAMIC BANGLADESH MAP PANEL ================= */
    .map-side-panel {
      background: linear-gradient(145deg, rgba(227, 30, 36, 0.05), rgba(14, 122, 58, 0.05));
      border-right: 1px solid var(--border-color);
      padding: 30px 24px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      align-items: center;
      position: relative;
      overflow: hidden;
    }

    .map-header-badge {
      text-align: center;
      width: 100%;
    }
    .map-tag {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(255, 255, 255, 0.06);
      border: 1px solid var(--border-color);
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.78rem;
      font-weight: 700;
      color: var(--map-caption-accent);
      margin-bottom: 6px;
    }
    .map-headline {
      font-size: 1.15rem;
      font-weight: 800;
      color: var(--text-main);
      line-height: 1.3;
    }

    /* Dynamic SVG Map Area */
    .map-canvas-wrap {
      width: 100%;
      max-width: 380px;
      height: 460px;
      display: flex;
      align-items: center;
      justify-content: center;
      position: relative;
      margin: 6px 0;
    }

    .bd-vector-map {
      width: 100%;
      height: 100%;
      filter: drop-shadow(0 10px 24px var(--map-glow));
      transition: all 0.4s ease;
    }

    .bd-div-path {
      fill: url(#bdGradient);
      transition: fill 0.4s ease;
    }

    /* RIVERS: Always Light Blue in both dark and light modes as requested */
    .bd-river {
      stroke: #38bdf8 !important;
      fill: none;
      stroke-linecap: round;
      stroke-linejoin: round;
      filter: drop-shadow(0 0 6px rgba(56, 189, 248, 0.85));
    }
    .bd-river-main {
      stroke-width: 22px;
    }
    .bd-river-sub {
      stroke-width: 14px;
    }

    /* SEA (Bay of Bengal / বঙ্গোপসাগর): Always Light Blue in both modes */
    .bd-sea {
      fill: url(#seaGradient);
      opacity: 0.95;
    }
    .bd-sea-wave {
      stroke: #38bdf8;
      stroke-width: 6px;
      stroke-linecap: round;
      fill: none;
      opacity: 0.75;
    }
    .bd-sea-label {
      fill: #38bdf8;
      font-size: 44px;
      font-weight: 700;
      letter-spacing: 2px;
      font-family: 'Hind Siliguri', sans-serif;
    }

    .river-label {
      fill: #38bdf8;
      font-size: 34px;
      font-weight: 700;
      font-family: 'Hind Siliguri', sans-serif;
      text-shadow: 0 2px 6px rgba(0,0,0,0.9);
    }

    .map-title-label {
      fill: var(--text-main);
      font-size: 72px;
      font-weight: 800;
      font-family: 'Plus Jakarta Sans', sans-serif;
      letter-spacing: -0.5px;
    }

    .div-text-label {
      fill: #ffffff;
      font-size: 46px;
      font-weight: 700;
      font-family: 'Plus Jakarta Sans', sans-serif;
      text-shadow: 0 2px 8px rgba(0,0,0,0.85);
    }
    .capital-label {
      fill: #facc15;
      font-weight: 800;
    }

    [data-theme="light"] .div-text-label {
      fill: #881337; /* Maroon font exactly as in user picture */
      text-shadow: 0 1px 4px rgba(255,255,255,0.8);
    }
    [data-theme="light"] .capital-label {
      fill: #881337;
      font-weight: 800;
    }
    [data-theme="light"] .map-title-label {
      fill: #0f172a;
    }

    .pulse-dot {
      animation: pulseAnimation 2s infinite ease-in-out;
      transform-origin: center;
    }

    @keyframes pulseAnimation {
      0% { r: 12; opacity: 1; }
      50% { r: 24; opacity: 0.35; }
      100% { r: 12; opacity: 1; }
    }



    /* ================= RIGHT SIDE: LOGIN FORM PANEL ================= */
    .form-side-panel {
      padding: 44px 38px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .brand-header-area {
      text-align: center;
      margin-bottom: 24px;
    }
    .logo-badge-card {
      background: #ffffff;
      padding: 6px 18px;
      border-radius: 8px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 14px rgba(0,0,0,0.12);
      margin-bottom: 12px;
    }
    .logo-badge-card img {
      height: 38px;
      max-width: 180px;
      object-fit: contain;
      display: block;
    }

    .brand-title {
      font-size: 1.85rem;
      font-weight: 800;
      color: var(--text-main);
      letter-spacing: -0.5px;
      margin-bottom: 6px;
    }
    .brand-title .hl-green { color: #0E7A3A; }
    .brand-title .badge-red {
      background: #E31E24;
      color: #ffffff;
      font-size: 0.8rem;
      padding: 2px 6px;
      border-radius: 4px;
      margin-left: 4px;
      vertical-align: middle;
    }
    .brand-subtitle {
      font-size: 0.88rem;
      color: var(--text-muted);
      margin-top: 4px;
    }

    /* Form Fields */
    .form-group { margin-bottom: 18px; }
    label {
      display: block;
      font-size: 0.88rem;
      font-weight: 600;
      margin-bottom: 6px;
      color: var(--text-main);
    }
    input.form-control {
      width: 100%;
      padding: 12px 14px;
      background: var(--bg-input);
      border: 1px solid var(--border-color);
      border-radius: 8px;
      color: var(--text-main);
      font-size: 0.95rem;
      font-family: inherit;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    input.form-control:focus {
      border-color: #0E7A3A;
      box-shadow: 0 0 0 3px rgba(14, 122, 58, 0.2);
    }

    .password-input-wrap {
      position: relative;
      display: flex;
      align-items: center;
      width: 100%;
    }
    .password-input-wrap input.form-control {
      padding-right: 48px;
    }
    .btn-toggle-pass {
      position: absolute;
      right: 8px;
      top: 50%;
      transform: translateY(-50%);
      background: transparent;
      border: none;
      cursor: pointer;
      padding: 8px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border-radius: 6px;
      color: var(--text-muted);
      transition: all 0.2s ease;
      line-height: 1;
    }
    .btn-toggle-pass:hover {
      color: var(--text-main);
      background: rgba(255, 255, 255, 0.08);
    }
    [data-theme="light"] .btn-toggle-pass:hover {
      background: rgba(0, 0, 0, 0.06);
    }
    .btn-toggle-pass.active {
      color: var(--map-fill-primary);
    }
    .btn-toggle-pass:focus {
      outline: none;
    }
    .btn-toggle-pass svg {
      display: block;
      transition: stroke 0.2s ease;
    }

    .btn-login-submit {
      width: 100%;
      background: #0E7A3A;
      color: #ffffff;
      border: none;
      padding: 13px;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 800;
      cursor: pointer;
      margin-top: 6px;
      box-shadow: 0 4px 12px rgba(14, 122, 58, 0.3);
      transition: background 0.2s, transform 0.1s;
    }
    .btn-login-submit:hover {
      background: #0a5c2b;
      transform: translateY(-1px);
    }

    .alert-box {
      padding: 10px 14px;
      border-radius: 8px;
      font-size: 0.88rem;
      margin-bottom: 18px;
      text-align: center;
      font-weight: 600;
    }
    .alert-error {
      background: rgba(227, 30, 36, 0.15);
      border: 1px solid #E31E24;
      color: #f87171;
    }
    .alert-success {
      background: rgba(14, 122, 58, 0.15);
      border: 1px solid #0E7A3A;
      color: #4ade80;
    }

    .bottom-hint-row {
      margin-top: 20px;
      text-align: center;
      font-size: 0.86rem;
      color: var(--text-muted);
      border-top: 1px solid var(--border-color);
      padding-top: 14px;
    }
    .bottom-hint-row a {
      color: #38bdf8;
      text-decoration: none;
      font-weight: 700;
    }
    .bottom-hint-row a:hover { text-decoration: underline; }

    .test-credentials-pill {
      margin-top: 14px;
      font-size: 0.78rem;
      color: var(--text-muted);
      text-align: center;
      background: var(--bg-input);
      border: 1px dashed var(--border-color);
      padding: 8px 12px;
      border-radius: 6px;
    }
    .test-credentials-pill code {
      color: var(--text-main);
      font-weight: 700;
    }
  </style>
</head>
<body>

  <!-- Top Utilities Bar: Portal Link, Language Toggle (EN/বাংলা), Theme Toggle -->
  <div class="top-controls">
    <a href="<?= $appUrl ?>/" class="btn-return-portal" title="মূল নিউজ পোর্টালে যান">
      <span id="txt-portal" data-bn="🌐 লাইভ পোর্টাল ↗" data-en="🌐 Live Portal ↗">🌐 লাইভ পোর্টাল ↗</span>
    </a>
    <button type="button" class="lang-toggle-btn" id="login-lang-toggle" aria-label="ভাষা পরিবর্তন" title="Switch Language">
      🌐 <span id="login-lang-label">EN</span>
    </button>
    <button type="button" class="theme-toggle-btn" id="login-theme-toggle" aria-label="থিম পরিবর্তন">
      <span id="theme-icon">🌙</span>
      <span id="theme-mode-label">ডার্ক মোড</span>
    </button>
  </div>

  <div class="login-container">

    <!-- ================= LEFT PANEL: DYNAMIC BANGLADESH MAP (RED IN DARK / GREEN IN WHITE) ================= -->
    <div class="map-side-panel">
      <div class="map-header-badge">
        <span class="map-tag" id="txt-map-tag" data-bn="📍 জাতীয় প্রশাসনিক ডেক্স" data-en="📍 National Admin Desk">
          📍 জাতীয় প্রশাসনিক ডেক্স
        </span>
        <h3 class="map-headline" id="txt-map-headline" data-bn="৮ বিভাগ ও ৬৪ জেলার কেন্দ্রীয় কন্ট্রোল হাব" data-en="Central Command Hub for 8 Divisions & 64 Districts">
          ৮ বিভাগ ও ৬৪ জেলার কেন্দ্রীয় কন্ট্রোল হাব
        </h3>
      </div>

      <!-- Vector SVG Map of Bangladesh with Rivers, Bay of Bengal & Divisions -->
      <div class="map-canvas-wrap">
        <?php require __DIR__ . '/partials/bd_map_svg.php'; ?>
      </div>
    </div>

    <!-- ================= RIGHT PANEL: LOGIN FORM ================= -->
    <div class="form-side-panel">
      <div class="brand-header-area">
        <div class="brand-title">NEWS<span class="hl-green">LENS</span><span class="badge-red">BD</span></div>
        <p class="brand-subtitle" id="txt-brand-sub" data-bn="নিউজ ম্যানেজমেন্ট ও সম্পাদকীয় কমান্ড ডেস্ক" data-en="News Management & Editorial Command Desk">নিউজ ম্যানেজমেন্ট ও সম্পাদকীয় কমান্ড ডেস্ক</p>
      </div>

      <?php if (isset($_GET['msg']) && $_GET['msg'] === 'registered'): ?>
        <div class="alert-box alert-success" id="txt-reg-success" data-bn="✅ রেজিস্ট্রেশন সম্পন্ন হয়েছে! আপনার ইমেইল ও পাসওয়ার্ড দিয়ে প্রবেশ করুন।" data-en="✅ Registration complete! Please log in with your credentials.">✅ রেজিস্ট্রেশন সম্পন্ন হয়েছে! আপনার ইমেইল ও পাসওয়ার্ড দিয়ে প্রবেশ করুন।</div>
      <?php endif; ?>

      <?php if (!empty($error)): ?>
        <div class="alert-box alert-error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form action="<?= $appUrl ?>/<?= $adminPath ?>/login" method="post" id="admin-login-form">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        
        <div class="form-group">
          <label for="email" id="txt-label-email" data-bn="অ্যাডমিন ইমেইল" data-en="Admin Email">অ্যাডমিন ইমেইল</label>
          <input type="email" name="email" id="email" class="form-control" required placeholder="admin@newslensbd.com" autocomplete="email">
        </div>

        <div class="form-group">
          <label for="password" id="txt-label-pass" data-bn="গোপন পাসওয়ার্ড" data-en="Secret Password">গোপন পাসওয়ার্ড</label>
          <div class="password-input-wrap">
            <input type="password" name="password" id="password" class="form-control" required placeholder="••••••••" autocomplete="current-password">
            <button type="button" class="btn-toggle-pass" id="btn-toggle-pass" aria-label="পাসওয়ার্ড দেখুন" title="পাসওয়ার্ড দেখুন">
              <svg class="eye-icon eye-open" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                <circle cx="12" cy="12" r="3"></circle>
              </svg>
              <svg class="eye-icon eye-closed" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                <line x1="1" y1="1" x2="23" y2="23"></line>
              </svg>
            </button>
          </div>
        </div>

        <button type="submit" class="btn-login-submit" id="txt-btn-login" data-bn="কমান্ড সেন্টারে প্রবেশ করুন ➔" data-en="Enter Command Center ➔">কমান্ড সেন্টারে প্রবেশ করুন ➔</button>
      </form>

      <div class="bottom-hint-row">
        <span id="txt-hint-need" data-bn="নতুন অ্যাকাউন্ট প্রয়োজন?" data-en="Need a new account?">নতুন অ্যাকাউন্ট প্রয়োজন?</span> <a href="<?= $appUrl ?>/<?= $adminPath ?>/register" id="txt-hint-link" data-bn="রেজিস্ট্রেশন করুন ➔" data-en="Register here ➔">রেজিস্ট্রেশন করুন ➔</a>
      </div>

      <div class="test-credentials-pill">
        <span id="txt-demo-login" data-bn="পরীক্ষামূলক লগইন:" data-en="Demo Login:">পরীক্ষামূলক লগইন:</span> <code>admin@newslensbd.com</code> / <code>password123</code>
      </div>
    </div>

  </div>

  <script>
    // Dynamic Theme & Bilingual (BN/EN) Controller for Login Page
    document.addEventListener('DOMContentLoaded', () => {
      const htmlRoot = document.documentElement;
      const themeToggle = document.getElementById('login-theme-toggle');
      const themeIcon = document.getElementById('theme-icon');
      const themeLabel = document.getElementById('theme-mode-label');
      const langToggle = document.getElementById('login-lang-toggle');
      const langLabel = document.getElementById('login-lang-label');

      // 1. Language Controller (Bangla / English)
      const applyLang = (lang) => {
        htmlRoot.setAttribute('lang', lang);
        localStorage.setItem('admin_lang', lang);
        document.cookie = 'admin_lang=' + lang + '; path=/; max-age=31536000; SameSite=Lax';

        if (langLabel) {
          langLabel.textContent = lang === 'en' ? 'বাংলা' : 'EN';
        }

        // Translate all data-bn / data-en attributes on the page
        document.querySelectorAll('[data-bn][data-en]').forEach(el => {
          el.textContent = lang === 'en' ? el.getAttribute('data-en') : el.getAttribute('data-bn');
        });

        // Update Theme Button Text based on current lang & theme
        updateThemeLabel(htmlRoot.getAttribute('data-theme') || 'dark', lang);
      };

      const updateThemeLabel = (theme, lang) => {
        if (!themeLabel) return;
        if (theme === 'dark') {
          themeLabel.textContent = lang === 'en' ? 'Light Mode' : 'লাইট মোড';
          if (themeIcon) themeIcon.textContent = '☀️';
        } else {
          themeLabel.textContent = lang === 'en' ? 'Dark Mode' : 'ডার্ক মোড';
          if (themeIcon) themeIcon.textContent = '🌙';
        }
      };

      // 2. Theme Controller (Dark: Red Map / Light: Green Map)
      const applyTheme = (theme) => {
        htmlRoot.setAttribute('data-theme', theme);
        localStorage.setItem('admin_theme', theme);
        document.cookie = 'admin_theme=' + theme + '; path=/; max-age=31536000; SameSite=Lax';

        const currentLang = localStorage.getItem('admin_lang') || 
                           (document.cookie.match(/admin_lang=([^;]+)/)?.[1]) || 'bn';
        updateThemeLabel(theme, currentLang);
      };

      // 3. Password Visibility Toggle (Dynamic with Dark & Light Theme)
      const passInput = document.getElementById('password');
      const passToggleBtn = document.getElementById('btn-toggle-pass');
      const eyeOpen = passToggleBtn?.querySelector('.eye-open');
      const eyeClosed = passToggleBtn?.querySelector('.eye-closed');

      passToggleBtn?.addEventListener('click', () => {
        if (!passInput) return;
        const isPassword = passInput.getAttribute('type') === 'password';
        const isEn = (localStorage.getItem('admin_lang') || 'bn') === 'en';

        if (isPassword) {
          passInput.setAttribute('type', 'text');
          if (eyeOpen) eyeOpen.style.display = 'none';
          if (eyeClosed) eyeClosed.style.display = 'block';
          passToggleBtn.classList.add('active');
          passToggleBtn.setAttribute('title', isEn ? 'Hide Password' : 'পাসওয়ার্ড লুকান');
          passToggleBtn.setAttribute('aria-label', isEn ? 'Hide Password' : 'পাসওয়ার্ড লুকান');
        } else {
          passInput.setAttribute('type', 'password');
          if (eyeOpen) eyeOpen.style.display = 'block';
          if (eyeClosed) eyeClosed.style.display = 'none';
          passToggleBtn.classList.remove('active');
          passToggleBtn.setAttribute('title', isEn ? 'Show Password' : 'পাসওয়ার্ড দেখুন');
          passToggleBtn.setAttribute('aria-label', isEn ? 'Show Password' : 'পাসওয়ার্ড দেখুন');
        }
      });

      // Initialize saved Theme & Lang
      const savedTheme = localStorage.getItem('admin_theme') || 
                        (document.cookie.match(/admin_theme=([^;]+)/)?.[1]) || 'dark';
      const savedLang = localStorage.getItem('admin_lang') || 
                       (document.cookie.match(/admin_lang=([^;]+)/)?.[1]) || 'bn';
      
      applyTheme(savedTheme);
      applyLang(savedLang);

      // Event Listeners
      themeToggle?.addEventListener('click', () => {
        const current = htmlRoot.getAttribute('data-theme') || 'dark';
        const next = current === 'dark' ? 'light' : 'dark';
        applyTheme(next);
      });

      langToggle?.addEventListener('click', () => {
        const current = localStorage.getItem('admin_lang') || 'bn';
        const next = current === 'en' ? 'bn' : 'en';
        applyLang(next);
      });
    });
  </script>
</body>
</html>

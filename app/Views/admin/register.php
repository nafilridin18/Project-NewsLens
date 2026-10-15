<?php
$appUrl = rtrim($appUrl ?? '', '/');
$adminPath = $adminPath ?? 'lens-desk';
$csrfToken = $csrfToken ?? '';
$error = $error ?? '';
$formData = $formData ?? ['name' => '', 'phone' => '', 'email' => ''];
?>
<!DOCTYPE html>
<html lang="bn" data-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title data-bn="রেজিস্ট্রেশন | Newslensbd অ্যাডমিন কমান্ড সেন্টার" data-en="Registration | Newslensbd Admin Command Center">রেজিস্ট্রেশন | Newslensbd অ্যাডমিন কমান্ড সেন্টার</title>
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
    .register-container {
      width: 100%;
      max-width: 1100px;
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      border-radius: 18px;
      box-shadow: 0 20px 45px rgba(0, 0, 0, 0.25);
      display: grid;
      grid-template-columns: 1.05fr 1fr;
      overflow: hidden;
      min-height: 640px;
      margin: 20px auto;
      transition: background 0.3s ease, border-color 0.3s ease;
    }

    @media (max-width: 860px) {
      .register-container {
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
      justify-content: center;
      align-items: center;
      position: relative;
      overflow: hidden;
      gap: 20px;
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

    /* RIVERS: Always Light Blue in both dark and light modes */
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
      fill: #881337;
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

    /* ================= RIGHT SIDE: REGISTER FORM PANEL ================= */
    .form-side-panel {
      padding: 36px 36px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .brand-header-area {
      text-align: center;
      margin-bottom: 20px;
    }
    .brand-title {
      font-family: 'Plus Jakarta Sans', sans-serif;
      font-size: 1.95rem;
      font-weight: 800;
      letter-spacing: -0.03em;
      color: var(--text-main);
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 3px;
      margin-bottom: 4px;
    }
    .brand-title .hl-green { color: #0E7A3A; }
    .brand-title .badge-red {
      background: #E31E24;
      color: #ffffff;
      font-size: 0.76rem;
      font-weight: 800;
      padding: 2px 7px;
      border-radius: 5px;
      margin-left: 5px;
      letter-spacing: 0.04em;
      display: inline-block;
      line-height: 1.3;
      vertical-align: middle;
    }
    .brand-title .badge-admin {
      background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
      color: #ffffff;
      font-size: 0.72rem;
      font-weight: 800;
      padding: 3px 9px;
      border-radius: 6px;
      margin-left: 6px;
      letter-spacing: 0.05em;
      text-transform: uppercase;
      box-shadow: 0 2px 8px rgba(2, 132, 199, 0.4);
      display: inline-block;
      line-height: 1.3;
      vertical-align: middle;
    }
    .brand-subtitle {
      font-size: 0.88rem;
      color: var(--text-muted);
      margin-top: 4px;
    }

    /* Form Fields */
    .form-group { margin-bottom: 14px; }
    label {
      display: block;
      font-size: 0.86rem;
      font-weight: 600;
      margin-bottom: 5px;
      color: var(--text-main);
    }
    input.form-control {
      width: 100%;
      padding: 10px 14px;
      background: var(--bg-input);
      border: 1px solid var(--border-color);
      border-radius: 8px;
      color: var(--text-main);
      font-size: 0.92rem;
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

    .btn-register-submit {
      width: 100%;
      background: #0E7A3A;
      color: #ffffff;
      border: none;
      padding: 12px;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 800;
      cursor: pointer;
      margin-top: 6px;
      box-shadow: 0 4px 12px rgba(14, 122, 58, 0.3);
      transition: background 0.2s, transform 0.1s;
    }
    .btn-register-submit:hover {
      background: #0a5c2b;
      transform: translateY(-1px);
    }

    .alert-box {
      padding: 10px 14px;
      border-radius: 8px;
      font-size: 0.88rem;
      margin-bottom: 16px;
      text-align: center;
      font-weight: 600;
    }
    .alert-error {
      background: rgba(227, 30, 36, 0.15);
      border: 1px solid #E31E24;
      color: #f87171;
    }

    .bottom-hint-row {
      margin-top: 18px;
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
  </style>
</head>
<body>

  <!-- Top Utilities Bar: Portal Link, Language Toggle (EN/বাংলা), Theme Toggle -->
  <div class="top-controls">
    <a href="<?= $appUrl ?>/" class="btn-return-portal" title="মূল নিউজ পোর্টালে যান">
      <span id="txt-portal" data-bn="🌐 লাইভ পোর্টাল ↗" data-en="🌐 Live Portal ↗">🌐 লাইভ পোর্টাল ↗</span>
    </a>
    <button type="button" class="lang-toggle-btn" id="reg-lang-toggle" aria-label="ভাষা পরিবর্তন" title="Switch Language">
      🌐 <span id="reg-lang-label">EN</span>
    </button>
    <button type="button" class="theme-toggle-btn" id="reg-theme-toggle" aria-label="থিম পরিবর্তন">
      <span id="theme-icon">🌙</span>
      <span id="theme-mode-label">ডার্ক মোড</span>
    </button>
  </div>

  <div class="register-container">

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

    <!-- ================= RIGHT PANEL: REGISTER FORM ================= -->
    <div class="form-side-panel">
      <div class="brand-header-area">
        <div class="brand-title">NEWS<span class="hl-green">LENS</span><span class="badge-red">BD</span><span class="badge-admin">ADMIN</span></div>
        <p class="brand-subtitle" id="txt-brand-sub" data-bn="নতুন অ্যাকাউন্ট নিবন্ধন (Admin & Editor Registration)" data-en="New Account Registration (Admin & Editor Desk)">নতুন অ্যাকাউন্ট নিবন্ধন (Admin & Editor Registration)</p>
      </div>

      <?php if (!empty($error)): ?>
        <div class="alert-box alert-error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form action="<?= $appUrl ?>/<?= $adminPath ?>/register" method="post" id="admin-register-form">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        
        <div class="form-group">
          <label for="name" id="txt-label-name" data-bn="সম্পূর্ণ নাম *" data-en="Full Name *">সম্পূর্ণ নাম *</label>
          <input type="text" name="name" id="name" class="form-control" required placeholder="আপনার পুরো নাম লিখুন" data-placeholder-bn="আপনার পুরো নাম লিখুন" data-placeholder-en="Enter your full name" value="<?= htmlspecialchars($formData['name'] ?? '') ?>" autocomplete="name">
        </div>

        <div class="form-group">
          <label for="phone" id="txt-label-phone" data-bn="মোবাইল / ফোন নম্বর *" data-en="Mobile / Phone Number *">মোবাইল / ফোন নম্বর *</label>
          <input type="tel" name="phone" id="phone" class="form-control" required placeholder="যেমন: 01700000000" data-placeholder-bn="যেমন: 01700000000" data-placeholder-en="e.g. 01700000000" value="<?= htmlspecialchars($formData['phone'] ?? '') ?>" autocomplete="tel">
        </div>

        <div class="form-group">
          <label for="email" id="txt-label-email" data-bn="ইমেইল ঠিকানা *" data-en="Email Address *">ইমেইল ঠিকানা *</label>
          <input type="email" name="email" id="email" class="form-control" required placeholder="name@domain.com" data-placeholder-bn="name@domain.com" data-placeholder-en="name@domain.com" value="<?= htmlspecialchars($formData['email'] ?? '') ?>" autocomplete="email">
        </div>

        <div class="form-group">
          <label for="password" id="txt-label-pass" data-bn="পাসওয়ার্ড (কমপক্ষে ৬ অক্ষর) *" data-en="Password (min 6 characters) *">পাসওয়ার্ড (কমপক্ষে ৬ অক্ষর) *</label>
          <div class="password-input-wrap">
            <input type="password" name="password" id="password" class="form-control" required placeholder="••••••••" autocomplete="new-password">
            <button type="button" class="btn-toggle-pass" aria-label="পাসওয়ার্ড দেখুন" title="পাসওয়ার্ড দেখুন">
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

        <div class="form-group">
          <label for="password_confirm" id="txt-label-pass-confirm" data-bn="পাসওয়ার্ড নিশ্চিত করুন *" data-en="Confirm Password *">পাসওয়ার্ড নিশ্চিত করুন *</label>
          <div class="password-input-wrap">
            <input type="password" name="password_confirm" id="password_confirm" class="form-control" required placeholder="••••••••" autocomplete="new-password">
            <button type="button" class="btn-toggle-pass" aria-label="পাসওয়ার্ড দেখুন" title="পাসওয়ার্ড দেখুন">
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

        <button type="submit" class="btn-register-submit" id="txt-btn-register" data-bn="অ্যাকাউন্ট তৈরি করুন ➔" data-en="Create Account ➔">অ্যাকাউন্ট তৈরি করুন ➔</button>
      </form>

      <div class="bottom-hint-row">
        <span id="txt-hint-have" data-bn="ইতিমধ্যে একটি অ্যাকাউন্ট আছে?" data-en="Already have an account?">ইতিমধ্যে একটি অ্যাকাউন্ট আছে?</span> 
        <a href="<?= $appUrl ?>/<?= $adminPath ?>/login" id="txt-hint-login" data-bn="লগইন করুন ➔" data-en="Login here ➔">লগইন করুন ➔</a>
      </div>
    </div>

  </div>

  <script>
    // Dynamic Theme & Bilingual (BN/EN) Controller for Register Page
    document.addEventListener('DOMContentLoaded', () => {
      const htmlRoot = document.documentElement;
      const themeToggle = document.getElementById('reg-theme-toggle');
      const themeIcon = document.getElementById('theme-icon');
      const themeLabel = document.getElementById('theme-mode-label');
      const langToggle = document.getElementById('reg-lang-toggle');
      const langLabel = document.getElementById('reg-lang-label');

      // 1. Language Controller (Bangla / English)
      const applyLang = (lang) => {
        htmlRoot.setAttribute('lang', lang);
        localStorage.setItem('admin_lang', lang);
        document.cookie = 'admin_lang=' + lang + '; path=/; max-age=31536000; SameSite=Lax';

        if (langLabel) {
          langLabel.textContent = lang === 'en' ? 'বাংলা' : 'EN';
        }

        // Title translation
        const pageTitle = document.querySelector('title[data-bn][data-en]');
        if (pageTitle) {
          document.title = lang === 'en' ? pageTitle.getAttribute('data-en') : pageTitle.getAttribute('data-bn');
        }

        // Translate text elements
        document.querySelectorAll('[data-bn][data-en]').forEach(el => {
          if (el.tagName !== 'TITLE') {
            el.textContent = lang === 'en' ? el.getAttribute('data-en') : el.getAttribute('data-bn');
          }
        });

        // Translate placeholders
        document.querySelectorAll('[data-placeholder-bn][data-placeholder-en]').forEach(el => {
          el.placeholder = lang === 'en' ? el.getAttribute('data-placeholder-en') : el.getAttribute('data-placeholder-bn');
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

      // 3. Password Visibility Toggles for All Password Inputs
      document.querySelectorAll('.btn-toggle-pass').forEach(btn => {
        btn.addEventListener('click', () => {
          const wrap = btn.closest('.password-input-wrap');
          const passInput = wrap?.querySelector('input');
          if (!passInput) return;

          const isPassword = passInput.getAttribute('type') === 'password';
          const isEn = (localStorage.getItem('admin_lang') || 'bn') === 'en';
          const eyeOpen = btn.querySelector('.eye-open');
          const eyeClosed = btn.querySelector('.eye-closed');

          if (isPassword) {
            passInput.setAttribute('type', 'text');
            if (eyeOpen) eyeOpen.style.display = 'none';
            if (eyeClosed) eyeClosed.style.display = 'block';
            btn.classList.add('active');
            btn.setAttribute('title', isEn ? 'Hide Password' : 'পাসওয়ার্ড লুকান');
            btn.setAttribute('aria-label', isEn ? 'Hide Password' : 'পাসওয়ার্ড লুকান');
          } else {
            passInput.setAttribute('type', 'password');
            if (eyeOpen) eyeOpen.style.display = 'block';
            if (eyeClosed) eyeClosed.style.display = 'none';
            btn.classList.remove('active');
            btn.setAttribute('title', isEn ? 'Show Password' : 'পাসওয়ার্ড দেখুন');
            btn.setAttribute('aria-label', isEn ? 'Show Password' : 'পাসওয়ার্ড দেখুন');
          }
        });
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

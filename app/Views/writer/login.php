<?php
$appUrl = rtrim($appUrl ?? '', '/');
$csrfToken = $csrfToken ?? '';
$error = $error ?? '';
?>
<!DOCTYPE html>
<html lang="bn" data-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>লেখক লগইন পোর্টাল | Newslensbd Writer Portal</title>
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
      --accent-cyan: #38bdf8;
      
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

    .lang-toggle-btn, .theme-toggle-btn {
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      color: var(--text-main);
      padding: 6px 12px;
      border-radius: 20px;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 0.85rem;
      font-weight: 700;
      transition: all 0.2s;
    }
    .lang-toggle-btn:hover, .theme-toggle-btn:hover {
      border-color: var(--map-fill-primary);
    }

    /* Outer Container: 2 Columns Split */
    .login-container {
      width: 100%;
      max-width: 1060px;
      background-color: var(--bg-card);
      border-radius: 20px;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
      border: 1px solid var(--border-color);
      overflow: hidden;
      display: grid;
      grid-template-columns: 1.15fr 0.95fr;
      margin: 40px auto 20px auto;
      transition: all 0.3s ease;
    }

    @media (max-width: 900px) {
      .login-container {
        grid-template-columns: 1fr;
        max-width: 480px;
        margin-top: 60px;
      }
      .map-side-panel {
        display: none !important;
      }
    }

    /* LEFT SIDE: MAP PANEL */
    .map-side-panel {
      background: radial-gradient(circle at 50% 35%, rgba(14, 122, 58, 0.12) 0%, rgba(7, 22, 48, 0.6) 80%);
      padding: 36px 32px 30px 32px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      border-right: 1px solid var(--border-color);
      position: relative;
      overflow: hidden;
    }
    [data-theme="light"] .map-side-panel {
      background: radial-gradient(circle at 50% 35%, rgba(14, 122, 58, 0.08) 0%, rgba(241, 245, 249, 0.8) 85%);
    }

    .map-header-badge {
      margin-bottom: 12px;
      z-index: 5;
    }
    .map-tag {
      display: inline-block;
      font-size: 0.76rem;
      font-weight: 800;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      padding: 4px 10px;
      border-radius: 20px;
      background: rgba(14, 122, 58, 0.2);
      color: #4ade80;
      border: 1px solid rgba(14, 122, 58, 0.4);
      margin-bottom: 8px;
    }
    [data-theme="light"] .map-tag {
      background: rgba(14, 122, 58, 0.12);
      color: #0E7A3A;
      border-color: rgba(14, 122, 58, 0.3);
    }
    .map-headline {
      font-size: 1.15rem;
      font-weight: 800;
      color: var(--text-main);
      line-height: 1.35;
    }

    .map-canvas-wrap {
      width: 100%;
      height: 380px;
      display: flex;
      justify-content: center;
      align-items: center;
      position: relative;
      margin: 8px 0;
    }

    /* SVG MAP STYLES */
    .bd-vector-map {
      width: 100%;
      height: 100%;
      max-height: 380px;
      filter: drop-shadow(0 10px 25px var(--map-glow));
      transition: filter 0.4s ease;
    }

    .bd-division {
      fill: url(#bdGradient);
      stroke: var(--map-stroke);
      stroke-width: 1.5;
      stroke-linejoin: round;
      cursor: pointer;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .bd-division:hover {
      fill: var(--map-fill-primary) !important;
      filter: brightness(1.25);
      stroke: #ffffff;
      stroke-width: 2.5;
    }

    .bd-sea {
      fill: url(#seaGradient);
      opacity: 0.85;
    }
    .bd-sea-wave {
      stroke: rgba(255, 255, 255, 0.35);
      stroke-width: 1.2;
      fill: none;
      stroke-dasharray: 6, 6;
    }
    .bd-sea-label {
      fill: #e0f2fe;
      font-size: 26px;
      font-weight: 800;
      letter-spacing: 0.08em;
      opacity: 0.9;
    }

    .bd-river {
      fill: none;
      stroke: #38bdf8;
      opacity: 0.65;
    }
    .bd-river-main {
      stroke-width: 3.5;
    }

    .div-label {
      fill: #ffffff;
      font-size: 26px;
      font-weight: 800;
      text-shadow: 0 2px 5px rgba(0,0,0,0.85);
      pointer-events: none;
    }
    .div-label-en {
      font-size: 19px;
      font-weight: 700;
      fill: #e2e8f0;
      opacity: 0.95;
    }

    /* RIGHT SIDE: FORM PANEL */
    .form-side-panel {
      padding: 44px 38px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .brand-header-area {
      text-align: center;
      margin-bottom: 26px;
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
    }
    .brand-title .hl-green { color: #0E7A3A; }
    .brand-title .badge-writer {
      background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
      color: #ffffff;
      font-size: 0.72rem;
      font-weight: 800;
      padding: 3px 9px;
      border-radius: 6px;
      margin-left: 8px;
      letter-spacing: 0.05em;
      text-transform: uppercase;
      box-shadow: 0 2px 8px rgba(2, 132, 199, 0.4);
    }
    .brand-subtitle {
      font-size: 0.92rem;
      color: var(--text-muted);
      margin-top: 6px;
    }

    .form-group {
      margin-bottom: 18px;
    }
    .form-group label {
      display: block;
      font-size: 0.88rem;
      font-weight: 700;
      margin-bottom: 7px;
      color: var(--text-main);
    }
    .form-control {
      width: 100%;
      background: var(--bg-input);
      border: 1.5px solid var(--border-color);
      border-radius: 8px;
      padding: 12px 14px;
      color: var(--text-main);
      font-size: 0.95rem;
      font-family: inherit;
      outline: none;
      transition: all 0.2s;
    }
    .form-control:focus {
      border-color: #0284c7;
      box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.25);
    }

    .password-input-wrap {
      position: relative;
      display: flex;
      align-items: center;
    }
    .password-input-wrap .form-control {
      padding-right: 48px;
    }
    .btn-toggle-pass {
      position: absolute;
      right: 6px;
      top: 50%;
      transform: translateY(-50%);
      background: transparent;
      border: none;
      cursor: pointer;
      padding: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--text-muted);
      border-radius: 6px;
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

    .btn-login-submit {
      width: 100%;
      background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
      color: #ffffff;
      border: none;
      padding: 13px;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 800;
      cursor: pointer;
      margin-top: 8px;
      box-shadow: 0 4px 14px rgba(2, 132, 199, 0.35);
      transition: all 0.2s ease;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
    }
    .btn-login-submit:hover {
      background: linear-gradient(135deg, #0369a1 0%, #075985 100%);
      transform: translateY(-1px);
      box-shadow: 0 6px 18px rgba(2, 132, 199, 0.45);
    }

    .alert-box {
      padding: 11px 15px;
      border-radius: 8px;
      font-size: 0.88rem;
      margin-bottom: 18px;
      text-align: center;
      font-weight: 600;
      line-height: 1.4;
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
      margin-top: 22px;
      text-align: center;
      font-size: 0.86rem;
      color: var(--text-muted);
      border-top: 1px solid var(--border-color);
      padding-top: 16px;
    }
    .bottom-hint-row a {
      color: #38bdf8;
      text-decoration: none;
      font-weight: 700;
    }
    .bottom-hint-row a:hover { text-decoration: underline; }

    .writer-notice-badge {
      display: flex;
      align-items: flex-start;
      gap: 8px;
      background: rgba(2, 132, 199, 0.1);
      border: 1px solid rgba(2, 132, 199, 0.3);
      padding: 10px 14px;
      border-radius: 8px;
      font-size: 0.84rem;
      color: #7dd3fc;
      margin-top: 16px;
      text-align: left;
    }
    [data-theme="light"] .writer-notice-badge {
      background: rgba(2, 132, 199, 0.08);
      border-color: rgba(2, 132, 199, 0.25);
      color: #0369a1;
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
        <span class="map-tag" id="txt-map-tag" data-bn="✍️ জাতীয় লেখক ও কন্ট্রিবিউটর ডেস্ক" data-en="✍️ National Writer & Contributor Desk">
          ✍️ জাতীয় লেখক ও কন্ট্রিবিউটর ডেস্ক
        </span>
        <h3 class="map-headline" id="txt-map-headline" data-bn="৮ বিভাগ ও ৬৪ জেলার লেখক নেটওয়ার্ক" data-en="Writer Network for 8 Divisions & 64 Districts">
          ৮ বিভাগ ও ৬৪ জেলার লেখক নেটওয়ার্ক
        </h3>
      </div>

      <!-- Vector SVG Map of Bangladesh with Rivers, Bay of Bengal & Divisions -->
      <div class="map-canvas-wrap">
        <?php require dirname(__DIR__) . '/admin/partials/bd_map_svg.php'; ?>
      </div>
    </div>

    <!-- ================= RIGHT PANEL: LOGIN FORM ================= -->
    <div class="form-side-panel">
      <div class="brand-header-area">
        <div class="brand-title">NEWS<span class="hl-green">LENS</span><span class="badge-writer">WRITER</span></div>
        <p class="brand-subtitle" id="txt-brand-sub" data-bn="স্বতন্ত্র লেখক লগইন ও কনটেন্ট ম্যানেজমেন্ট পোর্টাল" data-en="Dedicated Writer Login & Content Management Portal">স্বতন্ত্র লেখক লগইন ও কনটেন্ট ম্যানেজমেন্ট পোর্টাল</p>
      </div>

      <?php if (isset($_GET['logged_out'])): ?>
        <div class="alert-box alert-success" style="background: rgba(14, 122, 58, 0.2); border: 1px solid #10b981; color: #86efac;">
          ✅ আপনি সফলভাবে লেখক পোর্টাল থেকে লগআউট হয়েছেন।
        </div>
      <?php endif; ?>

      <?php if (isset($_GET['notice']) && $_GET['notice'] === 'writer_login_required'): ?>
        <div class="alert-box alert-error" style="background: rgba(2, 132, 199, 0.15); border: 1px solid #0284c7; color: #38bdf8;">
          ℹ️ আপনি একজন লেখক। আপনার অ্যাকাউন্টে প্রবেশ করতে এই ডেডিকেটেড লেখক পোর্টাল ব্যবহার করুন।
        </div>
      <?php endif; ?>

      <?php if (!empty($error)): ?>
        <div class="alert-box alert-error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <!-- Login Form: Strictly autocomplete="off" with no prefilled credentials -->
      <form action="<?= $appUrl ?>/writer/login" method="post" id="writer-login-form" autocomplete="off">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        
        <div class="form-group">
          <label for="writer-email" id="txt-label-email" data-bn="লেখকের অফিসিয়াল ইমেইল" data-en="Writer Official Email">লেখকের অফিসিয়াল ইমেইল</label>
          <input 
            type="email" 
            name="email" 
            id="writer-email" 
            class="form-control" 
            required 
            placeholder="writer@domain.com" 
            autocomplete="off" 
            value=""
          >
        </div>

        <div class="form-group">
          <label for="writer-password" id="txt-label-pass" data-bn="গোপন পাসওয়ার্ড" data-en="Secret Password">গোপন পাসওয়ার্ড</label>
          <div class="password-input-wrap">
            <input 
              type="password" 
              name="password" 
              id="writer-password" 
              class="form-control" 
              required 
              placeholder="••••••••" 
              autocomplete="new-password" 
              value=""
            >
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

        <button type="submit" class="btn-login-submit" id="txt-btn-login" data-bn="লেখক পোর্টালে প্রবেশ করুন ➔" data-en="Enter Writer Portal ➔">লেখক পোর্টালে প্রবেশ করুন ➔</button>
      </form>

      <div class="writer-notice-badge">
        <span>🛡️</span>
        <span id="txt-writer-notice" data-bn="পাবলিক লেখক নিবন্ধন বন্ধ রয়েছে। শুধুমাত্র অ্যাডমিন কর্তৃক অনুমোদিত লেখকগণ এই পোর্টাল ব্যবহার করতে পারবেন।" data-en="Public writer registration is closed. Only authorized writers created by Admin may log in here.">
          পাবলিক লেখক নিবন্ধন বন্ধ রয়েছে। শুধুমাত্র অ্যাডমিন কর্তৃক অনুমোদিত লেখকগণ এই পোর্টাল ব্যবহার করতে পারবেন।
        </span>
      </div>

      <div class="bottom-hint-row">
        <span id="txt-hint-reader" data-bn="আপনি কি সাধারণ পাঠক?" data-en="Are you a general reader?">আপনি কি সাধারণ পাঠক?</span> 
        <a href="<?= $appUrl ?>/login" id="txt-hint-reader-link" data-bn="পাঠক লগইন করুন ➔" data-en="Reader Login ➔">পাঠক লগইন করুন ➔</a>
      </div>
    </div>

  </div>

  <script>
    // Dynamic Theme & Bilingual (BN/EN) Controller for Writer Login Page
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
        localStorage.setItem('writer_lang', lang);

        if (langLabel) {
          langLabel.textContent = lang === 'en' ? 'বাংলা' : 'EN';
        }

        // Translate all data-bn / data-en attributes on the page
        document.querySelectorAll('[data-bn][data-en]').forEach(el => {
          el.textContent = lang === 'en' ? el.getAttribute('data-en') : el.getAttribute('data-bn');
        });

        // Translate placeholders
        const emailInput = document.getElementById('writer-email');
        if (emailInput) {
          emailInput.placeholder = lang === 'en' ? 'writer@domain.com' : 'লেখকের ইমেইল লিখুন';
        }
      };

      // Load saved language
      const savedLang = localStorage.getItem('writer_lang') || 'bn';
      applyLang(savedLang);

      if (langToggle) {
        langToggle.addEventListener('click', () => {
          const currentLang = htmlRoot.getAttribute('lang') || 'bn';
          const newLang = currentLang === 'bn' ? 'en' : 'bn';
          applyLang(newLang);
        });
      }

      // 2. Dynamic Theme Controller (Dark / Light)
      const applyTheme = (theme) => {
        htmlRoot.setAttribute('data-theme', theme);
        localStorage.setItem('writer_theme', theme);

        if (theme === 'light') {
          if (themeIcon) themeIcon.textContent = '☀️';
          if (themeLabel) themeLabel.textContent = (htmlRoot.getAttribute('lang') === 'en') ? 'Light Mode' : 'লাইট মোড';
        } else {
          if (themeIcon) themeIcon.textContent = '🌙';
          if (themeLabel) themeLabel.textContent = (htmlRoot.getAttribute('lang') === 'en') ? 'Dark Mode' : 'ডার্ক মোড';
        }
      };

      // Load saved theme
      const savedTheme = localStorage.getItem('writer_theme') || 'dark';
      applyTheme(savedTheme);

      if (themeToggle) {
        themeToggle.addEventListener('click', () => {
          const currentTheme = htmlRoot.getAttribute('data-theme') || 'dark';
          const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
          applyTheme(newTheme);
        });
      }

      // 3. Password Visibility Toggle
      const passInput = document.getElementById('writer-password');
      const passToggle = document.getElementById('btn-toggle-pass');
      if (passInput && passToggle) {
        const eyeOpen = passToggle.querySelector('.eye-open');
        const eyeClosed = passToggle.querySelector('.eye-closed');

        passToggle.addEventListener('click', () => {
          const isPassword = passInput.type === 'password';
          passInput.type = isPassword ? 'text' : 'password';
          if (eyeOpen && eyeClosed) {
            eyeOpen.style.display = isPassword ? 'none' : 'block';
            eyeClosed.style.display = isPassword ? 'block' : 'none';
          }
        });
      }

      // 4. Force clear autofill on load
      setTimeout(() => {
        const emailEl = document.getElementById('writer-email');
        const passEl = document.getElementById('writer-password');
        if (emailEl && !emailEl.getAttribute('data-typed')) {
          // ensure no ghost prefill
        }
      }, 50);
    });
  </script>
</body>
</html>

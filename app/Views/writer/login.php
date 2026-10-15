<?php
$appUrl = rtrim($appUrl ?? $config['app']['url'] ?? '', '/');
$csrfToken = $csrfToken ?? '';
$error = $error ?? '';
$info = $info ?? '';
$success = $success ?? '';
$prefillEmail = $prefillEmail ?? '';
?>
<!DOCTYPE html>
<html lang="bn" data-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>লেখক লগইন পোর্টাল | Newslensbd Writer Portal</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --bg-page: #071630;
      --bg-card: #0c1e3d;
      --bg-input: #071224;
      --border-color: #173567;
      --text-main: #f1f5f9;
      --text-muted: #94a3b8;
      --primary-accent: #0E7A3A;
      --primary-hover: #0a5c2b;
      --color-sky: #38bdf8;
      --color-red: #ef4444;
      --shadow-card: 0 20px 50px rgba(0, 0, 0, 0.45);
    }

    [data-theme="light"] {
      --bg-page: #f0fdf4;
      --bg-card: #ffffff;
      --bg-input: #f8fafc;
      --border-color: #cbd5e1;
      --text-main: #0f172a;
      --text-muted: #64748b;
      --primary-accent: #0E7A3A;
      --primary-hover: #085327;
      --color-sky: #0284c7;
      --color-red: #dc2626;
      --shadow-card: 0 16px 40px rgba(14, 122, 58, 0.12);
    }

    body {
      font-family: 'Hind Siliguri', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
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
      overflow-x: hidden;
    }

    /* Ambient background glow */
    .bg-glow-1 {
      position: absolute;
      top: -100px;
      left: -100px;
      width: 450px;
      height: 450px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(14, 122, 58, 0.25) 0%, rgba(7, 22, 48, 0) 70%);
      pointer-events: none;
      z-index: 0;
    }
    .bg-glow-2 {
      position: absolute;
      bottom: -120px;
      right: -80px;
      width: 500px;
      height: 500px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(56, 189, 248, 0.18) 0%, rgba(7, 22, 48, 0) 70%);
      pointer-events: none;
      z-index: 0;
    }

    /* Top Utility Bar (Bilingual Switcher & Dark/Light Toggle) */
    .top-controls {
      position: absolute;
      top: 20px;
      right: 24px;
      display: flex;
      align-items: center;
      gap: 12px;
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
      padding: 7px 14px;
      border-radius: 20px;
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      transition: all 0.2s;
    }
    .btn-return-portal:hover {
      color: var(--text-main);
      border-color: var(--primary-accent);
    }

    .theme-toggle-btn, .lang-toggle-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      border-radius: 24px;
      padding: 7px 14px;
      cursor: pointer;
      color: var(--text-main);
      font-size: 0.85rem;
      font-weight: 700;
      transition: all 0.2s;
    }
    .theme-toggle-btn:hover, .lang-toggle-btn:hover {
      border-color: var(--primary-accent);
    }

    /* Login Box Container */
    .writer-login-card {
      position: relative;
      z-index: 10;
      width: 100%;
      max-width: 480px;
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      border-radius: 22px;
      padding: 40px 34px;
      box-shadow: var(--shadow-card);
      overflow: hidden;
      transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .top-accent-line {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 5px;
      background: linear-gradient(90deg, #0E7A3A 0%, #38bdf8 50%, #10b981 100%);
    }

    .brand-header {
      text-align: center;
      margin-bottom: 28px;
    }

    .brand-badge {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 60px;
      height: 60px;
      border-radius: 18px;
      background: rgba(14, 122, 58, 0.15);
      border: 1.5px solid rgba(14, 122, 58, 0.35);
      color: #34d399;
      font-size: 28px;
      margin-bottom: 14px;
      box-shadow: 0 6px 16px rgba(14, 122, 58, 0.2);
    }

    .portal-title {
      font-size: 1.65rem;
      font-weight: 800;
      color: var(--text-main);
      margin-bottom: 6px;
      letter-spacing: -0.3px;
    }

    .portal-subtitle {
      color: var(--text-muted);
      font-size: 0.92rem;
      line-height: 1.45;
    }

    .role-isolated-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(14, 122, 58, 0.12);
      border: 1px solid rgba(14, 122, 58, 0.3);
      color: #34d399;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.78rem;
      font-weight: 700;
      margin-top: 10px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    [data-theme="light"] .role-isolated-pill {
      color: #0E7A3A;
      background: rgba(14, 122, 58, 0.1);
    }

    /* Alerts */
    .alert-box {
      padding: 12px 16px;
      border-radius: 10px;
      font-size: 0.88rem;
      font-weight: 600;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .alert-error {
      background: rgba(239, 68, 68, 0.14);
      border: 1px solid var(--color-red);
      color: #fca5a5;
    }
    [data-theme="light"] .alert-error {
      color: #dc2626;
      background: #fee2e2;
    }

    .alert-info {
      background: rgba(56, 189, 248, 0.14);
      border: 1px solid var(--color-sky);
      color: #7dd3fc;
    }
    [data-theme="light"] .alert-info {
      color: #0284c7;
      background: #e0f2fe;
    }

    .alert-success {
      background: rgba(16, 185, 129, 0.14);
      border: 1px solid #10b981;
      color: #86efac;
    }
    [data-theme="light"] .alert-success {
      color: #059669;
      background: #d1fae5;
    }

    /* Form Styles */
    .form-group {
      margin-bottom: 20px;
    }

    .form-label {
      display: block;
      font-weight: 700;
      font-size: 0.88rem;
      margin-bottom: 8px;
      color: var(--text-main);
    }

    .input-wrapper {
      position: relative;
    }

    .input-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 1rem;
      color: var(--text-muted);
      pointer-events: none;
    }

    .form-control {
      width: 100%;
      padding: 13px 44px 13px 42px;
      background: var(--bg-input);
      border: 1.5px solid var(--border-color);
      border-radius: 11px;
      font-size: 0.95rem;
      color: var(--text-main);
      font-family: inherit;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
    }
    .form-control:focus {
      border-color: var(--primary-accent);
      box-shadow: 0 0 0 3px rgba(14, 122, 58, 0.25);
    }

    .pass-toggle-btn {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      background: transparent;
      border: none;
      font-size: 1.15rem;
      cursor: pointer;
      color: var(--text-muted);
      padding: 4px;
      border-radius: 6px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .btn-writer-submit {
      width: 100%;
      background: linear-gradient(135deg, #0E7A3A 0%, #0a5c2b 100%);
      color: #ffffff;
      border: none;
      padding: 14px;
      border-radius: 12px;
      font-size: 1.05rem;
      font-weight: 800;
      cursor: pointer;
      transition: all 0.25s ease;
      box-shadow: 0 6px 20px rgba(14, 122, 58, 0.4);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-top: 6px;
      font-family: inherit;
    }
    .btn-writer-submit:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 26px rgba(14, 122, 58, 0.5);
    }

    /* Admin-only Writer Notice */
    .security-notice-card {
      margin-top: 26px;
      padding: 12px 16px;
      background: rgba(255, 255, 255, 0.03);
      border: 1px dashed var(--border-color);
      border-radius: 12px;
      font-size: 0.82rem;
      color: var(--text-muted);
      line-height: 1.5;
      text-align: center;
    }

    .footer-link-group {
      text-align: center;
      margin-top: 22px;
      font-size: 0.88rem;
      color: var(--text-muted);
    }
    .footer-link-group a {
      color: var(--color-sky);
      text-decoration: none;
      font-weight: 700;
    }
    .footer-link-group a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="bg-glow-1"></div>
  <div class="bg-glow-2"></div>

  <!-- Top Utilities Bar: Language Toggle (EN/বাংলা) & Theme Toggle (Dark/Light) -->
  <div class="top-controls">
    <a href="<?= $appUrl ?>/" class="btn-return-portal" title="মূল সাইটে ফিরে যান">
      <span>←</span>
      <span data-bn="লাইভ পোর্টাল" data-en="Live Portal">লাইভ পোর্টাল</span>
    </a>

    <!-- Theme Mode Switcher -->
    <button type="button" class="theme-toggle-btn" id="writerThemeToggle" aria-label="থিম পরিবর্তন">
      <span id="writerThemeIcon">🌙</span>
      <span id="writerThemeLabel" data-bn="ডার্ক মোড" data-en="Dark Mode">ডার্ক মোড</span>
    </button>

    <!-- Language Toggle -->
    <button type="button" class="lang-toggle-btn" id="writerLangToggle" aria-label="Switch Language">
      <span>🌐</span>
      <span id="writerLangLabel">English</span>
    </button>
  </div>

  <div class="writer-login-card">
    <div class="top-accent-line"></div>

    <!-- Header & Badge -->
    <div class="brand-header">
      <div class="brand-badge">✍️</div>
      <h1 class="portal-title" data-bn="লেখক লগইন পোর্টাল" data-en="Writer Login Portal">লেখক লগইন পোর্টাল</h1>
      <p class="portal-subtitle" data-bn="সংবাদ ও মতামত প্রকাশের জন্য লেখক অ্যাকাউন্টে প্রবেশ করুন" data-en="Sign in to your dedicated Writer Account to publish articles">সংবাদ ও মতামত প্রকাশের জন্য লেখক অ্যাকাউন্টে প্রবেশ করুন</p>
      <div class="role-isolated-pill" data-bn="🛡️ স্বতন্ত্র লেখক সিস্টেম" data-en="🛡️ Dedicated Writer System">
        🛡️ স্বতন্ত্র লেখক সিস্টেম
      </div>
    </div>

    <!-- Alerts -->
    <?php if (!empty($success)): ?>
      <div class="alert-box alert-success">
        <span>✓</span>
        <div><?= htmlspecialchars($success) ?></div>
      </div>
    <?php endif; ?>

    <?php if (!empty($info)): ?>
      <div class="alert-box alert-info">
        <span>ℹ️</span>
        <div><?= htmlspecialchars($info) ?></div>
      </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
      <div class="alert-box alert-error">
        <span>✕</span>
        <div><?= htmlspecialchars($error) ?></div>
      </div>
    <?php endif; ?>

    <!-- Form -->
    <form method="POST" action="<?= $appUrl ?>/writer/login" id="writerLoginForm">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

      <!-- Email Input -->
      <div class="form-group">
        <label for="writer-email" class="form-label" data-bn="লেখকের অফিসিয়াল ইমেইল *" data-en="Writer Official Email *">
          লেখকের অফিসিয়াল ইমেইল <span style="color: var(--color-red);">*</span>
        </label>
        <div class="input-wrapper">
          <span class="input-icon">📧</span>
          <input 
            type="email" 
            name="email" 
            id="writer-email" 
            required 
            value="<?= htmlspecialchars($prefillEmail) ?>"
            placeholder="writer@newslensbd.com" 
            autocomplete="email"
            class="form-control"
          >
        </div>
      </div>

      <!-- Password Input with Toggle -->
      <div class="form-group">
        <label for="writer-password" class="form-label" data-bn="পাসওয়ার্ড *" data-en="Password *">
          পাসওয়ার্ড <span style="color: var(--color-red);">*</span>
        </label>
        <div class="input-wrapper">
          <span class="input-icon">🔒</span>
          <input 
            type="password" 
            name="password" 
            id="writer-password" 
            required 
            placeholder="••••••••" 
            autocomplete="current-password"
            class="form-control"
          >
          <button 
            type="button" 
            onclick="toggleWriterPassword('writer-password', this)"
            title="পাসওয়ার্ড দেখুন বা লুকান"
            aria-label="পাসওয়ার্ড দেখুন বা লুকান"
            class="pass-toggle-btn"
          >
            👁️
          </button>
        </div>
      </div>

      <!-- Submit Button -->
      <button type="submit" class="btn-writer-submit" id="writerSubmitBtn">
        <span data-bn="লেখক প্যানেলে প্রবেশ করুন" data-en="Enter Writer Panel">লেখক প্যানেলে প্রবেশ করুন</span>
        <span>➔</span>
      </button>
    </form>

    <!-- Security & Policy Notice -->
    <div class="security-notice-card">
      <span style="font-size: 1rem;">🔒</span>
      <span data-bn="লেখক অ্যাকাউন্টসমূহ শুধুমাত্র অ্যাডমিন কর্তৃপক্ষ সরাসরি তৈরি করে থাকেন। নতুন অ্যাকাউন্ট বা পাসওয়ার্ড রিসেটের জন্য প্রধান সম্পাদকের সাথে যোগাযোগ করুন।" data-en="Writer accounts are strictly provisioned by Admin. Contact the Editor-in-Chief for new credentials or password resets.">
        লেখক অ্যাকাউন্টসমূহ শুধুমাত্র অ্যাডমিন কর্তৃপক্ষ সরাসরি তৈরি করে থাকেন। নতুন অ্যাকাউন্ট বা পাসওয়ার্ড রিসেটের জন্য প্রধান সম্পাদকের সাথে যোগাযোগ করুন।
      </span>
    </div>

    <!-- Reader Login Link -->
    <div class="footer-link-group">
      <span data-bn="আপনি কি সাধারণ পাঠক?" data-en="Are you a regular reader?">আপনি কি সাধারণ পাঠক?</span>
      <a href="<?= $appUrl ?>/login" data-bn="পাঠক লগইন এখানে ➔" data-en="Reader Login Here ➔">পাঠক লগইন এখানে ➔</a>
    </div>

  </div>

  <script>
    // Password visibility toggle
    function toggleWriterPassword(inputId, btn) {
      const input = document.getElementById(inputId);
      if (!input) return;
      if (input.type === 'password') {
        input.type = 'text';
        btn.textContent = '🙈';
        btn.style.color = 'var(--primary-accent)';
      } else {
        input.type = 'password';
        btn.textContent = '👁️';
        btn.style.color = 'var(--text-muted)';
      }
    }

    // Bilingual & Dark/Light Theme Controller
    (function() {
      const htmlRoot = document.documentElement;
      const themeToggle = document.getElementById('writerThemeToggle');
      const themeIcon = document.getElementById('writerThemeIcon');
      const themeLabel = document.getElementById('writerThemeLabel');
      const langToggle = document.getElementById('writerLangToggle');
      const langLabel = document.getElementById('writerLangLabel');

      let currentLang = localStorage.getItem('nl_lang') || 'bn';
      let currentTheme = localStorage.getItem('nl_theme') || 'dark';

      function applyTheme(theme) {
        htmlRoot.setAttribute('data-theme', theme);
        localStorage.setItem('nl_theme', theme);
        currentTheme = theme;
        if (theme === 'light') {
          if (themeIcon) themeIcon.textContent = '☀️';
          if (themeLabel) themeLabel.textContent = (currentLang === 'en') ? 'Light Mode' : 'লাইট মোড';
        } else {
          if (themeIcon) themeIcon.textContent = '🌙';
          if (themeLabel) themeLabel.textContent = (currentLang === 'en') ? 'Dark Mode' : 'ডার্ক মোড';
        }
      }

      function applyLanguage(lang) {
        currentLang = lang;
        localStorage.setItem('nl_lang', lang);
        htmlRoot.setAttribute('lang', lang);

        if (langLabel) {
          langLabel.textContent = (lang === 'bn') ? 'English' : 'বাংলা';
        }

        // Translate all elements with data-bn and data-en
        document.querySelectorAll('[data-bn]').forEach(el => {
          const text = (lang === 'en') ? el.getAttribute('data-en') : el.getAttribute('data-bn');
          if (text) {
            el.textContent = text;
          }
        });

        // Translate inputs with data-placeholder-bn
        const emailInput = document.getElementById('writer-email');
        if (emailInput) {
          emailInput.placeholder = (lang === 'en') ? 'writer@newslensbd.com' : 'writer@newslensbd.com';
        }

        applyTheme(currentTheme);
      }

      // Initial apply
      applyTheme(currentTheme);
      applyLanguage(currentLang);

      // Event listeners
      if (themeToggle) {
        themeToggle.addEventListener('click', () => {
          const next = (currentTheme === 'dark') ? 'light' : 'dark';
          applyTheme(next);
        });
      }

      if (langToggle) {
        langToggle.addEventListener('click', () => {
          const next = (currentLang === 'bn') ? 'en' : 'bn';
          applyLanguage(next);
        });
      }
    })();
  </script>
</body>
</html>

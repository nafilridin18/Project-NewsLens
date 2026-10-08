/**
 * Newslensbd Coming Soon Script
 * Features:
 *  - Real-time countdown to 23 Oct 2026 with progress bar
 *  - Bilingual switching (Bangla / English) with localStorage persistence
 *  - Navy Dark Mode / Light Mode with localStorage persistence
 *  - AJAX newsletter email subscription
 */

const LAUNCH_DATE = "2026-10-23T00:00:00+06:00";
const WINDOW_DAYS = 15;

const bnDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
const toBnNum = (n) => String(n).replace(/\d/g, d => bnDigits[d]);

document.addEventListener('DOMContentLoaded', () => {

  const htmlRoot = document.documentElement;

  // ================= 1. THEME MODE (NAVY DARK / LIGHT) =================
  const themeToggle = document.getElementById('theme-toggle');
  const themeLabel = document.getElementById('theme-label');
  
  const applyTheme = (theme) => {
    htmlRoot.setAttribute('data-theme', theme);
    localStorage.setItem('newslens_theme', theme);
    updateThemeLabel();
  };

  const updateThemeLabel = () => {
    const isDark = htmlRoot.getAttribute('data-theme') === 'dark';
    const currentLang = htmlRoot.getAttribute('lang') || 'bn';
    if (themeLabel) {
      if (isDark) {
        themeLabel.textContent = currentLang === 'bn' ? 'লাইট মোড' : 'Light Mode';
      } else {
        themeLabel.textContent = currentLang === 'bn' ? 'ডার্ক মোড' : 'Dark Mode';
      }
    }
  };

  const savedTheme = localStorage.getItem('newslens_theme') || 'light';
  applyTheme(savedTheme);

  themeToggle?.addEventListener('click', () => {
    const current = htmlRoot.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
    const nextTheme = current === 'dark' ? 'light' : 'dark';
    applyTheme(nextTheme);
  });

  // ================= 2. BILINGUAL SWITCHER (BANGLA / ENGLISH) =================
  const btnBn = document.getElementById('lang-bn');
  const btnEn = document.getElementById('lang-en');

  const applyLanguage = (lang) => {
    htmlRoot.setAttribute('lang', lang);
    localStorage.setItem('newslens_lang', lang);

    btnBn?.classList.toggle('active', lang === 'bn');
    btnEn?.classList.toggle('active', lang === 'en');

    // Update all text elements with data-bn and data-en
    document.querySelectorAll('[data-bn]').forEach(el => {
      const text = el.getAttribute('data-' + lang);
      if (text) el.innerHTML = text;
    });

    // Update placeholders
    document.querySelectorAll('[data-placeholder-bn]').forEach(el => {
      const ph = el.getAttribute('data-placeholder-' + lang);
      if (ph) el.placeholder = ph;
    });

    updateThemeLabel();
  };

  const savedLang = localStorage.getItem('newslens_lang') || 'bn';
  applyLanguage(savedLang);

  btnBn?.addEventListener('click', () => applyLanguage('bn'));
  btnEn?.addEventListener('click', () => applyLanguage('en'));

  // ================= 3. COUNTDOWN TIMER =================
  const launchTime = new Date(LAUNCH_DATE).getTime();
  const startTime = launchTime - (WINDOW_DAYS * 24 * 60 * 60 * 1000);

  const tDays = document.getElementById('t-days');
  const tHours = document.getElementById('t-hours');
  const tMins = document.getElementById('t-mins');
  const tSecs = document.getElementById('t-secs');
  const barFill = document.getElementById('bar-fill');
  const barText = document.getElementById('bar-text');

  const pad = (n) => String(n).padStart(2, '0');

  const updateCountdown = () => {
    const now = new Date().getTime();
    const distance = launchTime - now;
    const isBn = (htmlRoot.getAttribute('lang') || 'bn') === 'bn';

    if (distance <= 0) {
      if (tDays) tDays.textContent = isBn ? '০০' : '00';
      if (tHours) tHours.textContent = isBn ? '০০' : '00';
      if (tMins) tMins.textContent = isBn ? '০০' : '00';
      if (tSecs) tSecs.textContent = isBn ? '০০' : '00';
      if (barFill) barFill.style.width = '100%';
      if (barText) barText.textContent = isBn ? 'আজই লাইভ!' : 'Live Today!';
      return;
    }

    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

    const formatNum = (num) => {
      const padded = pad(num);
      return isBn ? toBnNum(padded) : padded;
    };

    if (tDays) tDays.textContent = formatNum(days);
    if (tHours) tHours.textContent = formatNum(hours);
    if (tMins) tMins.textContent = formatNum(minutes);
    if (tSecs) tSecs.textContent = formatNum(seconds);

    // Progress bar calculation
    const totalDuration = launchTime - startTime;
    const elapsed = now - startTime;
    let progressPercent = Math.min(100, Math.max(4, (elapsed / totalDuration) * 100));
    const roundedPercent = Math.round(progressPercent);

    if (barFill) barFill.style.width = roundedPercent + '%';
    if (barText) {
      if (isBn) {
        barText.textContent = `লঞ্চের পথে ${toBnNum(roundedPercent)}%`;
      } else {
        barText.textContent = `Path to launch ${roundedPercent}%`;
      }
    }
  };

  updateCountdown();
  setInterval(updateCountdown, 1000);

  // ================= 4. AJAX SUBSCRIBE FORM =================
  const form = document.getElementById('notify-form');
  const note = document.getElementById('notify-note');
  const btn = document.getElementById('notify-btn');

  if (form) {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const emailInput = document.getElementById('email');
      const email = emailInput?.value.trim();
      const isBn = (htmlRoot.getAttribute('lang') || 'bn') === 'bn';

      if (!email) return;

      btn.disabled = true;
      btn.textContent = isBn ? 'পাঠানো হচ্ছে...' : 'Sending...';

      try {
        const formData = new FormData(form);
        const res = await fetch(form.action, {
          method: 'POST',
          body: formData
        });

        const data = await res.json();
        if (data.ok) {
          form.innerHTML = `
            <div style="color:var(--color-secondary-green); font-weight:700; padding:14px; background:rgba(0,106,56,0.1); border-radius:8px; text-align:center;">
              ✓ ${isBn ? 'ধন্যবাদ! লঞ্চের দিন আপনাকে সবার আগে জানানো হবে।' : 'Thank you! You will be notified on launch day.'}
            </div>
          `;
        } else {
          if (note) {
            note.textContent = data.message || (isBn ? 'সঠিক ইমেইল লিখুন।' : 'Please enter a valid email.');
            note.style.color = 'var(--color-accent-red)';
          }
          btn.disabled = false;
          btn.textContent = isBn ? 'জানিয়ে দিন' : 'Notify Me';
        }
      } catch (err) {
        if (note) {
          note.textContent = isBn ? 'সার্ভার সমস্যা, একটু পরে চেষ্টা করুন।' : 'Server error, please try again.';
          note.style.color = 'var(--color-accent-red)';
        }
        btn.disabled = false;
        btn.textContent = isBn ? 'জানিয়ে দিন' : 'Notify Me';
      }
    });
  }

});

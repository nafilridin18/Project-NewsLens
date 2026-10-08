/**
 * Newslensbd (নিউজলেন্সবিডি) Master JavaScript
 * Vanilla ES6+ without frameworks
 */

document.addEventListener('DOMContentLoaded', () => {

  // ================= 1. DARK MODE (NAVY THEME - NO BLACK) =================
  const themeToggle = document.getElementById('theme-toggle');
  const themeText = document.getElementById('theme-text');
  const htmlRoot = document.documentElement;
  
  const updateThemeText = () => {
    const isDark = htmlRoot.getAttribute('data-theme') === 'dark';
    const lang = htmlRoot.getAttribute('lang') || 'bn';
    if (themeText) {
      themeText.textContent = isDark ? (lang === 'bn' ? 'লাইট' : 'Light') : (lang === 'bn' ? 'ডার্ক' : 'Dark');
    }
  };

  const applyTheme = (theme) => {
    htmlRoot.setAttribute('data-theme', theme);
    localStorage.setItem('newslens_theme', theme);
    updateThemeText();
  };

  const savedTheme = localStorage.getItem('newslens_theme') || 
                     (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
  applyTheme(savedTheme);

  if (themeToggle) {
    themeToggle.addEventListener('click', () => {
      const current = htmlRoot.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
      const nextTheme = current === 'dark' ? 'light' : 'dark';
      applyTheme(nextTheme);
    });
  }

  // ================= 1.5 COMPREHENSIVE BILINGUAL TRANSLATION ENGINE =================
  const btnPortalBn = document.getElementById('portal-lang-bn');
  const btnPortalEn = document.getElementById('portal-lang-en');

  const bnDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
  const enDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

  const bnToEnNum = (str) => {
    let s = String(str);
    for (let i = 0; i < 10; i++) s = s.replaceAll(bnDigits[i], enDigits[i]);
    return s;
  };

  const enToBnNum = (str) => {
    let s = String(str);
    for (let i = 0; i < 10; i++) s = s.replaceAll(enDigits[i], bnDigits[i]);
    return s;
  };

  const categoryMap = {
    'জাতীয়': 'National',
    'রাজনীতি': 'Politics',
    'সারাদেশ': 'Countrywide',
    'অর্থনীতি': 'Economy',
    'আন্তর্জাতিক': 'International',
    'খেলা': 'Sports',
    'বিনোদন': 'Entertainment',
    'বিজ্ঞান ও প্রযুক্তি': 'Tech',
    'শিক্ষা': 'Education',
    'স্বাস্থ্য': 'Health',
    'চাকরি': 'Jobs',
    'মতামত': 'Opinion',
    'লাইফস্টাইল': 'Lifestyle',
    'প্রবাস': 'Probash'
  };

  const divisionMap = {
    'ঢাকা': 'Dhaka',
    'চট্টগ্রাম': 'Chattogram',
    'রাজশাহী': 'Rajshahi',
    'খুলনা': 'Khulna',
    'বরিশাল': 'Barishal',
    'সিলেট': 'Sylhet',
    'রংপুর': 'Rangpur',
    'ময়মনসিংহ': 'Mymensingh'
  };

  const districtMap = {
    'গাজীপুর': 'Gazipur',
    'নারায়ণগঞ্জ': 'Narayanganj',
    'নরসিংদী': 'Narsingdi',
    'মুন্সীগঞ্জ': 'Munshiganj',
    'মানিকগঞ্জ': 'Manikganj',
    'টাঙ্গাইল': 'Tangail',
    'কিশোরগঞ্জ': 'Kishoreganj',
    'ফরিদপুর': 'Faridpur',
    'গোপালগঞ্জ': 'Gopalganj',
    'মাদারীপুর': 'Madaripur',
    'রাজবাড়ী': 'Rajbari',
    'শরীয়তপুর': 'Shariatpur',
    'কক্সবাজার': "Cox's Bazar",
    'কুমিল্লা': 'Cumilla',
    'ফেনী': 'Feni',
    'ব্রাহ্মণবাড়িয়া': 'Brahmanbaria',
    'নোয়াখালী': 'Noakhali',
    'চাঁদপুর': 'Chandpur',
    'লক্ষ্মীপুর': 'Lakshmipur',
    'বগুড়া': 'Bogura',
    'জয়পুরহাট': 'Joypurhat',
    'পাবনা': 'Pabna',
    'সিরাজগঞ্জ': 'Sirajganj',
    'নওগাঁ': 'Naogaon',
    'নাটোর': 'Natore',
    'চাঁপাইনবাবগঞ্জ': 'Chapai Nawabganj',
    'যশোর': 'Jashore',
    'সাতক্ষীরা': 'Satkhira',
    'মেহেরপুর': 'Meherpur',
    'নড়াইল': 'Narail',
    'চুয়াডাঙ্গা': 'Chuadanga',
    'কুষ্টিয়া': 'Kushtia',
    'মাগুরা': 'Magura',
    'বাগেরহাট': 'Bagerhat',
    'ঝিনাইদহ': 'Jhenaidah',
    'ঝালকাঠি': 'Jhalokati',
    'পটুয়াখালী': 'Patuakhali',
    'পিরোজপুর': 'Pirojpur',
    'বরগুনা': 'Barguna',
    'ভোলা': 'Bhola',
    'মৌলভীবাজার': 'Moulvibazar',
    'হবিগঞ্জ': 'Habiganj',
    'সুনামগঞ্জ': 'Sunamganj',
    'পঞ্চগড়': 'Panchagarh',
    'দিনাজপুর': 'Dinajpur',
    'লালমনিরহাট': 'Lalmonirhat',
    'নীলফামারী': 'Nilphamari',
    'গাইবান্ধা': 'Gaibandha',
    'ঠাকুরগাঁও': 'Thakurgaon',
    'কুড়িগ্রাম': 'Kurigram',
    'শেরপুর': 'Sherpur',
    'জামালপুর': 'Jamalpur',
    'নেত্রকোণা': 'Netrokona'
  };

  const translateTimeAgo = (text) => {
    if (!text) return text;
    let s = text.trim();
    if (s.includes('এইমাত্র')) return '⏱️ Just now';
    s = bnToEnNum(s);
    s = s.replace(/বছর আগে/g, 'years ago')
         .replace(/মাস আগে/g, 'months ago')
         .replace(/সপ্তাহ আগে/g, 'weeks ago')
         .replace(/দিন আগে/g, 'days ago')
         .replace(/ঘণ্টা আগে/g, 'hrs ago')
         .replace(/ঘন্টা আগে/g, 'hrs ago')
         .replace(/মিনিট আগে/g, 'mins ago')
         .replace(/সেকেন্ড আগে/g, 'secs ago');
    return s;
  };

  const translateViews = (text) => {
    if (!text) return text;
    let s = bnToEnNum(text.trim());
    return s.replace(/বার পঠিত/g, 'views');
  };

  const applyPortalLanguage = (lang) => {
    htmlRoot.setAttribute('lang', lang);
    localStorage.setItem('newslens_lang', lang);
    document.cookie = 'newslens_lang=' + lang + '; path=/; max-age=31536000; SameSite=Lax';

    btnPortalBn?.classList.toggle('active', lang === 'bn');
    btnPortalEn?.classList.toggle('active', lang === 'en');

    // 1. Explicit data-bn and data-en elements
    document.querySelectorAll('[data-bn]').forEach(el => {
      const text = el.getAttribute('data-' + lang);
      if (text) el.innerHTML = text;
    });

    // 2. Input Placeholders
    document.querySelectorAll('[data-placeholder-bn]').forEach(el => {
      const ph = el.getAttribute('data-placeholder-' + lang);
      if (ph) el.placeholder = ph;
    });

    // 3. Tooltips and titles
    document.querySelectorAll('[data-title-bn]').forEach(el => {
      const title = el.getAttribute('data-title-' + lang);
      if (title) el.title = title;
    });

    // 4. Dynamic category badges
    document.querySelectorAll('.category-badge, .category-badge-sm, .topic-pill').forEach(el => {
      if (!el.hasAttribute('data-bn')) {
        if (!el.dataset.origText) el.dataset.origText = el.textContent.trim();
        if (lang === 'en') {
          const raw = el.dataset.origText;
          if (categoryMap[raw]) el.textContent = categoryMap[raw];
        } else {
          if (el.dataset.origText) el.textContent = el.dataset.origText;
        }
      }
    });

    // 5. Dynamic Relative Time Stamps (e.g., ⏱️ ৫ মিনিট আগে)
    document.querySelectorAll('.meta-time, .meta-time-sm, .meta-time-tiny').forEach(el => {
      if (!el.hasAttribute('data-bn')) {
        if (!el.dataset.origText) el.dataset.origText = el.innerHTML.trim();
        if (lang === 'en') {
          el.innerHTML = translateTimeAgo(el.dataset.origText);
        } else {
          if (el.dataset.origText) el.innerHTML = el.dataset.origText;
        }
      }
    });

    // 6. Dynamic View Counters (e.g., 👁️ ১২ বার পঠিত)
    document.querySelectorAll('.meta-views, .views-counter-badge').forEach(el => {
      if (!el.hasAttribute('data-bn')) {
        if (!el.dataset.origText) el.dataset.origText = el.innerHTML.trim();
        if (lang === 'en') {
          el.innerHTML = translateViews(el.dataset.origText);
        } else {
          if (el.dataset.origText) el.innerHTML = el.dataset.origText;
        }
      }
    });

    // 7. Dynamic District Badges & Locations (e.g., 📍 ঢাকা)
    document.querySelectorAll('.meta-dist, .district-badge').forEach(el => {
      if (!el.hasAttribute('data-bn')) {
        if (!el.dataset.origText) el.dataset.origText = el.textContent.trim();
        if (lang === 'en') {
          let s = el.dataset.origText;
          for (const [bnName, enName] of Object.entries(districtMap)) {
            s = s.replace(bnName, enName);
          }
          for (const [bnName, enName] of Object.entries(divisionMap)) {
            s = s.replace(bnName, enName);
          }
          s = s.replace('সারাদেশ', 'Countrywide');
          el.textContent = s;
        } else {
          if (el.dataset.origText) el.textContent = el.dataset.origText;
        }
      }
    });

    // 8. Author tags
    document.querySelectorAll('.meta-author, .author-name').forEach(el => {
      if (!el.hasAttribute('data-bn')) {
        if (!el.dataset.origText) el.dataset.origText = el.textContent.trim();
        if (lang === 'en') {
          el.textContent = el.dataset.origText.replace('স্টাফ রিপোর্টার', 'Staff Reporter');
        } else {
          if (el.dataset.origText) el.textContent = el.dataset.origText;
        }
      }
    });

    // 9. Numeric Counters (e.g., 1, 2, 3 in tab list)
    document.querySelectorAll('.num-counter').forEach(el => {
      if (!el.dataset.origText) el.dataset.origText = el.textContent.trim();
      el.textContent = (lang === 'en') ? bnToEnNum(el.dataset.origText) : el.dataset.origText;
    });

    // 10. Font Resizer button glyphs
    const btnDecEl = document.getElementById('font-decrease');
    const btnResetEl = document.getElementById('font-reset');
    const btnIncEl = document.getElementById('font-increase');
    if (btnDecEl) btnDecEl.textContent = (lang === 'en') ? 'A-' : 'অ-';
    if (btnResetEl) btnResetEl.textContent = (lang === 'en') ? 'A' : 'অ';
    if (btnIncEl) btnIncEl.textContent = (lang === 'en') ? 'A+' : 'অ+';

    updateThemeText();
  };

  const savedLang = localStorage.getItem('newslens_lang') || 
                    (document.cookie.match(/newslens_lang=([^;]+)/)?.[1]) || 'bn';
  applyPortalLanguage(savedLang);

  btnPortalBn?.addEventListener('click', () => applyPortalLanguage('bn'));
  btnPortalEn?.addEventListener('click', () => applyPortalLanguage('en'));

  // ================= 2. FONT RESIZER =================
  const btnDec = document.getElementById('font-decrease');
  const btnReset = document.getElementById('font-reset');
  const btnInc = document.getElementById('font-increase');
  const fontBtns = [btnDec, btnReset, btnInc];

  const setFontSize = (sizePx, activeBtn) => {
    htmlRoot.style.fontSize = sizePx + 'px';
    fontBtns.forEach(btn => btn?.classList.remove('active'));
    activeBtn?.classList.add('active');
    localStorage.setItem('newslens_fontsize', sizePx);
  };

  btnDec?.addEventListener('click', () => setFontSize(14, btnDec));
  btnReset?.addEventListener('click', () => setFontSize(16, btnReset));
  btnInc?.addEventListener('click', () => setFontSize(18, btnInc));

  const savedFontSize = localStorage.getItem('newslens_fontsize');
  if (savedFontSize) {
    if (savedFontSize === '14') setFontSize(14, btnDec);
    else if (savedFontSize === '18') setFontSize(18, btnInc);
  }

  // ================= 3. MOBILE MENU =================
  const mobileBtn = document.getElementById('mobile-menu-btn');
  const navList = document.getElementById('nav-list');
  if (mobileBtn && navList) {
    mobileBtn.addEventListener('click', () => {
      navList.classList.toggle('open');
      const expanded = navList.classList.contains('open');
      mobileBtn.setAttribute('aria-expanded', expanded);
    });
  }

  // ================= 4. BREAKING TICKER SLIDESHOW (Picture 2 Fix) =================
  const tickerItems = document.querySelectorAll('#breaking-list .ticker-item');
  const tickerPrev = document.getElementById('ticker-prev');
  const tickerNext = document.getElementById('ticker-next');
  const tickerViewport = document.getElementById('ticker-viewport');

  if (tickerItems.length > 0) {
    let currentIdx = 0;
    let tickerInterval = null;

    const showTicker = (idx) => {
      tickerItems.forEach(item => item.classList.remove('active'));
      currentIdx = (idx + tickerItems.length) % tickerItems.length;
      tickerItems[currentIdx].classList.add('active');
    };

    const startTicker = () => {
      if (tickerItems.length > 1 && !tickerInterval) {
        tickerInterval = setInterval(() => {
          showTicker(currentIdx + 1);
        }, 3500); // 3.5 seconds per slide
      }
    };

    const stopTicker = () => {
      if (tickerInterval) {
        clearInterval(tickerInterval);
        tickerInterval = null;
      }
    };

    tickerPrev?.addEventListener('click', () => {
      stopTicker();
      showTicker(currentIdx - 1);
      startTicker();
    });

    tickerNext?.addEventListener('click', () => {
      stopTicker();
      showTicker(currentIdx + 1);
      startTicker();
    });

    tickerViewport?.addEventListener('mouseenter', stopTicker);
    tickerViewport?.addEventListener('mouseleave', startTicker);

    // Initial show & start
    showTicker(0);
    startTicker();
  }

  // ================= 5. LATEST / POPULAR TABS =================
  const tabBtns = document.querySelectorAll('.tabbed-news-widget .tab-btn');
  const tabContents = document.querySelectorAll('.tabbed-news-widget .tab-content');

  tabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const targetId = btn.getAttribute('data-tab');
      tabBtns.forEach(b => b.classList.remove('active'));
      tabContents.forEach(c => c.classList.remove('active'));

      btn.classList.add('active');
      const targetContent = document.getElementById(targetId);
      if (targetContent) targetContent.classList.add('active');
    });
  });

  // ================= 6. ONLINE POLL AJAX VOTE (Picture 4 Fix) =================
  const pollForm = document.getElementById('poll-form');
  const pollFeedback = document.getElementById('poll-feedback');
  const pollResultsLive = document.getElementById('poll-results-live');

  if (pollForm) {
    pollForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const selected = pollForm.querySelector('input[name="option_id"]:checked');
      if (!selected) {
        alert('অনুগ্রহ করে একটি অপশন নির্বাচন করুন।');
        return;
      }

      const formData = new FormData(pollForm);
      const submitBtn = pollForm.querySelector('button[type="submit"]');
      if (submitBtn) submitBtn.disabled = true;

      try {
        const response = await fetch(pollForm.action, {
          method: 'POST',
          body: formData
        });
        const res = await response.json();

        if (pollFeedback) {
          pollFeedback.style.display = 'block';
          pollFeedback.className = 'poll-feedback-msg ' + (res.success ? 'success' : 'error');
          pollFeedback.textContent = res.message;
        }

        if (res.success) {
          pollForm.style.display = 'none';
          if (pollResultsLive) pollResultsLive.style.display = 'block';
        }
      } catch (err) {
        if (pollFeedback) {
          pollFeedback.style.display = 'block';
          pollFeedback.className = 'poll-feedback-msg error';
          pollFeedback.textContent = 'নেটওয়ার্ক সংযোগে সমস্যা হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।';
        }
      } finally {
        if (submitBtn) submitBtn.disabled = false;
      }
    });
  }

  // ================= 7. NEWSLETTER SUBSCRIPTION AJAX (Picture 4 Fix) =================
  const newsletterForm = document.getElementById('sidebar-newsletter');
  const newsletterFeedback = document.getElementById('newsletter-feedback');

  if (newsletterForm) {
    newsletterForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const emailInput = newsletterForm.querySelector('input[name="email"]');
      if (!emailInput || !emailInput.value) return;

      const formData = new FormData(newsletterForm);
      try {
        const res = await fetch(newsletterForm.action, {
          method: 'POST',
          body: formData
        });
        const data = await res.json();
        if (newsletterFeedback) {
          newsletterFeedback.style.display = 'block';
          newsletterFeedback.className = 'newsletter-feedback-msg ' + (data.success ? 'success' : 'error');
          newsletterFeedback.textContent = data.message;
        }
        if (data.success) {
          newsletterForm.reset();
        }
      } catch (err) {
        if (newsletterFeedback) {
          newsletterFeedback.style.display = 'block';
          newsletterFeedback.className = 'newsletter-feedback-msg error';
          newsletterFeedback.textContent = 'সাবস্ক্রিপশনে ত্রুটি হয়েছে।';
        }
      }
    });
  }

  // ================= 8. VIDEO LIGHTBOX MODAL (Picture 5 Fix) =================
  const videoTriggers = document.querySelectorAll('.video-play-trigger');
  const videoModal = document.getElementById('video-lightbox-modal');
  const videoModalBody = document.getElementById('video-modal-body');
  const videoModalHeading = document.getElementById('video-modal-heading');
  const videoModalClose = document.getElementById('video-modal-close');

  const closeVideoModal = () => {
    if (videoModal) {
      videoModal.style.display = 'none';
      if (videoModalBody) videoModalBody.innerHTML = '';
    }
  };

  videoTriggers.forEach(trigger => {
    trigger.addEventListener('click', () => {
      const videoUrl = trigger.getAttribute('data-video-url');
      const videoTitle = trigger.getAttribute('data-video-title') || 'ভিডিও প্রতিবেদন';

      if (videoModalHeading) videoModalHeading.textContent = videoTitle;
      if (videoModalBody && videoUrl) {
        // Check for YouTube ID
        const ytMatch = videoUrl.match(/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/);
        if (ytMatch && ytMatch[1]) {
          videoModalBody.innerHTML = `<iframe src="https://www.youtube.com/embed/${ytMatch[1]}?autoplay=1" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>`;
        } else {
          videoModalBody.innerHTML = `<video controls autoplay style="width:100%;height:100%;"><source src="${videoUrl}" type="video/mp4">আপনার ব্রাউজার এই ভিডিও সাপোর্ট করছে না।</video>`;
        }
      }

      if (videoModal) videoModal.style.display = 'flex';
    });
  });

  videoModalClose?.addEventListener('click', closeVideoModal);
  videoModal?.addEventListener('click', (e) => {
    if (e.target === videoModal) closeVideoModal();
  });

  // ================= 9. COPY ARTICLE LINK =================
  const copyBtn = document.getElementById('copy-article-link');
  if (copyBtn) {
    copyBtn.addEventListener('click', () => {
      const url = copyBtn.getAttribute('data-url') || window.location.href;
      navigator.clipboard.writeText(url).then(() => {
        const origText = copyBtn.textContent;
        copyBtn.textContent = '✓ কপি হয়েছে!';
        setTimeout(() => { copyBtn.textContent = origText; }, 2000);
      }).catch(() => {
        alert('লিঙ্কটি কপি করুন: ' + url);
      });
    });
  }

});

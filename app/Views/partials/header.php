<?php
use App\Helpers\BanglaDate;
$settings = $settings ?? [];
$categories = $categories ?? [];
$appUrl = rtrim($appUrl ?? $config['app']['url'] ?? '', '/');
$currentLogo = !empty($settings['site_logo']) ? $appUrl . '/' . htmlspecialchars($settings['site_logo']) : $appUrl . '/assets/img/logo.png';
?>
<header class="site-header">
  <!-- Top Bar: Dates, Weather, Bilingual Toggle, Font Tools, Dark Mode -->
  <div class="top-bar">
    <div class="container top-bar-inner">
      <div class="top-bar-left">
        <span class="date-item bn-date"><i class="icon-calendar"></i> <span id="header-date" data-bn="<?= $todayBn ?? BanglaDate::formatBnDate() ?>" data-en="<?= date('l, d F Y') ?>"><?= $todayBn ?? BanglaDate::formatBnDate() ?></span></span>
        <span class="sep">|</span>
        <span class="weather-widget" title="আবহাওয়া" data-bn="ঢাকা: ২৯° সে. (বৃষ্টির সম্ভাবনা)" data-en="Dhaka: 29°C (Rain forecast)">ঢাকা: ২৯° সে. (বৃষ্টির সম্ভাবনা)</span>
      </div>
      <div class="top-bar-right">
        <!-- Bilingual Switcher [BN / EN] -->
        <div class="header-lang-switch">
          <button type="button" class="portal-lang-btn active" id="portal-lang-bn" data-lang="bn">বাংলা</button>
          <span class="sep">|</span>
          <button type="button" class="portal-lang-btn" id="portal-lang-en" data-lang="en">English</button>
        </div>
        <span class="sep">|</span>
        <!-- Font resize tools -->
        <div class="font-resizer" title="ফন্ট সাইজ পরিবর্তন" data-title-bn="ফন্ট সাইজ পরিবর্তন" data-title-en="Change Font Size">
          <button type="button" class="font-btn" id="font-decrease" aria-label="ছোট ফন্ট" data-bn="অ-" data-en="A-">অ-</button>
          <button type="button" class="font-btn active" id="font-reset" aria-label="স্বাভাবিক ফন্ট" data-bn="অ" data-en="A">অ</button>
          <button type="button" class="font-btn" id="font-increase" aria-label="বড় ফন্ট" data-bn="অ+" data-en="A+">অ+</button>
        </div>
        <span class="sep">|</span>
        <!-- Sleek Dark Mode Toggle (Navy Blue Theme) -->
        <button type="button" class="theme-toggle-pill" id="theme-toggle" aria-label="ডার্ক মোড পরিবর্তন" title="ডার্ক মোড">
          <span class="pill-slider">
            <span class="pill-icon-sun">☀️</span>
            <span class="pill-icon-moon">🌙</span>
          </span>
          <span class="theme-mode-text" id="theme-text" data-bn="ডার্ক" data-en="Dark">ডার্ক</span>
        </button>

        <span class="sep">|</span>

        <!-- Notification Bell Dropdown -->
        <div class="portal-notif-wrap" style="position: relative;">
          <button type="button" id="portalNotifBtn" class="notif-bell-btn" title="নোটিফিকেশন" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15); color: #fff; padding: 4px 8px; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; font-size: 0.85rem; position: relative;">
            <span>🔔</span>
            <span id="notifBadge" style="display: none; background: #e11b22; color: #fff; font-size: 0.7rem; font-weight: 800; padding: 1px 5px; border-radius: 10px; line-height: 1.2;">0</span>
          </button>

          <!-- Bell Popup Card -->
          <div id="portalNotifBox" style="display: none; position: absolute; right: 0; top: 115%; width: 320px; background: #081e42; border: 1px solid #173567; border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); z-index: 9999; overflow: hidden; color: #f8fafc;">
            <div style="padding: 10px 14px; border-bottom: 1px solid #173567; display: flex; justify-content: space-between; align-items: center; background: #051024;">
              <span style="font-weight: 700; font-size: 0.88rem;">🚨 নোটিফিকেশন</span>
              <button type="button" onclick="markAllNotificationsRead()" style="background: none; border: none; color: #38bdf8; font-size: 0.76rem; cursor: pointer; font-weight: 600;">সব পঠিত</button>
            </div>
            <div id="notifListContainer" style="max-height: 280px; overflow-y: auto; padding: 6px 0;">
              <div style="text-align: center; padding: 20px; color: #94a3b8; font-size: 0.82rem;">লোড হচ্ছে...</div>
            </div>
            <div style="padding: 8px 12px; border-top: 1px solid #173567; background: #051024; text-align: center;">
              <button type="button" id="enablePushBtn" onclick="requestPushPermission()" style="background: #0E7A3A; color: #fff; border: none; padding: 5px 12px; border-radius: 4px; font-size: 0.78rem; font-weight: 700; cursor: pointer; width: 100%;">
                🔔 ব্রাউজার পুশ নোটিফিকেশন চালু করুন
              </button>
            </div>
          </div>
        </div>

        <span class="sep">|</span>

        <!-- User Authentication Section -->
        <?php if (\App\Core\Auth::check()): ?>
          <?php 
            $curUser = \App\Core\Auth::user();
            $adminPath = $adminPath ?? 'lens-desk';
          ?>
          <div class="portal-user-wrap" style="position: relative;">
            <button type="button" id="portalUserBtn" style="background: rgba(14, 122, 58, 0.2); border: 1px solid #10b981; color: #86efac; padding: 4px 10px; border-radius: 6px; cursor: pointer; font-size: 0.84rem; font-weight: 700; display: inline-flex; align-items: center; gap: 6px;">
              <span>👤</span>
              <span><?= htmlspecialchars(mb_substr($curUser['name'] ?? 'User', 0, 14)) ?></span>
              <span style="font-size: 0.7rem; opacity: 0.8;">▼</span>
            </button>

            <!-- User Dropdown Menu -->
            <div id="portalUserBox" style="display: none; position: absolute; right: 0; top: 115%; width: 220px; background: #081e42; border: 1px solid #173567; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); z-index: 9999; overflow: hidden;">
              <div style="padding: 10px 14px; border-bottom: 1px solid #173567; background: #051024;">
                <div style="font-weight: 700; font-size: 0.88rem; color: #fff;"><?= htmlspecialchars($curUser['name'] ?? '') ?></div>
                <div style="font-size: 0.72rem; color: #94a3b8; text-transform: uppercase; margin-top: 2px;">
                  ভূমিকা: <?= htmlspecialchars($curUser['role'] ?? 'user') ?>
                </div>
              </div>

              <div style="display: flex; flex-direction: column; padding: 4px 0;">
                <?php if (($curUser['role'] ?? '') === 'writer'): ?>
                  <a href="<?= $appUrl ?>/writer/dashboard" style="padding: 8px 14px; color: #38bdf8; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                    <span>📊</span> <span>লেখক ড্যাশবোর্ড</span>
                  </a>
                  <a href="<?= $appUrl ?>/writer/posts" style="padding: 8px 14px; color: #f8fafc; text-decoration: none; font-size: 0.85rem; display: flex; align-items: center; gap: 8px;">
                    <span>📝</span> <span>আমার সংবাদসমূহ</span>
                  </a>
                <?php elseif (in_array($curUser['role'] ?? '', ['super_admin', 'editor'], true)): ?>
                  <a href="<?= $appUrl ?>/<?= $adminPath ?>" style="padding: 8px 14px; color: #38bdf8; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                    <span>⚙️</span> <span>অ্যাডমিন প্যানেল</span>
                  </a>
                <?php endif; ?>

                <a href="<?= $appUrl ?>/user/bookmarks" style="padding: 8px 14px; color: #f8fafc; text-decoration: none; font-size: 0.85rem; display: flex; align-items: center; gap: 8px;">
                  <span>🔖</span> <span>সংরক্ষিত সংবাদ</span>
                </a>
                <a href="<?= $appUrl ?>/profile" style="padding: 8px 14px; color: #f8fafc; text-decoration: none; font-size: 0.85rem; display: flex; align-items: center; gap: 8px;">
                  <span>👤</span> <span>প্রোফাইল ও সেটিংস</span>
                </a>
                <div style="border-top: 1px solid #173567; margin: 4px 0;"></div>
                <a href="<?= $appUrl ?>/logout" style="padding: 8px 14px; color: #f87171; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                  <span>🚪</span> <span>লগআউট</span>
                </a>
              </div>
            </div>
          </div>
        <?php else: ?>
          <div style="display: inline-flex; align-items: center; gap: 8px; font-size: 0.84rem;">
            <a href="<?= $appUrl ?>/login" style="color: #e2e8f0; text-decoration: none; font-weight: 600;">লগইন</a>
            <span style="opacity: 0.3;">/</span>
            <a href="<?= $appUrl ?>/register" style="color: #38bdf8; text-decoration: none; font-weight: 700;">নিবন্ধন</a>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Main Branding Bar (Logo with crisp white card & Dynamic Header Ad) -->
  <div class="brand-bar">
    <div class="container brand-bar-inner">
      <div class="brand-logo-area">
        <a href="<?= $appUrl ?>/" class="site-logo-link" aria-label="Newslensbd হোমপেজ">
          <div class="site-logo-white-badge">
            <img src="<?= $currentLogo ?>" alt="Newslensbd, News Beyond the Ordinary" class="site-main-logo" width="280" height="52">
          </div>
        </a>
      </div>

      <!-- Header Ad Slot (728x90 Dynamic Admin-Controlled with Hyperlink) -->
      <div class="header-ad-slot" aria-label="বিজ্ঞাপন">
        <?= \App\Helpers\AdBanner::render('header') ?>
      </div>
    </div>
  </div>

  <!-- Primary Navigation Bar (14 Categories) -->
  <nav class="main-navigation" aria-label="মূল মেনু">
    <div class="container nav-inner">
      <button type="button" class="mobile-menu-toggle" id="mobile-menu-btn" aria-label="মেনু খুলুন">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
      </button>

      <ul class="nav-list" id="nav-list">
        <li class="nav-item home-item">
          <a href="<?= $appUrl ?>/" class="nav-link <?= (($_SERVER['REQUEST_URI'] ?? '') === '/' || str_ends_with($_SERVER['REQUEST_URI'] ?? '', '/public/')) ? 'active' : '' ?>" data-bn="প্রচ্ছদ" data-en="Home">প্রচ্ছদ</a>
        </li>
        <?php foreach ($categories as $cat): ?>
          <li class="nav-item <?= $cat['slug'] === 'saradesh' ? 'special-saradesh' : '' ?>">
            <a href="<?= ($cat['slug'] === 'saradesh') ? $appUrl . '/saradesh' : $appUrl . '/category/' . htmlspecialchars($cat['slug']) ?>" class="nav-link" data-bn="<?= htmlspecialchars($cat['name_bn']) ?>" data-en="<?= htmlspecialchars($cat['name_en'] ?? $cat['name_bn']) ?>">
              <?= htmlspecialchars($cat['name_bn']) ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>

      <!-- Live Search Trigger -->
      <div class="nav-search-box">
        <form action="<?= $appUrl ?>/search" method="get" class="search-form" id="search-form">
          <input type="text" name="q" id="search-input" placeholder="খবর খুঁজুন..." data-placeholder-bn="খবর খুঁজুন..." data-placeholder-en="Search news..." autocomplete="off">
          <button type="submit" aria-label="খুঁজুন" class="search-btn">🔍</button>
        </form>
      </div>
    </div>
  </nav>
</header>

<script>
  (function() {
    const notifBtn = document.getElementById('portalNotifBtn');
    const notifBox = document.getElementById('portalNotifBox');
    const notifBadge = document.getElementById('notifBadge');
    const notifList = document.getElementById('notifListContainer');

    const userBtn = document.getElementById('portalUserBtn');
    const userBox = document.getElementById('portalUserBox');

    // Toggle user dropdown
    if (userBtn && userBox) {
      userBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        if (notifBox) notifBox.style.display = 'none';
        userBox.style.display = (userBox.style.display === 'block') ? 'none' : 'block';
      });
    }

    // Toggle notification box
    if (notifBtn && notifBox) {
      notifBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        if (userBox) userBox.style.display = 'none';
        const isShown = notifBox.style.display === 'block';
        notifBox.style.display = isShown ? 'none' : 'block';
        if (!isShown) loadNotifications();
      });
    }

    // Close when clicking outside
    document.addEventListener('click', function(e) {
      if (notifBox && !notifBox.contains(e.target) && e.target !== notifBtn) {
        notifBox.style.display = 'none';
      }
      if (userBox && !userBox.contains(e.target) && e.target !== userBtn) {
        userBox.style.display = 'none';
      }
    });

    // Check unread count on page load
    function checkNotificationCount() {
      fetch('<?= $appUrl ?>/api/notifications')
        .then(r => r.json())
        .then(data => {
          if (data.success && data.unreadCount > 0) {
            notifBadge.textContent = data.unreadCount > 99 ? '99+' : data.unreadCount;
            notifBadge.style.display = 'inline-block';
          } else {
            notifBadge.style.display = 'none';
          }
        })
        .catch(() => {});
    }

    // Load notification items into box
    window.loadNotifications = function() {
      notifList.innerHTML = '<div style="text-align: center; padding: 20px; color: #94a3b8; font-size: 0.82rem;">লোড হচ্ছে...</div>';
      fetch('<?= $appUrl ?>/api/notifications')
        .then(r => r.json())
        .then(data => {
          if (!data.success || !data.list || data.list.length === 0) {
            notifList.innerHTML = '<div style="text-align: center; padding: 30px 10px; color: #94a3b8; font-size: 0.82rem;">কোনো নতুন নোটিফিকেশন নেই।</div>';
            return;
          }

          let html = '';
          data.list.forEach(n => {
            const isRead = n.is_read == 1;
            const bg = isRead ? 'transparent' : 'rgba(56, 189, 248, 0.08)';
            const title = n.title || '';
            const msg = n.message || '';
            const targetUrl = n.url ? ('<?= $appUrl ?>' + n.url) : '#';

            html += `
              <a href="${targetUrl}" style="display: block; padding: 10px 14px; border-bottom: 1px solid rgba(255,255,255,0.06); text-decoration: none; color: #f8fafc; background: ${bg}; transition: background 0.15s;">
                <div style="font-weight: 700; font-size: 0.84rem; margin-bottom: 3px; line-height: 1.3;">${escapeHtml(title)}</div>
                <div style="font-size: 0.78rem; color: #94a3b8; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">${escapeHtml(msg)}</div>
              </a>
            `;
          });
          notifList.innerHTML = html;
        })
        .catch(() => {
          notifList.innerHTML = '<div style="text-align: center; padding: 20px; color: #f87171; font-size: 0.82rem;">লোড করতে সমস্যা হয়েছে।</div>';
        });
    };

    window.markAllNotificationsRead = function() {
      fetch('<?= $appUrl ?>/api/notifications/mark-read', { method: 'POST' })
        .then(() => {
          notifBadge.style.display = 'none';
          loadNotifications();
        })
        .catch(() => {});
    };

    function escapeHtml(str) {
      const div = document.createElement('div');
      div.textContent = str;
      return div.innerHTML;
    }

    // Web Push Registration
    window.requestPushPermission = function() {
      if (!('serviceWorker' in navigator) || !('PushManager' in window)) {
        alert('আপনার ব্রাউজার পুশ নোটিফিকেশন সমর্থন করে না।');
        return;
      }

      Notification.requestPermission().then(function(perm) {
        if (perm !== 'granted') {
          alert('পুশ নোটিফিকেশনের অনুমতি প্রদান করা হয়নি।');
          return;
        }

        navigator.serviceWorker.register('<?= $appUrl ?>/sw.js').then(function(reg) {
          fetch('<?= $appUrl ?>/api/push/public-key')
            .then(r => r.json())
            .then(keyData => {
              const pubKey = keyData.publicKey;
              if (!pubKey) {
                alert('পুশ কী এখনও কনফিগার করা হয়নি। শীঘ্রই সক্রিয় হবে।');
                return;
              }

              return reg.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(pubKey)
              }).then(function(sub) {
                return fetch('<?= $appUrl ?>/api/push/subscribe', {
                  method: 'POST',
                  headers: { 'Content-Type': 'application/json' },
                  body: JSON.stringify(sub)
                });
              }).then(function() {
                alert('ধন্যবাদ! ব্রেকিং নিউজ নোটিফিকেশন সফলভাবে চালু করা হয়েছে।');
                const btn = document.getElementById('enablePushBtn');
                if (btn) btn.textContent = '✓ নোটিফিকেশন সক্রিয় আছে';
              });
            });
        }).catch(err => {
          console.error(err);
        });
      });
    };

    function urlBase64ToUint8Array(base64String) {
      const padding = '='.repeat((4 - base64String.length % 4) % 4);
      const base64 = (base64String + padding).replace(/\-/g, '+').replace(/_/g, '/');
      const rawData = window.atob(base64);
      const outputArray = new Uint8Array(rawData.length);
      for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
      }
      return outputArray;
    }

    // Run badge check
    checkNotificationCount();
  })();
</script>

<?php
/**
 * Unified Responsive Admin Header Component
 * Supports: 
 * - 3-Line Hamburger Drawer for mobile/compact screens (slide-in from side)
 * - Super Admin Dynamic Name & Profile Change Modal accessible anywhere
 * - Dynamic Site Logo with white background badge
 * - Dark (Navy) / Light Theme Toggle
 * - Bangla / English Bilingual Switcher
 * - Complete Admin Navigation (Dashboard, News, Ads, Polls, Subscribers, Settings)
 */
use App\Models\Setting;
use App\Core\Auth;
use App\Core\Database;

$settings = $settings ?? Setting::all();
$appUrl = rtrim($appUrl ?? '', '/');
$adminPath = $adminPath ?? 'lens-desk';
$activeTab = $activeTab ?? '';
$user = $user ?? Auth::user() ?? ['name' => 'Super Admin', 'role' => 'super_admin'];

// Fetch latest phone & name from DB if needed
$userPhone = htmlspecialchars($user['phone'] ?? '');
if (empty($userPhone) && !empty($user['id'])) {
    try {
        $db = Database::getConnection();
        $st = $db->prepare("SELECT name, phone FROM users WHERE id = :id LIMIT 1");
        $st->execute([':id' => (int)$user['id']]);
        $row = $st->fetch();
        if ($row) {
            if (!empty($row['phone'])) {
                $userPhone = htmlspecialchars($row['phone']);
                $_SESSION['admin_user']['phone'] = $row['phone'];
            }
            if (!empty($row['name'])) {
                $user['name'] = $row['name'];
                $_SESSION['admin_user']['name'] = $row['name'];
            }
        }
    } catch (\Exception $e) {}
}

// Ensure no Gulam Sakaria
$displayName = 'Super Admin';
if (!empty($user['name']) && stripos($user['name'], 'sakaria') === false && stripos($user['name'], 'সাকারিয়া') === false) {
    $displayName = htmlspecialchars($user['name']);
}

$siteLogo = !empty($settings['site_logo']) ? $appUrl . '/' . htmlspecialchars($settings['site_logo']) : $appUrl . '/assets/img/logo.png';
$csrfToken = Auth::generateCsrf();
$currentUrl = htmlspecialchars($_SERVER['REQUEST_URI'] ?? ($appUrl . '/' . $adminPath));
?>
<style>
  :root {
    --adm-bg: #071630;
    --adm-nav-bg: #081e42;
    --adm-card-bg: #0c1e3d;
    --adm-border: #173567;
    --adm-text: #f1f5f9;
    --adm-text-muted: #94a3b8;
    --adm-primary: #0E7A3A;
    --adm-primary-hover: #0a5c2b;
    --adm-accent: #38bdf8;
    --adm-danger: #f87171;
    --adm-pill-bg: rgba(255, 255, 255, 0.08);
  }

  body.admin-light {
    --adm-bg: #f8fafc;
    --adm-nav-bg: #ffffff;
    --adm-card-bg: #ffffff;
    --adm-border: #e2e8f0;
    --adm-text: #0f172a;
    --adm-text-muted: #64748b;
    --adm-pill-bg: rgba(0, 0, 0, 0.05);
  }

  body {
    background-color: var(--adm-bg) !important;
    color: var(--adm-text) !important;
    font-family: 'Hind Siliguri', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    margin: 0;
    padding: 0;
    transition: background-color 0.2s ease, color 0.2s ease;
  }

  header.admin-top {
    background: var(--adm-nav-bg);
    border-bottom: 2px solid var(--adm-primary);
    padding: 10px 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 12px;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
  }

  .admin-brand-area {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
  }

  .admin-brand-link {
    display: flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    background: transparent;
  }

  .admin-logo-badge {
    background: #ffffff;
    padding: 3px 8px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 1px 4px rgba(0,0,0,0.12);
  }

  .admin-brand-logo-img {
    height: 32px;
    max-width: 140px;
    object-fit: contain;
    display: block;
  }

  .admin-brand-badge {
    background: var(--adm-primary);
    color: #ffffff;
    font-size: 0.72rem;
    font-weight: 800;
    padding: 2px 6px;
    border-radius: 4px;
    letter-spacing: 0.5px;
  }

  /* Admin Nav Items (Desktop) */
  .admin-nav-bar {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-shrink: 1;
  }

  .admin-nav-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: var(--adm-text-muted);
    text-decoration: none;
    font-size: 0.92rem;
    font-weight: 600;
    padding: 7px 12px;
    border-radius: 6px;
    white-space: nowrap;
    transition: all 0.18s ease;
  }

  .admin-nav-item:hover {
    color: var(--adm-accent);
    background: var(--adm-pill-bg);
  }

  .admin-nav-item.active {
    color: #ffffff;
    background: var(--adm-primary);
  }

  .admin-nav-item .nav-icon {
    font-size: 1.05rem;
    line-height: 1;
  }

  /* Right Tools & User Info */
  .admin-right-tools {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
  }

  .tool-btn {
    background: var(--adm-pill-bg);
    border: 1px solid var(--adm-border);
    color: var(--adm-text);
    padding: 6px 10px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.85rem;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all 0.2s;
  }

  .tool-btn:hover {
    border-color: var(--adm-accent);
    color: var(--adm-accent);
  }

  .admin-user-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: var(--adm-pill-bg);
    padding: 5px 10px;
    border-radius: 6px;
    font-size: 0.88rem;
    border: 1px solid var(--adm-border);
    cursor: pointer;
    transition: all 0.2s ease;
  }
  .admin-user-pill:hover {
    border-color: var(--adm-accent);
    background: rgba(56, 189, 248, 0.1);
  }

  .admin-role-tag {
    background: var(--adm-primary);
    color: #ffffff;
    padding: 2px 7px;
    border-radius: 4px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
  }

  .btn-portal-link {
    color: var(--adm-accent);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 0.88rem;
    font-weight: 600;
    padding: 6px 10px;
    border-radius: 6px;
    border: 1px solid var(--adm-border);
    transition: all 0.2s;
  }

  .btn-portal-link:hover {
    background: var(--adm-pill-bg);
  }

  .btn-logout-link {
    color: var(--adm-danger);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 0.88rem;
    font-weight: 600;
    padding: 6px 10px;
    border-radius: 6px;
    border: 1px solid rgba(248, 113, 113, 0.2);
    transition: all 0.2s;
  }

  .btn-logout-link:hover {
    background: rgba(248, 113, 113, 0.15);
  }

  /* 3-Line Hamburger Button */
  .admin-hamburger-btn {
    display: none;
    flex-direction: column;
    justify-content: space-between;
    width: 38px;
    height: 36px;
    background: var(--adm-pill-bg);
    border: 1px solid var(--adm-border);
    border-radius: 6px;
    padding: 8px 7px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-sizing: border-box;
  }
  .admin-hamburger-btn:hover {
    border-color: var(--adm-accent);
    background: rgba(56, 189, 248, 0.15);
  }
  .admin-hamburger-btn .ham-line {
    display: block;
    width: 100%;
    height: 2.5px;
    background-color: var(--adm-text);
    border-radius: 2px;
    transition: all 0.2s ease;
  }

  /* Offcanvas Drawer Backdrop & Container */
  .admin-drawer-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(3, 10, 24, 0.7);
    backdrop-filter: blur(4px);
    z-index: 9998;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
  }
  .admin-drawer-backdrop.active {
    opacity: 1;
    pointer-events: auto;
  }

  .admin-drawer {
    position: fixed;
    top: 0;
    right: 0;
    width: 310px;
    max-width: 86vw;
    height: 100vh;
    background: var(--adm-card-bg);
    border-left: 2px solid var(--adm-border);
    z-index: 9999;
    transform: translateX(100%);
    transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: -8px 0 30px rgba(0, 0, 0, 0.5);
    display: flex;
    flex-direction: column;
    overflow-y: auto;
  }
  .admin-drawer.active {
    transform: translateX(0);
  }

  .admin-drawer-head {
    padding: 16px 18px;
    border-bottom: 1px solid var(--adm-border);
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    background: var(--adm-nav-bg);
  }

  .drawer-user-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
  }
  .drawer-user-name {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--adm-text);
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .drawer-role-badge {
    align-self: flex-start;
    background: var(--adm-primary);
    color: #fff;
    font-size: 0.7rem;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 4px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .drawer-edit-name-btn {
    margin-top: 6px;
    background: transparent;
    border: 1px dashed var(--adm-accent);
    color: var(--adm-accent);
    font-size: 0.8rem;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 4px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    width: fit-content;
  }
  .drawer-edit-name-btn:hover {
    background: rgba(56, 189, 248, 0.12);
  }

  .admin-drawer-close {
    background: var(--adm-pill-bg);
    border: 1px solid var(--adm-border);
    color: var(--adm-text);
    width: 32px;
    height: 32px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 1.1rem;
    transition: all 0.2s;
  }
  .admin-drawer-close:hover {
    background: rgba(248, 113, 113, 0.2);
    color: var(--adm-danger);
    border-color: var(--adm-danger);
  }

  .admin-drawer-nav {
    padding: 14px 10px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    flex: 1;
  }

  .drawer-nav-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    border-radius: 8px;
    color: var(--adm-text);
    text-decoration: none;
    font-size: 0.95rem;
    font-weight: 600;
    transition: all 0.2s;
  }
  .drawer-nav-item:hover {
    background: var(--adm-pill-bg);
    color: var(--adm-accent);
    transform: translateX(4px);
  }
  .drawer-nav-item.active {
    background: var(--adm-primary);
    color: #ffffff;
    font-weight: 700;
  }
  .drawer-nav-icon {
    font-size: 1.2rem;
    line-height: 1;
  }

  .admin-drawer-foot {
    padding: 14px 16px;
    border-top: 1px solid var(--adm-border);
    background: var(--adm-nav-bg);
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  /* Admin Profile Modal */
  .adm-modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(3, 10, 24, 0.8);
    backdrop-filter: blur(5px);
    z-index: 10010;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 16px;
    box-sizing: border-box;
  }
  .adm-modal-backdrop.active {
    display: flex;
  }
  .adm-modal-box {
    background: var(--adm-card-bg);
    border: 1px solid var(--adm-border);
    border-radius: 12px;
    width: 100%;
    max-width: 440px;
    padding: 24px 24px;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.6);
    position: relative;
    box-sizing: border-box;
    animation: modalPop 0.25s ease-out;
  }
  @keyframes modalPop {
    from { opacity: 0; transform: scale(0.95) translateY(-10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
  }
  .adm-modal-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
    border-bottom: 1px solid var(--adm-border);
    padding-bottom: 12px;
  }
  .adm-modal-title {
    font-size: 1.15rem;
    font-weight: 700;
    color: var(--adm-text);
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .adm-modal-close {
    background: transparent;
    border: none;
    color: var(--adm-text-muted);
    font-size: 1.3rem;
    cursor: pointer;
    line-height: 1;
    padding: 4px;
  }
  .adm-modal-close:hover {
    color: var(--adm-danger);
  }
  .adm-form-group {
    margin-bottom: 14px;
  }
  .adm-form-group label {
    display: block;
    font-size: 0.88rem;
    font-weight: 600;
    margin-bottom: 5px;
    color: var(--adm-text-muted);
  }
  .adm-form-group input {
    width: 100%;
    padding: 9px 12px;
    background: var(--adm-bg);
    border: 1px solid var(--adm-border);
    border-radius: 6px;
    color: var(--adm-text);
    font-size: 0.92rem;
    box-sizing: border-box;
    outline: none;
  }
  .adm-form-group input:focus {
    border-color: var(--adm-accent);
  }
  .adm-modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 18px;
  }
  .adm-btn-save {
    background: var(--adm-primary);
    color: #fff;
    border: none;
    padding: 9px 16px;
    border-radius: 6px;
    font-size: 0.92rem;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.2s;
  }
  .adm-btn-save:hover {
    background: var(--adm-primary-hover);
  }
  .adm-btn-cancel {
    background: var(--adm-pill-bg);
    border: 1px solid var(--adm-border);
    color: var(--adm-text);
    padding: 9px 14px;
    border-radius: 6px;
    font-size: 0.92rem;
    cursor: pointer;
  }

  /* Success/Error Toast */
  .adm-toast {
    position: fixed;
    top: 70px;
    right: 20px;
    background: #0E7A3A;
    color: #fff;
    padding: 10px 18px;
    border-radius: 8px;
    font-size: 0.92rem;
    font-weight: 600;
    box-shadow: 0 8px 25px rgba(0,0,0,0.4);
    z-index: 10050;
    display: flex;
    align-items: center;
    gap: 10px;
    animation: toastSlide 0.3s ease-out;
  }
  .adm-toast.error {
    background: #dc2626;
  }
  @keyframes toastSlide {
    from { transform: translateY(-20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
  }

  /* Responsive Rules: On compact screen, hide horizontal nav & show 3-line hamburger */
  @media (max-width: 1024px) {
    .admin-nav-bar {
      display: none !important;
    }
    .admin-right-tools .admin-user-pill,
    .admin-right-tools .btn-portal-link,
    .admin-right-tools .btn-logout-link,
    .admin-right-tools #admLangBtn {
      display: none !important;
    }
    .admin-hamburger-btn {
      display: flex !important;
    }
  }

  @media (max-width: 768px) {
    header.admin-top {
      padding: 8px 12px;
    }
    .admin-brand-logo-img {
      height: 28px;
      max-width: 110px;
    }
    .admin-brand-badge {
      font-size: 0.65rem;
      padding: 1px 5px;
    }
    .tool-btn {
      padding: 5px 8px;
      font-size: 0.8rem;
    }
    .tool-btn .tool-text {
      display: none;
    }
  }
</style>

<!-- Top Sticky Header -->
<header class="admin-top">
  <!-- Dynamic Logo Brand with crisp white background badge -->
  <div class="admin-brand-area">
    <a href="<?= $appUrl ?>/<?= $adminPath ?>" class="admin-brand-link" title="Newslens CMS Dashboard">
      <div class="admin-logo-badge">
        <img src="<?= $siteLogo ?>" alt="Newslensbd" class="admin-brand-logo-img">
      </div>
      <span class="admin-brand-badge">CMS</span>
    </a>
  </div>

  <!-- Navigation Bar (Desktop View) -->
  <nav class="admin-nav-bar" aria-label="Admin Navigation">
    <a href="<?= $appUrl ?>/<?= $adminPath ?>" class="admin-nav-item <?= $activeTab === 'dashboard' ? 'active' : '' ?>" title="ড্যাশবোর্ড / Dashboard">
      <span class="nav-icon">📊</span>
      <span class="nav-text" data-bn="ড্যাশবোর্ড" data-en="Dashboard">ড্যাশবোর্ড</span>
    </a>
    <a href="<?= $appUrl ?>/<?= $adminPath ?>/posts" class="admin-nav-item <?= $activeTab === 'posts' ? 'active' : '' ?>" title="সংবাদ তালিকা / News List">
      <span class="nav-icon">📝</span>
      <span class="nav-text" data-bn="সংবাদ তালিকা" data-en="News List">সংবাদ তালিকা</span>
    </a>
    <a href="<?= $appUrl ?>/<?= $adminPath ?>/ads" class="admin-nav-item <?= $activeTab === 'ads' ? 'active' : '' ?>" title="বিজ্ঞাপন / Ads">
      <span class="nav-icon">📢</span>
      <span class="nav-text" data-bn="বিজ্ঞাপন" data-en="Ads">বিজ্ঞাপন</span>
    </a>
    <a href="<?= $appUrl ?>/<?= $adminPath ?>/polls" class="admin-nav-item <?= $activeTab === 'polls' ? 'active' : '' ?>" title="অনলাইন জরিপ / Polls">
      <span class="nav-icon">📈</span>
      <span class="nav-text" data-bn="অনলাইন জরিপ" data-en="Polls">অনলাইন জরিপ</span>
    </a>
    <a href="<?= $appUrl ?>/<?= $adminPath ?>/subscribers" class="admin-nav-item <?= $activeTab === 'subscribers' ? 'active' : '' ?>" title="নিউজলেটার / Newsletter">
      <span class="nav-icon">✉️</span>
      <span class="nav-text" data-bn="নিউজলেটার" data-en="Newsletter">নিউজলেটার</span>
    </a>
    <a href="<?= $appUrl ?>/<?= $adminPath ?>/settings" class="admin-nav-item <?= $activeTab === 'settings' ? 'active' : '' ?>" title="সাইট ও লোগো / Settings">
      <span class="nav-icon">⚙️</span>
      <span class="nav-text" data-bn="সাইট ও লোগো" data-en="Settings">সাইট ও লোগো</span>
    </a>
  </nav>

  <!-- Right Tools & User Profile -->
  <div class="admin-right-tools">
    <!-- Dark / Light Mode Switcher -->
    <button type="button" class="tool-btn" id="admThemeBtn" title="Toggle Light / Navy Dark Theme">
      <span id="admThemeIcon">🌙</span>
      <span class="tool-text" id="admThemeText">ডার্ক</span>
    </button>

    <!-- Bangla / English Switcher -->
    <button type="button" class="tool-btn" id="admLangBtn" title="বাংলা / English Switcher">
      🌐 <span id="admLangText">EN</span>
    </button>

    <!-- User Profile with quick edit trigger on click (No Gulam Sakaria) -->
    <div class="admin-user-pill" id="admUserPillBtn" title="নাম পরিবর্তন করতে ক্লিক করুন">
      <span>👤</span>
      <span class="user-label"><?= $displayName ?></span>
      <span class="admin-role-tag"><?= htmlspecialchars(strtoupper($user['role'] ?? 'ADMIN')) ?></span>
      <span style="font-size: 0.75rem; color: var(--adm-accent);" title="নাম পরিবর্তন">✏️</span>
    </div>

    <!-- Live Site Link -->
    <a href="<?= $appUrl ?>/" target="_blank" class="btn-portal-link" title="লাইভ সাইট দেখুন">
      <span>🌐</span>
      <span class="tool-text" data-bn="লাইভ সাইট" data-en="Live Site">লাইভ সাইট</span>
    </a>

    <!-- Logout -->
    <a href="<?= $appUrl ?>/<?= $adminPath ?>/logout" class="btn-logout-link" title="লগআউট">
      <span>🚪</span>
      <span class="tool-text" data-bn="লগআউট" data-en="Logout">লগআউট</span>
    </a>

    <!-- 3-Line Hamburger Button for Small Screens / Tabs -->
    <button type="button" class="admin-hamburger-btn" id="adminDrawerOpenBtn" aria-label="মেনু খুলুন" title="মেনু বার">
      <span class="ham-line"></span>
      <span class="ham-line"></span>
      <span class="ham-line"></span>
    </button>
  </div>
</header>

<!-- Mobile/Compact Offcanvas Drawer Backdrop -->
<div class="admin-drawer-backdrop" id="adminDrawerBackdrop"></div>

<!-- Slide-in Navigation Drawer -->
<aside class="admin-drawer" id="adminDrawer" aria-label="মোবাইল অ্যাডমিন মেনু">
  <!-- Drawer Header with User Info & Name Change Trigger -->
  <div class="admin-drawer-head">
    <div class="drawer-user-info">
      <div class="drawer-user-name">
        <span>👤</span>
        <span><?= $displayName ?></span>
      </div>
      <span class="drawer-role-badge"><?= htmlspecialchars(strtoupper($user['role'] ?? 'ADMIN')) ?></span>
      <button type="button" class="drawer-edit-name-btn" id="drawerEditNameBtn">
        ✏️ নাম পরিবর্তন করুন
      </button>
    </div>
    <button type="button" class="admin-drawer-close" id="adminDrawerCloseBtn" aria-label="মেনু বন্ধ করুন" title="বন্ধ করুন">✕</button>
  </div>

  <!-- Drawer Nav Menu Buttons -->
  <nav class="admin-drawer-nav">
    <a href="<?= $appUrl ?>/<?= $adminPath ?>" class="drawer-nav-item <?= $activeTab === 'dashboard' ? 'active' : '' ?>">
      <span class="drawer-nav-icon">📊</span>
      <span data-bn="ড্যাশবোর্ড ওভারভিউ" data-en="Dashboard Overview">ড্যাশবোর্ড ওভারভিউ</span>
    </a>
    <a href="<?= $appUrl ?>/<?= $adminPath ?>/posts" class="drawer-nav-item <?= $activeTab === 'posts' ? 'active' : '' ?>">
      <span class="drawer-nav-icon">📝</span>
      <span data-bn="সংবাদ তালিকা" data-en="News List">সংবাদ তালিকা</span>
    </a>
    <a href="<?= $appUrl ?>/<?= $adminPath ?>/posts/create" class="drawer-nav-item" style="color: #4ade80;">
      <span class="drawer-nav-icon">✍️</span>
      <span data-bn="নতুন খবর লিখুন" data-en="Create News Post">নতুন খবর লিখুন</span>
    </a>
    <a href="<?= $appUrl ?>/<?= $adminPath ?>/ads" class="drawer-nav-item <?= $activeTab === 'ads' ? 'active' : '' ?>">
      <span class="drawer-nav-icon">📢</span>
      <span data-bn="বিজ্ঞাপন ম্যানেজমেন্ট" data-en="Advertisements">বিজ্ঞাপন ম্যানেজমেন্ট</span>
    </a>
    <a href="<?= $appUrl ?>/<?= $adminPath ?>/polls" class="drawer-nav-item <?= $activeTab === 'polls' ? 'active' : '' ?>">
      <span class="drawer-nav-icon">📈</span>
      <span data-bn="অনলাইন জরিপ" data-en="Online Polls">অনলাইন জরিপ</span>
    </a>
    <a href="<?= $appUrl ?>/<?= $adminPath ?>/subscribers" class="drawer-nav-item <?= $activeTab === 'subscribers' ? 'active' : '' ?>">
      <span class="drawer-nav-icon">✉️</span>
      <span data-bn="নিউজলেটার গ্রাহক" data-en="Newsletter">নিউজলেটার গ্রাহক</span>
    </a>
    <a href="<?= $appUrl ?>/<?= $adminPath ?>/settings" class="drawer-nav-item <?= $activeTab === 'settings' ? 'active' : '' ?>">
      <span class="drawer-nav-icon">⚙️</span>
      <span data-bn="সাইট সেটিংস ও লোগো" data-en="Settings & Logo">সাইট সেটিংস ও লোগো</span>
    </a>
  </nav>

  <!-- Drawer Footer Actions -->
  <div class="admin-drawer-foot">
    <div style="display: flex; gap: 8px;">
      <button type="button" class="tool-btn" id="drawerThemeBtn" style="flex: 1; justify-content: center;">
        <span id="drawerThemeIcon">🌙</span>
        <span id="drawerThemeText">ডার্ক মোড</span>
      </button>
      <button type="button" class="tool-btn" id="drawerLangBtn" style="flex: 1; justify-content: center;">
        🌐 <span id="drawerLangText">English</span>
      </button>
    </div>

    <a href="<?= $appUrl ?>/" target="_blank" class="btn-portal-link" style="justify-content: center;">
      <span>🌐</span>
      <span data-bn="লাইভ নিউজ পোর্টাল" data-en="Live News Portal">লাইভ নিউজ পোর্টাল</span>
    </a>

    <a href="<?= $appUrl ?>/<?= $adminPath ?>/logout" class="btn-logout-link" style="justify-content: center;">
      <span>🚪</span>
      <span data-bn="লগআউট" data-en="Logout">লগআউট</span>
    </a>
  </div>
</aside>

<!-- Quick Name & Profile Change Modal (Universally available) -->
<div class="adm-modal-backdrop" id="admProfileModal">
  <div class="adm-modal-box">
    <div class="adm-modal-head">
      <div class="adm-modal-title">
        <span>👤</span>
        <span data-bn="অ্যাডমিন নাম ও প্রোফাইল পরিবর্তন" data-en="Change Admin Profile & Name">অ্যাডমিন নাম ও প্রোফাইল পরিবর্তন</span>
      </div>
      <button type="button" class="adm-modal-close" id="admProfileModalCloseBtn" aria-label="বন্ধ করুন">✕</button>
    </div>

    <form action="<?= $appUrl ?>/<?= $adminPath ?>/profile/update" method="post">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
      <input type="hidden" name="redirect_to" value="<?= $currentUrl ?>">

      <div class="adm-form-group">
        <label for="modalAdminName" data-bn="আপনার পুরো নাম *" data-en="Your Full Name *">আপনার পুরো নাম *</label>
        <input type="text" name="admin_name" id="modalAdminName" required value="<?= $displayName ?>" placeholder="যেমন: Super Admin">
      </div>

      <div class="adm-form-group">
        <label for="modalAdminPhone" data-bn="মোবাইল / ফোন নম্বর" data-en="Phone Number">মোবাইল / ফোন নম্বর</label>
        <input type="tel" name="admin_phone" id="modalAdminPhone" value="<?= $userPhone ?>" placeholder="যেমন: 01700000000">
      </div>

      <div class="adm-form-group">
        <label for="modalAdminPassword" data-bn="নতুন পাসওয়ার্ড (পরিবর্তন না করতে চাইলে ফাঁকা রাখুন)" data-en="New Password (leave empty to keep current)">নতুন পাসওয়ার্ড (পরিবর্তন না করতে চাইলে ফাঁকা রাখুন)</label>
        <input type="password" name="admin_password" id="modalAdminPassword" placeholder="••••••••">
      </div>

      <div class="adm-form-group">
        <label for="modalAdminPasswordConfirm" data-bn="পাসওয়ার্ড নিশ্চিত করুন" data-en="Confirm Password">পাসওয়ার্ড নিশ্চিত করুন</label>
        <input type="password" name="admin_password_confirm" id="modalAdminPasswordConfirm" placeholder="••••••••">
      </div>

      <div class="adm-modal-actions">
        <button type="button" class="adm-btn-cancel" id="admProfileModalCancelBtn" data-bn="বাতিল" data-en="Cancel">বাতিল</button>
        <button type="submit" class="adm-btn-save" data-bn="সংরক্ষণ করুন ➔" data-en="Save Changes ➔">সংরক্ষণ করুন ➔</button>
      </div>
    </form>
  </div>
</div>

<!-- Toast Notifications for Profile Updates -->
<?php if (isset($_GET['msg']) && $_GET['msg'] === 'profile_updated'): ?>
  <div class="adm-toast" id="admToastMsg">
    <span>✅</span>
    <span>প্রোফাইল ও নাম সফলভাবে সংরক্ষিত হয়েছে!</span>
  </div>
<?php elseif (isset($_GET['err'])): ?>
  <div class="adm-toast error" id="admToastMsg">
    <span>⚠️</span>
    <span>
      <?php 
        if ($_GET['err'] === 'pwd_short') echo 'পাসওয়ার্ড ন্যূনতম ৬ অক্ষরের হতে হবে!';
        elseif ($_GET['err'] === 'pwd_mismatch') echo 'পাসওয়ার্ড এবং কনফার্ম পাসওয়ার্ড মেলেনি!';
        else echo 'তথ্য আপডেট করার সময় একটি ত্রুটি ঘটেছে!';
      ?>
    </span>
  </div>
<?php endif; ?>

<script>
  (function() {
    // 1. Theme Management (Navy Dark default vs Light Mode)
    const themeBtn = document.getElementById('admThemeBtn');
    const themeIcon = document.getElementById('admThemeIcon');
    const themeText = document.getElementById('admThemeText');
    const drawerThemeBtn = document.getElementById('drawerThemeBtn');
    const drawerThemeIcon = document.getElementById('drawerThemeIcon');
    const drawerThemeText = document.getElementById('drawerThemeText');
    const savedTheme = localStorage.getItem('nl_admin_theme') || 'dark';

    function applyTheme(theme) {
      if (theme === 'light') {
        document.body.classList.add('admin-light');
        if (themeIcon) themeIcon.textContent = '☀️';
        if (themeText) themeText.textContent = 'লাইট';
        if (drawerThemeIcon) drawerThemeIcon.textContent = '☀️';
        if (drawerThemeText) drawerThemeText.textContent = 'লাইট মোড';
      } else {
        document.body.classList.remove('admin-light');
        if (themeIcon) themeIcon.textContent = '🌙';
        if (themeText) themeText.textContent = 'ডার্ক';
        if (drawerThemeIcon) drawerThemeIcon.textContent = '🌙';
        if (drawerThemeText) drawerThemeText.textContent = 'ডার্ক মোড';
      }
      localStorage.setItem('nl_admin_theme', theme);
    }

    applyTheme(savedTheme);

    function toggleTheme() {
      const current = document.body.classList.contains('admin-light') ? 'light' : 'dark';
      applyTheme(current === 'light' ? 'dark' : 'light');
    }

    if (themeBtn) themeBtn.addEventListener('click', toggleTheme);
    if (drawerThemeBtn) drawerThemeBtn.addEventListener('click', toggleTheme);

    // 2. Language Management (Bangla / English)
    const langBtn = document.getElementById('admLangBtn');
    const langText = document.getElementById('admLangText');
    const drawerLangBtn = document.getElementById('drawerLangBtn');
    const drawerLangText = document.getElementById('drawerLangText');
    let currentLang = localStorage.getItem('nl_admin_lang') || 'bn';

    function applyLanguage(lang) {
      currentLang = lang;
      localStorage.setItem('nl_admin_lang', lang);
      if (langText) langText.textContent = lang === 'bn' ? 'EN' : 'বাং';
      if (drawerLangText) drawerLangText.textContent = lang === 'bn' ? 'English' : 'বাংলা';

      document.querySelectorAll('[data-bn][data-en]').forEach(el => {
        const text = el.getAttribute('data-' + lang);
        if (text) {
          el.textContent = text;
        }
      });
    }

    applyLanguage(currentLang);

    function toggleLanguage() {
      applyLanguage(currentLang === 'bn' ? 'en' : 'bn');
    }

    if (langBtn) langBtn.addEventListener('click', toggleLanguage);
    if (drawerLangBtn) drawerLangBtn.addEventListener('click', toggleLanguage);

    // 3. Hamburger Drawer Toggle Logic
    const drawerOpenBtn = document.getElementById('adminDrawerOpenBtn');
    const drawerCloseBtn = document.getElementById('adminDrawerCloseBtn');
    const drawerBackdrop = document.getElementById('adminDrawerBackdrop');
    const drawer = document.getElementById('adminDrawer');

    function openDrawer() {
      if (drawer) drawer.classList.add('active');
      if (drawerBackdrop) drawerBackdrop.classList.add('active');
      document.body.style.overflow = 'hidden';
    }

    function closeDrawer() {
      if (drawer) drawer.classList.remove('active');
      if (drawerBackdrop) drawerBackdrop.classList.remove('active');
      document.body.style.overflow = '';
    }

    if (drawerOpenBtn) drawerOpenBtn.addEventListener('click', openDrawer);
    if (drawerCloseBtn) drawerCloseBtn.addEventListener('click', closeDrawer);
    if (drawerBackdrop) drawerBackdrop.addEventListener('click', closeDrawer);

    // 4. Admin Profile / Name Modal Logic
    const profileModal = document.getElementById('admProfileModal');
    const userPillBtn = document.getElementById('admUserPillBtn');
    const drawerEditBtn = document.getElementById('drawerEditNameBtn');
    const modalCloseBtn = document.getElementById('admProfileModalCloseBtn');
    const modalCancelBtn = document.getElementById('admProfileModalCancelBtn');

    function openProfileModal() {
      closeDrawer();
      if (profileModal) profileModal.classList.add('active');
      const nameInput = document.getElementById('modalAdminName');
      if (nameInput) {
        setTimeout(() => nameInput.focus(), 100);
      }
    }

    function closeProfileModal() {
      if (profileModal) profileModal.classList.remove('active');
    }

    if (userPillBtn) userPillBtn.addEventListener('click', openProfileModal);
    if (drawerEditBtn) drawerEditBtn.addEventListener('click', openProfileModal);
    if (modalCloseBtn) modalCloseBtn.addEventListener('click', closeProfileModal);
    if (modalCancelBtn) modalCancelBtn.addEventListener('click', closeProfileModal);

    if (profileModal) {
      profileModal.addEventListener('click', function(e) {
        if (e.target === profileModal) {
          closeProfileModal();
        }
      });
    }

    // Keyboard navigation (Escape key closes drawer/modal)
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        closeDrawer();
        closeProfileModal();
      }
    });

    // Auto dismiss toast after 4 seconds
    const toast = document.getElementById('admToastMsg');
    if (toast) {
      setTimeout(() => {
        toast.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-20px)';
        setTimeout(() => toast.remove(), 400);
      }, 4000);
    }
  })();
</script>

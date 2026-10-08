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
        <!-- Sleek Dark Mode Toggle (Navy Blue Theme - Picture 1 Fix) -->
        <button type="button" class="theme-toggle-pill" id="theme-toggle" aria-label="ডার্ক মোড পরিবর্তন" title="ডার্ক মোড">
          <span class="pill-slider">
            <span class="pill-icon-sun">☀️</span>
            <span class="pill-icon-moon">🌙</span>
          </span>
          <span class="theme-mode-text" id="theme-text" data-bn="ডার্ক" data-en="Dark">ডার্ক</span>
        </button>
        <!-- Notice: 'f yt x' plain text links removed per user instruction -->
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

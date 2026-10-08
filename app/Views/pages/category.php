<?php
use App\Helpers\BanglaDate;
$appUrl = rtrim($config['app']['url'] ?? '', '/');
?>
<div class="category-page-container container">
  <!-- Breadcrumb -->
  <nav class="breadcrumb-nav" aria-label="ব্রেডক্রাম্ব">
    <ol class="breadcrumb-list">
      <li><a href="<?= $appUrl ?>/" data-bn="প্রচ্ছদ" data-en="Home">প্রচ্ছদ</a></li>
      <li><span class="sep">/</span></li>
      <li class="active" data-bn="<?= htmlspecialchars($currentCategory['name_bn']) ?>" data-en="<?= htmlspecialchars($currentCategory['name_en'] ?? $currentCategory['name_bn']) ?>"><?= htmlspecialchars($currentCategory['name_bn']) ?></li>
    </ol>
  </nav>

  <!-- Category Title Banner -->
  <header class="category-header-banner">
    <h1 class="cat-page-title">
      <span class="cat-accent-bar"></span>
      <span class="cat-page-title-text" data-bn="<?= htmlspecialchars($currentCategory['name_bn']) ?>" data-en="<?= htmlspecialchars($currentCategory['name_en'] ?? $currentCategory['name_bn']) ?>"><?= htmlspecialchars($currentCategory['name_bn']) ?></span>
    </h1>
    <span class="cat-news-count" data-bn="মোট সংবাদ: <?= BanglaDate::bnNum(count($posts)) ?> টি" data-en="Total News: <?= count($posts) ?>">
      মোট সংবাদ: <?= BanglaDate::bnNum(count($posts)) ?> টি
    </span>
  </header>

  <div class="main-layout-grid">
    <!-- Category News List Column (70%) -->
    <main class="news-column-main">
      <?php if (!empty($posts)): ?>
        <div class="cat-news-grid">
          <?php foreach ($posts as $post): ?>
            <article class="cat-news-card">
              <?php if (!empty($post['featured_image'])): ?>
                <div class="cat-card-thumb">
                  <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($currentCategory['slug']) ?>/<?= $post['id'] ?>/<?= htmlspecialchars($post['slug']) ?>">
                    <img src="<?= htmlspecialchars($post['featured_image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" loading="lazy" width="300" height="190">
                  </a>
                </div>
              <?php endif; ?>
              <div class="cat-card-body">
                <h2 class="cat-card-title">
                  <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($currentCategory['slug']) ?>/<?= $post['id'] ?>/<?= htmlspecialchars($post['slug']) ?>">
                    <?= htmlspecialchars($post['title']) ?>
                  </a>
                </h2>
                <?php if (!empty($post['excerpt'])): ?>
                  <p class="cat-card-excerpt"><?= htmlspecialchars($post['excerpt']) ?></p>
                <?php endif; ?>
                <div class="meta-row-tiny">
                  <span class="meta-time">⏱️ <?= BanglaDate::timeAgo($post['published_at']) ?></span>
                  <?php if (!empty($post['district_name'])): ?>
                    <span class="meta-dist">📍 <?= htmlspecialchars($post['district_name']) ?></span>
                  <?php endif; ?>
                  <span class="meta-views">👁️ <?= BanglaDate::bnNum($post['views'] ?? 0) ?></span>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="empty-state-box">
          <p data-bn="এই বিভাগে আপাতত কোনো সংবাদ পাওয়া যায়নি।" data-en="No news found in this category right now.">এই বিভাগে আপাতত কোনো সংবাদ পাওয়া যায়নি।</p>
        </div>
      <?php endif; ?>
    </main>

    <!-- Sidebar Column (30%) -->
    <aside class="sidebar-column" aria-label="সাইডবার">
      <!-- Popular News Widget -->
      <div class="sidebar-widget popular-widget">
        <div class="widget-header">
          <h3 class="widget-title" data-bn="জনপ্রিয় সংবাদ" data-en="Popular News">জনপ্রিয় সংবাদ</h3>
        </div>
        <ol class="tab-news-list">
          <?php foreach ($popularPosts as $idx => $pop): ?>
            <li class="tab-news-item">
              <span class="num-counter popular-counter"><?= BanglaDate::bnNum($idx + 1) ?></span>
              <div class="item-details">
                <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($pop['category_slug'] ?? 'national') ?>/<?= $pop['id'] ?>/<?= htmlspecialchars($pop['slug'] ?? '') ?>" class="item-title">
                  <?= htmlspecialchars($pop['title']) ?>
                </a>
                <span class="meta-time-tiny">👁️ <?= BanglaDate::bnNum($pop['views'] ?? 100) ?> বার পঠিত</span>
              </div>
            </li>
          <?php endforeach; ?>
        </ol>
      </div>

      <!-- Advertisement Slot -->
      <div class="sidebar-widget sidebar-ad-widget" aria-label="বিজ্ঞাপন">
        <div class="widget-header">
          <h4 class="widget-title" data-bn="বিজ্ঞাপন" data-en="Advertisement">বিজ্ঞাপন</h4>
        </div>
        <div class="sidebar-ad-content">
          <?= \App\Helpers\AdBanner::render('sidebar') ?>
        </div>
      </div>
    </aside>
  </div>
</div>

<?php
use App\Helpers\BanglaDate;
$appUrl = rtrim($config['app']['url'] ?? '', '/');
$author = $author ?? [];
$posts = $posts ?? [];
$postCount = $postCount ?? count($posts);
$totalViews = $totalViews ?? 0;
$socialLinks = $socialLinks ?? [];
$avatarUrl = !empty($author['avatar']) ? ($appUrl . '/' . htmlspecialchars($author['avatar'])) : ($appUrl . '/assets/img/default-avatar.png');
?>
<div class="category-page-container container">
  <!-- Breadcrumb -->
  <nav class="breadcrumb-nav" aria-label="ব্রেডক্রাম্ব">
    <ol class="breadcrumb-list">
      <li><a href="<?= $appUrl ?>/" data-bn="প্রচ্ছদ" data-en="Home">প্রচ্ছদ</a></li>
      <li><span class="sep">/</span></li>
      <li data-bn="লেখক" data-en="Author">লেখক</li>
      <li><span class="sep">/</span></li>
      <li class="active"><?= htmlspecialchars($author['name']) ?></li>
    </ol>
  </nav>

  <!-- Author Profile Card -->
  <header style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 28px; margin-bottom: 30px; display: flex; gap: 24px; align-items: center; flex-wrap: wrap; box-shadow: 0 4px 15px rgba(0,0,0,0.04);">
    <img src="<?= $avatarUrl ?>" alt="<?= htmlspecialchars($author['name']) ?>" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid #0E7A3A; flex-shrink: 0;" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($author['name']) ?>&background=0E7A3A&color=fff'">
    
    <div style="flex: 1; min-width: 260px;">
      <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 6px;">
        <h1 style="font-size: 1.7rem; font-weight: 800; color: #0f172a; margin: 0;"><?= htmlspecialchars($author['name']) ?></h1>
        <span style="background: #0E7A3A; color: #fff; font-size: 0.72rem; font-weight: 700; padding: 3px 10px; border-radius: 12px; text-transform: uppercase;">
          <?= ($author['role'] === 'writer') ? 'লেখক' : (($author['role'] === 'editor') ? 'সম্পাদক' : 'সংবাদদাতা') ?>
        </span>
      </div>

      <?php if (!empty($author['short_bio'])): ?>
        <p style="color: #475569; font-size: 0.95rem; line-height: 1.6; margin: 6px 0 12px; max-width: 750px;">
          <?= nl2br(htmlspecialchars($author['short_bio'])) ?>
        </p>
      <?php endif; ?>

      <div style="display: flex; gap: 20px; flex-wrap: wrap; font-size: 0.88rem; color: #64748b; align-items: center;">
        <span>📝 <strong><?= BanglaDate::bnNum($postCount) ?></strong> টি প্রতিবেদন</span>
        <span>👁️ <strong><?= BanglaDate::bnNum($totalViews) ?></strong> বার পঠিত</span>
        
        <?php if (!empty($socialLinks['facebook'])): ?>
          <a href="<?= htmlspecialchars($socialLinks['facebook']) ?>" target="_blank" rel="noopener" style="color: #1877f2; text-decoration: none; font-weight: 600;">Facebook ↗</a>
        <?php endif; ?>
        <?php if (!empty($socialLinks['twitter'])): ?>
          <a href="<?= htmlspecialchars($socialLinks['twitter']) ?>" target="_blank" rel="noopener" style="color: #0f172a; text-decoration: none; font-weight: 600;">X (Twitter) ↗</a>
        <?php endif; ?>
        <?php if (!empty($socialLinks['linkedin'])): ?>
          <a href="<?= htmlspecialchars($socialLinks['linkedin']) ?>" target="_blank" rel="noopener" style="color: #0a66c2; text-decoration: none; font-weight: 600;">LinkedIn ↗</a>
        <?php endif; ?>
      </div>
    </div>
  </header>

  <!-- News List Grid -->
  <div class="main-layout-grid">
    <main class="news-column-main">
      <?php if (!empty($posts)): ?>
        <div class="cat-news-grid">
          <?php foreach ($posts as $post): ?>
            <article class="cat-news-card">
              <?php if (!empty($post['featured_image'])): ?>
                <div class="cat-card-thumb">
                  <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($post['category_slug'] ?? 'national') ?>/<?= (int)$post['id'] ?>/<?= htmlspecialchars($post['slug']) ?>">
                    <img src="<?= htmlspecialchars(imageUrl($post['featured_image'], $appUrl)) ?>" alt="<?= htmlspecialchars($post['title']) ?>" loading="lazy" width="300" height="190">
                  </a>
                </div>
              <?php endif; ?>
              <div class="cat-card-body">
                <span style="display: inline-block; background: #e0f2fe; color: #0369a1; font-size: 0.75rem; font-weight: 700; padding: 2px 8px; border-radius: 4px; margin-bottom: 6px;">
                  <?= htmlspecialchars($post['category_name']) ?>
                </span>
                <h2 class="cat-card-title">
                  <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($post['category_slug'] ?? 'national') ?>/<?= (int)$post['id'] ?>/<?= htmlspecialchars($post['slug']) ?>">
                    <?= htmlspecialchars($post['title']) ?>
                  </a>
                </h2>
                <?php if (!empty($post['excerpt'])): ?>
                  <p class="cat-card-excerpt"><?= htmlspecialchars($post['excerpt']) ?></p>
                <?php endif; ?>
                <div class="meta-row-tiny">
                  <span class="meta-time">⏱️ <?= BanglaDate::timeAgo($post['published_at'] ?? $post['created_at']) ?></span>
                  <span class="meta-views">👁️ <?= BanglaDate::bnNum($post['views'] ?? 0) ?></span>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div style="text-align: center; padding: 60px 20px; background: #fff; border-radius: 12px; border: 1px solid #e2e8f0;">
          <p style="color: #64748b; font-size: 1.1rem;">এই লেখকের এখনো কোনো প্রকাশিত প্রতিবেদন নেই।</p>
          <a href="<?= $appUrl ?>/" style="color: #0E7A3A; font-weight: 700; text-decoration: none; margin-top: 10px; display: inline-block;">← প্রচ্ছদে ফিরে যান</a>
        </div>
      <?php endif; ?>
    </main>

    <!-- Sidebar Column (30%) -->
    <aside class="sidebar-column">
      <div class="sidebar-widget">
        <h3 class="widget-title" style="border-left: 4px solid #0E7A3A; padding-left: 8px;">
          <span data-bn="শীর্ষ সংবাদ" data-en="Top News">শীর্ষ সংবাদ</span>
        </h3>
        <div class="widget-content">
          <?php if (!empty($breakingNews)): ?>
            <ul style="list-style: none; padding: 0; margin: 0;">
              <?php foreach (array_slice($breakingNews, 0, 5) as $bnPost): ?>
                <li style="padding: 10px 0; border-bottom: 1px solid #e2e8f0;">
                  <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($bnPost['category_slug'] ?? 'national') ?>/<?= (int)$bnPost['id'] ?>/<?= htmlspecialchars($bnPost['slug']) ?>" style="text-decoration: none; color: #1e293b; font-weight: 600; font-size: 0.95rem; line-height: 1.4;">
                    <?= htmlspecialchars($bnPost['title']) ?>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
        </div>
      </div>
    </aside>
  </div>
</div>

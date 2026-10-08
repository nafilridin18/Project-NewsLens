<?php
$breakingNews = $breakingNews ?? [];
$appUrl = rtrim($config['app']['url'] ?? '', '/');
if (!empty($breakingNews)):
?>
<section class="breaking-news-section" aria-label="ব্রেকিং নিউজ">
  <div class="container breaking-inner">
    <div class="breaking-badge">
      <span class="live-dot" aria-hidden="true"></span>
      <span class="badge-text">ব্রেকিং</span>
    </div>
    <div class="breaking-ticker-viewport" id="ticker-viewport">
      <ul class="breaking-list" id="breaking-list">
        <?php foreach ($breakingNews as $idx => $news): ?>
          <li class="ticker-item <?= $idx === 0 ? 'active' : '' ?>">
            <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($news['category_slug'] ?? 'national') ?>/<?= $news['id'] ?>/<?= htmlspecialchars($news['slug'] ?? '') ?>" class="ticker-link">
              <?= htmlspecialchars($news['title']) ?>
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div class="ticker-controls">
      <button type="button" class="ticker-nav-btn prev" id="ticker-prev" aria-label="পূর্ববর্তী সংবাদ">❮</button>
      <button type="button" class="ticker-nav-btn next" id="ticker-next" aria-label="পরবর্তী সংবাদ">❯</button>
    </div>
  </div>
</section>
<?php endif; ?>

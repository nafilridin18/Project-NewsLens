<?php
use App\Helpers\BanglaDate;
$appUrl = rtrim($config['app']['url'] ?? '', '/');
$currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
?>
<div class="single-article-container container">
  <!-- Breadcrumb -->
  <nav class="breadcrumb-nav" aria-label="ব্রেডক্রাম্ব">
    <ol class="breadcrumb-list">
      <li><a href="<?= $appUrl ?>/" data-bn="প্রচ্ছদ" data-en="Home">প্রচ্ছদ</a></li>
      <li><span class="sep">/</span></li>
      <li><a href="<?= $appUrl ?>/category/<?= htmlspecialchars($post['category_slug']) ?>"><?= htmlspecialchars($post['category_name']) ?></a></li>
      <?php if (!empty($post['district_name'])): ?>
        <li><span class="sep">/</span></li>
        <li><a href="<?= $appUrl ?>/saradesh?district=<?= $post['district_id'] ?>"><?= htmlspecialchars($post['district_name']) ?></a></li>
      <?php endif; ?>
    </ol>
  </nav>

  <div class="main-layout-grid">
    <!-- Main Article Body Column (70%) -->
    <main class="news-column-main article-main-wrapper">
      <article class="single-article-content">
        <!-- Header -->
        <header class="article-header">
          <div class="article-category-badge-wrap">
            <span class="category-badge"><?= htmlspecialchars($post['category_name']) ?></span>
            <?php if (!empty($post['is_breaking'])): ?>
              <span class="breaking-badge-pill" data-bn="● ব্রেকিং" data-en="● BREAKING">● ব্রেকিং</span>
            <?php endif; ?>
          </div>

          <h1 class="article-main-title"><?= htmlspecialchars($post['title']) ?></h1>

          <?php if (!empty($post['subtitle'])): ?>
            <p class="article-subtitle"><?= htmlspecialchars($post['subtitle']) ?></p>
          <?php endif; ?>

          <!-- Meta Author, Date, Views -->
          <div class="article-meta-bar">
            <div class="author-meta-box">
              <span class="author-avatar">✍️</span>
              <div class="author-text">
                <span class="author-name"><?= htmlspecialchars($post['author_name'] ?? 'স্টাফ রিপোর্টার') ?></span>
                <?php if (!empty($post['district_name'])): ?>
                  <span class="author-loc">| <?= htmlspecialchars($post['district_name']) ?></span>
                <?php endif; ?>
              </div>
            </div>

            <div class="time-meta-box">
              <span class="publish-time">⏱️ <?= BanglaDate::formatBnDate($post['published_at'] ?? $post['created_at']) ?></span>
              <span class="views-counter-badge">👁️ <?= BanglaDate::bnNum($post['views'] ?? 1) ?> বার পঠিত</span>
            </div>
          </div>

          <!-- Social Share Bar -->
          <div class="article-share-bar">
            <span class="share-label" data-bn="শেয়ার করুন:" data-en="Share:">শেয়ার করুন:</span>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($currentUrl) ?>" target="_blank" rel="noopener" class="share-btn fb-share" title="Facebook-এ শেয়ার">
              Facebook
            </a>
            <a href="https://api.whatsapp.com/send?text=<?= urlencode($post['title'] . ' ' . $currentUrl) ?>" target="_blank" rel="noopener" class="share-btn wa-share" title="WhatsApp-এ শেয়ার">
              WhatsApp
            </a>
            <a href="https://twitter.com/intent/tweet?text=<?= urlencode($post['title']) ?>&url=<?= urlencode($currentUrl) ?>" target="_blank" rel="noopener" class="share-btn tw-share" title="X-এ শেয়ার">
              X
            </a>
            <button type="button" class="share-btn copy-share" id="copy-article-link" data-url="<?= htmlspecialchars($currentUrl) ?>" title="লিঙ্ক কপি করুন" data-bn="🔗 কপি লিঙ্ক" data-en="🔗 Copy Link">
              🔗 কপি লিঙ্ক
            </button>
          </div>
        </header>

        <!-- Media: Featured Image or Video -->
        <?php if (!empty($post['featured_image'])): ?>
          <figure class="article-featured-figure">
            <img src="<?= htmlspecialchars($post['featured_image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="article-featured-img" width="850" height="480">
            <?php if (!empty($post['excerpt'])): ?>
              <figcaption class="article-featured-caption"><?= htmlspecialchars($post['excerpt']) ?></figcaption>
            <?php endif; ?>
          </figure>
        <?php endif; ?>

        <!-- Video Player Section (if video available) -->
        <?php if (!empty($post['video_url'])): ?>
          <div class="article-video-player-container">
            <h3 class="video-player-title" data-bn="📺 ভিডিও প্রতিবেদন" data-en="📺 Video Report">📺 ভিডিও প্রতিবেদন</h3>
            <?php 
              // Check if YouTube
              $videoUrl = $post['video_url'];
              $embedUrl = '';
              if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/', $videoUrl, $m)) {
                  $embedUrl = "https://www.youtube.com/embed/" . $m[1];
              }
            ?>
            <?php if ($embedUrl): ?>
              <div class="video-embed-responsive">
                <iframe src="<?= $embedUrl ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
              </div>
            <?php else: ?>
              <div class="custom-video-box">
                <video controls style="width:100%; border-radius:8px;">
                  <source src="<?= htmlspecialchars($videoUrl) ?>" type="video/mp4">
                  আপনার ব্রাউজার এই ভিডিও সাপোর্ট করছে না।
                </video>
              </div>
            <?php endif; ?>
          </div>
        <?php endif; ?>

        <!-- Body -->
        <div class="article-body-text">
          <?= $post['body'] ?>
        </div>

        <!-- Article Bottom Share & Tags -->
        <footer class="article-footer-meta">
          <div class="tag-pills-list">
            <span class="tag-title" data-bn="সম্পর্কিত টপিক:" data-en="Related Topics:">সম্পর্কিত টপিক:</span>
            <a href="<?= $appUrl ?>/category/<?= htmlspecialchars($post['category_slug']) ?>" class="topic-pill"><?= htmlspecialchars($post['category_name']) ?></a>
            <?php if (!empty($post['district_name'])): ?>
              <a href="<?= $appUrl ?>/saradesh?district=<?= $post['district_id'] ?>" class="topic-pill"><?= htmlspecialchars($post['district_name']) ?></a>
            <?php endif; ?>
          </div>
        </footer>
      </article>

      <!-- Related News Grid -->
      <?php if (!empty($relatedPosts)): ?>
        <section class="related-news-section" aria-label="সম্পর্কিত সংবাদ">
          <div class="section-header">
            <h3 class="section-title"><span class="title-accent" data-bn="সম্পর্কিত সংবাদ" data-en="Related News">সম্পর্কিত সংবাদ</span></h3>
          </div>
          <div class="related-cards-grid">
            <?php foreach ($relatedPosts as $rPost): ?>
              <article class="news-card related-card">
                <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($rPost['category_slug']) ?>/<?= $rPost['id'] ?>/<?= htmlspecialchars($rPost['slug']) ?>">
                  <?php if (!empty($rPost['featured_image'])): ?>
                    <img src="<?= htmlspecialchars($rPost['featured_image']) ?>" alt="<?= htmlspecialchars($rPost['title']) ?>" class="related-thumb" loading="lazy">
                  <?php endif; ?>
                  <h4 class="card-title"><?= htmlspecialchars($rPost['title']) ?></h4>
                </a>
                <span class="meta-time-sm">⏱️ <?= BanglaDate::timeAgo($rPost['published_at']) ?></span>
              </article>
            <?php endforeach; ?>
          </div>
        </section>
      <?php endif; ?>
    </main>

    <!-- Sidebar Column (30%) -->
    <aside class="sidebar-column" aria-label="সাইডবার">
      <!-- Popular News Widget -->
      <div class="sidebar-widget popular-widget">
        <div class="widget-header">
          <h3 class="widget-title" data-bn="জনপ্রিয় সংবাদ" data-en="Most Read News">জনপ্রিয় সংবাদ</h3>
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
      <div class="sidebar-widget ad-widget" aria-label="বিজ্ঞাপন">
        <div class="ad-placeholder sidebar-ad">
          <span class="ad-label" data-bn="বিজ্ঞাপন (৩০০ × ২৫০)" data-en="ADVERTISEMENT (300 × 250)">বিজ্ঞাপন (৩০০ × ২৫০)</span>
        </div>
      </div>
    </aside>
  </div>
</div>

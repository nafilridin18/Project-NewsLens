<?php
use App\Helpers\BanglaDate;
$appUrl = rtrim($config['app']['url'] ?? '', '/');
$mainLead = $leadStories['main'] ?? null;
$secondaryLeads = $leadStories['secondary'] ?? [];
$activePoll = $activePoll ?? null;
$videoPosts = $videoPosts ?? [];
?>
<div class="homepage-container container">

  <!-- ================= 1. LEAD NEWS SECTION ================= -->
  <section class="lead-news-section" aria-label="প্রধান সংবাদ">
    <div class="lead-grid">
      <!-- Main Lead (Hero Card) -->
      <?php if ($mainLead): ?>
      <article class="hero-lead-card">
        <div class="hero-media">
          <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($mainLead['category_slug']) ?>/<?= $mainLead['id'] ?>/<?= htmlspecialchars($mainLead['slug']) ?>">
            <img src="<?= htmlspecialchars($mainLead['featured_image']) ?>" alt="<?= htmlspecialchars($mainLead['title']) ?>" loading="eager" width="800" height="450">
          </a>
          <span class="category-badge"><?= htmlspecialchars($mainLead['category_name']) ?></span>
        </div>
        <div class="hero-content">
          <h1 class="hero-title">
            <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($mainLead['category_slug']) ?>/<?= $mainLead['id'] ?>/<?= htmlspecialchars($mainLead['slug']) ?>">
              <?= htmlspecialchars($mainLead['title']) ?>
            </a>
          </h1>
          <p class="hero-excerpt"><?= htmlspecialchars($mainLead['excerpt']) ?></p>
          <div class="meta-row">
            <span class="meta-author">✍️ <?= htmlspecialchars($mainLead['author_name'] ?? 'স্টাফ রিপোর্টার') ?></span>
            <span class="meta-time">⏱️ <?= BanglaDate::timeAgo($mainLead['published_at']) ?></span>
            <span class="meta-views">👁️ <?= BanglaDate::bnNum($mainLead['views'] ?? 0) ?> বার পঠিত</span>
          </div>
        </div>
      </article>
      <?php endif; ?>

      <!-- Secondary Leads (Right Grid) -->
      <div class="secondary-leads-grid">
        <?php foreach ($secondaryLeads as $sLead): ?>
          <article class="sub-lead-card">
            <div class="sub-lead-thumb">
              <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($sLead['category_slug']) ?>/<?= $sLead['id'] ?>/<?= htmlspecialchars($sLead['slug']) ?>">
                <img src="<?= htmlspecialchars($sLead['featured_image']) ?>" alt="<?= htmlspecialchars($sLead['title']) ?>" loading="lazy" width="280" height="175">
              </a>
              <span class="category-badge-sm"><?= htmlspecialchars($sLead['category_name']) ?></span>
            </div>
            <div class="sub-lead-body">
              <h2 class="sub-lead-title">
                <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($sLead['category_slug']) ?>/<?= $sLead['id'] ?>/<?= htmlspecialchars($sLead['slug']) ?>">
                  <?= htmlspecialchars($sLead['title']) ?>
                </a>
              </h2>
              <span class="meta-time-sm">⏱️ <?= BanglaDate::timeAgo($sLead['published_at']) ?></span>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Middle Banner Ad Slot (970x90) -->
  <div class="banner-ad-container" aria-label="বিজ্ঞাপন">
    <div class="ad-placeholder banner-ad">
      <span class="ad-label" data-bn="বিজ্ঞাপন (৯৭০ × ৯০)" data-en="ADVERTISEMENT (970 × 90)">বিজ্ঞাপন (৯৭০ × ৯০)</span>
    </div>
  </div>

  <!-- ================= 2. MAIN CONTENT + SIDEBAR ================= -->
  <div class="main-layout-grid">
    
    <!-- Primary News Column (70%) -->
    <div class="news-column-main">

      <!-- Category Section: জাতীয় (National) -->
      <?php if (!empty($nationalPosts)): ?>
      <section class="category-block" aria-label="জাতীয় সংবাদ">
        <div class="section-header">
          <h3 class="section-title"><span class="title-accent" data-bn="জাতীয়" data-en="National">জাতীয়</span></h3>
          <a href="<?= $appUrl ?>/category/national" class="see-more-link" data-bn="আরও দেখুন »" data-en="See More »">আরও দেখুন »</a>
        </div>
        <div class="category-cards-grid">
          <?php foreach ($nationalPosts as $post): ?>
            <article class="news-card">
              <?php if (!empty($post['featured_image'])): ?>
                <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($post['category_slug']) ?>/<?= $post['id'] ?>/<?= htmlspecialchars($post['slug']) ?>">
                  <img src="<?= htmlspecialchars($post['featured_image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="card-thumb" loading="lazy">
                </a>
              <?php endif; ?>
              <h4 class="card-title">
                <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($post['category_slug']) ?>/<?= $post['id'] ?>/<?= htmlspecialchars($post['slug']) ?>">
                  <?= htmlspecialchars($post['title']) ?>
                </a>
              </h4>
              <span class="meta-time-sm">⏱️ <?= BanglaDate::timeAgo($post['published_at']) ?></span>
            </article>
          <?php endforeach; ?>
        </div>
      </section>
      <?php endif; ?>

      <!-- Saradesh Teaser Banner (Option 2 Bridge) -->
      <section class="saradesh-teaser-card" aria-label="সারাদেশের খবর">
        <div class="saradesh-teaser-inner">
          <div class="teaser-text">
            <h3 data-bn="📍 আপনার জেলার খবর খুঁজছেন?" data-en="📍 Looking for news from your district?">📍 আপনার জেলার খবর খুঁজছেন?</h3>
            <p data-bn="৮টি বিভাগ ও ৬৪টি জেলার প্রতিদিনের তৃণমূল সংবাদ এক ক্লিকে জানুন।" data-en="Read grassroots daily news from 8 divisions and 64 districts in one click.">৮টি বিভাগ ও ৬৪টি জেলার প্রতিদিনের তৃণমূল সংবাদ এক ক্লিকে জানুন।</p>
          </div>
          <div class="teaser-action">
            <a href="<?= $appUrl ?>/saradesh" class="btn btn-saradesh" data-bn="সারাদেশ পেজে যান »" data-en="Go to Saradesh »">সারাদেশ পেজে যান »</a>
          </div>
        </div>
      </section>

      <!-- Category Section: অর্থনীতি ও প্রযুক্তি (Economy & Tech Grid) -->
      <div class="two-col-category-grid">
        <!-- Economy Block -->
        <?php if (!empty($economyPosts)): ?>
        <section class="category-block" aria-label="অর্থনীতি সংবাদ">
          <div class="section-header">
            <h3 class="section-title"><span class="title-accent" data-bn="অর্থনীতি" data-en="Economy">অর্থনীতি</span></h3>
            <a href="<?= $appUrl ?>/category/economy" class="see-more-link" data-bn="আরও দেখুন »" data-en="See More »">আরও দেখুন »</a>
          </div>
          <div class="cat-list-vertical">
            <?php foreach ($economyPosts as $post): ?>
              <article class="cat-list-item">
                <h4 class="cat-item-title">
                  <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($post['category_slug']) ?>/<?= $post['id'] ?>/<?= htmlspecialchars($post['slug']) ?>">
                    <?= htmlspecialchars($post['title']) ?>
                  </a>
                </h4>
                <span class="meta-time-tiny">⏱️ <?= BanglaDate::timeAgo($post['published_at']) ?></span>
              </article>
            <?php endforeach; ?>
          </div>
        </section>
        <?php endif; ?>

        <!-- Tech Block -->
        <?php if (!empty($techPosts)): ?>
        <section class="category-block" aria-label="প্রযুক্তি সংবাদ">
          <div class="section-header">
            <h3 class="section-title"><span class="title-accent" data-bn="বিজ্ঞান ও প্রযুক্তি" data-en="Science & Tech">বিজ্ঞান ও প্রযুক্তি</span></h3>
            <a href="<?= $appUrl ?>/category/tech" class="see-more-link" data-bn="আরও দেখুন »" data-en="See More »">আরও দেখুন »</a>
          </div>
          <div class="cat-list-vertical">
            <?php foreach ($techPosts as $post): ?>
              <article class="cat-list-item">
                <h4 class="cat-item-title">
                  <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($post['category_slug']) ?>/<?= $post['id'] ?>/<?= htmlspecialchars($post['slug']) ?>">
                    <?= htmlspecialchars($post['title']) ?>
                  </a>
                </h4>
                <span class="meta-time-tiny">⏱️ <?= BanglaDate::timeAgo($post['published_at']) ?></span>
              </article>
            <?php endforeach; ?>
          </div>
        </section>
        <?php endif; ?>
      </div>

      <!-- Multimedia & Video Gallery Section with Working Video Player (Picture 5 Fix) -->
      <section class="video-gallery-section" aria-label="ভিডিও সংবাদ">
        <div class="section-header">
          <h3 class="section-title"><span class="title-accent" data-bn="ভিডিও ও মাল্টিমিডিয়া" data-en="Video & Multimedia">ভিডিও ও মাল্টিমিডিয়া</span></h3>
        </div>
        <div class="video-grid">
          <?php if (!empty($videoPosts)): ?>
            <?php foreach ($videoPosts as $vPost): ?>
              <div class="video-card">
                <div class="video-thumbnail-box video-play-trigger" 
                     data-video-url="<?= htmlspecialchars($vPost['video_url'] ?: 'https://www.youtube.com/watch?v=dQw4w9WgXcQ') ?>"
                     data-video-title="<?= htmlspecialchars($vPost['title']) ?>"
                     role="button" tabindex="0" title="ভিডিও চালু করুন">
                  <img src="<?= htmlspecialchars($vPost['featured_image'] ?: 'https://images.unsplash.com/photo-1585829365295-ab7cd400c167?auto=format&fit=crop&w=600&q=80') ?>" alt="<?= htmlspecialchars($vPost['title']) ?>" loading="lazy">
                  <span class="play-badge" aria-label="Play Video">▶</span>
                </div>
                <h4 class="video-title">
                  <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($vPost['category_slug'] ?? 'national') ?>/<?= $vPost['id'] ?>/<?= htmlspecialchars($vPost['slug']) ?>">
                    <?= htmlspecialchars($vPost['title']) ?>
                  </a>
                </h4>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </section>

    </div>

    <!-- Sidebar Column (30%) -->
    <aside class="sidebar-column" aria-label="সাইডবার">

      <!-- 1. Latest / Popular Tabbed Widget -->
      <div class="sidebar-widget tabbed-news-widget">
        <div class="tab-header">
          <button type="button" class="tab-btn active" data-tab="tab-latest" data-bn="সর্বশেষ" data-en="Latest">সর্বশেষ</button>
          <button type="button" class="tab-btn" data-tab="tab-popular" data-bn="জনপ্রিয়" data-en="Popular">জনপ্রিয়</button>
        </div>

        <!-- Latest News Tab Content -->
        <div class="tab-content active" id="tab-latest">
          <ol class="tab-news-list">
            <?php foreach ($latestPosts as $idx => $post): ?>
              <li class="tab-news-item">
                <span class="num-counter"><?= BanglaDate::bnNum($idx + 1) ?></span>
                <div class="item-details">
                  <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($post['category_slug'] ?? 'national') ?>/<?= $post['id'] ?>/<?= htmlspecialchars($post['slug'] ?? '') ?>" class="item-title">
                    <?= htmlspecialchars($post['title']) ?>
                  </a>
                  <span class="meta-time-tiny">⏱️ <?= BanglaDate::timeAgo($post['published_at']) ?></span>
                </div>
              </li>
            <?php endforeach; ?>
          </ol>
        </div>

        <!-- Popular News Tab Content -->
        <div class="tab-content" id="tab-popular">
          <ol class="tab-news-list">
            <?php foreach ($popularPosts as $idx => $post): ?>
              <li class="tab-news-item">
                <span class="num-counter popular-counter"><?= BanglaDate::bnNum($idx + 1) ?></span>
                <div class="item-details">
                  <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($post['category_slug'] ?? 'national') ?>/<?= $post['id'] ?>/<?= htmlspecialchars($post['slug'] ?? '') ?>" class="item-title">
                    <?= htmlspecialchars($post['title']) ?>
                  </a>
                  <span class="meta-time-tiny">👁️ <?= BanglaDate::bnNum($post['views'] ?? 100) ?> বার পঠিত</span>
                </div>
              </li>
            <?php endforeach; ?>
          </ol>
        </div>
      </div>

      <!-- 2. Sidebar Ad Slot (300x250) -->
      <div class="sidebar-widget ad-widget">
        <div class="ad-placeholder square-ad">
          <span class="ad-label" data-bn="বিজ্ঞাপন (৩০০ × ২৫০)" data-en="ADVERTISEMENT (300 × 250)">বিজ্ঞাপন (৩০০ × ২৫০)</span>
        </div>
      </div>

      <!-- 3. Online Reader Poll (অনলাইন জরিপ - Picture 4 Fix with Real DB) -->
      <?php if ($activePoll): ?>
      <div class="sidebar-widget poll-widget" id="poll-widget-box">
        <div class="widget-header">
          <h4 class="widget-title" data-bn="অনলাইন জরিপ" data-en="Online Poll">অনলাইন জরিপ</h4>
        </div>
        <div class="poll-card">
          <p class="poll-question"><?= htmlspecialchars($activePoll['question']) ?></p>
          <div id="poll-feedback" class="poll-feedback-msg" style="display:none;"></div>
          
          <form class="poll-form" id="poll-form" action="<?= $appUrl ?>/api/poll/vote" method="post">
            <input type="hidden" name="poll_id" value="<?= $activePoll['id'] ?>">
            <?php foreach ($activePoll['options'] as $opt): ?>
              <label class="poll-opt">
                <input type="radio" name="option_id" value="<?= $opt['id'] ?>" required>
                <span><?= htmlspecialchars($opt['option_text']) ?></span>
              </label>
            <?php endforeach; ?>
            <button type="submit" class="btn-vote" data-bn="ভোট দিন" data-en="Vote Now">ভোট দিন</button>
          </form>

          <div id="poll-results-live" class="poll-results-live" style="display:none;">
            <?php foreach ($activePoll['options'] as $opt): 
              $pct = ($activePoll['total_votes'] > 0) ? round(($opt['votes'] / $activePoll['total_votes']) * 100, 1) : 0;
            ?>
              <div class="poll-stat-row">
                <div class="poll-stat-label">
                  <span><?= htmlspecialchars($opt['option_text']) ?></span>
                  <strong><?= BanglaDate::bnNum($pct) ?>% (<?= BanglaDate::bnNum($opt['votes']) ?> ভোট)</strong>
                </div>
                <div class="poll-stat-bar">
                  <div class="poll-stat-fill" style="width: <?= $pct ?>%;"></div>
                </div>
              </div>
            <?php endforeach; ?>
            <div class="poll-total-note">
              <small>মোট ভোট: <?= BanglaDate::bnNum($activePoll['total_votes']) ?> টি</small>
            </div>
          </div>
        </div>
      </div>
      <?php endif; ?>

      <!-- 4. Sidebar Advertisement (Admin Controlled: Picture or Video with Hyperlink) -->
      <?php
      $sidebarAd = \App\Models\Ad::getByPosition('sidebar');
      ?>
      <div class="sidebar-widget sidebar-ad-widget">
        <div class="widget-header">
          <h4 class="widget-title" data-bn="বিজ্ঞাপন" data-en="Advertisement">বিজ্ঞাপন</h4>
          <span class="ad-tag-badge">বিজ্ঞাপন</span>
        </div>
        <div class="sidebar-ad-content">
          <?php if ($sidebarAd && (!empty($sidebarAd['image']) || !empty($sidebarAd['video_url']))): ?>
            <?php if ($sidebarAd['type'] === 'video' && !empty($sidebarAd['video_url'])): ?>
              <?php 
                preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/', $sidebarAd['video_url'], $sMatch);
                $ytSidebarId = $sMatch[1] ?? '';
              ?>
              <div class="sidebar-ad-media">
                <?php if ($ytSidebarId): ?>
                  <iframe src="https://www.youtube.com/embed/<?= $ytSidebarId ?>?autoplay=0" title="<?= htmlspecialchars($sidebarAd['title']) ?>" frameborder="0" allowfullscreen class="sidebar-ad-iframe"></iframe>
                <?php else: ?>
                  <video src="<?= htmlspecialchars($sidebarAd['video_url']) ?>" controls class="sidebar-ad-video"></video>
                <?php endif; ?>
                <?php if (!empty($sidebarAd['link'])): ?>
                  <a href="<?= htmlspecialchars($sidebarAd['link']) ?>" target="_blank" rel="noopener" class="ad-visit-btn" title="বিজ্ঞাপনের বিস্তারিত দেখুন">
                    🌐 বিস্তারিত দেখতে ভিজিট করুন →
                  </a>
                <?php endif; ?>
              </div>
            <?php elseif (!empty($sidebarAd['image'])): ?>
              <div class="sidebar-ad-media">
                <a href="<?= htmlspecialchars($sidebarAd['link'] ?: '#') ?>" target="_blank" rel="noopener" class="sidebar-ad-link" title="<?= htmlspecialchars($sidebarAd['title']) ?> (ক্লিক করে বিস্তারিত দেখুন)">
                  <img src="<?= htmlspecialchars($sidebarAd['image']) ?>" alt="<?= htmlspecialchars($sidebarAd['title']) ?>" class="sidebar-ad-img" width="300" height="250">
                </a>
                <?php if (!empty($sidebarAd['link'])): ?>
                  <a href="<?= htmlspecialchars($sidebarAd['link']) ?>" target="_blank" rel="noopener" class="ad-visit-btn">
                    🌐 বিস্তারিত দেখতে ভিজিট করুন →
                  </a>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          <?php else: ?>
            <div class="ad-placeholder sidebar-ad-placeholder">
              <a href="<?= $appUrl ?>/page/advertise" class="ad-placeholder-link">
                <span class="ad-label" data-bn="বিজ্ঞাপন দিন (৩০০ × ২৫০)" data-en="ADVERTISE HERE (300 × 250)">বিজ্ঞাপন দিন (৩০০ × ২৫০)</span>
              </a>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- 4. Newsletter Subscription Widget (Picture 4 Fix with Real DB) -->
      <div class="sidebar-widget newsletter-widget">
        <div class="widget-header">
          <h4 class="widget-title" data-bn="নিউজলেটার" data-en="Newsletter">নিউজলেটার</h4>
        </div>
        <p class="newsletter-desc" data-bn="প্রতিদিনের শীর্ষ সংবাদ সকালের ইমেইলে সরাসরি পেতে সাবস্ক্রাইব করুন।" data-en="Subscribe to receive top daily headlines directly in your inbox.">
          প্রতিদিনের শীর্ষ সংবাদ সকালের ইমেইলে সরাসরি পেতে সাবস্ক্রাইব করুন।
        </p>
        <div id="newsletter-feedback" class="newsletter-feedback-msg" style="display:none;"></div>
        <form action="<?= $appUrl ?>/api/newsletter/subscribe" method="post" class="newsletter-form" id="sidebar-newsletter">
          <input type="email" name="email" placeholder="আপনার ইমেইল ঠিকানা" required>
          <button type="submit" data-bn="যুক্ত হোন" data-en="Subscribe">যুক্ত হোন</button>
        </form>
      </div>

      <!-- 5. Namaz & Prayer Widget with Start and End Times -->
      <div class="sidebar-widget namaz-widget">
        <div class="widget-header">
          <h4 class="widget-title" data-bn="নামাজের সময়সূচি (ঢাকা)" data-en="Prayer Times (Dhaka)">নামাজের সময়সূচি (ঢাকা)</h4>
          <span class="wakto-note" data-bn="শুরু ও শেষ সময়" data-en="Start & End">শুরু ও শেষ সময়</span>
        </div>
        <ul class="namaz-list-detailed">
          <li class="namaz-item">
            <span class="w-name" data-bn="ফজর" data-en="Fajr">ফজর</span>
            <span class="w-time"><strong data-bn="শুরু ৪:৪৩" data-en="Start 4:43">শুরু ৪:৪৩</strong> — <small data-bn="শেষ ৫:৫৮" data-en="End 5:58">শেষ ৫:৫৮</small></span>
          </li>
          <li class="namaz-item">
            <span class="w-name" data-bn="যোহর" data-en="Dhuhr">যোহর</span>
            <span class="w-time"><strong data-bn="শুরু ১১:৪৬" data-en="Start 11:46">শুরু ১১:৪৬</strong> — <small data-bn="শেষ ৩:৫৫" data-en="End 3:55">শেষ ৩:৫৫</small></span>
          </li>
          <li class="namaz-item">
            <span class="w-name" data-bn="আসর" data-en="Asr">আসর</span>
            <span class="w-time"><strong data-bn="শুরু ৩:৫৬" data-en="Start 3:56">শুরু ৩:৫৬</strong> — <small data-bn="শেষ ৫:৩৪" data-en="End 5:34">শেষ ৫:৩৪</small></span>
          </li>
          <li class="namaz-item">
            <span class="w-name" data-bn="মাগরিব" data-en="Maghrib">মাগরিব</span>
            <span class="w-time"><strong data-bn="শুরু ৫:৩৬" data-en="Start 5:36">শুরু ৫:৩৬</strong> — <small data-bn="শেষ ৬:৪৮" data-en="End 6:48">শেষ ৬:৪৮</small></span>
          </li>
          <li class="namaz-item">
            <span class="w-name" data-bn="এশা" data-en="Isha">এশা</span>
            <span class="w-time"><strong data-bn="শুরু ৬:৪৯" data-en="Start 6:49">শুরু ৬:৪৯</strong> — <small data-bn="শেষ ৪:৪২" data-en="End 4:42">শেষ ৪:৪২</small></span>
          </li>
        </ul>
      </div>

    </aside>

  </div>
</div>

<!-- Video Lightbox Modal (Picture 5) -->
<div id="video-lightbox-modal" class="video-modal-overlay" style="display:none;" aria-hidden="true">
  <div class="video-modal-dialog">
    <div class="video-modal-header">
      <h3 id="video-modal-heading" class="video-modal-title">ভিডিও প্রতিবেদন</h3>
      <button type="button" id="video-modal-close" class="video-modal-close-btn" aria-label="Close Video">✕</button>
    </div>
    <div class="video-modal-body" id="video-modal-body">
      <!-- Player inserted dynamically via JS -->
    </div>
  </div>
</div>

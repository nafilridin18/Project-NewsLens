<?php
use App\Helpers\BanglaDate;
$appUrl = rtrim($config['app']['url'] ?? '', '/');
?>
<div class="search-page-container container">
  <!-- Breadcrumb -->
  <nav class="breadcrumb-nav" aria-label="ব্রেডক্রাম্ব">
    <ol class="breadcrumb-list">
      <li><a href="<?= $appUrl ?>/" data-bn="প্রচ্ছদ" data-en="Home">প্রচ্ছদ</a></li>
      <li><span class="sep">/</span></li>
      <li class="active" data-bn="সংবাদ অনুসন্ধান ও ফিল্টার" data-en="Search & Filter">সংবাদ অনুসন্ধান ও ফিল্টার</li>
    </ol>
  </nav>

  <!-- Filter Panel -->
  <section class="search-filter-panel card-box">
    <h1 class="search-title" data-bn="🔍 সংবাদ খুঁজুন ও ফিল্টার করুন" data-en="🔍 Search & Filter News">🔍 সংবাদ খুঁজুন ও ফিল্টার করুন</h1>
    
    <form action="<?= $appUrl ?>/search" method="get" class="search-filter-form" id="search-filter-form">
      <div class="search-form-grid">
        <!-- 1. Headline / Keyword -->
        <div class="form-group field-keyword">
          <label for="search-q" data-bn="শিরোনাম বা কী-ওয়ার্ড" data-en="Headline or Keyword">শিরোনাম বা কী-ওয়ার্ড</label>
          <input type="text" id="search-q" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="যেমন: অর্থনীতি, ক্রিকেট, বাজেট..." class="form-control">
        </div>

        <!-- 2. Category Filter -->
        <div class="form-group">
          <label for="filter-category" data-bn="ক্যাটাগরি" data-en="Category">ক্যাটাগরি</label>
          <select id="filter-category" name="category" class="form-control">
            <option value="" data-bn="সকল ক্যাটাগরি" data-en="All Categories">সকল ক্যাটাগরি</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>" <?= (!empty($filters['category_id']) && $filters['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name_bn']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- 3. Time Filter -->
        <div class="form-group">
          <label for="filter-time" data-bn="সময়কাল" data-en="Time Range">সময়কাল</label>
          <select id="filter-time" name="time" class="form-control">
            <option value="" data-bn="যে কোনো সময়" data-en="Anytime">যে কোনো সময়</option>
            <option value="today" <?= ($filters['time_range'] === 'today') ? 'selected' : '' ?> data-bn="আজ (Today)" data-en="Today">আজ (Today)</option>
            <option value="week" <?= ($filters['time_range'] === 'week') ? 'selected' : '' ?> data-bn="গত ৭ দিন (Past Week)" data-en="Past Week">গত ৭ দিন (Past Week)</option>
            <option value="month" <?= ($filters['time_range'] === 'month') ? 'selected' : '' ?> data-bn="গত ৩০ দিন (Past Month)" data-en="Past Month">গত ৩০ দিন (Past Month)</option>
          </select>
        </div>

        <!-- 4. Location: Division Filter -->
        <div class="form-group">
          <label for="filter-division" data-bn="বিভাগ (Location)" data-en="Division (Location)">বিভাগ (Location)</label>
          <select id="filter-division" name="division" class="form-control">
            <option value="" data-bn="সকল বিভাগ" data-en="All Divisions">সকল বিভাগ</option>
            <?php foreach ($divisions as $div): ?>
              <option value="<?= $div['id'] ?>" <?= (!empty($filters['division_id']) && $filters['division_id'] == $div['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($div['name_bn']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- 5. Location: District Filter -->
        <div class="form-group">
          <label for="filter-district" data-bn="জেলা (District)" data-en="District">জেলা (District)</label>
          <select id="filter-district" name="district" class="form-control">
            <option value="" data-bn="সকল জেলা" data-en="All Districts">সকল জেলা</option>
            <?php foreach ($districts as $dist): ?>
              <option value="<?= $dist['id'] ?>" <?= (!empty($filters['district_id']) && $filters['district_id'] == $dist['id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($dist['name_bn']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="search-form-actions">
        <button type="submit" class="btn btn-primary" data-bn="🔍 খুঁজুন" data-en="🔍 Search">🔍 খুঁজুন</button>
        <a href="<?= $appUrl ?>/search" class="btn btn-secondary" data-bn="ফিল্টার মুছুন" data-en="Reset Filter">ফিল্টার মুছুন</a>
      </div>
    </form>
  </section>

  <!-- Results Count -->
  <div class="search-results-meta">
    <h2 class="results-heading" data-bn="অনুসন্ধানের ফলাফল (<?= BanglaDate::bnNum(count($results)) ?> টি সংবাদ পাওয়া গেছে)" data-en="Search Results (<?= count($results) ?> news found)">
      অনুসন্ধানের ফলাফল (<?= BanglaDate::bnNum(count($results)) ?> টি সংবাদ পাওয়া গেছে)
    </h2>
  </div>

  <!-- Search Results Grid -->
  <div class="main-layout-grid">
    <main class="news-column-main">
      <?php if (!empty($results)): ?>
        <div class="cat-news-grid">
          <?php foreach ($results as $res): ?>
            <article class="cat-news-card search-news-item">
              <?php if (!empty($res['featured_image'])): ?>
                <div class="cat-card-thumb">
                  <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($res['category_slug']) ?>/<?= $res['id'] ?>/<?= htmlspecialchars($res['slug']) ?>">
                    <img src="<?= htmlspecialchars($res['featured_image']) ?>" alt="<?= htmlspecialchars($res['title']) ?>" loading="lazy" width="280" height="175">
                  </a>
                  <span class="category-badge-sm"><?= htmlspecialchars($res['category_name']) ?></span>
                </div>
              <?php endif; ?>
              <div class="cat-card-body">
                <h3 class="cat-card-title">
                  <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($res['category_slug']) ?>/<?= $res['id'] ?>/<?= htmlspecialchars($res['slug']) ?>">
                    <?= htmlspecialchars($res['title']) ?>
                  </a>
                </h3>
                <?php if (!empty($res['excerpt'])): ?>
                  <p class="cat-card-excerpt"><?= htmlspecialchars($res['excerpt']) ?></p>
                <?php endif; ?>
                <div class="meta-row-tiny">
                  <span class="meta-time">⏱️ <?= BanglaDate::timeAgo($res['published_at']) ?></span>
                  <?php if (!empty($res['district_name'])): ?>
                    <span class="meta-dist">📍 <?= htmlspecialchars($res['district_name']) ?></span>
                  <?php endif; ?>
                  <span class="meta-views">👁️ <?= BanglaDate::bnNum($res['views'] ?? 0) ?> বার পঠিত</span>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div class="empty-state-box">
          <p data-bn="আপনার অনুসন্ধানের সাথে মিলে এমন কোনো সংবাদ পাওয়া যায়নি। অনুগ্রহ করে অন্য কী-ওয়ার্ড দিয়ে চেষ্টা করুন।" data-en="No news matched your search. Please try other keywords or clear filters.">
            আপনার অনুসন্ধানের সাথে মিলে এমন কোনো সংবাদ পাওয়া যায়নি। অনুগ্রহ করে অন্য কী-ওয়ার্ড দিয়ে চেষ্টা করুন।
          </p>
        </div>
      <?php endif; ?>
    </main>

    <!-- Sidebar Column -->
    <aside class="sidebar-column" aria-label="সাইডবার">
      <div class="sidebar-widget popular-widget">
        <div class="widget-header">
          <h3 class="widget-title" data-bn="সর্বাধিক পঠিত" data-en="Most Read">সর্বাধিক পঠিত</h3>
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
    </aside>
  </div>
</div>

<script>
// Cascading Division -> District Filter AJAX
document.addEventListener('DOMContentLoaded', function() {
  const divSelect = document.getElementById('filter-division');
  const distSelect = document.getElementById('filter-district');
  if (divSelect && distSelect) {
    divSelect.addEventListener('change', function() {
      const divId = this.value;
      const isEn = document.documentElement.getAttribute('lang') === 'en';
      distSelect.innerHTML = `<option value="" data-bn="সকল জেলা" data-en="All Districts">${isEn ? 'All Districts' : 'সকল জেলা'}</option>`;
      if (!divId) return;
      fetch('<?= $appUrl ?>/api/districts?division_id=' + divId)
        .then(r => r.json())
        .then(data => {
          data.forEach(d => {
            const opt = document.createElement('option');
            opt.value = d.id;
            opt.textContent = isEn ? (d.name_en || d.name_bn) : d.name_bn;
            opt.setAttribute('data-bn', d.name_bn);
            opt.setAttribute('data-en', d.name_en || d.name_bn);
            distSelect.appendChild(opt);
          });
        });
    });
  }
});
</script>

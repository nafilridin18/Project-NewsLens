<?php
use App\Helpers\BanglaDate;
$appUrl = rtrim($config['app']['url'] ?? '', '/');
?>
<div class="saradesh-container container">

  <!-- Breadcrumbs & Heading -->
  <div class="page-header-box">
    <div class="breadcrumb-nav">
      <a href="<?= $appUrl ?>/" data-bn="প্রচ্ছদ" data-en="Home">প্রচ্ছদ</a> <span class="sep">/</span> <span class="current" data-bn="সারাদেশ" data-en="All Bangladesh">সারাদেশ</span>
    </div>
    <h1 class="page-heading" data-bn="📍 সারাদেশের খবর (৮ বিভাগ ও ৬৪ জেলা)" data-en="📍 Countrywide News (8 Divisions & 64 Districts)">📍 সারাদেশের খবর (৮ বিভাগ ও ৬৪ জেলা)</h1>
    <p class="page-subtitle" data-bn="আপনার এলাকার তাজা ও বস্তুনিষ্ঠ সংবাদ এক ক্লিকে জানুন" data-en="Get grassroots & objective news from your locality in one click">আপনার এলাকার তাজা ও বস্তুনিষ্ঠ সংবাদ এক ক্লিকে জানুন</p>
  </div>

  <!-- Interactive Filter Box -->
  <section class="filter-card" aria-label="বিভাগ ও জেলা ফিল্টার">
    <form action="<?= $appUrl ?>/saradesh" method="get" class="filter-form" id="saradesh-filter-form">
      <div class="form-group">
        <label for="division-select" data-bn="বিভাগ নির্বাচন করুন:" data-en="Select Division:">বিভাগ নির্বাচন করুন:</label>
        <select name="division" id="division-select">
          <option value="" data-bn="-- সব বিভাগ --" data-en="-- All Divisions --">-- সব বিভাগ --</option>
          <?php foreach ($divisions as $div): ?>
            <option value="<?= $div['id'] ?>" <?= ($selectedDiv == $div['id']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($div['name_bn']) ?> (<?= htmlspecialchars($div['name_en'] ?? '') ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="district-select" data-bn="জেলা নির্বাচন করুন:" data-en="Select District:">জেলা নির্বাচন করুন:</label>
        <select name="district" id="district-select">
          <option value="" data-bn="-- সব জেলা --" data-en="-- All Districts --">-- সব জেলা --</option>
          <?php foreach ($districts as $dst): ?>
            <option value="<?= $dst['id'] ?>" <?= ($selectedDist == $dst['id']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($dst['name_bn']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-action">
        <button type="submit" class="btn-filter" data-bn="খবর দেখুন ➔" data-en="View News ➔">খবর দেখুন ➔</button>
      </div>
    </form>
  </section>

  <!-- Quick Division Selector Pills -->
  <div class="division-pills-row" aria-label="বিভাগসমূহ">
    <span class="pills-label" data-bn="দ্রুত দেখুন:" data-en="Quick View:">দ্রুত দেখুন:</span>
    <a href="<?= $appUrl ?>/saradesh" class="div-pill <?= empty($selectedDiv) ? 'active' : '' ?>" data-bn="সব" data-en="All">সব</a>
    <?php foreach ($divisions as $div): ?>
      <a href="<?= $appUrl ?>/saradesh?division=<?= $div['id'] ?>" class="div-pill <?= ($selectedDiv == $div['id']) ? 'active' : '' ?>">
        <?= htmlspecialchars($div['name_bn']) ?>
      </a>
    <?php endforeach; ?>
  </div>

  <!-- News Grid -->
  <section class="district-news-section" aria-label="সংবাদ তালিকা">
    <?php if (empty($newsList)): ?>
      <div class="no-news-box">
        <p>দুঃখিত, এই জেলায় এই মুহূর্তে কোনো সংবাদ পাওয়া যায়নি। অন্য বিভাগ বা জেলা নির্বাচন করুন।</p>
      </div>
    <?php else: ?>
      <div class="district-news-grid">
        <?php foreach ($newsList as $item): ?>
          <article class="district-news-card">
            <div class="news-thumb-box">
              <a href="<?= $appUrl ?>/news/saradesh/<?= $item['id'] ?>/<?= htmlspecialchars($item['slug'] ?? '') ?>">
                <img src="<?= htmlspecialchars($item['featured_image'] ?? 'https://images.unsplash.com/photo-1541872703-74c5e44368f9?auto=format&fit=crop&w=600&q=80') ?>" alt="<?= htmlspecialchars($item['title']) ?>" loading="lazy" width="400" height="240">
              </a>
              <?php if (!empty($item['district_name'])): ?>
                <span class="district-badge"><?= htmlspecialchars($item['district_name']) ?></span>
              <?php endif; ?>
            </div>
            <div class="news-body-box">
              <h2 class="news-title">
                <a href="<?= $appUrl ?>/news/saradesh/<?= $item['id'] ?>/<?= htmlspecialchars($item['slug'] ?? '') ?>">
                  <?= htmlspecialchars($item['title']) ?>
                </a>
              </h2>
              <?php if (!empty($item['excerpt'])): ?>
                <p class="news-excerpt"><?= htmlspecialchars($item['excerpt']) ?></p>
              <?php endif; ?>
              <div class="news-meta">
                <span class="meta-dist">📍 <?= htmlspecialchars($item['district_name'] ?? 'সারাদেশ') ?></span>
                <span class="meta-time">⏱️ <?= BanglaDate::timeAgo($item['published_at']) ?></span>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>

</div>

<script>
// Dynamic District Population on Division Change
document.addEventListener('DOMContentLoaded', () => {
  const divSelect = document.getElementById('division-select');
  const distSelect = document.getElementById('district-select');

  divSelect?.addEventListener('change', async () => {
    const divId = divSelect.value;
    distSelect.innerHTML = '<option value="">-- সব জেলা --</option>';

    if (!divId) return;

    try {
      const res = await fetch('<?= $appUrl ?>/api/districts?division_id=' + divId);
      if (res.ok) {
        const districts = await res.json();
        districts.forEach(d => {
          const opt = document.createElement('option');
          opt.value = d.id;
          opt.textContent = d.name_bn;
          distSelect.appendChild(opt);
        });
      }
    } catch (e) {
      console.error('Error fetching districts:', e);
    }
  });
});
</script>

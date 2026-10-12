<?php
use App\Helpers\BanglaDate;
$appUrl = rtrim($config['app']['url'] ?? '', '/');
$bookmarks = $bookmarks ?? [];
$user = $user ?? \App\Core\Auth::user();
?>
<div class="category-page-container container" style="margin-top: 30px; margin-bottom: 70px;">
  <!-- Breadcrumb -->
  <nav class="breadcrumb-nav" aria-label="ব্রেডক্রাম্ব">
    <ol class="breadcrumb-list">
      <li><a href="<?= $appUrl ?>/" data-bn="প্রচ্ছদ" data-en="Home">প্রচ্ছদ</a></li>
      <li><span class="sep">/</span></li>
      <li class="active">সংরক্ষিত সংবাদসমূহ (Bookmarks)</li>
    </ol>
  </nav>

  <header style="margin-bottom: 28px; border-bottom: 2px solid #0E7A3A; padding-bottom: 12px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
    <div>
      <h1 style="font-size: 1.6rem; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
        <span>🔖</span>
        <span>আমার সংরক্ষিত সংবাদ</span>
      </h1>
      <p style="color: #64748b; font-size: 0.9rem; margin: 4px 0 0;">পরবর্তীতে পড়ার জন্য আপনার বুকমার্ক করা সংবাদসমূহ।</p>
    </div>
    <span style="background: #e2e8f0; color: #334155; font-size: 0.85rem; font-weight: 700; padding: 4px 12px; border-radius: 20px;">
      মোট সংরক্ষিত: <?= BanglaDate::bnNum(count($bookmarks)) ?> টি
    </span>
  </header>

  <?php if (empty($bookmarks)): ?>
    <div style="text-align: center; padding: 70px 20px; background: #fff; border: 1px solid #e2e8f0; border-radius: 14px;">
      <span style="font-size: 3rem; display: block; margin-bottom: 12px;">📑</span>
      <h2 style="font-size: 1.3rem; font-weight: 700; color: #334155; margin-bottom: 8px;">কোনো সংবাদ সংরক্ষিত নেই</h2>
      <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 20px;">যেকোনো সংবাদ পড়ার সময় বুকমার্ক বাটনে ক্লিক করে পরে পড়ার জন্য সংরক্ষণ করতে পারেন।</p>
      <a href="<?= $appUrl ?>/" style="background: #0E7A3A; color: #fff; padding: 10px 22px; border-radius: 8px; font-weight: 700; text-decoration: none;">আজকের সংবাদ পড়ুন ➔</a>
    </div>
  <?php else: ?>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px;">
      <?php foreach ($bookmarks as $bm): ?>
        <article style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; transition: transform 0.2s, box-shadow 0.2s;" class="bookmark-card">
          <?php if (!empty($bm['featured_image'])): ?>
            <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($bm['category_slug'] ?? 'national') ?>/<?= (int)$bm['post_id'] ?>/<?= htmlspecialchars($bm['slug']) ?>" style="display: block; position: relative;">
              <img src="<?= htmlspecialchars(imageUrl($bm['featured_image'], $appUrl)) ?>" alt="<?= htmlspecialchars($bm['title']) ?>" style="width: 100%; height: 180px; object-fit: cover;" loading="lazy">
            </a>
          <?php endif; ?>

          <div style="padding: 16px; flex: 1; display: flex; flex-direction: column;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
              <span style="background: #e0f2fe; color: #0369a1; font-size: 0.75rem; font-weight: 700; padding: 2px 8px; border-radius: 4px;">
                <?= htmlspecialchars($bm['category_name']) ?>
              </span>
              <span style="font-size: 0.78rem; color: #94a3b8;">
                সংরক্ষণ: <?= BanglaDate::formatBnDate($bm['bookmarked_at']) ?>
              </span>
            </div>

            <h2 style="font-size: 1.05rem; font-weight: 700; line-height: 1.4; margin-bottom: 12px; flex: 1;">
              <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($bm['category_slug'] ?? 'national') ?>/<?= (int)$bm['post_id'] ?>/<?= htmlspecialchars($bm['slug']) ?>" style="text-decoration: none; color: #0f172a;">
                <?= htmlspecialchars($bm['title']) ?>
              </a>
            </h2>

            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f1f5f9; padding-top: 10px; margin-top: auto;">
              <span style="font-size: 0.8rem; color: #64748b;">
                👁️ <?= BanglaDate::bnNum($bm['views'] ?? 0) ?> বার পঠিত
              </span>

              <button type="button" onclick="removeBookmark(<?= (int)$bm['post_id'] ?>, this)" style="background: #fee2e2; border: 1px solid #fca5a5; color: #dc2626; padding: 4px 10px; border-radius: 6px; font-size: 0.78rem; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;">
                🗑️ সরান
              </button>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<script>
  function removeBookmark(postId, btn) {
    if (!confirm('আপনি কি এই সংবাদটি বুকমার্ক থেকে সরিয়ে ফেলতে চান?')) return;
    btn.disabled = true;
    btn.innerText = 'অপেক্ষা করুন...';

    const formData = new FormData();
    formData.append('post_id', postId);

    fetch('<?= $appUrl ?>/api/bookmark/toggle', {
      method: 'POST',
      body: formData
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        const card = btn.closest('.bookmark-card');
        if (card) {
          card.style.transition = 'all 0.3s ease';
          card.style.opacity = '0';
          card.style.transform = 'scale(0.9)';
          setTimeout(() => card.remove(), 300);
        }
      } else {
        alert(data.message || 'ত্রুটি ঘটেছে');
        btn.disabled = false;
        btn.innerText = '🗑️ সরান';
      }
    })
    .catch(err => {
      btn.disabled = false;
      btn.innerText = '🗑️ সরান';
      alert('সার্ভার যোগাযোগে ত্রুটি');
    });
  }
</script>

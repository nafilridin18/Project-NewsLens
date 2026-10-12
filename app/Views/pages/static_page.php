<?php
/**
 * Static Page Template (About, Contact, Advertise, Privacy, Terms, Corrections)
 */
$adminPath = $adminPath ?? 'lens-desk';
$appUrl = rtrim($appUrl ?? '', '/');
$title = $page['title_bn'] ?? $page['title'] ?? '';
$badge = $page['badge'] ?? 'প্রতিষ্ঠান';
$updatedTime = !empty($page['updated_at']) ? date('d F Y', strtotime($page['updated_at'])) : date('F Y');
?>
<main class="static-page-main" style="padding: 40px 0 60px; min-height: 65vh;">
  <div class="container">
    <div style="max-width: 880px; margin: 0 auto; background: var(--bg-card, #0b1f44); border: 1px solid var(--border-color, rgba(255,255,255,0.1)); border-radius: 16px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); position: relative;">
      
      <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 16px;">
        <div style="display: flex; align-items: center; gap: 10px;">
          <span style="background: var(--color-primary, #0E7A3A); color: #fff; font-size: 0.82rem; font-weight: 700; padding: 4px 12px; border-radius: 6px; letter-spacing: 0.3px;">
            <?= htmlspecialchars($badge) ?>
          </span>
          <span style="color: var(--text-muted, #94a3b8); font-size: 0.88rem;">
            নিউজলেন্সবিডি প্রাতিষ্ঠানিক পাতা
          </span>
          <?php if (($page['status'] ?? 'published') === 'draft'): ?>
            <span style="background: #f59e0b; color: #000; font-size: 0.75rem; font-weight: 800; padding: 2px 8px; border-radius: 4px;">
              ⚠️ খসড়া (Draft Preview)
            </span>
          <?php endif; ?>
        </div>

        <?php if (!empty($isAdmin) && !empty($page['id'])): ?>
          <a href="<?= $appUrl ?>/<?= $adminPath ?>/pages/edit?id=<?= (int)$page['id'] ?>" style="background: rgba(56, 189, 248, 0.15); border: 1px solid #38bdf8; color: #38bdf8; padding: 6px 14px; border-radius: 6px; font-size: 0.85rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s ease;">
            ✏️ পেজ সম্পাদনা করুন
          </a>
        <?php endif; ?>
      </div>

      <h1 style="font-size: 2rem; font-weight: 800; color: var(--text-main, #f8fafc); margin-bottom: 24px; border-bottom: 2px solid rgba(255,255,255,0.08); padding-bottom: 16px; line-height: 1.3;">
        <?= htmlspecialchars($title) ?>
      </h1>

      <div class="static-content" style="font-size: 1.05rem; line-height: 1.85; color: var(--text-main, #cbd5e1);">
        <?= $page['content'] ?>
      </div>

      <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center; font-size: 0.88rem; color: var(--text-muted, #94a3b8); flex-wrap: wrap; gap: 12px;">
        <span>সর্বশেষ হালনাগাদ: <?= htmlspecialchars($updatedTime) ?></span>
        <a href="<?= $appUrl ?>/" style="color: #38bdf8; text-decoration: none; font-weight: 700; transition: color 0.2s ease;">← প্রচ্ছদে ফিরে যান</a>
      </div>

    </div>
  </div>
</main>

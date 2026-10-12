<?php
/**
 * Static Page Template (About, Contact, Advertise, Privacy, Terms, Corrections)
 */
?>
<main class="static-page-main" style="padding: 40px 0; min-height: 60vh;">
  <div class="container">
    <div style="max-width: 860px; margin: 0 auto; background: var(--bg-card, #0b1f44); border: 1px solid var(--border-color, rgba(255,255,255,0.1)); border-radius: 16px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
      
      <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
        <span style="background: var(--color-primary, #0E7A3A); color: #fff; font-size: 0.8rem; font-weight: 700; padding: 3px 10px; border-radius: 4px;">
          <?= htmlspecialchars($page['badge'] ?? 'তথ্য') ?>
        </span>
        <span style="color: var(--text-muted, #94a3b8); font-size: 0.86rem;">
          নিউজলেন্সবিডি প্রাতিষ্ঠানিক পাতা
        </span>
      </div>

      <h1 style="font-size: 1.85rem; font-weight: 800; color: var(--text-main, #f8fafc); margin-bottom: 24px; border-bottom: 2px solid rgba(255,255,255,0.08); padding-bottom: 16px;">
        <?= htmlspecialchars($page['title_bn']) ?>
      </h1>

      <div class="static-content" style="font-size: 1.05rem; line-height: 1.8; color: var(--text-main, #cbd5e1);">
        <?= $page['content'] ?>
      </div>

      <div style="margin-top: 36px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center; font-size: 0.88rem; color: var(--text-muted, #94a3b8);">
        <span>সর্বশেষ হালনাগাদ: <?= date('F Y') ?></span>
        <a href="<?= $appUrl ?>/" style="color: #38bdf8; text-decoration: none; font-weight: 700;">← প্রচ্ছদে ফিরে যান</a>
      </div>

    </div>
  </div>
</main>

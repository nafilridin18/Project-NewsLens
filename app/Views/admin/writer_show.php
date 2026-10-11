<?php
$adminPath = $adminPath ?? 'lens-desk';
$appUrl = rtrim($appUrl ?? '', '/');
$writer = $writer ?? [];
$posts = $posts ?? [];
$activeTab = 'writers';
?>
<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle ?? 'লেখক বিস্তারিত') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Hind Siliguri', sans-serif; background: var(--adm-bg); color: var(--adm-text); }
    .writer-show-wrap { max-width: 1200px; margin: 24px auto 60px; padding: 0 20px; }

    .btn-back {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      color: var(--adm-text-muted);
      text-decoration: none;
      font-weight: 700;
      margin-bottom: 20px;
      font-size: 0.9rem;
    }
    .btn-back:hover { color: var(--adm-text); }

    /* Profile Header Card */
    .profile-card {
      background: var(--adm-card-bg);
      border: 1px solid var(--adm-border);
      border-radius: 16px;
      padding: 30px;
      margin-bottom: 26px;
      display: flex;
      gap: 24px;
      align-items: flex-start;
      flex-wrap: wrap;
    }
    .profile-avatar {
      width: 90px;
      height: 90px;
      border-radius: 50%;
      background: #071630;
      border: 2px solid var(--adm-border);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 38px;
      object-fit: cover;
    }
    .profile-meta h1 { font-size: 1.6rem; font-weight: 800; margin-bottom: 4px; }
    .profile-meta p { color: var(--adm-text-muted); font-size: 0.95rem; margin-bottom: 12px; }
    .badge-pill {
      display: inline-block;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 700;
    }
    .badge-direct { background: rgba(56, 189, 248, 0.2); color: #38bdf8; border: 1px solid #38bdf8; }
    .badge-review { background: rgba(168, 85, 247, 0.2); color: #c084fc; border: 1px solid #a855f7; }

    /* Stats Grid */
    .stats-row {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 16px;
      margin-bottom: 28px;
    }
    .stat-box {
      background: var(--adm-card-bg);
      border: 1px solid var(--adm-border);
      border-radius: 12px;
      padding: 18px 20px;
      text-align: center;
    }
    .stat-val { font-size: 1.8rem; font-weight: 800; color: #fff; margin-bottom: 4px; }
    .stat-lbl { font-size: 0.85rem; color: var(--adm-text-muted); }

    /* Articles Table */
    .articles-card {
      background: var(--adm-card-bg);
      border: 1px solid var(--adm-border);
      border-radius: 12px;
      overflow: hidden;
    }
    .articles-head { padding: 18px 20px; border-bottom: 1px solid var(--adm-border); font-size: 1.15rem; font-weight: 800; }
    table.art-tbl { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem; }
    table.art-tbl th { padding: 12px 18px; color: var(--adm-text-muted); font-weight: 700; background: rgba(0,0,0,0.2); }
    table.art-tbl td { padding: 12px 18px; border-bottom: 1px solid rgba(255,255,255,0.05); }
    .status-pub { color: #4ade80; font-weight: 700; }
    .status-rev { color: #facc15; font-weight: 700; }
    .status-drf { color: #94a3b8; font-weight: 700; }
  </style>
</head>
<body>

  <?php require __DIR__ . '/partials/header.php'; ?>

  <div class="writer-show-wrap">
    
    <a href="/<?= $adminPath ?>/writers" class="btn-back">⬅️ লেখক তালিকায় ফিরে যান</a>

    <div class="profile-card">
      <?php if (!empty($writer['avatar'])): ?>
        <img src="<?= htmlspecialchars($writer['avatar']) ?>" alt="" class="profile-avatar">
      <?php else: ?>
        <div class="profile-avatar">✍️</div>
      <?php endif; ?>

      <div class="profile-meta">
        <h1><?= htmlspecialchars($writer['name']) ?></h1>
        <p>📧 <?= htmlspecialchars($writer['email']) ?> <?= !empty($writer['phone']) ? '• 📱 ' . htmlspecialchars($writer['phone']) : '' ?></p>
        
        <div>
          <span class="badge-pill badge-<?= ($writer['permission_level'] === 'direct_publish') ? 'direct' : 'review' ?>">
            <?= ($writer['permission_level'] === 'direct_publish') ? '⚡ সরাসরি প্রকাশ (Direct Publish)' : '👁️ সম্পাদকীয় পর্যালোচনা (Editor Review)' ?>
          </span>
          <span style="font-size: 0.85rem; color: var(--adm-text-muted); margin-left: 10px;">
            যুক্ত হয়েছেন: <?= date('d M, Y', strtotime($writer['created_at'])) ?>
          </span>
        </div>

        <?php if (!empty($writer['bio'])): ?>
          <div style="margin-top: 14px; background: rgba(0,0,0,0.2); padding: 12px 16px; border-radius: 8px; font-size: 0.9rem;">
            <strong>বায়ো:</strong> <?= nl2br(htmlspecialchars($writer['bio'])) ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($writer['writer_reason'])): ?>
          <div style="margin-top: 10px; background: rgba(0,0,0,0.2); padding: 12px 16px; border-radius: 8px; font-size: 0.9rem; color: #38bdf8;">
            <strong>কেন লিখতে আগ্রহী:</strong> <?= nl2br(htmlspecialchars($writer['writer_reason'])) ?>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <!-- Stats -->
    <div class="stats-row">
      <div class="stat-box">
        <div class="stat-val"><?= count($posts) ?></div>
        <div class="stat-lbl">মোট লিখিত সংবাদ</div>
      </div>
      <div class="stat-box">
        <div class="stat-val" style="color: #4ade80;"><?= $publishedCount ?></div>
        <div class="stat-lbl">প্রকাশিত সংবাদ</div>
      </div>
      <div class="stat-box">
        <div class="stat-val" style="color: #facc15;"><?= $pendingCount ?></div>
        <div class="stat-lbl">রিভিউ / পেন্ডিং</div>
      </div>
      <div class="stat-box">
        <div class="stat-val" style="color: #38bdf8;"><?= number_format($totalViews) ?></div>
        <div class="stat-lbl">সর্বমোট পাঠক ভিউ</div>
      </div>
    </div>

    <!-- Articles Table -->
    <div class="articles-card">
      <div class="articles-head">📰 এই লেখকের প্রকাশিত ও খসড়া সংবাদসমূহ</div>
      <div style="overflow-x: auto;">
        <table class="art-tbl">
          <thead>
            <tr>
              <th>শিরোনাম</th>
              <th>ক্যাটাগরি</th>
              <th>স্ট্যাটাস</th>
              <th>ভিউ সংখ্যা</th>
              <th>তারিখ</th>
              <th>ভিউ</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($posts)): ?>
              <tr>
                <td colspan="6" style="text-align: center; padding: 30px; color: var(--adm-text-muted);">লেখক এখনো কোনো সংবাদ তৈরি করেননি।</td>
              </tr>
            <?php else: ?>
              <?php foreach ($posts as $p): ?>
                <tr>
                  <td><strong><?= htmlspecialchars($p['title']) ?></strong></td>
                  <td><?= htmlspecialchars($p['category_name']) ?></td>
                  <td>
                    <?php if ($p['status'] === 'published'): ?>
                      <span class="status-pub">প্রকাশিত</span>
                    <?php elseif ($p['status'] === 'pending_review' || $p['status'] === 'pending'): ?>
                      <span class="status-rev">পর্যালোচনায়</span>
                    <?php else: ?>
                      <span class="status-drf"><?= htmlspecialchars($p['status']) ?></span>
                    <?php endif; ?>
                  </td>
                  <td><strong><?= number_format((int) $p['views']) ?></strong></td>
                  <td><?= date('d M, Y', strtotime($p['created_at'])) ?></td>
                  <td>
                    <a href="/news/<?= $p['category_id'] ?>/<?= $p['id'] ?>" target="_blank" style="color: #38bdf8; text-decoration: none; font-weight: 700;">দেখুন ↗</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>

</body>
</html>

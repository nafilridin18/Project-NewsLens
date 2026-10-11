<?php
$adminPath = $adminPath ?? 'lens-desk';
$appUrl = rtrim($appUrl ?? '', '/');
$stats = $stats ?? [];
$recentPosts = $recentPosts ?? [];
$user = $user ?? \App\Core\Auth::user();
$activeTab = 'writer_dashboard';
?>
<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle ?? 'লেখক ড্যাশবোর্ড') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Hind Siliguri', sans-serif; background: var(--adm-bg); color: var(--adm-text); }
    .writer-wrap { max-width: 1360px; margin: 24px auto 60px; padding: 0 20px; }

    /* Welcome Banner */
    .welcome-card {
      background: linear-gradient(135deg, rgba(14, 122, 58, 0.25) 0%, rgba(12, 30, 61, 0.9) 100%);
      border: 1px solid rgba(16, 185, 129, 0.3);
      border-radius: 16px;
      padding: 24px 28px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 20px;
      margin-bottom: 28px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.25);
    }
    .welcome-left h1 { font-size: 1.8rem; font-weight: 800; color: #fff; margin-bottom: 6px; }
    .welcome-left p { color: var(--adm-text-muted); font-size: 0.95rem; }
    .welcome-actions { display: flex; gap: 12px; flex-wrap: wrap; }
    .btn-create {
      background: #0E7A3A;
      color: #fff;
      padding: 10px 20px;
      border-radius: 8px;
      font-weight: 700;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: background 0.2s;
    }
    .btn-create:hover { background: #0a5c2b; }
    .btn-outline {
      background: rgba(255,255,255,0.06);
      border: 1px solid var(--adm-border);
      color: var(--adm-text);
      padding: 10px 18px;
      border-radius: 8px;
      font-weight: 600;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.2s;
    }
    .btn-outline:hover { border-color: var(--adm-accent); color: var(--adm-accent); }

    /* Permission Badge Pill */
    .perm-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 700;
      margin-top: 8px;
    }
    .perm-direct { background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid #10b981; }
    .perm-review { background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid #f59e0b; }

    /* Stats Grid */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 16px;
      margin-bottom: 32px;
    }
    .stat-card {
      background: var(--adm-card-bg);
      border: 1px solid var(--adm-border);
      border-radius: 12px;
      padding: 20px;
      display: flex;
      align-items: center;
      gap: 16px;
      box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    }
    .stat-icon {
      width: 48px;
      height: 48px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.4rem;
      flex-shrink: 0;
    }
    .icon-total { background: rgba(56, 189, 248, 0.15); color: #38bdf8; }
    .icon-pub { background: rgba(16, 185, 129, 0.15); color: #34d399; }
    .icon-rev { background: rgba(245, 158, 11, 0.15); color: #fbbf24; }
    .icon-rej { background: rgba(239, 68, 68, 0.15); color: #f87171; }
    .icon-view { background: rgba(168, 85, 247, 0.15); color: #c084fc; }

    .stat-val { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.8rem; font-weight: 800; color: #fff; }
    .stat-lbl { font-size: 0.88rem; color: var(--adm-text-muted); font-weight: 600; }

    /* Table Container */
    .section-card {
      background: var(--adm-card-bg);
      border: 1px solid var(--adm-border);
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }
    .section-head {
      padding: 18px 24px;
      border-bottom: 1px solid var(--adm-border);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .section-head h2 { font-size: 1.2rem; font-weight: 800; }
    .table-wrap { width: 100%; overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.92rem; }
    th {
      background: rgba(0,0,0,0.25);
      padding: 14px 18px;
      color: var(--adm-text-muted);
      font-weight: 700;
      border-bottom: 1px solid var(--adm-border);
      white-space: nowrap;
    }
    td {
      padding: 14px 18px;
      border-bottom: 1px solid rgba(255,255,255,0.05);
      vertical-align: middle;
    }
    tr:hover { background: rgba(255,255,255,0.02); }

    /* Status Pills */
    .badge {
      display: inline-block;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 0.78rem;
      font-weight: 700;
    }
    .badge-pub { background: rgba(16, 185, 129, 0.2); color: #34d399; }
    .badge-rev { background: rgba(245, 158, 11, 0.2); color: #fbbf24; }
    .badge-rej { background: rgba(239, 68, 68, 0.2); color: #f87171; }
    .badge-dft { background: rgba(148, 163, 184, 0.2); color: #cbd5e1; }

    .btn-action {
      padding: 5px 12px;
      border-radius: 6px;
      font-size: 0.82rem;
      font-weight: 600;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 4px;
      border: 1px solid var(--adm-border);
      color: var(--adm-text);
      transition: all 0.15s;
    }
    .btn-action:hover { border-color: var(--adm-accent); color: var(--adm-accent); }
  </style>
</head>
<body>
  <?php 
  $activeTab = 'writer_dashboard';
  require dirname(__DIR__) . '/admin/partials/header.php'; 
  ?>

  <div class="writer-wrap">
    <!-- Welcome Card -->
    <div class="welcome-card">
      <div class="welcome-left">
        <h1>স্বাগতম, <?= htmlspecialchars($user['name'] ?? 'সম্মানিত লেখক') ?>!</h1>
        <p>আপনার সংবাদ ও বিশ্লেষণ প্রকাশের জন্য লেখক প্যানেলে আপনাকে স্বাগতম।</p>
        <?php if (($user['permission_level'] ?? '') === 'direct_publish'): ?>
          <span class="perm-badge perm-direct">⚡ সরাসরি প্রকাশ অনুমোদিত (Direct Publish)</span>
        <?php else: ?>
          <span class="perm-badge perm-review">📝 সম্পাদকীয় পর্যালোচনা সাপেক্ষে (Editor Review)</span>
        <?php endif; ?>
      </div>
      <div class="welcome-actions">
        <a href="<?= $appUrl ?>/writer/posts/create" class="btn-create">✍️ নতুন সংবাদ লিখুন</a>
        <a href="<?= $appUrl ?>/writer/posts" class="btn-outline">📋 আমার সংবাদসমূহ</a>
        <a href="<?= $appUrl ?>/profile" class="btn-outline">👤 প্রোফাইল ও পরিসংখ্যান</a>
      </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon icon-total">📝</div>
        <div>
          <div class="stat-val"><?= (int)($stats['total_posts'] ?? 0) ?></div>
          <div class="stat-lbl">মোট সংবাদ</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon icon-pub">✅</div>
        <div>
          <div class="stat-val"><?= (int)($stats['published_count'] ?? 0) ?></div>
          <div class="stat-lbl">প্রকাশিত</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon icon-rev">⏳</div>
        <div>
          <div class="stat-val"><?= (int)($stats['pending_count'] ?? 0) ?></div>
          <div class="stat-lbl">পর্যালোচনাধীন</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon icon-rej">❌</div>
        <div>
          <div class="stat-val"><?= (int)($stats['rejected_count'] ?? 0) ?></div>
          <div class="stat-lbl">প্রত্যাখ্যাত</div>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon icon-view">👁️</div>
        <div>
          <div class="stat-val"><?= number_format((int)($stats['total_views'] ?? 0)) ?></div>
          <div class="stat-lbl">সর্বমোট ভিউ</div>
        </div>
      </div>
    </div>

    <!-- Recent Posts Section -->
    <div class="section-card">
      <div class="section-head">
        <h2>সাম্প্রতিক সংবাদসমূহ</h2>
        <a href="<?= $appUrl ?>/writer/posts" class="btn-action">সবগুলো দেখুন →</a>
      </div>
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>শিরোনাম</th>
              <th>ক্যাটাগরি</th>
              <th>স্ট্যাটাস</th>
              <th>ভিউ</th>
              <th>তারিখ</th>
              <th style="text-align: right;">অ্যাকশন</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($recentPosts)): ?>
              <tr>
                <td colspan="6" style="text-align: center; padding: 40px; color: var(--adm-text-muted);">
                  এখনো কোনো সংবাদ লেখেননি। <a href="<?= $appUrl ?>/writer/posts/create" style="color: #10b981; font-weight: 700;">প্রথম সংবাদ লিখুন ↗</a>
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($recentPosts as $p): ?>
                <tr>
                  <td style="max-width: 400px; font-weight: 600;">
                    <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                      <?= htmlspecialchars($p['title']) ?>
                    </div>
                    <?php if (!empty($p['rejection_reason'])): ?>
                      <div style="font-size: 0.78rem; color: #f87171; margin-top: 4px;">
                        ⚠️ প্রত্যাখ্যাত কারণ: <?= htmlspecialchars($p['rejection_reason']) ?>
                      </div>
                    <?php endif; ?>
                  </td>
                  <td>
                    <span style="background: rgba(255,255,255,0.06); padding: 3px 8px; border-radius: 4px; font-size: 0.82rem;">
                      <?= htmlspecialchars($p['category_name'] ?? 'সাধারণ') ?>
                    </span>
                  </td>
                  <td>
                    <?php if ($p['status'] === 'published'): ?>
                      <span class="badge badge-pub">প্রকাশিত</span>
                    <?php elseif ($p['status'] === 'pending_review'): ?>
                      <span class="badge badge-rev">পর্যালোচনাধীন</span>
                    <?php elseif ($p['status'] === 'rejected'): ?>
                      <span class="badge badge-rej">প্রত্যাখ্যাত</span>
                    <?php else: ?>
                      <span class="badge badge-dft">খসড়া</span>
                    <?php endif; ?>
                  </td>
                  <td style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700;">
                    <?= number_format((int)$p['views']) ?>
                  </td>
                  <td style="font-size: 0.85rem; color: var(--adm-text-muted);">
                    <?= date('d M, Y', strtotime($p['created_at'])) ?>
                  </td>
                  <td style="text-align: right; white-space: nowrap;">
                    <a href="<?= $appUrl ?>/writer/posts/edit?id=<?= (int)$p['id'] ?>" class="btn-action">✏️ সম্পাদনা</a>
                    <?php if ($p['status'] === 'published'): ?>
                      <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($p['category_slug'] ?? 'national') ?>/<?= (int)$p['id'] ?>/<?= urlencode($p['slug']) ?>" target="_blank" class="btn-action" style="color: #38bdf8;">👁️ দেখুন ↗</a>
                    <?php endif; ?>
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

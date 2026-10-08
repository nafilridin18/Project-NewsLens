<?php
use App\Helpers\BanglaDate;
$topViewedPosts = $topViewedPosts ?? [];
?>
<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>ড্যাশবোর্ড ও অ্যানালিটিক্স | Newslensbd CMS</title>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    .admin-container { max-width: 1280px; margin: 30px auto; padding: 0 20px; }
    .page-title-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 12px; }
    .btn-create { background: var(--adm-primary); color: #fff; padding: 10px 18px; border-radius: 6px; text-decoration: none; font-weight: 700; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 6px; }
    .btn-create:hover { background: var(--adm-primary-hover); }
    .metrics-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 30px; }
    .metric-card { background: var(--adm-card-bg); border: 1px solid var(--adm-border); border-radius: 8px; padding: 20px; transition: all 0.2s; }
    .metric-label { font-size: 0.88rem; color: var(--adm-text-muted); margin-bottom: 6px; }
    .metric-value { font-size: 2rem; font-weight: 700; color: var(--adm-text); }
    .metric-pending { color: #fbbf24; }
    .metric-published { color: #4ade80; }
    .metric-views { color: #38bdf8; }
    .section-card { background: var(--adm-card-bg); border: 1px solid var(--adm-border); border-radius: 8px; padding: 20px; margin-bottom: 30px; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .section-header { font-size: 1.15rem; font-weight: 700; margin-bottom: 16px; border-bottom: 1px solid var(--adm-border); padding-bottom: 10px; display: flex; justify-content: space-between; align-items: center; color: var(--adm-text); }
    table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.92rem; }
    th { padding: 12px; border-bottom: 2px solid var(--adm-border); color: var(--adm-text-muted); font-weight: 600; }
    td { padding: 12px; border-bottom: 1px solid var(--adm-border); color: var(--adm-text); }
    .status-pill { padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; display: inline-block; }
    .status-published { background: rgba(74, 222, 128, 0.2); color: #4ade80; }
    .status-pending { background: rgba(251, 191, 36, 0.2); color: #fbbf24; }
    .status-draft { background: rgba(148, 163, 184, 0.2); color: #94a3b8; }
    .rank-badge { display: inline-flex; width: 26px; height: 26px; border-radius: 50%; background: #e11b22; color: #fff; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; }
    .views-counter-tag { background: var(--adm-nav-bg); color: var(--adm-accent); padding: 3px 10px; border-radius: 12px; font-weight: 700; border: 1px solid var(--adm-border); }
  </style>
</head>
<body>
  <?php 
  $activeTab = 'dashboard';
  require __DIR__ . '/partials/header.php'; 
  ?>

  <main class="admin-container">
    <div class="page-title-row">
      <h2>ড্যাশবোর্ড ওভারভিউ ও অ্যানালিটিক্স</h2>
      <div>
        <a href="<?= $appUrl ?>/<?= $adminPath ?>/posts/create" class="btn-create">✍️ নতুন খবর লিখুন</a>
      </div>
    </div>

    <!-- KPI Metric Cards -->
    <div class="metrics-grid">
      <div class="metric-card">
        <div class="metric-label">প্রকাশিত খবর</div>
        <div class="metric-value metric-published"><?= BanglaDate::bnNum($stats['total_published']) ?></div>
      </div>
      <div class="metric-card">
        <div class="metric-label">অনুমোদনের অপেক্ষায় (Pending)</div>
        <div class="metric-value metric-pending"><?= BanglaDate::bnNum($stats['total_pending']) ?></div>
      </div>
      <div class="metric-card">
        <div class="metric-label">খসড়া (Drafts)</div>
        <div class="metric-value"><?= BanglaDate::bnNum($stats['total_drafts']) ?></div>
      </div>
      <div class="metric-card">
        <div class="metric-label">মোট পাঠক ভিউ</div>
        <div class="metric-value metric-views"><?= BanglaDate::bnNum($stats['total_views']) ?></div>
      </div>
    </div>

    <!-- MOST VIEWED NEWS (USER REQUIREMENT: কোন নিউজ বেশি ওপেন হচ্ছে তা দেখা যাবে) -->
    <div class="section-card">
      <div class="section-header">
        <span style="color:#38bdf8;">🔥 সবচেয়ে বেশি পঠিত সংবাদ (Most Viewed News Ranking)</span>
        <small style="color:#94a3b8;">পাঠকদের সর্বোচ্চ পছন্দের শীর্ষ ৫টি খবর</small>
      </div>
      <table>
        <thead>
          <tr>
            <th style="width: 60px;">র‍্যাংক</th>
            <th>সংবাদের শিরোনাম</th>
            <th>ক্যাটাগরি</th>
            <th>পাঠক ভিউ সংখ্যা</th>
            <th>প্রকাশের তারিখ</th>
            <th>অ্যাকশন</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($topViewedPosts)): ?>
            <?php foreach ($topViewedPosts as $idx => $top): ?>
              <tr>
                <td><span class="rank-badge"><?= $idx + 1 ?></span></td>
                <td><strong><?= htmlspecialchars($top['title']) ?></strong></td>
                <td><?= htmlspecialchars($top['category_name']) ?></td>
                <td>
                  <span class="views-counter-tag">👁️ <?= BanglaDate::bnNum($top['views']) ?> বার পঠিত</span>
                </td>
                <td><?= BanglaDate::timeAgo($top['published_at']) ?></td>
                <td>
                  <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($top['category_slug']) ?>/<?= $top['id'] ?>/<?= htmlspecialchars($top['slug']) ?>" target="_blank" style="color:#38bdf8;text-decoration:none;font-weight:600;">খবরটি দেখুন ↗</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="6" style="text-align:center;padding:16px;color:#94a3b8;">কোনো তথ্য পাওয়া যায়নি।</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <!-- Recent Posts Section -->
    <div class="section-card">
      <div class="section-header">
        <span>সাম্প্রতিক খবরসমূহ</span>
        <a href="<?= $appUrl ?>/<?= $adminPath ?>/posts" style="color: #38bdf8; text-decoration: none; font-size: 0.88rem;">সব খবর দেখুন »</a>
      </div>
      <table>
        <thead>
          <tr>
            <th>শিরোনাম</th>
            <th>ক্যাটাগরি</th>
            <th>লেখক</th>
            <th>স্ট্যাটাস</th>
            <th>ভিউ</th>
            <th>সময়</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($recentPosts as $p): ?>
            <tr>
              <td><strong><?= htmlspecialchars($p['title']) ?></strong></td>
              <td><?= htmlspecialchars($p['category_name']) ?></td>
              <td><?= htmlspecialchars($p['author_name'] ?? 'স্টাফ') ?></td>
              <td>
                <span class="status-pill status-<?= $p['status'] ?>">
                  <?= $p['status'] === 'published' ? 'প্রকাশিত' : ($p['status'] === 'pending' ? 'রিভিউধীন' : 'খসড়া') ?>
                </span>
              </td>
              <td><?= BanglaDate::bnNum($p['views']) ?></td>
              <td><?= BanglaDate::timeAgo($p['created_at']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>

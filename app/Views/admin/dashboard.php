<?php
use App\Helpers\BanglaDate;

$topViewedPosts = $topViewedPosts ?? [];
$recentPosts = $recentPosts ?? [];
$stats = $stats ?? [];
$activePoll = $activePoll ?? null;
$categoryDistribution = $categoryDistribution ?? [];
$user = $user ?? ['name' => 'Super Admin', 'role' => 'super_admin'];
$adminPath = $adminPath ?? 'lens-desk';
$appUrl = rtrim($appUrl ?? '', '/');
?>
<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>অ্যাডমিন কমান্ড সেন্টার ও ড্যাশবোর্ড | Newslensbd CMS</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Hind Siliguri', sans-serif; }
    .admin-container { max-width: 1360px; margin: 24px auto 60px; padding: 0 20px; }

    /* Welcome & Quick Bar */
    .dashboard-hero {
      background: linear-gradient(135deg, rgba(14, 122, 58, 0.12), rgba(56, 189, 248, 0.08));
      border: 1px solid var(--adm-border);
      border-radius: 12px;
      padding: 22px 26px;
      margin-bottom: 24px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 16px;
    }
    .hero-info h2 { font-size: 1.55rem; font-weight: 800; color: var(--adm-text); margin-bottom: 4px; display: flex; align-items: center; gap: 8px; }
    .hero-badge { font-size: 0.72rem; padding: 3px 8px; border-radius: 20px; background: #0E7A3A; color: #fff; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .hero-info p { font-size: 0.92rem; color: var(--adm-text-muted); }
    .hero-actions { display: flex; gap: 10px; flex-wrap: wrap; }
    .btn-action-primary {
      background: #0E7A3A;
      color: #ffffff;
      padding: 10px 18px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 700;
      font-size: 0.92rem;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      box-shadow: 0 4px 12px rgba(14, 122, 58, 0.25);
      transition: all 0.2s;
    }
    .btn-action-primary:hover { background: #0a5c2b; transform: translateY(-1px); }
    .btn-action-secondary {
      background: var(--adm-card-bg);
      color: var(--adm-text);
      border: 1px solid var(--adm-border);
      padding: 10px 16px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      font-size: 0.92rem;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.2s;
    }
    .btn-action-secondary:hover { border-color: var(--adm-accent); color: var(--adm-accent); }

    /* KPI Metrics 6-Grid */
    .kpi-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
      gap: 16px;
      margin-bottom: 26px;
    }
    .kpi-card {
      background: var(--adm-card-bg);
      border: 1px solid var(--adm-border);
      border-radius: 10px;
      padding: 18px 20px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      position: relative;
      overflow: hidden;
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .kpi-card:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,0.15); }
    .kpi-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 3px;
      background: var(--kpi-bar, #38bdf8);
    }
    .kpi-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
    .kpi-title { font-size: 0.86rem; color: var(--adm-text-muted); font-weight: 600; }
    .kpi-icon { font-size: 1.25rem; opacity: 0.85; }
    .kpi-value { font-size: 2.1rem; font-weight: 800; color: var(--adm-text); line-height: 1.1; font-family: 'Plus Jakarta Sans', 'Hind Siliguri', sans-serif; }
    .kpi-sub { font-size: 0.78rem; color: var(--adm-text-muted); margin-top: 6px; }

    /* Dashboard Layout: Main 70% + Sidebar 30% */
    .dashboard-layout {
      display: grid;
      grid-template-columns: 2fr 1fr;
      gap: 24px;
    }
    @media (max-width: 1024px) {
      .dashboard-layout { grid-template-columns: 1fr; }
    }

    /* Section Cards */
    .dash-section-card {
      background: var(--adm-card-bg);
      border: 1px solid var(--adm-border);
      border-radius: 10px;
      padding: 22px;
      margin-bottom: 24px;
    }
    .dash-section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 18px;
      padding-bottom: 12px;
      border-bottom: 1px solid var(--adm-border);
      flex-wrap: wrap;
      gap: 8px;
    }
    .dash-section-title { font-size: 1.15rem; font-weight: 700; color: var(--adm-text); display: flex; align-items: center; gap: 8px; }
    .dash-section-link { color: var(--adm-accent); font-size: 0.86rem; text-decoration: none; font-weight: 600; }
    .dash-section-link:hover { text-decoration: underline; }

    /* Tables */
    .table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
    table.dash-table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.92rem; }
    table.dash-table th { padding: 12px 14px; border-bottom: 2px solid var(--adm-border); color: var(--adm-text-muted); font-weight: 600; font-size: 0.85rem; }
    table.dash-table td { padding: 12px 14px; border-bottom: 1px solid var(--adm-border); color: var(--adm-text); vertical-align: middle; }
    table.dash-table tr:hover td { background: rgba(255, 255, 255, 0.02); }

    .rank-badge { display: inline-flex; width: 28px; height: 28px; border-radius: 50%; background: #e11b22; color: #fff; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; box-shadow: 0 2px 6px rgba(225, 27, 34, 0.35); }
    .rank-1 { background: #f59e0b; color: #000; }
    .rank-2 { background: #94a3b8; color: #000; }
    .rank-3 { background: #d97706; color: #fff; }

    .news-title-link { color: var(--adm-text); text-decoration: none; font-weight: 600; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; max-width: 380px; }
    .news-title-link:hover { color: var(--adm-accent); }

    .views-counter-tag { background: var(--adm-nav-bg); color: var(--adm-accent); padding: 4px 10px; border-radius: 12px; font-weight: 700; border: 1px solid var(--adm-border); font-size: 0.82rem; display: inline-flex; align-items: center; gap: 4px; }
    .cat-tag { background: rgba(56, 189, 248, 0.12); color: #38bdf8; padding: 2px 8px; border-radius: 4px; font-size: 0.78rem; font-weight: 600; }

    .status-pill { padding: 3px 9px; border-radius: 4px; font-size: 0.76rem; font-weight: 700; display: inline-block; }
    .status-published { background: rgba(74, 222, 128, 0.18); color: #4ade80; border: 1px solid rgba(74, 222, 128, 0.3); }
    .status-pending { background: rgba(251, 191, 36, 0.18); color: #fbbf24; border: 1px solid rgba(251, 191, 36, 0.3); }
    .status-draft { background: rgba(148, 163, 184, 0.18); color: #94a3b8; border: 1px solid rgba(148, 163, 184, 0.3); }

    .table-actions { display: inline-flex; gap: 6px; align-items: center; }
    .col-action { white-space: nowrap !important; text-align: right; min-width: 90px; }
    .btn-tbl {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 4px;
      white-space: nowrap !important;
      padding: 5px 12px;
      border-radius: 6px;
      font-size: 0.85rem;
      font-weight: 700;
      text-decoration: none;
      line-height: 1;
      transition: all 0.2s;
      box-sizing: border-box;
    }
    .btn-tbl .btn-icon {
      display: inline-block;
      font-size: 0.95rem;
      line-height: 1;
    }
    .btn-tbl-edit { background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); }
    .btn-tbl-edit:hover { background: #38bdf8; color: #000; }
    .btn-tbl-view { background: rgba(14, 122, 58, 0.15); color: #4ade80; border: 1px solid rgba(14, 122, 58, 0.3); }
    .btn-tbl-view:hover { background: #0E7A3A; color: #fff; }

    /* Responsive: When screen/tab gets smaller or space is tight, show ONLY the icon */
    @media (max-width: 1150px) {
      .col-action { min-width: 44px; width: 44px; }
      .btn-tbl { padding: 5px 8px; }
      .btn-tbl .btn-text { display: none !important; }
    }

    /* Sidebar Widgets */
    .quick-links-list { display: flex; flex-direction: column; gap: 10px; }
    .quick-link-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 12px 14px;
      background: var(--adm-nav-bg);
      border: 1px solid var(--adm-border);
      border-radius: 8px;
      text-decoration: none;
      color: var(--adm-text);
      transition: all 0.2s;
    }
    .quick-link-item:hover { border-color: var(--adm-accent); transform: translateX(3px); }
    .quick-link-icon { font-size: 1.25rem; width: 32px; height: 32px; border-radius: 8px; background: rgba(255,255,255,0.05); display: flex; align-items: center; justify-content: center; }
    .quick-link-title { font-weight: 700; font-size: 0.92rem; }
    .quick-link-desc { font-size: 0.78rem; color: var(--adm-text-muted); }

    /* Category breakdown bars */
    .cat-bar-item { margin-bottom: 12px; }
    .cat-bar-header { display: flex; justify-content: space-between; font-size: 0.86rem; margin-bottom: 4px; }
    .cat-bar-bg { height: 7px; background: var(--adm-border); border-radius: 10px; overflow: hidden; }
    .cat-bar-fill { height: 100%; background: linear-gradient(90deg, #0E7A3A, #38bdf8); border-radius: 10px; }

    /* Poll Quick Widget */
    .poll-dash-box { background: var(--adm-nav-bg); border: 1px solid var(--adm-border); border-radius: 8px; padding: 14px; }
    .poll-dash-q { font-weight: 700; font-size: 0.92rem; color: var(--adm-text); margin-bottom: 8px; }
    .poll-dash-meta { font-size: 0.8rem; color: var(--adm-text-muted); display: flex; justify-content: space-between; margin-top: 8px; border-top: 1px solid var(--adm-border); padding-top: 6px; }

    /* System Status */
    .sys-status-list { font-size: 0.85rem; color: var(--adm-text-muted); display: flex; flex-direction: column; gap: 8px; }
    .sys-status-item { display: flex; justify-content: space-between; border-bottom: 1px dashed var(--adm-border); padding-bottom: 6px; }
    .sys-status-item strong { color: var(--adm-text); }
  </style>
</head>
<body>
  <?php 
  $activeTab = 'dashboard';
  require __DIR__ . '/partials/header.php'; 
  ?>

  <main class="admin-container">

    <!-- 1. Welcome & Executive Bar (No duplicate buttons) -->
    <section class="dashboard-hero">
      <div class="hero-info">
        <h2>
          <span><span data-bn="স্বাগতম" data-en="Welcome">স্বাগতম</span>, <?= htmlspecialchars($user['name'] ?? 'Super Admin') ?>!</span>
          <span class="hero-badge" data-bn="কমান্ড সেন্টার" data-en="Command Center">কমান্ড সেন্টার</span>
        </h2>
        <p><span data-bn="Newslensbd (নিউজলেন্সবিডি) কনটেন্ট ও পাবলিশিং অপারেশনাল ড্যাশবোর্ড • আজকের তারিখ:" data-en="Newslensbd Content &amp; Publishing Operational Dashboard • Today's Date:">Newslensbd (নিউজলেন্সবিডি) কনটেন্ট ও পাবলিশিং অপারেশনাল ড্যাশবোর্ড • আজকের তারিখ:</span> <span data-bn="<?= BanglaDate::formatBnDate() ?>" data-en="<?= date('F j, Y') ?>"><?= BanglaDate::formatBnDate() ?></span></p>
      </div>
      <div class="hero-actions">
        <a href="<?= $appUrl ?>/<?= $adminPath ?>/posts/create" class="btn-action-primary"><span data-bn="✍️ নতুন সংবাদ লিখুন" data-en="✍️ Write News">✍️ নতুন সংবাদ লিখুন</span></a>
      </div>
    </section>

    <!-- 2. KPI Metrics Grid (All Features Intact) -->
    <section class="kpi-grid">
      <!-- Published -->
      <div class="kpi-card" style="--kpi-bar: #4ade80;">
        <div class="kpi-top">
          <span class="kpi-title" data-bn="প্রকাশিত সংবাদ" data-en="Published News">প্রকাশিত সংবাদ</span>
          <span class="kpi-icon">📰</span>
        </div>
        <div class="kpi-value" style="color: #4ade80;" data-bn="<?= BanglaDate::bnNum($stats['total_published']) ?>" data-en="<?= (int)($stats['total_published'] ?? 0) ?>"><?= BanglaDate::bnNum($stats['total_published']) ?></div>
        <div class="kpi-sub" data-bn="সর্বমোট লাইভ আর্টিকেল" data-en="Total Live Articles">সর্বমোট লাইভ আর্টিকেল</div>
      </div>

      <!-- Pending -->
      <div class="kpi-card" style="--kpi-bar: #fbbf24;">
        <div class="kpi-top">
          <span class="kpi-title" data-bn="রিভিউ ও পেন্ডিং" data-en="Review &amp; Pending">রিভিউ ও পেন্ডিং</span>
          <span class="kpi-icon">⏳</span>
        </div>
        <div class="kpi-value" style="color: #fbbf24;" data-bn="<?= BanglaDate::bnNum($stats['total_pending']) ?>" data-en="<?= (int)($stats['total_pending'] ?? 0) ?>"><?= BanglaDate::bnNum($stats['total_pending']) ?></div>
        <div class="kpi-sub" data-bn="অনুমোদনের অপেক্ষায়" data-en="Awaiting Approval">অনুমোদনের অপেক্ষায়</div>
      </div>

      <!-- Drafts -->
      <div class="kpi-card" style="--kpi-bar: #94a3b8;">
        <div class="kpi-top">
          <span class="kpi-title" data-bn="খসড়া সংবাদ" data-en="Draft News">খসড়া সংবাদ</span>
          <span class="kpi-icon">📝</span>
        </div>
        <div class="kpi-value" style="color: #cbd5e1;" data-bn="<?= BanglaDate::bnNum($stats['total_drafts']) ?>" data-en="<?= (int)($stats['total_drafts'] ?? 0) ?>"><?= BanglaDate::bnNum($stats['total_drafts']) ?></div>
        <div class="kpi-sub" data-bn="অপ্রকাশিত খসড়া ফাইল" data-en="Unpublished Drafts">অপ্রকাশিত খসড়া ফাইল</div>
      </div>

      <!-- Views -->
      <div class="kpi-card" style="--kpi-bar: #38bdf8;">
        <div class="kpi-top">
          <span class="kpi-title" data-bn="মোট পাঠক ভিউ" data-en="Total Reader Views">মোট পাঠক ভিউ</span>
          <span class="kpi-icon">👁️</span>
        </div>
        <div class="kpi-value" style="color: #38bdf8;" data-bn="<?= BanglaDate::bnNum($stats['total_views']) ?>" data-en="<?= number_format((int)($stats['total_views'] ?? 0)) ?>"><?= BanglaDate::bnNum($stats['total_views']) ?></div>
        <div class="kpi-sub" data-bn="পাঠকদের মোট ভিজিট সংখ্যা" data-en="Total Reader Visits">পাঠকদের মোট ভিজিট সংখ্যা</div>
      </div>

      <!-- Active Ads -->
      <div class="kpi-card" style="--kpi-bar: #ec4899;">
        <div class="kpi-top">
          <span class="kpi-title" data-bn="সক্রিয় বিজ্ঞাপন" data-en="Active Ads">সক্রিয় বিজ্ঞাপন</span>
          <span class="kpi-icon">📢</span>
        </div>
        <div class="kpi-value" style="color: #f472b6;" data-bn="<?= BanglaDate::bnNum($stats['total_ads'] ?? 0) ?>" data-en="<?= (int)($stats['total_ads'] ?? 0) ?>"><?= BanglaDate::bnNum($stats['total_ads'] ?? 0) ?></div>
        <div class="kpi-sub" data-bn="চলমান স্পন্সর স্লট" data-en="Running Sponsor Slots">চলমান স্পন্সর স্লট</div>
      </div>

      <!-- Subscribers -->
      <div class="kpi-card" style="--kpi-bar: #a855f7;">
        <div class="kpi-top">
          <span class="kpi-title" data-bn="ইমেইল গ্রাহক" data-en="Email Subscribers">ইমেইল গ্রাহক</span>
          <span class="kpi-icon">📧</span>
        </div>
        <div class="kpi-value" style="color: #c084fc;" data-bn="<?= BanglaDate::bnNum($stats['total_subscribers'] ?? 0) ?>" data-en="<?= (int)($stats['total_subscribers'] ?? 0) ?>"><?= BanglaDate::bnNum($stats['total_subscribers'] ?? 0) ?></div>
        <div class="kpi-sub" data-bn="নিউজলেটার গ্রাহক সংখ্যা" data-en="Newsletter Subscribers">নিউজলেটার গ্রাহক সংখ্যা</div>
      </div>
    </section>

    <!-- 3. Dual-Column Content Grid -->
    <div class="dashboard-layout">

      <!-- LEFT COLUMN: Most Viewed & Recent News (70%) -->
      <div class="dash-col-main">

        <!-- Most Viewed News (User Requirement: Ranking) -->
        <div class="dash-section-card">
          <div class="dash-section-header">
            <h3 class="dash-section-title">
              <span data-bn="🔥 সর্বাধিক পঠিত শীর্ষ ৫ সংবাদ" data-en="🔥 Top 5 Most Viewed News">🔥 সর্বাধিক পঠিত শীর্ষ ৫ সংবাদ</span>
            </h3>
            <span style="font-size: 0.8rem; color: var(--adm-text-muted);" data-bn="রিয়েলটাইম ভিউ অ্যানালিটিক্স" data-en="Realtime View Analytics">রিয়েলটাইম ভিউ অ্যানালিটিক্স</span>
          </div>

          <div class="table-responsive">
            <table class="dash-table">
              <thead>
                <tr>
                  <th style="width: 50px;" data-bn="র‍্যাংক" data-en="Rank">র‍্যাংক</th>
                  <th data-bn="শিরোনাম" data-en="Title">শিরোনাম</th>
                  <th data-bn="ক্যাটাগরি" data-en="Category">ক্যাটাগরি</th>
                  <th data-bn="পাঠক ভিউ" data-en="Reader Views">পাঠক ভিউ</th>
                  <th data-bn="প্রকাশিত" data-en="Published">প্রকাশিত</th>
                  <th class="col-action" style="text-align: right;" data-bn="অ্যাকশন" data-en="Action">অ্যাকশন</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($topViewedPosts)): ?>
                  <?php foreach ($topViewedPosts as $idx => $top): ?>
                    <tr>
                      <td>
                        <span class="rank-badge <?= $idx === 0 ? 'rank-1' : ($idx === 1 ? 'rank-2' : ($idx === 2 ? 'rank-3' : '')) ?>" data-bn="<?= BanglaDate::bnNum($idx + 1) ?>" data-en="<?= $idx + 1 ?>">
                          <?= $idx + 1 ?>
                        </span>
                      </td>
                      <td>
                        <!-- News Title: Not translated per user request -->
                        <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($top['category_slug']) ?>/<?= $top['id'] ?>/<?= htmlspecialchars($top['slug']) ?>" target="_blank" class="news-title-link" title="<?= htmlspecialchars($top['title']) ?>">
                          <?= htmlspecialchars($top['title']) ?>
                        </a>
                      </td>
                      <td><span class="cat-tag" data-bn="<?= htmlspecialchars($top['category_name']) ?>" data-en="<?= htmlspecialchars(!empty($top['category_name_en']) ? $top['category_name_en'] : $top['category_name']) ?>"><?= htmlspecialchars($top['category_name']) ?></span></td>
                      <td>
                        <span class="views-counter-tag" data-bn="👁️ <?= BanglaDate::bnNum($top['views']) ?>" data-en="👁️ <?= number_format((int)$top['views']) ?>">👁️ <?= BanglaDate::bnNum($top['views']) ?></span>
                      </td>
                      <td style="font-size: 0.82rem; color: var(--adm-text-muted);" data-bn="<?= BanglaDate::timeAgo($top['published_at'] ?? 'now') ?>" data-en="<?= date('M j, Y', strtotime($top['published_at'] ?? 'now')) ?>"><?= BanglaDate::timeAgo($top['published_at'] ?? 'now') ?></td>
                      <td class="col-action">
                        <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($top['category_slug']) ?>/<?= $top['id'] ?>/<?= htmlspecialchars($top['slug']) ?>" target="_blank" class="btn-tbl btn-tbl-view" title="খবরটি দেখুন">
                          <span class="btn-text" data-bn="দেখুন" data-en="View">দেখুন</span> <span class="btn-icon">↗</span>
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="6" style="text-align:center;padding:18px;color:var(--adm-text-muted);" data-bn="কোনো সংবাদ পাওয়া যায়নি।" data-en="No news articles found.">কোনো সংবাদ পাওয়া যায়নি।</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Recent News Stream -->
        <div class="dash-section-card">
          <div class="dash-section-header">
            <h3 class="dash-section-title">
              <span data-bn="📝 সাম্প্রতিক প্রকাশিত ও খসড়া সংবাদ" data-en="📝 Recent Published &amp; Draft News">📝 সাম্প্রতিক প্রকাশিত ও খসড়া সংবাদ</span>
            </h3>
            <a href="<?= $appUrl ?>/<?= $adminPath ?>/posts" class="dash-section-link" data-bn="সকল সংবাদ পরিচালনা »" data-en="Manage All News »">সকল সংবাদ পরিচালনা »</a>
          </div>

          <div class="table-responsive">
            <table class="dash-table">
              <thead>
                <tr>
                  <th data-bn="শিরোনাম" data-en="Title">শিরোনাম</th>
                  <th data-bn="ক্যাটাগরি" data-en="Category">ক্যাটাগরি</th>
                  <th data-bn="লেখক" data-en="Author">লেখক</th>
                  <th data-bn="অবস্থা" data-en="Status">অবস্থা</th>
                  <th data-bn="ভিউ" data-en="Views">ভিউ</th>
                  <th data-bn="তারিখ" data-en="Date">তারিখ</th>
                  <th class="col-action" style="text-align: right;" data-bn="অ্যাকশন" data-en="Action">অ্যাকশন</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($recentPosts)): ?>
                  <?php foreach ($recentPosts as $p): ?>
                    <tr>
                      <td>
                        <!-- News Title: Not translated per user request -->
                        <a href="<?= $appUrl ?>/<?= $adminPath ?>/posts/edit?id=<?= $p['id'] ?>" class="news-title-link" title="<?= htmlspecialchars($p['title']) ?>">
                          <?= htmlspecialchars($p['title']) ?>
                        </a>
                      </td>
                      <td><span class="cat-tag" data-bn="<?= htmlspecialchars($p['category_name']) ?>" data-en="<?= htmlspecialchars(!empty($p['category_name_en']) ? $p['category_name_en'] : $p['category_name']) ?>"><?= htmlspecialchars($p['category_name']) ?></span></td>
                      <td style="font-size: 0.85rem;" data-bn="<?= htmlspecialchars($p['author_name'] ?? 'স্টাফ') ?>" data-en="<?= htmlspecialchars(!empty($p['author_name']) ? ($p['author_name'] === 'স্টাফ' ? 'Staff' : $p['author_name']) : 'Staff') ?>"><?= htmlspecialchars($p['author_name'] ?? 'স্টাফ') ?></td>
                      <td>
                        <span class="status-pill status-<?= $p['status'] ?>" data-bn="<?= $p['status'] === 'published' ? 'প্রকাশিত' : ($p['status'] === 'pending' ? 'রিভিউধীন' : 'খসড়া') ?>" data-en="<?= $p['status'] === 'published' ? 'Published' : ($p['status'] === 'pending' ? 'Under Review' : 'Draft') ?>">
                          <?= $p['status'] === 'published' ? 'প্রকাশিত' : ($p['status'] === 'pending' ? 'রিভিউধীন' : 'খসড়া') ?>
                        </span>
                      </td>
                      <td data-bn="<?= BanglaDate::bnNum($p['views']) ?>" data-en="<?= number_format((int)$p['views']) ?>"><?= BanglaDate::bnNum($p['views']) ?></td>
                      <td style="font-size: 0.82rem; color: var(--adm-text-muted);" data-bn="<?= BanglaDate::timeAgo($p['created_at']) ?>" data-en="<?= date('M j, Y', strtotime($p['created_at'])) ?>"><?= BanglaDate::timeAgo($p['created_at']) ?></td>
                      <td class="col-action">
                        <div class="table-actions">
                          <a href="<?= $appUrl ?>/<?= $adminPath ?>/posts/edit?id=<?= $p['id'] ?>" class="btn-tbl btn-tbl-edit" title="এডিট করুন"><span class="btn-icon">✏️</span><span class="btn-text" data-bn="এডিট" data-en="Edit">এডিট</span></a>
                          <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($p['category_slug'] ?? 'national') ?>/<?= $p['id'] ?>/<?= htmlspecialchars($p['slug'] ?? '') ?>" target="_blank" class="btn-tbl btn-tbl-view" title="প্রিভিউ দেখুন"><span class="btn-icon">👁️</span><span class="btn-text" data-bn="ভিউ" data-en="View">ভিউ</span></a>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr><td colspan="7" style="text-align:center;padding:18px;color:var(--adm-text-muted);" data-bn="কোনো সাম্প্রতিক সংবাদ পাওয়া যায়নি।" data-en="No recent news found.">কোনো সাম্প্রতিক সংবাদ পাওয়া যায়নি।</td></tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

      </div>

      <!-- RIGHT COLUMN: Category Stats & System Status (30%) - Duplicate quick actions removed -->
      <div class="dash-col-side">

        <!-- 2. Category Distribution Breakdown -->
        <?php if (!empty($categoryDistribution)): ?>
          <div class="dash-section-card">
            <div class="dash-section-header">
              <h4 class="dash-section-title" data-bn="📊 শীর্ষ ক্যাটাগরি বিশ্লেষণ" data-en="📊 Top Category Analytics">📊 শীর্ষ ক্যাটাগরি বিশ্লেষণ</h4>
            </div>
            <div>
              <?php 
                $maxCount = 1;
                foreach ($categoryDistribution as $cd) {
                  if ((int)$cd['post_count'] > $maxCount) $maxCount = (int)$cd['post_count'];
                }
                foreach ($categoryDistribution as $cd): 
                  $pct = round(($cd['post_count'] / $maxCount) * 100);
              ?>
                <div class="cat-bar-item">
                  <div class="cat-bar-header">
                    <span data-bn="<?= htmlspecialchars($cd['name_bn']) ?>" data-en="<?= htmlspecialchars(!empty($cd['name_en']) ? $cd['name_en'] : $cd['name_bn']) ?>"><?= htmlspecialchars($cd['name_bn']) ?></span>
                    <strong data-bn="<?= BanglaDate::bnNum($cd['post_count']) ?> টি" data-en="<?= (int)$cd['post_count'] ?> items"><?= BanglaDate::bnNum($cd['post_count']) ?> টি</strong>
                  </div>
                  <div class="cat-bar-bg">
                    <div class="cat-bar-fill" style="width: <?= $pct ?>%;"></div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>

        <!-- 3. Active Poll Summary -->
        <?php if (!empty($activePoll)): ?>
          <div class="dash-section-card">
            <div class="dash-section-header">
              <h4 class="dash-section-title" data-bn="🗳️ সক্রিয় জরিপ পর্যবেক্ষণ" data-en="🗳️ Active Poll Monitor">🗳️ সক্রিয় জরিপ পর্যবেক্ষণ</h4>
              <a href="<?= $appUrl ?>/<?= $adminPath ?>/polls" class="dash-section-link" data-bn="ম্যানেজ »" data-en="Manage »">ম্যানেজ »</a>
            </div>
            <div class="poll-dash-box">
              <div class="poll-dash-q"><?= htmlspecialchars($activePoll['question']) ?></div>
              <div class="poll-dash-meta">
                <span><span data-bn="মোট ভোট:" data-en="Total Votes:">মোট ভোট:</span> <strong data-bn="<?= BanglaDate::bnNum($activePoll['total_votes'] ?? 0) ?>" data-en="<?= (int)($activePoll['total_votes'] ?? 0) ?>"><?= BanglaDate::bnNum($activePoll['total_votes'] ?? 0) ?></strong></span>
                <span style="color: #4ade80;" data-bn="● লাইভ চলমান" data-en="● Live Active">● লাইভ চলমান</span>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <!-- 4. System & Server Health -->
        <div class="dash-section-card">
          <div class="dash-section-header">
            <h4 class="dash-section-title" data-bn="🛡️ সিস্টেম ও ডেটাবেস তথ্য" data-en="🛡️ System &amp; Database Info">🛡️ সিস্টেম ও ডেটাবেস তথ্য</h4>
          </div>
          <div class="sys-status-list">
            <div class="sys-status-item">
              <span data-bn="ডাটাবেস সার্ভার:" data-en="Database Server:">ডাটাবেস সার্ভার:</span>
              <strong style="color: #4ade80;">MariaDB (Port 3307)</strong>
            </div>
            <div class="sys-status-item">
              <span data-bn="সক্রিয় ডাটাবেস:" data-en="Active Database:">সক্রিয় ডাটাবেস:</span>
              <strong>newslensbd</strong>
            </div>
            <div class="sys-status-item">
              <span data-bn="PHP সংস্করণ:" data-en="PHP Version:">PHP সংস্করণ:</span>
              <strong><?= phpversion() ?></strong>
            </div>
            <div class="sys-status-item">
              <span data-bn="CMS ফ্রেমওয়ার্ক:" data-en="CMS Framework:">CMS ফ্রেমওয়ার্ক:</span>
              <strong>NewsLens v2.4 Enterprise</strong>
            </div>
            <div class="sys-status-item">
              <span data-bn="ব্রাউজার ক্যাশ:" data-en="Browser Cache:">ব্রাউজার ক্যাশ:</span>
              <strong style="color: #38bdf8;" data-bn="অপ্টিমাইজড" data-en="Optimized">অপ্টিমাইজড</strong>
            </div>
          </div>
        </div>

      </div>

    </div>

  </main>

  <!-- Admin Panel Full Footer (Matching Public Portal & User's Uploaded Design) -->
  <?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>

<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>বিজ্ঞাপন ব্যবস্থাপনা (Advertisements) | Newslens Desk</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    .admin-container { max-width: 1240px; margin: 24px auto 60px; padding: 0 20px; font-family: 'Hind Siliguri', sans-serif; }
    
    /* Header Row */
    .page-title-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .page-title-row h2 { color: var(--adm-text, #f1f5f9); font-size: 1.6rem; font-weight: 700; display: flex; align-items: center; gap: 10px; }
    .page-subtitle { color: var(--adm-text-muted, #94a3b8); font-size: 0.92rem; margin-top: 4px; }
    
    /* Metrics / KPI Grid */
    .metrics-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 16px; margin-bottom: 28px; }
    .metric-card { 
      background: var(--adm-card-bg, #0c1e3d); 
      border: 1px solid var(--adm-border, #173567); 
      border-radius: 12px; 
      padding: 18px 20px; 
      display: flex; 
      align-items: center; 
      gap: 16px; 
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); 
      transition: transform 0.2s ease, border-color 0.2s ease;
    }
    .metric-card:hover { transform: translateY(-2px); border-color: rgba(56, 189, 248, 0.4); }
    .metric-icon-box { 
      width: 48px; 
      height: 48px; 
      border-radius: 10px; 
      display: flex; 
      align-items: center; 
      justify-content: center; 
      font-size: 1.4rem; 
      flex-shrink: 0; 
    }
    .m-blue { background: rgba(56, 189, 248, 0.12); color: #38bdf8; }
    .m-green { background: rgba(34, 197, 94, 0.12); color: #22c55e; }
    .m-purple { background: rgba(168, 85, 247, 0.12); color: #c084fc; }
    .m-amber { background: rgba(245, 158, 11, 0.12); color: #f59e0b; }
    .m-cyan { background: rgba(6, 182, 212, 0.12); color: #06b6d4; }
    
    .metric-info h4 { font-size: 0.82rem; color: var(--adm-text-muted, #94a3b8); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; }
    .metric-info .metric-num { font-size: 1.55rem; color: var(--adm-text, #f1f5f9); font-weight: 700; line-height: 1.2; margin-top: 2px; }

    /* Slots Visual Guide */
    .slots-guide-banner {
      background: linear-gradient(135deg, rgba(8, 30, 66, 0.7) 0%, rgba(12, 30, 61, 0.9) 100%);
      border: 1px solid var(--adm-border, #173567);
      border-radius: 12px;
      padding: 16px 20px;
      margin-bottom: 28px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 12px;
    }
    .slots-chips { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; }
    .slot-chip { 
      background: var(--adm-nav-bg, #081e42); 
      border: 1px solid var(--adm-border, #173567); 
      border-radius: 20px; 
      padding: 6px 14px; 
      font-size: 0.84rem; 
      color: var(--adm-text, #f1f5f9); 
      display: flex; 
      align-items: center; 
      gap: 6px; 
    }
    .slot-chip strong { color: #38bdf8; }

    /* Forms */
    .create-card { 
      background: var(--adm-card-bg, #0c1e3d); 
      border-radius: 12px; 
      border: 1px solid var(--adm-border, #173567); 
      padding: 24px; 
      margin-bottom: 32px; 
      box-shadow: 0 4px 14px rgba(0,0,0,0.06); 
    }
    .card-title-head { 
      display: flex; 
      align-items: center; 
      justify-content: space-between; 
      margin-bottom: 20px; 
      padding-bottom: 12px; 
      border-bottom: 1px solid var(--adm-border, #173567); 
    }
    .card-title-head h3 { font-size: 1.2rem; color: var(--adm-text, #f1f5f9); display: flex; align-items: center; gap: 8px; }

    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; margin-bottom: 18px; }
    @media (max-width: 768px) { .form-grid-2 { grid-template-columns: 1fr; } }
    .form-group { margin-bottom: 18px; }
    .form-group label { display: block; font-weight: 600; margin-bottom: 6px; color: var(--adm-text, #f1f5f9); font-size: 0.92rem; }
    .form-control { 
      width: 100%; 
      padding: 10px 14px; 
      background: var(--adm-nav-bg, #081e42); 
      border: 1px solid var(--adm-border, #173567); 
      border-radius: 8px; 
      font-size: 0.95rem; 
      color: var(--adm-text, #f1f5f9); 
      font-family: inherit; 
      transition: border-color 0.2s;
    }
    .form-control:focus { border-color: #38bdf8; outline: none; box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15); }
    textarea.form-control { font-family: monospace; font-size: 0.88rem; }
    
    .btn-submit { 
      background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); 
      color: #fff; 
      padding: 11px 26px; 
      border-radius: 8px; 
      border: none; 
      font-weight: 700; 
      font-size: 0.98rem; 
      cursor: pointer; 
      display: inline-flex; 
      align-items: center; 
      gap: 8px; 
      transition: transform 0.15s ease, box-shadow 0.15s ease;
    }
    .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(2, 132, 199, 0.35); }

    /* Live Preview Box */
    .live-preview-box {
      margin-top: 14px;
      padding: 14px;
      background: rgba(0, 0, 0, 0.2);
      border: 1px dashed var(--adm-border, #173567);
      border-radius: 8px;
      text-align: center;
      display: none;
    }
    .live-preview-box img { max-height: 120px; max-width: 100%; border-radius: 6px; }

    /* Ads Table */
    .ads-table-card { 
      background: var(--adm-card-bg, #0c1e3d); 
      border-radius: 12px; 
      border: 1px solid var(--adm-border, #173567); 
      padding: 20px; 
      box-shadow: 0 4px 14px rgba(0,0,0,0.06); 
      overflow-x: auto; 
    }
    table { width: 100%; border-collapse: collapse; font-size: 0.92rem; text-align: left; }
    th { padding: 12px 14px; border-bottom: 2px solid var(--adm-border, #173567); color: var(--adm-text-muted, #94a3b8); font-weight: 600; font-size: 0.86rem; text-transform: uppercase; letter-spacing: 0.5px; }
    td { padding: 14px; border-bottom: 1px solid var(--adm-border, #173567); color: var(--adm-text, #f1f5f9); vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: rgba(255, 255, 255, 0.015); }

    /* Badges */
    .badge-pos { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 20px; font-weight: 600; font-size: 0.78rem; }
    .pos-header { background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid rgba(56, 189, 248, 0.3); }
    .pos-sidebar { background: rgba(168, 85, 247, 0.15); color: #c084fc; border: 1px solid rgba(168, 85, 247, 0.3); }
    .pos-in_article { background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }
    .pos-footer { background: rgba(20, 184, 166, 0.15); color: #2dd4bf; border: 1px solid rgba(20, 184, 166, 0.3); }

    .badge-type { background: rgba(255, 255, 255, 0.08); color: var(--adm-text, #f1f5f9); padding: 3px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; }
    .ad-thumb { max-height: 50px; max-width: 110px; object-fit: contain; border-radius: 6px; border: 1px solid var(--adm-border, #173567); background: #ffffff; padding: 2px; }

    /* Action Buttons */
    .btn-sm-action { border: none; padding: 6px 12px; border-radius: 6px; font-size: 0.82rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; transition: opacity 0.15s; font-family: inherit; }
    .btn-sm-action:hover { opacity: 0.88; }
    .btn-toggle { background: #0284c7; color: #fff; }
    .btn-edit { background: #6366f1; color: #fff; }
    .btn-del { background: #ef4444; color: #fff; }

    /* Stats inside table */
    .stat-pill { display: inline-flex; align-items: center; gap: 4px; font-size: 0.82rem; color: var(--adm-text-muted, #94a3b8); margin-right: 8px; }
    .stat-pill strong { color: var(--adm-text, #f1f5f9); }

    /* Alerts */
    .alert-banner { background: #064e3b; border: 1px solid #059669; color: #a7f3d0; padding: 14px 20px; border-radius: 10px; margin-bottom: 24px; display: flex; align-items: center; gap: 10px; font-weight: 600; }

    .hyperlink-preview { color: #38bdf8; text-decoration: none; word-break: break-all; font-size: 0.84rem; display: inline-flex; align-items: center; gap: 4px; }
    .hyperlink-preview:hover { text-decoration: underline; color: #7dd3fc; }

    /* Edit Modal */
    .modal-overlay {
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0, 0, 0, 0.7);
      backdrop-filter: blur(4px);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      padding: 20px;
    }
    .modal-box {
      background: var(--adm-card-bg, #0c1e3d);
      border: 1px solid var(--adm-border, #173567);
      border-radius: 14px;
      max-width: 680px;
      width: 100%;
      padding: 28px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
      max-height: 90vh;
      overflow-y: auto;
    }
  </style>
</head>
<body>

<?php
$activeTab = 'ads';
require __DIR__ . '/partials/header.php';
?>

<main class="admin-container">
  <?php if (isset($_GET['msg'])): ?>
    <div class="alert-banner">
      <?php if ($_GET['msg'] === 'created'): ?>
        <span data-bn="✅ নতুন বিজ্ঞাপন সফলভাবে প্রকাশ করা হয়েছে!" data-en="✅ New advertisement successfully published!">✅ নতুন বিজ্ঞাপন সফলভাবে প্রকাশ করা হয়েছে!</span>
      <?php elseif ($_GET['msg'] === 'updated'): ?>
        <span data-bn="✅ বিজ্ঞাপনের তথ্য সফলভাবে আপডেট করা হয়েছে!" data-en="✅ Advertisement information successfully updated!">✅ বিজ্ঞাপনের তথ্য সফলভাবে আপডেট করা হয়েছে!</span>
      <?php elseif ($_GET['msg'] === 'toggled'): ?>
        <span data-bn="✅ বিজ্ঞাপনের সক্রিয় স্ট্যাটাস সফলভাবে পরিবর্তন করা হয়েছে!" data-en="✅ Advertisement status successfully changed!">✅ বিজ্ঞাপনের সক্রিয় স্ট্যাটাস সফলভাবে পরিবর্তন করা হয়েছে!</span>
      <?php elseif ($_GET['msg'] === 'deleted'): ?>
        <span data-bn="✅ বিজ্ঞাপনটি সফলভাবে ডাটাবেজ থেকে মুছে ফেলা হয়েছে!" data-en="✅ Advertisement successfully deleted from database!">✅ বিজ্ঞাপনটি সফলভাবে ডাটাবেজ থেকে মুছে ফেলা হয়েছে!</span>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <div class="page-title-row">
    <div>
      <h2 data-bn="📢 বিজ্ঞাপন ব্যবস্থাপনা ও অ্যানালিটিক্স" data-en="📢 Advertisement Management & Analytics">📢 বিজ্ঞাপন ব্যবস্থাপনা ও অ্যানালিটিক্স</h2>
      <p class="page-subtitle" data-bn="ছবি, ভিডিও ও কোড বিজ্ঞাপন পরিচালনা করুন, ক্লিক/ইমপ্রেশন ট্র্যাক করুন এবং পোর্টালের নির্ধারিত স্থানে প্রদর্শন নিশ্চিত করুন" data-en="Manage picture, video and code ads, track clicks/impressions and ensure display in designated portal slots">ছবি, ভিডিও ও কোড বিজ্ঞাপন পরিচালনা করুন, ক্লিক/ইমপ্রেশন ট্র্যাক করুন এবং পোর্টালের নির্ধারিত স্থানে প্রদর্শন নিশ্চিত করুন</p>
    </div>
  </div>

  <!-- KPI Metrics Grid -->
  <?php
    $stTotal = $stats['total'] ?? count($ads);
    $stActive = $stats['active'] ?? 0;
    $stClicks = $stats['clicks'] ?? 0;
    $stImpressions = $stats['impressions'] ?? 0;
    $stCtr = $stats['ctr'] ?? 0;
  ?>
  <div class="metrics-grid">
    <div class="metric-card">
      <div class="metric-icon-box m-blue">📊</div>
      <div class="metric-info">
        <h4 data-bn="সর্বমোট বিজ্ঞাপন" data-en="Total Ads">সর্বমোট বিজ্ঞাপন</h4>
        <div class="metric-num"><span data-bn="<?= \App\Helpers\BanglaDate::bnNum($stTotal) ?> টি" data-en="<?= $stTotal ?> Ads"><?= \App\Helpers\BanglaDate::bnNum($stTotal) ?> টি</span></div>
      </div>
    </div>
    <div class="metric-card">
      <div class="metric-icon-box m-green">🟢</div>
      <div class="metric-info">
        <h4 data-bn="চলমান সক্রিয়" data-en="Active Ads">চলমান সক্রিয়</h4>
        <div class="metric-num"><span data-bn="<?= \App\Helpers\BanglaDate::bnNum($stActive) ?> টি" data-en="<?= $stActive ?> Active"><?= \App\Helpers\BanglaDate::bnNum($stActive) ?> টি</span></div>
      </div>
    </div>
    <div class="metric-card">
      <div class="metric-icon-box m-purple">👁️</div>
      <div class="metric-info">
        <h4 data-bn="সর্বমোট প্রদর্শন (Views)" data-en="Total Views">সর্বমোট প্রদর্শন (Views)</h4>
        <div class="metric-num"><span data-bn="<?= \App\Helpers\BanglaDate::bnNum($stImpressions) ?> বার" data-en="<?= $stImpressions ?> Views"><?= \App\Helpers\BanglaDate::bnNum($stImpressions) ?> বার</span></div>
      </div>
    </div>
    <div class="metric-card">
      <div class="metric-icon-box m-amber">🖱️</div>
      <div class="metric-info">
        <h4 data-bn="সর্বমোট ক্লিক (Clicks)" data-en="Total Clicks">সর্বমোট ক্লিক (Clicks)</h4>
        <div class="metric-num"><span data-bn="<?= \App\Helpers\BanglaDate::bnNum($stClicks) ?> বার" data-en="<?= $stClicks ?> Clicks"><?= \App\Helpers\BanglaDate::bnNum($stClicks) ?> বার</span></div>
      </div>
    </div>
    <div class="metric-card">
      <div class="metric-icon-box m-cyan">📈</div>
      <div class="metric-info">
        <h4 data-bn="ক্লিক রেট (CTR)" data-en="Click-Through Rate (CTR)">ক্লিক রেট (CTR)</h4>
        <div class="metric-num"><span data-bn="<?= \App\Helpers\BanglaDate::bnNum($stCtr) ?>%" data-en="<?= $stCtr ?>%"><?= \App\Helpers\BanglaDate::bnNum($stCtr) ?>%</span></div>
      </div>
    </div>
  </div>

  <!-- Slot Placement Visual Guide -->
  <div class="slots-guide-banner">
    <div>
      <strong style="color:var(--adm-text,#f1f5f9);display:block;margin-bottom:4px;" data-bn="📍 পোর্টালের নির্ধারিত বিজ্ঞাপন স্লট ও মাপ নির্দেশিকা:" data-en="📍 Designated Portal Ad Slots & Dimensions Guide:">📍 পোর্টালের নির্ধারিত বিজ্ঞাপন স্লট ও মাপ নির্দেশিকা:</strong>
      <span style="color:var(--adm-text-muted,#94a3b8);font-size:0.85rem;" data-bn="প্রতিটি স্লটের জন্য সঠিক মাপের বিজ্ঞাপন দিলে সাইটের সৌন্দর্য অক্ষুণ্ণ থাকে" data-en="Providing correct ad dimensions for each slot preserves portal elegance">প্রতিটি স্লটের জন্য সঠিক মাপের বিজ্ঞাপন দিলে সাইটের সৌন্দর্য অক্ষুণ্ণ থাকে</span>
    </div>
    <div class="slots-chips">
      <span class="slot-chip"><span data-bn="🔝 হেডার:" data-en="🔝 Header:">🔝 হেডার:</span> <strong data-bn="৭২৮ × ৯০" data-en="728 × 90">৭২৮ × ৯০</strong></span>
      <span class="slot-chip"><span data-bn="📑 ইন-ফিড / ইন-আর্টিকেল:" data-en="📑 In-Feed / In-Article:">📑 ইন-ফিড / ইন-আর্টিকেল:</span> <strong data-bn="৭২৮ × ৯০ / ৯৭০ × ৯০" data-en="728 × 90 / 970 × 90">৭২৮ × ৯০ / ৯৭০ × ৯০</strong></span>
      <span class="slot-chip"><span data-bn="📌 সাইডবার:" data-en="📌 Sidebar:">📌 সাইডবার:</span> <strong data-bn="৩০০ × ২৫০" data-en="300 × 250">৩০০ × ২৫০</strong></span>
      <span class="slot-chip"><span data-bn="🔻 ফুটার:" data-en="🔻 Footer:">🔻 ফুটার:</span> <strong data-bn="৯৭০ × ৯০" data-en="970 × 90">৯৭০ × ৯০</strong></span>
    </div>
  </div>

  <!-- Create New Ad Form -->
  <div class="create-card">
    <div class="card-title-head">
      <h3 data-bn="➕ নতুন বিজ্ঞাপন যোগ করুন (Add Advertisement)" data-en="➕ Add New Advertisement">➕ নতুন বিজ্ঞাপন যোগ করুন (Add Advertisement)</h3>
      <span style="font-size:0.85rem;color:var(--adm-text-muted,#94a3b8);" data-bn="ছবি, ভিডিও বা কাস্টম এইচটিএমএল/অ্যাডসেন্স কোড" data-en="Picture, video or custom HTML/AdSense code">ছবি, ভিডিও বা কাস্টম এইচটিএমএল/অ্যাডসেন্স কোড</span>
    </div>

    <form action="<?= $appUrl ?>/<?= $adminPath ?>/ads/store" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

      <div class="form-grid-2">
        <div class="form-group">
          <label for="title" data-bn="বিজ্ঞাপনের শিরোনাম / ব্র্যান্ডের নাম *" data-en="Ad Headline / Brand Name *">বিজ্ঞাপনের শিরোনাম / ব্র্যান্ডের নাম *</label>
          <input type="text" id="title" name="title" required placeholder="যেমন: গ্রামীণফোন স্পেশাল ইন্টারনেট প্যাক" data-placeholder-bn="যেমন: গ্রামীণফোন স্পেশাল ইন্টারনেট প্যাক" data-placeholder-en="e.g. Special Internet Pack" class="form-control">
        </div>
        <div class="form-group">
          <label for="position" data-bn="বিজ্ঞাপনের স্থান (Placement Slot) *" data-en="Ad Placement Slot *">বিজ্ঞাপনের স্থান (Placement Slot) *</label>
          <select id="position" name="position" required class="form-control">
            <option value="header" data-bn="🔝 হেডার ব্যানার (Header — 728 × 90)" data-en="🔝 Header Banner (728 × 90)">🔝 হেডার ব্যানার (Header — 728 × 90)</option>
            <option value="sidebar" data-bn="📌 সাইডবার ব্যানার (Sidebar — 300 × 250)" data-en="📌 Sidebar Banner (300 × 250)">📌 সাইডবার ব্যানার (Sidebar — 300 × 250)</option>
            <option value="in_article" data-bn="📑 ইন-ফিড / খবরের মাঝে (In-Article — 728 × 90)" data-en="📑 In-Feed / In-Article (728 × 90)">📑 ইন-ফিড / খবরের মাঝে (In-Article — 728 × 90)</option>
            <option value="footer" data-bn="🔻 ফুটার ব্যানার (Footer — 970 × 90)" data-en="🔻 Footer Banner (970 × 90)">🔻 ফুটার ব্যানার (Footer — 970 × 90)</option>
          </select>
        </div>
      </div>

      <div class="form-grid-2">
        <div class="form-group">
          <label for="ad_type" data-bn="বিজ্ঞাপনের ধরণ (Ad Type) *" data-en="Ad Type *">বিজ্ঞাপনের ধরণ (Ad Type) *</label>
          <select id="ad_type" name="type" required class="form-control" onchange="handleAdTypeSwitch(this.value, 'create')">
            <option value="image" data-bn="🖼️ ছবি / ব্যানার (Picture Banner)" data-en="🖼️ Picture / Banner">🖼️ ছবি / ব্যানার (Picture Banner)</option>
            <option value="video" data-bn="🎬 ভিডিও বিজ্ঞাপন (YouTube / MP4 Video)" data-en="🎬 Video Ad (YouTube / MP4)">🎬 ভিডিও বিজ্ঞাপন (YouTube / MP4 Video)</option>
            <option value="code" data-bn="💻 কাস্টম স্ক্রিপ্ট / গুগল অ্যাডসেন্স (HTML/JS Code)" data-en="💻 Custom Script / Google AdSense (HTML/JS)">💻 কাস্টম স্ক্রিপ্ট / গুগল অ্যাডসেন্স (HTML/JS Code)</option>
          </select>
        </div>
        <div class="form-group" id="link_group_create">
          <label for="link" data-bn="বিজ্ঞাপনের ক্লিক লিংক / হাইপারলিংক (Destination URL) *" data-en="Ad Destination URL *">বিজ্ঞাপনের ক্লিক লিংক / হাইপারলিংক (Destination URL) *</label>
          <input type="url" id="link" name="link" placeholder="https://example.com/promo" class="form-control">
          <small style="color:var(--adm-text-muted,#94a3b8);font-size:0.8rem;" data-bn="পাঠক বিজ্ঞাপনে ক্লিক করলে এই ঠিকানায় নিয়ে যাবে (অটোমেটিক ক্লিক ট্র্যাক হবে)" data-en="Readers clicking the ad will be redirected to this link (clicks auto-tracked)">পাঠক বিজ্ঞাপনে ক্লিক করলে এই ঠিকানায় নিয়ে যাবে (অটোমেটিক ক্লিক ট্র্যাক হবে)</small>
        </div>
      </div>

      <!-- Image Fields -->
      <div id="image_fields_create">
        <div class="form-grid-2">
          <div class="form-group">
            <label for="image_file" data-bn="ছবি ফাইল আপলোড (PNG/JPG/WebP/GIF):" data-en="Upload Image File (PNG/JPG/WebP/GIF):">ছবি ফাইল আপলোড (PNG/JPG/WebP/GIF):</label>
            <input type="file" id="image_file" name="image_file" accept="image/*" class="form-control" onchange="previewUploadImage(this, 'create_preview')">
          </div>
          <div class="form-group">
            <label for="image_url" data-bn="অথবা ছবির সরাসরি অনলাইন URL:" data-en="Or Direct Online Image URL:">অথবা ছবির সরাসরি অনলাইন URL:</label>
            <input type="text" id="image_url" name="image_url" placeholder="https://images.unsplash.com/..." class="form-control" oninput="previewUrlImage(this.value, 'create_preview')">
          </div>
        </div>
        <div id="create_preview" class="live-preview-box">
          <img src="" alt="Ad Preview" id="create_preview_img">
        </div>
      </div>

      <!-- Video Fields -->
      <div id="video_fields_create" style="display:none;">
        <div class="form-group">
          <label for="video_url" data-bn="বিজ্ঞাপন ভিডিও লিংক (YouTube URL অথবা MP4 ফাইল লিংক):" data-en="Ad Video Link (YouTube URL or MP4 Link):">বিজ্ঞাপন ভিডিও লিংক (YouTube URL অথবা MP4 ফাইল লিংক):</label>
          <input type="url" id="video_url" name="video_url" placeholder="https://www.youtube.com/watch?v=..." class="form-control">
          <small style="color:var(--adm-text-muted,#94a3b8);font-size:0.8rem;" data-bn="ইউটিউব লিংক দিলে স্বয়ংক্রিয়ভাবে রেসপন্সিভ প্লেয়ার তৈরি হবে" data-en="Providing a YouTube URL automatically generates a responsive video player">ইউটিউব লিংক দিলে স্বয়ংক্রিয়ভাবে রেসপন্সিভ প্লেয়ার তৈরি হবে</small>
        </div>
      </div>

      <!-- Code Fields -->
      <div id="code_fields_create" style="display:none;">
        <div class="form-group">
          <label for="code" data-bn="বিজ্ঞাপনের কোড (Google AdSense Script / Banner HTML):" data-en="Ad Code (Google AdSense Script / Banner HTML):">বিজ্ঞাপনের কোড (Google AdSense Script / Banner HTML):</label>
          <textarea id="code" name="code" rows="4" placeholder="<script async src='...'></script>" class="form-control"></textarea>
          <small style="color:var(--adm-text-muted,#94a3b8);font-size:0.8rem;" data-bn="গুগল অ্যাডসেন্স বা কোনো পার্টনার নেটওয়ার্কের সরাসরি স্ক্রিপ্ট বা কোড এখানে দিন" data-en="Enter Google AdSense or direct partner network script / HTML code here">গুগল অ্যাডসেন্স বা কোনো পার্টনার নেটওয়ার্কের সরাসরি স্ক্রিপ্ট বা কোড এখানে দিন</small>
        </div>
      </div>

      <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-top:14px;padding-top:14px;border-top:1px solid var(--adm-border,#173567);">
        <label style="display:inline-flex;align-items:center;gap:8px;cursor:pointer;color:var(--adm-text,#f1f5f9);font-weight:600;font-size:0.92rem;">
          <input type="checkbox" name="is_active" value="1" checked style="width:18px;height:18px;accent-color:#0284c7;">
          <span data-bn="সরাসরি সাইটে প্রদর্শন সক্রিয় রাখুন (Active)" data-en="Keep ad actively displayed on site (Active)">সরাসরি সাইটে প্রদর্শন সক্রিয় রাখুন (Active)</span>
        </label>
        <button type="submit" class="btn-submit" data-bn="🚀 বিজ্ঞাপন প্রকাশ করুন" data-en="🚀 Publish Advertisement">🚀 বিজ্ঞাপন প্রকাশ করুন</button>
      </div>
    </form>
  </div>

  <!-- Ads List Table -->
  <div class="card-title-head" style="margin-bottom:14px;">
    <h3 style="color:var(--adm-text,#f1f5f9);font-size:1.3rem;" data-bn="📋 সকল বিজ্ঞাপন ও লাইভ স্ট্যাটাস" data-en="📋 All Advertisements & Live Status">📋 সকল বিজ্ঞাপন ও লাইভ স্ট্যাটাস</h3>
  </div>

  <div class="ads-table-card">
    <?php if (!empty($ads)): ?>
      <table>
        <thead>
          <tr>
            <th data-bn="#ID" data-en="#ID">#ID</th>
            <th data-bn="প্রিভিউ" data-en="Preview">প্রিভিউ</th>
            <th data-bn="শিরোনাম" data-en="Headline">শিরোনাম</th>
            <th data-bn="স্থান (Position)" data-en="Placement Slot">স্থান (Position)</th>
            <th data-bn="ধরণ" data-en="Type">ধরণ</th>
            <th data-bn="গন্তব্য লিংক (URL)" data-en="Destination URL">গন্তব্য লিংক (URL)</th>
            <th data-bn="অ্যানালিটিক্স (Views / Clicks)" data-en="Analytics (Views / Clicks)">অ্যানালিটিক্স (Views / Clicks)</th>
            <th data-bn="স্ট্যাটাস" data-en="Status">স্ট্যাটাস</th>
            <th data-bn="অ্যাকশন" data-en="Actions">অ্যাকশন</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($ads as $ad): 
            $posClass = match($ad['position']) {
              'header' => 'pos-header',
              'sidebar' => 'pos-sidebar',
              'in_article' => 'pos-in_article',
              'footer' => 'pos-footer',
              default => 'pos-header'
            };
            $posNameBn = match($ad['position']) {
              'header' => '🔝 হেডার (728x90)',
              'sidebar' => '📌 সাইডবার (300x250)',
              'in_article' => '📑 ইন-ফিড / খবর (728x90)',
              'footer' => '🔻 ফুটার (970x90)',
              default => $ad['position']
            };
            $posNameEn = match($ad['position']) {
              'header' => '🔝 Header (728x90)',
              'sidebar' => '📌 Sidebar (300x250)',
              'in_article' => '📑 In-Feed (728x90)',
              'footer' => '🔻 Footer (970x90)',
              default => $ad['position']
            };
            $typeNameBn = match($ad['type']) {
              'video' => '🎬 ভিডিও',
              'code' => '💻 কোড',
              default => '🖼️ ছবি'
            };
            $typeNameEn = match($ad['type']) {
              'video' => '🎬 Video',
              'code' => '💻 Code',
              default => '🖼️ Image'
            };
            $impr = (int) ($ad['impressions'] ?? 0);
            $clk = (int) ($ad['clicks'] ?? 0);
            $ctrRate = $impr > 0 ? round(($clk / $impr) * 100, 1) : 0.0;
          ?>
            <tr>
              <td><span style="font-weight:700;color:var(--adm-text-muted,#94a3b8);"><?= $ad['id'] ?></span></td>
              <td>
                <?php if ($ad['type'] === 'video' && !empty($ad['video_url'])): ?>
                  <span style="font-size:1.5rem;" title="ভিডিও বিজ্ঞাপন">🎬</span>
                <?php elseif ($ad['type'] === 'code'): ?>
                  <span style="font-size:1.3rem;" title="কোড / স্ক্রিপ্ট বিজ্ঞাপন">💻</span>
                <?php elseif (!empty($ad['image'])): ?>
                  <img src="<?= htmlspecialchars((strpos($ad['image'], 'http') === 0 ? '' : $appUrl . '/') . ltrim($ad['image'], '/')) ?>" alt="Ad" class="ad-thumb">
                <?php else: ?>
                  <span style="color:var(--adm-text-muted,#94a3b8);font-size:0.8rem;" data-bn="মিডিয়া নেই" data-en="No Media">মিডিয়া নেই</span>
                <?php endif; ?>
              </td>
              <td>
                <strong style="display:block;color:var(--adm-text,#f1f5f9);font-size:0.95rem;"><?= htmlspecialchars($ad['title']) ?></strong>
                <span style="color:var(--adm-text-muted,#94a3b8);font-size:0.78rem;">
                  <span data-bn="যোগ করা হয়েছে:" data-en="Created:">যোগ করা হয়েছে:</span> <?= date('d M, Y', strtotime($ad['created_at'])) ?>
                </span>
              </td>
              <td>
                <span class="badge-pos <?= $posClass ?>" data-bn="<?= $posNameBn ?>" data-en="<?= $posNameEn ?>">
                  <?= $posNameBn ?>
                </span>
              </td>
              <td>
                <span class="badge-type" data-bn="<?= $typeNameBn ?>" data-en="<?= $typeNameEn ?>"><?= $typeNameBn ?></span>
              </td>
              <td>
                <?php if (!empty($ad['link'])): ?>
                  <a href="<?= htmlspecialchars($ad['link']) ?>" target="_blank" rel="noopener noreferrer" class="hyperlink-preview" title="ক্লিক করে পেজটি টেস্ট করুন">
                    🔗 <?= htmlspecialchars(mb_strimwidth($ad['link'], 0, 26, '...')) ?>
                  </a>
                <?php else: ?>
                  <span style="color:var(--adm-text-muted,#94a3b8);font-size:0.82rem;" data-bn="কোনো লিংক নেই" data-en="No Link">কোনো লিংক নেই</span>
                <?php endif; ?>
              </td>
              <td>
                <span class="stat-pill" title="মোট প্রদর্শন">👁️ <strong data-bn="<?= \App\Helpers\BanglaDate::bnNum($impr) ?>" data-en="<?= $impr ?>"><?= \App\Helpers\BanglaDate::bnNum($impr) ?></strong></span>
                <span class="stat-pill" title="মোট ক্লিক">🖱️ <strong data-bn="<?= \App\Helpers\BanglaDate::bnNum($clk) ?>" data-en="<?= $clk ?>"><?= \App\Helpers\BanglaDate::bnNum($clk) ?></strong></span>
                <span class="stat-pill" title="ক্লিক-থ্রু রেট">📈 <strong data-bn="<?= \App\Helpers\BanglaDate::bnNum($ctrRate) ?>%" data-en="<?= $ctrRate ?>%"><?= \App\Helpers\BanglaDate::bnNum($ctrRate) ?>%</strong></span>
              </td>
              <td>
                <?php if (!empty($ad['is_active'])): ?>
                  <span style="color:#22c55e;font-weight:700;display:inline-flex;align-items:center;gap:4px;" data-bn="🟢 সক্রিয়" data-en="🟢 Active">🟢 সক্রিয়</span>
                <?php else: ?>
                  <span style="color:#94a3b8;font-weight:600;display:inline-flex;align-items:center;gap:4px;" data-bn="⚪ বন্ধ" data-en="⚪ Inactive">⚪ বন্ধ</span>
                <?php endif; ?>
              </td>
              <td>
                <div style="display:flex;gap:6px;align-items:center;">
                  <!-- Toggle Active -->
                  <form action="<?= $appUrl ?>/<?= $adminPath ?>/ads/toggle" method="POST" style="display:inline;">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                    <input type="hidden" name="id" value="<?= $ad['id'] ?>">
                    <button type="submit" class="btn-sm-action btn-toggle" title="স্ট্যাটাস পরিবর্তন" data-bn="<?= !empty($ad['is_active']) ? 'বন্ধ করুন' : 'চালু করুন' ?>" data-en="<?= !empty($ad['is_active']) ? 'Pause' : 'Activate' ?>">
                      <?= !empty($ad['is_active']) ? 'বন্ধ করুন' : 'চালু করুন' ?>
                    </button>
                  </form>
                  
                  <!-- Quick Edit -->
                  <button type="button" class="btn-sm-action btn-edit" onclick='openEditModal(<?= json_encode($ad, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' title="এডিট করুন" data-bn="✏️ এডিট" data-en="✏️ Edit">
                    ✏️ এডিট
                  </button>

                  <!-- Delete -->
                  <form action="<?= $appUrl ?>/<?= $adminPath ?>/ads/delete" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই বিজ্ঞাপনটি সম্পূর্ণ মুছে ফেলতে চান?');" style="display:inline;">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                    <input type="hidden" name="id" value="<?= $ad['id'] ?>">
                    <button type="submit" class="btn-sm-action btn-del" title="মুছে ফেলুন" data-title-bn="মুছে ফেলুন" data-title-en="Delete">🗑️</button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p style="text-align:center;padding:40px;color:var(--adm-text-muted,#94a3b8);font-size:1rem;" data-bn="আপাতত কোনো বিজ্ঞাপন তৈরি করা নেই। ওপরের ফরম দিয়ে নতুন বিজ্ঞাপন যোগ করুন।" data-en="No advertisements created yet. Use the form above to add a new advertisement.">
        আপাতত কোনো বিজ্ঞাপন তৈরি করা নেই। ওপরের ফরম দিয়ে নতুন বিজ্ঞাপন যোগ করুন।
      </p>
    <?php endif; ?>
  </div>
</main>

<!-- Edit Advertisement Modal -->
<div class="modal-overlay" id="edit_ad_modal" onclick="closeEditModalOnBackdrop(event)">
  <div class="modal-box">
    <div class="card-title-head">
      <h3 data-bn="✏️ বিজ্ঞাপন এডিট করুন (Edit Advertisement)" data-en="✏️ Edit Advertisement">✏️ বিজ্ঞাপন এডিট করুন (Edit Advertisement)</h3>
      <button type="button" onclick="closeEditModal()" style="background:none;border:none;color:var(--adm-text,#f1f5f9);font-size:1.4rem;cursor:pointer;">✕</button>
    </div>

    <form action="<?= $appUrl ?>/<?= $adminPath ?>/ads/update" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
      <input type="hidden" name="id" id="edit_id" value="">

      <div class="form-grid-2">
        <div class="form-group">
          <label for="edit_title" data-bn="বিজ্ঞাপনের শিরোনাম *" data-en="Ad Headline *">বিজ্ঞাপনের শিরোনাম *</label>
          <input type="text" id="edit_title" name="title" required class="form-control">
        </div>
        <div class="form-group">
          <label for="edit_position" data-bn="বিজ্ঞাপনের স্থান *" data-en="Ad Placement *">বিজ্ঞাপনের স্থান *</label>
          <select id="edit_position" name="position" required class="form-control">
            <option value="header" data-bn="🔝 হেডার ব্যানার (Header — 728 × 90)" data-en="🔝 Header Banner (728 × 90)">🔝 হেডার ব্যানার (Header — 728 × 90)</option>
            <option value="sidebar" data-bn="📌 সাইডবার ব্যানার (Sidebar — 300 × 250)" data-en="📌 Sidebar Banner (300 × 250)">📌 সাইডবার ব্যানার (Sidebar — 300 × 250)</option>
            <option value="in_article" data-bn="📑 ইন-ফিড / খবরের মাঝে (In-Article — 728 × 90)" data-en="📑 In-Feed / In-Article (728 × 90)">📑 ইন-ফিড / খবরের মাঝে (In-Article — 728 × 90)</option>
            <option value="footer" data-bn="🔻 ফুটার ব্যানার (Footer — 970 × 90)" data-en="🔻 Footer Banner (970 × 90)">🔻 ফুটার ব্যানার (Footer — 970 × 90)</option>
          </select>
        </div>
      </div>

      <div class="form-grid-2">
        <div class="form-group">
          <label for="edit_type" data-bn="বিজ্ঞাপনের ধরণ *" data-en="Ad Type *">বিজ্ঞাপনের ধরণ *</label>
          <select id="edit_type" name="type" required class="form-control" onchange="handleAdTypeSwitch(this.value, 'edit')">
            <option value="image" data-bn="🖼️ ছবি / ব্যানার (Picture Banner)" data-en="🖼️ Picture / Banner">🖼️ ছবি / ব্যানার (Picture Banner)</option>
            <option value="video" data-bn="🎬 ভিডিও বিজ্ঞাপন (YouTube / MP4 Video)" data-en="🎬 Video Ad (YouTube / MP4)">🎬 ভিডিও বিজ্ঞাপন (YouTube / MP4 Video)</option>
            <option value="code" data-bn="💻 কাস্টম স্ক্রিপ্ট / গুগল অ্যাডসেন্স (HTML/JS Code)" data-en="💻 Custom Script / Google AdSense (HTML/JS)">💻 কাস্টম স্ক্রিপ্ট / গুগল অ্যাডসেন্স (HTML/JS Code)</option>
          </select>
        </div>
        <div class="form-group" id="link_group_edit">
          <label for="edit_link" data-bn="বিজ্ঞাপনের ক্লিক লিংক (Destination URL)" data-en="Ad Destination URL">বিজ্ঞাপনের ক্লিক লিংক (Destination URL)</label>
          <input type="url" id="edit_link" name="link" class="form-control">
        </div>
      </div>

      <!-- Image Fields -->
      <div id="image_fields_edit">
        <div class="form-grid-2">
          <div class="form-group">
            <label for="edit_image_file" data-bn="নতুন ছবি ফাইল আপলোড (ঐচ্ছিক):" data-en="Upload New Image File (Optional):">নতুন ছবি ফাইল আপলোড (ঐচ্ছিক):</label>
            <input type="file" id="edit_image_file" name="image_file" accept="image/*" class="form-control" onchange="previewUploadImage(this, 'edit_preview')">
          </div>
          <div class="form-group">
            <label for="edit_image_url" data-bn="অথবা ছবির অনলাইন URL:" data-en="Or Online Image URL:">অথবা ছবির অনলাইন URL:</label>
            <input type="text" id="edit_image_url" name="image_url" class="form-control" oninput="previewUrlImage(this.value, 'edit_preview')">
          </div>
        </div>
        <div id="edit_preview" class="live-preview-box">
          <img src="" alt="Ad Preview" id="edit_preview_img">
        </div>
      </div>

      <!-- Video Fields -->
      <div id="video_fields_edit" style="display:none;">
        <div class="form-group">
          <label for="edit_video_url" data-bn="বিজ্ঞাপন ভিডিও লিংক (YouTube URL / MP4):" data-en="Ad Video URL (YouTube / MP4):">বিজ্ঞাপন ভিডিও লিংক (YouTube URL / MP4):</label>
          <input type="url" id="edit_video_url" name="video_url" class="form-control">
        </div>
      </div>

      <!-- Code Fields -->
      <div id="code_fields_edit" style="display:none;">
        <div class="form-group">
          <label for="edit_code" data-bn="বিজ্ঞাপনের কোড (HTML/JS Code):" data-en="Ad Code (HTML/JS Code):">বিজ্ঞাপনের কোড (HTML/JS Code):</label>
          <textarea id="edit_code" name="code" rows="4" class="form-control"></textarea>
        </div>
      </div>

      <div style="display:flex;justify-content:space-between;align-items:center;margin-top:20px;padding-top:16px;border-top:1px solid var(--adm-border,#173567);">
        <label style="display:inline-flex;align-items:center;gap:8px;cursor:pointer;color:var(--adm-text,#f1f5f9);font-weight:600;">
          <input type="checkbox" name="is_active" id="edit_is_active" value="1" style="width:18px;height:18px;accent-color:#0284c7;">
          <span data-bn="সক্রিয় রাখুন (Active)" data-en="Keep Active">সক্রিয় রাখুন (Active)</span>
        </label>
        <div style="display:flex;gap:10px;">
          <button type="button" class="btn-sm-action" onclick="closeEditModal()" style="background:#475569;color:#fff;" data-bn="বাতিল" data-en="Cancel">বাতিল</button>
          <button type="submit" class="btn-submit" data-bn="💾 সংরক্ষণ করুন" data-en="💾 Save Changes">💾 সংরক্ষণ করুন</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
function handleAdTypeSwitch(type, prefix) {
  const imgBox = document.getElementById('image_fields_' + prefix);
  const vidBox = document.getElementById('video_fields_' + prefix);
  const codeBox = document.getElementById('code_fields_' + prefix);

  imgBox.style.display = (type === 'image') ? 'block' : 'none';
  vidBox.style.display = (type === 'video') ? 'block' : 'none';
  codeBox.style.display = (type === 'code') ? 'block' : 'none';
}

function previewUploadImage(input, previewBoxId) {
  const box = document.getElementById(previewBoxId);
  const img = box.querySelector('img');
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function(e) {
      img.src = e.target.result;
      box.style.display = 'block';
    }
    reader.readAsDataURL(input.files[0]);
  }
}

function previewUrlImage(url, previewBoxId) {
  const box = document.getElementById(previewBoxId);
  const img = box.querySelector('img');
  if (url && (url.startsWith('http://') || url.startsWith('https://'))) {
    img.src = url;
    box.style.display = 'block';
  } else {
    box.style.display = 'none';
  }
}

function openEditModal(ad) {
  document.getElementById('edit_id').value = ad.id;
  document.getElementById('edit_title').value = ad.title || '';
  document.getElementById('edit_position').value = ad.position || 'header';
  document.getElementById('edit_type').value = ad.type || 'image';
  document.getElementById('edit_link').value = ad.link || '';
  document.getElementById('edit_image_url').value = ad.image || '';
  document.getElementById('edit_video_url').value = ad.video_url || '';
  document.getElementById('edit_code').value = ad.code || '';
  document.getElementById('edit_is_active').checked = (parseInt(ad.is_active) === 1);

  handleAdTypeSwitch(ad.type || 'image', 'edit');

  const editBox = document.getElementById('edit_preview');
  const editImg = document.getElementById('edit_preview_img');
  if (ad.image) {
    const src = ad.image.startsWith('http') ? ad.image : '<?= $appUrl ?>/' + ad.image;
    editImg.src = src;
    editBox.style.display = 'block';
  } else {
    editBox.style.display = 'none';
  }

  document.getElementById('edit_ad_modal').style.display = 'flex';
}

function closeEditModal() {
  document.getElementById('edit_ad_modal').style.display = 'none';
}

function closeEditModalOnBackdrop(e) {
  if (e.target.id === 'edit_ad_modal') {
    closeEditModal();
  }
}
</script>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>

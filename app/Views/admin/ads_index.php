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
    .admin-container { max-width: 1160px; margin: 30px auto; padding: 0 20px; }
    .page-title-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .create-card { background: var(--adm-card-bg); border-radius: 8px; border: 1px solid var(--adm-border); padding: 24px; margin-bottom: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
    @media (max-width: 640px) { .form-grid-2 { grid-template-columns: 1fr; } }
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; font-weight: 600; margin-bottom: 6px; color: var(--adm-text); font-size: 0.92rem; }
    .form-control { width: 100%; padding: 10px 14px; background: var(--adm-nav-bg); border: 1px solid var(--adm-border); border-radius: 6px; font-size: 0.95rem; color: var(--adm-text); }
    .form-control:focus { border-color: var(--adm-primary); outline: none; }
    .btn-submit { background: var(--adm-primary); color: #fff; padding: 10px 24px; border-radius: 6px; border: none; font-weight: 700; font-size: 0.95rem; cursor: pointer; }
    .btn-submit:hover { background: var(--adm-primary-hover); }
    .ads-table-card { background: var(--adm-card-bg); border-radius: 8px; border: 1px solid var(--adm-border); padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; font-size: 0.92rem; }
    th { padding: 12px; border-bottom: 2px solid var(--adm-border); color: var(--adm-text-muted); font-weight: 600; text-align: left; }
    td { padding: 12px; border-bottom: 1px solid var(--adm-border); color: var(--adm-text); vertical-align: middle; }
    .badge-pos { background: rgba(56, 189, 248, 0.15); color: #38bdf8; padding: 3px 8px; border-radius: 4px; font-weight: 600; font-size: 0.8rem; }
    .badge-type { background: rgba(14, 122, 58, 0.15); color: #4ade80; padding: 3px 8px; border-radius: 4px; font-weight: 600; font-size: 0.8rem; }
    .ad-thumb { max-height: 50px; max-width: 120px; object-fit: contain; border-radius: 4px; border: 1px solid var(--adm-border); background: #ffffff; padding: 2px; }
    .btn-sm-action { border: none; padding: 4px 10px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; }
    .btn-toggle { background: #0284c7; color: #fff; }
    .btn-toggle:hover { background: #0369a1; }
    .btn-del { background: #ef4444; color: #fff; }
    .btn-del:hover { background: #dc2626; }
    .alert-banner { background: #064e3b; border: 1px solid #059669; color: #a7f3d0; padding: 12px 18px; border-radius: 6px; margin-bottom: 20px; }
    .hyperlink-preview { color: var(--adm-accent); text-decoration: none; word-break: break-all; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 4px; }
    .hyperlink-preview:hover { text-decoration: underline; }
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
      <?php 
        if ($_GET['msg'] === 'created') echo '✅ নতুন বিজ্ঞাপন সফলভাবে প্রকাশ করা হয়েছে!';
        if ($_GET['msg'] === 'toggled') echo '✅ বিজ্ঞাপনের সক্রিয় স্ট্যাটাস সফলভাবে পরিবর্তন করা হয়েছে!';
        if ($_GET['msg'] === 'deleted') echo '✅ বিজ্ঞাপনটি সফলভাবে ডাটাবেজ থেকে মুছে ফেলা হয়েছে!';
      ?>
    </div>
  <?php endif; ?>

  <div class="page-title-row">
    <div>
      <h2>📢 বিজ্ঞাপন ব্যবস্থাপনা (Advertisements Control Panel)</h2>
      <p style="color:var(--adm-text-muted);font-size:0.9rem;">ছবি (ব্যানার) ও ভিডিও বিজ্ঞাপন যোগ করুন, হাইপারলিংক সেট করুন এবং পোর্টালের বিভিন্ন স্থানে প্রদর্শন পরিচালনা করুন</p>
    </div>
  </div>

  <!-- Create New Ad Form (FEATURE: Admin Control over Ads, Picture/Video, Hyperlinks) -->
  <div class="create-card">
    <h3 style="margin-bottom:16px;color:var(--adm-text);display:flex;align-items:center;gap:8px;">
      ➕ নতুন বিজ্ঞাপন যোগ করুন (Add Advertisement)
    </h3>
    <form action="<?= $appUrl ?>/<?= $adminPath ?>/ads/store" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

      <div class="form-grid-2">
        <div class="form-group">
          <label for="title">বিজ্ঞাপনের শিরোনাম / ব্র্যান্ডের নাম *</label>
          <input type="text" id="title" name="title" required placeholder="যেমন: গ্রামীণফোন স্পেশাল ইন্টারনেট প্যাক" class="form-control">
        </div>
        <div class="form-group">
          <label for="position">বিজ্ঞাপনের স্থান (Position) *</label>
          <select id="position" name="position" required class="form-control">
            <option value="header">হেডার ব্যানার (Header Slot — 728 × 90)</option>
            <option value="sidebar">সাইডবার ব্যানার (Sidebar Slot — 300 × 250)</option>
            <option value="in_article">খবরের মাঝখানে (In Article Banner)</option>
            <option value="footer">ফুটার ব্যানার (Footer Slot)</option>
          </select>
        </div>
      </div>

      <div class="form-grid-2">
        <div class="form-group">
          <label for="ad_type">বিজ্ঞাপনের ধরণ (Type) *</label>
          <select id="ad_type" name="type" required class="form-control" onchange="toggleAdFields(this.value)">
            <option value="image">ছবি / ব্যানার (Picture Banner)</option>
            <option value="video">ভিডিও বিজ্ঞাপন (Video Ad)</option>
          </select>
        </div>
        <div class="form-group">
          <label for="link">বিজ্ঞাপনের ক্লিক লিংক / হাইপারলিংক (Hyperlink) *</label>
          <input type="url" id="link" name="link" required placeholder="https://example.com/details" class="form-control">
          <small style="color:var(--adm-text-muted);font-size:0.8rem;">পাঠক বিজ্ঞাপনে ক্লিক করলে এই ঠিকানায় বিস্তারিত দেখতে পারবেন</small>
        </div>
      </div>

      <!-- Image Fields -->
      <div id="image_fields_box">
        <div class="form-grid-2">
          <div class="form-group">
            <label for="image_file">বিজ্ঞাপনের ছবি আপলোড (PNG/JPG/WebP/GIF):</label>
            <input type="file" id="image_file" name="image_file" accept="image/*" class="form-control">
          </div>
          <div class="form-group">
            <label for="image_url">অথবা ছবির সরাসরি URL:</label>
            <input type="text" id="image_url" name="image_url" placeholder="https://..." class="form-control">
          </div>
        </div>
      </div>

      <!-- Video Fields -->
      <div id="video_fields_box" style="display:none;">
        <div class="form-group">
          <label for="video_url">বিজ্ঞাপন ভিডিও লিংক (YouTube / MP4 URL):</label>
          <input type="url" id="video_url" name="video_url" placeholder="https://www.youtube.com/watch?v=..." class="form-control">
          <small style="color:var(--adm-text-muted);font-size:0.8rem;">ভিডিও বিজ্ঞাপনের ক্ষেত্রে ইউটিউব বা সরাসরি ভিডিও ফাইল লিংক দিন</small>
        </div>
      </div>

      <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-top:14px;">
        <label style="display:inline-flex;align-items:center;gap:8px;cursor:pointer;color:var(--adm-text);font-weight:600;font-size:0.92rem;">
          <input type="checkbox" name="is_active" value="1" checked>
          <span>সরাসরি সাইটে প্রদর্শন সক্রিয় রাখুন (Active)</span>
        </label>
        <button type="submit" class="btn-submit">🚀 বিজ্ঞাপন প্রকাশ করুন</button>
      </div>
    </form>
  </div>

  <!-- Ads List -->
  <h3 style="margin-bottom:16px;color:var(--adm-text);">📋 চলমান ও সংরক্ষিত বিজ্ঞাপনের তালিকা</h3>

  <div class="ads-table-card">
    <?php if (!empty($ads)): ?>
      <table>
        <thead>
          <tr>
            <th>#ID</th>
            <th>প্রিভিউ</th>
            <th>শিরোনাম</th>
            <th>স্থান</th>
            <th>ধরণ</th>
            <th>হাইপারলিংক (Destination)</th>
            <th>স্ট্যাটাস</th>
            <th>অ্যাকশন</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($ads as $ad): ?>
            <tr>
              <td><?= $ad['id'] ?></td>
              <td>
                <?php if ($ad['type'] === 'video' && !empty($ad['video_url'])): ?>
                  <span style="font-size:1.4rem;" title="ভিডিও বিজ্ঞাপন">🎬</span>
                <?php elseif (!empty($ad['image'])): ?>
                  <img src="<?= htmlspecialchars($ad['image']) ?>" alt="Ad" class="ad-thumb">
                <?php else: ?>
                  <span style="color:var(--adm-text-muted);font-size:0.8rem;">কোনো ছবি নেই</span>
                <?php endif; ?>
              </td>
              <td><strong><?= htmlspecialchars($ad['title']) ?></strong></td>
              <td>
                <span class="badge-pos">
                  <?php 
                    $posMap = ['header' => 'হেডার (728x90)', 'sidebar' => 'সাইডবার (300x250)', 'in_article' => 'ইন-আর্টিকেল', 'footer' => 'ফুটার'];
                    echo $posMap[$ad['position']] ?? htmlspecialchars($ad['position']);
                  ?>
                </span>
              </td>
              <td>
                <span class="badge-type">
                  <?= $ad['type'] === 'video' ? 'ভিডিও' : 'ছবি/ব্যানার' ?>
                </span>
              </td>
              <td>
                <?php if (!empty($ad['link'])): ?>
                  <a href="<?= htmlspecialchars($ad['link']) ?>" target="_blank" rel="noopener" class="hyperlink-preview" title="ক্লিক করে পেজটি দেখুন">
                    🔗 <?= htmlspecialchars(mb_strimwidth($ad['link'], 0, 32, '...')) ?>
                  </a>
                <?php else: ?>
                  <span style="color:var(--adm-text-muted);font-size:0.85rem;">কোনো লিংক নেই</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if (!empty($ad['is_active'])): ?>
                  <span style="color:#22c55e;font-weight:700;">🟢 সক্রিয়</span>
                <?php else: ?>
                  <span style="color:#94a3b8;font-weight:600;">⚪ নিষ্ক্রিয়</span>
                <?php endif; ?>
              </td>
              <td>
                <div style="display:flex;gap:6px;">
                  <form action="<?= $appUrl ?>/<?= $adminPath ?>/ads/toggle" method="POST" style="display:inline;">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                    <input type="hidden" name="id" value="<?= $ad['id'] ?>">
                    <button type="submit" class="btn-sm-action btn-toggle" title="স্ট্যাটাস পরিবর্তন">
                      <?= !empty($ad['is_active']) ? 'বন্ধ করুন' : 'চালু করুন' ?>
                    </button>
                  </form>
                  <form action="<?= $appUrl ?>/<?= $adminPath ?>/ads/delete" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই বিজ্ঞাপনটি মুছে ফেলতে চান?');" style="display:inline;">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                    <input type="hidden" name="id" value="<?= $ad['id'] ?>">
                    <button type="submit" class="btn-sm-action btn-del" title="মুছে ফেলুন">🗑️</button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p style="text-align:center;padding:30px;color:var(--adm-text-muted);">আপাতত কোনো বিজ্ঞাপন তৈরি করা নেই। ওপরের ফরম দিয়ে নতুন বিজ্ঞাপন যোগ করুন।</p>
    <?php endif; ?>
  </div>
</main>

<script>
function toggleAdFields(type) {
  const imgBox = document.getElementById('image_fields_box');
  const vidBox = document.getElementById('video_fields_box');
  if (type === 'video') {
    imgBox.style.display = 'none';
    vidBox.style.display = 'block';
  } else {
    imgBox.style.display = 'block';
    vidBox.style.display = 'none';
  }
}
</script>

</body>
</html>

<?php
$isEditor = in_array($user['role'], ['super_admin', 'editor']);
$districts = $districts ?? [];
$categories = $categories ?? [];
?>
<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>নতুন সংবাদ রচনা | Newslensbd CMS</title>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    .admin-container { max-width: 1000px; margin: 30px auto; padding: 0 20px; }
    .form-card { background: var(--adm-card-bg); border: 1px solid var(--adm-border); border-radius: 8px; padding: 24px; }
    .form-row { margin-bottom: 18px; }
    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 18px; }
    label { display: block; font-size: 0.92rem; font-weight: 600; margin-bottom: 6px; color: var(--adm-text); }
    input[type="text"], input[type="url"], input[type="file"], select, textarea { width: 100%; padding: 10px 14px; background: var(--adm-nav-bg); border: 1px solid var(--adm-border); border-radius: 6px; color: var(--adm-text); font-size: 0.95rem; outline: none; }
    input:focus, select:focus, textarea:focus { border-color: var(--adm-primary); }
    textarea { min-height: 220px; line-height: 1.6; resize: vertical; }
    .checkbox-group { display: flex; gap: 24px; margin-bottom: 20px; background: var(--adm-nav-bg); padding: 12px; border-radius: 6px; border: 1px solid var(--adm-border); }
    .check-label { display: flex; align-items: center; gap: 8px; font-size: 0.9rem; cursor: pointer; color: var(--adm-text); }
    .action-row { display: flex; gap: 12px; justify-content: flex-end; padding-top: 16px; border-top: 1px solid var(--adm-border); }
    .btn { padding: 10px 20px; border-radius: 6px; font-weight: 700; font-size: 0.95rem; border: none; cursor: pointer; }
    .btn-draft { background: #334155; color: #fff; }
    .btn-draft:hover { background: #475569; }
    .btn-review { background: #f59e0b; color: #fff; }
    .btn-review:hover { background: #d97706; }
    .btn-publish { background: var(--adm-primary); color: #fff; }
    .btn-publish:hover { background: var(--adm-primary-hover); }
    .alert-err { background: #7f1d1d; border: 1px solid #b91c1c; color: #fecaca; padding: 12px 18px; border-radius: 6px; margin-bottom: 20px; font-weight: 600; }
  </style>
</head>
<body>
  <?php 
  $activeTab = 'posts';
  require __DIR__ . '/partials/header.php'; 
  ?>

  <main class="admin-container">
    <div style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
      <div>
        <h2 data-bn="✍️ নতুন সংবাদ তৈরি করুন" data-en="✍️ Create New Post">✍️ নতুন সংবাদ তৈরি করুন</h2>
        <p style="color: #94a3b8; font-size: 0.9rem;" data-bn="সঠিক তথ্য ও নিরপেক্ষতার সাথে সংবাদ পরিবেশন করুন" data-en="Deliver news with accurate information and neutrality">সঠিক তথ্য ও নিরপেক্ষতার সাথে সংবাদ পরিবেশন করুন</p>
      </div>
      <a href="<?= $appUrl ?>/<?= $adminPath ?>/posts" style="color:var(--adm-accent);text-decoration:none;font-weight:600;" data-bn="← সংবাদ তালিকায় ফিরে যান" data-en="← Back to News List">← সংবাদ তালিকায় ফিরে যান</a>
    </div>

    <?php if (!empty($_GET['err'])): ?>
      <div class="alert-err">
        <span data-bn="⚠️ ত্রুটি: <?= htmlspecialchars($_GET['err']) ?>" data-en="⚠️ Error: <?= htmlspecialchars($_GET['err']) ?>">⚠️ ত্রুটি: <?= htmlspecialchars($_GET['err']) ?></span>
      </div>
    <?php endif; ?>

    <div class="form-card">
      <form action="<?= $appUrl ?>/<?= $adminPath ?>/posts/store" method="post" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

        <div class="form-row">
          <label for="title" data-bn="খবরের প্রধান শিরোনাম *" data-en="Main News Headline *">খবরের প্রধান শিরোনাম *</label>
          <input type="text" name="title" id="title" required placeholder="আকর্ষণীয় ও বস্তুনিষ্ঠ শিরোনাম লিখুন..." data-placeholder-bn="আকর্ষণীয় ও বস্তুনিষ্ঠ শিরোনাম লিখুন..." data-placeholder-en="Write an engaging and objective headline...">
        </div>

        <div class="form-grid-2">
          <div>
            <label for="category_id" data-bn="ক্যাটাগরি নির্বাচন করুন *" data-en="Select Category *">ক্যাটাগরি নির্বাচন করুন *</label>
            <select name="category_id" id="category_id" required>
              <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" data-bn="<?= htmlspecialchars($cat['name_bn']) ?> (<?= htmlspecialchars($cat['name_en'] ?? '') ?>)" data-en="<?= htmlspecialchars($cat['name_en'] ?? $cat['name_bn']) ?>"><?= htmlspecialchars($cat['name_bn']) ?> (<?= htmlspecialchars($cat['name_en'] ?? '') ?>)</option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="district_id" data-bn="জেলা (যদি নির্দিষ্ট জেলার সংবাদ হয়)" data-en="District (If location-specific)">জেলা (যদি নির্দিষ্ট জেলার সংবাদ হয়)</label>
            <select name="district_id" id="district_id">
              <option value="" data-bn="-- জাতীয় / কোনো নির্দিষ্ট জেলা নয় --" data-en="-- National / No specific district --">-- জাতীয় / কোনো নির্দিষ্ট জেলা নয় --</option>
              <?php foreach ($districts as $dst): ?>
                <option value="<?= $dst['id'] ?>" data-bn="<?= htmlspecialchars($dst['name_bn']) ?> (<?= htmlspecialchars($dst['division_name'] ?? '') ?>)" data-en="<?= htmlspecialchars($dst['name_en'] ?? $dst['name_bn']) ?> (<?= htmlspecialchars($dst['division_name'] ?? '') ?>)"><?= htmlspecialchars($dst['name_bn']) ?> (<?= htmlspecialchars($dst['division_name'] ?? '') ?>)</option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <!-- Author / Byline Selection -->
        <div class="form-row" style="background: var(--adm-nav-bg, rgba(255,255,255,0.03)); padding: 14px 16px; border-radius: 8px; border: 1px solid var(--adm-border, #e2e8f0); margin-bottom: 18px;">
          <label style="font-weight: 700; display: block; margin-bottom: 8px;" data-bn="👤 লেখক / প্রতিবেদকের নাম (Author / Byline) *" data-en="👤 Author / Byline Name *">👤 লেখক / প্রতিবেদকের নাম (Author / Byline) *</label>
          <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 6px;">
            <label style="display: inline-flex; align-items: center; gap: 6px; cursor: pointer;">
              <input type="radio" name="author_choice" value="account" checked onchange="toggleAuthorChoice('account')">
              <span data-bn="<strong>অ্যাকাউন্ট নাম:</strong> <?= htmlspecialchars($user['name'] ?? 'Super Admin') ?>" data-en="<strong>Account Name:</strong> <?= htmlspecialchars($user['name'] ?? 'Super Admin') ?>"><strong>অ্যাকাউন্ট নাম:</strong> <?= htmlspecialchars($user['name'] ?? 'Super Admin') ?></span>
            </label>
            <label style="display: inline-flex; align-items: center; gap: 6px; cursor: pointer;">
              <input type="radio" name="author_choice" value="anonymous" onchange="toggleAuthorChoice('anonymous')">
              <span data-bn="🕵️ <strong>বেনামী (Anonymous)</strong>" data-en="🕵️ <strong>Anonymous</strong>">🕵️ <strong>বেনামী (Anonymous)</strong></span>
            </label>
            <label style="display: inline-flex; align-items: center; gap: 6px; cursor: pointer;">
              <input type="radio" name="author_choice" value="custom" onchange="toggleAuthorChoice('custom')">
              <span data-bn="✏️ <strong>অন্য নাম লিখুন (Custom)</strong>" data-en="✏️ <strong>Write Custom Name</strong>">✏️ <strong>অন্য নাম লিখুন (Custom)</strong></span>
            </label>
          </div>
          <div id="customAuthorBox" style="display: none; margin-top: 10px;">
            <input type="text" name="custom_author_name" id="custom_author_name" placeholder="প্রতিবেদকের কাস্টম নাম লিখুন (যেমন: নিজস্ব প্রতিবেদক, বিশেষ প্রতিনিধি)" data-placeholder-bn="প্রতিবেদকের কাস্টম নাম লিখুন (যেমন: নিজস্ব প্রতিবেদক, বিশেষ প্রতিনিধি)" data-placeholder-en="Enter custom author name (e.g. Staff Reporter, Special Correspondent)">
          </div>
        </div>

        <div class="form-grid-2">
          <div>
            <label for="featured_image" data-bn="ফিচার্ড ছবির URL (ওয়েব লিংক)" data-en="Featured Image URL (Web link)">ফিচার্ড ছবির URL (ওয়েব লিংক)</label>
            <input type="url" name="featured_image" id="featured_image" placeholder="https://domain.com/photo.jpg" value="https://images.unsplash.com/photo-1541872703-74c5e44368f9?auto=format&fit=crop&w=800&q=80">
          </div>
          <div>
            <label for="image_file" data-bn="অথবা লোকাল ডিভাইস থেকে ছবি আপলোড করুন" data-en="Or upload image from local device">অথবা লোকাল ডিভাইস থেকে ছবি আপলোড করুন</label>
            <input type="file" name="image_file" id="image_file" accept="image/jpeg,image/png,image/webp">
          </div>
        </div>

        <div class="form-row">
          <label for="video_url" data-bn="📺 ভিডিও প্রতিবেদন URL (ঐচ্ছিক - YouTube ভিডিও লিংক)" data-en="📺 Video Report URL (Optional - YouTube link)">📺 ভিডিও প্রতিবেদন URL (ঐচ্ছিক - YouTube ভিডিও লিংক)</label>
          <input type="url" name="video_url" id="video_url" placeholder="https://www.youtube.com/watch?v=...">
        </div>

        <div class="form-row">
          <label for="body" data-bn="খবরের মূল বিবরণ (Body) *" data-en="Main News Content (Body) *">খবরের মূল বিবরণ (Body) *</label>
          <textarea name="body" id="body" required placeholder="খবরের পূর্ণাঙ্গ বিবরণ লিখুন..." data-placeholder-bn="খবরের পূর্ণাঙ্গ বিবরণ লিখুন..." data-placeholder-en="Write full news content..."></textarea>
        </div>

        <!-- Editorial Attributes -->
        <div class="checkbox-group">
          <label class="check-label">
            <input type="checkbox" name="is_lead" value="1">
            <strong data-bn="প্রধান খবর (Lead News) হিসেবে হোমপেজে প্রদর্শন করুন" data-en="Display on homepage as Lead News">প্রধান খবর (Lead News) হিসেবে হোমপেজে প্রদর্শন করুন</strong>
          </label>
          <label class="check-label">
            <input type="checkbox" name="is_breaking" value="1">
            <strong style="color: #f87171;" data-bn="ব্রেকিং নিউজ টিকারে চালু করুন" data-en="Enable in Breaking News ticker">ব্রেকিং নিউজ টিকারে চালু করুন</strong>
          </label>
        </div>

        <!-- Workflow Action Buttons -->
        <div class="action-row">
          <button type="submit" name="submit_action" value="draft" class="btn btn-draft" data-bn="💾 খসড়া রাখুন (Draft)" data-en="💾 Save as Draft">💾 খসড়া রাখুন (Draft)</button>
          <button type="submit" name="submit_action" value="submit_review" class="btn btn-review" data-bn="📨 এডিটরের কাছে পাঠান (Pending)" data-en="📨 Send to Editor (Pending)">📨 এডিটরের কাছে পাঠান (Pending)</button>
          <?php if ($isEditor): ?>
            <button type="submit" name="submit_action" value="publish" class="btn btn-publish" data-bn="🚀 সরাসরি প্রকাশ করুন (Publish)" data-en="🚀 Publish Directly">🚀 সরাসরি প্রকাশ করুন (Publish)</button>
          <?php endif; ?>
        </div>

      </form>
    </div>
  </main>
  <script>
  function toggleAuthorChoice(val) {
    const box = document.getElementById('customAuthorBox');
    if (box) {
      box.style.display = (val === 'custom') ? 'block' : 'none';
      if (val === 'custom') document.getElementById('custom_author_name')?.focus();
    }
  }
  </script>
  <?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>

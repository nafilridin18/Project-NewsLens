<?php
$isEditor = in_array($user['role'], ['super_admin', 'editor']);
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
    input[type="text"], input[type="url"], select, textarea { width: 100%; padding: 10px 14px; background: var(--adm-nav-bg); border: 1px solid var(--adm-border); border-radius: 6px; color: var(--adm-text); font-size: 0.95rem; outline: none; }
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
  </style>
</head>
<body>
  <?php 
  $activeTab = 'posts';
  require __DIR__ . '/partials/header.php'; 
  ?>

  <main class="admin-container">
    <div style="margin-bottom: 20px;">
      <h2>✍️ নতুন সংবাদ তৈরি করুন</h2>
      <p style="color: #94a3b8; font-size: 0.9rem;">সঠিক তথ্য ও নিরপেক্ষতার সাথে সংবাদ পরিবেশন করুন</p>
    </div>

    <div class="form-card">
      <form action="<?= $appUrl ?>/<?= $adminPath ?>/posts/store" method="post">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

        <div class="form-row">
          <label for="title">খবরের প্রধান শিরোনাম *</label>
          <input type="text" name="title" id="title" required placeholder="আকর্ষণীয় ও বস্তুনিষ্ঠ শিরোনাম লিখুন...">
        </div>

        <div class="form-grid-2">
          <div>
            <label for="category_id">ক্যাটাগরি নির্বাচন করুন *</label>
            <select name="category_id" id="category_id" required>
              <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name_bn']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label for="district_id">জেলা (যদি প্রযোজ্য হয়)</label>
            <select name="district_id" id="district_id">
              <option value="">-- জাতীয় / কোনো নির্দিষ্ট জেলা নয় --</option>
              <option value="101">ঢাকা</option>
              <option value="201">চট্টগ্রাম</option>
              <option value="301">রাজশাহী</option>
              <option value="401">খুলনা</option>
              <option value="501">বরিশাল</option>
              <option value="601">সিলেট</option>
              <option value="701">রংপুর</option>
              <option value="801">ময়মনসিংহ</option>
            </select>
          </div>
        </div>

        <div class="form-row">
          <label for="featured_image">ফিচার্ড ছবির URL (বা মিডিয়া লাইব্রেরি)</label>
          <input type="url" name="featured_image" id="featured_image" placeholder="https://domain.com/photo.jpg" value="https://images.unsplash.com/photo-1541872703-74c5e44368f9?auto=format&fit=crop&w=800&q=80">
        </div>

        <div class="form-row">
          <label for="body">খবরের মূল বিবরণ (Body) *</label>
          <textarea name="body" id="body" required placeholder="খবরের পূর্ণাঙ্গ বিবরণ লিখুন..."></textarea>
        </div>

        <!-- Editorial Attributes -->
        <div class="checkbox-group">
          <label class="check-label">
            <input type="checkbox" name="is_lead" value="1">
            <strong>প্রধান খবর (Lead News) হিসেবে হোমপেজে প্রদর্শন করুন</strong>
          </label>
          <label class="check-label">
            <input type="checkbox" name="is_breaking" value="1">
            <strong style="color: #f87171;">ব্রেকিং নিউজ টিকারে চালু করুন</strong>
          </label>
        </div>

        <!-- Workflow Action Buttons -->
        <div class="action-row">
          <button type="submit" name="submit_action" value="draft" class="btn btn-draft">💾 খসড়া রাখুন (Draft)</button>
          <button type="submit" name="submit_action" value="submit_review" class="btn btn-review">📨 এডিটরের কাছে পাঠান (Pending)</button>
          <?php if ($isEditor): ?>
            <button type="submit" name="submit_action" value="publish" class="btn btn-publish">🚀 সরাসরি প্রকাশ করুন (Publish)</button>
          <?php endif; ?>
        </div>

      </form>
    </div>
  </main>
</body>
</html>

<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>সংবাদ সম্পাদনা | Newslens Desk</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    .admin-container { max-width: 1000px; margin: 30px auto; padding: 0 20px; }
    .page-title-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .form-card { background: var(--adm-card-bg); padding: 28px; border-radius: 8px; border: 1px solid var(--adm-border); box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); }
    .form-group { margin-bottom: 20px; }
    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px; }
    .form-group label { display: block; font-weight: 600; margin-bottom: 6px; color: var(--adm-text); }
    .form-control { width: 100%; padding: 10px 14px; background: var(--adm-nav-bg); border: 1px solid var(--adm-border); border-radius: 6px; font-size: 0.95rem; color: var(--adm-text); }
    input:focus, select:focus, textarea:focus { border-color: var(--adm-primary); outline: none; }
    textarea.form-control { min-height: 220px; font-family: inherit; line-height: 1.6; }
    .checkbox-group { display: flex; gap: 24px; margin-bottom: 20px; background: var(--adm-nav-bg); padding: 12px; border-radius: 6px; border: 1px solid var(--adm-border); }
    .checkbox-group label { display: inline-flex; align-items: center; gap: 6px; font-weight: 500; cursor: pointer; color: var(--adm-text); }
    .btn-submit { background: var(--adm-primary); color: #ffffff; padding: 12px 28px; border-radius: 6px; border: none; font-weight: 700; font-size: 1rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }
    .btn-submit:hover { background: var(--adm-primary-hover); }
    .current-img-preview { max-width: 240px; border-radius: 6px; margin-top: 8px; border: 1px solid var(--adm-border); }
    .alert-err { background: #7f1d1d; border: 1px solid #b91c1c; color: #fecaca; padding: 12px 18px; border-radius: 6px; margin-bottom: 20px; font-weight: 600; }
  </style>
</head>
<body>

<?php
$activeTab = 'posts';
require __DIR__ . '/partials/header.php';
$districts = $districts ?? [];
$categories = $categories ?? [];
$postImage = $post['featured_image'] ?? $post['image_url'] ?? '';
$postBody = $post['body'] ?? $post['content'] ?? '';
?>

<main class="admin-container">
  <div class="page-title-row">
    <h2><span data-bn="✏️ সংবাদ সম্পাদনা" data-en="✏️ Edit Post">✏️ সংবাদ সম্পাদনা</span> (Edit Post #<?= $post['id'] ?>)</h2>
    <a href="<?= $appUrl ?>/<?= $adminPath ?>/posts" style="color:var(--adm-accent);text-decoration:none;font-weight:600;" data-bn="← সংবাদ তালিকায় ফিরে যান" data-en="← Back to News List">← সংবাদ তালিকায় ফিরে যান</a>
  </div>

  <?php if (!empty($_GET['err'])): ?>
    <div class="alert-err">
      <span data-bn="⚠️ ত্রুটি: <?= htmlspecialchars($_GET['err']) ?>" data-en="⚠️ Error: <?= htmlspecialchars($_GET['err']) ?>">⚠️ ত্রুটি: <?= htmlspecialchars($_GET['err']) ?></span>
    </div>
  <?php endif; ?>

  <div class="form-card">
    <form action="<?= $appUrl ?>/<?= $adminPath ?>/posts/update" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
      <input type="hidden" name="id" value="<?= $post['id'] ?>">

      <div class="form-group">
        <label for="title" data-bn="খবরের প্রধান শিরোনাম *" data-en="Main News Headline *">খবরের প্রধান শিরোনাম *</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required class="form-control">
      </div>

      <div class="form-grid-2">
        <div>
          <label for="category_id" data-bn="ক্যাটাগরি নির্বাচন করুন *" data-en="Select Category *">ক্যাটাগরি নির্বাচন করুন *</label>
          <select id="category_id" name="category_id" required class="form-control">
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $post['category_id']) ? 'selected' : '' ?> data-bn="<?= htmlspecialchars($cat['name_bn']) ?> (<?= htmlspecialchars($cat['name_en'] ?? '') ?>)" data-en="<?= htmlspecialchars($cat['name_en'] ?? $cat['name_bn']) ?>">
                <?= htmlspecialchars($cat['name_bn']) ?> (<?= htmlspecialchars($cat['name_en'] ?? '') ?>)
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label for="district_id" data-bn="জেলা (যদি প্রযোজ্য হয়)" data-en="District (If applicable)">জেলা (যদি প্রযোজ্য হয়)</label>
          <select id="district_id" name="district_id" class="form-control">
            <option value="" data-bn="-- জাতীয় / কোনো নির্দিষ্ট জেলা নয় --" data-en="-- National / No specific district --">-- জাতীয় / কোনো নির্দিষ্ট জেলা নয় --</option>
            <?php foreach ($districts as $dst): ?>
              <option value="<?= $dst['id'] ?>" <?= ($dst['id'] == ($post['district_id'] ?? null)) ? 'selected' : '' ?> data-bn="<?= htmlspecialchars($dst['name_bn']) ?>" data-en="<?= htmlspecialchars($dst['name_en'] ?? $dst['name_bn']) ?>">
                <?= htmlspecialchars($dst['name_bn']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <?php
        $currentCustomAuthor = $post['custom_author'] ?? '';
        $authorChoice = 'account';
        if ($currentCustomAuthor === 'বেনামী') {
            $authorChoice = 'anonymous';
        } elseif (!empty($currentCustomAuthor)) {
            $authorChoice = 'custom';
        }
      ?>
      <!-- Author / Byline Selection -->
      <div class="form-group" style="background: var(--adm-nav-bg, rgba(255,255,255,0.03)); padding: 14px 16px; border-radius: 8px; border: 1px solid var(--adm-border, #e2e8f0); margin-bottom: 18px;">
        <label style="font-weight: 700; display: block; margin-bottom: 8px;" data-bn="👤 লেখক / প্রতিবেদকের নাম (Author / Byline) *" data-en="👤 Author / Byline Name *">👤 লেখক / প্রতিবেদকের নাম (Author / Byline) *</label>
        <div style="display: flex; gap: 20px; flex-wrap: wrap; margin-bottom: 6px;">
          <label style="display: inline-flex; align-items: center; gap: 6px; cursor: pointer;">
            <input type="radio" name="author_choice" value="account" <?= ($authorChoice === 'account') ? 'checked' : '' ?> onchange="toggleAuthorChoice('account')">
            <span data-bn="<strong>অ্যাকাউন্ট নাম:</strong> <?= htmlspecialchars($user['name'] ?? 'Super Admin') ?>" data-en="<strong>Account Name:</strong> <?= htmlspecialchars($user['name'] ?? 'Super Admin') ?>"><strong>অ্যাকাউন্ট নাম:</strong> <?= htmlspecialchars($user['name'] ?? 'Super Admin') ?></span>
          </label>
          <label style="display: inline-flex; align-items: center; gap: 6px; cursor: pointer;">
            <input type="radio" name="author_choice" value="anonymous" <?= ($authorChoice === 'anonymous') ? 'checked' : '' ?> onchange="toggleAuthorChoice('anonymous')">
            <span data-bn="🕵️ <strong>বেনামী (Anonymous)</strong>" data-en="🕵️ <strong>Anonymous</strong>">🕵️ <strong>বেনামী (Anonymous)</strong></span>
          </label>
          <label style="display: inline-flex; align-items: center; gap: 6px; cursor: pointer;">
            <input type="radio" name="author_choice" value="custom" <?= ($authorChoice === 'custom') ? 'checked' : '' ?> onchange="toggleAuthorChoice('custom')">
            <span data-bn="✏️ <strong>অন্য নাম লিখুন (Custom)</strong>" data-en="✏️ <strong>Write Custom Name</strong>">✏️ <strong>অন্য নাম লিখুন (Custom)</strong></span>
          </label>
        </div>
        <div id="customAuthorBox" style="display: <?= ($authorChoice === 'custom') ? 'block' : 'none' ?>; margin-top: 10px;">
          <input type="text" name="custom_author_name" id="custom_author_name" value="<?= htmlspecialchars(($authorChoice === 'custom') ? $currentCustomAuthor : '') ?>" placeholder="প্রতিবেদকের কাস্টম নাম লিখুন (যেমন: নিজস্ব প্রতিবেদক, বিশেষ প্রতিনিধি)" data-placeholder-bn="প্রতিবেদকের কাস্টম নাম লিখুন (যেমন: নিজস্ব প্রতিবেদক, বিশেষ প্রতিনিধি)" data-placeholder-en="Enter custom author name (e.g. Staff Reporter, Special Correspondent)" class="form-control">
        </div>
      </div>

      <div class="form-grid-2">
        <div>
          <label for="featured_image" data-bn="ফিচার্ড ছবির URL (ওয়েব লিংক)" data-en="Featured Image URL (Web link)">ফিচার্ড ছবির URL (ওয়েব লিংক)</label>
          <input type="text" id="featured_image" name="featured_image" value="<?= htmlspecialchars($postImage) ?>" class="form-control">
          <?php if (!empty($postImage)): ?>
            <div style="margin-top: 8px;">
              <span style="font-size: 0.85rem; color: var(--adm-text-muted);" data-bn="বর্তমান ছবি প্রিভিউ:" data-en="Current image preview:">বর্তমান ছবি প্রিভিউ:</span><br>
              <img src="<?= htmlspecialchars($postImage) ?>" alt="Post Image" class="current-img-preview">
            </div>
          <?php endif; ?>
        </div>
        <div>
          <label for="image_file" data-bn="অথবা নতুন ছবি আপলোড করুন" data-en="Or upload new image">অথবা নতুন ছবি আপলোড করুন</label>
          <input type="file" id="image_file" name="image_file" accept="image/jpeg,image/png,image/webp" class="form-control">
          <small style="color:var(--adm-text-muted); display:block; margin-top:6px;" data-bn="নতুন ছবি নির্বাচন না করলে আগের ছবিটি অপরিবর্তিত থাকবে।" data-en="If no new image is selected, the existing image will remain unchanged.">নতুন ছবি নির্বাচন না করলে আগের ছবিটি অপরিবর্তিত থাকবে।</small>
        </div>
      </div>

      <div class="form-group">
        <label for="video_url" data-bn="📺 ভিডিও URL (YouTube/ভিডিও লিংক):" data-en="📺 Video URL (YouTube/video link):">📺 ভিডিও URL (YouTube/ভিডিও লিংক):</label>
        <input type="url" id="video_url" name="video_url" value="<?= htmlspecialchars($post['video_url'] ?? '') ?>" placeholder="https://www.youtube.com/watch?v=..." class="form-control">
      </div>

      <div class="form-group">
        <label for="excerpt" data-bn="সংক্ষিপ্ত বিবরণ (Excerpt / সারসংক্ষেপ):" data-en="Short Excerpt:">সংক্ষিপ্ত বিবরণ (Excerpt / সারসংক্ষেপ):</label>
        <textarea id="excerpt" name="excerpt" rows="3" class="form-control" style="min-height:80px;"><?= htmlspecialchars($post['excerpt'] ?? '') ?></textarea>
      </div>

      <div class="form-group">
        <label for="body" data-bn="মূল সংবাদ বিস্তারিত (News Body) *" data-en="Main News Content (News Body) *">মূল সংবাদ বিস্তারিত (News Body) *</label>
        <textarea id="body" name="body" required class="form-control"><?= htmlspecialchars($postBody) ?></textarea>
      </div>

      <div class="checkbox-group">
        <label>
          <input type="checkbox" name="is_lead" value="1" <?= (!empty($post['is_lead'])) ? 'checked' : '' ?>>
          <span data-bn="⭐ লিড নিউজ (Lead News হিসেবে প্রদর্শন)" data-en="⭐ Lead News (Display as Lead News)">⭐ লিড নিউজ (Lead News হিসেবে প্রদর্শন)</span>
        </label>
        <label>
          <input type="checkbox" name="is_breaking" value="1" <?= (!empty($post['is_breaking'])) ? 'checked' : '' ?>>
          <span data-bn="🔴 ব্রেকিং নিউজ (টিকার স্লাইডারে প্রদর্শন)" data-en="🔴 Breaking News (Display in ticker)">🔴 ব্রেকিং নিউজ (টিকার স্লাইডারে প্রদর্শন)</span>
        </label>
      </div>

      <div class="form-group">
        <label for="status" data-bn="স্ট্যাটাস:" data-en="Status:">স্ট্যাটাস:</label>
        <select id="status" name="status" class="form-control">
          <option value="published" <?= ($post['status'] === 'published') ? 'selected' : '' ?> data-bn="প্রকাশিত (Published)" data-en="Published">প্রকাশিত (Published)</option>
          <option value="draft" <?= ($post['status'] === 'draft') ? 'selected' : '' ?> data-bn="খসড়া (Draft)" data-en="Draft">খসড়া (Draft)</option>
          <option value="pending" <?= ($post['status'] === 'pending') ? 'selected' : '' ?> data-bn="রিভিউ অপেক্ষমাণ (Pending)" data-en="Pending Review">রিভিউ অপেক্ষমাণ (Pending)</option>
          <option value="rejected" <?= ($post['status'] === 'rejected') ? 'selected' : '' ?> data-bn="বাতিল (Rejected)" data-en="Rejected">বাতিল (Rejected)</option>
        </select>
      </div>

      <button type="submit" class="btn-submit" data-bn="💾 পরিবর্তন সংরক্ষণ করুন (Update News)" data-en="💾 Update News Post">💾 পরিবর্তন সংরক্ষণ করুন (Update News)</button>
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

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
    .form-group label { display: block; font-weight: 600; margin-bottom: 6px; color: var(--adm-text); }
    .form-control { width: 100%; padding: 10px 14px; background: var(--adm-nav-bg); border: 1px solid var(--adm-border); border-radius: 6px; font-size: 0.95rem; color: var(--adm-text); }
    input:focus, select:focus, textarea:focus { border-color: var(--adm-primary); outline: none; }
    textarea.form-control { min-height: 200px; font-family: inherit; line-height: 1.6; }
    .checkbox-group { display: flex; gap: 24px; margin-bottom: 20px; background: var(--adm-nav-bg); padding: 12px; border-radius: 6px; border: 1px solid var(--adm-border); }
    .checkbox-group label { display: inline-flex; align-items: center; gap: 6px; font-weight: 500; cursor: pointer; color: var(--adm-text); }
    .btn-submit { background: var(--adm-primary); color: #ffffff; padding: 12px 28px; border-radius: 6px; border: none; font-weight: 700; font-size: 1rem; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }
    .btn-submit:hover { background: var(--adm-primary-hover); }
    .current-img-preview { max-width: 240px; border-radius: 6px; margin-top: 8px; border: 1px solid var(--adm-border); }
  </style>
</head>
<body>

<?php
$activeTab = 'posts';
require __DIR__ . '/partials/header.php';
?>

<main class="admin-container">
  <div class="page-title-row">
    <h2>✏️ সংবাদ সম্পাদনা (Edit Post #<?= $post['id'] ?>)</h2>
    <a href="<?= $appUrl ?>/<?= $adminPath ?>/posts" style="color:var(--adm-accent);text-decoration:none;font-weight:600;">← সংবাদ তালিকায় ফিরে যান</a>
  </div>

  <div class="form-card">
    <form action="<?= $appUrl ?>/<?= $adminPath ?>/posts/update" method="POST">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
      <input type="hidden" name="id" value="<?= $post['id'] ?>">

      <div class="form-group">
        <label for="title">খবরের শিরোনাম (Title):</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required class="form-control">
      </div>

      <div class="form-group">
        <label for="category_id">ক্যাটাগরি:</label>
        <select id="category_id" name="category_id" required class="form-control">
          <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $post['category_id']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($cat['name_bn']) ?> (<?= htmlspecialchars($cat['name_en']) ?>)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="excerpt">সংক্ষিপ্ত বিবরণ (Excerpt / সারসংক্ষেপ):</label>
        <textarea id="excerpt" name="excerpt" rows="3" class="form-control" style="min-height:80px;"><?= htmlspecialchars($post['excerpt'] ?? '') ?></textarea>
      </div>

      <div class="form-group">
        <label for="content">মূল সংবাদ বিস্তারিত (News Body):</label>
        <textarea id="content" name="content" required class="form-control"><?= htmlspecialchars($post['content']) ?></textarea>
      </div>

      <div class="form-group">
        <label for="image_url">ফিচার্ড ছবির URL (বা স্থানীয় পথ):</label>
        <input type="text" id="image_url" name="image_url" value="<?= htmlspecialchars($post['image_url'] ?? '') ?>" class="form-control">
        <?php if (!empty($post['image_url'])): ?>
          <div style="margin-top: 8px;">
            <span style="font-size: 0.85rem; color: var(--adm-text-muted);">বর্তমান ছবি:</span><br>
            <img src="<?= htmlspecialchars($post['image_url']) ?>" alt="Post Image" class="current-img-preview">
          </div>
        <?php endif; ?>
      </div>

      <div class="form-group">
        <label for="video_url">ভিডিও URL (YouTube/ভিডিও লিংক):</label>
        <input type="url" id="video_url" name="video_url" value="<?= htmlspecialchars($post['video_url'] ?? '') ?>" placeholder="https://www.youtube.com/watch?v=..." class="form-control">
      </div>

      <div class="checkbox-group">
        <label>
          <input type="checkbox" name="is_lead" value="1" <?= (!empty($post['is_lead'])) ? 'checked' : '' ?>>
          <span>⭐ লিড নিউজ (Lead News হিসেবে প্রদর্শন)</span>
        </label>
        <label>
          <input type="checkbox" name="is_breaking" value="1" <?= (!empty($post['is_breaking'])) ? 'checked' : '' ?>>
          <span>🔴 ব্রেকিং নিউজ (টিকার স্লাইডারে প্রদর্শন)</span>
        </label>
      </div>

      <div class="form-group">
        <label for="status">স্ট্যাটাস:</label>
        <select id="status" name="status" class="form-control">
          <option value="published" <?= ($post['status'] === 'published') ? 'selected' : '' ?>>প্রকাশিত (Published)</option>
          <option value="draft" <?= ($post['status'] === 'draft') ? 'selected' : '' ?>>খসড়া (Draft)</option>
          <option value="pending" <?= ($post['status'] === 'pending') ? 'selected' : '' ?>>রিভিউ অপেক্ষমাণ (Pending)</option>
          <option value="rejected" <?= ($post['status'] === 'rejected') ? 'selected' : '' ?>>বাতিল (Rejected)</option>
        </select>
      </div>

      <button type="submit" class="btn-submit">💾 পরিবর্তন সংরক্ষণ করুন (Update News)</button>
    </form>
  </div>
</main>

</body>
</html>

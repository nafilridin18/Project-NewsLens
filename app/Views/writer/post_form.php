<?php
$adminPath = $adminPath ?? 'lens-desk';
$appUrl = rtrim($appUrl ?? '', '/');
$isEdit = $isEdit ?? false;
$post = $post ?? [];
$categories = $categories ?? [];
$districts = $districts ?? [];
$user = $user ?? \App\Core\Auth::user();
$csrfToken = $csrfToken ?? \App\Core\Auth::generateCsrf();
$error = $_SESSION['writer_post_err'] ?? null;
unset($_SESSION['writer_post_err']);
$activeTab = 'writer_posts';
$permLevel = $user['permission_level'] ?? 'editor_review';
?>
<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle ?? ($isEdit ? 'সংবাদ সম্পাদনা' : 'নতুন সংবাদ লিখুন')) ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Hind Siliguri', sans-serif; background: var(--adm-bg); color: var(--adm-text); }
    .form-wrap { max-width: 1000px; margin: 24px auto 60px; padding: 0 20px; }

    .header-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 16px;
      margin-bottom: 24px;
    }
    .header-title { font-size: 1.6rem; font-weight: 800; display: flex; align-items: center; gap: 10px; }
    .back-link { color: var(--adm-accent); text-decoration: none; font-weight: 600; font-size: 0.95rem; }
    .back-link:hover { text-decoration: underline; }

    .alert-error {
      background: rgba(239, 68, 68, 0.2);
      border: 1px solid #ef4444;
      color: #fca5a5;
      padding: 12px 18px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-weight: 600;
    }

    .form-card {
      background: var(--adm-card-bg);
      border: 1px solid var(--adm-border);
      border-radius: 14px;
      padding: 28px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }
    .form-group { margin-bottom: 22px; }
    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media(max-width: 640px) { .form-grid-2 { grid-template-columns: 1fr; } }

    label { display: block; font-size: 0.92rem; font-weight: 700; margin-bottom: 8px; color: var(--adm-text); }
    .required { color: #f87171; }
    input[type="text"], input[type="url"], input[type="file"], select, textarea {
      width: 100%;
      padding: 12px 14px;
      background: rgba(0,0,0,0.25);
      border: 1px solid var(--adm-border);
      border-radius: 8px;
      color: #fff;
      font-family: inherit;
      font-size: 0.95rem;
      outline: none;
      transition: border-color 0.2s;
    }
    input:focus, select:focus, textarea:focus { border-color: var(--adm-accent); }
    textarea { min-height: 260px; line-height: 1.7; resize: vertical; }

    /* Image Preview */
    .img-preview-box {
      margin-top: 10px;
      display: inline-block;
      position: relative;
    }
    .img-preview-box img {
      max-width: 220px;
      max-height: 140px;
      border-radius: 8px;
      border: 1px solid var(--adm-border);
      object-fit: cover;
    }

    /* Permission Notice Card */
    .perm-notice {
      background: rgba(255,255,255,0.03);
      border: 1px dashed var(--adm-border);
      border-radius: 10px;
      padding: 14px 18px;
      margin-bottom: 24px;
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .perm-icon { font-size: 1.6rem; }
    .perm-text { font-size: 0.88rem; color: var(--adm-text-muted); line-height: 1.5; }
    .perm-text strong { color: #fff; }

    /* Form Actions */
    .action-row {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      gap: 14px;
      margin-top: 30px;
      padding-top: 20px;
      border-top: 1px solid var(--adm-border);
      flex-wrap: wrap;
    }
    .btn {
      padding: 12px 24px;
      border-radius: 8px;
      font-size: 0.95rem;
      font-weight: 700;
      cursor: pointer;
      border: none;
      font-family: inherit;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.2s;
    }
    .btn-draft {
      background: rgba(255,255,255,0.08);
      border: 1px solid var(--adm-border);
      color: var(--adm-text);
    }
    .btn-draft:hover { background: rgba(255,255,255,0.15); }
    .btn-primary {
      background: #0E7A3A;
      color: #fff;
    }
    .btn-primary:hover { background: #0a5c2b; }
    .btn-review {
      background: #d97706;
      color: #fff;
    }
    .btn-review:hover { background: #b45309; }
  </style>
</head>
<body>
  <?php 
  $activeTab = 'writer_posts';
  require dirname(__DIR__) . '/admin/partials/header.php'; 
  ?>

  <div class="form-wrap">
    <div class="header-row">
      <div class="header-title">
        <span><?= $isEdit ? '✏️' : '✍️' ?></span>
        <span data-bn="<?= $isEdit ? 'সংবাদ সম্পাদনা' : 'নতুন সংবাদ রচনা' ?>" data-en="<?= $isEdit ? 'Edit Post' : 'Write New Article' ?>"><?= $isEdit ? 'সংবাদ সম্পাদনা' : 'নতুন সংবাদ রচনা' ?></span>
      </div>
      <a href="<?= $appUrl ?>/writer/posts" class="back-link" data-bn="← সংবাদ তালিকায় ফিরে যান" data-en="← Back to My Posts">← সংবাদ তালিকায় ফিরে যান</a>
    </div>

    <?php if (!empty($error)): ?>
      <div class="alert-error">✕ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Writer Permission Notice -->
    <div class="perm-notice">
      <div class="perm-icon"><?= ($permLevel === 'direct_publish') ? '⚡' : '📝' ?></div>
      <div class="perm-text">
        <?php if ($permLevel === 'direct_publish'): ?>
          <strong>সরাসরি প্রকাশাধিকার (Direct Publish):</strong> আপনার জমাকৃত বা সম্পাদিত সংবাদ সরাসরি প্রধান সাইটে প্রকাশিত হবে।
        <?php else: ?>
          <strong>সম্পাদকীয় পর্যালোচনা (Editor Review):</strong> আপনার সংবাদটি জমা বা সম্পাদনার পর সম্পাদকীয় দল পর্যালোচনা করে অনুমোদন করবে।
        <?php endif; ?>
      </div>
    </div>

    <div class="form-card">
      <form action="<?= $appUrl ?>/writer/posts/<?= $isEdit ? 'update' : 'store' ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <?php if ($isEdit): ?>
          <input type="hidden" name="id" value="<?= (int)($post['id'] ?? 0) ?>">
        <?php endif; ?>

        <!-- Author Attribution (Locked to Writer) -->
        <div class="form-group" style="background: rgba(255,255,255,0.02); padding: 12px 16px; border-radius: 8px; border: 1px solid var(--adm-border); display: flex; align-items: center; justify-content: space-between; flex-wrap: gap; gap: 10px;">
          <div>
            <label style="margin: 0; font-size: 0.85rem; color: var(--adm-text-muted);" data-bn="👤 লেখক / বাইলাইন (Author Attribution)" data-en="👤 Author Attribution">👤 লেখক / বাইলাইন (Author Attribution)</label>
            <div style="font-weight: 700; color: #fff; font-size: 1rem; margin-top: 2px;">
              <?= htmlspecialchars($user['name'] ?? 'Writer') ?>
            </div>
          </div>
          <span style="font-size: 0.75rem; background: rgba(14, 122, 58, 0.25); border: 1px solid #10b981; color: #86efac; padding: 3px 10px; border-radius: 20px; font-weight: 700;">
            ✓ আপনার নামে নিবন্ধিত (Locked)
          </span>
        </div>

        <!-- Headline -->
        <div class="form-group">
          <label for="title" data-bn="খবরের প্রধান শিরোনাম *" data-en="News Headline *">খবরের প্রধান শিরোনাম <span class="required">*</span></label>
          <input type="text" id="title" name="title" required placeholder="বস্তুনিষ্ঠ ও আকর্ষণীয় শিরোনাম লিখুন..." data-placeholder-bn="বস্তুনিষ্ঠ ও আকর্ষণীয় শিরোনাম লিখুন..." data-placeholder-en="Write an objective and engaging headline..." value="<?= htmlspecialchars($post['title'] ?? '') ?>">
        </div>

        <!-- Category & District -->
        <div class="form-grid-2">
          <div class="form-group">
            <label for="category_id">ক্যাটাগরি নির্বাচন করুন <span class="required">*</span></label>
            <select id="category_id" name="category_id" required>
              <option value="">-- ক্যাটাগরি বেছে নিন --</option>
              <?php foreach ($categories as $cat): ?>
                <option value="<?= (int)$cat['id'] ?>" <?= ((int)($post['category_id'] ?? 0) === (int)$cat['id']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($cat['name_bn']) ?> (<?= htmlspecialchars($cat['name_en'] ?? '') ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="district_id">জেলা (যদি নির্দিষ্ট জেলার সংবাদ হয়)</label>
            <select id="district_id" name="district_id">
              <option value="">-- জাতীয় / কোনো নির্দিষ্ট জেলা নয় --</option>
              <?php foreach ($districts as $dst): ?>
                <option value="<?= (int)$dst['id'] ?>" <?= ((int)($post['district_id'] ?? 0) === (int)$dst['id']) ? 'selected' : '' ?>>
                  <?= htmlspecialchars($dst['name_bn']) ?> (<?= htmlspecialchars($dst['division_name'] ?? '') ?>)
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <!-- Featured Image -->
        <div class="form-group">
          <label for="featured_image">ফিচার্ড ছবি (Featured Image)</label>
          <input type="file" id="featured_image" name="featured_image" accept="image/jpeg,image/png,image/webp">
          <div style="font-size: 0.8rem; color: var(--adm-text-muted); margin-top: 4px;">অনুমোদিত ফরম্যাট: JPG, PNG, WebP (সর্বোচ্চ ৫ মেগাবাইট)</div>
          <?php if (!empty($post['featured_image'])): ?>
            <div class="img-preview-box">
              <div style="font-size: 0.8rem; color: var(--adm-text-muted); margin-bottom: 4px;">বর্তমান ছবি:</div>
              <img src="<?= $appUrl ?>/<?= htmlspecialchars($post['featured_image']) ?>" alt="Preview">
            </div>
          <?php endif; ?>
        </div>

        <!-- Video URL -->
        <div class="form-group">
          <label for="video_url">ভিডিও লিংক (ঐচ্ছিক - YouTube / Facebook Embed)</label>
          <input type="url" id="video_url" name="video_url" placeholder="https://www.youtube.com/watch?v=..." value="<?= htmlspecialchars($post['video_url'] ?? '') ?>">
        </div>

        <!-- Body -->
        <div class="form-group">
          <label for="body">সংবাদের বিস্তারিত বিবরণ <span class="required">*</span></label>
          <textarea id="body" name="body" required placeholder="সংবাদের পূর্ণাঙ্গ বিবরণ এখানে লিখুন..."><?= htmlspecialchars($post['body'] ?? '') ?></textarea>
        </div>

        <!-- Actions -->
        <div class="action-row">
          <button type="submit" name="post_action" value="draft" class="btn btn-draft">
            💾 খসড়া হিসেবে রাখুন
          </button>

          <?php if ($permLevel === 'direct_publish'): ?>
            <button type="submit" name="post_action" value="publish" class="btn btn-primary">
              ⚡ সরাসরি প্রকাশ করুন
            </button>
          <?php else: ?>
            <button type="submit" name="post_action" value="publish" class="btn btn-review">
              📝 পর্যালোচনার জন্য জমা দিন
            </button>
          <?php endif; ?>
        </div>
      </form>
    </div>
  </div>
</body>
</html>

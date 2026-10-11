<?php
$adminPath = $adminPath ?? 'lens-desk';
$appUrl = rtrim($appUrl ?? '', '/');
$user = $user ?? [];
$success = $success ?? null;
$error = $error ?? null;
$csrfToken = $csrfToken ?? \App\Core\Auth::generateCsrf();
$activeTab = 'profile';

$socialLinks = [];
if (!empty($user['social_links'])) {
    $socialLinks = is_array($user['social_links']) ? $user['social_links'] : (json_decode($user['social_links'], true) ?: []);
}

$avatarUrl = !empty($user['avatar']) ? ($appUrl . '/' . htmlspecialchars($user['avatar'])) : ($appUrl . '/assets/img/default-avatar.png');
?>
<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle ?? 'প্রোফাইল সম্পাদনা') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Hind Siliguri', sans-serif; background: var(--adm-bg); color: var(--adm-text); }
    .edit-wrap { max-width: 900px; margin: 24px auto 60px; padding: 0 20px; }

    .header-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 24px;
    }
    .header-title { font-size: 1.6rem; font-weight: 800; display: flex; align-items: center; gap: 10px; }
    .back-link { color: var(--adm-accent); text-decoration: none; font-weight: 600; }

    .alert { padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; font-size: 0.92rem; }
    .alert-success { background: rgba(14, 122, 58, 0.2); border: 1px solid #10b981; color: #86efac; }
    .alert-error { background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; color: #fca5a5; }

    .card {
      background: var(--adm-card-bg);
      border: 1px solid var(--adm-border);
      border-radius: 14px;
      padding: 28px;
      margin-bottom: 28px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }
    .card-title { font-size: 1.25rem; font-weight: 800; margin-bottom: 20px; border-bottom: 1px solid var(--adm-border); padding-bottom: 12px; }

    .form-group { margin-bottom: 20px; }
    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media(max-width: 640px) { .form-grid-2 { grid-template-columns: 1fr; } }

    label { display: block; font-size: 0.92rem; font-weight: 700; margin-bottom: 8px; color: var(--adm-text); }
    .required { color: #f87171; }
    input[type="text"], input[type="email"], input[type="password"], input[type="url"], input[type="file"], textarea {
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
    input:focus, textarea:focus { border-color: var(--adm-accent); }
    input:disabled { background: rgba(255,255,255,0.03); color: var(--adm-text-muted); cursor: not-allowed; }
    textarea { min-height: 120px; resize: vertical; line-height: 1.6; }

    .avatar-preview-area {
      display: flex;
      align-items: center;
      gap: 20px;
      margin-top: 10px;
    }
    .avatar-thumb {
      width: 72px;
      height: 72px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid var(--adm-primary);
    }

    .btn-submit {
      background: #0E7A3A;
      color: #fff;
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
      transition: background 0.2s;
    }
    .btn-submit:hover { background: #0a5c2b; }
  </style>
</head>
<body>
  <?php require APP_PATH . '/Views/admin/partials/header.php'; ?>

  <div class="edit-wrap">
    <div class="header-row">
      <div class="header-title">
        <span>✏️</span>
        <span>প্রোফাইল ও তথ্য সম্পাদনা</span>
      </div>
      <a href="<?= $appUrl ?>/profile" class="back-link">← প্রোফাইলে ফিরে যান</a>
    </div>

    <?php if (!empty($success)): ?>
      <div class="alert alert-success">✓ <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="alert alert-error">✕ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Basic Profile Info Form -->
    <div class="card">
      <div class="card-title">সাধারণ তথ্য</div>
      <form action="<?= $appUrl ?>/profile/update" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

        <!-- Avatar upload -->
        <div class="form-group">
          <label for="avatar">প্রোফাইল ছবি (Avatar)</label>
          <div class="avatar-preview-area">
            <img src="<?= $avatarUrl ?>" alt="Avatar" class="avatar-thumb" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($user['name'] ?? 'User') ?>&background=0E7A3A&color=fff'">
            <div style="flex: 1;">
              <input type="file" id="avatar" name="avatar" accept="image/jpeg,image/png,image/webp">
              <div style="font-size: 0.8rem; color: var(--adm-text-muted); margin-top: 4px;">সর্বোচ্চ আকার ২ মেগাবাইট (JPG, PNG, WebP)</div>
            </div>
          </div>
        </div>

        <div class="form-grid-2">
          <div class="form-group">
            <label for="name">পূর্ণ নাম <span class="required">*</span></label>
            <input type="text" id="name" name="name" required value="<?= htmlspecialchars($user['name'] ?? '') ?>">
          </div>

          <div class="form-group">
            <label for="email">ইমেইল ঠিকানা</label>
            <input type="email" id="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" disabled title="ইমেইল পরিবর্তনযোগ্য নয়">
          </div>
        </div>

        <div class="form-grid-2">
          <div class="form-group">
            <label for="phone">মোবাইল নম্বর</label>
            <input type="text" id="phone" name="phone" placeholder="০১XXXXXXXXX" value="<?= htmlspecialchars($user['phone'] ?? '') ?>">
          </div>

          <div class="form-group">
            <label for="role">ভূমিকা (Role)</label>
            <input type="text" value="<?= htmlspecialchars(strtoupper($user['role'] ?? 'USER')) ?>" disabled>
          </div>
        </div>

        <div class="form-group">
          <label for="short_bio">সংক্ষিপ্ত পরিচিতি (Short Bio)</label>
          <textarea id="short_bio" name="short_bio" placeholder="আপনার সাংবাদিকতা বা লেখালেখির অভিজ্ঞতা, আগ্রহ ও পরিচিতি লিখুন..."><?= htmlspecialchars($user['short_bio'] ?? '') ?></textarea>
        </div>

        <div class="form-grid-2">
          <div class="form-group">
            <label for="social_facebook">ফেসবুক প্রোফাইল URL</label>
            <input type="url" id="social_facebook" name="social_facebook" placeholder="https://facebook.com/username" value="<?= htmlspecialchars($socialLinks['facebook'] ?? '') ?>">
          </div>

          <div class="form-group">
            <label for="social_twitter">টুইটার / এক্স (X) URL</label>
            <input type="url" id="social_twitter" name="social_twitter" placeholder="https://x.com/username" value="<?= htmlspecialchars($socialLinks['twitter'] ?? '') ?>">
          </div>
        </div>

        <div class="form-group">
          <label for="social_linkedin">লিঙ্কডইন URL</label>
          <input type="url" id="social_linkedin" name="social_linkedin" placeholder="https://linkedin.com/in/username" value="<?= htmlspecialchars($socialLinks['linkedin'] ?? '') ?>">
        </div>

        <button type="submit" class="btn-submit">💾 তথ্য সংরক্ষণ করুন</button>
      </form>
    </div>

    <!-- Password Change Form -->
    <div class="card">
      <div class="card-title">পাসওয়ার্ড পরিবর্তন</div>
      <form action="<?= $appUrl ?>/profile/change-password" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

        <div class="form-group">
          <label for="current_password">বর্তমান পাসওয়ার্ড <span class="required">*</span></label>
          <input type="password" id="current_password" name="current_password" required placeholder="আপনার বর্তমান পাসওয়ার্ড দিন">
        </div>

        <div class="form-grid-2">
          <div class="form-group">
            <label for="new_password">নতুন পাসওয়ার্ড <span class="required">*</span></label>
            <input type="password" id="new_password" name="new_password" required minlength="8" placeholder="কমপক্ষে ৮ অক্ষরের পাসওয়ার্ড">
          </div>

          <div class="form-group">
            <label for="confirm_password">নতুন পাসওয়ার্ড নিশ্চিত করুন <span class="required">*</span></label>
            <input type="password" id="confirm_password" name="confirm_password" required minlength="8" placeholder="আবার নতুন পাসওয়ার্ড লিখুন">
          </div>
        </div>

        <button type="submit" class="btn-submit" style="background: #2563eb;">🔒 পাসওয়ার্ড আপডেট করুন</button>
      </form>
    </div>
  </div>
</body>
</html>

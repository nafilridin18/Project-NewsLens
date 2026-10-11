<!DOCTYPE html>
<html lang="bn" data-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>লেখক হিসেবে নিবন্ধন | Newslensbd</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg-page: #071630;
      --bg-card: #0b1f44;
      --bg-input: #06152d;
      --border-color: rgba(255, 255, 255, 0.12);
      --text-main: #f8fafc;
      --text-muted: #94a3b8;
      --color-primary: #0E7A3A;
      --color-accent: #E31E24;
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Hind Siliguri', sans-serif;
      background: var(--bg-page);
      color: var(--text-main);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 30px 15px;
    }
    .form-container {
      width: 100%;
      max-width: 620px;
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      border-radius: 18px;
      box-shadow: 0 20px 45px rgba(0,0,0,0.4);
      padding: 38px 32px;
    }
    .brand-header {
      text-align: center;
      margin-bottom: 24px;
    }
    .brand-title {
      font-size: 1.85rem;
      font-weight: 800;
      letter-spacing: -0.5px;
      margin-bottom: 6px;
    }
    .brand-title .hl-green { color: var(--color-primary); }
    .brand-title .badge-red {
      background: var(--color-accent);
      color: #fff;
      font-size: 0.8rem;
      padding: 2px 6px;
      border-radius: 4px;
      margin-left: 4px;
      vertical-align: middle;
    }
    .brand-subtitle {
      font-size: 0.92rem;
      color: var(--text-muted);
    }
    .alert-danger {
      background: rgba(227, 30, 36, 0.15);
      border: 1px solid var(--color-accent);
      color: #fca5a5;
      padding: 12px 14px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-weight: 600;
      text-align: center;
      font-size: 0.9rem;
    }
    .form-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }
    @media (max-width: 600px) {
      .form-grid { grid-template-columns: 1fr; }
    }
    .form-group {
      margin-bottom: 16px;
    }
    label {
      display: block;
      font-size: 0.88rem;
      font-weight: 600;
      margin-bottom: 6px;
      color: var(--text-main);
    }
    .required { color: var(--color-accent); }
    .form-control {
      width: 100%;
      padding: 11px 14px;
      background: var(--bg-input);
      border: 1px solid var(--border-color);
      border-radius: 8px;
      color: var(--text-main);
      font-size: 0.92rem;
      font-family: inherit;
      outline: none;
      transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-control:focus {
      border-color: var(--color-primary);
      box-shadow: 0 0 0 3px rgba(14, 122, 58, 0.25);
    }
    textarea.form-control {
      resize: vertical;
      min-height: 75px;
    }
    .btn-submit {
      width: 100%;
      background: var(--color-primary);
      color: #fff;
      border: none;
      padding: 13px;
      border-radius: 8px;
      font-size: 1.05rem;
      font-weight: 800;
      cursor: pointer;
      margin-top: 10px;
      box-shadow: 0 4px 14px rgba(14, 122, 58, 0.35);
      transition: all 0.2s ease;
    }
    .btn-submit:hover {
      background: #0a5c2b;
      transform: translateY(-1px);
    }
    .bottom-hint {
      margin-top: 20px;
      text-align: center;
      font-size: 0.88rem;
      color: var(--text-muted);
      border-top: 1px solid var(--border-color);
      padding-top: 16px;
    }
    .bottom-hint a {
      color: #38bdf8;
      text-decoration: none;
      font-weight: 700;
    }
    .badge-info-pill {
      background: rgba(56, 189, 248, 0.12);
      border: 1px solid rgba(56, 189, 248, 0.3);
      color: #7dd3fc;
      padding: 10px 14px;
      border-radius: 8px;
      font-size: 0.85rem;
      margin-bottom: 20px;
      line-height: 1.5;
    }
  </style>
</head>
<body>

  <div class="form-container">
    <div class="brand-header">
      <div class="brand-title">NEWS<span class="hl-green">LENS</span><span class="badge-red">BD</span></div>
      <p class="brand-subtitle">লেখক / কন্ট্রিবিউটর আবেদন ফরম</p>
    </div>

    <div class="badge-info-pill">
      ✍️ <strong>Newslensbd-এ আপনাকে স্বাগতম!</strong> নিবন্ধন সম্পন্ন হলে আপনার ইমেইলে একটি ওটিপি যাবে। ওটিপি যাচাইয়ের পর আপনার প্রোফাইলটি অ্যাডমিন অনুমোদনের জন্য পেন্ডিং থাকবে।
    </div>

    <?php if (!empty($error)): ?>
      <div class="alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="/writer/register" method="post">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

      <div class="form-grid">
        <div class="form-group">
          <label for="name">আপনার পূর্ণ নাম <span class="required">*</span></label>
          <input type="text" name="name" id="name" class="form-control" placeholder="যেমন: কাজী নজরুল ইসলাম" required value="<?= htmlspecialchars($old['name'] ?? '') ?>" autofocus>
        </div>

        <div class="form-group">
          <label for="email">ইমেইল ঠিকানা <span class="required">*</span></label>
          <input type="email" name="email" id="email" class="form-control" placeholder="writer@domain.com" required value="<?= htmlspecialchars($old['email'] ?? '') ?>">
        </div>
      </div>

      <div class="form-group">
        <label for="phone">মোবাইল নম্বর (ঐচ্ছিক)</label>
        <input type="tel" name="phone" id="phone" class="form-control" placeholder="০১৭xxxxxxxx" value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
      </div>

      <div class="form-grid">
        <div class="form-group" style="position: relative;">
          <label for="password">পাসওয়ার্ড <span class="required">*</span></label>
          <div style="position: relative;">
            <input type="password" name="password" id="password" class="form-control" placeholder="কমপক্ষে ৬ অক্ষর" required minlength="6" style="padding-right: 42px;">
            <button type="button" onclick="togglePass('password', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: transparent; border: none; font-size: 1.15rem; cursor: pointer; color: var(--text-muted);">👁️</button>
          </div>
        </div>

        <div class="form-group" style="position: relative;">
          <label for="password_confirm">পাসওয়ার্ড নিশ্চিত করুন <span class="required">*</span></label>
          <div style="position: relative;">
            <input type="password" name="password_confirm" id="password_confirm" class="form-control" placeholder="পুনরায় লিখুন" required minlength="6" style="padding-right: 42px;">
            <button type="button" onclick="togglePass('password_confirm', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: transparent; border: none; font-size: 1.15rem; cursor: pointer; color: var(--text-muted);">👁️</button>
          </div>
        </div>
      </div>

      <div class="form-group">
        <label for="bio">সংক্ষিপ্ত জীবনবৃত্তান্ত / বায়ো (Short Bio)</label>
        <textarea name="bio" id="bio" class="form-control" placeholder="আপনার পেশা, আগ্রহের ক্ষেত্র বা সংক্ষিপ্ত পরিচয়..."><?= htmlspecialchars($old['bio'] ?? '') ?></textarea>
      </div>

      <div class="form-group">
        <label for="writer_reason">কেন লিখতে চান? (ঐচ্ছিক)</label>
        <textarea name="writer_reason" id="writer_reason" class="form-control" placeholder="Newslensbd-এ কোন বিষয়ে সংবাদ বা কলাম লিখতে আগ্রহী?"><?= htmlspecialchars($old['writer_reason'] ?? '') ?></textarea>
      </div>

      <button type="submit" class="btn-submit">আবেদন ও ওটিপি পাঠান ➔</button>
    </form>

    <div class="bottom-hint">
      পূর্বেই অ্যাকাউন্ট আছে? <a href="/login">লগইন করুন ➔</a>
    </div>
  </div>

  <script>
  function togglePass(id, btn) {
    const el = document.getElementById(id);
    if (!el) return;
    if (el.type === 'password') {
      el.type = 'text';
      btn.textContent = '🙈';
    } else {
      el.type = 'password';
      btn.textContent = '👁️';
    }
  }
  </script>
</body>
</html>

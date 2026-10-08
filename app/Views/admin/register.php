<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>রেজিস্ট্রেশন | Newslensbd অ্যাডমিন প্যানেল</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Hind Siliguri', sans-serif; background: #071630; color: #e2e8f0; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 24px 16px; }
    .register-box { background: #0c1e3d; border: 1px solid #173567; border-radius: 12px; width: 100%; max-width: 480px; padding: 36px 30px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); }
    .brand-header { text-align: center; margin-bottom: 24px; }
    .brand-title { font-size: 1.8rem; font-weight: 800; color: #ffffff; letter-spacing: -0.5px; }
    .brand-title .highlight { color: #0E7A3A; }
    .brand-title .suffix { background: #E31E24; color: #fff; font-size: 0.9rem; padding: 2px 6px; border-radius: 4px; margin-left: 4px; }
    .brand-sub { font-size: 0.92rem; color: #94a3b8; margin-top: 6px; }
    .form-group { margin-bottom: 16px; }
    label { display: block; font-size: 0.9rem; margin-bottom: 6px; color: #cbd5e1; font-weight: 600; }
    input { width: 100%; padding: 11px 14px; background: #081e42; border: 1px solid #173567; border-radius: 6px; color: #fff; font-size: 0.95rem; outline: none; }
    input:focus { border-color: #0E7A3A; }
    .btn-submit { width: 100%; background: #0E7A3A; color: #fff; border: none; padding: 12px; border-radius: 6px; font-size: 1rem; font-weight: 700; cursor: pointer; margin-top: 10px; transition: background 0.2s; }
    .btn-submit:hover { background: #0a5c2b; }
    .error-alert { background: rgba(227, 30, 36, 0.15); border: 1px solid #E31E24; color: #fca5a5; padding: 10px 14px; border-radius: 6px; font-size: 0.88rem; margin-bottom: 16px; text-align: center; }
    .links-footer { margin-top: 20px; font-size: 0.9rem; color: #94a3b8; text-align: center; border-top: 1px solid #173567; padding-top: 16px; }
    .links-footer a { color: #38bdf8; text-decoration: none; font-weight: 600; }
    .links-footer a:hover { text-decoration: underline; }
  </style>
</head>
<body>
  <div class="register-box">
    <div class="brand-header">
      <div style="margin-bottom: 14px;">
        <span style="background: #ffffff; padding: 6px 16px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 3px 10px rgba(0,0,0,0.25);">
          <img src="<?= $appUrl ?>/assets/img/logo.png" alt="Newslensbd" style="height: 36px; max-width: 170px; object-fit: contain; display: block;">
        </span>
      </div>
      <div class="brand-title">NEWS<span class="highlight">LENS</span><span class="suffix">BD</span></div>
      <p class="brand-sub">নতুন অ্যাকাউন্ট নিবন্ধন (Admin & Editor Registration)</p>
    </div>

    <?php if (!empty($error)): ?>
      <div class="error-alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="<?= $appUrl ?>/<?= $adminPath ?>/register" method="post">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

      <div class="form-group">
        <label for="name">সম্পূর্ণ নাম *</label>
        <input type="text" name="name" id="name" required placeholder="আপনার পুরো নাম লিখুন" value="<?= htmlspecialchars($formData['name'] ?? '') ?>" autocomplete="name">
      </div>

      <div class="form-group">
        <label for="phone">মোবাইল / ফোন নম্বর *</label>
        <input type="tel" name="phone" id="phone" required placeholder="যেমন: 01700000000" value="<?= htmlspecialchars($formData['phone'] ?? '') ?>" autocomplete="tel">
      </div>

      <div class="form-group">
        <label for="email">ইমেইল ঠিকানা *</label>
        <input type="email" name="email" id="email" required placeholder="name@domain.com" value="<?= htmlspecialchars($formData['email'] ?? '') ?>" autocomplete="email">
      </div>

      <div class="form-group">
        <label for="password">পাসওয়ার্ড (কমপক্ষে ৬ অক্ষর) *</label>
        <input type="password" name="password" id="password" required placeholder="••••••••" autocomplete="new-password">
      </div>

      <div class="form-group">
        <label for="password_confirm">পাসওয়ার্ড নিশ্চিত করুন (Confirm Password) *</label>
        <input type="password" name="password_confirm" id="password_confirm" required placeholder="••••••••" autocomplete="new-password">
      </div>

      <button type="submit" class="btn-submit">অ্যাকাউন্ট তৈরি করুন ➔</button>
    </form>

    <div class="links-footer">
      ইতিমধ্যে একটি অ্যাকাউন্ট আছে? <a href="<?= $appUrl ?>/<?= $adminPath ?>/login">লগইন করুন ➔</a>
    </div>
  </div>
</body>
</html>

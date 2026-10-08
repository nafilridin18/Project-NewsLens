<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>লগইন | Newslensbd অ্যাডমিন প্যানেল</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Hind Siliguri', sans-serif; background: #070e1b; color: #e2e8f0; display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 16px; }
    .login-box { background: #0f1c30; border: 1px solid #1e293b; border-radius: 12px; width: 100%; max-width: 420px; padding: 36px 30px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); }
    .brand-header { text-align: center; margin-bottom: 24px; }
    .brand-title { font-size: 1.8rem; font-weight: 800; color: #ffffff; letter-spacing: -0.5px; }
    .brand-title .highlight { color: #0E7A3A; }
    .brand-title .suffix { background: #E31E24; color: #fff; font-size: 0.9rem; padding: 2px 6px; border-radius: 4px; margin-left: 4px; }
    .brand-sub { font-size: 0.9rem; color: #94a3b8; margin-top: 4px; }
    .form-group { margin-bottom: 18px; }
    label { display: block; font-size: 0.9rem; margin-bottom: 6px; color: #cbd5e1; }
    input { width: 100%; padding: 12px 14px; background: #070e1b; border: 1px solid #1e293b; border-radius: 6px; color: #fff; font-size: 0.95rem; outline: none; }
    input:focus { border-color: #0E7A3A; }
    .btn-submit { width: 100%; background: #0E7A3A; color: #fff; border: none; padding: 12px; border-radius: 6px; font-size: 1rem; font-weight: 700; cursor: pointer; margin-top: 8px; transition: background 0.2s; }
    .btn-submit:hover { background: #0a5c2b; }
    .error-alert { background: rgba(227, 30, 36, 0.15); border: 1px solid #E31E24; color: #fca5a5; padding: 10px; border-radius: 6px; font-size: 0.88rem; margin-bottom: 16px; text-align: center; }
    .success-alert { background: rgba(34, 197, 94, 0.15); border: 1px solid #22c55e; color: #86efac; padding: 10px; border-radius: 6px; font-size: 0.88rem; margin-bottom: 16px; text-align: center; }
    .register-link-row { margin-top: 18px; text-align: center; font-size: 0.9rem; color: #94a3b8; border-top: 1px solid #1e293b; padding-top: 14px; }
    .register-link-row a { color: #38bdf8; text-decoration: none; font-weight: 600; }
    .register-link-row a:hover { text-decoration: underline; }
    .demo-hint { margin-top: 16px; font-size: 0.8rem; color: #64748b; text-align: center; background: #070e1b; padding: 8px; border-radius: 4px; }
  </style>
</head>
<body>
  <div class="login-box">
    <div class="brand-header">
      <div style="margin-bottom: 14px;">
        <span style="background: #ffffff; padding: 6px 16px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; box-shadow: 0 3px 10px rgba(0,0,0,0.25);">
          <img src="<?= $appUrl ?>/assets/img/logo.png" alt="Newslensbd" style="height: 36px; max-width: 170px; object-fit: contain; display: block;">
        </span>
      </div>
      <div class="brand-title">NEWS<span class="highlight">LENS</span><span class="suffix">BD</span></div>
      <p class="brand-sub">সম্পাদনা ও কনটেন্ট ম্যানেজমেন্ট সিস্টেম (CMS)</p>
    </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'registered'): ?>
      <div class="success-alert">✅ রেজিস্ট্রেশন সফলভাবে সম্পন্ন হয়েছে! আপনার ইমেইল ও পাসওয়ার্ড দিয়ে লগইন করুন।</div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
      <div class="error-alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="<?= $appUrl ?>/<?= $adminPath ?>/login" method="post">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
      
      <div class="form-group">
        <label for="email">ইমেইল ঠিকানা</label>
        <input type="email" name="email" id="email" required placeholder="name@newslensbd.com" autocomplete="email">
      </div>

      <div class="form-group">
        <label for="password">পাসওয়ার্ড</label>
        <input type="password" name="password" id="password" required placeholder="••••••••">
      </div>

      <button type="submit" class="btn-submit">প্রবেশ করুন ➔</button>
    </form>

    <div class="register-link-row">
      অ্যাকাউন্ট নেই? <a href="<?= $appUrl ?>/<?= $adminPath ?>/register">নতুন অ্যাকাউন্ট রেজিস্টার করুন ➔</a>
    </div>

    <div class="demo-hint">
      টেস্টিং ক্রেডেনশিয়াল: <code>admin@newslensbd.com</code> / <code>password123</code>
    </div>
  </div>
</body>
</html>

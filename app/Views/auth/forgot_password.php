<!DOCTYPE html>
<html lang="bn" data-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>পাসওয়ার্ড পুনরুদ্ধার | Newslensbd</title>
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
      padding: 20px;
    }
    .form-card {
      width: 100%;
      max-width: 440px;
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      border-radius: 18px;
      box-shadow: 0 20px 45px rgba(0,0,0,0.4);
      padding: 36px 30px;
      text-align: center;
    }
    .brand-logo { font-size: 1.8rem; font-weight: 800; margin-bottom: 6px; }
    .brand-logo .hl { color: var(--color-primary); }
    .brand-logo .badge { background: var(--color-accent); color: #fff; font-size: 0.8rem; padding: 2px 6px; border-radius: 4px; margin-left: 4px; }
    .sub-title { font-size: 0.9rem; color: var(--text-muted); margin-bottom: 24px; }
    .form-group { text-align: left; margin-bottom: 18px; }
    label { display: block; font-size: 0.88rem; font-weight: 600; margin-bottom: 6px; color: var(--text-main); }
    input.form-control {
      width: 100%; padding: 12px 14px; background: var(--bg-input); border: 1px solid var(--border-color);
      border-radius: 8px; color: var(--text-main); font-size: 0.95rem; font-family: inherit; outline: none; transition: 0.2s;
    }
    input.form-control:focus { border-color: var(--color-primary); box-shadow: 0 0 0 3px rgba(14, 122, 58, 0.25); }
    .btn-submit {
      width: 100%; background: var(--color-primary); color: #fff; border: none; padding: 13px;
      border-radius: 8px; font-size: 1rem; font-weight: 700; cursor: pointer; transition: 0.2s;
    }
    .btn-submit:hover { background: #0a5c2b; }
    .alert { padding: 10px 14px; border-radius: 8px; font-size: 0.88rem; margin-bottom: 18px; font-weight: 600; text-align: center; }
    .alert-danger { background: rgba(227, 30, 36, 0.15); border: 1px solid var(--color-accent); color: #fca5a5; }
    .alert-success { background: rgba(14, 122, 58, 0.15); border: 1px solid var(--color-primary); color: #86efac; }
    .bottom-link { margin-top: 20px; font-size: 0.86rem; color: var(--text-muted); }
    .bottom-link a { color: #38bdf8; text-decoration: none; font-weight: 700; }
  </style>
</head>
<body>
  <div class="form-card">
    <div class="brand-logo">NEWS<span class="hl">LENS</span><span class="badge">BD</span></div>
    <p class="sub-title">পাসওয়ার্ড পুনরুদ্ধার করুন</p>

    <div style="font-size: 44px; margin-bottom: 16px;">🔑</div>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
      <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <p style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 20px; text-align: left; line-height: 1.5;">
      আপনার নিবন্ধিত ইমেইল ঠিকানাটি লিখুন। আমরা আপনার পাসওয়ার্ড রিসেট করার জন্য একটি ৬-সংখ্যার ওটিপি কোড পাঠাব।
    </p>

    <form action="/password/forgot" method="post">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
      
      <div class="form-group">
        <label for="email">অ্যাকাউন্ট ইমেইল</label>
        <input type="email" name="email" id="email" class="form-control" placeholder="name@domain.com" required autofocus>
      </div>

      <button type="submit" class="btn-submit">ওটিপি কোড পাঠান ➔</button>
    </form>

    <div class="bottom-link">
      <a href="/user/login">লগইনে ফিরে যান</a>
    </div>
  </div>
</body>
</html>

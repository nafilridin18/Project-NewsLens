<!DOCTYPE html>
<html lang="bn" data-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle ?? 'আবেদন পর্যালোচনায়') ?> | Newslensbd</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg-page: #071630;
      --bg-card: #0b1f44;
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
    .status-card {
      width: 100%;
      max-width: 520px;
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      border-radius: 18px;
      box-shadow: 0 20px 45px rgba(0,0,0,0.4);
      padding: 42px 32px;
      text-align: center;
    }
    .brand-logo { font-size: 1.8rem; font-weight: 800; margin-bottom: 20px; }
    .brand-logo .hl { color: var(--color-primary); }
    .brand-logo .badge { background: var(--color-accent); color: #fff; font-size: 0.8rem; padding: 2px 6px; border-radius: 4px; margin-left: 4px; }
    .status-icon {
      width: 76px;
      height: 76px;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 34px;
      margin-bottom: 20px;
    }
    .icon-pending {
      background: rgba(245, 158, 11, 0.15);
      border: 2px solid #f59e0b;
      color: #fbbf24;
    }
    .icon-rejected {
      background: rgba(227, 30, 36, 0.15);
      border: 2px solid var(--color-accent);
      color: #fca5a5;
    }
    h2 { font-size: 1.45rem; margin-bottom: 12px; }
    p { color: var(--text-muted); font-size: 0.95rem; line-height: 1.6; margin-bottom: 24px; }
    .info-box {
      background: #06152d;
      border: 1px solid var(--border-color);
      border-radius: 10px;
      padding: 16px;
      text-align: left;
      margin-bottom: 24px;
      font-size: 0.88rem;
    }
    .info-row { display: flex; justify-content: space-between; margin-bottom: 8px; }
    .info-row:last-child { margin-bottom: 0; }
    .info-label { color: var(--text-muted); }
    .info-value { color: var(--text-main); font-weight: 600; }
    .badge-status {
      display: inline-block;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 700;
    }
    .badge-pending { background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid #f59e0b; }
    .badge-rejected { background: rgba(227, 30, 36, 0.2); color: #fca5a5; border: 1px solid var(--color-accent); }
    .reason-box {
      background: rgba(227, 30, 36, 0.1);
      border-left: 4px solid var(--color-accent);
      padding: 12px;
      border-radius: 4px;
      text-align: left;
      margin-bottom: 20px;
      font-size: 0.9rem;
      color: #fca5a5;
    }
    .btn-logout {
      background: transparent;
      border: 1px solid var(--border-color);
      color: var(--text-muted);
      padding: 10px 20px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      font-size: 0.9rem;
      transition: all 0.2s;
      display: inline-block;
    }
    .btn-logout:hover {
      color: #fff;
      border-color: #fff;
    }
    .btn-refresh {
      background: var(--color-primary);
      color: #fff;
      padding: 10px 20px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 700;
      font-size: 0.9rem;
      display: inline-block;
      margin-right: 10px;
    }
  </style>
</head>
<body>

  <div class="status-card">
    <div class="brand-logo">NEWS<span class="hl">LENS</span><span class="badge">BD</span></div>

    <?php if (($user['status'] ?? '') === 'rejected'): ?>
      <div class="status-icon icon-rejected">✕</div>
      <h2 style="color: #fca5a5;">আবেদনটি গৃহীত হয়নি</h2>
      <p>দুঃখিত, আপনার লেখক হিসেবে নিবন্ধনের আবেদনটি সম্পাদকীয় পরিষদ কর্তৃক এই মুহূর্তে অনুমোদন করা সম্ভব হয়নি।</p>

      <?php if (!empty($user['rejected_reason'])): ?>
        <div class="reason-box">
          <strong>বাতিলের কারণ:</strong> <?= htmlspecialchars($user['rejected_reason']) ?>
        </div>
      <?php endif; ?>
    <?php else: ?>
      <div class="status-icon icon-pending">⏳</div>
      <h2>আবেদন পর্যালোচনায় রয়েছে</h2>
      <p>আপনার লেখক আবেদনটি সফলভাবে জমা হয়েছে এবং বর্তমানে অ্যাডমিন ও সম্পাদকীয় টিমের পর্যালোচনার অপেক্ষায় রয়েছে। অনুমোদন সম্পন্ন হলে আপনার ইমেইলে নোটিফিকেশন পাঠানো হবে।</p>
    <?php endif; ?>

    <div class="info-box">
      <div class="info-row">
        <span class="info-label">আবেদনকারীর নাম:</span>
        <span class="info-value"><?= htmlspecialchars($user['name'] ?? '') ?></span>
      </div>
      <div class="info-row">
        <span class="info-label">ইমেইল:</span>
        <span class="info-value"><?= htmlspecialchars($user['email'] ?? '') ?></span>
      </div>
      <div class="info-row">
        <span class="info-label">বর্তমান অবস্থা:</span>
        <span class="info-value">
          <?php if (($user['status'] ?? '') === 'rejected'): ?>
            <span class="badge-status badge-rejected">প্রত্যাখ্যাত (Rejected)</span>
          <?php else: ?>
            <span class="badge-status badge-pending">অপেক্ষমান (Pending Approval)</span>
          <?php endif; ?>
        </span>
      </div>
    </div>

    <div>
      <a href="/writer/pending" class="btn-refresh">🔄 স্ট্যাটাস রিফ্রেশ করুন</a>
      <a href="/logout" class="btn-logout">লগআউট ➔</a>
    </div>
  </div>

</body>
</html>

<?php
$appUrl = rtrim($appUrl ?? $config['app']['url'] ?? '', '/');
?>
<!DOCTYPE html>
<html lang="bn" data-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>লেখক নিবন্ধন তথ্য | Newslensbd</title>
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
    [data-theme="light"] {
      --bg-page: #f8fafc;
      --bg-card: #ffffff;
      --border-color: #cbd5e1;
      --text-main: #0f172a;
      --text-muted: #64748b;
      --color-primary: #0E7A3A;
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
      transition: all 0.3s;
    }
    .notice-card {
      width: 100%;
      max-width: 540px;
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      border-radius: 20px;
      box-shadow: 0 20px 45px rgba(0,0,0,0.4);
      padding: 40px 32px;
      text-align: center;
    }
    .icon-badge {
      font-size: 50px;
      margin-bottom: 16px;
    }
    h1 {
      font-size: 1.5rem;
      font-weight: 800;
      margin-bottom: 12px;
    }
    p {
      color: var(--text-muted);
      font-size: 0.95rem;
      line-height: 1.6;
      margin-bottom: 24px;
    }
    .btn-login {
      display: inline-block;
      background: var(--color-primary);
      color: #fff;
      padding: 12px 24px;
      border-radius: 10px;
      text-decoration: none;
      font-weight: 700;
      transition: background 0.2s;
    }
    .btn-login:hover {
      background: #0a5c2b;
    }
  </style>
</head>
<body>
  <div class="notice-card">
    <div class="icon-badge">🛡️</div>
    <h1 data-bn="পাবলিক লেখক নিবন্ধন বন্ধ রয়েছে" data-en="Public Writer Registration is Closed">পাবলিক লেখক নিবন্ধন বন্ধ রয়েছে</h1>
    <p data-bn="নিউজলেন্সবিডি-তে লেখক অ্যাকাউন্ট শুধুমাত্র অ্যাডমিন কর্তৃপক্ষ সরাসরি তৈরি ও অনুমোদন করে থাকেন। আপনার ইতোমধ্যে লেখক অ্যাকাউন্ট থাকলে নিচে ক্লিক করে লেখক লগইন পোর্টালে প্রবেশ করুন।" data-en="Writer accounts at NewsLensBD are exclusively provisioned and managed by the Administration. If you already have a writer account, please proceed to the dedicated Writer Login Portal.">
      নিউজলেন্সবিডি-তে লেখক অ্যাকাউন্ট শুধুমাত্র অ্যাডমিন কর্তৃপক্ষ সরাসরি তৈরি ও অনুমোদন করে থাকেন। আপনার ইতোমধ্যে লেখক অ্যাকাউন্ট থাকলে নিচে ক্লিক করে লেখক লগইন পোর্টালে প্রবেশ করুন।
    </p>
    <a href="<?= $appUrl ?>/writer/login" class="btn-login" data-bn="লেখক লগইন পোর্টালে যান ➔" data-en="Go to Writer Login Portal ➔">
      লেখক লগইন পোর্টালে যান ➔
    </a>
  </div>
</body>
</html>

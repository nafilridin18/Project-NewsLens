<!DOCTYPE html>
<html lang="bn" data-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle ?? 'ওটিপি যাচাই') ?> | Newslensbd</title>
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
    .otp-card {
      width: 100%;
      max-width: 480px;
      background: var(--bg-card);
      border: 1px solid var(--border-color);
      border-radius: 18px;
      box-shadow: 0 20px 45px rgba(0,0,0,0.4);
      padding: 40px 32px;
      text-align: center;
    }
    .brand-logo {
      font-size: 1.8rem;
      font-weight: 800;
      letter-spacing: -0.5px;
      margin-bottom: 6px;
    }
    .brand-logo .hl { color: var(--color-primary); }
    .brand-logo .badge {
      background: var(--color-accent);
      color: #fff;
      font-size: 0.8rem;
      padding: 2px 6px;
      border-radius: 4px;
      margin-left: 4px;
      vertical-align: middle;
    }
    .sub-title {
      font-size: 0.92rem;
      color: var(--text-muted);
      margin-bottom: 24px;
    }
    .icon-badge {
      width: 68px;
      height: 68px;
      background: rgba(14, 122, 58, 0.15);
      border: 2px solid var(--color-primary);
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 30px;
      margin-bottom: 18px;
    }
    .alert {
      padding: 12px 14px;
      border-radius: 8px;
      font-size: 0.9rem;
      margin-bottom: 20px;
      text-align: center;
      font-weight: 600;
    }
    .alert-danger {
      background: rgba(227, 30, 36, 0.15);
      border: 1px solid var(--color-accent);
      color: #fca5a5;
    }
    .alert-success {
      background: rgba(14, 122, 58, 0.15);
      border: 1px solid var(--color-primary);
      color: #86efac;
    }
    .email-display {
      background: var(--bg-input);
      border: 1px dashed var(--border-color);
      border-radius: 8px;
      padding: 10px 14px;
      font-size: 0.9rem;
      color: #38bdf8;
      font-weight: 700;
      margin-bottom: 24px;
      word-break: break-all;
    }
    .otp-input-wrap {
      margin-bottom: 24px;
    }
    .otp-input {
      width: 100%;
      max-width: 280px;
      padding: 14px;
      font-size: 2rem;
      font-weight: 800;
      letter-spacing: 12px;
      text-align: center;
      background: var(--bg-input);
      border: 2px solid var(--border-color);
      border-radius: 10px;
      color: var(--text-main);
      outline: none;
      transition: all 0.2s ease;
      font-family: inherit;
    }
    .otp-input:focus {
      border-color: var(--color-primary);
      box-shadow: 0 0 0 4px rgba(14, 122, 58, 0.25);
    }
    .btn-submit {
      width: 100%;
      background: var(--color-primary);
      color: #fff;
      border: none;
      padding: 14px;
      border-radius: 10px;
      font-size: 1.05rem;
      font-weight: 700;
      cursor: pointer;
      box-shadow: 0 4px 14px rgba(14, 122, 58, 0.35);
      transition: all 0.2s ease;
    }
    .btn-submit:hover {
      background: #0a5c2b;
      transform: translateY(-1px);
    }
    .resend-box {
      margin-top: 24px;
      font-size: 0.88rem;
      color: var(--text-muted);
      border-top: 1px solid var(--border-color);
      padding-top: 18px;
    }
    .btn-resend {
      background: transparent;
      border: none;
      color: #38bdf8;
      font-weight: 700;
      cursor: pointer;
      font-size: 0.9rem;
      text-decoration: underline;
      padding: 4px 8px;
    }
    .btn-resend:disabled {
      color: var(--text-muted);
      cursor: not-allowed;
      text-decoration: none;
      opacity: 0.6;
    }
  </style>
</head>
<body>

  <div class="otp-card">
    <div class="brand-logo">NEWS<span class="hl">LENS</span><span class="badge">BD</span></div>
    <p class="sub-title">নিরাপদ ইমেইল ওটিপি যাচাইকরণ</p>

    <div class="icon-badge">✉️</div>

    <h2 style="font-size: 1.3rem; margin-bottom: 8px;">৬-সংখ্যার কোড প্রবেশ করান</h2>
    <p style="font-size: 0.88rem; color: var(--text-muted); margin-bottom: 12px;">
      আপনার নিচের ইমেইল ঠিকানায় একটি ওটিপি কোড পাঠানো হয়েছে:
    </p>
    <div class="email-display"><?= htmlspecialchars($email) ?></div>

    <!-- Master Testing OTP Badge -->
    <div style="background: rgba(14, 122, 58, 0.18); border: 2px dashed #10b981; color: #86efac; padding: 14px 18px; border-radius: 12px; margin-bottom: 20px; text-align: center; cursor: pointer;" onclick="document.getElementById('otp-code').value='123456'" title="ক্লিক করলেই কোড বক্সে বসে যাবে">
      <div style="font-size: 0.86rem; color: #a7f3d0; font-weight: 700; margin-bottom: 4px;">🧪 টেস্ট ওটিপি কোড (Master Test OTP):</div>
      <div style="font-size: 1.85rem; font-weight: 800; color: #ffffff; letter-spacing: 8px; text-shadow: 0 0 10px rgba(16, 185, 129, 0.5);">123456</div>
      <div style="font-size: 0.78rem; color: #94a3b8; margin-top: 4px;">👆 কোডের ওপর ক্লিক করলেই সরাসরি বক্সে বসে যাবে</div>
    </div>

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
      <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <div id="ajax-alert" style="display: none;" class="alert"></div>

    <form action="/otp/verify" method="post" id="otp-form">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
      <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>" id="otp-email">
      <input type="hidden" name="purpose" value="<?= htmlspecialchars($purpose) ?>" id="otp-purpose">

      <div class="otp-input-wrap">
        <input type="text" name="code" class="otp-input" id="otp-code" maxlength="6" inputmode="numeric" pattern="[0-9]*" placeholder="••••••" required autofocus autocomplete="one-time-code">
      </div>

      <button type="submit" class="btn-submit">যাচাই সম্পন্ন করুন ➔</button>
    </form>

    <div class="resend-box">
      <span id="countdown-text">কোড পুনরায় পাঠাতে অপেক্ষা করুন: <strong id="timer-sec" style="color: #facc15;">60</strong> সেকেন্ড</span>
      <div style="margin-top: 8px;">
        <button type="button" class="btn-resend" id="btn-resend" disabled>পুনরায় কোড পাঠান</button>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      let timeLeft = 60;
      const timerSec = document.getElementById('timer-sec');
      const countdownText = document.getElementById('countdown-text');
      const resendBtn = document.getElementById('btn-resend');
      const ajaxAlert = document.getElementById('ajax-alert');
      const email = document.getElementById('otp-email').value;
      const purpose = document.getElementById('otp-purpose').value;
      const codeInput = document.getElementById('otp-code');

      // Auto-filter non-digits
      codeInput.addEventListener('input', (e) => {
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
      });

      // Countdown timer logic
      const timerInterval = setInterval(() => {
        timeLeft--;
        if (timeLeft <= 0) {
          clearInterval(timerInterval);
          countdownText.innerHTML = 'কোড পাননি? এখনই পুনরায় অনুরোধ করতে পারেন:';
          resendBtn.disabled = false;
        } else {
          timerSec.textContent = timeLeft;
        }
      }, 1000);

      // Resend OTP via AJAX
      resendBtn.addEventListener('click', async () => {
        resendBtn.disabled = true;
        resendBtn.textContent = 'পাঠানো হচ্ছে...';

        try {
          const formData = new FormData();
          formData.append('email', email);
          formData.append('purpose', purpose);

          const res = await fetch('/otp/resend', {
            method: 'POST',
            body: formData
          });
          const data = await res.json();

          ajaxAlert.style.display = 'block';
          if (data.success) {
            ajaxAlert.className = 'alert alert-success';
            ajaxAlert.textContent = data.message;
            timeLeft = data.wait_seconds || 60;
            countdownText.innerHTML = `কোড পুনরায় পাঠাতে অপেক্ষা করুন: <strong id="timer-sec" style="color: #facc15;">${timeLeft}</strong> সেকেন্ড`;
            resendBtn.textContent = 'পুনরায় কোড পাঠান';
            // Restart countdown
            const newInterval = setInterval(() => {
              timeLeft--;
              const secEl = document.getElementById('timer-sec');
              if (secEl) secEl.textContent = timeLeft;
              if (timeLeft <= 0) {
                clearInterval(newInterval);
                countdownText.innerHTML = 'কোড পাননি? পুনরায় অনুরোধ করতে পারেন:';
                resendBtn.disabled = false;
              }
            }, 1000);
          } else {
            ajaxAlert.className = 'alert alert-danger';
            ajaxAlert.textContent = data.message;
            resendBtn.disabled = false;
            resendBtn.textContent = 'পুনরায় কোড পাঠান';
          }
        } catch (err) {
          ajaxAlert.style.display = 'block';
          ajaxAlert.className = 'alert alert-danger';
          ajaxAlert.textContent = 'নেটওয়ার্ক সমস্যা। পুনরায় চেষ্টা করুন।';
          resendBtn.disabled = false;
          resendBtn.textContent = 'পুনরায় কোড পাঠান';
        }
      });
    });
  </script>
</body>
</html>

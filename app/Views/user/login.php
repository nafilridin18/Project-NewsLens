<?php
$appUrl = rtrim($config['app']['url'] ?? '', '/');
$error = $error ?? null;
$success = $success ?? null;
?>
<div class="container" style="max-width: 480px; margin: 40px auto 80px; padding: 0 16px;">
  <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 36px 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.06);">
    <div style="text-align: center; margin-bottom: 24px;">
      <h1 style="font-size: 1.6rem; font-weight: 800; color: #0f172a; margin-bottom: 6px;">অ্যাকাউন্টে লগইন</h1>
      <p style="color: #64748b; font-size: 0.92rem;">আপনার ইমেইল ও পাসওয়ার্ড দিয়ে প্রবেশ করুন।</p>
    </div>

    <?php if (!empty($success)): ?>
      <div style="background: #ecfdf5; border: 1px solid #10b981; color: #059669; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; font-size: 0.9rem;">
        ✓ <?= htmlspecialchars($success) ?>
      </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
      <div style="background: #fef2f2; border: 1px solid #f87171; color: #dc2626; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; font-size: 0.9rem;">
        ✕ <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="<?= $appUrl ?>/login">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

      <div style="margin-bottom: 18px;">
        <label style="display: block; font-weight: 700; margin-bottom: 6px; font-size: 0.9rem; color: #334155;">ইমেইল ঠিকানা</label>
        <input type="email" name="email" required placeholder="yourname@domain.com" style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; box-sizing: border-box; outline: none;">
      </div>

      <div style="margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
          <label style="font-weight: 700; font-size: 0.9rem; color: #334155;">পাসওয়ার্ড</label>
          <a href="<?= $appUrl ?>/forgot-password" style="color: #0284c7; font-size: 0.85rem; text-decoration: none; font-weight: 600;">পাসওয়ার্ড ভুলে গেছেন?</a>
        </div>
        <input type="password" name="password" required placeholder="••••••••" style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; box-sizing: border-box; outline: none;">
      </div>

      <button type="submit" style="width: 100%; background: #0E7A3A; color: #fff; border: none; padding: 13px; border-radius: 8px; font-size: 1rem; font-weight: 700; cursor: pointer; transition: background 0.2s;">
        লগইন করুন ➔
      </button>
    </form>

    <div style="text-align: center; margin-top: 24px; padding-top: 18px; border-top: 1px solid #f1f5f9; font-size: 0.9rem; color: #64748b;">
      নতুন পাঠক? <a href="<?= $appUrl ?>/register" style="color: #0E7A3A; font-weight: 700; text-decoration: none;">অ্যাকাউন্ট তৈরি করুন</a>
      <div style="margin-top: 8px;">
        লেখক হতে চান? <a href="<?= $appUrl ?>/writer/register" style="color: #0284c7; font-weight: 700; text-decoration: none;">লেখক হিসেবে আবেদন করুন ↗</a>
      </div>
    </div>
  </div>
</div>

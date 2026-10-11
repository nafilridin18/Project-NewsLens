<?php
$appUrl = rtrim($config['app']['url'] ?? '', '/');
$old = $old ?? [];
$error = $error ?? null;
?>
<div class="container" style="max-width: 520px; margin: 40px auto 80px; padding: 0 16px;">
  <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; padding: 36px 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.06);">
    <div style="text-align: center; margin-bottom: 24px;">
      <h1 style="font-size: 1.6rem; font-weight: 800; color: #0f172a; margin-bottom: 6px;">পাঠক অ্যাকাউন্ট তৈরি করুন</h1>
      <p style="color: #64748b; font-size: 0.92rem;">সংবাদ সংরক্ষণ, নোটিফিকেশন ও ব্যক্তিকেন্দ্রিক অভিজ্ঞতার জন্য সাইন আপ করুন।</p>
    </div>

    <?php if (!empty($error)): ?>
      <div style="background: #fef2f2; border: 1px solid #f87171; color: #dc2626; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; font-size: 0.9rem;">
        ✕ <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="<?= $appUrl ?>/register">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

      <div style="margin-bottom: 18px;">
        <label style="display: block; font-weight: 700; margin-bottom: 6px; font-size: 0.9rem; color: #334155;">আপনার পূর্ণ নাম *</label>
        <input type="text" name="name" required value="<?= htmlspecialchars($old['name'] ?? '') ?>" placeholder="উদাঃ মোঃ সাব্বির রহমান" style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; box-sizing: border-box; outline: none;">
      </div>

      <div style="margin-bottom: 18px;">
        <label style="display: block; font-weight: 700; margin-bottom: 6px; font-size: 0.9rem; color: #334155;">ইমেইল ঠিকানা *</label>
        <input type="email" name="email" required value="<?= htmlspecialchars($old['email'] ?? '') ?>" placeholder="yourname@domain.com" style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; box-sizing: border-box; outline: none;">
      </div>

      <div style="margin-bottom: 18px;">
        <label style="display: block; font-weight: 700; margin-bottom: 6px; font-size: 0.9rem; color: #334155;">পাসওয়ার্ড (কমপক্ষে ৬ অক্ষর) *</label>
        <input type="password" name="password" required minlength="6" placeholder="••••••••" style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; box-sizing: border-box; outline: none;">
      </div>

      <div style="margin-bottom: 24px;">
        <label style="display: block; font-weight: 700; margin-bottom: 6px; font-size: 0.9rem; color: #334155;">পাসওয়ার্ড নিশ্চিত করুন *</label>
        <input type="password" name="password_confirm" required minlength="6" placeholder="••••••••" style="width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 0.95rem; box-sizing: border-box; outline: none;">
      </div>

      <button type="submit" style="width: 100%; background: #0E7A3A; color: #fff; border: none; padding: 13px; border-radius: 8px; font-size: 1rem; font-weight: 700; cursor: pointer; transition: background 0.2s;">
        নিবন্ধন করুন ও ওটিপি পাঠান ➔
      </button>
    </form>

    <div style="text-align: center; margin-top: 24px; padding-top: 18px; border-top: 1px solid #f1f5f9; font-size: 0.9rem; color: #64748b;">
      ইতিমধ্যে অ্যাকাউন্ট আছে? <a href="<?= $appUrl ?>/login" style="color: #0E7A3A; font-weight: 700; text-decoration: none;">লগইন করুন</a>
      <div style="margin-top: 8px;">
        লেখা প্রকাশ করতে চান? <a href="<?= $appUrl ?>/writer/register" style="color: #0284c7; font-weight: 700; text-decoration: none;">লেখক হিসেবে আবেদন করুন ↗</a>
      </div>
    </div>
  </div>
</div>

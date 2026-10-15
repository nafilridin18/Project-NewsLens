<?php
$appUrl = rtrim($config['app']['url'] ?? '', '/');
$old = $old ?? [];
$error = $error ?? null;
?>
<div class="auth-page-container" style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 40px 16px;">
  <div class="auth-card" style="width: 100%; max-width: 520px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 38px 32px; box-shadow: 0 15px 40px rgba(8, 30, 66, 0.12); position: relative; overflow: hidden;">
    
    <!-- Decorative top accent bar -->
    <div style="position: absolute; top: 0; left: 0; right: 0; height: 5px; background: linear-gradient(90deg, var(--color-secondary), #10b981, var(--color-accent-red));"></div>

    <!-- Header & Badge -->
    <div style="text-align: center; margin-bottom: 26px;">
      <div style="display: inline-flex; align-items: center; justify-content: center; width: 56px; height: 56px; border-radius: 16px; background: rgba(0, 106, 56, 0.12); color: var(--color-secondary); font-size: 26px; margin-bottom: 14px; border: 1px solid rgba(0, 106, 56, 0.25);">
        📝
      </div>
      <h1 style="font-size: 1.65rem; font-weight: 800; color: var(--text-main); margin-bottom: 6px; letter-spacing: -0.3px;">পাঠক অ্যাকাউন্ট তৈরি করুন</h1>
      <p style="color: var(--text-muted); font-size: 0.94rem;">সংবাদ সংরক্ষণ, গুরুত্বপূর্ণ নোটিফিকেশন ও দ্রুততম আপডেটের জন্য যোগ দিন</p>
    </div>

    <!-- Reader Account Header Notice -->
    <div style="background: var(--bg-alt); padding: 8px 14px; border-radius: 10px; margin-bottom: 24px; border: 1px solid var(--border-color); text-align: center; font-size: 0.88rem; color: var(--text-muted); font-weight: 600;">
      <span>👤 সাধারণ পাঠক হিসেবে বিনামূল্যে নিবন্ধন করুন</span>
    </div>

    <!-- Error Alert -->
    <?php if (!empty($error)): ?>
      <div style="background: rgba(225, 27, 34, 0.12); border: 1px solid var(--color-accent-red); color: #f87171; padding: 12px 16px; border-radius: 10px; margin-bottom: 22px; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
        <span style="font-size: 1.1rem;">✕</span>
        <div><?= htmlspecialchars($error) ?></div>
      </div>
    <?php endif; ?>

    <form method="POST" action="<?= $appUrl ?>/register" id="registerForm">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

      <!-- Full Name -->
      <div style="margin-bottom: 18px;">
        <label for="reg-name" style="display: block; font-weight: 700; margin-bottom: 8px; font-size: 0.9rem; color: var(--text-main);">
          আপনার পূর্ণ নাম <span style="color: var(--color-accent-red);">*</span>
        </label>
        <div style="position: relative;">
          <span style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 1rem; color: var(--text-muted); pointer-events: none;">👤</span>
          <input 
            type="text" 
            name="name" 
            id="reg-name" 
            required 
            value="<?= htmlspecialchars($old['name'] ?? '') ?>"
            placeholder="উদাঃ মোঃ নাফিল উদ্দিন" 
            autocomplete="name"
            style="width: 100%; padding: 12px 14px 12px 42px; background: var(--bg-alt); border: 1.5px solid var(--border-color); border-radius: 10px; font-size: 0.95rem; color: var(--text-main); outline: none; transition: border-color 0.2s, box-shadow 0.2s;"
            onfocus="this.style.borderColor='var(--color-secondary)'; this.style.boxShadow='0 0 0 3px rgba(0, 106, 56, 0.2)';"
            onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none';"
          >
        </div>
      </div>

      <!-- Email Input -->
      <div style="margin-bottom: 18px;">
        <label for="reg-email" style="display: block; font-weight: 700; margin-bottom: 8px; font-size: 0.9rem; color: var(--text-main);">
          ইমেইল ঠিকানা <span style="color: var(--color-accent-red);">*</span>
        </label>
        <div style="position: relative;">
          <span style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 1rem; color: var(--text-muted); pointer-events: none;">📧</span>
          <input 
            type="email" 
            name="email" 
            id="reg-email" 
            required 
            value="<?= htmlspecialchars($old['email'] ?? '') ?>"
            placeholder="yourname@gmail.com" 
            autocomplete="email"
            style="width: 100%; padding: 12px 14px 12px 42px; background: var(--bg-alt); border: 1.5px solid var(--border-color); border-radius: 10px; font-size: 0.95rem; color: var(--text-main); outline: none; transition: border-color 0.2s, box-shadow 0.2s;"
            onfocus="this.style.borderColor='var(--color-secondary)'; this.style.boxShadow='0 0 0 3px rgba(0, 106, 56, 0.2)';"
            onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none';"
          >
        </div>
      </div>

      <!-- Password Input with Toggle -->
      <div style="margin-bottom: 18px;">
        <label for="reg-password" style="display: block; font-weight: 700; margin-bottom: 8px; font-size: 0.9rem; color: var(--text-main);">
          পাসওয়ার্ড (কমপক্ষে ৬ অক্ষর) <span style="color: var(--color-accent-red);">*</span>
        </label>
        <div style="position: relative;">
          <span style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 1rem; color: var(--text-muted); pointer-events: none;">🔒</span>
          <input 
            type="password" 
            name="password" 
            id="reg-password" 
            required 
            minlength="6"
            placeholder="••••••••" 
            autocomplete="new-password"
            style="width: 100%; padding: 12px 44px 12px 42px; background: var(--bg-alt); border: 1.5px solid var(--border-color); border-radius: 10px; font-size: 0.95rem; color: var(--text-main); outline: none; transition: border-color 0.2s, box-shadow 0.2s;"
            onfocus="this.style.borderColor='var(--color-secondary)'; this.style.boxShadow='0 0 0 3px rgba(0, 106, 56, 0.2)';"
            onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none';"
          >
          <button 
            type="button" 
            onclick="togglePasswordVisibility('reg-password', this)"
            title="পাসওয়ার্ড দেখুন বা লুকান"
            aria-label="পাসওয়ার্ড দেখুন বা লুকান"
            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: transparent; border: none; font-size: 1.15rem; cursor: pointer; color: var(--text-muted); padding: 4px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center;"
          >
            👁️
          </button>
        </div>
      </div>

      <!-- Confirm Password Input with Toggle -->
      <div style="margin-bottom: 24px;">
        <label for="reg-confirm-password" style="display: block; font-weight: 700; margin-bottom: 8px; font-size: 0.9rem; color: var(--text-main);">
          পাসওয়ার্ড নিশ্চিত করুন <span style="color: var(--color-accent-red);">*</span>
        </label>
        <div style="position: relative;">
          <span style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 1rem; color: var(--text-muted); pointer-events: none;">🛡️</span>
          <input 
            type="password" 
            name="password_confirm" 
            id="reg-confirm-password" 
            required 
            minlength="6"
            placeholder="••••••••" 
            autocomplete="new-password"
            style="width: 100%; padding: 12px 44px 12px 42px; background: var(--bg-alt); border: 1.5px solid var(--border-color); border-radius: 10px; font-size: 0.95rem; color: var(--text-main); outline: none; transition: border-color 0.2s, box-shadow 0.2s;"
            onfocus="this.style.borderColor='var(--color-secondary)'; this.style.boxShadow='0 0 0 3px rgba(0, 106, 56, 0.2)';"
            onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none';"
          >
          <button 
            type="button" 
            onclick="togglePasswordVisibility('reg-confirm-password', this)"
            title="পাসওয়ার্ড দেখুন বা লুকান"
            aria-label="পাসওয়ার্ড দেখুন বা লুকান"
            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: transparent; border: none; font-size: 1.15rem; cursor: pointer; color: var(--text-muted); padding: 4px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center;"
          >
            👁️
          </button>
        </div>
      </div>

      <!-- Submit Button -->
      <button 
        type="submit" 
        id="regSubmitBtn"
        style="width: 100%; background: linear-gradient(135deg, var(--color-secondary) 0%, #0E7A3A 100%); color: #ffffff; border: none; padding: 14px; border-radius: 11px; font-size: 1.05rem; font-weight: 800; cursor: pointer; transition: all 0.25s ease; box-shadow: 0 4px 15px rgba(0, 106, 56, 0.35); display: inline-flex; align-items: center; justify-content: center; gap: 8px;"
        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 24px rgba(0, 106, 56, 0.45)';"
        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0, 106, 56, 0.35)';"
      >
        <span>নিবন্ধন করুন ও ওটিপি পাঠান</span>
        <span style="font-size: 1.1rem;" class="btn-arrow">➔</span>
      </button>
    </form>

    <!-- Footer Links -->
    <div style="text-align: center; margin-top: 28px; padding-top: 20px; border-top: 1px solid var(--border-color); font-size: 0.92rem; color: var(--text-muted);">
      <p style="margin-bottom: 0;">
        ইতিমধ্যে অ্যাকাউন্ট আছে? <a href="<?= $appUrl ?>/login" style="color: var(--color-secondary); font-weight: 800; text-decoration: none;" onmouseover="this.style.textDecoration='underline';" onmouseout="this.style.textDecoration='none';">লগইন করুন ➔</a>
      </p>
    </div>

  </div>
</div>

<script>
function togglePasswordVisibility(inputId, btn) {
  const input = document.getElementById(inputId);
  if (!input) return;
  if (input.type === 'password') {
    input.type = 'text';
    btn.textContent = '🙈';
    btn.style.color = 'var(--color-secondary)';
  } else {
    input.type = 'password';
    btn.textContent = '👁️';
    btn.style.color = 'var(--text-muted)';
  }
}
</script>

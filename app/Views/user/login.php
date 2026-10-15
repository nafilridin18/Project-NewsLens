<?php
$appUrl = rtrim($config['app']['url'] ?? '', '/');
$error = $error ?? null;
$success = $success ?? null;
?>
<div class="auth-page-container" style="min-height: 80vh; display: flex; align-items: center; justify-content: center; padding: 40px 16px;">
  <div class="auth-card" style="width: 100%; max-width: 480px; background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 20px; padding: 38px 32px; box-shadow: 0 15px 40px rgba(8, 30, 66, 0.12); position: relative; overflow: hidden;">
    
    <!-- Decorative top accent bar -->
    <div style="position: absolute; top: 0; left: 0; right: 0; height: 5px; background: linear-gradient(90deg, var(--color-secondary), #10b981, var(--color-accent-red));"></div>

    <!-- Header & Badge -->
    <div style="text-align: center; margin-bottom: 28px;">
      <div style="display: inline-flex; align-items: center; justify-content: center; width: 56px; height: 56px; border-radius: 16px; background: rgba(0, 106, 56, 0.12); color: var(--color-secondary); font-size: 26px; margin-bottom: 14px; border: 1px solid rgba(0, 106, 56, 0.25);">
        🔐
      </div>
      <h1 style="font-size: 1.65rem; font-weight: 800; color: var(--text-main); margin-bottom: 6px; letter-spacing: -0.3px;">অ্যাকাউন্টে লগইন</h1>
      <p style="color: var(--text-muted); font-size: 0.94rem;">আপনার নিবন্ধিত ইমেইল ও পাসওয়ার্ড দিয়ে প্রবেশ করুন</p>
    </div>

    <!-- Reader Login Notice -->
    <div style="background: var(--bg-alt); padding: 8px 14px; border-radius: 10px; margin-bottom: 24px; border: 1px solid var(--border-color); text-align: center; font-size: 0.88rem; color: var(--text-muted); font-weight: 600;">
      <span>👤 সাধারণ পাঠক একাউন্ট লগইন</span>
    </div>

    <!-- Alerts -->
    <?php if (!empty($success)): ?>
      <div style="background: rgba(16, 185, 129, 0.12); border: 1px solid #10b981; color: #10b981; padding: 12px 16px; border-radius: 10px; margin-bottom: 22px; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
        <span style="font-size: 1.1rem;">✓</span>
        <div><?= htmlspecialchars($success) ?></div>
      </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
      <div style="background: rgba(225, 27, 34, 0.12); border: 1px solid var(--color-accent-red); color: #f87171; padding: 12px 16px; border-radius: 10px; margin-bottom: 22px; font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 8px;">
        <span style="font-size: 1.1rem;">✕</span>
        <div><?= htmlspecialchars($error) ?></div>
      </div>
    <?php endif; ?>

    <form method="POST" action="<?= $appUrl ?>/login" id="loginForm">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

      <!-- Email Input -->
      <div style="margin-bottom: 20px;">
        <label for="login-email" style="display: block; font-weight: 700; margin-bottom: 8px; font-size: 0.9rem; color: var(--text-main);">
          ইমেইল ঠিকানা <span style="color: var(--color-accent-red);">*</span>
        </label>
        <div style="position: relative;">
          <span style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 1rem; color: var(--text-muted); pointer-events: none;">📧</span>
          <input 
            type="email" 
            name="email" 
            id="login-email" 
            required 
            placeholder="example@domain.com" 
            autocomplete="email"
            style="width: 100%; padding: 12px 14px 12px 42px; background: var(--bg-alt); border: 1.5px solid var(--border-color); border-radius: 10px; font-size: 0.95rem; color: var(--text-main); outline: none; transition: border-color 0.2s, box-shadow 0.2s;"
            onfocus="this.style.borderColor='var(--color-secondary)'; this.style.boxShadow='0 0 0 3px rgba(0, 106, 56, 0.2)';"
            onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none';"
          >
        </div>
      </div>

      <!-- Password Input with Toggle -->
      <div style="margin-bottom: 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
          <label for="login-password" style="font-weight: 700; font-size: 0.9rem; color: var(--text-main);">
            পাসওয়ার্ড <span style="color: var(--color-accent-red);">*</span>
          </label>
          <a href="<?= $appUrl ?>/forgot-password" style="color: #0284c7; font-size: 0.85rem; text-decoration: none; font-weight: 700; transition: color 0.2s;" onmouseover="this.style.textDecoration='underline';" onmouseout="this.style.textDecoration='none';">
            পাসওয়ার্ড ভুলে গেছেন?
          </a>
        </div>
        <div style="position: relative;">
          <span style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); font-size: 1rem; color: var(--text-muted); pointer-events: none;">🔒</span>
          <input 
            type="password" 
            name="password" 
            id="login-password" 
            required 
            placeholder="••••••••" 
            autocomplete="current-password"
            style="width: 100%; padding: 12px 44px 12px 42px; background: var(--bg-alt); border: 1.5px solid var(--border-color); border-radius: 10px; font-size: 0.95rem; color: var(--text-main); outline: none; transition: border-color 0.2s, box-shadow 0.2s;"
            onfocus="this.style.borderColor='var(--color-secondary)'; this.style.boxShadow='0 0 0 3px rgba(0, 106, 56, 0.2)';"
            onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none';"
          >
          <button 
            type="button" 
            onclick="togglePasswordVisibility('login-password', this)"
            title="পাসওয়ার্ড দেখুন বা লুকান"
            aria-label="পাসওয়ার্ড দেখুন বা লুকান"
            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: transparent; border: none; font-size: 1.15rem; cursor: pointer; color: var(--text-muted); padding: 4px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center;"
          >
            👁️
          </button>
        </div>
      </div>

      <!-- Remember Me -->
      <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
        <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 0.88rem; color: var(--text-muted); cursor: pointer;">
          <input type="checkbox" name="remember" value="1" style="width: 16px; height: 16px; accent-color: var(--color-secondary); cursor: pointer;">
          <span>আমাকে মনে রাখুন</span>
        </label>
      </div>

      <!-- Submit Button -->
      <button 
        type="submit" 
        id="loginSubmitBtn"
        style="width: 100%; background: linear-gradient(135deg, var(--color-secondary) 0%, #0E7A3A 100%); color: #ffffff; border: none; padding: 14px; border-radius: 11px; font-size: 1.05rem; font-weight: 800; cursor: pointer; transition: all 0.25s ease; box-shadow: 0 4px 15px rgba(0, 106, 56, 0.35); display: inline-flex; align-items: center; justify-content: center; gap: 8px;"
        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 24px rgba(0, 106, 56, 0.45)';"
        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0, 106, 56, 0.35)';"
      >
        <span>লগইন করুন</span>
        <span style="font-size: 1.1rem; transition: transform 0.2s;" class="btn-arrow">➔</span>
      </button>
    </form>

    <!-- Footer Links -->
    <div style="text-align: center; margin-top: 28px; padding-top: 20px; border-top: 1px solid var(--border-color); font-size: 0.92rem; color: var(--text-muted);">
      <p style="margin-bottom: 0;">
        নতুন পাঠক? <a href="<?= $appUrl ?>/register" style="color: var(--color-secondary); font-weight: 800; text-decoration: none;" onmouseover="this.style.textDecoration='underline';" onmouseout="this.style.textDecoration='none';">বিনামূল্যে অ্যাকাউন্ট খুলুন ➔</a>
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

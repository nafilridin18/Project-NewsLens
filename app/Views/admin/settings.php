<?php
$currentLogo = !empty($settings['site_logo']) ? $appUrl . '/' . htmlspecialchars($settings['site_logo']) : $appUrl . '/assets/img/logo.png';
$currentPartnerLogo = !empty($settings['tech_partner_logo']) ? $appUrl . '/' . htmlspecialchars($settings['tech_partner_logo']) : $appUrl . '/assets/img/stratifyx-global.png';
?>
<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>সাইট, ব্র্যান্ডিং ও ফুটার সেটিংস | Newslens Desk</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    .admin-container { max-width: 1000px; margin: 30px auto; padding: 0 20px; }
    .page-title-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .settings-card { background: var(--adm-card-bg); border-radius: 8px; border: 1px solid var(--adm-border); padding: 24px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .settings-card-title { font-size: 1.15rem; font-weight: 700; color: var(--adm-text); margin-bottom: 16px; border-bottom: 1px solid var(--adm-border); padding-bottom: 10px; display: flex; align-items: center; gap: 8px; }
    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
    @media (max-width: 640px) { .form-grid-2 { grid-template-columns: 1fr; } }
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; font-weight: 600; margin-bottom: 6px; color: var(--adm-text); font-size: 0.92rem; }
    .form-control { width: 100%; padding: 10px 14px; background: var(--adm-nav-bg); border: 1px solid var(--adm-border); border-radius: 6px; font-size: 0.95rem; color: var(--adm-text); }
    .form-control:focus { border-color: var(--adm-primary); outline: none; }
    .logo-preview-box { background: rgba(0,0,0,0.15); border: 1px dashed var(--adm-border); border-radius: 6px; padding: 14px; display: inline-flex; align-items: center; gap: 14px; margin-top: 10px; }
    .logo-img-thumb { height: 48px; max-width: 220px; object-fit: contain; background: transparent !important; }
    .btn-submit-fixed { background: var(--adm-primary); color: #fff; padding: 12px 28px; border-radius: 6px; border: none; font-weight: 700; font-size: 1.05rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; }
    .btn-submit-fixed:hover { background: var(--adm-primary-hover); }
    .alert-banner { background: #064e3b; border: 1px solid #059669; color: #a7f3d0; padding: 12px 18px; border-radius: 6px; margin-bottom: 20px; }
  </style>
</head>
<body>

<?php
$activeTab = 'settings';
require __DIR__ . '/partials/header.php';
?>

<main class="admin-container">
  <?php if (isset($_GET['msg']) && $_GET['msg'] === 'updated'): ?>
    <div class="alert-banner">
      ✅ সাইট, লোগো এবং ফুটার সেটিংস সফলভাবে আপডেট ও সংরক্ষণ করা হয়েছে!
    </div>
  <?php endif; ?>
  <?php if (isset($_GET['msg']) && $_GET['msg'] === 'profile_updated'): ?>
    <div class="alert-banner">
      ✅ আপনার অ্যাডমিন নাম ও প্রোফাইল তথ্য সফলভাবে আপডেট করা হয়েছে!
    </div>
  <?php endif; ?>
  <?php if (isset($_GET['err'])): ?>
    <div class="alert-banner" style="background:#7f1d1d;border-color:#b91c1c;color:#fca5a5;">
      <?php 
        if ($_GET['err'] === 'pwd_short') echo '⚠️ পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে।';
        if ($_GET['err'] === 'pwd_mismatch') echo '⚠️ পাসওয়ার্ড এবং নিশ্চিতকরণ পাসওয়ার্ড মেলেনি।';
      ?>
    </div>
  <?php endif; ?>

  <div class="page-title-row">
    <div>
      <h2>⚙️ সাইট ব্র্যান্ডিং, লোগো ও ফুটার তথ্য ব্যবস্থাপনা</h2>
      <p style="color:var(--adm-text-muted);font-size:0.9rem;">মূল পোর্টালের লোগো, পার্টনার লোগো, সম্পাদকীয় তথ্য, সরকারি নিবন্ধন ও যোগাযোগ বিবরণ পরিবর্তন করুন</p>
    </div>
  </div>

  <!-- 0. Admin Profile & Name Change Card (FEATURE: supper admin tar nam change korte parbe) -->
  <div class="settings-card" style="border-left: 4px solid var(--adm-primary);">
    <div class="settings-card-title">👤 অ্যাডমিন প্রোফাইল ও নাম পরিবর্তন (Change Admin Name & Info)</div>
    <form action="<?= $appUrl ?>/<?= $adminPath ?>/profile/update" method="POST">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
      <div class="form-grid-2">
        <div class="form-group">
          <label for="admin_name">আপনার নাম (Admin Name) *</label>
          <input type="text" id="admin_name" name="admin_name" value="<?= htmlspecialchars($user['name'] ?? 'Super Admin') ?>" required class="form-control" placeholder="যেমন: Super Admin বা আপনার নাম">
        </div>
        <div class="form-group">
          <label for="admin_phone">মোবাইল / ফোন নম্বর</label>
          <input type="tel" id="admin_phone" name="admin_phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" class="form-control" placeholder="যেমন: 01700000000">
        </div>
      </div>
      <div class="form-grid-2">
        <div class="form-group">
          <label for="admin_password">নতুন পাসওয়ার্ড (পরিবর্তন করতে চাইলে)</label>
          <input type="password" id="admin_password" name="admin_password" placeholder="নতুন পাসওয়ার্ড দিন" class="form-control">
        </div>
        <div class="form-group">
          <label for="admin_password_confirm">নতুন পাসওয়ার্ড নিশ্চিত করুন</label>
          <input type="password" id="admin_password_confirm" name="admin_password_confirm" placeholder="পুনরায় নতুন পাসওয়ার্ড দিন" class="form-control">
        </div>
      </div>
      <div style="text-align:right;">
        <button type="submit" class="btn-submit-fixed" style="padding:8px 20px;font-size:0.95rem;">
          💾 নাম ও প্রোফাইল আপডেট করুন
        </button>
      </div>
    </form>
  </div>

  <form action="<?= $appUrl ?>/<?= $adminPath ?>/settings" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

    <!-- 1. Site Identity & Main Logo -->
    <div class="settings-card">
      <div class="settings-card-title">📰 ১. পোর্টালের নাম, স্লোগান ও প্রধান লোগো</div>
      
      <div class="form-grid-2">
        <div class="form-group">
          <label for="site_name_bn">ওয়েবসাইটের নাম (বাংলা) *</label>
          <input type="text" id="site_name_bn" name="site_name_bn" value="<?= htmlspecialchars($settings['site_name_bn'] ?? 'নিউজলেন্সবিডি') ?>" required class="form-control">
        </div>
        <div class="form-group">
          <label for="site_name_en">ওয়েবসাইটের নাম (English) *</label>
          <input type="text" id="site_name_en" name="site_name_en" value="<?= htmlspecialchars($settings['site_name_en'] ?? 'Newslensbd') ?>" required class="form-control">
        </div>
      </div>

      <div class="form-grid-2">
        <div class="form-group">
          <label for="tagline_bn">ট্যাগলাইন (বাংলা) *</label>
          <input type="text" id="tagline_bn" name="tagline_bn" value="<?= htmlspecialchars($settings['tagline_bn'] ?? 'সাধারণের বাইরে, সত্যের খোঁজে') ?>" required class="form-control">
        </div>
        <div class="form-group">
          <label for="tagline_en">ট্যাগলাইন (English) *</label>
          <input type="text" id="tagline_en" name="tagline_en" value="<?= htmlspecialchars($settings['tagline_en'] ?? 'News Beyond the Ordinary') ?>" required class="form-control">
        </div>
      </div>

      <div class="form-group">
        <label>প্রধান সাইট লোগো আপলোড (PNG / WebP / SVG):</label>
        <input type="file" name="site_logo_file" accept="image/*" class="form-control">
        
        <div style="margin-top:8px;">
          <label for="site_logo_url" style="font-weight:normal;font-size:0.85rem;color:var(--adm-text-muted);">অথবা লোগোর সরাসরি URL বা স্থানীয় পথ:</label>
          <input type="text" id="site_logo_url" name="site_logo_url" value="<?= htmlspecialchars($settings['site_logo'] ?? 'assets/img/logo.png') ?>" class="form-control">
        </div>

        <div class="logo-preview-box">
          <span style="font-size:0.85rem;color:var(--adm-text-muted);">বর্তমান লোগো প্রিভিউ:</span>
          <img src="<?= $currentLogo ?>" alt="Site Logo" class="logo-img-thumb">
        </div>
      </div>
    </div>

    <!-- 2. Technology Partner Settings (FEATURE REQUEST: Technology Partner logo) -->
    <div class="settings-card">
      <div class="settings-card-title">🤝 ২. টেকনোলজি পার্টনার (Technology Partner) তথ্য ও লোগো</div>

      <div class="form-grid-2">
        <div class="form-group">
          <label for="tech_partner_name">টেকনোলজি পার্টনারের নাম</label>
          <input type="text" id="tech_partner_name" name="tech_partner_name" value="<?= htmlspecialchars($settings['tech_partner_name'] ?? 'Stratifyx Global') ?>" class="form-control">
        </div>
        <div class="form-group">
          <label for="tech_partner_url">পার্টনারের ওয়েবসাইট লিংক</label>
          <input type="url" id="tech_partner_url" name="tech_partner_url" value="<?= htmlspecialchars($settings['tech_partner_url'] ?? 'https://stratifyxglobal.com') ?>" class="form-control">
        </div>
      </div>

      <div class="form-group">
        <label>টেকনোলজি পার্টনার লোগো আপলোড (স্বচ্ছ ব্যাকগ্রাউন্ড যুক্ত PNG):</label>
        <input type="file" name="tech_partner_logo_file" accept="image/*" class="form-control">

        <div style="margin-top:8px;">
          <label for="tech_partner_logo_url" style="font-weight:normal;font-size:0.85rem;color:var(--adm-text-muted);">অথবা পার্টনার লোগো URL বা স্থানীয় পথ:</label>
          <input type="text" id="tech_partner_logo_url" name="tech_partner_logo_url" value="<?= htmlspecialchars($settings['tech_partner_logo'] ?? 'assets/img/stratifyx-global.png') ?>" class="form-control">
        </div>

        <div class="logo-preview-box">
          <span style="font-size:0.85rem;color:var(--adm-text-muted);">বর্তমান পার্টনার লোগো:</span>
          <img src="<?= $currentPartnerLogo ?>" alt="Tech Partner" class="logo-img-thumb" style="height:52px;">
        </div>
      </div>
    </div>

    <!-- 3. Editorial & Government Registration (FEATURE REQUEST: Last Pic Edit Access) -->
    <div class="settings-card">
      <div class="settings-card-title">📝 ৩. সম্পাদকীয় তথ্য ও সরকারি নিবন্ধন (ফুটার হেড)</div>

      <div class="form-grid-2">
        <div class="form-group">
          <label for="editor_name_bn">ভারপ্রাপ্ত সম্পাদক ও প্রকাশক (বাংলা)</label>
          <input type="text" id="editor_name_bn" name="editor_name_bn" value="<?= htmlspecialchars($settings['editor_name_bn'] ?? 'সম্পাদক ও প্রকাশক মণ্ডলী') ?>" class="form-control" placeholder="যেমন: সম্পাদক ও প্রকাশক মণ্ডলী">
        </div>
        <div class="form-group">
          <label for="editor_name_en">Editor & Publisher (English)</label>
          <input type="text" id="editor_name_en" name="editor_name_en" value="<?= htmlspecialchars($settings['editor_name_en'] ?? 'Editorial Board & Publisher') ?>" class="form-control">
        </div>
      </div>

      <div class="form-grid-2">
        <div class="form-group">
          <label for="registration_info_bn">সরকারি নিবন্ধন তথ্য (বাংলা)</label>
          <input type="text" id="registration_info_bn" name="registration_info_bn" value="<?= htmlspecialchars($settings['registration_info_bn'] ?? 'তথ্য ও সম্প্রচার মন্ত্রণালয় (অনলাইন নিউজ পোর্টাল আবেদন নং: NL-BD-2026/TBD)') ?>" class="form-control">
        </div>
        <div class="form-group">
          <label for="registration_info_en">Registration Info (English)</label>
          <input type="text" id="registration_info_en" name="registration_info_en" value="<?= htmlspecialchars($settings['registration_info_en'] ?? 'Ministry of Information & Broadcasting (Application No: NL-BD-2026/TBD)') ?>" class="form-control">
        </div>
      </div>
    </div>

    <!-- 4. Office Address & Contact (FEATURE REQUEST: Last Pic Edit Access) -->
    <div class="settings-card">
      <div class="settings-card-title">📍 ৪. হেড অফিস ঠিকানা ও সরাসরি যোগাযোগ</div>

      <div class="form-grid-2">
        <div class="form-group">
          <label for="office_address_bn">অফিসের ঠিকানা (বাংলা)</label>
          <input type="text" id="office_address_bn" name="office_address_bn" value="<?= htmlspecialchars($settings['office_address_bn'] ?? 'বীর উত্তম সি আর দত্ত রোড, ঢাকা-১২০৫, বাংলাদেশ') ?>" class="form-control">
        </div>
        <div class="form-group">
          <label for="office_address_en">Office Address (English)</label>
          <input type="text" id="office_address_en" name="office_address_en" value="<?= htmlspecialchars($settings['office_address_en'] ?? 'Bir Uttam C.R. Dutta Road, Dhaka-1205, Bangladesh') ?>" class="form-control">
        </div>
      </div>

      <div class="form-grid-2">
        <div class="form-group">
          <label for="contact_phone">যোগাযোগ ফোন নম্বর</label>
          <input type="text" id="contact_phone" name="contact_phone" value="<?= htmlspecialchars($settings['contact_phone'] ?? '+880 1700-000000') ?>" class="form-control">
        </div>
        <div class="form-group">
          <label for="contact_email">যোগাযোগ ইমেইল (Contact Email)</label>
          <input type="email" id="contact_email" name="contact_email" value="<?= htmlspecialchars($settings['contact_email'] ?? 'info@newslensbd.com') ?>" class="form-control">
        </div>
      </div>
    </div>

    <!-- 5. Social Media & Copyright (FEATURE REQUEST: Last Pic Edit Access) -->
    <div class="settings-card">
      <div class="settings-card-title">🌐 ৫. সোশ্যাল মিডিয়া ও কপিরাইট বিবরণ</div>

      <div class="form-grid-2">
        <div class="form-group">
          <label for="social_facebook">Facebook পেজ লিংক</label>
          <input type="url" id="social_facebook" name="social_facebook" value="<?= htmlspecialchars($settings['social_facebook'] ?? 'https://facebook.com') ?>" class="form-control">
        </div>
        <div class="form-group">
          <label for="social_youtube">YouTube চ্যানেল লিংক</label>
          <input type="url" id="social_youtube" name="social_youtube" value="<?= htmlspecialchars($settings['social_youtube'] ?? 'https://youtube.com') ?>" class="form-control">
        </div>
      </div>

      <div class="form-group">
        <label for="social_x">X (Twitter) লিংক</label>
        <input type="url" id="social_x" name="social_x" value="<?= htmlspecialchars($settings['social_x'] ?? 'https://twitter.com') ?>" class="form-control">
      </div>

      <div class="form-grid-2">
        <div class="form-group">
          <label for="copyright_text_bn">কপিরাইট বার্তা (বাংলা)</label>
          <input type="text" id="copyright_text_bn" name="copyright_text_bn" value="<?= htmlspecialchars($settings['copyright_text_bn'] ?? '© ২০২৬ Newslensbd (নিউজলেন্সবিডি)। সর্বস্বত্ব সংরক্ষিত।') ?>" class="form-control">
        </div>
        <div class="form-group">
          <label for="copyright_text_en">Copyright Text (English)</label>
          <input type="text" id="copyright_text_en" name="copyright_text_en" value="<?= htmlspecialchars($settings['copyright_text_en'] ?? '© 2026 Newslensbd. All rights reserved.') ?>" class="form-control">
        </div>
      </div>
    </div>

    <div style="text-align:right;margin-bottom:40px;">
      <button type="submit" class="btn-submit-fixed">💾 সকল সেটিংস সংরক্ষণ করুন (Save Settings)</button>
    </div>
  </form>
</main>

</body>
</html>

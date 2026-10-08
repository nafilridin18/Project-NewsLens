<?php
/**
 * Unified Admin Panel Footer Component
 * Matches the public site footer and user's requested layout:
 * - 4-Column Grid (Brand & Editorial, Categories, Institution, Contact & Office)
 * - Brand logo inside crisp white badge
 * - Tagline, Editorial Board, Ministry Registration
 * - Category Links & Institutional Links
 * - Office Address, Phone, Email, Social Channels
 * - Copyright & Technology Partner (Stratifyx Global) crisp white badge
 * - 100% Bilingual (BN / EN) dynamic language support
 */
use App\Models\Setting;

$settings = $settings ?? Setting::all();
$appUrl = rtrim($appUrl ?? '', '/');

$currentLogo = !empty($settings['site_logo']) ? $appUrl . '/' . htmlspecialchars($settings['site_logo']) : $appUrl . '/assets/img/logo.png';
$techLogo = !empty($settings['tech_partner_logo']) ? $appUrl . '/' . htmlspecialchars($settings['tech_partner_logo']) : $appUrl . '/assets/img/stratifyx-global.png';
$techUrl = htmlspecialchars($settings['tech_partner_url'] ?? 'https://stratifyxglobal.com');
$techName = htmlspecialchars($settings['tech_partner_name'] ?? 'Stratifyx Global');

$taglineBn = htmlspecialchars($settings['tagline_bn'] ?? 'সাধারণের বাইরে, সত্যের খোঁজে');
$taglineEn = htmlspecialchars($settings['tagline_en'] ?? 'News Beyond the Ordinary');

$editorBn = htmlspecialchars($settings['editor_name_bn'] ?? 'সম্পাদক ও প্রকাশক মণ্ডলী');
$editorEn = htmlspecialchars($settings['editor_name_en'] ?? 'Editorial Board & Publisher');

$regBn = htmlspecialchars($settings['registration_info_bn'] ?? 'তথ্য ও সম্প্রচার মন্ত্রণালয় (অনলাইন নিউজ পোর্টাল আবেদন নং: NL-BD-2026/TBD)');
$regEn = htmlspecialchars($settings['registration_info_en'] ?? 'Ministry of Information & Broadcasting (Application No: NL-BD-2026/TBD)');

$addressBn = htmlspecialchars($settings['office_address_bn'] ?? 'বীর উত্তম সি আর দত্ত রোড, ঢাকা-১২০৫, বাংলাদেশ');
$addressEn = htmlspecialchars($settings['office_address_en'] ?? 'Bir Uttam C.R. Dutta Road, Dhaka-1205, Bangladesh');

$phone = htmlspecialchars($settings['contact_phone'] ?? '+880 1700-000000');
$email = htmlspecialchars($settings['contact_email'] ?? 'info@newslensbd.com');

$fbUrl = htmlspecialchars($settings['social_facebook'] ?? 'https://facebook.com');
$ytUrl = htmlspecialchars($settings['social_youtube'] ?? 'https://youtube.com');
$xUrl  = htmlspecialchars($settings['social_x'] ?? 'https://twitter.com');

$copyBn = htmlspecialchars($settings['copyright_text_bn'] ?? '© ২০২৬ Newslensbd (নিউজলেন্সবিডি)। সর্বস্বত্ব সংরক্ষিত।');
$copyEn = htmlspecialchars($settings['copyright_text_en'] ?? '© 2026 Newslensbd. All rights reserved.');
?>
<style>
/* ================= ADMIN FOOTER STYLES ================= */
.admin-site-footer {
  background-color: #051024;
  color: #cbd5e1;
  border-top: 3px solid #0E7A3A;
  margin-top: 50px;
  width: 100%;
  font-family: 'Hind Siliguri', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
  box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.25);
}

.admin-site-footer a {
  text-decoration: none;
  transition: all 0.2s ease;
}

.admin-footer-primary {
  padding: 45px 24px 35px;
  max-width: 1360px;
  margin: 0 auto;
}

.admin-footer-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 36px;
}

@media (min-width: 768px) {
  .admin-footer-grid {
    grid-template-columns: 1.4fr 1fr 1fr 1.2fr;
  }
}

/* Col 1: Brand */
.admin-footer-logo-badge {
  background: #ffffff !important;
  padding: 8px 16px;
  border-radius: 8px;
  display: inline-flex;
  align-items: center;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
  transition: transform 0.2s ease;
  margin-bottom: 12px;
}

.admin-footer-logo-badge:hover {
  transform: scale(1.02);
}

.admin-footer-logo-img {
  max-height: 44px;
  width: auto;
  object-fit: contain;
  display: block;
}

.admin-footer-tagline {
  color: #94a3b8;
  font-size: 0.92rem;
  margin: 8px 0 16px;
  line-height: 1.5;
}

.admin-editorial-info {
  font-size: 0.85rem;
  color: #94a3b8;
  line-height: 1.65;
}

.admin-editorial-info p {
  margin: 5px 0;
}

.admin-editorial-info strong {
  color: #f1f5f9;
}

/* Headings */
.admin-footer-heading {
  font-size: 1.05rem;
  color: #ffffff;
  margin: 0 0 16px 0;
  position: relative;
  padding-bottom: 8px;
  border-bottom: 1px solid rgba(255, 255, 255, 0.12);
  font-weight: 700;
  letter-spacing: 0.3px;
}

/* Links List */
.admin-footer-links {
  list-style: none;
  padding: 0;
  margin: 0;
}

.admin-footer-links li {
  margin-bottom: 9px;
}

.admin-footer-links a {
  color: #94a3b8;
  font-size: 0.9rem;
}

.admin-footer-links a:hover {
  color: #38bdf8;
  padding-left: 3px;
}

/* Contact */
.admin-contact-item {
  font-size: 0.88rem;
  margin: 0 0 10px 0;
  color: #94a3b8;
  line-height: 1.5;
}

.admin-footer-social {
  display: flex;
  gap: 10px;
  margin-top: 16px;
  flex-wrap: wrap;
}

.admin-footer-social a {
  background: rgba(255, 255, 255, 0.08);
  color: #ffffff;
  padding: 6px 14px;
  border-radius: 6px;
  font-size: 0.82rem;
  font-weight: 600;
  border: 1px solid rgba(255, 255, 255, 0.1);
}

.admin-footer-social a:hover {
  background: #0E7A3A;
  border-color: #0E7A3A;
  transform: translateY(-1px);
}

/* Footer Bottom Bar */
.admin-footer-bottom {
  background-color: #020617;
  padding: 18px 24px;
  border-top: 1px solid rgba(255, 255, 255, 0.07);
  font-size: 0.86rem;
}

.admin-footer-bottom-inner {
  max-width: 1360px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 14px;
}

.admin-footer-copyright p {
  margin: 0;
  color: #94a3b8;
}

.admin-tech-partner-badge {
  display: flex;
  align-items: center;
  gap: 10px;
}

.admin-partner-title {
  color: #94a3b8;
  font-size: 0.86rem;
  font-weight: 500;
}

.admin-partner-white-badge {
  background: #ffffff !important;
  padding: 4px 12px;
  border-radius: 6px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
  transition: transform 0.2s ease;
}

.admin-partner-white-badge:hover {
  transform: scale(1.03);
}

.admin-partner-footer-logo-lg {
  height: 30px;
  width: auto;
  object-fit: contain;
  display: block;
}
</style>

<footer class="admin-site-footer" aria-label="অ্যাডমিন প্যানেল ফুটার">
  <div class="admin-footer-primary">
    <div class="admin-footer-grid">

      <!-- Col 1: Brand & Editorial Details -->
      <div class="admin-footer-col brand-col">
        <a href="<?= $appUrl ?>/" target="_blank" class="admin-footer-logo-link" aria-label="Newslensbd লাইভ পোর্টাল">
          <div class="admin-footer-logo-white-badge">
            <img src="<?= $currentLogo ?>" alt="Newslensbd" class="admin-footer-logo-img" width="220" height="44">
          </div>
        </a>
        <p class="admin-footer-tagline" data-bn="<?= $taglineBn ?>" data-en="<?= $taglineEn ?>"><?= $taglineBn ?></p>
        <div class="admin-editorial-info">
          <p>
            <strong data-bn="সম্পাদক ও প্রকাশক:" data-en="Editor &amp; Publisher:">সম্পাদক ও প্রকাশক:</strong> 
            <span data-bn="<?= $editorBn ?>" data-en="<?= $editorEn ?>"><?= $editorBn ?></span>
          </p>
          <p>
            <strong data-bn="নিবন্ধন:" data-en="Registration:">নিবন্ধন:</strong> 
            <span data-bn="<?= $regBn ?>" data-en="<?= $regEn ?>"><?= $regBn ?></span>
          </p>
        </div>
      </div>

      <!-- Col 2: Categories -->
      <div class="admin-footer-col links-col">
        <h4 class="admin-footer-heading" data-bn="ক্যাটাগরি" data-en="Categories">ক্যাটাগরি</h4>
        <ul class="admin-footer-links">
          <li><a href="<?= $appUrl ?>/category/national" target="_blank" data-bn="জাতীয়" data-en="National">জাতীয়</a></li>
          <li><a href="<?= $appUrl ?>/category/politics" target="_blank" data-bn="রাজনীতি" data-en="Politics">রাজনীতি</a></li>
          <li><a href="<?= $appUrl ?>/saradesh" target="_blank" data-bn="সারাদেশ" data-en="All Bangladesh">সারাদেশ</a></li>
          <li><a href="<?= $appUrl ?>/category/economy" target="_blank" data-bn="অর্থনীতি" data-en="Economy">অর্থনীতি</a></li>
          <li><a href="<?= $appUrl ?>/category/international" target="_blank" data-bn="আন্তর্জাতিক" data-en="International">আন্তর্জাতিক</a></li>
          <li><a href="<?= $appUrl ?>/category/sports" target="_blank" data-bn="খেলা" data-en="Sports">খেলা</a></li>
          <li><a href="<?= $appUrl ?>/category/tech" target="_blank" data-bn="বিজ্ঞান ও প্রযুক্তি" data-en="Tech">বিজ্ঞান ও প্রযুক্তি</a></li>
          <li><a href="<?= $appUrl ?>/category/crime" target="_blank" data-bn="অপরাধ" data-en="Crime">অপরাধ</a></li>
        </ul>
      </div>

      <!-- Col 3: Institutional & Legal -->
      <div class="admin-footer-col links-col">
        <h4 class="admin-footer-heading" data-bn="প্রতিষ্ঠান" data-en="Institution">প্রতিষ্ঠান</h4>
        <ul class="admin-footer-links">
          <li><a href="<?= $appUrl ?>/page/about" target="_blank" data-bn="আমাদের সম্পর্কে" data-en="About Us">আমাদের সম্পর্কে</a></li>
          <li><a href="<?= $appUrl ?>/page/contact" target="_blank" data-bn="যোগাযোগ" data-en="Contact Us">যোগাযোগ</a></li>
          <li><a href="<?= $appUrl ?>/page/advertise" target="_blank" data-bn="বিজ্ঞাপন" data-en="Advertise">বিজ্ঞাপন</a></li>
          <li><a href="<?= $appUrl ?>/page/privacy" target="_blank" data-bn="গোপনীয়তা নীতি" data-en="Privacy Policy">গোপনীয়তা নীতি</a></li>
          <li><a href="<?= $appUrl ?>/page/terms" target="_blank" data-bn="ব্যবহারের শর্তাবলী" data-en="Terms of Service">ব্যবহারের শর্তাবলী</a></li>
          <li><a href="<?= $appUrl ?>/page/corrections" target="_blank" data-bn="সংশোধনী নীতি" data-en="Corrections Policy">সংশোধনী নীতি</a></li>
        </ul>
      </div>

      <!-- Col 4: Contact & Social -->
      <div class="admin-footer-col contact-col">
        <h4 class="admin-footer-heading" data-bn="যোগাযোগ ও অফিস" data-en="Contact &amp; Office">যোগাযোগ ও অফিস</h4>
        <p class="admin-contact-item" data-bn="📍 <?= $addressBn ?>" data-en="📍 <?= $addressEn ?>">📍 <?= $addressBn ?></p>
        <p class="admin-contact-item">📞 <?= $phone ?></p>
        <p class="admin-contact-item">✉️ <?= $email ?></p>
        <div class="admin-footer-social">
          <?php if (!empty($fbUrl)): ?>
            <a href="<?= $fbUrl ?>" target="_blank" rel="noopener" aria-label="Facebook">Facebook</a>
          <?php endif; ?>
          <?php if (!empty($ytUrl)): ?>
            <a href="<?= $ytUrl ?>" target="_blank" rel="noopener" aria-label="YouTube">YouTube</a>
          <?php endif; ?>
          <?php if (!empty($xUrl)): ?>
            <a href="<?= $xUrl ?>" target="_blank" rel="noopener" aria-label="X">X</a>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </div>

  <!-- Footer Bottom Bar & Technology Partner -->
  <div class="admin-footer-bottom">
    <div class="admin-footer-bottom-inner">
      <div class="admin-footer-copyright">
        <p data-bn="<?= $copyBn ?>" data-en="<?= $copyEn ?>"><?= $copyBn ?></p>
      </div>

      <!-- Tech Partner Stratifyx Global (Crisp White Badge) -->
      <div class="admin-tech-partner-badge">
        <span class="admin-partner-title" data-bn="টেকনোলজি পার্টনার:" data-en="Technology Partner:">টেকনোলজি পার্টনার:</span>
        <a href="<?= $techUrl ?>" target="_blank" rel="noopener" class="admin-partner-link" aria-label="<?= $techName ?>">
          <div class="admin-partner-white-badge">
            <img src="<?= $techLogo ?>" alt="<?= $techName ?>" class="admin-partner-footer-logo-lg" width="160" height="30">
          </div>
        </a>
      </div>
    </div>
  </div>
</footer>

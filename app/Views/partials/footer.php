<?php
$settings = $settings ?? [];
$appUrl = rtrim($appUrl ?? $config['app']['url'] ?? '', '/');

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
<?php
// Optional Footer / Bottom Banner (renders dynamically if active in database)
$footerAdHtml = \App\Helpers\AdBanner::render('footer', ['show_placeholder' => false]);
if (!empty($footerAdHtml)): ?>
  <div class="site-footer-ad-container container" aria-label="স্পন্সরড ব্যানার">
    <?= $footerAdHtml ?>
  </div>
<?php endif; ?>
<footer class="site-footer">
  <div class="footer-primary">
    <div class="container footer-grid">
      <!-- Col 1: Brand & Editorial -->
      <div class="footer-col brand-col">
        <div class="footer-logo">
          <a href="<?= $appUrl ?>/" class="footer-logo-link" aria-label="Newslensbd">
            <div class="footer-logo-white-badge">
              <img src="<?= $currentLogo ?>" alt="Newslensbd" class="footer-logo-img" width="220" height="48">
            </div>
          </a>
        </div>
        <p class="footer-tagline" data-bn="<?= $taglineBn ?>" data-en="<?= $taglineEn ?>"><?= $taglineBn ?></p>
        <div class="editorial-info">
          <p><strong data-bn="সম্পাদক ও প্রকাশক:" data-en="Editor & Publisher:">সম্পাদক ও প্রকাশক:</strong> <span data-bn="<?= $editorBn ?>" data-en="<?= $editorEn ?>"><?= $editorBn ?></span></p>
          <p><strong data-bn="নিবন্ধন:" data-en="Registration:">নিবন্ধন:</strong> <span data-bn="<?= $regBn ?>" data-en="<?= $regEn ?>"><?= $regBn ?></span></p>
        </div>
      </div>

      <!-- Col 2: Quick Links -->
      <div class="footer-col links-col">
        <h4 class="footer-heading" data-bn="ক্যাটাগরি" data-en="Categories">ক্যাটাগরি</h4>
        <ul class="footer-links">
          <li><a href="<?= $appUrl ?>/category/national" data-bn="জাতীয়" data-en="National">জাতীয়</a></li>
          <li><a href="<?= $appUrl ?>/category/politics" data-bn="রাজনীতি" data-en="Politics">রাজনীতি</a></li>
          <li><a href="<?= $appUrl ?>/saradesh" data-bn="সারাদেশ" data-en="All Bangladesh">সারাদেশ</a></li>
          <li><a href="<?= $appUrl ?>/category/economy" data-bn="অর্থনীতি" data-en="Economy">অর্থনীতি</a></li>
          <li><a href="<?= $appUrl ?>/category/international" data-bn="আন্তর্জাতিক" data-en="International">আন্তর্জাতিক</a></li>
          <li><a href="<?= $appUrl ?>/category/sports" data-bn="খেলা" data-en="Sports">খেলা</a></li>
          <li><a href="<?= $appUrl ?>/category/tech" data-bn="বিজ্ঞান ও প্রযুক্তি" data-en="Tech">বিজ্ঞান ও প্রযুক্তি</a></li>
          <li><a href="<?= $appUrl ?>/category/crime" data-bn="অপরাধ" data-en="Crime">অপরাধ</a></li>
        </ul>
      </div>

      <!-- Col 3: Institutional & Legal -->
      <div class="footer-col links-col">
        <h4 class="footer-heading" data-bn="প্রতিষ্ঠান" data-en="Institution">প্রতিষ্ঠান</h4>
        <ul class="footer-links">
          <li><a href="<?= $appUrl ?>/page/about" data-bn="আমাদের সম্পর্কে" data-en="About Us">আমাদের সম্পর্কে</a></li>
          <li><a href="<?= $appUrl ?>/page/contact" data-bn="যোগাযোগ" data-en="Contact Us">যোগাযোগ</a></li>
          <li><a href="<?= $appUrl ?>/page/advertise" data-bn="বিজ্ঞাপন" data-en="Advertise">বিজ্ঞাপন</a></li>
          <li><a href="<?= $appUrl ?>/page/privacy" data-bn="গোপনীয়তা নীতি" data-en="Privacy Policy">গোপনীয়তা নীতি</a></li>
          <li><a href="<?= $appUrl ?>/page/terms" data-bn="ব্যবহারের শর্তাবলী" data-en="Terms of Service">ব্যবহারের শর্তাবলী</a></li>
          <li><a href="<?= $appUrl ?>/page/corrections" data-bn="সংশোধনী নীতি" data-en="Corrections Policy">সংশোধনী নীতি</a></li>
        </ul>
      </div>

      <!-- Col 4: Contact & Social -->
      <div class="footer-col contact-col">
        <h4 class="footer-heading" data-bn="যোগাযোগ ও অফিস" data-en="Contact & Office">যোগাযোগ ও অফিস</h4>
        <p class="contact-item" data-bn="📍 <?= $addressBn ?>" data-en="📍 <?= $addressEn ?>">📍 <?= $addressBn ?></p>
        <p class="contact-item">📞 <?= $phone ?></p>
        <p class="contact-item">✉️ <?= $email ?></p>
        <div class="footer-social">
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

  <!-- Footer Bottom Bar & Partner -->
  <div class="footer-bottom">
    <div class="container footer-bottom-inner">
      <div class="copyright">
        <p data-bn="<?= $copyBn ?>" data-en="<?= $copyEn ?>"><?= $copyBn ?></p>
      </div>

      <!-- Tech Partner Stratifyx Global (Crisp White Badge Card Presentation) -->
      <div class="tech-partner-badge">
        <span class="partner-title" data-bn="টেকনোলজি পার্টনার:" data-en="Technology Partner:">টেকনোলজি পার্টনার:</span>
        <a href="<?= $techUrl ?>" target="_blank" rel="noopener" class="partner-link" aria-label="<?= $techName ?>">
          <div class="partner-white-badge">
            <img src="<?= $techLogo ?>" alt="<?= $techName ?>" class="partner-footer-logo-lg" width="160" height="38">
          </div>
        </a>
      </div>
    </div>
  </div>
</footer>

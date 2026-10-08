<?php
$appUrl = rtrim($config['app']['url'] ?? '', '/');
$pageTitle = $pageTitle ?? 'Newslensbd | সাধারণের বাইরে, সত্যের খোঁজে';
?>
<!DOCTYPE html>
<html lang="bn" data-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle) ?></title>
  <meta name="description" content="Newslensbd - সাধারণের বাইরে, সত্যের খোঁজে। বাংলাদেশের জাতীয়, আন্তর্জাতিক, বিভাগীয় ও জেলা পর্যায়ের সর্বশেষ নির্ভরযোগ্য সংবাদ।">
  <meta name="theme-color" content="#0b1f44">

  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:title" content="<?= htmlspecialchars($pageTitle) ?>">
  <meta property="og:description" content="Newslensbd - সাধারণের বাইরে, সত্যের খোঁজে।">
  <meta property="og:image" content="<?= $appUrl ?>/assets/img/logo.png">

  <!-- Fonts: Hind Siliguri, Noto Serif Bengali -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Noto+Serif+Bengali:wght@600;700;800&family=Instrument+Sans:wght@500;600;700&display=swap" rel="stylesheet">

  <!-- Portal Stylesheet -->
  <link rel="stylesheet" href="<?= $appUrl ?>/assets/css/style.css">
</head>
<body class="site-body">
  <div class="site-wrapper">

    <!-- Header Partial -->
    <?php require APP_PATH . '/Views/partials/header.php'; ?>

    <!-- Breaking News Ticker Partial -->
    <?php require APP_PATH . '/Views/partials/breaking_ticker.php'; ?>

    <!-- Main Content Area -->
    <main class="site-main" id="main-content">
      <?= $content ?>
    </main>

    <!-- Footer Partial -->
    <?php require APP_PATH . '/Views/partials/footer.php'; ?>

  </div>

  <!-- Portal JavaScript -->
  <script src="<?= $appUrl ?>/assets/js/main.js"></script>
</body>
</html>

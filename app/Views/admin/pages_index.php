<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle ?? 'প্রাতিষ্ঠানিক পেজ ব্যবস্থাপনা | Newslens Desk') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    .admin-container { max-width: 1240px; margin: 28px auto 60px; padding: 0 20px; font-family: 'Hind Siliguri', sans-serif; }
    
    .page-title-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .page-title-row h2 { color: var(--adm-text, #f1f5f9); font-size: 1.6rem; font-weight: 700; display: flex; align-items: center; gap: 10px; }
    .page-subtitle { color: var(--adm-text-muted, #94a3b8); font-size: 0.92rem; margin-top: 4px; }
    
    .stats-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 26px; }
    .stat-card {
      background: var(--adm-card-bg, #0c1e3d);
      border: 1px solid var(--adm-border, #173567);
      border-radius: 12px;
      padding: 16px 20px;
      display: flex;
      align-items: center;
      gap: 16px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
    .stat-icon { font-size: 1.8rem; background: var(--adm-pill-bg, rgba(255,255,255,0.06)); width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 10px; }
    .stat-val { font-size: 1.6rem; font-weight: 800; color: var(--adm-text, #f8fafc); line-height: 1; }
    .stat-lbl { font-size: 0.85rem; color: var(--adm-text-muted, #94a3b8); margin-top: 4px; }

    .btn-create {
      background: var(--adm-primary, #0E7A3A);
      color: #fff;
      padding: 10px 18px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 700;
      font-size: 0.92rem;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: background 0.2s, transform 0.1s;
      box-shadow: 0 2px 8px rgba(14, 122, 58, 0.3);
    }
    .btn-create:hover { background: var(--adm-primary-hover, #0a5c2b); transform: translateY(-1px); }

    .table-card {
      background: var(--adm-card-bg, #0c1e3d);
      border-radius: 12px;
      border: 1px solid var(--adm-border, #173567);
      box-shadow: 0 4px 16px rgba(0,0,0,0.08);
      overflow-x: auto;
    }
    table { width: 100%; border-collapse: collapse; text-align: left; }
    th {
      background: rgba(255, 255, 255, 0.03);
      padding: 14px 16px;
      font-size: 0.88rem;
      font-weight: 700;
      color: var(--adm-text-muted, #94a3b8);
      border-bottom: 2px solid var(--adm-border, #173567);
      white-space: nowrap;
    }
    td {
      padding: 14px 16px;
      font-size: 0.94rem;
      color: var(--adm-text, #f8fafc);
      border-bottom: 1px solid var(--adm-border, #173567);
      vertical-align: middle;
    }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: rgba(255, 255, 255, 0.02); }

    .badge-pill {
      background: rgba(14, 122, 58, 0.2);
      color: #4ade80;
      border: 1px solid rgba(14, 122, 58, 0.4);
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 700;
      display: inline-block;
    }
    .slug-code {
      background: var(--adm-pill-bg, rgba(255,255,255,0.08));
      color: var(--adm-accent, #38bdf8);
      padding: 3px 8px;
      border-radius: 6px;
      font-family: monospace;
      font-size: 0.86rem;
      border: 1px solid var(--adm-border, #173567);
    }
    
    .status-badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 0.82rem;
      font-weight: 700;
      cursor: pointer;
      border: none;
      transition: all 0.2s;
    }
    .status-published { background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); }
    .status-draft { background: rgba(245, 158, 11, 0.15); color: #fbbf24; border: 1px solid rgba(245, 158, 11, 0.3); }

    .actions-cell { display: flex; align-items: center; gap: 8px; white-space: nowrap; }
    .btn-action {
      background: var(--adm-pill-bg, rgba(255,255,255,0.06));
      color: var(--adm-text, #f8fafc);
      border: 1px solid var(--adm-border, #173567);
      padding: 6px 10px;
      border-radius: 6px;
      font-size: 0.85rem;
      font-weight: 600;
      text-decoration: none;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 4px;
      transition: all 0.2s;
    }
    .btn-action:hover { border-color: var(--adm-accent, #38bdf8); color: var(--adm-accent, #38bdf8); }
    .btn-action-del { color: #f87171; border-color: rgba(248, 113, 113, 0.3); }
    .btn-action-del:hover { background: rgba(248, 113, 113, 0.15); border-color: #f87171; color: #ef4444; }

    .alert { padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; display: flex; align-items: center; gap: 10px; }
    .alert-success { background: #064e3b; border: 1px solid #059669; color: #a7f3d0; }
    .alert-danger { background: #7f1d1d; border: 1px solid #dc2626; color: #fecaca; }
  </style>
</head>
<body>

<?php
$activeTab = 'pages';
require __DIR__ . '/partials/header.php';

$totalPages = count($pages);
$publishedCount = 0;
$draftCount = 0;
foreach ($pages as $p) {
    if (($p['status'] ?? 'published') === 'published') {
        $publishedCount++;
    } else {
        $draftCount++;
    }
}
?>

<main class="admin-container">

  <!-- Success/Error Messages -->
  <?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success">
      <?php if ($_GET['msg'] === 'created'): ?>
        <span data-bn="✅ নতুন প্রাতিষ্ঠানিক পেজ সফলভাবে তৈরি হয়েছে!" data-en="✅ New institutional page created successfully!">✅ নতুন প্রাতিষ্ঠানিক পেজ সফলভাবে তৈরি হয়েছে!</span>
      <?php elseif ($_GET['msg'] === 'updated'): ?>
        <span data-bn="✅ পেজের কনটেন্ট ও তথ্য সফলভাবে হালনাগাদ করা হয়েছে!" data-en="✅ Page content and details updated successfully!">✅ পেজের কনটেন্ট ও তথ্য সফলভাবে হালনাগাদ করা হয়েছে!</span>
      <?php elseif ($_GET['msg'] === 'toggled'): ?>
        <span data-bn="✅ পেজের স্ট্যাটাস পরিবর্তিত হয়েছে!" data-en="✅ Page status updated successfully!">✅ পেজের স্ট্যাটাস পরিবর্তিত হয়েছে!</span>
      <?php elseif ($_GET['msg'] === 'deleted'): ?>
        <span data-bn="✅ পেজটি সফলভাবে মুছে ফেলা হয়েছে।" data-en="✅ Page deleted successfully.">✅ পেজটি সফলভাবে মুছে ফেলা হয়েছে।</span>
      <?php elseif ($_GET['msg'] === 'core_drafted'): ?>
        <span data-bn="⚠️ মূল সিস্টেম পেজ মুছে ফেলা যায় না। এটি খসড়া (Draft) হিসেবে চিহ্নিত করা হয়েছে।" data-en="⚠️ Core system page cannot be deleted. It has been marked as Draft.">⚠️ মূল সিস্টেম পেজ মুছে ফেলা যায় না। এটি খসড়া (Draft) হিসেবে চিহ্নিত করা হয়েছে।</span>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <?php if (isset($_GET['err'])): ?>
    <div class="alert alert-danger">
      <span>⚠️ <?= htmlspecialchars($_GET['err']) ?></span>
    </div>
  <?php endif; ?>

  <!-- Page Header -->
  <div class="page-title-row">
    <div>
      <h2 data-bn="📄 প্রাতিষ্ঠানিক পেজ ব্যবস্থাপনা" data-en="📄 Institutional Pages Management">
        <span>📄</span>
        <span data-bn="প্রাতিষ্ঠানিক পেজ ব্যবস্থাপনা" data-en="Institutional Pages Management">প্রাতিষ্ঠানিক পেজ ব্যবস্থাপনা</span>
      </h2>
      <p class="page-subtitle" data-bn="ফুটার ও মেনুর স্ট্যাটিক পেজসমূহ (আমাদের সম্পর্কে, যোগাযোগ, বিজ্ঞাপন, গোপনীয়তা ও অন্যান্য)" data-en="Manage static footer and menu pages (About Us, Contact, Advertise, Privacy Policy, etc.)">
        ফুটার ও মেনুর স্ট্যাটিক পেজসমূহ (আমাদের সম্পর্কে, যোগাযোগ, বিজ্ঞাপন, গোপনীয়তা ও অন্যান্য)
      </p>
    </div>
    <div>
      <a href="<?= $appUrl ?>/<?= $adminPath ?>/pages/create" class="btn-create">
        <span>➕</span>
        <span data-bn="নতুন পেজ যোগ করুন" data-en="Create New Page">নতুন পেজ যোগ করুন</span>
      </a>
    </div>
  </div>

  <!-- KPI Cards -->
  <div class="stats-row">
    <div class="stat-card">
      <div class="stat-icon">📑</div>
      <div>
        <div class="stat-val"><?= $totalPages ?></div>
        <div class="stat-lbl" data-bn="মোট পেজ" data-en="Total Pages">মোট পেজ</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="color: #34d399;">🌐</div>
      <div>
        <div class="stat-val" style="color: #34d399;"><?= $publishedCount ?></div>
        <div class="stat-lbl" data-bn="প্রকাশিত পেজ" data-en="Published Pages">প্রকাশিত পেজ</div>
      </div>
    </div>
    <div class="stat-card">
      <div class="stat-icon" style="color: #fbbf24;">📝</div>
      <div>
        <div class="stat-val" style="color: #fbbf24;"><?= $draftCount ?></div>
        <div class="stat-lbl" data-bn="খসড়া পেজ" data-en="Draft Pages">খসড়া পেজ</div>
      </div>
    </div>
  </div>

  <!-- Pages Table -->
  <div class="table-card">
    <table>
      <thead>
        <tr>
          <th style="width: 50px;">#ID</th>
          <th data-bn="ব্যাজ" data-en="Badge">ব্যাজ</th>
          <th data-bn="শিরোনাম ও পরিচিতি" data-en="Title & Identity">শিরোনাম ও পরিচিতি</th>
          <th data-bn="URL স্লাগ" data-en="URL Slug">URL স্লাগ</th>
          <th data-bn="স্ট্যাটাস" data-en="Status">স্ট্যাটাস</th>
          <th data-bn="সর্বশেষ আপডেট" data-en="Last Updated">সর্বশেষ আপডেট</th>
          <th style="text-align: right;" data-bn="অ্যাকশন" data-en="Actions">অ্যাকশন</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($pages)): ?>
          <?php foreach ($pages as $p): ?>
            <?php
              $isPublished = ($p['status'] ?? 'published') === 'published';
              $liveUrl = $appUrl . '/page/' . htmlspecialchars($p['slug']);
              $coreSlugs = ['about', 'contact', 'advertise', 'privacy', 'terms', 'corrections'];
              $isCore = in_array($p['slug'], $coreSlugs, true);
            ?>
            <tr>
              <td style="color: var(--adm-text-muted); font-weight: 700;">#<?= (int)$p['id'] ?></td>
              <td>
                <span class="badge-pill"><?= htmlspecialchars($p['badge'] ?? 'প্রতিষ্ঠান') ?></span>
              </td>
              <td>
                <div style="font-weight: 700; font-size: 1rem; color: var(--adm-text);">
                  <?= htmlspecialchars($p['title']) ?>
                </div>
                <?php if (!empty($p['title_en'])): ?>
                  <div style="font-size: 0.82rem; color: var(--adm-text-muted);">
                    <?= htmlspecialchars($p['title_en']) ?>
                  </div>
                <?php endif; ?>
              </td>
              <td>
                <a href="<?= $liveUrl ?>" target="_blank" class="slug-code" title="লাইভ পেজ দেখুন">
                  /page/<?= htmlspecialchars($p['slug']) ?> ↗
                </a>
              </td>
              <td>
                <form method="POST" action="<?= $appUrl ?>/<?= $adminPath ?>/pages/toggle" style="display:inline;">
                  <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                  <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                  <button type="submit" class="status-badge <?= $isPublished ? 'status-published' : 'status-draft' ?>" title="ক্লিক করে স্ট্যাটাস পরিবর্তন করুন">
                    <span><?= $isPublished ? '●' : '○' ?></span>
                    <span><?= $isPublished ? 'প্রকাশিত' : 'খসড়া' ?></span>
                  </button>
                </form>
              </td>
              <td style="color: var(--adm-text-muted); font-size: 0.88rem;">
                <?= !empty($p['updated_at']) ? date('d M Y, h:i A', strtotime($p['updated_at'])) : '—' ?>
              </td>
              <td>
                <div class="actions-cell" style="justify-content: flex-end;">
                  <!-- Live Site View -->
                  <a href="<?= $liveUrl ?>" target="_blank" class="btn-action" title="লাইভ দেখুন">
                    <span>👁️</span> <span data-bn="দেখুন" data-en="View">দেখুন</span>
                  </a>
                  <!-- Edit Page -->
                  <a href="<?= $appUrl ?>/<?= $adminPath ?>/pages/edit?id=<?= (int)$p['id'] ?>" class="btn-action" title="সম্পাদনা">
                    <span>✏️</span> <span data-bn="সম্পাদনা" data-en="Edit">সম্পাদনা</span>
                  </a>
                  <!-- Delete Page -->
                  <form method="POST" action="<?= $appUrl ?>/<?= $adminPath ?>/pages/delete" onsubmit="return confirm('আপনি কি নিশ্চিত যে <?= $isCore ? 'এই পেজটি ড্রাফট করতে চান?' : 'এই পেজটি মুছে ফেলতে চান?' ?>');" style="display:inline;">
                    <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                    <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                    <button type="submit" class="btn-action btn-action-del" title="<?= $isCore ? 'ড্রাফট করুন' : 'মুছুন' ?>">
                      <span>🗑️</span>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr>
            <td colspan="7" style="text-align: center; padding: 40px; color: var(--adm-text-muted);">
              কোনো পেজ পাওয়া যায়নি। <a href="<?= $appUrl ?>/<?= $adminPath ?>/pages/create" style="color: #38bdf8; font-weight: 700;">নতুন পেজ তৈরি করুন ↗</a>
            </td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

</main>

<?php require __DIR__ . '/partials/footer.php'; ?>

</body>
</html>

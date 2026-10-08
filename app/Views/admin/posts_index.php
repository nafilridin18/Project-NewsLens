<?php
use App\Helpers\BanglaDate;
$isEditor = in_array($user['role'], ['super_admin', 'editor']);
?>
<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>সংবাদ ব্যবস্থাপনা | Newslensbd CMS</title>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    .admin-container { max-width: 1280px; margin: 30px auto; padding: 0 20px; }
    .page-title-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .btn-create { background: var(--adm-primary); color: #fff; padding: 10px 18px; border-radius: 6px; text-decoration: none; font-weight: 700; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 6px; }
    .btn-create:hover { background: var(--adm-primary-hover); }
    .filter-tabs { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
    .filter-tab { background: var(--adm-card-bg); border: 1px solid var(--adm-border); color: var(--adm-text-muted); padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 0.9rem; font-weight: 600; }
    .filter-tab.active, .filter-tab:hover { background: var(--adm-primary); color: #fff; border-color: var(--adm-primary); }
    .section-card { background: var(--adm-card-bg); border: 1px solid var(--adm-border); border-radius: 8px; padding: 20px; overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.92rem; }
    th { padding: 12px; border-bottom: 2px solid var(--adm-border); color: var(--adm-text-muted); font-weight: 600; }
    td { padding: 12px; border-bottom: 1px solid var(--adm-border); color: var(--adm-text); vertical-align: middle; }
    .status-pill { padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; display: inline-block; }
    .status-published { background: rgba(74, 222, 128, 0.2); color: #4ade80; }
    .status-pending { background: rgba(251, 191, 36, 0.2); color: #fbbf24; }
    .status-draft { background: rgba(148, 163, 184, 0.2); color: #94a3b8; }
    .views-badge { background: var(--adm-nav-bg); color: var(--adm-accent); padding: 2px 8px; border-radius: 12px; font-weight: 600; border: 1px solid var(--adm-border); }
    .btn-action { padding: 5px 10px; border-radius: 4px; font-size: 0.8rem; font-weight: 600; border: none; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 3px; }
    .btn-edit { background: #0284c7; color: #fff; }
    .btn-del { background: #ef4444; color: #fff; }
    .btn-view { background: #334155; color: #fff; }
    .btn-publish { background: #0E7A3A; color: #fff; }
    .btn-reject { background: #E31E24; color: #fff; }
    .alert-banner { background: #064e3b; border: 1px solid #059669; color: #a7f3d0; padding: 12px 18px; border-radius: 6px; margin-bottom: 20px; }
  </style>
</head>
<body>
  <?php 
  $activeTab = 'posts';
  require __DIR__ . '/partials/header.php'; 
  ?>

  <main class="admin-container">
    <?php if (isset($_GET['msg'])): ?>
      <div class="alert-banner">
        <?php if ($_GET['msg'] === 'created') echo '✅ নতুন সংবাদ সফলভাবে সংরক্ষণ করা হয়েছে!'; ?>
        <?php if ($_GET['msg'] === 'updated') echo '✅ সংবাদ সফলভাবে আপডেট করা হয়েছে!'; ?>
        <?php if ($_GET['msg'] === 'deleted') echo '✅ সংবাদটি সফলভাবে মুছে ফেলা হয়েছে!'; ?>
      </div>
    <?php endif; ?>

    <div class="page-title-row">
      <h2>সংবাদ ব্যবস্থাপনা ও সম্পাদনা তালিকা (<?= count($posts) ?> টি সংবাদ)</h2>
      <div>
        <a href="<?= $appUrl ?>/<?= $adminPath ?>/posts/create" class="btn-create">✍️ নতুন সংবাদ লিখুন</a>
      </div>
    </div>

    <!-- Status Filters -->
    <div class="filter-tabs">
      <a href="<?= $appUrl ?>/<?= $adminPath ?>/posts" class="filter-tab <?= empty($statusFilter) ? 'active' : '' ?>">সব খবর</a>
      <a href="<?= $appUrl ?>/<?= $adminPath ?>/posts?status=pending" class="filter-tab <?= $statusFilter === 'pending' ? 'active' : '' ?>">রিভিউ অপেক্ষমাণ (Pending)</a>
      <a href="<?= $appUrl ?>/<?= $adminPath ?>/posts?status=published" class="filter-tab <?= $statusFilter === 'published' ? 'active' : '' ?>">প্রকাশিত (Published)</a>
      <a href="<?= $appUrl ?>/<?= $adminPath ?>/posts?status=draft" class="filter-tab <?= $statusFilter === 'draft' ? 'active' : '' ?>">খসড়া (Draft)</a>
    </div>

    <!-- Posts Table -->
    <div class="section-card">
      <table>
        <thead>
          <tr>
            <th>আইডি</th>
            <th>শিরোনাম</th>
            <th>ক্যাটাগরি</th>
            <th>এলাকা</th>
            <th>পঠিত (Views)</th>
            <th>স্ট্যাটাস</th>
            <th>অ্যাকশন (সম্পাদনা ও মোছা)</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($posts)): ?>
            <tr><td colspan="7" style="text-align:center; padding: 24px; color:#94a3b8;">কোনো খবর পাওয়া যায়নি।</td></tr>
          <?php else: ?>
            <?php foreach ($posts as $p): ?>
              <tr>
                <td>#<?= $p['id'] ?></td>
                <td>
                  <strong><?= htmlspecialchars($p['title']) ?></strong>
                  <?php if (!empty($p['video_url'])): ?>
                    <span style="color:#f87171;font-size:0.75rem;margin-left:4px;">▶ ভিডিও</span>
                  <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($p['category_name']) ?></td>
                <td><?= htmlspecialchars($p['district_name'] ?? 'জাতীয়') ?></td>
                <td>
                  <span class="views-badge">👁️ <?= BanglaDate::bnNum($p['views'] ?? 0) ?></span>
                </td>
                <td>
                  <span class="status-pill status-<?= $p['status'] ?>">
                    <?= $p['status'] === 'published' ? 'প্রকাশিত' : ($p['status'] === 'pending' ? 'রিভিউধীন' : ($p['status'] === 'rejected' ? 'বাতিল' : 'খসড়া')) ?>
                  </span>
                </td>
                <td>
                  <div style="display:flex; gap:6px; flex-wrap:wrap;">
                    <!-- Edit Button -->
                    <a href="<?= $appUrl ?>/<?= $adminPath ?>/posts/edit?id=<?= $p['id'] ?>" class="btn-action btn-edit">✏️ সম্পাদনা</a>

                    <!-- View Post Button -->
                    <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($p['category_slug'] ?? 'national') ?>/<?= $p['id'] ?>/<?= htmlspecialchars($p['slug']) ?>" target="_blank" class="btn-action btn-view">↗ দেখুন</a>

                    <!-- Delete Button -->
                    <?php if ($isEditor): ?>
                      <form action="<?= $appUrl ?>/<?= $adminPath ?>/posts/delete" method="post" style="display:inline;" onsubmit="return confirm('আপনি কি নিশ্চিত যে সংবাদটি মুছে ফেলতে চান?');">
                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                        <button type="submit" class="btn-action btn-del">🗑️ মুছুন</button>
                      </form>
                    <?php endif; ?>

                    <!-- Workflow Approval for Pending Posts -->
                    <?php if ($isEditor && $p['status'] === 'pending'): ?>
                      <form action="<?= $appUrl ?>/<?= $adminPath ?>/posts/status" method="post" style="display:inline-flex; gap:4px;">
                        <input type="hidden" name="post_id" value="<?= $p['id'] ?>">
                        <button type="submit" name="status" value="published" class="btn-action btn-publish">অনুমোদন</button>
                        <button type="submit" name="status" value="rejected" class="btn-action btn-reject">বাতিল</button>
                      </form>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </main>
</body>
</html>

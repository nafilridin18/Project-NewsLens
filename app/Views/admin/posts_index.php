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
    .btn-action { 
      padding: 5px 10px; 
      border-radius: 4px; 
      font-size: 0.8rem; 
      font-weight: 600; 
      border: none; 
      cursor: pointer; 
      text-decoration: none; 
      display: inline-flex; 
      align-items: center; 
      justify-content: center;
      gap: 4px; 
      white-space: nowrap !important;
      line-height: 1.2;
    }
    .btn-edit { background: #0284c7; color: #fff; }
    .btn-del { background: #ef4444; color: #fff; }
    .btn-view { background: #334155; color: #fff; }
    .btn-publish { background: #0E7A3A; color: #fff; }
    .btn-reject { background: #E31E24; color: #fff; }
    .alert-banner { background: #064e3b; border: 1px solid #059669; color: #a7f3d0; padding: 12px 18px; border-radius: 6px; margin-bottom: 20px; }
    
    @media (max-width: 1050px) {
      .btn-action { padding: 6px 8px; }
      .btn-action .btn-text { display: none !important; }
    }
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
        <?php if ($_GET['msg'] === 'created'): ?>
          <span data-bn="✅ নতুন সংবাদ সফলভাবে সংরক্ষণ করা হয়েছে!" data-en="✅ New post successfully saved!">✅ নতুন সংবাদ সফলভাবে সংরক্ষণ করা হয়েছে!</span>
        <?php elseif ($_GET['msg'] === 'updated'): ?>
          <span data-bn="✅ সংবাদ সফলভাবে আপডেট করা হয়েছে!" data-en="✅ Post successfully updated!">✅ সংবাদ সফলভাবে আপডেট করা হয়েছে!</span>
        <?php elseif ($_GET['msg'] === 'deleted'): ?>
          <span data-bn="✅ সংবাদটি সফলভাবে মুছে ফেলা হয়েছে!" data-en="✅ Post successfully deleted!">✅ সংবাদটি সফলভাবে মুছে ফেলা হয়েছে!</span>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <div class="page-title-row">
      <h2>
        <span data-bn="সংবাদ ব্যবস্থাপনা ও সম্পাদনা তালিকা" data-en="News Management & Editorial List">সংবাদ ব্যবস্থাপনা ও সম্পাদনা তালিকা</span> 
        (<span data-bn="<?= BanglaDate::bnNum(count($posts)) ?> টি সংবাদ" data-en="<?= count($posts) ?> Posts"><?= BanglaDate::bnNum(count($posts)) ?> টি সংবাদ</span>)
      </h2>
      <div>
        <a href="<?= $appUrl ?>/<?= $adminPath ?>/posts/create" class="btn-create">
          <span data-bn="✍️ নতুন সংবাদ লিখুন" data-en="✍️ Write News">✍️ নতুন সংবাদ লিখুন</span>
        </a>
      </div>
    </div>

    <!-- Status Filters -->
    <div class="filter-tabs">
      <a href="<?= $appUrl ?>/<?= $adminPath ?>/posts" class="filter-tab <?= empty($statusFilter) ? 'active' : '' ?>" data-bn="সব খবর" data-en="All News">সব খবর</a>
      <a href="<?= $appUrl ?>/<?= $adminPath ?>/posts?status=pending" class="filter-tab <?= $statusFilter === 'pending' ? 'active' : '' ?>" data-bn="রিভিউ অপেক্ষমাণ (Pending)" data-en="Pending Review">রিভিউ অপেক্ষমাণ (Pending)</a>
      <a href="<?= $appUrl ?>/<?= $adminPath ?>/posts?status=published" class="filter-tab <?= $statusFilter === 'published' ? 'active' : '' ?>" data-bn="প্রকাশিত (Published)" data-en="Published">প্রকাশিত (Published)</a>
      <a href="<?= $appUrl ?>/<?= $adminPath ?>/posts?status=draft" class="filter-tab <?= $statusFilter === 'draft' ? 'active' : '' ?>" data-bn="খসড়া (Draft)" data-en="Draft">খসড়া (Draft)</a>
    </div>

    <!-- Posts Table -->
    <div class="section-card">
      <table>
        <thead>
          <tr>
            <th data-bn="আইডি" data-en="ID">আইডি</th>
            <th data-bn="শিরোনাম" data-en="Title">শিরোনাম</th>
            <th data-bn="ক্যাটাগরি" data-en="Category">ক্যাটাগরি</th>
            <th data-bn="এলাকা" data-en="Location">এলাকা</th>
            <th data-bn="পঠিত (Views)" data-en="Views">পঠিত (Views)</th>
            <th data-bn="স্ট্যাটাস" data-en="Status">স্ট্যাটাস</th>
            <th data-bn="অ্যাকশন (সম্পাদনা ও মোছা)" data-en="Actions (Edit & Delete)">অ্যাকশন (সম্পাদনা ও মোছা)</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($posts)): ?>
            <tr><td colspan="7" style="text-align:center; padding: 24px; color:#94a3b8;"><span data-bn="কোনো খবর পাওয়া যায়নি।" data-en="No news found.">কোনো খবর পাওয়া যায়নি।</span></td></tr>
          <?php else: ?>
            <?php foreach ($posts as $p): ?>
              <tr>
                <td>#<?= $p['id'] ?></td>
                <td>
                  <strong><?= htmlspecialchars($p['title']) ?></strong>
                  <?php if (!empty($p['video_url'])): ?>
                    <span style="color:#f87171;font-size:0.75rem;margin-left:4px;" data-bn="▶ ভিডিও" data-en="▶ Video">▶ ভিডিও</span>
                  <?php endif; ?>
                </td>
                <td>
                  <span data-bn="<?= htmlspecialchars($p['category_name']) ?>" data-en="<?= htmlspecialchars($p['category_name_en'] ?? $p['category_name']) ?>"><?= htmlspecialchars($p['category_name']) ?></span>
                </td>
                <td>
                  <span data-bn="<?= htmlspecialchars($p['district_name'] ?? 'জাতীয়') ?>" data-en="<?= htmlspecialchars($p['district_name_en'] ?? ($p['district_name'] ?? 'National')) ?>"><?= htmlspecialchars($p['district_name'] ?? 'জাতীয়') ?></span>
                </td>
                <td>
                  <span class="views-badge" data-bn="👁️ <?= BanglaDate::bnNum($p['views'] ?? 0) ?>" data-en="👁️ <?= (int)($p['views'] ?? 0) ?>">👁️ <?= BanglaDate::bnNum($p['views'] ?? 0) ?></span>
                </td>
                <td>
                  <span class="status-pill status-<?= $p['status'] ?>" 
                        data-bn="<?= $p['status'] === 'published' ? 'প্রকাশিত' : ($p['status'] === 'pending' ? 'রিভিউধীন' : ($p['status'] === 'rejected' ? 'বাতিল' : 'খসড়া')) ?>" 
                        data-en="<?= $p['status'] === 'published' ? 'Published' : ($p['status'] === 'pending' ? 'Pending' : ($p['status'] === 'rejected' ? 'Rejected' : 'Draft')) ?>">
                    <?= $p['status'] === 'published' ? 'প্রকাশিত' : ($p['status'] === 'pending' ? 'রিভিউধীন' : ($p['status'] === 'rejected' ? 'বাতিল' : 'খসড়া')) ?>
                  </span>
                </td>
                <td>
                  <div style="display:flex; gap:6px; flex-wrap:nowrap; align-items:center;">
                    <!-- Edit Button -->
                    <a href="<?= $appUrl ?>/<?= $adminPath ?>/posts/edit?id=<?= $p['id'] ?>" class="btn-action btn-edit" title="সম্পাদনা" data-title-bn="সম্পাদনা" data-title-en="Edit">
                      <span class="btn-icon">✏️</span><span class="btn-text" data-bn="সম্পাদনা" data-en="Edit">সম্পাদনা</span>
                    </a>

                    <!-- View Post Button -->
                    <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($p['category_slug'] ?? 'national') ?>/<?= $p['id'] ?>/<?= htmlspecialchars($p['slug']) ?>" target="_blank" class="btn-action btn-view" title="দেখুন" data-title-bn="দেখুন" data-title-en="View">
                      <span class="btn-icon">↗</span><span class="btn-text" data-bn="দেখুন" data-en="View">দেখুন</span>
                    </a>

                    <!-- Delete Button -->
                    <?php if ($isEditor): ?>
                      <form action="<?= $appUrl ?>/<?= $adminPath ?>/posts/delete" method="post" style="display:inline;" onsubmit="return confirm('আপনি কি নিশ্চিত যে সংবাদটি মুছে ফেলতে চান?');">
                        <input type="hidden" name="id" value="<?= $p['id'] ?>">
                        <button type="submit" class="btn-action btn-del" title="মুছুন" data-title-bn="মুছুন" data-title-en="Delete">
                          <span class="btn-icon">🗑️</span><span class="btn-text" data-bn="মুছুন" data-en="Delete">মুছুন</span>
                        </button>
                      </form>
                    <?php endif; ?>

                    <!-- Workflow Approval for Pending Posts -->
                    <?php if ($isEditor && $p['status'] === 'pending'): ?>
                      <form action="<?= $appUrl ?>/<?= $adminPath ?>/posts/status" method="post" style="display:inline-flex; gap:4px;">
                        <input type="hidden" name="post_id" value="<?= $p['id'] ?>">
                        <button type="submit" name="status" value="published" class="btn-action btn-publish" data-bn="অনুমোদন" data-en="Approve">অনুমোদন</button>
                        <button type="submit" name="status" value="rejected" class="btn-action btn-reject" data-bn="বাতিল" data-en="Reject">বাতিল</button>
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
  <?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>

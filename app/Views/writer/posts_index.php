<?php
$adminPath = $adminPath ?? 'lens-desk';
$appUrl = rtrim($appUrl ?? '', '/');
$posts = $posts ?? [];
$statusFilter = $statusFilter ?? '';
$search = $search ?? '';
$user = $user ?? \App\Core\Auth::user();
$success = $success ?? null;
$error = $error ?? null;
$csrfToken = $csrfToken ?? \App\Core\Auth::generateCsrf();
$activeTab = 'writer_posts';
?>
<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle ?? 'আমার সংবাদসমূহ') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Hind Siliguri', sans-serif; background: var(--adm-bg); color: var(--adm-text); }
    .posts-wrap { max-width: 1360px; margin: 24px auto 60px; padding: 0 20px; }

    .header-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 16px;
      margin-bottom: 24px;
    }
    .header-title { font-size: 1.6rem; font-weight: 800; display: flex; align-items: center; gap: 10px; }
    .btn-create {
      background: #0E7A3A;
      color: #fff;
      padding: 10px 20px;
      border-radius: 8px;
      font-weight: 700;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: background 0.2s;
    }
    .btn-create:hover { background: #0a5c2b; }

    .alert {
      padding: 12px 18px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-weight: 600;
      font-size: 0.92rem;
    }
    .alert-success { background: rgba(14, 122, 58, 0.2); border: 1px solid #10b981; color: #86efac; }
    .alert-error { background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; color: #fca5a5; }

    /* Filter Bar */
    .filter-card {
      background: var(--adm-card-bg);
      border: 1px solid var(--adm-border);
      border-radius: 12px;
      padding: 16px 20px;
      margin-bottom: 24px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 16px;
    }
    .pill-group { display: flex; gap: 8px; flex-wrap: wrap; }
    .filter-pill {
      padding: 6px 14px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 700;
      text-decoration: none;
      border: 1px solid var(--adm-border);
      color: var(--adm-text-muted);
      background: rgba(255,255,255,0.03);
      transition: all 0.2s;
    }
    .filter-pill:hover, .filter-pill.active {
      background: var(--adm-primary);
      border-color: var(--adm-primary);
      color: #fff;
    }

    .search-form { display: flex; gap: 8px; }
    .search-input {
      background: rgba(0,0,0,0.25);
      border: 1px solid var(--adm-border);
      padding: 8px 14px;
      border-radius: 8px;
      color: #fff;
      font-family: inherit;
      font-size: 0.9rem;
      min-width: 240px;
      outline: none;
    }
    .search-input:focus { border-color: var(--adm-accent); }
    .search-btn {
      background: rgba(255,255,255,0.08);
      border: 1px solid var(--adm-border);
      color: var(--adm-text);
      padding: 8px 14px;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
    }

    /* Table */
    .table-card {
      background: var(--adm-card-bg);
      border: 1px solid var(--adm-border);
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }
    .table-wrap { width: 100%; overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.92rem; }
    th {
      background: rgba(0,0,0,0.25);
      padding: 14px 18px;
      color: var(--adm-text-muted);
      font-weight: 700;
      border-bottom: 1px solid var(--adm-border);
      white-space: nowrap;
    }
    td {
      padding: 14px 18px;
      border-bottom: 1px solid rgba(255,255,255,0.05);
      vertical-align: middle;
    }
    tr:hover { background: rgba(255,255,255,0.02); }

    .badge {
      display: inline-block;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 0.78rem;
      font-weight: 700;
    }
    .badge-pub { background: rgba(16, 185, 129, 0.2); color: #34d399; }
    .badge-rev { background: rgba(245, 158, 11, 0.2); color: #fbbf24; }
    .badge-rej { background: rgba(239, 68, 68, 0.2); color: #f87171; }
    .badge-dft { background: rgba(148, 163, 184, 0.2); color: #cbd5e1; }

    .btn-action {
      padding: 5px 12px;
      border-radius: 6px;
      font-size: 0.82rem;
      font-weight: 600;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 4px;
      border: 1px solid var(--adm-border);
      color: var(--adm-text);
      background: transparent;
      cursor: pointer;
      transition: all 0.15s;
    }
    .btn-action:hover { border-color: var(--adm-accent); color: var(--adm-accent); }
    .btn-del:hover { border-color: #ef4444; color: #f87171; background: rgba(239, 68, 68, 0.1); }
  </style>
</head>
<body>
  <?php 
  $activeTab = 'writer_posts';
  require dirname(__DIR__) . '/admin/partials/header.php'; 
  ?>

  <div class="posts-wrap">
    <div class="header-row">
      <div class="header-title">
        <span>📝</span>
        <span>আমার সংবাদসমূহ (My Posts)</span>
      </div>
      <a href="<?= $appUrl ?>/writer/posts/create" class="btn-create">✍️ নতুন সংবাদ লিখুন</a>
    </div>

    <?php if (!empty($success)): ?>
      <div class="alert alert-success">✓ <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="alert alert-error">✕ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Filter & Search Bar -->
    <div class="filter-card">
      <div class="pill-group">
        <a href="<?= $appUrl ?>/writer/posts" class="filter-pill <?= empty($statusFilter) ? 'active' : '' ?>">সবগুলো</a>
        <a href="<?= $appUrl ?>/writer/posts?status=published" class="filter-pill <?= $statusFilter === 'published' ? 'active' : '' ?>">প্রকাশিত</a>
        <a href="<?= $appUrl ?>/writer/posts?status=pending_review" class="filter-pill <?= $statusFilter === 'pending_review' ? 'active' : '' ?>">পর্যালোচনাধীন</a>
        <a href="<?= $appUrl ?>/writer/posts?status=rejected" class="filter-pill <?= $statusFilter === 'rejected' ? 'active' : '' ?>">প্রত্যাখ্যাত</a>
        <a href="<?= $appUrl ?>/writer/posts?status=draft" class="filter-pill <?= $statusFilter === 'draft' ? 'active' : '' ?>">খসড়া</a>
      </div>

      <form method="GET" action="<?= $appUrl ?>/writer/posts" class="search-form">
        <?php if (!empty($statusFilter)): ?>
          <input type="hidden" name="status" value="<?= htmlspecialchars($statusFilter) ?>">
        <?php endif; ?>
        <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="শিরোনাম দিয়ে খুঁজুন..." class="search-input">
        <button type="submit" class="search-btn">🔍 খুঁজুন</button>
      </form>
    </div>

    <!-- Posts Table -->
    <div class="table-card">
      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th style="width: 70px;">ছবি</th>
              <th>শিরোনাম ও বিবরণ</th>
              <th>ক্যাটাগরি</th>
              <th>স্ট্যাটাস</th>
              <th>ভিউ</th>
              <th>তারিখ</th>
              <th style="text-align: right;">অ্যাকশন</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($posts)): ?>
              <tr>
                <td colspan="7" style="text-align: center; padding: 48px; color: var(--adm-text-muted);">
                  কোনো সংবাদ পাওয়া যায়নি। <a href="<?= $appUrl ?>/writer/posts/create" style="color: #10b981; font-weight: 700;">এখনই নতুন সংবাদ লিখুন ↗</a>
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($posts as $p): ?>
                <tr>
                  <td>
                    <?php if (!empty($p['featured_image'])): ?>
                      <img src="<?= $appUrl ?>/<?= htmlspecialchars($p['featured_image']) ?>" alt="" style="width: 54px; height: 38px; object-fit: cover; border-radius: 4px;">
                    <?php else: ?>
                      <div style="width: 54px; height: 38px; background: rgba(255,255,255,0.06); border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; color: var(--adm-text-muted);">ছবি নেই</div>
                    <?php endif; ?>
                  </td>
                  <td style="max-width: 420px;">
                    <div style="font-weight: 700; font-size: 0.98rem; margin-bottom: 4px;">
                      <?= htmlspecialchars($p['title']) ?>
                    </div>
                    <?php if (!empty($p['rejection_reason'])): ?>
                      <div style="background: rgba(239, 68, 68, 0.15); border-left: 3px solid #ef4444; padding: 6px 10px; border-radius: 4px; margin-top: 6px; font-size: 0.82rem; color: #fca5a5;">
                        <strong>⚠️ সম্পাদকের পর্যালোচনা মন্তব্য:</strong> <?= htmlspecialchars($p['rejection_reason']) ?>
                        <div style="font-size: 0.76rem; color: #f87171; margin-top: 2px;">সংবাদটি সম্পাদনা করে পুনরায় পর্যালোচনার জন্য পাঠাতে পারেন।</div>
                      </div>
                    <?php endif; ?>
                  </td>
                  <td>
                    <span style="background: rgba(255,255,255,0.06); padding: 3px 8px; border-radius: 4px; font-size: 0.82rem;">
                      <?= htmlspecialchars($p['category_name'] ?? 'সাধারণ') ?>
                    </span>
                  </td>
                  <td>
                    <?php if ($p['status'] === 'published'): ?>
                      <span class="badge badge-pub">প্রকাশিত</span>
                    <?php elseif ($p['status'] === 'pending_review'): ?>
                      <span class="badge badge-rev">পর্যালোচনাধীন</span>
                    <?php elseif ($p['status'] === 'rejected'): ?>
                      <span class="badge badge-rej">প্রত্যাখ্যাত</span>
                    <?php else: ?>
                      <span class="badge badge-dft">খসড়া</span>
                    <?php endif; ?>
                  </td>
                  <td style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700;">
                    <?= number_format((int)$p['views']) ?>
                  </td>
                  <td style="font-size: 0.84rem; color: var(--adm-text-muted); white-space: nowrap;">
                    <?= date('d M, Y', strtotime($p['created_at'])) ?>
                  </td>
                  <td style="text-align: right; white-space: nowrap;">
                    <div style="display: inline-flex; gap: 6px; align-items: center;">
                      <a href="<?= $appUrl ?>/writer/posts/edit?id=<?= (int)$p['id'] ?>" class="btn-action">✏️ সম্পাদনা</a>
                      
                      <?php if ($p['status'] === 'published'): ?>
                        <a href="<?= $appUrl ?>/news/<?= htmlspecialchars($p['category_slug'] ?? 'national') ?>/<?= (int)$p['id'] ?>/<?= urlencode($p['slug']) ?>" target="_blank" class="btn-action" style="color: #38bdf8;">👁️ দেখুন</a>
                      <?php endif; ?>

                      <form method="POST" action="<?= $appUrl ?>/writer/posts/delete" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই সংবাদটি মুছে ফেলতে চান? এটি পুনরুদ্ধার করা যাবে না।');" style="display: inline;">
                        <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
                        <input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
                        <button type="submit" class="btn-action btn-del" title="মুছে ফেলুন">🗑️</button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>

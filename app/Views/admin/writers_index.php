<?php
$adminPath = $adminPath ?? 'lens-desk';
$appUrl = rtrim($appUrl ?? '', '/');
$writers = $writers ?? [];
$readers = $readers ?? [];
$pendingCount = $pendingCount ?? 0;
$activeTab = 'writers';
?>
<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle ?? 'লেখক নিয়ন্ত্রণ ও ব্যবস্থাপনা') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Hind Siliguri', sans-serif; background: var(--adm-bg); color: var(--adm-text); }
    .writers-container { max-width: 1360px; margin: 24px auto 60px; padding: 0 20px; }

    /* Top Page Header */
    .page-header-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 16px;
      margin-bottom: 24px;
    }
    .page-title { font-size: 1.6rem; font-weight: 800; display: flex; align-items: center; gap: 10px; }
    .pending-badge {
      background: #f59e0b;
      color: #000;
      font-size: 0.8rem;
      font-weight: 800;
      padding: 3px 10px;
      border-radius: 20px;
    }
    .header-action-btns {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
    }
    .btn-create-writer {
      background: #0E7A3A;
      color: #fff;
      border: none;
      padding: 9px 18px;
      border-radius: 8px;
      font-weight: 700;
      font-size: 0.92rem;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: background 0.2s, transform 0.15s;
    }
    .btn-create-writer:hover {
      background: #0a5c2b;
      transform: translateY(-1px);
    }
    .btn-promote-user {
      background: rgba(56, 189, 248, 0.15);
      border: 1px solid rgba(56, 189, 248, 0.4);
      color: #38bdf8;
      padding: 9px 16px;
      border-radius: 8px;
      font-weight: 700;
      font-size: 0.9rem;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.2s;
    }
    .btn-promote-user:hover {
      background: rgba(56, 189, 248, 0.25);
      border-color: #38bdf8;
    }

    .alert-banner {
      padding: 12px 16px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-weight: 600;
      font-size: 0.92rem;
    }
    .alert-success { background: rgba(14, 122, 58, 0.2); border: 1px solid #10b981; color: #86efac; }
    .alert-error { background: rgba(227, 30, 36, 0.2); border: 1px solid #ef4444; color: #fca5a5; }

    /* Filters Bar */
    .filter-card {
      background: var(--adm-card-bg);
      border: 1px solid var(--adm-border);
      border-radius: 12px;
      padding: 16px 20px;
      margin-bottom: 24px;
      display: flex;
      gap: 14px;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
    }
    .filter-form { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
    .form-select, .form-input {
      padding: 8px 12px;
      background: var(--adm-bg);
      border: 1px solid var(--adm-border);
      border-radius: 6px;
      color: var(--adm-text);
      font-size: 0.88rem;
      font-family: inherit;
      outline: none;
    }
    .btn-filter {
      background: var(--adm-accent);
      color: #fff;
      border: none;
      padding: 8px 16px;
      border-radius: 6px;
      font-weight: 700;
      cursor: pointer;
    }

    /* Writers Table */
    .table-card {
      background: var(--adm-card-bg);
      border: 1px solid var(--adm-border);
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }
    .table-responsive { width: 100%; overflow-x: auto; }
    table.writers-table {
      width: 100%;
      border-collapse: collapse;
      text-align: left;
      font-size: 0.9rem;
    }
    table.writers-table th {
      background: rgba(0,0,0,0.25);
      padding: 14px 16px;
      color: var(--adm-text-muted);
      font-weight: 700;
      border-bottom: 1px solid var(--adm-border);
      white-space: nowrap;
    }
    table.writers-table td {
      padding: 14px 16px;
      border-bottom: 1px solid rgba(255,255,255,0.05);
      vertical-align: middle;
    }
    table.writers-table tr:hover { background: rgba(255,255,255,0.02); }

    .writer-profile-cell { display: flex; align-items: center; gap: 12px; }
    .avatar-img {
      width: 42px;
      height: 42px;
      border-radius: 50%;
      object-fit: cover;
      background: #071630;
      border: 1.5px solid var(--adm-border);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
    }
    .writer-name-title { font-weight: 700; color: var(--adm-text); font-size: 0.96rem; }
    .writer-email-sub { font-size: 0.8rem; color: var(--adm-text-muted); }

    /* Badges */
    .status-pill {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 0.78rem;
      font-weight: 700;
    }
    .status-active { background: rgba(14, 122, 58, 0.2); color: #86efac; border: 1px solid #10b981; }
    .status-pending { background: rgba(245, 158, 11, 0.2); color: #fde047; border: 1px solid #f59e0b; }
    .status-suspended { background: rgba(227, 30, 36, 0.2); color: #fca5a5; border: 1px solid #ef4444; }
    .status-removed { background: rgba(100, 116, 139, 0.2); color: #cbd5e1; border: 1px solid #64748b; }

    .perm-badge {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 0.78rem;
      font-weight: 700;
    }
    .perm-direct { background: rgba(56, 189, 248, 0.2); color: #38bdf8; border: 1px solid #38bdf8; }
    .perm-review { background: rgba(192, 132, 252, 0.2); color: #c084fc; border: 1px solid #c084fc; }

    /* Action Buttons */
    .action-group { display: flex; gap: 6px; flex-wrap: wrap; }
    .btn-tbl {
      padding: 5px 10px;
      border-radius: 6px;
      font-size: 0.8rem;
      font-weight: 600;
      border: 1px solid var(--adm-border);
      background: var(--adm-bg);
      color: var(--adm-text);
      cursor: pointer;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 4px;
      transition: all 0.15s;
    }
    .btn-tbl:hover { border-color: var(--adm-accent); color: var(--adm-accent); }
    .btn-approve { background: rgba(14, 122, 58, 0.25); color: #86efac; border-color: #10b981; }
    .btn-approve:hover { background: #0E7A3A; color: #fff; }
    .btn-reject { background: rgba(227, 30, 36, 0.25); color: #fca5a5; border-color: #ef4444; }
    .btn-reject:hover { background: #e31e24; color: #fff; }
    .btn-edit-perm { color: #38bdf8; }
    .btn-edit-perm:hover { background: rgba(56, 189, 248, 0.15); }
    .btn-key { color: #fbbf24; }
    .btn-key:hover { background: rgba(245, 158, 11, 0.15); border-color: #f59e0b; }
    .btn-demote { color: #cbd5e1; }
    .btn-demote:hover { background: rgba(148, 163, 184, 0.2); }
    .btn-suspend { color: #f87171; }
    .btn-suspend:hover { background: rgba(239, 68, 68, 0.15); border-color: #ef4444; }
    .btn-view { color: #38bdf8; }
    .btn-remove { color: #f87171; }

    /* Modals */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100vw;
      height: 100vh;
      background: rgba(3, 10, 24, 0.75);
      backdrop-filter: blur(4px);
      z-index: 9999;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 16px;
    }
    .modal-box {
      background: var(--adm-card-bg);
      border: 1px solid var(--adm-border);
      border-radius: 14px;
      max-width: 520px;
      width: 100%;
      box-shadow: 0 20px 40px rgba(0,0,0,0.5);
      overflow: hidden;
      animation: modalFadeIn 0.2s ease-out;
    }
    @keyframes modalFadeIn {
      from { opacity: 0; transform: scale(0.96); }
      to { opacity: 1; transform: scale(1); }
    }
    .modal-head {
      padding: 16px 20px;
      border-bottom: 1px solid var(--adm-border);
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: var(--adm-nav-bg);
    }
    .modal-head h3 { font-size: 1.15rem; font-weight: 700; color: var(--adm-text); }
    .modal-close-btn {
      background: none;
      border: none;
      color: var(--adm-text-muted);
      font-size: 1.25rem;
      cursor: pointer;
    }
    .modal-close-btn:hover { color: #f87171; }
    .modal-body { padding: 20px; font-size: 0.92rem; line-height: 1.5; }
    .modal-actions {
      padding: 14px 20px;
      background: var(--adm-nav-bg);
      border-top: 1px solid var(--adm-border);
      display: flex;
      justify-content: flex-end;
      gap: 10px;
    }
    .form-group-modal {
      margin-bottom: 14px;
    }
    .form-group-modal label {
      display: block;
      font-weight: 700;
      font-size: 0.86rem;
      margin-bottom: 6px;
      color: var(--adm-text-muted);
    }
    .form-group-modal input, .form-group-modal select, .form-group-modal textarea {
      width: 100%;
      padding: 9px 12px;
      background: var(--adm-bg);
      border: 1px solid var(--adm-border);
      border-radius: 6px;
      color: var(--adm-text);
      font-size: 0.9rem;
      font-family: inherit;
      outline: none;
    }
    .form-group-modal input:focus, .form-group-modal select:focus, .form-group-modal textarea:focus {
      border-color: var(--adm-accent);
    }
  </style>
</head>
<body>
  <?php require dirname(__DIR__) . '/admin/partials/header.php'; ?>

  <div class="writers-container">

    <!-- Top Page Header & Primary Actions -->
    <div class="page-header-row">
      <div>
        <h1 class="page-title">
          <span>👥</span>
          <span data-bn="লেখক নিয়ন্ত্রণ ও প্রশাসন" data-en="Writer Control & Management">লেখক নিয়ন্ত্রণ ও প্রশাসন</span>
          <?php if ($pendingCount > 0): ?>
            <span class="pending-badge" data-bn="<?= $pendingCount ?>টি অপেক্ষমান" data-en="<?= $pendingCount ?> Pending"><?= $pendingCount ?>টি অপেক্ষমান</span>
          <?php endif; ?>
        </h1>
        <p style="color: var(--adm-text-muted); font-size: 0.9rem; margin-top: 4px;" data-bn="স্বতন্ত্র লেখক অ্যাকাউন্ট তৈরি, পারমিশন লেভেল, পাসওয়ার্ড রিসেট ও পদোন্নতি ব্যবস্থাপনা।" data-en="Manage dedicated writer accounts, permission tiers, password resets, and user promotions.">
          স্বতন্ত্র লেখক অ্যাকাউন্ট তৈরি, পারমিশন লেভেল, পাসওয়ার্ড রিসেট ও পদোন্নতি ব্যবস্থাপনা।
        </p>
      </div>

      <div class="header-action-btns">
        <!-- Create Writer Button (Opens Modal) -->
        <button type="button" class="btn-create-writer" onclick="openCreateWriterModal()">
          <span>✍️</span>
          <span data-bn="নতুন লেখক তৈরি করুন" data-en="Create New Writer">নতুন লেখক তৈরি করুন</span>
        </button>

        <!-- Promote User to Writer Button -->
        <button type="button" class="btn-promote-user" onclick="openPromoteModal()">
          <span>👤⬆️</span>
          <span data-bn="পাঠককে লেখক বানান" data-en="Promote User to Writer">পাঠককে লেখক বানান</span>
        </button>
      </div>
    </div>

    <!-- Alert Banners -->
    <?php if (!empty($success)): ?>
      <div class="alert-banner alert-success">✓ <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
      <div class="alert-banner alert-error">✕ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Search & Filter Card -->
    <div class="filter-card">
      <form method="GET" action="/<?= $adminPath ?>/writers" class="filter-form">
        <input 
          type="text" 
          name="q" 
          class="form-input" 
          placeholder="নাম, ইমেইল বা ফোন দিয়ে খুঁজুন..." 
          value="<?= htmlspecialchars($search) ?>" 
          style="min-width: 240px;"
          data-placeholder-bn="নাম, ইমেইল বা ফোন দিয়ে খুঁজুন..."
          data-placeholder-en="Search by name, email, or phone..."
        >

        <select name="status" class="form-select">
          <option value="" data-bn="সকল স্ট্যাটাস" data-en="All Statuses">সকল স্ট্যাটাস</option>
          <option value="active" <?= $statusFilter === 'active' ? 'selected' : '' ?> data-bn="সক্রিয় (Active)" data-en="Active">সক্রিয় (Active)</option>
          <option value="pending" <?= $statusFilter === 'pending' ? 'selected' : '' ?> data-bn="অপেক্ষমান (Pending)" data-en="Pending">অপেক্ষমান (Pending)</option>
          <option value="suspended" <?= $statusFilter === 'suspended' ? 'selected' : '' ?> data-bn="স্থগিত (Suspended)" data-en="Suspended">স্থগিত (Suspended)</option>
        </select>

        <select name="permission" class="form-select">
          <option value="" data-bn="সকল পারমিশন" data-en="All Permissions">সকল পারমিশন</option>
          <option value="direct_publish" <?= $permFilter === 'direct_publish' ? 'selected' : '' ?> data-bn="সরাসরি প্রকাশ (Direct Publish)" data-en="Direct Publish">সরাসরি প্রকাশ (Direct Publish)</option>
          <option value="editor_review" <?= $permFilter === 'editor_review' ? 'selected' : '' ?> data-bn="রিভিউ সাপেক্ষে (Editor Review)" data-en="Editor Review">রিভিউ সাপেক্ষে (Editor Review)</option>
        </select>

        <button type="submit" class="btn-filter" data-bn="ফিল্টার" data-en="Filter">ফিল্টার</button>
        <?php if (!empty($search) || !empty($statusFilter) || !empty($permFilter)): ?>
          <a href="/<?= $adminPath ?>/writers" class="btn-tbl" style="padding: 8px 12px;" data-bn="রিসেট" data-en="Reset">রিসেট</a>
        <?php endif; ?>
      </form>

      <div>
        <span style="font-size: 0.88rem; color: var(--adm-text-muted);">
          <span data-bn="সর্বমোট লেখক:" data-en="Total Writers:">সর্বমোট লেখক:</span> <strong><?= count($writers) ?></strong> <span data-bn="জন" data-en="users">জন</span>
        </span>
      </div>
    </div>

    <!-- Writers List Table -->
    <div class="table-card">
      <div class="table-responsive">
        <table class="writers-table">
          <thead>
            <tr>
              <th data-bn="লেখক প্রোফাইল" data-en="Writer Profile">লেখক প্রোফাইল</th>
              <th data-bn="স্ট্যাটাস" data-en="Status">স্ট্যাটাস</th>
              <th data-bn="পারমিশন লেভেল" data-en="Permission Level">পারমিশন লেভেল</th>
              <th data-bn="মোট সংবাদ" data-en="Total Posts">মোট সংবাদ</th>
              <th data-bn="মোট ভিউ" data-en="Total Views">মোট ভিউ</th>
              <th data-bn="যোগদানের তারিখ" data-en="Joined Date">যোগদানের তারিখ</th>
              <th data-bn="অ্যাকশন" data-en="Actions">অ্যাকশন</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($writers)): ?>
              <tr>
                <td colspan="7" style="text-align: center; padding: 40px; color: var(--adm-text-muted);" data-bn="কোনো লেখকের তথ্য পাওয়া যায়নি।" data-en="No writers found.">
                  কোনো লেখকের তথ্য পাওয়া যায়নি।
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($writers as $w): ?>
                <tr>
                  <!-- Profile -->
                  <td>
                    <div class="writer-profile-cell">
                      <?php if (!empty($w['avatar'])): ?>
                        <img src="<?= htmlspecialchars($w['avatar']) ?>" alt="" class="avatar-img">
                      <?php else: ?>
                        <div class="avatar-img">✍️</div>
                      <?php endif; ?>
                      <div>
                        <div class="writer-name-title"><?= htmlspecialchars($w['name']) ?></div>
                        <div class="writer-email-sub"><?= htmlspecialchars($w['email']) ?> <?= !empty($w['phone']) ? '• ' . htmlspecialchars($w['phone']) : '' ?></div>
                      </div>
                    </div>
                  </td>

                  <!-- Status -->
                  <td>
                    <?php if ($w['status'] === 'active'): ?>
                      <span class="status-pill status-active" data-bn="সক্রিয়" data-en="Active">সক্রিয়</span>
                    <?php elseif ($w['status'] === 'pending'): ?>
                      <span class="status-pill status-pending" data-bn="অপেক্ষমান" data-en="Pending">অপেক্ষমান</span>
                    <?php elseif ($w['status'] === 'suspended'): ?>
                      <span class="status-pill status-suspended" data-bn="স্থগিত" data-en="Suspended">স্থগিত</span>
                    <?php else: ?>
                      <span class="status-pill status-removed"><?= htmlspecialchars($w['status']) ?></span>
                    <?php endif; ?>
                  </td>

                  <!-- Permission Level -->
                  <td>
                    <?php if ($w['permission_level'] === 'direct_publish'): ?>
                      <span class="perm-badge perm-direct" data-bn="⚡ সরাসরি প্রকাশ" data-en="⚡ Direct Publish">⚡ সরাসরি প্রকাশ</span>
                    <?php elseif ($w['permission_level'] === 'editor_review'): ?>
                      <span class="perm-badge perm-review" data-bn="👁️ রিভিউ সাপেক্ষে" data-en="👁️ Editor Review">👁️ রিভিউ সাপেক্ষে</span>
                    <?php else: ?>
                      <span style="color: var(--adm-text-muted); font-size: 0.8rem;" data-bn="নির্ধারিত নয়" data-en="Not Set">নির্ধারিত নয়</span>
                    <?php endif; ?>
                  </td>

                  <!-- Stats -->
                  <td><strong><?= (int) ($w['total_posts'] ?? 0) ?></strong> <span data-bn="টি" data-en="posts">টি</span></td>
                  <td><strong><?= number_format((int) ($w['total_views'] ?? 0)) ?></strong></td>
                  <td><?= date('d M, Y', strtotime($w['created_at'])) ?></td>

                  <!-- Actions -->
                  <td>
                    <div class="action-group">
                      <?php if ($w['status'] === 'pending'): ?>
                        <!-- Quick Approve Trigger -->
                        <button type="button" class="btn-tbl btn-approve" onclick="openApproveModal(<?= $w['id'] ?>, '<?= htmlspecialchars(addslashes($w['name'])) ?>')" data-bn="অনুমোদন ✓" data-en="Approve ✓">অনুমোদন ✓</button>
                        <button type="button" class="btn-tbl btn-reject" onclick="openRejectModal(<?= $w['id'] ?>, '<?= htmlspecialchars(addslashes($w['name'])) ?>')" data-bn="বাতিল ✕" data-en="Reject ✕">বাতিল ✕</button>
                      <?php else: ?>
                        <!-- Change Permission -->
                        <button type="button" class="btn-tbl btn-edit-perm" onclick="openPermModal(<?= $w['id'] ?>, '<?= htmlspecialchars(addslashes($w['name'])) ?>', '<?= $w['permission_level'] ?? 'editor_review' ?>')" title="পারমিশন লেভেল পরিবর্তন" data-bn="পারমিশন" data-en="Permission">পারমিশন</button>
                        
                        <!-- Reset Password Trigger -->
                        <button type="button" class="btn-tbl btn-key" onclick="openResetPasswordModal(<?= $w['id'] ?>, '<?= htmlspecialchars(addslashes($w['name'])) ?>')" title="লেখকের পাসওয়ার্ড রিসেট করুন" data-bn="🔑 পাসওয়ার্ড" data-en="🔑 Password">🔑 পাসওয়ার্ড</button>

                        <!-- Suspend / Activate -->
                        <?php if ($w['status'] === 'active'): ?>
                          <button type="button" class="btn-tbl btn-suspend" onclick="openSuspendModal(<?= $w['id'] ?>, '<?= htmlspecialchars(addslashes($w['name'])) ?>')" data-bn="স্থগিত" data-en="Suspend">স্থগিত</button>
                        <?php elseif ($w['status'] === 'suspended'): ?>
                          <form action="/<?= $adminPath ?>/writers/toggle-status" method="post" style="display:inline;" onsubmit="return confirm('লেখককে পুনরায় সক্রিয় করতে চান?');">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                            <input type="hidden" name="id" value="<?= $w['id'] ?>">
                            <input type="hidden" name="status_action" value="activate">
                            <button type="submit" class="btn-tbl btn-approve" data-bn="সক্রিয় করুন" data-en="Activate">সক্রিয় করুন</button>
                          </form>
                        <?php endif; ?>

                        <!-- Demote to Reader Option -->
                        <button type="button" class="btn-tbl btn-demote" onclick="openDemoteModal(<?= $w['id'] ?>, '<?= htmlspecialchars(addslashes($w['name'])) ?>')" title="পাঠক পদে রূপান্তর করুন" data-bn="পাঠক করুন" data-en="Demote">পাঠক করুন</button>
                      <?php endif; ?>

                      <!-- View Details -->
                      <a href="/<?= $adminPath ?>/writers/show?id=<?= $w['id'] ?>" class="btn-tbl btn-view" title="বিস্তারিত প্রোফাইল ও সংবাদ তালিকা" data-bn="প্রোফাইল ➔" data-en="Profile ➔">প্রোফাইল ➔</a>

                      <!-- Remove Writer -->
                      <button type="button" class="btn-tbl btn-remove" onclick="openRemoveModal(<?= $w['id'] ?>, '<?= htmlspecialchars(addslashes($w['name'])) ?>')" title="লেখককে অপসারণ করুন" data-bn="মুছুন" data-en="Delete">মুছুন</button>
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

  <!-- ================= MODALS ================= -->

  <!-- 1. Create Writer Modal (Admin Controlled) -->
  <div class="modal-overlay" id="createWriterModal">
    <div class="modal-box">
      <div class="modal-head">
        <h3 data-bn="✍️ নতুন লেখক অ্যাকাউন্ট তৈরি করুন" data-en="✍️ Create New Writer Account">✍️ নতুন লেখক অ্যাকাউন্ট তৈরি করুন</h3>
        <button class="modal-close-btn" onclick="closeModal('createWriterModal')">✕</button>
      </div>
      <form action="/<?= $adminPath ?>/writers/create" method="post">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <div class="modal-body">
          <p style="margin-bottom: 14px; font-size: 0.86rem; color: var(--adm-text-muted);" data-bn="অ্যাডমিন প্যানেল থেকে সরাসরি একজন নতুন লেখক যুক্ত করুন। তৈরির পর লেখক তার ইমেইল ও পাসওয়ার্ড দিয়ে /writer/login এ প্রবেশ করতে পারবেন।" data-en="Directly provision a new writer. The writer will be able to log in at /writer/login using the assigned credentials.">
            অ্যাডমিন প্যানেল থেকে সরাসরি একজন নতুন লেখক যুক্ত করুন। তৈরির পর লেখক তার ইমেইল ও পাসওয়ার্ড দিয়ে /writer/login এ প্রবেশ করতে পারবেন।
          </p>

          <div class="form-group-modal">
            <label data-bn="লেখকের পূর্ণ নাম *" data-en="Writer Full Name *">লেখকের পূর্ণ নাম *</label>
            <input type="text" name="name" required placeholder="উদাঃ মোঃ নাফিল উদ্দিন" data-placeholder-bn="উদাঃ মোঃ নাফিল উদ্দিন" data-placeholder-en="e.g. John Doe">
          </div>

          <div class="form-group-modal">
            <label data-bn="ইমেইল ঠিকানা *" data-en="Email Address *">ইমেইল ঠিকানা *</label>
            <input type="email" name="email" required placeholder="writer@newslensbd.com">
          </div>

          <div class="form-group-modal">
            <label data-bn="পাসওয়ার্ড (কমপক্ষে ৬ অক্ষর) *" data-en="Password (min 6 chars) *">পাসওয়ার্ড (কমপক্ষে ৬ অক্ষর) *</label>
            <input type="password" name="password" required minlength="6" placeholder="••••••••">
          </div>

          <div class="form-group-modal">
            <label data-bn="পারমিশন লেভেল *" data-en="Permission Level *">পারমিশন লেভেল *</label>
            <select name="permission_level">
              <option value="editor_review" selected data-bn="সম্পাদকীয় পর্যালোচনা (Editor Review) - এডিটর অনুমোদন দিলে লাইভ হবে" data-en="Editor Review - Posts require editorial approval">সম্পাদকীয় পর্যালোচনা (Editor Review)</option>
              <option value="direct_publish" data-bn="সরাসরি প্রকাশ (Direct Publish) - কোনো রিভিউ ছাড়া সাথে সাথে লাইভ হবে" data-en="Direct Publish - Posts go live instantly without review">সরাসরি প্রকাশ (Direct Publish)</option>
            </select>
          </div>

          <div class="form-group-modal">
            <label data-bn="ফোন / মোবাইল নম্বর (ঐচ্ছিক)" data-en="Phone Number (Optional)">ফোন / মোবাইল নম্বর (ঐচ্ছিক)</label>
            <input type="text" name="phone" placeholder="01700000000">
          </div>

          <div class="form-group-modal">
            <label data-bn="সংক্ষিপ্ত পরিচিতি / বায়ো (ঐচ্ছিক)" data-en="Short Bio (Optional)">সংক্ষিপ্ত পরিচিতি / বায়ো (ঐচ্ছিক)</label>
            <textarea name="bio" rows="2" placeholder="কলামিস্ট ও রাজনৈতিক বিশ্লেষক..."></textarea>
          </div>
        </div>
        <div class="modal-actions">
          <button type="button" class="btn-tbl btn-view" onclick="closeModal('createWriterModal')" data-bn="বাতিল" data-en="Cancel">বাতিল</button>
          <button type="submit" class="btn-create-writer" data-bn="লেখক তৈরি করুন ➔" data-en="Create Writer ➔">লেখক তৈরি করুন ➔</button>
        </div>
      </form>
    </div>
  </div>

  <!-- 2. Promote User to Writer Modal -->
  <div class="modal-overlay" id="promoteModal">
    <div class="modal-box">
      <div class="modal-head">
        <h3 data-bn="👤 সাধারণ পাঠককে লেখকে রূপান্তর" data-en="👤 Promote Reader to Writer">👤 সাধারণ পাঠককে লেখকে রূপান্তর</h3>
        <button class="modal-close-btn" onclick="closeModal('promoteModal')">✕</button>
      </div>
      <form action="/<?= $adminPath ?>/writers/promote" method="post">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <div class="modal-body">
          <p style="margin-bottom: 14px; font-size: 0.86rem; color: var(--adm-text-muted);" data-bn="বিদ্যমান সাধারণ পাঠক অ্যাকাউন্টকে লেখকের পদে উন্নীত করুন। তিনি /writer/login এ পূর্বের পাসওয়ার্ড দিয়েই প্রবেশ করতে পারবেন।" data-en="Promote an existing reader to writer role. They will access the writer portal using their existing password.">
            বিদ্যমান সাধারণ পাঠক অ্যাকাউন্টকে লেখকের পদে উন্নীত করুন। তিনি /writer/login এ পূর্বের পাসওয়ার্ড দিয়েই প্রবেশ করতে পারবেন।
          </p>

          <div class="form-group-modal">
            <label data-bn="নিবন্ধিত পাঠক নির্বাচন করুন *" data-en="Select Registered Reader *">নিবন্ধিত পাঠক নির্বাচন করুন *</label>
            <select name="user_id">
              <option value="" data-bn="-- পাঠক তালিকা থেকে নির্বাচন করুন --" data-en="-- Select from Reader List --">-- পাঠক তালিকা থেকে নির্বাচন করুন --</option>
              <?php foreach ($readers as $r): ?>
                <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['name']) ?> (<?= htmlspecialchars($r['email']) ?>)</option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group-modal">
            <label data-bn="অথবা পাঠকের ইমেইল লিখুন" data-en="Or Enter Reader Email">অথবা পাঠকের ইমেইল লিখুন</label>
            <input type="email" name="user_email" placeholder="reader@gmail.com">
          </div>

          <div class="form-group-modal">
            <label data-bn="পারমিশন লেভেল *" data-en="Permission Level *">পারমিশন লেভেল *</label>
            <select name="permission_level">
              <option value="editor_review" selected data-bn="সম্পাদকীয় পর্যালোচনা (Editor Review)" data-en="Editor Review">সম্পাদকীয় পর্যালোচনা (Editor Review)</option>
              <option value="direct_publish" data-bn="সরাসরি প্রকাশ (Direct Publish)" data-en="Direct Publish">সরাসরি প্রকাশ (Direct Publish)</option>
            </select>
          </div>
        </div>
        <div class="modal-actions">
          <button type="button" class="btn-tbl btn-view" onclick="closeModal('promoteModal')" data-bn="বাতিল" data-en="Cancel">বাতিল</button>
          <button type="submit" class="btn-create-writer" data-bn="পদোন্নতি দিন ➔" data-en="Promote ➔">পদোন্নতি দিন ➔</button>
        </div>
      </form>
    </div>
  </div>

  <!-- 3. Reset Password Modal -->
  <div class="modal-overlay" id="resetPasswordModal">
    <div class="modal-box">
      <div class="modal-head">
        <h3 data-bn="🔑 লেখকের পাসওয়ার্ড রিসেট করুন" data-en="🔑 Reset Writer Password">🔑 লেখকের পাসওয়ার্ড রিসেট করুন</h3>
        <button class="modal-close-btn" onclick="closeModal('resetPasswordModal')">✕</button>
      </div>
      <form action="/<?= $adminPath ?>/writers/reset-password" method="post">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <input type="hidden" name="id" id="resetWriterId">
        <div class="modal-body">
          <p style="margin-bottom: 14px;">
            <span data-bn="আপনি" data-en="You are resetting password for">আপনি</span> <strong id="resetWriterName" style="color:#fff;"></strong>-<span data-bn="এর নতুন পাসওয়ার্ড নির্ধারণ করছেন:" data-en="'s account:">এর নতুন পাসওয়ার্ড নির্ধারণ করছেন:</span>
          </p>

          <div class="form-group-modal">
            <label data-bn="নতুন পাসওয়ার্ড (কমপক্ষে ৬ অক্ষর) *" data-en="New Password (min 6 chars) *">নতুন পাসওয়ার্ড (কমপক্ষে ৬ অক্ষর) *</label>
            <input type="password" name="new_password" required minlength="6" placeholder="নতুন পাসওয়ার্ড লিখুন...">
          </div>
        </div>
        <div class="modal-actions">
          <button type="button" class="btn-tbl btn-view" onclick="closeModal('resetPasswordModal')" data-bn="বাতিল" data-en="Cancel">বাতিল</button>
          <button type="submit" class="btn-tbl btn-approve" style="padding: 10px 18px;" data-bn="পাসওয়ার্ড সংরক্ষণ করুন ➔" data-en="Save Password ➔">পাসওয়ার্ড সংরক্ষণ করুন ➔</button>
        </div>
      </form>
    </div>
  </div>

  <!-- 4. Demote Writer Modal -->
  <div class="modal-overlay" id="demoteModal">
    <div class="modal-box">
      <div class="modal-head">
        <h3 data-bn="পাঠক পদে স্থানান্তর (Demote)" data-en="Demote to Reader">পাঠক পদে স্থানান্তর (Demote)</h3>
        <button class="modal-close-btn" onclick="closeModal('demoteModal')">✕</button>
      </div>
      <form action="/<?= $adminPath ?>/writers/demote" method="post">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <input type="hidden" name="id" id="demoteWriterId">
        <div class="modal-body">
          <p style="margin-bottom: 12px;">
            <span data-bn="আপনি কি নিশ্চিত যে" data-en="Are you sure you want to demote">আপনি কি নিশ্চিত যে</span> <strong id="demoteWriterName" style="color:#fff;"></strong>-<span data-bn="কে লেখক পদ থেকে সাধারণ পাঠক পদে নামাতে চান?" data-en="from Writer to regular Reader?">কে লেখক পদ থেকে সাধারণ পাঠক পদে নামাতে চান?</span>
          </p>
          <p style="font-size: 0.82rem; color: #fbbf24;" data-bn="* লেখকের পূর্বে প্রকাশিত সকল সংবাদ সিস্টেমে লেখক হিসেবে বহাল ও অক্ষত থাকবে, তবে তিনি আর /writer/dashboard প্যানেলে প্রবেশ করতে পারবেন না।" data-en="* All previously published articles will remain safely credited, but they will no longer have access to the writer dashboard.">
            * লেখকের পূর্বে প্রকাশিত সকল সংবাদ সিস্টেমে লেখক হিসেবে বহাল ও অক্ষত থাকবে, তবে তিনি আর /writer/dashboard প্যানেলে প্রবেশ করতে পারবেন না।
          </p>
        </div>
        <div class="modal-actions">
          <button type="button" class="btn-tbl btn-view" onclick="closeModal('demoteModal')" data-bn="বাতিল" data-en="Cancel">বাতিল</button>
          <button type="submit" class="btn-tbl btn-reject" style="padding: 10px 18px;" data-bn="পাঠক পদে নামান" data-en="Demote to Reader">পাঠক পদে নামান</button>
        </div>
      </form>
    </div>
  </div>

  <!-- 5. Permission Modal -->
  <div class="modal-overlay" id="permModal">
    <div class="modal-box">
      <div class="modal-head">
        <h3 data-bn="পারমিশন লেভেল পরিবর্তন" data-en="Change Permission Level">পারমিশন লেভেল পরিবর্তন</h3>
        <button class="modal-close-btn" onclick="closeModal('permModal')">✕</button>
      </div>
      <form action="/<?= $adminPath ?>/writers/update-permission" method="post">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <input type="hidden" name="id" id="permWriterId">
        <div class="modal-body">
          <p style="margin-bottom: 14px;"><strong id="permWriterName" style="color:#fff;"></strong>-এর জন্য পারমিশন লেভেল নির্বাচন করুন:</p>
          
          <div style="margin-bottom: 12px;">
            <label style="display: flex; align-items: flex-start; gap: 8px; margin-bottom: 8px; cursor: pointer;">
              <input type="radio" name="permission_level" id="perm_direct" value="direct_publish" style="margin-top: 3px;">
              <div>
                <strong style="color: #38bdf8;">সরাসরি প্রকাশ (Direct Publish)</strong>
                <p style="font-size: 0.8rem; color: var(--adm-text-muted);">লেখকের প্রস্তুতকৃত সংবাদ এডিটরের রিভিউ ছাড়া সাথে সাথে পোর্টাল লাইভে প্রকাশিত হবে।</p>
              </div>
            </label>
            <label style="display: flex; align-items: flex-start; gap: 8px; cursor: pointer;">
              <input type="radio" name="permission_level" id="perm_review" value="editor_review" style="margin-top: 3px;">
              <div>
                <strong style="color: #c084fc;">সম্পাদকীয় পর্যালোচনা (Editor Review)</strong>
                <p style="font-size: 0.8rem; color: var(--adm-text-muted);">লেখকের সংবাদ প্রথমে এডিটরের রিভিউ কিউতে যাবে। এডিটর অনুমোদন করলে লাইভ হবে।</p>
              </div>
            </label>
          </div>
        </div>
        <div class="modal-actions">
          <button type="button" class="btn-tbl btn-view" onclick="closeModal('permModal')" data-bn="বাতিল" data-en="Cancel">বাতিল</button>
          <button type="submit" class="btn-tbl btn-approve" style="padding: 10px 18px;" data-bn="সংরক্ষণ করুন" data-en="Save Changes">সংরক্ষণ করুন</button>
        </div>
      </form>
    </div>
  </div>

  <!-- 6. Suspend Modal -->
  <div class="modal-overlay" id="suspendModal">
    <div class="modal-box">
      <div class="modal-head">
        <h3 data-bn="লেখক স্থগিতকরণ (Suspend)" data-en="Suspend Writer">লেখক স্থগিতকরণ (Suspend)</h3>
        <button class="modal-close-btn" onclick="closeModal('suspendModal')">✕</button>
      </div>
      <form action="/<?= $adminPath ?>/writers/toggle-status" method="post">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <input type="hidden" name="id" id="suspendWriterId">
        <input type="hidden" name="status_action" value="suspend">
        <div class="modal-body">
          <p style="margin-bottom: 12px;"><strong id="suspendWriterName" style="color:#fff;"></strong>-কে সাময়িকভাবে স্থগিত করার কারণ উল্লেখ করুন:</p>
          <textarea name="reason" class="form-input" style="width: 100%; min-height: 80px;" placeholder="স্থগিতকরণের কারণ..."></textarea>
          <p style="margin-top: 8px; font-size: 0.8rem; color: #fbbf24;">* স্থগিত থাকাকালীন লেখক লগইন বা পোস্ট করতে পারবেন না, তবে বিদ্যমান পোস্টগুলো অপরিবর্তিত থাকবে।</p>
        </div>
        <div class="modal-actions">
          <button type="button" class="btn-tbl btn-view" onclick="closeModal('suspendModal')" data-bn="বাতিল" data-en="Cancel">বাতিল</button>
          <button type="submit" class="btn-tbl btn-reject" style="padding: 10px 18px;" data-bn="স্থগিত করুন" data-en="Suspend Writer">স্থগিত করুন</button>
        </div>
      </form>
    </div>
  </div>

  <!-- 7. Remove/Delete Modal -->
  <div class="modal-overlay" id="removeModal">
    <div class="modal-box">
      <div class="modal-head">
        <h3 style="color: #ef4444;" data-bn="লেখক অপসারণ (Delete)" data-en="Remove Writer">লেখক অপসারণ (Delete)</h3>
        <button class="modal-close-btn" onclick="closeModal('removeModal')">✕</button>
      </div>
      <form action="/<?= $adminPath ?>/writers/remove" method="post">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <input type="hidden" name="id" id="removeWriterId">
        <div class="modal-body">
          <p style="margin-bottom: 14px;">আপনি <strong id="removeWriterName" style="color:#fff;"></strong>-কে অপসারণ করতে যাচ্ছেন। তার পূর্ববর্তী সংবাদগুলোর ক্ষেত্রে কী সিদ্ধান্ত নেবেন?</p>
          
          <div style="margin-bottom: 14px;">
            <label style="display: flex; align-items: flex-start; gap: 8px; margin-bottom: 8px; cursor: pointer;">
              <input type="radio" name="posts_action" value="keep" checked style="margin-top: 3px;">
              <div>
                <strong style="color: #fff;" data-bn="প্রকাশিত রাখুন (Former Writer হিসেবে)" data-en="Keep Published (as Former Writer)">প্রকাশিত রাখুন (Former Writer হিসেবে)</strong>
                <p style="font-size: 0.8rem; color: var(--adm-text-muted);" data-bn="সংবাদগুলো লাইভ থাকবে এবং বাইলাইনে 'সাবেক লেখক' লেবেল যুক্ত হবে।" data-en="Posts stay live with 'Former Writer' label.">সংবাদগুলো লাইভ থাকবে এবং বাইলাইনে 'সাবেক লেখক' লেবেল যুক্ত হবে।</p>
              </div>
            </label>
            <label style="display: flex; align-items: flex-start; gap: 8px; cursor: pointer;">
              <input type="radio" name="posts_action" value="unpublish" style="margin-top: 3px;">
              <div>
                <strong style="color: #fca5a5;" data-bn="সকল সংবাদ আনপাবলিশ (Draft) করুন" data-en="Unpublish all posts to Draft">সকল সংবাদ আনপাবলিশ (Draft) করুন</strong>
                <p style="font-size: 0.8rem; color: var(--adm-text-muted);" data-bn="লেখকের সকল প্রকাশিত সংবাদ খসড়া (Draft) অবস্থায় ফেরত যাবে।" data-en="All writer posts will be retracted to drafts.">লেখকের সকল প্রকাশিত সংবাদ খসড়া (Draft) অবস্থায় ফেরত যাবে।</p>
              </div>
            </label>
          </div>
        </div>
        <div class="modal-actions">
          <button type="button" class="btn-tbl btn-view" onclick="closeModal('removeModal')" data-bn="বাতিল" data-en="Cancel">বাতিল</button>
          <button type="submit" class="btn-tbl btn-reject" style="padding: 10px 18px;" data-bn="অপসারণ সম্পন্ন করুন" data-en="Confirm Removal">অপসারণ সম্পন্ন করুন</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function closeModal(id) {
      const el = document.getElementById(id);
      if (el) el.style.display = 'none';
    }
    function openCreateWriterModal() {
      document.getElementById('createWriterModal').style.display = 'flex';
    }
    function openPromoteModal() {
      document.getElementById('promoteModal').style.display = 'flex';
    }
    function openResetPasswordModal(id, name) {
      document.getElementById('resetWriterId').value = id;
      document.getElementById('resetWriterName').textContent = name;
      document.getElementById('resetPasswordModal').style.display = 'flex';
    }
    function openDemoteModal(id, name) {
      document.getElementById('demoteWriterId').value = id;
      document.getElementById('demoteWriterName').textContent = name;
      document.getElementById('demoteModal').style.display = 'flex';
    }
    function openApproveModal(id, name) {
      document.getElementById('approveWriterId').value = id;
      document.getElementById('approveWriterName').textContent = name;
      document.getElementById('approveModal').style.display = 'flex';
    }
    function openRejectModal(id, name) {
      document.getElementById('rejectWriterId').value = id;
      document.getElementById('rejectWriterName').textContent = name;
      document.getElementById('rejectModal').style.display = 'flex';
    }
    function openPermModal(id, name, currentPerm) {
      document.getElementById('permWriterId').value = id;
      document.getElementById('permWriterName').textContent = name;
      if (currentPerm === 'direct_publish') {
        document.getElementById('perm_direct').checked = true;
      } else {
        document.getElementById('perm_review').checked = true;
      }
      document.getElementById('permModal').style.display = 'flex';
    }
    function openSuspendModal(id, name) {
      document.getElementById('suspendWriterId').value = id;
      document.getElementById('suspendWriterName').textContent = name;
      document.getElementById('suspendModal').style.display = 'flex';
    }
    function openRemoveModal(id, name) {
      document.getElementById('removeWriterId').value = id;
      document.getElementById('removeWriterName').textContent = name;
      document.getElementById('removeModal').style.display = 'flex';
    }

    // Close modals on ESC key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay').forEach(m => m.style.display = 'none');
      }
    });
  </script>

</body>
</html>

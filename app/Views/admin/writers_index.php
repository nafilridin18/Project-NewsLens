<?php
$adminPath = $adminPath ?? 'lens-desk';
$appUrl = rtrim($appUrl ?? '', '/');
$writers = $writers ?? [];
$pendingCount = $pendingCount ?? 0;
$activeTab = 'writers';
?>
<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle ?? 'লেখক নিয়ন্ত্রণ') ?></title>
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
      border: 1px solid var(--adm-border);
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
    }
    .writer-name-title { font-weight: 700; color: var(--adm-text); font-size: 0.95rem; }
    .writer-email-sub { font-size: 0.8rem; color: var(--adm-text-muted); }

    /* Badges */
    .status-pill {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 0.78rem;
      font-weight: 700;
      text-transform: uppercase;
    }
    .status-active { background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid #10b981; }
    .status-pending { background: rgba(245, 158, 11, 0.2); color: #fbbf24; border: 1px solid #f59e0b; }
    .status-suspended { background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid #ef4444; }
    .status-removed { background: rgba(148, 163, 184, 0.2); color: #94a3b8; border: 1px solid #64748b; }

    .perm-badge {
      display: inline-block;
      padding: 4px 8px;
      border-radius: 6px;
      font-size: 0.78rem;
      font-weight: 700;
    }
    .perm-direct { background: rgba(56, 189, 248, 0.15); color: #38bdf8; border: 1px solid #38bdf8; }
    .perm-review { background: rgba(168, 85, 247, 0.15); color: #c084fc; border: 1px solid #a855f7; }

    /* Action Buttons */
    .action-group { display: flex; gap: 6px; flex-wrap: wrap; }
    .btn-tbl {
      padding: 6px 10px;
      border-radius: 6px;
      font-size: 0.8rem;
      font-weight: 700;
      text-decoration: none;
      border: 1px solid transparent;
      cursor: pointer;
      transition: all 0.2s;
    }
    .btn-approve { background: #0E7A3A; color: #fff; }
    .btn-approve:hover { background: #0a5c2b; }
    .btn-reject { background: #ef4444; color: #fff; }
    .btn-reject:hover { background: #dc2626; }
    .btn-edit-perm { background: transparent; border-color: var(--adm-border); color: #38bdf8; }
    .btn-edit-perm:hover { background: rgba(56, 189, 248, 0.1); }
    .btn-suspend { background: transparent; border-color: #f59e0b; color: #fbbf24; }
    .btn-view { background: transparent; border-color: var(--adm-border); color: var(--adm-text); }
    .btn-remove { background: transparent; border-color: #ef4444; color: #fca5a5; }

    /* Modals */
    .modal-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.7);
      backdrop-filter: blur(4px);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 9999;
      padding: 20px;
    }
    .modal-box {
      background: #0b1f44;
      border: 1px solid var(--adm-border);
      border-radius: 16px;
      width: 100%;
      max-width: 480px;
      padding: 28px;
      box-shadow: 0 20px 45px rgba(0,0,0,0.5);
    }
    .modal-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
    .modal-head h3 { font-size: 1.25rem; font-weight: 800; color: var(--adm-text); }
    .modal-close-btn { background: transparent; border: none; font-size: 20px; color: var(--adm-text-muted); cursor: pointer; }
    .modal-body { margin-bottom: 20px; font-size: 0.92rem; color: var(--adm-text-muted); }
    .modal-actions { display: flex; justify-content: flex-end; gap: 10px; }
  </style>
</head>
<body>

  <?php require __DIR__ . '/partials/header.php'; ?>

  <div class="writers-container">
    
    <div class="page-header-row">
      <div>
        <h1 class="page-title">
          ✍️ লেখক নিয়ন্ত্রণ ও অনুমোদন 
          <?php if ($pendingCount > 0): ?>
            <span class="pending-badge"><?= $pendingCount ?>টি অপেক্ষমান আবেদন</span>
          <?php endif; ?>
        </h1>
        <p style="color: var(--adm-text-muted); font-size: 0.92rem; margin-top: 4px;">সকল লেখকদের তালিকা, পারমিশন লেভেল এবং সম্পাদকীয় অনুমোদন পরিচালনা করুন</p>
      </div>
    </div>

    <?php if (!empty($success)): ?>
      <div class="alert-banner alert-success">✅ <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="alert-banner alert-error">⚠️ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Filter & Search Toolbar -->
    <div class="filter-card">
      <form action="/<?= $adminPath ?>/writers" method="get" class="filter-form">
        <select name="status" class="form-select">
          <option value="">সকল স্ট্যাটাস</option>
          <option value="pending" <?= ($statusFilter === 'pending') ? 'selected' : '' ?>>⏳ অপেক্ষমান (Pending)</option>
          <option value="active" <?= ($statusFilter === 'active') ? 'selected' : '' ?>>✅ সক্রিয় (Active)</option>
          <option value="suspended" <?= ($statusFilter === 'suspended') ? 'selected' : '' ?>>🚫 স্থগিত (Suspended)</option>
        </select>

        <select name="permission" class="form-select">
          <option value="">সকল পারমিশন</option>
          <option value="direct_publish" <?= ($permFilter === 'direct_publish') ? 'selected' : '' ?>>সরাসরি প্রকাশ (Direct Publish)</option>
          <option value="editor_review" <?= ($permFilter === 'editor_review') ? 'selected' : '' ?>>সম্পাদকীয় পর্যালোচনা (Editor Review)</option>
        </select>

        <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="নাম, ইমেইল বা ফোন দিয়ে খুঁজুন..." class="form-input" style="min-width: 220px;">
        <button type="submit" class="btn-filter">ফিল্টার করুন</button>
      </form>

      <div>
        <span style="font-size: 0.88rem; color: var(--adm-text-muted);">সর্বমোট লেখক: <strong><?= count($writers) ?></strong> জন</span>
      </div>
    </div>

    <!-- Writers List Table -->
    <div class="table-card">
      <div class="table-responsive">
        <table class="writers-table">
          <thead>
            <tr>
              <th>লেখক প্রোফাইল</th>
              <th>স্ট্যাটাস</th>
              <th>পারমিশন লেভেল</th>
              <th>মোট সংবাদ</th>
              <th>মোট ভিউ</th>
              <th>যোগদানের তারিখ</th>
              <th>অ্যাকশন</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($writers)): ?>
              <tr>
                <td colspan="7" style="text-align: center; padding: 40px; color: var(--adm-text-muted);">
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
                      <span class="status-pill status-active">সক্রিয়</span>
                    <?php elseif ($w['status'] === 'pending'): ?>
                      <span class="status-pill status-pending">অপেক্ষমান</span>
                    <?php elseif ($w['status'] === 'suspended'): ?>
                      <span class="status-pill status-suspended">স্থগিত</span>
                    <?php else: ?>
                      <span class="status-pill status-removed"><?= htmlspecialchars($w['status']) ?></span>
                    <?php endif; ?>
                  </td>

                  <!-- Permission Level -->
                  <td>
                    <?php if ($w['permission_level'] === 'direct_publish'): ?>
                      <span class="perm-badge perm-direct">⚡ সরাসরি প্রকাশ</span>
                    <?php elseif ($w['permission_level'] === 'editor_review'): ?>
                      <span class="perm-badge perm-review">👁️ সম্পাদকীয় রিভিউ</span>
                    <?php else: ?>
                      <span style="color: var(--adm-text-muted); font-size: 0.8rem;">নির্ধারিত নয়</span>
                    <?php endif; ?>
                  </td>

                  <!-- Stats -->
                  <td><strong><?= (int) ($w['total_posts'] ?? 0) ?></strong>টি</td>
                  <td><strong><?= number_format((int) ($w['total_views'] ?? 0)) ?></strong></td>
                  <td><?= date('d M, Y', strtotime($w['created_at'])) ?></td>

                  <!-- Actions -->
                  <td>
                    <div class="action-group">
                      <?php if ($w['status'] === 'pending'): ?>
                        <!-- Quick Approve Trigger -->
                        <button type="button" class="btn-tbl btn-approve" onclick="openApproveModal(<?= $w['id'] ?>, '<?= htmlspecialchars(addslashes($w['name'])) ?>')">অনুমোদন ✓</button>
                        <button type="button" class="btn-tbl btn-reject" onclick="openRejectModal(<?= $w['id'] ?>, '<?= htmlspecialchars(addslashes($w['name'])) ?>')">বাতিল ✕</button>
                      <?php else: ?>
                        <!-- Upgrade/Downgrade Permission -->
                        <button type="button" class="btn-tbl btn-edit-perm" onclick="openPermModal(<?= $w['id'] ?>, '<?= htmlspecialchars(addslashes($w['name'])) ?>', '<?= $w['permission_level'] ?? 'editor_review' ?>')">পারমিশন</button>
                        
                        <?php if ($w['status'] === 'active'): ?>
                          <button type="button" class="btn-tbl btn-suspend" onclick="openSuspendModal(<?= $w['id'] ?>, '<?= htmlspecialchars(addslashes($w['name'])) ?>')">স্থগিত</button>
                        <?php elseif ($w['status'] === 'suspended'): ?>
                          <form action="/<?= $adminPath ?>/writers/toggle-status" method="post" style="display:inline;" onsubmit="return confirm('লেখককে পুনরায় সক্রিয় করতে চান?');">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                            <input type="hidden" name="id" value="<?= $w['id'] ?>">
                            <input type="hidden" name="status_action" value="activate">
                            <button type="submit" class="btn-tbl btn-approve">সক্রিয় করুন</button>
                          </form>
                        <?php endif; ?>
                      <?php endif; ?>

                      <!-- View Details -->
                      <a href="/<?= $adminPath ?>/writers/show?id=<?= $w['id'] ?>" class="btn-tbl btn-view" title="বিস্তারিত প্রোফাইল ও সংবাদ তালিকা">প্রোফাইল ➔</a>

                      <!-- Remove Writer -->
                      <button type="button" class="btn-tbl btn-remove" onclick="openRemoveModal(<?= $w['id'] ?>, '<?= htmlspecialchars(addslashes($w['name'])) ?>')">মুছুন</button>
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

  <!-- 1. Approve Modal -->
  <div class="modal-overlay" id="approveModal">
    <div class="modal-box">
      <div class="modal-head">
        <h3>লেখক আবেদন অনুমোদন</h3>
        <button class="modal-close-btn" onclick="closeModal('approveModal')">✕</button>
      </div>
      <form action="/<?= $adminPath ?>/writers/approve" method="post">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <input type="hidden" name="id" id="approveWriterId">
        <div class="modal-body">
          <p style="margin-bottom: 14px;">আপনি <strong id="approveWriterName" style="color:#fff;"></strong>-এর আবেদন অনুমোদন করতে যাচ্ছেন। অনুগ্রহ করে উপযুক্ত পারমিশন লেভেল নির্বাচন করুন:</p>
          
          <div style="margin-bottom: 12px;">
            <label style="display: flex; align-items: flex-start; gap: 8px; margin-bottom: 8px; cursor: pointer;">
              <input type="radio" name="permission_level" value="direct_publish" style="margin-top: 3px;">
              <div>
                <strong style="color: #38bdf8;">সরাসরি প্রকাশ (Direct Publish)</strong>
                <p style="font-size: 0.8rem; color: var(--adm-text-muted);">লেখকের প্রস্তুতকৃত সংবাদ এডিটরের রিভিউ ছাড়া সাথে সাথে পোর্টাল লাইভে প্রকাশিত হবে।</p>
              </div>
            </label>
            <label style="display: flex; align-items: flex-start; gap: 8px; cursor: pointer;">
              <input type="radio" name="permission_level" value="editor_review" checked style="margin-top: 3px;">
              <div>
                <strong style="color: #c084fc;">সম্পাদকীয় পর্যালোচনা (Editor Review)</strong>
                <p style="font-size: 0.8rem; color: var(--adm-text-muted);">লেখকের সংবাদ প্রথমে এডিটরের রিভিউ কিউতে যাবে। এডিটর অনুমোদন করলে লাইভ হবে।</p>
              </div>
            </label>
          </div>
        </div>
        <div class="modal-actions">
          <button type="button" class="btn-tbl btn-view" onclick="closeModal('approveModal')">বাতিল</button>
          <button type="submit" class="btn-tbl btn-approve" style="padding: 10px 18px;">অনুমোদন করুন ➔</button>
        </div>
      </form>
    </div>
  </div>

  <!-- 2. Reject Modal -->
  <div class="modal-overlay" id="rejectModal">
    <div class="modal-box">
      <div class="modal-head">
        <h3>আবেদন প্রত্যাখ্যান</h3>
        <button class="modal-close-btn" onclick="closeModal('rejectModal')">✕</button>
      </div>
      <form action="/<?= $adminPath ?>/writers/reject" method="post">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <input type="hidden" name="id" id="rejectWriterId">
        <div class="modal-body">
          <p style="margin-bottom: 12px;"><strong id="rejectWriterName" style="color:#fff;"></strong>-এর আবেদন বাতিল করতে কারণ উল্লেখ করতে পারেন (ঐচ্ছিক):</p>
          <textarea name="rejected_reason" class="form-input" style="width: 100%; min-height: 80px;" placeholder="বাতিলের কারণ লিখুন..."></textarea>
        </div>
        <div class="modal-actions">
          <button type="button" class="btn-tbl btn-view" onclick="closeModal('rejectModal')">ফিরে যান</button>
          <button type="submit" class="btn-tbl btn-reject" style="padding: 10px 18px;">প্রত্যাখ্যান করুন</button>
        </div>
      </form>
    </div>
  </div>

  <!-- 3. Permission Modal -->
  <div class="modal-overlay" id="permModal">
    <div class="modal-box">
      <div class="modal-head">
        <h3>পারমিশন লেভেল পরিবর্তন</h3>
        <button class="modal-close-btn" onclick="closeModal('permModal')">✕</button>
      </div>
      <form action="/<?= $adminPath ?>/writers/update-permission" method="post">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <input type="hidden" name="id" id="permWriterId">
        <div class="modal-body">
          <p style="margin-bottom: 14px;"><strong id="permWriterName" style="color:#fff;"></strong>-এর নতুন পারমিশন নির্বাচন করুন:</p>
          <div style="margin-bottom: 12px;">
            <label style="display: flex; align-items: flex-start; gap: 8px; margin-bottom: 10px; cursor: pointer;">
              <input type="radio" name="permission_level" id="perm_direct" value="direct_publish" style="margin-top: 3px;">
              <div>
                <strong style="color: #38bdf8;">সরাসরি প্রকাশ (Direct Publish)</strong>
                <p style="font-size: 0.8rem; color: var(--adm-text-muted);">লেখকের সকল সংবাদ সরাসরি লাইভে যাবে।</p>
              </div>
            </label>
            <label style="display: flex; align-items: flex-start; gap: 8px; cursor: pointer;">
              <input type="radio" name="permission_level" id="perm_review" value="editor_review" style="margin-top: 3px;">
              <div>
                <strong style="color: #c084fc;">সম্পাদকীয় পর্যালোচনা (Editor Review)</strong>
                <p style="font-size: 0.8rem; color: var(--adm-text-muted);">সংবাদ এডিটর কিউতে রিভিউয়ের জন্য জমা হবে।</p>
              </div>
            </label>
          </div>
        </div>
        <div class="modal-actions">
          <button type="button" class="btn-tbl btn-view" onclick="closeModal('permModal')">বাতিল</button>
          <button type="submit" class="btn-tbl btn-approve" style="padding: 10px 18px;">সংরক্ষণ করুন</button>
        </div>
      </form>
    </div>
  </div>

  <!-- 4. Suspend Modal -->
  <div class="modal-overlay" id="suspendModal">
    <div class="modal-box">
      <div class="modal-head">
        <h3>লেখক স্থগিতকরণ (Suspend)</h3>
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
          <button type="button" class="btn-tbl btn-view" onclick="closeModal('suspendModal')">বাতিল</button>
          <button type="submit" class="btn-tbl btn-reject" style="padding: 10px 18px;">স্থগিত করুন</button>
        </div>
      </form>
    </div>
  </div>

  <!-- 5. Remove Writer Modal -->
  <div class="modal-overlay" id="removeModal">
    <div class="modal-box">
      <div class="modal-head">
        <h3 style="color: #ef4444;">লেখক অপসারন (Remove)</h3>
        <button class="modal-close-btn" onclick="closeModal('removeModal')">✕</button>
      </div>
      <form action="/<?= $adminPath ?>/writers/remove" method="post">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
        <input type="hidden" name="id" id="removeWriterId">
        <div class="modal-body">
          <p style="margin-bottom: 14px;">আপনি <strong id="removeWriterName" style="color:#fff;"></strong>-কে স্থায়ীভাবে অপসারণ করতে যাচ্ছেন। তার পূর্ববর্তী সংবাদগুলোর ক্ষেত্রে কী সিদ্ধান্ত নেবেন?</p>
          
          <div style="margin-bottom: 14px;">
            <label style="display: flex; align-items: flex-start; gap: 8px; margin-bottom: 8px; cursor: pointer;">
              <input type="radio" name="posts_action" value="keep" checked style="margin-top: 3px;">
              <div>
                <strong style="color: #fff;">প্রকাশিত রাখুন (Former Writer হিসেবে)</strong>
                <p style="font-size: 0.8rem; color: var(--adm-text-muted);">সংবাদগুলো লাইভ থাকবে এবং বাইলাইনে 'সাবেক লেখক' লেবেল যুক্ত হবে।</p>
              </div>
            </label>
            <label style="display: flex; align-items: flex-start; gap: 8px; cursor: pointer;">
              <input type="radio" name="posts_action" value="unpublish" style="margin-top: 3px;">
              <div>
                <strong style="color: #fca5a5;">সকল সংবাদ আনপাবলিশ (Draft) করুন</strong>
                <p style="font-size: 0.8rem; color: var(--adm-text-muted);">লেখকের সকল প্রকাশিত সংবাদ খসড়া (Draft) অবস্থায় ফেরত যাবে।</p>
              </div>
            </label>
          </div>
        </div>
        <div class="modal-actions">
          <button type="button" class="btn-tbl btn-view" onclick="closeModal('removeModal')">বাতিল</button>
          <button type="submit" class="btn-tbl btn-reject" style="padding: 10px 18px;">স্থায়ী অপসারন</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function closeModal(id) {
      document.getElementById(id).style.display = 'none';
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
  </script>

</body>
</html>

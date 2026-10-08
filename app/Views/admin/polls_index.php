<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>অনলাইন জরিপ ও ফলাফল ব্যবস্থাপনা | Newslens Desk</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    .admin-container { max-width: 1100px; margin: 30px auto; padding: 0 20px; }
    .page-title-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .create-poll-card { background: var(--adm-card-bg); border-radius: 8px; border: 1px solid var(--adm-border); padding: 24px; margin-bottom: 30px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .poll-card-box { background: var(--adm-card-bg); border-radius: 8px; border: 1px solid var(--adm-border); padding: 24px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .poll-q { font-size: 1.25rem; font-weight: 700; margin-bottom: 16px; color: var(--adm-text); }
    .stat-bar-container { margin-bottom: 14px; }
    .stat-label-flex { display: flex; justify-content: space-between; margin-bottom: 4px; font-size: 0.95rem; color: var(--adm-text); }
    .stat-progress { height: 12px; background: var(--adm-nav-bg); border-radius: 6px; overflow: hidden; border: 1px solid var(--adm-border); }
    .stat-progress-fill { height: 100%; background: var(--adm-primary); border-radius: 6px; transition: width 0.3s ease; }
    .poll-meta { font-size: 0.88rem; color: var(--adm-text-muted); margin-top: 16px; border-top: 1px solid var(--adm-border); padding-top: 12px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
    .poll-actions { display: flex; gap: 8px; align-items: center; }
    .btn-action-sm { border: none; padding: 6px 12px; border-radius: 4px; font-size: 0.82rem; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 4px; }
    .btn-active-poll { background: #0284c7; color: #fff; }
    .btn-active-poll:hover { background: #0369a1; }
    .btn-reset-poll { background: #d97706; color: #fff; }
    .btn-reset-poll:hover { background: #b45309; }
    .btn-delete-poll { background: #ef4444; color: #fff; }
    .btn-delete-poll:hover { background: #dc2626; }
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; font-weight: 600; margin-bottom: 6px; color: var(--adm-text); font-size: 0.92rem; }
    .form-control { width: 100%; padding: 10px 14px; background: var(--adm-nav-bg); border: 1px solid var(--adm-border); border-radius: 6px; font-size: 0.95rem; color: var(--adm-text); }
    .form-control:focus { border-color: var(--adm-primary); outline: none; }
    .options-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; margin-bottom: 16px; }
    .btn-submit { background: var(--adm-primary); color: #fff; padding: 10px 22px; border-radius: 6px; border: none; font-weight: 700; font-size: 0.95rem; cursor: pointer; }
    .btn-submit:hover { background: var(--adm-primary-hover); }
    .alert-banner { background: #064e3b; border: 1px solid #059669; color: #a7f3d0; padding: 12px 18px; border-radius: 6px; margin-bottom: 20px; }
    .badge-active { background: rgba(34, 197, 94, 0.2); color: #22c55e; border: 1px solid rgba(34, 197, 94, 0.3); padding: 3px 8px; border-radius: 4px; font-weight: 700; font-size: 0.8rem; }
    .badge-inactive { background: rgba(148, 163, 184, 0.2); color: #94a3b8; border: 1px solid rgba(148, 163, 184, 0.3); padding: 3px 8px; border-radius: 4px; font-weight: 600; font-size: 0.8rem; }
  </style>
</head>
<body>

<?php
$activeTab = 'polls';
require __DIR__ . '/partials/header.php';
?>

<main class="admin-container">
  <?php if (isset($_GET['msg'])): ?>
    <div class="alert-banner">
      <?php 
        if ($_GET['msg'] === 'created') echo '✅ নতুন অনলাইন জরিপ সফলভাবে প্রকাশ করা হয়েছে!';
        if ($_GET['msg'] === 'activated') echo '✅ নির্বাচিত জরিপটি মূল পোর্টালের হোমপেজে সক্রিয় করা হয়েছে!';
        if ($_GET['msg'] === 'reset') echo '✅ এই জরিপের সকল ভোটের সংখ্যা সফলভাবে রিসেট (০) করা হয়েছে!';
        if ($_GET['msg'] === 'deleted') echo '✅ জরিপটি সফলভাবে ডাটাবেজ থেকে মুছে ফেলা হয়েছে!';
      ?>
    </div>
  <?php endif; ?>

  <div class="page-title-row">
    <div>
      <h2>📈 অনলাইন জরিপ ও লাইভ ফলাফল ব্যবস্থাপনা</h2>
      <p style="color:var(--adm-text-muted);font-size:0.9rem;">পাঠকদের জনমত যাচাইয়ের জন্য নতুন জরিপ তৈরি করুন এবং ফলাফল পরিচালনা করুন</p>
    </div>
  </div>

  <!-- Create Poll Box (FEATURE REQUEST: emn jorip creat er freature dw aro) -->
  <div class="create-poll-card">
    <h3 style="margin-bottom:16px;color:var(--adm-text);display:flex;align-items:center;gap:8px;">
      ➕ নতুন জরিপ তৈরি করুন (Create Poll)
    </h3>
    <form action="<?= $appUrl ?>/<?= $adminPath ?>/polls" method="POST">
      <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">

      <div class="form-group">
        <label for="poll_question">জরিপের প্রশ্ন (Question) *</label>
        <textarea name="question" id="poll_question" required placeholder="উদাহরণ: আপনি কি মনে করেন দেশের রপ্তানি খাতে তথ্যপ্রযুক্তি দ্রুত শীর্ষস্থান নেবে?" class="form-control" rows="2" style="resize:vertical;"></textarea>
      </div>

      <label style="display:block;font-weight:600;margin-bottom:8px;color:var(--adm-text);font-size:0.92rem;">ভোটের বিকল্পসমূহ (Options) *</label>
      <div class="options-grid">
        <div>
          <input type="text" name="options[]" value="হ্যাঁ" required placeholder="বিকল্প ১ (যেমন: হ্যাঁ)" class="form-control">
        </div>
        <div>
          <input type="text" name="options[]" value="না" required placeholder="বিকল্প ২ (যেমন: না)" class="form-control">
        </div>
        <div>
          <input type="text" name="options[]" value="মতামত নেই" placeholder="বিকল্প ৩ (যেমন: মতামত নেই)" class="form-control">
        </div>
        <div>
          <input type="text" name="options[]" placeholder="বিকল্প ৪ (ঐচ্ছিক)" class="form-control">
        </div>
      </div>

      <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;margin-top:14px;">
        <label style="display:inline-flex;align-items:center;gap:8px;cursor:pointer;color:var(--adm-text);font-weight:600;font-size:0.92rem;">
          <input type="checkbox" name="is_active" value="1" checked>
          <span>হোমপেজে সক্রিয় জরিপ হিসেবে চালু রাখুন (Set as Active)</span>
        </label>
        <button type="submit" class="btn-submit">🚀 নতুন জরিপ প্রকাশ করুন</button>
      </div>
    </form>
  </div>

  <!-- Existing Polls List -->
  <h3 style="margin-bottom:16px;color:var(--adm-text);">📊 তৈরি করা জরিপের তালিকা ও লাইভ পরিসংখ্যান</h3>

  <?php if (!empty($polls)): ?>
    <?php foreach ($polls as $poll): ?>
      <div class="poll-card-box">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;margin-bottom:14px;">
          <div class="poll-q"><?= htmlspecialchars($poll['question']) ?></div>
          <div>
            <?php if (!empty($poll['is_active'])): ?>
              <span class="badge-active">🟢 সক্রিয় (Active)</span>
            <?php else: ?>
              <span class="badge-inactive">⚪ নিষ্ক্রিয় (Inactive)</span>
            <?php endif; ?>
          </div>
        </div>
        
        <div class="poll-stats-list">
          <?php foreach ($poll['options'] as $opt): ?>
            <div class="stat-bar-container">
              <div class="stat-label-flex">
                <span><strong><?= htmlspecialchars($opt['option_text']) ?></strong></span>
                <span><?= $opt['percentage'] ?>% (<?= $opt['votes'] ?> ভোট)</span>
              </div>
              <div class="stat-progress">
                <div class="stat-progress-fill" style="width: <?= $opt['percentage'] ?>%;"></div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>

        <div class="poll-meta">
          <div>
            <span>মোট প্রদত্ত ভোট: <strong><?= $poll['total_votes'] ?></strong> টি</span>
            <?php if ($poll['total_votes'] == 0): ?>
              <span style="color:var(--adm-text-muted);font-style:italic;">(এখনও কোনো ভোট পড়েনি)</span>
            <?php endif; ?>
            | <span>শুরুর তারিখ: <?= htmlspecialchars($poll['created_at']) ?></span>
          </div>

          <div class="poll-actions">
            <?php if (empty($poll['is_active'])): ?>
              <form action="<?= $appUrl ?>/<?= $adminPath ?>/polls/active" method="POST" style="display:inline;">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                <input type="hidden" name="id" value="<?= $poll['id'] ?>">
                <button type="submit" class="btn-action-sm btn-active-poll" title="হোমপেজে সক্রিয় করুন">🟢 সক্রিয় করুন</button>
              </form>
            <?php endif; ?>

            <form action="<?= $appUrl ?>/<?= $adminPath ?>/polls/reset" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই জরিপের সকল ভোটের সংখ্যা শূন্য (০) করতে চান?');" style="display:inline;">
              <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
              <input type="hidden" name="id" value="<?= $poll['id'] ?>">
              <button type="submit" class="btn-action-sm btn-reset-poll" title="ভোট সংখ্যা রিসেট করুন">🔄 ভোট রিসেট</button>
            </form>

            <form action="<?= $appUrl ?>/<?= $adminPath ?>/polls/delete" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই জরিপটি মুছে ফেলতে চান?');" style="display:inline;">
              <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
              <input type="hidden" name="id" value="<?= $poll['id'] ?>">
              <button type="submit" class="btn-action-sm btn-delete-poll" title="জরিপটি মুছুন">🗑️ মুছুন</button>
            </form>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="poll-card-box" style="text-align:center;padding:40px;color:var(--adm-text-muted);">
      <p>আপাতত কোনো জরিপ তৈরি করা নেই। ওপরের ফরম ব্যবহার করে আপনার প্রথম জরিপ তৈরি করুন।</p>
    </div>
  <?php endif; ?>
</main>

</body>
</html>

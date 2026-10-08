<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>নিউজলেটার গ্রাহক তালিকা | Newslens Desk</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    .admin-container { max-width: 1100px; margin: 30px auto; padding: 0 20px; }
    .page-title-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .table-card { background: var(--adm-card-bg); border-radius: 8px; border: 1px solid var(--adm-border); padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 12px 14px; text-align: left; border-bottom: 1px solid var(--adm-border); font-size: 0.95rem; color: var(--adm-text); }
    th { background: var(--adm-nav-bg); font-weight: 600; color: var(--adm-text-muted); }
    .btn-del { background: #ef4444; color: #fff; border: none; padding: 5px 12px; border-radius: 4px; cursor: pointer; font-size: 0.85rem; font-weight: 600; }
    .btn-del:hover { background: #dc2626; }
    .alert-banner { background: #064e3b; border: 1px solid #059669; color: #a7f3d0; padding: 12px 18px; border-radius: 6px; margin-bottom: 20px; }
  </style>
</head>
<body>

<?php
$activeTab = 'subscribers';
require __DIR__ . '/partials/header.php';
?>

<main class="admin-container">
  <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
    <div class="alert-banner">
      <span data-bn="✅ গ্রাহকের ইমেইল সফলভাবে তালিকা থেকে মুছে ফেলা হয়েছে!" data-en="✅ Subscriber email successfully deleted from list!">✅ গ্রাহকের ইমেইল সফলভাবে তালিকা থেকে মুছে ফেলা হয়েছে!</span>
    </div>
  <?php endif; ?>

  <div class="page-title-row">
    <div>
      <h2 data-bn="✉️ নিউজলেটার গ্রাহক তালিকা" data-en="✉️ Newsletter Subscribers List">✉️ নিউজলেটার গ্রাহক তালিকা</h2>
      <p style="color:var(--adm-text-muted);font-size:0.9rem;" data-bn="ওয়েবসাইট থেকে পাঠকদের সাবস্ক্রিপশন তালিকা" data-en="List of reader subscriptions from website">ওয়েবসাইট থেকে পাঠকদের সাবস্ক্রিপশন তালিকা</p>
    </div>
    <div>
      <span style="background:var(--adm-nav-bg);padding:6px 14px;border-radius:20px;border:1px solid var(--adm-border);font-weight:700;color:var(--adm-accent);">
        <span data-bn="মোট সাবস্ক্রাইবার:" data-en="Total Subscribers:">মোট সাবস্ক্রাইবার:</span> <?= count($subscribers) ?> <span data-bn="জন" data-en="Subscribers">জন</span>
      </span>
    </div>
  </div>

  <div class="table-card">
    <?php if (!empty($subscribers)): ?>
      <table>
        <thead>
          <tr>
            <th data-bn="#ID" data-en="#ID">#ID</th>
            <th data-bn="ইমেইল ঠিকানা" data-en="Email Address">ইমেইল ঠিকানা</th>
            <th data-bn="যুক্ত হওয়ার সময়" data-en="Subscribed At">যুক্ত হওয়ার সময়</th>
            <th data-bn="স্ট্যাটাস" data-en="Status">স্ট্যাটাস</th>
            <th data-bn="অ্যাকশন" data-en="Action">অ্যাকশন</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($subscribers as $sub): ?>
            <tr>
              <td><?= $sub['id'] ?></td>
              <td><strong><?= htmlspecialchars($sub['email']) ?></strong></td>
              <td><?= htmlspecialchars($sub['created_at']) ?></td>
              <td><span style="color:#22c55e;font-weight:600;" data-bn="সক্রিয় (Subscribed)" data-en="Active (Subscribed)">সক্রিয় (Subscribed)</span></td>
              <td>
                <form action="<?= $appUrl ?>/<?= $adminPath ?>/subscribers/delete" method="POST" onsubmit="return confirm('আপনি কি নিশ্চিত যে এই গ্রাহকটি মুছে ফেলতে চান?');" style="display:inline;">
                  <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken) ?>">
                  <input type="hidden" name="id" value="<?= $sub['id'] ?>">
                  <button type="submit" class="btn-del" title="মুছুন" data-title-bn="মুছুন" data-title-en="Delete" data-bn="🗑️ মুছুন" data-en="🗑️ Delete">🗑️ মুছুন</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p style="text-align:center;padding:30px;color:var(--adm-text-muted);" data-bn="এখনও কোনো পাঠক নিউজলেটার সাবস্ক্রাইব করেননি।" data-en="No readers have subscribed to the newsletter yet.">এখনও কোনো পাঠক নিউজলেটার সাবস্ক্রাইব করেননি।</p>
    <?php endif; ?>
  </div>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>

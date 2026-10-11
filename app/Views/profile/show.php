<?php
$adminPath = $adminPath ?? 'lens-desk';
$appUrl = rtrim($appUrl ?? '', '/');
$user = $user ?? [];
$stats = $stats ?? [];
$dailyViews = $dailyViews ?? [];
$categoryStats = $categoryStats ?? [];
$bookmarkCount = $bookmarkCount ?? 0;
$success = $success ?? null;
$error = $error ?? null;
$activeTab = 'profile';

$socialLinks = [];
if (!empty($user['social_links'])) {
    $socialLinks = is_array($user['social_links']) ? $user['social_links'] : (json_decode($user['social_links'], true) ?: []);
}

$avatarUrl = !empty($user['avatar']) ? ($appUrl . '/' . htmlspecialchars($user['avatar'])) : ($appUrl . '/assets/img/default-avatar.png');
?>
<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle ?? 'প্রোফাইল ও অ্যানালিটিক্স') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Hind Siliguri', sans-serif; background: var(--adm-bg); color: var(--adm-text); }
    .profile-wrap { max-width: 1200px; margin: 24px auto 60px; padding: 0 20px; }

    .alert { padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-weight: 600; font-size: 0.92rem; }
    .alert-success { background: rgba(14, 122, 58, 0.2); border: 1px solid #10b981; color: #86efac; }
    .alert-error { background: rgba(239, 68, 68, 0.2); border: 1px solid #ef4444; color: #fca5a5; }

    /* Profile Header Card */
    .profile-card {
      background: var(--adm-card-bg);
      border: 1px solid var(--adm-border);
      border-radius: 16px;
      padding: 30px;
      display: flex;
      gap: 28px;
      align-items: center;
      flex-wrap: wrap;
      margin-bottom: 28px;
      box-shadow: 0 8px 30px rgba(0,0,0,0.18);
    }
    .avatar-large {
      width: 110px;
      height: 110px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid var(--adm-primary);
      background: #071630;
      flex-shrink: 0;
    }
    .profile-info { flex: 1; min-width: 260px; }
    .profile-name { font-size: 1.8rem; font-weight: 800; color: #fff; margin-bottom: 4px; display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .role-tag {
      background: var(--adm-primary);
      color: #fff;
      font-size: 0.72rem;
      font-weight: 700;
      padding: 3px 10px;
      border-radius: 12px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .profile-bio { color: var(--adm-text-muted); font-size: 0.95rem; margin: 8px 0 14px; line-height: 1.5; max-width: 700px; }
    .profile-meta { display: flex; gap: 20px; flex-wrap: wrap; font-size: 0.88rem; color: var(--adm-text-muted); }
    .profile-meta span { display: inline-flex; align-items: center; gap: 6px; }

    .btn-edit {
      background: rgba(255,255,255,0.06);
      border: 1px solid var(--adm-border);
      color: var(--adm-text);
      padding: 10px 20px;
      border-radius: 8px;
      font-weight: 700;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: all 0.2s;
    }
    .btn-edit:hover { border-color: var(--adm-accent); color: var(--adm-accent); }

    /* Stats Grid */
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 16px;
      margin-bottom: 28px;
    }
    .stat-card {
      background: var(--adm-card-bg);
      border: 1px solid var(--adm-border);
      border-radius: 12px;
      padding: 20px;
      display: flex;
      align-items: center;
      gap: 16px;
    }
    .stat-icon {
      width: 48px;
      height: 48px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.4rem;
      background: rgba(56, 189, 248, 0.15);
      color: #38bdf8;
    }
    .stat-num { font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.8rem; font-weight: 800; color: #fff; }
    .stat-label { font-size: 0.88rem; color: var(--adm-text-muted); font-weight: 600; }

    /* Analytics Chart Card */
    .chart-card {
      background: var(--adm-card-bg);
      border: 1px solid var(--adm-border);
      border-radius: 16px;
      padding: 24px;
      margin-bottom: 28px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }
    .chart-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .chart-title { font-size: 1.2rem; font-weight: 800; display: flex; align-items: center; gap: 8px; }
    .chart-wrap { width: 100%; height: 260px; position: relative; }
    canvas#viewsChart { width: 100% !important; height: 100% !important; }

    /* Category Breakdown Table */
    .table-card {
      background: var(--adm-card-bg);
      border: 1px solid var(--adm-border);
      border-radius: 14px;
      overflow: hidden;
      margin-bottom: 28px;
    }
    .table-card-header { padding: 18px 24px; border-bottom: 1px solid var(--adm-border); font-size: 1.15rem; font-weight: 800; }
    table { width: 100%; border-collapse: collapse; text-align: left; font-size: 0.92rem; }
    th { background: rgba(0,0,0,0.25); padding: 14px 18px; color: var(--adm-text-muted); font-weight: 700; border-bottom: 1px solid var(--adm-border); }
    td { padding: 14px 18px; border-bottom: 1px solid rgba(255,255,255,0.05); }
    tr:hover { background: rgba(255,255,255,0.02); }
  </style>
</head>
<body>
  <?php require APP_PATH . '/Views/admin/partials/header.php'; ?>

  <div class="profile-wrap">
    <?php if (!empty($success)): ?>
      <div class="alert alert-success">✓ <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
      <div class="alert alert-error">✕ <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- User Header Card -->
    <div class="profile-card">
      <img src="<?= $avatarUrl ?>" alt="<?= htmlspecialchars($user['name'] ?? '') ?>" class="avatar-large" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($user['name'] ?? 'User') ?>&background=0E7A3A&color=fff'">
      
      <div class="profile-info">
        <div class="profile-name">
          <span><?= htmlspecialchars($user['name'] ?? '') ?></span>
          <span class="role-tag"><?= htmlspecialchars(strtoupper($user['role'] ?? 'USER')) ?></span>
          <?php if (($user['role'] ?? '') === 'writer'): ?>
            <span style="font-size: 0.78rem; font-weight: 600; color: #38bdf8; background: rgba(56, 189, 248, 0.1); padding: 3px 8px; border-radius: 6px;">
              <?= ($user['permission_level'] ?? '') === 'direct_publish' ? '⚡ Direct Publish' : '📝 Editor Review' ?>
            </span>
          <?php endif; ?>
        </div>

        <div class="profile-bio">
          <?= !empty($user['short_bio']) ? nl2br(htmlspecialchars($user['short_bio'])) : 'কোনো পরিচিতি তথ্য এখনো যোগ করা হয়নি।' ?>
        </div>

        <div class="profile-meta">
          <span>✉️ <?= htmlspecialchars($user['email'] ?? '') ?></span>
          <?php if (!empty($user['phone'])): ?>
            <span>📞 <?= htmlspecialchars($user['phone']) ?></span>
          <?php endif; ?>
          <span>📅 যুক্ত হয়েছেন: <?= date('d M, Y', strtotime($user['created_at'] ?? 'now')) ?></span>
          <?php if (!empty($user['id'])): ?>
            <a href="<?= $appUrl ?>/author/<?= (int)$user['id'] ?>" target="_blank" style="color: #38bdf8; text-decoration: none; font-weight: 700;">🌐 পাবলিক লেখক পেজ ↗</a>
          <?php endif; ?>
        </div>
      </div>

      <a href="<?= $appUrl ?>/profile/edit" class="btn-edit">✏️ প্রোফাইল সম্পাদনা</a>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
      <?php if (in_array($user['role'] ?? '', ['writer', 'editor', 'super_admin'], true)): ?>
        <div class="stat-card">
          <div class="stat-icon" style="color: #34d399; background: rgba(16, 185, 129, 0.15);">✅</div>
          <div>
            <div class="stat-num"><?= (int)($stats['published_posts'] ?? 0) ?></div>
            <div class="stat-label">প্রকাশিত সংবাদ</div>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon" style="color: #38bdf8; background: rgba(56, 189, 248, 0.15);">📝</div>
          <div>
            <div class="stat-num"><?= (int)($stats['total_posts'] ?? 0) ?></div>
            <div class="stat-label">সর্বমোট সংবাদ</div>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon" style="color: #c084fc; background: rgba(168, 85, 247, 0.15);">👁️</div>
          <div>
            <div class="stat-num"><?= number_format((int)($stats['total_views'] ?? 0)) ?></div>
            <div class="stat-label">মোট সংবাদ ভিউ</div>
          </div>
        </div>
      <?php else: ?>
        <div class="stat-card">
          <div class="stat-icon" style="color: #f59e0b; background: rgba(245, 158, 11, 0.15);">🔖</div>
          <div>
            <div class="stat-num"><?= $bookmarkCount ?></div>
            <div class="stat-label">সংরক্ষিত সংবাদ (Bookmarks)</div>
          </div>
        </div>
      <?php endif; ?>
    </div>

    <?php if (in_array($user['role'] ?? '', ['writer', 'editor', 'super_admin'], true)): ?>
      <!-- 30-Day Trend Chart -->
      <div class="chart-card">
        <div class="chart-header">
          <div class="chart-title">
            <span>📈</span>
            <span>গত ৩০ দিনের ভিউ পরিসংখ্যান (30-Day View Analytics)</span>
          </div>
          <span style="font-size: 0.85rem; color: var(--adm-text-muted);">রিয়েলটাইম ডুপ্লিকেট-প্রোটেক্টেড ট্র্যাকিং</span>
        </div>
        <div class="chart-wrap">
          <canvas id="viewsChart"></canvas>
        </div>
      </div>

      <!-- Category Breakdown -->
      <div class="table-card">
        <div class="table-card-header">ক্যাটাগরি ভিত্তিক সংবাদ ও ভিউ পরিসংখ্যান</div>
        <table>
          <thead>
            <tr>
              <th>ক্যাটাগরি</th>
              <th>সংবাদ সংখ্যা</th>
              <th>মোট ভিউ</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($categoryStats)): ?>
              <tr>
                <td colspan="3" style="text-align: center; color: var(--adm-text-muted); padding: 30px;">
                  কোনো ক্যাটাগরি ডেটা পাওয়া যায়নি।
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($categoryStats as $cs): ?>
                <tr>
                  <td style="font-weight: 700; color: #fff;">
                    <?= htmlspecialchars($cs['name_bn']) ?>
                  </td>
                  <td style="font-family: 'Plus Jakarta Sans', sans-serif;">
                    <?= (int)$cs['post_count'] ?> টি
                  </td>
                  <td style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; color: #34d399;">
                    <?= number_format((int)$cs['total_views']) ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>

  <script>
    // Lightweight canvas line chart rendering
    (function() {
      const rawData = <?= json_encode($dailyViews) ?>;
      const canvas = document.getElementById('viewsChart');
      if (!canvas) return;

      const ctx = canvas.getContext('2d');
      const dpr = window.devicePixelRatio || 1;
      const rect = canvas.getBoundingClientRect();
      canvas.width = rect.width * dpr;
      canvas.height = rect.height * dpr;
      ctx.scale(dpr, dpr);

      const w = rect.width;
      const h = rect.height;
      const padding = { top: 20, right: 30, bottom: 40, left: 50 };

      // Generate last 30 days labels
      const days = [];
      const counts = [];
      const dataMap = {};
      rawData.forEach(d => { dataMap[d.view_date] = parseInt(d.view_count) || 0; });

      for (let i = 29; i >= 0; i--) {
        const dateObj = new Date();
        dateObj.setDate(dateObj.getDate() - i);
        const ymd = dateObj.toISOString().slice(0, 10);
        days.push(dateObj.toLocaleDateString('en-GB', { day: 'numeric', month: 'short' }));
        counts.push(dataMap[ymd] || 0);
      }

      const maxVal = Math.max(...counts, 5);

      // Draw Grid Lines
      ctx.strokeStyle = 'rgba(255, 255, 255, 0.08)';
      ctx.lineWidth = 1;
      const gridSteps = 4;
      for (let i = 0; i <= gridSteps; i++) {
        const y = padding.top + (h - padding.top - padding.bottom) * (i / gridSteps);
        ctx.beginPath();
        ctx.moveTo(padding.left, y);
        ctx.lineTo(w - padding.right, y);
        ctx.stroke();

        const val = Math.round(maxVal - (maxVal * (i / gridSteps)));
        ctx.fillStyle = '#94a3b8';
        ctx.font = '11px sans-serif';
        ctx.textAlign = 'right';
        ctx.fillText(val, padding.left - 8, y + 4);
      }

      // Plot Line & Area
      const chartW = w - padding.left - padding.right;
      const chartH = h - padding.top - padding.bottom;
      const stepX = chartW / (counts.length - 1);

      const points = counts.map((val, idx) => {
        const x = padding.left + idx * stepX;
        const y = padding.top + chartH - (val / maxVal) * chartH;
        return { x, y };
      });

      // Gradient Fill
      const grad = ctx.createLinearGradient(0, padding.top, 0, h - padding.bottom);
      grad.addColorStop(0, 'rgba(16, 185, 129, 0.35)');
      grad.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

      ctx.beginPath();
      ctx.moveTo(points[0].x, points[0].y);
      for (let i = 1; i < points.length; i++) {
        ctx.lineTo(points[i].x, points[i].y);
      }
      ctx.lineTo(points[points.length - 1].x, h - padding.bottom);
      ctx.lineTo(points[0].x, h - padding.bottom);
      ctx.closePath();
      ctx.fillStyle = grad;
      ctx.fill();

      // Stroke line
      ctx.beginPath();
      ctx.moveTo(points[0].x, points[0].y);
      for (let i = 1; i < points.length; i++) {
        ctx.lineTo(points[i].x, points[i].y);
      }
      ctx.strokeStyle = '#10b981';
      ctx.lineWidth = 2.5;
      ctx.stroke();

      // Draw every 5th label
      ctx.fillStyle = '#94a3b8';
      ctx.textAlign = 'center';
      for (let i = 0; i < days.length; i += 5) {
        ctx.fillText(days[i], points[i].x, h - padding.bottom + 20);
      }
    })();
  </script>
</body>
</html>

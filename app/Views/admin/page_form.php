<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($pageTitle ?? 'পেজ ফর্ম | Newslens Desk') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    .admin-container { max-width: 1080px; margin: 28px auto 60px; padding: 0 20px; font-family: 'Hind Siliguri', sans-serif; }
    
    .form-header-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .form-header-row h2 { color: var(--adm-text, #f1f5f9); font-size: 1.6rem; font-weight: 700; display: flex; align-items: center; gap: 10px; }
    
    .btn-back {
      background: var(--adm-pill-bg, rgba(255,255,255,0.08));
      color: var(--adm-text, #f8fafc);
      border: 1px solid var(--adm-border, #173567);
      padding: 9px 16px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      font-size: 0.9rem;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.2s;
    }
    .btn-back:hover { border-color: var(--adm-accent, #38bdf8); color: var(--adm-accent, #38bdf8); }

    .form-card {
      background: var(--adm-card-bg, #0c1e3d);
      border: 1px solid var(--adm-border, #173567);
      border-radius: 14px;
      padding: 30px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.12);
    }

    .form-grid-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
      margin-bottom: 20px;
    }
    @media (max-width: 768px) {
      .form-grid-2 { grid-template-columns: 1fr; }
    }

    .form-group { margin-bottom: 22px; }
    .form-label {
      display: block;
      font-size: 0.95rem;
      font-weight: 700;
      color: var(--adm-text, #f8fafc);
      margin-bottom: 8px;
    }
    .form-hint {
      font-size: 0.82rem;
      color: var(--adm-text-muted, #94a3b8);
      margin-top: 5px;
    }
    .form-control {
      width: 100%;
      padding: 11px 14px;
      background: var(--adm-bg, #081329);
      border: 1px solid var(--adm-border, #173567);
      border-radius: 8px;
      color: var(--adm-text, #f8fafc);
      font-size: 0.95rem;
      font-family: inherit;
      outline: none;
      transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .form-control:focus {
      border-color: var(--adm-accent, #38bdf8);
      box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
    }

    /* Content Editor Toolbar */
    .editor-toolbar {
      display: flex;
      flex-wrap: wrap;
      gap: 6px;
      background: rgba(255, 255, 255, 0.04);
      padding: 8px 10px;
      border: 1px solid var(--adm-border, #173567);
      border-bottom: none;
      border-radius: 8px 8px 0 0;
    }
    .tool-btn {
      background: var(--adm-pill-bg, rgba(255,255,255,0.06));
      color: var(--adm-text, #f8fafc);
      border: 1px solid var(--adm-border, #173567);
      padding: 5px 10px;
      border-radius: 4px;
      font-size: 0.84rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.15s;
    }
    .tool-btn:hover {
      background: rgba(56, 189, 248, 0.15);
      border-color: var(--adm-accent, #38bdf8);
      color: var(--adm-accent, #38bdf8);
    }
    .content-textarea {
      border-radius: 0 0 8px 8px;
      min-height: 320px;
      font-family: 'Hind Siliguri', monospace, sans-serif;
      line-height: 1.6;
      resize: vertical;
    }

    .preview-box {
      margin-top: 15px;
      padding: 20px;
      border: 1px dashed var(--adm-border, #173567);
      border-radius: 8px;
      background: rgba(0,0,0,0.15);
      display: none;
    }

    .form-actions {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      gap: 14px;
      margin-top: 30px;
      padding-top: 20px;
      border-top: 1px solid var(--adm-border, #173567);
    }
    .btn-submit {
      background: var(--adm-primary, #0E7A3A);
      color: #fff;
      border: none;
      padding: 12px 26px;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 700;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 8px;
      transition: background 0.2s, transform 0.1s;
      box-shadow: 0 3px 10px rgba(14, 122, 58, 0.3);
    }
    .btn-submit:hover {
      background: var(--adm-primary-hover, #0a5c2b);
      transform: translateY(-1px);
    }

    .alert-danger {
      background: #7f1d1d;
      border: 1px solid #dc2626;
      color: #fecaca;
      padding: 12px 18px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-weight: 600;
    }
  </style>
</head>
<body>

<?php
$activeTab = 'pages';
require __DIR__ . '/partials/header.php';

$actionUrl = $isEdit ? "{$appUrl}/{$adminPath}/pages/update" : "{$appUrl}/{$adminPath}/pages/store";
$title = $page['title'] ?? '';
$titleEn = $page['title_en'] ?? '';
$badge = $page['badge'] ?? 'পরিচিতি';
$slug = $page['slug'] ?? '';
$content = $page['content'] ?? '';
$metaTitle = $page['meta_title'] ?? '';
$metaDesc = $page['meta_desc'] ?? '';
$status = $page['status'] ?? 'published';
?>

<main class="admin-container">

  <?php if (isset($_GET['err'])): ?>
    <div class="alert-danger">
      <span>⚠️ <?= htmlspecialchars($_GET['err']) ?></span>
    </div>
  <?php endif; ?>

  <div class="form-header-row">
    <div>
      <h2>
        <span><?= $isEdit ? '✏️' : '➕' ?></span>
        <span><?= $isEdit ? 'পেজ সম্পাদনা: ' . htmlspecialchars($title) : 'নতুন প্রাতিষ্ঠানিক পেজ যোগ করুন' ?></span>
      </h2>
      <p style="color: var(--adm-text-muted); font-size: 0.92rem; margin-top: 4px;">
        <?= $isEdit ? 'বিদ্যমান পেজের তথ্য বা কনটেন্ট হালনাগাদ করুন' : 'ওয়েবসাইটের ফুটার বা প্রাতিষ্ঠানিক সেকশনের জন্য নতুন পেজ তৈরি করুন' ?>
      </p>
    </div>
    <div>
      <a href="<?= $appUrl ?>/<?= $adminPath ?>/pages" class="btn-back">
        <span>←</span>
        <span>পেজ তালিকায় ফিরে যান</span>
      </a>
    </div>
  </div>

  <div class="form-card">
    <form method="POST" action="<?= $actionUrl ?>" id="pageForm">
      <input type="hidden" name="csrf_token" value="<?= $csrfToken ?>">
      <?php if ($isEdit): ?>
        <input type="hidden" name="id" value="<?= (int)$page['id'] ?>">
      <?php endif; ?>

      <!-- Row 1: Title Bn & Title En -->
      <div class="form-grid-2">
        <div class="form-group">
          <label class="form-label" for="title">
            পেজের শিরোনাম (বাংলা / মূল নাম) <span style="color:#ef4444;">*</span>
          </label>
          <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($title) ?>" required placeholder="উদা: আমাদের সম্পর্কে (About Us)">
          <div class="form-hint">শিরোনামটি মূল ওয়েব পেজের শীর্ষে বড় আকারে প্রদর্শিত হবে।</div>
        </div>

        <div class="form-group">
          <label class="form-label" for="title_en">
            ইংরেজি শিরোনাম (English Title)
          </label>
          <input type="text" id="title_en" name="title_en" class="form-control" value="<?= htmlspecialchars($titleEn) ?>" placeholder="e.g. About Us">
          <div class="form-hint">ইংরেজি ইন্টারফেসে ব্যবহারের জন্য।</div>
        </div>
      </div>

      <!-- Row 2: Slug, Badge, Status -->
      <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 20px;">
        <div class="form-group">
          <label class="form-label" for="slug">
            URL স্লাগ (Slug) <span style="color:#ef4444;">*</span>
          </label>
          <input type="text" id="slug" name="slug" class="form-control" value="<?= htmlspecialchars($slug) ?>" required placeholder="about, contact, terms">
          <div class="form-hint">লিঙ্ক হবে: <code>/page/<?= htmlspecialchars($slug ?: 'slug') ?></code></div>
        </div>

        <div class="form-group">
          <label class="form-label" for="badge">
            সেকশন ব্যাজ (Badge)
          </label>
          <input type="text" id="badge" name="badge" class="form-control" value="<?= htmlspecialchars($badge) ?>" placeholder="পরিচিতি, বার্তা পাঠান, শর্তাবলী">
          <div class="form-hint">পেজ হেডিংয়ের ওপর ছোট সবুজ ব্যাজ।</div>
        </div>

        <div class="form-group">
          <label class="form-label" for="status">
            প্রকাশনার স্ট্যাটাস (Status)
          </label>
          <select id="status" name="status" class="form-control">
            <option value="published" <?= $status === 'published' ? 'selected' : '' ?>>🌐 প্রকাশিত (Published)</option>
            <option value="draft" <?= $status === 'draft' ? 'selected' : '' ?>>📝 খসড়া (Draft)</option>
          </select>
          <div class="form-hint">ড্রাফট থাকলে সাধারণ পাঠকরা দেখতে পাবে না।</div>
        </div>
      </div>

      <!-- Content Area with Formatting Toolbar -->
      <div class="form-group">
        <label class="form-label" for="content">
          পেজের বিস্তারিত কনটেন্ট (Content HTML) <span style="color:#ef4444;">*</span>
        </label>
        
        <div class="editor-toolbar">
          <button type="button" class="tool-btn" onclick="wrapTag('p')">Paragraph &lt;p&gt;</button>
          <button type="button" class="tool-btn" onclick="wrapTag('h3')">Heading &lt;h3&gt;</button>
          <button type="button" class="tool-btn" onclick="wrapTag('strong')">Bold &lt;strong&gt;</button>
          <button type="button" class="tool-btn" onclick="wrapTag('em')">Italic &lt;em&gt;</button>
          <button type="button" class="tool-btn" onclick="insertList()">List &lt;ul&gt;&lt;li&gt;</button>
          <button type="button" class="tool-btn" onclick="insertBox()">Info Box &lt;div&gt;</button>
          <button type="button" class="tool-btn" onclick="insertLink()">Link &lt;a&gt;</button>
          <button type="button" class="tool-btn" onclick="togglePreview()" style="margin-left: auto; color: var(--adm-accent);">👁️ প্রিভিউ দেখুন</button>
        </div>

        <textarea id="content" name="content" class="form-control content-textarea" placeholder="এখানে পেজের বিস্তারিত টেক্সট ও বিবরণ লিখুন..."><?= htmlspecialchars($content) ?></textarea>
        <div class="form-hint">এইচটিএমএল ফরম্যাটিং বা সাধারণ প্যারাগ্রাফ উভয়ই সমর্থন করে।</div>

        <div id="previewBox" class="preview-box">
          <h4 style="color: var(--adm-accent); margin-bottom: 12px; font-size: 0.95rem;">লাইভ প্রিভিউ:</h4>
          <div id="previewContent" style="color: #cbd5e1; line-height: 1.8;"></div>
        </div>
      </div>

      <!-- SEO Meta Fields -->
      <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--adm-border, #173567); border-radius: 10px; padding: 20px; margin-top: 25px;">
        <h4 style="color: var(--adm-text, #f8fafc); font-size: 1.05rem; margin-bottom: 14px; display: flex; align-items: center; gap: 8px;">
          <span>🔍</span> <span>এসইও ও মেটা ডেটা (SEO Meta Details)</span>
        </h4>

        <div class="form-group">
          <label class="form-label" for="meta_title">মেটা টাইটেল (Meta Title)</label>
          <input type="text" id="meta_title" name="meta_title" class="form-control" value="<?= htmlspecialchars($metaTitle) ?>" placeholder="উদা: আমাদের সম্পর্কে | Newslensbd">
        </div>

        <div class="form-group" style="margin-bottom: 0;">
          <label class="form-label" for="meta_desc">মেটা বিবরণ (Meta Description)</label>
          <textarea id="meta_desc" name="meta_desc" class="form-control" rows="2" placeholder="সার্চ ইঞ্জিনের জন্য পেজের সারসংক্ষেপ..."><?= htmlspecialchars($metaDesc) ?></textarea>
        </div>
      </div>

      <!-- Actions -->
      <div class="form-actions">
        <a href="<?= $appUrl ?>/<?= $adminPath ?>/pages" class="btn-back">বাতিল করুন</a>
        <button type="submit" class="btn-submit">
          <span>💾</span>
          <span><?= $isEdit ? 'পরিবর্তনসমূহ সংরক্ষণ করুন' : 'পেজ প্রকাশ করুন' ?></span>
        </button>
      </div>

    </form>
  </div>

</main>

<?php require __DIR__ . '/partials/footer.php'; ?>

<script>
function wrapTag(tag) {
  const textarea = document.getElementById('content');
  const start = textarea.selectionStart;
  const end = textarea.selectionEnd;
  const selected = textarea.value.substring(start, end);
  const replacement = `<${tag}>${selected || 'টেক্সট'}</${tag}>`;
  textarea.setRangeText(replacement, start, end, 'select');
  textarea.focus();
}

function insertList() {
  const textarea = document.getElementById('content');
  const start = textarea.selectionStart;
  const listHtml = `\n<ul>\n  <li><strong>বিষয় ১:</strong> বিবরণ</li>\n  <li><strong>বিষয় ২:</strong> বিবরণ</li>\n</ul>\n`;
  textarea.setRangeText(listHtml, start, start, 'end');
  textarea.focus();
}

function insertBox() {
  const textarea = document.getElementById('content');
  const start = textarea.selectionStart;
  const boxHtml = `\n<div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; padding: 20px; margin: 20px 0;">\n  <p><strong>যোগাযোগের তথ্য:</strong> info@example.com</p>\n</div>\n`;
  textarea.setRangeText(boxHtml, start, start, 'end');
  textarea.focus();
}

function insertLink() {
  const url = prompt('লিঙ্কের URL লিখুন:', 'https://');
  if (!url) return;
  const textarea = document.getElementById('content');
  const start = textarea.selectionStart;
  const end = textarea.selectionEnd;
  const selected = textarea.value.substring(start, end) || 'এখানে ক্লিক করুন';
  const linkHtml = `<a href="${url}" target="_blank" rel="noopener">${selected}</a>`;
  textarea.setRangeText(linkHtml, start, end, 'select');
  textarea.focus();
}

function togglePreview() {
  const box = document.getElementById('previewBox');
  const preview = document.getElementById('previewContent');
  const content = document.getElementById('content').value;
  if (box.style.display === 'block') {
    box.style.display = 'none';
  } else {
    preview.innerHTML = content || '<em style="color:#94a3b8;">কোনো কনটেন্ট লেখা হয়নি</em>';
    box.style.display = 'block';
  }
}
</script>

</body>
</html>

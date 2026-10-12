<?php
namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Auth;
use App\Models\Page;
use App\Models\AuditLog;
use Exception;

class PageController {
    private string $adminPath;
    private string $appUrl;

    public function __construct() {
        $config = require CONFIG_PATH . '/config.php';
        $this->adminPath = $config['app']['admin_path'] ?? 'lens-desk';
        $this->appUrl = rtrim($config['app']['url'] ?? '', '/');

        if (!Auth::check()) {
            header("Location: {$this->appUrl}/{$this->adminPath}/login");
            exit;
        }

        $user = Auth::user();
        if (!in_array($user['role'], ['super_admin', 'admin', 'editor'], true)) {
            die('অনুমতি নেই: শুধুমাত্র সুপার অ্যাডমিন, অ্যাডমিন বা এডিটর প্রাতিষ্ঠানিক পেজ পরিচালনা করতে পারেন।');
        }
    }

    /**
     * List all static pages
     */
    public function index(): void {
        $user = Auth::user();
        $pages = Page::all();

        View::render('admin.pages_index', [
            'pageTitle' => 'প্রাতিষ্ঠানিক পেজ ব্যবস্থাপনা (Pages) | Newslens Desk',
            'user'      => $user,
            'pages'     => $pages,
            'adminPath' => $this->adminPath,
            'appUrl'    => $this->appUrl,
            'csrfToken' => Auth::generateCsrf()
        ], null);
    }

    /**
     * Show form to create new page
     */
    public function create(): void {
        $user = Auth::user();

        View::render('admin.page_form', [
            'pageTitle' => 'নতুন প্রাতিষ্ঠানিক পেজ তৈরি | Newslens Desk',
            'user'      => $user,
            'page'      => null,
            'isEdit'    => false,
            'adminPath' => $this->adminPath,
            'appUrl'    => $this->appUrl,
            'csrfToken' => Auth::generateCsrf()
        ], null);
    }

    /**
     * Store newly created page
     */
    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: {$this->appUrl}/{$this->adminPath}/pages");
            exit;
        }

        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die('CSRF ভ্যালিডেশন ব্যর্থ হয়েছে।');
        }

        $title    = trim($_POST['title'] ?? '');
        $titleEn  = trim($_POST['title_en'] ?? '');
        $badge    = trim($_POST['badge'] ?? '');
        $rawSlug  = trim($_POST['slug'] ?? '');
        $content  = trim($_POST['content'] ?? '');
        $metaTitle= trim($_POST['meta_title'] ?? '');
        $metaDesc = trim($_POST['meta_desc'] ?? '');
        $status   = in_array($_POST['status'] ?? '', ['published', 'draft'], true) ? $_POST['status'] : 'published';

        if ($title === '') {
            header("Location: {$this->appUrl}/{$this->adminPath}/pages/create?err=" . urlencode('পেজের শিরোনাম পূরণ করা আবশ্যক।'));
            exit;
        }

        // Generate / clean slug
        $slug = $this->slugify($rawSlug !== '' ? $rawSlug : ($titleEn !== '' ? $titleEn : $title));
        if ($slug === '') {
            $slug = 'page-' . time();
        }

        // Check slug uniqueness
        $existing = Page::findBySlug($slug);
        if ($existing) {
            header("Location: {$this->appUrl}/{$this->adminPath}/pages/create?err=" . urlencode('এই স্লাগ (Slug) দিয়ে ইতোমধ্যে একটি পেজ রয়েছে। অন্য একটি স্লাগ দিন।'));
            exit;
        }

        try {
            $pageId = Page::create([
                'title'      => $title,
                'title_en'   => $titleEn,
                'badge'      => $badge !== '' ? $badge : 'প্রতিষ্ঠান',
                'slug'       => $slug,
                'content'    => $content,
                'meta_title' => $metaTitle,
                'meta_desc'  => $metaDesc,
                'status'     => $status
            ]);

            try {
                AuditLog::log('create_page', 'page', $pageId, ['slug' => $slug, 'title' => $title]);
            } catch (Exception $e) {}

            header("Location: {$this->appUrl}/{$this->adminPath}/pages?msg=created");
            exit;
        } catch (Exception $e) {
            header("Location: {$this->appUrl}/{$this->adminPath}/pages/create?err=" . urlencode('সংরক্ষণ ব্যর্থ হয়েছে: ' . $e->getMessage()));
            exit;
        }
    }

    /**
     * Show edit form for existing page
     */
    public function edit(): void {
        $id = (int) ($_GET['id'] ?? 0);
        $page = Page::findById($id);

        if (!$page) {
            header("Location: {$this->appUrl}/{$this->adminPath}/pages?err=" . urlencode('পেজটি খুঁজে পাওয়া যায়নি।'));
            exit;
        }

        $user = Auth::user();

        View::render('admin.page_form', [
            'pageTitle' => 'পেজ সম্পাদনা: ' . htmlspecialchars($page['title']) . ' | Newslens Desk',
            'user'      => $user,
            'page'      => $page,
            'isEdit'    => true,
            'adminPath' => $this->adminPath,
            'appUrl'    => $this->appUrl,
            'csrfToken' => Auth::generateCsrf()
        ], null);
    }

    /**
     * Update page in database
     */
    public function update(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: {$this->appUrl}/{$this->adminPath}/pages");
            exit;
        }

        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die('CSRF ভ্যালিডেশন ব্যর্থ হয়েছে।');
        }

        $id       = (int) ($_POST['id'] ?? 0);
        $page     = Page::findById($id);

        if (!$page) {
            header("Location: {$this->appUrl}/{$this->adminPath}/pages?err=" . urlencode('পেজটি পাওয়া যায়নি।'));
            exit;
        }

        $title    = trim($_POST['title'] ?? '');
        $titleEn  = trim($_POST['title_en'] ?? '');
        $badge    = trim($_POST['badge'] ?? '');
        $rawSlug  = trim($_POST['slug'] ?? '');
        $content  = trim($_POST['content'] ?? '');
        $metaTitle= trim($_POST['meta_title'] ?? '');
        $metaDesc = trim($_POST['meta_desc'] ?? '');
        $status   = in_array($_POST['status'] ?? '', ['published', 'draft'], true) ? $_POST['status'] : 'published';

        if ($title === '') {
            header("Location: {$this->appUrl}/{$this->adminPath}/pages/edit?id={$id}&err=" . urlencode('পেজের শিরোনাম পূরণ করা আবশ্যক।'));
            exit;
        }

        $slug = $this->slugify($rawSlug !== '' ? $rawSlug : $page['slug']);
        if ($slug === '') {
            $slug = $page['slug'];
        }

        // If slug changed, ensure new slug is unique
        if ($slug !== $page['slug']) {
            $existing = Page::findBySlug($slug);
            if ($existing && (int)$existing['id'] !== $id) {
                header("Location: {$this->appUrl}/{$this->adminPath}/pages/edit?id={$id}&err=" . urlencode('এই স্লাগ (Slug) দিয়ে ইতোমধ্যে অন্য একটি পেজ রয়েছে।'));
                exit;
            }
        }

        try {
            Page::update($id, [
                'title'      => $title,
                'title_en'   => $titleEn,
                'badge'      => $badge !== '' ? $badge : 'প্রতিষ্ঠান',
                'slug'       => $slug,
                'content'    => $content,
                'meta_title' => $metaTitle,
                'meta_desc'  => $metaDesc,
                'status'     => $status
            ]);

            try {
                AuditLog::log('update_page', 'page', $id, ['slug' => $slug, 'title' => $title]);
            } catch (Exception $e) {}

            header("Location: {$this->appUrl}/{$this->adminPath}/pages?msg=updated");
            exit;
        } catch (Exception $e) {
            header("Location: {$this->appUrl}/{$this->adminPath}/pages/edit?id={$id}&err=" . urlencode('হালনাগাদ ব্যর্থ হয়েছে: ' . $e->getMessage()));
            exit;
        }
    }

    /**
     * Delete page
     */
    public function delete(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: {$this->appUrl}/{$this->adminPath}/pages");
            exit;
        }

        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die('CSRF ভ্যালিডেশন ব্যর্থ হয়েছে।');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $page = Page::findById($id);

        if (!$page) {
            header("Location: {$this->appUrl}/{$this->adminPath}/pages?err=" . urlencode('পেজ পাওয়া যায়নি।'));
            exit;
        }

        // Warn/prevent deleting critical system pages like about or terms, or allow deletion
        $coreSlugs = ['about', 'contact', 'advertise', 'privacy', 'terms', 'corrections'];
        if (in_array($page['slug'], $coreSlugs, true)) {
            // Instead of deleting, reset to draft
            Page::update($id, array_merge($page, ['status' => 'draft']));
            header("Location: {$this->appUrl}/{$this->adminPath}/pages?msg=core_drafted");
            exit;
        }

        Page::delete($id);
        try {
            AuditLog::log('delete_page', 'page', $id, ['slug' => $page['slug']]);
        } catch (Exception $e) {}

        header("Location: {$this->appUrl}/{$this->adminPath}/pages?msg=deleted");
        exit;
    }

    /**
     * Toggle status between published and draft
     */
    public function toggle(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: {$this->appUrl}/{$this->adminPath}/pages");
            exit;
        }

        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die('CSRF ভ্যালিডেশন ব্যর্থ হয়েছে।');
        }

        $id = (int) ($_POST['id'] ?? 0);
        Page::toggleStatus($id);

        header("Location: {$this->appUrl}/{$this->adminPath}/pages?msg=toggled");
        exit;
    }

    /**
     * Convert string to URL friendly slug
     */
    private function slugify(string $text): string {
        $text = strtolower(trim($text));
        $text = preg_replace('/[^\p{L}\p{Nd}\-]+/u', '-', $text);
        $text = trim($text, '-');
        return $text;
    }
}

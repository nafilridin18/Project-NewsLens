<?php
namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Auth;
use App\Core\Database;
use Exception;

class DashboardController {
    public function index(): void {
        $config = require CONFIG_PATH . '/config.php';
        $adminPath = $config['app']['admin_path'] ?? 'lens-desk';
        $appUrl = rtrim($config['app']['url'] ?? '', '/');

        if (!Auth::check()) {
            header("Location: {$appUrl}/{$adminPath}/login");
            exit;
        }

        $user = Auth::user();

        // Fetch counts from DB
        $stats = [
            'total_published' => 0,
            'total_pending'   => 0,
            'total_drafts'    => 0,
            'total_views'     => 0
        ];
        $recentPosts = [];
        $topViewedPosts = [];

        try {
            $db = Database::getConnection();
            $stmt = $db->query("
                SELECT 
                    COUNT(CASE WHEN status = 'published' THEN 1 END) as total_published,
                    COUNT(CASE WHEN status = 'pending' THEN 1 END) as total_pending,
                    COUNT(CASE WHEN status = 'draft' THEN 1 END) as total_drafts,
                    COALESCE(SUM(views), 0) as total_views
                FROM `posts`
            ");
            $dbStats = $stmt->fetch();
            if ($dbStats) {
                $stats = array_merge($stats, $dbStats);
            }

            // Recent posts
            $stmtRecent = $db->query("
                SELECT p.id, p.title, p.status, p.created_at, p.views, c.name_bn as category_name, u.name as author_name
                FROM `posts` p
                JOIN `categories` c ON p.category_id = c.id
                LEFT JOIN `users` u ON p.author_id = u.id
                ORDER BY p.id DESC
                LIMIT 8
            ");
            $recentPosts = $stmtRecent->fetchAll();

            // Most viewed posts (Top read news)
            $stmtTop = $db->query("
                SELECT p.id, p.title, p.slug, p.views, p.published_at, c.name_bn as category_name, c.slug as category_slug
                FROM `posts` p
                JOIN `categories` c ON p.category_id = c.id
                WHERE p.status = 'published'
                ORDER BY p.views DESC
                LIMIT 5
            ");
            $topViewedPosts = $stmtTop->fetchAll();
        } catch (Exception $e) {}

        View::render('admin.dashboard', [
            'pageTitle'      => 'অ্যাডমিন ড্যাশবোর্ড | Newslensbd CMS',
            'user'           => $user,
            'stats'          => $stats,
            'recentPosts'    => $recentPosts,
            'topViewedPosts' => $topViewedPosts,
            'adminPath'      => $adminPath,
            'appUrl'         => $appUrl
        ], null);
    }
}

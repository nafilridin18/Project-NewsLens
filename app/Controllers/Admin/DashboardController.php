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
            'total_published'   => 0,
            'total_pending'     => 0,
            'total_drafts'      => 0,
            'total_views'       => 0,
            'total_breaking'    => 0,
            'total_subscribers' => 0,
            'total_ads'         => 0
        ];
        $recentPosts = [];
        $topViewedPosts = [];
        $activePoll = null;
        $categoryDistribution = [];

        try {
            $db = Database::getConnection();
            $stmt = $db->query("
                SELECT 
                    COUNT(CASE WHEN status = 'published' THEN 1 END) as total_published,
                    COUNT(CASE WHEN status = 'pending' THEN 1 END) as total_pending,
                    COUNT(CASE WHEN status = 'draft' THEN 1 END) as total_drafts,
                    COUNT(CASE WHEN is_breaking = 1 THEN 1 END) as total_breaking,
                    COALESCE(SUM(views), 0) as total_views
                FROM `posts`
            ");
            $dbStats = $stmt->fetch();
            if ($dbStats) {
                $stats = array_merge($stats, $dbStats);
            }

            // Subscribers count
            try {
                $subStmt = $db->query("SELECT COUNT(*) FROM `newsletter_subscribers`");
                $stats['total_subscribers'] = (int) $subStmt->fetchColumn();
            } catch (Exception $e) {}

            // Active Ads count
            try {
                $adStmt = $db->query("SELECT COUNT(*) FROM `ads` WHERE `is_active` = 1");
                $stats['total_ads'] = (int) $adStmt->fetchColumn();
            } catch (Exception $e) {}

            // Category breakdown (top 5 categories by post count)
            try {
                $catDistStmt = $db->query("
                    SELECT c.name_bn, c.name_en, c.slug, COUNT(p.id) as post_count 
                    FROM `categories` c 
                    LEFT JOIN `posts` p ON c.id = p.category_id 
                    GROUP BY c.id, c.name_bn, c.name_en, c.slug 
                    ORDER BY post_count DESC 
                    LIMIT 6
                ");
                $categoryDistribution = $catDistStmt->fetchAll();
            } catch (Exception $e) {}

            // Active Poll
            try {
                $activePoll = \App\Models\Poll::getActive();
            } catch (Exception $e) {}

            // Recent posts (with slug and category slug for direct preview & edit)
            $stmtRecent = $db->query("
                SELECT p.id, p.title, p.slug, p.status, p.created_at, p.views, c.name_bn as category_name, c.name_en as category_name_en, c.slug as category_slug, 
                       COALESCE(NULLIF(p.custom_author, ''), u.name, 'স্টাফ') as author_name
                FROM `posts` p
                JOIN `categories` c ON p.category_id = c.id
                LEFT JOIN `users` u ON p.author_id = u.id
                ORDER BY p.id DESC
                LIMIT 8
            ");
            $recentPosts = $stmtRecent->fetchAll();

            // Most viewed posts (Top read news)
            $stmtTop = $db->query("
                SELECT p.id, p.title, p.slug, p.views, p.published_at, c.name_bn as category_name, c.name_en as category_name_en, c.slug as category_slug
                FROM `posts` p
                JOIN `categories` c ON p.category_id = c.id
                WHERE p.status = 'published'
                ORDER BY p.views DESC
                LIMIT 5
            ");
            $topViewedPosts = $stmtTop->fetchAll();
        } catch (Exception $e) {}

        View::render('admin.dashboard', [
            'pageTitle'            => 'অ্যাডমিন ড্যাশবোর্ড ও কন্ট্রোল প্যানেল | Newslensbd CMS',
            'user'                 => $user,
            'stats'                => $stats,
            'recentPosts'          => $recentPosts,
            'topViewedPosts'       => $topViewedPosts,
            'activePoll'           => $activePoll,
            'categoryDistribution' => $categoryDistribution,
            'adminPath'            => $adminPath,
            'appUrl'               => $appUrl
        ], null);
    }
}

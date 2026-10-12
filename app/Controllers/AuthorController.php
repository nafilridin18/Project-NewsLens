<?php
namespace App\Controllers;

use App\Core\Database;
use App\Core\View;
use App\Models\Category;
use App\Models\Post;
use App\Models\Setting;
use App\Helpers\BanglaDate;
use PDO;

class AuthorController {
    /**
     * Public Author Page: /author/{id}
     */
    public function show($idOrParams): void {
        $id = is_array($idOrParams) ? (int)($idOrParams['id'] ?? 0) : (int)$idOrParams;
        if ($id <= 0) {
            http_response_code(404);
            View::render('errors.404', ['pageTitle' => 'লেখক পাওয়া যায়নি | Newslensbd']);
            return;
        }

        $db = Database::getConnection();

        // Fetch author (safe column selection)
        $stmt = $db->prepare("
            SELECT id, name, email, avatar, 
                   COALESCE(NULLIF(bio, ''), '') AS bio, 
                   COALESCE(NULLIF(bio, ''), '') AS short_bio, 
                   social_links, role, created_at
            FROM `users`
            WHERE id = :id AND status = 'active' AND deleted_at IS NULL
            LIMIT 1
        ");
        $stmt->execute([':id' => $id]);
        $author = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$author) {
            http_response_code(404);
            View::render('errors.404', ['pageTitle' => 'লেখক পাওয়া যায়নি | Newslensbd']);
            return;
        }

        // Fetch author's published posts
        $pStmt = $db->prepare("
            SELECT p.*, c.name_bn AS category_name, c.slug AS category_slug
            FROM `posts` p
            JOIN `categories` c ON c.id = p.category_id
            WHERE p.author_id = :aid AND p.status = 'published'
            ORDER BY p.published_at DESC
        ");
        $pStmt->execute([':aid' => $id]);
        $posts = $pStmt->fetchAll(PDO::FETCH_ASSOC);

        // Calculate stats
        $totalViews = array_sum(array_column($posts, 'views'));
        $postCount = count($posts);

        $socialLinks = [];
        if (!empty($author['social_links'])) {
            $socialLinks = is_array($author['social_links']) ? $author['social_links'] : (json_decode($author['social_links'], true) ?: []);
        }

        $categories   = Category::getMenuCategories();
        $breakingNews = Post::getBreakingNews(6);
        $settings     = Setting::all();
        $todayBn      = BanglaDate::formatBnDate(null, true);

        View::render('pages.author', [
            'pageTitle'    => htmlspecialchars($author['name']) . ' - লেখকের প্রতিবেদনসমূহ | Newslensbd',
            'author'       => $author,
            'posts'        => $posts,
            'postCount'    => $postCount,
            'totalViews'   => $totalViews,
            'socialLinks'  => $socialLinks,
            'categories'   => $categories,
            'breakingNews' => $breakingNews,
            'settings'     => $settings,
            'todayBn'      => $todayBn
        ]);
    }
}

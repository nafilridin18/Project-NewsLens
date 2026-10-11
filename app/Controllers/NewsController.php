<?php
namespace App\Controllers;

use App\Core\View;
use App\Models\Post;
use App\Models\Category;
use App\Models\District;
use App\Models\Setting;
use App\Helpers\BanglaDate;

class NewsController {
    /**
     * Show single news article
     */
    public function show(array $params = []): void {
        $rawId = $params['id'] ?? $_GET['id'] ?? 0;
        $post = null;

        if (is_numeric($rawId) && (int)$rawId > 0) {
            $post = Post::find((int)$rawId);
        }

        // If not found by ID or rawId is a string slug, try finding by slug
        if (!$post) {
            $slugCandidate = $params['slug'] ?? (!is_numeric($rawId) ? $rawId : null);
            if (!empty($slugCandidate)) {
                $post = Post::findBySlug((string)$slugCandidate);
            }
        }

        if (!$post) {
            http_response_code(404);
            View::render('errors.404', ['pageTitle' => 'খবর পাওয়া যায়নি | Newslensbd']);
            return;
        }

        $id = (int) $post['id'];

        // Record view with 30-minute duplicate protection
        \App\Models\PostView::recordView($id);

        $categories   = Category::getMenuCategories();
        $breakingNews = Post::getBreakingNews(6);
        $relatedPosts = Post::getRelated((int)$post['category_id'], $id, 4);
        $popularPosts = Post::getPopular(6);
        $settings     = Setting::all();
        $todayBn      = BanglaDate::formatBnDate(null, true);

        View::render('pages.single', [
            'pageTitle'    => htmlspecialchars($post['title']) . ' | Newslensbd',
            'post'         => $post,
            'relatedPosts' => $relatedPosts,
            'popularPosts' => $popularPosts,
            'categories'   => $categories,
            'breakingNews' => $breakingNews,
            'settings'     => $settings,
            'todayBn'      => $todayBn
        ]);
    }

    /**
     * Category archive page
     */
    public function category(array $params = []): void {
        $slug = $params['slug'] ?? $_GET['slug'] ?? '';
        $categories = Category::getMenuCategories();
        
        // Find current category
        $currentCategory = null;
        foreach ($categories as $cat) {
            if ($cat['slug'] === $slug) {
                $currentCategory = $cat;
                break;
            }
        }

        if (!$currentCategory) {
            // Check direct from DB
            $db = \App\Core\Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM `categories` WHERE `slug` = :slug LIMIT 1");
            $stmt->execute([':slug' => $slug]);
            $currentCategory = $stmt->fetch();
        }

        if (!$currentCategory) {
            http_response_code(404);
            View::render('errors.404', ['pageTitle' => 'ক্যাটাগরি পাওয়া যায়নি | Newslensbd']);
            return;
        }

        $posts = Post::search(['category_id' => $currentCategory['id']], 24);
        $breakingNews = Post::getBreakingNews(6);
        $popularPosts = Post::getPopular(6);
        $settings     = Setting::all();
        $todayBn      = BanglaDate::formatBnDate(null, true);

        View::render('pages.category', [
            'pageTitle'       => htmlspecialchars($currentCategory['name_bn']) . ' সংবাদ | Newslensbd',
            'currentCategory' => $currentCategory,
            'posts'           => $posts,
            'categories'      => $categories,
            'breakingNews'    => $breakingNews,
            'popularPosts'    => $popularPosts,
            'settings'        => $settings,
            'todayBn'         => $todayBn
        ]);
    }

    /**
     * Search & Filter page with headline, time, location, category
     */
    public function search(): void {
        $q          = trim($_GET['q'] ?? '');
        $categoryId = !empty($_GET['category']) ? (int) $_GET['category'] : null;
        $divisionId = !empty($_GET['division']) ? (int) $_GET['division'] : null;
        $districtId = !empty($_GET['district']) ? (int) $_GET['district'] : null;
        $timeRange  = trim($_GET['time'] ?? '');

        $filters = [
            'q'           => $q,
            'category_id' => $categoryId,
            'division_id' => $divisionId,
            'district_id' => $districtId,
            'time_range'  => $timeRange
        ];

        $results = Post::search($filters, 30);

        $categories   = Category::getMenuCategories();
        $divisions    = District::getDivisions();
        $districts    = $divisionId ? District::getDistrictsByDivision($divisionId) : [];
        $breakingNews = Post::getBreakingNews(6);
        $popularPosts = Post::getPopular(6);
        $settings     = Setting::all();
        $todayBn      = BanglaDate::formatBnDate(null, true);

        View::render('pages.search', [
            'pageTitle'    => ($q !== '' ? 'অনুসন্ধান: "' . htmlspecialchars($q) . '" | ' : 'সংবাদ অনুসন্ধান | ') . 'Newslensbd',
            'q'            => $q,
            'filters'      => $filters,
            'results'      => $results,
            'categories'   => $categories,
            'divisions'    => $divisions,
            'districts'    => $districts,
            'breakingNews' => $breakingNews,
            'popularPosts' => $popularPosts,
            'settings'     => $settings,
            'todayBn'      => $todayBn
        ]);
    }
}

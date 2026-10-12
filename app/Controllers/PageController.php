<?php
namespace App\Controllers;

use App\Core\View;
use App\Core\Auth;
use App\Models\Category;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Page;
use App\Helpers\BanglaDate;

class PageController {
    /**
     * Show static pages (about, contact, advertise, privacy, terms, corrections or any custom slug)
     */
    public function show(array $params = []): void {
        $slug = trim($params['slug'] ?? $_GET['slug'] ?? '');
        $categories   = Category::getMenuCategories();
        $breakingNews = Post::getBreakingNews(6);
        $settings     = Setting::all();
        $todayBn      = BanglaDate::formatBnDate(null, true);

        // Fetch page from database
        $isAdmin = Auth::check() && in_array(Auth::user()['role'] ?? '', ['super_admin', 'admin', 'editor'], true);
        $page = Page::findBySlug($slug, !$isAdmin);

        if (!$page) {
            http_response_code(404);
            View::render('errors.404', [
                'pageTitle'    => 'পাতাটি পাওয়া যায়নি | Newslensbd',
                'categories'   => $categories,
                'breakingNews' => $breakingNews,
                'settings'     => $settings,
                'todayBn'      => $todayBn
            ]);
            return;
        }

        // Support backward compatibility for title_bn
        $page['title_bn'] = $page['title_bn'] ?? $page['title'] ?? '';

        View::render('pages.static_page', [
            'pageTitle'    => (!empty($page['meta_title']) ? $page['meta_title'] : ($page['title_bn'] . ' | Newslensbd')),
            'slug'         => $slug,
            'page'         => $page,
            'categories'   => $categories,
            'breakingNews' => $breakingNews,
            'settings'     => $settings,
            'todayBn'      => $todayBn,
            'isAdmin'      => $isAdmin
        ]);
    }
}

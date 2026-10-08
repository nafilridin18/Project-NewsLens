<?php
namespace App\Controllers;

use App\Core\View;
use App\Models\Post;
use App\Models\Category;
use App\Models\Setting;
use App\Models\Poll;
use App\Helpers\BanglaDate;

class HomeController {
    public function index(): void {
        $categories   = Category::getMenuCategories();
        $breakingNews = Post::getBreakingNews(6);
        $leadStories  = Post::getLeadStories();
        $latestPosts  = Post::getLatest(8);
        $popularPosts = Post::getPopular(8);
        $settings     = Setting::all();
        $activePoll   = Poll::getActive();
        $videoPosts   = Post::getVideoPosts(4);

        // Specific category block sections
        $nationalPosts = Post::getCategorySection('national', 4);
        $politicsPosts = Post::getCategorySection('politics', 4);
        $sportsPosts   = Post::getCategorySection('sports', 4);
        $economyPosts  = Post::getCategorySection('economy', 4);
        $techPosts     = Post::getCategorySection('tech', 4);

        // Current Bangla dates for header
        $todayBn = BanglaDate::formatBnDate(null, true);

        View::render('pages.home', [
            'pageTitle'     => 'Newslensbd | সাধারণের বাইরে, সত্যের খোঁজে',
            'categories'    => $categories,
            'breakingNews'  => $breakingNews,
            'leadStories'   => $leadStories,
            'latestPosts'   => $latestPosts,
            'popularPosts'  => $popularPosts,
            'nationalPosts' => $nationalPosts,
            'politicsPosts' => $politicsPosts,
            'sportsPosts'   => $sportsPosts,
            'economyPosts'  => $economyPosts,
            'techPosts'     => $techPosts,
            'videoPosts'    => $videoPosts,
            'activePoll'    => $activePoll,
            'settings'      => $settings,
            'todayBn'       => $todayBn
        ]);
    }
}

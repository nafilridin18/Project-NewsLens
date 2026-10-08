<?php
namespace App\Controllers;

use App\Core\View;
use App\Models\District;
use App\Models\Category;
use App\Models\Post;
use App\Models\Setting;
use App\Helpers\BanglaDate;

class SaradeshController {
    public function index(): void {
        $divisionId = isset($_GET['division']) ? (int) $_GET['division'] : null;
        $districtId = isset($_GET['district']) ? (int) $_GET['district'] : null;

        $divisions  = District::getDivisions();
        $districts  = $divisionId ? District::getDistrictsByDivision($divisionId) : [];
        $newsList   = District::getNewsByDistrict($districtId, $divisionId, 12);

        $categories   = Category::getMenuCategories();
        $breakingNews = Post::getBreakingNews(5);
        $settings     = Setting::all();
        $todayBn      = BanglaDate::formatBnDate(null, true);

        View::render('pages.saradesh', [
            'pageTitle'    => 'সারাদেশের খবর | Newslensbd (নিউজলেন্সবিডি)',
            'divisions'    => $divisions,
            'districts'    => $districts,
            'newsList'     => $newsList,
            'selectedDiv'  => $divisionId,
            'selectedDist' => $districtId,
            'categories'   => $categories,
            'breakingNews' => $breakingNews,
            'settings'     => $settings,
            'todayBn'      => $todayBn
        ]);
    }

    public function getDistricts(): void {
        header('Content-Type: application/json; charset=utf-8');
        $divisionId = (int) ($_GET['division_id'] ?? 0);
        if ($divisionId <= 0) {
            echo json_encode([]);
            return;
        }
        $districts = District::getDistrictsByDivision($divisionId);
        echo json_encode($districts, JSON_UNESCAPED_UNICODE);
    }
}

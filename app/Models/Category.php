<?php
namespace App\Models;

use App\Core\Database;
use PDO;
use Exception;

class Category {
    public static function getMenuCategories(): array {
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT * FROM `categories` WHERE `is_menu` = 1 ORDER BY `sort_order` ASC");
            $results = $stmt->fetchAll();
            if (!empty($results)) {
                return $results;
            }
        } catch (Exception $e) {
            // Fallback to default list if DB is offline or not yet seeded
        }

        return [
            ['id' => 1, 'name_bn' => 'জাতীয়', 'name_en' => 'National', 'slug' => 'national'],
            ['id' => 2, 'name_bn' => 'রাজনীতি', 'name_en' => 'Politics', 'slug' => 'politics'],
            ['id' => 3, 'name_bn' => 'সারাদেশ', 'name_en' => 'Countrywide', 'slug' => 'saradesh'],
            ['id' => 4, 'name_bn' => 'অর্থনীতি', 'name_en' => 'Economy', 'slug' => 'economy'],
            ['id' => 5, 'name_bn' => 'আন্তর্জাতিক', 'name_en' => 'International', 'slug' => 'international'],
            ['id' => 6, 'name_bn' => 'খেলা', 'name_en' => 'Sports', 'slug' => 'sports'],
            ['id' => 7, 'name_bn' => 'বিনোদন', 'name_en' => 'Entertainment', 'slug' => 'entertainment'],
            ['id' => 8, 'name_bn' => 'বিজ্ঞান ও প্রযুক্তি', 'name_en' => 'Tech', 'slug' => 'tech'],
            ['id' => 9, 'name_bn' => 'শিক্ষা', 'name_en' => 'Education', 'slug' => 'education'],
            ['id' => 10, 'name_bn' => 'স্বাস্থ্য', 'name_en' => 'Health', 'slug' => 'health'],
            ['id' => 11, 'name_bn' => 'চাকরি', 'name_en' => 'Jobs', 'slug' => 'jobs'],
            ['id' => 12, 'name_bn' => 'মতামত', 'name_en' => 'Opinion', 'slug' => 'opinion'],
            ['id' => 13, 'name_bn' => 'লাইফস্টাইল', 'name_en' => 'Lifestyle', 'slug' => 'lifestyle'],
            ['id' => 14, 'name_bn' => 'প্রবাস', 'name_en' => 'Probash', 'slug' => 'probash']
        ];
    }
}

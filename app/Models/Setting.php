<?php
namespace App\Models;

use App\Core\Database;
use Exception;

class Setting {
    private static array $cache = [];

    public static function get(string $key, $default = null) {
        if (!empty(self::$cache)) {
            return self::$cache[$key] ?? $default;
        }

        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT `key`, `value` FROM `settings`");
            $rows = $stmt->fetchAll();
            foreach ($rows as $row) {
                self::$cache[$row['key']] = $row['value'];
            }
            return self::$cache[$key] ?? $default;
        } catch (Exception $e) {
            return $default;
        }
    }

    public static function all(): array {
        if (empty(self::$cache)) {
            try {
                $db = Database::getConnection();
                $stmt = $db->query("SELECT `key`, `value` FROM `settings`");
                $rows = $stmt->fetchAll();
                foreach ($rows as $row) {
                    self::$cache[$row['key']] = $row['value'];
                }
            } catch (Exception $e) {
                self::$cache = [
                    'site_name_bn' => 'নিউজলেন্সবিডি',
                    'site_name_en' => 'Newslensbd',
                    'tagline_bn' => 'সাধারণের বাইরে, সত্যের খোঁজে',
                    'tagline_en' => 'News Beyond the Ordinary',
                    'color_primary' => '#0b1f44',
                    'color_secondary' => '#0E7A3A',
                    'color_accent' => '#E31E24',
                    'breaking_news_active' => '1',
                    'tech_partner_name' => 'Stratifyx Global',
                    'tech_partner_url' => 'https://stratifyxglobal.com'
                ];
            }
        }
        return self::$cache;
    }
}

<?php
namespace App\Models;

use App\Core\Database;
use PDO;
use Exception;

class Ad {
    /**
     * In-memory cache for ads fetched during the current request
     */
    private static array $cache = [];

    /**
     * Get active advertisement for a specific position
     */
    public static function getByPosition(string $position): ?array {
        if (array_key_exists($position, self::$cache)) {
            return self::$cache[$position];
        }

        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                SELECT * FROM `ads` 
                WHERE `position` = :pos 
                  AND `is_active` = 1 
                  AND (`start_at` IS NULL OR `start_at` <= NOW())
                  AND (`end_at` IS NULL OR `end_at` >= NOW())
                ORDER BY `id` DESC 
                LIMIT 1
            ");
            $stmt->execute([':pos' => $position]);
            $ad = $stmt->fetch();
            $result = $ad ?: null;
            self::$cache[$position] = $result;
            return $result;
        } catch (Exception $e) {
            self::$cache[$position] = null;
            return null;
        }
    }

    /**
     * Increment impression counter safely
     */
    public static function incrementImpression(int $id): void {
        if ($id <= 0) return;
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("UPDATE `ads` SET `impressions` = `impressions` + 1 WHERE `id` = :id");
            $stmt->execute([':id' => $id]);
        } catch (Exception $e) {}
    }

    /**
     * Record click, increment counter, and return destination link
     */
    public static function recordClick(int $id): ?string {
        if ($id <= 0) return null;
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT `link` FROM `ads` WHERE `id` = :id LIMIT 1");
            $stmt->execute([':id' => $id]);
            $ad = $stmt->fetch();
            if ($ad) {
                $up = $db->prepare("UPDATE `ads` SET `clicks` = `clicks` + 1 WHERE `id` = :id");
                $up->execute([':id' => $id]);
                return !empty($ad['link']) ? $ad['link'] : null;
            }
            return null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Get aggregate statistics for advertisements dashboard
     */
    public static function getStats(): array {
        try {
            $db = Database::getConnection();
            $total = (int) $db->query("SELECT COUNT(*) FROM `ads`")->fetchColumn();
            $active = (int) $db->query("SELECT COUNT(*) FROM `ads` WHERE `is_active` = 1")->fetchColumn();
            $sums = $db->query("SELECT SUM(`clicks`) as total_clicks, SUM(`impressions`) as total_impressions FROM `ads`")->fetch();
            $clicks = (int) ($sums['total_clicks'] ?? 0);
            $impressions = (int) ($sums['total_impressions'] ?? 0);
            $ctr = $impressions > 0 ? round(($clicks / $impressions) * 100, 2) : 0.0;

            return [
                'total'       => $total,
                'active'      => $active,
                'clicks'      => $clicks,
                'impressions' => $impressions,
                'ctr'         => $ctr
            ];
        } catch (Exception $e) {
            return ['total' => 0, 'active' => 0, 'clicks' => 0, 'impressions' => 0, 'ctr' => 0.0];
        }
    }

    /**
     * Get all ads ordered by latest
     */
    public static function getAll(): array {
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT * FROM `ads` ORDER BY `id` DESC");
            return $stmt->fetchAll() ?: [];
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Find single ad by ID
     */
    public static function find(int $id): ?array {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM `ads` WHERE `id` = :id LIMIT 1");
            $stmt->execute([':id' => $id]);
            $ad = $stmt->fetch();
            return $ad ?: null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Create new advertisement
     */
    public static function create(array $data): int {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO `ads` (`title`, `position`, `type`, `image`, `video_url`, `link`, `code`, `is_active`, `created_at`)
            VALUES (:title, :position, :type, :image, :video_url, :link, :code, :is_active, NOW())
        ");
        $stmt->execute([
            ':title'     => $data['title'],
            ':position'  => $data['position'] ?? 'header',
            ':type'      => $data['type'] ?? 'image',
            ':image'     => $data['image'] ?? null,
            ':video_url' => $data['video_url'] ?? null,
            ':link'      => $data['link'] ?? null,
            ':code'      => $data['code'] ?? null,
            ':is_active' => !empty($data['is_active']) ? 1 : 0
        ]);
        self::$cache = [];
        return (int) $db->lastInsertId();
    }

    /**
     * Update existing advertisement
     */
    public static function update(int $id, array $data): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            UPDATE `ads`
            SET `title` = :title,
                `position` = :position,
                `type` = :type,
                `image` = :image,
                `video_url` = :video_url,
                `link` = :link,
                `code` = :code,
                `is_active` = :is_active
            WHERE `id` = :id
        ");
        $res = $stmt->execute([
            ':title'     => $data['title'],
            ':position'  => $data['position'] ?? 'header',
            ':type'      => $data['type'] ?? 'image',
            ':image'     => $data['image'] ?? null,
            ':video_url' => $data['video_url'] ?? null,
            ':link'      => $data['link'] ?? null,
            ':code'      => $data['code'] ?? null,
            ':is_active' => !empty($data['is_active']) ? 1 : 0,
            ':id'        => $id
        ]);
        self::$cache = [];
        return $res;
    }

    /**
     * Toggle active status
     */
    public static function toggleActive(int $id): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE `ads` SET `is_active` = 1 - `is_active` WHERE `id` = :id");
        self::$cache = [];
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Delete advertisement
     */
    public static function delete(int $id): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM `ads` WHERE `id` = :id");
        self::$cache = [];
        return $stmt->execute([':id' => $id]);
    }
}

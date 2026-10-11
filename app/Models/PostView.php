<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class PostView {
    /**
     * Record a post view with 30-minute duplicate protection per IP hash.
     * Atomic counter update on posts.views only occurs when a view is distinct.
     */
    public static function recordView(int $postId): bool {
        if ($postId <= 0) return false;

        $ip = self::getClientIp();
        $salt = $_ENV['APP_KEY'] ?? 'newslens_views_salt_2026';
        $ipHash = hash('sha256', $ip . $salt);
        $userAgent = mb_substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 250);

        try {
            $db = Database::getConnection();

            // Check if viewed within last 30 minutes
            $checkStmt = $db->prepare("
                SELECT `id` 
                FROM `post_views` 
                WHERE `post_id` = :pid 
                  AND `ip_hash` = :hash 
                  AND `viewed_at` >= DATE_SUB(NOW(), INTERVAL 30 MINUTE)
                LIMIT 1
            ");
            $checkStmt->execute([
                ':pid'  => $postId,
                ':hash' => $ipHash
            ]);

            if ($checkStmt->fetch()) {
                // Duplicate view within 30 minutes
                return false;
            }

            // Insert distinct view log
            $insStmt = $db->prepare("
                INSERT INTO `post_views` (`post_id`, `ip_hash`, `user_agent`, `viewed_at`)
                VALUES (:pid, :hash, :ua, NOW())
            ");
            $insStmt->execute([
                ':pid'  => $postId,
                ':hash' => $ipHash,
                ':ua'   => $userAgent
            ]);

            // Atomic counter update
            $updStmt = $db->prepare("UPDATE `posts` SET `views` = `views` + 1 WHERE `id` = :pid");
            $updStmt->execute([':pid' => $postId]);

            return true;
        } catch (\Exception $e) {
            // Fallback: simple increment if post_views query fails
            try {
                $db = Database::getConnection();
                $db->prepare("UPDATE `posts` SET `views` = `views` + 1 WHERE `id` = :pid")
                   ->execute([':pid' => $postId]);
            } catch (\Exception $ex) {}
            return false;
        }
    }

    /**
     * Get daily views for an author's posts over the last 30 days
     */
    public static function getAuthorDailyViews(int $authorId, int $days = 30): array {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                SELECT 
                    DATE(pv.viewed_at) AS view_date, 
                    COUNT(*) AS view_count
                FROM `post_views` pv
                JOIN `posts` p ON p.id = pv.post_id
                WHERE p.author_id = :uid 
                  AND pv.viewed_at >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
                GROUP BY DATE(pv.viewed_at)
                ORDER BY view_date ASC
            ");
            $stmt->bindValue(':uid', $authorId, PDO::PARAM_INT);
            $stmt->bindValue(':days', $days, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get posts count and total views per category for an author
     */
    public static function getAuthorCategoryStats(int $authorId): array {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                SELECT 
                    c.id AS category_id,
                    c.name_bn,
                    c.slug,
                    COUNT(p.id) AS post_count,
                    COALESCE(SUM(p.views), 0) AS total_views
                FROM `posts` p
                JOIN `categories` c ON c.id = p.category_id
                WHERE p.author_id = :uid
                GROUP BY c.id, c.name_bn, c.slug
                ORDER BY total_views DESC
            ");
            $stmt->execute([':uid' => $authorId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return [];
        }
    }

    private static function getClientIp(): string {
        $headers = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                $ip = trim($ips[0]);
                if (filter_var($ip, FILTER_VALIDATE_IP)) {
                    return $ip;
                }
            }
        }
        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }
}

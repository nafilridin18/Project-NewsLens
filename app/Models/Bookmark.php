<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Bookmark {
    /**
     * Check if a post is bookmarked by a user
     */
    public static function isBookmarked(int $userId, int $postId): bool {
        if ($userId <= 0 || $postId <= 0) return false;
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT id FROM `bookmarks` WHERE user_id = :uid AND post_id = :pid LIMIT 1");
            $stmt->execute([':uid' => $userId, ':pid' => $postId]);
            return (bool) $stmt->fetch();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Toggle bookmark: returns true if added, false if removed
     */
    public static function toggle(int $userId, int $postId): bool {
        if ($userId <= 0 || $postId <= 0) return false;
        try {
            $db = Database::getConnection();
            $check = $db->prepare("SELECT id FROM `bookmarks` WHERE user_id = :uid AND post_id = :pid LIMIT 1");
            $check->execute([':uid' => $userId, ':pid' => $postId]);
            $existing = $check->fetch(PDO::FETCH_ASSOC);

            if ($existing) {
                $del = $db->prepare("DELETE FROM `bookmarks` WHERE id = :id");
                $del->execute([':id' => $existing['id']]);
                return false;
            } else {
                $ins = $db->prepare("INSERT INTO `bookmarks` (user_id, post_id, created_at) VALUES (:uid, :pid, NOW())");
                $ins->execute([':uid' => $userId, ':pid' => $postId]);
                return true;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get all bookmarked posts for a user
     */
    public static function getUserBookmarks(int $userId): array {
        if ($userId <= 0) return [];
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                SELECT b.id AS bookmark_id, b.created_at AS bookmarked_at,
                       p.id AS post_id, p.title, p.slug, p.featured_image, p.views, p.published_at,
                       c.name_bn AS category_name, c.slug AS category_slug
                FROM `bookmarks` b
                JOIN `posts` p ON p.id = b.post_id
                JOIN `categories` c ON c.id = p.category_id
                WHERE b.user_id = :uid AND p.status = 'published'
                ORDER BY b.created_at DESC
            ");
            $stmt->execute([':uid' => $userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return [];
        }
    }
}

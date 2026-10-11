<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class Notification {
    /**
     * Create an in-app notification (for specific user or broadcast if user_id is null)
     */
    public static function create(array $data): int {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                INSERT INTO `notifications` (`user_id`, `title`, `message`, `url`, `type`, `created_at`)
                VALUES (:uid, :title, :msg, :url, :type, NOW())
            ");
            $stmt->execute([
                ':uid'   => !empty($data['user_id']) ? (int) $data['user_id'] : null,
                ':title' => $data['title'] ?? '',
                ':msg'   => $data['message'] ?? '',
                ':url'   => $data['url'] ?? null,
                ':type'  => $data['type'] ?? 'system'
            ]);
            return (int) $db->lastInsertId();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Fetch recent notifications for user (or broadcast notifications if guest)
     */
    public static function getRecentForUser(?int $userId, int $limit = 10): array {
        try {
            $db = Database::getConnection();
            if ($userId && $userId > 0) {
                $stmt = $db->prepare("
                    SELECT * FROM `notifications`
                    WHERE `user_id` = :uid OR `user_id` IS NULL
                    ORDER BY `created_at` DESC
                    LIMIT :limit
                ");
                $stmt->bindValue(':uid', $userId, PDO::PARAM_INT);
            } else {
                $stmt = $db->prepare("
                    SELECT * FROM `notifications`
                    WHERE `user_id` IS NULL
                    ORDER BY `created_at` DESC
                    LIMIT :limit
                ");
            }
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get unread count
     */
    public static function getUnreadCount(?int $userId): int {
        try {
            $db = Database::getConnection();
            if ($userId && $userId > 0) {
                $stmt = $db->prepare("
                    SELECT COUNT(*) FROM `notifications`
                    WHERE (`user_id` = :uid OR `user_id` IS NULL) AND `is_read` = 0
                ");
                $stmt->execute([':uid' => $userId]);
            } else {
                $stmt = $db->query("
                    SELECT COUNT(*) FROM `notifications`
                    WHERE `user_id` IS NULL AND `is_read` = 0
                ");
            }
            return (int) $stmt->fetchColumn();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Mark all notifications as read
     */
    public static function markAllAsRead(?int $userId): void {
        try {
            $db = Database::getConnection();
            if ($userId && $userId > 0) {
                $stmt = $db->prepare("
                    UPDATE `notifications` SET `is_read` = 1
                    WHERE `user_id` = :uid OR `user_id` IS NULL
                ");
                $stmt->execute([':uid' => $userId]);
            } else {
                $db->query("UPDATE `notifications` SET `is_read` = 1 WHERE `user_id` IS NULL");
            }
        } catch (\Exception $e) {}
    }
}

<?php
namespace App\Models;

use App\Core\Database;
use PDO;

class PushSubscription {
    /**
     * Store or update a Web Push Subscription
     */
    public static function save(string $endpoint, ?string $publicKey, ?string $authToken, ?int $userId = null): bool {
        if (empty($endpoint)) return false;

        try {
            $db = Database::getConnection();

            // Check if already subscribed
            $stmt = $db->prepare("SELECT id FROM `push_subscriptions` WHERE endpoint = :ep LIMIT 1");
            $stmt->execute([':ep' => $endpoint]);
            $existing = $stmt->fetch();

            if ($existing) {
                $upd = $db->prepare("
                    UPDATE `push_subscriptions` 
                    SET user_id = :uid, public_key = :pk, auth_token = :auth 
                    WHERE id = :id
                ");
                $upd->execute([
                    ':uid'  => $userId,
                    ':pk'   => $publicKey,
                    ':auth' => $authToken,
                    ':id'   => $existing['id']
                ]);
            } else {
                $ins = $db->prepare("
                    INSERT INTO `push_subscriptions` (user_id, endpoint, public_key, auth_token, created_at)
                    VALUES (:uid, :ep, :pk, :auth, NOW())
                ");
                $ins->execute([
                    ':uid'  => $userId,
                    ':ep'   => $endpoint,
                    ':pk'   => $publicKey,
                    ':auth' => $authToken
                ]);
            }
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get all active push subscriptions
     */
    public static function all(): array {
        try {
            $db = Database::getConnection();
            return $db->query("SELECT * FROM `push_subscriptions` ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Remove unsubscribed or expired endpoint
     */
    public static function deleteByEndpoint(string $endpoint): void {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("DELETE FROM `push_subscriptions` WHERE endpoint = :ep");
            $stmt->execute([':ep' => $endpoint]);
        } catch (\Exception $e) {}
    }
}

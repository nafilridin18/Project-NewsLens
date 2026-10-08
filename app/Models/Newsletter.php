<?php
namespace App\Models;

use App\Core\Database;
use PDO;
use Exception;

class Newsletter {
    public static function subscribe(string $email): array {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'অনুগ্রহ করে সঠিক ইমেইল ঠিকানা দিন।'];
        }

        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                INSERT INTO `newsletter_subscribers` (`email`, `is_active`, `subscribed_at`) 
                VALUES (:email, 1, NOW())
                ON DUPLICATE KEY UPDATE `is_active` = 1
            ");
            $stmt->execute([':email' => strtolower(trim($email))]);
            return ['success' => true, 'message' => 'নিউজলেটারে সফলভাবে সাবস্ক্রাইব করা হয়েছে! ধন্যবাদ।'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'সাবস্ক্রিপশনে ত্রুটি হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।'];
        }
    }

    public static function getAll(): array {
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT * FROM `newsletter_subscribers` ORDER BY `id` DESC");
            return $stmt->fetchAll() ?: [];
        } catch (Exception $e) {
            return [];
        }
    }

    public static function delete(int $id): bool {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("DELETE FROM `newsletter_subscribers` WHERE `id` = :id");
            return $stmt->execute([':id' => $id]);
        } catch (Exception $e) {
            return false;
        }
    }
}

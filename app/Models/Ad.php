<?php
namespace App\Models;

use App\Core\Database;
use PDO;
use Exception;

class Ad {
    public static function getByPosition(string $position): ?array {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM `ads` WHERE `position` = :pos AND `is_active` = 1 ORDER BY `id` DESC LIMIT 1");
            $stmt->execute([':pos' => $position]);
            $ad = $stmt->fetch();
            return $ad ?: null;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function getAll(): array {
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT * FROM `ads` ORDER BY `id` DESC");
            return $stmt->fetchAll() ?: [];
        } catch (Exception $e) {
            return [];
        }
    }

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

    public static function create(array $data): int {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO `ads` (`title`, `position`, `type`, `image`, `video_url`, `link`, `is_active`, `created_at`)
            VALUES (:title, :position, :type, :image, :video_url, :link, :is_active, NOW())
        ");
        $stmt->execute([
            ':title'     => $data['title'],
            ':position'  => $data['position'] ?? 'header',
            ':type'      => $data['type'] ?? 'image',
            ':image'     => $data['image'] ?? null,
            ':video_url' => $data['video_url'] ?? null,
            ':link'      => $data['link'] ?? null,
            ':is_active' => !empty($data['is_active']) ? 1 : 0
        ]);
        return (int) $db->lastInsertId();
    }

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
                `is_active` = :is_active
            WHERE `id` = :id
        ");
        return $stmt->execute([
            ':title'     => $data['title'],
            ':position'  => $data['position'] ?? 'header',
            ':type'      => $data['type'] ?? 'image',
            ':image'     => $data['image'] ?? null,
            ':video_url' => $data['video_url'] ?? null,
            ':link'      => $data['link'] ?? null,
            ':is_active' => !empty($data['is_active']) ? 1 : 0,
            ':id'        => $id
        ]);
    }

    public static function toggleActive(int $id): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE `ads` SET `is_active` = 1 - `is_active` WHERE `id` = :id");
        return $stmt->execute([':id' => $id]);
    }

    public static function delete(int $id): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM `ads` WHERE `id` = :id");
        return $stmt->execute([':id' => $id]);
    }
}

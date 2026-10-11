<?php
namespace App\Models;

use App\Core\Database;
use App\Core\Auth;
use PDO;

class AuditLog {
    /**
     * Record an administrative or editorial action into audit_logs table
     */
    public static function log(string $action, ?string $targetType = null, ?int $targetId = null, ?array $details = null): bool {
        try {
            $db = Database::getConnection();
            $userId = Auth::id();
            $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

            $stmt = $db->prepare("
                INSERT INTO `audit_logs` (`user_id`, `action`, `target_type`, `target_id`, `details`, `ip`, `created_at`)
                VALUES (:user_id, :action, :target_type, :target_id, :details, :ip, NOW())
            ");
            return $stmt->execute([
                ':user_id'     => $userId,
                ':action'      => $action,
                ':target_type' => $targetType,
                ':target_id'   => $targetId,
                ':details'     => $details ? json_encode($details, JSON_UNESCAPED_UNICODE) : null,
                ':ip'          => $ip
            ]);
        } catch (\Exception $e) {
            // Fail silently so audit failure doesn't break user flow
            return false;
        }
    }
}

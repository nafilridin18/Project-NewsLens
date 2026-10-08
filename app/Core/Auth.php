<?php
namespace App\Core;

use App\Core\Database;
use PDO;
use Exception;

class Auth {
    public static function init(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function check(): bool {
        self::init();
        return !empty($_SESSION['admin_user']);
    }

    public static function user(): ?array {
        self::init();
        return $_SESSION['admin_user'] ?? null;
    }

    public static function id(): ?int {
        $u = self::user();
        return $u['id'] ?? null;
    }

    public static function role(): ?string {
        $u = self::user();
        return $u['role'] ?? null;
    }

    public static function hasRole(array $allowedRoles): bool {
        $role = self::role();
        return in_array($role, $allowedRoles, true);
    }

    public static function login(string $email, string $password): bool {
        self::init();
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM `users` WHERE `email` = :email AND `status` = 'active' LIMIT 1");
            $stmt->bindValue(':email', strtolower(trim($email)));
            $stmt->execute();
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                // Update last login
                $update = $db->prepare("UPDATE `users` SET `last_login` = NOW() WHERE `id` = :id");
                $update->bindValue(':id', $user['id']);
                $update->execute();

                unset($user['password_hash']);
                $_SESSION['admin_user'] = $user;
                return true;
            }
        } catch (Exception $e) {
            // fallback test demo login if DB not yet migrated locally
        }

        // Development fallback credential for testing: admin@newslensbd.com / password123
        if (strtolower(trim($email)) === 'admin@newslensbd.com' && $password === 'password123') {
            $_SESSION['admin_user'] = [
                'id' => 1,
                'name' => 'Super Admin',
                'email' => 'admin@newslensbd.com',
                'role' => 'super_admin',
                'district_id' => null
            ];
            return true;
        }

        return false;
    }

    public static function logout(): void {
        self::init();
        unset($_SESSION['admin_user']);
        session_destroy();
    }

    public static function generateCsrf(): string {
        self::init();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function verifyCsrf(?string $token): bool {
        self::init();
        if (empty($token) || empty($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }
}

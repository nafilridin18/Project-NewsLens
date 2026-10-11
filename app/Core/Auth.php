<?php
namespace App\Core;

use App\Core\Database;
use PDO;
use Exception;

class Auth {
    public static function init(): void {
        if (session_status() === PHP_SESSION_NONE) {
            if (!headers_sent()) {
                // Secure cookie configuration
                $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['SERVER_PORT'] ?? 80) == 443);
                session_set_cookie_params([
                    'lifetime' => 86400 * 7,
                    'path'     => '/',
                    'domain'   => '',
                    'secure'   => $isHttps,
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]);
            }
            @session_start();
        }
    }

    public static function check(): bool {
        self::init();
        return !empty($_SESSION['auth_user']);
    }

    public static function user(): ?array {
        self::init();
        return $_SESSION['auth_user'] ?? null;
    }

    public static function id(): ?int {
        $u = self::user();
        return isset($u['id']) ? (int) $u['id'] : null;
    }

    public static function role(): ?string {
        $u = self::user();
        return $u['role'] ?? null;
    }

    public static function status(): ?string {
        $u = self::user();
        return $u['status'] ?? null;
    }

    public static function permissionLevel(): ?string {
        $u = self::user();
        return $u['permission_level'] ?? null;
    }

    public static function isAdmin(): bool {
        $role = self::role();
        return in_array($role, ['super_admin', 'admin'], true);
    }

    public static function isEditor(): bool {
        $role = self::role();
        return in_array($role, ['super_admin', 'admin', 'editor'], true);
    }

    public static function isWriter(): bool {
        return self::role() === 'writer';
    }

    public static function isUser(): bool {
        return self::role() === 'user';
    }

    public static function hasRole(array $allowedRoles): bool {
        $role = self::role();
        return in_array($role, $allowedRoles, true);
    }

    /**
     * Authenticate and log in user
     * Returns array with result details
     */
    public static function attempt(string $email, string $password): array {
        self::init();
        $email = strtolower(trim($email));

        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM `users` WHERE `email` = :email LIMIT 1");
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password_hash'])) {
                // Check if account is deleted
                if (!empty($user['deleted_at'])) {
                    return ['success' => false, 'message' => 'এই অ্যাকাউন্টটি মুছে ফেলা হয়েছে।'];
                }

                // Check if suspended
                if ($user['status'] === 'suspended') {
                    return [
                        'success' => false,
                        'status'  => 'suspended',
                        'message' => 'আপনার অ্যাকাউন্টটি কর্তৃপক্ষ কর্তৃক সাময়িকভাবে স্থগিত করা হয়েছে।'
                    ];
                }

                // Update last login
                $update = $db->prepare("UPDATE `users` SET `last_login` = NOW() WHERE `id` = :id");
                $update->bindValue(':id', $user['id']);
                $update->execute();

                // Regenerate session id to prevent session fixation attacks
                session_regenerate_id(true);

                unset($user['password_hash']);
                $_SESSION['auth_user'] = $user;
                // Keep backward compatibility for existing code relying on $_SESSION['admin_user']
                $_SESSION['admin_user'] = $user;

                return [
                    'success' => true,
                    'user'    => $user,
                    'status'  => $user['status'],
                    'role'    => $user['role']
                ];
            }
        } catch (Exception $e) {
            // DB error fallback
        }

        return ['success' => false, 'message' => 'ইমেইল অথবা পাসওয়ার্ড সঠিক নয়।'];
    }

    /**
     * Simple boolean login for backward compatibility
     */
    public static function login(string $email, string $password): bool {
        $res = self::attempt($email, $password);
        return !empty($res['success']);
    }

    /**
     * Log in directly by user array (e.g. after OTP verification)
     */
    public static function loginUser(array $user): void {
        self::init();
        session_regenerate_id(true);
        unset($user['password_hash']);
        $_SESSION['auth_user'] = $user;
        $_SESSION['admin_user'] = $user;
    }

    /**
     * Refresh current user data from the database
     */
    public static function refreshUser(): void {
        self::init();
        $id = self::id();
        if (!$id) return;

        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM `users` WHERE `id` = :id LIMIT 1");
            $stmt->execute([':id' => $id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                unset($user['password_hash']);
                $_SESSION['auth_user'] = $user;
                $_SESSION['admin_user'] = $user;
            }
        } catch (Exception $e) {
            // Ignore
        }
    }

    public static function logout(): void {
        self::init();
        unset($_SESSION['auth_user'], $_SESSION['admin_user']);
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

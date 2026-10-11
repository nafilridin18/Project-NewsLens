<?php
namespace App\Core;

use App\Core\Auth;
use App\Core\Database;
use App\Core\View;
use PDO;

class Middleware {
    /**
     * Ensure user is authenticated and has one of the allowed roles
     */
    public static function requireRole(array $allowedRoles): array {
        Auth::init();

        if (!Auth::check()) {
            $config = require dirname(__DIR__, 2) . '/config/config.php';
            $adminPath = trim($config['app']['admin_path'] ?? 'lens-desk', '/');
            
            $currentUri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
            if (str_starts_with($currentUri, '/' . $adminPath)) {
                header("Location: /{$adminPath}/login");
            } else {
                header("Location: /login");
            }
            exit;
        }

        // ALWAYS refresh user data from DB so that newly approved writers or updated roles are immediately active
        Auth::refreshUser();
        $user = Auth::user();
        $role = $user['role'] ?? 'user';
        $status = $user['status'] ?? 'active';

        // Check for unverified email
        if ($status === 'unverified') {
            header("Location: /otp/verify?email=" . urlencode($user['email']));
            exit;
        }

        // Check for suspended account
        if ($status === 'suspended') {
            http_response_code(403);
            self::renderError('অ্যাকাউন্ট স্থগিত', 'আপনার অ্যাকাউন্টটি কর্তৃপক্ষ কর্তৃক সাময়িকভাবে স্থগিত করা হয়েছে। বিস্তারিত জানতে অ্যাডমিনের সাথে যোগাযোগ করুন।');
            exit;
        }

        // Check for pending writer approval
        if ($role === 'writer' && $status === 'pending') {
            $config = require dirname(__DIR__, 2) . '/config/config.php';
            $adminPath = trim($config['app']['admin_path'] ?? 'lens-desk', '/');
            
            // If writer is requesting anything other than pending screen or logout, redirect to pending screen
            $currentUri = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
            if (!str_contains($currentUri, '/pending') && !str_contains($currentUri, '/logout')) {
                header("Location: /writer/pending");
                exit;
            }
        }

        // Role verification (super_admin & admin are treated with highest privileges)
        $normalizedRoles = $allowedRoles;
        if (in_array('admin', $allowedRoles, true) && !in_array('super_admin', $allowedRoles, true)) {
            $normalizedRoles[] = 'super_admin';
        }
        if (in_array('super_admin', $allowedRoles, true) && !in_array('admin', $allowedRoles, true)) {
            $normalizedRoles[] = 'admin';
        }

        if (!in_array($role, $normalizedRoles, true)) {
            http_response_code(403);
            self::renderError('অননুমোদিত প্রবেশাধিকার (403)', 'এই পাতায় প্রবেশের জন্য আপনার অ্যাকাউন্টের প্রয়োজনীয় অনুমতি নেই।');
            exit;
        }

        return $user;
    }

    /**
     * Require Admin or Super Admin role
     */
    public static function requireAdmin(): array {
        return self::requireRole(['super_admin', 'admin']);
    }

    /**
     * Require Editor or Admin role
     */
    public static function requireEditor(): array {
        return self::requireRole(['super_admin', 'admin', 'editor']);
    }

    /**
     * Require Writer, Editor, or Admin role
     */
    public static function requireWriter(): array {
        return self::requireRole(['super_admin', 'admin', 'editor', 'writer']);
    }

    /**
     * Require Public User role or higher
     */
    public static function requireUser(): array {
        return self::requireRole(['super_admin', 'admin', 'editor', 'writer', 'user']);
    }

    /**
     * Require Any Authenticated User
     */
    public static function requireAuth(): array {
        return self::requireUser();
    }

    /**
     * Strictly verify post ownership:
     * - Admins and Editors can manage any post
     * - Writers can ONLY manage their OWN post (author_id === current user id)
     * Returns the post array if authorized, halts with 403 otherwise
     */
    public static function checkPostOwnership(int $postId): array {
        $user = self::requireWriter();
        $db = Database::getConnection();

        $stmt = $db->prepare("SELECT * FROM `posts` WHERE `id` = :id LIMIT 1");
        $stmt->execute([':id' => $postId]);
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$post) {
            http_response_code(404);
            self::renderError('সংবাদ পাওয়া যায়নি (404)', 'অনুরোধকৃত সংবাদটি সিস্টেমে বিদ্যমান নেই।');
            exit;
        }

        $role = $user['role'] ?? 'user';
        if (in_array($role, ['super_admin', 'admin', 'editor'], true)) {
            return $post; // Admins and Editors have global authority
        }

        // For Writers, strictly enforce author_id ownership
        if ((int) $post['author_id'] !== (int) $user['id']) {
            http_response_code(403);
            self::renderError('অননুমোদিত সম্পাদনা (403)', 'আপনি কেবল নিজের তৈরিকৃত সংবাদ দেখতে, সম্পাদনা বা মুছতে পারবেন।');
            exit;
        }

        return $post;
    }

    /**
     * Render a styled Bangla 403/Forbidden error page
     */
    public static function renderError(string $title, string $message): void {
        ?>
        <!DOCTYPE html>
        <html lang="bn">
        <head>
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width, initial-scale=1">
          <title><?= htmlspecialchars($title) ?> | Newslensbd</title>
          <style>
            body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #071630; color: #f8fafc; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; padding: 20px; }
            .card { background: #0b1f44; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px; padding: 40px; max-width: 500px; text-align: center; box-shadow: 0 20px 40px rgba(0,0,0,0.4); }
            h1 { color: #E31E24; font-size: 24px; margin-bottom: 12px; }
            p { color: #94a3b8; font-size: 15px; line-height: 1.6; margin-bottom: 24px; }
            .btn { background: #0E7A3A; color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-weight: 700; display: inline-block; transition: 0.2s; }
            .btn:hover { background: #0a5c2b; }
          </style>
        </head>
        <body>
          <div class="card">
            <div style="font-size: 48px; margin-bottom: 12px;">🛡️</div>
            <h1><?= htmlspecialchars($title) ?></h1>
            <p><?= htmlspecialchars($message) ?></p>
            <a href="/" class="btn">হোমপেজে ফিরে যান ➔</a>
          </div>
        </body>
        </html>
        <?php
        exit;
    }
}

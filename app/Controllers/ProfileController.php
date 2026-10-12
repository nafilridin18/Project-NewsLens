<?php
namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Core\View;
use App\Core\Middleware;
use App\Models\PostView;
use PDO;

class ProfileController {
    /**
     * Show User Profile & Analytics
     */
    public function show(): void {
        $user = Middleware::requireAuth();
        $userId = (int) $user['id'];
        $db = Database::getConnection();

        // Fresh user data
        $stmt = $db->prepare("SELECT * FROM `users` WHERE `id` = :id LIMIT 1");
        $stmt->execute([':id' => $userId]);
        $profile = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$profile) {
            header("Location: /");
            exit;
        }

        // Stats if writer/editor/admin
        $stats = [
            'total_posts'     => 0,
            'published_posts' => 0,
            'total_views'     => 0
        ];
        $dailyViews = [];
        $categoryStats = [];

        if (in_array($profile['role'], ['writer', 'editor', 'super_admin'], true)) {
            $pStmt = $db->prepare("
                SELECT 
                    COUNT(*) AS total_posts,
                    SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END) AS published_posts,
                    COALESCE(SUM(views), 0) AS total_views
                FROM `posts` 
                WHERE `author_id` = :uid
            ");
            $pStmt->execute([':uid' => $userId]);
            $stats = $pStmt->fetch(PDO::FETCH_ASSOC) ?: $stats;

            $dailyViews = PostView::getAuthorDailyViews($userId, 30);
            $categoryStats = PostView::getAuthorCategoryStats($userId);
        }

        // If public reader user, get saved bookmarks count
        $bookmarkCount = 0;
        try {
            $bmStmt = $db->prepare("SELECT COUNT(*) FROM `bookmarks` WHERE `user_id` = :uid");
            $bmStmt->execute([':uid' => $userId]);
            $bookmarkCount = (int) $bmStmt->fetchColumn();
        } catch (\Exception $e) {}

        View::render('profile.show', [
            'pageTitle'     => htmlspecialchars($profile['name']) . ' - প্রোফাইল | Newslensbd',
            'user'          => $profile,
            'stats'         => $stats,
            'dailyViews'    => $dailyViews,
            'categoryStats' => $categoryStats,
            'bookmarkCount' => $bookmarkCount,
            'success'       => $_SESSION['profile_msg'] ?? null,
            'error'         => $_SESSION['profile_err'] ?? null,
            'csrfToken'     => Auth::generateCsrf(),
            'activeTab'     => 'profile'
        ], null);
        unset($_SESSION['profile_msg'], $_SESSION['profile_err']);
    }

    /**
     * Show Edit Profile Form
     */
    public function edit(): void {
        $user = Middleware::requireAuth();
        $db = Database::getConnection();

        $stmt = $db->prepare("SELECT * FROM `users` WHERE `id` = :id LIMIT 1");
        $stmt->execute([':id' => (int)$user['id']]);
        $profile = $stmt->fetch(PDO::FETCH_ASSOC);

        View::render('profile.edit', [
            'pageTitle' => 'প্রোফাইল সম্পাদনা | Newslensbd',
            'user'      => $profile,
            'success'   => $_SESSION['profile_msg'] ?? null,
            'error'     => $_SESSION['profile_err'] ?? null,
            'csrfToken' => Auth::generateCsrf(),
            'activeTab' => 'profile'
        ], null);
        unset($_SESSION['profile_msg'], $_SESSION['profile_err']);
    }

    /**
     * Update Profile Info & Avatar
     */
    public function update(): void {
        $user = Middleware::requireAuth();
        $userId = (int) $user['id'];

        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['profile_err'] = 'নিরাপত্তা টোকেন সঠিক নয়।';
            header("Location: /profile/edit");
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $shortBio = trim($_POST['short_bio'] ?? '');
        $facebook = trim($_POST['social_facebook'] ?? '');
        $twitter = trim($_POST['social_twitter'] ?? '');
        $linkedin = trim($_POST['social_linkedin'] ?? '');

        if (empty($name)) {
            $_SESSION['profile_err'] = 'পূর্ণ নাম আবশ্যক।';
            header("Location: /profile/edit");
            exit;
        }

        $socialLinks = json_encode([
            'facebook' => $facebook,
            'twitter'  => $twitter,
            'linkedin' => $linkedin
        ], JSON_UNESCAPED_UNICODE);

        $avatarPath = $user['avatar'] ?? null;
        if (!empty($_FILES['avatar']['name'])) {
            $newAvatar = $this->handleAvatarUpload($_FILES['avatar']);
            if ($newAvatar) {
                $avatarPath = $newAvatar;
            }
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("
            UPDATE `users` SET 
                `name` = :name,
                `phone` = :phone,
                `bio` = :bio,
                `social_links` = :social,
                `avatar` = :avatar,
                `photo` = :avatar,
                `updated_at` = NOW()
            WHERE `id` = :id
        ");

        $stmt->execute([
            ':name'   => $name,
            ':phone'  => $phone,
            ':bio'    => $shortBio,
            ':social' => $socialLinks,
            ':avatar' => $avatarPath,
            ':id'     => $userId
        ]);

        // Refresh session
        if (isset($_SESSION['auth_user'])) {
            $_SESSION['auth_user']['name'] = $name;
            $_SESSION['auth_user']['avatar'] = $avatarPath;
            $_SESSION['auth_user']['photo'] = $avatarPath;
        }
        if (isset($_SESSION['admin_user'])) {
            $_SESSION['admin_user']['name'] = $name;
            $_SESSION['admin_user']['avatar'] = $avatarPath;
            $_SESSION['admin_user']['photo'] = $avatarPath;
        }
        if (isset($_SESSION['user'])) {
            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['avatar'] = $avatarPath;
            $_SESSION['user']['photo'] = $avatarPath;
        }
        Auth::refreshUser();

        $_SESSION['profile_msg'] = 'প্রোফাইল তথ্য সফলভাবে আপডেট হয়েছে!';
        header("Location: /profile");
        exit;
    }

    /**
     * Change Password
     */
    public function changePassword(): void {
        $user = Middleware::requireAuth();
        $userId = (int) $user['id'];

        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['profile_err'] = 'নিরাপত্তা টোকেন সঠিক নয়।';
            header("Location: /profile/edit");
            exit;
        }

        $currentPass = (string) ($_POST['current_password'] ?? '');
        $newPass = (string) ($_POST['new_password'] ?? '');
        $confirmPass = (string) ($_POST['confirm_password'] ?? '');

        if (strlen($newPass) < 8) {
            $_SESSION['profile_err'] = 'নতুন পাসওয়ার্ড কমপক্ষে ৮ অক্ষরের হতে হবে।';
            header("Location: /profile/edit");
            exit;
        }

        if ($newPass !== $confirmPass) {
            $_SESSION['profile_err'] = 'নতুন পাসওয়ার্ড এবং নিশ্চিতকরণ পাসওয়ার্ড মেলেনি।';
            header("Location: /profile/edit");
            exit;
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT password_hash FROM `users` WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $userId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row || !password_verify($currentPass, $row['password_hash'])) {
            $_SESSION['profile_err'] = 'বর্তমান পাসওয়ার্ড সঠিক নয়।';
            header("Location: /profile/edit");
            exit;
        }

        $newHash = password_hash($newPass, PASSWORD_BCRYPT);
        $upd = $db->prepare("UPDATE `users` SET password_hash = :p, updated_at = NOW() WHERE id = :id");
        $upd->execute([':p' => $newHash, ':id' => $userId]);

        $_SESSION['profile_msg'] = 'পাসওয়ার্ড সফলভাবে পরিবর্তন করা হয়েছে!';
        header("Location: /profile");
        exit;
    }

    private function handleAvatarUpload(array $file): ?string {
        if ($file['error'] !== UPLOAD_ERR_OK) return null;

        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $allowedTypes, true)) return null;
        if ($file['size'] > 2097152) return null; // 2MB max

        $ext = ($mime === 'image/png') ? 'png' : (($mime === 'image/webp') ? 'webp' : 'jpg');
        $fileName = 'avatar_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;

        $uploadDir = dirname(__DIR__, 2) . '/public/uploads/avatars';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0755, true);
        }

        $dest = $uploadDir . '/' . $fileName;
        if (move_uploaded_file($file['tmp_name'], $dest)) {
            return 'uploads/avatars/' . $fileName;
        }
        return null;
    }
}

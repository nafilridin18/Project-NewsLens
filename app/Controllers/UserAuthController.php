<?php
namespace App\Controllers;

use App\Core\Database;
use App\Core\Auth;
use App\Core\View;
use App\Core\Middleware;
use App\Models\OtpCode;
use App\Models\Bookmark;
use App\Models\Setting;
use App\Helpers\Mailer;
use PDO;

class UserAuthController {
    /**
     * Show Reader Registration Form
     */
    public function showRegister(): void {
        Auth::init();
        if (Auth::check()) {
            header("Location: /");
            exit;
        }

        View::render('user.register', [
            'pageTitle' => 'পাঠক অ্যাকাউন্ট তৈরি করুন | Newslensbd',
            'error'     => $_SESSION['user_reg_error'] ?? null,
            'old'       => $_SESSION['user_reg_old'] ?? [],
            'csrfToken' => Auth::generateCsrf()
        ], 'layouts/main');
        unset($_SESSION['user_reg_error'], $_SESSION['user_reg_old']);
    }

    /**
     * Process Reader Registration
     */
    public function register(): void {
        Auth::init();
        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['user_reg_error'] = 'নিরাপত্তা টোকেন সঠিক নয়।';
            header("Location: /register");
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['password_confirm'] ?? '';
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

        $_SESSION['user_reg_old'] = ['name' => $name, 'email' => $email];

        if (empty($name) || empty($email) || empty($password)) {
            $_SESSION['user_reg_error'] = 'নাম, ইমেইল এবং পাসওয়ার্ড আবশ্যক।';
            header("Location: /register");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['user_reg_error'] = 'সঠিক ইমেইল ঠিকানা দিন।';
            header("Location: /register");
            exit;
        }

        if (strlen($password) < 6) {
            $_SESSION['user_reg_error'] = 'পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে।';
            header("Location: /register");
            exit;
        }

        if ($password !== $confirm) {
            $_SESSION['user_reg_error'] = 'উভয় পাসওয়ার্ড হুবহু এক হতে হবে।';
            header("Location: /register");
            exit;
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id, status FROM `users` WHERE `email` = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            if ($existing['status'] === 'unverified') {
                $otpRes = OtpCode::createOtp($email, 'register', $ip);
                if ($otpRes['success']) {
                    Mailer::sendOtp($email, $otpRes['code'], 'register', $name);
                }
                header("Location: /otp/verify?email=" . urlencode($email) . "&purpose=register");
                exit;
            }
            $_SESSION['user_reg_error'] = 'এই ইমেইল দিয়ে ইতিপূর্বে অ্যাকাউন্ট খোলা হয়েছে। অনুগ্রহ করে লগইন করুন।';
            header("Location: /login");
            exit;
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $ins = $db->prepare("
            INSERT INTO `users` (
                `name`, `email`, `password_hash`, `role`, `status`, `created_at`
            ) VALUES (
                :name, :email, :pass, 'user', 'unverified', NOW()
            )
        ");
        $ins->execute([
            ':name'  => $name,
            ':email' => $email,
            ':pass'  => $passwordHash
        ]);

        $otpRes = OtpCode::createOtp($email, 'register', $ip);
        if (!$otpRes['success']) {
            $_SESSION['user_reg_error'] = $otpRes['message'];
            header("Location: /register");
            exit;
        }

        Mailer::sendOtp($email, $otpRes['code'], 'register', $name);
        header("Location: /otp/verify?email=" . urlencode($email) . "&purpose=register");
        exit;
    }

    /**
     * Show Reader Login Form
     */
    public function showLogin(): void {
        Auth::init();
        if (Auth::check()) {
            header("Location: /");
            exit;
        }

        View::render('user.login', [
            'pageTitle' => 'পাঠক লগইন | Newslensbd',
            'error'     => $_SESSION['user_login_error'] ?? null,
            'success'   => $_SESSION['user_login_success'] ?? null,
            'csrfToken' => Auth::generateCsrf()
        ], 'layouts/main');
        unset($_SESSION['user_login_error'], $_SESSION['user_login_success']);
    }

    /**
     * Process Reader Login
     */
    public function login(): void {
        Auth::init();
        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['user_login_error'] = 'নিরাপত্তা টোকেন সঠিক নয়।';
            header("Location: /login");
            exit;
        }

        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['user_login_error'] = 'ইমেইল এবং পাসওয়ার্ড আবশ্যক।';
            header("Location: /login");
            exit;
        }

        $res = Auth::attempt($email, $password);

        if (!$res['success']) {
            $_SESSION['user_login_error'] = $res['message'];
            header("Location: /login");
            exit;
        }

        $user = $res['user'];
        if ($user['status'] === 'unverified') {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
            $otpRes = OtpCode::createOtp($email, 'register', $ip);
            if ($otpRes['success']) {
                Mailer::sendOtp($email, $otpRes['code'], 'register', $user['name']);
            }
            header("Location: /otp/verify?email=" . urlencode($email) . "&purpose=register");
            exit;
        }

        if ($user['role'] === 'writer') {
            if ($user['status'] === 'pending') {
                header("Location: /writer/pending");
                exit;
            }
            header("Location: /writer/dashboard");
            exit;
        }

        if (in_array($user['role'], ['super_admin', 'editor'], true)) {
            $config = require CONFIG_PATH . '/config.php';
            $adminPath = $config['app']['admin_path'] ?? 'lens-desk';
            header("Location: /{$adminPath}");
            exit;
        }

        // Reader user
        header("Location: /");
        exit;
    }

    /**
     * User Logout
     */
    public function logout(): void {
        Auth::logout();
        header("Location: /");
        exit;
    }

    /**
     * Reader Bookmarks List
     */
    public function bookmarks(): void {
        $user = Middleware::requireAuth();
        $bookmarks = Bookmark::getUserBookmarks((int)$user['id']);

        View::render('user.bookmarks', [
            'pageTitle' => 'আমার সংরক্ষিত সংবাদ (Bookmarks) | Newslensbd',
            'bookmarks' => $bookmarks,
            'user'      => $user
        ], 'layouts/main');
    }

    /**
     * AJAX Bookmark Toggle Endpoint
     */
    public function toggleBookmark(): void {
        Auth::init();
        header('Content-Type: application/json; charset=utf-8');

        if (!Auth::check()) {
            http_response_code(401);
            echo json_encode([
                'success'       => false,
                'require_login' => true,
                'message'       => 'সংবাদটি বুকমার্ক করতে অনুগ্রহ করে প্রথমে লগইন করুন।'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $user = Auth::user();
        $postId = (int) ($_POST['post_id'] ?? 0);

        if ($postId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'অবৈধ পোস্ট আইডি।'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $isBookmarked = Bookmark::toggle((int)$user['id'], $postId);

        echo json_encode([
            'success'    => true,
            'bookmarked' => $isBookmarked,
            'message'    => $isBookmarked ? 'সংবাদটি বুকমার্কে সংরক্ষিত হয়েছে!' : 'সংবাদটি বুকমার্ক থেকে সরানো হয়েছে।'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

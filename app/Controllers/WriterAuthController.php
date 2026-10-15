<?php
namespace App\Controllers;

use App\Core\Database;
use App\Core\Auth;
use App\Core\View;
use App\Helpers\RateLimiter;
use PDO;

class WriterAuthController {
    /**
     * Show Dedicated Writer Login Page (/writer/login)
     */
    public function showLogin(): void {
        Auth::init();

        // If already logged in as active writer, redirect to writer dashboard
        if (Auth::check()) {
            $user = Auth::user();
            if ($user['role'] === 'writer') {
                if ($user['status'] === 'active') {
                    header("Location: /writer/dashboard");
                    exit;
                } elseif ($user['status'] === 'pending') {
                    header("Location: /writer/pending");
                    exit;
                }
            }
        }

        $error = $_SESSION['writer_login_error'] ?? null;
        $info = $_SESSION['writer_login_info'] ?? null;
        $success = $_SESSION['writer_login_success'] ?? null;
        $prefillEmail = trim($_GET['email'] ?? '');

        unset($_SESSION['writer_login_error'], $_SESSION['writer_login_info'], $_SESSION['writer_login_success']);

        View::render('writer.login', [
            'pageTitle'    => 'লেখক লগইন পোর্টাল | Newslensbd Writer Portal',
            'error'        => $error,
            'info'         => $info,
            'success'      => $success,
            'prefillEmail' => $prefillEmail,
            'csrfToken'    => Auth::generateCsrf()
        ], null);
    }

    /**
     * Process Writer Login (/writer/login)
     * Strictly restricted to accounts with role='writer'.
     */
    public function login(): void {
        Auth::init();
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $rateLimitKey = 'writer_login_' . md5($ip);

        // 1. Rate Limiting Check (Max 5 attempts in 15 mins)
        if (RateLimiter::tooManyAttempts($rateLimitKey, 5, 900)) {
            $secondsLeft = RateLimiter::availableIn($rateLimitKey);
            $minutesLeft = ceil($secondsLeft / 60);
            $_SESSION['writer_login_error'] = "অতিরিক্ত ভুল চেষ্টার কারণে লগইন সাময়িকভাবে লক করা হয়েছে। অনুগ্রহ করে {$minutesLeft} মিনিট পর পুনরায় চেষ্টা করুন।";
            header("Location: /writer/login");
            exit;
        }

        // 2. CSRF Token Validation
        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['writer_login_error'] = 'নিরাপত্তা টোকেন সঠিক নয়। অনুগ্রহ করে পুনরায় চেষ্টা করুন।';
            header("Location: /writer/login");
            exit;
        }

        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['writer_login_error'] = 'ইমেইল এবং পাসওয়ার্ড আবশ্যক।';
            header("Location: /writer/login");
            exit;
        }

        // 3. Attempt Authentication
        $res = Auth::attempt($email, $password);

        if (!$res['success']) {
            RateLimiter::hit($rateLimitKey, 900);
            $_SESSION['writer_login_error'] = 'ইমেইল অথবা পাসওয়ার্ড সঠিক নয়।';
            header("Location: /writer/login?email=" . urlencode($email));
            exit;
        }

        $user = $res['user'];

        // 4. Role Guard: ONLY role === 'writer' is permitted here.
        // If a normal reader or admin attempts to login here, return generic invalid credentials error.
        if (($user['role'] ?? '') !== 'writer') {
            Auth::logout();
            RateLimiter::hit($rateLimitKey, 900);
            $_SESSION['writer_login_error'] = 'ইমেইল অথবা পাসওয়ার্ড সঠিক নয়।';
            header("Location: /writer/login?email=" . urlencode($email));
            exit;
        }

        // 5. Status Guards for Writers
        if (!empty($user['deleted_at']) || $user['status'] === 'removed') {
            Auth::logout();
            $_SESSION['writer_login_error'] = 'এই লেখক অ্যাকাউন্টটি মুছে ফেলা হয়েছে।';
            header("Location: /writer/login");
            exit;
        }

        if ($user['status'] === 'suspended' || $user['status'] === 'banned') {
            Auth::logout();
            $_SESSION['writer_login_error'] = 'আপনার লেখক অ্যাকাউন্টটি কর্তৃপক্ষ কর্তৃক সাময়িকভাবে স্থগিত করা হয়েছে।';
            header("Location: /writer/login");
            exit;
        }

        if ($user['status'] === 'pending') {
            header("Location: /writer/pending");
            exit;
        }

        // 6. Successful login: Clear rate limiter and set session
        RateLimiter::clear($rateLimitKey);
        session_regenerate_id(true);

        $_SESSION['writer_user'] = $user;
        $_SESSION['auth_user'] = $user;

        header("Location: /writer/dashboard");
        exit;
    }

    /**
     * Dedicated Writer Logout
     */
    public function logout(): void {
        Auth::init();
        unset($_SESSION['writer_user'], $_SESSION['auth_user']);
        Auth::logout();
        header("Location: /writer/login?logged_out=1");
        exit;
    }

    /**
     * Public Writer Registration is Disabled
     * Redirects visitors to writer login with an informative message.
     */
    public function showRegister(): void {
        Auth::init();
        $_SESSION['writer_login_info'] = 'পাবলিক লেখক নিবন্ধন সেবা বন্ধ রয়েছে। লেখক অ্যাকাউন্ট শুধুমাত্র অ্যাডমিন কর্তৃপক্ষ তৈরি করেন।';
        header("Location: /writer/login");
        exit;
    }

    /**
     * Reject any direct POST calls to /writer/register
     */
    public function register(): void {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'message' => 'পাবলিক লেখক নিবন্ধন নিষিদ্ধ। লেখক অ্যাকাউন্ট কেবল অ্যাডমিন প্যানেল থেকে তৈরি করা যাবে।'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Show Pending Writer Approval Screen (Legacy or review)
     */
    public function showPending(): void {
        Auth::init();
        if (!Auth::check()) {
            header("Location: /writer/login");
            exit;
        }

        Auth::refreshUser();
        $user = Auth::user();

        if ($user['role'] === 'writer' && $user['status'] === 'active') {
            header("Location: /writer/dashboard");
            exit;
        }

        View::render('writer.pending', [
            'pageTitle' => 'লেখক আবেদন পর্যালোচনায় রয়েছে | Newslensbd',
            'user'      => $user
        ]);
    }
}

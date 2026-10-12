<?php
namespace App\Controllers;

use App\Core\Database;
use App\Core\Auth;
use App\Core\View;
use App\Models\OtpCode;
use App\Helpers\Mailer;
use PDO;

class PasswordResetController {
    /**
     * Show Forgot Password Request Form
     */
    public function showForgot(): void {
        Auth::init();
        View::render('auth.forgot_password', [
            'pageTitle' => 'পাসওয়ার্ড পুনরুদ্ধার | Newslensbd',
            'error'     => $_SESSION['forgot_error'] ?? null,
            'success'   => $_SESSION['forgot_success'] ?? null,
            'csrfToken' => Auth::generateCsrf()
        ]);
        unset($_SESSION['forgot_error'], $_SESSION['forgot_success']);
    }

    /**
     * Handle Forgot Password Email Submission
     */
    public function sendResetOtp(): void {
        Auth::init();
        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['forgot_error'] = 'নিরাপত্তা টোকেন সঠিক নয়।';
            header("Location: /password/forgot");
            exit;
        }

        $email = strtolower(trim($_POST['email'] ?? ''));
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['forgot_error'] = 'সঠিক ইমেইল ঠিকানা দিন।';
            header("Location: /password/forgot");
            exit;
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT `id`, `name`, `status` FROM `users` WHERE `email` = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Security rule from requirements: Do not reveal whether an email exists in forgot password responses
        if ($user && $user['status'] !== 'suspended') {
            $otpRes = OtpCode::createOtp($email, 'forgot_password', $ip);
            if ($otpRes['success']) {
                Mailer::sendOtp($email, $otpRes['code'], 'forgot_password', $user['name'] ?? 'ব্যবহারকারী');
            }
        }

        // Always redirect to OTP verify page with generic message
        $_SESSION['otp_success'] = 'যদি এই ইমেইলটি আমাদের সিস্টেমে নিবন্ধিত থাকে, তবে একটি ওটিপি কোড পাঠানো হয়েছে।';
        header("Location: /otp/verify?email=" . urlencode($email) . "&purpose=forgot_password");
        exit;
    }

    /**
     * Show Reset Password Form with Token Validation
     */
    public function showReset(): void {
        Auth::init();
        $token = trim($_GET['token'] ?? '');
        $email = strtolower(trim($_GET['email'] ?? ''));

        if (empty($token) || empty($email)) {
            header("Location: /password/forgot");
            exit;
        }

        // Verify token
        $db = Database::getConnection();
        $tokenHash = hash('sha256', $token);
        $stmt = $db->prepare("
            SELECT * FROM `password_resets` 
            WHERE `email` = :email AND `token_hash` = :th AND `used_at` IS NULL AND `expires_at` > NOW()
            ORDER BY `id` DESC LIMIT 1
        ");
        $stmt->execute([':email' => $email, ':th' => $tokenHash]);
        if (!$stmt->fetch()) {
            $_SESSION['forgot_error'] = 'রিসেট টোকেনের মেয়াদ শেষ হয়ে গেছে বা এটি অবৈধ। অনুগ্রহ করে পুনরায় অনুরোধ করুন।';
            header("Location: /password/forgot");
            exit;
        }

        View::render('auth.reset_password', [
            'pageTitle' => 'নতুন পাসওয়ার্ড নির্ধারণ | Newslensbd',
            'token'     => $token,
            'email'     => $email,
            'error'     => $_SESSION['reset_error'] ?? null,
            'csrfToken' => Auth::generateCsrf()
        ]);
        unset($_SESSION['reset_error']);
    }

    /**
     * Process Reset Password
     */
    public function reset(): void {
        Auth::init();
        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['reset_error'] = 'নিরাপত্তা টোকেন সঠিক নয়।';
            header("Location: /password/forgot");
            exit;
        }

        $token = trim($_POST['token'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['password_confirm'] ?? '';

        if (strlen($password) < 6) {
            $_SESSION['reset_error'] = 'পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে।';
            header("Location: /password/reset?token=" . urlencode($token) . "&email=" . urlencode($email));
            exit;
        }

        if ($password !== $confirm) {
            $_SESSION['reset_error'] = 'উভয় পাসওয়ার্ড হুবহু এক হতে হবে।';
            header("Location: /password/reset?token=" . urlencode($token) . "&email=" . urlencode($email));
            exit;
        }

        // Verify token in DB
        $db = Database::getConnection();
        $tokenHash = hash('sha256', $token);
        $stmt = $db->prepare("
            SELECT `id` FROM `password_resets` 
            WHERE `email` = :email AND `token_hash` = :th AND `used_at` IS NULL AND `expires_at` > NOW()
            ORDER BY `id` DESC LIMIT 1
        ");
        $stmt->execute([':email' => $email, ':th' => $tokenHash]);
        $resetRow = $stmt->fetch();

        if (!$resetRow) {
            $_SESSION['forgot_error'] = 'টোকেনটি অবৈধ বা মেয়াদোত্তীর্ণ।';
            header("Location: /password/forgot");
            exit;
        }

        // Update user password and invalidate token
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $up = $db->prepare("UPDATE `users` SET `password_hash` = :p WHERE `email` = :e");
        $up->execute([':p' => $passwordHash, ':e' => $email]);

        $db->prepare("UPDATE `password_resets` SET `used_at` = NOW() WHERE `id` = :id")->execute([':id' => $resetRow['id']]);

        // Invalidate all active sessions for this account by destroying current session
        session_destroy();

        $uStmt = $db->prepare("SELECT role FROM `users` WHERE `email` = :e LIMIT 1");
        $uStmt->execute([':e' => $email]);
        $userRole = $uStmt->fetchColumn() ?: 'user';

        if (in_array($userRole, ['super_admin', 'admin', 'editor'], true)) {
            header("Location: /{$adminPath}/login?msg=password_reset_success");
        } else {
            header("Location: /login?msg=password_reset_success");
        }
        exit;
    }

    /**
     * Alias for router compatibility
     */
    public function resetPassword(): void {
        $this->reset();
    }
}

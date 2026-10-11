<?php
namespace App\Controllers;

use App\Core\Database;
use App\Core\Auth;
use App\Core\View;
use App\Models\OtpCode;
use App\Helpers\Mailer;
use PDO;

class OtpController {
    /**
     * Show OTP Verification Page
     */
    public function showVerify(): void {
        Auth::init();
        $email = strtolower(trim($_GET['email'] ?? ''));
        $purpose = trim($_GET['purpose'] ?? 'register');

        if (empty($email)) {
            header("Location: /");
            exit;
        }

        View::render('auth.otp_verify', [
            'pageTitle' => 'ইমেইল ওটিপি যাচাই | Newslensbd',
            'email'     => $email,
            'purpose'   => $purpose,
            'error'     => $_SESSION['otp_error'] ?? null,
            'success'   => $_SESSION['otp_success'] ?? null,
            'csrfToken' => Auth::generateCsrf()
        ]);
        unset($_SESSION['otp_error'], $_SESSION['otp_success']);
    }

    /**
     * Handle OTP Verification Submission
     */
    public function verify(): void {
        Auth::init();
        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['otp_error'] = 'নিরাপত্তা টোকেন সঠিক নয়। অনুগ্রহ করে পুনরায় চেষ্টা করুন।';
            header("Location: /otp/verify?email=" . urlencode($_POST['email'] ?? '') . "&purpose=" . urlencode($_POST['purpose'] ?? 'register'));
            exit;
        }

        $email = strtolower(trim($_POST['email'] ?? ''));
        $purpose = trim($_POST['purpose'] ?? 'register');
        $code = trim($_POST['code'] ?? '');

        if (empty($email) || empty($code)) {
            $_SESSION['otp_error'] = 'অনুগ্রহ করে ৬-সংখ্যার ওটিপি কোডটি লিখুন।';
            header("Location: /otp/verify?email=" . urlencode($email) . "&purpose=" . urlencode($purpose));
            exit;
        }

        $result = OtpCode::verifyOtp($email, $purpose, $code);

        if (!$result['success']) {
            $_SESSION['otp_error'] = $result['message'];
            header("Location: /otp/verify?email=" . urlencode($email) . "&purpose=" . urlencode($purpose));
            exit;
        }

        $db = Database::getConnection();

        // 1. If Registration flow
        if ($purpose === 'register') {
            $stmt = $db->prepare("SELECT * FROM `users` WHERE `email` = :email LIMIT 1");
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                $_SESSION['otp_error'] = 'ব্যবহারকারী পাওয়া যায়নি।';
                header("Location: /");
                exit;
            }

            // If writer, status becomes 'pending'. If reader (user), status becomes 'active'
            $newStatus = ($user['role'] === 'writer') ? 'pending' : 'active';
            $update = $db->prepare("
                UPDATE `users` 
                SET `status` = :st, `email_verified_at` = NOW() 
                WHERE `id` = :id
            ");
            $update->execute([':st' => $newStatus, ':id' => $user['id']]);

            // Re-fetch updated user and login
            $user['status'] = $newStatus;
            $user['email_verified_at'] = date('Y-m-d H:i:s');
            Auth::loginUser($user);

            if ($user['role'] === 'writer') {
                header("Location: /writer/pending");
                exit;
            } else {
                header("Location: /?msg=welcome");
                exit;
            }
        }

        // 2. If Forgot Password flow
        if ($purpose === 'forgot_password') {
            // Generate a secure, one-time reset token valid for 15 minutes
            $resetToken = bin2hex(random_bytes(32));
            $tokenHash = hash('sha256', $resetToken);
            $expiresAt = date('Y-m-d H:i:s', strtotime('+15 minutes'));

            $ins = $db->prepare("
                INSERT INTO `password_resets` (`email`, `token_hash`, `expires_at`, `created_at`)
                VALUES (:email, :token_hash, :expires_at, NOW())
            ");
            $ins->execute([
                ':email'      => $email,
                ':token_hash' => $tokenHash,
                ':expires_at' => $expiresAt
            ]);

            header("Location: /password/reset?token=" . urlencode($resetToken) . "&email=" . urlencode($email));
            exit;
        }

        header("Location: /");
        exit;
    }

    /**
     * AJAX/POST Resend OTP Code
     */
    public function resend(): void {
        header('Content-Type: application/json; charset=utf-8');
        Auth::init();

        $email = strtolower(trim($_POST['email'] ?? ''));
        $purpose = trim($_POST['purpose'] ?? 'register');
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

        if (empty($email)) {
            echo json_encode(['success' => false, 'message' => 'ইমেইল দেওয়া হয়নি।'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $res = OtpCode::createOtp($email, $purpose, $ip);
        if (!$res['success']) {
            echo json_encode($res, JSON_UNESCAPED_UNICODE);
            return;
        }

        // Send OTP email
        $code = $res['code'];
        Mailer::sendOtp($email, $code, $purpose);

        echo json_encode([
            'success'      => true,
            'message'      => 'নতুন ওটিপি কোড পাঠানো হয়েছে। আপনার ইনবক্স বা স্প্যাম ফোল্ডার চেক করুন।',
            'wait_seconds' => OtpCode::RESEND_COOLDOWN_SECONDS
        ], JSON_UNESCAPED_UNICODE);
    }
}

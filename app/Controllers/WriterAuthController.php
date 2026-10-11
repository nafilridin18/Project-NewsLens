<?php
namespace App\Controllers;

use App\Core\Database;
use App\Core\Auth;
use App\Core\View;
use App\Models\OtpCode;
use App\Helpers\Mailer;
use PDO;

class WriterAuthController {
    /**
     * Show Writer Registration Page
     */
    public function showRegister(): void {
        Auth::init();
        if (Auth::check()) {
            $user = Auth::user();
            if ($user['role'] === 'writer' && $user['status'] === 'pending') {
                header("Location: /writer/pending");
                exit;
            }
        }

        View::render('writer.register', [
            'pageTitle' => 'লেখক হিসেবে যোগদান করুন | Newslensbd',
            'error'     => $_SESSION['writer_reg_error'] ?? null,
            'old'       => $_SESSION['writer_reg_old'] ?? [],
            'csrfToken' => Auth::generateCsrf()
        ]);
        unset($_SESSION['writer_reg_error'], $_SESSION['writer_reg_old']);
    }

    /**
     * Process Writer Registration
     */
    public function register(): void {
        Auth::init();
        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['writer_reg_error'] = 'নিরাপত্তা টোকেন সঠিক নয়।';
            header("Location: /writer/register");
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['password_confirm'] ?? '';
        $bio = trim($_POST['bio'] ?? '');
        $reason = trim($_POST['writer_reason'] ?? '');
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

        $_SESSION['writer_reg_old'] = [
            'name'          => $name,
            'email'         => $email,
            'phone'         => $phone,
            'bio'           => $bio,
            'writer_reason' => $reason
        ];

        // Validations
        if (empty($name) || empty($email) || empty($password)) {
            $_SESSION['writer_reg_error'] = 'নাম, ইমেইল এবং পাসওয়ার্ড আবশ্যক।';
            header("Location: /writer/register");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['writer_reg_error'] = 'সঠিক ইমেইল ঠিকানা দিন।';
            header("Location: /writer/register");
            exit;
        }

        if (strlen($password) < 6) {
            $_SESSION['writer_reg_error'] = 'পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে।';
            header("Location: /writer/register");
            exit;
        }

        if ($password !== $confirm) {
            $_SESSION['writer_reg_error'] = 'উভয় পাসওয়ার্ড হুবহু এক হতে হবে।';
            header("Location: /writer/register");
            exit;
        }

        $db = Database::getConnection();

        // Check if email already registered
        $stmt = $db->prepare("SELECT `id`, `status` FROM `users` WHERE `email` = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $existing = $stmt->fetch();

        if ($existing) {
            if ($existing['status'] === 'unverified') {
                // If unverified, let them verify OTP directly
                $otpRes = OtpCode::createOtp($email, 'register', $ip);
                if ($otpRes['success']) {
                    Mailer::sendOtp($email, $otpRes['code'], 'register', $name);
                }
                header("Location: /otp/verify?email=" . urlencode($email) . "&purpose=register");
                exit;
            }

            $_SESSION['writer_reg_error'] = 'এই ইমেইলটি দিয়ে ইতোমধ্যে একটি অ্যাকাউন্ট নিবন্ধিত রয়েছে।';
            header("Location: /writer/register");
            exit;
        }

        // Create user with role='writer' and status='unverified'
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $insert = $db->prepare("
            INSERT INTO `users` (
                `name`, `email`, `phone`, `password_hash`, `role`, 
                `bio`, `writer_reason`, `status`, `created_at`
            ) VALUES (
                :name, :email, :phone, :password_hash, 'writer',
                :bio, :reason, 'unverified', NOW()
            )
        ");

        $insert->execute([
            ':name'          => $name,
            ':email'         => $email,
            ':phone'         => !empty($phone) ? $phone : null,
            ':password_hash' => $passwordHash,
            ':bio'           => !empty($bio) ? $bio : null,
            ':reason'        => !empty($reason) ? $reason : null,
        ]);

        // Generate OTP and send email
        $otpRes = OtpCode::createOtp($email, 'register', $ip);
        if ($otpRes['success']) {
            Mailer::sendOtp($email, $otpRes['code'], 'register', $name);
        }

        unset($_SESSION['writer_reg_old']);
        header("Location: /otp/verify?email=" . urlencode($email) . "&purpose=register");
        exit;
    }

    /**
     * Show Pending Writer Approval Screen
     */
    public function showPending(): void {
        Auth::init();
        if (!Auth::check()) {
            $config = require dirname(__DIR__, 2) . '/config/config.php';
            $adminPath = trim($config['app']['admin_path'] ?? 'lens-desk', '/');
            header("Location: /{$adminPath}/login");
            exit;
        }

        // Always refresh user from database in case admin approved them recently
        Auth::refreshUser();
        $user = Auth::user();

        // If approved and active, redirect to writer dashboard!
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

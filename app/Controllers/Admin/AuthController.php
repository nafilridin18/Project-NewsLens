<?php
namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Auth;
use App\Core\Database;
use Exception;

class AuthController {
    public function login(): void {
        $config = require CONFIG_PATH . '/config.php';
        $adminPath = $config['app']['admin_path'] ?? 'lens-desk';
        $appUrl = rtrim($config['app']['url'] ?? '', '/');

        if (Auth::check()) {
            header("Location: {$appUrl}/{$adminPath}");
            exit;
        }

        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $csrf = $_POST['csrf_token'] ?? '';

            if (!Auth::verifyCsrf($csrf)) {
                $error = 'নিরাপত্তা যাচাই ব্যর্থ হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।';
            } elseif (Auth::login($email, $password)) {
                header("Location: {$appUrl}/{$adminPath}");
                exit;
            } else {
                $error = 'ইমেইল বা পাসওয়ার্ড সঠিক নয়।';
            }
        }

        View::render('admin.login', [
            'error'     => $error,
            'csrfToken' => Auth::generateCsrf(),
            'adminPath' => $adminPath,
            'appUrl'    => $appUrl
        ], null);
    }

    public function register(): void {
        $config = require CONFIG_PATH . '/config.php';
        $adminPath = $config['app']['admin_path'] ?? 'lens-desk';
        $appUrl = rtrim($config['app']['url'] ?? '', '/');

        if (Auth::check()) {
            header("Location: {$appUrl}/{$adminPath}");
            exit;
        }

        $error = null;
        $formData = [
            'name'  => '',
            'phone' => '',
            'email' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $csrf = $_POST['csrf_token'] ?? '';
            $name = trim($_POST['name'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $email = strtolower(trim($_POST['email'] ?? ''));
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';

            $formData = [
                'name'  => $name,
                'phone' => $phone,
                'email' => $email
            ];

            if (!Auth::verifyCsrf($csrf)) {
                $error = 'নিরাপত্তা যাচাই ব্যর্থ হয়েছে। অনুগ্রহ করে আবার চেষ্টা করুন।';
            } elseif (empty($name) || empty($phone) || empty($email) || empty($password)) {
                $error = 'সকল প্রয়োজনীয় তথ্য (নাম, ফোন, ইমেইল ও পাসওয়ার্ড) পূরণ করুন।';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'সঠিক ইমেইল ঠিকানা প্রদান করুন।';
            } elseif (strlen($password) < 6) {
                $error = 'পাসওয়ার্ড ন্যূনতম ৬ অক্ষরের হতে হবে।';
            } elseif ($password !== $passwordConfirm) {
                $error = 'পাসওয়ার্ড এবং কনফার্ম পাসওয়ার্ড মিলছে না।';
            } else {
                try {
                    $db = Database::getConnection();
                    // Check if email already exists
                    $stmtCheck = $db->prepare("SELECT id FROM `users` WHERE `email` = :email LIMIT 1");
                    $stmtCheck->execute([':email' => $email]);
                    if ($stmtCheck->fetch()) {
                        $error = 'এই ইমেইল ঠিকানা দিয়ে ইতোমধ্যে একটি অ্যাকাউন্ট খোলা আছে।';
                    } else {
                        // Check user count to determine initial role
                        $userCount = (int) $db->query("SELECT COUNT(*) FROM `users`")->fetchColumn();
                        $role = $userCount === 0 ? 'super_admin' : 'editor';

                        $stmtInsert = $db->prepare("
                            INSERT INTO `users` (`name`, `email`, `phone`, `password_hash`, `role`, `status`, `created_at`)
                            VALUES (:name, :email, :phone, :hash, :role, 'active', NOW())
                        ");
                        $stmtInsert->execute([
                            ':name'  => $name,
                            ':email' => $email,
                            ':phone' => $phone,
                            ':hash'  => password_hash($password, PASSWORD_DEFAULT),
                            ':role'  => $role
                        ]);

                        // Redirect to login with success message
                        header("Location: {$appUrl}/{$adminPath}/login?msg=registered");
                        exit;
                    }
                } catch (Exception $e) {
                    $error = 'রেজিস্ট্রেশনে ত্রুটি হয়েছে: ' . $e->getMessage();
                }
            }
        }

        View::render('admin.register', [
            'error'     => $error,
            'formData'  => $formData,
            'csrfToken' => Auth::generateCsrf(),
            'adminPath' => $adminPath,
            'appUrl'    => $appUrl
        ], null);
    }

    public function logout(): void {
        $config = require CONFIG_PATH . '/config.php';
        $adminPath = $config['app']['admin_path'] ?? 'lens-desk';
        $appUrl = rtrim($config['app']['url'] ?? '', '/');

        Auth::logout();
        header("Location: {$appUrl}/{$adminPath}/login");
        exit;
    }

    public function updateProfile(): void {
        $config = require CONFIG_PATH . '/config.php';
        $adminPath = $config['app']['admin_path'] ?? 'lens-desk';
        $appUrl = rtrim($config['app']['url'] ?? '', '/');

        if (!Auth::check()) {
            header("Location: {$appUrl}/{$adminPath}/login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: {$appUrl}/{$adminPath}/settings");
            exit;
        }

        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die('CSRF validation failed');
        }

        $user = Auth::user();
        $userId = (int) $user['id'];
        $newName = trim($_POST['admin_name'] ?? '');
        $newPhone = trim($_POST['admin_phone'] ?? '');
        $newPassword = $_POST['admin_password'] ?? '';
        $newPasswordConfirm = $_POST['admin_password_confirm'] ?? '';

        $redirectTo = !empty($_POST['redirect_to']) ? $_POST['redirect_to'] : "{$appUrl}/{$adminPath}/settings";
        $sep = strpos($redirectTo, '?') !== false ? '&' : '?';

        if (!empty($newName)) {
            try {
                $db = Database::getConnection();

                if (!empty($newPassword)) {
                    if (strlen($newPassword) < 6) {
                        header("Location: {$redirectTo}{$sep}err=pwd_short");
                        exit;
                    }
                    if ($newPassword !== $newPasswordConfirm) {
                        header("Location: {$redirectTo}{$sep}err=pwd_mismatch");
                        exit;
                    }
                    $stmt = $db->prepare("UPDATE `users` SET `name` = :name, `phone` = :phone, `password_hash` = :hash WHERE `id` = :id");
                    $stmt->execute([
                        ':name'  => $newName,
                        ':phone' => $newPhone,
                        ':hash'  => password_hash($newPassword, PASSWORD_DEFAULT),
                        ':id'    => $userId
                    ]);
                } else {
                    $stmt = $db->prepare("UPDATE `users` SET `name` = :name, `phone` = :phone WHERE `id` = :id");
                    $stmt->execute([
                        ':name'  => $newName,
                        ':phone' => $newPhone,
                        ':id'    => $userId
                    ]);
                }

                // Update Session immediately so header displays the new name
                $_SESSION['admin_user']['name'] = $newName;
                $_SESSION['admin_user']['phone'] = $newPhone;

                header("Location: {$redirectTo}{$sep}msg=profile_updated");
                exit;
            } catch (Exception $e) {}
        }

        header("Location: {$redirectTo}");
        exit;
    }
}

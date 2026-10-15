<?php
namespace App\Controllers\Admin;

use App\Core\Database;
use App\Core\Auth;
use App\Core\View;
use App\Core\Middleware;
use App\Models\AuditLog;
use App\Helpers\Mailer;
use PDO;

class WriterControlController {
    /**
     * List all writers and requests with search/filter
     */
    public function index(): void {
        Middleware::requireAdmin();
        $db = Database::getConnection();

        $statusFilter = trim($_GET['status'] ?? '');
        $permFilter = trim($_GET['permission'] ?? '');
        $search = trim($_GET['q'] ?? '');

        $sql = "
            SELECT u.*, 
                   COUNT(p.id) AS total_posts, 
                   COALESCE(SUM(p.views), 0) AS total_views
            FROM `users` u
            LEFT JOIN `posts` p ON p.author_id = u.id
            WHERE u.role = 'writer' AND u.deleted_at IS NULL
        ";
        $params = [];

        if (!empty($statusFilter)) {
            $sql .= " AND u.status = :status";
            $params[':status'] = $statusFilter;
        }

        if (!empty($permFilter)) {
            $sql .= " AND u.permission_level = :perm";
            $params[':perm'] = $permFilter;
        }

        if (!empty($search)) {
            $sql .= " AND (u.name LIKE :search OR u.email LIKE :search OR u.phone LIKE :search)";
            $params[':search'] = "%{$search}%";
        }

        $sql .= " GROUP BY u.id ORDER BY (u.status = 'pending') DESC, u.created_at DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $writers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Count pending writer requests for badge
        $pendingCountStmt = $db->query("SELECT COUNT(*) FROM `users` WHERE `role` = 'writer' AND `status` = 'pending' AND `deleted_at` IS NULL");
        $pendingCount = (int) $pendingCountStmt->fetchColumn();

        // Get list of standard users for quick promotion feature
        $readersStmt = $db->query("SELECT id, name, email, phone FROM `users` WHERE `role` = 'user' AND `deleted_at` IS NULL ORDER BY `name` ASC LIMIT 100");
        $readers = $readersStmt->fetchAll(PDO::FETCH_ASSOC);

        View::render('admin.writers_index', [
            'pageTitle'    => 'লেখক নিয়ন্ত্রণ ও ব্যবস্থাপনা | Newslensbd Admin',
            'writers'      => $writers,
            'readers'      => $readers,
            'pendingCount' => $pendingCount,
            'statusFilter' => $statusFilter,
            'permFilter'   => $permFilter,
            'search'       => $search,
            'adminPath'    => $this->adminSlug(),
            'success'      => $_SESSION['writer_action_msg'] ?? null,
            'error'        => $_SESSION['writer_action_err'] ?? null,
            'csrfToken'    => Auth::generateCsrf()
        ]);
        unset($_SESSION['writer_action_msg'], $_SESSION['writer_action_err']);
    }

    /**
     * Show writer profile details and all their articles
     */
    public function show(): void {
        Middleware::requireAdmin();
        $db = Database::getConnection();
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            header("Location: /" . $this->adminPath() . "/writers");
            exit;
        }

        $stmt = $db->prepare("SELECT * FROM `users` WHERE `id` = :id AND `role` = 'writer' LIMIT 1");
        $stmt->execute([':id' => $id]);
        $writer = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$writer) {
            header("Location: /" . $this->adminPath() . "/writers");
            exit;
        }

        // Get their articles
        $postStmt = $db->prepare("
            SELECT p.*, c.name_bn AS category_name
            FROM `posts` p
            JOIN `categories` c ON c.id = p.category_id
            WHERE p.author_id = :id
            ORDER BY p.created_at DESC
        ");
        $postStmt->execute([':id' => $id]);
        $posts = $postStmt->fetchAll(PDO::FETCH_ASSOC);

        // Stats summary
        $totalViews = array_sum(array_column($posts, 'views'));
        $publishedCount = count(array_filter($posts, fn($p) => $p['status'] === 'published'));
        $pendingCount = count(array_filter($posts, fn($p) => $p['status'] === 'pending_review' || $p['status'] === 'pending'));

        View::render('admin.writer_show', [
            'pageTitle'      => $writer['name'] . ' - লেখক তথ্য | Newslensbd Admin',
            'writer'         => $writer,
            'posts'          => $posts,
            'totalViews'     => $totalViews,
            'publishedCount' => $publishedCount,
            'pendingCount'   => $pendingCount,
            'adminPath'      => $this->adminSlug(),
            'csrfToken'      => Auth::generateCsrf()
        ]);
    }

    /**
     * Create New Writer Account (Admin only)
     */
    public function create(): void {
        $admin = Middleware::requireAdmin();
        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['writer_action_err'] = 'নিরাপত্তা টোকেন সঠিক নয়।';
            header("Location: /" . $this->adminPath() . "/writers");
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';
        $phone = trim($_POST['phone'] ?? '');
        $bio = trim($_POST['bio'] ?? '');
        $level = trim($_POST['permission_level'] ?? 'editor_review');

        if (!in_array($level, ['direct_publish', 'editor_review'], true)) {
            $level = 'editor_review';
        }

        if (empty($name) || empty($email) || empty($password)) {
            $_SESSION['writer_action_err'] = 'নাম, ইমেইল এবং পাসওয়ার্ড অবশ্যই প্রদান করতে হবে।';
            header("Location: /" . $this->adminPath() . "/writers");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['writer_action_err'] = 'সঠিক ইমেইল ঠিকানা দিন।';
            header("Location: /" . $this->adminPath() . "/writers");
            exit;
        }

        if (strlen($password) < 6) {
            $_SESSION['writer_action_err'] = 'পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে।';
            header("Location: /" . $this->adminPath() . "/writers");
            exit;
        }

        $db = Database::getConnection();

        // Check for existing user
        $stmt = $db->prepare("SELECT id, role, status FROM `users` WHERE `email` = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            if ($existing['role'] === 'writer') {
                $_SESSION['writer_action_err'] = 'এই ইমেইল দিয়ে ইতোমধ্যে একজন লেখক অ্যাকাউন্ট রয়েছে।';
            } else {
                $_SESSION['writer_action_err'] = "এই ইমেইলটি সাধারণ পাঠক হিসেবে নিবন্ধিত। 'পাঠককে লেখকে উন্নীত করুন' অপশন ব্যবহার করুন।";
            }
            header("Location: /" . $this->adminPath() . "/writers");
            exit;
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $insert = $db->prepare("
            INSERT INTO `users` (
                `name`, `email`, `phone`, `password_hash`, `role`, 
                `bio`, `permission_level`, `status`, `email_verified_at`, 
                `approved_by`, `approved_at`, `created_at`
            ) VALUES (
                :name, :email, :phone, :hash, 'writer',
                :bio, :perm, 'active', NOW(),
                :admin_id, NOW(), NOW()
            )
        ");

        $insert->execute([
            ':name'     => $name,
            ':email'    => $email,
            ':phone'    => !empty($phone) ? $phone : null,
            ':hash'     => $passwordHash,
            ':bio'      => !empty($bio) ? $bio : null,
            ':perm'     => $level,
            ':admin_id' => $admin['id']
        ]);

        $newWriterId = (int) $db->lastInsertId();

        AuditLog::log('create_writer_account', 'user', $newWriterId, [
            'name'             => $name,
            'email'            => $email,
            'permission_level' => $level,
            'created_by_admin' => $admin['id']
        ]);

        // Attempt sending credentials email
        try {
            Mailer::sendWriterStatus($email, $name, 'approved', $level);
        } catch (\Throwable $e) {}

        $_SESSION['writer_action_msg'] = "{$name}-কে সফলভাবে নতুন লেখক হিসেবে যুক্ত করা হয়েছে। (লগইন URL: /writer/login)";
        header("Location: /" . $this->adminPath() . "/writers");
        exit;
    }

    /**
     * Reset Writer Password (Admin-controlled)
     */
    public function resetPassword(): void {
        $admin = Middleware::requireAdmin();
        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['writer_action_err'] = 'নিরাপত্তা টোকেন সঠিক নয়।';
            header("Location: /" . $this->adminPath() . "/writers");
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);
        $newPassword = $_POST['new_password'] ?? '';

        if ($id <= 0 || strlen($newPassword) < 6) {
            $_SESSION['writer_action_err'] = 'নতুন পাসওয়ার্ড কমপক্ষে ৬ অক্ষরের হতে হবে।';
            header("Location: /" . $this->adminPath() . "/writers");
            exit;
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT id, name, email FROM `users` WHERE `id` = :id AND `role` = 'writer' LIMIT 1");
        $stmt->execute([':id' => $id]);
        $writer = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$writer) {
            $_SESSION['writer_action_err'] = 'লেখক পাওয়া যায়নি।';
            header("Location: /" . $this->adminPath() . "/writers");
            exit;
        }

        $hash = password_hash($newPassword, PASSWORD_BCRYPT);
        $up = $db->prepare("UPDATE `users` SET `password_hash` = :hash, `updated_at` = NOW() WHERE `id` = :id");
        $up->execute([':hash' => $hash, ':id' => $id]);

        AuditLog::log('reset_writer_password', 'user', $id, [
            'writer_email' => $writer['email'],
            'admin_id'     => $admin['id']
        ]);

        $_SESSION['writer_action_msg'] = "{$writer['name']}-এর পাসওয়ার্ড সফলভাবে রিসেট করা হয়েছে।";
        header("Location: /" . $this->adminPath() . "/writers");
        exit;
    }

    /**
     * Promote an existing normal reader (role='user') to Writer (role='writer')
     */
    public function promoteUser(): void {
        $admin = Middleware::requireAdmin();
        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['writer_action_err'] = 'নিরাপত্তা টোকেন সঠিক নয়।';
            header("Location: /" . $this->adminPath() . "/writers");
            exit;
        }

        $userId = (int) ($_POST['user_id'] ?? 0);
        $userEmail = strtolower(trim($_POST['user_email'] ?? ''));
        $level = trim($_POST['permission_level'] ?? 'editor_review');
        if (!in_array($level, ['direct_publish', 'editor_review'], true)) {
            $level = 'editor_review';
        }

        $db = Database::getConnection();

        if ($userId > 0) {
            $stmt = $db->prepare("SELECT * FROM `users` WHERE `id` = :id LIMIT 1");
            $stmt->execute([':id' => $userId]);
        } elseif (!empty($userEmail)) {
            $stmt = $db->prepare("SELECT * FROM `users` WHERE `email` = :email LIMIT 1");
            $stmt->execute([':email' => $userEmail]);
        } else {
            $_SESSION['writer_action_err'] = 'ব্যবহারকারী আইডি বা ইমেইল প্রদান করুন।';
            header("Location: /" . $this->adminPath() . "/writers");
            exit;
        }

        $targetUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$targetUser) {
            $_SESSION['writer_action_err'] = 'ব্যবহারকারী খুঁজে পাওয়া যায়নি।';
            header("Location: /" . $this->adminPath() . "/writers");
            exit;
        }

        if ($targetUser['role'] === 'writer') {
            $_SESSION['writer_action_err'] = "{$targetUser['name']} ইতোমধ্যে লেখক পদে রয়েছেন।";
            header("Location: /" . $this->adminPath() . "/writers");
            exit;
        }

        $up = $db->prepare("
            UPDATE `users` 
            SET `role` = 'writer',
                `status` = 'active',
                `permission_level` = :perm,
                `approved_by` = :admin_id,
                `approved_at` = NOW(),
                `updated_at` = NOW()
            WHERE `id` = :id
        ");

        $up->execute([
            ':perm'     => $level,
            ':admin_id' => $admin['id'],
            ':id'       => $targetUser['id']
        ]);

        AuditLog::log('promote_to_writer', 'user', $targetUser['id'], [
            'user_email'       => $targetUser['email'],
            'permission_level' => $level,
            'admin_id'         => $admin['id']
        ]);

        try {
            Mailer::sendWriterStatus($targetUser['email'], $targetUser['name'], 'approved', $level);
        } catch (\Throwable $e) {}

        $_SESSION['writer_action_msg'] = "{$targetUser['name']}-কে সফলভাবে লেখক হিসেবে অনুমোদন দেওয়া হয়েছে।";
        header("Location: /" . $this->adminPath() . "/writers");
        exit;
    }

    /**
     * Demote Writer back to User (role='user')
     */
    public function demoteWriter(): void {
        $admin = Middleware::requireAdmin();
        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['writer_action_err'] = 'নিরাপত্তা টোকেন সঠিক নয়।';
            header("Location: /" . $this->adminPath() . "/writers");
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);
        $db = Database::getConnection();

        $stmt = $db->prepare("SELECT * FROM `users` WHERE `id` = :id AND `role` = 'writer' LIMIT 1");
        $stmt->execute([':id' => $id]);
        $writer = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$writer) {
            $_SESSION['writer_action_err'] = 'লেখক পাওয়া যায়নি।';
            header("Location: /" . $this->adminPath() . "/writers");
            exit;
        }

        // Demote role to 'user', remove writer permission level
        $up = $db->prepare("
            UPDATE `users` 
            SET `role` = 'user',
                `permission_level` = NULL,
                `updated_at` = NOW()
            WHERE `id` = :id
        ");
        $up->execute([':id' => $id]);

        AuditLog::log('demote_writer_to_user', 'user', $id, [
            'writer_email' => $writer['email'],
            'admin_id'     => $admin['id']
        ]);

        $_SESSION['writer_action_msg'] = "{$writer['name']}-কে সফলভাবে সাধারণ পাঠক পদে স্থানান্তর করা হয়েছে। (পূর্বের সংবাদসমূহ সংরক্ষিত থাকবে)";
        header("Location: /" . $this->adminPath() . "/writers");
        exit;
    }

    /**
     * Approve legacy or pending writer request
     */
    public function approve(): void {
        $admin = Middleware::requireAdmin();
        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['writer_action_err'] = 'নিরাপত্তা টোকেন সঠিক নয়।';
            header("Location: /" . $this->adminPath() . "/writers");
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);
        $level = trim($_POST['permission_level'] ?? 'editor_review');
        if (!in_array($level, ['direct_publish', 'editor_review'], true)) {
            $level = 'editor_review';
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM `users` WHERE `id` = :id AND `role` = 'writer' LIMIT 1");
        $stmt->execute([':id' => $id]);
        $writer = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$writer) {
            $_SESSION['writer_action_err'] = 'লেখক পাওয়া যায়নি।';
            header("Location: /" . $this->adminPath() . "/writers");
            exit;
        }

        $up = $db->prepare("
            UPDATE `users` 
            SET `status` = 'active', 
                `permission_level` = :perm, 
                `approved_by` = :admin_id, 
                `approved_at` = NOW() 
            WHERE `id` = :id
        ");
        $up->execute([
            ':perm'     => $level,
            ':admin_id' => $admin['id'],
            ':id'       => $id
        ]);

        // Audit Log
        AuditLog::log('approve_writer', 'user', $id, [
            'writer_email'     => $writer['email'],
            'permission_level' => $level,
            'admin_id'         => $admin['id']
        ]);

        // Notify writer via email
        try {
            Mailer::sendWriterStatus($writer['email'], $writer['name'], 'approved', $level);
        } catch (\Throwable $e) {}

        $_SESSION['writer_action_msg'] = "{$writer['name']}-কে সফলভাবে অনুমোদন দেওয়া হয়েছে।";
        header("Location: /" . $this->adminPath() . "/writers");
        exit;
    }

    /**
     * Reject writer request with optional reason
     */
    public function reject(): void {
        $admin = Middleware::requireAdmin();
        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['writer_action_err'] = 'নিরাপত্তা টোকেন সঠিক নয়।';
            header("Location: /" . $this->adminPath() . "/writers");
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);
        $reason = trim($_POST['rejected_reason'] ?? '');

        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM `users` WHERE `id` = :id AND `role` = 'writer' LIMIT 1");
        $stmt->execute([':id' => $id]);
        $writer = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$writer) {
            $_SESSION['writer_action_err'] = 'লেখক পাওয়া যায়নি।';
            header("Location: /" . $this->adminPath() . "/writers");
            exit;
        }

        $up = $db->prepare("
            UPDATE `users` 
            SET `status` = 'rejected', 
                `rejected_reason` = :reason,
                `approved_by` = :admin_id,
                `approved_at` = NOW()
            WHERE `id` = :id
        ");
        $up->execute([
            ':reason'   => !empty($reason) ? $reason : null,
            ':admin_id' => $admin['id'],
            ':id'       => $id
        ]);

        AuditLog::log('reject_writer', 'user', $id, [
            'writer_email' => $writer['email'],
            'reason'       => $reason,
            'admin_id'     => $admin['id']
        ]);

        try {
            Mailer::sendWriterStatus($writer['email'], $writer['name'], 'rejected', null, $reason);
        } catch (\Throwable $e) {}

        $_SESSION['writer_action_msg'] = "{$writer['name']}-এর আবেদন প্রত্যাখ্যান করা হয়েছে।";
        header("Location: /" . $this->adminPath() . "/writers");
        exit;
    }

    /**
     * Change permission level (upgrade/downgrade)
     */
    public function updatePermission(): void {
        $admin = Middleware::requireAdmin();
        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['writer_action_err'] = 'নিরাপত্তা টোকেন সঠিক নয়।';
            header("Location: /" . $this->adminPath() . "/writers");
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);
        $level = trim($_POST['permission_level'] ?? 'editor_review');
        if (!in_array($level, ['direct_publish', 'editor_review'], true)) {
            $level = 'editor_review';
        }

        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM `users` WHERE `id` = :id AND `role` = 'writer' LIMIT 1");
        $stmt->execute([':id' => $id]);
        $writer = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$writer) {
            $_SESSION['writer_action_err'] = 'লেখক পাওয়া যায়নি।';
            header("Location: /" . $this->adminPath() . "/writers");
            exit;
        }

        $db->prepare("UPDATE `users` SET `permission_level` = :perm WHERE `id` = :id")->execute([
            ':perm' => $level,
            ':id'   => $id
        ]);

        AuditLog::log('change_writer_permission', 'user', $id, [
            'new_permission' => $level,
            'writer_email'   => $writer['email']
        ]);

        try {
            Mailer::sendWriterStatus($writer['email'], $writer['name'], 'approved', $level);
        } catch (\Throwable $e) {}

        $_SESSION['writer_action_msg'] = "{$writer['name']}-এর পারমিশন লেভেল আপডেট করা হয়েছে।";
        header("Location: /" . $this->adminPath() . "/writers");
        exit;
    }

    /**
     * Suspend / Reactivate writer
     */
    public function toggleStatus(): void {
        $admin = Middleware::requireAdmin();
        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['writer_action_err'] = 'নিরাপত্তা টোকেন সঠিক নয়।';
            header("Location: /" . $this->adminPath() . "/writers");
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);
        $action = trim($_POST['status_action'] ?? ''); // 'suspend' or 'activate'
        $reason = trim($_POST['reason'] ?? '');

        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM `users` WHERE `id` = :id AND `role` = 'writer' LIMIT 1");
        $stmt->execute([':id' => $id]);
        $writer = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$writer) {
            $_SESSION['writer_action_err'] = 'লেখক পাওয়া যায়নি।';
            header("Location: /" . $this->adminPath() . "/writers");
            exit;
        }

        if ($action === 'suspend') {
            $db->prepare("UPDATE `users` SET `status` = 'suspended', `rejected_reason` = :reason WHERE `id` = :id")->execute([
                ':reason' => !empty($reason) ? $reason : null,
                ':id'     => $id
            ]);
            AuditLog::log('suspend_writer', 'user', $id, ['reason' => $reason]);
            try {
                Mailer::sendWriterStatus($writer['email'], $writer['name'], 'suspended', null, $reason);
            } catch (\Throwable $e) {}
            $_SESSION['writer_action_msg'] = "{$writer['name']}-কে সাময়িকভাবে স্থগিত করা হয়েছে।";
        } elseif ($action === 'activate') {
            $db->prepare("UPDATE `users` SET `status` = 'active' WHERE `id` = :id")->execute([':id' => $id]);
            AuditLog::log('activate_writer', 'user', $id);
            $_SESSION['writer_action_msg'] = "{$writer['name']}-কে পুনরায় সক্রিয় করা হয়েছে।";
        }

        header("Location: /" . $this->adminPath() . "/writers");
        exit;
    }

    /**
     * Remove / Soft-delete writer
     */
    public function removeWriter(): void {
        $admin = Middleware::requireAdmin();
        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['writer_action_err'] = 'নিরাপত্তা টোকেন সঠিক নয়।';
            header("Location: /" . $this->adminPath() . "/writers");
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);
        $postsAction = trim($_POST['posts_action'] ?? 'keep'); // 'keep' or 'unpublish'

        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM `users` WHERE `id` = :id AND `role` = 'writer' LIMIT 1");
        $stmt->execute([':id' => $id]);
        $writer = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$writer) {
            $_SESSION['writer_action_err'] = 'লেখক পাওয়া যায়নি।';
            header("Location: /" . $this->adminPath() . "/writers");
            exit;
        }

        // Soft delete user
        $db->prepare("UPDATE `users` SET `deleted_at` = NOW(), `status` = 'removed' WHERE `id` = :id")->execute([':id' => $id]);

        if ($postsAction === 'unpublish') {
            $db->prepare("UPDATE `posts` SET `status` = 'draft' WHERE `author_id` = :id")->execute([':id' => $id]);
        } else {
            $db->prepare("
                UPDATE `posts` 
                SET `custom_author` = CONCAT(:name, ' (সাবেক লেখক)') 
                WHERE `author_id` = :id AND `custom_author` IS NULL
            ")->execute([':name' => $writer['name'], ':id' => $id]);
        }

        AuditLog::log('remove_writer', 'user', $id, [
            'posts_action' => $postsAction,
            'writer_name'  => $writer['name']
        ]);

        $_SESSION['writer_action_msg'] = "{$writer['name']}-কে সফলভাবে অপসারন করা হয়েছে।";
        header("Location: /" . $this->adminPath() . "/writers");
        exit;
    }

    /**
     * Delete writer permanently (Hard delete option if requested)
     */
    public function deleteWriter(): void {
        $admin = Middleware::requireAdmin();
        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['writer_action_err'] = 'নিরাপত্তা টোকেন সঠিক নয়।';
            header("Location: /" . $this->adminPath() . "/writers");
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);
        $postsAction = trim($_POST['posts_action'] ?? 'keep');

        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM `users` WHERE `id` = :id AND `role` = 'writer' LIMIT 1");
        $stmt->execute([':id' => $id]);
        $writer = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$writer) {
            $_SESSION['writer_action_err'] = 'লেখক পাওয়া যায়নি।';
            header("Location: /" . $this->adminPath() . "/writers");
            exit;
        }

        if ($postsAction === 'unpublish') {
            $db->prepare("UPDATE `posts` SET `status` = 'draft' WHERE `author_id` = :id")->execute([':id' => $id]);
        } else {
            $db->prepare("
                UPDATE `posts` 
                SET `custom_author` = CONCAT(:name, ' (সাবেক লেখক)') 
                WHERE `author_id` = :id AND `custom_author` IS NULL
            ")->execute([':name' => $writer['name'], ':id' => $id]);
        }

        // Soft delete writer
        $db->prepare("UPDATE `users` SET `deleted_at` = NOW(), `status` = 'removed' WHERE `id` = :id")->execute([':id' => $id]);

        AuditLog::log('delete_writer', 'user', $id, [
            'posts_action' => $postsAction,
            'writer_name'  => $writer['name']
        ]);

        $_SESSION['writer_action_msg'] = "{$writer['name']}-এর অ্যাকাউন্ট সফলভাবে মুছে ফেলা হয়েছে।";
        header("Location: /" . $this->adminPath() . "/writers");
        exit;
    }

    private function adminPath(): string {
        $c = require dirname(__DIR__, 3) . '/config/config.php';
        $admin = trim($c['app']['admin_path'] ?? 'lens-desk', '/');
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        $appUrl = rtrim($scriptDir, '/');
        return ltrim($appUrl . '/' . $admin, '/');
    }

    private function adminSlug(): string {
        $c = require dirname(__DIR__, 3) . '/config/config.php';
        return trim($c['app']['admin_path'] ?? 'lens-desk', '/');
    }
}

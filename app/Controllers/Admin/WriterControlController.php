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

        View::render('admin.writers_index', [
            'pageTitle'    => 'লেখক নিয়ন্ত্রণ ও অনুমোদন | Newslensbd Admin',
            'writers'      => $writers,
            'pendingCount' => $pendingCount,
            'statusFilter' => $statusFilter,
            'permFilter'   => $permFilter,
            'search'       => $search,
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
            header("Location: /lens-desk/writers");
            exit;
        }

        $stmt = $db->prepare("SELECT * FROM `users` WHERE `id` = :id AND `role` = 'writer' LIMIT 1");
        $stmt->execute([':id' => $id]);
        $writer = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$writer) {
            header("Location: /lens-desk/writers");
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
            'csrfToken'      => Auth::generateCsrf()
        ]);
    }

    /**
     * Approve writer request with direct_publish or editor_review
     */
    public function approve(): void {
        $admin = Middleware::requireAdmin();
        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['writer_action_err'] = 'নিরাপত্তা টোকেন সঠিক নয়।';
            header("Location: /lens-desk/writers");
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
            header("Location: /lens-desk/writers");
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
        Mailer::sendWriterStatus($writer['email'], $writer['name'], 'approved', $level);

        $_SESSION['writer_action_msg'] = "{$writer['name']}-কে সফলভাবে অনুমোদন দেওয়া হয়েছে।";
        header("Location: /" . $this->adminPath() . "/writers");
        exit;
    }

    private function adminPath(): string {
        $c = require dirname(__DIR__, 3) . '/config/config.php';
        return trim($c['app']['admin_path'] ?? 'lens-desk', '/');
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

        Mailer::sendWriterStatus($writer['email'], $writer['name'], 'rejected', null, $reason);

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

        Mailer::sendWriterStatus($writer['email'], $writer['name'], 'approved', $level);

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
            Mailer::sendWriterStatus($writer['email'], $writer['name'], 'suspended', null, $reason);
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
     * Remove writer (Soft delete with option for articles: unpublish or keep former author label)
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
            // Unpublish all their articles to draft
            $db->prepare("UPDATE `posts` SET `status` = 'draft' WHERE `author_id` = :id")->execute([':id' => $id]);
        } else {
            // Keep them published with custom_author label 'সাবেক লেখক (Former Writer)'
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
}

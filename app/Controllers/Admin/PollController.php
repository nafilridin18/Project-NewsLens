<?php
namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Auth;
use App\Core\Database;
use App\Models\Poll;
use PDO;
use Exception;

class PollController {
    private string $adminPath;
    private string $appUrl;

    public function __construct() {
        $config = require CONFIG_PATH . '/config.php';
        $this->adminPath = $config['app']['admin_path'] ?? 'lens-desk';
        $this->appUrl = rtrim($config['app']['url'] ?? '', '/');

        if (!Auth::check()) {
            header("Location: {$this->appUrl}/{$this->adminPath}/login");
            exit;
        }
    }

    public function index(): void {
        $user = Auth::user();
        $polls = Poll::getAllWithStats();

        View::render('admin.polls_index', [
            'pageTitle' => 'অনলাইন জরিপ ও ফলাফল | Admin',
            'user'      => $user,
            'polls'     => $polls,
            'adminPath' => $this->adminPath,
            'appUrl'    => $this->appUrl,
            'csrfToken' => Auth::generateCsrf()
        ], null);
    }

    public function store(): void {
        $user = Auth::user();
        if (!in_array($user['role'], ['super_admin', 'editor'])) {
            die('অনুমতি নেই: শুধুমাত্র সুপার অ্যাডমিন বা এডিটর জরিপ তৈরি করতে পারেন।');
        }

        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die('CSRF validation failed');
        }

        $question = trim($_POST['question'] ?? '');
        $options = $_POST['options'] ?? [];
        $isActive = !empty($_POST['is_active']) ? 1 : 0;

        if (!empty($question) && !empty($options)) {
            try {
                $db = Database::getConnection();

                if ($isActive) {
                    $db->exec("UPDATE `polls` SET `is_active` = 0");
                }

                $stmt = $db->prepare("INSERT INTO `polls` (`question`, `is_active`, `created_at`) VALUES (:q, :active, NOW())");
                $stmt->execute([':q' => $question, ':active' => $isActive]);
                $pollId = $db->lastInsertId();

                $stmtOpt = $db->prepare("INSERT INTO `poll_options` (`poll_id`, `option_text`, `votes`) VALUES (:pollId, :opt, 0)");
                foreach ($options as $opt) {
                    $optText = trim($opt);
                    if ($optText !== '') {
                        $stmtOpt->execute([':pollId' => $pollId, ':opt' => $optText]);
                    }
                }
            } catch (Exception $e) {}
        }

        header("Location: {$this->appUrl}/{$this->adminPath}/polls?msg=created");
        exit;
    }

    public function setActive(): void {
        $user = Auth::user();
        if (!in_array($user['role'], ['super_admin', 'editor'])) {
            die('অনুমতি নেই');
        }

        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die('CSRF validation failed');
        }

        $pollId = (int) ($_POST['id'] ?? 0);
        if ($pollId > 0) {
            try {
                $db = Database::getConnection();
                $db->exec("UPDATE `polls` SET `is_active` = 0");
                $stmt = $db->prepare("UPDATE `polls` SET `is_active` = 1 WHERE `id` = :id");
                $stmt->execute([':id' => $pollId]);
            } catch (Exception $e) {}
        }

        header("Location: {$this->appUrl}/{$this->adminPath}/polls?msg=activated");
        exit;
    }

    public function resetVotes(): void {
        $user = Auth::user();
        if (!in_array($user['role'], ['super_admin', 'editor'])) {
            die('অনুমতি নেই');
        }

        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die('CSRF validation failed');
        }

        $pollId = (int) ($_POST['id'] ?? 0);
        if ($pollId > 0) {
            try {
                $db = Database::getConnection();
                $stmtOpt = $db->prepare("UPDATE `poll_options` SET `votes` = 0 WHERE `poll_id` = :id");
                $stmtOpt->execute([':id' => $pollId]);

                $stmtVotes = $db->prepare("DELETE FROM `poll_votes` WHERE `poll_id` = :id");
                $stmtVotes->execute([':id' => $pollId]);
            } catch (Exception $e) {}
        }

        header("Location: {$this->appUrl}/{$this->adminPath}/polls?msg=reset");
        exit;
    }

    public function delete(): void {
        $user = Auth::user();
        if (!in_array($user['role'], ['super_admin', 'editor'])) {
            die('অনুমতি নেই');
        }

        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die('CSRF validation failed');
        }

        $pollId = (int) ($_POST['id'] ?? 0);
        if ($pollId > 0) {
            try {
                $db = Database::getConnection();
                $db->prepare("DELETE FROM `poll_votes` WHERE `poll_id` = :id")->execute([':id' => $pollId]);
                $db->prepare("DELETE FROM `poll_options` WHERE `poll_id` = :id")->execute([':id' => $pollId]);
                $db->prepare("DELETE FROM `polls` WHERE `id` = :id")->execute([':id' => $pollId]);
            } catch (Exception $e) {}
        }

        header("Location: {$this->appUrl}/{$this->adminPath}/polls?msg=deleted");
        exit;
    }
}

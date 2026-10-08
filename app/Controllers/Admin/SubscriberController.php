<?php
namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Auth;
use App\Models\Newsletter;

class SubscriberController {
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
        $subscribers = Newsletter::getAll();

        View::render('admin.subscribers_index', [
            'pageTitle'   => 'নিউজলেটার গ্রাহক তালিকা | Admin',
            'user'        => $user,
            'subscribers' => $subscribers,
            'adminPath'   => $this->adminPath,
            'appUrl'      => $this->appUrl,
            'csrfToken'   => Auth::generateCsrf()
        ], null);
    }

    public function delete(): void {
        $user = Auth::user();
        if (!in_array($user['role'], ['super_admin', 'editor'])) {
            die('অনুমতি নেই');
        }

        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            Newsletter::delete($id);
        }

        header("Location: {$this->appUrl}/{$this->adminPath}/subscribers?msg=deleted");
        exit;
    }
}

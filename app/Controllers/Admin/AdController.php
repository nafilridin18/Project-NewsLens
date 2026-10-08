<?php
namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Auth;
use App\Models\Ad;
use Exception;

class AdController {
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

        $user = Auth::user();
        if (!in_array($user['role'], ['super_admin', 'editor'])) {
            die('অনুমতি নেই: শুধুমাত্র সুপার অ্যাডমিন বা এডিটর বিজ্ঞাপন পরিচালনা করতে পারেন।');
        }
    }

    public function index(): void {
        $user = Auth::user();
        $ads = Ad::getAll();

        View::render('admin.ads_index', [
            'pageTitle' => 'বিজ্ঞাপন ব্যবস্থাপনা (Advertisements) | Admin',
            'user'      => $user,
            'ads'       => $ads,
            'adminPath' => $this->adminPath,
            'appUrl'    => $this->appUrl,
            'csrfToken' => Auth::generateCsrf()
        ], null);
    }

    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: {$this->appUrl}/{$this->adminPath}/ads");
            exit;
        }

        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die('CSRF validation failed');
        }

        $title = trim($_POST['title'] ?? '');
        $position = $_POST['position'] ?? 'header';
        $type = $_POST['type'] ?? 'image';
        $link = trim($_POST['link'] ?? '');
        $videoUrl = trim($_POST['video_url'] ?? '');
        $imageUrl = trim($_POST['image_url'] ?? '');

        // Handle Image File Upload if provided
        if (!empty($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['image_file'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'gif'])) {
                $uploadDir = dirname(CONFIG_PATH) . '/public/uploads/ads/';
                if (!is_dir($uploadDir)) {
                    @mkdir($uploadDir, 0777, true);
                }
                $filename = 'ad_' . time() . '_' . rand(100, 999) . '.' . $ext;
                $dest = $uploadDir . $filename;
                if (move_uploaded_file($file['tmp_name'], $dest)) {
                    $imageUrl = 'uploads/ads/' . $filename;
                }
            }
        }

        if (!empty($title)) {
            Ad::create([
                'title'     => $title,
                'position'  => $position,
                'type'      => $type,
                'image'     => $imageUrl,
                'video_url' => $videoUrl,
                'link'      => $link,
                'is_active' => !empty($_POST['is_active']) ? 1 : 0
            ]);
        }

        header("Location: {$this->appUrl}/{$this->adminPath}/ads?msg=created");
        exit;
    }

    public function toggle(): void {
        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die('CSRF validation failed');
        }

        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            Ad::toggleActive($id);
        }

        header("Location: {$this->appUrl}/{$this->adminPath}/ads?msg=toggled");
        exit;
    }

    public function delete(): void {
        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die('CSRF validation failed');
        }

        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            Ad::delete($id);
        }

        header("Location: {$this->appUrl}/{$this->adminPath}/ads?msg=deleted");
        exit;
    }
}

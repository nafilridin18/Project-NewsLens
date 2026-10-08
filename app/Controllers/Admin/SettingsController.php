<?php
namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Auth;
use App\Core\Database;
use App\Models\Setting;
use PDO;
use Exception;

class SettingsController {
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
            die('অনুমতি নেই: শুধুমাত্র সুপার অ্যাডমিন বা এডিটর সেটিংস পরিবর্তন করতে পারেন।');
        }
    }

    public function index(): void {
        $user = Auth::user();
        $settings = Setting::all();

        View::render('admin.settings', [
            'pageTitle' => 'সাইট, ব্র্যান্ডিং ও ফুটার সেটিংস | Admin',
            'user'      => $user,
            'settings'  => $settings,
            'adminPath' => $this->adminPath,
            'appUrl'    => $this->appUrl,
            'csrfToken' => Auth::generateCsrf()
        ], null);
    }

    public function update(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: {$this->appUrl}/{$this->adminPath}/settings");
            exit;
        }

        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die('CSRF ভ্যালিডেশন ব্যর্থ হয়েছে।');
        }

        $db = Database::getConnection();
        $uploadDir = dirname(CONFIG_PATH) . '/public/uploads/logos/';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0777, true);
        }

        // 1. Handle Site Logo Upload / URL
        if (!empty($_FILES['site_logo_file']) && $_FILES['site_logo_file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['site_logo_file'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'svg'])) {
                $filename = 'logo_' . time() . '.' . $ext;
                $dest = $uploadDir . $filename;
                if (move_uploaded_file($file['tmp_name'], $dest)) {
                    $relativeLogoPath = 'uploads/logos/' . $filename;
                    $stmt = $db->prepare("INSERT INTO `settings` (`key`, `value`) VALUES ('site_logo', :val) ON DUPLICATE KEY UPDATE `value` = :val");
                    $stmt->execute([':val' => $relativeLogoPath]);
                }
            }
        } elseif (!empty($_POST['site_logo_url'])) {
            $stmt = $db->prepare("INSERT INTO `settings` (`key`, `value`) VALUES ('site_logo', :val) ON DUPLICATE KEY UPDATE `value` = :val");
            $stmt->execute([':val' => trim($_POST['site_logo_url'])]);
        }

        // 2. Handle Technology Partner Logo Upload / URL
        if (!empty($_FILES['tech_partner_logo_file']) && $_FILES['tech_partner_logo_file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['tech_partner_logo_file'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['png', 'jpg', 'jpeg', 'webp', 'svg'])) {
                $filename = 'partner_' . time() . '.' . $ext;
                $dest = $uploadDir . $filename;
                if (move_uploaded_file($file['tmp_name'], $dest)) {
                    $relativePartnerPath = 'uploads/logos/' . $filename;
                    $stmt = $db->prepare("INSERT INTO `settings` (`key`, `value`) VALUES ('tech_partner_logo', :val) ON DUPLICATE KEY UPDATE `value` = :val");
                    $stmt->execute([':val' => $relativePartnerPath]);
                }
            }
        } elseif (!empty($_POST['tech_partner_logo_url'])) {
            $stmt = $db->prepare("INSERT INTO `settings` (`key`, `value`) VALUES ('tech_partner_logo', :val) ON DUPLICATE KEY UPDATE `value` = :val");
            $stmt->execute([':val' => trim($_POST['tech_partner_logo_url'])]);
        }

        // 3. Save all editorial, footer, contact, and branding settings
        $fields = [
            'site_name_bn', 'site_name_en',
            'tagline_bn', 'tagline_en',
            'editor_name_bn', 'editor_name_en',
            'registration_info_bn', 'registration_info_en',
            'office_address_bn', 'office_address_en',
            'contact_phone', 'contact_email',
            'social_facebook', 'social_youtube', 'social_x',
            'copyright_text_bn', 'copyright_text_en',
            'tech_partner_name', 'tech_partner_url'
        ];

        $stmtSet = $db->prepare("INSERT INTO `settings` (`key`, `value`) VALUES (:k, :v) ON DUPLICATE KEY UPDATE `value` = :v");
        foreach ($fields as $f) {
            if (isset($_POST[$f])) {
                $stmtSet->execute([':k' => $f, ':v' => trim($_POST[$f])]);
            }
        }

        header("Location: {$this->appUrl}/{$this->adminPath}/settings?msg=updated");
        exit;
    }
}

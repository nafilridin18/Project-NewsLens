<?php
namespace App\Controllers\Admin;

use App\Core\View;
use App\Core\Auth;
use App\Core\Database;
use App\Models\Category;
use App\Models\District;
use PDO;
use Exception;

class PostController {
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
        $statusFilter = $_GET['status'] ?? '';
        $user = Auth::user();
        $posts = [];

        try {
            $db = Database::getConnection();
            $query = "
                SELECT p.*, c.name_bn as category_name, c.name_en as category_name_en, COALESCE(NULLIF(p.custom_author, ''), u.name, 'স্টাফ') as author_name, d.name_bn as district_name, d.name_en as district_name_en
                FROM `posts` p
                JOIN `categories` c ON p.category_id = c.id
                LEFT JOIN `users` u ON p.author_id = u.id
                LEFT JOIN `districts` d ON p.district_id = d.id
                WHERE 1=1
            ";

            if ($statusFilter !== '') {
                $query .= " AND p.status = :status";
            }

            if ($user['role'] === 'district_rep' && !empty($user['district_id'])) {
                $query .= " AND p.district_id = " . (int)$user['district_id'];
            }

            $query .= " ORDER BY p.id DESC LIMIT 50";
            $stmt = $db->prepare($query);
            if ($statusFilter !== '') {
                $stmt->bindValue(':status', $statusFilter);
            }
            $stmt->execute();
            $posts = $stmt->fetchAll();
        } catch (Exception $e) {}

        View::render('admin.posts_index', [
            'posts'        => $posts,
            'statusFilter' => $statusFilter,
            'user'         => $user,
            'adminPath'    => $this->adminPath,
            'appUrl'       => $this->appUrl
        ], null);
    }

    public function create(): void {
        $user = Auth::user();
        $categories = Category::getMenuCategories();
        $divisions  = District::getDivisions();
        $db = Database::getConnection();
        $districts  = $db->query("SELECT d.*, divn.name_bn as division_name FROM `districts` d JOIN `divisions` divn ON d.division_id = divn.id ORDER BY divn.id, d.name_bn ASC")->fetchAll();

        View::render('admin.post_create', [
            'user'       => $user,
            'categories' => $categories,
            'divisions'  => $divisions,
            'districts'  => $districts,
            'adminPath'  => $this->adminPath,
            'appUrl'     => $this->appUrl,
            'csrfToken'  => Auth::generateCsrf()
        ], null);
    }

    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: {$this->appUrl}/{$this->adminPath}/posts");
            exit;
        }

        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die('CSRF validation failed');
        }

        $user = Auth::user();
        $title = trim($_POST['title'] ?? '');
        $body = trim($_POST['body'] ?? $_POST['content'] ?? '');
        $categoryId = (int) ($_POST['category_id'] ?? 1);
        $districtId = !empty($_POST['district_id']) ? (int) $_POST['district_id'] : null;
        $isLead = !empty($_POST['is_lead']) ? 1 : 0;
        $isBreaking = !empty($_POST['is_breaking']) ? 1 : 0;
        $videoUrl = trim($_POST['video_url'] ?? '');
        $featuredImage = trim($_POST['featured_image'] ?? $_POST['image_url'] ?? 'https://images.unsplash.com/photo-1541872703-74c5e44368f9?auto=format&fit=crop&w=800&q=80');

        // Check if an image was uploaded
        if (!empty($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $dir = dirname(CONFIG_PATH) . '/public/uploads/news/';
                if (!is_dir($dir)) mkdir($dir, 0777, true);
                $fn = 'news_' . time() . '_' . rand(100, 999) . '.' . $ext;
                if (move_uploaded_file($_FILES['image_file']['tmp_name'], $dir . $fn)) {
                    $featuredImage = $this->appUrl . '/uploads/news/' . $fn;
                }
            }
        }

        $action = $_POST['submit_action'] ?? 'draft';
        if (in_array($user['role'], ['super_admin', 'editor']) && $action === 'publish') {
            $status = 'published';
            $publishedAt = date('Y-m-d H:i:s');
        } elseif ($action === 'submit_review') {
            $status = 'pending';
            $publishedAt = null;
        } else {
            $status = 'draft';
            $publishedAt = null;
        }

        $slug = preg_replace('/[^\p{L}\p{Nd}]+/u', '-', mb_strtolower($title, 'UTF-8'));
        $slug = trim($slug, '-') . '-' . time();

        $authorChoice = $_POST['author_choice'] ?? 'account';
        $customAuthor = null;
        if ($authorChoice === 'anonymous') {
            $customAuthor = 'বেনামী';
        } elseif ($authorChoice === 'custom') {
            $customAuthor = trim($_POST['custom_author_name'] ?? '');
            if (empty($customAuthor)) $customAuthor = null;
        }

        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                INSERT INTO `posts` 
                (`title`, `slug`, `body`, `excerpt`, `featured_image`, `video_url`, `category_id`, `district_id`, `author_id`, `custom_author`, `status`, `is_lead`, `is_breaking`, `published_at`, `created_at`) 
                VALUES 
                (:title, :slug, :body, :excerpt, :image, :video, :catId, :distId, :authorId, :customAuthor, :status, :isLead, :isBreaking, :publishedAt, NOW())
            ");
            $excerpt = mb_substr(strip_tags($body), 0, 180, 'UTF-8');
            $stmt->execute([
                ':title'        => $title,
                ':slug'         => $slug,
                ':body'         => $body,
                ':excerpt'      => $excerpt,
                ':image'        => $featuredImage,
                ':video'        => $videoUrl,
                ':catId'        => $categoryId,
                ':distId'       => $districtId,
                ':authorId'     => $user['id'] ?? 1,
                ':customAuthor' => $customAuthor,
                ':status'       => $status,
                ':isLead'       => $isLead,
                ':isBreaking'   => $isBreaking,
                ':publishedAt'  => $publishedAt
            ]);
            header("Location: {$this->appUrl}/{$this->adminPath}/posts?msg=created");
            exit;
        } catch (\Throwable $e) {
            error_log("Post store error: " . $e->getMessage());
            header("Location: {$this->appUrl}/{$this->adminPath}/posts/create?err=" . urlencode($e->getMessage()));
            exit;
        }
    }

    public function edit(): void {
        $id = (int) ($_GET['id'] ?? 0);
        $user = Auth::user();
        $db = Database::getConnection();

        $stmt = $db->prepare("SELECT * FROM `posts` WHERE `id` = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $post = $stmt->fetch();

        if (!$post) {
            die('সংবাদটি পাওয়া যায়নি।');
        }

        $categories = Category::getMenuCategories();
        $divisions  = District::getDivisions();
        $districts  = $db->query("SELECT * FROM `districts` ORDER BY `name_bn` ASC")->fetchAll();

        View::render('admin.post_edit', [
            'user'       => $user,
            'post'       => $post,
            'categories' => $categories,
            'divisions'  => $divisions,
            'districts'  => $districts,
            'adminPath'  => $this->adminPath,
            'appUrl'     => $this->appUrl,
            'csrfToken'  => Auth::generateCsrf()
        ], null);
    }

    public function update(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: {$this->appUrl}/{$this->adminPath}/posts");
            exit;
        }

        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? '')) {
            die('CSRF validation failed');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $body = trim($_POST['body'] ?? $_POST['content'] ?? '');
        $categoryId = (int) ($_POST['category_id'] ?? 1);
        $districtId = !empty($_POST['district_id']) ? (int) $_POST['district_id'] : null;
        $isLead = !empty($_POST['is_lead']) ? 1 : 0;
        $isBreaking = !empty($_POST['is_breaking']) ? 1 : 0;
        $videoUrl = trim($_POST['video_url'] ?? '');
        $featuredImage = trim($_POST['featured_image'] ?? $_POST['image_url'] ?? '');

        // Upload picture if chosen
        if (!empty($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $dir = dirname(CONFIG_PATH) . '/public/uploads/news/';
                if (!is_dir($dir)) mkdir($dir, 0777, true);
                $fn = 'news_' . time() . '_' . rand(100, 999) . '.' . $ext;
                if (move_uploaded_file($_FILES['image_file']['tmp_name'], $dir . $fn)) {
                    $featuredImage = $this->appUrl . '/uploads/news/' . $fn;
                }
            }
        }

        $status = $_POST['status'] ?? 'published';
        $excerpt = mb_substr(strip_tags($body), 0, 180, 'UTF-8');

        $authorChoice = $_POST['author_choice'] ?? 'account';
        $customAuthor = null;
        if ($authorChoice === 'anonymous') {
            $customAuthor = 'বেনামী';
        } elseif ($authorChoice === 'custom') {
            $customAuthor = trim($_POST['custom_author_name'] ?? '');
            if (empty($customAuthor)) $customAuthor = null;
        }

        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                UPDATE `posts` SET 
                    `title` = :title,
                    `body` = :body,
                    `excerpt` = :excerpt,
                    `featured_image` = :image,
                    `video_url` = :video,
                    `category_id` = :catId,
                    `district_id` = :distId,
                    `custom_author` = :customAuthor,
                    `status` = :status,
                    `is_lead` = :isLead,
                    `is_breaking` = :isBreaking
                WHERE `id` = :id
            ");
            $stmt->execute([
                ':title'        => $title,
                ':body'         => $body,
                ':excerpt'      => $excerpt,
                ':image'        => $featuredImage,
                ':video'        => $videoUrl,
                ':catId'        => $categoryId,
                ':distId'       => $districtId,
                ':customAuthor' => $customAuthor,
                ':status'       => $status,
                ':isLead'       => $isLead,
                ':isBreaking'   => $isBreaking,
                ':id'           => $id
            ]);
            header("Location: {$this->appUrl}/{$this->adminPath}/posts?msg=updated");
            exit;
        } catch (\Throwable $e) {
            error_log("Post update error: " . $e->getMessage());
            header("Location: {$this->appUrl}/{$this->adminPath}/posts/edit?id={$id}&err=" . urlencode($e->getMessage()));
            exit;
        }
    }

    public function delete(): void {
        $user = Auth::user();
        if (!in_array($user['role'], ['super_admin', 'editor'])) {
            die('অনুমতি নেই');
        }

        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);
        if ($id > 0) {
            try {
                $db = Database::getConnection();
                $stmt = $db->prepare("DELETE FROM `posts` WHERE `id` = :id");
                $stmt->execute([':id' => $id]);
            } catch (Exception $e) {}
        }

        header("Location: {$this->appUrl}/{$this->adminPath}/posts?msg=deleted");
        exit;
    }

    public function updateStatus(): void {
        $user = Auth::user();
        if (!in_array($user['role'], ['super_admin', 'editor'])) {
            die('অনুমতি নেই');
        }

        $postId = (int) ($_POST['post_id'] ?? 0);
        $newStatus = $_POST['status'] ?? '';

        if ($postId > 0 && in_array($newStatus, ['published', 'rejected', 'pending'])) {
            try {
                $db = Database::getConnection();
                $stmt = $db->prepare("
                    UPDATE `posts` 
                    SET `status` = :status, `published_at` = CASE WHEN :status = 'published' THEN NOW() ELSE `published_at` END
                    WHERE `id` = :id
                ");
                $stmt->execute([':status' => $newStatus, ':id' => $postId]);
            } catch (Exception $e) {}
        }

        header("Location: {$this->appUrl}/{$this->adminPath}/posts?msg=updated");
        exit;
    }
}

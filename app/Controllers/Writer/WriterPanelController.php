<?php
namespace App\Controllers\Writer;

use App\Core\Database;
use App\Core\Auth;
use App\Core\View;
use App\Core\Middleware;
use App\Models\Post;
use App\Models\Category;
use App\Models\District;
use PDO;

class WriterPanelController {
    /**
     * Writer Dashboard
     */
    public function index(): void {
        $user = Middleware::requireWriter();
        $db = Database::getConnection();
        $authorId = (int) $user['id'];

        // Writer specific stats
        $stmt = $db->prepare("
            SELECT 
                COUNT(*) AS total_posts,
                COALESCE(SUM(views), 0) AS total_views,
                SUM(CASE WHEN status = 'published' THEN 1 ELSE 0 END) AS published_count,
                SUM(CASE WHEN status = 'pending_review' THEN 1 ELSE 0 END) AS pending_count,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) AS rejected_count,
                SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) AS draft_count
            FROM `posts` 
            WHERE `author_id` = :uid
        ");
        $stmt->execute([':uid' => $authorId]);
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);

        // Recent posts by this writer
        $recentStmt = $db->prepare("
            SELECT p.*, c.name_bn AS category_name
            FROM `posts` p
            JOIN `categories` c ON c.id = p.category_id
            WHERE p.author_id = :uid
            ORDER BY p.created_at DESC
            LIMIT 8
        ");
        $recentStmt->execute([':uid' => $authorId]);
        $recentPosts = $recentStmt->fetchAll(PDO::FETCH_ASSOC);

        View::render('writer.dashboard', [
            'pageTitle'   => 'লেখক ড্যাশবোর্ড | Newslensbd',
            'user'        => $user,
            'stats'       => $stats,
            'recentPosts' => $recentPosts,
            'activeTab'   => 'writer_dashboard'
        ], null);
    }

    /**
     * My Posts list (Strictly filtered by author_id)
     */
    public function posts(): void {
        $user = Middleware::requireWriter();
        $db = Database::getConnection();
        $authorId = (int) $user['id'];

        $statusFilter = trim($_GET['status'] ?? '');
        $search = trim($_GET['q'] ?? '');

        $sql = "
            SELECT p.*, c.name_bn AS category_name
            FROM `posts` p
            JOIN `categories` c ON c.id = p.category_id
            WHERE p.author_id = :uid
        ";
        $params = [':uid' => $authorId];

        if (!empty($statusFilter)) {
            $sql .= " AND p.status = :status";
            $params[':status'] = $statusFilter;
        }

        if (!empty($search)) {
            $sql .= " AND (p.title LIKE :search OR p.body LIKE :search)";
            $params[':search'] = "%{$search}%";
        }

        $sql .= " ORDER BY p.created_at DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        View::render('writer.posts_index', [
            'pageTitle'    => 'আমার সংবাদসমূহ | Newslensbd Writer',
            'posts'        => $posts,
            'statusFilter' => $statusFilter,
            'search'       => $search,
            'user'         => $user,
            'success'      => $_SESSION['writer_post_msg'] ?? null,
            'error'        => $_SESSION['writer_post_err'] ?? null,
            'activeTab'    => 'writer_posts',
            'csrfToken'    => Auth::generateCsrf()
        ], null);
        unset($_SESSION['writer_post_msg'], $_SESSION['writer_post_err']);
    }

    /**
     * Show New Post Form for Writer
     */
    public function create(): void {
        $user = Middleware::requireWriter();
        $categories = Category::all();
        $districts = District::all();

        View::render('writer.post_form', [
            'pageTitle'  => 'নতুন সংবাদ লিখুন | Newslensbd Writer',
            'isEdit'     => false,
            'categories' => $categories,
            'districts'  => $districts,
            'user'       => $user,
            'activeTab'  => 'writer_posts',
            'csrfToken'  => Auth::generateCsrf()
        ], null);
    }

    /**
     * Store new post written by writer
     */
    public function store(): void {
        $user = Middleware::requireWriter();
        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['writer_post_err'] = 'নিরাপত্তা টোকেন সঠিক নয়।';
            header("Location: /writer/posts/create");
            exit;
        }

        $title = trim($_POST['title'] ?? '');
        $categoryId = (int) ($_POST['category_id'] ?? 0);
        $body = trim($_POST['body'] ?? '');
        $districtId = !empty($_POST['district_id']) ? (int) $_POST['district_id'] : null;
        $videoUrl = trim($_POST['video_url'] ?? '');
        $requestedAction = trim($_POST['post_action'] ?? 'publish'); // 'publish' or 'draft'

        if (empty($title) || $categoryId <= 0 || empty($body)) {
            $_SESSION['writer_post_err'] = 'শিরোনাম, ক্যাটাগরি ও বিস্তারিত বিবরণ আবশ্যক।';
            header("Location: /writer/posts/create");
            exit;
        }

        // Handle image upload
        $imagePath = null;
        if (!empty($_FILES['featured_image']['name'])) {
            $imagePath = $this->handleImageUpload($_FILES['featured_image']);
        }

        // Routing rule based on permission level:
        // direct_publish -> published
        // editor_review -> pending_review
        $finalStatus = 'draft';
        if ($requestedAction === 'publish') {
            $perm = $user['permission_level'] ?? 'editor_review';
            $finalStatus = ($perm === 'direct_publish') ? 'published' : 'pending_review';
        }

        $db = Database::getConnection();
        $slug = $this->generateSlug($title);

        $stmt = $db->prepare("
            INSERT INTO `posts` (
                `title`, `slug`, `body`, `excerpt`, `featured_image`, `video_url`,
                `category_id`, `district_id`, `author_id`, `status`, `published_at`, `created_at`
            ) VALUES (
                :title, :slug, :body, :excerpt, :img, :vid,
                :cat, :dist, :author, :status, :pub_at, NOW()
            )
        ");

        $publishedAt = ($finalStatus === 'published') ? date('Y-m-d H:i:s') : null;
        $excerpt = mb_substr(strip_tags($body), 0, 160) . '...';

        $stmt->execute([
            ':title'   => $title,
            ':slug'    => $slug,
            ':body'    => $body,
            ':excerpt' => $excerpt,
            ':img'     => $imagePath,
            ':vid'     => !empty($videoUrl) ? $videoUrl : null,
            ':cat'     => $categoryId,
            ':dist'    => $districtId,
            ':author'  => (int) $user['id'],
            ':status'  => $finalStatus,
            ':pub_at'  => $publishedAt
        ]);

        if ($finalStatus === 'published') {
            $_SESSION['writer_post_msg'] = 'সংবাদটি সফলভাবে সরাসরি প্রকাশিত হয়েছে!';
        } elseif ($finalStatus === 'pending_review') {
            $_SESSION['writer_post_msg'] = 'সংবাদটি সম্পাদকীয় পর্যালোচনার (Editor Review) জন্য জমা হয়েছে।';
        } else {
            $_SESSION['writer_post_msg'] = 'সংবাদটি খসড়া হিসেবে সংরক্ষিত হয়েছে।';
        }

        header("Location: /writer/posts");
        exit;
    }

    /**
     * Edit Post (Strict Ownership verified)
     */
    public function edit(): void {
        $id = (int) ($_GET['id'] ?? 0);
        $post = Middleware::checkPostOwnership($id);
        $user = Auth::user();

        $categories = Category::all();
        $districts = District::all();

        View::render('writer.post_form', [
            'pageTitle'  => 'সংবাদ সম্পাদনা | Newslensbd Writer',
            'isEdit'     => true,
            'post'       => $post,
            'categories' => $categories,
            'districts'  => $districts,
            'user'       => $user,
            'activeTab'  => 'writer_posts',
            'csrfToken'  => Auth::generateCsrf()
        ], null);
    }

    /**
     * Update Post
     */
    public function update(): void {
        $id = (int) ($_POST['id'] ?? 0);
        $post = Middleware::checkPostOwnership($id);
        $user = Auth::user();

        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['writer_post_err'] = 'নিরাপত্তা টোকেন সঠিক নয়।';
            header("Location: /writer/posts/edit?id={$id}");
            exit;
        }

        $title = trim($_POST['title'] ?? '');
        $categoryId = (int) ($_POST['category_id'] ?? 0);
        $body = trim($_POST['body'] ?? '');
        $districtId = !empty($_POST['district_id']) ? (int) $_POST['district_id'] : null;
        $videoUrl = trim($_POST['video_url'] ?? '');
        $requestedAction = trim($_POST['post_action'] ?? 'publish');

        if (empty($title) || $categoryId <= 0 || empty($body)) {
            $_SESSION['writer_post_err'] = 'শিরোনাম, ক্যাটাগরি ও বিস্তারিত বিবরণ আবশ্যক।';
            header("Location: /writer/posts/edit?id={$id}");
            exit;
        }

        $imagePath = $post['featured_image'];
        if (!empty($_FILES['featured_image']['name'])) {
            $newImage = $this->handleImageUpload($_FILES['featured_image']);
            if ($newImage) $imagePath = $newImage;
        }

        // Rule from requirements:
        // "Posts of an editor_review writer: after the writer edits a published post it goes back to review.
        // For direct_publish writers edits go live immediately."
        $finalStatus = $post['status'];
        if ($requestedAction === 'draft') {
            $finalStatus = 'draft';
        } else {
            $perm = $user['permission_level'] ?? 'editor_review';
            if ($perm === 'editor_review') {
                $finalStatus = 'pending_review';
            } else {
                $finalStatus = 'published';
            }
        }

        $db = Database::getConnection();
        $excerpt = mb_substr(strip_tags($body), 0, 160) . '...';

        $stmt = $db->prepare("
            UPDATE `posts` SET 
                `title` = :title,
                `body` = :body,
                `excerpt` = :excerpt,
                `featured_image` = :img,
                `video_url` = :vid,
                `category_id` = :cat,
                `district_id` = :dist,
                `status` = :status,
                `updated_at` = NOW()
            WHERE `id` = :id
        ");

        $stmt->execute([
            ':title'   => $title,
            ':body'    => $body,
            ':excerpt' => $excerpt,
            ':img'     => $imagePath,
            ':vid'     => !empty($videoUrl) ? $videoUrl : null,
            ':cat'     => $categoryId,
            ':dist'    => $districtId,
            ':status'  => $finalStatus,
            ':id'      => $id
        ]);

        if ($finalStatus === 'pending_review') {
            $_SESSION['writer_post_msg'] = 'সংবাদটি সম্পাদিত হয়েছে এবং পুনরায় সম্পাদকীয় পর্যালোচনার (Review) জন্য পাঠানো হয়েছে।';
        } else {
            $_SESSION['writer_post_msg'] = 'সংবাদটি সফলভাবে আপডেট করা হয়েছে!';
        }

        header("Location: /writer/posts");
        exit;
    }

    /**
     * Delete Post (Strict Ownership verified)
     */
    public function delete(): void {
        $id = (int) ($_POST['id'] ?? 0);
        Middleware::checkPostOwnership($id);

        if (!Auth::verifyCsrf($_POST['csrf_token'] ?? null)) {
            $_SESSION['writer_post_err'] = 'নিরাপত্তা টোকেন সঠিক নয়।';
            header("Location: /writer/posts");
            exit;
        }

        $db = Database::getConnection();
        $db->prepare("DELETE FROM `posts` WHERE `id` = :id")->execute([':id' => $id]);

        $_SESSION['writer_post_msg'] = 'সংবাদটি সফলভাবে মুছে ফেলা হয়েছে।';
        header("Location: /writer/posts");
        exit;
    }

    private function generateSlug(string $title): string {
        $slug = mb_strtolower(trim($title));
        $slug = preg_replace('/[^\p{L}\p{Nd}]+/u', '-', $slug);
        $slug = trim($slug, '-');
        if (empty($slug)) $slug = 'news-' . time();
        return $slug . '-' . time();
    }

    private function handleImageUpload(array $file): ?string {
        if ($file['error'] !== UPLOAD_ERR_OK) return null;

        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mime, $allowedTypes, true)) return null;
        if ($file['size'] > 5242880) return null; // 5MB

        $ext = ($mime === 'image/png') ? 'png' : (($mime === 'image/webp') ? 'webp' : 'jpg');
        $fileName = 'writer_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        
        $uploadDir = (defined('PUBLIC_PATH') ? PUBLIC_PATH : (dirname(__DIR__, 3) . '/public')) . '/uploads/news';
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0755, true);
        }

        $dest = $uploadDir . '/' . $fileName;
        if (move_uploaded_file($file['tmp_name'], $dest)) {
            return 'uploads/news/' . $fileName;
        }
        return null;
    }
}

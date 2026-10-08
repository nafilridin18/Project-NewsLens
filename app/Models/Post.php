<?php
namespace App\Models;

use App\Core\Database;
use PDO;
use Exception;

class Post {
    /**
     * Get breaking news headlines
     */
    public static function getBreakingNews(int $limit = 6): array {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                SELECT p.id, p.title, p.slug, c.slug AS category_slug, p.published_at 
                FROM `posts` p
                JOIN `categories` c ON p.category_id = c.id
                WHERE p.status = 'published' AND (p.is_breaking = 1 OR p.is_lead = 1)
                ORDER BY p.published_at DESC
                LIMIT :limit
            ");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll();
            if (!empty($results)) {
                return $results;
            }

            // If no breaking flags, return latest published
            $stmtLatest = $db->prepare("
                SELECT p.id, p.title, p.slug, c.slug AS category_slug, p.published_at 
                FROM `posts` p
                JOIN `categories` c ON p.category_id = c.id
                WHERE p.status = 'published'
                ORDER BY p.published_at DESC
                LIMIT :limit
            ");
            $stmtLatest->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmtLatest->execute();
            $results = $stmtLatest->fetchAll();
            if (!empty($results)) return $results;
        } catch (Exception $e) {}

        return [
            ['id' => 1, 'title' => 'জাতীয় অর্থনীতিতে নতুন দিগন্ত: রপ্তানি আয়ে রেকর্ড প্রবৃদ্ধি ও বিনিয়োগ সম্ভাবনা', 'category_slug' => 'national', 'slug' => 'national-economy-growth-export-record']
        ];
    }

    /**
     * Get primary lead story and secondary lead stories
     */
    public static function getLeadStories(): array {
        try {
            $db = Database::getConnection();
            $stmt = $db->query("
                SELECT p.*, c.name_bn AS category_name, c.slug AS category_slug, 
                       COALESCE(NULLIF(p.custom_author, ''), u.name, 'স্টাফ রিপোর্টার') AS author_name, 
                       d.name_bn AS district_name
                FROM `posts` p
                JOIN `categories` c ON p.category_id = c.id
                LEFT JOIN `users` u ON p.author_id = u.id
                LEFT JOIN `districts` d ON p.district_id = d.id
                WHERE p.status = 'published'
                ORDER BY p.is_lead DESC, p.published_at DESC
                LIMIT 5
            ");
            $results = $stmt->fetchAll();
            if (!empty($results)) {
                return [
                    'main' => $results[0],
                    'secondary' => array_slice($results, 1)
                ];
            }
        } catch (Exception $e) {}

        return ['main' => null, 'secondary' => []];
    }

    /**
     * Get Latest news
     */
    public static function getLatest(int $limit = 8): array {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                SELECT p.*, c.name_bn AS category_name, c.slug AS category_slug, 
                       COALESCE(NULLIF(p.custom_author, ''), u.name, 'স্টাফ রিপোর্টার') AS author_name
                FROM `posts` p
                JOIN `categories` c ON p.category_id = c.id
                LEFT JOIN `users` u ON p.author_id = u.id
                WHERE p.status = 'published'
                ORDER BY p.published_at DESC
                LIMIT :limit
            ");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll();
            if (!empty($results)) return $results;
        } catch (Exception $e) {}

        return [];
    }

    /**
     * Get Popular news by view counts
     */
    public static function getPopular(int $limit = 8): array {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                SELECT p.*, c.name_bn AS category_name, c.slug AS category_slug
                FROM `posts` p
                JOIN `categories` c ON p.category_id = c.id
                WHERE p.status = 'published'
                ORDER BY p.views DESC, p.published_at DESC
                LIMIT :limit
            ");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll();
            if (!empty($results)) return $results;
        } catch (Exception $e) {}

        return [];
    }

    /**
     * Get posts for homepage category sections
     */
    public static function getCategorySection(string $categorySlug, int $limit = 4): array {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                SELECT p.*, c.name_bn AS category_name, c.slug AS category_slug, 
                       COALESCE(NULLIF(p.custom_author, ''), u.name, 'স্টাফ রিপোর্টার') AS author_name, 
                       d.name_bn AS district_name
                FROM `posts` p
                JOIN `categories` c ON p.category_id = c.id
                LEFT JOIN `users` u ON p.author_id = u.id
                LEFT JOIN `districts` d ON p.district_id = d.id
                WHERE p.status = 'published' AND c.slug = :categorySlug
                ORDER BY p.published_at DESC
                LIMIT :limit
            ");
            $stmt->bindValue(':categorySlug', $categorySlug);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll();
            if (!empty($results)) return $results;
        } catch (Exception $e) {}

        return [];
    }

    /**
     * Get video news posts
     */
    public static function getVideoPosts(int $limit = 4): array {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                SELECT p.*, c.name_bn AS category_name, c.slug AS category_slug 
                FROM `posts` p
                JOIN `categories` c ON p.category_id = c.id
                WHERE p.status = 'published' AND (p.video_url IS NOT NULL AND p.video_url != '')
                ORDER BY p.published_at DESC
                LIMIT :limit
            ");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll();
            if (!empty($results)) return $results;

            // Fallback to latest posts with sample video
            $fallback = self::getLatest($limit);
            foreach ($fallback as &$f) {
                if (empty($f['video_url'])) {
                    $f['video_url'] = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';
                }
            }
            return $fallback;
        } catch (Exception $e) {}

        return [];
    }

    /**
     * Find single post by ID
     */
    public static function find(int $id): ?array {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                SELECT p.*, c.name_bn AS category_name, c.slug AS category_slug, 
                       COALESCE(NULLIF(p.custom_author, ''), u.name, 'স্টাফ রিপোর্টার') AS author_name, 
                       u.photo AS author_photo,
                       d.name_bn AS district_name, dv.name_bn AS division_name
                FROM `posts` p
                JOIN `categories` c ON p.category_id = c.id
                LEFT JOIN `users` u ON p.author_id = u.id
                LEFT JOIN `districts` d ON p.district_id = d.id
                LEFT JOIN `divisions` dv ON d.division_id = dv.id
                WHERE p.id = :id
                LIMIT 1
            ");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $post = $stmt->fetch();
            return $post ?: null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Increment post view count
     */
    public static function incrementViews(int $id): void {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("UPDATE `posts` SET `views` = `views` + 1 WHERE `id` = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {}
    }

    /**
     * Get related posts
     */
    public static function getRelated(int $categoryId, int $excludeId, int $limit = 4): array {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                SELECT p.*, c.name_bn AS category_name, c.slug AS category_slug
                FROM `posts` p
                JOIN `categories` c ON p.category_id = c.id
                WHERE p.status = 'published' AND p.category_id = :categoryId AND p.id != :excludeId
                ORDER BY p.published_at DESC
                LIMIT :limit
            ");
            $stmt->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
            $stmt->bindValue(':excludeId', $excludeId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Search posts with filters
     */
    public static function search(array $filters = [], int $limit = 20): array {
        try {
            $db = Database::getConnection();
            $sql = "
                SELECT p.*, c.name_bn AS category_name, c.slug AS category_slug, 
                       COALESCE(NULLIF(p.custom_author, ''), u.name, 'স্টাফ রিপোর্টার') AS author_name, 
                       d.name_bn AS district_name, dv.name_bn AS division_name
                FROM `posts` p
                JOIN `categories` c ON p.category_id = c.id
                LEFT JOIN `users` u ON p.author_id = u.id
                LEFT JOIN `districts` d ON p.district_id = d.id
                LEFT JOIN `divisions` dv ON d.division_id = dv.id
                WHERE p.status = 'published'
            ";
            $params = [];

            if (!empty($filters['q'])) {
                $sql .= " AND (p.title LIKE :q1 OR p.body LIKE :q2)";
                $qVal = '%' . trim($filters['q']) . '%';
                $params[':q1'] = $qVal;
                $params[':q2'] = $qVal;
            }

            if (!empty($filters['category_id'])) {
                $sql .= " AND p.category_id = :category_id";
                $params[':category_id'] = (int) $filters['category_id'];
            }

            if (!empty($filters['district_id'])) {
                $sql .= " AND p.district_id = :district_id";
                $params[':district_id'] = (int) $filters['district_id'];
            } elseif (!empty($filters['division_id'])) {
                $sql .= " AND d.division_id = :division_id";
                $params[':division_id'] = (int) $filters['division_id'];
            }

            if (!empty($filters['time_range'])) {
                switch ($filters['time_range']) {
                    case 'today':
                        $sql .= " AND DATE(p.published_at) = CURDATE()";
                        break;
                    case 'week':
                        $sql .= " AND p.published_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                        break;
                    case 'month':
                        $sql .= " AND p.published_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
                        break;
                }
            }

            $sql .= " ORDER BY p.published_at DESC LIMIT :limit";
            $stmt = $db->prepare($sql);
            foreach ($params as $k => $v) {
                $stmt->bindValue($k, $v);
            }
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            return [];
        }
    }
}

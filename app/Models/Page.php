<?php
namespace App\Models;

use App\Core\Database;
use PDO;
use Exception;

class Page {
    /**
     * Get all pages from database
     */
    public static function all(): array {
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT * FROM `pages` ORDER BY `id` ASC");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($rows)) {
                return $rows;
            }
        } catch (Exception $e) {
            // DB fallback
        }

        return self::getDefaults();
    }

    /**
     * Find a page by its URL slug
     */
    public static function findBySlug(string $slug, bool $publishedOnly = false): ?array {
        $slug = trim($slug);
        if ($slug === '') return null;

        try {
            $db = Database::getConnection();
            $sql = "SELECT * FROM `pages` WHERE `slug` = ?";
            if ($publishedOnly) {
                $sql .= " AND `status` = 'published'";
            }
            $stmt = $db->prepare($sql);
            $stmt->execute([$slug]);
            $page = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($page) {
                return $page;
            }
        } catch (Exception $e) {
            // DB error fallback
        }

        // Check in default seeded pages
        $defaults = self::getDefaults();
        foreach ($defaults as $p) {
            if ($p['slug'] === $slug) {
                if ($publishedOnly && ($p['status'] ?? 'published') !== 'published') {
                    return null;
                }
                return $p;
            }
        }

        return null;
    }

    /**
     * Find page by ID
     */
    public static function findById(int $id): ?array {
        if ($id <= 0) return null;

        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM `pages` WHERE `id` = ?");
            $stmt->execute([$id]);
            $page = $stmt->fetch(PDO::FETCH_ASSOC);
            return $page ?: null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Create a new static page
     */
    public static function create(array $data): int {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO `pages` (`slug`, `title`, `title_en`, `badge`, `content`, `meta_title`, `meta_desc`, `status`)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['slug'],
            $data['title'],
            $data['title_en'] ?? null,
            $data['badge'] ?? null,
            $data['content'] ?? '',
            $data['meta_title'] ?? null,
            $data['meta_desc'] ?? null,
            $data['status'] ?? 'published'
        ]);

        return (int) $db->lastInsertId();
    }

    /**
     * Update an existing static page
     */
    public static function update(int $id, array $data): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            UPDATE `pages`
            SET `title` = ?,
                `title_en` = ?,
                `badge` = ?,
                `slug` = ?,
                `content` = ?,
                `meta_title` = ?,
                `meta_desc` = ?,
                `status` = ?
            WHERE `id` = ?
        ");
        return $stmt->execute([
            $data['title'],
            $data['title_en'] ?? null,
            $data['badge'] ?? null,
            $data['slug'],
            $data['content'] ?? '',
            $data['meta_title'] ?? null,
            $data['meta_desc'] ?? null,
            $data['status'] ?? 'published',
            $id
        ]);
    }

    /**
     * Delete a static page
     */
    public static function delete(int $id): bool {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM `pages` WHERE `id` = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Toggle status between published and draft
     */
    public static function toggleStatus(int $id): string {
        $page = self::findById($id);
        if (!$page) return 'error';

        $newStatus = ($page['status'] === 'published') ? 'draft' : 'published';
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE `pages` SET `status` = ? WHERE `id` = ?");
        $stmt->execute([$newStatus, $id]);

        return $newStatus;
    }

    /**
     * Built-in default static pages (used as fallback and initial seeder)
     */
    public static function getDefaults(): array {
        return [
            [
                'id'         => 1,
                'slug'       => 'about',
                'title'      => 'আমাদের সম্পর্কে (About Us)',
                'title_en'   => 'About Us',
                'badge'      => 'পরিচিতি',
                'content'    => '<p><strong>নিউজলেন্সবিডি (Newslensbd)</strong> বাংলাদেশের একটি অগ্রণী, বস্তুনিষ্ঠ ও দায়িত্বশীল ডিজিটাল সংবাদ মাধ্যম। আমাদের মূল লক্ষ্য—"সাধারণের বাইরে, সত্যের খোঁজে"—পাঠকের নিকট নিরপেক্ষ ও যাচাইকৃত সংবাদ পরিবেশন করা।</p><p>আমরা জাতীয়, আন্তর্জাতিক, রাজনীতি, অর্থনীতি, খেলাধুলা, বিজ্ঞান ও প্রযুক্তিসহ প্রতিটি ক্ষেত্রে নির্ভরযোগ্য সংবাদ প্রবাহ নিশ্চিত করতে নিরলসভাবে কাজ করে যাচ্ছি। আমাদের সাংবাদিক ও লেখকরা সত্যের পক্ষে অবিচল এবং অপতথ্য ও গুজবের বিরুদ্ধে সদা সতর্ক।</p><h3>আমাদের মূল্যবোধ</h3><ul><li><strong>বস্তুনিষ্ঠতা:</strong> তথ্যের সত্যতা যাচাই ছাড়া কোনো সংবাদ প্রকাশ না করা।</li><li><strong>নিরপেক্ষতা:</strong> কোনো পক্ষপাতিত্ব বা চাপের কাছে নতি স্বীকার না করা।</li><li><strong>দায়িত্বশীলতা:</strong> সমাজ ও রাষ্ট্রের প্রতি সর্বোচ্চ সংবেদনশীলতা বজায় রাখা।</li></ul>',
                'meta_title' => 'আমাদের সম্পর্কে | Newslensbd',
                'meta_desc'  => 'নিউজলেন্সবিডি বাংলাদেশের একটি অগ্রণী, বস্তুনিষ্ঠ ও দায়িত্বশীল ডিজিটাল সংবাদ মাধ্যম।',
                'status'     => 'published'
            ],
            [
                'id'         => 2,
                'slug'       => 'contact',
                'title'      => 'যোগাযোগ (Contact Us)',
                'title_en'   => 'Contact Us',
                'badge'      => 'বার্তা পাঠান',
                'content'    => '<p>নিউজলেন্সবিডির সাথে যেকোনো তথ্যের আদান-প্রদান, সংবাদ টিপস বা মতামতের জন্য নিচের মাধ্যমে আমাদের সাথে যোগাযোগ করতে পারেন:</p><div style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; padding: 20px; margin: 20px 0;"><p><strong>📧 বার্তা ও সম্পাদকীয় বিভাগ:</strong> editor@newslensbd.com</p><p><strong>📰 সংবাদ বিজ্ঞপ্তি:</strong> newsdesk@newslensbd.com</p><p><strong>📢 বিজ্ঞাপন ও স্পন্সরশিপ:</strong> ads@newslensbd.com</p><p><strong>📍 প্রধান কার্যালয়:</strong> বীর উত্তম সি আর দত্ত রোড, ঢাকা-১২০৫, বাংলাদেশ</p></div>',
                'meta_title' => 'যোগাযোগ | Newslensbd',
                'meta_desc'  => 'নিউজলেন্সবিডি প্রধান কার্যালয় ও বার্তা বিভাগের সাথে যোগাযোগের মাধ্যমসমূহ।',
                'status'     => 'published'
            ],
            [
                'id'         => 3,
                'slug'       => 'advertise',
                'title'      => 'বিজ্ঞাপন ও বিপণন (Advertise with Us)',
                'title_en'   => 'Advertise with Us',
                'badge'      => 'প্রচার ও প্রসার',
                'content'    => '<p>নিউজলেন্সবিডিতে বিজ্ঞাপন দিয়ে আপনার ব্র্যান্ড ও পণ্যের প্রচার পৌঁছে দিন প্রতিদিনের লাখো সচেতন পাঠকের কাছে।</p><h3>বিজ্ঞাপনের নির্ধারিত স্লটসমূহ:</h3><ul><li><strong>হেডার ব্যানার (Header Banner):</strong> ৭২৮ × ৯০ পিক্সেল</li><li><strong>সাইডবার ব্যানার (Sidebar Ad):</strong> ৩০০ × ২৫০ পিক্সেল</li><li><strong>আর্টিকেলের ভেতরে (In-Article Ad):</strong> ৭২৮ × ৯০ পিক্সেল</li><li><strong>ফুটার ব্যানার (Footer Banner):</strong> ৯৭০ × ৯০ পিক্সেল</li><li><strong>স্পন্সরড কনটেন্ট ও ভিডিও বিজ্ঞাপন:</strong> বিস্তারিত আলোচনার সাপেক্ষে</li></ul><p>বিজ্ঞাপনের মূল্য তালিকা ও বুকিংয়ের জন্য ইমেইল করুন: <strong>ads@newslensbd.com</strong></p>',
                'meta_title' => 'বিজ্ঞাপন ও বিপণন | Newslensbd',
                'meta_desc'  => 'নিউজলেন্সবিডি ডিজিটাল পোর্টালে বিজ্ঞাপন ও স্পন্সরশিপের সুযোগসমূহ।',
                'status'     => 'published'
            ],
            [
                'id'         => 4,
                'slug'       => 'privacy',
                'title'      => 'গোপনীয়তা নীতি (Privacy Policy)',
                'title_en'   => 'Privacy Policy',
                'badge'      => 'নিরাপত্তা',
                'content'    => '<p>নিউজলেন্সবিডি পাঠকদের তথ্যের সর্বোচ্চ সুরক্ষা ও গোপনীয়তা বজায় রাখতে প্রতিশ্রুতিবদ্ধ। আমাদের ওয়েবসাইট ব্রাউজ করার সময় ব্যবহারকারীর কোনো ব্যক্তিগত তথ্য অনুমতি ব্যতীত সংগ্রহ করা হয় না।</p><h3>তথ্য সংগ্রহ ও ব্যবহার:</h3><p>ওয়েবসাইটের পারফরম্যান্স এবং ব্যবহারকারীর অভিজ্ঞতা উন্নতির লক্ষ্যে কুকিজ (Cookies) ও ব্রাউজার ক্যাশ ব্যবহার করা হতে পারে। পাঠকদের ইমেইল শুধুমাত্র নিউজলেটার ও ওটিপি যাচাইকরণের উদ্দেশ্যে ব্যবহার করা হয় এবং তা কখনো কোনো তৃতীয় পক্ষের কাছে হস্তান্তর বা বিক্রি করা হয় না।</p>',
                'meta_title' => 'গোপনীয়তা নীতি | Newslensbd',
                'meta_desc'  => 'নিউজলেন্সবিডির পাঠকদের তথ্যের সুরক্ষা ও গোপনীয়তা নীতি।',
                'status'     => 'published'
            ],
            [
                'id'         => 5,
                'slug'       => 'terms',
                'title'      => 'ব্যবহারের শর্তাবলী (Terms of Service)',
                'title_en'   => 'Terms of Service',
                'badge'      => 'শর্তাবলী',
                'content'    => '<p>নিউজলেন্সবিডি ওয়েবসাইটে প্রকাশিত সকল সংবাদ, নিবন্ধ, ছবি ও ভিডিও কনটেন্ট কপিরাইট আইনের আওতাভুক্ত। পূর্বানুমতি ও যথাযথ কৃতজ্ঞতা প্রকাশ ছাড়া কোনো কনটেন্ট হুবহু কপি বা অননুমোদিতভাবে পুনর্প্রকাশ করা আইনত দণ্ডনীয়।</p><p>মন্তব্য বিভাগে কোনো আক্রমণাত্মক, অশ্লীল, ধর্মীয় অনুভূতিতে আঘাতকারী বা আইন পরিপন্থী মন্তব্য প্রকাশ করা কঠোরভাবে নিষিদ্ধ।</p>',
                'meta_title' => 'ব্যবহারের শর্তাবলী | Newslensbd',
                'meta_desc'  => 'নিউজলেন্সবিডি পোর্টাল ব্যবহারের নিয়মাবলী ও শর্তাবলী।',
                'status'     => 'published'
            ],
            [
                'id'         => 6,
                'slug'       => 'corrections',
                'title'      => 'সংশোধনী নীতি (Corrections Policy)',
                'title_en'   => 'Corrections Policy',
                'badge'      => 'স্বচ্ছতা',
                'content'    => '<p>নিউজলেন্সবিডি সঠিক ও বস্তুনিষ্ঠ সংবাদ পরিবেশনে সর্বদা সচেষ্ট। তবে কোনো সংবাদে অনিচ্ছাকৃত তথ্যগত ভুল বা মুদ্রণত্রুটি পরিলক্ষিত হলে আমরা তা তাৎক্ষণিকভাবে যাচাই করে দ্রুত সংশোধন করি এবং পাঠকদের উদ্দেশ্যে সংশোধনী নোট সংযোজন করি।</p><p>কোনো সংবাদে তথ্যের অসঙ্গতি চোখে পড়লে অনুগ্রহ করে আমাদের বার্তা বিভাগে ইমেইল করুন: <strong>corrections@newslensbd.com</strong></p>',
                'meta_title' => 'সংশোধনী নীতি | Newslensbd',
                'meta_desc'  => 'নিউজলেন্সবিডির সাংবাদিকতার সততা ও তথ্য সংশোধনী নীতি।',
                'status'     => 'published'
            ]
        ];
    }
}

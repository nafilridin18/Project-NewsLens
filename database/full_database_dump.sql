-- =====================================================================
-- Newslensbd (নিউজলেন্সবিডি) Database Schema
-- Version: 1.0.0 (MySQL 8.0+ / MariaDB 10.5+)
-- Charset: utf8mb4 / Collation: utf8mb4_unicode_ci
-- =====================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- 1. Users table
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL,
    `email` VARCHAR(191) NOT NULL UNIQUE,
    `phone` VARCHAR(30) NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `role` ENUM('super_admin', 'admin', 'editor', 'sub_editor', 'reporter', 'district_rep', 'writer', 'user') NOT NULL DEFAULT 'user',
    `district_id` INT UNSIGNED NULL,
    `photo` VARCHAR(255) NULL,
    `avatar` VARCHAR(255) NULL,
    `cover` VARCHAR(255) NULL,
    `bio` TEXT NULL,
    `location` VARCHAR(150) NULL,
    `social_links` JSON NULL,
    `writer_reason` TEXT NULL,
    `rejected_reason` TEXT NULL,
    `approved_by` BIGINT UNSIGNED NULL,
    `approved_at` DATETIME NULL,
    `status` ENUM('pending', 'active', 'suspended', 'removed', 'unverified', 'inactive', 'banned') NOT NULL DEFAULT 'active',
    `email_verified_at` TIMESTAMP NULL,
    `permission_level` ENUM('direct_publish', 'editor_review') NULL DEFAULT NULL,
    `last_login` DATETIME NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL,
    INDEX `idx_users_role` (`role`),
    INDEX `idx_users_status` (`status`),
    INDEX `idx_users_permission` (`permission_level`),
    INDEX `idx_users_role_status` (`role`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Divisions table (৮ বিভাগ)
DROP TABLE IF EXISTS `divisions`;
CREATE TABLE `divisions` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name_bn` VARCHAR(100) NOT NULL,
    `name_en` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Districts table (৬৪ জেলা)
DROP TABLE IF EXISTS `districts`;
CREATE TABLE `districts` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `division_id` INT UNSIGNED NOT NULL,
    `name_bn` VARCHAR(100) NOT NULL,
    `name_en` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    INDEX `idx_districts_division` (`division_id`),
    CONSTRAINT `fk_districts_division` FOREIGN KEY (`division_id`) REFERENCES `divisions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Upazilas table (উপজেলা)
DROP TABLE IF EXISTS `upazilas`;
CREATE TABLE `upazilas` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `district_id` INT UNSIGNED NOT NULL,
    `name_bn` VARCHAR(100) NOT NULL,
    `name_en` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL,
    INDEX `idx_upazilas_district` (`district_id`),
    UNIQUE KEY `uniq_district_upazila_slug` (`district_id`, `slug`),
    CONSTRAINT `fk_upazilas_district` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Categories table (১৪টি মূল ক্যাটাগরি ও সাব-ক্যাটাগরি)
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name_bn` VARCHAR(100) NOT NULL,
    `name_en` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `parent_id` INT UNSIGNED NULL DEFAULT NULL,
    `sort_order` INT NOT NULL DEFAULT 0,
    `is_menu` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_categories_parent` (`parent_id`),
    INDEX `idx_categories_menu_order` (`is_menu`, `sort_order`),
    CONSTRAINT `fk_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Posts table (খবর)
DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `subtitle` VARCHAR(255) NULL,
    `slug` VARCHAR(255) NOT NULL,
    `body` LONGTEXT NOT NULL,
    `excerpt` TEXT NULL,
    `featured_image` VARCHAR(255) NULL,
    `video_url` VARCHAR(255) NULL,
    `category_id` INT UNSIGNED NOT NULL,
    `district_id` INT UNSIGNED NULL,
    `upazila_id` INT UNSIGNED NULL,
    `author_id` BIGINT UNSIGNED NOT NULL,
    `custom_author` VARCHAR(150) NULL DEFAULT NULL,
    `status` ENUM('draft', 'pending_review', 'pending', 'published', 'scheduled', 'rejected') NOT NULL DEFAULT 'draft',
    `rejected_reason` TEXT NULL,
    `is_lead` TINYINT(1) NOT NULL DEFAULT 0,
    `is_breaking` TINYINT(1) NOT NULL DEFAULT 0,
    `views` BIGINT UNSIGNED NOT NULL DEFAULT 0,
    `meta_title` VARCHAR(255) NULL,
    `meta_desc` VARCHAR(500) NULL,
    `published_at` DATETIME NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_posts_slug` (`slug`),
    INDEX `idx_posts_status_published` (`status`, `published_at`),
    INDEX `idx_posts_category_status` (`category_id`, `status`, `published_at`),
    INDEX `idx_posts_district_status` (`district_id`, `status`, `published_at`),
    INDEX `idx_posts_lead_status` (`is_lead`, `status`, `published_at`),
    INDEX `idx_posts_breaking` (`is_breaking`),
    INDEX `idx_posts_author` (`author_id`),
    FULLTEXT KEY `ft_posts_search` (`title`, `body`),
    CONSTRAINT `fk_posts_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT,
    CONSTRAINT `fk_posts_district` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_posts_upazila` FOREIGN KEY (`upazila_id`) REFERENCES `upazilas` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_posts_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Tags table
DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `slug` VARCHAR(100) NOT NULL UNIQUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Post Tags table (ManyToMany)
DROP TABLE IF EXISTS `post_tags`;
CREATE TABLE `post_tags` (
    `post_id` BIGINT UNSIGNED NOT NULL,
    `tag_id` BIGINT UNSIGNED NOT NULL,
    PRIMARY KEY (`post_id`, `tag_id`),
    INDEX `idx_post_tags_tag` (`tag_id`),
    CONSTRAINT `fk_post_tags_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_post_tags_tag` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Media table (মিডিয়া লাইব্রেরি)
DROP TABLE IF EXISTS `media`;
CREATE TABLE `media` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `file_path` VARCHAR(255) NOT NULL,
    `alt_text` VARCHAR(255) NULL,
    `caption` TEXT NULL,
    `file_size` INT UNSIGNED NULL,
    `mime_type` VARCHAR(50) NULL,
    `uploaded_by` BIGINT UNSIGNED NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_media_uploader` (`uploaded_by`),
    CONSTRAINT `fk_media_user` FOREIGN KEY (`uploaded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Comments table (মন্তব্য)
DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `post_id` BIGINT UNSIGNED NOT NULL,
    `parent_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(191) NULL,
    `body` TEXT NOT NULL,
    `ip` VARCHAR(45) NOT NULL,
    `status` ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_comments_post_status` (`post_id`, `status`),
    INDEX `idx_comments_parent` (`parent_id`),
    CONSTRAINT `fk_comments_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_comments_parent` FOREIGN KEY (`parent_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Polls table (অনলাইন জরিপ)
DROP TABLE IF EXISTS `polls`;
CREATE TABLE `polls` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `question` VARCHAR(255) NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `end_at` DATETIME NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_polls_active` (`is_active`, `end_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Poll Options table (জরিপের অপশন)
DROP TABLE IF EXISTS `poll_options`;
CREATE TABLE `poll_options` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `poll_id` INT UNSIGNED NOT NULL,
    `option_text` VARCHAR(255) NOT NULL,
    `votes` INT UNSIGNED NOT NULL DEFAULT 0,
    INDEX `idx_poll_options_poll` (`poll_id`),
    CONSTRAINT `fk_poll_options_poll` FOREIGN KEY (`poll_id`) REFERENCES `polls` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. Poll Votes table (ভোট ট্র্যাকিং)
DROP TABLE IF EXISTS `poll_votes`;
CREATE TABLE `poll_votes` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `poll_id` INT UNSIGNED NOT NULL,
    `option_id` INT UNSIGNED NOT NULL,
    `ip` VARCHAR(45) NOT NULL,
    `fingerprint` VARCHAR(64) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uniq_poll_ip` (`poll_id`, `ip`),
    INDEX `idx_poll_votes_poll` (`poll_id`),
    CONSTRAINT `fk_poll_votes_poll` FOREIGN KEY (`poll_id`) REFERENCES `polls` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_poll_votes_option` FOREIGN KEY (`option_id`) REFERENCES `poll_options` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. Ads table (বিজ্ঞাপন)
DROP TABLE IF EXISTS `ads`;
CREATE TABLE `ads` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(150) NOT NULL,
    `position` ENUM('header', 'sidebar', 'in_article', 'footer', 'popup') NOT NULL,
    `type` ENUM('image', 'video', 'code') NOT NULL DEFAULT 'image',
    `image` VARCHAR(255) NULL,
    `video_url` VARCHAR(255) NULL,
    `link` VARCHAR(255) NULL,
    `code` TEXT NULL,
    `clicks` INT UNSIGNED NOT NULL DEFAULT 0,
    `impressions` INT UNSIGNED NOT NULL DEFAULT 0,
    `start_at` DATETIME NULL,
    `end_at` DATETIME NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_ads_position_active` (`position`, `is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. Settings table (সাইট সেটিংস)
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
    `key` VARCHAR(100) NOT NULL PRIMARY KEY,
    `value` LONGTEXT NULL,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 16. Activity Logs table (লগ ও অডিট ট্রেইল)
DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NULL,
    `action` VARCHAR(100) NOT NULL,
    `post_id` BIGINT UNSIGNED NULL,
    `details` TEXT NULL,
    `ip_address` VARCHAR(45) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_activity_user` (`user_id`),
    INDEX `idx_activity_post` (`post_id`),
    CONSTRAINT `fk_activity_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_activity_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 17. Newsletter Subscribers table (নিউজলেটার সাবস্ক্রাইবার)
DROP TABLE IF EXISTS `newsletter_subscribers`;
CREATE TABLE `newsletter_subscribers` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(191) NOT NULL UNIQUE,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `token` VARCHAR(64) NULL,
    `subscribed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 18. Static Pages table (স্ট্যাটিক পেজ: আমাদের সম্পর্কে, শর্তাবলী ইত্যাদি)
DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `title_en` VARCHAR(255) NULL,
    `badge` VARCHAR(100) NULL,
    `slug` VARCHAR(150) NOT NULL UNIQUE,
    `content` LONGTEXT NOT NULL,
    `meta_title` VARCHAR(255) NULL,
    `meta_desc` VARCHAR(500) NULL,
    `status` ENUM('draft', 'published') NOT NULL DEFAULT 'published',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 19. Security: Login Attempts table (ব্রুট ফোর্স প্রতিরোধ)
DROP TABLE IF EXISTS `login_attempts`;
CREATE TABLE `login_attempts` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `ip_address` VARCHAR(45) NOT NULL,
    `email` VARCHAR(191) NOT NULL,
    `attempted_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_login_ip_attempt` (`ip_address`, `attempted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 20. Security: OTP Codes (রেজিস্ট্রেশন ও পাসওয়ার্ড রিসেট ইমেইল যাচাই)
DROP TABLE IF EXISTS `otp_codes`;
CREATE TABLE `otp_codes` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(191) NOT NULL,
    `purpose` ENUM('register', 'forgot_password') NOT NULL,
    `code_hash` VARCHAR(255) NOT NULL,
    `attempts` INT UNSIGNED NOT NULL DEFAULT 0,
    `expires_at` DATETIME NOT NULL,
    `used_at` DATETIME NULL,
    `ip` VARCHAR(45) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_otp_email_purpose` (`email`, `purpose`),
    INDEX `idx_otp_expires` (`expires_at`),
    INDEX `idx_otp_ip_created` (`ip`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 21. Security: Password Resets
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(191) NOT NULL,
    `token_hash` VARCHAR(255) NOT NULL,
    `expires_at` DATETIME NOT NULL,
    `used_at` DATETIME NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_pwd_email` (`email`),
    INDEX `idx_pwd_token` (`token_hash`),
    INDEX `idx_pwd_expires` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 22. Analytics: Post Views Tracking (৩০ মিনিটের ডুপ্লিকেট সুরক্ষা সহ)
DROP TABLE IF EXISTS `post_views`;
CREATE TABLE `post_views` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `post_id` BIGINT UNSIGNED NOT NULL,
    `ip_hash` VARCHAR(64) NOT NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `viewed_at` DATETIME NOT NULL,
    INDEX `idx_pv_post_viewed` (`post_id`, `viewed_at`),
    INDEX `idx_pv_dedup` (`post_id`, `ip_hash`, `viewed_at`),
    CONSTRAINT `fk_pv_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 23. In-App Notifications (হেডার বেল ও বার্তা তালিকা)
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NULL,
    `type` VARCHAR(50) NOT NULL DEFAULT 'breaking_news',
    `title` VARCHAR(255) NOT NULL,
    `body` TEXT NOT NULL,
    `url` VARCHAR(255) NOT NULL,
    `read_at` DATETIME NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_notif_user_read` (`user_id`, `read_at`),
    CONSTRAINT `fk_notif_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 24. Web Push Subscriptions (মোবাইল ও ব্রাউজার পুশ নোটিফিকেশন)
DROP TABLE IF EXISTS `push_subscriptions`;
CREATE TABLE `push_subscriptions` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `endpoint` TEXT NOT NULL,
    `p256dh` VARCHAR(255) NOT NULL,
    `auth` VARCHAR(255) NOT NULL,
    `user_agent` VARCHAR(255) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_push_user` (`user_id`),
    CONSTRAINT `fk_push_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 25. Notification Queue (ব্যাকগ্রাউন্ড কিউ ও ক্রন জব)
DROP TABLE IF EXISTS `notification_queue`;
CREATE TABLE `notification_queue` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `post_id` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `body` TEXT NOT NULL,
    `url` VARCHAR(255) NOT NULL,
    `status` ENUM('pending', 'processing', 'completed', 'failed') NOT NULL DEFAULT 'pending',
    `attempts` INT UNSIGNED NOT NULL DEFAULT 0,
    `last_error` TEXT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `processed_at` DATETIME NULL,
    INDEX `idx_nq_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 26. Audit Logs (অ্যাডমিন ও সম্পাদকীয় কার্যক্রমের রেকর্ড)
DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE `audit_logs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NULL,
    `action` VARCHAR(100) NOT NULL,
    `target_type` VARCHAR(50) NULL,
    `target_id` BIGINT UNSIGNED NULL,
    `details` JSON NULL,
    `ip` VARCHAR(45) NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_audit_user` (`user_id`),
    INDEX `idx_audit_action` (`action`),
    INDEX `idx_audit_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 27. Bookmarks (পাঠকদের সংরক্ষিত সংবাদ)
DROP TABLE IF EXISTS `bookmarks`;
CREATE TABLE `bookmarks` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `post_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY `uniq_user_post_bookmark` (`user_id`, `post_id`),
    INDEX `idx_bm_user` (`user_id`),
    CONSTRAINT `fk_bm_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_bm_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;


-- =====================================================================
-- Newslensbd (নিউজলেন্সবিডি) Complete Seed Data
-- 14 Categories, 8 Divisions, 42 Districts, Super Admin, Settings,
-- Interactive Poll, Dummy Ads (Image & Video), and 14 Category News Articles
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Default Super Admin User (admin@newslensbd.com / password123)
INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password_hash`, `role`, `status`, `created_at`) VALUES
(1, 'Super Admin', 'admin@newslensbd.com', '01700000000', '$2y$10$w8LdNu1k7oJ.fQ7jR4J4te7E0K78o.gCszR8VzS4wQJcQ7X1eP52W', 'super_admin', 'active', NOW())
ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `role`=VALUES(`role`);

-- 2. 8 Divisions of Bangladesh
INSERT INTO `divisions` (`id`, `name_bn`, `name_en`, `slug`) VALUES
(1, 'ঢাকা', 'Dhaka', 'dhaka'),
(2, 'চট্টগ্রাম', 'Chattogram', 'chattogram'),
(3, 'রাজশাহী', 'Rajshahi', 'rajshahi'),
(4, 'খুলনা', 'Khulna', 'khulna'),
(5, 'বরিশাল', 'Barishal', 'barishal'),
(6, 'সিলেট', 'Sylhet', 'sylhet'),
(7, 'রংপুর', 'Rangpur', 'rangpur'),
(8, 'ময়মনসিংহ', 'Mymensingh', 'mymensingh')
ON DUPLICATE KEY UPDATE `name_bn`=VALUES(`name_bn`);

-- 3. 64 Districts of Bangladesh (৮ বিভাগের সকল ৬৪ জেলা)
INSERT INTO `districts` (`id`, `division_id`, `name_bn`, `name_en`, `slug`) VALUES
-- Dhaka Division (13)
(101, 1, 'ঢাকা', 'Dhaka', 'dhaka'),
(102, 1, 'গাজীপুর', 'Gazipur', 'gazipur'),
(103, 1, 'নারায়ণগঞ্জ', 'Narayanganj', 'narayanganj'),
(104, 1, 'নরসিংদী', 'Narsingdi', 'narsingdi'),
(105, 1, 'মুন্সীগঞ্জ', 'Munshiganj', 'munshiganj'),
(106, 1, 'মানিকগঞ্জ', 'Manikganj', 'manikganj'),
(107, 1, 'টাঙ্গাইল', 'Tangail', 'tangail'),
(108, 1, 'কিশোরগঞ্জ', 'Kishoreganj', 'kishoreganj'),
(109, 1, 'ফরিদপুর', 'Faridpur', 'faridpur'),
(110, 1, 'গোপালগঞ্জ', 'Gopalganj', 'gopalganj'),
(111, 1, 'মাদারীপুর', 'Madaripur', 'madaripur'),
(112, 1, 'রাজবাড়ী', 'Rajbari', 'rajbari'),
(113, 1, 'শরীয়তপুর', 'Shariatpur', 'shariatpur'),
-- Chattogram Division (11)
(201, 2, 'চট্টগ্রাম', 'Chattogram', 'chattogram'),
(202, 2, 'কক্সবাজার', 'Coxs Bazar', 'coxs-bazar'),
(203, 2, 'কুমিল্লা', 'Cumilla', 'cumilla'),
(204, 2, 'ফেনী', 'Feni', 'feni'),
(205, 2, 'ব্রাহ্মণবাড়িয়া', 'Brahmanbaria', 'brahmanbaria'),
(206, 2, 'নোয়াখালী', 'Noakhali', 'noakhali'),
(207, 2, 'চাঁদপুর', 'Chandpur', 'chandpur'),
(208, 2, 'লক্ষ্মীপুর', 'Lakshmipur', 'lakshmipur'),
(209, 2, 'রাঙ্গামাটি', 'Rangamati', 'rangamati'),
(210, 2, 'বান্দরবান', 'Bandarban', 'bandarban'),
(211, 2, 'খাগড়াছড়ি', 'Khagrachhari', 'khagrachhari'),
-- Rajshahi Division (8)
(301, 3, 'রাজশাহী', 'Rajshahi', 'rajshahi'),
(302, 3, 'বগুড়া', 'Bogura', 'bogura'),
(303, 3, 'পাবনা', 'Pabna', 'pabna'),
(304, 3, 'সিরাজগঞ্জ', 'Sirajganj', 'sirajganj'),
(305, 3, 'নওগাঁ', 'Naogaon', 'naogaon'),
(306, 3, 'নাটোর', 'Natore', 'natore'),
(307, 3, 'চাঁপাইনবাবগঞ্জ', 'Chapainawabganj', 'chapainawabganj'),
(308, 3, 'জয়পুরহাট', 'Joypurhat', 'joypurhat'),
-- Khulna Division (10)
(401, 4, 'খুলনা', 'Khulna', 'khulna'),
(402, 4, 'যশোর', 'Jashore', 'jashore'),
(403, 4, 'সাতক্ষীরা', 'Satkhira', 'satkhira'),
(404, 4, 'কুষ্টিয়া', 'Kushtia', 'kushtia'),
(405, 4, 'বাগেরহাট', 'Bagerhat', 'bagerhat'),
(406, 4, 'ঝিনাইদহ', 'Jhenaidah', 'jhenaidah'),
(407, 4, 'মাগুরা', 'Magura', 'magura'),
(408, 4, 'নড়াইল', 'Narail', 'narail'),
(409, 4, 'চুয়াডাঙ্গা', 'Chuadanga', 'chuadanga'),
(410, 4, 'মেহেরপুর', 'Meherpur', 'meherpur'),
-- Barishal Division (6)
(501, 5, 'বরিশাল', 'Barishal', 'barishal'),
(502, 5, 'পটুয়াখালী', 'Patuakhali', 'patuakhali'),
(503, 5, 'ভোলা', 'Bhola', 'bhola'),
(504, 5, 'পিরোজপুর', 'Pirojpur', 'pirojpur'),
(505, 5, 'বরগুনা', 'Barguna', 'barguna'),
(506, 5, 'ঝালকাঠি', 'Jhalokati', 'jhalokati'),
-- Sylhet Division (4)
(601, 6, 'সিলেট', 'Sylhet', 'sylhet'),
(602, 6, 'মৌলভীবাজার', 'Moulvibazar', 'moulvibazar'),
(603, 6, 'হবিগঞ্জ', 'Habiganj', 'habiganj'),
(604, 6, 'সুনামগঞ্জ', 'Sunamganj', 'sunamganj'),
-- Rangpur Division (8)
(701, 7, 'রংপুর', 'Rangpur', 'rangpur'),
(702, 7, 'দিনাজপুর', 'Dinajpur', 'dinajpur'),
(703, 7, 'কুড়িগ্রাম', 'Kurigram', 'kurigram'),
(704, 7, 'গাইবান্ধা', 'Gaibandha', 'gaibandha'),
(705, 7, 'নীলফামারী', 'Nilphamari', 'nilphamari'),
(706, 7, 'লালমনিরহাট', 'Lalmonirhat', 'lalmonirhat'),
(707, 7, 'পঞ্চগড়', 'Panchagarh', 'panchagarh'),
(708, 7, 'ঠাকুরগাঁও', 'Thakurgaon', 'thakurgaon'),
-- Mymensingh Division (4)
(801, 8, 'ময়মনসিংহ', 'Mymensingh', 'mymensingh'),
(802, 8, 'জামালপুর', 'Jamalpur', 'jamalpur'),
(803, 8, 'নেত্রকোণা', 'Netrokona', 'netrokona'),
(804, 8, 'শেরপুর', 'Sherpur', 'sherpur')
ON DUPLICATE KEY UPDATE `name_bn`=VALUES(`name_bn`), `name_en`=VALUES(`name_en`);

-- 4. 14 Main Menu Categories
INSERT INTO `categories` (`id`, `name_bn`, `name_en`, `slug`, `parent_id`, `sort_order`, `is_menu`) VALUES
(1, 'জাতীয়', 'National', 'national', NULL, 1, 1),
(2, 'রাজনীতি', 'Politics', 'politics', NULL, 2, 1),
(3, 'সারাদেশ', 'Countrywide', 'saradesh', NULL, 3, 1),
(4, 'অর্থনীতি', 'Economy', 'economy', NULL, 4, 1),
(5, 'আন্তর্জাতিক', 'International', 'international', NULL, 5, 1),
(6, 'খেলা', 'Sports', 'sports', NULL, 6, 1),
(7, 'বিনোদন', 'Entertainment', 'entertainment', NULL, 7, 1),
(8, 'বিজ্ঞান ও প্রযুক্তি', 'Science & Tech', 'tech', NULL, 8, 1),
(9, 'শিক্ষা', 'Education', 'education', NULL, 9, 1),
(10, 'স্বাস্থ্য', 'Health', 'health', NULL, 10, 1),
(11, 'চাকরি', 'Jobs', 'jobs', NULL, 11, 1),
(12, 'মতামত', 'Opinion', 'opinion', NULL, 12, 1),
(13, 'লাইফস্টাইল', 'Lifestyle', 'lifestyle', NULL, 13, 1),
(14, 'প্রবাস', 'Probash', 'probash', NULL, 14, 1),
(15, 'অপরাধ', 'Crime', 'crime', NULL, 15, 1)
ON DUPLICATE KEY UPDATE `name_bn`=VALUES(`name_bn`), `slug`=VALUES(`slug`);

-- 5. Default Site Settings
INSERT INTO `settings` (`key`, `value`) VALUES
('site_name_bn', 'নিউজলেন্সবিডি'),
('site_name_en', 'Newslensbd'),
('tagline_bn', 'সাধারণের বাইরে, সত্যের খোঁজে'),
('tagline_en', 'News Beyond the Ordinary'),
('color_primary', '#0b1f44'),
('color_secondary', '#0E7A3A'),
('color_accent', '#E31E24'),
('contact_email', 'info@newslensbd.com'),
('tech_partner_name', 'Stratifyx Global'),
('tech_partner_url', 'https://stratifyxglobal.com'),
('breaking_news_active', '1'),
('maintenance_mode', '0')
ON DUPLICATE KEY UPDATE `value`=VALUES(`value`);

-- 6. Interactive Poll & Options
INSERT INTO `polls` (`id`, `question`, `is_active`, `created_at`) VALUES
(1, 'আসন্ন সার্বিক পরিস্থিতিতে রাজনৈতিক দলগুলোর ধারাবাহিক সংলাপ কি বিদ্যমান সংকট নিরসনে ভূমিকা রাখবে?', 1, NOW())
ON DUPLICATE KEY UPDATE `question`=VALUES(`question`);

INSERT INTO `poll_options` (`id`, `poll_id`, `option_text`, `votes`) VALUES
(1, 1, 'হ্যাঁ, ইতিবাচক সমাধান আসবে', 420),
(2, 1, 'না, কোনো পরিবর্তন হবে না', 185),
(3, 1, 'মন্তব্য নেই / নিশ্চিত নই', 45)
ON DUPLICATE KEY UPDATE `option_text`=VALUES(`option_text`);

-- 7. Dummy Ads (Both Picture & Video)
DELETE FROM `ads` WHERE `id` <= 10;
INSERT INTO `ads` (`id`, `title`, `position`, `type`, `image`, `video_url`, `link`, `is_active`, `impressions`, `clicks`, `created_at`) VALUES
(1, 'প্রিমিয়াম স্মার্ট টেকনোলজি ও ইলেকট্রনিক্স মেগা অফার', 'header', 'image', 'https://images.unsplash.com/photo-1550009158-9ebf69173e03?auto=format&fit=crop&w=728&h=90&q=80', NULL, 'https://stratifyxglobal.com', 1, 140, 22, NOW()),
(2, 'Stratifyx Global - স্মার্ট ওয়েব ও সফটওয়্যার সলিউশন', 'sidebar', 'image', 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=600&q=80', NULL, 'https://stratifyxglobal.com', 1, 260, 48, NOW()),
(3, 'ক্যারিয়ার ডেভেলপমেন্ট ও প্রফেশনাল ট্রেনিং একাডেমি', 'in_article', 'image', 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=728&h=90&q=80', NULL, 'https://example.com/training', 1, 95, 14, NOW()),
(4, 'আন্তর্জাতিক ট্রাভেল ও হলিডে প্যাকেজে বিশেষ ছাড়', 'footer', 'image', 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=970&h=90&q=80', NULL, 'https://example.com/travel', 1, 110, 19, NOW());

-- 8. 14 Category-Specific News Articles + User Added News (ID 15)
DELETE FROM `posts` WHERE `id` <= 15;
INSERT INTO `posts` (`id`, `title`, `subtitle`, `slug`, `body`, `excerpt`, `featured_image`, `video_url`, `category_id`, `district_id`, `author_id`, `status`, `is_lead`, `is_breaking`, `views`, `published_at`, `created_at`) VALUES
(1, 'পদ্মা সেতু ও মেট্রোরেলের পর এবার মাতারবাড়ী গভীর সমুদ্র বন্দর চালু হচ্ছে: যোগাযোগের নতুন দিগন্ত', 'দেশের প্রথম গভীর সমুদ্র বন্দর বাণিজ্য ও অর্থনীতিতে আনবে বৈপ্লবিক পরিবর্তন', 'matarbari-deep-sea-port-inauguration-bangladesh', '<p>বাংলাদেশের অবকাঠামোগত উন্নয়নে যুক্ত হতে চলেছে আরেকটি যুগান্তকারী মাইলফলক। পদ্মা বহুমুখী সেতু, রূপপুর পারমাণবিক বিদ্যুৎ কেন্দ্র ও মেট্রোরেলের অভাবনীয় সাফল্যের পর এবার বহুল প্রতীক্ষিত মাতারবাড়ী গভীর সমুদ্র বন্দর বাণিজ্যিকভাবে উন্মুক্ত হতে যাচ্ছে।</p><p>বিশেষজ্ঞরা জানিয়েছেন, এই বন্দর পুরোপুরি চালু হলে ১৬ থেকে ১৮ মিটার ড্রাফটের বিশালাকার মাদার ভেসেল সরাসরি বাংলাদেশে ভিড়তে পারবে। এর ফলে সিঙ্গাপুর বা কলম্বো বন্দরের ওপর নির্ভরতা হ্রাস পাবে এবং আমদানি-রপ্তানি পরিবহন ব্যয় এক-তৃতীয়াংশ কমে যাবে।</p>', 'পদ্মা সেতু ও মেট্রোরেলের পর মাতারবাড়ী গভীর সমুদ্র বন্দর বাণিজ্যিক যাত্রার জন্য পুরোপুরি প্রস্তুত। এটি দেশের লজিস্টিক ও অর্থনীতিতে নতুন দিগন্ত উন্মোচন করবে।', 'https://images.unsplash.com/photo-1541872703-74c5e44368f9?auto=format&fit=crop&w=800&q=80', NULL, 1, 101, 1, 'published', 1, 1, 3840, NOW() - INTERVAL 1 HOUR, NOW()),
(2, 'নির্বাচন কমিশনের নতুন রোডম্যাপ ঘোষণা: রাজনৈতিক দলগুলোর সঙ্গে ধারাবাহিক সংলাপের উদ্যোগ', 'অংশগ্রহণমূলক নির্বাচনের পরিবেশ নিশ্চিতে সব দলের মতামত নেওয়ার আশ্বাস', 'election-commission-announces-new-roadmap-and-dialogue', '<p>নির্বাচন কমিশন (ইসি) আগামী জাতীয় সংসদ নির্বাচনকে সামনে রেখে তাদের পূর্ণাঙ্গ কর্মপরিকল্পনা ও রোডম্যাপ আনুষ্ঠানিকভাবে ঘোষণা করেছে। প্রধান নির্বাচন কমিশনার জানান, সকল নিবন্ধিত রাজনৈতিক দল, সুশীল সমাজ ও গণমাধ্যম প্রতিনিধিদের সঙ্গে ধাপে ধাপে সংলাপের আয়োজন করা হবে।</p>', 'আগামী সাধারণ নির্বাচনকে অবাধ ও নিরপেক্ষ করতে রাজনৈতিক দলগুলোর সঙ্গে উন্মুক্ত সংলাপের রোডম্যাপ ঘোষণা করেছে নির্বাচন কমিশন।', 'https://images.unsplash.com/photo-1529107386315-e1a2ed48a620?auto=format&fit=crop&w=800&q=80', NULL, 2, 101, 1, 'published', 0, 1, 2450, NOW() - INTERVAL 3 HOUR, NOW()),
(3, 'শীতের আমেজে উত্তরের জনপদে আগাম সবজির বাম্পার ফলন: কৃষকদের মুখে স্বস্তির হাসি', 'রংপুর ও দিনাজপুরের বিস্তীর্ণ মাঠে রঙিন শিম, ফুলকপি ও টমেটোর প্রাচুর্য', 'winter-vegetable-bumper-harvest-in-northern-districts', '<p>শীতের আগমনী বার্তার সাথে সাথে দেশের উত্তরাঞ্চলে আগাম শীতকালীন শাকসবজির বাম্পার ফলনে উৎসবমুখর পরিবেশ বিরাজ করছে। রংপুর, দিনাজপুর এবং গাইবান্ধার মাঠজুড়ে সবুজ ফুলকপি, বাঁধাকপি, শিম, মুলা ও লাল টমেটোতে ভরে উঠেছে খেত।</p>', 'অনুকূল আবহাওয়া ও আধুনিক কৃষি প্রযুক্তিতে উত্তরাঞ্চলের ৮ জেলায় শীতের আগাম সবজির রেকর্ড ফলন অর্জিত হয়েছে।', 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&w=800&q=80', NULL, 3, 701, 1, 'published', 0, 0, 1620, NOW() - INTERVAL 5 HOUR, NOW()),
(4, 'রেমিট্যান্স প্রবাহে নতুন রেকর্ড: গত মাসে দেশে এলো ২৪০ কোটি ডলারের প্রবাসী আয়', 'বৈধ পথে প্রণোদনা ও ব্যাংকিং চ্যানেলে সুবিধা বৃদ্ধি পাওয়ায় ইতিবাচক প্রভাব', 'remittance-inflow-reaches-record-high-economy-boost', '<p>বৈধ ব্যাংকিং চ্যানেলে রেমিট্যান্স পাঠানোর হার ক্রমাগত বৃদ্ধি পাওয়ায় বৈদেশিক মুদ্রার রিজার্ভে প্রাণচাঞ্চল্য ফিরে এসেছে। বাংলাদেশ ব্যাংকের হালনাগাদ তথ্যানুযায়ী, সদ্য সমাপ্ত মাসে প্রবাসী বাংলাদেশিরা ২৪০ কোটি মার্কিন ডলারেরও বেশি রেমিট্যান্স পাঠিয়েছেন।</p>', 'প্রবাসী আয়ে জোয়ার: এক মাসে ২৪০ কোটি ডলার দেশে আসায় বৈদেশিক মুদ্রার রিজার্ভ শক্তিশালী হয়েছে এবং ডলারের বাজারে স্বস্তি ফিরেছে।', 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?auto=format&fit=crop&w=800&q=80', NULL, 4, 101, 1, 'published', 1, 0, 4120, NOW() - INTERVAL 7 HOUR, NOW()),
(5, 'জলবায়ু সম্মেলনে বিশ্বনেতাদের ঐতিহাসিক চুক্তি: ক্ষতিগ্রস্ত দেশগুলোর জন্য বিশেষ ক্ষতিপূরণ তহবিল', 'কার্বন নিঃসরণ কমানো ও নবায়নযোগ্য শক্তিতে রূপান্তরের সময়সীমা নির্ধারণ', 'cop-climate-summit-historic-loss-and-damage-fund-deal', '<p>আন্তর্জাতিক জলবায়ু সম্মেলনে বিশ্বনেতারা জলবায়ু পরিবর্তনের শিকার দেশগুলোর জন্য একটি বিশেষ ‘ক্ষতিপূরণ ও পুনর্গঠন তহবিল’ (Loss and Damage Fund) কার্যকর করতে সর্বসম্মত চুক্তিতে পৌঁছেছেন।</p>', 'ঐতিহাসিক জলবায়ু চুক্তি স্বাক্ষরিত: ক্ষতিগ্রস্ত উন্নয়নশীল দেশগুলোর জন্য আন্তর্জাতিক সহায়তা তহবিল বাস্তবায়নের আনুষ্ঠানিক সিদ্ধান্ত।', 'https://images.unsplash.com/photo-1569163139599-0f4517e36f51?auto=format&fit=crop&w=800&q=80', NULL, 5, NULL, 1, 'published', 0, 1, 2980, NOW() - INTERVAL 9 HOUR, NOW()),
(6, 'মিরপুরে শ্বাসরুদ্ধকর শেষ ওভারে নাটকীয় জয়: সিরিজ নিশ্চিত করল টাইগাররা', 'অলরাউন্ড পারফরম্যান্সে দর্শকদের উল্লাস, সেরা খেলোয়াড় নির্বাচিত হলেন তরুণ পেসার', 'bangladesh-cricket-thrilling-last-over-win-mirpur', '<p>হোম অব ক্রিকেট মিরপুর শের-ই-বাংলা জাতীয় ক্রিকেট স্টেডিয়ামে টানটান উত্তেজনার ম্যাচে সফরকারীদের ৪ রানে হারিয়ে দ্বিপাক্ষিক সিরিজ নিজেদের করে নিয়েছে বাংলাদেশ জাতীয় ক্রিকেট দল।</p>', 'শেষ ওভারে শ্বাসরুদ্ধকর লড়াইয়ে সফরকারী দলকে পরাস্ত করে ১ ম্যাচ হাতে রেখেই সিরিজ জয় নিশ্চিত করল বাংলাদেশ দল।', 'https://images.unsplash.com/photo-1540747913346-19e32dc3e97e?auto=format&fit=crop&w=800&q=80', NULL, 6, 101, 1, 'published', 0, 0, 4890, NOW() - INTERVAL 11 HOUR, NOW()),
(7, 'আন্তর্জাতিক চলচ্চিত্র উৎসবে সেরা চলচ্চিত্রের সম্মাননা পেল বাংলাদেশি সিনেমা', 'মৌলিক গল্প ও বাস্তবধর্মী চিত্রনাট্যের জন্য জুরি বোর্ডের ভূয়সী প্রশংসা', 'bangladeshi-film-wins-prestigious-award-at-international-festival', '<p>বিশ্বমঞ্চে আবারও উজ্জ্বল হলো বাংলাদেশের শিল্প ও সংস্কৃতি। মর্যাদাপূর্ণ আন্তর্জাতিক চলচ্চিত্র উৎসবে সেরা ফিচার ফিল্মের সর্বোচ্চ পুরস্কার জিতে নিয়েছে বাংলাদেশি নির্মাতার নির্মিত বহুল প্রশংসিত চলচ্চিত্র।</p>', 'বিদেশের মাটিতে বাংলা সিনেমার ঐতিহাসিক গৌরব: আন্তর্জাতিক উৎসবে সেরা চলচ্চিত্র হিসেবে স্বীকৃতি লাভ।', 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=800&q=80', NULL, 7, 101, 1, 'published', 0, 0, 1850, NOW() - INTERVAL 13 HOUR, NOW()),
(8, 'কৃত্রিম বুদ্ধিমত্তার মাধ্যমে বাংলা ভাষার ন্যাচারাল ল্যাঙ্গুয়েজ মডেল উন্মোচন করলেন তরুণ প্রকৌশলীরা', 'বাংলা ব্যাকরণ, অনুবাদ ও নথি বিশ্লেষণের জন্য উন্মুক্ত এআই প্ল্যাটফর্ম', 'bangla-ai-large-language-model-unveiled-by-engineers', '<p>তথ্যপ্রযুক্তির যুগে বাংলা ভাষার ডিজিটাইজেশনে নতুন এক মাইলফলক রচিত হলো। দেশীয় সফটওয়্যার প্রকৌশলী ও গবেষকদের যৌথ উদ্যোগে নির্মিত হয়েছে কৃত্রিম বুদ্ধিমত্তা চালিত প্রথম পূর্ণাঙ্গ বাংলা লার্জ ল্যাঙ্গুয়েজ মডেল (LLM)।</p>', 'বাংলা ভাষার নিজস্ব এআই মডেল উন্মোচন: নির্ভুল অনুবাদ ও গবেষণায় নতুন সম্ভাবনার দ্বার উন্মুক্ত হলো দেশে।', 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=80', NULL, 8, 101, 1, 'published', 0, 0, 3340, NOW() - INTERVAL 15 HOUR, NOW()),
(9, 'নতুন শিক্ষাক্রমে দক্ষতাভিত্তিক মূল্যায়ন পদ্ধতি: শিক্ষকদের দেশব্যাপী বিশেষ প্রশিক্ষণ কর্মসূচি শুরু', 'মুখস্থবিদ্যার বদলে ব্যাবহারিক জ্ঞান ও সমস্যা সমাধানের দক্ষতায় গুরুত্ব', 'education-curriculum-teachers-skill-training-countrywide', '<p>শিক্ষা ব্যবস্থাকে আধুনিক ও বৈশ্বিক চাহিদার সাথে সঙ্গতিপূর্ণ করতে প্রাথমিক ও মাধ্যমিক স্তরে শিক্ষকদের দক্ষতাভিত্তিক বিশেষ প্রশিক্ষণ কর্মসূচি দেশব্যাপী শুরু হয়েছে।</p>', 'মুখস্থ নির্ভরতা পরিহার করে কর্মমুখী ও আধুনিক শিক্ষার বিস্তার ঘটাতে সারা দেশে শিক্ষকদের ব্যাপক প্রশিক্ষণ কার্যক্রম পরিচালিত হচ্ছে।', 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=800&q=80', NULL, 9, 101, 1, 'published', 0, 0, 1420, NOW() - INTERVAL 17 HOUR, NOW()),
(10, 'মৌসুমি রোগ প্রতিরোধে দেশব্যাপী পরিচ্ছন্নতা কার্যক্রম: হাসপাতালগুলোতে পর্যাপ্ত ওষুধ ও শয্যা প্রস্তুত', 'জনস্বাস্থ্য অধিদপ্তরের বিশেষ গাইডলাইন এবং প্রতিটি ওয়ার্ডে সচেতনতামূলক মেডিকেল টিম', 'health-directorate-guidelines-and-hospital-preparedness', '<p>মৌসুম পরিবর্তনের কারণে ডেঙ্গু ও ভাইরাসজনিত জ্বরের প্রাদুর্ভাব রোধে সারা দেশে একযোগে পরিচ্ছন্নতা ও জনসচেতনতামূলক কার্যক্রম জোরদার করা হয়েছে।</p>', 'জনস্বাস্থ্য সুরক্ষায় সমন্বিত উদ্যোগ: হাসপাতালগুলোতে সার্বক্ষণিক চিকিৎসক সেবা ও বিনামূল্যে প্রয়োজনীয় ওষুধ সরবরাহ চালু।', 'https://images.unsplash.com/photo-1538108149393-fbbd81895907?auto=format&fit=crop&w=800&q=80', NULL, 10, 101, 1, 'published', 0, 1, 2710, NOW() - INTERVAL 19 HOUR, NOW()),
(11, 'বিসিএস ও সরকারি ব্যাংকে বৃহৎ নিয়োগ বিজ্ঞপ্তি: অনলাইনে আবেদনের শেষ তারিখ ও পূর্ণাঙ্গ নির্দেশিকা', 'বিভিন্ন ক্যাডারে হাজারো শূন্যপদের বিপরীতে অনলাইনে আবেদন প্রক্রিয়া শুরু', 'bcs-and-public-bank-recruitment-circular-application-guide', '<p>সরকারি কর্ম কমিশন (পিএসসি) ও ব্যাংকার্স সিলেকশন কমিটি সচিবালয় স্নাতক সম্পন্নকারী চাকরিপ্রার্থীদের জন্য নতুন নিয়োগ বিজ্ঞপ্তি প্রকাশ করেছে।</p>', 'সরকারি চাকরিপ্রার্থীদের জন্য সুখবর: পিএসসি ও ব্যাংকিং খাতের নতুন নিয়োগে আবেদনের নিয়মাবলী ও প্রস্তুতি গাইড।', 'https://images.unsplash.com/photo-1486312338219-ce68d2c6f44d?auto=format&fit=crop&w=800&q=80', NULL, 11, 101, 1, 'published', 0, 0, 5210, NOW() - INTERVAL 21 HOUR, NOW()),
(12, 'তারুণ্যের উদ্দীপনা ও তথ্যপ্রযুক্তিনির্ভর আগামীর বাংলাদেশ বিনির্মাণে আমাদের করণীয়', 'জ্ঞানভিত্তিক সমাজ ও কর্মসংস্থান সৃষ্টিতে তরুণ সমাজের নেতৃত্বই সবচেয়ে বড় চালিকাশক্তি', 'opinion-youth-empowerment-and-building-future-bangladesh', '<p>একটি দেশের প্রকৃত শক্তি নিহিত থাকে তার তরুণ সমাজের মেধা, সততা ও উদ্ভাবনী শক্তির মাঝে। চতুর্থ শিল্প বিপ্লবের এই যুগে রোবটিক্স ও এআই-তে বাংলাদেশের তরুণরা সক্ষমতা প্রমাণ করছে।</p>', 'বিশেষ কলাম: তরুণ প্রজন্মকে সঠিক প্রযুক্তি ও শিক্ষার আলোয় আলোকিত করে দেশকে সমৃদ্ধির পথে এগিয়ে নেওয়ার কৌশল।', 'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=800&q=80', NULL, 12, 101, 1, 'published', 0, 0, 1340, NOW() - INTERVAL 23 HOUR, NOW()),
(13, 'শীতকালীন স্বাস্থ্য ও ত্বকের বিশেষ যত্ন: বিশেষজ্ঞদের ৫টি জরুরি দৈনন্দিন পরামর্শ', 'শুষ্ক আবহাওয়ায় সুস্থ থাকতে প্রচুর পানি পান ও পুষ্টিকর খাদ্য তালিকার গুরুত্ব', 'winter-skin-care-and-healthy-lifestyle-expert-tips', '<p>শীতের শুষ্ক আবহাওয়ায় ত্বক ও চুলের আর্দ্রতা ধরে রাখা অত্যন্ত জরুরি। পুষ্টিবিদ ও চর্মরোগ বিশেষজ্ঞরা এই মৌসুমে প্রতিদিন পর্যাপ্ত পানি ও স্যুপ পান করার পরামর্শ দিচ্ছেন।</p>', 'শীতের দিনে ত্বকের সুরক্ষা ও সতেজতা বজায় রাখতে বিশেষজ্ঞ চিকিৎসকদের সহজ এবং প্রাকৃতিক পাঁচটি কার্যকরী টিপস।', 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=800&q=80', NULL, 13, 101, 1, 'published', 0, 0, 1950, NOW() - INTERVAL 25 HOUR, NOW()),
(14, 'মালয়েশিয়া ও মধ্যপ্রাচ্যে দক্ষ জনশক্তি রপ্তানিতে নতুন সুযোগ: কর্মীদের সুরক্ষায় দ্বিপাক্ষিক চুক্তি', 'ন্যূনতম বেতন নির্ধারণ, হেলথ ইন্স্যুরেন্স ও নিরাপদ কর্মপরিবেশ নিশ্চিতে কঠোর শর্ত', 'probash-malaysia-middle-east-skilled-manpower-agreement', '<p>প্রবাসী কল্যাণ ও বৈদেশিক কর্মসংস্থান মন্ত্রণালয়ের কূটনৈতিক প্রচেষ্টায় মালয়েশিয়া ও মধ্যপ্রাচ্যের দেশগুলোতে বাংলাদেশের দক্ষ কর্মীদের জন্য কর্মসংস্থানের নতুন ক্ষেত্র প্রস্তুত হয়েছে।</p>', 'প্রবাসী কর্মীদের নিরাপত্তা ও উচ্চ আয়ের সুযোগ নিশ্চিত করতে বন্ধুভাবাপন্ন দেশগুলোর সাথে স্বাক্ষরিত হলো ঐতিহাসিক শ্রম চুক্তি।', 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=800&q=80', NULL, 14, 201, 1, 'published', 0, 0, 2640, NOW() - INTERVAL 27 HOUR, NOW());

-- 9. Institutional Static Pages (6 Core Pages)
INSERT INTO `pages` (`id`, `slug`, `title`, `title_en`, `badge`, `content`, `meta_title`, `meta_desc`, `status`) VALUES
(1, 'about', 'আমাদের সম্পর্কে (About Us)', 'About Us', 'পরিচিতি', '<p><strong>নিউজলেন্সবিডি (Newslensbd)</strong> বাংলাদেশের একটি অগ্রণী, বস্তুনিষ্ঠ ও দায়িত্বশীল ডিজিটাল সংবাদ মাধ্যম। আমাদের মূল লক্ষ্য—"সাধারণের বাইরে, সত্যের খোঁজে"—পাঠকের নিকট নিরপেক্ষ ও যাচাইকৃত সংবাদ পরিবেশন করা।</p>', 'আমাদের সম্পর্কে | Newslensbd', 'নিউজলেন্সবিডি বাংলাদেশের একটি অগ্রণী, বস্তুনিষ্ঠ ও দায়িত্বশীল ডিজিটাল সংবাদ মাধ্যম।', 'published'),
(2, 'contact', 'যোগাযোগ (Contact Us)', 'Contact Us', 'বার্তা পাঠান', '<p>নিউজলেন্সবিডির সাথে যেকোনো তথ্যের আদান-প্রদান, সংবাদ টিপস বা মতামতের জন্য যোগাযোগ করুন: <strong>info@newslensbd.com</strong></p>', 'যোগাযোগ | Newslensbd', 'নিউজলেন্সবিডি প্রধান কার্যালয় ও বার্তা বিভাগের সাথে যোগাযোগের মাধ্যমসমূহ।', 'published'),
(3, 'advertise', 'বিজ্ঞাপন ও বিপণন (Advertise with Us)', 'Advertise with Us', 'প্রচার ও প্রসার', '<p>নিউজলেন্সবিডিতে বিজ্ঞাপন দিয়ে আপনার ব্র্যান্ড ও পণ্যের প্রচার পৌঁছে দিন প্রতিদিনের লাখো সচেতন পাঠকের কাছে। যোগাযোগ: <strong>ads@newslensbd.com</strong></p>', 'বিজ্ঞাপন ও বিপণন | Newslensbd', 'নিউজলেন্সবিডি ডিজিটাল পোর্টালে বিজ্ঞাপন ও স্পন্সরশিপের সুযোগসমূহ।', 'published'),
(4, 'privacy', 'গোপনীয়তা নীতি (Privacy Policy)', 'Privacy Policy', 'নিরাপত্তা', '<p>নিউজলেন্সবিডি পাঠকদের তথ্যের সর্বোচ্চ সুরক্ষা ও গোপনীয়তা বজায় রাখতে প্রতিশ্রুতিবদ্ধ।</p>', 'গোপনীয়তা নীতি | Newslensbd', 'নিউজলেন্সবিডির পাঠকদের তথ্যের সুরক্ষা ও গোপনীয়তা নীতি।', 'published'),
(5, 'terms', 'ব্যবহারের শর্তাবলী (Terms of Service)', 'Terms of Service', 'শর্তাবলী', '<p>নিউজলেন্সবিডি ওয়েবসাইটে প্রকাশিত সকল সংবাদ, নিবন্ধ, ছবি ও ভিডিও কনটেন্ট কপিরাইট আইনের আওতাভুক্ত।</p>', 'ব্যবহারের শর্তাবলী | Newslensbd', 'নিউজলেন্সবিডি পোর্টাল ব্যবহারের নিয়মাবলী ও শর্তাবলী।', 'published'),
(6, 'corrections', 'সংশোধনী নীতি (Corrections Policy)', 'Corrections Policy', 'স্বচ্ছতা', '<p>নিউজলেন্সবিডি সঠিক ও বস্তুনিষ্ঠ সংবাদ পরিবেশনে সর্বদা সচেষ্ট। কোনো সংবাদে তথ্যের অসঙ্গতি চোখে পড়লে ইমেইল করুন: <strong>corrections@newslensbd.com</strong></p>', 'সংশোধনী নীতি | Newslensbd', 'নিউজলেন্সবিডির সাংবাদিকতার সততা ও তথ্য সংশোধনী নীতি।', 'published')
ON DUPLICATE KEY UPDATE `title`=VALUES(`title`), `title_en`=VALUES(`title_en`), `badge`=VALUES(`badge`), `status`=VALUES(`status`);

SET FOREIGN_KEY_CHECKS = 1;

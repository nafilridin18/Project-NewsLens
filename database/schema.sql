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
    `role` ENUM('super_admin', 'editor', 'sub_editor', 'reporter', 'district_rep') NOT NULL DEFAULT 'reporter',
    `district_id` INT UNSIGNED NULL,
    `photo` VARCHAR(255) NULL,
    `bio` TEXT NULL,
    `status` ENUM('active', 'inactive', 'banned') NOT NULL DEFAULT 'active',
    `last_login` DATETIME NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_users_role` (`role`),
    INDEX `idx_users_status` (`status`)
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
    `status` ENUM('draft', 'pending', 'published', 'scheduled', 'rejected') NOT NULL DEFAULT 'draft',
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

-- 20. Security: Password Resets
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
    `email` VARCHAR(191) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_pwd_resets_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

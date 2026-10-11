-- =====================================================================
-- Newslensbd (à¦¨à¦¿à¦‰à¦œà¦²à§‡à¦¨à§à¦¸à¦¬à¦¿à¦¡à¦¿) Database Schema
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

-- 2. Divisions table (à§® à¦¬à¦¿à¦­à¦¾à¦—)
DROP TABLE IF EXISTS `divisions`;
CREATE TABLE `divisions` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name_bn` VARCHAR(100) NOT NULL,
    `name_en` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Districts table (à§¬à§ª à¦œà§‡à¦²à¦¾)
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

-- 4. Upazilas table (à¦‰à¦ªà¦œà§‡à¦²à¦¾)
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

-- 5. Categories table (à§§à§ªà¦Ÿà¦¿ à¦®à§‚à¦² à¦•à§à¦¯à¦¾à¦Ÿà¦¾à¦—à¦°à¦¿ à¦“ à¦¸à¦¾à¦¬-à¦•à§à¦¯à¦¾à¦Ÿà¦¾à¦—à¦°à¦¿)
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

-- 6. Posts table (à¦–à¦¬à¦°)
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

-- 9. Media table (à¦®à¦¿à¦¡à¦¿à¦¯à¦¼à¦¾ à¦²à¦¾à¦‡à¦¬à§à¦°à§‡à¦°à¦¿)
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

-- 10. Comments table (à¦®à¦¨à§à¦¤à¦¬à§à¦¯)
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

-- 11. Polls table (à¦…à¦¨à¦²à¦¾à¦‡à¦¨ à¦œà¦°à¦¿à¦ª)
DROP TABLE IF EXISTS `polls`;
CREATE TABLE `polls` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `question` VARCHAR(255) NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `end_at` DATETIME NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_polls_active` (`is_active`, `end_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Poll Options table (à¦œà¦°à¦¿à¦ªà§‡à¦° à¦…à¦ªà¦¶à¦¨)
DROP TABLE IF EXISTS `poll_options`;
CREATE TABLE `poll_options` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `poll_id` INT UNSIGNED NOT NULL,
    `option_text` VARCHAR(255) NOT NULL,
    `votes` INT UNSIGNED NOT NULL DEFAULT 0,
    INDEX `idx_poll_options_poll` (`poll_id`),
    CONSTRAINT `fk_poll_options_poll` FOREIGN KEY (`poll_id`) REFERENCES `polls` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. Poll Votes table (à¦­à§‹à¦Ÿ à¦Ÿà§à¦°à§à¦¯à¦¾à¦•à¦¿à¦‚)
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

-- 14. Ads table (à¦¬à¦¿à¦œà§à¦žà¦¾à¦ªà¦¨)
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

-- 15. Settings table (à¦¸à¦¾à¦‡à¦Ÿ à¦¸à§‡à¦Ÿà¦¿à¦‚à¦¸)
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
    `key` VARCHAR(100) NOT NULL PRIMARY KEY,
    `value` LONGTEXT NULL,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 16. Activity Logs table (à¦²à¦— à¦“ à¦…à¦¡à¦¿à¦Ÿ à¦Ÿà§à¦°à§‡à¦‡à¦²)
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

-- 17. Newsletter Subscribers table (à¦¨à¦¿à¦‰à¦œà¦²à§‡à¦Ÿà¦¾à¦° à¦¸à¦¾à¦¬à¦¸à§à¦•à§à¦°à¦¾à¦‡à¦¬à¦¾à¦°)
DROP TABLE IF EXISTS `newsletter_subscribers`;
CREATE TABLE `newsletter_subscribers` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(191) NOT NULL UNIQUE,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `token` VARCHAR(64) NULL,
    `subscribed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 18. Static Pages table (à¦¸à§à¦Ÿà§à¦¯à¦¾à¦Ÿà¦¿à¦• à¦ªà§‡à¦œ: à¦†à¦®à¦¾à¦¦à§‡à¦° à¦¸à¦®à§à¦ªà¦°à§à¦•à§‡, à¦¶à¦°à§à¦¤à¦¾à¦¬à¦²à§€ à¦‡à¦¤à§à¦¯à¦¾à¦¦à¦¿)
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

-- 19. Security: Login Attempts table (à¦¬à§à¦°à§à¦Ÿ à¦«à§‹à¦°à§à¦¸ à¦ªà§à¦°à¦¤à¦¿à¦°à§‹à¦§)
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
-- =====================================================================
-- Newslensbd (à¦¨à¦¿à¦‰à¦œà¦²à§‡à¦¨à§à¦¸à¦¬à¦¿à¦¡à¦¿) Complete Seed Data
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
(1, 'à¦¢à¦¾à¦•à¦¾', 'Dhaka', 'dhaka'),
(2, 'à¦šà¦Ÿà§à¦Ÿà¦—à§à¦°à¦¾à¦®', 'Chattogram', 'chattogram'),
(3, 'à¦°à¦¾à¦œà¦¶à¦¾à¦¹à§€', 'Rajshahi', 'rajshahi'),
(4, 'à¦–à§à¦²à¦¨à¦¾', 'Khulna', 'khulna'),
(5, 'à¦¬à¦°à¦¿à¦¶à¦¾à¦²', 'Barishal', 'barishal'),
(6, 'à¦¸à¦¿à¦²à§‡à¦Ÿ', 'Sylhet', 'sylhet'),
(7, 'à¦°à¦‚à¦ªà§à¦°', 'Rangpur', 'rangpur'),
(8, 'à¦®à¦¯à¦¼à¦®à¦¨à¦¸à¦¿à¦‚à¦¹', 'Mymensingh', 'mymensingh')
ON DUPLICATE KEY UPDATE `name_bn`=VALUES(`name_bn`);

-- 3. Districts
INSERT INTO `districts` (`id`, `division_id`, `name_bn`, `name_en`, `slug`) VALUES
(101, 1, 'à¦¢à¦¾à¦•à¦¾', 'Dhaka', 'dhaka'),
(102, 1, 'à¦—à¦¾à¦œà§€à¦ªà§à¦°', 'Gazipur', 'gazipur'),
(103, 1, 'à¦¨à¦¾à¦°à¦¾à¦¯à¦¼à¦£à¦—à¦žà§à¦œ', 'Narayanganj', 'narayanganj'),
(104, 1, 'à¦¨à¦°à¦¸à¦¿à¦‚à¦¦à§€', 'Narsingdi', 'narsingdi'),
(105, 1, 'à¦®à§à¦¨à§à¦¸à§€à¦—à¦žà§à¦œ', 'Munshiganj', 'munshiganj'),
(106, 1, 'à¦®à¦¾à¦¨à¦¿à¦•à¦—à¦žà§à¦œ', 'Manikganj', 'manikganj'),
(107, 1, 'à¦Ÿà¦¾à¦™à§à¦—à¦¾à¦‡à¦²', 'Tangail', 'tangail'),
(108, 1, 'à¦•à¦¿à¦¶à§‹à¦°à¦—à¦žà§à¦œ', 'Kishoreganj', 'kishoreganj'),
(109, 1, 'à¦«à¦°à¦¿à¦¦à¦ªà§à¦°', 'Faridpur', 'faridpur'),
(201, 2, 'à¦šà¦Ÿà§à¦Ÿà¦—à§à¦°à¦¾à¦®', 'Chattogram', 'chattogram'),
(202, 2, 'à¦•à¦•à§à¦¸à¦¬à¦¾à¦œà¦¾à¦°', 'Coxs Bazar', 'coxs-bazar'),
(203, 2, 'à¦•à§à¦®à¦¿à¦²à§à¦²à¦¾', 'Cumilla', 'cumilla'),
(204, 2, 'à¦«à§‡à¦¨à§€', 'Feni', 'feni'),
(205, 2, 'à¦¬à§à¦°à¦¾à¦¹à§à¦®à¦£à¦¬à¦¾à¦¡à¦¼à¦¿à¦¯à¦¼à¦¾', 'Brahmanbaria', 'brahmanbaria'),
(206, 2, 'à¦¨à§‹à¦¯à¦¼à¦¾à¦–à¦¾à¦²à§€', 'Noakhali', 'noakhali'),
(207, 2, 'à¦šà¦¾à¦à¦¦à¦ªà§à¦°', 'Chandpur', 'chandpur'),
(301, 3, 'à¦°à¦¾à¦œà¦¶à¦¾à¦¹à§€', 'Rajshahi', 'rajshahi'),
(302, 3, 'à¦¬à¦—à§à¦¡à¦¼à¦¾', 'Bogura', 'bogura'),
(303, 3, 'à¦ªà¦¾à¦¬à¦¨à¦¾', 'Pabna', 'pabna'),
(304, 3, 'à¦¸à¦¿à¦°à¦¾à¦œà¦—à¦žà§à¦œ', 'Sirajganj', 'sirajganj'),
(305, 3, 'à¦¨à¦“à¦—à¦¾à¦', 'Naogaon', 'naogaon'),
(401, 4, 'à¦–à§à¦²à¦¨à¦¾', 'Khulna', 'khulna'),
(402, 4, 'à¦¯à¦¶à§‹à¦°', 'Jashore', 'jashore'),
(403, 4, 'à¦¸à¦¾à¦¤à¦•à§à¦·à§€à¦°à¦¾', 'Satkhira', 'satkhira'),
(404, 4, 'à¦•à§à¦·à§à¦Ÿà¦¿à¦¯à¦¼à¦¾', 'Kushtia', 'kushtia'),
(405, 4, 'à¦¬à¦¾à¦—à§‡à¦°à¦¹à¦¾à¦Ÿ', 'Bagerhat', 'bagerhat'),
(501, 5, 'à¦¬à¦°à¦¿à¦¶à¦¾à¦²', 'Barishal', 'barishal'),
(502, 5, 'à¦ªà¦Ÿà§à¦¯à¦¼à¦¾à¦–à¦¾à¦²à§€', 'Patuakhali', 'patuakhali'),
(503, 5, 'à¦­à§‹à¦²à¦¾', 'Bhola', 'bhola'),
(504, 5, 'à¦ªà¦¿à¦°à§‹à¦œà¦ªà§à¦°', 'Pirojpur', 'pirojpur'),
(601, 6, 'à¦¸à¦¿à¦²à§‡à¦Ÿ', 'Sylhet', 'sylhet'),
(602, 6, 'à¦®à§Œà¦²à¦­à§€à¦¬à¦¾à¦œà¦¾à¦°', 'Moulvibazar', 'moulvibazar'),
(603, 6, 'à¦¹à¦¬à¦¿à¦—à¦žà§à¦œ', 'Habiganj', 'habiganj'),
(604, 6, 'à¦¸à§à¦¨à¦¾à¦®à¦—à¦žà§à¦œ', 'Sunamganj', 'sunamganj'),
(701, 7, 'à¦°à¦‚à¦ªà§à¦°', 'Rangpur', 'rangpur'),
(702, 7, 'à¦¦à¦¿à¦¨à¦¾à¦œà¦ªà§à¦°', 'Dinajpur', 'dinajpur'),
(703, 7, 'à¦•à§à¦¡à¦¼à¦¿à¦—à§à¦°à¦¾à¦®', 'Kurigram', 'kurigram'),
(704, 7, 'à¦—à¦¾à¦‡à¦¬à¦¾à¦¨à§à¦§à¦¾', 'Gaibandha', 'gaibandha'),
(801, 8, 'à¦®à¦¯à¦¼à¦®à¦¨à¦¸à¦¿à¦‚à¦¹', 'Mymensingh', 'mymensingh'),
(802, 8, 'à¦œà¦¾à¦®à¦¾à¦²à¦ªà§à¦°', 'Jamalpur', 'jamalpur'),
(803, 8, 'à¦¨à§‡à¦¤à§à¦°à¦•à§‹à¦£à¦¾', 'Netrokona', 'netrokona'),
(804, 8, 'à¦¶à§‡à¦°à¦ªà§à¦°', 'Sherpur', 'sherpur')
ON DUPLICATE KEY UPDATE `name_bn`=VALUES(`name_bn`);

-- 4. 14 Main Menu Categories
INSERT INTO `categories` (`id`, `name_bn`, `name_en`, `slug`, `parent_id`, `sort_order`, `is_menu`) VALUES
(1, 'à¦œà¦¾à¦¤à§€à¦¯à¦¼', 'National', 'national', NULL, 1, 1),
(2, 'à¦°à¦¾à¦œà¦¨à§€à¦¤à¦¿', 'Politics', 'politics', NULL, 2, 1),
(3, 'à¦¸à¦¾à¦°à¦¾à¦¦à§‡à¦¶', 'Countrywide', 'saradesh', NULL, 3, 1),
(4, 'à¦…à¦°à§à¦¥à¦¨à§€à¦¤à¦¿', 'Economy', 'economy', NULL, 4, 1),
(5, 'à¦†à¦¨à§à¦¤à¦°à§à¦œà¦¾à¦¤à¦¿à¦•', 'International', 'international', NULL, 5, 1),
(6, 'à¦–à§‡à¦²à¦¾', 'Sports', 'sports', NULL, 6, 1),
(7, 'à¦¬à¦¿à¦¨à§‹à¦¦à¦¨', 'Entertainment', 'entertainment', NULL, 7, 1),
(8, 'à¦¬à¦¿à¦œà§à¦žà¦¾à¦¨ à¦“ à¦ªà§à¦°à¦¯à§à¦•à§à¦¤à¦¿', 'Science & Tech', 'tech', NULL, 8, 1),
(9, 'à¦¶à¦¿à¦•à§à¦·à¦¾', 'Education', 'education', NULL, 9, 1),
(10, 'à¦¸à§à¦¬à¦¾à¦¸à§à¦¥à§à¦¯', 'Health', 'health', NULL, 10, 1),
(11, 'à¦šà¦¾à¦•à¦°à¦¿', 'Jobs', 'jobs', NULL, 11, 1),
(12, 'à¦®à¦¤à¦¾à¦®à¦¤', 'Opinion', 'opinion', NULL, 12, 1),
(13, 'à¦²à¦¾à¦‡à¦«à¦¸à§à¦Ÿà¦¾à¦‡à¦²', 'Lifestyle', 'lifestyle', NULL, 13, 1),
(14, 'à¦ªà§à¦°à¦¬à¦¾à¦¸', 'Probash', 'probash', NULL, 14, 1),
(15, 'à¦…à¦ªà¦°à¦¾à¦§', 'Crime', 'crime', NULL, 15, 1)
ON DUPLICATE KEY UPDATE `name_bn`=VALUES(`name_bn`), `slug`=VALUES(`slug`);

-- 5. Default Site Settings
INSERT INTO `settings` (`key`, `value`) VALUES
('site_name_bn', 'à¦¨à¦¿à¦‰à¦œà¦²à§‡à¦¨à§à¦¸à¦¬à¦¿à¦¡à¦¿'),
('site_name_en', 'Newslensbd'),
('tagline_bn', 'à¦¸à¦¾à¦§à¦¾à¦°à¦£à§‡à¦° à¦¬à¦¾à¦‡à¦°à§‡, à¦¸à¦¤à§à¦¯à§‡à¦° à¦–à§‹à¦à¦œà§‡'),
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
(1, 'à¦†à¦¸à¦¨à§à¦¨ à¦¸à¦¾à¦°à§à¦¬à¦¿à¦• à¦ªà¦°à¦¿à¦¸à§à¦¥à¦¿à¦¤à¦¿à¦¤à§‡ à¦°à¦¾à¦œà¦¨à§ˆà¦¤à¦¿à¦• à¦¦à¦²à¦—à§à¦²à§‹à¦° à¦§à¦¾à¦°à¦¾à¦¬à¦¾à¦¹à¦¿à¦• à¦¸à¦‚à¦²à¦¾à¦ª à¦•à¦¿ à¦¬à¦¿à¦¦à§à¦¯à¦®à¦¾à¦¨ à¦¸à¦‚à¦•à¦Ÿ à¦¨à¦¿à¦°à¦¸à¦¨à§‡ à¦­à§‚à¦®à¦¿à¦•à¦¾ à¦°à¦¾à¦–à¦¬à§‡?', 1, NOW())
ON DUPLICATE KEY UPDATE `question`=VALUES(`question`);

INSERT INTO `poll_options` (`id`, `poll_id`, `option_text`, `votes`) VALUES
(1, 1, 'à¦¹à§à¦¯à¦¾à¦, à¦‡à¦¤à¦¿à¦¬à¦¾à¦šà¦• à¦¸à¦®à¦¾à¦§à¦¾à¦¨ à¦†à¦¸à¦¬à§‡', 420),
(2, 1, 'à¦¨à¦¾, à¦•à§‹à¦¨à§‹ à¦ªà¦°à¦¿à¦¬à¦°à§à¦¤à¦¨ à¦¹à¦¬à§‡ à¦¨à¦¾', 185),
(3, 1, 'à¦®à¦¨à§à¦¤à¦¬à§à¦¯ à¦¨à§‡à¦‡ / à¦¨à¦¿à¦¶à§à¦šà¦¿à¦¤ à¦¨à¦‡', 45)
ON DUPLICATE KEY UPDATE `option_text`=VALUES(`option_text`);

-- 7. Dummy Ads (Both Picture & Video)
DELETE FROM `ads` WHERE `id` <= 10;
INSERT INTO `ads` (`id`, `title`, `position`, `type`, `image`, `video_url`, `link`, `is_active`, `impressions`, `clicks`, `created_at`) VALUES
(1, 'à¦ªà§à¦°à¦¿à¦®à¦¿à¦¯à¦¼à¦¾à¦® à¦¸à§à¦®à¦¾à¦°à§à¦Ÿ à¦Ÿà§‡à¦•à¦¨à§‹à¦²à¦œà¦¿ à¦“ à¦‡à¦²à§‡à¦•à¦Ÿà§à¦°à¦¨à¦¿à¦•à§à¦¸ à¦®à§‡à¦—à¦¾ à¦…à¦«à¦¾à¦°', 'header', 'image', 'https://images.unsplash.com/photo-1550009158-9ebf69173e03?auto=format&fit=crop&w=728&h=90&q=80', NULL, 'https://stratifyxglobal.com', 1, 140, 22, NOW()),
(2, 'Stratifyx Global - à¦¸à§à¦®à¦¾à¦°à§à¦Ÿ à¦“à¦¯à¦¼à§‡à¦¬ à¦“ à¦¸à¦«à¦Ÿà¦“à¦¯à¦¼à§à¦¯à¦¾à¦° à¦¸à¦²à¦¿à¦‰à¦¶à¦¨ (à¦­à¦¿à¦¡à¦¿à¦“ à¦¸à§à¦ªà¦¨à¦¸à¦°)', 'sidebar', 'video', 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=600&q=80', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'https://stratifyxglobal.com', 1, 260, 48, NOW()),
(3, 'à¦•à§à¦¯à¦¾à¦°à¦¿à¦¯à¦¼à¦¾à¦° à¦¡à§‡à¦­à§‡à¦²à¦ªà¦®à§‡à¦¨à§à¦Ÿ à¦“ à¦ªà§à¦°à¦«à§‡à¦¶à¦¨à¦¾à¦² à¦Ÿà§à¦°à§‡à¦¨à¦¿à¦‚ à¦à¦•à¦¾à¦¡à§‡à¦®à¦¿', 'in_article', 'image', 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=728&h=90&q=80', NULL, 'https://example.com/training', 1, 95, 14, NOW()),
(4, 'à¦†à¦¨à§à¦¤à¦°à§à¦œà¦¾à¦¤à¦¿à¦• à¦Ÿà§à¦°à¦¾à¦­à§‡à¦² à¦“ à¦¹à¦²à¦¿à¦¡à§‡ à¦ªà§à¦¯à¦¾à¦•à§‡à¦œà§‡ à¦¬à¦¿à¦¶à§‡à¦· à¦›à¦¾à¦¡à¦¼', 'footer', 'image', 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=970&h=90&q=80', NULL, 'https://example.com/travel', 1, 110, 19, NOW());

-- 8. 14 Category-Specific News Articles + User Added News (ID 15)
DELETE FROM `posts` WHERE `id` <= 15;
INSERT INTO `posts` (`id`, `title`, `subtitle`, `slug`, `body`, `excerpt`, `featured_image`, `video_url`, `category_id`, `district_id`, `author_id`, `status`, `is_lead`, `is_breaking`, `views`, `published_at`, `created_at`) VALUES
(1, 'à¦ªà¦¦à§à¦®à¦¾ à¦¸à§‡à¦¤à§ à¦“ à¦®à§‡à¦Ÿà§à¦°à§‹à¦°à§‡à¦²à§‡à¦° à¦ªà¦° à¦à¦¬à¦¾à¦° à¦®à¦¾à¦¤à¦¾à¦°à¦¬à¦¾à¦¡à¦¼à§€ à¦—à¦­à§€à¦° à¦¸à¦®à§à¦¦à§à¦° à¦¬à¦¨à§à¦¦à¦° à¦šà¦¾à¦²à§ à¦¹à¦šà§à¦›à§‡: à¦¯à§‹à¦—à¦¾à¦¯à§‹à¦—à§‡à¦° à¦¨à¦¤à§à¦¨ à¦¦à¦¿à¦—à¦¨à§à¦¤', 'à¦¦à§‡à¦¶à§‡à¦° à¦ªà§à¦°à¦¥à¦® à¦—à¦­à§€à¦° à¦¸à¦®à§à¦¦à§à¦° à¦¬à¦¨à§à¦¦à¦° à¦¬à¦¾à¦£à¦¿à¦œà§à¦¯ à¦“ à¦…à¦°à§à¦¥à¦¨à§€à¦¤à¦¿à¦¤à§‡ à¦†à¦¨à¦¬à§‡ à¦¬à§ˆà¦ªà§à¦²à¦¬à¦¿à¦• à¦ªà¦°à¦¿à¦¬à¦°à§à¦¤à¦¨', 'matarbari-deep-sea-port-inauguration-bangladesh', '<p>à¦¬à¦¾à¦‚à¦²à¦¾à¦¦à§‡à¦¶à§‡à¦° à¦…à¦¬à¦•à¦¾à¦ à¦¾à¦®à§‹à¦—à¦¤ à¦‰à¦¨à§à¦¨à¦¯à¦¼à¦¨à§‡ à¦¯à§à¦•à§à¦¤ à¦¹à¦¤à§‡ à¦šà¦²à§‡à¦›à§‡ à¦†à¦°à§‡à¦•à¦Ÿà¦¿ à¦¯à§à¦—à¦¾à¦¨à§à¦¤à¦•à¦¾à¦°à§€ à¦®à¦¾à¦‡à¦²à¦«à¦²à¦•à¥¤ à¦ªà¦¦à§à¦®à¦¾ à¦¬à¦¹à§à¦®à§à¦–à§€ à¦¸à§‡à¦¤à§, à¦°à§‚à¦ªà¦ªà§à¦° à¦ªà¦¾à¦°à¦®à¦¾à¦£à¦¬à¦¿à¦• à¦¬à¦¿à¦¦à§à¦¯à§à§Ž à¦•à§‡à¦¨à§à¦¦à§à¦° à¦“ à¦®à§‡à¦Ÿà§à¦°à§‹à¦°à§‡à¦²à§‡à¦° à¦…à¦­à¦¾à¦¬à¦¨à§€à¦¯à¦¼ à¦¸à¦¾à¦«à¦²à§à¦¯à§‡à¦° à¦ªà¦° à¦à¦¬à¦¾à¦° à¦¬à¦¹à§à¦² à¦ªà§à¦°à¦¤à§€à¦•à§à¦·à¦¿à¦¤ à¦®à¦¾à¦¤à¦¾à¦°à¦¬à¦¾à¦¡à¦¼à§€ à¦—à¦­à§€à¦° à¦¸à¦®à§à¦¦à§à¦° à¦¬à¦¨à§à¦¦à¦° à¦¬à¦¾à¦£à¦¿à¦œà§à¦¯à¦¿à¦•à¦­à¦¾à¦¬à§‡ à¦‰à¦¨à§à¦®à§à¦•à§à¦¤ à¦¹à¦¤à§‡ à¦¯à¦¾à¦šà§à¦›à§‡à¥¤</p><p>à¦¬à¦¿à¦¶à§‡à¦·à¦œà§à¦žà¦°à¦¾ à¦œà¦¾à¦¨à¦¿à¦¯à¦¼à§‡à¦›à§‡à¦¨, à¦à¦‡ à¦¬à¦¨à§à¦¦à¦° à¦ªà§à¦°à§‹à¦ªà§à¦°à¦¿ à¦šà¦¾à¦²à§ à¦¹à¦²à§‡ à§§à§¬ à¦¥à§‡à¦•à§‡ à§§à§® à¦®à¦¿à¦Ÿà¦¾à¦° à¦¡à§à¦°à¦¾à¦«à¦Ÿà§‡à¦° à¦¬à¦¿à¦¶à¦¾à¦²à¦¾à¦•à¦¾à¦° à¦®à¦¾à¦¦à¦¾à¦° à¦­à§‡à¦¸à§‡à¦² à¦¸à¦°à¦¾à¦¸à¦°à¦¿ à¦¬à¦¾à¦‚à¦²à¦¾à¦¦à§‡à¦¶à§‡ à¦­à¦¿à¦¡à¦¼à¦¤à§‡ à¦ªà¦¾à¦°à¦¬à§‡à¥¤ à¦à¦° à¦«à¦²à§‡ à¦¸à¦¿à¦™à§à¦—à¦¾à¦ªà§à¦° à¦¬à¦¾ à¦•à¦²à¦®à§à¦¬à§‹ à¦¬à¦¨à§à¦¦à¦°à§‡à¦° à¦“à¦ªà¦° à¦¨à¦¿à¦°à§à¦­à¦°à¦¤à¦¾ à¦¹à§à¦°à¦¾à¦¸ à¦ªà¦¾à¦¬à§‡ à¦à¦¬à¦‚ à¦†à¦®à¦¦à¦¾à¦¨à¦¿-à¦°à¦ªà§à¦¤à¦¾à¦¨à¦¿ à¦ªà¦°à¦¿à¦¬à¦¹à¦¨ à¦¬à§à¦¯à¦¯à¦¼ à¦à¦•-à¦¤à§ƒà¦¤à§€à¦¯à¦¼à¦¾à¦‚à¦¶ à¦•à¦®à§‡ à¦¯à¦¾à¦¬à§‡à¥¤</p>', 'à¦ªà¦¦à§à¦®à¦¾ à¦¸à§‡à¦¤à§ à¦“ à¦®à§‡à¦Ÿà§à¦°à§‹à¦°à§‡à¦²à§‡à¦° à¦ªà¦° à¦®à¦¾à¦¤à¦¾à¦°à¦¬à¦¾à¦¡à¦¼à§€ à¦—à¦­à§€à¦° à¦¸à¦®à§à¦¦à§à¦° à¦¬à¦¨à§à¦¦à¦° à¦¬à¦¾à¦£à¦¿à¦œà§à¦¯à¦¿à¦• à¦¯à¦¾à¦¤à§à¦°à¦¾à¦° à¦œà¦¨à§à¦¯ à¦ªà§à¦°à§‹à¦ªà§à¦°à¦¿ à¦ªà§à¦°à¦¸à§à¦¤à§à¦¤à¥¤ à¦à¦Ÿà¦¿ à¦¦à§‡à¦¶à§‡à¦° à¦²à¦œà¦¿à¦¸à§à¦Ÿà¦¿à¦• à¦“ à¦…à¦°à§à¦¥à¦¨à§€à¦¤à¦¿à¦¤à§‡ à¦¨à¦¤à§à¦¨ à¦¦à¦¿à¦—à¦¨à§à¦¤ à¦‰à¦¨à§à¦®à§‹à¦šà¦¨ à¦•à¦°à¦¬à§‡à¥¤', 'https://images.unsplash.com/photo-1541872703-74c5e44368f9?auto=format&fit=crop&w=800&q=80', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 1, 101, 1, 'published', 1, 1, 3840, NOW() - INTERVAL 1 HOUR, NOW()),
(2, 'à¦¨à¦¿à¦°à§à¦¬à¦¾à¦šà¦¨ à¦•à¦®à¦¿à¦¶à¦¨à§‡à¦° à¦¨à¦¤à§à¦¨ à¦°à§‹à¦¡à¦®à§à¦¯à¦¾à¦ª à¦˜à§‹à¦·à¦£à¦¾: à¦°à¦¾à¦œà¦¨à§ˆà¦¤à¦¿à¦• à¦¦à¦²à¦—à§à¦²à§‹à¦° à¦¸à¦™à§à¦—à§‡ à¦§à¦¾à¦°à¦¾à¦¬à¦¾à¦¹à¦¿à¦• à¦¸à¦‚à¦²à¦¾à¦ªà§‡à¦° à¦‰à¦¦à§à¦¯à§‹à¦—', 'à¦…à¦‚à¦¶à¦—à§à¦°à¦¹à¦£à¦®à§‚à¦²à¦• à¦¨à¦¿à¦°à§à¦¬à¦¾à¦šà¦¨à§‡à¦° à¦ªà¦°à¦¿à¦¬à§‡à¦¶ à¦¨à¦¿à¦¶à§à¦šà¦¿à¦¤à§‡ à¦¸à¦¬ à¦¦à¦²à§‡à¦° à¦®à¦¤à¦¾à¦®à¦¤ à¦¨à§‡à¦“à¦¯à¦¼à¦¾à¦° à¦†à¦¶à§à¦¬à¦¾à¦¸', 'election-commission-announces-new-roadmap-and-dialogue', '<p>à¦¨à¦¿à¦°à§à¦¬à¦¾à¦šà¦¨ à¦•à¦®à¦¿à¦¶à¦¨ (à¦‡à¦¸à¦¿) à¦†à¦—à¦¾à¦®à§€ à¦œà¦¾à¦¤à§€à¦¯à¦¼ à¦¸à¦‚à¦¸à¦¦ à¦¨à¦¿à¦°à§à¦¬à¦¾à¦šà¦¨à¦•à§‡ à¦¸à¦¾à¦®à¦¨à§‡ à¦°à§‡à¦–à§‡ à¦¤à¦¾à¦¦à§‡à¦° à¦ªà§‚à¦°à§à¦£à¦¾à¦™à§à¦— à¦•à¦°à§à¦®à¦ªà¦°à¦¿à¦•à¦²à§à¦ªà¦¨à¦¾ à¦“ à¦°à§‹à¦¡à¦®à§à¦¯à¦¾à¦ª à¦†à¦¨à§à¦·à§à¦ à¦¾à¦¨à¦¿à¦•à¦­à¦¾à¦¬à§‡ à¦˜à§‹à¦·à¦£à¦¾ à¦•à¦°à§‡à¦›à§‡à¥¤ à¦ªà§à¦°à¦§à¦¾à¦¨ à¦¨à¦¿à¦°à§à¦¬à¦¾à¦šà¦¨ à¦•à¦®à¦¿à¦¶à¦¨à¦¾à¦° à¦œà¦¾à¦¨à¦¾à¦¨, à¦¸à¦•à¦² à¦¨à¦¿à¦¬à¦¨à§à¦§à¦¿à¦¤ à¦°à¦¾à¦œà¦¨à§ˆà¦¤à¦¿à¦• à¦¦à¦², à¦¸à§à¦¶à§€à¦² à¦¸à¦®à¦¾à¦œ à¦“ à¦—à¦£à¦®à¦¾à¦§à§à¦¯à¦® à¦ªà§à¦°à¦¤à¦¿à¦¨à¦¿à¦§à¦¿à¦¦à§‡à¦° à¦¸à¦™à§à¦—à§‡ à¦§à¦¾à¦ªà§‡ à¦§à¦¾à¦ªà§‡ à¦¸à¦‚à¦²à¦¾à¦ªà§‡à¦° à¦†à¦¯à¦¼à§‹à¦œà¦¨ à¦•à¦°à¦¾ à¦¹à¦¬à§‡à¥¤</p>', 'à¦†à¦—à¦¾à¦®à§€ à¦¸à¦¾à¦§à¦¾à¦°à¦£ à¦¨à¦¿à¦°à§à¦¬à¦¾à¦šà¦¨à¦•à§‡ à¦…à¦¬à¦¾à¦§ à¦“ à¦¨à¦¿à¦°à¦ªà§‡à¦•à§à¦· à¦•à¦°à¦¤à§‡ à¦°à¦¾à¦œà¦¨à§ˆà¦¤à¦¿à¦• à¦¦à¦²à¦—à§à¦²à§‹à¦° à¦¸à¦™à§à¦—à§‡ à¦‰à¦¨à§à¦®à§à¦•à§à¦¤ à¦¸à¦‚à¦²à¦¾à¦ªà§‡à¦° à¦°à§‹à¦¡à¦®à§à¦¯à¦¾à¦ª à¦˜à§‹à¦·à¦£à¦¾ à¦•à¦°à§‡à¦›à§‡ à¦¨à¦¿à¦°à§à¦¬à¦¾à¦šà¦¨ à¦•à¦®à¦¿à¦¶à¦¨à¥¤', 'https://images.unsplash.com/photo-1529107386315-e1a2ed48a620?auto=format&fit=crop&w=800&q=80', 'https://www.youtube.com/watch?v=ScMzIvxBSi4', 2, 101, 1, 'published', 0, 1, 2450, NOW() - INTERVAL 3 HOUR, NOW()),
(3, 'à¦¶à§€à¦¤à§‡à¦° à¦†à¦®à§‡à¦œà§‡ à¦‰à¦¤à§à¦¤à¦°à§‡à¦° à¦œà¦¨à¦ªà¦¦à§‡ à¦†à¦—à¦¾à¦® à¦¸à¦¬à¦œà¦¿à¦° à¦¬à¦¾à¦®à§à¦ªà¦¾à¦° à¦«à¦²à¦¨: à¦•à§ƒà¦·à¦•à¦¦à§‡à¦° à¦®à§à¦–à§‡ à¦¸à§à¦¬à¦¸à§à¦¤à¦¿à¦° à¦¹à¦¾à¦¸à¦¿', 'à¦°à¦‚à¦ªà§à¦° à¦“ à¦¦à¦¿à¦¨à¦¾à¦œà¦ªà§à¦°à§‡à¦° à¦¬à¦¿à¦¸à§à¦¤à§€à¦°à§à¦£ à¦®à¦¾à¦ à§‡ à¦°à¦™à¦¿à¦¨ à¦¶à¦¿à¦®, à¦«à§à¦²à¦•à¦ªà¦¿ à¦“ à¦Ÿà¦®à§‡à¦Ÿà§‹à¦° à¦ªà§à¦°à¦¾à¦šà§à¦°à§à¦¯', 'winter-vegetable-bumper-harvest-in-northern-districts', '<p>à¦¶à§€à¦¤à§‡à¦° à¦†à¦—à¦®à¦¨à§€ à¦¬à¦¾à¦°à§à¦¤à¦¾à¦° à¦¸à¦¾à¦¥à§‡ à¦¸à¦¾à¦¥à§‡ à¦¦à§‡à¦¶à§‡à¦° à¦‰à¦¤à§à¦¤à¦°à¦¾à¦žà§à¦šà¦²à§‡ à¦†à¦—à¦¾à¦® à¦¶à§€à¦¤à¦•à¦¾à¦²à§€à¦¨ à¦¶à¦¾à¦•à¦¸à¦¬à¦œà¦¿à¦° à¦¬à¦¾à¦®à§à¦ªà¦¾à¦° à¦«à¦²à¦¨à§‡ à¦‰à§Žà¦¸à¦¬à¦®à§à¦–à¦° à¦ªà¦°à¦¿à¦¬à§‡à¦¶ à¦¬à¦¿à¦°à¦¾à¦œ à¦•à¦°à¦›à§‡à¥¤ à¦°à¦‚à¦ªà§à¦°, à¦¦à¦¿à¦¨à¦¾à¦œà¦ªà§à¦° à¦à¦¬à¦‚ à¦—à¦¾à¦‡à¦¬à¦¾à¦¨à§à¦§à¦¾à¦° à¦®à¦¾à¦ à¦œà§à¦¡à¦¼à§‡ à¦¸à¦¬à§à¦œ à¦«à§à¦²à¦•à¦ªà¦¿, à¦¬à¦¾à¦à¦§à¦¾à¦•à¦ªà¦¿, à¦¶à¦¿à¦®, à¦®à§à¦²à¦¾ à¦“ à¦²à¦¾à¦² à¦Ÿà¦®à§‡à¦Ÿà§‹à¦¤à§‡ à¦­à¦°à§‡ à¦‰à¦ à§‡à¦›à§‡ à¦–à§‡à¦¤à¥¤</p>', 'à¦…à¦¨à§à¦•à§‚à¦² à¦†à¦¬à¦¹à¦¾à¦“à¦¯à¦¼à¦¾ à¦“ à¦†à¦§à§à¦¨à¦¿à¦• à¦•à§ƒà¦·à¦¿ à¦ªà§à¦°à¦¯à§à¦•à§à¦¤à¦¿à¦¤à§‡ à¦‰à¦¤à§à¦¤à¦°à¦¾à¦žà§à¦šà¦²à§‡à¦° à§® à¦œà§‡à¦²à¦¾à¦¯à¦¼ à¦¶à§€à¦¤à§‡à¦° à¦†à¦—à¦¾à¦® à¦¸à¦¬à¦œà¦¿à¦° à¦°à§‡à¦•à¦°à§à¦¡ à¦«à¦²à¦¨ à¦…à¦°à§à¦œà¦¿à¦¤ à¦¹à¦¯à¦¼à§‡à¦›à§‡à¥¤', 'https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&w=800&q=80', 'https://www.youtube.com/watch?v=21X5lGlDOfg', 3, 701, 1, 'published', 0, 0, 1620, NOW() - INTERVAL 5 HOUR, NOW()),
(4, 'à¦°à§‡à¦®à¦¿à¦Ÿà§à¦¯à¦¾à¦¨à§à¦¸ à¦ªà§à¦°à¦¬à¦¾à¦¹à§‡ à¦¨à¦¤à§à¦¨ à¦°à§‡à¦•à¦°à§à¦¡: à¦—à¦¤ à¦®à¦¾à¦¸à§‡ à¦¦à§‡à¦¶à§‡ à¦à¦²à§‹ à§¨à§ªà§¦ à¦•à§‹à¦Ÿà¦¿ à¦¡à¦²à¦¾à¦°à§‡à¦° à¦ªà§à¦°à¦¬à¦¾à¦¸à§€ à¦†à¦¯à¦¼', 'à¦¬à§ˆà¦§ à¦ªà¦¥à§‡ à¦ªà§à¦°à¦£à§‹à¦¦à¦¨à¦¾ à¦“ à¦¬à§à¦¯à¦¾à¦‚à¦•à¦¿à¦‚ à¦šà§à¦¯à¦¾à¦¨à§‡à¦²à§‡ à¦¸à§à¦¬à¦¿à¦§à¦¾ à¦¬à§ƒà¦¦à§à¦§à¦¿ à¦ªà¦¾à¦“à¦¯à¦¼à¦¾à¦¯à¦¼ à¦‡à¦¤à¦¿à¦¬à¦¾à¦šà¦• à¦ªà§à¦°à¦­à¦¾à¦¬', 'remittance-inflow-reaches-record-high-economy-boost', '<p>à¦¬à§ˆà¦§ à¦¬à§à¦¯à¦¾à¦‚à¦•à¦¿à¦‚ à¦šà§à¦¯à¦¾à¦¨à§‡à¦²à§‡ à¦°à§‡à¦®à¦¿à¦Ÿà§à¦¯à¦¾à¦¨à§à¦¸ à¦ªà¦¾à¦ à¦¾à¦¨à§‹à¦° à¦¹à¦¾à¦° à¦•à§à¦°à¦®à¦¾à¦—à¦¤ à¦¬à§ƒà¦¦à§à¦§à¦¿ à¦ªà¦¾à¦“à¦¯à¦¼à¦¾à¦¯à¦¼ à¦¬à§ˆà¦¦à§‡à¦¶à¦¿à¦• à¦®à§à¦¦à§à¦°à¦¾à¦° à¦°à¦¿à¦œà¦¾à¦°à§à¦­à§‡ à¦ªà§à¦°à¦¾à¦£à¦šà¦¾à¦žà§à¦šà¦²à§à¦¯ à¦«à¦¿à¦°à§‡ à¦à¦¸à§‡à¦›à§‡à¥¤ à¦¬à¦¾à¦‚à¦²à¦¾à¦¦à§‡à¦¶ à¦¬à§à¦¯à¦¾à¦‚à¦•à§‡à¦° à¦¹à¦¾à¦²à¦¨à¦¾à¦—à¦¾à¦¦ à¦¤à¦¥à§à¦¯à¦¾à¦¨à§à¦¯à¦¾à¦¯à¦¼à§€, à¦¸à¦¦à§à¦¯ à¦¸à¦®à¦¾à¦ªà§à¦¤ à¦®à¦¾à¦¸à§‡ à¦ªà§à¦°à¦¬à¦¾à¦¸à§€ à¦¬à¦¾à¦‚à¦²à¦¾à¦¦à§‡à¦¶à¦¿à¦°à¦¾ à§¨à§ªà§¦ à¦•à§‹à¦Ÿà¦¿ à¦®à¦¾à¦°à§à¦•à¦¿à¦¨ à¦¡à¦²à¦¾à¦°à§‡à¦°à¦“ à¦¬à§‡à¦¶à¦¿ à¦°à§‡à¦®à¦¿à¦Ÿà§à¦¯à¦¾à¦¨à§à¦¸ à¦ªà¦¾à¦ à¦¿à¦¯à¦¼à§‡à¦›à§‡à¦¨à¥¤</p>', 'à¦ªà§à¦°à¦¬à¦¾à¦¸à§€ à¦†à¦¯à¦¼à§‡ à¦œà§‹à¦¯à¦¼à¦¾à¦°: à¦à¦• à¦®à¦¾à¦¸à§‡ à§¨à§ªà§¦ à¦•à§‹à¦Ÿà¦¿ à¦¡à¦²à¦¾à¦° à¦¦à§‡à¦¶à§‡ à¦†à¦¸à¦¾à¦¯à¦¼ à¦¬à§ˆà¦¦à§‡à¦¶à¦¿à¦• à¦®à§à¦¦à§à¦°à¦¾à¦° à¦°à¦¿à¦œà¦¾à¦°à§à¦­ à¦¶à¦•à§à¦¤à¦¿à¦¶à¦¾à¦²à§€ à¦¹à¦¯à¦¼à§‡à¦›à§‡ à¦à¦¬à¦‚ à¦¡à¦²à¦¾à¦°à§‡à¦° à¦¬à¦¾à¦œà¦¾à¦°à§‡ à¦¸à§à¦¬à¦¸à§à¦¤à¦¿ à¦«à¦¿à¦°à§‡à¦›à§‡à¥¤', 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?auto=format&fit=crop&w=800&q=80', 'https://www.youtube.com/watch?v=kJQP7kiw5Fk', 4, 101, 1, 'published', 1, 0, 4120, NOW() - INTERVAL 7 HOUR, NOW()),
(5, 'à¦œà¦²à¦¬à¦¾à¦¯à¦¼à§ à¦¸à¦®à§à¦®à§‡à¦²à¦¨à§‡ à¦¬à¦¿à¦¶à§à¦¬à¦¨à§‡à¦¤à¦¾à¦¦à§‡à¦° à¦à¦¤à¦¿à¦¹à¦¾à¦¸à¦¿à¦• à¦šà§à¦•à§à¦¤à¦¿: à¦•à§à¦·à¦¤à¦¿à¦—à§à¦°à¦¸à§à¦¤ à¦¦à§‡à¦¶à¦—à§à¦²à§‹à¦° à¦œà¦¨à§à¦¯ à¦¬à¦¿à¦¶à§‡à¦· à¦•à§à¦·à¦¤à¦¿à¦ªà§‚à¦°à¦£ à¦¤à¦¹à¦¬à¦¿à¦²', 'à¦•à¦¾à¦°à§à¦¬à¦¨ à¦¨à¦¿à¦ƒà¦¸à¦°à¦£ à¦•à¦®à¦¾à¦¨à§‹ à¦“ à¦¨à¦¬à¦¾à¦¯à¦¼à¦¨à¦¯à§‹à¦—à§à¦¯ à¦¶à¦•à§à¦¤à¦¿à¦¤à§‡ à¦°à§‚à¦ªà¦¾à¦¨à§à¦¤à¦°à§‡à¦° à¦¸à¦®à¦¯à¦¼à¦¸à§€à¦®à¦¾ à¦¨à¦¿à¦°à§à¦§à¦¾à¦°à¦£', 'cop-climate-summit-historic-loss-and-damage-fund-deal', '<p>à¦†à¦¨à§à¦¤à¦°à§à¦œà¦¾à¦¤à¦¿à¦• à¦œà¦²à¦¬à¦¾à¦¯à¦¼à§ à¦¸à¦®à§à¦®à§‡à¦²à¦¨à§‡ à¦¬à¦¿à¦¶à§à¦¬à¦¨à§‡à¦¤à¦¾à¦°à¦¾ à¦œà¦²à¦¬à¦¾à¦¯à¦¼à§ à¦ªà¦°à¦¿à¦¬à¦°à§à¦¤à¦¨à§‡à¦° à¦¶à¦¿à¦•à¦¾à¦° à¦¦à§‡à¦¶à¦—à§à¦²à§‹à¦° à¦œà¦¨à§à¦¯ à¦à¦•à¦Ÿà¦¿ à¦¬à¦¿à¦¶à§‡à¦· â€˜à¦•à§à¦·à¦¤à¦¿à¦ªà§‚à¦°à¦£ à¦“ à¦ªà§à¦¨à¦°à§à¦—à¦ à¦¨ à¦¤à¦¹à¦¬à¦¿à¦²â€™ (Loss and Damage Fund) à¦•à¦¾à¦°à§à¦¯à¦•à¦° à¦•à¦°à¦¤à§‡ à¦¸à¦°à§à¦¬à¦¸à¦®à§à¦®à¦¤ à¦šà§à¦•à§à¦¤à¦¿à¦¤à§‡ à¦ªà§Œà¦à¦›à§‡à¦›à§‡à¦¨à¥¤</p>', 'à¦à¦¤à¦¿à¦¹à¦¾à¦¸à¦¿à¦• à¦œà¦²à¦¬à¦¾à¦¯à¦¼à§ à¦šà§à¦•à§à¦¤à¦¿ à¦¸à§à¦¬à¦¾à¦•à§à¦·à¦°à¦¿à¦¤: à¦•à§à¦·à¦¤à¦¿à¦—à§à¦°à¦¸à§à¦¤ à¦‰à¦¨à§à¦¨à¦¯à¦¼à¦¨à¦¶à§€à¦² à¦¦à§‡à¦¶à¦—à§à¦²à§‹à¦° à¦œà¦¨à§à¦¯ à¦†à¦¨à§à¦¤à¦°à§à¦œà¦¾à¦¤à¦¿à¦• à¦¸à¦¹à¦¾à¦¯à¦¼à¦¤à¦¾ à¦¤à¦¹à¦¬à¦¿à¦² à¦¬à¦¾à¦¸à§à¦¤à¦¬à¦¾à¦¯à¦¼à¦¨à§‡à¦° à¦†à¦¨à§à¦·à§à¦ à¦¾à¦¨à¦¿à¦• à¦¸à¦¿à¦¦à§à¦§à¦¾à¦¨à§à¦¤à¥¤', 'https://images.unsplash.com/photo-1569163139599-0f4517e36f51?auto=format&fit=crop&w=800&q=80', 'https://www.youtube.com/watch?v=9bZkp7q19f0', 5, NULL, 1, 'published', 0, 1, 2980, NOW() - INTERVAL 9 HOUR, NOW()),
(6, 'à¦®à¦¿à¦°à¦ªà§à¦°à§‡ à¦¶à§à¦¬à¦¾à¦¸à¦°à§à¦¦à§à¦§à¦•à¦° à¦¶à§‡à¦· à¦“à¦­à¦¾à¦°à§‡ à¦¨à¦¾à¦Ÿà¦•à§€à¦¯à¦¼ à¦œà¦¯à¦¼: à¦¸à¦¿à¦°à¦¿à¦œ à¦¨à¦¿à¦¶à§à¦šà¦¿à¦¤ à¦•à¦°à¦² à¦Ÿà¦¾à¦‡à¦—à¦¾à¦°à¦°à¦¾', 'à¦…à¦²à¦°à¦¾à¦‰à¦¨à§à¦¡ à¦ªà¦¾à¦°à¦«à¦°à¦®à§à¦¯à¦¾à¦¨à§à¦¸à§‡ à¦¦à¦°à§à¦¶à¦•à¦¦à§‡à¦° à¦‰à¦²à§à¦²à¦¾à¦¸, à¦¸à§‡à¦°à¦¾ à¦–à§‡à¦²à§‹à¦¯à¦¼à¦¾à¦¡à¦¼ à¦¨à¦¿à¦°à§à¦¬à¦¾à¦šà¦¿à¦¤ à¦¹à¦²à§‡à¦¨ à¦¤à¦°à§à¦£ à¦ªà§‡à¦¸à¦¾à¦°', 'bangladesh-cricket-thrilling-last-over-win-mirpur', '<p>à¦¹à§‹à¦® à¦…à¦¬ à¦•à§à¦°à¦¿à¦•à§‡à¦Ÿ à¦®à¦¿à¦°à¦ªà§à¦° à¦¶à§‡à¦°-à¦‡-à¦¬à¦¾à¦‚à¦²à¦¾ à¦œà¦¾à¦¤à§€à¦¯à¦¼ à¦•à§à¦°à¦¿à¦•à§‡à¦Ÿ à¦¸à§à¦Ÿà§‡à¦¡à¦¿à¦¯à¦¼à¦¾à¦®à§‡ à¦Ÿà¦¾à¦¨à¦Ÿà¦¾à¦¨ à¦‰à¦¤à§à¦¤à§‡à¦œà¦¨à¦¾à¦° à¦®à§à¦¯à¦¾à¦šà§‡ à¦¸à¦«à¦°à¦•à¦¾à¦°à§€à¦¦à§‡à¦° à§ª à¦°à¦¾à¦¨à§‡ à¦¹à¦¾à¦°à¦¿à¦¯à¦¼à§‡ à¦¦à§à¦¬à¦¿à¦ªà¦¾à¦•à§à¦·à¦¿à¦• à¦¸à¦¿à¦°à¦¿à¦œ à¦¨à¦¿à¦œà§‡à¦¦à§‡à¦° à¦•à¦°à§‡ à¦¨à¦¿à¦¯à¦¼à§‡à¦›à§‡ à¦¬à¦¾à¦‚à¦²à¦¾à¦¦à§‡à¦¶ à¦œà¦¾à¦¤à§€à¦¯à¦¼ à¦•à§à¦°à¦¿à¦•à§‡à¦Ÿ à¦¦à¦²à¥¤</p>', 'à¦¶à§‡à¦· à¦“à¦­à¦¾à¦°à§‡ à¦¶à§à¦¬à¦¾à¦¸à¦°à§à¦¦à§à¦§à¦•à¦° à¦²à¦¡à¦¼à¦¾à¦‡à¦¯à¦¼à§‡ à¦¸à¦«à¦°à¦•à¦¾à¦°à§€ à¦¦à¦²à¦•à§‡ à¦ªà¦°à¦¾à¦¸à§à¦¤ à¦•à¦°à§‡ à§§ à¦®à§à¦¯à¦¾à¦š à¦¹à¦¾à¦¤à§‡ à¦°à§‡à¦–à§‡à¦‡ à¦¸à¦¿à¦°à¦¿à¦œ à¦œà¦¯à¦¼ à¦¨à¦¿à¦¶à§à¦šà¦¿à¦¤ à¦•à¦°à¦² à¦¬à¦¾à¦‚à¦²à¦¾à¦¦à§‡à¦¶ à¦¦à¦²à¥¤', 'https://images.unsplash.com/photo-1540747913346-19e32dc3e97e?auto=format&fit=crop&w=800&q=80', 'https://www.youtube.com/watch?v=fJ9rUzIMcZQ', 6, 101, 1, 'published', 0, 0, 4890, NOW() - INTERVAL 11 HOUR, NOW()),
(7, 'à¦†à¦¨à§à¦¤à¦°à§à¦œà¦¾à¦¤à¦¿à¦• à¦šà¦²à¦šà§à¦šà¦¿à¦¤à§à¦° à¦‰à§Žà¦¸à¦¬à§‡ à¦¸à§‡à¦°à¦¾ à¦šà¦²à¦šà§à¦šà¦¿à¦¤à§à¦°à§‡à¦° à¦¸à¦®à§à¦®à¦¾à¦¨à¦¨à¦¾ à¦ªà§‡à¦² à¦¬à¦¾à¦‚à¦²à¦¾à¦¦à§‡à¦¶à¦¿ à¦¸à¦¿à¦¨à§‡à¦®à¦¾', 'à¦®à§Œà¦²à¦¿à¦• à¦—à¦²à§à¦ª à¦“ à¦¬à¦¾à¦¸à§à¦¤à¦¬à¦§à¦°à§à¦®à§€ à¦šà¦¿à¦¤à§à¦°à¦¨à¦¾à¦Ÿà§à¦¯à§‡à¦° à¦œà¦¨à§à¦¯ à¦œà§à¦°à¦¿ à¦¬à§‹à¦°à§à¦¡à§‡à¦° à¦­à§‚à¦¯à¦¼à¦¸à§€ à¦ªà§à¦°à¦¶à¦‚à¦¸à¦¾', 'bangladeshi-film-wins-prestigious-award-at-international-festival', '<p>à¦¬à¦¿à¦¶à§à¦¬à¦®à¦žà§à¦šà§‡ à¦†à¦¬à¦¾à¦°à¦“ à¦‰à¦œà§à¦œà§à¦¬à¦² à¦¹à¦²à§‹ à¦¬à¦¾à¦‚à¦²à¦¾à¦¦à§‡à¦¶à§‡à¦° à¦¶à¦¿à¦²à§à¦ª à¦“ à¦¸à¦‚à¦¸à§à¦•à§ƒà¦¤à¦¿à¥¤ à¦®à¦°à§à¦¯à¦¾à¦¦à¦¾à¦ªà§‚à¦°à§à¦£ à¦†à¦¨à§à¦¤à¦°à§à¦œà¦¾à¦¤à¦¿à¦• à¦šà¦²à¦šà§à¦šà¦¿à¦¤à§à¦° à¦‰à§Žà¦¸à¦¬à§‡ à¦¸à§‡à¦°à¦¾ à¦«à¦¿à¦šà¦¾à¦° à¦«à¦¿à¦²à§à¦®à§‡à¦° à¦¸à¦°à§à¦¬à§‹à¦šà§à¦š à¦ªà§à¦°à¦¸à§à¦•à¦¾à¦° à¦œà¦¿à¦¤à§‡ à¦¨à¦¿à¦¯à¦¼à§‡à¦›à§‡ à¦¬à¦¾à¦‚à¦²à¦¾à¦¦à§‡à¦¶à¦¿ à¦¨à¦¿à¦°à§à¦®à¦¾à¦¤à¦¾à¦° à¦¨à¦¿à¦°à§à¦®à¦¿à¦¤ à¦¬à¦¹à§à¦² à¦ªà§à¦°à¦¶à¦‚à¦¸à¦¿à¦¤ à¦šà¦²à¦šà§à¦šà¦¿à¦¤à§à¦°à¥¤</p>', 'à¦¬à¦¿à¦¦à§‡à¦¶à§‡à¦° à¦®à¦¾à¦Ÿà¦¿à¦¤à§‡ à¦¬à¦¾à¦‚à¦²à¦¾ à¦¸à¦¿à¦¨à§‡à¦®à¦¾à¦° à¦à¦¤à¦¿à¦¹à¦¾à¦¸à¦¿à¦• à¦—à§Œà¦°à¦¬: à¦†à¦¨à§à¦¤à¦°à§à¦œà¦¾à¦¤à¦¿à¦• à¦‰à§Žà¦¸à¦¬à§‡ à¦¸à§‡à¦°à¦¾ à¦šà¦²à¦šà§à¦šà¦¿à¦¤à§à¦° à¦¹à¦¿à¦¸à§‡à¦¬à§‡ à¦¸à§à¦¬à§€à¦•à§ƒà¦¤à¦¿ à¦²à¦¾à¦­à¥¤', 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=800&q=80', 'https://www.youtube.com/watch?v=3JZ_D3ELwOQ', 7, 101, 1, 'published', 0, 0, 1850, NOW() - INTERVAL 13 HOUR, NOW()),
(8, 'à¦•à§ƒà¦¤à§à¦°à¦¿à¦® à¦¬à§à¦¦à§à¦§à¦¿à¦®à¦¤à§à¦¤à¦¾à¦° à¦®à¦¾à¦§à§à¦¯à¦®à§‡ à¦¬à¦¾à¦‚à¦²à¦¾ à¦­à¦¾à¦·à¦¾à¦° à¦¨à§à¦¯à¦¾à¦šà¦¾à¦°à¦¾à¦² à¦²à§à¦¯à¦¾à¦™à§à¦—à§à¦¯à¦¼à§‡à¦œ à¦®à¦¡à§‡à¦² à¦‰à¦¨à§à¦®à§‹à¦šà¦¨ à¦•à¦°à¦²à§‡à¦¨ à¦¤à¦°à§à¦£ à¦ªà§à¦°à¦•à§Œà¦¶à¦²à§€à¦°à¦¾', 'à¦¬à¦¾à¦‚à¦²à¦¾ à¦¬à§à¦¯à¦¾à¦•à¦°à¦£, à¦…à¦¨à§à¦¬à¦¾à¦¦ à¦“ à¦¨à¦¥à¦¿ à¦¬à¦¿à¦¶à§à¦²à§‡à¦·à¦£à§‡à¦° à¦œà¦¨à§à¦¯ à¦‰à¦¨à§à¦®à§à¦•à§à¦¤ à¦à¦†à¦‡ à¦ªà§à¦²à§à¦¯à¦¾à¦Ÿà¦«à¦°à§à¦®', 'bangla-ai-large-language-model-unveiled-by-engineers', '<p>à¦¤à¦¥à§à¦¯à¦ªà§à¦°à¦¯à§à¦•à§à¦¤à¦¿à¦° à¦¯à§à¦—à§‡ à¦¬à¦¾à¦‚à¦²à¦¾ à¦­à¦¾à¦·à¦¾à¦° à¦¡à¦¿à¦œà¦¿à¦Ÿà¦¾à¦‡à¦œà§‡à¦¶à¦¨à§‡ à¦¨à¦¤à§à¦¨ à¦à¦• à¦®à¦¾à¦‡à¦²à¦«à¦²à¦• à¦°à¦šà¦¿à¦¤ à¦¹à¦²à§‹à¥¤ à¦¦à§‡à¦¶à§€à¦¯à¦¼ à¦¸à¦«à¦Ÿà¦“à¦¯à¦¼à§à¦¯à¦¾à¦° à¦ªà§à¦°à¦•à§Œà¦¶à¦²à§€ à¦“ à¦—à¦¬à§‡à¦·à¦•à¦¦à§‡à¦° à¦¯à§Œà¦¥ à¦‰à¦¦à§à¦¯à§‹à¦—à§‡ à¦¨à¦¿à¦°à§à¦®à¦¿à¦¤ à¦¹à¦¯à¦¼à§‡à¦›à§‡ à¦•à§ƒà¦¤à§à¦°à¦¿à¦® à¦¬à§à¦¦à§à¦§à¦¿à¦®à¦¤à§à¦¤à¦¾ à¦šà¦¾à¦²à¦¿à¦¤ à¦ªà§à¦°à¦¥à¦® à¦ªà§‚à¦°à§à¦£à¦¾à¦™à§à¦— à¦¬à¦¾à¦‚à¦²à¦¾ à¦²à¦¾à¦°à§à¦œ à¦²à§à¦¯à¦¾à¦™à§à¦—à§à¦¯à¦¼à§‡à¦œ à¦®à¦¡à§‡à¦² (LLM)à¥¤</p>', 'à¦¬à¦¾à¦‚à¦²à¦¾ à¦­à¦¾à¦·à¦¾à¦° à¦¨à¦¿à¦œà¦¸à§à¦¬ à¦à¦†à¦‡ à¦®à¦¡à§‡à¦² à¦‰à¦¨à§à¦®à§‹à¦šà¦¨: à¦¨à¦¿à¦°à§à¦­à§à¦² à¦…à¦¨à§à¦¬à¦¾à¦¦ à¦“ à¦—à¦¬à§‡à¦·à¦£à¦¾à¦¯à¦¼ à¦¨à¦¤à§à¦¨ à¦¸à¦®à§à¦­à¦¾à¦¬à¦¨à¦¾à¦° à¦¦à§à¦¬à¦¾à¦° à¦‰à¦¨à§à¦®à§à¦•à§à¦¤ à¦¹à¦²à§‹ à¦¦à§‡à¦¶à§‡à¥¤', 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=800&q=80', 'https://www.youtube.com/watch?v=L_LUpnjgPso', 8, 101, 1, 'published', 0, 0, 3340, NOW() - INTERVAL 15 HOUR, NOW()),
(9, 'à¦¨à¦¤à§à¦¨ à¦¶à¦¿à¦•à§à¦·à¦¾à¦•à§à¦°à¦®à§‡ à¦¦à¦•à§à¦·à¦¤à¦¾à¦­à¦¿à¦¤à§à¦¤à¦¿à¦• à¦®à§‚à¦²à§à¦¯à¦¾à¦¯à¦¼à¦¨ à¦ªà¦¦à§à¦§à¦¤à¦¿: à¦¶à¦¿à¦•à§à¦·à¦•à¦¦à§‡à¦° à¦¦à§‡à¦¶à¦¬à§à¦¯à¦¾à¦ªà§€ à¦¬à¦¿à¦¶à§‡à¦· à¦ªà§à¦°à¦¶à¦¿à¦•à§à¦·à¦£ à¦•à¦°à§à¦®à¦¸à§‚à¦šà¦¿ à¦¶à§à¦°à§', 'à¦®à§à¦–à¦¸à§à¦¥à¦¬à¦¿à¦¦à§à¦¯à¦¾à¦° à¦¬à¦¦à¦²à§‡ à¦¬à§à¦¯à¦¾à¦¬à¦¹à¦¾à¦°à¦¿à¦• à¦œà§à¦žà¦¾à¦¨ à¦“ à¦¸à¦®à¦¸à§à¦¯à¦¾ à¦¸à¦®à¦¾à¦§à¦¾à¦¨à§‡à¦° à¦¦à¦•à§à¦·à¦¤à¦¾à¦¯à¦¼ à¦—à§à¦°à§à¦¤à§à¦¬', 'education-curriculum-teachers-skill-training-countrywide', '<p>à¦¶à¦¿à¦•à§à¦·à¦¾ à¦¬à§à¦¯à¦¬à¦¸à§à¦¥à¦¾à¦•à§‡ à¦†à¦§à§à¦¨à¦¿à¦• à¦“ à¦¬à§ˆà¦¶à§à¦¬à¦¿à¦• à¦šà¦¾à¦¹à¦¿à¦¦à¦¾à¦° à¦¸à¦¾à¦¥à§‡ à¦¸à¦™à§à¦—à¦¤à¦¿à¦ªà§‚à¦°à§à¦£ à¦•à¦°à¦¤à§‡ à¦ªà§à¦°à¦¾à¦¥à¦®à¦¿à¦• à¦“ à¦®à¦¾à¦§à§à¦¯à¦®à¦¿à¦• à¦¸à§à¦¤à¦°à§‡ à¦¶à¦¿à¦•à§à¦·à¦•à¦¦à§‡à¦° à¦¦à¦•à§à¦·à¦¤à¦¾à¦­à¦¿à¦¤à§à¦¤à¦¿à¦• à¦¬à¦¿à¦¶à§‡à¦· à¦ªà§à¦°à¦¶à¦¿à¦•à§à¦·à¦£ à¦•à¦°à§à¦®à¦¸à§‚à¦šà¦¿ à¦¦à§‡à¦¶à¦¬à§à¦¯à¦¾à¦ªà§€ à¦¶à§à¦°à§ à¦¹à¦¯à¦¼à§‡à¦›à§‡à¥¤</p>', 'à¦®à§à¦–à¦¸à§à¦¥ à¦¨à¦¿à¦°à§à¦­à¦°à¦¤à¦¾ à¦ªà¦°à¦¿à¦¹à¦¾à¦° à¦•à¦°à§‡ à¦•à¦°à§à¦®à¦®à§à¦–à§€ à¦“ à¦†à¦§à§à¦¨à¦¿à¦• à¦¶à¦¿à¦•à§à¦·à¦¾à¦° à¦¬à¦¿à¦¸à§à¦¤à¦¾à¦° à¦˜à¦Ÿà¦¾à¦¤à§‡ à¦¸à¦¾à¦°à¦¾ à¦¦à§‡à¦¶à§‡ à¦¶à¦¿à¦•à§à¦·à¦•à¦¦à§‡à¦° à¦¬à§à¦¯à¦¾à¦ªà¦• à¦ªà§à¦°à¦¶à¦¿à¦•à§à¦·à¦£ à¦•à¦¾à¦°à§à¦¯à¦•à§à¦°à¦® à¦ªà¦°à¦¿à¦šà¦¾à¦²à¦¿à¦¤ à¦¹à¦šà§à¦›à§‡à¥¤', 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=800&q=80', 'https://www.youtube.com/watch?v=kXYiU_JCYtU', 9, 101, 1, 'published', 0, 0, 1420, NOW() - INTERVAL 17 HOUR, NOW()),
(10, 'à¦®à§Œà¦¸à§à¦®à¦¿ à¦°à§‹à¦— à¦ªà§à¦°à¦¤à¦¿à¦°à§‹à¦§à§‡ à¦¦à§‡à¦¶à¦¬à§à¦¯à¦¾à¦ªà§€ à¦ªà¦°à¦¿à¦šà§à¦›à¦¨à§à¦¨à¦¤à¦¾ à¦•à¦¾à¦°à§à¦¯à¦•à§à¦°à¦®: à¦¹à¦¾à¦¸à¦ªà¦¾à¦¤à¦¾à¦²à¦—à§à¦²à§‹à¦¤à§‡ à¦ªà¦°à§à¦¯à¦¾à¦ªà§à¦¤ à¦“à¦·à§à¦§ à¦“ à¦¶à¦¯à§à¦¯à¦¾ à¦ªà§à¦°à¦¸à§à¦¤à§à¦¤', 'à¦œà¦¨à¦¸à§à¦¬à¦¾à¦¸à§à¦¥à§à¦¯ à¦…à¦§à¦¿à¦¦à¦ªà§à¦¤à¦°à§‡à¦° à¦¬à¦¿à¦¶à§‡à¦· à¦—à¦¾à¦‡à¦¡à¦²à¦¾à¦‡à¦¨ à¦à¦¬à¦‚ à¦ªà§à¦°à¦¤à¦¿à¦Ÿà¦¿ à¦“à¦¯à¦¼à¦¾à¦°à§à¦¡à§‡ à¦¸à¦šà§‡à¦¤à¦¨à¦¤à¦¾à¦®à§‚à¦²à¦• à¦®à§‡à¦¡à¦¿à¦•à§‡à¦² à¦Ÿà¦¿à¦®', 'health-directorate-guidelines-and-hospital-preparedness', '<p>à¦®à§Œà¦¸à§à¦® à¦ªà¦°à¦¿à¦¬à¦°à§à¦¤à¦¨à§‡à¦° à¦•à¦¾à¦°à¦£à§‡ à¦¡à§‡à¦™à§à¦—à§ à¦“ à¦­à¦¾à¦‡à¦°à¦¾à¦¸à¦œà¦¨à¦¿à¦¤ à¦œà§à¦¬à¦°à§‡à¦° à¦ªà§à¦°à¦¾à¦¦à§à¦°à§à¦­à¦¾à¦¬ à¦°à§‹à¦§à§‡ à¦¸à¦¾à¦°à¦¾ à¦¦à§‡à¦¶à§‡ à¦à¦•à¦¯à§‹à¦—à§‡ à¦ªà¦°à¦¿à¦šà§à¦›à¦¨à§à¦¨à¦¤à¦¾ à¦“ à¦œà¦¨à¦¸à¦šà§‡à¦¤à¦¨à¦¤à¦¾à¦®à§‚à¦²à¦• à¦•à¦¾à¦°à§à¦¯à¦•à§à¦°à¦® à¦œà§‹à¦°à¦¦à¦¾à¦° à¦•à¦°à¦¾ à¦¹à¦¯à¦¼à§‡à¦›à§‡à¥¤</p>', 'à¦œà¦¨à¦¸à§à¦¬à¦¾à¦¸à§à¦¥à§à¦¯ à¦¸à§à¦°à¦•à§à¦·à¦¾à¦¯à¦¼ à¦¸à¦®à¦¨à§à¦¬à¦¿à¦¤ à¦‰à¦¦à§à¦¯à§‹à¦—: à¦¹à¦¾à¦¸à¦ªà¦¾à¦¤à¦¾à¦²à¦—à§à¦²à§‹à¦¤à§‡ à¦¸à¦¾à¦°à§à¦¬à¦•à§à¦·à¦£à¦¿à¦• à¦šà¦¿à¦•à¦¿à§Žà¦¸à¦• à¦¸à§‡à¦¬à¦¾ à¦“ à¦¬à¦¿à¦¨à¦¾à¦®à§‚à¦²à§à¦¯à§‡ à¦ªà§à¦°à¦¯à¦¼à§‹à¦œà¦¨à§€à¦¯à¦¼ à¦“à¦·à§à¦§ à¦¸à¦°à¦¬à¦°à¦¾à¦¹ à¦šà¦¾à¦²à§à¥¤', 'https://images.unsplash.com/photo-1538108149393-fbbd81895907?auto=format&fit=crop&w=800&q=80', 'https://www.youtube.com/watch?v=eVTXPUF4Oz4', 10, 101, 1, 'published', 0, 1, 2710, NOW() - INTERVAL 19 HOUR, NOW()),
(11, 'à¦¬à¦¿à¦¸à¦¿à¦à¦¸ à¦“ à¦¸à¦°à¦•à¦¾à¦°à¦¿ à¦¬à§à¦¯à¦¾à¦‚à¦•à§‡ à¦¬à§ƒà¦¹à§Ž à¦¨à¦¿à¦¯à¦¼à§‹à¦— à¦¬à¦¿à¦œà§à¦žà¦ªà§à¦¤à¦¿: à¦…à¦¨à¦²à¦¾à¦‡à¦¨à§‡ à¦†à¦¬à§‡à¦¦à¦¨à§‡à¦° à¦¶à§‡à¦· à¦¤à¦¾à¦°à¦¿à¦– à¦“ à¦ªà§‚à¦°à§à¦£à¦¾à¦™à§à¦— à¦¨à¦¿à¦°à§à¦¦à§‡à¦¶à¦¿à¦•à¦¾', 'à¦¬à¦¿à¦­à¦¿à¦¨à§à¦¨ à¦•à§à¦¯à¦¾à¦¡à¦¾à¦°à§‡ à¦¹à¦¾à¦œà¦¾à¦°à§‹ à¦¶à§‚à¦¨à§à¦¯à¦ªà¦¦à§‡à¦° à¦¬à¦¿à¦ªà¦°à§€à¦¤à§‡ à¦…à¦¨à¦²à¦¾à¦‡à¦¨à§‡ à¦†à¦¬à§‡à¦¦à¦¨ à¦ªà§à¦°à¦•à§à¦°à¦¿à¦¯à¦¼à¦¾ à¦¶à§à¦°à§', 'bcs-and-public-bank-recruitment-circular-application-guide', '<p>à¦¸à¦°à¦•à¦¾à¦°à¦¿ à¦•à¦°à§à¦® à¦•à¦®à¦¿à¦¶à¦¨ (à¦ªà¦¿à¦à¦¸à¦¸à¦¿) à¦“ à¦¬à§à¦¯à¦¾à¦‚à¦•à¦¾à¦°à§à¦¸ à¦¸à¦¿à¦²à§‡à¦•à¦¶à¦¨ à¦•à¦®à¦¿à¦Ÿà¦¿ à¦¸à¦šà¦¿à¦¬à¦¾à¦²à¦¯à¦¼ à¦¸à§à¦¨à¦¾à¦¤à¦• à¦¸à¦®à§à¦ªà¦¨à§à¦¨à¦•à¦¾à¦°à§€ à¦šà¦¾à¦•à¦°à¦¿à¦ªà§à¦°à¦¾à¦°à§à¦¥à§€à¦¦à§‡à¦° à¦œà¦¨à§à¦¯ à¦¨à¦¤à§à¦¨ à¦¨à¦¿à¦¯à¦¼à§‹à¦— à¦¬à¦¿à¦œà§à¦žà¦ªà§à¦¤à¦¿ à¦ªà§à¦°à¦•à¦¾à¦¶ à¦•à¦°à§‡à¦›à§‡à¥¤</p>', 'à¦¸à¦°à¦•à¦¾à¦°à¦¿ à¦šà¦¾à¦•à¦°à¦¿à¦ªà§à¦°à¦¾à¦°à§à¦¥à§€à¦¦à§‡à¦° à¦œà¦¨à§à¦¯ à¦¸à§à¦–à¦¬à¦°: à¦ªà¦¿à¦à¦¸à¦¸à¦¿ à¦“ à¦¬à§à¦¯à¦¾à¦‚à¦•à¦¿à¦‚ à¦–à¦¾à¦¤à§‡à¦° à¦¨à¦¤à§à¦¨ à¦¨à¦¿à¦¯à¦¼à§‹à¦—à§‡ à¦†à¦¬à§‡à¦¦à¦¨à§‡à¦° à¦¨à¦¿à¦¯à¦¼à¦®à¦¾à¦¬à¦²à§€ à¦“ à¦ªà§à¦°à¦¸à§à¦¤à§à¦¤à¦¿ à¦—à¦¾à¦‡à¦¡à¥¤', 'https://images.unsplash.com/photo-1486312338219-ce68d2c6f44d?auto=format&fit=crop&w=800&q=80', 'https://www.youtube.com/watch?v=OPf0YbXqDm0', 11, 101, 1, 'published', 0, 0, 5210, NOW() - INTERVAL 21 HOUR, NOW()),
(12, 'à¦¤à¦¾à¦°à§à¦£à§à¦¯à§‡à¦° à¦‰à¦¦à§à¦¦à§€à¦ªà¦¨à¦¾ à¦“ à¦¤à¦¥à§à¦¯à¦ªà§à¦°à¦¯à§à¦•à§à¦¤à¦¿à¦¨à¦¿à¦°à§à¦­à¦° à¦†à¦—à¦¾à¦®à§€à¦° à¦¬à¦¾à¦‚à¦²à¦¾à¦¦à§‡à¦¶ à¦¬à¦¿à¦¨à¦¿à¦°à§à¦®à¦¾à¦£à§‡ à¦†à¦®à¦¾à¦¦à§‡à¦° à¦•à¦°à¦£à§€à¦¯à¦¼', 'à¦œà§à¦žà¦¾à¦¨à¦­à¦¿à¦¤à§à¦¤à¦¿à¦• à¦¸à¦®à¦¾à¦œ à¦“ à¦•à¦°à§à¦®à¦¸à¦‚à¦¸à§à¦¥à¦¾à¦¨ à¦¸à§ƒà¦·à§à¦Ÿà¦¿à¦¤à§‡ à¦¤à¦°à§à¦£ à¦¸à¦®à¦¾à¦œà§‡à¦° à¦¨à§‡à¦¤à§ƒà¦¤à§à¦¬à¦‡ à¦¸à¦¬à¦šà§‡à¦¯à¦¼à§‡ à¦¬à¦¡à¦¼ à¦šà¦¾à¦²à¦¿à¦•à¦¾à¦¶à¦•à§à¦¤à¦¿', 'opinion-youth-empowerment-and-building-future-bangladesh', '<p>à¦à¦•à¦Ÿà¦¿ à¦¦à§‡à¦¶à§‡à¦° à¦ªà§à¦°à¦•à§ƒà¦¤ à¦¶à¦•à§à¦¤à¦¿ à¦¨à¦¿à¦¹à¦¿à¦¤ à¦¥à¦¾à¦•à§‡ à¦¤à¦¾à¦° à¦¤à¦°à§à¦£ à¦¸à¦®à¦¾à¦œà§‡à¦° à¦®à§‡à¦§à¦¾, à¦¸à¦¤à¦¤à¦¾ à¦“ à¦‰à¦¦à§à¦­à¦¾à¦¬à¦¨à§€ à¦¶à¦•à§à¦¤à¦¿à¦° à¦®à¦¾à¦à§‡à¥¤ à¦šà¦¤à§à¦°à§à¦¥ à¦¶à¦¿à¦²à§à¦ª à¦¬à¦¿à¦ªà§à¦²à¦¬à§‡à¦° à¦à¦‡ à¦¯à§à¦—à§‡ à¦°à§‹à¦¬à¦Ÿà¦¿à¦•à§à¦¸ à¦“ à¦à¦†à¦‡-à¦¤à§‡ à¦¬à¦¾à¦‚à¦²à¦¾à¦¦à§‡à¦¶à§‡à¦° à¦¤à¦°à§à¦£à¦°à¦¾ à¦¸à¦•à§à¦·à¦®à¦¤à¦¾ à¦ªà§à¦°à¦®à¦¾à¦£ à¦•à¦°à¦›à§‡à¥¤</p>', 'à¦¬à¦¿à¦¶à§‡à¦· à¦•à¦²à¦¾à¦®: à¦¤à¦°à§à¦£ à¦ªà§à¦°à¦œà¦¨à§à¦®à¦•à§‡ à¦¸à¦ à¦¿à¦• à¦ªà§à¦°à¦¯à§à¦•à§à¦¤à¦¿ à¦“ à¦¶à¦¿à¦•à§à¦·à¦¾à¦° à¦†à¦²à§‹à¦¯à¦¼ à¦†à¦²à§‹à¦•à¦¿à¦¤ à¦•à¦°à§‡ à¦¦à§‡à¦¶à¦•à§‡ à¦¸à¦®à§ƒà¦¦à§à¦§à¦¿à¦° à¦ªà¦¥à§‡ à¦à¦—à¦¿à¦¯à¦¼à§‡ à¦¨à§‡à¦“à¦¯à¦¼à¦¾à¦° à¦•à§Œà¦¶à¦²à¥¤', 'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=800&q=80', 'https://www.youtube.com/watch?v=lTRiuFIWV54', 12, 101, 1, 'published', 0, 0, 1340, NOW() - INTERVAL 23 HOUR, NOW()),
(13, 'à¦¶à§€à¦¤à¦•à¦¾à¦²à§€à¦¨ à¦¸à§à¦¬à¦¾à¦¸à§à¦¥à§à¦¯ à¦“ à¦¤à§à¦¬à¦•à§‡à¦° à¦¬à¦¿à¦¶à§‡à¦· à¦¯à¦¤à§à¦¨: à¦¬à¦¿à¦¶à§‡à¦·à¦œà§à¦žà¦¦à§‡à¦° à§«à¦Ÿà¦¿ à¦œà¦°à§à¦°à¦¿ à¦¦à§ˆà¦¨à¦¨à§à¦¦à¦¿à¦¨ à¦ªà¦°à¦¾à¦®à¦°à§à¦¶', 'à¦¶à§à¦·à§à¦• à¦†à¦¬à¦¹à¦¾à¦“à¦¯à¦¼à¦¾à¦¯à¦¼ à¦¸à§à¦¸à§à¦¥ à¦¥à¦¾à¦•à¦¤à§‡ à¦ªà§à¦°à¦šà§à¦° à¦ªà¦¾à¦¨à¦¿ à¦ªà¦¾à¦¨ à¦“ à¦ªà§à¦·à§à¦Ÿà¦¿à¦•à¦° à¦–à¦¾à¦¦à§à¦¯ à¦¤à¦¾à¦²à¦¿à¦•à¦¾à¦° à¦—à§à¦°à§à¦¤à§à¦¬', 'winter-skin-care-and-healthy-lifestyle-expert-tips', '<p>à¦¶à§€à¦¤à§‡à¦° à¦¶à§à¦·à§à¦• à¦†à¦¬à¦¹à¦¾à¦“à¦¯à¦¼à¦¾à¦¯à¦¼ à¦¤à§à¦¬à¦• à¦“ à¦šà§à¦²à§‡à¦° à¦†à¦°à§à¦¦à§à¦°à¦¤à¦¾ à¦§à¦°à§‡ à¦°à¦¾à¦–à¦¾ à¦…à¦¤à§à¦¯à¦¨à§à¦¤ à¦œà¦°à§à¦°à¦¿à¥¤ à¦ªà§à¦·à§à¦Ÿà¦¿à¦¬à¦¿à¦¦ à¦“ à¦šà¦°à§à¦®à¦°à§‹à¦— à¦¬à¦¿à¦¶à§‡à¦·à¦œà§à¦žà¦°à¦¾ à¦à¦‡ à¦®à§Œà¦¸à§à¦®à§‡ à¦ªà§à¦°à¦¤à¦¿à¦¦à¦¿à¦¨ à¦ªà¦°à§à¦¯à¦¾à¦ªà§à¦¤ à¦ªà¦¾à¦¨à¦¿ à¦“ à¦¸à§à¦¯à§à¦ª à¦ªà¦¾à¦¨ à¦•à¦°à¦¾à¦° à¦ªà¦°à¦¾à¦®à¦°à§à¦¶ à¦¦à¦¿à¦šà§à¦›à§‡à¦¨à¥¤</p>', 'à¦¶à§€à¦¤à§‡à¦° à¦¦à¦¿à¦¨à§‡ à¦¤à§à¦¬à¦•à§‡à¦° à¦¸à§à¦°à¦•à§à¦·à¦¾ à¦“ à¦¸à¦¤à§‡à¦œà¦¤à¦¾ à¦¬à¦œà¦¾à¦¯à¦¼ à¦°à¦¾à¦–à¦¤à§‡ à¦¬à¦¿à¦¶à§‡à¦·à¦œà§à¦ž à¦šà¦¿à¦•à¦¿à§Žà¦¸à¦•à¦¦à§‡à¦° à¦¸à¦¹à¦œ à¦à¦¬à¦‚ à¦ªà§à¦°à¦¾à¦•à§ƒà¦¤à¦¿à¦• à¦ªà¦¾à¦à¦šà¦Ÿà¦¿ à¦•à¦¾à¦°à§à¦¯à¦•à¦°à§€ à¦Ÿà¦¿à¦ªà¦¸à¥¤', 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=800&q=80', 'https://www.youtube.com/watch?v=ZbZSe6N_BXs', 13, 101, 1, 'published', 0, 0, 1950, NOW() - INTERVAL 25 HOUR, NOW()),
(14, 'à¦®à¦¾à¦²à¦¯à¦¼à§‡à¦¶à¦¿à¦¯à¦¼à¦¾ à¦“ à¦®à¦§à§à¦¯à¦ªà§à¦°à¦¾à¦šà§à¦¯à§‡ à¦¦à¦•à§à¦· à¦œà¦¨à¦¶à¦•à§à¦¤à¦¿ à¦°à¦ªà§à¦¤à¦¾à¦¨à¦¿à¦¤à§‡ à¦¨à¦¤à§à¦¨ à¦¸à§à¦¯à§‹à¦—: à¦•à¦°à§à¦®à§€à¦¦à§‡à¦° à¦¸à§à¦°à¦•à§à¦·à¦¾à¦¯à¦¼ à¦¦à§à¦¬à¦¿à¦ªà¦¾à¦•à§à¦·à¦¿à¦• à¦šà§à¦•à§à¦¤à¦¿', 'à¦¨à§à¦¯à§‚à¦¨à¦¤à¦® à¦¬à§‡à¦¤à¦¨ à¦¨à¦¿à¦°à§à¦§à¦¾à¦°à¦£, à¦¹à§‡à¦²à¦¥ à¦‡à¦¨à§à¦¸à§à¦¯à§à¦°à§‡à¦¨à§à¦¸ à¦“ à¦¨à¦¿à¦°à¦¾à¦ªà¦¦ à¦•à¦°à§à¦®à¦ªà¦°à¦¿à¦¬à§‡à¦¶ à¦¨à¦¿à¦¶à§à¦šà¦¿à¦¤à§‡ à¦•à¦ à§‹à¦° à¦¶à¦°à§à¦¤', 'probash-malaysia-middle-east-skilled-manpower-agreement', '<p>à¦ªà§à¦°à¦¬à¦¾à¦¸à§€ à¦•à¦²à§à¦¯à¦¾à¦£ à¦“ à¦¬à§ˆà¦¦à§‡à¦¶à¦¿à¦• à¦•à¦°à§à¦®à¦¸à¦‚à¦¸à§à¦¥à¦¾à¦¨ à¦®à¦¨à§à¦¤à§à¦°à¦£à¦¾à¦²à¦¯à¦¼à§‡à¦° à¦•à§‚à¦Ÿà¦¨à§ˆà¦¤à¦¿à¦• à¦ªà§à¦°à¦šà§‡à¦·à§à¦Ÿà¦¾à¦¯à¦¼ à¦®à¦¾à¦²à¦¯à¦¼à§‡à¦¶à¦¿à¦¯à¦¼à¦¾ à¦“ à¦®à¦§à§à¦¯à¦ªà§à¦°à¦¾à¦šà§à¦¯à§‡à¦° à¦¦à§‡à¦¶à¦—à§à¦²à§‹à¦¤à§‡ à¦¬à¦¾à¦‚à¦²à¦¾à¦¦à§‡à¦¶à§‡à¦° à¦¦à¦•à§à¦· à¦•à¦°à§à¦®à§€à¦¦à§‡à¦° à¦œà¦¨à§à¦¯ à¦•à¦°à§à¦®à¦¸à¦‚à¦¸à§à¦¥à¦¾à¦¨à§‡à¦° à¦¨à¦¤à§à¦¨ à¦•à§à¦·à§‡à¦¤à§à¦° à¦ªà§à¦°à¦¸à§à¦¤à§à¦¤ à¦¹à¦¯à¦¼à§‡à¦›à§‡à¥¤</p>', 'à¦ªà§à¦°à¦¬à¦¾à¦¸à§€ à¦•à¦°à§à¦®à§€à¦¦à§‡à¦° à¦¨à¦¿à¦°à¦¾à¦ªà¦¤à§à¦¤à¦¾ à¦“ à¦‰à¦šà§à¦š à¦†à¦¯à¦¼à§‡à¦° à¦¸à§à¦¯à§‹à¦— à¦¨à¦¿à¦¶à§à¦šà¦¿à¦¤ à¦•à¦°à¦¤à§‡ à¦¬à¦¨à§à¦§à§à¦­à¦¾à¦¬à¦¾à¦ªà¦¨à§à¦¨ à¦¦à§‡à¦¶à¦—à§à¦²à§‹à¦° à¦¸à¦¾à¦¥à§‡ à¦¸à§à¦¬à¦¾à¦•à§à¦·à¦°à¦¿à¦¤ à¦¹à¦²à§‹ à¦à¦¤à¦¿à¦¹à¦¾à¦¸à¦¿à¦• à¦¶à§à¦°à¦® à¦šà§à¦•à§à¦¤à¦¿à¥¤', 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=800&q=80', 'https://www.youtube.com/watch?v=uelHwf8o7_U', 14, 201, 1, 'published', 0, 0, 2640, NOW() - INTERVAL 27 HOUR, NOW()),
(15, 'Abir kalo', NULL, 'abir-kalo-1791486133', 'abir khob e kalo', 'abir khob e kalo', 'http://localhost/NewsLens/public/uploads/news/news_1791486133_450.jpg', '', 1, 108, 1, 'published', 0, 1, 1, NOW(), NOW());

SET FOREIGN_KEY_CHECKS = 1;

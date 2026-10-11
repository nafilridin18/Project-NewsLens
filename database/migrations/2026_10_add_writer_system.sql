-- =====================================================================
-- Migration: 2026_10_add_writer_system.sql
-- Newslensbd (নিউজলেন্সবিডি)
-- Adds Writer role, Writer Approval, Permissions, Profiles, Analytics,
-- User Accounts, OTP Verification, Notifications & Audit Logs
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Alter users table with safe column modifications
-- Update role and status enums to include writer, user, pending, suspended, removed, unverified
ALTER TABLE `users` 
    MODIFY COLUMN `role` ENUM('super_admin', 'admin', 'editor', 'sub_editor', 'reporter', 'district_rep', 'writer', 'user') NOT NULL DEFAULT 'user',
    MODIFY COLUMN `status` ENUM('pending', 'active', 'suspended', 'removed', 'unverified', 'inactive', 'banned') NOT NULL DEFAULT 'active';

-- Add additional columns if they don't already exist
DROP PROCEDURE IF EXISTS `AddUserColumnsIfMissing`;
DELIMITER //
CREATE PROCEDURE `AddUserColumnsIfMissing`()
BEGIN
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name='users' AND column_name='email_verified_at') THEN
        ALTER TABLE `users` ADD COLUMN `email_verified_at` TIMESTAMP NULL AFTER `status`;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name='users' AND column_name='permission_level') THEN
        ALTER TABLE `users` ADD COLUMN `permission_level` ENUM('direct_publish', 'editor_review') NULL DEFAULT NULL AFTER `email_verified_at`;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name='users' AND column_name='avatar') THEN
        ALTER TABLE `users` ADD COLUMN `avatar` VARCHAR(255) NULL AFTER `photo`;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name='users' AND column_name='cover') THEN
        ALTER TABLE `users` ADD COLUMN `cover` VARCHAR(255) NULL AFTER `avatar`;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name='users' AND column_name='location') THEN
        ALTER TABLE `users` ADD COLUMN `location` VARCHAR(150) NULL AFTER `bio`;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name='users' AND column_name='social_links') THEN
        ALTER TABLE `users` ADD COLUMN `social_links` JSON NULL AFTER `location`;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name='users' AND column_name='writer_reason') THEN
        ALTER TABLE `users` ADD COLUMN `writer_reason` TEXT NULL AFTER `social_links`;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name='users' AND column_name='rejected_reason') THEN
        ALTER TABLE `users` ADD COLUMN `rejected_reason` TEXT NULL AFTER `writer_reason`;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name='users' AND column_name='approved_by') THEN
        ALTER TABLE `users` ADD COLUMN `approved_by` BIGINT UNSIGNED NULL AFTER `rejected_reason`;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name='users' AND column_name='approved_at') THEN
        ALTER TABLE `users` ADD COLUMN `approved_at` DATETIME NULL AFTER `approved_by`;
    END IF;

    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name='users' AND column_name='deleted_at') THEN
        ALTER TABLE `users` ADD COLUMN `deleted_at` TIMESTAMP NULL AFTER `updated_at`;
    END IF;
END //
DELIMITER ;
CALL `AddUserColumnsIfMissing`();
DROP PROCEDURE IF EXISTS `AddUserColumnsIfMissing`;

-- 2. Update posts status ENUM to include 'pending_review'
ALTER TABLE `posts`
    MODIFY COLUMN `status` ENUM('draft', 'pending_review', 'pending', 'published', 'scheduled', 'rejected') NOT NULL DEFAULT 'draft';

-- Add rejected_reason to posts if missing
DROP PROCEDURE IF EXISTS `AddPostColumnsIfMissing`;
DELIMITER //
CREATE PROCEDURE `AddPostColumnsIfMissing`()
BEGIN
    IF NOT EXISTS (SELECT 1 FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name='posts' AND column_name='rejected_reason') THEN
        ALTER TABLE `posts` ADD COLUMN `rejected_reason` TEXT NULL AFTER `status`;
    END IF;
END //
DELIMITER ;
CALL `AddPostColumnsIfMissing`();
DROP PROCEDURE IF EXISTS `AddPostColumnsIfMissing`;

-- 3. Create otp_codes table
CREATE TABLE IF NOT EXISTS `otp_codes` (
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

-- 4. Create password_resets table
CREATE TABLE IF NOT EXISTS `password_resets` (
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

-- 5. Create post_views table (for 30-min unique views tracking and analytics)
CREATE TABLE IF NOT EXISTS `post_views` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `post_id` BIGINT UNSIGNED NOT NULL,
    `ip_hash` VARCHAR(64) NOT NULL,
    `user_id` BIGINT UNSIGNED NULL,
    `viewed_at` DATETIME NOT NULL,
    INDEX `idx_pv_post_viewed` (`post_id`, `viewed_at`),
    INDEX `idx_pv_dedup` (`post_id`, `ip_hash`, `viewed_at`),
    CONSTRAINT `fk_pv_post` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Create notifications table (in-app header bell)
CREATE TABLE IF NOT EXISTS `notifications` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `type` VARCHAR(50) NOT NULL DEFAULT 'breaking_news',
    `title` VARCHAR(255) NOT NULL,
    `body` TEXT NOT NULL,
    `url` VARCHAR(255) NOT NULL,
    `read_at` DATETIME NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX `idx_notif_user_read` (`user_id`, `read_at`),
    CONSTRAINT `fk_notif_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Create push_subscriptions table (Web Push notifications)
CREATE TABLE IF NOT EXISTS `push_subscriptions` (
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

-- 8. Create notification_queue table (background/cron processing)
CREATE TABLE IF NOT EXISTS `notification_queue` (
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

-- 9. Create audit_logs table (Admin/Editorial actions)
CREATE TABLE IF NOT EXISTS `audit_logs` (
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

-- 10. Create bookmarks table (Saved articles for readers)
CREATE TABLE IF NOT EXISTS `bookmarks` (
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

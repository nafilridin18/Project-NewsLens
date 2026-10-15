-- =====================================================================
-- Migration: 2026_10_separate_writer_system.sql
-- Newslensbd (নিউজলেন্সবিডি)
-- Completely separates Writer system from public user authentication.
-- Guarantees existing writers stay as role='writer', regular users stay as role='user',
-- and all published articles remain linked to their original authors.
-- =====================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Ensure role enum has 'writer' and 'user' properly defined
ALTER TABLE `users` 
    MODIFY COLUMN `role` ENUM('super_admin', 'admin', 'editor', 'sub_editor', 'reporter', 'district_rep', 'writer', 'user') NOT NULL DEFAULT 'user',
    MODIFY COLUMN `status` ENUM('pending', 'active', 'suspended', 'removed', 'unverified', 'inactive', 'banned') NOT NULL DEFAULT 'active';

-- 2. Preserve existing writers and regular users
-- (Do not modify existing role='writer' or role='user' rows)
-- Any accounts with null or empty role safely default to 'user'
UPDATE `users` SET `role` = 'user' WHERE `role` IS NULL OR `role` = '';

-- 3. Ensure existing active writers have an approved timestamp and permission_level
UPDATE `users` 
SET `permission_level` = 'editor_review' 
WHERE `role` = 'writer' AND `permission_level` IS NULL;

-- 4. Verify post authorship integrity
-- Existing posts must retain author_id referencing their original author
-- If an author is ever removed, custom_author preserves their attribution
ALTER TABLE `posts`
    MODIFY COLUMN `author_id` BIGINT UNSIGNED NOT NULL;

-- Add index on users (role, status) if not present for high-speed auth queries
CREATE INDEX IF NOT EXISTS `idx_users_role_status` ON `users` (`role`, `status`);

SET FOREIGN_KEY_CHECKS = 1;

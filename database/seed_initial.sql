-- =====================================================================
-- Newslensbd (নিউজলেন্সবিডি) Initial Seed Data
-- 8 Divisions & 14 Core Menu Categories + Default Settings
-- =====================================================================

-- 8 Divisions of Bangladesh
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

-- 14 Main Menu Categories
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
(14, 'প্রবাস', 'Probash', 'probash', NULL, 14, 1)
ON DUPLICATE KEY UPDATE `name_bn`=VALUES(`name_bn`);

-- Default Site Settings
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

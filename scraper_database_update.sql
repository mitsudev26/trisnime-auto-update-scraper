-- Database update untuk fitur Auto Update Scraper
-- Jalankan query ini untuk menambahkan tabel yang diperlukan

-- Tabel untuk menyimpan pengaturan scraper
CREATE TABLE IF NOT EXISTS `scraper_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `auto_update_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `last_update` datetime DEFAULT NULL,
  `update_interval` int(11) NOT NULL DEFAULT 3600,
  `max_episodes_per_anime` int(11) NOT NULL DEFAULT 50,
  `target_url` varchar(255) NOT NULL DEFAULT 'https://v1.animasu.top/',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert pengaturan default
INSERT INTO `scraper_settings` (`id`, `auto_update_enabled`, `update_interval`, `max_episodes_per_anime`, `target_url`, `created_at`) 
VALUES (1, 0, 3600, 50, 'https://v1.animasu.top/', NOW()) 
ON DUPLICATE KEY UPDATE `updated_at` = NOW();

-- Tabel untuk menyimpan log aktivitas scraper
CREATE TABLE IF NOT EXISTS `scraper_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text NOT NULL,
  `type` enum('info','success','warning','error') NOT NULL DEFAULT 'info',
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel untuk tracking anime yang sudah di-scrape (opsional, untuk optimasi)
CREATE TABLE IF NOT EXISTS `scraper_anime_tracking` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `anime_id` int(11) NOT NULL,
  `source_slug` varchar(255) NOT NULL,
  `source_url` varchar(500) NOT NULL,
  `last_scraped` datetime NOT NULL,
  `episodes_count` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','completed','error') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_source_slug` (`source_slug`),
  KEY `idx_anime_id` (`anime_id`),
  KEY `idx_last_scraped` (`last_scraped`),
  FOREIGN KEY (`anime_id`) REFERENCES `anime_list` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Update tabel anime_video untuk mendukung embed yang lebih baik
ALTER TABLE `anime_video` 
ADD COLUMN `embed_type` varchar(50) DEFAULT 'iframe' AFTER `video`,
ADD COLUMN `source_url` varchar(500) DEFAULT NULL AFTER `embed_type`,
ADD COLUMN `quality` varchar(20) DEFAULT NULL AFTER `source_url`,
ADD COLUMN `updated_at` datetime DEFAULT NULL AFTER `created_at`;

-- Index untuk performa yang lebih baik
ALTER TABLE `anime_video` 
ADD INDEX `idx_anime_eps` (`id_anime`, `eps`),
ADD INDEX `idx_updated_at` (`updated_at`);

-- Update tabel anime_list untuk tracking scraper
ALTER TABLE `anime_list` 
ADD COLUMN `source_url` varchar(500) DEFAULT NULL AFTER `seo_keywords`,
ADD COLUMN `scraped_at` datetime DEFAULT NULL AFTER `source_url`;

-- Index untuk anime_list
ALTER TABLE `anime_list` 
ADD INDEX `idx_scraped_at` (`scraped_at`),
ADD INDEX `idx_seo_slug` (`seo_slug`);

-- Tabel untuk menyimpan statistik scraper
CREATE TABLE IF NOT EXISTS `scraper_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `anime_added` int(11) NOT NULL DEFAULT 0,
  `anime_updated` int(11) NOT NULL DEFAULT 0,
  `episodes_added` int(11) NOT NULL DEFAULT 0,
  `episodes_updated` int(11) NOT NULL DEFAULT 0,
  `errors_count` int(11) NOT NULL DEFAULT 0,
  `execution_time` decimal(10,2) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_date` (`date`),
  KEY `idx_date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert log awal
INSERT INTO `scraper_logs` (`message`, `type`, `created_at`) 
VALUES ('Scraper system initialized', 'info', NOW());

-- Buat view untuk statistik cepat
CREATE OR REPLACE VIEW `scraper_dashboard_stats` AS
SELECT 
    (SELECT COUNT(*) FROM anime_list) as total_anime,
    (SELECT COUNT(*) FROM anime_video) as total_episodes,
    (SELECT COUNT(*) FROM anime_list WHERE DATE(created_at) = CURDATE()) as today_anime,
    (SELECT COUNT(*) FROM anime_video WHERE DATE(created_at) = CURDATE()) as today_episodes,
    (SELECT COUNT(*) FROM anime_list WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)) as recent_anime,
    (SELECT COUNT(*) FROM anime_video WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)) as recent_episodes,
    (SELECT last_update FROM scraper_settings WHERE id = 1) as last_update,
    (SELECT auto_update_enabled FROM scraper_settings WHERE id = 1) as auto_update_enabled;

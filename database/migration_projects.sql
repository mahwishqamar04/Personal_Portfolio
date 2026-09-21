-- ============================================
-- Mehwish Qamar Portfolio - Projects Migration
-- ============================================
-- WHAT THIS SCRIPT DOES:
--   1. Adds new columns to the existing projects table
--      (slug, short_description, detailed_description,
--       technologies, thumbnail, live_demo_url,
--       status, display_order, updated_at)
--   2. Populates the new columns from existing data
--   3. Creates the project_screenshots table for
--      multiple screenshots per project
--   4. Adds indexes for efficient querying
--
-- WHAT THIS SCRIPT DOES NOT DO:
--   - Does NOT drop or rename any existing column
--   - Does NOT delete any existing data
--   - Does NOT modify any other table
--
-- HOW TO USE:
--   1. BACK UP your database first (export via phpMyAdmin)
--   2. Open phpMyAdmin and select portfolio_db
--   3. Go to SQL tab, paste this file, click Go
--   4. Safe to run only ONCE. Running again will give
--      "Duplicate column" errors which is expected and OK.
--
-- COMPATIBILITY:
--   MySQL 5.7+ / MariaDB 10.2+
-- ============================================

-- ============================================
-- STEP 1: Add new columns to projects table
-- ============================================
-- Existing columns (id, title, description, category,
-- image, project_url, github_url, created_at) are untouched.

ALTER TABLE `projects`
    ADD COLUMN `slug` VARCHAR(200) DEFAULT NULL AFTER `id`,
    ADD COLUMN `short_description` VARCHAR(300) DEFAULT NULL AFTER `slug`,
    ADD COLUMN `detailed_description` TEXT DEFAULT NULL AFTER `short_description`,
    ADD COLUMN `technologies` TEXT DEFAULT NULL AFTER `detailed_description`,
    ADD COLUMN `thumbnail` VARCHAR(255) DEFAULT NULL AFTER `technologies`,
    ADD COLUMN `live_demo_url` VARCHAR(255) DEFAULT NULL AFTER `thumbnail`,
    ADD COLUMN `status` ENUM('active','inactive') NOT NULL DEFAULT 'active' AFTER `live_demo_url`,
    ADD COLUMN `display_order` INT NOT NULL DEFAULT 0 AFTER `status`,
    ADD COLUMN `updated_at` TIMESTAMP NULL DEFAULT NULL AFTER `created_at`;

-- ============================================
-- STEP 2: Populate new columns from existing data
-- ============================================

UPDATE `projects` SET
    slug = LOWER(
        REPLACE(
            REPLACE(
                REPLACE(
                    REPLACE(TRIM(`title`), ' ', '-'),
                '&', 'and'),
            '.', ''),
        '  ', '-')
    ),
    `short_description` = LEFT(COALESCE(`description`, ''), 300),
    `detailed_description` = `description`,
    `thumbnail` = `image`,
    `live_demo_url` = `project_url`,
    `status` = 'active',
    `display_order` = `id`,
    `updated_at` = `created_at`;

-- ============================================
-- STEP 3: Add indexes for performance
-- ============================================

ALTER TABLE `projects`
    ADD UNIQUE INDEX `idx_slug` (`slug`);

ALTER TABLE `projects`
    ADD INDEX `idx_status` (`status`);

ALTER TABLE `projects`
    ADD INDEX `idx_display_order` (`display_order`);

-- ============================================
-- STEP 4: Create project_screenshots table
-- ============================================
-- Supports MULTIPLE screenshots per project.
-- ON DELETE CASCADE: deleting a project auto-removes its screenshots.

CREATE TABLE IF NOT EXISTS `project_screenshots` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `project_id` INT UNSIGNED NOT NULL,
    `image_path` VARCHAR(255) NOT NULL,
    `caption` VARCHAR(200) DEFAULT NULL,
    `display_order` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_project_id` (`project_id`),
    INDEX `idx_display_order` (`display_order`),
    CONSTRAINT `fk_screenshot_project`
        FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- MIGRATION COMPLETE
-- ============================================

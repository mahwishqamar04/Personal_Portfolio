-- ============================================
-- Mehwish Qamar Portfolio - Complete Database Setup
-- ============================================
-- This script creates ALL tables for a fresh install.
--
-- TABLES CREATED:
--   1. contact_messages    - Contact form submissions
--   2. projects            - Portfolio projects (database-driven)
--   3. project_screenshots - Multiple screenshots per project
--   4. profile             - Profile/about information
--   5. services            - Services offered
--   6. skills              - Skills and competencies
--   7. certificates        - Education and certifications
--
-- HOW TO USE ON INFINITYFREE:
--   1. Create a MySQL database via the VistaPanel
--      (Control Panel > MySQL Databases).
--   2. Note the assigned database name, username,
--      and password.
--   3. Open phpMyAdmin from VistaPanel.
--   4. Select your newly created database.
--   5. Run this SQL file (Import tab or SQL tab).
--
-- HOW TO USE ON LOCAL XAMPP:
--   1. Open phpMyAdmin (http://localhost/phpmyadmin).
--   2. Create database "portfolio_db" (or run the
--      CREATE DATABASE statement below manually).
--   3. Select the database and run this SQL file.
--
-- NOTE: On InfinityFree you cannot create databases
-- via SQL. Create it through the control panel instead
-- and update .env with the assigned name.
-- For local XAMPP, you can uncomment the next two lines:
-- CREATE DATABASE IF NOT EXISTS portfolio_db
--     CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE portfolio_db;
-- ============================================


-- ============================================
-- Table: contact_messages
-- Stores contact form submissions
-- ============================================
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    is_read TINYINT(1) DEFAULT 0,
    INDEX idx_email (email),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================
-- Table: projects
-- Portfolio projects with full management support
-- Supports: title, slug, descriptions, technologies,
-- category, thumbnail, URLs, status, display order
-- ============================================
CREATE TABLE IF NOT EXISTS `projects` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `slug` VARCHAR(200) NOT NULL,
    `title` VARCHAR(150) NOT NULL,
    `short_description` VARCHAR(300) DEFAULT NULL,
    `detailed_description` TEXT DEFAULT NULL,
    `technologies` TEXT DEFAULT NULL,
    `category` VARCHAR(100) DEFAULT NULL,
    `thumbnail` VARCHAR(255) DEFAULT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `live_demo_url` VARCHAR(255) DEFAULT NULL,
    `project_url` VARCHAR(255) DEFAULT NULL,
    `github_url` VARCHAR(255) DEFAULT NULL,
    `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
    `display_order` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `idx_slug` (`slug`),
    INDEX `idx_status` (`status`),
    INDEX `idx_display_order` (`display_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================
-- Table: project_screenshots
-- Multiple screenshots per project (one-to-many)
-- Deleting a project auto-removes its screenshots
-- ============================================
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
-- Table: profile
-- Stores portfolio owner profile information
-- ============================================
CREATE TABLE IF NOT EXISTS `profile` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `full_name` VARCHAR(150) NOT NULL,
    `headline` VARCHAR(200) DEFAULT NULL,
    `bio` TEXT DEFAULT NULL,
    `education` VARCHAR(200) DEFAULT NULL,
    `experience` TEXT DEFAULT NULL,
    `profile_image` VARCHAR(255) DEFAULT NULL,
    `linkedin_url` VARCHAR(255) DEFAULT NULL,
    `github_url` VARCHAR(255) DEFAULT NULL,
    `upwork_url` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================
-- Table: services
-- Professional services offered
-- ============================================
CREATE TABLE IF NOT EXISTS `services` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(150) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `icon` VARCHAR(100) DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================
-- Table: skills
-- Skills and professional competencies
-- ============================================
CREATE TABLE IF NOT EXISTS `skills` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `skill_name` VARCHAR(100) NOT NULL,
    `skill_category` VARCHAR(100) DEFAULT NULL,
    `proficiency` INT DEFAULT 0,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ============================================
-- Table: certificates
-- Education and professional certifications
-- ============================================
CREATE TABLE IF NOT EXISTS `certificates` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(200) NOT NULL,
    `organization` VARCHAR(150) DEFAULT NULL,
    `certificate_date` DATE DEFAULT NULL,
    `certificate_url` VARCHAR(255) DEFAULT NULL,
    `image` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

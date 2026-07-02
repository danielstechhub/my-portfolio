-- =============================================================================
-- database/schema.sql
-- -----------------------------------------------------------------------------
-- Database schema for the Ayodeji Oluwafemi Daniel portfolio site.
-- Run this once against a local MySQL/MariaDB server to create the database
-- and its tables. Safe to re-run — uses CREATE IF NOT EXISTS throughout.
--
-- Import via phpMyAdmin: click "Import" -> choose this file -> Go.
-- Import via CLI:        mysql -u root -p < database/schema.sql
-- =============================================================================

CREATE DATABASE IF NOT EXISTS `portfolio_db`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `portfolio_db`;

-- -----------------------------------------------------------------------------
-- contact_submissions
-- Stores every validated contact form submission from components/contact.php.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `contact_submissions` (
  `id`             BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name`           VARCHAR(100)    NOT NULL,
  `email`          VARCHAR(150)    NOT NULL,
  `phone`          VARCHAR(20)     NULL,
  `company`        VARCHAR(150)    NULL,
  `subject`        VARCHAR(150)    NOT NULL,
  `message`        TEXT            NOT NULL,
  `project_type`   VARCHAR(30)     NULL,
  `budget`         VARCHAR(20)     NULL,
  `ip_address`     VARCHAR(45)     NOT NULL,        -- IPv4 or IPv6
  `user_agent`     VARCHAR(255)    NULL,
  `status`         ENUM('new', 'read', 'replied', 'archived', 'spam')
                     NOT NULL DEFAULT 'new',
  `created_at`     DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_contact_email` (`email`),
  KEY `idx_contact_status` (`status`),
  KEY `idx_contact_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- rate_limit_log
-- Optional persistent record of rate-limit hits, mirroring the file-based
-- limiter in includes/functions.php. Useful for auditing abuse over time;
-- the live rate-limit *decision* still runs off storage/logs/rate-limits
-- for zero-dependency operation, so the site works even without a DB.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `rate_limit_log` (
  `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `ip_address`  VARCHAR(45)     NOT NULL,
  `blocked`     TINYINT(1)      NOT NULL DEFAULT 0,
  `created_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_rate_ip_created` (`ip_address`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- visitor_analytics
-- Lightweight, privacy-conscious page-view log (no cookies, no fingerprinting).
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `visitor_analytics` (
  `id`          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `page`        VARCHAR(255)    NOT NULL,
  `referrer`    VARCHAR(255)    NULL,
  `ip_address`  VARCHAR(45)     NOT NULL,
  `user_agent`  VARCHAR(255)    NULL,
  `created_at`  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_analytics_page` (`page`),
  KEY `idx_analytics_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- admin_users
-- Minimal table for a future admin login to review submissions. Not wired up
-- yet in PHP — included so the schema is ready when that page is built.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `admin_users` (
  `id`            BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username`      VARCHAR(50)     NOT NULL,
  `email`         VARCHAR(150)    NOT NULL,
  `password_hash` VARCHAR(255)    NOT NULL,       -- store output of password_hash(), never plaintext
  `created_at`    DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_admin_username` (`username`),
  UNIQUE KEY `uniq_admin_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

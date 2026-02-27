-- ============================================================
-- QuickPropertyServices v2 - Simplified Database Schema
-- Database: quicdqyj_quickproperty
-- ============================================================

-- Run this on a FRESH database or after backing up existing data.
-- This drops old tables and creates the new simplified schema.

CREATE DATABASE IF NOT EXISTS `quicdqyj_quickproperty`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE `quicdqyj_quickproperty`;

-- ============================================================
-- DROP OLD TABLES (order matters due to foreign keys)
-- ============================================================
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `messages`;
DROP TABLE IF EXISTS `bookings`;
DROP TABLE IF EXISTS `services`;
DROP TABLE IF EXISTS `clients`;
DROP TABLE IF EXISTS `job_requests`;
DROP TABLE IF EXISTS `providers`;
DROP TABLE IF EXISTS `completion_media`;
DROP TABLE IF EXISTS `completion_reports`;
DROP TABLE IF EXISTS `estimate_media`;
DROP TABLE IF EXISTS `vendor_estimates`;
DROP TABLE IF EXISTS `vendor_assignments`;
DROP TABLE IF EXISTS `request_media`;
DROP TABLE IF EXISTS `service_requests`;
DROP TABLE IF EXISTS `vendors`;
DROP TABLE IF EXISTS `admin_history`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `category`;
SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- TABLE: users
-- Admin and Vendor accounts only
-- ============================================================
CREATE TABLE `users` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `role` ENUM('admin', 'vendor') NOT NULL DEFAULT 'vendor',
  `status` INT NOT NULL DEFAULT 1 COMMENT '1=Active, 2=Inactive',
  `created_at` DATE NOT NULL DEFAULT (CURRENT_DATE),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `uk_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: vendors
-- Extended profile for vendor users (1:1 with users)
-- ============================================================
CREATE TABLE `vendors` (
  `vendor_id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `business_name` VARCHAR(255) DEFAULT NULL,
  `specialty` VARCHAR(255) DEFAULT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `email` VARCHAR(255) DEFAULT NULL,
  `status` INT NOT NULL DEFAULT 1 COMMENT '1=Active, 2=Inactive',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`vendor_id`),
  UNIQUE KEY `uk_vendors_user_id` (`user_id`),
  CONSTRAINT `fk_vendors_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: category
-- Service categories for request classification
-- ============================================================
CREATE TABLE `category` (
  `category_id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: service_requests
-- Homeowner service requests (guest submissions)
-- ============================================================
CREATE TABLE `service_requests` (
  `request_id` INT NOT NULL AUTO_INCREMENT,
  `tracking_code` VARCHAR(12) NOT NULL,
  `homeowner_name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `address` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `category_id` INT DEFAULT NULL,
  `status` ENUM(
    'new','reviewing','vendors_assigned','estimates_received',
    'estimate_sent','homeowner_accepted','payment_received',
    'in_progress','completed','vendor_paid'
  ) NOT NULL DEFAULT 'new',
  `selected_estimate_id` INT DEFAULT NULL,
  `markup_percentage` DECIMAL(5,2) DEFAULT NULL,
  `markup_amount` DECIMAL(10,2) DEFAULT NULL,
  `final_price` DECIMAL(10,2) DEFAULT NULL,
  `payment_status` ENUM('pending','paid_escrow','released') NOT NULL DEFAULT 'pending',
  `admin_notes` TEXT DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`request_id`),
  UNIQUE KEY `uk_tracking_code` (`tracking_code`),
  KEY `idx_category_id` (`category_id`),
  KEY `idx_status` (`status`),
  CONSTRAINT `fk_requests_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: request_media
-- Photos/videos uploaded by homeowner with their request
-- ============================================================
CREATE TABLE `request_media` (
  `media_id` INT NOT NULL AUTO_INCREMENT,
  `request_id` INT NOT NULL,
  `file_path` VARCHAR(500) NOT NULL,
  `file_type` ENUM('image','video') NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`media_id`),
  KEY `idx_request_media_request` (`request_id`),
  CONSTRAINT `fk_request_media_request` FOREIGN KEY (`request_id`) REFERENCES `service_requests` (`request_id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: vendor_assignments
-- Links service requests to assigned vendors (max 5 per request)
-- ============================================================
CREATE TABLE `vendor_assignments` (
  `assignment_id` INT NOT NULL AUTO_INCREMENT,
  `request_id` INT NOT NULL,
  `vendor_id` INT NOT NULL,
  `status` ENUM('assigned','estimate_submitted','selected','not_selected') NOT NULL DEFAULT 'assigned',
  `assigned_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`assignment_id`),
  UNIQUE KEY `uk_request_vendor` (`request_id`, `vendor_id`),
  KEY `idx_va_vendor` (`vendor_id`),
  CONSTRAINT `fk_va_request` FOREIGN KEY (`request_id`) REFERENCES `service_requests` (`request_id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_va_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: vendor_estimates
-- Vendor price estimates for assigned requests
-- ============================================================
CREATE TABLE `vendor_estimates` (
  `estimate_id` INT NOT NULL AUTO_INCREMENT,
  `assignment_id` INT NOT NULL,
  `vendor_id` INT NOT NULL,
  `request_id` INT NOT NULL,
  `description` TEXT NOT NULL,
  `estimated_price` DECIMAL(10,2) NOT NULL,
  `timeline` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('submitted','selected','not_selected') NOT NULL DEFAULT 'submitted',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`estimate_id`),
  KEY `idx_ve_assignment` (`assignment_id`),
  KEY `idx_ve_vendor` (`vendor_id`),
  KEY `idx_ve_request` (`request_id`),
  CONSTRAINT `fk_ve_assignment` FOREIGN KEY (`assignment_id`) REFERENCES `vendor_assignments` (`assignment_id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ve_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ve_request` FOREIGN KEY (`request_id`) REFERENCES `service_requests` (`request_id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: estimate_media
-- Photos/videos from vendor estimates
-- ============================================================
CREATE TABLE `estimate_media` (
  `media_id` INT NOT NULL AUTO_INCREMENT,
  `estimate_id` INT NOT NULL,
  `file_path` VARCHAR(500) NOT NULL,
  `file_type` ENUM('image','video') NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`media_id`),
  KEY `idx_estimate_media_estimate` (`estimate_id`),
  CONSTRAINT `fk_estimate_media_estimate` FOREIGN KEY (`estimate_id`) REFERENCES `vendor_estimates` (`estimate_id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: completion_reports
-- Vendor proof of completed work
-- ============================================================
CREATE TABLE `completion_reports` (
  `report_id` INT NOT NULL AUTO_INCREMENT,
  `request_id` INT NOT NULL,
  `vendor_id` INT NOT NULL,
  `description` TEXT NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`report_id`),
  KEY `idx_cr_request` (`request_id`),
  KEY `idx_cr_vendor` (`vendor_id`),
  CONSTRAINT `fk_cr_request` FOREIGN KEY (`request_id`) REFERENCES `service_requests` (`request_id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_cr_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`vendor_id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: completion_media
-- Photos/videos proving project completion
-- ============================================================
CREATE TABLE `completion_media` (
  `media_id` INT NOT NULL AUTO_INCREMENT,
  `report_id` INT NOT NULL,
  `file_path` VARCHAR(500) NOT NULL,
  `file_type` ENUM('image','video') NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`media_id`),
  KEY `idx_completion_media_report` (`report_id`),
  CONSTRAINT `fk_completion_media_report` FOREIGN KEY (`report_id`) REFERENCES `completion_reports` (`report_id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: admin_history
-- Audit log for admin actions
-- ============================================================
CREATE TABLE `admin_history` (
  `history_id` INT NOT NULL AUTO_INCREMENT,
  `admin_id` INT NOT NULL,
  `action` VARCHAR(255) NOT NULL,
  `details` TEXT DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `user_agent` VARCHAR(255) DEFAULT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`history_id`),
  KEY `idx_admin_history_admin` (`admin_id`),
  CONSTRAINT `fk_admin_history_user` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- Add FK for selected_estimate_id (after vendor_estimates exists)
-- ============================================================
ALTER TABLE `service_requests`
  ADD CONSTRAINT `fk_requests_selected_estimate` FOREIGN KEY (`selected_estimate_id`)
    REFERENCES `vendor_estimates` (`estimate_id`)
    ON DELETE SET NULL ON UPDATE CASCADE;

-- ============================================================
-- SEED DATA: Default admin account
-- Password: admin123
-- ============================================================
INSERT INTO `users` (`first_name`, `last_name`, `email`, `password`, `role`, `status`, `created_at`)
VALUES ('Admin', 'User', 'admin@quickproperty.com', '$2y$10$gB0H1lnJF39g2iKviwGhu.5PXEskRkoLTabbDGyOg0PjEqPAxTBlm', 'admin', 1, CURRENT_DATE);

-- ============================================================
-- SEED DATA: Default categories
-- ============================================================
INSERT INTO `category` (`name`) VALUES
('Plumbing'),
('Electrical'),
('Painting'),
('Carpentry'),
('Roofing'),
('HVAC'),
('Landscaping'),
('Cleaning'),
('Flooring'),
('General Maintenance');

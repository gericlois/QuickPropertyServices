-- ============================================================
-- QuickPropertyServices Database Schema
-- Database: quicdqyj_quickproperty
-- ============================================================

CREATE DATABASE IF NOT EXISTS `quicdqyj_quickproperty`
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE `quicdqyj_quickproperty`;

-- ============================================================
-- TABLE: users
-- Central identity table for all roles (admin, provider, client)
-- ============================================================
CREATE TABLE `users` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `first_name` VARCHAR(100) NOT NULL,
  `last_name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `address` VARCHAR(255) DEFAULT NULL,
  `birthday` DATE DEFAULT NULL,
  `link_facebook` VARCHAR(255) DEFAULT NULL,
  `link_linkedin` VARCHAR(255) DEFAULT NULL,
  `link_instagram` VARCHAR(255) DEFAULT NULL,
  `role` ENUM('admin', 'provider', 'client') NOT NULL DEFAULT 'client',
  `status` INT NOT NULL DEFAULT 1 COMMENT '1=Active, 2=Inactive, 3=Banned',
  `created_at` DATE NOT NULL DEFAULT (CURRENT_DATE),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `uk_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: providers
-- Extended profile for users with role='provider' (1:1 with users)
-- ============================================================
CREATE TABLE `providers` (
  `provider_id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `business_name` VARCHAR(255) DEFAULT NULL,
  `work` VARCHAR(255) DEFAULT NULL COMMENT 'Type of work / specialty',
  `status` INT NOT NULL DEFAULT 2 COMMENT '1=Active, 2=Pending, 3=Inactive/Blocked',
  `created_at` DATE NOT NULL DEFAULT (CURRENT_DATE),
  PRIMARY KEY (`provider_id`),
  KEY `idx_providers_user_id` (`user_id`),
  CONSTRAINT `fk_providers_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: clients
-- Extended profile for users with role='client' (1:1 with users)
-- ============================================================
CREATE TABLE `clients` (
  `client_id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `status` INT NOT NULL DEFAULT 1 COMMENT '1=Active, 2=Inactive, 3=Banned',
  `created_at` DATE NOT NULL DEFAULT (CURRENT_DATE),
  PRIMARY KEY (`client_id`),
  KEY `idx_clients_user_id` (`user_id`),
  CONSTRAINT `fk_clients_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: category
-- Service categories referenced by the services table
-- ============================================================
CREATE TABLE `category` (
  `category_id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: services
-- Services offered by providers
-- ============================================================
CREATE TABLE `services` (
  `service_id` INT NOT NULL AUTO_INCREMENT,
  `provider_id` INT NOT NULL,
  `category_id` INT DEFAULT NULL,
  `service_name` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `base_price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `status` INT NOT NULL DEFAULT 1 COMMENT '1=Active, 2=Inactive/Deactivated',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`service_id`),
  KEY `idx_services_provider_id` (`provider_id`),
  KEY `idx_services_category_id` (`category_id`),
  CONSTRAINT `fk_services_provider` FOREIGN KEY (`provider_id`) REFERENCES `providers` (`provider_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_services_category` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: bookings
-- Service bookings between clients and providers
-- ============================================================
CREATE TABLE `bookings` (
  `booking_id` INT NOT NULL AUTO_INCREMENT,
  `client_id` INT NOT NULL COMMENT 'References users.user_id (the client user)',
  `provider_id` INT NOT NULL,
  `service_id` INT NOT NULL,
  `appointment_date` DATETIME NOT NULL,
  `total_price` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  `status` INT NOT NULL DEFAULT 1 COMMENT '1=Pending, 2=Accepted, 3=Done, 4=Declined',
  `created_at` DATE NOT NULL DEFAULT (CURRENT_DATE),
  PRIMARY KEY (`booking_id`),
  KEY `idx_bookings_client_id` (`client_id`),
  KEY `idx_bookings_provider_id` (`provider_id`),
  KEY `idx_bookings_service_id` (`service_id`),
  CONSTRAINT `fk_bookings_client` FOREIGN KEY (`client_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_bookings_provider` FOREIGN KEY (`provider_id`) REFERENCES `providers` (`provider_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_bookings_service` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: job_requests
-- Property service requests submitted by homeowners
-- ============================================================
CREATE TABLE `job_requests` (
  `request_id` INT NOT NULL AUTO_INCREMENT,
  `client_id` INT DEFAULT NULL,
  `contact_source` VARCHAR(100) DEFAULT NULL,
  `homeowner_name` VARCHAR(255) NOT NULL,
  `address` VARCHAR(255) NOT NULL,
  `phone1` VARCHAR(20) NOT NULL,
  `phone2` VARCHAR(20) DEFAULT NULL,
  `email` VARCHAR(255) NOT NULL,
  `work_description` TEXT NOT NULL,
  `estimator_notes` TEXT DEFAULT NULL,
  `crew_instructions` TEXT DEFAULT NULL,
  `image1` VARCHAR(255) DEFAULT NULL,
  `image2` VARCHAR(255) DEFAULT NULL,
  `image3` VARCHAR(255) DEFAULT NULL,
  `image4` VARCHAR(255) DEFAULT NULL,
  `image5` VARCHAR(255) DEFAULT NULL,
  `status` VARCHAR(50) NOT NULL DEFAULT 'Hot Lead' COMMENT 'Hot Lead, Appointment for Estimate, Estimate Needed, Estimate in Progress, Estimate Follow Up, Assigned to Vendor, Estimate Approved, Project in Progress, Project Completed, Project Invoiced, Project Done',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`request_id`),
  KEY `idx_job_requests_client_id` (`client_id`),
  CONSTRAINT `fk_job_requests_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: messages
-- Direct messages between clients and providers
-- ============================================================
CREATE TABLE `messages` (
  `message_id` INT NOT NULL AUTO_INCREMENT,
  `provider_id` INT NOT NULL,
  `client_id` INT NOT NULL,
  `sender_type` ENUM('provider', 'client') NOT NULL,
  `content` TEXT NOT NULL,
  `status` ENUM('unread', 'read') NOT NULL DEFAULT 'unread',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`message_id`),
  KEY `idx_messages_provider_id` (`provider_id`),
  KEY `idx_messages_client_id` (`client_id`),
  CONSTRAINT `fk_messages_provider` FOREIGN KEY (`provider_id`) REFERENCES `providers` (`provider_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_messages_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`client_id`) ON DELETE CASCADE ON UPDATE CASCADE
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
  KEY `idx_admin_history_admin_id` (`admin_id`),
  CONSTRAINT `fk_admin_history_user` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- SEED DATA: Default admin account
-- Password: admin123 (hashed with password_hash)
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

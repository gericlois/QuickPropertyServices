-- ============================================================
-- Homeowner Authentication Migration
-- Adds homeowners table and links to service_requests
-- ============================================================

CREATE TABLE IF NOT EXISTS `homeowners` (
  `homeowner_id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `status` TINYINT NOT NULL DEFAULT 1,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`homeowner_id`),
  UNIQUE KEY `uk_homeowner_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add homeowner_id to service_requests
ALTER TABLE `service_requests` ADD COLUMN `homeowner_id` INT DEFAULT NULL AFTER `request_id`;
ALTER TABLE `service_requests` ADD CONSTRAINT `fk_sr_homeowner` FOREIGN KEY (`homeowner_id`) REFERENCES `homeowners`(`homeowner_id`) ON DELETE SET NULL;

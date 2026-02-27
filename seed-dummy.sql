-- ============================================================
-- QuickPropertyServices v2 - Dummy Test Data
-- Run AFTER database-v2.sql
-- ============================================================

USE `quicdqyj_quickproperty`;

-- ============================================================
-- VENDORS (5 vendors with user accounts)
-- Password for all: vendor123
-- ============================================================
INSERT INTO `users` (`first_name`, `last_name`, `email`, `password`, `phone`, `role`, `status`, `created_at`) VALUES
('Mike', 'Johnson', 'mike@vendor.com', '$2y$10$gB0H1lnJF39g2iKviwGhu.5PXEskRkoLTabbDGyOg0PjEqPAxTBlm', '801-555-1001', 'vendor', 1, '2025-12-01'),
('Sarah', 'Williams', 'sarah@vendor.com', '$2y$10$gB0H1lnJF39g2iKviwGhu.5PXEskRkoLTabbDGyOg0PjEqPAxTBlm', '801-555-1002', 'vendor', 1, '2025-12-05'),
('Carlos', 'Rivera', 'carlos@vendor.com', '$2y$10$gB0H1lnJF39g2iKviwGhu.5PXEskRkoLTabbDGyOg0PjEqPAxTBlm', '801-555-1003', 'vendor', 1, '2025-12-10'),
('Lisa', 'Chen', 'lisa@vendor.com', '$2y$10$gB0H1lnJF39g2iKviwGhu.5PXEskRkoLTabbDGyOg0PjEqPAxTBlm', '801-555-1004', 'vendor', 1, '2026-01-02'),
('James', 'Brown', 'james@vendor.com', '$2y$10$gB0H1lnJF39g2iKviwGhu.5PXEskRkoLTabbDGyOg0PjEqPAxTBlm', '801-555-1005', 'vendor', 2, '2026-01-10');

INSERT INTO `vendors` (`user_id`, `business_name`, `specialty`, `phone`, `email`, `status`, `created_at`) VALUES
((SELECT user_id FROM users WHERE email='mike@vendor.com'), 'Mike\'s Plumbing Co.', 'Plumbing', '801-555-1001', 'mike@vendor.com', 1, '2025-12-01'),
((SELECT user_id FROM users WHERE email='sarah@vendor.com'), 'Williams Electric', 'Electrical', '801-555-1002', 'sarah@vendor.com', 1, '2025-12-05'),
((SELECT user_id FROM users WHERE email='carlos@vendor.com'), 'Rivera Painting & Drywall', 'Painting', '801-555-1003', 'carlos@vendor.com', 1, '2025-12-10'),
((SELECT user_id FROM users WHERE email='lisa@vendor.com'), 'Chen HVAC Solutions', 'HVAC', '801-555-1004', 'lisa@vendor.com', 1, '2026-01-02'),
((SELECT user_id FROM users WHERE email='james@vendor.com'), 'Brown Roofing', 'Roofing', '801-555-1005', 'james@vendor.com', 2, '2026-01-10');

-- ============================================================
-- SERVICE REQUESTS (10 requests at various stages)
-- ============================================================

-- Request 1: NEW
INSERT INTO `service_requests` (`tracking_code`, `homeowner_name`, `email`, `phone`, `address`, `description`, `category_id`, `status`, `payment_status`, `created_at`) VALUES
('QPS-A1B2C3D4', 'John Smith', 'john.smith@email.com', '801-200-1001', '123 Main St, Salt Lake City, UT 84101',
'Kitchen sink is leaking badly under the cabinet. Water is pooling on the floor and I can see mold starting to form. Need urgent repair.', 1, 'new', 'pending', '2026-02-20 09:30:00');

-- Request 2: NEW
INSERT INTO `service_requests` (`tracking_code`, `homeowner_name`, `email`, `phone`, `address`, `description`, `category_id`, `status`, `payment_status`, `created_at`) VALUES
('QPS-E5F6G7H8', 'Emily Davis', 'emily.davis@email.com', '801-200-1002', '456 Oak Ave, Provo, UT 84601',
'Several electrical outlets in the living room have stopped working. Breaker trips when I plug anything in. House is 30 years old.', 2, 'new', 'pending', '2026-02-22 14:15:00');

-- Request 3: REVIEWING
INSERT INTO `service_requests` (`tracking_code`, `homeowner_name`, `email`, `phone`, `address`, `description`, `category_id`, `status`, `payment_status`, `created_at`) VALUES
('QPS-I9J0K1L2', 'Robert Garcia', 'robert.garcia@email.com', '801-200-1003', '789 Pine Rd, Ogden, UT 84401',
'Need the entire master bedroom repainted. Walls have water stains and peeling paint from a previous roof leak that has been fixed.', 3, 'reviewing', 'pending', '2026-02-18 11:00:00');

-- Request 4: VENDORS_ASSIGNED (3 vendors assigned, waiting for estimates)
INSERT INTO `service_requests` (`tracking_code`, `homeowner_name`, `email`, `phone`, `address`, `description`, `category_id`, `status`, `payment_status`, `created_at`) VALUES
('QPS-M3N4O5P6', 'Amanda Thompson', 'amanda.t@email.com', '801-200-1004', '321 Elm St, Sandy, UT 84070',
'Central AC unit is blowing warm air. System is about 12 years old. Might need a full replacement or repair. Want professional assessment.', 6, 'vendors_assigned', 'pending', '2026-02-15 08:45:00');

-- Request 5: ESTIMATES_RECEIVED (all vendors submitted estimates)
INSERT INTO `service_requests` (`tracking_code`, `homeowner_name`, `email`, `phone`, `address`, `description`, `category_id`, `status`, `payment_status`, `created_at`) VALUES
('QPS-Q7R8S9T0', 'David Wilson', 'david.wilson@email.com', '801-200-1005', '654 Maple Dr, Draper, UT 84020',
'Hardwood floors in the hallway are warped and buckled from water damage. Need about 200 sq ft replaced and refinished.', 9, 'estimates_received', 'pending', '2026-02-10 16:30:00');

-- Request 6: ESTIMATE_SENT (admin picked best, sent to homeowner)
INSERT INTO `service_requests` (`tracking_code`, `homeowner_name`, `email`, `phone`, `address`, `description`, `category_id`, `status`, `markup_percentage`, `markup_amount`, `final_price`, `payment_status`, `created_at`) VALUES
('QPS-U1V2W3X4', 'Jennifer Martinez', 'jennifer.m@email.com', '801-200-1006', '987 Cedar Ln, Lehi, UT 84043',
'Backyard landscaping overhaul - remove old shrubs, install new sod, add flower beds, and build a small retaining wall.', 7, 'estimate_sent', 15.00, 525.00, 4025.00, 'pending', '2026-02-05 10:00:00');

-- Request 7: HOMEOWNER_ACCEPTED
INSERT INTO `service_requests` (`tracking_code`, `homeowner_name`, `email`, `phone`, `address`, `description`, `category_id`, `status`, `markup_percentage`, `markup_amount`, `final_price`, `payment_status`, `created_at`) VALUES
('QPS-Y5Z6A7B8', 'Michael Brown', 'michael.b@email.com', '801-200-1007', '147 Birch Ct, Orem, UT 84057',
'Roof has several missing shingles after the recent storm. Possible leak in the attic. Need inspection and repair ASAP.', 5, 'homeowner_accepted', 20.00, 400.00, 2400.00, 'pending', '2026-01-28 13:20:00');

-- Request 8: PAYMENT_RECEIVED / IN_PROGRESS
INSERT INTO `service_requests` (`tracking_code`, `homeowner_name`, `email`, `phone`, `address`, `description`, `category_id`, `status`, `markup_percentage`, `markup_amount`, `final_price`, `payment_status`, `created_at`) VALUES
('QPS-C9D0E1F2', 'Sarah Johnson', 'sarah.j@email.com', '801-200-1008', '258 Spruce Way, American Fork, UT 84003',
'Complete bathroom renovation - replace toilet, vanity, shower tile, and fixtures. Want modern farmhouse style.', 1, 'in_progress', 18.00, 990.00, 6490.00, 'paid_escrow', '2026-01-20 09:00:00');

-- Request 9: COMPLETED (waiting for vendor payment)
INSERT INTO `service_requests` (`tracking_code`, `homeowner_name`, `email`, `phone`, `address`, `description`, `category_id`, `status`, `markup_percentage`, `markup_amount`, `final_price`, `payment_status`, `created_at`) VALUES
('QPS-G3H4I5J6', 'Thomas Lee', 'thomas.lee@email.com', '801-200-1009', '369 Walnut Blvd, Taylorsville, UT 84129',
'Kitchen cabinet refacing and new countertop installation. Replacing laminate with quartz.', 4, 'completed', 15.00, 600.00, 4600.00, 'paid_escrow', '2026-01-10 11:30:00');

-- Request 10: VENDOR_PAID (fully completed)
INSERT INTO `service_requests` (`tracking_code`, `homeowner_name`, `email`, `phone`, `address`, `description`, `category_id`, `status`, `markup_percentage`, `markup_amount`, `final_price`, `payment_status`, `created_at`) VALUES
('QPS-K7L8M9N0', 'Karen White', 'karen.white@email.com', '801-200-1010', '741 Aspen St, Murray, UT 84107',
'Deep cleaning of entire 4-bedroom house including carpet shampooing, window washing, and garage cleanout.', 8, 'vendor_paid', 20.00, 300.00, 1800.00, 'released', '2025-12-15 14:00:00');

-- ============================================================
-- VENDOR ASSIGNMENTS
-- ============================================================

-- Request 4 (vendors_assigned): 3 vendors assigned
INSERT INTO `vendor_assignments` (`request_id`, `vendor_id`, `status`, `assigned_at`) VALUES
(4, 4, 'assigned', '2026-02-16 10:00:00'),
(4, 1, 'assigned', '2026-02-16 10:05:00'),
(4, 2, 'assigned', '2026-02-16 10:10:00');

-- Request 5 (estimates_received): 3 vendors, all submitted
INSERT INTO `vendor_assignments` (`request_id`, `vendor_id`, `status`, `assigned_at`) VALUES
(5, 1, 'estimate_submitted', '2026-02-11 09:00:00'),
(5, 3, 'estimate_submitted', '2026-02-11 09:05:00'),
(5, 4, 'estimate_submitted', '2026-02-11 09:10:00');

-- Request 6 (estimate_sent): 2 vendors, 1 selected
INSERT INTO `vendor_assignments` (`request_id`, `vendor_id`, `status`, `assigned_at`) VALUES
(6, 3, 'selected', '2026-02-06 08:00:00'),
(6, 1, 'not_selected', '2026-02-06 08:05:00');

-- Request 7 (homeowner_accepted): 3 vendors, 1 selected
INSERT INTO `vendor_assignments` (`request_id`, `vendor_id`, `status`, `assigned_at`) VALUES
(7, 1, 'not_selected', '2026-01-29 09:00:00'),
(7, 3, 'selected', '2026-01-29 09:05:00'),
(7, 2, 'not_selected', '2026-01-29 09:10:00');

-- Request 8 (in_progress): 2 vendors, 1 selected
INSERT INTO `vendor_assignments` (`request_id`, `vendor_id`, `status`, `assigned_at`) VALUES
(8, 1, 'selected', '2026-01-21 10:00:00'),
(8, 4, 'not_selected', '2026-01-21 10:05:00');

-- Request 9 (completed): 2 vendors, 1 selected
INSERT INTO `vendor_assignments` (`request_id`, `vendor_id`, `status`, `assigned_at`) VALUES
(9, 3, 'not_selected', '2026-01-11 08:00:00'),
(9, 1, 'selected', '2026-01-11 08:05:00');

-- Request 10 (vendor_paid): 2 vendors, 1 selected
INSERT INTO `vendor_assignments` (`request_id`, `vendor_id`, `status`, `assigned_at`) VALUES
(10, 2, 'selected', '2025-12-16 09:00:00'),
(10, 3, 'not_selected', '2025-12-16 09:05:00');

-- ============================================================
-- VENDOR ESTIMATES
-- ============================================================

-- Request 5: 3 estimates submitted
INSERT INTO `vendor_estimates` (`assignment_id`, `vendor_id`, `request_id`, `description`, `estimated_price`, `timeline`, `status`, `created_at`) VALUES
(4, 1, 5, 'Will remove damaged hardwood, install new matching oak planks, sand and refinish entire hallway. Includes materials and labor.', 3200.00, '5-7 business days', 'submitted', '2026-02-13 11:00:00'),
(5, 3, 5, 'Full tear-out and replacement with premium engineered hardwood. Three coats of polyurethane finish. 2-year warranty.', 4100.00, '7-10 business days', 'submitted', '2026-02-14 09:30:00'),
(6, 4, 5, 'Patch repair where possible, full replacement only where necessary. Refinish to match existing floors.', 2800.00, '4-5 business days', 'submitted', '2026-02-14 14:00:00');

-- Request 6: 2 estimates (1 selected)
INSERT INTO `vendor_estimates` (`assignment_id`, `vendor_id`, `request_id`, `description`, `estimated_price`, `timeline`, `status`, `created_at`) VALUES
(7, 3, 6, 'Complete landscaping package: shrub removal, sod installation (1500 sqft), 3 flower beds with seasonal plants, 40ft retaining wall with drainage.', 3500.00, '10-14 business days', 'selected', '2026-02-07 10:00:00'),
(8, 1, 6, 'Basic landscaping: remove shrubs, lay sod, simple flower bed. Retaining wall with concrete blocks.', 4200.00, '14-18 business days', 'not_selected', '2026-02-08 15:00:00');

-- Update selected_estimate_id for Request 6
UPDATE `service_requests` SET `selected_estimate_id` = (SELECT estimate_id FROM vendor_estimates WHERE request_id = 6 AND status = 'selected') WHERE request_id = 6;

-- Request 7: 3 estimates (1 selected)
INSERT INTO `vendor_estimates` (`assignment_id`, `vendor_id`, `request_id`, `description`, `estimated_price`, `timeline`, `status`, `created_at`) VALUES
(9, 1, 7, 'Roof inspection, replace 50+ missing shingles, seal all exposed areas. Check attic for water damage.', 2500.00, '2-3 business days', 'not_selected', '2026-01-30 11:00:00'),
(10, 3, 7, 'Full roof section repair including underlayment replacement. Architectural shingles to match existing. 5-year workmanship warranty.', 2000.00, '3-4 business days', 'selected', '2026-01-31 09:00:00'),
(11, 2, 7, 'Emergency patch job for immediate leaks plus full shingle replacement on damaged section.', 2200.00, '1-2 business days', 'not_selected', '2026-01-31 14:00:00');

UPDATE `service_requests` SET `selected_estimate_id` = (SELECT estimate_id FROM vendor_estimates WHERE request_id = 7 AND status = 'selected') WHERE request_id = 7;

-- Request 8: 2 estimates (1 selected)
INSERT INTO `vendor_estimates` (`assignment_id`, `vendor_id`, `request_id`, `description`, `estimated_price`, `timeline`, `status`, `created_at`) VALUES
(12, 1, 8, 'Full bathroom renovation: remove old fixtures, install new toilet, vanity, frameless shower with subway tile, modern fixtures. Farmhouse style.', 5500.00, '14-18 business days', 'selected', '2026-01-22 10:00:00'),
(13, 4, 8, 'Bathroom remodel with standard fixtures. Ceramic tile shower, stock vanity, chrome fixtures.', 4200.00, '10-12 business days', 'not_selected', '2026-01-23 11:00:00');

UPDATE `service_requests` SET `selected_estimate_id` = (SELECT estimate_id FROM vendor_estimates WHERE request_id = 8 AND status = 'selected') WHERE request_id = 8;

-- Request 9: 2 estimates (1 selected)
INSERT INTO `vendor_estimates` (`assignment_id`, `vendor_id`, `request_id`, `description`, `estimated_price`, `timeline`, `status`, `created_at`) VALUES
(14, 3, 9, 'Cabinet refacing with maple veneer, new soft-close hinges and pulls. Basic laminate countertop.', 3500.00, '7-10 business days', 'not_selected', '2026-01-12 10:00:00'),
(15, 1, 9, 'Premium cabinet refacing with shaker-style doors, quartz countertop (Calacatta), undermount sink cutout. 3-year warranty.', 4000.00, '10-14 business days', 'selected', '2026-01-13 09:00:00');

UPDATE `service_requests` SET `selected_estimate_id` = (SELECT estimate_id FROM vendor_estimates WHERE request_id = 9 AND status = 'selected') WHERE request_id = 9;

-- Request 10: 2 estimates (1 selected)
INSERT INTO `vendor_estimates` (`assignment_id`, `vendor_id`, `request_id`, `description`, `estimated_price`, `timeline`, `status`, `created_at`) VALUES
(16, 2, 10, 'Deep clean 4BR house: all rooms, bathrooms, kitchen. Carpet shampoo, window cleaning inside/out, garage sweep and organize.', 1500.00, '2 days', 'selected', '2025-12-17 10:00:00'),
(17, 3, 10, 'Standard deep cleaning package for 4BR. Carpet steam clean. Basic window wipe. No garage.', 1100.00, '1 day', 'not_selected', '2025-12-18 09:00:00');

UPDATE `service_requests` SET `selected_estimate_id` = (SELECT estimate_id FROM vendor_estimates WHERE request_id = 10 AND status = 'selected') WHERE request_id = 10;

-- ============================================================
-- COMPLETION REPORTS
-- ============================================================

-- Request 9: Completed
INSERT INTO `completion_reports` (`request_id`, `vendor_id`, `description`, `created_at`) VALUES
(9, 1, 'All cabinets have been refaced with shaker-style doors in white. Quartz countertops installed with undermount sink. All hardware is brushed nickel soft-close. Kitchen looks brand new. Homeowner was present during final walkthrough and approved the work.', '2026-02-01 16:00:00');

-- Request 10: Completed and paid
INSERT INTO `completion_reports` (`request_id`, `vendor_id`, `description`, `created_at`) VALUES
(10, 2, 'Full deep cleaning completed. All 4 bedrooms, 3 bathrooms, kitchen, living areas cleaned thoroughly. Carpets shampooed and dried. All windows washed inside and outside. Garage swept, organized, and cobwebs removed. House is move-in ready.', '2025-12-20 15:00:00');

-- ============================================================
-- ADMIN HISTORY (sample audit entries)
-- ============================================================
INSERT INTO `admin_history` (`admin_id`, `action`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 'Admin Login', 'Admin user logged in successfully', '127.0.0.1', 'Mozilla/5.0', '2026-02-25 08:00:00'),
(1, 'Created Vendor', 'Created vendor: Mike\'s Plumbing Co. (mike@vendor.com)', '127.0.0.1', 'Mozilla/5.0', '2025-12-01 10:00:00'),
(1, 'Created Vendor', 'Created vendor: Williams Electric (sarah@vendor.com)', '127.0.0.1', 'Mozilla/5.0', '2025-12-05 10:00:00'),
(1, 'Created Vendor', 'Created vendor: Rivera Painting & Drywall (carlos@vendor.com)', '127.0.0.1', 'Mozilla/5.0', '2025-12-10 10:00:00'),
(1, 'Assigned Vendors', 'Assigned 3 vendors to request QPS-M3N4O5P6', '127.0.0.1', 'Mozilla/5.0', '2026-02-16 10:15:00'),
(1, 'Selected Estimate', 'Selected estimate for request QPS-U1V2W3X4 with 15% markup', '127.0.0.1', 'Mozilla/5.0', '2026-02-08 11:00:00'),
(1, 'Updated Request Status', 'Request QPS-C9D0E1F2 status changed to in_progress', '127.0.0.1', 'Mozilla/5.0', '2026-01-25 09:00:00'),
(1, 'Updated Request Status', 'Request QPS-G3H4I5J6 status changed to completed', '127.0.0.1', 'Mozilla/5.0', '2026-02-01 17:00:00'),
(1, 'Updated Request Status', 'Request QPS-K7L8M9N0 vendor paid - payment released', '127.0.0.1', 'Mozilla/5.0', '2025-12-22 10:00:00');

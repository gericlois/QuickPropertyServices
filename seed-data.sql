-- ============================================================
-- QuickPropertyServices - Dummy / Seed Data
-- Run AFTER database.sql has been imported
-- ============================================================

USE `quicdqyj_quickproperty`;

-- ============================================================
-- USERS: Providers (user_id 2-6)
-- ============================================================
INSERT INTO `users` (`first_name`, `last_name`, `email`, `password`, `phone`, `address`, `birthday`, `link_facebook`, `link_linkedin`, `link_instagram`, `role`, `status`, `created_at`) VALUES
('James', 'Wilson', 'james.wilson@email.com', '$2y$10$gB0H1lnJF39g2iKviwGhu.5PXEskRkoLTabbDGyOg0PjEqPAxTBlm', '801-555-0101', '123 Main St, Salt Lake City, UT', '1985-03-15', 'https://facebook.com/jameswilson', 'https://linkedin.com/in/jameswilson', 'https://instagram.com/jameswilson', 'provider', 1, '2025-01-10'),
('Maria', 'Garcia', 'maria.garcia@email.com', '$2y$10$gB0H1lnJF39g2iKviwGhu.5PXEskRkoLTabbDGyOg0PjEqPAxTBlm', '801-555-0102', '456 Oak Ave, Provo, UT', '1990-07-22', 'https://facebook.com/mariagarcia', 'https://linkedin.com/in/mariagarcia', 'https://instagram.com/mariagarcia', 'provider', 1, '2025-02-05'),
('Robert', 'Chen', 'robert.chen@email.com', '$2y$10$gB0H1lnJF39g2iKviwGhu.5PXEskRkoLTabbDGyOg0PjEqPAxTBlm', '801-555-0103', '789 Pine Rd, Ogden, UT', '1988-11-08', '', '', '', 'provider', 1, '2025-03-12'),
('Sarah', 'Johnson', 'sarah.johnson@email.com', '$2y$10$gB0H1lnJF39g2iKviwGhu.5PXEskRkoLTabbDGyOg0PjEqPAxTBlm', '801-555-0104', '321 Elm St, Sandy, UT', '1992-05-30', '', 'https://linkedin.com/in/sarahjohnson', '', 'provider', 2, '2025-04-01'),
('David', 'Martinez', 'david.martinez@email.com', '$2y$10$gB0H1lnJF39g2iKviwGhu.5PXEskRkoLTabbDGyOg0PjEqPAxTBlm', '801-555-0105', '654 Birch Ln, Lehi, UT', '1980-09-14', '', '', 'https://instagram.com/davidmartinez', 'provider', 1, '2025-05-20');

-- ============================================================
-- USERS: Clients (user_id 7-16)
-- ============================================================
INSERT INTO `users` (`first_name`, `last_name`, `email`, `password`, `phone`, `address`, `birthday`, `role`, `status`, `created_at`) VALUES
('Emily', 'Davis', 'emily.davis@email.com', '$2y$10$gB0H1lnJF39g2iKviwGhu.5PXEskRkoLTabbDGyOg0PjEqPAxTBlm', '801-555-0201', '100 Maple Dr, Salt Lake City, UT', '1995-01-20', 'client', 1, '2025-01-15'),
('Michael', 'Brown', 'michael.brown@email.com', '$2y$10$gB0H1lnJF39g2iKviwGhu.5PXEskRkoLTabbDGyOg0PjEqPAxTBlm', '801-555-0202', '200 Cedar St, Draper, UT', '1987-06-10', 'client', 1, '2025-02-20'),
('Jessica', 'Taylor', 'jessica.taylor@email.com', '$2y$10$gB0H1lnJF39g2iKviwGhu.5PXEskRkoLTabbDGyOg0PjEqPAxTBlm', '801-555-0203', '300 Willow Ave, Murray, UT', '1993-04-05', 'client', 1, '2025-03-10'),
('Daniel', 'Anderson', 'daniel.anderson@email.com', '$2y$10$gB0H1lnJF39g2iKviwGhu.5PXEskRkoLTabbDGyOg0PjEqPAxTBlm', '801-555-0204', '400 Spruce Ct, Taylorsville, UT', '1991-08-18', 'client', 1, '2025-04-05'),
('Ashley', 'Thomas', 'ashley.thomas@email.com', '$2y$10$gB0H1lnJF39g2iKviwGhu.5PXEskRkoLTabbDGyOg0PjEqPAxTBlm', '801-555-0205', '500 Aspen Way, West Jordan, UT', '1989-12-25', 'client', 1, '2025-05-15'),
('Christopher', 'Lee', 'chris.lee@email.com', '$2y$10$gB0H1lnJF39g2iKviwGhu.5PXEskRkoLTabbDGyOg0PjEqPAxTBlm', '801-555-0206', '600 Poplar Rd, South Jordan, UT', '1986-02-14', 'client', 1, '2025-06-01'),
('Amanda', 'White', 'amanda.white@email.com', '$2y$10$gB0H1lnJF39g2iKviwGhu.5PXEskRkoLTabbDGyOg0PjEqPAxTBlm', '801-555-0207', '700 Walnut Blvd, Riverton, UT', '1994-10-03', 'client', 2, '2025-07-10'),
('Matthew', 'Harris', 'matthew.harris@email.com', '$2y$10$gB0H1lnJF39g2iKviwGhu.5PXEskRkoLTabbDGyOg0PjEqPAxTBlm', '801-555-0208', '800 Hickory Pl, Herriman, UT', '1983-07-29', 'client', 1, '2025-08-01'),
('Lauren', 'Clark', 'lauren.clark@email.com', '$2y$10$gB0H1lnJF39g2iKviwGhu.5PXEskRkoLTabbDGyOg0PjEqPAxTBlm', '801-555-0209', '900 Chestnut Ln, Midvale, UT', '1996-03-12', 'client', 3, '2025-09-05'),
('Andrew', 'Lewis', 'andrew.lewis@email.com', '$2y$10$gB0H1lnJF39g2iKviwGhu.5PXEskRkoLTabbDGyOg0PjEqPAxTBlm', '801-555-0210', '1000 Sycamore St, Cottonwood, UT', '1990-11-21', 'client', 1, '2025-10-15');

-- ============================================================
-- PROVIDERS table (linked to provider users)
-- ============================================================
INSERT INTO `providers` (`user_id`, `business_name`, `work`, `status`, `created_at`) VALUES
(2, 'Wilson Plumbing & Heating', 'Plumbing', 1, '2025-01-10'),
(3, 'Garcia Electric Solutions', 'Electrical', 1, '2025-02-05'),
(4, 'Chen Painting Pros', 'Painting', 1, '2025-03-12'),
(5, 'Johnson Carpentry Works', 'Carpentry', 2, '2025-04-01'),
(6, 'Martinez Roofing Co.', 'Roofing', 1, '2025-05-20');

-- ============================================================
-- CLIENTS table (linked to client users)
-- ============================================================
INSERT INTO `clients` (`user_id`, `status`, `created_at`) VALUES
(7, 1, '2025-01-15'),
(8, 1, '2025-02-20'),
(9, 1, '2025-03-10'),
(10, 1, '2025-04-05'),
(11, 1, '2025-05-15'),
(12, 1, '2025-06-01'),
(13, 2, '2025-07-10'),
(14, 1, '2025-08-01'),
(15, 3, '2025-09-05'),
(16, 1, '2025-10-15');

-- ============================================================
-- SERVICES (offered by providers)
-- ============================================================
INSERT INTO `services` (`provider_id`, `category_id`, `service_name`, `description`, `base_price`, `status`, `created_at`) VALUES
(1, 1, 'Emergency Pipe Repair', 'Fast response pipe repair for leaks and burst pipes', 150.00, 1, '2025-01-15 09:00:00'),
(1, 1, 'Water Heater Installation', 'Full water heater installation and replacement service', 500.00, 1, '2025-01-20 10:00:00'),
(1, 1, 'Drain Cleaning', 'Professional drain cleaning and unclogging', 120.00, 1, '2025-02-01 08:30:00'),
(2, 2, 'Electrical Panel Upgrade', 'Upgrade your old electrical panel to modern standards', 800.00, 1, '2025-02-10 09:00:00'),
(2, 2, 'Lighting Installation', 'Indoor and outdoor lighting installation', 200.00, 1, '2025-02-15 10:00:00'),
(2, 2, 'Outlet & Switch Repair', 'Repair or replace faulty outlets and switches', 100.00, 1, '2025-03-01 11:00:00'),
(3, 3, 'Interior House Painting', 'Complete interior painting with premium paints', 1200.00, 1, '2025-03-15 09:00:00'),
(3, 3, 'Exterior House Painting', 'Weather-resistant exterior painting service', 2500.00, 1, '2025-03-20 10:00:00'),
(3, 3, 'Cabinet Refinishing', 'Kitchen and bathroom cabinet refinishing', 600.00, 2, '2025-04-01 08:00:00'),
(4, 4, 'Custom Shelving', 'Design and build custom shelving units', 400.00, 1, '2025-04-10 09:00:00'),
(4, 4, 'Deck Construction', 'Custom deck building and design', 3500.00, 1, '2025-04-15 10:00:00'),
(5, 5, 'Roof Inspection', 'Comprehensive roof inspection and damage report', 250.00, 1, '2025-05-25 09:00:00'),
(5, 5, 'Roof Repair', 'Shingle replacement and leak repair', 700.00, 1, '2025-06-01 10:00:00'),
(5, 5, 'Gutter Installation', 'New gutter system installation', 450.00, 1, '2025-06-10 08:00:00'),
(1, 6, 'HVAC Maintenance', 'Annual HVAC system maintenance and tune-up', 180.00, 1, '2025-06-20 09:00:00');

-- ============================================================
-- BOOKINGS
-- ============================================================
INSERT INTO `bookings` (`client_id`, `provider_id`, `service_id`, `appointment_date`, `total_price`, `status`, `created_at`) VALUES
(7, 1, 1, '2025-02-10 10:00:00', 150.00, 3, '2025-02-01'),
(8, 1, 2, '2025-03-05 14:00:00', 500.00, 3, '2025-02-25'),
(9, 2, 4, '2025-04-12 09:00:00', 800.00, 3, '2025-04-01'),
(10, 2, 5, '2025-05-08 11:00:00', 200.00, 2, '2025-04-28'),
(11, 3, 7, '2025-06-15 08:00:00', 1200.00, 3, '2025-06-01'),
(12, 3, 8, '2025-07-20 09:00:00', 2500.00, 1, '2025-07-10'),
(7, 4, 10, '2025-08-05 10:00:00', 400.00, 2, '2025-07-25'),
(8, 4, 11, '2025-09-10 13:00:00', 3500.00, 1, '2025-08-30'),
(14, 5, 12, '2025-10-01 09:00:00', 250.00, 3, '2025-09-20'),
(16, 5, 13, '2025-10-15 14:00:00', 700.00, 2, '2025-10-05'),
(9, 1, 3, '2025-11-02 10:00:00', 120.00, 4, '2025-10-20'),
(10, 1, 15, '2025-11-20 08:00:00', 180.00, 1, '2025-11-10'),
(11, 2, 6, '2025-12-05 11:00:00', 100.00, 1, '2025-11-25'),
(12, 5, 14, '2025-12-18 09:00:00', 450.00, 2, '2025-12-08'),
(16, 3, 7, '2026-01-10 10:00:00', 1200.00, 1, '2025-12-28'),
(7, 1, 1, '2026-01-25 14:00:00', 150.00, 3, '2026-01-15'),
(14, 2, 4, '2026-02-05 09:00:00', 800.00, 1, '2026-01-25'),
(8, 5, 12, '2026-02-20 11:00:00', 250.00, 1, '2026-02-10');

-- ============================================================
-- JOB REQUESTS (spread across all statuses and months)
-- ============================================================
INSERT INTO `job_requests` (`client_id`, `contact_source`, `homeowner_name`, `address`, `phone1`, `phone2`, `email`, `work_description`, `estimator_notes`, `crew_instructions`, `status`, `created_at`) VALUES
-- Hot Leads
(1, 'Website', 'Emily Davis', '100 Maple Dr, Salt Lake City, UT', '801-555-0201', NULL, 'emily.davis@email.com', 'Kitchen faucet leaking badly, need urgent repair', NULL, NULL, 'Hot Lead', '2025-01-20 08:30:00'),
(2, 'Phone Call', 'Michael Brown', '200 Cedar St, Draper, UT', '801-555-0202', NULL, 'michael.brown@email.com', 'Entire house needs repainting - interior walls peeling', NULL, NULL, 'Hot Lead', '2025-03-15 10:00:00'),
(3, 'Referral', 'Jessica Taylor', '300 Willow Ave, Murray, UT', '801-555-0203', '801-555-0299', 'jessica.taylor@email.com', 'New deck construction in backyard, approximately 400 sq ft', NULL, NULL, 'Hot Lead', '2025-10-01 14:30:00'),
(NULL, 'Website', 'Tom Henderson', '45 River Rd, Park City, UT', '801-555-0301', NULL, 'tom.henderson@email.com', 'Bathroom remodel - new fixtures and tiling needed', NULL, NULL, 'Hot Lead', '2026-01-08 09:15:00'),
(NULL, 'Social Media', 'Rachel Kim', '78 Summit Ave, Sandy, UT', '801-555-0302', NULL, 'rachel.kim@email.com', 'Replace old garage door with modern insulated door', NULL, NULL, 'Hot Lead', '2026-02-01 11:00:00'),

-- Appointment for Estimate
(4, 'Website', 'Daniel Anderson', '400 Spruce Ct, Taylorsville, UT', '801-555-0204', NULL, 'daniel.anderson@email.com', 'Roof showing signs of wear, need inspection and estimate', 'Schedule for next Tuesday', NULL, 'Appointment for Estimate', '2025-02-10 09:00:00'),
(5, 'Phone Call', 'Ashley Thomas', '500 Aspen Way, West Jordan, UT', '801-555-0205', NULL, 'ashley.thomas@email.com', 'Want to add recessed lighting throughout living room', 'Client prefers afternoon appointments', NULL, 'Appointment for Estimate', '2025-06-20 13:00:00'),
(NULL, 'Referral', 'George Patel', '120 Hillside Dr, Bountiful, UT', '801-555-0303', NULL, 'george.patel@email.com', 'Full kitchen renovation including cabinets and countertops', 'Large scope project, bring measuring tools', NULL, 'Appointment for Estimate', '2025-11-15 10:30:00'),
(NULL, 'Website', 'Linda Foster', '250 Oak Park Blvd, Orem, UT', '801-555-0304', NULL, 'linda.foster@email.com', 'Fence installation around property perimeter', NULL, NULL, 'Appointment for Estimate', '2026-01-20 08:45:00'),

-- Estimate Needed
(6, 'Website', 'Christopher Lee', '600 Poplar Rd, South Jordan, UT', '801-555-0206', NULL, 'chris.lee@email.com', 'HVAC system making strange noises, may need full replacement', 'Check compressor unit outside', NULL, 'Estimate Needed', '2025-04-05 11:00:00'),
(NULL, 'Phone Call', 'Nancy Wright', '330 Sunset Blvd, Layton, UT', '801-555-0305', '801-555-0399', 'nancy.wright@email.com', 'Water damage in basement, need waterproofing solution', 'Bring moisture meter', NULL, 'Estimate Needed', '2025-09-10 09:30:00'),
(NULL, 'Website', 'Kevin Brooks', '440 Canyon Rd, American Fork, UT', '801-555-0306', NULL, 'kevin.brooks@email.com', 'Solar panel installation on south-facing roof', NULL, NULL, 'Estimate Needed', '2026-02-05 14:00:00'),

-- Estimate in Progress
(7, 'Referral', 'Amanda White', '700 Walnut Blvd, Riverton, UT', '801-555-0207', NULL, 'amanda.white@email.com', 'Complete bathroom renovation - master bath', 'Plumbing may need rerouting, check access', 'Demo existing fixtures first', 'Estimate in Progress', '2025-05-12 10:00:00'),
(NULL, 'Website', 'Steve Morrison', '560 Lakeview Ter, Saratoga Springs, UT', '801-555-0307', NULL, 'steve.morrison@email.com', 'Add second story to existing single-story home', 'Structural engineer consultation needed', NULL, 'Estimate in Progress', '2025-12-01 11:15:00'),

-- Estimate Follow Up
(8, 'Phone Call', 'Matthew Harris', '800 Hickory Pl, Herriman, UT', '801-555-0208', NULL, 'matthew.harris@email.com', 'Hardwood floor installation in 3 bedrooms', 'Client choosing between oak and maple', NULL, 'Estimate Follow Up', '2025-06-01 14:00:00'),
(NULL, 'Referral', 'Diane Cooper', '670 Meadow Ln, Eagle Mountain, UT', '801-555-0308', NULL, 'diane.cooper@email.com', 'Landscaping redesign for front yard', 'Waiting for client to pick plant selections', NULL, 'Estimate Follow Up', '2025-11-20 09:00:00'),

-- Assigned to Vendor
(NULL, 'Website', 'Paul Rodriguez', '890 Timber Creek, Pleasant Grove, UT', '801-555-0309', NULL, 'paul.rodriguez@email.com', 'Install new windows throughout house - 12 windows total', 'Assigned to Wilson Plumbing - they also do windows', 'Use double-pane low-E glass', 'Assigned to Vendor', '2025-07-15 08:00:00'),
(10, 'Phone Call', 'Andrew Lewis', '1000 Sycamore St, Cottonwood, UT', '801-555-0210', NULL, 'andrew.lewis@email.com', 'Electrical wiring upgrade for entire home', 'Assigned to Garcia Electric', 'Panel upgrade required first', 'Assigned to Vendor', '2025-12-10 10:00:00'),

-- Estimate Approved
(NULL, 'Referral', 'Sandra Mitchell', '150 Orchard Way, Kaysville, UT', '801-555-0310', NULL, 'sandra.mitchell@email.com', 'Kitchen backsplash tile installation', 'Approved at $1,800', 'Use subway tile pattern', 'Estimate Approved', '2025-08-01 09:00:00'),
(NULL, 'Website', 'Mark Thompson', '280 Valley View Dr, Centerville, UT', '801-555-0311', NULL, 'mark.thompson@email.com', 'Driveway repaving - asphalt to concrete', 'Approved at $4,200', 'Schedule concrete truck for delivery', 'Estimate Approved', '2026-01-05 13:30:00'),

-- Project in Progress
(1, 'Website', 'Emily Davis', '100 Maple Dr, Salt Lake City, UT', '801-555-0201', NULL, 'emily.davis@email.com', 'Master bedroom renovation with new closet system', 'Budget: $5,500', 'Demo started, framing next week', 'Project in Progress', '2025-08-20 08:00:00'),
(2, 'Phone Call', 'Michael Brown', '200 Cedar St, Draper, UT', '801-555-0202', NULL, 'michael.brown@email.com', 'Basement finishing - 800 sq ft', 'Budget: $15,000', 'Electrical and plumbing roughed in', 'Project in Progress', '2025-09-15 10:00:00'),
(NULL, 'Referral', 'Carlos Ramirez', '390 Mountain View Rd, Bluffdale, UT', '801-555-0312', NULL, 'carlos.ramirez@email.com', 'Complete roof replacement - 2,400 sq ft', 'Budget: $8,500', 'Old shingles removed, underlayment going on', 'Project in Progress', '2025-11-05 07:30:00'),
(NULL, 'Website', 'Julia Nguyen', '510 Spring Creek, Vineyard, UT', '801-555-0313', NULL, 'julia.nguyen@email.com', 'New patio with pergola construction', 'Budget: $6,200', 'Foundation poured, waiting for lumber delivery', 'Project in Progress', '2026-01-15 09:00:00'),

-- Project Completed
(3, 'Referral', 'Jessica Taylor', '300 Willow Ave, Murray, UT', '801-555-0203', NULL, 'jessica.taylor@email.com', 'Garage door replacement and opener installation', NULL, 'All work completed, client satisfied', 'Project Completed', '2025-04-20 15:00:00'),
(NULL, 'Website', 'Brian Foster', '720 Sunset Ridge, Alpine, UT', '801-555-0314', NULL, 'brian.foster@email.com', 'Fence installation - cedar wood, 200 linear ft', NULL, 'Completed ahead of schedule', 'Project Completed', '2025-07-30 16:00:00'),
(NULL, 'Phone Call', 'Helen Chang', '840 Crestwood Dr, Highland, UT', '801-555-0315', NULL, 'helen.chang@email.com', 'Bathroom tile and fixture replacement', NULL, 'Final inspection passed', 'Project Completed', '2025-10-25 14:00:00'),

-- Project Invoiced
(4, 'Website', 'Daniel Anderson', '400 Spruce Ct, Taylorsville, UT', '801-555-0204', NULL, 'daniel.anderson@email.com', 'Kitchen countertop replacement - granite', NULL, 'Invoice #1042 sent - $3,200', 'Project Invoiced', '2025-05-30 11:00:00'),
(NULL, 'Phone Call', 'Robert Evans', '960 Pinecrest Ave, Holladay, UT', '801-555-0316', NULL, 'robert.evans@email.com', 'Exterior siding replacement - vinyl', NULL, 'Invoice #1089 sent - $7,800', 'Project Invoiced', '2025-09-28 10:00:00'),
(NULL, 'Referral', 'Patricia Young', '110 Brookside Ct, Farmington, UT', '801-555-0317', NULL, 'patricia.young@email.com', 'Hardwood floor refinishing - 1,200 sq ft', NULL, 'Invoice #1105 sent - $2,400', 'Project Invoiced', '2026-01-28 09:00:00'),

-- Project Done
(5, 'Phone Call', 'Ashley Thomas', '500 Aspen Way, West Jordan, UT', '801-555-0205', NULL, 'ashley.thomas@email.com', 'Attic insulation upgrade - blown-in fiberglass', NULL, 'Payment received, project closed', 'Project Done', '2025-03-25 12:00:00'),
(6, 'Website', 'Christopher Lee', '600 Poplar Rd, South Jordan, UT', '801-555-0206', NULL, 'chris.lee@email.com', 'Plumbing repiping - copper to PEX', NULL, 'Payment received, warranty issued', 'Project Done', '2025-06-15 16:00:00'),
(NULL, 'Referral', 'Angela Scott', '230 Meadowbrook Ln, Clearfield, UT', '801-555-0318', NULL, 'angela.scott@email.com', 'Electrical panel upgrade 100A to 200A', NULL, 'Final payment received, permit closed', 'Project Done', '2025-08-10 13:00:00'),
(NULL, 'Website', 'Frank Miller', '350 Creekside Dr, Tooele, UT', '801-555-0319', NULL, 'frank.miller@email.com', 'Landscape irrigation system installation', NULL, 'Paid in full, system operational', 'Project Done', '2025-10-05 15:00:00'),
(NULL, 'Phone Call', 'Lisa Parker', '470 Canyon Vista, Heber City, UT', '801-555-0320', NULL, 'lisa.parker@email.com', 'Interior painting - 5 rooms, accent walls', NULL, 'All done, client left 5-star review', 'Project Done', '2025-12-20 11:00:00');

-- ============================================================
-- MESSAGES (between clients and providers)
-- ============================================================
INSERT INTO `messages` (`provider_id`, `client_id`, `sender_type`, `content`, `status`, `created_at`) VALUES
(1, 1, 'client', 'Hi, I need an urgent pipe repair at my kitchen. When can you come?', 'read', '2025-02-01 08:00:00'),
(1, 1, 'provider', 'Hello Emily! I can come tomorrow morning at 10 AM. Does that work?', 'read', '2025-02-01 08:30:00'),
(1, 1, 'client', 'Perfect, see you then. Thank you!', 'read', '2025-02-01 08:45:00'),
(2, 3, 'client', 'I am interested in upgrading my electrical panel. What is the process?', 'read', '2025-03-28 10:00:00'),
(2, 3, 'provider', 'Hi Jessica, we start with an inspection to assess your current panel. I will schedule a visit.', 'read', '2025-03-28 12:00:00'),
(3, 5, 'client', 'Can you provide a quote for painting 3 bedrooms?', 'read', '2025-05-28 09:00:00'),
(3, 5, 'provider', 'Sure! For 3 standard bedrooms, our estimate is around $1,200. Want me to come take a look?', 'read', '2025-05-28 11:00:00'),
(3, 5, 'client', 'Yes please, I am available this weekend.', 'read', '2025-05-28 14:00:00'),
(4, 1, 'client', 'I would like to get custom shelving built in my home office.', 'read', '2025-07-20 10:00:00'),
(4, 1, 'provider', 'I would love to help! Can you share the dimensions of the wall?', 'read', '2025-07-20 14:00:00'),
(5, 8, 'client', 'Our roof has some missing shingles after the storm. Need inspection.', 'read', '2025-09-18 08:00:00'),
(5, 8, 'provider', 'Sorry to hear that. I can inspect this Friday. I will also check for any water damage.', 'read', '2025-09-18 10:00:00'),
(1, 2, 'client', 'Do you install tankless water heaters?', 'unread', '2026-01-10 09:00:00'),
(2, 4, 'client', 'I have flickering lights in my kitchen, is this dangerous?', 'unread', '2026-01-25 15:00:00'),
(2, 4, 'provider', 'It could indicate a wiring issue. I recommend getting it checked ASAP. I can come Monday.', 'unread', '2026-01-25 16:30:00'),
(5, 10, 'client', 'We need gutter guards installed. Do you offer that service?', 'unread', '2026-02-05 10:00:00'),
(1, 6, 'provider', 'Hi Chris, just following up on your HVAC maintenance appointment next week.', 'unread', '2026-02-10 08:00:00'),
(3, 10, 'client', 'Looking for cabinet refinishing quote. Can you come for an estimate?', 'unread', '2026-02-15 11:00:00');

-- ============================================================
-- ADMIN HISTORY (audit log entries)
-- ============================================================
INSERT INTO `admin_history` (`admin_id`, `action`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 'Admin Login', 'Admin logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', '2025-01-10 08:00:00'),
(1, 'Added New Provider', 'Provider: James Wilson, Business: Wilson Plumbing & Heating, Email: james.wilson@email.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', '2025-01-10 08:15:00'),
(1, 'Added New Provider', 'Provider: Maria Garcia, Business: Garcia Electric Solutions, Email: maria.garcia@email.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', '2025-02-05 09:00:00'),
(1, 'Added New Provider', 'Provider: Robert Chen, Business: Chen Painting Pros, Email: robert.chen@email.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', '2025-03-12 10:00:00'),
(1, 'Updated Admin User Status', 'Admin User ID: 5 set to Inactive', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', '2025-04-02 14:00:00'),
(1, 'Activated Service', 'Service ID: 7', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', '2025-04-15 11:00:00'),
(1, 'Deactivated Service', 'Service ID: 9', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', '2025-05-01 09:30:00'),
(1, 'Added New Provider', 'Provider: David Martinez, Business: Martinez Roofing Co., Email: david.martinez@email.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', '2025-05-20 10:30:00'),
(1, 'Admin Login', 'Admin logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', '2025-06-10 08:00:00'),
(1, 'Updated Admin User Status', 'Admin User ID: 13 set to Inactive', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', '2025-07-12 15:00:00'),
(1, 'Updated Admin User Status', 'Admin User ID: 15 set to Banned', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', '2025-09-06 10:00:00'),
(1, 'Admin Login', 'Admin logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', '2025-10-01 08:30:00'),
(1, 'Activated Service', 'Service ID: 12', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', '2025-10-05 09:00:00'),
(1, 'Added New Provider', 'Provider: Sarah Johnson, Business: Johnson Carpentry Works, Email: sarah.johnson@email.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', '2025-11-01 11:00:00'),
(1, 'Admin Login', 'Admin logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', '2026-01-05 08:00:00'),
(1, 'Activated Service', 'Service ID: 15', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', '2026-01-10 09:15:00'),
(1, 'Deactivated Service', 'Service ID: 3', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', '2026-01-20 14:00:00'),
(1, 'Admin Login', 'Admin logged in successfully', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', '2026-02-01 08:00:00'),
(1, 'Updated Admin User Status', 'Admin User ID: 4 set to Active', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', '2026-02-10 10:00:00'),
(1, 'Activated Service', 'Service ID: 9', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', '2026-02-15 11:30:00');

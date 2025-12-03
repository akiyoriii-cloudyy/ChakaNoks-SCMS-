-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 03, 2025 at 03:21 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chakanoks_scms`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts_payable`
--

CREATE TABLE `accounts_payable` (
  `id` int(10) UNSIGNED NOT NULL,
  `purchase_order_id` int(10) UNSIGNED NOT NULL,
  `invoice_number` varchar(100) DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `amount_paid` decimal(10,2) DEFAULT 0.00,
  `payment_status` enum('unpaid','partial','paid','overdue') NOT NULL DEFAULT 'unpaid',
  `notes` text DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `balance` decimal(10,2) GENERATED ALWAYS AS (`amount` - coalesce(`amount_paid`,0)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Accounts payable records for approved purchase orders';

--
-- Dumping data for table `accounts_payable`
--

INSERT INTO `accounts_payable` (`id`, `purchase_order_id`, `invoice_number`, `invoice_date`, `due_date`, `amount`, `amount_paid`, `payment_status`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(28, 33, 'INV-20251129-59705', '2025-11-29', '2025-12-29', 18000.00, 0.00, 'unpaid', 'Auto-created from approved purchase order: PO-20251129-5677', 2, '2025-11-29 02:57:28', '2025-11-29 02:57:59'),
(29, 35, 'INV-20251129-13002', '2025-11-29', '2025-12-29', 9000.00, 0.00, 'unpaid', 'Auto-created from approved purchase order: PO-20251129-7054', 2, '2025-11-29 03:05:39', '2025-11-29 03:06:09'),
(30, 34, 'INV-20251129-94056', '2025-11-29', '2025-12-14', 24000.00, 0.00, 'unpaid', 'Auto-created from approved purchase order: PO-20251129-6834', 2, '2025-11-29 03:05:41', '2025-11-29 03:05:52'),
(31, 36, 'INV-20251201-33201', '2025-12-01', '2025-12-31', 17500.00, 0.00, 'unpaid', 'Auto-created from approved purchase order: PO-20251201-5995', 2, '2025-12-01 13:40:35', '2025-12-01 13:41:23');

-- --------------------------------------------------------

--
-- Table structure for table `audit_trail`
--

CREATE TABLE `audit_trail` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `record_id` int(10) UNSIGNED NOT NULL,
  `action` enum('INSERT','UPDATE','DELETE') NOT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `changed_fields` text DEFAULT NULL COMMENT 'Comma-separated list of changed fields',
  `changed_by` int(10) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Comprehensive audit trail for all database changes';

-- --------------------------------------------------------

--
-- Table structure for table `auth_logs`
--

CREATE TABLE `auth_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `event_type` enum('login_success','login_failed','logout','password_reset_request','password_reset_success','password_reset_failed','otp_sent','otp_verified','otp_failed','account_locked','account_unlocked') NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `details` text DEFAULT NULL COMMENT 'Additional event details',
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Authentication and security event audit log';

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` text DEFAULT NULL,
  `franchise_owner_id` int(10) UNSIGNED DEFAULT NULL,
  `franchise_type` enum('company_owned','franchised') DEFAULT 'company_owned',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Company branches';

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `code`, `name`, `address`, `franchise_owner_id`, `franchise_type`, `created_at`, `updated_at`) VALUES
(1, 'CENTRAL', 'Central Office', 'Quimpo Boulevard, Ecoland, Davao City, Philippines', NULL, 'company_owned', '2025-11-22 21:55:32', '2025-11-22 21:55:32'),
(2, 'MATINA', 'Matina Branch', 'MacArthur Highway, Matina Crossing, Davao City, Philippines', NULL, 'company_owned', '2025-11-22 21:55:32', '2025-11-22 21:55:32'),
(3, 'TORIL', 'Toril Branch', 'Agton Street, Toril, Davao City, Philippines', NULL, 'company_owned', '2025-11-22 21:55:32', '2025-11-22 21:55:32'),
(4, 'BUHANGIN', 'Buhangin Branch', 'Buhangin Road, Buhangin Proper, Davao City, Philippines', NULL, 'company_owned', '2025-11-22 21:55:32', '2025-11-22 21:55:32'),
(5, 'AGDAO', 'Agdao Branch', 'Lapu-Lapu Street, Agdao, Davao City, Philippines', NULL, 'company_owned', '2025-11-22 21:55:32', '2025-11-22 21:55:32'),
(6, 'LANANG', 'Lanang Branch', 'JP Laurel Avenue, Lanang, Davao City, Philippines', NULL, 'company_owned', '2025-11-22 21:55:32', '2025-11-22 21:55:32'),
(7, 'CEBU01', 'SM City Cebu Branch', 'Juan Luna Avenue, North Reclamation Area, Cebu City, 6000', 1, 'franchised', '2025-11-03 09:53:13', '2025-12-03 10:11:18'),
(8, 'MANILA01', 'SM Megamall Branch', 'EDSA corner Doña Julia Vargas Avenue, Ortigas Center, Mandaluyong City, Metro Manila', 2, 'franchised', '2025-11-08 09:53:13', '2025-12-03 10:11:18'),
(9, 'ILOILO01', 'Robinsons Place Iloilo Branch', 'Quezon Street corner Ledesma Street, Iloilo City Proper, Iloilo City, 5000', 3, 'franchised', '2025-11-13 09:53:13', '2025-12-03 10:11:18'),
(10, 'CDO01', 'Centrio Mall Branch', 'Claro M. Recto Avenue, Carmen, Cagayan de Oro City, 9000', 4, 'franchised', '2025-11-18 09:53:13', '2025-12-03 10:11:18'),
(11, 'BAGUIO01', 'Session Road Branch', 'Session Road, Baguio City, Benguet, 2600', 5, 'franchised', '2025-11-23 09:53:13', '2025-12-03 10:11:18');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Product categories';

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_by`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Chicken Parts', 'Chicken parts and products including whole chicken, breast, thigh, wings, drumstick, liver, gizzard, and other parts', 12, 'active', '2025-11-25 15:45:57', '2025-11-27 08:10:55'),
(3, 'Beverages', 'Soft drinks, juices, water, coffee, tea, and other drinks for customers and staff', 12, 'active', '2025-11-27 14:54:23', '2025-11-29 02:21:08'),
(4, 'Condiments & Sauces', 'Ketchup, mayonnaise, gravy, hot sauce, soy sauce, BBQ sauce, and other dipping sauces', 12, 'active', '2025-11-27 14:54:23', '2025-11-29 02:21:08'),
(5, 'Cooking Oils', 'Vegetable oil, palm oil, coconut oil, shortening, and other cooking fats for frying chicken', 12, 'active', '2025-11-27 14:54:23', '2025-11-29 02:21:08'),
(6, 'Seasonings & Spices', 'Salt, pepper, garlic powder, paprika, chicken seasoning, MSG, and other flavor enhancers', 12, 'active', '2025-11-27 14:54:23', '2025-11-29 02:21:08'),
(7, 'Rice & Grains', 'White rice, brown rice, java rice, garlic rice, and other grain products', 12, 'active', '2025-11-27 14:54:23', '2025-11-29 02:21:08'),
(8, 'Vegetables & Produce', 'Fresh vegetables including cabbage, carrots, onions, garlic, potatoes, lettuce, and coleslaw ingredients', 12, 'active', '2025-11-27 14:54:23', '2025-11-29 02:21:08'),
(9, 'Bread & Bakery', 'Burger buns, sandwich bread, pandesal, tortilla wraps, and other bread products', 12, 'active', '2025-11-27 14:54:23', '2025-11-29 02:21:08'),
(10, 'Dairy Products', 'Butter, cheese, milk, cream, and other dairy items for cooking and beverages', 12, 'active', '2025-11-27 14:54:23', '2025-11-29 02:21:08'),
(11, 'Frozen Goods', 'Frozen fries, frozen vegetables, ice cream, and other frozen products', 12, 'active', '2025-11-27 14:54:23', '2025-11-29 02:21:08'),
(12, 'Packaging & Supplies', 'Takeout boxes, paper bags, plastic containers, utensils, cups, straws, and other packaging materials', 12, 'active', '2025-11-27 14:54:23', '2025-11-29 02:21:08'),
(13, 'Cleaning & Sanitation', 'Dish soap, sanitizers, cleaning supplies, garbage bags, gloves, and sanitation products', 12, 'active', '2025-11-27 14:54:23', '2025-11-29 02:21:08'),
(14, 'Eggs', 'Fresh eggs in trays and dozens for cooking, breading, and breakfast items', 12, 'active', '2025-11-27 14:54:23', '2025-11-29 02:21:08'),
(15, 'Flour & Breading', 'All-purpose flour, breading mix, cornstarch, batter mix, and panko for chicken coating', 12, 'active', '2025-11-27 14:54:23', '2025-11-29 02:21:08'),
(16, 'Marinades & Brines', 'Chicken marinades, BBQ marinades, buttermilk brine, and flavor enhancers for chicken preparation', 12, 'active', '2025-11-27 08:01:29', '2025-11-29 02:21:08'),
(17, 'Side Dishes', 'Pre-made coleslaw, mashed potato mix, gravy, mac and cheese, and other ready-to-serve sides', 12, 'active', '2025-11-27 08:01:29', '2025-11-29 02:21:08'),
(18, 'Desserts & Sweets', 'Chocolate syrup, caramel, sundae toppings, brownie mix, and other dessert items', 12, 'active', '2025-11-27 08:01:29', '2025-11-29 02:21:08'),
(19, 'Paper Products', 'Napkins, paper towels, tissue paper, parchment paper, and other paper supplies', 12, 'active', '2025-11-27 08:01:29', '2025-11-29 02:21:08'),
(20, 'Kitchen Equipment', 'Tongs, spatulas, thermometers, cutting boards, knives, and other kitchen tools', 12, 'active', '2025-11-27 08:01:29', '2025-11-29 02:21:08'),
(21, 'Uniforms & Apparel', 'Chef hats, hair nets, aprons, kitchen gloves, face masks, and staff uniforms', 12, 'active', '2025-11-27 08:01:29', '2025-11-29 02:21:08');

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `id` int(10) UNSIGNED NOT NULL,
  `delivery_number` varchar(50) NOT NULL,
  `purchase_order_id` int(10) UNSIGNED NOT NULL,
  `supplier_id` int(10) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED NOT NULL,
  `scheduled_by` int(10) UNSIGNED DEFAULT NULL,
  `status` enum('scheduled','in_transit','delivered','partial_delivery','cancelled','delayed') NOT NULL DEFAULT 'scheduled',
  `scheduled_date` date NOT NULL,
  `actual_delivery_date` date DEFAULT NULL,
  `driver_name` varchar(100) DEFAULT NULL,
  `vehicle_info` varchar(100) DEFAULT NULL,
  `received_by` int(10) UNSIGNED DEFAULT NULL,
  `received_at` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Delivery records and tracking';

--
-- Dumping data for table `deliveries`
--

INSERT INTO `deliveries` (`id`, `delivery_number`, `purchase_order_id`, `supplier_id`, `branch_id`, `scheduled_by`, `status`, `scheduled_date`, `actual_delivery_date`, `driver_name`, `vehicle_info`, `received_by`, `received_at`, `notes`, `created_at`, `updated_at`) VALUES
(30, 'DEL-20251129-3229', 33, 42, 4, 2, 'scheduled', '2025-12-06', NULL, NULL, NULL, NULL, NULL, 'Auto-created from Purchase Order (seeder)', '2025-11-29 03:02:50', '2025-11-29 03:02:50'),
(31, 'DEL-20251129-4785', 34, 11, 5, NULL, 'scheduled', '2025-12-06', NULL, NULL, NULL, NULL, NULL, 'Auto-created from Purchase Order (seeder)', '2025-11-29 03:02:50', '2025-11-29 03:02:50'),
(32, 'DEL-20251129-7561', 35, 63, 6, 2, 'in_transit', '2025-11-30', NULL, 'BossKakak', 'L300 AB3123', NULL, NULL, 'Auto-created from Purchase Order #35', '2025-11-29 03:04:18', '2025-11-29 03:06:49'),
(33, 'DEL-20251201-8294', 36, 1, 2, 2, 'delivered', '2025-12-01', '2025-12-01', 'Marky Hatdog', 'L300 AB8930', 4, '2025-12-01 13:41:00', 'Auto-created from Purchase Order #36', '2025-12-01 13:40:27', '2025-12-01 13:41:58');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_items`
--

CREATE TABLE `delivery_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `delivery_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `expected_quantity` int(10) UNSIGNED NOT NULL,
  `received_quantity` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `condition_status` enum('good','damaged','expired','partial') NOT NULL DEFAULT 'good',
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Items in deliveries';

--
-- Dumping data for table `delivery_items`
--

INSERT INTO `delivery_items` (`id`, `delivery_id`, `product_id`, `expected_quantity`, `received_quantity`, `condition_status`, `notes`, `created_at`, `updated_at`) VALUES
(30, 30, 298, 150, 0, 'good', NULL, '2025-11-29 03:02:50', '2025-11-29 03:02:50'),
(31, 31, 113, 200, 0, 'good', NULL, '2025-11-29 03:02:50', '2025-11-29 03:02:50'),
(32, 32, 222, 50, 0, 'good', NULL, '2025-11-29 03:04:18', '2025-11-29 03:04:18'),
(33, 33, 285, 50, 50, 'good', NULL, '2025-12-01 13:40:27', '2025-12-01 13:41:58');

-- --------------------------------------------------------

--
-- Table structure for table `franchise_applications`
--

CREATE TABLE `franchise_applications` (
  `id` int(11) UNSIGNED NOT NULL,
  `applicant_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `proposed_location` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `investment_capital` decimal(15,2) NOT NULL DEFAULT 0.00,
  `business_experience` text DEFAULT NULL,
  `status` enum('pending','under_review','approved','rejected','on_hold') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `reviewed_by` int(11) UNSIGNED DEFAULT NULL,
  `reviewed_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `franchise_applications`
--

INSERT INTO `franchise_applications` (`id`, `applicant_name`, `email`, `phone`, `proposed_location`, `city`, `investment_capital`, `business_experience`, `status`, `notes`, `reviewed_by`, `reviewed_at`, `created_at`, `updated_at`) VALUES
(1, 'Maria Clara Santos', 'maria.santos@cebu-business.com', '+63 917 234 5678', 'SM City Cebu, North Reclamation Area', 'Cebu City', 8500000.00, 'MBA graduate with 5 years experience in retail management. Currently operates 2 successful convenience stores in Cebu.', 'approved', 'Excellent qualifications. Strong local market knowledge. Approved for SM City Cebu location.', 9, '2025-11-05 09:53:46', '2025-11-01 09:53:46', '2025-11-05 09:53:46'),
(2, 'Juan Carlos Reyes', 'jc.reyes@email.com', '+63 918 345 6789', 'Ayala Center Cebu', 'Cebu City', 6000000.00, 'First-time franchisee but has business degree and family retail background.', 'under_review', 'Under evaluation. Scheduled for site visit next week.', 9, '2025-11-28 09:53:46', '2025-11-21 09:53:46', '2025-11-28 09:53:46'),
(3, 'Roberto Antonio Tan', 'roberto.tan@manila-ventures.com', '+63 915 456 7890', 'SM Megamall, Ortigas Center', 'Mandaluyong City', 12000000.00, 'Owns 3 successful restaurants in Metro Manila. 10+ years in food and retail industry. Strong financial backing.', 'approved', 'Premium location approved. High-capacity franchisee with proven track record.', 9, '2025-11-10 09:53:46', '2025-11-06 09:53:46', '2025-11-10 09:53:46'),
(4, 'Patricia Anne Cruz', 'patricia.cruz@email.com', '+63 920 567 8901', 'Bonifacio Global City', 'Taguig City', 10000000.00, 'Corporate executive with MBA. Looking to start own business. No prior retail experience.', 'under_review', 'Strong financial capacity but lacks retail experience. Considering acceptance with training program.', 9, '2025-12-03 10:16:57', '2025-11-25 09:53:46', '2025-12-03 10:16:57'),
(5, 'Ana Marie Garcia', 'ana.garcia@iloilo-retail.com', '+63 919 678 9012', 'Robinsons Place Iloilo', 'Iloilo City', 5500000.00, 'Family owns established retail business in Iloilo. 7 years in business management.', 'approved', 'Local market expert. Family business background. Approved for Robinsons Place location.', 9, '2025-11-15 09:53:46', '2025-11-11 09:53:46', '2025-11-15 09:53:46'),
(6, 'Michael James Lopez', 'mj.lopez@email.com', '+63 921 789 0123', 'SM City Iloilo', 'Iloilo City', 4200000.00, 'Recent business graduate. Limited practical experience.', 'rejected', 'Insufficient experience and below minimum investment threshold for SM mall location.', 9, '2025-11-30 09:53:46', '2025-11-23 09:53:46', '2025-11-30 09:53:46'),
(7, 'Santiago Luis Ramos', 'santiago.ramos@cdo-enterprises.com', '+63 922 890 1234', 'Centrio Mall, Claro M. Recto Avenue', 'Cagayan de Oro City', 7000000.00, 'Successful businessman in Northern Mindanao. Owns hardware stores and construction supply business.', 'approved', 'Strong business acumen. Excellent financial standing. Approved for Centrio Mall location.', 9, '2025-11-20 09:53:46', '2025-11-16 09:53:46', '2025-11-20 09:53:46'),
(8, 'Catherine Rose Mendoza', 'catherine.mendoza@baguio-business.com', '+63 923 901 2345', 'Session Road, Baguio City', 'Baguio City', 6500000.00, '8 years operating a café and gift shop on Session Road. Strong tourist market knowledge.', 'approved', 'Prime tourist location. Experienced local operator. Approved for Session Road location.', 9, '2025-11-25 09:53:46', '2025-11-21 09:53:46', '2025-11-25 09:53:46'),
(9, 'David Emmanuel Torres', 'david.torres@email.com', '+63 924 012 3456', 'SM City Baguio', 'Baguio City', 5800000.00, '4 years in retail sales. Currently store manager at another retail chain.', 'under_review', 'Good retail experience but needs to clarify financing sources.', 9, '2025-12-03 09:56:23', '2025-11-27 09:53:46', '2025-12-03 09:56:23');

-- --------------------------------------------------------

--
-- Table structure for table `franchise_owners`
--

CREATE TABLE `franchise_owners` (
  `id` int(10) UNSIGNED NOT NULL,
  `owner_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `tax_id` varchar(50) DEFAULT NULL COMMENT 'TIN or business tax ID',
  `business_license` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
  `joined_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Franchise owners (normalized from branches)';

--
-- Dumping data for table `franchise_owners`
--

INSERT INTO `franchise_owners` (`id`, `owner_name`, `email`, `phone`, `address`, `tax_id`, `business_license`, `status`, `joined_date`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'Maria Clara Santos', 'maria.santos@cebu-business.com', '+63 917 234 5678', 'Unit 3B, Pacific Plaza Towers, Archbishop Reyes Avenue, Lahug, Cebu City, 6000', '123-456-789-000', 'CEBU-BL-2025-001234', 'active', '2025-11-03', 'MBA graduate with 5 years retail management experience. Operates 2 successful convenience stores in Cebu. Approved for SM City Cebu franchise.', '2025-11-03 10:11:18', '2025-11-03 10:11:18'),
(2, 'Roberto Antonio Tan', 'roberto.tan@manila-ventures.com', '+63 915 456 7890', '25th Floor, Discovery Center, ADB Avenue, Ortigas Center, Pasig City, Metro Manila', '234-567-890-000', 'MANILA-BL-2025-005678', 'active', '2025-11-08', 'Owns 3 successful restaurants in Metro Manila. 10+ years in food and retail industry. Strong financial backing. Premium franchisee for SM Megamall location.', '2025-11-08 10:11:18', '2025-11-08 10:11:18'),
(3, 'Ana Marie Garcia', 'ana.garcia@iloilo-retail.com', '+63 919 678 9012', 'Garcia Building, Quezon Street, Iloilo City Proper, Iloilo, 5000', '345-678-901-000', 'ILOILO-BL-2025-002345', 'active', '2025-11-13', 'Family owns established retail business in Iloilo. 7 years in business management. Local market expert with deep community connections.', '2025-11-13 10:11:18', '2025-11-13 10:11:18'),
(4, 'Santiago Luis Ramos', 'santiago.ramos@cdo-enterprises.com', '+63 922 890 1234', 'Ramos Commercial Complex, Corrales Avenue, Cagayan de Oro City, 9000', '456-789-012-000', 'CDO-BL-2025-003456', 'active', '2025-11-18', 'Successful businessman in Northern Mindanao. Owns hardware stores and construction supply business. Excellent financial standing and business acumen.', '2025-11-18 10:11:18', '2025-11-18 10:11:18'),
(5, 'Catherine Rose Mendoza', 'catherine.mendoza@baguio-business.com', '+63 923 901 2345', 'Mendoza Building, Upper Session Road, Baguio City, Benguet, 2600', '567-890-123-000', 'BAGUIO-BL-2025-004567', 'active', '2025-11-23', '8 years operating a café and gift shop on Session Road. Strong tourist market knowledge and established local presence.', '2025-11-23 10:11:18', '2025-11-23 10:11:18');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2025-08-29-054618', 'App\\Database\\Migrations\\CreateUsersTable', 'default', 'App', 1763819704, 1),
(2, '2025-08-29-054619', 'App\\Database\\Migrations\\CreateBranchesTable', 'default', 'App', 1763819705, 1),
(3, '2025-08-29-054620', 'App\\Database\\Migrations\\AlterUsersAddMoreRoles', 'default', 'App', 1763819705, 1),
(4, '2025-08-29-054621', 'App\\Database\\Migrations\\CreateProducts', 'default', 'App', 1763819705, 1),
(5, '2025-08-29-071346', 'App\\Database\\Migrations\\AddUsersBranchFK', 'default', 'App', 1763819705, 1),
(6, '2025-09-05-082236', 'App\\Database\\Migrations\\CreateReportsTable', 'default', 'App', 1763819705, 1),
(7, '2025-09-06-063840', 'App\\Database\\Migrations\\AddPasswordResetToUsers', 'default', 'App', 1763819705, 1),
(8, '2025-09-08-000001', 'App\\Database\\Migrations\\AddOtpColumnsToUsers', 'default', 'App', 1763819705, 1),
(9, '2025-09-10-100000', 'App\\Database\\Migrations\\CreateSuppliersTable', 'default', 'App', 1763819705, 1),
(10, '2025-09-10-100001', 'App\\Database\\Migrations\\CreatePurchaseRequestsTable', 'default', 'App', 1763819705, 1),
(11, '2025-09-10-100002', 'App\\Database\\Migrations\\CreatePurchaseRequestItemsTable', 'default', 'App', 1763819705, 1),
(12, '2025-09-10-100003', 'App\\Database\\Migrations\\CreatePurchaseOrdersTable', 'default', 'App', 1763819705, 1),
(13, '2025-09-10-100004', 'App\\Database\\Migrations\\CreatePurchaseOrderItemsTable', 'default', 'App', 1763819705, 1),
(14, '2025-09-10-100005', 'App\\Database\\Migrations\\CreateDeliveriesTable', 'default', 'App', 1763819705, 1),
(15, '2025-09-10-100006', 'App\\Database\\Migrations\\CreateDeliveryItemsTable', 'default', 'App', 1763819705, 1),
(16, '2025-09-10-100007', 'App\\Database\\Migrations\\CreateCategoriesTable', 'default', 'App', 1763819705, 1),
(17, '2025-09-10-100008', 'App\\Database\\Migrations\\CreateStockTransactionsTable', 'default', 'App', 1763819705, 1),
(18, '2025-09-10-100009', 'App\\Database\\Migrations\\AddCategoryIdToProducts', 'default', 'App', 1763819705, 1),
(19, '2025-11-21-000001', 'App\\Database\\Migrations\\AddBranchIdToProducts', 'default', 'App', 1763819705, 1),
(20, '2025-11-22-000001', 'App\\Database\\Migrations\\NormalizeProductsTable', 'default', 'App', 1763996526, 2),
(21, '2025-11-25-000001', 'App\\Database\\Migrations\\AddSelectedSupplierToPurchaseRequests', 'default', 'App', 1764001871, 3),
(22, '2025-11-25-000002', 'App\\Database\\Migrations\\CreateAccountsPayableTable', 'default', 'App', 1764003252, 4),
(23, '2025-11-29-000001', 'App\\Database\\Migrations\\AddScheduledByToDeliveries', 'default', 'App', 1764356547, 5),
(24, '2025-12-01-000001', 'App\\Database\\Migrations\\AddScheduledByToDeliveries', 'default', 'App', 1764603156, 6),
(25, '2025-12-01-000002', 'App\\Database\\Migrations\\CreateFranchiseTables', 'default', 'App', 1764603156, 6),
(26, '2025-12-03-000001', 'App\\Database\\Migrations\\CreateProductCatalogTable', 'default', 'App', 1764721838, 7),
(27, '2025-12-03-000002', 'App\\Database\\Migrations\\CreateStockBatchesTable', 'default', 'App', 1764721838, 7),
(28, '2025-12-03-000003', 'App\\Database\\Migrations\\CreateNormalizedStockTransactionsTable', 'default', 'App', 1764721838, 7),
(29, '2025-12-03-000004', 'App\\Database\\Migrations\\CreatePurchaseRequestQuotationsTable', 'default', 'App', 1764721838, 7),
(30, '2025-12-03-000005', 'App\\Database\\Migrations\\CreatePaymentTransactionsTable', 'default', 'App', 1764721838, 7),
(31, '2025-12-03-000006', 'App\\Database\\Migrations\\CreatePasswordResetTokensTable', 'default', 'App', 1764721838, 7),
(32, '2025-12-03-000007', 'App\\Database\\Migrations\\CreateAuthLogsTable', 'default', 'App', 1764721838, 7),
(33, '2025-12-03-000008', 'App\\Database\\Migrations\\CreateFranchiseOwnersTable', 'default', 'App', 1764721838, 7),
(34, '2025-12-03-000009', 'App\\Database\\Migrations\\CreateSupplierPerformanceTable', 'default', 'App', 1764721838, 7),
(35, '2025-12-03-000010', 'App\\Database\\Migrations\\CreateAuditTrailTable', 'default', 'App', 1764722058, 8),
(36, '2025-12-03-000011', 'App\\Database\\Migrations\\MigrateDataToNormalizedStructure', 'default', 'App', 1764722059, 8),
(37, '2025-12-03-000012', 'App\\Database\\Migrations\\NormalizeAccountsPayableTable', 'default', 'App', 1764724564, 9),
(38, '2025-12-03-100000', 'App\\Database\\Migrations\\AddForeignKeysToFranchiseTables', 'default', 'App', 1764726252, 10);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `token` varchar(255) NOT NULL,
  `otp` varchar(10) DEFAULT NULL,
  `otp_expires` datetime DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT 0,
  `used_at` datetime DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Password reset tokens (normalized from users table)';

-- --------------------------------------------------------

--
-- Table structure for table `payment_transactions`
--

CREATE TABLE `payment_transactions` (
  `id` int(10) UNSIGNED NOT NULL,
  `payment_number` varchar(50) NOT NULL,
  `accounts_payable_id` int(10) UNSIGNED NOT NULL,
  `payment_date` date NOT NULL,
  `payment_amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','check','bank_transfer','credit_card','online','other') NOT NULL,
  `payment_reference` varchar(100) DEFAULT NULL COMMENT 'Check number, transaction ID, etc.',
  `bank_name` varchar(100) DEFAULT NULL,
  `check_number` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `paid_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'User who made the payment',
  `recorded_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'User who recorded the payment',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Individual payment transactions for accounts payable';

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock_qty` int(11) NOT NULL DEFAULT 0,
  `unit` varchar(50) DEFAULT NULL,
  `min_stock` int(11) DEFAULT NULL,
  `max_stock` int(11) DEFAULT NULL,
  `expiry` date DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Products managed by inventory staff';

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `branch_id`, `created_by`, `category_id`, `name`, `price`, `stock_qty`, `unit`, `min_stock`, `max_stock`, `expiry`, `status`, `created_at`, `updated_at`) VALUES
(52, 2, 4, 2, 'Chicken Breast', 200.00, 200, 'kg', 200, 200, '2026-02-18', 'active', '2025-11-22 22:09:43', '2025-11-22 22:09:43'),
(53, 6, 12, 2, 'Chicken Drumstick', 200.00, 500, 'kg', 100, 900, '2026-02-23', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(54, 5, 7, 2, 'Chicken Neck', 90.00, 510, 'kg', 250, 250, '2026-02-17', 'active', '2025-11-22 22:12:24', '2025-11-27 03:16:16'),
(55, 4, 6, 2, 'Whole Chicken', 250.00, 300, 'kg', 300, 300, '2026-01-05', 'active', '2025-11-22 22:14:39', '2025-11-22 22:14:39'),
(56, 3, 5, 2, 'Chicken Thigh', 220.00, 490, 'kg', 450, 450, '2026-03-20', 'active', '2025-11-22 22:15:49', '2025-11-27 00:37:52'),
(57, 2, 12, 2, 'Chicken Drumstick', 200.00, 500, 'kg', 100, 900, '2026-02-25', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(58, 2, 4, 2, 'Chicken Feet', 90.00, 0, 'kg', 200, 200, '2026-01-26', 'active', '2025-11-25 00:00:02', '2025-11-29 11:53:33'),
(59, 2, 4, 2, 'Chicken Heart', 175.00, 300, 'kg', 300, 350, '2026-03-20', 'active', '2025-11-25 00:05:35', '2025-11-25 00:05:35'),
(60, 2, 4, 2, 'Chicken Head', 105.00, 300, 'kg', 300, 300, '2025-12-27', 'active', '2025-11-25 00:40:19', '2025-11-25 00:40:19'),
(61, 2, 12, 2, 'Whole Chicken', 250.00, 400, 'kg', 100, 1000, '2026-03-10', 'active', '2025-11-25 15:45:57', '2025-11-26 15:23:40'),
(62, 3, 12, 2, 'Chicken Breast', 280.00, 550, 'kg', 100, 1000, '2026-02-26', 'active', '2025-11-25 15:45:57', '2025-11-25 16:35:32'),
(63, 4, 12, 2, 'Chicken Thigh', 220.00, 550, 'kg', 100, 1000, '2026-02-23', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(64, 5, 12, 2, 'Chicken Wings', 180.00, 450, 'kg', 100, 800, '2026-02-18', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(65, 5, 12, 2, 'Chicken Liver', 120.00, 650, 'kg', 50, 600, '2026-02-20', 'active', '2025-11-25 15:45:57', '2025-11-27 02:58:38'),
(66, 3, 12, 2, 'Chicken Gizzard', 130.00, 280, 'kg', 50, 600, '2026-02-21', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(67, 4, 12, 2, 'Chicken Feet', 90.00, 200, 'kg', 50, 500, '2026-02-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(68, 5, 12, 2, 'Chicken Head', 80.00, 150, 'kg', 30, 400, '2026-02-22', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(69, 6, 12, 2, 'Chicken Neck', 100.00, 180, 'kg', 50, 500, '2026-02-19', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(70, 2, 12, 2, 'Chicken Back', 110.00, 90, 'kg', 50, 550, '2026-02-24', 'active', '2025-11-25 15:45:57', '2025-11-27 13:33:23'),
(71, 3, 12, 2, 'Chicken Heart', 140.00, 120, 'kg', 30, 400, '2026-02-17', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(72, 4, 12, 2, 'Chicken Kidney', 135.00, 100, 'kg', 30, 350, '2026-02-16', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(73, 5, 12, 2, 'Chicken Intestine', 95.00, 80, 'kg', 20, 300, '2026-02-15', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(74, 6, 12, 2, 'Chicken Blood', 85.00, 360, 'kg', 20, 250, '2026-02-13', 'active', '2025-11-25 15:45:57', '2025-11-27 02:57:13'),
(75, 3, 12, 2, 'Chicken Skin', 75.00, 400, 'kg', 30, 400, '2026-02-27', 'active', '2025-11-25 15:45:57', '2025-11-27 02:48:39'),
(76, 3, 12, 2, 'Chicken Fat', 70.00, 100, 'kg', 20, 300, '2026-03-01', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(77, 4, 12, 2, 'Chicken Bones', 60.00, 200, 'kg', 50, 500, '2026-03-03', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(78, 5, 12, 2, 'Chicken Tail', 105.00, 120, 'kg', 30, 350, '2026-02-20', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(79, 6, 12, 2, 'Chicken Leg Quarter', 210.00, 350, 'kg', 80, 700, '2026-02-24', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(80, 2, 12, 2, 'Chicken Breast Fillet', 320.00, 480, 'kg', 100, 800, '2026-02-24', 'active', '2025-11-25 15:45:57', '2025-11-27 02:55:06'),
(81, 3, 12, 2, 'Chicken Tenderloin', 290.00, 250, 'kg', 50, 600, '2026-02-21', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(82, 4, 12, 2, 'Chicken Wing Tip', 160.00, 180, 'kg', 40, 400, '2026-02-19', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(83, 5, 12, 2, 'Chicken Wing Flat', 170.00, 200, 'kg', 40, 450, '2026-02-18', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(84, 6, 12, 2, 'Chicken Wing Drumlette', 175.00, 220, 'kg', 50, 500, '2026-02-17', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(85, 3, 13, 2, 'Chicken Kidney', 135.00, 200, 'kg', 200, 200, '2025-12-29', 'active', '2025-11-27 00:35:53', '2025-11-27 00:35:53'),
(86, 2, 12, 2, 'Chicken Tail', 105.00, 220, 'kg', 30, 350, '2026-02-20', 'active', '2025-11-27 03:06:44', '2025-11-29 02:46:56'),
(87, 6, 12, 2, 'Chicken Head', 80.00, 250, 'kg', 30, 400, '2026-02-22', 'active', '2025-11-27 03:10:43', '2025-11-29 02:46:56'),
(88, 2, 12, 2, 'Chicken Wings', 180.00, 350, 'kg', 100, 800, '2026-02-18', 'active', '2025-11-27 13:31:24', '2025-11-27 13:32:22'),
(89, 5, 12, 2, 'Whole Chicken', 250.00, 500, 'kg', 100, 1000, '2026-03-12', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(90, 4, 12, 2, 'Chicken Breast', 280.00, 600, 'kg', 100, 1000, '2026-02-28', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(91, 1, 12, 2, 'Chicken Thigh', 220.00, 550, 'kg', 100, 1000, '2026-02-25', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(92, 6, 12, 2, 'Chicken Wings', 180.00, 450, 'kg', 100, 800, '2026-02-20', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(93, 3, 12, 2, 'Chicken Liver', 120.00, 300, 'kg', 50, 600, '2026-02-22', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(94, 5, 12, 2, 'Chicken Gizzard', 130.00, 280, 'kg', 50, 600, '2026-02-23', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(95, 1, 12, 2, 'Ground Chicken', 240.00, 150, 'kg', 30, 400, '2026-02-15', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(96, 2, 12, 3, 'Coca-Cola (1.5L)', 65.00, 225, 'bottles', 50, 500, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-29 02:50:48'),
(97, 4, 12, 3, 'Sprite (1.5L)', 65.00, 180, 'bottles', 50, 500, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(98, 1, 12, 3, 'Royal (1.5L)', 65.00, 150, 'bottles', 50, 500, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(99, 6, 12, 3, 'Bottled Water (500ml)', 15.00, 500, 'bottles', 100, 1000, '2027-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(100, 2, 12, 3, 'Iced Tea Powder', 350.00, 50, 'packs', 20, 150, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(101, 3, 12, 3, 'Orange Juice', 85.00, 100, 'bottles', 30, 300, '2026-05-26', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(102, 5, 12, 3, 'Coffee (3-in-1)', 8.00, 200, 'packs', 50, 500, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(103, 5, 12, 4, 'Ketchup (Gallon)', 450.00, 30, 'bottles', 10, 100, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(104, 4, 12, 4, 'Mayonnaise (Gallon)', 520.00, 25, 'bottles', 10, 80, '2026-05-26', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(105, 1, 12, 4, 'Hot Sauce', 85.00, 50, 'bottles', 20, 150, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(106, 6, 12, 4, 'Soy Sauce (Gallon)', 280.00, 20, 'bottles', 10, 60, '2027-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(107, 2, 12, 4, 'Gravy Mix', 45.00, 100, 'packs', 30, 300, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(108, 3, 12, 4, 'BBQ Sauce', 180.00, 40, 'bottles', 15, 100, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(109, 5, 12, 4, 'Sweet Chili Sauce', 150.00, 35, 'bottles', 15, 100, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(110, 5, 12, 5, 'Vegetable Oil (20L)', 1850.00, 15, 'pcs', 5, 50, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(111, 4, 12, 5, 'Vegetable Oil (5L)', 480.00, 30, 'pcs', 10, 100, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(112, 1, 12, 5, 'Palm Oil (20L)', 1650.00, 10, 'pcs', 5, 40, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(113, 6, 12, 5, 'Shortening', 120.00, 20, 'kg', 10, 80, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(114, 5, 12, 6, 'Salt (1kg)', 25.00, 100, 'packs', 30, 300, '2028-11-26', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(115, 4, 12, 6, 'Black Pepper (Ground)', 85.00, 50, 'packs', 20, 150, '2027-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(116, 1, 12, 6, 'Garlic Powder', 95.00, 40, 'packs', 15, 120, '2027-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(117, 6, 12, 6, 'Paprika', 120.00, 30, 'packs', 10, 100, '2027-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(118, 2, 12, 6, 'Chicken Seasoning', 75.00, 60, 'packs', 20, 200, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(119, 3, 12, 6, 'MSG', 45.00, 50, 'packs', 20, 150, '2028-11-26', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(120, 5, 12, 6, 'Cajun Seasoning', 150.00, 25, 'packs', 10, 80, '2027-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(121, 5, 12, 11, 'White Rice (25kg)', 1450.00, 50, 'bags', 20, 150, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(122, 4, 12, 11, 'White Rice (10kg)', 580.00, 30, 'bags', 15, 100, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(123, 1, 12, 11, 'Java Rice Mix', 65.00, 40, 'packs', 15, 120, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(124, 6, 12, 8, 'Garlic Rice Mix', 55.00, 45, 'packs', 15, 150, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(125, 5, 12, 8, 'Cabbage', 45.00, 50, 'kg', 20, 150, '2025-12-11', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(126, 4, 12, 8, 'Carrots', 60.00, 30, 'kg', 15, 100, '2025-12-18', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(127, 1, 12, 8, 'Onions (White)', 80.00, 40, 'kg', 15, 120, '2025-12-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(128, 6, 12, 8, 'Garlic (Bulb)', 180.00, 25, 'kg', 10, 80, '2026-01-26', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(129, 2, 12, 8, 'Potatoes', 55.00, 60, 'kg', 20, 200, '2025-12-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(130, 3, 12, 8, 'Lettuce', 120.00, 20, 'kg', 10, 60, '2025-12-04', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(131, 5, 12, 8, 'Coleslaw Mix', 95.00, 30, 'kg', 15, 100, '2025-12-04', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(132, 5, 12, 9, 'Burger Buns', 15.00, 200, 'pcs', 50, 500, '2025-12-04', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(133, 4, 12, 9, 'Sandwich Bread', 65.00, 50, 'packs', 20, 150, '2025-12-04', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(134, 1, 12, 9, 'Pandesal', 5.00, 300, 'pcs', 100, 600, '2025-11-30', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(135, 6, 12, 9, 'Tortilla Wraps', 12.00, 100, 'pcs', 30, 300, '2025-12-11', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(136, 5, 12, 6, 'Butter (Salted)', 180.00, 30, 'pcs', 10, 100, '2026-02-25', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(137, 4, 12, 10, 'Cheese (Cheddar Block)', 450.00, 20, 'kg', 10, 60, '2026-02-25', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(138, 1, 12, 10, 'Cheese (Sliced)', 85.00, 50, 'packs', 20, 150, '2026-01-26', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(139, 6, 12, 10, 'Milk (Fresh 1L)', 95.00, 40, 'bottles', 20, 120, '2025-12-11', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(140, 2, 12, 10, 'Heavy Cream', 180.00, 25, 'bottles', 10, 80, '2025-12-18', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(141, 5, 12, 11, 'Frozen Fries (Regular)', 250.00, 100, 'packs', 30, 300, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(142, 4, 12, 11, 'Frozen Fries (Curly)', 280.00, 80, 'packs', 25, 250, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(143, 1, 12, 11, 'Frozen Fries (Wedges)', 290.00, 60, 'packs', 20, 200, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(144, 6, 12, 8, 'Frozen Corn', 120.00, 50, 'packs', 20, 150, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(145, 2, 12, 10, 'Ice Cream (Vanilla)', 350.00, 30, 'pcs', 15, 100, '2026-05-26', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(146, 5, 12, 12, 'Takeout Box (Small)', 8.00, 500, 'pcs', 200, 2000, NULL, 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(147, 4, 12, 12, 'Takeout Box (Medium)', 12.00, 400, 'pcs', 150, 1500, NULL, 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(148, 1, 12, 12, 'Takeout Box (Large)', 18.00, 300, 'pcs', 100, 1000, NULL, 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(149, 6, 12, 12, 'Paper Bag (Small)', 3.00, 600, 'pcs', 200, 2000, NULL, 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(150, 2, 12, 12, 'Paper Bag (Large)', 5.00, 400, 'pcs', 150, 1500, NULL, 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(151, 3, 12, 12, 'Plastic Utensils Set', 5.00, 1000, 'pcs', 300, 3000, NULL, 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(152, 5, 12, 12, 'Drinking Straws', 0.50, 2000, 'pcs', 500, 5000, NULL, 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(153, 4, 12, 12, 'Cup (12oz)', 4.00, 500, 'pcs', 200, 2000, NULL, 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(154, 1, 12, 12, 'Aluminum Foil', 250.00, 30, 'rolls', 10, 100, NULL, 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(155, 5, 12, 13, 'Dish Soap (Gallon)', 350.00, 20, 'bottles', 10, 60, '2027-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(156, 4, 12, 13, 'Hand Sanitizer', 150.00, 50, 'bottles', 20, 150, '2027-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(157, 1, 12, 13, 'Bleach', 180.00, 15, 'bottles', 5, 50, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(158, 6, 12, 13, 'Degreaser', 280.00, 20, 'bottles', 10, 60, '2027-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(159, 2, 12, 13, 'Trash Bags (Large)', 85.00, 100, 'packs', 30, 300, NULL, 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(160, 3, 12, 13, 'Disposable Gloves', 250.00, 50, 'boxes', 20, 150, NULL, 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(161, 5, 12, 10, 'Eggs (Tray - 30pcs)', 220.00, 100, 'pcs', 30, 300, '2025-12-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(162, 4, 12, 10, 'Eggs (Dozen)', 95.00, 50, 'pcs', 20, 150, '2025-12-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(163, 5, 12, 15, 'All-Purpose Flour (25kg)', 950.00, 20, 'bags', 10, 60, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(164, 4, 12, 15, 'All-Purpose Flour (1kg)', 55.00, 50, 'packs', 20, 150, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(165, 1, 12, 15, 'Breading Mix', 120.00, 80, 'packs', 30, 250, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(166, 6, 12, 8, 'Cornstarch', 45.00, 40, 'packs', 15, 120, '2027-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(167, 2, 12, 15, 'Panko Breadcrumbs', 150.00, 30, 'packs', 10, 100, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(168, 6, 12, 16, 'Chicken Marinade', 180.00, 140, 'bottles', 15, 120, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-29 02:51:06'),
(169, 4, 12, 16, 'BBQ Marinade', 195.00, 30, 'bottles', 10, 100, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(170, 1, 12, 10, 'Buttermilk Brine', 220.00, 25, 'bottles', 10, 80, '2026-02-25', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(171, 6, 12, 8, 'Soy Garlic Marinade', 165.00, 35, 'bottles', 15, 100, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(172, 5, 12, 8, 'Coleslaw (Pre-made)', 150.00, 30, 'kg', 15, 100, '2025-12-04', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(173, 4, 12, 17, 'Mashed Potato Mix', 85.00, 50, 'packs', 20, 150, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(174, 1, 12, 4, 'Gravy (Pre-made)', 120.00, 40, 'bottles', 15, 120, '2026-02-25', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(175, 6, 12, 10, 'Mac and Cheese Mix', 95.00, 30, 'packs', 15, 100, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(176, 5, 12, 18, 'Chocolate Syrup', 180.00, 25, 'bottles', 10, 80, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(177, 4, 12, 18, 'Caramel Syrup', 195.00, 20, 'bottles', 10, 60, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(178, 1, 12, 18, 'Sundae Toppings', 85.00, 30, 'packs', 15, 100, '2026-05-26', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(179, 6, 12, 18, 'Brownie Mix', 120.00, 25, 'packs', 10, 80, '2026-11-27', 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(180, 5, 12, 19, 'Napkins (Pack)', 45.00, 200, 'packs', 50, 500, NULL, 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(181, 4, 12, 19, 'Paper Towels (Roll)', 65.00, 100, 'rolls', 30, 300, NULL, 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(182, 1, 12, 19, 'Tissue Paper (Box)', 85.00, 80, 'boxes', 30, 250, NULL, 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(183, 6, 12, 19, 'Parchment Paper', 180.00, 30, 'rolls', 10, 100, NULL, 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(184, 5, 12, 20, 'Tongs', 150.00, 20, 'pcs', 5, 50, NULL, 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(185, 4, 12, 20, 'Spatula', 120.00, 15, 'pcs', 5, 40, NULL, 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(186, 1, 12, 20, 'Thermometer', 350.00, 10, 'pcs', 3, 30, NULL, 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(187, 6, 12, 20, 'Cutting Board', 280.00, 15, 'pcs', 5, 40, NULL, 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(188, 2, 12, 20, 'Strainer', 180.00, 10, 'pcs', 3, 30, NULL, 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(189, 5, 12, 21, 'Chef Hat', 85.00, 30, 'pcs', 10, 80, NULL, 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(190, 4, 12, 21, 'Hair Net', 5.00, 200, 'pcs', 50, 500, NULL, 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(191, 1, 12, 21, 'Apron', 180.00, 40, 'pcs', 15, 100, NULL, 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(192, 6, 12, 21, 'Kitchen Gloves', 95.00, 50, 'pcs', 20, 150, NULL, 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(193, 2, 12, 21, 'Face Mask', 150.00, 100, 'boxes', 30, 300, NULL, 'active', '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(194, 5, 12, 2, 'Chicken Cutlet', 260.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(195, 5, 12, 3, 'Coca-Cola (330ml can)', 25.00, 0, 'cans', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(196, 5, 12, 3, 'Sprite (330ml can)', 25.00, 0, 'cans', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(197, 5, 12, 3, 'Royal (330ml can)', 25.00, 0, 'cans', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(198, 5, 12, 3, 'Pepsi (1.5L)', 65.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(199, 5, 12, 3, 'Mountain Dew (1.5L)', 65.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(200, 5, 12, 3, 'Bottled Water (1L)', 25.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(201, 5, 12, 3, 'Mineral Water (6L)', 85.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(202, 5, 12, 3, 'Pineapple Juice', 85.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(203, 5, 12, 3, 'Mango Juice', 85.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(204, 5, 12, 18, 'Hot Chocolate Mix', 120.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(205, 5, 12, 3, 'Lemonade Mix', 95.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(206, 5, 12, 3, 'Fruit Punch', 90.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(207, 5, 12, 3, 'Energy Drink', 45.00, 0, 'cans', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(208, 5, 12, 3, 'Yakult', 12.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(209, 5, 12, 10, 'Fresh Milk (1L)', 95.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(210, 5, 12, 10, 'Chocolate Milk', 100.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(211, 5, 12, 4, 'Ketchup (Sachet)', 2.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(212, 5, 12, 4, 'Mayonnaise (Sachet)', 3.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(213, 5, 12, 4, 'Soy Sauce (Sachet)', 1.50, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(214, 5, 12, 4, 'Vinegar (Gallon)', 150.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(215, 5, 12, 4, 'Brown Gravy', 50.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(216, 5, 12, 4, 'White Gravy', 50.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(217, 5, 12, 4, 'Honey Mustard', 200.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(218, 5, 12, 4, 'Ranch Dressing', 220.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(219, 5, 12, 4, 'Thousand Island', 210.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(220, 5, 12, 4, 'Caesar Dressing', 230.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(221, 5, 12, 8, 'Garlic Sauce', 160.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(222, 5, 12, 10, 'Cheese Sauce', 180.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(223, 5, 12, 4, 'Tartar Sauce', 170.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(224, 5, 12, 4, 'Buffalo Sauce', 175.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(225, 5, 12, 4, 'Teriyaki Sauce', 180.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(226, 5, 12, 5, 'Palm Oil (5L)', 420.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(227, 5, 12, 5, 'Canola Oil (5L)', 500.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(228, 5, 12, 8, 'Corn Oil (5L)', 480.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(229, 5, 12, 5, 'Coconut Oil (5L)', 550.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(230, 5, 12, 5, 'Olive Oil (1L)', 350.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(231, 5, 12, 6, 'Butter (Unsalted)', 185.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(232, 5, 12, 5, 'Margarine', 95.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(233, 5, 12, 6, 'Iodized Salt', 28.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(234, 5, 12, 6, 'Rock Salt', 30.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(235, 5, 12, 6, 'White Pepper', 90.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(236, 5, 12, 6, 'Onion Powder', 90.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(237, 5, 12, 6, 'Cayenne Pepper', 110.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(238, 5, 12, 6, 'Chili Powder', 100.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(239, 5, 12, 6, 'All-Purpose Seasoning', 80.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(240, 5, 12, 6, 'Oregano', 130.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(241, 5, 12, 6, 'Basil', 125.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(242, 5, 12, 6, 'Thyme', 135.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(243, 5, 12, 6, 'Rosemary', 140.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(244, 5, 12, 6, 'Bay Leaves', 115.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(245, 5, 12, 6, 'Cumin', 120.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(246, 5, 12, 6, 'Curry Powder', 110.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(247, 5, 12, 6, 'Five Spice', 145.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(248, 5, 12, 6, 'Lemon Pepper', 105.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(249, 5, 12, 6, 'Italian Seasoning', 125.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(250, 5, 12, 11, 'White Rice (5kg)', 300.00, 0, 'bags', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(251, 5, 12, 11, 'Brown Rice (5kg)', 350.00, 0, 'bags', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(252, 5, 12, 11, 'Fried Rice Mix', 60.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(253, 5, 12, 11, 'Jasmine Rice (25kg)', 1500.00, 0, 'bags', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(254, 5, 12, 11, 'Sticky Rice', 320.00, 0, 'bags', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(255, 5, 12, 8, 'Corn Grits', 280.00, 0, 'bags', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(256, 5, 12, 8, 'Onions (Red)', 85.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(257, 5, 12, 8, 'Tomatoes', 70.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(258, 5, 12, 8, 'Cucumber', 50.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(259, 5, 12, 6, 'Bell Pepper (Green)', 90.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(260, 5, 12, 6, 'Bell Pepper (Red)', 95.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(261, 5, 12, 8, 'Celery', 85.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(262, 5, 12, 8, 'Spring Onions', 75.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(263, 5, 12, 8, 'Ginger', 95.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(264, 5, 12, 3, 'Lemon', 65.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(265, 5, 12, 8, 'Calamansi', 70.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(266, 5, 12, 8, 'Chili (Siling Labuyo)', 150.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(267, 5, 12, 8, 'Pickles', 95.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(268, 5, 12, 8, 'Corn (Canned)', 45.00, 0, 'cans', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(269, 5, 12, 9, 'Hotdog Buns', 14.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(270, 5, 12, 9, 'Dinner Rolls', 12.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(271, 5, 12, 9, 'Pita Bread', 18.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(272, 5, 12, 8, 'Garlic Bread', 25.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(273, 5, 12, 9, 'Breadcrumbs', 85.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(274, 5, 12, 9, 'Croutons', 75.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(275, 5, 12, 10, 'Cheese (Parmesan)', 550.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(276, 5, 12, 10, 'Cream Cheese', 220.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(277, 5, 12, 10, 'Milk (Evaporated)', 45.00, 0, 'cans', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(278, 5, 12, 10, 'Milk (Condensed)', 55.00, 0, 'cans', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(279, 5, 12, 10, 'Sour Cream', 160.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(280, 5, 12, 10, 'Whipped Cream', 140.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(281, 5, 12, 10, 'Yogurt', 75.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(282, 5, 12, 11, 'Frozen Peas', 125.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(283, 5, 12, 11, 'Frozen Mixed Vegetables', 130.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(284, 5, 12, 10, 'Ice Cream (Chocolate)', 350.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(285, 2, 12, 10, 'Ice Cream (Strawberry)', 350.00, 50, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-12-01 13:41:58'),
(286, 5, 12, 10, 'Frozen Mozzarella Sticks', 320.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(287, 5, 12, 12, 'Plastic Container (Round)', 15.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(288, 5, 12, 12, 'Plastic Container (Rectangle)', 18.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(289, 5, 12, 12, 'Styrofoam Box', 10.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(290, 5, 12, 12, 'Plastic Fork', 0.50, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(291, 5, 12, 12, 'Plastic Spoon', 0.50, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(292, 5, 12, 12, 'Plastic Knife', 0.50, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(293, 5, 12, 12, 'Cup (8oz)', 3.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(294, 5, 12, 12, 'Cup (16oz)', 5.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(295, 5, 12, 12, 'Cup Lid', 1.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(296, 5, 12, 12, 'Sauce Cup (Small)', 2.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(297, 5, 12, 12, 'Cling Wrap', 180.00, 0, 'rolls', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(298, 5, 12, 12, 'Wax Paper', 120.00, 0, 'rolls', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(299, 5, 12, 13, 'Dish Soap (500ml)', 120.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(300, 5, 12, 13, 'Hand Soap', 85.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(301, 5, 12, 13, 'Floor Cleaner', 200.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(302, 5, 12, 13, 'Glass Cleaner', 150.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(303, 5, 12, 13, 'Disinfectant Spray', 220.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(304, 5, 12, 13, 'Sponge', 25.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(305, 5, 12, 13, 'Steel Wool', 35.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(306, 5, 12, 13, 'Scrub Brush', 45.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(307, 5, 12, 13, 'Mop Head', 120.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(308, 5, 12, 13, 'Trash Bags (Small)', 45.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(309, 5, 12, 13, 'Rubber Gloves', 150.00, 0, 'pairs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(310, 5, 12, 10, 'Eggs (Half Dozen)', 50.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(311, 5, 12, 10, 'Quail Eggs', 180.00, 0, 'tray', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(312, 5, 12, 6, 'Salted Eggs', 120.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(313, 5, 12, 10, 'Century Eggs', 150.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(314, 5, 12, 15, 'Batter Mix', 110.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(315, 5, 12, 15, 'Tempura Flour', 130.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(316, 5, 12, 15, 'Seasoned Flour', 100.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(317, 5, 12, 15, 'Cake Flour', 60.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(318, 5, 12, 15, 'Bread Flour', 65.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(319, 5, 12, 16, 'Teriyaki Marinade', 185.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(320, 5, 12, 8, 'Honey Garlic Marinade', 200.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(321, 5, 12, 16, 'Lemon Herb Marinade', 190.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(322, 5, 12, 16, 'Spicy Marinade', 175.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(323, 5, 12, 6, 'Salt Brine Mix', 85.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(324, 5, 12, 3, 'Pickle Juice', 95.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(325, 5, 12, 8, 'Corn on the Cob', 35.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(326, 5, 12, 17, 'Baked Beans', 55.00, 0, 'cans', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(327, 5, 12, 17, 'Potato Salad', 140.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(328, 5, 12, 17, 'Garden Salad Mix', 110.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(329, 5, 12, 18, 'Strawberry Syrup', 185.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(330, 5, 12, 18, 'Sprinkles', 65.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(331, 5, 12, 18, 'Cake Mix', 110.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(332, 5, 12, 18, 'Cookies', 75.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(333, 5, 12, 10, 'Ice Cream Cones', 45.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(334, 5, 12, 18, 'Fruit Cocktail', 55.00, 0, 'cans', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(335, 5, 12, 18, 'Leche Flan Mix', 95.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(336, 5, 12, 19, 'Toilet Paper', 75.00, 0, 'rolls', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(337, 5, 12, 19, 'Paper Plates', 35.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(338, 5, 12, 19, 'Paper Cups', 25.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(339, 5, 12, 19, 'Wax Paper Sheets', 120.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(340, 5, 12, 20, 'Ladle', 100.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(341, 5, 12, 20, 'Measuring Cups', 180.00, 0, 'set', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(342, 5, 12, 20, 'Measuring Spoons', 95.00, 0, 'set', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(343, 5, 12, 20, 'Timer', 250.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(344, 5, 12, 20, 'Chef Knife', 450.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(345, 5, 12, 20, 'Peeler', 85.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(346, 5, 12, 20, 'Can Opener', 120.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(347, 5, 12, 20, 'Whisk', 95.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(348, 5, 12, 20, 'Mixing Bowl', 150.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(349, 5, 12, 20, 'Colander', 200.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(350, 5, 12, 21, 'Safety Shoes', 850.00, 0, 'pairs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(351, 5, 12, 21, 'Polo Shirt (Staff)', 350.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(352, 5, 12, 21, 'Name Tag', 25.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30');

-- --------------------------------------------------------

--
-- Table structure for table `product_catalog`
--

CREATE TABLE `product_catalog` (
  `id` int(10) UNSIGNED NOT NULL,
  `sku` varchar(50) NOT NULL,
  `name` varchar(150) NOT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `standard_cost` decimal(10,2) DEFAULT NULL COMMENT 'Average cost from suppliers',
  `selling_price` decimal(10,2) DEFAULT NULL COMMENT 'Standard selling price',
  `min_order_qty` int(11) NOT NULL DEFAULT 1,
  `reorder_point` int(11) DEFAULT NULL COMMENT 'Minimum stock before reorder',
  `description` text DEFAULT NULL,
  `status` enum('active','discontinued','seasonal') NOT NULL DEFAULT 'active',
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Master product catalog (normalized from products table)';

--
-- Dumping data for table `product_catalog`
--

INSERT INTO `product_catalog` (`id`, `sku`, `name`, `category_id`, `unit`, `standard_cost`, `selling_price`, `min_order_qty`, `reorder_point`, `description`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'C15-8C0AE9', 'All-Purpose Flour (1kg)', 15, 'packs', 38.50, 55.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(2, 'C15-B983DA', 'All-Purpose Flour (25kg)', 15, 'bags', 665.00, 950.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(3, 'C06-708D8D', 'All-Purpose Seasoning', 6, 'packs', 56.00, 80.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(4, 'C12-1A156A', 'Aluminum Foil', 12, 'rolls', 175.00, 250.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(5, 'C21-209CEE', 'Apron', 21, 'pcs', 126.00, 180.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(6, 'C17-DEECF9', 'Baked Beans', 17, 'cans', 38.50, 55.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(7, 'C06-64BF6A', 'Basil', 6, 'packs', 87.50, 125.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(8, 'C15-71036A', 'Batter Mix', 15, 'packs', 77.00, 110.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(9, 'C06-327AEF', 'Bay Leaves', 6, 'packs', 80.50, 115.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(10, 'C16-454E58', 'BBQ Marinade', 16, 'bottles', 136.50, 195.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(11, 'C04-5FD122', 'BBQ Sauce', 4, 'bottles', 126.00, 180.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(12, 'C06-747A94', 'Bell Pepper (Green)', 6, 'kg', 63.00, 90.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(13, 'C06-1E0128', 'Bell Pepper (Red)', 6, 'kg', 66.50, 95.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(14, 'C06-412325', 'Black Pepper (Ground)', 6, 'packs', 59.50, 85.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(15, 'C13-1BFF57', 'Bleach', 13, 'bottles', 126.00, 180.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(16, 'C03-0830EC', 'Bottled Water (1L)', 3, 'bottles', 17.50, 25.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(17, 'C03-6694B9', 'Bottled Water (500ml)', 3, 'bottles', 10.50, 15.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(18, 'C15-16C386', 'Bread Flour', 15, 'packs', 45.50, 65.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(19, 'C09-6E1229', 'Breadcrumbs', 9, 'packs', 59.50, 85.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(20, 'C15-AF500E', 'Breading Mix', 15, 'packs', 84.00, 120.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(21, 'C04-704F9D', 'Brown Gravy', 4, 'packs', 35.00, 50.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(22, 'C11-E52AED', 'Brown Rice (5kg)', 11, 'bags', 245.00, 350.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(23, 'C18-68B3C1', 'Brownie Mix', 18, 'packs', 84.00, 120.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(24, 'C04-F66D13', 'Buffalo Sauce', 4, 'bottles', 122.50, 175.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(25, 'C09-ECFAEB', 'Burger Buns', 9, 'pcs', 10.50, 15.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(26, 'C06-FACE6A', 'Butter (Salted)', 6, 'pcs', 126.00, 180.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(27, 'C06-A0E464', 'Butter (Unsalted)', 6, 'pcs', 129.50, 185.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(28, 'C10-9E15A9', 'Buttermilk Brine', 10, 'bottles', 154.00, 220.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(29, 'C08-D846ED', 'Cabbage', 8, 'kg', 31.50, 45.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(30, 'C04-61081F', 'Caesar Dressing', 4, 'bottles', 161.00, 230.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(31, 'C06-9B47D6', 'Cajun Seasoning', 6, 'packs', 105.00, 150.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(32, 'C15-3F088A', 'Cake Flour', 15, 'packs', 42.00, 60.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(33, 'C18-0D091B', 'Cake Mix', 18, 'packs', 77.00, 110.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(34, 'C08-325C22', 'Calamansi', 8, 'kg', 49.00, 70.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(35, 'C20-D21C53', 'Can Opener', 20, 'pcs', 84.00, 120.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(36, 'C05-44F6F9', 'Canola Oil (5L)', 5, 'pcs', 350.00, 500.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(37, 'C18-640F40', 'Caramel Syrup', 18, 'bottles', 136.50, 195.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(38, 'C08-431FF2', 'Carrots', 8, 'kg', 42.00, 60.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(39, 'C06-B11136', 'Cayenne Pepper', 6, 'packs', 77.00, 110.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(40, 'C08-31D15D', 'Celery', 8, 'kg', 59.50, 85.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(41, 'C10-3484A7', 'Century Eggs', 10, 'pcs', 105.00, 150.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(42, 'C10-91FACA', 'Cheese (Cheddar Block)', 10, 'kg', 315.00, 450.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(43, 'C10-6E5846', 'Cheese (Parmesan)', 10, 'kg', 385.00, 550.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(44, 'C10-00D2CE', 'Cheese (Sliced)', 10, 'packs', 59.50, 85.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(45, 'C10-651530', 'Cheese Sauce', 10, 'bottles', 126.00, 180.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(46, 'C21-A90C7A', 'Chef Hat', 21, 'pcs', 59.50, 85.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(47, 'C20-C69CBE', 'Chef Knife', 20, 'pcs', 315.00, 450.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(48, 'C02-40D8D1', 'Chicken Back', 2, 'kg', 77.00, 110.00, 1, NULL, NULL, 'active', 12, '2025-11-25 15:45:57', '2025-11-27 13:33:23'),
(49, 'C02-9A2783', 'Chicken Blood', 2, 'kg', 59.50, 85.00, 1, NULL, NULL, 'active', 12, '2025-11-25 15:45:57', '2025-11-27 02:57:13'),
(50, 'C02-E275BC', 'Chicken Bones', 2, 'kg', 42.00, 60.00, 1, NULL, NULL, 'active', 12, '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(51, 'C02-D4B559', 'Chicken Breast', 2, 'kg', 177.33, 253.33, 1, NULL, NULL, 'active', 4, '2025-11-22 22:09:43', '2025-11-27 15:11:09'),
(52, 'C02-9C7E13', 'Chicken Breast Fillet', 2, 'kg', 224.00, 320.00, 1, NULL, NULL, 'active', 12, '2025-11-25 15:45:57', '2025-11-27 02:55:06'),
(53, 'C02-FF2F03', 'Chicken Cutlet', 2, 'kg', 182.00, 260.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(54, 'C02-8272D4', 'Chicken Drumstick', 2, 'kg', 140.00, 200.00, 1, NULL, NULL, 'active', 12, '2025-11-25 15:45:57', '2025-11-27 15:11:09'),
(55, 'C02-E54C2C', 'Chicken Fat', 2, 'kg', 49.00, 70.00, 1, NULL, NULL, 'active', 12, '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(56, 'C02-B00F03', 'Chicken Feet', 2, 'kg', 63.00, 90.00, 1, NULL, NULL, 'active', 4, '2025-11-25 00:00:02', '2025-11-29 11:53:33'),
(57, 'C02-0712C8', 'Chicken Gizzard', 2, 'kg', 91.00, 130.00, 1, NULL, NULL, 'active', 12, '2025-11-25 15:45:57', '2025-11-27 15:11:09'),
(58, 'C02-E3F8B5', 'Chicken Head', 2, 'kg', 61.83, 88.33, 1, NULL, NULL, 'active', 4, '2025-11-25 00:40:19', '2025-11-29 02:46:56'),
(59, 'C02-715F5B', 'Chicken Heart', 2, 'kg', 110.25, 157.50, 1, NULL, NULL, 'active', 4, '2025-11-25 00:05:35', '2025-11-25 15:45:57'),
(60, 'C02-A1D482', 'Chicken Intestine', 2, 'kg', 66.50, 95.00, 1, NULL, NULL, 'active', 12, '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(61, 'C02-D2DB33', 'Chicken Kidney', 2, 'kg', 94.50, 135.00, 1, NULL, NULL, 'active', 12, '2025-11-25 15:45:57', '2025-11-27 00:35:53'),
(62, 'C02-ED1CF3', 'Chicken Leg Quarter', 2, 'kg', 147.00, 210.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(63, 'C02-53E270', 'Chicken Liver', 2, 'kg', 84.00, 120.00, 1, NULL, NULL, 'active', 12, '2025-11-25 15:45:57', '2025-11-27 15:11:09'),
(64, 'C16-96DE35', 'Chicken Marinade', 16, 'bottles', 126.00, 180.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-29 02:51:06'),
(65, 'C02-24CFB5', 'Chicken Neck', 2, 'kg', 66.50, 95.00, 1, NULL, NULL, 'active', 7, '2025-11-22 22:12:24', '2025-11-27 03:16:16'),
(66, 'C06-8FFC8D', 'Chicken Seasoning', 6, 'packs', 52.50, 75.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(67, 'C02-65B6C6', 'Chicken Skin', 2, 'kg', 52.50, 75.00, 1, NULL, NULL, 'active', 12, '2025-11-25 15:45:57', '2025-11-27 02:48:39'),
(68, 'C02-9800BB', 'Chicken Tail', 2, 'kg', 73.50, 105.00, 1, NULL, NULL, 'active', 12, '2025-11-25 15:45:57', '2025-11-29 02:46:56'),
(69, 'C02-693E73', 'Chicken Tenderloin', 2, 'kg', 203.00, 290.00, 1, NULL, NULL, 'active', 12, '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(70, 'C02-51A403', 'Chicken Thigh', 2, 'kg', 154.00, 220.00, 1, NULL, NULL, 'active', 5, '2025-11-22 22:15:49', '2025-11-27 15:11:09'),
(71, 'C02-317CAC', 'Chicken Wing Drumlette', 2, 'kg', 122.50, 175.00, 1, NULL, NULL, 'active', 12, '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(72, 'C02-C135B2', 'Chicken Wing Flat', 2, 'kg', 119.00, 170.00, 1, NULL, NULL, 'active', 12, '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(73, 'C02-8986FC', 'Chicken Wing Tip', 2, 'kg', 112.00, 160.00, 1, NULL, NULL, 'active', 12, '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(74, 'C02-9046FC', 'Chicken Wings', 2, 'kg', 126.00, 180.00, 1, NULL, NULL, 'active', 12, '2025-11-25 15:45:57', '2025-11-27 15:11:09'),
(75, 'C08-ED27CB', 'Chili (Siling Labuyo)', 8, 'kg', 105.00, 150.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(76, 'C06-898EB2', 'Chili Powder', 6, 'packs', 70.00, 100.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(77, 'C10-3DE281', 'Chocolate Milk', 10, 'bottles', 70.00, 100.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(78, 'C18-DBEA36', 'Chocolate Syrup', 18, 'bottles', 126.00, 180.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(79, 'C12-7A29A2', 'Cling Wrap', 12, 'rolls', 126.00, 180.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(80, 'C03-72620E', 'Coca-Cola (1.5L)', 3, 'bottles', 45.50, 65.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-29 02:50:48'),
(81, 'C03-0DB067', 'Coca-Cola (330ml can)', 3, 'cans', 17.50, 25.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(82, 'C05-6EC06A', 'Coconut Oil (5L)', 5, 'pcs', 385.00, 550.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(83, 'C03-27720D', 'Coffee (3-in-1)', 3, 'packs', 5.60, 8.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(84, 'C20-1CCF50', 'Colander', 20, 'pcs', 140.00, 200.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(85, 'C08-1BDF0D', 'Coleslaw (Pre-made)', 8, 'kg', 105.00, 150.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(86, 'C08-480492', 'Coleslaw Mix', 8, 'kg', 66.50, 95.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(87, 'C18-597B56', 'Cookies', 18, 'packs', 52.50, 75.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(88, 'C08-AA1C03', 'Corn (Canned)', 8, 'cans', 31.50, 45.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(89, 'C08-BEB042', 'Corn Grits', 8, 'bags', 196.00, 280.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(90, 'C08-C19CA9', 'Corn Oil (5L)', 8, 'pcs', 336.00, 480.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(91, 'C08-836C15', 'Corn on the Cob', 8, 'pcs', 24.50, 35.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(92, 'C08-203389', 'Cornstarch', 8, 'packs', 31.50, 45.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(93, 'C10-CD1654', 'Cream Cheese', 10, 'pcs', 154.00, 220.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(94, 'C09-BBBF9A', 'Croutons', 9, 'packs', 52.50, 75.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(95, 'C08-039892', 'Cucumber', 8, 'kg', 35.00, 50.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(96, 'C06-FCA64B', 'Cumin', 6, 'packs', 84.00, 120.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(97, 'C12-98162C', 'Cup (12oz)', 12, 'pcs', 2.80, 4.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(98, 'C12-697B38', 'Cup (16oz)', 12, 'pcs', 3.50, 5.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(99, 'C12-071878', 'Cup (8oz)', 12, 'pcs', 2.10, 3.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(100, 'C12-830EB3', 'Cup Lid', 12, 'pcs', 0.70, 1.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(101, 'C06-10913B', 'Curry Powder', 6, 'packs', 77.00, 110.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(102, 'C20-9EB5AB', 'Cutting Board', 20, 'pcs', 196.00, 280.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(103, 'C13-C073B1', 'Degreaser', 13, 'bottles', 196.00, 280.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(104, 'C09-750AB0', 'Dinner Rolls', 9, 'pcs', 8.40, 12.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(105, 'C13-98A717', 'Dish Soap (500ml)', 13, 'bottles', 84.00, 120.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(106, 'C13-4468AF', 'Dish Soap (Gallon)', 13, 'bottles', 245.00, 350.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(107, 'C13-13C6E1', 'Disinfectant Spray', 13, 'bottles', 154.00, 220.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(108, 'C13-AD6556', 'Disposable Gloves', 13, 'boxes', 175.00, 250.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(109, 'C12-7A0CCC', 'Drinking Straws', 12, 'pcs', 0.35, 0.50, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(110, 'C10-14EC11', 'Eggs (Dozen)', 10, 'pcs', 66.50, 95.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(111, 'C10-54E6C9', 'Eggs (Half Dozen)', 10, 'pcs', 35.00, 50.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(112, 'C10-0D0D56', 'Eggs (Tray - 30pcs)', 10, 'pcs', 154.00, 220.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(113, 'C03-BE5CE0', 'Energy Drink', 3, 'cans', 31.50, 45.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(114, 'C21-4847F1', 'Face Mask', 21, 'boxes', 105.00, 150.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(115, 'C06-9B1D1E', 'Five Spice', 6, 'packs', 101.50, 145.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(116, 'C13-738CEC', 'Floor Cleaner', 13, 'bottles', 140.00, 200.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(117, 'C10-7D20D3', 'Fresh Milk (1L)', 10, 'bottles', 66.50, 95.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(118, 'C11-B96D19', 'Fried Rice Mix', 11, 'packs', 42.00, 60.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(119, 'C08-9F1E4A', 'Frozen Corn', 8, 'packs', 84.00, 120.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(120, 'C11-90A8A3', 'Frozen Fries (Curly)', 11, 'packs', 196.00, 280.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(121, 'C11-31E493', 'Frozen Fries (Regular)', 11, 'packs', 175.00, 250.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(122, 'C11-9F32FD', 'Frozen Fries (Wedges)', 11, 'packs', 203.00, 290.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(123, 'C11-143462', 'Frozen Mixed Vegetables', 11, 'packs', 91.00, 130.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(124, 'C10-91E671', 'Frozen Mozzarella Sticks', 10, 'packs', 224.00, 320.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(125, 'C11-E90592', 'Frozen Peas', 11, 'packs', 87.50, 125.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(126, 'C18-2FD03F', 'Fruit Cocktail', 18, 'cans', 38.50, 55.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(127, 'C03-2E33E1', 'Fruit Punch', 3, 'bottles', 63.00, 90.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(128, 'C17-713647', 'Garden Salad Mix', 17, 'kg', 77.00, 110.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(129, 'C08-D2BD03', 'Garlic (Bulb)', 8, 'kg', 126.00, 180.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(130, 'C08-ADD097', 'Garlic Bread', 8, 'pcs', 17.50, 25.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(131, 'C06-584C85', 'Garlic Powder', 6, 'packs', 66.50, 95.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(132, 'C08-666744', 'Garlic Rice Mix', 8, 'packs', 38.50, 55.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(133, 'C08-1853D6', 'Garlic Sauce', 8, 'bottles', 112.00, 160.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(134, 'C08-63E336', 'Ginger', 8, 'kg', 66.50, 95.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(135, 'C13-F8528A', 'Glass Cleaner', 13, 'bottles', 105.00, 150.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(136, 'C04-AC8CDD', 'Gravy (Pre-made)', 4, 'bottles', 84.00, 120.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(137, 'C04-9B2633', 'Gravy Mix', 4, 'packs', 31.50, 45.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(138, 'C02-54CB33', 'Ground Chicken', 2, 'kg', 168.00, 240.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(139, 'C21-BD2096', 'Hair Net', 21, 'pcs', 3.50, 5.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(140, 'C13-CB9E92', 'Hand Sanitizer', 13, 'bottles', 105.00, 150.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(141, 'C13-D8FC35', 'Hand Soap', 13, 'bottles', 59.50, 85.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(142, 'C10-DAE887', 'Heavy Cream', 10, 'bottles', 126.00, 180.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(143, 'C08-0F3059', 'Honey Garlic Marinade', 8, 'bottles', 140.00, 200.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(144, 'C04-A7BBBB', 'Honey Mustard', 4, 'bottles', 140.00, 200.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(145, 'C18-61AC2E', 'Hot Chocolate Mix', 18, 'packs', 84.00, 120.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(146, 'C04-C35BEA', 'Hot Sauce', 4, 'bottles', 59.50, 85.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(147, 'C09-344B8F', 'Hotdog Buns', 9, 'pcs', 9.80, 14.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(148, 'C10-C06FF6', 'Ice Cream (Chocolate)', 10, 'pcs', 245.00, 350.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(149, 'C10-16FA34', 'Ice Cream (Strawberry)', 10, 'pcs', 245.00, 350.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-12-01 13:41:58'),
(150, 'C10-4F3629', 'Ice Cream (Vanilla)', 10, 'pcs', 245.00, 350.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(151, 'C10-50B227', 'Ice Cream Cones', 10, 'pcs', 31.50, 45.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(152, 'C03-9BA558', 'Iced Tea Powder', 3, 'packs', 245.00, 350.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(153, 'C06-CECC5E', 'Iodized Salt', 6, 'packs', 19.60, 28.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(154, 'C06-8AFCBC', 'Italian Seasoning', 6, 'packs', 87.50, 125.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(155, 'C11-3BD253', 'Jasmine Rice (25kg)', 11, 'bags', 1050.00, 1500.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(156, 'C11-2F9C3A', 'Java Rice Mix', 11, 'packs', 45.50, 65.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(157, 'C04-17F263', 'Ketchup (Gallon)', 4, 'bottles', 315.00, 450.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(158, 'C04-F7975C', 'Ketchup (Sachet)', 4, 'pcs', 1.40, 2.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(159, 'C21-C190B7', 'Kitchen Gloves', 21, 'pcs', 66.50, 95.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(160, 'C20-AE62D8', 'Ladle', 20, 'pcs', 70.00, 100.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(161, 'C18-53B9B3', 'Leche Flan Mix', 18, 'packs', 66.50, 95.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(162, 'C03-FF71FF', 'Lemon', 3, 'kg', 45.50, 65.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(163, 'C16-66156D', 'Lemon Herb Marinade', 16, 'bottles', 133.00, 190.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(164, 'C06-8A27FD', 'Lemon Pepper', 6, 'packs', 73.50, 105.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(165, 'C03-5BA8A7', 'Lemonade Mix', 3, 'packs', 66.50, 95.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(166, 'C08-CB9C58', 'Lettuce', 8, 'kg', 84.00, 120.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(167, 'C10-2A948F', 'Mac and Cheese Mix', 10, 'packs', 66.50, 95.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(168, 'C03-358E5D', 'Mango Juice', 3, 'bottles', 59.50, 85.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(169, 'C05-0915D6', 'Margarine', 5, 'pcs', 66.50, 95.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(170, 'C17-ED27F8', 'Mashed Potato Mix', 17, 'packs', 59.50, 85.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(171, 'C04-2EE977', 'Mayonnaise (Gallon)', 4, 'bottles', 364.00, 520.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(172, 'C04-6BF07F', 'Mayonnaise (Sachet)', 4, 'pcs', 2.10, 3.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(173, 'C20-F7ABE9', 'Measuring Cups', 20, 'set', 126.00, 180.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(174, 'C20-0C3C8B', 'Measuring Spoons', 20, 'set', 66.50, 95.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(175, 'C10-93DFFE', 'Milk (Condensed)', 10, 'cans', 38.50, 55.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(176, 'C10-89F0EB', 'Milk (Evaporated)', 10, 'cans', 31.50, 45.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(177, 'C10-DED5D7', 'Milk (Fresh 1L)', 10, 'bottles', 66.50, 95.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(178, 'C03-4850D8', 'Mineral Water (6L)', 3, 'bottles', 59.50, 85.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(179, 'C20-83A1E5', 'Mixing Bowl', 20, 'pcs', 105.00, 150.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(180, 'C13-510626', 'Mop Head', 13, 'pcs', 84.00, 120.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(181, 'C03-096CC3', 'Mountain Dew (1.5L)', 3, 'bottles', 45.50, 65.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(182, 'C06-1B7EF9', 'MSG', 6, 'packs', 31.50, 45.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(183, 'C21-9F9A16', 'Name Tag', 21, 'pcs', 17.50, 25.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(184, 'C19-8E270A', 'Napkins (Pack)', 19, 'packs', 31.50, 45.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(185, 'C05-7A25A3', 'Olive Oil (1L)', 5, 'bottles', 245.00, 350.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(186, 'C06-1EABC6', 'Onion Powder', 6, 'packs', 63.00, 90.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(187, 'C08-3A65DC', 'Onions (Red)', 8, 'kg', 59.50, 85.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(188, 'C08-4616C1', 'Onions (White)', 8, 'kg', 56.00, 80.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(189, 'C03-245C8F', 'Orange Juice', 3, 'bottles', 59.50, 85.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(190, 'C06-5043F5', 'Oregano', 6, 'packs', 91.00, 130.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(191, 'C05-02D960', 'Palm Oil (20L)', 5, 'pcs', 1155.00, 1650.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(192, 'C05-1F1EDD', 'Palm Oil (5L)', 5, 'pcs', 294.00, 420.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(193, 'C09-01F569', 'Pandesal', 9, 'pcs', 3.50, 5.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(194, 'C15-4D1765', 'Panko Breadcrumbs', 15, 'packs', 105.00, 150.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(195, 'C12-6C3B82', 'Paper Bag (Large)', 12, 'pcs', 3.50, 5.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(196, 'C12-821CEE', 'Paper Bag (Small)', 12, 'pcs', 2.10, 3.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(197, 'C19-978898', 'Paper Cups', 19, 'packs', 17.50, 25.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(198, 'C19-D940F2', 'Paper Plates', 19, 'packs', 24.50, 35.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(199, 'C19-04367C', 'Paper Towels (Roll)', 19, 'rolls', 45.50, 65.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(200, 'C06-A66060', 'Paprika', 6, 'packs', 84.00, 120.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(201, 'C19-22FA35', 'Parchment Paper', 19, 'rolls', 126.00, 180.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(202, 'C20-9E7E5F', 'Peeler', 20, 'pcs', 59.50, 85.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(203, 'C03-865C75', 'Pepsi (1.5L)', 3, 'bottles', 45.50, 65.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(204, 'C03-D1745A', 'Pickle Juice', 3, 'bottles', 66.50, 95.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(205, 'C08-F8E51E', 'Pickles', 8, 'kg', 66.50, 95.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(206, 'C03-6DE87F', 'Pineapple Juice', 3, 'bottles', 59.50, 85.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(207, 'C09-6DE722', 'Pita Bread', 9, 'pcs', 12.60, 18.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(208, 'C12-A8E648', 'Plastic Container (Rectangle)', 12, 'pcs', 12.60, 18.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(209, 'C12-F07E64', 'Plastic Container (Round)', 12, 'pcs', 10.50, 15.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(210, 'C12-C071C0', 'Plastic Fork', 12, 'pcs', 0.35, 0.50, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(211, 'C12-F483D9', 'Plastic Knife', 12, 'pcs', 0.35, 0.50, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(212, 'C12-036BB3', 'Plastic Spoon', 12, 'pcs', 0.35, 0.50, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(213, 'C12-748832', 'Plastic Utensils Set', 12, 'pcs', 3.50, 5.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(214, 'C21-CE610B', 'Polo Shirt (Staff)', 21, 'pcs', 245.00, 350.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(215, 'C17-2A74A3', 'Potato Salad', 17, 'kg', 98.00, 140.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(216, 'C08-B1BEBF', 'Potatoes', 8, 'kg', 38.50, 55.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(217, 'C10-4D7B85', 'Quail Eggs', 10, 'tray', 126.00, 180.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(218, 'C04-CED88B', 'Ranch Dressing', 4, 'bottles', 154.00, 220.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(219, 'C06-08B883', 'Rock Salt', 6, 'packs', 21.00, 30.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(220, 'C06-4A60D6', 'Rosemary', 6, 'packs', 98.00, 140.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(221, 'C03-EE2673', 'Royal (1.5L)', 3, 'bottles', 45.50, 65.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(222, 'C03-6593CD', 'Royal (330ml can)', 3, 'cans', 17.50, 25.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(223, 'C13-CC001A', 'Rubber Gloves', 13, 'pairs', 105.00, 150.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(224, 'C21-21FD76', 'Safety Shoes', 21, 'pairs', 595.00, 850.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(225, 'C06-38D506', 'Salt (1kg)', 6, 'packs', 17.50, 25.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(226, 'C06-6904CD', 'Salt Brine Mix', 6, 'packs', 59.50, 85.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(227, 'C06-343391', 'Salted Eggs', 6, 'pcs', 84.00, 120.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(228, 'C09-53D6FD', 'Sandwich Bread', 9, 'packs', 45.50, 65.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(229, 'C12-EF8C58', 'Sauce Cup (Small)', 12, 'pcs', 1.40, 2.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(230, 'C13-6FF6D7', 'Scrub Brush', 13, 'pcs', 31.50, 45.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(231, 'C15-74431F', 'Seasoned Flour', 15, 'packs', 70.00, 100.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(232, 'C05-7A56F8', 'Shortening', 5, 'kg', 84.00, 120.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(233, 'C10-5B591B', 'Sour Cream', 10, 'bottles', 112.00, 160.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(234, 'C08-A76EA9', 'Soy Garlic Marinade', 8, 'bottles', 115.50, 165.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(235, 'C04-18A486', 'Soy Sauce (Gallon)', 4, 'bottles', 196.00, 280.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(236, 'C04-D92957', 'Soy Sauce (Sachet)', 4, 'pcs', 1.05, 1.50, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(237, 'C20-1E62F2', 'Spatula', 20, 'pcs', 84.00, 120.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(238, 'C16-2EF89F', 'Spicy Marinade', 16, 'bottles', 122.50, 175.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(239, 'C13-3F4027', 'Sponge', 13, 'pcs', 17.50, 25.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(240, 'C08-8CDE1C', 'Spring Onions', 8, 'kg', 52.50, 75.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(241, 'C18-77E9D6', 'Sprinkles', 18, 'packs', 45.50, 65.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(242, 'C03-223439', 'Sprite (1.5L)', 3, 'bottles', 45.50, 65.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(243, 'C03-23B4A1', 'Sprite (330ml can)', 3, 'cans', 17.50, 25.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(244, 'C13-96999F', 'Steel Wool', 13, 'pcs', 24.50, 35.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(245, 'C11-0D8A5A', 'Sticky Rice', 11, 'bags', 224.00, 320.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(246, 'C20-12505E', 'Strainer', 20, 'pcs', 126.00, 180.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(247, 'C18-B5CE79', 'Strawberry Syrup', 18, 'bottles', 129.50, 185.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(248, 'C12-D1AA77', 'Styrofoam Box', 12, 'pcs', 7.00, 10.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(249, 'C18-D76E7C', 'Sundae Toppings', 18, 'packs', 59.50, 85.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(250, 'C04-8D8103', 'Sweet Chili Sauce', 4, 'bottles', 105.00, 150.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(251, 'C12-45F1E6', 'Takeout Box (Large)', 12, 'pcs', 12.60, 18.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(252, 'C12-1F4307', 'Takeout Box (Medium)', 12, 'pcs', 8.40, 12.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(253, 'C12-5D86DD', 'Takeout Box (Small)', 12, 'pcs', 5.60, 8.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(254, 'C04-4E81E0', 'Tartar Sauce', 4, 'bottles', 119.00, 170.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(255, 'C15-DF19B3', 'Tempura Flour', 15, 'packs', 91.00, 130.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(256, 'C16-CE8E60', 'Teriyaki Marinade', 16, 'bottles', 129.50, 185.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(257, 'C04-BF072A', 'Teriyaki Sauce', 4, 'bottles', 126.00, 180.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(258, 'C20-D9CB7B', 'Thermometer', 20, 'pcs', 245.00, 350.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(259, 'C04-D542D1', 'Thousand Island', 4, 'bottles', 147.00, 210.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(260, 'C06-2E74F9', 'Thyme', 6, 'packs', 94.50, 135.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(261, 'C20-EFB477', 'Timer', 20, 'pcs', 175.00, 250.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(262, 'C19-84BFF6', 'Tissue Paper (Box)', 19, 'boxes', 59.50, 85.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(263, 'C19-423EC3', 'Toilet Paper', 19, 'rolls', 52.50, 75.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(264, 'C08-748108', 'Tomatoes', 8, 'kg', 49.00, 70.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(265, 'C20-5FB705', 'Tongs', 20, 'pcs', 105.00, 150.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(266, 'C09-9B284A', 'Tortilla Wraps', 9, 'pcs', 8.40, 12.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(267, 'C13-785BAA', 'Trash Bags (Large)', 13, 'packs', 59.50, 85.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(268, 'C13-D4A6E5', 'Trash Bags (Small)', 13, 'packs', 31.50, 45.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(269, 'C05-D01EC7', 'Vegetable Oil (20L)', 5, 'pcs', 1295.00, 1850.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(270, 'C05-60D9E9', 'Vegetable Oil (5L)', 5, 'pcs', 336.00, 480.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(271, 'C04-7A5743', 'Vinegar (Gallon)', 4, 'bottles', 105.00, 150.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(272, 'C12-6F81A6', 'Wax Paper', 12, 'rolls', 84.00, 120.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(273, 'C19-3175A1', 'Wax Paper Sheets', 19, 'packs', 84.00, 120.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(274, 'C10-F3DF33', 'Whipped Cream', 10, 'bottles', 98.00, 140.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(275, 'C20-7B0F00', 'Whisk', 20, 'pcs', 66.50, 95.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(276, 'C04-F396AD', 'White Gravy', 4, 'packs', 35.00, 50.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(277, 'C06-F2A183', 'White Pepper', 6, 'packs', 63.00, 90.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(278, 'C11-5494D2', 'White Rice (10kg)', 11, 'bags', 406.00, 580.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(279, 'C11-364AED', 'White Rice (25kg)', 11, 'bags', 1015.00, 1450.00, 1, NULL, NULL, 'active', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(280, 'C11-7144F6', 'White Rice (5kg)', 11, 'bags', 210.00, 300.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(281, 'C02-4C8E23', 'Whole Chicken', 2, 'kg', 175.00, 250.00, 1, NULL, NULL, 'active', 6, '2025-11-22 22:14:39', '2025-11-27 15:11:09'),
(282, 'C03-1379F6', 'Yakult', 3, 'bottles', 8.40, 12.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(283, 'C10-6A574C', 'Yogurt', 10, 'bottles', 52.50, 75.00, 1, NULL, NULL, 'active', 12, '2025-11-29 02:18:30', '2025-11-29 02:18:30');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `purchase_request_id` int(10) UNSIGNED DEFAULT NULL,
  `supplier_id` int(10) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED NOT NULL,
  `status` enum('pending','approved','sent_to_supplier','in_transit','delivered','cancelled','partial') NOT NULL DEFAULT 'pending',
  `order_date` date NOT NULL,
  `expected_delivery_date` date DEFAULT NULL,
  `actual_delivery_date` date DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `approved_by` int(10) UNSIGNED DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Purchase orders sent to suppliers';

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `order_number`, `purchase_request_id`, `supplier_id`, `branch_id`, `status`, `order_date`, `expected_delivery_date`, `actual_delivery_date`, `total_amount`, `approved_by`, `approved_at`, `notes`, `created_at`, `updated_at`) VALUES
(33, 'PO-20251129-5677', 38, 42, 4, 'approved', '2025-11-29', '2025-12-06', NULL, 18000.00, 2, '2025-11-29 02:57:28', 'Auto-created from approved purchase request', '2025-11-29 02:57:16', '2025-11-29 02:57:28'),
(34, 'PO-20251129-6834', 39, 11, 5, 'approved', '2025-11-29', '2025-12-06', NULL, 24000.00, 2, '2025-11-29 03:05:41', 'Auto-created from approved purchase request', '2025-11-29 02:59:07', '2025-11-29 03:05:41'),
(35, 'PO-20251129-7054', 40, 63, 6, 'approved', '2025-11-29', '2025-12-06', NULL, 9000.00, 2, '2025-11-29 03:05:39', 'Auto-created from approved purchase request', '2025-11-29 03:04:18', '2025-11-29 03:05:39'),
(36, 'PO-20251201-5995', 41, 1, 2, 'approved', '2025-12-01', '2025-12-08', NULL, 17500.00, 2, '2025-12-01 13:40:35', 'Auto-created from approved purchase request', '2025-12-01 13:40:27', '2025-12-01 13:40:35');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

CREATE TABLE `purchase_order_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `purchase_order_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `received_quantity` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Items in purchase orders';

--
-- Dumping data for table `purchase_order_items`
--

INSERT INTO `purchase_order_items` (`id`, `purchase_order_id`, `product_id`, `quantity`, `unit_price`, `subtotal`, `received_quantity`, `created_at`, `updated_at`) VALUES
(32, 33, 298, 150, 120.00, 18000.00, 0, '2025-11-29 02:57:16', '2025-11-29 02:57:16'),
(33, 34, 113, 200, 120.00, 24000.00, 0, '2025-11-29 02:59:07', '2025-11-29 02:59:07'),
(34, 35, 222, 50, 180.00, 9000.00, 0, '2025-11-29 03:04:18', '2025-11-29 03:04:18'),
(35, 36, 285, 50, 350.00, 17500.00, 0, '2025-12-01 13:40:27', '2025-12-01 13:40:27');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requests`
--

CREATE TABLE `purchase_requests` (
  `id` int(10) UNSIGNED NOT NULL,
  `request_number` varchar(50) NOT NULL,
  `branch_id` int(10) UNSIGNED NOT NULL,
  `requested_by` int(10) UNSIGNED NOT NULL,
  `status` enum('pending','approved','rejected','converted_to_po','cancelled') NOT NULL DEFAULT 'pending',
  `priority` enum('low','normal','high','urgent') NOT NULL DEFAULT 'normal',
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `approved_by` int(10) UNSIGNED DEFAULT NULL,
  `approved_at` datetime DEFAULT NULL,
  `selected_supplier_id` int(10) UNSIGNED DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Purchase requests from branches';

--
-- Dumping data for table `purchase_requests`
--

INSERT INTO `purchase_requests` (`id`, `request_number`, `branch_id`, `requested_by`, `status`, `priority`, `total_amount`, `notes`, `approved_by`, `approved_at`, `selected_supplier_id`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(38, 'PR-20251129-5115', 4, 4, 'converted_to_po', 'urgent', 18000.00, '', 2, '2025-11-29 02:57:16', 42, NULL, '2025-11-29 02:55:52', '2025-11-29 02:57:16'),
(39, 'PR-20251129-4567', 5, 7, 'converted_to_po', 'low', 24000.00, '', 2, '2025-11-29 02:59:07', 11, NULL, '2025-11-29 02:56:18', '2025-11-29 02:59:07'),
(40, 'PR-20251129-1540', 6, 8, 'converted_to_po', 'normal', 9000.00, '', 2, '2025-11-29 03:04:18', 63, NULL, '2025-11-29 02:56:41', '2025-11-29 03:04:18'),
(41, 'PR-20251201-8406', 2, 12, 'converted_to_po', 'high', 17500.00, '', 2, '2025-12-01 13:40:27', NULL, NULL, '2025-12-01 13:39:02', '2025-12-01 13:40:27');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_request_items`
--

CREATE TABLE `purchase_request_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `purchase_request_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Items in purchase requests';

--
-- Dumping data for table `purchase_request_items`
--

INSERT INTO `purchase_request_items` (`id`, `purchase_request_id`, `product_id`, `quantity`, `unit_price`, `subtotal`, `notes`, `created_at`, `updated_at`) VALUES
(35, 38, 298, 150, 120.00, 18000.00, '', '2025-11-29 02:55:52', '2025-11-29 02:55:52'),
(36, 39, 113, 200, 120.00, 24000.00, '', '2025-11-29 02:56:18', '2025-11-29 02:56:18'),
(37, 40, 222, 50, 180.00, 9000.00, '', '2025-11-29 02:56:41', '2025-11-29 02:56:41'),
(38, 41, 285, 50, 350.00, 17500.00, '', '2025-12-01 13:39:02', '2025-12-01 13:39:02');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_request_quotations`
--

CREATE TABLE `purchase_request_quotations` (
  `id` int(10) UNSIGNED NOT NULL,
  `purchase_request_id` int(10) UNSIGNED NOT NULL,
  `supplier_id` int(10) UNSIGNED NOT NULL,
  `quoted_total` decimal(10,2) DEFAULT NULL,
  `delivery_lead_time` int(11) DEFAULT NULL COMMENT 'Delivery time in days',
  `payment_terms` varchar(100) DEFAULT NULL,
  `quoted_date` date DEFAULT NULL,
  `valid_until` date DEFAULT NULL,
  `status` enum('pending','selected','rejected') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `quoted_by` int(10) UNSIGNED DEFAULT NULL COMMENT 'User who obtained the quote',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Supplier quotations for purchase requests';

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(10) UNSIGNED NOT NULL,
  `report_number` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Inventory Reports for products';

-- --------------------------------------------------------

--
-- Table structure for table `royalty_payments`
--

CREATE TABLE `royalty_payments` (
  `id` int(11) UNSIGNED NOT NULL,
  `branch_id` int(11) UNSIGNED NOT NULL,
  `period_month` int(2) NOT NULL,
  `period_year` int(4) NOT NULL,
  `gross_sales` decimal(15,2) NOT NULL DEFAULT 0.00,
  `royalty_rate` decimal(5,2) NOT NULL DEFAULT 5.00,
  `royalty_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `marketing_fee` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_due` decimal(15,2) NOT NULL DEFAULT 0.00,
  `amount_paid` decimal(15,2) NOT NULL DEFAULT 0.00,
  `balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','partial','paid','overdue') NOT NULL DEFAULT 'pending',
  `due_date` date DEFAULT NULL,
  `paid_date` date DEFAULT NULL,
  `payment_reference` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `royalty_payments`
--

INSERT INTO `royalty_payments` (`id`, `branch_id`, `period_month`, `period_year`, `gross_sales`, `royalty_rate`, `royalty_amount`, `marketing_fee`, `total_due`, `amount_paid`, `balance`, `status`, `due_date`, `paid_date`, `payment_reference`, `notes`, `created_at`, `updated_at`) VALUES
(1, 7, 12, 2025, 2545525.00, 5.00, 127276.25, 23299.00, 150575.25, 0.00, 150575.25, 'pending', '2026-01-01', NULL, NULL, 'Monthly royalty for SM City Cebu Branch - December 2025', '2025-10-29 09:53:46', '2025-10-29 09:53:46'),
(2, 7, 11, 2025, 2586753.00, 5.00, 129337.65, 23466.00, 152803.65, 110018.63, 42785.02, 'partial', '2025-12-01', '2025-11-30', 'REF-287E8C30', 'Monthly royalty for SM City Cebu Branch - November 2025', '2025-11-01 09:53:46', '2025-11-01 09:53:46'),
(3, 8, 11, 2025, 2597459.00, 5.00, 129872.95, 26160.00, 156032.95, 102981.75, 53051.20, 'partial', '2025-12-01', '2025-12-02', 'REF-5D990E28', 'Monthly royalty for SM Megamall Branch - November 2025', '2025-11-03 09:53:46', '2025-11-03 09:53:46'),
(4, 8, 10, 2025, 891520.00, 5.00, 44576.00, 29635.00, 74211.00, 74211.00, 0.00, 'paid', '2025-11-01', '2025-12-02', 'REF-26E5CA22', 'Monthly royalty for SM Megamall Branch - October 2025', '2025-11-06 09:53:46', '2025-11-06 09:53:46'),
(5, 8, 9, 2025, 2806435.00, 5.00, 140321.75, 10079.00, 150400.75, 0.00, 150400.75, 'overdue', '2025-10-01', NULL, NULL, 'Monthly royalty for SM Megamall Branch - September 2025', '2025-11-09 09:53:46', '2025-11-09 09:53:46'),
(6, 9, 10, 2025, 2768437.00, 5.00, 138421.85, 11226.00, 149647.85, 149647.85, 0.00, 'paid', '2025-11-01', '2025-11-24', 'REF-62C318EC', 'Monthly royalty for Robinsons Place Iloilo Branch - October 2025', '2025-11-08 09:53:46', '2025-11-08 09:53:46'),
(7, 9, 9, 2025, 2532036.00, 5.00, 126601.80, 28717.00, 155318.80, 0.00, 155318.80, 'overdue', '2025-10-01', NULL, NULL, 'Monthly royalty for Robinsons Place Iloilo Branch - September 2025', '2025-11-11 09:53:46', '2025-11-11 09:53:46'),
(8, 9, 8, 2025, 2652976.00, 5.00, 132648.80, 15318.00, 147966.80, 0.00, 147966.80, 'pending', '2025-09-01', NULL, NULL, 'Monthly royalty for Robinsons Place Iloilo Branch - August 2025', '2025-11-14 09:53:46', '2025-11-14 09:53:46'),
(9, 10, 9, 2025, 2399970.00, 5.00, 119998.50, 26650.00, 146648.50, 0.00, 146648.50, 'overdue', '2025-10-01', NULL, NULL, 'Monthly royalty for Centrio Mall Branch - September 2025', '2025-11-13 09:53:46', '2025-11-13 09:53:46'),
(10, 10, 8, 2025, 2496642.00, 5.00, 124832.10, 12639.00, 137471.10, 0.00, 137471.10, 'pending', '2025-09-01', NULL, NULL, 'Monthly royalty for Centrio Mall Branch - August 2025', '2025-11-16 09:53:46', '2025-11-16 09:53:46'),
(11, 10, 7, 2025, 1611318.00, 5.00, 80565.90, 23956.00, 104521.90, 66894.02, 37627.88, 'partial', '2025-08-01', '2025-11-29', 'REF-07B17ECA', 'Monthly royalty for Centrio Mall Branch - July 2025', '2025-11-19 09:53:46', '2025-11-19 09:53:46'),
(12, 11, 8, 2025, 2622847.00, 5.00, 131142.35, 12967.00, 144109.35, 0.00, 144109.35, 'pending', '2025-09-01', NULL, NULL, 'Monthly royalty for Session Road Branch - August 2025', '2025-11-18 09:53:46', '2025-11-18 09:53:46'),
(13, 11, 7, 2025, 2622483.00, 5.00, 131124.15, 18108.00, 149232.15, 114908.76, 34323.39, 'partial', '2025-08-01', '2025-11-28', 'REF-B7262886', 'Monthly royalty for Session Road Branch - July 2025', '2025-11-21 09:53:46', '2025-11-21 09:53:46'),
(14, 11, 6, 2025, 2944762.00, 5.00, 147238.10, 24525.00, 171763.10, 171763.10, 0.00, 'paid', '2025-07-01', '2025-11-30', 'REF-D8E73419', 'Monthly royalty for Session Road Branch - June 2025', '2025-11-24 09:53:46', '2025-11-24 09:53:46');

-- --------------------------------------------------------

--
-- Table structure for table `stock_batches`
--

CREATE TABLE `stock_batches` (
  `id` int(10) UNSIGNED NOT NULL,
  `batch_number` varchar(50) NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED NOT NULL,
  `supplier_id` int(10) UNSIGNED DEFAULT NULL,
  `quantity_initial` int(10) UNSIGNED NOT NULL COMMENT 'Initial quantity received',
  `quantity_current` int(10) UNSIGNED NOT NULL COMMENT 'Current available quantity',
  `unit_cost` decimal(10,2) DEFAULT NULL,
  `manufacture_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `received_date` datetime DEFAULT NULL,
  `status` enum('available','depleted','expired','damaged','recalled','quarantine') NOT NULL DEFAULT 'available',
  `purchase_order_id` int(10) UNSIGNED DEFAULT NULL,
  `delivery_id` int(10) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Physical inventory batches with FIFO/FEFO tracking';

--
-- Dumping data for table `stock_batches`
--

INSERT INTO `stock_batches` (`id`, `batch_number`, `product_id`, `branch_id`, `supplier_id`, `quantity_initial`, `quantity_current`, `unit_cost`, `manufacture_date`, `expiry_date`, `received_date`, `status`, `purchase_order_id`, `delivery_id`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'BATCH-INIT-164-20251203', 1, 4, NULL, 50, 50, 38.50, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 164)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(2, 'BATCH-INIT-163-20251203', 2, 5, NULL, 20, 20, 665.00, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 163)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(3, 'BATCH-INIT-154-20251203', 4, 1, NULL, 30, 30, 175.00, NULL, NULL, '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 154)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(4, 'BATCH-INIT-191-20251203', 5, 1, NULL, 40, 40, 126.00, NULL, NULL, '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 191)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(5, 'BATCH-INIT-169-20251203', 10, 4, NULL, 30, 30, 136.50, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 169)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(6, 'BATCH-INIT-108-20251203', 11, 3, NULL, 40, 40, 126.00, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 108)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(7, 'BATCH-INIT-115-20251203', 14, 4, NULL, 50, 50, 59.50, NULL, '2027-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 115)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(8, 'BATCH-INIT-157-20251203', 15, 1, NULL, 15, 15, 126.00, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 157)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(9, 'BATCH-INIT-99-20251203', 17, 6, NULL, 500, 500, 10.50, NULL, '2027-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 99)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(10, 'BATCH-INIT-165-20251203', 20, 1, NULL, 80, 80, 84.00, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 165)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(11, 'BATCH-INIT-179-20251203', 23, 6, NULL, 25, 25, 84.00, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 179)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(12, 'BATCH-INIT-132-20251203', 25, 5, NULL, 200, 200, 10.50, NULL, '2025-12-04', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 132)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(13, 'BATCH-INIT-136-20251203', 26, 5, NULL, 30, 30, 126.00, NULL, '2026-02-25', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 136)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(14, 'BATCH-INIT-170-20251203', 28, 1, NULL, 25, 25, 154.00, NULL, '2026-02-25', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 170)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(15, 'BATCH-INIT-125-20251203', 29, 5, NULL, 50, 50, 31.50, NULL, '2025-12-11', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 125)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(16, 'BATCH-INIT-120-20251203', 31, 5, NULL, 25, 25, 105.00, NULL, '2027-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 120)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(17, 'BATCH-INIT-177-20251203', 37, 4, NULL, 20, 20, 136.50, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 177)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(18, 'BATCH-INIT-126-20251203', 38, 4, NULL, 30, 30, 42.00, NULL, '2025-12-18', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 126)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(19, 'BATCH-INIT-137-20251203', 42, 4, NULL, 20, 20, 315.00, NULL, '2026-02-25', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 137)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(20, 'BATCH-INIT-138-20251203', 44, 1, NULL, 50, 50, 59.50, NULL, '2026-01-26', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 138)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(21, 'BATCH-INIT-189-20251203', 46, 5, NULL, 30, 30, 59.50, NULL, NULL, '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 189)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(22, 'BATCH-INIT-70-20251203', 48, 2, NULL, 90, 90, 77.00, NULL, '2026-02-24', '2025-11-25 15:45:57', 'available', NULL, NULL, 'Migrated from old products table (ID: 70)', 12, '2025-11-25 15:45:57', '2025-11-27 13:33:23'),
(23, 'BATCH-INIT-74-20251203', 49, 6, NULL, 360, 360, 59.50, NULL, '2026-02-13', '2025-11-25 15:45:57', 'available', NULL, NULL, 'Migrated from old products table (ID: 74)', 12, '2025-11-25 15:45:57', '2025-11-27 02:57:13'),
(24, 'BATCH-INIT-77-20251203', 50, 4, NULL, 200, 200, 42.00, NULL, '2026-03-03', '2025-11-25 15:45:57', 'available', NULL, NULL, 'Migrated from old products table (ID: 77)', 12, '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(25, 'BATCH-INIT-52-20251203', 51, 2, NULL, 200, 200, 140.00, NULL, '2026-02-18', '2025-11-22 22:09:43', 'available', NULL, NULL, 'Migrated from old products table (ID: 52)', 4, '2025-11-22 22:09:43', '2025-11-22 22:09:43'),
(26, 'BATCH-INIT-62-20251203', 51, 3, NULL, 550, 550, 196.00, NULL, '2026-02-26', '2025-11-25 15:45:57', 'available', NULL, NULL, 'Migrated from old products table (ID: 62)', 12, '2025-11-25 15:45:57', '2025-11-25 16:35:32'),
(27, 'BATCH-INIT-90-20251203', 51, 4, NULL, 600, 600, 196.00, NULL, '2026-02-28', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 90)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(28, 'BATCH-INIT-80-20251203', 52, 2, NULL, 480, 480, 224.00, NULL, '2026-02-24', '2025-11-25 15:45:57', 'available', NULL, NULL, 'Migrated from old products table (ID: 80)', 12, '2025-11-25 15:45:57', '2025-11-27 02:55:06'),
(29, 'BATCH-INIT-53-20251203', 54, 6, NULL, 500, 500, 140.00, NULL, '2026-02-23', '2025-11-25 15:45:57', 'available', NULL, NULL, 'Migrated from old products table (ID: 53)', 12, '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(30, 'BATCH-INIT-57-20251203', 54, 2, NULL, 500, 500, 140.00, NULL, '2026-02-25', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 57)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(31, 'BATCH-INIT-76-20251203', 55, 3, NULL, 100, 100, 49.00, NULL, '2026-03-01', '2025-11-25 15:45:57', 'available', NULL, NULL, 'Migrated from old products table (ID: 76)', 12, '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(32, 'BATCH-INIT-67-20251203', 56, 4, NULL, 200, 200, 63.00, NULL, '2026-02-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 67)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(33, 'BATCH-INIT-66-20251203', 57, 3, NULL, 280, 280, 91.00, NULL, '2026-02-21', '2025-11-25 15:45:57', 'available', NULL, NULL, 'Migrated from old products table (ID: 66)', 12, '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(34, 'BATCH-INIT-94-20251203', 57, 5, NULL, 280, 280, 91.00, NULL, '2026-02-23', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 94)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(35, 'BATCH-INIT-60-20251203', 58, 2, NULL, 300, 300, 73.50, NULL, '2025-12-27', '2025-11-25 00:40:19', 'available', NULL, NULL, 'Migrated from old products table (ID: 60)', 4, '2025-11-25 00:40:19', '2025-11-25 00:40:19'),
(36, 'BATCH-INIT-68-20251203', 58, 5, NULL, 150, 150, 56.00, NULL, '2026-02-22', '2025-11-25 15:45:57', 'available', NULL, NULL, 'Migrated from old products table (ID: 68)', 12, '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(37, 'BATCH-INIT-87-20251203', 58, 6, NULL, 250, 250, 56.00, NULL, '2026-02-22', '2025-11-27 03:10:43', 'available', NULL, NULL, 'Migrated from old products table (ID: 87)', 12, '2025-11-27 03:10:43', '2025-11-29 02:46:56'),
(38, 'BATCH-INIT-59-20251203', 59, 2, NULL, 300, 300, 122.50, NULL, '2026-03-20', '2025-11-25 00:05:35', 'available', NULL, NULL, 'Migrated from old products table (ID: 59)', 4, '2025-11-25 00:05:35', '2025-11-25 00:05:35'),
(39, 'BATCH-INIT-71-20251203', 59, 3, NULL, 120, 120, 98.00, NULL, '2026-02-17', '2025-11-25 15:45:57', 'available', NULL, NULL, 'Migrated from old products table (ID: 71)', 12, '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(40, 'BATCH-INIT-73-20251203', 60, 5, NULL, 80, 80, 66.50, NULL, '2026-02-15', '2025-11-25 15:45:57', 'available', NULL, NULL, 'Migrated from old products table (ID: 73)', 12, '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(41, 'BATCH-INIT-72-20251203', 61, 4, NULL, 100, 100, 94.50, NULL, '2026-02-16', '2025-11-25 15:45:57', 'available', NULL, NULL, 'Migrated from old products table (ID: 72)', 12, '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(42, 'BATCH-INIT-85-20251203', 61, 3, NULL, 200, 200, 94.50, NULL, '2025-12-29', '2025-11-27 00:35:53', 'available', NULL, NULL, 'Migrated from old products table (ID: 85)', 13, '2025-11-27 00:35:53', '2025-11-27 00:35:53'),
(43, 'BATCH-INIT-79-20251203', 62, 6, NULL, 350, 350, 147.00, NULL, '2026-02-24', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 79)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(44, 'BATCH-INIT-65-20251203', 63, 5, NULL, 650, 650, 84.00, NULL, '2026-02-20', '2025-11-25 15:45:57', 'available', NULL, NULL, 'Migrated from old products table (ID: 65)', 12, '2025-11-25 15:45:57', '2025-11-27 02:58:38'),
(45, 'BATCH-INIT-93-20251203', 63, 3, NULL, 300, 300, 84.00, NULL, '2026-02-22', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 93)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(46, 'BATCH-INIT-168-20251203', 64, 6, NULL, 140, 140, 126.00, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 168)', 12, '2025-11-27 15:11:09', '2025-11-29 02:51:06'),
(47, 'BATCH-INIT-54-20251203', 65, 5, NULL, 510, 510, 63.00, NULL, '2026-02-17', '2025-11-22 22:12:24', 'available', NULL, NULL, 'Migrated from old products table (ID: 54)', 7, '2025-11-22 22:12:24', '2025-11-27 03:16:16'),
(48, 'BATCH-INIT-69-20251203', 65, 6, NULL, 180, 180, 70.00, NULL, '2026-02-19', '2025-11-25 15:45:57', 'available', NULL, NULL, 'Migrated from old products table (ID: 69)', 12, '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(49, 'BATCH-INIT-118-20251203', 66, 2, NULL, 60, 60, 52.50, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 118)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(50, 'BATCH-INIT-75-20251203', 67, 3, NULL, 400, 400, 52.50, NULL, '2026-02-27', '2025-11-25 15:45:57', 'available', NULL, NULL, 'Migrated from old products table (ID: 75)', 12, '2025-11-25 15:45:57', '2025-11-27 02:48:39'),
(51, 'BATCH-INIT-78-20251203', 68, 5, NULL, 120, 120, 73.50, NULL, '2026-02-20', '2025-11-25 15:45:57', 'available', NULL, NULL, 'Migrated from old products table (ID: 78)', 12, '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(52, 'BATCH-INIT-86-20251203', 68, 2, NULL, 220, 220, 73.50, NULL, '2026-02-20', '2025-11-27 03:06:44', 'available', NULL, NULL, 'Migrated from old products table (ID: 86)', 12, '2025-11-27 03:06:44', '2025-11-29 02:46:56'),
(53, 'BATCH-INIT-81-20251203', 69, 3, NULL, 250, 250, 203.00, NULL, '2026-02-21', '2025-11-25 15:45:57', 'available', NULL, NULL, 'Migrated from old products table (ID: 81)', 12, '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(54, 'BATCH-INIT-56-20251203', 70, 3, NULL, 490, 490, 154.00, NULL, '2026-03-20', '2025-11-22 22:15:49', 'available', NULL, NULL, 'Migrated from old products table (ID: 56)', 5, '2025-11-22 22:15:49', '2025-11-27 00:37:52'),
(55, 'BATCH-INIT-63-20251203', 70, 4, NULL, 550, 550, 154.00, NULL, '2026-02-23', '2025-11-25 15:45:57', 'available', NULL, NULL, 'Migrated from old products table (ID: 63)', 12, '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(56, 'BATCH-INIT-91-20251203', 70, 1, NULL, 550, 550, 154.00, NULL, '2026-02-25', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 91)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(57, 'BATCH-INIT-84-20251203', 71, 6, NULL, 220, 220, 122.50, NULL, '2026-02-17', '2025-11-25 15:45:57', 'available', NULL, NULL, 'Migrated from old products table (ID: 84)', 12, '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(58, 'BATCH-INIT-83-20251203', 72, 5, NULL, 200, 200, 119.00, NULL, '2026-02-18', '2025-11-25 15:45:57', 'available', NULL, NULL, 'Migrated from old products table (ID: 83)', 12, '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(59, 'BATCH-INIT-82-20251203', 73, 4, NULL, 180, 180, 112.00, NULL, '2026-02-19', '2025-11-25 15:45:57', 'available', NULL, NULL, 'Migrated from old products table (ID: 82)', 12, '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(60, 'BATCH-INIT-64-20251203', 74, 5, NULL, 450, 450, 126.00, NULL, '2026-02-18', '2025-11-25 15:45:57', 'available', NULL, NULL, 'Migrated from old products table (ID: 64)', 12, '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(61, 'BATCH-INIT-88-20251203', 74, 2, NULL, 350, 350, 126.00, NULL, '2026-02-18', '2025-11-27 13:31:24', 'available', NULL, NULL, 'Migrated from old products table (ID: 88)', 12, '2025-11-27 13:31:24', '2025-11-27 13:32:22'),
(62, 'BATCH-INIT-92-20251203', 74, 6, NULL, 450, 450, 126.00, NULL, '2026-02-20', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 92)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(63, 'BATCH-INIT-176-20251203', 78, 5, NULL, 25, 25, 126.00, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 176)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(64, 'BATCH-INIT-96-20251203', 80, 2, NULL, 225, 225, 45.50, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 96)', 12, '2025-11-27 15:11:09', '2025-11-29 02:50:48'),
(65, 'BATCH-INIT-102-20251203', 83, 5, NULL, 200, 200, 5.60, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 102)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(66, 'BATCH-INIT-172-20251203', 85, 5, NULL, 30, 30, 105.00, NULL, '2025-12-04', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 172)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(67, 'BATCH-INIT-131-20251203', 86, 5, NULL, 30, 30, 66.50, NULL, '2025-12-04', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 131)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(68, 'BATCH-INIT-166-20251203', 92, 6, NULL, 40, 40, 31.50, NULL, '2027-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 166)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(69, 'BATCH-INIT-153-20251203', 97, 4, NULL, 500, 500, 2.80, NULL, NULL, '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 153)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(70, 'BATCH-INIT-187-20251203', 102, 6, NULL, 15, 15, 196.00, NULL, NULL, '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 187)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(71, 'BATCH-INIT-158-20251203', 103, 6, NULL, 20, 20, 196.00, NULL, '2027-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 158)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(72, 'BATCH-INIT-155-20251203', 106, 5, NULL, 20, 20, 245.00, NULL, '2027-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 155)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(73, 'BATCH-INIT-160-20251203', 108, 3, NULL, 50, 50, 175.00, NULL, NULL, '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 160)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(74, 'BATCH-INIT-152-20251203', 109, 5, NULL, 2000, 2000, 0.35, NULL, NULL, '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 152)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(75, 'BATCH-INIT-162-20251203', 110, 4, NULL, 50, 50, 66.50, NULL, '2025-12-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 162)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(76, 'BATCH-INIT-161-20251203', 112, 5, NULL, 100, 100, 154.00, NULL, '2025-12-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 161)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(77, 'BATCH-INIT-193-20251203', 114, 2, NULL, 100, 100, 105.00, NULL, NULL, '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 193)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(78, 'BATCH-INIT-144-20251203', 119, 6, NULL, 50, 50, 84.00, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 144)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(79, 'BATCH-INIT-142-20251203', 120, 4, NULL, 80, 80, 196.00, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 142)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(80, 'BATCH-INIT-141-20251203', 121, 5, NULL, 100, 100, 175.00, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 141)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(81, 'BATCH-INIT-143-20251203', 122, 1, NULL, 60, 60, 203.00, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 143)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(82, 'BATCH-INIT-128-20251203', 129, 6, NULL, 25, 25, 126.00, NULL, '2026-01-26', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 128)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(83, 'BATCH-INIT-116-20251203', 131, 1, NULL, 40, 40, 66.50, NULL, '2027-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 116)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(84, 'BATCH-INIT-124-20251203', 132, 6, NULL, 45, 45, 38.50, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 124)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(85, 'BATCH-INIT-174-20251203', 136, 1, NULL, 40, 40, 84.00, NULL, '2026-02-25', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 174)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(86, 'BATCH-INIT-107-20251203', 137, 2, NULL, 100, 100, 31.50, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 107)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(87, 'BATCH-INIT-95-20251203', 138, 1, NULL, 150, 150, 168.00, NULL, '2026-02-15', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 95)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(88, 'BATCH-INIT-190-20251203', 139, 4, NULL, 200, 200, 3.50, NULL, NULL, '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 190)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(89, 'BATCH-INIT-156-20251203', 140, 4, NULL, 50, 50, 105.00, NULL, '2027-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 156)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(90, 'BATCH-INIT-140-20251203', 142, 2, NULL, 25, 25, 126.00, NULL, '2025-12-18', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 140)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(91, 'BATCH-INIT-105-20251203', 146, 1, NULL, 50, 50, 59.50, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 105)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(92, 'BATCH-INIT-285-20251203', 149, 2, NULL, 50, 50, 245.00, NULL, NULL, '2025-11-29 02:18:30', 'available', NULL, NULL, 'Migrated from old products table (ID: 285)', 12, '2025-11-29 02:18:30', '2025-12-01 13:41:58'),
(93, 'BATCH-INIT-145-20251203', 150, 2, NULL, 30, 30, 245.00, NULL, '2026-05-26', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 145)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(94, 'BATCH-INIT-100-20251203', 152, 2, NULL, 50, 50, 245.00, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 100)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(95, 'BATCH-INIT-123-20251203', 156, 1, NULL, 40, 40, 45.50, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 123)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(96, 'BATCH-INIT-103-20251203', 157, 5, NULL, 30, 30, 315.00, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 103)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(97, 'BATCH-INIT-192-20251203', 159, 6, NULL, 50, 50, 66.50, NULL, NULL, '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 192)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(98, 'BATCH-INIT-130-20251203', 166, 3, NULL, 20, 20, 84.00, NULL, '2025-12-04', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 130)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(99, 'BATCH-INIT-175-20251203', 167, 6, NULL, 30, 30, 66.50, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 175)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(100, 'BATCH-INIT-173-20251203', 170, 4, NULL, 50, 50, 59.50, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 173)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(101, 'BATCH-INIT-104-20251203', 171, 4, NULL, 25, 25, 364.00, NULL, '2026-05-26', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 104)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(102, 'BATCH-INIT-139-20251203', 177, 6, NULL, 40, 40, 66.50, NULL, '2025-12-11', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 139)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(103, 'BATCH-INIT-119-20251203', 182, 3, NULL, 50, 50, 31.50, NULL, '2028-11-26', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 119)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(104, 'BATCH-INIT-180-20251203', 184, 5, NULL, 200, 200, 31.50, NULL, NULL, '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 180)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(105, 'BATCH-INIT-127-20251203', 188, 1, NULL, 40, 40, 56.00, NULL, '2025-12-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 127)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(106, 'BATCH-INIT-101-20251203', 189, 3, NULL, 100, 100, 59.50, NULL, '2026-05-26', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 101)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(107, 'BATCH-INIT-112-20251203', 191, 1, NULL, 10, 10, 1155.00, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 112)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(108, 'BATCH-INIT-134-20251203', 193, 1, NULL, 300, 300, 3.50, NULL, '2025-11-30', '2025-11-27 15:11:09', 'expired', NULL, NULL, 'Migrated from old products table (ID: 134)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(109, 'BATCH-INIT-167-20251203', 194, 2, NULL, 30, 30, 105.00, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 167)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(110, 'BATCH-INIT-150-20251203', 195, 2, NULL, 400, 400, 3.50, NULL, NULL, '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 150)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(111, 'BATCH-INIT-149-20251203', 196, 6, NULL, 600, 600, 2.10, NULL, NULL, '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 149)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(112, 'BATCH-INIT-181-20251203', 199, 4, NULL, 100, 100, 45.50, NULL, NULL, '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 181)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(113, 'BATCH-INIT-117-20251203', 200, 6, NULL, 30, 30, 84.00, NULL, '2027-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 117)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(114, 'BATCH-INIT-183-20251203', 201, 6, NULL, 30, 30, 126.00, NULL, NULL, '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 183)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(115, 'BATCH-INIT-151-20251203', 213, 3, NULL, 1000, 1000, 3.50, NULL, NULL, '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 151)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(116, 'BATCH-INIT-129-20251203', 216, 2, NULL, 60, 60, 38.50, NULL, '2025-12-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 129)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(117, 'BATCH-INIT-98-20251203', 221, 1, NULL, 150, 150, 45.50, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 98)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(118, 'BATCH-INIT-114-20251203', 225, 5, NULL, 100, 100, 17.50, NULL, '2028-11-26', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 114)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(119, 'BATCH-INIT-133-20251203', 228, 4, NULL, 50, 50, 45.50, NULL, '2025-12-04', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 133)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(120, 'BATCH-INIT-113-20251203', 232, 6, NULL, 20, 20, 84.00, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 113)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(121, 'BATCH-INIT-171-20251203', 234, 6, NULL, 35, 35, 115.50, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 171)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(122, 'BATCH-INIT-106-20251203', 235, 6, NULL, 20, 20, 196.00, NULL, '2027-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 106)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(123, 'BATCH-INIT-185-20251203', 237, 4, NULL, 15, 15, 84.00, NULL, NULL, '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 185)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(124, 'BATCH-INIT-97-20251203', 242, 4, NULL, 180, 180, 45.50, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 97)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(125, 'BATCH-INIT-188-20251203', 246, 2, NULL, 10, 10, 126.00, NULL, NULL, '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 188)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(126, 'BATCH-INIT-178-20251203', 249, 1, NULL, 30, 30, 59.50, NULL, '2026-05-26', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 178)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(127, 'BATCH-INIT-109-20251203', 250, 5, NULL, 35, 35, 105.00, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 109)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(128, 'BATCH-INIT-148-20251203', 251, 1, NULL, 300, 300, 12.60, NULL, NULL, '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 148)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(129, 'BATCH-INIT-147-20251203', 252, 4, NULL, 400, 400, 8.40, NULL, NULL, '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 147)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(130, 'BATCH-INIT-146-20251203', 253, 5, NULL, 500, 500, 5.60, NULL, NULL, '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 146)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(131, 'BATCH-INIT-186-20251203', 258, 1, NULL, 10, 10, 245.00, NULL, NULL, '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 186)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(132, 'BATCH-INIT-182-20251203', 262, 1, NULL, 80, 80, 59.50, NULL, NULL, '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 182)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(133, 'BATCH-INIT-184-20251203', 265, 5, NULL, 20, 20, 105.00, NULL, NULL, '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 184)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(134, 'BATCH-INIT-135-20251203', 266, 6, NULL, 100, 100, 8.40, NULL, '2025-12-11', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 135)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(135, 'BATCH-INIT-159-20251203', 267, 2, NULL, 100, 100, 59.50, NULL, NULL, '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 159)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(136, 'BATCH-INIT-110-20251203', 269, 5, NULL, 15, 15, 1295.00, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 110)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(137, 'BATCH-INIT-111-20251203', 270, 4, NULL, 30, 30, 336.00, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 111)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(138, 'BATCH-INIT-122-20251203', 278, 4, NULL, 30, 30, 406.00, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 122)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(139, 'BATCH-INIT-121-20251203', 279, 5, NULL, 50, 50, 1015.00, NULL, '2026-11-27', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 121)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09'),
(140, 'BATCH-INIT-55-20251203', 281, 4, NULL, 300, 300, 175.00, NULL, '2026-01-05', '2025-11-22 22:14:39', 'available', NULL, NULL, 'Migrated from old products table (ID: 55)', 6, '2025-11-22 22:14:39', '2025-11-22 22:14:39'),
(141, 'BATCH-INIT-61-20251203', 281, 2, NULL, 400, 400, 175.00, NULL, '2026-03-10', '2025-11-25 15:45:57', 'available', NULL, NULL, 'Migrated from old products table (ID: 61)', 12, '2025-11-25 15:45:57', '2025-11-26 15:23:40'),
(142, 'BATCH-INIT-89-20251203', 281, 5, NULL, 500, 500, 175.00, NULL, '2026-03-12', '2025-11-27 15:11:09', 'available', NULL, NULL, 'Migrated from old products table (ID: 89)', 12, '2025-11-27 15:11:09', '2025-11-27 15:11:09');

-- --------------------------------------------------------

--
-- Table structure for table `stock_transactions`
--

CREATE TABLE `stock_transactions` (
  `id` int(10) UNSIGNED NOT NULL,
  `transaction_number` varchar(50) NOT NULL,
  `batch_id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED NOT NULL,
  `transaction_type` enum('stock_in','stock_out','transfer','adjustment','return','waste','expired') NOT NULL,
  `quantity` int(11) NOT NULL COMMENT 'Positive for stock_in, negative for stock_out',
  `unit_cost` decimal(10,2) DEFAULT NULL,
  `reference_type` varchar(50) DEFAULT NULL COMMENT 'delivery, sale, transfer, adjustment, waste, expired',
  `reference_id` int(10) UNSIGNED DEFAULT NULL,
  `batch_qty_before` int(10) UNSIGNED NOT NULL,
  `batch_qty_after` int(10) UNSIGNED NOT NULL,
  `branch_total_before` int(10) UNSIGNED DEFAULT NULL,
  `branch_total_after` int(10) UNSIGNED DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `approved_by` int(10) UNSIGNED DEFAULT NULL,
  `transaction_date` datetime NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Normalized stock transactions with batch-level tracking';

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `payment_terms` varchar(100) DEFAULT NULL COMMENT 'e.g., Net 30, COD, etc.',
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Supplier records with contact details and terms';

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact_person`, `email`, `phone`, `address`, `payment_terms`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Magnolia Chicken', 'Sales Department', 'sales@magnolia.com.ph', '(02) 8123-4567', 'Magnolia Avenue, Quezon City, Philippines', 'Net 30', 'active', '2025-11-22 21:55:57', '2025-11-27 15:23:57'),
(2, 'Ana\'s Breeders Farms Inc', 'Farm Manager', 'info@anasbreeders.com.ph', '(049) 501-2345', 'Tagaytay, Cavite, Philippines', 'COD', 'active', '2025-11-22 21:55:57', '2025-11-27 15:23:57'),
(3, 'Premium Feeds Corporation', 'Sales Officer', 'sales@premiumfeeds.com.ph', '(02) 8456-7890', 'Makati City, Philippines', 'Net 15', 'active', '2025-11-22 21:55:57', '2025-11-27 15:23:57'),
(4, 'San Miguel Foods', 'Corporate Sales', 'corporate@sanmiguelfoods.com.ph', '(02) 8888-0000', 'Mandaluyong City, Philippines', 'Net 30', 'active', '2025-11-22 21:55:57', '2025-11-27 15:23:57'),
(5, 'CDO Foodsphere Inc.', 'Sales Department', 'sales@cdofoodsphere.com.ph', '(02) 8123-5678', 'Quezon City, Philippines', 'Net 30', 'active', '2025-11-22 21:55:57', '2025-11-27 15:23:57'),
(6, 'Excellence Poultry and Livestock Specialist Inc.', 'Operations Manager', 'info@excellencepoultry.com.ph', '(049) 502-3456', 'Cavite, Philippines', 'COD', 'active', '2025-11-22 21:55:57', '2025-11-27 15:23:57'),
(7, 'Rare Global Food Trading Corp.', 'Trading Manager', 'trading@rareglobal.com.ph', '(02) 8234-5678', 'Pasig City, Philippines', 'Net 15', 'active', '2025-11-22 21:55:57', '2025-11-27 15:23:57'),
(8, 'E&L Faster Food Imports Inc.', 'Import Manager', 'imports@elfaster.com.ph', '(02) 8345-6789', 'Port Area, Manila, Philippines', 'Net 45', 'active', '2025-11-22 21:55:57', '2025-11-27 15:23:57'),
(9, 'Foster Foods Inc', 'Sales Coordinator', 'sales@fosterfoods.com.ph', '(02) 8456-7890', 'Taguig City, Philippines', 'Net 30', 'active', '2025-11-22 21:55:57', '2025-11-27 15:23:57'),
(10, 'Pilmico', 'Corporate Sales', 'corporate@pilmico.com.ph', '(02) 8567-8901', 'Laguna, Philippines', 'Net 30', 'active', '2025-11-22 21:55:57', '2025-11-27 15:23:57'),
(11, 'Consistent Frozen Solutions Corp.', 'Sales Manager', 'sales@consistentfrozen.com.ph', '(02) 8678-9012', 'Cavite, Philippines', 'Net 15', 'active', '2025-11-22 21:55:57', '2025-11-27 15:23:57'),
(12, 'EcoSci Food', 'Business Development', 'bd@ecoscifood.com.ph', '(02) 8789-0123', 'Quezon City, Philippines', 'COD', 'active', '2025-11-22 21:55:57', '2025-11-27 15:23:57'),
(13, 'Advance Protein Inc.', 'Sales Officer', 'sales@advanceprotein.com.ph', '(02) 8890-1234', 'Bulacan, Philippines', 'Net 30', 'active', '2025-11-22 21:55:57', '2025-11-27 15:23:57'),
(14, 'Art Inc.', 'Account Manager', 'accounts@artinc.com.ph', '(02) 8901-2345', 'Manila, Philippines', 'Net 15', 'active', '2025-11-22 21:55:57', '2025-11-27 15:23:57'),
(15, 'Clarc Feedmill Inc.', 'Production Manager', 'production@clarcfeedmill.com.ph', '(049) 503-4567', 'Cavite, Philippines', 'COD', 'active', '2025-11-22 21:55:57', '2025-11-27 15:23:57'),
(16, 'Kai Anya Foods Intl Corp', 'International Sales', 'sales@kaianya.com.ph', '(02) 8012-3456', 'Makati City, Philippines', 'Net 30', 'active', '2025-11-22 21:55:57', '2025-11-27 15:23:57'),
(17, 'Hightower Incorporated', 'Sales Department', 'sales@hightower.com.ph', '(02) 8123-4567', 'Pasig City, Philippines', 'Net 30', 'active', '2025-11-22 21:55:57', '2025-11-27 15:23:57'),
(18, 'The Original Savory Escolta - Online', 'Online Manager', 'online@savoryescolta.com.ph', '(02) 8234-5678', 'Escolta, Manila, Philippines', 'COD', 'active', '2025-11-22 21:55:57', '2025-11-27 15:23:57'),
(19, 'Fresco PH', 'Sales Team', 'sales@fresco.com.ph', '(02) 8345-6789', 'Quezon City, Philippines', 'Net 15', 'active', '2025-11-22 21:55:57', '2025-11-27 15:23:57'),
(20, 'Coca-Cola Beverages Philippines Inc.', 'Corporate Sales', 'corporate@coca-cola.com.ph', '(02) 8888-1111', 'Makati City, Philippines', 'Net 30', 'active', '2025-11-27 15:23:23', '2025-11-27 15:23:57'),
(21, 'Pepsi-Cola Products Philippines Inc.', 'Sales Manager', 'sales@pepsi.com.ph', '(02) 8888-2222', 'Mandaluyong City, Philippines', 'Net 30', 'active', '2025-11-27 15:23:23', '2025-11-27 15:23:57'),
(22, 'Universal Robina Corporation (URC)', 'Beverage Division', 'beverages@urc.com.ph', '(02) 8888-3333', 'Pasig City, Philippines', 'Net 30', 'active', '2025-11-27 15:23:23', '2025-11-27 15:23:57'),
(23, 'Nestlé Philippines Inc.', 'Beverage Sales', 'beverages@nestle.com.ph', '(02) 8888-4444', 'Makati City, Philippines', 'Net 30', 'active', '2025-11-27 15:23:23', '2025-11-27 15:23:23'),
(24, 'Heinz Philippines Inc.', 'Sales Team', 'sales@heinz.com.ph', '(02) 8888-5555', 'Makati City, Philippines', 'Net 30', 'active', '2025-11-27 15:23:23', '2025-11-27 15:23:57'),
(25, 'Datu Puti Food Products', 'Sales Department', 'sales@dataputi.com.ph', '(02) 8888-6666', 'Quezon City, Philippines', 'Net 30', 'active', '2025-11-27 15:23:23', '2025-11-27 15:23:57'),
(26, 'Bunge Philippines Inc.', 'Oil Division', 'oils@bunge.com.ph', '(02) 8888-7777', 'Makati City, Philippines', 'Net 30', 'active', '2025-11-27 15:23:23', '2025-11-27 15:23:57'),
(27, 'Cargill Philippines Inc.', 'Cooking Oil Sales', 'oils@cargill.com.ph', '(02) 8888-8888', 'Taguig City, Philippines', 'Net 30', 'active', '2025-11-27 15:23:23', '2025-11-27 15:23:57'),
(28, 'Purefoods-Hormel Company Inc.', 'Oil Products', 'oils@purefoods.com.ph', '(02) 8888-9999', 'Makati City, Philippines', 'Net 30', 'active', '2025-11-27 15:23:23', '2025-11-27 15:23:57'),
(29, 'McCormick Philippines Inc.', 'Sales Department', 'sales@mccormick.com.ph', '(02) 8888-1010', 'Makati City, Philippines', 'Net 30', 'active', '2025-11-27 15:23:23', '2025-11-27 15:23:57'),
(30, 'Knorr Philippines', 'Seasoning Sales', 'sales@knorr.com.ph', '(02) 8888-2020', 'Makati City, Philippines', 'Net 30', 'active', '2025-11-27 15:23:23', '2025-11-27 15:23:57'),
(31, 'Ajinomoto Philippines Corporation', 'Sales Team', 'sales@ajinomoto.com.ph', '(02) 8888-3030', 'Makati City, Philippines', 'Net 30', 'active', '2025-11-27 15:23:23', '2025-11-27 15:23:57'),
(32, 'National Food Authority (NFA)', 'Rice Distribution', 'distribution@nfa.gov.ph', '(02) 8888-4040', 'Quezon City, Philippines', 'Net 15', 'active', '2025-11-27 15:23:23', '2025-11-27 15:23:57'),
(33, 'Doña Maria Rice Trading', 'Sales Manager', 'sales@donamaria.com.ph', '(02) 8888-5050', 'Manila, Philippines', 'Net 15', 'active', '2025-11-27 15:23:23', '2025-11-27 15:23:57'),
(34, 'Golden Grains Trading Corp.', 'Trading Manager', 'sales@goldengrains.com.ph', '(02) 8888-6060', 'Pasig City, Philippines', 'Net 15', 'active', '2025-11-27 15:23:23', '2025-11-27 15:23:57'),
(35, 'Farm Fresh Produce Trading', 'Sales Manager', 'sales@farmfresh.com.ph', '(02) 8888-7070', 'Laguna, Philippines', 'COD', 'active', '2025-11-27 15:23:23', '2025-11-27 15:23:57'),
(36, 'Benguet Vegetable Trading', 'Trading Manager', 'sales@benguetveg.com.ph', '(074) 444-1234', 'Benguet, Philippines', 'Net 7', 'active', '2025-11-27 15:23:23', '2025-11-27 15:23:57'),
(37, 'Gardenia Bakeries Philippines Inc.', 'Sales Department', 'sales@gardenia.com.ph', '(02) 8888-8080', 'Laguna, Philippines', 'Net 30', 'active', '2025-11-27 15:23:23', '2025-11-27 15:23:57'),
(38, 'Rebisco Biscuit Corporation', 'Bakery Sales', 'bakery@rebisco.com.ph', '(02) 8888-9090', 'Quezon City, Philippines', 'Net 30', 'active', '2025-11-27 15:23:23', '2025-11-27 15:23:57'),
(39, 'Local Bakery Suppliers Cooperative', 'Coordinator', 'info@localbakery.com.ph', '(02) 8888-0101', 'Manila, Philippines', 'COD', 'active', '2025-11-27 15:23:23', '2025-11-27 15:23:57'),
(40, 'Nestlé Philippines Inc. (Dairy)', 'Dairy Division', 'dairy@nestle.com.ph', '(02) 8888-1111', 'Makati City, Philippines', 'Net 30', 'active', '2025-11-27 15:23:23', '2025-11-27 15:23:57'),
(42, 'Magnolia Dairy Products', 'Sales Department', 'dairy@magnolia.com.ph', '(02) 8888-1212', 'Quezon City, Philippines', 'Net 30', 'active', '2025-11-27 15:23:57', '2025-11-27 15:23:57'),
(43, 'Alaska Milk Corporation', 'Sales Team', 'sales@alaska.com.ph', '(02) 8888-1313', 'Makati City, Philippines', 'Net 30', 'active', '2025-11-27 15:23:57', '2025-11-27 15:23:57'),
(44, 'Arctic Foods Distribution', 'Sales Manager', 'sales@arcticfoods.com.ph', '(02) 8888-1414', 'Pasig City, Philippines', 'Net 15', 'active', '2025-11-27 15:23:57', '2025-11-27 15:23:57'),
(45, 'Packaging Solutions Philippines Inc.', 'Sales Department', 'sales@packagingsolutions.com.ph', '(02) 8888-1515', 'Quezon City, Philippines', 'Net 30', 'active', '2025-11-27 15:23:57', '2025-11-27 15:23:57'),
(46, 'Eco-Pack Manufacturing Corp.', 'Sales Manager', 'sales@ecopack.com.ph', '(02) 8888-1616', 'Laguna, Philippines', 'Net 30', 'active', '2025-11-27 15:23:57', '2025-11-27 15:23:57'),
(47, 'Takeout Packaging Supplies', 'Sales Team', 'sales@takeoutpack.com.ph', '(02) 8888-1717', 'Manila, Philippines', 'Net 15', 'active', '2025-11-27 15:23:57', '2025-11-27 15:23:57'),
(48, 'Procter & Gamble Philippines', 'Commercial Sales', 'commercial@p&g.com.ph', '(02) 8888-1818', 'Makati City, Philippines', 'Net 30', 'active', '2025-11-27 15:23:57', '2025-11-27 15:23:57'),
(49, 'Unilever Philippines Inc.', 'Commercial Division', 'commercial@unilever.com.ph', '(02) 8888-1919', 'Makati City, Philippines', 'Net 30', 'active', '2025-11-27 15:23:57', '2025-11-27 15:23:57'),
(50, 'Industrial Cleaning Supplies Corp.', 'Sales Manager', 'sales@industrialcleaning.com.ph', '(02) 8888-2020', 'Pasig City, Philippines', 'Net 30', 'active', '2025-11-27 15:23:57', '2025-11-27 15:23:57'),
(51, 'Fresh Eggs Distribution Co.', 'Sales Manager', 'sales@fresheggs.com.ph', '(02) 8888-2121', 'Bulacan, Philippines', 'COD', 'active', '2025-11-27 15:23:57', '2025-11-27 15:23:57'),
(52, 'Poultry Egg Suppliers Association', 'Coordinator', 'info@poultryeggs.com.ph', '(02) 8888-2222', 'Laguna, Philippines', 'Net 7', 'active', '2025-11-27 15:23:57', '2025-11-27 15:23:57'),
(53, 'General Milling Corporation', 'Sales Department', 'sales@gmc.com.ph', '(02) 8888-2323', 'Makati City, Philippines', 'Net 30', 'active', '2025-11-27 15:23:57', '2025-11-27 15:23:57'),
(54, 'Bread Flour Specialists Inc.', 'Sales Manager', 'sales@breadflour.com.ph', '(02) 8888-2424', 'Quezon City, Philippines', 'Net 30', 'active', '2025-11-27 15:23:57', '2025-11-27 15:23:57'),
(55, 'Flavor Masters Food Products', 'Sales Team', 'sales@flavormasters.com.ph', '(02) 8888-2525', 'Makati City, Philippines', 'Net 30', 'active', '2025-11-27 15:23:57', '2025-11-27 15:23:57'),
(56, 'Marinade Specialists Philippines', 'Sales Manager', 'sales@marinadespecialists.com.ph', '(02) 8888-2626', 'Pasig City, Philippines', 'Net 15', 'active', '2025-11-27 15:23:57', '2025-11-27 15:23:57'),
(57, 'Ready-to-Serve Foods Inc.', 'Sales Department', 'sales@readyfoods.com.ph', '(02) 8888-2727', 'Quezon City, Philippines', 'Net 15', 'active', '2025-11-27 15:23:57', '2025-11-27 15:23:57'),
(58, 'Prepared Foods Distribution', 'Sales Manager', 'sales@preparedfoods.com.ph', '(02) 8888-2828', 'Manila, Philippines', 'Net 15', 'active', '2025-11-27 15:23:57', '2025-11-27 15:23:57'),
(59, 'Sweet Treats Philippines Inc.', 'Sales Team', 'sales@sweettreats.com.ph', '(02) 8888-2929', 'Makati City, Philippines', 'Net 30', 'active', '2025-11-27 15:23:57', '2025-11-27 15:23:57'),
(60, 'Ice Cream & Dessert Suppliers', 'Sales Manager', 'sales@icecreamdesserts.com.ph', '(02) 8888-3030', 'Quezon City, Philippines', 'Net 30', 'active', '2025-11-27 15:23:57', '2025-11-27 15:23:57'),
(61, 'Paper Products Manufacturing Corp.', 'Sales Department', 'sales@paperproducts.com.ph', '(02) 8888-3131', 'Laguna, Philippines', 'Net 30', 'active', '2025-11-27 15:23:57', '2025-11-27 15:23:57'),
(62, 'Tissue & Paper Supplies Inc.', 'Sales Manager', 'sales@tissuepaper.com.ph', '(02) 8888-3232', 'Manila, Philippines', 'Net 30', 'active', '2025-11-27 15:23:57', '2025-11-27 15:23:57'),
(63, 'Commercial Kitchen Equipment Inc.', 'Sales Department', 'sales@kitchenequipment.com.ph', '(02) 8888-3333', 'Makati City, Philippines', 'Net 30', 'active', '2025-11-27 15:23:57', '2025-11-27 15:23:57'),
(64, 'Restaurant Supply Solutions', 'Sales Manager', 'sales@restaurantsupply.com.ph', '(02) 8888-3434', 'Pasig City, Philippines', 'Net 30', 'active', '2025-11-27 15:23:57', '2025-11-27 15:23:57'),
(65, 'Uniform Solutions Philippines', 'Sales Team', 'sales@uniformsolutions.com.ph', '(02) 8888-3535', 'Quezon City, Philippines', 'Net 30', 'active', '2025-11-27 15:23:57', '2025-11-27 15:23:57'),
(66, 'Professional Apparel Manufacturing', 'Sales Manager', 'sales@proapparel.com.ph', '(02) 8888-3636', 'Manila, Philippines', 'Net 30', 'active', '2025-11-27 15:23:57', '2025-11-27 15:23:57');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_performance_metrics`
--

CREATE TABLE `supplier_performance_metrics` (
  `id` int(10) UNSIGNED NOT NULL,
  `supplier_id` int(10) UNSIGNED NOT NULL,
  `period_year` int(4) NOT NULL,
  `period_month` int(2) NOT NULL,
  `total_deliveries` int(11) NOT NULL DEFAULT 0,
  `on_time_deliveries` int(11) NOT NULL DEFAULT 0,
  `late_deliveries` int(11) NOT NULL DEFAULT 0,
  `avg_delay_days` decimal(5,2) NOT NULL DEFAULT 0.00,
  `total_items_delivered` int(11) NOT NULL DEFAULT 0,
  `rejected_items` int(11) NOT NULL DEFAULT 0,
  `quality_issues_count` int(11) NOT NULL DEFAULT 0,
  `total_purchase_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `avg_order_value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `delivery_score` decimal(5,2) DEFAULT NULL COMMENT '0-100 score based on on-time delivery',
  `quality_score` decimal(5,2) DEFAULT NULL COMMENT '0-100 score based on quality',
  `overall_score` decimal(5,2) DEFAULT NULL COMMENT 'Weighted average of all scores',
  `calculated_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Supplier performance tracking and scoring';

-- --------------------------------------------------------

--
-- Table structure for table `supply_allocations`
--

CREATE TABLE `supply_allocations` (
  `id` int(11) UNSIGNED NOT NULL,
  `branch_id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `allocated_qty` int(11) NOT NULL DEFAULT 0,
  `unit_price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','approved','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `allocation_date` date DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(11) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supply_allocations`
--

INSERT INTO `supply_allocations` (`id`, `branch_id`, `product_id`, `allocated_qty`, `unit_price`, `total_amount`, `status`, `allocation_date`, `delivery_date`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 7, 55, 466, 250.00, 116500.00, 'pending', '2025-11-08', '2025-12-10', 'Initial supply allocation for SM City Cebu Branch - 1 of 2', 9, '2025-11-08 09:53:46', '2025-11-08 09:53:46'),
(2, 7, 61, 488, 250.00, 122000.00, 'approved', '2025-11-09', '2025-12-13', 'Initial supply allocation for SM City Cebu Branch - 2 of 2', 9, '2025-11-09 09:53:46', '2025-11-09 09:53:46'),
(3, 8, 53, 402, 200.00, 80400.00, 'approved', '2025-11-11', '2025-12-10', 'Initial supply allocation for SM Megamall Branch - 1 of 2', 9, '2025-11-11 09:53:46', '2025-11-11 09:53:46'),
(4, 8, 68, 330, 80.00, 26400.00, 'shipped', '2025-11-12', '2025-12-13', 'Initial supply allocation for SM Megamall Branch - 2 of 2', 9, '2025-11-12 09:53:46', '2025-11-12 09:53:46'),
(5, 9, 67, 397, 90.00, 35730.00, 'shipped', '2025-11-14', '2025-12-10', 'Initial supply allocation for Robinsons Place Iloilo Branch - 1 of 2', 9, '2025-11-14 09:53:46', '2025-11-14 09:53:46'),
(6, 9, 52, 492, 200.00, 98400.00, 'delivered', '2025-11-15', '2025-12-13', 'Initial supply allocation for Robinsons Place Iloilo Branch - 2 of 2', 9, '2025-11-15 09:53:46', '2025-11-15 09:53:46'),
(7, 10, 55, 430, 250.00, 107500.00, 'delivered', '2025-11-17', '2025-12-10', 'Initial supply allocation for Centrio Mall Branch - 1 of 3', 9, '2025-11-17 09:53:46', '2025-11-17 09:53:46'),
(8, 10, 59, 261, 175.00, 45675.00, 'pending', '2025-11-18', '2025-12-13', 'Initial supply allocation for Centrio Mall Branch - 2 of 3', 9, '2025-11-18 09:53:46', '2025-11-18 09:53:46'),
(9, 10, 52, 358, 200.00, 71600.00, 'approved', '2025-11-19', '2025-12-16', 'Initial supply allocation for Centrio Mall Branch - 3 of 3', 9, '2025-11-19 09:53:46', '2025-11-19 09:53:46'),
(10, 11, 53, 141, 200.00, 28200.00, 'pending', '2025-11-20', '2025-12-10', 'Initial supply allocation for Session Road Branch - 1 of 2', 9, '2025-11-20 09:53:46', '2025-11-20 09:53:46'),
(11, 11, 64, 149, 180.00, 26820.00, 'approved', '2025-11-21', '2025-12-13', 'Initial supply allocation for Session Road Branch - 2 of 2', 9, '2025-11-21 09:53:46', '2025-11-21 09:53:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('superadmin','central_admin','branch_manager','staff','franchise_manager','logistics_coordinator','inventory_staff') NOT NULL DEFAULT 'branch_manager',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `reset_otp` varchar(255) DEFAULT NULL,
  `otp_expires` datetime DEFAULT NULL,
  `reset_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Application users';

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `branch_id`, `email`, `password`, `role`, `created_at`, `updated_at`, `reset_otp`, `otp_expires`, `reset_expires`) VALUES
(1, NULL, 'superadmin1@chakanoks.test', '$2y$10$gqWg8q30wEM7PTiior3zT.EdYJty93GtLEQQ/027I3J6ssIArt/fy', 'superadmin', NULL, NULL, NULL, NULL, NULL),
(2, 1, 'mansuetomarky@gmail.com', '$2y$10$g1UjHDsOe2hLXsvyGXKTxuxkPlgkFt4jHNWIJsc4TYTTZ9oAE4eUi', 'central_admin', NULL, NULL, NULL, NULL, NULL),
(3, 2, 'rualesabigail09@gmail.com', '$2y$10$0f2TzYrQ2atjT7H80Z/MruzBdRL4Tx3PaC7zHnStuF8Og2gqvIMKy', 'branch_manager', NULL, NULL, NULL, NULL, NULL),
(4, 2, 'matina.manager@chakanoks.test', '$2y$10$70yi53r0xC5s7oJcqOz4aeQ2hP/sujY2Kzo3PzqKnpcUFaBegrPqe', 'branch_manager', NULL, NULL, NULL, NULL, NULL),
(5, 3, 'toril.manager@chakanoks.test', '$2y$10$GfUZ92ahWuX/ozlI9xznmucok5.3C7ifnMLVHWDyk4SBnvqxOTgHO', 'branch_manager', NULL, NULL, NULL, NULL, NULL),
(6, 4, 'buhangin.manager@chakanoks.test', '$2y$10$aaKRlp1KfV3IgIiF09nQBeuYjmQO2qJR9wvEQ4/PeuGKgIRmzkOcy', 'branch_manager', NULL, NULL, NULL, NULL, NULL),
(7, 5, 'agdao.manager@chakanoks.test', '$2y$10$YbAh.zCMvth4jVDiIU6piO4MPDyH2.d2r1Y7V2Dlx4AUbStCkFn2u', 'branch_manager', NULL, NULL, NULL, NULL, NULL),
(8, 6, 'lanang.manager@chakanoks.test', '$2y$10$4wzAqdl/5XN6oOjRoL4HT.SShUsRlWWcmD5SXzkvf33aj/5uu0IyS', 'branch_manager', NULL, NULL, NULL, NULL, NULL),
(9, 4, 'imonakoplss@gmail.com', '$2y$10$VEGImgsPqqqnIrLCKTyFb.sH4KaMrTQW.Dd6GAshdMmJaZ987AJji', 'franchise_manager', NULL, NULL, NULL, NULL, NULL),
(11, 6, 'gpalagpalag@gmail.com', '$2y$10$0F.WdPUd7jN0VeyKVL1mQeQt2eivBlj9FnsczspjCdewNryQSg2xO', 'logistics_coordinator', NULL, NULL, NULL, NULL, NULL),
(12, 2, 'matina.inventory@chakanoks.test', '$2y$10$7vomot3tmjnu6wK7G7Zgs.iE.6cG9txDNkrIZSVng2fW2LqHOe1wG', 'inventory_staff', NULL, NULL, NULL, NULL, NULL),
(13, 3, 'toril.inventory@chakanoks.test', '$2y$10$K0roKmNK/84ZcjjfN0yJkOJZ9ENENAKA9oiiBQJ12DFtin4zzWqnq', 'inventory_staff', NULL, NULL, NULL, NULL, NULL),
(14, 4, 'buhangin.inventory@chakanoks.test', '$2y$10$A9fWl5lcyWl9A1K/Wt6zW.VfS7g/4YoUJER/32P7NsvafSRtf.vBy', 'inventory_staff', NULL, NULL, NULL, NULL, NULL),
(15, 5, 'agdao.inventory@chakanoks.test', '$2y$10$iC2BAjS4ukmQ9a.L6DlPqOaVnzXDd5GZy.AI0HDse81xyuqpvEo4y', 'inventory_staff', NULL, NULL, NULL, NULL, NULL),
(16, 6, 'lanang.inventory@chakanoks.test', '$2y$10$NiBxn3qFxyiEWP4kytdsdeSrqiBj/bAAokrv33ixs5FO1rWk2AfMW', 'inventory_staff', NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts_payable`
--
ALTER TABLE `accounts_payable`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_accounts_payable_created_by` (`created_by`),
  ADD KEY `purchase_order_id` (`purchase_order_id`),
  ADD KEY `payment_status` (`payment_status`),
  ADD KEY `due_date` (`due_date`);

--
-- Indexes for table `audit_trail`
--
ALTER TABLE `audit_trail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `table_name_record_id` (`table_name`,`record_id`),
  ADD KEY `changed_by` (`changed_by`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `action` (`action`);

--
-- Indexes for table `auth_logs`
--
ALTER TABLE `auth_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_type` (`event_type`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `delivery_number` (`delivery_number`),
  ADD KEY `fk_deliveries_received_by` (`received_by`),
  ADD KEY `purchase_order_id` (`purchase_order_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `status` (`status`),
  ADD KEY `fk_deliveries_scheduled_by` (`scheduled_by`);

--
-- Indexes for table `delivery_items`
--
ALTER TABLE `delivery_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_id` (`delivery_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `franchise_applications`
--
ALTER TABLE `franchise_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`),
  ADD KEY `idx_franchise_applications_email` (`email`),
  ADD KEY `idx_franchise_applications_reviewed_by` (`reviewed_by`);

--
-- Indexes for table `franchise_owners`
--
ALTER TABLE `franchise_owners`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id_is_used_reset_expires` (`user_id`,`is_used`,`reset_expires`);

--
-- Indexes for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_number` (`payment_number`),
  ADD KEY `fk_payment_trans_paid_by` (`paid_by`),
  ADD KEY `fk_payment_trans_recorded_by` (`recorded_by`),
  ADD KEY `accounts_payable_id` (`accounts_payable_id`),
  ADD KEY `payment_date` (`payment_date`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_products_created_by` (`created_by`),
  ADD KEY `fk_products_branch_id` (`branch_id`);

--
-- Indexes for table `product_catalog`
--
ALTER TABLE `product_catalog`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `fk_product_catalog_created_by` (`created_by`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `fk_purchase_orders_approved_by` (`approved_by`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `status` (`status`),
  ADD KEY `purchase_request_id` (`purchase_request_id`);

--
-- Indexes for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_id` (`purchase_order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `request_number` (`request_number`),
  ADD KEY `fk_purchase_requests_requested_by` (`requested_by`),
  ADD KEY `fk_purchase_requests_approved_by` (`approved_by`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `purchase_request_items`
--
ALTER TABLE `purchase_request_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_request_id` (`purchase_request_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `purchase_request_quotations`
--
ALTER TABLE `purchase_request_quotations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_request_supplier` (`purchase_request_id`,`supplier_id`),
  ADD KEY `fk_quotations_supplier` (`supplier_id`),
  ADD KEY `fk_quotations_quoted_by` (`quoted_by`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `royalty_payments`
--
ALTER TABLE `royalty_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `status` (`status`),
  ADD KEY `period_year_period_month` (`period_year`,`period_month`),
  ADD KEY `idx_royalty_payments_due_date` (`due_date`),
  ADD KEY `idx_royalty_payments_paid_date` (`paid_date`);

--
-- Indexes for table `stock_batches`
--
ALTER TABLE `stock_batches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `batch_number` (`batch_number`),
  ADD KEY `fk_stock_batches_branch` (`branch_id`),
  ADD KEY `fk_stock_batches_supplier` (`supplier_id`),
  ADD KEY `fk_stock_batches_po` (`purchase_order_id`),
  ADD KEY `fk_stock_batches_delivery` (`delivery_id`),
  ADD KEY `fk_stock_batches_created_by` (`created_by`),
  ADD KEY `product_id_branch_id` (`product_id`,`branch_id`),
  ADD KEY `expiry_date_status` (`expiry_date`,`status`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `stock_transactions`
--
ALTER TABLE `stock_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_number` (`transaction_number`),
  ADD KEY `fk_stock_trans_branch` (`branch_id`),
  ADD KEY `fk_stock_trans_created_by` (`created_by`),
  ADD KEY `fk_stock_trans_approved_by` (`approved_by`),
  ADD KEY `batch_id` (`batch_id`),
  ADD KEY `product_id_branch_id` (`product_id`,`branch_id`),
  ADD KEY `transaction_type` (`transaction_type`),
  ADD KEY `transaction_date` (`transaction_date`),
  ADD KEY `reference_type_reference_id` (`reference_type`,`reference_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `supplier_performance_metrics`
--
ALTER TABLE `supplier_performance_metrics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_supplier_period` (`supplier_id`,`period_year`,`period_month`),
  ADD KEY `period_year_period_month` (`period_year`,`period_month`),
  ADD KEY `overall_score` (`overall_score`);

--
-- Indexes for table `supply_allocations`
--
ALTER TABLE `supply_allocations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `status` (`status`),
  ADD KEY `idx_supply_allocations_product` (`product_id`),
  ADD KEY `idx_supply_allocations_date` (`allocation_date`),
  ADD KEY `idx_supply_allocations_created_by` (`created_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `branch_id` (`branch_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts_payable`
--
ALTER TABLE `accounts_payable`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `audit_trail`
--
ALTER TABLE `audit_trail`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_logs`
--
ALTER TABLE `auth_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `delivery_items`
--
ALTER TABLE `delivery_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `franchise_applications`
--
ALTER TABLE `franchise_applications`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `franchise_owners`
--
ALTER TABLE `franchise_owners`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=355;

--
-- AUTO_INCREMENT for table `product_catalog`
--
ALTER TABLE `product_catalog`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=284;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `purchase_request_items`
--
ALTER TABLE `purchase_request_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `purchase_request_quotations`
--
ALTER TABLE `purchase_request_quotations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `royalty_payments`
--
ALTER TABLE `royalty_payments`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `stock_batches`
--
ALTER TABLE `stock_batches`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=143;

--
-- AUTO_INCREMENT for table `stock_transactions`
--
ALTER TABLE `stock_transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `supplier_performance_metrics`
--
ALTER TABLE `supplier_performance_metrics`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supply_allocations`
--
ALTER TABLE `supply_allocations`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts_payable`
--
ALTER TABLE `accounts_payable`
  ADD CONSTRAINT `fk_accounts_payable_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `fk_accounts_payable_purchase_order` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `audit_trail`
--
ALTER TABLE `audit_trail`
  ADD CONSTRAINT `fk_audit_trail_changed_by` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL;

--
-- Constraints for table `auth_logs`
--
ALTER TABLE `auth_logs`
  ADD CONSTRAINT `fk_auth_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `fk_categories_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL;

--
-- Constraints for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD CONSTRAINT `fk_deliveries_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_deliveries_purchase_order` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_deliveries_received_by` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `fk_deliveries_scheduled_by` FOREIGN KEY (`scheduled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_deliveries_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `delivery_items`
--
ALTER TABLE `delivery_items`
  ADD CONSTRAINT `fk_delivery_items_delivery` FOREIGN KEY (`delivery_id`) REFERENCES `deliveries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_delivery_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `franchise_applications`
--
ALTER TABLE `franchise_applications`
  ADD CONSTRAINT `fk_franchise_applications_reviewed_by` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD CONSTRAINT `fk_password_reset_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment_transactions`
--
ALTER TABLE `payment_transactions`
  ADD CONSTRAINT `fk_payment_trans_ap` FOREIGN KEY (`accounts_payable_id`) REFERENCES `accounts_payable` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_payment_trans_paid_by` FOREIGN KEY (`paid_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `fk_payment_trans_recorded_by` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_branch_id` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_products_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL;

--
-- Constraints for table `product_catalog`
--
ALTER TABLE `product_catalog`
  ADD CONSTRAINT `fk_product_catalog_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `fk_product_catalog_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL;

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `fk_purchase_orders_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `fk_purchase_orders_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_purchase_orders_request` FOREIGN KEY (`purchase_request_id`) REFERENCES `purchase_requests` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `fk_purchase_orders_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD CONSTRAINT `fk_purchase_order_items_order` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_purchase_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  ADD CONSTRAINT `fk_purchase_requests_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `fk_purchase_requests_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_purchase_requests_requested_by` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_request_items`
--
ALTER TABLE `purchase_request_items`
  ADD CONSTRAINT `fk_purchase_request_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_purchase_request_items_request` FOREIGN KEY (`purchase_request_id`) REFERENCES `purchase_requests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `purchase_request_quotations`
--
ALTER TABLE `purchase_request_quotations`
  ADD CONSTRAINT `fk_quotations_quoted_by` FOREIGN KEY (`quoted_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `fk_quotations_request` FOREIGN KEY (`purchase_request_id`) REFERENCES `purchase_requests` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_quotations_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `royalty_payments`
--
ALTER TABLE `royalty_payments`
  ADD CONSTRAINT `fk_royalty_payments_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `stock_batches`
--
ALTER TABLE `stock_batches`
  ADD CONSTRAINT `fk_stock_batches_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_stock_batches_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `fk_stock_batches_delivery` FOREIGN KEY (`delivery_id`) REFERENCES `deliveries` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `fk_stock_batches_po` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `fk_stock_batches_product` FOREIGN KEY (`product_id`) REFERENCES `product_catalog` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_stock_batches_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE ON UPDATE SET NULL;

--
-- Constraints for table `stock_transactions`
--
ALTER TABLE `stock_transactions`
  ADD CONSTRAINT `fk_stock_trans_approved_by` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `fk_stock_trans_batch` FOREIGN KEY (`batch_id`) REFERENCES `stock_batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_stock_trans_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_stock_trans_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `fk_stock_trans_product` FOREIGN KEY (`product_id`) REFERENCES `product_catalog` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `supplier_performance_metrics`
--
ALTER TABLE `supplier_performance_metrics`
  ADD CONSTRAINT `fk_supplier_performance_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `supply_allocations`
--
ALTER TABLE `supply_allocations`
  ADD CONSTRAINT `fk_supply_allocations_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_supply_allocations_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_supply_allocations_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_branch_id` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

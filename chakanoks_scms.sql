-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2025 at 12:50 AM
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
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Company branches';

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `code`, `name`, `address`, `created_at`, `updated_at`) VALUES
(1, 'CENTRAL', 'Central Office', 'Quimpo Boulevard, Ecoland, Davao City, Philippines', '2025-09-02 02:09:18', '2025-09-02 02:09:18'),
(2, 'MATINA', 'Matina Branch', 'MacArthur Highway, Matina Crossing, Davao City, Philippines', '2025-09-02 02:09:18', '2025-09-02 02:09:18'),
(3, 'TORIL', 'Toril Branch', 'Agton Street, Toril, Davao City, Philippines', '2025-09-02 02:09:18', '2025-09-02 02:09:18'),
(4, 'BUHANGIN', 'Buhangin Branch', 'Buhangin Road, Buhangin Proper, Davao City, Philippines', '2025-09-02 02:09:18', '2025-09-02 02:09:18'),
(5, 'AGDAO', 'Agdao Branch', 'Lapu-Lapu Street, Agdao, Davao City, Philippines', '2025-09-02 02:09:18', '2025-09-02 02:09:18'),
(6, 'LANANG', 'Lanang Branch', 'JP Laurel Avenue, Lanang, Davao City, Philippines', '2025-09-02 02:09:18', '2025-09-02 02:09:18');

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

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(10) UNSIGNED NOT NULL,
  `report_number` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Inventory records for products';

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
(16, '2025-08-29-054618', 'App\\Database\\Migrations\\CreateUsersTable', 'default', 'App', 1756778860, 1),
(17, '2025-08-29-054619', 'App\\Database\\Migrations\\CreateBranchesTable', 'default', 'App', 1756778860, 1),
(18, '2025-08-29-054620', 'App\\Database\\Migrations\\AlterUsersAddMoreRoles', 'default', 'App', 1756778860, 1),
(19, '2025-08-29-054621', 'App\\Database\\Migrations\\CreateProducts', 'default', 'App', 1756778860, 1),
(20, '2025-08-29-071346', 'App\\Database\\Migrations\\AddUsersBranchFK', 'default', 'App', 1756778860, 1),
(22, '2025-09-05-082236', 'App\\Database\\Migrations\\CreateReportsTable', 'default', 'App', 1757062133, 2),
(23, '2025-09-06-063840', 'App\\Database\\Migrations\\AddPasswordResetToUsers', 'default', 'App', 1763146111, 3),
(24, '2025-09-08-000001', 'App\\Database\\Migrations\\AddOtpColumnsToUsers', 'default', 'App', 1763146111, 3),
(25, '2025-09-10-100000', 'App\\Database\\Migrations\\CreateSuppliersTable', 'default', 'App', 1763146111, 3),
(26, '2025-09-10-100001', 'App\\Database\\Migrations\\CreatePurchaseRequestsTable', 'default', 'App', 1763146187, 4),
(27, '2025-09-10-100002', 'App\\Database\\Migrations\\CreatePurchaseRequestItemsTable', 'default', 'App', 1763146187, 4),
(28, '2025-09-10-100003', 'App\\Database\\Migrations\\CreatePurchaseOrdersTable', 'default', 'App', 1763146188, 4),
(29, '2025-09-10-100004', 'App\\Database\\Migrations\\CreatePurchaseOrderItemsTable', 'default', 'App', 1763146188, 4),
(30, '2025-09-10-100005', 'App\\Database\\Migrations\\CreateDeliveriesTable', 'default', 'App', 1763146188, 4),
(31, '2025-09-10-100006', 'App\\Database\\Migrations\\CreateDeliveryItemsTable', 'default', 'App', 1763146188, 4),
(32, '2025-09-10-100007', 'App\\Database\\Migrations\\CreateCategoriesTable', 'default', 'App', 1763146188, 4),
(33, '2025-09-10-100008', 'App\\Database\\Migrations\\CreateStockTransactionsTable', 'default', 'App', 1763146717, 5),
(34, '2025-09-10-100009', 'App\\Database\\Migrations\\AddCategoryIdToProducts', 'default', 'App', 1763146717, 5);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `branch_address` varchar(255) DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `category_id` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
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

INSERT INTO `products` (`id`, `branch_address`, `created_by`, `category_id`, `name`, `category`, `price`, `stock_qty`, `unit`, `min_stock`, `max_stock`, `expiry`, `status`, `created_at`, `updated_at`) VALUES
(67, 'MATINA Branch, Davao City', 10, NULL, 'Whole Chicken', 'Chicken Parts', 250.00, 500, 'kg', 100, 1000, '2026-03-04', 'active', '2025-11-19 22:47:27', '2025-11-19 22:47:27'),
(68, 'TORIL Branch, Davao City', 10, NULL, 'Chicken Breast', 'Chicken Parts', 280.00, 600, 'kg', 100, 1000, '2026-02-20', 'active', '2025-11-19 22:47:27', '2025-11-19 22:47:27'),
(69, 'BUHANGIN Branch, Davao City', 10, NULL, 'Chicken Thigh', 'Chicken Parts', 220.00, 550, 'kg', 100, 1000, '2026-02-17', 'active', '2025-11-19 22:47:27', '2025-11-19 22:47:27'),
(70, 'AGDAO Branch, Davao City', 10, NULL, 'Chicken Wings', 'Chicken Parts', 180.00, 450, 'kg', 100, 800, '2026-02-12', 'active', '2025-11-19 22:47:27', '2025-11-19 22:47:27'),
(71, 'LANANG Branch, Davao City', 10, NULL, 'Chicken Drumstick', 'Chicken Parts', 200.00, 500, 'kg', 100, 900, '2026-02-17', 'active', '2025-11-19 22:47:27', '2025-11-19 22:47:27'),
(73, 'TORIL Branch, Davao City', 10, NULL, 'Chicken Gizzard', 'Chicken Parts', 130.00, 280, 'kg', 50, 600, '2026-02-15', 'active', '2025-11-19 22:47:27', '2025-11-19 22:47:27'),
(74, 'BUHANGIN Branch, Davao City', 10, NULL, 'Chicken Feet', 'Chicken Parts', 90.00, 200, 'kg', 50, 500, '2026-02-19', 'active', '2025-11-19 22:47:27', '2025-11-19 22:47:27'),
(75, 'AGDAO Branch, Davao City', 10, NULL, 'Chicken Head', 'Chicken Parts', 80.00, 150, 'kg', 30, 400, '2026-02-16', 'active', '2025-11-19 22:47:27', '2025-11-19 22:47:27'),
(76, 'LANANG Branch, Davao City', 10, NULL, 'Chicken Neck', 'Chicken Parts', 100.00, 180, 'kg', 50, 500, '2026-02-13', 'active', '2025-11-19 22:47:27', '2025-11-19 22:47:27'),
(78, 'TORIL Branch, Davao City', 10, NULL, 'Chicken Heart', 'Chicken Parts', 140.00, 120, 'kg', 30, 400, '2026-02-11', 'active', '2025-11-19 22:47:27', '2025-11-19 22:47:27'),
(79, 'BUHANGIN Branch, Davao City', 10, NULL, 'Chicken Kidney', 'Chicken Parts', 135.00, 100, 'kg', 30, 350, '2026-02-10', 'active', '2025-11-19 22:47:27', '2025-11-19 22:47:27'),
(80, 'AGDAO Branch, Davao City', 10, NULL, 'Chicken Intestine', 'Chicken Parts', 95.00, 80, 'kg', 20, 300, '2026-02-09', 'active', '2025-11-19 22:47:27', '2025-11-19 22:47:27'),
(81, 'LANANG Branch, Davao City', 10, NULL, 'Chicken Blood', 'Chicken Parts', 85.00, 60, 'kg', 20, 250, '2026-02-07', 'active', '2025-11-19 22:47:27', '2025-11-19 22:47:27'),
(83, 'TORIL Branch, Davao City', 10, NULL, 'Chicken Fat', 'Chicken Parts', 70.00, 100, 'kg', 20, 300, '2026-02-23', 'active', '2025-11-19 22:47:27', '2025-11-19 22:47:27'),
(84, 'BUHANGIN Branch, Davao City', 10, NULL, 'Chicken Bones', 'Chicken Parts', 60.00, 200, 'kg', 50, 500, '2026-02-25', 'active', '2025-11-19 22:47:27', '2025-11-19 22:47:27'),
(85, 'AGDAO Branch, Davao City', 10, NULL, 'Chicken Tail', 'Chicken Parts', 105.00, 120, 'kg', 30, 350, '2026-02-14', 'active', '2025-11-19 22:47:27', '2025-11-19 22:47:27'),
(86, 'LANANG Branch, Davao City', 10, NULL, 'Chicken Leg Quarter', 'Chicken Parts', 210.00, 350, 'kg', 80, 700, '2026-02-16', 'active', '2025-11-19 22:47:27', '2025-11-19 22:47:27'),
(88, 'TORIL Branch, Davao City', 10, NULL, 'Chicken Tenderloin', 'Chicken Parts', 290.00, 250, 'kg', 50, 600, '2026-02-15', 'active', '2025-11-19 22:47:27', '2025-11-19 22:47:27'),
(89, 'BUHANGIN Branch, Davao City', 10, NULL, 'Chicken Wing Tip', 'Chicken Parts', 160.00, 180, 'kg', 40, 400, '2026-02-13', 'active', '2025-11-19 22:47:27', '2025-11-19 22:47:27'),
(90, 'AGDAO Branch, Davao City', 10, NULL, 'Chicken Wing Flat', 'Chicken Parts', 170.00, 200, 'kg', 40, 450, '2026-02-12', 'active', '2025-11-19 22:47:27', '2025-11-19 22:47:27'),
(91, 'LANANG Branch, Davao City', 10, NULL, 'Chicken Wing Drumette', 'Chicken Parts', 175.00, 220, 'kg', 50, 500, '2026-02-11', 'active', '2025-11-19 22:47:27', '2025-11-19 22:47:27'),
(92, 'MATINA Branch, Davao City', 1, NULL, 'Whole Chicken', 'Chicken Parts', 250.00, 300, 'kg', 300, 500, '2026-01-17', 'active', '2025-11-19 22:48:42', '2025-11-19 22:48:42'),
(93, 'MATINA Branch, Davao City', 1, NULL, 'Chicken Thigh', 'Chicken Parts', 220.00, 500, 'kg', 400, 600, '2026-03-27', 'active', '2025-11-19 22:51:05', '2025-11-19 22:51:16'),
(94, 'MATINA Branch, Davao City', 1, NULL, 'Chicken Wing Drumlette', 'Chicken Parts', 175.00, 350, 'kg', 350, 400, '2026-02-24', 'active', '2025-11-19 22:56:57', '2025-11-19 22:56:57');

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
  `rejection_reason` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Purchase requests from branches';

--
-- Dumping data for table `purchase_requests`
--

INSERT INTO `purchase_requests` (`id`, `request_number`, `branch_id`, `requested_by`, `status`, `priority`, `total_amount`, `notes`, `approved_by`, `approved_at`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(1, 'PR-20251119-5046', 2, 9, 'pending', 'normal', 6250.00, '', NULL, NULL, NULL, '2025-11-19 23:49:57', '2025-11-19 23:49:57'),
(2, 'PR-20251119-9782', 2, 9, 'pending', 'high', 4250.00, '', NULL, NULL, NULL, '2025-11-19 23:52:25', '2025-11-19 23:52:25'),
(3, 'PR-20251119-9471', 2, 9, 'pending', 'normal', 8700.00, '', NULL, NULL, NULL, '2025-11-19 23:58:26', '2025-11-19 23:58:26');

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
(1, 1, 67, 25, 250.00, 6250.00, '', '2025-11-19 23:49:57', '2025-11-19 23:49:57'),
(2, 2, 81, 50, 85.00, 4250.00, 'Palit namo', '2025-11-19 23:52:25', '2025-11-19 23:52:25'),
(3, 3, 88, 30, 290.00, 8700.00, '', '2025-11-19 23:58:26', '2025-11-19 23:58:26');

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
-- Table structure for table `stock_transactions`
--

CREATE TABLE `stock_transactions` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `transaction_type` enum('stock_in','stock_out') NOT NULL COMMENT 'STOCK-IN or STOCK-OUT',
  `quantity` int(10) UNSIGNED NOT NULL,
  `reference_type` varchar(50) DEFAULT NULL COMMENT 'e.g., delivery, sale, adjustment',
  `reference_id` int(10) UNSIGNED DEFAULT NULL,
  `stock_before` int(10) UNSIGNED NOT NULL COMMENT 'Stock quantity before transaction',
  `stock_after` int(10) UNSIGNED NOT NULL COMMENT 'Stock quantity after transaction',
  `is_new_stock` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'NEW STOCK flag',
  `is_expired` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'EXPIRE? flag',
  `is_old_stock` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'OLDS flag',
  `expiry_date` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Stock transactions: STOCK-IN → NEW STOCK, STOCK-OUT → EXPIRE? → OLDS';

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
  `reset_expires` datetime DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `token_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Application users';

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `branch_id`, `email`, `password`, `role`, `created_at`, `updated_at`, `reset_otp`, `otp_expires`, `reset_expires`, `reset_token`, `token_expires`) VALUES
(1, NULL, 'superadmin1@chakanoks.test', '$2y$10$udHRvGlaHy791F041TImxOl4ch9OyUl3f6OEqS1yBd0z6qpX.Ra1K', 'superadmin', NULL, '2025-09-07 14:26:26', '$2y$10$eJoYb5b3zODZpXziUiNhA.gzOOqLDFP21L6hnJy3Y4Zc2HvB9JH3e', NULL, '2025-09-06 07:47:43', '76b6e4fc36c03ea525101b97fe546012860c29abe146bd2d57ac31059caf739f', '2025-09-07 14:56:26'),
(6, 6, 'gpalagpalag@gmail.com', '$2y$10$jg3K26wAG4GHh4BoUFs66u1ne4aeu7X6QuhtzuJIb5bMNtBsKfbRK', 'logistics_coordinator', NULL, '2025-09-07 14:16:42', NULL, NULL, NULL, '8cb3856c070f6e0e377532a8b52a1162e1976dab35fee8477bd5fdae0060cc68', '2025-09-07 14:46:41'),
(8, 1, 'mansuetomarky@gmail.com', '$2y$10$evCk1Y1wiKsuc/hcbYIcSOxu/x040A1X3HkHUxrbaK.6BL8brZGO2', 'central_admin', NULL, '2025-09-10 00:46:02', '425558', '2025-09-10 00:56:02', NULL, '44432118b9cb0236b725d6ed5a34598814759f8fe27ab2cdfa833c4d279c6f29', '2025-09-10 01:16:02'),
(9, 2, 'rualesabigail09@gmail.com', '$2y$10$tqasVPR9nVZ0SvY.AjQXO.OVfPW07JSsDvms/W7lzMCvFQh7eokkO', 'branch_manager', NULL, '2025-09-10 00:45:33', '718823', '2025-09-10 00:55:33', NULL, '1cec405e38087a42030dd5efbced6ff73e9a8dabaeaec7add57d17dba8cd2c77', '2025-09-10 01:15:33'),
(10, 3, 'leo333953@gmail.com', '$2y$10$ypa4VlVQNQ2sQNfFRcxccOGuKxyNfZw0tuJr5nRrZWpIB0lMnrAGy', 'inventory_staff', NULL, '2025-09-08 14:47:03', '252767', '2025-09-08 14:57:03', NULL, 'f389b909f370b92778485d85f34131ac4bf22b56f41de8da0f1b3ee98d8bf9d9', '2025-09-08 15:17:03'),
(12, 4, 'imonakoplss@gmail.com', '$2y$10$p2Zqa63n.Dbn8Xwmch1dTOm7/WkklxwNkJa.mg/xzMOyWG/xcJoJm', 'franchise_manager', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

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
  ADD KEY `status` (`status`);

--
-- Indexes for table `delivery_items`
--
ALTER TABLE `delivery_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_id` (`delivery_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_products_created_by` (`created_by`);

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
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stock_transactions`
--
ALTER TABLE `stock_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `transaction_type` (`transaction_type`),
  ADD KEY `reference_type_reference_id` (`reference_type`,`reference_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

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
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_items`
--
ALTER TABLE `delivery_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purchase_request_items`
--
ALTER TABLE `purchase_request_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_transactions`
--
ALTER TABLE `stock_transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

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
  ADD CONSTRAINT `fk_deliveries_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `delivery_items`
--
ALTER TABLE `delivery_items`
  ADD CONSTRAINT `fk_delivery_items_delivery` FOREIGN KEY (`delivery_id`) REFERENCES `deliveries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_delivery_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL;

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
-- Constraints for table `stock_transactions`
--
ALTER TABLE `stock_transactions`
  ADD CONSTRAINT `fk_stock_transactions_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `fk_stock_transactions_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_branch_id` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

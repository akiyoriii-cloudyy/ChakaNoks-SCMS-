-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2025 at 03:36 PM
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
(1, 'CENTRAL', 'Central Office', 'Quimpo Boulevard, Ecoland, Davao City, Philippines', '2025-11-22 21:55:32', '2025-11-22 21:55:32'),
(2, 'MATINA', 'Matina Branch', 'MacArthur Highway, Matina Crossing, Davao City, Philippines', '2025-11-22 21:55:32', '2025-11-22 21:55:32'),
(3, 'TORIL', 'Toril Branch', 'Agton Street, Toril, Davao City, Philippines', '2025-11-22 21:55:32', '2025-11-22 21:55:32'),
(4, 'BUHANGIN', 'Buhangin Branch', 'Buhangin Road, Buhangin Proper, Davao City, Philippines', '2025-11-22 21:55:32', '2025-11-22 21:55:32'),
(5, 'AGDAO', 'Agdao Branch', 'Lapu-Lapu Street, Agdao, Davao City, Philippines', '2025-11-22 21:55:32', '2025-11-22 21:55:32'),
(6, 'LANANG', 'Lanang Branch', 'JP Laurel Avenue, Lanang, Davao City, Philippines', '2025-11-22 21:55:32', '2025-11-22 21:55:32');

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

--
-- Dumping data for table `deliveries`
--

INSERT INTO `deliveries` (`id`, `delivery_number`, `purchase_order_id`, `supplier_id`, `branch_id`, `status`, `scheduled_date`, `actual_delivery_date`, `driver_name`, `vehicle_info`, `received_by`, `received_at`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'DEL-20251122-4690', 1, 1, 4, 'scheduled', '2025-11-29', NULL, NULL, NULL, NULL, NULL, 'Auto-created from Purchase Order', '2025-11-22 22:23:25', '2025-11-22 22:23:25'),
(2, 'DEL-20251122-8768', 2, 1, 3, 'scheduled', '2025-11-29', NULL, NULL, NULL, NULL, NULL, 'Auto-created from Purchase Order', '2025-11-22 22:24:35', '2025-11-22 22:24:35'),
(3, 'DEL-20251122-4707', 3, 1, 4, 'scheduled', '2025-11-29', NULL, NULL, NULL, NULL, NULL, 'Auto-created from Purchase Order', '2025-11-22 22:25:11', '2025-11-22 22:25:11'),
(4, 'DEL-20251122-9468', 4, 1, 5, 'scheduled', '2025-11-29', NULL, NULL, NULL, NULL, NULL, 'Auto-created from Purchase Order', '2025-11-22 22:26:31', '2025-11-22 22:26:31');

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
(1, 1, 39, 300, 0, 'good', NULL, '2025-11-22 22:23:25', '2025-11-22 22:23:25'),
(2, 2, 46, 350, 0, 'good', NULL, '2025-11-22 22:24:35', '2025-11-22 22:24:35'),
(3, 3, 32, 400, 0, 'good', NULL, '2025-11-22 22:25:11', '2025-11-22 22:25:11'),
(4, 4, 40, 150, 0, 'good', NULL, '2025-11-22 22:26:31', '2025-11-22 22:26:31');

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
(19, '2025-11-21-000001', 'App\\Database\\Migrations\\AddBranchIdToProducts', 'default', 'App', 1763819705, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED DEFAULT NULL,
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

INSERT INTO `products` (`id`, `branch_id`, `branch_address`, `created_by`, `category_id`, `name`, `category`, `price`, `stock_qty`, `unit`, `min_stock`, `max_stock`, `expiry`, `status`, `created_at`, `updated_at`) VALUES
(27, 2, 'MATINA Branch, Davao City', 10, NULL, 'Whole Chicken', 'Chicken Parts', 250.00, 500, 'kg', 100, 1000, '2026-03-07', 'active', '2025-11-22 22:08:37', '2025-11-22 22:08:37'),
(28, 3, 'TORIL Branch, Davao City', 10, NULL, 'Chicken Breast', 'Chicken Parts', 280.00, 600, 'kg', 100, 1000, '2026-02-23', 'active', '2025-11-22 22:08:37', '2025-11-22 22:08:37'),
(29, 4, 'BUHANGIN Branch, Davao City', 10, NULL, 'Chicken Thigh', 'Chicken Parts', 220.00, 550, 'kg', 100, 1000, '2026-02-20', 'active', '2025-11-22 22:08:37', '2025-11-22 22:08:37'),
(30, 5, 'AGDAO Branch, Davao City', 10, NULL, 'Chicken Wings', 'Chicken Parts', 180.00, 450, 'kg', 100, 800, '2026-02-15', 'active', '2025-11-22 22:08:37', '2025-11-22 22:08:37'),
(31, 6, 'LANANG Branch, Davao City', 10, NULL, 'Chicken Drumstick', 'Chicken Parts', 200.00, 500, 'kg', 100, 900, '2026-02-20', 'active', '2025-11-22 22:08:37', '2025-11-22 22:08:37'),
(32, 2, 'MATINA Branch, Davao City', 10, NULL, 'Chicken Liver', 'Chicken Parts', 120.00, 300, 'kg', 50, 600, '2026-02-17', 'active', '2025-11-22 22:08:37', '2025-11-22 22:08:37'),
(33, 3, 'TORIL Branch, Davao City', 10, NULL, 'Chicken Gizzard', 'Chicken Parts', 130.00, 280, 'kg', 50, 600, '2026-02-18', 'active', '2025-11-22 22:08:37', '2025-11-22 22:08:37'),
(34, 4, 'BUHANGIN Branch, Davao City', 10, NULL, 'Chicken Feet', 'Chicken Parts', 90.00, 200, 'kg', 50, 500, '2026-02-22', 'active', '2025-11-22 22:08:37', '2025-11-22 22:08:37'),
(35, 5, 'AGDAO Branch, Davao City', 10, NULL, 'Chicken Head', 'Chicken Parts', 80.00, 150, 'kg', 30, 400, '2026-02-19', 'active', '2025-11-22 22:08:37', '2025-11-22 22:08:37'),
(36, 6, 'LANANG Branch, Davao City', 10, NULL, 'Chicken Neck', 'Chicken Parts', 100.00, 180, 'kg', 50, 500, '2026-02-16', 'active', '2025-11-22 22:08:37', '2025-11-22 22:08:37'),
(37, 2, 'MATINA Branch, Davao City', 10, NULL, 'Chicken Back', 'Chicken Parts', 110.00, 220, 'kg', 50, 550, '2026-02-21', 'active', '2025-11-22 22:08:37', '2025-11-22 22:08:37'),
(38, 3, 'TORIL Branch, Davao City', 10, NULL, 'Chicken Heart', 'Chicken Parts', 140.00, 120, 'kg', 30, 400, '2026-02-14', 'active', '2025-11-22 22:08:37', '2025-11-22 22:08:37'),
(39, 4, 'BUHANGIN Branch, Davao City', 10, NULL, 'Chicken Kidney', 'Chicken Parts', 135.00, 100, 'kg', 30, 350, '2026-02-13', 'active', '2025-11-22 22:08:37', '2025-11-22 22:08:37'),
(40, 5, 'AGDAO Branch, Davao City', 10, NULL, 'Chicken Intestine', 'Chicken Parts', 95.00, 80, 'kg', 20, 300, '2026-02-12', 'active', '2025-11-22 22:08:37', '2025-11-22 22:08:37'),
(41, 6, 'LANANG Branch, Davao City', 10, NULL, 'Chicken Blood', 'Chicken Parts', 85.00, 60, 'kg', 20, 250, '2026-02-10', 'active', '2025-11-22 22:08:37', '2025-11-22 22:08:37'),
(42, 2, 'MATINA Branch, Davao City', 10, NULL, 'Chicken Skin', 'Chicken Parts', 75.00, 150, 'kg', 30, 400, '2026-02-24', 'active', '2025-11-22 22:08:37', '2025-11-22 22:08:37'),
(43, 3, 'TORIL Branch, Davao City', 10, NULL, 'Chicken Fat', 'Chicken Parts', 70.00, 100, 'kg', 20, 300, '2026-02-26', 'active', '2025-11-22 22:08:37', '2025-11-22 22:08:37'),
(44, 4, 'BUHANGIN Branch, Davao City', 10, NULL, 'Chicken Bones', 'Chicken Parts', 60.00, 200, 'kg', 50, 500, '2026-02-28', 'active', '2025-11-22 22:08:37', '2025-11-22 22:08:37'),
(45, 5, 'AGDAO Branch, Davao City', 10, NULL, 'Chicken Tail', 'Chicken Parts', 105.00, 120, 'kg', 30, 350, '2026-02-17', 'active', '2025-11-22 22:08:37', '2025-11-22 22:08:37'),
(46, 6, 'LANANG Branch, Davao City', 10, NULL, 'Chicken Leg Quarter', 'Chicken Parts', 210.00, 350, 'kg', 80, 700, '2026-02-19', 'active', '2025-11-22 22:08:37', '2025-11-22 22:08:37'),
(47, 2, 'MATINA Branch, Davao City', 10, NULL, 'Chicken Breast Fillet', 'Chicken Parts', 320.00, 400, 'kg', 100, 800, '2026-02-21', 'active', '2025-11-22 22:08:37', '2025-11-22 22:08:37'),
(48, 3, 'TORIL Branch, Davao City', 10, NULL, 'Chicken Tenderloin', 'Chicken Parts', 290.00, 250, 'kg', 50, 600, '2026-02-18', 'active', '2025-11-22 22:08:37', '2025-11-22 22:08:37'),
(49, 4, 'BUHANGIN Branch, Davao City', 10, NULL, 'Chicken Wing Tip', 'Chicken Parts', 160.00, 180, 'kg', 40, 400, '2026-02-16', 'active', '2025-11-22 22:08:37', '2025-11-22 22:08:37'),
(50, 5, 'AGDAO Branch, Davao City', 10, NULL, 'Chicken Wing Flat', 'Chicken Parts', 170.00, 200, 'kg', 40, 450, '2026-02-15', 'active', '2025-11-22 22:08:37', '2025-11-22 22:08:37'),
(51, 6, 'LANANG Branch, Davao City', 10, NULL, 'Chicken Wing Drumlette', 'Chicken Parts', 175.00, 220, 'kg', 50, 500, '2026-02-14', 'active', '2025-11-22 22:08:37', '2025-11-22 22:08:37'),
(52, 2, 'Matina Branch — MacArthur Highway, Matina Crossing, Davao City, Philippines', 4, NULL, 'Chicken Breast', 'Chicken Parts', 200.00, 200, 'kg', 200, 200, '2026-02-18', 'active', '2025-11-22 22:09:43', '2025-11-22 22:09:43'),
(53, 6, 'Lanang Branch — JP Laurel Avenue, Lanang, Davao City, Philippines', 8, NULL, 'Chicken Drumstick', 'Chicken Parts', 200.00, 300, 'kg', 300, 300, '2026-02-09', 'active', '2025-11-22 22:11:06', '2025-11-22 22:11:06'),
(54, 5, 'Agdao Branch — Lapu-Lapu Street, Agdao, Davao City, Philippines', 7, NULL, 'Chicken Neck', 'Chicken Parts', 90.00, 250, 'kg', 250, 250, '2026-02-17', 'active', '2025-11-22 22:12:24', '2025-11-22 22:12:24'),
(55, 4, 'Buhangin Branch — Buhangin Road, Buhangin Proper, Davao City, Philippines', 6, NULL, 'Whole Chicken', 'Chicken Parts', 250.00, 300, 'kg', 300, 300, '2026-01-05', 'active', '2025-11-22 22:14:39', '2025-11-22 22:14:39'),
(56, 3, 'Toril Branch — Agton Street, Toril, Davao City, Philippines', 5, NULL, 'Chicken Thigh', 'Chicken Parts', 220.00, 450, 'kg', 450, 450, '2026-03-20', 'active', '2025-11-22 22:15:49', '2025-11-22 22:15:49');

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
(1, 'PO-20251122-0947', 1, 1, 4, 'pending', '2025-11-22', '2025-12-02', NULL, 40500.00, NULL, NULL, 'Auto-created from approved purchase request', '2025-11-22 22:23:24', '2025-11-22 22:28:49'),
(2, 'PO-20251122-8191', 5, 7, 3, 'pending', '2025-11-22', '2025-12-02', NULL, 73500.00, NULL, NULL, 'Auto-created from approved purchase request', '2025-11-22 22:24:34', '2025-11-22 22:28:21'),
(3, 'PO-20251122-2423', 3, 17, 4, 'pending', '2025-11-22', '2025-11-30', NULL, 48000.00, NULL, NULL, 'Auto-created from approved purchase request', '2025-11-22 22:25:11', '2025-11-22 22:27:46'),
(4, 'PO-20251122-2076', 4, 3, 5, 'approved', '2025-11-22', '2025-12-01', NULL, 14250.00, 2, '2025-11-22 22:33:15', 'Auto-created from approved purchase request', '2025-11-22 22:26:31', '2025-11-22 22:33:15');

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
(1, 1, 39, 300, 135.00, 40500.00, 0, '2025-11-22 22:23:24', '2025-11-22 22:23:24'),
(2, 2, 46, 350, 210.00, 73500.00, 0, '2025-11-22 22:24:34', '2025-11-22 22:24:34'),
(3, 3, 32, 400, 120.00, 48000.00, 0, '2025-11-22 22:25:11', '2025-11-22 22:25:11'),
(4, 4, 40, 150, 95.00, 14250.00, 0, '2025-11-22 22:26:31', '2025-11-22 22:26:31');

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
(1, 'PR-20251122-3938', 4, 4, 'converted_to_po', 'urgent', 40500.00, '', 2, '2025-11-22 22:23:24', NULL, '2025-11-22 22:09:03', '2025-11-22 22:23:25'),
(2, 'PR-20251122-1692', 3, 8, 'rejected', 'urgent', 22500.00, '', NULL, NULL, 'EXPIRED ITEMS', '2025-11-22 22:11:49', '2025-11-22 22:24:06'),
(3, 'PR-20251122-0706', 4, 7, 'converted_to_po', 'normal', 48000.00, '', 2, '2025-11-22 22:25:11', NULL, '2025-11-22 22:13:44', '2025-11-22 22:25:11'),
(4, 'PR-20251122-0269', 5, 6, 'converted_to_po', 'low', 14250.00, '', 2, '2025-11-22 22:26:31', NULL, '2025-11-22 22:15:09', '2025-11-22 22:26:31'),
(5, 'PR-20251122-0236', 3, 5, 'converted_to_po', 'high', 73500.00, '', 2, '2025-11-22 22:24:34', NULL, '2025-11-22 22:16:25', '2025-11-22 22:24:34');

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
(1, 1, 39, 300, 135.00, 40500.00, '', '2025-11-22 22:09:03', '2025-11-22 22:09:03'),
(2, 2, 34, 250, 90.00, 22500.00, '', '2025-11-22 22:11:49', '2025-11-22 22:11:49'),
(3, 3, 32, 400, 120.00, 48000.00, '', '2025-11-22 22:13:44', '2025-11-22 22:13:44'),
(4, 4, 40, 150, 95.00, 14250.00, '', '2025-11-22 22:15:09', '2025-11-22 22:15:09'),
(5, 5, 46, 350, 210.00, 73500.00, '', '2025-11-22 22:16:25', '2025-11-22 22:16:25');

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

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact_person`, `email`, `phone`, `address`, `payment_terms`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Magnolia Chicken', 'Sales Department', 'sales@magnolia.com.ph', '(02) 8123-4567', 'Magnolia Avenue, Quezon City, Philippines', 'Net 30', 'active', '2025-11-22 21:55:57', '2025-11-22 21:55:57'),
(2, 'Ana\'s Breeders Farms Inc', 'Farm Manager', 'info@anasbreeders.com.ph', '(049) 501-2345', 'Tagaytay, Cavite, Philippines', 'COD', 'active', '2025-11-22 21:55:57', '2025-11-22 21:55:57'),
(3, 'Premium Feeds Corporation', 'Sales Officer', 'sales@premiumfeeds.com.ph', '(02) 8456-7890', 'Makati City, Philippines', 'Net 15', 'active', '2025-11-22 21:55:57', '2025-11-22 21:55:57'),
(4, 'San Miguel Foods', 'Corporate Sales', 'corporate@sanmiguelfoods.com.ph', '(02) 8888-0000', 'Mandaluyong City, Philippines', 'Net 30', 'active', '2025-11-22 21:55:57', '2025-11-22 21:55:57'),
(5, 'CDO Foodsphere Inc.', 'Sales Department', 'sales@cdofoodsphere.com.ph', '(02) 8123-5678', 'Quezon City, Philippines', 'Net 30', 'active', '2025-11-22 21:55:57', '2025-11-22 21:55:57'),
(6, 'Excellence Poultry and Livestock Specialist Inc.', 'Operations Manager', 'info@excellencepoultry.com.ph', '(049) 502-3456', 'Cavite, Philippines', 'COD', 'active', '2025-11-22 21:55:57', '2025-11-22 21:55:57'),
(7, 'Rare Global Food Trading Corp.', 'Trading Manager', 'trading@rareglobal.com.ph', '(02) 8234-5678', 'Pasig City, Philippines', 'Net 15', 'active', '2025-11-22 21:55:57', '2025-11-22 21:55:57'),
(8, 'E&L Faster Food Imports Inc.', 'Import Manager', 'imports@elfaster.com.ph', '(02) 8345-6789', 'Port Area, Manila, Philippines', 'Net 45', 'active', '2025-11-22 21:55:57', '2025-11-22 21:55:57'),
(9, 'Foster Foods Inc', 'Sales Coordinator', 'sales@fosterfoods.com.ph', '(02) 8456-7890', 'Taguig City, Philippines', 'Net 30', 'active', '2025-11-22 21:55:57', '2025-11-22 21:55:57'),
(10, 'Pilmico', 'Corporate Sales', 'corporate@pilmico.com.ph', '(02) 8567-8901', 'Laguna, Philippines', 'Net 30', 'active', '2025-11-22 21:55:57', '2025-11-22 21:55:57'),
(11, 'Consistent Frozen Solutions Corp.', 'Sales Manager', 'sales@consistentfrozen.com.ph', '(02) 8678-9012', 'Cavite, Philippines', 'Net 15', 'active', '2025-11-22 21:55:57', '2025-11-22 21:55:57'),
(12, 'EcoSci Food', 'Business Development', 'bd@ecoscifood.com.ph', '(02) 8789-0123', 'Quezon City, Philippines', 'COD', 'active', '2025-11-22 21:55:57', '2025-11-22 21:55:57'),
(13, 'Advance Protein Inc.', 'Sales Officer', 'sales@advanceprotein.com.ph', '(02) 8890-1234', 'Bulacan, Philippines', 'Net 30', 'active', '2025-11-22 21:55:57', '2025-11-22 21:55:57'),
(14, 'Art Inc.', 'Account Manager', 'accounts@artinc.com.ph', '(02) 8901-2345', 'Manila, Philippines', 'Net 15', 'active', '2025-11-22 21:55:57', '2025-11-22 21:55:57'),
(15, 'Clarc Feedmill Inc.', 'Production Manager', 'production@clarcfeedmill.com.ph', '(049) 503-4567', 'Cavite, Philippines', 'COD', 'active', '2025-11-22 21:55:57', '2025-11-22 21:55:57'),
(16, 'Kai Anya Foods Intl Corp', 'International Sales', 'sales@kaianya.com.ph', '(02) 8012-3456', 'Makati City, Philippines', 'Net 30', 'active', '2025-11-22 21:55:57', '2025-11-22 21:55:57'),
(17, 'Hightower Incorporated', 'Sales Department', 'sales@hightower.com.ph', '(02) 8123-4567', 'Pasig City, Philippines', 'Net 30', 'active', '2025-11-22 21:55:57', '2025-11-22 21:55:57'),
(18, 'The Original Savory Escolta - Online', 'Online Manager', 'online@savoryescolta.com.ph', '(02) 8234-5678', 'Escolta, Manila, Philippines', 'COD', 'active', '2025-11-22 21:55:57', '2025-11-22 21:55:57'),
(19, 'Fresco PH', 'Sales Team', 'sales@fresco.com.ph', '(02) 8345-6789', 'Quezon City, Philippines', 'Net 15', 'active', '2025-11-22 21:55:57', '2025-11-22 21:55:57');

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
(1, NULL, 'superadmin1@chakanoks.test', '$2y$10$05nYt6PPZIh43s/zQJ8gHuDAXP6pLJPhDjjqu4ZX2s7V.4A911RSu', 'superadmin', NULL, NULL, NULL, NULL, NULL),
(2, 1, 'mansuetomarky@gmail.com', '$2y$10$gt52/Pm/1bJZK4hfdhvUie8QtmvUWQlm98CYQRJrrhLhYFa5VOFae', 'central_admin', NULL, NULL, NULL, NULL, NULL),
(3, 2, 'rualesabigail09@gmail.com', '$2y$10$.LZ/LRwVORqiPuChzMNgueDAT3WyGfTWixE3qS/bSLudZVZviSi1u', 'branch_manager', NULL, NULL, NULL, NULL, NULL),
(4, 2, 'matina.manager@chakanoks.test', '$2y$10$W1avj3gcceL91MIskjVMZeBYmDAeb289Z7AynoYcAlJK.GUqfpIBS', 'branch_manager', NULL, NULL, NULL, NULL, NULL),
(5, 3, 'toril.manager@chakanoks.test', '$2y$10$wEt2TUqlBYKhffTHg9MuMugon2OE9xK0KSP7Knx74YZy82QuN1HKC', 'branch_manager', NULL, NULL, NULL, NULL, NULL),
(6, 4, 'buhangin.manager@chakanoks.test', '$2y$10$f9Kaaox50G8jzYr7yAk4eu50tnGx/OQ2/EyphhPKo8Kf.DiFfOYBC', 'branch_manager', NULL, NULL, NULL, NULL, NULL),
(7, 5, 'agdao.manager@chakanoks.test', '$2y$10$VscmeGr/qaTaGbAgy/2LoeA6tSHd9dRx73Vdltz5LfNUodKQIIoke', 'branch_manager', NULL, NULL, NULL, NULL, NULL),
(8, 6, 'lanang.manager@chakanoks.test', '$2y$10$1.r4K/oec6sqXdfihc8DBuAvR0C7RdEajNFjJ9BvGiS9fdskyK3YC', 'branch_manager', NULL, NULL, NULL, NULL, NULL),
(9, 4, 'imonakoplss@gmail.com', '$2y$10$Mf4.7PXPbVluqZl.xxpHg.C1L98jgV2tTJLJBBndRCbX0nB1ED.Uq', 'franchise_manager', NULL, NULL, NULL, NULL, NULL),
(10, 3, 'leo333953@gmail.com', '$2y$10$GtOtnhL4u.D9e0aD9DoJg.RFj69.Ok2rrwccDX1oZCq7j5aI6BvF2', 'inventory_staff', NULL, NULL, NULL, NULL, NULL),
(11, 6, 'gpalagpalag@gmail.com', '$2y$10$Zh4fYETWnHhZG7rs.yaUsO6wO3aqQpSCqHyiKwD3R.PJsiWf9LR4y', 'logistics_coordinator', NULL, NULL, NULL, NULL, NULL);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_products_created_by` (`created_by`),
  ADD KEY `fk_products_branch_id` (`branch_id`);

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `delivery_items`
--
ALTER TABLE `delivery_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `purchase_request_items`
--
ALTER TABLE `purchase_request_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
  ADD CONSTRAINT `fk_products_branch_id` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
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

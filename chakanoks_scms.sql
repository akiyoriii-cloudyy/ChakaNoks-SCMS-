-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2025 at 06:58 AM
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
  `supplier_id` int(10) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED NOT NULL,
  `invoice_number` varchar(100) DEFAULT NULL,
  `invoice_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('unpaid','partial','paid','overdue') NOT NULL DEFAULT 'unpaid',
  `payment_date` date DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_reference` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Accounts payable records for approved purchase orders';

--
-- Dumping data for table `accounts_payable`
--

INSERT INTO `accounts_payable` (`id`, `purchase_order_id`, `supplier_id`, `branch_id`, `invoice_number`, `invoice_date`, `due_date`, `amount`, `paid_amount`, `balance`, `payment_status`, `payment_date`, `payment_method`, `payment_reference`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(13, 17, 5, 5, 'INV-20251125-64640', '2025-11-25', '2025-12-25', 24000.00, 24000.00, 0.00, 'paid', '2025-11-25', 'Cash', '475895', 'Auto-created from approved purchase order: PO-20251125-5937', 2, '2025-11-25 14:28:23', '2025-11-25 14:29:02'),
(14, 18, 13, 6, 'INV-20251125-26188', '2025-11-25', '2025-12-25', 42000.00, 42000.00, 0.00, 'paid', '2025-11-25', 'Cash', '3192832', 'Auto-created from approved purchase order: PO-20251125-1815', 2, '2025-11-25 14:30:46', '2025-11-25 14:31:04');

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

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_by`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Chicken Parts', 'Chicken parts and products', 12, 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57');

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
(16, 'DEL-20251125-3663', 17, 5, 5, 'delivered', '2026-01-20', '2025-11-25', 'Markypadilla', 'L300 RE3949', 7, '2025-11-25 15:24:00', 'Auto-created from Purchase Order', '2025-11-25 14:28:00', '2025-11-25 15:24:32'),
(17, 'DEL-20251125-0721', 18, 13, 6, 'delivered', '2025-11-25', '2025-11-25', 'AbbyRuales', 'L300 AB8930', 8, '2025-11-25 15:18:00', 'Auto-created from Purchase Order', '2025-11-25 14:30:34', '2025-11-25 15:18:31');

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
(22, '2025-11-25-000002', 'App\\Database\\Migrations\\CreateAccountsPayableTable', 'default', 'App', 1764003252, 4);

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
(52, 2, 4, 1, 'Chicken Breast', 200.00, 200, 'kg', 200, 200, '2026-02-18', 'active', '2025-11-22 22:09:43', '2025-11-22 22:09:43'),
(53, 6, 12, 2, 'Chicken Drumstick', 200.00, 500, 'kg', 100, 900, '2026-02-23', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(54, 5, 7, 1, 'Chicken Neck', 90.00, 250, 'kg', 250, 250, '2026-02-17', 'active', '2025-11-22 22:12:24', '2025-11-22 22:12:24'),
(55, 4, 6, 1, 'Whole Chicken', 250.00, 300, 'kg', 300, 300, '2026-01-05', 'active', '2025-11-22 22:14:39', '2025-11-22 22:14:39'),
(56, 3, 5, 1, 'Chicken Thigh', 220.00, 495, 'kg', 450, 450, '2026-03-20', 'active', '2025-11-22 22:15:49', '2025-11-25 16:32:32'),
(57, 2, 4, 1, 'Chicken Drumstick', 200.00, 300, 'kg', 300, 300, '2026-03-24', 'active', '2025-11-24 23:15:06', '2025-11-24 23:15:06'),
(58, 2, 4, 1, 'Chicken Feet', 90.00, 200, 'kg', 200, 200, '2026-01-26', 'active', '2025-11-25 00:00:02', '2025-11-25 00:00:02'),
(59, 2, 4, 1, 'Chicken Heart', 175.00, 300, 'kg', 300, 350, '2026-03-20', 'active', '2025-11-25 00:05:35', '2025-11-25 00:05:35'),
(60, 2, 4, 1, 'Chicken Head', 105.00, 300, 'kg', 300, 300, '2025-12-27', 'active', '2025-11-25 00:40:19', '2025-11-25 00:40:19'),
(61, 2, 12, 2, 'Whole Chicken', 250.00, 500, 'kg', 100, 1000, '2026-03-10', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(62, 3, 12, 2, 'Chicken Breast', 280.00, 550, 'kg', 100, 1000, '2026-02-26', 'active', '2025-11-25 15:45:57', '2025-11-25 16:35:32'),
(63, 4, 12, 2, 'Chicken Thigh', 220.00, 550, 'kg', 100, 1000, '2026-02-23', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(64, 5, 12, 2, 'Chicken Wings', 180.00, 450, 'kg', 100, 800, '2026-02-18', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(65, 2, 12, 2, 'Chicken Liver', 120.00, 300, 'kg', 50, 600, '2026-02-20', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(66, 3, 12, 2, 'Chicken Gizzard', 130.00, 280, 'kg', 50, 600, '2026-02-21', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(67, 4, 12, 2, 'Chicken Feet', 90.00, 200, 'kg', 50, 500, '2026-02-25', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(68, 5, 12, 2, 'Chicken Head', 80.00, 150, 'kg', 30, 400, '2026-02-22', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(69, 6, 12, 2, 'Chicken Neck', 100.00, 180, 'kg', 50, 500, '2026-02-19', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(70, 2, 12, 2, 'Chicken Back', 110.00, 220, 'kg', 50, 550, '2026-02-24', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(71, 3, 12, 2, 'Chicken Heart', 140.00, 120, 'kg', 30, 400, '2026-02-17', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(72, 4, 12, 2, 'Chicken Kidney', 135.00, 100, 'kg', 30, 350, '2026-02-16', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(73, 5, 12, 2, 'Chicken Intestine', 95.00, 80, 'kg', 20, 300, '2026-02-15', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(74, 6, 12, 2, 'Chicken Blood', 85.00, 60, 'kg', 20, 250, '2026-02-13', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(75, 2, 12, 2, 'Chicken Skin', 75.00, 150, 'kg', 30, 400, '2026-02-27', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(76, 3, 12, 2, 'Chicken Fat', 70.00, 100, 'kg', 20, 300, '2026-03-01', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(77, 4, 12, 2, 'Chicken Bones', 60.00, 200, 'kg', 50, 500, '2026-03-03', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(78, 5, 12, 2, 'Chicken Tail', 105.00, 120, 'kg', 30, 350, '2026-02-20', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(79, 6, 12, 2, 'Chicken Leg Quarter', 210.00, 350, 'kg', 80, 700, '2026-02-22', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(80, 2, 12, 2, 'Chicken Breast Fillet', 320.00, 400, 'kg', 100, 800, '2026-02-24', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(81, 3, 12, 2, 'Chicken Tenderloin', 290.00, 250, 'kg', 50, 600, '2026-02-21', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(82, 4, 12, 2, 'Chicken Wing Tip', 160.00, 180, 'kg', 40, 400, '2026-02-19', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(83, 5, 12, 2, 'Chicken Wing Flat', 170.00, 200, 'kg', 40, 450, '2026-02-18', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57'),
(84, 6, 12, 2, 'Chicken Wing Drumlette', 175.00, 220, 'kg', 50, 500, '2026-02-17', 'active', '2025-11-25 15:45:57', '2025-11-25 15:45:57');

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
(17, 'PO-20251125-5937', 19, 5, 5, 'approved', '2025-11-25', '2025-12-02', NULL, 24000.00, 2, '2025-11-25 14:28:23', 'Auto-created from approved purchase request', '2025-11-25 14:28:00', '2025-11-25 14:28:23'),
(18, 'PO-20251125-1815', 20, 13, 6, 'approved', '2025-11-25', '2025-12-02', NULL, 42000.00, 2, '2025-11-25 14:30:46', 'Auto-created from approved purchase request', '2025-11-25 14:30:34', '2025-11-25 14:30:46');

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
  `selected_supplier_id` int(10) UNSIGNED DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Purchase requests from branches';

--
-- Dumping data for table `purchase_requests`
--

INSERT INTO `purchase_requests` (`id`, `request_number`, `branch_id`, `requested_by`, `status`, `priority`, `total_amount`, `notes`, `approved_by`, `approved_at`, `selected_supplier_id`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(19, 'PR-20251125-0970', 5, 7, 'converted_to_po', 'low', 24000.00, '', 2, '2025-11-25 14:28:00', 5, NULL, '2025-11-25 14:01:21', '2025-11-25 14:28:00'),
(20, 'PR-20251125-2497', 6, 8, 'converted_to_po', 'high', 42000.00, '', 2, '2025-11-25 14:30:34', 13, NULL, '2025-11-25 14:26:48', '2025-11-25 14:30:34');

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
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `payment_status` (`payment_status`),
  ADD KEY `due_date` (`due_date`);

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
-- AUTO_INCREMENT for table `accounts_payable`
--
ALTER TABLE `accounts_payable`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `delivery_items`
--
ALTER TABLE `delivery_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `purchase_request_items`
--
ALTER TABLE `purchase_request_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_transactions`
--
ALTER TABLE `stock_transactions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
  ADD CONSTRAINT `fk_accounts_payable_branch` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_accounts_payable_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `fk_accounts_payable_purchase_order` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_accounts_payable_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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

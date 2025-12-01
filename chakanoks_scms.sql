-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
<<<<<<< HEAD
-- Generation Time: Dec 01, 2025 at 02:27 PM
=======
-- Generation Time: Nov 28, 2025 at 08:09 PM
>>>>>>> a496b0278e37441ddb3a838f8a09720c22faf329
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
(28, 33, 42, 4, 'INV-20251129-59705', '2025-11-29', '2025-12-29', 18000.00, 18000.00, 0.00, 'paid', '2025-11-29', 'Cash', '8928391', 'Auto-created from approved purchase order: PO-20251129-5677', 2, '2025-11-29 02:57:28', '2025-11-29 02:57:59'),
(29, 35, 63, 6, 'INV-20251129-13002', '2025-11-29', '2025-12-29', 9000.00, 9000.00, 0.00, 'paid', '2025-11-29', 'Bank Transfer', '7823182', 'Auto-created from approved purchase order: PO-20251129-7054', 2, '2025-11-29 03:05:39', '2025-11-29 03:06:09'),
<<<<<<< HEAD
(30, 34, 11, 5, 'INV-20251129-94056', '2025-11-29', '2025-12-14', 24000.00, 24000.00, 0.00, 'paid', '2025-11-29', 'Checkque', '8923712', 'Auto-created from approved purchase order: PO-20251129-6834', 2, '2025-11-29 03:05:41', '2025-11-29 03:05:52'),
(31, 36, 1, 2, 'INV-20251201-33201', '2025-12-01', '2025-12-31', 17500.00, 17500.00, 0.00, 'paid', '2025-12-01', 'Cash', '239481', 'Auto-created from approved purchase order: PO-20251201-5995', 2, '2025-12-01 13:40:35', '2025-12-01 13:41:23');
=======
(30, 34, 11, 5, 'INV-20251129-94056', '2025-11-29', '2025-12-14', 24000.00, 24000.00, 0.00, 'paid', '2025-11-29', 'Checkque', '8923712', 'Auto-created from approved purchase order: PO-20251129-6834', 2, '2025-11-29 03:05:41', '2025-11-29 03:05:52');
>>>>>>> a496b0278e37441ddb3a838f8a09720c22faf329

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
<<<<<<< HEAD
(32, 'DEL-20251129-7561', 35, 63, 6, 2, 'in_transit', '2025-11-30', NULL, 'BossKakak', 'L300 AB3123', NULL, NULL, 'Auto-created from Purchase Order #35', '2025-11-29 03:04:18', '2025-11-29 03:06:49'),
(33, 'DEL-20251201-8294', 36, 1, 2, 2, 'delivered', '2025-12-01', '2025-12-01', 'Marky Hatdog', 'L300 AB8930', 4, '2025-12-01 13:41:00', 'Auto-created from Purchase Order #36', '2025-12-01 13:40:27', '2025-12-01 13:41:58');
=======
(32, 'DEL-20251129-7561', 35, 63, 6, 2, 'in_transit', '2025-11-30', NULL, 'BossKakak', 'L300 AB3123', NULL, NULL, 'Auto-created from Purchase Order #35', '2025-11-29 03:04:18', '2025-11-29 03:06:49');
>>>>>>> a496b0278e37441ddb3a838f8a09720c22faf329

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
<<<<<<< HEAD
(32, 32, 222, 50, 0, 'good', NULL, '2025-11-29 03:04:18', '2025-11-29 03:04:18'),
(33, 33, 285, 50, 50, 'good', NULL, '2025-12-01 13:40:27', '2025-12-01 13:41:58');
=======
(32, 32, 222, 50, 0, 'good', NULL, '2025-11-29 03:04:18', '2025-11-29 03:04:18');
>>>>>>> a496b0278e37441ddb3a838f8a09720c22faf329

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
(23, '2025-11-29-000001', 'App\\Database\\Migrations\\AddScheduledByToDeliveries', 'default', 'App', 1764356547, 5);

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
<<<<<<< HEAD
(204, 5, 12, 18, 'Hot Chocolate Mix', 120.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
=======
(204, 5, 12, 3, 'Hot Chocolate Mix', 120.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
>>>>>>> a496b0278e37441ddb3a838f8a09720c22faf329
(205, 5, 12, 3, 'Lemonade Mix', 95.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(206, 5, 12, 3, 'Fruit Punch', 90.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(207, 5, 12, 3, 'Energy Drink', 45.00, 0, 'cans', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(208, 5, 12, 3, 'Yakult', 12.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
<<<<<<< HEAD
(209, 5, 12, 10, 'Fresh Milk (1L)', 95.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(210, 5, 12, 10, 'Chocolate Milk', 100.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
=======
(209, 5, 12, 3, 'Fresh Milk (1L)', 95.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(210, 5, 12, 3, 'Chocolate Milk', 100.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
>>>>>>> a496b0278e37441ddb3a838f8a09720c22faf329
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
<<<<<<< HEAD
(221, 5, 12, 8, 'Garlic Sauce', 160.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(222, 5, 12, 10, 'Cheese Sauce', 180.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
=======
(221, 5, 12, 4, 'Garlic Sauce', 160.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(222, 5, 12, 4, 'Cheese Sauce', 180.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
>>>>>>> a496b0278e37441ddb3a838f8a09720c22faf329
(223, 5, 12, 4, 'Tartar Sauce', 170.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(224, 5, 12, 4, 'Buffalo Sauce', 175.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(225, 5, 12, 4, 'Teriyaki Sauce', 180.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(226, 5, 12, 5, 'Palm Oil (5L)', 420.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(227, 5, 12, 5, 'Canola Oil (5L)', 500.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
<<<<<<< HEAD
(228, 5, 12, 8, 'Corn Oil (5L)', 480.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(229, 5, 12, 5, 'Coconut Oil (5L)', 550.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(230, 5, 12, 5, 'Olive Oil (1L)', 350.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(231, 5, 12, 6, 'Butter (Unsalted)', 185.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
=======
(228, 5, 12, 5, 'Corn Oil (5L)', 480.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(229, 5, 12, 5, 'Coconut Oil (5L)', 550.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(230, 5, 12, 5, 'Olive Oil (1L)', 350.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(231, 5, 12, 5, 'Butter (Unsalted)', 185.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
>>>>>>> a496b0278e37441ddb3a838f8a09720c22faf329
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
<<<<<<< HEAD
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
=======
(250, 5, 12, 7, 'White Rice (5kg)', 300.00, 0, 'bags', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(251, 5, 12, 7, 'Brown Rice (5kg)', 350.00, 0, 'bags', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(252, 5, 12, 7, 'Fried Rice Mix', 60.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(253, 5, 12, 7, 'Jasmine Rice (25kg)', 1500.00, 0, 'bags', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(254, 5, 12, 7, 'Sticky Rice', 320.00, 0, 'bags', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(255, 5, 12, 7, 'Corn Grits', 280.00, 0, 'bags', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(256, 5, 12, 8, 'Onions (Red)', 85.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(257, 5, 12, 8, 'Tomatoes', 70.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(258, 5, 12, 8, 'Cucumber', 50.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(259, 5, 12, 8, 'Bell Pepper (Green)', 90.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(260, 5, 12, 8, 'Bell Pepper (Red)', 95.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(261, 5, 12, 8, 'Celery', 85.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(262, 5, 12, 8, 'Spring Onions', 75.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(263, 5, 12, 8, 'Ginger', 95.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(264, 5, 12, 8, 'Lemon', 65.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
>>>>>>> a496b0278e37441ddb3a838f8a09720c22faf329
(265, 5, 12, 8, 'Calamansi', 70.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(266, 5, 12, 8, 'Chili (Siling Labuyo)', 150.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(267, 5, 12, 8, 'Pickles', 95.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(268, 5, 12, 8, 'Corn (Canned)', 45.00, 0, 'cans', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(269, 5, 12, 9, 'Hotdog Buns', 14.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(270, 5, 12, 9, 'Dinner Rolls', 12.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(271, 5, 12, 9, 'Pita Bread', 18.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
<<<<<<< HEAD
(272, 5, 12, 8, 'Garlic Bread', 25.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
=======
(272, 5, 12, 9, 'Garlic Bread', 25.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
>>>>>>> a496b0278e37441ddb3a838f8a09720c22faf329
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
<<<<<<< HEAD
(284, 5, 12, 10, 'Ice Cream (Chocolate)', 350.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(285, 2, 12, 10, 'Ice Cream (Strawberry)', 350.00, 50, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-12-01 13:41:58'),
(286, 5, 12, 10, 'Frozen Mozzarella Sticks', 320.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
=======
(284, 5, 12, 11, 'Ice Cream (Chocolate)', 350.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(285, 5, 12, 11, 'Ice Cream (Strawberry)', 350.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(286, 5, 12, 11, 'Frozen Mozzarella Sticks', 320.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
>>>>>>> a496b0278e37441ddb3a838f8a09720c22faf329
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
<<<<<<< HEAD
(310, 5, 12, 10, 'Eggs (Half Dozen)', 50.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(311, 5, 12, 10, 'Quail Eggs', 180.00, 0, 'tray', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(312, 5, 12, 6, 'Salted Eggs', 120.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(313, 5, 12, 10, 'Century Eggs', 150.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
=======
(310, 5, 12, 14, 'Eggs (Half Dozen)', 50.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(311, 5, 12, 14, 'Quail Eggs', 180.00, 0, 'tray', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(312, 5, 12, 14, 'Salted Eggs', 120.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(313, 5, 12, 14, 'Century Eggs', 150.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
>>>>>>> a496b0278e37441ddb3a838f8a09720c22faf329
(314, 5, 12, 15, 'Batter Mix', 110.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(315, 5, 12, 15, 'Tempura Flour', 130.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(316, 5, 12, 15, 'Seasoned Flour', 100.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(317, 5, 12, 15, 'Cake Flour', 60.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(318, 5, 12, 15, 'Bread Flour', 65.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(319, 5, 12, 16, 'Teriyaki Marinade', 185.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
<<<<<<< HEAD
(320, 5, 12, 8, 'Honey Garlic Marinade', 200.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(321, 5, 12, 16, 'Lemon Herb Marinade', 190.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(322, 5, 12, 16, 'Spicy Marinade', 175.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(323, 5, 12, 6, 'Salt Brine Mix', 85.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(324, 5, 12, 3, 'Pickle Juice', 95.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(325, 5, 12, 8, 'Corn on the Cob', 35.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
=======
(320, 5, 12, 16, 'Honey Garlic Marinade', 200.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(321, 5, 12, 16, 'Lemon Herb Marinade', 190.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(322, 5, 12, 16, 'Spicy Marinade', 175.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(323, 5, 12, 16, 'Salt Brine Mix', 85.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(324, 5, 12, 16, 'Pickle Juice', 95.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(325, 5, 12, 17, 'Corn on the Cob', 35.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
>>>>>>> a496b0278e37441ddb3a838f8a09720c22faf329
(326, 5, 12, 17, 'Baked Beans', 55.00, 0, 'cans', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(327, 5, 12, 17, 'Potato Salad', 140.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(328, 5, 12, 17, 'Garden Salad Mix', 110.00, 0, 'kg', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(329, 5, 12, 18, 'Strawberry Syrup', 185.00, 0, 'bottles', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(330, 5, 12, 18, 'Sprinkles', 65.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(331, 5, 12, 18, 'Cake Mix', 110.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
(332, 5, 12, 18, 'Cookies', 75.00, 0, 'packs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
<<<<<<< HEAD
(333, 5, 12, 10, 'Ice Cream Cones', 45.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
=======
(333, 5, 12, 18, 'Ice Cream Cones', 45.00, 0, 'pcs', 0, 0, NULL, 'active', '2025-11-29 02:18:30', '2025-11-29 02:18:30'),
>>>>>>> a496b0278e37441ddb3a838f8a09720c22faf329
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
<<<<<<< HEAD
(35, 'PO-20251129-7054', 40, 63, 6, 'approved', '2025-11-29', '2025-12-06', NULL, 9000.00, 2, '2025-11-29 03:05:39', 'Auto-created from approved purchase request', '2025-11-29 03:04:18', '2025-11-29 03:05:39'),
(36, 'PO-20251201-5995', 41, 1, 2, 'approved', '2025-12-01', '2025-12-08', NULL, 17500.00, 2, '2025-12-01 13:40:35', 'Auto-created from approved purchase request', '2025-12-01 13:40:27', '2025-12-01 13:40:35');
=======
(35, 'PO-20251129-7054', 40, 63, 6, 'approved', '2025-11-29', '2025-12-06', NULL, 9000.00, 2, '2025-11-29 03:05:39', 'Auto-created from approved purchase request', '2025-11-29 03:04:18', '2025-11-29 03:05:39');
>>>>>>> a496b0278e37441ddb3a838f8a09720c22faf329

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
<<<<<<< HEAD
(34, 35, 222, 50, 180.00, 9000.00, 0, '2025-11-29 03:04:18', '2025-11-29 03:04:18'),
(35, 36, 285, 50, 350.00, 17500.00, 0, '2025-12-01 13:40:27', '2025-12-01 13:40:27');
=======
(34, 35, 222, 50, 180.00, 9000.00, 0, '2025-11-29 03:04:18', '2025-11-29 03:04:18');
>>>>>>> a496b0278e37441ddb3a838f8a09720c22faf329

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
<<<<<<< HEAD
(40, 'PR-20251129-1540', 6, 8, 'converted_to_po', 'normal', 9000.00, '', 2, '2025-11-29 03:04:18', 63, NULL, '2025-11-29 02:56:41', '2025-11-29 03:04:18'),
(41, 'PR-20251201-8406', 2, 12, 'converted_to_po', 'high', 17500.00, '', 2, '2025-12-01 13:40:27', NULL, NULL, '2025-12-01 13:39:02', '2025-12-01 13:40:27');
=======
(40, 'PR-20251129-1540', 6, 8, 'converted_to_po', 'normal', 9000.00, '', 2, '2025-11-29 03:04:18', 63, NULL, '2025-11-29 02:56:41', '2025-11-29 03:04:18');
>>>>>>> a496b0278e37441ddb3a838f8a09720c22faf329

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
<<<<<<< HEAD
(37, 40, 222, 50, 180.00, 9000.00, '', '2025-11-29 02:56:41', '2025-11-29 02:56:41'),
(38, 41, 285, 50, 350.00, 17500.00, '', '2025-12-01 13:39:02', '2025-12-01 13:39:02');
=======
(37, 40, 222, 50, 180.00, 9000.00, '', '2025-11-29 02:56:41', '2025-11-29 02:56:41');
>>>>>>> a496b0278e37441ddb3a838f8a09720c22faf329

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

--
-- Dumping data for table `stock_transactions`
--

INSERT INTO `stock_transactions` (`id`, `product_id`, `transaction_type`, `quantity`, `reference_type`, `reference_id`, `stock_before`, `stock_after`, `is_new_stock`, `is_expired`, `is_old_stock`, `expiry_date`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
(6, 70, 'stock_in', 20, 'Needs for Other Branch', NULL, 220, 240, 1, 0, 0, '2026-02-24', NULL, 12, '2025-11-26 15:22:20', '2025-11-26 15:22:20'),
(7, 61, 'stock_out', 100, 'stock_out', NULL, 500, 400, 0, 0, 0, '2026-03-10', 'damaged: Buanggggg', 12, '2025-11-26 15:23:40', '2025-11-26 15:23:40'),
(8, 56, 'stock_out', 5, 'stock_out', NULL, 495, 490, 0, 0, 0, '2026-03-20', 'sale', 13, '2025-11-27 00:37:52', '2025-11-27 00:37:52'),
(9, 75, 'stock_in', 250, 'delivery', 19, 150, 400, 1, 0, 0, '2026-02-27', NULL, 5, '2025-11-27 02:48:39', '2025-11-27 02:48:39'),
(10, 80, 'stock_in', 80, 'delivery', 21, 400, 480, 1, 0, 0, '2026-02-24', NULL, NULL, '2025-11-27 02:55:06', '2025-11-27 02:55:06'),
(11, 74, 'stock_in', 300, 'delivery', 18, 60, 360, 1, 0, 0, '2026-02-13', NULL, NULL, '2025-11-27 02:57:13', '2025-11-27 02:57:13'),
(12, 65, 'stock_in', 350, 'delivery', 20, 300, 650, 1, 0, 0, '2026-02-20', NULL, 7, '2025-11-27 02:58:38', '2025-11-27 02:58:38'),
(13, 86, 'stock_in', 220, 'delivery', 23, 0, 220, 1, 0, 0, '2026-02-20', NULL, NULL, '2025-11-27 03:06:44', '2025-11-27 03:06:44'),
(14, 87, 'stock_in', 250, 'delivery', 22, 0, 250, 1, 0, 0, '2026-02-22', NULL, NULL, '2025-11-27 03:10:43', '2025-11-27 03:10:43'),
(15, 54, 'stock_in', 260, 'delivery', 24, 250, 510, 1, 0, 0, '2026-02-19', NULL, 15, '2025-11-27 03:16:16', '2025-11-27 03:16:16'),
(16, 88, 'stock_in', 200, 'delivery', 25, 0, 200, 1, 0, 0, '2026-02-18', NULL, 12, '2025-11-27 13:31:24', '2025-11-27 13:31:24'),
(17, 70, 'stock_out', 150, 'stock_out', NULL, 240, 90, 0, 0, 0, '2026-02-24', 'waste: Buanggggg', 12, '2025-11-27 13:33:23', '2025-11-27 13:33:23'),
(18, 96, 'stock_in', 25, 'delivery', 27, 200, 225, 1, 0, 0, '2026-11-27', NULL, 4, '2025-11-29 02:50:48', '2025-11-29 02:50:48'),
<<<<<<< HEAD
(19, 168, 'stock_in', 100, 'delivery', 29, 40, 140, 1, 0, 0, '2026-11-27', NULL, 8, '2025-11-29 02:51:06', '2025-11-29 02:51:06'),
(20, 58, 'stock_out', 100, 'stock_out', NULL, 200, 100, 0, 0, 0, '2026-01-26', 'waste', 4, '2025-11-29 11:53:33', '2025-11-29 11:53:33'),
(21, 58, 'stock_out', 100, 'stock_out', NULL, 100, 0, 0, 0, 0, '2026-01-26', 'waste', 4, '2025-11-29 11:53:33', '2025-11-29 11:53:33'),
(22, 285, 'stock_in', 50, 'delivery', 33, 0, 50, 1, 0, 0, NULL, NULL, 4, '2025-12-01 13:41:58', '2025-12-01 13:41:58');
=======
(19, 168, 'stock_in', 100, 'delivery', 29, 40, 140, 1, 0, 0, '2026-11-27', NULL, 8, '2025-11-29 02:51:06', '2025-11-29 02:51:06');
>>>>>>> a496b0278e37441ddb3a838f8a09720c22faf329

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
<<<<<<< HEAD
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
=======
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
>>>>>>> a496b0278e37441ddb3a838f8a09720c22faf329

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
<<<<<<< HEAD
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
=======
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
>>>>>>> a496b0278e37441ddb3a838f8a09720c22faf329

--
-- AUTO_INCREMENT for table `delivery_items`
--
ALTER TABLE `delivery_items`
<<<<<<< HEAD
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
=======
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
>>>>>>> a496b0278e37441ddb3a838f8a09720c22faf329

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
<<<<<<< HEAD
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=355;
=======
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=353;
>>>>>>> a496b0278e37441ddb3a838f8a09720c22faf329

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
<<<<<<< HEAD
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;
=======
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
>>>>>>> a496b0278e37441ddb3a838f8a09720c22faf329

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
<<<<<<< HEAD
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
=======
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
>>>>>>> a496b0278e37441ddb3a838f8a09720c22faf329

--
-- AUTO_INCREMENT for table `purchase_requests`
--
ALTER TABLE `purchase_requests`
<<<<<<< HEAD
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
=======
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;
>>>>>>> a496b0278e37441ddb3a838f8a09720c22faf329

--
-- AUTO_INCREMENT for table `purchase_request_items`
--
ALTER TABLE `purchase_request_items`
<<<<<<< HEAD
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
=======
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
>>>>>>> a496b0278e37441ddb3a838f8a09720c22faf329

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_transactions`
--
ALTER TABLE `stock_transactions`
<<<<<<< HEAD
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
=======
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
>>>>>>> a496b0278e37441ddb3a838f8a09720c22faf329

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

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
  ADD CONSTRAINT `fk_deliveries_scheduled_by` FOREIGN KEY (`scheduled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
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

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 09, 2025 at 08:47 PM
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
(22, '2025-09-05-082236', 'App\\Database\\Migrations\\CreateReportsTable', 'default', 'App', 1757062133, 2);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `branch_address` varchar(255) DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
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

INSERT INTO `products` (`id`, `branch_address`, `created_by`, `name`, `category`, `price`, `stock_qty`, `unit`, `min_stock`, `max_stock`, `expiry`, `status`, `created_at`, `updated_at`) VALUES
(16, 'LANANG Branch, Davao City', 1, 'Chicken Gizzard', 'Chicken Parts', NULL, 600, 'kg', 550, 600, '2025-12-26', 'active', '2025-09-02 04:16:02', '2025-09-10 02:34:47'),
(23, 'LANANG Branch, Davao City', 1, 'Chicken Back', 'Chicken Parts', NULL, 890, 'kg', 890, 890, '2026-01-23', 'active', '2025-09-02 04:25:47', '2025-09-02 04:25:47'),
(24, 'LANANG Branch, Davao City', 1, 'Chicken Head', 'Chicken Parts', NULL, 1500, 'kg', 1500, 1500, '2026-02-25', 'active', '2025-09-02 04:28:08', '2025-09-02 04:28:08'),
(30, 'LANANG Branch, Davao City', 1, 'Chicken Blood', 'Chicken Parts', NULL, 1150, 'kg', 1150, 1150, '2026-02-26', 'active', '2025-09-02 04:39:55', '2025-09-02 04:39:55'),
(31, 'AGDAO Branch, Davao City', 1, 'Whole Chicken', 'Chicken Parts', NULL, 900, 'kg', 900, 900, '2025-11-18', 'active', '2025-09-02 04:42:42', '2025-09-02 08:38:01'),
(33, 'MATINA Branch, Davao City', 1, 'Chicken Skin', 'Chicken Parts', NULL, 750, 'kg', 750, 770, '2025-11-10', 'active', '2025-09-02 08:40:29', '2025-09-02 08:40:29'),
(34, 'MATINA Branch, Davao City', 1, 'Chicken Bones', 'Chicken Parts', NULL, 150, 'kg', 800, 800, '2025-12-11', 'active', '2025-09-02 08:41:19', '2025-09-02 08:41:19'),
(36, 'LANANG Branch, Davao City', 1, 'Chicken Thigh', 'Chicken Parts', NULL, 1200, 'kg', 1200, 1200, '2025-10-25', 'active', '2025-09-02 10:23:52', '2025-09-02 10:23:52'),
(37, 'LANANG Branch, Davao City', 1, 'Whole Chicken', 'Chicken Parts', NULL, 2000, 'kg', 1500, 2000, '2026-01-05', 'active', '2025-09-05 07:41:50', '2025-09-05 07:42:08'),
(38, 'MATINA Branch, Davao City', 10, 'Whole Chicken', 'Chicken Parts', NULL, 1700, 'kg', 1700, 1700, '2025-12-22', 'active', '2025-09-08 13:12:22', '2025-09-08 13:12:22'),
(39, 'TORIL Branch, Davao City', 10, 'Chicken Breast', 'Chicken Parts', NULL, 1600, 'kg', 1600, 1680, '2025-12-10', 'active', '2025-09-08 13:12:22', '2025-09-08 13:12:22'),
(40, 'BUHANGIN Branch, Davao City', 10, 'Chicken Wings', 'Chicken Parts', NULL, 1300, 'kg', 1300, 1350, '2025-12-02', 'active', '2025-09-08 13:12:22', '2025-09-08 13:12:22'),
(41, 'AGDAO Branch, Davao City', 10, 'Chicken Drumstick', 'Chicken Parts', NULL, 1800, 'kg', 1800, 1950, '2025-12-07', 'active', '2025-09-08 13:12:22', '2025-09-08 13:12:22'),
(42, 'LANANG Branch, Davao City', 10, 'Chicken Liver', 'Chicken Parts', NULL, 1500, 'kg', 1500, 1500, '2025-12-04', 'active', '2025-09-08 13:12:22', '2025-09-08 13:12:22'),
(43, 'TORIL Branch, Davao City', 10, 'Beplop', 'Chicken Parts', NULL, 1000, 'pcs', 1000, 1150, '2025-12-01', 'active', '2025-09-08 13:12:22', '2025-09-08 13:12:22');

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
(1, NULL, 'superadmin1@chakanoks.test', '$2y$10$bqaMOQ2YyJbljUiTNcn9HuX1q/VwrQQ4hwSjG07U4dCEsNaJgGC/C', 'superadmin', NULL, '2025-09-07 14:26:26', '$2y$10$eJoYb5b3zODZpXziUiNhA.gzOOqLDFP21L6hnJy3Y4Zc2HvB9JH3e', NULL, '2025-09-06 07:47:43', '76b6e4fc36c03ea525101b97fe546012860c29abe146bd2d57ac31059caf739f', '2025-09-07 14:56:26'),
(4, 4, 'akiyorii03@gmail.com', '$2y$10$cIERYmOVmkfxTzrRTw8CNegmNq2/mPmuiyGs4ARRhLLtc4uk2dZsa', 'franchise_manager', NULL, '2025-09-08 16:08:20', '967027', '2025-09-08 16:18:20', '2025-09-06 07:36:26', '386bd7a927470b9a98a64c7520378b2f6c6fb93bed404d11e7a9a6d7538a6470', '2025-09-08 16:38:20'),
(6, 6, 'gpalagpalag@gmail.com', '$2y$10$VgySft2MXCGuEfFcF0rh7u4Y6OZ59V08uQrFwXwuQEHfiJQeBS8Z2', 'logistics_coordinator', NULL, '2025-09-07 14:16:42', NULL, NULL, NULL, '8cb3856c070f6e0e377532a8b52a1162e1976dab35fee8477bd5fdae0060cc68', '2025-09-07 14:46:41'),
(8, 1, 'mansuetomarky@gmail.com', '$2y$10$xHm7dGX66VFiY7eFXhjwX.lX9NSqduH84eTZBbDcfsFlBGB7QUMJm', 'central_admin', NULL, '2025-09-10 00:46:02', '425558', '2025-09-10 00:56:02', NULL, '44432118b9cb0236b725d6ed5a34598814759f8fe27ab2cdfa833c4d279c6f29', '2025-09-10 01:16:02'),
(9, 2, 'rualesabigail09@gmail.com', '$2y$10$fcwod5wJldbpIFa3eBqxU.rUhU.0.VLwx9YRy60UJwRpkH5IhxIge', 'branch_manager', NULL, '2025-09-10 00:45:33', '718823', '2025-09-10 00:55:33', NULL, '1cec405e38087a42030dd5efbced6ff73e9a8dabaeaec7add57d17dba8cd2c77', '2025-09-10 01:15:33'),
(10, 3, 'leo333953@gmail.com', '$2y$10$v8cgCK6Ebm6lxYQKriym0epOWZ3SiT40dHIiCPuklyuWO.3pqRW1q', 'inventory_staff', NULL, '2025-09-08 14:47:03', '252767', '2025-09-08 14:57:03', NULL, 'f389b909f370b92778485d85f34131ac4bf22b56f41de8da0f1b3ee98d8bf9d9', '2025-09-08 15:17:03');

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
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_branch_id` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 29, 2025 at 04:36 PM
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
(1, 'CENTRAL', 'Central Office', NULL, '2025-08-29 08:32:36', '2025-08-29 08:32:36'),
(2, 'MATINA', 'Matina Branch', NULL, '2025-08-29 08:32:36', '2025-08-29 08:32:36'),
(3, 'TORIL', 'Toril Branch', NULL, '2025-08-29 08:32:36', '2025-08-29 08:32:36'),
(4, 'BUHANGIN', 'Buhangin Branch', NULL, '2025-08-29 08:32:36', '2025-08-29 08:32:36'),
(5, 'AGDAO', 'Agdao Branch', NULL, '2025-08-29 08:32:36', '2025-08-29 08:32:36'),
(6, 'LANANG', 'Lanang Branch', NULL, '2025-08-29 08:32:36', '2025-08-29 08:32:36');

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
(1, '2025-08-29-054618', 'App\\Database\\Migrations\\CreateUsersTable', 'default', 'App', 1756452271, 1),
(2, '2025-08-29-054619', 'App\\Database\\Migrations\\CreateBranchesTable', 'default', 'App', 1756452271, 1),
(3, '2025-08-29-054620', 'App\\Database\\Migrations\\AlterUsersAddMoreRoles', 'default', 'App', 1756452271, 1),
(4, '2025-08-29-054621', 'App\\Database\\Migrations\\CreateProducts', 'default', 'App', 1756452271, 1),
(5, '2025-08-29-071346', 'App\\Database\\Migrations\\AddUsersBranchFK', 'default', 'App', 1756452271, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `unit` varchar(50) DEFAULT NULL,
  `min_stock` int(11) DEFAULT NULL,
  `max_stock` int(11) DEFAULT NULL,
  `expiry` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Products managed by inventory staff';

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `branch_id`, `created_by`, `name`, `category`, `price`, `stock`, `unit`, `min_stock`, `max_stock`, `expiry`, `created_at`, `updated_at`) VALUES
(7, 2, NULL, 'Whole Chicken', 'Chicken Parts', NULL, 120, 'kg', 50, 200, '2025-09-03', '2025-08-29 09:36:01', '2025-08-29 09:36:01'),
(8, 3, 17, 'Chicken Breast', 'Chicken Parts', NULL, 80, 'kg', 40, 150, '2025-09-02', '2025-08-29 09:36:01', '2025-08-29 09:36:01'),
(9, 4, NULL, 'Chicken Wings', 'Chicken Parts', NULL, 20, 'kg', 30, 120, '2025-08-31', '2025-08-29 09:36:01', '2025-08-29 09:36:01'),
(10, 5, NULL, 'Chicken Drumstick', 'Chicken Parts', NULL, 60, 'kg', 50, 200, '2025-09-04', '2025-08-29 09:36:01', '2025-08-29 09:36:01'),
(11, 6, NULL, 'Chicken Liver', 'Chicken Parts', NULL, 15, 'kg', 25, 80, '2025-08-30', '2025-08-29 09:36:01', '2025-08-29 09:36:01'),
(12, 2, NULL, 'Other', 'Chicken Parts', NULL, 5, 'pcs', 1, 20, NULL, '2025-08-29 09:36:01', '2025-08-29 09:36:01');

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
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Application users';

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `branch_id`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(13, NULL, 'superadmin1@chakanoks.test', '$2y$10$4mEGFuWTFl67.si0zBLmxursYijK6YHhsKeOWaOWaF7BLgzJ9H6RS', 'superadmin', NULL, NULL),
(14, 1, 'markypadilla04@gmail.com', '$2y$10$K.flTM2NpzrB8baFvAImPeWS7XulpHPkScG6L/krgEa0AlSVbyjZK', 'central_admin', NULL, NULL),
(15, 2, 'abbyyygarciaa@gmail.com', '$2y$10$JP4X17QQqxDjC0wfOPBocuEmNxyDEggkZSff7b8yT5Zm5v0G2D7ja', 'branch_manager', NULL, NULL),
(16, 4, 'akiyorii03@gmail.com', '$2y$10$iLh0Z7woOqQkdvPKt375He9H8P60UsRJTyKOsFQ7DSfYPTuC2EqWW', 'franchise_manager', NULL, NULL),
(17, 3, 'wakwak321@gmail.com', '$2y$10$oc.pKhm7gEc.qIembk7G8O7v.gFURPJy9q0nVMGW0rXYHxEmhLj5y', 'inventory_staff', NULL, NULL),
(18, 6, 'gpalagpalag@gmail.com', '$2y$10$foZAsJj/czF/aMjLKeUbceEQ1jbMhoBGgWGx2x/vs.neLeiVIHkLK', 'logistics_coordinator', NULL, NULL);

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
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_products_branch_id` (`branch_id`),
  ADD KEY `fk_products_created_by` (`created_by`);

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
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_branch_id` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE SET NULL,
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

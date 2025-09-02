-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 02, 2025 at 01:22 PM
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
(20, '2025-08-29-071346', 'App\\Database\\Migrations\\AddUsersBranchFK', 'default', 'App', 1756778860, 1);

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
(16, 'LANANG Branch, Davao City', 1, 'Chicken Gizzard', 'Chicken Parts', NULL, 600, 'kg', 550, 600, '2025-12-26', 'active', '2025-09-02 04:16:02', '2025-09-02 04:16:49'),
(22, 'TORIL Branch, Davao City', 5, 'Beplop', 'Chicken Parts', NULL, 1150, 'pcs', 1000, 1150, '2025-11-25', 'active', '2025-09-02 08:39:13', '2025-09-02 08:39:37'),
(23, 'LANANG Branch, Davao City', 1, 'Chicken Back', 'Chicken Parts', NULL, 890, 'kg', 890, 890, '2026-01-23', 'active', '2025-09-02 04:25:47', '2025-09-02 04:25:47'),
(24, 'LANANG Branch, Davao City', 1, 'Chicken Head', 'Chicken Parts', NULL, 1500, 'kg', 1500, 1500, '2026-02-25', 'active', '2025-09-02 04:28:08', '2025-09-02 04:28:08'),
(26, 'TORIL Branch, Davao City', 5, 'Chicken Breast', 'Chicken Parts', NULL, 1600, 'kg', 1600, 1680, '2025-12-04', 'active', '2025-09-02 08:39:13', '2025-09-02 08:39:13'),
(27, 'BUHANGIN Branch, Davao City', 5, 'Chicken Wings', 'Chicken Parts', NULL, 1300, 'kg', 1300, 1350, '2025-11-26', 'active', '2025-09-02 08:39:13', '2025-09-02 08:39:13'),
(28, 'AGDAO Branch, Davao City', 5, 'Chicken Drumstick', 'Chicken Parts', NULL, 1800, 'kg', 1800, 1950, '2025-12-01', 'active', '2025-09-02 08:39:13', '2025-09-02 08:39:13'),
(29, 'LANANG Branch, Davao City', 5, 'Chicken Liver', 'Chicken Parts', NULL, 1500, 'kg', 1500, 1500, '2025-11-28', 'active', '2025-09-02 08:39:13', '2025-09-02 08:39:13'),
(30, 'LANANG Branch, Davao City', 1, 'Chicken Blood', 'Chicken Parts', NULL, 1150, 'kg', 1150, 1150, '2026-02-26', 'active', '2025-09-02 04:39:55', '2025-09-02 04:39:55'),
(31, 'AGDAO Branch, Davao City', 1, 'Whole Chicken', 'Chicken Parts', NULL, 900, 'kg', 900, 900, '2025-11-18', 'active', '2025-09-02 04:42:42', '2025-09-02 08:38:01'),
(32, 'MATINA Branch, Davao City', 5, 'Whole Chicken', 'Chicken Parts', NULL, 1700, 'kg', 1700, 1700, '2025-12-16', 'active', '2025-09-02 08:39:13', '2025-09-02 08:39:13'),
(33, 'MATINA Branch, Davao City', 1, 'Chicken Skin', 'Chicken Parts', NULL, 750, 'kg', 750, 770, '2025-11-10', 'active', '2025-09-02 08:40:29', '2025-09-02 08:40:29'),
(34, 'MATINA Branch, Davao City', 1, 'Chicken Bones', 'Chicken Parts', NULL, 150, 'kg', 800, 800, '2025-12-11', 'active', '2025-09-02 08:41:19', '2025-09-02 08:41:19'),
(36, 'LANANG Branch, Davao City', 1, 'Chicken Thigh', 'Chicken Parts', NULL, 1200, 'kg', 1200, 1200, '2025-10-25', 'active', '2025-09-02 10:23:52', '2025-09-02 10:23:52');

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
(1, NULL, 'superadmin1@chakanoks.test', '$2y$10$ZkrgvjzkNHFlj13X.jajiek.xjgkO/HTYraGSMbJ3SxhZDpldo0sa', 'superadmin', NULL, NULL),
(2, NULL, 'markypadilla04@gmail.com', '$2y$10$SmzrsRpQaS2Df6DaWwXUbeS9uSFdY2gNd.U.DigNfR0tTtp8Ifqp6', 'central_admin', NULL, NULL),
(3, NULL, 'abbyyygarciaa@gmail.com', '$2y$10$s..Ww00x56g1WHcb4GklQuvIX.yxf.5tIs3E/lMvgzfi2APIX.0pa', 'branch_manager', NULL, NULL),
(4, NULL, 'akiyorii03@gmail.com', '$2y$10$pSpQpDFHBptkczVl.dm2yu4L5UYvZn8YiVsEfL5vcTy9Ym.6HpJTC', 'franchise_manager', NULL, NULL),
(5, NULL, 'wakwak321@gmail.com', '$2y$10$RXjuwRx.DqMtMo.GUTwcI.EMaRhRVacYGv0p6Wb24EfWQMIUlNee2', 'inventory_staff', NULL, NULL),
(6, NULL, 'gpalagpalag@gmail.com', '$2y$10$Sox3EVYd9/2kpF/ygIgOQe0wAnhdz/IFE124fCElweinhEQlWFOuK', 'logistics_coordinator', NULL, NULL);

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
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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

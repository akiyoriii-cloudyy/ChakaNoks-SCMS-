-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 24, 2025 at 08:06 PM
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `code`, `name`, `address`, `created_at`, `updated_at`) VALUES
(7, 'CENTRAL', 'Central Office', 'Poblacion District, Davao City', NULL, NULL),
(8, 'MATINA', 'Matina Branch', 'Matina, Davao City', NULL, NULL),
(9, 'TORIL', 'Toril Branch', 'Toril, Davao City', NULL, NULL),
(10, 'BUHANGIN', 'Buhangin Branch', 'Buhangin, Davao City', NULL, NULL),
(11, 'AGDAO', 'Agdao Branch', 'Agdao, Davao City', NULL, NULL),
(12, 'LANANG', 'Lanang Branch', 'Lanang, Davao City', NULL, NULL);

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
(35, '2025-08-23-075015', 'App\\Database\\Migrations\\CreateBranches', 'default', 'App', 1756051237, 1),
(37, '2025-08-23-075016', 'App\\Database\\Migrations\\CreateUsers', 'default', 'App', 1756051237, 1),
(38, '2025-08-24-132642', 'App\\Database\\Migrations\\AlterUsersAddMoreRoles', 'default', 'App', 1756051237, 1),
(39, '2025-08-24-164622', 'App\\Database\\Migrations\\CreateProductsTable', 'default', 'App', 1756054834, 2);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) UNSIGNED NOT NULL,
  `branch_id` int(11) UNSIGNED DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `branch_id`, `name`, `price`, `stock`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Chicken Inasal', 120.00, 50, NULL, NULL),
(2, NULL, 'Pork BBQ', 90.00, 100, NULL, NULL),
(3, NULL, 'Halo-Halo', 70.00, 30, NULL, NULL),
(4, NULL, 'Chicken Adobo', 150.00, 40, NULL, NULL),
(5, NULL, 'Lechon Kawali', 180.00, 25, NULL, NULL),
(6, NULL, 'Lechon Baka', 195.00, 35, NULL, NULL),
(7, 7, 'Chicken Inasal', 120.00, 50, NULL, NULL),
(8, 8, 'Pork BBQ', 90.00, 100, NULL, NULL),
(9, 9, 'Halo-Halo', 70.00, 30, NULL, NULL),
(10, 10, 'Chicken Adobo', 150.00, 40, NULL, NULL),
(11, 11, 'Lechon Kawali', 180.00, 25, NULL, NULL),
(12, 12, 'Lechon Baka', 195.00, 35, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `branch_id` int(10) UNSIGNED DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('superadmin','central_admin','branch_manager','staff','franchise_manager','logistics_coordinator','inventory_staff') DEFAULT 'branch_manager',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `branch_id`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES
(8, NULL, 'superadmin1@chakanoks.test', '$2y$10$xZkY84gvzxK/CwP0pk49PO2SwVO/RhgxTfA.hfrqRFm/LAzu4q286', 'superadmin', NULL, NULL),
(9, 7, 'central.admin@chakanoks.test', '$2y$10$rTzH.O.XFPGFpGQfCRxSQ.EWDgkqOoUVC0Il77v60Dg/bt6t89ASa', 'central_admin', NULL, NULL),
(10, 8, 'matina.manager@chakanoks.test', '$2y$10$XpwOWw/M2sPjGugls8BmReHutJKwysFSXVGmUggrmlvo1PfrZKed2', 'branch_manager', NULL, NULL),
(11, 9, 'abbywakwak.staff@chakanoks.test', '$2y$10$VwDbnMcKSipe6zCs./F4p.dDO/B0vEYxx7Mh6SM7/NfIABXScTLlu', 'staff', NULL, NULL),
(12, 10, 'Franchise.Manager@chakanoks.test', '$2y$10$rMWM7kbz7GmYS8GdtV7WdOQpw26yzUmy2WMvLMqPXlh4p.7ZrQ.vO', 'franchise_manager', NULL, NULL),
(13, 11, 'Inventory.Staff@chakanoks.test', '$2y$10$sglGQDb0lhp5f06NmCB4juoXl/X8QQD3vd5LSwOfpN.d5cCK93Rq6', 'inventory_staff', NULL, NULL),
(14, 12, 'logisticsCoordinator.staff@chakanoks.test', '$2y$10$Qeqan5S2lDNXXKT.VLKkKuI.3xAXZQq4lm3yGJwO0Ktcsd9oiudKW', 'logistics_coordinator', NULL, NULL);

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
  ADD KEY `products_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `users_branch_id_foreign` (`branch_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE ON UPDATE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

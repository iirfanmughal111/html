-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 13, 2023 at 12:14 PM
-- Server version: 8.0.34-0ubuntu0.20.04.1
-- PHP Version: 7.4.3-4ubuntu2.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jazzcash_laravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `jazzcash_transactions`
--

CREATE TABLE `jazzcash_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `txn_ref_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` json NOT NULL COMMENT 'Order data fields and values',
  `request` json NOT NULL COMMENT 'Jazzcash request data fields and values',
  `response` json DEFAULT NULL COMMENT 'Jazzcash response data fields and values',
  `status` enum('pending','error','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2021_02_13_112349_create_order_table', 2),
(4, '2021_02_14_220933_create_product_table', 2),
(5, '2021_11_30_122831_create_jazzcash_transactions_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `order_id` int UNSIGNED NOT NULL,
  `TxnRefNo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`order_id`, `TxnRefNo`, `amount`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'T20230816123623', '10', 'Description of transaction', 'pending', NULL, NULL),
(2, 'T20230816131111', '20', 'Description of transaction', 'pending', NULL, NULL),
(3, 'T20230816131208', '20', 'Description of transaction', 'pending', NULL, NULL),
(4, 'T20230816132939', '30', 'Description of transaction', 'pending', NULL, NULL),
(5, 'T20230816134320', '30', 'Description of transaction', 'pending', NULL, NULL),
(6, 'T20230816134441', '40', 'Description of transaction', 'pending', NULL, NULL),
(7, 'T20231101121016', '10', 'Description of transaction', 'pending', NULL, NULL),
(8, 'T20231101121543', '10', 'Description of transaction', 'pending', NULL, NULL),
(9, 'T20231101121658', '10', 'Description of transaction', 'pending', NULL, NULL),
(10, 'T20231101121717', '10', 'Description of transaction', 'pending', NULL, NULL),
(11, 'T20231101122050', '10', 'Description of transaction', 'pending', NULL, NULL),
(12, 'T20231101122405', '1', 'Description of transaction', 'pending', NULL, NULL),
(13, 'T20231101122508', '1', 'Description of transaction', 'pending', NULL, NULL),
(14, 'T20231101122702', '1', 'Description of transaction', 'pending', NULL, NULL),
(15, 'T20231101122835', '1', 'Description of transaction', 'pending', NULL, NULL),
(16, 'T20231101122927', '1', 'Description of transaction', 'pending', NULL, NULL),
(17, 'T20231101123030', '1', 'Description of transaction', 'pending', NULL, NULL),
(18, 'T20231101123052', '1', 'Description of transaction', 'pending', NULL, NULL),
(19, 'T20231101124401', '1', 'Description of transaction', 'pending', NULL, NULL),
(20, 'T20231101124622', '1', 'Description of transaction', 'pending', NULL, NULL),
(21, 'T20231101124643', '1', 'Description of transaction', 'pending', NULL, NULL),
(22, 'T20231101124658', '1', 'Description of transaction', 'pending', NULL, NULL),
(23, 'T20231110122327', '1', 'Description of transaction', 'pending', NULL, NULL),
(24, 'T20231110125311', '1', 'Description of transaction', 'pending', NULL, NULL),
(25, 'T20231110125355', '1', 'Description of transaction', 'pending', NULL, NULL),
(26, 'T20231110125735', '1', 'Description of transaction', 'pending', NULL, NULL),
(27, 'T20231110130520', '1', 'Description of transaction', 'pending', NULL, NULL),
(28, 'T20231110130743', '1', 'Description of transaction', 'pending', NULL, NULL),
(29, 'T20231110141330512402', '1', 'Description of transaction', 'pending', NULL, NULL),
(30, 'T20231110131447', '1', 'Description of transaction', 'pending', NULL, NULL),
(31, 'T20231113111604', '1', 'Description of transaction', 'pending', NULL, NULL),
(32, 'T20231113112331', '1', 'Description of transaction', 'pending', NULL, NULL),
(33, 'T20231113112418', '1', 'Description of transaction', 'pending', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double(8,2) NOT NULL,
  `status` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `name`, `description`, `image`, `price`, `status`) VALUES
(1, 'Mens Shoes', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy', 'images/1.jpg', 1.00, 1),
(2, 'Ladies Dress', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy', 'images/2.jpg', 20.00, 1),
(3, 'Drone 360', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy', 'images/3.jpg', 30.00, 1),
(4, 'Power Laptop', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy', 'images/4.jpg', 40.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jazzcash_transactions`
--
ALTER TABLE `jazzcash_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jazzcash_transactions`
--
ALTER TABLE `jazzcash_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `order_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

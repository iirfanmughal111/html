-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 23, 2023 at 11:02 AM
-- Server version: 8.0.35-0ubuntu0.20.04.1
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
-- Database: `laravaleTesting`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
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
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2023_05_31_071247_create_notifications_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('00f8b8bb-f4fb-43fa-8ef9-2480bd23c36c', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:32:13', '2023-06-01 04:32:13'),
('04613fb9-445a-45f7-8c65-73cc1c85b39a', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 02:17:01', '2023-06-02 02:17:01'),
('06c5aa35-8ae6-4693-903e-6a909002c1cb', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 02:47:15', '2023-06-01 02:47:15'),
('10c4c804-c110-485e-8a56-586573dd0596', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 02:50:17', '2023-06-01 02:50:17'),
('12810087-610c-4b79-bf35-6370742aa73d', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 01:51:41', '2023-06-02 01:51:41'),
('170391a6-5a5e-440d-8375-0a779b392507', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 01:52:12', '2023-06-02 01:52:12'),
('18e1d973-9880-432c-a26d-0208e72bf6d9', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 02:41:37', '2023-06-01 02:41:37'),
('1ba179c8-158d-43ad-a6df-8b92b17c0f82', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:33:30', '2023-06-01 04:33:30'),
('1be241a6-e211-419f-885a-88f7c53c124d', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:55:24', '2023-06-01 04:55:24'),
('1ec49f76-5372-4f56-a5c2-f37ecc9605d1', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:28:54', '2023-06-01 04:28:54'),
('1fabde42-3413-42cd-af4c-c0c97ff29f1b', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:37:12', '2023-06-01 04:37:12'),
('245ec017-1591-4bcc-9347-5272eb0d88d1', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 01:47:36', '2023-06-02 01:47:36'),
('2481d77d-1c84-4ccc-becd-4acbba000e7e', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-05-31 02:53:33', '2023-05-31 02:53:33'),
('2acbceb8-e862-490a-9507-6710e6d3f877', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-05-31 02:38:55', '2023-05-31 02:38:55'),
('2d18fa6f-a2b8-4cc1-b6bb-0bf4939c39bd', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:25:21', '2023-06-01 04:25:21'),
('2d2f7574-4b4a-478e-8d15-8e55e23e9a92', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-05-31 05:58:16', '2023-05-31 05:58:16'),
('2d6cb775-4241-48a6-abb9-a5dcf9ddb22c', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:56:51', '2023-06-01 04:56:51'),
('2f151520-c4c5-4b91-8637-9f2707a6a62e', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-08-15 05:52:38', '2023-08-15 05:52:38'),
('2f5f37e3-4bfd-43d1-966a-d0ea350ee3ab', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:27:16', '2023-06-01 04:27:16'),
('2f89d442-66a6-4b95-b9f2-8ca2901a72cf', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 02:19:19', '2023-06-02 02:19:19'),
('2ffa7911-0a53-479d-af25-05df4eabae0e', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:37:05', '2023-06-01 04:37:05'),
('32ba0d52-82d9-4475-bb85-0d979dfea592', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 01:11:24', '2023-06-02 01:11:24'),
('370ac883-6c60-4ae4-afe2-96cd4f107bcd', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-05-31 02:53:04', '2023-05-31 02:53:04'),
('44ee37c4-4209-4134-a784-cf834d39c3df', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-05-31 02:51:47', '2023-05-31 02:51:47'),
('488f8f68-f3a9-4eb9-9c80-91aa288bc256', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 01:47:37', '2023-06-02 01:47:37'),
('4b711fe5-2149-4253-9db5-b2cfd4680fdc', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 03:20:22', '2023-06-01 03:20:22'),
('4eec658e-9ba1-4b8c-b38c-8318b8c8c15a', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:53:32', '2023-06-01 04:53:32'),
('558d687b-e953-4600-8617-e45a71d18872', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 01:50:52', '2023-06-02 01:50:52'),
('597093a6-acce-4301-b114-5f5ded506883', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:41:41', '2023-06-01 04:41:41'),
('59cd7e06-92dc-4aad-ba4b-61d9d560b0e6', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:36:45', '2023-06-01 04:36:45'),
('5a9bd583-480e-419e-9904-2342735c9218', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 01:41:34', '2023-06-02 01:41:34'),
('5cee2c92-6562-4a1e-ad86-7770b678ba2f', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:33:01', '2023-06-01 04:33:01'),
('5d32f4df-d557-4b89-a2de-6a7c1e85870b', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:31:54', '2023-06-01 04:31:54'),
('62067a65-0dac-4519-aeb9-e861b86179e9', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:38:11', '2023-06-01 04:38:11'),
('64dfcf21-3a75-49f3-adcc-9e03f421c7cb', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 02:43:16', '2023-06-01 02:43:16'),
('65df9166-a9d8-4f60-802a-23c5cf313349', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 02:51:37', '2023-06-01 02:51:37'),
('66ad9390-5de4-4285-a678-0b88b06c8717', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 03:03:36', '2023-06-01 03:03:36'),
('690b2ceb-6659-4363-8bfa-9614fae3aee5', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 01:50:53', '2023-06-02 01:50:53'),
('69448695-772b-4ade-a652-63f4ec4e0813', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 02:32:17', '2023-06-02 02:32:17'),
('69f8c095-26bf-4801-a770-2d8658aacbc4', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 01:54:26', '2023-06-02 01:54:26'),
('6b48ff0b-8587-48ef-9f30-4423627f3f96', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:26:33', '2023-06-01 04:26:33'),
('6bef6357-5866-4c8c-8aff-2f79f9f7624e', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 02:53:09', '2023-06-01 02:53:09'),
('769628e7-876e-476f-9971-19ad383127e6', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-05-31 04:07:15', '2023-05-31 04:07:15'),
('796f5595-1873-4abb-8f6b-be9b94e0a2f6', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 00:59:15', '2023-06-02 00:59:15'),
('7caa9ea0-6e5c-4fc9-a8c3-856adc479483', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 02:46:02', '2023-06-01 02:46:02'),
('817a079d-0136-4024-ba86-00e257c2bbe1', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 01:01:24', '2023-06-02 01:01:24'),
('87fa77a2-a719-41d4-a81b-78d5d71773b4', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 02:43:46', '2023-06-01 02:43:46'),
('8b1fa4d9-4b82-44e6-8cb5-aa926e0bbc34', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:25:38', '2023-06-01 04:25:38'),
('8b7e695b-a419-46d1-a403-dc16c1dc56c4', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:24:37', '2023-06-01 04:24:37'),
('8bd0c1ed-5217-4ee0-b7c4-8f266b67c2f5', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-05-31 02:53:52', '2023-05-31 02:53:52'),
('8dbbb356-5a2f-4ad8-8394-02fe76422667', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:29:57', '2023-06-01 04:29:57'),
('91fce128-4cc2-4693-845d-a6f16c996770', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 01:39:56', '2023-06-02 01:39:56'),
('922351a8-ec4e-4779-9f6b-46580685fa80', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 01:39:58', '2023-06-02 01:39:58'),
('961c333d-ad89-4d15-929e-6a5d89974614', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-08-15 05:26:51', '2023-08-15 05:26:51'),
('97256693-4b55-4fe0-88dc-c2dbcf28be8a', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 03:13:24', '2023-06-01 03:13:24'),
('99034788-1b2a-4389-88db-cc4ad1458b3b', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 01:52:53', '2023-06-02 01:52:53'),
('9e1e3b79-f5c7-4581-92ae-c2e0cc291be5', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 01:17:59', '2023-06-02 01:17:59'),
('a1e596a3-18a8-4ac3-9397-d2fa64e90568', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 02:46:20', '2023-06-01 02:46:20'),
('a377e761-2d6a-49d1-8c76-fae0be917df1', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:37:20', '2023-06-01 04:37:20'),
('a44790f5-5f12-4738-a0f0-763a5e449e69', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 01:52:54', '2023-06-02 01:52:54'),
('ac3fa3ef-08bc-47af-a16b-362c3c6794e6', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-05-31 04:08:23', '2023-05-31 04:08:23'),
('ace02ace-94af-480b-81ce-645a9c438a71', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:40:23', '2023-06-01 04:40:23'),
('ada14d6f-e728-4f1d-86fd-a508f4f185be', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:30:20', '2023-06-01 04:30:20'),
('b074f6ea-5c4e-4ce2-bf3b-c7adcd3d2ee8', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 02:42:09', '2023-06-01 02:42:09'),
('b25e2dfc-cfb5-45d2-9fc2-8f8f472567bc', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:27:01', '2023-06-01 04:27:01'),
('b484a257-6843-4514-ab7d-96502e7e057e', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:24:59', '2023-06-01 04:24:59'),
('b4a22352-0623-41e4-a90d-5cba13a9b784', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 01:51:40', '2023-06-02 01:51:40'),
('b6773af9-c5cf-478d-b129-e5cf5ca17e56', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:43:53', '2023-06-01 04:43:53'),
('bccacbd3-c6d6-4882-9fb1-582646ba6ad3', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-05-31 02:53:59', '2023-05-31 02:53:59'),
('bde545d7-460e-4830-b88f-4168bbe52368', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 01:52:29', '2023-06-02 01:52:29'),
('bdfdea1c-feb1-45ed-8817-9f8b3d2052d6', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:31:09', '2023-06-01 04:31:09'),
('be326e33-aac8-40a0-a591-87216245d43d', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 01:41:35', '2023-06-02 01:41:35'),
('c0a191a1-efee-4fb6-9524-279f9c9374e2', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-05-31 02:53:14', '2023-05-31 02:53:14'),
('c5836cd7-5a6c-442b-af39-203c0439ce5a', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-05-31 02:52:04', '2023-05-31 02:52:04'),
('c81cb892-ff24-440f-a378-c3f75e96f1da', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:28:15', '2023-06-01 04:28:15'),
('c9b49370-11c7-49b1-ba7a-628c731de9de', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 02:56:19', '2023-06-01 02:56:19'),
('cabd76be-8b65-4b7f-b94a-4118d8de82c4', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 02:18:15', '2023-06-02 02:18:15'),
('cd15b56c-89e6-4b0f-b3ae-819a06fc0d4c', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:25:06', '2023-06-01 04:25:06'),
('d163b043-efe0-4021-9aa0-1a14f62c7a72', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:30:46', '2023-06-01 04:30:46'),
('d37a6127-a579-411b-8942-9bf949b8bbda', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 01:00:42', '2023-06-02 01:00:42'),
('d5ba6332-01e1-4328-adc3-4fa57d021c0c', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 01:05:07', '2023-06-02 01:05:07'),
('d5ef9a7f-529c-4f08-bd68-6c90dd24f1bd', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 01:52:11', '2023-06-02 01:52:11'),
('d69e339a-c3b0-4e14-a6b4-ec36701b7156', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:37:57', '2023-06-01 04:37:57'),
('d92a9197-9de1-42da-b414-7af47f5c34c6', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-05-31 06:00:35', '2023-05-31 06:00:35'),
('d9676e72-edc9-4e62-b7ce-7e2efbedd940', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-05-31 02:51:30', '2023-05-31 02:51:30'),
('d9be056e-fc17-44fc-942b-546217f9f392', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:55:10', '2023-06-01 04:55:10'),
('da0ca9aa-1e5c-4cd6-9a43-cc188b86a7a6', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 02:32:00', '2023-06-02 02:32:00'),
('db91fa8d-3fe5-4c56-9edc-0e0c78224aed', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 02:45:35', '2023-06-01 02:45:35'),
('dbfc3257-2e1c-4ab8-9d65-0b05bf50003a', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 02:51:45', '2023-06-01 02:51:45'),
('de328121-e969-4129-98b4-1fef760bf62a', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 02:53:23', '2023-06-01 02:53:23'),
('dfe505fb-8cef-4796-9773-fe6e6fc91fb1', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-15 01:41:27', '2023-06-15 01:41:27'),
('e1f6e43b-d519-418b-a3e5-74f1c289e61c', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:56:03', '2023-06-01 04:56:03'),
('e2cf9a93-1707-4a25-a1b4-bfa0d91be98a', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 02:54:24', '2023-06-01 02:54:24'),
('e3e6f1e9-0026-4455-a245-06b95aa02c00', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 01:50:26', '2023-06-02 01:50:26'),
('e5160cbd-8aa6-4346-ac25-9da581fa2781', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:26:14', '2023-06-01 04:26:14'),
('e5f16caf-a7d5-424e-b061-4b74465a8859', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:33:19', '2023-06-01 04:33:19'),
('eac3fa37-2402-48b4-8921-1262b7478f10', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 02:28:47', '2023-06-02 02:28:47'),
('eba35db7-c2d8-43cf-93e6-640ab7a8095a', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-05-31 02:51:40', '2023-05-31 02:51:40'),
('ebdf5825-b919-4461-917d-93053773261e', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 01:50:27', '2023-06-02 01:50:27'),
('f0b3c5fb-5be0-4af0-be4a-667e2dce97e2', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 02:54:25', '2023-06-01 02:54:25'),
('f3a7f2df-fec9-4cc7-8e4e-5398cdab9572', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 02:52:11', '2023-06-01 02:52:11'),
('f41617c4-f080-46b8-aad1-5071ee2e459a', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 04:42:16', '2023-06-01 04:42:16'),
('f757c263-7116-4bef-8e93-2d91329739ff', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 01:52:28', '2023-06-02 01:52:28'),
('f7c19a89-0819-431a-9f28-69bad4c41088', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-05-31 04:08:35', '2023-05-31 04:08:35'),
('f8f1a391-e00c-449c-a1b7-7cbfdec9c08f', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 01:55:02', '2023-06-02 01:55:02'),
('f9974c7b-0fce-4cdd-9ef6-b17f02f8b397', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 00:59:59', '2023-06-02 00:59:59'),
('fa475f8a-1e8d-4188-86c8-228b36b42ef3', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-01 02:43:01', '2023-06-01 02:43:01'),
('fecdcd3b-fb01-420f-b630-bf51dde30e74', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 00:58:28', '2023-06-02 00:58:28'),
('fee1ca00-aa73-4c8d-9a67-4f83fc6dfc00', 'App\\Notifications\\DepositSuccessful', 'App\\Models\\User', 4, '{\"data\":\" Your deposit of 500 was successful\",\"data2\":\" Your deposit of 500 was successful in peviously\"}', NULL, '2023-06-02 01:02:54', '2023-06-02 01:02:54');

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
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'index.php', 'softhouse8219@gmail.com', NULL, 'admin123', NULL, '2023-05-31 02:29:37', '2023-05-31 02:29:37'),
(2, 'dsdfsd', 'dsfsf@dsfsd.dfd', NULL, 'user123', NULL, '2023-05-31 02:37:55', '2023-05-31 02:37:55'),
(4, 'dfdf', 'iiu@wew.sdsd', NULL, 'user123', NULL, '2023-05-31 02:38:55', '2023-05-31 02:38:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

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
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

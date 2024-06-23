-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2020 at 09:38 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.2.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `greenhomes`
--

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

CREATE TABLE `documents` (
  `id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `title` varchar(1000) DEFAULT NULL,
  `rental_unit_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `created_at`, `updated_at`, `deleted_at`, `title`, `rental_unit_id`) VALUES
(9, '2020-04-15 05:17:19', '2020-04-15 05:17:19', NULL, 'CNIC', 5),
(10, '2020-06-29 05:22:58', '2020-06-29 05:22:58', NULL, 'agreement', 7),
(12, '2020-09-14 17:27:38', '2020-09-14 17:27:38', NULL, 'agreement', 15),
(13, '2020-09-15 11:32:25', '2020-09-15 11:32:25', NULL, 'agreement', 16),
(14, '2020-09-15 11:35:39', '2020-09-15 11:35:39', NULL, 'agreement', 17),
(15, '2020-09-15 11:38:26', '2020-09-15 11:38:26', NULL, 'agreement', 18),
(17, '2020-09-15 11:46:33', '2020-09-15 11:46:33', NULL, 'agreement', 20),
(18, '2020-09-15 11:49:30', '2020-09-15 11:49:30', NULL, 'agreement', 21),
(19, '2020-09-15 11:57:58', '2020-09-15 11:57:58', NULL, 'agreement', 22),
(20, '2020-09-15 12:01:14', '2020-09-15 12:01:14', NULL, 'agreement', 23),
(21, '2020-09-15 12:04:24', '2020-09-15 12:04:24', NULL, 'agreement', 24),
(22, '2020-09-15 12:12:23', '2020-09-15 12:12:23', NULL, 'agreement 17-G back', 24),
(23, '2020-09-15 12:15:16', '2020-09-15 12:15:16', NULL, 'agreement BACK', 23),
(24, '2020-09-15 13:06:35', '2020-09-15 13:06:35', NULL, 'agreement BACK', 21),
(25, '2020-09-15 13:08:06', '2020-09-15 13:08:06', NULL, 'agreement BACK', 20),
(27, '2020-09-15 13:17:14', '2020-09-15 13:17:14', NULL, 'agreement BACK', 19),
(29, '2020-09-15 14:47:59', '2020-09-15 14:47:59', NULL, 'agreement', 25),
(30, '2020-09-15 14:50:04', '2020-09-15 14:50:04', NULL, 'agreement', 26),
(31, '2020-09-15 14:51:46', '2020-09-15 14:51:46', NULL, 'agreement', 27),
(32, '2020-09-15 15:07:14', '2020-09-15 15:07:14', NULL, 'agreement', 28),
(33, '2020-09-15 15:33:00', '2020-09-15 15:33:00', NULL, 'agreement', 19);

-- --------------------------------------------------------

--
-- Table structure for table `floors`
--

CREATE TABLE `floors` (
  `id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `floor_name` varchar(1000) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `image` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `floors`
--

INSERT INTO `floors` (`id`, `created_at`, `updated_at`, `deleted_at`, `floor_name`, `project_id`, `image`) VALUES
(25, '2020-10-26 03:12:55', '2020-10-26 03:12:55', NULL, 'A Block', 16, '92434385_1235674173305720_6671634492018393088_n.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `installments`
--

CREATE TABLE `installments` (
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `id` int(11) NOT NULL,
  `sale_id` int(11) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `monthly_installment` varchar(512) DEFAULT NULL,
  `total_sale_amount` varchar(512) DEFAULT NULL,
  `receive_amount` varchar(512) DEFAULT NULL,
  `pending_amount` varchar(512) DEFAULT NULL,
  `receiving_date` date DEFAULT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `received_by` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT 3,
  `agent_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `installments`
--

INSERT INTO `installments` (`created_at`, `updated_at`, `deleted_at`, `id`, `sale_id`, `unit_id`, `monthly_installment`, `total_sale_amount`, `receive_amount`, `pending_amount`, `receiving_date`, `tenant_id`, `received_by`, `status`, `agent_id`) VALUES
('2020-10-26 10:34:38', '2020-10-26 10:34:38', NULL, 28, 22, 44, '433333.3333333333', '15000000', '0', '13000000', '2020-10-26', 42, NULL, 1, NULL),
('2020-10-26 10:45:06', '2020-10-26 10:45:06', NULL, 29, 22, 44, '433333.3333333333', '15000000', '1000', '12999000', '2020-10-26', 42, NULL, 1, NULL),
('2020-10-27 11:35:01', '2020-10-27 11:35:01', NULL, 30, 36, 51, '187500', '8000000', '187500', '7312500', '2020-10-27', 40, NULL, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int(11) NOT NULL,
  `media_type` varchar(512) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `floor_id` int(11) DEFAULT NULL,
  `document_id` int(11) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `picture` varchar(1000) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `media_type`, `project_id`, `tenant_id`, `floor_id`, `document_id`, `unit_id`, `picture`, `created_at`, `updated_at`, `deleted_at`) VALUES
(35, '1', NULL, NULL, 5, NULL, 0, 'uti ground floor 001.jpg', '2020-06-30 02:55:46', '2020-06-30 02:55:46', NULL),
(36, '1', NULL, NULL, 6, NULL, 0, 'uti mezz floor 001.jpg', '2020-06-30 02:56:07', '2020-06-30 02:56:07', NULL),
(37, '1', NULL, NULL, 18, NULL, 0, 'utc map 001.jpg', '2020-06-30 03:00:08', '2020-06-30 03:00:08', NULL),
(38, '1', 8, NULL, NULL, NULL, 0, 'PROJECT UT1 001.jpg', '2020-06-30 03:13:39', '2020-06-30 03:13:39', NULL),
(39, '1', 13, NULL, NULL, NULL, 0, 'PROJECT UTC 001.jpg', '2020-06-30 03:14:04', '2020-06-30 03:14:04', NULL),
(40, '1', NULL, NULL, 19, NULL, 0, 'UTC GROUND 001.jpg', '2020-06-30 04:07:36', '2020-06-30 04:07:36', NULL),
(41, '1', 12, NULL, NULL, NULL, 0, 'PROJECT UT-ISLAMABAD 001.jpg', '2020-06-30 04:10:26', '2020-06-30 04:10:26', NULL),
(42, '1', 9, NULL, NULL, NULL, 0, 'UTII PROJECT 001.jpg', '2020-06-30 04:22:41', '2020-06-30 04:22:41', NULL),
(43, '1', 11, NULL, NULL, NULL, 0, 'VT PROJECT 001.jpg', '2020-06-30 04:23:08', '2020-06-30 04:23:08', NULL),
(44, '1', 11, NULL, NULL, NULL, 0, 'VT PROJECT 001.jpg', '2020-06-30 04:25:42', '2020-06-30 04:25:42', NULL),
(45, '1', 9, NULL, NULL, NULL, 0, 'UTII PROJECT 001.jpg', '2020-06-30 05:08:47', '2020-06-30 05:08:47', NULL),
(46, '1', 12, NULL, NULL, NULL, 0, 'PROJECT UT-ISLAMABAD 001.jpg', '2020-06-30 05:09:37', '2020-06-30 05:09:37', NULL),
(47, '1', 13, NULL, NULL, NULL, 0, 'PROJECT UTC 001.jpg', '2020-06-30 05:10:14', '2020-06-30 05:10:14', NULL),
(48, '1', NULL, NULL, 4, NULL, 0, 'uti lg floor 001.jpg', '2020-06-30 05:10:50', '2020-06-30 05:10:50', NULL),
(49, '1', NULL, NULL, 5, NULL, 0, 'uti ground floor 001.jpg', '2020-06-30 05:11:15', '2020-06-30 05:11:15', NULL),
(50, '1', NULL, NULL, 6, NULL, 0, 'uti mezz floor 001.jpg', '2020-06-30 05:11:42', '2020-06-30 05:11:42', NULL),
(51, '1', NULL, NULL, 18, NULL, 0, 'utc map 001.jpg', '2020-06-30 05:12:45', '2020-06-30 05:12:45', NULL),
(52, '1', NULL, NULL, 19, NULL, 0, 'UTC GROUND 001.jpg', '2020-06-30 05:13:55', '2020-06-30 05:13:55', NULL),
(53, '1', NULL, 19, NULL, NULL, 0, '2+3 001.jpg', '2020-07-29 13:42:34', '2020-07-29 13:42:34', NULL),
(54, '1', NULL, 20, NULL, NULL, 0, '2+3 001.jpg', '2020-07-29 13:43:20', '2020-07-29 13:43:20', NULL),
(55, '1', NULL, 21, NULL, NULL, 0, '1+14 G-UTII AGREEMENT 001.jpg', '2020-07-29 13:44:17', '2020-07-29 13:44:17', NULL),
(56, '1', NULL, 22, NULL, NULL, 0, '11-G AGREEMENT FRONT 001.jpg', '2020-07-29 13:45:19', '2020-07-29 13:45:19', NULL),
(57, '1', NULL, 23, NULL, NULL, 0, '18-G AGREEMENT FRONT 001.jpg', '2020-07-29 13:46:21', '2020-07-29 13:46:21', NULL),
(58, '1', NULL, 24, NULL, NULL, 0, '19-G UTII AGREEMENT 001.jpg', '2020-07-29 13:48:45', '2020-07-29 13:48:45', NULL),
(59, '1', NULL, 25, NULL, NULL, 0, '16-G UTII AGREEMENT FRONT 001.jpg', '2020-07-29 13:49:52', '2020-07-29 13:49:52', NULL),
(61, '2', NULL, NULL, NULL, 12, 0, '2+3 001.jpg', '2020-09-14 17:27:38', '2020-09-14 17:27:38', NULL),
(62, '2', NULL, NULL, NULL, 13, 0, '2+3 001.jpg', '2020-09-15 11:32:25', '2020-09-15 11:32:25', NULL),
(63, '2', NULL, NULL, NULL, 14, 0, '1+14 G-UTII AGREEMENT 001.jpg', '2020-09-15 11:35:39', '2020-09-15 11:35:39', NULL),
(64, '2', NULL, NULL, NULL, 15, 0, '1+14 G-UTII AGREEMENT 001.jpg', '2020-09-15 11:38:26', '2020-09-15 11:38:26', NULL),
(66, '2', NULL, NULL, NULL, 17, 0, '18-G AGREEMENT FRONT 001.jpg', '2020-09-15 11:46:33', '2020-09-15 11:46:33', NULL),
(67, '2', NULL, NULL, NULL, 18, 0, '19-G UTII AGREEMENT 001.jpg', '2020-09-15 11:49:30', '2020-09-15 11:49:30', NULL),
(68, '2', NULL, NULL, NULL, 19, 0, '16-G UTII AGREEMENT FRONT 001.jpg', '2020-09-15 11:57:58', '2020-09-15 11:57:58', NULL),
(69, '2', NULL, NULL, NULL, 20, 0, '15-G FRONT 001.jpg', '2020-09-15 12:01:14', '2020-09-15 12:01:14', NULL),
(70, '2', NULL, NULL, NULL, 21, 0, '17-G UTII AGREEMENT FRONT 001.jpg', '2020-09-15 12:04:24', '2020-09-15 12:04:24', NULL),
(71, '2', NULL, NULL, NULL, 22, 0, '17-G BACK 001.jpg', '2020-09-15 12:12:23', '2020-09-15 12:12:23', NULL),
(72, '2', NULL, NULL, NULL, 23, 0, '15-G BACK 001.jpg', '2020-09-15 12:15:16', '2020-09-15 12:15:16', NULL),
(73, '2', NULL, NULL, NULL, 24, 0, '19-G AGREEMENT BACK 001.jpg', '2020-09-15 13:06:35', '2020-09-15 13:06:35', NULL),
(74, '2', NULL, NULL, NULL, 25, 0, '18-G AGREEMENT BACK 001.jpg', '2020-09-15 13:08:06', '2020-09-15 13:08:06', NULL),
(76, '2', NULL, NULL, NULL, 27, 0, '11-G UTII BACK 001.jpg', '2020-09-15 13:17:14', '2020-09-15 13:17:14', NULL),
(78, '2', NULL, NULL, NULL, 29, 0, '15-K 3RD OWNER FRONT 001.jpg', '2020-09-15 14:47:59', '2020-09-15 14:47:59', NULL),
(79, '2', NULL, NULL, NULL, 30, 0, '15-K 2ND TENANT FRONT 001.jpg', '2020-09-15 14:50:04', '2020-09-15 14:50:04', NULL),
(80, '2', NULL, NULL, NULL, 31, 0, '15-K 3RD OWNER FRONT 001.jpg', '2020-09-15 14:51:46', '2020-09-15 14:51:46', NULL),
(81, '2', NULL, NULL, NULL, 32, 0, '14-K AGREEMENT FRONT 001.jpg', '2020-09-15 15:07:15', '2020-09-15 15:07:15', NULL),
(82, '2', NULL, NULL, NULL, 33, NULL, '11-G FRONT 001.jpg', '2020-09-15 15:33:00', '2020-09-15 15:33:00', NULL),
(83, '1', NULL, NULL, NULL, NULL, 25, '01-G UTII MAP 001.jpg', '2020-09-16 12:11:32', '2020-09-16 12:11:32', NULL),
(84, '1', NULL, NULL, NULL, NULL, 24, '03-G UTII MAP 001.jpg', '2020-09-16 12:13:22', '2020-09-16 12:13:22', NULL),
(85, '1', NULL, NULL, NULL, NULL, 23, '02-G UTII MAP 001.jpg', '2020-09-16 13:19:38', '2020-09-16 13:19:38', NULL),
(86, '1', NULL, NULL, NULL, NULL, 27, '11-G UTII MAP 001.jpg', '2020-09-16 13:22:45', '2020-09-16 13:22:45', NULL),
(88, '1', NULL, NULL, NULL, NULL, 33, '14-K LG UTII MAP 001.jpg', '2020-09-16 13:25:39', '2020-09-16 13:25:39', NULL),
(89, '1', NULL, NULL, NULL, NULL, 26, '14-G UTII MAP 001.jpg', '2020-09-16 13:26:43', '2020-09-16 13:26:43', NULL),
(90, '1', NULL, NULL, NULL, NULL, 31, '15-G UTII MAP 001.jpg', '2020-09-16 13:28:27', '2020-09-16 13:28:27', NULL),
(91, '1', NULL, NULL, NULL, NULL, 34, '15-K LG UTII MAP 001.jpg', '2020-09-16 13:29:29', '2020-09-16 13:29:29', NULL),
(92, '1', NULL, NULL, NULL, NULL, 30, '16-G UTII MAP 001.jpg', '2020-09-16 13:30:36', '2020-09-16 13:30:36', NULL),
(93, '1', NULL, NULL, NULL, NULL, 32, '17-G UTII MAP 001.jpg', '2020-09-16 13:32:30', '2020-09-16 13:32:30', NULL),
(94, '1', NULL, NULL, NULL, NULL, 28, '18-G UTII MAP 001.jpg', '2020-09-16 13:34:09', '2020-09-16 13:34:09', NULL),
(95, '1', NULL, NULL, NULL, NULL, 29, '19-G UTI MAP 001.jpg', '2020-09-16 13:35:26', '2020-09-16 13:35:26', NULL),
(96, '1', NULL, 35, NULL, NULL, NULL, '19-G AGREEMENT OLD 001.jpg', '2020-09-16 15:32:55', '2020-09-16 15:32:55', NULL),
(97, '1', NULL, 32, NULL, NULL, NULL, 'pic tan 001.jpg', '2020-09-17 09:39:27', '2020-09-17 09:39:27', NULL),
(98, '1', NULL, 32, NULL, NULL, NULL, 'pic tan 001.jpg', '2020-09-17 09:40:20', '2020-09-17 09:40:20', NULL),
(99, '1', NULL, 32, NULL, NULL, NULL, 'pic tan 001.jpg', '2020-09-17 09:41:08', '2020-09-17 09:41:08', NULL),
(100, '1', NULL, 19, NULL, NULL, NULL, 'pic tan 001.jpg', '2020-09-17 09:41:55', '2020-09-17 09:41:55', NULL),
(101, '1', NULL, 19, NULL, NULL, NULL, 'pic tan 001.jpg', '2020-09-17 09:42:45', '2020-09-17 09:42:45', NULL),
(102, '1', NULL, 19, NULL, NULL, NULL, 'pic tan 001.jpg', '2020-09-17 09:49:14', '2020-09-17 09:49:14', NULL),
(103, '1', NULL, 19, NULL, NULL, NULL, 'pic tan 001.jpg', '2020-09-17 11:07:57', '2020-09-17 11:07:57', NULL),
(104, '1', NULL, 22, NULL, NULL, NULL, 'pic tan 001.jpg', '2020-09-17 12:06:29', '2020-09-17 12:06:29', NULL),
(105, '1', NULL, 36, NULL, NULL, NULL, '92434385_1235674173305720_6671634492018393088_n.jpg', '2020-10-14 08:48:46', '2020-10-14 08:48:46', NULL),
(106, '1', 14, NULL, NULL, NULL, NULL, '27888388.jpg', '2020-10-14 09:24:12', '2020-10-14 09:24:12', NULL),
(107, '1', NULL, 38, NULL, NULL, NULL, 'menu2.jpg', '2020-10-16 11:43:24', '2020-10-16 11:43:24', NULL),
(108, '1', 15, NULL, NULL, NULL, NULL, 'menu2.jpg', '2020-10-23 05:40:00', '2020-10-23 05:40:00', NULL),
(109, '1', 16, NULL, NULL, NULL, NULL, 'WhatsApp Image 2020-10-21 at 7.55.35 PM.jpeg', '2020-10-26 03:08:20', '2020-10-26 03:08:20', NULL),
(110, '1', 17, NULL, NULL, NULL, NULL, 'menu2.jpg', '2020-10-26 03:09:24', '2020-10-26 03:09:24', NULL),
(111, '1', 18, NULL, NULL, NULL, NULL, '92434385_1235674173305720_6671634492018393088_n.jpg', '2020-10-26 03:10:36', '2020-10-26 03:10:36', NULL),
(112, '1', 19, NULL, NULL, NULL, NULL, '27888388.jpg', '2020-10-26 03:11:31', '2020-10-26 03:11:31', NULL),
(113, '1', NULL, NULL, 25, NULL, NULL, '92434385_1235674173305720_6671634492018393088_n.jpg', '2020-10-26 03:12:55', '2020-10-26 03:12:55', NULL),
(114, '1', NULL, 40, NULL, NULL, NULL, 'WhatsApp Image 2020-10-14 at 10.01.50 PM.jpeg', '2020-10-26 05:39:24', '2020-10-26 05:39:24', NULL),
(115, '1', NULL, 41, NULL, NULL, NULL, 'menu.jpg', '2020-10-26 05:42:07', '2020-10-26 05:42:07', NULL),
(116, '1', NULL, 42, NULL, NULL, NULL, 'WhatsApp Image 2020-09-30 at 7.04.43 PM.jpeg', '2020-10-26 05:43:43', '2020-10-26 05:43:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu_options`
--

CREATE TABLE `menu_options` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `route` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_options`
--

INSERT INTO `menu_options` (`id`, `title`, `parent_id`, `created_at`, `updated_at`, `deleted_at`, `route`, `icon`, `priority`) VALUES
(1, 'Dashboard', NULL, NULL, NULL, NULL, 'admin.home', 'fas fa-tachometer-alt', 1),
(2, 'User Management', NULL, NULL, NULL, NULL, NULL, 'fas fa-users nav-icon', 2),
(3, 'Permissions', 2, NULL, NULL, NULL, 'admin.permissions.index', 'fas fa-unlock-alt nav-icon', NULL),
(4, 'Roles', 2, NULL, NULL, NULL, 'admin.roles.index', 'fas fa-users nav-icon', NULL),
(5, 'Users', 2, NULL, NULL, NULL, 'admin.users.index', 'fas fa-users nav-icon', NULL),
(6, 'Faculty Members', NULL, NULL, NULL, NULL, 'admin.faculty-members.index', 'fas fa-users nav-icon', 3),
(7, 'Products', NULL, NULL, NULL, NULL, 'admin.products.index', 'fas fa-users nav-icon', 4),
(8, 'Course Categories', NULL, NULL, NULL, NULL, 'admin.course-categories.index', 'fas fa-users nav-icon', 5),
(9, 'Courses', NULL, NULL, NULL, NULL, 'admin.courses.index', 'fas fa-users nav-icon', 6),
(10, 'News', NULL, NULL, NULL, NULL, 'admin.news.index', 'fa fa-newspaper-o nav-icon', 7),
(11, 'Jobs', NULL, NULL, NULL, NULL, 'admin.jobs.index', 'fa fa-suitcase nav-icon', 8),
(12, 'Designations', NULL, NULL, NULL, NULL, 'admin.designations.index', 'fa fa-suitcase nav-icon', 9),
(13, 'Faculties', NULL, NULL, NULL, NULL, 'admin.faculties.index', 'fa fa-suitcase nav-icon', 10),
(14, 'Departments', NULL, NULL, NULL, NULL, 'admin.departments.index', 'fa fa-suitcase nav-icon', 11),
(15, 'Societies', NULL, NULL, NULL, NULL, 'admin.projects.index', 'fa fa-home nav-icon', 12),
(16, 'Blocks', NULL, NULL, NULL, NULL, 'admin.floors.index', 'fa fa-building-o nav-icon', 13),
(17, 'Plots', NULL, NULL, NULL, NULL, 'admin.units.index', 'fa fa-th-large nav-icon', 14),
(18, 'Customers', NULL, NULL, NULL, NULL, 'admin.tenants.index', 'fa fa-user nav-icon', 15),
(19, 'Rental Units', NULL, NULL, NULL, NULL, 'admin.rentalunits.index', 'fa fa-industry nav-icon', 16),
(20, 'Monthly Rent', NULL, NULL, NULL, NULL, 'admin.monthlyrents.index', 'fa fa-calendar nav-icon', 17),
(21, 'Reports', NULL, NULL, NULL, NULL, '', 'fa fa-file nav-icon', 19),
(22, 'Pending Rents', 21, NULL, NULL, NULL, 'show-pending-rents', 'fa fa-suitcase nav-icon', 19),
(23, 'Empty Units', 21, NULL, NULL, NULL, 'show-empty-units', 'fa fa-suitcase nav-icon', 19),
(24, 'Rental Units List', 21, NULL, NULL, NULL, 'show-rental-units-list', 'fa fa-suitcase nav-icon', 19),
(25, 'Paid Rents', 21, NULL, NULL, NULL, 'show-paid-rents', 'fa fa-suitcase nav-icon', 20),
(26, 'Sales', NULL, NULL, NULL, NULL, 'admin.sales.index', 'fa fa-chart-line nav-icon', 6),
(27, 'Installments', NULL, NULL, NULL, NULL, 'admin.installments.index', 'fa fa-dollar nav-icon', 9),
(28, 'Cash Sales', 21, NULL, NULL, NULL, 'cash-sales', 'fa fa-suitcase nav-icon', 20),
(29, 'Credit Sales', 21, NULL, NULL, NULL, 'credit-sales', 'fa fa-suitcase nav-icon', 20);

-- --------------------------------------------------------

--
-- Table structure for table `menu_options_role`
--

CREATE TABLE `menu_options_role` (
  `id` int(11) NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `menu_option_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_options_role`
--

INSERT INTO `menu_options_role` (`id`, `role_id`, `menu_option_id`, `created_at`, `updated_at`, `deleted_at`, `updated_by`) VALUES
(1, 1, 1, NULL, NULL, NULL, NULL),
(4, 1, 15, NULL, NULL, NULL, NULL),
(5, 1, 16, NULL, NULL, NULL, NULL),
(6, 1, 17, NULL, NULL, NULL, NULL),
(7, 1, 18, NULL, NULL, NULL, NULL),
(10, 1, 21, NULL, NULL, NULL, NULL),
(11, 1, 26, NULL, NULL, NULL, NULL),
(12, 1, 27, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `monthly_maintenace`
--

CREATE TABLE `monthly_maintenace` (
  `id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `monthly_rents`
--

CREATE TABLE `monthly_rents` (
  `id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `monthly_rent` varchar(1000) DEFAULT NULL,
  `received_payment` varchar(512) DEFAULT NULL,
  `payment_date` date DEFAULT NULL,
  `receipt_number` varchar(512) DEFAULT NULL,
  `payment_id` int(11) DEFAULT NULL,
  `month` varchar(1000) DEFAULT NULL,
  `year` varchar(1000) DEFAULT NULL,
  `status` int(11) DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `monthly_rents`
--

INSERT INTO `monthly_rents` (`id`, `created_at`, `updated_at`, `deleted_at`, `unit_id`, `tenant_id`, `monthly_rent`, `received_payment`, `payment_date`, `receipt_number`, `payment_id`, `month`, `year`, `status`) VALUES
(95, '2020-07-30 13:32:57', '2020-07-30 13:32:57', NULL, 28, 23, '35000', NULL, NULL, NULL, NULL, '2019-12', NULL, 2),
(109, '2020-09-16 10:31:38', '2020-09-16 10:31:38', NULL, 27, 22, '36000', NULL, NULL, NULL, NULL, '2020-01', NULL, 2),
(110, '2020-09-16 10:33:13', '2020-09-16 10:33:13', NULL, 27, 22, '36000', NULL, NULL, NULL, NULL, '2019-12', NULL, 2),
(111, '2020-09-16 10:33:49', '2020-09-16 10:33:49', NULL, 27, 22, '36000', NULL, NULL, NULL, NULL, '2019-11', NULL, 2),
(114, '2020-09-16 10:41:21', '2020-09-16 10:41:21', NULL, 25, 21, '45000', NULL, NULL, NULL, NULL, '2020-08', NULL, 2),
(115, '2020-09-16 10:41:21', '2020-09-16 10:41:21', NULL, 26, 22, '30000', NULL, NULL, NULL, NULL, '2020-08', NULL, 2),
(116, '2020-09-16 10:41:21', '2020-09-16 10:41:21', NULL, 27, 22, '36000', NULL, NULL, NULL, NULL, '2020-08', NULL, 2),
(117, '2020-09-16 10:41:21', '2020-09-16 10:41:21', NULL, 28, 23, '35000', NULL, NULL, NULL, NULL, '2020-08', NULL, 2),
(118, '2020-09-16 10:41:21', '2020-09-16 10:41:21', NULL, 29, 24, '33000', NULL, NULL, NULL, NULL, '2020-08', NULL, 2),
(119, '2020-09-16 10:41:21', '2020-09-16 10:41:21', NULL, 30, 25, '30000', NULL, NULL, NULL, NULL, '2020-08', NULL, 2),
(120, '2020-09-16 10:41:21', '2020-09-16 10:41:21', NULL, 31, 25, '60000', NULL, NULL, NULL, NULL, '2020-08', NULL, 2),
(121, '2020-09-16 10:41:21', '2020-10-21 10:42:58', NULL, 32, 27, '30000', '4343', '2020-10-21', '343234', NULL, '2020-08', NULL, 1),
(124, '2020-09-16 10:49:15', '2020-09-16 10:49:15', NULL, 31, 25, '60000', NULL, NULL, NULL, NULL, '2020-01', NULL, 2),
(125, '2020-09-16 10:51:36', '2020-09-16 10:51:36', NULL, 32, 27, '30000', NULL, NULL, NULL, NULL, '2020-05', NULL, 2),
(126, '2020-09-16 11:02:55', '2020-09-16 11:02:55', NULL, 28, 23, '35000', NULL, NULL, NULL, NULL, '2019-11', NULL, 2),
(127, '2020-09-18 16:52:42', '2020-09-18 17:37:53', NULL, 23, 19, '50000', '50000', '2020-09-18', '1525551', NULL, '2020-09', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('abdulhamid@uo.edu.pk', '$2y$10$0equo/Bvye.fRsmIHs2DKuaIUC6eR3/jMxnIev44BKjnDK4FKkti.', '2019-10-16 11:41:21');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `title`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'user_management_access', '2019-04-15 13:14:42', '2019-04-15 13:14:42', NULL),
(2, 'permission_create', '2019-04-15 13:14:42', '2019-04-15 13:14:42', NULL),
(3, 'permission_edit', '2019-04-15 13:14:42', '2019-04-15 13:14:42', NULL),
(4, 'permission_show', '2019-04-15 13:14:42', '2019-04-15 13:14:42', NULL),
(5, 'permission_delete', '2019-04-15 13:14:42', '2019-04-15 13:14:42', NULL),
(6, 'permission_access', '2019-04-15 13:14:42', '2019-04-15 13:14:42', NULL),
(7, 'role_create', '2019-04-15 13:14:42', '2019-04-15 13:14:42', NULL),
(8, 'role_edit', '2019-04-15 13:14:42', '2019-04-15 13:14:42', NULL),
(9, 'role_show', '2019-04-15 13:14:42', '2019-04-15 13:14:42', NULL),
(10, 'role_delete', '2019-04-15 13:14:42', '2019-04-15 13:14:42', NULL),
(11, 'role_access', '2019-04-15 13:14:42', '2019-04-15 13:14:42', NULL),
(12, 'user_create', '2019-04-15 13:14:42', '2019-04-15 13:14:42', NULL),
(13, 'user_edit', '2019-04-15 13:14:42', '2019-04-15 13:14:42', NULL),
(14, 'user_show', '2019-04-15 13:14:42', '2019-04-15 13:14:42', NULL),
(15, 'user_delete', '2019-04-15 13:14:42', '2019-04-15 13:14:42', NULL),
(16, 'user_access', '2019-04-15 13:14:42', '2019-04-15 13:14:42', NULL),
(17, 'product_create', '2019-04-15 13:14:42', '2019-10-09 12:12:49', '2019-10-09 12:12:49'),
(18, 'product_edit', '2019-04-15 13:14:42', '2019-10-09 12:12:56', '2019-10-09 12:12:56'),
(19, 'product_show', '2019-04-15 13:14:42', '2019-10-09 12:13:03', '2019-10-09 12:13:03'),
(20, 'product_delete', '2019-04-15 13:14:42', '2019-10-09 12:13:13', '2019-10-09 12:13:13'),
(21, 'product_access', '2019-04-15 13:14:42', '2019-10-09 12:13:19', '2019-10-09 12:13:19'),
(22, 'faculty_resource_create', '2019-09-15 20:03:15', '2019-09-15 20:04:43', NULL),
(23, 'faculty_resource_edit', '2019-09-15 20:04:17', '2019-09-15 20:04:17', NULL),
(24, 'faculty_resource_show', '2019-09-15 20:05:15', '2019-09-15 20:05:15', NULL),
(25, 'faculty_resource_delete', '2019-09-15 20:05:38', '2019-09-15 20:05:38', NULL),
(26, 'faculty_resource_access', '2019-09-15 20:06:18', '2019-09-15 20:06:18', NULL),
(27, 'course_category_create', NULL, NULL, NULL),
(28, 'course_category_edit', NULL, NULL, NULL),
(29, 'course_category_show', NULL, NULL, NULL),
(30, 'course_category_delete', NULL, NULL, NULL),
(31, 'course_category_access', NULL, NULL, NULL),
(32, 'course_create', NULL, NULL, NULL),
(33, 'course_edit', NULL, NULL, NULL),
(34, 'course_show', NULL, NULL, NULL),
(35, 'course_delete', NULL, NULL, NULL),
(36, 'course_access', NULL, NULL, NULL),
(37, 'news_create', NULL, NULL, NULL),
(38, 'news_edit', NULL, NULL, NULL),
(39, 'news_show', NULL, NULL, NULL),
(40, 'news_delete', NULL, NULL, NULL),
(41, 'news_access', NULL, NULL, NULL),
(42, 'jobs_create', NULL, NULL, NULL),
(43, 'jobs_edit', NULL, NULL, NULL),
(44, 'jobs_show', NULL, NULL, NULL),
(45, 'jobs_delete', NULL, NULL, NULL),
(46, 'jobs_access', NULL, NULL, NULL),
(47, 'designation_create', NULL, NULL, NULL),
(48, 'designation_edit', NULL, NULL, NULL),
(49, 'designation_show', NULL, NULL, NULL),
(50, 'designation_delete', NULL, NULL, NULL),
(51, 'designation_access', NULL, NULL, NULL),
(52, 'faculties_create', NULL, NULL, NULL),
(53, 'faculties_edit', NULL, NULL, NULL),
(54, 'faculties_show', NULL, NULL, NULL),
(55, 'faculties_delete', NULL, NULL, NULL),
(56, 'faculties_access', NULL, NULL, NULL),
(57, 'department_create', NULL, NULL, NULL),
(58, 'department_edit', NULL, NULL, NULL),
(59, 'department_show', NULL, NULL, NULL),
(60, 'department_delete', NULL, NULL, NULL),
(61, 'department_access', NULL, NULL, NULL),
(62, 'degree_program_create', '2019-10-07 10:42:15', '2019-10-07 10:42:53', NULL),
(63, 'degree_program_edit', '2019-10-07 10:42:32', '2019-10-07 10:43:09', NULL),
(64, 'degree_program_show', '2019-10-07 10:43:29', '2019-10-07 10:43:29', NULL),
(65, 'degree_program_delete', '2019-10-07 10:43:44', '2019-10-07 10:43:44', NULL),
(66, 'degree_program_access', '2019-10-07 10:43:44', '2019-10-07 10:43:44', NULL),
(67, 'scheme_of_study_create', '2019-10-07 10:42:15', '2019-10-07 10:42:53', NULL),
(68, 'scheme_of_study_edit', '2019-10-07 10:42:32', '2019-10-07 10:43:09', NULL),
(69, 'scheme_of_study_show', '2019-10-07 10:43:29', '2019-10-07 10:43:29', NULL),
(70, 'scheme_of_study_delete', '2019-10-07 10:43:44', '2019-10-07 10:43:44', NULL),
(71, 'scheme_of_study_access', '2019-10-07 10:43:44', '2019-10-07 10:43:44', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

CREATE TABLE `permission_role` (
  `role_id` int(10) UNSIGNED NOT NULL,
  `permission_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permission_role`
--

INSERT INTO `permission_role` (`role_id`, `permission_id`, `created_at`, `updated_at`, `deleted_at`, `updated_by`) VALUES
(2, 38, '2019-10-09 12:17:56', '2019-10-09 12:17:56', NULL, 2),
(2, 39, '2019-10-09 12:17:56', '2019-10-09 12:17:56', NULL, 2),
(2, 41, '2019-10-09 12:17:56', '2019-10-09 12:17:56', NULL, 2),
(2, 42, '2019-10-09 12:17:56', '2019-10-09 12:17:56', NULL, 2),
(2, 43, '2019-10-09 12:17:56', '2019-10-09 12:17:56', NULL, 2),
(2, 46, '2019-10-09 12:17:56', '2019-10-09 12:17:56', NULL, 2),
(4, 23, '2019-10-09 12:38:29', '2019-10-09 12:38:29', NULL, 2),
(4, 24, '2019-10-09 12:38:29', '2019-10-09 12:38:29', NULL, 2),
(4, 26, '2019-10-09 12:38:29', '2019-10-09 12:38:29', NULL, 2),
(1, 1, '2019-10-09 12:42:59', '2019-10-09 12:42:59', NULL, 2),
(1, 2, '2019-10-09 12:42:59', '2019-10-09 12:42:59', NULL, 2),
(1, 3, '2019-10-09 12:42:59', '2019-10-09 12:42:59', NULL, 2),
(1, 4, '2019-10-09 12:42:59', '2019-10-09 12:42:59', NULL, 2),
(1, 5, '2019-10-09 12:42:59', '2019-10-09 12:42:59', NULL, 2),
(1, 6, '2019-10-09 12:42:59', '2019-10-09 12:42:59', NULL, 2),
(1, 7, '2019-10-09 12:42:59', '2019-10-09 12:42:59', NULL, 2),
(1, 8, '2019-10-09 12:42:59', '2019-10-09 12:42:59', NULL, 2),
(1, 9, '2019-10-09 12:42:59', '2019-10-09 12:42:59', NULL, 2),
(1, 10, '2019-10-09 12:42:59', '2019-10-09 12:42:59', NULL, 2),
(1, 11, '2019-10-09 12:42:59', '2019-10-09 12:42:59', NULL, 2),
(1, 12, '2019-10-09 12:42:59', '2019-10-09 12:42:59', NULL, 2),
(1, 13, '2019-10-09 12:42:59', '2019-10-09 12:42:59', NULL, 2),
(1, 14, '2019-10-09 12:42:59', '2019-10-09 12:42:59', NULL, 2),
(1, 15, '2019-10-09 12:42:59', '2019-10-09 12:42:59', NULL, 2),
(1, 16, '2019-10-09 12:42:59', '2019-10-09 12:42:59', NULL, 2),
(1, 22, '2019-10-09 12:42:59', '2019-10-09 12:42:59', NULL, 2),
(1, 23, '2019-10-09 12:42:59', '2019-10-09 12:42:59', NULL, 2),
(1, 24, '2019-10-09 12:42:59', '2019-10-09 12:42:59', NULL, 2),
(1, 25, '2019-10-09 12:42:59', '2019-10-09 12:42:59', NULL, 2),
(1, 26, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 27, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 28, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 29, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 30, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 31, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 32, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 33, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 34, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 35, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 36, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 37, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 38, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 39, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 40, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 41, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 42, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 43, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 44, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 45, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 46, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 47, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 48, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 49, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 50, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 51, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 52, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 53, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 54, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 55, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 56, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 57, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 58, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 59, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 60, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 61, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 62, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 63, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 64, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 65, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 66, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 67, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 68, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 69, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 70, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(1, 71, '2019-10-09 12:43:00', '2019-10-09 12:43:00', NULL, 2),
(3, 22, '2019-10-12 20:56:36', '2019-10-12 20:56:36', NULL, 2),
(3, 23, '2019-10-12 20:56:36', '2019-10-12 20:56:36', NULL, 2),
(3, 24, '2019-10-12 20:56:36', '2019-10-12 20:56:36', NULL, 2),
(3, 26, '2019-10-12 20:56:36', '2019-10-12 20:56:36', NULL, 2),
(3, 62, '2019-10-12 20:56:36', '2019-10-12 20:56:36', NULL, 2),
(3, 63, '2019-10-12 20:56:36', '2019-10-12 20:56:36', NULL, 2),
(3, 64, '2019-10-12 20:56:36', '2019-10-12 20:56:36', NULL, 2),
(3, 65, '2019-10-12 20:56:36', '2019-10-12 20:56:36', NULL, 2),
(3, 66, '2019-10-12 20:56:36', '2019-10-12 20:56:36', NULL, 2),
(3, 67, '2019-10-12 20:56:36', '2019-10-12 20:56:36', NULL, 2),
(3, 68, '2019-10-12 20:56:36', '2019-10-12 20:56:36', NULL, 2),
(3, 69, '2019-10-12 20:56:36', '2019-10-12 20:56:36', NULL, 2),
(3, 70, '2019-10-12 20:56:36', '2019-10-12 20:56:36', NULL, 2),
(3, 71, '2019-10-12 20:56:36', '2019-10-12 20:56:36', NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `project_name` varchar(1000) DEFAULT NULL,
  `no_of_units` int(11) DEFAULT NULL,
  `location` varchar(1000) DEFAULT NULL,
  `no_of_floors` int(11) DEFAULT NULL,
  `image` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `created_at`, `updated_at`, `deleted_at`, `project_name`, `no_of_units`, `location`, `no_of_floors`, `image`) VALUES
(16, '2020-10-26 03:08:20', '2020-10-26 03:08:20', NULL, 'Green Homes', 200, 'Badiana', 10, 'WhatsApp Image 2020-10-21 at 7.55.35 PM.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `renew_contract`
--

CREATE TABLE `renew_contract` (
  `id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `increment_amount` varchar(1000) DEFAULT NULL,
  `documents` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `rental_units`
--

CREATE TABLE `rental_units` (
  `id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `landlord_id` int(11) DEFAULT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `monthly_rent` varchar(512) DEFAULT NULL,
  `maintenace` varchar(512) DEFAULT NULL,
  `allotment_date` date DEFAULT NULL,
  `receipt_number` varchar(512) DEFAULT NULL,
  `security` varchar(512) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 6,
  `business_type` varchar(512) DEFAULT NULL,
  `remarks` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `rent_a_property`
--

CREATE TABLE `rent_a_property` (
  `id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `monthly_rent` varchar(1000) DEFAULT NULL,
  `maintenace` varchar(1000) DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `rent_collection`
--

CREATE TABLE `rent_collection` (
  `id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `title`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Admin', '2019-04-15 13:13:32', '2019-04-15 13:13:32', NULL),
(2, 'Moderator', '2019-04-15 13:13:32', '2019-10-09 12:17:56', NULL),
(3, 'HOD', '2019-04-15 13:13:32', '2019-04-15 13:13:32', NULL),
(4, 'Faculty Member', '2019-04-15 13:13:32', '2019-04-15 13:13:32', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role_user`
--

CREATE TABLE `role_user` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `id` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_user`
--

INSERT INTO `role_user` (`user_id`, `role_id`, `id`, `updated_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, 4, 14, 2, '2019-10-10 11:11:30', '2019-10-10 11:11:30', NULL),
(6, 4, 15, 2, '2019-10-11 13:58:32', '2019-10-11 13:58:32', NULL),
(7, 4, 16, 2, '2019-10-11 14:04:16', '2019-10-11 14:04:16', NULL),
(8, 4, 17, 2, '2019-10-11 14:08:33', '2019-10-11 14:08:33', NULL),
(9, 4, 18, 2, '2019-10-11 14:16:08', '2019-10-11 14:16:08', NULL),
(10, 4, 19, 2, '2019-10-11 14:28:04', '2019-10-11 14:28:04', NULL),
(11, 4, 20, 2, '2019-10-11 14:37:18', '2019-10-11 14:37:18', NULL),
(12, 4, 21, 2, '2019-10-11 14:39:17', '2019-10-11 14:39:17', NULL),
(13, 4, 22, 2, '2019-10-11 14:53:21', '2019-10-11 14:53:21', NULL),
(14, 4, 23, 2, '2019-10-11 14:54:38', '2019-10-11 14:54:38', NULL),
(15, 4, 24, 2, '2019-10-11 14:56:26', '2019-10-11 14:56:26', NULL),
(18, 4, 27, 2, '2019-10-11 22:04:50', '2019-10-11 22:04:50', NULL),
(19, 4, 29, 2, '2019-10-11 22:10:36', '2019-10-11 22:10:36', NULL),
(20, 4, 30, 2, '2019-10-11 22:13:23', '2019-10-11 22:13:23', NULL),
(21, 4, 31, 2, '2019-10-11 22:26:45', '2019-10-11 22:26:45', NULL),
(3, 2, 33, 2, '2019-10-11 23:39:25', '2019-10-11 23:39:25', NULL),
(16, 4, 36, 2, '2019-10-12 10:46:44', '2019-10-12 10:46:44', NULL),
(17, 4, 37, 2, '2019-10-12 10:48:41', '2019-10-12 10:48:41', NULL),
(23, 4, 39, 2, '2019-10-12 11:07:20', '2019-10-12 11:07:20', NULL),
(22, 4, 40, 2, '2019-10-12 11:07:33', '2019-10-12 11:07:33', NULL),
(24, 4, 41, 2, '2019-10-12 11:12:11', '2019-10-12 11:12:11', NULL),
(25, 4, 42, 2, '2019-10-12 11:13:37', '2019-10-12 11:13:37', NULL),
(26, 4, 43, 2, '2019-10-12 11:16:04', '2019-10-12 11:16:04', NULL),
(28, 4, 45, 2, '2019-10-12 11:19:58', '2019-10-12 11:19:58', NULL),
(27, 4, 46, 2, '2019-10-12 12:16:50', '2019-10-12 12:16:50', NULL),
(29, 4, 47, 2, '2019-10-12 15:28:46', '2019-10-12 15:28:46', NULL),
(30, 4, 48, 2, '2019-10-12 16:06:31', '2019-10-12 16:06:31', NULL),
(33, 4, 51, 2, '2019-10-12 16:11:10', '2019-10-12 16:11:10', NULL),
(34, 4, 52, 2, '2019-10-12 16:22:36', '2019-10-12 16:22:36', NULL),
(35, 4, 53, 2, '2019-10-12 16:24:12', '2019-10-12 16:24:12', NULL),
(36, 4, 54, 2, '2019-10-12 16:25:28', '2019-10-12 16:25:28', NULL),
(5, 4, 60, 2, '2019-10-12 16:56:52', '2019-10-12 16:56:52', NULL),
(38, 4, 61, 2, '2019-10-12 17:06:04', '2019-10-12 17:06:04', NULL),
(39, 4, 62, 2, '2019-10-12 17:08:52', '2019-10-12 17:08:52', NULL),
(41, 4, 64, 2, '2019-10-12 17:14:07', '2019-10-12 17:14:07', NULL),
(42, 4, 65, 2, '2019-10-12 17:16:27', '2019-10-12 17:16:27', NULL),
(43, 4, 66, 2, '2019-10-12 17:18:15', '2019-10-12 17:18:15', NULL),
(44, 4, 67, 2, '2019-10-12 17:19:44', '2019-10-12 17:19:44', NULL),
(40, 4, 68, 2, '2019-10-12 17:24:05', '2019-10-12 17:24:05', NULL),
(46, 4, 70, 2, '2019-10-12 17:32:38', '2019-10-12 17:32:38', NULL),
(47, 4, 71, 2, '2019-10-12 17:43:46', '2019-10-12 17:43:46', NULL),
(48, 4, 72, 2, '2019-10-12 17:44:34', '2019-10-12 17:44:34', NULL),
(31, 4, 73, 2, '2019-10-12 18:11:00', '2019-10-12 18:11:00', NULL),
(37, 3, 75, 2, '2019-10-12 19:59:31', '2019-10-12 19:59:31', NULL),
(49, 4, 76, 2, '2019-10-13 08:35:05', '2019-10-13 08:35:05', NULL),
(50, 4, 77, 2, '2019-10-13 08:37:21', '2019-10-13 08:37:21', NULL),
(51, 4, 78, 2, '2019-10-13 08:48:04', '2019-10-13 08:48:04', NULL),
(52, 4, 79, 2, '2019-10-13 08:49:34', '2019-10-13 08:49:34', NULL),
(2, 1, 80, 2, '2019-10-13 09:11:27', '2019-10-13 09:11:27', NULL),
(53, 4, 81, 2, '2019-10-13 09:16:04', '2019-10-13 09:16:04', NULL),
(54, 4, 82, 2, '2019-10-13 09:19:03', '2019-10-13 09:19:03', NULL),
(55, 4, 83, 2, '2019-10-13 09:36:21', '2019-10-13 09:36:21', NULL),
(56, 4, 84, 2, '2019-10-13 09:38:20', '2019-10-13 09:38:20', NULL),
(57, 1, 85, 2, '2019-10-13 09:39:53', '2019-10-13 09:39:53', NULL),
(58, 4, 86, 2, '2019-10-13 09:45:33', '2019-10-13 09:45:33', NULL),
(45, 4, 90, 2, '2019-10-13 12:40:20', '2019-10-13 12:40:20', NULL),
(59, 4, 91, 2, '2019-10-13 15:57:05', '2019-10-13 15:57:05', NULL),
(60, 4, 92, 2, '2019-10-13 21:33:27', '2019-10-13 21:33:27', NULL),
(62, 4, 94, 2, '2019-10-14 09:15:54', '2019-10-14 09:15:54', NULL),
(63, 4, 95, 2, '2019-10-14 14:31:46', '2019-10-14 14:31:46', NULL),
(64, 4, 96, 2, '2019-10-14 14:33:00', '2019-10-14 14:33:00', NULL),
(61, 4, 97, 2, '2019-10-15 06:37:08', '2019-10-15 06:37:08', NULL),
(66, 4, 99, 2, '2019-10-15 10:31:01', '2019-10-15 10:31:01', NULL),
(65, 4, 100, 2, '2019-10-15 10:31:46', '2019-10-15 10:31:46', NULL),
(67, 4, 101, 2, '2019-10-15 10:34:05', '2019-10-15 10:34:05', NULL),
(68, 4, 103, 2, '2019-10-15 15:18:28', '2019-10-15 15:18:28', NULL),
(69, 4, 104, 2, '2019-10-16 09:13:41', '2019-10-16 09:13:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `id` int(11) NOT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `tenant_id` int(11) DEFAULT NULL,
  `agent_id` int(11) DEFAULT NULL,
  `down_payment` varchar(512) DEFAULT NULL,
  `sale_price` varchar(512) DEFAULT NULL,
  `no_of_installments` varchar(512) DEFAULT NULL,
  `guarantor_1` varchar(512) DEFAULT NULL,
  `guarantor_2` varchar(512) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `status` varchar(512) DEFAULT NULL,
  `agent_Commission` varchar(512) DEFAULT NULL,
  `discount` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`updated_at`, `deleted_at`, `created_at`, `id`, `unit_id`, `tenant_id`, `agent_id`, `down_payment`, `sale_price`, `no_of_installments`, `guarantor_1`, `guarantor_2`, `date`, `status`, `agent_Commission`, `discount`) VALUES
('2020-10-27 11:14:36', NULL, '2020-10-27 06:55:35', 35, 49, 40, 41, '500000', '2000000', '30', '41', '42', '2020-10-27', '8', '10000', NULL),
('2020-10-27 11:27:07', NULL, '2020-10-27 11:27:07', 36, 51, 40, 41, '500000', '8000000', '40', NULL, NULL, '2020-10-27', '3', '10000', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id`, `name`) VALUES
(1, 'Sold'),
(2, 'Available'),
(3, 'Active'),
(4, 'Deactive'),
(5, 'Available for Rent'),
(6, 'On Rent'),
(7, 'Available for Sale'),
(8, 'Cancel');

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `name` varchar(1000) DEFAULT NULL,
  `father_name` varchar(1000) DEFAULT NULL,
  `cnic_no` varchar(512) DEFAULT NULL,
  `mobile_no` varchar(512) DEFAULT NULL,
  `telephone_no` varchar(512) DEFAULT NULL,
  `address` varchar(1000) DEFAULT NULL,
  `is_agent` varchar(512) DEFAULT NULL,
  `image` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`id`, `created_at`, `updated_at`, `deleted_at`, `name`, `father_name`, `cnic_no`, `mobile_no`, `telephone_no`, `address`, `is_agent`, `image`) VALUES
(40, '2020-10-26 05:39:24', '2020-10-26 05:39:24', NULL, 'Hafiz Umar Waqar', 'Sikander', '346025656466464', '03068426484', '03068426484', '559-Q, Q-Block, Johar Town', '0', 'WhatsApp Image 2020-10-14 at 10.01.50 PM.jpeg'),
(41, '2020-10-26 05:42:07', '2020-10-26 05:47:49', NULL, 'Naqeeb ullah', 'Maqbool Khan', '3520286756444', '03410480085', '03410480085', 'Picko Road Lahore', '1', 'menu.jpg'),
(42, '2020-10-26 05:43:43', '2020-10-26 05:43:43', NULL, 'waqas u rehman', 'Sikander', '346030699993', '03217199603', '03217199603', '559-Q, Q-Block, Johar Town', '1', 'WhatsApp Image 2020-09-30 at 7.04.43 PM.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `floor_id` int(11) DEFAULT NULL,
  `unit_name` varchar(1000) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL,
  `area_sq` varchar(512) DEFAULT NULL,
  `dimension` varchar(512) DEFAULT NULL,
  `price_per_sq` varchar(512) DEFAULT NULL,
  `maintenace_per_sq` varchar(512) DEFAULT NULL,
  `corner_plot` tinyint(1) DEFAULT NULL,
  `status` varchar(512) DEFAULT '5',
  `meter_no` varchar(512) DEFAULT NULL,
  `image` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `created_at`, `updated_at`, `deleted_at`, `floor_id`, `unit_name`, `project_id`, `area_sq`, `dimension`, `price_per_sq`, `maintenace_per_sq`, `corner_plot`, `status`, `meter_no`, `image`) VALUES
(49, '2020-10-27 06:52:17', '2020-10-27 11:14:36', NULL, 25, '101', 16, NULL, '12x12', '200', '20500', NULL, '7', '222', NULL),
(51, '2020-10-27 10:45:17', '2020-10-27 11:27:07', NULL, 25, '306(5 Marla)', 16, NULL, '5 Marla', '244', '20500', NULL, '1', '133456666', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `deleted_at`, `updated_by`) VALUES
(1, 'Admin', 'admin@admin.com', NULL, '$2y$10$rmZkw9hGy1lLCt67fesL9OOD58ITNYq72BQQR9nvY.vpsKwpthPe6', NULL, NULL, '2019-10-10 10:10:19', '2019-10-10 10:10:19', NULL),
(2, 'Admin', 'admin', NULL, '$2y$10$smd1XJRl0w2bmN5EaZdUAelDLUbrVtBITiwEqgmaDD3gx4wkG5Kf.', 'W4nmMgSEXWsGXyWaS9I73oNQQcXVBJVYVnp7baqtIvqAOsyJFY32JTNZqYTq', '2019-09-19 02:57:56', '2019-10-14 05:30:23', NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `id` int(11) NOT NULL,
  `type` varchar(512) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `debit` varchar(512) DEFAULT NULL,
  `credit` varchar(512) DEFAULT NULL,
  `status` varchar(512) DEFAULT '3',
  `date` date DEFAULT NULL,
  `sale_id` int(11) DEFAULT NULL,
  `installment_id` int(11) DEFAULT NULL,
  `discount` varchar(512) DEFAULT NULL,
  `discount_voucher` int(11) DEFAULT NULL,
  `donation_voucher` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `vouchers`
--

INSERT INTO `vouchers` (`created_at`, `updated_at`, `deleted_at`, `id`, `type`, `account_id`, `debit`, `credit`, `status`, `date`, `sale_id`, `installment_id`, `discount`, `discount_voucher`, `donation_voucher`) VALUES
('2020-10-26 06:10:58', '2020-10-26 06:10:58', NULL, 27, 'CRV', 1, '500000', NULL, NULL, NULL, 16, NULL, NULL, NULL, NULL),
('2020-10-26 06:10:58', '2020-10-26 06:10:58', NULL, 28, 'CPV', 2, '10000', NULL, NULL, NULL, 16, NULL, NULL, 0, NULL),
('2020-10-26 07:47:56', '2020-10-26 07:47:56', NULL, 29, 'CRV', 1, '500000', NULL, NULL, NULL, 17, NULL, NULL, NULL, NULL),
('2020-10-26 07:47:56', '2020-10-26 07:47:56', NULL, 30, 'CPV', 2, '10000', NULL, NULL, NULL, 17, NULL, NULL, 0, NULL),
('2020-10-26 09:42:28', '2020-10-26 09:42:28', NULL, 31, 'CRV', 1, '200000', NULL, NULL, NULL, 18, NULL, NULL, NULL, NULL),
('2020-10-26 09:42:28', '2020-10-26 09:42:28', NULL, 32, 'CPV', 2, '4000', NULL, NULL, NULL, 18, NULL, NULL, 0, NULL),
('2020-10-26 09:45:41', '2020-10-26 09:45:41', NULL, 33, 'CRV', 1, '200000', NULL, NULL, NULL, 20, NULL, NULL, NULL, NULL),
('2020-10-26 09:45:41', '2020-10-26 09:45:41', NULL, 34, 'CPV', 2, '4000', NULL, NULL, NULL, 20, NULL, NULL, 0, NULL),
('2020-10-26 09:47:38', '2020-10-26 09:47:38', NULL, 35, 'CRV', 1, '100000', NULL, NULL, NULL, 21, NULL, NULL, NULL, NULL),
('2020-10-26 09:47:38', '2020-10-26 09:47:38', NULL, 36, 'CPV', 2, '2000', NULL, NULL, NULL, 21, NULL, NULL, 0, NULL),
('2020-10-26 09:49:54', '2020-10-26 09:49:54', NULL, 37, 'CRV', 1, '2000000', NULL, NULL, NULL, 22, NULL, NULL, NULL, NULL),
('2020-10-26 09:49:54', '2020-10-26 09:49:54', NULL, 38, 'CPV', 2, '40000', NULL, NULL, NULL, 22, NULL, NULL, 0, NULL),
('2020-10-26 10:34:38', '2020-10-26 10:34:38', NULL, 39, 'CRV', 1, '0', NULL, NULL, NULL, 22, 28, NULL, NULL, NULL),
('2020-10-26 10:34:38', '2020-10-26 10:34:38', NULL, 40, 'CPV', 2, '0', NULL, NULL, NULL, 22, NULL, NULL, 0, NULL),
('2020-10-26 10:45:06', '2020-10-26 10:45:06', NULL, 41, 'CRV', 1, '1000', NULL, NULL, NULL, 22, 29, NULL, NULL, NULL),
('2020-10-26 10:45:06', '2020-10-26 10:45:06', NULL, 42, 'CPV', 2, '20', NULL, NULL, NULL, 22, NULL, NULL, 0, NULL),
('2020-10-27 05:44:33', '2020-10-27 05:44:33', NULL, 43, 'CRV', 1, '500000', NULL, NULL, NULL, 30, NULL, NULL, NULL, NULL),
('2020-10-27 05:44:33', '2020-10-27 05:44:33', NULL, 44, 'CPV', 2, '10000', NULL, NULL, NULL, 30, NULL, NULL, 0, NULL),
('2020-10-27 06:09:06', '2020-10-27 06:09:06', NULL, 45, 'CRV', 1, '500000', NULL, NULL, NULL, 31, NULL, NULL, NULL, NULL),
('2020-10-27 06:09:06', '2020-10-27 06:09:06', NULL, 46, 'CPV', 2, '10000', NULL, NULL, NULL, 31, NULL, NULL, 0, NULL),
('2020-10-27 06:30:11', '2020-10-27 06:30:11', NULL, 47, 'CRV', 1, '500000', NULL, NULL, NULL, 32, NULL, NULL, NULL, NULL),
('2020-10-27 06:30:11', '2020-10-27 06:30:11', NULL, 48, 'CPV', 2, '10000', NULL, NULL, NULL, 32, NULL, NULL, 0, NULL),
('2020-10-27 06:35:26', '2020-10-27 06:35:26', NULL, 49, 'CRV', 1, '500000', NULL, NULL, NULL, 33, NULL, NULL, NULL, NULL),
('2020-10-27 06:35:26', '2020-10-27 06:35:26', NULL, 50, 'CPV', 2, '10000', NULL, NULL, NULL, 33, NULL, NULL, 0, NULL),
('2020-10-27 06:53:41', '2020-10-27 06:53:41', NULL, 51, 'CRV', 1, '500000', NULL, NULL, NULL, 34, NULL, NULL, NULL, NULL),
('2020-10-27 06:53:41', '2020-10-27 06:53:41', NULL, 52, 'CPV', 2, '10000', NULL, NULL, NULL, 34, NULL, NULL, 0, NULL),
('2020-10-27 06:55:35', '2020-10-27 11:14:36', NULL, 53, 'CRV', 1, '500000', NULL, '8', NULL, 35, NULL, NULL, NULL, NULL),
('2020-10-27 06:55:35', '2020-10-27 11:14:36', NULL, 54, 'CPV', 2, '10000', NULL, '8', NULL, 35, NULL, NULL, 0, NULL),
('2020-10-27 11:27:07', '2020-10-27 11:27:07', NULL, 55, 'CRV', 1, '500000', NULL, '3', NULL, 36, NULL, NULL, NULL, 0),
('2020-10-27 11:27:07', '2020-10-27 11:27:07', NULL, 56, 'CPV', 2, '10000', NULL, '3', NULL, 36, NULL, NULL, NULL, 1),
('2020-10-27 11:35:01', '2020-10-27 11:35:01', NULL, 57, 'CRV', 1, '187500', NULL, '3', NULL, 36, 30, NULL, NULL, 0),
('2020-10-27 11:35:01', '2020-10-27 11:35:01', NULL, 58, 'CPV', 2, '3750', NULL, '3', NULL, 36, NULL, NULL, NULL, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `documents`
--
ALTER TABLE `documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `floors`
--
ALTER TABLE `floors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `installments`
--
ALTER TABLE `installments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_options`
--
ALTER TABLE `menu_options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_options_role`
--
ALTER TABLE `menu_options_role`
  ADD PRIMARY KEY (`id`),
  ADD KEY `permission_role_role_id_foreign` (`role_id`),
  ADD KEY `permission_role_permission_id_foreign` (`menu_option_id`);

--
-- Indexes for table `monthly_maintenace`
--
ALTER TABLE `monthly_maintenace`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `monthly_rents`
--
ALTER TABLE `monthly_rents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD KEY `permission_role_role_id_foreign` (`role_id`),
  ADD KEY `permission_role_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `renew_contract`
--
ALTER TABLE `renew_contract`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rental_units`
--
ALTER TABLE `rental_units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rent_a_property`
--
ALTER TABLE `rent_a_property`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rent_collection`
--
ALTER TABLE `rent_collection`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_user_user_id_foreign` (`user_id`),
  ADD KEY `role_user_role_id_foreign` (`role_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `documents`
--
ALTER TABLE `documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `floors`
--
ALTER TABLE `floors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `installments`
--
ALTER TABLE `installments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=117;

--
-- AUTO_INCREMENT for table `menu_options`
--
ALTER TABLE `menu_options`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `menu_options_role`
--
ALTER TABLE `menu_options_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `monthly_maintenace`
--
ALTER TABLE `monthly_maintenace`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `monthly_rents`
--
ALTER TABLE `monthly_rents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `renew_contract`
--
ALTER TABLE `renew_contract`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rental_units`
--
ALTER TABLE `rental_units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `rent_a_property`
--
ALTER TABLE `rent_a_property`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rent_collection`
--
ALTER TABLE `rent_collection`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`),
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

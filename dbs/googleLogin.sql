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
-- Database: `googleLogin`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `access_token` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `email` varchar(130) DEFAULT NULL,
  `gender` varchar(130) DEFAULT NULL,
  `_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `access_token`, `email`, `gender`) VALUES
(1, 'ya29.a0AVvZVspahU-YAWu-L_27cMV82-Lcnh78oeK00x4tODzSXrZEteHziobqjAqn4CL17eGendachomTMUdjLVPnM4xidfM7Le4wM0NHMiIKJWM54yFQU9NpKWWlZTF3j24RM15MtcG7aO229jnAWjUtc3Jm4e7gaCgYKAXoSAQ8SFQGbdwaIDCZ3VI9PxA6PB_yKH-bJFg0163', 'softhouse8219@gmail.com', ''),
(2, 'ya29.a0AVvZVsq4UxwHCtnIyHNZvI-QiKjDILLLt1F9IFn4hfY-mQGKMsmdr3atVtZ5dX7RaVAOsN_YUua2fAFFuHmpgvPoLnn91FPQwgdaNzDipSGbxengf33en_xR-IktSeSta5eMzJmIoW0wa9ZFaL_y5tWJ_msRaCgYKAR4SARASFQGbdwaIAG6ZpsGVxsrCt5CHtvdp4g0163', 'haroon0cheema786@gmail.com', ''),
(3, '', '', ''),
(4, '', '', ''),
(5, '', '', ''),
(6, '', '', ''),
(7, '', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

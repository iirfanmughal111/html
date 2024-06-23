-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 23, 2023 at 11:01 AM
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
-- Database: `rmk_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `movieDetails`
--

CREATE TABLE `movieDetails` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(130) DEFAULT NULL,
  `artist` varchar(130) DEFAULT NULL,
  `_type` varchar(130) DEFAULT NULL,
  `seller` varchar(130) DEFAULT NULL,
  `notes` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `cost` int DEFAULT NULL,
  `sell` int DEFAULT NULL,
  `_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `movieDetails`
--

INSERT INTO `movieDetails` (`id`, `title`, `artist`, `_type`, `seller`, `notes`, `cost`, `sell`) VALUES
(1, 'title-update', 'artist-update', 'type-update', 'seller-update', 'notes-update', 5323, 1),
(4, 'title', 'artist', 'type', 'seller', 'notes', 500, 0),
(7, 'new title', 'sdsfdsf', 'sdf', 'sdfds', 'sdfsd', 34324, 0),
(8, 'search', 'artist', 'type of content', 'seller person', 'notes message', 8000, 1),
(9, 'haroon', 'iRFAN', 'fun', 'haroon2', 'nothing special', 9000, 0),
(10, 'irfan', 'ali', 'type-updated', 'haroon', 'notes will be here', 1000, 1),
(11, 'sdf1111', 'sdf', 'sdf', 'sdfsdf', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 3434, 1),
(12, 'eser', 'ewrewr', 'type', 'wer', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.', 2342343, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `movieDetails`
--
ALTER TABLE `movieDetails`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `movieDetails`
--
ALTER TABLE `movieDetails`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

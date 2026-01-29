-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 29, 2026 at 04:23 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `itama_book`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(12) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(2, 'Pencil'),
(12, 'Kids'),
(17, 'Fiction'),
(18, 'Novel'),
(19, 'Non-fiction'),
(20, 'Self Improvment'),
(21, 'Comic'),
(22, 'Bussiness'),
(23, 'Biography'),
(24, 'Art');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(12) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'seller'),
(3, 'customer');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(12) NOT NULL,
  `name` varchar(255) NOT NULL,
  `nik` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expiry` datetime DEFAULT NULL,
  `status` enum('online','offline') NOT NULL DEFAULT 'offline',
  `address` varchar(255) NOT NULL,
  `role` int(11) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `account_number` varchar(255) NOT NULL DEFAULT '',
  `qris_photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `nik`, `email`, `password`, `phone`, `reset_token`, `reset_expiry`, `status`, `address`, `role`, `avatar`, `account_number`, `qris_photo`) VALUES
(4, 'cella', '3175070508081001', 'rennardadit@gmail.com', '$2y$10$P53Ry9F6mc7fMHLhUpVUk.VJUIiXSgx6m6YepB8ctAhkfF1a6BomS', '', NULL, NULL, 'offline', 'Jl. Kav Kuningan', 1, '4_ce857.jpg', '', ''),
(13, 'Goseh', '12432435', 'reganre23@gmail.com', '$2y$10$AYWgG/lwVYUWGZ.yDOWse..qZmt.D.o8rOsxG5DgbVTmcRkd6mhjq', '', NULL, NULL, 'offline', 'Jl.Pulo Jahe', 2, 'avatar_1769350264_786.jpg', '1120365478', 'qris_1769654366_787.png'),
(14, 'Yunjung', '15435484332', 'rennard95@gmail.com', '$2y$10$Ez3aIYoNyCV6tCAJC17tzOkiJPa77pQg7AqmN9ozYnVYEiGkk3PAm', '', NULL, NULL, 'offline', 'JL.Nusa Indah', 2, 'avatar_1769654394_130.jpg', '3545675643256', 'qris_1769654354_606.png'),
(15, 'Goyun', '1231463242422', 'jungie@gmail.com', '$2y$10$1UFBuZSLlyPZFhTyrSN5u.4SxbaiLk6Kh2AsWkNe9mEfUBp4gpMC.', '082213521461', NULL, NULL, 'offline', 'Jl.Kanada Jepang', 3, 'avatar_1769596866_600.jpg', '', NULL),
(16, 'tama', '1687423942', 'tama@gmail.com', '$2y$10$IkDvU.p.BPof3V6woQo2Xe7kalXGZ63MDJaboGQ0qZKiPbJ78D1f6', '081384421151', NULL, NULL, 'offline', 'Jl.Kanada Jepang', 3, 'avatar_1769596850_540.jpg', '', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

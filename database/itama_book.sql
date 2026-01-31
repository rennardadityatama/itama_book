-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 31, 2026 at 09:39 AM
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
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` int(12) NOT NULL,
  `room_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message` int(11) NOT NULL,
  `is_read` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_room`
--

CREATE TABLE `chat_room` (
  `id` int(12) NOT NULL,
  `seller_id` int(12) NOT NULL,
  `customer_id` int(12) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `total_amount` int(11) NOT NULL,
  `payment_method` enum('qris','transfer') DEFAULT NULL,
  `shipping_resi` varchar(255) DEFAULT NULL,
  `tracking_link` varchar(255) DEFAULT NULL,
  `shipping_status` enum('pending','shipped','refund') NOT NULL DEFAULT 'pending',
  `status` enum('approved','refund','pending') NOT NULL DEFAULT 'pending',
  `payment_status` enum('paid','unpaid') NOT NULL,
  `payment_proof` varchar(255) DEFAULT NULL,
  `refunded_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `seller_id`, `total_amount`, `payment_method`, `shipping_resi`, `tracking_link`, `shipping_status`, `status`, `payment_status`, `payment_proof`, `refunded_at`) VALUES
(1, 15, 13, 41800, 'transfer', 'RESI90908765', NULL, 'shipped', 'approved', 'paid', 'payment_1769491031_69784a57e8f11.jpg', NULL),
(2, 15, 13, 41800, 'transfer', '123434545', 'https://jne.com', 'shipped', 'approved', 'paid', 'payment_1769491053_69784a6d9c5bb.jpg', NULL),
(3, 15, 14, 30000, 'qris', '14769329', NULL, 'shipped', 'approved', 'paid', 'payment_1769498592_697867e065856.jpg', NULL),
(4, 15, 14, 20000, 'transfer', '0909090', 'https://jne.com/0909090', 'shipped', 'approved', 'paid', 'payment_1769499081_697869c9ece3b.jpg', NULL),
(5, 15, 14, 10000, 'transfer', '889089890', 'https://jne.com', 'shipped', 'approved', 'paid', 'payment_1769499890_69786cf2bd694.jpg', NULL),
(6, 15, 14, 10000, 'transfer', NULL, NULL, 'pending', 'refund', 'unpaid', 'payment_1769500223_69786e3f3d469.jpg', NULL),
(7, 15, 14, 10000, 'qris', '0910211212', 'https://tokopedia.com', 'shipped', 'approved', 'paid', 'payment_1769500998_697871462abcb.jpg', NULL),
(8, 15, 13, 20900, 'qris', '769696969', 'https://tokopedia.com', 'shipped', 'approved', 'paid', 'payment_1769505890_69788462b35ab.jpg', NULL),
(9, 15, 14, 10000, 'transfer', '3429765943985', 'https://jne.com', 'shipped', 'approved', 'paid', 'payment_1769509810_697893b27807f.jpg', NULL),
(10, 16, 13, 62700, 'transfer', '813672343', 'https://tokopedia.com', 'shipped', 'approved', 'paid', 'payment_1769517319_6978b1072d6d6.jpg', NULL),
(11, 16, 14, 10000, 'transfer', '789987799', 'https://tokopedia.com', 'shipped', 'approved', 'paid', 'payment_1769656130_697acf42f0642.png', NULL),
(12, 15, 14, 360000, 'transfer', '179247834792', 'https://tokopedia.com', 'shipped', 'approved', 'paid', 'payment_1769656574_697ad0fe26232.png', NULL),
(13, 16, 13, 41800, 'qris', '317283927', 'https://jne.com', 'shipped', 'approved', 'paid', '', NULL),
(20, 15, 13, 110000, 'qris', '98324792', 'https://jne.com', 'shipped', 'approved', '', 'payment_1769744050_697c26b279e99.jpg', NULL),
(21, 15, 13, 110000, 'qris', NULL, NULL, 'pending', 'refund', 'unpaid', 'payment_1769744108_697c26ec7be0b.jpg', NULL),
(27, 15, 14, 12000, 'transfer', '078785588677', 'https://jne.com', 'shipped', 'approved', '', 'payment_1769772650_697c966ae1540.jpg', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `qty` int(11) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `price`, `qty`, `subtotal`) VALUES
(1, 1, 7, 20900.00, 2, 41800.00),
(2, 2, 7, 20900.00, 2, 41800.00),
(3, 3, 2, 10000.00, 1, 10000.00),
(4, 3, 6, 20000.00, 1, 20000.00),
(5, 4, 6, 20000.00, 1, 20000.00),
(6, 5, 2, 10000.00, 1, 10000.00),
(7, 6, 2, 10000.00, 1, 10000.00),
(8, 7, 2, 10000.00, 1, 10000.00),
(9, 8, 7, 20900.00, 1, 20900.00),
(10, 9, 2, 10000.00, 1, 10000.00),
(11, 10, 7, 20900.00, 3, 62700.00),
(12, 11, 2, 10000.00, 1, 10000.00),
(13, 12, 8, 120000.00, 3, 360000.00),
(14, 13, 7, 20900.00, 2, 41800.00),
(15, 21, 9, 110000.00, 1, 110000.00),
(22, 27, 8, 12000.00, 1, 12000.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` int(11) NOT NULL,
  `cost_price` int(11) NOT NULL,
  `margin` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `cost_price`, `margin`, `stock`, `image`, `description`, `seller_id`, `category_id`, `is_active`, `deleted_at`) VALUES
(2, 'Book of science', 10000, 9000, 1000, 1, 'prod_6976ef59c33d1.jpg', 'the best book', 14, 21, 1, NULL),
(6, 'Book of Nature', 20000, 15000, 5000, 2, 'prod_6976f1bcc1e6d.jpg', 'About flores, nature, sea', 14, 12, 1, NULL),
(7, 'Go Youn Jung Profile', 20900, 900, 20000, 0, 'prod_6976f2b257dc1.jpg', 'Most Beautifull person', 13, 2, 0, '2026-01-30 08:21:49'),
(8, 'Sunflower', 12000, 10000, 2000, 1, 'prod_697ad02cc95ad.png', 'This book about ...', 14, 24, 1, NULL),
(9, 'Go Youn Jung Profile', 110000, 80000, 30000, 0, 'prod_697c0a7c56c87.jpg', 'About Go Youn Jung aktrist from korea', 13, 23, 0, '2026-01-30 10:37:46');

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
  `last_activity` datetime DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `role` int(11) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `account_number` varchar(255) NOT NULL DEFAULT '',
  `qris_photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `nik`, `email`, `password`, `phone`, `reset_token`, `reset_expiry`, `last_activity`, `address`, `role`, `avatar`, `account_number`, `qris_photo`) VALUES
(4, 'cella', '3175070508081001', 'rennardadit@gmail.com', '$2y$10$P53Ry9F6mc7fMHLhUpVUk.VJUIiXSgx6m6YepB8ctAhkfF1a6BomS', '', NULL, NULL, '2026-01-30 09:17:18', 'Jl. Kav Kuningan', 1, '4_ce857.jpg', '', ''),
(13, 'ShinJi', '12432435', 'reganre23@gmail.com', '$2y$10$AYWgG/lwVYUWGZ.yDOWse..qZmt.D.o8rOsxG5DgbVTmcRkd6mhjq', '', NULL, NULL, '2026-01-30 10:37:48', 'Jl.Pulo Jahe', 2, 'avatar_1769350264_786.jpg', '1120365478', 'qris_1769654366_787.png'),
(14, 'Yunjung', '15435484332', 'rennard95@gmail.com', '$2y$10$Ez3aIYoNyCV6tCAJC17tzOkiJPa77pQg7AqmN9ozYnVYEiGkk3PAm', '', NULL, NULL, '2026-01-30 18:32:18', 'JL.Nusa Indah', 2, 'avatar_1769654394_130.jpg', '3545675643256', 'qris_1769654354_606.png'),
(15, 'Goyun', '1231463242422', 'jungie@gmail.com', '$2y$10$1UFBuZSLlyPZFhTyrSN5u.4SxbaiLk6Kh2AsWkNe9mEfUBp4gpMC.', '082213521461', NULL, NULL, '2026-01-30 18:31:02', 'Jl.Kanada Jepang', 3, 'avatar_1769596866_600.jpg', '', NULL),
(16, 'tama', '1687423942', 'tama@gmail.com', '$2y$10$IkDvU.p.BPof3V6woQo2Xe7kalXGZ63MDJaboGQ0qZKiPbJ78D1f6', '081384421151', NULL, NULL, '2026-01-30 06:18:39', 'Jl.Kanada Jepang', 3, 'avatar_1769596850_540.jpg', '', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_ibfk_1` (`customer_id`),
  ADD KEY `carts_ibfk_2` (`product_id`),
  ADD KEY `carts_ibfk_3` (`seller_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indexes for table `chat_room`
--
ALTER TABLE `chat_room`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seller_id` (`seller_id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `seller_id` (`seller_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `seller_id` (`seller_id`);

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
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_room`
--
ALTER TABLE `chat_room`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `carts_ibfk_3` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chats_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `chat_room` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chats_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `chat_room`
--
ALTER TABLE `chat_room`
  ADD CONSTRAINT `chat_room_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chat_room_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

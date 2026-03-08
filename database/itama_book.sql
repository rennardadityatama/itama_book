-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2026 at 12:22 AM
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
(2, 'Book'),
(12, 'Kids'),
(19, 'Non-fiction'),
(21, 'Comic'),
(22, 'Bussiness'),
(23, 'Biography'),
(24, 'Art');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `product_id` int(11) DEFAULT NULL COMMENT 'Product being discussed',
  `is_read` tinyint(4) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `room_id`, `sender_id`, `message`, `product_id`, `is_read`, `created_at`) VALUES
(71, 8, 25, 'test', 0, 1, '2026-03-08 08:18:31'),
(72, 8, 25, 'hadir', 0, 1, '2026-03-08 08:23:26'),
(73, 8, 14, 'iyaa', 0, 1, '2026-03-08 08:23:50');

-- --------------------------------------------------------

--
-- Table structure for table `chat_rooms`
--

CREATE TABLE `chat_rooms` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `last_product_id` int(11) DEFAULT NULL COMMENT 'The last product to talk about',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_rooms`
--

INSERT INTO `chat_rooms` (`id`, `customer_id`, `seller_id`, `last_product_id`, `created_at`, `updated_at`) VALUES
(8, 25, 14, 2, '2026-03-08 07:23:49', '2026-03-08 08:23:50');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `title` varchar(225) NOT NULL,
  `message` text NOT NULL,
  `is_read` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `order_id`, `room_id`, `type`, `title`, `message`, `is_read`, `created_at`) VALUES
(2, 14, 65, NULL, 'shipping', 'Input Tracking Number', 'Order #000065 approved. Please input tracking number.', 1, '2026-03-08 07:23:11'),
(16, 25, NULL, 8, 'chat', 'New Message', 'ChaMuhee sent you a message', 1, '2026-03-08 08:09:39'),
(17, 25, NULL, 8, 'chat', 'New Message', 'ChaMuhee sent you a message', 1, '2026-03-08 08:11:34'),
(18, 14, NULL, NULL, 'chat', 'New Message', 'Edamb sent you a message', 1, '2026-03-08 08:12:05'),
(19, 14, NULL, 8, 'chat', 'New Message', 'Edamb sent you a message', 1, '2026-03-08 08:13:03'),
(20, 25, NULL, 8, 'chat', 'New Message', 'ChaMuhee sent you a message', 1, '2026-03-08 08:13:18'),
(21, 14, NULL, 8, 'chat', 'New Message', 'Edamb sent you a message', 1, '2026-03-08 08:18:31'),
(22, 14, NULL, 8, 'chat', 'New Message', 'Edamb sent you a message', 1, '2026-03-08 08:23:26'),
(23, 25, NULL, 8, 'chat', 'New Message', 'ChaMuhee sent you a message', 1, '2026-03-08 08:23:50');

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
  `payment_status` enum('paid','unpaid','waiting_verification') NOT NULL DEFAULT 'waiting_verification',
  `payment_proof` varchar(255) DEFAULT NULL,
  `refunded_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `seller_id`, `total_amount`, `payment_method`, `shipping_resi`, `tracking_link`, `shipping_status`, `status`, `payment_status`, `payment_proof`, `refunded_at`, `created_at`, `updated_at`) VALUES
(1, 15, 13, 41800, 'transfer', 'RESI90908765', NULL, 'shipped', 'approved', 'paid', 'payment_1769491031_69784a57e8f11.jpg', NULL, '2026-01-13 08:01:32', '2026-02-09 09:33:01'),
(2, 15, 13, 41800, 'transfer', '123434545', 'https://jne.com', 'shipped', 'approved', 'paid', 'payment_1769491053_69784a6d9c5bb.jpg', NULL, '2026-01-30 08:01:32', '2026-02-09 09:33:33'),
(3, 15, 14, 30000, 'qris', '14769329', NULL, 'shipped', 'approved', 'paid', 'payment_1769498592_697867e065856.jpg', NULL, '2026-01-01 08:01:32', '2026-02-09 09:33:33'),
(4, 15, 14, 20000, 'transfer', '0909090', 'https://jne.com/0909090', 'shipped', 'approved', 'paid', 'payment_1769499081_697869c9ece3b.jpg', NULL, '2026-01-17 08:01:32', '2026-02-09 09:33:33'),
(5, 15, 14, 10000, 'transfer', '889089890', 'https://jne.com', 'shipped', 'approved', 'paid', 'payment_1769499890_69786cf2bd694.jpg', NULL, '2026-02-09 08:01:32', NULL),
(6, 15, 14, 10000, 'transfer', NULL, NULL, 'pending', 'refund', 'unpaid', 'payment_1769500223_69786e3f3d469.jpg', NULL, '2026-02-09 08:01:32', NULL),
(7, 15, 14, 10000, 'qris', '0910211212', 'https://tokopedia.com', 'shipped', 'approved', 'paid', 'payment_1769500998_697871462abcb.jpg', NULL, '2026-02-09 08:01:32', NULL),
(8, 15, 13, 20900, 'qris', '769696969', 'https://tokopedia.com', 'shipped', 'approved', 'paid', 'payment_1769505890_69788462b35ab.jpg', NULL, '2026-02-09 08:01:32', NULL),
(9, 15, 14, 10000, 'transfer', '3429765943985', 'https://jne.com', 'shipped', 'approved', 'paid', 'payment_1769509810_697893b27807f.jpg', NULL, '2026-02-09 08:01:32', NULL),
(10, 16, 13, 62700, 'transfer', '813672343', 'https://tokopedia.com', 'shipped', 'approved', 'paid', 'payment_1769517319_6978b1072d6d6.jpg', NULL, '2026-02-09 08:01:32', NULL),
(11, 16, 14, 10000, 'transfer', '789987799', 'https://tokopedia.com', 'shipped', 'approved', 'paid', 'payment_1769656130_697acf42f0642.png', NULL, '2026-02-05 08:01:32', '2026-02-10 11:55:56'),
(12, 15, 14, 360000, 'transfer', '179247834792', 'https://tokopedia.com', 'shipped', 'approved', 'paid', 'payment_1769656574_697ad0fe26232.png', NULL, '2026-02-03 08:01:32', '2026-02-10 11:55:56'),
(13, 16, 13, 41800, 'qris', '317283927', 'https://jne.com', 'shipped', 'approved', 'paid', '', NULL, '2026-02-01 08:01:32', '2026-02-10 11:55:56'),
(20, 15, 13, 110000, 'qris', '98324792', 'https://jne.com', 'shipped', 'approved', '', 'payment_1769744050_697c26b279e99.jpg', NULL, '2026-02-27 08:01:32', '2026-02-10 11:55:56'),
(21, 15, 13, 110000, 'qris', NULL, NULL, 'pending', 'refund', 'unpaid', 'payment_1769744108_697c26ec7be0b.jpg', NULL, '2026-02-13 08:01:32', '2026-02-10 11:55:56'),
(27, 15, 14, 12000, 'transfer', '078785588677', 'https://jne.com', 'shipped', 'approved', 'paid', 'payment_1769772650_697c966ae1540.jpg', NULL, '2026-02-11 08:01:32', '2026-02-12 08:15:25'),
(30, 15, 14, 22000, 'qris', '47527211447', 'https://youtube.com', 'shipped', 'approved', 'paid', 'payment_1770687305_698a8b49dfe5f.jpeg', '2026-02-10 08:54:20', '2026-02-10 08:35:05', '2026-02-12 08:15:25'),
(31, 15, 14, 20000, 'transfer', '52865563', 'https://youtube.com', 'shipped', 'approved', 'paid', 'payment_1770695576_698aab98713e5.jpeg', '2026-02-10 11:12:59', '2026-02-10 10:52:56', '2026-02-12 08:15:25'),
(32, 16, 13, 375000, 'qris', '7884778', 'https://tokopedia.com', 'shipped', 'approved', 'paid', 'payment_1770705446_698ad226327fc.jpeg', '2026-02-12 07:50:41', '2026-02-10 13:37:26', '2026-02-12 08:15:25'),
(33, 16, 13, 375000, 'transfer', '78541255', 'https://tokopedia.com', 'shipped', 'approved', 'paid', 'payment_1770705686_698ad316b6c07.jpeg', '2026-02-12 07:50:45', '2026-02-10 13:41:26', '2026-02-12 08:15:25'),
(34, 15, 14, 162000, 'qris', '68667383', 'https://tokopedia.com', 'shipped', 'approved', 'paid', 'payment_1770856900_698d21c44ee20.jpg', '2026-02-12 07:42:50', '2026-02-12 07:41:40', '2026-02-12 08:15:25'),
(35, 15, 13, 365000, 'transfer', '9090865', 'https://tokopedia.com', 'shipped', 'approved', 'paid', 'payment_1770857647_698d24af4b2ca.jpg', '2026-02-12 07:54:41', '2026-02-12 07:54:07', '2026-02-12 08:15:25'),
(36, 15, 14, 20000, 'transfer', '999090', 'https://tokopedia.com', 'shipped', 'approved', 'paid', 'payment_1770858155_698d26ab0b0ae.jpg', '2026-02-12 08:04:07', '2026-02-12 08:02:35', '2026-02-12 08:15:25'),
(37, 15, 14, 36000, 'qris', '0090978', 'https://tokopedia.com', 'shipped', 'approved', 'paid', 'payment_1770858591_698d285f9ec61.jpg', NULL, '2026-02-12 08:09:51', '2026-02-12 08:15:25'),
(38, 16, 14, 240000, 'transfer', '097653', 'https://tokopedia.com', 'shipped', 'approved', 'paid', 'payment_1770859006_698d29fed41ae.jpg', NULL, '2026-02-12 08:16:46', '2026-02-12 08:18:29'),
(39, 16, 14, 12000, 'qris', '098765475', 'https://tokopedia.com', 'shipped', 'approved', 'paid', 'payment_1770859041_698d2a2184f7e.jpg', NULL, '2026-02-12 08:17:21', '2026-02-12 08:18:45'),
(40, 15, 14, 140000, 'qris', '098769932', 'https://tokopedia.com', 'shipped', 'approved', 'paid', 'payment_1770869348_698d5264a3145.jpg', NULL, '2026-02-12 11:09:08', '2026-02-12 11:10:41'),
(41, 15, 13, 120000, 'transfer', '789654', 'https://tokopedia.com', 'shipped', 'approved', 'paid', 'payment_1770869378_698d528268341.jpg', NULL, '2026-02-12 11:09:38', '2026-02-13 14:17:32'),
(42, 15, 13, 125000, 'transfer', '7896541222', 'https://tokopedia.com', 'shipped', 'approved', 'paid', 'payment_1770869532_698d531c76111.jpg', NULL, '2026-02-12 11:12:12', '2026-02-13 14:17:43'),
(43, 22, 13, 125000, 'qris', '023155976', 'https://tokopedia.com', 'shipped', 'approved', 'paid', 'payment_1770966981_698ecfc5b205e.jpg', NULL, '2026-02-13 14:16:21', '2026-02-13 14:17:51'),
(44, 22, 14, 20000, 'qris', '4563322899', 'https://tokopedia.com', 'shipped', 'approved', 'paid', 'payment_1770966997_698ecfd5e2353.jpg', NULL, '2026-02-13 14:16:37', '2026-02-13 14:19:18'),
(45, 25, 13, 120000, 'transfer', '0976531212', 'https://tokopedia.com', 'shipped', 'approved', 'paid', 'payment_1771032902_698fd14648f62.png', NULL, '2026-02-14 08:35:02', '2026-02-14 08:42:20'),
(48, 25, 31, 12000, 'transfer', '098654567', 'https://tokopedia.com', 'shipped', 'approved', 'paid', 'payment_1771735354_699a893ab7fcf.png', NULL, '2026-02-22 11:42:34', '2026-02-22 11:43:16'),
(49, 25, 14, 30000, 'transfer', '1246712346', 'https://tokopedia.com', 'shipped', 'approved', 'paid', 'payment_1771748669_699abd3d30d01.png', NULL, '2026-02-22 15:24:29', '2026-02-22 15:29:42'),
(51, 25, 31, 12000, 'qris', '24335354', 'https://jne.com', 'shipped', 'approved', 'paid', 'payment_1772707897_69a960398e721.png', NULL, '2026-03-05 17:51:37', '2026-03-05 17:58:12'),
(52, 25, 14, 30000, 'qris', '2435435444', 'https://yputube.com', 'shipped', 'approved', 'paid', 'payment_1772713302_69a9755624dce.png', NULL, '2026-03-05 19:21:42', '2026-03-05 19:23:41'),
(53, 25, 31, 12000, 'qris', '3334344', 'https://yputube.com', 'shipped', 'approved', 'paid', 'payment_1772713302_69a9755624dce.png', NULL, '2026-03-05 19:21:42', '2026-03-05 19:23:45'),
(54, 25, 13, 125000, 'transfer', NULL, NULL, 'pending', 'pending', 'waiting_verification', 'payment_1772873845_13.png', NULL, '2026-03-07 15:57:25', NULL),
(55, 25, 14, 10000, 'qris', '09876546789', 'https://jne.com', 'shipped', 'approved', 'paid', 'payment_1772873845_14.png', NULL, '2026-03-07 15:57:25', '2026-03-07 16:03:27'),
(56, 25, 13, 125000, 'qris', NULL, NULL, 'pending', 'pending', 'waiting_verification', 'payment_1772874119_13.png', NULL, '2026-03-07 16:01:59', NULL),
(57, 25, 14, 20000, 'transfer', '121313132432', 'https://jne.com', 'shipped', 'approved', 'paid', 'payment_1772874119_14.png', NULL, '2026-03-07 16:01:59', '2026-03-07 16:04:00'),
(59, 25, 13, 125000, 'transfer', NULL, NULL, 'pending', 'pending', 'waiting_verification', 'payment_1772875602_13.png', NULL, '2026-03-07 16:26:42', NULL),
(60, 25, 14, 12000, 'qris', '0087765656565', 'https://jne.com', 'shipped', 'approved', 'paid', 'payment_1772875602_14.png', NULL, '2026-03-07 16:26:42', '2026-03-08 13:03:14'),
(61, 25, 13, 125000, 'transfer', NULL, NULL, 'pending', 'pending', 'waiting_verification', 'payment_1772875762_13.png', NULL, '2026-03-07 16:29:22', NULL),
(62, 25, 14, 12000, 'qris', '79779977899', 'https://jne.com', 'shipped', 'approved', 'paid', 'payment_1772875762_14.png', NULL, '2026-03-07 16:29:22', '2026-03-08 13:02:47'),
(63, 25, 13, 125000, 'transfer', NULL, NULL, 'pending', 'pending', 'waiting_verification', 'payment_1772876288_13.png', NULL, '2026-03-07 16:38:08', NULL),
(64, 25, 14, 10000, 'qris', '12345354355', 'https://jne.com', 'shipped', 'approved', 'paid', 'payment_1772876288_14.png', NULL, '2026-03-07 16:38:08', '2026-03-08 13:02:37'),
(65, 25, 14, 10000, 'qris', '098757887', 'https://jne.com', 'shipped', 'approved', 'paid', 'payment_1772954190_14.png', NULL, '2026-03-08 14:16:30', '2026-03-08 14:23:37');

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
  `subtotal` decimal(15,2) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `price`, `qty`, `subtotal`, `created_at`) VALUES
(1, 1, 7, 20900.00, 2, 41800.00, '2026-02-09 08:07:34'),
(2, 2, 7, 20900.00, 2, 41800.00, '2026-02-09 08:07:34'),
(3, 3, 2, 10000.00, 1, 10000.00, '2026-02-09 08:07:34'),
(4, 3, 6, 20000.00, 1, 20000.00, '2026-02-09 08:07:34'),
(5, 4, 6, 20000.00, 1, 20000.00, '2026-02-09 08:07:34'),
(6, 5, 2, 10000.00, 1, 10000.00, '2026-02-09 08:07:34'),
(7, 6, 2, 10000.00, 1, 10000.00, '2026-02-09 08:07:34'),
(8, 7, 2, 10000.00, 1, 10000.00, '2026-02-09 08:07:34'),
(9, 8, 7, 20900.00, 1, 20900.00, '2026-02-09 08:07:34'),
(10, 9, 2, 10000.00, 1, 10000.00, '2026-02-09 08:07:34'),
(11, 10, 7, 20900.00, 3, 62700.00, '2026-02-09 08:07:34'),
(12, 11, 2, 10000.00, 1, 10000.00, '2026-02-09 08:07:34'),
(13, 12, 8, 120000.00, 3, 360000.00, '2026-02-09 08:07:34'),
(14, 13, 7, 20900.00, 2, 41800.00, '2026-02-09 08:07:34'),
(15, 21, 9, 110000.00, 1, 110000.00, '2026-02-09 08:07:34'),
(22, 27, 8, 12000.00, 1, 12000.00, '2026-02-09 08:07:34'),
(25, 30, 2, 10000.00, 1, 10000.00, '2026-02-10 08:35:05'),
(26, 30, 8, 12000.00, 1, 12000.00, '2026-02-10 08:35:05'),
(27, 31, 6, 20000.00, 1, 20000.00, '2026-02-10 10:52:56'),
(28, 32, 11, 125000.00, 3, 375000.00, '2026-02-10 13:37:26'),
(29, 33, 11, 125000.00, 3, 375000.00, '2026-02-10 13:41:26'),
(30, 34, 2, 10000.00, 1, 10000.00, '2026-02-12 07:41:40'),
(31, 34, 6, 20000.00, 1, 20000.00, '2026-02-12 07:41:40'),
(32, 34, 8, 12000.00, 1, 12000.00, '2026-02-12 07:41:40'),
(33, 34, 10, 120000.00, 1, 120000.00, '2026-02-12 07:41:40'),
(34, 35, 11, 125000.00, 1, 125000.00, '2026-02-12 07:54:07'),
(35, 35, 12, 120000.00, 2, 240000.00, '2026-02-12 07:54:07'),
(36, 36, 6, 20000.00, 1, 20000.00, '2026-02-12 08:02:35'),
(37, 37, 8, 12000.00, 3, 36000.00, '2026-02-12 08:09:51'),
(38, 38, 10, 120000.00, 2, 240000.00, '2026-02-12 08:16:46'),
(39, 39, 8, 12000.00, 1, 12000.00, '2026-02-12 08:17:21'),
(40, 40, 10, 120000.00, 1, 120000.00, '2026-02-12 11:09:08'),
(41, 40, 6, 20000.00, 1, 20000.00, '2026-02-12 11:09:08'),
(42, 41, 12, 120000.00, 1, 120000.00, '2026-02-12 11:09:38'),
(43, 42, 11, 125000.00, 1, 125000.00, '2026-02-12 11:12:12'),
(44, 43, 11, 125000.00, 1, 125000.00, '2026-02-13 14:16:21'),
(45, 44, 6, 20000.00, 1, 20000.00, '2026-02-13 14:16:37'),
(46, 45, 12, 120000.00, 1, 120000.00, '2026-02-14 08:35:02'),
(49, 48, 15, 12000.00, 1, 12000.00, '2026-02-22 11:42:34'),
(50, 49, 2, 10000.00, 1, 10000.00, '2026-02-22 15:24:29'),
(51, 49, 6, 20000.00, 1, 20000.00, '2026-02-22 15:24:29'),
(53, 51, 15, 12000.00, 1, 12000.00, '2026-03-05 17:51:37'),
(54, 52, 6, 20000.00, 1, 20000.00, '2026-03-05 19:21:42'),
(55, 52, 2, 10000.00, 1, 10000.00, '2026-03-05 19:21:42'),
(56, 53, 15, 12000.00, 1, 12000.00, '2026-03-05 19:21:42'),
(57, 54, 11, 125000.00, 1, 125000.00, '2026-03-07 15:57:25'),
(58, 55, 2, 10000.00, 1, 10000.00, '2026-03-07 15:57:25'),
(59, 56, 11, 125000.00, 1, 125000.00, '2026-03-07 16:01:59'),
(60, 57, 6, 20000.00, 1, 20000.00, '2026-03-07 16:01:59'),
(62, 59, 11, 125000.00, 1, 125000.00, '2026-03-07 16:26:42'),
(63, 60, 8, 12000.00, 1, 12000.00, '2026-03-07 16:26:42'),
(64, 61, 11, 125000.00, 1, 125000.00, '2026-03-07 16:29:22'),
(65, 62, 8, 12000.00, 1, 12000.00, '2026-03-07 16:29:22'),
(66, 63, 11, 125000.00, 1, 125000.00, '2026-03-07 16:38:08'),
(67, 64, 2, 10000.00, 1, 10000.00, '2026-03-07 16:38:08'),
(68, 65, 2, 10000.00, 1, 10000.00, '2026-03-08 14:16:30');

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
(2, 'Castle in The Sky', 10000, 9000, 1000, 2, 'prod_69a953f7530b6.png', 'the best book', 14, 21, 1, NULL),
(6, 'Flovely and Unicorn', 20000, 15000, 5000, 2, 'prod_69a9545e1563c.png', 'About flores, nature, sea', 14, 12, 1, NULL),
(7, 'Go Youn Jung Profile', 20900, 900, 20000, 112, 'prod_6976f2b257dc1.jpg', 'Most Beautifull person', 13, 2, 0, '2026-01-30 08:21:49'),
(8, 'Time Travel', 12000, 10000, 2000, 4, 'prod_69a9594a741a1.png', 'This book about timw', 14, 24, 1, NULL),
(9, 'Go Youn Jung Profile', 110000, 80000, 30000, 123, 'prod_697c0a7c56c87.jpg', 'About Go Youn Jung aktrist from korea', 13, 23, 0, '2026-01-30 10:37:46'),
(10, 'Many Things', 120000, 100000, 20000, 0, 'prod_698acd4ade394.jpeg', 'wenak', 14, 19, 0, '2026-03-05 17:02:03'),
(11, 'Grow Flower', 125000, 100000, 25000, 780, 'prod_69a95f86650b2.png', 'How to Grow', 13, 23, 1, NULL),
(12, 'Enlight Yourself', 120000, 50000, 70000, 883, 'prod_69a95fb417006.png', 'Upgrade and know yourself', 13, 22, 1, NULL),
(15, 'Last Manages Stocks', 12000, 10000, 2000, 4, 'prod_699a89074e00c.jpg', 'Upgrade yourself', 31, 22, 1, NULL),
(16, 'Stocks', 123000, 120000, 3000, 0, 'prod_699a90b3473a4.jpg', 'yes', 14, 23, 0, '2026-02-22 12:14:54');

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
  `deleted_at` datetime DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `role` int(11) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `account_number` varchar(255) NOT NULL DEFAULT '',
  `qris_photo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `nik`, `email`, `password`, `phone`, `reset_token`, `reset_expiry`, `last_activity`, `deleted_at`, `address`, `role`, `avatar`, `account_number`, `qris_photo`) VALUES
(4, 'Rennard', '3175070508081001', 'rennardadit@gmail.com', '$2y$10$hyyzXlrqf/Zxqo0rizW5Y.rrwiwZgCMnL6PLFBawzx6bXCvfB2GBW', '', NULL, NULL, '2026-03-05 18:20:43', NULL, 'Jl. Kav Kuningan', 1, '4_ce857.jpg', '', ''),
(13, 'Goyunjung', '12432435', 'reganre23@gmail.com', '$2y$10$AYWgG/lwVYUWGZ.yDOWse..qZmt.D.o8rOsxG5DgbVTmcRkd6mhjq', '082213521416180', NULL, NULL, '2026-03-05 17:49:31', NULL, 'Jl.Pulo Jahe', 2, 'avatar_1771030782_204.png', '1120365478', 'qris_1772708544_805.png'),
(14, 'ChaMuhee', '15435484332', 'rennard95@gmail.com', '$2y$10$Ez3aIYoNyCV6tCAJC17tzOkiJPa77pQg7AqmN9ozYnVYEiGkk3PAm', '', '69e5562cb0698c6a848b5d8f85aa1ef32897efc2cad900324b532f46a5851c57', '2026-02-12 11:00:10', '2026-03-08 15:24:15', NULL, 'JL.Nusa Indah', 2, 'avatar_1771030773_765.png', '3545675643256', 'qris_14_1771748906.png'),
(15, 'Customer A', '1231463242422', 'jungie@gmail.com', '$2y$10$1UFBuZSLlyPZFhTyrSN5u.4SxbaiLk6Kh2AsWkNe9mEfUBp4gpMC.', '082213521461', NULL, NULL, '2026-02-12 11:13:10', '2026-02-13 14:12:36', 'Jl.Kanada Jepang', 3, 'avatar_1769596866_600.jpg', '', NULL),
(16, 'tama', '1687423942', 'tama@gmail.com', '$2y$10$IkDvU.p.BPof3V6woQo2Xe7kalXGZ63MDJaboGQ0qZKiPbJ78D1f6', '081384421151', NULL, NULL, '2026-02-12 08:18:05', NULL, 'Jl.Kanada Jepang', 3, 'avatar_1771033775_472.png', '', NULL),
(22, 'Gyj', '31750708091001', 'gyj@gmail.com', '$2y$10$vHv3LsPnRJoMyzMvqBuh.OntPZ/FsGAVr3E3DaeEl0IKf2r/QUZ7.', '0822135214611', NULL, NULL, '2026-02-13 20:00:24', '2026-02-13 20:03:17', 'Jl.Hiroshima', 3, '22_37230.jpg', '', NULL),
(23, 'gyj', '12345678', 'goyun@gmail.com', '$2y$10$InP5FBqkAieOUYU8HkFtV.Y8vKulmgdBc06SVX23xZhro06pN0PZm', '082236987745', NULL, NULL, '2026-02-14 07:39:49', '2026-02-14 07:59:56', 'Jl.Kanada Jepang', 3, 'avatar_1771029624_708.jpg', '', NULL),
(25, 'Edamb', '567888443', 'edamb@gmail.com', '$2y$10$e6Zgm5Y4mzwwoF8KVPA1O.ROg9vw/dfEPkYbPQ3KavFPx4i4vcOmC', '08221369885', NULL, NULL, '2026-03-08 15:24:16', NULL, 'Jl. Kav Kuningan', 3, '25_15bee.png', '', NULL),
(31, 'Seller', '0987654', 'selipah@gmail.com', '$2a$12$kg0RFStNYeDZ6nEoE1hkv.8Ticml5uQNqTtN/.LOsewTP6cT/0wRa', '', NULL, NULL, '2026-03-05 17:59:53', NULL, 'Jl.Pondok Kelapa', 2, 'avatar_1771735178_529.png', '0876543987', 'qris_31_1772708386.png'),
(32, 'seller', '123245453', 'seller@gmail.com', '$2y$10$v8fy8wDKRzsBFEY4Fza9U.VFC.E6g.rlTNTqFYZNkpdRTho29cy8u', '', NULL, NULL, '2026-03-11 17:53:47', '2026-02-22 11:55:20', 'Jl.Kanada Jepang', 2, 'avatar_1771736111_274.png', '0988898989', 'qris_1771736111_571.png'),
(33, 'customer', '09546877', 'customerb@gmail.com', '$2y$10$QTTaWrtv3EQzpQKmzs8BkOQ9WKDJYzN7ZoHpXt1VZIBVK3xB1sWo2', '08221352146111', NULL, NULL, '2026-03-06 06:09:44', NULL, 'Jl.Pondok Kelapa', 3, 'avatar_1771736870_243.png', '', NULL);

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
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `idx_room_created` (`room_id`,`created_at`);

--
-- Indexes for table `chat_rooms`
--
ALTER TABLE `chat_rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_customer_seller` (`customer_id`,`seller_id`),
  ADD KEY `seller_id` (`seller_id`),
  ADD KEY `idx_updated` (`updated_at`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `room_id` (`room_id`);

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
  ADD UNIQUE KEY `name` (`name`,`nik`),
  ADD KEY `role` (`role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `chat_rooms`
--
ALTER TABLE `chat_rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

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
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `chat_rooms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_rooms`
--
ALTER TABLE `chat_rooms`
  ADD CONSTRAINT `chat_rooms_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_rooms_ibfk_2` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_3` FOREIGN KEY (`room_id`) REFERENCES `chat_rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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

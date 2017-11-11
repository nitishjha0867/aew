-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2017 at 08:08 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bitsoabw_aew`
--

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `job_id` int(10) UNSIGNED NOT NULL,
  `job_num` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` int(10) UNSIGNED NOT NULL,
  `section` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `make` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_item_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `drawing_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `other_attachment` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_quantity` int(10) UNSIGNED NOT NULL,
  `product_rate` int(10) UNSIGNED NOT NULL,
  `discount` int(10) UNSIGNED NOT NULL,
  `due_date` date NOT NULL,
  `delivery_date` date NOT NULL,
  `challan_no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lr_no` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `late_delivery` tinyint(4) NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'job currently at',
  `comment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `invoice_status` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`job_id`, `job_num`, `order_id`, `section`, `make`, `product_item_code`, `description`, `drawing_no`, `other_attachment`, `product_quantity`, `product_rate`, `discount`, `due_date`, `delivery_date`, `challan_no`, `lr_no`, `late_delivery`, `status`, `comment`, `created_at`, `updated_at`, `invoice_status`) VALUES
(1, '17/1', 1, 'aaaa', 'zzzz', 'W-04', 'Iron Bits', '', NULL, 36, 10, 12, '2017-09-12', '2017-09-11', '', '', 0, '', '', '2017-09-15 19:33:57', '2017-09-15 19:33:57', 0),
(2, '17/2', 1, 'ssss', 'xxxx', 'W-02', 'Conveyor Belts', '', NULL, 360, 18, 11, '0000-00-00', '0000-00-00', '', '', 0, '', '', '2017-09-15 19:33:57', '2017-09-15 19:33:57', 0),
(3, '17/3', 1, 'dddd', 'cccc', 'W-01', 'Knobs', '', NULL, 12, 0, 0, '2017-09-27', '2017-09-23', '', '', 0, '', '', '2017-09-15 19:33:57', '2017-09-15 19:33:57', 0),
(4, '17/4', 2, 'qqqq', 'eeee', 'W-04', 'Iron Bits', '', NULL, 36, 12, 33, '2017-09-18', '2017-09-23', '', '', 0, '', '', '2017-09-21 19:25:11', '2017-09-21 19:25:11', 0),
(5, '17/5', 2, 'wwww', 'rrrr', 'W-03', 'Iron Rods', '', NULL, 152, 15, 22, '2017-09-10', '2017-09-09', '', '', 0, '', '', '2017-09-21 19:25:11', '2017-09-21 19:25:11', 0),
(6, '17/6', 3, 'qqqq', 'eeee', 'W-04', 'Iron Bits', '', NULL, 36, 12, 33, '2017-09-18', '2017-09-23', '', '', 0, '', '', '2017-09-21 19:28:05', '2017-09-21 19:28:05', 0),
(7, '17/7', 3, 'wwww', 'rrrr', 'W-03', 'Iron Rods', '', NULL, 152, 15, 22, '2017-09-10', '2017-09-09', '', '', 0, '', '', '2017-09-21 19:28:05', '2017-09-21 19:28:05', 0),
(8, '17/8', 4, 'qqqq', 'eeee', 'W-04', 'Iron Bits', '', NULL, 36, 12, 33, '2017-09-18', '2017-09-23', '', '', 0, '', '', '2017-09-21 19:30:02', '2017-09-21 19:30:02', 0),
(9, '17/9', 4, 'wwww', 'rrrr', 'W-03', 'Iron Rods', '', NULL, 152, 15, 22, '2017-09-10', '2017-09-09', '', '', 0, '', '', '2017-09-21 19:30:02', '2017-09-21 19:30:02', 0),
(10, '17/10', 5, 'qqqq', 'eeee', 'W-04', 'Iron Bits', '', NULL, 36, 12, 33, '2017-09-18', '2017-09-23', '', '', 0, '', '', '2017-09-21 19:30:22', '2017-09-21 19:30:22', 0),
(11, '17/11', 5, 'wwww', 'rrrr', 'W-03', 'Iron Rods', '', NULL, 152, 15, 22, '2017-09-10', '2017-09-09', '', '', 0, '', '', '2017-09-21 19:30:22', '2017-09-21 19:30:22', 0),
(12, '17/12', 6, 'qqqq', 'eeee', 'W-04', 'Iron Bits', '', NULL, 36, 12, 33, '2017-09-18', '2017-09-23', '', '', 0, '', '', '2017-09-21 19:31:50', '2017-09-21 19:31:50', 0),
(13, '17/13', 6, 'wwww', 'rrrr', 'W-03', 'Iron Rods', '', NULL, 152, 15, 22, '2017-09-10', '2017-09-09', '', '', 0, '', '', '2017-09-21 19:31:50', '2017-09-21 19:31:50', 0),
(14, '17/14', 7, 'qqqq', 'eeee', 'W-04', 'Iron Bits', '', NULL, 36, 12, 33, '2017-09-18', '2017-09-23', '', '', 0, '', '', '2017-09-21 19:32:20', '2017-09-21 19:32:20', 0),
(15, '17/15', 7, 'wwww', 'rrrr', 'W-03', 'Iron Rods', '', NULL, 152, 15, 22, '2017-09-10', '2017-09-09', '', '', 0, '', '', '2017-09-21 19:32:20', '2017-09-21 19:32:20', 0),
(16, '17/16', 8, 'qqqq', 'eeee', 'W-04', 'Iron Bits', '', NULL, 36, 12, 33, '2017-09-18', '2017-09-23', '', '', 0, '', '', '2017-09-21 19:35:34', '2017-09-21 19:35:34', 0),
(17, '17/17', 8, 'wwww', 'rrrr', 'W-03', 'Iron Rods', '', NULL, 152, 15, 22, '2017-09-10', '2017-09-09', '', '', 0, '', '', '2017-09-21 19:35:34', '2017-09-21 19:35:34', 0),
(18, '17/18', 9, 'qqqq', 'eeee', 'W-04', 'Iron Bits', '', NULL, 36, 12, 33, '2017-09-18', '2017-09-23', '', '', 0, '', '', '2017-09-21 19:37:16', '2017-09-21 19:37:16', 0),
(19, '17/19', 9, 'wwww', 'rrrr', 'W-03', 'Iron Rods', '', NULL, 152, 15, 22, '2017-09-10', '2017-09-09', '', '', 0, '', '', '2017-09-21 19:37:16', '2017-09-21 19:37:16', 0);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(10) UNSIGNED NOT NULL,
  `order_num` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_date` date NOT NULL,
  `order_copy` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `plant_id` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_num`, `order_date`, `order_copy`, `plant_id`, `created_at`, `updated_at`) VALUES
(1, 'ASQWZX454545', '2017-09-26', '', 3, '2017-09-15 19:33:57', '2017-09-15 19:33:57'),
(2, 'YOG123', '2017-09-05', 'sample-logo-229x72.png', 3, '2017-09-21 19:25:10', '2017-09-21 19:25:10'),
(3, 'YOG123', '2017-09-05', 'sample-logo-229x72.png', 3, '2017-09-21 19:28:04', '2017-09-21 19:28:04'),
(4, 'YOG123', '2017-09-05', 'sample-logo-229x72.png', 3, '2017-09-21 19:30:02', '2017-09-21 19:30:02'),
(5, 'YOG123', '2017-09-05', 'sample-logo-229x72.png', 3, '2017-09-21 19:30:22', '2017-09-21 19:30:22'),
(6, 'YOG123', '2017-09-05', 'sample-logo-229x72.png', 3, '2017-09-21 19:31:50', '2017-09-21 19:31:50'),
(7, 'YOG123', '2017-09-05', 'sample-logo-229x72.png', 3, '2017-09-21 19:32:20', '2017-09-21 19:32:20'),
(8, 'YOG123', '2017-09-05', 'sample-logo-229x72.png', 3, '2017-09-21 19:35:34', '2017-09-21 19:35:34'),
(9, 'YOG123', '2017-09-05', 'sample-logo-229x72.png', 3, '2017-09-21 19:37:16', '2017-09-21 19:37:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`job_id`),
  ADD KEY `jobs_product_item_code_foreign` (`product_item_code`),
  ADD KEY `jobs_order_id_index` (`order_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `orders_plant_id_index` (`plant_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `job_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `jobs_product_item_code_foreign` FOREIGN KEY (`product_item_code`) REFERENCES `products` (`product_item_code`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_plant_id_foreign` FOREIGN KEY (`plant_id`) REFERENCES `plants` (`plant_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

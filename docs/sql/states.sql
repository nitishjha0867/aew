-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 08, 2017 at 09:28 PM
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
-- Table structure for table `states`
--

CREATE TABLE IF NOT EXISTS `states` (
  `state_id` int(10) UNSIGNED NOT NULL,
  `state` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `states`
--

INSERT INTO `states` (`state_id`, `state`, `active`) VALUES
(1, 'Andaman & Nicobar Islands', 1),
(2, 'Andhra Pradesh', 1),
(3, 'Arunachal Pradesh', 1),
(4, 'Assam', 1),
(5, 'Bihar', 1),
(6, 'Chhattisgarh', 1),
(7, 'Dadra & Nagar Haveli', 1),
(8, 'Daman & Diu', 1),
(9, 'Delhi', 1),
(10, 'Goa', 1),
(11, 'Gujarat', 1),
(12, 'Haryana', 1),
(13, 'Himachal Pradesh', 1),
(14, 'Jammu & Kashmir', 1),
(15, 'Jharkhand', 1),
(16, 'Karnataka', 1),
(17, 'Kerala', 1),
(18, 'Lakshadweep', 1),
(19, 'Madhya Pradesh', 1),
(20, 'Maharashtra', 1),
(21, 'Manipur', 1),
(22, 'Meghalaya', 1),
(23, 'Mizoram', 1),
(24, 'Nagaland', 1),
(25, 'Orissa', 1),
(26, 'Puducherry', 1),
(27, 'Punjab', 1),
(28, 'Rajasthan', 1),
(29, 'Sikkim', 1),
(30, 'Tamil Nadu', 1),
(31, 'Telangana', 1),
(32, 'Tripura', 1),
(33, 'Uttar Pradesh', 1),
(34, 'Uttarakhand', 1),
(35, 'West Bengal', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `states`
--
-- ALTER TABLE `states`
--   ADD PRIMARY KEY (`state_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `state_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

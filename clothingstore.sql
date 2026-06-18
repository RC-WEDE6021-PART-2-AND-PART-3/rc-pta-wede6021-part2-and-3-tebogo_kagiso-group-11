-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2026 at 01:18 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clothingstore`
--
CREATE DATABASE IF NOT EXISTS `clothingstore` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `clothingstore`;

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

DROP TABLE IF EXISTS `tbladmin`;
CREATE TABLE IF NOT EXISTS `tbladmin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT DELAYED IGNORE INTO `tbladmin` (`admin_id`, `username`, `password`, `email`, `fullname`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'admin@clothingstore.com', 'Store Admin');

-- --------------------------------------------------------

--
-- Table structure for table `tblaorder`
--

DROP TABLE IF EXISTS `tblaorder`;
CREATE TABLE IF NOT EXISTS `tblaorder` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `clothes_id` int(11) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `quantity` int(11) DEFAULT 1,
  `total_price` decimal(10,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`),
  KEY `clothes_id` (`clothes_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tblclothes`
--

DROP TABLE IF EXISTS `tblclothes`;
CREATE TABLE IF NOT EXISTS `tblclothes` (
  `clothes_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `size` varchar(10) DEFAULT NULL,
  `condition_status` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`clothes_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblclothes`
--

INSERT DELAYED IGNORE INTO `tblclothes` (`clothes_id`, `name`, `brand`, `price`, `category`, `size`, `condition_status`) VALUES
(1, 'Vintage Logo Tee', 'ELLESSE', 250.00, 'Men', 'L', 'Like New'),
(2, 'Obsessed To Progress Tee', 'REDBAT', 180.00, 'Men', 'M', 'Good'),
(3, 'Originals Trefoil Tee', 'ADIDAS', 350.00, 'Men', 'XL', 'Excellent'),
(4, 'Old Skool', 'VANS', 420.00, 'Shoes', '42', 'Good'),
(5, 'Classic Hoodie', 'NIKE', 550.00, 'Men', 'L', 'Like New');

-- --------------------------------------------------------

--
-- Table structure for table `tbluser`
--

DROP TABLE IF EXISTS `tbluser`;
CREATE TABLE IF NOT EXISTS `tbluser` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbluser`
--

INSERT DELAYED IGNORE INTO `tbluser` (`user_id`, `fullname`, `email`, `username`, `password`, `status`, `created_at`) VALUES
(1, 'John Doe', 'john.doe@email.com', 'john.doe@email.com', '482c811da5d5b4bc6d497ffa98491e38', 'approved', '2026-05-03 23:03:01'),
(2, 'Jane Smith', 'jane.smith@email.com', 'jane.smith@email.com', '34819d7beeabb9260a5c854bc85b3e44', 'approved', '2026-05-03 23:03:01'),
(3, 'Bob Johnson', 'bob.j@email.com', 'bob.j@email.com', '8df7ef69c62f9359a656ec7aab3d705f', 'pending', '2026-05-03 23:03:01'),
(4, 'Alice Brown', 'alice.b@email.com', 'alice.b@email.com', '52b20b64fe5c0a415acbcb6e1836c570', 'approved', '2026-05-03 23:03:01'),
(5, 'Charlie Wilson', 'charlie.w@email.com', 'charlie.w@email.com', 'ad319dbc63d687f4f9623bd28157ae89', 'pending', '2026-05-03 23:03:01');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tblaorder`
--
ALTER TABLE `tblaorder`
  ADD CONSTRAINT `tblaorder_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbluser` (`user_id`),
  ADD CONSTRAINT `tblaorder_ibfk_2` FOREIGN KEY (`clothes_id`) REFERENCES `tblclothes` (`clothes_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

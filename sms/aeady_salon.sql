-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 16, 2025 at 08:19 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aeady salon`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `appointment_number` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `service` varchar(255) NOT NULL,
  `appointment_date` date NOT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `appointment_number`, `name`, `email`, `phone`, `service`, `appointment_date`, `message`, `created_at`) VALUES
(1, 'APT1735898284475', 'Edikan Emmanuel Effiom', 'aeadikanemmanuel@gmail.com', '09038585075', 'Hair Coloring', '2025-01-16', 'edrfgthjk', '2025-01-03 09:58:04');

-- --------------------------------------------------------

--
-- Table structure for table `billing`
--

CREATE TABLE `billing` (
  `id` int(11) NOT NULL,
  `details` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `AptNumber` varchar(255) NOT NULL,
  `ClientName` varchar(255) NOT NULL,
  `UserID` int(11) NOT NULL,
  `ServiceID` int(11) NOT NULL,
  `StylistID` int(11) DEFAULT NULL,
  `Message` text DEFAULT NULL,
  `AptDate` date NOT NULL,
  `AptTime` time NOT NULL,
  `Status` enum('Pending','Accepted','Declined') DEFAULT 'Pending',
  `BookingDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `PhoneNumber` int(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `AptNumber`, `ClientName`, `UserID`, `ServiceID`, `StylistID`, `Message`, `AptDate`, `AptTime`, `Status`, `BookingDate`, `PhoneNumber`) VALUES
(8, 'APTB08EA2B4', 'Edikan Emmanuel Effiom', 1, 2, 1, 'gjdfgfgjjbjk', '2025-01-09', '07:17:00', 'Accepted', '2025-01-09 15:17:47', 2147483647),
(9, 'APT212FAC6B', 'Edikan Emmanuel Effiom', 1, 3, 1, 'tyjgvbybhb', '2025-01-02', '04:29:00', 'Accepted', '2025-01-17 12:29:51', 2147483647),
(13, 'APT4F3CD26A', 'Edikan  Effiom', 1, 18, 3, 'renijfjirj', '2025-02-11', '12:11:00', 'Declined', '2025-01-26 18:11:26', 2147483647),
(14, 'APT146F0E52', 'Edikan Emmanuel Effiom', 1, 21, 1, 'thank you', '2025-02-06', '16:51:00', 'Accepted', '2025-01-29 12:48:13', 2147483647),
(15, 'APT02FC4B4D', 'Collins Smith', 1, 15, 1, 'asdfghjklsdfghjkl;', '2025-02-25', '08:26:00', 'Accepted', '2025-02-10 13:23:18', 2147483647);

-- --------------------------------------------------------

--
-- Table structure for table `follow_ups`
--

CREATE TABLE `follow_ups` (
  `id` int(11) NOT NULL,
  `ClientID` int(11) NOT NULL,
  `FollowupType` varchar(50) NOT NULL,
  `Message` text NOT NULL,
  `SendDate` date NOT NULL,
  `SendTime` time NOT NULL,
  `Status` varchar(20) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`id`, `name`, `email`, `phone`, `message`, `created_at`) VALUES
(1, 'Edikan Emmanuel', 'aeadikan223@gmail.com', '09038585075', 'where are you located at?', '2025-01-02 17:26:20'),
(2, 'Edikan Emmanuel', 'aeadikan223@gmail.com', '09038585075', 'where are you located at?', '2025-01-02 17:41:28'),
(5, 'fab', 'aeadikanemmanuel@gmail.com', '09038585075', 'i love okpa', '2025-01-02 19:14:37'),
(6, 'Edikan Emmanuel Effiom', 'aeadikanemmanuel@gmail.com', '09038585076', 'w4e5crvtbuhinjk', '2025-01-08 11:54:35');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT 'Reference to the user making the purchase',
  `service_id` int(11) DEFAULT NULL COMMENT 'Reference to the purchased service',
  `billing_id` int(11) DEFAULT NULL COMMENT 'Reference to the billing information',
  `price` decimal(10,2) DEFAULT NULL COMMENT 'Price of the service',
  `quantity` int(11) DEFAULT 1 COMMENT 'Quantity of the service purchased',
  `total_amount` decimal(10,2) GENERATED ALWAYS AS (`price` * `quantity`) STORED COMMENT 'Total amount for the invoice',
  `status` enum('Pending','Completed','Cancelled') DEFAULT 'Pending' COMMENT 'Status of the invoice',
  `posting_date` timestamp NULL DEFAULT current_timestamp() COMMENT 'Date and time of the transaction'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Table for storing invoice details';

-- --------------------------------------------------------

--
-- Table structure for table `pricing_rules`
--

CREATE TABLE `pricing_rules` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `rule_name` varchar(255) NOT NULL,
  `rule_type` enum('time','day','season') NOT NULL,
  `value` varchar(255) NOT NULL,
  `adjustment_type` enum('fixed','percentage') NOT NULL,
  `adjustment_value` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pricing_rules`
--

INSERT INTO `pricing_rules` (`id`, `service_id`, `rule_name`, `rule_type`, `value`, `adjustment_type`, `adjustment_value`) VALUES
(1, 14, 'Peak Hours Surcharge', 'time', '18:00-21:00', 'percentage', 15.00),
(2, 15, 'Weekend Discount', 'day', 'Saturday,Sunday', 'percentage', -10.00),
(3, 16, 'Holiday Promo', 'season', 'Christmas', 'fixed', -500.00),
(4, 17, 'Summer Discount', 'season', 'Summer', 'percentage', -5.00),
(5, 18, 'Peak Hours Surcharge', 'time', '18:00-21:00', 'percentage', 20.00),
(6, 19, 'Weekend Promo', 'day', 'Saturday,Sunday', 'fixed', -200.00),
(7, 20, 'Holiday Premium', 'season', 'New Year', 'percentage', 10.00),
(8, 21, 'Midweek Discount', 'day', 'Wednesday', 'percentage', -7.00),
(9, 22, 'Seasonal Adjustment', 'season', 'Rainy Season', 'fixed', -100.00),
(10, 23, 'Evening Surcharge', 'time', '18:00-21:00', 'percentage', 15.00);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `service_name`, `description`, `image`, `price`, `created_at`, `updated_at`) VALUES
(14, 'Hair wash with Blow dry', 'A refreshing hair wash followed by a professional blow-dry to leave your hair clean, shiny, and styled.', '../images/hairwash.jpg', 1500.00, '2025-01-22 16:44:12', '2025-01-22 16:44:12'),
(15, 'Color & highlights', 'Transform your look with professional hair coloring and highlighting, tailored to suit your style.', '../images/color_highlights.jpg', 2000.00, '2025-01-22 16:44:12', '2025-01-22 16:44:12'),
(16, 'Straightening', 'Achieve sleek, smooth, and frizz-free hair with our professional hair straightening service.', '../images/straightening.jpg', 2000.00, '2025-01-22 16:44:12', '2025-01-22 16:44:12'),
(17, 'Blow Drying', 'Get voluminous, perfectly styled hair with our expert blow-drying service.', '../images/blow_drying.jpg', 1000.00, '2025-01-22 16:44:12', '2025-01-22 16:44:12'),
(18, 'Hair Treatment', 'Restore and rejuvenate your hair with specialized treatments for improved health and shine.', '../images/hair_treat.jpg', 5000.00, '2025-01-22 16:44:12', '2025-01-22 16:44:12'),
(19, 'Braiding', 'Choose from a variety of stylish and intricate braid styles, perfect for every occasion.', '../images/braiding.jpg', 4000.00, '2025-01-22 16:44:12', '2025-01-22 16:44:12'),
(20, 'Weaving', 'Get beautifully styled weaves that enhance your look with a natural and seamless finish.', '../images/weaving.jpg', 3000.00, '2025-01-22 16:44:12', '2025-01-22 16:44:12'),
(21, 'Hair Styling', 'Expert styling services to create a look that suits your personality and the occasion.', '../images/styling.jpg', 2500.00, '2025-01-22 16:44:12', '2025-01-22 16:44:12'),
(22, 'Hair Loosening', 'Gentle and efficient hair loosening services to ensure your hair stays healthy and damage-free.', '../images/hair_losening.jpg', 1000.00, '2025-01-22 16:44:12', '2025-01-22 16:44:12'),
(23, 'Hair Extensions', 'Enhance your hair length and volume with our high-quality hair extension services, expertly applied for a natural finish.', '../images/hair-extension.jpg', 3000.00, '2025-01-22 16:44:12', '2025-01-22 16:44:12');

-- --------------------------------------------------------

--
-- Table structure for table `stylists`
--

CREATE TABLE `stylists` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `available` tinyint(1) DEFAULT 1,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stylists`
--

INSERT INTO `stylists` (`id`, `name`, `available`, `image`) VALUES
(1, 'Edikan', 1, NULL),
(3, 'Samantha', 1, '8fdf86391b565afcceb060f76c0b433b1736597563.jpg'),
(4, 'Godson', 1, '287dea4f4cce3c01aa25caa1e00a96371736598063.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `ID` int(10) NOT NULL,
  `AdminName` char(50) DEFAULT NULL,
  `UserName` char(50) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `Password` varchar(200) DEFAULT NULL,
  `AdminRegdate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`ID`, `AdminName`, `UserName`, `MobileNumber`, `Email`, `Password`, `AdminRegdate`) VALUES
(1, 'Admin', 'admin', 7898799798, 'tester1@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2024-05-01 13:21:50');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Edikan Emmanuel', 'aeadikanemmanuel@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', '2025-01-03 08:46:10'),
(2, 'John Doe', 'john@example.com', '482c811da5d5b4bc6d497ffa98491e38', '2025-01-03 09:49:36'),
(3, 'Collins Smith', 'collins@gmail.com', '674f3c2c1a8a6f90461e8a66fb5550ba', '2025-02-10 13:26:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `appointment_number` (`appointment_number`);

--
-- Indexes for table `billing`
--
ALTER TABLE `billing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `AptNumber` (`AptNumber`);

--
-- Indexes for table `follow_ups`
--
ALTER TABLE `follow_ups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ClientID` (`ClientID`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id_fk` (`user_id`),
  ADD KEY `service_id_fk` (`service_id`),
  ADD KEY `billing_id_fk` (`billing_id`);

--
-- Indexes for table `pricing_rules`
--
ALTER TABLE `pricing_rules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_service_name` (`service_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `billing`
--
ALTER TABLE `billing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `follow_ups`
--
ALTER TABLE `follow_ups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pricing_rules`
--
ALTER TABLE `pricing_rules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `follow_ups`
--
ALTER TABLE `follow_ups`
  ADD CONSTRAINT `follow_ups_ibfk_1` FOREIGN KEY (`ClientID`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `billing_id_fk` FOREIGN KEY (`billing_id`) REFERENCES `billing` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `service_id_fk` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `pricing_rules`
--
ALTER TABLE `pricing_rules`
  ADD CONSTRAINT `pricing_rules_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

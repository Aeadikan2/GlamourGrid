-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 09, 2025 at 09:44 PM
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
-- Table structure for table `billing`
--

CREATE TABLE `billing` (
  `id` int(11) NOT NULL,
  `details` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `billing`
--

INSERT INTO `billing` (`id`, `details`) VALUES
(206096942, 'order for 206096942'),
(214190343, 'order for 214190343'),
(311969982, 'order for 311969982'),
(327778153, 'order for 327778153'),
(351083361, 'order for 351083361'),
(430553432, 'order for 430553432'),
(535232939, 'order for 535232939'),
(549803976, 'order for 549803976'),
(622556792, 'order for 622556792'),
(663626516, 'order for 663626516'),
(744412992, 'order for 744412992'),
(789490146, 'order for 789490146'),
(895887789, 'order for 895887789');

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
  `PhoneNumber` int(15) NOT NULL,
  `reminded` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `AptNumber`, `ClientName`, `UserID`, `ServiceID`, `StylistID`, `Message`, `AptDate`, `AptTime`, `Status`, `BookingDate`, `PhoneNumber`, `reminded`) VALUES
(17, 'APTC3F186D3', 'Edikan Emmanuel Effiom', 4, 15, 4, 'asdfghjkwertyuiocvbn', '2025-03-05', '17:43:00', 'Accepted', '2025-02-24 21:44:04', 2147483647, 0),
(18, 'APT610B28B4', 'Collins Smith', 3, 16, 3, 'zxcvbndfghjkrtyui', '2025-03-06', '07:29:00', 'Accepted', '2025-02-24 23:30:02', 2147483647, 0),
(19, 'APTE72FD6B5', 'werty', 4, 18, 3, 'asdfghertyhj', '2025-03-06', '18:11:00', 'Accepted', '2025-02-26 10:11:40', 2147483647, 0),
(23, 'APT446BE6A8', 'Edikan Emmanuel Effiom', 6, 15, 3, 'wndnjndijdbhbiub', '2025-03-12', '07:30:00', 'Accepted', '2025-03-04 13:36:24', 2147483647, 0),
(24, 'APT3135F2D1', 'Edikan Emmanuel Effiom', 6, 18, 3, 'aesdrctfygvbhunijmk,l', '2025-03-12', '23:16:00', 'Accepted', '2025-03-08 16:16:42', 2147483647, 0);

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
(9, 'Edikan Emmanuel Effiom', 'aeadikanemmanuel@gmail.com', '09038585075', 'where are you at?', '2025-03-05 01:32:45');

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

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`id`, `user_id`, `service_id`, `billing_id`, `price`, `quantity`, `status`, `posting_date`) VALUES
(18, NULL, 14, 622556792, 1500.00, 1, 'Pending', '2025-02-24 21:01:32'),
(19, NULL, 20, 622556792, 3000.00, 1, 'Pending', '2025-02-24 21:01:32'),
(20, NULL, 22, 622556792, 1000.00, 1, 'Pending', '2025-02-24 21:01:32'),
(23, 3, 15, 214190343, 2000.00, 1, 'Pending', '2025-02-24 23:31:44'),
(25, NULL, 18, 327778153, 5000.00, 1, 'Pending', '2025-02-26 10:15:06'),
(26, 6, 18, 895887789, 5000.00, 1, 'Pending', '2025-03-04 13:52:53');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `title`, `content`, `created_at`) VALUES
(1, 6, 'money', 'sdfghjcvbn', '2025-03-04 21:34:51');

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
  `phone` varchar(20) NOT NULL,
  `image` varchar(255) DEFAULT 'default.jpg',
  `address` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `image`, `address`, `password`, `created_at`) VALUES
(2, 'John Doe', 'john@example.com', '', 'default.jpg', '', '482c811da5d5b4bc6d497ffa98491e38', '2025-01-03 09:49:36'),
(3, 'Collins Smith', 'collins@gmail.com', '', 'default.jpg', '', '674f3c2c1a8a6f90461e8a66fb5550ba', '2025-02-10 13:26:12'),
(6, 'Edikan Emmanuel Effiom', 'aeadikan@gmail.com', '09038585075', 'c2.jpg', 'KMC street, Flat 1', '$2y$10$Y1LcBvKnlCyyB6jpfvU2TOohq5G0VfC7pvVFrF9RnG6fyNCKliaM2', '2025-03-04 12:22:00');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `billing`
--
ALTER TABLE `billing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=895887790;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `billing_id_fk` FOREIGN KEY (`billing_id`) REFERENCES `billing` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `service_id_fk` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `user_id_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 15, 2025 at 11:54 AM
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
-- Database: `hotel_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `check_in_date` date NOT NULL,
  `check_out_date` date NOT NULL,
  `status` enum('reserved','paid') DEFAULT 'reserved',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `room_id`, `check_in_date`, `check_out_date`, `status`, `created_at`) VALUES
(9, 2, 2, '2025-05-13', '2025-05-14', 'paid', '2025-05-13 15:52:25'),
(12, 2, 3, '2025-05-12', '2025-05-15', 'paid', '2025-05-14 04:53:18'),
(15, 2, 4, '2025-05-14', '2025-05-16', 'paid', '2025-05-14 06:18:36'),
(16, 2, 1, '2025-05-17', '2025-05-21', 'paid', '2025-05-14 06:27:25'),
(17, 2, 2, '2025-05-17', '2025-05-21', 'paid', '2025-05-14 06:28:13'),
(19, 3, 4, '2025-05-20', '2025-05-21', 'paid', '2025-05-15 05:38:00'),
(22, 3, 4, '2025-05-17', '2025-05-19', 'paid', '2025-05-15 06:00:16'),
(23, 6, 6, '2025-05-17', '2025-05-18', 'reserved', '2025-05-15 07:02:03'),
(24, 6, 6, '2025-05-20', '2025-05-20', 'paid', '2025-05-15 07:06:38'),
(26, 6, 3, '2025-05-18', '2025-05-19', 'reserved', '2025-05-15 07:14:40'),
(31, 2, 1, '2025-05-15', '2025-05-15', 'paid', '2025-05-15 09:42:40'),
(32, 4, 3, '2025-05-23', '2025-05-27', 'reserved', '2025-05-15 09:45:12');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','paid') DEFAULT 'pending',
  `paid_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `booking_id`, `amount`, `status`, `paid_at`) VALUES
(9, 9, 500.00, 'paid', '2025-05-13 21:59:31'),
(12, 12, 500.00, 'paid', '2025-05-14 11:36:26'),
(15, 15, 800.00, 'paid', '2025-05-14 12:18:56'),
(16, 17, 2000.00, 'paid', '2025-05-14 12:51:57'),
(18, 19, 400.00, 'paid', '2025-05-15 11:38:26'),
(20, 22, 1200.00, 'paid', '2025-05-15 12:00:35'),
(21, 23, 2400.00, 'pending', NULL),
(22, 24, 1200.00, 'paid', '2025-05-15 13:11:33'),
(24, 26, 1000.00, 'pending', NULL),
(29, 31, 500.00, 'paid', '2025-05-15 15:44:10'),
(30, 32, 2500.00, 'pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_number` varchar(10) NOT NULL,
  `capacity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `is_available` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_number`, `capacity`, `price`, `is_available`) VALUES
(1, '101', 2, 500.00, 1),
(2, '102', 2, 500.00, 1),
(3, '103', 2, 500.00, 1),
(4, '104', 1, 400.00, 1),
(6, '105', 3, 1200.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `fname` varchar(30) NOT NULL,
  `lname` varchar(30) NOT NULL,
  `role` enum('customer','manager') DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `fname`, `lname`, `role`) VALUES
(1, 'admin', '$2y$10$QqctlDSxe8o0PZNmrEgWs.mAz1eLkId3JjdN0bf7ggX/PmoQfEJM2', '', '', 'manager'),
(2, 'john', '$2y$10$QqctlDSxe8o0PZNmrEgWs.mAz1eLkId3JjdN0bf7ggX/PmoQfEJM2', 'John', 'Hugo', 'customer'),
(3, 'alice', '$2y$10$v9W/lrod0gm8ImuUjqDNI..yxdR4zarCsos/ofUa.hqGuMt4Vwqx2', 'Alice', 'Fletcher', 'customer'),
(4, 'bob', '$2y$10$vwGYbvqi1s4zgW7Uz0PbGeAkGudgPLTsyYAaUrz7urNb6vMoMDn8.', 'Bob', 'Kennedy', 'customer'),
(6, 'carol', '$2y$10$YbC9gxOg6a5NVBo8i/BFlOMDGTEBvf8yI8fgp/bwzGLWf0rXjhXg2', 'Carol', 'Ine', 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_number` (`room_number`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

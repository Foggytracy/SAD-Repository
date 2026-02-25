-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2026 at 06:48 AM
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
-- Database: `attendance_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', 'admin123', '2026-02-15 04:16:43'),
(2, 'superadmin', 'password123', '2026-02-15 04:16:43');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `leave_status` enum('None','Sick','Vacation','Other') DEFAULT 'None',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `employee_id`, `date`, `time_in`, `time_out`, `leave_status`, `created_at`) VALUES
(75, 5, '2026-02-24', '22:04:16', NULL, 'None', '2026-02-24 14:04:16'),
(76, 6, '2026-02-24', '22:04:21', NULL, 'None', '2026-02-24 14:04:21'),
(77, 7, '2026-02-24', '22:04:51', NULL, 'None', '2026-02-24 14:04:51'),
(78, 1, '2026-02-25', '08:51:52', '09:23:17', 'None', '2026-02-25 00:51:52'),
(79, 2, '2026-02-25', '08:51:55', NULL, 'None', '2026-02-25 00:51:55'),
(80, 3, '2026-02-25', '08:51:58', NULL, 'None', '2026-02-25 00:51:58'),
(81, 4, '2026-02-25', '08:52:01', NULL, 'None', '2026-02-25 00:52:01');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `employee_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `department` varchar(50) NOT NULL,
  `schedule_in` time NOT NULL,
  `schedule_out` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `daily_rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` varchar(20) DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`employee_id`, `first_name`, `last_name`, `department`, `schedule_in`, `schedule_out`, `created_at`, `daily_rate`, `status`) VALUES
(1, 'John Lor Ganary', 'Daguyos', 'HR', '00:00:09', '17:00:00', '2026-02-24 05:04:59', 6000.00, 'Inactive'),
(2, 'Nicholas', 'Bayles', 'HR', '07:00:00', '16:00:00', '2026-02-14 15:40:51', 819.81, 'Active'),
(3, 'Raul', 'Bernal', 'HR', '07:00:00', '16:00:00', '2026-02-14 15:40:51', 1212.92, 'Active'),
(4, 'Franco', 'Victor', 'HR', '07:00:00', '16:00:00', '2026-02-14 15:40:51', 924.50, 'Active'),
(5, 'Renier', 'Arimas', 'HR', '08:00:00', '17:00:00', '2026-02-14 15:40:51', 819.81, 'Active'),
(6, 'Princess Joana', 'Faraon', 'HR', '08:00:00', '17:00:00', '2026-02-14 15:40:51', 819.81, 'Active'),
(7, 'Rainier', 'Nadal', 'HR', '08:00:00', '17:00:00', '2026-02-14 15:40:51', 1212.92, 'Active'),
(8, 'Gerald', 'Ramos', 'HR', '07:00:00', '16:00:00', '2026-02-14 15:40:51', 1212.92, 'Active'),
(9, 'Dindo', 'Urbano', 'HR', '08:00:00', '17:00:00', '2026-02-14 15:40:51', 924.50, 'Active'),
(10, 'Rey', 'Alba', 'HR', '09:00:00', '18:00:00', '2026-02-14 15:40:51', 819.81, 'Active'),
(11, 'Rodel', 'Monterde', 'HR', '09:00:00', '18:00:00', '2026-02-14 15:40:51', 924.50, 'Active'),
(12, 'Armando', 'Ramos', 'HR', '09:00:00', '18:00:00', '2026-02-14 15:40:51', 914.85, 'Active'),
(13, 'Romerico', 'Lorenzo', 'Finance', '07:00:00', '16:00:00', '2026-02-14 15:40:51', 819.81, 'Active'),
(14, 'Maricelle', 'Sibug', 'Finance', '07:00:00', '16:00:00', '2026-02-14 15:40:51', 1346.15, 'Active'),
(15, 'Res Ann', 'Apolinario', 'Finance', '00:00:09', '18:00:00', '2026-02-14 15:40:51', 1346.15, 'Active'),
(16, 'Marisol', 'Botor', 'Finance', '08:00:00', '17:00:00', '2026-02-14 15:40:51', 1030.38, 'Active'),
(17, 'Catherine Joy', 'Caoile', 'Finance', '08:00:00', '17:00:00', '2026-02-14 15:40:51', 1030.38, 'Active'),
(18, 'Mark Anthony', 'Cruz', 'Finance', '08:00:00', '17:00:00', '2026-02-14 15:40:51', 1346.15, 'Active'),
(19, 'Roxann', 'Guevarra', 'Finance', '08:00:00', '17:00:00', '2026-02-14 15:40:51', 1030.38, 'Active'),
(20, 'Ciara Michaella', 'Mamaril', 'Finance', '08:00:00', '17:00:00', '2026-02-14 15:40:51', 819.81, 'Active'),
(21, 'Arniel', 'Regio', 'Finance', '00:00:08', '17:00:00', '2026-02-14 15:40:51', 914.85, 'Active'),
(22, 'Abbygail Joie', 'San Juan', 'Finance', '08:00:00', '17:00:00', '2026-02-14 15:40:51', 819.81, 'Active'),
(23, 'Joseph Michael', 'Pascual', 'Operations', '08:00:00', '17:00:00', '2026-02-14 15:40:51', 819.81, 'Active'),
(24, 'Lemuel', 'Ferolino', 'Operations', '09:00:00', '18:00:00', '2026-02-14 15:40:51', 819.81, 'Active'),
(25, 'Kim', 'Bap', 'HR', '09:00:00', '17:00:00', '2026-02-24 14:06:16', 1000.00, 'Inactive');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employee` (`employee_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

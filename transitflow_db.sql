-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 13, 2026 at 02:11 AM
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
-- Database: `transitflow_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE `alerts` (
  `alert_id` int(11) NOT NULL,
  `route_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alerts`
--

INSERT INTO `alerts` (`alert_id`, `route_id`, `message`, `created_at`) VALUES
(1, 1, 'Meow Meow Meow Meow', '2025-12-07 04:16:50'),
(2, 1, 'maraming tambay\r\n', '2025-12-07 16:06:22');

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `route_id` int(11) NOT NULL,
  `route_name` varchar(100) NOT NULL,
  `origin` varchar(255) DEFAULT NULL,
  `destination` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`route_id`, `route_name`, `origin`, `destination`, `created_at`) VALUES
(1, 'Route 69', 'Tanauan', 'Tacloban', '2025-12-07 04:02:33'),
(2, 'tacloban', '', '', '2025-12-07 16:05:12'),
(3, '', 'leyte', '', '2025-12-07 16:05:17'),
(8, 'dada', 'dada', 'dada', '2026-01-05 14:08:18'),
(9, 'basey ', 'tacloban', 'bahay', '2026-01-05 14:43:51');

-- --------------------------------------------------------

--
-- Table structure for table `route_stop_order`
--

CREATE TABLE `route_stop_order` (
  `route_stop_id` int(11) NOT NULL,
  `route_id` int(11) NOT NULL,
  `stop_id` int(11) NOT NULL,
  `stop_order` int(11) NOT NULL,
  `estimated_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `route_stop_order`
--

INSERT INTO `route_stop_order` (`route_stop_id`, `route_id`, `stop_id`, `stop_order`, `estimated_time`) VALUES
(1, 1, 2, 1, 0),
(2, 2, 1, 2, 1),
(4, 1, 4, 2, NULL),
(9, 2, 3, 1, 0),
(10, 9, 4, 2, 8),
(11, 1, 1, 3, 2);

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `schedule_id` int(11) NOT NULL,
  `route_id` int(11) NOT NULL,
  `departure_time` time NOT NULL,
  `arrival_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`schedule_id`, `route_id`, `departure_time`, `arrival_time`, `created_at`) VALUES
(1, 1, '13:15:00', '14:15:00', '2025-12-07 04:12:32'),
(2, 1, '01:05:00', '00:08:00', '2025-12-07 16:06:07');

-- --------------------------------------------------------

--
-- Table structure for table `stops`
--

CREATE TABLE `stops` (
  `stop_id` int(11) NOT NULL,
  `route_id` int(11) DEFAULT NULL,
  `stop_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `latitude` float(10,6) NOT NULL,
  `longitude` float(10,6) NOT NULL,
  `stop_order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stops`
--

INSERT INTO `stops` (`stop_id`, `route_id`, `stop_name`, `created_at`, `latitude`, `longitude`, `stop_order`) VALUES
(1, 1, 'campetic\r\n', '2025-12-07 04:08:48', 0.000000, 0.000000, 0),
(2, 1, 'tanuan\r\n', '2025-12-07 16:05:51', 0.000000, 0.000000, 0),
(3, NULL, 'basey\r\n', '2025-12-16 13:09:47', 111.000000, 3322.000000, 0),
(4, NULL, 'tacloban\r\n', '2025-12-18 15:40:34', 0.000000, 0.000000, 0),
(5, NULL, 'ada', '2026-01-05 14:08:14', 0.000000, 0.000000, 0),
(6, NULL, 'tacloban', '2026-01-05 14:45:35', 1.000000, 1.000000, 0);

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

CREATE TABLE `trips` (
  `trip_id` int(11) NOT NULL,
  `route_id` int(11) NOT NULL,
  `departure_time` time NOT NULL,
  `day_of_week` varchar(50) NOT NULL,
  `vehicle_info` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trips`
--

INSERT INTO `trips` (`trip_id`, `route_id`, `departure_time`, `day_of_week`, `vehicle_info`) VALUES
(1, 2, '07:19:00', 'Monday', NULL),
(2, 1, '19:36:00', 'Weekdays', NULL),
(3, 1, '12:17:00', 'Weekdays', NULL),
(4, 1, '14:40:00', 'Daily', NULL),
(5, 2, '09:00:00', 'Thursday', NULL),
(6, 1, '11:09:00', 'Daily', NULL),
(7, 1, '00:00:00', 'Daily', NULL),
(8, 2, '22:46:00', 'Weekdays', NULL),
(9, 2, '11:00:00', 'Weekdays', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`alert_id`),
  ADD KEY `fk_alerts_route_id` (`route_id`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`route_id`),
  ADD UNIQUE KEY `route_name` (`route_name`);

--
-- Indexes for table `route_stop_order`
--
ALTER TABLE `route_stop_order`
  ADD PRIMARY KEY (`route_stop_id`),
  ADD UNIQUE KEY `route_id` (`route_id`,`stop_order`),
  ADD UNIQUE KEY `route_id_2` (`route_id`,`stop_id`),
  ADD KEY `stop_id` (`stop_id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `fk_schedules_route_id` (`route_id`);

--
-- Indexes for table `stops`
--
ALTER TABLE `stops`
  ADD PRIMARY KEY (`stop_id`),
  ADD KEY `fk_stops_route_id` (`route_id`);

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`trip_id`),
  ADD KEY `route_id` (`route_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alerts`
--
ALTER TABLE `alerts`
  MODIFY `alert_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `route_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `route_stop_order`
--
ALTER TABLE `route_stop_order`
  MODIFY `route_stop_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stops`
--
ALTER TABLE `stops`
  MODIFY `stop_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `trip_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alerts`
--
ALTER TABLE `alerts`
  ADD CONSTRAINT `fk_alerts_route_id` FOREIGN KEY (`route_id`) REFERENCES `routes` (`route_id`);

--
-- Constraints for table `route_stop_order`
--
ALTER TABLE `route_stop_order`
  ADD CONSTRAINT `route_stop_order_ibfk_1` FOREIGN KEY (`route_id`) REFERENCES `routes` (`route_id`),
  ADD CONSTRAINT `route_stop_order_ibfk_2` FOREIGN KEY (`stop_id`) REFERENCES `stops` (`stop_id`);

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `fk_schedules_route_id` FOREIGN KEY (`route_id`) REFERENCES `routes` (`route_id`);

--
-- Constraints for table `stops`
--
ALTER TABLE `stops`
  ADD CONSTRAINT `fk_stops_route_id` FOREIGN KEY (`route_id`) REFERENCES `routes` (`route_id`);

--
-- Constraints for table `trips`
--
ALTER TABLE `trips`
  ADD CONSTRAINT `trips_ibfk_1` FOREIGN KEY (`route_id`) REFERENCES `routes` (`route_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

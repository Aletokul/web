-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Oct 17, 2025 at 10:08 AM
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
-- Database: `teren_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `fields`
--

CREATE TABLE `fields` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `sport_type` varchar(20) NOT NULL,
  `type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fields`
--

INSERT INTO `fields` (`id`, `name`, `sport_type`, `type`) VALUES
(1, 'Tenis - Šljaka', 'tenis', 'sljaka'),
(2, 'Tenis - Beton', 'tenis', 'beton'),
(3, 'Fudbal', 'fudbal', ''),
(4, 'Tenis - Trava', 'tenis', 'trava'),
(5, 'Košarka - Basket', 'kosarka', 'basket'),
(6, 'Košarka', 'kosarka', '');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `field_id`, `user_id`, `date`, `start_time`, `end_time`) VALUES
(39, 4, 5, '2025-10-14', '10:00:00', '11:00:00'),
(42, 1, 6, '2025-10-16', '18:00:00', '19:00:00'),
(43, 6, 6, '2025-10-14', '14:00:00', '15:00:00'),
(44, 5, 5, '2025-10-17', '19:00:00', '20:00:00'),
(45, 5, 5, '2025-10-17', '20:00:00', '21:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `remember_token` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `remember_token`) VALUES
(1, 'proba', '$2y$10$ymRn05Dog85dohaWC1j3ReUW5a8yN2Oy836zpjlP01.O3wTEtQ8u6', 'user', NULL),
(2, 'admin', '$2y$10$/96WdGVUNsebNsx8XvcLoOnyQaBtt64RTDOAgjFyKf3D4RgTPGosa', 'admin', 'eec1af1ec6e474879a79fa6edcfbadd3'),
(5, 'aca', '$2y$10$Oc9AA6LkImGh7cUesVdUt.O3UKcomHH0hTjkoitc7UryqNri3sHju', 'user', '23e92c0a2e77dd38ee99220145e58bd6'),
(6, 'jelena', '$2y$10$6O/p6YnilSCufSxloY6TNuZrn2gv3CWv3r3zu52FIR5dgn8qmepuW', 'user', 'cd77c087e72ad323b209444b068030c1'),
(7, 'nesto', '$2y$10$lJrvfxoQHrzrGLgwu4ZB5.IW.VSW0mCxzlY4jQj8qpcVqN9sPozTS', 'user', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `fields`
--
ALTER TABLE `fields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fields` (`field_id`),
  ADD KEY `users` (`user_id`);

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
-- AUTO_INCREMENT for table `fields`
--
ALTER TABLE `fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `fk_reservation_field` FOREIGN KEY (`field_id`) REFERENCES `fields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reservation_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

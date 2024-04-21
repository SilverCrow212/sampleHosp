-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2024 at 05:20 PM
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
-- Database: `bghmc_exam`
--

-- --------------------------------------------------------

--
-- Table structure for table `admission_details`
--

CREATE TABLE `admission_details` (
  `id` int(200) NOT NULL,
  `pat_id` int(200) NOT NULL,
  `enccode` varchar(200) DEFAULT NULL,
  `ward` varchar(200) DEFAULT NULL,
  `admission` varchar(200) DEFAULT NULL,
  `discharge` varchar(200) DEFAULT NULL,
  `created_at` varchar(200) NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admission_details`
--

INSERT INTO `admission_details` (`id`, `pat_id`, `enccode`, `ward`, `admission`, `discharge`, `created_at`) VALUES
(6, 8, 'fa481e27e75a6b6d308e5858d458a85a', 'B', '2024-04-21T14:46:40.000Z', '2024-04-21T14:51:42.000Z', '2024-04-21 22:46:44'),
(24, 9, 'a80e75807c3c84fb62d48be23939c4ee', 'B', '2024-04-21T15:18:34.000Z', NULL, '2024-04-21 23:18:41');

-- --------------------------------------------------------

--
-- Table structure for table `patient_details`
--

CREATE TABLE `patient_details` (
  `pat_id` int(11) NOT NULL,
  `pat_lastname` varchar(200) DEFAULT NULL,
  `pat_firstname` varchar(200) DEFAULT NULL,
  `pat_middlename` varchar(200) DEFAULT NULL,
  `pat_suffixname` varchar(200) DEFAULT NULL,
  `pat_birth` varchar(200) DEFAULT NULL,
  `pat_address` varchar(200) DEFAULT NULL,
  `created_at` varchar(200) NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_details`
--

INSERT INTO `patient_details` (`pat_id`, `pat_lastname`, `pat_firstname`, `pat_middlename`, `pat_suffixname`, `pat_birth`, `pat_address`, `created_at`) VALUES
(8, 'tester', 'does', 'jon', 'sr', '2024-04-20T16:00:00.000Z', 'address niya ulet', '2024-04-21 22:19:27'),
(9, 'yes', 'test', 'last', 'asd', '2024-04-21T16:00:00.000Z', 'asdasd', '2024-04-21 23:18:22');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admission_details`
--
ALTER TABLE `admission_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `enccode` (`enccode`),
  ADD KEY `test` (`pat_id`);

--
-- Indexes for table `patient_details`
--
ALTER TABLE `patient_details`
  ADD PRIMARY KEY (`pat_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admission_details`
--
ALTER TABLE `admission_details`
  MODIFY `id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `patient_details`
--
ALTER TABLE `patient_details`
  MODIFY `pat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admission_details`
--
ALTER TABLE `admission_details`
  ADD CONSTRAINT `test` FOREIGN KEY (`pat_id`) REFERENCES `patient_details` (`pat_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3308
-- Generation Time: Oct 14, 2023 at 03:08 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sms_sender_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `zone` varchar(200) NOT NULL,
  `isAdmin` bit(5) NOT NULL DEFAULT b'0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `zone`, `isAdmin`) VALUES
(1, 'Charles Bih', 'charles@gmail.com', '$2y$10$VNuTBD18rQgtmZo9ZaeeO.xZLSGchMrTL/ry8TZ3UqVgJRH4ONhhe', 'Tamale', b'00001'),
(2, 'Karl Klark', 'bih@gmail.com', '$2y$10$VNuTBD18rQgtmZo9ZaeeO.xZLSGchMrTL/ry8TZ3UqVgJRH4ONhhe', 'sefwi', b'00000'),
(3, 'Isaac Kwame', 'ike@gmail.com', '$2y$10$VNuTBD18rQgtmZo9ZaeeO.xZLSGchMrTL/ry8TZ3UqVgJRH4ONhhe', 'yamfo', b'00000'),
(4, 'Isaac Kwame', 'ike1@gmail.com', '$2y$10$VNuTBD18rQgtmZo9ZaeeO.xZLSGchMrTL/ry8TZ3UqVgJRH4ONhhe', 'yamfo', b'00000'),
(8, 'Christopher Asante', 'chris@gmail.com', '$2y$10$MG3C/2bgl75W3aaQjaQZru.DJpZsQHeOLEtN5BmUct4W0v6EjxY0q', 'Bekwai', b'11111');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

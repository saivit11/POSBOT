-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 04, 2021 at 02:25 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `posbot`
--

-- --------------------------------------------------------

--
-- Table structure for table `practitioner`
--

CREATE TABLE `practitioner` (
  `PRAC_NO` int(10) NOT NULL,
  `PRAC_NAME` varchar(50) NOT NULL,
  `PRAC_SPECIALIZATION` varchar(50) NOT NULL,
  `PRAC_COUNTRY` varchar(50) NOT NULL,
  `PRAC_STATE` varchar(50) NOT NULL,
  `PRAC_CITY` varchar(50) NOT NULL,
  `PRAC_STREET` varchar(50) NOT NULL,
  `PRAC_NUMBER` varchar(11) NOT NULL,
  `PRAC_EMAIL` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `practitioner`
--

INSERT INTO `practitioner` (`PRAC_NO`, `PRAC_NAME`, `PRAC_SPECIALIZATION`, `PRAC_COUNTRY`, `PRAC_STATE`, `PRAC_CITY`, `PRAC_STREET`, `PRAC_NUMBER`, `PRAC_EMAIL`) VALUES
(1, 'Dr. Rakesh Sharma', 'Pediatrician', 'India', 'Haryana', 'Gurgaon', 'Sector 90', '9971266212', 'rsharma@ymail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `practitioner`
--
ALTER TABLE `practitioner`
  ADD PRIMARY KEY (`PRAC_NO`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `practitioner`
--
ALTER TABLE `practitioner`
  MODIFY `PRAC_NO` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

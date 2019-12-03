-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2019 at 10:55 AM
-- Server version: 10.1.29-MariaDB
-- PHP Version: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fep`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `Username` varchar(30) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Eamil` varchar(222) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `Username`, `Password`, `Eamil`) VALUES
(1, 'Flexible', '$2y$10$JeXOVL8K9fTyMgi21Xn34uivJWAkeOo/asxhwrAgDJ8iKLy4AyUdW', 'flexible.exams.planner@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `examdata`
--

CREATE TABLE `examdata` (
  `id` int(11) NOT NULL,
  `Class_ID` varchar(50) NOT NULL,
  `Subject_ID` varchar(50) NOT NULL,
  `Student_ID` varchar(50) NOT NULL,
  `Subject_name` varchar(50) NOT NULL,
  `lecturer_name` varchar(70) NOT NULL,
  `exam_days` varchar(50) NOT NULL,
  `exam_dates` varchar(50) NOT NULL,
  `exam_times` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset`
--

CREATE TABLE `password_reset` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `process_state`
--

CREATE TABLE `process_state` (
  `PSSD` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `process_state`
--

INSERT INTO `process_state` (`PSSD`) VALUES
(b'0');

-- --------------------------------------------------------

--
-- Table structure for table `students_away`
--

CREATE TABLE `students_away` (
  `id` int(11) NOT NULL,
  `Student_ID` int(11) NOT NULL,
  `town` varchar(30) NOT NULL,
  `distance` int(11) NOT NULL,
  `department` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `times`
--

CREATE TABLE `times` (
  `exam_times` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `examdata`
--
ALTER TABLE `examdata`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students_away`
--
ALTER TABLE `students_away`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `examdata`
--
ALTER TABLE `examdata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25951;

--
-- AUTO_INCREMENT for table `students_away`
--
ALTER TABLE `students_away`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

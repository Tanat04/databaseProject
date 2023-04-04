-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 10, 2019 at 08:08 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `hospital`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `adminId` varchar(5) NOT NULL,
  `adminName` varchar(255) NOT NULL,
  `adminPhno` varchar(255) NOT NULL,
  PRIMARY KEY (`adminId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`adminId`, `adminName`, `adminPhno`) VALUES
('a01', 'John Doe', '555-555-5555'),
('a02', 'Jane Smith', '123-456-7890');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `doctorId` varchar(5) NOT NULL,
  `doctorName` varchar(255) NOT NULL,
  `doctorPhNo` varchar(255) NOT NULL,
  PRIMARY KEY (`doctorId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`doctorId`, `doctorName`, `doctorPhNo`) VALUES
('d01', 'Tanat Arora', '493-292-0229'),
('d02', 'Lynn Thit', '123-998-3847');

-- --------------------------------------------------------

--
-- Table structure for table `patientd`
--

CREATE TABLE `patient` (
  `patientId` varchar(5) NOT NULL,
  `patientName` varchar(255) NOT NULL,
  `patientPhNo` varchar(20) NOT NULL,
  `patientGender` enum('Male','Female') NOT NULL,
  `patientDob` date NOT NULL,
  PRIMARY KEY (`patientId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patients`
--

INSERT INTO `patient` (`patientId`, `patientName`, `patientPhNo`, `patientGender`, `patientDob`) VALUES 
('p01', 'Aung Cham', '555-1234-9999', 'Male', '1995-03-05'),
('p02', 'Sam Song', '555-1234-9998', 'Male', '1995-08-05');

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `appointmentId` int(11) NOT NULL,
  `patientId` varchar(5) NOT NULL,
  `doctorId` varchar(5) NOT NULL,
  `appointmentStatus` enum('discharged','admit') NOT NULL,
  `doctorNote` varchar(255) DEFAULT NULL,
  `appointmentDate` date NOT NULL,
  `appointmentTime` time NOT NULL,
  PRIMARY KEY (`appointmentId`),
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`appointmentId`, `patientId`, `doctorId`, `appointmentStatus`, `doctorNote`, `appointmentDate`, `appointmentTime`) VALUES
(1, 'p01', 'd01', 'admit', 'Patient needs to be kept overnight for observation', '2023-03-05', '13:30:00'),
(2, 'p02', 'd02', 'discharged', 'Patient is free to go home, follow-up in 2 weeks', '2023-02-07', '15:30:00'),
(3, 'p01', 'd02', 'discharged', 'Patient requires surgery, schedule for tomorrow', '2023-02-08', '16:00:00');

-- --------------------------------------------------------

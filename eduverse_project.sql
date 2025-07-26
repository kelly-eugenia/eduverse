-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Generation Time: May 24, 2025 at 10:27 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `s104475686_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `addedCourses`
--

CREATE TABLE `addedCourses` (
  `instructorID` int(11) NOT NULL,
  `courseID` varchar(6) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `addedCourses`
--

INSERT INTO `addedCourses` (`instructorID`, `courseID`, `date`) VALUES
(1, 'DS101', '2025-01-02'),
(1, 'IT1106', '2024-05-31'),
(1, 'IT1107', '2025-07-05');

-- --------------------------------------------------------

--
-- Table structure for table `enrolledCourses`
--

CREATE TABLE `enrolledCourses` (
  `learnerID` int(11) NOT NULL,
  `courseID` varchar(6) NOT NULL,
  `enrolledDate` date NOT NULL,
  `completedDate` date DEFAULT NULL,
  `progress` int(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrolledCourses`
--

INSERT INTO `enrolledCourses` (`learnerID`, `courseID`, `enrolledDate`, `completedDate`, `progress`) VALUES
(1, 'BS102', '2025-05-01', NULL, 30),
(1, 'DS101', '2024-08-10', '2024-10-10', 100),
(1, 'IT307', '2025-01-31', '2025-04-15', 100),
(2, 'MK204', '2025-01-17', NULL, 50);

-- --------------------------------------------------------

--
-- Table structure for table `instructors`
--

CREATE TABLE `instructors` (
  `instructorID` int(11) NOT NULL,
  `firstName` varchar(20) NOT NULL,
  `lastName` varchar(20) NOT NULL,
  `dob` date NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `instructors`
--

INSERT INTO `instructors` (`instructorID`, `firstName`, `lastName`, `dob`, `username`, `password`, `email`) VALUES
(1, 'Emily', 'Carter', '1996-02-08', 'emilycarter', 'pass123$', 'emilycarter@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `learners`
--


CREATE TABLE `learners` (
  `learnerID` int(11) NOT NULL,
  `firstName` varchar(20) NOT NULL,
  `lastName` varchar(20) NOT NULL,
  `dob` date NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(30) NOT NULL,
  `email` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learners`
--

INSERT INTO `learners` (`learnerID`, `firstName`, `lastName`, `dob`, `username`, `password`, `email`) VALUES
(1, 'Jane', 'Smith', '2000-03-09', 'janesmith', 'pass123$', 'jane.smith@gmail.com'),
(2, 'John', 'Lee', '2002-06-12', 'johnlee06', 'pass123$', 'johnlee@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `likedCourses`
--

CREATE TABLE `likedCourses` (
  `learnerID` int(11) NOT NULL,
  `courseID` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `likedCourses`
--

INSERT INTO `likedCourses` (`learnerID`, `courseID`) VALUES
(1, 'BS102'),
(1, 'DS101');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addedCourses`
--
ALTER TABLE `addedCourses`
  ADD PRIMARY KEY (`courseID`),
  ADD KEY `instructorID` (`instructorID`);

--
-- Indexes for table `enrolledCourses`
--
ALTER TABLE `enrolledCourses`
  ADD PRIMARY KEY (`learnerID`,`courseID`);

--
-- Indexes for table `instructors`
--
ALTER TABLE `instructors`
  ADD PRIMARY KEY (`instructorID`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `instructorID` (`instructorID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `learners`
--
ALTER TABLE `learners`
  ADD PRIMARY KEY (`learnerID`),
  ADD UNIQUE KEY `learnerID` (`learnerID`,`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `likedCourses`
--
ALTER TABLE `likedCourses`
  ADD PRIMARY KEY (`learnerID`,`courseID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `instructorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `learners`
--
ALTER TABLE `learners`
  MODIFY `learnerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addedCourses`
--
ALTER TABLE `addedCourses`
  ADD CONSTRAINT `addedcourses_ibfk_1` FOREIGN KEY (`instructorID`) REFERENCES `instructors` (`instructorID`),
  ADD CONSTRAINT `instructorID` FOREIGN KEY (`instructorID`) REFERENCES `instructors` (`instructorID`);

--
-- Constraints for table `enrolledCourses`
--
ALTER TABLE `enrolledCourses`
  ADD CONSTRAINT `enrolledcourses_ibfk_1` FOREIGN KEY (`learnerID`) REFERENCES `learners` (`learnerID`),
  ADD CONSTRAINT `learnerID` FOREIGN KEY (`learnerID`) REFERENCES `learners` (`learnerID`);

--
-- Constraints for table `likedCourses`
--
ALTER TABLE `likedCourses`
  ADD CONSTRAINT `liked_learnerID` FOREIGN KEY (`learnerID`) REFERENCES `learners` (`learnerID`),
  ADD CONSTRAINT `likedcourses_ibfk_1` FOREIGN KEY (`learnerID`) REFERENCES `learners` (`learnerID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

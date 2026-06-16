-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2026 at 05:03 PM
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
-- Database: `srms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `UserName` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `updationDate` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `EmailId` varchar(120) NOT NULL,
  `otp_code` varchar(10) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `UserName`, `Password`, `updationDate`, `EmailId`, `otp_code`, `otp_expiry`) VALUES
(1, 'ceyrow', '2e9b1e7ad57bd87f74c13285bb1636c3', '2026-06-12 14:01:09', 'cabaascaliceerow@gmail.com', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `StudentName` varchar(100) DEFAULT NULL,
  `RollId` varchar(50) DEFAULT NULL,
  `Message` text DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`id`, `StudentName`, `RollId`, `Message`, `CreatedAt`) VALUES
(29, 'Aisha Abdi Ahmed', '222', 'Asalamu calkm ustaat Magaceygu waa Aisha Abdi Ahmed\r\nUstat maadada jsp 60 aa keenay lakiin 55 aa ii qoran marka ustaat\r\ndib ugu noqo ustaat adoo mahadsan\r\n', '2026-04-27 14:43:17'),
(30, 'caamir', '444', 'fghtrhttyyyuykyu', '2026-05-24 18:29:48'),
(31, 'Ahmed Ali Mohamud', '4532', 'where is my final marks', '2026-05-24 18:38:17'),
(32, 'Ahmed Ali Mohamud', '4532', 'where is my final marks', '2026-05-24 18:38:32');

-- --------------------------------------------------------

--
-- Table structure for table `tblclasses`
--

CREATE TABLE `tblclasses` (
  `id` int(11) NOT NULL,
  `ClassName` varchar(80) DEFAULT NULL,
  `CreationDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblclasses`
--

INSERT INTO `tblclasses` (`id`, `ClassName`, `CreationDate`, `UpdationDate`) VALUES
(1, 'semester 1', '2026-02-27 14:14:22', '2026-02-27 14:14:22'),
(2, 'semester 2', '2026-02-27 14:14:26', '2026-02-27 14:14:26'),
(3, 'semester 3', '2026-02-27 14:14:31', '2026-02-27 14:14:31'),
(4, 'semester 4', '2026-02-27 14:14:35', '2026-02-27 14:14:35'),
(5, 'semester 5', '2026-02-27 14:14:40', '2026-02-27 14:14:40'),
(6, 'semester 6', '2026-02-27 14:15:00', '2026-02-27 14:15:00'),
(7, 'semester 7', '2026-02-27 14:15:10', '2026-02-27 14:15:10'),
(8, 'semester 8', '2026-02-27 14:15:18', '2026-02-27 14:15:18');

-- --------------------------------------------------------

--
-- Table structure for table `tbldepartment`
--

CREATE TABLE `tbldepartment` (
  `id` int(11) NOT NULL,
  `DepartmentName` varchar(255) NOT NULL,
  `FacultyId` int(11) DEFAULT NULL,
  `CreationDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbldepartment`
--

INSERT INTO `tbldepartment` (`id`, `DepartmentName`, `FacultyId`, `CreationDate`) VALUES
(1, 'Computer Science', 1, '2026-05-04 11:16:38'),
(2, 'Information Technology', 1, '2026-05-04 11:16:38'),
(3, 'Software Engineering', 1, '2026-05-04 11:16:38'),
(4, 'Civil Engineering', 2, '2026-05-04 11:16:38'),
(5, 'Electrical Engineering', 2, '2026-05-04 11:16:38'),
(6, 'Accounting', 3, '2026-05-04 11:16:38'),
(7, 'Management', 3, '2026-05-04 11:16:38');

-- --------------------------------------------------------

--
-- Table structure for table `tblfaculty`
--

CREATE TABLE `tblfaculty` (
  `id` int(11) NOT NULL,
  `FacultyName` varchar(255) NOT NULL,
  `CreationDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblfaculty`
--

INSERT INTO `tblfaculty` (`id`, `FacultyName`, `CreationDate`) VALUES
(1, 'Faculty of  Engineering and Computer Science', '2026-05-04 11:16:11'),
(2, 'Faculty of Engineering', '2026-05-04 11:16:11'),
(3, 'Faculty of Business', '2026-05-04 11:16:11');

-- --------------------------------------------------------

--
-- Table structure for table `tblresult`
--

CREATE TABLE `tblresult` (
  `id` int(11) NOT NULL,
  `StudentId` int(11) DEFAULT NULL,
  `ClassId` int(11) DEFAULT NULL,
  `AcademicYear` varchar(20) NOT NULL,
  `Semester` varchar(50) DEFAULT NULL,
  `ExamType` varchar(20) NOT NULL,
  `SubjectId` int(11) DEFAULT NULL,
  `marks` int(11) DEFAULT NULL,
  `PostingDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `StudentName` varchar(50) DEFAULT NULL,
  `RollId` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblresult`
--

INSERT INTO `tblresult` (`id`, `StudentId`, `ClassId`, `AcademicYear`, `Semester`, `ExamType`, `SubjectId`, `marks`, `PostingDate`, `UpdationDate`, `StudentName`, `RollId`) VALUES
(169, 19, 1, '2025-2026', NULL, 'Midterm', 4, 40, '2026-05-24 17:50:01', NULL, NULL, NULL),
(170, 19, 1, '2025-2026', NULL, 'Midterm', 2, 40, '2026-05-24 17:50:01', NULL, NULL, NULL),
(171, 19, 1, '2025-2026', NULL, 'Midterm', 1, 40, '2026-05-24 17:50:01', NULL, NULL, NULL),
(172, 19, 1, '2025-2026', NULL, 'Final', 4, 60, '2026-05-24 17:50:27', NULL, NULL, NULL),
(173, 19, 1, '2025-2026', NULL, 'Final', 2, 60, '2026-05-24 17:50:27', NULL, NULL, NULL),
(174, 19, 1, '2025-2026', NULL, 'Final', 1, 60, '2026-05-24 17:50:27', NULL, NULL, NULL),
(175, 19, 2, '2025-2026', NULL, 'Midterm', 6, 40, '2026-05-24 17:52:41', NULL, NULL, NULL),
(176, 19, 2, '2025-2026', NULL, 'Midterm', 8, 40, '2026-05-24 17:52:41', NULL, NULL, NULL),
(177, 19, 2, '2025-2026', NULL, 'Midterm', 7, 40, '2026-05-24 17:52:41', NULL, NULL, NULL),
(178, 19, 3, '2026-2027', NULL, 'Midterm', 6, 40, '2026-05-24 17:55:40', NULL, NULL, NULL),
(179, 19, 3, '2026-2027', NULL, 'Midterm', 8, 40, '2026-05-24 17:55:40', NULL, NULL, NULL),
(180, 19, 3, '2026-2027', NULL, 'Midterm', 1, 40, '2026-05-24 17:55:40', NULL, NULL, NULL),
(181, 19, 3, '2026-2027', NULL, 'Final', 6, 60, '2026-05-24 17:56:56', NULL, NULL, NULL),
(182, 19, 3, '2026-2027', NULL, 'Final', 8, 60, '2026-05-24 17:56:56', NULL, NULL, NULL),
(183, 19, 3, '2026-2027', NULL, 'Final', 1, 60, '2026-05-24 17:56:56', NULL, NULL, NULL),
(184, 23, 1, '2025-2026', NULL, 'Midterm', 4, 30, '2026-05-24 18:36:33', NULL, NULL, NULL),
(185, 23, 1, '2025-2026', NULL, 'Midterm', 2, 25, '2026-05-24 18:36:33', NULL, NULL, NULL),
(186, 23, 1, '2025-2026', NULL, 'Midterm', 1, 21, '2026-05-24 18:36:33', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblstudents`
--

CREATE TABLE `tblstudents` (
  `StudentId` int(11) NOT NULL,
  `StudentName` varchar(100) NOT NULL,
  `RollId` varchar(100) NOT NULL,
  `StudentEmail` varchar(100) NOT NULL,
  `Gender` varchar(10) NOT NULL,
  `DOB` varchar(100) NOT NULL,
  `ClassId` int(11) NOT NULL,
  `RegDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `Status` int(1) NOT NULL,
  `Faculty` int(11) DEFAULT NULL,
  `Department` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblstudents`
--

INSERT INTO `tblstudents` (`StudentId`, `StudentName`, `RollId`, `StudentEmail`, `Gender`, `DOB`, `ClassId`, `RegDate`, `UpdationDate`, `Status`, `Faculty`, `Department`) VALUES
(12, 'Abas Ali', '111', 'cabaascaliceerow@gmail.com', 'Male', '2026-03-09', 7, '2026-03-09 16:22:24', NULL, 1, NULL, NULL),
(13, 'Aisha Abdi Ahmed', '222', 'cabaascaliceerow@gmail.com', 'Female', '2026-03-09', 8, '2026-03-09 16:25:21', '2026-03-24 11:29:59', 1, NULL, NULL),
(14, 'yaxye  xasan', '333', 'cabaascaliceyrow@gmail.com', 'Male', '', 1, '2026-04-24 04:28:15', NULL, 1, NULL, NULL),
(15, 'xaliimo cali', '555', 'cabaascaliceyrow@gmail.com', 'Female', '', 2, '2026-04-24 04:30:55', NULL, 1, NULL, NULL),
(16, 'farxiyo cali', '777', 'cabaascaliceerow@gmail.com', 'Female', '', 3, '2026-04-24 04:35:55', NULL, 1, NULL, NULL),
(17, 'saadaq xasan', '888', 'cabaascaliceerow@gmail.com', 'Male', '', 4, '2026-04-24 04:36:32', NULL, 1, NULL, NULL),
(18, 'salaad', '9999', 'cabaascaliceerow@gmail.com', 'Male', '', 1, '2026-05-04 11:18:56', '2026-05-04 12:24:00', 1, 1, 2),
(19, 'caamir', '444', 'cabaascaliceerow@gmail.com', 'Male', '2026-05-04', 3, '2026-05-04 12:19:29', '2026-05-24 17:54:02', 1, 1, 2),
(20, 'faadumo', '8080', 'cabaascaliceerow@gmail.com', 'Female', '2026-05-04', 1, '2026-05-04 12:34:24', NULL, 1, 3, 6),
(22, 'cumar', '9090', 'cabaascaliceerow@gmail.com', 'Male', '2026-05-04', 1, '2026-05-04 19:11:32', NULL, 1, 3, 6),
(23, 'Ahmed Ali Mohamud', '4532', 'ggggg@gmail.com', 'Male', '2026-05-24', 1, '2026-05-24 18:34:10', NULL, 1, 3, 6);

-- --------------------------------------------------------

--
-- Table structure for table `tblsubjectcombination`
--

CREATE TABLE `tblsubjectcombination` (
  `id` int(11) NOT NULL,
  `ClassId` int(11) NOT NULL,
  `SubjectId` int(11) NOT NULL,
  `FacultyId` int(11) DEFAULT NULL,
  `DepartmentId` int(11) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `CreationDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `Updationdate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblsubjectcombination`
--

INSERT INTO `tblsubjectcombination` (`id`, `ClassId`, `SubjectId`, `FacultyId`, `DepartmentId`, `status`, `CreationDate`, `Updationdate`) VALUES
(61, 1, 10, 1, 1, 1, '2026-05-25 06:50:14', '2026-05-25 06:50:14'),
(62, 1, 9, 3, 6, 1, '2026-05-25 07:09:35', '2026-05-25 07:09:35'),
(63, 2, 13, 3, 6, 1, '2026-05-26 04:14:48', '2026-05-26 04:14:48');

-- --------------------------------------------------------

--
-- Table structure for table `tblsubjects`
--

CREATE TABLE `tblsubjects` (
  `id` int(11) NOT NULL,
  `SubjectName` varchar(100) NOT NULL,
  `SubjectCode` varchar(100) NOT NULL,
  `Creationdate` timestamp NOT NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  `FacultyName` varchar(255) NOT NULL,
  `DepartmentName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblsubjects`
--

INSERT INTO `tblsubjects` (`id`, `SubjectName`, `SubjectCode`, `Creationdate`, `UpdationDate`, `FacultyName`, `DepartmentName`) VALUES
(1, 'php', 'php01', '2017-06-07 09:23:57', '2026-03-09 14:55:42', '', ''),
(2, 'java', 'java11', '2017-06-07 09:24:25', '2026-03-09 14:56:09', '', ''),
(4, 'html', 'html1', '2017-06-07 09:36:15', '2026-03-09 14:56:57', '', ''),
(5, 'css', 'css', '2017-06-07 09:36:23', '2026-03-09 14:57:20', '', ''),
(6, 'java script', 'js', '2017-08-28 18:43:29', '2026-03-09 14:58:01', '', ''),
(7, 'Python', 'Py03', '2017-08-28 18:52:41', '2026-03-09 14:58:41', '', ''),
(8, 'node js', 'node11', '2017-08-28 19:21:46', '2026-03-09 14:59:53', '', ''),
(9, 'JSP', 'J', '2026-04-27 14:15:45', '0000-00-00 00:00:00', '', ''),
(10, 'Cloud Computing', 'cloud', '2026-04-27 14:16:39', '0000-00-00 00:00:00', '', ''),
(11, 'IT Project', 'it', '2026-04-27 14:17:04', '0000-00-00 00:00:00', '', ''),
(12, 'introduction to buisness', 'buisn', '2026-05-25 15:45:09', NULL, 'Faculty of Business', 'Accounting'),
(13, 'english1', 'eng', '2026-05-25 16:59:05', NULL, 'Faculty of Business', 'Accounting');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblclasses`
--
ALTER TABLE `tblclasses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbldepartment`
--
ALTER TABLE `tbldepartment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblfaculty`
--
ALTER TABLE `tblfaculty`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblresult`
--
ALTER TABLE `tblresult`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblstudents`
--
ALTER TABLE `tblstudents`
  ADD PRIMARY KEY (`StudentId`),
  ADD UNIQUE KEY `RollId` (`RollId`);

--
-- Indexes for table `tblsubjectcombination`
--
ALTER TABLE `tblsubjectcombination`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_class_faculty_dept` (`ClassId`,`FacultyId`,`DepartmentId`);

--
-- Indexes for table `tblsubjects`
--
ALTER TABLE `tblsubjects`
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
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tblclasses`
--
ALTER TABLE `tblclasses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbldepartment`
--
ALTER TABLE `tbldepartment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tblfaculty`
--
ALTER TABLE `tblfaculty`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tblresult`
--
ALTER TABLE `tblresult`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=187;

--
-- AUTO_INCREMENT for table `tblstudents`
--
ALTER TABLE `tblstudents`
  MODIFY `StudentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tblsubjectcombination`
--
ALTER TABLE `tblsubjectcombination`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `tblsubjects`
--
ALTER TABLE `tblsubjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

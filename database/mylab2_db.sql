-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2024 at 05:11 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `mylab2_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `genders`
--

CREATE TABLE `genders` (
  `gender_id` int(11) NOT NULL,
  `gender` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genders`
--

INSERT INTO `genders` (`gender_id`, `gender`) VALUES
(1, 'male'),
(2, 'female'),
(3, 'other');

-- --------------------------------------------------------

--
-- Table structure for table `logins`
--

CREATE TABLE `logins` (
  `username` varchar(16) NOT NULL,
  `password` varchar(16) NOT NULL,
  `person_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logins`
--

INSERT INTO `logins` (`username`, `password`, `person_id`) VALUES
('admin', 'admin', 1);

-- --------------------------------------------------------

--
-- Table structure for table `maritial_statuses`
--

CREATE TABLE `maritial_statuses` (
  `ms_id` int(11) NOT NULL,
  `status` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maritial_statuses`
--

INSERT INTO `maritial_statuses` (`ms_id`, `status`) VALUES
(1, 'single'),
(2, 'married'),
(3, 'divorced'),
(4, 'widowed');

-- --------------------------------------------------------

--
-- Table structure for table `persons`
--

CREATE TABLE `persons` (
  `person_id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `dob` date DEFAULT NULL,
  `age` decimal(10,0) NOT NULL,
  `contact` varchar(16) DEFAULT NULL,
  `gender_id` int(11) DEFAULT NULL,
  `ms_id` int(11) DEFAULT NULL,
  `role_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `persons`
--

INSERT INTO `persons` (`person_id`, `name`, `dob`, `age`, `contact`, `gender_id`, `ms_id`, `role_id`) VALUES
(1, 'Syed Murtaza Hussain', '1984-12-03', 0, '0314-2308332', 1, 2, 1),
(7, 'aaaa', '2024-05-31', 0, '11111', 2, 2, 3),
(8, 'Ali Baba', '2024-05-31', 0, '0312-1234567', 1, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE `results` (
  `result_id` int(11) NOT NULL,
  `test_date` date DEFAULT NULL,
  `result_date` date DEFAULT NULL,
  `HB` int(11) DEFAULT NULL,
  `WBC` int(11) DEFAULT NULL,
  `MP` int(11) DEFAULT NULL,
  `PCV` int(11) DEFAULT NULL,
  `MCV` int(11) DEFAULT NULL,
  `MCH` int(11) DEFAULT NULL,
  `MCHC` int(11) DEFAULT NULL,
  `RBC` int(11) DEFAULT NULL,
  `Platelets` int(11) DEFAULT NULL,
  `Hypochromic` varchar(25) DEFAULT NULL,
  `Macrocytosis` varchar(25) DEFAULT NULL,
  `Microcytosis` varchar(25) DEFAULT NULL,
  `Anisocytosis` varchar(25) DEFAULT NULL,
  `Poikilocytosis` varchar(25) DEFAULT NULL,
  `person_id` int(11) DEFAULT NULL,
  `lab_no` varchar(25) DEFAULT NULL,
  `dept_no` varchar(25) DEFAULT NULL,
  `ref_phy` varchar(25) DEFAULT NULL,
  `result_desc` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role` varchar(16) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role`) VALUES
(1, 'administrator'),
(2, 'employee'),
(3, 'patient');

-- --------------------------------------------------------

--
-- Table structure for table `tests`
--

CREATE TABLE `tests` (
  `test_id` int(11) NOT NULL,
  `test_name` varchar(64) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tests`
--

INSERT INTO `tests` (`test_id`, `test_name`, `description`) VALUES
(1, 'HB', 'Hemoglobin'),
(2, 'WBC', 'White Blood Cells '),
(3, 'MP', 'Malarial Parasite'),
(4, 'PCV', 'Packed Cell Volume'),
(5, 'MCV', 'Mean Corpuscular Volume'),
(6, 'MCH', 'Mean Corpuscular Hemoglobin'),
(7, 'MCHC', 'Mean Corpuscular Hemoglobin Concentration'),
(8, 'RBC', 'Red Blood Cell'),
(9, 'Platelets', 'Platelets'),
(10, 'Hypochromic', 'Hypochromic'),
(11, 'Macrocytosis', 'Macrocytosis'),
(12, 'Microcytosis', 'Microcytosis'),
(13, 'Anisocytosis', 'Anisocytosis'),
(14, 'Poikilocytosis', 'Poikilocytosis');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `genders`
--
ALTER TABLE `genders`
  ADD PRIMARY KEY (`gender_id`);

--
-- Indexes for table `logins`
--
ALTER TABLE `logins`
  ADD PRIMARY KEY (`username`),
  ADD KEY `person_id` (`person_id`);

--
-- Indexes for table `maritial_statuses`
--
ALTER TABLE `maritial_statuses`
  ADD PRIMARY KEY (`ms_id`);

--
-- Indexes for table `persons`
--
ALTER TABLE `persons`
  ADD PRIMARY KEY (`person_id`),
  ADD KEY `gender_id` (`gender_id`),
  ADD KEY `ms_id` (`ms_id`),
  ADD KEY `role_id` (`role_id`);

--
-- Indexes for table `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`result_id`),
  ADD KEY `person_id` (`person_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `tests`
--
ALTER TABLE `tests`
  ADD PRIMARY KEY (`test_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `genders`
--
ALTER TABLE `genders`
  MODIFY `gender_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `maritial_statuses`
--
ALTER TABLE `maritial_statuses`
  MODIFY `ms_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `persons`
--
ALTER TABLE `persons`
  MODIFY `person_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `results`
--
ALTER TABLE `results`
  MODIFY `result_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tests`
--
ALTER TABLE `tests`
  MODIFY `test_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `logins`
--
ALTER TABLE `logins`
  ADD CONSTRAINT `logins_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `persons` (`person_id`);

--
-- Constraints for table `persons`
--
ALTER TABLE `persons`
  ADD CONSTRAINT `persons_ibfk_1` FOREIGN KEY (`gender_id`) REFERENCES `genders` (`gender_id`),
  ADD CONSTRAINT `persons_ibfk_2` FOREIGN KEY (`ms_id`) REFERENCES `maritial_statuses` (`ms_id`),
  ADD CONSTRAINT `persons_ibfk_3` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);

--
-- Constraints for table `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `results_ibfk_2` FOREIGN KEY (`person_id`) REFERENCES `persons` (`person_id`);
COMMIT;
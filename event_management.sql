-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 23, 2024 at 05:53 PM
-- Server version: 8.0.33-0ubuntu0.22.04.2
-- PHP Version: 8.1.2-1ubuntu2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `event_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `Admin`
--

CREATE TABLE `Admin` (
  `ADMIN_ID` int NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Password` varchar(255) DEFAULT NULL,
  `Contact` varchar(15) DEFAULT NULL,
  `Email_ID` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Admin`
--

INSERT INTO `Admin` (`ADMIN_ID`, `Name`, `Password`, `Contact`, `Email_ID`) VALUES
(1, 'Shivika', '$2y$10$0Effnu9WiH.bZDj.kxC8IOVD7Fvd.QZNT7ptHpf7zPqBN3UZ5DTdK', '987686756', 'shivi@gmail.com'),
(6, 'Shivika', '$2y$10$SEj/nsQH5hE1319WRsgN/.aNo6cMXCRF.p1QlfhM2.tu.bN7Eo2h2', '987686756', 'shivika123@gmail.com'),
(7, 'Teesha', '$2y$10$t69WUL0peWAj/MY10aF6ROeIx0GVzSzeUyWjByoN2QlBh12q/PqPK', '9878565464', 'teesha123@gmail.com'),
(8, 'Priyanshu', '$2y$10$Q/0vN3Cn/OnCvQqPRxhEMuYF3saCbh7sll453h1kUY7SMJJCkPvye', '9879876667', 'priyanshu@gmail.com'),
(9, 'Khushi', '$2y$10$.oGGsN6h5asnK1kyx2SB2e2zG7sz49Xhge2fNOaGST6tCyqsiCGbG', '9876543456', 'khushi123@gmail.com'),
(10, 'ShiviR', '$2y$10$bcMzX7qZRVzyfM7Ry7ELvenVw./tqY0JkBFvK2UGBSoshN8bqIrGW', '9878676987', 'shivir@iitg.ac.in');

-- --------------------------------------------------------

--
-- Table structure for table `Auditorium`
--

CREATE TABLE `Auditorium` (
  `Audi_ID` int NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Location` varchar(100) DEFAULT NULL,
  `Capacity` int DEFAULT NULL,
  `Projector` tinyint(1) DEFAULT '0',
  `Sound_Sys` tinyint(1) DEFAULT '0'
) ;

--
-- Dumping data for table `Auditorium`
--

INSERT INTO `Auditorium` (`Audi_ID`, `Name`, `Location`, `Capacity`, `Projector`, `Sound_Sys`) VALUES
(1, 'Bhupen Hazarika', 'in front of library', 1000, 1, 1),
(2, 'Mini Auditorium', 'below Bhupen Hazarika', 200, 1, 1),
(7, 'abcxyz', 'mnb978', 600, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Booking`
--

CREATE TABLE `Booking` (
  `Book_ID` int NOT NULL,
  `Event_ID` int NOT NULL,
  `Audi_ID` int NOT NULL,
  `Start_Time` timestamp NULL DEFAULT NULL,
  `End_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Booking`
--

INSERT INTO `Booking` (`Book_ID`, `Event_ID`, `Audi_ID`, `Start_Time`, `End_time`) VALUES
(1, 8, 1, '2024-11-14 12:30:00', '2024-11-16 12:30:00'),
(2, 11, 1, '2024-12-06 06:30:00', '2024-12-07 06:30:00'),
(3, 13, 1, '2025-01-02 07:20:00', '2025-01-03 06:20:00');

--
-- Triggers `Booking`
--
DELIMITER $$
CREATE TRIGGER `CheckAuditoriumCapacity` BEFORE INSERT ON `Booking` FOR EACH ROW BEGIN
    DECLARE audi_capacity INT;
    DECLARE event_capacity INT;

    SELECT Capacity INTO audi_capacity FROM Auditorium WHERE Audi_ID = NEW.Audi_ID;
    SELECT Capacity INTO event_capacity FROM Event WHERE Event_ID = NEW.Event_ID;

    IF event_capacity > audi_capacity THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Auditorium capacity is less than event capacity.';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `Event`
--

CREATE TABLE `Event` (
  `Event_ID` int NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Start_Time` timestamp NOT NULL,
  `End_Time` timestamp NOT NULL,
  `Capacity` int DEFAULT NULL,
  `Projector` tinyint(1) DEFAULT '0',
  `Sound_System` tinyint(1) DEFAULT '0',
  `REQUIREMENTS` text
) ;

--
-- Dumping data for table `Event`
--

INSERT INTO `Event` (`Event_ID`, `Name`, `Start_Time`, `End_Time`, `Capacity`, `Projector`, `Sound_System`, `REQUIREMENTS`) VALUES
(1, 'Alcheringa', '2025-01-16 18:30:00', '2025-01-20 18:30:00', 2000, 0, 1, ''),
(3, 'Spirit', '2025-02-12 12:30:00', '2025-02-13 18:30:00', 1500, 1, 1, ''),
(4, 'abcxyz', '2024-11-29 12:30:00', '2024-11-30 12:30:00', 500, 1, 1, ''),
(8, 'Techniche', '2024-11-14 12:30:00', '2024-11-16 12:30:00', 500, 1, 0, ''),
(9, 'hjvgbj', '2024-11-14 12:30:00', '2024-11-15 03:30:00', 500, 1, 0, ''),
(10, 'xyzq', '2024-11-30 06:30:00', '2024-12-01 09:30:00', 1500, 1, 1, ''),
(11, 'abcd', '2024-12-06 06:30:00', '2024-12-07 06:30:00', 500, 1, 1, ''),
(12, 'Alcheringa', '2024-11-14 06:19:00', '2024-11-15 06:20:00', 500, 1, 0, ''),
(13, 'Alcheringa', '2025-01-02 07:20:00', '2025-01-03 06:20:00', 500, 1, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `Feedback`
--

CREATE TABLE `Feedback` (
  `Feedback_ID` int NOT NULL,
  `Participant_ID` int NOT NULL,
  `Event_ID` int NOT NULL,
  `Feedback` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Feedback`
--

INSERT INTO `Feedback` (`Feedback_ID`, `Participant_ID`, `Event_ID`, `Feedback`) VALUES
(1, 2872, 1, 'Nice Event!\r\n'),
(2, 8297, 3, 'niceee/'),
(3, 2106, 4, 'nice\r\n'),
(4, 3726, 3, 'jhgvlkfs bn'),
(5, 3085, 12, 'nice!');

-- --------------------------------------------------------

--
-- Table structure for table `Participant`
--

CREATE TABLE `Participant` (
  `Participant_ID` int NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Phone` varchar(15) DEFAULT NULL,
  `Username` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Participant`
--

INSERT INTO `Participant` (`Participant_ID`, `Name`, `Email`, `Phone`, `Username`) VALUES
(3085, 'Shivika', 'shivi245@iitg.ac.in', '9879879980', 'ShivikaK'),
(3379, 'shivika', 'shivi123@gmail.com', '98786745645', 'Shivika123'),
(3726, 'Pratham', 'pratham@gmail.com', '896875645', 'Pratham');

-- --------------------------------------------------------

--
-- Table structure for table `Registers`
--

CREATE TABLE `Registers` (
  `Register_ID` int NOT NULL,
  `Admin_ID` int NOT NULL,
  `Event_ID` int NOT NULL,
  `Registration_Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Registers`
--

INSERT INTO `Registers` (`Register_ID`, `Admin_ID`, `Event_ID`, `Registration_Date`) VALUES
(1, 6, 1, '2024-11-03 17:40:12'),
(3, 6, 3, '2024-11-03 18:04:54'),
(4, 6, 4, '2024-11-04 05:54:28'),
(5, 6, 8, '2024-11-04 07:13:03'),
(6, 6, 9, '2024-11-04 07:13:57'),
(7, 9, 10, '2024-11-11 05:13:22'),
(8, 9, 11, '2024-11-11 05:14:39'),
(9, 6, 12, '2024-11-11 06:19:33'),
(10, 6, 13, '2024-11-11 06:20:17');

-- --------------------------------------------------------

--
-- Table structure for table `Request`
--

CREATE TABLE `Request` (
  `Request_ID` int NOT NULL,
  `Event_ID` int NOT NULL,
  `Participant_ID` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Request`
--

INSERT INTO `Request` (`Request_ID`, `Event_ID`, `Participant_ID`) VALUES
(1, 3, 2540),
(2, 1, 1899),
(3, 1, 7978),
(4, 1, 7158),
(5, 3, 8297),
(6, 4, 2106),
(7, 3, 8000),
(8, 3, 3379),
(9, 3, 3726),
(10, 12, 3085);

-- --------------------------------------------------------

--
-- Table structure for table `Ticket`
--

CREATE TABLE `Ticket` (
  `Ticket_ID` int NOT NULL,
  `Participant_ID` int NOT NULL,
  `Event_ID` int NOT NULL,
  `Issue_Date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `STATUS` text,
  `Auditorium` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `Ticket`
--

INSERT INTO `Ticket` (`Ticket_ID`, `Participant_ID`, `Event_ID`, `Issue_Date`, `STATUS`, `Auditorium`) VALUES
(1, 2705, 3, '2024-11-03 18:56:06', NULL, NULL),
(2, 2540, 3, '2024-11-03 18:57:32', 'approved', NULL),
(3, 1899, 1, '2024-11-03 19:06:47', 'approved', NULL),
(4, 7978, 1, '2024-11-03 19:11:31', 'approved', NULL),
(5, 7158, 1, '2024-11-04 04:49:12', 'approved', NULL),
(6, 8297, 3, '2024-11-04 05:20:10', 'approved', NULL),
(7, 2106, 4, '2024-11-04 05:55:41', 'approved', NULL),
(8, 8000, 3, '2024-11-04 06:04:04', NULL, NULL),
(9, 3379, 3, '2024-11-04 06:47:06', 'approved', NULL),
(10, 3726, 3, '2024-11-04 06:47:38', 'approved', NULL),
(11, 3085, 12, '2024-11-11 06:22:05', 'approved', NULL);

--
-- Triggers `Ticket`
--
DELIMITER $$
CREATE TRIGGER `CheckEventCapacity` BEFORE INSERT ON `Ticket` FOR EACH ROW BEGIN
    DECLARE current_count INT;
    DECLARE max_capacity INT;

    SELECT COUNT(*) INTO current_count FROM Ticket WHERE Event_ID = NEW.Event_ID;
    SELECT Capacity INTO max_capacity FROM Event WHERE Event_ID = NEW.Event_ID;

    IF current_count >= max_capacity THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Event capacity reached. Cannot book more tickets.';
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `UpdateCapacityOnTicketDelete` AFTER DELETE ON `Ticket` FOR EACH ROW BEGIN
    UPDATE Event
    SET Capacity = Capacity + 1
    WHERE Event_ID = OLD.Event_ID;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Admin`
--
ALTER TABLE `Admin`
  ADD PRIMARY KEY (`ADMIN_ID`),
  ADD UNIQUE KEY `Email_ID` (`Email_ID`);

--
-- Indexes for table `Auditorium`
--
ALTER TABLE `Auditorium`
  ADD PRIMARY KEY (`Audi_ID`);

--
-- Indexes for table `Booking`
--
ALTER TABLE `Booking`
  ADD PRIMARY KEY (`Book_ID`),
  ADD KEY `Event_ID` (`Event_ID`),
  ADD KEY `Audi_ID` (`Audi_ID`);

--
-- Indexes for table `Event`
--
ALTER TABLE `Event`
  ADD PRIMARY KEY (`Event_ID`);

--
-- Indexes for table `Feedback`
--
ALTER TABLE `Feedback`
  ADD PRIMARY KEY (`Feedback_ID`),
  ADD KEY `Participant_ID` (`Participant_ID`),
  ADD KEY `Event_ID` (`Event_ID`);

--
-- Indexes for table `Participant`
--
ALTER TABLE `Participant`
  ADD PRIMARY KEY (`Participant_ID`),
  ADD KEY `Phone` (`Phone`) USING BTREE,
  ADD KEY `Email` (`Email`) USING BTREE;

--
-- Indexes for table `Registers`
--
ALTER TABLE `Registers`
  ADD PRIMARY KEY (`Register_ID`),
  ADD KEY `Admin_ID` (`Admin_ID`),
  ADD KEY `Event_ID` (`Event_ID`);

--
-- Indexes for table `Request`
--
ALTER TABLE `Request`
  ADD PRIMARY KEY (`Request_ID`),
  ADD KEY `Event_ID` (`Event_ID`),
  ADD KEY `fk_participant` (`Participant_ID`);

--
-- Indexes for table `Ticket`
--
ALTER TABLE `Ticket`
  ADD PRIMARY KEY (`Ticket_ID`),
  ADD KEY `Participant_ID` (`Participant_ID`),
  ADD KEY `Event_ID` (`Event_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Admin`
--
ALTER TABLE `Admin`
  MODIFY `ADMIN_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `Booking`
--
ALTER TABLE `Booking`
  MODIFY `Book_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `Event`
--
ALTER TABLE `Event`
  MODIFY `Event_ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `Feedback`
--
ALTER TABLE `Feedback`
  MODIFY `Feedback_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `Participant`
--
ALTER TABLE `Participant`
  MODIFY `Participant_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3727;

--
-- AUTO_INCREMENT for table `Registers`
--
ALTER TABLE `Registers`
  MODIFY `Register_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `Request`
--
ALTER TABLE `Request`
  MODIFY `Request_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `Ticket`
--
ALTER TABLE `Ticket`
  MODIFY `Ticket_ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Booking`
--
ALTER TABLE `Booking`
  ADD CONSTRAINT `Booking_ibfk_1` FOREIGN KEY (`Event_ID`) REFERENCES `Event` (`Event_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `Booking_ibfk_2` FOREIGN KEY (`Audi_ID`) REFERENCES `Auditorium` (`Audi_ID`) ON DELETE CASCADE;

--
-- Constraints for table `Feedback`
--
ALTER TABLE `Feedback`
  ADD CONSTRAINT `Feedback_ibfk_1` FOREIGN KEY (`Participant_ID`) REFERENCES `Participant` (`Participant_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `Feedback_ibfk_2` FOREIGN KEY (`Event_ID`) REFERENCES `Event` (`Event_ID`) ON DELETE CASCADE;

--
-- Constraints for table `Registers`
--
ALTER TABLE `Registers`
  ADD CONSTRAINT `Registers_ibfk_1` FOREIGN KEY (`Admin_ID`) REFERENCES `Admin` (`ADMIN_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `Registers_ibfk_2` FOREIGN KEY (`Event_ID`) REFERENCES `Event` (`Event_ID`) ON DELETE CASCADE;

--
-- Constraints for table `Request`
--
ALTER TABLE `Request`
  ADD CONSTRAINT `fk_participant` FOREIGN KEY (`Participant_ID`) REFERENCES `Participant` (`Participant_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `Request_ibfk_1` FOREIGN KEY (`Event_ID`) REFERENCES `Event` (`Event_ID`) ON DELETE CASCADE;

--
-- Constraints for table `Ticket`
--
ALTER TABLE `Ticket`
  ADD CONSTRAINT `Ticket_ibfk_1` FOREIGN KEY (`Participant_ID`) REFERENCES `Participant` (`Participant_ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `Ticket_ibfk_2` FOREIGN KEY (`Event_ID`) REFERENCES `Event` (`Event_ID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

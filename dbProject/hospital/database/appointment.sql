SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `appointment` (
  `appointmentId` int(11) NOT NULL,
  `patientId` varchar(5) NOT NULL,
  `doctorId` varchar(5) NOT NULL,
  `appointmentStatus` enum('discharged','admit') NOT NULL,
  `doctorNote` varchar(255) DEFAULT NULL,
  `appointmentDate` date NOT NULL,
  `appointmentTime` time NOT NULL,
  PRIMARY KEY (`appointmentId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `outbreaks`
--

INSERT INTO `appointment` (`appointmentId`, `patientId`, `doctorId`, `appointmentStatus`, `doctorNote`, `appointmentDate`, `appointmentTime`)
VALUES
(1, 'p01', 'd01', 'admit', 'Patient needs to be kept overnight for observation', '2023-03-05', '13:30:00'),
(2, 'p02', 'd02', 'discharged', 'Patient is free to go home, follow-up in 2 weeks', '2023-02-07', '15:30:00'),
(3, 'p01', 'd02', 'discharged', 'Patient requires surgery, schedule for tomorrow', '2023-02-08', '16:00:00');
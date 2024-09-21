-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 18, 2024 at 12:18 PM
-- Server version: 10.4.16-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `note_taking`
--

-- --------------------------------------------------------

--
-- Table structure for table `notes`
--

CREATE TABLE `notes` (
  `note_id` int(11) NOT NULL,
  `user_ID` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `note` varchar(1000) NOT NULL,
  `last_updated_at` datetime NOT NULL,
  `is_favorite` tinyint(1) DEFAULT 0,
  `is_archived` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notes`
--

INSERT INTO `notes` (`note_id`, `user_ID`, `title`, `note`, `last_updated_at`, `is_favorite`, `is_archived`) VALUES
(66, 6, 'Test', '\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\"', '2024-04-18 09:55:30', 0, 0),
(67, 12, '1914 translation by H. Rackham', '\"But I must explain to you how all this mistaken idea of denouncing pleasure and praising pain was born and I will give you a complete account of the system, and expound the actual teachings of the great explorer of the truth, the master-builder of human happiness. No one rejects, dislikes, or avoids pleasure itself, because it is pleasure, but because those who do not know how to pursue pleasure rationally encounter consequences that are extremely painful. Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure. To take a trivial example, which of us ever undertakes laborious physical exercise, except to obtain some advantage from it? But who has any right to find fault with a man who chooses to enjoy a pleasure that has no annoying consequences, or one who avoids a pain that produces no resultant pleasure?\"', '2024-04-18 09:57:42', 0, 0),
(68, 12, 'Section 1.10.32 of \"de Finibus Bonorum et Malorum\", written by Cicero in 45 BC', '', '2024-04-18 09:58:21', 0, 0),
(69, 12, 'Section 1.10.32 of \"de Finibus Bonorum et Malorum\", written by Cicero in 45 BC', '\"Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?\"', '2024-04-18 10:00:02', 1, 0),
(70, 12, 'Japhy', 'I AM JAPHZEL MOJADO CURRENTLY LIVING IN NAGA CEBU', '2024-04-18 10:00:47', 1, 0),
(71, 12, 'BASKET', 'BASKETBALL IS A PHYSICAL SPORT', '2024-04-18 10:01:23', 0, 1),
(72, 13, 'My Diary ', 'I am allen sanchez from Minglanilla. My course is BSIT', '2024-04-18 10:13:07', 0, 0),
(73, 13, 'MY BASKETBALL JOURNEY', 'MY BASKETBALL JOURNEYMY BASKETBALL JOURNEYMY BASKETBALL JOURNEYMY BASKETBALL JOURNEYMY BASKETBALL JOURNEYMY BASKETBALL JOURNEYMY BASKETBALL JOURNEYMY BASKETBALL JOURNEYMY BASKETBALL JOURNEYMY BASKETBALL JOURNEYMY BASKETBALL JOURNEYMY BASKETBALL JOURNEYMY BASKETBALL JOURNEYMY BASKETBALL JOURNEYMY BASKETBALL JOURNEY', '2024-04-18 10:12:48', 0, 0),
(74, 13, 'BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY-SCC CAMPUS', 'POBLACION WARD 2 MINGLANILLA', '2024-04-18 10:13:49', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `user_ID` int(11) NOT NULL,
  `fullName` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `image_path` varchar(255) DEFAULT 'profile/user.png'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`user_ID`, `fullName`, `email`, `password`, `image_path`) VALUES
(6, 'admin', 'admin1@gmail.com', '21232f297a57a5a743894a0e4a801fc3', 'profile/user.png'),
(11, 'test2', 'test2@gmail.com', '0192023a7bbd73250516f069df18b500', 'profile/user.png'),
(12, 'japhzel', 'japh@gmail.com', '0192023a7bbd73250516f069df18b500', 'profile/user.png'),
(13, 'allen', 'allen@gmail.com', '0192023a7bbd73250516f069df18b500', 'profile/user.png'),
(14, 'test4', 'test4@gmail.com', '0192023a7bbd73250516f069df18b500', 'profile/user.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`note_id`),
  ADD KEY `user_ID` (`user_ID`);

--
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`user_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notes`
--
ALTER TABLE `notes`
  MODIFY `note_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `register`
--
ALTER TABLE `register`
  MODIFY `user_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notes`
--
ALTER TABLE `notes`
  ADD CONSTRAINT `notes_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `register` (`user_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

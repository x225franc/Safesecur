-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 27, 2022 at 06:08 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `safesecur`
--

-- --------------------------------------------------------

--
-- Table structure for table `info`
--

CREATE TABLE `info` (
  `id_info` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `msg` text NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `info`
--

INSERT INTO `info` (`id_info`, `name`, `phone`, `mail`, `msg`, `date`) VALUES
(9, 'DIAWARA ALPHA MALICK', '0677060367', 'diawaraalphamalick225@gmail.com', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Exercitationem sit temporibus officiis officia odit dolores adipisci, rem alias sint eius tempora quaerat, architecto quisquam provident ipsum iure. Deserunt, dolore sequi.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Exercitationem sit temporibus officiis officia odit dolores adipisci, rem alias sint eius tempora quaerat, architecto quisquam provident ipsum iure. Deserunt, dolore sequi.', '2022-07-27'),
(10, 'Alpha Malick Diawara', '0677060367', '225franc@gmail.com', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Exercitationem sit temporibus officiis officia odit dolores adipisci, rem alias sint eius tempora quaerat, architecto quisquam provident ipsum iure. Deserunt, dolore sequi.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Exercitationem sit temporibus officiis officia odit dolores adipisci, rem alias sint eius tempora quaerat, architecto quisquam provident ipsum iure. Deserunt, dolore sequi.Lorem ipsum dolor sit amet, consectetur adipisicing elit. Exercitationem sit temporibus officiis officia odit dolores adipisci, rem alias sint eius tempora quaerat, architecto quisquam provident ipsum iure. Deserunt, dolore sequi.', '2022-07-27'),
(11, 'DIAWARA ALPHA MALICK', '0677060367', 'diawaraalphamalick225@gmail.com', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Voluptatibus ipsam aliquid nulla distinctio id amet at vero deserunt animi eum, deleniti fugiat ab, impedit expedita earum atque repellat harum nam.\r\nLorem ipsum, dolor sit amet consectetur adipisicing elit. Voluptatibus ipsam aliquid nulla distinctio id amet at vero deserunt animi eum, deleniti fugiat ab, impedit expedita earum atque repellat harum nam.\r\nLorem ipsum, dolor sit amet consectetur adipisicing elit. Voluptatibus ipsam aliquid nulla distinctio id amet at vero deserunt animi eum, deleniti fugiat ab, impedit expedita earum atque repellat harum nam.\r\n', '2022-07-27'),
(12, 'DIAWARA ALPHA MALICK', '0677060367', 'diawaraalphamalick225@gmail.com', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Voluptatibus ipsam aliquid nulla distinctio id amet at vero deserunt animi eum, deleniti fugiat ab, impedit expedita earum atque repellat harum nam.\r\n', '2022-07-27'),
(13, 'Alpha Malick Diawara', '0677060367', '225franc@gmail.com', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Voluptatibus ipsam aliquid nulla distinctio id amet at vero deserunt animi eum, deleniti fugiat ab, impedit expedita earum atque repellat harum nam.\r\n', '2022-07-27'),
(14, 'DIAWARA ALPHA MALICK', '0677060367', 'diawaraalphamalick225@gmail.com', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Voluptatibus ipsam aliquid nulla distinctio id amet at vero deserunt animi eum, deleniti fugiat ab, impedit expedita earum atque repellat harum nam.\r\n', '2022-07-27'),
(15, 'DIAWARA ALPHA MALICK', '0677060367', 'diawaraalphamalick225@gmail.com', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Voluptatibus ipsam aliquid nulla distinctio id amet at vero deserunt animi eum, deleniti fugiat ab, impedit expedita earum atque repellat harum nam.\r\n', '2022-07-27'),
(16, 'Test', '2250757144902', 'test@test.test', 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Voluptatibus ipsam aliquid nulla distinctio id amet at vero deserunt animi eum, deleniti fugiat ab, impedit expedita earum atque repellat harum nam.', '2022-07-27');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`) VALUES
(1, 'admin', '123');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `info`
--
ALTER TABLE `info`
  ADD PRIMARY KEY (`id_info`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `info`
--
ALTER TABLE `info`
  MODIFY `id_info` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

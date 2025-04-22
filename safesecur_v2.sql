-- phpMyAdmin SQL Dump
CREATE DATABASE IF NOT EXISTS `safesecur_v2`;

USE `safesecur_v2`;

-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 19, 2025 at 05:22 PM
-- Server version: 8.0.30
-- PHP Version: 8.2.26
SET
  SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET
  time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;

/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;

/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;

/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `safesecur_v2`
--
-- --------------------------------------------------------
--
-- Table structure for table `actualites`
--
CREATE TABLE
  `actualites` (
    `id` int NOT NULL,
    `title` varchar(150) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
      `description` varchar(1000) CHARACTER
    SET
      utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
      `date` date DEFAULT NULL,
      `img` varchar(1000) DEFAULT NULL
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

-- --------------------------------------------------------
--
-- Table structure for table `catalogue`
--
CREATE TABLE
  `catalogue` (
    `id` int NOT NULL,
    `name` varchar(100) NOT NULL,
    `category_id` int NOT NULL,
    `description` text,
    `price` int NOT NULL,
    `img` varchar(1000) DEFAULT NULL
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;


-- --------------------------------------------------------
--
-- Table structure for table `category`
--
CREATE TABLE
  `category` (`id` int NOT NULL, `name` varchar(100) NOT NULL) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

--
-- Dumping data for table `category`
--
INSERT INTO
  `category` (`id`, `name`)
VALUES
  (6, 'Avion'),
  (7, 'Bateaux');

-- --------------------------------------------------------
--
-- Table structure for table `user`
--
CREATE TABLE
  `user` (
    `id` int NOT NULL,
    `username` varchar(100) NOT NULL,
    `password` varchar(255) NOT NULL
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--
--
-- Indexes for table `actualites`
--
ALTER TABLE `actualites` ADD PRIMARY KEY (`id`);

--
-- Indexes for table `catalogue`
--
ALTER TABLE `catalogue` ADD PRIMARY KEY (`id`),
ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category` ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user` ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--
--
-- AUTO_INCREMENT for table `actualites`
--
ALTER TABLE `actualites` MODIFY `id` int NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 1;

--
-- AUTO_INCREMENT for table `catalogue`
--
ALTER TABLE `catalogue` MODIFY `id` int NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 1;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category` MODIFY `id` int NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 1;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user` MODIFY `id` int NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 3;

--
-- Constraints for dumped tables
--
--
-- Constraints for table `catalogue`
--
ALTER TABLE `catalogue` ADD CONSTRAINT `catalogue_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;

/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;

/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
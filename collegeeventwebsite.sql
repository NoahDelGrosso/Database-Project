-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 07, 2019 at 04:12 AM
-- Server version: 5.7.26
-- PHP Version: 7.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `collegeeventwebsite`
--

-- --------------------------------------------------------

--
-- Table structure for table `rsos`
--

DROP TABLE IF EXISTS `rsos`;
CREATE TABLE IF NOT EXISTS `rsos` (
  `rso_id` int(11) NOT NULL AUTO_INCREMENT,
  `rso_name` varchar(256) NOT NULL,
  `university_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rso_email` int(11) NOT NULL,
  `rso_official` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rso_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `universities`
--

DROP TABLE IF EXISTS `universities`;
CREATE TABLE IF NOT EXISTS `universities` (
  `university_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `university_name` varchar(256) NOT NULL,
  `university_description` text,
  `university_superadmin` int(10) UNSIGNED DEFAULT NULL,
  `university_enrollment` int(11) DEFAULT NULL,
  `university_picture` varchar(256) DEFAULT NULL,
  `university_latitude` float NOT NULL,
  `university_longitude` float NOT NULL,
  `university_zipcode` int(11) NOT NULL,
  PRIMARY KEY (`university_id`),
  UNIQUE KEY `university_name` (`university_name`),
  UNIQUE KEY `university_name_2` (`university_name`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `password` varchar(256) NOT NULL,
  `role` int(1) NOT NULL DEFAULT '0',
  `university_id` int(11) UNSIGNED NOT NULL,
  `email` varchar(256) NOT NULL,
  `name` varchar(256) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `password`, `role`, `university_id`, `email`, `name`) VALUES
(1, 'ggggg', 0, 1, 'ppppp', 'Bob'),
(2, 'aaaa', 2, 0, 'qqqqq', 'Polly'),
(3, 'bug', 2, 3, 'k', 'Mathilda');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

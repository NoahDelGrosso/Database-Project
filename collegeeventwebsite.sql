-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 19, 2019 at 07:34 PM
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
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `comment_text` text NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `user_id`, `event_id`, `comment_text`) VALUES
(1, 4, 2, 'sweet\r\n'),
(2, 6, 3, 'awesome'),
(3, 2, 4, 'yesss\r\n'),
(4, 11, 3, 'Veggies Suck'),
(5, 12, 5, 'nacie'),
(6, 12, 3, 'get off our page mane'),
(7, 12, 4, 'yes im likin it!'),
(8, 13, 2, 'oo la la'),
(9, 15, 2, 'see yall there!'),
(10, 15, 5, 'nah bruh u not needed'),
(11, 15, 5, 'kiwi stfu'),
(12, 17, 5, 'yeah kiwi stfu'),
(13, 17, 5, 'you cant make smoothies out of grains\r\n'),
(14, 18, 10, 'any vegetables or meats stay away, forbidden fruits only, apples cannot come either'),
(15, 19, 5, 'got juice?'),
(16, 19, 1, 'i wanna be a lemon!\r\n');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
  `event_id` int(11) NOT NULL AUTO_INCREMENT,
  `event_email` varchar(256) NOT NULL,
  `event_contact_email` varchar(256) DEFAULT NULL,
  `event_date` datetime NOT NULL,
  `event_name` varchar(256) NOT NULL,
  `event_phone` varchar(256) NOT NULL,
  `event_description` text NOT NULL,
  `university_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_address` text,
  `event_point` point DEFAULT NULL,
  `event_latitude` float DEFAULT NULL,
  `event_longitude` float DEFAULT NULL,
  `event_zipcode` int(11) NOT NULL,
  `event_type` int(11) NOT NULL,
  `rso_id` int(11) DEFAULT NULL,
  `event_approved` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_email`, `event_contact_email`, `event_date`, `event_name`, `event_phone`, `event_description`, `university_id`, `user_id`, `event_address`, `event_point`, `event_latitude`, `event_longitude`, `event_zipcode`, `event_type`, `rso_id`, `event_approved`) VALUES
(1, 'gmail.com', 'lemon@gmail.com', '2019-11-23 09:09:00', 'lemon gathering', '1234567890', 'lemons', 1, 1, 'lemons', '\0\0\0\0\0\0\0\0\0\0\0\0€<@fffffRÀ', NULL, NULL, 32763, 1, NULL, 0),
(2, 'gmail.com', 'apple@gmail.com', '2019-11-27 08:30:00', 'apple Gathering', '1234343434', 'apple followers ', 1, 2, 'aples', '\0\0\0\0\0\0\0\0\0\0\0\0€<@fffffRÀ', NULL, NULL, 23746, 2, NULL, 0),
(3, 'gmail.com', 'potato@gmail.com', '2019-12-19 18:00:00', 'veggie fun', '4239423', 'potato', 1, 3, 'potato', '\0\0\0\0\0\0\0ÍÌÌÌÌÌ$@ffffffL@', NULL, NULL, 12345, 2, NULL, 0),
(4, 'gmail.com', 'blueberry@gmail.com', '2019-11-28 04:30:00', 'berry gathering', '888888888', 'berries rule', 1, 7, 'berry way', '\0\0\0\0\0\0\0fffffT@fffffT@', NULL, NULL, 88776, 2, NULL, 0),
(5, 'gmail.com', 'grains@gmail.com', '2020-01-06 15:00:00', 'grains bro', '3487448', 'grains brah', 1, 9, '89 grain way', '\0\0\0\0\0\0\0ffffffP@333333SÀ', NULL, NULL, 44564, 2, NULL, 0),
(6, 'gmail.com', 'kiwi@gmail.com', '2019-11-27 15:03:00', 'kiwi dance festival', '647564733', 'kiwis and peaches welcome!', 1, 12, 'kiwi drive', '\0\0\0\0\0\0\0ffffffP@333333SÀ', NULL, NULL, 23543, 2, NULL, 0),
(7, 'gmail.com', 'onion@gmail.com', '2019-11-06 18:00:00', 'we smell festival', '338723432', 'onions and other smelly veggies welcome', 1, 13, 'onion way', '\0\0\0\0\0\0\0ffffffP@333333SÀ', NULL, NULL, 34343, 1, NULL, 0),
(8, 'gmail.com', 'blackberry@gmail.com', '2019-11-30 04:30:00', 'berries are more important than citrus', '8374674344', 'calling all berries phone home', 1, 15, 'blackberry way', '\0\0\0\0\0\0\0fffffT@fffffT@', NULL, NULL, 44444, 2, NULL, 0),
(9, 'gmail.com', 'banana@gmail.com', '2020-03-17 20:00:00', 'smoothie making day yeah', '5463546453', 'lets make some smoothies', 1, 17, 'smoothie day way ', '\0\0\0\0\0\0\0ÍÌÌÌÌÌF@333333V@', NULL, NULL, 33647, 2, NULL, 0),
(10, 'gmail.com', 'peach@gmail.com', '2019-12-19 15:30:00', 'forbidden fruits join up', '635463473', 'forbidden fruits only', 1, 18, 'forbidden', '\0\0\0\0\0\0\0\0\0\0\0\0@A@š™™™™¹VÀ', NULL, NULL, 35467, 1, NULL, 0),
(11, 'gmail.com', 'grapes@gmail.com', '2020-06-18 14:30:00', 'grape juice', '4843487437', 'come if youre a fruit', 1, 19, 'were guna make juice drive', '\0\0\0\0\0\0\0333333A@fffff¦KÀ', NULL, NULL, 88776, 2, NULL, 0),
(12, 'gmail.com', 'plum@gmail.com', '2019-11-16 15:45:00', 'plums. grapes. and citrus', '94324328', 'calling all my home boys', 1, 20, '39 plum lane', '\0\0\0\0\0\0\0\0\0\0\0\0ÀF@š™™™™™P@', NULL, NULL, 22328, 2, NULL, 0),
(13, 'gmail.com', 'pineapple@gmail.com', '2020-07-15 18:00:00', 'pineapple festival', '432432423', 'all hail the magic pineapple', 1, 21, 'hawaii', '\0\0\0\0\0\0\0š™™™™¹V@fffffæZÀ', NULL, NULL, 99999, 2, NULL, 0),
(14, 'gmail.com', 'fish@gmail.com', '2019-11-30 16:00:00', 'chicken and fish tacos', '4657382723', 'free tacos', 1, 16, '456 taco circle', '\0\0\0\0\0\0\0\0\0\0\0\0ÀF@ÍÌÌÌÌL7À', NULL, NULL, 45398, 2, NULL, 0),
(15, 'gmail.com', 'strawberry@gmail.com', '2020-02-11 14:30:00', 'strawberry festival', '74627443944', 'creating this for my fellow strawberries', 1, 22, '45 strawberry way', '\0\0\0\0\0\0\0ÍÌÌÌÌÌ<@ÍÌÌÌÌÌ<@', NULL, NULL, 28282, 1, NULL, 0),
(16, 'gmail.com', 'papaya@gmail.com', '2020-03-10 14:03:00', 'do you like corvettes?', '349238742', 'Wanna see a papaya driving a car', 1, 23, 'corvettes', '\0\0\0\0\0\0\0ffffff=@\0\0\0\0\0`R@', NULL, NULL, 43643, 2, NULL, 0),
(17, 'gmail.com', 'mango@gmail.com', '2020-04-22 01:00:00', 'mangos and papayas', '3423423234', 'mangos and papayas welcome', 1, 24, '45 mango way', '\0\0\0\0\0\0\0\0\0\0\0\0@A@\0\0\0\0\0@C@', NULL, NULL, 83745, 2, NULL, 0),
(18, 'gmail.com', 'apricot@gmail.com', '2019-11-15 14:00:00', 'an apricot is not a peach', '28762444', 'peaches cant come ', 1, 25, '73 peaches not way', '\0\0\0\0\0\0\033333³B@\0\0\0\0\0`R@', NULL, NULL, 37434, 1, NULL, 0),
(19, 'gmail.com', 'apricot@gmail.com', '2019-12-11 16:00:00', 'rally against the meats', '245353434', 'hey fruits come join us in our vegan rally against meat', 1, 25, 'save the animals drive', '\0\0\0\0\0\0\0333333A@\0\0\0\0\0àT@', NULL, NULL, 73843, 2, NULL, 0),
(20, 'gmail.com', 'apricot@gmail.com', '2019-11-16 14:00:00', 'vegan rally', '72493743', 'no meat ', 1, 25, 'dont eat it way', '\0\0\0\0\0\0\033333³B@\0\0\0\0\0`R@', NULL, NULL, 38743, 2, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `event_subscriptions`
--

DROP TABLE IF EXISTS `event_subscriptions`;
CREATE TABLE IF NOT EXISTS `event_subscriptions` (
  `event_subscriptions_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  PRIMARY KEY (`event_subscriptions_id`)
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `event_subscriptions`
--

INSERT INTO `event_subscriptions` (`event_subscriptions_id`, `user_id`, `event_id`) VALUES
(1, 1, 1),
(2, 6, 2),
(3, 6, 3),
(4, 7, 4),
(5, 9, 2),
(6, 9, 5),
(7, 9, 3),
(8, 9, 4),
(9, 2, 1),
(10, 2, 2),
(11, 2, 5),
(12, 2, 3),
(13, 2, 4),
(14, 10, 2),
(15, 10, 5),
(16, 10, 3),
(17, 10, 4),
(18, 11, 2),
(19, 11, 5),
(20, 11, 3),
(21, 11, 4),
(22, 12, 1),
(23, 12, 2),
(24, 12, 5),
(25, 12, 6),
(26, 12, 3),
(27, 12, 4),
(28, 13, 2),
(29, 13, 5),
(30, 13, 6),
(31, 13, 7),
(32, 13, 3),
(33, 13, 4),
(34, 5, 1),
(35, 5, 2),
(36, 5, 5),
(37, 5, 6),
(38, 5, 7),
(39, 5, 3),
(40, 5, 4),
(41, 14, 1),
(42, 14, 2),
(43, 14, 5),
(44, 14, 6),
(45, 14, 7),
(46, 14, 3),
(47, 14, 4),
(48, 15, 1),
(49, 15, 2),
(50, 15, 5),
(51, 15, 6),
(52, 15, 7),
(53, 15, 3),
(54, 15, 4),
(55, 15, 8),
(56, 16, 2),
(57, 16, 3),
(58, 16, 4),
(59, 17, 1),
(60, 17, 2),
(61, 17, 5),
(62, 17, 6),
(63, 17, 7),
(64, 17, 3),
(65, 17, 4),
(66, 17, 8),
(67, 17, 9),
(68, 18, 1),
(69, 18, 10),
(70, 18, 5),
(71, 18, 4),
(72, 18, 8),
(73, 19, 10),
(74, 19, 7),
(75, 19, 3),
(76, 19, 6),
(77, 19, 9),
(78, 19, 11),
(79, 19, 1),
(80, 20, 10),
(81, 20, 3),
(82, 20, 12),
(83, 20, 8),
(84, 20, 6),
(85, 20, 9),
(86, 20, 4),
(87, 21, 10),
(88, 21, 3),
(89, 21, 4),
(90, 21, 6),
(91, 23, 2),
(92, 23, 3),
(93, 23, 5),
(94, 23, 5),
(95, 23, 8),
(96, 23, 9),
(97, 24, 10),
(98, 24, 5),
(99, 24, 7),
(100, 1, 11),
(101, 1, 12),
(102, 1, 4),
(103, 1, 16);

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
  `rso_email` varchar(256) NOT NULL,
  `rso_official` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`rso_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rsos`
--

INSERT INTO `rsos` (`rso_id`, `rso_name`, `university_id`, `user_id`, `rso_email`, `rso_official`) VALUES
(1, 'lemon followers', 1, 1, 'gmail.com', 1),
(2, 'apple followers', 1, 2, 'gmail.com', 1),
(3, 'veggie followers', 1, 3, 'gmail.com', 1),
(4, 'potato followers', 1, 3, 'gmail.com', 1),
(5, 'zucchini people', 1, 4, 'gmail.com', 1),
(6, 'oranges', 1, 5, 'gmail.com', 1),
(7, 'loog', 1, 3, 'gmail.com', 1),
(8, 'we are the berry group', 1, 7, 'gmail.com', 1),
(9, 'dod', 1, 8, 'gmail.com', 1),
(10, 'Arby\'s', 1, 11, 'gmail.com', 1),
(11, 'kiwis and peaches only', 1, 12, 'gmail.com', 1),
(12, 'booyah', 1, 5, 'gmail.com', 0),
(13, 'berry Fu', 1, 15, 'gmail.com', 0),
(14, 'pescador', 1, 16, 'gmail.com', 0),
(15, 'lets make smoothies', 1, 17, 'gmail.com', 0),
(16, 'forbidden fruit', 1, 18, 'gmail.com', 0),
(17, 'grape juice', 1, 19, 'gmail.com', 0),
(18, 'are you a plum too?', 1, 20, 'gmail.com', 0),
(19, 'join if you like pineapple', 1, 21, 'gmail.com', 0),
(20, 'chicken tacos', 1, 16, 'gmail.com', 0),
(21, 'papaya power', 1, 23, 'gmail.com', 0),
(22, 'ratatonga', 1, 25, 'gmail.com', 0);

-- --------------------------------------------------------

--
-- Table structure for table `rso_memberships`
--

DROP TABLE IF EXISTS `rso_memberships`;
CREATE TABLE IF NOT EXISTS `rso_memberships` (
  `rso_membership_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `rso_id` int(11) NOT NULL,
  PRIMARY KEY (`rso_membership_id`)
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rso_memberships`
--

INSERT INTO `rso_memberships` (`rso_membership_id`, `user_id`, `rso_id`) VALUES
(1, 1, 1),
(2, 5, 1),
(3, 5, 2),
(4, 5, 3),
(5, 5, 4),
(6, 5, 5),
(7, 5, 6),
(8, 7, 7),
(9, 7, 5),
(10, 7, 1),
(11, 7, 2),
(12, 7, 3),
(13, 7, 4),
(14, 7, 6),
(15, 8, 8),
(16, 8, 1),
(17, 8, 2),
(18, 8, 3),
(19, 8, 4),
(20, 8, 5),
(21, 8, 6),
(22, 8, 7),
(23, 8, 9),
(24, 2, 1),
(25, 2, 2),
(26, 2, 3),
(27, 2, 4),
(28, 2, 5),
(29, 2, 6),
(30, 2, 7),
(31, 2, 8),
(32, 2, 9),
(33, 12, 1),
(34, 12, 2),
(35, 12, 3),
(36, 12, 4),
(37, 12, 5),
(38, 12, 6),
(39, 12, 7),
(40, 12, 8),
(41, 12, 9),
(42, 12, 10),
(43, 12, 11),
(44, 5, 7),
(45, 5, 8),
(46, 5, 9),
(47, 5, 10),
(48, 5, 11),
(49, 15, 1),
(50, 15, 2),
(51, 15, 3),
(52, 15, 4),
(53, 15, 5),
(54, 15, 6),
(55, 15, 7),
(56, 15, 8),
(57, 15, 9),
(58, 15, 10),
(59, 15, 11),
(60, 15, 12),
(61, 17, 1),
(62, 17, 2),
(63, 17, 3),
(64, 17, 4),
(65, 17, 5),
(66, 17, 6),
(67, 17, 7),
(68, 17, 8),
(69, 17, 9),
(70, 17, 10),
(71, 17, 11),
(72, 17, 12),
(73, 17, 13),
(74, 17, 14),
(75, 18, 1),
(76, 18, 2),
(77, 18, 3),
(78, 18, 4),
(79, 18, 5),
(80, 18, 8),
(81, 18, 11),
(82, 18, 10),
(83, 19, 3),
(84, 19, 7),
(85, 19, 13),
(86, 19, 9),
(87, 19, 5),
(88, 19, 11),
(89, 20, 6),
(90, 20, 16),
(91, 20, 1),
(92, 20, 18),
(93, 20, 12),
(94, 21, 5),
(95, 21, 9),
(96, 21, 13),
(97, 21, 1),
(98, 21, 11),
(99, 21, 15),
(100, 21, 17),
(101, 21, 18),
(102, 21, 16),
(103, 21, 19),
(104, 21, 3),
(105, 22, 7),
(106, 22, 17),
(107, 22, 19),
(108, 22, 20),
(109, 22, 9),
(110, 22, 4),
(111, 22, 14),
(112, 22, 15),
(113, 23, 9),
(114, 23, 17),
(115, 23, 19),
(116, 23, 11),
(117, 23, 10),
(118, 23, 14),
(119, 23, 7),
(120, 23, 6),
(121, 21, 4),
(122, 25, 10),
(123, 25, 15);

-- --------------------------------------------------------

--
-- Table structure for table `universities`
--

DROP TABLE IF EXISTS `universities`;
CREATE TABLE IF NOT EXISTS `universities` (
  `university_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `university_name` varchar(256) DEFAULT NULL,
  `university_description` text,
  `university_superadmin` int(10) UNSIGNED DEFAULT NULL,
  `university_enrollment` int(11) DEFAULT NULL,
  `university_picture` varchar(256) DEFAULT NULL,
  `university_latitude` float DEFAULT NULL,
  `university_longitude` float DEFAULT NULL,
  `university_zipcode` int(11) DEFAULT NULL,
  `university_point` point DEFAULT NULL,
  PRIMARY KEY (`university_id`),
  UNIQUE KEY `university_name` (`university_name`),
  UNIQUE KEY `university_name_2` (`university_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `universities`
--

INSERT INTO `universities` (`university_id`, `university_name`, `university_description`, `university_superadmin`, `university_enrollment`, `university_picture`, `university_latitude`, `university_longitude`, `university_zipcode`, `university_point`) VALUES
(1, 'Fruit University', 'A bunch of fruits', 1, 2000, 'fruit.com', NULL, NULL, 32765, '\0\0\0\0\0\0\0ffffffC@333333Z@'),
(2, 'Vegetable University', 'A bunch of Veggies', 3, 8000, 'veggie.com', NULL, NULL, 75024, '\0\0\0\0\0\0\0ÍÌÌÌÌŒ@@333333X@'),
(3, 'Meat University', NULL, 11, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `password` varchar(256) NOT NULL,
  `role` int(1) NOT NULL DEFAULT '0',
  `role_2` int(11) NOT NULL,
  `university_id` int(11) UNSIGNED NOT NULL,
  `email` varchar(256) NOT NULL,
  `name` varchar(256) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `password`, `role`, `role_2`, `university_id`, `email`, `name`) VALUES
(1, 'lemon', 0, 2, 1, 'lemon@gmail.com', 'lemon'),
(2, 'apple', 0, 0, 1, 'apple@gmail.com', 'apple'),
(3, 'potato', 0, 2, 2, 'potato@gmail.com', 'potato'),
(4, 'zucchini', 0, 0, 2, 'zucchini@gmail.com', 'zucchini'),
(5, 'orange', 0, 0, 1, 'orange@gmail.com', 'orange'),
(6, 'mushroom', 0, 0, 2, 'mushroom@gmail.com', 'mushroom'),
(7, 'blueberry', 0, 0, 1, 'blueberry@gmail.com', 'blueberry'),
(8, 'strawberry', 0, 0, 1, 'strawberry@gmail.com', 'strawberry'),
(9, 'grain', 0, 0, 2, 'grain@gmail.com', 'grain'),
(10, 'parsley', 0, 0, 2, 'parsley@gmail.com', 'parsley'),
(11, 'chicken', 0, 2, 3, 'chicken@gmail.com', 'chicken'),
(12, 'kiwi', 0, 0, 1, 'kiwi@gmail.com', 'kiwi'),
(13, 'onion', 0, 0, 2, 'onion@gmail.com', 'onion'),
(14, 'squash', 0, 0, 1, 'squash@gmail.com', 'squash'),
(15, 'blackberry', 0, 0, 1, 'blackberry@gmail.com', 'blackberry'),
(16, 'fish', 0, 0, 3, 'fish@gmail.com', 'fish'),
(17, 'banana', 0, 0, 1, 'banana@gmail.com', 'banana'),
(18, 'peach', 0, 0, 1, 'peach@gmail.com', 'peach'),
(19, 'grapes', 0, 0, 1, 'grapes@gmail.com', 'grapes'),
(20, 'plum', 0, 0, 1, 'plum@gmail.com', 'plum'),
(21, 'pineapple', 0, 0, 1, 'pineapple@gmail.com', 'pineapple'),
(22, 'grapefruit', 0, 0, 1, 'grapefruit@gmail.com', 'grapefruit'),
(23, 'papaya', 0, 0, 1, 'papaya@gmail.com', 'papaya'),
(24, 'mango', 0, 0, 1, 'mango@gmail.com', 'mango'),
(25, 'apricot', 0, 0, 1, 'apricot@gmail.com', 'apricot');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 20, 2015 at 04:52 PM
-- Server version: 5.5.38
-- PHP Version: 5.3.10-1ubuntu3.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `event`
--

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE IF NOT EXISTS `event` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` tinyint(3) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `from` time NOT NULL,
  `to` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `from` (`from`,`to`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`id`, `user_id`, `name`, `date`, `from`, `to`) VALUES
(2, 1, 'Trusa', '2015-03-20', '10:00:00', '14:00:00'),
(3, 1, 'Tula', '0000-00-00', '15:14:00', '15:14:00'),
(4, 1, 'derun', '0000-00-00', '15:15:00', '15:16:00'),
(5, 1, 'Feldona', '0000-00-00', '10:00:00', '15:00:00'),
(6, 1, 'ddd', '0000-00-00', '15:17:00', '15:18:00'),
(7, 1, 'efewf', '0000-00-00', '15:18:00', '15:18:00'),
(8, 1, 'efefwefewf', '0000-00-00', '15:18:00', '15:18:00'),
(9, 1, 'e', '0000-00-00', '15:23:00', '15:23:00'),
(10, 1, 'efwffwefwefwef', '2015-03-20', '15:25:00', '15:25:00'),
(11, 1, 'j', '2015-03-20', '15:49:00', '15:49:00'),
(12, 1, 'u', '2015-03-20', '15:49:00', '15:50:00'),
(13, 2, 'eee', '2015-03-20', '15:49:00', '15:50:00'),
(14, 1, 'h', '2015-03-20', '16:37:00', '16:38:00'),
(15, 1, 'g', '2015-03-20', '16:40:00', '16:50:00');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`) VALUES
(1, 'Anthony'),
(2, 'Marc'),
(3, 'Martin');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `event`
--
ALTER TABLE `event`
  ADD CONSTRAINT `event_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

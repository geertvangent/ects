-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Host: 192.170.200.131
-- Generation Time: Dec 10, 2012 at 07:38 AM
-- Server version: 5.5.28
-- PHP Version: 5.3.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `desiderius`
--

-- --------------------------------------------------------

--
-- Table structure for table `discovery_bamaflex_history`
--

CREATE TABLE IF NOT EXISTS `discovery_bamaflex_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `history_id` int(10) unsigned NOT NULL,
  `history_source` int(10) unsigned NOT NULL,
  `previous_id` int(10) unsigned NOT NULL,
  `previous_source` int(10) unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=144 ;

--
-- Dumping data for table `discovery_bamaflex_history`
--

INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(1, 2, 1, 18, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(2, 1, 1, 15, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(3, 3, 1, 19, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(4, 4, 1, 20, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(5, 9, 1, 25, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(6, 5, 1, 21, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(7, 6, 1, 22, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(8, 7, 1, 23, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(9, 8, 1, 24, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(10, 1, 2, 8, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(11, 1, 2, 4, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(12, 8, 2, 9, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(13, 9, 2, 10, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(14, 10, 2, 11, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(15, 11, 2, 27, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(16, 18, 2, 84, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(17, 84, 2, 76, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(18, 76, 2, 68, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(19, 68, 2, 60, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(20, 60, 2, 52, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(21, 52, 2, 44, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(22, 44, 2, 36, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(23, 36, 2, 28, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(24, 21, 2, 87, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(25, 87, 2, 79, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(26, 79, 2, 71, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(27, 71, 2, 63, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(28, 63, 2, 55, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(29, 55, 2, 47, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(30, 47, 2, 39, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(31, 39, 2, 31, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(32, 37, 1, 19, 1, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(33, 37, 1, 21, 1, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(34, 63, 1, 49, 1, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(35, 64, 1, 49, 1, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(36, 25, 2, 91, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(37, 91, 2, 83, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(38, 83, 2, 75, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(39, 75, 2, 67, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(40, 67, 2, 59, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(41, 59, 2, 51, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(42, 51, 2, 43, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(43, 43, 2, 35, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(44, 22, 2, 88, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(45, 88, 2, 80, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(46, 80, 2, 72, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(47, 72, 2, 64, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(48, 64, 2, 56, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(49, 56, 2, 48, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(50, 48, 2, 40, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(51, 40, 2, 32, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(52, 24, 2, 90, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(53, 90, 2, 82, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(54, 82, 2, 74, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(55, 74, 2, 66, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(56, 66, 2, 58, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(57, 58, 2, 50, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(58, 50, 2, 42, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(59, 42, 2, 34, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(60, 23, 2, 89, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(61, 89, 2, 81, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(62, 81, 2, 73, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(63, 73, 2, 65, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(64, 65, 2, 57, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(65, 57, 2, 49, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(66, 49, 2, 41, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(67, 41, 2, 33, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(68, 38, 1, 20, 1, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(69, 38, 1, 22, 1, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(70, 38, 1, 25, 1, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(71, 19, 2, 85, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(72, 85, 2, 77, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(73, 77, 2, 69, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(74, 69, 2, 61, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(75, 61, 2, 53, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(76, 53, 2, 45, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(77, 45, 2, 37, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(78, 37, 2, 29, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(79, 4, 2, 5, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(80, 5, 2, 6, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(81, 6, 2, 7, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(82, 7, 2, 26, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(83, 20, 2, 86, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(84, 86, 2, 78, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(85, 78, 2, 70, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(86, 70, 2, 62, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(87, 62, 2, 54, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(88, 54, 2, 46, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(89, 46, 2, 38, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(90, 38, 2, 30, 2, 'application\\discovery\\module\\faculty\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(91, 15, 1, 432, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(92, 18, 1, 430, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(93, 23, 1, 473, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(94, 27, 1, 423, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(95, 30, 1, 433, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(96, 31, 1, 434, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(97, 33, 1, 441, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(98, 33, 1, 442, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(99, 35, 1, 443, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(100, 35, 1, 444, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(101, 39, 1, 417, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(102, 40, 1, 418, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(103, 46, 1, 415, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(104, 46, 1, 1139, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(105, 48, 1, 416, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(106, 48, 1, 467, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(107, 76, 1, 412, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(108, 77, 1, 413, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(109, 77, 1, 367, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(110, 77, 1, 391, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(111, 77, 1, 393, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(112, 78, 1, 414, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(113, 78, 1, 419, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(114, 78, 1, 420, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(115, 83, 1, 460, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(116, 90, 1, 461, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(117, 92, 1, 438, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(118, 96, 1, 477, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(119, 107, 1, 523, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(120, 108, 1, 522, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(121, 108, 1, 554, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(122, 114, 1, 538, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(123, 114, 1, 539, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(124, 114, 1, 540, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(125, 104, 1, 508, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(126, 104, 1, 509, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(127, 104, 1, 510, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(128, 104, 1, 511, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(129, 104, 1, 512, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(130, 104, 1, 513, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(131, 104, 1, 514, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(132, 104, 1, 515, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(133, 104, 1, 516, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(134, 104, 1, 517, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(135, 104, 1, 518, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(136, 104, 1, 519, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(137, 104, 1, 520, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(138, 104, 1, 521, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(139, 104, 1, 1121, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(140, 104, 1, 1142, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(141, 117, 1, 541, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(142, 120, 1, 533, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');
INSERT INTO `discovery_bamaflex_history` (`id`, `history_id`, `history_source`, `previous_id`, `previous_source`, `type`) VALUES(143, 121, 1, 483, 2, 'application\\discovery\\module\\training\\implementation\\bamaflex');

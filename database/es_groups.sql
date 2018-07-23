-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2018 at 11:11 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `erp2`
--

-- --------------------------------------------------------

--
-- Table structure for table `es_groups`
--

CREATE TABLE IF NOT EXISTS `dlvry_groups` (
  `es_groupsid` int(11) NOT NULL AUTO_INCREMENT,
  `es_groupname` varchar(255) NOT NULL,
  `es_grouporderby` int(11) NOT NULL,
  PRIMARY KEY (`es_groupsid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `es_groups`
--

INSERT INTO `dlvry_groups` (`es_groupsid`, `es_groupname`, `es_grouporderby`) VALUES
(1, 'BIOLOGY SCIENCE', 0),
(2, 'COMPUTER SCIENCE', 0),
(3, 'ELECTRONICS SCIENCE', 0),
(4, 'HUMANITIES', 0),
(5, 'COMMERCE', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

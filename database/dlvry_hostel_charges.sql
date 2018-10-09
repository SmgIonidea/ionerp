-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2018 at 07:18 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ionerp`
--

-- --------------------------------------------------------

--
-- Table structure for table `dlvry_hostel_charges`
--

CREATE TABLE IF NOT EXISTS `dlvry_hostel_charges` (
  `es_hostel_charges_id` int(11) NOT NULL AUTO_INCREMENT,
  `es_roomallotmentid` int(11) NOT NULL,
  `es_hostelbuldid` int(11) NOT NULL,
  `es_personid` int(11) NOT NULL,
  `es_persontype` varchar(255) NOT NULL,
  `due_month` date NOT NULL,
  `room_rate` double NOT NULL,
  `amount_paid` double NOT NULL,
  `deduction` double NOT NULL,
  `balance` double NOT NULL,
  `name` varchar(255) NOT NULL,
  `father` varchar(255) NOT NULL,
  `paid_on` date NOT NULL,
  `remarks` text NOT NULL,
  `created_on` date NOT NULL,
  PRIMARY KEY (`es_hostel_charges_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `dlvry_hostel_charges`
--

INSERT INTO `dlvry_hostel_charges` (`es_hostel_charges_id`, `es_roomallotmentid`, `es_hostelbuldid`, `es_personid`, `es_persontype`, `due_month`, `room_rate`, `amount_paid`, `deduction`, `balance`, `name`, `father`, `paid_on`, `remarks`, `created_on`) VALUES
(1, 101, 1, 1001, 'Staff', '2017-08-09', 3000, 2000, 300, 700, 'Hari Prasad', 'Vishnu Prasad', '2017-09-20', '', '2017-09-30'),
(2, 102, 2, 1002, 'Student', '2017-08-09', 5000, 2000, 300, 2700, 'Hari Prasad', 'Vishnu Prasad', '2017-09-20', '', '2017-09-30');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2018 at 07:17 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ioncudos_india_dev_v5_3`
--

-- --------------------------------------------------------

--
-- Table structure for table `dlvry_in_supplier_master`
--

CREATE TABLE IF NOT EXISTS `dlvry_in_supplier_master` (
  `es_in_supplier_masterid` int(11) NOT NULL AUTO_INCREMENT,
  `in_name` varchar(255) NOT NULL,
  `in_address` varchar(255) NOT NULL,
  `in_city` varchar(255) NOT NULL,
  `in_state` varchar(255) NOT NULL,
  `in_country` varchar(255) NOT NULL,
  `in_office_no` varchar(255) NOT NULL,
  `in_mobile_no` varchar(255) NOT NULL,
  `in_email` varchar(255) NOT NULL,
  `in_fax` varchar(255) NOT NULL,
  `in_description` varchar(255) NOT NULL,
  `status` enum('active','inactive','deleted') NOT NULL,
  PRIMARY KEY (`es_in_supplier_masterid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `dlvry_in_supplier_master`
--

INSERT INTO `dlvry_in_supplier_master` (`es_in_supplier_masterid`, `in_name`, `in_address`, `in_city`, `in_state`, `in_country`, `in_office_no`, `in_mobile_no`, `in_email`, `in_fax`, `in_description`, `status`) VALUES
(1, 'Sneha suppliers', 'Shimoga (CMC)', 'Shimoga (CMC)', 'Karnataka', 'India', '', '8345345345', 'raosuchitra158@gmail.com', '23456', 'sfgasdfgafs', 'active'),
(2, 'Sapna book house', 'Majestic main bus stop', 'Bangalore', 'Karnataka', 'India', '', '8123789670', 'sapnabookhouse@gmail.com', '678900', 'Test', 'active');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

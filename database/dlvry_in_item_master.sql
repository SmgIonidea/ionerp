-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2018 at 07:44 AM
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
-- Table structure for table `dlvry_in_item_master`
--

CREATE TABLE IF NOT EXISTS `dlvry_in_item_master` (
  `in_item_masterid` int(11) NOT NULL AUTO_INCREMENT,
  `in_item_code` varchar(255) NOT NULL,
  `in_item_name` varchar(255) NOT NULL,
  `cost` bigint(20) NOT NULL,
  `in_inventory_id` int(11) NOT NULL,
  `in_category_id` int(11) NOT NULL,
  `in_qty_available` float NOT NULL,
  `in_reorder_level` float NOT NULL,
  `in_quantity_issued` float NOT NULL DEFAULT '0',
  `in_last_recieved_date` datetime NOT NULL,
  `in_last_issued_date` datetime NOT NULL,
  `status` enum('active','inactive','deleted') NOT NULL DEFAULT 'active',
  `in_units` varchar(255) NOT NULL,
  `test` varchar(255) NOT NULL,
  `test1` varchar(255) NOT NULL,
  `created_by` varchar(20) NOT NULL,
  `modified_by` varchar(20) NOT NULL,
  `created_on` datetime NOT NULL,
  `modified_on` datetime NOT NULL,
  PRIMARY KEY (`in_item_masterid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `dlvry_in_item_master`
--

INSERT INTO `dlvry_in_item_master` (`in_item_masterid`, `in_item_code`, `in_item_name`, `cost`, `in_inventory_id`, `in_category_id`, `in_qty_available`, `in_reorder_level`, `in_quantity_issued`, `in_last_recieved_date`, `in_last_issued_date`, `status`, `in_units`, `test`, `test1`, `created_by`, `modified_by`, `created_on`, `modified_on`) VALUES
(1, '1', 'shoes', 250, 1, 1, 500, 200, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'active', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, '2', 'socks', 50, 2, 2, 500, 100, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'active', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, '3', 'tie', 50, 3, 3, 500, 200, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'active', '', '', '', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

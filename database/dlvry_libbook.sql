-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2018 at 12:03 PM
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
-- Table structure for table `dlvry_libbook`
--

CREATE TABLE IF NOT EXISTS `dlvry_libbook` (
  `es_libbookid` int(11) NOT NULL AUTO_INCREMENT,
  `lbook_dateofpurchase` date NOT NULL,
  `lbook_aceesnofrom` int(11) NOT NULL,
  `lbook_accessnoto` int(11) NOT NULL,
  `lbook_bookfromno` int(11) NOT NULL,
  `lbook_booktono` int(11) NOT NULL,
  `lbook_author` varchar(255) NOT NULL,
  `lbook_title` varchar(255) NOT NULL,
  `lbook_publishername` varchar(255) NOT NULL,
  `lbook_publisherplace` varchar(255) NOT NULL,
  `lbook_booksubject` varchar(255) NOT NULL,
  `lbook_bookedition` varchar(255) NOT NULL,
  `lbook_year` varchar(255) NOT NULL,
  `lbook_cost` varchar(255) NOT NULL,
  `lbook_sourse` varchar(255) NOT NULL,
  `lbook_aditinalbookinfo` varchar(255) NOT NULL,
  `lbook_bookstatus` varchar(255) NOT NULL,
  `lbook_category` varchar(255) NOT NULL,
  `lbook_class` varchar(255) NOT NULL,
  `lbook_booksubcategory` varchar(255) NOT NULL,
  `lbook_ref` varchar(255) NOT NULL,
  `lbook_statusstatus` varchar(255) NOT NULL,
  `lbook_pages` varchar(255) NOT NULL,
  `lbook_volume` varchar(255) NOT NULL,
  `lbook_bilnumber` varchar(255) NOT NULL,
  `lbook_image` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `issuestatus` enum('issued','notissued') NOT NULL,
  PRIMARY KEY (`es_libbookid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `dlvry_libbook`
--

INSERT INTO `dlvry_libbook` (`es_libbookid`, `lbook_dateofpurchase`, `lbook_aceesnofrom`, `lbook_accessnoto`, `lbook_bookfromno`, `lbook_booktono`, `lbook_author`, `lbook_title`, `lbook_publishername`, `lbook_publisherplace`, `lbook_booksubject`, `lbook_bookedition`, `lbook_year`, `lbook_cost`, `lbook_sourse`, `lbook_aditinalbookinfo`, `lbook_bookstatus`, `lbook_category`, `lbook_class`, `lbook_booksubcategory`, `lbook_ref`, `lbook_statusstatus`, `lbook_pages`, `lbook_volume`, `lbook_bilnumber`, `lbook_image`, `status`, `issuestatus`) VALUES
(1, '2018-09-05', 1, 0, 0, 2, 'sadfgh', 'wertyh', 'Aradhya', '', '', '', '', '', '', '', '', 'English', '', 'English literature', '', '', '', '', '2345678', '', 'active', 'notissued'),
(2, '2018-09-05', 2, 1, 0, 2, 'sadfgh', 'wertyh', 'Aradhya', '', '', '', '', '', '', '', '', 'English', '', 'English literature', '', '', '', '', '2345678', '', 'active', 'notissued'),
(9, '2018-10-03', 3, 2, 0, 2, 'astsg', 'safasdf', 'Aradhya', '', '', '', '', '', '', '', '', 'Kannada', '', 'Padakosha', '', '', '', '', '3456', '', 'active', 'notissued'),
(10, '2018-10-03', 4, 3, 0, 2, 'astsg', 'safasdf', 'Aradhya', '', '', '', '', '', '', '', '', 'Kannada', '', 'Padakosha', '', '', '', '', '3456', '', 'active', 'notissued'),
(16, '2018-10-02', 6, 5, 0, 2, 'SDF', 'WER', 'Aradhya', '', '', '', '', '', '', '', '', '1', '', '4', '', '', '', '', '87654', '', 'active', 'notissued'),
(15, '2018-10-02', 5, 4, 0, 2, 'SDF', 'WER', 'Aradhya', '', '', '', '', '', '', '', '', '1', '', '4', '', '', '', '', '87654', '', 'active', 'notissued');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

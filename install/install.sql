-- phpMyAdmin SQL Dump
-- version 2.8.0.1
-- http://www.phpmyadmin.net
-- 
-- Host: custsql-ipw25.eigbox.net
-- Generation Time: Mar 12, 2013 at 12:15 PM
-- Server version: 5.0.91
-- PHP Version: 4.4.9
-- 
-- Database: `webdevbackend`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `contact`
-- 

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `first` varchar(25) NOT NULL,
  `last` varchar(25) NOT NULL,
  `email` varchar(32) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `contact`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `contactmeta`
-- 

CREATE TABLE `contactmeta` (
  `contactID` int(11) NOT NULL,
  `name` varchar(25) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY  (`contactID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `contactmeta`
-- 


-- --------------------------------------------------------

-- 
-- Table structure for table `events`
-- 

CREATE TABLE `events` (
  `ID` int(11) NOT NULL auto_increment,
  `projectID` int(11) NOT NULL,
  `descript` varchar(200) NOT NULL,
  `date` date NOT NULL,
  `completed` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`ID`),
  KEY `userID` (`projectID`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;


-- --------------------------------------------------------

-- 
-- Table structure for table `invoices`
-- 

CREATE TABLE `invoices` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `userID` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `projectid` int(11) NOT NULL,
  `due_date` date NOT NULL,
  `cur_date` date NOT NULL,
  `status` varchar(5) NOT NULL default 'ready',
  `service` text NOT NULL,
  `service_cost` int(11) NOT NULL,
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;


-- --------------------------------------------------------

-- 
-- Table structure for table `locations`
-- 

CREATE TABLE `locations` (
  `type` tinyint(4) NOT NULL default '0',
  `location` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `locations`
-- 

INSERT INTO `locations` VALUES (1, 'Tasks,Calendar,Invoices,Clients,Projects,Payment,Account Settings,phpMyAdmin,Logout'
);
INSERT INTO `locations` VALUES (0, 'Projects,Invoices,Requests,Account Settings,Contact,Logout')
;

-- --------------------------------------------------------

-- 
-- Table structure for table `projectmeta`
-- 

CREATE TABLE `projectmeta` (
  `id` int(11) NOT NULL auto_increment,
  `projectID` int(11) NOT NULL,
  `key` varchar(25) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;


-- --------------------------------------------------------

-- 
-- Table structure for table `projects`
-- 

CREATE TABLE `projects` (
  `id` int(11) NOT NULL auto_increment,
  `userID` int(11) NOT NULL,
  `name` varchar(75) NOT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL,
  `domain` varchar(100) NOT NULL default '0',
  `host` varchar(100) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;


-- --------------------------------------------------------

-- 
-- Table structure for table `requests`
-- 

CREATE TABLE `requests` (
  `id` int(11) NOT NULL auto_increment,
  `userID` int(11) NOT NULL,
  `type` varchar(32) NOT NULL,
  `descript` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;


-- --------------------------------------------------------

-- 
-- Table structure for table `service`
-- 

CREATE TABLE `service` (
  `id` int(11) NOT NULL auto_increment,
  `invoiceID` int(11) NOT NULL,
  `service` text NOT NULL,
  `cost` int(11) NOT NULL,
  `quantity` varchar(15) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;


-- --------------------------------------------------------

-- 
-- Table structure for table `users`
-- 

CREATE TABLE `users` (
  `id` int(10) NOT NULL auto_increment,
  `email` varchar(100) NOT NULL,
  `pass` varchar(32) NOT NULL COMMENT 'salt+pass',
  `first` varchar(20) NOT NULL,
  `last` varchar(20) NOT NULL,
  `addr` varchar(60) NOT NULL,
  `zip` varchar(12) NOT NULL,
  `city` varchar(25) NOT NULL,
  `state` varchar(2) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

-- 
-- Dumping data for table `users`
-- 

INSERT INTO `users` VALUES (1, 'test@grooveshark.com', 'ae37c513596ceba4207582cc2b80758e', 'Tester', 's', 'streets', '32607', 'the ville', 'FL', '9541234567', 1
);

-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 16, 2015 at 08:16 AM
-- Server version: 5.5.43-0ubuntu0.14.04.1
-- PHP Version: 5.6.10-1+deb.sury.org~trusty+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `scrapper_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `channels`
--

CREATE TABLE IF NOT EXISTS `channels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_id` varchar(255) NOT NULL,
  `channel_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `fecthed_videos`
--

CREATE TABLE IF NOT EXISTS `fecthed_videos` (
  `title` text NOT NULL,
  `des` longtext NOT NULL,
  `tags` longtext NOT NULL,
  `image` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `channel_id` varchar(255) NOT NULL,
  `is_downloaded` tinyint(1) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `download_link` longtext NOT NULL,
  `is_singular` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


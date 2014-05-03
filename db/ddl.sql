SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `gis`
--
CREATE DATABASE `gis` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `gis`;

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

CREATE TABLE `attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `set_id` int(11) DEFAULT NULL,
  `sequence` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `set_idx` (`set_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `layers`
--
CREATE TABLE `layers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `min_lat_long` point DEFAULT NULL,
  `max_lat_long` point DEFAULT NULL,
  `color` varchar(45) DEFAULT '#FF0000',
  `enabled` tinyint(4) DEFAULT '1',
  `position` int(11) DEFAULT '1',
  `zoom_min` smallint(6) DEFAULT '0',
  `zoom_max` smallint(6) DEFAULT '100',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `linestrings`
--

CREATE TABLE `linestrings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `set_id` int(11) NOT NULL,
  `part` int(11) DEFAULT NULL,
  `lat_long` linestring NOT NULL,
  PRIMARY KEY (`id`),
  SPATIAL KEY `lat_lng` (`lat_long`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `points`
--

CREATE TABLE `points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `set_id` int(11) NOT NULL,
  `lat_long` point NOT NULL,
  PRIMARY KEY (`id`),
  SPATIAL KEY `lat_lng` (`lat_long`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `polygons`
--

CREATE TABLE `polygons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `set_id` int(11) NOT NULL,
  `part` int(11) DEFAULT NULL,
  `lat_long` polygon NOT NULL,
  PRIMARY KEY (`id`),
  KEY `setId` (`set_id`),
  SPATIAL KEY `lat_long` (`lat_long`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sets`
--

CREATE TABLE `sets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `layer` int(11) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `min_lat_long` point DEFAULT NULL,
  `max_lat_long` point DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--
-- MySQL dump 10.10
--
-- Host: localhost    Database: lead_need
-- ------------------------------------------------------
-- Server version	5.0.26-community-nt

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES cp1251 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `connection`
--

DROP TABLE IF EXISTS `connection`;
CREATE TABLE `connection` (
  `id` int(10) unsigned NOT NULL,
  `leader_id` int(10) unsigned NOT NULL default '0',
  `seeker_id` int(10) unsigned NOT NULL default '0',
  `feedback` char(255) default '""',
  `date` datetime default NULL,
  `letter` blob,
  UNIQUE KEY `leader_id` (`leader_id`),
  UNIQUE KEY `seeker_id` (`seeker_id`),
  KEY `date` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Dumping data for table `connection`
--

LOCK TABLES `connection` WRITE;
/*!40000 ALTER TABLE `connection` DISABLE KEYS */;
/*!40000 ALTER TABLE `connection` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demander`
--

DROP TABLE IF EXISTS `demander`;
CREATE TABLE `demander` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `status` tinyint(1) unsigned NOT NULL default '1' COMMENT '0: deleted; 1: not confirmed; 2: active.',
  `gname` char(40) character set cp1251 collate cp1251_bin NOT NULL default '',
  `lname` char(40) character set cp1251 collate cp1251_bin NOT NULL default '''''',
  `address` char(100) character set cp1251 collate cp1251_bin NOT NULL default '''''',
  `zip` mediumint(9) NOT NULL default '0',
  `phone` char(15) character set cp1251 default '',
  `email` char(30) character set cp1251 NOT NULL default '',
  `url` char(50) character set cp1251 NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 COLLATE=cp1251_ukrainian_ci;

--
-- Dumping data for table `demander`
--

LOCK TABLES `demander` WRITE;
/*!40000 ALTER TABLE `demander` DISABLE KEYS */;
/*!40000 ALTER TABLE `demander` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demander_skills`
--

DROP TABLE IF EXISTS `demander_skills`;
CREATE TABLE `demander_skills` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `person_id` int(10) unsigned NOT NULL default '0',
  `skill` char(10) character set cp1251 collate cp1251_bin NOT NULL default '""',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;

--
-- Dumping data for table `demander_skills`
--

LOCK TABLES `demander_skills` WRITE;
/*!40000 ALTER TABLE `demander_skills` DISABLE KEYS */;
/*!40000 ALTER TABLE `demander_skills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leader`
--

DROP TABLE IF EXISTS `leader`;
CREATE TABLE `leader` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `status` tinyint(1) unsigned NOT NULL default '1' COMMENT '0: deleted; 1: not confirmed; 2: active.',
  `gname` char(40) character set cp1251 collate cp1251_bin NOT NULL default '',
  `lname` char(40) character set cp1251 collate cp1251_bin NOT NULL default '''''',
  `address` char(100) character set cp1251 collate cp1251_bin NOT NULL default '''''',
  `zip` mediumint(9) NOT NULL default '0',
  `phone` char(15) character set cp1251 NOT NULL default '',
  `email` char(30) character set cp1251 NOT NULL default '',
  `url` char(50) character set cp1251 NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=cp1251 COLLATE=cp1251_ukrainian_ci;

--
-- Dumping data for table `leader`
--

LOCK TABLES `leader` WRITE;
/*!40000 ALTER TABLE `leader` DISABLE KEYS */;
INSERT INTO `leader` VALUES (1,1,'Bob','Smith','',32767,'1298341294','askld@ksjd.com','safs'),(2,1,'sdlkfj','akjsdlk','',99999,'laskdjas','lsafj','ksafjsl'),(3,1,'laskfj','sdkfjsdl','',0,'','','');
/*!40000 ALTER TABLE `leader` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leader_skills`
--

DROP TABLE IF EXISTS `leader_skills`;
CREATE TABLE `leader_skills` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `person_id` int(10) unsigned NOT NULL default '0',
  `skill` char(10) character set cp1251 collate cp1251_bin NOT NULL default '""',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=cp1251;

--
-- Dumping data for table `leader_skills`
--

LOCK TABLES `leader_skills` WRITE;
/*!40000 ALTER TABLE `leader_skills` DISABLE KEYS */;
INSERT INTO `leader_skills` VALUES (1,1,'.Net'),(2,1,'Cobol'),(3,1,'HTML'),(4,1,'Java'),(5,1,'Perl'),(6,2,'_Net'),(7,2,'AJAX'),(8,2,'C++'),(9,2,'Cobol'),(10,2,'CSS'),(11,2,'Flash'),(12,2,'Fortran'),(13,2,'Pascal'),(14,2,'Perl'),(15,3,'Ada'),(16,3,'AJAX'),(17,3,'C#'),(18,3,'C++'),(19,3,'CSS'),(20,3,'Drupal'),(21,3,'Flex'),(22,3,'Fortran'),(23,3,'Java'),(24,3,'Pascal'),(25,3,'PHP');
/*!40000 ALTER TABLE `leader_skills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `skills`
--

DROP TABLE IF EXISTS `skills`;
CREATE TABLE `skills` (
  `id` tinyint(3) unsigned NOT NULL auto_increment,
  `skill` char(20) collate cp1251_ukrainian_ci NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `skill` (`skill`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 COLLATE=cp1251_ukrainian_ci;

--
-- Dumping data for table `skills`
--

LOCK TABLES `skills` WRITE;
/*!40000 ALTER TABLE `skills` DISABLE KEYS */;
INSERT INTO `skills` VALUES (19,'.Net'),(18,'Ada'),(2,'AJAX'),(14,'ASP'),(5,'C#'),(4,'C++'),(16,'Cobol'),(3,'CSS'),(21,'Drupal'),(11,'Flash'),(13,'Flex'),(17,'Fortran'),(12,'HTML'),(6,'Java'),(20,'javaScript'),(10,'jQuery'),(15,'Pascal'),(8,'Perl'),(9,'Photoshop'),(1,'PHP'),(7,'Ruby');
/*!40000 ALTER TABLE `skills` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2009-02-05  5:12:11

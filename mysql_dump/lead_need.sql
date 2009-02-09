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
-- Table structure for table `person`
--

DROP TABLE IF EXISTS `person`;
CREATE TABLE `person` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `status` tinyint(1) unsigned NOT NULL default '1' COMMENT '0: disabled; 1: active.',
  `ptype` tinyint(1) unsigned NOT NULL default '2' COMMENT '1: leader; 2: demander.',
  `created` datetime NOT NULL,
  `gname` char(40) character set cp1251 collate cp1251_bin NOT NULL default '',
  `lname` char(40) character set cp1251 collate cp1251_bin NOT NULL default '''''',
  `address` char(100) character set cp1251 collate cp1251_bin NOT NULL default '''''',
  `zip` char(5) collate cp1251_ukrainian_ci NOT NULL default '',
  `phone` char(15) character set cp1251 NOT NULL default '',
  `email` char(30) character set cp1251 NOT NULL default '',
  `url` char(50) character set cp1251 NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `created` (`created`),
  KEY `status` (`status`),
  KEY `ptype` (`ptype`)
) ENGINE=MyISAM AUTO_INCREMENT=143 DEFAULT CHARSET=cp1251 COLLATE=cp1251_ukrainian_ci;

--
-- Dumping data for table `person`
--

LOCK TABLES `person` WRITE;
/*!40000 ALTER TABLE `person` DISABLE KEYS */;
/*!40000 ALTER TABLE `person` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `person_skills`
--

DROP TABLE IF EXISTS `person_skills`;
CREATE TABLE `person_skills` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `status` tinyint(1) unsigned NOT NULL default '1',
  `person_id` int(10) unsigned NOT NULL default '0',
  `skill` char(10) character set cp1251 collate cp1251_bin NOT NULL default '""',
  PRIMARY KEY  (`id`),
  KEY `person` (`person_id`),
  KEY `skill` (`skill`)
) ENGINE=MyISAM AUTO_INCREMENT=916 DEFAULT CHARSET=cp1251;

--
-- Dumping data for table `person_skills`
--

LOCK TABLES `person_skills` WRITE;
/*!40000 ALTER TABLE `person_skills` DISABLE KEYS */;
/*!40000 ALTER TABLE `person_skills` ENABLE KEYS */;
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
INSERT INTO `skills` VALUES (18,'Ada'),(2,'AJAX'),(14,'ASP'),(5,'C#'),(4,'C++'),(16,'Cobol'),(3,'CSS'),(21,'Drupal'),(11,'Flash'),(13,'Flex'),(17,'Fortran'),(12,'HTML'),(6,'Java'),(20,'javaScript'),(10,'jQuery'),(15,'Pascal'),(8,'Perl'),(9,'Photoshop'),(1,'PHP'),(7,'Ruby'),(19,'_Net');
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

-- Dump completed on 2009-02-09  5:04:47

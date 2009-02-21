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
-- Table structure for table `connections`
--

DROP TABLE IF EXISTS `connections`;
CREATE TABLE `connections` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `status` tinyint(1) unsigned NOT NULL default '1',
  `leader_id` int(10) unsigned NOT NULL default '0',
  `seeker_id` int(10) unsigned NOT NULL default '0',
  `intro_date` datetime default NULL,
  `letter_date` datetime default NULL,
  `letter_subj` char(45) default '""',
  `letter` text,
  `letter_stat` tinyint(1) unsigned NOT NULL default '3' COMMENT '3-no letter;2-saved;1-successfuly sent;0-sending error.',
  `feedback` char(255) default '""',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `pare` (`leader_id`,`seeker_id`),
  KEY `date` (`letter_date`),
  KEY `leader` (`leader_id`),
  KEY `seeker` (`seeker_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=cp1251;

--
-- Dumping data for table `connections`
--

LOCK TABLES `connections` WRITE;
/*!40000 ALTER TABLE `connections` DISABLE KEYS */;
INSERT INTO `connections` VALUES (2,1,145,147,'2009-02-19 18:46:50','2009-02-19 21:39:23','Introduction letter from Brian Russell','ks.djfh kjd dkjjd dh dj sdf \nd f djd df34 dh 234 dfd',0,'\"\"'),(3,1,144,148,'2009-02-19 18:46:50','2009-02-19 21:39:32','Introduction letter from Brian Russell','key ekrthejr',1,'\"\"'),(4,1,145,148,'2009-02-19 18:46:50','2009-02-19 22:35:34','Introduction letter from Brian Russell','jsjsj xc dfd dsfdsfd',2,'\"\"'),(5,1,149,150,'2009-02-19 18:46:50','0000-00-00 00:00:00','Introduction letter from Brian Russell','Hello Bob\na cvxcv sdkjfh ksjdfhskd ',2,'\"\"'),(6,1,143,150,'2009-02-19 18:46:50',NULL,'\"\"',NULL,3,'\"\"'),(7,1,151,150,NULL,NULL,'\"\"',NULL,3,'\"\"'),(8,1,144,150,NULL,NULL,'\"\"',NULL,3,'\"\"');
/*!40000 ALTER TABLE `connections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `filters`
--

DROP TABLE IF EXISTS `filters`;
CREATE TABLE `filters` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `filter_str` varchar(255) collate cp1251_ukrainian_ci NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 COLLATE=cp1251_ukrainian_ci;

--
-- Dumping data for table `filters`
--

LOCK TABLES `filters` WRITE;
/*!40000 ALTER TABLE `filters` DISABLE KEYS */;
INSERT INTO `filters` VALUES (1,'sk=>(1,2,3);'),(2,'sk=>(2,4,6);'),(3,'sk=>(Ada,PHP,CSS,javascript);');
/*!40000 ALTER TABLE `filters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glsettings`
--

DROP TABLE IF EXISTS `glsettings`;
CREATE TABLE `glsettings` (
  `id` tinyint(1) unsigned NOT NULL auto_increment,
  `value` varchar(255) collate cp1251_ukrainian_ci default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 COLLATE=cp1251_ukrainian_ci;

--
-- Dumping data for table `glsettings`
--

LOCK TABLES `glsettings` WRITE;
/*!40000 ALTER TABLE `glsettings` DISABLE KEYS */;
/*!40000 ALTER TABLE `glsettings` ENABLE KEYS */;
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
  `company` char(30) character set cp1251 NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `created` (`created`),
  KEY `status` (`status`),
  KEY `ptype` (`ptype`)
) ENGINE=MyISAM AUTO_INCREMENT=152 DEFAULT CHARSET=cp1251 COLLATE=cp1251_ukrainian_ci;

--
-- Dumping data for table `person`
--

LOCK TABLES `person` WRITE;
/*!40000 ALTER TABLE `person` DISABLE KEYS */;
INSERT INTO `person` VALUES (143,1,1,'2009-02-09 16:20:07','Kim','Yeng','','0','','otrpor@poit.etr','',''),(144,1,1,'2009-02-09 16:20:58','Jim','Smith','sldkjgh','09373','989445489549','proy@rktyr.df','',''),(145,1,1,'2009-02-09 23:05:27','Tom','Bartlen','asfhasklfhakjshf','21874','','qpoewri@poerie.wr','',''),(146,0,1,'2009-02-10 05:25:21','jaekhtwekjh','kajs','kdsjfhskdjf','92348','','skjf@slfkj.sodf','',''),(147,1,2,'2009-02-10 05:51:30','Gerry','King','','0','','kdsjfh@ldkfj.werew','',''),(148,1,2,'2009-02-11 22:17:05','Alex','Young','','0','','lskdfj@lswll.dfd','',''),(149,1,1,'2009-02-13 06:23:21','Bob','Line','','0','','sldkf@lskdfj.sd','',''),(150,1,2,'2009-02-13 06:24:10','Jorge','Krem','','0','','dskj@dlkjf.sd','',''),(151,1,1,'2009-02-20 20:24:16','Tim','McKein','','23525','','tim@dlfk.vom','','');
/*!40000 ALTER TABLE `person` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `person_skills`
--

DROP TABLE IF EXISTS `person_skills`;
CREATE TABLE `person_skills` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `status` tinyint(1) unsigned NOT NULL default '1',
  `ptype` tinyint(1) unsigned NOT NULL default '1',
  `person_id` int(10) unsigned NOT NULL default '0',
  `skill` char(10) character set cp1251 collate cp1251_bin NOT NULL default '""',
  PRIMARY KEY  (`id`),
  KEY `person` (`person_id`),
  KEY `type` (`ptype`),
  FULLTEXT KEY `skill` (`skill`)
) ENGINE=MyISAM AUTO_INCREMENT=1113 DEFAULT CHARSET=cp1251;

--
-- Dumping data for table `person_skills`
--

LOCK TABLES `person_skills` WRITE;
/*!40000 ALTER TABLE `person_skills` DISABLE KEYS */;
INSERT INTO `person_skills` VALUES (1053,1,1,143,'Ruby'),(1052,1,1,143,'Perl'),(1108,1,1,151,'AJAX'),(1107,1,1,144,'Ruby'),(1106,1,1,144,'Fortran'),(1081,1,1,149,'Perl'),(1080,1,1,145,'javaScript'),(1079,1,1,145,'Fortran'),(1078,1,1,145,'Drupal'),(1077,1,1,145,'AJAX'),(1105,1,1,144,'Drupal'),(1104,1,1,144,'CSS'),(1035,0,1,146,'PHP'),(1034,0,1,146,'Pascal'),(1033,0,1,146,'Java'),(1032,0,1,146,'Flex'),(1068,0,2,147,'Ada'),(1031,0,1,146,'CSS'),(1030,0,1,146,'C#'),(1029,0,1,146,'Ada'),(1067,1,2,148,'PHP'),(1066,1,2,148,'Pascal'),(1065,1,2,148,'javaScript'),(1064,1,2,148,'Java'),(1063,1,2,148,'Flex'),(1062,1,2,148,'CSS'),(1069,0,2,147,'Cobol'),(1076,1,1,145,'Ada'),(1082,1,1,149,'PHP'),(1083,1,1,149,'Ruby'),(1089,1,2,150,'HTML'),(1088,1,2,150,'CSS'),(1090,1,2,150,'Perl'),(1091,1,2,150,'PHP'),(1092,1,2,150,'Ruby'),(1103,1,1,144,'AJAX'),(1109,1,1,151,'C__'),(1110,1,1,151,'Drupal'),(1111,1,1,151,'javaScript'),(1112,1,1,151,'Perl');
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

-- Dump completed on 2009-02-21  5:46:53

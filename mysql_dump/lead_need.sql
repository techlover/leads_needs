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
  `letter_date` datetime default NULL,
  `letter_subj` char(45) default '""',
  `letter` text,
  `letter_stat` tinyint(1) unsigned NOT NULL default '3' COMMENT '3-sending error;2-successfuly sent;1-saved as draft;0-no letter.',
  `l_feed` tinyint(1) unsigned NOT NULL default '0' COMMENT '7-error sending feedback letter;6-stop bugging;5-extedned info;4-bad feedback;3-not work yet;2-good feedback;1-no feedback;0-draft',
  `n_feed` tinyint(1) unsigned NOT NULL default '0' COMMENT '7-error sending feedback letter;6-stop bugging;5-extedned info;4-bad feedback;3-not work yet;2-good feedback;1-no feedback;0-draft',
  `l_feed_date` date default NULL,
  `n_feed_date` date default NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `pare` (`leader_id`,`seeker_id`),
  KEY `date` (`letter_date`),
  KEY `leader` (`leader_id`),
  KEY `seeker` (`seeker_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=cp1251;

--
-- Dumping data for table `connections`
--

LOCK TABLES `connections` WRITE;
/*!40000 ALTER TABLE `connections` DISABLE KEYS */;
INSERT INTO `connections` VALUES (2,1,145,147,'2009-02-22 00:09:36','Introduction letter from Brian Russell','ks.djfh kjd dkjjd dh dj sdf \nd f djd df34 dh 234 dfd',2,1,2,NULL,NULL),(3,1,144,148,'2009-02-19 21:39:32','Introduction letter from Brian Russell','key ekrthejr',1,0,1,NULL,NULL),(4,1,145,148,'2009-02-19 22:35:34','Introduction letter from Brian Russell','jsjsj xc dfd dsfdsfd',2,2,0,NULL,NULL),(5,1,149,150,'0000-00-00 00:00:00','Introduction letter from Brian Russell','Hello Bob\na cvxcv sdkjfh ksjdfhskd ',2,3,0,NULL,NULL),(6,1,143,150,NULL,'\"\"',NULL,3,4,0,NULL,NULL),(7,1,151,150,NULL,'\"\"',NULL,3,5,4,NULL,NULL),(8,1,144,150,'2009-04-01 18:06:34','sldkg','asdfasfsa',1,6,5,NULL,NULL),(9,0,146,147,NULL,'\"\"',NULL,3,7,8,NULL,NULL),(10,1,152,148,'2009-03-30 16:28:19','Introduction letter from Brian Russell','adskjfh kasdj fhasdkljfhlakdsjfh alsdkjfh ',3,0,9,NULL,NULL),(11,1,151,148,NULL,'\"\"',NULL,3,0,0,NULL,NULL),(12,1,151,147,NULL,'\"\"',NULL,3,0,3,NULL,NULL),(13,1,152,147,'2009-04-01 18:06:24','dgdssdgdsg x',' oijtrqwer02394234 32l wk23094 32\'23 4;23[p\" 3\"32 432\"3 213 4/ \\ \\3 ;ldsk dsa aw kljrhaslkjfh asldjkfh aldskjfhsl adkjfha\'\\\'lsdf  ds\"a f\\n \n\\adsf  asdf asdf s adf  asdf as dfasas\nsfsdf ds asjgh jsd h\nffasdf as asdasd a\nsd as d as \nasd\nas\nd \na \n235v325 3425 345 34534 534\nbye:)',1,2,1,'2009-03-31',NULL),(14,0,155,148,NULL,'\"\"',NULL,3,0,0,NULL,NULL),(15,1,156,148,NULL,'\"\"',NULL,3,0,0,NULL,NULL);
/*!40000 ALTER TABLE `connections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `filters`
--

DROP TABLE IF EXISTS `filters`;
CREATE TABLE `filters` (
  `id` tinyint(1) unsigned NOT NULL auto_increment COMMENT '1-lead;2-need;3-connections;4-lead order;5-need order;6-connections order.',
  `active` tinyint(1) unsigned NOT NULL default '0',
  `fvalue` char(255) collate cp1251_ukrainian_ci NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 COLLATE=cp1251_ukrainian_ci;

--
-- Dumping data for table `filters`
--

LOCK TABLES `filters` WRITE;
/*!40000 ALTER TABLE `filters` DISABLE KEYS */;
INSERT INTO `filters` VALUES (1,0,'lnm;con;joi;sk=>o#0#0#(12,7)'),(2,0,'con;joi;sk=>0#0#(12,6,8)'),(3,0,'lnm;con;joi;sk=>tom#0#0#()'),(4,1,'order by name;0'),(5,1,'order by name;0'),(6,1,'order by leader;0');
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
-- Table structure for table `letter_templates`
--

DROP TABLE IF EXISTS `letter_templates`;
CREATE TABLE `letter_templates` (
  `id` tinyint(2) unsigned NOT NULL auto_increment,
  `name` char(20) collate cp1251_ukrainian_ci default NULL,
  `from_email` char(30) collate cp1251_ukrainian_ci default 'brian@carrborocoworking.com',
  `from_name` char(30) collate cp1251_ukrainian_ci default 'Brian Russell - CCC',
  `subject` char(20) collate cp1251_ukrainian_ci default NULL,
  `text` text collate cp1251_ukrainian_ci,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251 COLLATE=cp1251_ukrainian_ci;

--
-- Dumping data for table `letter_templates`
--

LOCK TABLES `letter_templates` WRITE;
/*!40000 ALTER TABLE `letter_templates` DISABLE KEYS */;
INSERT INTO `letter_templates` VALUES (1,'intro','brian@carrborocoworking.com','Brian Russell - CCC','\"\"',NULL),(2,'feedback','brian@carrborocoworking.com','Brian Russell - CCC','Dear person','<html><body><p>Dear $person_name1,</p><p>Would you like to give few seconds for sending feedback about your connection with $person_name2?<br>Consider following options:</p><p><a href=\'$link_good\' target=\'_blank\'>I had a great experience with $person_name2</a></p><p><a href=\'$link_pend\'>We haven\'t start working yet</a></p><p><a href=\'$link_bad\'>No, our collaboration did not trurned out</a></p><br><p>If you would like to describe your collaboration a bit more, click <a href=\'$link_more\'>here</a>.</p><br><p>Or no disturbing any more <a href=\'$link_stop\'>stop bugging me</a>.</p></body></html>');
/*!40000 ALTER TABLE `letter_templates` ENABLE KEYS */;
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
) ENGINE=MyISAM AUTO_INCREMENT=157 DEFAULT CHARSET=cp1251 COLLATE=cp1251_ukrainian_ci;

--
-- Dumping data for table `person`
--

LOCK TABLES `person` WRITE;
/*!40000 ALTER TABLE `person` DISABLE KEYS */;
INSERT INTO `person` VALUES (143,1,1,'2009-02-09 16:20:07','Kim','Yeng','','0','','otrpor@poit.etr','',''),(144,1,1,'2009-02-09 16:20:58','Jim','Smith','sldkjgh','09373','989445489549','proy@rktyr.df','',''),(145,1,1,'2009-02-09 23:05:27','Tom','Bartlen','asfhasklfhakjshf','21874','','qpoewri@poerie.wr','',''),(146,0,1,'2009-02-10 05:25:21','jaekhtwekjh','kajs','kdsjfhskdjf','92348','','skjf@slfkj.sodf','',''),(147,1,2,'2009-02-10 05:51:30','Gerry','King','','0','','kdsjfh@ldkfj.werew','',''),(148,1,2,'2009-02-11 22:17:05','Alex','Young','','0','','lskdfj@lswll.dfd','',''),(149,1,1,'2009-02-13 06:23:21','Bob','Line','','0','','sldkf@lskdfj.sd','',''),(150,1,2,'2009-02-13 06:24:10','Jorge','Krem','','0','','dskj@dlkjf.sd','',''),(151,1,1,'2009-02-20 20:24:16','Tim','McKein','','23525','','tim@dlfk.vom','',''),(152,1,1,'2009-02-21 23:52:33','Alan','Gibs','','0','','alan@yahoo.com','',''),(153,1,1,'2009-02-21 23:56:19','Michel','Drigs','','0','','mdrt.dfkj@dsmfn.net','',''),(154,0,1,'2009-03-01 02:18:52','Ben','Motzart','','0','','sdkjgh@kf.sf.sds','',''),(155,0,1,'2009-03-02 21:13:29','Fdkl','kdfg','','0','','ldlsls@dkfj.com','',''),(156,1,1,'2009-03-02 23:37:31','William','Roberts','','0','','Will@jhfd.com','','');
/*!40000 ALTER TABLE `person` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `person_skills`
--

DROP TABLE IF EXISTS `person_skills`;
CREATE TABLE `person_skills` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `status` tinyint(1) unsigned NOT NULL default '1',
  `ptype` tinyint(1) unsigned NOT NULL default '1',
  `person_id` int(10) unsigned NOT NULL default '0',
  `skill_id` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `person` (`person_id`),
  KEY `type` (`ptype`)
) ENGINE=MyISAM AUTO_INCREMENT=1235 DEFAULT CHARSET=cp1251;

--
-- Dumping data for table `person_skills`
--

LOCK TABLES `person_skills` WRITE;
/*!40000 ALTER TABLE `person_skills` DISABLE KEYS */;
INSERT INTO `person_skills` VALUES (1107,1,1,144,7),(1106,1,1,144,17),(1169,1,1,151,1),(1168,1,1,151,8),(1167,1,1,151,6),(1166,1,1,151,13),(1165,1,1,151,21),(1105,1,1,144,21),(1104,1,1,144,3),(1035,0,1,146,1),(1034,0,1,146,15),(1033,0,1,146,6),(1032,0,1,146,13),(1068,1,2,147,18),(1031,0,1,146,3),(1030,0,1,146,5),(1029,0,1,146,18),(1067,1,2,148,1),(1066,1,2,148,15),(1065,1,2,148,20),(1064,1,2,148,6),(1063,1,2,148,13),(1062,1,2,148,3),(1069,1,2,147,16),(1164,1,1,151,4),(1163,1,1,151,5),(1162,1,1,151,2),(1089,1,2,150,12),(1088,1,2,150,3),(1090,1,2,150,8),(1091,1,2,150,1),(1092,1,2,150,7),(1103,1,1,144,2),(1205,1,1,145,3),(1204,1,1,145,14),(1170,1,1,151,7),(1161,1,1,151,18),(1203,1,1,145,18),(1202,1,1,145,19),(1179,1,1,143,3),(1180,1,1,143,8),(1181,1,1,143,1),(1182,1,1,143,7),(1190,1,1,149,8),(1189,1,1,149,20),(1188,1,1,149,12),(1187,1,1,149,14),(1191,1,1,152,14),(1192,1,1,152,16),(1193,1,1,152,3),(1194,1,1,152,11),(1195,1,1,152,13),(1196,1,1,152,12),(1197,1,1,152,6),(1198,1,1,153,18),(1199,1,1,153,5),(1200,1,1,153,4),(1201,1,1,153,3),(1206,1,1,145,1),(1224,0,1,154,12),(1223,0,1,154,3),(1222,0,1,154,4),(1221,0,1,154,5),(1220,0,1,154,19),(1234,0,1,155,13),(1226,1,1,156,2),(1227,1,1,156,3),(1228,1,1,156,21),(1229,1,1,156,12),(1230,1,1,156,20),(1231,1,1,156,1),(1232,1,1,156,7),(1233,1,1,156,23);
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
INSERT INTO `skills` VALUES (19,'.Net'),(18,'Ada'),(2,'AJAX'),(14,'ASP'),(5,'C#'),(4,'C++'),(16,'Cobol'),(3,'CSS'),(21,'Drupal'),(11,'Flash'),(13,'Flex'),(17,'Fortran'),(12,'HTML'),(6,'Java'),(20,'javaScript'),(10,'jQuery'),(15,'Pascal'),(8,'Perl'),(9,'Photoshop'),(1,'PHP'),(7,'Ruby'),(22,'wet'),(23,'WordPress');
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

-- Dump completed on 2009-04-03  4:09:02

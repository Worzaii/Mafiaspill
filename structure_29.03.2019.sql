-- MySQL dump 10.13  Distrib 5.7.25, for Linux (x86_64)
--
-- Host: localhost    Database: mafia
-- ------------------------------------------------------
-- Server version	5.7.25-0ubuntu0.18.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `mafia`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `mafia` /*!40100 DEFAULT CHARACTER SET latin1 */;
CREATE USER 'mafia'@'localhost' IDENTIFIED BY 'mafia';
GRANT ALL ON mafia.* TO 'mafia'@'localhost';
FLUSH PRIVILEGES;
USE `mafia`;
--
-- Table structure for table `billogg`
--

DROP TABLE IF EXISTS `billogg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `billogg` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL,
  `bvalg` int(1) DEFAULT NULL,
  `time` bigint(255) NOT NULL,
  `date` varchar(40) NOT NULL DEFAULT 'non given',
  `resu` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `billogg`
--

LOCK TABLES `billogg` WRITE;
/*!40000 ALTER TABLE `billogg` DISABLE KEYS */;
/*!40000 ALTER TABLE `billogg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bunkerinv`
--

DROP TABLE IF EXISTS `bunkerinv`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bunkerinv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `timestamp` bigint(20) NOT NULL,
  `accepted` enum('0','1') COLLATE latin1_danish_ci NOT NULL,
  `timeleft` bigint(20) DEFAULT NULL,
  `length` bigint(20) NOT NULL,
  `used` enum('0','1') COLLATE latin1_danish_ci NOT NULL DEFAULT '0',
  `declined` enum('0','1') COLLATE latin1_danish_ci NOT NULL DEFAULT '0',
  `gone` enum('0','1') COLLATE latin1_danish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bunkerinv`
--

LOCK TABLES `bunkerinv` WRITE;
/*!40000 ALTER TABLE `bunkerinv` DISABLE KEYS */;
/*!40000 ALTER TABLE `bunkerinv` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cars`
--

DROP TABLE IF EXISTS `cars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `choice` varchar(255) NOT NULL,
  `levelmin` int(2) NOT NULL DEFAULT '1',
  `minval` bigint(20) NOT NULL DEFAULT '0',
  `maxval` bigint(20) NOT NULL DEFAULT '0',
  `bilmin` tinyint(1) NOT NULL DEFAULT '0',
  `bilmax` tinyint(1) NOT NULL DEFAULT '0',
  `timewait` bigint(20) NOT NULL DEFAULT '60',
  `expgain` tinyint(1) NOT NULL,
  `punishtime` bigint(20) NOT NULL DEFAULT '60',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cars`
--

LOCK TABLES `cars` WRITE;
/*!40000 ALTER TABLE `cars` DISABLE KEYS */;
INSERT INTO `cars` VALUES (1,'Rapp en bil',1,10,100,1,2,3,1,15);
/*!40000 ALTER TABLE `cars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carslog`
--

DROP TABLE IF EXISTS `carslog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `carslog` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL,
  `choice` int(1) DEFAULT NULL,
  `timestamp` bigint(255) NOT NULL,
  `result` enum('0','1') NOT NULL DEFAULT '0',
  `timewait` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carslog`
--

LOCK TABLES `carslog` WRITE;
/*!40000 ALTER TABLE `carslog` DISABLE KEYS */;

/*!40000 ALTER TABLE `carslog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chance`
--

DROP TABLE IF EXISTS `chance`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `option` int(11) NOT NULL,
  `chance` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chance`
--

LOCK TABLES `chance` WRITE;
/*!40000 ALTER TABLE `chance` DISABLE KEYS */;
/*!40000 ALTER TABLE `chance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat`
--

DROP TABLE IF EXISTS `chat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL,
  `message` varchar(255) NOT NULL,
  `timestamp` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat`
--

LOCK TABLES `chat` WRITE;
/*!40000 ALTER TABLE `chat` DISABLE KEYS */;

/*!40000 ALTER TABLE `chat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forumban`
--

DROP TABLE IF EXISTS `forumban`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forumban` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `active` enum('1','0') NOT NULL DEFAULT '1',
  `timestamp` bigint(20) NOT NULL,
  `bantime` bigint(20) NOT NULL,
  `banner` int(11) NOT NULL,
  `reason` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forumban`
--

LOCK TABLES `forumban` WRITE;
/*!40000 ALTER TABLE `forumban` DISABLE KEYS */;
/*!40000 ALTER TABLE `forumban` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `garage`
--

DROP TABLE IF EXISTS `garage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `garage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `car_id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `stolen_city` int(11) NOT NULL,
  `transferred` enum('0','1') NOT NULL DEFAULT '0',
  `current_city` int(11) NOT NULL,
  `sold` enum('0','1') NOT NULL DEFAULT '0',
  `timestamp` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `garage`
--

LOCK TABLES `garage` WRITE;
/*!40000 ALTER TABLE `garage` DISABLE KEYS */;
/*!40000 ALTER TABLE `garage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invsjekk`
--

DROP TABLE IF EXISTS `invsjekk`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `invsjekk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(255) COLLATE latin1_danish_ci NOT NULL,
  `code` varchar(255) COLLATE latin1_danish_ci NOT NULL,
  `ip` varchar(100) COLLATE latin1_danish_ci NOT NULL,
  `time` bigint(20) NOT NULL,
  `used` enum('0','1') COLLATE latin1_danish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invsjekk`
--

LOCK TABLES `invsjekk` WRITE;
/*!40000 ALTER TABLE `invsjekk` DISABLE KEYS */;

/*!40000 ALTER TABLE `invsjekk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ipban`
--

DROP TABLE IF EXISTS `ipban`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ipban` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` mediumint(9) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `timestamp` bigint(20) NOT NULL,
  `reason` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip_UNIQUE` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ipban`
--

LOCK TABLES `ipban` WRITE;
/*!40000 ALTER TABLE `ipban` DISABLE KEYS */;
/*!40000 ALTER TABLE `ipban` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jail`
--

DROP TABLE IF EXISTS `jail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `reason` varchar(100) NOT NULL,
  `timestamp` bigint(20) NOT NULL,
  `timeleft` bigint(20) NOT NULL,
  `priceout` bigint(20) NOT NULL DEFAULT '0',
  `breaker` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jail`
--

LOCK TABLES `jail` WRITE;
/*!40000 ALTER TABLE `jail` DISABLE KEYS */;

/*!40000 ALTER TABLE `jail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `krim`
--

DROP TABLE IF EXISTS `krim`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `krim` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `description` text,
  `minval` bigint(20) NOT NULL DEFAULT '1',
  `maxval` bigint(20) NOT NULL DEFAULT '10',
  `expgain` float(5,2) NOT NULL DEFAULT '0.00',
  `untilnext` bigint(20) NOT NULL DEFAULT '10',
  `punishtime` bigint(20) NOT NULL DEFAULT '60',
  `levelmin` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `krim`
--

LOCK TABLES `krim` WRITE;
/*!40000 ALTER TABLE `krim` DISABLE KEYS */;
INSERT INTO `krim` VALUES (7,'Seventh',50000,100000,1.25,150,120,7),(2,'Second',15,30,0.45,25,15,2),(3,'Third',150,500,0.67,45,30,3),(4,'Forth',750,1200,0.85,60,45,4),(5,'Fifth',5000,8000,0.98,80,60,5),(1,'First',1,10,0.35,10,5,1),(6,'Sixth',10000,25000,1.05,105,80,6);
/*!40000 ALTER TABLE `krim` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `krimlogg`
--

DROP TABLE IF EXISTS `krimlogg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `krimlogg` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL,
  `timestamp` bigint(255) NOT NULL,
  `crime` tinyint(2) NOT NULL DEFAULT '0',
  `result` bigint(20) NOT NULL DEFAULT '0',
  `timewait` varchar(45) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `krimlogg`
--

LOCK TABLES `krimlogg` WRITE;
/*!40000 ALTER TABLE `krimlogg` DISABLE KEYS */;

/*!40000 ALTER TABLE `krimlogg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(60) NOT NULL,
  `text` text NOT NULL,
  `author` varchar(15) NOT NULL,
  `userlevel` enum('1','2','3','4','5') NOT NULL DEFAULT '5',
  `timestamp` bigint(20) NOT NULL,
  `showing` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES (1,'Hello','This is a test!','1','5',1,1);
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rob_log`
--

DROP TABLE IF EXISTS `rob_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rob_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `ucity` int(11) NOT NULL,
  `tcity` int(11) NOT NULL,
  `result` tinyint(1) NOT NULL,
  `robbed_amount` bigint(20) NOT NULL,
  `timestamp` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rob_log`
--

LOCK TABLES `rob_log` WRITE;
/*!40000 ALTER TABLE `rob_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `rob_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `support`
--

DROP TABLE IF EXISTS `support`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `support` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL,
  `topic` varchar(20) NOT NULL,
  `issue_type` enum('0','1','2','3') NOT NULL DEFAULT '1',
  `text` mediumtext NOT NULL,
  `timestamp` bigint(20) NOT NULL,
  `handled` enum('0','1') NOT NULL DEFAULT '0',
  `handler` bigint(20) DEFAULT NULL,
  `handler_reply` mediumtext,
  `removed` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `support`
--

LOCK TABLES `support` WRITE;
/*!40000 ALTER TABLE `support` DISABLE KEYS */;
/*!40000 ALTER TABLE `support` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(20) NOT NULL,
  `named` varchar(60) DEFAULT NULL,
  `pass` varchar(32) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT '/imgs/nopic.png' COMMENT 'Avatar',
  `profile` text,
  `family` int(2) DEFAULT NULL,
  `code` varchar(12) DEFAULT NULL,
  `bank` bigint(25) NOT NULL DEFAULT '0' COMMENT 'Penger spiller har i banken',
  `hand` bigint(25) NOT NULL DEFAULT '0' COMMENT 'Penger pГѓВҐ handa(ute)',
  `city` enum('1','2','3','4','5','6','7','8') NOT NULL DEFAULT '1',
  `weapon` enum('0','1','2','3','4','5','6','7','8','9') NOT NULL DEFAULT '0',
  `bullets` int(11) NOT NULL DEFAULT '0',
  `health` int(3) NOT NULL DEFAULT '100',
  `points` bigint(20) NOT NULL DEFAULT '0',
  `exp` decimal(10,3) NOT NULL DEFAULT '0.000',
  `status` enum('1','2','3','4','5','6','7') NOT NULL DEFAULT '5',
  `support` enum('0','1') NOT NULL DEFAULT '0',
  `moddet` enum('0','1') NOT NULL DEFAULT '0',
  `modgrunn` text,
  `modav` varchar(25) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `regip` varchar(20) DEFAULT NULL,
  `reghostname` varchar(255) DEFAULT NULL,
  `hostname` varchar(255) DEFAULT NULL,
  `lastactive` bigint(20) DEFAULT NULL,
  `forceout` enum('0','1') NOT NULL DEFAULT '0',
  `lastdato` int(10) DEFAULT NULL,
  `regdato` int(10) NOT NULL,
  `Extrainfo` longtext COMMENT 'Info pГѓВҐ spiller notert av Ledelsen',
  `note` longtext,
  `vervetav` int(11) DEFAULT NULL,
  `kodehem` varchar(200) DEFAULT NULL,
  `airportwait` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`),
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-03-29 16:13:39

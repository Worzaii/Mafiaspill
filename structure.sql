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
-- Table structure for table `chat`
--

DROP TABLE IF EXISTS `chat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL,
  `mld` varchar(255) NOT NULL,
  `date` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat`
--

LOCK TABLES `chat` WRITE;
/*!40000 ALTER TABLE `chat` DISABLE KEYS */;
/*!40000 ALTER TABLE `chat` ENABLE KEYS */;
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
  `uid` int(11) NOT NULL DEFAULT '0',
  `reason` varchar(100) NOT NULL DEFAULT 'Gjorde noe ulovlig!',
  `time` bigint(20) NOT NULL,
  `timeleft` bigint(20) NOT NULL,
  `prisut` bigint(20) NOT NULL DEFAULT '1000000',
  `breaker` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jail`
--

LOCK TABLES `jail` WRITE;
/*!40000 ALTER TABLE `jail` DISABLE KEYS */;
/*!40000 ALTER TABLE `jail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `krimlogg`
--

DROP TABLE IF EXISTS `krimlogg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `krimlogg` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `usid` bigint(20) NOT NULL,
  `time` bigint(255) NOT NULL,
  `valid` tinyint(2) NOT NULL DEFAULT '0',
  `resu` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
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
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
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
  `hand` bigint(25) NOT NULL DEFAULT '0' COMMENT 'Penger p???? handa(ute)',
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
  `Extrainfo` longtext COMMENT 'Info p???? spiller notert av Ledelsen',
  `note` longtext,
  `vervetav` int(11) DEFAULT NULL,
  `kodehem` varchar(200) DEFAULT NULL,
  `airportwait` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`),
  KEY `kuler` (`bullets`),
  KEY `poeng` (`points`),
  KEY `Liv` (`health`),
  KEY `Stilling` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
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

-- Dump completed on 2019-03-24 16:56:38

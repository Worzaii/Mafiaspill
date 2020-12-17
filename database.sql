-- MySQL dump 10.13  Distrib 8.0.22, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: mafia
-- ------------------------------------------------------
-- Server version	8.0.22
-- Todo: Introduce foreign key checking!
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `mafia`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `mafia` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `mafia`;

--
-- Table structure for table `bank_transfer`
--

DROP TABLE IF EXISTS `bank_transfer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bank_transfer` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL,
  `tid` int NOT NULL,
  `timestamp` int NOT NULL,
  `sum` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bank_transfer`
--

LOCK TABLES `bank_transfer` WRITE;
/*!40000 ALTER TABLE `bank_transfer` DISABLE KEYS */;
/*!40000 ALTER TABLE `bank_transfer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banklogg`
--

DROP TABLE IF EXISTS `banklogg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `banklogg` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `uid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `amount` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `way` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `all` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `timestamp` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banklogg`
--

LOCK TABLES `banklogg` WRITE;
/*!40000 ALTER TABLE `banklogg` DISABLE KEYS */;
/*!40000 ALTER TABLE `banklogg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banlog`
--

DROP TABLE IF EXISTS `banlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `banlog` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL,
  `timestamp` bigint NOT NULL,
  `active` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '1',
  `reason` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `banner` bigint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banlog`
--

LOCK TABLES `banlog` WRITE;
/*!40000 ALTER TABLE `banlog` DISABLE KEYS */;
/*!40000 ALTER TABLE `banlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bjtables`
--

DROP TABLE IF EXISTS `bjtables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bjtables` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` bigint DEFAULT NULL,
  `ucards` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `dcards` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `decks` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `timestamp` bigint DEFAULT NULL,
  `price` bigint DEFAULT NULL,
  `active` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '1',
  `result` bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bjtables`
--

LOCK TABLES `bjtables` WRITE;
/*!40000 ALTER TABLE `bjtables` DISABLE KEYS */;
/*!40000 ALTER TABLE `bjtables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `block`
--

DROP TABLE IF EXISTS `block`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `block` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `uid` bigint NOT NULL,
  `bid` bigint NOT NULL,
  `timestamp` bigint DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `block`
--

LOCK TABLES `block` WRITE;
/*!40000 ALTER TABLE `block` DISABLE KEYS */;
/*!40000 ALTER TABLE `block` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bunkerinv`
--

DROP TABLE IF EXISTS `bunkerinv`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bunkerinv` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL,
  `tid` int NOT NULL,
  `timestamp` bigint NOT NULL,
  `accepted` enum('0','1') CHARACTER SET latin1 COLLATE latin1_danish_ci NOT NULL,
  `timeleft` bigint DEFAULT NULL,
  `length` bigint NOT NULL,
  `used` enum('0','1') CHARACTER SET latin1 COLLATE latin1_danish_ci NOT NULL DEFAULT '0',
  `declined` enum('0','1') CHARACTER SET latin1 COLLATE latin1_danish_ci NOT NULL DEFAULT '0',
  `gone` enum('0','1') CHARACTER SET latin1 COLLATE latin1_danish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cars` (
  `id` int NOT NULL AUTO_INCREMENT,
  `choice` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `levelmin` int NOT NULL DEFAULT '1',
  `minval` bigint NOT NULL DEFAULT '0',
  `maxval` bigint NOT NULL DEFAULT '0',
  `bilmin` enum('1','2','3','4','5','6','7','8','9','10') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '1',
  `bilmax` enum('1','2','3','4','5','6','7','8','9','10') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '1',
  `timewait` bigint NOT NULL DEFAULT '60',
  `expgain` decimal(10,3) NOT NULL DEFAULT '0.000',
  `punishtime` bigint NOT NULL DEFAULT '60',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cars`
--

LOCK TABLES `cars` WRITE;
/*!40000 ALTER TABLE `cars` DISABLE KEYS */;
INSERT INTO `cars` (`id`, `choice`, `levelmin`, `minval`, `maxval`, `bilmin`, `bilmax`, `timewait`, `expgain`, `punishtime`) VALUES (1,'Rapp en bil',1,10,100,'1','2',3,1.000,15),(2,'Rapp en større bil',2,20,200,'2','3',6,2.000,30);
/*!40000 ALTER TABLE `cars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carslog`
--

DROP TABLE IF EXISTS `carslog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carslog` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `uid` bigint NOT NULL,
  `choice` int DEFAULT NULL,
  `timestamp` bigint NOT NULL,
  `result` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `timewait` bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chance` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL,
  `type` int NOT NULL,
  `option` int NOT NULL,
  `chance` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chance`
--

LOCK TABLES `chance` WRITE;
/*!40000 ALTER TABLE `chance` DISABLE KEYS */;
INSERT INTO `chance` (`id`, `uid`, `type`, `option`, `chance`) VALUES (1,1,1,1,58),(2,2,1,1,5),(3,1,1,2,37);
/*!40000 ALTER TABLE `chance` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat`
--

DROP TABLE IF EXISTS `chat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chat` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` bigint NOT NULL,
  `message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `timestamp` bigint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat`
--

LOCK TABLES `chat` WRITE;
/*!40000 ALTER TABLE `chat` DISABLE KEYS */;
INSERT INTO `chat` (`id`, `uid`, `message`, `timestamp`) VALUES (1,1,'test',1592135794),(2,1,'Hmmmja',1594329391),(3,1,'test',1600629497),(4,1,'oof',1600630863),(5,1,'Hei, dette er en test!',1600636654),(6,1,'test',1600636787),(7,1,'How long?',1600636985),(8,1,'hmm',1600786462),(9,1,'test',1600788197),(10,7,'Jaha?',1601241605),(11,1,'Jepp!',1601241615);
/*!40000 ALTER TABLE `chat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crime`
--

DROP TABLE IF EXISTS `crime`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `crime` (
  `id` int NOT NULL AUTO_INCREMENT,
  `description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `minval` bigint NOT NULL DEFAULT '1',
  `maxval` bigint NOT NULL DEFAULT '10',
  `expgain` decimal(10,3) NOT NULL DEFAULT '0.000',
  `untilnext` bigint NOT NULL DEFAULT '10',
  `punishtime` bigint NOT NULL DEFAULT '60',
  `levelmin` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crime`
--

LOCK TABLES `crime` WRITE;
/*!40000 ALTER TABLE `crime` DISABLE KEYS */;
INSERT INTO `crime` (`id`, `description`, `minval`, `maxval`, `expgain`, `untilnext`, `punishtime`, `levelmin`) VALUES (1,'Stjel en lommebok fra en dude',0,500,0.567,1,1,1),(2,'Napp veska til en kjendis',500,2500,0.678,10,5,2),(3,'Ran en kiosk',1000,5000,0.789,45,10,3),(4,'Gå til en dør og be om penger',2500,10000,1.123,60,15,4),(5,'Ran en bankautomat',5000,8000,1.234,80,30,5),(6,'Lur de eldre på internett med BitCoins',10000,25000,2.222,105,40,6),(7,'Snik deg inn på et museum',25000,1000000,4.500,150,60,7);
/*!40000 ALTER TABLE `crime` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `firms`
--

DROP TABLE IF EXISTS `firms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `firms` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL,
  `bank` int NOT NULL DEFAULT '0',
  `city` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `firms`
--

LOCK TABLES `firms` WRITE;
/*!40000 ALTER TABLE `firms` DISABLE KEYS */;
/*!40000 ALTER TABLE `firms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `flight_log`
--

DROP TABLE IF EXISTS `flight_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `flight_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` bigint NOT NULL,
  `timestamp` bigint NOT NULL,
  `from_city` enum('0','1','2','3','4','5','6','7','8') DEFAULT '0',
  `to_city` enum('0','1','2','3','4','5','6','7','8') DEFAULT '0',
  `price` bigint DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `flight_log`
--

LOCK TABLES `flight_log` WRITE;
/*!40000 ALTER TABLE `flight_log` DISABLE KEYS */;
INSERT INTO `flight_log` (`id`, `uid`, `timestamp`, `from_city`, `to_city`, `price`) VALUES (1,1,1586552345,'1','5',10000),(2,1,1586553477,'5','3',10000);
/*!40000 ALTER TABLE `flight_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forumban`
--

DROP TABLE IF EXISTS `forumban`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `forumban` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL,
  `active` enum('1','0') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '1',
  `timestamp` bigint NOT NULL,
  `bantime` bigint NOT NULL,
  `banner` int NOT NULL,
  `reason` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `garage` (
  `id` int NOT NULL AUTO_INCREMENT,
  `car_id` int NOT NULL,
  `uid` int NOT NULL,
  `stolen_city` int NOT NULL,
  `transferred` bigint NOT NULL DEFAULT '0',
  `current_city` int NOT NULL,
  `sold` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `timestamp` bigint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invsjekk` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mail` varchar(255) CHARACTER SET latin1 COLLATE latin1_danish_ci NOT NULL,
  `code` varchar(255) CHARACTER SET latin1 COLLATE latin1_danish_ci NOT NULL,
  `ip` varchar(100) CHARACTER SET latin1 COLLATE latin1_danish_ci NOT NULL,
  `timestamp` bigint NOT NULL,
  `used` enum('0','1') CHARACTER SET latin1 COLLATE latin1_danish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invsjekk`
--

LOCK TABLES `invsjekk` WRITE;
/*!40000 ALTER TABLE `invsjekk` DISABLE KEYS */;
INSERT INTO `invsjekk` (`id`, `mail`, `code`, `ip`, `timestamp`, `used`) VALUES (1,'user@localhost.localdomain','1417295108','127.0.0.1',1583960139,'1');
/*!40000 ALTER TABLE `invsjekk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ipban`
--

DROP TABLE IF EXISTS `ipban`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ipban` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ip` int NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `timestamp` bigint NOT NULL,
  `reason` text CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `banner` bigint NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip_UNIQUE` (`ip`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ipban`
--

LOCK TABLES `ipban` WRITE;
/*!40000 ALTER TABLE `ipban` DISABLE KEYS */;
INSERT INTO `ipban` (`id`, `ip`, `active`, `timestamp`, `reason`, `banner`) VALUES (1,2130706434,0,1586706992,'test',1);
/*!40000 ALTER TABLE `ipban` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jail`
--

DROP TABLE IF EXISTS `jail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jail` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL,
  `reason` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `timestamp` bigint NOT NULL,
  `timeleft` bigint NOT NULL,
  `priceout` bigint NOT NULL DEFAULT '0',
  `breaker` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jail`
--

LOCK TABLES `jail` WRITE;
/*!40000 ALTER TABLE `jail` DISABLE KEYS */;
INSERT INTO `jail` (`id`, `uid`, `reason`, `timestamp`, `timeleft`, `priceout`, `breaker`) VALUES (1,1,'Pr&oslash;vde &aring; v&aelig;re litt kriminiminel.',1583952730,1583952745,2500000,NULL),(2,1,'Pr&oslash;vde &aring; v&aelig;re litt kriminiminel.',1583952769,1583952784,2500000,NULL),(3,1,'Pr&oslash;vde &aring; v&aelig;re litt kriminiminel.',1583952852,1583952867,2500000,NULL),(4,1,'Pr&oslash;vde &aring; v&aelig;re litt kriminiminel.',1583953041,1583953056,2500000,NULL),(5,1,'Pr&oslash;vde &aring; v&aelig;re litt kriminiminel.',1583953204,1583953219,2500000,NULL),(6,1,'Pr&oslash;vde &aring; v&aelig;re litt kriminiminel.',1583953260,1583953275,2500000,NULL),(7,1,'Pr&oslash;vde &aring; v&aelig;re litt kriminiminel.',1583953808,1583953823,2500000,NULL),(8,2,'Pr&oslash;vde &aring; v&aelig;re litt kriminiminel.',1584832305,1584832320,2500000,NULL),(9,2,'Pr&oslash;vde &aring; v&aelig;re litt kriminiminel.',1585345756,1585345771,2500000,NULL),(10,1,'Testing jail',1585348715,1585428715,1000000,2),(11,1,'Pr&oslash;vde &aring; v&aelig;re litt kriminiminel.',1586700780,1586700795,2500000,NULL),(12,1,'Pr&oslash;vde &aring; v&aelig;re litt kriminiminel.',1586702028,1586702043,2500000,NULL),(13,1,'Pr&oslash;vde &aring; v&aelig;re litt kriminiminel.',1604841041,1604841046,2500000,NULL),(14,1,'Testing',1604841041,1604850000,0,NULL),(15,1,'Prøvde å være litt kriminiminel.',1605136762,1605136767,2500000,NULL),(16,1,'Prøvde å være litt kriminiminel.',1605137058,1605137063,2500000,NULL),(17,1,'Prøvde å være litt kriminiminel',1605137253,1605137258,2500000,NULL),(18,1,'Prøvde å være litt kriminiminel',1605137605,1605137610,2500000,NULL),(19,1,'Prøvde å være litt kriminiminel',1605137678,1605137683,2500000,NULL),(20,1,'Prøvde å være litt kriminiminel',1605137708,1605137718,2500000,NULL),(21,1,'Prøvde å være litt kriminiminel',1605137779,1605137784,2500000,NULL),(22,1,'Prøvde å være litt kriminiminel',1605137830,1605137833,2500000,NULL),(23,1,'Prøvde å være litt kriminiminel',1605137991,1605137996,2500000,NULL),(24,1,'Prøvde å være litt kriminiminel',1605138122,1605138125,2500000,NULL),(25,1,'Prøvde å være litt kriminiminel',1605138135,1605138138,2500000,NULL),(26,1,'Prøvde å være litt kriminiminel',1605138450,1605138453,2500000,NULL),(27,1,'Prøvde å være litt kriminiminel',1605139526,1605139529,2500000,NULL),(28,1,'Prøvde å være litt kriminiminel',1605187833,1605187838,2500000,NULL),(29,1,'Prøvde å være litt kriminiminel',1605189171,1605189176,2500000,NULL),(30,1,'Prøvde å være litt kriminiminel',1605202745,1605202748,2500000,NULL),(31,1,'Prøvde å være litt kriminiminel',1605202758,1605202761,2500000,NULL),(32,1,'Prøvde å være litt kriminiminel',1605202842,1605202843,2500000,NULL),(33,1,'Prøvde å være litt kriminiminel',1605202844,1605202845,2500000,NULL),(34,1,'Prøvde å være litt kriminiminel',1605202855,1605202856,2500000,NULL),(35,1,'Prøvde å være litt kriminiminel',1605202856,1605202857,2500000,NULL),(36,1,'Prøvde å være litt kriminiminel',1605202859,1605202860,2500000,NULL),(37,1,'Prøvde å være litt kriminiminel',1605202863,1605202864,2500000,NULL),(38,1,'Prøvde å være litt kriminiminel',1605202865,1605202866,2500000,NULL),(39,1,'Prøvde å være litt kriminiminel',1605202879,1605202880,2500000,NULL),(40,1,'Prøvde å være litt kriminiminel',1605202894,1605202895,2500000,NULL),(41,1,'Prøvde å være litt kriminiminel',1605202906,1605202907,2500000,NULL),(42,1,'Prøvde å være litt kriminiminel',1605202907,1605202908,2500000,NULL),(43,1,'Prøvde å være litt kriminiminel',1605202912,1605202913,2500000,NULL),(44,1,'Prøvde å være litt kriminiminel',1605202913,1605202914,2500000,NULL),(45,1,'Prøvde å være litt kriminiminel',1605202976,1605202977,2500000,NULL),(46,1,'Prøvde å være litt kriminiminel',1605202977,1605202978,2500000,NULL),(47,1,'Prøvde å være litt kriminiminel',1605202979,1605202980,2500000,NULL),(48,1,'Prøvde å være litt kriminiminel',1605202981,1605202982,2500000,NULL),(49,1,'Prøvde å være litt kriminiminel',1605202996,1605202997,2500000,NULL);
/*!40000 ALTER TABLE `jail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `krimlogg`
--

DROP TABLE IF EXISTS `krimlogg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `krimlogg` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `uid` bigint NOT NULL,
  `timestamp` bigint NOT NULL,
  `crime` tinyint NOT NULL DEFAULT '0',
  `result` bigint NOT NULL DEFAULT '0',
  `timewait` varchar(45) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=85 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `krimlogg`
--

LOCK TABLES `krimlogg` WRITE;
/*!40000 ALTER TABLE `krimlogg` DISABLE KEYS */;
INSERT INTO `krimlogg` (`id`, `uid`, `timestamp`, `crime`, `result`, `timewait`) VALUES (1,1,1605202398,1,73,'1605202404'),(2,1,1605202562,1,156,'1605202568'),(3,1,1605202671,1,0,'1605202677'),(4,1,1605202725,1,414,'1605202731'),(5,1,1605202733,1,235,'1605202739'),(6,1,1605202745,1,0,'1605202749'),(7,1,1605202749,1,423,'1605202753'),(8,1,1605202754,1,0,'1605202758'),(9,1,1605202758,1,0,'1605202762'),(10,1,1605202771,1,0,'1605202775'),(11,1,1605202825,1,192,'1605202826'),(12,1,1605202826,1,485,'1605202827'),(13,1,1605202828,1,250,'1605202829'),(14,1,1605202830,1,439,'1605202831'),(15,1,1605202832,1,355,'1605202833'),(16,1,1605202833,1,301,'1605202834'),(17,1,1605202835,1,172,'1605202836'),(18,1,1605202840,1,130,'1605202841'),(19,1,1605202842,1,0,'1605202843'),(20,1,1605202844,1,0,'1605202845'),(21,1,1605202845,1,0,'1605202846'),(22,1,1605202848,1,233,'1605202849'),(23,1,1605202850,1,205,'1605202851'),(24,1,1605202851,1,172,'1605202852'),(25,1,1605202855,1,0,'1605202856'),(26,1,1605202856,1,0,'1605202857'),(27,1,1605202858,1,158,'1605202859'),(28,1,1605202859,1,0,'1605202860'),(29,1,1605202861,1,375,'1605202862'),(30,1,1605202863,1,0,'1605202864'),(31,1,1605202865,1,0,'1605202866'),(32,1,1605202868,1,0,'1605202869'),(33,1,1605202870,1,199,'1605202871'),(34,1,1605202872,1,190,'1605202873'),(35,1,1605202873,1,447,'1605202874'),(36,1,1605202875,1,0,'1605202876'),(37,1,1605202877,1,364,'1605202878'),(38,1,1605202879,1,0,'1605202880'),(39,1,1605202886,1,8,'1605202887'),(40,1,1605202891,1,235,'1605202892'),(41,1,1605202892,1,76,'1605202893'),(42,1,1605202894,1,0,'1605202895'),(43,1,1605202906,1,0,'1605202907'),(44,1,1605202907,1,0,'1605202908'),(45,1,1605202909,1,166,'1605202910'),(46,1,1605202910,1,0,'1605202911'),(47,1,1605202912,1,0,'1605202913'),(48,1,1605202913,1,0,'1605202914'),(49,1,1605202915,1,235,'1605202916'),(50,1,1605202918,1,0,'1605202919'),(51,1,1605202919,1,0,'1605202920'),(52,1,1605202921,1,0,'1605202922'),(53,1,1605202923,1,305,'1605202924'),(54,1,1605202924,1,484,'1605202925'),(55,1,1605202926,1,0,'1605202927'),(56,1,1605202927,1,160,'1605202928'),(57,1,1605202931,1,237,'1605202932'),(58,1,1605202932,1,70,'1605202933'),(59,1,1605202933,1,279,'1605202934'),(60,1,1605202973,1,0,'1605202974'),(61,1,1605202974,1,0,'1605202975'),(62,1,1605202975,1,111,'1605202976'),(63,1,1605202976,1,0,'1605202977'),(64,1,1605202977,1,0,'1605202978'),(65,1,1605202978,1,80,'1605202979'),(66,1,1605202979,1,0,'1605202980'),(67,1,1605202980,1,0,'1605202981'),(68,1,1605202981,1,0,'1605202982'),(69,1,1605202982,1,418,'1605202983'),(70,1,1605202983,1,241,'1605202984'),(71,1,1605202986,1,473,'1605202987'),(72,1,1605202991,1,0,'1605202992'),(73,1,1605202993,1,411,'1605202994'),(74,1,1605202996,1,0,'1605202997'),(75,1,1605203001,1,489,'1605203002'),(76,1,1605203016,1,0,'1605203017'),(77,1,1605203018,1,0,'1605203019'),(78,1,1605203019,1,252,'1605203020'),(79,1,1605203036,1,0,'1605203037'),(80,1,1605203038,1,0,'1605203039'),(81,1,1605203039,1,0,'1605203040'),(82,1,1605203041,1,154,'1605203042'),(83,1,1605203058,1,265,'1605203059'),(84,1,1605475940,1,0,'1605475941');
/*!40000 ALTER TABLE `krimlogg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lottery`
--

DROP TABLE IF EXISTS `lottery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lottery` (
  `id` int NOT NULL AUTO_INCREMENT,
  `round` int DEFAULT NULL,
  `timestamp` int DEFAULT NULL,
  `winnerid` int DEFAULT NULL,
  `timestartstamp` int DEFAULT NULL,
  `pl` int DEFAULT NULL,
  `pr` int DEFAULT NULL,
  `ti` int DEFAULT NULL,
  `al` int DEFAULT NULL,
  `pot` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lottery`
--

LOCK TABLES `lottery` WRITE;
/*!40000 ALTER TABLE `lottery` DISABLE KEYS */;
/*!40000 ALTER TABLE `lottery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lotteryconfig`
--

DROP TABLE IF EXISTS `lotteryconfig`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lotteryconfig` (
  `id` int NOT NULL AUTO_INCREMENT,
  `timestamp` int DEFAULT NULL,
  `percentage` int DEFAULT NULL,
  `ticketprice` int DEFAULT NULL,
  `numticks` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lotteryconfig`
--

LOCK TABLES `lotteryconfig` WRITE;
/*!40000 ALTER TABLE `lotteryconfig` DISABLE KEYS */;
/*!40000 ALTER TABLE `lotteryconfig` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lotterytickets`
--

DROP TABLE IF EXISTS `lotterytickets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lotterytickets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `round` int NOT NULL,
  `uid` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lotterytickets`
--

LOCK TABLES `lotterytickets` WRITE;
/*!40000 ALTER TABLE `lotterytickets` DISABLE KEYS */;
/*!40000 ALTER TABLE `lotterytickets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mails`
--

DROP TABLE IF EXISTS `mails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mails` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `uid` bigint NOT NULL,
  `tid` bigint NOT NULL,
  `title` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `timestamp` bigint NOT NULL,
  `opened` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `type` enum('0','1','2') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `deleted` enum('0','1') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mails`
--

LOCK TABLES `mails` WRITE;
/*!40000 ALTER TABLE `mails` DISABLE KEYS */;
INSERT INTO `mails` (`id`, `uid`, `tid`, `title`, `message`, `timestamp`, `opened`, `type`, `deleted`) VALUES (1,1,1,'Test','This is a test message\\nWith some line jumps',1,'0','1','0');
/*!40000 ALTER TABLE `mails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(60) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `text` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `author` varchar(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `userlevel` enum('1','2','3','4','5') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '5',
  `timestamp` bigint NOT NULL,
  `showing` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` (`id`, `title`, `text`, `author`, `userlevel`, `timestamp`, `showing`) VALUES (15,'Alle','Alle','1','5',1578007846,1),(16,'Ledelsen','Ledelsen','1','4',1578007858,1),(17,'Ledelsen uten picmaker','ledelse uten picmaker','1','3',1578007874,1),(18,'Admin og Mod','Administrator og moderator','1','2',1578007895,1),(19,'Kun Administrator','Administrator','1','1',1578007905,1);
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resetpasset`
--

DROP TABLE IF EXISTS `resetpasset`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `resetpasset` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `uid` bigint NOT NULL,
  `resgen` bigint NOT NULL,
  `timestamp` bigint DEFAULT NULL,
  `used` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resetpasset`
--

LOCK TABLES `resetpasset` WRITE;
/*!40000 ALTER TABLE `resetpasset` DISABLE KEYS */;
/*!40000 ALTER TABLE `resetpasset` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rob_log`
--

DROP TABLE IF EXISTS `rob_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rob_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL,
  `tid` int NOT NULL,
  `ucity` int NOT NULL,
  `tcity` int NOT NULL,
  `result` tinyint(1) NOT NULL,
  `robbed_amount` bigint NOT NULL,
  `timestamp` bigint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rob_log`
--

LOCK TABLES `rob_log` WRITE;
/*!40000 ALTER TABLE `rob_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `rob_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `robbery`
--

DROP TABLE IF EXISTS `robbery`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `robbery` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` bigint NOT NULL,
  `tid` int DEFAULT NULL,
  `timestamp` bigint NOT NULL,
  `ucity` int NOT NULL,
  `tcity` int NOT NULL,
  `amount` bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `robbery`
--

LOCK TABLES `robbery` WRITE;
/*!40000 ALTER TABLE `robbery` DISABLE KEYS */;
/*!40000 ALTER TABLE `robbery` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `selfedit`
--

DROP TABLE IF EXISTS `selfedit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `selfedit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL,
  `bank_old` bigint NOT NULL DEFAULT '0',
  `bank_new` bigint NOT NULL DEFAULT '0',
  `exp_old` decimal(10,3) NOT NULL DEFAULT '0.000',
  `exp_new` decimal(10,3) NOT NULL DEFAULT '0.000',
  `hand_old` bigint NOT NULL DEFAULT '0',
  `hand_new` bigint NOT NULL DEFAULT '0',
  `bullets_old` bigint NOT NULL DEFAULT '0',
  `bullets_new` bigint NOT NULL DEFAULT '0',
  `points_old` bigint NOT NULL DEFAULT '0',
  `points_new` bigint NOT NULL DEFAULT '0',
  `timestamp` bigint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `selfedit`
--

LOCK TABLES `selfedit` WRITE;
/*!40000 ALTER TABLE `selfedit` DISABLE KEYS */;
/*!40000 ALTER TABLE `selfedit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` bigint NOT NULL,
  `user_agent` tinytext,
  `user_ip` bigint NOT NULL,
  `timestamp` bigint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` (`id`, `uid`, `user_agent`, `user_ip`, `timestamp`) VALUES (1,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.106 Safari/537.36',2130706433,1581634419),(2,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.106 Safari/537.36',2130706433,1581712509),(3,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36',2130706433,1582657543),(4,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36',2130706433,1582796037),(5,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36',2130706433,1582797565),(6,2,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36',2130706433,1582797590),(7,3,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36',2130706433,1582798339),(8,2,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36',2130706433,1582798356),(9,4,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36',2130706433,1582800247),(10,5,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36',2130706433,1582800332),(11,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36',2130706433,1582800396),(12,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.122 Safari/537.36',2130706433,1582840324),(13,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Safari/537.36',2130706433,1583947451),(14,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Safari/537.36',2130706433,1583951889),(15,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Safari/537.36',2130706433,1583952255),(16,2,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Safari/537.36',2130706433,1583959558),(17,2,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Safari/537.36',2130706433,1584109580),(18,2,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.132 Safari/537.36',2130706433,1584226173),(19,2,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36',2130706433,1584829076),(20,2,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36',2130706433,1585345722),(21,2,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36',2130706433,1585390671),(22,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36',2130706433,1586528950),(23,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36',2130706433,1586548490),(24,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36',2130706433,1586700748),(25,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36',2130706433,1586719469),(26,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36',2130706433,1586866129),(27,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36',2130706433,1586898002),(28,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.163 Safari/537.36',2130706433,1587322609),(29,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.97 Safari/537.36',2130706433,1592135786),(30,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.116 Safari/537.36',2130706433,1594329375),(31,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.102 Safari/537.36',2130706433,1600622143),(32,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.102 Safari/537.36',2130706433,1600705092),(33,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.102 Safari/537.36',2130706433,1600785497),(34,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36',2130706433,1601238093),(35,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:81.0) Gecko/20100101 Firefox/81.0',2130706433,1601239613),(36,7,'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:81.0) Gecko/20100101 Firefox/81.0',2130706433,1601241599),(37,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.121 Safari/537.36',2130706433,1601316782),(38,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.111 Safari/537.36',2130706433,1604429864),(39,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.111 Safari/537.36',2130706433,1604439356),(40,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.111 Safari/537.36',2130706433,1604440487),(41,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.183 Safari/537.36',2130706433,1604839180),(42,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.183 Safari/537.36',2130706433,1604860446),(43,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.193 Safari/537.36',2130706433,1605120607),(44,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.193 Safari/537.36',2130706433,1605120900),(45,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.193 Safari/537.36',2130706433,1605120998),(46,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.193 Safari/537.36',2130706433,1605121118),(47,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.193 Safari/537.36',2130706433,1605187826),(48,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.193 Safari/537.36',2130706433,1605351453),(49,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.193 Safari/537.36',2130706433,1605475933),(50,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36',2130706433,1606154965),(51,1,'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36',2130706433,1606319767);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stillingslogg`
--

DROP TABLE IF EXISTS `stillingslogg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stillingslogg` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` int DEFAULT NULL COMMENT 'The user setting the status',
  `pid` int DEFAULT NULL COMMENT 'The user affected by change',
  `old_status` int DEFAULT NULL COMMENT 'The old status of affected user',
  `new_status` int DEFAULT NULL COMMENT 'The new status of affected user',
  `timestamp` int DEFAULT NULL COMMENT 'When it was executed',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stillingslogg`
--

LOCK TABLES `stillingslogg` WRITE;
/*!40000 ALTER TABLE `stillingslogg` DISABLE KEYS */;
/*!40000 ALTER TABLE `stillingslogg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `support`
--

DROP TABLE IF EXISTS `support`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `support` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `uid` bigint NOT NULL,
  `topic` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `issue_type` enum('0','1','2','3') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '1',
  `text` mediumtext CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `timestamp` bigint NOT NULL,
  `handled` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `handler` bigint DEFAULT NULL,
  `handler_reply` mediumtext CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `removed` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `support`
--

LOCK TABLES `support` WRITE;
/*!40000 ALTER TABLE `support` DISABLE KEYS */;
/*!40000 ALTER TABLE `support` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `userinfo`
--

DROP TABLE IF EXISTS `userinfo`;
/*!50001 DROP VIEW IF EXISTS `userinfo`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `userinfo` AS SELECT 
 1 AS `id`,
 1 AS `user`,
 1 AS `bank`,
 1 AS `hand`,
 1 AS `city`,
 1 AS `weapon`,
 1 AS `bullets`,
 1 AS `health`,
 1 AS `points`,
 1 AS `exp`,
 1 AS `status`,
 1 AS `support`,
 1 AS `ip`,
 1 AS `hostname`,
 1 AS `lastactive`,
 1 AS `picmaker`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `pass` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `mail` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `image` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '/imgs/nopic.png',
  `profile` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `family` int DEFAULT NULL,
  `bank` bigint NOT NULL DEFAULT '0',
  `hand` bigint NOT NULL DEFAULT '0',
  `city` enum('1','2','3','4','5','6','7','8') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '1',
  `weapon` enum('0','1','2','3','4','5','6','7','8','9') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `bullets` int NOT NULL DEFAULT '0',
  `health` int NOT NULL DEFAULT '100',
  `points` bigint NOT NULL DEFAULT '0',
  `exp` decimal(10,3) NOT NULL DEFAULT '0.000',
  `status` enum('1','2','3','4','5') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '5',
  `support` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `ip` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `regip` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `hostname` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `reghostname` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `lastactive` bigint NOT NULL DEFAULT '0',
  `forceout` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `regstamp` int NOT NULL,
  `picmaker` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Current Database: `mafia`
--

USE `mafia`;

--
-- Final view structure for view `userinfo`
--

/*!50001 DROP VIEW IF EXISTS `userinfo`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `userinfo` AS select `users`.`id` AS `id`,`users`.`user` AS `user`,`users`.`bank` AS `bank`,`users`.`hand` AS `hand`,`users`.`city` AS `city`,`users`.`weapon` AS `weapon`,`users`.`bullets` AS `bullets`,`users`.`health` AS `health`,`users`.`points` AS `points`,`users`.`exp` AS `exp`,`users`.`status` AS `status`,`users`.`support` AS `support`,`users`.`ip` AS `ip`,`users`.`hostname` AS `hostname`,`users`.`lastactive` AS `lastactive`,`users`.`picmaker` AS `picmaker` from `users` order by `users`.`id` desc */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-12-18  0:27:04

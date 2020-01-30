-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: mafia
-- ------------------------------------------------------
-- Server version	8.0.19

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
/* Making it faster and simpler */
CREATE user if not exists 'mafia'@'localhost' identified by 'mafia';
grant all on mafia.* to 'mafia'@'localhost';

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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
  `active` enum('0','1') CHARACTER SET latin1 NOT NULL DEFAULT '1',
  `reason` text CHARACTER SET latin1,
  `banner` bigint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
  `choice` varchar(255) CHARACTER SET latin1 NOT NULL,
  `levelmin` int NOT NULL DEFAULT '1',
  `minval` bigint NOT NULL DEFAULT '0',
  `maxval` bigint NOT NULL DEFAULT '0',
  `bilmin` enum('1','2','3','4','5','6','7','8','9','10') CHARACTER SET latin1 NOT NULL DEFAULT '1',
  `bilmax` enum('1','2','3','4','5','6','7','8','9','10') CHARACTER SET latin1 NOT NULL DEFAULT '1',
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
INSERT INTO `cars` VALUES (1,'Rapp en bil',1,10,100,'1','2',3,1.000,15),(2,'Rapp en større bil',2,20,200,'2','3',6,2.000,30);
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
  `result` enum('0','1') CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `timewait` bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chat` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` bigint NOT NULL,
  `message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `timestamp` bigint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat`
--

LOCK TABLES `chat` WRITE;
/*!40000 ALTER TABLE `chat` DISABLE KEYS */;

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
  `description` text CHARACTER SET latin1,
  `minval` bigint NOT NULL DEFAULT '1',
  `maxval` bigint NOT NULL DEFAULT '10',
  `expgain` float(5,2) NOT NULL DEFAULT '0.00',
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
INSERT INTO `crime` VALUES (1,'Stjel en lommebok',1,10,0.35,3,15,1),(2,'Stjel og selg en tyggispakke',15,30,0.45,25,15,2),(3,'Stjel lommebok fra finkledde personer',150,500,0.67,45,30,3),(4,'Ran en kiosk',750,1200,0.85,60,45,4),(5,'Ran en bankautomat',5000,8000,0.98,80,60,5),(6,'Begå nettkriminalitet',10000,25000,1.05,105,80,6),(7,'Ran en stor bank',50000,100000,1.25,150,120,7);
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `flight_log`
--

LOCK TABLES `flight_log` WRITE;
/*!40000 ALTER TABLE `flight_log` DISABLE KEYS */;
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
  `active` enum('1','0') CHARACTER SET latin1 NOT NULL DEFAULT '1',
  `timestamp` bigint NOT NULL,
  `bantime` bigint NOT NULL,
  `banner` int NOT NULL,
  `reason` longtext CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
  `sold` enum('0','1') CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `timestamp` bigint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ipban` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ip` int NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `timestamp` bigint NOT NULL,
  `reason` text CHARACTER SET latin1,
  `banner` bigint NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip_UNIQUE` (`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jail` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL,
  `reason` varchar(100) CHARACTER SET latin1 NOT NULL,
  `timestamp` bigint NOT NULL,
  `timeleft` bigint NOT NULL,
  `priceout` bigint NOT NULL DEFAULT '0',
  `breaker` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `krimlogg` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `uid` bigint NOT NULL,
  `timestamp` bigint NOT NULL,
  `crime` tinyint NOT NULL DEFAULT '0',
  `result` bigint NOT NULL DEFAULT '0',
  `timewait` varchar(45) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `krimlogg`
--

LOCK TABLES `krimlogg` WRITE;
/*!40000 ALTER TABLE `krimlogg` DISABLE KEYS */;

/*!40000 ALTER TABLE `krimlogg` ENABLE KEYS */;
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
INSERT INTO `mails` VALUES (1,1,1,'Test','This is a test message\\nWith some line jumps',1,'0','1','0');
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
  `title` varchar(60) CHARACTER SET latin1 NOT NULL,
  `text` text CHARACTER SET latin1 NOT NULL,
  `author` varchar(15) CHARACTER SET latin1 NOT NULL,
  `userlevel` enum('1','2','3','4','5') CHARACTER SET latin1 NOT NULL DEFAULT '5',
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
INSERT INTO `news` VALUES (15,'Alle','Alle','1','5',1578007846,1),(16,'Ledelsen','Ledelsen','1','4',1578007858,1),(17,'Ledelsen uten picmaker','ledelse uten picmaker','1','3',1578007874,1),(18,'Admin og Mod','Administrator og moderator','1','2',1578007895,1),(19,'Kun Administrator','Administrator','1','1',1578007905,0);
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
  `used` enum('0','1') CHARACTER SET latin1 NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;

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
  `topic` varchar(20) CHARACTER SET latin1 NOT NULL,
  `issue_type` enum('0','1','2','3') CHARACTER SET latin1 NOT NULL DEFAULT '1',
  `text` mediumtext CHARACTER SET latin1 NOT NULL,
  `timestamp` bigint NOT NULL,
  `handled` enum('0','1') CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `handler` bigint DEFAULT NULL,
  `handler_reply` mediumtext CHARACTER SET latin1,
  `removed` enum('0','1') CHARACTER SET latin1 NOT NULL DEFAULT '0',
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
  `lastactive` bigint DEFAULT NULL,
  `forceout` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  `regstamp` int NOT NULL,
  `picmaker` enum('0','1') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*No user to show*/
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

-- Dump completed on 2020-01-30 22:21:17

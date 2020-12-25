-- MySQL dump 10.13  Distrib 8.0.22, for Linux (x86_64)
--
-- Host: localhost    Database: mafia
-- ------------------------------------------------------
-- Server version	8.0.22-0ubuntu0.20.04.3

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

/*!40000 DROP DATABASE IF EXISTS `mafia`*/;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `mafia` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `mafia`;

CREATE USER IF NOT EXISTS 'mafia'@'localhost' identified with mysql_native_password by 'mafia'; /* Feel free to change this before import! */
GARNT ALL ON mafia.* TO 'mafia'@'localhost';
FLUSH PRIVILEGES;

--
-- Temporary view structure for view `LessUser`
--

DROP TABLE IF EXISTS `LessUser`;
/*!50001 DROP VIEW IF EXISTS `LessUser`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `LessUser` AS SELECT 
 1 AS `id`,
 1 AS `user`,
 1 AS `mail`,
 1 AS `family`,
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
 1 AS `regip`,
 1 AS `hostname`,
 1 AS `reghostname`,
 1 AS `lastactive`,
 1 AS `forceout`,
 1 AS `regstamp`,
 1 AS `picmaker`*/;
SET character_set_client = @saved_cs_client;

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
  PRIMARY KEY (`id`),
  KEY `bank_transfer_ibfk_1` (`uid`),
  CONSTRAINT `bank_transfer_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
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
  `amount` varchar(255) NOT NULL,
  `way` varchar(255) NOT NULL,
  `all` varchar(255) NOT NULL,
  `timestamp` int NOT NULL,
  `uid` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `banklogg_ibfk_1` (`uid`),
  CONSTRAINT `banklogg_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
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
  `id` int NOT NULL AUTO_INCREMENT,
  `uid` int NOT NULL,
  `timestamp` bigint NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `reason` mediumtext,
  `banner` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `banlog_ibfk_1` (`uid`),
  KEY `banlog_ibfk_2` (`banner`),
  CONSTRAINT `banlog_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`),
  CONSTRAINT `banlog_ibfk_2` FOREIGN KEY (`banner`) REFERENCES `users` (`id`)
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
  `uid` int NOT NULL,
  `ucards` text,
  `dcards` text,
  `decks` text,
  `timestamp` bigint DEFAULT NULL,
  `price` bigint DEFAULT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `result` bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `bjtables_ibfk_1` (`uid`),
  CONSTRAINT `bjtables_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
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
  `uid` int NOT NULL,
  `bid` int NOT NULL,
  `timestamp` bigint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `block_ibfk_1` (`uid`),
  KEY `block_ibfk_2` (`bid`),
  CONSTRAINT `block_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`),
  CONSTRAINT `block_ibfk_2` FOREIGN KEY (`bid`) REFERENCES `users` (`id`)
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
  `accepted` enum('0','1') NOT NULL,
  `timeleft` bigint DEFAULT NULL,
  `length` bigint NOT NULL,
  `used` enum('0','1') NOT NULL DEFAULT '0',
  `declined` enum('0','1') NOT NULL DEFAULT '0',
  `gone` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `bunkerinv_ibfk_1` (`uid`),
  KEY `bunkerinv_ibfk_2` (`tid`),
  CONSTRAINT `bunkerinv_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`),
  CONSTRAINT `bunkerinv_ibfk_2` FOREIGN KEY (`tid`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
  `choice` varchar(255) NOT NULL,
  `levelmin` int NOT NULL DEFAULT '1',
  `minval` bigint NOT NULL DEFAULT '0',
  `maxval` bigint NOT NULL DEFAULT '0',
  `bilmin` enum('1','2','3','4','5','6','7','8','9','10') NOT NULL DEFAULT '1',
  `bilmax` enum('1','2','3','4','5','6','7','8','9','10') NOT NULL DEFAULT '1',
  `timewait` bigint NOT NULL DEFAULT '60',
  `expgain` decimal(10,3) NOT NULL DEFAULT '0.000',
  `punishtime` bigint NOT NULL DEFAULT '60',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
  `uid` int NOT NULL,
  `choice` int DEFAULT NULL,
  `timestamp` bigint NOT NULL,
  `result` enum('0','1') NOT NULL DEFAULT '0',
  `timewait` bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `carslog_ibfk_1` (`uid`),
  CONSTRAINT `carslog_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
  PRIMARY KEY (`id`),
  KEY `chance_ibfk_1` (`uid`),
  CONSTRAINT `chance_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
  `uid` int NOT NULL,
  `message` varchar(255) NOT NULL,
  `timestamp` bigint NOT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_ibfk_1` (`uid`),
  CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
  `description` mediumtext,
  `minval` bigint NOT NULL DEFAULT '1',
  `maxval` bigint NOT NULL DEFAULT '10',
  `expgain` decimal(10,3) NOT NULL DEFAULT '0.000',
  `untilnext` bigint NOT NULL DEFAULT '10',
  `punishtime` bigint NOT NULL DEFAULT '60',
  `levelmin` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crime`
--

LOCK TABLES `crime` WRITE;
/*!40000 ALTER TABLE `crime` DISABLE KEYS */;
INSERT INTO `crime` VALUES (1,'Stjel en lommebok fra en dude',0,500,0.567,1,1,1),(2,'Napp veska til en kjendis',500,2500,0.678,10,5,2),(3,'Ran en kiosk',1000,5000,0.789,45,10,3),(4,'Gå til en dør og be om penger',2500,10000,1.123,60,15,4),(5,'Ran en bankautomat',5000,8000,1.234,80,30,5),(6,'Lur de eldre på internett med BitCoins',10000,25000,2.222,105,40,6),(7,'Snik deg inn på et museum',25000,1000000,4.500,150,60,7);
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
  PRIMARY KEY (`id`),
  KEY `firms_ibfk_1` (`uid`),
  CONSTRAINT `firms_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
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
  `uid` int NOT NULL,
  `timestamp` bigint NOT NULL,
  `from_city` enum('0','1','2','3','4','5','6','7','8') DEFAULT '0',
  `to_city` enum('0','1','2','3','4','5','6','7','8') DEFAULT '0',
  `price` bigint DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `flight_log_ibfk_1` (`uid`),
  CONSTRAINT `flight_log_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
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
  `active` enum('1','0') NOT NULL DEFAULT '1',
  `timestamp` bigint NOT NULL,
  `bantime` bigint NOT NULL,
  `banner` int NOT NULL,
  `reason` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `forumban_ibfk_1` (`uid`),
  KEY `forumban_ibfk_2` (`banner`),
  CONSTRAINT `forumban_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`),
  CONSTRAINT `forumban_ibfk_2` FOREIGN KEY (`banner`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
  `sold` enum('0','1') NOT NULL DEFAULT '0',
  `timestamp` bigint NOT NULL,
  PRIMARY KEY (`id`),
  KEY `garage_ibfk_1` (`uid`),
  CONSTRAINT `garage_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
  `mail` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `timestamp` bigint NOT NULL,
  `used` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
  `active` tinyint NOT NULL DEFAULT '1',
  `timestamp` bigint NOT NULL,
  `reason` mediumtext,
  `banner` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip_UNIQUE` (`ip`),
  KEY `ipban_ibfk_1` (`banner`),
  CONSTRAINT `ipban_ibfk_1` FOREIGN KEY (`banner`) REFERENCES `users` (`id`)
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
  `reason` varchar(100) NOT NULL,
  `timestamp` bigint NOT NULL,
  `timeleft` bigint NOT NULL,
  `priceout` bigint NOT NULL DEFAULT '0',
  `breaker` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jail_ibfk_1` (`uid`),
  KEY `jail_ibfk_2` (`breaker`),
  CONSTRAINT `jail_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`),
  CONSTRAINT `jail_ibfk_2` FOREIGN KEY (`breaker`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
  `uid` int NOT NULL,
  `timestamp` bigint NOT NULL,
  `crime` tinyint NOT NULL DEFAULT '0',
  `result` bigint NOT NULL DEFAULT '0',
  `timewait` bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `krimlogg_ibfk_1` (`uid`),
  CONSTRAINT `krimlogg_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `krimlogg`
--

LOCK TABLES `krimlogg` WRITE;
/*!40000 ALTER TABLE `krimlogg` DISABLE KEYS */;
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
  PRIMARY KEY (`id`),
  KEY `lottery_ibfk_1` (`winnerid`),
  CONSTRAINT `lottery_ibfk_1` FOREIGN KEY (`winnerid`) REFERENCES `users` (`id`)
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
  PRIMARY KEY (`id`),
  KEY `lotterytickets_ibfk_1` (`uid`),
  CONSTRAINT `lotterytickets_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
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
  `uid` int NOT NULL,
  `tid` int NOT NULL,
  `title` varchar(25) DEFAULT NULL,
  `message` text,
  `timestamp` bigint NOT NULL,
  `opened` enum('0','1') NOT NULL DEFAULT '0',
  `type` enum('0','1','2') NOT NULL DEFAULT '0',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `mails_ibfk_1` (`uid`),
  KEY `mails_ibfk_2` (`tid`),
  CONSTRAINT `mails_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`),
  CONSTRAINT `mails_ibfk_2` FOREIGN KEY (`tid`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mails`
--

LOCK TABLES `mails` WRITE;
/*!40000 ALTER TABLE `mails` DISABLE KEYS */;
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
  `title` varchar(60) NOT NULL,
  `text` mediumtext NOT NULL,
  `author` int NOT NULL,
  `userlevel` enum('1','2','3','4','5') NOT NULL DEFAULT '5',
  `timestamp` bigint NOT NULL,
  `showing` tinyint NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `news_ibfk_1` (`author`),
  CONSTRAINT `news_ibfk_1` FOREIGN KEY (`author`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
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
  `uid` int NOT NULL,
  `resgen` bigint NOT NULL,
  `timestamp` bigint DEFAULT NULL,
  `used` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `resetpasset_ibfk_1` (`uid`),
  CONSTRAINT `resetpasset_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
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
  PRIMARY KEY (`id`),
  KEY `rob_log_ibfk_1` (`uid`),
  KEY `rob_log_ibfk_2` (`tid`),
  CONSTRAINT `rob_log_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`),
  CONSTRAINT `rob_log_ibfk_2` FOREIGN KEY (`tid`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
  `uid` int NOT NULL,
  `tid` int DEFAULT NULL,
  `timestamp` bigint NOT NULL,
  `ucity` int NOT NULL,
  `tcity` int NOT NULL,
  `amount` bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `robbery_ibfk_1` (`uid`),
  KEY `robbery_ibfk_2` (`tid`),
  CONSTRAINT `robbery_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`),
  CONSTRAINT `robbery_ibfk_2` FOREIGN KEY (`tid`) REFERENCES `users` (`id`)
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
  PRIMARY KEY (`id`),
  KEY `selfedit_ibfk_1` (`uid`),
  CONSTRAINT `selfedit_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
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
  `uid` int NOT NULL,
  `user_agent` tinytext,
  `user_ip` bigint NOT NULL,
  `timestamp` bigint NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_ibfk_1` (`uid`),
  CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
  PRIMARY KEY (`id`),
  KEY `stillingslogg_ibfk_1` (`uid`),
  CONSTRAINT `stillingslogg_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
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
  `uid` int NOT NULL,
  `topic` varchar(20) NOT NULL,
  `issue_type` enum('0','1','2','3') NOT NULL DEFAULT '1',
  `text` longtext NOT NULL,
  `timestamp` bigint NOT NULL,
  `handled` enum('0','1') NOT NULL DEFAULT '0',
  `handler` int DEFAULT NULL,
  `handler_reply` longtext,
  `removed` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `support_ibfk_1` (`uid`),
  KEY `support_ibfk_2` (`handler`),
  CONSTRAINT `support_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`),
  CONSTRAINT `support_ibfk_2` FOREIGN KEY (`handler`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
  `user` varchar(20) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT '/imgs/nopic.png',
  `profile` text,
  `family` int DEFAULT NULL,
  `bank` bigint NOT NULL DEFAULT '0',
  `hand` bigint NOT NULL DEFAULT '0',
  `city` enum('1','2','3','4','5','6','7','8') NOT NULL DEFAULT '1',
  `weapon` enum('0','1','2','3','4','5','6','7','8','9') NOT NULL DEFAULT '0',
  `bullets` int NOT NULL DEFAULT '0',
  `health` int NOT NULL DEFAULT '100',
  `points` bigint NOT NULL DEFAULT '0',
  `exp` decimal(10,3) NOT NULL DEFAULT '0.000',
  `status` enum('1','2','3','4','5') NOT NULL DEFAULT '4' COMMENT '1=adm,2=mod,3=forummod,4=user,5=NPC',
  `support` enum('0','1') NOT NULL DEFAULT '0',
  `ip` varchar(50) DEFAULT NULL,
  `regip` varchar(20) DEFAULT NULL,
  `hostname` varchar(255) DEFAULT NULL,
  `reghostname` varchar(255) DEFAULT NULL,
  `lastactive` bigint NOT NULL DEFAULT '0',
  `forceout` bit(1) NOT NULL DEFAULT b'0',
  `regstamp` int NOT NULL,
  `picmaker` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
-- Final view structure for view `LessUser`
--

/*!50001 DROP VIEW IF EXISTS `LessUser`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_0900_ai_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `LessUser` AS select `users`.`id` AS `id`,`users`.`user` AS `user`,`users`.`mail` AS `mail`,`users`.`family` AS `family`,`users`.`bank` AS `bank`,`users`.`hand` AS `hand`,`users`.`city` AS `city`,`users`.`weapon` AS `weapon`,`users`.`bullets` AS `bullets`,`users`.`health` AS `health`,`users`.`points` AS `points`,`users`.`exp` AS `exp`,`users`.`status` AS `status`,`users`.`support` AS `support`,`users`.`ip` AS `ip`,`users`.`regip` AS `regip`,`users`.`hostname` AS `hostname`,`users`.`reghostname` AS `reghostname`,`users`.`lastactive` AS `lastactive`,`users`.`forceout` AS `forceout`,`users`.`regstamp` AS `regstamp`,`users`.`picmaker` AS `picmaker` from `users` */;
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

-- Dump completed on 2020-12-25  1:58:46

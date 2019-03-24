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

USE `mafia`;

--
-- Table structure for table `ban`
--

DROP TABLE IF EXISTS `ban`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ban` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `banner` int(11) NOT NULL,
  `date` varchar(50) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ban`
--

LOCK TABLES `ban` WRITE;
/*!40000 ALTER TABLE `ban` DISABLE KEYS */;
/*!40000 ALTER TABLE `ban` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bankoverforinger`
--

DROP TABLE IF EXISTS `bankoverforinger`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bankoverforinger` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `tid` int(11) DEFAULT NULL,
  `sum` bigint(20) NOT NULL DEFAULT '0',
  `time` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bankoverforinger`
--

LOCK TABLES `bankoverforinger` WRITE;
/*!40000 ALTER TABLE `bankoverforinger` DISABLE KEYS */;
/*!40000 ALTER TABLE `bankoverforinger` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bildebestilling`
--

DROP TABLE IF EXISTS `bildebestilling`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bildebestilling` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pm` int(11) NOT NULL,
  `reqid` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '1',
  `tb` text,
  `lp` bigint(20) NOT NULL COMMENT 'Minstepris',
  `hp` bigint(20) NOT NULL COMMENT 'HГѓВёystepris',
  `com` text NOT NULL,
  `stock` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `time` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bildebestilling`
--

LOCK TABLES `bildebestilling` WRITE;
/*!40000 ALTER TABLE `bildebestilling` DISABLE KEYS */;
/*!40000 ALTER TABLE `bildebestilling` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bildelinker`
--

DROP TABLE IF EXISTS `bildelinker`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bildelinker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_danish_ci NOT NULL,
  `path` varchar(255) COLLATE latin1_danish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bildelinker`
--

LOCK TABLES `bildelinker` WRITE;
/*!40000 ALTER TABLE `bildelinker` DISABLE KEYS */;
/*!40000 ALTER TABLE `bildelinker` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `biler`
--

DROP TABLE IF EXISTS `biler`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `biler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `handling` varchar(255) NOT NULL,
  `level` int(2) NOT NULL,
  `profitmin` bigint(20) NOT NULL DEFAULT '0',
  `profitmax` bigint(20) NOT NULL DEFAULT '0',
  `bilmin` tinyint(1) NOT NULL DEFAULT '0',
  `bilmax` tinyint(1) NOT NULL DEFAULT '0',
  `aftertime` bigint(20) NOT NULL DEFAULT '120',
  `exp` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `biler`
--

LOCK TABLES `biler` WRITE;
/*!40000 ALTER TABLE `biler` DISABLE KEYS */;
/*!40000 ALTER TABLE `biler` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bilgarasje`
--

DROP TABLE IF EXISTS `bilgarasje`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bilgarasje` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bilid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `gotcity` int(11) NOT NULL,
  `transfer` int(15) NOT NULL DEFAULT '0',
  `curcity` int(11) NOT NULL,
  `lostfound` int(1) NOT NULL DEFAULT '0',
  `sold` tinyint(1) NOT NULL DEFAULT '0',
  `time` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bilgarasje`
--

LOCK TABLES `bilgarasje` WRITE;
/*!40000 ALTER TABLE `bilgarasje` DISABLE KEYS */;
/*!40000 ALTER TABLE `bilgarasje` ENABLE KEYS */;
UNLOCK TABLES;

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
-- Table structure for table `biloverlogg`
--

DROP TABLE IF EXISTS `biloverlogg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `biloverlogg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fra` int(11) NOT NULL DEFAULT '0',
  `til` int(11) NOT NULL DEFAULT '0',
  `bilid` int(11) NOT NULL,
  `time` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `biloverlogg`
--

LOCK TABLES `biloverlogg` WRITE;
/*!40000 ALTER TABLE `biloverlogg` DISABLE KEYS */;
/*!40000 ALTER TABLE `biloverlogg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bjtables`
--

DROP TABLE IF EXISTS `bjtables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bjtables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `ucards` longtext,
  `dcards` longtext,
  `decks` longtext,
  `time` bigint(20) NOT NULL,
  `price` bigint(20) NOT NULL DEFAULT '100000',
  `result` bigint(20) DEFAULT NULL COMMENT 'Sum resultat',
  `round` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bjtables`
--

LOCK TABLES `bjtables` WRITE;
/*!40000 ALTER TABLE `bjtables` DISABLE KEYS */;
/*!40000 ALTER TABLE `bjtables` ENABLE KEYS */;
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
  `time` bigint(20) NOT NULL,
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
  `percent` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
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
-- Table structure for table `debuglogg`
--

DROP TABLE IF EXISTS `debuglogg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `debuglogg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `res` int(11) NOT NULL DEFAULT '1',
  `ip` varchar(40) DEFAULT NULL,
  `allways` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `debuglogg`
--

LOCK TABLES `debuglogg` WRITE;
/*!40000 ALTER TABLE `debuglogg` DISABLE KEYS */;
/*!40000 ALTER TABLE `debuglogg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `famforum`
--

DROP TABLE IF EXISTS `famforum`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `famforum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Tradnavn` varchar(40) NOT NULL DEFAULT 'Uten tittel',
  `Tradstarter` varchar(30) NOT NULL,
  `Melding` longtext,
  `Sticky` enum('0','1') NOT NULL DEFAULT '0',
  `time` bigint(20) NOT NULL,
  `topicstate` char(1) NOT NULL DEFAULT '0',
  `familie` int(11) NOT NULL,
  `slettet` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `famforum`
--

LOCK TABLES `famforum` WRITE;
/*!40000 ALTER TABLE `famforum` DISABLE KEYS */;
/*!40000 ALTER TABLE `famforum` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `familier`
--

DROP TABLE IF EXISTS `familier`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `familier` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Familieid-en',
  `Navn` varchar(30) NOT NULL DEFAULT 'Famnavn',
  `Leder` varchar(45) NOT NULL DEFAULT 'Spilernavn' COMMENT 'Lederen er den som eier familien, og styrer den. Lederen kan velge sin etterkommer, sГѓВҐ lengst den er i familien, og i live.',
  `Ub` varchar(45) DEFAULT NULL,
  `Bank` bigint(20) NOT NULL DEFAULT '0' COMMENT 'Familier starter kun med 25m ved opprettelse, derfor mГѓВҐ spilleren selv ha nok penger til ГѓВҐ kunne drive familien.',
  `apen` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Denne er vanligvis ikke ГѓВҐpen ved opprettelse av familien, den kan ГѓВҐpnes i familiepanelet',
  `TimeMade` varchar(45) NOT NULL DEFAULT '0' COMMENT 'Denne viser timestamp av nГѓВҐr familien ble laget/opprettet',
  `lagtned` tinyint(1) NOT NULL DEFAULT '0',
  `img` varchar(255) NOT NULL DEFAULT 'imgs/nopic.png' COMMENT 'Dette er bilde til familiene.',
  `profil` text COMMENT 'Dette er profil texten til familiene.',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `familier`
--

LOCK TABLES `familier` WRITE;
/*!40000 ALTER TABLE `familier` DISABLE KEYS */;
/*!40000 ALTER TABLE `familier` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `famreq`
--

DROP TABLE IF EXISTS `famreq`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `famreq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usern` varchar(45) NOT NULL DEFAULT 'usern',
  `famname` varchar(45) NOT NULL DEFAULT 'fam',
  `reqtext` longtext NOT NULL,
  `timestamp` varchar(45) DEFAULT 'Tid satt',
  `time` varchar(45) DEFAULT 'Tidsformat satt',
  `bes` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Denne stГѓВѓГ‚ВҐr for besvarelse av en familie.',
  `res` enum('0','1') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `famreq`
--

LOCK TABLES `famreq` WRITE;
/*!40000 ALTER TABLE `famreq` DISABLE KEYS */;
/*!40000 ALTER TABLE `famreq` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `famsvar`
--

DROP TABLE IF EXISTS `famsvar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `famsvar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tradid` int(11) NOT NULL,
  `usern` bigint(20) NOT NULL,
  `svaret` longtext NOT NULL,
  `besvart` varchar(50) NOT NULL,
  `slettet` int(1) NOT NULL DEFAULT '0',
  `tid` int(1) NOT NULL DEFAULT '0',
  `time` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `famsvar`
--

LOCK TABLES `famsvar` WRITE;
/*!40000 ALTER TABLE `famsvar` DISABLE KEYS */;
/*!40000 ALTER TABLE `famsvar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `firma`
--

DROP TABLE IF EXISTS `firma`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `firma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Navn` varchar(255) NOT NULL DEFAULT 'Undefined',
  `Type` int(11) NOT NULL,
  `Konto` bigint(20) NOT NULL DEFAULT '0',
  `Eier` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `firma`
--

LOCK TABLES `firma` WRITE;
/*!40000 ALTER TABLE `firma` DISABLE KEYS */;
/*!40000 ALTER TABLE `firma` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forum`
--

DROP TABLE IF EXISTS `forum`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forum` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tema` varchar(30) NOT NULL,
  `uid` int(11) NOT NULL,
  `melding` longtext NOT NULL,
  `dato` varchar(30) NOT NULL,
  `time` bigint(20) NOT NULL,
  `lasttime` bigint(20) NOT NULL,
  `type` tinyint(1) DEFAULT '1',
  `suid` int(11) DEFAULT NULL,
  `slettet` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forum`
--

LOCK TABLES `forum` WRITE;
/*!40000 ALTER TABLE `forum` DISABLE KEYS */;
/*!40000 ALTER TABLE `forum` ENABLE KEYS */;
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
  `act` enum('1','0') NOT NULL DEFAULT '1',
  `rel_id` int(11) DEFAULT NULL,
  `date` varchar(70) NOT NULL,
  `timeleft` bigint(20) NOT NULL,
  `banid` int(11) NOT NULL,
  `res` longtext NOT NULL,
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
-- Table structure for table `forumcore`
--

DROP TABLE IF EXISTS `forumcore`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forumcore` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `theme` varchar(30) COLLATE latin1_danish_ci NOT NULL DEFAULT 'Ingen tema spesifisert',
  `time` bigint(20) NOT NULL DEFAULT '0',
  `type` enum('0','1','2','3') COLLATE latin1_danish_ci NOT NULL DEFAULT '3',
  `lastanswer` bigint(20) DEFAULT NULL,
  `about` text COLLATE latin1_danish_ci NOT NULL,
  `deleted` enum('0','1') COLLATE latin1_danish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forumcore`
--

LOCK TABLES `forumcore` WRITE;
/*!40000 ALTER TABLE `forumcore` DISABLE KEYS */;
/*!40000 ALTER TABLE `forumcore` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forumsvar`
--

DROP TABLE IF EXISTS `forumsvar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forumsvar` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `suid` int(11) NOT NULL,
  `stid` int(11) NOT NULL,
  `smelding` longtext NOT NULL,
  `sdato` varchar(30) NOT NULL,
  `stime` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forumsvar`
--

LOCK TABLES `forumsvar` WRITE;
/*!40000 ALTER TABLE `forumsvar` DISABLE KEYS */;
/*!40000 ALTER TABLE `forumsvar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `glpw`
--

DROP TABLE IF EXISTS `glpw`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `glpw` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `time` bigint(20) NOT NULL,
  `time2` bigint(20) NOT NULL,
  `ipreq` varchar(20) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `ipcon` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `glpw`
--

LOCK TABLES `glpw` WRITE;
/*!40000 ALTER TABLE `glpw` DISABLE KEYS */;
/*!40000 ALTER TABLE `glpw` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `infoin`
--

DROP TABLE IF EXISTS `infoin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `infoin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full` longtext COLLATE latin1_danish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `infoin`
--

LOCK TABLES `infoin` WRITE;
/*!40000 ALTER TABLE `infoin` DISABLE KEYS */;
/*!40000 ALTER TABLE `infoin` ENABLE KEYS */;
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
  `used` enum('0','1') COLLATE latin1_danish_ci DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invsjekk`
--

LOCK TABLES `invsjekk` WRITE;
/*!40000 ALTER TABLE `invsjekk` DISABLE KEYS */;
INSERT INTO `invsjekk` VALUES (1,'baretester@live.no','1585778718','84.209.188.146',1552783505,'1');
/*!40000 ALTER TABLE `invsjekk` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ipban`
--

DROP TABLE IF EXISTS `ipban`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ipban` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) NOT NULL,
  `grunn` varchar(255) NOT NULL,
  `dato` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
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
-- Table structure for table `jobbe_med`
--

DROP TABLE IF EXISTS `jobbe_med`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobbe_med` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(20) NOT NULL,
  `tekst` longtext NOT NULL,
  `time` bigint(30) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobbe_med`
--

LOCK TABLES `jobbe_med` WRITE;
/*!40000 ALTER TABLE `jobbe_med` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobbe_med` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kills`
--

DROP TABLE IF EXISTS `kills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `bul` int(4) NOT NULL,
  `time` bigint(20) NOT NULL,
  `city` enum('1','2','3','4','5','6','7','8') COLLATE latin1_danish_ci NOT NULL,
  `lifeleft` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kills`
--

LOCK TABLES `kills` WRITE;
/*!40000 ALTER TABLE `kills` DISABLE KEYS */;
/*!40000 ALTER TABLE `kills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `krim`
--

DROP TABLE IF EXISTS `krim`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `krim` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `handlingstekst` text,
  `minm` bigint(20) NOT NULL,
  `maxm` bigint(20) NOT NULL,
  `expgain` float(5,2) NOT NULL DEFAULT '0.00',
  `wait` bigint(20) NOT NULL DEFAULT '10',
  `jailtime` bigint(20) NOT NULL DEFAULT '60',
  `krav` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `krim`
--

LOCK TABLES `krim` WRITE;
/*!40000 ALTER TABLE `krim` DISABLE KEYS */;
INSERT INTO `krim` VALUES (7,'Tekst 7',150,10000,0.90,30,40,1),(2,'Tekst 2',500,30000,0.80,35,45,2),(3,'Tekst 3',1000,45000,0.70,50,70,3),(4,'Tekst 4',1125,50000,0.60,70,90,4),(5,'Tekst 5',1500,70000,0.45,90,90,5),(1,'Tekst 1',1600,120000,0.35,100,120,7),(6,'Tekst 6',1650,100000,0.21,110,90,6);
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
-- Table structure for table `lodd`
--

DROP TABLE IF EXISTS `lodd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lodd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `runde` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lodd`
--

LOCK TABLES `lodd` WRITE;
/*!40000 ALTER TABLE `lodd` DISABLE KEYS */;
/*!40000 ALTER TABLE `lodd` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lotto`
--

DROP TABLE IF EXISTS `lotto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lotto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `runde` int(11) NOT NULL,
  `tid` bigint(20) NOT NULL COMMENT 'Tiden som gjenstГѓВҐr fГѓВёr lottoen trekkes.',
  `vinner` int(11) DEFAULT NULL,
  `tidstart` int(11) NOT NULL,
  `pl` bigint(20) NOT NULL DEFAULT '1000000' COMMENT 'Pris lodd nГѓВҐ',
  `pr` int(11) NOT NULL DEFAULT '10' COMMENT 'Prosentandel',
  `ti` bigint(20) NOT NULL DEFAULT '30' COMMENT 'Rundetid nГѓВҐ',
  `al` bigint(20) NOT NULL DEFAULT '10' COMMENT 'Antall lodd',
  `premie` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lotto`
--

LOCK TABLES `lotto` WRITE;
/*!40000 ALTER TABLE `lotto` DISABLE KEYS */;
/*!40000 ALTER TABLE `lotto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lottoconfig`
--

DROP TABLE IF EXISTS `lottoconfig`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lottoconfig` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Loddpris` bigint(20) NOT NULL DEFAULT '1000000',
  `Prosent` int(3) NOT NULL DEFAULT '10',
  `Tid` bigint(20) NOT NULL DEFAULT '30',
  `Antlodd` bigint(20) NOT NULL DEFAULT '10',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lottoconfig`
--

LOCK TABLES `lottoconfig` WRITE;
/*!40000 ALTER TABLE `lottoconfig` DISABLE KEYS */;
/*!40000 ALTER TABLE `lottoconfig` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(60) NOT NULL COMMENT 'Tittel av nyhet',
  `text` text NOT NULL,
  `author` varchar(15) NOT NULL COMMENT 'Forfatter av nyhet',
  `date` varchar(40) NOT NULL DEFAULT '00:00:00 00.00.0000' COMMENT 'Dato nyheten ble skrevet.',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paymentcheck`
--

DROP TABLE IF EXISTS `paymentcheck`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `paymentcheck` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mes_id` varchar(32) COLLATE latin1_danish_ci NOT NULL,
  `mobil` varchar(30) COLLATE latin1_danish_ci NOT NULL,
  `poeng` int(11) NOT NULL DEFAULT '0',
  `tilpris` decimal(10,3) NOT NULL DEFAULT '0.000',
  `uid` bigint(20) NOT NULL,
  `status` varchar(20) COLLATE latin1_danish_ci NOT NULL DEFAULT 'failed',
  `time` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paymentcheck`
--

LOCK TABLES `paymentcheck` WRITE;
/*!40000 ALTER TABLE `paymentcheck` DISABLE KEYS */;
/*!40000 ALTER TABLE `paymentcheck` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pokertables`
--

DROP TABLE IF EXISTS `pokertables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pokertables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `ucards` text NOT NULL,
  `rest` text NOT NULL,
  `round` mediumint(9) NOT NULL,
  `bet` bigint(20) NOT NULL,
  `time` bigint(20) NOT NULL,
  `result` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pokertables`
--

LOCK TABLES `pokertables` WRITE;
/*!40000 ALTER TABLE `pokertables` DISABLE KEYS */;
/*!40000 ALTER TABLE `pokertables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ransp`
--

DROP TABLE IF EXISTS `ransp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ransp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT 'Spiller som skal stjele',
  `aid` int(11) NOT NULL COMMENT 'Spiller som skal bli bestjГѓВҐlet',
  `us` int(11) NOT NULL COMMENT 'Raner By',
  `as` int(11) NOT NULL COMMENT 'Spiller By',
  `kl` bigint(20) NOT NULL COMMENT 'Sum, om spiller klarer det, ellers 0',
  `time` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ransp`
--

LOCK TABLES `ransp` WRITE;
/*!40000 ALTER TABLE `ransp` DISABLE KEYS */;
/*!40000 ALTER TABLE `ransp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reg_reqs`
--

DROP TABLE IF EXISTS `reg_reqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reg_reqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(40) COLLATE latin1_danish_ci NOT NULL,
  `user` varchar(20) COLLATE latin1_danish_ci NOT NULL,
  `time` int(15) NOT NULL,
  `pardon` enum('0','1') COLLATE latin1_danish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reg_reqs`
--

LOCK TABLES `reg_reqs` WRITE;
/*!40000 ALTER TABLE `reg_reqs` DISABLE KEYS */;
/*!40000 ALTER TABLE `reg_reqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `regcodes`
--

DROP TABLE IF EXISTS `regcodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `regcodes` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '1',
  `code` varchar(11) DEFAULT NULL,
  `date` varchar(50) DEFAULT NULL,
  `used` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `regcodes`
--

LOCK TABLES `regcodes` WRITE;
/*!40000 ALTER TABLE `regcodes` DISABLE KEYS */;
/*!40000 ALTER TABLE `regcodes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resetpasset`
--

DROP TABLE IF EXISTS `resetpasset`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resetpasset` (
  `uid` int(11) NOT NULL,
  `resgen` bigint(20) NOT NULL,
  `timemade` bigint(20) NOT NULL,
  `used` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resetpasset`
--

LOCK TABLES `resetpasset` WRITE;
/*!40000 ALTER TABLE `resetpasset` DISABLE KEYS */;
INSERT INTO `resetpasset` VALUES (1,6870074,1553119890,0);
/*!40000 ALTER TABLE `resetpasset` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stillingslogg`
--

DROP TABLE IF EXISTS `stillingslogg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stillingslogg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL,
  `nyid` bigint(20) NOT NULL,
  `type` enum('1','2','3','4','5') NOT NULL,
  `dato` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `support` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `usid` bigint(20) NOT NULL,
  `theme` varchar(20) NOT NULL DEFAULT 'Ingen tema skrevet',
  `issue` int(1) NOT NULL DEFAULT '1',
  `text` mediumtext NOT NULL,
  `time` bigint(20) NOT NULL,
  `treated` enum('0','1') NOT NULL DEFAULT '0',
  `treatedby` bigint(20) DEFAULT NULL,
  `answer` mediumtext,
  `til_hvem` bigint(20) NOT NULL DEFAULT '0',
  `slettet` enum('0','1') NOT NULL DEFAULT '0',
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
-- Table structure for table `supportsvar`
--

DROP TABLE IF EXISTS `supportsvar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supportsvar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `title` varchar(50) COLLATE latin1_danish_ci NOT NULL DEFAULT 'Support',
  `message` longtext COLLATE latin1_danish_ci NOT NULL,
  `time` bigint(20) NOT NULL,
  `read` enum('0','1') COLLATE latin1_danish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supportsvar`
--

LOCK TABLES `supportsvar` WRITE;
/*!40000 ALTER TABLE `supportsvar` DISABLE KEYS */;
/*!40000 ALTER TABLE `supportsvar` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `update`
--

DROP TABLE IF EXISTS `update`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `update` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `by` varchar(30) NOT NULL DEFAULT 'Admin',
  `Script` varchar(100) NOT NULL DEFAULT 'Et script',
  `reason` longtext,
  `date` varchar(40) NOT NULL DEFAULT '00:00:00 00.00.0000',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `reason` (`reason`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `update`
--

LOCK TABLES `update` WRITE;
/*!40000 ALTER TABLE `update` DISABLE KEYS */;
/*!40000 ALTER TABLE `update` ENABLE KEYS */;
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
INSERT INTO `users` VALUES (1,'Werzy',NULL,'1c96e45b974a26fd07c96a4c41fb8e5b','baretester@live.no','/imgs/nopic.png',NULL,NULL,NULL,0,0,'1','0',0,100,0,0.000,'5','0','0',NULL,NULL,'84.209.188.146','84.209.188.146','cm-84.209.188.146.getinternet.no','cm-84.209.188.146.getinternet.no',1553357570,'0',NULL,1552784001,NULL,NULL,0,NULL);
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

-- Dump completed on 2019-03-23 16:21:32

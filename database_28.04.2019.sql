-- MySQL dump 10.13  Distrib 8.0.16, for Linux (x86_64)
--
-- Host: localhost    Database: mafia
-- ------------------------------------------------------
-- Server version	8.0.16

/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40103 SET @OLD_TIME_ZONE = @@TIME_ZONE */;
/*!40103 SET TIME_ZONE = '+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS = @@UNIQUE_CHECKS, UNIQUE_CHECKS = 0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0 */;
/*!40101 SET @OLD_SQL_MODE = @@SQL_MODE, SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES = @@SQL_NOTES, SQL_NOTES = 0 */;

--
-- Current Database: `mafia`
--

CREATE DATABASE /*!32312 IF NOT EXISTS */ `mafia` /*!40100 DEFAULT CHARACTER SET latin1 */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `mafia`;
CREATE USER 'mafia'@'localhost' IDENTIFIED BY 'mafia';
GRANT ALL ON mafia.* TO 'mafia'@'localhost';
FLUSH PRIVILEGES;

--
-- Table structure for table `banlog`
--

DROP TABLE IF EXISTS `banlog`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
SET character_set_client = utf8mb4;
CREATE TABLE `banlog`
(
    `id`        bigint(20)     NOT NULL AUTO_INCREMENT,
    `uid`       int(11)        NOT NULL,
    `timestamp` bigint(20)     NOT NULL,
    `active`    enum ('0','1') NOT NULL DEFAULT '1',
    `reason`    text,
    `banner`    bigint(20)     NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 0
  DEFAULT CHARSET = latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banlog`
--

LOCK TABLES `banlog` WRITE;
/*!40000 ALTER TABLE `banlog`
    DISABLE KEYS */;
/*!40000 ALTER TABLE `banlog`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `billogg`
--

DROP TABLE IF EXISTS `billogg`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
SET character_set_client = utf8mb4;
CREATE TABLE `billogg`
(
    `id`    bigint(20)  NOT NULL AUTO_INCREMENT,
    `uid`   bigint(20)  NOT NULL,
    `bvalg` int(1)               DEFAULT NULL,
    `time`  bigint(255) NOT NULL,
    `date`  varchar(40) NOT NULL DEFAULT 'non given',
    `resu`  tinyint(1)  NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE = MyISAM
  DEFAULT CHARSET = latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `billogg`
--

LOCK TABLES `billogg` WRITE;
/*!40000 ALTER TABLE `billogg`
    DISABLE KEYS */;
/*!40000 ALTER TABLE `billogg`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `block`
--

DROP TABLE IF EXISTS `block`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
SET character_set_client = utf8mb4;
CREATE TABLE `block`
(
    `id`        bigint(20) NOT NULL AUTO_INCREMENT,
    `uid`       bigint(20) NOT NULL,
    `bid`       bigint(20) NOT NULL,
    `timestamp` bigint(20) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 0
  DEFAULT CHARSET = latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `block`
--

LOCK TABLES `block` WRITE;
/*!40000 ALTER TABLE `block`
    DISABLE KEYS */;
/*!40000 ALTER TABLE `block`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bunkerinv`
--

DROP TABLE IF EXISTS `bunkerinv`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
SET character_set_client = utf8mb4;
CREATE TABLE `bunkerinv`
(
    `id`        int(11)                                 NOT NULL AUTO_INCREMENT,
    `uid`       int(11)                                 NOT NULL,
    `tid`       int(11)                                 NOT NULL,
    `timestamp` bigint(20)                              NOT NULL,
    `accepted`  enum ('0','1') COLLATE latin1_danish_ci NOT NULL,
    `timeleft`  bigint(20)                                       DEFAULT NULL,
    `length`    bigint(20)                              NOT NULL,
    `used`      enum ('0','1') COLLATE latin1_danish_ci NOT NULL DEFAULT '0',
    `declined`  enum ('0','1') COLLATE latin1_danish_ci NOT NULL DEFAULT '0',
    `gone`      enum ('0','1') COLLATE latin1_danish_ci NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE = MyISAM
  DEFAULT CHARSET = latin1
  COLLATE = latin1_danish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bunkerinv`
--

LOCK TABLES `bunkerinv` WRITE;
/*!40000 ALTER TABLE `bunkerinv`
    DISABLE KEYS */;
/*!40000 ALTER TABLE `bunkerinv`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cars`
--

DROP TABLE IF EXISTS `cars`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
SET character_set_client = utf8mb4;
CREATE TABLE `cars`
(
    `id`         int(11)      NOT NULL AUTO_INCREMENT,
    `choice`     varchar(255) NOT NULL,
    `levelmin`   int(2)       NOT NULL DEFAULT '1',
    `minval`     bigint(20)   NOT NULL DEFAULT '0',
    `maxval`     bigint(20)   NOT NULL DEFAULT '0',
    `bilmin`     tinyint(1)   NOT NULL DEFAULT '0',
    `bilmax`     tinyint(1)   NOT NULL DEFAULT '0',
    `timewait`   bigint(20)   NOT NULL DEFAULT '60',
    `expgain`    tinyint(1)   NOT NULL,
    `punishtime` bigint(20)   NOT NULL DEFAULT '60',
    PRIMARY KEY (`id`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 2
  DEFAULT CHARSET = latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cars`
--

LOCK TABLES `cars` WRITE;
/*!40000 ALTER TABLE `cars`
    DISABLE KEYS */;
INSERT INTO `cars`
VALUES (1, 'Rapp en bil', 1, 10, 100, 1, 2, 3, 1, 15);
/*!40000 ALTER TABLE `cars`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `carslog`
--

DROP TABLE IF EXISTS `carslog`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
SET character_set_client = utf8mb4;
CREATE TABLE `carslog`
(
    `id`        bigint(20)     NOT NULL AUTO_INCREMENT,
    `uid`       bigint(20)     NOT NULL,
    `choice`    int(1)                  DEFAULT NULL,
    `timestamp` bigint(255)    NOT NULL,
    `result`    enum ('0','1') NOT NULL DEFAULT '0',
    `timewait`  bigint(20)     NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 0
  DEFAULT CHARSET = latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carslog`
--

LOCK TABLES `carslog` WRITE;
/*!40000 ALTER TABLE `carslog`
    DISABLE KEYS */;
/*!40000 ALTER TABLE `carslog`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chance`
--

DROP TABLE IF EXISTS `chance`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
SET character_set_client = utf8mb4;
CREATE TABLE `chance`
(
    `id`     int(11) NOT NULL AUTO_INCREMENT,
    `uid`    int(11) NOT NULL,
    `type`   int(11) NOT NULL,
    `option` int(11) NOT NULL,
    `chance` int(3)  NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 0
  DEFAULT CHARSET = latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chance`
--

LOCK TABLES `chance` WRITE;
/*!40000 ALTER TABLE `chance`
    DISABLE KEYS */;
/*!40000 ALTER TABLE `chance`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chat`
--

DROP TABLE IF EXISTS `chat`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
SET character_set_client = utf8mb4;
CREATE TABLE `chat`
(
    `id`        int(11)      NOT NULL AUTO_INCREMENT,
    `uid`       bigint(20)   NOT NULL,
    `message`   varchar(255) NOT NULL,
    `timestamp` bigint(20)   NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 0
  DEFAULT CHARSET = latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chat`
--

LOCK TABLES `chat` WRITE;
/*!40000 ALTER TABLE `chat`
    DISABLE KEYS */;
INSERT INTO `chat`
VALUES (1, 0, 'First message of the day!', UNIX_TIMESTAMP());
/*!40000 ALTER TABLE `chat`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `crime`
--

DROP TABLE IF EXISTS `crime`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
SET character_set_client = utf8mb4;
CREATE TABLE `crime`
(
    `id`          int(11)     NOT NULL AUTO_INCREMENT,
    `description` text,
    `minval`      bigint(20)  NOT NULL DEFAULT '1',
    `maxval`      bigint(20)  NOT NULL DEFAULT '10',
    `expgain`     float(5, 2) NOT NULL DEFAULT '0.00',
    `untilnext`   bigint(20)  NOT NULL DEFAULT '10',
    `punishtime`  bigint(20)  NOT NULL DEFAULT '60',
    `levelmin`    int(11)     NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 8
  DEFAULT CHARSET = latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `crime`
--

LOCK TABLES `crime` WRITE;
/*!40000 ALTER TABLE `crime`
    DISABLE KEYS */;
INSERT INTO `crime`
VALUES (7, 'Seventh', 50000, 100000, 1.25, 150, 120, 7),
       (2, 'Second', 15, 30, 0.45, 25, 15, 2),
       (3, 'Third', 150, 500, 0.67, 45, 30, 3),
       (4, 'Forth', 750, 1200, 0.85, 60, 45, 4),
       (5, 'Fifth', 5000, 8000, 0.98, 80, 60, 5),
       (1, 'First', 1, 10, 0.35, 3, 15, 1),
       (6, 'Sixth', 10000, 25000, 1.05, 105, 80, 6);
/*!40000 ALTER TABLE `crime`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `forumban`
--

DROP TABLE IF EXISTS `forumban`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
SET character_set_client = utf8mb4;
CREATE TABLE `forumban`
(
    `id`        int(11)        NOT NULL AUTO_INCREMENT,
    `uid`       int(11)        NOT NULL,
    `active`    enum ('1','0') NOT NULL DEFAULT '1',
    `timestamp` bigint(20)     NOT NULL,
    `bantime`   bigint(20)     NOT NULL,
    `banner`    int(11)        NOT NULL,
    `reason`    longtext       NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 0
  DEFAULT CHARSET = latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forumban`
--

LOCK TABLES `forumban` WRITE;
/*!40000 ALTER TABLE `forumban`
    DISABLE KEYS */;
/*!40000 ALTER TABLE `forumban`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `garage`
--

DROP TABLE IF EXISTS `garage`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
SET character_set_client = utf8mb4;
CREATE TABLE `garage`
(
    `id`           int(11)        NOT NULL AUTO_INCREMENT,
    `car_id`       int(11)        NOT NULL,
    `uid`          int(11)        NOT NULL,
    `stolen_city`  int(11)        NOT NULL,
    `transferred`  enum ('0','1') NOT NULL DEFAULT '0',
    `current_city` int(11)        NOT NULL,
    `sold`         enum ('0','1') NOT NULL DEFAULT '0',
    `timestamp`    bigint(20)     NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 0
  DEFAULT CHARSET = latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `garage`
--

LOCK TABLES `garage` WRITE;
/*!40000 ALTER TABLE `garage`
    DISABLE KEYS */;
/*!40000 ALTER TABLE `garage`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invsjekk`
--

DROP TABLE IF EXISTS `invsjekk`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
SET character_set_client = utf8mb4;
CREATE TABLE `invsjekk`
(
    `id`        int(11)                                 NOT NULL AUTO_INCREMENT,
    `mail`      varchar(255) COLLATE latin1_danish_ci   NOT NULL,
    `code`      varchar(255) COLLATE latin1_danish_ci   NOT NULL,
    `ip`        varchar(100) COLLATE latin1_danish_ci   NOT NULL,
    `timestamp` bigint(20)                              NOT NULL,
    `used`      enum ('0','1') COLLATE latin1_danish_ci NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 0
  DEFAULT CHARSET = latin1
  COLLATE = latin1_danish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invsjekk`
--

LOCK TABLES `invsjekk` WRITE;
/*!40000 ALTER TABLE `invsjekk`
    DISABLE KEYS */;
/*!40000 ALTER TABLE `invsjekk`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ipban`
--

DROP TABLE IF EXISTS `ipban`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
SET character_set_client = utf8mb4;
CREATE TABLE `ipban`
(
    `id`        int(11)    NOT NULL AUTO_INCREMENT,
    `ip`        int(20)    NOT NULL,
    `active`    tinyint(1) NOT NULL DEFAULT '1',
    `timestamp` bigint(20) NOT NULL,
    `reason`    text,
    `banner`    bigint(20) NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `ip_UNIQUE` (`ip`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 0
  DEFAULT CHARSET = latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ipban`
--

LOCK TABLES `ipban` WRITE;
/*!40000 ALTER TABLE `ipban`
    DISABLE KEYS */;
/*!40000 ALTER TABLE `ipban`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jail`
--

DROP TABLE IF EXISTS `jail`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
SET character_set_client = utf8mb4;
CREATE TABLE `jail`
(
    `id`        int(11)      NOT NULL AUTO_INCREMENT,
    `uid`       int(11)      NOT NULL,
    `reason`    varchar(100) NOT NULL,
    `timestamp` bigint(20)   NOT NULL,
    `timeleft`  bigint(20)   NOT NULL,
    `priceout`  bigint(20)   NOT NULL DEFAULT '0',
    `breaker`   int(11)               DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 0
  DEFAULT CHARSET = latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jail`
--

LOCK TABLES `jail` WRITE;
/*!40000 ALTER TABLE `jail`
    DISABLE KEYS */;
/*!40000 ALTER TABLE `jail`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `krimlogg`
--

DROP TABLE IF EXISTS `krimlogg`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
SET character_set_client = utf8mb4;
CREATE TABLE `krimlogg`
(
    `id`        bigint(20)  NOT NULL AUTO_INCREMENT,
    `uid`       bigint(20)  NOT NULL,
    `timestamp` bigint(255) NOT NULL,
    `crime`     tinyint(2)  NOT NULL DEFAULT '0',
    `result`    bigint(20)  NOT NULL DEFAULT '0',
    `timewait`  varchar(45) NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 0
  DEFAULT CHARSET = latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `krimlogg`
--

LOCK TABLES `krimlogg` WRITE;
/*!40000 ALTER TABLE `krimlogg`
    DISABLE KEYS */;
/*!40000 ALTER TABLE `krimlogg`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mails`
--

DROP TABLE IF EXISTS `mails`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
SET character_set_client = utf8mb4;
CREATE TABLE `mails`
(
    `id`        bigint(20)                                    NOT NULL AUTO_INCREMENT,
    `uid`       bigint(20)                                    NOT NULL,
    `tid`       bigint(20)                                    NOT NULL,
    `title`     varchar(25) COLLATE utf8mb4_unicode_ci                 DEFAULT NULL,
    `message`   text COLLATE utf8mb4_unicode_ci,
    `timestamp` bigint(20)                                    NOT NULL,
    `opened`    enum ('0','1') COLLATE utf8mb4_unicode_ci     NOT NULL DEFAULT '0',
    `type`      enum ('0','1','2') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
    `deleted`   enum ('0','1') COLLATE utf8mb4_unicode_ci     NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mails`
--

LOCK TABLES `mails` WRITE;
/*!40000 ALTER TABLE `mails`
    DISABLE KEYS */;
/*!40000 ALTER TABLE `mails`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
SET character_set_client = utf8mb4;
CREATE TABLE `news`
(
    `id`        int(11)                    NOT NULL AUTO_INCREMENT,
    `title`     varchar(60)                NOT NULL,
    `text`      text                       NOT NULL,
    `author`    varchar(15)                NOT NULL,
    `userlevel` enum ('1','2','3','4','5') NOT NULL DEFAULT '5',
    `timestamp` bigint(20)                 NOT NULL,
    `showing`   tinyint(1)                 NOT NULL DEFAULT '1',
    PRIMARY KEY (`id`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 0
  DEFAULT CHARSET = latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news`
    DISABLE KEYS */;
INSERT INTO `news`
VALUES (1, 'Hello', 'This is the first news article! Yey!', '1', '5', UNIX_TIMESTAMP(), 1);
/*!40000 ALTER TABLE `news`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resetpasset`
--

DROP TABLE IF EXISTS `resetpasset`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
SET character_set_client = utf8mb4;
CREATE TABLE `resetpasset`
(
    `id`        bigint(20)     NOT NULL AUTO_INCREMENT,
    `uid`       bigint(20)     NOT NULL,
    `resgen`    bigint(20)     NOT NULL,
    `timestamp` bigint(20)              DEFAULT NULL,
    `used`      enum ('0','1') NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 0
  DEFAULT CHARSET = latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resetpasset`
--

LOCK TABLES `resetpasset` WRITE;
/*!40000 ALTER TABLE `resetpasset`
    DISABLE KEYS */;
/*!40000 ALTER TABLE `resetpasset`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rob_log`
--

DROP TABLE IF EXISTS `rob_log`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
SET character_set_client = utf8mb4;
CREATE TABLE `rob_log`
(
    `id`            int(11)    NOT NULL AUTO_INCREMENT,
    `uid`           int(11)    NOT NULL,
    `tid`           int(11)    NOT NULL,
    `ucity`         int(11)    NOT NULL,
    `tcity`         int(11)    NOT NULL,
    `result`        tinyint(1) NOT NULL,
    `robbed_amount` bigint(20) NOT NULL,
    `timestamp`     bigint(20) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = MyISAM
  DEFAULT CHARSET = latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rob_log`
--

LOCK TABLES `rob_log` WRITE;
/*!40000 ALTER TABLE `rob_log`
    DISABLE KEYS */;
/*!40000 ALTER TABLE `rob_log`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `support`
--

DROP TABLE IF EXISTS `support`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
SET character_set_client = utf8mb4;
CREATE TABLE `support`
(
    `id`            bigint(20)             NOT NULL AUTO_INCREMENT,
    `uid`           bigint(20)             NOT NULL,
    `topic`         varchar(20)            NOT NULL,
    `issue_type`    enum ('0','1','2','3') NOT NULL DEFAULT '1',
    `text`          mediumtext             NOT NULL,
    `timestamp`     bigint(20)             NOT NULL,
    `handled`       enum ('0','1')         NOT NULL DEFAULT '0',
    `handler`       bigint(20)                      DEFAULT NULL,
    `handler_reply` mediumtext,
    `removed`       enum ('0','1')         NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE = MyISAM
  DEFAULT CHARSET = latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `support`
--

LOCK TABLES `support` WRITE;
/*!40000 ALTER TABLE `support`
    DISABLE KEYS */;
/*!40000 ALTER TABLE `support`
    ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client = @@character_set_client */;
SET character_set_client = utf8mb4;
CREATE TABLE `users`
(
    `id`          int(11)                                        NOT NULL AUTO_INCREMENT,
    `user`        varchar(20)                                    NOT NULL,
    `named`       varchar(60)                                             DEFAULT NULL,
    `pass`        varchar(255)                                   NOT NULL,
    `mail`        varchar(255)                                   NOT NULL,
    `image`       varchar(255)                                   NOT NULL DEFAULT '/imgs/nopic.png',
    `profile`     text                                           null,
    `bank`        bigint(25)                                     NOT NULL DEFAULT '0',
    `hand`        bigint(25)                                     NOT NULL DEFAULT '0',
    `city`        enum ('1','2','3','4','5','6','7','8')         NOT NULL DEFAULT '1',
    `weapon`      enum ('0','1','2','3','4','5','6','7','8','9') NOT NULL DEFAULT '0',
    `bullets`     int(11)                                        NOT NULL DEFAULT '0',
    `health`      int(3)                                         NOT NULL DEFAULT '100',
    `points`      bigint(20)                                     NOT NULL DEFAULT '0',
    `exp`         decimal(10, 3)                                 NOT NULL DEFAULT '0.000',
    `status`      enum ('1','2','3','4')                         NOT NULL DEFAULT '4',
    `support`     enum ('0','1')                                 NOT NULL DEFAULT '0',
    `picmaker`    enum ('0','1')                                 NOT NULL DEFAULT '0',
    `ip`          varchar(50)                                             DEFAULT NULL,
    `regip`       varchar(20)                                             DEFAULT NULL,
    `hostname`    varchar(255)                                            DEFAULT NULL,
    `reghostname` varchar(255)                                            DEFAULT NULL,
    `lastactive`  bigint(20)                                              DEFAULT NULL,
    `forceout`    enum ('0','1')                                 NOT NULL DEFAULT '0',
    `regstamp`    int(10)                                        NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = MyISAM
  AUTO_INCREMENT = 0
  DEFAULT CHARSET = latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users`
    DISABLE KEYS */;
/*!40000 ALTER TABLE `users`
    ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE = @OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE = @OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS = @OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES = @OLD_SQL_NOTES */;

-- Dump completed on 2019-04-28 16:29:23

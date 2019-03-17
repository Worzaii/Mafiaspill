SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
CREATE DATABASE IF NOT EXISTS `mafia_no_net` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `mafia_no_net`;

DROP TABLE IF EXISTS `ban`;
CREATE TABLE IF NOT EXISTS `ban` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `banner` int(11) NOT NULL,
  `date` varchar(50) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `bankoverforinger`;
CREATE TABLE IF NOT EXISTS `bankoverforinger` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `tid` int(11) DEFAULT NULL,
  `sum` bigint(20) NOT NULL DEFAULT '0',
  `time` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `bildebestilling`;
CREATE TABLE IF NOT EXISTS `bildebestilling` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pm` int(11) NOT NULL,
  `reqid` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '1',
  `tb` text,
  `lp` bigint(20) NOT NULL COMMENT 'Minstepris',
  `hp` bigint(20) NOT NULL COMMENT 'HÃ¸ystepris',
  `com` text NOT NULL,
  `stock` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `time` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `bildelinker`;
CREATE TABLE IF NOT EXISTS `bildelinker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE latin1_danish_ci NOT NULL,
  `path` varchar(255) COLLATE latin1_danish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;

DROP TABLE IF EXISTS `biler`;
CREATE TABLE IF NOT EXISTS `biler` (
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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `bilgarasje`;
CREATE TABLE IF NOT EXISTS `bilgarasje` (
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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `billogg`;
CREATE TABLE IF NOT EXISTS `billogg` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL,
  `bvalg` int(1) DEFAULT NULL,
  `time` bigint(255) NOT NULL,
  `date` varchar(40) NOT NULL DEFAULT 'non given',
  `resu` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `biloverlogg`;
CREATE TABLE IF NOT EXISTS `biloverlogg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fra` int(11) NOT NULL DEFAULT '0',
  `til` int(11) NOT NULL DEFAULT '0',
  `bilid` int(11) NOT NULL,
  `time` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `bjtables`;
CREATE TABLE IF NOT EXISTS `bjtables` (
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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `bunkerinv`;
CREATE TABLE IF NOT EXISTS `bunkerinv` (
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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;

DROP TABLE IF EXISTS `chance`;
CREATE TABLE IF NOT EXISTS `chance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `option` int(11) NOT NULL,
  `percent` int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `chat`;
CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL,
  `mld` varchar(255) NOT NULL,
  `date` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `debuglogg`;
CREATE TABLE IF NOT EXISTS `debuglogg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `res` int(11) NOT NULL DEFAULT '1',
  `ip` varchar(40) DEFAULT NULL,
  `allways` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `famforum`;
CREATE TABLE IF NOT EXISTS `famforum` (
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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `familier`;
CREATE TABLE IF NOT EXISTS `familier` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Familieid-en',
  `Navn` varchar(30) NOT NULL DEFAULT 'Famnavn',
  `Leder` varchar(45) NOT NULL DEFAULT 'Spilernavn' COMMENT 'Lederen er den som eier familien, og styrer den. Lederen kan velge sin etterkommer, sÃ¥ lengst den er i familien, og i live.',
  `Ub` varchar(45) DEFAULT NULL,
  `Bank` bigint(20) NOT NULL DEFAULT '0' COMMENT 'Familier starter kun med 25m ved opprettelse, derfor mÃ¥ spilleren selv ha nok penger til Ã¥ kunne drive familien.',
  `apen` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Denne er vanligvis ikke Ã¥pen ved opprettelse av familien, den kan Ã¥pnes i familiepanelet',
  `TimeMade` varchar(45) NOT NULL DEFAULT '0' COMMENT 'Denne viser timestamp av nÃ¥r familien ble laget/opprettet',
  `lagtned` tinyint(1) NOT NULL DEFAULT '0',
  `img` varchar(255) NOT NULL DEFAULT 'imgs/nopic.png' COMMENT 'Dette er bilde til familiene.',
  `profil` text COMMENT 'Dette er profil texten til familiene.',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `famreq`;
CREATE TABLE IF NOT EXISTS `famreq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usern` varchar(45) NOT NULL DEFAULT 'usern',
  `famname` varchar(45) NOT NULL DEFAULT 'fam',
  `reqtext` longtext NOT NULL,
  `timestamp` varchar(45) DEFAULT 'Tid satt',
  `time` varchar(45) DEFAULT 'Tidsformat satt',
  `bes` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'Denne stÃÂ¥r for besvarelse av en familie.',
  `res` enum('0','1') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `famsvar`;
CREATE TABLE IF NOT EXISTS `famsvar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tradid` int(11) NOT NULL,
  `usern` bigint(20) NOT NULL,
  `svaret` longtext NOT NULL,
  `besvart` varchar(50) NOT NULL,
  `slettet` int(1) NOT NULL DEFAULT '0',
  `tid` int(1) NOT NULL DEFAULT '0',
  `time` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `firma`;
CREATE TABLE IF NOT EXISTS `firma` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Navn` varchar(255) NOT NULL DEFAULT 'Undefined',
  `Type` int(11) NOT NULL,
  `Konto` bigint(20) NOT NULL DEFAULT '0',
  `Eier` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `forum`;
CREATE TABLE IF NOT EXISTS `forum` (
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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `forumban`;
CREATE TABLE IF NOT EXISTS `forumban` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `act` enum('1','0') NOT NULL DEFAULT '1',
  `rel_id` int(11) DEFAULT NULL,
  `date` varchar(70) NOT NULL,
  `timeleft` bigint(20) NOT NULL,
  `banid` int(11) NOT NULL,
  `res` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `forumcore`;
CREATE TABLE IF NOT EXISTS `forumcore` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `theme` varchar(30) COLLATE latin1_danish_ci NOT NULL DEFAULT 'Ingen tema spesifisert',
  `time` bigint(20) NOT NULL DEFAULT '0',
  `type` enum('0','1','2','3') COLLATE latin1_danish_ci NOT NULL DEFAULT '3',
  `lastanswer` bigint(20) DEFAULT NULL,
  `about` text COLLATE latin1_danish_ci NOT NULL,
  `deleted` enum('0','1') COLLATE latin1_danish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;

DROP TABLE IF EXISTS `forumsvar`;
CREATE TABLE IF NOT EXISTS `forumsvar` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `suid` int(11) NOT NULL,
  `stid` int(11) NOT NULL,
  `smelding` longtext NOT NULL,
  `sdato` varchar(30) NOT NULL,
  `stime` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`sid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `glpw`;
CREATE TABLE IF NOT EXISTS `glpw` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `time` bigint(20) NOT NULL,
  `time2` bigint(20) NOT NULL,
  `ipreq` varchar(20) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `ipcon` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `infoin`;
CREATE TABLE IF NOT EXISTS `infoin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full` longtext COLLATE latin1_danish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;

DROP TABLE IF EXISTS `invsjekk`;
CREATE TABLE IF NOT EXISTS `invsjekk` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mail` varchar(255) COLLATE latin1_danish_ci NOT NULL,
  `code` varchar(255) COLLATE latin1_danish_ci NOT NULL,
  `ip` varchar(100) COLLATE latin1_danish_ci NOT NULL,
  `time` bigint(20) NOT NULL,
  `used` enum('0','1') COLLATE latin1_danish_ci DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;

DROP TABLE IF EXISTS `ipban`;
CREATE TABLE IF NOT EXISTS `ipban` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) NOT NULL,
  `grunn` varchar(255) NOT NULL,
  `dato` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `jail`;
CREATE TABLE IF NOT EXISTS `jail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `reason` varchar(100) NOT NULL DEFAULT 'Gjorde noe ulovlig!',
  `time` bigint(20) NOT NULL,
  `timeleft` bigint(20) NOT NULL,
  `prisut` bigint(20) NOT NULL DEFAULT '1000000',
  `breaker` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `jobbe_med`;
CREATE TABLE IF NOT EXISTS `jobbe_med` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(20) NOT NULL,
  `tekst` longtext NOT NULL,
  `time` bigint(30) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `kills`;
CREATE TABLE IF NOT EXISTS `kills` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kid` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `bul` int(4) NOT NULL,
  `time` bigint(20) NOT NULL,
  `city` enum('1','2','3','4','5','6','7','8') COLLATE latin1_danish_ci NOT NULL,
  `lifeleft` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;

DROP TABLE IF EXISTS `krim`;
CREATE TABLE IF NOT EXISTS `krim` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `handlingstekst` text,
  `minm` bigint(20) NOT NULL,
  `maxm` bigint(20) NOT NULL,
  `expgain` float(5,2) NOT NULL DEFAULT '0.00',
  `wait` bigint(20) NOT NULL DEFAULT '10',
  `jailtime` bigint(20) NOT NULL DEFAULT '60',
  `krav` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

INSERT INTO `krim` (`id`, `handlingstekst`, `minm`, `maxm`, `expgain`, `wait`, `jailtime`, `krav`) VALUES
(7, 'Tekst 7', 150, 10000, 0.90, 30, 40, 1),
(2, 'Tekst 2', 500, 30000, 0.80, 35, 45, 2),
(3, 'Tekst 3', 1000, 45000, 0.70, 50, 70, 3),
(4, 'Tekst 4', 1125, 50000, 0.60, 70, 90, 4),
(5, 'Tekst 5', 1500, 70000, 0.45, 90, 90, 5),
(1, 'Tekst 1', 1600, 120000, 0.35, 100, 120, 7),
(6, 'Tekst 6', 1650, 100000, 0.21, 110, 90, 6);

DROP TABLE IF EXISTS `krimlogg`;
CREATE TABLE IF NOT EXISTS `krimlogg` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `usid` bigint(20) NOT NULL,
  `time` bigint(255) NOT NULL,
  `valid` tinyint(2) NOT NULL DEFAULT '0',
  `resu` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `lodd`;
CREATE TABLE IF NOT EXISTS `lodd` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `runde` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `lotto`;
CREATE TABLE IF NOT EXISTS `lotto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `runde` int(11) NOT NULL,
  `tid` bigint(20) NOT NULL COMMENT 'Tiden som gjenstÃ¥r fÃ¸r lottoen trekkes.',
  `vinner` int(11) DEFAULT NULL,
  `tidstart` int(11) NOT NULL,
  `pl` bigint(20) NOT NULL DEFAULT '1000000' COMMENT 'Pris lodd nÃ¥',
  `pr` int(11) NOT NULL DEFAULT '10' COMMENT 'Prosentandel',
  `ti` bigint(20) NOT NULL DEFAULT '30' COMMENT 'Rundetid nÃ¥',
  `al` bigint(20) NOT NULL DEFAULT '10' COMMENT 'Antall lodd',
  `premie` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

DROP TABLE IF EXISTS `lottoconfig`;
CREATE TABLE IF NOT EXISTS `lottoconfig` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Loddpris` bigint(20) NOT NULL DEFAULT '1000000',
  `Prosent` int(3) NOT NULL DEFAULT '10',
  `Tid` bigint(20) NOT NULL DEFAULT '30',
  `Antlodd` bigint(20) NOT NULL DEFAULT '10',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `mail`;
CREATE TABLE IF NOT EXISTS `mail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `UId` int(11) NOT NULL,
  `TId` int(11) NOT NULL,
  `read` enum('0','1') NOT NULL DEFAULT '0',
  `theme` varchar(50) NOT NULL,
  `message` longtext NOT NULL,
  `time` bigint(100) NOT NULL,
  `date` varchar(50) NOT NULL,
  `answer` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `mail2`;
CREATE TABLE IF NOT EXISTS `mail2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT 'Uten tittel',
  `message` longtext NOT NULL,
  `time` bigint(20) NOT NULL,
  `read` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `Sort` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `news`;
CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(60) NOT NULL COMMENT 'Tittel av nyhet',
  `text` text NOT NULL,
  `author` varchar(15) NOT NULL COMMENT 'Forfatter av nyhet',
  `date` varchar(40) NOT NULL DEFAULT '00:00:00 00.00.0000' COMMENT 'Dato nyheten ble skrevet.',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `paymentcheck`;
CREATE TABLE IF NOT EXISTS `paymentcheck` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mes_id` varchar(32) COLLATE latin1_danish_ci NOT NULL,
  `mobil` varchar(30) COLLATE latin1_danish_ci NOT NULL,
  `poeng` int(11) NOT NULL DEFAULT '0',
  `tilpris` decimal(10,3) NOT NULL DEFAULT '0.000',
  `uid` bigint(20) NOT NULL,
  `status` varchar(20) COLLATE latin1_danish_ci NOT NULL DEFAULT 'failed',
  `time` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;

DROP TABLE IF EXISTS `pokertables`;
CREATE TABLE IF NOT EXISTS `pokertables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `ucards` text NOT NULL,
  `rest` text NOT NULL,
  `round` mediumint(9) NOT NULL,
  `bet` bigint(20) NOT NULL,
  `time` bigint(20) NOT NULL,
  `result` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ransp`;
CREATE TABLE IF NOT EXISTS `ransp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT 'Spiller som skal stjele',
  `aid` int(11) NOT NULL COMMENT 'Spiller som skal bli bestjÃ¥let',
  `us` int(11) NOT NULL COMMENT 'Raner By',
  `as` int(11) NOT NULL COMMENT 'Spiller By',
  `kl` bigint(20) NOT NULL COMMENT 'Sum, om spiller klarer det, ellers 0',
  `time` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `regcodes`;
CREATE TABLE IF NOT EXISTS `regcodes` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '1',
  `code` varchar(11) DEFAULT NULL,
  `date` varchar(50) DEFAULT NULL,
  `used` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `reg_reqs`;
CREATE TABLE IF NOT EXISTS `reg_reqs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(40) COLLATE latin1_danish_ci NOT NULL,
  `user` varchar(20) COLLATE latin1_danish_ci NOT NULL,
  `time` int(15) NOT NULL,
  `pardon` enum('0','1') COLLATE latin1_danish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user` (`user`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;

DROP TABLE IF EXISTS `stillingslogg`;
CREATE TABLE IF NOT EXISTS `stillingslogg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` bigint(20) NOT NULL,
  `nyid` bigint(20) NOT NULL,
  `type` enum('1','2','3','4','5') NOT NULL,
  `dato` bigint(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `support`;
CREATE TABLE IF NOT EXISTS `support` (
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
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `supportsvar`;
CREATE TABLE IF NOT EXISTS `supportsvar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` int(11) NOT NULL,
  `fid` int(11) NOT NULL,
  `title` varchar(50) COLLATE latin1_danish_ci NOT NULL DEFAULT 'Support',
  `message` longtext COLLATE latin1_danish_ci NOT NULL,
  `time` bigint(20) NOT NULL,
  `read` enum('0','1') COLLATE latin1_danish_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_danish_ci;

DROP TABLE IF EXISTS `update`;
CREATE TABLE IF NOT EXISTS `update` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `by` varchar(30) NOT NULL DEFAULT 'Admin',
  `Script` varchar(100) NOT NULL DEFAULT 'Et script',
  `reason` longtext,
  `date` varchar(40) NOT NULL DEFAULT '00:00:00 00.00.0000',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(20) NOT NULL,
  `named` varchar(60) DEFAULT NULL,
  `pass` varchar(32) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL DEFAULT '/imgs/nopic.png' COMMENT 'Avatar',
  `profile` longtext NOT NULL,
  `family` int(2) DEFAULT NULL,
  `code` varchar(12) DEFAULT NULL,
  `bank` bigint(25) NOT NULL DEFAULT '0' COMMENT 'Penger spiller har i banken',
  `hand` bigint(25) NOT NULL DEFAULT '0' COMMENT 'Penger pÃ¥ handa(ute)',
  `city` enum('1','2','3','4','5','6','7','8') NOT NULL DEFAULT '1',
  `weapon` enum('0','1','2','3','4','5','6','7','8','9') NOT NULL DEFAULT '0',
  `bullets` int(11) NOT NULL DEFAULT '0',
  `health` int(3) NOT NULL DEFAULT '100',
  `points` bigint(20) NOT NULL DEFAULT '0',
  `exp` decimal(10,3) NOT NULL DEFAULT '0.000',
  `status` enum('1','2','3','4','5','6','7') NOT NULL DEFAULT '5',
  `support` enum('0','1') NOT NULL DEFAULT '0',
  `moddet` enum('0','1') NOT NULL DEFAULT '0',
  `modgrunn` text NOT NULL,
  `modav` varchar(25) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `regip` varchar(20) NOT NULL,
  `reghostname` varchar(255) NOT NULL,
  `hostname` varchar(255) NOT NULL,
  `lastactive` bigint(20) NOT NULL DEFAULT '0',
  `forceout` enum('0','1') NOT NULL DEFAULT '0',
  `lastdato` int(10) NOT NULL,
  `regdato` int(10) NOT NULL,
  `Extrainfo` longtext COMMENT 'Info pÃ¥ spiller notert av Ledelsen',
  `note` longtext NOT NULL,
  `vervetav` int(11) NOT NULL,
  `kodehem` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`),
  KEY `kuler` (`bullets`),
  KEY `poeng` (`points`),
  KEY `Liv` (`health`),
  KEY `Stilling` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;


ALTER TABLE `update` ADD FULLTEXT KEY `reason` (`reason`);

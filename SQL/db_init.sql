/**
  This file will create all necessary tables in the database that is defined by docker-compose.yml.
  If not changed, it'll be "mafia".
 */

CREATE TABLE `users`
(
    `id`          int                                            NOT NULL AUTO_INCREMENT,
    `user`        varchar(20)                                    NOT NULL,
    `pass`        varchar(255)                                   NOT NULL,
    `mail`        varchar(255)                                   NOT NULL,
    `image`       varchar(255)                                   NOT NULL DEFAULT '/imgs/nopic.png',
    `profile`     text,
    `family`      int                                                     DEFAULT NULL,
    `bank`        bigint                                         NOT NULL DEFAULT '0',
    `hand`        bigint                                         NOT NULL DEFAULT '0',
    `city`        enum ('1','2','3','4','5','6','7','8')         NOT NULL DEFAULT '1',
    `weapon`      enum ('0','1','2','3','4','5','6','7','8','9') NOT NULL DEFAULT '0',
    `bullets`     int                                            NOT NULL DEFAULT '0',
    `health`      int                                            NOT NULL DEFAULT '100',
    `points`      bigint                                         NOT NULL DEFAULT '0',
    `exp`         decimal(10, 3)                                 NOT NULL DEFAULT '0.000',
    `status`      enum ('1','2','3','4','5')                     NOT NULL DEFAULT '4' COMMENT '1=adm,2=mod,3=forummod,4=user,5=NPC',
    `support`     enum ('0','1')                                 NOT NULL DEFAULT '0',
    `ip`          varchar(50)                                             DEFAULT NULL,
    `regip`       varchar(20)                                             DEFAULT NULL,
    `hostname`    varchar(255)                                            DEFAULT NULL,
    `reghostname` varchar(255)                                            DEFAULT NULL,
    `lastactive`  bigint                                         NOT NULL DEFAULT '0',
    `forceout`    bit(1)                                         NOT NULL DEFAULT b'0',
    `regstamp`    int                                            NOT NULL,
    `picmaker`    bit(1)                                         NOT NULL DEFAULT b'0',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 2
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

/**
  Password is invalid on purpose. To create account, run ./setup/setup.php and create admin user.
 */
INSERT INTO `users`
VALUES (1, 'System', 'system', 'nomail@nodomain.no', '/imgs/nopic.png', NULL, NULL, 0, 0, '1', '0', 0, 100, 0, 0.000,
        '5', '0', NULL, NULL, NULL, NULL, 0, _binary '\0', 1759142109, _binary '\0');

CREATE TABLE `bank_transfer`
(
    `id`        bigint NOT NULL AUTO_INCREMENT,
    `uid`       int    NOT NULL,
    `tid`       int    NOT NULL,
    `timestamp` int    NOT NULL,
    `sum`       int    NOT NULL,
    PRIMARY KEY (`id`),
    KEY `bank_transfer_ibfk_1` (`uid`),
    CONSTRAINT `bank_transfer_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE `banklogg`
(
    `id`        bigint       NOT NULL AUTO_INCREMENT,
    `amount`    varchar(255) NOT NULL,
    `way`       varchar(255) NOT NULL,
    `all`       varchar(255) NOT NULL,
    `timestamp` int          NOT NULL,
    `uid`       int          NOT NULL,
    PRIMARY KEY (`id`),
    KEY `banklogg_ibfk_1` (`uid`),
    CONSTRAINT `banklogg_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE `banlog`
(
    `id`        int            NOT NULL AUTO_INCREMENT,
    `uid`       int            NOT NULL,
    `timestamp` bigint         NOT NULL,
    `active`    enum ('0','1') NOT NULL DEFAULT '1',
    `reason`    mediumtext,
    `banner`    int            NOT NULL,
    PRIMARY KEY (`id`),
    KEY `banlog_ibfk_1` (`uid`),
    KEY `banlog_ibfk_2` (`banner`),
    CONSTRAINT `banlog_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`),
    CONSTRAINT `banlog_ibfk_2` FOREIGN KEY (`banner`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE `bjtables`
(
    `id`        int            NOT NULL AUTO_INCREMENT,
    `uid`       int            NOT NULL,
    `ucards`    text,
    `dcards`    text,
    `decks`     text,
    `timestamp` bigint                  DEFAULT NULL,
    `price`     bigint                  DEFAULT NULL,
    `active`    enum ('0','1') NOT NULL DEFAULT '1',
    `result`    bigint         NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `bjtables_ibfk_1` (`uid`),
    CONSTRAINT `bjtables_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE `block`
(
    `id`        bigint NOT NULL AUTO_INCREMENT,
    `uid`       int    NOT NULL,
    `bid`       int    NOT NULL,
    `timestamp` bigint DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `block_ibfk_1` (`uid`),
    KEY `block_ibfk_2` (`bid`),
    CONSTRAINT `block_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`),
    CONSTRAINT `block_ibfk_2` FOREIGN KEY (`bid`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE `bunkerinv`
(
    `id`        int            NOT NULL AUTO_INCREMENT,
    `uid`       int            NOT NULL,
    `tid`       int            NOT NULL,
    `timestamp` bigint         NOT NULL,
    `accepted`  enum ('0','1') NOT NULL,
    `timeleft`  bigint                  DEFAULT NULL,
    `length`    bigint         NOT NULL,
    `used`      enum ('0','1') NOT NULL DEFAULT '0',
    `declined`  enum ('0','1') NOT NULL DEFAULT '0',
    `gone`      enum ('0','1') NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `bunkerinv_ibfk_1` (`uid`),
    KEY `bunkerinv_ibfk_2` (`tid`),
    CONSTRAINT `bunkerinv_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`),
    CONSTRAINT `bunkerinv_ibfk_2` FOREIGN KEY (`tid`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE `cars`
(
    `id`         int                                             NOT NULL AUTO_INCREMENT,
    `choice`     varchar(255)                                    NOT NULL,
    `levelmin`   int                                             NOT NULL DEFAULT '1',
    `minval`     bigint                                          NOT NULL DEFAULT '0',
    `maxval`     bigint                                          NOT NULL DEFAULT '0',
    `bilmin`     enum ('1','2','3','4','5','6','7','8','9','10') NOT NULL DEFAULT '1',
    `bilmax`     enum ('1','2','3','4','5','6','7','8','9','10') NOT NULL DEFAULT '1',
    `timewait`   bigint                                          NOT NULL DEFAULT '60',
    `expgain`    decimal(10, 3)                                  NOT NULL DEFAULT '0.000',
    `punishtime` bigint                                          NOT NULL DEFAULT '60',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 3
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;
INSERT INTO `cars`
VALUES (1, 'Rapp en bil', 1, 10, 100, '1', '2', 3, 1.000, 15),
       (2, 'Rapp en større bil', 2, 20, 200, '2', '3', 6, 2.000, 30);

CREATE TABLE `carslog`
(
    `id`        bigint         NOT NULL AUTO_INCREMENT,
    `uid`       int            NOT NULL,
    `choice`    int                     DEFAULT NULL,
    `timestamp` bigint         NOT NULL,
    `result`    enum ('0','1') NOT NULL DEFAULT '0',
    `timewait`  bigint         NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `carslog_ibfk_1` (`uid`),
    CONSTRAINT `carslog_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE `chance`
(
    `id`     int NOT NULL AUTO_INCREMENT,
    `uid`    int NOT NULL,
    `type`   int NOT NULL,
    `option` int NOT NULL,
    `chance` int NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `chance_ibfk_1` (`uid`),
    CONSTRAINT `chance_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE `chat`
(
    `id`        int          NOT NULL AUTO_INCREMENT,
    `uid`       int          NOT NULL,
    `message`   varchar(255) NOT NULL,
    `timestamp` bigint       NOT NULL,
    PRIMARY KEY (`id`),
    KEY `chat_ibfk_1` (`uid`),
    CONSTRAINT `chat_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE `crime`
(
    `id`          int            NOT NULL AUTO_INCREMENT,
    `description` mediumtext,
    `minval`      bigint         NOT NULL DEFAULT '1',
    `maxval`      bigint         NOT NULL DEFAULT '10',
    `expgain`     decimal(10, 3) NOT NULL DEFAULT '0.000',
    `untilnext`   bigint         NOT NULL DEFAULT '10',
    `punishtime`  bigint         NOT NULL DEFAULT '60',
    `levelmin`    int            NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 8
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;
INSERT INTO `crime`
VALUES (1, 'Stjel en lommebok fra en dude', 0, 500, 0.567, 1, 1, 1),
       (2, 'Napp veska til en kjendis', 500, 2500, 0.678, 10, 5, 2),
       (3, 'Ran en kiosk', 1000, 5000, 0.789, 45, 10, 3),
       (4, 'Gå til en dør og be om penger', 2500, 10000, 1.123, 60, 15, 4),
       (5, 'Ran en bankautomat', 5000, 8000, 1.234, 80, 30, 5),
       (6, 'Lur de eldre på internett med BitCoins', 10000, 25000, 2.222, 105, 40, 6),
       (7, 'Snik deg inn på et museum', 25000, 1000000, 4.500, 150, 60, 7);

CREATE TABLE `firms`
(
    `id`   int NOT NULL AUTO_INCREMENT,
    `uid`  int NOT NULL,
    `bank` int NOT NULL DEFAULT '0',
    `city` int          DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `firms_ibfk_1` (`uid`),
    CONSTRAINT `firms_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE `flight_log`
(
    `id`        int    NOT NULL AUTO_INCREMENT,
    `uid`       int    NOT NULL,
    `timestamp` bigint NOT NULL,
    `from_city` enum ('0','1','2','3','4','5','6','7','8') DEFAULT '0',
    `to_city`   enum ('0','1','2','3','4','5','6','7','8') DEFAULT '0',
    `price`     bigint                                     DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `flight_log_ibfk_1` (`uid`),
    CONSTRAINT `flight_log_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE `forumban`
(
    `id`        int            NOT NULL AUTO_INCREMENT,
    `uid`       int            NOT NULL,
    `active`    enum ('1','0') NOT NULL DEFAULT '1',
    `timestamp` bigint         NOT NULL,
    `bantime`   bigint         NOT NULL,
    `banner`    int            NOT NULL,
    `reason`    longtext       NOT NULL,
    PRIMARY KEY (`id`),
    KEY `forumban_ibfk_1` (`uid`),
    KEY `forumban_ibfk_2` (`banner`),
    CONSTRAINT `forumban_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`),
    CONSTRAINT `forumban_ibfk_2` FOREIGN KEY (`banner`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE `garage`
(
    `id`           int            NOT NULL AUTO_INCREMENT,
    `car_id`       int            NOT NULL,
    `uid`          int            NOT NULL,
    `stolen_city`  int            NOT NULL,
    `transferred`  bigint         NOT NULL DEFAULT '0',
    `current_city` int            NOT NULL,
    `sold`         enum ('0','1') NOT NULL DEFAULT '0',
    `timestamp`    bigint         NOT NULL,
    PRIMARY KEY (`id`),
    KEY `garage_ibfk_1` (`uid`),
    CONSTRAINT `garage_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE `invsjekk`
(
    `id`        int            NOT NULL AUTO_INCREMENT,
    `mail`      varchar(255)   NOT NULL,
    `code`      varchar(255)   NOT NULL,
    `ip`        varchar(100)   NOT NULL,
    `timestamp` bigint         NOT NULL,
    `used`      enum ('0','1') NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE `ipban`
(
    `id`        int     NOT NULL AUTO_INCREMENT,
    `ip`        int     NOT NULL,
    `active`    tinyint NOT NULL DEFAULT '1',
    `timestamp` bigint  NOT NULL,
    `reason`    mediumtext,
    `banner`    int     NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `ip_UNIQUE` (`ip`),
    KEY `ipban_ibfk_1` (`banner`),
    CONSTRAINT `ipban_ibfk_1` FOREIGN KEY (`banner`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE `jail`
(
    `id`        int          NOT NULL AUTO_INCREMENT,
    `uid`       int          NOT NULL,
    `reason`    varchar(100) NOT NULL,
    `timestamp` bigint       NOT NULL,
    `timeleft`  bigint       NOT NULL,
    `priceout`  bigint       NOT NULL DEFAULT '0',
    `breaker`   int                   DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `jail_ibfk_1` (`uid`),
    KEY `jail_ibfk_2` (`breaker`),
    CONSTRAINT `jail_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`),
    CONSTRAINT `jail_ibfk_2` FOREIGN KEY (`breaker`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE `krimlogg`
(
    `id`        bigint  NOT NULL AUTO_INCREMENT,
    `uid`       int     NOT NULL,
    `timestamp` bigint  NOT NULL,
    `crime`     tinyint NOT NULL DEFAULT '0',
    `result`    bigint  NOT NULL DEFAULT '0',
    `timewait`  bigint  NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `krimlogg_ibfk_1` (`uid`),
    CONSTRAINT `krimlogg_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE `lottery`
(
    `id`             int NOT NULL AUTO_INCREMENT,
    `round`          int DEFAULT NULL,
    `timestamp`      int DEFAULT NULL,
    `winnerid`       int DEFAULT NULL,
    `timestartstamp` int DEFAULT NULL,
    `pl`             int DEFAULT NULL,
    `pr`             int DEFAULT NULL,
    `ti`             int DEFAULT NULL,
    `al`             int DEFAULT NULL,
    `pot`            int DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `lottery_ibfk_1` (`winnerid`),
    CONSTRAINT `lottery_ibfk_1` FOREIGN KEY (`winnerid`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE `lotteryconfig`
(
    `id`          int NOT NULL AUTO_INCREMENT,
    `timestamp`   int DEFAULT NULL,
    `percentage`  int DEFAULT NULL,
    `ticketprice` int DEFAULT NULL,
    `numticks`    int DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE `lotterytickets`
(
    `id`    int NOT NULL AUTO_INCREMENT,
    `round` int NOT NULL,
    `uid`   int NOT NULL,
    PRIMARY KEY (`id`),
    KEY `lotterytickets_ibfk_1` (`uid`),
    CONSTRAINT `lotterytickets_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE `mails`
(
    `id`        bigint             NOT NULL AUTO_INCREMENT,
    `uid`       int                NOT NULL,
    `tid`       int                NOT NULL,
    `title`     varchar(25)                 DEFAULT NULL,
    `message`   text,
    `timestamp` bigint             NOT NULL,
    `opened`    enum ('0','1')     NOT NULL DEFAULT '0',
    `type`      enum ('0','1','2') NOT NULL DEFAULT '0',
    `deleted`   enum ('0','1')     NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `mails_ibfk_1` (`uid`),
    KEY `mails_ibfk_2` (`tid`),
    CONSTRAINT `mails_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`),
    CONSTRAINT `mails_ibfk_2` FOREIGN KEY (`tid`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE `news`
(
    `id`        int                        NOT NULL AUTO_INCREMENT,
    `title`     varchar(60)                NOT NULL,
    `text`      mediumtext                 NOT NULL,
    `author`    int                        NOT NULL,
    `userlevel` enum ('1','2','3','4','5') NOT NULL DEFAULT '5',
    `timestamp` bigint                     NOT NULL,
    `showing`   tinyint                    NOT NULL DEFAULT '1',
    PRIMARY KEY (`id`),
    KEY `news_ibfk_1` (`author`),
    CONSTRAINT `news_ibfk_1` FOREIGN KEY (`author`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

/**
  Insert a default news to greet creator / admin
 */
INSERT INTO `mafia`.`news`(`title`, `text`, `author`, `timestamp`)
    VALUE ('Welcome to the game!',
           'This is a fresh copy of the game.\nFeel free to do with it as you please.',
           '1', unix_timestamp());

CREATE TABLE `resetpasset`
(
    `id`        bigint         NOT NULL AUTO_INCREMENT,
    `uid`       int            NOT NULL,
    `resgen`    bigint         NOT NULL,
    `timestamp` bigint                  DEFAULT NULL,
    `used`      enum ('0','1') NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `resetpasset_ibfk_1` (`uid`),
    CONSTRAINT `resetpasset_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE `rob_log`
(
    `id`            int        NOT NULL AUTO_INCREMENT,
    `uid`           int        NOT NULL,
    `tid`           int        NOT NULL,
    `ucity`         int        NOT NULL,
    `tcity`         int        NOT NULL,
    `result`        tinyint(1) NOT NULL,
    `robbed_amount` bigint     NOT NULL,
    `timestamp`     bigint     NOT NULL,
    PRIMARY KEY (`id`),
    KEY `rob_log_ibfk_1` (`uid`),
    KEY `rob_log_ibfk_2` (`tid`),
    CONSTRAINT `rob_log_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`),
    CONSTRAINT `rob_log_ibfk_2` FOREIGN KEY (`tid`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE `robbery`
(
    `id`        int    NOT NULL AUTO_INCREMENT,
    `uid`       int    NOT NULL,
    `tid`       int             DEFAULT NULL,
    `timestamp` bigint NOT NULL,
    `ucity`     int    NOT NULL,
    `tcity`     int    NOT NULL,
    `amount`    bigint NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `robbery_ibfk_1` (`uid`),
    KEY `robbery_ibfk_2` (`tid`),
    CONSTRAINT `robbery_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`),
    CONSTRAINT `robbery_ibfk_2` FOREIGN KEY (`tid`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE `selfedit`
(
    `id`          int            NOT NULL AUTO_INCREMENT,
    `uid`         int            NOT NULL,
    `bank_old`    bigint         NOT NULL DEFAULT '0',
    `bank_new`    bigint         NOT NULL DEFAULT '0',
    `exp_old`     decimal(10, 3) NOT NULL DEFAULT '0.000',
    `exp_new`     decimal(10, 3) NOT NULL DEFAULT '0.000',
    `hand_old`    bigint         NOT NULL DEFAULT '0',
    `hand_new`    bigint         NOT NULL DEFAULT '0',
    `bullets_old` bigint         NOT NULL DEFAULT '0',
    `bullets_new` bigint         NOT NULL DEFAULT '0',
    `points_old`  bigint         NOT NULL DEFAULT '0',
    `points_new`  bigint         NOT NULL DEFAULT '0',
    `timestamp`   bigint         NOT NULL,
    PRIMARY KEY (`id`),
    KEY `selfedit_ibfk_1` (`uid`),
    CONSTRAINT `selfedit_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE `sessions`
(
    `id`         int    NOT NULL AUTO_INCREMENT,
    `uid`        int    NOT NULL,
    `user_agent` tinytext,
    `user_ip`    bigint NOT NULL,
    `timestamp`  bigint NOT NULL,
    PRIMARY KEY (`id`),
    KEY `sessions_ibfk_1` (`uid`),
    CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE `stillingslogg`
(
    `id`         int NOT NULL AUTO_INCREMENT,
    `uid`        int DEFAULT NULL COMMENT 'The user setting the status',
    `pid`        int DEFAULT NULL COMMENT 'The user affected by change',
    `old_status` int DEFAULT NULL COMMENT 'The old status of affected user',
    `new_status` int DEFAULT NULL COMMENT 'The new status of affected user',
    `timestamp`  int DEFAULT NULL COMMENT 'When it was executed',
    PRIMARY KEY (`id`),
    KEY `stillingslogg_ibfk_1` (`uid`),
    CONSTRAINT `stillingslogg_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE `support`
(
    `id`            bigint                 NOT NULL AUTO_INCREMENT,
    `uid`           int                    NOT NULL,
    `topic`         varchar(20)            NOT NULL,
    `issue_type`    enum ('0','1','2','3') NOT NULL DEFAULT '1',
    `text`          longtext               NOT NULL,
    `timestamp`     bigint                 NOT NULL,
    `handled`       enum ('0','1')         NOT NULL DEFAULT '0',
    `handler`       int                             DEFAULT NULL,
    `handler_reply` longtext,
    `removed`       enum ('0','1')         NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`),
    KEY `support_ibfk_1` (`uid`),
    KEY `support_ibfk_2` (`handler`),
    CONSTRAINT `support_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`id`),
    CONSTRAINT `support_ibfk_2` FOREIGN KEY (`handler`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_0900_ai_ci;

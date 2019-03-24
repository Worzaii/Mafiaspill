<?php
/*Tanken med denne filen er &aring; sette opp evt. renter til midnatt eller annet gitt tidspunkt*/
define('THRUTT', "Sperrederrp!");
    include("classes/class.php");
    $db = new database;
    $db->configure();
    $db->connect();
    $db->query("INSERT INTO `chat`(`id`,`uid`,`mld`,`time`) VALUES (NULL,'0','Dette er en automatisert melding laget igjennom crontab i Ubuntu! I smell rents coming up!',UNIX_TIMESTAMP())");
    $db->disconnect();
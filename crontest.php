<?php
/*Tanken med denne filen er å sette opp evt. renter til midnatt eller annet gitt tidspunkt*/
define('THRUTT', "Sperredørp!");
    include("classes/class.php");
    $db = new database;
    $db->configure();
    $db->connect();
    $db->query("INSERT INTO `chat`(`id`,`uid`,`mld`,`time`) VALUES (NULL,'0','Dette er en automatisert melding laget igjennom crontab i Debian! I smell rents coming up!','".time()."')");
    $db->disconnect();
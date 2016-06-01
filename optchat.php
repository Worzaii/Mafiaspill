<?php
define("THRUTT", "Sperredørp!");
include '../classes/class.php';
$db = new database();
$db->configure();
$db->connect();
$db->query("INSERT INTO `chat`(`uid`,`mld`,`time`) VALUES('0','".  utf8_encode("Tester ut cronjobs i debian, æøå nicholas.gs.gresø")."',UNIX_TIMESTAMP())");
echo "true";

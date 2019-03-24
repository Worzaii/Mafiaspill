<?php
define("THRUTT", "Sperred&oslash;rp!");
include '../classes/class.php';
$db = new database();
$db->configure();
$db->connect();
$db->query("INSERT INTO `chat`(`uid`,`mld`,`time`) VALUES('0','".  utf8_encode("Tester ut cronjobs i debian, &aelig;&oslash;&aring; nicholas.gs.gres&oslash;")."',UNIX_TIMESTAMP())");
echo "true";

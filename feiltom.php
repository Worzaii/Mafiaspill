<?php
include("core.php");
$han = fopen("feil.txt", "w");
fwrite($han, utf8_encode("feil.txt ble sist t&oslash;mt ".date("H:i:s d.m.y").' av '.$obj->user));
fclose($han);
header("Location: feilvis.php");
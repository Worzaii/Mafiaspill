<?php
session_start();
echo "<h1>Hey!</h1>";
echo "<p>Your session lang is: " . $_SESSION["lang"] . "</p>";
foreach ($_SESSION as $key => $value) {
    echo "<p>$key: $value</p>";
}
echo "<hr>";
/* Needs this in php.ini file:
* extension=intl
*/
echo "Closest accepted language is: " . Locale::acceptFromHttp($_SERVER['HTTP_ACCEPT_LANGUAGE']);
/*foreach ($_SERVER as $key => $value) {
    echo "<p>$key: $value</p>";
}*/

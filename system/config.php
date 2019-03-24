<?php
if (!defined('BASEPATH')) {
    die('Ingen tilgang!');
}

function safegen($u, $p)
{
    $ua = $_SERVER["HTTP_USER_AGENT"];
    $l  = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
    $i  = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : NULL;
    return md5(sha1($u.$p.$ua.$l.$i."f9f9¤Sss9"));
}

function gen($t)
{
    return str_replace(['&aelig;', '&oslash;', '&aring;', 'Æ', 'Ø', '&aring;'],
        ['&aelig;', '&oslash;', '&aring;', '&Aelig;', '&Oslash;', '&Aring;'], $t);
}
/* Config must be loaded before any other scripts, this must be defined and correct */
define('THRUTT', "Sperrederrp!");
define('DOMENE_NAVN', 'mafia.werzaire.net');
define('NAVN_DOMENE', 'Werzaire.net');
define('MAIL_SENDER', 'werzaire.net');
define('UTVIKLER', 'Nicholas Arnesen');
define("HENVEND_MAIL", "henvendelser@".DOMENE_NAVN);
define("HENVEND_MAIL_SAFE", str_replace([".", "@"], ["[dot]", "[at]"], HENVEND_MAIL));

/* Configuration */
#ini_set('session.use_cookies', 0);
#ini_set('session.use_only_cookies', 0);
ini_set('session.cookie_secure', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.gc_maxlifetime', 3600);
session_set_cookie_params(3600, "/", DOMENE_NAVN, TRUE, FALSE);
ini_set('memory_limit', '32M');
date_default_timezone_set('Europe/Oslo');
ini_set("date.timezone", "Europe/Oslo");
ini_set('max_execution_time', 15);
date_default_timezone_set("Europe/Oslo");
ini_set("date.timezone", "Europe/Oslo");
ini_set("error_log", "/var/www/mafia.werzaire.net/logs/error.log");

/* Error-reporting
 * Everything should just be logged to the file mentioned above...
 */
ini_set('error_reporting', E_ALL);
ini_set('display_errors', FALSE);
ini_set('display_startup_errors', FALSE);

/* Starting session */
session_name("TheGame");
session_start();
header("Content-Type: text/html; charset=UTF-8");
if (isset($_SESSION['HTTP_USER_AGENT'])) {
    if ($_SESSION['HTTP_USER_AGENT'] !== sha1($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR'])) {
        header('Location: https://'.DOMENE_NAVN.'loggut.php?g=8');
        exit('Cross-network-tilgang avsl&aring;tt!');
    }
} else {
    $_SESSION['HTTP_USER_AGENT'] = sha1($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']);
}
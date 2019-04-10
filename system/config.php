<?php
if (!defined('BASEPATH')) {
    die('Ingen tilgang!');
}

function safegen($u, $p)
{
    $ua = $_SERVER["HTTP_USER_AGENT"];
    $l = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
    $i = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : null;
    return md5(sha1($u . $p . $ua . $l . $i . "g9gr90eGR"));
}

function gen($t)
{
    return str_replace(
        ['æ', 'ø', 'å', 'Æ', 'Ø', 'Å'],
        ['&aelig;', '&oslash;', '&aring;', '&Aelig;', '&Oslash;', '&Aring;'],
        $t
    );
}

/* Config must be loaded before any other scripts, this must be defined and correct */
define('THRUTT', "Sperrederrp!");
define('DOMENE_NAVN', 'mafia.werzaire.net');
define('NAVN_DOMENE', 'Werzaire.net');
define('MAIL_SENDER', 'werzaire.net');
define('UTVIKLER', 'Nicholas Arnesen');
define('DESC', 'Kommer senere...');
define('KEYWORDS', 'mafia, spill');
define("HENVEND_MAIL", "henvendelser@" . DOMENE_NAVN);
define("HENVEND_MAIL_SAFE", str_replace([".", "@"], ["[dot]", "[at]"], HENVEND_MAIL));

/* Configuration */
#ini_set('session.use_cookies', 0);
#ini_set('session.use_only_cookies', 0);
ini_set('session.cookie_secure', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.gc_maxlifetime', 60 * 60 * 24); /* Implementing a temporary longer time for development purposes */
session_set_cookie_params(60 * 60 * 24, "/", DOMENE_NAVN, true, false);
ini_set('memory_limit', '32M');
date_default_timezone_set('Europe/Oslo');
ini_set("date.timezone", "Europe/Oslo");
ini_set('max_execution_time', 15);
date_default_timezone_set("Europe/Oslo");
ini_set("date.timezone", "Europe/Oslo");

/* Error-reporting
 * Everything should just be logged to the file mentioned below...
 */
ini_set("error_log", "/var/www/mafia.werzaire.net/logs/error.log");
ini_set('error_reporting', E_ALL);
ini_set('display_errors', false);
ini_set('display_startup_errors', false);

/* Starting session */
session_name("TheGame");
session_start();
header("Content-Type: text/html; charset=UTF-8");
if (isset($_SESSION['HTTP_USER_AGENT'])) {
    if ($_SESSION['HTTP_USER_AGENT'] !== sha1($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR'])) {
        header('Location: https://' . DOMENE_NAVN . 'loggut.php?g=8');
        exit('Cross-network-tilgang avsl&aring;tt!');
    }
} else {
    $_SESSION['HTTP_USER_AGENT'] = sha1($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);
}

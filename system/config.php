<?php

function safegen($u, $p)
{
    global $_SERVER;
    $ua = $_SERVER["HTTP_USER_AGENT"];
    $l  = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
    $i  = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : NULL;
    return md5(sha1($u.$p.$ua.$l.$i."f9f9¤Sss9"));
}

function gen($t)
{
    return str_replace(['æ', 'ø', 'å', 'Æ', 'Ø', 'Å'], ['&aelig;', '&oslash;', '&aring;', '&Aelig;', '&Oslash;', '&Aring;'],
        $t);
}
if (!defined('BASEPATH')) {
    die('Ingen tilgang!');
}
$sefeil = 1;
define('THRUTT', "Sperredørp!"); /* Sikkerhetsinnlegg for blokkering av oppdelte scripts */
define('DOMENE_NAVN', 'mafia.werzaire.net');
define('NAVN_DOMENE', 'Werzaire.net');
define('MAIL_SENDER', 'werzaire.net');
define("HENVEND_MAIL", "henvendelser@".DOMENE_NAVN);
define("HENVEND_MAIL_SAFE", str_replace([".", "@"], ["[dot]", "[at]"], HENVEND_MAIL));
/* Konfigurasjon */
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_strict_mode', 1);
session_set_cookie_params(1800, NULL, DOMENE_NAVN, TRUE, FALSE);
ini_set('memory_limit', '32M');
date_default_timezone_set('Europe/Oslo');
ini_set("date.timezone", "Europe/Oslo");
ini_set('max_execution_time', 15);
date_default_timezone_set("Europe/Oslo");
ini_set("date.timezone", "Europe/Oslo");

/* Error-reporting */
ini_set('error_reporting', (($sefeil == 1) ? E_ALL : 0));
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
/* Error-reporting END */

session_name(NAVN_DOMENE);
session_start();
header("Content-Type: text/html; charset=UTF-8");
if (isset($_SESSION['HTTP_USER_AGENT'])) {
    if ($_SESSION['HTTP_USER_AGENT'] !== sha1($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR'])) {
        header('Location: https://'.DOMENE_NAVN.'loggut.php?g=8');
        exit('Cross-network-tilgang avslått!');
    }
} else {
    $_SESSION['HTTP_USER_AGENT'] = sha1($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']);
}
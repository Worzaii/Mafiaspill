<?php

if (isset($_SERVER['HTTPS']) &&
    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
    $protocol = 'https://';
} else {
    $protocol = 'http://';
}

define("PROTOCOL", $protocol);
if (!defined('BASEPATH')) {
    die('Ingen tilgang!');
}
if (isset($_SERVER["DEV"]) && $_SERVER["DEV"] == 1) {
    define("DEV_USERNAME", $_SERVER['DEV_USERNAME']);
    define("DEV_PASSWORD", $_SERVER['DEV_PASSWORD']);
}
function SqlLog($query): void
{
    if (defined("LOCALWRITE")) {
        $file = LOCALWRITE;
    } elseif ($_SERVER['SERVER_NAME'] == "mafia.localhost.localdomain") {
        $file = $_SERVER["DOCUMENT_ROOT"] . "\\logs\\sql.log";
    } else {
        $file = "/var/www/html/logs/sql.log";
    }
    $f = fopen($file, "a+");
    fwrite($f, date("[d-M-Y H:i:s e] ") . $query . "\n");
    fclose($f);
}

function safegen($u, $p): string
{
    $ua = $_SERVER["HTTP_USER_AGENT"];
    #$l = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
    $i = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : null;
    return md5(sha1($u . $p . $ua . $i . "tissbajs194\""));
}

function CodeGen($length = 12): string
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $string = null;
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }
    return $string;
}

/* Config must be loaded before any other scripts, this must be defined and correct */
const THRUTT = "Sperrederrp!";
if (empty($_SERVER["SERVER_NAME"])) {
    define('DOMENE_NAVN', "localhost.localdomain");
} else {
    define(
        'DOMENE_NAVN',
        $_SERVER["SERVER_NAME"]
    ); /* Name is fetched from the nginx configuration*/
}
const NAVN_DOMENE = 'Mafiaspill';
const MAIL_SENDER = 'mafiaspill';
const UTVIKLER = 'Nicholas Arnesen';
const DESC = 'Kommer senere...';
const KEYWORDS = 'mafia, spill';
const HENVEND_MAIL = "henvendelser@" . DOMENE_NAVN;
define("HENVEND_MAIL_SAFE", str_replace([".", "@"], ["[dot]", "[at]"], HENVEND_MAIL));
if (php_sapi_name() != "cli") {
    define("WWWPATH", PROTOCOL . $_SERVER["HTTP_HOST"]);
}
$timeout = (60 * (120));

/* Database connection parametres START */
const HOST = "mysql";
const DATABASE = "mafia";
const USERNAME = "mafia";
const PASSWORD = "mafia";
/* Database connection parametres END */

/* Starting session */
if (php_sapi_name() != "cli") {
    session_start();
    if (isset($_SESSION['HTTP_USER_AGENT'])) {
        if ($_SESSION['HTTP_USER_AGENT'] !== sha1($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR'])) {
            error_log("Seems the agent isn't correctly defined. Here's the comparements:");
            error_log("Session value: '" . $_SESSION['HTTP_USER_AGENT'] . "' and the value it's compared to: ");
            error_log("Server-value:  '" . $_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR'] . "'");
            error_log("Removing the information so it can be set anew...");
            unset($_SESSION['HTTP_USER_AGENT']);
            error_log("Result of removal: " . ((isset($_SESSION['HTTP_USER_AGENT']) ? "Failed" : "Successful")));
            header('Location: ' . PROTOCOL . DOMENE_NAVN . '/loggut.php?g=8');
            exit('Cross-network-tilgang avslått!');
        }
    } else {
        $_SESSION['HTTP_USER_AGENT'] = sha1($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);
    }
}

<?php

if (!defined('BASEPATH')) {
    die('Ingen tilgang!');
}
if (isset($_SERVER['DEV']) && $_SERVER['DEV'] == 1) {
    //error_log("Development mode is enabled from the fastcgi_params in your nginx setup!");
    if (!isset($_SERVER['LOGLOCATION']) /*||
        !isset($_SERVER['LOGLOCATION'])*/) {
        throw new Exception('DEV env is active but is missing important values.
        Please see that LOGLOCATION is defined in either php.ini or passed by nginx/apache');
    }
    ini_set('display_errors', "on");
    ini_set('display_startup_errors', "on");
    ini_set('error_reporting', "32767");
    ini_set('log_errors', "on");
    ini_set('track_errors', "on");
    ini_set('html_errors', "on");
    ini_set('error_log', $_SERVER['LOGLOCATION']);
    define(
        'LOCALWRITE',
        $_SERVER['LOGLOCATION']
    ); /* Inserts nginx-defined location, see fastcgi_param LOGLOCATION "REPLACEPATH"; */
}

function safegen($u, $p)
{
    $ua = $_SERVER["HTTP_USER_AGENT"];
    #$l = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
    $i = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : null;
    return md5(sha1($u . $p . $ua . $i . "ra\""));
}

function codegen($length = 12)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $string = null;
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters))];
    }
    return $string;
}

/* Config must be loaded before any other scripts, this must be defined and correct */
const THRUTT = 'Sperrederrp!';
if (empty($_SERVER["SERVER_NAME"])) {
    define('DOMENE_NAVN', "localhost.localdomain");
} else {
    define(
        'DOMENE_NAVN',
        $_SERVER["SERVER_NAME"]
    ); /* Name is fetched from the nginx configuration*/
}
const NAVN_DOMENE = 'Werzaire.net';
const MAIL_SENDER = 'werzaire.net';
const UTVIKLER = 'Nicholas Arnesen';
const DESC = 'Kommer senere...';
const KEYWORDS = 'mafia, spill';
define("HENVEND_MAIL", ((isset($_SERVER['mailto'])) ? $_SERVER['mailto'] : "root@localhost.localdomain"));
define("HENVEND_MAIL_SAFE", str_replace([".", "@"], ["[dot]", "[at]"], HENVEND_MAIL));
if (php_sapi_name() != "cli") {
    define("WWWPATH", "https://" . $_SERVER["HTTP_HOST"]);
}
const TIMEOUT = 7200;

/* Database connection parametres START */
const HOST = "localhost";
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
            header('Location: https://' . DOMENE_NAVN . '/loggut.php?g=8');
            exit('Cross-network-tilgang avslått!');
        }
    } else {
        $_SESSION['HTTP_USER_AGENT'] = sha1($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);
    }
}

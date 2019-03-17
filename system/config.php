<?php
if (!defined('BASEPATH')) {
    die('Ingen tilgang!');
}
/* if(isset($_SERVER['DOMAIN_NAME']) && $_SERVER['DOMAIN_NAME'] != "mafia-no.net"){
  die('Feil domene!');
  }
  if(isset($_GET['debug']) && ($_GET['debug'] == "secret_code")){
  $sefeil = 1;
  }
  else{
  $sefeil=0;//0=av/1=på
  } */
$sefeil = 1;
define('THRUTT', "Sperredørp!"); /* Sikkerhetsinnlegg for blokkering av oppdelte scripts */
define('DOMENE_NAVN', 'mafia.werzaire.net');
define('NAVN_DOMENE', 'Werzaire.net');
define('MAIL_SENDER', 'werzaire.net');
/* Konfigurasjon */
ini_set('session.use_only_cookies', 1);
ini_set('memory_limit', '32M');
ini_set('mysql.connect_timeout', 5);
date_default_timezone_set('Europe/Oslo');
ini_set("date.timezone", "Europe/Oslo");
//ini_set('max_execution_time', 0);
//ini_set("session.save_path", "/tmp");
ini_set('session.cookie_httponly', true);
date_default_timezone_set("Europe/Oslo");
ini_set("date.timezone", "Europe/Oslo");

/* Error-reporting */
ini_set('error_reporting', (($sefeil == 1) ? E_ALL : 0));
ini_set('display_errors', "Yes");
ini_set('display_startup_errors', "Yes");

/* Error-reporting END */

session_name(NAVN_DOMENE); /* Setter egen session-name ftw */

//session_set_cookie_params(3600, '/', 'mafia-no.net', FALSE, TRUE);/*Får session til å vare i 30 minutter istedenfor 20 min*/
/* Konfigurasjon END */
function safegen($u, $p)
{
    /* Denne funksjonen skal brukes til "alltid innlogget" funksjonen som et sikkerhetstiltak. */
    global $_SERVER;
    $ua = $_SERVER["HTTP_USER_AGENT"];
    //$c = $_SERVER["HTTP_COOKIE"]; /*Kan ikke bruke denne delen da den endrer seg og ødelegger for alltid innloggetfunksjonen*/
    $l  = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
    $i  = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : NULL;
    return md5(sha1($u.$p.$ua.$l.$i."f9f9¤Sss9"));
}
//  header("Content-Type: text/html; charset=UTF-8");
header("Content-Type: text/html; charset=windows-1252");
session_start();
if (isset($_SESSION['HTTP_USER_AGENT'])) {
    if ($_SESSION['HTTP_USER_AGENT'] !== sha1($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR'])) {
        session_destroy();
        header('Location: https://'.DOMENE_NAVN);
        exit('Cross-network-tilgang avslått!');
    }
} else {
    $_SESSION['HTTP_USER_AGENT'] = sha1($_SERVER['HTTP_USER_AGENT'].$_SERVER['REMOTE_ADDR']);
}
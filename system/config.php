<?php
if (!defined('BASEPATH')) {
    die('Ingen tilgang!');
}
function sql_log($query)
{
    if ($_SERVER['SERVER_NAME'] == "mafia.localhost.localdomain") {
        $file = $_SERVER["DOCUMENT_ROOT"] . "\\logs\\sql.log";
    } else {
        $file = "/var/www/mafia.werzaire.net/logs/sql.log";
    }
    $f = fopen($file, "a+");
    fwrite($f, date("[d-M-Y H:i:s e] ") . $query . "\n");
    fclose($f);
}

function safegen($u, $p)
{
    $ua = $_SERVER["HTTP_USER_AGENT"];
    #$l = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
    $i = isset($_SERVER["HTTP_X_FORWARDED_FOR"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : null;
    return md5(sha1($u . $p . $ua . $i . "tissbajs194\""));
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
define('THRUTT', "Sperrederrp!");
if (empty($_SERVER["SERVER_NAME"])) {
    define('DOMENE_NAVN', "localhost.localdomain");
} else {
    define('DOMENE_NAVN',
        $_SERVER["SERVER_NAME"]); /* Name is fetched from the nginx configuration*/
}
define('NAVN_DOMENE', 'Werzaire.net');
define('MAIL_SENDER', 'werzaire.net');
define('UTVIKLER', 'Nicholas Arnesen');
define('DESC', 'Kommer senere...');
define('KEYWORDS', 'mafia, spill');
define("HENVEND_MAIL", "henvendelser@" . DOMENE_NAVN);
define("HENVEND_MAIL_SAFE", str_replace([".", "@"], ["[dot]", "[at]"], HENVEND_MAIL));
define("WWWPATH", "https://" . $_SERVER["HTTP_HOST"]);
$timeout = (60 * (120));

/* Database connection parametres START */
define("HOST", "localhost");
define("DATABASE", "mafia");
define("USERNAME", "mafia");
define("PASSWORD", "mafia");
/* Database connection parametres END */

/* Rest of config has been imported to php.ini file directly. */

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
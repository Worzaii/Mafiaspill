<?php
define("BASEPATH", 1);
include_once './system/config.php';
include_once './classes/Database.php';
include_once './inc/functions.php';
if (isset($_SERVER['X-Requested-With'])) {
    if ($_SERVER['X-Requested-With'] == "XMLHttpRequest") {
        define("JSON", 1);
    } else {
        define("JSON", 0);
    }
} else {
    define("JSON", 0);
}
if (defined("LVL") && LVL == true) {
    $r = '../';
} else {
    $r = null;
}
if (isset($_SESSION['sessionzar'])) {
    $db = new \DatabaseObject\database();
    if (!$db->connect()) {
        die("Kunne ikke koble til db!<br><a href=\"loggut.php?g=2\">Tilbake til innlogging.</a>");
    }
    $db->clock_start();
    list($user, $pass, $sss) = $_SESSION['sessionzar'];
    $ip = (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] . $_SERVER['REMOTE_ADDR']
        : $_SERVER['REMOTE_ADDR'];
    $db->query("SELECT id,user,pass,ip,forceout,lastactive, health, status, image, exp, hand, points, city, family, bullets, weapon, support, profile FROM `users` WHERE `user` = '" . $db->escape($user) . "' AND `pass` = '" . $db->escape($pass) . "'");
    if ($db->num_rows() == 0) {
        header("Location: loggut.php?g=4");
        die('<a href="loggut.php">Det kan se ut som du har blitt logget ut, det er noen andre som har logget p&aring; din bruker.</a>');
    } elseif ($db->num_rows() == 1) {
        $obj = $db->fetch_object();
        if ($obj->ip != $ip) {
            header("Location: loggut.php?g=7&$ip");
            echo '<h1>Det kan se ut som du har blitt logget inn p&aring; et annet nettverk. Klikk her for &aring; g&aring; til innloggingssiden: <a href="loggut.php">Index</a></h1>';
            die();
        }
        liv_check();
        ipbanned($ip);
        if ($obj->forceout == 1) {
            $db->query("UPDATE `users` SET `forceout` = '0' WHERE `id` = '{$obj->id}'");
            die('<a href="loggut.php?g=6">Du har blitt logget ut av en i Ledelsen! Vennligst logg inn p&aring; nytt for &aring; fortsette &aring; spille.</a>');
        }
        if (($obj->lastactive + 1800) < time()) {
            header("Location: loggut.php?g=5");
        } elseif (($obj->lastactive + 1800) > time()) {
            if (defined("NOUPDATE") && NOUPDATE == 1) {
            } else {
                if (!$db->query("UPDATE `users` SET `lastactive` = UNIX_TIMESTAMP() WHERE `id` = '{$obj->id}'")) {
                    if ($obj->status == 1) {
                        die('<p>Kunne ikke sette ny info!<br>' . mysqli_error($db->con) . '</p>');
                    } else {
                        die('<p>Det har oppst&aring;tt en feil i scriptet!!!</p>');
                    }
                }
            }
        }
    }
} else {
    header("Location: loggut.php?g=1");
}

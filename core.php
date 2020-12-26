<?php

define("BASEPATH", 1);
include_once __DIR__ . '/system/config.php';
include_once __DIR__ . '/inc/functions.php';
include_once __DIR__ . '/classes/User.php';
include_once __DIR__ . '/classes/MinSide.php';
include_once __DIR__ . '/classes/BBcodes.php';
if (isset($_SERVER['X-Requested-With'])) {
    if ($_SERVER['X-Requested-With'] == "XMLHttpRequest") {
        define("JSON", 1);
    } else {
        define("JSON", 0);
    }
} else {
    define("JSON", 0);
}
if (isset($_SESSION['sessionzar'])) {
    include __DIR__ . "/inc/database.php";
    $m = explode(" ", microtime());
    $start = $m[0] + $m[1];
    [$user, $pass, $sss] = $_SESSION['sessionzar'];
    $ip = (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] . $_SERVER['REMOTE_ADDR']
        : $_SERVER['REMOTE_ADDR'];
    #    $st1 = $db->prepare("SELECT id,user,pass,ip,forceout,lastactive, health, status, image, exp, bank, hand, points, city, family, bullets, weapon, support, profile FROM `users` WHERE `user` = ? AND `pass` = ?");
    $st1 = $db->prepare("SELECT * FROM `users` WHERE `user` = ? AND `pass` = ?");
    $st1->execute([$user, $pass]);
    $obj = $st1->fetchObject(\UserObject\User::class);
    if (!$obj) {
        header("Location: " . WWWPATH . "/loggut.php?g=4");
        die('<a href="' . WWWPATH . '/loggut.php">Det kan se ut som du har blitt logget ut, det er noen andre som har logget på din bruker.</a>');
    } else {
        $stored_queries = [
            "online" => 0,
            "jail" => 0
        ];
        if ($obj->ip != $ip) {
            header("Location: " . WWWPATH . "/loggut.php?g=7&currentip=$ip&dbip={$obj->ip}");
            echo '<h1>Det kan se ut som du har blitt logget inn på et annet nettverk. Klikk her for å gå til innloggingssiden: <a href="' . WWWPATH . 'loggut.php">Index</a></h1>';
            die();
        }
        liv_check();
        ipbanned($ip);
        if ($obj->forceout == 1) {
            $db->query("UPDATE `users` SET `forceout` = '0' WHERE `id` = '{$obj->id}'");
            die('<a href="' . WWWPATH . '/loggut.php?g=6">Du har blitt logget ut av en i Ledelsen! Vennligst logg inn på nytt for å fortsette å spille.</a>');
        }
        if (($obj->lastactive + $timeout) < time()) {
            header("Location: " . WWWPATH . "/loggut.php?g=5");
        } elseif (($obj->lastactive + $timeout) > time()) {
            if (defined("NOUPDATE") && NOUPDATE == 1) {
            } else {
                $st2 = $db->prepare("UPDATE `users` SET `lastactive` = UNIX_TIMESTAMP() WHERE `id` = ?");
                if (!$st2->execute([$obj->id])) {
                    if ($obj->status == 1) {
                        die('<p>Kunne ikke sette ny info!<br>' . $st2->errorInfo() . '</p>');
                    } else {
                        die('<p>Det har oppstått en feil i scriptet!!!</p>');
                    }
                }
            }
        }
    }
} else {
    header("Location: " . WWWPATH . "/loggut.php?g=1");
}

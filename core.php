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
    try {
        $db = new PDO("mysql:dbname=mafia;host=127.0.0.1", "mafia", "mafia", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
            PDO::ATTR_STRINGIFY_FETCHES => false,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
            PDO::MYSQL_ATTR_SSL_CA => "C:\\ProgramData\\MySQL\\MySQL Server 8.0\\Data\\ca.pem",
            PDO::MYSQL_ATTR_SSL_CERT => "C:\\ProgramData\\MySQL\\MySQL Server 8.0\\Data\\client-cert.pem",
            PDO::MYSQL_ATTR_SSL_KEY => "C:\\ProgramData\\MySQL\\MySQL Server 8.0\\Data\\client-key.pem"
        ]);
    } catch (PDOException $PDOException) {
        error_log("Couldn't connect to database. Error: " . $PDOException->getMessage());
        die("Kunne ikke koble til db!<br><a href=\"loggut.php?g=2\">Tilbake til innlogging.</a>");
    }
    $m = explode(" ", microtime());
    $start = $m[0] + $m[1];
    list($user, $pass, $sss) = $_SESSION['sessionzar'];
    $ip = (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) ? $_SERVER['HTTP_X_FORWARDED_FOR'] . $_SERVER['REMOTE_ADDR']
        : $_SERVER['REMOTE_ADDR'];
    $st1 = $db->prepare("SELECT id,user,pass,ip,forceout,lastactive, health, status, image, exp, bank, hand, points, city, family, bullets, weapon, support, profile FROM `users` WHERE `user` = ? AND `pass` = ?");
    $st1->execute([$user, $pass]);
    $obj = $st1->fetchObject();
    if (!$obj) {
        header("Location: loggut.php?g=4");
        die('<a href="loggut.php">Det kan se ut som du har blitt logget ut, det er noen andre som har logget p&aring; din bruker.</a>');
    } else {
        $stored_queries = [
            "online" => 0,
            "jail" => 0
        ];
        if ($obj->ip != $ip) {
            header("Location: loggut.php?g=7&currentip=$ip&dbip={$obj->ip}");
            echo '<h1>Det kan se ut som du har blitt logget inn p&aring; et annet nettverk. Klikk her for &aring; g&aring; til innloggingssiden: <a href="loggut.php">Index</a></h1>';
            die();
        }
        liv_check();
        ipbanned($ip);
        if ($obj->forceout == 1) {
            $db->query("UPDATE `users` SET `forceout` = '0' WHERE `id` = '{$obj->id}'");
            die('<a href="loggut.php?g=6">Du har blitt logget ut av en i Ledelsen! Vennligst logg inn p&aring; nytt for &aring; fortsette &aring; spille.</a>');
        }
        if (($obj->lastactive + $timeout) < time()) {
            header("Location: loggut.php?g=5");
        } elseif (($obj->lastactive + $timeout) > time()) {
            if (defined("NOUPDATE") && NOUPDATE == 1) {
            } else {
                $st2 = $db->prepare("UPDATE `users` SET `lastactive` = UNIX_TIMESTAMP() WHERE `id` = ?");
                if (!$st2->execute([$obj->id])) {
                    if ($obj->status == 1) {
                        die('<p>Kunne ikke sette ny info!<br>' . $st2->errorInfo() . '</p>');
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

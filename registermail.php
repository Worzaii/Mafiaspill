<?php
define("BASEPATH", true);
require_once('system/config.php');
try {
    $db = new PDO("mysql:dbname=" . DATABASE . ";host=" . HOST, USERNAME, PASSWORD, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_STRINGIFY_FETCHES => false,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
        PDO::MYSQL_ATTR_SSL_CA => "C:\\ProgramData\\MySQL\\MySQL Server 8.0\\Data\\ca.pem",
        PDO::MYSQL_ATTR_SSL_CERT => "C:\\ProgramData\\MySQL\\MySQL Server 8.0\\Data\\client-cert.pem",
        PDO::MYSQL_ATTR_SSL_KEY => "C:\\ProgramData\\MySQL\\MySQL Server 8.0\\Data\\client-key.pem"
    ]);
} catch (PDOException $PDOException) {
    error_log("Couldn't connect to database. Error: " . var_export($PDOException, true));
    die(json_encode(['string' => "Kunne ikke koble til db. ", 'state' => 0]));
}
$disa1 = null;
$disa2 = null;
$s = $db->prepare("SELECT count(*) FROM `invsjekk` WHERE `code` = ? 
AND `mail` = ? AND `used` = '0' AND `timestamp` > UNIX_TIMESTAMP()");
if ($s->execute([$_GET['code'], $_GET['mail']])) {
    if ($s->fetchColumn() == 1) {
        $s2 = $db->prepare("SELECT * FROM `invsjekk` WHERE `code` = ? 
AND `mail` = ? AND `used` = '0' AND `timestamp` > UNIX_TIMESTAMP()");
        $s2->execute([$_GET['code'], $_GET['mail']]);
        $f = $s2->fetchObject();
        $mail = $f->mail;
        $code = $f->code;
        $disa1 = "";
        $disa2 = "";
    } else {
        $mail = "Link ikke godkjent!";
        $code = "Link ikke godkjent!";
        $disa1 = " disabled";
        $disa2 = " disabled";
    }
}
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <title>Mafia-no Registrering</title>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8">
    <meta name="description" content="<?=DESC?>">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <link type="text/css" rel="stylesheet" href="css/login.css">
    <script src="js/jquery.js"></script>
    <script src="js/handler.js" type="text/javascript"></script>
</head>
<body>
<header>
    <div id="header">
    </div>
</header>
<section>
    <div class="wrapper">
        <div id="shadow"></div>
        <div id="information">
            <p>Registrer deg idag! :)</p>
        </div>
        <div id="content" style="margin-top: 20px;">
            <p><a href="/">Tilbake til innlogging!</a></p>
            <div id="reg">
                <h2>Registrer deg</h2>
                <div id="ressu"></div>
                <form class="loginform" id="registerform" action="handlers/handler.php?brukerreg">
                    <input type="email" class="text" name="mail" placeholder="Email" readonly value="<?= $mail ?>"><br>
                    <input type="text" class="text" name="code" placeholder="Kode" readonly value="<?= $code ?>"><br>
                    <input type="text" class="text" name="vervetav" placeholder="Hvem ble du vervet av?"
                           tabindex="1"><br>
                    <input type="text" class="text" name="user"<?= $disa1 ?> placeholder="Brukernavn" tabindex="2"><br>
                    <input type="password" class="text" name="pass"<?= $disa2 ?> placeholder="Passord" tabindex="3"><br>
                    <input type="submit" value="Registrer deg" tabindex="4" class="button"/>
                </form>
            </div>
        </div>
    </div>
</section>
<footer>
    <div style="width: 600px; maring: 0 auto 0 auto;">
        <div id="spot1">
            <iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2FMafia.no.net.Nicho&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;action=like&amp;colorscheme=dark&amp;font&amp;height=21&amp;appId=223082924413026"
                    style="border:none; overflow:hidden; width:450px; height:21px;background-color: transparent;"></iframe>
        </div>
        <div id="spot2">Mafia-no.net &copy; 2014 Utvikles av Nicholas Arnesen</div>
        <div id="spot3">Design av <a href="http://www.evjanddesign.net">evjand design</a></div>
    </div>
</footer>
</body>
</html>
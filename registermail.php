<?php
define("BASEPATH", true);
require_once('system/config.php');
include "./inc/database.php";
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
    <meta name="description" content="<?php echo DESC ?>">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <link type="text/css" rel="stylesheet" href="css/login.css">
    <script src="js/jquery-3.5.1.js"></script>
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
                    <input type="email" class="text" name="mail" placeholder="Email" readonly
                           value="<?php echo $mail ?>"><br>
                    <input type="text" class="text" name="code" placeholder="Kode" readonly
                           value="<?php echo $code ?>"><br>
                    <input type="text" class="text" name="vervetav"
                           placeholder="Hvem ble du vervet av?"
                           tabindex="1"><br>
                    <input type="text" class="text" name="user"<?php echo $disa1 ?>
                           placeholder="Brukernavn" tabindex="2"><br>
                    <input type="password" class="text" name="pass"<?php echo $disa2 ?>
                           placeholder="Passord" tabindex="3"><br>
                    <input type="submit" value="Registrer deg" tabindex="4" class="button">
                </form>
            </div>
        </div>
    </div>
</section>
<?php include "inc/footer.php"; ?>
</body>
</html>

<?php
define("BASEPATH", true);
require_once('system/config.php');
require_once("classes/class.php");
$db = new database;
$db->configure();
$db->connect()or$db->connection_error();
$disa1 = null;
$disa2 = null;
$s  = $db->query("SELECT * FROM `invsjekk` WHERE `code` = '".$db->escape($_GET['code'])."' AND `mail` = '".$db->escape($_GET['mail'])."' AND `used` = '0' AND `time` > '".time()."'");
if ($db->num_rows() == 1) {
    $f    = $db->fetch_object();
    $mail = $f->mail;
    $code = $f->code;
    $disa1 = "";
    $disa2 = "";
} else {
    $mail  = "Link ikke godkjent!";
    $code  = "Link ikke godkjent!";
    $disa1 = " disabled";
    $disa2 = " disabled";
}
?>
<!DOCTYPE html>
<html lang="no">
    <head>
        <title>Mafia-no Registrering</title>
        <meta http-equiv="content-type" content="text/html;charset=ISO-8859-1">
        <meta name="description" content="mafia-no.net er ogs&aring; en av de spillene som er p&aring; webben og h&aring;per p&aring; en fremtid med ber&oslash;mmelse og ting som interesserer spillerne. Se v&aring;r facebookside idag https://www.facebook.com/Mafia.no.net.Nicho!">
        <meta name="keywords" content="mafiaspill, klikkbasert, ikke ferdig, jobbes med, mafia, mafiano">
        <meta name="author" content="Nicholas Arnesen">
        <link type="text/css" rel="stylesheet" href="css/login.css">
        <script src="js/jquery.js?<?php echo time() ?>"></script>
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
                <div style="border-radius: 50px; border: 2px solid #f00; z-index: 1; height: auto; width: 100%; background: #888 url('/imgs/warn1.png') repeat; text-align: center; margin-top: -30px;">
                    <p>Registrer deg idag! :)</p>
                </div>
                <div id="content" style="margin-top: 20px;">
                    <p><a href="/">Tilbake til innlogging!</a></p>
                    <div id="reg">
                        <h2>Registrer deg</h2>
                        <div id="ressu"></div>
                        <form class="loginform" id="brukerreg" action="handlers/handler.php?brukerreg">
                            <input type="email" class="text" name="mail" placeholder="Email" readonly value="<?= $mail ?>"><br>
                            <input type="text" class="text" name="code" placeholder="Kode" readonly value="<?= $code ?>"><br>
                            <input type="text" class="text" name="vervetav" placeholder="Hvem ble du vervet av?" tabindex="1"><br>
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
                    <iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2FMafia.no.net.Nicho&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;action=like&amp;colorscheme=dark&amp;font&amp;height=21&amp;appId=223082924413026" style="border:none; overflow:hidden; width:450px; height:21px;background-color: transparent;"></iframe>
                </div>
                <div id="spot2">Mafia-no.net &copy; 2014 Utvikles av Nicholas Arnesen</div>
                <div id="spot3">Design av <a href="http://www.evjanddesign.net">evjand design</a></div>
            </div>
        </footer>
    </body>
</html>
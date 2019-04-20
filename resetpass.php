<?php
define('BASEPATH', true);
require_once './system/config.php';
require_once './classes/class.php';
require_once './inc/functions.php';
$db = new database;
$db->configure();
$db->connect();
$s  = $db->query("SELECT * FROM `resetpasset` WHERE `resgen` = '".$db->escape($_GET['resgen'])."' AND `uid` = '".$db->escape($_GET['id'])."' AND `used` = '0'"
    ." AND `timemade` < (UNIX_TIMESTAMP()+3600)");
if ($db->num_rows() == 1) {
    $res   = $db->fetch_object();
    $time  = time() - ($res->timemade + 3600);
    $user  = user($res->uid, 1);
    $valid = true;
} else {
    $valid = false;
}
?>
<!DOCTYPE html>
<html lang="no">
    <head>
        <title><?= NAVN_DOMENE; ?> Gjenopprett Passord</title>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8">
        <meta name="description" content="">
        <meta name="keywords" content="">
        <meta name="author" content="Nicholas Arnesen">
        <link type="text/css" rel="stylesheet" href="./css/login.css">
        <script src="./js/jquery.js" type="text/javascript"></script>
        <script src="js/handler.js" type="text/javascript"></script>
        <script src="./js/teller.js" type="text/javascript"></script>
    </head>
    <body>
        <header>
            <div id="header"></div>
        </header>
        <section>
            <div class="wrapper">
                <div id="shadow"></div>
                <div id="content" style="margin-top: 20px;">
                    <p style="margin-left:10px;position:absolute;z-index: 1;margin-top: 10px;"><a href="/">Tilbake til innlogging!</a></p>
                    <h2 style="margin: 0;padding: 10px 25px 10px 0px;font-size: 14px;text-transform: uppercase;font-weight: bold;color: #111;text-align: right;border-bottom: 1px solid #999;box-shadow: 0px 1px 0px #fff;">Gjenopprett passord</h2>
                    <?php
                    if ($valid == false) {
                        echo '<p class="feil">Koden er ikke lengre tilgjengelig, eller link stemmer ikke!</p>';
                    } else {
                        ?>
                        <div id="reg">
                            <p>Tid som gjenst&aring;r med f&oslash;lgende kode: <span id="timeleft"></span><script>teller(<?= $time; ?>, "timeleft", false, "ned");</script></p>
                            <hr>
                            <div id="ressu"></div>
                            <form class="loginform" id="respas" action="handlers/handler.php?respas">
                                <?php echo '<input type="text" class="text" value="'.$user->user.'" readonly="">'; ?>
                                <input type="hidden" name="uid" value="<?= $user->id ?>"><br>
                                <input type="password" class="text" name="p1" placeholder="Passord" tabindex="1" autofocus="" required=""><br>
                                <input type="password" class="text" name="p2" placeholder="Gjenta passord" tabindex="2" required=""><br>
                                <input type="submit" value="Lagre nytt passord" tabindex="3" class="button">
                            </form>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </section>
        <?php include_once './inc/footer.php'; ?>
    </body>
</html>
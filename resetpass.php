<?php
define('BASEPATH', true);
require_once './system/config.php';
require_once './inc/pdoinc.php';
require_once './inc/functions.php';

$sp = $db->prepare("SELECT count(*) from resetpasset " .
    "WHERE resgen = ? AND uid = ? AND used = '0' AND timestamp < (UNIX_TIMESTAMP()+3600)");
$sp->execute([$_GET['resgen'], $_GET['id']]);
if ($sp->fetchColumn() == 1) {
    $s = $db->prepare("SELECT * from resetpasset " .
        "WHERE resgen = ? AND uid = ? AND used = '0' AND timestamp < (UNIX_TIMESTAMP()+3600)");
    $s->execute([$_GET['resgen'], $_GET['id']]);
    $res = $s->fetchObject();
    $time = ($res->timestamp + 3600) - time();
    $user = user($res->uid, 1);
    $valid = true;
} else {
    $valid = false;
}
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <title><?php echo NAVN_DOMENE; ?> Gjenopprett Passord</title>
    <link type="text/css" rel="stylesheet" href="./css/login.css">
    <meta http-equiv="content-type" content="text/html;charset=UTF-8">
    <meta name="description" content="<?php echo DESC; ?>">
    <meta name="keywords" content="<?php echo KEYWORDS; ?>">
    <meta name="author" content="<?php echo UTVIKLER; ?>">
    <script src="./js/jquery-3.5.1.js" type="text/javascript"></script>
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
            <p style="margin-left:10px;position:absolute;z-index: 1;margin-top: 10px;"><a href="/">Tilbake til
                    innlogging!</a></p>
            <h2 style="margin: 0;padding: 10px 25px 10px 0px;font-size: 14px;text-transform: uppercase;font-weight: bold;color: #111;text-align: right;border-bottom: 1px solid #999;box-shadow: 0px 1px 0px #fff;">
                Gjenopprett passord</h2>
            <?php
            if ($valid === false) {
                echo feil('Koden er ikke lengre tilgjengelig, eller link stemmer ikke!');
            } else {
                ?>
                <div id="resetpassword">
                    <p>Tid som gjenstår med følgende kode: <span id="timeleft"></span>
                        <script>teller(<?php echo $time; ?>, "timeleft", false, "ned");</script>
                    </p>
                    <hr>
                    <div id="resetpasswordresult"></div>
                    <form class="loginform" id="resetpasswordform" action="handlers/handler.php?resetpassword">
                        <?php echo '<input type="text" class="text" value="' . $user->user . '" readonly="">'; ?>
                        <input type="hidden" name="uid" value="<?php echo $user->id; ?>"><br>
                        <input type="hidden" name="resgen" value="<?php echo $res->resgen; ?>">
                        <input autofocus="" class="text" name="p1" placeholder="Passord" required=""
                               tabindex="1" type="password"><br>
                        <input class="text" name="p2" placeholder="Gjenta passord" required="" tabindex="2"
                               type="password"><br>
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

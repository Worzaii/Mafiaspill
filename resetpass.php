<?php
    define('BASEPATH', true);
    require_once('system/config.php');
    require_once("classes/class.php");
    $db = new database;
    $db->configure();
    $db->connect();
    $s = $db->query("SELECT * FROM `resetpasset` WHERE `resgen` = '".$db->escape($_GET['resgen'])."' AND `uid` = '".$db->escape($_GET['id'])."' AND `used` = '0' AND `rimming` > UNIX_TIMESTAMP()");
    if($db->num_rows() == 1){
      include './inc/functions.php';
      $res = $db->fetch_object();
      $time = ($res->rimming) - time();
      $user = user($res->uid,1);
      $valid = true;
    }
    else{
			$valid=false;
    }
?>
<!DOCTYPE html>
<html lang="no">
  <head>
    <title>Mafia-no Gjenopprett Passord</title>
    <meta http-equiv="content-type" content="text/html;charset=ISO-8859-1">
    <meta name="description" content="Dette er siden man blir sendt til når man har mottat mail med link til å lagre nytt passord.">
    <meta name="keywords" content="passord, gjenoppretting, password, mafia-no,mafia-no">
    <meta name="author" content="Nicholas Arnesen">
    <link type="text/css" rel="stylesheet" href="css/login.css">
    <script src="http://code.jquery.com/jquery-latest.js" type="text/javascript"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js" type="text/javascript"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js" type="text/javascript"></script>
    <script src="js/nyajaxhandler.js" type="text/javascript"></script>
    <script src="js/teller.js" type="text/javascript"></script>
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
          if($valid == false){
            echo '<p class="feil">Koden er ikke lengre tilgjengelig, eller link stemmer ikke!</p>';
          }
          else{
            ?>
          <div id="reg">
            <p>Tid som gjenstår med følgende kode: <span id="timeleft"></span><script type="text/javascript">teller(<?php echo $time; ?>,"timeleft",false,"ned");</script></p>
            <hr>
            <div id="ressu"></div>
            <form class="loginform" id="respas" action="handlers/handler2.php?respas">
              <?php echo '<input type="text" class="text" value="'.$user->user.'" readonly="">'; ?>
              <input type="hidden" name="uid" value="<?=$user->id?>"><br />
              <input type="password" class="text" name="p1" placeholder="Passord" tabindex="1" autofocus="" required="" /><br />
              <input type="password" class="text" name="p2" placeholder="Gjenta passord" tabindex="2" required="" /><br />
              <input type="submit" value="Lagre nytt passord" tabindex="3" class="button"/>
            </form>
          </div>
          <?php
          }
          ?>
        </div>
      </div>
    </section>
    <footer>
    <div style="width: 600px; margin: 0 auto 0 auto;">
      <div id="spot1">
        <iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2FMafia.no.net.Nicho&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;action=like&amp;colorscheme=dark&amp;font&amp;height=21&amp;appId=223082924413026" style="border:none; overflow:hidden; width:450px; height:21px;background-color: transparent;"></iframe>
      </div>
      <div id="spot2">Mafia-no.net &copy; <?php echo date("Y"); ?> Utvikles av Nicholas Arnesen</div>
      <div id="spot3">Design av <a href="http://www.evjanddesign.net">evjand design</a></div>
      </div>
    </footer>
  </body>
</html>

<?php
  global $db,$obj,$set;/*Ettersom left.php blir inkludert inne i en funksjon, så må disse variablene globaliseres for at dette scriptet skal kunne bruke disse variablene*/
  $sql = $db->query("SELECT * FROM `users` WHERE `lastactive` BETWEEN '".(time()-1800)."' AND '".time()."' ORDER BY `lastactive` DESC");/*Siste 30 spillere pålogget*/
  $ant = $db->num_rows();
  $sql3= $db->query("SELECT * FROM `chat`");
  $num2= $db->num_rows();
  $sql4 = $db->query("SELECT * FROM `jail` WHERE `uid` = '$obj->id' AND `breaker` = '0' AND `timeleft` > '".time()."' ORDER BY `id` DESC LIMIT 1");
  $ant2 = $db->num_rows();
  $sql5 = $db->query("SELECT * FROM `mail2` WHERE `tid` = '$obj->id' AND `read` = '0' ORDER BY `id` DESC");
  $nymeldinger = $db->num_rows();
  $sqlsys=$db->query("SELECT * FROM `sysmail` WHERE `uid` = '{$obj->id}' AND `read` = '0'");
  $ex = $db->num_rows();
  $nymeldinger = $nymeldinger + $ex;/*Legger systemmeldinger sammen med innboks.*/
  $db->query("SELECT * FROM `krimlogg` WHERE `usid` = '$obj->id' AND `time` > '".time()."' ORDER BY `id` DESC LIMIT 0,1");
  if($db->num_rows() == 1){
  $kt=$db->fetch_object();
  $ktl=(($kt->time - time()) >= 1) ? ($kt->time - time()) : null;
  }
  else{
    $ktl=null;
  }
  $db->query("SELECT * FROM `billogg` WHERE `uid` = '$obj->id' AND `time` > '".time()."' ORDER BY `id` DESC LIMIT 0,1");
  $numrows = $db->num_rows();
  if($numrows >= 1){
    $bt=$db->fetch_object();
    $btl=(($bt->time - time()) >= 1) ? ($bt->time - time()) : null;
  }
  else{
    $bt = NULL;
    $btl=NULL;
  }
  $db->query("SELECT * FROM `ransp` WHERE `uid` = '$obj->id' AND `time` > '".time()."' ORDER BY `id` DESC LIMIT 1");
  $ranrows = $db->num_rows();
  if($ranrows >= 1){
    $rt=$db->fetch_object();
    $rtl=(($rt->time - time()) >= 1) ? ($rt->time - time()) : null;
  }
  else{
    $rt=NULL;
    $rtl=NULL;
  }
  $db->query("SELECT * FROM `jail` WHERE `uid` = '$obj->id' AND `timeleft` > '".time()."' ORDER BY `id` DESC LIMIT 1");
  $jailrow = $db->num_rows();
  if($jailrow >= 1){
    $jt=$db->fetch_object();
    $jte=(($jt->timeleft - time()) >= 1) ? ($jt->timeleft - time()) : null;
  }
  else{
    $jt=NULL;
    $jte=NULL;
  }
  if($obj->airportwait > time()){
    $fte = $obj->airportwait - time();
    $flytid = 1;
  }
  else{
    $flytid = NULL;
  }
  if($nymeldinger == 0){
      $nymeldinger = null;
  }
  else{
      if($nymeldinger >= 2)$ee="e";
      if($nymeldinger == 1)$ee=null;
      $nymeldinger = '(<b>'.number_format($nymeldinger).' ny'.$ee.'</b>)';
  }
  $lib = unserialize($obj->settings);
  $onl = ($lib["o"] == 1) ? "/Online" : "online.php";
?>
<h2>Rank</h2>
<ul>
<?php
     if($ktl == NULL){echo '<li><a href="Krim">Kriminalitet</a>  <img src="http://cdn1.iconfinder.com/data/icons/fatcow/16/thumb_up.png" />';}
else{echo '<li><a href="Krim">Kriminalitet</a> <span style="font-size:10px;" id="krimteller">'.$ktl.'</span><script type="text/javascript">loggteller('.$ktl.',"krimteller",false,"ned");</script></li>';}
  if($btl == NULL){echo '<li><a href="Bil">Biltyveri</a>  <img src="http://cdn1.iconfinder.com/data/icons/fatcow/16/thumb_up.png" />';}
else{echo '<li><a href="Bil">Biltyveri</a> <span style="font-size:10px;" id="bilteller">'.$btl.'</span><script type="text/javascript">loggteller('.$btl.',"bilteller",false,"ned");</script></li>';}
if($rtl == NULL){echo '<li><a href="Stjele">Ran Spiller</a> <img src="http://cdn1.iconfinder.com/data/icons/fatcow/16/thumb_up.png" />';}else{echo '<li><a href="Stjele">Ran Spiller</a> <span style="font-size:10px;" id="ranteller">'.$rtl.'</span><script type="text/javascript">loggteller('.$rtl.',"ranteller",false,"ned");</script></li>';}
    if($jte == NULL){echo '<li><a href="Fengsel">Fengsel</a>'; if($ant2 >= 1){echo "($ant2)";} echo '</li>';}
else{echo '<li><a href="Fengsel">Fengsel</a> <span style="font-size:10px;" id="jailteller">'.$jte.'</span><script type="text/javascript">loggteller('.$jte.',"jailteller",false,"ned");</script></li>';}
  if($flytid == NULL){
    echo '<li><a href="Fly">Flyplass</a> <img src="http://cdn1.iconfinder.com/data/icons/fatcow/16/thumb_up.png" /></li>';
  }
  else{
    echo '<li><a href="Fly">Flyplass</a> <span style="font-size:10px;" id="flyteller">'.$fte.'</span><script type="text/javascript">loggteller('.$fte.',"flyteller",false,"ned");</script></li>';
  }
 ?>
  <li><a href="Drap">Drap</a></li>
  <li><a href="oppdrag.php">Oppdrag <b style="color:#FFFFFF"></b></a></li>
  <li><a href="Ran">Ran</a></li>
</ul>
<h2>Verdier</h2>
<ul>
  <li><a href="Marked">Svartebørsen</a></li>
  <li><a href="Bunker">Bunker</a></li>
  <li><a href="Bank">Banken</a></li>
  <li><a href="Poeng">Poeng</a></li>
  <li><a href="verving.php">Verving</li></a>
  <li><a href="Auksjon">Auksjon</a></li>
  <li><a href="Firmaer">Firmaer</a></li>
  <li><a href="Garasje">Garasje</a></li>
</ul>
<h2>Kommunikasjon</h2>
<ul>
  <li><a href="Innboks">Innboks</a><?=$nymeldinger?></li>
  <li><a href="deputy.php">Send inn søknad!</a></li>
  <li><a href="support.php">Support</a></li>
  <li><a href="<?php echo $onl; ?>">Spillere pålogget</a> (<?=$ant;?>)</li>
  <li><a href="Nyheter">Nyheter</a></li>
  <li><a href="Ledelsen">Ledelsen</a></li>
</ul>
<h2>Sosialt</h2>
<ul>
  <li><a href="Chat">Chat</a> <?php if($num2 >= 2){echo "($num2)";}?></li>
  <li><a href="nyforum.php?type=1"> Generelt Forum</a></li>
  <li><a href="nyforum.php?type=2"> Salg og Søknadsforum</a></li>
  <li><a href="nyforum.php?type=3"> Off-Topic</a></li>
  <?php if($obj->family != NULL){echo '<li><a href="familiepanel.php?side=konfam">Familie</a></li>';}
  else{?>
  <li><a href="Familie">Familie</a></li>
  <?php }?>
</ul>
<h2>Gambling</h2>
<ul>
  <li><a href="Lotto">Lotto</a></li>
  <li><a href="Blackjack">Blackjack</a></li>
  <?php if(r1()){echo '<li><a href="Poker">Poker(under arbeid)</a></li>';} ?> 
</ul>
	<script src='//js.pusher.com/2.2/pusher.min.js'></script>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="js/shortcut.js"></script>
<div id="cometchat_base" style="left: 20px; width: 1326px; z-index: 100004;">
<div id="cometchat_optionsbutton" class="cometchat_tab" unselectable="on">
<div id="cometchat_optionsbutton_icon" class="cometchat_optionsimages">
</div>
</div>
<div id="cometchat_trayicons">
<div id="cometchat_trayicon_notifications" class="cometchat_trayicon" unselectable="on">
<div class="cometchat_trayiconimage">
<img src="http://mafiaspillet.no/cometchat5.1/modules/notifications/icon.png">
</div>
<div class="cometchat_trayicontext">Varsler</div>
</div>
<div id="cometchat_trayicon_meldinger" class="cometchat_trayicon" unselectable="on">
<div class="cometchat_trayiconimage">
<img src="http://mafiaspillet.no/cometchat5.1/modules/meldinger/icon.png">
</div>
<div class="cometchat_trayicontext">Innboks</div>
</div>
<div id="cometchat_trayicon_ikoner" class="cometchat_trayicon" unselectable="on" style="padding: 0px;">
</div>
</div>
<span id="cometchat_userstab" class="cometchat_tab" unselectable="on">
<span id="cometchat_userstab_icon" class="cometchat_user_available2">
</span>
<span id="cometchat_userstab_text">Chat (100)</span>
</span>
<div id="cometchat_chatbox_right" class="cometchat_chatbox_right_last cometchat_chatbox_lr">
<span class="cometchat_tabtext">0</span>
<span style="top:-5px;display:none" class="cometchat_tabalertlr">0</span>
</div>
<div id="cometchat_chatboxes" unselectable="on" style="width: 0px;">
<div id="cometchat_chatboxes_wide">
</div>
</div>
<div id="cometchat_chatbox_left" class="cometchat_chatbox_left_last cometchat_chatbox_lr">
<span class="cometchat_tabtext">0</span>
<span class="cometchat_tabalertlr" style="top:-5px;display:none;">0</span>
</div>
</div>
      <script type="text/javascript">
            function notchrome() {
               alert ("\bViktig informasjon!!\b\nDu bruker ikke google chrome og vill dermed ikke få full effekt av alle opplevlsene i spillet, vi anbefaler derfor at du bruker google chrome når du spiller westmafia!");
            }
      </script>
<?php
    $user_agent = $_SERVER['HTTP_USER_AGENT']; 
if (strpos( $user_agent, 'Chrome') !== false)
{
$chromen = "Fantastisk denne brukeren fortjener en kjeks!";
}else {
echo '<script type="text/javascript">'
   , 'notchrome();'
   , '</script>'
;
}

?>

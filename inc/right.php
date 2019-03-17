<?php
global $obj;
global $db;
    if($obj->image == NULL || $obj->image == ''){
        $img = "imgs/nopic.png";
    }
    else{
        $img = $obj->image;
    }
    $rank = rank($obj->exp);
?>
<h2><?=$obj->user;?></h2>
<a href="profil.php?id=<?=$obj->id;?>"><img id="avatar" src="<?=$img;?>" alt=""></a>
<?php
if($obj->status <= 3 || $obj->status == 6 || $obj->support == 1){
  echo '<h2>Panel</h2><ul>';
  if($obj->status == 1){
  echo '
  <li><a href="fulladmin.php">Administrer spillet</a></li>
  <li><a href="adminjobb.php">Logg for admins</a></li>
  ';
  }
  if($obj->status <= 2) {
      echo '
      <li><a href="moderator_cp.php">Moderator panel</a></li>
      <li><a href="nyforum.php?type=4">Ledelsens forum</a></li>
      ';
  }
  if($obj->status <= 3) {
      echo '
      <li><a href="forum_cp.php">Forumadministrasjon</a></li>
      ';
  }

if($obj->support == '1' || $obj->status == '1' || $obj->status == '2'){
      $db->query("SELECT * FROM `support` WHERE `read` = '0'");
      $nymeldinger = $db->num_rows();
        if($nymeldinger == 0){
        $nymeldinger = null;
    }
    else{
        if($nymeldinger >= 2)$ee="e";
        if($nymeldinger == 1)$ee=null;
        $nymeldinger = '(<b style="color:#f11;">'.number_format($nymeldinger).' ny'.$ee.'</b>)';
    }
        echo '<li><a href="supportpanel.php">Supportsenter'.$nymeldinger.'</a></li>';
}
       /* if($obj->status == 1 || $obj->status == 2 || $obj->status == 6){
echo '
<li><a href="picmaker.php">Picmakerpanel</a></li>
';*/
  //}
  echo '</ul>';
}
?>
<h2>Din Bruker</h2>
<ul>
  <li>Rank: <?=$rank[1];?></li>
  <li>Penger: <?=number_format($obj->hand);?>kr</li>
  <li>Poeng: <?=number_format($obj->points);?></li>
  <li>By: <?=city($obj->city);?></li>
  <li>Liv: <?=$obj->health;?>%<br /><div style="width:100px;height:4px;background: #f00;"><div style="width:<?=$obj->health?>%;height:4px;background: #0f0;"></div></div></li>
  <li>Familie: <?php if($obj->family == null){echo "<i>ingen</i>";}else{echo '<a href="#">'.famidtoname($obj->family,1).'</a>';} ?></li>
  <br />
  <li>Kuler: <?=number_format($obj->bullets);?>
  <li>Våpen: <?=weapon($obj->weapon)?></li>
</ul>
<h2>Oversikt</h2>
<ul>
    <li><a href="/FAQ">FAQ</a></li>
    <li><a href="Koder">BB-koder</a></li>
    <li><a href="minside.php">Min side</a></li>
    <li><a href="statistikk.php">Statistikk</a></li>
    <li><a href="Ranstikk">Ran statistikk</a></li>
</ul>
<h2>Innstillinger</h2>
<ul>
    <li><a href="endre.php">Endre innstillinger</a></li>
    <li><a href="finnspiller.php">Finn spiller</a></li>
    <li><a href="endreprofil.php">Endre profil</a></li>
    <li><a href="/Passord">Endre passord</a></li>
    <li><a href="loggut.php" onclick="return confirm('Er du sikker på at du vil logge ut? ')">Logg ut</a></li>
</ul>
</div>
</div>
</div>
</section>
<footer>
<div id="spot1"><iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2FMafia.no.net.Nicho&amp;send=false&amp;layout=button_count&amp;
width=450&amp;show_faces=false&amp;action=like&amp;colorscheme=dark&amp;font&amp;height=21&amp;appId=223082924413026" scrolling="no" frameborder="0"
 style="border:none; overflow:hidden; width:450px; height:21px;" allowTransparency="true"></iframe></div>
 <div id="spot2">Mafia-no.net © 2011-<?=date("Y")?> Utvikles av Nicholas Arnesen</div>
 <div id="spot3">Design av <a href="http://facebook.com/evjanddesign">Evjand design</a></div>
</footer>
<script type="text/javascript">
$(document).ready(function(){
  $(document).bind("click", function(event) {
    $("div.custom-menu").remove();
  });
  $("a.user_menu").bind("contextmenu", function(event) {
  event.preventDefault();
  var thevalue = event.delegateTarget.attributes[1].value;
  var split = thevalue.split(";");
  var user = split[0];
  var id = split[1];
  $("body")
      .append("<div class=\"custom-menu\"><ul><li><a href=\"profil.php?id=" + id + "\">Gå til Profil</a></li><li><a href=\"innboks.php?ny&usertoo="+user+"\">Send melding</a></li><li><a href=\"bank.php?til="+user+"\">Send penger</a></li></ul></div>");
  $("div.custom-menu").css({top: event.pageY + "px", left: event.pageX + "px"});  
  });
});
</script>
<div id="boxes">
<div style="top: 199.5px; left: 551.5px; display: none;" id="dialog" class="window">

<div style="margin-left: 90%; margin-top: 5px;"><a href="#" class="close"><img src="http://png.findicons.com/files/icons/1714/dropline_neu/24/dialog_close.png"></div></a>
<center><font color="red"><h1>Viktig informasjon!</h1></font><br>
<p>Du bruker ikke Google Chrome og dermed vill du ikke kunne få full opplevelse av spillet, vi annbefaler deg og bruke Google Chrome når du spiller WestMafia</p>
</div>

<!-- Mask to cover the whole screen -->
<div style="width: 1478px; height: 602px; display: none; opacity: 0.8;" id="mask"></div>
</div>
</body>
</html>
<?php die();
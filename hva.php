<?php
  include("core.php");
  startpage("Hva skal jobbes med?");
  function br($t){
    $t = str_replace("\n", "<br>", $t);
    return str_replace("&lt;br&gt;", "<br>", $t);
  }
?>
<h1>Hva skal jobbes med?</h1>
<form method="post" align="center" action="">
<?php
if(isset($_POST['jobb'])){
$jobb = mysql_real_escape_string($_POST['jobb']);
$time = time();
if(mysql_query("INSERT INTO `jobbe_med` VALUES(NULL,'{$obj->user}','{$jobb}','{$time}')")){
echo '<p class="lykket">Ditt forslag har blitt lagt inn!</p>';
}
else{
if($obj->status == 1){
echo mysql_error();
}
else{
echo '<p class="feil">Kunne ikke legge inn forslag :S</p>';
}
}
}?>
<textarea name="jobb" style="width: 98%;height: 100px;" autofocus placeholder="Skriv inn her hva du ønsker at Ledelsen skal jobbe med. Om det allerede har blitt foreslått av noen andre, prøv å ikke reposte da det kan bli mange like henvendelser og uoversiklig! - Werzire"></textarea>
<br />
<input class="hva" type="submit" value="Be Ledelsen jobbe med dette!" />
<br />
</form>
<?php
$s = mysql_query("SELECT * FROM `jobbe_med` ORDER BY `id` DESC")or die(mysql_error());
while($r = mysql_fetch_object($s)){
  echo '
    <table class="table chat" border="0">
    <tr><td class="table chat2">'.status($r->user).'</td><td class="table chat3">'.br($r->tekst).'</td><td colspan="2">'.date("H:i:s d.m.y",$r->time).'</td></tr>
    </table>';
  }
  endpage();
?>
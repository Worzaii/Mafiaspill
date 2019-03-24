<?php
include("core.php");
startpage("Spillere p&aring;logget");
?>
<h1>Spillere p&aring;logget</h1>
<?php
?>
<p>Viser alle spillere som har v&aelig;rt aktiv den siste halvtimen</p>
<?php
$sql = $db->query("SELECT * FROM `users` WHERE `lastactive` BETWEEN '".(time()-1800)."' AND '".time()."' ORDER BY `lastactive` DESC");
$res = "";
while($r = mysqli_fetch_object($sql)){
  $res .= '<a href="profil.php?id='.$r->id.'">'.status($r->id,1)."</a>, ";
}
echo '<p>'.substr($res, 0, -2).'</p>';
$pi = rainbow("Picmaker");
echo <<<END
<p><span style="color:#0ff">Admin</span><br>
<span style="color:#0f0">Moderator</span><br>
<span style="color:#ff0">Forum-moderator</span><br>
<span style="color:#DE22D7;">Supportspiller</span><br>
$pi<br>Vanlig spiller</p>

END;
endpage();
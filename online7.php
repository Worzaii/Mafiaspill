<?php
include("core.php");
startpage("Spillere p�logget");
?>
<h1>Spillere p&aring;logget</h1>
<?php
?>
<p>Viser alle spillere som har vrt aktiv den siste halvtimen</p>
<?php
$sql = $db->query("SELECT * FROM `users` WHERE `lastactive` BETWEEN '".(time()-1800)."' AND '".time()."' ORDER BY `lastactive` DESC");
$res = "";
while($r = mysqli_fetch_object($sql)){
  $res .= '<a class="user_menu" value="'.$r->user.';'.$r->id.'" href="profil.php?id='.$r->id.'">'.status($r->id,1)."</a>, ";
}
echo '<p>'.substr($res, 0, -2).'</p>';
$pi = rainbow("Picmaker");
echo <<<END
<center><br><br>
<table class="table" style="margin-top: 1px; text-align: center; width: 140px;">
<th><b>FARGEFORKLARING</b></th>
<tr><td><span style="color:#0ff">Administrator</span></td></tr>
<tr><td><span style="color:#0f0">Hoved-Moderators</span></td></tr>
<tr><td><span style="color:yellowgreen">Moderator</span></td></tr>
<tr><td><span style="color:#ff0">Forum-moderator</span></td></tr>
<tr><td><span style="color:#DE22D7;">Supportspiller</span></td></tr>
<tr><td>$pi</td></tr>
</table></center>

END;
endpage();
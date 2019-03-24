<?php
    include("core.php");
    startpage("Spillstatistikk");
?>
<h1>Statistikk</h1>
<?php
$select = $db->query("SELECT * FROM `users` ORDER BY `id` DESC LIMIT 0,10");
$select2= $db->query("SELECT * FROM `users` ORDER BY `id` DESC");
$select3= $db->query("SELECT * FROM `users` WHERE `status` <> '1' AND `status` <> '2' AND `health` > '0' ORDER BY `exp` DESC LIMIT 10");
$select4= $db->query("SELECT * FROM `users` WHERE `status` <> 1 AND `status` <> 2 AND `health` > '0' ORDER BY `bank` DESC LIMIT 10");
$select5= $db->query("SELECT * FROM `users` WHERE `activated` = '1'");
$select6= $db->query("SELECT * FROM `users` WHERE `moddet` = '1' OR `health` = '0'");
$tw = $db->num_rows();
echo '
<div>
<table class="table" style="width:230px; margin:19px auto;">
<thead>
<th colspan="2">Spill-statistikk</th>
</thead>
<tbody>
<tr><td>Antall spillere registrert:</td><td>'.$db->num_rows($select2).'</td></tr>
<tr><td>Antall aktiverte spillere:</td><td>'.$db->num_rows($select5).'</td></tr>
<tr><td>Antall d&oslash;de spillere:</td><td>'.$tw.'</td></tr>
</tbody>
</table>
<br>
<table style="width:220px;float:left;clear:left;margin-left:25px;" class="table">
<thead><tr><th colspan="2">Siste 10 registrerte:</th></tr></thead>
<tr><td style="width:50px;">ID:</td><td>Bruker:</td></tr>
';
while($r = mysqli_fetch_object($select)){
echo '
<tr>
<td>
#'.$r->id.'</td><td><strong><a href="profil.php?id='.$r->id.'">'.status($r->user).'</a></strong></td>
</tr>
';
}
echo '
</table>
</div>
';
if($obj->status == 1 || $obj->status == 2){
$exp = '<th>Exp:</th>';
$col = ' colspan="2"';
}
else{
$exp = null;
$col=null;
}
echo '<div>
  <table id="rank" style="width:300px;float:right;margin-right:25px;clear:right;" class="table">
  <tr>
  <th>Topp 10 rank</th>'.$exp.'</tr>';
while($r = mysqli_fetch_object($select3)){
if($obj->status == 1 || $obj->status == 2){
$kr = '<td>'.$r->exp.'</td>';
}
else{
$kr = null;
}
if($select3 == NULL){
    echo 'test';
}
echo '<tr><td><a href="profil.php?id='.$r->id.'">'.status($r->user).'</a></td>'.$kr.'</tr>';
}
echo '</table></div>';
if($obj->status == 1 || $obj->status == 2){
$ex = '<th>Banksum:</th>';
$col=' colspan="2"';
}
else{
$ex=null;
$col=null;
}
echo '<div><table id="bank" class="table" style="float:left;width:300px;margin:50px auto;"><tr><th>Topp 10 bank</th>'.$ex.'</tr>';

while($r = mysqli_fetch_object($select4)){
if($obj->status == 1 || $obj->status == 2){
$kr = '<td>'.number_format($r->bank).' kr</td>';
}
else{
$kr = null;
}
echo '<tr><td><a href="profil.php?id='.$r->id.'">'.status($r->user).'</a></td>'.$kr.'</tr>';
}
echo '</table></div>';

?><div style="clear:both;"></div>
<?php
$srr = $db->query("SELECT * FROM `familier` WHERE `lagtned` = '0' ORDER BY `TimeMade` ASC") or die("Feil: ".mysqli_error($db->connection_id));
 echo '
 <div class="familie w500">
 <h1 class="big">Familieoversikt</h1>
 <table style="width:auto;" class="table center">
 ';
 if($db->num_rows() == 0){
 echo '<tr><td>Ingen familier eksisterer enda.</td></tr>';
 }
 else{
 while($r = mysqli_fetch_object($srr)){
 if($stopwhile==1){
 /*Ikke gjenta headers*/
 }
 else{
 $stopwhile=1;
 echo '
 <thead>
   <tr class="c_1">
       <td colspan="2">Familie</td>
       <td>Boss</td>
	   <td>Underboss</td>
 </tr>
 </thead>
 <tbody>
 ';
 }
 
 echo '

         <tr class="c_2">
          <td><a href="familievis.php?fam='.$r->Navn.'"><img src="'.$r->img.'" alt="" height="35" width="35"></a></td>
          <td><a href="familievis.php?fam='.$r->Navn.'">'.$r->Navn.'</a></td>
          <td><a href="profil.php?id='.$r->id.'">'.user($r->Leder).'</a></td>
          <td><a href="profil.php?id='.$r->id.'">'.user($r->Ub).'</a></td>
         </tr>
';
    echo '</tbody>';
 }
 }
 
echo '</table>';
echo '</div>';
?>
<?php
    endpage();
?>
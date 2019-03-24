<?php
include("core.php");
startpage("Viser 20 siste");
?>
<h1>Siste ran(topp 20)</h1>
<?php
if(r1() || r2()){
  echo '<a href="Ranstikk?mer">Se topp 100</a>';
  if(isset($_GET['mer'])){
    $lim = 100;
  }
  else{
    $lim=20;
  }
}
else{
  $lim=20;
}
echo '<table class="table">
<tr><td>Offer</td><td>Bel&oslash;p(om lyktes)</td><td>Mafia</td><td>Tidspunkt</td></tr>';
$sql = $db->query("SELECT * FROM `ransp` ORDER BY `time` DESC LIMIT 0,$lim");
if($db->num_rows() == 0){
echo '<p class="feil">Ingen har pr&oslash;vet &aring; rane enda!</p>';
}
else{
while($r = mysqli_fetch_object($sql)){
if($r->kl == 0){
echo '<tr><td>'.user($r->aid).'</td><td><span style="color:#f00;">Feilet!</span></td><td>'.user($r->uid).'</td><td>'.date("H:i:s d.m",$r->time).'</td></tr>';
}
else{
echo '<tr><td>'.user($r->aid).'</td><td><span style="color:#0f0;">'.number_format($r->kl).' kr</span></td><td>'.user($r->uid).'</td><td>'.date("H:i:s d.m",$r->time).'</td></tr>';
}
}
}
echo '</table>';
?>
<?php
endpage();
?>
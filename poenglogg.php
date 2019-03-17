<?php
error_reporting(true);
include("core.php");
if(!(r1() || r2())){
  startpage("Ingen tilgang");
  echo '<p class="feil">Ingen tilgang!</p></br>
  <p>Ingen tilgang!</p>';
  endpage();
  die();
}
else{
startpage("Se alle spillere som har brukt poeng");
?>
<?php if(r1()) echo '<p>Trykk <a href="smslogg.php">Her</a> for å se sms-loggen. Der ser du hvem som har sendt sms til systemet!</p>';?>
<?php
/*Scriptet starter*/
$moddetvis = $db->query("SELECT * FROM `poenglogg` ORDER BY `id` DESC");
$betalvis = $db->query("SELECT * FROM `paymentcheck`");
?>
<table class="table">
  <tr>
    <th colspan="5">Poenglogg</th>
  </tr>
  <tr>
    <td>Spiller:</td>
    <td>Hva</td>
    <td>Tid poeng brukt</td>
  </tr>
    <?php
if($r->hva = NULL){
    echo '<td>Er mulig det skjedde en feil. Sjekk smslogg og feil.txt!.</td>';
}
while($r = mysqli_fetch_object($moddetvis)){
$e = mysqli_fetch_object($betalvis);
echo '
<tr>
<td>'.user($r->uid).'</td><td>'.$r->hva.'</td><td>'.date("H:i:s d.m.Y",$r->tid).'</td>
</tr>
';	
}
?>
</table>
<?php
endpage();
}

?>
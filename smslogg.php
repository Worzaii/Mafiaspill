<?php
include("core.php");
if(!r1()){
  startpage("Ingen tilgang");
  noaccess();
  endpage();
}
else{
  startpage("Se alle spillere som har kj&oslash;pt");
?>
<p>Om du forst&aring;r noe s&aring; kan du jo sjekke denne filen:</br><a href="smsloggfull.php">Smsloggfull</a>
<?php
  $sender = $_GET['sender'];
  $amount = $_GET['amount'];
  $message_id = $_GET['payment_id'];//unique id
  $uid = $_GET['cuid'];
/*Scriptet starter*/
$betalvis = $db->query("SELECT * FROM `paymentcheck` ORDER BY `id` DESC");
?>
<table class="table">
    <tr>
        <th colspan="5">SMS-Logg</th>
    </tr>
    <tr>
        <td>Spiller</td>
        <td>Kj&oslash;pte</td>
        <td>Mobilnummer</td>
        <td>Status</td>
        <td>Tid</td>
        

    </tr>
    <?php
while($r = mysqli_fetch_object($betalvis)){
echo '
<tr>
<td>'.user($r->uid).'</td><td>'.$r->poeng.' poeng</td><td>'.$r->mobil.'</td><td>'.$r->status.'</td><td>'.date("H:i:s d.m.Y",$r->time).'</td>
</tr>
';	
}
?>
</table>

<?php
endpage();
}
?>
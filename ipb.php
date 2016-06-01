<?php
define('THRUTT', "Sperredørp!");/*Sikkerhetsinnlegg for blokkering av oppdelte scripts*/
include("classes/class.php");
$db = new database;
$db->configure();
$db->connect();
$ipq = $db->query("SELECT * FROM `ipban` WHERE `ip`='".$_SERVER['REMOTE_ADDR']."'");
if($db->num_rows() == 1){
$ip=$db->fetch_object();
?>
<link rel="stylesheet" href="style.css" type="text/css" /> 
<table width="25%" class="C2">
<tr>
<th colspan="1" align="center">Du har blitt IP-Bannet!</th>
</tr>

<?php
echo "
<tr>
<td>Ip-adressen: <u>".$ip->ip."</u> er blitt utestengt!<br><br>
Grunn: ".$ip->grunn."<br>
Bannet dato: ".date("H:i:s d.m.Y",$ip->dato)."
</tr>";

echo '
</table>
<p>Om du mener at denne IP-bannen er gjort på gale vilkår/grunnlag, send en mail til denne mailadressen: <a href="mailto:support@mafia-no.net?subject=Ip-ban%20p&aring%20'.$_SERVER['REMOTE_ADDR'].'">support@mafia-no.net</a></p><br><br><a href="loggut.php">Logg ut!</a>';
}
else{
  header("Location: /Nyheter");
}


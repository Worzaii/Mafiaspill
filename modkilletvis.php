<?php
error_reporting(true);
include("core.php");
startpage("Se alle spillere som er modkillet");
/*Scriptet starter*/
$moddetvis = $db->query("SELECT * FROM `users` WHERE `moddet` = '1' ORDER BY `id` ASC");
?>
<table class="table">
    <tr>
        <th colspan="7">Spillere som er modkillet (Og info om dem)</th>
    </tr>
    <tr>
        <td colspan="2">Spiller:</td>
        <td>Grunn</td>
        <td>Modkillet av</td>
        <td>Sist aktiv</td>
        <td>Registrert</td>
        <td>Notat</td>
        

    </tr>
    <?php
while($r = mysqli_fetch_object($moddetvis)){
echo '
<tr>
<td style="text-align:center;width:60px;"><span style="font-size:20px;font-weight:bold;">#'.$r->id.'</td><td>'.user($r->id).'</td><td>'.$r->modgrunn.'</td><td>'.$r->modav.'</td><td>'.date("H.i.s | d-m-Y",$r->lastactive).'</td><td>'.date("H.i.s | d-m-Y",$r->regdato).'</td><td>'.$r->note.'</td>
</tr>
';	
}
?>
</table>

<?php
endpage();

?>
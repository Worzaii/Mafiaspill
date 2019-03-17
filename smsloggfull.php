<?php
error_reporting(true);
include("core.php");
if($obj->status > 1){
    startpage("Ingen tilgang");
    echo '<p class="feil">Ingen tilgang!</p></br>
    <p>Ingen tilgang!</p>';
endpage();
die;
}
else{
startpage("Se alle spillere som er modkillet");
/*Scriptet starter*/
$moddetvis = $db->query("SELECT * FROM `infoin`");
?>
<table class="table">
    <tr>
        <th colspan="2">Full info om poengkjøp</th>
    </tr>
    <tr>
        <td colspan="2">Info</td>
    <?php
while($r = mysqli_fetch_object($moddetvis)){
echo '
<tr>
<td>'.$r->full.'</td>
</tr>
';	
}
?>
</table>

<?php
endpage();
}
?>
<?php
  include("core.php");
  if($obj->status == 1 || $obj->status == 2){
  startpage("Modkill spiller")
?>
<h1>Modkill spiller</h1>
<p>N&aring;r dere modkiller noen, s&aring; SKAL dere bruke grunn.</p>
<?php
	if(isset($_POST['bruker']) &&isset($_POST['grunn']) && isset($_POST['valg'])){
		$user = $db->escape($_POST['bruker']);
		$valg = $db->escape($_POST['valg']);
		$grunn = $db->escape($_POST['grunn']);
		$s = $db->query("SELECT * FROM `users` WHERE `user` = '$user'")or die(mysqli_error($db->connection_id));
		$fet = $db->fetch_object($s);
		$userid = $fet->id;
    if($user->status == 1 && $obj->status > 1){
      feil("Du kan ikke modkille en administrator!");
    }
    else{
      if($valg == 1){
      if($db->query("UPDATE `users` SET `moddet` ='1',`modgrunn` ='$grunn',`modav` ='$obj->user',`status` ='6',`health`='0' WHERE `user` ='$user'")){
        if($db->affected_rows() == 1){
          echo '<p class="lykket">Spilleren har blitt modkillet, og kan ikke lengre logge inn!</p>';
          $db->query("INSERT INTO `modkillLogg`(`uid`,`time`,`active`,`reason`,`aid`,`action`) VALUES('".$fet->id."',UNIX_TIMESTAMP(),'1','$grunn','".$obj->id."','0')");
        }
        else{
          echo '<p class="feil">Brukeren ble ikke ber&oslash;rt, enten fordi spilleren ikke eksisterer, eller er allerede modkillet!</p>';
        }
      }
      else{
      echo "Spilleren kunne ikke bli modkillet, skriver ut databasefeil: ".mysqli_error($db->connection_id);
      }
      }
      if($valg == 2){
      if($db->query("UPDATE `users` SET `moddet` ='0',`modgrunn` ='',`modav` ='',`status` ='5',`health`='100' WHERE `user` ='$user'")){
        if($db->affected_rows() == 1){
          echo '<p class="lykket">'.$user.' har blitt gjennopplivet. Det burde v&aelig;re en veldig god grunn til dette!</p>';
          $db->query("INSERT INTO `modkillLogg`(`uid`,`time`,`active`,`reason`,`aid`,`action`) VALUES('".$fet->id."',UNIX_TIMESTAMP(),'1','$grunn','".$obj->id."','1')");
        }
        else{
          echo '<p class="feil">Brukeren ble ikke ber&oslash;rt, enten fordi spilleren ikke eksisterer, eller er allerede i live/gjenopplivet!</p>';
        }
      }
      else{
      echo "Spilleren kunne ikke bli gjenopplivet, skriver ut databasefeil: ".mysqli_error($db->connection_id);
      }
      }
    }
  }
if(isset($_GET['kill'])){
    $kill = $db->escape($_GET['kill']);
    $sporring = $db->query("SELECT * FROM `users` WHERE `id` = '$kill'");
    if($db->num_rows($sporring) == 0){NULL;}else{
        $asd = $db->fetch_object($sporring);
    }
}
?>
<form method="post" action="">
<table>
<tr>
<th colspan="2">Modkill / gjenoppliv spiller</th>
</tr>
<tr>
    <td>Bruker:</td><td><input type="text" value="<?=$asd->user?>" name="bruker"></br></td>
</tr>
<tr>
<td>Grunn:</td><td><input type="text" name="grunn"></td>
</tr>
<tr>
<td>Valg:</td><td>
<select name="valg">
<option value="1">Modkill spilleren</option>
<option value="2">Gjenoppliv spilleren</option>
</select>
</td>
</tr>
</table>
<input type="submit" value="Utf&oslash;r!"></form>
<?php
}
else{noaccess();}
endpage();
?>
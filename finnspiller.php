<?php
	include("core.php");
	startpage("Spillers&oslash;k");
?>
<h1>Finn spiller</h1>
<br> <center><form method="post"><input type="text" name="soktekst" value="" maxlength="15"/><input type="submit" class="finn" name="finn" value="Finn spiller"></form></center>
<?php
if(isset($_POST['finn'])){
	$soktekst = $db->escape($_POST['soktekst']);
	if(strlen($soktekst) <= 1){
		echo '
		<p class="feil"> Du m&aring; s&oslash;ke med mer enn ét tegn!</p>
		';
	}
	else{
		$getdb1   = $db->query("SELECT * FROM `users` WHERE `user` LIKE '%$soktekst%' LIMIT 50");
		$numrow   = $db->num_rows();
		if($numrow == 0){
			print "<center><font color='#ff0000'>Spillere med <b>$soktekst</b> ble ikke funnet.</font></center>";
		}
		else{
			while($row = mysqli_fetch_assoc($getdb1)){
				$nick = $row['user'];
				$id = $row['id'];
        if($row['health'] <= 0){
          echo '<center>'.'.</center>';
        }
				print '<center>'.user($row['id']).'</center>';
			}
		}
	}
}
?>
<?php
  endpage();
?>
<?php
include("core.php");
if(isset($_POST['oldpass']) && isset($_POST['newpass']) && isset($_POST['newpass2']) && isset($_POST['submit']))
{
	$old = md5(sha1($_POST['oldpass']));
	$new = md5(sha1($_POST['newpass']));
	$ne2 = md5(sha1($_POST['newpass2']));
	$res = null;
	if(strlen($_POST['oldpass']) <= 2 || strlen($_POST['newpass']) <= 2 || strlen($_POST['newpass']) <= 2 || $_POST['newpass'] != $_POST['newpass2'] || $obj->pass != $old)
	{
		$res .= '<p class="feil">Det har oppst&aring;tt en eller flere feil:</p>';
		if(strlen($_POST['oldpass']) <= 2)
		{
			$res .= '<p class="feil">Det gamle passordet var for kort eller ikke oppgitt!</p>';
		}
		if(strlen($_POST['newpass']) <= 2)
		{
			$res .= '<p class="feil">Det nye passordet var for kort eller ikke oppgitt!</p>';
		}
		if(strlen($_POST['newpass2']) <= 2)
		{
			$res .= '<p class="feil">Det gjentatte passordet var for kort eller ikke oppgitt!</p>';
		}
		if($_POST['newpass'] != $_POST['newpass2'])
		{
			$res .= '<p class="feil">Passordene var ikke like!</p>';
		}
		if($obj->pass != $old)
		{
			$res .= '<p class="feil"><u>Det gamle passordet du oppgav var ikke riktig!</u></p>';
		}
	}
	else
	{/* Ingen feil, prøver å endre passordet*/
		if($db->query("UPDATE `users` SET `pass` = '{$new}' WHERE `id` = '{$obj->id}' AND `pass` = '{$old}' LIMIT 1"))
		{
			$_SESSION['sessionzar'] = array($obj->user,$new);
			$res .= '<p class="lykket">Passordet er oppdatert!</p>';
		}
		else
		{
			if($obj->status == 1)
			{
				$res .= '<p>Feil i oppdatering: '.mysqli_error().'</p>';
			}
			else
			{
				$res .= '<p class="feil">Kunne ikke oppdatere passordet! Kontakt ledelsen for hjelp, da det kan inneholde feil i scriptet!</p>';
			}
		}
	}
}    
startpage("Endre passord");
?>
<h1>Endre passord</h1><?php if(isset($res)){echo $res;}?>
<form method="post" action="">
  <table class="table" style="width: 240px;">
    <tr>
    	<th colspan="2">Endre passord</th>
    </tr>
    <tr>
    	<td>Gammelt passord:</td>
    	<td><input type="password" name="oldpass" /></td>
    </tr>
    <tr>
      <td>Nytt passord:</td>
      <td><input type="password" name="newpass" /></td>
    </tr>
    <tr>
      <td>Gjenta passord:</td>
      <td><input type="password" name="newpass2" /></td>
    </tr>
    <tr>
    	<th colspan="2"><input type="submit" name="submit" value="Endre passordet!" /></th>
    </tr>
  </table>
</form>
<?php
endpage();
?>
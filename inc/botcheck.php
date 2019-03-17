<?php
	$row = mysql_query(""); // hent info fra brukerene dine
	
	if(isset($_POST['submitBtn'])){
		$secCode = isset($_POST['secCode']) ? strtolower($_POST['secCode']) : "";
			if($secCode == $_SESSION['securityCode']){
				echo 'Riktig kode!';
					echo '<br><br>';
				$redir = $_SESSION['rediranti'];
				redirect($redir, 1);
				unset($_SESSION['securityCode']);
				unset($_SESSION['antifeil']);
				unset($_SESSION['antitime']);
				$result = true;
			} else {
				if($_SESSION['antifeil'] >= 3){				// hvis brukeren har skrevet inn koden feil 3 ganger, (kan endres)
					
					// Her lager du hva som skal skje dersom brukeren har skrivd inn koden feil
					
				} 
				else {
				
					if(isset($_SESSION['antifeil'])){
						$_SESSION['antifeil'] = $_SESSION['antifeil']+1;
					} else {
						$_SESSION['antifeil'] = 1;
					}
					$forsokigjen = 4 - $_SESSION['antifeil'];
					if($row->antibot < 1){
								// Hvis brukeren ikke har skrivd den inn feil og database feltet er tomt
 						//mysql_query(""); oppdater databasen med 1 feil
 					 } else {
 								// Hvis brukeren har fler en 1 feil
						//mysql_query(""); oppdater databasen med tegnene; +1		
 					}
					echo 'Feil kode! Du har ".$forsokigjen." antall forsøk igjen!';
					$result = false;
				}
			}
		}
   
   if((!isset($_POST['submitBtn'])) || (!$result)){
?>      
		<form action="" method="post">
			<table width="100%">
				<tr>
					<th>ANTI-BOT</th>
				</tr>
				<tr>
					<td><b>De eneste tegnene som kommer i antiboten er sifferne 0-9, a-z og A-Z!</b></td>
				<tr>
					<td align="center"><img src="/antibot.php" alt="Oppdater siden" border="1"></td>
				</tr>
				<tr>
					<td align="center">
						ANTI-BOT: 
						<input class="text" name="secCode" type="text" size="10">
					</td>
				</tr>
				<tr>
					<td align="center"><input class="text" type="submit" name="submitBtn" value="Fullfør"></td>
				</tr>
			</table>  
		</form>
<?php
	} 
?>
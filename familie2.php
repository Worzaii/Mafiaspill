<?php
include("core.php");
if(r1() || r2()){
/*
Det første man skal se når man kommer inn på "familie" er familielistene
*/
startpage("Familier i spillet");
$q = $db->query("SELECT * FROM `familie` WHERE `active` = '1' ORDER BY `date` DESC")or die(mysqli_error($db->connection_id));
if($db->num_rows() == 0){
/*Ingen familier aktive*/
$familier='<tr><td colspan="6" style="font-style:italic;">Det er ingen familier!</td></tr>';
}
else{
while($r = mysqli_fetch_object($q)){
$open = ($r->open == 1) ? '<span style="color:#0f0;">&Aring;pen</span>' : '<span style="color:#0f0;">Lukket</span>';
$familier='<tr><td>'.$r->name.'</td><td>'.user($r->boss).'</td><td>'.user($r->uboss).'</td><td>'.date("H:i:s d.m.y",$r->date).'</td><td>'.$open.'</td><td>'.$r->kills.'</td></tr>';
}
}
echo '<h1>Familier i spillet</h1>
<p>Werzaire jobber med dette scriptet.</p>
<table class="table">
	<thead>
		<tr>
			<th colspan="6">Familier:</th>
		</tr>
		<tr>
			<th>Navn</th><th>Boss</th><th>Underboss</th><th>Dato Opprettet</th><th>Godtar S&oslash;knader?</th><th>Antall drap</th>
		</tr>
	</thead>
	<tbody>
	'.$familier.'
	</tbody>
</table>
';

}
else{
	startpage();
	echo '<h1>Siden jobbes med, kom igjen senere.</h1><p class="feil">Ingen tilgang, siden jobbes med.</p>';
	/*Lister opp familier*/
	
}
endpage();
?>
<?php
die();
include("core.php");
if($obj->status <= 2 || $obj->status == 6){
startpage("Picmakerpanel");
?>
<h1>PicmakerPanelet</h1>
<p>Dette panelet er under konstruksjon! Følgende muligheter vil komme:</p>
<ol style="margin-left:20px;">
<li>Motta forespørsler om avatar/profilbilde/sig</li>
<li>Spesielle måter å betale på/motta pengene</li>
<li>Viser en vegg med bildene til picmakeren</li>
<li>Wall of fame?</li>
</ol>
<?php
if($obj->status <= 2 || $obj->status == 6){
$self = $_SERVER['PHP_SELF'];//Scriptet selv
echo <<<ENDHTML
<p><a href="$self?s=1" title="Godta oppdrag, Avslå oppdrag, Fullfør oppdrag">Administrer søknader!</a><a href="$self?s=2" title="Steng av mulighet til å søke om bilde hos deg.">Steng av mulighet til å søke!</a></p>
ENDHTML;
if(isset($_GET['s'])){
$s = $_GET['s'];
//if(!is_numeric($s)){
//echo '<p class="feil">Siden er ikke gyldig!</p>';
//}
//else{
if($s == 1){//Administrasjon
echo'
<h2>Administrason av søknader!</h2>
<!--<p>Under her vil det vises en lang tabell med forskjellige spillere listet opp med deres forespørsler. Du får 4 knapper ved slutten av tabellen der du kan gjøre følgende:
</p>
<ol style="margin-left:20px;">
<li>Godkjenne søknad: slik at du kan begynne å jobbe med den. Samtidig får søkeren svar om at du har begynt/godkjent søknaden.</li>
<li>Avslå søknad: slik at du kan varsle søkeren om at du ikke kan/er uenig eller lignende.</li>
<li>Slette søknader: Slik at det blir mer ryddig i tabellen. Er en søknad ikke avslått/godkjent vil den automatisk bli avslått.</li>
<li>Fullføre søknad: Slik at du kan levere det ferdige bildet til brukeren. Når han har godtatt det, så mottar du pengene du krevet for bildet.</li>
</ol>-->
<p>Under her vil det vises en lang tabell med forskjellige forespørsler der du kan behandle bilder.</p>
<li>Ohyeah!</li>
<li>Ohyeahx2</li>
';
}
}
}
//}
?>
<p><a href=""></a></p>
<?php
}
else{
startpage("Ingen tilgang!");
echo '
<h1>Ingen tilgang!</h1><p>Du har ikke tilgang til denne siden. Mener du dette er feil, ta kontakt med en admin.</p>
';
}
endpage();
?>
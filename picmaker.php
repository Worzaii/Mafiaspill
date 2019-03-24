<?php
die();
include("core.php");
if($obj->status <= 2 || $obj->status == 6){
startpage("Picmakerpanel");
?>
<h1>PicmakerPanelet</h1>
<p>Dette panelet er under konstruksjon! F&oslash;lgende muligheter vil komme:</p>
<ol style="margin-left:20px;">
<li>Motta foresp&oslash;rsler om avatar/profilbilde/sig</li>
<li>Spesielle m&aring;ter &aring; betale p&aring;/motta pengene</li>
<li>Viser en vegg med bildene til picmakeren</li>
<li>Wall of fame?</li>
</ol>
<?php
if($obj->status <= 2 || $obj->status == 6){
$self = $_SERVER['PHP_SELF'];//Scriptet selv
echo <<<ENDHTML
<p><a href="$self?s=1" title="Godta oppdrag, Avsl&aring; oppdrag, Fullf&oslash;r oppdrag">Administrer s&oslash;knader!</a><a href="$self?s=2" title="Steng av mulighet til &aring; s&oslash;ke om bilde hos deg.">Steng av mulighet til &aring; s&oslash;ke!</a></p>
ENDHTML;
if(isset($_GET['s'])){
$s = $_GET['s'];
//if(!is_numeric($s)){
//echo '<p class="feil">Siden er ikke gyldig!</p>';
//}
//else{
if($s == 1){//Administrasjon
echo'
<h2>Administrason av s&oslash;knader!</h2>
<!--<p>Under her vil det vises en lang tabell med forskjellige spillere listet opp med deres foresp&oslash;rsler. Du f&aring;r 4 knapper ved slutten av tabellen der du kan gj&oslash;re f&oslash;lgende:
</p>
<ol style="margin-left:20px;">
<li>Godkjenne s&oslash;knad: slik at du kan begynne &aring; jobbe med den. Samtidig f&aring;r s&oslash;keren svar om at du har begynt/godkjent s&oslash;knaden.</li>
<li>Avsl&aring; s&oslash;knad: slik at du kan varsle s&oslash;keren om at du ikke kan/er uenig eller lignende.</li>
<li>Slette s&oslash;knader: Slik at det blir mer ryddig i tabellen. Er en s&oslash;knad ikke avsl&aring;tt/godkjent vil den automatisk bli avsl&aring;tt.</li>
<li>Fullf&oslash;re s&oslash;knad: Slik at du kan levere det ferdige bildet til brukeren. N&aring;r han har godtatt det, s&aring; mottar du pengene du krevet for bildet.</li>
</ol>-->
<p>Under her vil det vises en lang tabell med forskjellige foresp&oslash;rsler der du kan behandle bilder.</p>
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
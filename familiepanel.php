<?php
  include("core.php");
  startpage("Familiepanel");
?>
	<table align="center">
    <tr>
      <td class="dra"><img src="http://i.imgur.com/5AEqu.png" alt="Familiesenteret" /></td>
    </tr>
  </table>
<?php
if(isset($_GET['side'])){
$side = $_GET['side'];
if($side == "oppfam"){
  if($obj->family == null){
    if(isset($_POST['opprfam'])){
      //Lage ny familie starter
      $navn = $db->escape($_POST['opprfam']);
      if(strlen($navn) <= 5){
        echo '<font color="#ba0000">Familienavnet er for kort! Det må være mellom 5-25 tegn.</font>';
      }
      else if(strlen($navn) >= 26){
        echo '<font color="#ba0000">Familienavnet er for langt! Det må være mellom 5-25 tegn.</font>';
      }
      else if(($obj->hand <= 199999999) && $obj->id > 1){
        echo '<font color="#ba0000">Du har ikke råd til å opprette en familie, du må minst ha 200,000,000 kr ute på hånda når du kjøper/oppretter en familie!</font>';
      }
      else if((strlen($navn) >= 2 && strlen($navn) <= 25 && $obj->hand >= 200000000) || $obj->id==1){
        $db->query("SELECT * FROM `familier` WHERE `lagtned` = '0'");
        //echo 'Det er '.$db->num_rows().' familier i spillet';
        if($db->num_rows() >= 5 && $obj->id!=1){
          //Det er allerede max antall tillatte familier i spillet
          echo '<p class="feil">Det er ikke plass til flere familier i spillet.</p>';
        }
        else if($db->num_rows() <= 5 || $obj->id==1){
          //Plass til flere familier, så sjekker nå om navnet er tilgjengelig
          $newsql = $db->query("SELECT * FROM `familier` WHERE `Navn` = '$navn' AND `lagtned` = '0'")or die('Feil:'.mysqli_error($db->connection_id));
          if($db->num_rows() == 0){
            //Oppretter familien
            if(mysqli_query($db->connection_id,"INSERT INTO `familier`(`Navn`,`Leder`,`TimeMade`) VALUES('$navn','{$obj->id}','".time()."')")){
              if(mysqli_query($db->connection_id,"UPDATE `users` SET `family` = '".mysqli_insert_id($db->connection_id)."',`hand` = (`hand` - 200000000) WHERE `id` = '{$obj->id}'")){
                echo '<p style="color:#0f0;text-align:center;font-size:14px;">Du eier nå familien '.$navn.'! <a href="familiepanel.php?side=konfam">Gå til kontrollpanel!</a></p>
                <p class="lykket">200,000,000kr har blitt trukket fra hånden din!</p>';
              }
              else{
                echo '<p>Error: '.mysqli_error($db->connection_id).'</p>';
              }
            }
            else{
              echo '<p>Error: '.mysqli_error($db->connection_id).'</p>';
            }
          }
          else{
          //Familienavn ikke tilgjengelig
          echo '<font color="#ba0000">Familienavnet er ikke tilgjengelig, vennligst velg et annet.</font>';
          }
        }
      }
    }
    //Viser panel for å opprette en familie
    echo '
    <form method="post" action="familiepanel.php?side=oppfam">
    <table>
    <tr>
    <th colspan="3">Opprett familie:</th>
    </tr>
    <tr>
    <td>Familienavn</td><td>:</td><td><input type="text" class="textbox" name="opprfam"></td>
    </tr>
    <tr>
    <td colspan="3" style="margin:0px auto;text-align:center;"><input type="submit" value="Start familie!" class="button"></td>
    </tr>
    </table>
    </form>
    ';
  }
  else{
    echo '<font color="#ba0000">Du er allerede i en familie, og kan derfor ikke opprette en.</font>';
  }
}
else if($side == "sokfam"){
if($obj->family == null){

if(isset($_POST['soknad']) && isset($_POST['radch'])){
$sok = $db->escape($_POST['soknad']);
$famnr = $db->escape($_POST['radch']);
$time = date("H:i:s | d.m.Y");
$stam = time();
$get = $db->query("SELECT * FROM `familier` WHERE `id` = '$famnr' AND `lagtned` = '0'")or die("Feil: ".mysqli_error($db->connection_id));
$geti = $db->fetch_object($get);
$allerede = $db->query("SELECT * FROM `famreq` WHERE `usern` = '$obj->id' AND `famname` = '$geti->id' AND `bes` = '0' ORDER BY `id` DESC LIMIT 1")or die("Feil: ".mysqli_error($db->connection_id));
if($db->num_rows() >= 1){
echo '<p style="color:#f00">Du har allerede søkt i denne familien, du må vente til gjenglederen godkjenner eller avslår din søknad før du kan søke på nytt!</p>';
}
else{
if($db->query("INSERT INTO `famreq`(`usern`,`famname`,`reqtext`,`timestamp`,`time`) VALUES('$obj->id','$geti->id','".utf8_encode($sok)."','$stam','$time')")){
echo '<font color="#0f0">Du har søkt inn i <b>'.$geti->Navn.'</b>! Vennligst vent på svar fra gjengens leder.</font>';
}
else{
echo '<font color="#ba0000">Kunne ikke query-ere.</font>';
die("Feil: ".mysqli_error($db->connection_id));
}
}
}
echo <<<ENDHTML
<form method="post" action="familiepanel.php?side=sokfam">
<table class="table" style="width:50%;">
<tr>
<th colspan="2">Søk deg inn i en søkbar familie her:</th>
</tr>
<tr>
<th>Familienavn</th><td style="width:25px;">Velg;</td>
</tr>
ENDHTML;
$sql = $db->query("SELECT * FROM `familier` WHERE `apen` = '1' AND `lagtned` = '0' ORDER BY `TimeMade` DESC")or die("Feil: ".mysqli_error($db->connection_id));
if($db->num_rows() >= 1){
while($r = mysqli_fetch_object($sql)){
echo '<tr><td><a href="familievis.php?fam='.$r->Navn.'">'.$r->Navn.'</a></td><td><input type="radio" name="radch" value="'.$r->id.'"></td></tr>';
}
echo '<tr class="c_2"><th colspan="2">Skriv en søknad til den valgte familien.</th></tr>';
echo '<tr class="c_3"><td colspan="2">
<p style="color:#fff;text-align:center;font-weight:bold;">Skriv søknad:</p>
<div class="center" style="width:100%;"><p>
<textarea name="soknad" cols="33" rows="17">Søknaden her!</textarea>
</p></div>
<p style="text-align:center">
<input type="submit" value="Søk nå!" class="button">
</p>
</td></tr>';
}
else if($db->num_rows() == 0){
echo '<tr><th colspan="2">Ingen familier er åpne for søknader.</th></tr>';
}
echo '</table></form>';
}
else{
echo '<font color="#ba0000">Du er allerede i en familie, derfor kan du søke deg inn i en!</font>';
}
}
else if($side == "konfam"){
if($obj->family != NULL){
//Hvis spiller er i familie
$check = $db->query("SELECT * FROM `familier` WHERE `Leder` = '{$obj->id}' AND `lagtned` = '0'");
$checkub = $db->query("SELECT * FROM `familier` WHERE `Ub` = '{$obj->id}' AND `lagtned` = '0'");
if($db->num_rows($check) == 1 || $db->num_rows($checkub) == 1){
$fm=true;
$famnames = famidtoname($obj->family);
/*Spilleren er leder, og vil ha full tilgang til alt i familien*/
echo <<<ENDHTML
<table class="table">
<tr class="c_1">
<th>Kontrollpanel for din familie $famnames</th>
</tr>
<tr>
<td>
<table class="table">
<tr class="c_2">
<td><a href="familiepanel.php?side=konfam&tab=1">Se fulle søknader til familien!</a></td>
<td><a href="familiepanel.php?side=konfam&tab=2">Bank & Doneringsoversikt</a></td>
<td><a href="nyforum.php?type=5">Se familiens forum!</a></td>
</tr>
<tr class="c_3">
<td><a href="familiepanel.php?side=konfam&tab=4">Legg ned familien!</a></td>
<td><a href="familiepanel.php?side=konfam&tab=5">Se loggen for familien!</a></td>
<td><a href="familiepanel.php?side=konfam&tab=7">Endre familie innstillinger</a></td>
</tr>
<tr>
<td class="c_3" colspan="3" style="text-align:center;"><a href="kulefabrikk.php">Kulefabrikk</a></td>
</tr>
</table>
</td>
</tr>
</table>
ENDHTML;
if(isset($_GET['tab'])){
//Viser kontrollfunksjoner
$tab = $_GET['tab'];
if($tab == 1){
//Vise Søknader
function enter($txt){
$txt = str_replace("\n","<br />",$txt);
return utf8_decode(($txt));
}

$sql = $db->query("SELECT * FROM `famreq` WHERE `famname` = '$obj->family' AND `bes` = '0'")or die(mysqli_error($db->connection_id));
$antub = $db->num_rows($sql);
echo <<<ENDHTML
<form method="post" action="familiepanel.php?side=konfam&tab=1">
<table class="table">
<tr>
<th colspan="3" style="text-align:center;font-variant:small-caps;font-size:14px;">Søknader til familien din: $antub besvart(e)</th>
</tr>
ENDHTML;
if($antub == 0){
echo '<tr><td colspan="3"><i>Det er ingen søknader til familien akkurat nå...</i></td></tr>';
}
else{
while($r = mysqli_fetch_object($sql)){
//Henter informasjonen ifra databasen
$sel = $db->query("SELECT * FROM `users` WHERE `id` = '".$r->usern."'")or die(mysqli_error($db->connection_id));
$usi = $db->fetch_object($sel);
echo '
<tr><td colspan="3"><b>Søker:</b> '.user($usi->id).' <b>Dato:</b> '.$r->time.'</td></tr>
<tr>
<td valign="top" style="width:100px;padding:0px;"><img style="height:100px;width:100px;" src="'.$usi->image.'"</td><td valign="top">'.enter($r->reqtext).'</td><td style="width:15px;" align="center"><input name="radion" type="radio" value="'.$r->id.'"></td>
</tr>
';
}//While END
}//Else END
echo '</table>';
if(isset($_POST['no'])){
//Avslår søknaden
$id = $db->escape($_POST['radion']);
$us = $db->query("SELECT * FROM `famreq` WHERE `id` = '$id'");
$useri = $db->fetch_object($us);
$res = $db->escape($_POST['noreason']);
if($db->query("UPDATE `famreq` SET `bes` = '1',`res` = '0' WHERE `id` = '$id'")){
echo '<p style="color:#f00;">Søknaden har blitt avslått.</p>';
$beskjed = 'Din søknad til '.famidtoname($useri->famname).' har blitt avslått.
Grunn;<br />'.$noreason;
$db->query("INSERT INTO `mail2`(`fid`,`tid`,`title`,`message`,`time`) VALUES ('$obj->id','$obj->id','Avslått søknad','$beskjed','$dato')");
}
else{
echo '<p>Feil: '.mysqli_error($db->connection_id).'</p>';
}
}
else if(isset($_POST['yes'])){
//Godkjenner søknaden
$id = $db->escape($_POST['radion']);
$res = $db->escape($_POST['yesreason']);
$get = $db->query("SELECT * FROM `famreq` WHERE `id` = '$id'")or die(mysqli_error($db->connection_id));
$geti = $db->fetch_object($get);
$famnavn = famidtoname($geti->famnavn);
$db->query("UPDATE `famreq` SET `bes` = '1',`res` = '1' WHERE `id` = '$id'")or die(mysqli_error($db->connection_id));
$db->query("UPDATE `users` SET `family` = '$geti->famname' WHERE `id` = '$geti->usern'")or die(mysqli_error($db->connection_id));
$beskjed = '[b]Din søknad til '.famidtoname($geti->famname).' har blitt godkjent!
Velkomstmelding;[/b]' .$res;
if($db->query("INSERT INTO `mail2`(`fid`,`tid`,`title`,`message`,`time`) VALUES ('".$geti->usern."','".$geti->usern."','Godkjent søknad','$beskjed','".time()."')")){
//Velkomstmelding utført?
echo '<font color="#0f0">Velkomstmelding sendt!</font>';
}
else{
echo '<font color="#ba0000">Kunne ikke sende velkomstmelding!<br />Grunn: '.mysqli_error($db->connection_id).'<br />Spørring: "INSERT INTO `mail2`(`fid`,`tid`,`title`,`message`,`time`) VALUES ("'.$geti->usern.'","'.$geti->usern.'",\'Godkjent soknad\',\'$beskjed\',\'$dato\')"</font>';
}
}
echo '
<table>
<tr>
<td>Avslå-grunn: <br /><textarea style="width:250px;height:130px;margin:5px;" name="noreason" title="Hvis avslått, så kan du velge å skrive en tilbakemelding her for brukeren som søkte.">Din søknad til '.$famnames.' ble desverre avslått.</textarea><br /><input type="submit" value="Avslå valgt søknad!" class="button" name="no">
</td>
<td>
Velkomstmelding: <br /><textarea style="width:250px;height:130px;margin:5px;" name="yesreason" title="Hvis godkjent, så kan du skrive en velkomstmelding her!">Din søknad til '.$famnames.' ble godkjent!</textarea><br /><input type="submit" value="Godkjenn valgt søknad" class="button" name="yes">
</td>
</tr>
</table>
</form>
';
}
if($tab == 2){
if(isset($_GET['sub']) && $_GET['sub'] == 1){
if($db->num_rows($checkub) == 1){
feil('Du har ingen tilgang!');
}
else{
//Banken
$ssqqll = $db->query("SELECT * FROM `familier` WHERE `Leder` = '$obj->id'");
$getfetch = $db->fetch_object($ssqqll);
//Banken
if(isset($_POST['money'])){
$belop = $db->escape($_POST['money']);
if($belop <= -1){
feil('Du kan ikke ta ut mindre en 0 kr.');
}
else{
if(isset($_POST['taut'])){
if(isset($_POST['alt']) && $_POST['alt'] == 1){
$belop = $getfetch->Bank;
}
if($getfetch->Bank >= $belop){
if(!$db->query("UPDATE `users` SET `hand` = (hand + $belop) WHERE `id` = '$obj->id'")){$gjennom = false;echo '<p>Kunne ikke gi deg pengene! Grunn: '.mysqli_error($db->connection_id).'</p>';}else{$gjennom = true;}
if(!$db->query("UPDATE `familier` SET `Bank` = (Bank - $belop) WHERE `Leder` = '$obj->id'")){$gjennom2 = false;echo '<p>Kunne ikke ta ut pengene av banken! Grunn: '.mysqli_error($db->connection_id).'</p>';}else{$gjennom2 = true;}
if($gjennom == true && $gjennom2 == true){
echo '<p>Du har tatt ut '.number_format($belop).' fra familiebanken!</p>';
echo '<p>Du har satt inn '.number_format($belop).' i familiebanken!</p>';$log = array(
1=>"Tok ut ".number_format($belop)." fra banken"
);
famlogg($obj->id,$log[1]);
}
else if($gjennom == false || $gjennom2 == false){
echo '<p>Det hendte noe uforutsett! Det kan hende du ikke mottok pengene fra banken, eller at banken ikke ble tømt for penger! (Dette blir senere knyttet opp mot varsler til ledelsen!)</p>';
}
}//Ta ut END
else if($getfetch->Bank < $belop){
echo '<p>Du har ikke så mye penger i familiebanken!</p>';
}
}
else if(isset($_POST['sein'])){
if(isset($_POST['alt']) && $_POST['alt'] == 1){
$belop = $obj->hand;
}
if($obj->hand >= $belop){
if(!$db->query("UPDATE `users` SET `hand` = (hand - $belop) WHERE `id` = '$obj->id'")){$gjennom = false;echo '<p>Kunne ikke ta fra deg pengene! Grunn: '.mysqli_error($db->connection_id).'</p>';}else{$gjennom = true;}
if(!$db->query("UPDATE `familier` SET `Bank` = (Bank + $belop) WHERE `Leder` = '$obj->id'")){$gjennom2 = false;echo '<p>Kunne ikke sette inn pengene i familiebanken! Grunn: '.mysqli_error($db->connection_id).'</p>';}else{$gjennom2 = true;}
if($gjennom == true && $gjennom2 == true){
echo '<p>Du har satt inn '.number_format($belop).' i familiebanken!</p>';$log = array(
1=>"Satt inn ".number_format($belop)." i banken"
);
famlogg($obj->id,$log[1]);
}
else if($gjennom == false || $gjennom2 == false){
echo '<p>Det hendte noe uforutsett! Det kan hende du ikke ble trekt for penger, eller at banken ikke ble fikk beløpet! (Dette blir senere knyttet opp mot varsler til ledelsen!)</p>';
}
}//Ta ut END
else if($obj->hand < $belop){
echo '<p>Du har ikke så mye penger ute!</p>';
}
}
else if(isset($_POST['tauta'])){
if($db->query("UPDATE `familier` SET `Bank` = '0' WHERE `Leder` = '$obj->id'")){
if($db->query("UPDATE `users` SET `hand` = (hand + $getfetch->Bank) WHERE `id` = '$obj->id'")){
echo '<p>Du har tatt ut alle pengene som var i familiebanken. Du tok ut: '.number_format($getfetch->Bank).'kr</p>';
}
else{
echo '<p>Kunne ikke gi deg pengene på handa: '.mysqli_error($db->connection_id).'<br />(Dette blir senere knyttet opp mot varsler til ledelsen!)</p>';
}
}
else{
echo '<p>Kunne ikke ta ut pengene fra familiebanken: '.mysqli_error($db->connection_id).'<br />(Dette blir senere knyttet opp mot varsler til ledelsen!)</p>';
}
}
else if(isset($_POST['seina'])){
if($db->query("UPDATE `familier` SET `Bank` = (Bank + $obj->hand) WHERE `Leder` = '$obj->id'")){
if($db->query("UPDATE `users` SET `hand` = '0' WHERE `id` = '$obj->id'")){
echo '<p>Du har satt inn alle pengene du hadde ute. Du satt inn: '.number_format($obj->hand).'kr til familiebanken!</p>';
}
else{
echo '<p>Kunne ikke ta fra deg pengene: '.mysqli_error($db->connection_id).'<br />(Dette blir senere knyttet opp mot varsler til ledelsen!)</p>';
}
}
else{
echo '<p>Kunne ikke gi pengene til familiebanken: '.mysqli_error($db->connection_id).'<br />(Dette blir senere knyttet opp mot varsler til ledelsen!)</p>';
}
}
}
}
$current = number_format($getfetch->Bank);
echo <<<ENDHTML
<form method="post" action="familiepanel.php?side=konfam&tab=2&sub=1">
<table class="table" style="width:400px;">
<tr class="c_1">
<th colspan="2">Banken</th>
</tr>
<tr class="c_1">
<th style="text-align:right;">Penger i banken:</th><td>$current</td>
</tr>
<tr>
<td style="text-align:right;">Beløp</td><td><input type="number" name="money" maxlenght="9999999999999"></td>
</tr>
<tr>
<td colspan="2">
<input type="submit" value="Ta ut valgt beløp!" name="taut" class="button">
<input type="submit" value="Sett inn valgt beløp!" name="sein" class="button"><br><input type="checkbox" name="alt" value="1">Alt?
</td>
</tr>
</table>
</form>
ENDHTML;
}
}
else if(isset($_GET['sub']) && $_GET['sub'] == 2){
/*Viser doneringssiden*/
?>
<table class="table">
<thead>
<tr><th>Spiller</th><th>Donert tilsammen</th></tr>
</thead>
<tbody>
<?php
$q = $db->query("SELECT `uid`,SUM(`sum`) AS `tils` FROM `familiedoner` WHERE `fid` = '{$obj->family}' GROUP BY `uid` ORDER BY `id` DESC");
if($db->num_rows() >= 1){
while($r = mysqli_fetch_object($q)){
echo '<tr><td>'.user($r->uid).'</td><td>'.number_format($r->tils).' kr</td></tr>';
}
}
else{
echo '<tr><td colspan="3" style="text-align:center;">Ingen har donert.</td></tr>';
}
?>
</tbody>
</table>
<?php
}
else{
/*Viser linker*/
echo '<a href="familiepanel.php?side=konfam&tab=2&sub=1">Banken</a> | <a href="familiepanel.php?side=konfam&tab=2&sub=2">Doneringsoversikt</a>';
}
}
if($tab == 3){
//Forumet
function tom($tittel){
if(strlen($tittel) == 0){
$tittel = "Uten tittel!";
}
return ($tittel);
}
// Dette er html + scriptet.
if(isset($_GET['nytrad'])){}else{echo'<p><a href="familiepanel.php?side=konfam&tab=3&nytrad" class="knapp">&rarr; Lag ny tråd!</a></p>';}
if(isset($_GET['topic'])){
$tema = $db->escape($_GET['topic']);
if(!is_numeric($tema)){
echo '<font color="#ba0000">Tråden er ugyldig!</font>';
}
else if($db->num_rows($db->query("SELECT * FROM famforum WHERE id = '$tema' AND `slettet` = '0'")) == 1){
$sql = $db->query("SELECT * FROM famforum WHERE id = '$tema' AND `slettet` = '0'");
$sql2 = $db->query("SELECT * FROM users WHERE id = '$obj->id'");
$nums=$db->query("SELECT * FROM famsvar WHERE tradid = '$tema' AND `slettet` = '0'");
$siden = $db->escape($_GET['siden']);
if(!is_numeric($siden) || !isset($_GET['siden'])){
$siden = 1;
}
$num = $db->num_rows($nums);
$res = ($siden-1)*15;
$ant1 = $num / 15; //Denne er til sideviseren
$ant = ceil($ant1);
$sql3=$db->query("SELECT * FROM famsvar WHERE tradid = '$tema' AND `slettet` = '0' ORDER BY time DESC LIMIT $res,15")or die(mysqli_error($db->connection_id));

$g = $db->fetch_object($sql);
$u = $db->fetch_object($sql2);
$trad = htmlentities(utf8_decode($g->Tradnavn));
$trs = $g->Tradstarter;
$imguser=$db->query("SELECT * FROM `users` WHERE `id` = '$trs'");
$imgg=$db->fetch_object($imguser);
$usbi=$imgg->image;
$innh = $g->Melding;
$id = $g->id;
$innh = bbcodes($innh,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1);
$innh = str_replace("\n","<br />",$innh);
$dato = date("H:i:s | d-m-y",$g->time);
$userstart = user($g->Tradstarter);
if(isset($_GET['action']) && $_GET['action'] == "fmslett"){
if($fm == false){
echo '<div class="mislykket_feil">Du har ingen tilgang!</div>';
}
else{
$id = $db->escape($_POST['deletetic']);
//Skjekker om innlegg finnes
$db->query("SELECT * FROM `famsvar` WHERE id = '$id' AND `slettet` = '0'")or die(mysqli_error($db->connection_id));
if($db->num_rows() == 1){
//sletter innlegget
if($db->query("UPDATE `famsvar` SET `slettet` = '1' WHERE `id` = '$id' AND `slettet` = '0'")){
echo '<font color="green">Innlegget du valgte å slette er nå slettet! Sender deg tilbake til tråden!
<script>
function redirect()
{
document.location = "familiepanel.php?side=konfam&tab=3&topic='.$tema.'";
}
setTimeout( "redirect();", 3000 );
</script></font>';
}
else{
echo '<div class="mislykket_feil">Kunne ikke kjøre query: '.mysqli_error($db->connection_id).'</div>';
}
}
else{
echo '<div class="mislykkket_feil">Kunne ikke finne innlegget. Sender deg tilbake!
<script>
function redirect()
{
document.location = "familiepanel.php?side=konfam&tab=3";
}
setTimeout( "redirect();", 1000 );
<script></div>
';
}
}
}
echo <<<ENDHTML
<div class="forum_post">
<div class="post_top">
<div class="links"></div>
<h1>$trad</h1>
<br />
<h2>Opprettet <b>$dato</b></h2>
</div>
<div class="post_content">
<div class="post_left">
<div class="profileimage">
<img src="$usbi" height="100" width="100" alt="" class="profileimg" /></a>
$userstart<br />

</div>
<br />
<div class="playerstats">
<a href="familiepanel.php?side=konfam&tab=3&nysvar&trad=$tema">Besvar tråd</a><br /><a href="familiepanel.php?side=konfam&tab=3&nytrad">Opprett tråd</a><br /><a href="familiepanel.php?side=konfam&tab=3&slett&id=$id">Slett tråd</a>
</div>

</div>
<div class="post_right">
<div class="topic_text">
        $innh
        </div>
</div>
</div>
</div>
ENDHTML;

//side script.

$amount = 10;

$csql = "SELECT * FROM famsvar WHERE tid = '".$db->escape($_GET['id']). "' AND `slettet` = '0' ORDER BY `time` DESC";
$cres = $db->query($csql) or die(mysqli_error($db->connection_id));
$totaltopics = $db->num_rows($cres);

if (empty($_GET['side'])){
$side = 2;
}else{
if(is_numeric($_GET['side'])){
$side = $_GET['side'];
}else{
$side = 2;
}
}

$min = $amount * ( $side - 2 );
$max = $amount;

if($db->num_rows($sql3) >= 1){
if($fm == true){
$top = '
<form method="post" action="familiepanel.php?side=konfam&tab=3&topic='.$tema.'&action=fmslett">
<div style="width:600px;text-align:right;"><input type="submit" value="Slett innlegget!" name="submit" class="button"></div>
';
echo $top;
}
while($r = mysqli_fetch_object($sql3)){
$nysql = $db->query("SELECT * FROM `users` WHERE id = '{$r->usern}'")or die(mysqli_error($db->connection_id));
$get = $db->fetch_object($nysql);
$sigg = $r->signatur;
$svar = htmlentities($r->svaret);
$svar = str_replace("\n","<br />",$svar);
if($fm == true){
$extra = '<input name="deletetic" type="radio" value="'.$r->id.'">';
}
echo '
<table align="center" style="width:100%;margin:0px auto;">
<tr>
<td class="dra">
<table>
<tr>
<th style="width:110px;" class="dra">'.user($r->usern).'<br /><img style="width:100px;height:100px;" src="'.$get->image.'" alt=""></th>
<td class="dra" style="width:100%;" valign="top">'.$extra.'<b>Dato skrevet:</b> '.date("H:i:s | d-m-Y",$r->time).'<br /><br />'.$svar.'</td>
</table>
</td>
</tr>
</table>
';
}
if($fm == true){echo '</form>';}
echo '<p style="text-align:center;">';
for($i=1;$i<=$ant;$i++){
echo '<a href="familiepanel.php?side=konfam&tab=3&topic='.$tema.'&siden='.$i.'">{'.$i.'} </a>';
}
echo '</p>';
}
else{
echo '<font color="#ba0000">Ingen svar postet!</font>';
}
}
else{
echo '<font color="#ba0000">Tråden ble ikke funnet, den kan ha blitt slettet.</font>
';
}
}//Vise tråd? END
else if(isset($_GET['nysvar']) && isset($_GET['trad'])){
$trad = $db->escape($_GET['trad']);
$sql = $db->query("SELECT * FROM famforum WHERE id = '$trad'");
if($db->num_rows($sql) != 1){
echo '<font color="#ba0000">Denne tråden du ønsker å svare eksisterer ikke lengre. Den kan ha blitt slettet.</font>';
}
else{
if(isset($_POST['nysvaret'])) {
$svaret = $db->escape($_POST['nysvaret']);
$svaret = str_replace("\n","<br>",$svaret);
$svaret = str_replace("\"","&quote;",$svaret);
if($db->num_rows($sql) == 1){
$time = time();
if($db->query("INSERT INTO famsvar(tradid,usern,svaret,time) VALUES('$trad','{$obj->id}','".utf8_encode($svaret)."','$time')")){
echo '<div class="lykket_green">Du har nå besvart tråden, sender deg tilbake...</div>';
echo '
<script>
function redirect()
{
document.location = "familiepanel.php?side=konfam&tab=3&topic='.$trad.'";
}
setTimeout( "redirect();", 3000 );
</script>
';
}
else{
echo 'Feil: '.mysqli_error($db->connection_id);
}
}
}
else{
}
echo '
<form method="post" action>
<div class="box_k w500">
<h1 class="big">Besvar tråden</h1>

<dl class="dt_70 form">
<dt>Melding</dt>
<dd><textarea name="nysvaret" style="width: 410px;max-width: 420px;max-height: 500px" rows="15" class="text_box"></textarea></dd>
<dt><input type="submit" class="button" value="Svar" /></dt>

</dl>
</div>
</form>
';
}
}//Svar tråd END
else if(isset($_GET['nytrad'])){
if($_GET['nytrad'] == "opprett"){
$melding = $db->escape($_POST['teksten']);
$melding = str_replace("\rn","<br />",$melding);
$tittel = $db->escape($_POST['tittelen']);
$sticky = $db->escape($_POST['sticky']);
if($sticky == 1){
$sticky = 1;
}
else{
$sticky = 0;
}
if($db->query("INSERT INTO famforum(Tradnavn,Tradstarter,Melding,Sticky,`time`,`familie`)VALUES('".utf8_encode($tittel)."','{$obj->id}','".utf8_encode($melding)."','$sticky','".time()."','".$obj->family."')")){
echo '<div class="lykket_green">Tråden din ble opprettet!<br />Klikk <a href="familiepanel.php?side=konfam&tab=3&topic='.$db->insert_id().'">her</a> for å gå til den nye tråden du opprettet.</div>';
}
else{
echo '<div class="lykket_green">Den ble ikke opprettet! '.mysqli_error($db->connection_id).'</div>';
}
}
//Ny tråd...
echo <<<ENDHTML
<form method="post" action="familiepanel.php?side=konfam&tab=3&nytrad=opprett">
<p><a href="familiepanel.php?side=konfam&tab=3">Tilbake!</a></p>
<div class="box_k w500">

<h1 class="big">Opprett ny tråd</h1>

<dt><b>Sticky</b></dt>
<dd style="float:none;padding-left:20px;"><input style="text-align:left" name="sticky" type="checkbox" id="action" value="1" /> <sup>(Merk av for sticky)</sup></dd>
<dt>Trådtittel</dt><dd><input type="text" maxlength="20" class="textbox" style="width:50%;margin:0px 10px 0px 0px;" name="tittelen"></dd>
<dt>Melding</dt>
<dd><textarea name="teksten" class="text_box" style="width: 410px;max-width: 420px;max-height: 500px;" rows="15"></textarea></dd>
<dt><input type="submit" name="creat" class="button" value="Opprett tråd"></dt>

</div>
</form>
ENDHTML;
}//Ny tråd END
else if(isset($_GET['slettopic']) && isset($_GET['topic'])){
/*Sletter tråd*/
}
else if(isset($_GET['slettindex'])){
/*Forumindex slett tråd*/
$id = $db->escape($_POST['indexslett']);
$db->query("SELECT * FROM `famforum` WHERE `id` = '$id' AND `slettet` = '0' AND `familie` = '{$obj->family}' LIMIT 1")or die(mysqli_error($db->connection_id));
if($db->num_rows() == 1){
/*"sletter" tråd ved å skjule den i oppføringer og for visning*/
$db->query("UPDATE `famforum` SET `slettet` = '1' WHERE `id` = '$id' LIMIT 1")or die(mysqli_error($db->connection_id));
if($db->affected_rows() == 1){
/*Tråden eksisterte og ble "slettet".*/
echo '<p class="lykket">Tråden har blitt slettet, sender deg tilbake om 3 sek...</p><script>
function redirect()
{
document.location = "familiepanel.php?side=konfam&tab=3";
}
setTimeout( "redirect();", 3000 );
</script>';
}
else{
echo die(mysqli_error($db->connection_id));
}
}
else{
die("Det ble funnet ".$db->num_rows()." i følgende spørring: ".$db->last_query."");
/*Tråden eksisterer ikke eller har blitt "slettet" fra før av*/
echo '<p class="feil">Tråden eksisterer ikke.</p>
<p>Sender deg tilbake til forumindex.
<script>
function redirect()
{
document.location = "familiepanel.php?side=konfam&tab=3";
}
setTimeout( "redirect();", 3000 );
</script>
</p>';
}
}
else{
$chk = $db->query("SELECT * FROM `famforum` WHERE `familie` = '".$obj->family."' AND `slettet` = '0' ORDER BY `Sticky` DESC,`id` ASC");
if($db->num_rows($chk) == 0){
echo '<p class="feil">Det er ingen tråder postet!</p>';
}
else{
while($g = mysqli_fetch_assoc($chk)){
$tradid = $g['id'];
$sql2 = $db->query("SELECT * FROM `famsvar` WHERE `tradid` = '$tradid' ORDER BY `time` DESC LIMIT 0,1");
$r = $db->num_rows($sql2);
$bes = $r->usern;
if($bes == NULL){
$bes = '<span stype="text-decoraton:underline;">ingen</span>';
}else{
$bes = user($bes);
}
if($g['Sticky'] == 1){
$write = '(<span style="color:#f00">Sticky</span>)';
}
else{
$write = "";
}
$brukeren = user($g->Tradstarter);
if($newheader == 1)$exadd=null;
else {$newheader=1;$exadd='<tr>
<th>Tema</th><th>Sist svart av</th><th>Dato opprettet</th><th>Opprettet av</th><th>Velg</th>
</tr>';}
if($sub == 1){$submitter=null;}
else{
$sub =1;
$submitter='<input type="submit" value="Slett valgt tråd" class="button">';
}
echo '
<form method="post" action="familiepanel.php?side=konfam&tab=3&slettindex" onsubmit="return confirm(\'Er du sikker på at du vil slette denne tråden?\')">
'.$submitter.'
<table class="table">
'.$exadd.'
<tr class="c_1" style="width:130px">
<td style="width:130px"><a href="familiepanel.php?side=konfam&tab=3&topic='.$g['id'].'">'.tom($g['Tradnavn']).'</a>'.$write.'</td>
<td style="width:130px">'.$bes.'</td>
<td style="width:130px">'.date("H:i:s | d-m-Y",$g['time']).'</td>
<td style="width:130px">'.$brukeren.'</td>
<td style="width:50px"><input type="radio" name="indexslett" value="'.$g['id'].'"></td>
</tr>
</table>
';
}//Write END
}
}
}
if($tab == 4){
if($db->num_rows($checkub) == 1){
echo '<p class="feil">Du har ingen tilgang hit!</p>';
}
else{
//Legge ned familien
$ssqqll = $db->query("SELECT * FROM `familier` WHERE `Leder` = '$obj->id' AND `lagtned` = '0'")or die('Feil: '.mysqli_error($db->connection_id));
$getfetch = $db->fetch_object($ssqqll);
if(isset($_POST['submit'])){
$pass = md5($_POST['userpass']);
if($pass == $obj->pass){
echo '<p>Legger ned familie!</p>';
if($db->query("UPDATE `familier` SET `lagtned` = '1' WHERE `id` = '$getfetch->id'")){
if($db->query("UPDATE `users` SET `family` = NULL WHERE `family` = '$getfetch->id'")){
if($db->query("UPDATE `users` SET `hand` = (hand + $getfetch->Bank) WHERE `id` = '$obj->id'")){
echo '<p class="lykket">Familien din har nå blitt lagt ned!<br />Alle som var med i familien har blitt kastet ut.<br />Alle pengene som var i familiebanken har blitt lagt ut på handen din, du burde sette dem inn i banken.</p>';
}
else{
echo '<div class="mislykket_red">Kunne ikke gi deg alle pengene i familiebanken!<br />Grunn: '.mysqli_error($db->connection_id).'</div>';
}
}
else{
echo '<div class="mislykket_red">Kunne ikke kaste ut alle medlemmer!<br />Grunn: '.mysqli_error($db->connection_id).'</div>';
}
}
else{
echo '<div class="mislykket_red">Kunne ikke legge ned familie!<br />Grunn: '.mysqli_error($db->connection_id).'</div>';
}
}
else{
//Feil passord
echo '<p class="n_feil">Du oppgav feil passord! Gjengen ble ikke lagt ned!</p>';
}
}
echo <<<ENDHTML
<form method="post" action="familiepanel.php?side=konfam&tab=4">
<table class="table" style="width:100px;">
<tr class="c_1">
<th colspan="2">Legge ned familie?<br />(Dette kan ikke angres!)</th>
</tr>
<tr class="c_2">
<td>Ditt Passord:</td><td><input class="button" style="outline:none;text-align:left;" type="password" name="userpass"></td>
</tr>
<tr class="c_2">
<td colspan="2"><input class="button" type="submit" name="submit" value="Legg ned familien!"></td>
</tr>
</table>
</form>
ENDHTML;
}
}
if($tab == 5){
$logg = $db->query("SELECT * FROM `familielogg` WHERE `familie` = '$obj->family' ORDER BY `id` DESC");
//Familielogg
?>
<table class="table">
<tr>
<th colspan="5">Logg</th>
</tr>
<tr>
<td>Spiller</td>
<td>Hendelse</td>  
<td>Tid</td>
</tr>
<?php
while($r = mysqli_fetch_object($logg)){
echo '
<tr>
<td>'.user($r->spiller).'</td><td>'.$r->hendelse.'</td><td>'.date("H:i:s d.m.y",$r->time).'</td>
</tr>
';	
}
?>
</table>
<?php
}
/*else if($tab == 6){
//Familiestatus
$sql = $db->query("SELECT * FROM `familier` WHERE `Leder` = '$user' AND `Navn` = '$obj->Familie'")or die();
$sel = $db->fetch_object($sql);
if(isset($_POST['status'])){
$sta = $db->escape($_POST['status']);
if($db->query("UPDATE `familier` SET `apen` = '$sta'")){
if($sta == 1){
echo '<p style="color:#0f0;text-align:center;">Familien er nå åpen for nye søknader.</p>';
}
else if($sta == 0){
echo '<p style="color:#f00;text-align:center;">Familien er nå lukket for nye søknader.</p>';
}
}
//Dette gjentas fordi den automatisk skal vise oppdateringen
$sql = $db->query("SELECT * FROM `familier` WHERE `Leder` = '$user' AND `Navn` = '$obj->Familie'")or die();
$sel = $db->fetch_object($sql);
}
echo '
<form method="post" action="familiepanel.php?side=konfam&tab=6">
<table class="table">
<tr class="c_1">
<th colspan="3" style="text-align:center;font-weight:bold;">Familiestatus:</th>
</tr>
<tr class="c_3">
<td style="width:20%;">&larr;Lukket</td><td style="width:60%;"><div class="center"><input class="ran" type="range" name="status" min="0" max="1" value="'.$sel->apen.'"></div></td><td style="width:20%;">Åpen &rarr;</td>
</tr>
<tr>
<th colspan="3" align="center"><input class="button" type="submit" value="Endre!"></th>
</tr>
</table>
</form>
';
}*/
else if($tab == 7){
/*Endre profil*/
echo <<<END


<form method="post" action="familiepanel.php?side=konfam&tab=7">
<table class="table">
<thead>
<tr>
<th colspan="4">Familieinstillinger</th>
</tr>
</thead>
<tbody>
<tr><td><a href="familiepanel.php?side=konfam&tab=7&famtab=1">Endre profil</a></td><td><a href="familiepanel.php?side=konfam&tab=7&famtab=2">Sett UB</a></td><td><a href="familiepanel.php?side=konfam&tab=7&famtab=3">Åpne eller lukke familiesøknader</a></td><td><a href="familiepanel.php?side=konfam&tab=7&famtab=4">Endre medlemmer</a></td></tr>
</tbody>
</table>
</form>
END;
if(isset($_GET['famtab'])){
$sidetab = $_GET['famtab'];
if($sidetab == 1){
/*Endre profil*/
if(isset($_POST['famprofil']) && isset($_POST['famavatar'])){
$profil=$db->escape($_POST['famprofil']);
$avatar=  htmlentities($db->escape($_POST['famavatar']));
$db->query("UPDATE `familier` SET `profil` = '".utf8_encode($profil)."',`img` = '$avatar' WHERE `id` = '$obj->family' LIMIT 1") or die(mysqli_error($db->connection_id));
if ($db->affected_rows() == 1){
echo '<p class="lykket">Familieprofilen/avataret ble oppdatert!</p>';
}
else{
echo '<p class="feil">Du må gjøre endringer før vi kan endre profilen! ;)</p>';
}
}
$famprofil = $db->fetch_object($check);
$profil = htmlentities(utf8_decode($famprofil->profil));
$avatar= htmlentities($famprofil->img);
echo <<<END
<form method="post" action="familiepanel.php?side=konfam&tab=7&famtab=1">
<table class="table">
<thead>
<tr>
<th colspan="2">Endre familieprofilen</th>
</tr>
</thead>
<tbody>
<tr>
<td>Avatar:</td><td><input type="text" value="$avatar" name="famavatar"></td>
</tr>
<tr><td colspan="2"><textarea style="min-width:400px; max-width:95%;min-height:200px;max-height:500px;" name="famprofil">$profil</textarea></br><input type="submit" style="margin-left:10px;"></td></tr>
</tbody>

</table>
</form>
END;
}
else if($sidetab == 2){
if($db->num_rows($checkub) == 1){
echo '<p class="feil">Du har ingen tilgang!</p>';
}
else{
/*Endre UB*/
$getmembers = $db->query("SELECT * FROM `users` WHERE `family` = '$obj->family' AND `id` <> '$obj->id'");
if($db->num_rows() >= 1){
$select = '<select name="ub"><option style="color:red;" value="0">Ingen UB</option>';
while($r = mysqli_fetch_object($getmembers)){
$select.='<option value="'.$r->id.'">'.$r->user.'</option>';
}
$select.='</select>';
}
else{
$select = 'Ingen medlemmer';
}
if(isset($_POST['ub'])){
/*Oppdaterer ub, så lengst personen er i familien fra før av*/
$ub = $db->escape($_POST['ub']);
if($ub == 0){
$db->query("UPDATE `familier` SET `ub` = NULL WHERE `id` = '$obj->family'");
echo '<p class="lykket">Du fjernet din ub!</p> <p class="feil">OBS: FAMILIEN DIN HAR IKKE EN UNDERBOSS!</p>';
}
else{
$ex = (get_user($ub)) ? get_user($ub) : NULL;
if($ex != NULL){
if($ex->family == $obj->family){
$db->query("UPDATE `familier` SET `Ub` =  '$ex->id' WHERE `id` = '$obj->family'");
$array = array(
1=>"ble satt opp som underboss i familien"
);
famlogg($ex->id, $array[1]);
echo '<p class="lykket">'.$ex->user.' er nå underboss i din familie! </p>';
}
else{
echo '<p class="feil">Brukeren er ikke med i din familie!</p>';
}
}
else{
/*Eksisterer ikke*/
echo '<p class="feil">Brukeren eksisterer ikke!</p>';
}
}
}
echo <<<END
<form method="post" action="familiepanel.php?side=konfam&tab=7&famtab=2">
<table class="table">
<thead>
<tr>
<th colspan="3">Sett underboss i familien</th>
</tr>
</thead>
<tbody>
<tr><td>Brukere i familie:</td><td>$select</td></tr>
<tr><td style="text-align:center;" colspan="2"><input type="submit" value="Sett ny ub!"</td></tr>
</tbody>

</table>
</form>
END;
}
}
else if($sidetab == 3){
/*Åpne eller lukke familiesøknader*/
$db->query("SELECT * FROM `familier` WHERE `id` = '$obj->family'");
$in = $db->fetch_object();
if(isset($_POST['veksle'])){
$apen = ($in->apen == 1) ? 0 : 1;
$status = ($apen == 1) ? "åpnet" : "lukket";
$db->query("UPDATE `familier` SET `apen` = '$apen' WHERE `id` = '$obj->family' ");
echo '<p class="lykket">Familien har nå blitt '.$status.'.</p>';
}
$status = ($in->apen == 1) ? '<span style="color:#0f0">Familien er åpen for søknader!</span>' : '<span style="color:#f00">Familien er lukket for søknader</span>';
echo <<<END
<form method="post" action="familiepanel.php?side=konfam&tab=7&famtab=3">
<table class="table">
<thead>
<tr>
<th colspan="3">Velg status for søknader</th>
</tr>
</thead>
<tbody>
<tr><td>Status:</td><td>$status</td></tr>
<tr><td style="text-align:center;" colspan="2"><input type="submit" name="veksle" value="Endre Åpne/Lukket!"</td></tr>
</tbody>

</table>
</form>
END;
}
else if($sidetab == 4){
/*Endre Medlemmer*/
$getmembers = $db->query("SELECT * FROM `users` WHERE `family` = '$obj->family' AND `id` <> '$obj->id'");
if($db->num_rows() >= 1){
$select = '<select name="fjern"><option style="color:red;" value="0">Velg medlem</option>';
while($r = mysqli_fetch_object($getmembers)){
$select.='<option value="'.$r->id.'">'.$r->user.'</option>';
}
$select.='</select>';
}
else{
$select = 'Ingen medlemmer';
}
if(isset($_POST['fjern'])){
/*Oppdaterer medlemmer, så lengst personen er i familien fra før av*/
$m = $db->query("SELECT * FROM `familier` WHERE `id` = '$obj->family'");
$r = $db->fetch_object($m);
if($_POST['fjern'] == $r->Leder){
echo '<p class="feil">Du kan ikke kaste ut din egen leder. Lederen er informert om at du prøvde.';
$db->query("INSERT INTO `sysmail`(`uid`,`time`,`msg`) VALUES ('".$r->Leder."','".time()."','".$db->slash('--<b>Familie</b><br/>'.$obj->user.' prøvde å kaste deg ut av din familie!')."')");
}
else{
$fjern = $db->escape($_POST['fjern']);
$ex = (get_user($fjern)) ? get_user($fjern) : NULL;
if($ex != NULL){
if($ex->family == $obj->family){
$db->query("UPDATE `users` SET `family` = NULL WHERE `id` = '$ex->id' LIMIT 1")or die(mysqli_error($db->connection_id));
echo '<p class="lykket">'.$ex->user.' er nå kastet ut av familien!</p>';
$db->query("SELECT * FROM `familier` WHERE `id` = '$obj->id'");
$i = $db->fetch_object();
if($i->Ub == $ex->id){
$db->query("UPDATE `familier` SET `Ub` = NULL WHERE `id` = '$obj->family'");
}
}
else{
echo '<p class="feil">Brukeren er ikke med i din familie!</p>';
}
}
else{
/*Eksisterer ikke*/
echo '<p class="feil">Brukeren eksisterer ikke!</p>';
}
}
}
echo <<<END
<form method="post" action="familiepanel.php?side=konfam&tab=7&famtab=4">
<table class="table">
<thead>
<tr>
<th colspan="2">Kast ut medlemmer</th>
</tr>
</thead>
<tbody>
<tr><td>Brukere i familie:</td><td>$select</td></tr>
<tr><td style="text-align:center;" colspan="2"><input type="submit" value="Kast ut!"</td></tr>
</tbody>

</table>
</form>
END;
}
}
}
else if($tab == 8){
//Viser en type bank der en kan kun donere
if(isset($_POST['money'])){
/*Donering for medlemmer*/
$belop = $db->escape($_POST['money']);
if($belop <= 0){
echo '<p class="feil">Summen du ønsker å donere må være over 0 kr.</p>';
}
else{
if($belop > $obj->hand){
echo '<div class="mislykket">Du har ikke så mye ute på handa!</div>';
}
else if($obj->hand >= $belop){
if($db->query("UPDATE `users` SET `hand` = (hand - $belop) WHERE `id` = '$obj->id'")){
if($db->query("UPDATE `familier` SET `Bank` = (Bank + $belop) WHERE `id` = '$obj->family'")){
if($db->query("INSERT INTO `familielogg`(`familie`,`hendelse`,`time`,`spiller`) VALUES('$obj->family','Donerte ".number_format($belop)."kr!',UNIX_TIMESTAMP(),'{$obj->id}')")){
if($db->query("INSERT INTO `familiedoner`(`uid`,`fid`,`sum`,`time`) VALUES('{$obj->id}','{$obj->family}','$belop',UNIX_TIMESTAMP())")){
lykket('Du har satt inn '.number_format($belop).'kr i familiebanken! Siste mysqli feil: </p>'.$db->last_error);
}
else{
feil('Kunne ikke oppdatere familiedoneringslogg!');
}
}
else{
feil('Kunne ikke oppdatere logg.');
}
}
else{
feil('Kunne ikke sette inn penger! De kan ha blitt tatt fra handa!');
}
}
else{
feil('Kunne ikke ta penger fra handa!');
}
}
}
}
$for = $db->query("SELECT * FROM `familier` WHERE `id` = '$obj->family'");
$fetch = $db->fetch_object($for);
$current = number_format($fetch->Bank);
echo <<<ENDHTML
<form method="post" action="familiepanel.php?side=konfam&tab=2">
<table class="table" style="width:400px;">
<tr class="c_1">
<th colspan="2">Donér</th>
</tr>
<tr class="c_1">
<th style="text-align:right;">Penger i banken:</th><td>$current</td>
</tr>
<tr>
<td style="text-align:right;">Beløp å donere</td><td><input type="number" name="money" maxlenght="9999999999999"></td>
</tr>
<tr>
<td align="center" colspan="2">
<input type="submit" value="Doner!" style="padding:10px;" name="taut" class="button"><br />
</td>
</tr>
</table>
</form>
ENDHTML;
}//Tab 8 END
}
//Forumet
}
else{
//For de som ikke er leder kommer her
echo '
<table class="table">
<tr class="c_1">
<th>Dine valg i familien '.famidtoname($obj->family).'.</th>
</tr>
<tr>
<td>
<table class="table">
<tr class="c_2"><td><a href="nyforum.php?type=5">Se forum</a></td><td><a href="familiepanel.php?side=konfam&tab=2">Doner til familien</a></td><td><a href="familiepanel.php?side=konfam&tab=3">Forlat familien</a></td></tr>
</table>
</td>
</tr>
</table>
';
if(isset($_GET['tab'])){
$tab = $db->escape($_GET['tab']);
if($tab == 1){
//Forumet for de vanlige brukerne
header("Location: nyforum.php?type=5");
/*
echo '<p class="feil">Hei du, jeg holder på å fikse forumet, kan du vente litt a? &lt;3</p>';
if(r1()){
if(isset($_GET['trad'])){
$id = $_GET['trad'];
if(is_numeric($id)){
$db->query("SELECT * FROM `famforum` WHERE `familie` = '{$obj->family}' AND `slettet` = '0'");
if($db->num_rows() == 1){
$res = $db->fetch_object();
}
}
echo 'Oh my gawd, vil du se en tråd altså?! :S';
$text = bbcodes($res->Melding);
$eie = get_user($res->Tradstarter);
$bilde1=$eie->image;
$usern=$eie->user;
$tittel = htmlentities($res->Tradnavn,NULL,"ISO-8859-1");
echo <<<ENDHTML
<table class="table" style="width:99%;">
<tr>
<td style="text-align:center;width:150px;"><img style="width:150px;height:150px;" src="$bilde1" alt=""><br>$usern</td><td>$tittel</td><td>$text</td>
</tr>
</table>
ENDHTML;
}
else if(isset($_GET['ny'])){

}
else{
//Viser frem trådene i forumet
$s = $db->query("SELECT * FROM `famforum` WHERE `familie` = '{$obj->family}' ORDER BY `Sticky` DESC,`time` DESC");
if($db->num_rows() >= 1){
echo '<table class="table" style="width:99%;">';
while($r = mysqli_fetch_object($s)){
echo '<tr><td><a href="familiepanel.php?side=konfam&tab=1&trad='.$r->id.'">'.htmlentities($r->Tradnavn,NULL,"ISO-8859-1").'</a></td><td>'.user($r->Tradstarter).'</td><td>'.date("H:i:s d.m.Y",$r->time).'</td></tr>';
}
echo '</table>';
}
else{
echo '<p class="feil">Det er ikke opprettet noen tråder i forumet enda!</p>';
}
}
/*ikke satt viser hele forum*/
/*1:lestråd*/
/*2:nytråd*/
/*3:*/
//}
}
else if($tab == 2){
//Viser en type bank der en kan kun donere
if(isset($_POST['money'])){
//Donerer
$belop = $db->escape($_POST['money']);
if($belop < 0){
feil('Beklager, du kan ikke ta noen pengesedler ifra din familie!');
}
else if($obj->hand >= $belop){
if($db->query("UPDATE `users` SET `hand` = (hand - $belop) WHERE `id` = '$obj->id'")){
if($db->query("UPDATE `familier` SET `Bank` = (Bank + $belop) WHERE `id` = '$obj->family'")){
if($db->query("INSERT INTO `familielogg`(`familie`,`hendelse`,`time`,`spiller`) VALUES('$obj->family','Donerte ".number_format($belop)."kr!',UNIX_TIMESTAMP(),'{$obj->id}')")){
if($db->query("INSERT INTO `familiedoner`(`uid`,`fid`,`sum`,`time`) VALUES('{$obj->id}','{$obj->family}','$belop',UNIX_TIMESTAMP())")){
lykket('Du har satt inn '.number_format($belop).'kr i familiebanken!');
}
else{
feil('Kunne ikke oppdatere familiedoneringslogg!');
}
}
else{
feil('Kunne ikke oppdatere logg.');
}
}
else{
feil('Kunne ikke sette inn penger! De kan ha blitt tatt fra handa!');
}
}
else{
feil('Kunne ikke ta penger fra handa!');
}
}
}
$for = $db->query("SELECT * FROM `familier` WHERE `id` = '$obj->family'");
$fetch = $db->fetch_object($for);
$current = number_format($fetch->Bank);
echo <<<ENDHTML
<form method="post" action="familiepanel.php?side=konfam&tab=2">
<table class="table" style="width:400px;">
<tr class="c_1">
<th colspan="2">Donér</th>
</tr>
<tr class="c_1">
<th style="text-align:right;">Penger i banken:</th><td>$current</td>
</tr>
<tr>
<td style="text-align:right;">Beløp å donere</td><td><input type="number" name="money" maxlenght="9999999999999"></td>
</tr>
<tr>
<td align="center" colspan="2">
<input type="submit" value="Doner!" style="padding:10px;" name="taut" class="button"><br />
</td>
</tr>
</table>
</form>
ENDHTML;

}//Tab 2 END
else if($tab == 3){
//Forlate familien
if(isset($_POST['submit'])){
if($db->query("UPDATE `users` SET `family` = NULL WHERE `id` = '$obj->id'")){
echo '<div class="lykket" style="border:2px solid green;bakground-color:#0f0;margin:0px auto;width:300px;text-align:center;">Du har forlatt familien! En melding er blitt sendt til Lederen.</div>';
}
else{
echo '<div class="feilet">Du kunne ikke forlate: '.mysqli_error($db->connection_id).'</div>';
}
}
echo <<<ENDHTML
<form method="post" action="familiepanel.php?side=konfam&tab=3">
<table class="table" style="width:200px;">
<tr>
<th>Forlate familien?</th>
</tr>
<tr>
<td align="center"><input type="submit" value="Forlat familien!" name="submit" class="button"></td>
</tr>
</table>
</form>
ENDHTML;
}
}
}
}
else{
echo '<font color="#ba0000;">Du kan ikke se på kontrollpanelet når du ikke er i en familie.</font>';
}
}
}
else{
  if($obj->family == NULL){
    echo '
    <p><a href="familiepanel.php?side=oppfam">Opprette familie</a></p>
    <p><a href="familiepanel.php?side=sokfam">Søk i familier</a></p>
    </tr>
    </table>
    ';
  }
  else{
    echo'<p><a href="familiepanel.php?side=konfam">Kontrollpanel for familien</a></p>';
  }
}
endpage();
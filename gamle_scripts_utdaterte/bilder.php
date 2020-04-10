<?php
die();
include("core.php");
startpage("Bildebestillinger");
?>
<h1>S&oslash;k etter bilder/avatarer/profilbilder ++</h1>
<?php
if (isset($_POST['stock']) && isset($_POST['type']) && isset($_POST['pris1']) && isset($_POST['pris2']) && isset($_POST['hvem']) && isset($_POST['extra'])) {
    $link = mysql_real_escape_string($_POST['stock']);
    $type = mysql_real_escape_string($_POST['type']);
    $pri1 = mysql_real_escape_string($_POST['pris1']);
    $pri2 = mysql_real_escape_string($_POST['pris2']);
    $hvem = mysql_real_escape_string($_POST['hvem']);
    $besk = mysql_real_escape_string($_POST['additional']);
    $excom = mysql_real_escape_string($_POST['extra']);
    $tid = time();
//Herfra blir inputs validert
    $array = array(1, 2, 3, 4);//Verdier som godkjennes.
    if (!filter_var($link, FILTER_VALIDATE_URL) || $type != in_array($array)) {
        echo feil('Det var noe feil, sjekk at du har fyllt alt inn riktig.');
    } else {
        if (mysql_query("INSERT INTO `bildebestilling`(`pm`,`reqid`,`type`,`tb`,`lp`,`hp`,`com`,`stock`,`time`) VALUES('$hvem','{$obj->id}','$type','$beskrivelse','$pri1','$pri2','$excom','$link','$tid')")) {
            echo lykket('Din bestilling er lagret!');
        } else {
            echo feil('Det oppstod en feil ved lagring av din bestilling!');
            if ($obj->status == 1) {
                echo mysql_error();
            }
        }
    }
}
?>
<p class="feil">Denne funksjonen er under arbeid! Ikke klar enda!</p>
<form method="post" action="">
<table class="table" style="width:400px;">
<tr>
<th colspan="2">Fyll ut dette skjemaet til hver detalj!</th>
</tr>
<tr>
<td>Stock:</td><td><input type="text" name="stock"></td>
</tr>
<tr>
<td>Type:</td><td>
<select id="valg" name="type" onchange="displayrow(this.value)">
<option value="1">Avatar</option>
<option value="2">Sigg</option>
<option value="3">Profilbilde</option>
<option value="4">Annet?</option>
</select>
</td>
</tr>
<tr id="trtog" style="display:none;">
<td colspan="2">
<textarea name="additional" style="width:100%;height:50px;">Beskriv typen her.</textarea>
</td>
</tr>
<tr>
<td>Villig pris:</td><td>Gir minst:<br><input type="number" name="pris1" id="pris1" min="0" max="300000000" value="0" onchange="changemin2()"><br>Maks:<br><input type="number" name="pris2" min="0" max="300000000" id="pris2" value="0"></td>
</tr>
<tr>
<td><span title="Er det en spesiell picmaker som skal jobbe med bildet ditt?"><em>Hvem:</em></span></td><td>
<select name="hvem">
<option value="0" selected="">Hvem som helst</option>
<?php
$s = mysql_query("SELECT * FROM `users` WHERE `status` = '6' ORDER BY `id` DESC");
while ($r = mysql_fetch_object($s)) {
    echo '
<option value="'.$r->id.'">'.status($r->user).'</option>
';
}
?>
</select>
</td>
</tr>
<tr>
<td colspan="2">Ekstra kommentar til picmaker:<br>
<textarea name="ekstra" style="width:100%;" placeholder="Noe spessielt du vil ha gjort med bildet, s&aring; kan du forklare det her."></textarea>
</td>
</tr>
<tr>
<th colspan="2"><input type="submit" name="submitter" value="Send bildebestillingen!"></th>
</tr>
</table>
</form>
<script>
function displayrow(valu){
if(valu == 4){
/*$("#trtog").css("display","table-row");
$("#trtog").animate({
display:'table-row'
});*/
$("#trtog").fadeIn(800);
}
else{
/*$("#trtog").css("display","none");
$("#trtog").animate({
display:'none'
});*/
$("#trtog").fadeOut(750);
}
}
function changemin2(){
var p = $("#pris1").val();
var pp= $("#pris2").val();
if(p > pp){
$("#pris2").val(p);
}
}
</script>
<?php
if ($_POST['type'] == 1) {
    'Avatar';
}
if ($_POST['submitter']) {
    echo ' <h2>Din bestilling ser slik ut!</h2></br>
Stock: '.$_POST['stock'].'</br>
Type: '.$_POST['type'].'</br>
Min-Pris:'.$_POST['pris1'].'</br>
Max-Pris:'.$_POST['pris2'].'</br>
Sendt til: '.$_POST['hvem'].'</br>
Ekstra-Informasjon: '.$_POST['extra'].'';
}
endpage();
?>
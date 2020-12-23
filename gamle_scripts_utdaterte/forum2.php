<?php
include("core.php");
function enter($text){
$text = str_replace("\n","<br>",$text);
return($text);
startpage("Forum");
}
?>

<?php
//Et forum er delt opp i flere deler.
//1. Selve forumtrådene i liste/tabell
//2. Selve tråden i forumet/visning av tråden
//3. Svar på tråden
//4. Slette tråd/ kunn for admin og fm's
//5. Opprette tråder
//6. Flere?
if(isset($_GET['topic'])){
//Viser tråden
$topic = mysql_real_escape_string($_GET['topic']);
echo '<br>';
if(isset($_POST['post'])){
//Svarer på tråd
$svar = mysql_real_escape_string($_POST['svar']);
if(strlen($svar) <= 2){
echo '<p style="color:#f00;text-align:center;">Svaret ditt er for kort, vennligst ha 3 tegn eller mer!</p>';
}
else{
$user=$_SESSION['nick'];
$date=date("H:i:s d.m.Y");
$time = time();
if(mysql_query("INSERT INTO `forum1svar`(`topicid`,`usern`,`answer`,`date`,`timestamp`)VALUES('$topic','$user','$svar','$date','$time')")){
echo <<<ENDHTML
<p>Ditt svar er skrevet, sender deg tilbake!</p>
<script>
 function getgoing()
  {
    top.location="http://world-mafia.clanteam.com/forum.php?topic=$topic";
  }
  setTimeout('getgoing()',3000);
</script>
ENDHTML;
}
}
}

$sql = mysql_query("SELECT * FROM `forum` WHERE `id` = '$topic'")or die('Feil: '.mysql_error());
$get = mysql_fetch_object($sql);
$geti = mysql_query("SELECT * FROM `users` WHERE `user` = '$get->creator'");
$usr = mysql_fetch_object($geti);
if(mysql_num_rows($sql) == 0){
echo '<p style="color:#f00;text-align:center;">Denne tråden eksisterer ikke lengre. Eller har ikke blitt opprettet av noen.</p>';
}
else{
$what = $get->what;
$what = htmlentities($what);
$what = enter($what);
echo '
<br>
<p style="text-align:center;padding-center:30px;"><a style="color:#A07650;" href="forum.php">Tilbake til forumet!</a></p>';
if(!isset($_GET['nysvar'])){
  $text = '<p style="text-align:right;padding-right:40px;"><a style="border:2px solid #A07650A07650;border-radius:15px;padding:5px;background-image:-webkit-gradient(linear,left bottom,left top,color-stop(0.33, #1e1e1e),color-stop(0.67, #202020));color:#fff" href="forum.php?topic='.$topic.'&nysvar">Svar på tråd!</a></p>';
}
else{
  $text = '
  <div style="margin:0px auto;width:500px;text-align:center;">
  <form method="post" action="forum2.php?topic='.$topic.'&do">
  <p>Nytt innlegg:</p>
  <p><textarea name="svar" style="width:500px;height:200px;"></textarea></p>
  <input type="submit" style="border:1px solid #A07650;border-radius:15px;padding:5px;background-image:-webkit-gradient(linear,left bottom,left top,color-stop(0.33, #1e1e1e),color-stop(0.67, #202020));color:#fff" value="Post innlegg!" name="post">
  </form>
  </div>
  ';
  }
echo '
<table border="1" style="margin:0px auto;border-collapse:collapse;border-color:grey;border:1px solid #A07650;">
<tr>
<th colspan="2" style="border-left-width:0px;text-align:left;">'.$get->topic.'</th>
</tr>
<tr>
<td><img style="width:140px;height:120px;" src="'.$usr->img.'"><br><a style="color:#A07650;" href="profilen2.php?user='.$usr->nick.'">'.$usr->nick.'</a></td><td style="width:400px;" valign="top">'.$what.'</td>
</tr>
</table>
';
$sql = mysql_query("SELECT * FROM `forum` WHERE `uid` = '$topic' ORDER BY `time` ASC");
if(mysql_num_rows($sql) == 0){
$place = '<p>Ingen svar!</p>';
}
else{
$place2 = 'Andre har skrevet!';
echo <<<ENDHTML
<table style="border-collapse:collapse;border:1px solid #A07650;margin:0px auto;margin-top:10px;padding:0px;" border="1" width="507">
<tr>
<th align="left" colspan="2">Svar:</th>
</tr>
ENDHTML;
while($r = mysql_fetch_object($sql)){
$get = mysql_fetch_object($useri);
$resimg = $get->img;
$svar = htmlentities($r->answer);
$svar = bilde($svar);
$svar = enter($svar);
$svar = b($svar);
$svar = u($svar);
$svar = i($svar);
$svar = midt($svar);
echo '
<tr>
<td style="width:100px;">
<img src="'.$resimg.'" style="width:100px;height:100px;"><a style="color:#A07650;" href="profil.php?user='.$obj->user.'">'.$obj->user.'</a>
</td>
<td valign="top" style="padding:0px;">
'.$svar.'<br>
<!--<table style="border-collapse:collapse;border-bottom:1px solid #A07650;margin:0px;width:100%;padding:0px;">
<td style="height:15px;text-align:right;">
'.$r->date.'
</td>
</table>-->
</td>
</tr>
';
}
echo '</table>';
}
}
echo $text;
}
else if(isset($_GET['nytrad'])){
//Opprette ny tråd
echo '<br><p style="text-align:left;padding-left:40px;"><a style="border:2px solid #A07650;border-radius:15px;padding:5px;background-image:-webkit-gradient(linear,left bottom,left top,color-stop(0.33, #1e1e1e),color-stop(0.67, #202020));color:#fff" href="forum2.php">Tilbake til forumsiden!</a></p>';
if(isset($_POST['melding']) && isset($_POST['tema'])){
$sms = mysql_real_escape_string($_POST['melding']);
$tema = mysql_real_escape_string($_POST['tema']);
if(strlen($sms) <= 14){
echo '<p style="color:#f00;text-align:center;">Meldingen er for kort! Det må minst være 15 tegn eller lengre!</p>';
}
else if(strlen($tema) <= 3){
echo '<p style="color:#f00;text-align:center;">Temaet for tråden er for kort! Det må minst være 4 tegn!</p>';
}
else if(strlen($sms) >= 15 && strlen($tema) >= 4){
  $user = $_SESSION['user'];
  $date = date("H:i:s d.m.Y");
if(mysql_query("INSERT INTO `forum`(`tema`,`creator`,`date`,`what`) VALUES('$tema','$user','$date','$sms')")){
echo '<p style="color:#0f0;text-align:center;font-weight:bold;">Tråden har blitt opprettet!</p>';
}
else{
echo '<p>Det oppstod en feil!<br>'.mysql_error().'</p>';
}
}
}
echo <<<ENDHTML
<form method="post" action="forum2.php?nytrad&post">
<tr>
<h3>lag en ny tråd!</h3>
</tr>
<tr>
<th style="width:50%;text-align:right;">Temaet: </th><td style="width:50%;text-align:left;"><input type="text" name="tema" style="width:50%;"></td>
</tr>
<tr>
<th colspan="2">
<textarea name="melding" style="width:550px;height:200px;"></textarea>
</th>
</tr>
<tr>
<th colspan="2"><input type="submit" value="Opprett tråden!"></th>
</tr>
</table>
</form>
ENDHTML;
}
else{
echo '
<br>
<center><img src="http://lastopp.no/3/0dfb49e119aa9507ed67f1733b0f9214.png"></center>
<br>
<br>
<br><br><table style="padding:10px;margin:0px auto;border-collapse:collapse;border-color:grey;border:1px solid grey;width:500px;" border="1">
<tr>
<!--<th style="padding:10px;">Tråd</th><th style="padding:10px;">Av</th><th style="padding:10px;">Dato</th><th style="padding:10px;">Antall svar</th>-->
<th>Alt mulig forumet <a href="forum.php?nytrad" style="color:#A07650;">Lag din egen tråd</a></th>
</tr>
';
$sql = mysql_query("SELECT * FROM `forum` ORDER BY `id` DESC")or die('Feil:'.mysql_error());
while($r = mysql_fetch_object($sql)){
$sql2 = mysql_query("SELECT * FROM `forumsvar` WHERE `sid` = '$r->id'")or die('Feil:'.mysql_error());
$res = mysql_num_rows($sql2);
$sjekk = mysql_fetch_object($sql2);
if($res == 0){
  $sist = "<b>ingen</b>";
  }
  else{
  $sist = '<a href="profil.php?nick='.$sjekk->user.'" style="color:#A07650;">'.$sjekk->user.'</a>';
  }
echo '
<tr>
<!--<td style="padding:10px;"><a href="forum2.php?topic='.$obj->id.'">'.$r->topic.'</a></td><td style="padding:10px;">'.$r->creator.'</td><td>'.$r->date.'</td><td style="padding:10px;">'.$res.'</td>-->
<td>
<p><a href="forum.php?topic='.$r->id.'" style="color:#A07650;">'.$r->topic.'</a></p>
<p>Opprettet av <a href="profilen2.php?nick='.$r->creator.'" style="color:#A07650;">'.$r->creator.'</a> den '.$r->date.' Sist besvart av: '.$sist.'</p>
</td>
</tr>
';
}
echo '</table><br><p style="text-align:right;padding-right:40px;"><a style="border:2px solid #A07650A07650;border-radius:15px;padding:5px;background-image:-webkit-gradient(linear,left bottom,left top,color-stop(0.33, #1e1e1e),color-stop(0.67, #202020));color:#fff" href="forum.php?nytrad">Opprett ny tråd!</a></p>';
}
echo '<br>';
?>
<hr>
</div>

</div>
<?php
endpage();
?>
<?php
include("core.php");
startpage("Forum");
$s = $db->query("SELECT * FROM `forumban` WHERE `uid` = '$obj->id' AND `timeleft` > '$time' AND `act` = '1' ORDER BY `timeleft` DESC LIMIT 1");
if($db->num_rows() == 1){
    echo '';
    $f = $db->fetch_object($s);
    $timeleft = $f->timeleft - time();
    if($f->timeleft - time() && $f->act = '1'){
      $db->query("UPDATE `forumban` SET `act` = '0' WHERE `uid` = '$obj->id'");
    }
	echo '
	<div style="border-width: 2px; border-style: dotted; border-color: red; "><p class="feil"> Du er utestengt fra forumet!</p>
	</br><b>Du har blitt utestengt av forumet av '.user($f->banid).' og du kan derfor heller ikke se noe.</br>
    Du har </h3><span id="user'.$f->uid.'"></span><script>teller('.$timeleft.',"user'.$f->uid.'","true","ned");</script> igjen av straffen din.</br>
	</br>
	Grunnen for at du ble utestengt er: '.$f->res.'</b></div></br>
	';
endpage();
die();
}
else{
function br($t){
$r = str_replace("\n","<br>",$t);
return($r);
}
/*if($obj->status <= 3){
    $addbutton='<a class="button" href="#">T&oslash;m forum!</a>';
}
else{
    $addbutton=null;
}*/
?>
<h1>Generelt forum</h1>
<div id="forummenu">
<p style="text-align: center;"><a class="button" href="forum1.php">Forsiden </a><a class="button" href="?ny"> Nytt tema </a></p>
</div>
<?php
// lock topic script.
if(isset($_GET['vis'])){
    if(!is_numeric($_GET['vis'])){
        echo '<p class="feil">Tr�dverdi er feil!</p>';
    }
    else{
        $vis = $db->escape($_GET['vis']);
        $ge = $db->query("SELECT * FROM `forum` WHERE `id` = '$vis' AND `slettet` = '0'");
        if($db->num_rows($ge) == 1){//Viser tr�dens emne
            if(isset($_POST['svar'])){
                $svar = $db->escape($_POST['svar']);
                $dato = date("H:i:s d.m.y");
                $time = time();
                $ins = $db->query("INSERT INTO `forumsvar` VALUES(NULL,'$obj->id','$vis','$svar','$dato','$time')");
                if(!$ins && $obj->status == 1){
                    echo '<p>Det har oppst�tt en feil ved lagring i databasen! Feilen er:<br>'.mysqli_error().'</p>';
                }
                else if(!$ins && $obj->status >= 2){
                    echo '<p class="feil">Det har oppst�tt en feil! Rapporter via support snarest!</p>';
                }
                else{
                    echo '<p class="lykket">Du har lagt inn et svar til innlegget!</p>';
                    $db->query("UPDATE `forum` SET `suid` = '".$obj->id."' WHERE `id` = '".$vis."'");
                }
            }
            echo '<a class="button" href="'.$_SERVER['PHP_SELF'].'?svar='.htmlentities($_GET['vis']).'">Besvar emnet!</a>';
            if($obj->status <= '3'){echo'<a class="button" onclick="return confirm(\'Er du sikker p&aring; at du vil slette tr&aring;den?\')" href="'.$_SERVER['PHP_SELF'].'?slett='.$_GET['vis'].'">Slett tr&aring;d!</a>';}
            $ges = $db->query("SELECT * FROM `forumsvar` WHERE `stid` = '$vis' ORDER BY `sid` DESC");
            while($r = mysqli_fetch_object($ge)){
                $ss = $db->query("SELECT * FROM `users` WHERE `id` = '$r->uid'");
                $u = mysqli_fetch_object($ss);
                $user = $u->user;
                echo '
                <table class="table">
                <tr>
                <th colspan="2">'.htmlentities($r->tema).' </br>Skrevet: '.date("H:i:s | d.m.Y",$r->time).'</th>
                </tr>
                <tr class="table tema">
                <td class="forumimg" style="text-align:center;"><img src="'.bilde(htmlentities($r->uid)).'" alt="" style="width:250px;height:200px;"><br>'.status($user).'</td><td class="tablecontent">   '  .bbcodes($r->melding,1,1,1,1,1,1,1,1,1,1,1,1,1,1).' </td>
                </tr>
                </table>
                ';
            }
            if($db->num_rows($ges) >= 1){
                while($r = mysqli_fetch_object($ges)){
                    $ss = $db->query("SELECT * FROM `users` WHERE `id` = '$r->suid'");
                    $u = mysqli_fetch_object($ss);
                    $user = $u->user;
                    echo '<table class="table">
                    <tr class="forumsvar">
                    <td class="forumimg" style="text-align:center;"><img src="'.bilde($r->suid).'" alt="" style="width:120px;height:100px;"><br>'.status($user).'</td><td class="tablecontent"></br>'.bbcodes($r->smelding,1,1,1,1,1,1,1,1,1,1,1,1,1,1).'</td>
                    </tr>
                    </table>';
                }
            }
            else{
                echo '<p class="feil">Ingen svar!</p>';
            }
        }
        else{//Enten slettet, eller eksisterer ikke.
            echo '<p class="feil">Denne tr&aring;den eksisterer ikke eller har blitt slettet!</p>';
        }
    }
}
else if(isset($_GET['svar'])){
    $id = $db->escape($_GET['svar']);
    if(!is_numeric($id) || $id <= 0){
        echo '<p class="feil">Emnet kan ikke besvares. Grunn er ugyldig id.</p>';
    }
    else{
        $g = $db->query("SELECT * FROM `forum` WHERE `id` = '$id'");
        if($db->num_rows($g) == 1){
        $m = mysqli_fetch_object($g);
        $img = bilde($m->uid);
        $mld = br($m->melding);
        $path = $_SERVER['PHP_SELF']."?vis=".$m->id;
        echo <<< ENDHTML
        <form method="post" action="$path">
        <table class="forumindex3">
        <tr>
        <th colspan="2">Besvar emnet $emne</th>
        </tr>
        <tr>
        <td class="tableimg"><img src="$img" alt="" style="width:100px;height:100px;"></td><td class="tablecontent">$mld</td>
        </tr>
        <tr>
        <td colspan="2"><textarea name="svar"></textarea></td>
        </tr>
        <tr>
        <td colspan="2" style="text-align:center;"><input type="submit" value="Besvar!"></td>
        </tr>
        </table>
        </form>
ENDHTML;
        }
        else{
        echo '<p class="feil">Tr&aring;den eksisterer ikke, eller har blitt slettet!</p>';
        }
    }
}
else if(isset($_GET['slett'])){
    $id = $db->escape($_GET['slett']);
        $g = $db->query("SELECT * FROM `forum` WHERE `id` = '$id'");
        if($db->num_rows($g) == 1){
        $m = mysqli_fetch_object($g);
        $db->query("UPDATE `forum` SET `slettet` = '1' WHERE `id` = '$id' LIMIT 1");
        echo '<p class="lykket">Tr&aring;den ble slettet!</p>';
        }
        else{
        echo '<p class="feil">Tr&aring;den eksisterer ikke, eller har blitt slettet!</p>';
        }
}
else{
if(isset($_GET['ny'])){
if(isset($_POST['tema'])){
$txt = $db->escape($_POST['tema']);
$type = $db->escape($_POST['type']);
$mel = $db->escape($_POST['meld']);
$array = array(0,1,2,3);
if(strlen($txt) <= 2 || !in_array($type,$array,false) || strlen($mel) <= 10){
echo '
<p class="feil">Det ble funnet feil i det du skrev!</p>
';
if(strlen($txt) <= 2){
echo '<p class="feil">Tema for kort! Minimum 3 tegn!</p>';
}
if(!in_array($type,$array,false)){
echo '<p class="feil">Det var oppgitt feil verdi ved forumvalg!</p>';
}
if(strlen($mel) <= 10){
echo '<p class="feil">Meldingen er for kort. Minimumsgrense er 10 tegn.</p>';
}
}
else{
$dato = date("H:i:s d.m.y");
$tid = time();
if($db->query("INSERT INTO `forum`(`tema`,`uid`,`melding`,`dato`,`time`,`type`) VALUES('$txt','$obj->id','$mel','$dato','$tid','$type')")){
echo '<p class="lykket">Du har startet nytt tema!</p>';
}
else{
echo '<p class="feil">Det oppstod et problem!</p>';
if($obj->status == 1){
echo '<p>'.mysqli_error().'</p>';
}
}
}
}
else{
$txt = null;
}
echo <<<ENDHTML
<form method="post" action="">
<table class="table" style="width:90%;">
<tr><th colspan="2">Opprett ny tr&aring;d</th></tr>
<tr>
<th>Tema:</th><td><input type="text" value="$txt" name="tema">
</tr>
<tr>
<th colspan="2">Melding:</th>
</tr>
<tr>
<td colspan="2" style="padding:0px;"><textarea style="border-width:0px;width:100%;min-height:300px;padding:0px;margin:0px;" name="meld" class="txar"></textarea></td>
<tr>
<th>Innhold:</th><td><select name="type"><option value="0">Generelt</option><option value="1">Salg</option><option value="2">S&oslash;knad</option><option value="3">Off topic</option></select></td>
</tr>
<tr>
<th colspan="2" class="last"><input type="submit" value="Opprett tema!"></th>
</tr>
</table>
</form>
ENDHTML;
}
else{//Viser forumindex
$get = $db->query("SELECT * FROM `forum` WHERE `slettet` = '0' ORDER BY `lasttime` DESC")or die(mysqli_error());
$rows = null;
while($r = mysqli_fetch_object($get)){
$sist = ($r->suid != '') ? $r->suid : '<i>Ingen</i>';
$rows .= '
<tr>
<td><a href="'.$_SERVER['PHP_SELF'].'?vis='.$r->id.'">'.$r->tema.'</a></td><td>'.$r->dato.'</td><td>'.user($r->uid).'</td><td>'.user($sist).'</td>
</tr>
';
}
echo <<< ENDHTML
<table class="table">
<tr class="c_1">
<th colspan="4">Generelt forum</th>
</tr>
<tr>
<th>Tema</th><th>Opprettet</th><th>Av</th><th>Sist svar av</th>
</tr>
$rows
</table>
ENDHTML;
}
}
?>
<?php
endpage();
}
?>
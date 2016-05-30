<?php
    include("core.php");
    if(r1() || r2()){
      if($obj->status == '3'){echo '<p class="feil">Forumban er stengt grunnet fatal feil i funksjonen.</p>';}
    startpage("Forumpanel");
    if(isset($_GET['edit']) || isset($_GET['del'])){
        if(isset($_GET['edit'])){
		$id = $db->escape($_GET['edit']);
		$db->query("DELETE FROM `forumban` WHERE `id` = '$id' LIMIT 1");
		echo '<p class="lykket">Forumban ble fjernet!</p></br>
		<a href="forumban.php">Gå tilbake!</a>';		
		
		}
else{
echo '<p class"feil">En feil skjedde da du skulle fjerne ban</p>';
}
		/*
            $id = $db->escape($_GET['edit']);
            if(isset($_POST['newtime'])){
                $v = $db->escape($_POST['newtime']);
                if(!in_array($v, array(1,2,3,4,5,6,7,8))){
                    echo '<p>Valget ditt er ikke gyldig!</p>';
                }
                else{
                    $v=$v-1;//Arrayindex starter på 0
                    $time = array(0,300,1800,3600,7200,14400,28800,201600,806400);
                    echo '<p class="feil">Funksjon er ikke klar enda!</p>';
                }
            }
            echo '<h1>Rediger forumban</h1>';
            $s = $db->query("SELECT * FROM `forumban` WHERE `id` = '$id'");
            if($db->num_rows() == 1){
                $obb=$db->fetch_object();
                $ban = user($obb->uid);
                $tid = date("H:i:s d.m.y",$obb->timeleft);
                $time= $obb->timeleft - time();
                echo <<<ENDHTML
                <form method="post" action="forumban.php?edit=$id">
                    <table class="table">
                        <thead>
                            <tr>
                                <th colspan="3">Rediger ban for $ban</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <th>Tid og dato straff avtar:</th><td>$tid</td>
                            </tr>
                            <tr>
                            <th>Beregnet tid ute:</th><td><span id="tid"></span></td>
                            </tr>
                            <tr>
                            <th colspan="2">Endre tiden</th>
                            </tr>
                            <tr>
                            <th>Velg ny tid:</th>
                            <td>
                                <select name="newtime">
									<option value="1">Fjern straff</option>
                                    <option value="2">5 minutter</option>
                                    <option value="3">30 minutter</option>
                                    <option value="4">1 time</option>
                                    <option value="5">6 timer</option>
                                    <option value="6">12 timer</option>
                                    <option value="7">1 dag</option>
                                    <option value="8">1 uke</option>
                                    <option value="9" style="background-color:#f00;">1 måned</option>
                                </select>
                            </td>
                            </tr>
                            <tr>
                                <th colspan="2"><input type="submit" value="Lagre..."></th>
                            </tr>
                        </tbody>
                    </table>
                </form>
                <script type="text/javascript">
                teller($time,"tid",false,"ned");
                </script>
ENDHTML;
            }
            else{
                echo '<p>Denne banen eksisterer ikke lengre, enten for at tiden banen varte er gått ut eller at noen andre har slettet den.</p>';
            }
            ?>

<?php
    }*/
	}
    else{
    if(isset($_POST['user']) && isset($_POST['res']) && isset($_POST['time'])){
        $user = $db->escape($_POST['user']);
        $res = $db->escape($_POST['res']);
        $time = $db->escape($_POST['time']);
        if(is_numeric($time) && strlen($res) >= 4){
            $s = $db->query("SELECT * FROM `users` WHERE `user` = '$user'");
            if($db->num_rows($s) == 1){
                $u = mysqli_fetch_object($s);
                $tim = array(300,900,1800,3600,18000,86400,604800);
                $timeleft = time() + $tim[$time];
                if($db->query("INSERT INTO `forumban`(`uid`,`date`,`timeleft`,`banid`,`res`) VALUES('$u->id','".time()."','$timeleft','$obj->id','$res')")){
                    $banres = '<p class="lykket">Spilleren har blitt bannet!</p>';
                }
                else{
                    $banres = '<p class="feil">Det oppstod feil i query!</p>';
                }
            }
            else{
                $banres = '<p class="feil">Brukeren eksisterer ikke!</p>';
            }
        }
        else{
        $banres = '<p class="feil">Tid ikke korrekt definert(endring i nettleser) eller grunn ikke lang nok! Grunn må være 4 tegn eller mer.</p>';
        }
    }
    if(isset($_GET['ban'])){
    $kill = $db->escape($_GET['ban']);
    $sporring = $db->query("SELECT * FROM `users` WHERE `id` = '$kill'");
    if($db->num_rows($sporring) == 0){NULL;}else{
        $asd = $db->fetch_object($sporring);
    }
}
?>
<h1>Forumbanpanel</h1>
<?php
if(isset($banres)){
echo $banres;
}
?>
<form method="post" action="">
<table class="table">
<tr>
<th colspan="2">Forumban en spiller</th>
</tr>
<tr>
<td>Spiller:</td><td><input type="text" value="<?=$asd->user?>"name="user" maxlength="15" /></td>
</tr>
<tr>
<td>Grunn:</td><td><input type="text" name="res" /></td>
</tr>
<tr>
<td>Varighet:</td><td>
<select name="time">
<option value="0">5 minutter</option>
<option value="1">15 minutter</option>
<option value="2">30 minutter</option>
<option value="3">1 time</option>
<option value="4">5 timer</option>
<option value="5">1 dag</option>
<option value="6">En uke</option>
</select>
</td>
</tr>
<tr>
<th colspan="2"><input type="submit" value="Ban spiller!" /></th>
</tr>
</table>
</form>
<table class="table" style="margin-top:20px;">
<tr>
<th colspan="6">Forumbannede spillere:</th>
</tr>
<tr>
<td>Bruker:</td><td>Dato bannet:</td><td>Banner:</td><td>Tid igjen:</td><td>Grunn</td><td><i name="redigerban">Ta vekk straff:</i></td>
</tr>
<?php
$t = time();
$s = $db->query("SELECT * FROM `forumban` WHERE `timeleft` > '$t' AND `act` = '1' ORDER BY `timeleft` DESC");
if($db->num_rows($s) >= 1){
while($r = mysqli_fetch_object($s)){
$time = $r->timeleft - time();
$time2 = time() - $r->timeleft;
echo '
<tr>
    <td>'.user($r->uid).'</td>
    <td>'.date("H:i:s d.m.Y",$r->date).'</td>
    <td>'.user($r->banid).'</td>
    <td><span id="user'.$r->uid.'"></span>
    <script type="text/javascript">teller('.$time.',"user'.$r->uid.'",false,"ned");</script>
    </td>
	<td>'.$r->res.'</td>
    <td><a title="Rediger" href="forumban.php?edit='.$r->id.'"><img src="imgs/edit.gif" alt="Rediger"></a></td>
</tr>
';
}
}
else{
echo '<tr>
<td colspan="5" style="text-align:center;"><em>Ingen er bannet atm.</em></td>
</tr>';
}
?>
</table>
<?php
}
}
else if($obj->status == '3'){
  startpage("STENGT");
  echo '<p class="feil">Forumban er stengt grunnet fatal feil i funksjonen. Vi åpner den straks.</p>';}
else{
startpage("Ingen tilgang!");
echo '
<h1>Ingen tilgang!</h1><p>Du har ikke tilgang til denne siden. Mener du dette er feil, ta kontakt med en admin.</p>
';
}
endpage();
?>
<?php
    include("core.php");
    startpage("Stjel penger");
    echo '<img src="imgs/Ranspiller.png">';
?>
<h1>Stjel penger fra spillere</h1>
<?php
if(bunker() == true){
	$bu = bunker(true);
	echo '
	<p class="feil">Du er i bunker, gjenst&aring;ende tid: <span id="bunker">'.$bu.'</span><br>Du er ute kl. '.date("H:i:s d.m.Y",$bu).'</p>
	<script>
	teller('.($bu - time()).',\'bunker\',false,\'ned\');
	</script>
	';
}
else if(fengsel() == true){
	echo '
	<p class="feil">Du er i fengsel, gjenst&aring;ende tid: <span id="krim">'.fengsel(true).'</span></p>
	<script>
	teller('.fengsel(true).',\'krim\',true,\'ned\');
	</script>
	';
}
else{
	  $time = time();
    $time2=$time+900;
    $t = $db->query("SELECT * FROM `ransp` WHERE `uid` = '$obj->id' AND `time` > '$time' ORDER BY `id` DESC LIMIT 1");
    if($db->num_rows() == 1){
    $l = mysqli_fetch_object($t);
    $left = $l->time - $time;
    if($left >= 1){
    $kan = 0;
    //M&aring; fortsatt vente
    echo '<p class="feil">Du m&aring; fortsatt vente i <span id="rantid"></span>!<!--<br>Tid: '.$l->time.' - '.$time.' = '.$left.'--></p><script>teller('.$left.',"rantid",true,"ned");</script>';
    }
    else{
    //echo '<p>time: '.$time.'<br>ltime: '.$l->time.'<br>time - ltime = '.$left.'</p>';
    $kan = 1;
    }
    }
    else{
    //F&oslash;rste kupp
    $kan = 1;
    }
    if(isset($_POST['spiller'])){
    if($kan == 0){
    echo '<p class="feil">Du kan ikke stjele fra noen enda!</p>';
    }
    else{
    $sp = $db->escape($_POST['spiller']);
    if(strtolower($sp) == strtolower($obj->user)){
    echo '<p class="feil">Du kan ikke rane deg selv! :)</p>';
    }
    else{
    $s = $db->query("SELECT * FROM `users` WHERE `user` = '$sp' LIMIT 1");
    if($db->num_rows() == 1){
    $f = $db->fetch_object();
    if($f->status == 1 || $f->status == 2){
    echo '<p class="feil">Du kan ikke rane ledelsen!</p>';
    }
    else if($f->health <= 0){
    echo '<p class="feil">Du kan ikke rane d&oslash;de spillere!</p>';
    }
    else{
    if($f->city == $obj->city){
    if($f->hand >= 500000){//Om spiller har over 500,000kr ute, s&aring; kan han bli bestj&aring;let
    $rand = rand(100000,$f->hand);
    $db->query("UPDATE `users` SET `hand` = (`hand` - $rand) WHERE `id` = '$f->id' LIMIT 1");
    $db->query("UPDATE `users` SET `hand` = (`hand` + $rand),`exp` = (`exp` + 2.0) WHERE `id` = '$obj->id' LIMIT 1");
    $db->query("INSERT INTO `ransp`(`uid`,`aid`,`us`,`as`,`kl`,`time`) VALUES('$obj->id','$f->id','$obj->city','$f->city','$rand','$time2')");
    $db->query("SELECT * FROM `oppuid` WHERE `uid` = '{$obj->id}' AND `done` = '0' AND `oid` = '4' ORDER BY `oid` DESC LIMIT 1");     
    if($db->num_rows() == 1 && $obj->city == 1){/*Sjekker om oppdrag 4 er aktivt*/
    $db->query("UPDATE `oppuid` SET `tms` = (`tms` + 1) WHERE `uid` = '{$obj->id}' AND `done` = '0' AND `tms` < '30' AND `oid` = '4' LIMIT 1");
}
    $db->query("INSERT INTO `sysmail`(`uid`,`time`,`msg`) VALUES ('".$f->id."','".time()."','".$db->slash('--<b>Ran Spiller</b><br/>'.$obj->user.' ranet '.number_format($rand).'kr fra deg!')."')");
    echo '<p class="lykket">Du klarte &aring; rane '.status($f->user).' for '.number_format($rand).' kr!</p>';
    }
    else{
    echo '<p class="feil">Mafiaen har ikke nok penger ute! '.$f->user.' har bare '.number_format($f->hand).' kr ute!</p>';
    }
    }
    else{
    echo '<p class="feil">Du var ikke i samme byen som mafiaen, du klarte det ikke!</p>';
    $rand = 0;
    $db->query("INSERT INTO `ransp`(`uid`,`aid`,`us`,`as`,`kl`,`time`) VALUES('$obj->id','$f->id','$obj->city','$f->city','$rand','$time2')");
    }
    }
    }
    else{
    echo '<p class="feil">Spilleren eksisterer ikke!</p>';
    }
    }
    }
    }
?><br>
<form method="post" action="">
    <table class="table" style="width: 300px;">
        <tr>
            <th colspan="2"><p style="width:300px;">Ran spiller<br><span style="font-size: 10px;">For at du skal klare &aring; stjele penger m&aring; spilleren ha minst 500,000kr ute og v&aelig;re i samme by som du er i, bommer du m&aring; du vente en stund f&oslash;r du kan pr&oslash;ve igjen.</span></p></th>
        </tr>
        <tr>
            <td>Nick:</td>
            <td>
                <input type="sumbit" maxlength="15" name="spiller">
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: center;">
                <input class="ran" type="submit" value="Ran spilleren!">
            </td>
        </tr>
    </table>
</form>
<?php
}
    endpage();
?>
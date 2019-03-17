<?php
include("core.php");
/*ini_set('display_errors',1);
error_reporting(E_ALL);*/
/*CREATE TABLE `jail`(
 * `id` INT NOT NULL AUTO_INCREMENT,
 * `uid` INT NOT NULL,
 * `reason` VARCHAR(100) NOT NULL DEFAULT 'Gjorde noe ulovlig!',
 * `time` BIGINT NOT NULL,
 * `timeleft` BIGINT NOT NULL,
 * `breaker` INT NULL,
 * PRIMARY KEY(`id`));
 */
if(fengsel()){
  $injail=true;
}
else $injail = false;
if(bunker() == true){
	startpage("Fengsel");
	echo '<h1>Fengsel</h1>';
	$bu = bunker(true);
	echo '
	<p class="feil">Du er i bunker, gjenst&aring;ende tid: <span id="bunker">'.$bu.'</span><br />Du er ute kl. '.date("H:i:s d.m.Y",$bu).'</p>
	<script type="text/javascript">
	teller('.($bu - time()).',\'bunker\',false,\'ned\');
	</script>
	';
}
else{
if(isset($_POST['valget']) && (isset($_POST['kjope']) || isset($_POST['bryte']))){
    $tid = $db->escape($_POST['valget']);
    if(isset($_POST['kjope'])) $ut = 1;
    else if(isset($_POST['bryte'])) $ut = 0;
    if(fengsel() == true){
        if($ut == 0) $res = '<p class="feil">Du kan ikke bryte ut noen når du er i fengselet selv!</p>';
        else if($ut == 1) $res = '<p class="feil">Du kan ikke kjøpe ut noen når du er i fengselet selv!</p>';
    }
    else{/*Fortsetter script*/
        if($ut == 1){
            /*Spilleren kjøpes ut om personen har råd*/
            $getjailuser = $db->query("SELECT * FROM `jail` WHERE `breaker` = '0' AND `timeleft` > UNIX_TIMESTAMP() AND `id` = '$tid'");
            if($db->num_rows() == 0){
                $res = '<p class="feil">Spilleren var ikke i fengsel!</p>';
            }
            $f = $db->fetch_object($getjailuser);
            if($f->timeleft >= time() + 900){
                $res = '<p class="feil">Du kan ikke kjøpe ut noen som har over 15minutter ventetid!</p>';
            }
            else{
                $e = $db->query("SELECT * FROM `jail` WHERE `breaker` = '0' AND `timeleft` > UNIX_TIMESTAMP() AND `id` = '$tid'");
                $f = $db->fetch_object($e);
                if($obj->hand >= $f->prisut){
                    if($db->query("UPDATE `users` SET `hand` = (`hand` - '{$f->prisut}') WHERE `id` = '{$obj->id}' LIMIT 1")){
                        if($db->query("UPDATE `jail` SET `breaker` = '{$obj->id}' WHERE `breaker` = '0' AND `id` = '$tid' AND `timeleft` > UNIX_TIMESTAMP() ORDER BY `id` DESC LIMIT 1")){
                            $db->query("UPDATE `jail` SET `bryttut` = '1' WHERE `id` = '$tid'");
                            $res = '<p class="lykket">Du har kjøpt ut '.user($f->uid).' for '.number_format($f->prisut).'kr!</p>';
                            $db->query("INSERT INTO `sysmail`(`uid`,`time`,`msg`) VALUES ('".$f->uid."','".time()."','".$db->slash('--<b>Fengsel</b><br/>'.$obj->user.' kjøpte deg ut fra fengslet!')."')");     
                        }
                    }
                }
                else{
                    $res = '<p class="feil">Du har ikke råd til å kjøpe ut '.status($f->uid,1).'!</p>';
                }
            }
        }
        else if($ut == 0){
            $getjailuser = $db->query("SELECT * FROM `jail` WHERE `breaker` = '0' AND `timeleft` > UNIX_TIMESTAMP() AND `id` = '$tid'");
            if($db->num_rows() == 0){
                $res = '<p class="feil">Spilleren var ikke i fengsel!</p>';
            }
            else{
                $f = $db->fetch_object();
                $pris = $f->prisut;
                $user = $f->uid;
                $q = $db->query("SELECT * FROM `users` WHERE `id` = '$user'");
                if($db->num_rows() == 0){
                    $userlocked = 'Ingen';
                }
                else{
                    $qq= $db->fetch_object();
                    $userlocked = $qq->user;
                }
                if($f->timeleft >= time() + 900){
                    $res = '<p class="feil">Du kan ikke bryte ut noen som har mere enn 15minutter ventetid!</p>';
                }
                else{
                $n2 = rand(1, 100);
                if($n2 <= 60){/*40 % sjanse for å havne i fengsel.*/
                    /*Spilleren settes i fengsel*/
                    if(settinn($obj->id, "Prøvde å bryte ut {$userlocked}", 90)){
                        $res = '<p class="feil">Du klarte ikke å bryte ut '.user($f->uid).'! Du ble satt inn selv.</p>';
                    }
                    else echo '<p class="feil">Av en eller annen grunn ble du ikke satt i fengselet!</p>';
                }
                else{
                    /*Spilleren klarte å bryte ut den innsatte!*/
                    if($db->query("UPDATE `jail` SET `breaker` = '{$obj->id}' WHERE `breaker` = '0' AND `id` = '$tid' AND `timeleft` > UNIX_TIMESTAMP() ORDER BY `id` DESC LIMIT 1")){
                        if($db->query("UPDATE `users` SET `exp` = (`exp` + 0.05) WHERE `id` = '{$obj->id}' LIMIT 1")){
                            $db->query("UPDATE `jail` SET `bryttut` = '1' WHERE `id` = '$tid'");
                            $res = '<p class="lykket">Du klarte å bryte ut '.user($f->uid).'!<br>Du fikk også litt rank!</p>';
                            //$db->query("INSERT INTO `sysmail`(`uid`,`time`,`msg`) VALUES ('".$f->uid."','".time()."','".$db->slash('--<b>Fengsel</b><br/>'.$obj->user.' brøt deg ut fra fengslet!')."')");   
                            sysmel($f->uid,'--<b>Fengsel</b></br>'.$obj->user.' brøt deg ut fra fengslet!');
                        }
                    }
                    }
                }
            }
        }
}
}
startpage("Fengselet");
echo '<img src="imgs/fengsel.png" />';
?>
<h1>Fengselet</h1>
<div style="margin: 0 auto;width: auto;text-align: center;">
</div>
<?php
    if(isset($res)) echo $res;
?>
<form method="post" action="">
<table class="table" style="margin: 0px auto; margin-top: 10px; margin-bottom: 15px;">
    <thead>
        <tr>
            <th>Bruker:</th><th>Grunn:</th><th>Tid igjen:</th><th>Kausjonspris</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $timenow = time();
            $s = $db->query("SELECT * FROM `jail` WHERE `breaker` = '0' AND `timeleft` > '$timenow' ORDER BY `time` DESC");
            if($db->num_rows() >= 1){
                $numid = 0;
                while ($r = mysqli_fetch_object($s)) {
                    $numid = $numid + 1;
                    $left = $r->timeleft - $timenow;
                    echo '<tr class="velg" id="n'.$numid.'" onclick="select('.$r->id.',this);">
                        <td>'.user($r->uid,0).'</td>
                            <td>'.$r->reason.'</td>
                                <td><span id="f'.$r->id.'"></span>
                                    <script type="text/javascript">teller('.$left.',"f'.$r->id.'",false,"ned");</script>
                                </td>
                                <td>'.  number_format($r->prisut).'kr</td>
                        </tr>';
                }
                
            if(fengsel() == 1) $ak = 1;
            if(fengsel() == 0) $ak = 0;
            if($ak == 1) $extra = null;
            //if($ak == 1) $extraone = '<tr><td colspan="4"><input type="submit" value="Jeg er en dum knapp uten funksjon :)" disabled name="selv" class="button2"></td></tr>';
            if($ak == 0) $extraone = NULL;
                    if($ak == 0) $extra = 
                        '<tr>
                            <td colspan="4" style="text-align: center;">
                                <input type="submit" value="Bryt ut!" name="bryte" class="button2">
                                <input type="submit" value="Kjøp ut!" name="kjope" class="button2">
                                
                            </td>
                        </tr>';
                    echo $extra; echo $extraone;
            }
            else{
                echo '<tr><td colspan="4" style="text-align: center;"><em>Det er ingen innsatt i fengselet, sjekk igjen senere!</em></td></tr>';
            }
        ?>
    </tbody>
</table>
<input type="hidden" id="valget" value name="valget">
</form>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.velg').hover(function(){
                $(this).not(".valgt").removeClass().addClass('c_2 velg').css('cursor','pointer');
            },function() {
                $(this).not(".valgt").removeClass().addClass('velg').css('cursor','pointer');
            });
        });
        function select(id,self){
            $("table.table tr.valgt").removeClass().addClass("velg").css('cursor','pointer');
            $(self).removeClass().addClass('c_3 valgt').css('cursor','pointer');
            $("#valget").val(id);
        }
    </script>
<?php
/*if(isset($_POST['selv'])){
   $db->query("SELECT * FROM `jail` WHERE `uid` = '$obj->id' AND `timeleft` > UNIX_TIMESTAMP() AND `bryttut` = '0'");
   if($db->num_rows() == 1){
     $r = $db->fetch_object();
     if($r->timeleft > time() + 300){
       echo '<p class="feil">Du har over 5 minutter igjen av straffen din, og kan ikke bryte deg selv ut!</p>';
     }
     else{
     $rand = mt_rand(1,100);
     if($rand > 80){
       $db->query("UPDATE `jail` SET `bryttut` = '1',`breaker`='".$obj->id."' WHERE `uid` = '$obj->id' AND `id` = '$r->id'");
       echo '<p class="lykket">Du klarte å bryte ut deg selv!';
     }
     else if($rand < 80){
     $db->query("UPDATE `jail` SET `timeleft` = (`timeleft` + 120) WHERE `uid` = '$obj->id' AND `id` = '$r->id'");
     $db->query("UPDATE `jail` SET `reason` = 'Prøvde å bryte seg selv ut' WHERE `id` = '$r->id' AND `uid` = '$obj->id'");
     echo '<p class="feil">Du klarte ikke å bryte ut deg selv. Du fikk to minutter ekstra i straff! Du kan ikke bryte deg selv ut flere enn en gang per straff.';
     }  
   }
   }
  }*/
}
if(fengsel() == true){
echo '
<p class="feil">Du er i fengsel, gjenstående tid: <span id="krim">'.fengsel(true).'</span></p>
<script type= "text/javascript">
teller('.fengsel(true).',\'krim\',true,\'ned\');
</script>
';
}
endpage();
?>
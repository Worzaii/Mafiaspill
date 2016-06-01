<?php
include("core.php");
startpage("Kast Mynt");
if(r1() || r2()){
// Ett veldig simpelt kast mynt script.
// Scriptet er ikke lansert fordi jeg vil lage en logg over hver enkelt spiller
// som spiller, for å se om noen jukser med dette, eller rett og slett for å lage
// en grei oversikt.
if(isset($_POST['bet'])){
        $innsats = $db->escape($_POST['innsats']);
        $innsats = str_replace(",","",$innsats);
        $innsats = str_replace(".","",$innsats);
        $innsats = str_replace(" ","",$innsats);
        $innsats = str_replace("kr","",$innsats);
    $winning = $innsats * 2;
    $random = mt_rand(1,100);
    if($innsats <= 0){
        echo '<p class="feil">Du må satse mer enn 0kr!</p>';
    }elseif($innsats >= 200000000){
        echo '<p class="feil">Du kan ikke satse mer enn 200,000,000r</p>';
    }elseif($obj->hand <= $innsats){
        echo '<p class="feil">Du må ha penger ute på hånden.</p>';
    }elseif($obj->hand >= $innsats){
    if($random <=50){
        echo '<p class="feil"`>Du satset '.number_format($innsats).'kr og tapte!</p>';
        $db->query("UPDATE `users` SET `hand` = (`hand` - $innsats) WHERE `id` = '$obj->id' LIMIT 1");
        $db->query("INSERT INTO `kastmynt_logg` (`uid`,`innsats`,`gevinst`,`time`,`name`,) VALUES ('$obj->id','$innsats','-$innsats',UNIX_TIMESTAMP(),'$obj->user')");
        $db->query("UPDATE `kastmynt_logg` SET `total_win_loss` = (`total_win_loss` - $innsats) WHERE `id` = '".$db->insert_id()."'");
    }elseif($random >=50){
        echo '<p class="lykket">Du vant '.number_format($winning).'kr</p>';
        $db->query("UPDATE `users` SET `hand` = (`hand` + $winning) WHERE `id` = '$obj->id'");
        $db->query("INSERT INTO `kastmynt_logg` (`uid`,`innsats`,`gevinst`,`time`,`name`) VALUES
                ('$obj->id','$innsats','$winning',UNIX_TIMESTAMP(),'$obj->user')");
        $db->query("UPDATE `kastmynt_logg` SET `total_win_loss` = (`total_win_loss` + $winning) WHERE `id` = '".$db->insert_id()."'");
        
        }
    }
}
?>
<p>Plasser din innsats her. Det er 50/50 sjanse for å vinne eller og tape.<br />
    Spillet er i beta form og du spiller på <u style="color:red;">EGET ANSVAR!</u></p>
<form action="" method="post">
    <table class="table">
        <tr><td><input type="text" name="innsats" placeholder="Din Innsats"></td>
            <td><input type="submit" name="bet" value="Sats"/></td></tr>
    </table>
</form>
<?php
}else{noaccess();}
endpage();
?>
<?php
    include("core.php");
if (fengsel() == true) {
    startpage("Poeng");
    echo '
<p class="feil">Du er i fengsel, gjenstående tid: <span id="krim">'.fengsel(true).'</span></p>
<script type= "text/javascript">
teller(' . fengsel(true) . ')
</script>
';
} else if (bunker() == true) {
    startpage("Poeng");
    $bu = bunker(true);
    echo '
<p class="feil">Du er i bunker, gjenstående tid: <span id="bunker">' . $bu . '</span><br>Du er ute kl. ' . date("H:i:s d.m.Y", $bu) . '</p>
<script>
teller(' . ($bu - time()) . ')
</script>
';
} else {
    if (true) {
        $style = '<script src="http://fortumo.com/javascripts/fortumopay.js" type="text/javascript"></script>';
        startpage("Poengkjøp", $style);
        if (isset($_GET['hundremill'])) {
            if ($obj->points < 200) {
                echo feil('Du har ikke nok poeng!');
            } else {
                $db->query("UPDATE `users` SET `points` = (`points` - 200) WHERE `user`= '$obj->user' LIMIT 1");
                $db->query("UPDATE `users` SET `hand` = (`hand` + 100000000) WHERE `user`= '$obj->user' LIMIT 1");
                $db->query("INSERT INTO `poenglogg`(`uid`,`hva`,`tid`) VALUES ('.$obj->id.','Hundre millioner','" . time() . "')");
                echo lykket('Du har kjøpt 100,000,000kr for 200 poeng!');
            }
        } else if (isset($_GET['kuler'])) {
            if ($obj->points < 150) {
                echo feil('Du har ikke nok poeng!');
            } else {
                $db->query("UPDATE `users` SET `points` = (`points` - 150) WHERE `user`= '$obj->user' LIMIT 1");
                $db->query("UPDATE `users` SET `bullets` = (`bullets` + 100) WHERE `user`= '$obj->user' LIMIT 1");
                $db->query("INSERT INTO `poenglogg`(`uid`,`hva`,`tid`) VALUES ('.$obj->id.','Hundre kuler','" . time() . "')");
                echo lykket('Du har kjøpt 100 kuler for 150 poeng!');
            }
        } else if (isset($_GET['fulltliv'])) {
            if ($obj->points < 20) {
                echo feil('Du har ikke nok poeng!');
            } else if ($obj->health == 100) {
                echo feil('Du har allerede full helse!');
            } else {
                $db->query("UPDATE `users` SET `points` = (`points` - 20) WHERE `user`= '$obj->user' LIMIT 1");
                $db->query("UPDATE `users` SET `health` = '100' WHERE `user`= '$obj->user' LIMIT 1");
                $db->query("INSERT INTO `poenglogg`(`uid`,`hva`,`tid`) VALUES ('.$obj->id.','Liv påfyll (100% Liv)','" . time() . "')");
                echo lykket('Du har nå fullt liv!');
            }
        } else if (isset($_GET['skipen'])) {
            if ($obj->points < 20) {
                echo feil('Du har ikke nok poeng!');
            } else {
                $sjekk1 = $db->query("SELECT * FROM `oppuid` WHERE `oid` = '1' AND `done` AND `tms` AND `uid` = {$obj->id}");
                $hent1 = $db->fetch_object($sjekk1);
                if ($hent1->tms >= 25) {
                    echo feil('Du har allerede fullført dette oppdraget!');
                } else {
                    $db->query("UPDATE `users` SET `points` = (`points` - 20) WHERE `user`= '$obj->user' LIMIT 1");
                    $db->query("UPDATE `oppuid` SET `tms` = '25' WHERE `oid` = '1' AND `uid` = {$obj->id} LIMIT 1");
                    $db->query("INSERT INTO `poenglogg`(`uid`,`hva`,`tid`) VALUES ('.$obj->id.','Skippet oppdrag 1','" . time() . "')");
                    echo lykket('Du har nå skippet oppdrag 1 for 20 Poeng! (Husk å gå til oppdrag og fullfør oppdraget, før du skipper neste.)');
                }
            }
        }
        $sjekk = $db->query("SELECT * FROM `oppuid` WHERE `oid` = '2' AND `tms` AND `uid` = {$obj->id}");
        $hent = $db->fetch_object($sjekk);
        if (isset($_GET['skipto'])) {
            if ($obj->points < 50) {
                echo feil('Du har ikke nok poeng!');
            } else {
                $sjekk1 = $db->query("SELECT * FROM `oppuid` WHERE `oid` = '1' AND `done` AND `tms` AND `uid` = {$obj->id}");
                $hent1 = $db->fetch_object($sjekk1);
                if ($hent1->tms < 24) {
                    echo '<p class="feil">Du kan ikke skippe oppdrag 2, før du har skippet oppdrag 1!</br>';
                }
                if ($hent->tms == 750) {
                    echo feil('Du har allerede fullført dette oppdraget!');
                }
                if ($hent->tms < 750) {
                    $db->query("UPDATE `users` SET `points` = (`points` - 50) WHERE `user`= '$obj->user' LIMIT 1");
                    $db->query("UPDATE `oppuid` SET `tms` = '750' WHERE `oid`= '2' AND `uid` = {$obj->id} LIMIT 1");
                    $db->query("INSERT INTO `poenglogg`(`uid`,`hva`,`tid`) VALUES ('.$obj->id.','Skippet oppdrag 2','" . time() . "')");
                    echo lykket('Du har skippet oppdrag 2 for 50 Poeng!(Husk å gå til oppdrag og fullfør oppdraget, før du skipper neste.)');
                }
            }
        } else if (isset($_GET['ranstats'])) {
            if ($obj->points < 20) {
                echo feil('Du har ikke nok poeng!');
            } else {
                $db->query("UPDATE `users` SET `points` = (`points` - 20) WHERE `user`= '$obj->user' LIMIT 1");
                $db->query("INSERT INTO `poenglogg`(`uid`,`hva`,`tid`) VALUES ('.$obj->id.','Ran-statistikk','" . time() . "')");
                echo lykket('Viser 10 spillere med over 500,000 ute!');
                ?> 
<table class="table">
    <tr>
        <th colspan="3">Ran-Statistikk</th>
    </tr>
    <tr>
        <td>Spiller</td>
        <td>Penger ute</td>
        <td>By</td>      
    </tr>
    <?php
    $r = $db->query("SELECT * FROM `users` WHERE `hand` > '499999' AND `status` <> '1' AND `status` <> '2' ORDER BY `hand` DESC LIMIT 10");
    if ($db->num_rows($r) == 0) {
        echo '<td>Ingen brukere har over 500,000 ute på hånden.</td>';
    } else {
        while ($f = mysqli_fetch_object($r)) {
            echo '
<tr>
<td>'.user($f->id).'</td><td>'.number_format($f->hand).'</td><td>'.city($f->city).'</td>
</tr>
';
        }
    }
    ?>
</table>
                <?php
            }
        }


        ?>
<h1>Kjøpe poeng?</h1>
<p>Retningslinjer som gjelder ved kjøp av poeng!<br>
<br><p>
�. 1 Du må være eier av telefonen du bestiller fra, eller ha samtykke av foreldre/foresatte til å kjøpe poeng hos mafia-no.net.<br>
�. 2 Du vil ikke under noen omstendighet få noen form for refundering.<br>
�. 3 Om du finner feil, er du pålagt til å rapportere de til en i ledelsen.<br>
�. 4 Om du blir drept, modkillet eller utestengt fra spillet så viser vi til punkt 2.<br>
�. 5 Du må være minst 15 år for å kjøpe poeng, og ha tillatelse av dine foreldre/foresatte!
</p>

<p>Klikk på knappen under for å få opp Fortumo. Den vil forklare deg hvordan du skal gå fram for å bestille poeng!</p>
<p>Om du har problemer med poengkjøpet, ta kontakt med <a href="innboks.php?ny&usertoo=Werzaire">Werzaire</a> eller <a href="innboks.php?ny&usertoo=Inside">Inside</a> snarest!</p>
<p>
    <a id="fmp-button" href="#" rel="dccd681af34a5efda41420fb9f82a359/<?php echo $obj->id?>">
        <img src="http://fortumo.com/images/fmp/fortumopay_96x47.png" width="96" height="47" alt="Mobile Payments by Fortumo" border="0">
    </a>
</p>
<br>
<!--<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="MNE82PXL8EX28">
<table>
<tr><td><input type="hidden" name="on0" value="Poeng kjøp">Poeng kjøp</td></tr><tr><td><select name="os0">
 <option value="500 Poeng">500 Poeng 150,00 NOK</option>
 <option value="1500 Poeng">1500 Poeng 500,00 NOK</option>
 <option value="3000 Poeng">3000 Poeng 1 000,00 NOK</option>
</select> </td></tr>
</table>
<input type="hidden" name="currency_code" value="NOK">
<input type="image" src="https://www.paypalobjects.com/no_NO/NO/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal ? den trygge og enkle metoden for å betale på nettet.">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>-->
<h1>Kjøp fordeler</h1>
<p>Når du har kjøpt noe med poeng som du har bestilt, så er det ikke mulighet for å få tilbake poengene du har brukt.</p>
<form method="post" action="" onsubmit="return false;">
    <table class="table">
        <thead>
            <tr>
                <th>Hva</th><th>Pris</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><a onclick="return confirm('Er du sikker på at du vil bruke 200 poeng for 100,000,000kr?')" href="poeng.php?hundremill">Kjøp 100,000,000</a></td><td>200 Poeng</td></tr>
            <tr><td><a onclick="return confirm('Er du sikker på at du vil bruke 150 poeng for 100 kuler?')" href="poeng.php?kuler">Kjøp 100 kuler <b style="color:red;">NB: Husk at drap er stengt!</b></a></td><td>150 Poeng</td></tr>
                <tr><td><a onclick="return confirm('Er du sikker på at du vil bruke 20 poeng for 100% liv?')" href="poeng.php?fulltliv">Fyll helsen din fullt opp! <b style="color:red;">NB: Husk at drap er stengt!</b></td><td>20 Poeng</td></tr>
                <tr><td>Skip oppdrag:<a onclick="return confirm('Er du sikker på at du vil bruke 20 poeng for å skippe oppdrag 1?')" href="poeng.php?skipen">1, </a><a onclick="return confirm('Er du sikker på at du vil bruke 50 poeng for å skippe oppdrag 2?')" href="poeng.php?skipto">2, </a><a onclick="return confirm('Er du sikker på at du vil bruke 70 poeng for å skippe oppdrag 3?')" href="poeng.php?skiptre">3</a> </td><td>1 = 20 Poeng -- 2 = 50 Poeng</td></tr>
                <tr><td><a onclick="return confirm('Er du sikker på at du vil bruke 20 poeng for å se ran-stats?')" href="poeng.php?ranstats">Se 10 spillere som har over 500,000kr ute på hånden</a></td><td>20 Poeng</td></tr>

        </tbody>
    </table>
</form>
        <?php
    } else {
        startpage("Siden er ikke klar!");
        echo '<h1>Poengsiden er ikke klar!</h1><p>Ikke prøv å bestille poeng enda.</p>';
    }
}
    endpage();
?>

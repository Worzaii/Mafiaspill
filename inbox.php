<?php
    include("core.php");
    startpage("Innboksen");
?>
<?php
//echo '<p style="-moz-border-radius: 3px;-webkit-border-radius: 3px;background: #322929;border: 1px solid #4F4141;border-radius: 3px;color: #666;font-family: verdana;font-size: 11px;font-weight: 700;line-height: 30px;margin: 10px auto;padding: 5px;text-align: center;text-shadow: 1px 1px 0 black;width: 540px;">Meldingssystemet er ikke feilfritt. Det er kun �n feil som gjenst&aring;r. Feilen er slik at f&oslash;rste melding med tema som du sender til en person vises ikke. Dette jobbes med.</p>';//Notis til de som bruker meldinger
if (!isset($_GET['do'])) {
    echo '
    <h1>Innboks</h1>
    <p><a href="inbox.php?do=1" class="button">Ny melding</a><a href="" class="button">Slett merkede meldinger</a><a href="" class="button">Slett hele innboksen!</a><a href="" class="button">Merk merkede som lest</a></p>
    ';
    $sql = mysql_query("SELECT * FROM `mail` WHERE `TId` = '$obj->id' OR `UId` = '$obj->id' GROUP BY `theme` ORDER BY `time` DESC")or die('Feil: '.mysql_error());
    if (mysql_num_rows($sql) >= 1) {
        echo '
    <table id="innbokstable" class="table">
    <tr class="first">
    <th colspan="2">Innboks:</th>
    </tr>
    ';
        while ($r = mysql_fetch_object($sql)) {
            if ($r->UId == $obj->id) {
                $sender = $r->TId;
            } else {
                $sender = $r->UId;
            }
            if ($r->read == 0) {
                $ny = "(Ny!)";
            } else {
                $ny = null;
            }
            $till = mysql_query("SELECT * FROM `users` WHERE `id` = '$sender'");
            $til = mysql_fetch_object($till);
            echo '
    <tr onclick="javascript:window.location.href=\'inbox.php?do=2&id='.$r->id.'\'">
    <th>'.$r->theme.$ny.'</th><td>'.$til->user.'</td>
    </tr>
    ';
        }
        echo '</table>';
    }
}
if (isset($_GET['do'])) {
    if ($_GET['do'] == 1) {//Ny melding tab
        echo '<h1>Ny melding</h1>';
        if (isset($_POST['bruker']) && isset($_POST['tema']) && isset($_POST['melding'])) {
            $sms = mysql_real_escape_string($_POST['melding']);
            $usto = mysql_real_escape_string($_POST['bruker']);
            $tema = mysql_real_escape_string($_POST['tema']);
            $time = time();
            $dato = date("H:i:s d.m.Y");
            if (strlen($sms) == 0) {
                echo '
<p class="feil">Meldingsinnholdet var ikke stort nok! Du m&aring; ha mer enn 1 tegn.</p>
';
            } else if (strlen($usto) <= 3) {
                echo '<p class="feil">Brukernavnet er ikke gyldig, det er for kort!</p>';
            } else if (strlen($tema) == 0) {
                $tema = 'Uten tema';
            } else {//Fortsetter sendingen av melding
                $q = mysql_query("SELECT * FROM `users` WHERE `user` = '$usto'");
                if (mysql_num_rows($q) == 1) {
                    $u = mysql_fetch_object($q);
                    if (mysql_query("INSERT INTO `mail`(`id`,`UId`,`TId`,`theme`,`message`,`time`,`date`) VALUES(NULL,'$obj->id','$u->id','$tema','$sms','$time','$dato')")) {
                        echo '<p class="lykket">Meldingen er blitt sendt!</p>';
                    } else {
                        if ($obj->status == 1) {
                            echo '
<p>Meldingen ble ikke sendt! Problemet: '.mysql_error().'</p>
';
                        } else {
                            echo '
<p class="feil">Det har oppst&aring;tt en feil! Rapporter til Support snarest!</p>
';
                        }
                    }
                } else {
                    echo '<p class="feil">Brukeren var ikke funnet!</p>';
                }
            }
        }
        echo <<<END
<form method="post" action="inbox.php?do=1">
<table class="innboks nymelding">
<tr>
<th colspan="2">Ny samtale(melding)</th>
</tr>
<tr>
<td>Tema:</td><td><input type="tema" value="" name="tema"></td>
</tr>
<tr>
<td>Spiller:</td><td><input type="text" value="" maxlength="20" name="bruker"></td>
</tr>
<tr>
<td colspan="2"><p>Melding:</p>
<textarea name="melding" style=""></textarea>
</td>
</tr>
<tr>
<th colspan="2"><input class="small awesome" type="submit" value="Start samtalen!"></th>
</tr>
</table>
</form>
END;
    } else if ($_GET['do'] == 2) {
        echo '<h1>Leser melding</h1>';
        echo '<a href="inbox.php">Tilbake til innboks</a>';
        $id = mysql_real_escape_string($_GET['id']);
        if (!is_numeric($id)) {
            echo '<p class="feil">Meldingens id er ikke gyldig!</p>';
        } else if ($id <= 0) {
            echo '<p class="feil">Pr&oslash;ver du hack eller noe? xD</p>';
        } else if (is_numeric($id) && $id >= 1) {
            $sql = mysql_query("SELECT * FROM `mail` WHERE `UId` = '$obj->id' AND `id` = '$id' OR `TId` = '$obj->id' AND `id` = '$id'") or die(mysql_error());
            $sql2 = mysql_fetch_object($sql) or die(mysql_error());
            $sql3 = mysql_query("SELECT * FROM `mail` WHERE `theme` = '$sql2->theme' AND `UId` = '$obj->id' OR `theme` = '$sql2->theme' AND `TId` = '$obj->id' ORDER BY `id` DESC") or die(mysql_error());
            if (!mysql_query("UPDATE `mail` SET `read` = '1' WHERE `theme` = '$sql2->theme' AND `TId` = '$obj->id'")) {
                if ($obj->status == 1) {
                    echo '<p class="feil">Kunne ikke oppdatere "lest/ulest". Feil: ' . mysql_error() . '</p>';
                }
            }
            if (isset($_POST['svar1'])) {
                $f3 = mysql_fetch_object($sql3);
                $svar = mysql_real_escape_string($_POST['svar1']);
                $tema = $f3->theme;
                /*feil med svar til avsender*/
                if ($obj->id == $f3->UId) {
                    $till = $f3->TId;
                } else {
                    $till = $f3->UId;
                }
                $time = time();
                $dato = date("H:i:s d.m.Y");
                if (mysql_query("INSERT INTO `mail`(`id`,`UId`,`TId`,`theme`,`message`,`time`,`date`) VALUES(NULL,'$obj->id','$till','$tema','$svar','$time','$dato')")) {
                    echo '<p class="lykket">Meldingen er blitt sendt!</p>';
                } else {
                    if ($obj->status == 1) {
                        echo '
<p>Meldingen ble ikke sendt! Problemet: '.mysql_error().'</p>
';
                    } else {
                        echo '
<p class="feil">Det har oppst&aring;tt en feil! Rapporter til Support snarest!</p>
';
                    }
                }
            }
            if (mysql_num_rows($sql3) >= 1) {
                while ($getmessages = mysql_fetch_object($sql3)) {
                    $melding = str_replace("\n", "<br>", $getmessages->message);
                    if ($obj->id == $getmessages->UId) {//Spilleren som skriver
                        echo '
<div class="left">'.user($getmessages->UId).': '.$melding.'</div>
';
                    } else {
                        echo '
<div class="right">'.user($getmessages->UId).': '.$melding.'</div>
';
                    }
                }
                echo '
<div class="c"></div>
<div class="svar">
<p>Besvar:</p>
<form method="post" action="inbox.php?do=2&id='.$id.'">
<textarea name="svar1"></textarea><br><input type="submit" value="Send!">
</form>
</div>
';
            } else {
                echo '<p>Meldingen eksisterer ikke!</p>';
            }
        }
    } else {
        echo '<h1>Denne siden eksisterer ikke!</h1><p><a href="inbox.php" class="amazing small">Tilbake</a> </p>';
    }
}
endpage();

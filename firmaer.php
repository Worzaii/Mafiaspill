<?php
include("core.php");
startpage("Firma-paneler");
if (bunker()) {
    $bu = bunker(true);
    $tid = date("H:i:s d.m.Y", $bu);
    echo <<<HTML
    <p class="feil">Du er i fengsel, gjenstående tid: <span id="fengsel">$bu</span><br>Du er ute: $tid</p>
    <script>
    teller('$bu','bunker',false,'ned');
    </script>
HTML;
} elseif (fengsel()) {
    $fe = fengsel(true);
    $tid = date("H:i:s d.m.Y", $fe);
    echo <<<HTML
    <p class="feil">Du er i fengsel, gjenstående tid: <span id="fengsel">$fe</span><br>Du er ute: $tid</p>
    <script>
    teller('$fe','bunker',false,'ned');
    </script>
HTML;
} else {
    echo '<h1>Dine firmaer</h1>';
    $config = ["type" => ["1" => "Lottofirma", "2" => "Flyplassfirma"]];
    $s = $db->query("SELECT count(*) FROM `firms` ORDER BY `id` ASC");
    /**
     * TODO: Recreate the script with the logic within instead of query. Convert to fully PDO query prepared statements
     */
    if ($s->fetchColumn() >= 1) {
        $s = $db->query("select * from firms order by id asc ");
        if (isset($_POST['allout']) || isset($_POST['overfor'])) {
            if (isset($_POST['allout'])) {
                $id = $db->escape($_POST['id']);
                $in = firma($id);
                $eier = $in[1];
                if ($eier == $obj->id) {
                    /*Er eieren...*/
                    $db->query("UPDATE `users` SET `hand` = (`hand` + " . $in[3] . ") WHERE `id` = '{$eier}' LIMIT 1");
                    $db->query("UPDATE `firms` SET `bank` = '0' WHERE `id` = '$id' LIMIT 1");
                    echo lykket('Du tok ut ' . number_format($in[3]) . ' kr!');
                } else {
                    echo feil('Dette er ikke ditt firma!');
                }
            } elseif (isset($_POST['overfor']) && isset($_POST['overfortil'])) {
                $usr = $_POST['overfortil'];
                $id = $db->escape($_POST['id']);
                $u = (getUser($usr) == true) ? true : false;
                if ($u == true) {
                    $uin = getUser($usr, 1);
                    if (is_numeric($uin)) {
                        if ($db->query("UPDATE `firms` SET `uid` = '{$uin}' WHERE `id` = '$id' LIMIT 1")) {
                            echo lykket('Du har overført firmaet ditt til ' . user($uin) . '!');
                            #sysmel($uin,'<b>--Firma</b><br>Du har mottat et firma ifra ' . user($obj->id) . '! Klikk her for å se ditt panel over firmaene dine: <a href="http://mafia-no.net/Firmaer">Firmapanel</a>');
                        } else {
                            echo feil('Feil i spørring! ' . $db->get_last_error() . '');
                        }
                    }
                } else {
                    echo feil('Brukeren eksisterer ikke!');
                }
            }
        }
        while ($a = mysqli_fetch_object($s)) {
            if ($a->Type == 1) {
                /*Lottofirma har ekstra egenskaper*/
                /*Noen variabler for Lotto*/
                $lottoupdate = '<input type="submit" name="changelotto" value="Oppdater Lottoinnstillinger."><br>';
                /*END VARIABLER*/
                $get_settings = $db->query("SELECT * FROM lotteryconfig ORDER BY `id` DESC LIMIT 1");
                if ($db->num_rows() == 1) {
                    $val = $db->fetch_object();
                    $current_value1 = $val->Loddpris;
                    $current_value2 = $val->Tid;
                    $current_value3 = $val->Prosent;
                    $current_value4 = $val->Antlodd;
                } else {
                    $current_value1 = null;
                    $current_value2 = null;
                    $current_value3 = null;
                    $current_value4 = null;
                }
                if (isset($_POST['changelotto']) && (isset($_POST['edit1lotto']) && isset($_POST['edit2lotto']) && isset($_POST['edit3lotto']) && isset($_POST['edit4lotto']))) {
                    /*Endrer verdier om de blir godkjente*/
                    $v1 = $db->escape($_POST['edit1lotto']);
                    $v2 = $db->escape($_POST['edit2lotto']);
                    $v3 = $db->escape($_POST['edit3lotto']);
                    $v4 = $db->escape($_POST['edit4lotto']);
                    if ($v1 == $current_value1 && $v2 == $current_value2 && $v3 == $current_value3 && $v4 == $current_value4) {
                        echo feil('Verdiene er allerede oppdatert, prøv heller å endre den før du lagrer! =)');
                    } elseif (($v1 >= 10000 && $v1 <= 1000000) && ($v2 >= 10 && $v2 <= 60) && ($v3 >= 5 && $v3 <= 30) && ($v4 >= 100 && $v4 <= 1000)) {
                        if ($db->query("INSERT INTO lotteryconfig(`ticketprice`,`timestamp`,`percentage`,`numticks`) VALUES('$v1','$v2','$v3','$v4')")) {
                            echo lykket('Du har oppdatert instillingene for lotto, de vil bli aktive ved neste runde.');
                            $current_value1 = $v1;
                            $current_value2 = $v2;
                            $current_value3 = $v3;
                            $current_value4 = $v4;
                        } else {
                            echo feil('Det oppstod en feil ved oppdatering, ta kontakt med Werzaire og gi han dette: "' . mysqli_error($db->con) . '"');
                        }
                    } else {
                        if ($v1 >= 10000 && $v1 <= 1000000) {
                            echo warning('Tallet må være mellom 10,000kr-1,000,000kr på loddprisen!');
                        }
                        if ($v2 >= 10 && $v2 <= 60) {
                            echo warning('Tallet må være mellom 10 til 60 minutter på tiden!');
                        }
                        if ($v3 >= 5 && $v3 <= 30) {
                            echo warning('Tallet må være mellom 5%-15% på prosenten som trekkes av til firmakonto!');
                        }
                        if ($v4 >= 100 && $v4 <= 1000) {
                            echo warning('Tallet må være mellom 100-1,000 stk på antall lodd spillere kan kjøpe samtidig!');
                        }
                    }
                }
                $ex = '
      <tr>
        <td>Pris per lodd:</td><td><input name="edit1lotto" type="number" min="10000" max="1000000" value="' . $current_value1 . '"> kr<br>Minst: 10,000kr Maks: 1,000,000kr</td>
      </tr>
      <tr>
        <td>Tid per lottorunde:</td><td><input name="edit2lotto" type="number" min="10" max="60" value="' . $current_value2 . '"> minutter<br>Minst: 10 minutter Maks 1 time(60 minutter)</td>
      </tr>
      <tr>
        <td>Trekk til deg:</td><td><input name="edit3lotto" type="number" min="5" max="15" value="' . $current_value3 . '"> % trekk<br>Minst: 5% Maks: 30%</td>
      </tr>
      <tr>
        <td>Antall mulige lodd:</td><td><input name="edit4lotto" type="number" min="100" max="1000" value="' . $current_value4 . '"> stk<br>Minst: 100 lodd Maks: 1,000 lodd</td>
      </tr>
      ';
                $by = null;
            } else {
                $ex = null;
                $by = "en i " . city($a->By);
                $lottoupdate = null;
            }
            echo '<form method="post" action="">
    <table class="table">
      <thead>
        <tr><th colspan="2">Type: ' . $config['type'][$a->Type] . '&raquo;<span title="Flyplassens navn">' . htmlentities($a->Navn) . "</span>" . $by . '</th></tr>
      </thead>
      <tbody>
      <tr>
        <td>Eier</td><td>' . status($a->Eier, 1) . '</td>
      </tr>
      <tr>
        <td>Innsamlet:</td><td>' . number_format($a->Konto) . ' kr</td>
      </tr>
      ' . $ex . '
      <tr>
        <th colspan="2">Valg for eier:</th>
      </tr>
      <tr>
        <td colspan="2">
        ' . $lottoupdate . '
        <input type="submit" name="allout" value="Ta ut alle pengene!"><br>
        <input type="submit" value="Overfør til en annen spiller:" name="overfor">
        <input type="text" placeholder="Nick" name="overfortil"></td>
      </tr>
      </tbody>
    </table>
    <input type="hidden" value="' . $a->id . '" name="id">
    </form>';
        }
    } else {
        echo feil('Ingen firmapaneler funnet!');
    }
}
endpage();

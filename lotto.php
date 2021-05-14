<?php

include("core.php");
startpage("Lotto");
echo "<h1>Lotto</h1>";
if (bunker()) {
    $bu = bunker(true);
    $tid = date("H:i:s d.m.Y", $bu);
    echo <<<HTML
    <p class="feil">Du er i fengsel, gjenstående tid: <span id="fengsel">$bu</span><br>Du er ute: $tid</p>
    <script>
    teller('$bu','bunker',false,'ned');
    </script>
    ';
HTML;
} elseif (fengsel()) {
    $fe = fengsel(true);
    $tid = date("H:i:s d.m.Y", $fe);
    echo <<<HTML
    <p class="feil">Du er i fengsel, gjenstående tid: <span id="fengsel">$fe</span><br>Du er ute: $tid</p>
    <script>
    teller('$fe','bunker',false,'ned');
    </script>
    ';
HTML;
} else {
    startpage("Lotto");
    $now = time();
    $textout = null;
    $s = $db->query("SELECT * FROM lottery ORDER BY `id` DESC LIMIT 0,1");//Henter siste runde, om det er noen.
    if ($db->num_rows() == 0) {//Første runde starter automatisk
        $run = 1;
        $tid = time() + (10 * 60);//10 minutters pause første runden
        $co = $db->query("SELECT * FROM lotteryconfig ORDER BY `id` DESC LIMIT 0,1");//Henter siste config og oppdaterer
        $conf = $db->fetch_object();
        $db->query(
            "INSERT INTO lottery(`round`,`timestamp`,`winnerid`,`timestartstamp`,`pl`,`pr`,`ti`,`al`,`pot`) VALUES('$run','$tid',NULL,'$now','{$conf->Loddpris}','{$conf->Prosent}','{$conf->Tid}','{$conf->Antlodd}',NULL);"
        ) or die(mysqli_error($db->connetion_id));
    } else {//Fortsetter med nåværende runde
        $f = $db->fetch_object($s);
        $run = $f->runde;
        $tid = $f->tid;
        $vinner = $f->vinner;
        $left = $f->tid - time();
        if ($tid < time() && $vinner == null) {
            /*Om tiden er ute og vinneren ikke er trukket enda, så trekkes vinner og neste runde starter automatisk*/
            $co = $db->query(
                "SELECT * FROM lotteryconfig ORDER BY `id` DESC LIMIT 1"
            );//Henter siste config og oppdaterer
            $conf = $db->fetch_object();
            $nytid = time() + ($conf->Tid * 60);//Minutter før trekning settes
            $db->query("SELECT * FROM lotterytickets WHERE `round` = '$run' ORDER BY `id` DESC");
            $allnum = $db->num_rows();
            if ($allnum == 0) {/*Starter runden på nytt*/
                $db->query(
                    "UPDATE lottery SET `timestamp` = '" . (($conf->Tid * 60) + time(
                        )) . "' WHERE `id` = '" . $f->id . "'"
                );
            } else {
                $random = mt_rand(0, ($allnum - 1));
                $db->query(
                    "SELECT * FROM lotterytickets WHERE `round` = '$run' ORDER BY `id` DESC LIMIT " . $random . ", " . $allnum
                );
                $vinner = $db->fetch_object();
                $lotto = $db->query("SELECT * FROM lottery ORDER BY `id` DESC LIMIT 1");
                $lotf = $db->fetch_object();
                $sum = $f->pl * $allnum;
                /*Trekker fra premie som går til eier*/
                $sum2 = ($sum / 100) * $lotf->pr;
                $sum = $sum - $sum2;
                $nyr = $run + 1;
                $db->query(
                    "UPDATE lottery SET `winnerid` = '{$vinner->uid}',`pot` = '$sum',`timestamp` = unix_timestamp() WHERE `round` = '$run' AND `timestamp` = '$tid'"
                );
                if ($db->affected_rows() != 1) {
                    echo '<p>Kunne ikke oppdatere lotto! evt feil: ' . mysqli_error($db->con) . '</p>';
                    die();
                }
                $db->query(
                    "UPDATE `users` SET `hand` = (`hand` + $sum),`exp` = (`exp` + 5) WHERE `id` = '{$vinner->uid}' LIMIT 1"
                );
                #$db->query("INSERT INTO `sysmail`(`uid`,`time`,`msg`) VALUES ('" . $vinner->uid . "','" . time() . "','" . $db->slash('--<b>Vunnet i lotto!</b><br>Du har vunnet ' . number_format($sum) . 'kr') . "')");
                $db->query(
                    "INSERT INTO lottery(`round`,`timestamp`,`winnerid`,`timestartstamp`,`pl`,`pr`,`ti`,`al`) VALUES('$nyr','$nytid',NULL,'$now','{$conf->Loddpris}','{$conf->Prosent}','{$conf->Tid}','{$conf->Antlodd}')"
                );
                $db->query("UPDATE `firms` SET `bank` = (`bank` + " . $sum2 . ") WHERE `id` = '1'");
                #$db->query("SELECT * FROM `oppuid` WHERE `uid` = '{$vinner->uid}' ORDER BY `oid` DESC LIMIT 1");
                /*if ($db->num_rows() == 1) {
                    $db->query("UPDATE `oppuid` SET `tms` = (`tms` + 1) WHERE `uid` = '{$vinner->uid}' AND `done` = '0' AND `tms` < '50' AND `oid` = '3' LIMIT 1");
                }*/
            }//Trekke vinner end
        }
    }
    $l = $db->query(
        "SELECT COUNT(`id`) AS `egenlodd` FROM lotterytickets WHERE `uid` = '{$obj->id}' AND `round` = '$run'"
    );
    $al = $db->query("SELECT COUNT(`id`) AS `antlodd` FROM `lotterytickets` WHERE `round` = '$run'");
    $f1 = $db->fetch_object($l);
    $f2 = $db->fetch_object($al);
    $antlodd = $f2->antlodd;
    $egenlodd = $f1->egenlodd;
    if (isset($_POST['antloddkj'])) {//Spiller skal kjøpe lodd
        $ant = $db->escape($_POST['antloddkj']);
        if (!is_numeric($ant) || $ant <= 0) {
            $textout .= '<p>Du må kjøpe mer enn �t lodd om gangen! Du kan ikke kjøpe 0 lodd.</p>';
        } else {
            if (($ant + $egenlodd) <= $f->al) {
                if (($ant * $f->pl) <= $obj->hand) {
                    $pris = $ant * $f->pl;
                    if ($egenlodd < $f->al) {
                        $add = "INSERT INTO `lotterytickets`(`uid`,`round`,``) VALUES";
                        for ($i = 1; $i <= $ant; $i++) {
                            if ($i == $ant) {
                                $add .= "('{$obj->id}','{$run}','{$now}');";
                            } else {
                                $add .= "('{$obj->id}','{$run}','{$now}'),";
                            }
                        }
                        if ($db->query($add)) {
                            if ($db->query(
                                "UPDATE `users` SET `hand` = (`hand` - $pris) WHERE `id` = '{$obj->id}' LIMIT 1;"
                            )) {
                                $textout .= '<p class="lykket">Du har kjøpt ' . number_format($ant) . ' lodd!</p>';
                            } else {
                                if ($obj->status == 1) {
                                    echo mysqli_error($db->con);
                                } else {
                                    $textout .= '<p class="feil">Kunne ikke kjøpe lodd! Feil i spørring!</p>';
                                }
                            }
                        } else {
                            if ($obj->status == 1) {
                                echo mysqli_error($db->con);
                            } else {
                                $textout .= '<p class="feil">Kunne ikke kjøpe lodd! Feil i spørring!</p>';
                            }
                        }
                    } else {
                        if ($egenlodd >= $f->al) {
                            $textout .= '<p class="feil">Du har allerede kjøpt maks antall lodd!</p>';
                        } else {
                            $textout .= '<p>Du har for mange lodd! o.0</p>';
                        }
                    }
                } else {
                    $textout .= '<p class="feil">Du har ikke råd til å kjøpe flere lodd, sjekk at du har penger ute.</p>';
                }
            } else {
                $textout .= '<p class="feil">Du kan ikke kjøpe så mange lodd!</p>';
            }
        }
    }
    $ver = firma(1);
    if (!is_array($ver)) {
        $Navn = "Lottoselskapet AS";
        $Eier = 1;
    } else {
        /*
        $ver->array(NavnPåFirma,EierAvFirma,TypeFirma);
        */
        $Navn = $ver[0];
        $Eier = $ver[1];
        $r = $db->query("SELECT * FROM `users` WHERE `id` = '$Eier'");
        $uf = $db->fetch_object();
        if ($db->num_rows() == 0) {
            unset($uf);
            $uf = array();
            $uf['user'] = "Werzaire";
        }
    }
    $lotto = $db->query("SELECT * FROM lottery ORDER BY `id` DESC LIMIT 1");
    $lotf = $db->fetch_object();
    ?>
    <h1>Lotto<?php
        if ($obj->status == 1) {
            echo '<sub><a href="?contestants">Vis deltagere!(adminfunksjon)</a></sub>';
        } ?></h1>
    <?php
    if ($uf->id == $obj->id || $obj->status == 1) {
        echo '<h4><a href="Firmaer">Vis firmapanel</a></h4>';
    }
    if (isset($_GET['contestants']) && $obj->status == 1) {
        $l = $db->query(
            "SELECT `uid`,COUNT(*) AS `antlodd` FROM `lotterytickets` WHERE `round` = '$run' GROUP BY `uid` ORDER BY COUNT(*) DESC"
        );
        echo '<table class="table" style="width:300px;"><tr><th colspan="2">Loddoversikt:</th></tr>';
        while ($r = mysqli_fetch_object($l)) {
            echo '<tr>
      <td>' . status($r->uid, 1) . '</td><td>' . $r->antlodd . ' kjøpte lodd</td>
      </tr>';
        }
        echo '</table>';
    }
    echo $textout; ?>
    <form method="post" action="">
        <table class="table2" style="width:300px;">
            <tr>
                <th style="height:35px;color:#999;background:#222;" colspan="2">
                    Runde <?= $run; ?></th>
            </tr>
            <tr>
                <th>Lottoen eies av:</th>
                <td><?php
                    echo status($uf->user); ?></td>
            </tr>
            <tr>
                <th>Navn på firma:</th>
                <td><?= $Navn; ?></td>
            </tr>
            <tr>
                <th colspan="2">Tid igjen:
                    <span id="lottoleft"></span>
                    <script>
                        teller(<?php if (empty($left)) {
                            echo($conf->Tid * 60);
                        } else {
                            echo $left;
                        }?>, 'lottoleft', true, 'ned');
                    </script>
                </th>
            </tr>
            <tr>
                <th>Antall lodd:</th>
                <td><?= $antlodd ?></td>
            </tr>
            <tr>
                <th>Dine lodd:</th>
                <td><?= $egenlodd ?></td>
            </tr>
            <tr>
                <th>Pris per lodd:</th>
                <td><?php
                    echo number_format($f->pl); ?>kr
                </td>
            </tr>
            <tr>
                <th>Maks lodd per bruker:</th>
                <td><?= $f->al; ?></td>
            </tr>
            <tr>
                <th>Pot:</th>
                <td><?php
                    echo number_format($antlodd * $f->pl); ?> kr
                </td>
            </tr>
            <tr>
                <th>Trekkes fra til eier:</th>
                <td><?= $lotf->pr; ?> %</td>
            </tr>
            <tr>
                <td colspan="2"><input class="" type="submit" value="Kjøp lodd"> <input
                        style="-webkit-appearance: button;
-webkit-padding-end: 20px;border: 1px solid #AAA;color: #555;font-size: inherit;width: 32px;height: 20px;background-color: #aaa;"
                        type="text" name="antloddkj" value="1" min="1" max="<?= $max ?>"
                        style="width:50px;"></td>
                <br>
            </tr>
        </table>
    </form>
    <table class="table" style="width:75%;">
        <tr>
            <th colspan="3">Vinnere</th>
        </tr>
        <tr>
            <td>Vinner</td>
            <td>Tidspunkt</td>
            <td>Premie</td>
        </tr>
        <?php
        $getll = $db->query(
            "SELECT * FROM lottery WHERE `winnerid` is not null AND `winnerid` > 0 ORDER BY `id` DESC LIMIT 10"
        );
        while ($r = mysqli_fetch_object($getll)) {
            echo '
<tr>
<td>#' . $r->id . ' ' . user($r->vinner) . '</td><td>' . date(
                    "H:i:s d.m.Y",
                    $r->tid
                ) . '</td><td>' . number_format($r->premie) . 'Kr</td>
</tr>
';
        }
        ?>
    </table>
    <?php
}
endpage();

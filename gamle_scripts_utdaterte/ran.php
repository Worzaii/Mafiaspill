<?php
include("core.php");
startpage("Ran");
if (fengsel() == true) {
    echo '
<p class="feil">Du er i fengsel, gjenst&aring;ende tid: <span id="krim">'.fengsel(true).'</span></p>
<script type= "text/javascript">
teller('.fengsel(true).',\'krim\',true,\'ned\');
</script>
';
} else if (bunker() == true) {
    $bu = bunker(true);
    echo '
<p class="feil">Du er i bunker, gjenst&aring;ende tid: <span id="bunker">' . $bu . '</span><br>Du er ute kl. ' . date("H:i:s d.m.Y", $bu) . '</p>
<script>
teller('.($bu - time()).',\'bunker\',false,\'ned\');
</script>
';
} else { //scripte....
    $utstyr = array(
      1=>array('name' => "USP", 'pris' => 1000000, 'type' => 1, 'power' => 2),
      2=>array('name' => "UMP", 'pris' => 3000000, 'type' => 1, 'power' => 10),
      3=>array('name' => "M4A4", 'pris' => 5000000, 'type' => 1, 'power' => 15),
      4=>array('name' => "AK47", 'pris' => 6500000, 'type' => 1, 'power' => 20),
      5=>array('name' => "Opel Corsa", 'pris' => 1000000, 'type' => 2, 'power' => 2),
      6=>array('name' => "Aston Martin DB9", 'pris' => 3500000, 'type' => 2, 'power' => 10),
      7=>array('name' => "Ford Escort", 'pris' => 4000000, 'type' => 2, 'power' => 15),
      8=>array('name' => "Kinaputter", 'pris' => 100000, 'type' => 3, 'power' => 2),
      9=>array('name' => "TNT", 'pris' => 2500000, 'type' => 3, 'power' => 10),
      10=>array('name' => "C4", 'pris' => 3250000, 'type' => 3, 'power' => 15)
    );


    $g = $db->query("SELECT * FROM `ran` WHERE (`leder` = '$obj->id' OR `spiller1` = '$obj->id' OR `spiller2` = '$obj->id' OR `spiller3` = '$obj->id' OR `spiller4` = '$obj->id') AND `utfort` = '0' AND `active` = '1'");
    if ($db->num_rows($g) >= 1) {
        $a = $db->fetch_row();
        if ($a['leder'] == $obj->id) {
            $leder = true;
        } else {
            $leder = false;
            for ($i = 1; $i <= 4; $i++) {
                if ($a["spiller$i"] == $obj->id) {
                    $spiller = $i;
                    break;
                }
            }
            if (!isset($spiller)) { // error skal aldri skje
                die('ERR');
            }
        }

        if ($leder || $a["spillervapen$spiller"] != 0) { // har kj&oslash;pt eller er leder
            if ($_POST['forlat']) {
                if ($leder) {
                    $db->query("UPDATE `ran` SET `active` = '0' WHERE `id` = '{$a['id']}'");
                    $db->query("UPDATE `raninv` SET `active` = '0' WHERE `ranid` = '{$a['id']}'");
                    echo lykket('Du har forlatt ranet, alle som var med mistet utstyret sitt!</br>Oppdater siden for &aring; komme deg bort.');
                } else {
                    $db->query("UPDATE `ran` SET `spiller$spiller` = '0',`spillervapen$spiller` = '0' WHERE `id` = '{$a['id']}'");
                    echo lykket('Du har forlatt ranet! <a href="/Ran">Klikk her for &aring; g&aring; tilbake til Ran.</a>');
                }
            }
            if (isset($_GET['kast']) && (isset($_GET['s1']) || isset($_GET['s2']) || isset($_GET['s3']) || isset($_GET['s4'])) && $_GET['kast'] != 0) {
                $id = $_GET['kast'];
                $inq = (isset($_GET['s1'])) ? 1 : ((isset($_GET['s2'])) ? 2 : ((isset($_GET['s3'])) ? 3 : (isset($_GET['s4']) ? 4 : 0)));
                if ($inq == 0) {
                    echo feil('Det var en feil i valg av bruker som skulle kastes ut!');
                }
                $db->query("SELECT * FROM `ran` WHERE `id` = '$a->id' AND ``");
            }
            ?>
      <form action="" method="POST">
        <table style="width:585px;" class="table">
          <tr>
            <th colspan="2">Status for ranet</th>
          </tr>
          <tr>
            <td>Spiller</td><td>Utstyr</td>
          </tr>
            <?php
            $rand = mt_rand(0, 100);
            echo '
            <tr><td>'.user($a['leder']).'</td><td><strong>Leder</strong></td></tr>
            <tr>
              <td>'.($a['spiller1'] == 0 ? 'Venter p&aring; spiller..' : user($a['spiller1'])).'<a href="Ran?kast='.$a['spiller1'].'&s1">Kast ut!</a></td>
              <td>'.($a['spillervapen1'] == 0 ? 'Har ikke kj&oslash;pt' : $utstyr[$a['spillervapen1']]['name']).'</td>
            </tr>
            <tr>
              <td>'.($a['spiller2'] == 0 ? 'Venter p&aring; spiller..' : user($a['spiller2'])).'<a href="Ran?kast='.$a['spiller2'].'&s2">Kast ut!</a></td>
              <td>'.($a['spillervapen2'] == 0 ? 'Har ikke kj&oslash;pt' : $utstyr[$a['spillervapen2']]['name']).'</td>
            </tr>
            <tr>
              <td>'.($a['spiller3'] == 0 ? 'Venter p&aring; spiller..' : user($a['spiller3'])).'<a href="Ran?kast='.$a['spiller3'].'&s3">Kast ut!</a></td>
              <td>'.($a['spillervapen3'] == 0 ? 'Har ikke kj&oslash;pt' : $utstyr[$a['spillervapen3']]['name']).'</td>
            </tr>
            <tr>
              <td>'.($a['spiller4'] == 0 ? 'Venter p&aring; spiller..' : user($a['spiller4'])).'<a href="Ran?kast='.$a['spiller4'].'&s4">Kast ut!</a></td>
              <td>'.($a['spillervapen4'] == 0 ? 'Har ikke kj&oslash;pt' : $utstyr[$a['spillervapen4']]['name']).'</td>
            </tr>';
            ?>          
        </table>
          <?php if ($leder) {
              echo '<input type="submit" name="fullfor" value="Fullf&oslash;r ranet!">';
          } ?>
        <input type="submit" name="forlat" onclick="return confirm('Er du sikker p&aring; at du vil forlate ranet?')" value="Forlat Ranet!">
      </form>
            <?php
            if ($leder) { // Om leder.. kan invitere og fullf&oslash;re ranet.
                ?>
</br>
<form action="" method="POST">
    <input type="text" name="invite">
    <input type="submit" value="Inviter spiller!">
</form>
                <?php
                if ($_POST['fullfor']) {
                    $allespillere = true;
                    $allevapen = true;
                    $types = array();
                    for ($i = 1; $i <= 4; $i++) {
                        if ($a["spiller$i"] == 0) {
                            $allespillere = false;
                        }
                        if ($a["spillervapen$i"] == 0) {
                            $allevapen = false;
                        } else {
                            $types[$utstyr[$a["spillervapen$i"]]['type']] = 1;
                        }
                    }
                    if ($allespillere == false) {
                        echo feil('Du mangler fortsatt noen til ranet.');
                    } else if ($allevapen == false) {
                        echo feil('Ikke alle har utstyr enn&aring;.');
                    }
                    if ($allespillere && $allevapen) {// utf&oslash;r ranet
                        $rand = mt_rand(0, 100);
                        if (count($types) != 3) {
                            $rand -= 50;
                        }
                        if (count($types) == 3) {
                            $rand + $a['power'];
                        }
                        //          echo count($types);
                        if ($rand <= 50) {
                            $bel = 0;
                            $timeleft = time() + 900;
                            echo feil('Ranet var ikke vellykket! Dere ble tatt av purken og sitter n&aring; i fengsel!');
                            /*Kaster inn alle som var med i ranet i fengsel i �n query*/
                            $db->query("INSERT INTO `jail`(`uid`,`reason`,`time`,`timeleft`,`prisut`) VALUES('{$a['leder']}','Mislykket ran!','" . time() . "','$timeleft',15000000),('{$a['spiller1']}','Mislykket ran!','" . time() . "','$timeleft',15000000),('{$a['spiller2']}','Mislykket ran!','" . time() . "','$timeleft',15000000),('{$a['spiller3']}','Mislykket ran!','" . time() . "','$timeleft',15000000),('{$a['spiller4']}','Mislykket ran!','" . time() . "','$timeleft',15000000)");
                            sysmel(array($a['spiller1'], $a['spiller2'], $a['spiller3'], $a['spiller4']), 'Ran -- Mislykket ran!<br>Dere gjorde alt dere kunne, men klarte det ikke, dere ble satt i fengsel i 15 minutter!');
                        } else if ($rand >= 50) {
                            $bel = mt_rand(5000000, 55000000);
                            $lykket = array(
                                1 => "Dere plaffet ned alle dere s&aring;, og fikk med dere",
                                2 => "Dere m&oslash;tte noen hindringer, men klarte &aring; skremme alle nok til &aring; ikke ringe purken!</br>Dere fikk med dere",
                                3 => "Moren din ringte mens du var i ranet. Heldigvis hadde du handsfree og m&oslash;tte ingen flere hindringer!</br>Dere fikk med dere",
                                4 => "Daglig leder hadde glemt &aring; l&aring;se safen. Dette var lett!</br>Dere fikk med dere"
                            );
                            echo lykket('' . $lykket[array_rand($lykket)] . ' ' . number_format($bel) . 'kr!');
                            $db->query("UPDATE `users` SET `hand` = (`hand` + $bel),`exp` = (`exp` + 5) WHERE `id` = '{$a['leder']}' LIMIT 1");
                            $db->query("UPDATE `users` SET `exp` = (`exp` + 7.5) WHERE `id` IN(" . $a['spiller1'] . "," . $a['spiller2'] . "," . $a['spiller3'] . "," . $a['spiller4'] . ")");/*Gir de som var med p&aring; ranet exp.*/
                            sysmel(array($a['spiller1'], $a['spiller2'], $a['spiller3'], $a['spiller4']), 'Ran -- Vellykket ran!<br>Dere fikk med dere <b>' . number_format() . '</b>kr! Du fikk ogs&aring; litt XP!');
                        }
                        $db->query("UPDATE `ran` SET `utfort` = '1',`fortjeneste` = '$bel' WHERE `id` = '{$a['id']}'"); // Fuck yeah
                        $db->query("UPDATE `raninv` SET `active` = '0' WHERE `ranid` = '{$a['id']}'");
                    }
                } else if ($_POST['invite']) {
                    $y = $db->fetch_object();
                    $idd = strtolower($db->escape($_POST['invite']));
                    $afs = $db->query("SELECT * FROM `raninv` WHERE `ranid` = '{$a['id']}' AND `to` = '$idd' AND `active` = '1'");
                    if ($db->num_rows() >= 1) {
                        echo feil('Spilleren ' . $idd . ' er allerede invitert.');
                    } else if ($idd == strtolower($obj->user)) {
                        echo feil('Du kan ikke invitere deg selv.');
                    } else if (!user_exists($idd)) {
                        echo feil('Brukeren eksisterer ikke!');
                    } else {
                        $db->query("INSERT INTO `raninv` (`from`,`to`,`city`,`time`,`ranid`,`active`) VALUES ('$obj->user','$idd','$obj->city','" . time() . "','{$a['id']}','1')");
                        echo lykket('Spilleren ' . $idd . ' ble invitert.');
                    }
                }
            }
        } else { // ikke kj&oslash;pt ... heller ikke leder
            if (isset($_POST['submitteren'])) {// kj&oslash;per utstyr...
                $id = intval($_POST['utstyrvelg']);
                if (isset($utstyr[$id]['name'])) {
                    if ($obj->hand < $utstyr[$id]['pris']) {
                        echo feil('Du har ikke r&aring;d til dette!');
                    } else {
                        $db->query("UPDATE `ran` SET `spillervapen$spiller` = '$id'");
                        $db->query("UPDATE `users` SET `hand` = (`hand` - '" . $utstyr[$id]['pris'] . "') WHERE `id` = '$obj->id' LIMIT 1");
                        header("Location: ran.php?asds");
                    }
                }
            }

            echo lykket('Du har et ran ventende p&aring; deg, eller du har ikke valgt utstyr!');
            ?>
<form action="" method="POST">
  <table style="text-align:center;" class="table">
    <tr>
      <th>Velg utstyr</th>
      <th>Pris</th>
      <th>Kj&oslash;p</th>
    </tr>
      <?php
      foreach ($utstyr as $iden => $daten) {
          echo '<tr><td>' . $daten['name'] . '</td><td>' . number_format($daten['pris']) . '</td><td><input type="radio" name="utstyrvelg" value="' . $iden . '"></td></tr>' . "\n";
      }
      ?>
    <tr><td><input type="submit" name="submitteren" value="Kj&oslash;p Utstyr!"></td></tr>
  </table>
</form>
            <?php
        }
    } else {
        if ($_POST['ransid']) {
            $id = (!is_numeric($_POST['ransid'])) ? 0 : $db->escape($_POST['ransid']);
            if ($id >= 1) {
                $kla = $db->query("SELECT * FROM `raninv` WHERE `id` = '$id' AND `to` = '$obj->user' AND `active` = '1'");
                if ($db->num_rows($kla) >= 1) {
                    $jkl = $db->fetch_object($kla);
                    $db->query("SELECT * FROM `ran` WHERE `id` = '$jkl->ranid' AND `active` = '1'");
                    if ($db->num_rows() == 1) {
                        $b = $db->fetch_row();
                        if ($obj->city == $b['by']) {
                            $use = 0;
                            for ($i = 1; $i <= 4; $i++) {
                                if ($b["spiller$i"] == 0) {
                                    $use = $i;
                                    break;
                                }
                            }
                            if ($use == 0) {
                                echo feil('Ranet er fullt!');
                            } else {
                                $db->query("UPDATE `ran` SET `spiller$use` = '$obj->id' WHERE `id` = '$jkl->ranid' LIMIT 1");
                                $db->query("UPDATE `raninv` SET `active` = '0' WHERE `id` = '$jkl->id' LIMIT 1");
                                header("Location: /Ran");
                            }
                        } else {
                            echo feil('Du m&aring; v&aelig;re i samme by som ranet ble startet!<br>Ranet befinner seg i ' . city($b['by'], 1) . ', mens du er i ' . city($obj->city, 1) . '.');
                        }
                    } else {
                        echo feil('Beklager, men det ser ut som ranet var utf&oslash;rt f&oslash;r du kunne bli med!');
                    }
                } else {
                    echo feil('Finner ikke invitasjonen din! Om du mener dette er feil, ta kontakt med <a href="support.php">Support!</a>');
                }
            } else {
                echo feil('Id stemmer ikke!');
            }
        }
        if (isset($_GET['ran'])) { // Starter ett nytt ran ...
            $id = $db->escape($_GET['ran']);
            if ($id == 1) {
                if ($obj->exp < 100) {
                    echo feil('Du m&aring; v&aelig;re Underboss eller h&oslash;yere for &aring; starte dette ranet!');
                } else {
                    if ($obj->hand >= 1000000) {
                        $db->query("UPDATE `users` SET `hand` = (`hand` - 1000000) WHERE `id` = '" . $obj->id . "' LIMIT 1");
                        $db->query("INSERT INTO `mafia_no_net`.`ran` (`id`, `leder`, `spiller1`, `spiller2`, `spiller3`, `spiller4`, `time`, `active`, `spillervapen1`, `spillervapen2`, `spillervapen3`, `spillervapen4`, `by`, `utfort`, `type`,`fortjeneste`) VALUES (NULL, '$obj->id', '0', '0', '0', '0', UNIX_TIMESTAMP(), '1', '0', '0', '0', '0', '$obj->city', '0', '1','0');") or die(mysqli_error($db->con));
                        header("Location: /Ran");
                    }
                }
            }
            if ($id == 2) {
                if ($obj->exp < 350) {
                    echo feil('Du m&aring; v&aelig;re Don eller h&oslash;yere for &aring; starte dette ranet!');
                } else {
                    if ($obj->hand >= 4000000) {
                        $db->query("UPDATE `users` SET `hand` = (`hand` - 4000000) WHERE `id` = '" . $obj->id . "' LIMIT 1");
                        $db->query("INSERT INTO `mafia_no_net`.`ran` (`id`, `leder`, `spiller1`, `spiller2`, `spiller3`, `spiller4`, `time`, `active`, `spillervapen1`, `spillervapen2`, `spillervapen3`, `spillervapen4`, `by`, `utfort`, `type`,`fortjeneste`) VALUES (NULL, '$obj->id', '0', '0', '0', '0', UNIX_TIMESTAMP(), '1', '0', '0', '0', '0', '$obj->city', '0', '2','0');") or die(mysqli_error($db->con));
                        header("Location: /Ran");
                    }
                }
            }
        }
        $adsfas = $db->query("SELECT * FROM `raninv` WHERE `to` = '$obj->user' AND `active` = '1'");
        ?>
<form>
  <table style="width: 330px;"class="table">
    <tr>
      <th colspan="3">Velg type ran</th>
    </tr>
    <td>Type ran</td><td>Rank krav</td><td>Startpris</td>
    <tr><td><a href="/Ran?ran=1" onclick="return confirm('Ved &aring; starte dette ranet vil du bruke 1,000,000kr p&aring; &aring; starte planleggingen, godta?');">Ran den lokale 7-11</a></td><td>Underboss</td><td>1,000,000kr</td></tr>
    <tr><td><a href="/Ran?ran=2" onclick="return confirm('Ved &aring; starte dette ranet vil du bruke 4,000,000kr p&aring; &aring; starte planleggingen, godta?');">Ran NOKAS</a></td><td>Don</td><td>4,000,000kr</td></tr>
  </table>
</form>
<!-- Invitasjoner starter her-->
</br>
<form action="" method="POST">
  <table style="width:210px;" class="table">
    <tr>
      <th colspan="3">Dine invitasjoner</th>
    </tr>
    <tr>
      <td>Fra</td><td>By</td><td>Velg</td>
    </tr>
      <?php
      if ($db->num_rows($adsfas) >= 1) {
          while ($r = mysqli_fetch_object($adsfas)) {
              echo '<tr><td>' . $r->from . '</td><td>' . city($r->city) . '</td><td><input type="radio" name="ransid" value="' . $r->id . '"></td></tr>';
          }
      } else {
          echo '<tr><td colspan="3" style="text-align:center;">Du er ikke invitert til ran</td></tr>';
      }
      ?>
  </table>
  </br><input style="margin-left:250px;margin-right:250px;" type="submit" name="velg" value="Velg ranet!">
</form>
        <?php
    }
}
endpage();
?>
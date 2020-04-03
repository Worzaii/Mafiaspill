<?php
/**
 * TODO: Make the new crime script working again, but better!
 */
include("core.php");
startpage("Kriminalitet");
echo '<img alt src="images/headers/krim.png"><p>N&aring;r du f&oslash;rst starter med kriminalitet,
 s&aring; vil du kun ha et valg. Ettersom du kommer opp i rank, s&aring; vil nye valg l&aring;ses opp. 
 Hvis du ikke ser noen valg, kontakt support!</p>';
if (!(fengsel() || bunker())) {
    /* Continues crime script, elsewise do jail, then bunker if applicable */
    $q = $db->prepare("SELECT count(*) FROM `krimlogg` WHERE `uid` = ? AND `timewait` > UNIX_TIMESTAMP() ORDER BY `timewait` DESC LIMIT 0,1");
    $q->execute([$obj->id]);
    if ($q->fetchColumn() == 1) {
        /* Crime still on cooldown! */
        $q2 = $db->prepare("SELECT timewait FROM `krimlogg` WHERE `uid` = ? AND `timewait` > UNIX_TIMESTAMP() ORDER BY `timewait` DESC LIMIT 0,1");
        $q2->execute([$obj->id]);
        $f = $q2->fetchColumn();
        echo '
        <p class="feil">Du m&aring; vente <span id="krim">' . ($f - time()) . '</span> f&oslash;r neste krim.</p>
        <script>
        teller(' . ($f - time()) . ', "krim", false, "ned");
        </script>
        ';
    } elseif (isset($_POST['valget'])) {
        /* Starting execution of crime */
        $valg = $_POST['valget'];
        if (!is_numeric($valg)) {
            echo feil("Valg ikke godkjent! Pr&oslash;v p&aring; nytt! <a href=\"" . $_SERVER['PHP_SELF'] . "\">Kriminalitet</a>");
        } else {
            $exists = $db->prepare("select count(*) from crime where id = ?");
            $exists->execute([$valg]);
            if ($exists->fetchColumn() == 1) { /* Option exists, continuing crime*/
                $crime = $db->prepare("select * from crime where id = ?");
                $crime->execute([$valg]);
                $info = $crime->fetchObject();
                $chance = $db->prepare("select * from chance where uid = ? and `option` = ? and type = '1'");
                $chance->execute([$obj->id, $valg]);
                $thechance = $chance->fetchObject();
                if ($thechance->chance >= 74) {
                    error_log(var_export($thechance,true));
                    $ran2 = rand(10, 46);
                    $db->query("UPDATE `chance` SET `chance` = (`chance` - $ran2) WHERE `uid` = '" . $obj->id . "' AND `option` = '" . $thechance->option . "' LIMIT 1");
                    $thechance->chance -= $ran2;
                } elseif ($thechance->chance <= 73) {
                    $ran2 = rand(1, 3);
                    $db->query("UPDATE `chance` SET `chance` = (`chance` + $ran2) WHERE `uid` = '$obj->id' AND `option` = '$thechance->option' AND `chance` < '80'");
                    $thechance->chance += $ran2;
                }
                $kr = mt_rand($info->minval, $info->maxval);
                $timewait = $info->untilnext + time();
                if (mt_rand(0, 100) <= $thechance->chance) {
                    if ($db->query("UPDATE `users` SET `hand` = (`hand` + $kr),`exp` = (`exp` + $info->expgain) WHERE `user`= '$obj->user' LIMIT 1")) {
                        if ($db->query("INSERT INTO `krimlogg`(`uid`,`timestamp`,`crime`,`result`,`timewait`) VALUES('{$obj->id}',UNIX_TIMESTAMP(),'{$info->id}','$kr','$timewait')")) {
                            $time = $timewait - time();
                            echo '
                                <p class="lykket">Du var heldig og fikk ' . number_format($kr) . 'kr med deg!</p>
                                <p class="feil">Tid til neste krim: <span id="krim">' . $info->untilnext . '</span>.</p>
                                <script>
                                teller(' . $info->untilnext . ', "krim", false, \'ned\');
                                </script>
                                ';
                        } else {
                            if (r1()) {
                                echo '
                <p>Feil i sp&oslash;rring2: ' . mysqli_error($db->con) . '</p>
                ';
                            } else {
                                echo '<p>Det var feil i utf&oslash;relse av sp&oslash;rringer, vennligst rapporter dette til support, slik at de kan se i loggen hva som hendte!</p>';
                            }
                        }
                    } else {
                        if (r1()) {
                            echo '
              <p>Feil i sp&oslash;rring: ' . mysqli_error($db->con) . '</p>
              ';
                        } else {
                            echo '
              <p>Det var feil i utf&oslash;relse av sp&oslash;rringer, vennligst rapporter dette til support!</p>
              ';
                        }
                    }
                } else {
                    if ($db->query("INSERT INTO `krimlogg`(`uid`,`timestamp`,`crime`,`result`,`timewait`) VALUES('$obj->id',UNIX_TIMESTAMP(),'$valg','0','$timewait')")) {
                        $fen = rand(0, 1);
                        if ($fen == 1) {
                            echo '
              <p class="feil">Du klarte det ikke!</p>
              <p class="feil">Tid til neste krim: <span id="krim">' . $info->untilnext . '</span>.</p>
              <script>
              teller(' . $info->untilnext . ', "krim", false, \'ned\');
              </script>
              ';
                        } else {
                            $time = time();
                            $time2 = time() + $timewait;
                            $punish = $time + $info->punishtime;
                            $q = $db->query("INSERT INTO `jail`(`uid`,`reason`,`timestamp`,`timeleft`,`priceout`) VALUES('$obj->id','Pr&oslash;vde &aring; v&aelig;re litt kriminiminel.',UNIX_TIMESTAMP(),'$punish',2500000)");
                            echo feil('Du klarte det ikke, og politiet oppdaget deg!</p>');
                            if ($q->rowCount() == 1) {
                                echo '
                <p class="feil">Du ble satt i fengsel! <br>Gjenst&aring;ende tid: <span id="krim2">' . ($info->punishtime) . '</span>.</p>
                <script>
                teller(' . $info->punishtime . ', "krim2", false, \'ned\');
                </script>
                ';
                                $jailed = true;
                            } else {
                                echo feil('Klarte ikke &aring; sette deg i fengsel! S&aring; bra...');
                            }
                        }
                    }
                }
            }
        }
    } else {
        echo lykket("Du er klar til &aring; utf&oslash;re kriminalitet!");
        $get_actions = $db->prepare("select * from crime where levelmin <= ? ORDER BY `levelmin` DESC,`id` DESC");
        $get_actions->execute([rank($obj->exp)[0]]);
        ?>
        <form name="krim"
              method="post"
              id="krim"
              action="">
            <table style="width:590px;"
                   class="table">
                <tr>
                    <th colspan="5">Krimhandlinger</th>
                </tr>
                <tr>
                    <td style="width:250px;"><b>Handling</b></td>
                    <td><b>Fortjeneste</b></td>
                    <td><b>Ventetid</b></td>
                    <td><b>Sjanse</b></td>
                    <td><b>Straff</b></td>
                </tr>
                <?php
                while ($r = $get_actions->fetchObject()) {
                $sql2 = $db->prepare("SELECT count(*) FROM `chance` WHERE `uid` = ? AND `type` = '1' AND `option` = ?");
                $sql2->execute([$obj->id, $r->id]);
                if ($sql2->fetchColumn() >= 1) {
                    $sql3 = $db->prepare("SELECT * FROM `chance` WHERE `uid` = ? AND `type` = '1' AND `option` = ?");
                    $sql3->execute([$obj->id, $r->id]);
                    $res = $sql3->fetchObject();
                    $sjanse = $res->chance . '%';
                } else {
                    $new_chance = $db->prepare("INSERT INTO `chance`(`uid`,`type`,`option`) VALUES(?,'1',?)");
                    $new_chance->execute([$obj->id, $r->id]);
                    $sjanse = "0%";
                }
                echo '<tr class="valg" onclick="sendpost(' . $r->id . ')">
<td>' . htmlentities($r->description, ENT_NOQUOTES | ENT_HTML401,
                        "UTF-8") . '</td><td>' . number_format($r->minval) . '-' . number_format($r->maxval) . 'kr</td><td>' . $r->untilnext . ' sekunder</td><td>' . $sjanse . '</td><td>' . $r->punishtime . ' sekunder</td>
</tr>
';
                ?>
            </table>
            <input type="hidden"
                   value=""
                   name="valget"
                   id="valget">
        </form>
        <script>
          function sendpost(valg) {
            $('#valget').val(valg);
            $('#krim').submit();
          }

          $(document).ready(function() {
            $('.valg').hover(function() {
              $(this).removeClass().addClass('c_2').css('cursor', 'pointer');
            }, function() {
              $(this).removeClass().css('cursor', 'pointer');
            });
          });
        </script>
        <?php
    }
    }
} else {
    if (bunker()) {
        echo feil('Du er i bunker, gjenst&aring;ende tid: <span id="nedteller"></span>');
        $remaining_time = time() - bunker(true);
    } elseif (fengsel()) {
        echo feil('Du er i fengsel, gjenst&aring;ende tid: <span id="nedteller"></span>');
    }
    ?>
    <script>teller(<?=fengsel(true)?>, 'nedteller', false, 'ned');</script>
    <?php
}
endpage();

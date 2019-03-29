<?php
include("core.php");
startpage("Kriminalitet");
echo '<img alt src="./imgs/krim.png"><p>N&aring;r du f&oslash;rst starter med kriminalitet, s&aring; vil du kun ha et valg. Ettersom du kommer opp i rank, s&aring; vil nye valg l&aring;ses opp. Hvis du ikke ser noen valg, kontakt support!</p>';
$jailed = false;
if (fengsel() == true) {
    $bu = fengsel(true);
    echo '
    <p class="feil">Du er i fengsel, gjenst&aring;ende tid: <span id="fengsel">'.$bu.'</span><br>Du er ute kl. '.date("H:i:s d.m.Y",
        (time() + $bu)).'</p>
    <script>
    teller('.$bu.',\'fengsel\',false,\'ned\');
    </script>
    ';
} else if (bunker()) {
    $bu = bunker(true);
    echo '
    <p class="feil">Du er i bunker, gjenst&aring;ende tid: <span id="bunker">'.$bu.'</span><br>Du er ute kl. '.date("H:i:s d.m.Y",
        $bu).'</p>
    <script>
    teller('.($bu - time()).',\'bunker\',false,\'ned\');
    </script>
    ';
} else {
    $q = $db->query("SELECT * FROM `krimlogg` WHERE `uid` = '$obj->id' AND `timewait` > UNIX_TIMESTAMP() ORDER BY `timewait` DESC LIMIT 0,1");
    if (!$q) {
        if (r1()) {
            echo '
        <p class="feil">Tidssp&oslash;rring kunne ikke utf&oslash;res: '.mysqli_error($db->connection_id).'</p>
        ';
        } else {
            echo '
        <p class="feil">Det har oppst&aring;tt en feil, vennligst kontakt Admin via Support for &aring; f&aring; rettet p&aring; dette!</p>
        ';
        }
    } else if ($db->num_rows() == 1) {
        /* There's recently been done something: */
        $f = $db->fetch_object($q);
        if (time() < $f->timewait) {
            echo '
        <p class="feil">Du m&aring; vente <span id="krim">'.($f->timewait - time()).'</span> f&oslash;r neste krim.</p>
        <script>
        teller('.($f->timewait - time()).',\'krim\',true,\'ned\');
        </script>
        ';
        }
    } else if ($db->num_rows() == 0) {
        if (isset($_POST['valget'])) {
            if (empty($_POST['valget'])) {
                echo '<p class="feil">Du m&aring; velge et alternativ f&oslash;rst!</p>';
            } else {
                $val  = $db->escape($_POST['valget']);
                $valg = $db->query("SELECT * FROM `krim` WHERE `id` = '$val' LIMIT 0,1");
                if (!$valg) {
                    if (r1()) {
                        echo '
      <p>Feil i sp&oslash;rring1: '.mysqli_error($db->connection_id).'</p>
      ';
                    } else {
                        echo '
        <p>Det var feil i utf&oslash;relse av sp&oslash;rringer, vennligst rapporter dette til en Admin!</p>
        ';
                    }
                } else {
                    $v  = $db->fetch_object($valg);
                    $sj = $db->query("SELECT * FROM `chance` WHERE `uid` = '".$obj->id."' AND `type` = '1' AND `option` = '".$val."'");
                    if ($db->num_rows() == 0) {
                        $db->query("INSERT INTO `chance`(`uid`,`type`,`option`) VALUES('$obj->id','1','$val')");
                    } else {
                        $vf = $db->fetch_object($sj);
                        if ($vf->chance >= 74) {
                            $ran2       = rand(10, 46);
                            $db->query("UPDATE `chance` SET `chance` = (`chance` - $ran2) WHERE `uid` = '".$obj->id."' AND `option` = '".$vf->option."' LIMIT 1");
                            $vf->chance += $ran2;
                        } else if ($vf->chance <= 73) {
                            $ran2       = rand(1, 3);
                            $db->query("UPDATE `chance` SET `chance` = (`chance` + $ran2) WHERE `uid` = '$obj->id' AND `option` = '$vf->option' AND `chance` < '46'");
                            $vf->chance += $ran2;
                        }
                    }
                    $kr       = mt_rand($v->minval, $v->maxval);
                    $timewait = $v->untilnext + time();
                    $time2    = $timewait - time();
                    if (mt_rand(0, 100) <= $vf->chance) {
                        if ($db->query("UPDATE `users` SET `hand` = (`hand` + $kr),`exp` = (`exp` + $v->expgain) WHERE `user`= '$obj->user' LIMIT 1")) {
                            if ($db->query("INSERT INTO `krimlogg`(`uid`,`timestamp`,`crime`,`result`,`timewait`) VALUES('{$obj->id}',UNIX_TIMESTAMP(),'{$v->id}','$kr','$timewait')")) {
                                /* Removing missions for now... */
                                /* $db->query("SELECT * FROM `oppuid` WHERE `uid` = '{$obj->id}' AND `done` = '0' AND `oid` = '2' ORDER BY `oid` DESC LIMIT 1");
                                  if ($db->num_rows() == 1) {
                                  if ($time - time()) {
                                  $db->query("UPDATE `oppuid` SET `tms` = (`tms` + 1) WHERE `uid` = '{$obj->id}' AND `done` = '0' AND `tms` < '250' AND `oid` = '2' LIMIT 1");
                                  }
                                  } */
                                $time = $timewait - time();
                                echo '
                                <p class="lykket">Du var heldig og fikk '.number_format($kr).'kr med deg!</p>
                                <p class="feil">Tid til neste krim: <span id="krim">'.$time2.'</span>.</p>
                                <script>
                                teller('.$time2.',\'krim\',true,\'ned\');
                                </script>
                                ';
                            } else {
                                if (r1()) {
                                    echo '
                <p>Feil i sp&oslash;rring2: '.mysqli_error($db->connection_id).'</p>
                ';
                                } else {
                                    echo '<p>Det var feil i utf&oslash;relse av sp&oslash;rringer, vennligst rapporter dette til support, slik at de kan se i loggen hva som hendte!</p>';
                                }
                            }
                        } else {
                            if (r1()) {
                                echo '
              <p>Feil i sp&oslash;rring: '.mysqli_error($db->connection_id).'</p>
              ';
                            } else {
                                echo '
              <p>Det var feil i utf&oslash;relse av sp&oslash;rringer, vennligst rapporter dette til support!</p>
              ';
                            }
                        }
                    } else {
                        if ($db->query("INSERT INTO `krimlogg`(`uid`,`timestamp`,`crime`,`result`,`timewait`) VALUES('$obj->id',UNIX_TIMESTAMP(),'$val','0','$timewait')")) {
                            $fen = rand(0, 1);
                            if ($fen == 1) {
                                echo '
              <p class="feil">Du klarte det ikke!</p>
              <p class="feil">Tid til neste krim: <span id="krim">'.$time2.'</span>.</p>
              <script>
              teller('.$time2.',\'krim\',true,\'ned\');
              </script>
              ';
                            } else {
                                $time  = time();
                                $time2 = time() + $timewait;
                                $punish = $time + $v->punishtime;
                                $q     = $db->query("INSERT INTO `jail`(`uid`,`reason`,`timestamp`,`timeleft`,`priceout`) VALUES('$obj->id','Was a bad boy',UNIX_TIMESTAMP(),'$punish',2500000)");
                                echo '
              <p class="feil">Du klarte det ikke, og politiet oppdaget deg!</p>';
                                if ($db->affected_rows() == 1) {
                                    echo '
                <p class="feil">Du ble satt i fengsel! <br>Gjenst&aring;ende tid: <span id="krim2">'.($timewait - time()).'</span>.</p>
                <script>
                teller('.($timewait - time()).',\'krim2\',true,\'ned\');
                </script>
                ';
                                    $jailed = true;
                                } else {
                                    echo '<p class="feil">Klarte ikke &aring; sette deg i fengsel! S&aring; bra...</p>';
                                }
                            }
                        }
                    }
                }
            }
        } else {
            if (!$jailed) {
                ?>
                <form name="krim" method="post" id="krim" action="">
                    <table style="width:590px;" class="table">
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
                        $rank   = rank($obj->exp);
                        $ranknr = $rank[0];
                        $sql    = $db->query("SELECT * FROM `krim` WHERE `levelmin` <= '$ranknr' ORDER BY `levelmin` DESC,`id` DESC");
                        if ($db->num_rows() >= 1) {
                            while ($r = mysqli_fetch_object($sql)) {
                                $sql2 = $db->query("SELECT * FROM `chance` WHERE `uid` = '$obj->id' AND `type` = '1' AND `option` = '$r->id'");
                                if ($db->num_rows() >= 1) {
                                    $get2   = $db->fetch_object();
                                    $sjanse = $get2->chance.'%';
                                } else {
                                    $db->query("INSERT INTO `chance`(`uid`,`type`,`option`) VALUES('$obj->id','1','$r->id')");
                                    $sjanse = "0%";
                                }
                                echo '
              <tr class="valg notactive" onclick="sendpost('.$r->id.')">
              <td>'.htmlentities($r->description, ENT_NOQUOTES | ENT_HTML401, "UTF-8").'</td><td>'.number_format($r->minval).'-'.number_format($r->maxval).'kr</td><td>'.$r->untilnext.' sekunder</td><td>'.$sjanse.'</td><td>'.$r->punishtime.' sekunder</td>
              </tr>
              ';
                            }
                        } else {
                            echo '
            <tr>
            <td colspan="4" style="text-align:center;"><i>Det ser ut til &aring; v&aelig;re tomt for valg... Har du h&oslash;y nok rank til &aring; utf&oslash;re dette da?</i></td>
            </tr>
            ';
                        }
                        ?>
                    </table>
                    <input type="hidden" value="" name="valget" id="valget">
                </form>
                <script language="javascript">
                    function sendpost(valg) {
                        $('#valget').val(valg);
                        $('#krim').submit();
                    }
                    $(document).ready(function () {
                        $('.valg').hover(function () {
                            $(this).removeClass().addClass('c_2').css('cursor', 'pointer');
                        }, function () {
                            $(this).removeClass().addClass('c_1').css('cursor', 'pointer');
                        });
                    });
                </script>
                <?php
            }
        }
    }
}
endpage();

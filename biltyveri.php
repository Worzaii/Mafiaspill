<?php
include("core.php");
include_once("inc/bilconfig.php");
startpage("Biltyveri");
echo '<img src="images/headers/biltyveri.png"><p>Når du først starter med biltyveri, så vil du kun ha et valg. Ettersom du kommer opp i rank, så vil nye valg låses opp.</p>';
if ($write = canUseFunction(1, 1)) {
    echo $write;
} else {
    $q = $db->query("SELECT * FROM `carslog` WHERE `uid` = '$obj->id' AND `timewait` > UNIX_TIMESTAMP() ORDER BY `id` DESC LIMIT 0,1");
    if ($q->num_rows() == 1) {
        $qf = $db->fetch_object($q);
        $left = $qf->timewait - time();
        echo feil('Du m&aring vente <span id="tid"></span> før neste gang!') . '<script>teller(' . $left . ',"tid",false,"ned");</script>';
    } else {
        if (isset($_POST['valget'])) {
            $v = $db->escape($_POST['valget']);
            if (is_numeric($v) && $v >= 1) {
                $s = $db->query("SELECT * FROM `cars` WHERE `id` = '$v' LIMIT 1");
                if ($db->num_rows() == 1) {
                    $se = $db->fetch_object();
                    if ($se->bilmin > $se->bilmax || $se->bilmin < 1) {
                        echo feil('Feil i bilvalg! Kontakt admin for &aring fikse!');
                    } else {
                        $sa = $db->query("SELECT * FROM `chance` WHERE `type` = '2' AND `uid` = '$obj->id' AND `option` = '$v'");
                        if ($db->num_rows() == 1) {
                            $si = $db->fetch_object();
                            if (rand(1, 100) > $si->chance) {
                                if (rand(1, 2) == 1) {
                                    echo feil('Du klarte det ikke!');
                                    if (!$db->query("INSERT INTO `carslog`(`uid`,`timestamp`,`timewait`,`result`,`choice`) VALUES('$obj->id',UNIX_TIMESTAMP(),'" . (time()
                                            + $se->timewait) . "','0','$v')")) {
                                        echo feil('Det var feil med en spørring, og det ble lagret i loggen. 
                                        Varsle Ledelsen om dette snarest, slik at de kan se igjennom det.');
                                    }
                                } else {
                                    echo feil('Du klarte det ikke og havnet i fengselet!');
                                    if (!$db->query("INSERT INTO `carslog`(`uid`,`timewait`,`timestamp`,`result`,`choice`) VALUES('{$obj->id}','" . (time()
                                            + $se->timewait) . "',UNIX_TIMESTAMP(),'2','{$se->id}')")) {
                                        echo feil('Kunne ikke legge inn i loggen! x.x');
                                    }
                                    if ($db->query("INSERT INTO `jail`(`uid`,`reason`,`timestamp`,`timeleft`,`priceout`) VALUES('{$obj->id}','Prøvde &aring stjele bil',UNIX_TIMESTAMP(),'" . (time()
                                            + $se->punishtime) . "', 5000)")) {
                                        echo feil('Tid igjen: <span id="fengsel"></span><script>teller(' . $se->punishtime . ',"fengsel",false,"ned");</script>');
                                    } else {
                                        echo feil('Prøvde å fengsle deg, men klarte det ikke... :7');
                                    }
                                }
                            } else {
                                if (!is_array($cartypes)) {
                                    echo feil('Det er en feil i bilconfig! Vennligst rapporter dette til Admin snarest!');
                                } else {
                                    $whatcar = (rand($se->bilmin, $se->bilmax) - 1); //Velger biler
                                    echo lykket('Du fikk med deg ' . $cartypes[$whatcar]["name"] . ' med en verdi p&aring ' . number_format($cartypes[$whatcar]["price"]) . '!');
                                    if ($db->query("INSERT INTO `garage`(`car_id`,`uid`,`stolen_city`,`current_city`,`timestamp`) VALUES('{$whatcar}','{$obj->id}','{$obj->city}','{$obj->city}',UNIX_TIMESTAMP())")) {
                                        if ($db->query("INSERT INTO `carslog`(`uid`,`timestamp`,`timewait`,
                      `result`,`choice`) 
                      VALUES('$obj->id',UNIX_TIMESTAMP(),(UNIX_TIMESTAMP() + {$se->timewait}),'1','$v')")) {
                                            $db->query("UPDATE `users` 
SET `exp` = (`exp` + {$se->expgain}) 
WHERE `id` = '{$obj->id}' LIMIT 1");
                                        }
                                    }
                                }
                            }
                            if ($si->chance >= 74) {
                                $ran2 = rand(10, 36);
                                $db->query("UPDATE `chance` SET `chance` = (`chance` - $ran2) 
WHERE `uid` = '" . $obj->id . "' AND 
`option` = '" . $si->option . "' LIMIT 1");
                                $si->chance -= $ran2;
                            } else {
                                $ran2 = rand(1, 3);
                                $db->query("UPDATE `chance` 
SET `chance` = (`chance` + $ran2) 
WHERE `uid` = '$obj->id' AND 
      `option` = '$si->option' LIMIT 1");
                                $si->chance += $ran2;
                            }
                        } else {
                            echo feil('Beklager, det kan se ut som det er feil med din sjanse-sjekker. Ta kontakt med admin for å få dette fikset.');
                        }
                    }
                } else {
                    feil('Dette valget eksisterer ikke!');
                }
            } else {
                feil('Det er ikke gyldig valg, da verdien ikke er tall! #Nohacksplease!');
            }
        } else {
            ?>
            <div id="biltyveri">
                <form method="post" id="bil" action="">
                    <table class="table" style="width:590px;">
                        <tr>
                            <th colspan="3">Biltyveri</th>
                        </tr>
                        <tr class="c_3">
                            <td>Oppgave</td>
                            <td>Sjanse</td>
                            <td>Ventetid</td>
                        </tr>
                        <?php
                        $rank = rank($obj->exp);
                        $s = $db->query("SELECT * FROM `cars` WHERE `levelmin` <= '" . $rank[0] . "' 
                        ORDER BY `levelmin` DESC,`id` DESC");
                        if ($db->num_rows() >= 1) {
                            while ($r = mysqli_fetch_object($s)) {
                                $sql2 = $db->query("SELECT * FROM `chance` 
WHERE `uid` = '{$obj->id}' AND 
      `type` = '2' AND 
      `option` = '{$r->id}'");
                                if ($db->num_rows() >= 1) {
                                    $get2 = $db->fetch_object($sql2);
                                } else {
                                    $db->query("INSERT INTO `chance`(`uid`,`type`,`option`) 
VALUES('$obj->id','2','$r->id')");
                                }
                                echo '
            <tr class="valg" onclick="sendpost(' . $r->id . ')">
            <td>' . htmlentities($r->choice, ENT_NOQUOTES | ENT_HTML401,
                                        "UTF-8") . '</td><td>' . ((!is_numeric($get2->chance))
                                        ? 0 : $get2->chance) . '%</td><td>' . timec($r->timewait) . '</td>
            </tr>
            ';
                            }
                        } else {
                            echo '<tr><td colspan="3"><em>Ingen biltyverier kan bli tatt akkurat nå!</em></td></tr>';
                        }
                        ?>
                    </table>
                    <input type="hidden" value="" name="valget" id="valget">
                </form>
                <script language="javascript">
                  function sendpost(valg) {
                    $('#valget').val(valg);
                    $('#bil').submit();
                  }

                  $(document).ready(function() {
                    $('.valg').hover(function() {
                      $(this).removeClass().addClass('c_2').css('cursor', 'pointer');
                    }, function() {
                      $(this).removeClass().css('cursor', 'pointer');
                    });
                  });
                </script>
            </div>
            <?php
        }
    }
}
endpage();

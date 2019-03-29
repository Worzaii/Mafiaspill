<?php
include("core.php");
if (fengsel()) {
    $injail = true;
} else {
    $injail = false;
}
if (bunker() == true) {
    startpage("Fengsel");
    echo '<h1>Fengsel</h1>';
    $bu = bunker(true);
    echo '
	<p class="feil">Du er i bunker, gjenst&aring;ende tid: <span id="bunker">'.$bu.'</span><br>Du er ute kl. '.date("H:i:s d.m.Y",
        (time() + $bu)).'</p>
	<script>
	teller('.$bu.',\'bunker\',false,\'ned\');
	</script>
	';
} else {
    if (isset($_POST['valget']) && (isset($_POST['kjope']) || isset($_POST['bryte']))) {
        $tid = $db->escape($_POST['valget']);
        if (isset($_POST['kjope'])) {
            $ut = 1;
        } else if (isset($_POST['bryte'])) {
            $ut = 0;
        }
        if (fengsel() == true) {
            if ($ut == 0) {
                $res = feil('Du kan ikke bryte ut noen n&aring;r du er i fengselet selv!');
            } else if ($ut == 1) {
                $res = feil('Du kan ikke kj&oslash;pe ut noen n&aring;r du er i fengselet selv!</p>');
            }
        } else {/* Fortsetter script */
            if ($ut == 1) {
                $getjailuser = $db->query("SELECT * FROM `jail` WHERE `breaker` IS NULL AND `timeleft` > UNIX_TIMESTAMP() AND `id` = '$tid'");
                if ($db->num_rows() == 0) {
                    $res = feil('Spilleren var ikke i fengsel!');
                }
                $f = $db->fetch_object($getjailuser);
                if ($f->timeleft >= (time() + 600)) {
                    $res = feil('Du kan ikke kj&oslash;pe ut noen som har over 10minutter ventetid!');
                } else {
                    $e = $db->query("SELECT * FROM `jail` WHERE `breaker` IS NULL AND `timeleft` > UNIX_TIMESTAMP() AND `id` = '$tid'");
                    $f = $db->fetch_object($e);
                    if ($obj->hand >= $f->priceout) {
                        if ($db->query("UPDATE `users` SET `hand` = (`hand` - '{$f->priceout}') WHERE `id` = '{$obj->id}' LIMIT 1")) {
                            if ($db->query("UPDATE `jail` SET `breaker` = '{$obj->id}' WHERE `breaker` IS NULL AND `id` = '$tid' AND `timeleft` > UNIX_TIMESTAMP() ORDER BY `id` DESC LIMIT 1")) {
                                $db->query("UPDATE `jail` SET `breaker` = '{$obj->id}' WHERE `id` = '$tid'");
                                $res = lykket('Du har kj&oslash;pt ut '.user($f->uid).' for '.number_format($f->priceout).'kr!');
                                /* $db->query("INSERT INTO `sysmail`(`uid`,`time`,`msg`) VALUES ('".$f->uid."','".time()."','".$db->slash('--<b>Fengsel</b><br/>'.$obj->user.' kj&oslash;pte deg ut fra fengslet!')."')"); */
                            }
                        }
                    } else {
                        $res = feil('Du har ikke r&aring;d til &aring; kj&oslash;pe ut '.status($f->uid, 1).'!');
                    }
                }
            } else if ($ut == 0) {
                $getjailuser = $db->query("SELECT * FROM `jail` WHERE `breaker` IS NULL AND `timeleft` > UNIX_TIMESTAMP() AND `id` = '$tid'");
                if ($db->num_rows() == 0) {
                    $res = feil('Spilleren var ikke i fengsel!');
                } else {
                    $f    = $db->fetch_object();
                    $pris = $f->prisut;
                    $user = $f->uid;
                    $q    = $db->query("SELECT * FROM `users` WHERE `id` = '$user'");
                    if ($db->num_rows() == 0) {
                        $userlocked = 'Ingen';
                    } else {
                        $qq         = $db->fetch_object();
                        $userlocked = $qq->user;
                    }
                    if ($f->timeleft >= (time() + 600)) {
                        $res = '<p class="feil">Du kan ikke bryte ut noen som har mere enn 10minutter ventetid!</p>';
                    } else {
                        $n2 = rand(1, 100);
                        if ($n2 <= 60) {
                            if (settinn($obj->id, "Pr&oslash;vde &aring; bryte ut {$userlocked}", 60)) {
                                $res = feil('Du klarte ikke &aring; bryte ut '.user($f->uid).'! De oppdaget det og kastet deg like s&aring; godt inn.');
                            } else {
                                $res = feil('Av en eller annen grunn ble du ikke satt i fengselet!');
                            }
                        } else {
                            if ($db->query("UPDATE `jail` SET `breaker` = '{$obj->id}' WHERE `breaker` IS NULL AND `id` = '$tid' AND `timeleft` > UNIX_TIMESTAMP() ORDER BY `id` DESC LIMIT 1")) {
                                if ($db->query("UPDATE `users` SET `exp` = (`exp` + 0.4) WHERE `id` = '{$obj->id}' LIMIT 1")) {
                                    $res = lykket('Du klarte &aring; bryte ut '.user($f->uid).'!<br>Du tjente 0.4xp!');
                                    /* $db->query("INSERT INTO `sysmail`(`uid`,`time`,`msg`) VALUES ('".$f->uid."','".time()."','".$db->slash('--<b>Fengsel</b><br/>'.$obj->user.' br&oslash;t deg ut fra fengslet!')."')"); */
                                    /* sysmel($f->uid,
                                      '--<b>Fengsel</b></br>'.$obj->user.' br&oslash;t deg ut fra fengslet!'); */
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    startpage("Fengselet");
    echo '<img src="imgs/fengsel.png">';
    ?>
    <h1>Fengselet</h1>
    <div style="margin: 0 auto;width: auto;text-align: center;">
    </div>
    <?php
    if (isset($res)) {
        echo $res;
    }
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
                $s       = $db->query("SELECT * FROM `jail` WHERE `breaker` IS NULL AND `timeleft` > UNIX_TIMESTAMP() ORDER BY `timeleft` DESC");
                if ($db->num_rows() >= 1) {
                    $numid = 0;
                    while ($r     = mysqli_fetch_object($s)) {
                        $numid = $numid + 1;
                        $left  = $r->timeleft - $timenow;
                        echo '<tr class="velg" id="n'.$numid.'" onclick="select('.$r->id.',this);">
                        <td>'.user($r->uid, 0).'</td>
                            <td>'.$r->reason.'</td>
                                <td><span id="f'.$r->id.'"></span>
                                    <script>teller('.$left.',"f'.$r->id.'",false,"ned");</script>
                                </td>
                                <td>'.number_format($r->priceout).'kr</td>
                        </tr>';
                    }

                    if (fengsel() == 1) {
                        $ak = 1;
                    }
                    if (fengsel() == 0) {
                        $ak = 0;
                    }
                    if ($ak == 1) {
                        $extra = null;
                    }
                    if ($ak == 0) {
                        $extraone = NULL;
                    }
                    if ($ak == 0) {
                        $extra = '<tr>
                            <td colspan="4" style="text-align: center;">
                                <input type="submit" value="Bryt ut!" name="bryte" class="button2">
                                <input type="submit" value="Kj&oslash;p ut!" name="kjope" class="button2">
                                
                            </td>
                        </tr>';
                        echo $extra;
                        echo $extraone;
                    }
                } else {
                    echo '<tr><td colspan="4" style="text-align: center;"><em>Det er ingen innsatt i fengselet, sjekk igjen senere!</em></td></tr>';
                }
                ?>
            </tbody>
        </table>
        <input type="hidden" id="valget" value name="valget">
    </form>
    <script>
        $(document).ready(function () {
            $('.velg').hover(function () {
                $(this).not(".valgt").removeClass().addClass('c_2 velg').css('cursor', 'pointer');
            }, function () {
                $(this).not(".valgt").removeClass().addClass('velg').css('cursor', 'pointer');
            });
        });
        function select(id, self) {
            $("table.table tr.valgt").removeClass().addClass("velg").css('cursor', 'pointer');
            $(self).removeClass().addClass('c_3 valgt').css('cursor', 'pointer');
            $("#valget").val(id);
        }
    </script>
    <?php
}
if (fengsel() == true) {
    echo '
<p class="feil">Du er i fengsel, gjenst&aring;ende tid: <span id="krim">'.fengsel(true).'</span></p>
<script type= "text/javascript">
teller('.fengsel(true).',\'krim\',true,\'ned\');
</script>
';
}
endpage();

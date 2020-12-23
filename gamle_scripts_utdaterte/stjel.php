<?php
include("core.php");
startpage("Stjel penger");
echo '<img src="images/headers/Ranspiller.png">';
?>
    <h1>Stjel penger fra spillere</h1>
<?php
if (bunker() == true) {
    $bu = bunker(true);
    echo '
	<p class="feil">Du er i bunker, gjenstående tid: <span id="bunker">' . $bu . '</span><br>Du er ute kl. ' . date("H:i:s d.m.Y", $bu) . '</p>
	<script>
	teller(' . ($bu - time()) . ',\'bunker\',\'ned\',false);
	</script>
	';
} elseif (fengsel() == true) {
    echo '
	<p class="feil">Du er i fengsel, gjenstående tid: <span id="krim">' . fengsel(true) . '</span></p>
	<script>
	teller(' . fengsel(true) . ';)
	</script>
	';
} else {
    $time = time();
    $time2 = $time + 900;
    $t = $db->query("SELECT * FROM `robbery` WHERE `uid` = '$obj->id' AND `timestamp` > '$time' ORDER BY `id` DESC LIMIT 1");
    if ($db->num_rows() == 1) {
        $l = mysqli_fetch_object($t);
        $left = $l->timestamp - $time;
        if ($left >= 1) {
            $kan = 0;
            //Må fortsatt vente
            echo '<p class="feil">Du må fortsatt vente i <span id="rantid"></span>!<!--<br>Tid: ' . $l->time .
                ' - ' . $time . ' = ' . $left . '--></p><script>teller(' . $left . ',"rantid",false,"ned")</script>';
        } else {
            //echo '<p>time: '.$time.'<br>ltime: '.$l->time.'<br>time - ltime = '.$left.'</p>';
            $kan = 1;
        }
    } else {
        //Første kupp
        $kan = 1;
    }
    if (isset($_POST['spiller'])) {
        if ($kan == 0) {
            echo feil('Du kan ikke stjele fra noen enda!');
        } else {
            $sp = $db->escape($_POST['spiller']);
            if (strtolower($sp) == strtolower($obj->user)) {
                echo warning('Du kan ikke rane deg selv! :)');
            } else {
                $s = $db->query("SELECT * FROM `users` WHERE `user` = '$sp' LIMIT 1 FOR UPDATE ");
                if ($db->num_rows() == 1) {
                    $f = $db->fetch_object();
                    if ($f->status == 1 || $f->status == 2) {
                        echo warning('Du kan ikke rane ledelsen!');
                    } elseif ($f->health <= 0) {
                        echo warning('Du kan ikke rane døde spillere!');
                    } else {
                        if ($f->city == $obj->city) {
                            if ($f->hand >= 500000) {
                                $rand = rand(100000, $f->hand);
                                $db->query("UPDATE `users` SET `hand` = (`hand` - $rand) WHERE `id` = '$f->id' LIMIT 1");
                                $db->query("UPDATE `users` SET `hand` = (`hand` + $rand),`exp` = (`exp` + 2.0) WHERE `id` = '$obj->id' LIMIT 1");
                                $db->query("INSERT INTO `robbery`(`uid`,`tid`,`ucity`,`tcity`,`amount`,`timestamp`) VALUES('$obj->id','$f->id','$obj->city','$f->city','$rand','$time2')");
                                /*$db->query("INSERT INTO `sysmail`(`uid`,`time`,`msg`) VALUES ('" . $f->id . "','" .
                                    time() . "','" . $db->slash('--<b>Ran Spiller</b><br>' . $obj->user . ' ranet '
                                . number_format($rand) . 'kr fra deg!') . "')");*/
                                echo lykket('Du klarte å rane ' . status($f->user) . ' for ' . number_format($rand) . ' kr!');
                            } else {
                                echo warning('Spilleren har ikke nok penger ute! ' . $f->user . ' har bare ' .
                                    number_format($f->hand) . ' kr ute!');
                            }
                        } else {
                            echo warning('Du var ikke i samme byen som mafiaen, du klarte det ikke!');
                            $rand = 0;
                            $db->query("INSERT INTO `robbery`(`uid`,`tid`,`ucity`,`tcity`,`amount`,`timestamp`) VALUES('$obj->id','$f->id','$obj->city','$f->city','$rand','$time2')");
                        }
                    }
                } else {
                    echo feil('Spilleren eksisterer ikke!');
                }
            }
        }
    }
    ?><br>
    <form method="post" action="">
        <table class="table" style="width: 300px;">
            <tr>
                <th colspan="2"><p style="width:300px;">Ran spiller<br><span style="font-size: 10px;">For at du skal klare å stjele penger må spilleren ha minst 500,000kr ute og være i samme by som du er i, bommer du må du vente en stund før du kan prøve igjen.</span>
                    </p></th>
            </tr>
            <tr>
                <td>Nick:</td>
                <td>
                    <input type="sumbit" maxlength="15" name="spiller">
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;">
                    <input class="ran" type="submit" value="Ran spilleren!">
                </td>
            </tr>
        </table>
    </form>
    <?php
}
endpage();

<?php
include("core.php");
if (r1() || r2() || r3()) {
    startpage("Forumpanel");
    if (isset($_GET['edit']) || isset($_GET['del'])) {
        if (isset($_GET['edit'])) {
            $id = $db->escape($_GET['edit']);
            $db->query("DELETE FROM `forumban` WHERE `id` = '$id' LIMIT 1");
            echo lykket('Forumban ble fjernet!') . '</br>
		<a href="forumban.php">G&aring; tilbake!</a>';
        } else {
            echo feil('En feil skjedde da du skulle fjerne ban');
        }
    } else {
        if (isset($_POST['user']) && isset($_POST['res']) && isset($_POST['time'])) {
            $user = $db->escape($_POST['user']);
            $res = $db->escape($_POST['res']);
            $time = $db->escape($_POST['time']);
            if (is_numeric($time) && strlen($res) >= 4) {
                $s = $db->query("SELECT * FROM `users` WHERE `user` = '$user'");
                if ($db->num_rows($s) == 1) {
                    $u = $db->fetch_object();
                    $tim = [300, 900, 1800, 3600, 18000, 86400, 604800];
                    if ($db->query("INSERT INTO `forumban`(`uid`,`timestamp`,`bantime`,`banner`,`reason`) 
VALUES('$u->id',UNIX_TIMESTAMP(),(UNIX_TIMESTAMP()+" . $tim[$time] . "),'$obj->id','$res')")) {
                        $banres = lykket('Spilleren har blitt bannet!');
                    } else {
                        $banres = feil('Det oppstod feil i query!');
                    }
                } else {
                    $banres = feil('Brukeren eksisterer ikke!');
                }
            } else {
                $banres = feil('Tid ikke korrekt definert(endring i nettleser) eller grunn ikke lang nok! 
                Grunn m&aring; v&aelig;re 4 tegn eller mer.');
            }
        }
        if (isset($_GET['ban'])) {
            $ban = $db->escape($_GET['ban']);
            $sporring = $db->query("SELECT * FROM `users` WHERE `id` = '$ban'");
            if ($db->num_rows($sporring) == 0) {
                echo feil('Ingen bruker med brukernavnet ' . htmlentities($ban) . ' eksisterer.');
            } else {
                $asd = $db->fetch_object($sporring);
            }
        }
        ?>
        <h1>Forumbanpanel</h1>
        <?php
        if (isset($banres)) {
            echo $banres;
        }
        ?>
        <form method="post" action="">
            <table class="table">
                <tr>
                    <th colspan="2">Forumban en spiller</th>
                </tr>
                <tr>
                    <td>Spiller:</td>
                    <td><input type="text" value="<?= (isset($asd->user) ? $asd->user : null); ?>" name="user"
                               maxlength="15"></td>
                </tr>
                <tr>
                    <td>Grunn:</td>
                    <td><input type="text" name="res"></td>
                </tr>
                <tr>
                    <td>Varighet:</td>
                    <td>
                        <select name="time">
                            <option value="0">5 minutter</option>
                            <option value="1">15 minutter</option>
                            <option value="2">30 minutter</option>
                            <option value="3">1 time</option>
                            <option value="4">5 timer</option>
                            <option value="5">1 dag</option>
                            <option value="6">En uke</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th colspan="2"><input type="submit" value="Ban spiller!"></th>
                </tr>
            </table>
        </form>
        <table class="table" style="margin-top:20px;">
            <tr>
                <th colspan="6">Forumbannede spillere:</th>
            </tr>
            <tr>
                <td>Bruker:</td>
                <td>Dato bannet:</td>
                <td>Banner:</td>
                <td>Tid igjen:</td>
                <td>Grunn</td>
                <td><i name="redigerban">Ta vekk straff:</i></td>
            </tr>
            <?php
            $s = $db->query("SELECT * FROM `forumban` WHERE `bantime` > UNIX_TIMESTAMP() 
                           AND `active` = 1 ORDER BY `bantime` DESC");
            if ($db->num_rows($s) >= 1) {
                while ($r = mysqli_fetch_object($s)) {
                    $time = $r->bantime - time();
                    echo '
<tr>
    <td>' . user($r->uid) . '</td>
    <td>' . date("H:i:s d.m.Y", $r->timestamp) . '</td>
    <td>' . user($r->banner) . '</td>
    <td><span id="user' . $r->uid . '"></span>
    <script>teller(' . $time . ',"user' . $r->uid . '",false,"ned");</script>
    </td>
	<td>' . htmlentities($r->reason) . '</td>
    <td><a title="Rediger" href="forumban.php?edit=' . $r->id . '"><img src="imgs/edit.gif" alt="Rediger"></a></td>
</tr>
';
                }
            } else {
                echo '<tr>
<td colspan="5" style="text-align:center;"><em>Ingen er bannet atm.</em></td>
</tr>';
            }
            ?>
        </table>
        <?php
    }
} else {
    startpage("Ingen tilgang!");
    noaccess();
}
endpage();

<?php
include("core.php");
if (r1() || r2()) {
    startpage("IP-ban bruker");
    echo '<h1>Utesteng IP-adresse</h1>';
    /* IP BANN SYSTEMET */
    if (isset($_POST['ban'])) {
        $ip = ($_POST['ip']);
        $iplong = ip2long($ip);
        $sql1 = $db->query("SELECT * FROM `ipban` WHERE `ip`='$iplong'");
        if ($db->num_rows() === 1) {
            lykket("IP-adressen er allerede bannet!");
        } else {
            $grunn = $db->escape($_POST['grunn']);
            $db->query("INSERT INTO `ipban` VALUES(null,'$iplong',1,UNIX_TIMESTAMP(),'$grunn','{$obj->id}')");
            if ($db->affected_rows() == 1) {
                echo lykket("IP-adressen <u>" . htmlentities($ip) . "</u> er bannet!");
            } else {
                echo feil('Kunne ikke banne ip-adresse: ' . htmlentities($ip));
            }
        }
    } elseif (isset($_POST['deleteban'])) {
        $id = (int)$_POST['deleteban'];
        $db->query("select * from `ipban` WHERE active = 1 and id = '$id' order by id desc limit 1");
        if ($db->num_rows() == 1) {
            $db->query("update `ipban` set active = 0 where id = '$id'");
            if ($db->affected_rows() == 1) {
                echo lykket("IP-adressen har blitt fjernet fra blokkeringen.");
            } else {
                echo feil('Kunne ikke fjerne IP-adressen fra blokkering. Se hendelsesloggen...');
            }
        } else {
            echo info('Det var ingenting i tabellen der. 
            Pr&oslash;v &aring; friske opp siden og pr&oslash;v p&aring; nytt.');
        }
    }
    ?>
    <form action="" method="post">
        <table class="table" style="width:300px">
            <thead>
            <th colspan="2" style="text-align: center">IP-adresse</th>
            </thead>
            <tr class="uhead">
                <td>IP-adresse:</td>
                <td><input required="" class="frelst" type="text" name="ip" class="input"></td>
            </tr>
            <tr class="ehead">
                <td>Grunn:</td>
                <td><textarea required="" class="frelst" name="grunn" style="width: 225px;height: 200px;"></textarea>
            </tr>
            <tr class="uhead">
                <td colspan="2"><input type="submit" name="ban" value="Ban IP!" class="submit"></td>
            </tr>
        </table>
    </form>
    <form method="post" action>
        <table class="table">
            <thead>
            <tr>
                <th colspan="5">Bannede IP-adresser</th>
            </tr>
            <tr>
                <th>Adressen:</th>
                <th>Grunnlaget:</th>
                <th>Av:</th>
                <th>Dato:</th>
                <th>Fjerne ban?</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $i = $db->query("SELECT * FROM `ipban` where active = 1 ORDER BY `id` DESC");
            if ($db->num_rows() >= 1) {
                while ($r = mysqli_fetch_object($i)) {
                    echo '<tr><td>' . long2ip($r->ip) . '</td><td>' . $r->reason . '</td><td>' . user($r->banner) . '</td>
<td>' . date("H:i:s d.m.Y", $r->timestamp) . '</td><td><input type="submit" name="deleteban" value="' . $r->id . '"></td></tr>';
                }
            } else {
                echo '<tr><td colspan="5" class="center">Ingen rader &aring; vise.</td></tr>';
            }
            ?>
            </tbody>
        </table>
    </form>
    <?php
} else {
    startpage("Ingen tilgang");
    noaccess();
}
endpage();
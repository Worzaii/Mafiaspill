<?php
include("core.php");
if (r1() || r2()) {
    startpage("IP-ban bruker");
    echo '<h1>Utesteng IP-adresse</h1>';
    if (isset($_POST['ban'])) {
        $ip = ($_POST['ip']);
        $iplong = ip2long($ip);
        $ipban = $db->prepare("SELECT count(*) FROM `ipban` WHERE `ip`=?");
        $ipban->execute([$iplong]);
        if ($ipban->fetchColumn() == 1) {
            echo warning("IP-adressen er allerede bannet!");
        } else {
            $grunn = $_POST['grunn'];
            $banip = $db->prepare("INSERT INTO `ipban`(ip, timestamp, reason, banner) VALUES(?, unix_timestamp(), ?, ?)");
            $banip->execute([$iplong, $_POST['grunn'], $obj->id]);
            if ($banip->rowCount() == 1) {
                echo lykket("IP-adressen <u>" . htmlentities($ip) . "</u> er bannet!");
            } else {
                echo feil('Kunne ikke banne ip-adresse: ' . htmlentities($ip));
            }
        }
    } elseif (isset($_POST['deleteban'])) {
        $id = (int)$_POST['deleteban'];
        $delban = $db->prepare("select count(*) from `ipban` WHERE active = '1' and id = ? order by id desc limit 1");
        $delban->execute([$id]);
        if ($delban->fetchColumn() == 1) {
            $bandel = $db->prepare("update `ipban` set active = 0 where id = ?");
            $bandel->execute([$id]);
            if ($bandel->rowCount() == 1) {
                echo lykket("IP-adressen har blitt fjernet fra blokkeringen.");
            } else {
                echo feil('Kunne ikke fjerne IP-adressen fra blokkering. Se hendelsesloggen...');
            }
        } else {
            echo warning('IP-adressen du fors&oslash;kte &aring; fjerne er ikke aktiv i tabellen, kanskje den allerede er fjernet?');
        }
    }
    if (isset($_GET['ip'])) {
        $ip = htmlentities($_GET['ip']);
    } else {
        $ip = null;
    }
    ?>
    <form action="" method="post">
        <table class="table" style="width:300px">
            <thead>
            <th colspan="2" style="text-align: center">IP-adresse</th>
            </thead>
            <tr class="uhead">
                <td>IP-adresse:</td>
                <td><input required="" value="<?= $ip; ?>" type="text" name="ip"
                           class="input frelst"></td>
            </tr>
            <tr class="ehead">
                <td>Grunn:</td>
                <td><textarea required="" class="frelst" name="grunn"
                              style="width: 225px;height: 200px;"></textarea>
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
            $i = $db->query("SELECT * FROM `ipban` where active = '1' ORDER BY `id` DESC");
            error_log("Dumping some data:\n
            Errorcode: " . $i->errorCode() . "\n
            Rowcount: " . $i->rowCount() . "\n
            Columns: " . $i->columnCount() . "\n
            i fetch object dump: " . /*var_export($i->fetchObject(), true) . */ "\n
            Fetchall dump: " . var_export($i->fetchObject(), true)
            );
            $i->closeCursor();$i->execute();
            if ($i->rowCount() >= 1) {
                while ($r = $i->fetchObject()) {
                    echo '<tr>
<td>' . long2ip($r->ip) . '</td>
<td>' . $r->reason . '</td><td>' . user($r->banner) . '</td>
<td>' . date("H:i:s d.m.Y", $r->timestamp) . '</td>
<td><input type="submit" name="deleteban" value="' . $r->id . '"></td>
</tr>';
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
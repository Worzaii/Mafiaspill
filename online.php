<?php
include("core.php");
startpage("P&aring;loggede spillere");
?>
    <h1>P&aring;loggede spillere</h1>
<?php
if (r1() || r2()) {
    $add1 = "<th>IP-adresse</th>";
    $add3 = "<th>Hostname</th>";
    $cols = 4;
} else {
    $add1 = null;
    $cols = 2;
    $add3 = null;
}
$online = $db->query("SELECT id,user,lastactive,ip,hostname,status FROM `users` WHERE `lastactive` BETWEEN (UNIX_TIMESTAMP() - 1800) AND UNIX_TIMESTAMP()
ORDER BY `lastactive` DESC");
?>
    <table class="table online">
        <thead>
        <tr>
            <th colspan="<?=$cols;?>">P&aring;logget n&aring;:</th>
        </tr>
        <tr>
            <th style="width:95px;">Spiller:</th>
            <th>Sist aktiv:</th><?=$add1 . $add3;?>
        </tr>
        </thead>
        <tbody>
        <?php
        while ($r = mysqli_fetch_object($online)) {
            $newtime = time() - $r->lastactive;
            if (r1() || r2()) {
                $add2 = "<td><span class=\"added-ip\">" . (($r->ip != null) ?
                        ((!r1() && $r->status === 1) ? "***" : $r->ip) : "Ikke registrert") . "</span></td>";
                $add3 = "<td>" . (($r->hostname != null) ? (($obj->status > 1 && $r->status == 1) ?
                        "***" : $r->hostname) : "Ikke registrert") . "</td>";
            } else {
                $add2 = null;
                $add3 = null;
            }
            echo '
          <tr>
          <td style="cursor:pointer;" onclick="window.location=\'profil.php?id=' . $r->id . '\'">
          ' . status($r->user) . '</td><td><span id="id' . $r->id . '"></span>
          <script>teller(' . $newtime . ',"id' . $r->id . '",false,"opp");</script></td>
          ' . $add2 . $add3 . '
          </tr>
          ';
        }
        /*$r = $db->fetch_object();
        echo 'Testing some more: '.$r->user;*/
        $db->result->close();
        ?>
        </tbody>
    </table>
<?php

if (r1() || r2()) {
    echo '<table class="table extra">
    <thead>
    <tr>
        <th colspan="4">Spillere som har v&aelig;rt p&aring;logget siste uken</th>
    </tr>
    <tr>
        <th>Bruker</th>
        <th>Sist aktiv</th>
        <th>Ip</th>
        <th>Hostname</th>
    </tr>
    </thead>
    <tbody>
';
    $lately = $db->query("SELECT id,`user`,lastactive,ip,hostname FROM `users` WHERE `lastactive` BETWEEN
    (UNIX_TIMESTAMP() - (60*60*24*7)) AND (UNIX_TIMESTAMP() - 1800)
    ORDER BY `lastactive` DESC");
    if ($lately->num_rows >= 1) {
        while ($s = mysqli_fetch_object($lately)) {
            $newtime = time() - $s->lastactive;
            $add2 = "<td>" . (($s->ip != null) ? (($obj->status > 1 && $s->status == 1) ? "***" : $s->ip) :
                    "Ikke registrert") . "</td>";
            $add3 = "<td>" . (($s->hostname != null) ? (($obj->status > 1 && $s->status == 1) ? "***" : $s->hostname) :
                    "Ikke registrert") . "</td>";
            echo '
          <tr>
          <td style="cursor:pointer;" onclick="window.location=\'profil.php?id=' . $s->id . '\'">
          ' . status($s->user) . '</td><td><span id="id' . $s->id . '"></span>
          <script>teller(' . $newtime . ',"id' . $s->id . '",false,"opp");</script>
          ' . $add2 . $add3 . '
          </tr>
          ';
        }
    } else {
        echo '<tr><td colspan="' . $cols . '" class="center">Det er ingenting &aring; vise.</td></tr>';
    }
    
    echo '</tbody></table>';
}
endpage();

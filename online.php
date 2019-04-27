<?php
include("core.php");
startpage("P&aring;loggede spillere");
?>
    <h1>P&aring;loggede spillere</h1>
<?php
if (r1() || r2()) {
    $add1 = "<td>IP-adresse</td>";
    $add3 = "<td>Hostname</td>";
    $cols = 4;
} else {
    $add1 = null;
    $cols = 2;
    $add3 = null;
}
$sql = $db->query("SELECT * FROM `users` WHERE `lastactive` BETWEEN (UNIX_TIMESTAMP() - 1800) AND UNIX_TIMESTAMP()
ORDER BY `lastactive` DESC");
?>
    <table class="table">
        <tr>
            <th colspan="<?= $cols; ?>">P&aring;logget n&aring;:</th>
        </tr>
        <tr>
            <td style="width:95px;">Spiller:</td>
            <td>Sist aktiv:</td><?= $add1 . $add3; ?>
        </tr>
        <?php
        while ($r = mysqli_fetch_object($sql)) {
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
          <script>teller(' . $newtime . ',"id' . $r->id . '",false,"opp";)</script>
          ' . $add2 . $add3 . '
          </tr>
          ';
        }
        mysqli_free_result($sql);
        ?>
    </table>
<?php

if (r1() || r2()) {
    echo '<table class="table" style="margin-top: 15px;">
<tr>
<th colspan="4">Spillere som har v&aelig;rt p&aring;logget siste 30 dager</th>
</tr>
<tr>
<th>Bruker</th><th>Sist aktiv</th><th>Ip</th><th>Hostname</th>
</tr>';
    $db->query("SELECT * FROM `users` WHERE `lastactive` BETWEEN
    '" . strtotime("-30days") . "' AND (UNIX_TIMESTAMP() - 1800)
    ORDER BY `lastactive` DESC");
    while ($r = $db->fetch_object()) {
        $newtime = time() - $r->lastactive;
        $add2 = "<td>" . (($r->ip != null) ? (($obj->status > 1 && $r->status == 1) ? "***" : $r->ip) :
                "Ikke registrert") . "</td>";
        $add3 = "<td>" . (($r->hostname != null) ? (($obj->status > 1 && $r->status == 1) ? "***" : $r->hostname) :
                "Ikke registrert") . "</td>";
        echo '
          <tr>
          <td style="cursor:pointer;" onclick="window.location=\'profil.php?id=' . $r->id . '\'">
          ' . status($r->user) . '</td><td><span id="id' . $r->id . '"></span>
          <script>teller(' . $newtime . ',"id' . $r->id . '",false,"opp";)</script>
          ' . $add2 . $add3 . '
          </tr>
          ';
    }
    echo '</table>';
}
endpage();

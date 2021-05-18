<?php

include("core.php");
$annual = "uken"; /* Setting a default value, if not overriden */
if (isset($_GET['timespan']) && r1()) {
    $time = (int)$_GET['timespan'];
    if ($time === 1) {
        /* One day limit */
        $t = 60 * 60 * 24;
        $annual = "dagen";
    } elseif ($time === 2) {
        /* One week limit */
        $t = 60 * 60 * 24 * 7;
        $annual = "uken";
    } elseif ($time === 3) {
        /* One month-ish */
        $t = 60 * 60 * 24 * 31;
        $annual = "måneden";
    } elseif ($time === 4) {
        /* One year */
        $t = 60 * 60 * 24 * 365;
        $annual = "året";
    } else {
        error_log("Couldn't do it, type and value of GET is: " . gettype($time) . " " . $time);
    }
}
startpage("Påloggede spillere");
?>
    <h1>Påloggede spillere</h1>
<?php
if (r1() || r2()) {
    echo <<<END
<p>Tabell 2 tidsjusteringer: <a href="online.php?timespan=1">En dag</a> |
    <a href="online.php?timespan=2">En uke (standard)</a> |
    <a href="online.php?timespan=3">En måned</a> |
    <a href="online.php?timespan=4">Ett år</a>
</p>
END;
    $add1 = "<th>IP-adresse</th>";
    $add3 = "<th>Hostname</th>";
    $cols = 4;
} else {
    $add1 = null;
    $cols = 2;
    $add3 = null;
}
/** @var PDO $db */
$online = $db->query(
    "SELECT id,user,lastactive,ip,hostname,status FROM `users` WHERE `lastactive` BETWEEN (UNIX_TIMESTAMP() - 1800) AND UNIX_TIMESTAMP()
ORDER BY `lastactive` DESC"
);
?>
    <table class="table online">
        <thead>
        <tr>
            <th colspan="<?= $cols; ?>">Pålogget nå:</th>
        </tr>
        <tr>
            <th style="width:95px;">Spiller:</th>
            <th>Sist aktiv:</th><?= $add1 . $add3; ?>
        </tr>
        </thead>
        <tbody>
        <?php
        while ($r = $online->fetchObject()) {
            $newtime = time() - $r->lastactive;
            if (r1() || r2()) {
                error_log("Listed user result: " . ((!r1() && ($r->status == 1 || $r->status == 2)) ? "No" : "Yes"));
                if (r1()) {
                    $add2 = "<td><span class=\"added-ip\">" . (($r->ip != null) ?
                            $r->ip : "Ikke registrert") . "</span></td>";
                    $add3 = "<td>" . (($r->hostname != null) ? $r->hostname : "Ikke registrert") . "</td>";
                } elseif (r2()) {
                    $add2 = "<td><span class=\"added-ip\">" . (($r->ip != null) ?
                            ((($r->status == 1)) ? "***" : $r->ip) : "Ikke registrert") . "</span></td>";
                    $add3 = "<td>" . (($r->hostname != null) ? (($r->status == 1) ?
                            "***" : $r->hostname) : "Ikke registrert") . "</td>";
                }
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
        ?>
        </tbody>
    </table>
<?php

if (r1() || r2()) {
    echo '<table class="table extra">
    <thead>
    <tr>
        <th colspan="4">Spillere som har vært pålogget siste ' . $annual . '</th>
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
    if (isset($t)) {
        $timequery = $t;
    } else {
        /* Defaults to a week */
        $timequery = 60 * 60 * 24 * 7;
    }
    error_log(
        "online.php timequery changed by GET method. Query became: SELECT id,`user`,lastactive,ip,hostname,status from users where lastactive between (unix_timestamp() - ($timequery)) and (unix_timestamp() - 1800)"
    );
    $lately = $db->query(
        "SELECT COUNT(*) as numrows FROM `users` WHERE `lastactive` BETWEEN
    (UNIX_TIMESTAMP() - ($timequery)) AND (UNIX_TIMESTAMP() - 1800)
    ORDER BY `lastactive` DESC"
    );
    if ($lately->fetchColumn() >= 1) {
        $lately2 = $db->query(
            "SELECT id,`user`,lastactive,ip,hostname,status from users where lastactive between (unix_timestamp() - ($timequery)) and (unix_timestamp() - 1800)"
        );
        while ($s = $lately2->fetchObject()) {
            $newtime = time() - $s->lastactive;
            if (r1()) {
                $add2 = "<td><span class=\"added-ip\">" . (($s->ip != null) ?
                        $s->ip : "Ikke registrert") . "</span></td>";
                $add3 = "<td>" . (($s->hostname != null) ? $s->hostname : "Ikke registrert") . "</td>";
            } elseif (r2()) {
                $add2 = "<td><span class=\"added-ip\">" . (($s->ip != null) ?
                        ((($s->status == 1)) ? "***" : $s->ip) : "Ikke registrert") . "</span></td>";
                $add3 = "<td>" . (($s->hostname != null) ? (($s->status == 1) ?
                        "***" : $s->hostname) : "Ikke registrert") . "</td>";
            }
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
        echo '<tr><td colspan="' . $cols . '" class="center">Det er ingenting å vise.</td></tr>';
    }

    echo '</tbody></table>';
}
endpage();

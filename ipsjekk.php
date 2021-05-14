<?php

include("core.php");
if ($obj->status == 1 || $obj->status == 2) {
startpage("Multi/IP-sjekk");
?>
<h1>Sjekk spillere for multi-muligheter</h1>
<p>Det er UTROLIG viktig at det ikke deles videre på noen som helst slags vis, hvordan vi tar multier. </br>
    Bruk ikke modkill-grunn som "HADDE SAMME PASSORD SOM xxxx".. bruk heller "Multi", eller noe sånt!
</p>
<table class="table">
    <tr>
        <th colspan="2">Spillere, og ip:</th>
        <th>Antall:</th>
    </tr>
    <?php
    $s = $db->query(
        "SELECT  `ip` , COUNT(  `ip` ) AS  `antc`,lastactive FROM  `users` WHERE `status` IN (1,2,3,4,5,6) AND `ip` <> '' GROUP BY  `ip` HAVING COUNT(`ip`) > 1 ORDER BY `lastactive` DESC LIMIT 0 , 30"
    );
    $p = $db->query(
        "SELECT  `pass` , COUNT(  `pass` ) AS  `antp` FROM  `users` WHERE `activated` = '1' AND `status` IN (3,4,5,6) GROUP BY  `pass` HAVING COUNT(`pass`) > 1 ORDER BY `antp` DESC LIMIT 0 , 30"
    );
    if ($db->num_rows($s) >= 1) {
        while ($r = mysqli_fetch_object($s)) {
            $s2 = $db->query("SELECT * FROM `users` WHERE `ip` = '" . $r->ip . "' ORDER BY `id` ASC");
            $antc = $db->num_rows();
            $string1 = null;
            while ($r2 = mysqli_fetch_object($s2)) {
                $string1 .= ' <a href="profil.php?id=' . $r2->id . '">' . status($r2->user) . '</a>';
            }
            echo '
<tr>
<td>' . $string1 . '</td><td>' . $r->ip . '</td><td>' . $antc . '</td>
</tr>
';
        }
    } else {
        echo '
<tr>
<td colspan="3"><em>Ingen med multi</em></td>
</tr>
';
    }
    ?>
</table>
<table class="table">
    <tr>
        <th colspan="2">Spillere, og passord:</th>
        <th>Antall like:</th>
    </tr>
    <?php
    $p = $db->query(
        "SELECT  `pass` , COUNT(`pass`) AS  `antp` FROM  `users` WHERE `activated` = '1' AND `status` IN (3,4,5,6) GROUP BY  `pass` HAVING COUNT(`pass`) > 1 ORDER BY `antp` DESC LIMIT 0 , 30"
    );
    if ($db->num_rows($p) >= 1) {
        while ($r = mysqli_fetch_object($p)) {
            $s2 = $db->query("SELECT * FROM `users` WHERE `pass` = '" . $r->pass . "' ORDER BY `id` ASC");
            $antc = $db->num_rows();
            $string1 = null;
            while ($r2 = mysqli_fetch_object($s2)) {
                $string1 .= ' <a href="profil.php?id=' . $r2->id . '">' . status($r2->user) . '</a>';
            }
            echo '
<tr>
<td>' . $string1 . '</td><td>' . $r->pass . '(MD5 hash)</td><td>' . $antc . '</td>
</tr>
';
        }
    } else {
        echo '
<tr>
<td colspan="3"><em>Ingen med like passord</em></td>
</tr>
';
    }
    echo '</table>';
    $nys = $db->query(
        "SELECT `mail`,COUNT(`mail`) AS `ant` FROM `users` WHERE `mail` <> '' GROUP BY `mail` HAVING COUNT(`mail`) > 1 ORDER BY `ant` DESC"
    );
    echo '<table class="table">
<tr>
<th>Mail:</th><th>Antall</th><th>Brukere:</th>
</tr>
';
    while ($r = mysqli_fetch_object($nys)) {
        $s = $db->query("SELECT * FROM `users` WHERE `mail` = '{$r->mail}' ORDER BY `id` ASC");
        $users = null;
        while ($u = mysqli_fetch_object($s)) {
            $users .= '<a href="profil.php?id=' . $u->id . '">' . status($u->user) . "</a>, ";
        }
        echo '
<tr>
<td>' . $r->mail . '</td><td>' . $r->ant . '</td><td>' . $users . '</td>
</tr>
';
    }
    echo '</table>';
    } else {
        startpage("Ingen tilgang!");
        noaccess();
    }
    endpage();
    ?>

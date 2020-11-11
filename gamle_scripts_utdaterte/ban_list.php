<?php
include("core.php");
startpage();
if (r1() || r2()) {
    $db->query("select
    users.id,users.user,users.lastactive,users.regstamp,banlog.timestamp,banlog.reason,banlog.banner
from `banlog` inner join users on banlog.uid = users.id where banlog.active = '1' order by banlog.id desc");
    ?>
    <table class="table">
        <tr>
            <th colspan="6">Bannede brukerkontoer</th>
        </tr>
        <tr>
            <td colspan="2">Brukernavn:</td>
            <td>Grunnlag</td>
            <td>Bannet av</td>
            <td>Sist aktiv</td>
            <td>Registrert</td>
        </tr>
        <?php
        if ($db->num_rows() >= 1) {
            while ($r = $db->fetch_object()) {
                echo '
<tr>
<td style="text-align:right"><a href="ban_user.php?kill=' . $r->user . '&unban">#' . $r->id . '</a></td>
<td><a href="ban_user.php?kill=' . $r->user . '&unban">' . $r->user . '</a></td>
<td>' . $r->reason . '</td>
<td>' . status($r->banner) . '</td>
<td>' . date("H.i.s | d-m-Y", $r->lastactive) . '</td>
<td>' . date("H.i.s | d-m-Y", $r->regstamp) . '</td>
</tr>
';
            }
        } else {
            echo '<tr><td colspan="6"><i>Det er ingen bannede brukere akkurat nå...</i></td></tr>';
        }
        ?>
    </table>

    <?php
} else {
    noaccess();
}
endpage();

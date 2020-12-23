<?php
include 'core.php';
if (r1() || r2()) {
    startpage("Auksjonslogg/oversikt");
    ?>
    <h1>Auksjonslogg</h1>
    <p>Se oversikt over innlagte bud og når budene ble lagt inn!</p>
    <table class="table">
        <thead>
        <tr>
            <th>Auksjoner</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <table class="table">
                    <?php
                    $s1 = $db->query("SELECT * FROM `auksjon` WHERE `done` = '0' AND `time_left` > 
                                               UNIX_TIMESTAMP() AND `currentbid` < `autowin` ORDER BY `id` DESC");
                    if ($db->num_rows() >= 1) {
                        $res = '';
                        while ($r = mysqli_fetch_object($s1)) {
                            $s2 = null;
                            $ex = null;
                            $s2 = $db->query("SELECT * FROM `budauk` WHERE `aid` = '{$r->id}' ORDER BY `id` DESC");
                            if ($db->num_rows() >= 1) {
                                while ($e = $db->fetch_object($s2)) {
                                    $ex .= '<tr><td>' . user($e->uid) . '</td><td>' . number_format($e->bid) . ' kr</td>
<td>' . date("H:i:s d.m.y", $e->time) . '</td></tr>';
                                }
                            } else {
                                $ex = '<tr><td>Ingen bud har blitt lagt inn!<br></td></tr>';
                            }
                            /*Skriver alle auksjonene til variabel som skrives ut etterpå*/
                            $firmaet = firma($r->item);
                            if ($firmaet[2] == 2) {
                                /*Det er en flyplass*/
                                $ting = "Flyplassen i " . city($firmaet[4]);
                            } else if ($firmaet[2] == 1) {
                                /*Lottofirma*/
                                $ting = "Lottofirma";
                            }
                            if (!isset($high)) {
                                $high = 0;
                            } else {
                                $high = $high + 1;
                            }
                            $tidigjen = $r->time_left - time();
                            $curbid = ($r->currentbid == 0) ? "Ingen bud" : number_format($r->currentbid);
                            $res .= '<tr class="auksjonsliste bud" onclick="velg_auk(' . $r->id . ',' . $high . ')">
                  <td>#' . $r->id . '</td>
                  <td>' . $ting . '</td>
                  <td>' . number_format($r->lowbid) . 'kr</td>
                  <td>' . $curbid . '</td>
                  <td>' . number_format($r->autowin) . 'kr</td>
                  <td>' . number_format($r->increasebid) . 'kr</td>
                  <td><span id="teller' . $r->id . '"></span>
                  <script>teller(' . $tidigjen . ',"teller' . $r->id . '",false,"ned");</script></td>
                  <td>' . user($r->seller) . '</td>
                  </tr>
                  <tr>
                  <td colspan="8">
                  <table class="table">
                  <tbody>
                  ' . $ex . '
                  </tbody>
                  </table>
                  </td>
                  </tr>
                  ';
                        }
                        echo $res;
                    } else {
                        echo '<tr><td>Ingen aktive auksjoner å vise!</td></tr>';
                    }
                    ?>
                </table>
            </td>
        </tr>
        </tbody>
    </table>
    <?php
} else {
    noaccess();
}
endpage();
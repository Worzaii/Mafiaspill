<?php
/**
 * TODO: Make the new crime script working again, but better!
 */
include("core.php");
startpage("Kriminalitet");
echo '<img alt src="images/headers/krim.png"><p>N&aring;r du f&oslash;rst starter med kriminalitet,
 s&aring; vil du kun ha et valg. Ettersom du kommer opp i rank, s&aring; vil nye valg l&aring;ses opp. 
 Hvis du ikke ser noen valg, kontakt support!</p>';
if (!(fengsel() || bunker())) {
    /* Continues crime script, elsewise do jail, then bunker if applicable */
    $q = $db->prepare("SELECT count(*) FROM `krimlogg` WHERE `uid` = ? AND `timewait` > UNIX_TIMESTAMP() ORDER BY `timewait` DESC LIMIT 0,1");
    $q->execute([$obj->id]);
    if ($q->fetchColumn() == 1) {
        /* Crime still on cooldown! */
        $q2 = $db->prepare("SELECT timewait FROM `krimlogg` WHERE `uid` = ? AND `timewait` > UNIX_TIMESTAMP() ORDER BY `timewait` DESC LIMIT 0,1");
        $q2->execute([$obj->id]);
        $f = $q2->fetchColumn();
        echo '
        <p class="feil">Du m&aring; vente <span id="krim">' . ($f - time()) . '</span> f&oslash;r neste krim.</p>
        <script>
        teller(' . ($f - time()) . ', "krim", false, "ned");
        </script>
        ';
    } else {
        echo lykket("Du er klar til &aring; utf&oslash;re kriminalitet!");
        $get_actions = $db->prepare("select * from crime where levelmin <= ? ORDER BY `levelmin` DESC,`id` DESC");
        $get_actions->execute([rank($obj->exp)[0]]);
        ?>
        <form name="krim"
              method="post"
              id="krim"
              action="">
            <table style="width:590px;"
                   class="table">
                <tr>
                    <th colspan="5">Krimhandlinger</th>
                </tr>
                <tr>
                    <td style="width:250px;"><b>Handling</b></td>
                    <td><b>Fortjeneste</b></td>
                    <td><b>Ventetid</b></td>
                    <td><b>Sjanse</b></td>
                    <td><b>Straff</b></td>
                </tr>
                <?php
                while ($r = $get_actions->fetchObject()) {
                $sql2 = $db->prepare("SELECT count(*) FROM `chance` WHERE `uid` = ? AND `type` = '1' AND `option` = ?");
                $sql2->execute([$obj->id, $r->id]);
                if ($sql2->fetchColumn() >= 1) {
                    $sql3 = $db->prepare("SELECT * FROM `chance` WHERE `uid` = ? AND `type` = '1' AND `option` = ?");
                    $sql3->execute([$obj->id, $r->id]);
                    $res = $sql3->fetchObject();
                    $sjanse = $res->chance . '%';
                } else {
                    $new_chance = $db->prepare("INSERT INTO `chance`(`uid`,`type`,`option`) VALUES(?,'1',?)");
                    $new_chance->execute([$obj->id, $r->id]);
                    $sjanse = "0%";
                }
                echo '<tr class="valg" onclick="sendpost(' . $r->id . ')">
<td>' . htmlentities($r->description, ENT_NOQUOTES | ENT_HTML401,
                        "UTF-8") . '</td><td>' . number_format($r->minval) . '-' . number_format($r->maxval) . 'kr</td><td>' . $r->untilnext . ' sekunder</td><td>' . $sjanse . '</td><td>' . $r->punishtime . ' sekunder</td>
</tr>
';
                ?>
            </table>
            <input type="hidden"
                   value=""
                   name="valget"
                   id="valget">
        </form>
        <script>
          function sendpost(valg) {
            $('#valget').val(valg);
            $('#krim').submit();
          }

          $(document).ready(function() {
            $('.valg').hover(function() {
              $(this).removeClass().addClass('c_2').css('cursor', 'pointer');
            }, function() {
              $(this).removeClass().css('cursor', 'pointer');
            });
          });
        </script>
        <?php
    }
    }
} else {
    if (bunker()) {
        echo feil('Du er i bunker, gjenst&aring;ende tid: <span id="nedteller"></span>');
        $remaining_time = time() - bunker(true);
    } elseif (fengsel()) {
        echo feil('Du er i fengsel, gjenst&aring;ende tid: <span id="nedteller"></span>');
        $remaining_time = time() - fengsel(true);
    }
    ?>
    <script>teller(<?=$remaining_time?>, 'nedteller', false, 'ned');</script>
    <?php
}
endpage();

<?php
include("core.php");
startpage("Flyplass");
echo '<h1>Flyplass</h1><img src="images/headers/flyplass.png">';
if (bunker()) {
    $bu = bunker(true);
    $tid = date("H:i:s d.m.Y", $bu);
    echo <<<HTML
    <p class="feil">Du er i fengsel, gjenst&aring;ende tid: <span id="fengsel">$bu</span><br>Du er ute: $tid</p>
    <script>
    teller('$bu','bunker',false,'ned');
    </script>
    ';
HTML;
} elseif (fengsel()) {
    $fe = fengsel(true);
    $tid = date("H:i:s d.m.Y", $fe);
    echo <<<HTML
    <p class="feil">Du er i fengsel, gjenst&aring;ende tid: <span id="fengsel">$fe</span><br>Du er ute: $tid</p>
    <script>
    teller('$fe','bunker',false,'ned');
    </script>
    ';
HTML;
} else {
    $airport = $db->prepare("select count(*) from flight_log where (timestamp + 600) > unix_timestamp() and uid = ? order by id desc limit 0,1");
    $airport->execute([$obj->id]);
    #error_log(var_export($airport, true));
    if ($airport->fetchColumn() == 1) {
        $airfetch = $db->prepare("select timestamp from flight_log where (timestamp + 600) > unix_timestamp() and uid = ? order by id desc limit 0,1");
        $airfetch->execute([$obj->id]);
        $wait = ($airfetch->fetchColumn() + 600) - time();
        error_log($wait);
        echo info('Du m&aring; vente f&oslash;r du kan fly igjen! 
<span id="flyplass"></span><script>teller(\'' . ($wait) . '\',"flyplass",false,"ned")</script><br>
Du kan fly igjen: ' . date('H:i:s d.m.Y', time() + $wait));
    } else {
        if (isset($_POST['tilby'])) {
            $i = (int)$_POST['tilby'];
            if (($i >= 1 && $i <= 8) && ($obj->city != $i) && ($obj->hand >= 10000)) {
                /* If everything is correct, just go ahead, if not, it'll be sorted underneath for more clean code with less nesting */
                $fly = $db->prepare("UPDATE `users` SET `hand` = (`hand` - 10000), `city` = ? WHERE `id` = ?");
                $fly->execute([$i, $obj->id]);
                if ($fly->rowCount() == 1) {
                    echo lykket('Du har betalt for en billett til ' . city($i) . ' til prisen 
                                    av 10,000kr! 
                                    Du m&aring; n&aring; vente i 20 minutter f&oslash;r du kan reise igjen.');
                    $flightlog = $db->prepare("insert into flight_log(uid, timestamp, from_city, to_city, price) 
values (?,unix_timestamp(), ?, ?, 10000)");
                    $flightlog->execute([$obj->id, $obj->city, $i]);
                    error_log("Trying to add to flight log... Result: " . var_export($flightlog->fetchAll(),
                            true));
                } else {
                    echo feil('Du kunne ikke reise p&aring; grunn av en feil i enten sp&oslash;rring eller i databasen, ta kontakt med Ledelsen!');
                }
            } elseif ($i >= 1 && $i <= 8) {
                echo warning('Du valgte ikke en gyldig by!');
            } elseif ($obj->city != $i) {
                echo warning('Du kan ikke velge samme by som du er i!');
            } elseif ($obj->hand >= 10000) {
                echo warning('Du m&aring; ha nok penger p&aring; h&aring;nda for &aring; reise!<br>Du mangler: ' . number_format((10000 - $obj->hand)) . "kr");
            }
        }
        ?>
        <p>&aring; ta fly vil koste deg 10,000kr ingame. Dette blir endret i fremtiden slik at
            firmaeierne kan
            endre prisene mellom 1,000kr-50,000kr!</p>
        <form method="post" action id="fly">
            <table class="table flyplass">
                <tr>
                    <th><em title="Flyplass">Velg by:</em></th>
                </tr>
                <?php
                for ($i = 1; $i <= 8; $i++) {
                    echo '
    <tr class="valg" onclick="sendpost(' . $i . ')">
    <td>Reis til ' . city($i) . '!</td>
    </tr>
    ';
                }
                ?>
            </table>
            <input type="hidden" id="vei" value="0" name="tilby">
        </form>
        <script language="javascript">
          function sendpost(valg) {
            $('#vei').val(valg);
//$('.wantto').dialog();
            $('#fly').submit();
          }

          $(document).ready(function() {
            $('.valg').hover(function() {
              $(this).find('td').removeClass().addClass('normrad1').css('cursor', 'pointer');
            }, function() {
              $(this).find('td').removeClass();
            });
          });
        </script>
        <style type="text/css">
            .valg {
                cursor: pointer;
            }
        </style>
        <?php
    }
}
endpage();

<?php
include("core.php");
startpage("Flyplass");
echo '<h1>Flyplass</h1><img src="images/headers/flyplass.png">';
if (bunker()) {
    $bu = bunker(true);
    $tid = date("H:i:s d.m.Y", $bu);
    echo <<<HTML
    <p class="feil">Du er i fengsel, gjenstående tid: <span id="fengsel">$bu</span><br>Du er ute: $tid</p>
    <script>
    teller('$bu','bunker',false,'ned');
    </script>
    ';
HTML;
} elseif (fengsel()) {
    $fe = fengsel(true);
    $tid = date("H:i:s d.m.Y", $bu);
    echo <<<HTML
    <p class="feil">Du er i fengsel, gjenstående tid: <span id="fengsel">$fe</span><br>Du er ute: $tid</p>
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
        echo warning('Du må vente før du kan fly igjen! 
<span id="flyplass"></span><script>teller(\'' . ($wait) . '\',"flyplass",false,"ned")</script><br>
Du kan fly igjen: ' . date('H:i:s d.m.Y', time() + $wait));
    } else {
        if (isset($_POST['tilby'])) {
            $i = $db->escape($_POST['tilby']);
            if ($i >= 1 && $i <= 8) {
                if ($obj->city == $i) {
                    echo warning('Du er allerede i denne byen!');
                } else {
                    if ($obj->hand <= 9999) {
                        echo warning('Du har ikke råd til å fly!');
                    } else {
                        if ($obj->hand >= 10000) {
                            $db->query("UPDATE `users` 
SET `hand` = (`hand` - 10000),
    `city` = '$i' 
WHERE `id` = '{$obj->id}' LIMIT 1");
                            if ($db->affected_rows() == 1) {
                                echo lykket('Du har betalt for en billett til ' . city($i) . ' til prisen 
                                    av 10,000kr! 
                                    Du må nå vente i 20 minutter før du kan reise igjen.');
                                $flightlog = $db->query("insert into flight_log(uid, timestamp, from_city, to_city, price) 
values ('{$obj->id}',unix_timestamp(), '$obj->city', '$i', '10000')");
                                error_log("Trying to add to flight log... Result: " . var_export($flightlog,
                                        true));
                            } else {
                                echo feil('Du kunne ikke reise på grunn av en feil i enten 
                                    spørring eller i databasen, ta kontakt med Ledelsen!');
                            }
                        }
                    }
                }
            } else {
                echo feil('Den byen du valgte, om du valgte noen, er ikke gyldig!');
            }
        }
        ?>
        <p>å ta fly vil koste deg 10,000kr ingame. Dette blir endret i fremtiden slik at
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
        <script>
          $(function() {
            $('#dialog-confirm').dialog({
              resizable: true,
              height: 400,
              modal: true,
              buttons: {
                'Delete all items': function() {
                  $(this).dialog('close');
                },
                Cancel: function() {
                  $(this).dialog('close');
                },
              },
            });
          });
        </script>
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

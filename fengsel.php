<?php
include("core.php");
startpage("Fengsel");
echo '<h1>Fengsel</h1>';
echo '<img src="images/headers/fengsel.png">';
if (isset($_POST['valget']) && (isset($_POST['bryte']) || isset($_POST['kjope']))) {
    /* Starting execution of script depending on action taken */
    $valget = $_POST['valget'];
    if (!is_numeric($valget) || $valget < 1) {
        $res = feil('Du m&aring; velge et gyldig valg f&oslash;rst...');
    } else {
        $action = (isset($_POST['bryte']) ? 'bryte' : (isset($_POST['kjope']) ? 'kjope' : null));
        if (in_array($action, ['kjope', 'bryte'])) {
            /* Finally starting to do stuff. Already too many if/elses going on here */
            if ($action == 'kjope') {
                $jailie = $db->prepare("select count(*) from jail where id = ? and breaker is null and timeleft > unix_timestamp() limit 1");
                $jailie->execute([$valget]);
                if ($jailie->fetchColumn() == 1) {
                    $jailed = $db->prepare("select uid,timeleft,priceout from jail where id = ? and breaker is null and timeleft > unix_timestamp() limit 1");
                    $jailed->execute([$valget]);
                    $jail3 = $jailed->fetchObject();
                    if ($jail3->priceout > $obj->hand) {
                        $res = feil('Du har ikke r&aring;d til &aring; kj&oslash;pe ut spilleren.');
                    } else {
                        $breakout = $db->prepare("update jail set breaker = ? where uid = ? and breaker is null and timeleft > unix_timestamp()");
                        $breakout->execute([$obj->id, $jail3->uid]);
                        if ($breakout->rowCount() == 1) {
                            $doit = $db->prepare("update users set hand = (hand - ?) where id = ?");
                            $doit->execute([$jail3->priceout, $obj->id]);
                            if ($doit->rowCount() != 1) {
                                error_log('Kunne ikke oppdatere penger i handa til brukeren?');
                            }
                            $res = lykket('Du kj&oslash;pte ut spilleren! ');
                        } else {
                            $res = warning('Kunne ikke bryte ut spilleren. For sent?');
                        }
                    }
                } else {
                    $res = warning('Kunne ikke lengre finne spiller i fengsel.');
                }
            } elseif ($action == 'bryte') {
                $res = feil("Funksjonen er ikke klar til bruk enda.");
            } else {
                $res = warning('Valg ikke opprettet...');
            }
        } else {
            $res = feil('Ugyldig valg!');
        }
    }
}
?>
    <h1>Fengselet</h1>
    <div style="margin: 0 auto;width: auto;text-align: center;">
    </div>
<?php
if (isset($res)) {
    echo $res;
}
?>
    <form method="post" action="">
        <table class="table" style="margin: 0px auto; margin-top: 10px; margin-bottom: 15px;">
            <thead>
            <tr>
                <th>Bruker:</th>
                <th>Grunn:</th>
                <th>Tid igjen:</th>
                <th>Kausjonspris</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $s = $db->query("SELECT count(*) FROM `jail` WHERE `breaker` IS NULL AND `timeleft` > UNIX_TIMESTAMP() ORDER BY `timeleft` DESC");
            if ($s->fetchColumn() >= 1) {
                $s2 = $db->query("SELECT * FROM `jail` WHERE `breaker` IS NULL AND `timeleft` > UNIX_TIMESTAMP() ORDER BY `timeleft` DESC");
                $numid = 0;
                while ($r = $s2->fetchObject()) {
                    $numid++;
                    $left = $r->timeleft - time();
                    echo '<tr class="velg" id="n' . $numid . '" onclick="select(' . $r->id . ',this);">
                        <td>' . user($r->uid) . '</td>
                            <td>' . $r->reason . '</td>
                                <td><span id="f' . $r->id . '"></span>
                                    <script>teller(' . $left . ',"f' . $r->id . '",false,"ned");</script>
                                </td>
                                <td>' . number_format($r->priceout) . 'kr</td>
                        </tr>';
                }
                if (fengsel() == false) {
                    echo '<tr>
                            <td colspan="4" style="text-align: center;">
                                <input type="submit" value="Bryt ut!" name="bryte" class="button2">
                                <input type="submit" value="Kj&oslash;p ut!" name="kjope" class="button2">
                                
                            </td>
                        </tr>';
                }
            } else {
                echo '<tr><td colspan="4" style="text-align: center;"><em>Det er ingen innsatt i fengselet, sjekk igjen senere!</em></td></tr>';
            }
            ?>
            </tbody>
        </table>
        <input type="hidden" id="valget" value name="valget">
    </form>
    <script>
      $(document).ready(function() {
        $('.velg').hover(function() {
          $(this).not('.valgt').removeClass().addClass('c_2 velg').css('cursor', 'pointer');
        }, function() {
          $(this).not('.valgt').removeClass().addClass('velg').css('cursor', 'pointer');
        });
      });

      function select(id, self) {
        $('table.table tr.valgt').removeClass().addClass('velg').css('cursor', 'pointer');
        $(self).removeClass().addClass('c_3 valgt').css('cursor', 'pointer');
        $('#valget').val(id);
      }
    </script>
<?php
if (fengsel() == true) {
    echo '
<p class="feil">Du er i fengsel, gjenst&aring;ende tid: <span id="krim">' . fengsel(true) . '</span></p>
<script type= "text/javascript">
teller(' . fengsel(true) . ',\'krim\',true,\'ned\');
</script>
';
}
endpage();

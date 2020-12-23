<?php
include("core.php");
include("./classes/pagination.php");
include_once("inc/bilconfig.php");
startpage("Garasje");
echo '<img src="images/headers/garasje.png" alt>';
if (fengsel()) {
    $bu = fengsel(true);
    echo feil('Du er i fengsel, gjenstående tid: <span id="fengsel">' . $bu . '</span>
<br>Du er ute kl. ' . date("H:i:s d.m.Y", (time() + $bu))) .
        '<script>teller(' . $bu . ', "fengsel", false, \'ned\');</script>';
} elseif (bunker()) {
    $bu = bunker(true);
    echo '
    <p class="feil">Du er i bunker, gjenstående tid:
    <span id="bunker">' . $bu . '</span><br>Du er ute kl. ' . date("H:i:s d.m.Y", $bu) . '</p>
    <script>
    teller(' . ($bu - time()) . ', "bunker", false, \'ned\');
    </script>
    ';
} else {
    ?>
    <h1>Garasjen</h1>
    <p>Velkommen til garasjen din!<br>Her for å selge biler, så må du sende bilene til en annen by, når
        de er fremme, dra til byen du sendte de til, og selg dem for penger :)</p>
    <?php
    if ((isset($_POST['selgbil']) || isset($_POST['sendbil']) ||
            isset($_POST['sendtil']) || isset($_POST['selgalle'])) &&
        isset($_POST['checkbil'])) {
        $biler = $_POST['checkbil'];
        if (isset($_POST['selgbil'])) {
            $errors = ["num" => 0, "res" => 'Kunne ikke selge valgte biler'];
            $success = ["num" => 0, "sum" => 0, "res" => null];
            foreach ($biler as $bil) {
                $bil = $db->escape($bil);
                $gc = $db->query("SELECT * FROM `garage` WHERE `id` = '{$bil}' AND `uid` = '{$obj->id}' AND `sold` = '0'");
                if ($db->num_rows() == 1) {
                    $f = $db->fetch_object();
                    if ($obj->city == $f->current_city) {
                        if ($obj->city == $f->stolen_city) {
                            $errors["num"] += 1;
                            $errors["res"] .= ', fordi bilen var i samme by som du stjal den i';
                        } else {
                            $pris = $cartypes[$f->car_id]["price"];
                            $s = $db->query("SELECT * FROM `garage` 
WHERE `uid` = '{$obj->id}' AND 
      `transferred` <> '0' AND 
      `transferred` < UNIX_TIMESTAMP() AND 
      `id` = '{$bil}' AND 
      `sold` = '0' AND 
      `stolen_city` <> `current_city` 
ORDER BY `id` DESC");
                            if ($db->num_rows() == 1) {
                                $db->query("UPDATE `garage` SET `sold` = '1' 
WHERE `uid` = '{$obj->id}' AND 
      `id` = '{$bil}'
      LIMIT 1");
                                $success['num'] += 1;
                                $success['sum'] += $pris;
                            } else {
                                $errors['res'] .= ', fordi bilen muligens flyttes';
                                $errors['num'] += 1;
                            }
                        }
                    } else {
                        $errors['res'] .= ', fordi du ikke er i samme by som bilen';
                        $errors['num'] += 1;
                    }
                } else {
                    $errors['res'] .= ', fordi den ikke finnes, eller ikke tilhører deg';
                    $errors['num'] += 1;
                }
            }/*Foreach for flere biler END*/
            $errors['res'] .= '.</p>';
            if ($success['num'] >= 1) {/*Kommer med successtekst og resultat*/
                $ant = ($success >= 2) ? " biler" : " bil";
                echo lykket('Du solgte ' . $success['num'] . $ant . ' til verdien av ' . number_format($success["sum"]) . 'kr!');
                $db->query("UPDATE `users` SET `hand` = (`hand` + " . $success['sum'] . ") WHERE `id` = '{$obj->id}' LIMIT 1");
            }
            if ($errors['num'] >= 1) {
                $ant = ($success >= 2) ? " bilene dine" : " bilen din";
                echo feil('Det oppstod ' . $errors["num"] . ' feil' . ' da du prøvde å selge ' . $ant . '!<br>'.$errors['res']);
            }
            /*Selg bil END*/
        } elseif (isset($_POST['sendbil'])) {
            $tilby = $db->escape($_POST['tilby']);
            $errors = ["num" => 0, "res" => "Noen biler ble ikke sendt."];
            $success = ["num" => 0, "res" => null];
            foreach ($biler as $bil) {
                $bil = $db->escape($bil);
                if (is_numeric($tilby)) {
                    $tras = time() + 1800;
                    $gc = $db->query("SELECT * FROM `garage` 
WHERE `id` = '{$bil}' AND 
      `uid` = '{$obj->id}' AND 
      `sold` = '0'");
                    if ($db->num_rows() == 1) {
                        $f = $db->fetch_object($gc);
                        if ($f->transfer > time()) {
                            $errors["num"] += 1;
                            $errors["res"] .= ", fordi en eller flere valgte biler allerede sendes til en by";
                        } else {
                            if ($f->current_city == $tilby) {
                                $errors['num'] += 1;
                                $errors['res'] .= ', fordi bilen ikke kan sendes tilbake til byen den ble stjålet i';
                            } else {
                                if ($f->current_city == $obj->city) {
                                    if ($db->query("UPDATE `garage` 
SET `current_city` = '{$tilby}',
    `transferred` = '{$tras}' 
WHERE `uid` = '{$obj->id}' AND 
      `id` = '$bil'")) {
                                        $success['num'] += 1;
                                    }
                                } else {
                                    $errors['res'] .= ', fordi du ikke er i samme byen som bilen er i';
                                    $errors['num'] += 1;
                                }
                            }
                        }
                    } else {
                        $errors['res'] .= ', fordi du enten har solgt den, gitt den fra deg eller ikke eier den';
                        $errors['num'] += 1;
                    }
                } else {
                    $errors['res'] .= ', fordi byen du sender til har feil';
                    $errors['num'] += 1;
                }
            }
            if ($success['num'] >= 1) {
                $ant = ($success == 1) ? " bil" : " biler";
                echo lykket('Du sendte ' . $success['num'] . $ant . ' til ' . city($tilby));
            }
            if ($errors['num'] >= 1) {
                $ant = ($success == 1) ? " bil" : " biler";
                echo feil('Det oppstod ' . $errors["num"] . ' feil da du prøvde å sendte ' . $ant . ' biler.<br>' . $errors["res"] . '!');
            }
        }
        if (isset($_POST['selgalle'])) {
            $now = time();
            $gc = $db->query("SELECT * FROM `garage` 
WHERE `transferred` < '$now' AND 
      `transferred` <> '0' AND 
      `uid` = '{$obj->id}' AND 
      `current_city` <> `stolen_city` AND 
      `current_city` = '{$obj->city}' AND 
      `sold` = '0'");
            if ($db->num_rows() >= 1) {
                $sum = 0;
                $ant = $db->num_rows();
                $pre = ($ant == 1) ? null : 'er';
                while ($b = mysqli_fetch_object($gc)) {
                    $sum = $sum + ($cartypes[$b->car_id]["price"]);
                    $db->query("UPDATE `garage` SET `sold` = '1' 
WHERE `uid` = '{$obj->id}' AND 
      `id` = '{$b->id}' LIMIT 1");
                }
                $db->query("UPDATE `users` SET `hand` = (`hand` + {$sum}) 
WHERE `id` = '{$obj->id}' LIMIT 1");
                echo lykket('Du har solgt ' . $ant . ' bil' . $pre . ' for ' . number_format($sum) . 'kr!');
            } else {
                echo feil('Ingen biler er klare til å selges i ' . city($obj->city) . '!');
            }
        }
    }
    $sql = "SELECT * FROM `garage` 
WHERE `uid` = '{$obj->id}' AND 
      `sold` = '0' AND 
      `transferred` >= '0' ORDER BY `id` DESC";
    if ($db->num_rows($db->query($sql)) >= 1) {
        echo '
        <form method="post" action="" id="bilfrakt">
        <table class="table">
        <tr>
        <th><input type="checkbox" id="velgalle" onchange="derp();">Velg</th><th>Bil</th><th>Stjålet i</th><th>Nåværende by</th><th>I bevegelse?</th>
        </tr>';
        $pagination = new Pagination($db, $sql, 20, 'p');
        $pagination_links = $pagination->GetPageLinks();
        $garsje = $pagination->GetSQLRows();
        foreach ($garsje as $garasjen) {
            if (!isset($high)) {
                $high = 0;
            } else {
                $high = $high + 1;
            }
            $biltime = $garasjen['transferred'] - time();
            echo '
            <tr class="biler">
            <td><input type="checkbox" name="checkbil[]" value="' . $garasjen['id'] . '"></td>
            <td>' . $cartypes[$garasjen["car_id"]]["name"] . '</td>
            <td>' . city($garasjen['stolen_city']) . '</td>
            <td>' . city($garasjen['current_city']) . '</td>
            <td>
            <span id="bil' . $garasjen['car_id'] . '"></span>
            <script>teller(' . $biltime . ',"bil' . $garasjen['car_id'] . '",false,"ned");</script></td>
            </tr>
            ';
        }
        echo '<tr><td colspan="5">' . $pagination_links . '</tr></td>';
        echo '
        </table>
        <h1>Valg:</h1>
        <p><input style="font-size: 12px;border: 1px solid #aaa;-webkit-border-radius: 10px;height: 25px;" type="submit" class="button2" name="sendbil" value="Send til valgt by!">
        <select style="-webkit-appearance: button;
-webkit-padding-end: 20px;border: 1px solid #AAA;color: #555;font-size: inherit;width: 107px;height: 20px;background-color: #aaa;" name="tilby">
        <option value="1">Til ' . city(1) . '</option>
        <option value="2">Til ' . city(2) . '</option>
        <option value="3">Til ' . city(3) . '</option>
        <option value="4">Til ' . city(4) . '</option>
        <option value="5">Til ' . city(5) . '</option>
        <option value="6">Til ' . city(6) . '</option>
        <option value="7">Til ' . city(7) . '</option>
        <option value="8">Til ' . city(8) . '</option>
        </select><br>
        <input class="button2" style="font-size: 12px;border: 1px solid #aaa;-webkit-border-radius: 10px;height: 25px;" type="submit" name="selgbil" value="Selg bilen!"><br>
<input type="submit" style="font-size: 12px;border: 1px solid #aaa;-webkit-border-radius: 10px;height: 25px;" class="button2" name="selgalle" value="Selg biler som er klare!"></p>
        </form>
        ';
    } else {
        echo '<p><em>Biler du har stjelt vil du finne her.</em></p>';
    }

    if (isset($_GET['verdier'])) {
        $now = time();
        $gc = $db->query("SELECT * FROM `garage` WHERE `uid` = '{$obj->id}' AND `sold` = '0'");
        if ($db->num_rows() >= 1) {
            $sum = 0;
            $ant = $db->num_rows();
            $pre = ($ant == 1) ? null : 'er';
            while ($b = mysqli_fetch_object($gc)) {
                $sum = $sum + ($cartypes[$b->car_id]["price"]);
            }
            echo '<p>Verdien for alle bilene dine totalt er: ' . number_format($sum) . 'kr!</p>';
            echo '<p>Du har totalt ' . $db->num_rows($gc) . ' bil' . $pre . ' i din garasje!';
        } else {
            echo feil('Du har ingen biler!');
        }
    }
    echo '</br><a class href="garasje.php?verdier">Sjekk verdiene på bilene!</a>';
}
?>
    <script>
        function derp() {
            if (document.getElementById('velgalle').checked == true) {
                a = document.getElementsByName("checkbil[]");
                for (index = 0; index < a.length; ++index) {
                    document.getElementsByName("checkbil[]")[index].checked = true;
                }
            } else {
                a = document.getElementsByName("checkbil[]");
                for (index = 0; index < a.length; ++index) {
                    document.getElementsByName("checkbil[]")[index].checked = false;
                }
            }
        }
    </script>
<?php
endpage();
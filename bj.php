<?php
include("core.php");
$failed = null;
startpage("Blackjack");
echo '<h1>Blackjack</h1>';
if (fengsel()) {
    $bu = fengsel(true);
    echo feil('Du er i fengsel, gjenst&aring;ende tid: <span id="fengsel">' . $bu . '</span>
<br>Du er ute kl. ' . date("H:i:s d.m.Y", (time() + $bu))) .
        '<script>teller(' . $bu . ', "fengsel", false, "ned");</script>';
} elseif (bunker()) {
    $bu = bunker(true);
    echo '
    <p class="feil">Du er i bunker, gjenst&aring;ende tid:
    <span id="bunker">' . $bu . '</span><br>Du er ute kl. ' . date("H:i:s d.m.Y", $bu) . '</p>
    <script>
    teller(' . ($bu - time()) . ', "bunker", false, "ned");
    </script>
    ';
} else {
    $suits = [
        "Spar", "Hjerter", "Kl&oslash;ver", "Ruter"
    ];
    $faces = [
        "To" => 2, "Tre" => 3, "Fire" => 4, "Fem" => 5, "Seks" => 6, "Syv" => 7, "&Aring;tte" => 8,
        "Ni" => 9, "Ti" => 10, "Knekt" => 10, "Dame" => 10, "Konge" => 10, "Ess" => 1
    ];
    function evaluateHand($hand)
    {
        global $faces;
        $value = 0;
        $hasEss = 0;
        foreach ($hand as $card) {
            if ($card['face'] == 'Ess') {
                $hasEss = $hasEss + 1;
            } else {
                $value = intval($value) + intval($faces[$card['face']]);
            }
        }
        if ($hasEss >= 1) {
            for ($i = 0; $i < $hasEss; $i++) {
                if ($value >= 11) {
                    $value = $value + 1;
                } else {
                    $value = $value + 11;
                }
            }
        }
        return $value;
    }
    
    $deck = [];
    foreach ($suits as $suit) {
        $keys = array_keys($faces);
        foreach ($keys as $face) {
            $deck[] = ['face' => $face, 'suit' => $suit];
        }
    }
    shuffle($deck);
    $hand = [];
    if (isset($_POST['price'])) {
        error_log('Starting BJ purchase script.');
        $s = $db->query("SELECT * FROM `bjtables` WHERE `uid` = '{$obj->id}'
                           AND `active` = '1' ORDER BY `id` DESC LIMIT 1");
        if ($db->num_rows() == 1) {
            error_log('Stopping BJ script, round is active!');
            echo feil('Du spiller allerede en runde,
            fullf&oslash;r den f&oslash;r du pr&oslash;ver &aring; starte et nytt spill!');
        } else {
            error_log('Continuing BJ script.');
            $pris = $db->escape($_POST['price']);
            error_log("Price is at: $pris");
            $pris = str_replace("kr", "", $pris);
            $pris = str_replace(",", "", $pris);
            $pris = str_replace(".", "", $pris);
            $pris = str_replace(" ", "", $pris);
            error_log("Price got converted to: $pris");
            if ($pris <= 99) {
                echo feil('Du m&aring; by 100kr eller mer');
            } elseif (!is_numeric($pris)) {
                echo feil('Du har ikke oppgitt en gyldig verdi! Tall er godkjent om f&oslash;lgende
                tegn er blant tallene: "kr", ",", "." og mellomrom.');
            } elseif ($pris > 750000000) {
                echo feil('Maks innsats per runde er p&aring; 750,000,000kr!');
            } elseif ($pris >= 100 && is_numeric($pris) && $pris <= 750000000) {
                if ($obj->hand <= ($pris - 1)) {
                    echo feil('Du har ikke s&aring; mye penger &aring; spille for. Sjekk at du har
                    minst 100 kr ute p&aring; handa!');
                } else {
                    error_log('Dealing cards...');
                    for ($i = 0; $i < 2; $i++) {
                        $hand[] = array_shift($deck);
                        $dealer[] = array_shift($deck);
                    }
                    $handstr = serialize($hand);
                    $deckstr = serialize($deck);
                    $dealerstr = serialize($dealer);
                    error_log("Inserting values into database.");
                    if (!$db->query("INSERT INTO `bjtables`(`uid`,`ucards`,`dcards`,`decks`,`timestamp`,`price`)
VALUES('$obj->id','$handstr','$dealerstr','$deckstr',UNIX_TIMESTAMP(),'$pris')")) {
                        if ($obj->status == 1) {
                            echo feil('Feil i query... ' . mysqli_error($db->con));
                        } else {
                            echo feil('Det var feil med db, rapporter problemet!');
                        }
                    } else {
                        error_log("Inserted into the database. Removing money from player hand.");
                        $db->query("UPDATE `users` SET `hand` = (`hand` - $pris) WHERE `id` = '{$obj->id}' LIMIT 1");
                    }
                    if (evaluateHand($hand) == 21) {
                        error_log("Player got a value of 21 on first!");
                        if (evaluateHand($dealer) == 21) {
                            error_log("Seems dealer also did! Returning money");
                            echo feil('Dere begge fikk 21, du fikk pengene tilbake!');
                            $db->query("UPDATE `users` SET `hand` = (`hand` + '$pris')
WHERE `id` = '$obj->id' LIMIT 1");
                            $db->query("UPDATE `bjtables` SET `active` = '1',`result` = '0'
WHERE `uid` = '$obj->id' AND `active` = '0' LIMIT 1");
                            $failed = true;
                        } else {
                            error_log("Well, dealer didn't get 21, user wins 3 times his start.");
                            $price3 = $pris * 3;
                            $db->query("UPDATE `users` SET `hand` = (`hand` + $price3)
WHERE `id` = '$obj->id' LIMIT 1");
                            $db->query("UPDATE `bjtables` SET `active` = '0',`result` = '$price3'
WHERE `uid` = '$obj->id' AND `active` = '1' LIMIT 1");
                            $failed = true;
                        }
                    }
                }
            }
        }
    }
    ?>
    <form method="post"
          action="bj.php">
        <table class="table bj">
            <thead>
            <tr>
                <th>Blackjack bordet</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <table class="table bj">
                        <tr>
                            <td>
                                <?php
                                $db->query("SELECT * FROM `bjtables`
WHERE `uid` = '{$obj->id}' AND `active` = '1' LIMIT 1");
                                $activeRound = 0;
                                if ($db->num_rows() == 1) {
                                    $activeRound = 1;
                                    $arraycards = $db->fetch_object();
                                    echo lykket('Innsats: ' . number_format($arraycards->price));
                                    $hand = unserialize($arraycards->ucards);
                                    $dealer = unserialize($arraycards->dcards);
                                    $deck = unserialize($arraycards->decks);
                                    if (isset($_POST['stand'])) {
                                        error_log("User used 'Stand'");
                                        while (evaluateHand($dealer) < 17) {
                                            $dealer[] = array_shift($deck);
                                        }
                                        error_log("Checking.. User: " . evaluateHand($hand) . " Dealer: " .
                                            evaluateHand($dealer));
                                        if (evaluateHand($dealer) >= 22) {
                                            error_log("Dealer drew above 21...");
                                            echo lykket('Dealeren fikk over 21, du vant!');
                                            $price = $arraycards->price * 2;
                                            $db->query("UPDATE `bjtables`
SET `active` = '0',`result` = '$price',`dcards` = '" . serialize($dealer) . "'
WHERE `uid` = '$obj->id' AND `active` = '1' ORDER BY `id` DESC LIMIT 1");
                                            $db->query("UPDATE `users`
SET `hand` = (`hand` + $price)
WHERE `id` = '$obj->id' LIMIT 1");
                                            $failed = true;
                                        } elseif ((evaluateHand($hand) == 21 && evaluateHand($dealer) == 21) ||
                                            evaluateHand($hand) == evaluateHand($dealer)) {
                                            error_log("Both got 21 or ended with the same amount... Draw");
                                            echo lykket('Dere fikk likt, du fikk pengene tilbake.');
                                            $db->query("UPDATE `bjtables`
SET `active` = '0',`result` = '0',`dcards` = '" . serialize($dealer) . "'
WHERE `uid` = '$obj->id' AND `active` = '1' ORDER BY `id` DESC LIMIT 1");
                                            $db->query("UPDATE `users`
SET `hand` = (`hand` + $arraycards->price)
WHERE `id` = '$obj->id' LIMIT 1");
                                            $failed = true;
                                        } elseif ((evaluateHand($dealer) <= 21) && evaluateHand($dealer) >
                                            evaluateHand($hand)) {
                                            error_log("Dealer got more than player, player lost");
                                            echo feil('Dealeren hadde h&oslash;yere enn deg, du tapte!');
                                            $db->query("UPDATE `bjtables`
SET `active` = '0',`result` = '-$arraycards->price',`dcards` = '" . serialize($dealer) . "'
WHERE `uid` = '$obj->id' AND `active` = '1' ORDER BY `id` DESC LIMIT 1");
                                            $failed = true;
                                        } elseif (evaluateHand($dealer) < evaluateHand($hand)) {
                                            error_log("Player won, more than dealer");
                                            $price = $arraycards->price * 2;
                                            echo lykket('Du fikk h&oslash;yere enn dealeren,
                                            du vant ' . number_format($price) . ' kr!');
                                            $db->query("UPDATE `bjtables`
SET `active` = '0',`result` = '$price',`dcards` = '" . serialize($dealer) . "'
WHERE `uid` = '$obj->id' AND `active` = '1' ORDER BY `id` DESC LIMIT 1");
                                            $db->query("UPDATE `users`
SET `hand` = (`hand` + $price) WHERE `id` = '$obj->id' LIMIT 1");
                                            $failed = true;
                                        } else {
                                            error_log("Odd, we shouldn't have landed here...");
                                        }
                                    } elseif (isset($_POST['newcard'])) {
                                        $dealer = unserialize($arraycards->dcards);
                                        $hand = unserialize($arraycards->ucards);
                                        $deck = unserialize($arraycards->decks);
                                        if (evaluateHand($hand) >= 22) {
                                            $failed = true;
                                        } else {
                                            $hand[] = array_shift($deck);
                                            $handin = serialize($hand);
                                            $deckin = serialize($deck);
                                            if (evaluateHand($hand) >= 22) {
                                                echo feil('Du fikk over 21, du tapte!');
                                                if (!$db->query("UPDATE `bjtables`
SET `ucards` = '$handin',`decks` = '$deckin',`active` = '0',`result` = '-$arraycards->price'
WHERE `uid` = '$obj->id' AND `active` = '1' ORDER BY `id` DESC LIMIT 1")) {
                                                    echo warning('Kunne ikke oppdatere!!! Meld til ledelsen!');
                                                }
                                                $failed = true;
                                            } else {
                                                if ($db->query("UPDATE `bjtables`
SET `ucards` = '$handin',`decks` = '$deckin'
WHERE `uid` = '$obj->id' AND `active` = '1' ORDER BY `id` DESC LIMIT 1")) {
                                                    echo lykket('Du trakk et nytt kort!');
                                                } else {
                                                    echo feil('Kunne ikke trekke nytt kort pga db!');
                                                }
                                            }
                                        }
                                    }
                                    $tastatur = ["Ess" => 1, "To" => 2, "Tre" => 3, "Fire" => 4, "Fem" => 5,
                                        "Seks" => 6, "Syv" => 7, "&Aring;tte" => 8, "Ni" => 9, "Ti" => 10,
                                        "Knekt" => 11, "Dame" => 12, "Konge" => 13];
                                    foreach ($hand as $index => $card) {
                                        if ($card['suit'] == "Hjerter") {
                                            $img = '<img src="spillkort/h' . $tastatur[$card['face']] . '.png"
                                            alt="Hjerter ' . $tastatur[$card['face']] . '">';
                                        } elseif ($card['suit'] == "Kl&oslash;ver") {
                                            $img = '<img src="spillkort/k' . $tastatur[$card['face']] . '.png"
                                            alt="Kl&oslash;ver' . $tastatur[$card['face']] . '">';
                                        } elseif ($card['suit'] == "Ruter") {
                                            $img = '<img src="spillkort/r' . $tastatur[$card['face']] . '.png"
                                            alt="Ruter ' . $tastatur[$card['face']] . '">';
                                        } elseif ($card['suit'] == "Spar") {
                                            $img = '<img src="spillkort/s' . $tastatur[$card['face']] . '.png"
                                            alt="Spar ' . $tastatur[$card['face']] . '">';
                                        }
                                        echo $img;
                                    }
                                    if (evaluateHand($hand) >= 22) {
                                        $db->query("UPDATE `bjtables` SET `active` = '0'
WHERE `uid` = '$obj->id' AND active = '1'
ORDER BY `id` DESC LIMIT 1");
                                    }
                                    echo lykket('Sum av kortene: ' . evaluateHand($hand));
                                    echo '
                <tr>
                <td style="text-align: center;">
                <input type="submit" value="Nytt kort!" name="newcard">
                <input type="submit" value="St&aring;" name="stand"></td>
                </tr>';
                                } else {
                                    echo '
                <form method="post" action="">
                <h3>Innskudd:</h3><br>
                <input type="text" name="price" value="100,000kr"><input class="spill" type="submit" value="Spill!">
                </form>';
                                }
                                ?></td>
                        </tr>
                        <tr>
                            <td>
                                <?php
                                if ($activeRound == 1) {
                                    if (!isset($_POST['stand']) || !$failed) {
                                        if ($dealer[0]['suit'] == "Hjerter") {
                                            $img = '<img src="spillkort/h' . $tastatur[$dealer[0]['face']] . '.png"
                                            alt="Hjerter ' . $tastatur[$dealer[0]['face']] . '">';
                                        } elseif ($dealer[0]['suit'] == "Kl&oslash;ver") {
                                            $img = '<img src="spillkort/k' . $tastatur[$dealer[0]['face']] . '.png"
                                            alt="Kl&oslash;ver ' . $tastatur[$dealer[0]['face']] . '">';
                                        } elseif ($dealer[0]['suit'] == "Ruter") {
                                            $img = '<img src="spillkort/r' . $tastatur[$dealer[0]['face']] . '.png"
                                            alt="Ruter ' . $tastatur[$dealer[0]['face']] . '">';
                                        } elseif ($dealer[0]['suit'] == "Spar") {
                                            $img = '<img src="spillkort/s' . $tastatur[$dealer[0]['face']] . '.png"
                                            alt="Spar ' . $tastatur[$dealer[0]['face']] . '">';
                                        }
                                        $img .= '<img src="spillkort/cb.png" alt="">';
                                        echo $img;
                                    } else {
                                        foreach ($dealer as $index => $card) {
                                            if ($card['suit'] == "Hjerter") {
                                                $img = '<img src="spillkort/h' . $tastatur[$card['face']] . '.png"
                                                alt="Hjerter ' . $tastatur[$dealer[0]['face']] . '">';
                                            } elseif ($card['suit'] == "Kl&oslash;ver") {
                                                $img = '<img src="spillkort/k' . $tastatur[$card['face']] . '.png"
                                                alt="Kl&oslash;ver ' . $tastatur[$dealer[0]['face']] . '">';
                                            } elseif ($card['suit'] == "Ruter") {
                                                $img = '<img src="spillkort/r' . $tastatur[$card['face']] . '.png"
                                                alt="Ruter ' . $tastatur[$dealer[0]['face']] . '">';
                                            } elseif ($card['suit'] == "Spar") {
                                                $img = '<img src="spillkort/s' . $tastatur[$card['face']] . '.png"
                                                alt="Spar ' . $tastatur[$dealer[0]['face']] . '">';
                                            }
                                            echo $img;
                                        }
                                        echo feil('Dealerens sum: ' . evaluateHand($dealer));
                                    }
                                }
                                ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
    <a style="margin-left: 15px;"
       href='bj.php'>Ny runde</a>
    <br>
    <a style="margin-left: 15px;"
       href="bjstats.php">Statistikk over tidligere runder</a>
    <br>
    <a style="margin-left: 15px;display:none;"
       href="bjrunder.php">Vis resultatet av tidligere runder(med kort)</a>
    <?php
}
endpage();
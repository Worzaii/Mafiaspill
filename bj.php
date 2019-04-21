<?php
include("core.php");
if (bunker() == true) {
    startpage("Blackjack");
    $bu = bunker(true);
    echo '<h1>Blackjack</h1>
    <p class="feil">Du er i bunker, gjenst&aring;ende tid: <span id="bunker">' . $bu . '</span><br>Du er ute kl. ' . date("H:i:s d.m.Y", $bu) . '</p>
    <script>
    teller('.($bu - time()).',\'bunker\',false,\'ned\');
    </script>
    ';
} else if (fengsel()) {
    startpage("Blackjack");
    $ja = fengsel(true);
    echo '<h1>Blackjack</h1>
    <p class="feil">Du er i fengsel, gjenst&aring;ende tid: <span id="krim">'.$ja.'</span></p>
    <script>
    teller(' . $ja . ')
    </script>
    ';
} else {
    include("inc/gamefunctions.php");
    startpage("Blackjack");
/* Setter opp navnene p&aring; deckene */
    $suits = array(
    "Spar", "Hjerter", "Kl&oslash;ver", "Ruter"
    );
/* S&aring; setter vi opp verdiene p&aring; alle kortene */
    $faces = array(
    "To"=>2, "Tre"=>3, "Fire"=>4, "Fem"=>5, "Seks"=>6, "Syv"=>7, "&aring;tte"=>8,
    "Ni"=>9, "Ti"=>10, "Knekt"=>10, "Dame"=>10, "Konge"=>10, "Ess"=>1
    );
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
/* S&aring; bruker vi foreach til &aring; sette opp hele kortstokken istedenfor &aring; gj&oslash;re dette manuelt selv. */
    $deck = array();
    foreach ($suits as $suit) {
        $keys = array_keys($faces);
        foreach ($keys as $face) {
            $deck[] = array('face' => $face, 'suit' => $suit);
        }
    }
/* Deretter blander vi opp kortstokken, da alle kort blir plassert hulter til bulter */
    shuffle($deck);
    $hand = array();
/* Her startes en ny runde, hvis spilleren definerte en pengesum &aring; spille for */
    if (isset($_POST['price'])) {
        /*Kasser inn og legger ut kort p&aring; bordet.*/
        $s = $db->query("SELECT * FROM `bjtables` WHERE `uid` = '{$obj->id}' AND `round` = '0' ORDER BY `id` DESC LIMIT 1");
        if ($db->num_rows($s) == 1) {
            echo '<p class="feil">Du spiller allerede en runde, fullf&oslash;r den f&oslash;r du pr&oslash;ver &aring; starte et nytt spill!</p>';
        } else {
            /*Spilleren er i en runde*/
            $pris = $db->escape($_POST['price']);
            $pris = str_replace("kr", "", $pris);
            $pris = str_replace(",", "", $pris);
            $pris = str_replace(".", "", $pris);
            $pris = str_replace(" ", "", $pris);
            if ($pris <= 99) {
                echo '<p class="feil">Du m&aring; by 100kr eller mer</p>';
            } else if (!is_numeric($pris)) {
                echo '<p class="feil">Du har ikke oppgitt en gyldig verdi! Tall er godkjent om f&oslash;lgende tegn er blant tallene: "kr", ",", "." og mellomrom.</p>';
            } else if ($pris > 750000000) {//Tillater en maksgrense p&aring; 750,000,000kroners innsats.
                echo '<p class="feil">Maks innsats per runde er p&aring; 750,000,000kr!</p>';
            } else if ($pris >= 100 && is_numeric($pris) && $pris <= 750000000) {
                if ($obj->hand <= ($pris - 1)) {
                    echo '<p class="feil">Du har ikke s&aring; mye penger &aring; spille for. Sjekk at du har minst 100 kr ute p&aring; handa!</p>';
                } else {
                    for ($i = 0; $i < 2; $i++) {
                        $hand[] = array_shift($deck);
                        $dealer[] = array_shift($deck);
                    }
                    $handstr = serialize($hand);
                    $deckstr = serialize($deck);
                    $dealerstr = serialize($dealer);
                    $timenow = time();
                    if (!$db->query("INSERT INTO `bjtables`(`uid`,`ucards`,`dcards`,`decks`,`time`,`price`) VALUES('$obj->id','$handstr','$dealerstr','$deckstr','$timenow','$pris')")) {
                        if ($obj->status == 1) {
                            echo '<p class="feil">Feil i query... ' . mysqli_error($db->con) . '</p>';
                        } else {
                            echo '<p class="feil">Det var feil med db, rapporter problemet!</p>';
                        }
                    } else {
                        $db->query("UPDATE `users` SET `hand` = (`hand` - $pris) WHERE `id` = '$obj->id' LIMIT 1");
                    }
                    if (evaluateHand($hand) == 21) {
                        /*Spiller fikk 21 p&aring; f&oslash;rste, vinner om dealer ikke fikk 21.*/
                        if (evaluateHand($dealer) == 21) {
                            echo '<p class="feil">Dere begge fikk 21, du fikk pengene tilbake!</p>';
                            $db->query("UPDATE `users` SET `hand` = (`hand` + '$pris') WHERE `id` = '$obj->id' LIMIT 1");
                            $db->query("UPDATE `bjtables` SET `round` = '1',`result` = '0' WHERE `uid` = '$obj->id' AND `round` = '0' LIMIT 1");
                            $failed = true;
                        } else {
                            /*21 p&aring; f&oslash;rste og dealeren under, vil si 3 ganger innsats*/
                            $price3 = $pris * 3;
                            $db->query("UPDATE `users` SET `hand` = (`hand` + $price3) WHERE `id` = '$obj->id' LIMIT 1");
                            $db->query("UPDATE `bjtables` SET `round` = '1',`result` = '$price3' WHERE `uid` = '$obj->id' AND `round` = '0' LIMIT 1");
                            if ($db->num_rows() == 1) {/*Sjekker om oppdrag 5 er aktivt*/
                                $db->query("UPDATE `oppuid` SET `tms` = (`tms` + 1) WHERE `uid` = '{$obj->id}' AND `done` = '0' AND `tms` < '1' AND `oid` = '5' LIMIT 1");
                            }
                            $failed = true;
                        }
                    }
                }
            }
        }
    }/*Starter runde!*/
    ?>
<form method="post" action="bj.php">
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
                            //Henter inn n&aring;v&aelig;rende runde, om det er noen.
                            $db->query("SELECT * FROM `bjtables` WHERE `uid` = '{$obj->id}' AND `round` = '0' LIMIT 1");
                            $activeRound = 0;
                            if ($db->num_rows() == 1) {
                                $activeRound = 1;
                                $arraycards = $db->fetch_object();
                                echo '<p class="lykket">Innsats: ' . number_format($arraycards->price) . '</p>';
                                $hand = unserialize($arraycards->ucards);
                                $dealer = unserialize($arraycards->dcards);
                                $deck = unserialize($arraycards->decks);
                                if (isset($_POST['stand'])) {
                                    while (evaluateHand($dealer) < 17) {
                                        $dealer[] = array_shift($deck);
                                    }
                                    if (evaluateHand($dealer) >= 22) {
                                        echo '<p class="lykket">Dealeren fikk over 21, du vant!</p>';
                                        $price = $arraycards->price * 2;
                                        $db->query("UPDATE `bjtables` SET `round` = '1',`result` = '$price',`dcards` = '" . serialize($dealer) . "' WHERE `uid` = '$obj->id' AND `round` = '0' ORDER BY `id` DESC LIMIT 1");
                                        $db->query("UPDATE `users` SET `hand` = (`hand` + $price) WHERE `id` = '$obj->id' LIMIT 1");
                                        $failed = true;
                                    } else if (evaluateHand($hand) == 21 && evaluateHand($dealer) == 21 || evaluateHand($hand) == evaluateHand($dealer)) {
                                        echo '<p class="lykket">Dere fikk likt, du fikk pengene tilbake.</p>';
                                        $db->query("UPDATE `bjtables` SET `round` = '1',`result` = '0',`dcards` = '" . serialize($dealer) . "' WHERE `uid` = '$obj->id' AND `round` = '0' ORDER BY `id` DESC LIMIT 1");
                                        $db->query("UPDATE `users` SET `hand` = (`hand` + $arraycards->price) WHERE `id` = '$obj->id' LIMIT 1");
                                        $failed = true;
                                    } else if (evaluateHand($dealer) <= 21 && evaluateHand($dealer) > evaluateHand($hand)) {
                                        /*Dealer hadde h&oslash;yere enn spiller*/
                                        echo '<p class="feil">Dealeren hadde h&oslash;yere enn deg, du tapte!</p>';
                                        $db->query("UPDATE `bjtables` SET `round` = '1',`result` = '-$arraycards->price',`dcards` = '" . serialize($dealer) . "' WHERE `uid` = '$obj->id' AND `round` = '0' ORDER BY `id` DESC LIMIT 1");
                                        $failed = true;
                                    } else if (evaluateHand($dealer) < evaluateHand($hand)) {
                                        $price = $arraycards->price * 2;
                                        echo '<p class="lykket">Du fikk h&oslash;yere enn dealeren, du vant ' . number_format($price) . ' kr!</p>';
                                        $db->query("UPDATE `bjtables` SET `round` = '1',`result` = '$price',`dcards` = '" . serialize($dealer) . "' WHERE `uid` = '$obj->id' AND `round` = '0' ORDER BY `id` DESC LIMIT 1");
                                        $db->query("UPDATE `users` SET `hand` = (`hand` + $price) WHERE `id` = '$obj->id' LIMIT 1");
                                        $failed = true;
                                    }
                                } else if (isset($_POST['newcard'])) {
                                    $dealer = unserialize($arraycards->dcards);
                                    $hand = unserialize($arraycards->ucards);
                                    $deck = unserialize($arraycards->decks);
                                    if (evaluateHand($hand) >= 22) {
                                        //echo '<p class="feil">Du fikk over 21, og har dermed tapt!</p>';
                                        $failed = true;
                                    } else {
                                        $hand[] = array_shift($deck);
                                        $handin = serialize($hand);
                                        $deckin = serialize($deck);
                                        if (evaluateHand($hand) >= 22) {
                                            echo '<p class="feil">Du fikk over 21, du tapte!</p>';
                                            if (!$db->query("UPDATE `bjtables` SET `ucards` = '$handin',`decks` = '$deckin',`round` = '1',`result` = '-$arraycards->price' WHERE `uid` = '$obj->id' AND `round` = '0' ORDER BY `id` DESC LIMIT 1")) {
                                                echo mysql_error();
                                            }
                                            $failed = true;
                                        } else {
                                            if ($db->query("UPDATE `bjtables` SET `ucards` = '$handin',`decks` = '$deckin' WHERE `uid` = '$obj->id' AND `round` = '0' ORDER BY `id` DESC LIMIT 1")) {
                                                echo '<p class="lykket">Du trakk et nytt kort!</p>';
                                            } else {
                                                echo '<p class="feil">Kunne ikke trekke nytt kort pga db!</p>';
                                            }
                                        }
                                    }
                                }
                                $tastatur = array("Ess" => 1, "To" => 2, "Tre" => 3, "Fire" => 4, "Fem" => 5, "Seks" => 6, "Syv" => 7, "&aring;tte" => 8, "Ni" => 9, "Ti" => 10, "Knekt" => 11, "Dame" => 12, "Konge" => 13);
                                foreach ($hand as $index => $card) {
                /*Henter fram bilder, og sorterer de etter kode*/
                                    if ($card['suit'] == "Hjerter") {
                                        $img = '<img src="spillkort/h' . $tastatur[$card['face']] . '.png" alt="Hjerter ' . $tastatur['face'] . '">';
                                    } else if ($card['suit'] == "Kl&oslash;ver") {
                                        $img = '<img src="spillkort/k' . $tastatur[$card['face']] . '.png" alt="Kl&oslash;ver' . $tastatur['face'] . '">';
                                    } else if ($card['suit'] == "Ruter") {
                                        $img = '<img src="spillkort/r' . $tastatur[$card['face']] . '.png" alt="Ruter ' . $tastatur['face'] . '">';
                                    } else if ($card['suit'] == "Spar") {
                                        $img = '<img src="spillkort/s' . $tastatur[$card['face']] . '.png" alt="Spar ' . $tastatur['face'] . '">';
                                    }
                                    echo $img;
                                }
                                if (evaluateHand($hand) >= 22) {
                                    //echo '<p class="feil">Du fikk over 21, og har dermed tapt!</p>';
                                    $db->query("UPDATE `bjtables` SET `round` = '1' WHERE `uid` = '$obj->id' ORDER BY `id` DESC LIMIT 1");
                                }
                                echo '<p class="lykket">Sum av kortene: ' . evaluateHand($hand) . '</p>';
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
                                    /*Henter fram bilder, og sorterer de etter kode*/
                                    if ($dealer[0]['suit'] == "Hjerter") {
                                        $img = '<img src="spillkort/h' . $tastatur[$dealer[0]['face']] . '.png" alt="Hjerter ' . $tastatur[$dealer[0]['face']] . '">';
                                    } else if ($dealer[0]['suit'] == "Kl&oslash;ver") {
                                        $img = '<img src="spillkort/k' . $tastatur[$dealer[0]['face']] . '.png" alt="Kl&oslash;ver ' . $tastatur[$dealer[0]['face']] . '">';
                                    } else if ($dealer[0]['suit'] == "Ruter") {
                                        $img = '<img src="spillkort/r' . $tastatur[$dealer[0]['face']] . '.png" alt="Ruter ' . $tastatur[$dealer[0]['face']] . '">';
                                    } else if ($dealer[0]['suit'] == "Spar") {
                                        $img = '<img src="spillkort/s' . $tastatur[$dealer[0]['face']] . '.png" alt="Spar ' . $tastatur[$dealer[0]['face']] . '">';
                                    }
                                    $img .= '<img src="spillkort/cb.png" alt="">';
                                    echo $img;
                                } else {
                                    foreach ($dealer as $index => $card) {
                                        /*Henter fram bilder, og sorterer de etter kode*/
                                        if ($card['suit'] == "Hjerter") {
                                            $img = '<img src="spillkort/h' . $tastatur[$card['face']] . '.png" alt="Hjerter ' . $tastatur[$dealer[0]['face']] . '">';
                                        } else if ($card['suit'] == "Kl&oslash;ver") {
                                            $img = '<img src="spillkort/k' . $tastatur[$card['face']] . '.png" alt="Kl&oslash;ver ' . $tastatur[$dealer[0]['face']] . '">';
                                        } else if ($card['suit'] == "Ruter") {
                                            $img = '<img src="spillkort/r' . $tastatur[$card['face']] . '.png" alt="Ruter ' . $tastatur[$dealer[0]['face']] . '">';
                                        } else if ($card['suit'] == "Spar") {
                                            $img = '<img src="spillkort/s' . $tastatur[$card['face']] . '.png" alt="Spar ' . $tastatur[$dealer[0]['face']] . '">';
                                        }
                                        echo $img;
                                    }
                                    echo '<p class="feil">Dealerens sum: ' . evaluateHand($dealer) . '</p>';
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
<a style="margin-left: 15px;" href='bj.php'>Ny runde</a>
<br>
<a style="margin-left: 15px;" href="bjstats.php">Statistikk over tidligere runder</a>
<br>
<a style="margin-left: 15px;display:none;" href="BjRounds">Vis resultatet av tidligere runder(med kort)</a>
    <?php
}
endpage();
<?php
include("core.php");
startpage("Profil");
if (!isset($_GET['id'])) {
    echo '
<p>Denne profilen kan ikke vises. Vennligst s&oslash;k p&aring; en spiller for &aring; se profilen. S&oslash;kefunksjonen kommer s&aring; snart profilscriptet er klart.</p>
';
} elseif (isset($_GET['id'])) {
//Profilen fungerer
    $id = $db->escape($_GET['id']);
    if (!is_numeric($id)) {
        echo feil('Denne id-en er ikke godkjent!');
    } else {
        $profil = $db->query("SELECT * FROM `users` WHERE `id` = '$id' LIMIT 1");
        if ($db->num_rows() == 1) {
            $fetch = $db->fetch_object($profil);
            $timeuser = time() - $fetch->lastactive;
            list($ranknr, $ranknm, $exper) = rank($fetch->exp);
            unset($ranknr);
            if (r1()) {
                $experience = "({$fetch->exp})";
            }
            if ($fetch->image == null || $fetch->image == '') {
                $image = "imgs/nopic.png";
            } else {
                $image = htmlentities(filter_var($fetch->image, FILTER_SANITIZE_URL));
            }
            $regd = ($fetch->regdato != 0) ? date("H:i:s | d-m-Y", $fetch->regdato) : '<em>Ukjent registreringsdato</em>';
            $var = [1 => "<span class='stat1' title='Admin'>Admin</span>", 2 => "<span class='stat2' title='Moderator'>Moderator</span>", 3 => "<span class='stat3' title='Forum moderator'>Forum Moderator</span>", 4 => "<span class='stat4'>Picmaker</span>", 5 => "<span class='stat5' title='Vanlig spiller'>Vanlig spiller</span>", 6 => "<span class='stat6' title='D&oslash;d'>D&oslash;d</span>"];
            $status = $var[$fetch->status];
            if ($fetch->health == 0) {
                $status = '<span style="color:#ff0000">D&oslash;d</span>';
            }
            $ute = number_format($fetch->hand);
            $sendtmld = number_format($fetch->sendtmld);
            if ($sendtmld == 0) {
                $sendtmld = 'Ingen meldinger sendt';
            }
            $lasttime = ($fetch->lastactive == 0) ? "Ingen aktivitet siden registrering!" :
                date("H:i:s | d-m-Y", $fetch->lastactive);
            $db->query("SELECT CHAR_LENGTH(`note`) as `lengde`,`note` FROM `users` WHERE `id` = '$fetch->id' LIMIT 1;");
            $res = $db->fetch_object();
            $havenote = ($res->lengde >= 1) ? 1 : 0;
            $exbut = (r1() || r2()) ? '<a class="button" href="profil.php?id=' . $fetch->id . '&note">Se notater p&aring; bruker!(' . $havenote . ')</a>' : null;
            $familie = famidtoname($fetch->family, 1);
            if ($fetch->family == null) {
                $familie = "<i>ingen</i>";
            } else {
                $familie = famidtoname($fetch->family, 1);
            }
            if (isset($_GET['note'])) {
                /*Vis notat p&aring; bruker*/
                if (isset($_POST['norte'])) {
                    $id = $db->escape($_POST['norte']);
                    $db->query("UPDATE `users` SET `note` = '" . $db->escape($_POST['norte']) . "' WHERE `id` = '" . $fetch->id . "' LIMIT 1");
                    $db->query("UPDATE `users` SET `noteav` = '" . $obj->user . "' WHERE `id` = '" . $fetch->id . "' LIMIT 1");
                    lykket("Notatene ble oppdatert!");
                    $fetch->note = htmlentities($_POST['norte'], null, "ISO-8859-1");
                } else {
                    echo "<p class=\"feil\">Hold det s&aring; kort som mulig, unng&aring; enter og lang tekst.</br></p>";
                }
                echo '<form method="post" action=""><textarea style="height:140px; width:430px;" name="norte">' . ($fetch->note) . '</textarea><input type="submit" value="Lagre"></form>';
            }
            if (r1() || r2()) {
                echo '<p>' . (($obj->status == 1) ? "<a href=\"stilling.php?nick='.$fetch->user.'\">Sett stilling</a> || " : null) . '<a href="ban_user.php?kill=' . $fetch->id . '">Modkill Spilleren</a> || <a href="forumban.php?ban=' . $fetch->id . '">Forumban Spilleren</a> || <a href="profil.php?id=' . $fetch->id . '&note">Endre notat p&aring; spiller</a>' . (($obj->status == 1) ? " || <a href=\"endrespiller.php?u=" . $fetch->user . "\">Endre verdier</a>" : null) . '</p>';
//echo '<a href="profil.php?id='.$fetch->id.'&bank">Se 50 siste bank overf&oslash;ringer fra brukeren</a></br>';
                $fetch->note = html_entity_decode($fetch->note, null, "ISO-8859-1");
                echo '<p style="text-align:center;cursor:pointer;font-weight:bold" title="Trykk p&aring; meg!" onclick="$(\\' . bbcodes($fetch->note, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0) . '</div>';
            }
            echo "

<table style=\"width:310px;margin-top: 60px;\" class=\"table ekstra\">
<tr>
</tr>
<tr>
<td style=\"text-align:center;\" colspan=\"2\" class=\"img\"><img src=\"{$image}\" style=\"width:250px;height:250px;text-align:center\"></td>
</tr>
<tr><th colspan=\"2\">Om</th></tr>
<tr>
<td>Nick:</td><td><a href=\"innboks.php?ny&usertoo=$fetch->user\">" . status($fetch->id, 1) . "</a></td>
</tr>
<tr>
<td>Sist p&aring;logget:</td><td>{$lasttime}</td>
</tr>
<tr>
<td>Tid siden sist:</td><td><span id=\"usertime\"></span></td>
</tr>
<tr>
<td>Dato registrert:</td><td>{$regd}</td>
</tr>
<tr>
<td>Penger ute:</td><td>{$ute} kr</td>
</tr>
<tr>
<td>Meldinger sendt:</td><td>{$sendtmld}</td>
</tr>
<tr>
<td>Rank:</td><td>{$ranknm}{$experience}</td>
</tr>
<!--<tr>
<td>Pengerank:</td><td>Venter p&aring; sql</td>-->
</tr>
<tr>
<td>Familie:</td><td>$familie</td>
</tr>
<tr>
<td>Status:</td><td>{$status}</td>
</tr>
";
            if (r1() || r2()) {
                $db->query("SELECT `user_agent` FROM `sessusr` WHERE `uid` = '{$fetch->id}' ORDER BY `id` DESC LIMIT 1");
                if ($db->num_rows() == 1) {
                    $desuyo = $db->fetch_object();
                    $harikke = $desuyo->user_agent;
                } else {
                    $harikke = "Har ikke logget inn enda.";
                }
                $harikke = ($obj->status > 1 && $fetch->status == 1) ? "***" : $harikke;
                echo '<tr><th colspan="2">Moderator & Admin info</th></tr><tr>
<td>Mail:</td><td>' . $fetch->mail . '</td></tr>
<tr><td>By:</td><td>' . city($fetch->city) . '</td></tr>
<tr><td>Bank:</td><td>' . number_format($fetch->bank) . ' kr</td></tr>
<tr><td>Liv:</td><td>' . $fetch->health . '%</td></tr>
<tr><td>Kuler:</td><td>' . $fetch->bullets . '</td></tr>
<tr><td>Poeng:</td><td>' . $fetch->points . '</td></tr>
<tr><td>Oppf&oslash;rt IP:</td><td>' . (($obj->status > 1) ? "***" : (($fetch->regip != null) ? $fetch->regip : "<i>Ikke registrert</i>")) . '</td></tr>
<tr><td>Sist brukte IP:</td><td>' . (($obj->status > 1) ? "***" : (($fetch->ip != null) ? $fetch->ip : "<i>Ikke registrert</i>")) . '</td></tr>
<tr><td>Oppf&oslash;rt hostname:</td><td>' . (($obj->status > 1) ? "***" : (($fetch->reghostname != null) ? $fetch->reghostname : "<i>Ikke registrert</i>")) . '</td></tr>
<tr><td>Siste hostname:</td><td>' . (($obj->status > 1) ? "***" : (($fetch->hostname != null) ? $fetch->hostname : "<i>Ikke registrert</i>")) . '</td></tr><tr>
<td colspan="2"><a href="http://browscap.org/ua-lookup">Nettleser:</a><br>' . $harikke . '</td>  
</tr>
';
            }
            echo "
</table>
<br>

<div class=\"profiltekst\">
";
            $profil = bbcodes($fetch->profile, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0);
            echo $profil;
            echo '
</div>
<script>
teller(' . $timeuser . ';)
</script>
';
        } else {
            echo '
<p class="feil">Det var ikke funnet noen bruker med id: ' . htmlentities($id) . '! Bruk s&oslash;kefunksjonen <a href="finnspiller.php">Finn spiller</a> for &aring; finne spillere.</p>
';
        }
    }
}
?>
<?php
endpage();

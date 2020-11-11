<?php
ob_start();
include("core.php");
include("pagination.php");
startpage("Forumet");
/*
 @author Slowboii
 @date 01.11.2013
 */
$forums = array(
    1 => array('navn' => "Generelt", 'id' => 1),
    2 => array('navn' => "Salg & Søknad", 'id' => 2),
    3 => array('navn' => "Offtopic", 'id' => 3),
    4 => array('navn' => "Ledelsen", 'id' => 4),
    5 => array('navn' => "Gjengforum", 'id' => 5)
); // Typer forum
if (isset($_GET['type'])) {
    if (isset($_POST['sopprett'])) {
        header("Location: nyforum.php?opprett");
    }
    $id = $db->escape($_GET['type']);
    if ($forums[$id]['id'] == '5') { // Ikke tilgang til familie forum, om ikke din familie.
        if ($obj->family == 0) {
            echo feil('Du er ikke medlem i en familie.');
        } else {
            $sql = "SELECT * FROM `forum` WHERE `famid` = '$obj->family' AND `slettet` = '0' ORDER BY `lasttime` DESC";
        }
    } elseif ($forums[$id]['id']) {
        // $query = $db->query("SELECT * FROM `forum` WHERE `type` = {$forums[$id]['id']} AND `slettet` = '0' ORDER BY `id` DESC");
        $sql = "SELECT * FROM `forum` WHERE `type` = {$forums[$id]['id']} AND `slettet` = '0' ORDER BY `lasttime` DESC";
    } elseif ($forums[$id]['id'] == '4' && !r1() && !r2()) {
        noaccess();
    } // Ingen tilgang til ledelsen forumet om man ikke er admin/mod.
    if ($db->num_rows($db->query($sql)) == 0) {
        echo '<form action="" method="POST">
  <input type="submit" name="sopprett" value="Opprett tråd!">
</form><p class="feil">Ingen tråder er opprettet i forumet.</p>';
    } else {
        ?>
        <form action="" method="POST">
            <input type="submit" name="sopprett" value="Opprett tråd!">
        </form>
        <table class="table">
            <tr>
                <th colspan="5"><?= $navn ?></th>
            </tr>
            <tr>
                <td>Tema</td>
                <td>Trådstarter</td>
                <td>Opprettet</td>
                <td>Sist svar av</td>
            </tr>
            <?php
            $pagination = new Pagination($db, $sql, 20, 'p');
            $pagination_links = $pagination->GetPageLinks();
            $forumtrad = $pagination->GetSQLRows();
            foreach ($forumtrad as $trad) {
                echo '<tr><td><a href="nyforum.php?trad=' . $trad['id'] . '">' . $trad['tema'] . '</a></td><td>' . user($trad['uid']) . '</td><td>' . date("H:i:s | d-m-Y",
                        $trad['time']) . '</td><td>' . user($trad['lastusr']) . '</td></tr>';
            }
            echo '<tr><td colspan="5">' . $pagination_links . '</td></tr>';
            ?>
        </table>
        <?php
    }
}
if (isset($_GET['trad'])) {
    $trad = $db->escape($_GET['trad']);
    $start = $db->query("SELECT * FROM `forum` WHERE `id` = '$trad'");
    $hent = $db->fetch_object($start);
    if ($hent->type == 5 && $hent->famid != $obj->family) {
        echo feil('Du har ikke tilgang til denne tråden!');
    } else {
        if ($db->num_rows($start) == 0) {
            echo feil('Tråden er slettet, eller finnes ikke.');
        } else {
            ?>
            <form action="nyforum.php?svar=<?= $trad ?>" method="POST">
                <input type="submit" name="svar" value="Besvar Tråden">
            </form>
            <?php
            if ($obj->status == 1 || $obj->status == 2) {
                ?>
                <form action="nyforum.php?slett_trad=<?= $trad ?>" method="POST">
                    <input type="submit" onclick="return confirm('Er du sikker?')" name="slett"
                           value="Slett Tråden">
                </form>
                <form action="nyforum.php?flytt_trad=<?= $trad ?>" method="POST">
                    <input type="submit" name="flytt" value="Flytt Tråden">
                </form>

                <?php
            }
            $if_rediger_trad = "|| <a href=\"nyforum.php?rediger_trad=" . $hent->id . "\">Rediger Tråd</a>";
            $rediger_trad = ($hent->uid == $obj->id) ? $if_rediger_trad : null;
            echo '<div class="forumstart">  
  <div class="object_one"><img src="' . bilde(htmlentities($hent->uid)) . '" height="150" width="170">' . user($hent->uid) . ' ' . $rediger_trad . '</div>
    <div class="object_two" style="max-width: 400px;"><div class="object_three"><b>' . $hent->tema . ' @ ' . date("H:i:s | d-m-Y",
                    $hent->time) . '</b></div></br>' . bbcodes($hent->melding, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
                    1, 1, 0) . '</div>
    </div>';
            $sql = "SELECT * FROM `forumsvar` WHERE `stid` = '$trad' ORDER BY `sid` DESC";
            if ($db->num_rows($db->query($sql)) == 0) {
                echo 'Ingen svar!';
            } else {
                $pagination = new Pagination($db, $sql, 20, 'p');
                $pagination_links = $pagination->GetPageLinks();
                $forumsvar = $pagination->GetSQLRows();
                foreach ($forumsvar as $forum) {
                    $object_four = "<div class=\"object_four\">Sitering av " . user($forum['siteringav']) . " @ " . date("H:i:s | d-m-Y",
                            $forum['stime']) . "</br>" . bbcodes($forum['sitatsvar'], 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
                            1, 1, 1, 1, 1, 0) . "</div>";
                    $if_object_four = ($forum['sitert'] == 1) ? $object_four : null;
                    $rediger = '|| <a href=\"nyforum.php?rediger=' . $forum['sid'] . '\">Rediger</a>';
                    $if_rediger = ($forum['suid'] == $obj->id) ? $rediger : null;
                    echo '<div class="forumstart">  
  <div class="object_one"><img src="' . bilde(htmlentities($forum['suid'])) . '" height="150" width="170">' . user($forum['suid']) . ' || <a href="nyforum.php?siter=' . $forum['sid'] . '">Siter</a> ' . $if_rediger . '</div>
    <div class="object_two" style="max-width: 400px;"><div class="object_three">@ ' . date("H:i:s | d-m-Y",
                            $forum['time']) . '</div>' . $if_object_four . '</br>' . bbcodes($forum['smelding'], 1, 1,
                            1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0) . '</div>
      <!--<div class="redigert">' . $if_redigert . '</div>-->
    </div>';
                }
                echo '<tr><td colspan="5">' . $pagination_links . '</tr></td>';
            }
        }
    }
}
if (isset($_GET['svar'])) {
    if (!is_numeric($_GET['svar'])) {
        echo feil('Feil ID.');
    } else {
        ?>
        <form action="" method="POST">
            <textarea style="width: 540px;margin-left: auto;margin-right: auto;height: 120px;" name="object_one"
                      placeholder="Besvar Tråden"></textarea>
            <input type="submit" name="object_two">
        </form>
        <?php
        if (isset($_POST['object_two'])) {
            $id = intval($_GET['svar']);
            $object_one = $db->escape($_POST['object_one']);
            $db->query("INSERT INTO `forumsvar` 
    (`sid`, `suid`, `stid`, `smelding`, `stime`, `sitatsvar`, `sitert`, `siteringav`, `slettet`, `redigert`, `redigertime`, `time`) VALUES 
    (NULL, '$obj->id', '$id', '$object_one', '1', '0', '0', '0', '0', '0', '0', '" . time() . "')");
            $db->query("UPDATE `forum` SET `lasttime` = '" . time() . "',`lastusr` = '$obj->id' WHERE `id` = '$id'");
            header("Location: nyforum.php?trad=$id");
        }
    }
}
if (isset($_GET['opprett'])) {
    $option = (r1() || r2()) ? "<option value=\"4\">Ledelsen</option>" : null;
    $gjeng = ($obj->family != 0) ? "<option value=\"5\">" . famidtoname($obj->family) . " forumet</option>" : null;
    ?>
    <form action="" method="POST">
        <table class="table">
            <tr>
                <th colspan="3">Opprett Tråd!</th>
            </tr>
            <tr>
                <td>Emne:</td>
                <td><input type="text" name="thread"></td>
            </tr>
            <tr>
                <td>Melding:</td>
                <td><textarea style="margin: 2px;width: 503px;height: 168px;" name="object_one"
                              placeholder="Innhold"></textarea></td>
            </tr>
            <tr>
                <td>Type:</td>
                <td><select name="type_forum">
                        <option value="1">Generelt</option>
                        <option value="2">Salg & Søknad</option>
                        <option value="3">Offtopic</option>
                        <?= $option ?>
                        <?= $gjeng ?>
                    </select>
            </tr>
            </td>
            <tr>
                <td><input type="submit" name="object_two"></td>
            </tr>
        </table>
    </form>
    <?php
    if (isset($_POST['object_two'])) {
        $tema = $db->escape($_POST['thread']);
        $id = intval($_GET['opprett']);
        $object_one = $db->escape($_POST['object_one']);
        $type = $db->escape($_POST['type_forum']);
        if ($_POST['type_forum'] == '5') {
            $db->query("INSERT INTO `mafia_no_net`.`forum` (`id`, `tema`, `uid`, `melding`, `dato`, `time`, `lasttime`, `type`, `suid`, `slettet`, `merkslett`,`famid`) 
    VALUES (NULL, '$tema', '$obj->id', '$object_one', UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), '$type', NULL, '0', '0','$obj->family');");
        } else {
            $db->query("INSERT INTO `mafia_no_net`.`forum` (`id`, `tema`, `uid`, `melding`, `dato`, `time`, `lasttime`, `type`, `suid`, `slettet`, `merkslett`,`famid`) 
    VALUES (NULL, '$tema', '$obj->id', '$object_one', UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), UNIX_TIMESTAMP(), '$type', NULL, '0', '0','0');");
        }
        header("Location: nyforum.php?type=$type");

    }
}
if (isset($_GET['siter'])) {
    $id = $db->escape($_GET['siter']);
    $hm = $db->query("SELECT * FROM `forumsvar` WHERE `sid` = '$id'");
    if ($db->num_rows($hm) == 0) {
        echo feil('Ingen sitering tilgjengelig.');
    } else {
        ?>
        <form action="" method="POST">
            <textarea style="width: 540px;margin-left: auto;margin-right: auto;height: 120px;" name="object_one"
                      placeholder="Sitering"></textarea>
            <input type="submit" name="object_two">
        </form>
        <?php
        if (isset($_POST['object_two'])) {
            $sitid = $db->escape($_GET['siter']);
            $obje = $db->escape($_POST['object_one']);
            $sitatsvar = $db->query("SELECT * FROM `forumsvar` WHERE `sid` = '$sitid'");
            $sitsvar_fetch = $db->fetch_object($sitatsvar);
            $db->query("INSERT INTO `forumsvar` 
    (`sid`, `suid`, `stid`, `smelding`, `stime`, `sitatsvar`, `sitert`, `siteringav`, `slettet`, `redigert`, `redigertime`, `time`) VALUES 
    (NULL, '$obj->id', '$sitsvar_fetch->stid', '$obje', '$sitsvar_fetch->time', '$sitsvar_fetch->smelding', '1', '$sitsvar_fetch->suid', '0', '0', '0', '" . time() . "')");
            header("Location: nyforum.php?trad=$sitsvar_fetch->stid");
        }
    }
}
if (isset($_GET['slett_trad'])) {
    if (r1() || r2()) {
        $id = $db->escape($_GET['slett_trad']);
        if ($obj->status >= 2) {
            noaccess();
        } else {
            $query = $db->query("SELECT * FROM `forum` WHERE `id` = '$id' AND `slettet` = '0'");
            $fetch = $db->fetch_object($query);
            if ($db->num_rows($query) == 0) {
                echo feil('Tråden er allerede slettet!');
            } else {
                $db->query("UPDATE `forum` SET `slettet` = '1' WHERE `id` = '$id' LIMIT 1");
                header("Location: nyforum.php?type=$fetch->type");
            }
        }
    } else {
        noaccess();
    }
}

if (isset($_GET['rediger'])) {
    $trad_id = $db->escape($_GET['rediger']);
    $sjekk = $db->query("SELECT * FROM `forumsvar` WHERE `sid` = '$trad_id'");
    $sjekk_fetch = $db->fetch_object($sjekk);
    if ($sjekk_fetch->rediger >= 2) {
        echo feil('Du har redigert posten din 2 ganger. Du kan ikke redigere flere ganger nå!');
    } else { // Hvor mange ganger man kan redigere.
        $id = $db->escape($_GET['rediger']);
        $tradid = $db->escape($_GET['trad']);
        $query = $db->query("SELECT * FROM `forumsvar` WHERE `sid` = '$id' AND `suid` = '$obj->id'");
        if ($db->num_rows($query) == 0) {
            echo feil('Dette er ikke din post!');
        } else {
            $fetch = $db->fetch_object($query);
            ?>
            <form action="" method="POST">
                <textarea style="width: 540px;margin-left: auto;margin-right: auto;height: 120px;" name="rediger_one"
                          placeholder="Sitering"><?= $fetch->smelding ?></textarea>
                <input type="submit" name="rediger_two">
            </form>
            <?php
            if (isset($_POST['rediger_two'])) {
                $trad_id = $db->escape($_GET['rediger']);
                $sjekk = $db->query("SELECT * FROM `forumsvar` WHERE `sid` = '$trad_id'");
                $sjekk_fetch = $db->fetch_object($sjekk);
                $rediger_one = $db->escape($_POST['rediger_one']);
                $db->query("UPDATE `forumsvar` SET `smelding` = '$rediger_one',`rediger` = (`rediger` + 1) WHERE `sid` = '$trad_id' AND `suid` = '$obj->id' LIMIT 1");
                header("Location: nyforum.php?trad=$sjekk_fetch->stid");
            }
        }
    }
}
if (isset($_GET['rediger_trad'])) {
    $id_trad = $db->escape($_GET['rediger_trad']);
    $query = $db->query("SELECT * FROM `forum` WHERE `id` = '$id_trad' AND `slettet` = '0' AND `uid` = '$obj->id'");
    if ($db->num_rows($query) == 0) {
        echo feil('Ingen tråd funnet.');
    } else {
        $object_fetch = $db->fetch_object();
        if ($object_fetch->redigert >= 1) {
            echo feil('Du har allerede redigert denne posten 1 gang.');
        } else {
            ?>
            <form action="" method="POST">
                <table class="table">
                    Tema: <input type="text" name="title" value="<?= $object_fetch->tema ?>">
                    <tr>
                        <td><textarea style="width: 540px;margin-left: auto;margin-right: auto;height: 120px;"
                                      name="rediger_one" placeholder="Sitering"><?= $object_fetch->melding ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td><input type="submit" name="rediger_two"></td>
                    </tr>
                </table>
            </form>
            <?php
            if (isset($_POST['rediger_two'])) {
                $id = $db->escape($_POST['title']);
                $rediger_one = $db->escape($_POST['rediger_one']);
                $db->query("UPDATE `forum` SET `melding` = '$rediger_one',`tema` = '$id',`redigert` = (`redigert` + 1) WHERE `id` = '$id_trad' LIMIT 1");
                echo lykket('Du har endret tråden!');
                $query = $db->query("SELECT * FROM `forum` WHERE `id` = '$id_trad' AND `slettet` = '0' AND `uid` = '$obj->id'");
                $object = $db->fetch_object();
                header("Location: nyforum.php?trad=$object->id");
            }
        }
    }
}
if (isset($_GET['slett_svar'])) {
    if (r1() || r2()) {
        $id = $db->escape($_GET['slett_svar']);
        $query = $db->query("SELECT * FROM `forumsvar` WHERE `sid` = '$id'");
        if ($db->num_rows($query) == 0) {
            echo feil('Allerede slettet? Iallefall.. fant ingen svar med den id�n...');
        } else {
            $db->query("UPDATE `forumsvar` SET `slettet` = '1' WHERE `sid` = '$id' LIMIT 1");
        }
    } else {
        noaccess();
    }
}
if (isset($_GET['flytt_trad'])) {
    if (r1() || r2()) {
        $flyttid = $db->escape($_GET['flytt_trad']);
        $query = $db->query("SELECT * FROM `forum` WHERE `id` = '$flyttid'");
        if ($db->num_rows($query) == 0) {
            echo feil('Ingen tråder funnet med oppgitt id.');
        } else {
            if (isset($_POST['senderen'])) {
                /*NOTE TO SELF:
                 * 1: Generelt
                 * 2: Salg Søknad
                 * 3: Offtopic
                 * 4: Ledelsen*/
                $value = $db->escape($_POST['value']);
                $trad_id = $db->escape($_GET['flytt_trad']);
                $db->query("UPDATE `forum` SET `type` = '$value' WHERE `id` = '$trad_id' LIMIT 1");
                echo lykket('Tråden ble flyttet til ' . $forums[$value]['navn'] . '');
            }
            $ta = $db->fetch_object($query);
            $ledelsen = (r1() || r2()) ? "<option value=\"4\">Ledelsen Forumet</option>" : null;
            ?>
            <form action="" method="POST">
                <table class="table">
                    <th colspan="3">Flytt tråd!</th>
                    <tr>
                        <td>Trådnavn</td>
                        <td>Trådstarter</td>
                        <td>Plassert I</td>
                    </tr>
                    <tr>
                        <td><?= $ta->tema ?></td>
                        <td><?= user($ta->uid) ?></td>
                        <td><?= $forums[$ta->type]['navn'] ?></td>
                    </tr>
                    <tr>
                        <td colspan="3"><?= bbcodes($ta->melding, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1,
                                0) ?></td>
                    </tr>
                    Flytt denne tråden til: <select name="value">
                        <option value="1">Generelt</option>
                        <option value="2">Salg & Søknad</option>
                        <option value="3">Offtopic</option>
                        <?= $ledelsen ?>
                    </select>
                    <input type="submit" name="senderen" value="Flytt tråden!">
                </table>
            </form>
            <?php
        }

    } else {
        noaccess();
    }
}
endpage();
?>
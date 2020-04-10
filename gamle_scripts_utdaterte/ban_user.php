<?php
include("core.php");
if (r1() || r2()) {
    startpage("Modkill spiller")
    ?>
    <h1>Modkill spiller</h1>
    <p>Det skal helst legges inn et grunnlag for hvorfor en brukerkonto bannes.</p>
    <?php
    if (isset($_POST['bruker']) && isset($_POST['grunn']) && isset($_POST['valg'])) {
        $user = $db->escape($_POST['bruker']);
        $valg = $db->escape($_POST['valg']);
        $grunn = $db->escape($_POST['grunn']);
        $fet = user_exists($user, 2);
        if ($fet) {
            if ($fet->status == 1 && !r1()) {
                feil("Du kan ikke banne en administrator!");
            } else {
                if ($valg == 1) {
                    if ($db->query("select * from `banlog` where uid = '{$fet->id}' and active = '1'
order by id desc limit 1")) {
                        if ($db->num_rows() == 1) {
                            echo warning('Brukeren er allerede bannet. Mente du &aring; fjerne ban p&aring; brukerkontoen?');
                        } else {
                            if ($db->query("INSERT INTO `banlog`(`uid`,`timestamp`,`reason`,`banner`) 
VALUES('" . $fet->id . "',UNIX_TIMESTAMP(),'$grunn','" . $obj->id . "')")) {
                                if ($db->affected_rows() == 1) {
                                    echo lykket('Spilleren har blitt modkillet, og kan ikke lengre logge inn!');
                                } else {
                                    echo feil('Kunne ikke legge inn rad i databasen...');
                                }
                            } else {
                                echo feil('Kunne ikke banne spiller. Se loggen for databasen.');
                            }
                        }
                    } else {
                        echo feil('Kunne ikke sp&oslash;rre tabellen om brukeren allerede er bannet!');
                    }
                } elseif ($valg == 2) {
                    if ($db->query("select * from `banlog` 
where uid = '{$fet->id}' and active = '1' order by id desc limit 1")) {
                        if ($db->num_rows() === 1) {
                            $banlog = $db->fetch_object();
                            if ($db->query("update `banlog` set active = '0' where id = '{$banlog->id}' and active = '1'")) {
                                echo lykket('Fjernet ban fra ' . user($banlog->uid));
                            } else {
                                echo feil('Kunne ikke fjerne ban fra brukerkonto, sjekk databaseloggen.');
                            }
                        } else {
                            echo info('Det var ingen brukerkonto som var bannet med den ID.');
                        }
                    } else {
                        echo feil('Kunne ikke sp&oslash;rre tabellen om bruker er bannet, sjekk logg for databasen');
                    }
                } else {
                    echo feil('Ukjent valg... Pr&oslash;v igjen.');
                }
            }
        } else {
            echo feil('Kunne ikke finne bruker med brukernavnet: ' . htmlentities($user));
        }
    }
    if (isset($_GET['kill'])) {
        $kill = $db->escape($_GET['kill']);
        $uid = user_exists($kill, 2);
        if (!$uid) {
            echo warning('Det var ingen bruker med brukernavnet ' . htmlentities($_GET['kill']));
            $uname = null;
        } else {
            $uname = $uid->user;
        }
    }
    if (isset($_GET['unban'])) {
        $ban2 = " checked";
        $ban1 = null;
    } else {
        $ban2 = null;
        $ban1 = " checked";
    }
    ?>
    <form method="post" action="">
        <table class="table">
            <tr>
                <th colspan="2">Modkill / gjenoppliv spiller</th>
            </tr>
            <tr>
                <td>Bruker:</td>
                <td><input type="text" value="<?= $uname; ?>" autofocus name="bruker"></br></td>
            </tr>
            <tr>
                <td>Grunn:</td>
                <td><textarea name="grunn" style="width:100%;height: 100px;" placeholder="Skriv grunnlag her.
Kan redigeres i ettertid om n&oslash;dvendig."></textarea></td>
            </tr>
            <tr>
                <td>Valg:</td>
                <td>
                    <input type="radio" name="valg" value="1"<?= $ban1; ?>>Ban bruker<br>
                    <input type="radio" name="valg" value="2"<?= $ban2; ?>>Fjern ban fra bruker
                </td>
            </tr>
        </table>
        <input type="submit" value="Utf&oslash;r!"></form>
    <?php
} else {
    noaccess();
}
endpage();

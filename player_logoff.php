<?php

include("core.php");
if (!r1()) {
    startpage("Ingen tilgang");
    noaccess();
} else {
    startpage("Logg ut en spiller");
    ?>
    <h1>Logg ut en spiller!</h1>
    <p>Ikke misbruk denne funksjonen da det vil bli en irritasjon til en spiller når brukt i
        feil tilfelle! Eneste
        tilfellene man kan bruke denne funksjonen er når det er en spiller som ødelegger
        for andre. Og
        lignende situasjoner.</p>
    <?php
    if (isset($_GET['usr'])) {
        echo '<p><a href="player_logoff.php">Tilbake!</a></p>';
        $us = $_GET['usr'];
        $s = $db->prepare("SELECT count(*) FROM `users` WHERE `user` = ?");
        $s->execute([$us]);
        if ($s->fetchColumn() == 1) {
            $p = $db->prepare("SELECT * FROM `users` WHERE `user` = ?");
            $p->execute([$us]);
            $u = $p->fetchObject();
            $tid = time() - $u->lastactive;
            if (isset($_POST['utlogg'])) {
                $logout = $db->prepare("UPDATE `users` SET `forceout` = '1' WHERE `id` = ? and forceout = '0'");
                if ($logout->execute([$u->id])) {
                    if ($logout->rowCount() == 1) {
                        echo lykket('Neste gang ' . status($u->user) . ' oppdaterer siden blir de logget ut!');
                    } else {
                        echo warning(
                            'Det er mulig ' . status($u->user) . ' allerede har blitt satt til å logges ut neste gang.'
                        );
                    }
                }
            }
            $user = $u->user;
            echo <<<HTML
    <form method="post" action="player_logoff.php?usr=$user">
    Sist aktiv: <span id="utid"></span><script type="text/javascript">teller($tid,"utid",false,"opp");</script>
    <input type="submit" name="utlogg" value="Logg ut spilleren!">
    </form>
HTML;
        } else {
            echo warning('Spilleren finnes ikke! Prøv igjen!');
        }
    } else {
        ?>
        <form method="get" action="">
            Brukernavn: <input type="text" name="usr"><br>
            <input type="submit" value="Sjekk spiller!">
        </form>
        <?php
    }
}
endpage();

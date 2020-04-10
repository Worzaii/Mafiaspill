<?php
include("core.php");
if (!r1()) {
    startpage("Ingen tilgang");
    noaccess();
    endpage();
    die();
}
startpage("Logg ut en spiller");
?>
    <h1>Logg ut en spiller!</h1>
    <p>Ikke misbruk denne funksjonen da det vil bli en irritasjon til en spiller n&aring;r brukt i feil tilfelle! Eneste
        tilfellene man kan bruke denne funksjonen er n&aring;r det er en spiller som &oslash;delegger for andre. Og
        lignende situasjoner.</p>
<?php
if (isset($_GET['usr'])) {
    echo '<p><a href="player_logoff.php">Tilbake!</a></p>';
    $us = $db->escape($_GET['usr']);
    $s = $db->query("SELECT * FROM `users` WHERE `user` = '$us'");
    if ($db->num_rows() == 1) {
        $u = $db->fetch_object();
        $tid = time() - $u->lastactive;
        if (isset($_POST['utlogg'])) {
            if ($db->query("UPDATE `users` SET `forceout` = '1' WHERE `id` = '{$u->id}'")) {
                echo lykket('Neste gang ' . status($u->user) . ' oppdaterer siden blir personen logget ut!');
            }
        }
        echo '
    <form method="post" action="player_logoff.php?usr=' . $u->user . '">
    Sist aktiv: <span id="utid"></span><script>teller(' . $tid . ',"utid",false,"opp";)</script>
    <input type="submit" name="utlogg" value="Logg ut spilleren!">
    </form>
    ';
    } else {
        echo feil('Spilleren finnes ikke! Pr&oslash;v igjen:');
    }
} else {
    ?>
    <form method="get" action="">
        Brukernavn: <input type="text" name="usr"><br>
        <input type="submit" value="Sjekk spiller!">
    </form>
    <?php
}
endpage();

<?php

include("core.php");
startpage("Nyheter");
?>
    <h1>Nyheter</h1>
<?php
if (r1() || r2()) {
    echo '<p class="button2"><a href="publiser.php">Skriv en ny nyhet!</a></p><p class="button2"><a href="nyhetspanel.php">Behandle nyheter!</a></p>';
}
/** @var PDO $db */
/** @var User $obj */
try {
    $np = $db->prepare(
        "SELECT COUNT(*) as `numrows` FROM `news` WHERE `showing` = '1' AND `userlevel` >= ? ORDER BY `id` DESC LIMIT 0,10"
    );
    $np->execute([$obj->status]);
    $count = $np->fetchColumn();
    if ($count == 0) {
        echo info('Ingen nyheter er publisert!');
    } else {
        echo <<<HTML
<div class="nyheter">
    <table class="ny1">
        <tr>
            <td>
HTML;

        $news = $db->prepare(
            "SELECT * FROM `news` WHERE `showing` = '1' AND `userlevel` >= ? ORDER BY `id` DESC LIMIT 0,10"
        );
        $news->execute([$obj->status]);
        while ($r = $news->fetchObject()) {
            $statuss = null;
            print '
            <table class="';
            if ($r->id % 2) {
                print 'news1">';
            } else {
                print 'news2">';
            }
            if ($r->userlevel == 1) {
                $statuss = 'news_admin';
            } elseif ($r->userlevel == 2) {
                $statuss = 'news_mod';
            }
            $bbc = new BBcodes($r->text);
            $newres = $bbc->applyAllBBcodes();
            /*$q = $db->query("SELECT * FROM `users` WHERE `user` = '$r->author'");
            $f = $db->fetch_object($q);*/
            print '
            <tr>
            <td class="linkstyle ' . $statuss . '"><b>' . htmlentities(
                    $r->title,
                    ENT_NOQUOTES | ENT_HTML401,
                    'UTF-8'
                )
                . '</b> skrevet av ' . status($r->author) . ' <div class="innlegsdato">' . date(
                    "H:i:s | d.m.Y",
                    $r->timestamp
                ) . '</div></td>
            </tr>
            <tr>
            <td colspan="2">
            ' . $newres . '
            </td>
            </tr>
            </table>
            ';
        }
        echo <<<HTML
</td>
</tr>
</table>
</div>
HTML;
    }
} catch (Exception $e) {
    error_log("Noe gikk galt i nyheter.php: " . $e->getMessage());
    echo feil("Kunne ikke laste nyheter, sjekk logg!");
}
endpage();

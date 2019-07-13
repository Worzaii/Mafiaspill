<?php
include("core.php");
startpage("Nyheter");
?>
<h1>Nyheter</h1>
<div class="nyheter">
    <table class="ny1">
        <tr>
            <td>
                <?php
                if ($obj->status == 1 || $obj->status == 2) {
                    echo '<p class="button2"><a href="publiser.php">Skriv en ny nyhet!</a></p>';
                }
                $sql = $db->query("SELECT * FROM `news` WHERE `showing` = '1' AND `userlevel` >= '" . $obj->status . "' ORDER BY `id` DESC LIMIT 0,10");
                if ($db->num_rows() == 0) {
                    echo '<p class="feil">Ingen nyheter er publisert!</p>';
                } elseif ($db->num_rows() >= 1) {
                    while ($r = mysqli_fetch_object($sql)) {
                        $statuss = null;
                        print '
            <table class="';
                        if ($r->id % 2) {
                            print 'news1">';
                        } else {
                            print 'news2">';
                        }
                        if ($r->userlevel == 1) {
                            $statuss = ' style="background:#800;"';
                        } elseif ($r->userlevel == 2) {
                            $statuss = ' style="background:#0052A5"';
                        }

                        $newres = bbcodes($r->text, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);
                        $q = $db->query("SELECT * FROM `users` WHERE `user` = '$r->author'");
                        $f = $db->fetch_object($q);
                        print '
            <tr>
            <td class="linkstyle"' . $statuss . '><b>' . htmlentities($r->title, ENT_NOQUOTES | ENT_HTML401, 'UTF-8')
                            . '</b> skrevet av ' . status($r->author) . '<div class="innlegsdato">' . date("H:i:s | d.m.Y", $r->timestamp) . '</div></td>
            </tr>
            <tr>
            <td colspan="2">
            ' . $newres . '
            </td>
            </tr>
            </table>
            ';
                    }
                }
                ?>
            </td>
        </tr>
    </table>
</div>
<?php
endpage();
?>


